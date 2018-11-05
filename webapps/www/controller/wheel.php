<?php
/**
 * 大转盘控制器
 */
require_once __DIR__ . '/member.php';
class wheel extends member {
	function __construct() {
		parent::__construct();
		$this->load->model('wheel');
	}

	function index() {

		$wheel = $this->wheel->getActive();

		if (empty($wheel['prize']) || empty($wheel['config'])) {
			exit($this->msg('对不起,活动暂未开始,敬请期待', array('iniframe' => '', 'url' => '/member/index')));
		}

		// 积分
		$this->load->model('score');
		$score = $this->score->getTotal(MID);

		$this->smarty->assign('score', $score);
		$this->smarty->assign('wheel', $wheel);
		$this->smarty->display('wheel/index.html');
	}

	function action() {
		$res = array(
			'error' => 1,
		);

		$wheel = $this->wheel->getActive();

		if (empty($wheel['config']['score_cost'])) {
			$res['msg'] = '对不起,活动暂未开始,敬请期待';
			exit(json_encode($res));
		}

		if ($wheel['config']['start_time'] > RUN_TIME) {
			$res['msg'] = '对不起,活动将于 ' . date('Y-m-d H:i:s', $wheel['config']['start_time']) . '开始 ,敬请期待';
			exit(json_encode($res));
		}

		if ($wheel['config']['end_time'] && $wheel['config']['end_time'] < RUN_TIME) {
			$res['msg'] = '对不起,活动已经结束, 敬请期待下期';
			exit(json_encode($res));
		}

		$res = $this->wheel->action($wheel);

		exit(json_encode($res));

	}

}