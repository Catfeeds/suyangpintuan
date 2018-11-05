<?php

/**

 * 模板管理

 * ============================================================================

 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。

 * 网站地址: http://www.lnest.com；

 * ----------------------------------------------------------------------------

 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和

 * 使用；不允许对程序代码以任何形式任何目的的再发布。

 *

 */

class templates extends Lowxp {

	function __construct() {

		#按钮

		$this->btnMenu = array(

			0 => array('url' => '#!templates/index', 'name' => '邮箱及短信模板'),

			1 => array('url' => '#!templates/edit?com=xshow|添加模板', 'name' => '添加模板'),

			2 => array('url' => '#!templates/send', 'name' => '发送队列'),

		);

		parent::__construct();

		#加载

		$this->load->model('templates');

	}

	function index($page = 1) {

		#检索

		$conds = $this->getConds();

		#分页

		$this->load->model('page');

		$_GET['page'] = intval($page);

		$this->page->set_vars(array('per' => 30));

		#数据集

		$sql = "SELECT * FROM " . $this->templates->baseTable . $conds['where'] . $conds['order'];

		$data['list'] = $this->page->hashQuery($sql)->result_array();

		foreach ($data['list'] as $k => $v) {

			$data['list'][$k] = $v;

		}

		$this->smarty->assign('data', $data);

		$this->smarty->display('business/templates/list.html');

	}

	//创建/更新

	function edit() {

		//提交

		if (isset($_POST['Submit'])) {

			$res = $this->templates->save();

			if (isset($res['code']) && $res['code'] == 0) {

				$this->tip($res['message'], array('inIframe' => true));

				$this->exeJs("parent.com.xhide();parent.main.refresh()");

			} else {

				$this->tip($res['message'], array('inIframe' => true, 'type' => 1));

			}

			exit;

		}

		$code = isset($_GET['code']) ? trim($_GET['code']) : 0;

		$row = array();

		//编辑

		if ($code) {

			$row = $this->db->get("SELECT * FROM " . $this->templates->baseTable . " WHERE template_code='$code'");

		}

		if (!$code) {
			$this->smarty->assign('btnNo', 1);
		}

		$this->smarty->assign('row', $row);

		$this->smarty->display('business/templates/edit.html');

	}

	/** 获取过滤条件

	 * @return array

	 */

	private function getConds() {

		$where = ' WHERE 1 ';

		$order = ' ORDER BY ';

		#关键词搜索

		$array = array('k', 'q');

		foreach ($array as $v) {

			if (!isset($_GET[$v])) {
				$_GET[$v] = '';
			}

		}

		if (!empty($_GET['q'])) {

			$where .= " AND `" . trim($_GET['k']) . "` LIKE '%" . addslashes($_GET['q']) . "%'";

		}

		$_GET['type'] = isset($_GET['type']) ? $_GET['type'] : 'mail';

		if (!empty($_GET['type'])) {

			$where .= " AND `type`='" . $_GET['type'] . "'";

		}

		#快速排序

		$order .= isset($_GET['sortby']) ? $_GET['sortby'] : 'template_id';

		$order .= ' ';

		$order .= isset($_GET['sortorder']) ? $_GET['sortorder'] : 'DESC';

		$this->smarty->assign($_GET);

		return array('where' => $where, 'order' => $order);

	}

	//删除

	function del() {

		$id = (int) $_POST['id'];

		if (!$id) {
			die;
		}

		$row = $this->db->get("select * from ###_templates where template_id=" . $id);

		if ($row['is_system'] == 0) {

			admin_log_sid('删除模板：' . $row['template_subject']);

			$this->db->delete('###_templates', array('template_id' => $id));

			$this->tip('删除成功', array('type' => 1));

		}

	}

	//发送队列

	function send($page = 1) {

		#检索

		$conds = $this->getCondsSend();

		$condition = " WHERE 1 ";

		$condition .= count($conds) ? " AND " . implode(' AND ', $conds) : '';

		$orderby = " ORDER BY id DESC ";

		#分页

		$this->load->model('page');

		$_GET['page'] = intval($page);

		$this->page->set_vars(array('per' => (int) $this->common['page_listrows']));

		#数据集

		$sql = "SELECT * FROM ###_send " . $condition . $orderby;

		$data['list'] = $this->page->hashQuery($sql)->result_array();

		$data['list'] = $this->db->lJoin($data['list'], 'member', 'mid,username', 'mid', 'mid');

		$this->smarty->assign('btnNo', 2);

		$this->smarty->assign('data', $data);

		$this->smarty->display('business/templates/send.html');

	}

