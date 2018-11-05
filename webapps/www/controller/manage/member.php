<?php

/**
 * Name 会员管理
 * Class member_adm

 */
class member extends Lowxp
{

    function __construct()
    {

        #按钮

        $this->btnMenu = array(

            0 => array('url' => '#!member/index', 'name' => '会员管理'),

            1 => array('url' => '#!member/edit?com=xshow|添加会员', 'name' => '添加会员'),

            2 => array('url' => '#!robots/add?com=xshow|快速开团', 'name' => '快速开团'),

            //2 => array('url' => '#!member/account_log', 'name' => '帐户明细'),

            //3 => array('url' => '#!member/index?page=&k=username&q=&rank_id=&online=&status=&verify_mobile=&start_time=&end_time=&sortby=a.ivt_count&sortorder=DESC', 'name' => '推荐排行表'),

        );

        parent::__construct();

        #加载

        $this->load->model('member');

    }

    function test()
    {

        //$this->db->delete('member',array('mid'=>241));die('22');

        //$this->db->update('member',array('openid'=>''),array('mid'=>1));die('3333');

        $sql = "select * from ###_member where mid=1";

        $row = $this->db->get($sql);

        print_r($row);
        die;

    }

    function index($page = 1)
    {

        #检索

        $conds = $this->getFilterCond();

        $condition = $conds['where'];

        $orderby = $conds['order'];

        #分页

        $this->load->model('page');

        $_GET['page'] = intval($page);

        $this->page->set_vars(array(

            'per' => (int)$this->common['page_listrows'],

        ));

        /*$sql = "SELECT DISTINCT a.mid,a.*,b.c_time AS stime,IFNULL(c.username,'-') AS ivt_name FROM ###_member AS a " .

            "LEFT JOIN " . $this->session->baseTable . " AS b ON b.mid=a.mid " .

                "LEFT JOIN ###_member AS c ON c.mid=a.ivt_id " .

            ($condition != '' ? " WHERE $condition" : '') . $orderby;
        $sql = "SELECT DISTINCT a.mid,a.*,b.*,IFNULL(c.username,'-') AS ivt_name FROM ###_member AS a " .

            "LEFT JOIN ###_member_detail AS b ON b.mid=a.mid " .

            "LEFT JOIN ###_member AS c ON c.mid=a.ivt_id " .

            ($condition != '' ? " WHERE $condition" : '') . $orderby;*/
        $sql = "SELECT DISTINCT a.mid,a.* FROM ###_member AS a " .
            ($condition != '' ? " WHERE $condition" : '') . $orderby;
        $list = $this->page->hashQuery($sql)->result_array();
        $list = $this->db->lJoin($list,"member_detail","mid,photo,nickname,c_time","mid","mid");
        $list = $this->db->lJoin($list,"score_total","mid,total_left","mid","mid","sc_");
        $list = $this->db->lJoin($list,"member","mid,username","ivt_id","mid","ivt_");

        foreach ($list as $k => $v) {

            #在线离线判断

            if ($v['stime'] && (time() - $v['stime']) < $this->session->expire) {

                $v['online'] = 1;

            } else {

                $v['online'] = 0;

            }

            $list[$k] = $v;

//			$re = $this->db->select("select level,sum(commission) as num from ###_commission where mid={$v['mid']} group by level");
//			if ($re) {
//				foreach ($re as $_v) {
//					$list[$k]["commss"][$_v['level']] = $_v['num'];
//				}
//			}

        }

        #对搜索结果进行批量操作

        $batch = isset($_GET['batch']) ? trim($_GET['batch']) : '';

        $tpl = '';

        if ($batch == 'sms' || $batch == 'mail') {

            if ($batch == 'sms') {
                $tpl = $_GET['smstpl'];
            } elseif ($batch == 'mail') {
                $tpl = $_GET['mailtpl'];
            }

            $rowTpl = $this->db->get("SELECT * FROM ###_templates WHERE template_code='" . $tpl . "' AND status=1 AND is_system=0");

            if ($rowTpl) {

                $mlist = $this->db->select($sql);

                foreach ($mlist as $v) {

                    $data = array(

                        'mid' => $v['mid'],

                        'content' => $rowTpl['template_content'],

                        'type' => $batch,

                        'template_code' => $tpl,

                        'staus' => 0,

                        'add_time' => time(),

                    );

                    $this->db->save('send', $data);

                }

            }

        }

        #等级列表

        $sql = "SELECT * FROM ###_member_rank ORDER BY id";

        $this->smarty->assign('ranklist', $this->db->select($sql));

        #自定义短信邮件模板

        $sql = "SELECT * FROM ###_templates WHERE is_system=0 AND status=1";

        $this->smarty->assign('template_list', $this->db->select($sql));

        unset($_GET['page']);

        // fan 2016-04-22 start
        // 解决前台搜索数组index错误
        if (empty($_GET['rank_id'])) {
            $_GET['rank_id'] = null;
        }
        if (empty($_GET['status'])) {
            $_GET['status'] = null;
        }
        if (empty($_GET['verify_mobile'])) {
            $_GET['verify_mobile'] = null;
        }

        if (empty($_GET['sortby'])) {
            $_GET['sortby'] = null;
        }

        if (empty($_GET['sortorder'])) {
            $_GET['sortorder'] = null;
        }

        // fan 2016-04-22 end

        $this->smarty->assign($_GET);

        $this->smarty->assign('list', $list);

        $this->smarty->display('manage/member/list.html');

    }

    /**
     * ajax统计会员人数

     */

    function online()
    {

        $act = isset($_POST['act']) ? trim($_POST['act']) : '';

        #自动清除过期的session

        $this->db->delete($this->session->baseTable, "(mid>0 AND c_time<" . (time() - $this->session->expire) . ") OR (mid=0 AND c_time<" . (time() - $this->session->expire2) . ")");

        #统计并发在线人数

        $expire = 10; #并发在线时间段

        $sql = "SELECT COUNT(sesskey) AS online FROM " . $this->session->baseTable . " WHERE c_time>" . (time() - $expire);

        $online = $this->db->getstr($sql);

        if ($act == 'bf') {
            exit($online);
        }

        #统计在线人数

        $sql = "SELECT COUNT(sesskey) AS online_nomid FROM " . $this->session->baseTable . " WHERE mid=0 AND c_time>" . (time() - $this->session->expire2);

        $online_nomid = $this->db->getstr($sql);

        $sql = "SELECT COUNT(sesskey) AS online_mid FROM " . $this->session->baseTable . " WHERE mid>0 AND c_time>" . (time() - $this->session->expire);

        $online_mid = $this->db->getstr($sql);

        $str = "在线游客(<b class='c-orange'>$online_nomid</b>) 在线会员(<b class='c-orange'>$online_mid</b>) " . $expire . "秒内在线(<b class='c-orange' id='online_BF'>$online</b>) <a href='javascript:member.online();'>更新</a>";

        exit($str);

    }

    /**
     * 编辑会员

     */

    function edit($mid = '')
    {
        $mid = intval($mid);
        //提交
        if (isset($_POST['Submit'])) {
            $post = $_POST['post'];
            //获取邀请人
            if (!empty($post['ivt_id'])) {
                // 有设置邀请人id时
                $ivtId = $post['ivt_id'];
                if (floor($ivtId) != $ivtId) {
                    $this->tip('对不起, 邀请人ID只能为正整数', array('inIframe' => true, 'type' => 2));
                    die;
                }
                // 获取本人信息
                $member = $this->member->check_username($post['mid'], 'mid');
                // 仅当新输入的邀请人id 与原有邀请人id不同时 执行变动邀请人id操作
                if ($member['ivt_id'] != $ivtId) {
                    // 获取新推荐人信息
                    $ivt = $this->member->check_username($ivtId, 'mid');
                    if (!$ivt) {
                        $this->tip('对不起, 您输入邀请人ID不存在', array('inIframe' => true, 'type' => 2));
                        die;
                    }
                    if ($ivt['ivt_id'] == $post['mid']) {
                        // 避免新推荐人的推荐人是用户自己
                        $this->tip('对不起, 您输入的邀请人的邀请人, 不能是用户本人.请务必避免发生循环邀请.', array('inIframe' => true, 'type' => 2));
                        die;
                    }
                    if ($member['ivt_id']) {
                        // 有旧上家则 修改旧上家的推荐人计数
                        $this->db->update('member', 'ivt_count=ivt_count-1', 'mid=' . $member['ivt_id']);
                    }
                    // 修改新上家的推荐人计数
                    $this->db->update('member', 'ivt_count=ivt_count+1', 'mid=' . $ivtId);
                }
            }
            if (empty($post['mid'])) {
                $res = $this->member->create_user($post);
            } else {
                $res = $this->member->update_user($post);
            }
            if (isset($res['code']) && $res['code'] == 0) {
                $this->tip($res['message'], array('inIframe' => true));
                $this->exeJs("parent.com.xhide();parent.main.refresh()");
            } else {
                $this->tip($res['message'], array('inIframe' => true, 'type' => 1));
            }
            die;

        }
        if ($mid) {
            $row = $this->member->member_info($mid);
        }

        #非特殊等级列表
//		$sql = "SELECT * FROM ###_member_rank ORDER BY id";
        //
        //		$this->smarty->assign('ranklist', $this->db->select($sql));

        #分销等级
        $sql = "SELECT * FROM ###_member_comms_rank ORDER BY id";
        $this->smarty->assign('comms_list', $this->db->select($sql));

        $this->load->model('linkage');
        if (!isset($row)) {
            $row['rank_id'] = $row['sex'] = $row['id'] = $row['status'] = $row['zone'] = 0;
        }
        $area = $this->linkage->select_linkage($row['zone'] ? $row['zone'] : 1, 1, 'zone');
        $this->smarty->assign('area', $area);
        $this->smarty->assign('row', $row);
        $this->smarty->assign('is_robots', isset($_GET['is_robots']) ? intval($_GET['is_robots']) : 0);

        $template = "manage/member/edit.html";
        $_GET['act'] = isset($_GET['act']) ? $_GET['act'] : '';
        if ($_GET['act'] == 'mobile') {
            $template = "manage/member/mobile_status.html";
        }
        $this->smarty->display($template);
    }

