<?php
/**
 * ZZCMS 商家管理
 * ============================================================================
 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。
 * 网站地址: http://www.lnest.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 */

class chat extends Lowxp {

	function __construct() {

		defined('BID') || define('BID', 0);


		parent::__construct();
		#加载
	}

	function index() {
		$this->smarty->display($this->mod . '/chat/index.html');
	}
	function mobile() {
		$token = uniqueId();
		S('CHAT_SERVICE_TOKEN_' . MERID . '_' . BID, $token);
        $wx_url = C('wx_url');
        if(!empty($wx_url)){
            $domain = $wx_url;
        }else{
            $domain = Domain;
        }
		$url = REQUEST_SCHEME . $domain . '/chat/service?token=' . $token . (BID ? '&bid=' . BID : '');
		$data = array(
			'url'    => $url,
			'qrcode' => creat_tmp_code($url),
		);
		if (empty($_GET['is_ajax'])) {
			$this->smarty->assign('data', $data);
			$this->smarty->display($this->mod . '/chat/mobile.html');
		} else {
			echo json_encode($data);
		}
	}
	/**
	 * 客服设置
	 */
	function setting() {

		$info = $this->db->get('select * from ###_chat_config where bid=' . BID);

		if (isset($_POST['Submit'])) {
			$post = $_POST['post'];

			$this->load->model("upload");
			$image_url = $this->load->config('picture', 'image_url');

			if ($_FILES['guest_avatar']['error'] == 0) {
				$post['guest_avatar'] = $this->upload->upload_image('guest_avatar', $image_url . 'chat/');
			}

			if ($_FILES['service_avatar']['error'] == 0) {
				$post['service_avatar'] = $this->upload->upload_image('service_avatar', $image_url . 'chat/');
			}

			if ($info) {
				$res = $this->db->update('###_chat_config', $post, array('bid' => BID));
			} else {
				$post['bid'] = BID;
				$res         = $this->db->insert('###_chat_config', $post);
			}

			if (false !== $res) {
				$this->tip("操作成功", array('inIframe' => true));
				$this->exeJs("parent.com.xhide();parent.main.refresh()");
			} else {
				$this->error_msg('操作失败');
			}
			exit;
		}

		if (!$info) {
			$info = array(
				'bid'            => BID,
				'guest_avatar'   => '',
				'service_avatar' => '',
				'service_name'   => '',
				'service_time'   => '',
				'service_tel'    => '',
				'welcome'        => '',
				'status'         => 1,
			);
		}

		$info['welcome_editor'] = $this->form->editor('welcome', $info['welcome'], 'name="post[welcome]" style="width:100%;height:240px;"', array('toolbar' => 'basic'));

		$this->smarty->assign('info', $info);
		$this->smarty->display($this->mod . '/chat/setting.html');

	}

	function records($page = 1) {
		$this->load->model('page');
		$_GET['page'] = intval($page);

		$data = array();

		$data['size'] = isset($_GET['size']) ? $_GET['size'] : (int) $this->common['page_listrows'];
		$this->page->set_vars(array('per' => $data['size']));

		$where = '';
		if (!empty($_GET['group_id'])) {
			$where = ' AND group_id="' . $_GET['group_id'] . '"';
		}

		$fields = array(
			'id'       => 'ID',
			// 'bid'      => '商户id',
			'type'     => '消息类型',
			'group_id' => '分组id',
			'service'  => '客服消息',
			'uid'      => '用户id',
			'name'     => '用户名',
			'avatar'   => '头像',
			'time'     => '发送时间',
			'msg'      => '消息内容',
		);

		$orders = array(
			array('key' => 'time', 'desc' => 1),
			array('key' => 'id', 'desc' => 1),
			array('key' => 'uid'),
		);

		$conditions = $this->getCondtions($fields, $orders);

		if (!empty($conditions['where'])) {
			$where .= ' AND ' . $conditions['where'];
		}

		$sql = 'SELECT * FROM `###_chat_record` WHERE bid = ' . BID . $where;

		$data['list'] = $this->page->hashQuery($sql)->result_array();

		$data['totalCounts'] = $this->page->pages['total'];
		$data['totalPages']  = $this->page->pages['count'];

		$this->smarty->assign('data', $data);

		if (isset($_GET['load'])) {
			$data['html'] = $this->smarty->fetch($this->mod . '/chat/lbi/record_list.html');

			$res = array(
				'data' => $data,
			);

			echo json_encode($res);
			exit;
		}

		$this->smarty->display($this->mod . '/chat/records.html');
	}

