<?php
class score_model extends Lowxp_Model {
	public $power = 0;//总开关
	private $ruleTable   = '###_score_rule';
	private $configTable = '###_score_config';

	// 积分类型
	public $scoreTypes = array(
		0 => '其他类型', // 杂项放在这里
		1 => '签到积分',
		2 => '注册积分',
		//3 => '分享积分',
		//4 => '充值积分',
		5 => '团购积分',
		6 => '积分兑换',
		//7 => '后台调整',
	);

    function __construct() {
        if (file_exists(AppDir . 'config/version_score.php')) {
            include AppDir . 'config/version_score.php';
        }
        if (defined("Version_score")) {
            $this->power = 1;
        }
    }

	// 积分类型 注意这里的顺序跟积分类别分别对应 调用的时候 用 $types[$post['rule_id']]; rule_id 不为0所以首位留空,如需要新增在后面加上即可
	public $socreTypes = array('', 'sign', 'subscribe', 'invite', 'recharge', 'shopping','exchange');

	public function getTotal($mid) {
		$res = $this->db->get("SELECT * FROM ###_score_total WHERE mid = " . $mid . " LIMIT 1");
		return $res;
	}

	/**
	 * 获取单行信息
	 * @param  integer $id 规则id
	 * @return array   解析配置以后的规则数组
	 */
	public function getRow($id) {
		$rule   = $this->db->get("SELECT * FROM ###_score_rule WHERE id=" . $id);
		$config = $this->db->get("SELECT * FROM ###_score_config WHERE rule_id=" . $id . " limit 1");

		if ($config) {
			$rule['status'] = $config['status'];

			empty($config['config']) || $rule['config'] = $config['config'];
		}

		$rule['config'] = empty($rule['config']) ? array() : json_decode($rule['config'], true);

		if (!empty($rule['config']['extend'])) {
			// 规则内部的附加规则统一保存成extend  json类型

			$rule['config']['extend'] = json_decode($rule['config']['extend'], true);
		}

		return $rule;
	}

	/**
	 * 根据id 获取积分规则配置信息
	 * @param  integer $id 积分规则id
	 * @return array       积分规则配置信息
	 */
	public function getConfig($id) {
		$config = $this->getRow($id);
		return empty($config['config']) ? array() : $config['config'];
	}

	/**
	 * 签名送积分
	 * 如果有扩展规则,则需有扩展规则数组 extend=array( array('amount'=>连续天数1奖励,'day'=> 连续天数1),array('amount'=>连续天数2奖励,'day'=> 连续天数2));
	 *
	 * @param  array $post 示例 array('status'=>1, amount: 10, extend=>array(array('day'=>2, 'amount'=>2 ), array('day'=>4, 'amount'=>4 ));
	 * @return array 失败 有错误信息 成功有 res['data'] 数据 并且res['error']=0
	 */
	public function parseSign(array $post) {

		$data = array(
			'amount' => $post['amount'],
			'extend' => array(),
		);

		$res = array();

		if (!empty($post['extend'])) {
			// 有扩展规则
			$extend = array();
			foreach ($post['extend'] as $v) {
				if (empty($v['amount']) || empty($v['target']) || $v['target'] < 0) {
					continue;
				}
				$extend[round($v['target'])] = round($v['amount']);
			}

			ksort($extend);
			$data['extend'] = json_encode($extend);
		} elseif ($post['amount'] == 0 && $post['status']) {
			// 无扩展规则 并且积分为0

			$res['error'] = 1;
			$res['msg']   = '对不起, 积分总开关开启且无扩展规则时,每日积分不可以为0';
			return $res;
		}

		$res['data'] = $data;
		return $res;
	}

