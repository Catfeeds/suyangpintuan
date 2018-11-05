<?php
/**
 * Class coupon_model
 * 优惠券抵扣时
 */
class coupon_model extends Lowxp_Model {

	public $baseTable = '###_coupon';
	public $logTable  = '###_coupon_log';

	// 发送类型
	public $sendTypes = array(
		0 => '指定发放',
		// 1 => '积分兑换',
		2 => '扫码获取',
		3 => '购物赠送',
		4 => '分享获得',
		5 => '中奖获得',
	);

	// 分享类型
	public $shareTypes = array(
		0 => '不可分享',
		1 => '分享母券',
		2 => '分享子券',
	);

	// 基础状态
	public $baseStatus = array(
		0 => '禁用',
		1 => '启用',
	);

	// 优惠券记录状态
	public $logStatus = array(
		0 => '禁用',
		1 => '未用',
		2 => '已用',
		3 => '冻结',
	);

	/**
	 * 根据id获取优惠券配置信息
	 * @param  integer $id 优惠券id
	 * @return array
	 */
	public function getRow($id) {
		$row = $this->db->get('SELECT * FROM ' . $this->baseTable . ' WHERE id = ' . $id . ' LIMIT 1');
		return empty($row) ? array() : $row;
	}

	/**
	 * 根据id获取优惠券记录信息
	 * @param  integer $id 优惠券记录id
	 * @return array
	 */
	public function getCouponLog($id) {
		$row = $this->db->get('SELECT * FROM ' . $this->logTable . ' WHERE id = ' . $id . ' LIMIT 1');
		return empty($row) ? array() : $row;
	}
	
	/**
	 * 根据id,mid检查使用的真实性，和准确性，避免恶意修改
	 * @param integer $id 优惠券记录id
	 * @param integer $mid 会员id
	 * @return array
	 */
	public function checkCoupon($id,$mid,$sid) {
		$error = 0;
		$row = $this->getCouponLog($id);
		if($row['status']!=1){
			$msg = "该优惠券已使用！";
			$error = 1;
		}
		if($row['mid']!=$mid){
			$msg = "该优惠券不属于您！";
			$error = 1;
		}
		if($row['sid']>0&&$row['sid']!=$sid){
			$msg = "该优惠券不能用于此商家！";
			$error = 1;
		}
		$data = array();
		if($error !=1 ){
			//如果检查正常，返回抵用券价格
			$coupon = $this->getRow($row['coupon_id']);
			$data['amount'] = $coupon['amount'];
			$error = 0;
			$msg = "";
		}
		$data['error'] = $error;
		$data['msg'] = $msg;
		return $data;
	}

	/**
	 * 根据id获取优惠券的完整信息
	 * @param  integer $id 优惠券记录id
	 * @return array
	 */
	public function getFullCouponLog($id) {
		$sql = 'SELECT a.id,a.mid,a.send_type,a.coupon_id,a.pid,a.order_id,a.share,a.share_time,a.use_time,a.create_time,a.expire_time,a.status,b.title,b.type,b.target,b.amount,b.need_amount,b.stock,b.sended,b.used,b.uniqued,b.exchange,b.score,b.paid,b.paid_goodscat,b.paid_amount FROM `###_coupon_log` a,`###_coupon` b WHERE a.coupon_id = b.id AND a.id=' . $id;
		$row = $this->db->get($sql);
		return empty($row) ? array() : $row;
	}

	/**
	 * 获取有效的优惠券列表 供其他模块选择 例如作为大转盘的奖品
	 * @return array
	 */
	public function getAvailableCouponList($where = '') {
		$sql  = 'SELECT * FROM `###_coupon` WHERE status=1 AND ' . RUN_TIME . '>start_time AND (end_time = 0 OR end_time > ' . RUN_TIME . ')'.$where;
		$list = $this->db->select($sql);
		return empty($list) ? array() : $list;

	}

