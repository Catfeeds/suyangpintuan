<?php
/**
 * ZZCMS 积分管理
 * ============================================================================
 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。
 * 网站地址: http://www.lnest.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 */
class score extends Lowxp {

	// 注意标签是从0 开始 而id是从1开始
	public $btnMenu = array(
		0 => array('url' => '#!score/index?type=1', 'name' => '签到积分'),
		1 => array('url' => '#!score/index?type=2', 'name' => '注册积分'),
		2 => array('url' => '#!score/index?type=3', 'name' => '分享积分'),
		//3 => array('url' => '#!score/index?type=4', 'name' => '充值积分'),
		4 => array('url' => '#!score/index?type=5', 'name' => '拼团积分'),
		//5 => array('url' => '#!score/index?type=6', 'name' => '积分兑换'),
	);

	public function __construct() {
		parent::__construct();
		$this->load->model('score');
		//开关
		if(!$this->score->power){
			$this->tip("您没有安装积分插件", array('inIframe' => false, 'type' => 1));
			exit;
		}
	}

	public function index() {

		$type = empty($_GET['type']) ? 1 : $_GET['type'];
		// ajax 处理
		if (!empty($_POST['post'])) {
			$res = $this->score->save($_POST['post']);
			exit(json_encode($res));
		}

		$this->smarty->assign('btnNo', $type - 1);

		// 规则跟分类按钮是一一对应的 但是规则id 是从1开始的
		$row = $this->score->getRow($type);

		$type = $this->score->socreTypes[$type];
		$tmpl = 'manage/score/' . $type . '.html';
		$this->smarty->assign('row', $row);
		$this->smarty->display($tmpl);
	}

	public function log($page = 1) {
		$fields = array(
				'id'     => 'ID',
				'mid'    => '用户id',
				'type'   => '类型',
				'amount' => '数量',
				'remark' => '说明',
				'c_time' => '创建时间',
		);
	
		$orders = empty($_GET['orders']) ? array(
				array('key' => 'c_time', 'desc' => 1),
				array('key' => 'id', 'desc' => 1),
				array('key' => 'id'),
		) : $_GET['orders'];
	
		$conds     = $this->getCondtions($fields, $orders);
		$condtions = $conds['where'];
		$orderby   = $conds['order'];
		
		if (!empty($_GET['filters']['mobile'][1])) {
			if ('cs' == $_GET['filters']['mobile'][0]) {
				$tmpWhere = ' WHERE mobile like "%' . $_GET['filters']['mobile'][1] . '%"';
			} else {
				$tmpWhere = ' WHERE mobile="' . $_GET['filters']['mobile'][1] . '"';
			}
	
			$mid = $this->db->getStr('SELECT mid FROM `###_member` ' . $tmpWhere . ' LIMIT 1', 'mid') ?: 0;
			$condtions .= ($condtions ? ' AND' : ' WHERE') . ' mid=' . $mid;
		}
	
		if (!empty($_GET['filters']['username'][1])) {
			if ('cs' == $_GET['filters']['username'][0]) {
				$tmpWhere = ' WHERE username like "%' . $_GET['filters']['username'][1] . '%"';
			} else {
				$tmpWhere = ' WHERE username="' . $_GET['filters']['username'][1] . '"';
			}
	
			$mid = $this->db->getStr('SELECT mid FROM `###_member` ' . $tmpWhere . ' LIMIT 1', 'mid') ?: 0;
			$condtions .= ($condtions ? ' AND' : ' WHERE') . ' mid=' . $mid;
		}
		$sql = "SELECT * FROM `###_score_log` " . $condtions . $orderby;
		
		$excel = !empty($_GET['excel']);
		if ($excel) {
			$data['list'] = $this->db->select($sql);
		} else {
			$this->load->model('page');
			$_GET['page'] = intval($page);
			$this->page->set_vars(array('per' => (int) $this->common['page_listrows']));
			$data['list'] = $this->page->hashQuery($sql)->result_array();
		}
	
		$data['score_types'] = $typeNames = $this->score->scoreTypes;
		
		if ($data['list']) {
			$data['list'] = $this->db->ljoin($data['list'], 'member', 'mid,username,mobile', 'mid', 'mid');
	
			foreach ($data['list'] as $k => $v) {
				$data['list'][$k]['score_type'] = $typeNames[$v['type']];
			}
		}
	
		//导出
		if ($excel) {
			return $data['list'];
		}
	
		$unit_score = L('unit_score');
		$this->smarty->assign('btnMenu', array(
				0 => array('url' => '#!score/log', 'name' => $unit_score . '记录'),
				1 => array('url' => '#!score/index?type=1', 'name' => $unit_score . '规则'),
		));
	
		$this->smarty->assign('btnRig', array(
				array('url' => 'javascript:;', 'name' => '导出', 'className' => 'BtnGreen e2-score-export'),
		));
	
		$this->smarty->assign('data', $data);
		$this->smarty->display('manage/score/logs.html');
	}
	
	function export() {
		$this->load->model('share');
	
		ini_set('memory_limit', '256M');
		$_GET['excel'] = 1;
	
		$data = $this->log();
	
		$list = array();
		foreach ($data as $k => $v) {
			$v['c_time'] = date('Y-m-d H:i:s', $v['c_time']);
	
			$list[] = $v;
		}
	
		$unit_score = L('unit_score');
		$fields     = array(
				'id'       => '编号',
				'type'     => '类型',
				'mid'      => '用户id',
				'mobile'   => '手机号',
				'username' => '用户名',
				'amount'   => '数量',
				'remark'   => '说明',
				'c_time'   => '创建时间',
		);
	
		$this->share->SetExcelHeader($unit_score . '列表-' . date('Y-m-d H:i:s'), $unit_score . '列表');
	
		$this->share->SetExcelBody($fields, $list);
	
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
		return $con ? ' WHERE ' . join(' AND ', $con) : '';
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