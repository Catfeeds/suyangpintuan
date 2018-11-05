<?php

/**
 * Class welcome
 */
class mall extends Lowxp {

	/**
	 * IndexAction
	 */
	function home() {
		#广告专题列表
		$this->load->model('wxsite');
		$rows = $this->wxsite->getAdItems(array('setlink' => true));
		$this->smarty->assign('list', $rows);
		#轮播图列表
		$sliders = $this->db->select("SELECT * FROM wxsite_slider");
		$this->load->model('upload');
		$sliders = $this->upload->getSliderImgUrls($sliders);

		#首页商品展示.
		$goods    = $this->db->select("SELECT id,`name`,`desc`,cover,price,real_price,cate FROM goods WHERE stock>0 AND shelf_flag=1");
		$goods    = $this->upload->getImgUrls($goods, 'cover', 'goods', array('src', 'thumb'));
		$caterows = $this->db->select("SELECT * FROM product_cate WHERE `parent`=0");
		$cates    = array_column($caterows, 'name', 'id');
		$GoodsA   = array();
		$GoodsB   = array();
		foreach ($goods as $val) {
			if ($val['cate'] < 2) {
				$GoodsA[] = $val;
			} else {
				$GoodsB[$val['cate']][] = $val;
			}
		}

		$this->smarty->assign('cates', $cates);
		$this->smarty->assign('goodsa', $GoodsA);
		$this->smarty->assign('goodsb', $GoodsB);

		#echo '<pre>';print_r($sliders);echo '</pre>';
		$this->smarty->assign('sliders', $sliders);
		$this->smarty->display('home/mall/home.html');
	}

	function good_list() {
		$where = 'id <> 0';
		if ($_GET['cate']) {
			$cate_id     = intval($_GET['cate']);
			$child_arr   = $this->db->select("SELECT id FROM product_cate WHERE parent = '$cate_id'");
			$child_cat[] = $cate_id;
			if ($child_arr) {
				foreach ($child_arr as $key => $val) {
					$child_cat[] = $val['id'];
				}
			}
			$child_cat = implode(',', $child_cat);
			$where .= " AND cate IN ($child_cat)";
			//父级ID
			$top_parent = $this->db->get("SELECT parent FROM product_cate WHERE id = '$cate_id'");
			$top_cate   = $top_parent['parent'] ? $top_parent['parent'] : $cate_id;
			$this->smarty->assign('top_cate', $top_cate);
		}
		$sort_arr = array('price', 'sell');
		$sort     = $_GET['sort'] ? trim($_GET['sort']) : 'price';
		if (in_array($sort, $sort_arr)) {
			$order = "$sort DESC";
		}

		$this->smarty->assign('sort', $sort);
		$goods = $this->db->select("SELECT * FROM goods WHERE $where ORDER BY $order");

		$this->modTree();

		$this->load->model('upload');
		$goods = $this->upload->getImgUrls($goods, 'cover', 'goods', array('thumb'));

		$this->smarty->assign('goods', $goods);
		$this->smarty->display('home/mall/list.html');
	}
	/**
	 * 分类树
	 */
	function modTree() {
		$rows  = $this->db->select("SELECT * FROM product_cate ORDER BY `order`,id");
		$items = array();
		foreach ($rows as $val) {
			$items[$val['id']] = $val;
		}

		foreach ($items as $item) {
			if ($item['parent']) {
				$items[$item['parent']]['son'][$item['id']] = &$items[$item['id']];
				unset($items[$item['id']]);
			}
		}
		$this->smarty->assign('tree', $items);
	}

