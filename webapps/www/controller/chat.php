<?php
use GatewayClient\Gateway;

class chat extends Lowxp {

	private $GatewayInited = 0;

	public function __construct() {
		parent::__construct();

		defined('BID') || define('BID', empty($_REQUEST['bid']) ? 0 : intval($_REQUEST['bid']));

		$this->userListCacheName = 'CHAT_USER_LIST_' . MERID . '_' . BID;

	}

	protected function initSmarty() {
		$this->load->smarty(array(
			'tplDir'     => AppDir . 'views/' . LANG_NAME . '/chat',
			'compileDir' => RUNTIME_PATH . 'views_c/' . LANG_NAME . '/chat',
			'cacheDir'   => RUNTIME_PATH . 'cache/' . LANG_NAME . '/chat',
		));
	}

	protected function initGateway() {
		if (!$this->GatewayInited) {
			require AppDir . 'library/GatewayClient/Gateway.php';
			Gateway::$registerAddress = '127.0.0.1:1236';
			$this->GatewayInited      = 1;
		}
	}

	/**
	 * 获取配置
	 * @return array
	 */
	protected function getConfig($bid=0) {

		$config = array(
			'bid'            => BID,
			'guest_avatar'   => photo(0),
			'service_avatar' => '/static/chat/img/logo.png',
			'service_name'   => '客服',
			'service_time'   => '',
			'service_tel'    => '',
			'welcome'        => '欢迎您, 有什么可以帮助您的吗?',
			'amap_map_key'   => C('amap_map_key'), // 默认高德地图api key 不配置无法使用地图
			'site_name'      => C('site_name'),
			'site_logo'      => C('share_logo.png', 'images') ?: '/static/chat/img/logo.png',
			'sound_open'     => true,
		);
        if($bid>0){
            $customConfig = $this->db->get('SELECT * FROM `###_chat_config` WHERE bid= ' . $bid . ' LIMIT 1');
        }else{
            $customConfig = $this->db->get('SELECT * FROM `###_chat_config` WHERE bid= ' . BID . ' LIMIT 1');
        }

		if (is_array($customConfig)) {
			$config = array_merge($config, $customConfig);
		}
		return $config;
	}

	/**
	 * 聊天首页
	 * @return html
	 */
	public function index() {
		$this->initSmarty();

		if (BID) {
			$this->load->model('business');
			$tmp   = $this->business->get(BID, 'name');
			$title = empty($tmp['name']) ? '' : $tmp['name'];
		} else {
			$title = C('site_name');
		}

		$this->smarty->assign('title', $title);
		$this->smarty->display('index.html');
	}

	public function test() {
		$this->initSmarty();
		$this->smarty->display('test.html');
	}

	// 客服聊天界面
	public function service() {

	    if($_GET['bid']>0){

            $uid = intval($_GET['bid']);
            $this->setServiceLogin(array('uid' => $uid));

        }elseif (!empty($_GET['token'])) {
			unset($_SESSION['service']);
			$cacheName = 'CHAT_SERVICE_TOKEN_' . MERID . '_' . BID; // 保存后台生成的 移动端接待 token 省去密码输入过程;
			$token     = S($cacheName);
			S($cacheName, null); // token 使用一次后过期
			if ($token == $_GET['token']) {
				$this->setServiceLogin(array('uid' => 0));
				$this->redirect('/chat/service' . (BID ? '?bid=' . BID : ''));
			}
		} else if (empty($_SESSION['service'])) {
			// 检查客服是否已经登录

			if (!BID && !empty($_SESSION['uid'])) {
				// 平台商户已经登录
				$uid = $_SESSION['uid'];
				$this->setServiceLogin(array('uid' => $uid));
			} else if (BID && !empty($_SESSION['b_id'])) {
				// 入驻商家已经登录
				$uid = BID;
				$this->setServiceLogin(array('uid' => $uid));
			}

		}

		$this->initSmarty();

		$this->smarty->display('service.html');
	}

