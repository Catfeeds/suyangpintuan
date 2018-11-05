<?php
/**
 * ZZCMS 物流配送
 */

class express extends Lowxp {
	function __construct() {
		#按钮
		$this->btnMenu = array(
			0 => array('url' => '#!express/index', 'name' => '快递公司'),
			1 => array('url' => '#!express/edit?com=xshow|添加快递公司', 'name' => '添加快递公司'),
		);

		parent::__construct();
		$this->load->model('express');
	}

	function index($page = 1) {
		#分页
		$this->load->model('page');
		$_GET['page'] = intval($page);
		$this->page->set_vars(array('per' => (int) $this->common['page_listrows']));

		$this->load->model("linkage");
		$arealist = $this->linkage->select(1);
		$this->smarty->assign('arealist', $arealist);

		#数据集
		$sql          = "SELECT * FROM ###_express WHERE sid = ".SID." ORDER BY listorder,id";
		$data['list'] = $this->page->hashQuery($sql)->result_array();

		$this->smarty->assign('data', $data);
		$this->smarty->display('business/express/list.html');
	}

	//创建/更新
	function edit() {
		//提交
		if (isset($_POST['Submit'])) {
			$res = $this->express->save();
			if (isset($res['code']) && $res['code'] == 0) {
				$this->tip($res['message'], array('inIframe' => true));
				$this->exeJs("parent.com.xhide();parent.main.refresh()");
			} else {
				$this->tip($res['message'], array('inIframe' => true, 'type' => 1));
			}
			exit;
		}

		$id  = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$row = array();

		//编辑
		if ($id) {
			$row = $this->db->get("SELECT * FROM ###_express WHERE id = $id AND sid = ".SID);
			$this->smarty->assign('id', $id);
		}

		if (!$id) {
			$this->smarty->assign('btnNo', 1);
		}

		$this->smarty->assign('row', $row);
        $this->smarty->assign('shiplist', $this->express->shiplist);
		$this->smarty->display('business/express/edit.html');
	}

	//删除
	function del() {
		$id = (int) $_POST['id'];
		if (!$id) {
			die;
		}

		$express = $this->db->get("SELECT * FROM ###_express WHERE id = $id AND sid = ".SID);

		// 删除快递公司时. 清理打印模版
		$print = $this->db->get("SELECT * FROM `###_express_print_tpl` WHERE sid = ".SID." and express_id = $id LIMIT 1");
		if (!empty($print)) {
			$this->db->delete('###_express_print_field', 'tpl_id=' . $print['id']);
			$this->db->delete('###_express_print_tpl', 'sid = ' . SID . ' and express_id=' . $id);
			$this->db->delete('###_express_shipping', 'express_id=' . $id);
		}

		admin_log_sid('删除快递公司：' . $express['name']);
		$this->db->delete('###_express', array('id' => $id, 'sid' => SID));
		$this->tip('删除成功', array('type' => 1));
	}
	//设置商品所在城市
	function area() {
		$areaid = end(array_filter($_REQUEST['post']['areaid']));
		if ($areaid > 0) {
			$this->db->update("config", array('value' => $areaid), array("varname" => 'prov_sl'));
			$this->base->clear_caches();
			$this->tip('操作成功', array('type' => 1, 'url' => "express/index"));
		}
	}

	//打印模版预览
	public function printPreview() {
		if (empty($_GET['express_id'])) {
			exit('对不起, 预览模版不存在');
		} else {
			$expressId = $_GET['express_id'];
		}

		$config = $this->db->get("SELECT * FROM `###_express_print_tpl` WHERE sid=" . MERID . " and express_id=" . $expressId . ' LIMIT 1');

		if (empty($config['id'])) {
			exit('对不起, 暂无预览. 请先配置快递单打印模版');
		}

		$field = $this->db->select("SELECT * FROM `###_express_print_field` WHERE tpl_id=" . $config['id'] . " ORDER BY id");

		$this->smarty->assign('field', $field);
		$this->smarty->assign('config', $config);
		$this->smarty->display('business/express/print_view.html');
	}

