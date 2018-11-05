<?php

/**
 * 代理控制器
 */
class agent extends Lowxp {

	function __construct() {
		parent::__construct();
		$method          = $_SERVER['request']['method'];
		$isLogin         = isset($_SESSION['mid']);
		$notLoginActions = in_array($method, array('agree', 'mshop', 'myivt_pic'));
		$this->load->model('member');
		if ($isLogin) {
			$this->memberinfo  = $this->member->member_info(MID);
			$this->memberother = $this->member->member_other(MID);
			$this->smarty->assign('member', $this->memberinfo);
			$this->smarty->assign('other', $this->memberother);
			if ($this->memberinfo['is_agent'] == 0 && $method != 'agree') {
				$this->is_agent();
			}
#判断分销商条件是否满足
		} else {
			//跳转登录
			if (!$notLoginActions) {
				login();
			}
		}
		$this->display_before(array('title' => '分销中心'));
	}

	/*
		*判断用户是否具备分销资格
	*/
	function is_agent() {
		$this->load->model("agent");                
		//判断分销商资格	                
                $is_agent = 0;
                if ($this->memberinfo['agent_rank'] > 0) {
                        $is_agent = $this->memberinfo['agent_rank'];
                } else {
                        $res = $this->agent->getComss(MID);
                        if ($res['id']) {
                                $r = $this->db->update('member', array('agent_rank' => $res['id']), array('mid' => MID));
                        }
                        $is_agent = $res['id'];
                }
                
		if ($is_agent==0) {
			$list                    = $this->agent->getCommsRankList();
			$row                     = end($list);
			$row['conditions_array'] = array_filter($row['conditions_array']);
			$this->smarty->assign("row", $row);
			$this->smarty->display("agent/not_agent.html");
			exit;
		} elseif ($this->memberother['is_agent'] == 0) {
			$this->smarty->display("agent/agent_agree.html");
			exit;
		}
	}

	/*
		*同意分销商协议
	*/
	function agree() {
		if (!isset($_POST['agree'])) {
			exit($this->msg('请先同意分销协议', array('icon' => 8)));
		}

		$r = $this->db->update("member", array("is_agent" => 1, "agent_time" => RUN_TIME), array("mid" => MID));
		exit($this->msg('开店成功', array('icon' => 1, 'url' => url("/agent/index"))));
	}

	/*
		*分销中心首页
	*/
	function index() {
		$data = array();

		if (STPL == 'mobile') {
			$row['head'] = '分销中心';
			$this->smarty->assign('row', $row);
		}

		$this->smarty->display("agent/index.html");

	}

	/*
		*代理申请
	*/
	function apply($step = 0) {

		if ($step == 0) {
//申请代理须知

			$this->smarty->display("agent/apply_note.html");

		} else {
//申请代理
			$url = "/member";
			if (isset($_POST['Submit'])) {

				$post   = $_POST['post'];
				$areaid = end(array_filter($post['area']));
				$type   = $post['type'];
				$areaid = $type == 2 ? $post['area'][0] : $post['area'][1];
				$mobile = trim($post['mobile']);

				if ($type == 0) {
					exit($this->msg('请选择代理类型', array('icon' => 2)));
				}

				if ($type > 1 && $areaid <= 0) {
					exit($this->msg('请选择地区', array('icon' => 2)));
				}

				if ($mobile == '') {
					exit($this->msg('请填写手机号码', array('icon' => 2)));
				}

				#判断是否已申请
				$sql = "select 1 from ###_member_agent where status in (0,1) and mid='" . MID . "'";
				if ($this->db->get($sql)) {
					exit($this->msg('您已申请，请等待管理员审核', array('icon' => 8, 'url' => url($url))));
				}

				#判断盖地区是否已申请
				if ($type > 1) {
					$sql = "select 1 from ###_member_agent where status in (0,1) and type='{$type}' and areaid='{$areaid}'";
					if ($this->db->get($sql)) {
						exit($this->msg('该地区已有人申请', array('icon' => 8, 'url' => url($url))));
					}
				}

				$insert_array['mid']    = MID;
				$insert_array['type']   = $post['type']; //1:合伙人，2：省代理，3市代理
				$insert_array['mobile'] = $mobile;
				$insert_array['areaid'] = $areaid;
				$insert_array['c_time'] = RUN_TIME;
				$res                    = $this->db->insert('member_agent', $insert_array);

				if ($res) {
					// 模版消息 5 会员申请成为合伙人或代理 {插入昵称}
					// template_msg_action start
					$this->load->model('template_msg');
					$msgParams = array(getUsername(MID));
                                        if ($type > 1) {
                                            $this->template_msg->inQueue(5, 0, $msgParams);
                                        }else{
                                            $this->template_msg->inQueue(27, 0, $msgParams);
                                        }					
					// template_msg_action end
					exit($this->msg('申请成功', array('icon' => 1, 'url' => url($url))));
				} else {
					exit($this->msg('申请有误，请联系客服', array('icon' => 2, 'url' => url($url))));
				}

			}

			$this->load->model('linkage');

			$pro = $this->linkage->select_linkage(1, 1, 'area');

			$this->smarty->assign("pro", $pro);

			$this->smarty->display("agent/apply.html");

		}

	}

