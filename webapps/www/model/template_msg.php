<?php

/**


 * Class template_msg_model


 */

class template_msg_model extends Lowxp_Model {

	private $ruleTable   = '###_template_msg_rule';
	private $configTable = '###_template_msg_config';

	public $typeName = array(
		'商户类',
		'会员类',
		'分销代理类',
		'订单类',
		'佣金类',
		'营销类',
		'其他类',
	);

	public $btnMenu = array(
		0 => array('url' => '#!template_msg/index?type=0', 'name' => '商户类'),
		1 => array('url' => '#!template_msg/index?type=1', 'name' => '会员类'),
		2 => array('url' => '#!template_msg/index?type=2', 'name' => '分销代理类'),
		3 => array('url' => '#!template_msg/index?type=3', 'name' => '订单类'),
		4 => array('url' => '#!template_msg/index?type=4', 'name' => '佣金类'),
		5 => array('url' => '#!template_msg/index?type=5', 'name' => '营销类'),
		6 => array('url' => '#!template_msg/index?type=6', 'name' => '其他类'),
	);

	/**
	 * 获取模版消息列表 主要用来后台列表展示
	 * @param  integer $type 模版消息类型
	 * @return array         获取模版消息列表
	 */
	public function getList($type) {
		$sql = "SELECT a.id,a.title,b.u_time AS u_time, IF ( b.content, b.content, a.content ) AS content, IF (b.wechat, b.wechat, a.wechat) AS wechat, IF (b.msg, b.msg, a.msg) AS msg, IF (b.sms, b.sms, a.sms) AS sms, IF (b.mail, b.mail, a.mail) AS mail FROM ###_template_msg_rule a LEFT JOIN ( SELECT * FROM ###_template_msg_config ) b ON a.id = b.rule_id WHERE a.type = " . $type . " AND a.STATUS = 1 ORDER BY listorder,id";
		return $this->db->select($sql);
	}

	/**
	 * 根据id 获取设置好的用户配置
	 * @param  integer $id 规则id
	 * @return array
	 */
	public function getRow($id) {
		$rule = $this->db->get("SELECT * FROM ###_template_msg_rule WHERE id=" . $id);
		if (!$rule) {
			return array();
		}

		$rule['default_content'] = $rule['content'];

		$rule['params'] = empty($rule['params']) ? array() : explode(',', $rule['params']);

		$config = $this->db->get("SELECT * FROM ###_template_msg_config WHERE rule_id=" . $id . " limit 1");
		if ($config) {
			$rule['content'] = $config['content'];

			$rule['msg'] = $config['msg'];

			$rule['wechat'] = $config['wechat'];

			$rule['mail'] = $config['mail'];

			$rule['sms'] = $config['sms'];
		}

		return $rule;
	}

	/**
	 * 处理编辑请求
	 * @param  array  $post 提交的参数
	 * @return json         编辑状态
	 */
	public function save($post) {

		$res = array(
			'error' => '0',
			'msg'   => '编辑成功',
		);

		if (empty($post['content'])) {
			$res['error'] = 1;

			$res['msg'] = '对不起, 模版不能为空';
			return $res;
		}

		$data = array(
			'content' => $post['content'],
			'wechat'  => empty($post['wechat']) ? 0 : 1,
			'sms'     => empty($post['sms']) ? 0 : 1,
			'mail'    => empty($post['mail']) ? 0 : 1,
			'msg'     => empty($post['msg']) ? 0 : 1,
			'u_time'  => time(),
		);

		$config = $this->db->get("SELECT id FROM ###_template_msg_config WHERE rule_id = " . $post['rule_id']);
		if (empty($config['id'])) {
                    
			$data['rule_id'] = $post['rule_id'];

			$this->db->insert('template_msg_config', $data);
		} else {
			$this->db->update('template_msg_config', $data, 'id=' . $config['id']);
		}

		return $res;
	}

	/**
	 * 功能同下面的方法 优化执行流程减少数据库操作
	 * 注意 务必确定 $this->sendType 在###_template_msg_config 表内存在对应字段
	 * @param integer $id     ###_template_msg_rule 表的 id
	 * @param integer $index $this->sendType 的index 决定开关类型
	 * @param integer $status 0关1开
	 */
	public function setStatus($id, $index, $status = 0) {

		if (empty($this->sendType[$index])) {
			exit(json_encode(array('error' => 1, 'msg' => '对不起, 类型不存在')));
		}

		if (is_array($id)) {
			$ids = $id;

			$whereConfig = ' rule_id in (' . join(',', $ids) . ')';
		} elseif (filter_var($id, FILTER_VALIDATE_INT)) {
			$ids = (array) $id;

			$whereConfig =  ' rule_id=' . $id;
		} else {
			exit(json_encode(array('error' => 1, 'msg' => '对不起, id不正确')));
		}

		$sql = "SELECT id,rule_id FROM ###_template_msg_config WHERE " . $whereConfig;

		$exist = $this->db->select($sql);

		$existIds = array();
		foreach ($exist as $v) {
			$existIds[] = $v['id'];
			foreach ($ids as $m => $n) {
				if ($v['rule_id'] == $n) {
					unset($ids[$m]);
				}
			}
		}

		if ($existIds) {
			$sql = "UPDATE ###_template_msg_config set " . $this->sendType[$index] . " = " . $status . " where id in ( " . join(',', $existIds) . ")";

			$this->db->query($sql);
		}

		if ($ids) {

			$data = array(
				'u_time' => time(),
			);

			$sql = "SELECT * FROM ###_template_msg_rule WHERE id in (" . join(',', $ids) . ")";

			$rules = $this->db->select($sql);
			foreach ($rules as $v) {

				// 默认规则
				$data['rule_id'] = $v['id'];

				$data['content'] = $v['content'];

				$data['msg'] = $v['msg'];

				$data['wechat'] = $v['wechat'];

				$data['mail'] = $v['mail'];

				$data['sms'] = $v['sms'];

				// 配置规则
				$data[$this->sendType[$index]] = $status;

				$this->db->insert('template_msg_config', $data);
			}
		}

	}

