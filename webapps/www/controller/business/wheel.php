<?php

/**

 * ZZCMS 优惠券

 * ============================================================================

 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。

 * 网站地址: http://www.lnest.com；

 * ----------------------------------------------------------------------------

 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和

 * 使用；不允许对程序代码以任何形式任何目的的再发布。

 */

class wheel extends Lowxp {

	public function __construct() {
		$this->btnMenu = array(
			0 => array('url' => '#!wheel/index', 'name' => '活动列表'),
			1 => array('url' => '#!wheel/edit', 'name' => '添加活动'),
			2 => array('url' => '#!wheel/logs', 'name' => '中奖记录'),

		);

		parent::__construct();

		$this->load->model('wheel');
	}

	#列表

	public function index($page = 1) {
		$_GET['page'] = $page;
		$sql          = 'SELECT * FROM ###_wheel';

		// 所有被筛选的字段默认值
		$filterFields = array(
			'id',
			'title',
			'title_blur',
			'status',
			'start_time_egt',
			'start_time_elt',
			'end_time_egt',
			'end_time_elt',
			'create_time_egt',
			'create_time_elt',
		);

		$filters = array();
		foreach ($filterFields as $v) {
			$filters[$v] = isset($_GET['filters'][$v]) ? $_GET['filters'][$v] : '';
		}

		$filters['title_blur'] = isset($_GET['filters']['title_blur']) ? $_GET['filters']['title_blur'] : 1;

		$sql .= $this->parseFilters($filters);

		// 默认排序规则
		$orders = empty($_GET['orders']) ? array(
			array('key' => 'status', 'status' => 'desc'),
			array('key' => 'id', 'status' => 'desc'),
			array('key' => 'end_time', 'status' => 'desc'),
		) : $_GET['orders'];

		$orderTypes = array(
			'id'          => 'id',
			'title'       => '名称',
			'start_time'  => '开始时间',
			'end_time'    => '结束时间',
			'create_time' => '新建时间',
			'status'      => '状态',
		);

		$orderFields = array_keys($orderTypes);
		$sql .= $this->parseOrders($orders, $orderFields);

		$this->load->model('page');
		$list = $this->page->hashQuery($sql)->result_array();

		$this->smarty->assign('status', $this->wheel->baseStatus);
		$this->smarty->assign('orderTypes', $orderTypes);
		$this->smarty->assign('filters', $filters);
		$this->smarty->assign('orders', $orders);
		$this->smarty->assign('list', $list);
		$this->smarty->assign('btnNo', 0);
		$this->smarty->display('business/wheel/list.html');

	}

	//创建/更新
	public function edit() {
		//提交
		if (!empty($_POST['post'])) {
			$this->editHandle($_POST['post']);
		}

		$id = isset($_GET['id']) ? $_GET['id'] : 0;

		$row = array();

		//编辑
		if ($id) {
			$row = $this->db->get("SELECT * FROM " . $this->wheel->baseTable . " WHERE id=" . $id);

			if ($row) {
				$prize = $this->wheel->getPrizes($id, true);
				$this->smarty->assign('prize', $prize);
			}

		} else {
			$this->smarty->assign('btnNo', 1);
		}

		$defaultDercription = '<p>1. 本次活动单次抽奖消耗 XX 积分，积分直接从用户积分里面扣除</p>
            <p>2. 本活动有效期 XX 起,至 XX </p>
            <p>3. 实物奖品 15天内不填写收货地址并且不主动联系商家. 视为主动放弃奖品</p>
            <p>4. 本次活动最终解释权归 XX 所有</p>';

		$row['html_description'] = $this->form->editor('description', empty($row['description']) ? $defaultDercription : $row['description'], 'name="post[description]" style="width:100%;height:200px;"', array('toolbar' => 'basic'));

		$this->smarty->assign('row', $row);
		$this->smarty->display('business/wheel/edit.html');

	}

	public function editHandle($post) {

		$res = $this->wheel->save($post);
		if (isset($res['error']) && $res['error'] == 0) {
			$this->tip($res['msg'], array('inIframe' => true));
			$this->exeJs("parent.window.location='/business#!wheel/index';");
		} else {
			$this->tip($res['msg'], array('inIframe' => true, 'type' => 2));
		}
		exit;
	}