	// ws 连接时发送系统定制欢迎语 和 聊天记录
	public function bind() {
		$config = $this->getConfig();
		$time   = date('Y-m-d H:i:s');

		$session_id = session_id();

		$res = array(
			'config'     => $config,
			'merid'      => MERID,
			'session_id' => $session_id, // 同步php 会话id  页面刷新也不会变化 可用来保存游客临时聊天记录
			'time'       => $time,
		);

		$client_id = isset($_REQUEST['client_id']) ? $_REQUEST['client_id'] : '';
		if ($client_id) {

			// 假设用户已经登录，用户uid和群组id在session中
			if (!empty($_SESSION['mid'])) {
				// 已登录 后聊天记录需要合并
				// 配置 用户聊天分组 不同BID 不同分组
				$group_id = MERID . '_' . BID . '_' . $_SESSION['mid'];

				if (!empty($_SESSION['user']['group_id']) && $_SESSION['user']['group_id'] != $group_id) {
					// 登录后 如果已经有分组 则合并聊天记录 写入会员uid 信息
					$this->db->update('###_chat_record', array('uid' => $_SESSION['mid'], 'group_id' => $group_id), array('group_id' => $_SESSION['user']['group_id']));
				}

				// 获取用户头像
                $_SESSION['avatar'] = photo($_SESSION['mid']) ?: $config['guest_avatar'];

				$user = array(
					'uid'    => $_SESSION['mid'],
					'name'   => $_SESSION['username'],
					'avatar' => $_SESSION['avatar'],
				);

			} else {
				// 游客状态
				$group_id = MERID . '_' . BID . '_' . $session_id;
				// 退出登录时需要清空 $_SESSION['user']    model/member->setLogout 方法内  否则会导致聊天记录合并异常

				// 未登录
				$user = array(
					'uid'    => 0,
					'name'   => '游客' . substr($session_id, -6),
					'avatar' => $config['guest_avatar'],
				);

			}

			$_SESSION['user']['group_id'] = $group_id;

			// 聊天窗口顶部 产品提示  需要传到到客户列表
			$good_id = !empty($_POST['good_id']) ? intval($_POST['good_id']) : '';
			if ($good_id) {
				$this->load->model('goods');
				$good = $this->goods->get($good_id);

				if ($good) {
					$user['good'] = array(
						'id'      => $good_id,
						'href'    => '/goods/show/' . $good_id,
						'name'    => $good['name'],
						'img_src' => $good['img_src'],
					);
				}
			}

			$this->initGateway();

			// 绑定前端ws 到后端 用户id 注意此时用户id 等于 分组id 每个入驻商户分组不同 用户id 不同
			Gateway::bindUid($client_id, $group_id);

			// 访客进入自己的房间等待 客服加入
			Gateway::joinGroup($client_id, $group_id);

			// 加入客户分组 可以给所有本商户客户推送信息
			Gateway::joinGroup($client_id, 'customer_' . MERID);

			// 在线客户列表
			$user_list = S($this->userListCacheName);

			$user['group_id'] = $group_id;
			$user['time']     = $time;
			$user['online']   = 1;

			$user_list[$group_id] = empty($user_list[$group_id]) ? $user : array_merge($user_list[$group_id], $user);

			S($this->userListCacheName, $user_list);

			$new_message = array(
				'type'      => 'customerLogin',
				'user'      => $user,
				'user_list' => $user_list,
				'time'      => $time,
				'bid'       => BID,
			);

			// 向BID入驻商户推送客户信息
			Gateway::sendToGroup('service_' . MERID . '_' . BID, json_encode($new_message));

			// 获取本用户在当前入驻商户的 聊天记录
			$size            = empty($_POST['size']) ? 20 : $_POST['size'];
			$sql             = 'SELECT * FROM `###_chat_record` WHERE bid = ' . BID . ' AND group_id="' . $group_id . '" ORDER BY id DESC LIMIT ' . $size;
			$res['records']  = array_reverse($this->db->select($sql));
			$res['group_id'] = $group_id;
			$res['user']     = $user;

		}
		$this->ajaxReturn($res);
	}

