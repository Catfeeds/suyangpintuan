<?php

/**
 * 任务
 */
class task_Library extends Lowxp_Model
{

    function run()
    {

        /* 团购到期人数没满，处理为失败 */
        $this->_order_common();

        /* 批量处理退款*/
        $this->_order_refund();

        /* 退款处理*/
        $this->_refund();

        /* 时间到处理免费试用和抽奖 */
        $this->_lottery();
        $this->_lottery_coupon();

        /* 超值大牌到期自动下架 */
        //$this->_sale();

        /*团购订单通知*/
        $this->_order_notice();
		
		/* 处理结算   */
        $this->_order_bill();

        /* 处理佣金结算*/
        $this->_comms_bill();

        /* 自动确认收货 */
        $this->_auto_confirm();

        /* 自动成团 */
        $this->_auto_join();
		
		/**积分清算*/
        $this->_score_bill();
    }

    /**
     *    自动确认指定时间后未确认收货的订单
     */
    function _auto_confirm()
    {
        $days = C("confirm_days") > 0 ? C("confirm_days") : 0;
        if ($days == 0) {
            return false;
        }

        $run_time = RUN_TIME - $days * 24 * 60 * 60;
        $sql = "select id,shipping_time from ###_goods_order where status_shipping=2  and status_order=0 and shipping_time<=" . $run_time . " order by id asc limit 0,50";
        $res = $this->db->select($sql);
        if ($res) {
            $this->load->model('order');
            foreach ($res as $k => $v) {
                //订单交易完成
                $set = array('status_shipping' => 10);
                $set['status_pay'] = 10;
                $set['status_order'] = 10;
                $set['confirm_time'] = RUN_TIME;
                $msg = '确认收货';
                $this->order->chageOrderState($v['id'], $set, $msg, array("fish"=>"会员"));
            }
        }

    }

    /**
     *团购到期人数未满则处理为失败
     */
    function _order_common()
    {
        $this->load->model("order");
        $sql = "select * from ###_goods_order_common where status=" . TEAM_ING . " and e_time<=" . RUN_TIME;
        $res = $this->db->select($sql);
        foreach ($res as $k => $v) {
            if($v['team_num']>$v['team_num_yes']){
                $this->db->update("goods_order_common", array("status" => TEAM_FIELD), array("id" => $v['id']));
                $this->db->update("goods_order", array("status_common" => TEAM_FIELD), array("common_id" => $v['id']));
                //组团失败，发送消息
                $this->order->common_faild($v['id']);
            }elseif($v['goods_typeid']==CART_STEP && $v['team_num']==$v['team_num_yes']){//阶梯团 时间结束更改状态
                $this->db->update("goods_order_common", "status=10,u_time=" . RUN_TIME, array("id" => $v['id']));
                $this->db->update("goods_order", array("status_common" => TEAM_SUCC), array("common_id" => $v['id']));
                //阶梯团修改最后价格
                $this->order->common_step($v);
                //组团成功发送消息
                $this->order->common_succ_step($v['id'],$v);
            }

        }

    }