	/*
		*推广图片
	*/
	function myivt_pic($mid = 0) {
		$data = array();

		if (STPL == 'mobile') {
			$row['head'] = '推广图片';
			$this->smarty->assign('row', $row);
		}

		$myivt_url = url('/member/index') . '?inviter_id=' . $mid;
		$qrcode    = creat_code($myivt_url, 'qr' . $mid . '.png');

		$this->smarty->assign('qrcode', $qrcode);
		$this->smarty->assign('mid', $mid);
		$this->smarty->display("agent/myivt_pic.html");

	}

	/*
		*推广名片
	*/
	function myivt_card($mid = 0) {
		$data = array();

		if (STPL == 'mobile') {
			$row['head'] = '推广名片';
			$this->smarty->assign('row', $row);
		}
		$this->load->model("member");
		$detail = $this->member->member_info($mid);
		$this->smarty->assign('detail', $detail);
		$this->smarty->assign('mid', $mid);
		$this->smarty->display("agent/myivt_card.html");

	}

	/*
		*推广商品
	*/
	function promotion($page = 1) {
		//获取一级佣金比例
		$this->load->library("agent");
		$scale = $this->agent->get_agent_scale(MID);

		if (STPL == 'mobile') {
			$row['head'] = '推广商品';
			$this->smarty->assign('row', $row);
		}
		$data = array();
		$this->load->model('goods');
		$options = array();
		$size    = 10;

		$re_mshop = $this->db->get("select good_id from ###_mshop where mid=" . MID);

		#商品列表
		$data['list'] = $this->goods->getList($size, $page, $id, $type, $options);

		#异步加载
		if (isset($_GET['load'])) {
			if ($data['list']) {
				$content = '';
				foreach ($data['list'] as $v) {
					$v['is_mshop'] = $v['comms'] = 0;
					if ($re_mshop && strpos($re_mshop['good_id'], "," . $v['id'])) {
						$v['is_mshop'] = 1;
					}

					if ($scale > 0) {
						$v['comms'] = round($v['price'] * $scale * 0.01, 2);
					}

					$this->smarty->assign('m', $v);
					$content .= $this->smarty->fetch('agent/lbi/list.html');
				}
				echo $content;die;
			}
		} else {
			if ($data['list']) {
				foreach ($data['list'] as $k => $v) {
					$data['list'][$k]['is_mshop'] = $data['list'][$k]['comms'] = 0;
					if ($re_mshop && strpos($re_mshop['good_id'], "," . $data['list'][$k]['id'])) {
						$data['list'][$k]['is_mshop'] = 1;
					}

					if ($scale > 0) {
						$data['list'][$k]['comms'] = round($v['price'] * $scale * 0.01, 2);
					}

				}
			}
		}

		$this->smarty->assign('data', $data);
		$this->display_before($data['row']);
		$this->smarty->display("agent/promotion.html");

	}

	/*
			*分销排行榜
		        * param int $type 1:分销总排行榜，2：盟友排行榜
	*/
	function agent_sort($type = 1) {
		$data = array();

		if (STPL == 'mobile') {
			$row['head'] = '分销排行榜';
			$this->smarty->assign('row', $row);
		}
		if ($type == 1) {
			$sql          = "select username,commission_total from ###_member order by commission_total desc,mid asc limit 10";
			$data['list'] = $this->db->select($sql);
		} else {
			$this->load->model('agent');
			$m_str = $this->agent->getInvIdsAll(MID);
			if ($m_str) {
				$sql          = "select username,commission_total from ###_member where mid in ({$m_str}) order by commission_total desc,mid asc limit 10";
				$data['list'] = $this->db->select($sql);
			}
		}

		$this->smarty->assign("type", $type);
		$this->smarty->assign("data", $data);
		$this->smarty->display("agent/agent_sort.html");

	}