	/** 订单列表 */
	public function order($page = 1) {

		if (!empty($_GET['mid']) && !empty($_SESSION['service']['wsid'])) {
			// 后台客服
			$mid = $_GET['mid'];
		} elseif (!empty($_SESSION['mid'])) {
			// 前台不会传入 $_GET['mid']
			$mid = $_SESSION['mid'];
		} else {
			$mid = 0;
		}

		if (!$mid) {
			$this->error('对不起, 暂无数据');
		}

		$this->load->model('order');
		$data  = array();
		$where = '';

		//分页
		$this->load->model('page');
		$_GET['page'] = $page;
		$data['size'] = empty($_GET['size']) ? 5 : $_GET['size'];
		$this->page->set_vars(array('per' => $data['size']));

		//订单状态
		$status         = isset($_REQUEST['status']) ? intval($_REQUEST['status']) : 0;
		$data['status'] = $status;

		$condition = array();

		if ($status) {
			$condition[] = $this->order->order_status($status, '', 1);
		}
		if (!empty($_REQUEST['is_rate'])) {
			$condition[] = "is_rate=" . intval($_REQUEST['is_rate']);
		}

		$condition[] = 'sid =' . BID; // bid 隔离 非拼团系统需要移除
		$condition[] = 'mid =' . $mid;

		$where = join(' AND ', $condition);
		$where = $where ? ' WHERE ' . $where : '';

		$sql                 = "SELECT * FROM `###_goods_order` " . $where . " ORDER BY id DESC";
		$list                = $this->page->hashQuery($sql)->result_array();
		$data['totalCounts'] = $this->page->pages['total'];
		$data['totalPages']  = $this->page->pages['count'];

		$list        = $this->order->unionOrderGoods($list);
		$refund_days = C("refund_days");
		foreach ($list as $k => $v) {
			if (!isset($list[$k]['goods'])) {
				$list[$k]['goods'] = array();
			}

		}

		$data['list'] = $list;

		$this->success('成功', '', $data);
	}

	/**
	 * 聊天记录
	 * @return ajax
	 */
	public function records() {

		if (empty($_GET['group_id'])) {
			$this->error('对不起, 参数不正确');
		}

		$group_id = $_GET['group_id'];
		$last     = empty($_GET['last']) ? 0 : intval($_GET['last']);
		$size     = empty($_GET['size']) ? 20 : intval($_GET['size']);

		// $sql = 'SELECT * FROM `###_chat_record` WHERE group_id=? AND (0=? OR id<?) ORDER BY id DESC LIMIT ?';

		// 需要一个起点 只查询会话开始时的 id 之前的id

		// $records = array_reverse($this->db->select($sql, array($group_id, $last, $last, $size)));

		$sql = 'SELECT * FROM `###_chat_record` WHERE group_id="' . $group_id . '" AND (0=' . $last . ' OR id<' . $last . ') ORDER BY id DESC LIMIT ' . $size;

		$records = array_reverse($this->db->select($sql));

		$res = array('records' => $records);

		$this->success('成功', '', $res);
	}

