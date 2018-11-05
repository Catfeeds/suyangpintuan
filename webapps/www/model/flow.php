<?php

/**
 * Class flow_model
 */
class flow_model extends Lowxp_Model {

    public $baseTable = '###_cart';

    function __construct() {
        $this->load->model('goods');
    }

    //购物车商品列表
    function cart_goods($ids = '', $type = '', $is_selected = '') {
        $list = array();

        if (!empty($_SESSION['mid'])) {
            $where = '';
            if (!empty($ids)) {
                $where = " AND id IN (" . implode(',', $ids) . ")";
            }

            if ($type) {
                $where .= " AND type = '$type'";
            }

            if ($is_selected) {
                $where .= " AND is_selected = '$is_selected'";
            }

            $list = $this->db->select("SELECT * FROM ###_cart WHERE mid = '" . $_SESSION['mid'] . "' $where ORDER BY id DESC");
        } else {
            $cart_str = cookie('goods_cart');
            if (get_magic_quotes_gpc()) {
                $cart_str = stripslashes($cart_str);
            }
//去除斜杠
            $list = unserialize($cart_str);
            $list = is_array($list) ? $list : array();
        }

        if ($list) {
            $this->load->model("goods");
            $list = $this->db->lJoin($list, 'goods', 'id,cid,thumb,stock,is_sale,weight,weight_unit,stock,sp_val,price', 'goods_id', 'id', 'goods_');
            foreach ($list as $k => $v) {

                //获取产品规格 和 最新价格 Feng 2016-07-26 start
                if ($v['spec']) {
                    $v['goods_spec'] = $this->goods->getSpec($v['goods_sp_val'], $v['spec']);
                    $item = $this->db->get("select price,thumb from ###_goods_item where goods_id={$v['goods_id']} and spec='{$v['spec']}'");
                    $v['goods_price'] = $item['price'];
                    $v['subtotal'] = $v['qty'] * $v['goods_price'];
                    $v['goods_thumb'] = !empty($item['thumb']) ? $item['thumb'] : $v['goods_thumb'];
                } else {
                    $v['subtotal'] = $v['qty'] * $v['goods_price'];
                }
                //获取产品规格 和 最新价格 Feng 2016-07-26 end
                //清除不正常的商品商品
                if ($v['qty'] <= 0 || $v['goods_stock'] <= 0 || $v['goods_is_sale'] == 0) {
                    if ($_SESSION['mid']) {
                        $this->db->delete("cart", array('id' => $v['id'], 'mid' => $_SESSION['mid']));
                    }
                    unset($list[$k]);
                    continue;
                }

                $v['thumb'] = $v['goods_thumb'];
                unset($v['goods_thumb']);
                $v = $this->goods->getThumb($v);

                $v['url'] = '/goods/show/' . $v['goods_id'];
                if (!$_SESSION['mid']) {
                    $v['id'] = $k + 1;
                }
                $list[$k] = $v;
            }
        }
        //保存购物车数量到cookie Feng 2016-05-30
        $_SESSION['cartNum'] = count($list);

        return $list;
    }

    /**
     * 根据订单号获取 处理好以后的  结果跟购物车相同的商品明细信息数组
     * @param  integer $orderId   订单id
     * @param  boolean $getThumb  是否获取商品缩略图
     * @return array   			  跟购物车相同的商品明细信息数组
     */
    public function getOrderGoodList($orderId, $getThumb = false) {
        $list = $this->db->select('SELECT *,good_id as goods_id, buy_num as qty ,buy_num*sell_price as subtotal,goods_spec FROM ###_goods_order_item WHERE order_id = ' . $orderId);
        if (!$list) {
            return false;
        }

        $list = $this->db->lJoin($list, 'goods', 'id,cid,thumb,stock,is_sale,weight,weight_unit,price', 'goods_id', 'id', 'goods_');

        foreach ($list as $k => $v) {

            //获取产品规格 和 最新价格 Feng 2016-07-26 start
            if ($v['spec']) {
                $item = $this->db->get("select price,thumb from ###_goods_item where goods_id={$v['goods_id']} and spec='{$v['spec']}'");
                $v['goods_price'] = $item['price'];
                $v['goods_thumb'] = !empty($item['thumb']) ? $item['thumb'] : $v['goods_thumb'];
                $v['subtotal'] = $v['sell_price'] != $v['goods_price'] ? $v['qty'] * $v['goods_price'] : $v['subtotal'];
            } elseif ($v['extension_id'] == 0) {//除了团购，秒杀活动外
                $v['subtotal'] = $v['sell_price'] != $v['goods_price'] ? $v['qty'] * $v['goods_price'] : $v['subtotal'];
            }
            //获取产品规格 和 最新价格 Feng 2016-07-26 end
            //清除不正常的商品
            if ($v['qty'] <= 0 || $v['goods_stock'] <= 0 || $v['goods_is_sale'] == 0) {
                return false; //未付款订单 返回空 提示重新下单
                //unset($list[$k]);
                //continue;
            }

            if ($getThumb) {
                $v['thumb'] = $v['goods_thumb'];
                unset($v['goods_thumb']);

                $v = $this->goods->getThumb($v);
                $v['url'] = url('/goods/show/' . $v['goods_id']);
            }

            $list[$k] = $v;
        }//echo "<pre>";print_r($list);exit;
        return $list;
    }

