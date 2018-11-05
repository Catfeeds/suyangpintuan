<?php

/**

 * Created by JetBrains PhpStorm.

 * User: lowxp

 * Date: 13-5-30

 * Time: 上午9:38

 * To change this template use File | Settings | File Templates.

 */
class wechat_model extends Lowxp_Model {
	public $fromUser;
	public $toUser;

	/**

	 * 会员关注微信,回复信息,首次关注创建用户

	 * @param $post

	 */

	function subscribe($post) {
		#$this->debug->WriteLog("SubScribe:-------------");
		$this->checkMemberExists($post['FromUserName']);
		//todo:获取公司设置，给用户返回的第一句话

		//关注回复的url为固定url
		#$msgUrl = RootUrl.'wx/'.$_SESSION['flag'].'/'.$post['FromUserName'].'/init';

		$rule = $this->db->get("SELECT * FROM ###_wx_autoreply_rule WHERE rule_type='subscribe'");
		if (!isset($rule['id'])) {
			return;
		}

		$rule_id = $rule['id'];
		$this->outputByRuleId($rule_id);

		#$reply = $this->db->get("SELECT * FROM wx_reply WHERE re_type=1");
		#$msg = $reply['content'];
		#$wxXml = $this->textTpl($post['FromUserName'],$post['ToUserName'],$msg);
		#echo $wxXml;die;
	}

	/**
	 * 取消关注
	 * @param $post
	 */
	function unsubscribe($post) {
		$openid = $post['FromUserName'];
		$member = $this->db->get("SELECT * FROM ###_member WHERE openid='{$openid}'");
		$this->debug->WriteLog('unsubscribe Done.');
		if (isset($member['mid'])) {
			$this->db->update('member', array('subscribe_time' => '0'), array('mid' => $member['mid']));
			#$statistic = new service_statistic();
			#$statistic->unsubscribe($member['mid']);
		}
	}

	function location($post) {
		if (!isset($post['Latitude'])) {
			return;
		}

		$openid = $post['FromUserName'];
		$member = $this->db->get("SELECT * FROM ###_member WHERE openid='{$openid}'");
		if (isset($member['mid'])) {

			$lat = $post['Latitude'];
			$lng = $post['Longitude'];

			$location = $this->db->get(array('tableName' => 'member_location', 'condition' => 'mid=' . $member['mid']));
			$input    = array(
				'lat'       => $lat,
				'lng'       => $lng,
				'precision' => $post['Precision'],
				'u_time'    => time(),
			);

			if (isset($location['mid'])) {
				$this->db->update('member_location', $input, array('mid' => $member['mid']));
			} else {
				$input['mid'] = $member['mid'];
				$this->db->insert('member_location', $input);
			}
			$this->setUserCity($member['mid'], $lat, $lng);
		}

	}

	/**
	 * 检查用户是否存在,不存在则创建.
	 * @param $openid
	 * @return bool|string
	 */
	public function checkMemberExists($openid) {
		//$member = $this->db->get("SELECT * FROM ###_member WHERE openid='{$openid}'");
		$member = $this->db->get("SELECT * FROM ###_oauth WHERE openid='{$openid}'");

		if (isset($member['mid'])) {
			//todo@类型记录:1新增关注，2重新关注。
			//更新关注状态,更新关注状态,关注时间,重载用户最新信息
			$this->db->update('member', array('subscribe_time' => time()), array('mid' => $member['mid']));
		}
	}

	/**
	 * 检查菜单中所绑定的事件.
	 * @param $eventKey
	 * @param $fromUser
	 * @param $toUser
	 */
	function checkMenuEvent($eventKey, $fromUser, $toUser) {
		$row = $this->db->get("SELECT `release` FROM ###_wx_menu_data WHERE id=1");
		if (!isset($row['release'])) {
			echo $this->textTpl($fromUser, $toUser, 'Error.1');exit;
		}

		$data = json_decode($row['release'], true);

		if (isset($data[$eventKey])) {
			#(array)menuData::key,type:text|news|wheel|image,data:news|1,wheel|2...
			$menuData = $data[$eventKey];

			//文本回复.
			if ($menuData['type'] == 'text') {
				$xx = $this->textTpl($fromUser, $toUser, $menuData['data']);
				die($xx);
			}

			//图文回复.
			if ($menuData['type'] == 'news') {
				$segments = explode('|', $menuData['data']);
				$newsId   = $segments[1];
				$wxnews   = $this->db->select("SELECT * FROM ###_wx_news WHERE id=" . $newsId);
				$this->load->model('wxnews');
				$wxnews = $this->wxnews->getNewsInfo($wxnews);
				$news   = $wxnews[0];

				foreach ($news['items'] as $k => $row) {
					$news['items'][$k]['imgurl'] = $row['imgurl_src'];
				}

				if (count($news['items']) == 1) {
					$news['items'][0]['content'] = $news['items'][0]['desc'];
				}
				echo $this->newsTpl($fromUser, $toUser, $news['items']);
				die;
			}

			//大转盘回复.
			if ($menuData['type'] == 'wheel') {
				$segments = explode('|', $menuData['data']);
				$wheelId  = $segments[1];
				if (!is_numeric($wheelId)) {
					$this->textTpl($fromUser, $toUser, 'Error.2');die;
				}
				$wheel = $this->db->get("SELECT * FROM ###_wx_lottery_wheel WHERE id=" . $wheelId);
				$this->checkWheelNews($wheel, $fromUser, $toUser);
				die;
			}

		}
	}

