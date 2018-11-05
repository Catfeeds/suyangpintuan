<?php
/**
 * Name 会员管理
 * Class member_adm
 */
class order extends Lowxp {
	function __construct() {
		#按钮


		parent::__construct();

		$this->load->model('order');
	}

	function index($page = 1) {
        $this->btnMenu = array(
            0 => array('url' => '#!order/index', 'name' => '订单管理'),
            1 => array('url' => '#!order/index?lstype=pay', 'name' => '待付款'),
            2 => array('url' => '#!order/index?lstype=shipping', 'name' => '待发货'),
            3 => array('url' => '#!order/index?lstype=refund', 'name' => '退款'),
        );
        $this->smarty->assign('btnMenu', $this->btnMenu);

		$conds = $this->getFilterCond();

		$condition = 'WHERE go.is_robots=0 AND go.sid=0 AND (go.status_common=0 || (go.status_common=10 && go.extension_code!=5 && go.extension_code!=6) || ( (go.extension_code=5 || go.extension_code=6) && go.status_lottery=10) ) ';

		if($_GET['lstype']=='shipping'){//待发货
		    $condition.=" AND go.status_pay=10 and go.status_order=0 and go.status_shipping in (0,1) ";
            $this->smarty->assign('btnNo', 2);
        }elseif($_GET['lstype']=='pay'){//待付款
            $condition.=" AND go.status_pay in (0,1) and go.status_order=0 ";
            $this->smarty->assign('btnNo', 1);
        }elseif($_GET['lstype']=='refund'){//退款
            $condition =" WHERE is_robots=0 AND sid=0 and go.status_order in (2,3,4) ";
            $this->smarty->assign('btnNo', 3);
        }else{
            $condition.=" AND go.status_pay=10 ";
            $this->smarty->assign('btnNo', 0);
        }

		$condition .= count($conds['where']) ? " AND " . implode(' AND ', $conds['where']) : '';

		$orderby = $conds['order'];

		$excel = (isset($_GET['excel']) && $_GET['excel'] == 1) ? 1 : 0;

		$this->load->model('page');

		$_GET['page'] = $page;

		//$sql = "SELECT DISTINCT go.id,go.*,goi.type,goi.verify_code_id FROM ###_goods_order AS go ";
		$sql = "SELECT DISTINCT go.id,go.* FROM ###_goods_order AS go ";
		if (strpos($condition, 'goi.') !== false) {
			$sql .= "LEFT JOIN ###_goods_order_item AS goi ON goi.order_id=go.id ";
		} elseif (strpos($condition, 'g.') !== false) {
			$sql .= "LEFT JOIN ###_goods_order_item AS goi ON goi.order_id=go.id ";
			$sql .= "LEFT JOIN ###_goods AS g ON g.id=goi.good_id ";
		} elseif (strpos($condition, 'tvc.') !== false){
			$sql .= "LEFT JOIN ###_goods_order_item AS goi ON goi.order_id=go.id ";
			$sql .= "LEFT JOIN ###_take_verify_code AS tvc ON go.id=tvc.order_id ";
		} else {
			//$sql .= "LEFT JOIN ###_goods_order_item AS goi ON goi.order_id=go.id ";
		}

		if (isset($_REQUEST['total'])) {
			#订单支付
			$condition2 = '';
			if (strpos($condition, 'goi.') !== false) {
				$condition2 .= "LEFT JOIN ###_goods_order_item AS goi ON goi.order_id=go.id " . $condition;
			} elseif (strpos($condition, 'g.') !== false) {
				$condition2 .= "LEFT JOIN ###_goods_order_item AS goi ON goi.order_id=go.id ";
				$condition2 .= "LEFT JOIN ###_goods AS g ON g.id=goi.good_id " . $condition;
			} elseif (strpos($condition, 'tvc.') !== false){
				$condition2 .= "LEFT JOIN ###_take_verify_code AS tvc ON go.id=tvc.order_id $condition";
			} else {
				$condition2 = $condition;
			}
			$str = "SELECT SUM(go.cost_amount) AS cost_amount, SUM(go.order_amount) AS order_amount,SUM(go.money_paid) AS money_paid FROM ###_goods_order AS go " . $condition2;

			$row = $this->db->get($str);

			$data['order_price'] = $row['order_amount'] - $row['money_paid'];

			$data['money_paid'] = $row['money_paid'];
			if($_GET['is_balance']){
				$data['cost_amount'] = $row['cost_amount'];
			
				$data['order_amount'] = $row['order_amount'];
			}
			$this->smarty->assign('data', $data);
		}

		$sql .= $condition . $orderby;

		$list = array();
		if ($excel == 1) {
			$list = $this->db->select($sql);
		} else {
			$list = $this->page->hashQuery($sql)->result_array();
		}
		$this->load->model('share');
		$list = $this->order->unionOrderGoods($list);
		foreach ($list as $k => $v) {
			if (!isset($list[$k]['goods'])) {
				$list[$k]['goods'] = array();
			}
			if($v['common_id'] > 0 && $v['extension_code'] == CART_AA){
				$order_common = $this->db->get("SELECT mid FROM ###_goods_order_common WHERE id=$v[common_id]");
				$list[$k]['tuanzhang'] = $order_common['mid'];
			}else{
				$list[$k]['tuanzhang'] = 0;
			}
		}

		//订单类型
		//判断积分兑换是否开启
		$this->load->model('exchange');
		if($this->exchange->power){
			$actTypes = array_merge($this->order->actTypes,$this->order->actTypes_exchange);
		}else{
			$actTypes = $this->order->actTypes;
		}
		
		$this->smarty->assign('orderType', $actTypes);

		//付款状态
		$payStatus = array();

		$array = $this->order->payStatus;
		foreach ($array as $k => $v) {
			if ($k == 0) {
				$payStatus[99] = $v;
				unset($payStatus[0]);
			} else {
				$payStatus[$k] = $v;
			}
		}
		$this->smarty->assign('payStatus', $payStatus);

		//发货状态
		$shippingStatus = $this->order->shippingStatus;
		foreach ($shippingStatus as $k => $v) {
			if ($k == 0) {
				$shippingStatus[99] = $v;
				unset($shippingStatus[0]);
			}
		}
		$this->smarty->assign('shippingStatus', $shippingStatus);

		//订单状态
		$orderStatus = $this->order->orderStatus;
		foreach ($orderStatus as $k => $v) {
			if ($k == 0) {
				$orderStatus[99] = $v;
				unset($orderStatus[0]);
			}
		}
		$this->smarty->assign('orderStatus', $orderStatus);
		
		//财务结算状态
		$balanceStatus = $this->order->balanceStatus;
		foreach ($balanceStatus as $k => $v) {
			if ($k == 0) {
				$balanceStatus[99] = $v;
				unset($balanceStatus[0]);
			}
		}
		$this->smarty->assign('balanceStatus', $balanceStatus);
		//导出
		if ($excel == 1) {
			return $list;
		}

		$this->smarty->assign('list', $list);


		$this->smarty->display('manage/order/list.html');
	}

