<?php #全局公用函数库

/**获取IP地址
 * @param int $type =1只获取本机IP
 * @return mixed
 */
function getIP($type = 1) {
	if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
		$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	} elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
		$ip = $_SERVER["HTTP_CLIENT_IP"];
	} elseif (isset($_SERVER["REMOTE_ADDR"])) {
		$ip = $_SERVER["REMOTE_ADDR"];
	} else {
		$ip = "Unknown";
	}
	return pathIp($ip, $type);
}

/** 重新解析IP
 * @param $ip
 */
function pathIp($ip, $type = 1) {
	if (strpos($ip, ',') !== false) {
		$ips = explode(',', $ip);
		#type=1里去除代理IP
		if ($type == 1) {return trim($ips[0]);}
		#去除代理阿里云的IP
		if (strpos(trim($ips[1]), '42.121.') !== false && strpos(trim($ips[1]), '42,121.') == 0) {
			$ip = trim($ips[0]);
		}
	}
	return $ip;
}

/**
 * 获取当前网址
 * @params $type string url全网址 www二级前缀
 * @params $http string http or https or ''
 * @params $www string 设置前缀
 */
function getUrl($type = 'url', $http = '', $www = '') {
	$host = $_SERVER['HTTP_HOST'];
	$url  = '';

	if ($type == 'www') {
		#返回www前缀
		$url = substr($host, 0, strpos($host, '.'));
		if ($url != 'm') {
			$url = 'www';
		}

	} elseif ($type == 'referer') {
		#返回referer
		$url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
	} else {
		$http = $http ? $http : ((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) ? 'https' : 'http');
		$uri  = isset($_SERVER['HTTP_X_REWRITE_URL']) ? $_SERVER['HTTP_X_REWRITE_URL'] : $_SERVER['REQUEST_URI'];

		if ($www) {
			#返回设置了前缀的网址
			$url = $http . '://' . $www . '.' . Domain . $uri;
		} else {
			#返回原始网址
			$url = $http . '://' . $host . $uri;
		}
	}
	return $url;
}

/** 整站链接转换，静态扩展
 * @param $url 动态地址
 * @param $httpsOn 是否ssl访问
 * @return mixed
 */
function url($url = '') {
	#处理远程地址
	if (strpos($url, 'http://') !== false || strpos($url, 'https://') !== false) {
		return $url;
	}

	if (empty($url)) {
		$url = RootUrl;
	} else {
		$url = rtrim(RootUrl, '/') . $url;
	}

	#手机端
	if (getUrl('www') == 'm') {
		if (strpos($url, 'www.') !== false) {
			$url = str_replace('www.', 'm.', $url);
		} elseif (strpos($url, 'm.') === false) {
			$url = str_replace('http://', 'http://m.', $url);
		}
	}

	return $url;
}

/** 微信消息模板通知地址
 * @return mixed
 */
function note_url($url = '') {
	#处理远程地址
	if (strpos($url, 'http://') !== false || strpos($url, 'https://') !== false) {
		return $url;
	}
	$domain = C("wx_url") ? C("wx_url") : RootUrl;
	if (empty($url)) {
		$url = $domain;
	} else {
		$url = rtrim($domain, '/') . $url;
	}
	return $url;
}

/* 获取当前时间戳，精确到毫秒 */
function microtime_float() {
	list($usec, $sec) = explode(" ", microtime());
	return str_replace(',', '', number_format($usec + (float) $sec, 3));
}
/** 格式化时间戳，精确到毫秒，x代表毫秒
 * 1. 获取当前时间戳(精确到毫秒)：microtime_float()
 * 2. 时间戳转换时间：microtime_format(1270626578.000,'Y年m月d日 H时i分s秒 x毫秒')
 */
function microtime_format($time, $tag = 'Y-m-d H:i:s x', $space = ' ') {
	list($usec, $sec) = explode(".", $time);
	$sec              = !empty($sec) ? $sec : '000';
	$date             = date($tag, $usec);
	$date             = str_replace('x', $sec, $date);
	if (trim($space)) {$date = str_replace(' ', $space, $date);}
	return $date;
}

/**
 * 格式化价格
 * @param   float   $price  商品价格
 * @param   $type 格式化类型
 * @return  string
 */
function price_format($price, $type = 0) {
	if ($price === '') {$price = 0;}
	$segments = Lowxp_Router::getInstance()->segments;
	if ($segments[0] != 'manage') {
		switch ($type) {
			case 0:
				$price = number_format($price, 2, '.', '');
				break;
		}
	} else { $price = number_format($price, 2, '.', '');}
	return $price;
}

/** 记录后台操作日志
 * @param $content
 */
function admin_log($content) {
	global $lowxp;

	$segments = Lowxp_Router::getInstance()->segments;
	$mod      = $segments[0];
	if ($mod != 'manage') {
		return;
	}

	if (!empty($content)) {
		$data = array(
			'log_time'   => RUN_TIME,
			'user_id'    => defined('UID') ? UID : 0,
			'ip_address' => getIp(),
			'log_info'   => $content,
		);
		$lowxp->db->save('###_m_user_log', $data);
	}
}

/** 记录后台操作日志
 * @param $content
 */
function admin_log_sid($content) {
    global $lowxp;

    $segments = Lowxp_Router::getInstance()->segments;
    $mod      = $segments[0];
    if ($mod != 'business') {
        return;
    }

    if (!empty($content)) {
        $data = array(
            'log_time'   => RUN_TIME,
            'user_id'    => isset($_SESSION['b_uid']) ? $_SESSION['b_uid'] : 0,
            'ip_address' => getIp(),
            'log_info'   => $content,
            'sid'   => defined('SID') ? SID : 0,
            'gid'   => defined('BGID') ? BGID : 0,
        );
        $lowxp->db->save('###_business_user_log', $data);
    }
}

/** 更换模板smarty类配置
 * @return mixed
 */
function smartyTpl($tpl = 'tpl') {
	global $lowxp;
	$smarty = $lowxp->load->smarty(array(
		'tplDir'     => AppDir . 'views/' . $tpl . '/',
		'compileDir' => RUNTIME_PATH . 'views_c/' . $tpl . '/',
		'cacheDir'   => RUNTIME_PATH . 'cache/' . $tpl . '/',
	), true);
	return $smarty;
}

/** 获取模板状态
 * @param $template_code
 * @return bool
 */
function statusTpl($template_code) {
	global $lowxp;
	$sql = "SELECT status FROM ###_templates " .
		"WHERE template_code = '$template_code'";
	if ($lowxp->db->getstr($sql) == 1) {
		return true;
	}
	return false;
}

/**
 * 设置缓存
 */
function zzcookie($name, $value = '', $time = null) {
	return setcookie($name, $value, $time, '/', Domain);
}

/**
 * 读取缓存
 */
function cookie($name) {
	return isset($_COOKIE[$name]) ? $_COOKIE[$name] : '';
}

/**
 * 处理序列化的支付、配送的配置参数
 * 返回一个以name为索引的数组
 * @access  publi
 * @param   string       $cfg
 * @return  void
 */
