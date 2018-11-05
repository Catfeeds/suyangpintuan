<?php
class sign_model extends Lowxp_Model {

	private $ruleId = 1;

	/**
	 * 执行签到
	 * @param  integer mid      用户id
	 * @param  integer continue 连续天数
	 * @return array
	 */
	public function action($mid, $continue = 0) {
		$res = array('error' => 1, 'msg' => "对不起签到失败,请稍后重试");

		$tmpTime = strtotime(date('Y-m-d', RUN_TIME));

		$lastLog = $this->db->get("SELECT * FROM ###_score_log WHERE type=1 AND mid=" . $mid . " AND c_time > " . $tmpTime . " ORDER BY c_time DESC LIMIT 1");

		if ($lastLog) {
			return array(
				'error' => 1,
				'msg'   => '您今天已经签过到. 明天请保持哦',
			);
		}

		$this->load->model('score');
		$config = $this->score->getConfig($this->ruleId);

		// 连续签到数增加
		++$continue;

		$amount = $config['amount'];

		if (!empty($config['extend'])) {
			krsort($config['extend']);

			foreach ($config['extend'] as $k => $v) {
				// $continue 等于 $k 则奖励一次 示例 规则为如每次签到奖励2分,连续3天奖励5分,连续签到5天奖励10分. 则第1天2分 第2天2分 第3天7分 第4天2分 第5天12分 第6天2分 类推
				// $continue 大于等于 $k 则叠加奖励.断签则从头开始 示例 规则为如每次签到奖励2分,连续3天奖励5分,连续签到5天奖励10分. 则第1天2分 第2天2分 第3天7分 第4天7分 第5天12分 第6天12分 类推
				// 默认使用叠加奖励
				if ($continue >= $k) {
					$amount += $v;
					break;
				}
			}

		}

		$amount = round($amount);
		if (!$amount) {
			IS_DEV && Log::write('签到积分规则有误,签到积分计算结果为0');

			return array(
				'error' => 1,
				'msg'   => '对不起,签到失败',
			);
		}

		$data = array(
			'mid'    => $mid,
			'type'   => 1,
			'amount' => $amount,
			'remark' => '第' . $continue . '天连续签到'.L('unit_score'),
			'c_time' => RUN_TIME,
		);

		if ($this->score->scoreLog($data)) {
			$res['error'] = 0;
			$res['msg']   = '签到成功';
			$res['data']  = array(
				'score'    => $amount,
				'date'     => date('Y-m-d H:i:s', RUN_TIME),
				'continue' => $continue,
			);
		}

		return $res;
	}

	/**
	 * 传入日志列表返回连续签到天数
	 * @param  array   $logs 数据库日志列表
	 * @return integer       连续签到天数
	 */
	public function checkContinue(array $logs) {
		$continue = 0;

		// 如果没有记录 或者记录只有一天
		if (empty($logs)) {
			return $continue;
		}

		// 判断今天是否签到
		$today = $logs[0]['c_time'] >= strtotime(date('Y-m-d', RUN_TIME)) ? true : false;

		if ($today) {
			// 今天已经签到则 去除第一条 从昨天开始判断
			array_shift($logs);
			++$continue;
		}

		// 第一条记录表示昨天. 判断昨天是否签到
		if ($logs[0]['c_time'] + 86400 >= strtotime(date('Y-m-d', RUN_TIME))) {
			++$continue;
		}

		// 如果连续2天不连续 则返回 无需继续判断
		if (!$continue) {
			return $continue;
		}

		// 超过2天连续以后
		foreach ($logs as $k => $v) {
			// 是最后一条了没必要验证
			if (empty($logs[$k + 1]['c_time'])) {
				break;
			}

			// 判断每天的前一天是否签到
			if ($logs[$k + 1]['c_time'] + 86400 >= strtotime(date('Y-m-d', $logs[$k]['c_time']))) {
				++$continue;
			} else {
				// 有一天不连续就不继续验证
				break;
			}

		}
		return $continue;
	}

	// 日历版本

	public $weeks = array('周一', '周二', '周三', '周四', '周五', '周六', '周日');

