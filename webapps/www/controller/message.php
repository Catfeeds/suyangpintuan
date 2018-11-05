<?php
/**
 * 站内信控制器
 */
require_once __DIR__ . '/member.php';
class message extends member {
	function __construct() {
		parent::__construct();
		$this->load->model('message');
	}

	function index($page = 1) {
		// 登录时将所有的未读消息写入到引用表 这样不活跃的用户就没有消息引用 减少数据压力
		$this->message->getUnRead(MID);
		
		if (!empty($_POST['ids']) && is_array($_POST['ids']) && isset($_GET['remove'])) {
			// 批量删除操作
			$ids = join(',', $_POST['ids']);
			$this->message->remove($ids, MID);
		}

		$this->load->model('page');
		$_GET['page'] = $page;
		$this->page->set_vars(array('per' => 10));
		// $this->page->set_vars(array('per' => $this->site_config['page_size']));

		if (!empty($_GET['type'])) {
			$type = intval($_GET['type']);
			if (!$type) {
				die;
			}
			$sql = 'SELECT a.mid,a.message_id,a.status,b.type as message_type,b.title as message_title,b.content as message_content,b.status as message_status FROM `###_message_status` a, `###_1_message` b WHERE a.message_id = b.id AND a.status> 0 AND a.mid = ' . MID . ' AND b.type = ' . $type . ' ORDER BY a.status , a.id desc';

			$list = $this->page->hashQuery($sql)->result_array();
		} else {
			$sql = 'SELECT * FROM `###_message_status` WHERE status = 1 AND mid = ' . MID . ' ORDER BY status , id desc';
			$list = $this->page->hashQuery($sql)->result_array();

			$list = $this->db->lJoin($list, 'message', 'id,type,title,content,status', 'message_id', 'id', 'message_');
		}

		#异步加载
		if (isset($_GET['load'])) {
			if ($list) {
				$content = '';
				foreach ($list as $v) {
					$this->smarty->assign('m', $v);
					$content .= $this->smarty->fetch('message/lbi/list.html');
				}
				echo $content;
			}
			die;
		}

		$messageTypes = $this->message->types;
		unset($messageTypes[0]);

		$this->smarty->assign('types', $messageTypes);
		$this->smarty->assign('list', $list);
		$this->smarty->display('message/list.html');
	}

	public function detail($id) {
		$id = intval($id);
		if (!$id) {
			$this->error('对不起,页面不存在');
		}
		$this->message->getDetail($id, MID);

		if (!$data) {
			$this->error('对不起消息未找到');
		}

		// 打开的消息标记已读
		$this->message->read($id, MID);

		$this->smarty->assign('data', $data);
		$this->smarty->display('message/detail.html');
	}

	public function setRead($id) {
		if (false !== $this->message->read($id, MID)) {
			$this->success('');
		} else {
			$this->error('系统繁忙, 请稍后重试');
		}
	}

	public function remove() {
		$ids = isset($_POST['ids']) ? $_POST['ids'] : 0;
		if (!$ids) {
			$this->error('对不起,删除失败.请选择想删除的消息');
		}
		if(is_array($ids)){
			$ids = join(',', $ids);
		}
		if (false !== $this->message->remove($ids, MID)) {
			$this->success('删除成功');
		} else {
			$this->error('系统繁忙, 删除失败,请稍后重试');
		}
	}
}