    /**
     * 会员详情

     */

    function detail($mid = '')
    {

        $mid = intval($mid);

        if ($mid) {

            $row = $this->db->get("SELECT * FROM ###_member WHERE mid=" . $mid);
			
			$row_detail = $this->db->get("SELECT sex FROM ###_member_detail WHERE mid=" . $mid);

            $row['sex'] = $row_detail['sex'];

            $member_ivt = $this->db->get("SELECT mid,username FROM ###_member WHERE mid=" . $row['ivt_id']);

            $row['itv_name'] = $member_ivt['username'];

        }

        #非特殊等级列表

        $sql = "SELECT * FROM ###_member_rank ORDER BY id";

        $this->smarty->assign('ranklist', $this->db->select($sql));

        $this->load->model('linkage');

        if (!isset($row)) {
            $row['rank_id'] = $row['sex'] = $row['id'] = $row['status'] = $row['zone'] = 0;
        }

        #统计总订单数和金额
        $sql = "select count(1) as num,sum(order_amount) as amount from `###_goods_order` where status_pay=10 and mid=" . $mid ." AND extension_code!=".CART_EXCHANGE;
        $res = $this->db->get($sql);
        $this->load->model('exchange');
        if($this->exchange->power){
        	//加上积分兑换订单中的，现金支付
        	$sql = "select count(1) as num,sum(money_paid) as amount from `###_goods_order` where status_pay=10 and mid=" . $mid ." AND extension_code=".CART_EXCHANGE;
        	$res_exchange = $this->db->get($sql);
        	$res['num'] += $res_exchange['num'];
        	$res['amount'] += $res_exchange['amount'];
        }
        $this->smarty->assign('res', $res);

        #统计本月订单数和金额
        $starttime = strtotime(date("Y-m"));
        $sql = "select count(1) as num,sum(order_amount) as amount from `###_goods_order` where status_pay=10 and c_time>" . $starttime . " and mid=" . $mid ." AND extension_code!=".CART_EXCHANGE;
        $res_month = $this->db->get($sql);
        if($this->exchange->power){
        	$sql = "select count(1) as num,sum(money_paid) as amount from `###_goods_order` where status_pay=10 and c_time>" . $starttime . " and mid=" . $mid ." AND extension_code=".CART_EXCHANGE;
        	$res_exchange_month = $this->db->get($sql);
        	$res_month['num'] += $res_exchange_month['num'];
        	$res_month['amount'] += $res_exchange_month['amount'];
        }
        $this->smarty->assign('res_month', $res_month);

        #获取最近10条订单记录
        $sql = "select order_sn,name,mobile,goods_amount,order_amount,c_time from `###_goods_order` where status_pay=10 and mid=" . $mid . " order by id desc limit 10";
        $list_order = $this->db->select($sql);
        $this->smarty->assign('list_order', $list_order);

        #统计签到次数和积分值
        $sql = "select count(1) as num,sum(amount) as points from `###_score_log` where type=1 and mid=" . $mid;
        $log = $this->db->get($sql);
        $this->smarty->assign('log', $log);

        #获取最近10条签到记录
        $sql = "select c_time,amount from `###_score_log` where type=1 and mid='{$mid}' order by id desc limit 10";
        $list_log = $this->db->select($sql);
        $this->smarty->assign('list_log', $list_log);

        #获取收货地址
        $addr_list = $this->db->select("SELECT * FROM ###_member_address WHERE mid=$mid ORDER BY is_default DESC");
        foreach ($addr_list as $k => $v) {
            $sql = "select count(1) as num from `###_goods_order` where address_id='{$v['id']}'";
            $res = $this->db->get($sql);
            $addr_list[$k]['use_num'] = $res['num'];
        }
        $this->smarty->assign('addr_list', $addr_list);

        #获取银行
        $bank_list = $this->db->select("SELECT * FROM ###_member_bankcard WHERE mid=$mid ORDER BY is_default DESC");
        $this->smarty->assign('bank_list', $bank_list);

        $this->smarty->assign('row', $row);

        $template = "manage/member/detail.html";

        $this->smarty->display($template);

    }

    /**
     * 消费充值记录

     */

    function paylog($mid = '', $page = 1)
    {

        is_numeric($mid) || $this->fatalError('参数错误!');

        $member = $this->db->get("SELECT * FROM ###_member WHERE mid=" . $mid);

        $this->smarty->assign('member', $member);

        $this->load->model('page');

        $_GET['page'] = $page;

        $this->page->set_vars(array(

            'url' => ' href="#!member/paylog/' . $mid . '/{num}"',

        ));

        $list = $this->page->hashQuery("SELECT * FROM ###_member_pay_log WHERE mid=" . $mid)->result_array();

        $this->smarty->assign('list', $list);

        $this->smarty->display('manage/member/paylog.html');

    }

    /**
     * 调整账户金额/积分

     */

    function change_account($mid = '')
    {

        //提交

        if (isset($_POST['Submit'])) {

            $post = $_POST['post'];

            $row = $this->db->get("SELECT * FROM ###_member WHERE mid=" . intval($post['mid']));

            if (empty($row)) {
                $res = array('code' => 10001, 'message' => '用户不存在!');
            }

            $input['user_money'] = $post['addfee_user_money'] * floatval($post['user_money']);

            $input['frozen_money'] = $post['addfee_frozen_money'] * floatval($post['frozen_money']);

            $input['db_points'] = $post['addfee_db_points'] * intval($post['db_points']);

            $input['pay_points'] = $post['addfee_pay_points'] * intval($post['pay_points']);

            $input['desc'] = trim($post['remark']);

            $input['mid'] = $post['mid'];

            $res = $this->member->accountlog('admin', $input);

            admin_log('调整会员帐户余额：' . $row['username']);
			
			//调整积分
            if(floatval($post['user_score'])>0){
            	$this->load->model('score');
            	if($post['addfee_user_score']==1){
            		$remark = "后台操作会员（".$row['username'].":".$row['mid']."）增加积分：".floatval($post['user_score']);
            		$score = floatval($post['user_score']);
            	}else{
            		$score_info = $this->score->getTotal($row['mid']);
            		if ($score_info['total_left'] < floatval($post['user_score'])) {
            			$this->tip('扣除积分不足', array('inIframe' => true));
            			$this->exeJs("parent.com.xhide();parent.main.refresh()");
            		}
            		$score = -floatval($post['user_score']);
            		$remark = "后台操作会员（".$row['username'].":".$row['mid']."）减少积分：".floatval($post['user_score']);
            	}
            }
            //定义type=0杂项
            if (!$this->score->scoreLog(array('mid' => $row['mid'], 'amount' =>$score, 'remark' => $remark,'type'=>0))) {
            	// 积分操作错误
            	$this->tip('系统繁忙', array('inIframe' => true));
            	$this->exeJs("parent.com.xhide();parent.main.refresh()");
            }

            #发送中奖短信

            if (abs($input['user_money']) >= 5000 && $this->common['sms_open'] == 1 && statusTpl('sms_account')) {

                $this->smarty->assign('username', $row['username']);

                $this->smarty->assign('user_money', $input['user_money']);

                $this->load->library('sms');

                $ret = $this->sms->sendSmsTpl('18450068888', 'sms_account');

            }

            if (isset($res['code']) && $res['code'] == 0) {

                $this->tip($res['message'], array('inIframe' => true));

                //$this->exeJs("parent.location.href='/manage#!category/index'");

                $this->exeJs("parent.com.xhide();parent.main.refresh()");

            } else {

                $this->tip($res['message'], array('inIframe' => true, 'type' => 1));

            }

            die;

        }

        $row = $this->db->get("SELECT * FROM ###_member WHERE mid=" . $mid);
		
		//查询当前会员的积分
        $this->load->model('score');
        $score_info = $this->score->getTotal($mid);
        $row['score'] = $score_info['total_left'];

        $this->smarty->assign('row', $row);

        $this->smarty->display('manage/member/change_account.html');

    }

