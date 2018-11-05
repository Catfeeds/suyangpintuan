<?php

/**

 * Class yunbuy_model

 */

class yunbuy_model extends Lowxp_Model {

	public $baseTable = '###_yunbuy';

	function __construct() {}

	//获取挖宝

	function getyunbuy($where = "", $page_size = 10, $page = 1, $feild = "*", $url = 'href="/yunbuy/{num}"') {

		#分页

		$this->load->model('page');

		$_GET['page'] = intval($page);

		$page_size = empty($page_size) ? (int) $this->site_config['page_size'] : $page_size;

		$this->page->set_vars(array('per' => $page_size, 'url' => $url));

		$where = empty($where) ? "buy_id <> 0" : $where;

		$list = $this->page->hashQuery("SELECT $feild FROM ###_yunbuy WHERE $where")->result_array();

		if ($list) {

			foreach ($list as $key => $val) {

				$val['jindu'] = sprintf("%.2f", $val['buy_num'] / $val['need_num'] * 100);

				$val = $this->getThumb($val, 1);

				$list[$key] = $val;

			}

		}

		$this->load->model('upload');

		$list = $this->upload->getImgUrls($list, 'cover', 'gallery', array('src'));

		return $list;

	}

	/** 重新获取

	 * @param array $val 一元数组

	 * @param int $type 1返回主图 2返回展示图集

	 */

	function getThumb($val, $type = 1, $sizeList = array('src')) {

		//主图

		$thumb = json_decode($val['thumb'], true);

		$thumbs = json_decode($val['thumbs'], true);

		if ($type == 2) {

			$pics = array();

			foreach ($thumbs as $k => $v) {

				$pics[$k]['imgurl_src'] = $v['path'];

			}

			return $pics;

		} else {

			if ($thumb[0]['path']) {

				$val['imgurl_src'] = $thumb[0]['path'];

			} else {

				$thumbs = json_decode($val['thumbs'], true);

				if ($thumbs[0]['path']) {

					$val['imgurl_src'] = $thumbs[0]['path'];

				}

			}

			foreach ($sizeList as $size) {

				if (isset($val['imgurl_src'])) {

					$val['imgurl_' . $size] = $val['imgurl_src'];

				}

			}

			return $val;

		}

	}

	//保存数据

