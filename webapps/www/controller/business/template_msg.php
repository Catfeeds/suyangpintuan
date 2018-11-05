<?php
/**
 * ZZCMS 模版消息管理
 * ============================================================================
 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。
 * 网站地址: http://www.lnest.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 */
class template_msg extends Lowxp {

	/**
	 * 发送方式 按需扩展
	 * 注意顺序必须按照 微信 站内信 邮件 短信的方式
	 * 如果扩展需要注意顺序
	 * @var array
	 */
	public $sendType = array('msg', 'wechat', 'mail', 'sms');

	public $btnMenu = array(
		0 => array('url' => '#!template_msg/index?type=0', 'name' => '商户类'),
		1 => array('url' => '#!template_msg/index?type=1', 'name' => '会员类'),
		//2 => array('url' => '#!template_msg/index?type=2', 'name' => '分销代理类'),
		3 => array('url' => '#!template_msg/index?type=3', 'name' => '订单类'),
		//4 => array('url' => '#!template_msg/index?type=4', 'name' => '佣金类'),
		5 => array('url' => '#!template_msg/index?type=5', 'name' => '营销类'),
		//6 => array('url' => '#!template_msg/index?type=6', 'name' => '其他类'),
	);

	public function __construct() {
		parent::__construct();
		$this->load->model('template_msg');
	}
	public function index() {
		$type = empty($_GET['type']) ? 0 : $_GET['type'];

		$data['list'] = $this->template_msg->getList($type);

		$this->smarty->assign('btnNo', $type);
		$this->smarty->assign('data', $data);
		$this->smarty->display('business/template_msg/list.html');
	}

	/**
	 * 设置发送开关
	 * 注意这里接受的id 是###_template_msg_rule 表的id
	 */
	public function setStatus() {
		if (empty($_POST['id']) || floor($_POST['index']) != $_POST['index']) {
			exit(json_encode(array('error' => 1, 'msg' => '对不起, 参数错误')));
		}

		$id     = $_POST['id'];
		$index  = intval($_POST['index']);
		$status = empty($_POST['status']) ? 0 : 1;

		$this->template_msg->setStatus($id, $index, $status);

		exit(json_encode(array('error' => 0, 'msg' => '操作成功')));
	}

	//创建/更新
	public function edit() {
		//提交
		if (isset($_POST['post'])) {
			$this->editHandle();
		}

		if (empty($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
			exit(json_encode(array('error' => 1, 'msg' => '对不起,参数错误')));
		}

		$row = $this->template_msg->getRow($_GET['id']);
		$this->smarty->assign('row', $row);
		$this->smarty->assign('typeName', $this->template_msg->typeName);
		$this->smarty->display('business/template_msg/edit.html');
	}

	protected function editHandle() {
		$post = $_POST['post'];
		$res  = $this->template_msg->save($post);
		exit(json_encode($res));
	}
}