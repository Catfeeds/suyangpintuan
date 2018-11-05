<?php

/**
 * YeePay易宝插件
 */

if (!defined('App'))die('Hacking attempt');

include_once(AppDir . 'function/payment.php');
$payment_lang = AppDir . 'includes/languages/payment/yeepay.php';

if (file_exists($payment_lang))
{
    global $_LANG;
    include_once($payment_lang);
}

/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code']    = basename(__FILE__, '.php');

    /* 描述对应的语言项 */
    $modules[$i]['desc']    = 'yp_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online']  = '1';

    /* 作者 */
    $modules[$i]['author']  = 'LNEST TEAM';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.yeepay.com';

    /* 版本号 */
    $modules[$i]['version'] = '1.0.1';

    /* 配置信息 */
    $modules[$i]['config']  = array(
        /*array('name' => 'yp_account', 'type' => 'text', 'value' => ''),
        array('name' => 'yp_key',     'type' => 'text', 'value' => ''),*/
    );

    return;
}

/**
 * 类
 */
class yeepay
{
    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function yeepay()
    {
    }

    function __construct()
    {
        $this->yeepay();
    }

    /**
     * 生成支付代码
     * @param   array   $order  订单信息
     * @param   array   $payment    支付方式信息
     */
    function get_code($order, $payment)
    {
        include 'yeepay/common.php';
        $data_order_id    = $order['order_sn'];
        $data_amount      = number_format($order['order_amount'], 2, '.', '');
        $message_type     = 'Buy';
        $data_cur         = 'CNY';
        $product_id       = '';
        $product_cat      = '';
        $product_desc     = '';
        $address_flag     = '0';
        $data_return_url  = return_url(basename(__FILE__, '.php'));
        #支付通道编码
        ##默认为""，到易宝支付网关.若不需显示易宝支付的页面，直接跳转到各银行、神州行支付、骏网一卡通等支付页面，该字段可依照附录:银行列表设置参数值.

        #直连银行编码
        $bank             = (isset($order['bank']) && trim($order['bank'])) ? trim($order['bank']) : '';
        $pd_FrpId         = $bank;

        #应答机制
        ##默认为"1": 需要应答机制;
        $pr_NeedResponse  = "1";

        $mct_properties   = $order['log_id'];
        $MD5KEY = getReqHmacString($data_order_id,$data_amount,$data_cur,$product_id,$product_cat,$product_desc,$data_return_url,$mct_properties,$pd_FrpId,$pr_NeedResponse);

        $def_url  = "<form action='".$reqURL_onLine."' method='post'>";
        $def_url .= "<input type='hidden' name='p0_Cmd' value='".$message_type."'>";
        $def_url .= "<input type='hidden' name='p1_MerId' value='".$p1_MerId."'>";
        $def_url .= "<input type='hidden' name='p2_Order' value='".$data_order_id."'>";
        $def_url .= "<input type='hidden' name='p3_Amt' value='".$data_amount."'>";
        $def_url .= "<input type='hidden' name='p4_Cur' value='".$data_cur."'>";
        $def_url .= "<input type='hidden' name='p5_Pid' value='".$product_id."'>";
        $def_url .= "<input type='hidden' name='p6_Pcat' value='".$product_cat."'>";
        $def_url .= "<input type='hidden' name='p7_Pdesc' value='".$product_desc."'>";
        $def_url .= "<input type='hidden' name='p8_Url' value='".$data_return_url."'>";
        $def_url .= "<input type='hidden' name='p9_SAF' value='".$address_flag."'>";
        $def_url .= "<input type='hidden' name='pa_MP' value='".$mct_properties."'>";
        $def_url .= "<input type='hidden' name='pd_FrpId' value='".$pd_FrpId."'>";
        $def_url .= "<input type='hidden' name='pr_NeedResponse' value='".$pr_NeedResponse."'>";
        $def_url .= "<input type='hidden' name='hmac' value='".$MD5KEY."'>";
        $def_url .= "<input type='submit' class='payBtn' value='" . $GLOBALS['_LANG']['pay_button'].($bank ? '（银行直连）' : '')."'>";
        $def_url .= "</form>";

        return $def_url;
    }

    /**
     * 响应操作
     */
    function respond()
    {
        include 'yeepay/common.php';
        $return = getCallBackValue($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);

        #判断返回签名是否正确（True/False）
        $bRet = CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);
        #以上代码和变量不需要修改.

        #校验码正确.
        $v_result = false;
        if($bRet){
            if($r1_Code=="1"){
                #	需要比较返回的金额与商家数据库中订单的金额是否相等，只有相等的情况下才认为是交易成功.
                #	并且需要对返回的处理进行事务控制，进行记录的排它性处理，在接收到支付结果通知后，判断是否进行过业务逻辑处理，不要重复进行业务逻辑处理，防止对同一条交易重复发货的情况发生.
                if($r9_BType=="1"){
                    order_paid($_REQUEST['r8_MP']);
                    $v_result = true;
                }elseif($r9_BType=="2"){
                    #如果需要应答机制则必须回写流,以success开头,大小写不敏感.
                    $v_result = true;
                    order_paid($_REQUEST['r8_MP']);
                    $v_result = true;
                }
            }

        }
        return $v_result;
    }
}

if (!function_exists("hmac"))
{
    function hmac($data, $key)
    {
        $key  = @iconv('GB2312', 'UTF8', $key);
        $data = @iconv('GB2312', 'UTF8', $data);

        $b = 64; // byte length for md5
        if (strlen($key) > $b)
        {
            $key = pack('H*', md5($key));
        }

        $key    = str_pad($key, $b, chr(0x00));
        $ipad   = str_pad('', $b, chr(0x36));
        $opad   = str_pad('', $b, chr(0x5c));
        $k_ipad = $key ^ $ipad ;
        $k_opad = $key ^ $opad;

        return md5($k_opad . pack('H*', md5($k_ipad . $data)));
    }
}

?>