	/**
	 * 根据mid获取邮箱地址
	 * CH_MEMBER_SMS_MID
	 * @param  integer $mid 用户id   mid 为0 表示消息接受人是商户
	 * @return string       短信地址
	 */
	public function getSmsByMid($mid = 0) {
		if ($mid) {

			$cacheName = 'CH_MEMBER_SMS_' . $mid;

			$contact = S($cacheName);
			if (!$contact) {
				$contact = $this->db->getstr('SELECT mobile FROM ###_member WHERE mid=' . $mid . ' limit 1');

				S($cacheName, $contact, 30);
			}

		} else {
			$contact = C('contact_mobile');
		}

		return $contact;
	}

	/**
	 * 根据mid获取邮箱地址
	 * CH_MEMBER_WECHAT_MID
	 * @param  integer $mid 用户id   mid 为0 表示消息接受人是商户
	 * @return string       微信openid
	 */
	public function getWehcatByMid($mid = 0) {
		if ($mid) {

			$cacheName = 'CH_MEMBER_WECHAT_'. $mid;

			$contact = S($cacheName);
			if (!$contact) {
				$contact = $this->db->getstr('SELECT openid FROM ###_oauth WHERE type=0 and mid=' . $mid . ' limit 1');

				S($cacheName, $contact, 30);
			}

		} else {
			$contact = false;
		}

		return $contact;
	}

	/**
	 * 根据mid获取邮箱地址
	 * CH_MEMBER_MAIL_MID
	 * @param  integer $mid 用户id   mid 为0 表示消息接受人是商户
	 * @return string       邮箱地址
	 */
	public function getMailByMid($mid = 0) {
		if ($mid) {

			$cacheName = 'CH_MEMBER_MAIL_'. $mid;

			$contact = S($cacheName);
			if (!$contact) {
				$contact = $this->db->getstr('SELECT email FROM ###_member_detail WHERE mid=' . $mid . ' limit 1');

				S($cacheName, $contact, 30);
			}

		} else {
			$contact = C('contact_email');
		}

		return $contact;
	}

	/**
	 * 根据mid 获取模版消息接受方式
	 * CH_MEMBER_CONTACT_MID
	 * @param  integer $mid 用户id   mid 为0 表示消息接受人是商户
	 * @return array        用户模版消息接收方式数组
	 */
	public function getContactByMid($mid = 0) {
		if ($mid) {

			$cacheName = 'CH_MEMBER_CONTACT_' . $mid;

			$contact = S($cacheName);
			if (!$contact) {
				$contact = array(
					'msg'    => $mid,
					'sms'    => $this->getSmsByMid($mid),
					'wechat' => $this->getWehcatByMid($mid),
					'mail'   => $this->getMailByMid($mid),
				);

				S($cacheName, $contact, 30);
			}

		} else {
			$contact = array(
				'msg'  => 0,
				'sms'  => C('contact_mobile'),
				'mail' => C('contact_email'),
			);
		}

		return $contact;
	}

	/**
	 * 模版消息入队函数
	 * @param  integer $ruleId    调用的消息规则id   为0时第三个参数请传入完整的临时规则
	 * @param  string  $targetIds 接受人的mid号  多位则逗号分隔符
	 * @param  array   $params    一维数组参数. 注意顺序有严格要求使用前先查看规则.  规则id 为0时本参数须传入临时规则 格式: array('type' => 整数, 'title' => 字符, 'content' => 字符, 'msg' => 0或1, 'sms' => 0或1, 'wechat' => 0或1, 'mail'=>0或1);
	 * @return boolean
	 */
	public function inQueue($ruleId, $targetIds, $params) {
		$data = array(
			$ruleId,
			$targetIds,
			$params,
		);

		require_once AppDir . 'library/queue/Queue.php';

		$queue = new Queue(array('key' => 'QUEUE_MSG'));

		$queue->put($data);
	}

	/**
	 * 批量消息入队
	 * @param  array  $data 消息队列数组   array(array($rid, $tid, $params), array($rid, $tid, $params), ...);
	 * @return void
	 */
	public function inQueueMany(array $data) {
		require_once AppDir . 'library/queue/Queue.php';

		$queue = new Queue(array('key' => 'QUEUE_MSG'));

		call_user_func_array(array($queue, 'puts'), $data);
	}

