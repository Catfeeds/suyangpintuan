<?php
/**
 * 获奖信息
 */
require_once __DIR__ . '/member.php';
class score extends member {
	function __construct() {
		parent::__construct();
		$this->load->model('score');
		//开关
		if(!$this->score->power) $this->api_result(array('msg'=>'积分模块未启用','code'=>100001));
	}

	function index() {

		$this->load->model('page');
		$size = empty($_GET['size']) ? 10 : $_GET['size'];
		$this->page->set_vars(array('per' => $size));

		$where = ' WHERE mid = ' . MID;

		if (isset($_GET['type'])) {
			$where .= ' AND type=' . intval($_GET['type']);
		}

		$sql = 'SELECT * FROM `###_score_log` ' . $where . ' ORDER BY c_time DESC';

		$list = $this->page->hashQuery($sql)->result_array();

		$data['score_types'] = $typeNames = $this->score->scoreTypes;

		foreach ($list as $k => $v) {
			$list[$k]['score_type'] = $typeNames[$v['type']];
			$list[$k]['c_time_str'] = date('Y-m-d H:i:s',$v['c_time']);
		}

        $data['score'] = $this->score->getTotal(MID);
		
		$data['list']        = $list;
		$data['totalCounts'] = $this->page->pages['total'];
		$data['totalPages']  = $this->page->pages['count'];

		$this->api_result(array('data'=>$data));
	}

}