	/**
	 * 订单详情
	 * @param string $id
	 */
	function detail($id = '') {
		is_numeric($id) || die;

		$order = $this->db->get("SELECT * FROM `###_goods_order` WHERE id=" . $id);
		if (!isset($order['id'])) {
			die('订单不存在!');
		}

		$this->load->model('share');

		$orders = array($order);

		$this->load->model('order');

		$orders = $this->order->unionOrderGoods($orders);

		foreach ($orders as $k => $v) {
			if (!isset($orders[$k]['goods'])) {
				$list[$k]['goods'] = array();
			}

		}
		$order = $orders[0];

        //平台优惠券
        if($order['coupon_id']){
            $coupon_id = $this->db->getstr("select coupon_id from ###_coupon_log where id={$order['coupon_id']}","coupon_id");
            if($coupon_id)$order['coupon_amount'] = $this->db->getstr("select amount from ###_coupon where id={$coupon_id}");
        }
        //商家优惠券
        if($order['coupon_id_sid']){
            $coupon_id_sid = $this->db->getstr("select coupon_id from ###_coupon_log where id={$order['coupon_id_sid']}","coupon_id");
            if($coupon_id_sid)$order['coupon_amount_sid'] = $this->db->getstr("select amount from ###_coupon where id={$coupon_id_sid}");
        }
        //商家信息
        if($order['sid']>0){
            $order['business_name'] = $this->db->getstr("select name from ###_business where id={$order['sid']}",'name');
        }
        if($order['status_pay']>0){
            $order['out_trade_no'] = $this->db->getstr("select out_trade_no from ###_pay_log where order_id={$order['id']}","out_trade_no");
        }

        //判断开团参团
        if($order['common_id']>0){
            $c_mid = $this->db->getstr("select mid from ###_goods_order_common where id={$order['common_id']}","mid");
            if($order['mid']==$c_mid){
                $order['comm_type'] = "开团";
            }else{
                $username = $this->db->getstr("select username from ###_member where mid={$c_mid}","username");
                $order['comm_type'] = "参团,团长:【{$username}】";
            }
        }
        
		$rows = $this->db->select("SELECT * FROM ###_goods_order_log WHERE order_id=" . $id . " ORDER BY id DESC");

		$this->smarty->assign('order_logs', $rows);

		$this->smarty->assign('order', $order);

		$this->smarty->display('manage/order/detail.html');
	}

	//订单更新
	function edit() {
		$this->load->model('order');

		//提交
		if (isset($_POST['Submit'])) {
			$res = $this->order->save();

			if (isset($res['code']) && $res['code'] == 0) {
				$this->tip($res['message'], array('inIframe' => true));

				$this->exeJs("parent.com.xhide();parent.main.refresh()");
			} else {
				$this->tip($res['message'], array('inIframe' => true, 'type' => 1));
			}
			exit;
		}

		$id = (int) $_GET['id'];

		$row = $this->db->get("SELECT * FROM ###_goods_order WHERE id=" . $id);

		#物流列表
		$express = $this->db->select("SELECT * FROM ###_express WHERE status=1 and sid=0 ORDER BY id ASC");

		$this->smarty->assign('express', $express);

		$this->smarty->assign('row', $row);

		$this->smarty->display('manage/order/edit.html');
	}

	//编辑订单商品
	function goods() {
		$id = (int) $_GET['id'];

		//提交
		if (isset($_POST['Submit'])) {
			$post = $_POST['post'];

			#保存
			$where = $id ? array('id' => $id) : '';

			$res = $this->db->save('goods_order_item', $post, '', $where);

			if ($res) {
				admin_log('编辑订单商品备注：' . $id);

				$this->tip('更新成功', array('inIframe' => true));

				$this->exeJs("parent.com.xhide();parent.main.refresh()");
			} else {
				$this->tip('数据更新失败', array('inIframe' => true, 'type' => 1));
			}
			exit;
		}
		$row = $this->db->get("SELECT * FROM ###_goods_order_item WHERE id=" . $id);

		$this->smarty->assign('row', $row);

		$this->smarty->display('manage/order/goods.html');
	}

	//选菜列表
	function pack($id = 0, $page = 1) {
		$excel = (isset($_GET['excel']) && $_GET['excel'] == 1) ? 1 : 0;

		$where = 1;
		if ($id) {
			$where .= " AND order_id=" . $id;

			$this->smarty->assign('id', $id);
		}

		$array = array('start_time', 'end_time');
		foreach ($array as $v) {
			if (!isset($_GET[$v])) {
				$_GET[$v] = '';
			}

		}
		if ($_GET['start_time']) {
			$where .= " AND c_time>=" . strtotime($_GET['start_time'] . ' 00:00:00');
		}
		if ($_GET['end_time']) {
			$where .= " AND c_time<=" . strtotime($_GET['end_time'] . ' 23:59:59');
		}
		if (isset($_GET['status_shipping']) && $_GET['status_shipping']) {
			$_GET['status_shipping'] = ($_GET['status_shipping'] == 99) ? 0 : $_GET['status_shipping'];

			$where .= " AND status_shipping=" . $_GET['status_shipping'];
		}

		$this->load->model('share');

		$this->load->model('order');

		$this->load->model('goods');

		$this->load->model('page');

		$_GET['page'] = $page;

		//按期数查询选菜记录
		$sql = "SELECT * FROM ###_goods_order_item_pack WHERE " . $where . " GROUP BY order_id,qishu ORDER BY id DESC";
		if ($excel == 1) {
			$items = $this->db->select($sql);
		} else {
			$items = $this->page->hashQuery($sql, $id . '/')->result_array();
		}
		$items = $this->db->lJoin($items, 'express', 'id,name,pinyin', 'express', 'id', 'express_');
		foreach ($items as $k => $v) {
			$order = $this->db->get("SELECT * FROM `###_goods_order` WHERE id=" . $v['order_id']);
			if ($id && $k == 0) {
				$this->btnMenu[1] = array('url' => '#!order/pack', 'name' => '选菜管理（' . $order['order_sn'] . '）');

				$this->smarty->assign('btnMenu', $this->btnMenu);
			}
			$orders = array($order);

			$orders = $this->order->unionOrderGoods($orders);

			$order = $orders[0];

			$items[$k]['order'] = $order;

			$cat_pack = !empty($order['goods_list']) ? json_decode($order['goods_list'], true) : array();

			$cat_pack = is_array($cat_pack) ? $cat_pack : array();

			$items_pack = $this->db->select("SELECT * FROM ###_goods_order_item_pack WHERE order_id=" . $v['order_id'] . " AND qishu=" . $v['qishu']);

			$items_pack = $this->db->lJoin($items_pack, 'goods', 'id,thumb,unit,pack_num,cid', 'good_id', 'id', 'goods_');
			foreach ($items_pack as $m => $n) {
				$n['thumb'] = $n['goods_thumb'];
				unset($n['goods_thumb']);

				$n = $this->goods->getThumb($n, 1, array('thumb'));

				//实际单位
				if ($cat_pack) {
					foreach ($cat_pack as $w) {
						if ($w['cat_id'] == $n['goods_cid']) {
							$n['cat_unit'] = $w['cat_unit'];

							break;
						}
					}
				}

				$items_pack[$m] = $n;
			}
			$items[$k]['goods'] = $items_pack;

			$items[$k]['status_name'] = $this->order->shippingStatus[$v['status_shipping']];
		}

		if ($excel) {
			return $items;
		}

		//发货状态
		$shippingStatus = $this->order->shippingStatus;
		foreach ($shippingStatus as $k => $v) {
			if ($k == 0) {
				$shippingStatus[99] = $v;
				unset($shippingStatus[0]);
			}
		}
		$this->smarty->assign('shippingStatus', $shippingStatus);

		$this->smarty->assign('items', $items);

		$this->smarty->assign('btnNo', 1);

		$this->smarty->display('manage/order/pack.html');
	}

