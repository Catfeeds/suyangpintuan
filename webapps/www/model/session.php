<?php

/**
 * session日志
 * 功能点：单点登陆、在线、离线、自动下线
 */
class session_model extends Lowxp_Model {

	public $baseTable = '###_member_login_log';
	public $expire    = 3600; #秒,会员在线周期，超过未刷新页面视为离线且自动下线
	public $expire2   = 3600; #秒,游客在线周期，超过将自动清除

	function __construct() {
		//firefox,上传session Bug
		if (isset($_POST['PHPSESSID']) && !empty($_POST['PHPSESSID'])) {
			session_id($_POST['PHPSESSID']);
		}elseif(isset($_GET['PHPSESSID']) && !empty($_GET['PHPSESSID'])){
            session_id($_GET['PHPSESSID']);
        }
		session_set_cookie_params(0, '/',  Domain);
		session_start();
	}

	#入口执行
	function init() {
		#排除蜘蛛
		if ($this->useragent->is_robot()) {return;}

		#单点登陆实现
		$mid = (isset($_SESSION['mid']) && $_SESSION['mid'] > 0) ? $_SESSION['mid'] : 0;
		if ($mid > 0) {
			$res = $this->db->get("SELECT sesskey,mid,c_time FROM " . $this->baseTable . " WHERE mid='$mid'");
			if (empty($res) || (session_id() != $res['sesskey']) || (time() - $res['c_time']) > $this->expire) {
				// 注意这里检查单点登录状态  不满足条件会清除$_SEESION['mid']
				$this->load->model('member');
				$this->member->logout();
			}
		}

		#创建或更新在线日志
		$mid   = (isset($_SESSION['mid']) && $_SESSION['mid'] > 0) ? $_SESSION['mid'] : 0;
		$where = array();
		if ($mid > 0) {
			$where['mid'] = $mid;
		}

		$this->save($mid, $where);
	}

	#在线
	function login($mid) {
		$this->db->delete($this->baseTable, array('sesskey' => session_id()));
		$res     = $this->db->get("SELECT sesskey,mid FROM " . $this->baseTable . " WHERE mid='$mid'");
		$sesskey = empty($res) ? '' : $res['sesskey'];
		if ($sesskey != session_id()) {
			session_regenerate_id();
		}

		$where = array();
		if ($mid > 0 && $sesskey) {
			$where['mid'] = $mid;
		}

		$this->save($mid, $where);
	}

	#离线
	function logout($mid) {
		$this->db->delete($this->baseTable, array('mid' => $mid));
	}

	#更新
	private function save($mid, $where = '') {
		$this->db->save($this->baseTable, array(
			'sesskey' => session_id(),
			'mid'     => $mid,
			'adminid' => 0,
			'ip'      => getIP(),
			'c_time'  => time(),
		), '', $where);
	}
}