	function records_del($group_id = '') {
		if ($group_id && false !== $this->db->delete('chat_record', array('group_id' => $group_id, 'bid' => BID))) {
			$this->tip('删除成功');
			exit;
		}
		$this->tip('操作失败', array('type' => 2));
	}

	function autoreplay($page = 1) {

		$this->smarty->assign('btnMenu', array(
			0 => array('url' => '#!chat/autoreplay_edit?com=xshow|新增自动回复规则', 'name' => '新增规则'),
		));
		$fields = array(
			'id'          => 'ID',
			// 'bid'         => '商户id',
			'keyword'     => '关键字',
			'type'        => '模糊匹配',
			'content'     => '内容',
			'create_time' => '发送时间',
			'status'      => '消息内容',
		);

		$orders = array(
			array('key' => 'create_time', 'desc' => 1),
			array('key' => 'id', 'desc' => 1),
			array('key' => 'keyword'),
		);

		#检索
		$conds     = $this->getCondtions($fields, $orders);
		$condtions = $conds['where'];
		$orderby   = $conds['order'];

		if ($condtions) {
			$condtions = ' AND ' . $condtions;
		}

		#分页
		$this->load->model('page');
		$_GET['page'] = intval($page);

		$data = array();

		$data['size'] = isset($_GET['size']) ? $_GET['size'] : (int) $this->common['page_listrows'];
		$this->page->set_vars(array('per' => $data['size']));

		$sql = "SELECT * FROM `###_chat_autoreplay` WHERE bid = " . BID . $condtions . $orderby;

		$data['list'] = $this->page->hashQuery($sql)->result_array();

		if ($data['list']) {

		}

		$this->smarty->assign('data', $data);
		$this->smarty->display($this->mod . '/chat/autoreplay.html');
	}

	function autoreplay_edit() {
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

		if ($id) {
			$row = $this->db->get('SELECT * FROM `###_chat_autoreplay` WHERE bid = ' . BID . ' AND id = ' . $id . ' LIMIT 1');
		} else {
			$row = array(
				'id'      => '',
				'content' => '',
				'status'  => 1,
			);
		}

		//提交
		if (isset($_POST['Submit'])) {

			$post = $_POST['post'];
			$data = array(
				'keyword'     => $post['keyword'],
				'type'        => $post['type'],
				'content'     => $post['content'],
				'create_time' => RUN_TIME,
				'status'      => $post['status'],
			);

			$success = false;
			if (empty($row['id'])) {

				$data['bid'] = BID;

				if ($this->db->insert('chat_autoreplay', $data)) {
					$success = true;
					$this->tip('新增成功', array('inIframe' => true));
				}
			} elseif (false !== $this->db->update('chat_autoreplay', $data, array('id' => $id, 'bid' => BID))) {
				$success = true;
				$this->tip('编辑成功', array('inIframe' => true));
			}

			if ($success) {
				// 编辑后清理缓存
				S('CHAT_AUTOREPLAY_LIST_' . MERID . '_' . BID, null);

				$this->exeJs("parent.com.xhide();parent.main.refresh()");
			} else {
				$this->tip('操作失败', array('inIframe' => true, 'type' => 2));
			}
			exit;
		}

		$row['content_editor'] = $this->form->editor('content', $row['content'], 'name="post[content]" style="width:100%;height:240px;"', array('toolbar' => 'basic'));

		$this->smarty->assign('row', $row);
		$this->smarty->display($this->mod . '/chat/autoreplay_edit.html');

	}

	function autoreplay_del($id) {
		if ($id && false !== $this->db->delete('chat_autoreplay', array('id' => $id, 'bid' => BID))) {
			$this->tip('删除成功');
			exit;
		}
		$this->tip('操作失败', array('type' => 2));
	}

