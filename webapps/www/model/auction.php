<?php

/**
 * Class auction_model
 */
class auction_model extends Lowxp_Model {

	public $baseTable = '###_goods_activity';

	/**促销分类
     */
	public $actTypes = array(
		CART_KILL => array('id' => CART_KILL, 'title' => '秒杀'),
                CART_SALE => array('id' => CART_SALE, 'title' => '特价'),
                CART_FREE => array('id' => CART_FREE, 'title' => '免费试用'),
                CART_LUCK => array('id' => CART_LUCK, 'title' => '抽奖'),
	);

	public $status = array(
		UNDER_WAY => array('title' => '正在进行', 'class' => ''),
		PRE_START => array('title' => '即将开始', 'class' => 'mall_be'),
		FINISHED  => array('title' => '已结束', 'class' => 'mall_end'),
	);

	function __construct() {}

	/**获取促销类别名
		     * @param $key
		     * @return array
	*/
	function getType($key) {
		if ($this->actTypes[$key]) {
			return $this->actTypes[$key];
		} else {
			return array();
		}
	}

	//保存数据
	function save() {
		$post     = $_POST['post'];
		$id       = intval($_POST['id']);
		$act_type = intval($post['act_type']);

		#表单处理
		if (empty($post['act_name'])) {return array('code' => 10001, 'message' => '请输入促销标题');}
		if ($act_type != CART_PACK) {
			if (empty($post['goods_id'])) {return array('code' => 10001, 'message' => '请搜索并关联商品!');}
			if (empty($post['start_time']) || empty($post['end_time'])) {return array('code' => 10001, 'message' => '请选择活动起始时间!');}
		}

		if ($act_type != CART_CRDT) {
			if ($this->base->validate($post['act_price'], 'number') == false || trim($post['act_price']) < 0) {
				return array('code' => 10001, 'message' => '价格必须是大于等于0的数字!');
			}
		}

		#初始值 格式化
		$post['start_time'] = strtotime($post['start_time']);
		$post['end_time']   = strtotime($post['end_time']);

		$row = array();
		if (!$id) {
			#同一个商品不能同时上线
			$tmp = $this->getGoodsAuction($post['goods_id'], $act_type);
			if ($tmp['start_time'] < time() && $tmp['end_time'] > time()) {
				return array('code' => 10001, 'message' => '该商品正在' . $this->actTypes[$act_type]['title'] . '促销中!');
			}

			$qishu         = $this->db->getstr("SELECT MAX(qishu) FROM " . $this->baseTable . " WHERE goods_id=" . $post['goods_id'] . " AND act_type=" . $act_type);
			$post['qishu'] = $qishu + 1;
		}

		#竞拍参数
		/*$post['ext_info'] = serialize(array(
			'min_num' => isset($post['min_num']) ? intval($post['min_num']) : 0,
			'max_num' => isset($post['max_num']) ? intval($post['max_num']) : 0,
			'act_day' => isset($post['act_day']) ? intval($post['act_day']) : 0,
		));*/

		#处理缩略图
		if ($act_type == CART_PACK) {
			if (isset($post['act_thumb']) && !empty($post['act_thumb'])) {
				$arrData = array();
				foreach ($post['act_thumb']['path'] as $k => $v) {
					$arrData[$k]['path']  = $v;
					$arrData[$k]['title'] = $post['act_thumb']['title'][$k];
				}
				$post['act_thumb'] = json_encode($arrData);
			} else {
				return array('code' => 10003, 'message' => '请上传套餐展示图!');
			}
		}

		#保存
		$where = $id ? array('act_id' => $id) : '';
		$res   = $this->db->save($this->baseTable, $post, '', $where);

		if ($res === false) {return array('code' => 10002, 'message' => '数据操作失败!');}
		if ($id) {
			admin_log('更新促销商品：' . $post['title']);
			S("C_AUCTION_" . $id, NULL);
			return array('code' => 0, 'type' => 'update', 'message' => '更新成功');
		} else {
			admin_log('添加促销商品：' . $post['title']);
			return array('code' => 0, 'type' => 'insert', 'message' => '添加成功');
		}
	}