	// 实际快递单打印 注意内部字符替换
	public function printView() {

		if (empty($_GET['express_id'])) {
			exit('对不起, 预览模版不存在');
		} else {
			$expressId = $_GET['express_id'];
		}

		if (empty($_GET['order_id'])) {
			exit('对不起, 指定订单不存在');
		} else {
			$orderId = $_GET['order_id'];
		}

		$config = $this->db->get("SELECT * FROM `###_express_print_tpl` WHERE sid=" . MERID . " and express_id=" . $expressId . ' LIMIT 1');

		if (empty($config['id'])) {
			exit('对不起, 暂无预览. 请先配置快递单打印模版');
		}

		$order = $this->db->get("SELECT * FROM `###_goods_order` WHERE id=" . $orderId . ' LIMIT 1');
		if (empty($order['id'])) {
			exit('对不起, 订单不存在, 请检查订单id');
		}

		$field = $this->db->select("SELECT * FROM `###_express_print_field` WHERE tpl_id=" . $config['id'] . " ORDER BY id");

		// 字段同步逻辑
		foreach ($field as $k => $v) {
			switch ($v['value']) {
				case 'goods_order.mobile':
					$field[$k]['name'] = $order['mobile'];
					break;
				case 'goods_order.address':
					$field[$k]['name'] = $order['address'];
					break;
				case 'goods_order.area':
					$field[$k]['name'] = $order['area'];
					break;
				case 'goods_order.name':
					$field[$k]['name'] = $order['name'];
					break;
				default:
					break;
			}

		}

		$this->smarty->assign('field', $field);
		$this->smarty->assign('config', $config);
		$this->smarty->display('business/express/print_view.html');
	}

	// 快递单公用模版选择器
	public function printPublic($page = 1) {

		$_GET['page'] = $page;
		$sql          = "SELECT * FROM `###_express_print_public` WHERE status>0 ORDER BY id";

		$this->load->model('page');
		$this->page->set_vars(array(
			'per' => (int) $this->common['page_listrows'],
			'url' => 'href="javascript:;" title="第{num}页" data-page="{num}"',
		));
		$list = $this->page->query($sql)->result_array();

		$this->smarty->assign('list', $list);
		$this->smarty->display('business/express/print_public.html');
	}

	// 快递单打印模版编辑
	public function printEdit() {
		if (isset($_POST['post'])) {
			$this->printEditHandle();
			exit;
		}

		$id = isset($_GET['id']) ? $_GET['id'] : 0;
		if (!$id) {
			$this->error('对不起参数错误');
		}

		// 获取当前快递信息 主要是名称
		$row = $this->db->get("SELECT * FROM `###_express` WHERE id =" . $id);
		$this->smarty->assign('row', $row);

		$oldConfig = $this->db->get("SELECT * FROM `###_express_print_tpl` WHERE sid=" . MERID . " and express_id=" . $id . ' LIMIT 1');

		// 公用模版id
		$publicTpl = isset($_GET['publicTpl']) ? $_GET['publicTpl'] : 0;
		if ($publicTpl) {
			$this->smarty->assign('publicTpl', $publicTpl);
			$config = $this->db->get("SELECT * FROM `###_express_print_public` WHERE id=" . $publicTpl . ' LIMIT 1');
			if (!empty($config['id'])) {
				$field = $this->db->select("SELECT * FROM `###_express_print_public_field` WHERE tpl_id=" . $publicTpl . ' ORDER BY name');
			}

			// 使用公用模版时 检查当前配置是否已经有生成id
			if (empty($oldConfig['id'])) {
				$config['id']        = '';
				$config['old_image'] = '';
			} else {
				$config['id']        = $oldConfig['id'];
				$config['old_image'] = $oldConfig['image'];
			}
		} else {
			// 不使用公用模版时
			$config = $oldConfig;

			if (!empty($config['id'])) {
				// 记录老配置图片位置 准备做清理工作
				$config['old_image'] = $config['image'];

				$field = $this->db->select("SELECT * FROM `###_express_print_field` WHERE tpl_id=" . $config['id'] . ' ORDER BY name');
			}
		}

		if (empty($field)) {
			$field = $this->db->select("SELECT * FROM `###_express_print_public_field` WHERE tpl_id=0 ORDER BY id");
		}

		$this->smarty->assign('field', $field);
		$this->smarty->assign('config', $config);
		$this->smarty->display('business/express/print_edit.html');
	}

