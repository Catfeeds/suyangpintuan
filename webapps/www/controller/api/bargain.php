<?php

/**
 * 助力接口
 */
class bargain extends Lowxp {

	function __construct() {
		parent::__construct();
		$isLogin = isset($_SESSION['mid']);
		if ($isLogin) {
			$this->load->model('member');
			$this->load->model('bargain');
			$this->memberinfo = $this->member->member_info($_SESSION['mid']);
			defined('MID') || define('MID', $_SESSION['mid']);
			defined('USER') || define('USER', $this->memberinfo['username']);
		} else {
			$this->api_result(array('flag' => false, 'msg' => '请登录', 'code' => 100001));
		}
	}

	function index() {

		$option['page'] = empty($_GET['page']) ? 1 : $_GET['page'];
		$option['size'] = empty($_GET['size']) ? 10 : $_GET['size'];

		$option['where'] = " and status=1 and stock>0 and e_time>=" . RUN_TIME;
		$list            = $this->bargain->getList($option);

		if ($list) {
			foreach ($list as $key => $val) {
				if ($val['goods_sp_val']) {
					$val['goods_sp_val'] = json_decode($val['goods_sp_val'], true);
					$sp_ids              = join(',', array_keys($val['goods_sp_val']));
					$sp_arr              = $this->db->select("select * from ###_goods_spec where id in ($sp_ids)");

					$specs = $this->db->select('SELECT spec,price,thumb FROM `###_goods_item` WHERE goods_id=' . $val['goods_id']);

					$specs               = api_imgurl($specs, array('thumb'));
					$list[$key]['specs'] = $specs;

					$sp_val = array_combine(array_column($sp_arr, 'id'), array_column($sp_arr, "name"));
					foreach ($val['goods_sp_val'] as $k => $v) {
						$list[$key]['sp_vals'][$sp_val[$k]] = $v;
					}

					$list[$key]['log_id'] = $this->db->getstr("select id from ###_bargain_log where bargain_id={$val['id']} and e_time>" . RUN_TIME . " and status=0 and mid=" . MID, "id");

				}
			}
		}

		$data['list'] = $list;

		$this->api_result(array('data' => $data));
	}

	//生成砍价记录
	function bargain_apply() {
		$post['bargain_id'] = $_POST['bargain_id'];
		$post['spec']       = $_POST['spec'];
		$res                = $this->bargain->bargain_log_save($post);
		$this->api_result(array('flag' => empty($res['code']), 'msg' => $res['message'], 'code' => $res['code'], 'data' => isset($res['id']) ? $res['id'] : 0));
	}