function unserialize_config($cfg) {
	if (is_string($cfg) && ($arr = unserialize($cfg)) !== false) {
		$config = array();
		foreach ($arr AS $key => $val) {
			$config[$val['name']] = $val['value'];
		}

		return $config;
	} else {
		return false;
	}
}

/**
 * 发起HTTP请求
 * @param $url
 * @param string $method
 * @param array $params
 * @return mixed
 */
function http($url, $method = 'get', $params = array(), $error = 0, $HttpHeader = '') {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_HEADER, 0); #返回头部信息
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);

	//取消SSL验证
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

	if (strtolower($method) == 'post') {
		if (is_array($params)) {
			$fields = http_build_query($params);
		} else {
			//咱用于微信发送无参数的纯文本数据
			$HttpHeader[] = 'Content-Type: text/xml';
			$fields       = $params;
		}
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	} else {
		//Get请求,处理地址中包含的参数.
		$segments = parse_url($url);
		if (isset($segments['query']) && $segments['query'] != '') {
			parse_str($segments['query'], $params2);
			$params = array_merge($params, $params2);
		}
		$fields = http_build_query($params);

		$url = $segments['scheme'] . '://' . $segments['host'] . $segments['path'];
		if ($fields != '') {
			$url = $url . '?' . $fields;
		}

		curl_setopt($ch, CURLOPT_HTTPGET, 1);
	}

	curl_setopt($ch, CURLOPT_URL, $url);
	if ($HttpHeader) {
		curl_setopt($ch, CURLOPT_HTTPHEADER, $HttpHeader);
	}

	$ret = curl_exec($ch);
	if ($error) {
		print curl_error($ch);
	}
	curl_close($ch);
	return $ret;
}

/**
 * 删除匹配前缀的图片
 * 图片带路径如：/upload/images/aaa.jpg
 */
function delImage($filename) {
	global $lowxp;
	$lowxp->load->model('upload');
	if (is_file(RootDir . 'web' . $filename)) {
		$basename = basename(RootDir . 'web' . $filename);
		$ext      = '.' . end(explode('.', $basename));
		$filestr  = str_replace($ext, '', $basename);
		$filestr  = str_replace('_thumb', '', $filestr);
		$filedir  = str_replace($basename, '', $filename);
	} else {
		return false;
	}
	$FullDir = RootDir . 'web' . $filedir;
	$files   = $lowxp->upload->matchFiles($FullDir, $filestr);
	foreach ($files as $fileName) {
		$filePath = $FullDir . '/' . $fileName;
		if (is_file($filePath)) {
			unlink($filePath);
		}
	}
}

/** 动态IP库获取城市字符串
 * @param $ip IP
 * @param $item 获取的内容
 * @return mixed
 */
function cityIp($ip = '', $item = 'country') {
	global $lowxp;
	$lowxp->load->library('ip');
	$addr = $lowxp->ip->ip2addr($ip ? pathIp($ip, 1) : getIP(1));
	return $addr[$item];
}

/** 验证邮件地址 */
function is_email($email) {
	$chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
	if (strpos($email, '@') !== false && strpos($email, '.') !== false) {
		if (preg_match($chars, $email)) {return true;} else {return false;}
	} else {return false;}
}

/** 验证手机号 */
function is_mobile($mobile) {
	return (strlen($mobile) == 11 || strlen($mobile) == 12) && preg_match("/^1\d{10}$/", $mobile) || preg_match("/^0\d{11}$/", $mobile);
}

/**
 * 传入日期格式或时间戳格式时间，返回与当前时间的差距，如1分钟前，2小时前，5月前，3年前等
 * @param string or int $date 分两种日期格式"2013-12-11 14:16:12"或时间戳格式"1386743303"
 * @param int $type
 * @return string
 */
function formatTime($date = 0, $type = 1) {
	//$type = 1为时间戳格式，$type = 2为date时间格式
	date_default_timezone_set('PRC'); //设置成中国的时区
	switch ($type) {
		case 1:
			//$date时间戳格式
			$second = time() - $date;
			$minute = floor($second / 60) ? floor($second / 60) : 1; //得到分钟数
			if ($minute >= 60 && $minute < (60 * 24)) { //分钟大于等于60分钟且小于一天的分钟数，即按小时显示
				$hour = floor($minute / 60); //得到小时数
			} elseif ($minute >= (60 * 24) && $minute < (60 * 24 * 30)) { //如果分钟数大于等于一天的分钟数，且小于一月的分钟数，则按天显示
				$day = floor($minute / (60 * 24)); //得到天数
			} elseif ($minute >= (60 * 24 * 30) && $minute < (60 * 24 * 365)) { //如果分钟数大于等于一月且小于一年的分钟数，则按月显示
				$month = floor($minute / (60 * 24 * 30)); //得到月数
			} elseif ($minute >= (60 * 24 * 365)) { //如果分钟数大于等于一年的分钟数，则按年显示
				$year = floor($minute / (60 * 24 * 365)); //得到年数
			}
			break;
		case 2:
			//$date为字符串格式 2013-06-06 19:16:12
			$date   = strtotime($date);
			$second = time() - $date;
			$minute = floor($second / 60) ? floor($second / 60) : 1; //得到分钟数
			if ($minute >= 60 && $minute < (60 * 24)) { //分钟大于等于60分钟且小于一天的分钟数，即按小时显示
				$hour = floor($minute / 60); //得到小时数
			} elseif ($minute >= (60 * 24) && $minute < (60 * 24 * 30)) { //如果分钟数大于等于一天的分钟数，且小于一月的分钟数，则按天显示
				$day = floor($minute / (60 * 24)); //得到天数
			} elseif ($minute >= (60 * 24 * 30) && $minute < (60 * 24 * 365)) { //如果分钟数大于等于一月且小于一年的分钟数，则按月显示
				$month = floor($minute / (60 * 24 * 30)); //得到月数
			} elseif ($minute >= (60 * 24 * 365)) { //如果分钟数大于等于一年的分钟数，则按年显示
				$year = floor($minute / (60 * 24 * 365)); //得到年数
			}
			break;
		default:
			break;
	}
	if (isset($year)) {
		return $year . '年前';
	} elseif (isset($month)) {
		return $month . '月前';
	} elseif (isset($day)) {
		return $day . '天前';
	} elseif (isset($hour)) {
		return $hour . '小时前';
	} elseif (isset($minute)) {
		return $minute . '分钟前';
	}
}

/**
 * 替换本地图片成图库图片
 */
function picurl($content = "", $url = 0) {
	global $lowxp;
	$setting  = ($lowxp->mod == 'manage') ? $lowxp->common : $lowxp->site_config;
	$cloudurl = '';
	if ($setting['cloudsave'] == 1) {
		$cloudurl = ($url == 1) ? $setting['cloudurl2'] : $setting['cloudurl'];
	}

	if ($url == 1) {
		return $cloudurl . $content;
	}
	return str_replace(RootUrl . 'upload', $cloudurl . '/upload', $content);
}