	/**
	 * 收到货款
	 * @param string $order_id
	 */
	function receivePay($order_id = '') {
		is_numeric($order_id) || $this->fatalError('参数错误!');

		$order = $this->db->get("SELECT * FROM ###_goods_order WHERE id=" . $order_id);
		isset($order['id']) || $this->fatalError('订单不存在!');

		//提交
		if (isset($_POST['Submit'])) {
			isset($_POST['state_info']) || $this->tip('请填写操作备注!', array('inIframe' => true, 'type' => 1));

			//货到付款，收到货款时，订单交易完成
            if($order['extension_code']==CART_STEP && $order['status_pay']==0){//阶梯团先付定金 成团后付尾款
                $set = array('status_pay' => 1);
            }else{
                $set = array('status_pay' => 10);
            }

			if ($order['is_cod'] == 1) {
				$set['status_shipping'] = 10;

				$set['status_order'] = 10;
			}
            $set['pay_time'] = RUN_TIME;//更新付款时间
			$res = $this->order->chageOrderState($order_id, $set, $_POST['state_info'], array('amount' => $order['amount']));
			if ($res) {
				//收到货款 支付方式改为线下付款 Feng 2016-06-14 start
				$this->db->update("goods_order", array("pay_name" => "线下付款"), array("id" => $order_id));
				//收到货款 支付方式改为线下付款 Feng 2016-06-14 end

				//付款后的处理
                if($set['status_pay']==10){
                    $this->order->moneyOrder($order);
                }


				$this->tip('操作成功', array('inIframe' => true));

				$this->exeJs("parent.com.xhide();parent.main.refresh()");
			} else {
				$this->tip('操作失败', array('inIframe' => true, 'type' => 1));
			}
		}

		$this->smarty->assign('order_id', $order_id);

		$this->smarty->assign('order_no', $order['order_sn']);

		$this->smarty->display('manage/order/state_receive_pay.html');
	}

	/**
	 * 退款 Feng 2016-05-26   弃用
	 * @param string $order_id
	 */
	function refund($order_id = '') {
		is_numeric($order_id) || $this->fatalError('参数错误!');

		$order = $this->db->get("SELECT * FROM ###_goods_order WHERE id=" . $order_id);
		isset($order['id']) || $this->fatalError('订单不存在!');

		//提交
		/*if (isset($_POST['Submit'])) {
			$post = $_POST['post'];
			if (empty($post['refund_note'])) {$this->tip('请填写操作备注!', array('inIframe' => true, 'type' => 1));exit;}
			$post['refund_time'] = RUN_TIME;
			//$res = $this->db->update("goods_order", $post, array("id"=>$order_id));
			$res = $this->order->chageOrderState($order_id, $post, '退货成功');
			if ($res) {
				if ($post['refund_amount'] > 0 && $post['status_order'] == 3) {
					$this->load->model('member');
					$t = $this->member->accountlog('refund', array('mid' => $order['mid'], 'user_money' => $post['refund_amount'], 'desc' => '订单' . $order['order_sn'] . '退款成功'));

					// 模版消息 17 退款申请被通过 {插入订单号},{插入店铺}
					// template_msg_action start
					$this->load->model('template_msg');
					$msgParams = array($order['order_sn'], C("site_name"));
					$this->template_msg->inQueue(17, $order['mid'], $msgParams);
					// template_msg_action end

				} elseif ($post['status_order'] == 4) {
					// 模版消息 18 退款申请被拒绝 {插入订单号},{插入理由},{插入店铺}
					// template_msg_action start
					$this->load->model('template_msg');
					$msgParams = array($order['order_sn'], $post['refund_note'], C("site_name"));
					$this->template_msg->inQueue(18, $order['mid'], $msgParams);
					// template_msg_action end
				}

				$this->tip('操作成功', array('inIframe' => true));

				$this->exeJs("parent.com.xhide();parent.main.refresh()");
			} else {
				$this->tip('操作失败', array('inIframe' => true, 'type' => 1));
			}
		}*/

		$this->smarty->assign('order', $order);

		$this->smarty->display('manage/order/state_refund.html');
	}

	/**
	 * 取消订单
	 * @param string $order_id
	 */
	function cancel($order_id = '') {
		is_numeric($order_id) || $this->fatalError('参数错误!');

		$order = $this->db->get("SELECT * FROM ###_goods_order WHERE id=" . $order_id);
		isset($order['id']) || $this->fatalError('订单不存在!');

		//提交
		if (isset($_POST['Submit'])) {
			$this->load->model('order');

			$res = $this->order->chageOrderState($order_id, array('status_order' => 1), '取消订单：' . $_POST['state_info']);

			if ($res) {
				//取消订单成功,相关的优惠券和积分返还给用户.
				$this->order->cancelOrder($order_id);

				$this->tip('取消成功', array('inIframe' => true));

				$this->exeJs("parent.com.xhide();parent.main.refresh()");
			} else {
				$this->tip('操作失败!', array('inIframe' => true, 'type' => 1));
			}
			exit;
		}

		$this->smarty->assign('order_id', $order_id);

		$this->smarty->assign('order_no', $order['order_sn']);

		$this->smarty->display('manage/order/state_order_cancel.html');
	}

	/**
	 * 修改价格
	 * @param string $order_id
	 */
	function chPrice($order_id = '') {
		is_numeric($order_id) || $this->fatalError('参数错误!');

		$order = $this->db->get("SELECT * FROM ###_goods_order WHERE id=" . $order_id);
		isset($order['id']) || $this->fatalError('订单不存在!');

		//提交
		if (isset($_POST['Submit'])) {
			isset($_POST['amount']) || $this->tip('参数错误!', array('inIframe' => true, 'type' => 1));

			$this->load->model('order');

			$res = $this->order->chOrderPrice($order_id, $_POST['amount']);

			if ($res === -1) {
				$this->tip('订单不存在!', array('inIframe' => true, 'type' => 1));
			} elseif ($res === -2) {
				$this->tip('价格未更新!', array('inIframe' => true, 'type' => 1));
			} elseif ($res) {
				$this->tip('价格更新成功', array('inIframe' => true));
			}
			$this->exeJs("parent.com.xhide();parent.main.refresh()");
		}

		$this->smarty->assign('order', $order);

		$member = $this->db->get("SELECT * FROM ###_member WHERE mid=" . $order['mid']);

		$this->smarty->assign('member', $member);

		$this->smarty->display('manage/order/state_chprice.html');
	}

