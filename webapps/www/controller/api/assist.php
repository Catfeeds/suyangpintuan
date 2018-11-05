<?php

/**
 * 助力接口
 */
class assist extends Lowxp {

	function __construct() {
		parent::__construct();
		$isLogin = isset($_SESSION['mid']);
		if ($isLogin) {
			$this->load->model('member');
			$this->load->Model('linkage');
			$this->load->model("refund");
			$this->load->model('order');
			$this->load->model("goods");
			$this->load->model('assist');
			$this->memberinfo = $this->member->member_info($_SESSION['mid']);
			defined('MID') || define('MID', $_SESSION['mid']);
			defined('USER') || define('USER', $this->memberinfo['username']);
		} else {
			$this->api_result(array('flag' => false, 'msg' => '请登录', 'code' => 400001));
		}
	}

	function index() {

		$option['page']  = empty($_GET['page']) ? 1 : $_GET['page'];
		$option['size']  = empty($_GET['size']) ? 10 : $_GET['size'];
		$option['where'] = " and status=1 and stock>0 ";
		$list            = $this->assist->getList($option);

		if ($list) {
			foreach ($list as $key => $val) {
				if ($val['goods_sp_val']) {
					$val['goods_sp_val'] = json_decode($val['goods_sp_val'], true);
					$sp_ids              = join(',', array_keys($val['goods_sp_val']));
					$sp_arr              = $this->db->select("select * from ###_goods_spec where id in ($sp_ids)");

					$specs = $this->db->select('SELECT spec,price,thumb FROM `###_goods_item` WHERE goods_id=' . $val['goods_id']);

					$specs               = api_imgurl($specs, array('thumb'));
					$list[$key]['specs'] = $specs;

					$sp_val = array_combine(array_column($sp_arr, 'id'), array_column($sp_arr, "name"));
					foreach ($val['goods_sp_val'] as $k => $v) {
						$list[$key]['sp_vals'][$sp_val[$k]] = $v;
					}
				}
				$list[$key]['log_id'] = $this->db->getstr("select id from ###_assist_log where assist_id={$val['id']} and e_time>" . RUN_TIME . " and status=0 and mid=" . MID, "id");

			}
		}

		$data['list'] = $list;

		//获取收货地址
		$addresses            = $this->member->member_address(MID, 1);
		$data['address_list'] = empty($addresses) ? array() : $addresses;

		$data['merid'] = MERID;

		$this->api_result(array('data' => $data));
	}

	//生成助力记录
	function assist_apply() {
		$post['address_id'] = $_POST['address_id'];
		$post['assist_id']  = $_POST['assist_id'];
		$post['spec']       = $_POST['spec'];
		$res                = $this->assist->assist_log_save($post);

		$this->api_result(array('flag' => empty($res['code']), 'msg' => $res['message'], 'code' => $res['code'], 'data' => isset($res['id']) ? $res['id'] : 0));
	}

	function show() {

		$id = get('id');
		if (!$id) {
			$this->api_result(array('flag' => false, 'msg' => '活动不存在'));
		}

		$data['assist'] = $this->assist->getOne($id);
		$data['row']    = $data['assist']['goods'];

		//助力记录
		$data['log'] = $this->db->get("select id,e_time from ###_assist_log where assist_id={$id} and status=0 and mid=" . MID);
		if ($data['log']) {
			$data['end_time'] = $data['log']['e_time'] - RUN_TIME;
		}

		if ($data['row']['sp_val']) {
			$data['row']['sp_val'] = json_decode($data['row']['sp_val'], true);
			$sp_ids                = join(',', array_keys($data['row']['sp_val']));
			$sp_arr                = $this->db->select("select * from ###_goods_spec where id in ($sp_ids)");
			$sp_val                = array_combine(array_column($sp_arr, 'id'), array_column($sp_arr, "name"));
			foreach ($data['row']['sp_val'] as $k => $v) {
				$data['sp_vals'][$sp_val[$k]] = $v;
			}
			$data['spec'] = array();
			$spec         = trim($_GET['spec']);
			if ($spec) {
				$data['spec'] = explode("-", $spec);
			}

		}

		//获取商家信息
		if ($data['row']['sid'] > 0) {
			$this->load->model("business");
			$store['info'] = $this->business->get($data['row']['sid'], "name,logo,fav_num,kf_online");
			//统计商品数量
			$store['goods_total'] = $this->db->getstr("select count(1) as num from ###_goods where is_sale=1 and sid=" . $data['row']['sid'], "num");
			//统计销量
			$store['sell_total'] = $this->db->getstr("select sum(sell+sales_num) as num from ###_goods where is_sale=1 and sid=" . $data['row']['sid'], "num");
			//判断是否已关注
			$store['is_fav'] = $this->db->get("select 1 from ###_business_fav where sid={$data['row']['sid']} and mid=" . MID);
			$data['store']   = $store;
		}

		//获取收货地址
		$addresses            = $this->member->member_address(MID, 1);
		$data['address_list'] = empty($addresses) ? array() : $addresses;

		//echo "<pre>";print_r($data);exit;
		$this->smarty->assign('data', $data);
		if ($data['row']['keywords']) {
			$data['row']['title'] = $data['row']['keywords'];
		}

		$this->api_result(array('data' => $data));
	}

	//我的助力
	function log_list($page = 1) {

		$option['page']  = $page;
		$option['where'] = " and mid=" . MID;
		$list            = $this->assist->getLogList($option);

		$data['list'] = $list;
		if ($data['list']) {
			foreach ($data['list'] as $k => $v) {
				$data['list'][$k]['help_list'] = $this->db->select("select a.*,m.username from ###_assist_log_help as a left join ###_member as m on a.mid=m.mid where a.log_id={$v['id']}");
				if ($v['order_id'] > 0) {
					$data['list'][$k]['order_sn'] = $this->db->getstr("select order_sn from ###_goods_order where id={$v['order_id']}", "order_sn");
				}
			}
		}

		$this->api_result(array('data' => $data));
	}

}