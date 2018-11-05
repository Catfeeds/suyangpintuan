<?php
/*
 * ---------------------------------------------------------------------
 * 分页类
 * @author      lowxp modified
 * ---------------------------------------------------------------------
 * 调用示例
 * $this->load->load->model('page');
 * $this->page->set_vars(array(
 *    'url'=>' title="第{num}页" onclick="quote.submit({num})" href="javascript:;"',
 *    'goto'=>'onclick="quote.goto()"',
 *    'per'=>10
 * ));
 * $r = $this->page->query(   SQL语句  )->result_array();
 * 与 $this->db->query一样调用.
 * 在模板页面中直接 echo $page;即可
 * ----------------------------------------------------------------------
 */
/*

<div class="page_global">
<a href="javascript:;">1</a>
<a href="javascript:;">2</a>
<a href="javascript:;">3</a>
<a href="javascript:;">4</a>
<a href="javascript:;" class="on">5</a>
<a href="javascript:;">6</a>
<a href="javascript:;">7</a>
<a href="javascript:;">8</a>
<a href="javascript:;" class="prv_page">上一页</a>
<a href="javascript:;" class="next_page">下一页</a>
</div>
 */
class page_model extends Lowxp_Model {
	public $pages = array(
		'var'     => 'page', #当前页参数
		'tpl'     => '
		<div class="com-page">
		    {info}
			<a class="home_page" {home}>首页</a>
			<a class="prev_page" {prev}>上一页</a>
			{prevMore}{links}{nextMore}
			<a class="next_page" {next}>下一页</a>
			<a class="end_page" {end}>尾页</a>
		</div>',
		'link'    => '<a {url}>{num}</a> ', #普通分页链接
		'current' => 'class="dq"', #当前页链接样式
		'url'     => 'href="javascript:;" title="第{num}页" rel="{num}"',
		'per'     => 15, #每页显示数
		'total'   => 0, #总记录数
		'nonce'   => 0, #当前页(起始页为1)
		'count'   => 0, #总页数
		'prev'    => 0, #下一页页码
		'next'    => 0, #上一页页码
		'home'    => 0, #首页页码
		'end'     => 0, #尾页页码
		'links'   => array(),
		'code'    => '当前无记录', #分页代码
		'info'    => '共<b>{total}</b>条记录', #分页信息
		#'info'=>'共<b id="pcount">{count}</b>页 到第 <input id="cpage" value="{nonce}"/> 页<input type="button" {goto} value="确定" />',
		#'info'=>'<i>|</i><span>到第</span><input id="cpage" value="{nonce}"/> <span>页</span><input class="gotobtn" type="button" {goto} value="确定" />',
		'goto'    => '',
	);
	#设置分页参数
	function set_vars($a) {
		if (!isset($this->pageOpt)) {
			$this->pageOpt = array();
		}

		$this->pageOpt = array_merge($this->pageOpt, $a);
	}
	#带分页查询
	function query($sql) {
		isset($this->pageOpt) || $this->pageOpt = array();
		$page                                   = isset($_POST['page']) ? $_POST['page'] : (isset($_GET['page']) ? $_GET['page'] : 1);
		$this->pages['nonce']                   = max((int) $page, 1); #设置当前页
		foreach ($this->pageOpt as $k => $v) {
			$this->pages[$k] = $v;
		}
#设置分页参数
		$limit = ($this->pages['nonce'] - 1) * $this->pages['per'] . ',' . $this->pages['per'];

		$r = $this->db->query(substr_replace($sql, 'SELECT SQL_CALC_FOUND_ROWS ', 0, 6) . ' LIMIT ' . $limit);
		$t = $this->db->query("SELECT FOUND_ROWS() AS t")->row_array();

		$this->pages['total'] = $t['t']; #设置总记录数
		$this->smarty->assign('page_total', $t['t']);
		$this->smarty->assign('page_count', $this->pages['count']);
		$this->smarty->assign('page', $this->page());
		return $r;
	}

	/**
	 * 获取当前查询参数
	 * @return string
	 */
	function getQuerystring() {
		$query = '';
		if (isset($_GET['page'])) {
			$vars = $_GET;
			unset($vars['page']);
			if (isset($vars['ajax'])) {
				unset($vars['ajax']);
			}

			$query = http_build_query($vars);
		}
		return $query;
	}

