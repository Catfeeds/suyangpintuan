<?php
/**
 * 促销活动
 * ============================================================================
 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。
 * 网站地址: http://www.lnest.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 *
 */

class auction extends Lowxp {
	function __construct() {
		$this->btnMenu = array(
                    0 => array('url' => '#!auction/index', 'name' => '促销活动'),
                    1 => array('url' => '#!auction/edit', 'name' => '添加促销活动'),
		);
		parent::__construct();
		$this->load->model('auction');
	}

	function index($page = 1) {
		#检索
		$conds     = $this->getConds();
		$condition = " WHERE 1 ";
		$condition .= count($conds['where']) ? " AND " . implode(' AND ', $conds['where']) : '';
		$orderby = $conds['order'];
               
		#分页
		$this->load->model('page');
		$_GET['page'] = intval($page);
		$this->page->set_vars(array('per' => (int) $this->common['page_listrows']));

		#数据集
		$sql = "SELECT distinct a.act_id,a.*,g.thumb,g.thumbs,g.price FROM " . $this->auction->baseTable . " AS a " .
			"LEFT JOIN ###_goods AS g ON g.id=a.goods_id " . $condition . $orderby;
		$data['list'] = $this->page->hashQuery($sql)->result_array();
                
		$this->load->model('goods');
		foreach ($data['list'] as $k => $v) {
			
			$v        = $this->goods->getThumb($v);
			$v['act_type_name'] = $this->auction->actTypes[$v['act_type']]['title'];
			$v['status']        = $this->auction->status($v);
			$v['start_time']    = date('Y-m-d H:i:s', $v['start_time']);
			$v['end_time']      = date('Y-m-d H:i:s', $v['end_time']);

			#标题加期数
			$v['act_name'] = "<span class='c-gray'>第" . $v['qishu'] . "期-</span>" . $v['act_name'];

			$data['list'][$k] = $v;
		}

		#促销类型
		$actTypes = array(
			CART_KILL => array('id' => CART_KILL, 'title' => '秒杀'),
			CART_SALE => array('id' => CART_SALE, 'title' => '特价'),
			CART_FREE => array('id' => CART_FREE, 'title' => '免费试用'),
			CART_LUCK => array('id' => CART_LUCK, 'title' => '抽奖'),
		);
		$this->smarty->assign('actTypes', $actTypes);

		$this->smarty->assign('data', $data);
		$this->smarty->display('business/auction/list.html');
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

		$id  = isset($_GET['id']) ? $_GET['id'] : 0;
		$act = isset($_GET['act']) ? $_GET['act'] : '';
		$row = array();

		//编辑
		if ($id) {
			$row = $this->auction->get($id);
			//$row = $this->db->get("SELECT * FROM ". $this->auction->baseTable ." WHERE act_id=".$id);
			$row['status'] = $this->auction->status($row);			
		} else {
			$row = array(
				'act_id'   => 0,
				'act_type' => isset($_REQUEST['act_type']) ? intval($_REQUEST['act_type']) : CART_KILL,
			);
			$act = 'add';
		}

		#商品分类
		$row['goods_name'] = isset($row['goods_name']) ? $row['goods_name'] : '';
		$row['act_name']   = isset($row['act_name']) ? $row['act_name'] : '';
		$row['act_desc']   = isset($row['act_desc']) ? $row['act_desc'] : '';
		$row['start_time'] = isset($row['start_time']) ? $row['start_time'] : '';
		$row['end_time']   = isset($row['end_time']) ? $row['end_time'] : '';
		$this->smarty->assign('row', $row);
		$this->smarty->assign('act', $act);
		$this->smarty->display('business/auction/edit.html');
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

		admin_log_sid('删除促销商品：' . $row['act_name']);
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
			if (in_array($_GET['k'], array('goods_id'))) {
				$where[] = "a." . trim($_GET['k']) . " LIKE '" . addslashes($_GET['q']) . "'";
			} else {
				$where[] = "a." . trim($_GET['k']) . " LIKE '%" . addslashes($_GET['q']) . "%'";
			}
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

}