	/**
	 * 保存优惠券的设置信息
	 * @return json
	 */
	function save($post) {

		$res = array('error' => 1, 'msg' => '编辑失败');

		// 安全过滤 start
		if (empty($post['stock']) || !filter_var($post['stock'], FILTER_VALIDATE_INT)) {
			$res['msg'] = '发放数量须是正整数';
			return $res;
		}

		if (empty($post['amount']) || $post['amount'] < 0 || !filter_var($post['amount'], FILTER_VALIDATE_INT)) {
			$res['msg'] = '优惠金额必须是正整数';
			return $res;
		}

		if (!empty($post['score']) && ($post['score'] < 0 || !filter_var($post['score'], FILTER_VALIDATE_INT))) {
			$res['msg'] = '兑换积分可以是不设置或0或正整数';
			return $res;
		}

		if (isset($post['need_amount']) && $post['need_amount'] != 0) {

			// 有使用优惠券所需金额限制
			if ($post['need_amount'] < 0 || !filter_var($post['need_amount'], FILTER_VALIDATE_INT)) {
				$res['msg'] = '使用优惠券所需金额必须是正整数';
				return $res;
			}

			// 这里暂时注释掉 允许满1元减5元类似的 促销活动优惠券
			// if ($post['amount'] > $post['need_amount']) {
			// 	$res['msg'] = '优惠金额不能大于使用优惠券所需金额';
			// 	return $res;
			// }
		}

		// 安全过滤 end
		$post['start_time'] = strtotime($post['start_time']);
		$post['end_time']   = strtotime($post['end_time']);

		// 设置默认值
		$data = array(
			'paid'     => 0,
			'share'    => 0,
			'uniqued'  => 0,
			'exchange' => 0,
		);

		$data = array_merge($data, $post);

		if (empty($post['id'])) {
			// admin_log('新增优惠券：' . $post['title']);
			$this->db->insert('coupon', $data);
			$res['error'] = 0;
			$res['msg']   = '新增成功';
		} else {

			// 部分字段禁止修改
			// 后期优化性能 可以考虑将本 need_amount amount target target_name 一起存入 coupon_log 然后即可删除下面的几条限制条件判断
			$coupon = $this->db->get('SELECT * FROM `###_coupon` WHERE id = ' . $data['id']);
			if ($coupon['sended']) {

				if ($coupon['amount'] != $data['amount']) {
					$res['msg'] = '为保证用户体验,已经被领取的券 优惠金额不可再修改';
					return $res;
				}

				if ($coupon['need_amount'] != $data['need_amount']) {
					$res['msg'] = '为保证用户体验,已经被领取的券 需满足金额不可再修改';
					return $res;
				}

				if ($coupon['target'] != $data['target']) {
					$res['msg'] = '为保证用户体验,已经被领取的券 品类限制不可再修改';
					return $res;
				}

			}

			// admin_log('编辑优惠券' . $post['title']);
			if (false !== $this->db->update('coupon', $data, 'id=' . $post['id'])) {
				$res['error'] = 0;
				$res['msg']   = '编辑成功';
			}

		}

		return $res;
	}

	##分享型优惠券数据结构
	// 暂时只支持分享一次的情况
	// status 状态 0 不显示 1 正常 2已使用 3冻结中
	// 所有类型的券 status = 1 且无 use_time 才可以使用
	// 所有券使用的时候 设置use_time= time(); 并且 status = 2

	// 分享型母券特点
	// 有share 为1 为分享型母券
	// 无share_time status 为 3 表示没有生成子券 share为1 无share_time的母券可以生成子券
	// 有share_time status 为 3 表示已经生成子券 但是子券未被使用
	// 有share_time status 为 1 表示分享的子券已经使用 本母券可以使用

	// 分享型子券特点
	// 有pid 有share 为2 为 分享型子券
	// 只能通过分享获得,并且只有被分享才会有pid
	// 所以所有有pid的券都是子券 并且跟母券成对出现
	// 有pid的券 被使用的时候 需要把pid对应的券的 status 设置为1

	/**
	 * 发放优惠券 分享型优惠券需要提前查询分享标记
	 * 注意发放后需要 回写优惠券发送张数到 coupon表
	 * 批量发送的时候 批量调用
	 * @param  integer $mid        为整数型用户id
	 * @param  integer $couponId   优惠券id
	 * @param  integer $status     发送状态
	 * @param  integer $expireTime 过期时间默认是不过期
	 * @param  integer $sendType   获取方式
	 * @param  integer $sid        商家id
	 * @return integer             插入的优惠券记录id
	 */
	public function send($mid, $couponId, $expireTime = 0, $status = 1, $share = 0, $sendType = 0,$sid=0) {

		$data = array(
			'mid'         => $mid,
			'send_type'   => $sendType,
			'coupon_id'   => $couponId,
			'pid'         => 0,
			'share'       => $share,
			'share_time'  => 0,
			'order_id'    => 0,
			'use_time'    => 0,
			'create_time' => RUN_TIME,
			'expire_time' => $expireTime,
			'status'      => $status,
			'sid'      => $sid,
		);

		return $this->db->insert('coupon_log', $data);

	}

