<?php
/**
 * Class welcome
 */
class welcome extends Lowxp {

	/**
	 * IndexAction
	 */
	function index() {
		echo '<div style="font-family:\5FAE\8F6F\96C5\9ED1;font-size:24px;padding:10px;">欢迎光临 :)</div>';
	}

	function setImgSize() {
		return;

		$rows = $this->db->select("SELECT * FROM images");

		foreach ($rows as $val) {
			$path = $this->load->config('picture','image_dir') . $val['cate'] . '/' . $val['imgurl'];
			if (is_file($path)) {
				$data = getimagesize($path);
				#echo '<pre>';print_r($data);echo '</pre>';
				$size = $data[0] . 'x' . $data[1];

				$this->db->update('images', array('size' => $size), array('id' => $val['id']));
			}
		}
	}
    function scode(){
        $def = array(1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f','g','h','i','j','k','l','m','n','p','q','r','s','t','u','v','w','x','y','z');//去除难以区分的0和o
        $keys = array_rand($def,4);

        $code = $imgstr = '';
        foreach($keys as $v){
            $code.= $def[$v];
            $imgstr.= ' '.$def[$v];
        }
        $_SESSION['scode'] = strtolower($code);

        $im = imagecreate(100,40);
//$gray = imagecolorallocate($im, 255,244,199);
        $gray = imagecolorallocate($im, rand(230,255),rand(230,255),rand(230,255));
        imagefill($im, 0,0, $gray);

        for($i=0;$i<20;$i++){
            $color = imagecolorallocate($im, rand(0,255), rand(0,255), rand(0,255));
            imagesetpixel($im, rand()%90, rand()%30, $color);
        }

        imagettftext($im, 14, 10, 10, 34, imagecolorallocate($im, rand(0,128), rand(0,128), rand(0,128)),RootDir.'web/admin/fonts/scode.ttf', strtolower($imgstr));

        header('content-type: image/png');
        imagepng($im);
        imagedestroy($im);
    }

    /**
     * 算术验证码
     */
    function scode_bak() {
        //生成验证码图片
        Header("Content-type: image/PNG");

        srand((double) microtime() * 1000000);

        // $w = 95; //宽
        // $h = 34; //高

        $w = 210; //宽
        $h = 70; //高

        $authnum = 0;

        $randnuml = rand(2, 19);

        $randnumr = rand(1, 19);

        $chars = array('+', '-');

        $char = array_rand($chars, 1);

        $char = $chars[$char];
        switch ($char) {
            case '+':
                $authnum = $randnuml + $randnumr;
                $char    = '加';
                break;
            case '-':
                $randnumr = rand(0, $randnuml - 1);
                $authnum  = $randnuml - $randnumr;
                $char     = '减';
                break;
            default:break;
        }

        $_SESSION['scode'] = iconv('gbk', 'utf-8', $authnum);

        $im = imagecreate($w, $h);

        $gray = imagecolorallocate($im, rand(230, 255), rand(230, 255), rand(230, 255));

        imagefill($im, 0, 0, $gray);

        /*创建干扰线等*/
        for ($i = 0; $i < 5; $i++) {
            $color = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));

            imagearc($im, mt_rand(-10, $w), mt_rand(-10, $h), mt_rand(30, 150), mt_rand(20, 200), 55, 44, $color);
        }

        for ($i = 0; $i < 20; $i++) {
            $color = imagecolorallocate($im, rand(0, 255), rand(0, 255), rand(0, 255));

            imagesetpixel($im, rand() % 90, rand() % 30, $color);
        }

        $fontSize = 26;
        $codeNX   = -20;

        $answer = $this->ninteen2cn($randnuml) . $char . $this->ninteen2cn($randnumr);
        $answer = preg_split('/(?<!^)(?!$)/u', $answer);
        foreach ($answer as $v) {
            $codeNX += mt_rand($fontSize * 1.3, $fontSize * 1.5);
            imagettftext($im, $fontSize, mt_rand(-20, 20), $codeNX, $fontSize * 1.8, imagecolorallocate($im, rand(0, 128), rand(0, 128), rand(0, 128)), RootDir . 'web/admin/fonts/msyh.ttf', $v);
        }