    //直购购物车商品列表
    function cart_goods_auc() {
        $list = array();
        if ($_SESSION['mid'] && $_SESSION['cart_type']) {
            $cart_str = cookie('goods_cart_' . $_SESSION['cart_type']);
            if (get_magic_quotes_gpc()) { //去除斜杠
                $cart_str = stripslashes($cart_str);
            }
            $cart_str = unserialize(base64_decode($cart_str));
            $list[0] = is_array($cart_str) ? $cart_str : array();
        }
        if ($list[0]) {
            // 无$list[0] 则无直购
            $list = $this->db->lJoin($list, 'goods', 'id,cid,thumb', 'goods_id', 'id', 'goods_');
            foreach ($list as $k => $v) {
                $v['thumb'] = $v['goods_thumb'];
                unset($v['goods_thumb']);
                $v = $this->goods->getThumb($v);
                $v['url'] = 'javascript:;';
                $v['id'] = $k + 1;
                $list[$k] = $v;
                //清除不正常的商品商品,排除非当前会员商品
                if (empty($v['qty']) || $v['qty'] <= 0 || $v['mid'] != $_SESSION['mid']) {
                    unset($list[$k]);
                }
            }
        }
        return $list;
    }

    //重新获取购物车数量
    function cart_num($cart_goods) {
        $cart_num = 0;
        foreach ($cart_goods as $k => $v) {
            if ($v['qty'] > 0) {
                $cart_num++;
            }
        }
        return $cart_num;
    }

    //重新计算购物车竞价
    function cart_total_cart($cart_goods) {
        $total = 0;
        //套餐价格
        foreach ($cart_goods as $k => $v) {
            if ($v['qty'] > 0) {
                $total += $v['subtotal'];
            }
        }
        return formatPrice($total);
    }

    //重新计算购物车商品成本价 Feng 2016-06-02
    function cart_total_cost($cart_goods) {
        $total = 0;
        foreach ($cart_goods as $k => $v) {
            if ($v['qty'] > 0) {
                $total += $v['qty'] * $v['cost_price'];
            }
        }
        return formatPrice($total);
    }

    //购物车总价
    function cart_total($ids = '', $type = '') {
        $total = 0;
        $list = $this->cart_goods($ids, $type);
        foreach ($list as $v) {
            $total += $v['subtotal'];
        }
        return formatPrice($total);
    }

    //清空购物车
    function cart_empty($ids = '', $is_selected = 0) {
        $where = 'mid=' . $_SESSION['mid'];
        if (is_array($ids)) {
            $ids = implode(',', $ids);
        }
        if ($ids) {
            $where .= " AND id IN($ids)";
        }
        if ($is_selected) {
            $where .= " AND is_selected = $is_selected";
        }
        if ($_SESSION['cart_type'] > 0) {
            zzcookie('goods_cart_' . $_SESSION['cart_type'], '');
        } else {
            $this->db->delete("cart", $where);
        }
        //更改购物车数量 feng 2016-06-02
        $this->cart_goods();
    }