    /**
     *处理免费试用和抽奖
     */
    function _lottery()
    {

        $sql = "select t.* from ###_lottery as t left join ###_goods as g on t.act_id=g.id where t.status=0 and (g.typeid = ".CART_FREE." or g.typeid=".CART_LUCK.") and t.e_time<=" . RUN_TIME;
        $res = $this->db->select($sql);
        $luck_array = array();
        if ($res) {

            foreach ($res as $k => $v) {
                //修改开奖状态
                $this->db->update("lottery", array("status" => 1), array('id' => $v['id']));

                //组团中  修改为组团失败
                $this->db->update("goods_order_common", array("status" => 2), array("status" => 1, "goods_id" => $v['act_id']));

                //随机获取中奖名单
                $order_common = $this->db->select("select order_sn,mid from ###_goods_order where status_common=10 and status_lottery=1 and extension_id={$v['act_id']}");
                if ($order_common) {
                    $temp_array = array_combine(array_column($order_common, "mid"), array_column($order_common, 'order_sn'));
                    if (count($temp_array) > $v['luck_num']) {
                        $luck_array = $this->array_shuffle($temp_array, $v['luck_num']);
                    } else {
                        $luck_array = $temp_array;
                    }

                    //处理中奖名单
                    $log_array = array();
                    foreach ($luck_array as $_k => $_v) {
                        $this->db->update("goods_order", array("status_lottery" => 10), array('order_sn' => $_v));
                        $log_array[$_k]['lottery_id'] = $v['id'];
                        $log_array[$_k]['act_id'] = $v['act_id'];
                        $log_array[$_k]['mid'] = $_k;
                        $log_array[$_k]['order_sn'] = $_v;
                    }
                    shuffle($log_array);
                    $this->db->insertAll("lottery_log", $log_array);
                }
                $send_couponid = $this->db->getstr("select coupon_id from ###_goods where id={$v['act_id']}","coupon_id");
                $this->db->update("goods_order_common", array("status" => 2), array('goods_id' => $v['id']));//组团失败，退款
                $this->db->update("goods_order", array("status_lottery" => 2,"send_couponid"=>$send_couponid), array('status_lottery' => 1, 'extension_id' => $v['act_id']));//修改为未中奖状态
                $this->db->update("goods_order", array("status_common" => 2), array('status_common' => 1, 'extension_id' => $v['act_id']));//未成团修改为失败

            }
        }
    }

    //抽奖未中奖送优惠券
    function _lottery_coupon(){
        $list = $this->db->select("select id,mid,send_couponid from ###_goods_order where status_common=10 and status_lottery=2 and send_couponid>0 and is_robots=0 limit 100");
        if($list){
            $this->load->model("coupon");
            foreach($list as $v){
                $this->coupon->sendOne($v['mid'], $v['send_couponid']);
                $this->db->update("goods_order",array("send_couponid"=>0),array("id"=>$v['id']));
            }
        }
    }

    //随机数组
    function array_shuffle($array, $num = 0)
    {

        if (!is_array($array) || $num == 0) {
            return array();
        }

        if (($count = count($array)) <= 1) {
            return $array;
        }

        $rand_keys = array_rand($array, $num);
        if($num==1)$rand_keys = array($rand_keys);
        $newArr = array();
        foreach ($rand_keys as $v) {
            $newArr[$v] = $array[$v];
        }
        return $newArr;
    }

    //超值大牌到期自动下架
    function _sale()
    {
        $res = $this->db->select("select * from ###_brand where ismenu=1 and end_time>0 and end_time<" . RUN_TIME);
        foreach ($res as $v) {
            $this->db->update("brand", array('ismenu' => 0), array("id" => $v['id']));
            $this->db->update("goods", array('is_sale' => 0), array("bid" => $v['id']));
        }
    }

