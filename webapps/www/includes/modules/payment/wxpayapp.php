<?php
if (!defined('App'))die('Hacking attempt');
include_once(AppDir . 'function/payment.php');
$payment_lang = AppDir . 'includes/languages/payment/wxpayapp.php';
include_once(AppDir."includes/modules/payment/wxpay/lib/WxPay.Api.php");
include_once(AppDir."includes/modules/payment/wxpay/demo/WxPay.AppPay.php");
include_once(AppDir."includes/modules/payment/wxpay/demo/log.php");
include_once(AppDir."includes/modules/payment/wxpay/lib/WxPay.Notify.php");

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
    $modules[$i]['desc']    = 'wxpayapp_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '1';

    /* 是否支持在线支付 */
    $modules[$i]['is_online']  = '1';

    /* 作者 */
    $modules[$i]['author']  = 'CAIYA TEAM';

    /* 网址 */
    $modules[$i]['website'] = 'http://wx.qq.com';

    /* 版本号 */
    $modules[$i]['version'] = '0.0.1';

    /* 配置信息 */
    $modules[$i]['config']  = array(
        array('name' => 'wxpay_app_id',           'type' => 'text',   'value' => ''),
        array('name' => 'wxpay_app_secret',       'type' => 'text',   'value' => ''),
        array('name' => 'wxpay_mchid',        'type' => 'text',   'value' => ''),
        array('name' => 'wxpay_key',       'type' => 'text',   'value' => ''),
        array('name' => 'cert_pem', 'type' => 'file', 'value' => ''),
        array('name' => 'key_pem', 'type' => 'file', 'value' => ''),
    );
    return;
}
if(!class_exists('WxPayConfig')){
    class WxPayConfig {

        //=======【基本信息设置】=====================================

        //

        /**

         * TODO: 修改这里配置为您自己申请的商户信息

         * 微信公众号信息配置

         *

         * APPID：绑定支付的APPID（必须配置，开户邮件中可查看）

         *

         * MCHID：商户号（必须配置，开户邮件中可查看）

         *

         * KEY：商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）

         * 设置地址：https://pay.weixin.qq.com/index.php/account/api_cert

         *

         * APPSECRET：公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置），

         * 获取地址：https://mp.weixin.qq.com/advanced/advanced?action=dev&t=advanced/dev&token=2005451881&lang=zh_CN

         * @var string

         */

        public $appid = '';

        public $mchid = '';

        public $key = '';

        public $appsecret = '';

        public $notifyurl = '';

        public $successurl = '';

        public $sslcert_path = '';

        public $sslkey_path = '';

        function __construct() {

            $payment = get_payment('wxpayapp');

            if (isset($payment)) {

                $this->appid = $payment['wxpay_app_id'];

                $this->appsecret = $payment['wxpay_app_secret'];

                $this->mchid = $payment['wxpay_mchid'];

                $this->key = $payment['wxpay_key'];

                $this->notifyurl = url('/welcome/respond');

                $this->successurl = url('/welcome/respond/');

                $payment['cert_pem'] = RUNTIME_PATH .'cacert/wxpayapp/apiclient_cert.pem';

                $payment['key_pem'] = RUNTIME_PATH .'cacert/wxpayapp/apiclient_key.pem';

                $this->sslcert_path = $payment['cert_pem'];

                $this->sslkey_path = $payment['key_pem'];

                defined('SSLCERT') || define('SSLCERT', $payment['cert_pem']);

                defined('SSLKEY') || define('SSLKEY', $payment['key_pem']);

            }

        }

        //=======【证书路径设置】=====================================

        /**

         * TODO：设置商户证书路径

         * 证书路径,注意应该填写绝对路径（仅退款、撤销订单时需要，可登录商户平台下载，

         * API证书下载地址：https://pay.weixin.qq.com/index.php/account/api_cert，下载之前需要安装商户操作证书）

         * @var path

         */

        //const SSLCERT_PATH = '../cert/apiclient_cert.pem';
        const SSLCERT_PATH = SSLCERT;

        //const SSLKEY_PATH = '../cert/apiclient_key.pem';
        const SSLKEY_PATH = SSLKEY;

        //=======【curl代理设置】===================================

        /**

         * TODO：这里设置代理机器，只有需要代理的时候才设置，不需要代理，请设置为0.0.0.0和0

         * 本例程通过curl使用HTTP POST方法，此处可修改代理服务器，

         * 默认CURL_PROXY_HOST=0.0.0.0和CURL_PROXY_PORT=0，此时不开启代理（如有需要才设置）

         * @var unknown_type

         */

        const CURL_PROXY_HOST = "0.0.0.0"; //"10.152.18.220";

        const CURL_PROXY_PORT = 0; //8080;

        //=======【上报信息配置】===================================

        /**

         * TODO：接口调用上报等级，默认紧错误上报（注意：上报超时间为【1s】，上报无论成败【永不抛出异常】，

         * 不会影响接口调用流程），开启上报之后，方便微信监控请求调用的质量，建议至少

         * 开启错误上报。

         * 上报等级，0.关闭上报; 1.仅错误出错上报; 2.全量上报

         * @var int

         */

        const REPORT_LEVENL = 1;

    }
}






