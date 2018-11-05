<?php
/**
 * ZZCMS 站点统计
 * ============================================================================
 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。
 * 网站地址: http://www.lnest.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 */

class statistic extends Lowxp{

    function index(){
        $this->smarty->assign('title','数据统计');
        //商家总数
        $total['bis'] = $this->db->getstr("select count(1) as num from ###_business where status=1","num");

        //商品总数
        $total['goods'] = $this->db->getstr("select count(1) as num from ###_goods ","num");

        //平台商品
        $total['goods_sys'] = $this->db->getstr("select count(1) as num from ###_goods where sid=0","num");

        //商家商品
        $total['goods_bis'] = $this->db->getstr("select count(1) as num from ###_goods where sid>0","num");

        $sys = $this->db->select("select status_order,sum(order_amount) as num from ###_goods_order where status_pay>0 and sid=0 and is_robots=0 group by status_order");
        $sys_status = array_combine(array_column($sys,"status_order"),array_column($sys,"num"));

        //平台下单金额
        $total['order_sys'] = price_format($sys_status[0]+$sys_status[10]);
        //平台退款金额
        $total['order_sys_refund'] = price_format($sys_status[3]);

        $bns = $this->db->select("select status_order,sum(order_amount) as num from ###_goods_order where status_pay>0 and sid>0  and is_robots=0 group by status_order");
        $bns_status = array_combine(array_column($bns,"status_order"),array_column($bns,"num"));

        //商家下单金额
        $total['order_bns'] = price_format($bns_status[0]+$bns_status[10]);
        //商家退款金额
        $total['order_bns_refund'] = price_format($bns_status[3]);

        $end_time   = strtotime(date("Y-m-d",RUN_TIME));
        $start_time = $end_time-7*24*3600;

        //7天内店铺销售排行
        $store_sort = $this->db->select("select sid,sum(order_amount) as num from ###_goods_order where status_pay>0 and sid>0 and c_time>{$start_time} and c_time<={$end_time} and is_robots=0 group by sid order by num desc limit 30");
        $store_sort = $this->db->lJoin($store_sort,"business","id,name","sid","id","b_");

        //7天内商品销售排行
        $goods_sort = $this->db->select("select goods_name,sum(buy_num) as num from ###_goods_order_item where c_time>{$start_time} and c_time<={$end_time}  group by good_id  order by num desc limit 30");

        $this->smarty->assign("total",$total);
        $this->smarty->assign("store_sort",$store_sort);
        $this->smarty->assign("goods_sort",$goods_sort);

        /*//总访问量
        $view = $this->db->get("SELECT sum(view_num) as total_view FROM statistic_view_info");
        $total_view = $view['total_view'];

        $this->smarty->assign('total_view',$total_view);

        $user = $this->db->get("SELECT * FROM m_user WHERE uid=".UID);
        $lastIp = $user['ip'];
        $this->smarty->assign('userip',$lastIp);

        $system = array(
            'type'=>php_uname('s'),
            'version'=>php_uname('r'),
            'phpversion'=>PHP_VERSION,

        );
        $this->smarty->assign('system',$system);

        $this->days7();

        $this->browser();

        $this->platform();

        $this->zone_city();*/

        #$res = $this->getIPLoc_sina('42.120.7.232');
        #$res = $this->getIPLoc_sina('61.172.240.228');
        #echo '<pre>';print_r($res);echo '</pre>';
        #echo '<pre>';print_r($days);echo '</pre>';
        #echo '<pre>';print_r($days);echo '</pre>';
        #echo '<pre>';print_r($days);echo '</pre>';

        $this->smarty->display('manage/statistic/index.html');
    }

    /**
     * 近7天访问统计
     */
    function days7(){
        //查近7天每日访问.
        $day7 = date('Y-m-d',strtotime('-6 days'));
        $rows = $this->db->select("SELECT * FROM statistic_view_info WHERE date>'".$day7."'");
        $day7rows = array();
        foreach($rows as $val)$day7rows[$val['date']] = $val;

        #$days7 = array();
        $sevenData = array(
            'days'=>array(),
            'data'=>array()
        );
        for($i=6;$i>-1;$i--){
            $timestamp = strtotime('-'.$i.' days');
            $day = date('Y-m-d',$timestamp);
            $sevenData['days'][] = date('d',$timestamp);

            if(!isset($day7rows[$day])){
                $sevenData['data'][] = 0;
            }else{
                $sevenData['data'][] = $day7rows[$day]['view_num'];
            }
        }
        $sevenDays = json_encode($sevenData['days']);
        $sevenView = json_encode($sevenData['data']);
        $this->smarty->assign('seven_days',$sevenDays);
        $this->smarty->assign('seven_view',$sevenView);
    }

