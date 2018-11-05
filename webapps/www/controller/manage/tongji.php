<?php
/**
 * 225 商城数据统计
 * ============================================================================
 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。
 * 网站地址: http://www.lnest.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 */

class tongji extends Lowxp{

    function __construct(){
        #按钮
        $this->btnMenu = array(
            0=>array('url'=>'#!tongji/money','name'=>'资金统计'),
        );
        parent::__construct();
    }

    #资金统计
    function money(){
        $data = array();

        #检索
        $condition = ' WHERE 1 ';
        $_GET['start_time'] = isset($_GET['start_time'])?trim($_GET['start_time']):date('Y-m-01');
        $_GET['end_time'] = isset($_GET['end_time'])?trim($_GET['end_time']):date('Y-m-d');
        $start_time = strtotime($_GET['start_time']." 00:00:00");
        $end_time = strtotime($_GET['end_time']." 23:59:59");
        $this->smarty->assign($_GET);
        $where = " AND logtime>=$start_time AND logtime<=$end_time";

        #充值
        $sql = "SELECT IFNULL(SUM(user_money),0) AS user_money FROM ###_account_log WHERE stage IN('".ACT_SAVING."') AND user_money>0".$where;
        $data['recharge01'] = $this->db->getstr($sql);
        $sql = "SELECT IFNULL(SUM(user_money),0) AS user_money FROM ###_account_log WHERE stage IN('".ACT_ADJUSTING."') AND user_money>0.$where";
        $data['recharge02'] = $this->db->getstr($sql);
        $data['recharge'] = $data['recharge01']+$data['recharge02'];
        #提现
        $sql = "SELECT IFNULL(SUM(user_money),0) AS user_money FROM ###_account_log WHERE stage IN('".ACT_DRAWING."') AND user_money<0".$where;
        $data['withdraw01'] = $this->db->getstr($sql);
        $sql = "SELECT IFNULL(SUM(user_money),0) AS user_money FROM ###_account_log WHERE stage IN('".ACT_ADJUSTING."') AND user_money<0".$where;
        $data['withdraw02'] = $this->db->getstr($sql);
        $data['withdraw'] = $this->db->getstr($sql);
        #交易余额
        $sql = "SELECT IFNULL(SUM(user_money),0) AS user_money FROM ###_account_log WHERE stage IN('".ACT_ORDER."','".ACT_DB."') AND user_money<0".$where;
        $data['order'] = $this->db->getstr($sql);
        #可用余额,冻结金额
        $sql = "SELECT IFNULL(SUM(user_money),0) AS user_money,IFNULL(SUM(frozen_money),0) AS frozen_money,IFNULL(SUM(pay_points),0) AS pay_points,IFNULL(SUM(db_points),0) AS db_points FROM ###_account_log WHERE 1".$where;
        $member_money = $this->db->get($sql);
        $data['user_money'] = $member_money['user_money'];
        $data['frozen_money'] = $member_money['frozen_money'];
        $data['pay_points'] = $member_money['pay_points'];
        $data['db_points'] = $member_money['db_points'];
        #冻结款支付
        $sql = "SELECT IFNULL(SUM(frozen_money),0) AS frozen_pay FROM ###_account_log WHERE stage IN('".ACT_DOP."') AND frozen_money<0".$where;
        $data['frozen_pay'] = $this->db->getstr($sql);
        #余额兑换夺宝币
        $sql = "SELECT IFNULL(SUM(user_money),0) AS user_money FROM ###_account_log WHERE stage IN('".ACT_CHANGE."') AND user_money<0".$where;
        $data['change'] = $this->db->getstr($sql);
        #余额核对
        $data['tj_money'] = $data['recharge']-(abs($data['withdraw'])+abs($data['order'])+abs($data['user_money'])+abs($data['frozen_money'])+abs($data['change'])+abs($data['frozen_pay']));

        $this->smarty->assign('data',$data);
        $this->smarty->display('manage/tongji/money.html');
    }

}