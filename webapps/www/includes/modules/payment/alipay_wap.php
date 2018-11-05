<?php

/**
 * ECSHOP 支付宝WAP插件
 * 2015-09-27 更新最新的支付宝wap接口
 */

if (!defined('App')) {
    die('Hacking attempt');
}

include_once AppDir . 'function/payment.php';
$payment_lang = AppDir . 'includes/languages/payment/alipay_wap.php';

if (file_exists($payment_lang)) {
    global $_LANG;

    include_once $payment_lang;
}

/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE) {
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code'] = basename(__FILE__, '.php');

    /* 描述对应的语言项 */
    $modules[$i]['desc'] = 'alipay_wap_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod'] = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online'] = '1';

    /* 作者 */
    $modules[$i]['author'] = 'jimmy';

    /* 网址 */
    $modules[$i]['website'] = 'http://x3d.cnblogs.com';

    /* 版本号 */
    $modules[$i]['version'] = '0.4';

    /* 配置信息 共用?? */
    $modules[$i]['config'] = array(
        array('name' => 'alipay_remark', 'type' => 'text', 'value' => ''),
        array('name' => 'alipay_account', 'type' => 'text', 'value' => ''),
        array('name' => 'alipay_key', 'type' => 'text', 'value' => ''),
        array('name' => 'alipay_partner', 'type' => 'text', 'value' => ''),
        // array('name' => 'alipay_seller_id', 'type' => 'text', 'value' => ''),
        array('name' => 'alipay_pay_method', 'type' => 'select', 'value' => ''),
        array('name' => 'alipay_cacert', 'type' => 'file', 'value' => ''),
    );

    return;
}

/**
 * 类
 */
class alipay_wap {

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    // function alipay() {
    // }

    // function __construct() {
    // 	$this->alipay();
    // }

    /**
     * 生成支付代码
     * @param   array   $order      订单信息
     * @param   array   $payment    支付方式信息
     */
    function get_code($order, $payment) {

        //服务器异步通知页面路径
        $notify_url = return_url(basename(__FILE__, '.php'));
        //需http://格式的完整路径，不允许加?id=123这类自定义参数

        //页面跳转同步通知页面路径
        $call_back_url = return_url(basename(__FILE__, '.php'));
        //需http://格式的完整路径，不允许加?id=123这类自定义参数

        $alipay_conf = $this->getAlipayConf($payment);

        //基本参数
        $parameter = array(
            'service'        => 'alipay.wap.create.direct.pay.by.user',
            //合作身份者id，以2088开头的16位纯数字
            'partner'        => $alipay_conf['partner'],
            '_input_charset' => $alipay_conf['input_charset'],
            'sign_type'      => $alipay_conf['sign_type'],
            'notify_url'     => $notify_url,
            'return_url'     => $call_back_url,
            //业务参数
            'seller_id'      => $alipay_conf['partner'],
            'payment_type'   => '1',
            'out_trade_no'   => $order['log_id'],
            'subject'        => $alipay_conf['remark'] . ' 订单:' . $order['order_sn'],
            'total_fee'      => $order['order_amount'],
            //'show_url'	=> $show_url, //TODO
            //'body'	=> '',
            'it_b_pay'       => '1d',
            //'extern_token'	=> $extern_token,
        );

        //建立请求
        require_once dirname(__FILE__) . "/alipay_wap/lib/alipay_submit.class.php";
        $alipaySubmit = new AlipaySubmit($alipay_conf);
        $html_text    = $alipaySubmit->buildRequestForm($parameter, "get", "立即使用支付宝支付");
        return $html_text;
    }

    /**
     * 响应操作
     */
    function respond() {

        $payment     = get_payment($_REQUEST['code']);
        $alipay_conf = $this->getAlipayConf($payment);

        require_once dirname(__FILE__) . "/alipay_wap/lib/alipay_notify.class.php";

        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($alipay_conf);

        // die;
        // 无 post 为同步
        if (empty($_POST)) {
            // 同步回调;
            // code不是支付宝回传的信息
            unset($_GET['code']);

            $verify_result = $alipayNotify->verifyReturn();
            // $verify_result = 1; // debug 用

            if ($verify_result && $_GET['trade_status'] == 'TRADE_SUCCESS') {
                order_paid($_GET['out_trade_no'],'','',$_GET['trade_no']);
                com_exeJs('if (top.location != location){top.location.href = location.href;}');
                return true;
            } else {
                return false;
            }
        } else {
            // code不是支付宝回传的信息
            unset($_POST['code']);
            // 异步回调
            $verify_result = $alipayNotify->verifyNotify();
            if ($verify_result && $_POST['trade_status'] == 'TRADE_SUCCESS') {
                order_paid($_POST['out_trade_no'],'','',$_POST['trade_no']);
                echo 'success';
                die;
            }
        }
    }

    /**
     * 响应操作
     */
    function refund() {

        $payment     = get_payment("alipay_wap");
        $alipay_conf = $this->getAlipayConf($payment);
        require_once dirname(__FILE__) . "/alipay_wap/lib/alipay_notify.class.php";
        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($alipay_conf);
        $verify_result = $alipayNotify->verifyNotify();
        if($verify_result) {//验证成功

            //退款批次号
            $batch_no = $_POST['batch_no'];
            //必填

            //退款成功总数
            $success_num = $_POST['success_num'];
            //必填，0<= success_num<= batch_num

            //处理结果详情
            $result_details = $_POST['result_details'];
            $res = alipay_refund($result_details);
            //必填，详见“6.3 处理结果详情说明”

            //解冻结果明细
            $unfreezed_deta = $_POST['unfreezed_deta'];
            //格式：解冻结订单号^冻结订单号^解冻结金额^交易号^处理时间^状态^描述码

            //判断是否在商户网站中已经做过了这次通知返回的处理
            //如果没有做过处理，那么执行商户的业务程序
            //如果有做过处理，那么不执行商户的业务程序
            if($res){
                echo "success";		//请不要修改或删除
            }


            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");

            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        } else {
            //验证失败
            echo "fail";
        }

    }


    /**
     * 构造要传给lib的配置参数
     * @param array $payment
     * @return array
     */
    protected function getAlipayConf($payment) {
        return array(
            'partner'       => trim($payment['alipay_partner']),
            'seller_id'     => trim($payment['alipay_partner']),
            'remark'        => trim($payment['alipay_remark']),
            'key'           => trim($payment['alipay_key']),
            'transport'     => 'http',
            'cacert'           => dirname(__FILE__) . '/alipay_wap/cacert.pem',
            'private_key_path' => trim($payment['alipay_cacert']),
            'input_charset' => strtolower('utf-8'),
            'sign_type'     => strtoupper('md5'),
        );
    }
}