    /**
     * 浏览器分布
     */
    function browser(){
        $rows = $this->db->select("SELECT num,browser FROM statistic_view_browser");
        $browsers = array();
        $nums = array();
        $browserData = array();
        foreach($rows as $val){
            $browsers[] = $val['browser'];
            $nums[] = $val['num'];
            $browserData[] = array('name'=>$val['browser'],'value'=>$val['num']);
        }

        #echo '<pre>';print_r($rows);echo '</pre>';

        $this->smarty->assign('browsers_type',json_encode($browsers));
        $this->smarty->assign('browsers_data',json_encode($browserData));
    }

    /**
     * 浏览器分布
     */
    function platform(){
        $rows = $this->db->select("SELECT num,platform FROM statistic_view_platform");
        $platforms = array();
        #$nums = array();
        $platformData = array();
        foreach($rows as $val){
            $platforms[] = $val['platform'];
            #$nums[] = $val['num'];
            $platformData[] = array('name'=>$val['platform'],'value'=>$val['num']);
        }

        $this->smarty->assign('platforms_type',json_encode($platforms));
        $this->smarty->assign('platforms_data',json_encode($platformData));
    }

    function zone_city(){
        $rows = $this->db->select("SELECT * FROM statistic_view_city ORDER BY num DESC LIMIT 0,15");

        $citys = array();
        #$nums = array();
        $cityData = array();
        foreach($rows as $val){
            $citys[] = $val['city'];
            $cityData[] = array('name'=>$val['city'],'value'=>$val['num']);
        }

        $this->smarty->assign('citys',json_encode($citys));
        $this->smarty->assign('citys_data',json_encode($cityData));
    }

    /**
     * 根据新浪IP查询接口获取IP所在地
     * @param $queryIP
     * @return string
     */
    private function getIPLoc_sina($queryIP){
        $url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip='.$queryIP;
        $ch = curl_init($url);
        //curl_setopt($ch,CURLOPT_ENCODING ,'utf8');
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
        $location = curl_exec($ch);
        $location = json_decode($location,true);

        curl_close($ch);

        return $location;
    }


    /**
     * 到账金额
     * @param $queryIP
     * @return string
     */
    function pay_log($page = 1){
        $this->btnMenu = array(0 => array('url' => '#!refund/log', 'name' => '支付记录'));
        $this->smarty->assign('btnMenu', isset($this->btnMenu) ? $this->btnMenu : array());

        ${$_REQUEST['k']} = $_REQUEST['q'];
        $start_time = !empty($_GET['start_time']) ? strtotime($_GET['start_time']) : 0;
        $end_time = !empty($_GET['end_time']) ? strtotime($_GET['end_time']) : 0;

        $where = " 1 ";

        //支付ID
        if ($_GET['log_id'] > 0) {
            $where .= " AND log_id=" . intval($_GET['log_id']);
        }
        //订单ID
        if ($_GET['order_id'] > 0) {
            $where .= " AND order_id=" . intval($_GET['order_id']);
        }
        //订单号
        if($_GET['order_sn']){
            $order_id = $this->db->getstr("select id from ###_goods_order where order_sn='{$_GET['order_sn']}'");
            if($order_id)$where .= " AND order_id=" . $order_id;
        }
        //支付单号
        if ($_GET['out_trade_no']) {
            $where .= " AND out_trade_no='" . trim($_GET['out_trade_no'])."'";
        }
        //支付类型
        if ($_GET['order_type'] > 0 || $_GET['order_type'] == '0') {
            $where .= " AND order_type=" . intval($_GET['order_type']);
        }
        //是否支付
        if ($_GET['is_paid'] > 0 || $_GET['is_paid'] == '0') {
            $where .= " AND is_paid=" . intval($_GET['is_paid']);
        }

        $sort = !empty($_GET['sort']) ? trim($_GET['sort']) : 'log_id';

        $order = !empty($_GET['sort']) ? trim($_GET['order']) : 'DESC';

        #分页

        $this->load->model('page');

        $_GET['page'] = intval($page);

        $this->page->set_vars(array('per' => (int)$this->common['page_listrows']));

        #数据集

        $sql = "SELECT * FROM ###_pay_log WHERE $where ORDER BY $sort $order";

        $list = $this->page->hashQuery($sql)->result_array();

        $list = $this->db->lJoin($list, "goods_order", "id,order_sn", "order_id", "id", "order_");

        $pay_type = array(
            PAY_ORDER   => "订单支付",
            PAY_SURPLUS => "充值",
            PAY_BS       => "商家认证支付",
            PAY_PRE      => "定金支付",
            PAY_END      => "尾款支付",
        );

        foreach($list as $k=>$v){
            $list[$k]['pay_type'] = $pay_type[$v['order_type']];
        }
        //统计已支付总额 和 待支付总额
        $tmp = $this->db->select("select is_paid,sum(order_amount) as num from ###_pay_log group by is_paid");
        $total = array_combine(array_column($tmp,"is_paid"),array_column($tmp,"num"));
        $this->smarty->assign('pay_type', $pay_type);
        $this->smarty->assign('total', $total);
        $this->smarty->assign('list', $list);
        $this->smarty->display('manage/statistic/pay_log.html');
    }