	/**
	 * 发送单张优惠券的方法
	 * @param  integer $mid      用户id
	 * @param  integer $couponId 优惠券类别id
	 * @param  integer $sendType 领取方式id
	 * @return boolean           成功true 失败 false;
	 */
	public function sendOne($mid, $couponId, $sendType = 0) {
		$coupon = $this->db->get('SELECT * FROM ' . $this->baseTable . ' WHERE status = 1 and id=' . $couponId);
		if (empty($coupon['stock'])) {
			return false;
		}

		// 分享型优惠券刚拿到时是冻结的;
		$status = $coupon['share'] ? 3 : 1;

		// 目前过期时间就是优惠券类别的过期时间
		$logId = $this->send($mid, $coupon['id'], $coupon['end_time'], $status, $coupon['share'], $sendType,$coupon['sid']);

		if ($this->db->update('coupon', 'stock=stock-1,sended=sended+1', 'id=' . $couponId . ' AND stock>0')) {

			// 模版消息 20 获得优惠券 {插入优惠券面值},{插入店铺}
			// template_msg_action start
			/*$this->load->model('template_msg');
			$msgParams = array($coupon['amount'], C('site_name'));
			$this->template_msg->inQueue(20, $mid, $msgParams);*/
			// template_msg_action end

			return true;
		} else {
			$this->db->delete('coupon_log', 'id=' . $logId);
			return false;
		}

	}

	/**
	 * 批量发送优惠券 通常是手工发送 mids只有一个的时候是发单张, 如sendOne相比性能相差不大
	 * @param  string  $mids     用户id 或id逗号分隔符集合
	 * @param  integer $couponId 优惠券类别id
	 * @param  integer $sendType 领取方式id
	 * @return integer           发送成功的数量
	 */
	public function sendMany($mids, $couponId, $sendType = 0) {

		$coupon = $this->db->get('SELECT * FROM ' . $this->baseTable . ' WHERE status = 1 and id=' . $couponId);
		if (empty($coupon['stock'])) {
			return false;
		}

		$ids = explode(',', $mids);
		$ids = array_chunk($ids, $coupon['stock']);

		if (!$ids) {
			return false;
		}

		$logs = array();
		foreach ($ids[0] as $v) {
			$logs[]['mid'] = $v;
		}

		// 分享型优惠券刚拿到时是冻结的;
		$status = $coupon['share'] ? 3 : 1;

		$extInfo = array(
			'coupon_id'   => $coupon['id'],
			'share'       => $coupon['share'],
			'expire_time' => $coupon['end_time'],
			'create_time' => RUN_TIME,
			'status'      => $status,
			'send_type'   => $sendType,

		);

		$inserted = $this->db->lockInsertAll('coupon_log', $logs, $extInfo, 'id');
		if (!$inserted) {
			return false;
		}

		$count = count($inserted);

		// 尽量在访问量低时进行批量操作
		if ($this->db->update('coupon', 'stock=stock-' . $count . ',sended=sended+' . $count, 'id=' . $couponId . ' AND stock=' . $coupon['stock'])) {
			$targetIds = join(',', array_column($inserted, 'mid'));

			// 模版消息 20 获得优惠券 {插入优惠券面值},{插入店铺}
			// template_msg_action start
			$this->load->model('template_msg');
			$msgParams = array($coupon['amount'], C('site_name'));

			$this->template_msg->inQueue(20, $targetIds, $msgParams);
			// template_msg_action end

			return $inserted;
		} else {
			$insertedIds = join(',', array_column($inserted, 'id'));
			// 这个建立在 insert id连续的基础上 否则会出错
			$this->db->delete('coupon_log', 'id in (' . $insertedIds . ')');
			return false;
		};

	}