	/**
	 * 发货管理
	 * @param string $id
	 */
	function send($order_id = '') {
		is_numeric($order_id) || $this->fatalError('参数错误!');

		$order = $this->db->get("SELECT * FROM ###_goods_order WHERE id=" . $order_id);
		isset($order['id']) || $this->fatalError('订单不存在!');

		#物流列表
		$express = $this->db->select("SELECT * FROM ###_express WHERE status=1 and sid=0 ORDER BY id ASC");

		$this->smarty->assign('express', $express);

		$this->load->model('share');

		$orders = array($order);

		$this->load->model('order');

		$orders = $this->order->unionOrderGoods($orders);

		$order = $orders[0];

        $order['goods'] = $this->db->lJoin($order['goods'],"goods_additional","goods_id,goods_no","good_id","goods_id");
        if($order['goods'][0]['spec']){
            $order['goods'][0]['goods_no'] = $this->db->getstr("select serial from ###_goods_item where goods_id={$order['goods'][0]['good_id']} and spec='{$order['goods'][0]['spec']}'","serial");
        }

        if(C('nation_realname')==1 && $order['goods'][0]['goods_nation_id']>0){
            $order['real_info'] = $this->db->get("select realname,idcard from ###_member_detail where mid={$order['mid']}");
        }

		$this->smarty->assign('order', $order);

		$this->smarty->display('manage/order/send.html');
	}
	
	/**
	 * 财务结算
	 * @param string $id
	 */
	function balance($order_id = '') {
		is_numeric($order_id) || $this->fatalError('参数错误!');
	
		$order = $this->db->get("SELECT * FROM ###_goods_order WHERE id=" . $order_id);
		isset($order['id']) || $this->fatalError('订单不存在!');
	
		//验证该订单是否可以结算，需要在已发货和完结状态
		if($order['status_pay']==10&&(($order['status_shipping']==10&&$order['status_order']==10)||($order['status_shipping']==2&&$order['status_order']==0))){
			//判断是否已经结算过
			if($order['is_balance']==1){
				$this->fatalError('该订单财务已经结算!');
			}else{
				$this->load->model('order');
				$res = $this->order->chageOrderState($order_id, array('is_balance' => 1), '财务结算');
				$this->tip('操作成功', array('inIframe' => true));
				$this->exeJs("parent.window.location='/manage#!order/index';");
			}
		}else{
			$this->fatalError('参数错误!');
		}
		exit;
	}

	function send_take($order_id = ''){
		is_numeric($order_id) || $this->fatalError('参数错误!');
		$order = $this->db->get("SELECT * FROM ###_goods_order WHERE id=" . $order_id);
		isset($order['id']) || $this->fatalError('订单不存在!');
		$msg = '发货操作';
		$this->load->model('order');
		$res = $this->order->chageOrderState($order_id, array('status_shipping' => 2), $msg);
		if ($res) {
			// 模版消息 15 卖家已发货 {插入订单号},{插入商品标题},{插入快递公司},{插入快递单号}
			// template_msg_action start
			$this->load->model('template_msg');
			$orderItems = $this->db->select('SELECT goods_name FROM ###_goods_order_item where order_id = ' . $order_id);
			$orderNames = join('; ', array_column($orderItems, 'goods_name'));
			$expressName = '发货操作';
			$randNums = randNums(13);
			$msgParams = array($order['order_sn'], $orderNames, $expressName, $randNums);
			// template_msg_action end
			//AA团只发货一次
			if($order['extension_code']==CART_AA){
				$this->db->update('goods_order', array(
					'status_shipping'=> 2,
					'shipping_time' => RUN_TIME,
				), array('common_id' => $order['common_id']));
				$list = $this->db->select("select mid from ###_goods_order where common_id={$order['common_id']}");
				$data = array();
				foreach($list as $k=>$v){
					$temp[0] = 15;
					$temp[1] = $v['mid'];
					$temp[2] = $msgParams;
					$data[] = $temp;
				}
				$this->template_msg->inQueueMany($data);
			}else{
				$this->db->update('goods_order', array(
					'shipping_time' => RUN_TIME,
				), array('id' => $order_id));
				$this->template_msg->inQueue(15, $order['mid'], $msgParams);
			}
			$take_verify_code_input = array(
				'mid' => $order['mid'],
				'order_id' => $order_id,
				'verify_code' => $randNums,
				'status' => 0,
			);
			$verify_code_id = $this->db->save('take_verify_code', $take_verify_code_input);
			$this->db->update('goods_order_item', array(
				'verify_code_id' => $verify_code_id,
			), array('order_id' => $order_id));
			$this->tip('发货成功', array('inIframe' => true));
			$this->exeJs("parent.com.xhide();refresh()");
		} else {
			$this->tip('操作失败!', array('inIframe' => true, 'type' => 1));
		}
	}

	/** 套餐选菜发货
	 * @param string $order_id
	 */
	function send_pack($order_id = '', $qishu = 1) {
		is_numeric($order_id) || $this->fatalError('参数错误!');

		$order = $this->db->get("SELECT * FROM ###_goods_order WHERE id=" . $order_id);
		isset($order['id']) || $this->fatalError('订单不存在!');

		#物流列表
		$express = $this->db->select("SELECT * FROM ###_express ORDER BY id ASC");

		$this->smarty->assign('express', $express);

		$this->load->model('share');

		$orders = array($order);

		$this->load->model('order');

		$orders = $this->order->unionOrderGoods($orders);

		$order = $orders[0];

		//查询当期选菜
		$items_pack = $this->db->select("SELECT * FROM ###_goods_order_item_pack WHERE order_id=" . $order_id . " AND qishu=" . $qishu);

		$items_pack = $this->db->lJoin($items_pack, 'goods', 'id,thumb', 'good_id', 'id', 'goods_');
		foreach ($items_pack as $m => $n) {
			$n['thumb'] = $n['goods_thumb'];
			unset($n['goods_thumb']);

			$n = $this->goods->getThumb($n, 1, array('thumb'));

			$items_pack[$m] = $n;
		}

		$this->smarty->assign('qishu', $qishu);

		$this->smarty->assign('order', $order);

		$this->smarty->assign('items', $items_pack);

		$this->smarty->display('manage/order/send_pack.html');
	}