	/*
		*添加/取消推广商品
	*/
	function ajax_mshop($good_id) {

		$data = array("error" => 1, "msg" => '');
		if ($good_id == false) {
			$data['msg'] = "请选择商品";
			echo json_encode($data);exit;
		}
		$where    = " where mid=" . MID;
		$sql      = "select good_id from ###_mshop {$where}";
		$res      = $this->db->get($sql);
		$good_str = $res == false ? "0" : $res['good_id'];

		if (strpos($good_str, "," . $good_id)) {
			$ids = str_replace("," . $good_id, "", $good_str);
			if (!$res) {
				$r = $this->db->insert("mshop", array("mid" => MID, "good_id" => $ids));
			} else {
				$r = $this->db->update("mshop", array("good_id" => $ids), array("mid" => MID));
			}
			$r = $this->db->query($sql);
			if (false !== $r) {
				$data['error'] = 0;
				$data['type']  = 2;
				$data['msg']   = "取消分销";
				echo json_encode($data);exit;
			}
		} else {
			$ids = $good_str . "," . $good_id;
			if (!$res) {
				$r = $this->db->insert("mshop", array("mid" => MID, "good_id" => $ids));
			} else {
				$r = $this->db->update("mshop", array("good_id" => $ids), array("mid" => MID));
			}
			$r = $this->db->query($sql);
			if (false !== $r) {
				$data['error'] = 0;
				$data['type']  = 1;
				$data['msg']   = "我要分销";
				echo json_encode($data);exit;
			}
		}
	}
	/*
		*微店素材
	*/
	function mshop_edit() {

		if ($_POST['Submit']) {

			$this->load->model('upload');

			$thumb = array('thumb' => array('width' => 640, 'height' => 320));

			$img_src = $this->upload->save_image('mshop', 'images', $thumb);

			if ($img_src) {

				$res = $this->member->member_other_save(array("mshop_img" => $img_src), MID);

				exit($this->msg('修改成功', array('icon' => 1, 'url' => url("/agent/mshop_edit"))));
			} else {
				exit($this->msg('请选择图片', array('icon' => 8, 'url' => url("/agent/mshop_edit"))));
			}

		}

		$row = $this->memberother;
		$this->smarty->assign("row", $row);
		$this->smarty->display("agent/mshop_edit.html");
	}
	/*
		*微店资料
	*/
	function mshop_setting() {

		if ($_POST['Submit']) {
			$post                 = $_POST['post'];
			$data['mshop_name']   = trim($post['mshop_name']);
			$data['wx']           = trim($post['wx']);
			$data['mshop_notice'] = trim($post['mshop_notice']);
			$res                  = $this->member->member_other_save($data, MID);
			exit($this->msg('修改成功', array('icon' => 1, 'url' => url("/agent/mshop_setting"))));
		}

		$row = $this->memberother;
		$this->smarty->assign("row", $row);
		$this->smarty->display("agent/mshop_setting.html");
	}
	/*
		*分享设置
	*/
	function mshop_share() {

		if ($_POST['Submit']) {
			$post                = $_POST['post'];
			$data['mshop_share'] = trim($post['mshop_share']);
			$data['good_share']  = trim($post['good_share']);
			$res                 = $this->member->member_other_save($data, MID);
			exit($this->msg('修改成功', array('icon' => 1, 'url' => url("/agent/mshop_share"))));
		}

		$row = $this->memberother;
		$this->smarty->assign("row", $row);
		$this->smarty->display("agent/mshop_share.html");
	}

	/*
		*微店
	*/
	function mshop($mid = 0, $page = 1) {
		if ($mid == 0) {
			$this->exeJs('location.href="' . url('/member') . '"');
			die;
		}
		$info               = $this->member->member_info($mid);
		$sql                = "select mshop_img,wx,mshop_name from ###_member_detail where mid=" . $mid;
		$r                  = $this->db->get($sql);
		$info['mshop_img']  = $r['mshop_img'];
		$info['wx']         = $r['wx'];
		$info['mshop_name'] = $r['mshop_name'];
		$this->smarty->assign('info', $info);

		$data = array();
		$this->load->model('goods');
		$options = array();
		$size    = 10;

		#排除取消分销的商品
		$re = $this->db->get("select good_id from ###_mshop where mid=" . MID);
		if ($re) {
			$options['where'] = "and g.id NOT IN (" . $re['good_id'] . ")";
		}
		#商品列表
		$data['list']  = $this->goods->getList($size, $page, $id, $type, $options);
		$data['total'] = $this->page->pages['total'];
		#异步加载
		if (isset($_GET['load'])) {
			if ($data['list']) {
				$content = '';
				foreach ($data['list'] as $v) {
					$this->smarty->assign('m', $v);
					$content .= $this->smarty->fetch('agent/lbi/mshop_list.html');
				}
				echo $content;die;
			}
		}
		$this->smarty->assign('data', $data);
		$this->smarty->display("agent/mshop.html");
	}