	/**
	 * 批量执行队列
	 * @param  integer $count 每次出列的行数
	 * @return void
	 */
	public function dealQueue($count = 100) {
		require_once AppDir . 'library/queue/Queue.php';

		$queue = new Queue(array('key' => 'QUEUE_MSG'));

		$msgs = array();

		$rel = array();

		$rows = $queue->gets($count);

		if (!$rows) {
			return false;
		}

		foreach ($rows as $row) {
			if (!$row) {
				continue;
			}

			$rule = empty($row[0]) ? $row[2] : $this->dealRule($row[0], $row[2]);

			if (!$rule) {
				continue;
			}

			$targetIds = explode(',', $row[1]);
			if (1 == count($targetIds)) {
				$this->dealRow($rule, $targetIds[0]);

				$res = $this->dealRowMsg($rule, $targetIds[0]);
				if ($res) {
					$msgs[] = $res;
				}
			} else {

				// 60秒内相同规则生成同一条id 并不多见
				// $cacheName = md5('C_MSG_TEMPID_' . md5(json_encode($rule)));
				// $tmpId     = S($cacheName);
				// if (!$tmpId) {
				// 	$res   = $this->dealRowMsg($rule, -1);
				// 	$tmpId = $this->db->insert('message', $res);
				// 	S($cacheName, $tmpId, 60);
				// }

				$res = $this->dealRowMsg($rule, -1);

				$tmpId = $this->db->insert('message', $res);

				foreach ($targetIds as $v) {
					$this->dealRow($rule, $v);

					if ($res) {
						$rel[] = array('mid' => $v, 'message_id' => $tmpId, 'status' => 1);
					}
				}
			}

		}

		if ($msgs) {
			$this->db->insertAll('message', $msgs);
		}
		if ($rel) {
			$this->db->insertAll('message_status', $rel);
		}
	}

	/**
	 * 按照每条规则发送消息
	 * @param  array  $row 每行消息队列规则
	 * @return mixed       需要批量发送的站内信消息或者false
	 */
	public function dealRow(array $rule, $targetId) {
		$this->load->library('message');
		if ($rule['sms'] && C('sms_open')==1) {
			$this->message->send_sms($this->getSmsByMid($targetId), $rule['content']);
		}
		if ($rule['wechat']) {
			$this->message->send_wechat($this->getWehcatByMid($targetId), $rule['content']);
		}
		if ($rule['mail']) {
			$this->message->send_mail($this->getMailByMid($targetId), $rule['content'], $rule['title']);
		}
	}

	/**
	 * 按照每条规则发送消息 注意所有的模版消息 发送人都是 0 表示系统消息 状态都为已删除 因为系统并不需要查看自己所发送的消息
	 * @param  array  $row 每行消息队列规则
	 * @return mixed       需要批量发送的站内信消息或者false
	 */
	public function dealRowMsg(array $rule, $targetId) {
		$res = false;
		if ($rule['msg']) {
			$res = array(
				'mid'         => 0,
				'target'      => $targetId,
				'type'        => $rule['type'],
				'title'       => $rule['title'],
				'content'     => $rule['content'],
				'create_time' => RUN_TIME,
				'status'      => 0,
			);
		}

		return $res;
	}

	/**
	 * 根据参数和规则获取处理后的规则
	 * @param  integer $ruleId 规则id
	 * @param  mixed   $params 参数
	 * @return array           处理后的参数 格式类似 array('type' => 整数, 'title' => 字符, 'content' => 字符, 'msg' => 0或1, 'sms' => 0或1, 'wechat' => 0或1, 'mail'=>0或1);
	 */
	public function dealRule($ruleId, $params) {
		if (!$ruleId) {
			return false;
		}

		$cacheName = 'C_MSG_RULE_ID_' . md5($ruleId . serialize($params));

		$rule = S($cacheName);
		if (!$rule) {

			$rule = $this->getRow($ruleId);

			// 阻止缓存生成事件
			// if (!$rule || !$rule['status'] || !($rule['msg'] || $rule['wechat'] || $rule['sms'] || $rule['mail'])) {
			// 	return false;
			// }

			if ($rule) {
				// 清除不必要的字段 节省缓存空间
				unset($rule['description']);
				// unset($rule['params']);
				unset($rule['listorder']);
				unset($rule['default_content']);
				unset($rule['status']);
			}

			$rule = $this->parseContent($rule, $params);

			S($cacheName, $rule, 30);
		}

		return $rule;
	}

	/**
	 * 处理变量组成消息内容
	 * @param  array  $rule   模版消息规则
	 * @param  array  $params 一维数组参数. 注意顺序有严格要求使用前先查看规则
	 * @return array          处理好的规则明显
	 */
	public function parseContent(array $rule, $params) {

		// 处理模版
		if (!empty($rule['params']) && $params && is_array($params)) {
			$rule['content'] = str_replace($rule['params'], $params, $rule['content']);
		}

		return $rule;
	}

}