    /**
     * 账户明细

     */

    function account_log($page = '1')
    {

        #操作类型

        $this->smarty->assign('stages', $this->member->stages());

        $mid = intval((isset($_GET['mid']) ? $_GET['mid'] : 0));

        $where = '';

        $order = ' ORDER BY ';

        if ($mid) {
            $where .= " AND mid = '$mid'";
        }

        if (isset($_GET['stage'])) {
            $where .= " AND stage = '$_GET[stage]'";
        }

        if (isset($_GET['start_time'])) {
            $where .= " AND logtime >= '" . strtotime($_GET['start_time']) . "'";
        }

        if (isset($_GET['end_time'])) {
            $where .= " AND logtime <= '" . strtotime($_GET['end_time']) . "'";
        }

        if (isset($_GET['points'])) {
            $where .= " AND " . trim($_GET['points']) . "<>0";
        }

        if (isset($_GET['sortorder'])) {

            if ($_GET['points']) {

                $order .= trim($_GET['points']) . " " . $_GET['sortorder'];

            } else {

                $order .= "id " . $_GET['sortorder'];

            }

        } else {

            $order .= 'id DESC';

        }

        #关键词搜索

        $array = array('k', 'q');

        foreach ($array as $v) {

            if (!isset($_GET[$v])) {
                $_GET[$v] = '';
            }

        }

        if (!empty($_GET['q'])) {

            $where .= " AND `" . trim($_GET['k']) . "` LIKE '%" . addslashes($_GET['q']) . "%'";

        }

        $this->smarty->assign($_GET);

        $this->load->model('page');

        $_GET['page'] = intval($page);

        $this->page->set_vars(array(

            'per' => (int)$this->common['page_listrows'],

            'url' => ' href="#!member/account_log/{num}?mid=' . $mid . '"',

        ));

        $list = $this->page->hashQuery("SELECT * FROM ###_account_log WHERE id <> 0 $where $order")->result_array();

        $this->smarty->assign('list', $list);

        $total = $this->db->get("SELECT SUM(user_money) as user_money,SUM(frozen_money) as frozen_money,SUM(pay_points) as pay_points,SUM(db_points) as db_points FROM ###_account_log WHERE id <> 0 $where");

        $this->smarty->assign('total', $total);

        $this->smarty->assign('btnNo', 2);

        $this->smarty->display('manage/member/account_log.html');

    }

    /**
     * 提现记录

     */

    function member_account($page = '1')
    {

        $_GET['k'] = isset($_GET['k']) ? $_GET['k'] : '';
        $_GET['q'] = isset($_GET['q']) ? $_GET['q'] : '';
        $_GET['type'] = 2;
        $_GET['status'] = isset($_GET['status']) ? $_GET['status'] : 2;
        $_GET['start_time'] = isset($_GET['start_time']) ? $_GET['start_time'] : '';
        $_GET['end_time'] = isset($_GET['end_time']) ? $_GET['end_time'] : '';

        $this->load->model('page');

        $_GET['page'] = intval($page);

        $this->page->set_vars(array(

            'per' => (int)$this->common['page_listrows'],

        ));

        $where = '';

        if (isset($_GET['type'])) {
            $where .= " AND type = '" . intval($_GET['type']) . "' ";
        }

        if ($_GET['status'] > 0) {
            $where .= " AND status = '" . intval($_GET['status']) . "' ";
        }
        if (!empty($_GET['q'])) {

            if ($_GET['k'] == 'username') {

                // $mid = $this->db->getstr("select mid from ###_member where username like '" . trim($_GET['q']) . "'");

                // $where .= "AND mid=$mid";

                // 按照模糊查找调整过
                $mids = $this->db->select("select mid from ###_member where username like '%" . trim($_GET['q']) . "%'");

                if ($mids && is_array($mids)) {
                    if (count($mids) == 1) {
                        $where .= ' AND mid = ' . $mids[0]['mid'];
                    } else {
                        $midStr = array();
                        foreach ($mids as $v) {
                            $midStr[] = $v['mid'];
                        }
                        $where .= " AND mid in (" . join(',', $midStr) . ") ";
                    }
                } else {
                    $where .= ' AND mid = 0 ';
                }

            } else {

                $where .= " AND " . trim($_GET['k']) . " LIKE '%" . trim($_GET['q']) . "%'";

            }

        }

        if ($_GET['start_time']) {
            $where .= " AND add_time >= '" . strtotime($_GET['start_time']) . "'";
        }

        if ($_GET['end_time']) {
            $where .= " AND add_time <= '" . strtotime($_GET['end_time']) . "'";
        }

        if ($_GET['pay_id'] > 0) {
            $where .= " AND pay_id = '" . intval($_GET['pay_id']) . "' ";
        }

        $list = $this->page->hashQuery("SELECT * FROM ###_member_account WHERE mid<>0 $where ORDER BY id DESC")->result_array();

        $list = $this->db->lJoin($list, 'member', 'mid,mobile', 'mid', 'mid');

        $total = $this->db->getstr("SELECT SUM(amount) as total  FROM ###_member_account WHERE mid<>0 $where");

        $this->smarty->assign('total', $total > 0 ? $total : '0.00');

        $fee = $this->db->getstr("SELECT SUM(fee) as fee  FROM ###_member_account WHERE mid<>0 $where");

        $this->smarty->assign('fee', $fee > 0 ? $fee : '0.00');

        #支付方式

        $this->load->model('payment');

        $this->smarty->assign('payment', $this->payment->getPayment("is_cod<>1 AND pay_code<>'balance'"));

        $this->load->model('share');

        $this->smarty->assign('list', $list);

        $this->smarty->display('manage/member/member_account.html');

    }

    /**
     * 提现申请列表

     */

    function member_withraw($page = '1')
    {

        $_GET['k'] = isset($_GET['k']) ? $_GET['k'] : '';
        $_GET['q'] = isset($_GET['q']) ? $_GET['q'] : '';
        $_GET['type'] = 2;
        $_GET['status'] = 1;
        $_GET['start_time'] = isset($_GET['start_time']) ? $_GET['start_time'] : '';
        $_GET['end_time'] = isset($_GET['end_time']) ? $_GET['end_time'] : '';

        $this->load->model('page');

        $_GET['page'] = intval($page);

        $this->page->set_vars(array(

            'per' => (int)$this->common['page_listrows'],

        ));

        $where = '';

        if (isset($_GET['type'])) {
            $where .= " AND type = '" . intval($_GET['type']) . "' ";
        }

        if ($_GET['status'] > 0) {
            $where .= " AND status = '" . intval($_GET['status']) . "' ";
        }
        if (!empty($_GET['q'])) {

            if ($_GET['k'] == 'username') {

                // $mid = $this->db->getstr("select mid from ###_member where username like '" . trim($_GET['q']) . "'");

                // $where .= "AND mid=$mid";

                // 按照模糊查找调整过
                $mids = $this->db->select("select mid from ###_member where username like '%" . trim($_GET['q']) . "%'");

                if ($mids && is_array($mids)) {
                    if (count($mids) == 1) {
                        $where .= ' AND mid = ' . $mids[0]['mid'];
                    } else {
                        $midStr = array();
                        foreach ($mids as $v) {
                            $midStr[] = $v['mid'];
                        }
                        $where .= " AND mid in (" . join(',', $midStr) . ") ";
                    }
                } else {
                    $where .= " AND mid = 0  ";
                }

            } else {

                $where .= " AND " . trim($_GET['k']) . " LIKE '%" . trim($_GET['q']) . "%'";

            }

        }

        if ($_GET['start_time']) {
            $where .= " AND add_time >= '" . strtotime($_GET['start_time']) . "'";
        }

        if ($_GET['end_time']) {
            $where .= " AND add_time <= '" . strtotime($_GET['end_time']) . "'";
        }

        if ($_GET['pay_id'] > 0) {
            $where .= " AND pay_id = '" . intval($_GET['pay_id']) . "' ";
        }

        $list = $this->page->hashQuery("SELECT * FROM ###_member_account WHERE mid<>0 $where ORDER BY id DESC")->result_array();

        $list = $this->db->lJoin($list, 'member', 'mid,mobile', 'mid', 'mid');

        $total = $this->db->getstr("SELECT SUM(amount) as total  FROM ###_member_account WHERE mid<>0 $where");

        $this->smarty->assign('total', $total > 0 ? $total : '0.00');

        $fee = $this->db->getstr("SELECT SUM(fee) as fee  FROM ###_member_account WHERE mid<>0 $where");

        $this->smarty->assign('fee', $fee > 0 ? $fee : '0.00');

        #支付方式

        $this->load->model('share');

        $this->smarty->assign('list', $list);

        $this->smarty->display('manage/member/member_withraw.html');

    }

