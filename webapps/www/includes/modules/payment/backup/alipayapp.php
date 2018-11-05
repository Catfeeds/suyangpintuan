<?php

/**
 * 支付宝插件
 */

if (!defined('App'))die('Hacking attempt');

include_once(AppDir . 'function/payment.php');
$payment_lang = AppDir . 'includes/languages/payment/alipayapp.php';

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
    $modules[$i]['desc']    = 'alipayapp_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '1';

    /* 是否支持在线支付 */
    $modules[$i]['is_online']  = '1';

    /* 作者 */
    $modules[$i]['author']  = 'ECSHOP TEAM';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.alipay.com';

    /* 版本号 */
    $modules[$i]['version'] = '1.0.2';

    /* 配置信息 */
    $modules[$i]['config']  = array(
        array('name' => 'alipay_account',           'type' => 'text',   'value' => ''),
        array('name' => 'alipay_key',               'type' => 'text',   'value' => ''),
        array('name' => 'alipay_partner',           'type' => 'text',   'value' => '')
    );

    return;
}

/**
 * 类
 */
class alipayapp
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function __construct()
    {
        
    }

    /**
     * 生成支付代码
     * @param   array   $order      订单信息
     * @param   array   $payment    支付方式信息
     */
    function get_code($order, $payment)
    {
        if (!defined('ZZ_CHARSET'))
        {
            $charset = 'utf-8';
        }
        else
        {
            $charset = ZZ_CHARSET;
        }

        $parameter = array(
            'notify_url'        => return_url(basename(__FILE__, '.php')),
            //'return_url'        => return_url(basename(__FILE__, '.php')),
            'service'           => 'mobile.securitypay.pay',
            'payment_type'      => 1,
            '_input_charset'    => $charset,
            'partner'           => $payment['alipay_partner'],
            /* 业务参数 */
            'out_trade_no'      => $order['order_sn'] . $order['log_id'],
            'subject'           => $order['order_sn'],
            'seller_id'      => $payment['alipay_account'],
            'total_fee'             => $order['order_amount'],
            'body'          => '充值'
        );

        ksort($parameter);
        reset($parameter);

        $param = '';
        $sign  = '';

        foreach ($parameter AS $key => $val)
        {
            $param .= "$key=" .urlencode($val). "&";
            $sign  .= "$key=$val&";
        }
        $param = substr($param, 0, -1);
        $sign  = substr($sign, 0, -1). $payment['alipay_key'];
        //$sign  = substr($sign, 0, -1). ALIPAY_AUTH;
        $result = array();
        $result['partner'] = $parameter['partner'];
        $result['seller_id'] = $parameter['seller_id'];
        $result['out_trade_no'] = $parameter['out_trade_no'];
        $result['subject'] = $parameter['subject'];
        $result['body'] = $parameter['body'];
        $result['total_fee'] = $parameter['total_fee'];
        $result['notify_url'] = $parameter['notify_url'].$param. '&sign='.md5($sign).'&sign_type=MD5';
        $result = http_build_query($result);
        return $result;
    }

    /**
     * 响应操作
     */
    function respond()
    {

        if (!empty($_POST))
        {
            foreach($_POST as $key => $data)
            {
                $_GET[$key] = $data;
            }
        }
        $payment  = get_payment($_GET['code']);
        $seller_email = rawurldecode($_GET['seller_email']);
        $order_sn = str_replace($_GET['subject'], '', $_GET['out_trade_no']);
        $order_sn = trim(addslashes($order_sn));

        /* 检查支付的金额是否相符 */
        if (!check_money($order_sn, $_GET['total_fee']))
        {
            return false;
        }


        /* 检查数字签名是否正确 */
        ksort($_GET);
        reset($_GET);

        $sign = '';
        foreach ($_GET AS $key=>$val)
        {
            if ($key != 'sign' && $key != 'sign_type' && $key != 'code' && $key !='act'  && $key!='/welcome/respond')
            {
                $sign .= "$key=$val&";
            }
        }

        $sign = substr($sign, 0, -1) . $payment['alipay_key'];
        //$sign = substr($sign, 0, -1) . ALIPAY_AUTH;
        if (md5($sign) != $_GET['sign'])
        {
            return false;
        }

        if ($_GET['trade_status'] == 'WAIT_SELLER_SEND_GOODS')
        {
            /* 改变订单状态 */
            order_paid($order_sn, 2);
            return true;
        }
        elseif ($_GET['trade_status'] == 'TRADE_FINISHED')
        {
            /* 改变订单状态 */
            order_paid($order_sn);
            return true;
        }
        elseif ($_GET['trade_status'] == 'TRADE_SUCCESS')
        {
            /* 改变订单状态 */
            order_paid($order_sn, 2);
            return true;
        }
        else
        {
            return false;
        }
    }
}

?>