	function verify_order_code($order_id = ''){
		if(isset($_POST['Submit'])){
			$post = $_POST['post'];
            $verify_code = trim($post['verify_code']);
			if(empty($verify_code)){
				$this->tip('参数错误!', array('inIframe' => true, 'type' => 1));exit;
			}
			$order = $this->db->get("SELECT id,mid,common_id,status_shipping,is_cod,extension_code FROM ###_goods_order WHERE id=" . $order_id);
			if(!$order){
				$this->tip('订单不存在!', array('inIframe' => true, 'type' => 1));exit;
			}
			$take_verify_code = $this->db->get("SELECT id,mid FROM ###_take_verify_code WHERE order_id=$order_id AND verify_code=$post[verify_code] AND status=0");
			if(!$take_verify_code){
				$this->tip('验证码错误或已过期!', array('inIframe' => true, 'type' => 1));exit;
			}
			if($order['common_id'] > 0 && $order['extension_code'] == CART_AA){
				$order_common = $this->db->get("SELECT mid FROM ###_goods_order_common WHERE id=$order[common_id]");
				if(!$order_common){
					$this->tip('订单出错!', array('inIframe' => true, 'type' => 1));exit;
				}
				if($order_common['mid'] != $take_verify_code['mid']){
					$this->tip('非法操作!', array('inIframe' => true, 'type' => 1));exit;
				}
			}else{
				if($order['mid'] != $take_verify_code['mid']){
					$this->tip('非法操作!', array('inIframe' => true, 'type' => 1));exit;
				}
			}
			if ($order['status_shipping'] == 2) {
				$this->load->model('order');
				//非货到付款，收货时，订单交易完成
				$set = array('status_shipping' => 10);
				if($order['is_cod'] != 1){
					$set['status_pay']   = 10;
					$set['status_order'] = 10;
				}
				$set['confirm_time'] = RUN_TIME;
				$msg                 = '确认收货';
				$this->order->chageOrderState($order['id'], $set, $msg);
				if($order['extension_code']==CART_AA){//AA团 团长确认收货
					$this->db->update('goods_order', array(
						'status_pay'   => 10,
						'status_order' => 10,
						'status_shipping' => 10,
					), array('common_id' => $order['common_id']));
				}
				$this->db->update('take_verify_code', array(
					'status'   => 1,
				), array('id' => $take_verify_code['id']));
				$this->tip('验证成功', array('inIframe' => true));
				$this->exeJs("parent.com.xhide();parent.main.refresh()");
			}else{
				$this->tip('订单出错!', array('inIframe' => true, 'type' => 1));exit;
			}
		}
		$this->smarty->assign('order_id', $order_id);
		$this->smarty->display('manage/order/verify_order_code.html');
	}

	//备货
	function doSendPre() {
		if (!empty($_POST['order_id']) && isset($_POST['oldprice'])) {
			$this->tip('缺少必要参数!', array('inIframe' => true, 'type' => 1));
		}

		$qishu = isset($_POST['qishu']) ? intval($_POST['qishu']) : 0;

		$order_id = intval($_POST['order_id']);

		$order = $this->db->get("SELECT * FROM ###_goods_order WHERE id=" . $order_id);

		$msg = '备货操作' . ($_POST['state_info'] ? '：' . $_POST['state_info'] : '');
		if ($qishu > 0) {
			$msg .= '（第' . $qishu . '次选菜）';
		}

		$this->load->model('order');

		$res = $this->order->chageOrderState($order_id, array('status_shipping' => 1), $msg, array('qishu' => $qishu));

		if ($res) {
			$this->tip('备货成功', array('inIframe' => true));

			$this->exeJs("parent.com.xhide();parent.main.refresh()");
		} else {
			$this->tip('操作失败!', array('inIframe' => true, 'type' => 1));
		}
	}

	/**
	 * 提交发货信息
	 */
	function doSend() {
		if (!empty($_POST['order_id']) && isset($_POST['express_no'])) {
			$this->tip('缺少必要参数!', array('inIframe' => true, 'type' => 1));
		}
		if (empty($_POST['express_no'])) {
			$this->tip('请输入物流单号!', array('inIframe' => true, 'type' => 1));
			die;
		}

		$order_id = intval($_POST['order_id']);

		$express_no = $_POST['express_no'];

		$express = intval($_POST['express']);

		if (!$express) {
			$pinyin = trim($_POST['pinyin']);

			$express_name = trim($_POST['express_name']);

			$row_express = $this->db->get("SELECT * FROM ###_express WHERE name='" . $express_name . "'" );
			if ($row_express) {
				$express = $row_express['id'];
			} else {
				$express = $this->db->save('express', array(
					'name'   => $express_name,
					'pinyin' => $pinyin,
				));
			}
		}

		$msg = '发货操作' . ($_POST['state_info'] ? '：' . $_POST['state_info'] : '');
		
		$this->load->model('order');

		$res = $this->order->chageOrderState($order_id, array('status_shipping' => 2), $msg);

		if ($res) {
			
                        
			
            // 模版消息 15 卖家已发货 {插入订单号},{插入商品标题},{插入快递公司},{插入快递单号}
			// template_msg_action start
			$this->load->model('template_msg');
			$order      = $this->db->get('SELECT * FROM ###_goods_order where id = ' . $order_id);
			$orderItems = $this->db->select('SELECT goods_name FROM ###_goods_order_item where order_id = ' . $order_id);

			$orderNames = join('; ', array_column($orderItems, 'goods_name'));

			$expressName = $this->db->getstr('SELECT name FROM ###_express WHERE id=' . $express);

			$msgParams = array($order['order_sn'], $orderNames, $expressName, $express_no);
			
			// template_msg_action end

            // 微信模版消息 order_ship 发货
            // wxtemplate_action start
            $this->load->model('wxtemplate');
            $msgParams = array(
                "url" => RootUrl."member/order_ship/".$order['order_sn'],
                "keyword1"=>$order['order_sn'],
                "keyword2"=>$expressName,
                "keyword3"=>$express_no,
                "keyword4"=>$order['area'].$order['address'],
            );
            $this->wxtemplate->inQueue($order['mid'],'order_ship',$msgParams);
            // wxtemplate_action end
                        
            //AA团只发货一次
            if($order['extension_code']==CART_AA){
                $this->db->update('goods_order', array(
                        'status_shipping'=> 2,
                        'express'       => $express,
                        'express_num'   => $express_no,
                        'shipping_time' => RUN_TIME,
                ), array('common_id' => $order['common_id']));
                $list = $this->db->select("select mid from ###_goods_order where common_id={$order['common_id']}");
                $data = array();
                foreach($list as $k=>$v){
                    $temp[0] = 15;
                    $temp[1] = $v['mid'];
                    $temp[2] = $msgParams;
                    $data[] = $temp;
                }
                $this->template_msg->inQueueMany($data);
            }else{
                $this->db->update('goods_order', array(
                        'express'       => $express,
                        'express_num'   => $express_no,
                        'shipping_time' => RUN_TIME,
                ), array('id' => $order_id));
                $this->template_msg->inQueue(15, $order['mid'], $msgParams);
            }

			$this->tip('发货成功', array('inIframe' => true));

			$this->exeJs("parent.com.xhide();parent.main.refresh()");
		} else {
			$this->tip('操作失败!', array('inIframe' => true, 'type' => 1));
		}
	}

	/**
	 * 物流查询
	 * @param $order_id
	 */
	function viewExpress($order_id) {
		$order = $this->db->get("SELECT * FROM ###_goods_order WHERE id=" . $order_id);
		isset($order['express_num']) || $this->fatalError('订单不存在!');

		$express_num = $order['express_num'];

		$express = $this->db->get("SELECT * FROM ###_express WHERE id=" . $order['express'] );
		if (!isset($express['id'])) {
			die('无法查询该快递!');
		}

		$type = $express['pinyin'];

		$this->load->model('share');

		$result = $this->share->http('http://www.kuaidi100.com/query', 'get', array(
			'type'     => $type,
			'postid'   => $express_num,
			'id'       => '1',
			'valicode' => '',
			'temp'     => '0.03273166622966528',
		));

		$data = json_decode($result, true);

		$this->smarty->assign('list', $data);

		$this->smarty->assign('order', $order);

		$this->smarty->assign('express', $express);
		//print_r('<pre>');var_dump($data);print_r('</pre>');exit;
		$this->smarty->display('manage/order/view_express.html');

		#document:http://www.kuaidi100.com/openapi/mobileapi.shtml
		#http://m.kuaidi100.com/index_all.html?type=[快递公司编码]&postid=[快递单号]&callbackurl=[点击"返回"跳转的地址]
	}

