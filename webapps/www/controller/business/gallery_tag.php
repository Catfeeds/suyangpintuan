<?php
/**
 * ZZCMS 多媒体分类
 */

class gallery_tag extends Lowxp {
    function __construct() {
        #按钮
        $this->btnMenu = array(
            0 => array('url' => '#!gallery_tag/index', 'name' => '多媒体分类'),
            1 => array('url' => '#!gallery_tag/edit?com=xshow|添加多媒体分类', 'name' => '添加分类'),
        );

        parent::__construct();
        $this->load->model('gallery_tag');
    }

    function index($page = 1) {
        /*#分页
        $this->load->model('page');
        $_GET['page'] = intval($page);
        $this->page->set_vars(array('per' => (int) $this->common['page_listrows']));

        #数据集
        $sql          = "SELECT * FROM ###_gallery_tag WHERE `type`='image'  and sid=".BID." ORDER BY id";
        $data['list'] = $this->page->hashQuery($sql)->result_array();*/
        $list  = $this->gallery_tag->select('',' and sid='.BID);
        $array = array();
        foreach ($list as $r) {
            $r['str_manage'] = "";
            $r['str_manage'] .= "<a href='#!gallery_tag/edit/?parentid=" . $r['id'] . "&com=xshow|添加子分类' class='iconfont icon-a' title='添加子分类'>&#xe604;</a>";
            $r['str_manage'] .= "<a href='#!gallery_tag/edit/?id=" . $r['id'] . "&com=xshow|编辑多媒体分类' class='iconfont icon-a' title='编辑分类'>&#xe603;</a>";
            $r['str_manage'] .= "<a href='javascript:;' onclick=\"main.confirm_del('gallery_tag/del','" . $r['id'] . "')\" class='iconfont icon-a' title='删除'>&#xe606;</a>";

            $array[] = $r;
        }
        $this->load->library("tree");
        $this->tree->set_params($array);
        $str = "<tr>
                    <td class='id' align='center'>\$id</td>
                    <td>\$spacer\$name</td>
                    <td class='opera' align='center' nowrap>\$str_manage</td>
                </tr>";
        $data['list'] = $this->tree->get_tree(0, $str);

        $this->smarty->assign('data', $data);
        $this->smarty->display('business/gallery_tag/list.html');
    }

    //创建/更新
    function edit() {
        //提交
        if (isset($_POST['Submit'])) {
            $_POST['post']['sid'] = BID;
            $res = $this->gallery_tag->save();

            if (isset($res['code']) && $res['code'] == 0) {
                $this->tip($res['message'], array('inIframe' => true));
                $this->exeJs("parent.com.xhide();parent.main.refresh()");
            } else {
                $this->tip($res['message'], array('inIframe' => true, 'type' => 1));
            }
            exit;
        }

        $id  = (int) $_GET['id'];
        $row = array();
        $parentid = isset($_GET['parentid']) ? (int) $_GET['parentid'] : 0;
        //编辑
        if ($id) {
            $row = $this->db->get("SELECT * FROM ###_gallery_tag WHERE id=" . $id." and sid=".BID);
            $this->smarty->assign('id', $id);

            $parentid = intval($row['parentid']);
        }

        if (!$id) {
            $this->smarty->assign('btnNo', 1);
        }

        //获取下拉分类
        $select_categorys = $this->gallery_tag->category_option($parentid,'','','',' and sid='.BID);
        $this->smarty->assign('select_categorys', $select_categorys);

        $this->smarty->assign('row', $row);
        $this->smarty->display('business/gallery_tag/edit.html');
    }

    //删除
    function del() {
        $id = (int) $_POST['id'];
        if (!$id) {
            die;
        }

        admin_log_sid('删除多媒体分类：' . $this->db->getstr("SELECT name FROM ###_gallery_tag WHERE id=" . $id." and sid=".BID));
        $this->db->delete('###_gallery_tag', array('id' => $id,'sid'=>BID));
        $this->tip('删除成功');
    }
}