    /**
     * 充值列表

     */

    function member_recharge($page = '1')
    {

        $_GET['k'] = isset($_GET['k']) ? $_GET['k'] : '';
        $_GET['q'] = isset($_GET['q']) ? $_GET['q'] : '';
        $_GET['type'] = 1;
        $_GET['status'] = isset($_GET['status']) ? $_GET['status'] : 1;
        $_GET['start_time'] = isset($_GET['start_time']) ? $_GET['start_time'] : '';
        $_GET['end_time'] = isset($_GET['end_time']) ? $_GET['end_time'] : '';

        $this->load->model('page');

        $_GET['page'] = intval($page);

        $this->page->set_vars(array(

            'per' => (int)$this->common['page_listrows'],

        ));

        $where = '';

        if (isset($_GET['type'])) {
            $where .= " AND type = '" . intval($_GET['type']) . "' ";
        }

        if ($_GET['status'] > 0) {
            $where .= " AND status = '" . intval($_GET['status']) . "' ";
        }
        if (!empty($_GET['q'])) {

            if ($_GET['k'] == 'username') {

                // $mid = $this->db->getstr("select mid from ###_member where username like '" . trim($_GET['q']) . "'");

                // $where .= "AND mid=$mid";

                // 按照模糊查找调整过
                $mids = $this->db->select("select mid from ###_member where username like '%" . trim($_GET['q']) . "%'");

                if ($mids && is_array($mids)) {
                    if (count($mids) == 1) {
                        $where .= ' AND mid = ' . $mids[0]['mid'];
                    } else {
                        $midStr = array();
                        foreach ($mids as $v) {
                            $midStr[] = $v['mid'];
                        }
                        $where .= " AND mid in (" . join(',', $midStr) . ") ";
                    }
                } else {
                    $where .= " AND mid = 0  ";
                }

            } else {

                $where .= " AND " . trim($_GET['k']) . " LIKE '%" . trim($_GET['q']) . "%'";

            }

        }

        if ($_GET['start_time']) {
            $where .= " AND add_time >= '" . strtotime($_GET['start_time']) . "'";
        }

        if ($_GET['end_time']) {
            $where .= " AND add_time <= '" . strtotime($_GET['end_time']) . "'";
        }

        if ($_GET['pay_id'] > 0) {
            $where .= " AND pay_id = '" . intval($_GET['pay_id']) . "' ";
        }

        $list = $this->page->hashQuery("SELECT * FROM ###_member_account WHERE mid<>0 $where ORDER BY id DESC")->result_array();

        $list = $this->db->lJoin($list, 'member', 'mid,realname,mobile', 'mid', 'mid');

        $total = $this->db->getstr("SELECT SUM(amount) as total  FROM ###_member_account WHERE mid<>0 $where");

        $this->smarty->assign('total', $total > 0 ? $total : '0.00');

        $fee = $this->db->getstr("SELECT SUM(fee) as fee  FROM ###_member_account WHERE mid<>0 $where");

        $this->smarty->assign('fee', $fee > 0 ? $fee : '0.00');

        #支付方式

        $this->load->model('share');

        $this->smarty->assign('list', $list);

        $this->smarty->display('manage/member/member_recharge.html');

    }

    /**
     * 充值提现编辑

     */

    function member_account_edit($id = '')
    {

        if ($id) {

            $row = $this->db->select("SELECT * FROM ###_member_account WHERE id=" . $id);

            $row = $this->db->lJoin($row, 'member', 'mid,mobile', 'mid', 'mid');

            $row = $row[0];

            $row['amount'] += $row['fee'];
        }

        //提交

        if (isset($_POST['Submit'])) {

            $post = $_POST['post'];

            $res = $this->member->member_account_save($post);

            if (isset($res['code']) && $res['code'] == 0) {

                //成功

                if ($post['status'] == 2) {

                    //充值

                    if ($row['type'] == 1) {

                        $this->member->accountlog('recharge', array('mid' => $row['mid'], 'user_money' => $row['amount'], 'desc' => '通过' . $row['pay_name'] . '充值账户'));

                    } else {

                        //提现
                        if ($row['from'] == 1) {
#佣金提现
                            $this->db->update('member', " deduct_commission = deduct_commission - {$row['amount']}", array('mid' => $row['mid']));
                        } else {
#余额提现
                            $this->member->accountlog('withdraw', array('mid' => $row['mid'], 'frozen_money' => -$row['amount'], 'desc' => '通过' . $row['pay_name'] . '提现账户'));
                        }
                        // 模版消息 22 提现申请被通过 {插入账号},{插入金额}
                        // template_msg_action start
                        $this->load->model('template_msg');
                        $msgParams = array($row['user_note'], $row['amount']);
                        $this->template_msg->inQueue(22, $row['mid'], $msgParams);
                        // template_msg_action end

                    }

                } elseif ($post['status'] == 3 && $row['type'] == 2) {
                    if ($row['from'] == 1) {
#佣金提现
                        $this->db->update('member', "commission = commission + {$row['amount']} , deduct_commission = deduct_commission - {$row['amount']}", array('mid' => $row['mid']));

                    } else {
#余额提现
                        $input = array();

                        $input['mid'] = $row['mid'];

                        $input['user_money'] = $row['amount'];

                        $input['frozen_money'] = -$row['amount'];

                        $input['desc'] = '取消账户提现,解冻保证金';

                        $this->member->accountlog('withdraw', $input);
                    }

                    // 模版消息 23 提现申请被拒绝 {插入账号},{插入金额},{插入理由},{插入店铺}
                    // template_msg_action start
                    $this->load->model('template_msg');
                    $msgParams = array($row['user_note'], $row['amount'], $post['admin_note'], C("site_name"));
                    $this->template_msg->inQueue(23, $row['mid'], $msgParams);
                    // template_msg_action end

                }

                $this->tip($res['message'], array('inIframe' => true));

                $this->exeJs("parent.com.xhide();parent.main.refresh()");

            } else {

                $this->tip($res['message'], array('inIframe' => true, 'type' => 1));

            }

            die;

        }

        $this->smarty->assign('row', $row);

        $this->smarty->display('manage/member/member_account_edit.html');

    }

    /**
     * 红包记录列表

     */

    function member_redpack($page = '1')
    {

        $_GET['k'] = isset($_GET['k']) ? $_GET['k'] : '';
        $_GET['q'] = isset($_GET['q']) ? $_GET['q'] : '';
        //$_GET['status'] = isset($_GET['status']) ? $_GET['status'] : 1;
        $_GET['start_time'] = isset($_GET['start_time']) ? $_GET['start_time'] : '';
        $_GET['end_time'] = isset($_GET['end_time']) ? $_GET['end_time'] : '';

        $this->load->model('page');

        $_GET['page'] = intval($page);

        $this->page->set_vars(array(

            'per' => (int)$this->common['page_listrows'],

        ));

        $where = '';

        if (isset($_GET['type'])) {
            $where .= " AND type = '" . intval($_GET['type']) . "' ";
        }

        if ($_GET['status'] > 0) {
            $where .= " AND status = '" . intval($_GET['status']) . "' ";
        }
        if (!empty($_GET['q'])) {

            if ($_GET['k'] == 'username') {

                // 按照模糊查找调整过
                $mids = $this->db->select("select mid from ###_member where username like '%" . trim($_GET['q']) . "%'");

                if ($mids && is_array($mids)) {
                    if (count($mids) == 1) {
                        $where .= ' AND mid = ' . $mids[0]['mid'] . ' ';
                    } else {
                        $midStr = array();
                        foreach ($mids as $v) {
                            $midStr[] = $v['mid'];
                        }
                        $where .= " AND mid in (" . join(',', $midStr) . ") ";
                    }
                } else {
                    $where .= " AND mid = 0  ";
                }

            } else {

                $where .= " AND " . trim($_GET['k']) . " LIKE '%" . trim($_GET['q']) . "%'";

            }

        }

        if ($_GET['start_time']) {
            $where .= " AND add_time >= '" . strtotime($_GET['start_time']) . "'";
        }

        if ($_GET['end_time']) {
            $where .= " AND add_time <= '" . strtotime($_GET['end_time']) . "'";
        }

        $list = $this->page->hashQuery("SELECT * FROM ###_member_redpack WHERE mid<>0 $where ORDER BY id DESC")->result_array();

        $list = $this->db->lJoin($list, 'member', 'mid,mobile', 'mid', 'mid');

        $total = $this->db->getstr("SELECT SUM(amount) as total  FROM ###_member_redpack WHERE mid<>0 $where");

        $this->smarty->assign('total', $total > 0 ? $total : '0.00');

        $this->smarty->assign('list', $list);

        $this->smarty->display('manage/member/member_redpack.html');

    }

