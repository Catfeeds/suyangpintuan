<?php
class Wechatredpack_Library {

	public $redPack;

	public function __construct() {
		require_once AppDir . 'includes/modules/payment/wxpay/WxRedPack.php';
		$this->redPack = new WxRedPack();
	}

	/**
	 * 发送普通微信红包
	 * @param  string  $openid      微信openid
	 * @param  string  $billno      本地系统的红包唯一识别编号
	 * @param  integer $totalAmount 金额 单位分 最少100分
	 * @param  string  $sendName    红包发送者名称
	 * @param  string  $wishing     红包祝福语
	 * @param  string  $remark      红包备注信息
	 * @param  string  $actName     红包活动名称
	 * @return array                json结果
	 */
	public function sendRedPack($openid, $billno, $totalAmount = 100, $sendName = '', $wishing = '', $remark = '', $actName = '') {
		$res = array(
			'error' => 1,
			'msg'   => '对不起,参数错误',
		);

		if (!($openid && $billno && $totalAmount && $sendName && $wishing && $remark && $actName)) {
			return $res;
		}

		$this->redPack->setReOpenid($openid);
		$this->redPack->setPartnerTradeNo($billno);
		$this->redPack->setTotalAmount($totalAmount);
		$this->redPack->setSendName($sendName);
		$this->redPack->setWishing($wishing);
		$this->redPack->setRemark($remark);
		$this->redPack->setActName($actName);
		$data = $this->redPack->sendRedPack();

		if ($data) {
			$data = (array) simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
			if ('SUCCESS' == $data['err_code']) {
				$res['error'] = 0;
				$res['msg']   = '发送成功';
				return $res;
			}

			$res['msg'] = $data['err_code_des'];
		} else {
			$res['msg'] = $this->redPack->error();
		}

		return $res;
	}

	/**
	 * 发送裂变微信红包
	 * @param  string  $openid      微信openid
	 * @param  string  $billno      本地系统的红包唯一识别编号
	 * @param  integer $totalAmount 金额 单位分 最少100分乘以个数
	 * @param  integer $totalNum    裂变红包个数
	 * @param  string  $sendName    红包发送者名称
	 * @param  string  $wishing     红包祝福语
	 * @param  string  $remark      红包备注信息
	 * @param  string  $actName     红包活动名称
	 * @return array                json结果
	 */
	public function sendGroupRedPack($openid, $billno, $totalAmount = 100, $totalNum = 3, $sendName = '', $wishing = '', $remark = '', $actName = '') {
		$res = array(
			'error' => 1,
			'msg'   => '对不起,参数错误',
		);

		if (!($openid && $billno && $totalAmount && $sendName && $wishing && $remark && $actName)) {
			return $res;
		}

		$this->redPack->setReOpenid($openid);
		$this->redPack->setPartnerTradeNo($billno);
		$this->redPack->setTotalAmount($totalAmount);
		$this->redPack->setTotalNum($totalNum);
		$this->redPack->setSendName($sendName);
		$this->redPack->setWishing($wishing);
		$this->redPack->setRemark($remark);
		$this->redPack->setActName($actName);
		$data = $this->redPack->sendRedPack();

		if ($data) {
			$data = (array) simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
			if ('SUCCESS' == $data['err_code']) {
				$res['error'] = 0;
				$res['msg']   = '发送成功';
				return $res;
			}

			$res['msg'] = $data['err_code_des'];
		} else {
			$res['msg'] = $this->redPack->error();
		}

		return $res;
	}

	/**
	 * 普通红包查询
	 * @param string $billno 红包编号
	 */
	public function checkRedPack($billno) {
		$res = array(
			'error' => 1,
			'msg'   => '对不起,参数错误',
		);

		if (!$billno) {
			return $res;
		}

		$this->redPack->setPartnerTradeNo($billno);
		$data = $this->redPack->checkRedPack();

		if ($data) {
			$data = (array) simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);

			$res['error'] = 0;
			$res['msg']   = '查询成功';
			$res['data']  = $data;
		} else {
			$res['msg'] = $this->redPack->error();
		}

		return $res;
	}

}

?>