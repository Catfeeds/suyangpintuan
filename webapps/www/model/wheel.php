<?php
/**
 * Class wheel_model
 */
class wheel_model extends Lowxp_Model {
	public $power = 0; #总开关
	public $baseTable = '###_wheel';
	public $extTable  = '###_wheel_prize';
	public $logTable  = '###_wheel_log';

	// 奖品类别
	public $prizeTypes = array(
		0 => '积分',
		1 => '商品',
		2 => '优惠券',

	);

	public $prizeExpress = array(
		0 => '不运输',
		1 => '需运输',
	);

	public $prizeStatus = array(
		0 => '已作废',
		1 => '已发奖',
		2 => '待发奖',
		3 => '待领奖',
	);

	public $baseStatus = array(
		0 => '禁用',
		1 => '启用',
	);

    function __construct() {
        if (file_exists(AppDir . 'config/version_wheel.php')) {
            include AppDir . 'config/version_wheel.php';
        }
        if (defined("Version_wheel")) {
            $this->power = 1;
        }
    }

	/**
	 * 获取sid 下  status 为开启的 最后编辑过的 第一条大转盘信息
	 * @return array  大转盘的配置信息
	 */
	public function getActive() {
		$sql  = 'SELECT * from ' . $this->baseTable . ' WHERE status= 1 ORDER BY update_time DESC limit 1';
		$row  = $this->db->get($sql);
		if($row){
            $data = array(
                'config' => $row,
                'prize'  => $this->getPrizes($row['id']),
            );
            return $data;
        }
        return false;

	}

	/**
	 * 根据中奖记录id 获取
	 * @param  integer $logId   中奖记录id
	 * @param  boolean $extInfo 是否获取商品消息
	 * @return array
	 */
	public function getPrizeByLogId($logId, $extInfo = true) {
		$prize = $this->db->get('SELECT * FROM ' . $this->wheel->logTable . ' WHERE id=' . $logId . ' limit 1');
		if ($prize && $extInfo) {
			$this->load->model('goods');
			$prize['good']   = empty($prize['good_id']) ? array() : $this->goods->get($prize['good_id'], array('cart' => false));
			$prize['coupon'] = empty($prize['coupon_id']) ? array() : $this->db->get('SELECT * FROM ###_coupon WHERE id=' . $prize['coupon_id']);
		}
		return $prize;
	}

	/**
	 * 根据wheel_id 获取奖品信息
	 * @param  integer $id      wheel_id
	 * @param  boolean $extInfo 是否获取扩展信息
	 * @return array
	 */
	public function getPrizes($id=0, $extInfo = false) {
		$prize = $this->db->select('SELECT * FROM ' . $this->wheel->extTable . ' WHERE wheel_id=' . $id . ' ORDER BY level');
		if ($prize && $extInfo) {
			$this->load->model('goods');
			foreach ($prize as $k => $v) {
				$prize[$k]['good']   = empty($v['good_id']) ? array() : $this->goods->get($v['good_id'], array('cart' => false));
				$prize[$k]['coupon'] = empty($v['coupon_id']) ? array() : $this->db->get('SELECT * FROM ###_coupon WHERE id=' . $v['coupon_id']);
			}
		}
		return $prize;
	}

	/**
	 * 获取中奖等级
	 * @return array
	 */
	public function getLevels() {
		$level = $this->db->select('SELECT distinct level from ' . $this->logTable . ' ORDER BY level');
		return $level ? array_column($level, 'level') : array();
	}

	/**
	 * 根据奖项 获取中奖情况
	 * @param  array $prizes  奖项明细数据
	 * @return array         返回中奖详情
	 */
	public function lottery($prizes) {
		// 生成0到100间的随机小数
		$random = random_float() * 100;
		$total  = 0;
		foreach ($prizes as $k => $v) {
			$total += $v['rate'];
			if ($random < $total) {
				$level = $v['level'];
				return $v;
				break;
			}
		}
		return false;
	}