	function records_users($page = 1) {

		#分页
		$this->load->model('page');
		$_GET['page'] = intval($page);

		$data = array();

		$data['size'] = isset($_GET['size']) ? $_GET['size'] : (int) $this->common['page_listrows'];
		$this->page->set_vars(array('per' => $data['size']));

		$where = '';
		if (!empty($_GET['group_id'])) {
			$where = ' AND group_id="' . $_GET['group_id'] . '"';
		}

		$fields = array(
			'id'       => 'ID',
			// 'bid'      => '商户id',
			'type'     => '消息类型',
			'group_id' => '分组id',
			'service'  => '客服消息',
			'uid'      => '用户id',
			'name'     => '用户名',
			'avatar'   => '头像',
			'time'     => '发送时间',
			'msg'      => '消息内容',
		);

		$orders = array(
			array('key' => 'time', 'desc' => 1),
			array('key' => 'id', 'desc' => 1),
			array('key' => 'uid'),
		);

		$conditions = $this->getCondtions($fields, $orders);

		if (!empty($conditions['where'])) {
			$where .= ' AND ' . $conditions['where'];
		}

		$sql = 'SELECT DISTINCT name, group_id, avatar FROM `###_chat_record` WHERE service= 0 AND bid= ' . BID . $where;

		$data['list'] = $this->page->hashQuery($sql)->result_array();

		$data['totalCounts'] = $this->page->pages['total'];
		$data['totalPages']  = $this->page->pages['count'];

		$this->smarty->assign('data', $data);
		$data['html'] = $this->smarty->fetch($this->mod . '/chat/lbi/user_list.html');

		$res = array(
			'data' => $data,
		);

		echo json_encode($res);
	}

	/** 检索条件
	 * @return array
	 */
	protected function getCondtions($fields, $orders) {

		// 筛选字段过滤 避免sql字段不存在错误
		$filters = empty($_GET['filters']) ? array() : $_GET['filters'];
		foreach ($filters as $k => $v) {
			if (!isset($fields[$k])) {
				unset($filters[$k]);
			}
		}

		// 加入默认筛选条件
		$where = $this->parseFilters($filters);
		// $where = $where ? ' WHERE ' . $where : '';

		// 默认排序规则 //需传入数组 类似 orders[0][key]=status&orders[0][status]=desc&orders[1][key]=id&orders[1][status]=desc&orders[2][key]=end_time&orders[2][status]=desc
		$orders = empty($_GET['orders']) ? $orders : $_GET['orders'];

		// 去除不参与排序的字段

		// 解析排序规则
		$order = $this->parseOrders($orders, $fields);

		$this->smarty->assign('fields', $fields);
		$this->smarty->assign('filters', $filters);
		$this->smarty->assign('orders', $orders);

		return array('where' => $where, 'order' => $order);
	}

	/**
	 * 通用where语句生成组件
	 * // $filters = array(
	 * // 	'id'     => '',
	 * // 	'title'  => array('cs', ''),
	 * // 	'c_time' => array(
	 * // 		array('ge', 0),
	 * // 		array('le', 999999),
	 * // 	),
	 * // );
	 * @param  array   $filters 过滤器
	 * @param  integer $sid     商户id
	 * @param  string  $where   默认筛选条件
	 * @return string           sql 语句中的 where 条件 不包含where
	 */
	function parseFilters($filters, $where = '') {
		$con = array();

		if ($where) {
			$con[] = $where;
		}
		foreach ($filters as $k => $v) {
			// 去掉非0值 和无操作符的
			if ((!$v && !is_numeric($v)) || (is_array($v) && empty($v[0]))) {
				continue;
			}

			// 解析语法 array(filed => value)
			if (!is_array($v)) {
				$v     = trim($v);
				$con[] = $k . '="' . $v . '"';
				continue;
			}

			// 解析语法
			// field => array('eq', value)
			// array('is')
			// array('eq', value, 'timestamp')
			// array('bt', array(start, end), 'timestamp')
			if (!is_array($v[0])) {
				if (isset($v[1]) && $v[1] !== '') {
					$v = $this->parseValue($v);

					$v[0] == 'bt' && $v[1] = join(',', $v[1]);

					$tmp           = $this->convertFilter($k, $v[0], (isset($v[1]) ? $v[1] : NULL));
					$tmp && $con[] = $tmp;
				}
				continue;
			}

			// 解析语法
			// field => array(array('ge', 0), array('le', 999999))
			// field => array(array('ge', 0, 'timestamp'), array('le', 999999, 'timestamp'));
			foreach ($v as $n) {
				if (isset($n[1]) && $n[1] !== '') {
					$n             = $this->parseValue($n);
					$tmp           = $this->convertFilter($k, $n[0], (isset($n[1]) ? $n[1] : NULL));
					$tmp && $con[] = $tmp;
				}
			}

		}
		return $con ? join(' AND ', $con) : '';
	}

