<?php
/**
 * Name 产品管理
 * Class product_adm
 */
class spec extends Lowxp {

	function __construct() {
		#按钮
		$this->btnMenu = array(
			0 => array('url' => '#!goods/spec/index', 'name' => '规格管理'),
			1 => array('url' => '#!goods/spec/add?com=xshow|添加碎片', 'name' => '添加规格'),
		);
                S("CH_SPEC",null);
		parent::__construct();
		$this->load->model('goodscat');
	}

	/**
	 * 获取规格列表
	 */
	function getPecList() {
		$rows = $this->db->select("SELECT * FROM ###_goods_spec");
		$this->smarty->assign('list', $rows);
		$this->smarty->display('business/goods/sel_spec.html');
	}

	/**
	 * 规格列表
	 */
	function index() {
		$specs     = $this->db->select("SELECT * FROM ###_goods_spec");
		$items     = $this->db->select("SELECT * FROM ###_goods_spec_item ORDER BY `order`");
		$specItems = array();
		foreach ($items as $val) {
			$specItems[$val['spec_id']][] = $val['value'];
		}

		foreach ($specs as $key => $val) {
			$specs[$key]['spec_list'] = isset($specItems[$val['id']]) ? implode(',', $specItems[$val['id']]) : '';
		}
		$this->smarty->assign('list', $specs);
		$this->smarty->display('business/goods/spec_list.html');
	}

	function add($data = array()) {
		if (!isset($data['id'])) {
			$data = array(
				'id'     => '',
				'name'   => '',
				'type'   => '1',
				'values' => array(),
			);
		}
		$this->smarty->assign('spec', $data);
		$select_categorys = $this->goodscat->category_option();
		$this->smarty->assign('select_categorys', $select_categorys);
		$this->smarty->display('business/goods/spec_add.html');
	}

	function edit($id = '') {
		is_numeric($id) || $this->fatalError('参数错误!');
		$spec           = $this->db->get("SELECT * FROM ###_goods_spec WHERE id=" . $id);
		$rows           = $this->db->select("SELECT * FROM ###_goods_spec_item WHERE spec_id=" . $id . " ORDER BY `order`");
		$spec['values'] = $rows;

		$this->smarty->assign('spec', $spec);

		$select_categorys = $this->goodscat->category_option($spec['catid']);

		$this->smarty->assign('select_categorys', $select_categorys);

		$this->smarty->display('business/goods/spec_add.html');
	}

	/**
	 * 删除规格
	 * @param string $id
	 */
	function del($id = '') {
		is_numeric($id) || $this->fatalError('参数错误!');
		$row = $this->db->get("SELECT * FROM ###_goods_spec WHERE id=" . $id);
		isset($row['id']) || $this->fatalError('规格不存在!');
		$this->db->delete('goods_spec', array('id' => $id));
		$this->db->delete('goods_spec_item', array('spec_id' => $id));
		$this->tip('删除成功!');
		$this->exeJs('main.refresh();');
	}

	function rmSpecItem($id = '') {
		is_numeric($id) || $this->fatalError('参数错误!');
		$row = $this->db->get("SELECT * FROM ###_goods_spec_item WHERE id=" . $id);
		if (!isset($row['id'])) {
			$this->fatalError('规格不存在!');
		}
		$this->db->delete('goods_spec_item', array('id' => $id));
		$this->tip('删除成功!');
		$this->exeJs('main.refresh();');
	}

	function create() {
		$name = $_POST['name'];

		$cat = array();
		if (isset($_POST['catid'])) {
			$cat = $this->goodscat->get($_POST['catid']);
		}
		if (isset($_POST['id']) && is_numeric($_POST['id'])) {
			$id   = $_POST['id'];
			$spec = $this->db->get("SELECT * FROM ###_goods_spec WHERE id=" . $id);
			if (!isset($spec['id'])) {
				$this->fatalError('规格不存在!');
			}

			$this->db->update('goods_spec', array('name' => $name, 'catid' => $cat['id'], 'catname' => $cat['catname']), array('id' => $_POST['id']));
		} else {
			$this->db->insert('goods_spec', array(
				'name'    => $name,
				'catid'   => $cat['id'],
				'catname' => $cat['catname'],
				'type'    => '1',
				'c_time'  => RUN_TIME,
			));
			$id = $this->db->insert_id();
		}
		//修改旧配置
		$OldOrders    = $_POST['olds']['order'];
		$OldNames     = $_POST['olds']['name'];
		$oldSpecItems = $this->db->select("SELECT * FROM ###_goods_spec_item WHERE spec_id=" . $id);
		$oldSpecIds   = array();
		foreach ($oldSpecItems as $val) {
			$oldSpecIds[] = $val['id'];
		}

		if ($OldNames) {
			foreach ($OldNames as $k => $val) {
				$order = $OldOrders[$k];
				if (!in_array($k, $oldSpecIds)) {
					//删除不存在的规格.
					$this->db->delete('goods_spec_item', array('id' => $k));
				} else {
					$this->db->update('goods_spec_item', array(
						'value' => $val,
						'order' => $order,
					), array('id' => $k));
				}
			}
		}
		//新增新添加的规格
		$Orders = $_POST['item']['order'];
		$Names  = $_POST['item']['name'];
		if ($Names) {
			foreach ($Names as $k => $val) {
				$order = $Orders[$k];
				$this->db->insert('goods_spec_item', array(
					'spec_id' => $id,
					'value'   => $val,
					'order'   => $order,
				));
			}
		}
                
		$this->tip("提交成功!", array('inIframe' => true));
		$this->exeJs("parent.com.xhide();parent.main.refresh()");
	}

	/**
	 * 获取规格数据
	 * @param string $id
	 */
	function getSpecItem($id = '') {
		is_numeric($id) || $this->fatalError('参数错误!');
		$spec = $this->db->get("SELECT id,`name`,`type` FROM ###_goods_spec WHERE id=" . $id);
		isset($spec['id']) || $this->fatalError('规格不存在!');
		$spec['values'] = array();
		$rows           = $this->db->select("SELECT * FROM ###_goods_spec_item WHERE spec_id=" . $id . " ORDER BY `order`");
		foreach ($rows as $val) {
			$spec['values'][] = array(
				'id'   => $val['id'],
				'name' => $val['value'],
			);
		}
		echo json_encode($spec);
	}
        
        /**
	 * ajax添加规格数据
	 */
        function ajaxAddSpec(){
            $data['spec_id'] = intval($_POST['spec']);
            $data['value'] = $_POST['value'];
            $data['order'] = 0;
            $res = $this->db->insert('goods_spec_item',$data);
            if($res>0){
                echo json_encode(array('id'=>$res,"value"=>$data['value'],"spec_id"=>$data['spec_id']));exit;
            }else{
                echo json_encode(array('id'=>0));exit;
            }
        }

}