	/**
	 * 设置沃销价格.
	 * 按会员提供的方案进行减价.
	 * @param $goods
	 * @param $goodsItem
	 */
	function setSalePrice(&$goods, &$goodsItem) {
		isset($_GET['sale_id']) && isset($_GET['saler']);
		$sale_id = intval($_GET['sale_id']);
		$saler   = intval($_GET['saler']);

		$memSale = $this->db->get("SELECT * FROM member_promo_sale WHERE sale_id=" . $sale_id . " AND mid=" . $saler . "");
		if (!isset($memSale['id'])) {
			die('沃销不存在!');
		}

		if (!isset($_GET['preview'])) {
			if ($memSale['flag'] != 1) {
				die('无效的沃销!');
			}

		}

		$scheme = json_decode($memSale['scheme'], true);
		if (!isset($scheme['discount'])) {
			die('沃销设置错误!');
		}

		$discount = floatval($scheme['discount']);

		foreach ($goodsItem as $k => $v) {
			if (strpos($k, 'price-') !== false) {
				$goodsItem[$k] = $v - $discount;
			}
		}

		$goods['real_price'] = $goods['real_price'] - $discount;

		$this->smarty->assign('sale_id', $sale_id);
		$this->smarty->assign('saler', $saler);

	}

	/**
	 * 商品详情
	 * @param string $id
	 */
	function detail($id = '') {
		if (!is_numeric($id)) {
			die('Error!');
		}

		$goods = $this->db->get("SELECT * FROM goods WHERE id = " . $id);

		#商品规格
		$goods['sp_val'] = json_decode($goods['sp_val'], true);
		#商品参数
		$goods['params'] = json_decode($goods['params'], true);

		$specs = is_array($goods['sp_val']) ? array_keys($goods['sp_val']) : array();

		$sp       = $this->db->select("SELECT * FROM goods_spec WHERE id IN(" . implode(',', $specs) . ")");
		$specList = array_column($sp, 'name', 'id');
		$this->smarty->assign('sp_names', $specList);

		#商品图片
		$goods['pics'] = json_decode($goods['pics'], true);
		$picdata       = array();
		if (is_array($goods['pics'])) {
			foreach ($goods['pics'] as $v) {
				$picdata[] = array('id' => $v);
			}

			$this->load->model('upload');
			$goods['pics'] = $this->upload->getGalleryImgUrls($picdata, 'goods');
		} else {
			$goods['pics'] = array('', '', '', '', '', '');
		}

		#商品每个规格的库存,价格,序列号
		$items     = $this->db->select("SELECT * FROM goods_item WHERE goods_id=" . $id);
		$goodsItem = array();
		foreach ($items as $val) {
			$goodsItem['price-' . $val['spec']]  = $val['price'];
			$goodsItem['stock-' . $val['spec']]  = $val['stock'];
			$goodsItem['serial-' . $val['spec']] = $val['serial'];
		}

		#[设置沃销价格]
		if (isset($_GET['sale_id']) && isset($_GET['saler'])) {
			$this->setSalePrice($goods, $goodsItem);
		}

		$this->smarty->assign('goodsItem', json_encode($goodsItem));

		#商品评价
		$evals = $this->db->select("SELECT * FROM goods_order_evaluate WHERE goods_id=" . $id . " ORDER BY id DESC");
		$evals = $this->db->lJoin($evals, 'member', 'mid,nickname', 'mid', 'mid');
		$this->smarty->assign('evals', $evals);

		$this->smarty->assign('good', $goods);
		$this->smarty->display('home/mall/detail.html');
	}

	//todo:当调整库存时,检查购物车中购买的商品数量是否缺货.