    /**
     * 退款金额
     * @param $queryIP
     * @return string
     */
    function refund_log($page = 1){
        $this->btnMenu = array(0 => array('url' => '#!refund/log', 'name' => '退款记录'));
        $this->smarty->assign('btnMenu', isset($this->btnMenu) ? $this->btnMenu : array());

        $start_time = !empty($_GET['start_time']) ? strtotime($_GET['start_time']) : 0;
        $end_time = !empty($_GET['end_time']) ? strtotime($_GET['end_time']) : 0;

        $where = " 1 ";
        if ($start_time > 0) {
            $where .= " AND c_time>=" . $start_time;
        }

        if ($end_time > 0) {
            $where .= " AND c_time<=" . $end_time;
        }
        //退款状态
        if ($_GET['is_refund'] > 0 || $_GET['is_refund'] == '0') {
            $where .= " AND is_refund=" . intval($_GET['is_refund']);
        }
        //退款ID
        if ($_GET['id'] > 0) {
            $where .= " AND id=" . $_GET['id'];
        }
        //订单ID
        if ($_GET['order_id'] > 0) {
            $where .= " AND order_id=" . $_GET['order_id'];
        }
        //订单号
        if($_GET['order_sn']){
            $order_id = $this->db->getstr("select id from ###_goods_order where order_sn='{$_GET['order_sn']}'");
            if($order_id)$where .= " AND order_id=" . $order_id;
        }
        //支付单号
        if ($_GET['out_trade_no']) {
            $where .= " AND out_trade_no='" . $_GET['out_trade_no']."'";
        }
        //退款单号
        if ($_GET['out_refund_no']) {
            $where .= " AND out_refund_no='" . $_GET['out_refund_no']."'";
        }

        $sort = !empty($_GET['sort']) ? trim($_GET['sort']) : 'id';

        $order = !empty($_GET['sort']) ? trim($_GET['order']) : 'DESC';

        #分页

        $this->load->model('page');

        $_GET['page'] = intval($page);

        $this->page->set_vars(array('per' => (int)$this->common['page_listrows']));

        #数据集

        $sql = "SELECT * FROM ###_refund_log WHERE $where ORDER BY $sort $order";

        $list = $this->page->hashQuery($sql)->result_array();

        $list = $this->db->lJoin($list, "goods_order", "id,order_sn", "order_id", "id", "order_");

        //统计退款金额
        $tmp = $this->db->select("select is_refund,sum(order_amount) as num from ###_refund_log group by is_refund");
        $total = array_combine(array_column($tmp,"is_refund"),array_column($tmp,"num"));
        $this->smarty->assign('list', $list);
        $this->smarty->assign('total', $total);

        $this->smarty->display('manage/statistic/refund_log.html');
    }
}