//判断是否为ajax请求
function isAjax() {
	if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {return true;} else {
		return false;
	}

}

/**
 * 生成二维码
 */
function creat_code($value, $filename = 'qrcode.png') {
	global $lowxp;
	$dir = $lowxp->load->config('picture', 'image_dir') . 'qrcode/';
	if (!is_dir($dir)) {
		mkdir($dir, 0777, true);
	}

	#二维码图片不存在
	if (!is_file($dir . $filename)) {
		require_once AppDir . 'library/phpqrcode/phpqrcode.php';
		$errorCorrectionLevel = 'L'; //容错级别
		$matrixPointSize      = 5; //生成图片大小
		//$file                 = $dir . str_replace(".", "a.", $filename);
		$file = $dir . $filename;
		//生成二维码图片
		QRcode::png($value, $file, $errorCorrectionLevel, $matrixPointSize, 1);
		// $logo = RootDir . 'web/common/w_logo.png'; //准备好的logo图片
		// $QR   = $file; //已经生成的原始二维码图

		// if (file_exists($logo)) {
		// 	$QR          = imagecreatefromstring(file_get_contents($QR));
		// 	$logo        = imagecreatefromstring(file_get_contents($logo));
		// 	$QR_width    = imagesx($QR); //二维码图片宽度
		// 	$QR_height   = imagesy($QR); //二维码图片高度
		// 	$logo_width  = imagesx($logo); //logo图片宽度
		// 	$logo_height = imagesy($logo); //logo图片高度
		// 	//$logo_qr_width = $QR_width/4;
		// 	$logo_qr_width  = 16;
		// 	$scale          = $logo_width / $logo_qr_width;
		// 	$logo_qr_height = $logo_height / $scale;
		// 	$from_width     = ($QR_width - $logo_qr_width) / 2;
		// 	$from_height    = ($QR_width - $logo_qr_height) / 2;
		// 	//重新组合图片并调整大小
		// 	imagecopyresampled($QR, $logo, $from_width, $from_height, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
		// }
		// //输出图片
		// imagepng($QR, $dir . $filename);
		// imagedestroy($QR);
		//unlink($file);
		$lowxp->load->model('upload');
		$lowxp->upload->yunsave('qrcode/' . $filename);
	}
	return '/upload/' . MERID . '/images/qrcode/' . $filename;
}

/**
 * 生成临时二维码 可以被清理缓存删除
 */
function creat_tmp_code($value) {
	global $lowxp;

	$filename = md5($value) . '.png';

	$dir = RootDir . 'web/tmp/qrcode/';

	if (!is_dir($dir)) {
		mkdir($dir, 0777, true);
		chmod($dir, 0777);
	}

	$file = $dir . $filename;

	#二维码图片不存在
	if (!is_file($file)) {
		require_once AppDir . 'library/phpqrcode/phpqrcode.php';
		$errorCorrectionLevel = 'L'; //容错级别
		$matrixPointSize      = 10; //生成图片大小
		// $file                 = $dir . str_replace(".", "a.", $filename);
		//生成二维码图片
		QRcode::png($value, $file, $errorCorrectionLevel, $matrixPointSize, 1);
		// $logo = RootDir . 'web/common/w_logo.png'; //准备好的logo图片
		// $QR   = $file; //已经生成的原始二维码图

		// if (file_exists($logo)) {
		// 	$QR          = imagecreatefromstring(file_get_contents($QR));
		// 	$logo        = imagecreatefromstring(file_get_contents($logo));
		// 	$QR_width    = imagesx($QR); //二维码图片宽度
		// 	$QR_height   = imagesy($QR); //二维码图片高度
		// 	$logo_width  = imagesx($logo); //logo图片宽度
		// 	$logo_height = imagesy($logo); //logo图片高度
		// 	//$logo_qr_width = $QR_width/4;
		// 	$logo_qr_width  = 16;
		// 	$scale          = $logo_width / $logo_qr_width;
		// 	$logo_qr_height = $logo_height / $scale;
		// 	$from_width     = ($QR_width - $logo_qr_width) / 2;
		// 	$from_height    = ($QR_width - $logo_qr_height) / 2;
		// 	//重新组合图片并调整大小
		// 	imagecopyresampled($QR, $logo, $from_width, $from_height, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);

		// 	//输出图片
		// 	imagepng($QR, $dir . $filename);
		// 	imagedestroy($QR);
		// 	unlink($file);
		// 	$file = $dir . $filename;
		// }

		// $lowxp->load->model('upload');
		// $lowxp->upload->yunsave('qrcode/' . $filename);
	}
	return '/tmp/qrcode/' . $filename;
}

/**
 * 语音验证码
 */
function voiceVerify($mobile = '') {
	global $lowxp;
	if (empty($mobile)) {
		return false;
	}

	if ($_SESSION['voiceVerify']) {
		unset($_SESSION['voiceVerify']);
	}

	//获取验证码
	$lowxp->load->library('sms');
	$verifyCode = $lowxp->sms->getVerifyCode();
	include_once AppDir . 'library/CCPRestSDK.php';
	// 初始化REST SDK
	//$serverIP = 'sandboxapp.cloopen.com';
	$serverIP    = 'app.cloopen.com';
	$serverPort  = '8883';
	$softVersion = '2013-12-26';
	//语音接口配置帐号
	$accountSid   = '';
	$accountToken = '';
	//$appId = '8a48b5514b0b8727014b24069550117e';
	$appId     = '8a48b5514b0b8727014b242c8e3b1222';
	$playTimes = '2';

	$rest = new REST($serverIP, $serverPort, $softVersion);
	$rest->setAccount($accountSid, $accountToken);
	$rest->setAppId($appId);

	$result = $rest->voiceVerify($verifyCode, $playTimes, $mobile, "4000901225", $respUrl = '');
	if ($result == NULL) {
		//echo "result error!";
		return false;
	}

	if ($result->statusCode != 0) {
		//echo "error code :" . $result->statusCode . "<br>";
		//echo "error msg :" . $result->statusMsg . "<br>";
		return false;
		//TODO 添加错误处理逻辑
	} else {
		$voiceVerify = $result->VoiceVerify;
		//echo "callSid:".$voiceVerify->callSid."<br/>";
		//echo "dateCreated:".$voiceVerify->dateCreated."<br/>";
		//TODO 添加成功处理逻辑
		$_SESSION['voiceVerify']['mobile'] = $mobile;
		$_SESSION['voiceVerify']['code']   = $verifyCode;
		return $verifyCode;
	}

}

/** 格式化价格
 * @param $price
 * @param bool $change_price
 * @return string
 */
function formatPrice($price, $change_price = true) {
	if ($price === '') {
		$price = 0;
	}
	$price = number_format($price, 2, '.', '');
	return $price;
}

/** 格式化数字
 * @param $num
 * @return string
 */
function formatNum($num) {
	if ($num > 10000) {
		$string = round($num / 10000, 2);
		$string .= "万";
	} else {
		$string = $num;
	}
	return $string;
}