	/**
	 * 客服加入聊天分组
	 * @return ajax
	 */
	public function serviceJoin() {

		if (empty($_POST['client_id'])) {
			$this->error('对不起, 无会话id');
		}

		$group_id = isset($_POST['group_id']) ? $_POST['group_id'] : '';
		if (!$group_id) {
			$this->error('对不起, 连接失败');
		}
		$client_id = $_POST['client_id'];

		$this->initGateway();
		Gateway::joinGroup($client_id, $group_id);

		$user_list = S($this->userListCacheName);

		$wsid = $_SESSION['service']['wsid'];

		// 清理当前接待状态
		foreach ($user_list as $k => $v) {
			if (isset($v['service']) && $wsid == $v['service']) {
				unset($user_list[$k]['service']);
			}
		}

		// 添加接待状态
		$user_list[$group_id]['service'] = $wsid;
		S($this->userListCacheName, $user_list);

		$new_message = array(
			'type'      => 'serviceJoin',
			'group_id'  => $group_id,
			'user_list' => $user_list,
			'time'      => date('Y-m-d H:i:s'),
		);

		Gateway::sendToGroup('service_' . MERID . '_' . BID, json_encode($new_message));

		$this->success('连接成功');
	}

	/**
	 * 绑定客服
	 * @return json  客服信息
	 */
	public function serviceBind() {
		$data = array(
			'merid'  => MERID,
			'config' => $this->getConfig(),
		);

		$client_id = isset($_REQUEST['client_id']) ? $_REQUEST['client_id'] : '';
		if (!$client_id) {
			$this->error('连接中, 请稍候重试', '', array('data' => $data));
		}

		if (empty($_SESSION['service'])) {
			// 未登录 或者转换客服权限
			// 需要处理平台客服和商户客服转换成普通客服的情况
			$this->error('请先登录', '', array('data' => $data));
		}

		$this->initGateway();
		Gateway::bindUid($client_id, $_SESSION['service']['wsid']);
		// 客服加入客服分组 给所有本商户客服推送信息
		Gateway::joinGroup($client_id, 'service_' . MERID . '_' . BID);

		$user_list = $this->refreshUserList();

		$data['user']      = $_SESSION['service'];
		$data['user_list'] = $user_list;
		$data['customer']  = '';

		$wsid = $_SESSION['service']['wsid'];

		// 寻找断线前接待的客户
		foreach ($user_list as $k => $v) {
			if (isset($v['service']) && $wsid == $v['service']) {
				$data['customer'] = $v;
			}
		}

		$this->success('连接成功', '', array('data' => $data));
	}

	/**
	 * 获取在线客服列表 标记不在线客户
	 * @return array 剩余在线客户列表
	 */
	protected function refreshUserList($group_id = '') {

		$user_list = S($this->userListCacheName);

		if (!$user_list) {
			return array();
		}

		$time = date('Y-m-d H:i:s');

		$logout = array();
		$this->initGateway();

		if (!$group_id) {
			foreach ($user_list as $k => $v) {
				if (!Gateway::isUidOnline($k)) {
					// 如果当前不在线

					if (!empty($v['online'])) {
						// 如果标记是在线 则修改成离线状态
						$logout[]                = $user_list[$k]['name'];
						$user_list[$k]['time']   = $time; // 记录退出时间
						$user_list[$k]['online'] = 0;
					} elseif ((RUN_TIME - strtotime($v['time'])) > 6 * 3600) {
						// 如果标记已经是离线 且离线时间超过 6小时 则删除
						unset($user_list[$k]);
						continue;
					}

				}
			}
		} elseif (!empty($user_list[$group_id])) {
			$logout[] = $user_list[$group_id]['name'];

			$user_list[$group_id]['time']   = $time; // 记录退出时间
			$user_list[$group_id]['online'] = 0;
		}

		if ($logout) {
			S($this->userListCacheName, $user_list);
			$new_message = array(
				'type'      => 'customerLogout',
				'user'      => array('name' => join(',', $logout)),
				'user_list' => $user_list,
				'time'      => $time,
			);
			Gateway::sendToGroup('service_' . MERID, json_encode($new_message));
		}
		return $user_list;
	}

