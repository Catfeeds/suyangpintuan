<?php

class test extends Lowxp {
	function __construct() {
		parent::__construct();
	}
    function lt(){
        echo L('unit_score');
    }
	function score() {

		$this->load->model('coupon');
		$this->coupon->sendMany('353', 1, 1);
	}

	function short() {
		echo md5(Crypt::encrypt('lnestfenxiaoxitong', AuthKey));
	}

	function get() {
		// require AppDir . 'library/queue/driver/RedisMessageQueue.php';

		// $rmq = new RedisMessageQueue();

		// $rmq->puts(1, 2, 3, 4);

		require AppDir . 'library/queue/Queue.php';
		$queue = new Queue();
		$queue::put(1);
		$queue->put(2);
		$queue::put(3);
		$queue->put(4);
		echo $queue::get();
		echo $queue->get();
	}

	function encode() {

		$tmp = array(
			'sid' => MERID,
			'id'  => 1,
		);

		$token = '?t=' . url_encrypt($token, AuthKey);

		echo url('/test/decode/' . $token);

	}

	function decode($aaa) {
		echo $aaa;
		print_r($_GET);
	}

	function queue() {

		$receiver = array(
			// 'wechat' => 'oFVX-jtF0QWeNAB6YbxVse12gzS8',
			// 'sms'    => '18627181357',
			'mail' => '2276755572@qq.com',
			'msg'  => '353',
		);

		$params = array('a', 'b', 'c', 'd');

		send_template_msg(8, $receiver, $params);
		$this->load->model('template_msg');
		$this->template_msg->inQueue(8, $params);
	}

	public function qr() {
		// $data = 'aaa';
		// require_once AppDir . 'library/phpqrcode/phpqrcode.php';
		// QRcode::png($data, false, "L", 4);
		// exit();
		$this->smarty->caching = false;
		require_once AppDir . 'library/phpqrcode/phpqrcode.php';
		QRcode::png('http://www.cnblogs.com/txw1958/');
		// var_dump(creat_code('http://www.cnblogs.com/txw1958/', '123.png'));
	}
	public function getReceiver() {

		$this->load->model('template_msg');
		echo $this->template_msg->getContactByMid(353);

	}

	function in() {
		$this->load->model('template_msg');

		$data = array(
			array(
				8,
				'1,2,3',
				array('a', 'b', 'c'),
			),
		);

		$this->template_msg->inQueueMany($data);

		print_r($data);
	}

	function out() {

		$this->load->model('template_msg');
		$this->template_msg->dealQueue(100);

	}

	function testread() {
		$this->load->model('message');
		echo $this->message->getUnRead(568);

	}

	function e2() {
		echo 123;
	}

	function param() {

		echo '<pre>';
		echo '<br>';

		echo '<br>';
		echo 'session';
		echo '<br>';
		print_r($_SESSION);

	}

	function filequeue() {

		require AppDir . 'library/queue/driver/File.php';

		$queue = new File();
		$aaa   = $queue->get();

		// $queue->put(1);
		// $queue->put(2);
		// $queue->put(3);
		$queue->puts(4, 1, 2, 3);

		//$ids = ['1', '2', '3', '4'];
		//$logs = array_merge(array('mid'), $ids);

	}

	function cal() {
		$this->db->query('BEGIN');
		$this->db->insert('message', array('title' => 1));

		sleep(10);

		$update = $this->db->update('wx_logs_copy', 'content=1', 'id=4');

		if (false !== $update) {
			// if ($update !== false) {
			$this->db->query('COMMIT');
		} else {
			$this->db->query('ROLLBACK');
		}

		// echo $this->db->update('wx_logs_copy', 'content=1', 'id=0');
		// echo $this->db->insert('wx_logs_copy', 'content=1');
	}

	function checkredpack() {
		$this->load->library('wechatredpack');
		$res = $this->wechatredpack->checkRedPack('4');
		var_dump($res);
	}

	function redpack() {
		$openid      = 'oFVX-jtF0QWeNAB6YbxVse12gzS8';
		$billno      = '1';
		$totalAmount = 100;
		$sendName    = '测试发送人';
		$wishing     = '测试祝福语';
		$remark      = '测试备注';
		$actName     = '测试活动名称';

		$this->load->library('wechatredpack');
		$res = $this->wechatredpack->sendRedpack($openid, $billno, $totalAmount, $sendName, $wishing, $remark, $actName);
		var_dump($res);
	}