	/**
	 * 抽奖具体事务处理
	 * @param  arrray $wheel 大转盘配置信息
	 * @return json
	 */
	public function action($wheel) {
		//扣除积分
		$this->load->model('score');

		$score = $this->score->getTotal(MID);

		$res = array(
			'error' => 1,
		);

		if ($score['total_left'] < $wheel['config']['score_cost']) {
			$res['msg'] = '对不起,您的积分不够';
			exit(json_encode($res));
		}

		if (!$this->score->scoreLog(array('mid' => MID, 'amount' => -$wheel['config']['score_cost'], 'remark' => '大转盘活动消耗 ' . $wheel['config']['score_cost'] . ' 积分'))) {
			// 积分扣除错误

			$res['msg'] = '系统繁忙,请稍后重试';
			exit(json_encode($res));
		}

		$level       = 0;
		$scoreSend   = 0;
		$logId       = 0;
		$needExpress = false;

		$prize = $this->lottery($wheel['prize']);
		if ($prize && $prize['stock'] > 0) {
			$logId = $this->sendPrize($prize, $wheel);
			if ($logId) {
				// 奖品发放成功则记录奖品等级 如果失败 则未中奖
				$level       = $prize['level'];
				$needExpress = empty($prize['need_express']) ? false : true;
				$scoreSend += isset($prize['score_send']) && $prize['score_send'] ? $prize['score_send'] : 0;
			}
		}

		if ($level) {
			// 奖品发放成功时
			$msg = '恭喜您获得' . ten2chinese($level) . '等奖<br> ' . $prize['title'];

			// 模版消息 21 活动中奖 {插入昵称},{插入商品标题},{插入活动名称},{插入店铺}
			// template_msg_action start
			$this->load->model('template_msg');
			$msgParams = array(getUsername(MID), $prize['title'], $wheel['config']['title'], C('site_name'));
			$this->template_msg->inQueue(21, MID, $msgParams);
			// template_msg_action end

		} else {
			// 如果奖品发放失败
			$msg = $wheel['config']['miss_tip'] ? $wheel['config']['miss_tip'] : '很遗憾,未中奖,请再接再厉';

			if ($wheel['config']['miss_score']) {
				// 如果设置了安慰积分 一般会成功发放
				$this->score->scoreLog(array('mid' => MID, 'amount' => $wheel['config']['miss_score'], 'remark' => '大转盘安慰积分' . $wheel['config']['miss_score']));
				$msg .= '<br>获得安慰积分' . $wheel['config']['miss_score'];
				$scoreSend += $wheel['config']['miss_score'];
			}
		}

		$res['error'] = 0;
		$res['msg']   = $msg;
		$res['data']  = array(
			'logId'       => $logId,
			'level'       => $level,
			'prizeLength' => count($wheel['prize']),
			'needExpress' => $needExpress,
			'scoreLeft'   => $score['total_left'] - $wheel['config']['score_cost'] + $scoreSend,
		);

		return $res;
	}

	public function sendPrize($prize, $wheel) {
		if ($prize['stock'] <= 0) {
			return false;
		}

		// 奖品发放
		switch ($prize['type']) {
			case '1':
				$tmpLog = $this->sendPrizeGood($prize);
				break;
			case '2':
				$tmpLog = $this->sendPrizeCoupon($prize);
				break;
			default:
				$tmpLog = $this->sendPrizeScore($prize);
				break;
		}

		if (!$tmpLog) {
			// 奖品发放失败 则失败
			return false;
		}

		$log = array(
			'mid'         => MID,
			'wheel_id'    => $wheel['config']['id'],
			'level'       => $prize['level'],
			'type'        => $prize['type'],
			'score_cost'  => $wheel['config']['score_cost'],
			'remark'      => '大转盘' . ten2chinese($prize['level']) . '等奖: ' . $prize['title'],
			'create_time' => RUN_TIME,
			'update_time' => RUN_TIME,
			'expire_time' => RUN_TIME + (15 * 86400), // 奖品过期时间. 实物奖品 15天内不填写收货地址并且不主动联系商家.视为主动放弃奖品
		);

		$log = array_merge($log, $tmpLog);

		// 生成中奖记录
		$logId = $this->db->insert($this->logTable, $log);
		// 减少奖项库存
		if ($this->db->update($this->extTable, 'stock=stock - 1', 'id=' . $prize['id'] . ' AND stock>0')) {
			return $logId;
		} else {
			$this->db->delete($this->logTable, 'id=' . $logId);
			return false;
		}

	}

	/**
	 * 发放实物奖品 就是log写入 如果库存调用失败则无log 所以不用担心多发放实物奖品
	 * @param  array $prize 中奖的奖项详情数组
	 * @return array        获奖日志内容
	 */
	public function sendPrizeGood($prize) {

		$tmpLog = array(
			'need_express' => $prize['need_express'],
			'good_id'      => $prize['good_id'],
			'status'       => 3, //  3表示待领奖
		);

		return $tmpLog;
	}

	/**
	 * 发放优惠券奖品 必须放在奖项库存判断之前 如果执行成功了才减少奖项库存 生成获奖日志
	 * @param  array $prize 中奖的奖项详情数组
	 * @return array        获奖日志内容
	 */
	public function sendPrizeCoupon($prize) {
		// 优惠券奖品
		$this->load->model('coupon');
		if (!$this->coupon->sendOne(MID, $prize['coupon_id'], 5)) {
			// 优惠券发放失败不中奖
			return false;
		}

		$tmpLog['coupon_id'] = $prize['coupon_id'];
		$tmpLog['status']    = 1;
		return $tmpLog;
	}