	/**
	 * 导出订单
	 */
	function exportExcel($type = '', $id = '') {
		$this->load->model('share');

		$_GET['excel'] = 1;
        if($_GET['business_order']){
            $data = $this->business_order();
        }else{
            $data = $this->index();
        }


        $list = array();
        foreach ($data as $k => $v) {
            $v['order_sn'] = $v['order_sn']."'";

            $v['c_time'] = date('Y-m-d H:i:s', $v['c_time']);

            $v['address'] = $v['area'] . ' ' . $v['address'];

            $v['goods_list'] = $v['goods'][0]['goods_name'].","."货号：".$v['goods'][0]['goods_no'];

            $v['mobile'] = $v['mobile'];

            //快递公司 快递单号
            $v['express'] = trim($v['express']);
            $v['express_num'] = trim($v['express_num']);

            $v['out_trade_no'] = '';
            //if($v['status_pay']>0){
            $v['out_trade_no'] = $this->db->getstr("select out_trade_no from ###_pay_log where order_id={$v['id']} and order_type!=".PAY_BS,"out_trade_no");
            //}

            $list[] = $v;
        }
        $fields = array(
            'id'     => '订单ID',
            'order_sn'     => '订单号',
            'out_trade_no'     => '支付单号',
            'c_time'       => '下单时间',
            'order_code'   => '订单类型',
            'status_name'  => '订单状态',
            'm_username'   => '会员名',
            'order_tip' => '买家备注',
            'name'         => '收货人',
            'mobile'       => '收货人电话',
            'address'      => '收货地址',
            'order_amount' => '订单总金额',
            'goods_list'   => '商品列表',
            'express'   => '快递公司ID',
            'express_num'   => '快递单号',
        );

        $this->share->SetExcelHeader('订单列表-' . date('Y-m-d H:i:s'), '订单列表');

        $this->share->SetExcelBody($fields, $list);

	}

	/** 关闭三天未付款订单
	 */
	function close3day() {
		$day3ago = strtotime(date('Y-m-d 00:00:01', strtotime('-3 days')));

		$rows = $this->db->select("SELECT * FROM `###_goods_order` WHERE status=1 AND c_time<" . $day3ago);

		$this->load->model('order');
		foreach ($rows as $val) {
			$this->order->chageOrderState($val['id'], array('status_order' => 1), '一键关闭超过3天未付款订单!');

			$this->order->cancelOrder($val['id']);
		}

		$this->tip('操作成功!');

		$this->exeJs('main.refresh();');
	}

	/** 检索条件
	 * @return array
	 */
	function getFilterCond() {
		$where = array();

		$order = ' ORDER BY ';

		#关键词搜索
		$array = array('k', 'q');
		foreach ($array as $v) {
			if (!isset($_GET[$v])) {
				$_GET[$v] = '';
			}

		}
		if (!empty($_GET['q'])) {
			if ('username' == $_GET['k']) {
				$sql = "SELECT mid FROM ###_member WHERE  `" . trim($_GET['k']) . "` LIKE '%" . addslashes($_GET['q']) . "%'";

				$tmp = $this->db->select($sql);

				if ($tmp) {
					$mids = join(",", array_column($tmp, 'mid'));

					$where[] = "go.mid in (" . $mids . ")";
				} else {
					$where[] = "go.mid = 0";
				}
			} else if ('ivt_id' == $_GET['k']) {
				// fan 2016-04-13 start
				// 按推荐人
				$itv_id = $_GET['q'];

				$ivt_ids = '';
				if ($itv_id && is_numeric($itv_id)) {
					$this->load->model('invite');

					$ivt_ids = $this->invite->getInviteTreeIds($itv_id);
					if ($ivt_ids) {
						$where[] = "go.mid in (" . $ivt_ids . ")";
					}
				}
				if (!$ivt_ids) {
					$where[] = " 1=2";
				}
			} else {
				$where[] = trim($_GET['k']) . " LIKE '%" . addslashes($_GET['q']) . "%'";
			}
		}

		$array = array('start_time', 'end_time', 'status_pay', 'status_shipping', 'status_order', 'extension_code');
		foreach ($array as $v) {
			if (!isset($_GET[$v])) {
				$_GET[$v] = '';
			}

		}
        //商家
        if ($_REQUEST['bname']) {
            $bid_array = $this->db->select("select id from ###_business where name LIKE '%" . addslashes($_REQUEST['bname']) . "%'");
            if($bid_array){
                $bid_array = array_column($bid_array,"id");
                $where[] = " go.sid in (".join(',',$bid_array).") ";
            }else{
                $where[] = " 1=2 ";
            }
        }

		if ($_GET['start_time']) {
			$where[] = "go.c_time>=" . strtotime($_GET['start_time'] . ' 00:00:00');
		}
		if ($_GET['end_time']) {
			$where[] = "go.c_time<=" . strtotime($_GET['end_time'] . ' 23:59:59');
		}
		if ($_GET['end_time']) {
			$where[] = "go.c_time<=" . strtotime($_GET['end_time'] . ' 23:59:59');
		}
		if ($_GET['verify_code']) {
			$where[] = "tvc.verify_code = ".trim($_GET['verify_code']);
		}
		if (isset($_GET['extension_code']) && $_GET['extension_code']) {
			$where[] = "go.extension_code=" . $_GET['extension_code'];
		}
		if (isset($_GET['status_pay']) && $_GET['status_pay']) {
			$_GET['status_pay'] = ($_GET['status_pay'] == 99) ? 0 : $_GET['status_pay'];

			$where[] = "go.status_pay=" . $_GET['status_pay'];

			$_GET['status_pay'] = ($_GET['status_pay'] == 0) ? 99 : $_GET['status_pay'];
		}
		if (isset($_GET['status_shipping']) && $_GET['status_shipping']) {
			$_GET['status_shipping'] = ($_GET['status_shipping'] == 99) ? 0 : $_GET['status_shipping'];

			$where[] = "go.status_shipping=" . $_GET['status_shipping'];

			$_GET['status_shipping'] = ($_GET['status_shipping'] == 0) ? 99 : $_GET['status_shipping'];
		}
		if (isset($_GET['status_order']) && $_GET['status_order']) {
			$_GET['status_order'] = ($_GET['status_order'] == 99) ? 0 : $_GET['status_order'];

			$where[] = "go.status_order=" . $_GET['status_order'];

			$_GET['status_order'] = ($_GET['status_order'] == 0) ? 99 : $_GET['status_order'];
		}
        if (isset($_GET['is_balance']) && $_GET['is_balance']) {
            $_GET['is_balance'] = ($_GET['is_balance'] == 99) ? 0 : $_GET['is_balance'];
            if($_GET['is_balance']==1)
                $where[] = "go.order_bill=" . $_GET['is_balance'];
            else
                $where[] = "go.order_bill=0 AND go.status_pay=10 AND ((status_shipping=10 AND status_order=10) or (status_shipping=2 AND status_order=0))";
            $_GET['is_balance'] = ($_GET['is_balance'] == 0) ? 99 : $_GET['is_balance'];
        }
        if ($_GET['sid']>0) {
            $sid = $_GET['sid']==999?0:intval($_GET['sid']);
            $where[] = "go.sid=" . $sid;
        }
		#快速排序
		$order .= isset($_GET['sortby']) ? $_GET['sortby'] : 'go.id';

		$order .= ' ';

		$order .= isset($_GET['sortorder']) ? $_GET['sortorder'] : 'DESC';

		$this->smarty->assign($_GET);
		return array('where' => $where, 'order' => $order);
	}

