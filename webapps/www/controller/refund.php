<?php

/**
 * 退款、退货控制器
 * ============================================================================
 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。
 * 网站地址: http://www.lnest.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 */
class refund extends Lowxp
{

    function __construct()
    {
        parent::__construct();
        if (!defined("MID")) login();
        $this->load->model("refund");
    }

    function index($page = 1)
    {
        $option['page'] = $page;
        $option['mid'] = MID;
        $list = $this->refund->getList($option);

        $list = $this->db->lJoin($list, 'goods_order', 'id,order_amount,order_sn,sid,money_paid,amount', 'order_id', 'id', "o_");
        $list = $this->db->lJoin($list, 'business', 'id,name,kf_online', 'sid', 'id', "b_");
        if ($list) {
            $orderIds = array_column($list, "order_id");
            $items = $this->db->select("SELECT * FROM ###_goods_order_item WHERE order_id IN(" . implode(',', $orderIds) . ")");
            $items = $this->db->lJoin($items, 'goods', 'id,typeid,thumb,thumbs,team_num,price,team_price,market_price,discount_type,discount_amount', 'good_id', 'id', 'goods_');

            $this->load->model("goods");
            $orderItems = array();
            foreach ($items as $k => $v) {
                $thumb = $this->db->getstr("select thumb from ###_goods_item where goods_id={$v['goods_id']} and spec='{$v['spec']}'", 'thumb');
                $v['thumb'] = !empty($thumb) ? $thumb : $v['goods_thumb'];
                $v['thumbs'] = !empty($thumb) ? $thumb : $v['goods_thumbs'];
                unset($v['goods_thumb']);
                unset($v['goods_thumbs']);

                $v = $this->goods->getThumb($v, 1, array('thumb'));

                $items[$k] = $v;
                if (!isset($orderItems[$v['order_id']])) {
                    $orderItems[$v['order_id']] = array();
                }
                $orderItems[$v['order_id']][] = $v;
            }
            foreach ($list as $k => $v) {
                $list[$k]['status_name'] = $this->refund->status_name($v);
                $list[$k]['goods'] = $orderItems[$v['order_id']];
				if($v['sid']==0){
                	$list[$k]['kf_online'] = C('kf_online');
                }else{
                	$list[$k]['kf_online'] = $v['store_kf_online'];
                }
            }
        }
        if (isset($_GET['load'])) {
            $content = '';
            if ($list) {
                foreach ($list as $v) {
                    $this->smarty->assign("m", $v);
                    $content .= $this->smarty->fetch('refund/lbi/refund_list.html');
                }
            }
            echo $content;
            die;
        }
        $data['list'] = $list;
        $this->smarty->assign("data", $data);
        $this->smarty->display("refund/list.html");
    }

    /**
     * Feng 2016-05-26
     * 退货申请
     */
    function apply($order_id = 0)
    {

        $row = $this->db->get("select * from ###_goods_order where id={$order_id} and mid=" . MID);
        if ($row == false) $this->error('订单有误！');

        if (isset($_POST['Submit'])) {

            $pay_info = $this->db->get("select order_amount from ###_pay_log where order_id={$order_id} and order_type !=".PAY_BS);

            $post = $_POST['post'];
            $post['mid'] = MID;
            $post['order_id'] = $order_id;
            $post['c_time'] = RUN_TIME;
            $post['refund_amount'] = $pay_info['order_amount'];
            $post['sid'] = $row['sid'];
            //上传图片
            if ($_POST['pic']) {
                $post['pic'] = join("|", $_POST['pic']);
            }

            $is_exist = $this->db->get("select 1 from ###_refund where order_id={$post['order_id']} and status=0");
            if ($is_exist) {
                $this->error('您已申请，请等待商家处理');
            }
            $res = $this->db->insert("refund", $post);

            if ($res !== false) {
                //$order_status = $post['type']==1?11:12;
                $r = $this->db->update("goods_order", array("is_refund" => 2), array("id" => $order_id));

                $order = $this->db->get("select * from ###_goods_order where id=" . $order_id);
                // 模版消息 4 会员申请退款 {插入昵称},{插入订单号}
                // template_msg_action start
                $this->load->model('template_msg');
                $msgParams = array(getUsername($order['mid']), $order['order_sn']);
                $this->template_msg->inQueue(4, 0, $msgParams);
                // template_msg_action end
            }
            redirect('/refund/detail/' . $res);
        }
        $reasonType = $this->refund->reasonTypes;
        $this->smarty->assign("reasontype", $reasonType);
        $this->smarty->display("refund/apply.html");

    }
    /*
     * 详情
     */
    function detail($id)
    {

        $row = $this->db->get("select * from ###_refund where id={$id} ");
        $this->smarty->assign("row", $row);
        $this->smarty->display("refund/detail.html");

    }
    /*
     * 退货快递
     */
    function shipping($id)
    {

        if (isset($_POST['Submit'])) {

            $post = $_POST['post'];
            $res = $this->db->update("refund", array("express" => $post['express'], "express_num" => $post['express_num'], "status_shipping" => 1), array("id" => $post['id']));
            if ($res !== false) {
                $this->success('发货成功，请等待商家处理', '/refund/index');
            } else {
                $this->error('修改数据库失败');
            }
        }

        $row = $this->db->get("select * from ###_refund where id={$id} order by id desc limit 1");

        if ($row['address_id']) $row['address'] = $this->db->get("select name,address,mobile from ###_business_address where id={$row['address_id']}");

        $this->smarty->assign("row", $row);
        $this->smarty->display("refund/shipping.html");
    }

    /*
     * 交易投诉
     */
    function complain($id){

        if (isset($_POST['Submit'])) {

            $post = $_POST['post'];
            $res = $this->db->update("refund", array("complain_content" => $post['complain_content'], "complain_status" => 1), array("id" => $post['id']));
            if ($res !== false) {
                $this->success('提交成功，请等待平台处理', '/refund/index');
            } else {
                $this->error('修改数据库失败');
            }
        }
        $row = $this->db->get("select * from ###_refund where id={$id} order by id desc limit 1");
        $this->smarty->assign("row", $row);
        $this->smarty->display("refund/complain.html");
    }

}