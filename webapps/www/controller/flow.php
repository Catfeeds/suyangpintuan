<?php

/**
 * 下单流程  add_cart() =>team() =>done() => pay(); 
 */
class flow extends Lowxp {

    function __construct() {
        parent::__construct();

        $method = $_SERVER['request']['method'];

        $isLogin = isset($_SESSION['mid']);

        //不能登录状态的方法.
        $notLoginActions = in_array($method, array('add_cart', 'del_cart', 'buy', 'buy_pack', 'is_login'));

        if (!$isLogin && !$notLoginActions) {
            login();
        }

        if (!$isLogin) {
            define('MID', 0);
        }

        //获取购物车内容  Feng 2016-05-30 start
        //$this->load->model("flow");

        //$this->cart_goods = $this->flow->cart_goods();
        //获取购物车内容  Feng 2016-05-30 end

        $this->display_before(array('title' => '结算'));
    }

    /**
     * ajax判断是否登录
     */
    function is_login() {
        $data = array('error' => 0, 'msg' => '');

        if (!$_SESSION['mid']) {
            $data['error'] = 1;
            exit(json_encode($data));
        }
    }
    
    /** 
     * 加入购物车
     */
    function add_cart($ajax_type = '') {

        //判断是否关注
        if(defined('MID') && C('wxpay_gz') && IS_WECHAT){
            $subscribe = $this->db->getstr("select subscribe_time from ###_member where mid=".MID,"subscribe_time");
            if($subscribe==0){
                exit(json_encode(array('error' => 'subscribe')));
            }
        }

        $id = intval($_POST['id']);

        //判断是否在为开始的秒杀中
        if($this->goods->is_wkill($id)){
            die(json_encode(array('code'=>'error','msg' => '活动未开始')));
        }

        $num = isset($_POST['num']) ? intval($_POST['num']) : 1;//数量

        $type = isset($_POST['type']) ? trim($_POST['type']) : CART_TUAN;

        $spec = isset($_POST['spec']) ? trim($_POST['spec']) : '';//规格参数

        $option = isset($_POST['option']) ? trim($_POST['option']) : '';//自选团参数

        $common_id = isset($_POST['common_id']) ? intval($_POST['common_id']) : '0';//规格参数
        
        $this->load->model('goods');

        $row = $this->goods->get($id, array("spec" => $spec));
        if (empty($row)) {
            die(json_encode(array('code'=>'error','msg' => '商品信息错误')));
        }           
        if($row['stock']<=0){
            die(json_encode(array('code'=>'error','msg' => '没有库存了')));
        }

        if($row['limit_buy_bumber']>0){
            $max = $row['limit_buy_bumber'];
        }else{
            $max = $row['stock'];
        }
        $num = $num>$max?$max:$num;
        if($num<=0){
            die(json_encode(array('code'=>'error','msg' => '商品数量不能为0')));
        }
        $row['team_num'] = '';
        if($common_id>0){
            $common_info = $this->db->get("select team_num,team_price,goods_typeid from ###_goods_order_common where id=".$common_id);
            //判断后台是否修改商品类型
            if($type!=$common_info['goods_typeid']){
                die(json_encode(array('code'=>'error','msg' => '该活动已经结束')));
            }
            $row['team_num'] = $common_info['team_num'];
            $price = !in_array($type,array(7,9,10))?$row['team_price']:$common_info['team_price'];
            $market_price = $row['price'];
        }elseif($type!=CART_GOODS && $type!=CART_EXCHANGE){
            if($type==CART_OPTION && strpos($option,"-")){#自选开团
                $option_array = explode("-",$option);
                $row['team_num'] = $option_array[0];
                $price = $option_array[1];
            }elseif($type==CART_STEP){#阶梯团
                $row['team_num'] = end($row['step']['team_num']);
                $price =  $row['team_price'];
            }else{
                $row['team_num'] = $row['team_num'];
                $price = $row['team_price'];
            }
            $market_price = $row['price'];
        }else{
            //单独购用拼团价格购买
            if($row['typeid']==CART_GOODS){
                $price = $row['team_price'];
                $market_price = $row['price'];
            }else{
                $price = $row['price'];
                $market_price = $row['market_price'];
            }

        }
        $cart_new = array(
            'goods_id' => $row['id'],
            'goods_name' => $row['name'],
            'cost_price' => $row['cost_price'],
            'market_price' => $market_price,
            'goods_price' => $price,
            'spec' => $spec,
            'qty' => $num,
            'subtotal' => formatPrice($price * $num),
            'type' => $type,//拼团类型
            'common_id' => $common_id,//拼团id
            'team_num' => $row['team_num'],//组团人数
        );
		/*if(!empty($_SESSION['cart'])){
        	$cart_old = $_SESSION['cart'];
        	if($cart_old['goods_id']==$cart_new['goods_id'] && $cart_old['type']==$cart_new['type']){
        		if($cart_new['spec']==''){
					$cart_new = $cart_old;
					$cart_new['qty'] = $num;
					$cart_new['subtotal'] = formatPrice($cart_new['goods_price'] * $num);
        			
        		}
        	}
        }*/
        $_SESSION['cart'] = $cart_new;

        $result = array('error' => 'success');
        if ($ajax_type == '') {
            exit(json_encode($result));
        }
    }
    
