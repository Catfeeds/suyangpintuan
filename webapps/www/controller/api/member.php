<?php

/**
 * 会员中心接口
 */
class member extends Lowxp {

	function __construct() {
		parent::__construct();
		$isLogin = isset($_SESSION['mid']);
		if ($isLogin) {
			$this->load->model('member');
			$this->load->Model('linkage');
			$this->load->model("refund");
			$this->load->model('order');
			$this->load->model("goods");
			$this->memberinfo = $this->member->member_info($_SESSION['mid']);
			define('MID', $_SESSION['mid']);
			define('USER', $this->memberinfo['username']);
		} else {
			$this->api_result(array('flag' => false, 'msg' => '请登录', 'code' => 400001));
		}
	}

	/**
	 * 会员中心未读信息和所有状态订单的数量
	 */
	public function index() {
		$member                 = $this->member->member_info(MID);
		$member['photo']        = empty($member['photo']) ? "common/photo.gif" : $member['photo'];
		$member['photo']        = strpos($member['photo'], "://") !== false ? $member['photo'] : yunurl($member['photo']);
		$data['member']         = $member;
		$data['member']['city'] = '';
		if ($member['zone'] > 0) {
			$data['member']['city'] = $this->db->getstr("select name from ###_linkage where id={$member['zone']}", "name");
		}

		// 登录时将所有的未读消息写入到引用表 这样不活跃的用户就没有消息引用 减少数据压力
		$this->load->model('message');
		$this->message->getUnRead(MID);

		#未读消息
		$data['msgUnreadCount'] = $this->db->getstr('SELECT count(*) as count FROM `###_message_status` WHERE status=1 AND mid = ' . MID);
		#可用佣金和总佣金
		$data['commission']       = $this->memberinfo['commission'];
		$data['commission_total'] = $this->memberinfo['commission_total'];
		//计算佣金
		$start_time             = strtotime(date('Y-m-01', strtotime('-1 month'))); //上个月月初
		$end_time               = strtotime(date('Y-m-t', strtotime('-1 month'))); //上个月月末
		$data['pre_commission'] = $this->db->getstr("select sum(commission) as pre_commission from ###_commission where status=0 and addtime>=$start_time and addtime<=$end_time and mid=" . MID, "pre_commission");
		$now_time               = strtotime(date('Y-m-01', RUN_TIME)); //本月月初
		$data['now_commission'] = $this->db->getstr("select sum(commission) as now_commission from ###_commission where status=0 and addtime>=$now_time and mid=" . MID, "now_commission");

		#待付款
		$where            = $this->order->order_status(100, '', 1);
		$data['dfkCount'] = (int) $this->db->getstr("SELECT count(*) FROM ###_goods_order WHERE " . ($where ? $where : '1') . " AND mid='" . MID . "'");
		#待发货
		$where            = $this->order->order_status(101, '', 1);
		$data['dfhCount'] = (int) $this->db->getstr("SELECT count(*) FROM ###_goods_order WHERE " . ($where ? $where : '1') . " AND mid='" . MID . "'");
		#待收货
		$where            = $this->order->order_status(102, '', 1);
		$data['dshCount'] = (int) $this->db->getstr("SELECT count(*) FROM ###_goods_order WHERE " . ($where ? $where : '1') . " AND mid='" . MID . "'");
		#待评价
		$where = $this->order->order_status(110, '', 1);
		$where .= " and is_rate = 0";
		$data['dpjCount'] = (int) $this->db->getstr("SELECT count(*) FROM ###_goods_order WHERE " . ($where ? $where : '1') . " AND mid='" . MID . "'");
		#退款\售后
		$data['tkCount'] = (int) $this->db->getstr("SELECT count(*) FROM ###_refund WHERE  mid='" . MID . "'");

		//助力成功
		$this->load->model('assist');
		$assist_id = get('assist_id') ?: cookie("assist_id");
		if ($this->assist->power == 1 && !empty($assist_id)) {
//判断是否开启
			$assist                  = $this->assist->getLogOne($assist_id);
			$log                     = $this->assist->getHelpLogOne($assist_id, MID);
			$data['assist_username'] = $assist['username'];
			$data['assist_log']      = $log;
			zzcookie('assist_id', null);
		}
		//分销开关
		$data['scomss']      = C("comss");
		$data['aboutus_url'] = urlencode(url('/content/index/23?client=xiao'));
        //积分开关
        $this->load->model('score');
        $data['open_score'] = $this->score->power;
        //大转盘开关
        $this->load->model('wheel');
        $data['open_wheel'] = $this->wheel->power;
        //签到权限
        $data['rule_1'] = $this->score->getRow(1);
		$this->api_result(array('data' => $data));
	}

	/**
	 * 收货地址
	 */
	public function addressList() {
		$mid          = MID;
		$address      = $this->db->select("select id,`name`,mobile,`zone` as city_id,area,address,is_default from ###_member_address where mid = $mid ORDER BY is_default DESC,id DESC ");
		$data['data'] = $address;
		unset($address);
		$this->api_result($data);
	}

	/**
	 * 收货地址详情
	 */
	public function addressShow() {
		$mid = MID;
		$id  = intval($_GET['id']);
		if (!$id) {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，地址id不能为空';
			$this->api_result($data);
		}
		$address                = $this->db->select("select * from ###_member_address where mid = $mid AND id=$id");
		$zone_arr               = explode('  ', $address[0]['area']);
		$address[0]['area_arr'] = $zone_arr;
		$this->api_result(array('data' => $address, 'detail' => $address[0]));
	}

	/**
	 * 获取省
	 */
	public function province() {
		$province = $this->db->select("SELECT id,`name`,child FROM ###_linkage WHERE parentid = 1");
		$this->api_result(array('data' => $province));
	}

	/**
	 * 获取市或区
	 */
	public function cityOrArea() {
		$id = isset($_GET['id']) ? trim($_GET['id']) : '';
		if (!$id) {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，父级id不能为空';
			$this->api_result($data);
		}
		$cityOrarea = $this->db->select("SELECT id,`name`,parentid,arrparentid,child FROM ###_linkage WHERE parentid = $id");
		$this->api_result(array('data' => $cityOrarea));
	}

	/**
	 * 获取地区
	 */
	public function getArea() {
		$parentid = isset($_GET['parentid']) ? trim($_GET['parentid']) : 1;
		if (!$parentid) {
			$data['flag'] = true;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，父级id不能为空';
			$this->api_result($data);
		}
		$area = $this->db->select("SELECT id,`name`,parentid,arrparentid,child FROM ###_linkage WHERE parentid = '$parentid'");
		$this->api_result(array('data' => $area));
	}

	/**
	 * 删除收货地址
	 */
	public function addressDelete() {
		$mid = MID;
		$id  = intval($_GET['id']);
		if (!$id) {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，地址id不能为空';
			$this->api_result($data);
		}
		$result = $this->db->delete('member_address', array('id' => $id, 'mid' => $mid));
		if (!$result) {
			$data['flag'] = false;
			$data['code'] = 100001;
			$data['data'] = $result;
			$data['msg']  = '删除地址失败';
		} else {
			$data['data'] = $result;
		}
		$this->api_result($data);
	}

	/**
	 * 设置默认地址
	 */
	public function addressDefault() {
		$mid = MID;
		$id  = intval($_GET['id']);
		if (!$id) {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，地址id不能为空';
			$this->api_result($data);
		}
		$this->db->update('member_address', array('is_default' => 0), array('mid' => $mid));
		$result = $this->db->update('member_address', array('is_default' => 1), array('mid' => $mid, 'id' => $id));
		if (!$result) {
			$data['flag'] = false;
			$data['code'] = 100001;
			$data['msg']  = '设置默认地址失败，请重试';
		} else {
			$data['data'] = $result;
		}
		$this->api_result($data);
	}