/** token令牌
 * @return mixed
 */
function createToken() {
	$token             = md5(uniqid(mt_rand(), true));
	$_SESSION['token'] = $token;
	return $token;
}
function checkToken() {
	$return = $_REQUEST['token'] === $_SESSION['token'] ? true : false;
	createToken();
	return $return;
}

/**
 * 生成 小数点指定长度位的随机0到1之间的小数
 * @param  integer $length 小数长度
 * @return float
 */
/**
 * 生成随机浮点数数 默认0到1之间
 * @param  integer $min 最小值
 * @param  integer $max 最大值
 * @return float
 */
function random_float($min = 0, $max = 1) {
	return $min + mt_rand() / mt_getrandmax() * ($max - $min);
}

/**
 * 随机产生10位数字
 * @param int $length
 * @return string
 */
function randNums($length = 10) {
	$temp = '';
	for ($i = 1; $i <= $length; $i++) {
		$temp .= rand(0, 9);
	}
	return $temp;
}

/**
 * 字符串截取  超出长度则使用省略号
 * @param  string  $str 待截取字符串
 * @param  integer $len 截取长度
 * @return string       截取后的结果
 */
function cut_str($str, $len) {
	return mb_strlen($str, 'utf8') > $len ? mb_substr($str, 0, $len, 'utf8') . '...' : $str;
}

/**
 * 字符串截取 中间显示*号
 * @param  string  $str 待截取字符串
 * @param  integer $start 显示前几位
 * @param  integer $end 显示后几位
 * @return string       截取后的结果
 */
function cut_format($str, $start, $end) {
	return substr($str, 0, $start) . "****" . substr($str, -$end);
}

/**
 * 对查询结果集进行排序
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param array $sortby 排序类型
 * asc正向排序 desc逆向排序 nat自然排序
 * @return array
 */
function list_sort_by($list, $field, $sortby = 'asc') {
	if (is_array($list)) {
		$refer = $resultSet = array();
		foreach ($list as $i => $data) {
			$refer[$i] = &$data[$field];
		}

		switch ($sortby) {
			case 'asc': // 正向排序
				asort($refer);
				break;
			case 'desc': // 逆向排序
				arsort($refer);
				break;
			case 'nat': // 自然排序
				natcasesort($refer);
				break;
		}
		foreach ($refer as $key => $val) {
			$resultSet[] = &$list[$key];
		}

		return $resultSet;
	}
	return false;
}

/**
 * 使用特定function对数组中所有元素做处理
 * @param string &$array 要处理的字符串
 * @param string $function 要执行的函数
 * @return boolean $apply_to_keys_also 是否也应用到key上
 */
function arrayRecursive(&$array, $function, $apply_to_keys_also = false) {
	static $recursive_counter = 0;
	if (++$recursive_counter > 1000) {
		die('possible deep recursion attack');
	}
	foreach ($array as $key => $value) {
		if (is_array($value)) {
			arrayRecursive($array[$key], $function, $apply_to_keys_also);
		} else {
			$array[$key] = $function($value);
		}

		if ($apply_to_keys_also && is_string($key)) {
			$new_key = $function($key);
			if ($new_key != $key) {
				$array[$new_key] = $array[$key];
				unset($array[$key]);
			}
		}
	}
	$recursive_counter--;
}

/**
 * 禁用关键词
 * @param $content
 */
function badWord($content) {
	$badwords = getBadWord();

	$badwords = array_combine($badwords, array_fill(0, count($badwords), '**'));
	return strtr($content, $badwords);
}

/**
 * 判断是否存在敏感词
 * @param $content
 * @return true:存在，false:不存在
 */
function isbadWord($content) {
	$badwords = getBadWord();
	$re_str   = '#bw#';
	$badwords = array_combine($badwords, array_fill(0, count($badwords), $re_str));
	$is_bw    = false;
	if (is_array($content)) {
		foreach ($content as $v) {
			$v = strtr($v, $badwords);
			if (strpos($v, $re_str) !== false) {
				$is_bw = true;
				break;
			}
		}
	} else {
		$str   = strtr($content, $badwords);
		$is_bw = strpos($str, $re_str) === false ? false : true;
	}
	return $is_bw;
}

/**
 * 获取敏感词
 * @return $content
 */
function getBadWord() {
	global $Loader;
	$badwords = array();
	include AppDir . 'library/badwords.php';
	$source_file = $Loader->config('picture', 'source_dir');
	$configfile  = $source_file . "badwords.php";
	if (file_exists($configfile)) {
		include $configfile;
	}
	return $badwords;
}

/**
 * 替换敏感词 并去掉换行和html标签
 * @param  string $content
 * @return string
 */
function clearHtml($content) {
	// $content = badWord($content);
	$content = str_replace("\r\n", '', strip_tags($content));
	$content = str_replace("\n", '', strip_tags($content));
	return $content;
}
/**
 * 替换换行,回车,空格,tab空格,翻页等
 * @param  string $content
 * @return string
 */
function clearBr($content) {
	$content = preg_replace('/[\s]{2,}/', '', $content);
	return $content;
}
// fan 2016-05-06 start

// 生成唯一字符串id
function uniqueId() {
	return md5(uniqid(mt_rand(), true));
}

/**
 * 生成唯一GUIDv4 标识
 * @param boolean $trim 结果是否有 花括号
 */
function GUIDv4($trim = false) {
	// Windows
	if (function_exists('com_create_guid') === true) {
		if ($trim === true) {
			return trim(com_create_guid(), '{}');
		} else {
			return com_create_guid();
		}

	}

	// OSX/Linux
	if (function_exists('openssl_random_pseudo_bytes') === true) {
		$data    = openssl_random_pseudo_bytes(16);
		$data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
		$data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
		return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
	}

	// Fallback (PHP 4.2+)
	mt_srand((double) microtime() * 10000);
	$charid = strtolower(md5(uniqid(rand(), true)));
	$hyphen = chr(45); // "-"
	$lbrace = $trim ? "" : chr(123); // "{"
	$rbrace = $trim ? "" : chr(125); // "}"
	$guidv4 = $lbrace .
	substr($charid, 0, 8) . $hyphen .
	substr($charid, 8, 4) . $hyphen .
	substr($charid, 12, 4) . $hyphen .
	substr($charid, 16, 4) . $hyphen .
	substr($charid, 20, 12) .
		$rbrace;
	return $guidv4;
}

// fan 2016-05-06 end

// fan 2016-04-10 start
// array_column 兼容处理
if (!function_exists("array_column")) {
	function array_column($array, $column_name) {
		return array_map(function ($element) use ($column_name) {return $element[$column_name];}, $array);
	}
}
// fan 2016-04-10 end

