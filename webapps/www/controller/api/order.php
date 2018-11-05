<?php

/**
 * Class order 接口
 */
class order extends Lowxp{

    function __construct() {
        parent::__construct();
        $isLogin = isset($_SESSION['mid']);
        if($isLogin){
            $this->load->model('goods');
            $this->load->model("member");
            $this->load->model('payment');
            $this->load->model('linkage');
            $this->load->model('order');
            $this->load->model('flow');
            $this->load->model('coupon');
            $this->memberinfo = $this->member->member_info($_SESSION['mid']);
            define('MID',$_SESSION['mid']);
            define('USER',$this->memberinfo['username']);
        }else{
            $this->api_result(array('flag' => false, 'msg' => '请登录', 'code' => 400001));
        }
    }

    /**
     * 下单详情
     */
    public function check(){
        $log_id         = isset($_POST['log_id']) ? intval($_POST['log_id']) : 0;//砍价id
        if($log_id>0){
            $bargain = $this->db->get("select goods_id,last_price,spec from ###_bargain_log where id={$log_id}");
            $id = $bargain['goods_id'];
            $num = 1;
            $type = CART_GOODS;
            $spec = $bargain['spec'];
        }else{
            $id         = isset($_POST['id']) ? intval($_POST['id']) : 0;//商品id
            $num        = isset($_POST['num']) ? intval($_POST['num']) : 1;//数量
            $type     = isset($_POST['typeid']) ? intval($_POST['typeid']) : CART_TUAN;//购买类型
            $spec       = isset($_POST['spec']) ? trim($_POST['spec']) : '';//规格参数
            $common_id  = isset($_POST['common_id']) ? intval($_POST['common_id']) : 0;//团id(参团的时候用到)
            $option = isset($_POST['option']) ? trim($_POST['option']) : '';//自选团参数
            $coupon_id = isset($_POST['coupon_id']) ? intval($_POST['coupon_id']) : '';//通用优惠券
            $coupon_id_sid = isset($_POST['coupon_id_sid']) ? intval($_POST['coupon_id_sid']) : '';//店铺优惠券
        }


        if(!$id){

            $this->api_result(array('flag' => false, 'msg' => '参数异常，商品id不能为空', 'code' => 100002));
        }

        $goods = $this->goods->get($id, array("spec" => $spec));
        if(isset($bargain['last_price']) && !empty($bargain['last_price'])){
            $goods['typeid']=CART_GOODS;
            $goods['buy_num_limit']=1;
            $goods['team_price'] = $bargain['last_price'];
        }

        if (empty($goods)) {
            $this->api_result(array('flag' => false, 'msg' => '商品信息错误', 'code' => 100001));
        }

        if($goods['limit_buy_bumber'] > 0 && $type > 0){
            $max = $goods['limit_buy_bumber'];
        }else{
            $max = $goods['stock'];
        }
        $num = $num>$max?$max:$num;
        if($num<=0){
            $this->api_result(array('flag' => false, 'msg' => '没有库存了', 'code' => 100001));
        }
        if($common_id>0){//判断后台是否修改商品类型
            $common_info = $this->db->get("select team_num,team_price,goods_typeid from ###_goods_order_common where id=".$common_id);
            if($type!=$common_info['goods_typeid']){
                $this->api_result(array('flag' => false, 'msg' => '该活动已经结束', 'code' => 100001));
            }
            $goods['team_price'] = !in_array($type,array(7,9,10))?$goods['team_price']:$common_info['team_price'];
        }
        //海淘产品需要实名认证才能购买
        if(C('nation_realname')==1 && $goods['nation_id']>0){
            $res_card = $this->db->getstr("select realname from ###_member_detail where mid=".MID,"realname");
            if(empty($res_card)){
                $this->api_result(array('flag' => false, 'msg' => '全球购产品需要实名认证', 'code' => 100007));
            }
        }

        if($type != CART_GOODS){
            if($type==CART_OPTION && strpos($option,"-")){#自选开团
                $option_array = explode("-",$option);
                $goods['team_num'] = $option_array[0];
                $price = $option_array[1];
            }elseif($type==CART_STEP){#阶梯团
                $goods['team_num'] = end($goods['step']['team_num']);
                $price =  $goods['team_price'];
            }else{
                $goods['team_num'] = $goods['team_num'];
                $price = $goods['team_price'];
            }
            $market_price = $goods['price'];
        }else{
            if($goods['typeid']==CART_GOODS){
                $price = $goods['team_price'];
                $market_price = $goods['price'];
            }else{
                $price = $goods['price'];
                $market_price = $goods['market_price'];
            }
        }

        $cart_new = array(
            'goods_id' => $goods['id'],
            'goods_name' => $goods['name'],
            'cost_price' => $goods['cost_price'],
            'market_price' => $market_price,
            'goods_price' => $price,
            'spec' => $spec,
            'qty' => $num,
            'subtotal' => formatPrice($price * $num),
            'type' => $type,//拼团类型
            'common_id' => $common_id,//拼团id
            'team_num' => $goods['team_num'],//组团人数
            'log_id' =>$log_id,//砍价id
        );
        $_SESSION['cart'] = $cart_new;
        $data = array();
        $data['app_checking'] = null;//APP审核版本
        $data['web_payurl'] = null;//web支付地址
        //APP审核时 跳到web端支付用
        if(C('app_checking')){
            S("cart_".$_SESSION['mid'],null);
            S("cart_".$_SESSION['mid'],$cart_new);
            $data['app_checking'] = C('app_checking');
            $data['web_payurl'] = RootUrl."flow/team/".$goods['id']."?sign=".encrypt_en($_SESSION['mid']);
        }

        if(!check_team(MID,$goods['typeid']) && $common_id > 0 && $goods['typeid'] > 0){
            $this->api_result(array('flag' => false, 'msg' => '该团仅限新用户能参团', 'code' => 100001));
        }

        #获取收货地址
        $this->load->model('take_address');
        $express_sql = "select * from ###_express where status = 1 and sid = {$goods['sid']} order by listorder asc,id asc";
        if($goods['typeid'] == CART_AA && $common_id > 0){
            $goods_order = $this->db->get("select address_id,take_address_id from ###_goods_order where common_id={$common_id}");
            if($goods_order['address_id'] > 0 && $goods_order['take_address_id'] == 0){
                $address = $this->member->get_address("id=".$goods_order['address_id']);
                $address['id'] = (int)$address['id'];
                $express = $this->db->select($express_sql);
            }elseif($goods_order['address_id'] == 0 && $goods_order['take_address_id'] > 0){
                $take_address = $this->take_address->getById($goods_order['take_address_id']);
            }
        }else{
            $address_id = empty($order_info['address_id']) ? 0 : $order_info['address_id'];
            $addresses = $this->member->member_address(MID, 1);
            if ($addresses) {
                if (empty($addresses[$address_id])) {
                    $address = array_slice($addresses, 0, 1);
                    $address = $address[0];
                } else {
                    $address = $addresses[$address_id];
                }
                $address['id'] = (int)$address['id'];
            } else {
                $address = null;
            }
            if($goods['express']) {
                $express = $this->db->select("select * from ###_express where status = 1 AND sid = {$goods['sid']} AND id in({$goods['express']}) order by listorder asc,id asc");
                //$express = $this->db->select($express_sql);
            }
            if($goods['take_address']) {
                $take_address = $this->take_address->selectBySid($goods['sid']," and id in ({$goods['take_address']})");
            }
        }

        $data['address'] = $address;
        $data['express'] = count($express)>0?$express:null;
        $data['take_address'] = count($take_address)>0?$take_address:null;
        $data['goods']['id'] = (int)$goods['id'];
        $data['goods']['img_cover'] = $goods['img_cover'];
        $data['goods']['name'] = $goods['name'];
        $data['goods']['price'] = formatPrice($price);
        $data['goods']['market_price'] = $market_price;
        $data['goods']['goods_spec'] = $goods['goods_spec'];
        $data['goods']['num'] = $num;
        $data['goods']['deposit'] = $goods['deposit'];
        $data['goods']['typeid'] = $goods['typeid'];
        $data['goods']['is_free_shipping'] = $goods['is_free_shipping'];
        $data['goods']['deposit_total'] = formatPrice($goods['deposit']* $num);
        $data['store_name'] = empty($goods['store_name'])?C('site_name'):$goods['store_name'];
        $data['store_id'] = empty($goods['sid'])?null:$goods['sid'];
        if($type > 0) {
            if($goods['limit_buy_bumber'] == 0){
                $data['goods']['buy_num_limit'] = (int)$goods['stock'];
            }else{
                $data['goods']['buy_num_limit'] = (int)$goods['limit_buy_bumber'];
            }
            $data['goods']['subtotal'] = formatPrice($price * $num);
            switch($goods['discount_type']){
                case 0:
                    $data['goods']['discount_amount'] = '0.00';
                    break;
                case 1:
                    $data['goods']['discount_amount'] = $data['goods']['subtotal'];
                    break;
                case 2:
                    $data['goods']['discount_amount'] = $goods['discount_amount'];
                    break;
                default:
                    $data['goods']['discount_amount'] = '0.00';
                    break;
            }
        } else {
            $data['goods']['buy_num_limit'] = (int)$goods['stock'];
            $data['goods']['subtotal'] = formatPrice($price * $num);
            $data['goods']['discount_amount'] = '0.00';
        }

        #商家优惠卷
        $data['seller_unreceive'] = null;
        $data['seller_useable'] = null;
        $data['seller_disable'] = null;
        if ($goods['sid'] > 0) {
            $goods_list = array($_SESSION['cart']);
            $goods_list = $this->db->lJoin($goods_list,"goods","id,cid,sid","goods_id","id","goods_");
            $is_res = $this->db->select("select distinct coupon_id from ###_coupon_log where mid=".MID." and sid=".$goods_list[0]['goods_sid']);
            if($is_res){
                $string_res = join(",",  array_column($is_res, "coupon_id"));
                $list_res = $this->db->select("select * from ###_coupon where id not in ({$string_res}) and status=1 and sid=".$goods_list[0]['goods_sid']);
            }else{
                $list_res = $this->db->select("select * from ###_coupon where status=1 and sid=".$goods_list[0]['goods_sid']);
            }
            $k = 0;

            foreach($list_res as $v){
                $data['seller_unreceive'][$k]['id'] = (int)$v['id'];
                $data['seller_unreceive'][$k]['coupon_id'] = $v['coupon_id'];
                $data['seller_unreceive'][$k]['amount'] = $v['amount'];
                $data['seller_unreceive'][$k]['title'] = $v['title'];
                $data['seller_unreceive'][$k]['start_time'] = $v['start_time'];
                $data['seller_unreceive'][$k]['expire_time'] = $v['expire_time'];
                $data['seller_unreceive'][$k]['need_amount'] = $v['need_amount'];
                $data['seller_unreceive'][$k]['format_start_time'] = !empty($v['start_time']) ? date('Y-m-d',$v['start_time']) : '';
                $data['seller_unreceive'][$k]['format_expire_time'] = !empty($v['expire_time']) ? date('Y-m-d',$v['expire_time']) : '';
                $k++;
            }

            $sid=$goods['sid'];
            $this->load->model('coupon');
            $coupon_list = $this->coupon->getCouponList($goods_list, true, $sid);
            $k = 0;
            foreach($coupon_list['useable'] as $v){
                $data['seller_useable'][$k]['id'] = (int)$v['id'];
                $data['seller_useable'][$k]['coupon_id'] = $v['coupon_id'];
                $data['seller_useable'][$k]['amount'] = $v['amount'];
                $data['seller_useable'][$k]['title'] = $v['title'];
                $data['seller_useable'][$k]['start_time'] = $v['start_time'];
                $data['seller_useable'][$k]['expire_time'] = $v['expire_time'];
                $data['seller_useable'][$k]['need_amount'] = $v['need_amount'];
                $data['seller_useable'][$k]['format_start_time'] = !empty($v['start_time']) ? date('Y-m-d',$v['start_time']) : '';
                $data['seller_useable'][$k]['format_expire_time'] = !empty($v['expire_time']) ? date('Y-m-d',$v['expire_time']) : '';
                $k++;
            }
            $k = 0;
            foreach($coupon_list['disable'] as $v){
                $data['seller_disable'][$k]['id'] = (int)$v['id'];
                $data['seller_disable'][$k]['coupon_id'] = $v['coupon_id'];
                $data['seller_disable'][$k]['amount'] = $v['amount'];
                $data['seller_disable'][$k]['title'] = $v['title'];
                $data['seller_disable'][$k]['start_time'] = $v['start_time'];
                $data['seller_disable'][$k]['expire_time'] = $v['expire_time'];
                $data['seller_disable'][$k]['need_amount'] = $v['need_amount'];
                $data['seller_disable'][$k]['format_start_time'] = !empty($v['start_time']) ? date('Y-m-d',$v['start_time']) : '';
                $data['seller_disable'][$k]['format_expire_time'] = !empty($v['expire_time']) ? date('Y-m-d',$v['expire_time']) : '';
                $k++;
            }
        }

        #平台优惠券
        $param = array(
            'subtotal' => formatPrice($price * $num),
            'goods_cid'=>$goods['cid'],
        );
        $this->load->model('coupon');
        $coupon_list = $this->coupon->getCouponList(array($param), true);
        $k = 0;
        $data['useable'] = null;
        foreach($coupon_list['useable'] as $v){
            $data['useable'][$k]['id'] = (int)$v['id'];
            $data['useable'][$k]['coupon_id'] = $v['coupon_id'];
            $data['useable'][$k]['amount'] = $v['amount'];
            $data['useable'][$k]['title'] = $v['title'];
            $data['useable'][$k]['start_time'] = $v['start_time'];
            $data['useable'][$k]['expire_time'] = $v['expire_time'];
            $data['useable'][$k]['need_amount'] = $v['need_amount'];
            $data['useable'][$k]['format_start_time'] = !empty($v['start_time']) ? date('Y-m-d',$v['start_time']) : '';
            $data['useable'][$k]['format_expire_time'] = !empty($v['expire_time']) ? date('Y-m-d',$v['expire_time']) : '';
            $k++;
        }
        $k = 0;
        $data['disable'] = null;
        foreach($coupon_list['disable'] as $v){
            $data['disable'][$k]['id'] = (int)$v['id'];
            $data['disable'][$k]['coupon_id'] = $v['coupon_id'];
            $data['disable'][$k]['amount'] = $v['amount'];
            $data['disable'][$k]['title'] = $v['title'];
            $data['disable'][$k]['start_time'] = $v['start_time'];
            $data['disable'][$k]['expire_time'] = $v['expire_time'];
            $data['disable'][$k]['need_amount'] = $v['need_amount'];
            $data['disable'][$k]['format_start_time'] = !empty($v['start_time']) ? date('Y-m-d',$v['start_time']) : '';
            $data['disable'][$k]['format_expire_time'] = !empty($v['expire_time']) ? date('Y-m-d',$v['expire_time']) : '';
            $k++;
        }

        #支付方式
        $where = $_REQUEST['client']=='xiao' ? "pay_code = 'wxpayxiao'" : "(pay_code = 'alipayapp' or pay_code = 'wxpayapp')";
        $payment = $this->payment->getPayment("enabled = '1' AND $where");
        foreach($payment as $k=>$v){
            $data['payment'][$k]['pay_id'] = (int)$v['pay_id'];
            $data['payment'][$k]['pay_name'] = $v['pay_name'];
            $data['payment'][$k]['pay_code'] = $v['pay_code'];
            if(empty($v['thumb'])){
                $data['payment'][$k]['thumb'] = null;
            }else{
                $thumb = json_decode($v['thumb']);
                $data['payment'][$k]['thumb'] = yunurl($thumb[0]->path);
            }

        }
        $data['token'] = createToken();
        $data['member']['user_money'] = $this->memberinfo['user_money'];
        // 积分
        $this->load->model('score');
        $score = $this->score->getTotal(MID);
        $data['score'] = $score;
        $rule = $this->score->getRow(6);
        $data['rule'] = $rule;
        #统计
        $cart = $this->goods->check_goods($_SESSION['cart'],$_POST);
        $total = $this->flow->total($_POST, array($cart));
        $data['subtotal'] = !empty($total['flowTotal']) ? $total['flowTotal'] : $data['goods']['subtotal'];
        if(empty($this->flow->error) && (!empty($coupon_id) || !empty($coupon_id_sid))){
            $data['coupon_id'] = $coupon_id;
            $data['coupon'] = $total['coupon'];
            $data['coupon_id_sid'] = $coupon_id_sid;
            $data['coupon_store'] = $total['coupon_store'];
        }
        unset($goods,$address,$payment);
        $this->api_result(array('data'=>$data));
    }

