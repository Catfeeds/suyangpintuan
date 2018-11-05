<?php

/**
 * Class user_model
 */
class order_model extends Lowxp_Model
{
    //订单类型
    public $orderCode = array(
        CART_GOODS => array('商品', '/goods/show/%s'),
        CART_KILL => array('秒杀', '/kill/show/%s'),
        //CART_TUAN => array('团购', '/tuan/show/%s'),
        //CART_PACK => array('套餐', '/package/view/%s'),
        //CART_CRDT => array('积分商品', '/goods/show/%s'),
    );
    //综合状态，非货到付款
    public $status = array(
        CS_AWAIT_PAY => array('title' => '待付款', 'where' => array('status_pay' => array('0','1'), 'status_shipping' => array('0'), 'status_order' => array('0'))),
        CS_AWAIT_SHIP => array('title' => '待发货', 'where' => array('status_pay' => array('10'), 'status_shipping' => array('0', '1'), 'status_order' => array('0'), 'status_common' => array('10', '0'))),
        CS_AWAIT_GET => array('title' => '待收货', 'where' => array('status_pay' => array('10'), 'status_shipping' => array('2'), 'status_order' => array('0'))),
        CS_FINISHED => array('title' => '交易完成', 'where' => array('status_pay' => array('10'), 'status_shipping' => array('10'), 'status_order' => array('10'))),
        CS_CENCEL => array('title' => '取消', 'where' => array('status_pay' => array('0'), 'status_shipping' => array('0'), 'status_order' => array('1'))),
        //CS_REFUND => array('title' => '退货', 'where' => array('status_pay' => array('10'), 'status_shipping' => array('10'), 'status_order' => array('2', '3', '4'))),
        CS_REFUND_ING => array('title' => '退款中', 'where' => array('status_pay' => array('10'), 'status_shipping' => array('0', '10'), 'status_order' => array('2'))),
        CS_REFUND_SUCC => array('title' => '退款成功', 'where' => array('status_pay' => array('10'), 'status_shipping' => array('0', '10'), 'status_order' => array('3'))),
    );
    //综合状态，货到付款
    public $status_cod = array(
        CS_AWAIT_SHIP => array('title' => '待发货', 'where' => array('status_pay' => array('0'), 'status_shipping' => array('0', '1'), 'status_order' => array('0'), 'status_common' => array('10', '0'))),
        CS_AWAIT_GET => array('title' => '待收货', 'where' => array('status_pay' => array('0'), 'status_shipping' => array('2'), 'status_order' => array('0'))),
        CS_AWAIT_PAY => array('title' => '待付款', 'where' => array('status_pay' => array('0'), 'status_shipping' => array('2', '10'), 'status_order' => array('0'))),
        CS_FINISHED => array('title' => '交易完成', 'where' => array('status_pay' => array('10'), 'status_shipping' => array('10'), 'status_order' => array('10'))),
    );
    //付款状态
    public $payStatus = array(
        0 => '未付款',
        1=>'已付定金',
        10 => '已付款',
    );
    //发货状态
    public $shippingStatus = array(
        0 => '未发货',
        1 => '备货中',
        2 => '已发货',
        10 => '已收货',
    );
    //订单状态
    public $orderStatus = array(
        0 => '未完成',
        1 => '已取消',
        2 => '退款中',
        3 => '退款完成',
        4 => '退款失败',
        5 => '交易关闭',
        10 => '交易完成',
    );
    //财务结算
    public $balanceStatus = array(
    		0 => '财务未结算',
    		1 => '财务已结算',
    );
    //拼团状态 1组团中,2组团失败，10组团成功
    public $orderCommon = array(
        1 => '组团中',
        2 => '组团失败',
        10 => '组团成功',
    );
    //开奖状态 0开奖中，2未中奖，10中奖
    public $orderLottery = array(
        1 => '开奖中',
        2 => '未中奖',
        10 => '中奖',
    );
    public $actTypes = array(
        CART_GOODS => array('id' => CART_GOODS, 'title' => '单独购买','desc'=>'单独购买'),
        CART_TUAN => array('id' => CART_TUAN, 'title' => '拼团','desc'=>'都可以开团和参团'),
        CART_YUAN => array('id' => CART_YUAN, 'title' => '新人专享','desc'=>'都可以开团，只有新用户才能参团'),
        CART_KILL => array('id' => CART_KILL, 'title' => '秒杀','desc'=>'都可以开团和参团。有时间和库存限制，其中一个条件达到则停止'),
        //CART_SALE => array('id' => CART_SALE, 'title' => '特价','desc'=>'都可以开团，只有新用户才能参团'),
        CART_FREE => array('id' => CART_FREE, 'title' => '免费试用','desc'=>'都可以开团，只有新用户才能参团。有时间限制，时间结束抽取几名幸运者发货'),
        CART_LUCK => array('id' => CART_LUCK, 'title' => '抽奖','desc'=>'都可以开团和参团。有时间限制，时间结束抽取几名幸运者发货'),
        CART_AA => array('id' => CART_AA, 'title' => '邻居团','desc'=>'都可以开团和参团。用户支付的价格=团购价/拼团人数'),
        CART_SHARE => array('id' => CART_SHARE, 'title' => '推广团','desc'=>'都可以开团和参团。组团成功，团长可以拿到对应的佣金'),
        CART_OPTION => array('id' => CART_OPTION, 'title' => '自选团','desc'=>'都可以开团和参团。选择不同人数的团价格不同'),
        CART_STEP => array('id' => CART_STEP, 'title' => '阶梯团','desc'=>'都可以开团和参团。一个团不同人数价格不同'),
        CART_ZC => array('id' => CART_ZC, 'title' => '新品预售','desc'=>'和普通团一样，发货统一到一定时间才发'),
        //CART_CITY => array('id' => CART_CITY, 'title' => '同城','desc'=>'同城里面显示同个城市产品'),
    );
	