	/**
	 * 加入购物车
	 * note:购物车页面设置商品数量使用方法:upCartGoodNum直接设置商品数量,
	 * note:其他商品加入购物车使用累加购买数量方法
	 * @param int $goods_id   商品ID
	 * @param string $spec    商品规格
	 * @param int $buy_num    购买数量,为负数时,表示减少购物车中的数量.
	 * @param string $from    商品类型,group:团折,cheap:超级特惠
	 * @param string $from_id 对应商品类型的ID,团折ID/超级特惠ID
	 */
	private function checkCart($goods_id, $spec, $buy_num = 0, $from = 'normal', $from_id = '0') {

		if ($spec == '') {
			return;
		}

		$this->load->model('goods');
		$spec = $this->goods->spec_format($spec);

		//检查库存.
		$ck1        = $this->db->get("SELECT sum(buy_num) as cart_buy FROM shoping_cart WHERE mid=" . MID . " AND good_id=" . $goods_id . " AND spec='" . $spec . "'");
		$cartBuyNum = $ck1['cart_buy']; //购物车已添加该规格商品总数

		$ck2 = $this->db->get("SELECT * FROM goods_item WHERE goods_id=" . $goods_id . " AND spec='" . $spec . "'");

		if (!isset($ck2['id'])) {
			die('该型号商品不存在!');
		}
#
		$stock = $ck2['stock']; //该规格商品总库存
		if ($cartBuyNum + $buy_num > $stock) {
			die('库存不足!');
		}

		//检查购物车是否已有该商品,有则更新数量,无则新建.
		$conds = "mid=" . MID . " AND good_id=" . $goods_id . " AND spec='" . $spec . "'";
		$conds .= " AND `from`='" . $from . "'";
		if ($from_id != '0') {
			$conds .= " AND from_id=" . $from_id;
		}

		$cart = $this->db->get("SELECT * FROM shoping_cart WHERE " . $conds);

		//购物车中已经有此物品.
		if (isset($cart['id'])) {

			switch ($from) {
				//todo:特惠限量管理,总的限量.
				case 'cheap': #检查超级特惠是否可加入购物车.
					$cheap = $this->db->get("SELECT * FROM goods_cheap WHERE id=" . $from_id . " AND is_show=1 AND end_time>" . RUN_TIME);
					if (!isset($cheap['id'])) {
						die('超级特惠已下架!');
					}

					#检查会员是否购买过该限购产品（从订单记录中查询）
					$rows            = $this->db->select("SELECT b.status,a.buy_num FROM goods_order_item a LEFT JOIN goods_order b b.id=a.order_id WHERE a.mid=" . MID . " AND from='cheap' AND from_id=" . $from_id);
					$already_buy_num = 0;
					if (count($rows)) {
						foreach ($rows as $val) {
							if ($val['status'] == '5') {
								continue;
							}
#已取消的订单不计入统计.
							$already_buy_num += $val['buy_num'];
						}
						if ($already_buy_num > $cheap['user_buy_limit']) {
							die('该款产品限购,你的历史订单中已购买过该产品!');
						}

					}
					#$hasBuy = $this->db->get("SELECT * FROM goods_cheap_status WHERE cheap_id=".$from_id." AND mid=".MID);
					//查看是否可以购买.扣除之前购买的数量,限购.
					//todo:检查未付款订单中的超级特惠商品是否存在.
					$canBuyNum = $cheap['user_buy_limit'] - $already_buy_num;
					if ($canBuyNum < 1) {
						die('该款产品限购,还可购买' . $canBuyNum . '件!');
					}

					if ($cart['buy_num'] + $buy_num > $canBuyNum) {
						die('该款产品限购,只可购买' . $canBuyNum . '件!');
					}

					break;
				case 'group': #团折.
					break;
				case 'sale': #沃销
					break;
				case 'normal':break;
			}

			$buy_num = $cart['buy_num'] + $buy_num;

			$this->db->update('shoping_cart', array('buy_num' => $buy_num), array('id' => $cart['id']));
			#该商品库存只有$stock.
		} else {
			$this->load->model('mall');
			$rule = $this->mall->getSpecNames($goods_id, $spec);

			//根据spec查询参数.
			$this->db->insert('shoping_cart', array(
				'mid'     => MID,
				'good_id' => $goods_id,
				'rule'    => $rule,
				'spec'    => $spec,
				'buy_num' => $buy_num,
				'from'    => $from,
				'from_id' => $from_id,
				'c_time'  => RUN_TIME,
			));
		}

		echo '0';
	}