	/** 订单收益
	 * @return array
	 */
	function order_income($page = 1) {
		$conds = $this->getFilterCond();

		$condition = 'WHERE 1  AND (go.status_order=10 or (go.status_pay=10 and go.status_order=0))';

		$condition .= count($conds['where']) ? " AND " . implode(' AND ', $conds['where']) : '';

		$orderby = $conds['order'];

		$excel = (isset($_GET['excel']) && $_GET['excel'] == 1) ? 1 : 0;

		$this->load->model('page');

		$_GET['page'] = $page;

		$sql = "SELECT DISTINCT go.id,go.* FROM ###_goods_order AS go ";
		if (strpos($condition, 'goi.') !== false) {
			$sql .= "LEFT JOIN ###_goods_order_item AS goi ON goi.order_id=go.id ";
		} elseif (strpos($condition, 'g.') !== false) {
			$sql .= "LEFT JOIN ###_goods_order_item AS goi ON goi.order_id=go.id ";

			$sql .= "LEFT JOIN ###_goods AS g ON g.id=goi.good_id ";
		}
		$sql .= $condition . $orderby;

		$list = array();

		$list = $this->page->hashQuery($sql)->result_array();

		$list = $this->order->unionOrderGoods($list);

		$this->load->model("agent");
		foreach ($list as $k => $v) {
			if (!isset($list[$k]['goods'])) {
				$list[$k]['goods'] = array();
			}
			#佣金
			$list[$k]['commss'] = 0;

			$commss = $this->agent->getCommission(array("order_id" => $v['id']), "all");
			if ($commss) {
				$list[$k]['commss'] = array_sum(array_column($commss, "commission"));
				foreach ($commss as $_v) {
					if ($_v['level'] == "-1") {
						$list[$k]['level_p'] = $_v['commission'];
					} elseif ($_v['level'] == "-2") {
						$list[$k]['level_a'] = $_v['commission'];
					} else {
						$list[$k]['level_' . $_v['level']] += $_v['commission'];
					}
				}
			}
			#优惠金额
			$list[$k]['coupon'] = $v['goods_amount'] + $v['shipping_fee'] - $v['order_amount'];

			#收益
			$list[$k]['income'] = $list[$k]['goods_amount'] - $list[$k]['cost_amount'] - $list[$k]['commss'] - $list[$k]['coupon'];

		}
		//echo "<pre>";print_r($list);exit;

		//统计总收益和佣金
		$total = array();
		if (count($conds['where']) > 0) {
			$order = $this->db->select($sql);

			$order_str = join(",", array_column($order, "id"));
			if ($order_str) {
				$total['comm'] = $this->db->getstr("select sum(commission) as total from ###_commission where order_id in ({$order_str})", "total");

				$total['income'] = array_sum(array_column($order, 'order_amount')) - array_sum(array_column($order, 'cost_amount')) - array_sum(array_column($order, 'shipping_fee')) - $total['comm'];
			}
		} else {
			$total['comm'] = $this->db->getstr("select sum(commission_total) as total from ###_member", "total");

			$total['income'] = $this->db->getstr("select sum(order_amount-cost_amount-shipping_fee) as total from ###_goods_order WHERE status_order=10  or (status_pay=10 and status_order=0)", "total");

			$total['income'] -= $total['comm'];
		}

		$this->smarty->assign('list', $list);

		$this->smarty->assign('total', $total);

		$this->smarty->display('manage/order/income.html');
	}
        //商家订单
    function business_order($page = 1) {
        $this->btnMenu = array(
            0 => array('url' => '#!order/business_order', 'name' => '订单管理'),
            1 => array('url' => '#!order/business_order?lstype=pay', 'name' => '待付款'),
            2 => array('url' => '#!order/business_order?lstype=shipping', 'name' => '待发货'),
            3 => array('url' => '#!order/business_order?lstype=refund', 'name' => '退款'),
        );
        $this->smarty->assign('btnMenu', $this->btnMenu);

		$conds = $this->getFilterCond();

		$condition = 'WHERE go.is_robots=0 AND go.sid>0 AND (go.status_common=0 || (go.status_common=10 && go.extension_code!=5 && go.extension_code!=6) || ( (go.extension_code=5 || go.extension_code=6) && go.status_lottery=10) ) ';

		if($_GET['lstype']=='shipping'){//待发货
            $condition.=" AND go.status_pay=10 and go.status_order=0 and go.status_shipping in (0,1) ";
            $this->smarty->assign('btnNo', 2);
        }elseif($_GET['lstype']=='pay'){//待付款
            $condition.=" AND go.status_pay in (0,1) and go.status_order=0 ";
            $this->smarty->assign('btnNo', 1);
        }elseif($_GET['lstype']=='refund'){//退款
            $condition =" WHERE is_robots=0 AND sid>0 and go.status_order in (2,3,4) ";
            $this->smarty->assign('btnNo', 3);
        }else{
            $condition.=" AND go.status_pay=10 ";
            $this->smarty->assign('btnNo', 0);
        }

		$condition .= count($conds['where']) ? " AND " . implode(' AND ', $conds['where']) : '';

		$orderby = $conds['order'];

		$excel = (isset($_GET['excel']) && $_GET['excel'] == 1) ? 1 : 0;

		$this->load->model('page');

		$_GET['page'] = $page;

		//$sql = "SELECT DISTINCT go.id,go.*,goi.type,goi.verify_code_id FROM ###_goods_order AS go ";
		$sql = "SELECT DISTINCT go.id,go.* FROM ###_goods_order AS go ";
		if (strpos($condition, 'goi.') !== false) {
			$sql .= "LEFT JOIN ###_goods_order_item AS goi ON goi.order_id=go.id ";
		} elseif (strpos($condition, 'g.') !== false) {
			$sql .= "LEFT JOIN ###_goods_order_item AS goi ON goi.order_id=go.id ";
			$sql .= "LEFT JOIN ###_goods AS g ON g.id=goi.good_id ";
		}elseif (strpos($condition, 'tvc.') !== false){
			$sql .= "LEFT JOIN ###_goods_order_item AS goi ON goi.order_id=go.id ";
			$sql .= "LEFT JOIN ###_take_verify_code AS tvc ON go.id=tvc.order_id ";
		} else {
			//$sql .= "LEFT JOIN ###_goods_order_item AS goi ON goi.order_id=go.id ";
		}

		if (isset($_REQUEST['total'])) {
			#订单支付
			$condition2 = '';
			if (strpos($condition, 'goi.') !== false) {
				$condition2 .= "LEFT JOIN ###_goods_order_item AS goi ON goi.order_id=go.id " . $condition;
			} elseif (strpos($condition, 'g.') !== false) {
				$condition2 .= "LEFT JOIN ###_goods_order_item AS goi ON goi.order_id=go.id ";

				$condition2 .= "LEFT JOIN ###_goods AS g ON g.id=goi.good_id " . $condition;
			} else {
				$condition2 = $condition;
			}
			$str = "SELECT SUM(go.order_amount) AS order_amount,SUM(go.money_paid) AS money_paid FROM ###_goods_order AS go " . $condition2;

			$row = $this->db->get($str);

			$data['order_price'] = $row['order_amount'] - $row['money_paid'];

			$data['money_paid'] = $row['money_paid'];

			$this->smarty->assign('data', $data);
		}

		$sql .= $condition . $orderby;

		$list = array();
		if ($excel == 1) {
			$list = $this->db->select($sql);
		} else {
			$list = $this->page->hashQuery($sql)->result_array();
		}
		$this->load->model('share');
			
		$list = $this->order->unionOrderGoods($list);

		foreach ($list as $k => $v) {
			if (!isset($list[$k]['goods'])) {
				$list[$k]['goods'] = array();
			}
			if($v['common_id'] > 0 && $v['extension_code'] == CART_AA){
				$order_common = $this->db->get("SELECT mid FROM ###_goods_order_common WHERE id=$v[common_id]");
				$list[$k]['tuanzhang'] = $order_common['mid'];
			}else{
				$list[$k]['tuanzhang'] = 0;
			}
		}

		//订单类型
		$this->smarty->assign('orderType', $this->order->actTypes);

		//付款状态
		$payStatus = array();

		$array = $this->order->payStatus;
		foreach ($array as $k => $v) {
			if ($k == 0) {
				$payStatus[99] = $v;
				unset($payStatus[0]);
			} else {
				$payStatus[$k] = $v;
			}
		}
		$this->smarty->assign('payStatus', $payStatus);

		//发货状态
		$shippingStatus = $this->order->shippingStatus;
		foreach ($shippingStatus as $k => $v) {
			if ($k == 0) {
				$shippingStatus[99] = $v;
				unset($shippingStatus[0]);
			}
		}
		$this->smarty->assign('shippingStatus', $shippingStatus);
        //财务结算状态
        $balanceStatus = $this->order->balanceStatus;
        foreach ($balanceStatus as $k => $v) {
            if ($k == 0) {
                $balanceStatus[99] = $v;
                unset($balanceStatus[0]);
            }
        }
        $this->smarty->assign('balanceStatus', $balanceStatus);
		//订单状态
		$orderStatus = $this->order->orderStatus;
		foreach ($orderStatus as $k => $v) {
			if ($k == 0) {
				$orderStatus[99] = $v;
				unset($orderStatus[0]);
			}
		}
		$this->smarty->assign('orderStatus', $orderStatus);
        //商家列表
        $res_business = $this->db->select("select id,name from ###_business where status=1");
        if($res_business){
            $business = array_combine(array_column($res_business, 'id'), array_column($res_business, "name"));
            $this->smarty->assign('business_list', $business);
        }

		//导出
		if ($excel == 1) {
			return $list;
		}

		$this->smarty->assign('list', $list);

		$this->smarty->display('manage/order/business_order.html');
	}