    /** 
     * 团购
     */
    public function team(){
        $this->load->model("member");
        $data['member'] = $this->member->member_info(MID);
        $data['cart'] = $_SESSION['cart'];
        $data['goods'] = $this->goods->get($data['cart']['goods_id'],array("spec"=>$data['cart']['spec']));
        #echo "<pre>";print_r($data['cart']);exit;
        //非阶梯团 支付全额
        #if($data['goods']['typeid']!=CART_STEP)$data['goods']['deposit'] = 100;

        if(!check_team(MID,$data['goods']['typeid']) && $data['cart']['common_id']>0 && $data['goods']['typeid']>0){
            $this->error("该团仅限绑定手机的新用户能参团","/member/info?type=mobile");
        }

        //海淘产品需要实名认证才能购买
        if(C('nation_realname')==1 && $data['goods']['nation_id']>0){
            $res_card = $this->db->getstr("select realname from ###_member_detail where mid=".MID,"realname");
            if(empty($res_card)){
                $this->error("全球购产品需要实名认证","/member/verifyidcard");
            }
        }

        //收货地址
        $this->load->model('take_address');
        if($data['cart']['type']==CART_AA && $data['cart']['common_id']>0){//AA团直接调用团长的地址
            $goods_order = $this->db->get("select address_id,take_address_id from ###_goods_order where common_id={$data['cart']['common_id']}");
            if($goods_order['address_id'] > 0 && $goods_order['take_address_id'] == 0){
                $data['address'] = $this->member->get_address("id=".$goods_order['address_id']);
            }elseif($goods_order['address_id'] == 0 && $goods_order['take_address_id'] > 0){
                $data['address'] = $this->take_address->getById($goods_order['take_address_id']);
                $data['address']['take_address_id'] = $data['address']['id'];
            }
        }else{
            $addresses = $this->member->member_address(MID, 1);
            $data['address_list'] = empty($addresses) ? array() : $addresses;
            if ($addresses) {               
                $data['address'] = array_slice($addresses, 0, 1);
                $data['address'] = $data['address'][0];
            } else {
                $data['address'] = array();
            }
            //自提点
            if($data['goods']['take_address']) {
                $data['take_address'] = $this->take_address->selectBySid($data['goods']['sid']," and id in ({$data['goods']['take_address']})");
            }
        }
        if($data['goods']['is_virtual']==1){
        	$data['mobile'] = $data['address']['mobile']?$data['address']['mobile']:$data['member']['mobile'];
        }
        #echo "<pre>";print_r($data);exit;
        //支付方式
        $this->load->model('payment');
        $data['payment'] = $this->payment->getPayment("enabled = '1' AND pay_code != 'alipayapp' AND pay_code != 'wxpayapp' AND pay_code != 'alipay' AND pay_code != 'wxpayxiao'");

        //优惠券
        $this->load->model('coupon');
        $data['coupon_list'] = $this->coupon->getCouponList(array($data['cart']), true);
        //快递公司
        if($data['goods']['express']){
            $data['express'] = $this->db->select("select * from ###_express where status = 1 AND sid = {$data['goods']['sid']} AND id in({$data['goods']['express']}) order by listorder asc,id asc");
        }

		// 积分
        $this->load->model('score');
        $score = $this->score->getTotal(MID);
        $this->smarty->assign('score', $score);
        $rule = $this->score->getRow(6);
        $this->smarty->assign('rule',$rule);
        $this->smarty->assign('data',$data);
        $this->smarty->assign('token',createToken());
        $this->smarty->display('flow/team.html');        
    }

    /**
     * 计算运费
     * @param $express_id int 快递方式id
     * @param $address_id int 收货地址id
     */
    function ajaxCalculateShippingCosts($express_id, $address_id){
        $express_id = intval($express_id);
        if(!$express_id){
            $data['error'] = 0;
            $data['msg'] = '请选择运送方式';
            echo json_encode($data);exit;
        }
        $address_id = intval($address_id);
        if(!$address_id){
            $data['error'] = 0;
            $data['msg'] = '请选择收货地址';
            echo json_encode($data);exit;
        }
        $this->load->model('flow');
        $data = $this->flow->calculateShippingCosts($express_id, $address_id);
        echo json_encode($data);exit;
    }