    /**
     * 删除充值申请

     */

    function member_account_del()
    {

        $id = (int)$_POST['id'];

        if (!$id) {
            die;
        }

        $row = $this->db->get("SELECT * FROM ###_member_account WHERE id=" . $id);

        if (isset($row['id'])) {

            admin_log('删除充值/提现申请：' . $row['username']);

            //删除提现解冻保证金

            if ($row['type'] == 2 && $row['status'] == 1) {

                $input = array();

                $input['mid'] = $row['mid'];

                $input['user_money'] = $row['amount'];

                $input['frozen_money'] = -$row['amount'];

                $input['desc'] = '取消账户提现,解冻保证金';

                $this->member->accountlog('withdraw', $input);

            }

            $this->db->delete('member_account', array('id' => $id));

            $this->tip('删除成功', array('type' => 1));

        }

    }

    /**
     * 认证身份证

     */

    function verify_idcard($page = 1)
    {

        $where = ' ';

        #关键词搜索

        $array = array('k', 'q');

        foreach ($array as $v) {

            if (!isset($_GET[$v])) {
                $_GET[$v] = '';
            }

        }

        if (!empty($_GET['q'])) {

            $where .= " AND " . trim($_GET['k']) . " LIKE '" . addslashes($_GET['q']) . "'";

        }

        if (!empty($_GET['status'])) {

            $where .= " AND status = '" . $_GET['status'] . "'";

        } else {
            $_GET['status'] = null;
        }

        $this->smarty->assign($_GET);

        $this->load->model('page');

        $_GET['page'] = intval($page);

        $this->page->set_vars(array(

            'per' => (int)$this->common['page_listrows'],

        ));

        $list = $this->page->hashQuery("SELECT * FROM ###_verify_idcard WHERE mid<>0 $where ORDER BY id DESC")->result_array();

        $list = $this->db->lJoin($list, 'member', 'mid,mobile', 'mid', 'mid');

        $this->smarty->assign('list', $list);

        $this->smarty->display('manage/member/verify_idcard.html');

    }

    function verify_idcard_edit($id = '')
    {

        $row = $this->db->get("SELECT * FROM ###_verify_idcard WHERE id=" . $id);

        if (isset($_POST['Submit'])) {

            $post = $_POST['post'];

            $post['id'] = $id;

            $this->member->verify_idcard_save($post);

            if ($post['status'] == 2) {

                $post = array();

                $post['realname'] = $row['realname'];

                $post['idcard'] = $row['card'];

                //$this->db->update('member', $post, array('mid' => $row['mid']));
                $this->member->member_other_save($post, $row['mid']);

            } elseif ($post['status'] == 3) {

                //拒绝通过审核，扣除10元保证金

                //$input = array();

                //$input['mid'] = $row['mid'];

                //$input['user_money'] = -10;

                //$input['desc'] = '实名认证审核未通过，扣除保证金';

                //$this->member->accountlog('admin',$input);

                //更新会员实名

                $post = array();

                $post['realname'] = '';

                $post['idcard'] = '';

                //$this->db->update('member', $post, array('mid' => $row['mid']));
                $this->member->member_other_save($post, $row['mid']);

                //站内信通知用户

                $this->load->model('msg');

                $input_arr = array();

                $input_arr['type'] = 1;

                $input_arr['to_mid'] = $row['mid'];

                $input_arr['to_username'] = $row['username'];

                $input_arr['title'] = '您的实名认证未通过系统认证';

                $input_arr['content'] = '您的实名认证未通过系统认证,拒绝理由-' . $post['remark'];

                $this->msg->msg_save($input_arr);

            }

            //$this->tip('保存成功',array('inIframe'=>true,'type'=>1));

            //$this->exeJs("parent.com.xhide();parent.main.refresh();");

            $this->tip('保存成功', array('inIframe' => true, 'type' => 1));

            $this->exeJs("parent.location.href='/manage#!member/verify_idcard/'");

        }

        $this->smarty->assign('row', $row);

        $this->smarty->display('manage/member/verify_idcard_edit.html');

    }

    /**
     * 收货地址

     */

    function address($mid = '')
    {

        $list = $this->db->select("SELECT * FROM ###_member_address WHERE mid=$mid ORDER BY is_default DESC");
        foreach ($list as $k => $v) {
            $sql = "select count(1) as num from `###_goods_order` where address_id='{$v['id']}'";
            $res = $this->db->get($sql);
            $list[$k]['use_num'] = $res['num'];
        }
        $this->smarty->assign('list', $list);

        $this->smarty->display('manage/member/address.html');

    }

    /**
     * 银行账号

     */

    function bankcard($mid = '')
    {

        $list = $this->db->select("SELECT * FROM ###_member_bankcard WHERE mid=$mid  ORDER BY is_default DESC");

        $this->smarty->assign('list', $list);

        $this->smarty->display('manage/member/bankcard.html');

    }

    /**
     * 会员公告

     */

    function message($page = 1)
    {
        $_GET['k'] = isset($_GET['k']) ? $_GET['k'] : '';
        $_GET['q'] = isset($_GET['q']) ? $_GET['q'] : '';
        $_GET['mid_type'] = isset($_GET['mid_type']) ? $_GET['mid_type'] : 0;
        $_GET['tomid_type'] = isset($_GET['tomid_type']) ? $_GET['tomid_type'] : 0;
        $_GET['username'] = isset($_GET['username']) ? $_GET['username'] : '';
        $_GET['tousername'] = isset($_GET['tousername']) ? $_GET['tousername'] : '';

        #按钮

        $this->btnMenu += array(

            3 => array('url' => '#!member/message', 'name' => '会员公告'),

            4 => array('url' => '#!member/message_edit?com=xshow|发布公告', 'name' => '发布公告'),

        );

        #筛选

        $where = 'WHERE 1';

        #关键词搜索

        if (isset($_GET['q']) && !empty($_GET['q'])) {

            $where .= " AND " . trim($_GET['k']) . " LIKE '%" . addslashes($_GET['q']) . "%'";

        }

        if (isset($_GET['mid_type']) && !empty($_GET['mid_type'])) {

            $where .= " AND mid_type=1";

        }

        if (isset($_GET['tomid_type']) && !empty($_GET['tomid_type'])) {

            $where .= " AND tomid_type=1";

        }

        if (isset($_GET['username']) && !empty($_GET['username'])) {

            $where .= " AND username='" . trim($_GET['username']) . "' ";

        }

        if (isset($_GET['tousername']) && !empty($_GET['tousername'])) {

            $where .= " AND tousername='" . trim($_GET['tousername']) . "' ";

        }

        $this->load->model('page');

        $_GET['page'] = intval($page);

        $this->page->set_vars(array(

            'per' => (int)$this->common['page_listrows'],

        ));

        $list = $this->page->hashQuery("SELECT * FROM ###_msg $where ORDER BY id DESC")->result_array();

        $this->smarty->assign('btnNo', 3);

        $this->smarty->assign('btnMenu', $this->btnMenu);

        if (empty($list)) {
            $list['mid_type'] = 0;
        }

        $this->smarty->assign('list', $list);

        $this->smarty->display('manage/member/message.html');

    }

    function message_edit($id = '')
    {

        //提交

        $this->load->model('msg');

        if (isset($_POST['Submit'])) {

            $_POST['post']['mid'] = defined('UID') ? UID : 0;

            $_POST['post']['username'] = defined('USER') ? USER : '';

            $_POST['post']['mid_type'] = 1;

            $tousername = !empty($_POST['tousername']) ? trim($_POST['tousername']) : 0;

            $user = $this->db->get("SELECT mid FROM ###_member WHERE username='" . $tousername . "'");

            if ($user['mid']) {

                $_POST['post']['tomid'] = $user['mid'];

                $_POST['post']['tousername'] = $tousername;

            }

            $res = $this->msg->save();

            if (isset($res['code']) && $res['code'] == 0) {

                $this->tip($res['message'], array('inIframe' => true));

                $this->exeJs("parent.com.xhide();parent.main.refresh()");

            } else {

                $this->tip($res['message'], array('inIframe' => true, 'type' => 1));

            }

            exit;

        }

        $id = !empty($id) ? intval($id) : 0;

        $row = array();

        //编辑

        if ($id) {

            $row = $this->db->get("SELECT * FROM ###_msg WHERE id=" . $id);

            $this->smarty->assign('id', $id);

        }

        //初始化内容
        $row['content'] = !empty($row['content']) ? $row['content'] : '';
        $row['html_content'] = $this->form->editor('content', $row['content'], 'name="post[content]" style="width:100%;height:200px;"', array('toolbar' => 'basic'));

        if (!$id) {
            $this->smarty->assign('btnNo', 4);
        }

        $this->smarty->assign('row', $row);

        $this->smarty->display('manage/member/message_edit.html');

    }

    /**
     * 会员过滤条件
     * @return array
     */