    //失败批量处理退款 拼团失败和未中奖订单
    function _order_refund(){
        $payment_list = $this->db->select("select pay_id,pay_code from ###_payment where enabled=1");
        foreach($payment_list as $k=>$v){
            $payment[$v['pay_id']] = strpos($v['pay_code'],"alipay")!==false?"alipay":$v['pay_code'];
        }

        //$order_list = $this->db->select("select o.id,o.status_pay,o.order_amount,o.mid,o.order_sn,o.status_lottery,o.extension_code,o.money_paid,o.pay_id,p.out_trade_no from ###_goods_order as o left join ###_pay_log as p on o.id=p.order_id  where  (o.status_common=2 || o.status_lottery=2) and o.common_id>0 and o.is_robots=0 and o.status_order=0 limit 100");
        $order_list = $this->db->select("select id,status_pay,order_amount,mid,order_sn,status_lottery,extension_code,extension_id,money_paid,pay_id from ###_goods_order where (status_common=2 || status_lottery=2) and common_id>0 and is_robots=0 and status_order=0 order by id desc limit 50");
        $order_list = $this->db->lJoin($order_list,"pay_log","order_id,out_trade_no,trade_no,order_amount","id","order_id","","order_type !=".PAY_BS);
        $order_list = $this->db->lJoin($order_list,"goods_order_item","good_id,goods_name","extension_id","good_id");
        foreach ($order_list as $k => $v) {

            if ($v['status_pay'] >0 ) {//已付款和已付定金订单执行退款申请操作
                if ($v['status_lottery'] == 10) continue;
                $res = $this->db->update("goods_order", array("status_order" => 2), array("id" => $v['id']));
                if ($res > 0) {
                    //添加退款记录
                    $this->load->model("refund");
                    $temp['order_id'] = $v['id'];
                    /*if($v['extension_code']==CART_STEP){
                        $temp['order_amount'] = $v['money_paid'];
                    }else{
                        $temp['order_amount'] = $v['order_amount'];
                    }*/
                    $temp['order_amount'] = $v['order_amount'];
                    $temp['out_trade_no'] = $v['out_trade_no'];
                    $temp['trade_no'] = $v['trade_no'];
                    $temp['payment'] = $payment[$v['pay_id']];
                    $this->refund->addRefundLog($temp);

                    // 模版消息 4 会员申请退款 {插入昵称},{插入订单号}
                    // template_msg_action start
                    //$this->load->model('template_msg');
                    //$msgParams = array(getUsername($v['mid']), $v['order_sn']);
                    //$this->template_msg->inQueue(4, 0, $msgParams);
                    // template_msg_action end

                    // 微信模版消息 refund_apply 退款申请
                    // wxtemplate_action start
                    $this->load->model('wxtemplate');
                    $msgParams = array(
                        "url" => RootUrl."member/order/?order_sn=".$v['order_sn'],
                        "orderProductPrice"=>$temp['order_amount'],
                        "orderProductName"=>$v['goods_name'],
                        "orderName"=>$v['order_sn'],
                    );
                    $this->wxtemplate->inQueue($v['mid'],'refund_apply',$msgParams);
                    // wxtemplate_action end

                }
            } else {//未付款订单则关闭订单
                $res = $this->db->update("goods_order", array("status_order" => 5), array("id" => $v['id']));
            }

        }
    }

    //处理退款
    function _refund()
    {
        $this->load->library("wechatrefund");
        $this->load->library("wechatapprefund");
        $this->load->library("alipayrefund");
        $this->load->model("refund");
        $res = $this->db->select("select * from ###_refund_log where is_refund=0 limit 0,50");
        $res = $this->db->lJoin($res, "goods_order", "id,mid,order_sn", "order_id", "id", "order_");
        $wxtype = '';
        foreach ($res as $k => $v) {
            if ($v['order_amount'] > 0 && $v['out_trade_no']) {
                if($v['payment'] == 'alipay'){###支付宝退款
                    $v['out_refund_no'] = date("YmdHis") . $v['id'];
                    $result = $this->alipayrefund->refund($v);
                    if ($result) {
                        $this->refund->updateRefundLog($v['id'], array("out_refund_no" => $v['out_refund_no'], "is_refund" => 3, "order_id" => $v['order_id']));
                    }
                }else{###微信退款
                    $wxtype = empty($wxtype)?$v['payment']:$wxtype;
                    $v['out_refund_no'] = date("YmdHis") . $v['id'];
                    if($wxtype=='wxpay'){//微信和微信app一次只退一种方式，防止冲突
                        $result = $this->wechatrefund->refund($v,$v['payment']);
                    }elseif($wxtype=='wxpayapp'){
                        $result = $this->wechatapprefund->refund($v,$v['payment']);
                    }
                    if ($result['return_code'] == "SUCCESS" && $result['result_code']=='SUCCESS' || $result['err_code_des']=='订单已全额退款') {
                        $this->refund->updateRefundLog($v['id'], array("out_refund_no" => $v['out_refund_no'], "is_refund" => 1, "order_id" => $v['order_id']));

                        //微信消息模板
                        $msgParams = array(
                            "url" => note_url("/member/order/?order_sn=".$v['order_order_sn']),
                            "keyword1"=>$v['order_order_sn'],
                            "keyword2"=>$v['order_amount'],
                            "keyword2"=>$v['order_amount'],
                        );
                        $wxmsg[] = array(0=>$v['order_mid'],1=>"refund_note",2=>$msgParams);

                    }
                }
            } elseif ($v['order_amount'] == 0) {
                $this->refund->updateRefundLog($v['id'], array("is_refund" => 1, "order_id" => $v['order_id']));
            }
        }

        // 微信模版消息 refund_note 退款结果通知
        // wxtemplate_action start
        $this->load->model('wxtemplate');
        $this->wxtemplate->inQueueMany($wxmsg);
        // wxtemplate_action end

    }

