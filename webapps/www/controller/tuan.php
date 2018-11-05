<?php
/**
 * 团购控制器
 * ============================================================================
 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。
 * 网站地址: http://www.lnest.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 */

class tuan extends Lowxp{

    function __construct(){
        parent::__construct();
        $this->load->model('auction');
    }

    /**
     * 团购封面
     */
    function index($page=1){
        $status='';
        $id=0;
        $data = array();
        $data['nav'] = 5;
        $data['id'] = $id;
        $data['status'] = $status;
        $data['status_row'] = $this->auction->status[$status];

        $size = 10;
        $data['list'] = $this->auction->getList($size,$page,$id,$status,CART_TUAN);

        #异步加载
        if(isset($_GET['load'])){
            if($data['list']){
                $content = '';
                foreach($data['list'] as $v){
                    $this->smarty->assign('m', $v);
                    $content .= $this->smarty->fetch('auction/lbi/tuan_list.html');
                }
                echo $content;die;
            }
        }

        $this->smarty->assign('data', $data);
        $this->display_before(array('title'=>'团购'));
        $this->smarty->display('auction/tuan_list.html');
    }

    /** 竞拍列表
     * @param int $status 状态
     * @param int $id 商品分类ID
     * @param int $page 页码
     */
    function lists($status=UNDER_WAY, $id=0, $page=1){
        $data = array();
        $data['nav'] = 5;
        $data['id'] = $id;
        $data['status'] = $status;
        $data['status_row'] = $this->auction->status[$status];

        $size = 10;
        $data['list'] = $this->auction->getList($size,$page,$id,$status,CART_TUAN,array('qishu'=>1));

        #异步加载
        if(isset($_GET['load'])){
            if($data['list']){
                $content = '';
                foreach($data['list'] as $v){
                    $this->smarty->assign('m', $v);
                    $content .= $this->smarty->fetch('auction/lbi/tuan_list.html');
                }
                echo $content;die;
            }
        }

        $this->smarty->assign('data', $data);
        $this->display_before(array('title'=>'团购'));
        $this->smarty->display('auction/tuan_list.html');
    }
    
    /**
    * 促销详情  缓存促销详情，销量实时获取或者通过redis获取
    */
   function show($id) {

           $this->load->model("goods");
           $row           = $this->auction->get($id);
           $row['status'] = $this->auction->status($row);
           $ext_info      = unserialize($row['ext_info']);
           $row           = array_merge($row, $ext_info);
           if ($row['status'] == PRE_START) {
                   $row['diff_time'] = $row['start_time'] - time();
           } else {
                   $row['diff_time'] = $row['end_time'] - time();
           }
           $row['act_sell'] = $this->auction->getActSell($id);
           $this->smarty->assign("row", $row);
           $this->smarty->display("auction/tuan.html");

   }

}