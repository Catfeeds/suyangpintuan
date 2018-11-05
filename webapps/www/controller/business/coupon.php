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

class coupon extends Lowxp {

	public function __construct() {
		$this->btnMenu = array(
			0 => array('url' => '#!coupon/index', 'name' => '优惠券管理'),
			1 => array('url' => '#!coupon/edit', 'name' => '添加优惠券'),
			2 => array('url' => '#!coupon/logs', 'name' => '优惠券记录'),
		);

		parent::__construct();

		$btnRig = array(
			//0 => array('url' => '#!member/index', 'name' => '在会员管理下方手工发放'),
		);

		$this->smarty->assign('btnRig', $btnRig);
		$this->load->model('coupon');
	}

	#列表

	public function index($page = 1) {
		$_GET['page'] = $page;
		$sql          = 'SELECT * FROM ###_coupon';

		// 所有被筛选的字段默认值
		$filterFields = array(
			'id',
			'title',
			'title_blur',
			'status',
			'amount_egt',
			'amount_elt',
			'need_amount_egt',
			'need_amount_elt',
			'start_time_egt',
			'start_time_elt',
			'end_time_egt',
			'end_time_elt',
			'target_exist',
			'uniqued',
			'exchange',
			'paid',
			'share',
		);

		$filters = array();
		foreach ($filterFields as $v) {
			$filters[$v] = isset($_GET['filters'][$v]) ? $_GET['filters'][$v] : '';
		}

		// 设置默认模糊查找示例
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
			'start_time'  => '有效期开始',
			'end_time'    => '有效期结束',
			'status'      => '状态',
			'amount'      => '抵扣金额',
			'need_amount' => '满减金额',
			'stock'       => '剩余数量',
			'used'        => '使用数量',
			'sended'      => '发放数量',
		);

		$orderFields = array_keys($orderTypes);
		$sql .= $this->parseOrders($orders, $orderFields);

		$this->load->model('page');
		$list = $this->page->hashQuery($sql)->result_array();