	public function logs($page = 1) {
		$_GET['page'] = $page;
		$sql          = 'SELECT * FROM ###_wheel_log';

		// 所有被筛选的字段默认值
		$filterFields = array(
			'wheel_id',
			'mid',
			'status',
			'level',
			'type',
			'create_time_egt',
			'create_time_elt',
			'expire_time_egt',
			'expire_time_elt',
			'remark',
			'remark_blur',
			'need_express',
		);

		$filters = array();
		foreach ($filterFields as $v) {
			$filters[$v] = isset($_GET['filters'][$v]) ? $_GET['filters'][$v] : '';
		}

		// 设置默认模糊查找示例
		$filters['remark_blur'] = isset($_GET['filters']['remark_blur']) ? $_GET['filters']['remark_blur'] : 1;

		$sql .= $this->parseFilters($filters);

		// 默认排序规则
		$orders = empty($_GET['orders']) ? array(
			array('key' => 'create_time', 'status' => 'desc'),
			array('key' => 'status', 'status' => 'desc'),
			array('key' => 'level', 'status' => 'asc'),
		) : $_GET['orders'];

		$orderTypes = array(
			'create_time'  => '中奖时间',
			'expire_time'  => '过期时间',
			'type'         => '奖项类型',
			'mid'          => '会员id',
			'wheel_id'     => '活动id',
			'level'        => '奖项等级',
			'status'       => '状态',
			'prizeExpress' => '运输状态',
		);

		$orderFields = array_keys($orderTypes);
		$sql .= $this->parseOrders($orders, $orderFields);

		$this->load->model('page');

		$list = $this->page->hashQuery($sql)->result_array();

		$prizeLevels = $this->wheel->getLevels();
		$this->smarty->assign('prizeLevels', $prizeLevels);

		$this->smarty->assign('prizeExpress', $this->wheel->prizeExpress);
		$this->smarty->assign('prizeTypes', $this->wheel->prizeTypes);
		$this->smarty->assign('prizeStatus', $this->wheel->prizeStatus);
		$this->smarty->assign('orderTypes', $orderTypes);
		$this->smarty->assign('filters', $filters);
		$this->smarty->assign('orders', $orders);
		$this->smarty->assign('list', $list);
		$this->smarty->assign('btnNo', 2);
		$this->smarty->display('business/wheel/logs.html');
	}

	public function detail() {
		$id = isset($_GET['id']) ? $_GET['id'] : 0;

		if (!$id) {
			$this->tip('对不起,参数错误', array('inIframe' => true, 'type' => 2));
			exit;
		}

		$prize = $this->wheel->getPrizeByLogId($id);
		$this->load->model('member');

		$address = $prize['address_id'] ? $this->member->get_address('id=' . $prize['address_id']) : array();

		$this->smarty->assign('address', $address);
		$this->smarty->assign('row', $prize);
		$this->smarty->assign('btnNo', 2);
		$this->smarty->display('business/wheel/detail.html');
	}

	/**
	 * 处理待发奖的需要运输的实物奖品
	 * @param  integer $id 中奖记录id
	 * @return
	 */
	public function dealExpress() {
		if (isset($_POST['post'])) {
			$this->dealExpressHandle($_POST['post']);
			exit;
		}
		$id = isset($_GET['id']) ? $_GET['id'] : 0;

		if (!$id) {
			$this->tip('对不起,参数错误', array('inIframe' => true, 'type' => 2));
			exit;
		}

		$prize = $this->wheel->getPrizeByLogId($id);
		$this->load->model('member');

		$address = $prize['address_id'] ? $this->member->get_address('id=' . $prize['address_id']) : array();

		$this->smarty->assign('address', $address);
		$this->smarty->assign('row', $prize);
		$this->smarty->assign('btnNo', 2);
		$this->smarty->display('business/wheel/dealExpress.html');

	}

	public function dealExpressHandle($post) {
		$res = array('error' => 1, 'msg' => '保存失败');

		if (empty($post['id'])) {
			$this->tip('对不起,参数错误');
		}

		if (empty($post['status'])) {
			$this->tip('对不起,操作错误');
		}

		// 安全过滤 start
		if (1 == $post['status']) {
			if (empty($post['express_name'])) {
				$this->tip('对不起,请输入快递公司名称', array('inIframe' => true, 'type' => 2));
				exit;
			}

			if (empty($post['express_sn'])) {
				$this->tip('对不起,请输入快递单号', array('inIframe' => true, 'type' => 2));
				exit;
			}
		} elseif (2 != $post['status']) {
			// 为避免错误 status 只能为1或者2 发货或者未发货
			$this->tip('对不起,操作错误');
		}

		// 安全过滤 end

		$data = array(
			'express_name' => isset($post['express_name']) ? $post['express_name'] : '',
			'express_sn'   => isset($post['express_sn']) ? $post['express_sn'] : '',
			'message_to'   => isset($post['message_to']) ? $post['message_to'] : '',
			'status'       => $post['status'],
			'update_time'  => RUN_TIME,
		);

		$this->db->update('wheel_log', $data, 'id=' . $post['id']);

		$this->tip('保存成功', array('inIframe' => true, 'type' => 0));
		$this->exeJs("parent.window.location='/business#!wheel/logs';");
		exit;
	}