	/**
	 * 加入购物车
	 * 未登录时用COOKIE做标记,在登录时同步至用户账号.
	 * 已登录则加入购物车表
	 * @param $id
	 */
	function joincart($id) {
		#$params = $_POST['params'];
		$spec    = $_POST['spec']; #产品规格.(颜色:黑色,版本:16G)
		$buy_num = intval($_POST['buy_num']);

		if (!defined('MID')) {
			//加入cookie购物车
			return;
		}
		is_numeric($id) || die;

		if (!empty($_POST['sale_id']) && !empty($_POST['saler'])) {

			$promoSale = $this->db->get("SELECT * FROM goods_promo_sale WHERE id=" . $_POST['sale_id'] . " AND end_time>" . RUN_TIME);
			if (!isset($promoSale['id'])) {
				die('沃销已过期!');
			}

			$memSale = $this->db->get("SELECT * FROM member_promo_sale WHERE sale_id=" . $_POST['sale_id'] . " AND mid=" . $_POST['saler']);
			if (!isset($memSale['id'])) {
				die('沃销不存在!');
			}

			if ($memSale['flag'] != '1') {
				die('沃销未发布!');
			}

			$this->checkCart($id, $spec, $buy_num, 'sale', $memSale['id']);
		} else {
			$this->checkCart($id, $spec, $buy_num, 'normal');
		}
	}

	/**
	 * 立即购买
	 * @param $id
	 */
	function buynow($id) {
		$this->joincart($id);
	}

	/**
	 * 添加团折产品到购物车.
	 */
	function joinGrpCart() {
		//限购和团折产品检查.

		//加入购物车时,需要将现有库存和添加的数量累加.
		//修改购物车数量时,是直接更新购物车商品的数量.
		isset($_POST['group_id']) && is_numeric($_POST['group_id']) && isset($_POST['goods_id']) && is_numeric($_POST['goods_id']) || die;

		$goodsGrpId = $_POST['group_id'];
		$prmGrp     = $this->db->get("SELECT * FROM goods_promo_group WHERE id=" . $goodsGrpId . " AND end_time>" . RUN_TIME . " AND is_show=1");
		if (!isset($prmGrp['id'])) {
			die('团折已下架!');
		}

		foreach ($_POST['buy_num'] as $spec => $buy_num) {
			$this->checkCart($_POST['goods_id'], $spec, $buy_num, 'group', $_POST['group_id']);
		}
	}

	/**
	 * 添加超级特惠到购物车
	 */
	function joinCheapCart() {
		isset($_POST['cheap_id']) && is_numeric($_POST['cheap_id']) || die;
		isset($_POST['goods_id']) && is_numeric($_POST['goods_id']) || die;
		isset($_POST['spec']) || die;
		$goods_id = $_POST['goods_id'];
		$cheap_id = $_POST['cheap_id'];
		$this->checkCart($goods_id, $_POST['spec'], 1, 'cheap', $cheap_id);
	}

	/**
	 * 查看购物车
	 */
	function cart() {
		#如果没登录,提示登录,才能继续下一步的购买.
		$this->load->model('mall');
		$items      = $this->mall->getCartList(MID);
		$totalPrice = $this->mall->getTotalPrice($items);
		$this->smarty->assign('totalPrice', $totalPrice);

		$this->smarty->assign('list', $items);

		$this->smarty->display('home/mall/cart.html');
	}

	/**
	 * 更新购物车产品数量
	 */
	function upCartGoodNum() {
		$cart_id  = intval($_POST['cart_id']);
		$toNum    = intval($_POST['buy_num']);
		$cartGood = $this->db->get("SELECT * FROM shoping_cart WHERE id=" . $cart_id . " AND mid=" . MID);
		if (!isset($cartGood['id'])) {
			die('商品不存在!');
		}

		$addNum = $toNum - $cartGood['buy_num'];
		$this->checkCart($cartGood['good_id'], $cartGood['spec'], $addNum, $cartGood['from'], $cartGood['from_id']);
	}

	function delCartGood($id) {
		is_numeric($id) || die;

		$row = $this->db->get("SELECT * FROM shoping_cart WHERE id=" . $id . " AND mid=" . MID);
		if (isset($row['id'])) {
			$this->db->delete('shoping_cart', array('id' => $row['id']));
		}
	}