	function show() {

		$id = get('id');
		if (!$id) {
			$this->api_result(array('flag' => false, 'msg' => '活动不存在'));
		}

		$data['log']       = $this->bargain->getLogOne($id);
		$data['row']       = $this->bargain->getOne($data['log']['bargain_id']);
		$data['help_list'] = $this->bargain->getHelpLogList($id);

		//获取商家信息
		if ($data['row']['goods']['sid'] > 0) {
			$this->load->model("business");
			$store['info'] = $this->business->get($data['row']['goods']['sid'], "name,logo,fav_num");
			//统计商品数量
			$store['goods_total'] = $this->db->getstr("select count(1) as num from ###_goods where is_sale=1 and sid=" . $data['row']['goods']['sid'], "num");
			//统计销量
			$store['sell_total'] = $this->db->getstr("select sum(sell+sales_num) as num from ###_goods where is_sale=1 and sid=" . $data['row']['goods']['sid'], "num");
			//判断是否已关注
			$store['is_fav'] = $this->db->get("select 1 from ###_business_fav where sid={$data['row']['goods']['sid']} and mid=" . MID);
			$data['store']   = $store;
		}

		//判断是否已经帮忙砍过
		$log_mid_list            = array_combine(array_column($data['help_list'], "mid"), array_column($data['help_list'], "username"));
		$data['has_bargain']     = isset($log_mid_list[MID]) ? 1 : 0;
		$data['log']['username'] = $this->db->getstr("select username from ###_member where mid=" . $data['log']['mid'], "username");
		$data['log']['avatar']   = photo($data['log']['mid']);

		//砍价排行榜
		$data['bargain_num'] = $page['count'] = $this->db->getstr("select count(1) as num from ###_bargain_log where goods_id={$data['row']['goods_id']} ");
		// $data['bargain_sort'] = $this->bargain_sort($data['row']['goods_id']);

		//砍价公告
		$data['bargain_note'] = $this->db->select("select mid,goods_id,last_price from ###_bargain_log where status=1 order by id desc limit 10");
		$data['bargain_note'] = $this->db->lJoin($data['bargain_note'], 'member', "mid,username", "mid", "mid");
		$data['bargain_note'] = $this->db->lJoin($data['bargain_note'], 'goods', "id,name", "goods_id", "id");

		$data['end_time'] = $data['row']['e_time'] > RUN_TIME ? ($data['row']['e_time'] - RUN_TIME) : 0;

		$data['text'] = empty($data['row']['description']) ? '我在' . C('site_name') . '发现一件好货' : $data['row']['description'];
		$data['text'] .= '，帮我砍到' . $data['row']['last_price'] . '元吧！！！';

		$data['mid'] = MID;

		//商品标签 包邮 7天退换 48小时发货
		if ($data['row']['goods']['goods_tip']) {
			$this->load->model("goods_tag");
			$tags = $this->goods_tag->getList(array("where" => " AND id in({$data['row']['goods']['goods_tip']})"));
			if ($tags) {
				$data['row']['goods']['goods_tip_list'] = array_column($tags, "name");
			}

		}
		//自提方式
		if ($data['row']['goods']['take_address']) {
			$this->load->model("take_address");
			$data['row']['goods']['take_address'] = $this->take_address->selectBySid($data['row']['goods']['sid'], " and id in ({$data['row']['goods']['take_address']})");
		}

		if ($data['row']['keywords']) {
			$data['row']['title'] = $data['row']['keywords'];
		}

		if (!empty($data['row']['e_time'])) {
			$data['row']['e_time_str'] = date('Y-m-d H:i:s', $data['row']['e_time']);
		}

		$this->api_result(array('data' => $data));
	}

	//帮忙砍价
	function bargain_help() {
		$log_id = $_POST['log_id'];
		$res    = $this->bargain->bargain_help($log_id);
		$this->api_result(array('data' => $res));
	}

	//我的砍价
	function log_list() {
		$option['page']  = get('page') ?: 1;
		$option['size']  = get('size') ?: 10;
		$option['where'] = " and mid=" . MID;
		$list            = $this->bargain->getLogList($option);

		$data['list'] = $list;

		$this->api_result(array('data' => $data));
	}

	//砍价下单
	function bargain_cart() {

		$log_id = $_REQUEST['log_id'];
		$row    = $this->bargain->getLogOne($log_id);
		if ($row['status'] != 1) {
			$this->api_result(array('flag' => false, 'code' => 100001, 'msg' => '砍价未成功！'));
		}
		if ($row['order_id'] > 0) {
			$this->api_result(array('flag' => false, 'code' => 100001, 'msg' => '该砍价已下单！'));
		}
		$cart_new = array(
			'goods_id'     => $row['goods_id'],
			'market_price' => $row['price'],
			'goods_price'  => $row['last_price'],
			'spec'         => $row['spec'],
			'qty'          => 1,
			'subtotal'     => formatPrice($row['last_price']),
			'log_id'       => $row['id'],
			'type'         => CART_GOODS,
		);
		$_SESSION['cart'] = $cart_new;

		$this->api_result();
	}

	//砍价排行榜列表
	function bargain_sort($goods_id = 0, $page = 1) {
		$goods_id        = get('goods_id');
		$option['page']  = get('page') ?: 1;
		$option['size']  = get('size') ?: 10;
		$option['where'] = " and goods_id=" . $goods_id;
		$option['order'] = " bargain_price desc,id asc";
		$list            = $this->bargain->getLogList($option);

		$data['list'] = $list;

		$this->api_result(array('data' => $data));
	}

}