// fan 2016-04-18 start
/**

 * 发送模版消息主函数
 *
 * 注意事项
 * 1. 模版写入的时候会经过数据库类的 badword 函数做一次简单的敏感次替换处理
 * 2. 暂未对 特殊符号等做处理
 * 3. 后期可能需要增加更加强大高效的敏感词处理方法,同时过滤微信信息和特殊符号
 * 4. 注意发送是有开关的 如果 ###_template_msg_rule 表内没有默认打开消息开关 则必须在 ###_template_msg 表内有对应配置
 *
 * 示例
 * 调用id为8的模版规则.查询得知它接受4个参数
 * 接受方式二维数组
 * $receiver = array(
 *    'wechat' => '微信openid',
 *    'sms'    => '手机号码',
 *    'mail'   => '邮箱地址',
 *    'msg'    => '用户的mid',
 * );
 *
 * 第8条规则的4个参数,依次传入一维数组
 * $params = array('a', 'b', 'c', 'd');
 *
 * //调用第8条发送规则
 * send_template_msg(8, $receiver, $params);
 *
 * @param  integer $templateId ###_template_msg_rule 表的id 字段
 * @param  array   $receiver   接收方式,示例 array('wechat'=>'微信openid', 'msg'=>'用户id', 'mail'=>'邮件号', 'sms' =>'手机号')
 * @param  array   $params     模版变量一维数组, 注意顺序需与模版规则内完成相同 array(a,b,c,d);\
 * @param  string  $title      邮件站内信标题.留空的话会使用模版信息规则标题
 * @return boolean 成功true 失败false
 */

function send_template_msg($templateId = 0, array $receiver, array $params, $title = '') {
	if (empty($templateId)) {
		return false;
	}

	global $lowxp;

	$lowxp->load->model('template_msg');

	$rule = $lowxp->template_msg->dealRule($templateId, $params);

	if (empty($rule)) {
		return false;
	}

	$title = $title ? $title : $rule['title'];

	$lowxp->load->library('message');

	// 处理发送
	foreach ($receiver as $k => $v) {
		if (!empty($rule[$k])) {
			$lowxp->message->send($k, $v, $rule['content'], $rule['type'], $title);
		}
	}
}

/**
 * 全局信息发送函数
 * @param  string  $channel 发送渠道 wechart sms mail msg
 * @param  string  $target  发送目标
 * @param  string  $content 发送内容
 * @param  integer $type    消息分组 用来确定消息类别
 * @param  string  $title   邮件站内信标题.留空的话会使用模版信息规则标题
 * @return boolean          成功true 失败false
 */
function send_message($channel, $target, $content, $type = 0, $title = '') {
	global $lowxp;
	$lowxp->load->library('message');
	return $lowxp->message->send($channel, $target, $content, $type);
}

// fan 2016-04-18 end

/**
 * 写入积分函数
 * @param  integer $mid    用户id
 * @param  integer $type   积分类型
 * @param  integer $amount 积分数量
 * @param  string  $remark 积分备注
 * @return
 */
function score_log($mid, $type = 0, $amount, $remark = '') {
	global $lowxp;
	$lowxp->load->model('score');
	$data = array(
		'mid'    => $mid,
		'type'   => $type,
		'amount' => $amount,
		'remark' => $remark,
		'c_time' => time(),
	);
	return $lowxp->score->scoreLog($data);
}

/**
 * 获取自定义导航
 * @param  integer $type   导航类型
 * @param  integer $order  排序
 * @return
 */
function get_nav($type = 1, $order = 'listorder desc,id asc') {
	global $lowxp;
	$sql = "select * from ###_nav where type={$type} and status=1 order by {$order}";
	return $lowxp->db->select($sql);
}

/**
 * 十以内整数转汉字
 * @param  integer $num 十以内的整数
 * @return string       汉字
 */
function ten2chinese($num, $sim = false) {
	if ($sim) {
		$arrCn = array('零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖');
	} else {
		$arrCn = array('零', '一', '二', '三', '四', '五', '六', '七', '八', '九', '十');
	}

	return $arrCn[$num];
}

/**
 * 数字转换为中文
 * @param  string|integer|float  $num  目标数字
 * @param  integer $mode 模式[true:金额（默认）,false:普通数字表示]
 * @param  boolean $sim 使用小写（默认）
 * @return string
 */
function number2chinese($num, $mode = true, $sim = false) {
	if (!is_numeric($num)) {
		return '含有非数字非小数点字符！';
	}

	$char = $sim ? array('零', '一', '二', '三', '四', '五', '六', '七', '八', '九')
	: array('零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖');
	$unit = $sim ? array('', '十', '百', '千', '', '万', '亿', '兆')
	: array('', '拾', '佰', '仟', '', '萬', '億', '兆');
	$retval = $mode ? '元' : '';
	//小数部分
	if (strpos($num, '.')) {
		list($num, $dec) = explode('.', $num);
		$dec             = strval(round($dec, 2));
		if ($mode) {
			$retval .= "{$char[$dec['0']]}角{$char[$dec['1']]}分";
		} else {
			$retval = '点';
			for ($i = 0, $c = strlen($dec); $i < $c; $i++) {
				$retval .= $char[$dec[$i]];
			}
		}
	}
	//整数部分
	$str = $mode ? strrev(intval($num)) : strrev($num);
	for ($i = 0, $c = strlen($str); $i < $c; $i++) {
		$out[$i] = $char[$str[$i]];
		if ($mode) {
			$out[$i] .= $str[$i] != '0' ? $unit[$i % 4] : '';
			if ($i > 1 and $str[$i] + $str[$i - 1] == 0) {
				$out[$i] = '';
			}
			if ($i % 4 == 0) {
				$out[$i] .= $unit[4 + floor($i / 4)];
			}
		}
	}
	$retval = join('', array_reverse($out)) . $retval;
	return $retval;
}

/**
 * 获取用户名
 * @param  integer $mid 用户mid
 * @return string       用户名
 */
function getUsername($mid) {
	$cacheName = 'MEMBER_USERNAME_' . $mid;
	$username  = S($cacheName);
	if (!$username) {
		global $lowxp;
		try {
			$username = $lowxp->db->getstr('SELECT username FROM ###_member where mid =' . $mid);
		} catch (Exception $e) {
			$username = '';
		}
		$username && S($cacheName, $username);
	}
	return $username;
}

/**
 * 清空指定文件夹
 * @param  string  $dir       文件夹路径
 * @param  boolean $clearRoot 清理后是否删除本文件夹
 * @return integer
 */
function clear_dir($dir, $clearRoot = false) {
	if (!is_dir($dir)) {
		return 0;
	}

	//先删除目录下的文件：
	$handle = @opendir($dir);
	if ($handle === false) {
		return 0;
	}

	$count = 0;

	while (false != ($file = readdir($handle))) {
		if ($file != '.' && $file != '..') {
			$fullpath = $dir . DIRECTORY_SEPARATOR . $file;
			if (!is_dir($fullpath)) {
				if (@unlink($fullpath)) {
					++$count;
				}
			} else {
				$count += clear_dir($fullpath, true);
			}
		}
	}

	closedir($handle);

	//删除当前文件夹：
	$clearRoot && @rmdir($dir);

	return $count;
}

