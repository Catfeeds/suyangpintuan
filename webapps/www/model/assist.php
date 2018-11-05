<?php
/**
 * Class assist_model
 */
class assist_model extends Lowxp_Model {

	public $pow       = 0;
	public $baseTable = "###_assist";
	public $logTable  = "###_assist_log";
	public $helpTable = "###_assist_log_help";

	function __construct() {

		if (file_exists(AppDir . 'config/version_assist.php')) {
			include AppDir . 'config/version_assist.php';
		}
		if (defined("Version_assist")) {
			$this->power = 1;
		}

		//批量处理
		$queueDealCacheName = 'ASSIST_ACTION';
		if (!S($queueDealCacheName)) {
			S($queueDealCacheName, 1, 180);
			$this->run();
		}
	}
	//修改过期助力状态
	function run() {
		$this->db->update($this->logTable, array("status" => 2), " status=0 and e_time<" . RUN_TIME);
	}

	/*
		        * 获取助力活动列表
	*/
	function getList($option = array()) {

		$page = isset($option['page']) ? $option['page'] : 1;

		$where = " 1 ";

		if ($option['where']) {
			$where .= $option['where'];
		}

		$this->load->model('page');

		$_GET['page'] = intval($page);

		if (isset($option['size'])) {
			$size = isset($option['size']) ? $option['size'] : '10';
		} else {
			$size = isset($this->common['page_listrows']) ? (int) $this->common['page_listrows'] : 10;
		}
		$this->page->set_vars(array('per' => $size));

		if (!isset($option['order'])) {
			$option['order'] = " listorder desc,id desc ";
		}
		#数据集

		$sql = "SELECT * FROM {$this->baseTable} WHERE {$where} ORDER BY {$option['order']}";

		$list = $this->page->hashQuery($sql)->result_array();
		$list = $this->db->lJoin($list, "goods", "id,name,thumbs,sp_val", "goods_id", "id", "goods_");
		if ($list) {
			foreach ($list as $k => $v) {
				$thumb                 = json_decode($v['goods_thumbs'], true);
				$list[$k]['img_cover'] = yunurl($thumb[0]['path']);
			}
		}
		return $list;
	}

	/*
		        * 获取助力活动详情
	*/
	function getOne($id, $field = "*") {
		$row = $this->db->get("select {$field} from {$this->baseTable} where id={$id}");
		if ($row) {
			$this->load->model("goods");
			$row['goods'] = $this->goods->get($row['goods_id']);
			$this->load->model('fav');
			$row['goods']['is_fav'] = $this->fav->is_fav($row['goods_id'], MID);
		}
		return $row;
	}

	/*
		        * 修改助力
	*/
	function save($post = array()) {

		if (empty($post['goods_id'])) {
			return array('code' => 10001, 'message' => '请选择商品');
		}
		if (empty($post['number'])) {
			return array('code' => 10002, 'message' => '助力次数不能为空');
		}
		if (empty($post['term'])) {
			return array('code' => 10002, 'message' => '期限不能为空');
		}

		if ($post['id'] > 0) {
			$res = $this->db->update($this->baseTable, $post, array("id" => $post['id']));
		} else {
			$is_res = $this->db->get("select 1 from {$this->baseTable} where goods_id={$post['goods_id']} ");
			if ($is_res) {
				return array('code' => 10003, 'message' => '该商品已报名');
			}
			$post['c_time'] = RUN_TIME;
			$res            = $this->db->insert($this->baseTable, $post);
		}
		if ($res !== false) {
			return array('code' => 0, 'message' => '操作成功');
		} else {
			return array('code' => 10005, 'message' => '操作失败');
		}

	}

	/*
		        * 获取助力记录详情
	*/
	function getLogOne($id, $field = "*") {
		$row = $this->db->get("select {$field} from {$this->logTable} where id={$id}");
		if ($row) {
			$row['end_time'] = $row['e_time'] - RUN_TIME;
			if ($row['end_time'] < 0) {
				$row['end_time'] = 0;
			}

			$row['username'] = $this->db->getstr("select username from ###_member where mid={$row['mid']}");
		}
		return $row;
	}

