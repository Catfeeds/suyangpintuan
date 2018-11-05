<?php
/**
 * 获奖信息
 */
require_once __DIR__ . '/member.php';
class prize extends member {
	function __construct() {
		parent::__construct();
		$this->load->model('prize');
		//开关
        if(!$this->prize->power) if(!$this->wheel->power) $this->api_result(array('msg'=>'奖品模块未启用','code'=>100001));
	}

	/**
	 * 中奖记录展示
	 * @param  integer $page 请求的页数
	 * @return void
	 */
	function index() {

		$this->load->model('page');
		$this->page->set_vars(array('per' => $this->site_config['page_size']));
		//$this->page->set_vars(array('per' => 1));

		$sql = 'SELECT * FROM ###_wheel_log WHERE mid = ' . MID . ' ORDER BY status DESC, create_time DESC';

		$list = $this->page->hashQuery($sql)->result_array();

		if ($list) {
			$list = $this->db->lJoin($list, 'goods', 'id,name,thumb', 'good_id', 'id', 'good_');
		}
        $this->load->model('goods');
		$status = $this->prize->logStatus;
		foreach ($list as $k => $v) {
            $v['thumb'] = $v['good_thumb'];
            $list[$k] = $this->goods->getThumb($v);
			if (3 == $v['status']) {
				$list[$k]['left_time'] = $v['expire_time'] > RUN_TIME ? ceil(($v['expire_time'] - RUN_TIME) / 86400) : 0;
			}
			$list[$k]['status_str'] = $status[$v['status']];
            $list[$k]['create_time_str'] = date('Y-m-d H:i:s',$v['create_time']);
            $list[$k]['expire_time_str'] = date('Y-m-d H:i:s',$v['expire_time']);
		}
		$this->api_result(array('data'=>array('list'=>$list)));
	}

	public function detail() {
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if (!$id) {
			$this->error('对不起,页面不存在', '/prize/index',true);
		}

		$type = isset($_GET['type']) ? $_GET['type'] : '';

		$this->load->model('member');

		$prize = $this->prize->getByLogId($id, $type);

		$data = array(
			'prize' => $prize,
			'address' => $this->member->get_address('id=' . $prize['address_id']),
		);
		if (empty($data['prize'])) {
			$this->error('对不起,记录不存在', '/prize/index',true);
		}
		$hotline = C('contact_hotline') ?: '';
        $this->api_result(array('data'=>$data));
	}

	/**
	 * 实物奖品领奖页面
	 * @return void
	 */
	public function express() {
		if (!empty($_POST)) {
			$this->expressHandle($_POST);
			exit;
		}

		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if (!$id) {
			$this->error('对不起,数据不存在', '/prize/index',true);
		}

		$type = isset($_GET['type']) ? $_GET['type'] : '';

		$data = array(
			'prize' => $this->prize->getByLogId($id, $type),
		);

		if (empty($data['prize'])) {
			$this->error('对不起,记录不存在', '/prize/index',true);
		}

		$address_id = isset($data['prize']['address_id']) ? $data['prize']['address_id'] : 0;

		//收货地址
		$addresses = $this->member->member_address(MID, 1);

		$data['address_list'] = empty($addresses) ? array() : $addresses;

		if ($addresses) {
			if (empty($addresses[$address_id])) {
				$data['address'] = array_slice($addresses, 0, 1);
				$data['address'] = $data['address'][0];
				$address_id = $data['address']['id'];
			} else {
				$data['address'] = $addresses[$address_id];
			}
		} else {
			$data['address'] = array();
		}

		$hotline = C('contact_hotline') ?: '';
		$this->api_result(array('data'=>$data));
	}

	public function expressHandle($post) {
		$res = array('error' => 1, 'msg' => '保存失败');

		if (empty($post['id'])) {
			$this->error('对不起,参数错误','',true);
		}

		if (empty($post['address_id'])) {
			$this->error('对不起,请设置收货方式','',true);
		}

		// 安全过滤 end
		$data = array(
			'address_id' => isset($post['address_id']) ? $post['address_id'] : '',
			'message_from' => isset($post['message_from']) ? $post['message_from'] : '',
			'status' => 2,
			'update_time' => RUN_TIME,
		);

		if (false !== $this->db->update('wheel_log', $data, 'id=' . $post['id'])) {
			$this->success('操作成功','',true);
		} else {
			$this->error('对不起,保存失败. 请核对信息后重试','',true);
		};

		exit;
	}

}