        imagepng($im);

        imagedestroy($im);
    }

    // 只能把整数0到19转成汉字
    function ninteen2cn($int) {
        $res   = '';
        $arrCn = array('零', '一', '二', '三', '四', '五', '六', '七', '八', '九', '十');

        if ($int > 10) {
            $int = substr($int, 1);
            $res = '十' . $arrCn[$int];
        } elseif ($int <= 10) {
            $res = $arrCn[$int];
        }

        return $res;
    }

	/** TODO:短信验证码获取入口
	 * verify_code status字段说明（后续添加其它验证码需要往下扩展说明）
	 * 1注册验证状态 2注册成功状态
	 * 3
	 */
	function sms() {
		$iTime = 60; #第ip第隔多少秒才能获取一次

		//总开关
		if (!$this->site_config['sms_open']) {
			$this->error('短信接口关闭,请联系客服');
		}

		//  验证码
		$is_scode = isset($_POST['is_scode']) ? trim($_POST['is_scode']) : 2;
		if($is_scode==1){
            $scode = isset($_POST['scode']) ? trim($_POST['scode']) : '';

            if (!$scode) {
                $this->error('请输入验证码计算答案');
            }

            if ($scode != $_SESSION['scode']) {
                $this->error('对不起,答案错误');
            }
        }


		$act = isset($_POST['act']) ? trim($_POST['act']) : '';
		if (empty($act)) {
			die;
		}

		$mobile = isset($_POST['mobile']) ? trim($_POST['mobile']) : '';
		//手机号为空时发送会员手机号
		if (empty($mobile) && $_SESSION['mid']) {
			$this->load->model('member');

			$member = $this->member->member_info($_SESSION['mid']);

			$mobile = $member['mobile'];
		}

		//模板开关
		if (!statusTpl($act)) {
			die;
		}

		//提交的手机号是否正确
		if (empty($mobile) || ($this->base->validate($mobile, 'mobile') == false)) {
			$this->error('请填写正确的手机号!');
		}

		//需要重新设置的变量
		$status = 1;

		//注册验证码

		switch ($act) {
            case 'sms_code':
				//通用验证码
				$status = 1;
				break;
			case 'sms_register':
				//注册
				$status = 2;
				//提交的手机号是否已经注册帐号
				$sql = "SELECT COUNT(mid) FROM `###_member` WHERE mobile = '$mobile'";
				if ($this->db->getstr($sql) > 0) {
					$this->error('手机号已经注册过');
				}
				break; 
            case 'sms_chpass':
                //找回密码
                $status = 3;
                //提交的手机号是否存在
				$sql = "SELECT COUNT(mid) FROM `###_member` WHERE mobile = '$mobile'";
				if ($this->db->getstr($sql) == 0) {
					$this->error('手机号不存在');
				}
                    break;
            case 'sms_chpaypass':
				//支付密码验证
				$status = 4;
				break;
			case 'sms_bankcard':
				//银行卡验证
				$status = 5;
				break;
            case 'sms_business_reg':
				//商家入驻
				$status = 6;
				//提交的手机号是否已经存在
				$sql = "SELECT 1 FROM `###_business` WHERE mobile = '$mobile'";
				if ($this->db->getstr($sql) > 0) {
					$this->error('手机号已经注册过');
				}
				break;
            case 'sms_business_getpwd':
				//商家找回密码
				$status = 7;
				//提交的手机号是否已经存在
				$sql = "SELECT 1 FROM `###_business` WHERE mobile = '$mobile'";
				if ($this->db->getstr($sql) == 0) {
					$this->error('手机号不存在');
				}
				break;  
			default:
				# code...
				break;
		}

		//获取验证码请求是否获取过
		$sql = "SELECT COUNT(id) FROM `###_verify_code` WHERE status='$status' AND mobile='$mobile' AND getip='" . getIP() . "' AND dateline>'" . (RUN_TIME - $iTime) . "'";
		if ($this->db->getstr($sql) > 0) {

			$this->error('每IP每手机号每' . $iTime . '秒只能获取一次验证码。');
		}

		//获取验证码 存疑
		// if (isset($_SESSION['voiceVerify'])) {
		// 	unset($_SESSION['voiceVerify']);
		// }

		$this->load->library('sms');

		$verifycode = $this->sms->getVerifyCode();

		$this->smarty->assign('verify_code', $verifycode);

		//发送短信
		$ret = $this->sms->sendSmsTpl($mobile, $act);
		if ($ret === true) {
			$data = array(
				'mobile'     => $mobile,
				'getip'      => getIP(),
				'verifycode' => $verifycode,
				'dateline'   => time(),
				'status'     => $status,
			);

			$this->db->save('###_verify_code', $data);

			$this->success('短信验证码已发送,请注意查收');
		} else {

			$msg = $ret ? $ret : '手机短信验证码发送失败!';
			$this->error($msg);
		}
	}

	/**
	 * 语音验证码入口
	 */
	function voice() {
		$iTime = 60; #第ip第隔多少秒才能获取一次
		//手机号为空时发送会员手机号
		if ($_SESSION['mid']) {
			$this->load->model('member');

			$member = $this->member->member_info($_SESSION['mid']);

			$mobile = $member['mobile'];
		} else {
			$mobile = isset($_REQUEST['mobile']) ? trim($_REQUEST['mobile']) : '';
		}
		//提交的手机号是否正确
		if (empty($mobile) || ($this->base->validate($mobile, 'mobile') == false)) {
			$result['error'] = 1;

			$result['message'] = '请填写正确的手机号!';
			die(json_encode($result));
		}
		if (!$_SESSION['mid']) {
			//提交的手机号是否已经注册帐号
			$sql = "SELECT COUNT(mid) FROM ###_member WHERE mobile = '$mobile'";
			if ($this->db->getstr($sql) > 0) {
				$result['error'] = 2;

				$result['message'] = '手机号已经被注册过!';
				die(json_encode($result));
			}
		}
		$status = 10;

		//获取验证码请求是否获取过
		$sql = "SELECT COUNT(id) FROM ###_verify_code WHERE status='$status' AND mobile='$mobile' AND getip='" . getIP() . "' AND dateline>'" . (time() - $iTime) . "'";
		if ($this->db->getstr($sql) > 0) {
			$result['error'] = 3;

			$result['message'] = '每IP每手机号每' . $iTime . '秒只能获取一次验证码。';
			die(json_encode($result));
		}

		$verifycode = voiceVerify($mobile);
		if ($verifycode) {
			$data = array(
				'mobile'     => $mobile,
				'getip'      => getIP(),
				'verifycode' => $verifycode,
				'dateline'   => time(),
				'status'     => $status,
			);

			$this->db->save('###_verify_code', $data);

			$result['error'] = 0;

			$result['message'] = '语音验证码发送成功';
			die(json_encode($result));
		} else {
			$result['error'] = 4;

			$result['message'] = '语音验证码发送失败!';
			die(json_encode($result));
		}

	}

	/**
	 * 支付返回地址
	 */
    function respond($order_sn = "") {

        include_once AppDir . 'function/payment.php';
        /* 支付方式代码 */
        $pay_code = !empty($_REQUEST['code']) ? trim($_REQUEST['code']) : 'wxpay';

        $icon = 0;

        if (empty($_REQUEST['code']) && !empty($order_sn)) {

            $pay_info = $this->db->get("SELECT p1.is_paid,p2.common_id FROM ###_pay_log as p1 left join ###_goods_order as p2 on p1.order_id=p2.id WHERE p1.log_id = '$order_sn'");

            $icon = 2;

            $is_success = $pay_info['is_paid'] ? 1 : 0;

            //$msg = $pay_info['is_paid'] ? '支付成功' : '支付失败';
            $msg = '支付成功';

            if($pay_info['common_id']>0){
                $url = url('/goods/team/'.$pay_info['common_id'].'?show=1');
            }else{
                $url = url('/member/order');
            }

        } else {
            //获取首信支付方式
            if (empty($pay_code) && !empty($_REQUEST['v_pmode']) && !empty($_REQUEST['v_pstring'])) {
                $pay_code = 'cappay';
            }

            //获取快钱神州行支付方式
            if (empty($pay_code) && ($_REQUEST['ext1'] == 'shenzhou') && ($_REQUEST['ext2'] == 'ecshop')) {
                $pay_code = 'shenzhou';
            }

            //获取微信支付方式
            if (isset($_POST['return_code']) && isset($_POST['result_code'])) {
                $pay_code = 'wxpay';

                //处理微信回调参数
                $file_in = file_get_contents("php://input");

                $xml = new DOMDocument();

                $xml->loadXML($file_in);

                $outTradeNos = $xml->getElementsByTagName('out_trade_no');
                foreach ($outTradeNos as $outTradeNo) {
                    $_POST['out_trade_no'] = $outTradeNo->nodeValue;
                }
                $resultCodes = $xml->getElementsByTagName('result_code');
                foreach ($resultCodes as $resultCode) {
                    $_POST['result_code'] = $resultCode->nodeValue;
                }
                $returnCodes = $xml->getElementsByTagName('return_code');
                foreach ($returnCodes as $returnCode) {
                    $_POST['return_code'] = $returnCode->nodeValue;
                }
            }

            /* 参数是否为空 */
            if (empty($pay_code)) {
                $msg = '支付方式不存在';
            } else {
                /* 检查code里面有没有问号 */
                if (strpos($pay_code, '?') !== false) {
                    $arr1 = explode('?', $pay_code);

                    $arr2 = explode('=', $arr1[1]);

                    $_REQUEST['code'] = $arr1[0];

                    $_REQUEST[$arr2[0]] = $arr2[1];

                    $_GET['code'] = $arr1[0];

                    $_GET[$arr2[0]] = $arr2[1];

                    $pay_code = $arr1[0];
                }

                /* 判断是否启用 */
                $payment = $this->db->get("SELECT * FROM ###_payment WHERE pay_code = '$pay_code' AND enabled = 1");
                if (empty($payment)) {
                    $msg = '支付方式不可用';
                } else {
                    $plugin_file = AppDir . 'includes/modules/payment/' . $pay_code . '.php';

                    /* 检查插件文件是否存在，如果存在则验证支付是否成功，否则则返回失败信息 */
                    if (is_file($plugin_file)) {
                        /* 根据支付方式代码创建支付类的对象并调用其响应操作方法 */
                        include_once $plugin_file;

                        $payment = new $pay_code();
                        if (isset($_REQUEST['ajax'])) {
                            if ((@$payment->respond())) {echo 'ok';die;} else {echo 'error';die;}
                        } else {
                            if (@$payment->respond()) {
                                $msg = '恭喜您，支付成功！<br><span style="font-size:16px;color:#666;font-weight:normal">Congratulations,payment is successful!</span>';

                                $icon = 2;
                            } else {
                                $msg = '支付失败';
                            }
                        }
                    } else {
                        $msg = '支付方式不存在';
                    }
                }
            }
            $url = url('/member/order');
        }
        if ($_REQUEST['ajax']) {
            die(json_encode(array('is_success' => $is_success)));
        }
        if (STPL == 'mobile') {
            $this->success($msg,$url);
        }else{
            $this->success($msg,'/');
        }
        /*$this->msg($msg, array('iniframe' => false, 'url' => url('/member/recchage'), 'icon' => $icon, 'link' => ($icon == 2) ? array(
            array('text' => '我的订单', 'link' => '/member/order'),
        ) : array(
            array('text' => '继续充值', 'link' => '/member/recchage'),
            array('text' => '返回首页', 'link' => '/'),
        )));*/
    }

    /**
     * APP异步回调地址
     */
    function apprespond($payment = "") {

        include_once AppDir . 'function/payment.php';
        /* 支付方式代码 */
        $pay_code = !empty($payment) ? trim($payment) : 'alipayapp';
        $icon = 0;
        //获取微信支付方式
        if (isset($_POST['return_code']) && isset($_POST['result_code'])) {
            $pay_code = 'wxpay';

            //处理微信回调参数
            $file_in = file_get_contents("php://input");

            $xml = new DOMDocument();

            $xml->loadXML($file_in);

            $outTradeNos = $xml->getElementsByTagName('out_trade_no');
            foreach ($outTradeNos as $outTradeNo) {
                $_POST['out_trade_no'] = $outTradeNo->nodeValue;
            }
            $resultCodes = $xml->getElementsByTagName('result_code');
            foreach ($resultCodes as $resultCode) {
                $_POST['result_code'] = $resultCode->nodeValue;
            }
            $returnCodes = $xml->getElementsByTagName('return_code');
            foreach ($returnCodes as $returnCode) {
                $_POST['return_code'] = $returnCode->nodeValue;
            }
        }

        /* 参数是否为空 */
        if (empty($pay_code)) {
            return false;
        } else {

            /* 判断是否启用 */
            $payment = $this->db->get("SELECT * FROM ###_payment WHERE pay_code = '$pay_code' AND enabled = 1");
            if (empty($payment)) {
                return false;
            } else {
                $plugin_file = AppDir . 'includes/modules/payment/' . $pay_code . '.php';

                /* 检查插件文件是否存在，如果存在则验证支付是否成功，否则则返回失败信息 */
                if (is_file($plugin_file)) {
                    /* 根据支付方式代码创建支付类的对象并调用其响应操作方法 */
                    include_once $plugin_file;
                    $payment = new $pay_code();
                    if ((@$payment->respond($payment))){echo 'success';die;} else {echo 'faild';die;}
                } else {
                    return false;
                }
            }
        }
    }

    //支付宝退款处理
    function alipayrefund(){
        $plugin_file = AppDir . 'includes/modules/payment/alipay_wap.php';
        include_once $plugin_file;
        $payment = new alipay_wap();
        $payment->refund();
    }

	/**
	 * 清除缓存
	 */
	function clearCaches() {
		if (!$_POST) {
			die;
		}

		$type = trim($_POST['type']);

		$count = $this->base->clear_caches($type);
		die($count);
	}

	/** 管理员授权前台会员登录
	 * @param $mid
	 */
	function auth_login($mid) {
		if (isset($_SESSION['gid']) && $_SESSION['gid'] < 2) {
			$this->load->model('member');
			if ($_SESSION['mid']) {
				$this->member->logout();
			}
			$user = $this->db->get("SELECT * FROM ###_member WHERE mid='" . $mid . "'");

			$this->member->setLogin($user, 1);
			exit($this->msg('授权登录中...', array('iniframe' => false, 'url' => '/member')));
		} else {
			$this->msg();
		}
	}

	/**
     * 临时二维码生成
     */
    function qrcode(){
        $url = isset($_GET['url']) ? trim($_GET['url']) : '';
    	$common_id = isset($_GET['common_id']) ? trim($_GET['common_id']) :'0';
        include AppDir.'library/phpqrcode/phpqrcode.php';
        $errorCorrectionLevel = 'L';//容错级别
        $matrixPointSize = 8;//生成图片大小
        if($common_id>0){
	        $dir = RootDir.'web/upload/images/qrcode/common_'.$common_id.'.png';
	        //生成二维码图片
	        QRcode::png($url,$dir,$errorCorrectionLevel,$matrixPointSize,2,true);
        }else{
        	QRcode::png($url,false,$errorCorrectionLevel,$matrixPointSize,2);
        }
    }


	

}