	function save($post = '') {

		$post = empty($post) ? $_POST['post'] : $post;

		$id = intval($_POST['id']);

		$goods_id = intval($_POST['goods_id']);

		#表单处理

		if (empty($post['cid'])) {return array('code' => 10001, 'message' => '请选择商品分类!');}

		if (empty($post['bid'])) {return array('code' => 10001, 'message' => '请选择商品品牌!');}

		if (empty($post['title'])) {return array('code' => 10001, 'message' => '请输入商品名称!');}

		if ($this->base->validate($post['goods_price'], 'number') == false || trim($post['goods_price']) <= 0) {

			return array('code' => 10001, 'message' => '商品价格必须是大于0的数字!');

		}

		if ($this->base->validate($post['price'], 'number') == false || trim($post['price']) <= 0) {

			return array('code' => 10001, 'message' => '单次价格必须是大于0的数字!');

		}

		if ($this->base->validate($post['max_qishu'], 'number') == false || trim($post['max_qishu']) < 0) {

			return array('code' => 10001, 'message' => '最大期数必须是大于等于0的数字!');

		}

		#处理缩略图

		if (isset($post['thumb']) && !empty($post['thumb'])) {

			$arrData = array();

			foreach ($post['thumb']['path'] as $k => $v) {

				$arrData[$k]['path'] = $v;

				$arrData[$k]['title'] = $post['thumb']['title'][$k];

			}

			$post['thumb'] = json_encode($arrData);

		} else {

			return array('code' => 10001, 'message' => '请上传一张缩略图！');

		}

		#处理图集

		if (isset($post['thumbs']) && !empty($post['thumbs'])) {

			$arrData = array();

			foreach ($post['thumbs']['path'] as $k => $v) {

				$arrData[$k]['path'] = $v;

				$arrData[$k]['title'] = $post['thumbs']['title'][$k];

			}

			$post['thumbs'] = json_encode($arrData);

		} else {

			return array('code' => 10001, 'message' => '请上传至少一张展示图集！');

		}

		//先处理商品数据

		$goods = array(

			'cid'     => $post['cid'],

			'bid'     => $post['bid'],

			'name'    => $post['title'],

			'price'   => $post['custom_price'],

			'thumb'   => $post['thumb'],

			'thumbs'  => $post['thumbs'],

			'content' => $post['content'],

			'is_sale' => 1,

			'u_time'  => time(),

			'tips'    => '',

			'words'   => '',

		);

		//初始化参数

		if (empty($id)) {

			#插入商品

			$post['goods_id'] = $this->db->save('goods', $goods, '');

			if (empty($post['goods_id'])) {return array('code' => 10002, 'message' => '商品添加失败!');} //未知错误

			$post['need_num'] = ceil($post['goods_price'] / $post['price']);

			$post['qishu'] = 1;

			$post['buy_num'] = 0;

			$post['end_num'] = $post['need_num'];

		} else {

			#更新商品

			$post['goods_id'] = $goods_id;

			$res = $this->db->save('goods', $goods, '', array('id' => $goods_id));

			if (empty($res)) {return array('code' => 10002, 'message' => '商品更新失败!');} //未知错误

			$buy_num = $this->db->getstr("SELECT buy_num FROM ###_yunbuy WHERE buy_id = '$id'");

			if (empty($buy_num)) {

				$post['need_num'] = ceil($post['goods_price'] / $post['price']);

				$post['end_num'] = $post['need_num'];

			} else {

				unset($post['goods_price']);

			}

		}

		$post['add_time'] = RUN_TIME;

		#挖宝参数

		$post['buynum'] = (isset($post['buynum']) && !empty($post['buynum'])) ? intval($post['buynum']) : 0;

		$post['ext_info'] = serialize(array(

			'buynum'  => $post['buynum'],

			'usernum' => 0,

			'isreal'  => 0,

			'member'  => 0,

			'notjoin' => 0,

		));

		#保存

		$where = $id ? array('buy_id' => $id) : '';

		$res = $this->db->save($this->baseTable, $post, '', $where);

		if (empty($id) && empty($post['sid'])) {

			$new_id = $this->db->insert_id();

			$this->db->update($this->baseTable, array('sid' => $new_id), array('buy_id' => $new_id));

		}

		if (empty($res)) {return array('code' => 10002, 'message' => '数据操作失败!');} //未知错误

		if ($id) {

			admin_log('编辑一元云购商品：' . $post['title']);

			return array('code' => 0, 'type' => 'update', 'message' => '更新成功');

		} else {

			admin_log('添加一元云购商品：' . $post['title']);

			return array('code' => 0, 'type' => 'insert', 'message' => '添加成功');

		}

	}

	//查询状态

	function status_sql($status) {

		switch ($status) {

			case 1:

				$status_sql = " AND buy_num < need_num";

				break;

			case 2:

				$status_sql = " AND luck_code<> '0' ";

				break;

			case 3:

				$status_sql = " AND wait_time<> '' AND luck_code = '0'";

				break;

			case 4:

				$status_sql = " AND qishu = max_qishu ";

				break;

			default:

				$status_sql = '';

		}

		return $status_sql;

	}

	//挖宝状态

	function status($row) {

		if ($row['buy_num'] != $row['need_num']) {

			$status = "<span class='c-green'>进行中</span>";

		} elseif (!empty($row['wait_time']) && empty($row['luck_code'])) {

			$status = "<span class='c-orange'>待揭晓</span>";

		} elseif (!empty($row['luck_code'])) {

			$status = "<span class='c-red'>已揭晓</span>";

		}

		return $status;

	}

	//获取开奖结果

	function getlottery($qihao) {

		$row = $this->db->get("SELECT * FROM ###_lottery WHERE qihao = '$qihao'");

		return $row['code'];

	}

	//获取挖宝详情

	function yuninfo($id) {

		$row = $this->db->get("SELECT * FROM ###_yunbuy WHERE buy_id = $id");

		if (empty($row)) {
			return false;
		}

		$row['jindu'] = sprintf("%.2f", $row['buy_num'] / $row['need_num'] * 100);

		$row = $this->getThumb($row, 1, array('middle', 'src', 'thumb'));

		#商品图片

		if ($row['cover']) {

			$picdata = array('id' => $row['cover']);

			$this->load->model('upload');

			$row['show_cover'] = $this->upload->getGalleryImgUrls($picdata, array('middle', 'src', 'thumb'));

		}

		return $row;

	}