//判断是否为为微信浏览器
function isWechat() {
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger')) {
		return true;
	} else {
		return false;
	}
}
//跳转登陆
function login() {
	if (IS_WECHAT && C('reg_bind_sms')) {
		redirect('/member/mobile');
	} else {
		redirect('/member/login');
	}
}

/**
 * 加密为url可传递的参数
 * @param  mixed  $params 需要加密的参数 可以数数组 对象等 字符串等
 * @param  string $key
 * @return string         加密结果
 */
function url_encrypt($params, $key) {
	return urlencode(Crypt::encrypt(serialize($params), $key));
}

/**
 * url传递的参数解密
 * @param  string $string 密文
 * @param  string $key
 * @return mixed          解密结果
 */
function url_decrypt($string, $key) {
	try {
		return unserialize(Crypt::decrypt(urldecode($string), $key));
	} catch (Exception $e) {
		return false;
	}
}

/**
 * 调用微信接口长链接转短链接
 * @param  string $longUrl 长链接
 * @return string          短链接
 */
function get_short_url($longUrl) {
	$cacheName = 'SHORT_URL' . md5($longUrl);
	$shortUrl  = S($cacheName);
	if (!$shortUrl) {
		global $lowxp;
		$lowxp->load->model('wxapi');
		$shortUrl = $lowxp->wxapi->wechat->getShortUrl($longUrl);
		S($cacheName, $shortUrl);
	}
	return $shortUrl ?: $longUrl;
}

/** php防注入和XSS攻击通用过滤.
 * @param $arr
 * @param int $type
 * @return bool
 */
function SafeFilter(&$arr, $type = 0, $app = 0) {
	if ($type == 1) {
		if (is_array($arr)) {
			foreach ($arr as $key => $value) {
				if (!is_array($value)) {
					if (!get_magic_quotes_gpc()) {
						$value = stripslashes($value);
					}
					//$arr[$key] = $value;
					$arr[$key] = htmlspecialchars_decode($value, ENT_QUOTES);
				} else {
					SafeFilter($arr[$key], $type);
				}
			}
		}
		return false;
	}
	if (is_array($arr)) {
		foreach ($arr as $key => $value) {
			if (!is_array($value)) {
				if (!get_magic_quotes_gpc()) {
					$value = $app ? $value : addslashes($value);
				}

				//屏蔽可注入关键词
				$word_data = array(
					"execute", "update", "master", "truncate", "declare", "select",
					"create", "delete", "insert", "use", "load_file", "outfile", "extract",
					"eval", "group_concat",
				);
				$error_data = array("%", " ");
				foreach ($word_data as $v) {
					foreach ($error_data as $e) {
						$find[] = $v . $e;
					}
				}
				$find[] = "/**/";
				//$find = array(
				//     "execute%","execute ","update%","update ","master%","master ",
				//     "truncate%","truncate ","declare%","declare ","select%","select ",
				//    "create%","create ","delete%","delete ","insert%","insert ",
				//     "use%","use ","load_file%","load_file ","outfile%","outfile ",
				//    "extract%","extract ","eval%","eval ","group_concat%","group_concat ","/**/");
				$value     = str_ireplace($find, "", $value);
				$value     = strip_tags($value);
				$arr[$key] = $app ? $value : htmlspecialchars($value, ENT_QUOTES);
			} else {
				SafeFilter($arr[$key]);
			}
		}
	}
}

/**
 * 判断是否为新用户
 * @param  integer $mid 用户mid
 * @return boolean
 */
function is_new($mid = 0) {
	global $lowxp;
	if ($mid == 0) {
		return false;
	}

	$res = $lowxp->db->getstr('select is_new from ###_member where mid=' . $mid, "is_new");
	if ($res == 0) {
		return true;

		/*$num = $lowxp->db->get("select 1 from ###_goods_order where status_common=10 and extension_code>0 and mid=".MID);
			        if($num==false){
			            return true;
			        }else{
			            $lowxp->db->update("member",array("is_new"=>1),array("mid"=>$mid));
			            return false;
		*/
	} else {
		return false;
	}
}
/**
 * 修改会员为老用户
 * @return boolean
 */
function set_new($mid = 0) {
	global $lowxp;
	$res = $lowxp->db->getstr('select is_new from ###_member where mid=' . $mid, "is_new");
	if ($res == 0) {
		$lowxp->db->update("member", array("is_new" => 1), array("mid" => $mid));
	}
}

/**
 * 判断是否有参团资格 ( 1元购和免费试用 要新会员才能参团 )
 * @param  integer $mid 用户mid
 * @return boolean
 */
function check_team($mid = 0, $typeid = 0) {
	if ((is_new($mid) && ($typeid == CART_YUAN)) || ($typeid != CART_YUAN)) {
		return true;
	} else {
		return false;
	}
}

function photo_path($mid) {
	global $lowxp;
	$nopic = '/common/photo.gif';
	$mid   = intval($mid);
	if (empty($mid)) {
		return $nopic;
	}

	if (!S("H_MEMBER_DETAIL_" . $mid)) {
		$detail = $lowxp->db->get("select * from ###_member_detail where mid=" . $mid);
		S("H_MEMBER_DETAIL_" . $mid, $detail, 86400);
		$source = $detail['photo'];
	} else {
		$detail = S("H_MEMBER_DETAIL_" . $mid);
		$source = $detail['photo'];
	}
	$size = 80;
	if (empty($source)) {
		return $nopic;
	}

	$img = substr($source, 0, strpos($source, '.jpg'));
	$img = $img . '_' . $size . '.jpg';
	if (strpos($img, 'http://') === false && strpos($img, 'https://') === false) {
		$img = $lowxp->common['cloudurl'] . $img;
	}
	if (strpos($source, 'http://') === false && strpos($source, 'https://') === false) {
		$source = $lowxp->common['cloudurl'] . $source;
	}
	return is_file(RootDir . 'web' . $img) ? $img : $source;
}

/**
 * 获取广告
 * @param  integer $typeid 位置
 * @return boolean
 */
function getAd($typeid = 0) {
	global $lowxp;
	if ($typeid == 0) {
		return false;
	}

	//$sna = "CM_AD_".$typeid;
	//if(S($sna)){
	//    $res = S($sna);
	//}else{
	$res = $lowxp->db->getstr('select images from ###_ad where status=1 and typeid=' . $typeid, "images");
	//    S($sna,$res);
	//}
	return $res;
}
/**
 * 获取首页推广
 * @param  integer $typeid 位置
 * @return boolean
 */