	/*
		        * 获取助力记录列表
	*/
	function getLogList($option = array()) {

		$page = isset($option['page']) ? $option['page'] : 1;

		$where = " 1 ";

		if ($option['where']) {
			$where .= $option['where'];
		}

		$this->load->model('page');

		$_GET['page'] = intval($page);

		if (isset($option['size'])) {
			$size = isset($option['size']) ? $option['size'] : '10';
		} else {
			$size = isset($this->common['page_listrows']) ? (int) $this->common['page_listrows'] : 10;
		}
		$this->page->set_vars(array('per' => $size));

		if (!isset($option['order'])) {
			$option['order'] = " id desc ";
		}
		#数据集

		$sql = "SELECT * FROM {$this->logTable} WHERE {$where} ORDER BY {$option['order']}";

		$list = $this->page->hashQuery($sql)->result_array();
		$list = $this->db->lJoin($list, "goods", "id,name,thumbs,sp_val", "goods_id", "id", "goods_");
		$list = $this->db->lJoin($list, "member", "mid,username", "mid", "mid");
		if ($list) {
			foreach ($list as $k => $v) {

                $list[$k]['photo'] = photo($v['mid']);

				$list[$k]['end_time'] = $list[$k]['e_time'] - RUN_TIME;
				if ($list[$k]['end_time'] < 0) {
					$list[$k]['end_time'] = 0;
				}

				$thumb                 = json_decode($v['goods_thumbs'], true);
				$list[$k]['img_cover'] = yunurl($thumb[0]['path']);
			}
		}
		return $list;
	}

	//生成助力
	function assist_log_save($post = array()) {

		if (empty($post['address_id'])) {
			return array('code' => 10001, 'message' => '收货地址不能为空');
		}
		if (empty($post['assist_id'])) {
			return array('code' => 10001, 'message' => '助力ID不能为空');
		}
		$row = $this->getOne($post['assist_id']);
		if (empty($row) || $row['status'] == 0) {
			return array('code' => 10001, 'message' => '助力不存在');
		}
		$res_has = $this->db->getstr("select id from {$this->logTable} where assist_id={$post['assist_id']} and e_time>" . RUN_TIME . " and status=0 and mid=" . MID);
		if ($res_has) {
			return array('code' => 10002, 'id' => $res_has, 'message' => '当前有未完成的活动');
		}

		//扣除活动库存
		$is_stock = $this->db->update($this->baseTable, 'stock=stock-1,sell=sell+1', 'stock>=1 and id=' . $post['assist_id']);
		if ($is_stock === false) {
			return array('code' => 10001, 'message' => '活动库存不足');
		}

		$data['assist_id'] = $post['assist_id']; //助力id
		$data['goods_id']  = $row['goods_id']; //商品id
		$data['spec']      = $post['spec']; //商品属性
		$data['mid']       = MID;
		$data['price']     = $row['price']; //助力原价
		$data['number']    = $row['number']; //助力总需人次
		$data['sid']       = $row['sid'];
		$data['is_app']    = $row['is_app'];
		$data['e_time']    = RUN_TIME + $row['term'] * 86400; //截止时间
		$data['c_time']    = RUN_TIME;

		//收货地址
		$address_info       = $this->member->get_address("mid='" . MID . "' AND id='" . $post['address_id'] . "'");
		$data['zone']       = $address_info['zone'];
		$data['area']       = $address_info['area'];
		$data['address']    = $address_info['address'];
		$data['mobile']     = $address_info['mobile'];
		$data['name']       = $address_info['name'];
		$data['address_id'] = $post['address_id'];

		$log_id = $this->db->insert($this->logTable, $data);
		if ($log_id > 0) {
			return array('code' => 0, 'message' => '助力成功', 'id' => $log_id);
		} else {
			return array('code' => 10001, 'message' => '助力失败');
		}

	}

	//帮忙助力
	function assist_help($log_id = 0, $mid = 0) {

		if (empty($log_id)) {
			return array('code' => 10001, 'message' => 'ID不能为空');
		}

		//每个助力只能帮一次
		$res_has = $this->db->getstr("select 1 from {$this->helpTable} where mid={$mid} and log_id={$log_id}");
		if ($res_has) {
			return array('code' => 10001, 'message' => '您已帮忙助力过');
		}

		$row = $this->getLogOne($log_id);

		if ($row['e_time'] < RUN_TIME) {
			return array('code' => 10001, 'message' => '该助力已过期');
		}

		if ($row['is_app'] == 0) {
//需要app助力
			$data['mid']    = $mid;
			$data['log_id'] = $log_id;
			$data['status'] = $row['is_app'];
			$data['c_time'] = RUN_TIME;
			$this->db->insert($this->helpTable, $data);
			return array('code' => 0, 'message' => '助力成功');
		}

		$num    = $row['number'] - $row['number_yes'];
		$status = $num == 1 ? 1 : 0;

		$res = $this->db->update($this->logTable, "number_yes = number_yes+1,status={$status}", " id={$log_id} and number_yes<number");
		if ($res !== false) {
			$data['mid']    = $mid;
			$data['log_id'] = $log_id;
			$data['status'] = $row['is_app'];
			$data['c_time'] = RUN_TIME;
			$this->db->insert($this->helpTable, $data);

			//助力成功后发送通知
			if ($status == 1) {

				$this->createOrder($log_id);

				$goods_name = $this->db->getstr("select name from ###_goods where id={$row['goods_id']}", "name");
				// 微信模版消息 refund_note 退款结果通知
				// wxtemplate_action start
				$msgParams = array(
					"url"      => note_url("/assist/log_list"),
					"keyword1" => $goods_name,
					"keyword2" => "0元",
				);
				$wxmsg[] = array(0 => $row['mid'], 1 => "assist_succ", 2 => $msgParams);
				$this->load->model('wxtemplate');
				//$this->wxtemplate->inQueueMany($wxmsg);
				// wxtemplate_action end
			}

			return array('code' => 0, 'message' => '助力成功');
		} else {
			return array('code' => 10001, 'message' => '助力失败');
		}

	}