    /** 
     * 提交页面
     */
    function done() {
        if (!checkToken()) {
            $res = array(
                'data' => array(
                    'token' => createToken(),
                ),
            );            
            //$this->error('页面已过期！,请刷新', '', $res);
        }
        
        $res = array(
            'data' => array(
                'token' => createToken(),
            ),
        );
        if(defined('RUNSTOP')){
            $this->error('使用期限已到期，请联系官方客服！', '', $res);
        }
        //检查商品是否满足条件,是否为团购，人数是否已满
        $this->load->model('goods');
        $cart = $this->goods->check_goods($_SESSION['cart'],$_POST);
        if (!$cart) {
            $msg = $this->goods->getError();
            $this->error($msg, '', $res);
        }
        $is_virtual = $this->goods->selectById($_SESSION['cart']['goods_id'],'is_virtual');
        if($is_virtual[0]['is_virtual']==1){
        	$mobile = $_POST['mobile'];
        	//如果会员中心没有手机号码，就需要填入会员中心
        	if(!$mobile){
        		$this->error('请输入手机号码','',$res);
        	}else{
        		$this->load->model("member");
        		$member = $this->member->member_info(MID);
        		if(!$member['mobile']){
        			$this->db->update('member',array('mobile'=>$mobile),array('mid'=>MID));
        		}
        	}
        }else{
	        #判断是否自提，AA团直接试用团长的地址
	        if($_POST['express_type']==2){
	            $address_id = 0;
	            $take_address_id = intval($_POST['take_address_id']);
	            if (!$take_address_id) {
	                $this->error('请选择自提点！', '', $res);
	            }
	            $this->load->model('take_address');
	            if($cart['type']==CART_AA){//AA团直接试用团长的地址
	                $address_info = $this->take_address->getById($take_address_id);
	            }else{
	                $address_info = $this->take_address->get($take_address_id, $cart['sid']);
	            }
	        }else{
	            $take_address_id = 0;
	            $address_id = isset($_POST['address_id']) ? intval($_POST['address_id']) : 0;
	            if (!$address_id) {
	                $this->error('请选择收货地址！', '', $res);
	            }
	            $this->load->model('member');
	            if($cart['type']==CART_AA){//AA团直接试用团长的地址
	                $address_info = $this->member->get_address("id=".$address_id);
	            }else{
	                $address_info = $this->member->get_address("mid='" . MID . "' AND id='" . $address_id . "'");
	            }
	        }
	        if (!$address_info) {
	            $this->error('请添加一个收货地址！', '', $res);
	        }
        }
        
        $this->load->model('order');
        $order = array(
            'mid' => MID,
            'c_time' => RUN_TIME,
        );

        //购物车
        $cart_goods = array();
        $order_info = array();

        $cart_goods = array($cart);
        $order['extension_code'] = $cart['type'];
        $order['extension_id'] = $cart['goods_id'];
        $order['send_couponid'] = $cart['coupon_id'];
        if (empty($cart_goods)) {
            $this->error('您的选择的商品有误！', '/goods/index', $res);
        }
		
		if($_POST['exchange_pay']){
        	$order['extension_code'] = CART_EXCHANGE;//积分兑换
        }

        //支付方式与结算
        $pay_id = isset($_POST['pay_id']) ? intval($_POST['pay_id']) : 0;

        //收货地址
        $order['zone'] = $address_info['zone'];
        $order['area'] = $address_info['area'];
        $order['address'] = $address_info['address'];
        $order['mobile'] = $is_virtual[0]['is_virtual']==1?$mobile:$address_info['mobile'];
        $order['name'] = $address_info['name'];
        $order['address_id'] = $address_id;
        $order['take_address_id'] = $take_address_id;
        // 优惠券id
        $order['coupon_id'] = isset($_POST['coupon_id']) ? intval($_POST['coupon_id']) : 0;
    	//这里需要判断抵用券是否是属于该用户，以及商家抵用券是否属于该商家
        $this->load->model('coupon');
        if($order['coupon_id']>0){
        	$coupon_arr = $this->coupon->checkCoupon($order['coupon_id'],MID,0);
        	if($coupon_arr['error']==0){
        		$order['coupon_id_num'] = $coupon_arr['amount'];
        	}else{
        		$this->errror($coupon_arr['msg'],'',$res);
        	}
        }
        $order['coupon_id_sid'] = isset($_POST['coupon_id_sid']) ? intval($_POST['coupon_id_sid']) : 0;
        if($order['coupon_id_sid']>0){
        	$coupon_sid_arr = $this->coupon->checkCoupon($order['coupon_id_sid'],MID,$cart['sid']);
        	if($coupon_sid_arr['error'] == 0){
        		$order['coupon_id_sid_num'] = $coupon_sid_arr['amount'];
        	}else{
        		$this->error($coupon_arr['msg'],'',$res);
        	}
        }

        $total = $this->flow->total($_POST, $cart_goods);
        
        if(!$total){
            $msg = $this->flow->getError();
            $this->error($msg, '', $res);
        }        
        
        if ($total['flowTotal'] > 0 && !$pay_id) {
            $this->error('请选择支付方式！', '', $res);
        }

        // 订单总价
        $order['order_amount'] = isset($total['flowOrderTotal']) ? $total['flowOrderTotal'] : 0;
        // 商品总价
        $order['goods_amount'] = isset($total['flowGoodsTotal']) ? $total['flowGoodsTotal'] : 0;
        // 商品成本价 Feng 2016-06-02
        $order['cost_amount'] = isset($total['flowCostTotal']) ? $total['flowCostTotal'] : 0;
        // 运费
        $order['shipping_fee'] = isset($total['shipping_fee']) ? $total['shipping_fee'] : 0;
        // 未支付金额 扣除
        $order['amount'] = isset($total['flowTotal']) ? $total['flowTotal'] : 0;
        // 余额
        $order['surplus'] = isset($total['flow_user_money_fee']) ? $total['flow_user_money_fee'] : 0;
        // 货到付款
        $order['is_cod'] = isset($total['is_cod']) ? intval($total['is_cod']) : 0;
		//积分支付
		$order['score'] = isset($total['flow_user_score_fee']) ? $total['flow_user_score_fee'] : 0;
        
        //阶梯团定金
        if($cart['type']==CART_STEP){
            $order['pre_amount'] = $cart['deposit']*$cart['qty'];
            $order['amount'] = $total['flowTotal'] = $order['pre_amount']+$order['shipping_fee'];
        }

        //获取支付方式
        if ($pay_id) {
            $this->load->model('payment');
            $payment = $this->payment->payment_info($pay_id);
            $order['pay_id'] = $pay_id;
            $order['pay_name'] = addslashes($payment['pay_name']);
        }

        //应付金额为0时，更新付款状态
        if ($total['flowTotal'] <= 0) {
            if($cart['type']==CART_STEP){
                $order['status_pay'] = 1;
            }else{
                $order['status_pay'] = 10;
            }
        }

        //配送时间 当前分销项目暂无 不过可以让用户手动填写 或者选择. 如果增加的话 建议做成时间范围选择
        if (isset($_POST['best_time'])) {
            $order['best_time'] = isset($_POST['best_time']) ? stripcslashes($_POST['best_time']) : '';
        }

        //其它信息 checkout 页面 用户填写的 给卖家留言
        if (isset($_POST['order_tip'])) {
            $order['order_tip'] = isset($_POST['order_tip']) ? stripcslashes($_POST['order_tip']) : '';
        }

        //订单号
        $order['order_sn'] = $order_sn = $this->flow->order_sn();

        //团购id
        $order['common_id'] = $cart['common_id'];
        //商家id
        $order['sid'] = $cart['sid'];

        //减库存
        if($cart['spec']){
            $is_stock = $this->db->query("update ###_goods_item set stock=stock-{$cart['qty']} where goods_id={$cart['id']} and stock>={$cart['qty']} and spec='{$cart['spec']}'");
            if($is_stock==false){
                $this->error('库存不足!', '', $res);
            }else{
                $this->db->query("update ###_goods set stock=stock-{$cart['qty']} where id={$cart['id']} and stock>={$cart['qty']}");
            }
        }else{
            $is_stock = $this->db->query("update ###_goods set stock=stock-{$cart['qty']} where id={$cart['id']} and stock>={$cart['qty']}");
            if($is_stock==false){
                $this->error('库存不足!', '', $res);
            }
        }

        $order_id = $order['order_id'] = $this->db->insert('goods_order', $order);
        if (!$order_id) {
            $this->error('订单添加失败!', '', $res);
        }
		
        //如果是积分兑换，就需要扣除积分
        if($order['score']>0&&isset($_POST['exchange_pay'])){
        	$this->load->model('exchange');
        	if($this->exchange->power){
        		$this->exchange->action(array('mid'=>MID,'order_sn'=>$order_sn,'score'=>$order['score'],'type'=>"0"));
        	}
        }

        $this->load->model("goods");
        foreach ($cart_goods as $v) {
            $sp_val = $this->db->getstr("select sp_val from ###_goods where id={$v['goods_id']}", "sp_val");
            $data_goods = array(
                'mid' => MID,
                'order_id' => $order_id,
                'cost_price' => $v['cost_price'],
                'good_id' => $v['goods_id'],
                'goods_spec' => $this->goods->getSpec($sp_val, $v['spec']),
                'spec' => $v['spec'],
                'goods_name' => $v['goods_name'],
                'buy_num' => $v['qty'],
                'sell_price' => $v['goods_price'],
                'c_time' => time(),
                'extension_id' => $v['obj_id'],
                'type' => $v['type'],
                'team_num' => $v['team_num'],
                'express_id' => $_POST['express_id'],
            );
            $this->db->insert('goods_order_item', $data_goods);
        }

        // 模版消息 1 下单成功 {插入昵称},{插入订单号}
        // template_msg_action start
        $this->load->model('template_msg');
        $msgParams = array(getUsername(MID), $order_sn);
        $this->template_msg->inQueue(1, 0, $msgParams);
        // template_msg_action end

        //更新砍价记录下单id
        if(isset($_SESSION['cart']['log_id']) && $_SESSION['cart']['log_id']>0){
            $this->db->update("bargain_log",array("order_id"=>$order_id),array("id"=>$_SESSION['cart']['log_id']));
        }
        unset($_SESSION['cart']['log_id']);

        //下单成功跳转页
        $url = '/member/order';

        //支付金额为0
        if ($total['flowTotal'] <= 0) {
            //余额红包积分等处理
            $this->load->model('order');

            $order = $this->db->get("SELECT * FROM ###_goods_order WHERE id=" . $order_id);

            $this->order->moneyOrder($order);
            
            $this->db->update("goods_order", array('pay_time'=>RUN_TIME), array("id"=>$order_id));
            //拼团处理
            $common_id = $this->order->order_common($order);
            if($common_id>0){
                $this->success('订单支付成功!', '/goods/team/'.$common_id.'?show=1');
            }else{
                $this->success('订单支付成功!', $url);
            }
        }
        //货到付款，跳转到订单中心
        if ($total['is_cod'] == 1) {
            $this->success('下单成功!', $url);
        }

        //第三方支付跳转
        $this->success('', '/flow/pay/' . $order_sn);
        // $this->redirect('/flow/pay/' . $order_sn);
    }
    