    //cookie购物车合并到数据库
    function cart_merge() {
        if (!$_SESSION['mid']) {
            return;
        }

        //数据库购物车
        $cart_goods = $this->cart_goods();

        //cookie购物车
        $mid = $_SESSION['mid'];
        $_SESSION['mid'] = 0;
        $cart_goods_cookie = $this->cart_goods();
        $_SESSION['mid'] = $mid;

        //合并
        if (empty($cart_goods_cookie)) {
            return;
        }
        foreach ($cart_goods_cookie as $k => $v) {
            unset($v['id']);
            $is_new = true;
            $cart_qty = 0;
            $cart_price = 0;
            $id = 0;
            foreach ($cart_goods as $ko => $vo) {
                if ($vo['goods_id'] == $v['goods_id'] && $vo['type'] == $v['type'] && $vo['obj_id'] == $v['obj_id']) {
                    $is_new = false;
                    $cart_qty = $vo['qty'];
                    $cart_price = $vo['goods_price'];
                    $id = $vo['id'];
                    break;
                }
            }
            if ($is_new) {
                $v['mid'] = $_SESSION['mid'];
                $this->db->save('cart', $v);
            } else {
                $update = array(
                    'qty' => $cart_qty + $v['qty'],
                    'subtotal' => $cart_price * ($cart_qty + $v['qty']),
                );
                $where = array(
                    'id' => $id,
                );
                $this->db->save('cart', $update, '', $where);
            }
        }

        zzcookie('goods_cart', '');
    }