	/** 获取过滤条件

	 * @return array

	 */

	function getCondsSend() {

		$where = array();

		#关键词搜索

		$array = array('k', 'q');

		foreach ($array as $v) {

			if (!isset($_GET[$v])) {
				$_GET[$v] = '';
			}

		}

		if (!empty($_GET['q'])) {

			$where[] = trim($_GET['k']) . " LIKE '%" . addslashes($_GET['q']) . "%'";

		}

		if (isset($_GET['status']) && $_GET['status'] != '') {

			if ($_GET['status'] == 99) {

				$where[] = "status=0";

			} else {

				$where[] = "status=1";

			}

		}

		$this->smarty->assign($_GET);

		return $where;

	}

	//删除

	function del_send() {

		$id = (int) $_POST['id'];

		if (!$id) {
			die;
		}

		admin_log_sid('删除邮件短信发送队列：' . $this->db->getstr("SELECT `user` FROM ###_send WHERE id=" . $id));

		$this->db->delete('###_send', array('id' => $id));

		$this->tip('删除成功', array('type' => 1));

	}

	//批量发送队列

	function sendtpl() {

		if (!isset($_POST['queue'])) {die();}

		set_time_limit(0);

		//队列参数

		$params = array(

			'queue' => 'queue_sendtpl', #队列变量名

			'size'  => 1, #单次执行条数

			'msg'   => '邮件短信全部发送完毕', #执行完毕提示

		);

		//返回数据

		$result = array(

			'error' => 0, #error错误代码

			'head'  => '<a href="javascript:;" onclick="cron.init(\'sendtpl\')">邮件短信发送任务...</a>', #head队列标题

			'msg'   => '', #msg当前进度任务

			'size'  => 0, #size剩余条数

			'count' => 0, #count处理总条数

		);

		$queue = $params['queue'];

		$add = intval($_POST['add']);

		//创建队列

		if ($add == 1) {

			queue_initialize($queue);

			//TODO:获取队列数据...

			$sql = "SELECT * FROM ###_send " .

				"WHERE status=0";

			$list = $this->db->select($sql);

			foreach ($list as $v) {

				queue_enqueue($queue, $v['id']);

			}

			$result['count'] = queue_size($queue);

		}

		//队列执行中...

		if (queue_size($queue) > 0) {

			for ($i = 0; $i < $params['size']; $i++) {

				$id = queue_dequeue($queue);

				$v = $this->db->get("SELECT * FROM ###_send WHERE id=" . $id);

				$w = $this->db->get("SELECT mobile,email FROM ###_member WHERE mid=" . $v['mid']);

				$status = 0;

				if ($v['type'] == 'sms') {

					if ($this->common['sms_open'] == 1 && statusTpl($v['template_code'])) {

						$this->load->library('sms');

						$ret = $this->sms->sendSmsTpl($w['mobile'], $v['template_code'], 0, $v['content']);

						if ($ret === true) {
							$status = 1;
						}

					}

				} elseif ($v['type'] == 'mail') {

					$this->load->library('mail');

					$ret = $this->mail->sendMailTpl($w['email'], $v['template_code'], 0, $v['content']);

					if ($ret === true) {
						$status = 1;
					}

				}

				if ($status == 1) {

					$this->db->save('send', array(

						'status'    => 1,

						'send_time' => time(),

					), '', array('id' => $id));

				}

				//TODO:队列单元执行...

				$result['msg'] .= '<p>发送' . ($v['type'] == 'sms' ? '短信:' . $w['mobile'] : '邮件:' . $w['email']) . '</p>';

			}

			$result['error'] = 0000;

			$result['size'] = queue_size($queue);

		}

		//队列执行完成

		else {

			queue_destroy($queue);

			$result['error'] = 1002;

			$result['msg'] = $params['msg'];

		}

		die(json_encode($result));

	}

}