	function groupredpack() {
		$openid      = 'oFVX-jtF0QWeNAB6YbxVse12gzS8';
		$billno      = '4';
		$totalAmount = 100;
		$totalNum    = 2;
		$sendName    = '测试发送人';
		$wishing     = '测试祝福语';
		$remark      = '测试备注';
		$actName     = '测试活动名称';

		$this->load->library('wechatredpack');
		$res = $this->wechatredpack->sendGroupRedPack($openid, $billno, $totalAmount, $totalNum, $sendName, $wishing, $remark, $actName);
		var_dump($res);
	}

	function testmsg() {
		$receiver = array(
			// 'wechat' => 'oFVX-jtF0QWeNAB6YbxVse12gzS8',
			// 'sms'    => '18627181357',
			'mail' => '2276755572@qq.com',
			'msg'  => '353',
		);

		$params = array('a', 'b', 'c', 'd');

		send_template_msg(8, $receiver, $params);
	}

	function curlfile($filepath) {
		if (class_exists('CURLFile')) {
			$field = array('media' => new CURLFile(realpath($filepath)));
		} else {
			$field = array('media' => '@' . realpath($filepath));
		}
		return $field;
	}

	function log() {

		// 直接写入
		log::write('EMERG', 'EMERG'); // 第一个参数 为log信息 第二个参数为等级

		// 缓存到内存 但不写入
		log::record('EMERG', 'EMERG'); // 严重错误: 导致系统崩溃无法使用
		log::record('ALERT', 'ALERT'); // 警戒性错误: 必须被立即修改的错误
		log::record('CRIT', 'CRIT'); // 临界值错误: 超过临界值的错误，例如一天24小时，而输入的是25小时这样
		log::record('ERR', 'ERR'); // 一般错误: 一般性错误
		log::record('WARN', 'WARN'); // 警告性错误: 需要发出警告的错误
		log::record('NOTIC', 'NOTIC'); // 通知: 程序可以运行但是还不够完美的错误
		log::record('INFO', 'INFO'); // 信息: 程序输出信息
		log::record('DEBUG', 'DEBUG'); // 调试: 调试信息
		log::record('SQL', 'SQL'); // 其他level  被默认识别为debug 注意只在调试模式开启时有效
		// 把缓存日志一次性写入
		log::save();

		log::write(date('Y-m-d H:i:s', RUN_TIME) . ' post-merge');
	}

	function cache() {
		$key = 'aa';

		$tmp = S($key);
		if ($tmp == null) {
			$value = array(
				'b' => 1,
			);
			S($key, $value, 10);
			echo ' 第一次保存 写入';
		} else {
			echo ' 读取';
		}

		var_dump($tmp);
	}

	function share() {
		$this->load->model("wxapi");
		$url         = getUrl();
		$signPackage = $this->wxapi->wechat->getJsSign($url);
		$this->smarty->assign('signPackage', $signPackage);
		$comment         = array();
		$comment['text'] = '【' . $this->site_config['site_name'] . '】' . $this->site_config['ivtad'];

		// 注意这里的分享页面很关键,决定了通过微信分享注册为分销下家以后 看到的第一个页面
		// 注意后面的 inviter_id 参数需要跟wxpai内部的分享参数名称一直
		$comment['url'] = getUrl() . '?inviter_id=' . MID;
		$this->smarty->assign('comment', $comment);
		$this->smarty->display('test/share.html');
	}
	function order() {

		$this->load->model("template_msg");
                $res = $this->template_msg->inQueue(0,'573,574,575,576,577,578,579',array('type' =>1, 'title' =>'测试标题', 'content' =>"测试内容", 'msg' => 1, 'sms' => 0, 'wechat' => 0, 'mail'=>0));
                var_dump($res);

	}
	function mobile() {
		$url = "http://app.yyaiwopai.com/?code=003f4jI007ULWA13ERJ00HNnI00f4jIF&state=22138&iv=4053";
		if (strpos($url, 'code=') && strpos($url, 'state=')) {
//首次授权登录会带code参数，应该
			$url_query = parse_url($url, PHP_URL_QUERY);
			parse_str($url_query, $arr_query);
			unset($arr_query['code']);
			unset($arr_query['state']);
			$url = $_SERVER['SERVER_NAME'] . "?" . http_build_query($arr_query);
		}echo $url;

	}

