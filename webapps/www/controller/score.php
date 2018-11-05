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
		if(!$this->score->power){
			$this->exeJs('location.href="/"');die;
		}
	}

	function index($page = 1) {
		$_GET['page'] = $page;

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
		}

		$score = $this->score->getTotal(MID);
		$this->smarty->assign('score',$score);
		
		$data['list']        = $list;
		$data['totalCounts'] = $this->page->pages['total'];
		$data['totalPages']  = $this->page->pages['count'];

		#异步加载
		if (isset($_GET['load'])) {
			if ($list) {
				$content = '';
				foreach ($list as $v) {
					$this->smarty->assign('m', $v);
					$content .= $this->smarty->fetch('score/lbi/list.html');
				}
				echo $content;
			}
			die;
			//$this->ajaxListItem('score/lbi/list.html', $data);
		}
		$this->smarty->assign('data', $data);
		$this->smarty->display('score/list.html');
	}

}