    function getFilterCond()
    {

        $where = ' 1 ';

        $order = ' ORDER BY ';

        #关键词搜索

        $array = array('k', 'q');

        foreach ($array as $v) {

            if (!isset($_REQUEST[$v])) {
                $_REQUEST[$v] = '';
            }

        }

        if (!empty($_REQUEST['q'])) {
            //按推荐人
            if ($_REQUEST['k'] == 'itv') {
                $itv_id = $this->db->getstr("SELECT mid FROM ###_member WHERE username='" . $_REQUEST['q'] . "'");
                if ($itv_id) {
                    $where .= " AND a.ivt_id=" . $itv_id;
                } else {
                    $where .= " AND a.ivt_id=" . '-1';
                }
            }elseif($_REQUEST['k']=='nickname'){
                $member_other = $this->db->select("SELECT mid FROM ###_member_detail WHERE " . trim($_REQUEST['k']) . "  LIKE '%" . $_REQUEST['q'] . "%'");
            	if(!empty($member_other)){
            		foreach($member_other as $k=>$v){
            			if($k>0)
            				$other_mid .=",".$v['mid'];
            			else $other_mid = $v['mid'];
            		}
            		$where .= " AND a.mid in($other_mid) ";
            	}else{
					$where .=" AND 1<>1 ";
				}
            } else {
                $where .= " AND a." . trim($_REQUEST['k']) . " LIKE '%" . addslashes($_REQUEST['q']) . "%'";
            }
        } elseif ($_REQUEST['k'] == 'itv') {

            $where .= " AND a.ivt_id>0";

        }

        $fields = array('start_time', 'end_time');

        foreach ($fields as $v) {

            if (!isset($_REQUEST[$v])) {
                $_REQUEST[$v] = '';
            }

        }

        //起始时间：查关注时间

        if (!empty($_REQUEST['start_time'])) {

            $where .= " AND b.c_time>" . strtotime($_REQUEST['start_time']);

        }

        //结束时间：查关注时间

        if (!empty($_REQUEST['end_time'])) {

            $where .= " AND b.c_time<" . strtotime($_REQUEST['end_time']);

        }

        //判断会员类型
        if(!isset($_REQUEST['is_robots'])){
            $_REQUEST['is_robots'] = '';
        }
        if($_REQUEST['is_robots'] !== ''){
            $where .= " AND a.is_robots=" . intval($_REQUEST['is_robots']);
        }

        //会员等级

        if (!empty($_REQUEST['rank_id'])) {

            $where .= " AND a.rank_id=" . intval($_REQUEST['rank_id']);
        }

        //在线离线状态
        $_REQUEST['online'] = isset($_REQUEST['online']) ? $_REQUEST['online'] : 0;
        if ($_REQUEST['online'] == 1) {

            $where .= " AND b.mid>0 AND b.c_time>" . (time() - $this->session->expire);

        }

        //手机状态

        if (isset($_REQUEST['verify_mobile']) && $_REQUEST['verify_mobile'] !== '') {

            if ($_REQUEST['verify_mobile'] == 99) {

                $where .= " AND (a.verify_mobile=0 OR a.verify_mobile IS NULL OR a.verify_mobile='')";

            } else {

                $where .= " AND a.verify_mobile=" . $_REQUEST['verify_mobile'];

            }

        }
        $_REQUEST['status'] = !empty($_REQUEST['status']) ? $_REQUEST['status'] : 1;
        //用户状态
        if (isset($_REQUEST['status'])) {

            if ($_REQUEST['status'] == 99) {

                $where .= " AND a.status!=1";

            } else {

                $where .= " AND a.status=1";

            }

        }

        //手机认证

        if (isset($_REQUEST['is_voice'])) {

            if ($_REQUEST['is_voice'] == 99) {

                $where .= " AND a.is_voice=0";

            } else {

                $where .= " AND a.is_voice=1";

            }

        }

        //赚拍币

        if (isset($_REQUEST['free'])) {

            if ($_REQUEST['free'] == 99) {

                $where .= " AND a.free!=1";

            } else {

                $where .= " AND a.free=1";

            }

        }

        #快速排序

        $order .= isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 'a.mid';

        $order .= ' ';

        $order .= isset($_REQUEST['sortorder']) ? $_REQUEST['sortorder'] : 'DESC';

        return array('where' => $where, 'order' => $order);

    }

    /**
     * 导出充值提现

     */

    function export_account()
    {

        $ids = trim($_GET['ids']);

        $where = "";

        if ($ids) {
            $where .= " AND id IN ($ids)";
        }

        $list = $this->db->select("SELECT * FROM ###_member_account WHERE id <> 0 $where");

        $list = $this->db->lJoin($list, "member", "mid,realname", "mid", "mid");

        foreach ($list as $k => $v) {

            $list[$k]['add_time'] = date('Y-m-d H:i:s', $v['add_time']);

            $list[$k]['type'] = $v['type'] == 1 ? '充值' : '提现';

        }

        $fields = array(

            'mid' => '会员ID',

            'username' => '会员名',

            'type' => '类型',

            'realname' => '真实姓名',

            'amount' => '金额',

            'pay_name' => '支付方式',

            'user_note' => '银行账号',

            'add_time' => '申请时间',

        );

        $this->load->model('share');

        $this->share->SetExcelHeader('充值提现-' . date('Y-m-d-H-i'), '微信会员列表');

        $this->share->SetExcelBody($fields, $list);

    }

    /**
     * 批量处理会员

     */

    function batch_member()
    {

        $ids = trim($_REQUEST['ids']);
        $act = trim($_REQUEST['act']);
        $conds = $this->getFilterCond();
        $condition = $conds['where'];
        $orderby = $conds['order'];
        if ($ids) {
            $condition .= " AND a.mid IN ($ids)";
        }

        $sql = "SELECT DISTINCT a.mid,a.*,b.c_time AS stime,IFNULL(c.username,'-') AS ivt_name FROM ###_member AS a " .
            "LEFT JOIN ###_member_detail AS b ON b.mid=a.mid " .
            "LEFT JOIN ###_member AS c ON c.mid=a.ivt_id " .
            ($condition != '' ? " WHERE $condition" : '') . $orderby;
        $list = $this->db->select($sql);
        if ($act == 'export') {//导出
            foreach ($list as $k => $v) {
                $list[$k]['c_time'] = date('Y-m-d H:i:s', $v['c_time']);
            }
            $fields = array('mid' => 'ID', 'username' => '用户名', 'nickname' => '真实姓名', 'mobile' => '手机', 'email' => '邮箱', 'ivt_name' => '推荐人', 'user_money' => '可用余额', 'commission' => '可用佣金', 'c_time' => '注册时间',
            );
            $this->load->model('share');
            $this->share->SetExcelHeader('会员列表-' . date('Y-m-d-H-i'), '微信会员列表');
            $this->share->SetExcelBody($fields, $list);
        } elseif ($act == 'send_redpack') {//发红包
            $str = join(",", array_column($list, "mid"));
            $this->smarty->assign("num", count($list));
            $this->smarty->assign('mids', $str);
            $html = $this->smarty->fetch('manage/member/send_redpack.html');
            echo $html;
            exit;
        } elseif ($act == 'send_coupon') {//发优惠券
            $this->load->model("coupon");
            $coupon_list = $this->coupon->getAvailableCouponList(" and sid=0");
            $str = join(",", array_column($list, "mid"));
            $this->smarty->assign("num", count($list));
            $this->smarty->assign('list', $coupon_list);
            $this->smarty->assign('mids', $str);
            $html = $this->smarty->fetch('manage/member/send_coupon.html');
            echo $html;
            exit;
        } elseif ($act == 'bacth_message') {//发站内信
            $this->load->model("coupon");
            $coupon_list = $this->coupon->getAvailableCouponList();
            $str = join(",", array_column($list, "mid"));
            $this->smarty->assign("num", count($list));
            $this->smarty->assign('list', $coupon_list);
            $this->smarty->assign('mids', $str);
            $html = $this->smarty->fetch('manage/member/send_msg.html');
            echo $html;
            exit;
        }elseif ($act == 'bacth_wxtemplate') {//发送微信推文
            $this->load->model("wxtemplate");
            $wxlist = $this->wxtemplate->getlist(array("where"=>" and status=1"));
            $this->smarty->assign('list', $wxlist);
            $wxid = $_REQUEST['wxid'];
            $this->smarty->assign('wxid', $wxid);
            if($wxid>0){
                $row = array();
                foreach($wxlist as $v){
                    if($v['id']==$wxid){
                        $row = $v;break;
                    }
                }
                $content = $row['content'];
                preg_match_all("/{{(.*)}}/",$content,$res);
                foreach($res[1] as $k=>$v){
                    $v = substr($v,0,strpos($v,'.'));
                    $res[1][$k] = $v;
                }
                $res = array_combine($res[1],$res[0]);
                //echo "<pre>";print_r($res);exit;
                $this->smarty->assign("wxlist", $res);
                $this->smarty->assign("row", $row);
            }

            $str = join(",", array_column($list, "mid"));
            $this->smarty->assign("num", count($list));
            $this->smarty->assign('mids', $str);
            $html = $this->smarty->fetch('manage/member/send_wxtemplate.html');
            echo $html;
            exit;
        }

    }