function getSpread($typeid = 0) {
	global $lowxp;
	if ($typeid == 0) {
		return false;
	}

	$cache_name = "CH_SPREAD_INDEX_" . $typeid;
	if (S($cache_name)) {
		$goods_list = S($cache_name);
	} else {
		$res = $lowxp->db->get('select gid,`name`,`desc`,`thumb`,`url`,`iosurl`,`anurl` from ###_spread where status=1 and typeid=' . $typeid);
		if ($res['gid']) {
			$goods_list = $lowxp->db->select("select id,thumbs,team_price from ###_goods where id in({$res['gid']}) ");
			if ($goods_list) {
//有商品读取商品
				foreach ($goods_list as $k => $v) {
					$v['thumbs'] = json_decode($v['thumbs'], true);
					if ($v['thumbs'][0]['path']) {
						$v['thumbs'] = yunurl($v['thumbs'][0]['path']);
					}
					$res['url'] .= strpos($res['url'], "?") > 0 ? "&" : "?";
					$v['url']       = $res['url'] . "order=goods_id&sort=" . $v['id'];
					$v['iosurl']    = $res['iosurl'];
					$v['anurl']     = $res['anurl'];
					$v['tp_name']   = $res['name'];
					$v['tp_desc']   = $res['desc'];
					$goods_list[$k] = $v;
				}
			} else {
//没有商品读取默认图片
				$mp['thumbs'] = json_decode($res['thumb'], true);
				if ($mp['thumbs'][0]['path']) {
					$mp['thumbs'] = yunurl($mp['thumbs'][0]['path']);
				}
				$mp['id']         = 0;
				$mp['team_price'] = 0;
				$mp['url']        = $res['url'];
				$mp['iosurl']     = $res['iosurl'];
				$mp['anurl']      = $res['anurl'];
				$mp['tp_name']    = $res['name'];
				$mp['tp_desc']    = $res['desc'];
				$goods_list[]     = $mp;
			}
		} else {
//没有商品读取默认图片
			$mp['thumbs'] = json_decode($res['thumb'], true);
			if ($mp['thumbs'][0]['path']) {
				$mp['thumbs'] = yunurl($mp['thumbs'][0]['path']);
			}
			$mp['id']         = 0;
			$mp['team_price'] = 0;
			$mp['url']        = $res['url'];
			$mp['iosurl']     = $res['iosurl'];
			$mp['anurl']      = $res['anurl'];
			$mp['tp_name']    = $res['name'];
			$mp['tp_desc']    = $res['desc'];
			$goods_list[]     = $mp;
		}
		S($cache_name, $goods_list);
	}
	return $goods_list;
}

/**
 * 加密函数
 *
 * @param string $txt 需要加密的字符串
 * @param string $key 密钥
 * @return string 返回加密结果*/
function encrypt_en($txt, $key = '') {
	if (empty($txt)) {
		return $txt;
	}

	if (empty($key)) {
		$key = md5(MD5_KEY);
	}

	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
	$ikey  = "-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm";
	$nh1   = rand(0, 64);
	$nh2   = rand(0, 64);
	$nh3   = rand(0, 64);
	$ch1   = $chars{$nh1};
	$ch2   = $chars{$nh2};
	$ch3   = $chars{$nh3};
	$nhnum = $nh1 + $nh2 + $nh3;
	$knum  = 0;
	$i     = 0;
	while (isset($key{$i})) {
		$knum += ord($key{$i++});
	}

	$mdKey = substr(md5(md5(md5($key . $ch1) . $ch2 . $ikey) . $ch3), $nhnum % 8, $knum % 8 + 16);
	$txt   = base64_encode(time() . '_' . $txt);
	$txt   = str_replace(array('+', '/', '='), array('-', '_', '.'), $txt);
	$tmp   = '';
	$j     = 0;
	$k     = 0;
	$tlen  = strlen($txt);
	$klen  = strlen($mdKey);
	for ($i = 0; $i < $tlen; $i++) {
		$k = $k == $klen ? 0 : $k;
		$j = ($nhnum + strpos($chars, $txt{$i}) + ord($mdKey{$k++})) % 64;
		$tmp .= $chars{$j};
	}
	$tmplen = strlen($tmp);
	$tmp    = substr_replace($tmp, $ch3, $nh2 % ++$tmplen, 0);
	$tmp    = substr_replace($tmp, $ch2, $nh1 % ++$tmplen, 0);
	$tmp    = substr_replace($tmp, $ch1, $knum % ++$tmplen, 0);
	return $tmp;
}

/**
 * 解密函数
 *
 * @param string $txt 需要解密的字符串
 * @param string $key 密匙
 * @return string 字符串类型的返回结果*/
function decrypt_de($txt, $key = '', $ttl = 0) {
	if (empty($txt)) {
		return $txt;
	}

	if (empty($key)) {
		$key = md5(MD5_KEY);
	}

	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
	$ikey  = "-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm";
	$knum  = 0;
	$i     = 0;
	$tlen  = @strlen($txt);
	while (isset($key{$i})) {
		$knum += ord($key{$i++});
	}

	$ch1   = @$txt{$knum % $tlen};
	$nh1   = strpos($chars, $ch1);
	$txt   = @substr_replace($txt, '', $knum % $tlen--, 1);
	$ch2   = @$txt{$nh1 % $tlen};
	$nh2   = @strpos($chars, $ch2);
	$txt   = @substr_replace($txt, '', $nh1 % $tlen--, 1);
	$ch3   = @$txt{$nh2 % $tlen};
	$nh3   = @strpos($chars, $ch3);
	$txt   = @substr_replace($txt, '', $nh2 % $tlen--, 1);
	$nhnum = $nh1 + $nh2 + $nh3;
	$mdKey = substr(md5(md5(md5($key . $ch1) . $ch2 . $ikey) . $ch3), $nhnum % 8, $knum % 8 + 16);
	$tmp   = '';
	$j     = 0;
	$k     = 0;
	$tlen  = @strlen($txt);
	$klen  = @strlen($mdKey);
	for ($i = 0; $i < $tlen; $i++) {
		$k = $k == $klen ? 0 : $k;
		$j = strpos($chars, $txt{$i}) - $nhnum - ord($mdKey{$k++});
		while ($j < 0) {
			$j += 64;
		}

		$tmp .= $chars{$j};
	}
	$tmp = str_replace(array('-', '_', '.'), array('+', '/', '='), $tmp);
	$tmp = trim(base64_decode($tmp));

	if (preg_match("/\d{10}_/s", substr($tmp, 0, 11))) {
		if ($ttl > 0 && (time() - substr($tmp, 0, 11) > $ttl)) {
			$tmp = null;
		} else {
			$tmp = substr($tmp, 11);
		}
	}
	return $tmp;
}
/**
 *图片添加又怕云地址
 * $width又拍云缩放尺寸
 */
function yunurl($url, $width = "") {
	if ($url == '') {
		return '';
	}

	if (C('cloudsave') && strpos($url, "://") === false) {
		$url = C('cloudurl') . $url;
		if (!empty($width)) {
			$url .= "!/sq/" . $width;
		}
	} elseif (strpos($url, "://") === false) {
		$url = rtrim(RootUrl, '/') . $url;
	}
	return $url;
}
/**
 *判断字符串是否包含某值
 */
function find_in_set($str, $id) {
	if (empty($str) || empty($id)) {
		return false;
	}

	$arr = explode(',', $str);
	return in_array($id, $arr) ? true : false;
}

function rep_content($content) {
	$content = str_replace('"/upload/', '"' . RootUrl . 'upload/', $content);
	return $content;
}
/**
 *随机获取会员头像
 */