	// 登录客服
	public function serviceLogin() {
		if (empty($_POST['client_id'])) {
			$this->error('对不起, 无会话id');
		}

		if (empty($_POST['username'])) {
			$this->error('对不起, 请输入用户名');
		}

		if (empty($_POST['password'])) {
			$this->error('对不起, 请输入密码');
		}

		if (empty($_POST['scode'])) {
			$this->error('对不起, 请输入验证码');
		}

		if (strtolower(trim($_POST['scode'])) != strtolower($_SESSION['scode'])) {
			$this->error('对不起, 验证码错误!');
		}

		$username = $_POST['username'];
		$password = $_POST['password'];

		if (BID) {
			$user = $this->db->get("SELECT * FROM `###_business` WHERE mobile = '" . $username . "' LIMIT 1");
			if (empty($user['status'])) {
				$this->error('对不起, 无使用权限');
			}

			$user['uid'] = 0;

		} else {
			$user = $this->db->get("SELECT uid,username,password,salt,group_id,visitor FROM `###_m_user` WHERE username='" . $username . "' ");
			if (empty($user['uid'])) {
				$this->error('对不起, 账号或密码错误!');
			}
		}

		$this->load->model('user');
		if ($this->user->get_salt_hash($password, $user['salt']) != $user['password']) {
			$this->error('对不起, 账号或密码错误!');
		}

		$this->setServiceLogin($user);

		$this->serviceBind($_POST['client_id']);

	}

	/**
	 * 设置登录信息
	 * @param array $user
	 * @param array $config
	 */
	protected function setServiceLogin($user, $config = array()) {

		$config              =  $this->getConfig($user['uid']);
		$_SESSION['service'] = array(
			'wsid'   => 's_' . MERID . '_' . BID . '_' . $user['uid'],
			'uid'    => $user['uid'],
			'name'   => empty($config['service_name']) ? '客服' : $config['service_name'],
			'avatar' => empty($config['service_avatar']) ? C('logo.png', 'images') : $config['service_avatar'],
		);
	}

	/**
	 * 退出客服
	 * @return void
	 */
	public function serviceLogout() {
		unset($_SESSION['service']);
		$this->success('已经退出');
	}

	protected function parseMsg($str) {
		if (!$str) {
			return '';
		}

		// todo 敏感词过滤

		$str = preg_replace_callback('/\[订单(.*)\]/', function ($matches) {
			if ($matches[1]) {

				$order  = $this->db->get('SELECT * FROM `###_goods_order` WHERE id=' . $matches[1]);
				$orders = array($order);
				$this->load->model('order');
				$orders = $this->order->unionOrderGoods($orders);
				$order  = $orders[0];

				$this->initSmarty();
				$this->smarty->assign('order', $order);
				return $this->smarty->fetch('msg_order.html');
			}
		}, $str);

		$str = preg_replace_callback('/\[商品(.*)\]/', function ($matches) {
			if ($matches[1]) {
				$this->load->model('goods');
				$good = $this->goods->get($matches[1]);
				$this->initSmarty();
				$this->smarty->assign('good', $good);
				return $this->smarty->fetch('msg_good.html');
			}
		}, $str);
		// 下面的部分在客户端前台转换不做转换 不会执行

		$str = preg_replace_callback('/\[表情(\d*)\]/', function ($matches) {
			return '<i class="emoticon emoticon-' . $matches[1] . '"></i>';
		}, $str);

		$str = preg_replace_callback('/\[图片(.*)\]/', function ($matches) {
			// return '<a href="' . $matches[1] . '"><img src="' . $matches[1] . '" /></a>';
			return '<img src="' . $matches[1] . '" />';
		}, $str);

		// 静态图片 需要申请额外的key
		// $str = preg_replace_callback('/\[地图(.*)\]/', function ($matches) {
		// 	$mapApiKey = '';
		// 	$apiUrl    = 'http://restapi.amap.com/v3/staticmap';
		// 	return '<img src="' . $apiUrl . '?position=' . $matches[1] . '&zoom=16&size=400*400&markers=mid,0x3993f8,:' . $matches[1] . '&key=' . $mapApiKey . '">';
		// }, $str);

		// $str = preg_replace_callback('/\[地图(.*)\]/', function ($matches) {
		// 	$url = 'https://m.amap.com/share/index/lnglat=' . $matches[1] . '&src=mypage&callnative=0';
		// 	return '<iframe src="' . $url . '"></iframe>';
		// }, $str);

		$str = preg_replace_callback('/\[地图(.*)\]/', function ($matches) {
			return '<button class="btn btn-primary modal-record-position" data-toggle="modal" data-target="#modalRecordPosition" data-position="' . $matches[1] . '"><i class="glyphicon glyphicon-map-marker"> </i> 点击查看位置分享</button>';
		}, $str);

		$str = preg_replace_callback('/(\r\n|\n|\r)/', function ($matches) {
			return '<br>';
		}, $str);

		return $str;
	}

