<?php

/**
 * fan 2016-03-29 开始重构
 * 微信api
 */
class wxapi_model extends Lowxp_Model {
	// api对象存储变量
	public $wechat;
	// wxoauth 会用到的2个参数
	public $open_id;
	public $wxuser;

	function __construct() {
		if(C('wx_appsecret')=='')die('请在后台配置微信参数,并刷新缓存');
		$option = array(
			'token'          => C('wx_token'),
			'appid'          => C('wx_appid'),
			'appsecret'      => C('wx_appsecret'),
			'encodingaeskey' => C('wx_encodingaeskey'),
			'cachedir'       => AppDir . 'cache',
			'logfile'        => RootDir . 'log/' . date('Y-m-d', time()) . '.log',
			'debug'          => false,
		);
		// $this->load->library('wechat', $option);
		require_once AppDir . '/library/wechat.php';

		$this->wechat = new Wechat_Library($option);

	}

	/**

	 * 后台部分
	 */
	/**
	 * 发布菜单.
	 * @return boolean
	 */
	public function menuCreate() {
		$row = $this->db->get("SELECT json FROM ###_wx_menu_data ");

		$menu_arr = json_decode($row['json'], true);

		$data = array();

		$events = array();
		foreach ($menu_arr['button'] as $key => $row) {
			if (empty($row['sub_button'])) {
				$data[$key]['type'] = $row['type'] == 'view' ? 'view' : 'click';
			}
			$data[$key]['name'] = $row['name'];

			if (!empty($row['sub_button']) && is_array($row['sub_button'])) {
				//有子菜单
				$sub_button = array();
				foreach ($row['sub_button'] as $k => $v) {
					$sub_button[$k]['type'] = $v['type'] == 'view' ? 'view' : 'click';

					$sub_button[$k]['name'] = $v['name'];
					if ($v['type'] == 'view') {
						$sub_button[$k]['url'] = $v['data'];
					} else {
						$sub_button[$k]['key'] = $v['key'];

						$events[$v['key']] = array(
							'type' => $v['type'],
							'key'  => $v['key'],
							'data' => $v['data'],
						);
					}
				}
				$data[$key]['sub_button'] = $sub_button;
			} else {
				//没子菜单
				if ($row['type'] == 'view') {
					$data[$key]['url'] = $row['data'];
				} else {
					$data[$key]['key'] = $row['key'];

					$events[$row['key']] = array(
						'type' => $row['type'],
						'key'  => $row['key'],
						'data' => $row['data'],
					);
				}
			}
		}

		//提交相关事件到发布设置中.
		$this->db->update('wx_menu_data', array('release' => json_encode($events)));

		$export['button'] = $data;

		return $this->wechat->createMenu($export);
	}

