<?php
/**
 * Class wechat
 */

class wechat extends Lowxp_Controller {
	public function __construct() {
		parent::__construct();
		// header('Content-Type:text/html; charset=utf-8');
		$this->load->model('session');
		$config = $this->load->config('database');
		$this->load->database($config);
		include AppDir . 'function/common.php';
		$this->load->library('base');
		
	}

	public function index() {
                $this->load->model('wxapi');
		$this->wxapi->wechat->valid();
		$param = $this->wxapi->wechat->getRev()->getRevData();
		// 默认回应 插件应该在这个之前
		switch ($param['MsgType']) {
			case 'event':
				$this->replyEvent($param);
				break;
			case 'location':
				break;
			// case 'text':
			// case 'image':
			// case 'voice':
			// case 'video':
			// case 'shortvideo':
			// case 'link':
			default:

				## 关键词自动回复.
				$this->wxapi->smartReply($param['Content'], $param['FromUserName'], $param['ToUserName']);

				## 默认消息回复（没有找到对应关键词）
				$rule = $this->db->get("SELECT * FROM ###_wx_autoreply_rule WHERE rule_type='autoreply' ");
				if (!empty($rule['id'])) {
					$this->wxapi->outputByRuleId($rule['id']);
					exit;
				}

				// 关键字匹配失败则传到多客服
				$this->wxapi->wechat->transfer_customer_service();

				## 所有条件均匹配失败  默认回复
				$this->wxapi->wechat->text('请访问微站查看更多资源哦')->reply();
				break;
		}
		exit;
	}

	/**
	 * 处理事件类型的消息
	 * 注意新版本的微信客服不在发送接通推送消息 也无法在新网页版微信客服客户端主动关闭会话
	 * @param  array $param 微信传递过来的参数
	 * @return void
	 */
	public function replyEvent($param) {
                $this->load->model('wxapi');
		switch ($param['Event']) {
			case 'CLICK': #检查菜单中的事件内容.
				$this->wxapi->checkMenuEvent($param['EventKey'], $param['FromUserName'], $param['ToUserName']);
				break;
			case 'subscribe':
				$this->wxapi->subscribe($param);
				break;
			case 'unsubscribe':
				$this->wxapi->unsubscribe($param);
				break;
			case 'kf_create_session':
				$text = '您好! 客服:' . $param['KfAccount'] . '为您服务。';
				$this->wxapi->wechat->text($text)->reply();
				break;
			case 'kf_close_session':
				$text = '感谢咨询, 客服已断开。工作时段向本号发送[客服]二字,稍后就会有人为您服务';
				$this->wxapi->wechat->text($text)->reply();
				break;
			case 'kf_switch_session':
				$text = '请稍候, 已转接到客服:' . $param['ToKfAccount'] . '为您服务';
				$this->wxapi->wechat->text($text)->reply();
				break;
			default:
				break;
		}
	}

	/**
	 * 连接主动发起微信客服会话
	 * @return void
	 */
	public function customService() {
                $this->load->model('wxapi');
		if (empty($_SESSION['wxoauth']['open_id'])) {
			if (empty($_SESSION['mid'])) {
				exit(json_encode(array('error' => 1, 'msg' => '请先登录', 'tag' => '9')));
			}

			// 获取openid
			$mid  = $_SESSION['mid'];
			$user = $this->db->get('SELECT mid, openid FROM ###_member where mid = "' . $mid . '"');

			$openid = empty($user['openid']) ? '' : $user['openid'];
		} else {
			$openid = $_SESSION['wxoauth']['open_id'];
		}

		if (!$openid) {
			exit(json_encode(array('error' => 2, 'msg' => '对不起，绑定微信账号才能使用微信客服', 'tag' => '8')));
		}

		// 创建会话
		$this->wxapi->createSession($openid);

	}

	/**
	 * 关闭微信内部浏览器
	 */
	public function close() {
                $this->load->model('wxapi');
		$url         = getUrl();
		$signPackage = $this->wxapi->wechat->getJsSign($url);
		$html        = <<<EOT
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <script type="text/javascript">
    wx.config({
        debug: false,
        appId: '{$signPackage['appId']}',
        timestamp: '{$signPackage['timestamp']}',
        nonceStr: '{$signPackage['nonceStr']}',
        signature: '{$signPackage['signature']}',
        jsApiList: [
            'closeWindow'
        ]
    });
    wx.ready(function() {
        wx.closeWindow();
    });
    wx.error(function(res) {
        alert(res.errMsg);
    });
    </script>
EOT;
		echo $html;
	}

	//生成微信二维扫码
	public function build_qrcode() {
		if (isset($_GET['data'])) {
			$data = urldecode($_GET['data']);
			require_once AppDir . 'library/phpqrcode/phpqrcode.php';
			QRcode::png($data, false, "L", 6);
		}

		exit();
	}

}