	public function say() {

		if (empty($_POST['type']) || $_POST['type'] != 'say') {
			$this->error('对不起, 类型错误');
		}

		if (empty($_POST['user'])) {
			$this->error('用户信息不能为空');
		}

		if (empty($_POST['group_id'])) {
			$this->error('接受对象不能为空');
		}

		if (empty($_POST['msg'])) {
			$this->error('消息内容不能为空');
		}

        $config = $this->getConfig();

		$group_id = $_POST['group_id'];

		$msg = isset($_POST['msg']) ? $_POST['msg'] : '';

		$msg = $this->parseMsg($msg);

		$time = date('Y-m-d H:i:s');

		$service = empty($_POST['user']['wsid']) ? 0 : 1;

		$user = $_POST['user'];

        if (empty($user['avatar'])) {
            $user['avatar'] = $service ? $config['service_avatar'] : $config['guest_avatar'];
        }

		$record = array(
			'type'      => 'say',
			'bid'       => BID,
			'service'   => $service,
			'group_id'  => $group_id,
			'uid'       => $user['uid'],
			'name'      => $user['name'],
			'avatar'    => $user['avatar'],
			'msg'       => $msg,
			'time'      => $time,
			'time_show' => 0,
		);

		$message = array(
			'type'      => 'say',
			'service'   => $service,
			'user'      => $user,
			'msg'       => $msg,
			'time'      => $time,
			'time_show' => 0,
			'notice'    => '',
		);

		$this->initGateway();

		if ($service && !Gateway::isUidOnline($group_id)) {
			// 客服发来的消息  检查客户是否在线用户不在线
			$message['notice'] = '请注意: 客户已离线';

			// 推送客户在线状态
			$this->refreshUserList($group_id);
		}

		// 时间显示逻辑 消息间相隔600秒显示一次
		$cacheName = 'TMP_CHAT_LAST_MSG_' . MERID . '_' . BID . '_' . $group_id;
		$lastTime  = S($cacheName);

		if (RUN_TIME - $lastTime > 60) {
			$message['time_show'] = 1;
			$record['time_show']  = 1;
		}

		S($cacheName, RUN_TIME);

		Gateway::sendToGroup($group_id, json_encode($message));

		// 聊天记录
		$this->db->insert('###_chat_record', $record);

		if (!$service) {
			// 客户发言时的逻辑
			// 自动回复
			$autoreplay = $this->getAutoReplayList();
			if ($autoreplay) {

				$serviceUser = $this->getServiceUser($config);

				foreach ($autoreplay as $v) {
					if ($this->handleAutoreplay($v, $msg, $group_id, $serviceUser, $time)) {
						break; // 发送一条就停止 去除本逻辑就可以每条都回复
					};
				}
			}
		}

		$this->success('已发送');

	}