	/**
	 * 地址修改
	 */
	public function addressUpdate() {
		$input        = array();
		$input['id']  = intval($_POST['id']);
		$input['mid'] = MID;
		if (!$input['id']) {
			$this->api_result(array('flag' => false, 'msg' => '参数异常，地址id不能为空', 'code' => 100002));
		}

		$input['name']    = trim($_POST['name']);
		$input['address'] = trim($_POST['address']);
		$input['zip']     = isset($_POST['zip']) ? trim($_POST['zip']) : '';

		$memberinfo        = $this->member->member_info($input['mid']);
		$input['username'] = $memberinfo['username'];
		$input['mobile']   = trim($_POST['mobile']) ? trim($_POST['mobile']) : trim($memberinfo['mobile']);

		//提交区域名称获取区域
		$provinc = !empty($_POST['provinc']) ? trim($_POST['provinc']) : '';
		$city    = !empty($_POST['city']) ? trim($_POST['city']) : '';
		$county  = !empty($_POST['county']) ? trim($_POST['county']) : '';
		if (!empty($provinc) && !empty($city)) {
			$zone_arr = array($provinc, $city, $county);
			$zone     = '';
			foreach ($zone_arr as $name) {
				$parentid = !empty($zone) ? $zone : '1';
				$name     = str_replace(array('省', '市', '区'), '', $name);
				$zone     = $this->db->getstr("SELECT id FROM ###_linkage WHERE parentid = '$parentid' AND name LIKE '%{$name}%'");
				if ($zone) {
					$_POST['zone'] = $zone;
				}

			}
		}

		$input['zone'] = trim($_POST['zone']);
		if (empty($input['name'])) {
			$this->api_result(array('flag' => false, 'msg' => '参数异常，请输入收件人', 'code' => 100002));
		}

		if (empty($input['mobile'])) {
			$this->api_result(array('flag' => false, 'msg' => '参数异常，请输入手机号码', 'code' => 100002));
		}

		if (empty($input['zone'])) {
			$this->api_result(array('flag' => false, 'msg' => '参数异常，请选择配送区域', 'code' => 100002));
		}

		if (empty($input['address'])) {
			$this->api_result(array('flag' => false, 'msg' => '参数异常，请输入详细地址', 'code' => 100002));
		}

		if (!is_mobile($input['mobile'])) {
			$this->api_result(array('flag' => false, 'msg' => '参数异常，请输入正确手机号', 'code' => 100002));
		}

		$input['area'] = $input['zone'] ? $this->linkage->pos_linkage($input['zone'], false) : '';
		$input['area'] = str_replace('>', '', $input['area']);

		$this->member->member_address_save($input);
		$this->api_result(array('msg' => '修改成功'));

	}

	/**
	 * 地址添加
	 */
	public function addressAdd() {
		$this->load->model('member');
		$this->load->Model('linkage');
		$input        = array();
		$input['mid'] = MID;
		if (empty($input['mid'])) {
			$this->api_result(array('flag' => false, 'msg' => '参数异常，会员mid不能为空', 'code' => 100002));
		}

		//提交区域名称获取区域
		$provinc = !empty($_POST['provinc']) ? trim($_POST['provinc']) : '';
		$city    = !empty($_POST['city']) ? trim($_POST['city']) : '';
		$county  = !empty($_POST['county']) ? trim($_POST['county']) : '';
		if (!empty($provinc) && !empty($city)) {
			$zone_arr = array($provinc, $city, $county);
			$zone     = '';
			foreach ($zone_arr as $name) {
				$parentid = !empty($zone) ? $zone : '1';
				$name     = str_replace(array('省', '市', '区'), '', $name);
				$zone     = $this->db->getstr("SELECT id FROM ###_linkage WHERE parentid = '$parentid' AND name LIKE '%{$name}%'");
				if ($zone) {
					$_POST['zone'] = $zone;
				}

			}
		}
		$input['name']    = trim($_POST['name']);
		$input['address'] = trim($_POST['address']);
		$input['zip']     = isset($_POST['zip']) ? trim($_POST['zip']) : '';

		$memberinfo        = $this->member->member_info($input['mid']);
		$input['username'] = $memberinfo['username'];
		$input['mobile']   = trim($_POST['mobile']) ? trim($_POST['mobile']) : trim($memberinfo['mobile']);

		$input['zone'] = trim($_POST['zone']);

		if (empty($input['username'])) {
			$this->api_result(array('flag' => false, 'msg' => '参数异常，请输入收件人', 'code' => 100002));
		}

		if (empty($input['name'])) {
			$this->api_result(array('flag' => false, 'msg' => '参数异常，请输入收件人', 'code' => 100002));
		}

		if (empty($input['mobile'])) {
			$this->api_result(array('flag' => false, 'msg' => '参数异常，请输入手机号码', 'code' => 100002));
		}

		if (empty($input['zone'])) {
			$this->api_result(array('flag' => false, 'msg' => '参数异常，请选择配送区域', 'code' => 100002));
		}

		if (empty($input['address'])) {
			$this->api_result(array('flag' => false, 'msg' => '参数异常，请输入详细地址', 'code' => 100002));
		}

		if (!is_mobile($input['mobile'])) {
			$this->api_result(array('flag' => false, 'msg' => '参数异常，请输入正确手机号', 'code' => 100002));
		}

		$input['area'] = $input['zone'] ? $this->linkage->pos_linkage($input['zone'], false) : '';
		$input['area'] = str_replace('>', '', $input['area']);

		$id = $this->member->member_address_save($input);
		$address      = $this->member->get_address('id=' . $id);
		$address_list = $this->member->member_address(MID);
		$data = array('address' => $address, 'address_list' => $address_list);
		$this->api_result(array('msg' => '修改成功', 'data' => $data));
	}

	/**
	 * 我的消息
	 */
	public function message() {
		// 登录时将所有的未读消息写入到引用表 这样不活跃的用户就没有消息引用 减少数据压力
		$this->load->model('message');
		$this->message->getUnRead(MID);

		$mid  = MID;
		$data = array();
		if (!$mid) {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，会员mid不能为空';
			$this->api_result($data);
		}
		$_GET['page'] = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$this->load->model('page');
		$size = (int) $this->site_config['page_size'];
		$this->page->set_vars(array('per' => $size));
		$sql  = 'SELECT * FROM `###_message_status` WHERE status = 1 AND mid = ' . $mid . ' ORDER BY status , id desc';
		$list = $this->page->hashQuery($sql)->result_array();
		$list = $this->db->lJoin($list, 'message', 'id,type,title,content,status', 'message_id', 'id', 'message_');
		if ($list) {
			foreach ($list as $k => $v) {
				$list[$k]['content'] = rep_content($v['content']);
			}
			$data['list_total'] = (int) $this->page->pages['total'];
			$data['list']       = $list;
		}
		unset($list);
		$this->api_result(array('data' => $data));
	}

	/**
	 * 删除我的消息
	 */
	public function messageRemove() {
		$id = intval($_GET['id']);
		if (!$id) {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，缺少消息id';
			$this->api_result($data);
		}
		$mid = MID;
		if (!$mid) {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，会员mid不能为空';
			$this->api_result($data);
		}
		$this->load->model('message');
		$result = $this->message->remove($id, $mid);
		if (!$result) {
			$data['flag'] = false;
			$data['code'] = 100001;
			$data['msg']  = '删除消息失败';
		} else {
			$data['data'] = $result;
		}
		$this->api_result($data);
	}

	/**
	 * 我的消息已读
	 */
	public function messageRead() {
		$id = intval($_GET['id']);
		if (!$id) {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，缺少消息id';
			$this->api_result($data);
		}
		$this->load->model('message');
		$result = $this->message->read($id, MID);
		if (!$result) {
			$data['flag'] = false;
			$data['code'] = 100001;
			$data['msg']  = '失败';
		} else {
			$data['data'] = $result;
		}
		$this->api_result($data);
	}

	/**
	 * 我的优惠卷
	 */
	public function coupon() {
		$type = isset($_GET['type']) ? $_GET['type'] : 1;
		$mid  = MID;
		if (!$mid) {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，会员mid不能为空';
			$this->api_result($data);
		}
		$_GET['page'] = isset($_GET['page']) ? intval($_GET['page']) : 1;
		switch ($type) {
			case '1': //未使用
				$where = ' AND (expire_time>=' . RUN_TIME . ' or expire_time=0) AND use_time=0';
				break;
			case '2': //已使用
				$where = ' AND use_time>0';
				break;
			case '3': //过期
				$where = ' AND expire_time>0 AND expire_time<' . RUN_TIME . ' AND use_time=0';
				break;
			case '2|3': //已使用|过期
				$where = ' AND (use_time>0 OR ( expire_time>0 AND expire_time<' . RUN_TIME . ' AND use_time=0))';
				break;
			default: //全部
				$where = '';
				break;
		}
		$this->load->model('page');
		$size = (int) $this->site_config['page_size'];
		$this->page->set_vars(array('per' => $size));
		$sql  = 'SELECT * FROM ###_coupon_log WHERE mid = ' . $mid . $where . ' ORDER BY create_time DESC';
		$list = $this->page->hashQuery($sql)->result_array();
		$list = $this->db->lJoin($list, 'coupon', 'id,title,need_amount,amount,start_time,end_time,remark', 'coupon_id', 'id', 'b_');
		if (!empty($list)) {
			foreach ($list as $key => $val) {
				$list[$key]['b_start_time_s'] = !empty($val['b_start_time']) ? date('Y/m/d', $val['b_start_time']) : '';
				$list[$key]['expire_time_s']  = !empty($val['expire_time']) ? date('Y/m/d', $val['expire_time']) : '';
			}
		}
		$this->api_result(array('data' => $list));
	}

