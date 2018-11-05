<?php
// namespace wepay\join\demo\api;

// use wepay\join\demo\common\ConfigUtil;
// use wepay\join\demo\common\SignUtil;
// use wepay\join\demo\common\BytesUtils;
// use wepay\join\demo\common\RSAUtils;
include '../common/SignUtil.php';
include '../common/ConfigUtil.php';

include '../common/BytesUtils.php';

class WebSuccessCtrl {
	public function execute() {
		$param                  = array();
		$param["token"]         = $_GET["token"];
		$param["tradeNum"]      = $_GET["tradeNum"];
		$param["tradeAmount"]   = $_GET["tradeAmount"];
		$param["tradeCurrency"] = $_GET["tradeCurrency"];
		$param["tradeDate"]     = $_GET["tradeDate"];
		$param["tradeTime"]     = $_GET["tradeTime"];
		$param["tradeStatus"]   = $_GET["tradeStatus"];

		$sign = $_GET["sign"];
		echo "oldSign=" . $sign . "<br>";
		ksort($param); //拼装字符串前要先排序，SignUtil::signString没给排序
		$strSourceData          = SignUtil::signString($param, array());
		$decryptStr             = RSAUtils::decryptByPublicKey($sign);
		$sha256SourceSignString = hash("sha256", $strSourceData);
		$_SESSION['queryDatas'] = null;

		if (strcasecmp($decryptStr, $sha256SourceSignString) != 0) {
			$_SESSION['errorMsg'] = "验证签名失败！";
		} else {
			$_SESSION['errorMsg'] = "tradeNum:" . $param["tradeNum"] . ":status:" . $param["tradeStatus"];
		}
		header("location:../tpl/queryResult.php");
	}

}

$webSuccess = new WebSuccessCtrl();
$webSuccess->execute();
?>