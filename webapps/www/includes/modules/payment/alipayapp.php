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
        array('name' => 'private_key',               'type' => 'text',   'value' => ''),
        array('name' => 'public_key',               'type' => 'text',   'value' => ''),
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
        require_once dirname(__FILE__) . "/alipayapp/AopClient.php";
        require_once dirname(__FILE__) . "/alipayapp/AlipayTradeAppPayRequest.php";
        $aop = new AopClient;
        $aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $aop->appId = $payment['alipay_partner'];
        $aop->rsaPrivateKey = $payment['private_key'];
        $aop->format = "json";
        $aop->charset = "UTF-8";
        $aop->signType = "RSA2";
        $aop->alipayrsaPublicKey = $payment['public_key'];
        //实例化具体API对应的request类,类名称和接口名称对应,当前调用接口名称：alipay.trade.app.pay
        $request = new AlipayTradeAppPayRequest();
        //SDK已经封装掉了公共参数，这里只需要传入业务参数
        $bizcontent = json_encode(array(
            'body'=>'在线支付',
            'subject'=>$order['order_sn'],
            'out_trade_no'=>$order['order_sn'].$order['log_id'],//此订单号为商户唯一订单号
            'total_amount'=> $order['order_amount'],//保留两位小数
            'product_code'=>'QUICK_MSECURITY_PAY'
        ));
        $request->setNotifyUrl(RootUrl . 'welcome/apprespond/alipayapp');
        $request->setBizContent($bizcontent);
        //这里和普通的接口调用不同，使用的是sdkExecute
        $response = $aop->sdkExecute($request);

        return $response;

        //htmlspecialchars是为了输出到页面时防止被浏览器将关键参数html转义，实际打印到日志以及http传输不会有这个问题
        echo htmlspecialchars($response);//就是orderString 可以直接给客户端请求，无需再做处理。
        exit;
    }

    /**
     * 响应操作
     */
    function respond()
    {
        require_once dirname(__FILE__) . "/alipayapp/AopClient.php";
        require_once dirname(__FILE__) . "/alipayapp/SignData.php";
        //require_once dirname(__FILE__) . "/alipayapp/AlipayTradeQueryRequest.php";
        $payment_info  = get_payment("alipayapp");
        $payment = unserialize_config($payment_info['pay_config']);
        $aop = new AopClient;
        $aop->alipayrsaPublicKey = $payment['public_key'];
        $flag = $aop->rsaCheckV1($_POST, NULL, "RSA2");
		//$string = "";
		//foreach($_POST as $k=>$v){
		//	$string .= $k."=".$v."&";
		//}
		//file_put_contents("./aopdata.log",$string,FILE_APPEND);
		if($flag){
			//file_put_contents("./aopdata.log",$payment['public_key']."111111",FILE_APPEND);
		}else{
			//file_put_contents("./aopdata.log",$payment['public_key']."222222",FILE_APPEND);
		}
        //print_r($payment['public_key']);
        //if($flag){
            $order_sn = str_replace($_POST['subject'], '', $_POST['out_trade_no']);
            $order_sn = trim(addslashes($order_sn));
            if (!check_money($order_sn, $_POST['total_amount']))
            {
                return false;
            }
            if($_POST['trade_status']=='TRADE_SUCCESS'){
                order_paid($order_sn,'','',$_POST['trade_no']);
                echo 'success';
                die;
            }
        //}else{
        //    return false;
        //}

        /*$aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = $payment['alipay_partner'];
        $aop->rsaPrivateKey = $payment['private_key'];
        $aop->alipayrsaPublicKey=$payment['public_key'];
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA2';
        $aop->postCharset='UTF-8';
        $aop->format='json';
        $request = new AlipayTradeQueryRequest ();
        $request->setBizContent(json_encode([
            'out_trade_no'=>$_POST['out_trade_no'],
            'trade_no'=>$_POST['trade_no']
        ]));
        $result = $aop->execute($request);
        var_dump($result);exit;
        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";

        $resultCode = $result->$responseNode->code;

        if(!empty($resultCode)&&$resultCode == 10000){
            $order_sn = str_replace($_POST['subject'], '', $_POST['out_trade_no']);
            $order_sn = trim(addslashes($order_sn));
            order_paid($order_sn,'','',$_POST['trade_no']);
            echo 'success';
            die;
        } else {
            return false;
        }*/
    }
}

?>