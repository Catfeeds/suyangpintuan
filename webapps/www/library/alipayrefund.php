<?php
include_once AppDir . "includes/modules/payment/alipay_wap.php";
include_once AppDir . "includes/modules/payment/alipay_wap/lib/alipay_submit.class.php";

class Alipayrefund_Library extends Lowxp_Model{

    public function __construct() {}

    /**
     * 微信退款
     * @param  array  $refund_array  退款数组refund_log
     * @return array                  json结果
     */
    public function refund($refund_array = array()) {

        $payment   = $this->get_alipay();
        $parameter = $this->getAlipayConf($payment);
        $parameter['batch_no'] = $refund_array['out_refund_no'];
        $parameter['refund_date'] = date("Y-m-d H:i:s",RUN_TIME);
        $parameter['batch_num'] = 1;
        $parameter['detail_data'] = $refund_array['trade_no'].'^'.$refund_array['order_amount'].'^协商退款';
        $alipaySubmit = new AlipaySubmit($payment);
        $html_text = $alipaySubmit->buildRequestHttp($parameter);
        $doc = new DOMDocument();
        $doc->loadXML($html_text);
        $is_success = $doc->getElementsByTagName('is_success')->item(0)->nodeValue;
        return $is_success=='T'?true:false;
    }
    

    /**
     * 构造要传给lib的配置参数
     * @param array $payment
     * @return array
     */
    protected function getAlipayConf($payment) {
        $parameter = array(
            "service" => "refund_fastpay_by_platform_nopwd",
            "partner" => trim($payment['alipay_partner']),
            "notify_url"	=> RootUrl . 'welcome/alipayrefund',
            "batch_no"	=> '',
            "refund_date"	=> '',
            "batch_num"	=> 1,
            "detail_data"	=> '',
            "_input_charset"	=> strtolower('utf-8')
        );
        return $parameter;
    }

    function get_alipay() {

        global $lowxp;

        $sql     = "SELECT * FROM ###_payment WHERE pay_code = 'alipay_wap' AND enabled = '1' LIMIT 1";
        $payment = $lowxp->db->get($sql);

        if ($payment) {
            $config_list = unserialize($payment['pay_config']);
            foreach ($config_list AS $config) {
                $payment[$config['name']] = $config['value'];
            }
        }
        $payment["partner"]	= $payment['alipay_partner'];
        $payment["key"]	    = $payment['alipay_key'];
        $payment["cacert"]	= AppDir . "includes/modules/payment/alipay_wap/cacert.pem";
        $payment['transport']    = 'http';
        $payment['input_charset']= strtolower('utf-8');
        $payment['sign_type']    = strtoupper('MD5');
        return $payment;
    }

}

?>