	/**
	 * 收藏
	 */
	public function fav() {
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$mid  = MID;
		$data = array();
		if (!$mid) {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，会员mid不能为空';
			$this->api_result($data);
		}
		$this->load->model('fav');
		$size = 10;
		$list = $this->fav->getList($size, $page, $mid);
		if ($list) {
			$data['list_total'] = (int) $this->page->pages['total'];
			foreach ($list as $k => $v) {
				$data['list'][$k]['id']         = $v['id'];
				$data['list'][$k]['goods_id']   = $v['goods_id'];
				$data['list'][$k]['mid']        = $v['mid'];
				$data['list'][$k]['addtime']    = $v['addtime'];
				$data['list'][$k]['goods_name'] = $v['goods_name'];
				if (!empty($v['goods_thumb'])) {
					$goods_thumb                     = json_decode($v['goods_thumb']);
					$data['list'][$k]['goods_thumb'] = yunurl($goods_thumb[0]->path);
				}
				$data['list'][$k]['goods_team_price'] = $v['goods_team_price'];
				$data['list'][$k]['goods_team_num']   = $v['goods_team_num'];
			}
		}
		unset($list);
		$this->api_result(array('data' => $data));
	}

	/**
	 * 我的收藏->店铺
	 */
	public function store_fav() {
		$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$size = 10;
		$this->load->model('fav');
		$list = $this->fav->getStoreList($size, $page, MID);
		$data = array();
		if ($list) {
			$data['list_total'] = (int) $this->page->pages['total'];
			foreach ($list as $k => $v) {
				$list[$k]['store_logo'] = yunurl($v['store_logo']);
			}
			$data['list'] = $list;
		}
		unset($list);
		$this->api_result(array('data' => $data));
	}

	/**
	 * 取消收藏
	 */
	public function favDelete() {
		$mid = MID;
		$id  = trim($_GET['id']);
		if (!$mid) {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，会员mid不能为空';
			$this->api_result($data);
		}
		if (!$id) {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，id不能为空';
			$this->api_result($data);
		}
		$this->load->model('fav');
		$res = $this->fav->favDelete($id, $mid);
		if (!$res) {
			$data['flag'] = false;
			$data['code'] = 100001;
			$data['msg']  = '取消收藏失败';
		} else {
			$data['data'] = $res;
		}
		$this->api_result($data);
	}

	/**
	 * 添加收藏
	 */
	public function favAddOrDelete() {
		$mid = MID;
		$id  = intval($_GET['id']);
		if (!$mid) {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，会员mid不能为空';
			$this->api_result($data);
		}
		if (!$id) {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，id不能为空';
			$this->api_result($data);
		}
		$this->load->model('fav');
		$res = $this->fav->favAddOrDelete($id, $mid);
		if (!$res) {
			$data['flag'] = false;
			$data['code'] = 100001;
			$data['msg']  = '取消或添加收藏失败';
		} else {
			$data['data'] = $res;
		}
		$this->api_result($data);
	}

	/**
	 * 我的团
	 */
	public function team() {
		$this->load->model('order');
		$this->load->model('team');
		$this->load->model('page');
		$size = (int) $this->site_config['page_size'];
		$this->page->set_vars(array('per' => $size));
		$_GET['page'] = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$status       = !empty($_REQUEST['status']) ? intval($_REQUEST['status']) : ''; //订单状态
		$mid          = MID;
		$data         = array();
		if (!$mid) {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，会员mid不能为空';
			$this->api_result($data);
		}

		//$where = ' (A.extension_code!=' . CART_LUCK . ' AND A.extension_code!=' . CART_FREE . ') ';
		$where = ' 1 ';
		if ($status !== '') {
			$where .= " AND A.status_common=" . $status;
		}
		$select = "A.id,A.order_sn,A.common_id,A.status_common,A.status_pay,A.status_shipping,A.status_order,A.status_lottery,A.square_time,";
		$select .= "B.goods_id,B.team_num_yes,";
		$select .= "C.name,C.thumb,C.team_num,";
		$select .= "D.sell_price";
		$sql = "SELECT {$select} FROM ###_goods_order A
                LEFT JOIN ###_goods_order_common B ON A.common_id = B.id
                LEFT JOIN ###_goods C ON B.goods_id = C.id
                LEFT JOIN ###_goods_order_item D ON A.id = D.order_id
                WHERE " . ($where ? $where : '1') . " AND A.mid='" . $mid . "' AND A.common_id>0 ORDER BY A.id DESC";
		$list = $this->page->hashQuery($sql)->result_array();
		if ($list) {
			$data['list_total'] = (int) $this->page->pages['total'];
			foreach ($list as $k => $v) {
				if (isset($v['thumb'])) {
					$thumb             = json_decode($v['thumb']);
					$list[$k]['thumb'] = yunurl($thumb[0]->path);
				}
				$list[$k]['status_name'] = $this->order->order_status_name($v);
				if ($v['status_common'] == TEAM_ING && $v['square_time'] == 0) {
					$list[$k]['issue'] = false;
				} else {
					$list[$k]['issue'] = true;
				}
				unset($list[$k]['status_pay'], $list[$k]['status_shipping'], $list[$k]['status_order'], $list[$k]['status_lottery']);
			}
			$data['list'] = $list;
		}
		unset($list);
		$this->api_result(array('data' => $data));
	}

	/**
	 * 我的抽奖
	 */
	public function order_lottery() {
		$this->load->model('order');
		$this->load->model('page');
		$size = (int) $this->site_config['page_size'];
		$this->page->set_vars(array('per' => $size));
		$_GET['page'] = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$mid          = MID;
		$data         = array();
		if (!$mid) {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，会员mid不能为空';
			$this->api_result($data);
		}

		$where = ' (A.extension_code=' . CART_LUCK . ' or A.extension_code=' . CART_FREE . ') ';
		$sql   = "SELECT A.id,A.is_rate,A.order_sn,A.order_amount,A.status_common,A.status_pay,A.status_shipping,A.status_order,A.status_lottery,B.goods_id,C.name,C.thumb,D.buy_num,D.goods_spec FROM ###_goods_order A LEFT JOIN ###_goods_order_common B ON A.common_id = B.id LEFT JOIN ###_goods C ON B.goods_id = C.id LEFT JOIN ###_goods_order_item D ON A.id = D.order_id WHERE " . ($where ? $where : '1') . " AND A.mid='" . $mid . "' and A.common_id>0 ORDER BY A.id DESC";
		$list  = $this->page->hashQuery($sql)->result_array();
		$list  = $this->db->lJoin($list, 'business', 'id,kf_online,name', 'sid', 'id', 's_');
		if ($list) {
			$data['list_total'] = (int) $this->page->pages['total'];
			foreach ($list as $k => $v) {
				if (isset($v['thumb'])) {
					$thumb             = json_decode($v['thumb']);
					$list[$k]['thumb'] = yunurl($thumb[0]->path);
				}
				$list[$k]['status_id']       = $this->order->order_status('', $v, 2);
				$list[$k]['status_name']     = $this->order->order_status_name($v);
				$list[$k]['goods_isComment'] = C('goods_isComment');
				$kf_online                   = $v['sid'] > 0 ? $v['s_kf_online'] : C('kf_online');
				$list[$k]['kf_online']       = empty($kf_online) ? null : $kf_online;
				unset($list[$k]['status_common'], $list[$k]['status_pay'], $list[$k]['status_shipping'], $list[$k]['status_lottery']);
			}
			$data['list'] = $list;
		}
		unset($list);
		$this->api_result(array('data' => $data));
	}

