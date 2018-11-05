<?php

/**
 * Name 管理员登录
 * Class login_adm
 */

class member_pay_log extends Lowxp{


    function index(){
        $this->load->model('pay_log');
        $this->load->model('product');

        $page = empty($_GET['page']) ? '' : intval($_GET['page']);
        $_GET['page'] = 1;
        $this->product->agency_list(100);
        $_GET['page'] = $page;
        $this->pay_log->get_list();

        $this->smarty->assign('title','充值消费列表');
        $this->smarty->display('manage/pay_log/list.html');
    }

    /**
     * 增加游戏弹框
     */

    function addForm() {

        $id = empty($_REQUEST['id']) ? '' : $_REQUEST['id'];
        if (is_numeric($id)) {
            $info = $this->db->get("SELECT * FROM `game` WHERE `id` = '$id'");

            if ($info['imgID']) {
                $this->load->model('upload');
                $info['image'] = $this->upload->getImgInfo($info['imgID']);
            }

            $this->smarty->assign('info', $info);
        }else{
            $this->smarty->assign('info', array(
                'introduce'=>'',
                'title'=>'',
                'code'=>'',
                'imgID'=>'',
                'imgID'=>'',
            ));
        }

        $this->smarty->display('manage/goods/addForm.html');
    }

    /**
     * 增加游戏
     */

    function edit() {
        $this->load->model('product');
        $this->product->edit();
    }

    /**
     * 删除游戏
     */

    function delGame() {
        $this->load->model('product');
        $this->product->delGame();
    }


    //----------------------------------服务器开始----------------------------

    function category(){

        $this->load->model('product');
        $this->product->server_list();

        $this->smarty->assign('title','服务器列表');
        $this->smarty->display('manage/goods/category.html');
    }

    /**
     * 增加服务器弹框
     */

    function addServerForm() {
        $this->load->model('product');
        $this->product->agency_list(50);
        $this->product->get_game_list(50);

        $id = empty($_REQUEST['id']) ? '' : $_REQUEST['id'];
        if (is_numeric($id)) {
            $info = $this->db->get("SELECT * FROM `server` WHERE `id` = '$id'");
            $this->smarty->assign('info', $info);
        }else{
            $this->smarty->assign('info',array('title'=>'','introduce'=>''));
        }

        $this->smarty->display('manage/goods/addServerForm.html');
    }

    /*
     * 编辑添加服务器信息
     */

    function editServer() {
        $this->load->model('product');
        $info = $this->product->editServer();

        exit(json_encode($info));
    }

    /**
     * 删除服务器
     */

    function delServer() {
        $this->load->model('product');
        $this->product->delServer();
    }

    //----------------------------------服务器结束----------------------------
    //========================================================================

    //----------------------------------代理商开始-----------------------------

    function agency(){

        $this->load->model('product');
        $this->product->agency_list();

        $this->smarty->assign('title','代理商列表');
        $this->smarty->display('manage/goods/agency.html');
    }

    /**
     * 增加服务器弹框
     */

    function addAgencyForm() {

        $id = empty($_REQUEST['id']) ? '' : $_REQUEST['id'];
        if (is_numeric($id)) {
            $info = $this->db->get("SELECT * FROM `agency` WHERE `aid` = '$id'");
            $this->smarty->assign('info', $info);
        }else{
            $this->smarty->assign('info',array('name'=>'','expire'=>''));
        }

        $this->smarty->display('manage/goods/addAgencyForm.html');
    }

    /*
     * 编辑添加服务器信息
     */

    function editAgency() {
        $this->load->model('product');
        $info = $this->product->editAgency();

        exit(json_encode($info));
    }

    /**
     * 删除服务器
     */

    function stopAgency() {
        $this->load->model('product');
        $this->product->stop_agency();
    }

}