	/**
	 * 关键词自动回复
	 * @param $keyword
	 * @param $fromUser
	 * @param $toUser
	 */
	function smartreply($keyword, $fromUser, $toUser) {
		$keyword = addslashes($keyword);
		#检查大转盘关键词.

		$this->db->query("SHOW TABLES LIKE '%wx_lottery_wheel%'");
		if ($this->db->num_rows() > 0) {
			$wheel = $this->db->get("SELECT * FROM ###_wx_lottery_wheel WHERE `keyword`='" . $keyword . "'");
			if (isset($wheel['id'])) {
				$this->checkWheelNews($wheel, $fromUser, $toUser);
			}
		}

		#匹配一条规则进行回复
		$ruleId = false;
		#1.完全匹配模式.
		$find = $this->db->get("SELECT * FROM ###_wx_autoreply_keyword WHERE `match`=1 AND `keyword`='" . $keyword . "'");
		if (isset($find['id'])) {
			$ruleId = $find['rule_id'];
		}

		#2.模糊匹配.
		#关键词检索回复(多用户平台,限制单用户最多可设置200关键词).
		if ($ruleId === false) {
			$kws = $this->db->select("SELECT * FROM ###_wx_autoreply_keyword WHERE `match`=0 ");
			foreach ($kws as $val) {
				if (strpos($keyword, $val['keyword']) !== false) {
					$ruleId = $val['rule_id'];
					break; #$ruleIds[$val['rule_id']] = $val['rule_id'];
				}
			}
		}

		if (is_numeric($ruleId)) {

			//回复图文信息.

			#根据对应RULEID，进行回复。

			$this->fromUser = $fromUser;

			$this->toUser = $toUser;

			$this->outputByRuleId($ruleId);

			exit;

		}

	}

	/**
	 * 根据规则输出对应消息.
	 * @param $rule_id
	 */
	function outputByRuleId($rule_id) {
		if (!is_numeric($rule_id)) {
			return;
		}

		$rule = $this->db->get("SELECT * FROM ###_wx_autoreply_rule WHERE id=" . $rule_id);
		if (!isset($rule['id'])) {
			return;
		}

		$reply_list = json_decode($rule['reply_list'], true);
		$this->load->model('wxnews');
		$newsList = $this->wxnews->getMsgsByReplyList($reply_list);
		$retList  = array();

		if (isset($newsList['msg_type'])) {
			$newsList = array($newsList);
		}

		foreach ($newsList as $val) {
			#echo '<pre>';print_r($val);echo '</pre>';

			switch ($val['msg_type']) {
				case 'news':
					$retList[] = $this->newsTpl($this->fromUser, $this->toUser, $val['news_list']);
					break;
				case 'text':
					if (trim($val['content']) != '') {
						$retList[] = $this->textTpl($this->fromUser, $this->toUser, $val['content']);
					}

					break;
				case 'wheel':
					$wheel        = $val['news_list'][0];
					$wheel['url'] = RootUrl . 'xlottery/wheel/' . $wheel['id'];
					$retList[]    = $this->newsTpl($this->fromUser, $this->toUser, $wheel);
					break;
			}
		}
		$res = implode("\n", $retList);
		$this->debug->WriteLog($res);
		echo $res;
		//echo '<pre>';print_r($retList);echo '</pre>';
	}

	/**

	 * 大转盘信息提示.

	 * @param $wheel

	 * @param $fromUser

	 * @param $toUser

	 */

	function checkWheelNews($wheel, $fromUser, $toUser) {

		if (!isset($wheel['id'])) {
			return;
		}

		if ($wheel['status'] == '0') {

			echo $this->textTpl($fromUser, $toUser, '大转盘活动还没有开始!');exit;

		}

		if ($wheel['status'] == '2') {

			$this->textTpl($fromUser, $toUser, '大转盘活动已结束!');exit;

		}

		$this->load->model('upload');

		#时间在区间中.

		if (RUN_TIME > $wheel['begin_time']) {

			#overtimes:活动次数已玩完.

			if (RUN_TIME > $wheel['end_time']) {

				echo $this->textTpl($fromUser, $toUser, '大转盘活动已结束!');exit;

			}

			#活动开始,点击进入开始抽奖.

			$images = $this->upload->getImageUrl($wheel['news_cover'], 'wechat', array('src', 'thumb'));

			$xml = $this->newsTpl($fromUser, $toUser, array(

				'id'      => $wheel['id'],

				'title'   => $wheel['news_title'],

				'content' => $wheel['news_desc'],

				'imgurl'  => $images['imgurl_src'],

				'url'     => RootUrl . 'xlottery/wheel/' . $wheel['id'],

			));

			echo $xml;

		} elseif (RUN_TIME > $wheel['begin_time'] - 600) {

			#10分钟内,活动预热,点击进入活动说明.

			$images = $this->upload->getImageUrl($wheel['ready_news_cover'], 'wechat', array('src', 'thumb'));

			$xml = $this->newsTpl($fromUser, $toUser, array(

				'id'      => $wheel['id'],

				'title'   => $wheel['ready_news_title'],

				'content' => $wheel['ready_news_desc'],

				'imgurl'  => $images['imgurl_src'],

				'url'     => RootUrl . 'wheelDetail/wheelDetail/' . $wheel['id'],

			));

			echo $xml;

		} else {

			$xml = $this->textTpl($fromUser, $toUser, '活动将于' . (

				date('Y-m-d H:i', $wheel['begin_time']) . '-' .

				date('Y-m-d H:i', $wheel['end_time'])

			) . '举行,敬请期待!');

			echo $xml;

		}

		exit;

	}

