<?php
/**
 * Name 商品类型管理
 * Class product_adm
 */
class goods_type extends Lowxp {

	function __construct() {
		#按钮
		$this->btnMenu = array(
			0 => array('url' => '#!goods_type/index', 'name' => '商品类型管理'),
			1 => array('url' => '#!goods_type/edit?com=xshow|添加商品类型', 'name' => '添加商品类型'),
		);
		parent::__construct();
		$this->load->model('goodstype');
	}

	/**
	 * 商品类型列表
	 */
	function index($page = 1) {

		#分页
		$this->load->model('page');
		$_GET['page'] = intval($page);
		$this->page->set_vars(array('per' => (int) $this->common['page_listrows']));
		$sql          = "select * from `###_goods_type` ";
		$data['list'] = $this->page->hashQuery($sql)->result_array();
		foreach ($data['list'] as $k => $v) {
			$data['list'][$k]["attribute_num"] = $this->db->getstr("select count(1) as num from ###_attribute where cat_id={$v['cat_id']}");
		}
		$this->smarty->assign('data', $data);
		$this->smarty->display('business/goodstype/list.html');
	}
	/**
	 * 商品类型添加、修改
	 */
	function edit($id = '') {
		if (isset($_POST['Submit'])) {
			$res = $this->goodstype->save();

			if (isset($res['code']) && $res['code'] == 0) {
				$this->tip($res['message'], array('inIframe' => true));
				$this->exeJs("parent.com.xhide();parent.main.refresh()");
			} else {
				$this->tip($res['message'], array('inIframe' => true, 'type' => 1));
			}
			exit;

		}
		if ($id) {
			$row = $this->goodstype->get($id);
		}

		$this->smarty->assign("row", $row);
		$this->smarty->display('business/goodstype/edit.html');
	}

	/**
	 * 删除规格
	 * @param string $id
	 */
	function del() {
		$cat_id = $_REQUEST['id'];

		$res = $this->goodstype->del($cat_id);

		if (isset($res['code']) && $res['code'] == 0) {
			$this->tip($res['message'], array('inIframe' => true));
			$this->exeJs("parent.com.xhide();parent.main.refresh()");
		} else {
			$this->tip($res['message'], array('inIframe' => true, 'type' => 1));
		}
	}
	/**
	 * 属性列表
	 * @param string $id
	 */
	function attr_list($cat_id, $page = 1) {

		#分页
		$this->load->model('page');
		$_GET['page'] = intval($page);
		$this->page->set_vars(array('per' => (int) $this->common['page_listrows']));
		$sql          = "select p1.*,p2.cat_name from `###_attribute` as p1 left join `###_goods_type` as p2 on p1.cat_id=p2.cat_id where p1.cat_id={$cat_id} order by sort_order desc";
		$data['list'] = $this->page->hashQuery($sql, $cat_id . "/")->result_array();

		$this->smarty->assign("data", $data);
		$this->smarty->display('business/goodstype/attr_list.html');
	}

	/**
	 * 删除属性
	 * @param string $id
	 */
	function attr_del() {
		$attr_id = $_REQUEST['id'];
		$res     = $this->goodstype->attr_del($attr_id);
		if (isset($res['code']) && $res['code'] == 0) {
			$this->tip($res['message'], array('inIframe' => true));
			$this->exeJs("parent.com.xhide();parent.main.refresh()");
		} else {
			$this->tip($res['message'], array('inIframe' => true, 'type' => 1));
		}
	}
	/**
	 * 属性添加、修改
	 */
	function attr_edit($id = '') {
		if (isset($_POST['Submit'])) {
			$res = $this->goodstype->attr_save();

			if (isset($res['code']) && $res['code'] == 0) {
				$this->tip($res['message'], array('inIframe' => true));
				$this->exeJs("parent.com.xhide();parent.main.refresh()");
			} else {
				$this->tip($res['message'], array('inIframe' => true, 'type' => 1));
			}
			exit;

		}
		if ($id) {
			$row = $this->goodstype->get_attr($id);
		}

		$cat_list        = $this->goodstype->get_cat_list();
		$row['cat_list'] = $cat_list;
		$this->smarty->assign("row", $row);
		$this->smarty->display('business/goodstype/attr_edit.html');
	}
	/**
	 * 更新属性排序
	 */
	function attr_sort() {
		$res = $this->goodstype->attr_sort();
		$this->exeJs("parent.com.xhide();parent.main.refresh()");
		exit;
	}

}