	/*
		*我的团队
	*/
	function team($level = '') {
		$this->load->model('agent');
		$data['list'] = $this->agent->getInviteLevelList(MID);
		if ($level != '') {
			$tpl          = "agent/team_level.html";
			$data['list'] = $data['list'][$level];
		} else {
			$tpl = "agent/team.html";
		}
		$this->smarty->assign('data', $data);
		$this->smarty->display($tpl);
	}

	/*
		*佣金
	*/
	function comms() {		               
		$data = $this->comms_count();
                $this->load->model('agent');
                $data['agent'] = $this->agent->userNextComm($this->memberinfo);
		$this->smarty->assign('data', $data);
		$this->smarty->display("agent/comms.html");
	}
	/*
		*佣金明细
	*/
	function comms_list($page = 1) {
		$data['head'] = '总佣金';

		$where = " and mid=" . MID;
		if ($_GET['level'] != '') {
			$level = intval($_GET['level']);
			$where .= " and level='{$level}'";
			if ($level == '-2') {
				$data['head'] = '省代理佣金';
			}

			if ($level == '-1') {
				$data['head'] = '市代理佣金';
			}

			if ($level == 0) {
				$data['head'] = '合伙人佣金';
			}

			if ($level > 0) {
				$data['head'] = $level . '级佣金';
			}

		}
		$type = trim($_GET['type']);
		if ($type) {
			if ($type == 'today') {
				$data['head'] = '今日佣金';
				$ty           = strtotime('today');
			} elseif ($type == 'week') {
				$data['head'] = '本周佣金';
				$ty           = strtotime(date("Y-m-d", strtotime('this week')));
			} else {
				$data['head'] = '本月佣金';
				$ty           = strtotime(date("Y-m-01"));
			}
			$where .= " and addtime>=" . $ty;
		}

		$this->load->model("agent");
		$_data['where'] = $where;
		$data['list']   = $this->agent->getCommission($_data, 10, $page);

		#异步加载
		if (isset($_GET['load'])) {
			if ($data['list']) {
				$content = '';
				foreach ($data['list'] as $v) {
					$this->smarty->assign('m', $v);
					$content .= $this->smarty->fetch('agent/lbi/comms.html');
				}
				echo $content;die;
			}
		}
		$sql = "SELECT sum(commission) as num FROM ###_commission where 1 " . $where;
		$res = $this->db->get($sql);
		$this->smarty->assign('total', $res['num']);
		$this->smarty->assign('data', $data);
		$this->smarty->display("agent/comms_list.html");
	}
	/*
		*佣金统计
	*/
	function comms_count() {
		$res = array();

		//各个分销级别的佣金统计
		$sql       = "select level,sum(commission) as num from ###_commission where mid=" . MID . " group by level";
		$res_count = $this->db->select($sql);
		foreach ($res_count as $k => $v) {
			$res['comms'][$v['level']] = $v['num'];
		}

		$tday = strtotime('today');
		$wday = strtotime(date("Y-m-d", strtotime('this week')));
		$mday = strtotime(date("Y-m-01"));

		//今日佣金
		$sql          = "select sum(commission) as num from ###_commission where mid=" . MID . " and addtime>=" . $tday;
		$res_today    = $this->db->get($sql);
		$res['today'] = !empty($res_today['num']) ? $res_today['num'] : 0.00;
		//本周佣金
		$sql         = "select sum(commission) as num from ###_commission where mid=" . MID . " and addtime>=" . $wday;
		$res_week    = $this->db->get($sql);
		$res['week'] = !empty($res_week['num']) ? $res_week['num'] : 0.00;
		//本月佣金
		$sql          = "select sum(commission) as num from ###_commission where mid=" . MID . " and addtime>=" . $mday;
		$res_month    = $this->db->get($sql);
		$res['month'] = !empty($res_month['num']) ? $res_month['num'] : 0.00;
		return $res;
	}

