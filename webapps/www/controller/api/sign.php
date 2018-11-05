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
	    $data = array();
		$sql  = "SELECT * FROM ###_score_log WHERE type=1 AND mid = " . MID . " ORDER BY c_time DESC LIMIT 10";
        $logs = $this->db->select($sql);
        if(!empty($logs)){
            foreach ($logs as $k=>$v){
                $logs[$k]['c_time_str'] = date('Y-m-d H:i:s',$v['c_time']);
            }
        }
        $data['logs'] = $logs;

		$this->load->model('score');
		$data['rule'] = $this->score->getConfig(1);
		
		$rule = $this->score->getRow(1);
		if($rule['status']!=1){
			//die($this->msg('您没有该权限'));
			$this->error("无权操作权限！","/",true);
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
        $data['score'] = $score;
		$this->api_result(array('data'=>$data));
	}

	public function action() {
        $this->load->model('score');
        $score = $this->score->getTotal(MID);

        $today = strtotime(date('Y-m-d', RUN_TIME));

        if (empty($score) || ($score['last_sign'] + 86400 < $today)) {
            // 昨天没签到则不再连续
            $score['continue_sign'] = 0;
        }

		$continue = $score['continue_sign'];

		$this->load->model('sign');
		$res = $this->sign->action(MID, $continue);

		exit(json_encode($res));
	}

}