	/**
	 * 分享优惠券生成子券 不用事务有可能被分享两次
	 * @param  integer $mid         被分享人的mid
	 * @param  integer $couponLogId 被分享的优惠券记录ID
	 * @return json
	 */
	public function share($mid, $couponLogId) {
		$res = array(
			'error' => 1,
		);

		if (!(filter_var($mid, FILTER_VALIDATE_INT) && filter_var($couponLogId, FILTER_VALIDATE_INT))) {
			$res['msg'] = '对不起,参数错误';
			return $res;
		}

		$couponLog = $this->getCouponLog($couponLogId);

		if (empty($couponLog['share']) && 1 == $couponLog['share']) {
			$res['msg'] = '对不起,只有分享型母券才可以被分享';
			return $res;
		}

		if ($couponLog['share_time']) {
			$res['msg'] = '对不起,本优惠券已经在' . date('Y-m-d H:i:s', $couponLog['share_time']) . '分享过.只能分享一次';
			return $res;
		}

		// 设置子券状态
		$data = array(
			'mid'         => $mid,
			'send_type'   => 4, // 分享获得 sendType为4
			'coupon_id'   => $couponLog['coupon_id'],
			'pid'         => $couponLog['id'],
			'share'       => 2,
			'share_time'  => 0,
			'order_id'    => 0,
			'use_time'    => 0,
			'create_time' => RUN_TIME,
			'expire_time' => $couponLog['end_time'], // 目前过期时间就是优惠券类别的过期时间
			'status'      => 1,
		);

		// 插入生成子券
		$logId = $this->db->insert('coupon_log', $data);

		// 设置母券分享时间 让母券不能第二次分享
		$data = 'share_time=' . RUN_TIME;
		if ($this->db->update('coupon_log', $data, array('id' => $couponLogId, 'share_time' => 0))) {
			$res['error'] = 0;
			$res['msg']   = '分享成功';
		} else {
			$this->db->delete('coupon_log', 'id=' . $logId);
			$res['msg'] = '分享失败';
		}

		return $res;
	}

	/**
	 * 优惠券被使用
	 * @param  integer $couponLogId 优惠券id
	 * @param  integer   $orderId   订单id
	 */
	public function used($couponLogId, $orderId) {
		$couponLog = $this->getCouponLog($couponLogId);

		// 修改优惠券状态
		$this->db->update('coupon_log', 'use_time=' . RUN_TIME . ',status=2,order_id=' . $orderId, 'id=' . $couponLogId);

		// 如果是分享子券 修改母券状态 使母券可用
		if ($couponLog['pid']) {
			$this->db->update('coupon_log', 'status=1', 'id= ' . $couponLog['pid']);
		}

		// 修改优惠券统计状态
		return $this->db->update('coupon', 'used=used+1', 'id=' . $couponLog['coupon_id']);
	}

	/**
	 * 退货申请通过以后 让优惠券重新可用
	 * 注意分享子券退货的时候 并不影响母券的使用
	 * @param  integer $couponLogId 优惠券id
	 */
	public function reUsable($couponLogId) {
		$couponLog = $this->getCouponLog($couponLog);
		// 修改优惠券状态
		$this->db->update('coupon_log', 'use_time=0,status=1,', 'where id=' . $couponLogId);

		// 修改优惠券统计状态
		return $this->db->update('coupon', 'used=used-1', 'where id=' . $couponLog['coupon_id']);
	}

	/**
	 * 根据逗号分隔符获取品类限制范围内的金额合计
	 * @param  string $target   逗号分隔符限制品类pid
	 * @param  array  $goodList 数据结构类似购物车表的 商品列表字段 注意goods_id 字段跟goods_order_item表不同 注意要获取品类id 和购物金额合计 subtotal
	 * @return float            品类范围内的金额合计
	 */
	public function getCatTotal($target, $goodList) {
		$catTotal = 0;
		$target   = explode(',', $target);
		foreach ($goodList as $k => $v) {
			if (in_array($v['goods_cid'], $target)) {
				$catTotal += $v['subtotal'];
			}
		}
		return $catTotal;
	}