    /**
     * 批量发货
     */
    function excelOrderSend() {
        if (isset($_POST['leadExcel']) && !empty($_POST['leadExcel'])) {
            $this->load->model('order');
            $data0 = mb_convert_encoding(file_get_contents($_FILES['inputExcel']['tmp_name']), "utf-8", "gbk");
            $data = explode("\n", trim($data0));
            $this->load->model('template_msg');
            $this->load->model('wxtemplate');

            foreach ($data as $key => $val) {
                if ($key < 1)continue;
                $line_list = explode("\t", $val);
                $temp = array();
                $order_id = $temp['id'] = $line_list[0];
                $temp['goods_name'] = substr($line_list[11],0,strpos($line_list[11],','));
                $temp['express'] = intval($line_list[12]);
                $temp['express_num'] = $line_list[13];

                $order = $this->db->get('SELECT id,mid,order_sn,area,address,extension_code,common_id,status_shipping FROM ###_goods_order where id = ' . $order_id);
                if($temp['id'] && $temp['express'] && $temp['express_num'] && $order['status_shipping']==0){
                    $res = $this->db->update("goods_order",array("express"=>$temp['express'],"express_num"=>$temp['express_num'],'shipping_time' => RUN_TIME),array("id"=>$temp['id']));
                    if($res!==false){
                        $msg = '发货操作';
                        $res = $this->order->chageOrderState($temp['id'], array('status_shipping' => 2), $msg);

                        // 模版消息 15 卖家已发货 {插入订单号},{插入商品标题},{插入快递公司},{插入快递单号}
                        // template_msg_action start
                        $orderNames = $temp['goods_name'];
                        $expressName = $this->db->getstr('SELECT name FROM ###_express WHERE id=' . $temp['express']);
                        $msgParams = array($order['order_sn'], $orderNames, $expressName, $temp['express_num']);
                        $msg_data[] = array(
                            0=>15,
                            1=>$order['mid'],
                            2=>$msgParams
                        );
                        //$this->template_msg->inQueue(15, $order['mid'], $msgParams);
                        // template_msg_action end

                        // 微信模版消息 order_ship 发货
                        // wxtemplate_action start
                        $wxmsgParams = array(
                            "url" => RootUrl."member/order_ship/".$order['order_sn'],
                            "keyword1"=>$order['order_sn'],
                            "keyword2"=>$expressName,
                            "keyword3"=>$temp['express_num'],
                            "keyword4"=>$order['area'].$order['address'],
                        );
                        $wxmsg_data[] = array(
                            0=>$order['mid'],
                            1=>'order_ship',
                            2=>$wxmsgParams
                        );
                        //$this->wxtemplate->inQueue($order['mid'],'order_ship',$msgParams);
                        // wxtemplate_action end

                        //AA团只发货一次
                        if($order['extension_code']==CART_AA){
                            $this->db->update('goods_order', array(
                                'status_shipping'=> 2,
                                'express'       => $temp['express'],
                                'express_num'   => $temp['express_num'],
                                'shipping_time' => RUN_TIME,
                            ), array('common_id' => $order['common_id']));
                            /*$list = $this->db->select("select mid from ###_goods_order where common_id={$order['common_id']}");
                            $data = array();
                            foreach($list as $k=>$v){
                                if($v['mid']==$order['mid'])continue;
                                $_temp[0] = 15;
                                $_temp[1] = $v['mid'];
                                $_temp[2] = $msgParams;
                                $data[] = $_temp;
                            }
                            $this->template_msg->inQueueMany($data);*/
                        }

                    }
                }
            }
            if(count($msg_data)>0)$this->template_msg->inQueueMany($msg_data);
            if(count($wxmsg_data)>0)$this->wxtemplate->inQueueMany($wxmsg_data);
            if ($res > 0) {
                $this->tip("导入成功", array('inIframe' => true, 'type' => 1));
                $this->exeJs("parent.com.xhide();parent.main.refresh()");
            } else {
                $this->tip("导入失败", array('inIframe' => true, 'type' => 1));
                $this->exeJs("parent.com.xhide();parent.main.refresh()");
            }
        }
        $template = "manage/order/order_send_excel.html";
        $this->smarty->display($template);
    }
}