	function update() {
		$a = exec("ls /dev 2>/dev/null", $out, $status);

		#print_r($a);

		print_r($out);

		print_r($status);

	}

        function test(){

            $res = rand_user(2);
            echo "<pre>";print_r($res);exit;

            include_once AppDir . "includes/modules/payment/wxpayapp.php";
            $conf = new WxPayConfig();
            print_r($conf);exit;

	         /*$row = $this->db->get("select * from ###_wx_template where nid='order_nopay'");
	         $content = $row['content'];
             preg_match_all("/{{(.*)}}/",$content,$res);
             $res = array_combine($res[1],$res[0]);
	         echo $content;
	         echo "<pre>";print_r($res);
	         exit;*/

             $keyword = "aAssdf";
             if($keyword){
                if(preg_replace("/[a-zA-Z]/","",$keyword)==''){
                    echo 1;exit;
                }else{
                    echo 2;exit;
                }
             }
             $this->load->model("linkage");
             $res = $this->linkage->strToZone($str);

             echo "<pre>";print_r($res);exit;

            //自动确认收货
            //$this->load->library("wechattransfers");
            //print_r($this->wechattransfers->transfers());
            /*$res = $this->db->get("select * from ###_goods_order_common where id=561");
            $this->load->model('order');
            echo $this->order->common_step($res);*/

            /*$this->load->model('order');
            $this->order->chageOrderState(1151, array('status_pay' => 10,'pay_time'=>RUN_TIME), '订单支付成功', array('amount' => 80));*/

            //余额红包积分等处理
            /*$this->load->model('order');
            $order = $this->db->get("SELECT * FROM ###_goods_order WHERE id=1151");
            $this->order->moneyOrder($order);*/
            /*$bw['name'] = "测试标题";
            $bw['desc'] = "测试推荐";
            $bw['content'] = "测试内容";*/

            /*//echo "<pre>";print_r(getBadWord());exit;
            tongcg.b0.upaiyun.com/upload/1/images/gallery/q/7/944_thumb.jpg
            //$imgurl = 'tongcg.b0.upaiyun.com/upload/images/20/gallery/q/5/942_thumb.jpg';
            $imgurl = 'gallery/q/5/942_thumb.jpg';
            $savedir = "/upload/images/";

            require_once AppDir . 'library/upyun.class.php';
            //需存储在img1空间上的字段 new UpYun('fxiao', 'user', 'pwd');
            $upyun = new UpYun('tongcg', 'zsl', 'qq7525365');

            $fh    = fopen($this->load->config('picture', 'image_dir') . $imgurl, 'rb');
            $rsp = $upyun->writeFile($savedir . $imgurl, $fh, True); // 上传图片，自动创建目录
            var_dump($rsp);
            fclose($fh);*/

            /*$this->load->model("order");
            $this->order->common_succ_step(705);*/

        }
        //退款
        function refund($id=0){
            $this->load->library("wechatrefund");
            $res = $this->db->get("select * from ###_refund_log where id={$id}");
            $res['out_refund_no'] = date("YmdHis") . $res['id'];
            $result = $this->wechatrefund->refund($res);
            //print_r($this->wechatrefund->refundQuery());
            print_r($result);
        }
        //退款APP
        function refundapp($id=0){
            $this->load->library("wechatapprefund");
            $res = $this->db->get("select * from ###_refund_log where id={$id}");
            $res['out_refund_no'] = date("YmdHis") . $res['id'];
            $result = $this->wechatapprefund->refund($res);
            //print_r($this->wechatrefund->refundQuery());
            print_r($result);
        }

        function topin(){
            $list = $this->db->select("select * from ###_linkage ");
            foreach($list as $k=>$v){
                $pin = $this->pinyin($v['name'],true);
                $up=ucwords($pin{0});
                $this->db->update("linkage",array("pin"=>$up),array("id"=>$v['id']));
                //echo $up."<br>";
            }

        }

        //检查微信配置是否正确
        function wxtest(){
            $this->load->model("wxapi");
            $signPackage = $this->wxapi->wechat->checkAuth();
            $errCode = $this->wxapi->wechat->errCode;
            $errMsg = $this->wxapi->wechat->errMsg;
            var_dump($errCode);
            var_dump($errMsg);
        }
        function vt(){
            echo 1;exit;
        }




       
}