	/**
	 * 订单确认
	 */
	function order_confirm() {
		//查看是否有收货地址,如果没有收货地址,直接跳转到新建收货地址.
		$deliver = $this->getDeliver();
		if (!isset($deliver['id'])) {
			header('Location: /xdeliver/add?callback=' . urlencode('/xmall/order_confirm'));
			die;
		}
		$this->smarty->assign('deliver', $deliver);

		//快递类型
		$express = $rows = $this->db->select("SELECT * FROM express");
		$this->smarty->assign('express', $express);

		//查询出会员的优惠券.
		$userCps = $this->db->select("SELECT a.id,a.coupon_id,b.exchange_money,b.title FROM member_coupon a LEFT JOIN coupon b ON b.id=a.coupon_id WHERE a.mid=" . MID . " AND a.status=0 AND b.end_time>" . RUN_TIME);
		$this->smarty->assign('coupons', $userCps);

		//查看会员还有多少积分
		$credit = $this->db->get("SELECT * FROM member WHERE mid=" . MID);
		if (!isset($credit['mid'])) {
			die('会员不存在!');
		}

		$this->smarty->assign('credit', $credit['credit']);

		$this->load->model('mall');
		$items      = $this->mall->getCartList(MID);
		$totalPrice = $this->mall->getTotalPrice($items);
		$this->smarty->assign('totalPrice', $totalPrice);

		$this->load->model('config');
		$ratios = array(
			'gain_credit' => $this->mall->statisticPoint(MID, $totalPrice),
			'credit'      => $this->config->get('credit_ratio'), #会员的积分获得率
			'vip_credit'  => $this->config->get('xvip_credit_ratio_default'), #超级会员的积分获得率
			'vip_credit2' => $this->config->get('xvip_credit_ratio_2000'), #超级会员多于2千时的积分获得率
			'use_credit'  => $this->config->get('credit_money_ratio'), //使用积分抵用钱款的比率.
		);

		$member = $this->db->get("SELECT mid,xvip FROM member WHERE mid=" . MID);
		$this->smarty->assign('is_vip', $member['xvip'] == 1 ? '1' : '0');
		$this->smarty->assign('ratio', $ratios);

		if (count($items) == 0) {
			header('Location: /xmall/good_list');
			exit; #die('请先选择要付款的商品.');
		}
		$this->smarty->assign('list', $items);

		$this->smarty->display('home/mall/order_confirm.html');
	}

	/**
	 * 获取收货地址.
	 * @param string $deliver_id
	 * @return array|null
	 */
	private function getDeliver($deliver_id = '') {
		if (is_numeric($deliver_id)) {
			$conds = ' AND id=' . $deliver_id;
		} else {
			$conds = ' AND is_default=1';
		}
		//读取默认的收货地址,并拼接成字符串.
		$deliver = $this->db->get("SELECT * FROM member_deliver_addr WHERE mid=" . MID . $conds);
		if (!isset($deliver['id'])) {
			return $deliver;
		}

		$delivers = array($deliver);
		$delivers = $this->db->lJoin($delivers, 'zone', 'id,name', 'prov_id', 'id', 'prov_');
		$delivers = $this->db->lJoin($delivers, 'zone', 'id,name', 'city_id', 'id', 'city_');
		$delivers = $this->db->lJoin($delivers, 'zone', 'id,name', 'district_id', 'id', 'district_');
		$deliver  = $delivers[0];
		return $deliver;
	}

	/**
	 * 创建订单前检查各项参数是否有效.
	 */
	function _checkCreateOrder() {

		isset($_POST['express']) || die('Err1');
		is_numeric($_POST['express']) || die('Err6');
		isset($_POST['paytype']) || die('Err2');
		in_array($_POST['paytype'], array('1', '2', '3', '4')) || die('Err5');
		isset($_POST['cartids']) || die('Err3');
		is_array($_POST['cartids']) || die('Err4');

		//检查会员使用的优惠券是否存在并有效.
		if (!empty($_POST['coupon'])) {
			$couponArr        = explode('|', $_POST['coupon']);
			$member_coupon_id = $couponArr[0];

			$userCoupon = $this->db->get("SELECT * FROM member_coupon WHERE mid=" . MID . " AND id=" . $member_coupon_id . " AND is_used=0");

			if (!isset($userCoupon['mid'])) {
				die('优惠券不存在!');
			}

			$coupon = $this->db->get("SELECT * FROM coupon WHERE id=" . $userCoupon['coupon_id'] . " AND end_time>" . RUN_TIME);
			if (!isset($coupon['id'])) {
				die('优惠券已过期');
			}

		}

		//检查使用的积分是否足够
		if (isset($_POST['use_credit']) && is_numeric($_POST['use_credit'])) {
			$member = $this->db->get("SELECT * FROM member WHERE mid=" . MID);
			if ($member['credit'] < $_POST['use_credit']) {
				die('积分不足!');
			}

		}

	}

