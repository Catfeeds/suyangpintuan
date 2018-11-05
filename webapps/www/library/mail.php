<?php/** 邮件类 * Class Mail_Library */class Mail_Library extends Lowxp_Model {	/** 发送模板邮件	 * @param $toemail 接收人	 * @param $template_cod 模板标识	 * @param $type 1返回短信内容	 * @param $content 发送内容	 * @param $assignTpl 传入模板的变量	 */	function sendMailTpl($toemail, $template_code, $type = 0, $content = '') {		if (empty($template_code) || empty($toemail)) {			return false;		}		global $lowxp;		$sql = "SELECT template_subject,template_content,status,send_number FROM ###_templates WHERE `type`='mail' AND `status`='1' AND template_code = '$template_code'";		if ($row = $this->db->get($sql)) {			#模板付值			$assignTpl = $lowxp->smarty->get_template_vars();			$smarty    = smartyTpl();			if (is_array($assignTpl) && !empty($assignTpl)) {				foreach ($assignTpl as $k => $v) {					$smarty->assign($k, $v);				}			}			$smarty->assign('send_date', date('Y-m-d H:i'));			#获取模板解析内容			$content = $content ? $content : $smarty->fetch($template_code . '.html');			unset($smarty);			if ($type == 1) {return $content;}			$result = $this->sendMail($toemail, $row['template_subject'], stripslashes($content));			if ($result == true && !is_array($result)) {				#发送记录				$data = array(					'last_send'   => time(),					'send_number' => intval($row['send_number']) + 1,				);				$this->db->save('###_templates', $data, '', array('template_code' => $template_code));				$data = array(					'email'     => $toemail,					'content'   => $content,					'send_time' => time(),					'tpl'       => $template_code,				);				$this->db->save('###_mail', $data);				return true;			} else {				return false;			}		}		return false;	}	/**	 * 发送邮件 基础函数	 * @param $toemail 收件人email	 * @param $subject 邮件主题	 * @param $message 正文	 * @param $from 发件人	 * @param $cfg 邮件配置信息	 * @param $sitename 邮件站点名称	 */	function sendMail($toemail, $subject, $message, $from = '', $cfg = array(), $sitename = '') {		$this->load->model('setting');		$config = $this->setting->value();		if (!$config['mail_open']) {			return false;		}		if ($sitename == '') {			$sitename = $config['site_name'];		}		if ($cfg && is_array($cfg)) {			$adminemail = $cfg['from'];			$mail_type  = $cfg['mail_type']; //邮件发送模式			$mail       = $cfg;		} else {			$adminemail = $config['mail_send'];			$mail_type  = $config['mail_type']; //邮件发送模式			if ($config['mail_user'] == '' || $config['mail_password'] == '') {				return false;			}			$mail = Array(				'mailsend'      => 2,				'maildelimiter' => 1,				'mailusername'  => 1,				'server'        => $config['mail_server'],				'port'          => $config['mail_port'],				'auth'          => $config['mail_auth'],				'from'          => $config['mail_send'],				'auth_username' => $config['mail_user'],				'auth_password' => $config['mail_password'],			);		}		//mail 发送模式		if ($mail_type == 0) {			$headers = 'MIME-Version: 1.0' . "\r\n";			$headers .= 'Content-type: text/html; charset=' . ZZ_CHARSET . '' . "\r\n";			$headers .= 'From: ' . $sitename . ' <' . $from . '>' . "\r\n";			mail($toemail, $subject, $message, $headers);			return true;		}		//SMTP 发送模式		$charset      = ZZ_CHARSET;		$content_type = 'Content-Type: text/html; charset=' . $charset;		$content      = base64_encode($message);		$email        = $toemail;		$name         = $toemail;		$shop_name    = $config['site_name'];		$notification = false;		$headers   = array();		$headers[] = 'Date: ' . gmdate('D, j M Y H:i:s') . ' +0000';		$headers[] = 'To: "' . '=?' . $charset . '?B?' . base64_encode($name) . '?=' . '" <' . $email . '>';		$headers[] = 'From: "' . '=?' . $charset . '?B?' . base64_encode($shop_name) . '?=' . '" <' . $mail['from'] . '>';		$headers[] = 'Subject: ' . '=?' . $charset . '?B?' . base64_encode($subject) . '?=';		$headers[] = $content_type . '; format=flowed';		$headers[] = 'Content-Transfer-Encoding: base64';		$headers[] = 'Content-Disposition: inline';		if ($notification) {			$headers[] = 'Disposition-Notification-To: ' . '=?' . $charset . '?B?' . base64_encode($shop_name) . '?=' . '" <' . $mail['from'] . '>';		}		/* 获得邮件服务器的参数设置 */		$params['host'] = $mail['server'];		$params['port'] = $mail['port'];		$params['user'] = $mail['auth_username'];		$params['pass'] = $mail['auth_password'];		if (empty($params['host']) || empty($params['port'])) {			// 如果没有设置主机和端口直接返回 false			//$GLOBALS['err'] ->add($GLOBALS['_LANG']['smtp_setting_error']);			return false;		} else {			// 发送邮件			if (!function_exists('fsockopen')) {				//如果fsockopen被禁用，直接返回				//$GLOBALS['err']->add($GLOBALS['_LANG']['disabled_fsockopen']);				return false;			}			require_once AppDir . 'library/smtp.php';			static $smtp;			$send_params['recipients'] = $email;			$send_params['headers']    = $headers;			$send_params['from']       = $mail['from'];			$send_params['body']       = $content;			if (!isset($smtp)) {				$smtp = new smtp($params);			}			if ($smtp->connect() && $smtp->send($send_params)) {				return true;			} else {				$err_msg = $smtp->error_msg();				if (empty($err_msg)) {					return array('error' => 'Unknown Error');				} else {					if (strpos($err_msg, 'Failed to connect to server') !== false) {						return array('error' => sprintf('SMTP邮件服务器连接失败', $params['host'] . ':' . $params['port']));					} else if (strpos($err_msg, 'AUTH command failed') !== false) {						return array('error' => 'SMTP邮件服务器登录失败');					} elseif (strpos($err_msg, 'bad sequence of commands') !== false) {						return array('error' => '服务器拒绝发送该邮件');					} else {						return array('error' => $err_msg);					}				}				return false;			}		}	}	private function runLog($mode = 'SMTP', $b = '', $c = '', $d = '') {}}