    /**
     * 批量处理充值提现

     */

    function batch_account()
    {

        $ids = trim($_GET['ids']);

        if ($ids) {

            foreach (explode(",", $ids) as $id) {

                $row = $this->db->get("SELECT * FROM ###_member_account WHERE id=" . $id);

                $post['status'] = intval($_GET['status']);

                $post['id'] = $id;

                if ($row['status'] == 1) {
                    $res = $this->member->member_account_save($post);
                }

                if (isset($res['code']) && $res['code'] == 0) {

                    //成功

                    if ($post['status'] == 2) {

                        //充值

                        if ($row['type'] == 1) {

                            $this->member->accountlog('recharge', array('mid' => $row['mid'], 'user_money' => $row['amount'], 'desc' => '通过' . $row['pay_name'] . '充值账户'));

                        } else {

                            //提现

                            $this->member->accountlog('withdraw', array('mid' => $row['mid'], 'frozen_money' => -$row['amount'], 'desc' => '通过' . $row['pay_name'] . '提现账户'));

                        }

                    }

                }

            }

            $this->tip('操作成功', array('type' => 1));

            $this->exeJs("parent.main.refresh()");

        }

    }

    /**
     * 邀请奖励

     */

    function award_ivt($page = 1)
    {

        $where = '';

        if ($_GET['status']) {
            $where .= "AND status = '$_GET[status]'";
        }

        if ($_GET['num']) {
            $where .= "AND num = '$_GET[num]'";
        }

        $this->load->model('page');

        $_GET['page'] = intval($page);

        $this->page->set_vars(array(

            'per' => (int)$this->common['page_listrows'],

        ));

        $list = $this->page->hashQuery("SELECT * FROM ###_award_ivt WHERE mid<>0 $where ORDER BY id DESC")->result_array();

        $this->smarty->assign('list', $list);

        $this->smarty->display('manage/member/award_ivt.html');

    }

    /**
     * 审核奖励

     */

    function award_ivt_edit($id = "")
    {

        $row = $this->db->get("SELECT * FROM ###_award_ivt WHERE id = '$id'");

        $this->smarty->assign('row', $row);

        $this->smarty->display('manage/member/award_ivt_edit.html');

    }

    /**
     * 佣金明细

     */

    function commission($page = 1)
    {

        $_GET['q'] = isset($_GET['q']) ? $_GET['q'] : '';
        $_GET['k'] = isset($_GET['k']) ? $_GET['k'] : 0;
        $_GET['start_time'] = isset($_GET['start_time']) ? $_GET['start_time'] : '';
        $_GET['end_time'] = isset($_GET['end_time']) ? $_GET['end_time'] : '';

        $where = '';

        if ($_GET['q']) {
            $where .= "AND `" . trim($_GET['k']) . "` LIKE '%" . trim($_GET['q']) . "%'";
        }

        if ($_GET['start_time']) {
            $where .= "AND addtime >= '" . strtotime($_GET['start_time']) . "'";
        }

        if ($_GET['end_time']) {
            $where .= "AND addtime <= '" . strtotime($_GET['end_time']) . "'";
        }

        if (($_GET['level'] || $_GET['level'] == '0') && $_GET['level'] != 99) {
            $where .= "AND level = '" . $_GET['level'] . "'";
        }

        $this->load->model("agent");
        $data['where'] = $where;
        $list = $this->agent->getCommission($data, '', $page);

        $total = $this->db->getstr("SELECT SUM(commission) as total  FROM ###_commission WHERE mid<>0 $where");

        $this->smarty->assign('total', $total > 0 ? $total : '0.00');

        $this->smarty->assign('list', $list);

        $this->smarty->display('manage/member/commission.html');

    }

    /**
     * 佣金提现记录

     */

    function withdraw_commission($page = 1)
    {

        $_GET['q'] = isset($_GET['q']) ? $_GET['q'] : 0;
        $_GET['k'] = isset($_GET['k']) ? $_GET['k'] : 0;
        $_GET['status'] = isset($_GET['status']) ? $_GET['status'] : '';
        $_GET['start_time'] = isset($_GET['start_time']) ? $_GET['start_time'] : '';
        $_GET['end_time'] = isset($_GET['end_time']) ? $_GET['end_time'] : '';

        $this->load->model('page');

        $_GET['page'] = intval($page);

        $this->page->set_vars(array(

            'per' => (int)$this->common['page_listrows'],

        ));

        $where = '';

        if ($_GET['status'] || $_GET['status']=='0') {
            $where .= " AND status=" . trim($_GET['status']);
        }

        if ($_GET['q']) {
            $where .= " AND " . trim($_GET['k']) . " LIKE '%" . trim($_GET['q']) . "%'";
        }

        if ($_GET['start_time']) {
            $where .= " AND addtime >= '" . strtotime($_GET['start_time']) . "'";
        }

        if ($_GET['end_time']) {
            $where .= " AND addtime <= '" . strtotime($_GET['end_time']) . "'";
        }

        $list = $this->page->hashQuery("SELECT * FROM ###_withdraw_commission WHERE mid<>0 $where ORDER BY id DESC")->result_array();

        $total = $this->db->getstr("SELECT SUM(commission) as total  FROM ###_withdraw_commission WHERE mid<>0 $where");

        $this->smarty->assign('total', $total > 0 ? $total : '0.00');

        $this->smarty->assign('list', $list);

        $this->smarty->display('manage/member/withdraw_commission.html');

    }

    /**
     * 佣金提现编辑

     */

    function withdraw_commission_edit($id = "")
    {

        $row = $this->db->get("SELECT * FROM ###_withdraw_commission WHERE id = '$id'");

        if (isset($_POST['Submit']) && $row['status']==0) {
            $status = isset($_POST['status']) ? intval($_POST['status']) : 1;

            if ($status == 1) {//通过 添加资金记录和扣除冻结佣金

                if($row['type'] == 0){//请求微信企业转账接口
                    $this->load->library("wechattransfers");
                    $res = $this->wechattransfers->transfers($row);

                    if($res['result_code']=='SUCCESS'){
                        $this->db->update('member', "deduct_commission = deduct_commission - {$row['commission']}", array('mid' => $row['mid']));
                    }else{
                        $this->tip($res['return_msg'], array('inIframe' => true, 'type' => 1));
                        $this->exeJs("parent.com.xhide();parent.main.refresh()");
                        die();
                    }
                }else{
                    $this->db->update('member', "deduct_commission = deduct_commission - {$row['commission']}", array('mid' => $row['mid']));
                }

            } elseif ($status == 2) {//不通过  解冻冻结佣金

                $this->db->update('member', "commission = commission + {$row['commission']} , deduct_commission = deduct_commission - {$row['commission']}", array('mid' => $row['mid']));
            }

            $this->db->update('withdraw_commission', array('status' => $_POST['status']), array('id' => $id));

            $this->tip('操作成功', array('inIframe' => true));

            $this->exeJs("parent.com.xhide();parent.main.refresh()");

        }

        $this->smarty->assign('row', $row);

        $this->smarty->display('manage/member/withdraw_commission_edit.html');

    }

    /**
     * 删除会员公告

     */

    function del_msg()
    {

        $id = (int)$_POST['id'];

        if (!$id) {
            die;
        }

        admin_log('删除会员公告：' . $id);

        $this->db->delete('###_msg', array('id' => $id));

        $this->tip('删除成功', array('type' => 1));

    }

    /**
     * 批量删除与恢复会员
     * Feng
     * 2016-03-29
     *
     */

    function batch_edit()
    {

        $type = isset($_POST['type']) ? $_POST['type'] : 'delete';

        if ($type == 'delete') {
            $msg = "删除成功！";
            $status = 0;
        } else {
            $msg = "恢复成功！";
            $status = 1;
        }

        $ids = $_POST['ids'];

        if (!$ids) {
            echo "请选择用户！";
            exit;
        }

        $sql = "update ###_member set status=$status where mid in ({$ids})";
        $this->db->query($sql);

        echo $msg;
        exit;

    }

    /**
     *
     * 意见反馈
     * Feng
     * 2016-04-27
     *
     */