	/**
	 * 我的订单
	 */
	public function order() {
		//取消未付款订单
		$this->cancel_nopay();

		$this->load->model('order');
		$this->load->model('page');
		$size = (int) $this->site_config['page_size'];
		$this->page->set_vars(array('per' => $size));
		$_GET['page'] = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$status       = isset($_GET['status']) ? intval($_GET['status']) : ''; //订单状态
		$data         = array();
		$where        = '';
		if ($status) {
			$where .= $this->order->order_status($status, '', 1);
		}
		if (isset($_REQUEST['is_rate'])) {
			$is_rate = intval($_GET['is_rate']);
			$where .= " and is_rate = $is_rate";
		}
		$sql  = "SELECT A.*,B.good_id,B.goods_spec,B.buy_num,C.name,C.thumb FROM ###_goods_order A LEFT JOIN ###_goods_order_item B ON A.id = B.order_id LEFT JOIN ###_goods C ON B.good_id = C.id WHERE " . ($where ? $where : '1') . " AND A.mid = " . MID . " ORDER BY A.id DESC ";
		$list = $this->page->hashQuery($sql)->result_array();
		$list = $this->db->lJoin($list, "payment", "pay_id,pay_code", "pay_id", "pay_id");
		$list = $this->db->lJoin($list, 'business', 'id,kf_online,name', 'sid', 'id', 's_');

		if ($list) {
			$refund_days        = C("refund_days");
			$data['list_total'] = (int) $this->page->pages['total'];
			foreach ($list as $k => $v) {
				$data['list'][$k]['id']            = $v['id'];
				$data['list'][$k]['order_sn']      = $v['order_sn'];
				$data['list'][$k]['is_rate']       = (int) $v['is_rate'];
				$data['list'][$k]['order_amount']  = $v['order_amount'];
				$data['list'][$k]['good_id']       = $v['good_id'];
				$data['list'][$k]['goods_spec']    = $v['goods_spec'];
				$data['list'][$k]['buy_num']       = $v['buy_num'];
				$data['list'][$k]['name']          = $v['name'];
				$data['list'][$k]['pay_code']      = $v['pay_code'];
				$data['list'][$k]['status_common'] = $v['status_common'];
				$data['list'][$k]['status_pay']    = $v['status_pay'];
				$data['list'][$k]['pre_amount']    = $v['pre_amount'];
				$data['list'][$k]['end_amount']    = $v['end_amount'];
				$data['list'][$k]['extension_code']= $v['extension_code'];
				$data['list'][$k]['store_name']    = !empty($v['s_name']) ? $v['s_name'] : C('site_name');
				if (isset($v['thumb'])) {
					$thumb                     = json_decode($v['thumb']);
					$data['list'][$k]['thumb'] = yunurl($thumb[0]->path, 300);
				}
				$data['list'][$k]['status_name'] = $this->order->order_status_name($v);
				//综合状态
				$data['list'][$k]['status_id'] = $this->order->order_status('', $v, 2);
				$data['list'][$k]['refund']    = 0;
				if ($refund_days && $v['status_order'] == 10 && $v['is_refund'] == 1 && $v['extension_code'] != CART_AA && ($v['confirm_time'] + $refund_days * 24 * 3600) >= RUN_TIME) {
					$data['list'][$k]['refund'] = 1;
				}
				$kf_online                     = $v['sid'] > 0 ? $v['s_kf_online'] : C('kf_online');
				$data['list'][$k]['kf_online'] = empty($kf_online) ? null : $kf_online;
				$online_link                   = url('/chat?good_id=') . $v['good_id'];
				if (!empty($goods['sid'])) {
					$online_link .= '&bid=' . $goods['sid'];
				}

				$data['list'][$k]['online_link'] = empty($data['list'][$k]['kf_online']) ? $online_link : $data['list'][$k]['kf_online'];
				$data['list'][$k]['online_link'] = urlencode($data['list'][$k]['online_link']);
			}
		}
		unset($list);
		$this->api_result(array('data' => $data));
	}

	/**
	 * 取消订单
	 */
	function cancel_nopay() {
		$nopay_time = C("nopay_time") * 60;
		if ($nopay_time > 0) {
			$order_list = $this->db->select("SELECT id,c_time FROM ###_goods_order WHERE mid=" . MID . " AND status_pay=0 AND status_shipping=0 AND status_order=0 ");
			if ($order_list) {
				$this->load->model('order');
				foreach ($order_list as $v) {
					if (RUN_TIME - $v['c_time'] > $nopay_time) {
						$this->order->chageOrderState($v['id'], array('status_order' => 1), '取消订单');
					}
				}
			}
		}
	}

	/**
	 * 取消订单
	 */
	public function orderCancel() {
		$id = intval($_GET['id']);
		if (!$id) {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，缺少订单id';
			$this->api_result($data);
		}
		$mid = MID;
		if (!$mid) {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，会员mid不能为空';
			$this->api_result($data);
		}
		$result = $this->db->update("goods_order", array("status_order" => 1), array("id" => $id, "mid" => $mid));
		if (!$result) {
			$data['flag'] = false;
			$data['code'] = 100001;
			$data['msg']  = '取消订单失败';
		} else {
			$data['data'] = $result;
		}
		$this->api_result($data);
	}

	/**
	 * 删除订单
	 */
	public function orderDelete() {
		$id = intval($_GET['id']);
		if (!$id) {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，缺少订单id';
			$this->api_result($data);
		}
		$mid = MID;
		if (!$mid) {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，会员mid不能为空';
			$this->api_result($data);
		}
		$this->db->delete("goods_order", array("id" => $id, "mid" => $mid));
		$this->db->delete("goods_order_item", array("order_id" => $id, "mid" => $mid));
		$this->api_result(array());
	}