	public $actTypes_exchange = array(
    	CART_EXCHANGE => array('id' => CART_EXCHANGE, 'title' => '积分兑换','desc'=>'积分与支付共同使用'),
    );

    //保存数据
    function save()
    {
        $post = $_POST['post'];

        $id = intval($_POST['id']);

        #保存
        $res = $this->db->save('goods_order', $post, '', array('id' => $id));

        admin_log('编辑订单信息：' . $post['order_sn']);
        return array('code' => 0, 'type' => 'update', 'message' => '更新成功');
    }

    /**
     * 读取每个订单的商品列表
     * @param $data
     * @param array $params
     * @return mixed
     */
    function unionOrderGoods($data, $params = array())
    {
        $orderIds = array();
        foreach ($data as $v) {
            $orderIds[] = $v['id'];
        }

        if (count($orderIds) == 0) {
            return $data;
        }

        $this->load->model('goods');

        $items = $this->db->select("SELECT * FROM ###_goods_order_item WHERE order_id IN(" . implode(',', $orderIds) . ")");

        $items = $this->db->lJoin($items, 'goods', 'id,typeid,thumb,thumbs,team_num,price,team_price,market_price,discount_type,discount_amount,sid,nation_id', 'good_id', 'id', 'goods_');

        $orderItems = array();
        foreach ($items as $k => $v) {
            $thumb = '';
            if(!empty($v['spec'])){
                $thumb = $this->db->getstr("select thumb from ###_goods_item where goods_id={$v['goods_id']} and spec='{$v['spec']}'", 'thumb');
            }
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
        foreach ($data as $key => $val) {
            //获取扩展商品信息
            // if ($val['extension_code'] && $val['extension_id']) {
            // 	$data[$key]['extension'] = $this->db->get("SELECT act_name FROM ###_goods_activity WHERE act_id=" . $val['extension_id']);
            // }

            if (isset($orderItems[$val['id']])) {
                $data[$key]['goods'] = $orderItems[$val['id']];
            } else {
                $data[$key]['goods'] = array();
            }

            $data[$key]['status_name'] = $this->order_status_name($val);

            $data[$key]['order_code'] = $this->actTypes[$val['extension_code']]['title'];

            //$data[$key]['goods_url'] = sprintf($this->orderCode[$val['extension_code']][1], $val['extension_id']);

            //综合状态
            $data[$key]['status_id'] = $this->order_status('', $val, 2);
        }
        $data = $this->db->lJoin($data, 'member', 'mid,username,mobile,status', 'mid', 'mid', 'm_');

        $data = $this->db->lJoin($data, 'express', 'id,name,pinyin,id,status,free', 'express', 'id', 'express_');
//        $data = $this->db->lJoin($data, 'share', 'id,order_id', 'id', 'order_id', 'share_');
        return $data;
    }

    /**
     * 修改订单状态
     * @param $order_id
     * @param $status
     * @param $state_info
     * @param array $params
     * @return bool|string
     */
    function chageOrderState($order_id, $status, $state_info, $params = array())
    {
        //$order = $this->db->get("SELECT id,status_pay,status_shipping,status_order FROM goods_order WHERE id=".$order_id);
        //if(!isset($order['id']))return '-10002';#订单不存在

        $input = array(
            'order_id' => $order_id,
            'state_info' => $state_info,
            'c_time' => RUN_TIME,
            'userid' => defined('UID') ? UID : 0,
            'username' => isset($params['fish'])?$params['fish']:USER,
        );

        $set = array();
        foreach ($status as $k => $v) {
            $set[$k] = $v;
        }
        //收到货款
        if (isset($params['amount'])) {
            $set['money_paid'] = $params['amount'];

            $set['amount'] = 0;
        }

        $this->db->insert('goods_order_log', $input);
        if (!empty($set)) {

            $order = $this->db->get("SELECT * FROM ###_goods_order WHERE id=" . $order_id);

            $this->db->update('goods_order', $set, array('id' => $order_id));

            //确认收货和退货成功  Feng 2016-05-26 start
            if ($set['status_order'] == 10 ) {
                 #确认收货佣金
                //添加佣金记录
                if ($order['is_comm'] == 0) {
                    $this->load->model("member");
                    $this->member->comms_record($order);
                }
				
				//如果是第三方支付，需要结算积分，这里发积分记录
                if($order['money_paid'] > 0){
                	//记录，该订单需要发送积分的记录
                	$this->load->model("exchange");
                	if($this->exchange->power){
                		$this->exchange->score_record($order);
                	}
                }

                if ($set['status_order'] == 10) {
                    // 模版消息 3 订单确认收货 {插入昵称},{插入订单号}
                    // template_msg_action start
                    $this->load->model('template_msg');
                    $msgParams = array(getUsername($order['mid']), $order['order_sn']);
                    $this->template_msg->inQueue(3, 0, $msgParams);
                    // template_msg_action end
                }

            } elseif ($set['status_order'] == 3) {
                #退货成功，扣除积分和佣金

                $this->load->model('score');

                $this->score->actionByRuleId(5, $order['mid'], -$order['score_sended']);

                // 模版消息 4 会员申请退款 {插入昵称},{插入订单号}
                // template_msg_action start
                $this->load->model('template_msg');
                $msgParams = array(getUsername($order['mid']), $order['order_sn']);
                $this->template_msg->inQueue(4, 0, $msgParams);
                // template_msg_action end

            } elseif (($set['status_pay'] == 10 && $order['status_pay'] != 10 && $order['extension_code']!=CART_STEP ) || ($order['extension_code']==CART_STEP && $set['status_pay'] == 1 && $order['status_pay'] != 1)) {
                $this->order_common($order);

                // 微信模版消息 buy_succ 支付成功
                // wxtemplate_action start
                $this->load->model('wxtemplate');
                $msgParams = array(
                    "url" => note_url("/member/order/?order_sn=".$order['order_sn']),
                    "keyword1"=>$order['order_sn'],
                    "keyword2"=>$order['order_amount'],
                    "keyword3"=>date("Y-m-d H:i:s",RUN_TIME),
                );
                $this->wxtemplate->inQueue($order['mid'],'buy_succ',$msgParams);
                // wxtemplate_action end
            }
            //确认收货和退货成功  Feng 2016-05-26 end

        }

        return true;
    }

    /**
     * 支付成功团购处理
     * @param $order
     * @return bool
     */
    function order_common($order)
    {
        $lottery_status = 0;
        if ($order['common_id'] == 0 && $order['extension_code'] > CART_GOODS && $order['extension_code'] < CART_EXCHANGE) {//开团

            $team = $this->db->get("select g.team_num,g.id as goods_id,g.luck_num,g.end_time,g.typeid,g.sid,g.team_day,g.team_hour,g.team_limit,o.team_num as option_team_num,o.sell_price from ###_goods as g left join ###_goods_order_item as o on g.id=o.good_id where o.order_id={$order['id']}");
            //添加拼团
            $data = array();
            $data['mid'] = $order['mid'];
            $data['goods_id'] = $team['goods_id'];
            $data['goods_typeid'] = $team['typeid'];
            $data['team_num'] = $team['option_team_num']>0?$team['option_team_num']:$team['team_num'];
            $data['team_num_yes'] = 1;
            $data['team_price'] = $team['sell_price'];
            $data['c_time'] = RUN_TIME;
            $team_day = !empty($team['team_day'])?$team['team_day']:0;#成团时间 单位天
            $team_hour = !empty($team['team_hour'])?$team['team_hour']:0;#成团时间 单位小时
            $end_time = $team_day*TEAM_TIME+$team_hour;
            $data['e_time'] = RUN_TIME + $end_time * 3600;
            $data['sid'] = $team['sid'];
            $common_id = $this->db->insert("goods_order_common", $data);
            $order['common_id'] = $common_id;

            //免费试用和抽奖添加开奖信息
            if ($team['typeid'] == CART_FREE || $team['typeid'] == CART_LUCK) {
                $lottery_status = 1;
                $is_lottery = $this->db->get("select 1 from ###_lottery where act_id={$team['goods_id']}");
                if ($is_lottery == false) $this->db->insert("lottery", array("act_id" => $team['goods_id'], "typeid" => $team['typeid'], "luck_num" => $team['luck_num'], "e_time" => $team['end_time']));
            }

            //更新订单信息
            $this->db->update("goods_order", array("common_id" => $common_id, "status_common" => TEAM_ING, 'status_lottery' => $lottery_status), array("id" => $order['id']));

        } elseif ($order['common_id'] > 0 && $order['extension_code'] > CART_GOODS && $order['extension_code'] < CART_EXCHANGE) {//组团

            $order_common = $this->db->get("select * from ###_goods_order_common where id ={$order['common_id']}");

            //免费试用和抽奖添加开奖信息
            if ($order['extension_code'] == CART_FREE || $order['extension_code'] == CART_LUCK) $lottery_status = 1;
            //阶梯团修改价格
            if($order['extension_code'] == CART_STEP){
                $new_price = $this->common_price($order_common);
            }
            if ($order_common['team_num'] <= $order_common['team_num_yes'] + 1 ) {//超过团购人数则直接往上加
                if($order['extension_code'] == CART_STEP){
                    $this->load->model("goods");
                    $goods = $this->goods->get($order_common['goods_id']);
                    if($goods['team_limit'] && $goods['team_num_max']<=$order_common['team_num_yes'] + 1){
                        $this->db->update("goods_order_common", "team_num=team_num_yes+1,team_num_yes=team_num_yes+1,status=10,u_time=" . RUN_TIME, array("id" => $order['common_id']));
                        $this->db->update("goods_order", array("status_common" => TEAM_SUCC, 'status_lottery' => $lottery_status), "common_id={$order['common_id']} and status_pay>0");
                        //阶梯团修改最后价格
                        $order_common['team_price'] = $new_price;
                        $this->common_step($order_common);
                        //组团成功发送消息
                        $this->common_succ_step($order['common_id'],$order_common);
                    }else{
                        $this->db->update("goods_order_common", "team_num=team_num_yes+1,team_num_yes=team_num_yes+1", array("id" => $order['common_id']));
                        $this->db->update("goods_order", array("status_common" => TEAM_ING), "common_id={$order['common_id']} and status_pay>0");
                    }
                }else{
                    $this->db->update("goods_order_common", "team_num=team_num_yes+1,team_num_yes=team_num_yes+1,status=10,u_time=" . RUN_TIME, array("id" => $order['common_id']));
                    $this->db->update("goods_order", array("status_common" => TEAM_SUCC, 'status_lottery' => $lottery_status), "common_id={$order['common_id']} and status_pay>0");
                    //组团成功发送消息
                    $this->common_succ($order['common_id'],$order_common);
                }
            } else {

                $this->db->update("goods_order_common", "team_num_yes=team_num_yes+1", array("id" => $order['common_id']));
                $this->db->update("goods_order", array("status_common" => TEAM_ING, 'status_lottery' => $lottery_status), "common_id={$order['common_id']} and status_pay>0");
            }
        }
        set_new($order['mid']);//修改会员新老状态
        return $order['common_id'];
    }

    /**
     * 组团成功发送站内信
     * @param $common_id
     * @return bool
     */
    function common_succ($common_id,$order_common = array())
    {
        if($order_common['goods_typeid']==CART_FREE || $order_common['goods_typeid']==CART_LUCK){
            return false;
        }
        $this->load->model('member');
        if($order_common['type']==CART_SHARE || C('comss')){
            $member = $this->member->member_other($order_common['mid']);
            $goods = $this->db->get("select share_comss from ###_goods_additional where goods_id=".$order_common['goods_id']);
            $comm_scale = $this->db->getstr("select comm_scale from ###_goods where id=".$order_common['goods_id'],"comm_scale");
        }
        $res = $this->db->select("select id,mid,order_sn,goods_amount,sid from ###_goods_order where common_id={$common_id} and is_comm=0 and is_robots=0 and status_pay=10");
        $res = $this->db->lJoin($res, "goods_order_item", "order_id,goods_name,good_id", "id", "order_id");
        $data = array();
        $goods_name = $sid = '';
        foreach ($res as $k => $v) {
            $temp[0] = 30;
            $temp[1] = $v['mid'];
            $temp[2] = array($v['order_sn'], $v['goods_name']);
            $data[] = $temp;
            $goods_name = $v['goods_name'];
            $sid = $v['sid'];

            //微信消息模板
            $msgParams = array(
                "url" => note_url("/member/order/?order_sn=".$v['order_sn']),
                "keyword1"=>$v['order_sn'],
                "keyword2"=>$v['goods_name'],
            );
            $wxmsg[] = array(0=>$v['mid'],1=>"team_succ",2=>$msgParams);

            $temp = $this->member->member_info($v['mid']);

            /*if(C('comss') && isset($comm_scale) && $comm_scale>0 && $temp['ivt_id']>0){//分销佣金
                $ivt = $this->member->member_info($temp['ivt_id']);
                $ratio = $comm_scale;
                $commission = round($ratio * $v['goods_amount']* 0.01, 2);
                $desc = $ratio."%";

                if ($commission > 0) {
                    $insert_arr = array();
                    $insert_arr['mid'] = $ivt['mid'];
                    $insert_arr['username'] = $ivt['nickname'];
                    $insert_arr['ivt_mid'] = $v['mid'];
                    $insert_arr['ivt_username'] = $temp['nickname'];
                    $insert_arr['order_id'] = $v['id'];
                    $insert_arr['total'] = $v['goods_amount'];
                    $insert_arr['commission'] = $commission;
                    $insert_arr['desc'] =  "邀请会员[".$temp['nickname']."]下单(订单号".$v['order_sn']."、商品ID".$v['good_id']."),获得".$desc.L('unit_comm')."(".L('unit_distribution').L('unit_comm').")";
                    $insert_arr['level'] = 1;
                    $this->member->save_commission($insert_arr);
                }
            }
            if($order_common['goods_typeid']==CART_SHARE && isset($goods) && $goods['share_comss']>0){//推广团添加佣金
                if($v['mid']==$order_common['mid'])continue;
                $insert_arr = array();
                $insert_arr['mid'] = $order_common['mid'];
                $insert_arr['username'] = $member['nickname'];
                $insert_arr['ivt_mid'] = $v['mid'];
                $insert_arr['ivt_username'] = $temp['nickname'];
                $insert_arr['order_id'] = $v['id'];
                $insert_arr['total'] = $v['goods_amount'];
                $insert_arr['commission'] = $goods['share_comss'];
                $insert_arr['desc'] =  "会员[".$temp['nickname']."]参团(订单号".$v['order_sn']."、商品ID".$v['good_id']."),获得".$goods['share_comss']."元".L('unit_comm')."收益(推广团)";
                $this->member->save_commission($insert_arr);
            }*/

        }


        // 模版消息 30 拼团成功 {插入订单号},{插入商品标题}
        // template_msg_action start
        $this->load->model('template_msg');
        $this->template_msg->inQueueMany($data);
        // template_msg_action end

        // 微信模版消息 team_succ 拼团成功
        // wxtemplate_action start
        $this->load->model('wxtemplate');
        $this->wxtemplate->inQueueMany($wxmsg);
        // wxtemplate_action end


        //拼团成功通知商家
        if ($sid > 0) {
            $this->load->model('business');
            $buss = $this->business->get($sid, 'mobile');
            $sms = $buss['mobile'];
        } else {
            $sms = C('contact_mobile');
        }
        $receiver = array('sms' => $sms);
        $params = array($goods_name);
        send_template_msg(33, $receiver, $params);
    }
    /**
     * 组团成功(阶梯团)发送站内信
     * @param $common_id
     * @return bool
     */
    function common_succ_step($common_id,$order_common = array())
    {

        $res = $this->db->select("select id,mid,order_sn,goods_amount,sid from ###_goods_order where common_id={$common_id} and status_pay=1");
        $res = $this->db->lJoin($res, "goods_order_item", "order_id,goods_name,good_id", "id", "order_id");
        $data = array();
        foreach ($res as $k => $v) {
            $temp[0] = 34;
            $temp[1] = $v['mid'];
            $temp[2] = array($v['order_sn'], $v['goods_name']);
            $data[] = $temp;
        }

        // 模版消息 34 阶梯团拼团成功 {插入订单号},{插入商品标题}
        // template_msg_action start
        $this->load->model('template_msg');
        $this->template_msg->inQueueMany($data);
        // template_msg_action end

    }
    /**
     * 组团失败发送站内信,返还优惠券
     * @param $common_id
     * @return bool
     */
    function common_faild($common_id)
    {
        $res = $this->db->select("select id,mid,order_amount,coupon_id,coupon_id_sid,order_sn,extension_code,money_paid from ###_goods_order where common_id={$common_id} and status_pay>0 and is_robots=0");
        $res = $this->db->lJoin($res,"goods_order_item","order_id,goods_name","id","order_id");
        $data = array();
        foreach ($res as $k => $v) {

            if($v['extension_code']==CART_STEP){
                $order_amount = $v['money_paid'];
            }else{
                $order_amount = $v['order_amount'];
            }

            $temp[0] = 31;
            $temp[1] = $v['mid'];
            $temp[2] = array($order_amount);
            $data[] = $temp;

            //微信消息模板
            $msgParams = array(
                "url" => note_url("/member/order/?order_sn=".$v['order_sn']),
                "keyword1"=>$v['goods_name'],
                "keyword2"=>$v['order_amount'],
            );
            $wxmsg[] = array(0=>$v['mid'],1=>"team_faild",2=>$msgParams);

            //修改优惠券为可用
            if ($v['coupon_id'] > 0) {
                $this->db->update("coupon_log", array("status" => 1, "use_time" => 0, "order_id" => 0), array("id" => $v['coupon_id']));
            }
            if ($v['coupon_id_sid'] > 0) {
                $this->db->update("coupon_log", array("status" => 1, "use_time" => 0, "order_id" => 0), array("id" => $v['coupon_id_sid']));
            }
        }
        // 模版消息 31 拼团失败 {插入退款金额}
        // template_msg_action start
        $this->load->model('template_msg');
        $this->template_msg->inQueueMany($data);
        // template_msg_action end

        // 微信模版消息 team_faild 拼团失败
        // wxtemplate_action start
        $this->load->model('wxtemplate');
        $this->wxtemplate->inQueueMany($wxmsg);
        // wxtemplate_action end
    }
    /**
     * 阶梯团 调整价格
     * @param $order_common
     */
    function common_price($order_common){
        $this->load->model("goods");
        $goods = $this->goods->get($order_common['goods_id']);
        arsort($goods['step']['team_num']);
        foreach($goods['step']['team_num'] as $v){
            if(($order_common['team_num_yes']+1)>=$v){
                $num = $v;break;
            }
        }
        $price = $goods['step_array'][$num];
        if(empty($price))$price = reset($goods['step_array']);
        $this->db->update("goods_order_common",array("team_price"=>$price),array("id"=>$order_common['id']));
        return $price;
    }

    /**
     * 阶梯团组团成功 修改订单价格，尾款
     * @param $order_common
     * @param $goods
     */
    function common_step($order_common){
        $order_item = $this->db->select("select o.pre_amount,o.id,o.shipping_fee,i.buy_num,i.id as item_id from ###_goods_order as o left join ###_goods_order_item as i on o.id=i.order_id where o.common_id={$order_common['id']}");
        foreach($order_item as $k=>$v){
            $goods_amount = $order_common['team_price']*$v['buy_num'];
            $end_amount = $goods_amount-$v['pre_amount'];
            $this->db->update("goods_order",array("end_amount"=>$end_amount,"amount"=>$end_amount,"order_amount"=>$goods_amount+$v['shipping_fee'],"goods_amount"=>$goods_amount),array("id"=>$v['id']));
            $this->db->update("goods_order_item",array("sell_price"=>$order_common['team_price']),array("id"=>$v['item_id']));
        }
    }



    /**
     * 调整价格记录
     * @param $order_id
     * @param $price
     * @return bool
     */
    function chOrderPrice($order_id, $price)
    {
        $order = $this->db->get("SELECT id,amount FROM ###_goods_order WHERE id=" . $order_id);
        if (!isset($order['id'])) {
            return -1;
        }

        $order = $this->db->get("SELECT * FROM ###_goods_order WHERE id=" . $order_id);

        $price = floatval($price);
        if ($order['amount'] == $price) {
            return -2;
        }

        $input = array(
            'order_id' => $order_id,
            'state_info' => '价格从[' . $order['amount'] . ']改为[' . $price . ']',
            'c_time' => RUN_TIME,
            'userid' => defined('UID') ? UID : 0,
            'username' => defined('USER') ? USER : '',
        );

        $this->db->insert('goods_order_log', $input);

        $this->db->update('goods_order', array('amount' => $price), array('id' => $order_id));
        return true;
    }

    /**
     * 取消订单,对优惠券,积分的处理.
     */
    function cancelOrder($order_id)
    {
        return;
    }

    /** 订单付款后,对余额，红包，积分扣除赠送、销量增减的处理.
     * @param  array $order 订单详细信息
     */
    function moneyOrder(array $order)
    {

        $order_id = $order['id'];

        $this->load->model('member');

        $member = $this->member->member_info($order['mid']);

        if ($order['coupon_id']) {
            // 本步骤落实优惠券的使用状态 让优惠不可再用
            // 对于下单但是未支付的订单 因为并没有执行本步骤 所以继续下其他订单的时候 优惠券依然可以被选择
            // 但是继续未完成的支付的时候 需要判断优惠券的可用状态 做必要拦截 避免重复使用
            $this->load->model('coupon');

            $this->coupon->used($order['coupon_id'], $order['id']);
        }
        //店铺优惠券
        if ($order['coupon_id_sid']) {
            // 本步骤落实优惠券的使用状态 让优惠不可再用
            // 对于下单但是未支付的订单 因为并没有执行本步骤 所以继续下其他订单的时候 优惠券依然可以被选择
            // 但是继续未完成的支付的时候 需要判断优惠券的可用状态 做必要拦截 避免重复使用
            $this->load->model('coupon');

            $this->coupon->used($order['coupon_id_sid'], $order['id']);
        }

        if ($order['surplus'] > 0) {
            //记录账户明细
            $log_arr = array();

            $log_arr['mid'] = $order['mid'];

            $log_arr['username'] = $member['username'];

            $log_arr['user_money'] = -$order['surplus']; //余额
            $log_arr['desc'] = '订单支付 ' . $order['order_sn'];

            // if ($bonus['money']) {
            // 	$log_arr['desc'] .= "(使用红包{$bonus['money']}元)";
            // }

            // $log_arr['rank_points'] = $order['order_amount']; #加经验值
            $this->member->accountlog(ACT_ORDER, $log_arr);
        }
		

        //增加销量
        $this->orderGoodsSell($order, '+');

        /*if (C('comm_time') == 1 && $order['is_comm'] == 0) {
            #确认收货或者已付款，发放积分和佣金
            // 按照新积分规则增加积分
            // 购物送积分是积分规则5
            if ($order['order_amount']) {
                $this->load->model('score');
                $score = $this->score->actionByRuleId(5, $order['mid'], $order['order_amount']);
                // 在订单内记录 赠送的积分 用来在退货发生时减扣;
                if (!empty($score['amount'])) {
                    $this->db->update('goods_order', 'score_sended=' . $score['amount'], 'id=' . $order['id']);
                }
            }
            if (C('comss')) {
                $this->load->library("agent");

                $this->agent->comms($order);
            }
            //更改订单佣金是否发放状态 2016-07-04
            $this->db->update("goods_order", array("is_comm" => 1), array("id" => $order['id']));
        }*/

        #直购分销佣金 阶梯团付尾款
        $goods = $this->db->get("select comm_scale from ###_goods where id=".$order['extension_id']);
        if(C('comss') && $member['ivt_id']>0 && !empty($goods['comm_scale']) && ($order['common_id']==0 || $order['common_id']>0 && $order['extension_code']==CART_STEP) ){//分销佣金
            $ivt = $this->member->member_info($member['ivt_id']);
            $ratio = $goods['comm_scale'];
            $commission = round($ratio * $order['goods_amount']* 0.01, 2);
            $desc = $ratio."%";
            if ($commission > 0) {

                $insert_arr = array();
                $insert_arr['mid'] = $ivt['mid'];
                $insert_arr['username'] = $ivt['nickname'];
                $insert_arr['ivt_mid'] = $member['mid'];
                $insert_arr['ivt_username'] = $member['nickname'];
                $insert_arr['order_id'] = $order['id'];
                $insert_arr['total'] = $order['goods_amount'];
                $insert_arr['commission'] = $commission;
                $insert_arr['desc'] =  "邀请会员[".$member['nickname']."]下单(订单号".$order['order_sn']."、商品ID".$order['extension_id']."),获得".$desc.L('unit_comm')."(".L('unit_distribution').L('unit_comm').")";
                $insert_arr['level'] = 1;
                //$this->member->save_commission($insert_arr);
            }
        }

        // 模版消息 2 订单已付款 {插入昵称},{插入订单号}
        // template_msg_action start
        $this->load->model('template_msg');
        $msgParams = array($member['username'], $order['order_sn']);
        $this->template_msg->inQueue(2, 0, $msgParams);
        // template_msg_action end

        if (defined('ORDER_COUNT')) {//添加订单统计
            $this->load->model("setting");
            $this->setting->logCount();
        }
    }

    //得到分销比例
    function comss_po()
    {
        $c = ($this->mod == 'manage') ? $this->common : $this->site_config;

        $comss_po = explode(',', trim($c['comss_po']));
        for ($i = 0; $i < COMSS_LEVEL; $i++) {
            if (!$comss_po[$i]) {
                $comss_po[$i] = 0;
            } else {
                $comss_po[$i] = intval($comss_po[$i]);
            }
        }
        return $comss_po;
    }

    /** 获取会员所有推荐人信息
     * @param $mid
     * @param int $type 1获取订单额
     * @return array
     */
    function comss_levels($mid, $type = 0)
    {
        static $levels = array();

        $this->load->model('member');

        $member = $this->member->member_info($mid, 'mid,username,ivt_id');
        if ($member) {
            $arr = array(
                'mid' => $mid,
                'username' => $member['username'],
            );

            //推荐人订单消费金额
            if ($type == 1) {
                $arr['order_amount'] = $this->midOrderMoney($mid);
            }

            $levels[] = $arr;
        }

        //存在父级，递归输出
        if ($member['ivt_id']) {
            if (count($levels) <= COMSS_LEVEL) {
                $this->comss_levels($member['ivt_id'], $type);
            }
        }

        unset($levels[0]);

        $array = array();
        foreach ($levels as $v) {
            $array[] = $v;
        }

        return $array;
    }

    /** 用户消费金额
     * @param $mid
     * @return mixed
     */
    function midOrderMoney($mid)
    {
        $sql = "SELECT SUM(order_amount) FROM ###_goods_order WHERE mid='" . $mid . "' AND status_pay=10 AND status_order IN(0,10)";

        $order_amount = $this->db->getstr($sql);
        return $order_amount;
    }

    /** 增减商品销量    note:只加销量不减库存，库存再下单时减
     * @param $order
     * @param string $type
     */
    function orderGoodsSell($order, $type = '+')
    {
        //更新商品库存与销量
        $sql = "SELECT g.stock,g.sell,goi.good_id,goi.spec,goi.buy_num FROM ###_goods_order_item AS goi " .
            "LEFT JOIN ###_goods AS g ON g.id=goi.good_id " .
            "WHERE goi.order_id='" . $order['id'] . "'";

        $items = $this->db->select($sql);
        foreach ($items as $v) {
            $set = '';
            if ($type == '-') {
                $set = 'sell=sell-' . $v['buy_num'];
                /*if ($v['sell'] >= $v['buy_num']) {
                    $set .= ',sell=sell-' . $v['buy_num'];
                }*/
            } else {
                $set = 'sell=sell+' . $v['buy_num'];
                /*if ($v['stock'] >= $v['buy_num']) {
                    $set .= ',stock=stock-' . $v['buy_num'];
                }*/
            }
            if (!empty($set)) {
                $this->db->update('goods', $set, array('id' => $v['good_id']));
                /*$str = 'stock=stock-' . $v['buy_num'];
                if ($v['spec']) $this->db->update("goods_item", $str, array('goods_id' => $v['good_id'], "spec" => $v['spec']));*/
            }

        }
        //更新促销商品库存与销量
        /*if (in_array($order['extension_code'], array( CART_TUAN, CART_PACK))) {
            $sql = "SELECT g.act_stock,g.act_sell,g.act_id,goi.buy_num FROM ###_goods_order_item AS goi " .
                "LEFT JOIN ###_goods_activity AS g ON g.act_id=goi.extension_id " .
                 "WHERE goi.order_id='" . $order['id'] . "'";
             $items = $this->db->select($sql);
             foreach ($items as $v) {
                 if (in_array($order['extension_code'], array(CART_PACK))) {
                     $v['buy_num'] = 1;
                 }
                 $set = '';
                 if ($type == '-') {
                     $set = 'act_stock=act_stock+' . $v['buy_num'];
                     if ($v['act_sell'] >= $v['buy_num']) {
                         $set .= ',act_sell=act_sell-' . $v['buy_num'];
                     }
                 } else {
                     $set = 'act_sell=act_sell+' . $v['buy_num'];
                     if ($v['act_stock'] >= $v['buy_num']) {
                         $set .= ',act_stock=act_stock-' . $v['buy_num'];
                     }
                 }
                 if (!empty($set)) {
                     $this->db->update('goods_activity', $set, array('act_id' => $v['act_id']));
                 }
             }
        }*/
    }

    /** 综合状态相关
     * @param $status
     * @param $order
     * @param $type '0判断综合状态 1根据综合状态获取SQL' 2根据订单信息获取当前综合状态
     * @param $pre '订单表别名'
     * @return int
     */
    function order_status($status, $order, $type = 0, $pre = '')
    {
        if ($type == 2) {
            $status_array = $this->status;
//			if ($order['is_cod'] == 1) {
//				$status_array = $this->status_cod;
//			}
            $status = 0;
            foreach ($status_array as $k => $v) {
                $status_bool = true;
                if ((isset($v['where']['status_pay']) && !in_array($order['status_pay'], $v['where']['status_pay']))) {
                    $status_bool = false;

                    continue;
                }
                if ((isset($v['where']['status_shipping']) && !in_array($order['status_shipping'], $v['where']['status_shipping']))) {
                    $status_bool = false;

                    continue;
                }
                if ((isset($v['where']['status_order']) && !in_array($order['status_order'], $v['where']['status_order']))) {
                    $status_bool = false;

                    continue;
                }
                if ($status_bool) {
                    $status = $k;

                    break;
                }
            }
            return $status;
        } elseif ($type == 1) {
            $sql = '';

            $status_row = $this->status[$status];
            if (isset($status_row['where']) && is_array($status_row['where'])) {
                foreach ($status_row['where'] as $k => $v) {
                    if ($sql) {
                        $sql .= " AND ";
                    }
                    $sql .= $pre . $k . " IN(" . implode(',', $v) . ")";
                }
            }
            return $sql;
        } else {
            $status_bool = true; //返回order是否为status状态
            $status_row = $this->status[$status];
            if ((isset($status_row['where']['status_pay']) && !in_array($order['status_pay'], $status_row['where']['status_pay']))) {
                $status_bool = false;
            } elseif (isset($status_row['where']['status_shipping']) && !in_array($order['status_shipping'], $status_row['where']['status_shipping'])) {
                $status_bool = false;
            } elseif (isset($status_row['where']['status_order']) && !in_array($order['status_order'], $status_row['where']['status_order'])) {
                $status_bool = false;
            }
            return $status_bool;
        }
    }

    /** 获取各订单状态名称
     * @param $order
     * @param $type 1：显示团购状态，2：显示订单状态
     * @return string
     */
    function order_status_name($order, $type = 1)
    {
        $status_name = '';

        $status_pay = $order['status_pay'];

        $status_shipping = $order['status_shipping'];

        $status_order = $order['status_order'];

        $status_common = $order['status_common'];

        $status_lottery = $order['status_lottery'];

        $status_pay_name = $this->payStatus[$status_pay];

        $status_shipping_name = $this->shippingStatus[$status_shipping];

        $status_order_name = $this->orderStatus[$status_order];

        $status_common_name = $this->orderCommon[$status_common];

        $status_lottery_name = $this->orderLottery[$status_lottery];

        if ($status_order > 0) {//其它订单状态

            return $status_order_name;

        } elseif ($status_shipping > 0) {//发货状态

            return $status_shipping_name;

        } elseif ($status_common > 0 && $type == 1) {//拼团中

            if ($status_common == 10 && $status_lottery > 0) {
                return $status_lottery_name;
            } else {
                return $status_common_name;
            }
        }
        if ($order['is_cod'] == 1) {
            return $status_shipping_name . ',' . $status_pay_name;
        } else {
            return $status_pay_name . ',' . $status_shipping_name;
        }
    }

    /** 根据订单号判断选菜功能
     * @param $order_sn
     */
    function pack_select($order_sn)
    {
        $data = array('error' => 1, 'msg' => '未知错误', 'url' => '', 'order_id' => 0, 'max_qishu' => 0, 'goods_list' => array());

        //判断是否登陆
        if (!$_SESSION['mid']) {
            $data['msg'] = '请先登陆！';

            $data['url'] = '/member';
            return $data;
        }

        $sql = "SELECT * FROM `###_goods_order` WHERE mid='" . $_SESSION['mid'] . "' AND extension_code='" . CART_PACK . "' AND order_sn=" . $order_sn;

        $order = $this->db->get($sql);
        if (!isset($order['id'])) {
            $data['msg'] = '套餐订单不存在！';
            return $data;
        }

        #判断订单状态
        if (!in_array($order['status_pay'], array(10))) {
            $data['msg'] = '该订单未付款，不可选菜！';

            $data['un_pay'] = 1;
            return $data;
        }
        if (!in_array($order['status_order'], array(0, 10))) {
            if ($order['status_order'] == 1) {
                $data['msg'] = '该订单已取消，不可选菜！';
            } else {
                $data['msg'] = '该订单状态错误，不可选菜！';
            }
            return $data;
        }

        #判断套餐是否到期
        if ($order['end_time'] < time()) {
            $data['msg'] = '该套餐有效已过，不可选菜';
            return $data;
        }

        #判断订单商品是否购买完成
        $sql = "SELECT SUM(buy_num) AS buy_num_total,good_id,goods_name FROM ###_goods_order_item_pack WHERE order_id=" . $order['id'] . " GROUP BY good_id";

        $goods_list_order = $this->db->select($sql);

        #获取最后一次选菜期数
        $sql = "SELECT MAX(qishu) FROM ###_goods_order_item_pack WHERE order_id=" . $order['id'];

        $data['max_qishu'] = $this->db->getstr($sql);

        #配送次数（合并相同的配送单号）
        $sql = "SELECT * FROM ###_goods_order_item_pack WHERE order_id=" . $order['id'] . " GROUP BY qishu";

        $list = $this->db->select($sql);

        $data['count_qishu'] = 0;

        $express_nums = array();
        foreach ($list as $v) {
            if (in_array($v['express_num'], $express_nums) || empty($v['express_num'])) {
                continue;
            }
            $express_nums[] = $v['express_num'];

            $data['count_qishu']++;
        }

        $this->load->model('auction');

        $goods_list = $this->auction->getPackGoods($order['goods_list']);
        //剩余总数
        $pack_number_have = 0;
        foreach ($goods_list as $k => $v) {
            $v['cat_number_order'] = 0;
            foreach ($goods_list_order as $w) {
                foreach ($v['list'] as $x) {
                    if ($w['good_id'] == $x['id']) {
                        #已购买数量
                        $v['cat_number_order'] += $w['buy_num_total'] * (isset($x['pack_num']) && floatval($x['pack_num']) ? floatval($x['pack_num']) : 1);
                    }
                }
            }
            #剩余数量
            $v['cat_number_have'] = $v['cat_number'] - $v['cat_number_order'];

            $pack_number_have += $v['cat_number_have'];

            $goods_list[$k] = $v;
        }
        if ($pack_number_have <= 0) {
            $data['msg'] = '该套餐您已购买完成，没有剩余数量可选了！';
            return $data;
        }

        $data['order_id'] = $order['id'];

        $data['error'] = 0;

        $data['goods_list'] = $goods_list;
        return $data;
    }

    /**
     * 手动创建订单
     * @param  integer $mid 用户id
     * @param  array $goodList 商品列表 二维数组  需要字段 array('good_id', 'goods_name', 'buy_num', 'sell_price');
     * @param  array $orderOption 订单附加配置信息
     * @return mixed
     */
    public function createOrder($mid, array $goodList = array(), array $orderOption = array())
    {

        // 生成订单
        $order = array(
            'order_sn' => $this->get_order_sn(),
            'mid' => $mid,
            'c_time' => RUN_TIME,
            //'extension_code' => $cart_type, // 促销活动类型
        );

        $order = array_merge($order, $orderOption);

        //保存订单表
        $order_id = $this->db->insert('goods_order', $order);

        $this->db->insertAll('goods_order_item', $goodList, array('order_id' => $order_id, 'c_time' => RUN_TIME, 'mid' => $mid));

        return true;
    }

    /**
     * goods_order 关联 goods_order_item 查询
     * @param int $id 订单id
     * @param string $select 查询字段
     * @param string $mid 会员id
     * @return array|null
     */
    public function getGoodsOrderDetailByOrderId($id, $select = '*', $mid = ''){
        if(!empty($mid)){
            $sql = "select $select from ###_goods_order where id = $id and mid = $mid";
        }else{
            $sql = "select $select from ###_goods_order where id = $id";
        }
        $res = $this->db->select($sql);
        $res = $this->db->lJoin($res, "goods_order_item", "order_id,goods_name,buy_num", "id", "order_id");
        return $res;
    }

    /**
     * goods_order 关联 goods_order_item、goods_order_common、goods 查询
     * @param int $id 订单id
     * @param string $select 查询字段
     * @param string $mid 会员id
     * @return mixed
     */
    public function getOrderItemCommonGoods($id, $select = '*', $mid = ''){
        if(!empty($mid)){
            $sql = "select $select from ###_goods_order where id = $id and mid = $mid";
        }else{
            $sql = "select $select from ###_goods_order where id = $id";
        }
        $items = $this->db->select($sql);
        $items = $this->db->lJoin($items, "goods_order_item", "order_id,goods_name", "id", "order_id", 'goi_');
        $items = $this->db->lJoin($items, "goods_order_common", "id,goods_id,team_num,team_num_yes", "common_id", 'id', 'goc_');
        $items = $this->db->lJoin($items, 'goods', 'id,thumb,thumbs,team_num,price,team_price,market_price,discount_type,discount_amount,sid', 'goc_goods_id', 'id', 'goods_');
        $this->load->model('goods');
        foreach($items as $k=>$v){
            $val['thumb'] = $v['goods_thumb'];
            $val['thumbs'] = $v['goods_thumbs'];
            $res = $this->goods->getThumb($val, 1);
            $items[$k]['img_cover'] = $res['img_cover'];
        }
        return $items[0];
    }

    /**
     * @param $id
     * @return array|null
     */
    public function getOrderById($id){
        $sql = "select * from ###_goods_order where id = $id";
        return $this->db->get($sql);
    }

    /**
     * @param $id
     * @return array|null
     */
    public function getCommonById($id){
        $sql = "select * from ###_goods_order_common where id = $id";
        return $this->db->get($sql);
    }
}