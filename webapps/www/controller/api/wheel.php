<?php
/**
 * 大转盘控制器
 */
require_once __DIR__ . '/member.php';
class wheel extends member {
	function __construct() {
		parent::__construct();
		$this->load->model('wheel');
		//开关
        if(!$this->wheel->power) $this->api_result(array('msg'=>'大转盘模块未启用','code'=>100001));
	}

	function index() {
		$wheel = $this->wheel->getActive();
		if (empty($wheel['prize']) || empty($wheel['config'])) {
            $this->api_result(array('msg'=>'对不起,活动暂未开始,敬请期待','code'=>100002));
		}
		// 积分
		$this->load->model('score');
		$score = $this->score->getTotal(MID);
		$data = array();
        $data['score'] = $score;
        if(!empty($wheel['prize'])){
            foreach ($wheel['prize'] as $key=>$val){
                $wheel['prize'][$key]['level_str'] = ten2chinese($val['level']);
            }
        }
        $data['wheel'] = $wheel;
        $this->api_result(array('data'=>$data));
	}

	function action() {
		$res = array(
			'code' => 10001,
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
		if(!empty($res['msg'])) $this->api_result($res);
		$res = $this->wheel->action($wheel);
		if(!empty($res['msg'])) $res['msg'] = strip_tags($res['msg']);
		unset($res['code']);
		$this->api_result($res);

	}

}