	/**
	 * 订单详情
	 */
	public function orderDetail() {
		$order_sn = trim($_GET['order_sn']);
		if (!$order_sn) {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，缺少订单编号order_sn';
			$this->api_result($data);
		}
		$mid = MID;
		if (!$mid) {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，会员mid不能为空';
			$this->api_result($data);
		}

		$order = $this->db->get("SELECT * FROM `###_goods_order` WHERE order_sn = $order_sn AND mid = $mid");
		if (!isset($order['id'])) {
			$data['flag'] = false;
			$data['code'] = 100001;
			$data['msg']  = '订单不存在';
			$this->api_result($data);
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
		#优惠卷
		if ($order['coupon_id']) {
			$this->load->model('coupon');
			$coupon                 = $this->coupon->getFullCouponLog($order['coupon_id']);
			$coupon['target_name']  = $this->coupon->getTargetName($order['coupon_id']);
			$data['data']['amount'] = $coupon['amount'];
		} else {
			$data['data']['amount'] = 0;
		}

		#团购 团长优惠
		if ($order['goods'][0]['goods_discount_type'] > 0 && $order['common_id'] > 0) {
//团长优惠
			$order['discount_amount']        = $order['goods'][0]['goods_discount_type'] == 1 ? $order['goods'][0]['goods_team_price'] : $order['goods'][0]['goods_discount_amount'];
			$data['data']['discount_amount'] = $order['discount_amount'];
		} else {
			$data['data']['discount_amount'] = 0;
		}

		$data['data']['order_id']           = (int) $order['id'];
		$data['data']['status_common']      = $order['status_common'];
		$data['data']['name']               = $order['name'];
		$data['data']['mobile']             = $order['mobile'];
		$data['data']['area']               = $order['area'];
		$data['data']['address']            = $order['address'];
		$data['data']['status_shipping']    = $order['status_shipping'];
		$data['data']['status_name']        = $order['status_name'];
		$data['data']['goods_id']           = (int) $order['goods'][0]['goods_id'];
		$data['data']['goods_name']         = $order['goods'][0]['goods_name'];
		$data['data']['goods_typeid']       = $order['goods'][0]['goods_typeid'];
		$data['data']['goods_img_src']      = yunurl($order['goods'][0]['img_src']);
		$data['data']['goods_sell_price']   = $order['goods'][0]['sell_price'];
		$data['data']['goods_price']        = $order['goods'][0]['goods_price'];
		$data['data']['goods_spec']         = $order['goods'][0]['goods_spec'];
		$data['data']['goods_buy_num']      = $order['goods'][0]['buy_num'];
		$data['data']['total_price']        = formatPrice($order['goods'][0]['sell_price'] * $order['goods'][0]['buy_num']);
		$data['data']['preferential_price'] = $order['discount_amount'] + $coupon['amount'];
		$data['data']['order_amount']       = $order['order_amount'];
		$data['data']['common_id']          = $order['common_id'];
		$data['data']['contact_mobile']     = C('contact_mobile');
		$data['data']['order_sn']           = $order['order_sn'];
		$data['data']['pay_name']           = $order['pay_name'];
		$data['data']['c_time']             = date('Y-m-d H:i:s', $order['c_time']);
		$data['data']['status_id']          = $order['status_id'];
		$data['data']['status_order']       = $order['status_order'];
		$data['data']['is_rate']            = $order['is_rate'];
		$data['data']['bid']                = $order['sid'];
		$data['data']['status_pay']         = $order['status_pay'];
		$data['data']['shipping_fee']       = $order['shipping_fee'];
		$data['data']['money_paid']         = $order['money_paid'];
		$data['data']['amount']             = $order['amount'];
		if ($order['coupon_id']) {
			$this->load->model('coupon');
			$coupon                 = $this->coupon->getFullCouponLog($order['coupon_id']);
			$coupon['target_name']  = $this->coupon->getTargetName($order['coupon_id']);
			$data['data']['coupon'] = $coupon;
		}
		if ($order['coupon_id_sid']) {
			$this->load->model('coupon');
			$coupon                     = $this->coupon->getFullCouponLog($order['coupon_id_sid']);
			$coupon['target_name_sid']  = $this->coupon->getTargetName($order['coupon_id_sid']);
			$data['data']['coupon_sid'] = $coupon;
		}
		//商家信息
		$data['data']['store_name'] = $data['data']['store_mobile'] = null;
		if ($order['sid'] > 0) {
			$res                          = $this->db->get("select name,mobile,kf_online from ###_business where id ={$order['sid']}");
			$data['data']['store_name']   = $res['name'];
			$data['data']['store_mobile'] = $res['mobile'];
			$data['kf_online']            = $res['kf_online'];
		} else {
			$data['kf_online']          = C('kf_online');
			$data['data']['store_name'] = C('site_name');
		}
		$data['kf_online'] = empty($data['kf_online']) ? null : $data['kf_online'];
		$online_link       = url('/chat?good_id=') . $order['goods'][0]['id'];
		if (!empty($order['goods'][0]['sid'])) {
			$online_link .= '&bid=' . $order['goods'][0]['sid'];
		}

		$data['data']['online_link'] = empty($data['kf_online']) ? $online_link : $data['kf_online'];
		$data['data']['online_link'] = urlencode($data['data']['online_link']);
		unset($orders, $order);
		$this->api_result($data);
	}

	/**
	 * 查看物流
	 */
	public function orderShip() {
		$order_sn               = $data['order_sn']               = $_GET['order_sn'];
		$data['express']        = $data['wuliu']        = null;
		$row                    = $this->db->get("select * from ###_goods_order where order_sn={$order_sn} and mid=" . MID);
		$express                = $this->db->get("select name,pinyin from ###_express where id={$row['express']}");
		$data['contact_mobile'] = C('contact_mobile');
		$data['express_num']    = $row['express_num'];
		$data['express']        = $express['name'];
		//根据快递单号查询物流信息
		$this->load->library("express");
		if ($express['pinyin'] && $row['express_num']) {
			$wuliu = $this->express->getorder($express['pinyin'], $row['express_num']);
			//$wuliu = $this->express->getorder("zhongtong",'534537800439');
			$data['wuliu'] = null;
			if ($wuliu['data']) {
				foreach ($wuliu['data'] as $k => $v) {
					$temp            = array();
					$temp['time']    = $v['time'];
					$temp['context'] = $v['context'];
					$data['wuliu'][] = $temp;
				}
			}
		}
		$this->api_result(array("data" => $data));
	}

	/**
	 * 提交评价
	 */
	public function orderRate() {
		$inter_array['mid']        = MID;
		$inter_array['order_id']   = isset($_POST['order_id']) ? intval($_POST['order_id']) : '';
		$inter_array['good_id']    = isset($_POST['good_id']) ? intval($_POST['good_id']) : '';
		$inter_array['goods_spec'] = isset($_POST['goods_spec']) ? trim($_POST['goods_spec']) : '';
		$inter_array['star']       = isset($_POST['star']) ? intval($_POST['star']) : '';
		$inter_array['content']    = isset($_POST['content']) ? trim($_POST['content']) : '';
		$inter_array['state']      = 0;
		$inter_array['c_time']     = RUN_TIME;
		$inter_array['buy_num']    = isset($_POST['buy_num']) ? intval($_POST['buy_num']) : '';
		if ($inter_array['good_id']) {
			$inter_array['sid'] = $this->db->getstr("select sid from ###_goods where id={$inter_array['good_id']}", "sid");
		}
		if (empty($inter_array['content'])) {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '请输入评价内容';
			$this->api_result($data);
		}

		if (!$inter_array['mid']) {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，会员mid不能为空';
			$this->api_result($data);
		}
		if (!$inter_array['order_id']) {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，order_id不能为空';
			$this->api_result($data);
		}
		if (!$inter_array['good_id']) {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，good_id不能为空';
			$this->api_result($data);
		}
		if ($inter_array['star'] === '') {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，star不能为空';
			$this->api_result($data);
		}
		if (!$inter_array['buy_num']) {
			$data['flag'] = false;
			$data['code'] = 100002;
			$data['msg']  = '参数异常，buy_num不能为空';
			$this->api_result($data);
		}
		$rateId = $this->db->insert("goods_rate", $inter_array);
		if (!$rateId) {
			$data['flag'] = false;
			$data['code'] = 100001;
			$data['msg']  = '评论失败';
			$this->api_result($data);
		}
		if (!$this->db->update("goods_order", array("is_rate" => 1), array("id" => $_POST['order_id'], 'mid' => MID))) {
			$this->db->delete("goods_rate", array('id' => $rateId));
			$data['flag'] = false;
			$data['code'] = 100001;
			$data['msg']  = '评论失败';
			$this->api_result($data);
		} else {
			$this->api_result(array());
		}
	}

	/**
	 * 确认收货
	 */
	public function finish_order() {
		$id    = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$qishu = 0;
		$order = $this->db->get("SELECT * FROM ###_goods_order WHERE id = '" . $id . "' and mid=" . MID);
		if (!$order) {
			$this->api_result(array('flag' => false, 'msg' => '订单不存在', 'code' => 100001));
		}
		if ($order['status_shipping'] == 2) {
			$this->load->model('order');
			#非货到付款，收货时，订单交易完成
			$set['status_shipping'] = 10;
			//if ($order['is_cod'] != 1 && $qishu <= 1) {
			$set['status_pay']   = 10;
			$set['status_order'] = 10;
			//}
			$set['confirm_time'] = RUN_TIME;
			$msg                 = '确认收货';
			$this->order->chageOrderState($id, $set, $msg);
			if ($order['extension_code'] == CART_AA) {
//AA团 团长确认收货
				$this->db->update('goods_order', array(
					'status_pay'   => 10,
					'status_order' => 10,
					'status_order' => 10,
				), array('common_id' => $order['common_id']));
			}
			$this->api_result(array());
		} else {
			$this->api_result(array('flag' => false, 'msg' => '请确认商品状态是否为已发货', 'code' => 100001));
		}
	}

	/**
	 * 退款申请
	 */
	public function apply_refund() {
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : false;
		if (!$order_id) {
			$this->api_result(array('flag' => false, 'msg' => '参数异常', 'code' => 100001));
		}

		$row = $this->db->get("select * from ###_goods_order where id = $order_id and mid=" . MID);
		if (!$row) {
			$this->api_result(array('flag' => false, 'msg' => '订单不存在', 'code' => 100001));
		}

		$data['order_id'] = $order_id;
		$data['type']     = array('1' => '仅退款', '2' => '退货退款');
		$data['reason']   = $this->refund->reasonTypes;
		$this->api_result(array('data' => $data));
	}

	/**
	 * 上传图片
	 */
//    public function save_refund_pic(){
	//        $this->load->model('upload');
	//        $tmp_name = $_FILES["pic"]["tmp_name"];
	//        $name = explode('.', $_FILES["pic"]["name"]);
	//        $name_ext = end($name);
	//        $name = $this->upload->random_filename().'_src.'.$name_ext;
	//        $dir = $this->upload->getCateDir('refund');
	//        $file = $dir.$name;
	//        $res = move_uploaded_file($tmp_name, $file);
	//        if($res){
	//            $data['pic'] = str_replace(RootDir . 'web', '', $file);
	//            $data['name'] = $name;
	//            $this->api_result(array('data'=>$data));
	//        }else{
	//            $this->api_result(array('flag' => false, 'msg' => '上传图片失败，', 'code' => 100001));
	//        }
	//    }

	/**
	 * 删除图片
	 */
//    public function delete_refund_pic(){
	//        $pic = trim($_GET['pic']);
	//        $this->load->model('upload');
	//        $dir = $this->upload->getCateDir('refund');
	//        $res = $this->upload->rmFile($dir.$pic);
	//        if(!$res)
	//            $this->api_result(array('flag' => false, 'msg' => '删除图片失败，'.$pic, 'code' => 100001));
	//        $this->api_result(array());
	//    }

	/**
	 * 提交申请退款
	 */
	public function submit_apply_refund() {
		$order_id  = isset($_POST['order_id']) ? intval($_POST['order_id']) : false;
		$type      = isset($_POST['type']) ? intval($_POST['type']) : false;
		$reason_id = isset($_POST['reason_id']) ? intval($_POST['reason_id']) : false;
		$note      = isset($_POST['note']) ? trim($_POST['note']) : false;
		if (!$order_id || !$type || !$reason_id || !$note) {
			$this->api_result(array('flag' => false, 'msg' => '参数异常', 'code' => 100001));
		}

		#检查退款申请是否已存在
		$is_exist = $this->db->get("select 1 from ###_refund where order_id=$order_id and status=0");
		if ($is_exist) {
			$this->api_result(array('flag' => false, 'msg' => '您已申请，请等待商家处理', 'code' => 100001));
		}

		#检查订单是否存在
		$row = $this->db->get("select * from ###_goods_order where id={$order_id} and mid=" . MID);
		if (!$row) {
			$this->api_result(array('flag' => false, 'msg' => '订单不存在', 'code' => 100001));
		}

		#图片处理
		$pic = array();
		if (!empty($_FILES)) {
			$this->load->model('upload');
			$dir = $this->upload->getCateDir('refund');
			is_dir($dir) || mkdir($dir, 0777, true);
			foreach ($_FILES["pic"]["error"] as $key => $error) {
				if ($error == UPLOAD_ERR_OK) {
					$tmp_name = $_FILES["pic"]["tmp_name"][$key];
					$name     = explode('.', $_FILES["pic"]["name"][$key]);
					$name_ext = end($name);
					$name     = $this->upload->random_filename() . '_src.' . $name_ext;
					$file     = $dir . $name;
					$res      = move_uploaded_file($tmp_name, $file);
					if (!$res) {
						$this->api_result(array('flag' => false, 'msg' => '上传图片失败，', 'code' => 100001));
					}
					$pic[] = str_replace(RootDir . 'web', '', $file);
				}
			}
		}
		#保存退款
		$post['pic']           = join("|", $pic);
		$post['order_id']      = $order_id;
		$post['type']          = $type;
		$post['reason_id']     = $reason_id;
		$post['note']          = $note;
		$post['mid']           = MID;
		$post['c_time']        = RUN_TIME;
		$post['refund_amount'] = $row['order_amount'];
		$post['sid']           = $row['sid'];
		$res                   = $this->db->insert("refund", $post);
		if ($res) {
			$r = $this->db->update("goods_order", array("is_refund" => 2), array("id" => $order_id));
			#模版消息 4 会员申请退款 {插入昵称},{插入订单号}
			// template_msg_action start
			$this->load->model('template_msg');
			$msgParams = array(getUsername($row['mid']), $row['order_sn']);
			$this->template_msg->inQueue(4, 0, $msgParams);
			// template_msg_action end
			$this->api_result(array());
		} else {
			$this->api_result(array('flag' => false, 'msg' => '申请失败', 'code' => 100001));
		}
	}

	/**
	 * 退款订单列表
	 */
	public function refund_order_lists() {
		$option['page'] = isset($_GET['page']) ? intval($_GET['page']) : 1; //页码
		$option['mid']  = MID;
		$list           = $this->refund->getList($option);
		$list           = $this->db->lJoin($list, 'goods_order', 'id,order_amount,order_sn', 'order_id', 'id', "o_");
		$data           = array();
		if ($list) {
			$orderIds           = array_column($list, "order_id");
			$items              = $this->db->select("SELECT * FROM ###_goods_order_item WHERE order_id IN(" . implode(',', $orderIds) . ")");
			$items              = $this->db->lJoin($items, 'goods', 'id,thumb,thumbs,team_num,price,team_price,market_price,discount_type,discount_amount', 'good_id', 'id', 'goods_');
			$data['list_total'] = (int) $this->page->pages['total'];
			$orderItems         = array();
			foreach ($items as $k => $v) {
				$thumb       = $this->db->getstr("select thumb from ###_goods_item where goods_id={$v['goods_id']} and spec='{$v['spec']}'", 'thumb');
				$v['thumb']  = !empty($thumb) ? $thumb : $v['goods_thumb'];
				$v['thumbs'] = !empty($thumb) ? $thumb : $v['goods_thumbs'];
				unset($v['goods_thumb']);
				unset($v['goods_thumbs']);
				$v         = $this->goods->getThumb($v, 1, array('thumb'));
				$items[$k] = $v;
				if (!isset($orderItems[$v['order_id']])) {
					$orderItems[$v['order_id']] = array();
				}
				$orderItems[$v['order_id']][] = $v;
			}
			foreach ($list as $k => $v) {
				$list[$k]['status_name'] = $this->refund->status_name($v);
				$list[$k]['goods']       = $orderItems[$v['order_id']];

				$data['list'][$k]['goods_id']        = (int) $list[$k]['goods_id'];
				$data['list'][$k]['order_id']        = (int) $list[$k]['o_id'];
				$data['list'][$k]['status_name']     = $list[$k]['status_name'];
				$data['list'][$k]['order_sn']        = $list[$k]['o_order_sn'];
				$data['list'][$k]['goods_name']      = $list[$k]['goods'][0]['goods_name'];
				$data['list'][$k]['goods_spec']      = $list[$k]['goods'][0]['goods_spec'];
				$thumb                               = json_decode($list[$k]['goods'][0]['thumb']);
				$data['list'][$k]['goods_thumb']     = yunurl($thumb[0]->path);
				$data['list'][$k]['buy_num']         = (int) $list[$k]['goods'][0]['buy_num'];
				$data['list'][$k]['order_amount']    = formatPrice($list[$k]['o_order_amount']);
				$data['list'][$k]['refund_id']       = (int) $list[$k]['id'];
				$data['list'][$k]['status_shipping'] = (int) $list[$k]['status_shipping'];
				$data['list'][$k]['status']          = (int) $list[$k]['status'];
				$data['list'][$k]['type']            = (int) $list[$k]['type'];
			}
		}
		$this->api_result(array('data' => $data));
	}

	/**
	 * 商家地址
	 */
	public function business_address() {
		$id = isset($_GET['refund_id']) ? intval($_GET['refund_id']) : false;
		if (!$id) {
			$this->api_result(array('flag' => false, 'msg' => '参数异常', 'code' => 100001));
		}

		$row = $this->db->get("select * from ###_refund where id={$id} AND mid = " . MID . " order by id desc limit 1");
		if ($row['address_id']) {
			$row['address'] = $this->db->get("select name,address,mobile from ###_business_address where id={$row['address_id']}");
		} else {
			$this->api_result(array('flag' => false, 'msg' => '商家暂无填写地址', 'code' => 100001));
		}

		$data['id']      = (int) $row['id'];
		$data['name']    = $row['address']['name'];
		$data['address'] = $row['address']['address'];
		$data['mobile']  = $row['address']['mobile'];
		$this->api_result(array('data' => $data));
	}

	/**
	 * 提交退货申请
	 */
	public function shipping() {
		$id          = isset($_POST['id']) ? intval($_POST['id']) : false;
		$express     = isset($_POST['express']) ? trim($_POST['express']) : false;
		$express_num = isset($_POST['express_num']) ? trim($_POST['express_num']) : false;
		$res         = $this->db->update("refund", array("express" => $express, "express_num" => $express_num, "status_shipping" => 1), array("id" => $id));
		if (!$res) {
			$this->api_result(array('flag' => false, 'msg' => '申请失败', 'code' => 100001));
		}
		$this->api_result(array());
	}

	/**
	 * 退款详情
	 */
	public function refund_detail() {
		$id = isset($_GET['refund_id']) ? intval($_GET['refund_id']) : false;
		if (!$id) {
			$this->api_result(array('flag' => false, 'msg' => '参数异常', 'code' => 100001));
		}

		$row = $this->db->get("select * from ###_refund where id={$id} AND mid = " . MID);
		if (!$row) {
			$this->api_result(array('flag' => false, 'msg' => '不存在此退款', 'code' => 100001));
		}
		$msg    = null;
		$reason = null;
		switch ($row['status']) {
			case 0:
				$msg = "您的申请已提交，等待商家处理";
				break;
			case 2:
				$msg = "商家拒绝了您的退款申请";
				if (empty($row['mark'])) {
					$reason = '商家未填写拒绝原因';
				} else {
					$reason = $row['mark'];
				}
				break;
			case 10:
				if ($row['type'] == 1) {
					if ($row['status_refund'] == 0) {
						$msg = "商家审核通过，等待平台确认";
					} else {
						$msg = "商家审核通过，平台已确认";
					}
				} elseif ($row['type'] == 2) {
					if ($row['status_refund'] == 10 && $row['status_shipping'] == 10) {
						$msg = "商家已收货，平台已确认";
					} elseif ($row['status_refund'] == 0 && $row['status_shipping'] == 10) {
						$msg = "商家已收货，等待平台确认";
					} elseif ($row['status_shipping'] == 0) {
						$msg = "等退货";
					} elseif ($row['status_shipping'] == 1) {
						$msg = "等待商家收货";
					}
				}
				break;
		}
		$data['msg']    = $msg;
		$data['reason'] = $reason;
		$this->api_result(array('data' => $data));
	}

	/**
	 * 退货信息
	 */
	public function refund_shipping() {
		$id = isset($_GET['refund_id']) ? intval($_GET['refund_id']) : false;
		if (!$id) {
			$this->api_result(array('flag' => false, 'msg' => '参数异常', 'code' => 100001));
		}

		$row = $this->db->get("select * from ###_refund where id={$id} AND mid = " . MID . " order by id desc limit 1");
		if (!$row) {
			$this->api_result(array('flag' => false, 'msg' => '不存在此退款', 'code' => 100001));
		}
		if ($row['address_id']) {
			$row['address'] = $this->db->get("select name,address,mobile from ###_business_address where id={$row['address_id']}");
		}

		$data['refund_id']   = (int) $row['id'];
		$data['name']        = $row['address']['name'];
		$data['mobile']      = $row['address']['mobile'];
		$data['address']     = $row['address']['address'];
		$data['express']     = $row['express'];
		$data['express_num'] = $row['express_num'];
		$this->api_result(array('data' => $data));
	}

	/**
	 * 异步获取发布商品信息
	 */
	function ajax_issue() {
		$order_id = isset($_REQUEST['order_id']) ? intval($_REQUEST['order_id']) : 0;
		$this->load->model('order');
		$res = $this->order->getOrderItemCommonGoods($order_id, 'id,common_id', MID);
		if (!$res) {
			$this->api_result(array('flag' => false, 'msg' => '获取失败', 'code' => 100001));
		}
		$data['order_id']     = $res['id'];
		$data['goods_name']   = $res['goi_goods_name'];
		$data['img_cover']    = yunurl($res['img_cover']);
		$data['team_num']     = $res['goc_team_num'];
		$data['team_num_yes'] = $res['goc_team_num_yes'];
		$this->api_result(array('data' => $data));
	}

	/**
	 * 提交发布
	 */
	function issue() {
		$order_id    = isset($_POST['order_id']) ? intval($_POST['order_id']) : '';
		$square_desc = isset($_POST['square_desc']) ? trim($_POST['square_desc']) : '';
		if (!$order_id) {
			$this->api_result(array('flag' => false, 'msg' => '订单id不合法', 'code' => 100001));
		}

		if (!$square_desc) {
			$this->api_result(array('flag' => false, 'msg' => '请输入您想说的话', 'code' => 100001));
		}

		$this->load->model('order');
		$order = $this->order->getOrderById($order_id);
		if (!$order) {
			$this->api_result(array('flag' => false, 'msg' => '订单不存在', 'code' => 100001));
		}

		if ($order['mid'] != MID) {
			$this->api_result(array('flag' => false, 'msg' => '非法操作', 'code' => 100001));
		}

		if ($order['common_id'] == 0) {
			$this->api_result(array('flag' => false, 'msg' => '此订单不是拼团', 'code' => 100001));
		}

		if (!empty($order['square_desc']) || !empty($order['square_time'])) {
			$this->api_result(array('flag' => false, 'msg' => '已经发布，请勿重复', 'code' => 100001));
		}

		$res = $this->db->update('goods_order', array('square_desc' => $square_desc, 'square_time' => RUN_TIME), array('id' => $order['id'], 'mid' => MID));
		if (!$res) {
			$this->api_result(array('flag' => false, 'msg' => '操作失败', 'code' => 100001));
		}
		$this->api_result(array('data' => $res));
	}

	/*
		    *佣金明细
	*/
	function comms_list() {
		$page  = isset($_GET['page']) ? intval($_GET['page']) : 1; //页码
		$where = " and mid=" . MID;
		$this->load->model("commission");
		$_data['where'] = $where;
		$data['list']   = $this->commission->getCommission($_data, 10, $page);
		foreach ($data['list'] as $k => $v) {
			if ($v['level'] == 0) {
				$v['whereFrom'] = '开团' . L('unit_comm');
			} else {
				$v['whereFrom'] = '会员' . L('unit_comm');
			}
			unset($v['level']);
			$v['addtime']     = date('Y-m-d H:m:s', $v['addtime']);
			$data['list'][$k] = $v;
		}
		$data['list_total']       = (int) $this->page->pages['total'];
		$data['commission']       = $this->memberinfo['commission'];
		$data['commission_total'] = $this->memberinfo['commission_total'];
		$this->api_result(array('data' => $data));
	}

	/**
	 * 佣金提现
	 */
	function withdraw_commission() {
		$post['type']     = $_POST['type'];
		$post['realname'] = $_POST['realname'];
		$post['account']  = $_POST['account'];
		$post['amount']   = $_POST['amount'];
		$this->load->model("commission");
		$res = $this->commission->withdraw_commission($post);
		if ($res['error'] > 0) {
			$this->api_result(array('flag' => false, 'msg' => $res['msg'], 'code' => $res['error']));
		} else {
			$this->api_result(array('data' => $res['msg']));
		}
	}

	/**
	 * 提现规则 和金额接口
	 * @return json
	 */
	function withdraw_commission_info() {
		$data = array(
			'commission' => $this->memberinfo['commission'],
			'rule'       => '24小时内到账，法定节假日3天到账，提现范围' . C('comm_limit_min') . '-' . C('comm_limit_max') . '元<br>已授权微信的用户可选【微信(线上接口)】提现到当前微信号',
		);

		$this->api_result(array('data' => $data));
	}

	/**
	 * 佣金提现记录
	 */
	function withdraw_commission_log() {
		$page  = isset($_GET['page']) ? intval($_GET['page']) : 1; //页码
		$where = " and mid=" . MID;
		$this->load->model("commission");
		$_data['where'] = $where;
		$list           = $this->commission->getWithdrawCommission($_data, 10, $page);
		$data['list']   = array();
		$status_array   = array(0 => "处理中", 1 => "已到账", 2 => "不通过");
		foreach ($list as $k => $v) {
			$temp['addtime']     = date('Y-m-d H:m:s', $v['addtime']);
			$temp['amount']      = $v['amount'];
			$temp['status']      = $v['status'];
			$temp['status_name'] = $status_array[$v['status']];
			$data['list'][$k]    = $temp;
		}
		#累计提现金额
		$total = $this->db->getstr("select sum(commission) as num from ###_withdraw_commission where status=1 and mid=" . MID, "num");
		if ($total) {
			$data['total'] = formatPrice($total);
		} else {
			$data['total'] = '0.00';
		}
		$data['list_total'] = (int) $this->page->pages['total'];
		$this->api_result(array('data' => $data));
	}

	/*
		    * 拼团王
	*/
	function team_sort() {
		$data['list'] = $this->db->select("select mid,count(1) as num from ###_goods_order where status_common=10 group by mid order by num desc limit 100");
		$data['list'] = $this->db->lJoin($data['list'], 'member', 'mid,username,mobile', 'mid', 'mid');
		$data['list'] = $this->db->lJoin($data['list'], 'member_detail', 'mid,photo,nickname', 'mid', 'mid');

		$list         = array_column($data['list'], 'mid');
		$list         = array_flip($list);
		$data['sort'] = isset($list[MID]) ? (string) ($list[MID] + 1) : "未上榜";
		if (isset($list[MID])) {
			$data['sort_num'] = $data['list'][$list[MID]]['num'];
		} else {
			$data['sort_num'] = $this->db->getstr("select count(1) as num from ###_goods_order where status_common=10 and mid=" . MID, "num");
		}
		$data['list'] = array_slice($data['list'], 0, 10);
		foreach ($data['list'] as $k => $v) {
			$photo                     = photo_path($v['mid']);
			$data['list'][$k]['photo'] = strpos($photo, "://") !== false ? $photo : yunurl($photo);
		}
		$photo         = photo_path(MID);
		$data['photo'] = strpos($photo, "://") ? $photo : yunurl($photo);
		$data['note']  = $this->db->getstr("SELECT content FROM ###_block WHERE `mark`='team_sort'", "content");
		$data['block'] = $data['note'];
		$data['note']  = strip_tags($data['note']);
		$this->api_result(array('data' => $data));
	}
	/*
		     * 我的粉丝
	*/
	function myivt_list() {
		$page  = isset($_GET['page']) ? intval($_GET['page']) : 1; //页码
		$where = " and ivt_id=" . MID;
		$this->load->model("commission");
		$_data['where'] = $where;
		$list           = $this->commission->getMyivtList($_data, 10, $page);
		$data['list']   = array();
		foreach ($list as $k => $v) {
			$temp['username'] = $v["username"];
			$photo            = ltrim(photo_path($v['mid']), '/');
			$temp['photo']    = strpos($photo, "://") ? $photo : yunurl($photo);
			$temp['c_time']   = date('Y-m-d H:m:s', $v['c_time']);
			$data['list'][$k] = $temp;
		}
		$data['list_total'] = (int) $this->page->pages['total'];
		$data['ivt_count']  = (int) $this->memberinfo['ivt_count'];
		$this->api_result(array('data' => $data));
	}

	/*
		    * 修改密码
	*/
	function chpwd() {
		$oldPass = trim($_POST['oldpass']);
		$pass1   = trim($_POST['pass1']);
		$pass2   = trim($_POST['pass2']);
		if ($pass1 != $pass2) {
			$this->api_result(array('flag' => false, 'msg' => '两次密码不一致', 'code' => 100002));
		}
		$user = $this->db->get("SELECT * FROM `###_member_detail` WHERE `mid` = '" . MID . "'");
		if (!$user) {
			$this->api_result(array('flag' => false, 'msg' => '用户不存在', 'code' => 100002));
		} elseif (!empty($user['password']) && $user['password'] != $this->member->get_salt_hash($oldPass, $user['salt'])) {
			$this->api_result(array('flag' => false, 'msg' => '原密码错误', 'code' => 100002));
		} else {
			$setPass = $this->member->get_salt_hash($pass1, $user['salt']);
			$this->db->update('member_detail', array(
				"password" => $setPass,
			), array('mid' => MID));
			S("H_MEMBER_DETAIL_" . MID, null);
			$this->api_result(array('msg' => '密码重置成功', 'UPSW' => $setPass));
		}
	}

	/*
		    * 修改手机号码
	*/
	function chmobile() {
		$mobile = trim($_POST['mobile']);
		//短信验证码
		if ($this->site_config['sms_open'] == 1 && statusTpl('sms_register')) {
			$this->load->library('sms');
			$verifycode = empty($_POST['sms_code']) ? '' : trim($_POST['sms_code']);

			/* 验证手机号验证码和IP */
			$sql = "SELECT COUNT(id) FROM ###_verify_code WHERE mobile='$mobile' AND getip='" . getIP() . "' AND verifycode='$verifycode' AND status=2 AND dateline>" . (RUN_TIME - 3600);
			//验证码60分钟内有效
			if ($this->db->getstr($sql) == 0) {
				$this->api_result(array('flag' => false, 'msg' => '手机号和验证码不匹配 或者 验证码已过期（1小时内有效）！', 'code' => 100002));
			}
		}
		$member = $this->member->check_username($mobile, 'mobile', ' AND mid!=' . MID);
		if ($member['mobile']) {
			$this->api_result(array('flag' => false, 'msg' => '该手机号已绑定，请更换！', 'code' => 100002));
		}
		$this->db->update('member', array('mobile' => $mobile), array('mid' => MID));
		$this->api_result(array('msg' => '更新成功'));
	}

	/*
		    * 修改昵称
	*/
	function chnickname() {
		$nickname     = trim($_POST['nickname']);
		$nickname_len = mb_strlen($nickname, 'UTF8');
		if ($nickname_len < 2 || $nickname_len > 16) {
			$this->api_result(array('flag' => false, 'msg' => '请输入2-15个字符长度的昵称', 'code' => 100002));
		}
		$is_res = $this->db->get("select 1 from ###_member_detail where nickname = '{$nickname}' and mid!=" . MID);
		if ($is_res) {
			$this->api_result(array('flag' => false, 'msg' => '该昵称已经存在', 'code' => 100002));
		}
		$this->db->update('member_detail', array('nickname' => $nickname), array('mid' => MID));
		S("H_MEMBER_DETAIL_" . MID, null);
		$this->api_result(array('msg' => '更新成功'));
	}

	/*
		    * 修改昵称
	*/
	function photo() {
		$file = trim($_POST['name']);
		$this->load->model('upload');
		//$thumb = array();
		$thumb = array('thumb' => array('width' => 320, 'height' => 320));
		$pic   = $this->upload->save_image('file', 'photo', $thumb, MID);
		if ($pic) {
			$name = substr(strrchr($pic, "/"), 1);
			$temp = $this->db->getstr("select photo from ###_member_detail where mid=" . MID);

			$res = $this->db->update('member_detail', array('photo' => $pic), array('mid' => MID));
			if ($res && $temp{0} == '/') {
				@unlink(RootDir . 'web' . $temp);
			}
			S("H_MEMBER_DETAIL_" . MID, null);
			//echo json_encode(array("error"=>0,"pic"=>$pic,"name"=>$name));exit;
			$pic = yunurl($pic);
			$pic .= "?v=" . RUN_TIME;
			$this->api_result(array('msg' => '更新成功', "avatar" => $pic));
		} else {
			$this->api_result(array('flag' => false, 'msg' => '请上传头像！', 'code' => 100002));
		}

	}

	//实名认证
	function verifyidcard() {

		$verify = $this->db->get("SELECT * FROM ###_verify_idcard WHERE mid='" . MID . "' ORDER BY id DESC");
		$this->smarty->assign('verify', $verify);

		if (empty($_POST['realname'])) {
			$this->api_result(array('flag' => false, 'msg' => '请输入真实姓名', 'code' => 100001));
		}

		if (empty($_POST['card'])) {
			$this->api_result(array('flag' => false, 'msg' => '请输入身份证号', 'code' => 100002));
		}

		$this->load->model('upload');

		$thumb = array('thumb' => array('width' => 350, 'height' => 350));

		$_POST['idcard'] = $this->upload->save_image('idcard', 'idcard', $thumb);

		$_POST['idcard2'] = $this->upload->save_image('idcard2', 'idcard2', $thumb);

		if (empty($_POST['idcard'])) {
			$this->api_result(array('flag' => false, 'msg' => '请上传身份证正面照', 'code' => 100003));
		}

		if (empty($_POST['idcard2'])) {
			$this->api_result(array('flag' => false, 'msg' => '请上传身份证反面照', 'code' => 100004));
		}

		$realname = trim($_POST['realname']);
		$card     = trim($_POST['card']);

		//检验唯一性
		$has_realname = $this->db->getstr("SELECT COUNT(*) AS count FROM ###_member_detail WHERE realname = '$realname'");
		$has_idcard   = $this->db->getstr("SELECT COUNT(*) AS count FROM ###_member_detail WHERE idcard = '$card'");
		$has_verify   = $this->db->getstr("SELECT COUNT(*) AS count FROM ###_verify_idcard WHERE card = '$card' AND realname = '$realname' AND status = 1");
		if (!empty($has_realname) || !empty($has_idcard) || !empty($has_verify)) {
			$this->api_result(array('flag' => false, 'msg' => '您的证件已验证过，请使用其他证件', 'code' => 100004));
		}

		$input                = array();
		$input['mid']         = MID;
		$input['username']    = USER;
		$input['realname']    = $realname;
		$input['card']        = $_POST['card'];
		$input['card_image']  = yunurl($_POST['idcard']);
		$input['card_image2'] = yunurl($_POST['idcard2']);
		$input['add_time']    = RUN_TIME;
		$this->member->verify_idcard_save($input);
		$this->api_result(array('msg' => '提交成功,我们会尽快处理您的验证'));

	}

	/**
	 * 更新用户资料(小程序接口)
	 */
	function updateinfo() {
		$data = array();
		if (!empty($_POST['avatarUrl'])) {
			$data['photo'] = trim($_POST['avatarUrl']);
		}

		if (!empty($_POST['nickName'])) {
			$data['nickname'] = trim($_POST['nickName']);
		}

		if (!empty($_POST['gender'])) {
			$data['sex'] = intval($_POST['gender']);
		}

		$state = $this->db->update('member_detail', $data, array('mid' => MID));
		S("H_MEMBER_DETAIL_" . MID, null);
		$member          = $this->member->member_info(MID);
		$member['photo'] = empty($member['photo']) ? "common/photo.gif" : $member['photo'];
		$member['photo'] = strpos($member['photo'], "://") !== false ? $member['photo'] : yunurl($member['photo']);
		$this->api_result(array('msg' => '更新成功', 'data' => $member));
	}


}