	/**

	 * 扫码注册登录部分
	 */
	/**
	 * $type reg:授权并注册  oauth：只授权
	 *
	 * 微信登录 如果首次登录则自动注册会员
	 * 注意 会占用 $_SESSION  请勿覆盖 优化阶段可以考虑使用缓存代替
	 * 注意即时没关注也可以使用本方法登录
	 * $_SESSION['wxoauth']['token_time']  token的过期时间
	 * $_SESSION['wxoauth']['open_id']     用户openid
	 * $_SESSION['wxoauth']['user_token']  用户的use_token
	 * $_SESSION['wxoauth']['wxuser']      获取到的用户信息 这个其实非必须 注册或者登录完毕以后 可以注销
	 * $_SESSION['wxoauth']['wx_redirect'] 保存的回调地址 用来当页面跳转的时候 依然可以进行保险性质的第二次验证
	 * @return string 返回openid
	 */
	public function wxoauth($type = "reg") {
		IS_DEV && Log::write('记录操作之前的 SESSION ' . json_encode($_SESSION));

		$scope = 'snsapi_base';

		$code = isset($_GET['code']) ? $_GET['code'] : '';

		$token_time = isset($_SESSION['wxoauth']['token_time']) ? $_SESSION['wxoauth']['token_time'] : 0;
		if (!$code && isset($_SESSION['wxoauth']['open_id']) && isset($_SESSION['wxoauth']['user_token']) && $token_time > time() - 3600) {
			if (!$this->wxuser) {
				$this->wxuser = $_SESSION['wxoauth']['wxuser'];
			}
			$this->open_id = $_SESSION['wxoauth']['open_id'];
			return $this->open_id;
		} else {
			if ($code) {
				$json = $this->wechat->getOauthAccessToken();
				if (!$json) {
					// 获取accesstoken 失败 直接放弃
					unset($_SESSION['wxoauth']['wx_redirect']);
					die('对不起. 获取用户授权失败，请重新扫码');
				}

				// 必须保留 不然无法跳出循环
				$access_token = $json['access_token'];

				$_SESSION['wxoauth']['user_token'] = $access_token;

				$_SESSION['wxoauth']['open_id'] = $this->open_id = $json["openid"];

				$_SESSION['wxoauth']['token_time'] = time();

				$userinfo = $this->wechat->getUserInfo($this->open_id);
				if ($userinfo && !empty($userinfo['nickname'])) {
					$this->wxuser = array(
						'open_id'  => $this->open_id,
						'nickname' => $userinfo['nickname'],
						'sex'      => intval($userinfo['sex']),
						'location' => $userinfo['city'],
						'avatar'   => $userinfo['headimgurl'],
                        'unionid' =>isset($userinfo['unionid'])?$userinfo['unionid']:0,
                        'subscribe_time' =>isset($userinfo['subscribe_time'])?$userinfo['subscribe_time']:0
					);
				} elseif (strstr($json['scope'], 'snsapi_userinfo') !== false) {
					// 本步骤为第一步获取失败第二次查询 第二道保险
					$userinfo = $this->wechat->getOauthUserinfo($access_token, $this->open_id);
					if ($userinfo && !empty($userinfo['nickname'])) {
						$this->wxuser = array(
							'open_id'  => $this->open_id,
							'nickname' => $userinfo['nickname'],
							'sex'      => intval($userinfo['sex']),
							'location' => $userinfo['city'],
							'avatar'   => $userinfo['headimgurl'],
                            'unionid' =>isset($userinfo['unionid'])?$userinfo['unionid']:0,
                            'subscribe_time' =>isset($userinfo['subscribe_time'])?$userinfo['subscribe_time']:0
						);
					} else {
						// 第二道保险失效 则返回openid 用户debug
						IS_DEV && Log::write('如果获取用户信息失败则返回openid ' . $this->open_id);
						return $this->open_id;
					}
				}

				if ($this->wxuser) {
					// 双保险获取 有一次成功便会转入这里

					if ($type == 'reg') {
						$this->load->model("member");
						$this->member->oauth_user($this->wxuser);
						//$this->regist($this->wxuser);
					} elseif ($type == 'oauth') {
						$_SESSION["inviter_id"] = intval($_GET['inviter_id']);
						$_SESSION['wxoauth']['unionid'] = $this->wxuser['unionid'];
						$_SESSION['wxoauth']['nickname'] = $this->wxuser['nickname'];
						$_SESSION['wxoauth']['avatar']   = $this->wxuser['headimgurl'];
					}

					// 获取用户微信关注状态 用来提示会员关注
					if (isset($userinfo['subscribe'])) {
						$_SESSION['wxoauth']['subscribe'] = $userinfo['subscribe'];
					}

					// user 写入session  跟最上面形成闭环
					// $_SESSION['wxuser']  = $this->wxuser;
					// 删除保存的全局回调url 这步很重要 不然会形成死循环
					unset($_SESSION['wxoauth']['wx_redirect']);

					// 处理完以后返回用户的openid
					return $this->open_id;
				}

				// 如果有code 并且按照上面的条件全部执行失败
				// 就将scope 设置成snsapi_userinfo
				// 继续往下面执行 免得重复  使用snsapi_base 获取第二次code
				$scope = 'snsapi_userinfo';
			}
			if ($scope == 'snsapi_base') {
				// 第一次获取
				$url = REQUEST_SCHEME . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
				// 回调地址写入session备用
				$_SESSION['wxoauth']['wx_redirect'] = $url;
			} else {
				// 第二道保险的一部分
				// snsapi_userinfo 作用域下 用第一次保存的 备用 回调地址 执行第二次尝试
				$url = $_SESSION['wxoauth']['wx_redirect'];
			}
			if (!$url) {
				// 如果备用url 则授权失败
				unset($_SESSION['wxoauth']['wx_redirect']);

				IS_DEV && Log::write('对不起,获取用户授权失败.请重新扫码 ' . json_encode($_SESSION));
				die('对不起,获取用户授权失败.请重新扫码');
			}
			$oauth_url = $this->wechat->getOauthRedirect($url, "wxbase", $scope);

			IS_DEV && Log::write('获取到的回调地址' . $oauth_url);

			header('Location: ' . $oauth_url);
			// 跳转则停止执行 提高效率
			die;
		}
	}

