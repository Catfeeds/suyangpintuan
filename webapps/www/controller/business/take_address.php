<?php

/**
 * ZZCMS 商家管理
 * ============================================================================
 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。
 * 网站地址: http://www.lnest.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 */
class take_address extends Lowxp
{
    function __construct()
    {
        #按钮
        $this->btnMenu = array(
            0 => array('url' => '#!take_address/index', 'name' => '自提地址列表'),
            1 => array('url' => '#!take_address/edit?com=xshow', 'name' => '自提地址添加'),
            2 => array('url' => '#!take_address/take_user', 'name' => '核销人员'),
            3 => array('url' => '#!take_address/take_user_add?com=xshow|添加核销人员', 'name' => '添加核销人员'),
        );
        parent::__construct();
        $this->load->model('take_address');
    }

    function index($page = 1)
    {
        $data['list'] = $this->take_address->select($page, SID);
        $this->smarty->assign('data', $data);
        $this->smarty->assign('btnNo', 0);
        $this->smarty->display('business/take_address/index.html');
    }

    function edit($id = '')
    {
        if(isset($_POST['Submit'])){
            $res = $this->take_address->save();
            if(isset($res['code']) && $res['code'] == 0){
                $this->tip($res['message'], array('inIframe' => true));
                $this->exeJs("parent.com.xhide();parent.main.refresh()");
            }else{
                $this->tip($res['message'], array('inIframe' => true, 'type' => 1));
            }
        }
        if($id){
            $info = $this->take_address->get($id, SID);
            $this->smarty->assign('info', $info);
        }
        $this->smarty->display('business/take_address/edit.html');
    }

    function del()
    {
        $id = intval($_REQUEST['id']);
        if ($id == 0) $this->error_msg('ID不能为空');
        $res = $this->take_address->delete($id, SID);
        if (false !== $res) {
            $this->tip("操作成功", array('inIframe' => true));
            $this->exeJs("parent.com.xhide();parent.main.refresh()");
        } else {
            $this->error_msg('操作失败');
        }
    }
    function error_msg($msg)
    {
        $this->tip($msg, array('inIframe' => true));
        exit;
    }

    /**
     * 核销人员列表
     */
    function take_user($page=1){

        $data['list'] = $this->take_address->take_user($page, SID);
        $this->smarty->assign('data', $data);
        $this->smarty->assign('btnNo', 2);
        $this->smarty->display('business/take_address/take_user.html');
    }
    /**
     * 添加核销人员
     */
    function take_user_add($id=0){
        if(isset($_POST['Submit'])){
            $res = $this->take_address->take_user_add();
            if($res['code']==0){
                $this->tip("操作成功", array('inIframe' => true));
                $this->exeJs("parent.com.xhide();parent.main.refresh()");
            }else{
                $this->error_msg($res['message']);
            }

        }
        $data['row'] = array();
        if($id>0){
            $data['row'] = $this->take_address->take_user_one($id);
        }
        $sql = "select * from `###_take_address` WHERE sid = ".SID;
        $data['list'] = $this->db->select($sql);
        $this->smarty->assign('data', $data);
        $this->smarty->display('business/take_address/take_user_add.html');
    }
    /**
     * 删除核销人员
     */
    function take_user_del(){
        $id = intval($_REQUEST['id']);
        if ($id == 0) $this->error_msg('ID不能为空');
        $res = $this->take_address->take_user_del($id);
        if (false !== $res) {
            $this->tip("操作成功", array('inIframe' => true));
            $this->exeJs("parent.com.xhide();parent.main.refresh()");
        } else {
            $this->error_msg('操作失败');
        }
    }

    /**
     * 判断会员是否存在
     */
    function ajax_user() {
        $mobile = $_POST['mobile'];
        if (empty($mobile)) {
            die(json_encode(array('status' => 1, "msg" => "手机号码不能为空")));
        }

        $is_res = $this->db->get("select mid,username,mobile from ###_member where mobile={$mobile}");
        if ($is_res == false) {
            die(json_encode(array('status' => 1, "msg" => "该会员不存在")));
        } else {
            die(json_encode(array('status' => 0, "msg" => "用户名：" . $is_res['username'], 'mobile' => $is_res['mobile'],'mid'=>$is_res['mid'])));
        }

    }
}