	public function printEditHandle() {

		if (isset($_POST['post'])) {
			$post = $_POST['post'];

			$data = array(
				'status' => 1,
				'sid'    => MERID,
			);

			isset($post['express_id']) && $data['express_id']   = $post['express_id'];
			isset($post['description']) && $data['description'] = $post['description'];
			isset($post['width']) && $data['width']             = $post['width'];
			isset($post['height']) && $data['height']           = $post['height'];
			isset($post['left']) && $data['left']               = $post['left'];
			isset($post['top']) && $data['top']                 = $post['top'];

			// 非引用公用模版时图片处理
			if (!empty($post['image']) && $post['image'] != $post['old_image']) {
				// 清理旧文件
				if ($post['old_image']) {
					$oldimg = RootDir . 'web' . $post['old_image'];
					if (file_exists($oldimg)) {
						@unlink($oldimg);
					}
				}

				// 临时文件移动到存储目录
				$source = RootDir . 'web' . $post['image'];
				if (file_exists($source)) {

					// 确保目标文件夹存在					
					$sourceDir = $this->load->config('picture', 'source_dir').'images/';
					if (!is_dir($sourceDir)) {
						mkdir($sourceDir, 0777, true);
					}

					// 移动文件到存储目录
					$pathinfo = pathinfo($post['image']);

					$data['image'] = $this->load->config('picture', 'source_url') . 'images/' . $pathinfo['basename'];

					$destination = RootDir . 'web' . $data['image'];

					if ($post['image'] != $data['image']) {

						if ($post['publicTpl']) {
							// 引用公用模版时 复制图片
							if (!copy($source, $destination)) {
								IS_DEV && Log::write('文件移动失败');
							}
						} else {
							// 非引用公用模版时 移动图片
							if (!rename($source, $destination)) {
								IS_DEV && Log::write('文件移动失败');
							}
						}

					}
				}
			}

			if (empty($post['id'])) {
				$id = $this->db->insert('express_print_tpl', $data);
			} else {
				$id = $post['id'];
				$this->db->update('express_print_tpl', $data, 'id=' . $post['id']);
			}

			if ($id && !empty($post['field'])) {
				$this->db->delete('express_print_field', ' tpl_id=' . $id);
				$this->db->insertAll('express_print_field', $post['field'], array('tpl_id' => $id));
			}

			$res = array(
				'error' => '0',
				'msg'   => '保存成功',
			);

			if (isset($data['image'])) {
				$res['data']['old_image'] = $data['image'];
			}

			$this->ajaxReturn($res);
		}
	}

	public function backgroundUpload() {
		$res = array(
			'error' => '1',
			'msg'   => '上传失败',
		);

		if (!empty($_POST['lastimg'])) {
			$lastImg = RootDir . 'web' . $_POST['lastimg'];
			if (file_exists($lastImg)) {
				@unlink($lastImg);
			}
		}

		$this->load->model('upload');
		$src = $this->upload->save_image('uploadFile', '../tmp');

		if ($src) {
			$res['error'] = '0';
			$res['msg']   = '上传成功';
			$res['src']   = $src;
		}

		$this->ajaxReturn($res);
	}

	//区域列表
	public function expressShippingList($page = 1){
		$btnMenu = array(
			0 => array('url' => "#!express/editExpressShipping?express_id=$_GET[id]&com=xshow|添加配送区域", 'name' => '添加配送区域'),
		);
		$this->smarty->assign('btnMenu', $btnMenu);
		$this->load->model('page');
		$_GET['page'] = intval($page);
		$this->page->set_vars(array('per' => (int) $this->common['page_listrows']));
		$express_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$sql = "SELECT * FROM ###_express_shipping WHERE express_id = $express_id AND sid = ".SID;
		$list = $this->page->hashQuery($sql)->result_array();
		foreach($list as $k=>$v){
			$this->load->model('linkage');
			$linkage = $this->linkage->selectLinkageByIds($v['linkage_id'], 'name');
			$name = array_column($linkage, 'name');
			$list[$k]['linkage_name'] = implode(' ', $name);
			$list[$k]['config'] = unserialize($v['config']);
		}
		$this->smarty->assign('list', $list);
		$this->smarty->display('business/express/shipping_area_list.html');
	}