	/**
	 * 注册新用户 或更新unionid
	 * @param  array $user 从微信获取的用户信息数组
	 */
	protected function regist($user) {
		$sql = '';

		$member = array();

		if (!empty($user['open_id'])) {
			$sql = "SELECT * FROM ###_oauth WHERE openid='" . $user['open_id'] . "'";
		} elseif (!empty($user['unionid'])) {
			$sql = "SELECT * FROM ###_oauth WHERE openid='" . $user['unionid'] . "'";
		}

		// 有sql 标识新注册用户
		if ($sql) {
			$member = $this->db->get($sql);
		}

		if (empty($member['mid'])) {
			// 新用户注册
			$user['nickname'] = trim($user['nickname']);

			$user['open_id'] = isset($user['open_id']) ? $user['open_id'] : '';

			$user['unionid'] = isset($user['unionid']) ? $user['unionid'] : '';

			// oauth 跳转绑定手机号  不过这步很突兀  可能会放到下单和找回密码任务完成
			// $_SESSION['oauth'] = !empty($info) ? $info : $data;
			//推广链接直接返回
			// $segments = Lowxp_Router::getInstance()->segments;
			// if (isset($segments['1']) && isset($segments[2]) && $segments['1'] == 'register') {
			// return $data;
			// }
			//未绑定帐号
			//header('Location:'.url('/member/oauth_chose'));
			//exit;

			$data = array(
				'username'       => $user['nickname'], //  微信扫码用户名即为微信用户名  有重复可能 用户名 后期不作为登录依据
				//'nickname'       => $user['nickname'],
				//'photo'          => $user['avatar'],
				//'openid'         => $user['open_id'],
				//'unionid'        => $user['unionid'],
				'subscribe_time' => isset($user['subscribe_time']) ? $user['subscribe_time'] : 0,
				//'c_time'         => RUN_TIME,
				//'lastlogin'      => RUN_TIME,
				'ivt_id'         => 0,
				'ivt_level'      => 0,
			);

			// 注册送积分
			$this->load->model('score');

			$this->score->actionByRuleId(2, $mid);

			// 模版消息参数 start
			$queue       = array(); // 模版消息批量容器
			$memberCount = $this->db->getStr('select count(*) as count from ###_member', 'count');

			$siteName = C('site_name');

			// 模版变量参数 等待插入其他参数
			$templateParam = array($memberCount, $siteName);

			// 模版消息7 注册成会员 {插入ID号},{插入会员个数},{插入店铺}
			$templateId = 7;
			// 模版消息参数 end

			// 要求参数字段名称 注意要尽量生僻不要跟其他get参数重复
			$ivtFieldName = 'inviter_id';

			if (!empty($_GET[$ivtFieldName]) && floor($_GET[$ivtFieldName]) == $_GET[$ivtFieldName]) {
				$ivtId = intval($_GET[$ivtFieldName]);

				// $ivt = $this->db->get("SELECT p1.mid,p1.ivt_level,p1.partner_id,p1.ivt_id,p1.ivt_id_2,p2.nickname,p1.username FROM `###_member` as p1 left join `###_member_detail` as p2 on p1.mid=p2.mid WHERE p1.`mid` = '" . trim($ivtId) . "'");

				$ivt = $this->db->get('SELECT * FROM ###_member WHERE mid =' . $ivtId);

				#推荐人统计人数
				if (!empty($ivt['mid'])) {

					// 模版8 推荐成为会员 模版变量 {插入ID号},{插入昵称},{插入会员个数},{插入店铺}
					$templateId = 8;

					// 模版消息参数插入昵称
					array_unshift($templateParam, $ivt['username']);

					// 分享送积分规则
					$scoreRule = $this->score->getConfig(3);
					// 模版消息 10 有人被下级推荐成会员 {插入昵称},{分销层级},{插入上级昵称},{插入会员个数},{插入店铺}

					if ($ivt['ivt_id_3']) {
						// 推荐人3级上级

						$queue[] = array(10, $ivt['ivt_id_3'], array($user['nickname'], 1, getUsername($ivt['ivt_id_3']), $memberCount, $siteName));
						if (!empty($scoreRule['extend'][3])) {
							$tmp = array(
								'mid'    => $ivt['ivt_id_3'],
								'type'   => 3, // 注意这里的 ruleId 固定为3表示 分享赚积分
								'amount' => round($scoreRule['extend'][3]),
								'remark' => $user['nickname'] . ' 被邀请注册.作为推荐人3级上级获得积分',
								'c_time' => RUN_TIME,
							);
							$this->score->scoreLog($tmp);
						}

					}

					if ($ivt['ivt_id_2']) {
						// 推荐人2级上级

						$queue[] = array(10, $ivt['ivt_id_2'], array($user['nickname'], 2, getUsername($ivt['ivt_id_2']), $memberCount, $siteName));
						if (!empty($scoreRule['extend'][2])) {
							$tmp = array(
								'mid'    => $ivt['ivt_id_2'],
								'type'   => 3, // 注意这里的 ruleId 固定为3表示 分享赚积分
								'amount' => round($scoreRule['extend'][2]),
								'remark' => $user['nickname'] . ' 被邀请注册.作为推荐人2级上级获得积分',
								'c_time' => RUN_TIME,
							);
							$this->score->scoreLog($tmp);
						}

					}
					if ($ivt['ivt_id']) {
						// 推荐人1级上级

						$queue[] = array(10, $ivt['ivt_id'], array($user['nickname'], 3, getUsername($ivt['ivt_id']), $memberCount, $siteName));
						if (!empty($scoreRule['extend'][1])) {
							$tmp = array(
								'mid'    => $ivt['ivt_id'],
								'type'   => 3, // 注意这里的 ruleId 固定为3表示 分享赚积分
								'amount' => round($scoreRule['extend'][1]),
								'remark' => $user['nickname'] . ' 被邀请注册.作为推荐人1级上级获得积分',
								'c_time' => RUN_TIME,
							);
							$this->score->scoreLog($tmp);
						}
					}

					// 推荐人本人
					// 模版消息 9 有人被您推荐成会员 {插入昵称},{插入会员个数},{插入店铺}
					$queue[] = array(9, $ivt['mid'], array($user['nickname'], $memberCount, $siteName));
					if (!empty($scoreRule['extend'][0])) {
						$tmp = array(
							'mid'    => $ivt['mid'],
							'type'   => 3, // 注意这里的 ruleId 固定为3表示 分享赚积分
							'amount' => round($scoreRule['extend'][0]),
							'remark' => $user['nickname'] . ' 被邀请注册.作为直接级上级获得积分',
							'c_time' => RUN_TIME,
						);
						$this->score->scoreLog($tmp);
					}

					// 分享送积分
					// $this->score->inviteAction($ivt['mid'], $user['nickname'], $templateParam);

					$data['ivt_id'] = $ivt['mid'];

					$data['ivt_level'] = $ivt['ivt_level'] + 1;
					//Feng 添加上三级推荐人id和合伙人id 2016-06-03
					$data['ivt_id_2'] = $ivt['ivt_id'];

					$data['ivt_id_3'] = $ivt['ivt_id_2'];

					$data['partner_id'] = $ivt['partner_id']; #合伙人ID
					$this->db->update('member', "ivt_count=ivt_count+1", array('mid' => $ivt['mid']));
				}

			}

			$mid = $this->db->insert('member', $data);
			if ($mid > 0) {
				$data_oauth['mid'] = $mid;

				$data_oauth['type'] = 0;

				$data_oauth['openid'] = $user['open_id'];

				$data_oauth['create_time'] = RUN_TIME;

				$this->db->insert('oauth', $data_oauth);

				$data_detail['mid'] = $mid;

				$data_detail['nickname'] = $user['nickname'];

				$data_detail['photo'] = $user['avatar'];

				$data_detail['c_time'] = RUN_TIME;

				$data_detail['lastlogin'] = RUN_TIME;

				$this->db->insert('member_detail', $data_detail);
			}

			// 模版消息 start
			// 主动注册会员 或者被推荐成会员

			$receiver = array(
				'wehcat' => $user['openid'],
				'msg'    => $mid,
			);

			array_unshift($templateParam, $mid);

			// 模版消息 注册为会员 推荐为会员
			// template_msg_action start
			$this->load->model('template_msg');

			$queue[] = array(
				$templateId,
				$mid,
				$templateParam,
			);

			// $this->template_msg->inQueue($templateId, $mid, $templateParam);
			$this->template_msg->inQueueMany($queue);
			// template_msg_action end

			// 模版消息 end

		} elseif (!empty($user['unionid'])) {
			$data_oauth['mid'] = $mid;

			$data_oauth['type'] = 1;

			$data_oauth['openid'] = $user['unionid'];

			$data_oauth['create_time'] = RUN_TIME;

			$this->db->insert('oauth', $data_oauth);
		}
	}

