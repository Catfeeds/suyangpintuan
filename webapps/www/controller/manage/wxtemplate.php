<?php
/**
 * ZZCMS 微信消息模版管理
 * ============================================================================
 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。
 * 网站地址: http://www.lnest.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 */
class wxtemplate extends Lowxp {

    public function __construct() {
        #按钮
        $this->btnMenu = array(
            0 => array('url' => '#!wxtemplate/index', 'name' => '微信消息模板'),
            1 => array('url' => '#!wxtemplate/getTMlist', 'name' => '更新消息模板'),
            2 => array('url' => '#!wxtemplate/uptoken', 'name' => '清除access_token'),
        );

        parent::__construct();
        $this->load->model('wxtemplate');

    }

    //微信消息模板
    public function index(){
        $list = $this->wxtemplate->getList();
        $this->smarty->assign('list', $list);
        $this->smarty->display('manage/template_msg/wxlist.html');
    }

    //获取微信模板
    public function  getTMlist(){

        $res = $this->wxtemplate->getTMlist();

        if($res['template_list']){
            $this->tip("获取成功！", array('inIframe' => true, 'type' => 1));
        }else{
            $this->tip("获取失败，请先添加公众号消息模板", array('inIframe' => true, 'type' => 1));
        }
        $this->exeJs("parent.window.location='/manage#!wxtemplate/index';");

    }

    //删除
    public function  del($id){
        $id = intval($id);
        $res = $this->wxtemplate->del($id);
        if ($res) {
            $this->tip('删除成功', array('type' => 1));
        }else{
            $this->tip('删除失败', array('type' => 1));
        }
        $this->exeJs("parent.window.location='/manage#!wxtemplate/index';");
    }

    //创建/更新
    public function edit() {
        //提交
        if (isset($_POST['post'])) {
            $res = $this->wxtemplate->save($_POST['post']);
            $this->tip($res['msg'], array('inIframe' => true));
            $this->exeJs("parent.com.xhide();parent.main.refresh()");
        }
        $row = $this->wxtemplate->getRow($_GET['id']);
        $this->smarty->assign('row', $row);
        $this->smarty->display('manage/template_msg/wxedit.html');
    }
    //清除access_token缓存
    public function uptoken(){
        $cache_name = "wechat_access_token".C("wx_appid");
        S($cache_name,null);
        $this->tip("操作成功！", array('inIframe' => true, 'type' => 1));
        $this->exeJs("parent.window.location='/manage#!wxtemplate/index';");
    }

}