	/**
	 * 充值送积分
	 * @param  array  $post 示例 array('status' => 1, 'amount'=>10);
	 * @return array 失败 有错误信息 成功有 res['data'] 数据 并且res['error']=0
	 */
	public function parseRecharge(array $post) {

		$data = array(
			'percent' => $post['percent'],
			'extend'  => array(),
		);

		$res = array();

		if (!empty($post['extend'])) {
			// 有扩展规则
			$extend = array();
			foreach ($post['extend'] as $v) {
				if (empty($v['amount']) || empty($v['target']) || $v['target'] < 0) {
					continue;
				}
				$extend[round($v['target'])] = round($v['amount']);
			}

			ksort($extend);
			$data['extend'] = json_encode($extend);
		}

		$res['data'] = $data;
		return $res;
	}

	/**
	 * 关注送积分
	 * @param  array  $post 示例 array('status' => 1, 'amount'=>10);
	 * @return array 失败 有错误信息 成功有 res['data'] 数据 并且res['error']=0
	 */
	public function parseSubscribe(array $post) {

		if ($post['status'] && !filter_var($post['amount'], FILTER_VALIDATE_INT)) {
			return array(
				'error' => 1,
				'msg'   => '对不起,总开关开启的时候,积分需要是非0整数',
			);
		}

		$data = array(
			'amount' => $post['amount'],
		);

		$res = array();

		$res['data'] = $data;
		return $res;
	}

	/**
	 * 分享送积分 当下家通过本用户的链接 或者分销名片 成为会员的时候 生效
	 * @param  array $post 示例 array('status' => 1, 'amount'=>10);
	 * @return array 失败 有错误信息 成功有 res['data'] 数据 并且res['error']=0
	 */
	public function parseInvite(array $post) {

		$data = array(
			'extend' => json_encode($post['extend']),
		);

		$res = array();

		$res['data'] = $data;
		return $res;
	}

	/**
	 * 购物送积分百分比
	 * @param  array  $post 示例 array('status' => 1, 'percent'=>100);
	 * @return array 失败 有错误信息 成功有 res['data'] 数据 并且res['error']=0
	 */
	public function parseShopping(array $post) {

		if (!is_numeric($post['percent'])) {
			return array(
				'error' => 1,
				'msg'   => '对不起,百分比只能是数字',
			);
		}

		if ($post['status'] && !$post['percent']) {
			return array(
				'error' => 1,
				'msg'   => '对不起,总开关开启的时候,百分比不能是0',
			);
		}

		$data = array(
			'percent' => $post['percent'],
		);

		$res = array();

		$res['data'] = $data;
		return $res;
	}
	
	/**
	 * 积分兑换
	 * @param  array  $post 示例 array('status' => 1, 'percent'=>100);
	 * @return array 失败 有错误信息 成功有 res['data'] 数据 并且res['error']=0
	 */
	public function parseExchange(array $post) {
	
		if (!is_numeric($post['percent'])) {
			return array(
					'error' => 1,
					'msg'   => '对不起,百分比只能是数字',
			);
		}
	
		if ($post['status'] && !$post['percent']) {
			return array(
					'error' => 1,
					'msg'   => '对不起,总开关开启的时候,百分比不能是0',
			);
		}
	
		$data = array(
				'percent' => $post['percent'],
		);
	
		$res = array();
	
		$res['data'] = $data;
		return $res;
	}

	/**
	 * 按照 rule_id 来分发配置解析函数
	 * 类型必须在 scoreTypes 内注册
	 * 注意分发解析函数都必须 用驼峰命名 示例: parseSign
	 * @param  array 前台post 数组
	 * @return array 失败 有错误信息 成功有 res['data'] 数据 并且res['error']=0
	 */
	public function parseConfig(array $post) {
		$type = ucwords($this->socreTypes[$post['rule_id']]);
		
		if (empty($type)) {
			return array(
				'error' => 1,
				'msg'   => '对不起,您没有权限设置本功能',
			);
		}

		return call_user_func(array($this, 'parse' . $type), $post);
	}