	//购物车商品

	function cartgoods($ids = '', $type = '') {

		$cartgoods = array();

		if ($_SESSION['mid']) {

			if (!empty($ids)) {
				$where = "AND id IN (" . implode(',', $ids) . ")";
			}

			if ($type) {
				$where .= " AND type = '$type'";
			}

			$cartgoods = $this->db->select("SELECT * FROM ###_cart WHERE mid = '$_SESSION[mid]' $where ORDER BY id DESC");

		} else {

			$cart_str = cookie('goodscart');

			if (get_magic_quotes_gpc()) {
				$cart_str = stripslashes($cart_str);
			}
//去除斜杠

			$cartgoods = unserialize($cart_str);

			$cartgoods = is_array($cartgoods) ? $cartgoods : array();

		}

		$cartgoods = $this->db->lJoin($cartgoods, 'goods', 'goods_id,thumb', 'goods_id', 'id');

		foreach ($cartgoods as $k => $v) {

			$v = $this->getThumb($v, 1, array('src', 'thumb'));

			$cartgoods[$k] = $v;

		}

		if (!empty($cartgoods)) {

			$this->load->model('upload');

			$cartgoods = $this->upload->getImgUrls($cartgoods, 'cover', 'gallery', array('src', 'thumb'));

		}

		return $cartgoods;

	}

	//购物车总价

	function cartTotal($ids = '', $type = '') {

		$total = 0;

		if ($_SESSION['mid']) {

			if (!empty($ids)) {
				$where = "AND id IN (" . implode(',', $ids) . ")";
			}

			if ($type) {
				$where .= " AND type = '$type'";
			}

			$total = $this->db->getstr("SELECT SUM(subtotal) AS total FROM ###_yuncart WHERE mid = '$_SESSION[mid]' $where", 'total');

		} else {

			$cartgoods = $this->cartgoods();

			$total = 0;

			foreach ($cartgoods as $goods) {

				$total += $goods['subtotal'];

			}

		}

		return $total;

	}

	//生成订单号