    /**
     * 计算运费
     */
    public function calculateShippingCosts(){
        $express_id = intval($_GET['express_id']);
        if(!$express_id){
            $this->api_result(array('flag' => false, 'msg' => '请选择运送方式', 'code' => 100001));
        }
        $address_id = intval($_GET['address_id']);
        if(!$address_id){
            $this->api_result(array('flag' => false, 'msg' => '请选择收货地址', 'code' => 100001));
        }
        $this->load->model('flow');
        $data = $this->flow->calculateShippingCosts($express_id, $address_id);
        if(!$data['error']){
            $this->api_result(array('flag' => false, 'msg' => $data['msg'], 'code' => 100001));
        }
        $this->api_result(array('data'=>$data));
    }

    /**
     * 提交订单
     */
    public function done(){
        $_POST['num'] = isset($_POST['num']) ? intval($_POST['num']) : 1;//数量
        $pay_id = isset($_POST['pay_id']) ? intval($_POST['pay_id']) : false;
        $best_time = isset($_POST['best_time']) ? stripcslashes($_POST['best_time']) : '';//配送时间
        $order_tip = isset($_POST['order_tip']) ? stripcslashes($_POST['order_tip']) : '';//买家留言

        if (!$pay_id)
            $this->api_result(array('flag' => false, 'msg' => '请选择支付方式！', 'code' => 100002));
        if (!checkToken() && empty($_POST['client']))
            $this->api_result(array('flag' => false, 'msg' => '请勿重复提交', 'code' => 100002));

        #检查商品是否满足条件,是否为团购，人数是否已满等
        $cart = $this->goods->check_goods($_SESSION['cart'],$_POST);
        if (!$cart) {
            $msg = $this->goods->getError();
            $this->api_result(array('flag' => false, 'msg' => $msg, 'code' => 100001));
        }

        #判断是否已参团
        if($_SESSION['cart']['common_id']>0){
            $is_res = $this->db->getstr("select 1 from ###_goods_order where common_id={$_SESSION['cart']['common_id']} and status_pay>0 and mid=".MID);
            if($is_res)
                $this->api_result(array('flag' => false, 'msg' => '您已参过该团', 'code' => 100001));
        }

        #判断是否自提，AA团直接试用团长的地址
        if(isset($_POST['take_address_id']) && $_POST['take_address_id']>0){
            $address_id = 0;
            $take_address_id = intval($_POST['take_address_id']);
            if (!$take_address_id) {
                $this->api_result(array('flag' => false, 'msg' => '请选择收货地址！', 'code' => 100001));
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
                $this->api_result(array('flag' => false, 'msg' => '请选择收货地址！', 'code' => 100001));
            }
            $this->load->model('member');
            if($cart['type']==CART_AA){//AA团直接试用团长的地址
                $address_info = $this->member->get_address("id=".$address_id);
            }else{
                $address_info = $this->member->get_address("mid='" . MID . "' AND id='" . $address_id . "'");
            }
        }
        if (!$address_info) {
            $this->api_result(array('flag' => false, 'msg' => '请添加一个收货地址！', 'code' => 100001));
        }

        $order = array(
            'mid' => MID,
            'c_time' => RUN_TIME,
        );

        //购物车
        $order_info = array();
        $cart_goods = array($cart);
        $order['extension_code'] = $cart['typeid'];
        $order['extension_id'] = $cart['goods_id'];
        $order['send_couponid'] = $cart['coupon_id'];
        #收货地址
        $order['zone'] = $address_info['zone'];
        $order['area'] = $address_info['area'];
        $order['address'] = $address_info['address'];
        $order['mobile'] = $address_info['mobile'];
        $order['name'] = $address_info['name'];
        $order['address_id'] = $address_id;
        $order['take_address_id'] = $take_address_id;
        #优惠券id
        $order['coupon_id'] = isset($_POST['coupon_id']) ? intval($_POST['coupon_id']) : 0;
        $order['coupon_id_sid'] = isset($_POST['coupon_id_sid']) ? intval($_POST['coupon_id_sid']) : 0;
        if($cart['typeid']==CART_EXCHANGE) $_POST['exchange_pay'] = 1;

        $total = $this->flow->total($_POST, $cart_goods);
        if(!$total){
            $msg = $this->flow->getError();
            $this->api_result(array('flag' => false, 'msg' => $msg, 'code' => 100002));
        }

        $order['order_amount'] = isset($total['flowOrderTotal']) ? $total['flowOrderTotal'] : 0;// 订单总价
        $order['goods_amount'] = isset($total['flowGoodsTotal']) ? $total['flowGoodsTotal'] : 0; // 商品总价
        $order['cost_amount'] = isset($total['flowCostTotal']) ? $total['flowCostTotal'] : 0;// 商品成本价 Feng 2016-06-02
        $order['shipping_fee'] = isset($total['shipping_fee']) ? $total['shipping_fee'] : 0;// 运费
        $order['amount'] = isset($total['flowTotal']) ? $total['flowTotal'] : 0;// 未支付金额 扣除
        $order['surplus'] = isset($total['flow_user_money_fee']) ? $total['flow_user_money_fee'] : 0;// 余额
        $order['is_cod'] = isset($total['is_cod']) ? intval($total['is_cod']) : 0;// 货到付款
        $order['score'] = isset($total['flow_user_score_fee']) ? $total['flow_user_score_fee'] : 0;//积分支付

        #获取支付方式
        if ($pay_id) {
            $payment = $this->payment->payment_info($pay_id);
            $order['pay_id'] = $pay_id;
            $order['pay_name'] = addslashes($payment['pay_name']);
        }
        //阶梯团定金
        if($cart['type']==CART_STEP){
            $order['pre_amount'] = $cart['deposit']*$cart['qty'];
            $order['amount'] = $total['flowTotal'] = $order['pre_amount'];
        }

        //应付金额为0时，更新付款状态
        if ($total['flowTotal'] <= 0) {
            if($cart['type']==CART_STEP){
                $order['status_pay'] = 1;
            }else{
                $order['status_pay'] = 10;
            }
        }

        $order['best_time'] = $best_time;
        $order['order_tip'] = $order_tip;
        $order['order_sn'] = $order_sn = $this->flow->order_sn();
        $order['common_id'] = $cart['common_id'];
        $order['sid'] = $cart['sid'];

        $order_id = $order['order_id'] = $this->db->save('goods_order', $order);
        if (!$order_id) {
            $this->api_result(array('flag' => false, 'msg' => '订单添加失败!', 'code' => 100001));
        }
        //如果是积分兑换，就需要扣除积分
        if($order['score']>0&&$order['extension_code']==CART_EXCHANGE){
            $this->load->model('exchange');
            if($this->exchange->power){
                $this->exchange->action(array('mid'=>MID,'order_sn'=>$order_sn,'score'=>$order['score'],'type'=>"0"));
            }
        }

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
                'type' =>$v['type'],
                'team_num' => $v['team_num'],
                'express_id' => $_POST['express_id'],
            );
            $this->db->save('goods_order_item', $data_goods);
        }

        // 模版消息 1 下单成功 {插入昵称},{插入订单号}
        // template_msg_action start
        $this->load->model('template_msg');
        $msgParams = array(getUsername(MID), $order_sn);
        $this->template_msg->inQueue(1, 0, $msgParams);
        // template_msg_action end

        $data['data']['common_id'] = null;

        //更新砍价记录下单id
        if(isset($_SESSION['cart']['log_id']) && $_SESSION['cart']['log_id']>0){
            $this->db->update("bargain_log",array("order_id"=>$order_id),array("id"=>$_SESSION['cart']['log_id']));
        }

        //print_r($total);print_r($_POST);exit;
        //支付金额为0
        if ($total['flowTotal'] <= 0) {
            //余额红包积分等处理
            $order = $this->db->get("SELECT * FROM ###_goods_order WHERE id=" . $order_id);
            $this->order->moneyOrder($order);
            $this->db->update("goods_order", array('pay_time'=>RUN_TIME), array("id"=>$order_id));
            //拼团处理
            $common_id = $this->order->order_common($order);
            $data['msg'] = '订单支付成功!';
            $data['data']['order_sn'] = $order_sn;
            if($_SESSION['cart']['type'] > 0){
                $data['data']['common_id'] = $common_id;
            }
            $data['data']['payment'] = null;

            $this->api_result($data);
        }else{
            $data['msg'] = '生成订单成功，请前往支付!';
            $data['data']['order_sn'] = $order_sn;

            //测试用
            $_GET['order_sn'] = $order_sn;
            $this->pay();

            $this->api_result($data);
        }
    }

    /**
     * 订单支付
     */
    public function pay(){
        session_destroy();
        $order_sn = isset($_GET['order_sn']) ? trim($_GET['order_sn']) : '';

        if(empty($order_sn)){
            $this->api_result(array('flag' => false, 'msg' => '参数异常，订单号不能为空', 'code' => 100001));
        }
        #订单信息
        $order_info = $this->db->get("SELECT * FROM ###_goods_order WHERE mid='" . MID . "' AND order_sn='" . $order_sn . "'");
        if (empty($order_info)) {
            $this->api_result(array('flag' => false, 'msg' => '该订单不存在', 'code' => 100001));
        }
        #获取app选择的支付方式 防止微信下单再app里面支付不了
        $order_info['pay_id'] = !empty($_GET['pay_id']) ? trim($_GET['pay_id']) :$order_info['pay_id'];

        #判断订单状态
        if($order_info['status_pay'] == 10){
            $this->api_result(array('flag' => false, 'msg' => '订单已支付', 'code' => 100001));
        }
        #判断是否已参团
        if($order_info['common_id']>0 && $order_info['status_pay']==0){
            $is_res = $this->db->getstr("select 1 from ###_goods_order where common_id={$order_info['common_id']} and status_pay>0 and mid=".MID);
            if($is_res)
                $this->api_result(array('flag' => false, 'msg' => '您已参过该团', 'code' => 100001));
        }
        #判断优惠券是否已被使用
        if($order_info['coupon_id']>0){
            $r = $this->db->get("select 1 from ###_coupon_log where id={$order_info['coupon_id']} and status=2");
            if($r)
                $this->api_result(array('flag' => false, 'msg' => '该订单优惠券已被使用', 'code' => 100001));
        }
        #判断店铺优惠券是否已被使用
        if($order_info['coupon_id_sid']>0){
            $r = $this->db->get("select 1 from ###_coupon_log where id={$order_info['coupon_id_sid']} and status=2");
            if($r)
                $this->api_result(array('flag' => false, 'msg' => '该订单店铺优惠券已被使用', 'code' => 100001));
        }

        #判断库存
        $row = $this->db->get("select g.stock,g.is_sale from ###_goods_order_item as o left join ###_goods as g on o.good_id=g.id where o.order_id=".$order_info['id']);
        if($row['stock']<=0){
            $this->api_result(array('flag' => false, 'msg' => '该商品已抢光', 'code' => 100001));
        }elseif($row['is_sale']==0){
            $this->api_result(array('flag' => false, 'msg' => '该商品已下架', 'code' => 100001));
        }
        #判断支付状态
        if (!$this->order->order_status(CS_AWAIT_PAY, $order_info)) {
            $this->api_result(array('flag' => false, 'msg' => '该订单不可再支付', 'code' => 100001));
        }
        #判断支付费用
        if ($order_info['amount'] <= 0) {
            $this->api_result(array('msg'=>'订单已经支付完成'));
        }

        $payment_info = $this->payment->payment_info($order_info['pay_id']);
        if(empty($payment_info)) {
            $this->api_result(array('msg'=>'支付方式未启用'));
        }
        $order = array();
        $order['order_sn'] = $order_info['order_sn'];

        #计算支付手续费用
        $payment_info['pay_fee'] = $this->payment->pay_fee($order_info['pay_id'], $order_info['amount']);
        $order['order_amount'] = $order_info['amount'] + $payment_info['pay_fee'];
        #判断是否已经存在支付记录
        if($order_info['extension_code']==CART_STEP ){
            $order_type = $order_info['status_pay']==1?PAY_END:PAY_PRE;
        }else{
            $order_type = PAY_ORDER;
        }
        $log_info = $this->db->get("SELECT * FROM ###_pay_log WHERE order_id = '{$order_info['id']}' and order_type='{$order_type}'");
        if($log_info == false){
            $order['log_id'] = $this->payment->pay_log_save(array('order_id' => $order_info['id'], 'order_amount' => $order_info['amount'], 'order_type' => $order_type, 'is_paid' => PS_UNPAYED));
        }else{
            $order['log_id'] = $log_info['log_id'];
        }
        $order['out_trade_no'] = date("YmdHis").'_'.$order['log_id'];
        $this->payment->pay_log_save(array('log_id'=>$order['log_id'],'out_trade_no'=>$order['out_trade_no']));


        #取得支付信息，生成支付代码
        $payment = unserialize_config($payment_info['pay_config']);
        #调用相应的支付方式文件
        include_once AppDir . 'includes/modules/payment/' . $payment_info['pay_code'] . '.php';
        $pay_obj = new $payment_info['pay_code'];
        $order['payment'] = $pay_obj->get_code($order, $payment);
        $this->api_result(array('data'=>$order));
    }
    //获取团id
    function getcommonid(){
        $order_sn = $_REQUEST['order_sn'];
        $order = $this->db->get("select id,common_id,pay_id from ###_goods_order where order_sn='{$order_sn}' and mid=".MID);
        $data['common_id'] = $order['common_id'];
        //支付成功修改支付方式
        $pay_id = isset($_GET['pay_id']) ? trim($_GET['pay_id']) :'';
        if($pay_id>0 && $pay_id!=$order['pay_id']){
            $pay_name = $this->db->getstr("select pay_name from ###_payment where pay_id={$pay_id}","pay_name");
            $this->db->update("goods_order",array("pay_id"=>$pay_id,"pay_name"=>$pay_name),array("id"=>$order['id']));
        }

        $this->api_result(array('data'=>$data));
    }

    //获取支付方式
    function getpayment(){
        #支付方式
        $payment = $this->payment->getPayment("enabled = '1' AND (pay_code = 'alipayapp' or pay_code = 'wxpayapp')");
        foreach($payment as $k=>$v){
            $data['payment'][$k]['pay_id'] = (int)$v['pay_id'];
            $data['payment'][$k]['pay_name'] = $v['pay_name'];
            $data['payment'][$k]['pay_code'] = $v['pay_code'];
            if(empty($v['thumb'])){
                $data['payment'][$k]['thumb'] = null;
            }else{
                $thumb = json_decode($v['thumb']);
                $data['payment'][$k]['thumb'] = yunurl($thumb[0]->path);
            }

        }
        $this->api_result(array('data'=>$data));
    }

}