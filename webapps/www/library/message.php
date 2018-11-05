<?php
class Message_Library extends Lowxp_Model{
	/**
	 * 模版消息发送
	 * @param  string  $channel 发送渠道 msg wechat mail sms
	 * @param  string  $target  发送目标 注意每种渠道对应的接收方式不同
	 * @param  string  $content 发送内容
	 * @param  integer $type    站内信类别
	 * @param  string  $title   站内信或邮件标题
	 * @return mixed            发送结果
	 */
	public static function send($channel, $target, $content, $type = 0, $title) {
		$res = false;
		switch ($channel) {
			case 'msg':
				$res = self::send_msg($target, $content, $type, $title);
				break;
			case 'wechat':
				$res = self::send_wechat($target, $content);
				break;
			case 'mail':
				$res = self::send_mail($target, $content, $title);
				break;
			case 'sms':
				$res = self::send_sms($target, $content);
				break;
			default:
				break;
		}
		return $res;
	}

	/**
	 * 发送站内信
	 * @param  integer $mid     用户id
	 * @param  string  $content 发送内容
	 * @param  integer $type    站内信类别
	 * @param  string  $title   站内信标题
	 * @return boolean          发送结果
	 */
	public static function send_msg($mid, $content, $type = 0, $title = '系统消息') {
		if (!($mid && $content)) {
			return false;
		}
		$lowxp = &get_instance();

		$data = array(
			'mid'         => 0,
			'target'      => $mid,
			'type'        => $type,
			'content'     => $content,
			'title'       => $title,
			'create_time' => RUN_TIME,
			'status'      => 0,
		);

		return $lowxp->db->insert('message', $data);
	}

	/**
	 * 客服接口发送微信消息
	 * @param  string $openid  微信openid
	 * @param  string $content 发送内容
	 * @return boolean         发送结果
	 */

	// todo test
	public static function send_wechat($openid, $content) {
		if (!($openid && $content)) {
			return false;
		}
		$lowxp = &get_instance();
		$lowxp->load->model('wxapi');
		$data = array(
			'touser'  => $openid,
			'msgtype' => 'text',
			'text'    => array(
				'content' => $content,
			),
		);
		return $lowxp->wxapi->wechat->sendCustomMessage($data);
	}

	/**
	 * 发送邮件
	 * @param  string $mailAddress 邮件地址
	 * @param  string $content     发送内容
	 * @param  string $title       邮件标题
	 * @return boolean             发送结果
	 */
	public static function send_mail($mailAddress, $content, $title = '系统消息') {
		if (!($mailAddress && $content)) {
			return false;
		}
		$lowxp = &get_instance();
		$lowxp->load->library('mail');
		return $lowxp->mail->sendMail($mailAddress, $title, $content);
	}

	/**
	 * 发送短信
	 * @param  string $phone   手机号码
	 * @param  string $content 发送内容
	 * @return boolean         发送结果
	 */
	public static function send_sms($phone, $content) {
		if (!($phone && $content)) {
			return false;
		}
		$lowxp = &get_instance();
		$lowxp->load->library('sms');
		return $lowxp->sms->sendSms($phone, $content);
	}

}

?>