    //组团订单通知
    function _order_notice()
    {
        //$res = $this->db->select("select id,mid,c_time from ###_goods_order_common where status=1 and team_num_yes<team_num limit 100");
        $end_time = RUN_TIME-24*3600;
        $res = $this->db->select("select common_id,mid,c_time from ###_goods_order where status_common=".TEAM_ING." and extension_code !=5  and extension_code !=6 and c_time>".$end_time." limit 100");
        $res = $this->db->lJoin($res, "member", "mid,mobile,username", "mid", "mid");
        $res = $this->db->lJoin($res, "member_detail", "mid,photo,nickname", "mid", "mid");
        foreach ($res as $k => $v) {
            //$v['avatar'] = photo_path($v['mid']);
            $v['avatar'] = empty($v['photo']) ? '/common/photo.gif' : yunurl($v['photo']);
            $wx_url = C('wx_url');
            if(!empty($wx_url)){
                $v['url'] = REQUEST_SCHEME.C('wx_url')."/goods/team/" . $v['common_id'];
            }else{
                $v['url'] = RootUrl."goods/team/" . $v['common_id'];
            }
            $name = !empty($v['nickname'])?$v['nickname']:$v['username'];
            $v['message'] = formatTime($v['c_time'])."," . cut_str($name,24) . "成功参团，等你来拼！";
            unset($v['id']);
            unset($v['mid']);
            unset($v['mobile']);
            $res[$k] = $v;
        }
        $content = "var user = " . json_encode($res);
        $file_dir = $this->load->config('picture', 'tmp_dir');
        if (!is_dir($file_dir)) mkdir($file_dir, 0777, true);
        file_put_contents($file_dir . "order_json.js", $content);
    }
	