	//帮忙助力
	function assist_help_app($mid = 0) {

		$log_id = $this->db->getstr("select log_id from {$this->helpTable} where mid={$mid} and status=0 order by id desc limit 1", "log_id");

		if (empty($log_id)) {
			return array('code' => 10001, 'message' => 'ID不能为空');
		}

		$row = $this->getLogOne($log_id);

		if ($row['e_time'] < RUN_TIME) {
			return array('code' => 10001, 'message' => '该助力已过期');
		}

		$num    = $row['number'] - $row['number_yes'];
		$status = $num == 1 ? 1 : 0;

		$res = $this->db->update($this->logTable, "number_yes = number_yes+1,status={$status}", " id={$log_id} and number_yes<number");
		if ($res !== false) {

			$this->db->update($this->helpTable, array("status" => 1), array("log_id" => $log_id, "mid" => $mid));

			//助力成功后发送通知
			if ($status == 1) {

				$this->createOrder($log_id);

				$goods_name = $this->db->getstr("select name from ###_goods where id={$row['goods_id']}", "name");
				// 微信模版消息 refund_note 退款结果通知
				// wxtemplate_action start
				$msgParams = array(
					"url"      => note_url("/assist/log_list"),
					"keyword1" => $goods_name,
					"keyword2" => "0元",
				);
				$wxmsg[] = array(0 => $row['mid'], 1 => "assist_succ", 2 => $msgParams);
				$this->load->model('wxtemplate');
				//$this->wxtemplate->inQueueMany($wxmsg);
				// wxtemplate_action end
			}

			return array('code' => 0, 'message' => '助力成功');
		} else {
			return array('code' => 10001, 'message' => '助力失败');
		}

	}

	//帮忙助力记录列表
	function getHelpLogList($log_id = 0) {
		$where           = " log_id={$log_id} ";
		$option['order'] = " id desc ";
		$list            = $this->db->select("SELECT * FROM {$this->helpTable} WHERE {$where} ORDER BY {$option['order']}");
		$list            = $this->db->lJoin($list, "member", "mid,username", "mid", "mid");
		return $list;
	}

	/*
		        * 帮忙助力详情
	*/
	function getHelpLogOne($log_id = 0, $mid = 0, $field = "*") {
		$where = " 1 ";
		if ($log_id > 0) {
			$where .= " and log_id=" . $log_id;
		}
		if ($mid > 0) {
			$where .= " and mid=" . $mid;
		}
		$row = $this->db->get("select {$field} from {$this->helpTable} where {$where}");
		return $row;
	}

	//创建订单
	function createOrder($log_id = 0) {
		$log = $this->db->get("select * from {$this->logTable} where id={$log_id}");
		if ($log['status'] != 1) {
			return fasle;
		}

		$this->load->model("flow");
		$order['order_sn'] = $this->flow->order_sn();

		$order['c_time']         = RUN_TIME;
		$order['mid']            = $log['mid'];
		$order['status_pay']     = 10;
		$order['extension_code'] = 0;
		$order['extension_id']   = $log['goods_id'];
		$order['zone']           = $log['zone'];
		$order['area']           = $log['area'];
		$order['address']        = $log['address'];
		$order['mobile']         = $log['mobile'];
		$order['name']           = $log['name'];
		$order['address_id']     = $log['addres_id'];
		$order['pay_name']       = '助力成功';
		$order['sid']            = $log['sid'];
		$order_id                = $this->db->save('goods_order', $order);
		if ($order_id > 0) {
			$this->load->model("goods");
			$goods      = $this->db->get("select `name`,`sp_val`,`price` from ###_goods where id={$log['goods_id']}");
			$data_goods = array(
				'mid'          => $log['mid'],
				'order_id'     => $order_id,
				'good_id'      => $log['goods_id'],
				'goods_spec'   => $this->goods->getSpec($goods['sp_val'], $log['spec']),
				'spec'         => $log['spec'],
				'goods_name'   => $goods['name'],
				'buy_num'      => 1,
				'sell_price'   => $goods['price'],
				'c_time'       => time(),
				'extension_id' => 0,
				'type'         => 0,
				'express_id'   => $_POST['express_id'],
			);
			$this->db->insert('goods_order_item', $data_goods);

			$this->db->update($this->logTable, array("order_id" => $order_id), array("id" => $log_id));
		}

	}

}