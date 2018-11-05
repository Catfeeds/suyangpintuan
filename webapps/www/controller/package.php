<?php
/**
 * 套餐控制器
 * ============================================================================
 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。
 * 网站地址: http://www.lnest.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 */

class package extends Lowxp{

    function __construct(){
        parent::__construct();
        $this->load->model('auction');
    }

    /**
     * @param int $status 状态
     * @param int $page 页码
     */
    function index($status=UNDER_WAY, $page=1){
        $status=UNDER_WAY;

        $data = array();
        $data['nav'] = 1;
        $data['status'] = $status;
        $data['status_row'] = $this->auction->status[$status];

        $size = 10;
        $data['list'] = $this->auction->getList($size,$page,0,'',CART_PACK,array(
            'qishu' => 0,
			'order' => "a.act_id DESC",
        ));

        #异步加载
        if(isset($_GET['load'])){
            if($data['list']){
                $content = '';
                foreach($data['list'] as $v){
                    $this->smarty->assign('m', $v);
                    $content .= $this->smarty->fetch('package/lbi/package_list.html');
                }
                echo $content;die;
            }
        }

        $this->smarty->assign('data', $data);
        $this->display_before(array('title'=>'私人定制'));
        $this->smarty->display('auction/package.html');
    }

    /**套餐详情页
     * @param $act_id
     * @param int $order_sn
     */
    function view($act_id, $order_sn=0){
        $this->load->model('order');
        $row = $this->auction->get($act_id);
        if(empty($row)){ $this->msg();die; }
        $row['ext_info'] = unserialize($row['ext_info']);
        zzcookie('pack_id', $act_id);

        if(!$_SESSION['mid'] && IS_WECHAT){
            $this->msg('','/member/login?back_url='.'/package/'.$act_id.'/'.$order_sn);
        }

        if(!$order_sn && !isset($_GET['buy'])){
            //查看会员是否已经购买此套餐，并在有效期内
            $sql = "SELECT id,order_sn FROM `goods_order` WHERE
                mid='".$_SESSION['mid']."' AND
                end_time>'".time()."' AND
                extension_code='".CART_PACK."' AND
                extension_id='".$act_id."'";
            $order = $this->db->get($sql);
            if($order){
                $order_sn = $order['order_sn'];
            }
        }

        if($order_sn){
            $data = $this->order->pack_select($order_sn);

            if($data['error']==1){
                $link = array(
                    array('link'=>'javascript:history.go(-1);','text'=>'返回'),
                    array('link'=>'/package/view/'.$act_id.'?buy','text'=>'重新购买'),
                );
                //未付款
                if(isset($data['un_pay']) && $data['un_pay'] == 1){
                    $link = array_merge($link,array(
                        array('link'=>'/member/order?order_sn='.$order_sn,'text'=>'去付款'),
                    ));
                }
                $this->msg($data['msg'],array('iniframe'=>false,'link'=>$link));exit;
            }

            $row['order_sn'] = $order_sn;
            $row['count_qishu'] = $data['count_qishu'];
            $row['max_qishu'] = $data['max_qishu'];
            $row['goods_list'] = $data['goods_list'];
        }else{
            $row['goods_list'] = $this->auction->getPackGoods($row['goods_list']);
        }

        $data = array('row'=>$row,'id'=>$act_id);
        $this->smarty->assign('data', $data);
        $this->display_before($data['row']);
        $this->smarty->display('auction/package_view.html');
    }

    function agree(){
        $this->smarty->display('auction/package_agree.html');
    }

}