    function opinion($page = 1)
    {
        $where = " where 1 ";

        $_GET['k'] = isset($_GET['k']) ? $_GET['k'] : '';
        $_GET['q'] = isset($_GET['q']) ? $_GET['q'] : '';
        $_GET['start_time'] = isset($_GET['start_time']) ? $_GET['start_time'] : '';
        $_GET['end_time'] = isset($_GET['end_time']) ? $_GET['end_time'] : '';

        if (!empty($_GET['q'])) {

            $where .= " AND " . trim($_GET['k']) . " LIKE '%" . trim($_GET['q']) . "%'";

        }

        if ($_GET['start_time']) {
            $where .= " AND c_time >= '" . strtotime($_GET['start_time']) . "'";
        }

        if ($_GET['end_time']) {
            $where .= " AND c_time <= '" . strtotime($_GET['end_time']) . "'";
        }

        $this->load->model('page');

        $_GET['page'] = intval($page);

        $this->page->set_vars(array(

            'per' => (int)$this->common['page_listrows'],

        ));

        $list = $this->page->hashQuery("SELECT * FROM ###_member_opinion $where ORDER BY status ASC,id DESC")->result_array();

        $this->smarty->assign('list', $list);

        $this->smarty->display('manage/member/opinion.html');

    }

    /**
     *
     * 意见反馈详情
     * Feng
     * 2016-04-27
     *
     */
    function opinion_edit($id = '')
    {
        if (isset($_POST['Submit'])) {
            $id = $_POST['id'];
            $status = $_POST['status'];
            $res = $this->db->update('member_opinion', array('status' => $status), array("id" => $id));
            $this->tip('操作成功', array('inIframe' => true));
            $this->exeJs("parent.com.xhide();parent.main.refresh()");
        }
        $sql = "select * from ###_member_opinion where id='{$id}' ";

        $row = $this->db->get($sql);

        $this->smarty->assign('row', $row);

        $this->smarty->display('manage/member/opinion_edit.html');

    }

    /**
     *
     * 意见反馈删除
     * Feng
     * 2016-04-27
     *
     */
    function opinion_del($id = '')
    {

        if ($id) {

            $res = $this->db->delete('member_opinion', array("id" => $id));

            $this->tip('删除成功', array('inIframe' => true));

            $this->exeJs("parent.com.xhide();parent.main.refresh()");

        }

    }

    /**
     *
     * 发送红包
     * Feng
     * 2016-05-03
     *
     */
    function send_redpack($mids = '')
    {
        if (isset($_POST['Submit'])) {
            $post = $_POST['post'];
            $money = $post['money'] * 100;
            if ($money <= 0) {
                $this->tip('请输入正确的金额', array('inIframe' => true));
                exit;
            }

            $mids = $post['mid'];
            $mid_array = explode(",", $mids);
            foreach ($mid_array as $mid) {
                $openid = $this->db->getstr("select openid from ###_oauth where type=0 and mid=".$mid,"openid");
                $info = $this->member->member_info($mid, "username");
                if ($openid == '') {
                    continue;
                    //$this->tip('该用户未绑定微信', array('inIframe' => true));$this->exeJs("parent.com.xhide();parent.main.refresh()");
                }

                $billno = date("YmdHis") . $mid;
                $totalAmount = $money;
                $sendName = !empty($post['sendName']) ? $post['sendName'] : C('site_name');
                $wishing = !empty($post['wishing']) ? $post['wishing'] : C('site_name');
                $remark = !empty($post['remark']) ? $post['remark'] : C('site_name');
                $actName = !empty($post['actName']) ? $post['actName'] : C('site_name');
                $this->load->library('wechatredpack');
                $res = $this->wechatredpack->sendRedPack($openid, $billno, $totalAmount, $sendName, $wishing, $remark, $actName);
                if($res['error']==0){
                    $insert_array['mid'] = $mid;
                    $insert_array['username'] = $info['username'];
                    $insert_array['mch_billno'] = $billno;
                    $insert_array['sendname'] = $sendName;
                    $insert_array['amount'] = $money;
                    $insert_array['actname'] = $actName;
                    $insert_array['wishing'] = $wishing;
                    $insert_array['remark'] = $remark;
                    $insert_array['status'] = 1;
                    $insert_array['add_time'] = RUN_TIME;
                    $this->db->insert('member_redpack', $insert_array);
                }
            }
            //var_dump($res);exit;
            $this->tip('发送成功', array('inIframe' => true));
            $this->exeJs("parent.com.xhide();");
        }
        if ($mids) {
            $row = $this->member->member_info($mids, "mid,username");
            $this->smarty->assign('row', $row);
            $this->smarty->display('manage/member/send_redpack.html');
        }

    }

    /**
     *
     * 发送优惠券
     * Feng
     * 2016-05-19
     *
     */
    function send_coupon($mids = '')
    {
        if (isset($_POST['Submit'])) {
            $post = $_POST['post'];
            if (empty($post['couponid'])) {
                $this->tip('请选择优惠券', array('inIframe' => true));
                exit;
            }
            $this->load->model("coupon");
            $res = $this->coupon->sendMany($post['mid'], $post['couponid']);
            if ($res) {
                $this->tip('发送成功', array('inIframe' => true));
                $this->exeJs("parent.com.xhide();");
            } else {
                $this->tip('发送失败', array('inIframe' => true));
            }
        }

    }

    /**
     *
     * 发送优惠券
     * Feng
     * 2016-05-19
     *send_msg($mid, $content, $type = 0, $title = '系统消息')
     */
    function send_message($mids = '')
    {
        if (isset($_POST['Submit'])) {
            $post = $_POST['post'];
            if (empty($post['title'])) {
                $this->tip('标题不能为空', array('inIframe' => true));
                exit;
            }
            if (empty($post['content'])) {
                $this->tip('内容不能为空', array('inIframe' => true));
                exit;
            }
            $msg = isset($post['msg']) ? intval($post['msg']) : 0;
            $sms = isset($post['sms']) ? intval($post['sms']) : 0;
            $wechat = isset($post['wechat']) ? intval($post['wechat']) : 0;
            $mail = isset($post['mail']) ? intval($post['mail']) : 0;
            $this->load->model("template_msg");
            $msgParams = array(
                'type' => 1,
                'title' => $post['title'],
                'content' => $post['content'],
                'msg' => $msg,
                'sms' => $sms,
                'wechat' => $wechat,
                'mail' => $mail,
            );
            $res = $this->template_msg->inQueue(0, $post['mid'], $msgParams);

            //if ($res) {
            $this->tip('发送成功', array('inIframe' => true));
            $this->exeJs("parent.com.xhide();");
            //} else {
            //	$this->tip('发送失败', array('inIframe' => true));
            //}
        }

    }

    /**
     * 发送微信推文
     */
    function send_wxtemplate()
    {
        if (isset($_POST['Submit'])) {
            $post = $_POST['post'];

            // 微信模版消息
            //wxtemplate_action start
            $this->load->model('wxtemplate');
            $msgParams = $post['params'];
            //wxtemplate_action end

            $mid_array = explode(",",$post['mid']);
            if($mid_array){
                foreach($mid_array as $v){
                    $this->wxtemplate->inQueue($v,$post['nid'],$msgParams);
                }
            }
            $this->tip('发送成功', array('inIframe' => true));
            $this->exeJs("parent.com.xhide();");
        }

    }

    /**
     *
     * 下级会员
     * Feng
     * 2016-06-12
     *
     */
    function ivt_level($mid)
    {

        $this->load->model("agent");
        $list = $this->agent->getInviteLevelList($mid);
        $total = 0;
        foreach ($list as $k => $v) {
            foreach ($v as $_k => $_v) {
                $sql = "select sum(commission) as num from ###_commission where mid={$mid} and ivt_mid={$_v['mid']}";
                $list[$k][$_k]['commission_total'] = $this->db->getstr($sql, "num");
            }
            $total += count($v);
        }
        //echo "<pre>";print_r($list);exit;
        $this->smarty->assign('total', $total);
        $this->smarty->assign('list', $list);
        $this->smarty->display('manage/member/ivt_level.html');

    }

    /**
     * 上传头像
     */
    function avatar($mid)
    {
        $mid = intval($mid);
        if($_POST['name']){
            $file = trim($_POST['name']);
            $this->load->model('upload');
            $thumb = array('thumb'=>array('width'=>320,'height'=>320));
            //$thumb = array();
            $pic = $this->upload->save_image('file', 'photo', $thumb,$mid);
            $name = substr ( strrchr ( $pic ,  "/" ),  1 );
            $temp = $this->db->getstr("select photo from ###_member_detail where mid=".$mid);
            S("H_MEMBER_DETAIL_" . $mid, null);
            $res = $this->db->update('member_detail',array('photo'=>$pic),array('mid'=>$mid));
            if($res && $temp{0}=='/'){
                @unlink(RootDir.'web'.$temp);
            }
            $pic = yunurl($pic);
            echo json_encode(array("error"=>0,"pic"=>$pic,"name"=>$name));exit;
        }
        $avatar = $this->db->getstr("select photo from ###_member_detail where mid=".$mid,"photo");
        $avatar = yunurl($avatar);
        $this->smarty->assign('mid', $mid);
        $this->smarty->assign('avatar', $avatar);
        $this->smarty->display('manage/member/avatar.html');

    }

}