	/**根据商品分类json获取套餐商品列表
        * @param string $goods_list
        * @param string $order_sn
        * @return array|mixed|string
	*/
	function getPackGoods($goods_list = '', $order_sn = '') {
		$this->load->model('goods');
		$goods_list = is_array($goods_list) ? $goods_list : json_decode($goods_list, true);
		$goods_list = is_array($goods_list) ? $goods_list : array();
		if ($goods_list) {
			foreach ($goods_list as $k => $v) {
				$v['list']      = $this->goods->getList(30, 1, $v['cat_id'], 0);
				$goods_list[$k] = $v;
			}
		}
		return $goods_list;
	}

	//促销商品详情
	function get($id, $options = array()) {
		$id = intval($id);
		//if (!S("C_AUCTION_" . $id)) {
			$sql = "SELECT a.* FROM " . $this->baseTable . " AS a WHERE a.act_id='$id'";
			$row = $this->db->get($sql);
			if (empty($row)) {
				return false;
			}

			if ($row['act_type'] == CART_PACK) {
				$goods_list        = !empty($row['goods_list']) ? json_decode($row['goods_list'], true) : array();
				$goods_list        = is_array($goods_list) ? $goods_list : array();
				$row['goods_list'] = $goods_list;
			}
			$this->load->model('goods');
			$row['goods_info'] = $this->goods->get($row['goods_id']);
			S("C_AUCTION_" .  $id, $row);
		//} else {
		//	$row = S("C_AUCTION_" . $id);
		//}

		return $row;
	}

	/** 前台全局促销商品列表
	 * @param int $size
	 * @param int $page
	 * @param int $id
	 * @param string $status
	 * @param int $type
	 * @param array $options
	 * @return array|mixed
	 */
	function getList($size = 10, $page = 1, $id = 0, $status = '', $type = 0, $options = array()) {
		$table        = $this->baseTable;
                
		$where        = ' WHERE 1 ';
		$orderDefault = 'a.end_time ASC,a.act_id DESC';
		$now          = time();
		$list         = array();
                if($type>0){
                    $where  .= ' AND a.act_type=' . $type;                    
                }
		#按商品分类ID
		if ($id) {
                    $this->load->model('goodscat');
                    $where .= " AND g.cid" . $this->goodscat->condArrchild($id);
		}

		#按拍卖状态
		$sqlStatus = $this->status_sql($status, 'a.');
		$where .= $sqlStatus ? (' AND ' . $sqlStatus) : '';
		switch ($status) {
			case PRE_START: #未开始
				$orderDefault = 'a.start_time ASC';
				break;
			case FINISHED: #已结束
				$orderDefault = 'a.act_id DESC';
				break;
			default:break;
		}

		#按关键词搜索
		if (isset($options['k'])) {
			if (isset($options['q']) && !empty($options['q'])) {
				$where .= " AND a.act_name LIKE '%" . htmlspecialchars(trim($options['q'])) . "%'";
			}
		}

		#排序
		$order = ' ORDER BY ';
		$order .= isset($options['order']) ? $options['order'] : $orderDefault;

		#分组去重复
		$group = ' GROUP BY ';
		if ($status == FINISHED_ALL) {
			$group .= " tt.goods_id ";
		} else {
			$group .= " tt.act_id ";
		}

		#分页
		$this->load->model('page');
		$_GET['page'] = intval($page);
		$this->page->set_vars(array('per' => $size));

		$sqlPublic = "FROM " . $table . " AS a " .
			"LEFT JOIN ###_goods AS g ON g.id=a.goods_id " . $where;

		#size=0时，返回记录数
		if ($size == 0) {
			$sql   = "SELECT count(DISTINCT a.act_id) " . $sqlPublic;
			$count = $this->db->getstr($sql);
			return $count;
		}

		#内容列表结果
		$urlQuery = isset($options['url']) ? $options['url'] : "$type/$status/$id/";
		$target   = isset($options['target']) ? $options['target'] : "";
		$sql      = "SELECT tt.* FROM (SELECT a.*,g.thumb,g.price,g.unit " . $sqlPublic . ") AS tt" . $group . str_replace('a.', 'tt.', $order);
		$res      = $this->page->hashQuery($sql, $urlQuery, $target)->result_array();

		#格式化内容
		foreach ($res as $row) {
			$row = $this->getFormat($row, $options);

			#其它参数
			$row['img_w']    = isset($options['img_w']) ? $options['img_w'] : 180;
			$row['img_h']    = isset($options['img_h']) ? $options['img_h'] : 180;
			$row['img_type'] = isset($options['img_type']) ? $options['img_type'] : 1;
			$row['key']      = isset($options['key']) ? $options['key'] : '';

			$row['url'] = $row['act_type']==CART_TUAN?url('/tuan/show/' . $row['act_id']):url('/kill/show/' . $row['act_id']);
			$list[]     = $row;
		}
		return $list;
	}