	/**
	 *
	 * @return string $where 过滤条件
	 */
	/**
	 * 通用where语句生成组件
	 * 通过后缀来识别不同的查询依据
	 * 目前支持的后缀有
	 * gt,lt,egt,elt,key,value,blur,exist
	 * @param  array   $filters 过滤器
	 * @param  integer $sid     商户id
	 * @return string           sql 语句中的 where 条件
	 */
	function parseFilters($filters) {

		$tmp = array();

		foreach ($filters as $k => $v) {
			// 过滤:非0空值; 近似开关_blur;  键值对值_value;
			$v = trim($v);
			if ((!$v && !is_numeric($v)) || strpos($k, '_blur') || strpos($k, '_value')) {
				continue;
			}

			if (strpos($k, '_exist')) {
				if ($v) {
					// 判断不为空
					$field = substr($k, 0, -6);
					$tmp[] = $field . ' <> "" and ' . $field . ' is not null';
				}
				continue;
			}

			if (strpos($k, '_key')) {
				// 有key结尾 则表示键值对,必须同步出现
				$field    = substr($k, 0, -4);
				$valueKey = $field . '_value';
				if (!empty($filters[$valueKey])) {
					if (empty($filters[$field . '_blur'])) {
						$tmp[] = $v . ' = "' . $filters[$valueKey] . '"';
					} else {
						$tmp[] = $v . ' like "%' . $filters[$valueKey] . '%"';
					}
				}
				// _key结尾的过滤掉
				continue;
			}

			// 尝试转换时间
			$tryDate = strtotime($v);

			if (strpos($k, '_egt')) {
				// egt 表示大于
				$field = substr($k, 0, -4);
				if ($tryDate) {
					// 内容为时间则比较转换后的大小
					$tmp[] = $field . ' >= "' . $tryDate . '"';
				} else {
					// 不是时间则直接比较大小
					$tmp[] = $field . ' >= "' . $v . '"';
				}
				continue;
			} elseif (strpos($k, '_elt')) {
				// elt 表示小于
				$field = substr($k, 0, -4);
				if ($tryDate) {
					// 内容为时间则比较转换后的大小
					$tmp[] = $field . ' <= "' . $tryDate . '"';
				} else {
					// 不是时间则直接比较大小
					$tmp[] = $field . ' <= "' . $v . '"';
				}
				continue;
			} elseif (strpos($k, '_gt')) {
				// gt 表示大于
				$field = substr($k, 0, -3);
				if ($tryDate) {
					// 内容为时间则比较转换后的大小
					$tmp[] = $field . ' > "' . $tryDate . '"';
				} else {
					// 不是时间则直接比较大小
					$tmp[] = $field . ' > "' . $v . '"';
				}
				continue;
			} elseif (strpos($k, '_lt')) {
				// lt 表示小于
				$field = substr($k, 0, -3);
				if ($tryDate) {
					// 内容为时间则比较转换后的大小
					$tmp[] = $field . ' < "' . $tryDate . '"';
				} else {
					// 不是时间则直接比较大小
					$tmp[] = $field . ' < "' . $v . '"';
				}
				continue;
			}

			// 键值对跟时间均过滤完毕后
			if (empty($filters[$k . '_blur'])) {
				$tmp[] = $k . ' = "' . $v . '"';
			} else {
				$tmp[] = $k . ' like "%' . $v . '%"';
			}
		}

		return $tmp ? ' WHERE ' . join(' AND ', $tmp) : '';

	}

	public function parseOrders($orders, $orderFields) {
		$tmp = array();
		foreach ($orders as $k => $v) {
			if (in_array($v['key'], $orderFields) && in_array($v['status'], array('asc', 'desc', 'ASC', 'DESC'))) {
				$tmp[] = $v['key'] . ' ' . $v['status'];
			}
		}
		return $tmp ? ' ORDER BY ' . join(',', $tmp) : '';
	}

	public function toggleActive() {
		$id = isset($_POST['id']) ? $_POST['id'] : 0;

		$res = array(
			'error' => 1,
			'msg'   => '操作失败',
		);
		if ($id) {
			$this->wheel->forbidAll();
			$sql = 'UPDATE ' . $this->wheel->baseTable . ' SET status=not status , update_time = ' . RUN_TIME . ' AND id=' . $id;
			$this->db->query($sql);
			$res['error'] = 0;
			$res['msg']   = '操作成功';
		}

		exit(json_encode($res));

	}

}