	public function editExpressShipping($id = ''){
		if(isset($_POST['Submit'])){
			$res = $this->express->saveExpressShipping();
			if(isset($res['code']) && $res['code'] == 0){
				$this->tip($res['message'], array('inIframe' => true));
				$this->exeJs("parent.com.xhide();parent.main.refresh()");exit;
			}else{
				$this->tip($res['message'], array('inIframe' => true, 'type' => 1));exit;
			}
		}
		if(!isset($_GET['express_id']) || empty($_GET['express_id'])){
			$this->tip('参数异常（express_id）', array('inIframe' => true, 'type' => 1));
			$this->exeJs("parent.com.xhide()");exit;
		}
		if($id){
			$row = $this->express->getExpressShippingById($id, SID);
			$row['config'] = unserialize($row['config']);
			$this->load->model('linkage');
			$row['linkage'] = $this->linkage->selectLinkageByIds($row['linkage_id'], 'id,name');
			$this->smarty->assign('id', $id);
			$this->smarty->assign('row', $row);
		}
		$this->smarty->display('business/express/edit_shipping_area.html');
	}

	public function delExpressShipping(){
		$id = (int) $_POST['id'];
		if (!$id) {die;}
		$expressShipping = $this->express->getExpressShippingById($id);
		$express = $this->express->getExpressByIdSid($expressShipping['express_id'], SID);
		admin_log_sid('删除' . $express['name'] .'公司区域：' . $expressShipping['name']);
		$this->db->delete('###_express_shipping', array('id' => $id, 'sid' => SID));
		$this->tip('删除成功', array('type' => 1));
	}

	function ajaxExpressShipping(){
		$linkageId = (int) $_POST['linkage_id'];
		$express_id = (int) $_POST['express_id'];
		$id = (int) $_POST['id'];
		$where = "express_id = $express_id and sid = ".SID;
		if($id){
			$where .= " and id != $id";
		}
		$express = $this->express->selectExpressShipping($where, 'linkage_id');
		if($express) {
			$linkage_id = array_column($express, 'linkage_id');
			$linkage_id = implode(',', $linkage_id);
			$sql = "select id,arrparentid,arrchildid from ###_linkage where id = $linkageId and id not in ($linkage_id) and lang = " . LANG_ID;
		}else{
			$sql = "select id,arrparentid,arrchildid from ###_linkage where id = $linkageId and lang =" . LANG_ID;;
		}
		$linkage = $this->db->get($sql);
		$data = array();
		$linkage_id_arr =explode(',', $linkage_id);
		$linkage_parent_arr = explode(',', $linkage['arrparentid']);
		$array_intersect = array_intersect($linkage_id_arr,$linkage_parent_arr);
		if(empty($array_intersect) && $linkage){
			$data['arrparentid'] = "$linkage[arrparentid],$linkage[id]";
			$data['arrchildid'] = "$linkage[arrchildid]";
		}
		echo json_encode($data);
	}

	function ajaxExpressShippingProvince(){
		$parentid = (int) $_POST['parentid'];
		$express_id = (int) $_POST['express_id'];
		$id = (int) $_POST['id'];
		$where = "express_id = $express_id and sid = ".SID;
		if($id){
			$where .= " and id != $id";
		}
		$express = $this->express->selectExpressShipping($where, 'id,linkage_id');
		if($express){
			$linkage_id = array_column($express, 'linkage_id');
			$linkage_id = implode(',', $linkage_id);
			$sql = "select id,`name` from ###_linkage where id not in ($linkage_id) AND parentid = $parentid AND lang = " . LANG_ID . " ORDER BY listorder,id";
		}else{
			$sql = "select id,`name` from ###_linkage where parentid = $parentid AND lang = " . LANG_ID . " ORDER BY listorder,id";
		}
		$linkage = $this->db->select($sql);
		echo json_encode($linkage);
	}
}