	/**
	 * 后台积分规则配置编辑
	 * @param  array  $post 前台提交的数组
	 * @return array        $res = array('error' => '0','msg'   => '编辑成功',);
	 */
	public function save(array $post) {

		$res = array(
			'error' => '0',
			'msg'   => '编辑成功',
		);

		if ((isset($post['amount']) && floor($post['amount']) != $post['amount'])) {
			$res['error'] = 1;
			$res['msg']   = '对不起, 积分值只能是整数';
			return $res;
		}
		
		$parseConfig = $this->parseConfig($post);
		
		if (!empty($parseConfig['error'])) {
			return $parseConfig;
		}

		$data = array(
			'config' => json_encode($parseConfig['data']),
			'status' => $post['status'],
		);
		$config = $this->db->get("SELECT id FROM ###_score_config WHERE rule_id = " . $post['rule_id']);
		if (empty($config['id'])) {
			$data['rule_id'] = $post['rule_id'];

			$this->db->insert('score_config', $data);
		} else {
			$this->db->update('score_config', $data, 'id=' . $config['id']);
		}

		return $res;
	}

	/**
	 * 写入积分日志 注意初始化积分的时候 可能会发生高并发冲突
	 * 注意减扣积分也是在这里 比如退货的时候 或者活动消耗的时候
	 * @param  array   $param array('mid'=> 0, $type=>0, $amount=>0, $remark => '', 'c_time'=>0)
	 * @return array          返回生成的积分记录详情
	 */
	public function scoreLog(array $param) {
		if (empty($param['mid']) || empty($param['amount'])) {
			return false;
		}

		// 积分分值四舍五入成整数
		$param['amount'] = round($param['amount']);

		// 0表示其他类型的积分类型 无定义的情况下 设置为0
		$param['type'] = isset($param['type']) ? $param['type'] : 0;

		$logs = array(
			'mid'    => $param['mid'],
			'type'   => $param['type'],
			'amount' => $param['amount'],
			'remark' => $param['remark'],
			'c_time' => empty($param['c_time']) ? RUN_TIME : $param['c_time'],
		);

		// 注意score_total表无自增id
		$logs['id'] = $this->db->insert('score_log', $logs);

		// 积分统计类型等于 socore 连接 type
		$scoreName = 'score_' . $param['type'];

		// todo 需设置锁定 或事务 解决高并发

		$total = $this->db->get("SELECT * FROM ###_score_total WHERE mid = " . $param['mid'] . " LIMIT 1");

		$id = 0;
		if (empty($total['mid'])) {
			//  如果没有找到则尝试插入
			$insert = array();

			// type 为1 表示签到
			if ($param['type'] == 1) {
				// 第一次插入签到时间跟连续签到次数
				$insert[] = 'continue_sign=1,last_sign=' . RUN_TIME;
			}

			$insert[] = 'mid=' . $param['mid'] . ',u_time=' . RUN_TIME . ',' . $scoreName . '=' . $scoreName . '+' . $param['amount'] . ',total_left=' . $param['amount'];

			if ($param['amount'] > 0) {
				// total_amount 历史获得的积分总数量 当发生退货或者抽奖对话等积分减少的情况时总数不减少,并且允许出现负积分情况
				$insert[] = 'total_amount=' . $param['amount'];
			} else {
				$insert[] = 'total_cost=' . $param['amount'];
			}

			$sql = 'INSERT INTO ###_score_total SET ' . join(',', $insert);

			$id = $this->db->query($sql);
		}

		if ($id) {
			// 插入成功
			return $logs;
		} else {
			// 如果插入失败 则尝试更新;
			$update = array();

			// type 1 标识签到

			if ($param['type'] == 1) {
				// 判断昨天是否签到
				$update[] = 'last_sign=' . RUN_TIME;

				if ($total['last_sign'] + 86400 >= strtotime(date('Y-m-d', RUN_TIME))) {
					// 判断昨天是否签到
					$update[] = 'continue_sign=continue_sign+1';
				}else{
                    $update[] = 'continue_sign=1';
                }
			}

			if ($param['amount'] > 0) {
				$update[] = 'total_amount=total_amount+' . $param['amount'];
			} else {
				$update[] = 'total_cost=total_cost-' . $param['amount'];
			}

			$update[] = 'u_time=' . RUN_TIME . ',' . $scoreName . '=' . $scoreName . '+' . $param['amount'] . ',total_left=total_left+' . $param['amount'];

			if ($this->db->update('score_total', join(',', $update), 'mid=' . $param['mid'] . ' AND u_time=' . $total['u_time'])) {

				// if ($this->db->update('score_total', join(',', $update), 'mid=' . $param['mid'])) {
				// 如果更新成功
				return $logs;
			} else {
				// 统计失败 则删除插入的积分日志
				$this->db->delete('score_log', 'id=' . $logs['id']);
				return false;
			}
		}
	}

