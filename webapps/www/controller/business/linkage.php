<?php

/**

 * ZZCMS 联动菜单

 * ============================================================================

 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。

 * 网站地址: http://www.lnest.com；

 * ----------------------------------------------------------------------------

 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和

 * 使用；不允许对程序代码以任何形式任何目的的再发布。

 */

class linkage extends Lowxp {

	function __construct() {

		#按钮

		$this->btnMenu = array(

			0 => array('url' => '#!linkage/index', 'name' => '联动菜单'),

			1 => array('url' => '#!linkage/edit/?com=xshow|添加联动菜单', 'name' => '添加联动菜单'),

		);

		parent::__construct();

		#加载

		$this->load->model('linkage');

	}

	function index($page = 1) {

		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

		$where = 'WHERE lang=' . LANG_ID;

		#分页

		$this->load->model('page');

		$_GET['page'] = intval($page);

		$this->page->set_vars(array('per' => (int) $this->common['page_listrows']));

		#子级菜单

		$data['table_title'] = '';

		$url = '';

		if ($id) {

			$where .= ' AND parentid=' . $id;

			$row = $this->linkage->get($id);

			$url = $row['parentid'] ? '?id=' . $row['parentid'] : '';

			$data['table_title'] = $this->linkage->pos_linkage($id);

			$this->btnMenu[1] = array('url' => '#!linkage/edit?parentid=' . $id . '&com=xshow|添加子级联动菜单', 'name' => '添加子级联动菜单');

			$this->btnMenu[2] = array('url' => '#!linkage/index' . $url, 'name' => '返回上一级');

			$this->smarty->assign('btnMenu', $this->btnMenu);

		} else {

			$where .= ' AND parentid=0';

		}

		#数据集

		$sql = "SELECT * FROM ###_linkage " . $where . " ORDER BY listorder,id";

		$data['list'] = $this->page->hashQuery($sql)->result_array();

		$this->smarty->assign('data', $data);

		$this->smarty->display('manage/linkage/list.html');

	}

	//创建/更新

	function edit() {

		//提交

		if (isset($_POST['Submit'])) {

			$res = $this->linkage->save();

			if (isset($res['code']) && $res['code'] == 0) {

				$this->tip($res['message'], array('inIframe' => true));

				//$this->exeJs("parent.location.href='/manage#!category/index'");

				$this->exeJs("parent.com.xhide();parent.main.refresh()");

			} else {

				$this->tip($res['message'], array('inIframe' => true, 'type' => 1));

			}

			die;

		}

		$id = (int) $_REQUEST['id'];

		$parentid = isset($_REQUEST['parentid']) ? intval($_REQUEST['parentid']) : 0;

		$row = array();

		//编辑

		if ($id) {

			$row = $this->linkage->get($id);

			$data['parentid'] = $row['parentid'];

		} else if ($parentid) {

			$data['parentid'] = $parentid;

		} else {

			$data['parentid'] = 0;

		}

		$data['select_options'] = $this->linkage->select(0);

		if (!$id) {
			$this->smarty->assign('btnNo', 1);
		}

		$this->smarty->assign('row', $row);

		$this->smarty->assign('data', $data);

		$this->smarty->display('manage/linkage/edit.html');

	}

	//删除

	function del($id){
        $id = (int) $_POST['id'];
        if(!$id) die;       
        
        #存在子菜单，不允许直接删除
        $res = $this->db->get("select id from ###_linkage where parentid=".$id);
        if(!empty($res)){ $this->tip('请先删除它的子菜单！',array('type'=>1));die; }

        $this->db->delete('###_linkage', array('id'=>$id));
        //$this->linkage->repair();   
        
        $left_id = ",".$id;        
        $res = $this->db->select("select * from ###_linkage where FIND_IN_SET('$id',arrchildid) AND lang=".LANG_ID);
        
        if($res){
            foreach($res as $k=>$v){                
                $v['arrchildid'] = str_replace($left_id,'',$v['arrchildid']);                
                //$this->db->query("update ###_linkage set arrchildid='".$v['arrchildid']."' where id=".$v['id']);
                $this->db->update("###_linkage",array('arrchildid'=>$v['arrchildid']),array('id'=>$v['id']));
            }
        } 
        
        
        $this->tip('删除成功');
    }
	

	//ajax下拉联动

	function chang_parent() {

		$id = (int) $_POST['id'];

		$hidetop = $_POST['hidetop'];

		$field = $_POST['field'];
        $status = $_POST['status'];
        if($status==1){
            $str = $this->linkage->select_showlinkage($id, $hidetop, $field);
        }else{
            $str = $this->linkage->select_linkage($id, $hidetop, $field);
        }

		die($str);

	}

    //ajax下来分类（商品分类，品牌，同城分类）

    function select_cat() {

        $id = (int) $_POST['id'];

        $table = $_POST['table'];

        $field = $_POST['field'];

        $zone = $_POST['zone'];

        $str = $this->linkage->select_cat($id,$field,$table,$zone);

        die($str);

    }

}