	public function calendarDate($mid = 353) {
		$year  = date('Y');
		$month = date('m');

		// $year  = '2016';
		// $month = 5;

		// 设定月第一天
		$currentMonthFirstDay = mktime(0, 0, 0, $month, 1, $year);
		// 上月没显示完的天数 从周一开始
		$lastMonthleftDays = date('N', $currentMonthFirstDay);

		// 日历第一位时间戳
		$firstDate = $currentMonthFirstDay - 86400 * $lastMonthleftDays;

		// 日历第一位前一天的日期
		$firstDay = date('d', $firstDate);

		// 本月最后一天是日历的第几位
		$currentMonthDays = $lastMonthleftDays + date('t', $currentMonthFirstDay);

		$w = 0;
		$i = 0;
		$j = 1;
		$k = 1;
		$d = 1;

		$tmp = array();

		$logs         = $this->getSignLogs($mid, $currentMonthDays);
		$logs['date'] = empty($logs['date']) ? array() : $logs['date'];

		while ($d < $lastMonthleftDays) {
			$tmp[$w][$i] = array('day' => $firstDay + $d, 'type' => 1);

			if (in_array(date('Y-m-d', $firstDate + 86400 * $d), $logs['date'])) {
				$tmp[$w][$i]['signed'] = 1;
			}

			$i++;
			if ($d % 7 == 0) {
				$i = 0;
				$w++;
			}
			$d++;
		}

		while ($d < $currentMonthDays) {
			$tmp[$w][$i] = array('day' => $j, 'type' => 2);

			if (in_array(date('Y-m-d', $firstDate + 86400 * $d), $logs['date'])) {
				$tmp[$w][$i]['signed'] = 1;
			}

			if ($j == date('d')) {
				$tmp[$w][$i]['today'] = 1;
			}

			$j++;
			$i++;
			if ($d % 7 == 0) {
				$i = 0;
				$w++;
			}
			$d++;
		}

		$j = 1;
		while ($d < 43) {
			$tmp[$w][$i] = array('day' => $j, 'type' => 3);
			$j++;
			$i++;
			if ($d % 7 == 0) {
				$i = 0;
				$w++;
			}
			$d++;
		}

		// 混合写法 低效
		// for ($d = 1; $d < 43; $d++) {
		//  if ($d < $lastMonthleftDays) {
		//      $res[$w][$i] = array('day' => $firstDay + $d);
		//  } elseif ($d < $currentMonthDays) {
		//      $res[$w][$i] = array('day' => $j);
		//      if ($j == date('d')) {
		//          $res[$w][$i]['today'] = 1;
		//      }
		//      $j++;
		//  } else {
		//      $res[$w][$i] = array('day' => $k);
		//      $k++;
		//  }

		// if (in_array(date('Y-m-d', $firstDate + 86400 * $d), $logs)) {
		//  $res[$w][$i]['signed'] = 1;
		// }

		//  $i++;
		//  if ($d % 7 == 0) {
		//      $i = 0;
		//      $w++;
		//  }
		// }
		$res = array(
			'calendar' => $tmp,
			'continue' => empty($logs['continue']) ? 0 : $logs['continue'],
		);

		return $res;
	}

	/**
	 * 动态获取已签到日期数组
	 * @param  integer $mid   用户id
	 * @param  integer $limit 取出的数量 通常为日历第一天到今天之前的天数
	 * @return array()        array('2016-01-01','2016-01-02')  类似格式的签到数组
	 */
	public function getSignLogs($mid, $limit = null) {
		$sql = "SELECT c_time FROM ###_score_log WHERE type=1 AND mid=" . $mid . " order by c_time desc";

		filter_var($limit, FILTER_VALIDATE_INT) && $sql .= ' limit ' . $limit;

		$logs = $this->db->select($sql);

		$res = array(
			'date'     => array(),
			'continue' => 0,
		);

		if (empty($logs)) {
			return $res;
		}
		$countinue = false;

		if (strtotime(date('Y-m-d', time())) - 86400 < $logs[0]['c_time']) {
			$res['continue'] = 1;
			$countinue       = true;
		}

		foreach ($logs as $k => $v) {

			$res['date'][$k] = date('Y-m-d', $v['c_time']);

			if ($countinue && !empty($logs[$k + 1]) && $logs[$k + 1]['c_time'] > (strtotime($res['date'][$k]) - 86400) && $logs[$k + 1]['c_time'] < (strtotime($res['date'][$k]) + 86400)) {
				$res['continue']++;
			} else {
				$countinue = false;
			}
		}
		return $res;
	}

	/**
	 * 为提高效率将签到日志获取成日志 转换成json 数组  保存在 ###_score_total 表内
	 * @param  integer $mid 用户id
	 * @param  integer $limit 取出的数量 通常为日历第一天到今天之前的天数 默认有效天数最大是38天
	 */
	public function logIntoScoreTotal($mid, $limit = 42) {

		$res = $this->getSignLogs($mid, $limit);

		if ($res) {
			$sql = "SELECT id FROM ###_score_total WHERE mid=" . $mid;

			$data = array(
				'mid'    => $mid,
				'u_time' => time(),
			);

			$row = $this->db->get($sql);

			if ($row) {
				$data['sign_logs'] = json_encode($res);
				$this->db->update('score_total', $data, ' mid=' . $mid);
				return true;
			}
		}
		return false;

	}

	/**
	 * 从###_score_total 表中获取已签到日期数组
	 * @param  integer $mid   用户id
	 * @return array()        array('2016-01-01','2016-01-02')  类似格式的签到数组
	 */
	public function getSignLogsFromScoreTotal($mid) {
		$sql = "SELECT sign_logs FROM ###_score_total WHERE mid=" . $mid;
		$row = $this->db->get($sql);

		if (!empty($row['sign_logs'])) {
			$logs = json_decode($row['sign_logs'], true);
		}

		return empty($logs) || !is_array($logs) ? array() : $logs;
	}

	/**
	 * 创造测试用假数据
	 */
	public function createTestData() {
		$this->load->model('score');
		$data = array(
			'mid'    => 353,
			'type'   => 1,
			'amount' => 10,
		);
		$i = 1;
		while ($i <= 31) {
			$data['remark'] = '测试签到,第' . $i . '天';
			$data['c_time'] = time() + 86400 * ($i - 32);

			$this->score->scoreLog($data);
			$i++;
		}
	}

}

?>