	/**
	 * hash分页请求,(#!class/method)
	 * @param $sql
	 * @param $target 锚链接
	 * @return bool|DB_Result|mysqli_result
	 */
	function hashQuery($sql, $segmets = '', $target = '') {
		$class  = Lowxp_Router::$class;
		$method = Lowxp_Router::$method;

		$segments  = Lowxp_Router::getInstance()->segments;
		$this->mod = $mod = $segments[0]=='business'?'manage':$segments[0];                
		$pre       = '';

		$queyrstring = $this->getQuerystring();
		$queyrstring = !empty($queyrstring) ? '?' . $queyrstring : '';

		$url = $class . '/' . $method . '/' . $segmets . '{num}' . $queyrstring;
		if ($mod == 'manage') {
			$url = '#!' . $url;
		} else {
			$url = url('/' . $url);
		}

		$this->set_vars(array('url' => 'href="' . $url . $target . '"'));
		return $this->query($sql);
	}

	function get_page($total) {
		isset($this->pageOpt) || $this->pageOpt = array();
		$this->pages['nonce']                   = isset($_POST['page']) ? max((int) $_POST['page'], 1) : 1; #设置当前页
		foreach ($this->pageOpt as $k => $v) {
			$this->pages[$k] = $v;
		}
#设置分页参数
		$this->pages['total'] = $total; #设置总记录数
		$t                    = $total < $this->pages['per'] ? '' : $this->page();
		$this->smarty->assign('page', $t);
	}

	function page() {
//分页
		foreach ($this->pages as $k => &$v) {
			${'_' . $k} = $v;
		}

		$_per   = empty($_per) ? 10 : $_per;
		$this->pages['count'] = $_count = (int) ceil($_total / $_per);
		//if($_count<2)return '';
		$_nonce = min($_count, max($_nonce, 1)); //当前页区间[1,count]
		/*分页区间*/
		$off  = 3;
		$off2 = $off * 2;
		if ($_count > $off2 + 1) {
			$first = $_nonce < $off + 1 ? 1 : $_nonce - $off;
			$last  = $_count - $_nonce < $off + 1 ? $_count : $_nonce + $off;
			if ($last - $first < $off2) {
				($last - $off2 > 0) ? ($first = $last - $off2) : ($last = $first + $off2);
			}

		} else {
			$first = 1;
			$last  = $_count;}
		/*--------*/
		for ($i = $first; $i <= $last; $i++) {
			array_push($_links, $i);
		}

		$links   = '';
		$links02 = array();
		foreach ($_links as $v) {
			if (isset($this->mod) && $this->mod != 'manage') {
				$_current = '';
			}

			$url = str_replace('{num}', $v, $v == $_nonce ? $_current : $_url);
			$links .= str_replace(array('{num}', '{url}'), array($v, $url), $_link);
			$links02[$v] = $url;
		}

		$disabled = $disabled02 = '';
		if (isset($this->mod) && $this->mod == 'manage') {
			$disabled   = ' id="p-disable"';
			$disabled02 = ' id="p-disable-02"';
		}

		$_prev = $_nonce == 1 ? '' : $_nonce - 1; #上一页
		$_next = $_nonce == $_count ? '' : $_nonce + 1; #下一页
		$prev  = $_prev == '' ? $disabled : str_replace('{num}', $_prev, $_url);
		$next  = $_next == '' ? $disabled : str_replace('{num}', $_next, $_url);

		$_home = $_nonce == 1 ? '' : 1; #首页
		$_end  = $_nonce == $_count ? '' : $_count; #尾页
		$home  = $_home == '' ? $disabled02 : str_replace('{num}', $_home, $_url);
		$end   = $_end == '' ? $disabled02 : str_replace('{num}', $_end, $_url);

		$moreNext = $_count - $last > 1 ? '<span>...</span>' : '';
		$morePrev = $first > 2 ? '<span>...</span>' : '';

		$first = $first > 1 ? str_replace(array('{url}', '{num}'), array(str_replace('{num}', 1, $_url), 1), $_link) . $morePrev : '';
		$last  = $last < $_count ? $moreNext . str_replace(array('{url}', '{num}'), array(str_replace('{num}', $_count, $_url), $_count), $_link) : '';

		#当前:{nonce}/{count} 共{total}条信息
		$info  = str_replace(array('{nonce}', '{count}', '{total}', '{goto}'), array($_nonce, $_count, $_total, $_goto), $_info);
		$_code = str_replace(array('{links}', '{prev}', '{next}', '{info}', '{prevMore}', '{nextMore}', '{home}', '{end}'), array($links, $prev, $next, $info, $first, $last, $home, $end), $_tpl);

		if (isset($this->mod) && $this->mod != 'manage') {
			$_code = array(
				'home'     => $home, #第一页链接
				'end'      => $end, #尾页链接
				'prev'     => $prev, #上一页链接
				'next'     => $next, #下一页链接
				'links'    => $links02, #数字链接数组
				'total'    => $_total, #总条数
				'count'    => $_count, #总页数
				'nonce'    => $_nonce, #当前页
				'moreprev' => $moreNext, #更多上页
				'morenext' => $moreNext, #更多下页
			);
		}

		return $_code;
	}
}