	/**

	 * 微信图文信息模板

	 * @param $fromUser

	 * @param $toUser

	 * @param $title

	 * @param $desc

	 * @param $picUrl 该条信息图片地址

	 * @param $url    该条信息链接地址

	 * @return string  xml

	 */

	function subscribeTpl($fromUser, $toUser, $title, $desc, $picUrl, $url) {

		$wxTpl = "<xml>

 <ToUserName><![CDATA[^toUser]]></ToUserName>

 <FromUserName><![CDATA[^fromUser]]></FromUserName>

 <CreateTime>^time</CreateTime>

 <MsgType><![CDATA[news]]></MsgType>

 <ArticleCount>1</ArticleCount>

 <Articles>

 <item>

 <Title><![CDATA[^title]]></Title>

 <Description><![CDATA[^description]]></Description>

 <PicUrl><![CDATA[^picurl]]></PicUrl>

 <Url><![CDATA[^url]]></Url>

 </item>

 </Articles>

 <FuncFlag>1</FuncFlag>

 </xml>";

		$wxTpl = str_replace(

			array('^toUser', '^fromUser', '^time', '^title', '^description', '^picurl', '^url'),

			array($fromUser, $toUser, time(), $title, $desc, $picUrl, $url),

			$wxTpl

		);

		return $wxTpl;

	}

	/**

	 * 文本模板

	 *

	 * @param $fromUser

	 * @param $toUser

	 * @param $text

	 * @return mixed|string

	 */

	function textTpl($fromUser, $toUser, $text) {

		$wxTpl = "<xml>

    <ToUserName><![CDATA[^toUser]]></ToUserName>

    <FromUserName><![CDATA[^fromUser]]></FromUserName>

    <CreateTime>^time</CreateTime>

    <MsgType><![CDATA[text]]></MsgType>

    <Content><![CDATA[^text]]></Content>

    <MsgId>" . rand(100000, 9999999) . "</MsgId>

</xml>";

		$wxTpl = str_replace(

			array('^toUser', '^fromUser', '^time', '^text'),

			array($fromUser, $toUser, time(), $text),

			$wxTpl

		);

		return $wxTpl;

	}

	/**

	 * 图文模板

	 * @param $fromUser

	 * @param $toUser

	 * @param $data

	 * @return string

	 */

	function newsTpl($fromUser, $toUser, $data) {

		if (isset($data['id'])) {

			$data = array($data);

		}

		$wxTpl = "<xml>

    <ToUserName><![CDATA[" . $fromUser . "]]></ToUserName>

    <FromUserName><![CDATA[" . $toUser . "]]></FromUserName>

    <CreateTime>" . time() . "</CreateTime>

    <MsgType><![CDATA[news]]></MsgType>

    <ArticleCount>" . count($data) . "</ArticleCount>

    <Articles>";

		foreach ($data as $v) {

			$wxTpl .= "

        <item>

        <Title><![CDATA[" . $v['title'] . "]]></Title>

        <Description><![CDATA[" . (isset($v['desc']) ? $v['desc'] : $v['content']) . "]]></Description>

        <PicUrl><![CDATA[" . $v['imgurl'] . "]]></PicUrl>

        <Url><![CDATA[" . (

				isset($v['url'])

				? $v['url']

				: RootUrl . 'wxnews/detail/' . $v['id']

			) . "]]></Url>

        </item>";

		}

		$wxTpl .= "

    </Articles>

</xml> ";

		return $wxTpl;

	}

	/**

	 * 多客服模板

	 *

	 * @param $fromUser

	 * @param $toUser

	 * @param $text

	 * @return mixed|string

	 */

	function serviceTpl($fromUser, $toUser) {

		$wxTpl = "<xml>

    <ToUserName><![CDATA[^toUser]]></ToUserName>

    <FromUserName><![CDATA[^fromUser]]></FromUserName>

    <CreateTime>^time</CreateTime>

    <MsgType><![CDATA[transfer_customer_service]]></MsgType>

</xml>";

		$wxTpl = str_replace(

			array('^toUser', '^fromUser', '^time'),

			array($fromUser, $toUser, time()),

			$wxTpl

		);
		return $wxTpl;

	}

}