    /**
     * ajax修改购物车
     *
     * */
    function ajax_cart() {
        $post = $_POST;
        foreach ($post['num'] as $k => $v) {
            $_POST = array();

            $_POST['id'] = $post['goods_id'][$k];

            $_POST['qty'] = $v;

            $_POST['spec'] = $post['spec'][$k];

            $_POST['act'] = "|";

            $_POST['is_selected'] = $post['is_selected'][$k];

            $this->add_cart("ajax_cart");
        }
        die(json_encode(array('error' => 0)));
    }

    

    /** 删除购物车
     */
    function del_cart($ids = '') {
        $ids = explode(',', $ids);

        foreach ($ids as $id) {
            $id = intval($id);
            if (!$id) {
                continue;
            }

            $cart_goods = $this->cart_goods;

            foreach ($cart_goods as $cart_good_key => $cart_good) {
                if ($cart_good['id'] == $id) {
                    unset($cart_goods[$cart_good_key]);
                }
            }

            if (!$_SESSION['mid']) {
                zzcookie('goods_cart', serialize($cart_goods));
            } else {
                $this->db->delete("cart", array('id' => $id, 'mid' => $_SESSION['mid']));
            }
        }

        $this->load->model('flow');

        $cart_num = $this->flow->cart_num($cart_goods);

        $cart_total = $this->flow->cart_total_cart($cart_goods);

        $result = array('error' => 0, 'cartNum' => $cart_num, 'cart_total' => $cart_total);
        exit(json_encode($result));
    }

    /** 购物车页面
     */
    function cart() {


        $data['cart_goods'] = $this->cart_goods;

        $data['cart_total'] = $this->flow->cart_total();

        $_SESSION['cart_type'] = 0;

        $this->smarty->assign('data', $data);

        $this->display_before(array('title' => '购物车'));

        $this->smarty->display('flow/cart.html');
    }

    /**
     * 直购
     */
    /*function buy() {
        $data = array('error' => 2, 'msg' => '未知错误');

        //未登录
        if (!$_SESSION['mid']) {
            $data['error'] = 1;
            exit(json_encode($data));
        }

        //获取必要参数
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $qty = isset($_POST['qty']) ? intval($_POST['qty']) : 0;
        $type = isset($_POST['type']) ? intval($_POST['type']) : 0;
        if ($id == 0 || $qty == 0 || $type == 0) {
            $data['msg'] = '参数错误！';
            exit(json_encode($data));
        }

        if (!in_array($type, array(CART_KILL, CART_TUAN, CART_CRDT))) {
            //TODO:其它商品类型
            exit(json_encode($data));
        }

        //获取促销商品信息
        $this->load->model('auction');
        $row = $this->db->get("SELECT * FROM " . $this->auction->baseTable . " WHERE act_id=" . $id);
        $ext_info = unserialize($row['ext_info']);
        $row = array_merge($row, $ext_info);

        //判断是否可以购买
        $tmp = $this->auction->is_order_auction($id, $qty, $type);
        if ($tmp['msg']) {
            $data['msg'] = $tmp['msg'];
            exit(json_encode($data));
        }

        //加入直购购物车
        $cart_new = array(
            'mid' => MID,
            'goods_id' => $row['goods_id'],
            'goods_name' => $row['act_name'],
            'goods_price' => $row['act_price'],
            'goods_points' => $row['act_points'],
            'qty' => $qty,
            'subtotal' => formatPrice($row['act_price'] * $qty),
            'subtotal_points' => $row['act_points'] * $qty,
            'type' => $type,
            'obj_id' => $id,
        );

        zzcookie('goods_cart_' . $type, base64_encode(serialize($cart_new)));
        $_SESSION['cart_type'] = $type;
        $data['error'] = 0;
        exit(json_encode($data));
    }*/
    
    
    //type=1通用优惠券，type=2商家优惠券
    function coupon_ajax_list($id=0,$type=1){
        $goods_list = array($_SESSION['cart']);
        $goods_list = $this->db->lJoin($goods_list,"goods","id,cid,sid","goods_id","id","goods_");
        
        //获取未领取的优惠券
        if($type==2){
            $is_res = $this->db->select("select distinct coupon_id from ###_coupon_log where mid=".MID." and sid=".$goods_list[0]['goods_sid']);
            if($is_res){
                $string_res = join(",",  array_column($is_res, "coupon_id"));       
                $list_res = $this->db->select("select * from ###_coupon where id not in ({$string_res}) and status=1 and stock>0 and (end_time>=".RUN_TIME." || end_time=0) and sid=".$goods_list[0]['goods_sid']);
            }  else {
                $list_res = $this->db->select("select * from ###_coupon where status=1 and stock>0 and (end_time>=".RUN_TIME." || end_time=0) and sid=".$goods_list[0]['goods_sid']);
            }
            $this->smarty->assign('list_res', $list_res);
        }     
        
        
        // 获取可用优惠券列表
        $sid = 0;
        if($type==2)$sid=$goods_list[0]['goods_sid'];
        $this->load->model('coupon');
        $data['coupon_list'] = $this->coupon->getCouponList($goods_list, true,$sid);
        $data['coupon_id'] = intval($id);
        $this->smarty->assign('data', $data);
        if($type==2){
            $content = $this->smarty->fetch('flow/coupon_list_sid.html');        
        }else{
            $content = $this->smarty->fetch('flow/coupon_list.html');        
        }        
        echo $content;exit;
    }