/**
 * 类
 */
class wxpayapp
{
    /**
     * 生成支付代码
     * @param   array   $order      订单信息
     * @param   array   $payment    支付方式信息
     */
    function get_code($order, $payment){
        $input = new WxPayUnifiedOrder();
        $conf = new WxPayConfig();
        $tools = new AppPay();
        $returnrul = $conf->successurl.$order["log_id"];

        //记录支付订单号
        $order_no = date("YmdHis").'_'.$order['log_id'];
        //insert_order_sn($order['log_id'], array('order_no'=>$order_no));

        //初始化日志
        //$logHandler= new CLogFileHandler(AppDir."includes/modules/payment/wxpay/logs/app".date('Y-m-d').'.log');

        //$log = Log::Init($logHandler, 15);
        $input->SetBody($order['order_sn']);
        $input->SetAttach($order['log_id']);
        $input->SetOut_trade_no($order_no);
        $input->SetTotal_fee($order['order_amount']*100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetNotify_url(RootUrl . 'welcome/apprespond/wxpayapp');
        $input->SetTrade_type("APP");
        $order_data = WxPayApi::unifiedOrder($input);

        $order_data['timestamp'] = time();
        $str='appid='.$conf->appid.'&noncestr='.$order_data['nonce_str'].'&package=Sign=WXPay&partnerid='.$conf->mchid.'&prepayid='.$order_data['prepay_id'].'&timestamp='.$order_data['timestamp'];

        //③ 重新生成签名，并将结果返回给客户端
        $order_data['sign'] = strtoupper(md5($str.'&key='.$conf->key));
        $parameter= array(
            'appid'=> $order_data['appid'],
            'partnerid'=>$order_data['mch_id'],
            'prepayid'=>$order_data['prepay_id'],
            'wxpackage'=>'Sign=WXPay',//原package 安卓不能解析
            'noncestr'=>$order_data['nonce_str'],
            'timestamp'=>$order_data['timestamp'],
            'sign'=>$order_data['sign']
        );
        return $parameter;



        //$appApiParameters = $tools->GetAppApiParameters($order_form);
        /*$appApiParameters['appid'] = $conf->appid;
        $appApiParameters['mchid'] = $conf->mchid;
        $appApiParameters['appkey'] = $conf->appsecret;
        $appApiParameters['notifyurl'] = $conf->notifyurl;
        $appApiParameters['totalfee'] = intval($order['order_amount']*100);
        $appApiParameters['tradeno'] = $order_no;
        $appApiParameters['attach'] = $order['log_id'];*/
        var_dump($appApiParameters);
        return $appApiParameters;
    }



    /**
     * 接受通知处理订单。
     * @param undefined $log_id
     * 20141125
     */
    function respond(){

        $notify = new NativeNotifyCallBack();

        $notify->Handle(true);
    }

}

class NativeNotifyCallBack extends WxPayNotify {

    public function unifiedorder($openId, $product_id) {
        //统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody("test");
        $input->SetAttach("test");
        $input->SetOut_trade_no(WxPayConfig::MCHID . date("YmdHis"));
        $input->SetTotal_fee("1");
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
        $input->SetTrade_type("NATIVE");
        $input->SetOpenid($openId);
        $input->SetProduct_id($product_id);
        $result = WxPayApi::unifiedOrder($input);
        //Logwx::DEBUG("unifiedorder:" . json_encode($result));
        return $result;
    }

    //查询订单
    public function Queryorder($transaction_id) {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = WxPayApi::orderQuery($input);
        //Logwx::DEBUG("query:" . json_encode($result));
        if (array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS") {
            return true;
        }
        return false;
    }

    public function NotifyProcess($data, &$msg) {

        if (!array_key_exists("transaction_id", $data)) {
            $msg = "输入参数不正确";
            return false;
        }
        //查询订单，判断订单真实性
        if (!$this->Queryorder($data["transaction_id"])) {
            $msg = "订单查询失败";
            return false;
        }
        $log_id = explode('_', $data['out_trade_no']);
        $log_id = $log_id[1];
        order_paid($log_id);
        return true;
    }
}
?>