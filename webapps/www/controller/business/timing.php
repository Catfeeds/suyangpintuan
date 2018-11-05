<?php
/**
 * ZZCMS 定期处理
 * ============================================================================
 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。
 * 网站地址: http://www.lnest.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 */

class timing extends Lowxp {
	#单次解冻出价记录数
	private $frozenNum = 10000;

	function __construct() {
		$this->btnMenu = array(
			//0 => array('url' => '#!timing/index', 'name' => '控制面板'),
		);
		parent::__construct();
		$this->load->model('timing');
		$this->load->model('business');
	}

	function index() {
		$data = array();
                $data = $this->business->get(BID);    
                
                $sql =  'select count(1) as num from ###_goods_order WHERE is_robots=0 AND sid='.BID.' AND (status_common=0 || (status_common=10 && extension_code!=5 && extension_code!=6) || ( (extension_code=5 || extension_code=6) && status_lottery=10) ) and status_shipping=0 and status_pay=10 and status_order=0';
                $data['ship_num'] = $this->db->getstr($sql, "num");
                
                $sql = 'select count(1) as num from ###_refund where sid='.BID.' and type=1 and status=0 ';
                $data['refund_num'] = $this->db->getstr($sql, "num");
                
                $sql = 'select count(1) as num from ###_refund where sid='.BID.' and type=2 and status=0 ';
                $data['return_num'] = $this->db->getstr($sql, "num");
                
                $sql = 'select count(1) as num from ###_goods_rate where sid='.BID.' and state=0 ';
                $data['rate_num'] = $this->db->getstr($sql, "num");

                //待成团
                $sql = 'select count(1) as num from ###_goods_order_common where is_robots=0 AND sid='.BID.' and status=1 ';
                $data['team_num'] = $this->db->getstr($sql, "num");
		
                $this->smarty->assign('data', $data);
                $url = url('/store/index/'.BID);
                $qrcode = creat_tmp_code($url);
                $this->smarty->assign('qrcode', $qrcode);
		        $this->smarty->display('business/timing/index.html');
	}	

}