	function snOrder() {

		$order_sn = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), -8, 8);

		return $order_sn;

	}

	//清空购物车

	function emptyCart($ids = '') {

		$where = 'mid=' . $_SESSION['mid'];

		if (is_array($ids)) {

			$ids = implode(',', $ids);

		}

		$where .= " AND id IN($ids)";

		return $this->db->delete("yuncart", $where);

	}

	//订单信息

	function orderInfo($id = '', $where = '') {

		$where = $where ? $where : "order_id = '$id'";

		$row = $this->db->get("SELECT * FROM ###_yunorder WHERE $where");

		return $row;

	}

	//订单挖宝商品

	function orderDb($order_id) {

		$list = $this->db->select("SELECT * FROM ###_yundb WHERE order_id = '$order_id'");

		return $list;

	}

	//订单信息

	function dbInfo($id = '', $where = '') {

		$where = $where ? $where : "id = '$id'";

		$row = $this->db->get("SELECT * FROM ###_yundb WHERE $where");

		return $row;

	}

	//更新订单

	function updateOrder($update_arr, $order_id, $where = '') {

		$where = empty($where) ? array('order_id' => $order_id) : $where;

		return $this->db->update("###_yunorder", $update_arr, $where);

	}

	//更新挖宝商品

	function updateDb($update_arr, $id, $where = '') {

		$where = empty($where) ? array('id' => $id) : $where;

		return $this->db->update("###_yundb", $update_arr, $where);

	}

	//支付完成后分配挖宝号码

	function orderPayed($order_id) {

		$order = $this->orderInfo($order_id);

		if ($order['status'] == 2) {

			$orderdb = $this->orderDb($order_id);

			#返现给归属用户

			if ($this->site_config['order_back'] && $order['used_sharecode']) {

				$this->load->model('member');

				$share = $this->db->get("SELECT mid,id FROM ###_sharecode WHERE code = '$order[used_sharecode]'");

				$this->member->accountlog('admin', array('mid' => $share['mid'], 'user_money' => $this->site_config['order_back'], 'desc' => '分享码被使用返现收益'));

				$this->db->update("sharecode", "used_time = used_time+1", array('code' => $order['used_sharecode']));

				$insert_arr = array();

				$insert_arr['mid'] = $_SESSION['mid'];

				$insert_arr['username'] = $_SESSION['username'];

				$insert_arr['sid'] = $share['id'];

				$insert_arr['order_id'] = $order_id;

				$insert_arr['order_sn'] = $order['order_sn'];

				$insert_arr['use_time'] = RUN_TIME;

				$this->db->insert("usecode_log", $insert_arr);

			}

			if ($order['type'] != 2) {

				//奖励邀请用户佣金

				$this->load->model('member');

				$member = $this->member->member_info($_SESSION['mid']);

				if ($member['ivt_id']) {

					$ivt_member = $this->db->get("SELECT mid,username FROM ###_member WHERE mid = '$member[ivt_id]'");

					//减掉红包使用的金额

					$total = $order['total'];

					if ($order['user_bonus_id']) {

						$bonus_money = $this->db->getstr("SELECT SUM(money) FROM ###_member_bonus WHERE id IN(" . $order['user_bonus_id'] . ")");

						if (!empty($bonus_money) && $order['total'] >= $bonus_money) {

							$total -= $bonus_money;

						}

					}

					if ($this->site_config['ivtreg_db'] && !empty($ivt_member) && $total > 0) {

						if (strpos($this->site_config['ivtreg_db'], '%')) {

							$path = str_replace('%', '', $this->site_config['ivtreg_db']) / 100;

							$commission = $total * $path;

						} else {

							$commission = $this->site_config['ivtreg_db'];

						}

						$insert_arr = array();

						$insert_arr['mid'] = $ivt_member['mid'];

						$insert_arr['username'] = $ivt_member['username'];

						$insert_arr['ivt_mid'] = $_SESSION['mid'];

						$insert_arr['ivt_username'] = $_SESSION['username'];

						$insert_arr['order_id'] = $order['order_id'];

						$insert_arr['total'] = $total;

						$insert_arr['commission'] = $commission;

						$insert_arr['desc'] = "邀请会员参与云购(订单号$order[order_sn]),获得" . $this->site_config['ivtreg_db'] . "佣金收益";

						$this->member->save_commission($insert_arr);

					}

				}

			}

			foreach ($orderdb as $val) {

				//剩余人次

				$end_num = $this->db->getstr("SELECT end_num FROM ###_yunbuy WHERE buy_id = '$val[buy_id]'");

				//剩余人次小于购买数退还剩余金额

				if ($end_num < $val['qty']) {

					$back_points = $val['price'] * ($val['qty'] - $end_num);

					$log_arr = array();

					$log_arr['mid'] = $_SESSION['mid'];

					$log_arr['username'] = $_SESSION['username'];

					if ($val['type'] == 1) {

						$log_arr['db_points'] = $back_points;

						$log_arr['desc'] = '参与人次大于剩余人次，退还剩余云购币 云购订单' . $order['order_sn'];

					} else {

						$log_arr['pay_points'] = $back_points;

						$log_arr['desc'] = '参与人次大于剩余人次，退还剩余拍币 云购订单' . $order['order_sn'];

					}

					$this->member->accountlog('admin', $log_arr);

					$val['qty'] = $end_num;

					$is_back = true;

				}

				//更新商品信息

				$this->db->update($this->baseTable, "buy_num = buy_num+'$val[qty]' , end_num = end_num - '$val[qty]'", array('buy_id' => $val['buy_id']));

				//已生成抽奖号

				$order_arr = $this->db->select("SELECT * FROM ###_yundb WHERE buy_id = '$val[buy_id]' AND status = '2'");

				if ($order_arr) {

					$sold_code = '';

					foreach ($order_arr as $v) {

						$sold_code[] = $v['yun_code'];

					}

					$sold_code = implode(",", $sold_code);

				}

				$yunbuy = $this->yuninfo($val['buy_id']);

				//未生成抽奖号

				$newcode_arr = array();

				for ($i = 0; $i < $yunbuy['need_num']; $i++) {

					$newcode = 10000001 + $i;

					$newcode_arr[$newcode] = $newcode;

				}

				$sold_code = explode(",", $sold_code);

				if (!empty($sold_code)) {

					foreach ($sold_code as $c) {

						unset($newcode_arr[$c]);

					}

				}

				//生成抽奖号

				$lukycode_arr = array();

				for ($i = 0; $i < $val['qty']; $i++) {

					$luckcode = array_rand($newcode_arr);

					unset($newcode_arr[$luckcode]);

					$lukycode_arr[] = $luckcode;

				}

				$param = array();

				$param['yun_code'] = implode(',', $lukycode_arr);

				$param['db_time'] = microtime_float();

				$param['timenum'] = microtime_format($param['db_time'], 'Hisx');

				$param['status'] = 2;

				if ($is_back) {

					$param['qty'] = $val['qty'];

					$param['total'] = $val['qty'] * $val['price'];

				}

				//首次云购或使用分享码时生成分享码

				$order_count = $this->db->getstr("SELECT COUNT(*) FROM ###_yunorder WHERE mid = '$_SESSION[mid]' AND status = 2");

//                if(($order['used_sharecode'] || $order_count==1) && $this->site_config['order_share'] && $order['type']!=2){

//                    $this->load->model('sharecode');

//                    $code = $this->sharecode->creat_code($_SESSION['mid'],$this->site_config['order_share']);

//                    $this->updateOrder(array('sharecode'=>$code),$order_id);

//                    $param['sharecode'] = $code;

//                }

				//更新参与人数

				$this->setting->logCount($val['qty']);

				//更新订单

				$state = $this->updateDb($param, $val['id']);

				//刷新商品信息查询是否开奖

				$goods_info = $this->yuninfo($val['buy_id']);

				if ($goods_info['buy_num'] >= $goods_info['need_num']) {
					$this->wait_lottery($val['buy_id'], $param['db_time']);
				}

			}

		}

	}

	/**

	 * 更新待开奖商品

	 */

	function wait_lottery($buy_id, $last_dbtime) {

		$goods_info = $this->yuninfo($buy_id);

		$update_arr = array();

		$time = RUN_TIME;

		$hour = date('H', $time);

		$minute = date('i', $time);

		//彩期在每天白天10点至22点，夜场22点至凌晨2点开售；白天10分钟一期，夜场5分钟一期；每日期数：白天72期，夜场48期，共120期；

		if ($hour < 2) {

			$qihao = floor(($time - strtotime(date('Y-m-d 00:00:00', time()))) / 300) + 1;

			//2点不开奖 10点开24期

			if ($minute >= 55 && $hour == 1) {

				$waittime = strtotime(date('Y-m-d 10:03:00'));

			} else {

				$waittime = $time - $time % 300 + 480;

			}

		} elseif ($hour >= 2 && $hour < 10) {

			$qihao = 24;

			$waittime = strtotime(date('Y-m-d 10:03:00', $time));

		} elseif ($hour >= 10 && $hour < 22) {

			$qihao = floor(($time - strtotime(date('Y-m-d 10:00:00', time()))) / 600) + 25;

			$waittime = $time - $time % 600 + 780;

		} elseif ($hour >= 22) {

			//超过23:55明天第一期

			if ($hour == 23 && $minute > 55) {

				$update_arr['qihao'] = date('ymd', strtotime('+1 day')) . '001';

			} else {

				$qihao = floor(($time - strtotime(date('Y-m-d 22:00:00', time()))) / 300) + 97;

			}

			$waittime = $time - $time % 300 + 480;

		}

		$update_arr['qihao'] = empty($update_arr['qihao']) ? date('ymd', $time) . sprintf("%03d", $qihao) : $update_arr['qihao'];

		//原时时彩开奖时间

		//$update_arr['wait_time'] = $waittime;

		$update_arr['wait_time'] = RUN_TIME + 60 * 3;

		//参与记录时间和

		$update_arr['time_total'] = $this->sum_time($last_dbtime);

		$update_arr['last_dbtime'] = $last_dbtime;

		$update_arr['end_time'] = $waittime;

		$state = $this->db->update("###_yunbuy", $update_arr, array('buy_id' => $buy_id));

		//更新云购记录为待揭晓

		$this->updateDb(array('status' => 4), '', "buy_id='$buy_id' AND yun_code <> ''");

		$this->updateDjx();

		//得到最大期数

		$qishu = $this->db->getstr("SELECT qishu FROM ###_yunbuy WHERE sid='" . $goods_info['sid'] . "' ORDER BY buy_id DESC");

		//加入新一期产品

		if ($state && $qishu == $goods_info['qishu'] && $qishu < $goods_info['max_qishu']) {

			$insert_arr = array();

			$insert_arr['sid'] = $goods_info['sid'];

			$insert_arr['title'] = $goods_info['title'];

			$insert_arr['goods_id'] = $goods_info['goods_id'];

			$insert_arr['goods_subtit'] = $goods_info['goods_subtit'];

			$insert_arr['goods_price'] = $goods_info['goods_price'];

			$insert_arr['price'] = $goods_info['price'];

			$insert_arr['custom_price'] = $goods_info['custom_price'];

			$insert_arr['cover'] = $goods_info['cover'];

			$insert_arr['pics'] = $goods_info['pics'];

			$insert_arr['cid'] = $goods_info['cid'];

			$insert_arr['need_num'] = $goods_info['need_num'];

			$insert_arr['end_num'] = $goods_info['need_num'];

			$insert_arr['qishu'] = $qishu + 1;

			$insert_arr['max_qishu'] = $goods_info['max_qishu'];

			$insert_arr['is_rec'] = $goods_info['is_rec'];

			$insert_arr['listorders'] = $goods_info['listorders'];

			$insert_arr['keywords'] = $goods_info['keywords'];

			$insert_arr['description'] = $goods_info['description'];

			$insert_arr['ext_info'] = $goods_info['ext_info'];

			$insert_arr['type'] = $goods_info['type'];

			$insert_arr['is_show'] = 1;

			$insert_arr['add_time'] = RUN_TIME;

			$insert_arr['posid'] = $goods_info['posid'];

			$insert_arr['thumb'] = $goods_info['thumb'];

			$insert_arr['thumbs'] = $goods_info['thumbs'];

			$this->db->insert("###_yunbuy", $insert_arr);

		}

	}

	//开奖

	function lottery($buy_id) {

		$yuninfo = $this->yuninfo($buy_id);

		if ($yuninfo['end_num'] > 0) {
			return false;
		}

		//开奖号  原计算方式加入时时彩

		//$kjjg = $this->db->getstr("SELECT code FROM ###_lottery WHERE qihao = '$yuninfo[qihao]'",'code');

		//$kjjg = !empty($kjjg) ? $kjjg : '00000';

		$kjjg = 0;

		$luck_code = 10000001 + fmod(floatval($yuninfo['time_total'] + $kjjg), $yuninfo['need_num']);

		//查询中奖人更新开奖订单

		$db_info = $this->db->get("SELECT * FROM ###_yundb WHERE yun_code LIKE '%$luck_code%' AND buy_id = '$buy_id'");

		$state = 0;

		if ($db_info) {
			$state = $this->updateDb(array('luck_code' => $luck_code, 'status' => '3', 'fdis' => 1), $db_info['id']);
		}

		if ($state) {

			$update_arr = array();

			$update_arr['member_id'] = $db_info['mid'];

			$update_arr['member_name'] = $db_info['username'];

			$update_arr['luck_code'] = $luck_code;

			$update_arr['end_time'] = time();

			$update_arr['kjjg'] = $kjjg;

			$this->db->update("yunbuy", $update_arr, array('buy_id' => $buy_id));

			//更新云购记录为未中奖

			$this->updateDb(array('status' => 5), '', "buy_id='$buy_id' AND status <> 3 AND yun_code <> ''");

			$this->updateDjx();

			#发送中奖通知

			$db_member = $this->db->get("SELECT mobile,email,username FROM ###_member WHERE mid = '$db_info[mid]'");

			$this->smarty->assign('username', $db_member['username']);

			$this->smarty->assign('goodsname', $db_info['goods_name']);

			#发送中奖短信

			$setting = $this->setting->value("'sms_open'");

			if ($setting['sms_open'] == 1 && statusTpl('sms_cod')) {

				$this->load->library('sms');

				$ret = $this->sms->sendSmsTpl($db_member['mobile'], 'sms_cod');

			}

			#发送中奖邮件

			if ($db_info['email']) {

				$this->load->library('mail');

				$this->mail->sendMailTpl($db_member['email'], 'mail_cod');

			}

		}

	}

	//开奖前参与记录时间和

	function sum_time($time) {

		$num = 0;

		$list = $this->yunbuy->getyunDb("AND d.status <> 1 AND d.db_time <= '$time' ORDER BY db_time DESC", 100, 1);

		foreach ($list as $v) {

			$num += $v['timenum'];

		}

		return $num;

		//$sum_time = $this->db->get("SELECT SUM(timenum) as sum_time,COUNT(*) AS count FROM (SELECT timenum FROM ###_yundb WHERE status <> 1 AND db_time <= $time ORDER BY db_time DESC LIMIT 100) AS s");

		//return $sum_time['count']>=100 ? $sum_time['sum_time'] : '10000001';

		//return $sum_time['sum_time'];

	}

	//参与记录

	function getyunDb($where, $page_size = '99999', $page = 1, $feild = "", $url = 'href="/yunbuy/{num}"', $url_page = '') {

		$page_size = empty($page_size) ? (int) $this->site_config['page_size'] : $page_size;

		$where = empty($where) ? "d.buy_id <> 0" : $where;

		$sql = "FROM ###_yundb AS d ";

		$sql .= "LEFT JOIN ###_yunbuy AS y ON y.buy_id=d.buy_id ";

		$sql .= "WHERE d.id <> 0 $where";

		//返回数量

		if ($page_size == 'count') {

			$count = $this->db->getstr("SELECT COUNT(*) " . $sql);

			return $count;

		}

		#分页

		$this->load->model('page');

		$_GET['page'] = intval($page);

		$this->page->set_vars(array('per' => $page_size, 'url' => $url));

		//echo "SELECT d.*,y.thumb,y.thumbs".$feild." ".$sql;die;

		$list = $this->page->hashQuery("SELECT d.*,y.thumb,y.thumbs" . $feild . " " . $sql, $url_page)->result_array();

		if ($list) {

			$list = $this->db->lJoin($list, 'member', 'mid,nickname,photo', 'mid', 'mid');

			foreach ($list as $key => $val) {

				if (preg_match('/GROUP BY/', $where)) {

					//查询参与的同期记录

					$some_buy = $this->db->select("SELECT qty,yun_code FROM ###_yundb WHERE buy_id = '$val[buy_id]' AND mid = '$val[mid]' AND status <> 1 AND id <> '$val[id]' ");

					if (!empty($some_buy)) {

						foreach ($some_buy as $k => $v) {

							$val['qty'] += $v['qty'];

							$val['yun_code'] .= ',' . $v['yun_code'];

						}

					}

				}

				if ($val['goods_price'] > 0) {
					$val['need_num'] = ceil($val['goods_price'] / $val['price']);
				}

				$val = $this->getThumb($val, 1, array('src', 'thumb'));

				$list[$key] = $val;

			}

			$this->load->model('upload');

			$list = $this->upload->getImgUrls($list, 'cover', 'gallery', array('src', 'thumb'));

		}

		return $list;

	}

	//晒单

	function getShare($where, $page_size = '99999', $page = 1, $feild = "s.*", $url = 'href="/yunbuy/{num}"', $url_page = '') {

		#分页

		$this->load->model('page');

		$_GET['page'] = intval($page);

		$page_size = empty($page_size) ? (int) $this->site_config['page_size'] : $page_size;

		$this->page->set_vars(array('per' => $page_size, 'url' => $url));

		$where = empty($where) ? "s.id <> 0" : $where;

		$sql = "SELECT s.* FROM ###_share AS s WHERE " . $where;

		$list = $this->page->hashQuery($sql, $url_page)->result_array();

		$list = $this->db->lJoin($list, 'member', 'mid,nickname', 'mid', 'mid');

		if ($list) {

			foreach ($list as $key => $val) {

				if (in_array($val['extension_code'], array(CART_WIN, CART_AUC))) {

					$list[$key]['url'] = url('/auction/view/') . $val['obj_id'];

				} elseif ($val['extension_code'] == CART_DB) {

					$list[$key]['url'] = url('/yunbuy/detail/') . $val['obj_id'];

				}

				$list[$key]['thumbs'] = unserialize($val['thumbs']);

			}

		}

		return $list;

	}

	function getDbtotal($where) {

		$result = $this->db->getstr("SELECT COUNT(DISTINCT buy_id) AS count FROM ###_yundb WHERE $where");

		return $result;

	}

	/**云购商品购买限制

		     * @param $row

		     * @param $mid

		     * @param array $options

		     * @return array

	*/

	function extBuy($row, $mid, $options = array()) {

		$data = array('error' => 0);

		$qty = isset($options['qty']) ? intval($options['qty']) : 1;

		$ext_info = unserialize($row['ext_info']);

		#购买人次限制

		if ($ext_info['buynum'] > 0) {

			$buyCount = $this->db->getstr("SELECT SUM(qty) AS qty FROM ###_yundb WHERE mid = '$mid' AND status > 1 AND buy_id=" . $row['buy_id']);

			if (($buyCount + $qty) > $ext_info['buynum']) {

				$data['error'] = 1;

				$data['buynum'] = $ext_info['buynum'];

			}

		}

		#推荐人数限制

		if ($ext_info['usernum'] > 0) {

			$this->load->model('member');

			$options['where'] = (isset($ext_info['isreal']) && $ext_info['isreal'] == 1) ? " AND realname!=''" : '';

			$itvCount = $this->member->itvCount($mid, $options);

			if ($itvCount < $ext_info['usernum']) {

				$data['error'] = 2;

				$data['usernum'] = $ext_info['usernum'];

				$data['isreal'] = $ext_info['isreal'];

			}

		}

		#会员条件限制

		if (!empty($ext_info['member'])) {

			#7天内注册新会员

			if ($ext_info['member'] == '-1') {

				$member = $this->db->get("SELECT rank_id,c_time FROM ###_member WHERE mid = '$mid'");

				if (RUN_TIME - 3600 * 24 * 7 > $member['c_time']) {

					$data['error'] = 3;

					$data['error_text'] = '7天内注册的新会员才能参与云购';

				}

			}

		}

		#中奖后限制参与

		if ($ext_info['notjoin'] && $row['type'] == 2 && $_SESSION['mid']) {

			#查询是否中奖

			$luck_db = $this->db->getstr("SELECT COUNT(*) AS count FROM ###_yunbuy WHERE member_id = '$_SESSION[mid]' AND type = 2");

			if ($luck_db) {
				$data['error'] = 4;
			}

		}

		return $data;

	}

	//编辑晒单

	function saveShare() {

		$id = $_POST['id'];

		$post = $_POST['post'];

		#表单处理

		if (empty($post['obj_name'])) {return array('code' => 10001, 'message' => '请输入商品名称!');}

		if (empty($post['title'])) {return array('code' => 10001, 'message' => '请输入晒单标题!');}

		if (empty($post['content'])) {return array('code' => 10001, 'message' => '请输入晒单内容!');}

		#保存

		$res = $this->db->save('share', $post, '', array('id' => $id));

		if (empty($res)) {return array('code' => 10002, 'message' => '数据操作失败!');} //未知错误

		if ($id) {

			admin_log('编辑晒单：' . $post['title'] . "($id)");

			return array('code' => 0, 'type' => 'update', 'message' => '更新成功');

		} else {

			return array('code' => 1003, 'message' => '更新失败');

		}

	}

	/** 获取所有待揭晓云购商品 写入缓存 */

	function updateDjx($type = 0) {

		$array = array();

		$list = $this->base->read_static_cache('dbDjx', '');

		if ($type == 0 || ($type == 1 && $list == false)) {

			$sql = "SELECT * FROM ###_yunbuy " .

				"WHERE buy_id<>0 AND is_show=1 AND luck_code=0 AND last_dbtime>0 ORDER BY wait_time DESC";

			$list = $this->db->select($sql);

			#单独保存待揭晓的buy_id

			$ids = ',';

			if ($list) {

				foreach ($list as $k => $v) {

					$v = $this->getThumb($v);

					$list[$k] = $v;

					$ids .= $v['buy_id'] . ',';

				}

			}

			#获取图像

			$this->load->model('upload');

			$list = $this->upload->getImgUrls($list, 'cover', 'gallery', array('src'));

			$this->base->write_static_cache('dbDjx', array('ids' => $ids, 'list' => $list), '');

		} else {

			$array = $list;

		}

		return $array;

	}

}