	/**
	 * 通过openid 登录的函数
	 * 注意本函数同时兼容 openid 和unionid
	 * @param  string $openid 或者 unionid
	 * @return boolean
	 */
    public function login($openid = '') {
        if($this->wxuser){

            $oauth = $this->db->get("SELECT * FROM ###_oauth WHERE openid='" . $this->wxuser['unionid'] . "' OR openid='" . $this->wxuser['open_id'] . "' limit 1");

        }else{
            if (!$openid) {
                return false;
            }
            $oauth = $this->db->get("SELECT * FROM ###_oauth WHERE openid='" . $openid . "'");
        }
        if ($oauth) {
            $this->load->model('member');

            $member = $this->member->member_info($oauth['mid']);
            if($member['subscribe_time']==0){
                if(isset($_SESSION['wxoauth']['wxuser']) && $this->wxuser['subscribe_time']==0){
                    $this->wxuser = $this->wechat->getUserInfo($this->open_id);
                }
                if($this->wxuser['subscribe_time']>0){
                    $this->db->update('member', 'subscribe_time=' . RUN_TIME, 'mid=' . $member['mid']);
                }
            }
            //禁用的会员不自动登录
            if($member['status']==1){
                $this->member->setLogin($member);
                return true;
            }
        } else {
            Log::write('未找到用户 openid(unionid)为' . $openid, 'ERR');
        }

        return false;
    }