	/**
	 * 创建订单,并且跳转到相对应的支付方式.
	 */
	function create_order() {

		$this->_checkCreateOrder();

		$cartids = implode(',', $_POST['cartids']);
		if (empty($cartids)) {
			die('订购产品为空!');
		}

		//读取购物车中的订单列表
		$this->load->model('mall');
		$items = $this->mall->getCartListByIds(MID, $cartids);
		if (count($items) == 0) {
			die('该产品已生成订单!');
		}
#购物车为空.
		$totalPrice = $this->mall->getTotalPrice($items);

		//获取当前选择的收货地址
		$addr = $this->getDeliver();
		//先检查优惠券是否存在,检查积分是否足够.

		//创建订单记录
		$this->db->insert('goods_order', array(
			'mid'     => MID,
			'note'    => $_POST['message'],
			'deliver' => json_encode($addr),
			'express' => $_POST['express'],
			'c_time'  => RUN_TIME,
		));
		$order_id   = $this->db->insert_id();
		$totalPrice = $this->mall->useCoupon(MID, $totalPrice); #使用优惠券后的总价.
		$totalPrice = $this->mall->useCredit(MID, $totalPrice, $order_id); #使用积分后的总价.

		$credit = $this->mall->countGainCredit(MID, $totalPrice);

		//更新订单金额.
		$this->db->update('goods_order', array(
			'order_price' => $totalPrice, #订单最终金额
			'credit'      => $credit,
		), array('id' => $order_id));

		//生成订单号.
		$printfid = sprintf("%08d", $order_id);
		$orderNum = rand(1000, 9999) . $printfid . rand(100, 999);
		$this->db->update('goods_order', array('order_sn' => $orderNum), array('id' => $order_id));

		//创建订单详细产品列表
		foreach ($items as $val) {
			$from_id = $val['from_id'];
			if ($val['from'] == 'sale') {
				#推销者,推销ID,推销方案价,购买者,购买产品金额和数量,购买时间
				#记录沃销卖的产品和用户.
				$ms = $this->db->get("SELECT * FROM member_promo_sale WHERE id=" . $val['from_Id'] . " AND flag=1");
				if (!isset($ms)) {
					die('推广的沃销已下架!');
				}
#需要事务机制:commit,roll_back
				$this->db->insert('goods_promo_sale_status', array(
					'goods_id' => $val['good_id'],
					'buy_num'  => $val['buy_num'],
					'price'    => $val['good_real_price'],
					'buy_mid'  => MID,
					'sale_id'  => $ms['sale_id'],
					'saler'    => $ms['mid'],
					'scheme'   => $ms['scheme'],
					'order_id' => $order_id,
					'c_time'   => RUN_TIME,
				));
				$from_id = $this->db->insert_id();
			}

			$this->db->insert('goods_order_item', array(
				'order_id'   => $order_id,
				'mid'        => $val['mid'],
				'rule'       => $val['rule'],
				'spec'       => $val['spec'],
				'buy_num'    => $val['buy_num'],
				'good_id'    => $val['good_id'],
				'from'       => $val['from'],
				'from_id'    => $from_id,
				'sell_price' => $val['good_real_price'],
			));
		}

		//删除购物车中的订单.
		$this->db->query("DELETE FROM shoping_cart WHERE mid=" . MID . " AND id IN(" . $cartids . ")");
		$this->exeJs('location.href="/xorder/payment/' . $order_id . '";');
	}

}