    public function getFreight() {
        //收货地址
        $address_id = isset($_GET['address_id']) ? intval($_GET['address_id']) : 0;

        $res = array(
            'error' => 1,
            'msg' => '请先添加,并选择收货地址',
        );

        if (!$address_id) {
            exit(json_encode($res));
        }

        $this->load->model('member');

        $address = $this->member->get_address('id=' . $address_id);
        if (empty($address['zone'])) {
            exit(json_encode($res));
        }

        $order_sn = isset($_GET['order_sn']) ? $_GET['order_sn'] : '';

        if ($order_sn) {
            //订单信息
            $order_info = $this->db->get("SELECT * FROM ###_goods_order WHERE mid='" . MID . "' AND order_sn='" . $order_sn . "'");
            if (empty($order_info)) {
                $this->error('该订单不存在', '/member/order');
            }

            $cart_goods = $this->flow->getOrderGoodList($order_info['id']);
            if (empty($cart_goods)) {
                $this->error('订单商品已失效.请重新下单', '/goods/index');
            }
        } else {
            $this->cart_goods = $this->flow->cart_goods('', '', 1);

            $cart_goods = $this->cart_goods;
            if (empty($cart_goods)) {
                $this->error('您的购物车是空的！', '/goods/index');
            }
        }

        $freight = $this->flow->getFright($cart_goods, $address['zone']);
        if (empty($freight)) {
            return $res;
        }

        $data['fright'] = $freight;

        $goodsTotal = $this->flow->cart_total_cart($cart_goods);

        $this->smarty->assign('goodsTotal', $goodsTotal);

        $this->smarty->assign('data', $data);

        $res['html'] = $this->smarty->fetch('member/lbi/freight_list.html');

        $res['error'] = 0;

        $res['msg'] = '获取成功';

        exit(json_encode($res));
    }

    /** 订单确认页
     */
    function checkout($order_sn = '') {
        $data = array('title' => '结算');

        $this->load->model('order');

        $this->load->model('member');

        $back = '/flow/checkout';

        $order_info = array();

        $data['cart_type'] = isset($_SESSION['cart_type']) ? $_SESSION['cart_type'] : 0;

        //收货地址
        $address_id = isset($_GET['address_id']) ? intval($_GET['address_id']) : 0;

        if ($order_sn) {
            //订单信息
            $order_info = $this->db->get("SELECT * FROM ###_goods_order WHERE mid='" . MID . "' AND order_sn='" . $order_sn . "'");
            if (empty($order_info)) {
                $this->error('订单不存在', '/member/order');
            }

            $data['cart_goods'] = $this->flow->getOrderGoodList($order_info['id'], true);
            if (empty($data['cart_goods'])) {
                $this->error('订单商品已失效.请重新下单', '/goods/index');
            }

            //判断支付状态
            if (!$this->order->order_status(CS_AWAIT_PAY, $order_info)) {
                $this->error('该订单不可再支付');
            }

            $back = '/flow/checkout/' . $order_sn;

            $this->smarty->assign('order_sn', $order_sn);
        } else {

            if ($_SESSION['cart_type'] > 0) {
                $data['cart_goods'] = $this->flow->cart_goods_auc();
            } else {
                $this->cart_goods = $this->flow->cart_goods('', '', 1);
                $data['cart_goods'] = $this->cart_goods;
            }
            if (empty($data['cart_goods'])) {
                header('Location:' . url('/flow/cart'));
            }
        }

        //收货地址
        $address_id = empty($order_info['address_id']) ? 0 : $order_info['address_id'];

        //收货地址
        $addresses = $this->member->member_address(MID, 1);

        $data['address_list'] = empty($addresses) ? array() : $addresses;
        if ($addresses) {
            if (empty($addresses[$address_id])) {
                $data['address'] = array_slice($addresses, 0, 1);

                $data['address'] = $data['address'][0];

                $address_id = $data['address']['id'];
            } else {
                $data['address'] = $addresses[$address_id];
            }
        } else {
            $data['address'] = array();
        }

        $zone = empty($data['address']['zone']) ? 0 : $data['address']['zone'];

        $data['weight'] = $this->flow->getWeight($data['cart_goods']) ? : 0;

        // 账户余额
        //配送方式
        $data['fright'] = $this->flow->getFright($data['weight'], $zone);

        foreach ($data['fright'] as $k => $v) {
            if (!empty($order_info['fright_id']) && $v['id'] == $order_info['fright_id']) {
                $this->smarty->assign('freight', $v);

                break;
            }
        }

        $member = $this->member->member_info(MID);

        $data['flow_user_money'] = $member['user_money'];

        //今天
        // $week          = array("日", "一", "二", "三", "四", "五", "六");
        // $data['today'] = date("Y-m-d，星期" . $week[date('w')]);
        // //配送时间
        // $week        = date('w');
        // $data['sat'] = date('m月d日', strtotime('+' . 2 - $week . ' days'));
        // $data['sun'] = date('m月d日', strtotime('+' . 5 - $week . ' days'));
        //支付方式
        $this->load->model('payment');

        $data['payment'] = $this->payment->getPayment("enabled = '1'");

        // 获取可用优惠券列表
        $this->load->model('coupon');

        $data['coupon_list'] = $this->coupon->getCouponList($data['cart_goods'], true);

        $this->smarty->assign('address_id_checked', $address_id);

        $this->smarty->assign('back', $back);

        $this->smarty->assign('order', $order_info);

        $this->smarty->assign('data', $data);
        $this->smarty->assign('token', createToken());

        $this->smarty->display('flow/checkout.html');
    }