	/**
	 * 佣金转余额
	 */
	function withdraw_commission() {
		//$this->member->commission_fee(100);
		$member = $this->memberinfo;

		//$bankcard = $this->member->bankcard(MID);

		if ($_POST['Submit']) {
			//令牌检查
			if (!checkToken()) {exit($this->msg('请不要重复提交！'));}

			$withdraw_money = is_numeric($_POST['amount']) ? $_POST['amount'] : 0;
			if ($withdraw_money <= 0) {
				exit($this->msg('请输入正确金额'));
			}

			//if($withdraw_money < $this->site_config['withdraw_commission']) exit($this->msg('申请提现佣金必须大于或等于'.$this->site_config['withdraw_commission']));
			if ($this->memberinfo['commission'] < $withdraw_money) {
				exit($this->msg('金额不能大于可用佣金'));
			}

			//if($withdraw_money>4000) exit($this->msg('大额佣金提现请联系客服处理'));
			//$commission_fee = $this->member->commission_fee($withdraw_money);

			$insert_arr               = array();
			$insert_arr['mid']        = MID;
			$insert_arr['username']   = USER;
			$insert_arr['commission'] = $withdraw_money;
			//$insert_arr['amount']     = $commission_fee['amount'];
			//$insert_arr['fee']        = $commission_fee['fee'];
			$insert_arr['amount']  = $withdraw_money;
			$insert_arr['fee']     = 0;
			$insert_arr['status']  = 1;
			$insert_arr['addtime'] = RUN_TIME;
			$this->db->insert('withdraw_commission', $insert_arr);
			$this->db->update('member', "commission = commission-$withdraw_money , deduct_commission = deduct_commission + $withdraw_money", array('mid' => MID));
			exit($this->msg('申请成功', array('icon' => 1, 'url' => url('/member/withdraw_list'))));
		}
		$this->smarty->assign('type', $type);
		$this->smarty->assign('bank', $bankcard[0]);
		$this->smarty->display('agent/withdraw_commission.html');
	}

	/**
	 * 佣金提现
	 */
	function withdraw() {
		$member = $this->memberinfo;

		if (empty($member['realname'])) {
			$this->error('请先进行实名认证', url('/member/verifyidcard'));
		}

		$bankcard = $this->member->bankcard(MID);
		foreach ($bankcard as $k => $v) {
			$bankcard[$k]['bankcard'] = substr($v['bankcard'], 0, 3) . '*****' . substr($v['bankcard'], -3);
		}

		if (empty($bankcard)) {
			$this->error('请先绑定银行卡', url('/member/bankcard'));
		}

		#获取提现手续费列表
		$feeList = $this->base->explodeChar($this->site_config['withdraw_fee']);
		$this->smarty->assign('feelist', $feeList);

		if ($_POST['Submit']) {
			$pay_id   = intval($_POST['id']);
			$gotime   = isset($_POST['gotime']) ? trim($_POST['gotime']) : '';
			$bankcard = $this->db->get("SELECT * FROM ###_member_bankcard WHERE mid = '" . MID . "' AND id = '$pay_id'");
			if (empty($bankcard)) {
				$this->error('您选择的银行卡不存在，请重新选择');
			}

			$amount = floatval($_POST['amount']);
			if (empty($amount)) {
				$this->error('请输入正确的提现金额');
			}

			if ($amount > $member['commission']) {
				$this->error('提现金额超过了可用佣金');
			}

			//提现手续费
			if (isset($feeList[$gotime])) {
				if (strpos($feeList[$gotime], '%')) {
					$path = str_replace('%', '', $feeList[$gotime]) / 100;
					$fee  = $amount * $path;
				} else {
					$fee = $feeList[$gotime];
				}
			}

			$input              = array();
			$input['gotime']    = $gotime;
			$input['mid']       = MID;
			$input['username']  = USER;
			$input['amount']    = $amount - $fee;
			$input['pay_id']    = $pay_id;
			$input['pay_name']  = $bankcard['bankname'];
			$input['type']      = 2;
			$input['fee']       = $fee ? $fee : 0;
			$input['status']    = 1;
			$input['user_note'] = $bankcard['bankcard'];
			$input['from']      = 1; #1佣金提现，2余额提现
			$state              = $this->member->member_account_save($input);

			//冻结提现金额

			if ($state['code'] == 0) {
				$this->db->update('member', "commission = commission-$amount , deduct_commission = deduct_commission + $amount", array('mid' => MID));
			}
			// 模版消息 6 会员申请提现 {插入昵称}
			// template_msg_action start
			$this->load->model('template_msg');
			$msgParams = array(USER);
			$this->template_msg->inQueue(6, 0, $msgParams);
			// template_msg_action end
			$this->success('申请成功,我们将尽快为您处理！', url('/member/accountlog'));
		}

		$this->smarty->assign('list', $bankcard);
		$this->smarty->assign('nav', 'account');
		$this->smarty->display('agent/withdraw.html');

	}
}