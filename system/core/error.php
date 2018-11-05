<?php
/**
 * 错误处理
 */
function URL_404() {ShowError('', 404);}
function ShowError($message, $code = 200, $heading = '运行出现错误') {
	if (!IS_DEV) {
		//记录到文件
		return;
	}
	;
	$message = '<p>' . implode('</p><p>', (!is_array($message)) ? array($message) : $message) . '</p>';
	SetHeader($code, is_array($message) ? '' : $message);
	ob_start();
	if ($code == 404) {
		include RootDir . 'system/error/404.php';
	} else {
		include RootDir . 'system/core/general.php';
	}
	$buffer = ob_get_contents();
	ob_end_clean();
	echo $buffer;
	#exit($buffer);
}
function SetHeader($code = 200, $text = '') {
	$status = array(
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
	);
	if ($code == '' || !is_numeric($code)) {
		ShowError('Status codes must be numeric', 500);
	}
	if (isset($status[$code]) AND $text == '') {
		$text = $status[$code];
	}
	if ($text == '') {
		ShowError('No status text available.  Please check your status code number or supply your own message text.', 500);
	}
	$server_protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : false;

	if (substr(php_sapi_name(), 0, 3) == 'cgi') {
		header("Status: {$code} {$text}", true);
	} elseif ($server_protocol == 'HTTP/1.1' || $server_protocol == 'HTTP/1.0') {
		header($server_protocol . " {$code} {$text}", true, $code);
	} else {
		header("HTTP/1.1 {$code} {$text}", true, $code);
	}
}

function ErrorHandle($errno, $errstr, $errfile, $errline) {
	//todo:兼容旧代码处理
	$Error = array(
		1    => array('ERROR', '致命错误，脚本执行中断,就是脚本中有不可识别的东西出现'),
		2    => array('WARINIG', '部分代码出错,但不影响整体运行'),
		4    => array('PARSE', '字符、变量或结束的地方写规范有误'),
		8    => array('NOTICE', '一般通知，如变量未定义等'),
		16   => array('CORE_ERROR', 'PHP进程在启动时,发生了致命性错误'),
		32   => array('CORE_WARNING', '在PHP启动时警告(非致命性错误)'),
		64   => array('COMPILE_ERROR', '编译时致命性错误'),
		128  => array('COMPILE_WARNING', '编译时警告级错误'),
		256  => array('USER_ERROR', '用户自定义的错误消息'),
		512  => array('USER_WARNING', '用户自定义的警告消息'),
		1024 => array('USER_NOTICE', '用户自定义的提醒消息'),
		2048 => array('E_STRICT', '不包括E_STRICT的所有的报错信息'),
		4096 => array('RECOVERABLE_ERROR', '编码标准化警告,允许PHP建议如何修改代码以确保最佳的互操作性向前兼容性。'),
		8191 => array('ALL', '不包括E_STRICT的所有的报错信息'),
	);
	ShowError(array(
		'错误级别：' . (isset($Error[$errno]) ? $Error[$errno][0] : '未知错误') . ', ' . $Error[$errno][1],
		'出错文件： ' . $errfile,
		'行号： ' . $errline,
		'错误描述：' . $errstr,
	));
}