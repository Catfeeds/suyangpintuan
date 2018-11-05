<?php
/**
 * Name 拼团管理
 * Class member_adm
 */
class team extends Lowxp {
	function __construct() {
		#按钮
		$this->btnMenu = array(
                    0 => array('url' => '#!team/index', 'name' => '拼团管理'),
		);

		parent::__construct();

		$this->load->model('team');
		$this->load->model('order');
	}

	function index($page = 1) {

		$option = array();
                $option['page'] = $page;
                $option['where']=" AND sid=".BID;

                //搜索赋值
                ${$_REQUEST['k']} = $_REQUEST['q'];
                if(!empty($order_sn)){
                    $common_id = $this->db->getstr("select common_id from ###_goods_order where order_sn='{$order_sn}'","common_id");
                    if($common_id>0){
                        $id = $common_id;
                    }
                }
                if(!empty($id)){
                    $option['where'].=" AND id=".intval($id);
                }
                if(!empty($goods_id)){
                    $option['where'].=" AND goods_id=".intval($goods_id);
                }
                if(!empty($username)){
                    $sql = "SELECT mid FROM ###_member WHERE  `username` LIKE '%" . addslashes($username) . "%'";
                    $tmp = $this->db->select($sql);
                    if ($tmp) {
                            $mids = join(",", array_column($tmp, 'mid'));
                            $option['where'].= " AND mid in (" . $mids . ")";
                    } else {
                            $option['where'].= " AND mid=0 ";
                    }
                }
                if($_REQUEST['status']>0){
                    $option['where'].=" AND status=".intval($_REQUEST['status']);
                }
                if($_REQUEST['goods_typeid']>0){
                    $option['where'].=" AND goods_typeid=".intval($_REQUEST['goods_typeid']);
                }
                if ($_REQUEST['is_robots'] || $_REQUEST['is_robots']=='0') {
                    $option['where'] .= " AND is_robots=" . intval($_REQUEST['is_robots']);
                }

                $c_stime = strtotime($_REQUEST['c_stime']);
                $c_etime = strtotime($_REQUEST['c_etime']);
                $e_stime = strtotime($_REQUEST['e_stime']);
                $e_etime = strtotime($_REQUEST['e_etime']);
                if(!empty($c_stime)){
                    $option['where'].=" AND c_time>=".$c_stime;
                }
                if(!empty($c_etime)){
                    $option['where'].=" AND c_time<=".$c_etime;
                }
                if(!empty($e_stime)){
                    $option['where'].=" AND e_time>=".$e_stime;
                }
                if(!empty($e_etime)){
                    $option['where'].=" AND e_time<=".$e_etime;
                }
                #快速排序
                $order .= isset($_GET['sortby']) ? $_GET['sortby'] : 'id';
		$order .= ' ';
		$order .= isset($_GET['sortorder']) ? $_GET['sortorder'] : 'DESC';
                $option['orderby'] = $order;

                $orderCommon = $this->order->orderCommon;
                $actTypes = $this->order->actTypes;
                unset($actTypes[0]);
                $list = $this->team->getList($option);

                foreach($list as $k=>$v){
                    $v['status_name'] = $orderCommon[$v['status']];
                    $v['type_name'] = $actTypes[$v['goods_typeid']]['title'];
                    $img = json_decode($v['goods_thumb'], true);
                    $v['img_src'] = yunurl($img[0]['path']);
                    $v['ship_num'] = 0;//待发货
                    $v['refund_num'] = 0;//待退款
                    if($v['status']==2){//拼团失败
                        $v['refund_num'] = $this->db->getstr("select count(1) as num from ###_goods_order where common_id='{$v['id']}' and status_pay=10 and status_order=0 and is_robots=0","num");
                    }elseif($v['status']==10){//拼团成功
                        if($v['goods_typeid']==CART_LUCK || $v['goods_typeid']==CART_FREE){//抽奖和免费试用 中奖的发货，没中奖的退款
                            $v['ship_num'] = $this->db->getstr("select count(1) as num from ###_goods_order where common_id='{$v['id']}' and status_pay=10 and status_shipping<2 and status_lottery=10  and is_robots=0","num");
                            $v['refund_num'] = $this->db->getstr("select count(1) as num from ###_goods_order where common_id='{$v['id']}' and status_pay=10 and status_order=0 and status_lottery=2  and is_robots=0","num");
                        }else{
                            $v['ship_num'] = $this->db->getstr("select count(1) as num from ###_goods_order where common_id='{$v['id']}' and status_pay=10 and status_shipping<2 and is_robots=0","num");
                        }
                    }

                    $list[$k] = $v;
                }
                //echo "<pre>";print_r($list);exit;
		$this->smarty->assign('orderCommon', $orderCommon);
		$this->smarty->assign('actTypes', $actTypes);
		$this->smarty->assign('list', $list);

		$this->smarty->display('business/team/list.html');
	}