	//结算商家订单，结算公式（订单金额+平台抵用券-退款金额-佣金）
    function _order_bill(){
    	$days = C("refund_days") > 0 ? C("refund_days") : 0;
    	$run_time = RUN_TIME - $days * 24 * 60 * 60;
    	//$sql = "select id,sid,order_amount,coupon_id_num,coupon_id_sid_num,comm_amount,order_sn from ###_goods_order where confirm_time<=" . $run_time . " and status_pay=10 and status_shipping = 10 and status_order=10 and sid>0 and pay_time>0 and order_bill=0 and is_robots=0";
    	$sql = "select id,sid,order_amount,coupon_id_num,coupon_id_sid_num,comm_amount,order_sn from ###_goods_order where status_pay=10 and status_shipping = 10 and status_order=10 and sid>0 and pay_time>0 and order_bill=0 and is_robots=0";
    	$res = $this->db->select($sql);
    	if ($res) {
    		$this->load->model('order');
    		$this->load->model('business');
    		
    		foreach ($res as $k => $v) {
    			$amount = $v['order_amount']+$v['coupon_id_num']-$v['comm_amount'];
    			//记录账户明细
    			/* $log_arr = array();
    			$log_arr['mid'] = $v['sid'];
    			$business = $this->business->get($v['sid'],'name');
    			$log_arr['username'] = $business['name'];
    			$log_arr['user_money'] = $amount; //余额
    			$log_arr['desc'] = '订单结算 ' . $v['order_sn'];
    			$this->business->accountlog(ACT_BILL, $log_arr); */
    			
    			//记录结算明细
    			$bill_log = array();
    			$bill_log['sid'] = $v['sid'];
    			$business = $this->business->get($v['sid'],'name');
    			$bill_log['name'] = $business['name'];
    			$bill_log['user_money'] = $amount;
    			$bill_log['coupon_id_num'] = $v['coupon_id_num'];
    			$bill_log['coupon_id_sid_num'] = $v['coupon_id_sid_num'];
    			$bill_log['comm_amount'] = $v['comm_amount'];
    			$bill_log['order_sn'] = $v['order_sn'];
    			$bill_log['desc'] = '订单结算 ' . $v['order_sn'];
    			$this->business->billlog(ACT_BILL,$bill_log);
    			//订单交易完成，结算
    			$set = array('order_bill' => 1);
    			$msg = '商家结算';
    			$this->order->chageOrderState($v['id'], $set, $msg, array("fish"));
    		}
    	}
    }
	
	//结算积分 每月22结算上个月的订单
    function _score_bill(){
    	$new_d = date("d",RUN_TIME);
    	if($new_d>=22){
    		$start_time = strtotime(date('Y-m-01', strtotime('-1 month')));//上个月月初
    		$end_time =  strtotime(date('Y-m-t', strtotime('-1 month')));//上个月月末
    		//查找上个月初到月末的所有消费记录，并予以清算积分
    		$res_list = $this->db->select("select mid,score from ###_goods_order_score where status=0 and addtime>=$start_time and addtime<=$end_time limit 100");
    		
    		foreach($res_list as $k=>$v){
    			$this->load->model('exchange');
    			if($this->exchange->power){
        			$this->exchange->action(array('order_sn'=>$order['order_sn'],'score'=>$order['score'],'type'=>"0"));
    				$this->db->update('goods_order_score',array("status"=>1,"updatetime"=>RUN_TIME),array("id"=>$v['id']));
    			}
    		}
    	}
    }

    //结算佣金 每月22结算上个月的订单
    function _comms_bill(){
        $new_d = date("d",RUN_TIME);
        if($new_d>=22){
            $start_time = strtotime(date('Y-m-01', strtotime('-1 month')));//上个月月初
            $end_time =  strtotime(date('Y-m-t', strtotime('-1 month')));//上个月月末

            $res_list = $this->db->select("select id,mid,commission from ###_commission where status=0 and addtime>=$start_time and addtime<=$end_time limit 100");

            foreach($res_list as $k=>$v){
                $res = $this->db->update('member', "commission = commission + {$v['commission']},commission_total = commission_total + {$v['commission']}", array('mid' => $v['mid']));
                if($res!==false)$this->db->update('commission',array("status"=>1,"updatetime"=>RUN_TIME),array("id"=>$v['id']));
            }
        }
    }

    //自动成团
    function _auto_join(){
        if(C("auto_join")>0){
            $time = C("auto_join") > 0 ? C("auto_join") : 12;
            $end_time = RUN_TIME-$time*3600;
            $sql = "select id from ###_goods_order_common where status=1 and c_time<=".$end_time." and goods_typeid = ".CART_TUAN." limit 100";
            $list = $this->db->select($sql);
            $this->load->model("robots");
            if($list){
                foreach($list as $v){
                    $this->robots->join_team($v['id']);
                }
            }
        }
    }

}

?>