	// 解析数值
	function parseValue($v) {
		if (isset($v[2])) {
			switch ($v[2]) {
				case 'timestamp':
					if (is_array($v[1])) {
						array_walk($v[1], 'strtotime');
					} elseif (is_string($v[1])) {
						$v[1] = strtotime($v[1]) ?: 0;
					}
					break;
				default:
					# code...
					break;
			}
		}
		return $v;
	}

	/**
	 * 解析查询表达式
	 * @param  string $field        字段名称
	 * @param  string $comparator   查询规则
	 * @param  string $value        查询数值
	 * @return string
	 */
	function convertFilter($field, $comparator, $value = NULL) {
		if ($value === '') {
			return '';
		}

		switch ($comparator) {
			case 'cs':return $field . " LIKE '%" . $value . "%'";
			case 'sw':return $field . " LIKE '" . $value . "%'";
			case 'ew':return $field . " LIKE '%" . $value . "'";
			case 'eq':return $field . " = '" . $value . "'";
			case 'lt':return $field . " < '" . $value . "'";
			case 'gt':return $field . " > '" . $value . "'";
			case 'le':return $field . " <= '" . $value . "'";
			case 'ge':return $field . " >= '" . $value . "'";
			case 'bt':$v = explode(',', $value);if (count($v) < 2) {
					return false;
				}
				return $field . " BETWEEN '" . $v[0] . "' AND '" . $v[1] . "'";
			case 'in':return $field . " IN (" . explode(',', $value) . ")";
			case 'is':return $field . " IS NULL";
			case 'ex':return $field . " <> '' AND " . $field . " IS NOT NULL";
			case 'ncs':return $field . " NOT LIKE '%" . $value . "%'";
			case 'nsw':return $field . " NOT LIKE '" . $value . "%'";
			case 'new':return $field . " NOT LIKE '%" . $value . "'";
			case 'neq':return $field . " <> '" . $value . "'";
			case 'nlt':return $field . " >= '" . $value . "'";
			case 'ngt':return $field . " <= '" . $value . "'";
			case 'nle':return $field . " > '" . $value . "'";
			case 'nge':return $field . " < '" . $value . "'";
			case 'nbt':$v = explode(',', $value);if (count($v) < 2) {
					return false;
				}
				return $field . " NOT BETWEEN '" . $v[0] . "' AND '" . $v[1] . "'";
			case 'nin':return $field . " NOT IN (" . explode(',', $value) . ")";
			case 'nis':return $field . " IS NOT NULL";
			case 'nex':return "(" . $field . " = '' OR " . $field . " IS NULL)";
		}
	}

	/**
	 * 解析排序规则 并去除不参与排序的字段
	 * @param  array  $orders 排序规则, 格式类似: array(array('key' => 'listorder'),array('key' => 'u_time', 'desc' => 1),array('key' => 'id', 'desc' => 1))
	 * @param  array  $fields 待排序字段名
	 * @return string         排序sql 语句 包含order by 文本
	 */
	public function parseOrders(array $orders, array $fields) {
		$tmp        = array();
		$fieldskeys = array_keys($fields) ?: array();
		foreach ($orders as $k => $v) {
			if (in_array($v['key'], $fieldskeys)) {
				$tmp[] = $v['key'] . (empty($v['desc']) ? '' : ' desc');
			}
		}
		return $tmp ? ' ORDER BY ' . join(',', $tmp) : '';
	}

}