    //生成订单号
    function order_sn() {
        //$order_sn = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), -8, 8);
        $order_sn = date('YmdHis').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(md5(microtime(true)),true), 7, 13), 1))), 0, 8);
        return $order_sn;
    }

    /**
     * 获取配送方式
     * @param  integer  $address  区域id
     * @param  mixed    $goodList 购物车订单列表 用来根据商品重量获取运费.  或者直接传入计算后的商品重量
     * @return array
     */
    public function getFright($goodList, $zoneId = 0) {

        $this->load->model('linkage');
        $this->load->model('member');
        $cart_type = isset($_SESSION['cart_type']) ? $_SESSION['cart_type'] : 0;

        $weight = is_array($goodList) ? $this->getWeight($goodList) : $goodList; // 传入的是数组就计算重量. 传入的不是数组就直接是计算好以后的重量

        $list = $this->db->select("SELECT * FROM ###_express WHERE status=1 ORDER BY listorder ASC,id ASC");

        if (!$zoneId) {
            // notice 这里是获取用户的第一个地址 用来确定省内省外
            // 后期应该还是考虑通过综合方式获取用户位置信息
            $addresses = $this->member->member_address(MID, 1);
            if ($addresses) {
                $addresses = array_slice($addresses, 0, 1);
                $zoneId = $addresses[0]['zone'];
            }
        }

        // 获取省份id
        $arr = $this->linkage->parentArr($zoneId);
        $prov_sl = C('prov_sl');
        $prov_sl = empty($prov_sl) ? PROV_SL : $prov_sl;
        $array = array();
        foreach ($list as $k => $v) {
            $v['title'] = $v['name'];
            if ($k == 0) {
                $v['is_default'] = 1;
            }
            //免运费
            if ($v['free'] || $cart_type == CART_PACK) {
                $v['freight_free'] = formatPrice('0');
                $v['shipping_fee'] = formatPrice('0');
            } else {
                //计算运费
                //免运费额度
                // todo 根据重量计算运费
                //省内省外
                if (in_array($prov_sl, $arr)) {
                    $price_1 = $v['price_sl_1'];
                    $price_2 = $v['price_sl_2'];
                } else {
                    $price_1 = $v['price_sw_1'];
                    $price_2 = $v['price_sw_2'];
                }

                if ($weight <= 0) {
                    // 商品无重量运费为0
                    $v['shipping_fee'] = 0;
                } elseif ($weight <= $v['weight']) {
                    // 商品重量小于首重则运费为首重运费
                    $v['shipping_fee'] = $price_1;
                } else {
                    // 商品商品重量大于首重则为 首重费用 + (商品重量 减去 首重) 取整 乘 续重
                    $v['shipping_fee'] = ceil($weight - $v['weight']) * $price_2 + $price_1;
                }

                $v['freight_free'] = formatPrice($this->site_config['freight_free']);
                $v['shipping_fee'] = formatPrice($v['shipping_fee']);
            }
            $array[$v['id']] = $v;
        }
        return $array;
    }

    /**
     * 计算购物车商品重量
     */
    function getWeight($goodList) {
        // $cart_type = isset($_SESSION['cart_type']) ? $_SESSION['cart_type'] : 0;
        $weight = 0;
        // $goods_ids = '';
        // $cart_goods = array();
        // if ($cart_type > 0) {
        // 	$cart_goods = $this->cart_goods_auc();
        // } else {
        // $cart_goods = $this->cart_goods();
        // }

        foreach ($goodList as $v) {
            if (!isset($v['goods_id'])) {
                continue;
            }

            $sql = "SELECT weight,weight_unit FROM ###_goods WHERE id='" . $v['goods_id'] . "'";
            $goods = $this->db->get($sql);
            if ($goods['weight'] <= 0) {
                continue;
            }

            //将克转为千克
            if (!in_array(strtoupper($goods['weight_unit']), array('KG', 'G'))) {
                $goods['weight'] = 0;
            } elseif (strtoupper($goods['weight_unit']) == 'G') {
                $goods['weight'] = $goods['weight'] / 1000;
            }
            if ($goods['weight'] > 0) {
                $weight += floatval($goods['weight']) * $v['qty'];
            }
        }
        return $weight;
    }

    /**
     * 计算购物车商品重量
     */
    function getWeight1() {
        $cart_type = isset($_SESSION['cart_type']) ? $_SESSION['cart_type'] : 0;
        $weight = 0;
        $goods_ids = '';

        $cart_goods = array();
        // if ($cart_type > 0) {
        // 	$cart_goods = $this->cart_goods_auc();
        // } else {
        $cart_goods = $this->cart_goods();
        // }

        foreach ($cart_goods as $v) {
            if (!isset($v['goods_id'])) {
                continue;
            }

            $sql = "SELECT weight,weight_unit FROM ###_goods WHERE id='" . $v['goods_id'] . "'";
            $goods = $this->db->get($sql);
            if ($goods['weight'] <= 0) {
                continue;
            }

            //将克转为千克
            if (!in_array(strtoupper($goods['weight_unit']), array('KG', 'G'))) {
                $goods['weight'] = 0;
            } elseif (strtoupper($goods['weight_unit']) == 'G') {
                $goods['weight'] = $goods['weight'] / 1000;
            }
            if ($goods['weight'] > 0) {
                $weight += floatval($goods['weight']) * $v['qty'];
            }
        }
        return $weight;
    }

    /**
     * 根据订单信息和(购物车或订单信息)计算各项费用
     * @param $order
     * @param $cart_goods
     * @param array $order_info
     * @return mixed
     */

    function total($order, $cart_goods, $order_info = array()) {
        $data = array();
        $order_price = 0; #订单总金额
        $total = 0; #第三方需支付金额
        //商品金额
        if (empty($order_info)) {
            $order_price = $data['flowGoodsTotal'] = $this->cart_total_cart($cart_goods);
        } else {
            $order_price = $data['flowGoodsTotal'] = $order_info['goods_amount'];
        }
        //阶梯团定金 阶梯团暂不试用优惠券
        /*if($cart_goods['type']==CART_STEP){
            $order['pre_amount'] = $cart_goods['deposit']*$cart_goods['qty'];
            $order_price = $order['pre_amount'];
        }*/
        //商品成本  Feng 2016-06-02
        if (empty($order_info)) {
            $data['flowCostTotal'] = $this->cart_total_cost($cart_goods);
        } else {
            $data['flowCostTotal'] = $order_info['cost_amount'];
        }

        //计算积分
        // if ($order['extension_code'] == CART_CRDT) {
        // 	foreach ($cart_goods as $k => $v) {
        // 		$data['points_fee'] += $v['subtotal_points'];
        // 	}
        // }
        // 优惠券id
        $order['coupon_id'] = isset($order['coupon_id']) ? intval($order['coupon_id']) : 0;
        if ($order['coupon_id']) {
            $this->load->model('coupon');
            $coupon = $this->coupon->getFullCouponLog($order['coupon_id']);
            // 优惠券可用性检查
            if (!$this->coupon->checkUsable($coupon, $cart_goods)) {
                $this->error = $this->coupon->getError();
                return false;
            }
            $data['coupon'] = $coupon['amount'];

            // 计算优惠后的订单金额
            $order_price = $order_price > $coupon['amount'] ? $order_price - $coupon['amount'] : 0;
        }
        //店铺优惠券
        $order['coupon_id_sid'] = isset($order['coupon_id_sid']) ? intval($order['coupon_id_sid']) : 0;
        if ($order['coupon_id_sid']) {
            $this->load->model('coupon');
            $coupon = $this->coupon->getFullCouponLog($order['coupon_id_sid']);
            // 优惠券可用性检查
            if (!$this->coupon->checkUsable($coupon, $cart_goods)) {
                $this->error = $this->coupon->getError();
                return false;
            }
            $data['coupon_store'] = $coupon['amount'];

            // 计算优惠后的订单金额
            $order_price = $order_price > $coupon['amount'] ? $order_price - $coupon['amount'] : 0;
        }

        //配送费用
        if((!isset($order['take_address_id']) || empty($order['take_address_id'])) && isset($order['address_id'])){
            if(!$order['express_id'] && $cart_goods[0]['express'] && ( $cart_goods[0]['typeid']!=CART_AA || $cart_goods[0]['typeid']==CART_AA && $cart_goods[0]['common_id']==0)){
                $this->error = '请选择运送方式！';
                return false;
            }
            if($order['express_id'] && $cart_goods[0]['express'] && ($cart_goods[0]['typeid']!=CART_AA || $cart_goods[0]['typeid']==CART_AA && $cart_goods[0]['common_id']==0)){
                $express_id = $order['express_id'];
                $address_id = $order['address_id'];
                $result = $this->calculateShippingCosts($express_id, $address_id);
                if($result['error'] == 0){
                    $this->error = $result['msg'];
                    return false;
                }else{
                    $data['shipping_fee'] = $result['price'];
                    $order_price = $order_price + $result['price'];
                }
            }
        }

        $total = $order_price;

        //支付方式
        /*$is_cod = false; #是否为货到付款
        if (isset($order['pay_id'])) {
            $pay_id = (int) $order['pay_id'];
            $this->load->model('payment');
            $payment_info = $this->payment->payment_info($pay_id);
            if ($payment_info['is_cod'] == 1) {
                $is_cod = true;
            }
        }
        $data['is_cod'] = $is_cod;*/

        //余额支付 APP审核时需用到余额支付
        if(C('app_checking')){
            $this->load->model('member');
            $member                  = $this->member->member_info($_SESSION['mid']);
            $data['flow_user_money'] = $member['user_money'];
            if (isset($order['balance_pay'])) {
                $balance_pay = (int) $order['balance_pay'];
                if ($balance_pay == 1 && $total > 0 ) {
                    if ($total >= $data['flow_user_money']) {
                        $total -= $data['flow_user_money'];
                        $data['flow_user_money_fee'] = price_format($data['flow_user_money']);
                    } elseif ($total < $data['flow_user_money']) {
                        $data['flow_user_money_fee'] = price_format($total);
                        $total                       = 0;
                    }
                }
            }
        }else{
            $data['flow_user_money_fee'] = '0.00';
        }
		
		//积分兑换
		if(isset($order['exchange_pay'])){
			// 积分
			$this->load->model('score');
			$score = $this->score->getTotal($_SESSION['mid']);
			$rule = $this->score->getRow(6);

			$data['flow_user_score'] = $score['total_left'];
			$exchange_pay = (int)$order['exchange_pay'];
			if($exchange_pay == 1 && $total > 0 && !$id_cod) {
				if($total > $data['flow_user_score']) {
					$this->error = '对不起，您的积分不足！';
        			return false;
					/* $total -= $data['flow_user_score'];
					if($rule['config']['percent']){
						$total = $total*$rule['config']['percent']/100;
					}
					$data['flow_user_score_fee'] = price_format($data['flow_user_score']); */
				}elseif($total <= $data['flow_user_score']) {
					$data['flow_user_score_fee'] = price_format($total);
					$total = 0;
				}
			}
		}

        //红包支付
        // $data['flow_bonus_money_fee'] = '0.00';
        // $this->load->model('bonus');
        // $bonus = $this->bonus->getBonusUser(array(
        // 	'mid' => $_SESSION['mid'],
        // ), 1);
        // $data['flow_bonus_money'] = $bonus['money'];
        // $data['flow_bonus_count'] = $bonus['count'];
        // if (isset($order['bonus_pay'])) {
        // 	$bonus_pay = (int) $order['bonus_pay'];
        // 	if ($bonus_pay == 1 && $total > 0 && !$is_cod) {
        // 		$data['flow_bonus_ids'] = $bonus['ids'];
        // 		if ($total >= $data['flow_bonus_money']) {
        // 			$total -= $data['flow_bonus_money'];
        // 			$data['flow_bonus_money_fee'] = price_format($data['flow_bonus_money']);
        // 		} elseif ($total < $data['flow_bonus_money']) {
        // 			$data['flow_bonus_money_fee'] = price_format($total);
        // 			$total                        = 0;
        // 		}
        // 	}
        // }
        //赠送的积分
        // $data['flowJf'] = $this->getJf($data['flowGoodsTotal']);
        //赠送的红包
        // $bonus                  = $this->bonus->getBonusOrder($order_price);
        // $data['flowBonusMoney'] = price_format($bonus['money']);
        //最终费用
        $data['flowOrderTotal'] = price_format($order_price);
        $data['flowTotal'] = price_format($total);
        return $data;
    }

    /**
     * 计算运费
     * @param $express_id
     * @param $address_id
     * @return mixed
     */
    public function calculateShippingCosts($express_id, $address_id){
        if(empty($_SESSION['cart']) || !isset($_SESSION['cart'])){
            $data['error'] = 0;
            $data['msg'] = "超时";
            return $data;
        }
        $goods_id = $_SESSION['cart']['goods_id'];
        $type = $_SESSION['cart']['type'];
        $common_id = $_SESSION['cart']['common_id'];
        #判断是否是AA团
        if($type == CART_AA && $common_id > 0){
            $goods_order = $this->db->get("select address_id from ###_goods_order where common_id = $common_id");
            if(!$goods_order){
                $data['error'] = 0;
                $data['msg'] = "该团不存在";
                return $data;
            }
            $memberAddress = $this->db->get("select `zone` from ###_member_address where id = $goods_order[address_id]");
        }else{
            $memberAddress = $this->db->get("select `zone` from ###_member_address where id = $address_id and mid = ".MID);
        }
        if(!$memberAddress){
            $data['error'] = 0;
            $data['msg'] = "收货地址不存在";
            return $data;
        }
        #获取商品信息
        $goods = $this->db->get("select unit,weight,weight_unit,sid,is_free_shipping from ###_goods where id = $goods_id");
        if(!$goods){
            $data['error'] = 0;
            $data['msg'] = "商品不存在";
            return $data;
        }
        #获取快递方式
        $this->load->model('express');
        $express = $this->express->getExpressByIdSid($express_id, $goods['sid']);
        if(!$express){
            $data['error'] = 0;
            $data['msg'] = "运送方式不存在";
            return $data;
        }
        #开始计算运费
        if(!$express['free'] && !$goods['is_free_shipping']){
            $this->load->model('linkage');
            $linkage = $this->linkage->get($memberAddress['zone']);
            $arrparentid = explode(',', $linkage['arrparentid']);
            $arrparentid[0] = $memberAddress['zone'];
            foreach($arrparentid as $v){
                $sql = "select * from ###_express_shipping where express_id = $express_id and find_in_set($v, linkage_id)";
                $expressShipping = $this->db->get($sql);
                if($expressShipping){
                    $config = unserialize($expressShipping['config']);
                    #每x件多少千克/克,x > 1时
                    /*if(!empty($goods['unit']) || $goods['unit'] > 1){
                        $goods['weight'] = $goods['weight']/$goods['unit'];
                    }*/
                    #单位转换1g = 1/1000kg
                    if($goods['weight_unit'] == 'g'){
                        $goods['weight'] = $goods['weight']/1000;
                    }
                    $goods['weight'] = $goods['weight'] * $_SESSION['cart']['qty'];
                    $weightMultiple =  round($goods['weight']/$config['cs_weight'],2);
                    if($weightMultiple>= 0 && $weightMultiple <= 1){
                        $price = $config['cs_price'];
                    }elseif($weightMultiple > 1){
                        $price = $config['cs_price'] + ($goods['weight'] - $config['cs_weight']) * $config['xf_weight'];
                    }
                    break;
                }
            }
            $data['error'] = 200;
            $data['express'] = $express['name'];
            $data['price'] = $price;
        }else{
            $data['error'] = 200;
            $data['express'] = $express['name'];
            $data['price'] = 0;
        }
        return $data;
    }

    /** 计算订单金额能兑换多少积分
     * @param $price
     */
    function getJf($price) {
        $pay_points_order = (int) $this->site_config['pay_points_order'];
        $jf = floor($price / $pay_points_order);
        return $jf;
    }

}
