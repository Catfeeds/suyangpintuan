<?php

class prize_model extends Lowxp_Model {
	
	public $power = 0;//总开关
	public $logStatus = array(
		0 => '已作废',
		1 => '已发奖',
		2 => '待发奖',
		3 => '待领奖',
	);

	public $logType = array('wheel');

    function __construct() {
        if (file_exists(AppDir . 'config/version_wheel.php')) {
            include AppDir . 'config/version_wheel.php';
        }
        if (defined("Version_wheel")) {
            $this->power = 1;
        }
    }

	public function getByLogId($id, $type) {
		$prize = array();
		switch ($type) {
			case 'wheel':
				$this->load->model('wheel');
				$prize = $this->wheel->getPrizeByLogId($id);
				break;
			default:
				break;
		}

		return $prize;

	}

	/**
	 * 根据mid 或者未领奖的实物奖品数量
	 * 因为后期中奖种类过多 需要用统一方法来统计
	 * @param  integer $mid 用户mid
	 * @return integer      带领取的实物奖品数量
	 */
	public function getUnreceivedCount($mid) {
		$sql   = 'SELECT count(*) as count FROM `###_wheel_log` WHERE status=3 AND expire_time>' . RUN_TIME . ' AND mid = ' . $mid;
		$count = $this->db->getstr($sql);
		return $count ?: 0;
	}
}