	/**
	 * 写入积分日志
	 * 注意减扣积分也是在这里 比如退货的时候 或者活动消耗的时候
	 * @param  array   $param array('mid'=> 0, $type=>0, $amount=>0, $remark => '', 'c_time'=>0)
	 * @return array          返回生成的积分记录详情
	 */
	public function scoreLog2(array $param) {
		if (empty($param['mid']) || empty($param['amount'])) {
			return false;
		}

		// 积分分值四舍五入成整数
		$param['amount'] = round($param['amount']);

		// 0表示其他类型的积分类型 无定义的情况下 设置为0
		$param['type'] = isset($param['type']) ? $param['type'] : 0;

		$logs = array(
			'mid'    => $param['mid'],
			'type'   => $param['type'],
			'amount' => $param['amount'],
			'remark' => $param['remark'],
			'c_time' => empty($param['c_time']) ? RUN_TIME : $param['c_time'],
		);

		$logs['id'] = $this->db->insert('score_log', $logs);

		// 积分统计类型等于 socore 连接 type
		$scoreName = 'score_' . $param['type'];

		// $data 用来做 score_total 表统计操作
		$data = array(
			$scoreName => $param['amount'],
			'u_time'   => RUN_TIME,
		);

		$total = $this->db->get("SELECT * FROM ###_score_total WHERE mid = " . $param['mid'] . " ORDER BY id LIMIT 1");

		// type 为1 表示签到
		if ($param['type'] == 1) {
			// 第一次插入签到时间跟连续签到次数
			$data['continue_sign'] = 1;
			$data['last_sign']     = RUN_TIME;
		}

		if (empty($total['mid'])) {
			$data['mid'] = $param['mid'];

			if ($param['amount'] > 0) {
				// total_amount 历史获得的积分总数量 当发生退货或者抽奖对话等积分减少的情况时总数不减少,并且允许出现负积分情况
				$data['total_amount'] = $param['amount'];
			} else {
				$data['total_cost'] = -$param['amount'];
			}

			$data['total_left'] = $param['amount'];

			$this->db->insert('score_total', $data);
		} else {

			// type 1 标识签到
			if ($param['type'] == 1 && ($total['last_sign'] + 86400 >= strtotime(date('Y-m-d', RUN_TIME)))) {
				// 判断昨天是否签到
				$data['continue_sign'] = $total['continue_sign'] + 1;
			}

			if ($param['amount'] > 0) {
				$data['total_amount'] = $total['total_amount'] + $param['amount'];
			} else {
				$data['total_cost'] = $total['total_cost'] - $param['amount'];
			}

			$data['total_left'] = $total['total_left'] + $param['amount'];

			$data[$scoreName] += empty($total[$scoreName]) ? 0 : $total[$scoreName];

			$this->db->update('score_total', $data, 'mid=' . $param['mid']);
		}

		return $logs;

	}

