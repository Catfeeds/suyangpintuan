<?php
//include_once AppDir . "includes/modules/payment/wxpay.php";
include_once AppDir . "includes/modules/payment/wxpay/lib/WxPay.Api.php";

class Wechatapprefund_Library extends Lowxp_Model {

	public function __construct() {}

	/**
	 * 微信退款
	 * @param  array  $refund_array  退款数组refund_log
	 * @return array                  json结果
	 */
	public function refund($refund_array,$payment='wxpay') {

        include_once AppDir . "includes/modules/payment/wxpayapp.php";

		$conf = new WxPayConfig();

		$input = new WxPayRefund();

		$input->SetOut_trade_no($refund_array['out_trade_no']);

		$input->SetOut_refund_no($refund_array['out_refund_no']);

		$input->SetTotal_fee($refund_array['order_amount'] * 100);

		$input->SetRefund_fee($refund_array['order_amount'] * 100);

		$input->SetOp_user_id($conf->mchid);
		try {
			$result = WxPayApi::refund($input);

		} catch (Exception $e) {
			$result = array(
				'return_code' => 'ERROR',
				'return_msg'  => $e->getMessage(),
			);
		}

		return $result;
	}

	/**
	 * 微信退款查询
	 * @param  string  $out_refund_no      退款单号
	 * @return array                json结果
	 */
	public function refundQuery($out_refund_no = '4007792001201612294373396806') {

		$input = new WxPayRefundQuery();

		$input->SetOut_refund_no($out_refund_no);

		$result = WxPayApi::refundQuery($input);

		return $result;
	}

}

?>