    /**
     * 从chekout 页面生成订单
     * todo 增加一个create_order 方法 将一切购物流程 生成订单的过程分离到 checkout之前 时checkout 只处理已存在的订单
     * @return
     */
    function done_fenxiao() {
        // if (!isset($_SESSION['cart_type'])) {
        // 	exit($this->msg('订单购买超时，已失效！', array('url' => '/member/order')));
        // }
        //令牌检查
        // todo 需检查
        // if (!checkToken()) {exit($this->msg('请不要重复提交订单！'));}

        if (!checkToken()) {
            $res = array(
                'data' => array(
                    'token' => createToken(),
                ),
            );
            // $this->exeJs('location.href="/flow/checkout/' . $order_sn . '"');die;
            // header('location:/flow/checkout/' . $order_sn);
            $this->error('页面已过期！,请刷新', '', $res);
        }

        $res = array(
            'data' => array(
                'token' => createToken(),
            ),
        );
        //  刷新token
        // createToken();

        $order_sn = isset($_POST['order_sn']) ? trim($_POST['order_sn']) : '';

        $address_id = isset($_POST['address_id']) ? intval($_POST['address_id']) : 0;

        // 收货地址验证
        if (!$address_id) {
            $this->error('请选择收货地址！', '', $res);
        }

        //配送方式
        /*$fright_id = isset($_POST['fright_id']) ? intval($_POST['fright_id']) : 0;
        if (!$fright_id) {
            $this->error('请选择配送方式！', '', $res);
        }*/

        $this->load->model('member');

        $address_info = $this->member->get_address("mid='" . MID . "' AND id='" . $address_id . "'");
        if (!$address_info) {
            $this->error('请添加一个收货地址！', '', $res);
        }

        $this->load->model('order');

        $order = array(
            'mid' => MID,
            'c_time' => RUN_TIME,
        );

        //购物车
        $cart_goods = array();

        $order_info = array();

        if ($order_sn) {
            //订单信息
            $order_info = $this->db->get("SELECT * FROM ###_goods_order WHERE mid='" . MID . "' AND order_sn='" . $order_sn . "'");
            if (empty($order_info)) {
                $this->error('订单不存在', '/member/order', $res);
            }

            $cart_goods = $this->flow->getOrderGoodList($order_info['id']);
            if (empty($cart_goods)) {
                $this->error('订单商品已失效.请重新下单', '/goods/index', $res);
            }
            //判断支付状态
            if (!$this->order->order_status(CS_AWAIT_PAY, $order_info)) {
                $this->error('该订单不可再支付', '/member/order', $res);
            }

            $order_id = $order_info['id'];
        } else {

            if ($_SESSION['cart_type'] > 0) {
                $cart_goods = $this->flow->cart_goods_auc();
                $order['extension_code'] = $_SESSION['cart_type'];
                $order['extension_id'] = $cart_goods[0]['obj_id'];
            } else {
                $this->cart_goods = $this->flow->cart_goods('', '', 1);
                $cart_goods = $this->cart_goods;
            }
            if (empty($cart_goods)) {
                $this->error('您的购物车是空的！', '/goods/index', $res);
            }
        }

        /*$fright = $this->flow->getFright($cart_goods, $address_info['zone']);
        if (empty($fright[$fright_id])) {
            $this->error('请选择一个配送方式！', '', $res);
        }*/

        //收货地址
        $order['zone'] = $address_info['zone'];

        $order['area'] = $address_info['area'];

        $order['address'] = $address_info['address'];

        $order['mobile'] = $address_info['mobile'];

        $order['name'] = $address_info['name'];

        $order['address_id'] = $address_id;

        // $order['express'] 快递id 应该是配送方式类型id
        // 注意这里跟配送方式是耦合的
        // 如支付前配送方式变更 这里查询的名称甚至金额均会变更 具体金额以支付时的金额为准
        $order['express'] = $fright_id;
        // $order['fright_id'] 支付方式id 旧系统有货到付款等其他形式 express 跟 fright_id 不同
        $order['fright_id'] = $fright_id;

        $order['fright_name'] = $fright[$fright_id]['title'];

        //支付方式与结算
        $pay_id = isset($_POST['pay_id']) ? intval($_POST['pay_id']) : 0;

        // 优惠券id
        $order['coupon_id'] = isset($_POST['coupon_id']) ? intval($_POST['coupon_id']) : 0;

        //$total = $this->flow->total($_POST, $cart_goods, $order_info);
        $total = $this->flow->total($_POST, $cart_goods);

        if ($total['flowTotal'] > 0 && !$pay_id) {
            $this->error('请选择一个支付方式，支付剩余款项！', '', $res);
        }

        // 订单总价
        $order['order_amount'] = isset($total['flowOrderTotal']) ? $total['flowOrderTotal'] : 0;
        // 商品总价
        $order['goods_amount'] = isset($total['flowGoodsTotal']) ? $total['flowGoodsTotal'] : 0;
        // 商品成本价 Feng 2016-06-02
        $order['cost_amount'] = isset($total['flowCostTotal']) ? $total['flowCostTotal'] : 0;
        // 运费
        $order['shipping_fee'] = isset($total['shipping_fee']) ? $total['shipping_fee'] : 0;
        // 未支付金额 扣除
        $order['amount'] = isset($total['flowTotal']) ? $total['flowTotal'] : 0;
        // 余额
        $order['surplus'] = isset($total['flow_user_money_fee']) ? $total['flow_user_money_fee'] : 0;

        // 货到付款
        $order['is_cod'] = isset($total['is_cod']) ? intval($total['is_cod']) : 0;

        // $order['integral'] 旧积分抵扣 已废除
        // $order['integral'] = isset($total['points_fee']) ? $total['points_fee'] : 0;
        // $order['bonus'] 红包 已废除
        // $order['bonus'] = isset($total['flow_bonus_money_fee']) ? $total['flow_bonus_money_fee'] : 0;
        // $order['send_bonus'] 旧 1赠送红包 已废除
        // $order['send_bonus'] = $total['flowBonusMoney'] > 0 ? 1 : 0;
        // $order['credit'] 本次订单可送积分  已废除
        // $order['credit']        = isset($total['flowJf']) ? $total['flowJf'] : 0;
        // 使用的所有红包ID 注意这里是复数形式 废除
        // $order['user_bonus_id'] = (isset($total['flow_bonus_ids']) && !empty($total['flow_bonus_ids'])) ? trim($total['flow_bonus_ids']) : 0;
        //获取支付方式
        if ($pay_id) {
            $this->load->model('payment');

            $payment = $this->payment->payment_info($pay_id);

            $order['pay_id'] = $pay_id;

            $order['pay_name'] = addslashes($payment['pay_name']);
        }

        //应付金额为0时，更新付款状态
        if ($total['flowTotal'] <= 0) {
            $order['status_pay'] = 10;
        }

        //配送时间 当前分销项目暂无 不过可以让用户手动填写 或者选择. 如果增加的话 建议做成时间范围选择
        if (isset($_POST['best_time'])) {
            $order['best_time'] = isset($_POST['best_time']) ? stripcslashes($_POST['best_time']) : '';
        }

        //其它信息 checkout 页面 用户填写的 给卖家留言
        if (isset($_POST['order_tip'])) {
            $order['order_tip'] = isset($_POST['order_tip']) ? stripcslashes($_POST['order_tip']) : '';
        }

        if (!$order_sn) {
            // 	// 无 订单号才会有 $cart_goods
            // 	//订单号
            $order['order_sn'] = $order_sn = $this->flow->order_sn();

            // 	//写入套餐配置
            // 	// CART_PACK 标识套餐类型的购物车
            // 	// if ($cart_goods[0]['type'] == CART_PACK) {
            // 	// 	$sql      = "SELECT ext_info,goods_list FROM ###_goods_activity WHERE act_id='" . $cart_goods[0]['obj_id'] . "'";
            // 	// 	$row      = $this->db->get($sql);
            // 	// 	$ext_info = unserialize($row['ext_info']);
            // 	// 	#有效时间
            // 	// 	$order['end_time'] = strtotime('+' . intval($ext_info['act_day']) . ' day');
            // 	// 	#套餐商品
            // 	// 	$order['goods_list'] = $row['goods_list'];
            // 	// }
            // 	//保存订单表
            $order_id = $order['order_id'] = $this->db->insert('goods_order', $order);
            if (!$order_id) {
                $this->error('订单添加失败!', '', $res);
            }

            // 	// //保存订单商品表
            // 	// if ($cart_goods[0]['type'] == CART_PACK) {
            // 	// 	//获取套餐商品到购物车商品表
            // 	// 	foreach ($cart_goods[0]['list'] as $w) {
            // 	// 		foreach ($w['list'] as $k => $v) {
            // 	// 			$data_goods = array(
            // 	// 				'mid'          => MID,
            // 	// 				'order_id'     => $order_id,
            // 	// 				'good_id'      => $v['goods_id'],
            // 	// 				'goods_name'   => $v['goods_name'],
            // 	// 				'buy_num'      => $v['goods_number'],
            // 	// 				'sell_price'   => $v['goods_price'],
            // 	// 				'c_time'       => time(),
            // 	// 				'extension_id' => $cart_goods[0]['obj_id'],
            // 	// 			);
            // 	// 			$this->db->save('goods_order_item', $data_goods);
            // 	// 			#写入套餐选菜表
            // 	// 			$data_goods['qishu'] = 1;
            // 	// 			$this->db->save('goods_order_item_pack', $data_goods);
            // 	// 		}
            // 	// 	}
            // 	// } else {
            $this->load->model("goods");
            foreach ($cart_goods as $v) {
                $sp_val = $this->db->getstr("select sp_val from ###_goods where id={$v['goods_id']}", "sp_val");
                $data_goods = array(
                    'mid' => $v['mid'],
                    'order_id' => $order_id,
                    'cost_price' => $v['cost_price'],
                    'good_id' => $v['goods_id'],
                    'goods_spec' => $this->goods->getSpec($sp_val, $v['spec']),
                    'spec' => $v['spec'],
                    'goods_name' => $v['goods_name'],
                    'buy_num' => $v['qty'],
                    'sell_price' => $v['goods_price'],
                    'c_time' => time(),
                    'extension_id' => $v['obj_id'],
                );

                $this->db->insert('goods_order_item', $data_goods);
            }
            // }
            //清空购物车
            $this->flow->cart_empty('', 1);

            // 模版消息 1 下单成功 {插入昵称},{插入订单号}
            // template_msg_action start
            $this->load->model('template_msg');
            $msgParams = array(getUsername(MID), $order_sn);
            $this->template_msg->inQueue(1, 0, $msgParams);
            // template_msg_action end
        } else {
            //修改订单表
            $this->db->update('goods_order', $order, '', array('order_sn' => $order_sn));
        }

        //下单成功跳转页
        $url = '/member/order';
        // if ($_SESSION['cart_type'] == CART_PACK) {
        // 	$url = '/member/package';
        // }
        //付款完成，跳转到订单中心
        if ($total['flowTotal'] <= 0) {
            //余额红包积分等处理
            $this->load->model('order');

            $order = $this->db->get("SELECT * FROM ###_goods_order WHERE id=" . $order_id);

            $this->order->moneyOrder($order);

            $this->success('订单支付成功!', $url);
        }
        //货到付款，跳转到订单中心
        if ($total['is_cod'] == 1) {
            $this->success('下单成功!', $url);
        }

        //第三方支付跳转
        $this->success('', '/flow/pay/' . $order_sn);
        // $this->redirect('/flow/pay/' . $order_sn);
    }