	/**
	 * 计算拍卖活动状态（注意参数一定是原始信息）
	 * @param   array   $auction    拍卖活动原始信息
	 * @return  int
	 */
	function status($auction) {
		$now = time();
		if ($auction['is_finished'] == 0) {
			if ($now < $auction['start_time']) {
				return PRE_START; // 未开始
			} elseif ($now > $auction['end_time']) {
				return FINISHED; // 已结束，未处理
			} else {
				return UNDER_WAY; // 进行中
			}
		} elseif ($auction['is_finished'] == 1) {
			return FINISHED; // 已结束，未处理
		} else {
			return SETTLED; // 已结束，已处理
		}
	}

	/**
	 * 查询竞拍状态的sql
	 * @param   string  $status 状态
	 * @param   string  $alias  竞拍表的别名（包括.例如 a.）
	 * @return  string
	 */
	function status_sql($status = '', $alias = '') {
		$sql = '';
		$now = time();
		if ($status === '') {
			return '';
		}

		switch ($status) {
			case PRE_START: #未开始
				$sql = $alias . "start_time>'$now' AND " . $alias . "is_finished=0";
				break;
			case UNDER_WAY: #进行中
				$sql = $alias . "start_time<='$now' AND " . $alias . "end_time>='$now' AND " . $alias . "is_finished=0";
				break;
			case FINISHED: #已结束（未处理）
				$sql = "((" . $alias . "end_time<'$now' AND is_finished=0) OR " . $alias . "is_finished=1)";
				break;
			case SETTLED: #已处理
				$sql = $alias . "is_finished=2";
				break;
			case FINISHED_ALL: #已结束（包含已处理）
				$sql = "((" . $alias . "end_time<'$now' AND is_finished=0) OR " . $alias . "is_finished=1 OR " . $alias . "is_finished=2)";
				break;
			default:
				break;
		}

		return $sql;
	}

	/** 竞拍公用数据处理
	 * @param $row
	 * @param array $options
	 * @return array
	 */
	private function getFormat($row, $options = array()) {
		$ext_info = unserialize($row['ext_info']);
		$row      = array_merge($row, $ext_info);

		#竞拍状态
		$row['status'] = $this->status($row);

		#获取时间差
		if ($row['status'] == PRE_START) {
			$row['diff_time'] = $row['start_time'] - time();
		} else {
			$row['diff_time'] = $row['end_time'] - time();
		}

		#标题加关键词处理
		if (!empty($row['words'])) {
			$words = explode('|', $row['words']);
			foreach ($words as $v) {
				$v = trim($v);
				if ($v && strpos($row['act_name'], $v) !== false) {
					$row['act_name'] = str_replace($v, "<font class='color01'>" . $v . "</font>", $row['act_name']);
				}
			}
		}

		#标题默认加期数
		$qishu = isset($options['qishu']) ? $options['qishu'] : 1;
		//$row['act_name'] = ($qishu ? '(第'.$row['qishu'].'期) ' : '').$row['act_name'];
		$row['act_name'] = $row['act_name'];

		return $row;
	}

