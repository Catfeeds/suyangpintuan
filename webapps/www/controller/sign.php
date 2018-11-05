<?php

/**
 * Class welcome
 */
require_once __DIR__ . '/member.php';
class sign extends member {

	function __construct() {
		parent::__construct();
	}

	public function index() {

		$sql  = "SELECT * FROM ###_score_log WHERE type=1 AND mid = " . MID . " ORDER BY c_time DESC LIMIT 10";
		$logs = $this->db->select($sql);
		$this->smarty->assign('logs', $logs);

		$this->load->model('score');
		$config = $this->score->getConfig(1);
		$this->smarty->assign('rule', $config);
		
		$rule = $this->score->getRow(1);
		if($rule['status']!=1){
			//die($this->msg('您没有该权限'));
			$this->error("无权操作权限！","/");
			die;
		}

		$score = $this->score->getTotal(MID);

		$today = strtotime(date('Y-m-d', RUN_TIME));

		if (empty($score) || ($score['last_sign'] + 86400 < $today)) {
			// 昨天没签到则不再连续
			$score['continue_sign'] = 0;
		}

		if (!empty($score['last_sign']) && $score['last_sign'] > $today) {
			$score['signed'] = true;
		}

		$this->smarty->assign('score', $score);
		$this->smarty->display('member/sign.html');

	}

	public function action() {

		$continue = empty($_POST['continue']) ? 0 : $_POST['continue'];

		$this->load->model('sign');
		$res = $this->sign->action(MID, $continue);

		exit(json_encode($res));
	}

}