	/**
	 * 检查优惠券是否可用
	 * @param  integer $couponLogId 优惠券记录id
	 * @param  array   $goodList    数据结构类似购物车表的 商品列表字段 注意goods_id 字段跟goods_order_item表不同 注意要获取品类goods_cid 和购物金额合计 subtotal
	 * @return json
	 */
	public function checkUsable(array $couponLog, array $goodList = array()) {
		// 重置错误信息
		$this->error = '';
		if (empty($couponLog['status'])) {
			$this->error = '对不起,优惠券未找到或者被禁用,有疑问请联系客服';
			return false;
		}

		switch ($couponLog['status']) {
			case 2:
				$this->error = '对不起,本券已经被使用';
				return false;
				break;
			case 3:
				$this->error = '对不起,本券分享的优惠券被使用后才能使用';
				return false;
				break;
		}

		if ($couponLog['use_time']) {
			$this->error = '对不起,本券已在' . date('Y-m-d H:i:s', $couponLog['use_time']) . '被使用';
			return false;
		}

		if ($couponLog['expire_time'] && $couponLog['expire_time'] < RUN_TIME) {
			$this->error = '对不起,本券已在' . date('Y-m-d H:i:s', $couponLog['expire_time']) . '过期,无法使用';
			return false;
		}

		// 需求金额检查
		if ($couponLog['need_amount']) {
			$totalAmount = 0;
			foreach ($goodList as $k => $v) {
				$totalAmount += $v['subtotal'];
			}
			if ($couponLog['need_amount'] > $totalAmount) {
				$this->error = '对不起,必须满足购物券需求金额才能使用';
				return false;
			}
		}

		#品类限制
		if(!empty($couponLog['target'])){
			$target   = explode(',', $couponLog['target']);
			$target_is_ok = true;

            foreach ($goodList as $k => $v) {
                if (!in_array($v['goods_cid'], $target)) {
                    $target_is_ok = false;
                    break;
                }
            }

			if(!$target_is_ok){
				$this->error = '对不起,该商品不能使用购物券';
				return false;
			}
		}

		// 品类检查
		if ($couponLog['target']) {
			$catTotal = $this->getCatTotal($couponLog['target'], $goodList);
			if ($catTotal < $couponLog['need_amount']  || !$catTotal) {
				$this->error = '对不起,本单没有满足优惠券的品类金额限制';
				return false;
			}
		}

		return true;
	}

	/**
	 * 获取可用优惠券列表
	 * @param  array   $goodList   数据机构类似购物车表的 商品列表字段 注意goods_id 字段跟goods_order_item表不同
	 * @return array               优惠券信息列表
	 */
	public function getUserUsableCouponList($goodList) {
		// 获取购买商品的总金额
		$totalAmount = 0;
		foreach ($goodList as $k => $v) {
			$totalAmount += $v['subtotal'];
		}

		// 获取除了品类和金额限制外的可用优惠券列表
		$couponList = $this->getUserCouponList();

		// 过滤品类和金额限制
		foreach ($couponList as $k => $v) {

			if ($v['need_amount'] > $totalAmount || 1 != $v['status']) {
				// 需求金额超过总金额  或者 状态不为1
				unset($couponList[$k]);
				continue;
			}

			if (!empty($v['target'])) {
				$catTotal = $this->getCatTotal($v['target'], $goodList);
				if ($v['need_amount'] > $catTotal  || !$catTotal) {
					unset($couponList[$k]);
					continue;
				}
			}

			$couponList[$k]['target_name'] = $this->getTargetName($v['target']);

		}

		return $couponList;
	}

	/**
	 * 获取没有使用过 并且没有过期的优惠券
	 * 注意包含所有status
	 * 注意 内部status 为1的才可以使用 还有无法使用的未分享券
	 * @return array 除了品类和金额限制外的可用优惠券列表
	 */
	public function getUserCouponList($where) {
		// $sql  = 'SELECT * FROM ###_coupon_log a,###_coupon b WHERE a.coupon_id = b.id AND a.use_time=0 AND a.status=1 and a.mid=' . MID . ' AND a.expire_time>= ' . RUN_TIME;
		$sql  = 'SELECT a.id,a.send_type,a.coupon_id,a.pid,a.order_id,a.share,a.share_time,a.use_time,a.create_time,a.expire_time,a.status,b.title,b.type,b.target,b.amount,b.need_amount,b.stock,b.sended,b.used,b.uniqued,b.exchange,b.score,b.paid,b.paid_goodscat,b.paid_amount FROM `###_coupon_log` a,`###_coupon` b WHERE a.coupon_id = b.id AND a.use_time=0  and a.mid=' . MID . ' AND (' . RUN_TIME . '<a.expire_time OR a.expire_time = 0) '.$where;
		$list = $this->db->select($sql);
		return $list;
	}