	/**
	 * 处理 自动回复规则
	 * @param  array 	$rule         自动回复单行规则
	 * @param  string 	$msg          信息内容
	 * @param  string 	$group_id     聊天分组id
	 * @param  array 	$serviceUser  客服信息数组 array(wsid,uid,name,avatar)
	 * @param  string 	$time         时间字符串 类似 2017-01-01 13:00:09
	 * @return boolean  	          是否成功
	 */
	protected function handleAutoreplay($rule, $msg, $group_id, $serviceUser, $time) {

		if ((empty($rule['type']) && false === strpos($msg, $rule['keyword'])) || (!empty($rule['type']) && $msg != $rule['keyword'])) {
			return false;
		}
		// 精确匹配

		$record = array(
			'type'      => 'say',
			'bid'       => BID,
			'service'   => 1,
			'group_id'  => $group_id,
			'uid'       => $serviceUser['uid'],
			'name'      => $serviceUser['name'],
			'avatar'    => $serviceUser['avatar'],
			'msg'       => $rule['content'],
			'time'      => $time,
			'time_show' => 0,
		);

		// 聊天记录
		$this->db->insert('###_chat_record', $record);

		$message = array(
			'type'      => 'say',
			'service'   => 1,
			'user'      => $serviceUser,
			'msg'       => $rule['content'],
			'time'      => $time,
			'time_show' => 0,
			'notice'    => '',
		);

		Gateway::sendToGroup($group_id, json_encode($message));

		return true;
	}

	protected function getServiceUser($config) {
		$serviceUser = array(
			'wsid'   => 's_' . MERID . '_' . BID . '_' . 0,
			'uid'    => 0, // 这里这里无论 平台客服 还是 商户客服 uid 都是0
			'name'   => $config['service_name'],
			'avatar' => $config['service_avatar'],
		);
		return $serviceUser;
	}

	protected function getAutoReplayList() {
		$cacheName  = 'CHAT_AUTOREPLAY_LIST_' . MERID . '_' . BID;
		$autoreplay = S($cacheName);
		if (!$autoreplay) {
			$autoreplay = $this->db->select('SELECT * FROM `###_chat_autoreplay` WHERE status = 1 AND bid=' . BID . ' ORDER BY id desc');
			S($cacheName, $autoreplay, 3600);
		}
		return $autoreplay;
	}

	public function upload() {
		if (isset($_REQUEST['qquuid'])) {

			$filename = md5(uniqid(mt_rand(), true)) . '.jpg';

			$res = $this->fineUploadHandle('upload/chat', $filename);

			$this->ajaxReturn($res);
		}
	}

	protected function fineUploadHandle($uploadDirectory = NULL, $filename = NULL, $allowedMimes = array()) {

		require_once AppDir . 'library/fineuploader/php-traditional-server/handler.php';
		$uploader = new UploadHandler();

		$uploader->allowedMimes = $allowedMimes ?: array('image/jpeg', 'image/png', 'image/gif');
		$uploader->chunksFolder = 'upload/tmp';

		$filename = $filename ?: (empty($_REQUEST['qqfilename']) ? NULL : $_REQUEST['qqfilename']);
		if (isset($_GET['done'])) {
			$result = $uploader->combineChunks($uploadDirectory, $filename);
		} else {
			$result               = $uploader->handleUpload($uploadDirectory, $filename);
			$result['uploadName'] = $uploader->getUploadName();
		}

		return $result;

	}

	public function onClose() {

		if (empty($_GET['group_id'])) {
			return;
		}

		$group_id = $_GET['group_id'];

		$this->initGateway();
		if (!Gateway::isUidOnline($group_id)) {

			$user_list = S($this->userListCacheName);

			if (isset($user_list[$group_id])) {

				$time = date('Y-m-d H:i:s');

				$user = $user_list[$group_id];

				$user_list[$group_id]['time']   = $time;
				$user_list[$group_id]['online'] = 0;

				S($this->userListCacheName, $user_list);

				$new_message = array(
					'type'      => 'customerLogout',
					'user'      => $user,
					'user_list' => $user_list,
					'time'      => $time,
				);

				Gateway::sendToGroup('service_' . MERID . '_' . BID, json_encode($new_message));

			}
		}

	}

}
