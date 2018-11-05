<?php
/**
 * 套餐商品
 * ============================================================================
 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。
 * 网站地址: http://www.lnest.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 *
 */

class package extends Lowxp {
	function __construct() {
		$this->btnMenu = array(
			0 => array('url' => '#!package/index', 'name' => '套餐商品'),
			1 => array('url' => '#!package/edit?com=xshow|添加套餐', 'name' => '添加套餐商品'),
		);
		parent::__construct();
		$this->load->model('auction');
	}

	function index($page = 1) {
		#检索
		$conds     = $this->getConds();
		$condition = " WHERE act_type='" . CART_PACK . "' ";
		$condition .= count($conds['where']) ? " AND " . implode(' AND ', $conds['where']) : '';
		$orderby = $conds['order'];

		#分页
		$this->load->model('page');
		$_GET['page'] = intval($page);
		$this->page->set_vars(array('per' => (int) $this->common['page_listrows']));

		#数据集
		$sql          = "SELECT a.* FROM " . $this->auction->baseTable . " AS a " . $condition . $orderby;
		$data['list'] = $this->page->hashQuery($sql)->result_array();

		$this->load->model('goods');
		foreach ($data['list'] as $k => $v) {
			$goods_list         = !empty($v['goods_list']) ? json_decode($v['goods_list'], true) : array();
			$goods_list         = is_array($goods_list) ? $goods_list : array();
			$v['goods_list']    = $goods_list;
			$v['act_type_name'] = $this->auction->actTypes[$v['act_type']]['title'];
			$v['status']        = $this->auction->status($v);
			$v['start_time']    = date('Y-m-d H:i:s', $v['start_time']);
			$v['end_time']      = date('Y-m-d H:i:s', $v['end_time']);
			$v['act_thumb']     = isset($v['act_thumb']) ? json_decode($v['act_thumb'], true) : array();
			$v['ext_info']      = unserialize($v['ext_info']);
			$data['list'][$k]   = $v;
		}

		$this->smarty->assign('data', $data);
		$this->smarty->display('manage/package/list.html');
	}

	//创建/更新
	function edit() {
		//提交
		if (isset($_POST['Submit'])) {
			$res = $this->auction->save();

			if (isset($res['code']) && $res['code'] == 0) {
				$this->tip($res['message'], array('inIframe' => true));
				$this->exeJs("parent.com.xhide();parent.main.refresh()");
			} else {
				$this->tip($res['message'], array('inIframe' => true, 'type' => 1));
			}
			exit;
		}

		$id  = isset($_GET['id']) ? intval($_GET['id']) : '';
		$act = isset($_GET['act']) ? $_GET['act'] : '';
		$row = array();

		//编辑
		if ($id) {
			$row           = $this->db->get("SELECT * FROM " . $this->auction->baseTable . " WHERE act_id=" . $id);
			$row['status'] = $this->auction->status($row);
			$ext_info      = unserialize($row['ext_info']);
			$row           = array_merge($row, $ext_info);
		} else {
			$row = array(
				'act_id'   => 0,
				'act_type' => CART_PACK,
			);
			$act = 'add';
		}

		#商品分类
		$this->load->model('goodscat');
		if ($row['act_type'] == CART_PACK) {
			$category = $this->goodscat->select(CATE_PACK);
			$this->smarty->assign('category', $category);
		} else {
			$select_categorys = $this->goodscat->category_option();
			$this->smarty->assign('select_categorys', $select_categorys);
		}

		//生成图片控件
		$row['act_thumb']  = isset($row['act_thumb']) ? $row['act_thumb'] : '';
		$row['html_thumb'] = $this->form->files('act_thumb', $row['act_thumb']);

		$row['act_name']   = isset($row['act_name']) ? $row['act_name'] : '';
		$row['start_time'] = isset($row['start_time']) ? $row['start_time'] : time();
		$row['end_time']   = isset($row['end_time']) ? $row['end_time'] : time();

		$this->smarty->assign('row', $row);
		$this->smarty->assign('act', $act);
		$this->smarty->display('manage/package/edit.html');
	}

	//删除
	function del() {
		$id = (int) $_POST['id'];
		if (!$id) {
			die;
		}

		$row = $this->db->get("SELECT * FROM " . $this->auction->baseTable . " WHERE act_id=" . $id);
		if ($row['act_sell'] > 0) {
			$this->tip('已经有人购买，禁止被删除！', array('type' => 2));die;
		}

		admin_log('删除套餐商品：' . $row['act_name']);
		$this->db->delete($this->auction->baseTable, array('act_id' => $id));
		$this->tip('删除成功', array('type' => 1));
	}

	/** 获取过滤条件
	 * @return array
	 */
	private function getConds() {
		$where = array();
		$order = ' ORDER BY ';

		#关键词搜索
		$array = array('k', 'q');
		foreach ($array as $v) {
			if (!isset($_GET[$v])) {
				$_GET[$v] = '';
			}

		}
		if (!empty($_GET['q'])) {
			$where[] = "a." . trim($_GET['k']) . " LIKE '%" . addslashes($_GET['q']) . "%'";
		}

		$array = array('status', 'act_type');
		foreach ($array as $v) {
			if (!isset($_GET[$v])) {
				$_GET[$v] = '';
			}

		}
		if ($_GET['status'] !== '') {
			$status_sql = $this->auction->status_sql($_GET['status'], 'a.');
			if ($status_sql) {
				$where[] = $status_sql;
			}
		}
		if ($_GET['act_type']) {
			$where[] = "a.act_type='" . $_GET['act_type'] . "'";
		}

		#快速排序
		$order .= isset($_GET['sortby']) ? $_GET['sortby'] : 'act_id';
		$order .= ' ';
		$order .= isset($_GET['sortorder']) ? $_GET['sortorder'] : 'DESC';

		$this->smarty->assign($_GET);
		return array('where' => $where, 'order' => $order);
	}

	//获取套餐商品
	function changGoods() {
		$act_id = isset($_POST['act_id']) ? intval($_POST['act_id']) : 0;

		$auction = $act_id ? $this->db->get("SELECT * FROM ###_goods_activity WHERE act_id=" . $act_id) : array();
		$setup   = !empty($auction['goods_list']) ? json_decode($auction['goods_list'], true) : array();
		$setup   = is_array($setup) ? $setup : array();
		$setup   = array_merge(array(
			array('cat_id' => '', 'cat_name' => '', 'cat_number' => '', 'cat_unit'),
		), $setup);

		$this->smarty->assign('setup', $setup);
		$this->smarty->assign('act_id', $act_id);
		$this->smarty->display('manage/package/goods.html');
	}

}