	/**

	 * 自动回复部分
	 */

	/**
	 * 会员关注微信,回复信息,首次关注创建用户
	 * @param $post
	 */
	public function subscribe($post) {
		$mid = $this->checkMemberExists($post['FromUserName']);

		if ($mid) {
			$this->db->update('member', 'subscribe_time=' . RUN_TIME, 'mid=' . $mid);
		}

		//todo:获取公司设置，给用户返回的第一句话

		//关注回复的url为固定url
		#$msgUrl = RootUrl.'wx/'.$_SESSION['flag'].'/'.$post['FromUserName'].'/init';

		$rule = $this->db->get("SELECT * FROM ###_wx_autoreply_rule WHERE rule_type='subscribe'");
		if (!isset($rule['id'])) {
			return false;
		}

		$this->outputByRuleId($rule['id']);

		#$reply = $this->db->get("SELECT * FROM wx_reply WHERE re_type=1");
		#$msg = $reply['content'];
		#$wxXml = $this->textTpl($post['FromUserName'],$post['ToUserName'],$msg);
		#echo $wxXml;die;
	}

	/**
	 * 用户取消关注
	 * @return void
	 */
	public function unsubscribe($post) {
		$mid = $this->checkMemberExists($post['FromUserName']);
		if ($mid) {
			return $this->db->update('member', 'subscribe_time=0', 'mid=' . $mid);
		} else {
			return false;
		}
	}