	/**
	 * 订单详情
	 * @param string $id
	 */
	function detail($id = '') {
		is_numeric($id) || die;
        $orderCommon = $this->order->orderCommon;
		$data = $this->team->get($id,BID,array("where"=>" and a.is_robots=0 "));
        $data['row']['status_name'] = $orderCommon[$data['row']['status']];
        foreach($data['list'] as $k=>$v){
            $data['list'][$k]['status_name'] = $this->order->order_status_name($v,2);
            $data['list'][$k]['status_id'] = $this->order->order_status('', $v, 2);
        }
        $this->load->model('order');
        $data['goods']['typeid_name'] = $this->order->actTypes[$data['goods']['typeid']]['title'];
        $data['row']['user_num'] = count($data['list']);
        $data['row']['robots_num'] = $data['row']['team_num_yes']-$data['row']['user_num'];
		$this->smarty->assign('data', $data);
		$this->smarty->display('business/team/detail.html');
	}

        /**
	 * 退款 Feng 2016-05-26
	 * @param string $order_id
	 */
	function refund() {
                $id = intval($_REQUEST['id']);
		is_numeric($id) || $this->fatalError('参数错误!');

		$order_common = $this->db->get("SELECT * FROM ###_goods_order_common WHERE id=" . $id);
		isset($order_common['id']) || $this->fatalError('订单不存在!');
                //if($order_common['status']==10)$this->fatalError('该订单已成团!');
                $payment_list = $this->db->select("select pay_id,pay_code from ###_payment where enabled=1");
                foreach($payment_list as $k=>$v){
                    $payment[$v['pay_id']] = strpos($v['pay_code'],"wxpay")!==false?"wxpay":"alipay";
                }
                $count = 0;
                $order_list = $this->db->select("select o.id,o.status_pay,o.order_amount,o.mid,o.order_sn,o.status_lottery,o.extension_code,o.money_paid,o.pay_id,p.out_trade_no from ###_goods_order as o left join ###_pay_log as p on o.id=p.order_id  where o.common_id={$order_common['id']} ");
                foreach($order_list as $k=>$v){

                    if($v['status_pay']==10){
                        if($v['status_lottery']==10)continue;
                        $res = $this->db->update("goods_order", array("status_order" => 2), array("id" => $v['id']));
                        if($res>0){
                            //添加退款记录
                            $this->load->model("refund");
                            $temp['order_id'] = $v['id'];
                            if($v['extension_code']==CART_STEP){
                                $temp['order_amount'] = $v['money_paid'];
                            }else{
                                $temp['order_amount'] = $v['order_amount'];
                            }
                            $temp['out_trade_no'] = $v['out_trade_no'];
                            $temp['payment'] = $payment[$v['pay_id']];
                            $this->refund->addRefundLog($temp);

                            // 模版消息 4 会员申请退款 {插入昵称},{插入订单号}
                            // template_msg_action start
                            $this->load->model('template_msg');
                            $msgParams = array(getUsername($v['mid']), $v['order_sn']);
                            $this->template_msg->inQueue(4, 0, $msgParams);
                            // template_msg_action end

                            $count++;
                        }
                    }else{
                        $res = $this->db->update("goods_order", array("status_order" => 5), array("id" => $v['id']));
                    }

                }
                $this->tip("本团退款".$count."单", array('inIframe' => true));
                $this->exeJs("parent.com.xhide();parent.main.refresh()");

	}

    function qrcode(){
        $this->smarty->display('manage/team/qrcode.html');
    }
}