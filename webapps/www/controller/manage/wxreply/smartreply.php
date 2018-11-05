<?php
/**
 * Name 微信自动回复
 * Class wxreply
 */
class smartreply extends Lowxp {
	/**
	 * 微信菜单设置
	 */
	function index() {
		$rules = $this->db->select("SELECT * FROM ###_wx_autoreply_rule WHERE rule_type='normal' " );

		$this->load->model('wxnews');
		foreach ($rules as $k => $v) {
			if (empty($v['reply_list'])) {
				continue;
			}
			$replyList = json_decode($v['reply_list'], true);

			$rules[$k]['reply_list'] = $this->wxnews->getTitlesByReplys($replyList);

		}

		$this->smarty->assign('rules', $rules);
		$this->smarty->display('manage/wxreply/smartreply/list.html');
	}
	/**
	 * 编辑
	 * @param string $rule_id
	 */
	function edit($rule_id = '') {
		is_numeric($rule_id) || die('Error!');
		$rule               = $this->db->get("SELECT * FROM ###_wx_autoreply_rule WHERE id=" . $rule_id);
		$rule['reply_list'] = json_decode($rule['reply_list'], true);
		$rule['keywords']   = $this->db->select("SELECT * FROM ###_wx_autoreply_keyword WHERE rule_id=" . $rule_id . " ORDER BY id");
		$this->load->model('wxnews');
		$rule['reply_list'] = $this->wxnews->getMsgsByReplyList($rule['reply_list']);
		$this->add($rule);
	}
	/**
	 * 添加
	 */
	function add($rule = array()) {
		if (!isset($rule['id'])) {
			$rule = array(
				'id'         => '',
				'name'       => '',
				'keywords'   => array(),
				'reply_list' => array(),
			);
		}
		$this->smarty->assign('rule', $rule);
		$this->smarty->assign('ruleJson', json_encode($rule));
		$this->smarty->display('manage/wxreply/smartreply/form.html');
	}
	/**
	 * 删除规则. 根据ruleid 删除关联的三张表
	 * @param string $rule_id
	 */
	function delRule($rule_id = '') {
		is_numeric($rule_id) || $this->fatalError('参数错误!');
		$rule = $this->db->get("SELECT * FROM ###_wx_autoreply_rule WHERE id=" . $rule_id);
		if (isset($rule['id'])) {
			$this->db->delete('wx_autoreply_rule', array('id' => $rule['id']));
			$this->db->delete('wx_autoreply_text', array('rule_id' => $rule['id']));
			$this->db->delete('wx_autoreply_keyword', array('rule_id' => $rule['id']));
		}
		$this->tip('删除成功!');
		$this->exeJs('main.refresh();');
	}
	/**
	 * 删除关键字匹配
	 */
	function del($id) {
		$this->db->delete('wx_reply', array('re_id' => $id));
		$this->tip('删除成功!');
		$this->exeJs('main.refresh();');
	}
	/**
	 * 创建/更新规则
	 */
	function submit() {
		if (empty($_POST['reply_data']) || empty($_POST['reply_type'])) {
			$this->fatalError('请添加回复内容!');
		}
		if (empty($_POST['rule_name'])) {
			$this->fatalError('请输入规则名称!');
		}
		if (empty($_POST['kw'])) {
			$this->fatalError('请输入规则名称!');
		}
		$reply_data = $_POST['reply_data'];
		$reply_type = $_POST['reply_type'];
		#关键词
		$keywords = $_POST['kw'];
		#关键词匹配
		$kwmatch   = isset($_POST['match']) ? $_POST['match'] : array();
		$ruleInput = array(
			'name'      => $_POST['rule_name'],
			'rule_type' => 'normal',
			'keywords'  => implode(',', $keywords),
		);
		if (isset($_POST['id']) && is_numeric($_POST['id'])) {
			// 有id则更新
			$rule_id = $_POST['id'];
			$this->db->delete('wx_autoreply_text', array('rule_id' => $rule_id));
			$this->db->delete('wx_autoreply_keyword', array('rule_id' => $rule_id));
			$this->db->update('wx_autoreply_rule', $ruleInput, array('id' => $rule_id));
			$tipMsg = '更新成功!';
		} else {
			$rule_id = $this->db->insert('wx_autoreply_rule', $ruleInput);
			$tipMsg  = '添加成功!';
		}
		//回复关键词设置
		foreach ($keywords as $key => $val) {
			$match = isset($kwmatch[$key]) ? 1 : 0;
			$this->db->insert('wx_autoreply_keyword', array(
				'keyword' => $val,
				'match'   => $match,
				'rule_id' => $rule_id,
			));
		}
		//回复内容更新
		$reply_list = array();
		foreach ($reply_data as $key => $val) {
			$msgType = $reply_type[$key];
			$msg_id  = '';
			switch ($msgType) {
				case 'news':
					$msg_id = $val;
					break;
				case 'text':
					$msg_id = $this->db->insert('wx_autoreply_text', array(
						'rule_id' => $rule_id,
						'content' => clearHtml($val),
					));
					break;
			}
			if ($msg_id) {
				$reply_list[] = array(
					'msg_type' => $msgType,
					'msg_id'   => $msg_id,
				);
			}
		}

		$data = array(
			'reply_list' => json_encode($reply_list),
			'c_time'     => RUN_TIME,
		);

		$this->db->update('wx_autoreply_rule', $data, array('id' => $rule_id));
		$this->tip($tipMsg);
		$this->exeJs('location.href="/manage#!wxreply/smartreply";');
	}
}