    //支付最终页
    function pay($order_sn) {

        //判断是否由checkout页面提交过来 以防修改商品价格后支付原来旧价格
        //if (!strpos($_SERVER['HTTP_REFERER'], 'flow/checkout'))$this->redirect('/flow/checkout/' . $order_sn);
        
        //订单信息
        $order_info = $this->db->get("SELECT * FROM ###_goods_order WHERE mid='" . MID . "' AND order_sn='" . $order_sn . "'");
        if (empty($order_info)) {
            $this->error('该订单不存在');
        }

        if(!check_team(MID,$order_info['extension_code']) && $order_info['common_id']>0 && $order_info['extension_code']>0){
            $this->error("该团仅限绑定手机的新用户能参团");
        }

        //判断是否已参团
        if($order_info['common_id']>0 && $order_info['status_pay']==0){
            $is_res = $this->db->getstr("select 1 from ###_goods_order where common_id={$order_info['common_id']} and status_pay>0 and mid=".MID);
            if($is_res)$this->error('您已参过该团', '', $res);
            $team = $this->db->getstr("select 1 from ###_goods_order_common where id={$order_info['common_id']} and status=".TEAM_ING);
            if(!$team)$this->error('该团已失效', '', $res);
        }
        //判断优惠券是否已被使用
        if($order_info['coupon_id']>0){
            $r = $this->db->get("select 1 from ###_coupon_log where id={$order_info['coupon_id']} and status=2");
            if($r)$this->error('该订单优惠券已被使用');
        }
        //判断店铺优惠券是否已被使用
        if($order_info['coupon_id_sid']>0){
            $r = $this->db->get("select 1 from ###_coupon_log where id={$order_info['coupon_id_sid']} and status=2");
            if($r)$this->error('该订单店铺优惠券已被使用');
        }
        
        //秒杀判断是否有库存
        $row = $this->db->get("select g.stock,g.is_sale,g.name,g.end_time,g.limit_buy_one,g.id from ###_goods_order_item as o left join ###_goods as g on o.good_id=g.id where o.order_id=".$order_info['id']);
        if($row['stock']<=0 && $order_info['end_amount']==0){
            //$this->error('该商品已抢光');
        }elseif($row['is_sale']==0){
            $this->error('该商品已下架');
        }
        //秒杀，免费试用，抽奖到期不能再支付
        if(in_array($order_info['extension_code'], array(CART_KILL,CART_LUCK,CART_FREE))){
            if($row['end_time']<=RUN_TIME){
                $this->error('该活动已结束');
            }
        }
        //判断是否限购
        if($row['limit_buy_one']==1 && $order_info['end_amount']==0){
            $is_has = $this->db->get("select 1 from ###_goods_order where extension_id={$row['id']} and status_pay>0 and mid=".MID);
            if($is_has){
                $this->error('该商品每人限购一次');
            }
        }

        //判断支付状态
        $this->load->model('order');
        if (!$this->order->order_status(CS_AWAIT_PAY, $order_info)) {
            $this->error('该订单不可再支付');
        }

        //判断支付费用
        if ($order_info['amount'] <= 0) {
            $this->error('订单已经支付完成');
        }

        //生成支付按钮
        $this->load->model('payment');

        $payment_info = $this->payment->payment_info($order_info['pay_id']);

        $order = array();

        $order['order_sn'] = $order_info['order_sn'];
        //计算支付手续费用
        $payment_info['pay_fee'] = $this->payment->pay_fee($order_info['pay_id'], $order_info['amount']);

        $order['order_amount'] = $order_info['amount'] + $payment_info['pay_fee'];
        
        //判断是否已经存在支付记录
        if($order_info['extension_code']==CART_STEP ){
            $order_type = $order_info['status_pay']==1?PAY_END:PAY_PRE;
        }else{
            $order_type = PAY_ORDER;
        }
        $log_info = $this->db->get("SELECT * FROM ###_pay_log WHERE order_id = '{$order_info['id']}' and order_type='{$order_type}'");
        if($log_info==false){
            $order['log_id'] = $this->payment->pay_log_save(array('order_id' => $order_info['id'], 'order_amount' => $order_info['amount'], 'order_type' => $order_type, 'is_paid' => PS_UNPAYED));
        }else{
            $order['log_id'] = $log_info['log_id'];
        }
        
        $order['out_trade_no'] = date("YmdHis").'_'.$order['log_id'];
        
        $this->payment->pay_log_save(array('log_id'=>$order['log_id'],'out_trade_no'=>$order['out_trade_no'],'order_amount' => $order['order_amount']));

        //取得支付信息，生成支付代码
        $payment = unserialize_config($payment_info['pay_config']);

        /* 调用相应的支付方式文件 */
        include_once AppDir . 'includes/modules/payment/' . $payment_info['pay_code'] . '.php';
        /* 取得在线支付方式的支付按钮 */
        $pay_obj = new $payment_info['pay_code'];
        $order['goods_name'] = cut_str($row['name'],40);
        $payment_info['pay_button'] = $pay_obj->get_code($order, $payment);

        // 用户放弃支付的时候 回退会进入对应订单检查状态
        $this->smarty->assign('headBack', '/flow/checkout/' . $order['order_sn']);

        $this->smarty->assign('order', $order_info);

        $this->smarty->assign('payment_info', $payment_info);

        $this->display_before(array('title' => '订单支付'));

        $this->smarty->display('flow/pay.html');
    }

    /**
     * ajax重新计算订单各项金额
     */
    // function total($order_sn = '') {
    // 	$order_info = array();
    // 	if ($order_sn) {
    // 		$order_info = $this->db->get("SELECT * FROM ###_goods_order WHERE mid='" . MID . "' AND order_sn='" . $order_sn . "'");
    // 	}
    // 	$ids = explode(',', trim($_POST['ids']));
    // 	if ($ids) {
    // 		$this->cart_goods = $this->flow->cart_goods($ids);
    // 	}
    // 	$cart_goods = $this->cart_goods;
    // 	if ($_SESSION['cart_type'] > 0) {
    // 		$cart_goods = $this->flow->cart_goods_auc();
    // 	}
    // 	$data = $this->flow->total($_POST, $cart_goods, $order_info);
    // 	exit(json_encode($data));
    // }

    /** 生成新的订单号
     */
    // private function get_order_sn() {
    // 	$order_sn = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), -8, 8);
    // 	return $order_sn;
    // }
}
