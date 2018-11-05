<?php
include_once AppDir . "includes/modules/payment/wxpay.php";
include_once AppDir . "includes/modules/payment/wxpay/lib/WxPay.Api.php";

class Wechattransfers_Library extends Lowxp_Model{

    public function __construct() {}

    /**
     * 微信转账
     * @param  array  $transfers_array  退款数组transfers_log
     * @return array                  json结果
     */
    public function transfers($transfers_array = array()) {

        $conf = new WxPayConfig();

        $input = new WxPayTransfers();

        $trade_no = date("YmdHis",RUN_TIME)."X".$transfers_array['id'];
        $input->SetPartner_trade_no($trade_no);

        $input->SetOpenid($transfers_array['openid']);

        $input->SetCheck_Name("NO_CHECK");

        $input->SetAmount($transfers_array['amount']*100);

        $input->SetDesc('企业转账');

        $result = WxPayApi::transfers($input);

        return $result;
    }
}

?>