		$this->smarty->assign('status', $this->coupon->baseStatus);
		$this->smarty->assign('orderTypes', $orderTypes);
		$this->smarty->assign('filters', $filters);
		$this->smarty->assign('orders', $orders);
		$this->smarty->assign('list', $list);
		$this->smarty->assign('btnNo', 0);
		$this->smarty->display('business/coupon/list.html');

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
			$row = $this->db->get("SELECT * FROM " . $this->coupon->baseTable . " WHERE id=" . $id);
		} else {
			$this->smarty->assign('btnNo', 1);
		}

		$this->smarty->assign('row', $row);
		$this->smarty->display('business/coupon/edit.html');

	}

	public function editHandle($post) {
                $post['sid'] = BID;
		$res = $this->coupon->save($post);
		if (isset($res['error']) && $res['error'] == 0) {
			$this->tip($res['msg'], array('inIframe' => true));
			$this->exeJs("parent.window.location='/business#!coupon/index';");
		} else {
			$this->tip($res['msg'], array('inIframe' => true, 'type' => 2));
		}
		exit;
	}

	public function logs($page = 1) {
		$_GET['page'] = $page;
		$sql          = 'SELECT * FROM ###_coupon_log';

		// 所有被筛选的字段默认值
		$filterFields = array(
			'coupon_id',
			'mid',
			'status',
			'share',
			'send_type',
			'create_time_egt',
			'create_time_elt',
			'use_time_egt',
			'use_time_elt',
		);

		$filters = array();
		foreach ($filterFields as $v) {
			$filters[$v] = isset($_GET['filters'][$v]) ? $_GET['filters'][$v] : '';
		}

		$sql .= $this->parseFilters($filters);
                
		// 默认排序规则
		$orders = empty($_GET['orders']) ? array(
			array('key' => 'create_time', 'status' => 'desc'),
			array('key' => 'mid', 'status' => 'asc'),
			array('key' => 'coupon_id', 'status' => 'desc'),
		) : $_GET['orders'];

		$orderTypes = array(
			'create_time' => '发放时间',
			'use_time'    => '使用时间',
			'mid'         => '会员id',
			'coupon_id'   => '优惠券id',
			'send_type'   => '获取方式',
			'status'      => '状态',
		);

		$orderFields = array_keys($orderTypes);
		$sql .= $this->parseOrders($orders, $orderFields);

		$this->load->model('page');

		$list = $this->page->hashQuery($sql)->result_array();

		$list = $this->db->lJoin($list, 'coupon', 'id,title,need_amount,amount,start_time,end_time', 'coupon_id', 'id', 'b_');

		$this->smarty->assign('shareTypes', $this->coupon->shareTypes);
		$this->smarty->assign('sendTypes', $this->coupon->sendTypes);
		$this->smarty->assign('status', $this->coupon->logStatus);
		$this->smarty->assign('orderTypes', $orderTypes);
		$this->smarty->assign('filters', $filters);
		$this->smarty->assign('orders', $orders);
		$this->smarty->assign('list', $list);
		$this->smarty->assign('btnNo', 2);
		$this->smarty->display('business/coupon/logs.html');
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

		return $tmp ? ' WHERE sid='.BID . ' AND ' .join(' AND ', $tmp) : ' WHERE sid='.BID;

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

	/**
	 * 优惠券编辑界面商品分类zTree列表数据源
	 * @return json
	 */
	public function goodscat() {

		$sql  = "SELECT id, parentid as pId,catname as name FROM ###_goods_cat ORDER BY listorder ASC,id ASC";
		$list = $this->db->select($sql);

		// 所有分类标签
		array_unshift($list, array('id' => 0, 'pId' => 0, 'name' => '可选品类'));

		$target = empty($_GET['target']) ? array() : explode(',', $_GET['target']);
		if ($target) {
			ksort($target);
			foreach ($list as $k => $v) {
				foreach ($target as $m => $n) {
					if ($v['id'] == $n) {
						$list[$k]['checked'] = true;
						unset($target[$m]);
						break;
					}
				}
			}
		}

		$res = array(
			'error' => 0,
			'data'  => array(
				'list' => $list,
			),
		);

		exit(json_encode($res));
	}

	/**
	 * @param  integer $page  第几页
	 * @return string         返回可以被ajax get获取的标准页面
	 */
	public function selection($page = 1) {
		$_GET['page'] = $page;
		$sql          = 'SELECT * FROM ###_coupon';

		// 所有被筛选的字段默认值
		$filterFields = array(
			'id',
			'title',
			'title_blur',
			'amount_egt',
			'amount_elt',
			'need_amount_egt',
			'need_amount_elt',
			'start_time_egt',
			'start_time_elt',
			'end_time_egt',
			'end_time_elt',
			'target_exist',
			'uniqued',
			'exchange',
			'paid',
			'share',
		);

		$filters = array();
		foreach ($filterFields as $v) {
			$filters[$v] = isset($_GET['filters'][$v]) ? $_GET['filters'][$v] : '';
		}

		// 设置默认值 本例为可用优惠券才可以被选择
		$filters['status']   = 1;
		$filters['stock_gt'] = 0;

		$sql .= $this->parseFilters($filters);

		// 设置默认模糊查找示例
		$filters['title_blur'] = isset($_GET['filters']['title_blur']) ? $_GET['filters']['title_blur'] : 1;

		// 默认排序规则
		$orders = empty($_GET['orders']) ? array(
			array('key' => 'status', 'status' => 'desc'),
			array('key' => 'id', 'status' => 'desc'),
			array('key' => 'end_time', 'status' => 'desc'),
		) : $_GET['orders'];

		$orderTypes = array(
			'id'          => 'id',
			'title'       => '名称',
			'start_time'  => '有效期开始',
			'end_time'    => '有效期结束',
			'status'      => '状态',
			'amount'      => '抵扣金额',
			'need_amount' => '满减金额',
			'stock'       => '剩余数量',
			'used'        => '使用数量',
			'sended'      => '发放数量',
		);

		$orderFields = array_keys($orderTypes);
		$sql .= $this->parseOrders($orders, $orderFields);
		$this->load->model('page');
		$this->page->set_vars(array(
			'per' => (int) $this->common['page_listrows'],
			'url' => 'href="javascript:;" title="第{num}页" data-page="{num}"',
		));
		$list = $this->page->query($sql)->result_array();

		$this->smarty->assign('status', $this->coupon->baseStatus);
		$this->smarty->assign('orderTypes', $orderTypes);
		$this->smarty->assign('filters', $filters);
		$this->smarty->assign('orders', $orders);
		$this->smarty->assign('list', $list);
		$this->smarty->display('business/coupon/selection.html');
	}

	/**
	 * 生成扫码抢优惠券
	 * @return void
	 */
	public function qrcode() {
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
		$coupon = $this->coupon->getRow($id);
		$qrcode = '/wechat/build_qrcode?data='.url('/store/scanCouponHandle/'.$id);
		$this->smarty->assign('qrcode', $qrcode);
		$this->smarty->assign('coupon', $coupon);
		$this->smarty->display('business/coupon/qrcode.html');

	}

}