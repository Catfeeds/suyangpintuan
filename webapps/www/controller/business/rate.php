<?php

/**

 * ZZCMS 评价管理

 * ============================================================================

 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。

 * 网站地址: http://www.lnest.com；

 * ----------------------------------------------------------------------------

 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和

 * 使用；不允许对程序代码以任何形式任何目的的再发布。

 */

class rate extends Lowxp {

	function __construct() {

		parent::__construct();

	}

	function index($page = 1) {

		#分页

		$this->load->model('page');

		$_GET['page'] = intval($page);

		$this->page->set_vars(array('per' => (int) $this->common['page_listrows']));

		$where     = ' and sid='.BID;
		$_GET['q'] = isset($_GET['q']) ? $_GET['q'] : '';
		$_GET['k'] = isset($_GET['k']) ? $_GET['k'] : '';
		if ($_GET['q']) {

			$k = trim($_GET['k']);

			$q = trim($_GET['q']);

			$where .= "AND $k LIKE '%$q%' ";

		}

		if (isset($_GET['state']) && !empty($_GET['state'])) {

			$is_show = intval($_GET['state']) == 99 ? 0 : 1;

			$where .= "AND state=$state ";

		}

		$sort = !empty($_GET['sort']) ? trim($_GET['sort']) : 'listorder';

		$order = !empty($_GET['sort']) ? trim($_GET['order']) : 'DESC';

		#数据集

		$sql = "SELECT * FROM ###_goods_rate WHERE mid <> 0 $where ORDER BY $sort $order";

		$list = $this->page->hashQuery($sql)->result_array();

		foreach ($list as $k => $v) {
			$sql                   = "select username from ###_member where mid='{$v['mid']}' ";
			$temp_m                = $this->db->get($sql);
			$list[$k]['username']  = $temp_m['username'];
			$sql                   = "select name from ###_goods where id = '{$v['good_id']}'";
			$temp_g                = $this->db->get($sql);
			$list[$k]['good_name'] = $temp_g['name'];
		}

		$this->smarty->assign('list', $list);

		$this->smarty->display('business/order/rate_list.html');

	}

	//更新

	function edit() {

		//提交

		if (isset($_POST['Submit'])) {

			$res = $this->yunbuy->saveShare();

			if (isset($res['code']) && $res['code'] == 0) {

				$this->tip($res['message'], array('inIframe' => true));

				$this->exeJs("parent.com.xhide();parent.main.refresh()");

			} else {

				$this->tip($res['message'], array('inIframe' => true, 'type' => 1));

			}

			exit;

		}

		$id = (int) $_GET['id'];

		$row = $this->db->get("SELECT * FROM ###_share WHERE id=" . $id);

		$this->smarty->assign('row', $row);

		$this->smarty->display('business/share/edit.html');

	}

	//删除

	function del($id) {

		$id = (int) $_POST['id'];

		if (!$id) {
			die;
		}

		$this->db->delete('###_goods_rate', array('id' => $id,'sid'=>BID));

		$this->tip('删除成功');

	}

	//设为精华

	function is_rec() {

		$id = (int) $_POST['id'];

		$val = (int) $_POST['val'];

		$this->load->model('setting');

		$site_config = $this->setting->value("'rec_share_db'");

		$row = $this->db->get("SELECT mid,username FROM ###_share WHERE id = '$id'");

		$this->db->update('share', array('is_rec' => $val, 'db_points' => $site_config['rec_share_db']), array('id' => $id));

		if ($site_config['rec_share_db'] && $val) {

			$this->load->model('member');

			$this->member->accountlog('admin', array('mid' => $row['mid'], 'username' => $row['username'], 'db_points' => $site_config['rec_share_db'] - $site_config['share_db'], 'desc' => '晒单被设置为精华奖励云购币'));

		}

		echo 1;

	}

}