	/**
	 * 发放积分奖品 通常积分奖品
	 * @param  array $prize 中奖的奖项详情数组
	 * @return array        获奖日志内容
	 */
	public function sendPrizeScore($prize) {
		// 默认作为送积分处理
		$this->load->model('score');
		if (!$this->score->scoreLog(array('mid' => MID, 'amount' => $prize['score_send'], 'remark' => 'id' . $prize['wheel_id'] . '大转盘' . ten2chinese($prize['level']) . '等奖'))) {
			// 积分发放失败
			return false;
		}

		$tmpLog['score_send'] = $prize['score_send'];
		$tmpLog['status']     = 1;
		return $tmpLog;
	}

	/**
	 * 保存大转盘的设置信息
	 * @return json
	 */
	public function save(array $post) {

		$res = array('error' => 1, 'msg' => '保存失败');

		// 安全过滤 start
		if (empty($post['score_cost']) || $post['score_cost'] < 0 || !filter_var($post['score_cost'], FILTER_VALIDATE_INT)) {
			$res['msg'] = '每次抽奖所需积分须是正整数';
			return $res;
		}

		if (empty($post['title'])) {
			$res['msg'] = '对不起,请设置活动标题';
			return $res;
		}

		if (empty($post['prize'])) {
			$res['msg'] = '对不起,请设置奖项';
			return $res;
		} else {

			$rateTotal = 0;
			foreach ($post['prize'] as $k => $v) {

				$rateTotal += $v['rate'];

				$level = ($k + 1) . '等奖';

				if (empty($v['title'])) {
					$res['msg'] = '对不起,请设置 ' . $level . ' 奖项名称';
					return $res;
				}

				if (!empty($v['stock']) && ($v['stock'] < 0 || !filter_var($v['stock'], FILTER_VALIDATE_INT))) {
					$res['msg'] = $level . ' 奖品数量只能为0或正整数';
					return $res;
				}

				if (!is_numeric($v['rate']) || $v['rate'] < 0 || $v['rate'] > 100) {
					$res['msg'] = $level . ' 中奖率只能为0到100间的数字,小数点后超过8位的部分将忽略';
					return $res;
				}

				if (0 == $v['type'] && (empty($v['score_send']) || !filter_var($v['score_send'], FILTER_VALIDATE_INT))) {
					$res['msg'] = '对不起, ' . $level . ' 赠送积分需是正整数';
					return $res;
				}

			}

			if ($rateTotal > 100) {
				$res['msg'] = '对不起,总中奖率不可以超过100';
				return $res;
			}

		}

		if (!empty($post['miss_score']) && ($post['miss_score'] < 0 || !filter_var($post['miss_score'], FILTER_VALIDATE_INT))) {
			$res['msg'] = '未中奖赠送的安慰积分只能为0或正整数';
			return $res;
		}

		// die;

		// 设置默认值
		$data = array(
			'title'       => $post['title'],
			'description' => $post['description'],
			'miss_tip'    => $post['miss_tip'],
			'miss_score'  => $post['miss_score'],
			'score_cost'  => $post['score_cost'],
			'start_time'  => strtotime($post['start_time']),
			'end_time'    => strtotime($post['end_time']),
			'status'      => $post['status'],
		);

		if ($post['status']) {
			$this->forbidAll();
		}

		if (empty($post['id'])) {
			$data['create_time'] = RUN_TIME;
			// admin_log('新增大转盘活动：' . $post['title']);
			$id = $this->db->insert($this->baseTable, $data);

			$this->db->insertAll($this->extTable, $post['prize'], array('wheel_id' => $id));

			$res['error'] = 0;
			$res['msg']   = '新增成功';
		} else {
            $data['update_time'] = RUN_TIME;
			// admin_log('编辑大转盘活动' . $post['title']);
			$this->db->update($this->baseTable, $data, array('id' => $post['id']));

			$prizes = $this->getPrizes($post['id']);

			foreach ($post['prize'] as $key => $value) {
				foreach ($prizes as $k => $v) {
					if ($v['id'] == $value['id']) {
						$this->db->update($this->extTable, $value, 'id=' . $v['id']);
						unset($post['prize'][$key]);
						break;
					}
				}
			}

			$this->db->insertAll($this->extTable, $post['prize'], array('wheel_id' => $post['id']));

			$res['error'] = 0;
			$res['msg']   = '编辑成功';
		}

		return $res;
	}

	/**
	 * 禁用所有的活动
	 */
	public function forbidAll() {
		$sql = 'UPDATE ' . $this->baseTable . ' SET status=0 ';
		$this->db->query($sql);
	}
}