	/**
	 * 获取分成可用和不可用两组优惠券列表
	 * @return array
	 */
	public function getCouponList($goodList, $disable = false,$sid=0) {
		// 获取购买商品的总金额
		$totalAmount = 0;
		foreach ($goodList as $k => $v) {
			$totalAmount += $v['subtotal'];
		}

		// 获取除了品类和金额限制外的可用优惠券列表
                $where = " and a.sid=".$sid;
		$couponList = $this->getUserCouponList($where);

		$disableList = array();

		// 过滤品类和金额限制
		foreach ($couponList as $k => $v) {

			if ($v['need_amount'] > $totalAmount || 1 != $v['status']) {
				// 需求金额超过总金额  或者 状态不为1

				$disableList[] = $couponList[$k];
				unset($couponList[$k]);
				continue;
			}

			if (!empty($v['target'])) {
				// 品类金额
				$catTotal = $this->getCatTotal($v['target'], $goodList);
				if ($v['need_amount'] > $catTotal || !$catTotal ) {
					$couponList[$k]['unreach_need_amount'] = true;

					$disableList[] = $couponList[$k];
					unset($couponList[$k]);
					continue;
				}
			}

			$couponList[$k]['target_name'] = $this->getTargetName($v['target']);

		}

		if ($disable) {
			return array(
				'useable' => $couponList,
				'disable' => $disableList,
			);
		} else {
			return $couponList;
		}

	}

	/**
	 * 根据 target 获取品类限制字段名称 span 集合字符串
	 * @param  array $list 商品列表 注意格式要符合购物车列表字段
	 * @return array 获取品类限制字段名称以后的商品列表
	 */
	public function getListTargetName($list) {
		foreach ($list as $k => $v) {
			$list[$k]['target_name'] = $this->getTargetName($v['target_name']);
		}

		return $list;
	}

	/**
	 * 逗号分隔符判断 品类是否有效 注意这里面的分隔样式目前是span 分隔
	 * @param  string $target 逗号分隔符品类id
	 * @return string         格式化以后的品类名称
	 */
	public function getTargetName($target) {

		try {
			if (!$target) {
				return false;
			}

			$catNamelist = S('CAT_NAME_LIST');
			$catNamelist = $catNamelist ? $catNamelist : array();

			$tmpKey = md5($target);

			if (empty($catNamelist[$tmpKey])) {
				$sql      = 'SELECT catname FROM ###_goods_cat where id in (' . $target . ')';
				$tmp      = $this->db->select($sql);
				$catNames = array_column($tmp, 'catname');

				$catNamelist[$tmpKey] = $catNames;

				// 缓存暂时设置60秒 后期整理缓存方案以后相对延长
				S('CAT_NAME_LIST', $catNamelist, 60);
			} else {
				$catNames = $catNamelist[$tmpKey];
			}

			return '<span>' . join('</span><span>', $catNames) . '</span>';
		} catch (Exception $e) {
			return '';
		}

	}

    /**
     * 订单满多少送优惠券
     */
    function sendOrder($order=array()){

        if(!$order)return false;

        #订单总额
        $amount = $order['order_amount'];

        #满送优惠券列表
        $coupon = $this->getAvailableCouponList(" and paid=1 ");

        #订单商品cid
        $cid = $this->db->getstr("select cid from ###_goods where id={$order['extension_id']}","cid");

        #满足条件的优惠
        $send_array = array();

        foreach($coupon as $k=>$v){
            #满多少送
            if($amount<$v['paid_amount'])continue;
            #限品类
            if($v['paid_goodscat']){
                $goodscat = explode(',',$v['paid_goodscat']);
                if(!in_array($cid,$goodscat))continue;
            }
            #每人只能领一张
            if($v['uniqued']==1){
                $is_res = $this->db->get("select 1 from ###_coupon_log where mid={$order['mid']} and id={$v['id']}");
                if($is_res)continue;
            }
            $send_array[$v['amount']] = $v['id'];
        }
        #发送优惠券
        if($send_array){
            krsort($send_array);
            $send_id = reset($send_array);
            $this->sendOne($order['mid'],$send_id);
        }else{
            return false;
        }


    }

}