	/**
	 * 检查用户是否存在,不存在则创建.
	 * @param $openid        传入openid
	 * @return bool|integer  返回用户mid
	 */
	public function checkMemberExists($openid) {
        $userinfo = $this->wechat->getUserInfo($openid);
        if($userinfo['unionid']){
            $sql    = 'SELECT * FROM `###_oauth` WHERE openid = "' . $userinfo['unionid'] . '" or  openid = "' . $openid . '" LIMIT 1';
        }else{
            $sql    = 'SELECT * FROM `###_oauth` WHERE openid = "' . $openid . '" LIMIT 1';
        }
		$member = $this->db->get($sql);

		return isset($member['mid']) ? $member['mid'] : false;

	}

	/**
	 * 根据规则输出对应消息.
	 * @param $rule_id
	 */
	public function outputByRuleId($rule_id) {
		if (!is_numeric($rule_id)) {
			return false;
		}

		$rule = $this->db->get("SELECT * FROM ###_wx_autoreply_rule WHERE id=" . $rule_id);
		if (empty($rule['id'])) {
			return false;
		}

		$replyList = json_decode($rule['reply_list'], true);

		$this->load->model('wxnews');

		$newsList = $this->wxnews->getMsgsByReplyList($replyList);

		if (isset($newsList['msg_type'])) {
			$newsList = array($newsList);
		}
        if (!is_array($newsList)) {
            return false;
        }

        foreach ($newsList as $val) {
			switch ($val['msg_type']) {
				case 'news':
					$this->wechat->news($val['news_list'])->reply();

					break;
				case 'text':
					if (trim($val['content'])) {
						$this->wechat->text($val['content'])->reply();
					}
					break;
			}
		}
	}

	/**
	 * 检查菜单中所绑定的事件.
	 * @param $eventKey
	 * @param $fromUser
	 * @param $toUser
	 */
	public function checkMenuEvent($eventKey, $fromUser, $toUser) {
		$row = $this->db->get("SELECT `release` FROM ###_wx_menu_data ");
		if (!isset($row['release'])) {
			Log::write(json_encode($row));

			$this->wechat->text('请访问微站查看更多资源哦')->reply();
			exit;
		}

		$data = json_decode($row['release'], true);

		if (isset($data[$eventKey])) {
			#(array)menuData::key,type:text|news|wheel|image,data:news|1,wheel|2...
			$menuData = $data[$eventKey];

			switch ($menuData['type']) {
				case 'text':
					// 文本回复
					$this->wechat->text($menuData['data'])->reply();
					exit;

					break;
				case 'news':
					// 图文回复
					$segments = explode('|', $menuData['data']);

					$newsId = $segments[1];

					$wxnews = $this->db->select("SELECT * FROM ###_wx_news WHERE id=" . $newsId);

					$this->load->model('wxnews');

					$wxnews = $this->wxnews->getNewsInfo($wxnews);

					$this->wechat->news($wxnews[0]['items'])->reply();
					exit;

					break;
				case 'customer_service':
					// 客服
					$this->wechat->transfer_customer_service();
					exit;

					break;
				default:
					break;
			}

		}
	}

	/**
	 * 关键词自动回复
	 * @param $keyword
	 * @param $fromUser
	 * @param $toUser
	 */
	public function smartReply($keyword = '') {
		$keyword = addslashes($keyword);

		#匹配一条规则进行回复
		$ruleId = false;
		#1.完全匹配模式.
		$match = $this->db->get("SELECT * FROM ###_wx_autoreply_keyword WHERE `match`=1 AND `keyword`='" . $keyword . "'");
		if (isset($match['id'])) {
			$ruleId = $match['rule_id'];
		}

		#2.模糊匹配.
		#关键词检索回复(多用户平台,限制单用户最多可设置200关键词).
		if ($ruleId === false) {
			$keywords = $this->db->select("SELECT * FROM ###_wx_autoreply_keyword WHERE `match`=0 ");
			foreach ($keywords as $val) {
				if (strpos($keyword, $val['keyword']) !== false) {
					$ruleId = $val['rule_id'];

					break; #$ruleIds[$val['rule_id']] = $val['rule_id'];
				}
			}
		}

		if (is_numeric($ruleId)) {
			$this->outputByRuleId($ruleId);
			exit;
		}
	}

