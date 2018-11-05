<?php

/**

 * ZZCMS 管理员、角色

 * ============================================================================

 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。

 * 网站地址: http://www.lnest.com；

 * ----------------------------------------------------------------------------

 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和

 * 使用；不允许对程序代码以任何形式任何目的的再发布。

 */

class admin extends Lowxp {

	function __construct() {

		parent::__construct();

		$this->load->model('admin_sid');

	}

	#列表（管理员）

	function index($page = 1) {

		#按钮

		$this->btnMenu = array(

			0 => array('url' => '#!admin/index', 'name' => '管理员列表'),

			1 => array('url' => '#!admin/edit?com=xshow|添加管理员', 'name' => '添加管理员'),

		);

		#分页

		$this->load->model('page');

		$_GET['page'] = intval($page);

		$this->page->set_vars(array('per' => (int) $this->common['page_listrows']));

		#数据集

		$sql = "SELECT DISTINCT a.uid,a.*,ar.name FROM " . $this->admin_sid->baseTable . " AS a " .

		"LEFT JOIN " . $this->admin_sid->groupTable . " AS ar ON a.group_id=ar.id WHERE a.group_id>0 and a.sid=".BID."  ORDER BY uid DESC";

		$data['list'] = $this->page->hashQuery($sql)->result_array();

		$this->smarty->assign('data', $data);

		$this->smarty->assign('btnMenu', isset($this->btnMenu) ? $this->btnMenu : array());

		$this->smarty->display('business/admin/list.html');

	}

	//创建/更新（管理员）

	function edit() {

		//提交

		if (isset($_POST['Submit'])) {

		    $_POST['post']['sid'] = SID;

			$res = $this->admin_sid->save();

			if (isset($res['code']) && $res['code'] == 0) {

				$this->tip($res['message'], array('inIframe' => true));

				$this->exeJs("parent.com.xhide();parent.main.refresh()");

			} else {

				$this->tip($res['message'], array('inIframe' => true, 'type' => 1));

			}

			exit;

		}

		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

		$row = array();

		//编辑

		if ($id) {

			$row = $this->db->get("SELECT * FROM " . $this->admin_sid->baseTable . " WHERE uid=" . $id);

		}

		#角色选择

		$list            = array();
		$row['group_id'] = isset($row['group_id']) ? $row['group_id'] : 0;
		if ($row['group_id'] == -1) {
			#超级帐号

			$list['-1'] = '超级管理员';

		} else {

			$array = $this->db->select("SELECT id,name FROM " . $this->admin_sid->groupTable . " where sid=".BID." ORDER BY listorder,id");

			foreach ($array as $v) {

				$list[$v['id']] = $v['name'];

			}

		}

		$select_group = $this->form->select($list, $row['group_id'], 'name="post[group_id]"');

		$this->smarty->assign('select_group', $select_group);

		if (!$id) {
			$this->smarty->assign('btnNo', 1);
		}

		$this->smarty->assign('row', $row);

		$this->smarty->display('business/admin/edit.html');

	}

	//删除

	function del() {

		$id = (int) $_POST['id'];

		if (!$id) {
			die;
		}

		#必须保留一个

		if ($this->db->getstr("select COUNT(*) from " . $this->admin_sid->baseTable) <= 2) {

			$this->tip('必须保留一个管理员帐号！', array('type' => 1));

		} else {

			admin_log_sid('删除管理员：' . $this->db->getstr("SELECT username FROM " . $this->admin_sid->baseTable . " WHERE uid=" . $id));

			$this->db->delete('###_business_user', array('uid' => $id));

			$this->tip('删除成功', array('type' => 1));

		}

	}

	#列表（角色）

	function role($page = 1) {

		#按钮

		$this->btnMenu = array(

			0 => array('url' => '#!admin/role', 'name' => '角色管理'),

			1 => array('url' => '#!admin/edit_role?com=xshow|添加管理员角色', 'name' => '添加角色'),

		);

		#分页

		$this->load->model('page');

		$_GET['page'] = intval($page);

		$this->page->set_vars(array('per' => (int) $this->common['page_listrows']));

		#数据集

		$sql = "SELECT * FROM " . $this->admin_sid->groupTable  . " where sid=".BID." ORDER BY listorder,id";

		$data['list'] = $this->page->hashQuery($sql)->result_array();

		$this->smarty->assign('data', $data);

		$this->smarty->assign('btnMenu', isset($this->btnMenu) ? $this->btnMenu : array());

		$this->smarty->display('business/admin/list_role.html');

	}

	#添加、更新（角色）

	function edit_role() {

		//提交

		if (isset($_POST['Submit'])) {

            $_POST['post']['sid'] = BID;

			$res = $this->admin_sid->save_role();

			if (isset($res['code']) && $res['code'] == 0) {

				$this->tip($res['message'], array('inIframe' => true));

				$this->exeJs("parent.com.xhide();parent.main.refresh()");

			} else {

				$this->tip($res['message'], array('inIframe' => true, 'type' => 1));

			}

			exit;

		}

		$id = (int) $_GET['id'];

		$row = array();

		$row = $this->db->get("SELECT * FROM ###_business_user_group WHERE id=" . $id);

		$menu_list = $this->base->explodeChar($row['menu_list'], ',');

		#权限节点

		$this->load->library('tree');

		$this->load->model('menus_sid');

		$list = $this->menus_sid->menus_array("menus");

		$array = array();

        $select_category = "";

		foreach ($list[0] as $r) {

		    if($r['id']==1)continue;

			$r['checked'] = '';

			if (in_array($r['id'], $menu_list)) {$r['checked'] = 'checked';}

            $select_category.= "<div><label><input type='checkbox' name='post[menu_list][]' value='{$r['id']}' {$r['checked']}/> {$r['name']}</label></div>";

			if(isset($list[$r['id']])){

			    foreach($list[$r['id']] as $v){

                    if (in_array($v['id'], $menu_list)) {$v['checked'] = 'checked';}

                    $select_category.= "<div>{$this->tree->nbsp}{$this->tree->icon[1]}<label><input type='checkbox' name='post[menu_list][]' value='{$v['id']}' {$v['checked']}  /> {$v['name']}</label></div>";

                }

            }

		}

		/*$this->tree->set_params($array);

		$str = "<div>\$spacer <label><input type='checkbox' name='post[menu_list][]' value='\$id' \$checked /> \$name</label></div>";

		$select_category = $this->tree->get_tree(0, $str);*/

		$this->smarty->assign('select_category', $select_category);

		$this->smarty->assign('id', $id);

		$this->smarty->assign('row', $row);

		$this->smarty->display('business/admin/edit_role.html');

	}

	//删除（角色）

	function del_role() {

		$id = (int) $_POST['id'];

		if (!$id) {
			die;
		}

		#必须保留一个

		if ($this->db->getstr("select COUNT(*) from " . $this->admin_sid->groupTable) <= 1) {

			$this->tip('必须保留一个管理员角色！', array('type' => 1));

		} else {

			admin_log_sid('删除管理员角色：' . $this->db->getstr("SELECT name FROM " . $this->admin_sid->groupTable . " WHERE id=" . $id));

			$this->db->delete('###_business_user_group', array('id' => $id));

			$this->tip('删除成功', array('type' => 1));

		}

	}

}