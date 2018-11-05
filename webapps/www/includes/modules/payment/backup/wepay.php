<?php

if (!defined('App')) {
	die('Hacking attempt');
}

include_once AppDir . 'function/payment.php';
$payment_lang = AppDir . 'includes/languages/payment/wepay.php';

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
	$modules[$i]['desc'] = 'wepay_desc';

	/* 是否支持货到付款 */
	$modules[$i]['is_cod'] = '0';

	/* 是否支持在线支付 */
	$modules[$i]['is_online'] = '1';

	/* 作者 */
	$modules[$i]['author'] = 'lnest';

	/* 网址 */
	$modules[$i]['website'] = 'https://wangyin.com.cn/wepay/pay';

	/* 版本号 */
	$modules[$i]['version'] = '0.1';

	/* 配置信息 */
	$modules[$i]['config'] = array(
		array(
			'name'  => 'merchantRemark',
			'type'  => 'text',
			'value' => '',
		),
		array(
			'name'  => 'merchantNum',
			'type'  => 'text',
			'value' => '',
		),
		array(
			'name'  => 'desKey',
			'type'  => 'text',
			'value' => '',
		),
		array(
			'name'  => 'md5Key',
			'type'  => 'text',
			'value' => '',
		),
	);

	return;
}

/**
 * 类
 */
class wepay {

	/**
	 * 生成支付代码
	 * @param   array   $order      订单信息
	 * @param   array   $payment    支付方式信息
	 */
	function get_code($order, $payment) {

		$param = array();

		// 1.0 不实用 des加密
		// 2.0使用 des加密 正式生产环节需要使用 2.0
		$param['version'] = '2.0';
		// 识别用户信息,支付成功后会调用successCallbackUrl返回给商户。
		// （注:商户可以记录这个token值，当用户再次支付的时候传入该token，用户无需再次输入银行卡信息 ，直接输入短息验证码进行支付。）
		// 这个根据需要决定是否保存token 到用户信息中 本例未做保存
		$param['token'] = '';
		// 商户号
		$param['merchantNum'] = $payment['merchantNum'];
		// 商户备注
		$param['merchantRemark'] = $payment['merchantRemark'];
		// 商城内部订单编号  一般为本订单的内部id
		$param['tradeNum'] = $order['log_id'];
		// 交易名称  这里务必获取商品名称 或者订单内的交易名称
		$param['tradeName'] = $payment['merchantRemark'] . ' 订单:' . $order['order_sn'];
		// 交易描述
		$param['tradeDescription'] = '订单:' . $order['order_sn'];
		// 交易时间
		$param['tradeTime'] = date('Y-m-d H:i:s', time());
		// 交易总额 单位分
		// 注意本系统传入的单位为元 京东接受的单位为分
		$param['tradeAmount'] = $order['order_amount'] * 100;
		// 货币类型  一般默认就写 "CNY"
		$param['currency'] = 'CNY';
		// 异步通知的接受地址
		$param['notifyUrl'] = url('/welcome/respond?code=wepay');
		// 成功以后的回调页面 可以指定任意页面 一般是订单列表或者支付结果;
		$param['successCallbackUrl'] = url('/welcome/respond?code=wepay');
		// 失败以后的回调页面
		$param['failCallbackUrl'] = url('/welcome/respond?code=wepay');

		// 测试回调地址
		// $param['notifyUrl']          = 'http://lvkh.com.cn/test/respond?code=wepay';
		// $param['successCallbackUrl'] = 'http://lvkh.com.cn/test/respond?code=wepay';
		// $param['failCallbackUrl']    = 'http://lvkh.com.cn/test/respond?code=wepay';

		// 签名开始
		include_once AppDir . 'includes/modules/payment/wepay/common/SignUtil.php';
		$mechantSign = SignUtil::sign($param);

		// 2.0版本
		if ($param['version'] == '2.0') {
			include_once AppDir . 'includes/modules/payment/wepay/common/DesUtils.php';

			// 商户des key
			$key = $payment['desKey'];
			// 计算后的密钥
			$desUtils = new DesUtils();

			$param['merchantRemark']     = $desUtils->encrypt($param['merchantRemark'], $key);
			$param['tradeNum']           = $desUtils->encrypt($param['tradeNum'], $key);
			$param['tradeName']          = $desUtils->encrypt($param['tradeName'], $key);
			$param['tradeDescription']   = $desUtils->encrypt($param['tradeDescription'], $key);
			$param['tradeTime']          = $desUtils->encrypt($param['tradeTime'], $key);
			$param['tradeAmount']        = $desUtils->encrypt($param['tradeAmount'], $key);
			$param['currency']           = $desUtils->encrypt($param['currency'], $key);
			$param['notifyUrl']          = $desUtils->encrypt($param['notifyUrl'], $key);
			$param['successCallbackUrl'] = $desUtils->encrypt($param['successCallbackUrl'], $key);
			$param['failCallbackUrl']    = $desUtils->encrypt($param['failCallbackUrl'], $key);

		}

		$form = '<form id="payForm" action="https://m.jdpay.com/wepay/web/pay" method="post">
		    <input type="hidden" name="version" value="' . $param['version'] . '">
		    <input type="hidden" name="token" value="' . $param['token'] . '">
		    <input type="hidden" name="merchantSign" value="' . $mechantSign . '">
		    <input type="hidden" name="merchantNum" value="' . $param['merchantNum'] . '">
		    <input type="hidden" name="merchantRemark" value="' . $param['merchantRemark'] . '">
		    <input type="hidden" name="tradeNum" value="' . $param['tradeNum'] . '">
		    <input type="hidden" name="tradeName" value="' . $param['tradeName'] . '">
		    <input type="hidden" name="tradeDescription" value="' . $param['tradeDescription'] . '">
		    <input type="hidden" name="tradeTime" value="' . $param['tradeTime'] . '">
		    <input type="hidden" name="tradeAmount" value="' . $param['tradeAmount'] . '">
		    <input type="hidden" name="currency" value="' . $param['currency'] . '">
		    <input type="hidden" name="notifyUrl" value="' . $param['notifyUrl'] . '">
		    <input type="hidden" name="successCallbackUrl" value="' . $param['successCallbackUrl'] . '">
		    <input type="hidden" name="failCallbackUrl" value="' . $param['failCallbackUrl'] . '">
		    <input type="submit" value="立即使用京东支付" class="enter_s1 enter_w1" />
		    <h3 style="color:red">京东支付支持主流银行卡和信用卡</h3>
		</form>';

		return $form;
	}