function rand_user($num = 2) {
	global $lowxp;
	$res_limit = $lowxp->db->get("select max(mid) as maxid,min(mid) as minid from ###_member_detail");
	$arr_user  = range($res_limit['minid'], $res_limit['maxid']);
	shuffle($arr_user);
	$res_arr = array_slice($arr_user, 0, $num);
	$str_mid = join(",", $res_arr);
	$result  = $lowxp->db->select("select photo from  ###_member_detail where mid in ($str_mid)");
	$nopic   = RootUrl . 'common/photo.gif';
	foreach ($result as $k => $v) {
		if (empty($v['photo'])) {
			$v['photo'] = $nopic;
		} else {
			$v['photo'] = yunurl($v['photo']);
		}
		$result[$k] = $v;
	}
	return $result;
}

/**
 *获取专题活动及报名的产品
 *1=>"首页",2=>"同城",3=>"海淘",4=>"秒杀",5=>"清仓"
 */
function getTA($posid = 0) {
	global $lowxp;
	$lowxp->load->model('topic');
	return $lowxp->topic->getTopicApply($posid);
}

/**
 *获取秒杀倒计时剩余时间
 */
function kill_etime() {
	$new_h = date("H", RUN_TIME);
	if ($new_h >= 10) {
//第二天10点
		$ntime = RUN_TIME + 24 * 3600;
		$etime = strtotime(date("Y-m-d 10:0:0", $ntime));
		$time  = $etime - RUN_TIME;
	} else {
//当天10点
		$etime = strtotime(date("Y-m-d 10:0:0", RUN_TIME));
		$time  = $etime - RUN_TIME;
	}
	return $time;
}

function genTree9($list) {
	$tree = array();
	foreach ($list as $k => $v) {
		if (isset($list[$v['parentid']])) {
			$list[$v['parentid']]['sub'][$k] = &$list[$k];
		} else {
			$tree[$v['id']] = &$list[$k];
		}
	}
	return $tree;
}

/**
 *获取顶级分类
 */
function genTop($list) {
	$tree = array();
	foreach ($list as $k => $v) {
		if ($v['parentid'] == 0 && $v['ismenu'] == 1) {
			$temp            = array();
			$temp['id']      = $v['id'];
			$temp['catname'] = $v['catname'];
			$tree[]          = $temp;
		}
	}
	return $tree;
}

/**
 *简化app分类字段
 */
function formatCat($list) {
	$tree = array();
	if ($list) {
		foreach ($list as $k => $v) {
			$temp            = array();
			$temp['id']      = $v['id'];
			$temp['catname'] = $v['catname'];
			$tree[]          = $temp;
		}
	}
	return $tree;
}
/**
 *json_decode 图片地址
 * $data array  要解析的数组
 * $field array  要解析的字段 thumb，thumbs
 */
function api_imgurl($data = array(), $field = array()) {
	if (empty($data)) {
		return '';
	}

	if (empty($field)) {
		return $data;
	}

	foreach ($data as $k => $v) {
		foreach ($field as $i) {
			if ($v[$i]) {
				$thumb        = json_decode($v[$i], true);
				$data[$k][$i] = yunurl($thumb[0]['path']);
			}
		}
	}
	return $data;
}

/*
 * 计算两点地理坐标之间的距离
 * @param  Decimal $longitude1 起点经度
 * @param  Decimal $latitude1  起点纬度
 * @param  Decimal $longitude2 终点经度
 * @param  Decimal $latitude2  终点纬度
 * @param  Int     $unit       单位 1:米 2:公里
 * @param  Int     $decimal    精度 保留小数位数
 * @return Decimal
 */
function getDistance($longitude1, $latitude1, $longitude2, $latitude2, $unit = 2, $decimal = 2) {

	$EARTH_RADIUS = 6370.996; // 地球半径系数
	$PI           = 3.1415926;

	$radLat1 = $latitude1 * $PI / 180.0;
	$radLat2 = $latitude2 * $PI / 180.0;

	$radLng1 = $longitude1 * $PI / 180.0;
	$radLng2 = $longitude2 * $PI / 180.0;

	$a = $radLat1 - $radLat2;
	$b = $radLng1 - $radLng2;

	$distance = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
	$distance = $distance * $EARTH_RADIUS * 1000;

	if ($unit == 2) {
		$distance = $distance / 1000;
	}

	return round($distance, $decimal);

}

if (!function_exists('exif_imagetype')) {
	function exif_imagetype($filename) {
		if ((list($width, $height, $type, $attr) = getimagesize($filename)) !== false) {
			return $type;
		}
		return false;
	}
}

function gd_extension($full_path_to_image = '') {
	$extension = 'null';
	if ($image_type = exif_imagetype($full_path_to_image)) {
		$extension = image_type_to_extension($image_type, false);
	}
	$known_replacements = array(
		'jpeg' => 'jpg',
		'tiff' => 'tif',
	);
	$extension = str_replace(array_keys($known_replacements), array_values($known_replacements), $extension);

	return $extension;
}

/**
 * 创建gd库文件
 * @param  string $filePath [description]
 * @return [type]           [description]
 */
function gd_create_img($filePath = '') {

	$type = gd_extension($filePath);
	switch ($type) {
		case 'jpg':
			$type = 'jpeg';
			break;
		case 'gif':
			$type = 'gif';
			break;
		case 'png':
			$type = 'png';
			break;
		default:
			$type = 'jpeg'; // qq头像无后缀名 默认用jpeg 打开
			break;
	}

	$fn  = 'imagecreatefrom' . $type;
	$img = $fn($filePath);
	return $img;
}

function gd_write_str($img, $str, $x, $y, $font, $color, $row, $per_row = 5, $font_size = 20, $angle = 0) {
	$length = mb_strlen($str);

	for ($i = 0; $i < $row; $i++) {
		$next = $i + 1;
		if ($next == $row && $next * $per_row < $length) {
			$tmp = mb_substr($str, $i * $per_row, $per_row - 1, 'utf-8') . '...';
		} else {
			$tmp = mb_substr($str, $i * $per_row, $per_row, 'utf-8');
		}
		imagettftext($img, $font_size, $angle, $x, $y, $color, $font, $tmp);
		$y += 30;
	}
}

function gd_round_bg($src_img) {
	$w = ImageSX($src_img);
	$h = ImageSY($src_img);

	$img = imagecreatetruecolor($w, $w);
	imagesavealpha($img, true);
	$bg = imagecolorallocatealpha($img, 255, 255, 255, 127);
	imagefill($img, 0, 0, $bg);
	$r   = $w / 2;
	$y_x = $r;
	$y_y = $r;
	for ($x = 0; $x < $w; $x++) {
		for ($y = 0; $y < $h; $y++) {
			$rgbColor = imagecolorat($src_img, $x, $y);
			if (((($x - $r) * ($x - $r) + ($y - $r) * ($y - $r)) < ($r * $r))) {
				imagesetpixel($img, $x, $y, $rgbColor);
			}
		}
	}
	return $img;
}