	/**

	 * 客服部分
	 */

	// 注意微信限制每天最多发起2万次会话
	// 每次成功发起createsession 为准
	// session ecsited 也会计算次数
	// 总计最大2万次。 此为微信服务号限制 数字无法变更
	// 微信多客服交谈模式为 微信消息  当用户在微站内时 无法看到微信消息
	/**
	 * 主动创建客服会话
	 * @param  string $openid
	 * @param  string $kfaccount
	 * @param  string $text      '会话接通时客服看到的文字'
	 * @return void
	 */
	public function createSession($openid, $kfaccount = '', $text = '用户通过网页连接到客服') {

		$kfaccount = $kfaccount ? $kfaccount : $this->getKf();

		if ($this->wechat->createKFSession($openid, $kfaccount, $text)) {
			$errMsg = $this->wechat->errMsg;
			if ('session exsited' == $errMsg) {
				// 当前会员和本客服已有会话
				exit(json_encode(array('error' => 0, 'msg' => '客服已连接, 转到聊天界面吗?', 'tag' => '6')));
			} else {
				exit(json_encode(array('error' => 0, 'msg' => '现在关闭微站转到在线客服？', 'tag' => '5')));
			}

		};

		$errCode = $this->wechat->errCode;

		// 当前会员和其他客服已有会话
		if ('61458' == $errCode) {
			exit(json_encode(array('error' => 0, 'msg' => '已经有客服为您服务, 转到聊天界面吗?', 'tag' => '4')));
		}

		if ('61459' == $errCode) {
			exit(json_encode(array('error' => 2, 'msg' => '对不起, 客服不在线', 'tag' => '10')));
		}

		// 未知错误
		exit(json_encode(array('error' => 2, 'msg' => '对不起，当前无空闲客服，请稍后重试', 'tag' => '3')));

	}

	/**
	 * 获取当前可用的空闲客服
	 * 当客服超过最大连接数 目前是10时 报繁忙错误
	 * @return string 客服id
	 */
	public function getKf() {

		$list = $this->getOnlineKfList();

		if (!$list) {
			exit(json_encode(array('error' => 2, 'msg' => '对不起，客服不在线或系统繁忙，请稍后重试', 'tag' => '1')));
		}

		if (count($list) > 1) {

			// 当在线客服数量大于1时
			// 按照当前接待人数对在线客服排序。选择当前最空闲的客服，
			$list = list_sort_by($list, 'accepted_case');
		}

		// 可选 当最空闲的客服同时连接数超过10的时候 提示繁忙。
		if ($list[0]['accepted_case'] > 10) {
			exit(json_encode(array('error' => 2, 'msg' => '对不起，所有客服均很繁忙，请稍后重试', 'tag' => '2')));
		}

		// 选择第一个可用的客服
		if (isset($list[0]['kf_account']) && $list[0]['kf_account']) {
			return $list[0]['kf_account'];
		} else {
			exit(json_encode(array('error' => 2, 'msg' => '对不起，客服正在忙碌', 'tag' => '7')));
		}

	}

	/**
	 * 获取在线客服列表
	 * @return array 在线客服数组
	 */
	public function getOnlineKfList() {

		$cacheKey =  'kf_online_list';

		// 首先获取缓存
		$res = S($cacheKey);

		// 并行获取微信服务器信息
		$onlinkelist = $this->wechat->getCustomServiceOnlineKFlist();

		if (isset($onlinkelist['kf_online_list'])) {
			$res = $onlinkelist['kf_online_list'];

			S($cacheKey, $res);
		}

		return $res;
	}

}