	// 获取数据库内部的保存参数
	function getPaymentConfig() {
		$info = get_payment(__CLASS__);
		if (isset($info['pay_config']) && $info['pay_config']) {
			$config = unserialize_config($info['pay_config']);
			if ($config) {
				return $config;
			}
		}
		return false;
	}

	/**
	 * 响应操作
	 */
	function respond() {

		// 获取paymentconfig;
		$config = $this->getPaymentConfig();
		if (!$config) {
			return false;
		}

		$param = $_REQUEST;

		// 首先判断异步
		if (isset($param['resp']) && $param['resp']) {
			// 验证签名
			// 注意异步验证需要获取 congfig 中的 md5Key 以及 desKey;

			$data = $this->xml_to_array(base64_decode($param['resp']));

			if ($data['SIGN'][0] == $this->generateSign($data, $config['md5Key'])) {

				// 验签成功，业务处理
				// 对Data数据进行解密
				include_once AppDir . 'includes/modules/payment/wepay/common/DesUtils.php';
				$des = new DesUtils(); // （秘钥向量，混淆向量）
				$res = $des->decrypt($data['DATA'][0], $config['desKey']); // 加密字符串

				$res = simplexml_load_string($res, 'SimpleXMLElement', LIBXML_NOCDATA);
				$res = json_decode(json_encode($res), true);

				// 交易返回码 字符串 0000 表示交易成功;
				if (isset($res['RETURN']['CODE']) && $res['RETURN']['CODE'] == '0000') {
					if (isset($res['TRADE']['ID']) && $res['TRADE']['ID']) {

						order_paid($res['TRADE']['ID']);
						// 文档原文为 支付成功标示为返回码“200”并且返回内容“success”，其他返回内容均认为商户系统处理异步通知失败。
						echo 'success';
						die;
					}
				}

			}
			return false;
		} else if (isset($param['tradeStatus']) && $param['tradeStatus'] == '0') {

			$data                  = array();
			$data['token']         = $param['token'];
			$data['tradeNum']      = $param['tradeNum'];
			$data['tradeAmount']   = $param['tradeAmount'];
			$data['tradeCurrency'] = $param['tradeCurrency'];
			$data['tradeDate']     = $param['tradeDate'];
			$data['tradeTime']     = $param['tradeTime'];
			$data['tradeStatus']   = $param['tradeStatus'];

			include_once AppDir . 'includes/modules/payment/wepay/common/SignUtil.php';

			ksort($data); //拼装字符串前要先排序，SignUtil::signString没给排序
			$strSourceData          = SignUtil::signString($data, array());
			$sha256SourceSignString = hash('sha256', $strSourceData);

			// 解码签名信息
			// 这里可能需要执行 urldecode
			// $sign = urldecode($param['sign']);  // 文档中有但实际测试时不适用本条
			// 注意测试商户使用的public_key跟生产环境不同.正式使用的时候 需要替换生产环境专用公钥
			$sign = $param['sign'];

			$decryptStr = RSAUtils::decryptByPublicKey($sign);

			if (strcasecmp($decryptStr, $sha256SourceSignString) == 0) {
				order_paid($data['tradeNum']);
				return true;
			}
		}

		return false;
	}

	public function xml_to_array($xml) {
		$array = (array) (simplexml_load_string($xml));
		foreach ($array as $key => $item) {
			$array[$key] = $this->struct_to_array((array) $item);
		}
		return $array;
	}
	public function struct_to_array($item) {
		if (!is_string($item)) {
			$item = (array) $item;
			foreach ($item as $key => $val) {
				$item[$key] = $this->struct_to_array($val);
			}
		}
		return $item;
	}

	/**
	 * 签名
	 */
	public function generateSign($data, $md5Key) {
		$sb = $data['VERSION'][0] . $data['MERCHANT'][0] . $data['TERMINAL'][0] . $data['DATA'][0] . $md5Key;

		return md5($sb);
	}

}

?>