	/** 根据商品ID获取促销商品ID
	 * @param $goods_id
	 * @param int $act_type
	 * @return mixed
	 */
	function getGoodsAuction($goods_id, $act_type = CART_KILL) {
		$sql = "SELECT * FROM " . $this->baseTable . " WHERE goods_id=" . $goods_id . " AND act_type=" . $act_type;
		$row = $this->db->get($sql);
		return $row;
	}

	//统计搜索词
	function setSearch($q = '') {
		$q = trim($q);
		if (empty($q)) {
			return false;
		}

		$where = '';
		$post  = array(
			'word'      => $q,
			'last_time' => time(),
		);

		$sql = "SELECT * FROM ###_search " .
			"WHERE `word`='$q'";
		$row = $this->db->get($sql);

		if ($row) {
			$where = array('word' => $q);
			$post  = array_merge($post, array(
				'qty' => $row['qty'] + 1,
				'ips' => $row['ips'] . '|' . getIP(),
			));
		} else {
			$post = array_merge($post, array(
				'qty'        => 1,
				'ips'        => getIP(),
				'start_time' => time(),
			));
		}

		$this->db->save('search', $post, '', $where);
	}

	/** 判断促销商品是否可以下单
	 * @param $row
	 * @param $id
	 * @param $qty
	 * @param $type
	 */
	function is_order_auction($id, $qty, $type) {
		$data = array('msg' => '');

		$row      = $this->get($id);
		$ext_info = unserialize($row['ext_info']);
		$row      = array_merge($row, $ext_info);

		if (!$row) {
			$data['msg'] = '促销商品不存在！';
		}
		if ($row['act_type'] != CART_PACK) {
			if ($row['start_time'] > time()) {
				$data['msg'] = '该商品促销还没开始！';
			}
			if ($row['end_time'] < time()) {
				$data['msg'] = '该商品促销已经结束！';
			}
		}
		if ($qty > ($row['act_stock'])) {
			$data['msg'] = '库存不足！';
		}
		if ($row['act_type'] == CART_KILL && $qty > $row['max_num']) {
			$data['msg'] = '购买数量不能大于' . $row['max_num'];
			return $data;
		}
		if ($row['act_type'] == CART_TUAN && $qty < $row['min_num']) {
			$data['msg'] = '购买数量不能小于' . $row['min_num'];
		}
		if ($row['act_type'] == CART_CRDT) {
			$this->load->model('member');
			$member  = $this->member->member_info(MID, 'pay_points');
			$max_num = floor($member['pay_points'] / $row['act_points']);
			if ($max_num < $qty) {
				$data['msg'] = '您当前积分最大兑换数量为' . $max_num;
			}
		}

		//当前会员已购买数量
		$sql = "SELECT SUM(goi.buy_num) FROM ###_goods_order_item AS goi " .
			"LEFT JOIN ###_goods_order AS go ON go.id=goi.order_id " .
			"WHERE go.extension_code='" . $row['act_type'] . "' AND goi.extension_id='" . $row['act_id'] . "' AND " .
			" go.status_order IN(0,10) AND go.mid=" . $_SESSION['mid'];
		$number = $this->db->getstr($sql);
		if ($row['act_type'] == CART_KILL && ($qty + $number) > $row['max_num']) {
			$data['msg'] = '您已经购买了此商品！';
		}
		return $data;
	}

	/*
		     * 获取促销活动的销量
	*/
	function getActSell($id) {

		return $this->db->getstr("select act_sell from ###_goods_activity where act_id={$id}", "act_sell");

	}

}