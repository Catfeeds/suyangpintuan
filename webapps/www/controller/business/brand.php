<?php

/**

 * ZZCMS 品牌管理

 * ============================================================================

 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。

 * 网站地址: http://www.lnest.com；

 * ----------------------------------------------------------------------------

 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和

 * 使用；不允许对程序代码以任何形式任何目的的再发布。

 */

class brand extends Lowxp {

	function __construct() {

		#按钮

		$this->btnMenu = array(

			0 => array('url' => '#!brand/index', 'name' => '品牌管理'),

			1 => array('url' => '#!brand/edit?com=xshow|添加品牌', 'name' => '添加品牌'),

		);

		parent::__construct();

		#加载

		$this->load->model('brand');

		$this->load->library('tree');

	}

	function index() {

		$list = $this->brand->select();

		$array = array();

		foreach ($list as $r) {

			$r['thumb'] = json_decode($r['thumb'], true);

			$r['str_business'] = "";
			$r['moduleid']   = isset($r['moduleid']) ? $r['moduleid'] : 0;
			if ($r['moduleid'] > 0 && $r['module'] != 'page') {

				$r['str_business'] .= "<a href='#!content/index/" . $r['module'] . "?catid=" . $r['id'] . "' class='iconfont icon-a' title='查看内容'>&#xe601;</a> ";

			} elseif ($r['moduleid'] > 0 && $r['module'] == 'page') {

				$r['str_business'] .= "<a href='#!content/edit/" . $r['module'] . "?id=" . $r['id'] . "&com=xshow|编辑内容' class='iconfont icon-a' title='编辑内容'>&#xe601;</a> ";

			}

			//$r['str_business'] .= "<a href='#!brand/edit/?parentid=".$r['id']."&com=xshow|添加子品牌' class='iconfont icon-a' title='添加子品牌'>&#xe604;</a>";

			$r['str_business'] .= "<a href='#!brand/edit/?id=" . $r['id'] . "&com=xshow|编辑品牌' class='iconfont icon-a' title='编辑品牌'>&#xe603;</a>";

			$r['str_business'] .= "<a href='javascript:;' onclick=\"main.confirm_del('brand/del','" . $r['id'] . "')\" class='iconfont icon-a' title='删除'>&#xe606;</a>";

			if ($r['ismenu'] == 1) {

				$r['ismenu'] = "<a href='javascript:;' class='c-green' onclick='main.chang_status(\"" . $r['id'] . "\",\"brand\",\"ismenu\")' title='点击设为不显示'>显示</a>";

			} else {

				$r['ismenu'] = "<a href='javascript:;' class='c-red' onclick='main.chang_status(\"" . $r['id'] . "\",\"brand\",\"ismenu\")' title='点击设为显示'>不显示</a>";

			}

			if ($r['thumb'][0]['path']) {

				$r['thumb'] = '<a href="' . $r['thumb'][0]['path'] . '" target="_blank" class="iconfont c-green" title="缩略图">&#xe602;</a>';

			}
			$r['type'] = isset($r['type']) ? $r['type'] : 0;
			if ($r['type'] == 1) {

				$r['module_title'] = '<span class="c-red">' . $r['module_title'] . '</span>';

			}
                        
                        $r['start_time'] = empty($r['start_time'])?'':date("Y-m-d H:i:s", $r['start_time']);
                        
                        $r['end_time'] = empty($r['end_time'])?'':date("Y-m-d H:i:s", $r['end_time']);
                        
			$array[] = $r;

		}

		$this->tree->set_params($array);

		$str = "<tr>

                    <td align='center'><input type='text' class='form-i-s w30' name='listorders[\$id]' value='\$listorder' /></td>

                    <td class='id' align='center'>\$id</td>

                    <td>\$spacer\$catname \$thumb</td>
                    
                    <td align='center'>\$sale</td>
                    
                    <td align='center'>\$start_time~\$end_time</td>
                    
                    <td align='center'>\$ismenu</td>

                    <td class='opera' align='center' nowrap>\$str_business</td>

				</tr>";

		$data['list'] = $this->tree->get_tree(0, $str);

		$this->smarty->assign('data', $data);

		$this->smarty->display('business/brand/list.html');

	}

	//创建/更新

	function edit() {

		//提交

		if (isset($_POST['Submit'])) {
                        
			$res = $this->brand->save();

			if (isset($res['code']) && $res['code'] == 0) {

				$this->tip($res['message'], array('inIframe' => true));

				//$this->exeJs("parent.location.href='/business#!brand/index'");

				$this->exeJs("parent.com.xhide();parent.main.refresh()");

			} else {

				$this->tip($res['message'], array('inIframe' => true, 'type' => 1));

			}

			die;

		}

		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

		$parentid = isset($_GET['parentid']) ? (int) $_GET['parentid'] : 0;

		$row = array();

		//编辑

		if ($id) {

			$row = $this->db->get("SELECT * FROM ###_brand WHERE id=" . $id);

			$this->smarty->assign('id', $id);

			$parentid = intval($row['parentid']);

			#获取图片地址

			$this->load->model('upload');

			$row = $this->upload->getImgUrls($row, 'id', 'category');

		} else {

			$row['ismenu'] = 1;

			$row['pagesize'] = 10;

			$row['listtype'] = 0;

		}

		//获取下拉品牌

		$select_categorys = $this->brand->category_option($parentid);

		$this->smarty->assign('select_categorys', $select_categorys);

		//生成图片控件
		$row['thumb']      = isset($row['thumb']) ? $row['thumb'] : '';
		$row['html_thumb'] = $this->form->files('thumb', $row['thumb']);

		if (!$id) {
			$this->smarty->assign('btnNo', 1);
			$row['id'] = 0;
		};

		$this->smarty->assign('row', $row);

		$this->smarty->display('business/brand/edit.html');

	}

	//删除

	function del($id) {

		$id = (int) $_POST['id'];

		if (!$id) {
			die;
		}

		#存在子菜单，不能允许直接删除

		$res = $this->db->get("select id from ###_brand where parentid=" . $id);

		if (!empty($res)) {$this->tip('请先删除它的子品牌！', array('type' => 1));die;}

		admin_log_sid('删除商品品牌：' . $this->db->getstr("SELECT catname FROM ###_brand WHERE id=" . $id));

		$this->db->delete('###_brand', array('id' => $id));

		$this->brand->repair();

		$this->brand->repair();

		$this->tip('删除成功');

	}

}