	/**
	 * 根据规则id 执行积分增减
	 * @param  integer $ruleId 规则id
	 * @param  integer $mid    用户id
	 * @param  integer $target 满多少送多少的目标,通常是金额 . 比如购物总消费168元 这里传入消费金额168 充值200元 这里传入200
	 * @return array           返回生成的积分记录详情
	 */
	public function actionByRuleId($ruleId, $mid, $target = 0) {
		$rule   = $this->getRow($ruleId);
		$config = $rule['config'];

		$amount = 0;

		if (!empty($config['amount'])) {
			$amount += $config['amount'];
		}

		if (!empty($config['percent'])) {
			$amount += $config['percent'] * $target / 100;
		}

		if (!empty($config['extend'])) {

			krsort($config['extend']);
			foreach ($config['extend'] as $k => $v) {

				if ($target >= $k) {
					$amount += $v;
					break;
				}

			}
		}

		// 积分四舍五入取整 如果等于0 则不做任何积分操作
		$amount = round($amount);

		if (!$amount) {
			return false;
		}

		$data = array(
			'mid'    => $mid,
			'type'   => $ruleId,
			'amount' => $amount,
			'remark' => $target . ' ' . $rule['title'],
			'c_time' => RUN_TIME,
		);

		return $this->scoreLog($data);
	}

	/**
	 * 邀请送积分
	 * @param  integer $mid 用户id
	 * @return boolean
	 */
	public function inviteAction($mid, $nickname, $templateParam) {
		$config = $this->getConfig(3);

		if (empty($config['extend'])) {
			return false;
		}

		$this->mapInviter($mid, $nickname, $templateParam, $config['extend']);
	}

	/**
	 * 向上遍历推荐人 并触发积分 和模版消息
	 *
	 * 模版9  有人被您推荐成会员 {插入昵称},{插入会员个数},{插入店铺}
	 * 模版10 有人被您的下级推荐成会员 {插入昵称},{分销层级},{插入上级昵称},{插入会员个数},{插入店铺}
	 *
	 * @param  integer $mid      一级推荐人mid
	 * @param  string  $nickname 被推荐人昵称 必须填写
	 * @param  array   $extend   推荐级别积分规则 扩展数组 示例 array(10,10,10,10,10,10)
	 * @param  integer $index    推荐级别
	 * @param  array   $template 模版消息扩展参数
	 */
	public function mapInviter($mid, $nickname = '', $templateParam, $extend, $index = 0) {
		if (!$mid) {
			return false;
		}

		// 传入的 会员个数 及店铺名称 转换成临时数组
		$tmpParam = array(
			$templateParam[0],
			$templateParam[1],
			$templateParam[2],
		);

		$member = $this->db->get("SELECT mid,ivt_id,nickname,openid,email,mobile FROM ###_member WHERE mid = " . $mid . " LIMIT 1");

		if (!empty($member)) {

			$receiver = array(
				'msg'    => $mid,
				'wechat' => $member['openid'],
				'mail'   => $member['email'],
				'sms'    => $member['mobile'],
			);

			$templateId = 9;

			if ($index != 0) {
				$templateId = 10;
				// 如果不是第一级 就插入推荐人昵称
				array_unshift($tmpParam, $index);
			}

			array_unshift($tmpParam, $nickname);

			send_template_msg($templateId, $receiver, $tmpParam);
			// 模版消息 end

			if (empty($extend[$index])) {
				return false;
			}

			$amount = round($extend[$index]);

			if (!$amount) {
				return false;
			}

			$data = array(
				'mid'    => $member['ivt_id'],
				'type'   => 3, // 注意这里的 ruleId 固定为3表示 分享赚积分
				'amount' => $amount,
				'remark' => $nickname . ' 被邀请注册.作为' . $index . '级上级获得积分',
				'c_time' => RUN_TIME,
			);
			$this->scoreLog($data);

			$index++;
			$this->mapInviter($member['ivt_id'], $nickname, $templateParam, $extend, $index);
		}
	}

}

?>