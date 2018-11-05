<?php

/**
 * Class welcome
 */
class member extends Lowxp {

	function __construct() {
		parent::__construct();
		$method  = $_SERVER['request']['method'];
		$isLogin = isset($_SESSION['mid']);

		//注册,提交注册,登录,忘记密码:不能登录状态的方法.
		$notLoginActions = in_array($method, array('register', 'submit', 'login', 'act_login', 'forget', 'check_username', 'check_ivt', 'check_email', 'check_mobile', 'resetpass', 'oauth', 'oauth_login', 'oauth_chose', 'mobile'));
		//除以上模块外,其他都需要登录状态进行操作.
		if ($isLogin) {
			if ($notLoginActions) {
				//$this->exeJs('alert("当前已登录,该操作需在未登录状态下.");');
				//跳转到一个初始页面
				$this->exeJs('location.href="/"');
				die;
			}

			$this->load->model('member');
			$this->memberinfo = $this->member->member_info(MID);
			$this->smarty->assign('member', $this->memberinfo);
		} else {
			//跳转登录
			if (!$notLoginActions) {
				login();
			}
			if ($method == 'doexit') {
				return;
			}
		}
		$this->display_before(array('title' => '会员中心'));
	}
	/**我的收藏*/
	function fav($page = 1) {
		$this->load->model('fav');
		$data = array();

		$size         = 10;
		$data['list'] = $this->fav->getList($size, $page, $_SESSION['mid']);
		#异步加载
		if (isset($_GET['load'])) {
			if ($data['list']) {
				$content = '';
				foreach ($data['list'] as $v) {
					$this->smarty->assign('m', $v);
					$content .= $this->smarty->fetch('member/lbi/fav_list.html');
				}
				echo $content;die;
			}else{
				echo null;die;
			}
		}
		$this->smarty->assign('data', $data);
		$this->display_before(array('title' => '我的收藏'));
		$this->smarty->display('member/fav.html');
	}
	/**取消收藏*/
	function del_fav($ids) {
		$this->load->model('fav');
		$ids = trim($ids);
		$res = $this->fav->delFav($ids);
		echo json_encode($res);exit;
	}
        /**我收藏的店铺*/
	function store_fav($page = 1) {
		$this->load->model('fav');
		$data = array();

		$size         = 10;
		$data['list'] = $this->fav->getStoreList($size, $page, $_SESSION['mid']);
		#异步加载
		if (isset($_GET['load'])) {
			if ($data['list']) {
				$content = '';
				foreach ($data['list'] as $v) {
					$this->smarty->assign('m', $v);
					$content .= $this->smarty->fetch('member/lbi/store_list.html');
				}
				echo $content;die;
			}else{
				echo null;die;
			}
		}
		$this->smarty->assign('data', $data);
		$this->display_before(array('title' => '我收藏的店铺'));
		$this->smarty->display('member/store_fav.html');
	}
	/** 订单列表 */
	function order($page = 1) {
		$this->load->model('order');
		$data  = array();
		$where = '';

		//订单详情页
		$order_sn = isset($_GET['order_sn']) ? $_GET['order_sn'] : 0;
		if ($order_sn) {
			$order = $this->db->get("SELECT * FROM `###_goods_order` WHERE order_sn=" . $order_sn);
			if (!isset($order['id'])) {die($this->msg('订单不存在'));}

			$this->load->model('share');
			$orders = array($order);
			$this->load->model('order');
			$orders = $this->order->unionOrderGoods($orders);
			$orders = $this->db->lJoin($orders, 'business', 'id,name,mobile,kf_online', 'sid', 'id', 'store_');
                        
			foreach ($orders as $k => $v) {
				if (!isset($orders[$k]['goods'])) {
					$list[$k]['goods'] = array();
				}
			}
			$order = $orders[0];

			if ($order['coupon_id']) {
				$this->load->model('coupon');
				$coupon                = $this->coupon->getFullCouponLog($order['coupon_id']);
				$coupon['target_name'] = $this->coupon->getTargetName($order['coupon_id']);
				$this->smarty->assign('coupon', $coupon);
			}
			if ($order['coupon_id_sid']) {
				$this->load->model('coupon');
				$coupon                = $this->coupon->getFullCouponLog($order['coupon_id_sid']);
				$coupon['target_name_sid'] = $this->coupon->getTargetName($order['coupon_id_sid']);
				$this->smarty->assign('coupon_sid', $coupon);                                
			}
			if($order['goods'][0]['goods_discount_type']>0 && $order['common_id']>0){//团长优惠
				$order['discount_amount'] = $order['goods'][0]['goods_discount_type']==1?$order['goods'][0]['goods_team_price']:$order['goods'][0]['goods_discount_amount'];
			}
                        
                        $order['refund'] = 0;
			#判断该订单是否能退货 Feng 2015-05-26 start			
			if ($refund_days && $order['status_order'] == 10 && $order['is_refund'] == 1 && $order['confirm_time'] && ($order['confirm_time'] + $refund_days * 24 * 3600) >= RUN_TIME) {
				$order['refund'] = 1;
			}
			#判断该订单是否能退货 Feng 2015-05-26 end
			$this->smarty->assign('order', $order);
			$this->smarty->display('member/order_detail.html');
			die;
		}

		//分页
		$this->load->model('page');
		$_GET['page'] = $page;
		$size         = (int) C('page_size');
		$this->page->set_vars(array('per' => $size));

		//订单状态
		$status = isset($_REQUEST['status']) ? intval($_REQUEST['status']) : 0;
		$data['status'] = $status;
		if ($status) {
			$where .= $this->order->order_status($status, '', 1);
		}
		if (isset($_REQUEST['is_rate'])) {
			$where .= " and is_rate=" . intval($_REQUEST['is_rate']);
		}
                
		$sql  = "SELECT * FROM ###_goods_order WHERE " . ($where ? $where : '1') . " AND mid='" . MID . "' ORDER BY id DESC";
		$list = $this->page->hashQuery($sql)->result_array();
		//$this->load->model('share');
		$list = $this->order->unionOrderGoods($list);
		$list = $this->db->lJoin($list,"goods_order_common","id,mid","common_id","id","com_");
		$list = $this->db->lJoin($list, 'business', 'id,name,kf_online', 'sid', 'id', 'store_');
		$refund_days = C("refund_days");
		foreach ($list as $k => $v) {
			if (!isset($list[$k]['goods'])) {
				$list[$k]['goods'] = array();
			}
			#判断该订单是否能退货 Feng 2015-05-26 start
			$list[$k]['refund'] = 0;			
			if($refund_days && $v['status_order'] == 10 && $v['is_refund'] == 1 && $v['extension_code']!=CART_AA && ($v['confirm_time'] + $refund_days * 24 * 3600) >= RUN_TIME) {
				$list[$k]['refund'] = 1;
			}
			if($v['sid']==0){
                $list[$k]['kf_online'] = C('kf_online');
            }else{
                $list[$k]['kf_online'] = $v['store_kf_online'];
            }
		}
		//echo "<pre>";print_r($list);exit;
		#异步加载
		if (isset($_GET['load'])) {
			$content = '';
			if ($list) {				
				foreach ($list as $v) {
					$this->smarty->assign('m', $v);
					$content .= $this->smarty->fetch('member/lbi/order_list.html');
				}
			}
			echo $content;die;
		}

		$data['list'] = $list;
		$this->smarty->assign('data', $data);
		$this->smarty->display('member/order.html');
	}

	function ajax_verify_code(){
		$verify_code_id = isset($_POST['verify_code_id']) ? intval($_POST['verify_code_id']) : 0;
		if(!$verify_code_id){
			$data['error'] = true;
			$data['msg'] = '参数异常';
			echo json_encode($data);
		}
		$this->load->model('take_verify_code');
		$this->load->model('order');
		$res = $this->take_verify_code->getById($verify_code_id, 'verify_code, order_id' ,MID);
		$list = $this->order->getGoodsOrderDetailByOrderId($res['order_id'], 'id,mobile,status_pay,area,address,goods_amount', MID);
		if(!$res){
			$data['error'] = true;
			$data['msg'] = '查找不到提货码';
		}else{
			$data['error'] = false;
			$data['code_url'] = url('/member/verify_order_code?code_id='.$verify_code_id);
			$data['verify_code'] = $res['verify_code'];
			$data['address'] = $list[0]['area'].$list[0]['address'];
			$data['mobile'] = $list[0]['mobile'];
			$data['goods_name'] = $list[0]['goods_name'];
			$data['goods_amount'] = $list[0]['goods_amount'];
			switch($list[0]['status_pay']){
				case 0:
					$data['status_pay'] = '未付款';
					break;
				case 1:
					$data['status_pay'] = '部分付款';
					break;
				case 10:
					$data['status_pay'] = '付款完成';
					break;
				case 20:
					$data['status_pay'] = '不需要支付';
					break;
			}
            $data['goods_spec'] = $this->db->getstr("select goods_spec from ###_goods_order_item where order_id={$res['order_id']}");
		}
		echo json_encode($data);
	}
    //核销
    function verify_order_code(){
        if(isset($_POST['Submit'])){
            $verify_code = trim($_POST['verify_code']);
            $order_id = trim($_POST['order_id']);
            if(empty($verify_code)){
                die($this->msg('提货码不能为空',array('icon' => 8)));
            }
            $order = $this->db->get("SELECT id,mid,common_id,status_shipping,is_cod,extension_code FROM ###_goods_order WHERE id=" . $order_id);
            if(!$order){
                die($this->msg('订单不存在',array('icon' => 8)));
            }
            $take_verify_code = $this->db->get("SELECT id,mid FROM ###_take_verify_code WHERE order_id=$order_id AND verify_code={$verify_code} AND status=0");
            if(!$take_verify_code){
                die($this->msg('验证码错误或已过期',array('icon' => 8)));
            }
            if($order['common_id'] > 0 && $order['extension_code'] == CART_AA){
                $order_common = $this->db->get("SELECT mid FROM ###_goods_order_common WHERE id={$order['common_id']}");
                if(!$order_common){
                    die($this->msg('订单出错',array('icon' => 8)));
                }
                if($order_common['mid'] != $take_verify_code['mid']){
                    die($this->msg('非法操作',array('icon' => 8)));
                }
            }else{
                if($order['mid'] != $take_verify_code['mid']){
                    die($this->msg('非法操作',array('icon' => 8)));
                }
            }
            if ($order['status_shipping'] == 2) {
                $this->load->model('order');
                //非货到付款，收货时，订单交易完成
                $set = array('status_shipping' => 10);
                if($order['is_cod'] != 1){
                    $set['status_pay']   = 10;
                    $set['status_order'] = 10;
                }
                $set['confirm_time'] = RUN_TIME;
                $msg                 = '确认收货';
                $this->order->chageOrderState($order['id'], $set, $msg);
                if($order['extension_code']==CART_AA){//AA团 团长确认收货
                    $this->db->update('goods_order', array(
                        'status_pay'   => 10,
                        'status_order' => 10,
                        'status_shipping' => 10,
                    ), array('common_id' => $order['common_id']));
                }
                $this->db->update('take_verify_code', array(
                    'status'   => 1,
                ), array('id' => $take_verify_code['id']));

                #赠送优惠券
                $this->load->model("coupon");
                $this->coupon->sendOrder($order);
                
                $this->msg('验证成功',array('icon' => 1,'url'=>'/'));
            }else{
                die($this->msg('订单出错',array('icon' => 8)));
            }
        }
        $verify_code_id = isset($_GET['code_id']) ? intval($_GET['code_id']) : 0;

        $this->load->model('take_verify_code');
        $this->load->model('order');
        $res = $this->take_verify_code->getById($verify_code_id, 'verify_code, order_id,status');
        $list = $this->order->getGoodsOrderDetailByOrderId($res['order_id'], 'id,take_address_id,order_sn,mobile,status_pay,area,address,goods_amount,order_amount');

        //判断核销人员是否存在
        $is_res = $this->db->get("select 1 from ###_take_user where mid=".MID." and take_id={$list[0]['take_address_id']} and status=1");
        if(!$is_res)$this->error("无权操作！","/");

        if(!$res){
            //$this->msg('查找不到提货码', url('/'));
        }else{
            $data = $list[0];
            $data['verify_code'] = $res['verify_code'];
            $data['verify_status'] = $res['status'];
            $data['order_id'] = $res['order_id'];
            $data['address'] = $list[0]['area'].$list[0]['address'];
            /*$data['mobile'] = $list[0]['mobile'];
            $data['goods_name'] = $list[0]['goods_name'];
            $data['goods_amount'] = $list[0]['goods_amount'];
            $data['order_sn'] = $list[0]['order_sn'];
            $data['order_amount'] = $list[0]['order_amount'];
            $data['buy_num'] = $list[0]['buy_num'];*/
            switch($list[0]['status_pay']){
                case 0:
                    $data['status_pay'] = '未付款';
                    break;
                case 1:
                    $data['status_pay'] = '部分付款';
                    break;
                case 10:
                    $data['status_pay'] = '付款完成';
                    break;
                case 20:
                    $data['status_pay'] = '不需要支付';
                    break;
            }
        }
        $data['goods_spec'] = $this->db->getstr("select goods_spec from ###_goods_order_item where order_id={$res['order_id']}");
        //echo "<pre>";print_r($data);exit;
        $this->smarty->assign('data', $data);
        $this->smarty->display('member/verify_order_code.html');
    }


    /**
     * 取消订单
     */
    function cancel_nopay() {
        $nopay_time = C("nopay_time")*60;
        if($nopay_time>0){
            $order_list = $this->db->select("SELECT id,c_time FROM ###_goods_order WHERE mid=" . MID . " AND status_pay=0 AND status_shipping=0 AND status_order=0 ");
            if($order_list){
                $this->load->model('order');
                foreach($order_list as $v){
                    if(RUN_TIME-$v['c_time']>$nopay_time){
                        $this->order->chageOrderState($v['id'], array('status_order' => 1), '取消订单');
                    }
                }
            }
        }
    }
	/**
	 * 确认收货
	 */
	function finish_order($id = '', $qishu = 0) {
		$id    = intval($id);
		$qishu = intval($qishu);
		$order = $this->db->get("SELECT * FROM ###_goods_order WHERE id = '" . $id . "' and mid=".MID);
		if ($order['status_shipping'] == 2) {
			$this->load->model('order');

			//非货到付款，收货时，订单交易完成
			$set = array('status_shipping' => 10);
			if ($order['is_cod'] != 1 && $qishu <= 1) {
				$set['status_pay']   = 10;
				$set['status_order'] = 10;
			}
			$set['confirm_time'] = RUN_TIME;
			$msg                 = '确认收货';			

			$this->order->chageOrderState($id, $set, $msg);
                        
            if($order['extension_code']==CART_AA){//AA团 团长确认收货
                 $this->db->update('goods_order', array(
                        'status_pay'   => 10,
                        'status_order' => 10,
                        'status_shipping' => 10
                ), array('common_id' => $order['common_id']));
            }
            #赠送优惠券
            $this->load->model("coupon");
            $this->coupon->sendOrder($order);

            $goods_name = $this->db->getstr("select goods_name from ###_goods_order_item where order_id={$order['id']}","goods_name");
            $express_name = $this->db->getstr("select name from ###_express where id={$order['express']}","name");

            // 微信模版消息 order_succ 确认收货通知
            // wxtemplate_action start
            $this->load->model('wxtemplate');
            $msgParams = array(
                "url" => note_url("/member/order/?order_sn=".$order['order_sn']),
                "keyword1"=>$goods_name,
                "keyword2"=>$express_name,
                "keyword3"=>$order['express_num'],
                "keyword4"=>$order['area'].$order['address'],
            );
            $this->wxtemplate->inQueue($order['mid'],'order_succ',$msgParams);
            //wxtemplate_action end
		}
		//if ($qishu > 0) {
		//	$this->msg('', url('/member/package?order_sn=' . $order['order_sn']));
		//} else {
		$this->msg('', url('/member/order'));
		//}
	}

	/**
	 * 完善信息
	 */
	function info() {
        //修改昵称
        if(isset($_POST['Submit1'])){
            $nickname = trim($_POST['nickname']);
            $nickname_len      = mb_strlen($nickname, 'UTF8');
            if ($nickname_len < 2 || $nickname_len > 16) {
                die($this->msg('请输入2-15个字符长度的昵称'));
            }
            $is_res = $this->db->get("select 1 from ###_member_detail where nickname = '{$nickname}' and mid!=".MID);
            if($is_res){
                die($this->msg('该昵称已经存在'));
            }
            $this->db->update('member_detail', array('nickname' => $nickname), array('mid' => MID));
            S("H_MEMBER_DETAIL_" . MID, null);
            die($this->msg('更新成功',array('url'=>"/member/info")));
        }
        //修改手机号码
        if (isset($_POST['Submit2'])) {
            $mobile = trim($_POST['mobile']);
            //判断手机是否已绑定
            $member = $this->member->check_username($mobile, 'mobile', ' AND mid!=' . MID);
            if ($member['mobile']) {
                die($this->msg('该手机号已绑定，请更换！'));
            }
            //短信验证码
            if ($this->site_config['sms_open'] == 1 && statusTpl('sms_register')) {
                $this->load->library('sms');
                $verifycode = empty($_POST['sms_code']) ? '' : trim($_POST['sms_code']);

                /* 验证手机号验证码和IP */
                $sql = "SELECT COUNT(id) FROM ###_verify_code WHERE mobile='$mobile' AND getip='" . getIP() . "' AND verifycode='$verifycode' AND status=2 AND dateline>" . (RUN_TIME - 3600); //验证码60分钟内有效
                if ($this->db->getstr($sql) == 0) {
                    die($this->msg('手机号和验证码不匹配 或者 验证码已过期（1小时内有效）！'));
                }
            }
            $this->db->update('member', array('mobile' => $mobile), array('mid' => MID));
            die($this->msg('更新成功',array('url'=>"/member/info")));
        }
		/*if (isset($_POST['Submit'])) {
			$input = array();
			$items = array(
				'nickname', 'sex', 'wx', 'mobile', 'email',
			);
			foreach ($items as $val) {
				if (isset($_POST[$val])) {
					$input[$val] = addslashes($_POST[$val]);
				}
			}
			$reMobile = '/^\d+$/';
			//删除旧图片
			//if($input['photo']) delImage($this->memberinfo['photo']);
			//限制昵称长度
			$input['nickname'] = trim($input['nickname']);
			$nickname_len      = mb_strlen($input['nickname'], 'UTF8');
			if (!empty($input['nickname']) && ($nickname_len < 2 || $nickname_len > 16)) {
				die($this->msg('请输入2-15个字符长度的昵称'));
			}
			$input['zone'] = end(array_filter($_POST['zone']));
			//手机唯一性判断
			$member = $this->member->check_username($input['mobile'], 'mobile', ' AND mid!=' . MID);
			if ($member['mobile']) {
				die($this->msg('该手机号已绑定，请更换！'));
			}
			//邮箱唯一性判断
			if ($input['email']) {
				$is_email = $this->db->get("select 1 from ###_member_detail where email = '" . $input['email'] . "' and mid!=" . MID);
				if ($is_email) {
					die($this->msg('该邮箱已存在，请更换！'));
				}
			}

			$data['mobile'] = $input['mobile'];
			$data['zone']   = $input['zone'];
			$r              = $this->db->update('member', $data, array('mid' => MID));
			$detail         = array_diff($input, $data);
			$d              = $this->member->member_other_save($detail, MID);
			exit($this->msg('更新成功', array('icon' => 1, 'url' => 'reload')));
		}*/
		$member = $this->memberinfo;
        if(in_array($_GET['type'],array('mobile','nickname'))){
            $tpl = $_GET['type'].".html";
        }else{
            $tpl = "info.html";
        }
		$this->smarty->assign('member', $member);
		$this->smarty->display('member/'.$tpl);
	}


	/**
	 * 上传头像
	 */
	function photo() {
        $file = trim($_POST['name']);
        $this->load->model('upload');
        //$thumb = array();
        $thumb = array('thumb'=>array('width'=>320,'height'=>320));
        $pic = $this->upload->save_image('file', 'photo', $thumb,MID);
        $name = substr ( strrchr ( $pic ,  "/" ),  1 );
        $temp = $this->db->getstr("select photo from ###_member_detail where mid=".MID);

        $res = $this->db->update('member_detail',array('photo'=>$pic),array('mid'=>MID));
        if($res && $temp{0}=='/'){
            @unlink(RootDir.'web'.$temp);
        }
        S("H_MEMBER_DETAIL_" . MID, null);
        echo json_encode(array("error"=>0,"pic"=>$pic,"name"=>$name));exit;

		/*if (!isAjax() || !$_POST['image']) {
			exit;
		}
		$img = $_POST['image'];

		$ext     = 'jpg';
		$dataExt = 'jpeg';
		if (strpos($img, 'data:image/png') !== false) {
			$ext = $dataExt = 'png';
		} elseif (strpos($img, 'data:image/gif') !== false) {
			$ext = $dataExt = 'png';
		} elseif (strpos($img, 'data:image/jpeg') !== false) {
		} else {
			exit('请上传png/gif/jpg/jpeg格式的头像');
		}

		$img  = str_replace("data:image/$dataExt;base64,", '', $img);
		$img  = str_replace(' ', '+', $img);
		$data = base64_decode($img);

		$dir        = 'photo/';
		$upload_dir = $this->load->config('picture','image_url');
		$fileName   = MID . '.' . $ext;
		$file       = $upload_dir . $dir . $fileName;
		$success    = file_put_contents($file, $data);

		if ($success) {
			$this->load->model('upload');
			$this->db->update("member", array('photo' => $file), array('mid' => MID));
			$this->upload->yunsave("/" . $dir . $fileName, 'photo');
			exit('1');
		} else {
			exit('头像更新失败');
		}*/
	}

	/**
	 * 找回密码
	 */
	function forget() {
		if ($_POST['Submit']) {
            $mobile = trim($_POST['mobile']);
            $password = trim($_POST['password']);
			//注册短信验证码
            $verifycode = '';
            if ($this->site_config['sms_open'] == 1 && statusTpl('sms_register')) {
                    $this->load->library('sms');
                    $verifycode = empty($_POST['sms_code']) ? '' : trim($_POST['sms_code']);

                    /* 验证手机号验证码和IP */
                    $sql  = "SELECT id FROM ###_verify_code WHERE mobile='$mobile' AND verifycode='$verifycode' AND status=3 AND dateline>(" . time() . "-3600)"; //验证码60分钟内有效
                    $temp = $this->db->get($sql);
                    if (!$temp) {
                            exit($this->msg("手机号码和验证码不匹配或者验证码已过期（1小时内有效）"));
                    }
            }
			$member = $this->db->get("SELECT D.mid,D.salt FROM ###_member as M left join ###_member_detail as D on M.mid=D.mid WHERE M.mobile = '$mobile' ");			
			if (empty($member)) {
				exit($this->msg('该用户不存在！'));
			}
            $this->load->model('member');
            $pwd = $this->member->get_salt_hash($password,$member['salt']);
            $this->db->update("member_detail",array("password"=>$pwd),array("mid"=>$member['mid']));
            exit($this->msg('修改密码成功',array('url'=>'/member/login')));

		}
		$this->smarty->assign('row', array('head' => '密码找回'));
		$this->smarty->display('member/forget.html');
	}
	/**
	 * 重置密码
	 */
	function resetpass($username = '', $code = '') {
		$username = addslashes($username);
		$member   = $this->db->get("SELECT * FROM ###_member WHERE username = '$username'");
		if (empty($member)) {
			exit($this->msg('暂时没有该用户信息', array('iniframe' => false)));
		}

		if ($code != md5($member['mid'] . $member['salt'] . $member['c_time'])) {
			exit($this->msg('重置密码链接无效', array('iniframe' => false)));
		}

		if (isset($_POST['Submit'])) {
			$this->load->model('member');
			$pass1 = $_POST['password'];
			$pass2 = $_POST['confirm_password'];
			if (empty($pass1)) {
				exit($this->msg('密码不能为空'));
			}

			if ($pass1 != $pass2) {
				exit($this->msg('两次密码不一致'));
			}

			$setPass = $this->member->get_salt_hash($pass1, $member['salt']);
			$this->db->update('member', array('password' => $setPass), array('mid' => $member['mid']));
			exit($this->msg('密码重置成功', array('icon' => 1, 'url' => url())));
		}
		$this->smarty->assign('row', array('head' => '密码重置'));
		$this->smarty->display('member/resetpass.html');
	}
	/**
	 * 登录页
	 */
	function login() {
		if (defined('MID')) {
			$this->redirect('/member/index');
		}
		$back_url = empty($_SERVER['HTTP_REFERER']) ? (empty($_GET['back']) ? '' : $_GET['back']) : $_SERVER['HTTP_REFERER'];
		$this->smarty->assign('back_url', $back_url);
		if(C('login_type')) {//手机验证码登陆
            $this->smarty->display('member/login_sms.html');
        }else{//密码登陆
            $this->smarty->display('member/login.html');
        }

	}

	/**
	 * 注册页
	 */
	/**
	 * 本函数在 wxapi 内被实现 已经作废
	 * @param  string $username [description]
	 * @return [type]           [description]
	 */
	function register($username = '') {
		if (empty($_SESSION['oauth']['nickname'])) {
			unset($_SESSION['oauth']);
		}

		if ($username) {
			//zzcookie('ivt_member', stripcslashes(trim($username)));
			$_SESSION['ivt_member'] = stripcslashes(trim($username));
		}

		$this->smarty->assign('backUrl', $_SERVER['HTTP_REFERER']);
		$this->smarty->assign('ivt_member', $_COOKIE['ivt_member'] ? $_COOKIE['ivt_member'] : $username);

		//微信推广链接处理（直接注册并登陆，绑定推荐人）
		if (IS_WECHAT && isset($_SESSION['oauth']) && !empty($_SESSION['oauth']) && !$_SESSION['mid'] && $username && $_SESSION['oauth']['openid']) {
			$info             = $_SESSION['oauth'];
			$info['username'] = $info['nickname'];
			$this->load->model('member');

			//用户名重复时更换用户名
			$row = $this->member->check_username($info['nickname']);
			if ($row) {
				$info['username'] .= '_' . uniqid();
			}

			//微信绑定过时，直接登录
			$member = array();
			if ($info['unionid']) {
				$member = $this->member->check_username($info['unionid'], 'unionid');
			}
			if (!$member && $info['openid']) {
				$member = $this->member->check_username($info['openid'], 'openid');
			}

			if (!$member) {
				$input = array(
					'username'  => $info['username'],
					'nickname'  => $info['nickname'],
					'photo'     => $info['headimgurl'],
					'openid'    => $info['openid'],
					'unionid'   => $info['unionid'],
					'c_time'    => RUN_TIME,
					'lastlogin' => RUN_TIME,
				);
				if ($username) {
					$r                  = $this->db->get("SELECT `mid`,ivt_level FROM `###_member` WHERE `mid` = '" . trim($username) . "'");
					$input['ivt_id']    = $r ? $r['mid'] : 0;
					$input['ivt_level'] = $r ? ($r['ivt_level'] + 1) : 0;

					#推荐人统计人数
					if ($r['mid']) {
						$this->db->update('member', "ivt_count=ivt_count+1", array('mid' => $r['mid']));
					}

				}
				$id     = $this->db->insert('member', $input);
				$member = $this->db->get("SELECT * FROM ###_member WHERE mid='" . $id . "'");
			}
			$this->member->setLogin($member);
			header('Location:/');
			die;
		}

		$this->smarty->display('member/register.html');
	}

	/*
	* 注册程序
	*/
	function submit() {
		$this->load->model('member');
		$username  = empty($_POST['username']) ? '' : trim($_POST['username']);
		$password  = empty($_POST['password']) ? '' : trim($_POST['password']);
		$password2 = empty($_POST['confirm_password']) ? '' : trim($_POST['confirm_password']);
		$mobile    = isset($_POST['mobile']) ? trim($_POST['mobile']) : '';
		$agree     = isset($_POST['agree']) ? trim($_POST['agree']) : '';
        if(empty($username))$username = $mobile;
		//第三方登录
		if (!empty($_SESSION['oauth'])) {
			//$username = $_SESSION['oauth']['nickname'];
			$password = $password2 = $_SESSION['oauth']['oauth_login'] + mt_rand(10 - 999);
			if (empty($username)) {
				exit($this->msg("请填写用户名称"));
			}
		} else {
			if (!$username) {exit($this->msg("请输入用户名，长度3~40个字符"));}
			if (!$password) {exit($this->msg("请输入您的密码，长度3~40个字符"));}
			//if ($password != $password2) {exit($this->msg("两次输入的密码不一致"));}
			if (!$mobile) {exit($this->msg("请输入手机号码"));}
			if (!$agree) {exit($this->msg("请阅读并同意" . $this->site_config['site_name'] . "注册协议"));}

			//手机号唯一
			$r = $this->db->get("SELECT username FROM ###_member WHERE mobile='$mobile'");
			if ($r) {exit($this->msg("手机号已经存在，请更换"));}

            if (C('sms_open')==0) {
                $scode = isset($_POST['scode']) ? trim($_POST['scode']) : '';
                if (!$scode) {
                    exit($this->msg("请输入验证码计算答案"));
                }
                if ($scode != $_SESSION['scode']) {
                    exit($this->msg("对不起,答案错误"));
                }
			}
		}

		//用户名唯一
		$r = $this->db->get("SELECT username FROM ###_member WHERE username='$username'");
		if ($r) {exit($this->msg("用户名已经存在，请更换"));}

		//同IP限制*时间内不能重复注册
		$r = $this->db->get("SELECT mid FROM ###_member WHERE ip='" . getIP() . "' AND c_time>'" . strtotime("-5 minute") . "'");
		if ($r) {exit($this->msg("您注册太快了，请稍候来"));}

		//注册短信验证码
		$verifycode = '';
		if ($this->site_config['sms_open'] == 1 && statusTpl('sms_register')) {
			$this->load->library('sms');
			$verifycode = empty($_POST['sms_code']) ? '' : trim($_POST['sms_code']);

			/* 验证手机号验证码和IP */
			$sql  = "SELECT id FROM ###_verify_code WHERE mobile='$mobile' AND verifycode='$verifycode' AND dateline>(" . time() . "-3600)"; //验证码60分钟内有效
			$temp = $this->db->get($sql);
			if (!$temp) {
				exit($this->msg("手机号码和验证码不匹配或者验证码已过期（1小时内有效）"));
			}
		}

		$input['username']       = $username;
		$input['password']       = $password;
		$input['mobile']         = $mobile;
		$input['oauth_login']    = !empty($_SESSION['wxoauth']) ? $_SESSION['wxoauth']['oauth_login'] : '';
		//$input['nickname']       = !empty($_SESSION['wxoauth']) ? $_SESSION['wxoauth']['nickname'] : '';
		$input['photo']          = !empty($_SESSION['wxoauth']) ? $_SESSION['wxoauth']['avatar'] : '';
		$input['openid']         = !empty($_SESSION['wxoauth']) ? $_SESSION['wxoauth']['open_id'] : '';
		$input['unionid']        = !empty($_SESSION['wxoauth']) ? $_SESSION['wxoauth']['unionid'] : '';
		$input['subscribe_time'] = !empty($_SESSION['wxoauth']['subscribe_time']) ? $_SESSION['wxoauth']['subscribe_time'] : 0;
		$input['sex']            = 1;
		$input['birthday']       = '0000-00-00';
		if (!empty($_SESSION['voiceVerify'])) {
			$input['is_voice'] = 1;
		}

		//$ivt_member = cookie('ivt_member');
		$ivt_member = $_SESSION['ivt_member'];
		if ($ivt_member) {
			$r                  = $this->db->get("SELECT `mid`,ivt_level FROM `###_member` WHERE `mid` = '" . trim($ivt_member) . "'");
			$input['ivt_id']    = $r ? $r['mid'] : 0;
			$input['ivt_level'] = $r ? ($r['ivt_level'] + 1) : 0;

			#推荐人统计人数
			if ($r['mid']) {
				$this->db->update('member', "ivt_count=ivt_count+1", array('mid' => $r['mid']));
			}

		}
		$input['pay_password'] = substr($mobile, -6);

		$res = $this->member->create_user($input);
		if ($res) {
			//清空第三方登录
			if (isset($_SESSION['wxoauth'])) {
                $oauth = $_SESSION['wxoauth'];
                $data_oauth['mid'] = $res['mid'];
                $data_oauth['type'] = 0;
                $data_oauth['openid'] = $oauth['open_id'];
                $data_oauth['create_time'] = RUN_TIME;
                $this->db->insert('oauth', $data_oauth);
                if($oauth['unionid']){
                    $data_oauth['mid'] = $res['mid'];
                    $data_oauth['type'] = 3;//微信unionid
                    $data_oauth['openid'] = $oauth['unionid'];
                    $data_oauth['create_time'] = RUN_TIME;
                    $this->db->insert('oauth', $data_oauth);
                }
				unset($_SESSION['wxoauth']);
			}

			$this->member->setLogin($res);

			#修改验证码状态
			if ($this->site_config['sms_open'] == 1 && statusTpl('sms_register')) {
				$this->db->update('verify_code', array(
					'reguid'      => $_SESSION['mid'],
					'regdateline' => time(),
					'status'      => 2,
				), "`mobile`='$mobile' AND `verifycode`='$verifycode' AND `getip`='" . getIP() . "' AND `status`='1' AND `dateline`>" . (time() - 3600));
			}
			$backUrl = '/member';
			if (strpos($_REQUEST['backUrl'], '/login') === false) {
				$backUrl = $_REQUEST['backUrl'];
			}
			exit($this->msg("注册成功", array('icon' => 1, 'url' => $backUrl)));
		} else {
			exit($this->msg("注册失败（" . $res['message'] . '|' . $res['code'] . "）！"));
		}
	}

	function index() {
		$data = array();

        if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
            $data['agent'] = 'ios';
        }else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
            $data['agent'] = 'an';
        }

		if (STPL == 'mobile') {
			$row['head'] = '会员中心';
			$this->smarty->assign('row', $row);
		}

        // 登录时将所有的未读消息写入到引用表 这样不活跃的用户就没有消息引用 减少数据压力
        $this->load->model('message');
        $this->message->getUnRead(MID);

		#用户中心下方按钮列表 显示剩余剩余积分
		/*$this->load->model('score');
		$data['score'] = $this->score->getTotal(MID);*/

		#用户中心显示未读消息
		$data['msgUnreadCount'] = $this->db->getstr('SELECT count(*) as count FROM `###_message_status` WHERE status=1 AND mid = ' . MID);

        #用户中心显示今日佣金
		/*$this->load->model('commission');
		$data['commission_today'] = $this->commission->getComssTotal(MID);*/

		#用户中心显示代付款数量
		$this->load->model('order');
		$where = $this->order->order_status(100, '', 1);
		$data['dfkCount']  = $this->db->getstr("SELECT count(*) FROM ###_goods_order WHERE " . ($where ? $where : '1') . " AND mid='" . MID . "' ORDER BY id DESC");
        #用户中心显示待发货数量
        $where = $this->order->order_status(101, '', 1);
        $data['dfhCount']  = $this->db->getstr("SELECT count(*) FROM ###_goods_order WHERE " . ($where ? $where : '1') . " AND mid='" . MID . "' ORDER BY id DESC");
        #用户中心显示待收货数量
        $where = $this->order->order_status(102, '', 1);
        $data['dshCount']  = $this->db->getstr("SELECT count(*) FROM ###_goods_order WHERE " . ($where ? $where : '1') . " AND mid='" . MID . "' ORDER BY id DESC");
        #用户中心显示待评价数量
        $where = $this->order->order_status(110, '', 1);
        $data['dpjCount']  = $this->db->getstr("SELECT count(*) FROM ###_goods_order WHERE " . ($where ? $where : '1') . " AND mid='" . MID . "' AND is_rate=0 ORDER BY id DESC");
        #用户中心显示退款/退货数量
        $data['dtkCount']  = $this->db->getstr("SELECT count(*) FROM ###_refund WHERE status_refund=0 AND mid='" . MID . "'");

        //计算佣金
        $start_time = strtotime(date('Y-m-01', strtotime('-1 month')));//上个月月初
        $end_time =  strtotime(date('Y-m-t', strtotime('-1 month')));//上个月月末
        $data['pre_commission'] = $this->db->getstr("select sum(commission) as pre_commission from ###_commission where status=0 and addtime>=$start_time and addtime<=$end_time and mid=".MID,"pre_commission");
        $now_time = strtotime(date('Y-m-01', RUN_TIME));//本月月初
        $data['now_commission'] = $this->db->getstr("select sum(commission) as now_commission from ###_commission where status=0 and addtime>=$now_time and mid=".MID,"now_commission");

		$this->smarty->assign('data', $data);
		$this->smarty->assign('nav', 'index');
		
		//积分兑换开关
		$this->load->model('exchange');
		$this->smarty->assign('open_exchange',$this->exchange->power);
		//积分开关
		$this->load->model('score');
		$this->smarty->assign('open_score',$this->score->power);
		

		//大转盘开关
		$this->load->model('wheel');
		$this->smarty->assign('open_wheel',$this->wheel->power);

		//签到权限
		$rule_1 = $this->score->getRow(1);
		$this->smarty->assign('rule_1',$rule_1);

		//助力成功
        $this->load->model('assist');
        $assist_id = cookie("assist_id");
        if($this->assist->power==1 && !empty($assist_id)) {//判断是否开启
            $assist = $this->assist->getLogOne($assist_id);
            $log = $this->assist->getHelpLogOne($assist_id,MID);
            $this->smarty->assign('assist_username',$assist['username']);
            $this->smarty->assign('log',$log);
            zzcookie('assist_id',null);
        }

		
		$this->smarty->display('member/index.html');
	}

	/**
	 * 修改密码
	 */
	function chpass() {
		if (isset($_POST['Submit'])) {
			$oldPass = trim($_POST['oldpass']);
			$pass1   = trim($_POST['pass1']);
			$pass2   = trim($_POST['pass2']);
			if ($pass1 != $pass2) {
				exit($this->msg('两次密码不一致'));
			}

			$mid = $this->member->alter_pass($oldPass, $pass2, MID);

			if ($mid == -1) {
				exit($this->msg('用户不存在'));
			} elseif ($mid == -2) {
				exit($this->msg('原密码错误'));
			} else {
				$this->member->logout();
				exit($this->msg('密码重置成功，请重新登陆', array('icon' => 1, 'url' => url('/member/login'))));
			}
		} else {
			$this->smarty->display('member/chpass.html');
		}
	}

	/**
	 *  修改支付密码
	 */
	function chpaypass() {
		if (isset($_POST['Submit'])) {
			$oldPass = $_POST['oldpass'];
			$pass1   = $_POST['pass1'];
			$pass2   = $_POST['pass2'];
			if ($pass1 != $pass2) {
				exit(exit($this->msg('两次密码不一致')));
			}

			//短信验证码
			if ($this->site_config['sms_open'] == 1 && statusTpl('sms_chpaypass')) {
				$this->load->library('sms');
				$verifycode = empty($_POST['sms_code']) ? '' : trim($_POST['sms_code']);
				$mobile     = $this->memberinfo['mobile'];
				/* 验证手机号验证码和IP */
				$sql = "SELECT COUNT(id) FROM ###_verify_code WHERE mobile='$mobile' AND getip='" . getIP() . "' AND verifycode='$verifycode' AND status=4 AND dateline>'" . time() . "'-3600"; //验证码60分钟内有效
				if ($this->db->getstr($sql) == 0) {
					exit($this->msg("手机号和验证码不匹配 或者 验证码已过期（1小时内有效）！"));
				}
			}
			$paypass = $this->member->get_salt_hash($pass2, $this->memberinfo['salt']);
			$this->db->update('member', array('pay_password' => $paypass), array('mid' => MID));

			exit($this->msg('保存成功', array('icon' => 1)));
		}

		if (STPL == 'mobile') {
			$row['head'] = '修改支付密码';
			$this->smarty->assign('row', $row);
		}
		$this->smarty->assign('nav', 'chpaypass');
		$this->smarty->display('member/chpaypass.html');
	}

	/**
	 * 账户明细
	 */
	function accountdetail($page = 1) {
		$size = $this->site_config['page_size'];

		if (STPL == 'mobile' && isAjax() == true) {
			$size = isset($_POST['amount']) ? intval($_POST['amount']) : 10;
			$last = isset($_POST['last']) ? intval($_POST['last']) : 0;
			$page = $last > 0 ? ceil($last / $size + 1) : 1;
		}

		$this->smarty->assign('nav', 'accountdetail');
		$_GET['page'] = $page;
		$this->load->model('page');
		$this->page->set_vars(array(
			'per' => $size,
			'url' => 'href="/member/accountdetail/{num}"',
		));

		#时间段
		$where = "";
		if (!empty($_GET['from_data'])) {
			$where .= " AND logtime >= '" . strtotime($_GET['from_data']) . "'";
		}

		if (!empty($_GET['to_data'])) {
			$where .= " AND logtime <= '" . strtotime($_GET['to_data']) . "'";
		}

		$list = $this->page->hashQuery("SELECT * FROM ###_account_log WHERE mid = '" . MID . "'" . $where . " ORDER BY id DESC")->result_array();
		foreach ($list as $k => $v) {
			$v['stage_title'] = $this->member->stages($v['stage']);
			$list[$k]         = $v;
		}

		if (STPL == 'mobile') {
			$row['head'] = '资金管理';
			$this->smarty->assign('row', $row);

			if (isAjax() == true) {
				$array = array();
				foreach ($list as $k => $m) {
					$this->smarty->assign('m', $m);
					$array[] = array('content' => $this->smarty->fetch('member/lbi/list_accountdetail.html'));
				}
				die(json_encode($array));
			}
		}
		$this->smarty->assign('list', $list);
		$this->smarty->assign('nav', 'account');
		$this->smarty->display('member/accountdetail.html');
	}

	/**
	 * 充值提现日志
	 */
	function accountlog($page = 1) {
		$size   = $this->site_config['page_size'];
		$typeid = $_GET['type'] ? intval($_GET['type']) : 0;
		$where  = '';
		if ($typeid) {
			$where .= " AND `type`='$typeid'";
		}

		if (STPL == 'mobile' && isAjax() == true) {
			$size = isset($_POST['amount']) ? intval($_POST['amount']) : 10;
			$last = isset($_POST['last']) ? intval($_POST['last']) : 0;
			$page = $last > 0 ? ceil($last / $size + 1) : 1;
		}

		$this->smarty->assign('nav', 'accountlog');
		$_GET['page'] = $page;
		$this->load->model('page');
		$this->page->set_vars(array(
			'per' => 8,
		));
		$list = $this->page->hashQuery("SELECT * FROM ###_member_account WHERE `mid` = '" . MID . "'$where ORDER BY id DESC")->result_array();

		#异步加载
		if (isset($_GET['load'])) {
			if ($list) {
				$content = '';
				foreach ($list as $v) {
					$this->smarty->assign('m', $v);
					$content .= $this->smarty->fetch('member/lbi/accountlog_list.html');
				}
				echo $content;die;
			}
		}
		$this->smarty->assign('list', $list);
		$this->smarty->assign('nav', 'account');
		$this->smarty->display('member/accountlog.html');
	}
	/**
	 * 取消充值/提现申请
	 */
	function account_cancel($id = '') {
		$row = $this->db->get("SELECT * FROM ###_member_account WHERE id = '$id'");
		$this->db->update('member_account', array('status' => 3), array('id' => intval($id)));
		//取消提现解冻保证金
                $row['amount'] += $row['fee'];
		if ($row['type'] == 2) {
	        if ($row['from'] == 1) {            
	            $this->db->update('member', "commission = commission + {$row['amount']} , deduct_commission = deduct_commission - {$row['amount']}", array('mid' => $row['mid']));   
	        }else{
	            $input                 = array();
                    $input['mid']          = MID;
                    $input['user_money']   = $row['amount'];
                    $input['frozen_money'] = -$row['amount'];
                    $input['desc']         = '取消账户提现,解冻保证金';
                    $this->member->accountlog('withdraw', $input);
                }			
		}                             
		$this->success('取消成功', url('/member/accountlog'));
                
	}
	/**
	 * 充值
	 */
	function recchage() {
		$this->load->model('payment');
		$payment = $this->payment->getPayment("enabled = '1' AND is_cod <> '1' AND pay_code <>'balance'");
		if (empty($payment)) {
			exit($this->msg('暂时没有可用支付方式，无法充值', array('iniframe' => false, 'url' => 'back')));
		}

		if (isset($_POST['Submit'])) {
			//exit($this->msg("充值接口调整升级中，如急需充值可联系客服 QQ:200676009 电话:400-0901-225。给您带来不便敬请谅解！",array('iniframe'=>false,'url'=>'back')));
			$pay_id = intval($_POST['pay_id']);
			$amount = floatval($_POST['amount']);
			$order  = array();

			#选择了银行时，使用易宝直连
			if (isset($_POST['bank']) && trim($_POST['bank']) != '') {
				$pay_id        = 5;
				$order['bank'] = $_POST['bank'];
			}
			#使用自定义的金额
			if ($amount <= 0) {
				$amount = floatval($_POST['other']);
			}

			if (empty($pay_id)) {
				exit($this->msg('请选择支付方式', array('iniframe' => false, 'url' => 'back')));
			}

			if (empty($amount)) {
				exit($this->msg('请输入正确的充值金额', array('iniframe' => false, 'url' => 'back')));
			}

			//验证支付方式
			$payment_info = $this->payment->payment_info($pay_id);
			if (empty($payment_info) || $payment_info['enabled'] != 1 || $payment_info['is_online'] != 1) {
				exit($this->msg('支付方式不可用,请重新选择'));
			}

			$input             = array();
			$input['mid']      = MID;
			$input['username'] = USER;
			$input['amount']   = $amount;
			$input['add_time'] = RUN_TIME;
			$input['pay_id']   = $pay_id;
			$input['pay_name'] = $payment_info['pay_name'];
			$input['pay_code'] = $payment_info['pay_code'];
			$input['type']     = 1;
			$input['status']   = 1;
			$this->member->member_account_save($input);
			$id = $this->db->insert_id();

			//取得支付信息，生成支付代码
			$payment = unserialize_config($payment_info['pay_config']);

			$order['order_sn']       = 'R' . $id;
			$order['surplus_amount'] = $amount;
			//计算支付手续费用
			$payment_info['pay_fee'] = $this->payment->pay_fee($pay_id, $amount);
			$order['order_amount']   = $amount + $payment_info['pay_fee'];
			$order['log_id']         = $this->payment->pay_log_save(array('order_id' => $id, 'order_amount' => $amount, 'order_type' => PAY_SURPLUS, 'is_paid' => PS_UNPAYED));

			/* 调用相应的支付方式文件 */
			include_once AppDir . 'includes/modules/payment/' . $payment_info['pay_code'] . '.php';
			/* 取得在线支付方式的支付按钮 */
			$pay_obj                    = new $payment_info['pay_code'];
			$payment_info['pay_button'] = $pay_obj->get_code($order, $payment);

			if (STPL == 'mobile') {
				$row['head'] = '充值';
				$this->smarty->assign('row', $row);
			}
			$this->smarty->assign('order', $order);
			$this->smarty->assign('payment_info', $payment_info);
			$this->smarty->display('member/pay.html');
			die;
		}

		$this->smarty->assign('list', $payment);
		$this->smarty->display('member/rechage.html');
	}
	/**
	 * 支付
	 * @param string $id
	 */
	function pay($id = '') {
		$rechage = $this->db->get("SELECT * FROM ###_member_account WHERE id = '$id' AND type = '1'");
		if (empty($rechage)) {
			exit($this->msg('充值信息错误', array('iniframe' => false)));
		}

		$this->load->model('payment');
		$payment_info = $this->payment->payment_info($rechage['pay_id']);

		$order                   = array();
		$order['order_sn']       = $id;
		$order['surplus_amount'] = $rechage['amount'];
		//计算支付手续费用
		$payment_info['pay_fee'] = $this->payment->pay_fee($rechage['pay_id'], $rechage['amount']);
		$order['order_amount']   = $rechage['amount'] + $payment_info['pay_fee'];
		$pay_log                 = $this->db->get("SELECT * FROM ###_pay_log WHERE order_id = '$id' AND order_type = '" . PAY_SURPLUS . "'");
		$order['log_id']         = $pay_log['log_id'];

		//取得支付信息，生成支付代码
		$payment = unserialize_config($payment_info['pay_config']);

		/* 调用相应的支付方式文件 */
		include_once AppDir . 'includes/modules/payment/' . $payment_info['pay_code'] . '.php';
		/* 取得在线支付方式的支付按钮 */
		$pay_obj                    = new $payment_info['pay_code'];
		$payment_info['pay_button'] = $pay_obj->get_code($order, $payment);

		if (STPL == 'mobile') {
			$row['head'] = '充值';
			$this->smarty->assign('row', $row);
		}

		$this->smarty->assign('order', $order);
		$this->smarty->assign('payment_info', $payment_info);
		$this->smarty->display('member/pay.html');
	}
	/**
	 * 提现
	 */
//	function withdraw() {
	//		$member = $this->memberinfo;
	//		if (empty($member['realname'])) {
	//			exit($this->msg('请先进行实名认证', array('iniframe' => false, 'url' => url('/member/verifyidcard'))));
	//		}
	//
	//		$bankcard = $this->member->bankcard(MID);
	//		if (empty($bankcard)) {
	//			exit($this->msg('请先绑定银行卡', array('iniframe' => false, 'url' => url('/member/bankcard'))));
	//		}
	//
	//		#获取提现手续费列表
	//		$feeList = $this->base->explodeChar($this->site_config['withdraw_fee']);
	//		$this->smarty->assign('feelist', $feeList);
	//
	//		if (isset($_POST['Submit'])) {
	//			$pay_id   = intval($_POST['id']);
	//			$gotime   = isset($_POST['gotime']) ? trim($_POST['gotime']) : '';
	//			$bankcard = $this->db->get("SELECT * FROM ###_member_bankcard WHERE mid = '" . MID . "' AND id = '$pay_id'" );
	//			if (empty($bankcard)) {
	//				exit($this->msg('您选择的银行卡不存在，请重新选择'));
	//			}
	//
	//			$amount = floatval($_POST['amount']);
	//			if (empty($amount)) {
	//				exit($this->msg('请输入正确的提现金额'));
	//			}
	//
	//			if ($amount > $member['user_money']) {
	//				exit($this->msg('提现金额超过了您的账户可用金额' . $member['user_money']));
	//			}
	//
	//			//提现手续费
	//			if (isset($feeList[$gotime])) {
	//				if (strpos($feeList[$gotime], '%')) {
	//					$path = str_replace('%', '', $feeList[$gotime]) / 100;
	//					$fee  = $amount * $path;
	//				} else {
	//					$fee = $feeList[$gotime];
	//				}
	//			}
	//			$input              = array();
	//			$input['gotime']    = $gotime;
	//			$input['mid']       = MID;
	//			$input['username']  = USER;
	//			$input['amount']    = $amount - $fee;
	//			$input['pay_id']    = $pay_id;
	//			$input['pay_name']  = $bankcard['bankname'];
	//			$input['type']      = 2;
	//			$input['fee']       = $fee ? $fee : 0;
	//			$input['status']    = 1;
	//			$input['user_note'] = $bankcard['bankcard'];
	//			$state              = $this->member->member_account_save($input);
	//			//冻结提现金额
	//			if ($state['code'] == 0) {
	//				$input                 = array();
	//				$input['mid']          = MID;
	//				$input['user_money']   = -$amount;
	//				$input['frozen_money'] = $amount - $fee;
	//				$input['desc']         = '账户提现,冻结保证金';
	//				$this->member->accountlog('withdraw', $input);
	//			}
	//			exit($this->msg('申请成功,我们将尽快为您处理！', array('url' => url('/member/accountlog'))));
	//		}
	//
	//		if (STPL == 'mobile') {
	//			$row['head'] = '资金管理';
	//			$this->smarty->assign('row', $row);
	//		}
	//		$this->smarty->assign('list', $bankcard);
	//		$this->smarty->assign('nav', 'account');
	//		$this->smarty->display('member/withdraw.html');
	//	}

	/**
	 * 实名认证
	 */
	function verifyidcard() {
		$verify = $this->db->get("SELECT * FROM ###_verify_idcard WHERE mid='" . MID . "' ORDER BY id DESC");
		$this->smarty->assign('verify', $verify);

		if (isset($_POST['Submit'])) {
			if (empty($_POST['realname'])) {
				exit($this->msg('请输入真实姓名'));
			}

			if (empty($_POST['card'])) {
				exit($this->msg('请输入身份证号'));
			}

			$this->load->model('upload');

			$thumb = array('thumb' => array('width' => 350, 'height' => 350));

			$_POST['idcard'] = $this->upload->save_image('idcard', 'idcard', $thumb);

			$_POST['idcard2'] = $this->upload->save_image('idcard2', 'idcard2', $thumb);

			if (empty($_POST['idcard'])) {
				exit($this->msg('请上传身份证正面照'));
			}

			if (empty($_POST['idcard2'])) {
				exit($this->msg('请上传身份证反面照'));
			}

			$realname = trim($_POST['realname']);
			$card     = trim($_POST['card']);
			//检验唯一性
            $has_realname = $this->db->getstr("SELECT COUNT(*) AS count FROM ###_member_detail WHERE realname = '$realname'");
            $has_idcard   = $this->db->getstr("SELECT COUNT(*) AS count FROM ###_member_detail WHERE idcard = '$card'");
            $has_verify   = $this->db->getstr("SELECT COUNT(*) AS count FROM ###_verify_idcard WHERE card = '$card' AND realname = '$realname' AND status = 1" );
			if (!empty($has_realname) || !empty($has_idcard) || !empty($has_verify)) {
				exit($this->msg('您的证件已验证过，请使用其他证件'));
			}

			$input                = array();
			$input['mid']         = MID;
			$input['username']    = USER;
			$input['realname']    = $realname;
			$input['card']        = $_POST['card'];
			$input['card_image']  = $_POST['idcard'];
			$input['card_image2'] = $_POST['idcard2'];
			$input['add_time']    = RUN_TIME;
			$this->member->verify_idcard_save($input);
			exit($this->msg('提交成功,我们会尽快处理您的验证。', array('icon' => 1, 'url' => url('/member/verifyidcard'))));
		}

		if (STPL == 'mobile') {
			$row['head'] = '实名认证';
			$this->smarty->assign('row', $row);
		}
		$this->smarty->display('member/verifyidcard.html');
	}

	/**
	 * 收货地址
	 */
	function address($id = '') {
		if (isset($_POST['Submit'])) {
			$input             = array();
			$input['id']       = intval($_POST['id']);
			$input['mid']      = MID;
			$input['username'] = USER;
			$input['name']     = trim($_POST['name']);
			$input['address']  = trim($_POST['address']);
			$input['zip']      = isset($_POST['zip']) ? trim($_POST['zip']) : '';
			$input['mobile']   = trim($_POST['mobile']) ? trim($_POST['mobile']) : trim($this->memberinfo['mobile']);
			$input['zone']     = !empty($_POST['zone']) ? end($_POST['zone']) : '';
			$this->load->Model('linkage');
			$input['area'] = $input['zone'] ? $this->linkage->pos_linkage($input['zone'], false) : '';
			$input['area'] = str_replace('>', '', $input['area']);
			if (empty($input['name'])) {
				exit($this->msg('请输入收件人姓名'));
			}

			if (empty($input['mobile'])) {
				exit($this->msg('请输入手机号码'));
			}

			if (empty($input['zone'])) {
				exit($this->msg('请选择配送区域'));
			}

			if (empty($input['address'])) {
				exit($this->msg('请输入详细地址'));
			}

			//清空默认
			$input['is_default'] = $_POST['is_default'] ? 1 : 0;
			if ($input['is_default']) {
				$this->db->update('member_address', array('is_default' => 0), array('mid' => MID));
			}

			$r          = $this->member->member_address_save($input);
			$address_id = $input['id'] ? $input['id'] : $r;

			$url = $_REQUEST['back'] ? (trim($_REQUEST['back']) . '?address_id=' . $address_id) : '/member/address';
			if ($_REQUEST['back']) {
				exit($this->msg('', array('url' => url($url))));
			} else {
				exit($this->msg('收货地址' . ($input['id'] ? '更新' : '添加') . '成功', array('icon' => 1, 'url' => url($url))));
			}
		}

		$this->smarty->assign('back', isset($_GET['back']) ? trim($_GET['back']) : '/member/address');
		if ($id) {
			$row = array();
			if ($id == 'add') {
				$row['zone'] = 1;
			} else {
				$row = $this->db->get("SELECT * FROM ###_member_address WHERE id = '$id' and mid=".MID);
			}
			$this->smarty->assign('row', $row);
			$this->smarty->display('member/address_form.html');
		} else {
			$address = $this->member->member_address(MID);
			$this->smarty->assign('address', $address);
			$this->smarty->display('member/address.html');
		}
	}
	function address_del($id) {
		$url = $_GET['back'] ? trim('/member/address?back=' . $_GET['back']) : '/member/address';
		$res = $this->db->delete('member_address', array('id' => intval($id),"mid"=>MID));
                if($res===false){
                    exit($this->msg('收货地址删除成功', array('icon' => 1, 'iniframe' => false, 'url' => url($url))));
                }else{
                    exit($this->msg('收货地址删除失败'));
                }		
	}
	function ajax_address_del(){
		$id = intval($_POST['id']);
		if($this->db->delete('member_address', array('id' => intval($id),"mid"=>MID))){
			$data['error'] = false;
			$data['msg'] = '收货地址删除成功';
		}else{
			$data['error'] = true;
			$data['msg'] = '收货地址删除失败';
		}
		echo json_encode($data);
	}

	/**
	 * Feng 2016-06-06
	 * 设置默认收货地址
	 */
	function address_default($id) {
		if ($id == '') {
			return false;
		}

		$this->db->update('member_address', array('is_default' => 0), array('mid' => MID));
		$this->db->update('member_address', array('is_default' => 1), array('mid' => MID, 'id' => $id));

	}
	// fan 2016-05-17 start
	/**
	 * checkout 异步删除收货地址操作
	 * @param  integer $id 收货地址整数 id
	 * @return json
	 */
	public function address_ajax_del($id = 0) {
		$this->db->delete('member_address', array('id' => intval($id)));
		$res = array('error' => 0, 'msg' => '删除成功');
		exit(json_encode($res));
	}
	/**
	 * checkout页面异步编辑收货地址
	 * @param  mixed  $id  收货地址整数 id 或者 字符串add 表示新增
	 * @return json
	 */
	public function address_ajax_edit($id = '') {
		$checked = empty($_REQUEST['checked']) ? 0 : $_REQUEST['checked'];
		$this->smarty->assign('checked', $checked);
		if (!empty($_POST)) {
			$res               = array('error' => 1);
			$input             = array();
			$input['id']       = intval($_POST['id']);
			$input['mid']      = MID;
			$input['username'] = USER;
			$input['name']     = trim($_POST['name']);
			$input['address']  = trim($_POST['address']);
			$input['zip']      = isset($_POST['zip']) ? trim($_POST['zip']) : '';
			$input['mobile']   = trim($_POST['mobile']) ? trim($_POST['mobile']) : trim($this->memberinfo['mobile']);
			$input['zone']     = !empty($_POST['zone']) ? end($_POST['zone']) : '';
			$this->load->Model('linkage');
			$input['area'] = $input['zone'] ? $this->linkage->pos_linkage($input['zone'], false) : '';
			$input['area'] = str_replace('>', '', $input['area']);
			if (empty($input['name'])) {
				$res['msg'] = '请输入收件人姓名';
				exit(json_encode($res));
			}
			if (empty($input['mobile'])) {
				$res['msg'] = '请输入手机号码';
				exit(json_encode($res));
			}
			if (empty($input['zone'])) {
				$res['msg'] = '请选择配送区域';
				exit(json_encode($res));
			}
			if (empty($input['address'])) {
				$res['msg'] = '请输入详细地址';
				exit(json_encode($res));
			}
			//清空默认
			$input['is_default'] = empty($_POST['is_default']) ? 0 : 1;
			if ($input['is_default']) {
				$this->db->update('member_address', array('is_default' => 0), array('mid' => MID));
			}
			$input['id']  = $this->member->member_address_save($input);
			$res['error'] = 0;
			$res['msg']   = '保存成功';
			$res['data']  = $input;
			$this->smarty->assign('addr', $input);
			//收货地址
			$address     = $this->member->member_address(MID, 1);
			$res['html'] = '';
			foreach ($address as $k => $v) {
				$this->smarty->assign('addr', $v);
				$res['html'] .= $this->smarty->fetch('member/lbi/address_list.html');
			}
			exit(json_encode($res));
		}
		$row = array();
		if ($id == 'add') {
			$row['zone'] = 1;
		} else {
			$row = $this->db->get("SELECT * FROM ###_member_address WHERE id = '$id'");
		}
		$this->smarty->assign('row', $row);
		$this->smarty->display('member/address_ajax_edit.html');
	}
	// fan 2016-05-17 end
	/**
	 * 银行卡管理
	 */
	function bankcard($id = '') {
		$member = $this->memberinfo;
		$this->smarty->assign('member', $member);
		if (empty($member['realname'])) {
			exit($this->msg('绑定银行卡前请先进行实名认证', array('iniframe' => false, 'url' => url('/member/verifyidcard'))));
		}

		if (isset($_POST['Submit'])) {
			if (empty($_POST['bankname'])) {
				exit($this->msg('请输入银行名称'));
			}

			if (empty($_POST['bankcard'])) {
				exit($this->msg('请输入银行卡号'));
			}

			if (empty($_POST['bankaddress'])) {
				exit($this->msg('请输入开户行'));
			}

			//短信验证码
			if ($this->site_config['sms_open'] == 1 && statusTpl('sms_bankcard')) {
				$this->load->library('sms');
				$verifycode = empty($_POST['sms_code']) ? '' : trim($_POST['sms_code']);

				$mobile = isset($_POST['mobile']) ? $_POST['mobile'] : $this->memberinfo['mobile'];

				/* 验证手机号验证码和IP */
				$sql = "SELECT COUNT(id) FROM ###_verify_code WHERE mobile='$mobile' AND getip='" . getIP() . "' AND verifycode='$verifycode' AND status=5 AND dateline>'" . time() . "'-3600"; //验证码60分钟内有效
				if ($this->db->getstr($sql) == 0) {
					exit($this->msg("手机号和验证码不匹配 或者 验证码已过期（1小时内有效）！"));
				}
			}

			$input                = array();
			$input['id']          = $id ? intval($id) : 0;
			$input['name']        = $member['realname'];
			$input['mid']         = MID;
			$input['username']    = USER;
			$input['bankname']    = trim($_POST['bankname']);
			$input['bankcard']    = trim($_POST['bankcard']);
			$input['bankaddress'] = trim($_POST['bankaddress']);
			$input['is_default']  = $_POST['is_default'] ? 1 : 0;
			//清空默认
			if ($_POST['is_default']) {
				$this->db->update('member_bankcard', array('is_default' => 0), array('mid' => MID));
			}
			$this->member->bankcard_save($input);

			$back_url = $_POST['back_url'] ? $_POST['back_url'] : url('/member/bankcard');
			exit($this->msg('保存成功', array('icon' => 1, 'url' => $back_url)));
		}
		if ($id) {
			$row = $this->db->get("SELECT * FROM ###_member_bankcard WHERE `id` = '" . $id );
			$this->smarty->assign('row', $row);
		}
		$back_url = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $_GET['back'];
		$this->smarty->assign('back_url', $back_url);

		if (STPL == 'mobile') {
			$row['head'] = '绑定银行卡';
			$this->smarty->assign('row', $row);
		}

		$bankcard = $this->member->bankcard(MID);
		$this->smarty->assign('bankcard', $bankcard);
		$this->smarty->display('member/bankcard.html');
	}
	function bankcard_del($id) {
		$this->db->delete('member_bankcard', array('id' => intval($id)));
		$this->success('删除成功', url('/member/bankcard'));
		//exit($this->msg('删除成功', array('iniframe' => false, 'url' => url('/member/bankcard'))));
	}

	/**
	 * 邀请好友
	 */
	function myivt() {

		//获取微信签名包
		if (IS_WECHAT) {
			$this->load->model('wxapi');
			$signPackage = $this->wxapi->getSignPackage();
			$this->smarty->assign('signPackage', $signPackage);
		}

		//提成规则
		$this->load->model('order');
		$this->smarty->assign('comss_po', $this->order->comss_po());

		//分享内容
		$comment         = array();
		$comment['text'] = '【' . $this->site_config['site_name'] . '】' . $this->site_config['ivtad'];

		// 注意这里的分享页面很关键,决定了通过微信分享注册为分销下家以后 看到的第一个页面
		// 注意后面的 inviter_id 参数需要跟wxpai内部的分享参数名称一直
		$comment['url'] = url('/member/index') . '?inviter_id=' . MID;

		$qrcode = creat_code($comment['url'], 'qr' . MID . '.png');
		$this->smarty->assign('qrcode', $qrcode);

		$comment['pic'] = url($qrcode);

		//短地址
		$dwz = http('http://dwz.cn/create.php', 'post', array('url' => $comment['url']));
		$dwz = json_decode($dwz);
		if ($dwz->tinyurl) {
			$comment['url'] = $dwz->tinyurl;
		}

		$this->smarty->assign('username', urlencode(USER));
		$this->smarty->assign('comment', $comment);
		$this->smarty->display('member/myivt.html');
	}

	/*
	 * 我的邀请
	*/
	function myivt_list($page = 1) {
        $where = " and ivt_id=" . MID;
        $this->load->model("commission");
        $_data['where'] = $where;
        $data['list']   = $this->commission->getMyivtList($_data, 10, $page);

		#异步加载
		if (isset($_GET['load'])) {
            $content = '';
            if ($data['list']) {
				foreach ($data['list'] as $v) {
					$this->smarty->assign('m', $v);
					$content .= $this->smarty->fetch('member/lbi/myivt_list.html');
				}
			}
            echo $content;die;
		}
        $this->smarty->assign('data', $data);
		$this->smarty->display('member/myivt_list.html');
	}

    /*
	 * 拼团王
	*/
    function team_sort() {
        $where = " status_common=10";
        $start = C('pin_start')>0?C('pin_start'):'';
        $end = C('pin_end')>0?C('pin_end'):'';
        if($start){
            $where .= " and c_time>=".strtotime($start);
        }
        if($end){
            $where .= " and c_time<=".strtotime($end);
        }
        $data['list'] = $this->db->select("select mid,count(1) as num from ###_goods_order where {$where} group by mid order by num desc limit 100");
        $data['list'] = $this->db->lJoin($data['list'],'member','mid,username,mobile','mid','mid');
        $data['list'] = $this->db->lJoin($data['list'],'member_detail','mid,photo,nickname','mid','mid');

        $list = array_column($data['list'],'mid');
        $list = array_flip($list);
        $data['sort'] = isset($list[MID])?($list[MID]+1):"未上榜";
        if(isset($list[MID])){
            $data['sort_num'] = $data['list'][$list[MID]]['num'];
        }else{
            $data['sort_num'] = $this->db->getstr("select count(1) as num from ###_goods_order where {$where} and mid=".MID,"num");
        }
        $this->smarty->assign('data', $data);
        $this->smarty->display('member/team_sort.html');
    }


	/**
	 * 分享码
	 */
	function sharecode() {
		#我的分享码
		$sharecode = $this->db->get("SELECT *  FROM ###_sharecode WHERE mid = '" . MID . "'");
		$this->smarty->assign('sharecode', $sharecode);
		if ($sharecode) {
			$sharecode_list = $this->db->select("SELECT o.mid,o.username,o.order_sn,o.add_time,m.nickname FROM ###_yunorder AS o LEFT JOIN ###_member AS m ON m.mid = o.mid WHERE o.used_sharecode = '$sharecode'");
			$this->smarty->assign('sharecode_list', $sharecode_list);
		}
		#我使用的分享码
		$used_sharecode = $this->db->getstr("SELECT used_sharecode FROM ###_yunorder WHERE mid = '" . MID . "' AND used_sharecode <> ''");
		$this->smarty->assign('used_sharecode', $used_sharecode);
		if ($used_sharecode) {
			$used_sharecode_list = $this->db->select("SELECT o.mid,o.username,o.order_sn,o.add_time,m.nickname FROM ###_yunorder AS o LEFT JOIN ###_member AS m ON m.mid = o.mid  WHERE o.used_sharecode = '$used_sharecode'");
			$this->smarty->assign('used_sharecode_list', $used_sharecode_list);
		}

		//分享内容
		$comment         = array();
		$comment['text'] = '#' . $this->site_config['site_name'] . '# 分享码 ' . $sharecode . '使用即可免费云购哦！.';
		$comment['url']  = url();
		$this->smarty->assign('comment', $comment);

		$this->smarty->assign('nav', 'sharecode');
		$this->smarty->display('member/sharecode.html');
	}

	/**
	 * AJAX检查注册用户名
	 */
	function check_username() {
		$r = $this->is_member($_POST['param']);
		if ($r) {
			$result = array('status' => 'n', 'info' => '用户名已存在');
		} else {
			$result = array('status' => 'y');
		}
		echo json_encode($result);
	}
	/**
	 * AJAX检查邀请人信息
	 */
	function check_ivt() {
		$r = $this->is_member($_POST['param']);
		if ($r) {
			$result = array('status' => 'y', 'info' => '邀请人' . $r['username']);
		} else {
			$result = array('status' => 'n', 'info' => '用户不存在');
		}
		echo json_encode($result);
	}
	/**
	 * AJAX检查邮箱
	 */
	function check_email() {
		$r = $this->db->get("SELECT mid,username FROM ###_member WHERE email = '" . trim($_POST['param']) . "'");
		if ($r) {
			$result = array('status' => 'n', 'info' => '邮箱已存在');
		} else {
			$result = array('status' => 'y');
		}
		echo json_encode($result);
	}
	/**
	 * AJAX检查手机号
	 */
	function check_mobile() {
		$r = $this->db->get("SELECT mid,username FROM ###_member WHERE mobile = '" . trim($_POST['param']) . "'");
		if ($r) {
			$result = array('status' => 'n', 'info' => '手机号已存在');
		} else {
			$result = array('status' => 'y');
		}
		echo json_encode($result);
	}

	function is_member($username, $mid = '') {
		if ($mid) {
			return $r = $this->db->get("SELECT mid,username FROM ###_member WHERE mid = '" . intval($mid) . "'");
		} else {
			return $r = $this->db->get("SELECT mid,username FROM ###_member WHERE username = '" . htmlspecialchars($username) . "'");
		}
	}
    /**
     * 登录程序
     */
    function act_login() {
        if(C('login_type')){
            !empty($_POST['mobile']) || exit($this->msg("请输入您的手机号"));
            !empty($_POST['sms_code']) || exit($this->msg("请输入手机验证码"));
            $mobile = $_POST['mobile'];
            $verifycode = $_POST['sms_code'];
            if (C('sms_open') == 1 && statusTpl('sms_register')) {
                $this->load->library('sms');
                /* 验证手机号验证码和IP */
                $sql  = "SELECT id FROM ###_verify_code WHERE mobile='$mobile' AND verifycode='$verifycode' AND status=1 AND dateline>(" . time() . "-3600)"; //验证码60分钟内有效
                $temp = $this->db->getstr($sql,'id');
                if (!$temp) {
                    exit($this->msg("验证码不正确或者验证码已过期（1小时内有效）"));
                }else{
                    $this->db->update("verify_code",array('id'=>$temp),array("status"=>0));
                }
            }
            $res = $this->db->get("select mid,username,status from ###_member where mobile='{$mobile}' order by mid desc limit 1");

            $this->load->model('member');
            if(empty($res)){//该手机没有记录则添加会员
                $post['mobile'] = $post['username'] = $mobile;
                $post['password'] = rand(111111,999999);
                $res = $this->member->create_user($post);
            }elseif($res['status']!=1){
                exit($this->msg("此帐户已被封号，如有疑问，请咨询网站客服人员！"));
            }
            $this->member->setLogin($res);
            $back_url = $_POST['back_url'] ? $_POST['back_url'] : '/member';
            exit($this->msg('', array('url' => $back_url)));
        }else{
            !empty($_POST['username']) || exit($this->msg("请输入您的手机号"));
            !empty($_POST['password']) || exit($this->msg("请输入登录密码"));

            $username = addslashes(trim($_POST['username']));
            $password = $_POST['password'];
            $user     = $this->db->get("SELECT p1.*,p2.password,p2.salt FROM ###_member as p1 left join ###_member_detail as p2 on p1.mid=p2.mid WHERE p1.username='" . $username . "' || p1.mobile='" . $username . "'");

            $this->load->model('member');
            if (!$user || $this->member->get_salt_hash($password, $user['salt']) != $user['password']) {
                //登录失败
                exit($this->msg("用户名或密码错误"));
            } else {
                if ($user['status'] != 1) {
                    exit($this->msg("此帐户已被封号，如有疑问，请咨询网站客服人员！"));
                }
                //TODO:记住登录 1个月
                if (isset($_REQUEST['remember']) && intval($_REQUEST['remember']) == 1) {
                    zzcookie('username', $username);
                    zzcookie('password', $password);
                } else {
                    if (cookie('username')) {
                        zzcookie('username', '', -3600);
                        zzcookie('password', '', -3600);
                    }
                }

                //登录成功;
                unset($user['salt'], $user['password']);
                $this->member->setLogin($user);
                //处理微信授权
                if (!empty($_SESSION['wxoauth'])) {
                    $oauth = $_SESSION['wxoauth'];
                    $data_oauth['mid'] = $user['mid'];
                    $data_oauth['type'] = 0;
                    $data_oauth['openid'] = $oauth['open_id'];
                    $data_oauth['create_time'] = RUN_TIME;
                    $this->db->insert('oauth', $data_oauth);
                    if($oauth['unionid']){
                        $data_oauth['mid'] = $user['mid'];
                        $data_oauth['type'] = 3;//微信unionid
                        $data_oauth['openid'] = $oauth['unionid'];
                        $data_oauth['create_time'] = RUN_TIME;
                        $this->db->insert('oauth', $data_oauth);
                    }
                    //清空授权
                    unset($_SESSION['oauth']);
                }
                $back_url = $_POST['back_url'] ? $_POST['back_url'] : '/member';
                exit($this->msg('', array('url' => $back_url)));
            }
        }

    }
	/**
	 * 登录程序
	 */
	function act_login_bak() {
		!empty($_POST['username']) || exit($this->msg("请输入您的手机号"));
		!empty($_POST['password']) || exit($this->msg("请输入登录密码"));

		$username = addslashes(trim($_POST['username']));
		$password = $_POST['password'];
		$user     = $this->db->get("SELECT p1.*,p2.password,p2.salt FROM ###_member as p1 left join ###_member_detail as p2 on p1.mid=p2.mid WHERE p1.username='" . $username . "' || p1.mobile='" . $username . "'");

		$this->load->model('member');
		if (!$user || $this->member->get_salt_hash($password, $user['salt']) != $user['password']) {
			//登录失败
			exit($this->msg("用户名或密码错误"));
		} else {
			if ($user['status'] != 1) {
				exit($this->msg("此帐户已被封号，如有疑问，请咨询网站客服人员！"));
			}
			//TODO:记住登录 1个月
			if (isset($_REQUEST['remember']) && intval($_REQUEST['remember']) == 1) {
				zzcookie('username', $username);
				zzcookie('password', $password);
			} else {
				if (cookie('username')) {
					zzcookie('username', '', -3600);
					zzcookie('password', '', -3600);
				}
			}

			//登录成功;
			unset($user['salt'], $user['password']);
			$this->member->setLogin($user);
			//处理微信授权
			if (!empty($_SESSION['wxoauth'])) {
                $oauth = $_SESSION['wxoauth'];
                $data_oauth['mid'] = $user['mid'];
                $data_oauth['type'] = 0;
                $data_oauth['openid'] = $oauth['open_id'];
                $data_oauth['create_time'] = RUN_TIME;
                $this->db->insert('oauth', $data_oauth);
                if($oauth['unionid']){
                    $data_oauth['mid'] = $user['mid'];
                    $data_oauth['type'] = 3;//微信unionid
                    $data_oauth['openid'] = $oauth['unionid'];
                    $data_oauth['create_time'] = RUN_TIME;
                    $this->db->insert('oauth', $data_oauth);
                }
				//清空授权
				unset($_SESSION['oauth']);
			}
			$back_url = $_POST['back_url'] ? $_POST['back_url'] : '/member';
			exit($this->msg('', array('url' => $back_url)));
		}
	}

	function doexit() {
		$this->member->logout();
		header('Location:/');
	}

	/**
	 * 第三方登录
	 */
	function oauth($type = "") {
		$type = $type ? trim($type) : '';
		if (!in_array($type, array('qq', 'weibo')) || $this->site_config[$type . '_login'] == 0) {
			$this->msg();
		}

		include_once AppDir . 'includes/oauth/jntoo.php';

		$c = &website($type);

		if ($c) {
			if (empty($_REQUEST['callblock'])) {
				if (empty($_REQUEST['callblock']) && isset($GLOBALS['_SERVER']['HTTP_REFERER'])) {
					$back_act = strpos($GLOBALS['_SERVER']['HTTP_REFERER'], 'user.php') ? 'index.php' : $GLOBALS['_SERVER']['HTTP_REFERER'];
				} else {
					$back_act = 'index.php';
				}
			} else {
				$back_act = trim($_REQUEST['callblock']);
			}

			if ($back_act[4] != ':') {
				$back_act = url() . $back_act;
			}

			$open = empty($_REQUEST['open']) ? 0 : intval($_REQUEST['open']);
			$url  = $c->login(url() . 'member/oauth_login/' . $type . '?callblock=' . urlencode($back_act) . '&open=' . $open);

			if (!$url) {
				$this->msg($c->get_error(), array('url' => url(), 'iniframe' => false));
			}

			header('Location: ' . $url);
		} else {
			$this->msg('该功能尚未开启！', array('url' => url(), 'iniframe' => false));
		}
	}

	/**
	 * 第三方登录处理
	 * @param $type
	 */
	function oauth_login($type) {
		$type = empty($type) ? '' : trim($type);
		include_once AppDir . 'includes/oauth/jntoo.php';
		$c = &website($type);

		if ($c) {
			$access = $c->getAccessToken();
			if (!$access) {
				$this->msg('授权错误，请重试', array('url' => url(), 'iniframe' => false));
			}
			$c->setAccessToken($access);
			$info = $c->getMessage();

			if (empty($info) || empty($info['mid'])) {
				//$this->msg($c->get_error(),array('url'=>url(),'iniframe'=>false));die;
				$this->msg('授权错误，请重试', array('url' => url(), 'iniframe' => false));die;
			}
            switch($type){
                case 'qq':
                    $type = 1;
                    break;
                case 'wb':
                    $type = 2;
                    break;
                default:
                    $this->msg('第三方登录类型只限qq、微博', array('url' => url(), 'iniframe' => false));die;
            }
            $this->load->model('member');
            $user['open_id'] = $info['mid']; //openid
            $user['nickname'] = str_replace("'", "", $info['nickname']); // 过滤掉分号
            $user['location'] = $info['city']; // 所在地
            $user['avatar'] = $info['figureurl_qq_2']; // 所在地
            $user['type'] = $type;
            $mid = $this->member->oauth_user($user);

            $res = $this->db->get("select mid,username,status from ###_member where mid='{$mid}' order by mid desc limit 1");

            if($res['status']!=1){
                exit($this->msg("此帐户已被封号，如有疑问，请咨询网站客服人员！"));
            }
            $this->member->setLogin($res);

			if (!empty($_REQUEST['open'])) {
				$this->msg('', url('/member/register'));
				//$this->exeJs('window.close();');
			} else {
				header('Location: ' . $_REQUEST['callblock']);
			}
		}
	}

	function message($page = 1) {

        // 登录时将所有的未读消息写入到引用表 这样不活跃的用户就没有消息引用 减少数据压力
        $this->load->model('message');
        $this->message->getUnRead(MID);

		$id           = intval($_GET['id']);
		$type         = isset($_GET['type']) ? intval($_GET['type']) : 0;
		$data['type'] = $type;
		if ($id) {
			$data['row'] = $this->db->get("SELECT * FROM ###_msg WHERE (tomid='" . MID . "' or tomid=0) AND id=" . $id);
			//标记已读
			if ($data['row']) {
				$this->load->model('msg');
				$this->msg->markRead($id, MID);
			} else {
				$this->msg();exit;
			}

			$this->smarty->assign('data', $data);
			$this->smarty->display('member/message_show.html');
			exit;
		}

		$_GET['page'] = $page;
		$pagesize     = 10;
		$this->load->model('page');
		$this->page->set_vars(array('per' => $pagesize));

		$where = "(tomid='" . MID . "' or tomid=0)";
		//未读
		if ($type == 1) {
			$msg_ids = $this->db->getstr("SELECT msg_ids FROM ###_member_msg WHERE mid='" . MID . "'");
			if (!empty($msg_ids)) {
				$where .= " AND id NOT IN($msg_ids)";
			}
		}
		$data['list'] = $this->page->hashQuery("SELECT * FROM msg WHERE " . $where . " ORDER BY id DESC")->result_array();

		#异步加载
		if (isset($_GET['load'])) {
			if ($data['list']) {
				$content = '';
				foreach ($data['list'] as $v) {
					$this->smarty->assign('m', $v);
					$content .= $this->smarty->fetch('member/lbi/message_list.html');
				}
				echo $content;die;
			}
		}

		$this->smarty->assign('data', $data);
		$this->smarty->display('member/message.html');
	}

	/*微信登录*/
	function oauth_chose() {
		if (empty($_SESSION['oauth'])) {
			$this->msg('授权出错请重试', array('url' => url(), 'iniframe' => false));exit;
		}
		if (STPL == 'mobile') {
			$row['head'] = '会员授权';
			$this->smarty->assign('row', $row);
		}
		$this->smarty->display('member/oauth_chose.html');
	}

	/**
	 * 意见反馈
	 * Feng
	 * 2016-04-27
	 */
	function opinion() {
		$memberinfo = $this->memberinfo;
		if (isset($_POST['Submit'])) {
			$input             = array();
			$input['mid']      = MID;
			$input['username'] = USER;
			$input['mobile']   = trim($_POST['mobile']);
			$input['email']    = trim($_POST['email']);
			$input['content']  = trim($_POST['content']);
			$input['c_time']   = RUN_TIME;
			if (empty($input['mobile'])) {
				exit($this->msg('请输入手机号码'));
			}
			if (empty($input['email'])) {
				exit($this->msg('请输入邮箱'));
			}
			if (empty($input['content'])) {
				exit($this->msg('请输入反馈内容'));
			}
			$res = $this->db->insert('member_opinion', $input);
			if ($res) {
				exit($this->msg('提交成功', array('icon' => 1, 'url' => url('/member/index'))));
			} else {
				exit($this->msg('提交有误', array('icon' => 2)));
			}
		}
		$this->smarty->assign('member', $memberinfo);
		$this->smarty->display('member/opinion.html');
	}

	/**
	 * Feng 2016-05-10
	 * 发货提醒
	 */
	function ship_remind($order_sn) {
		if ($order_sn == false) {
			echo json_encode(array("error" => 1, "msg" => "订单有误！"));exit;
		}
		$this->db->update("goods_order", array("ship_remind" => 1, "ship_time" => RUN_TIME), array("order_sn" => $order_sn));
		// 模版消息 24 买家发货提醒 {插入订单号}
		// template_msg_action start
		$this->load->model('template_msg');
		$msgParams = array($order_sn);
		$this->template_msg->inQueue(24, 0, $msgParams);
		// template_msg_action end
		echo json_encode(array("error" => 0, "msg" => "已提醒，请等待卖家发货"));exit;
	}
	/**
	 * Feng 2016-05-10
	 * 评价
	 */
	function order_rate($order_sn = '') {
		if (isset($_POST['Submit'])) {
			$post = $_POST['post'];
			foreach ($post['good_id'] as $k => $v) {
				$inter_array['good_id']  = $v;
				$inter_array['goods_spec']  = $post['goods_spec'][$k];
				$inter_array['star']     = $post['star'][$k];
				$inter_array['content']  = $post['content'][$k];
				$inter_array['order_id'] = $post['order_id'];
				$inter_array['buy_num']  = $post['buy_num'][$k];
				$inter_array['state']    = 0;
				$inter_array['c_time']   = RUN_TIME;
				$inter_array['mid']      = $post['mid'];
				$inter_array['sid']      = $post['sid'][$k];
				$this->db->insert("goods_rate", $inter_array);
			}
			$this->db->update("goods_order", array("is_rate" => 1), array("id" => $post['order_id']));
			exit($this->msg('评价成功', array('icon' => 1, 'url' => url('/member/order'))));
		} elseif ($order_sn) {
			$order = $this->db->get("SELECT * FROM `###_goods_order` WHERE status_order=10 and order_sn=" . $order_sn);
			if ($order == false) {die($this->exeJs("alert('订单有误');location.href='/member/order'"));}
			if ($order['is_rate'] == 1) {die($this->exeJs("alert('订单有误');location.href='/member/order'"));}
			$this->load->model('share');
			$orders = array($order);
			$this->load->model('order');
			$orders = $this->order->unionOrderGoods($orders);

			foreach ($orders as $k => $v) {
				if (!isset($orders[$k]['goods'])) {
					$list[$k]['goods'] = array();
				}
			}
			$order = $orders[0];

			$this->smarty->assign('order', $order);
			$this->smarty->display('member/order_rate.html');
			die;
		}
	}

	
	/**
	 * Feng 2016-06-24
	 * 退货申请
	 */
	function mobile() {
		if (isset($_POST['Submit'])) {
			$mobile = isset($_POST['mobile']) ? trim($_POST['mobile']) : '';
			#手机号不能为空
			if (empty($mobile)) {
				exit($this->msg("手机号不能为空"));
			}

			#手机号唯一
			$r = $this->db->get("SELECT username FROM ###_member WHERE mobile='$mobile'");
			if ($r) {
				exit($this->msg("手机号已经存在，请更换"));
			}

			//注册短信验证码
			$verifycode = '';
			if ($this->site_config['sms_open'] == 1 && statusTpl('sms_register')) {
				$this->load->library('sms');
				$verifycode = empty($_POST['sms_code']) ? '' : trim($_POST['sms_code']);

				/* 验证手机号验证码和IP */
				$sql  = "SELECT id FROM ###_verify_code WHERE mobile='$mobile' AND verifycode='$verifycode'  AND dateline>(" . time() . "-3600)"; //验证码60分钟内有效
				$temp = $this->db->get($sql);
				if (!$temp) {
					exit($this->msg("手机号码和验证码不匹配或者验证码已过期（1小时内有效）"));
				}
			}
			$user           = $_SESSION['wxoauth'];
			$user['mobile'] = $mobile;
			$this->load->model("member");
			$mid = $this->member->oauth_user($user);
			if ($mid > 0) {
				unset($_SESSION['wxoauth']);
				$member = $this->member->member_info($oauth['mid']);
				$this->member->setLogin($member);
				exit($this->msg('', array('url' => '/member')));
			} else {
				exit($this->msg("对不起,获取用户授权失败."));
			}

		}
		$this->smarty->display('member/mobile.html');

	}


	/*
    *佣金明细
	*/
	function comms_list($page = 1) {

		$where = " and mid=" . MID;
		$this->load->model("commission");
		$_data['where'] = $where;
		$data['list']   = $this->commission->getCommission($_data, 10, $page);

		#异步加载
		if (isset($_GET['load'])) {
            $content = '';
			if ($data['list']) {
				foreach ($data['list'] as $v) {
					$this->smarty->assign('m', $v);
					$content .= $this->smarty->fetch('member/lbi/commission_list.html');
				}
			}
            echo $content;die;
		}
		/*$sql = "SELECT sum(commission) as num FROM ###_commission where 1 " . $where;
		$res = $this->db->get($sql);
		$this->smarty->assign('total', $res['num']);*/
		$this->smarty->assign('data', $data);
		$this->smarty->display("member/commission_list.html");
	}
	/**
	 * 佣金提现
	 */
	function withdraw_commission() {

		if ($_POST['Submit']) {
            $post = $_POST['post'];
            //令牌检查
            if (!checkToken()){echo json_encode(array('error'=>10000,'msg'=>"请不要重复提交",'token'=>createToken()));exit;}
            $this->load->model('commission');
            $res = $this->commission->withdraw_commission($post);
            $res['token'] = createToken();
            echo json_encode($res);exit;
			//exit($this->msg('申请成功', array('icon' => 1, 'url' => url('/member/withdraw_list'))));
		}
        $openid = $this->db->getstr("select openid from ###_oauth where type=0 and status=1 and mid=".MID,'openid');
        $this->smarty->assign('openid', $openid);
		$this->smarty->display('member/withdraw_commission.html');
	}
    /**
     * 佣金提现记录
     */
    function withdraw_commission_log($page=1) {
        $where = " and mid=" . MID;
        $this->load->model("commission");
        $_data['where'] = $where;
        $data['list']   = $this->commission->getWithdrawCommission($_data, 10, $page);

        #异步加载
        if (isset($_GET['load'])) {
            $content = '';
            if ($data['list']) {
                foreach ($data['list'] as $v) {
                    $this->smarty->assign('m', $v);
                    $content .= $this->smarty->fetch('member/lbi/withdraw_commission_log.html');
                }
            }
            echo $content;die;
        }
        #累计提现金额
        $data['total'] = $this->db->getstr("select sum(commission) as num from ###_withdraw_commission where status=1 and mid=".MID,"num");

        $this->smarty->assign('data', $data);
        $this->smarty->display("member/withdraw_commission_log.html");
    }
	/**
	 * Feng 2016-06-30
	 * 提现
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
			$bankcard = $this->db->get("SELECT * FROM ###_member_bankcard WHERE mid = '" . MID . "' AND id = '$pay_id' ");
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
		$this->smarty->display('member/withdraw.html');

	}
	
        
        /**我的团**/
	function team($page = 1) {
		$this->load->model('order');
		$this->load->model('team');
		$data  = array();
		//$where = ' (extension_code!='.CART_LUCK.' AND extension_code!='.CART_FREE.') ';
		$where = ' 1 ';

		//订单详情页
		$order_sn = isset($_GET['order_sn']) ? $_GET['order_sn'] : 0;
		if ($order_sn) {
			$order = $this->db->get("SELECT * FROM `###_goods_order` WHERE order_sn=" . $order_sn);
			if (!isset($order['id'])) {die($this->msg('订单不存在'));}

			$this->load->model('share');
			$orders = array($order);
			$this->load->model('order');
			$orders = $this->order->unionOrderGoods($orders);

			foreach ($orders as $k => $v) {
				if (!isset($orders[$k]['goods'])) {
					$list[$k]['goods'] = array();
				}
			}
			$order = $orders[0];
                        
			if ($order['coupon_id']) {
				$this->load->model('coupon');
				$coupon                = $this->coupon->getFullCouponLog($order['coupon_id']);
				$coupon['target_name'] = $this->coupon->getTargetName($order['coupon_id']);
				$this->smarty->assign('coupon', $coupon);
			}
                        if($order['goods'][0]['goods_discount_type']>0 && $order['common_id']>0){//团长优惠                           
                            $order['discount_amount'] = $order['goods'][0]['goods_discount_type']==1?$order['goods'][0]['goods_team_price']:$order['goods'][0]['goods_discount_amount'];
                        }
                        
                        $order['refund'] = 0;
			#判断该订单是否能退货 Feng 2015-05-26 start			
			if ($refund_days && $order['status_order'] == 10 && $order['is_refund'] == 1 && $order['confirm_time'] && ($order['confirm_time'] + $refund_days * 24 * 3600) >= RUN_TIME) {
				$order['refund'] = 1;
			}
			#判断该订单是否能退货 Feng 2015-05-26 end
			$this->load->model('take_verify_code');
			$this->smarty->assign('order', $order);
			$this->smarty->display('member/order_detail.html');
			die;
		}

		//分页
		$this->load->model('page');
		$_GET['page'] = $page;
		$size         = (int) $this->site_config['page_size'];
		$this->page->set_vars(array('per' => $size));
		//订单状态
		$status         = isset($_REQUEST['status']) ? intval($_REQUEST['status']) : '';
		$data['status'] = $status;
		//if ($status) {
		//	$where .= $this->order->order_status($status, '', 1);
		//}		
               
		if($status!==''){
		   $where .= " AND status_common=".$status;
		}else{
            $where .= " AND status_common>0";
        }
                
		$sql  = "SELECT * FROM ###_goods_order WHERE " . ($where ? $where : '1') . " AND mid='" . MID . "' and common_id>0 ORDER BY id DESC";
		$list = $this->page->hashQuery($sql)->result_array();
		$list        = $this->order->unionOrderGoods($list);
		$refund_days = C("refund_days");
		foreach ($list as $k => $v) {
			if (!isset($list[$k]['goods'])) {
				$list[$k]['goods'] = array();
			}
                        $list[$k]['refund'] = 0;
			#判断该订单是否能退货 Feng 2015-05-26 start			
			if ($refund_days && $v['status_order'] == 10 && $v['is_refund'] == 1 && $v['confirm_time'] && ($v['confirm_time'] + $refund_days * 24 * 3600) >= RUN_TIME) {
				$list[$k]['refund'] = 1;
			}

			#判断该订单是否能退货 Feng 2015-05-26 end
		}
		//echo "<pre>";print_r($list);exit;
		#异步加载
		if (isset($_GET['load'])) {
			if ($list) {
				$content = '';
				foreach ($list as $v) {
					$this->smarty->assign('m', $v);
					$content .= $this->smarty->fetch('member/lbi/team_list.html');
				}
				echo $content;die;
			}echo '';die;
		}
		$data['list'] = $list;
		$this->smarty->assign('data', $data);
		$this->smarty->display('member/team.html');
	}

	/**
	 * 提交发布
	 */
	function issue(){
		$post = $_POST['post'];
		$order_id = isset($post['order_id']) ? intval($post['order_id']) : false;
		$square_desc = isset($post['square_desc']) ? trim($post['square_desc']) : false;
		if(!$order_id)
			$this->error('订单id不合法');
		if(!$square_desc)
			$this->error('请输入您想说的话');
		$this->load->model('order');
		$order = $this->order->getOrderById($order_id);
		if(!$order)
			$this->error('订单不存在');
		if($order['mid'] != MID)
			$this->error('非法操作');
		if($order['common_id'] == 0)
			$this->error('此订单不是拼团');
		if(!empty($order['square_desc']) || !empty($order['square_time']))
			$this->error('已经发布，请勿重复');
		$res = $this->db->update(
			'goods_order',
			array('square_desc' => $square_desc, 'square_time' => RUN_TIME),
			array('id'=>$order['id'], 'mid'=>MID));
		if(!$res){
			$this->error('操作失败');
		}
		$this->success('操作成功', '/member/team?status=1');
	}

	/**
	 * 异步获取发布商品信息
	 */
	function ajax_issue(){
		$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
		$this->load->model('order');
		$res = $this->order->getOrderItemCommonGoods($order_id, 'id,common_id', MID);
		if(!$res){
			$data['error'] = true;
			$data['msg'] = '获取失败';
		}else{
			$data['error'] = false;
			$data['data'] = $res;
		}
		echo json_encode($data);
	}
        
	/**
	 *查看物流
	 **/
	function order_ship($order_sn){
		$row = $this->db->get("select * from ###_goods_order where order_sn={$order_sn}");
		//$row['express_name'] = $this->db->getstr("select name from ###_express where id={$row['express']}","name");
		if($row['express']){
			$express = $this->db->get("select * from ###_express where id={$row['express']}");
			$this->smarty->assign('express', $express);
		}
		$this->smarty->assign('row', $row);
		
		//根据快递单号查询物流信息
		$this->load->library("express");
		if($express['pinyin']&&$row['express_num']){
			$wuliu = $this->express->getorder($express['pinyin'],$row['express_num']);
		}
		if(!empty($wuliu)){
			$this->smarty->assign('wuliu',$wuliu);
		}
		$this->smarty->display('member/order_ship.html');
	}

	/**
	 *删除订单
	 **/
	function order_del($order_id){
		$this->db->delete("goods_order", array("id"=>$order_id));
		$this->db->delete("goods_order_item", array("order_id"=>$order_id));
		$this->success("删除成功！");
	}

	/**
	 *未付款取消订单
	 **/
	function order_cencel($order_id){
		$this->db->update("goods_order",array("status_order"=>1), array("id"=>$order_id));

        $order = $this->db->get("select order_sn,mid,score from ###_goods_order where id={$order_id}");
        $goods_name = $this->db->getstr("select goods_name from ###_goods_order_item where order_id={$order_id}","goods_name");

		//积分退回
        if($order['score']>0){
        	$this->load->model('exchange');
        	if($this->exchange->power){
        		$this->exchange->action(array('mid'=>$order['mid'],'order_sn'=>$order['order_sn'],'score'=>$order['score'],'type'=>"1"));
        	}
        }
        // 微信模版消息 buy_succ 订单取消
        // wxtemplate_action start
        $this->load->model('wxtemplate');
        $msgParams = array(
            "url" => note_url("/member/order/?order_sn=".$order['order_sn']),
            "keyword1"=>$order['order_sn'],
            "keyword2"=>$goods_name,
        );
        $this->wxtemplate->inQueue($order['mid'],'order_cancel',$msgParams);
        // wxtemplate_action end

		$this->success("取消成功！");
	}
        
	/**
	 *我的抽奖
	 **/
	function order_lottery($page = 1){
		$this->load->model('order');
		$data  = array();
		$where = ' (extension_code='.CART_LUCK.' or extension_code='.CART_FREE.') ';
		
		//分页
		$this->load->model('page');
		$_GET['page'] = $page;
		$size         = (int) $this->site_config['page_size'];
		$this->page->set_vars(array('per' => $size));

		//订单状态
		$status         = isset($_REQUEST['status']) ? intval($_REQUEST['status']) : 0;
		$data['status'] = $status;
		if ($status) {
			$where .= $this->order->order_status($status, '', 1);
		}		
                
		$sql  = "SELECT * FROM ###_goods_order WHERE " . ($where ? $where : '1') . " AND mid='" . MID . "' ORDER BY id DESC";
		$list = $this->page->hashQuery($sql)->result_array();
        $list = $this->db->lJoin($list, 'business', 'id,kf_online,name', 'sid', 'id','s_');
		$list        = $this->order->unionOrderGoods($list);
		if($list){
		    foreach($list as $k=>$v){
                $kf_online = $v['sid']>0?$v['s_kf_online']:C('kf_online');
                $list[$k]['kf_online'] = empty($kf_online)?null:$kf_online;
            }
        }
		
		#异步加载
		if (isset($_GET['load'])) {
                        $content = '';
			if ($list) {				
                            foreach ($list as $v) {
                                $this->smarty->assign('m', $v);
                                $content .= $this->smarty->fetch('member/lbi/lottery_list.html');
                            }				
			}
                        echo $content;die;                        
		}
		$data['list'] = $list;
		$this->smarty->assign('data', $data);
		$this->smarty->display('member/order_lottery.html');
	}


    function square($page = 1){
        $this->load->model('page');
        $_GET['page'] = $page;
        $size = (int) $this->site_config['page_size'];
        $this->page->set_vars(array('per' => $size));
        $select  = "go.mid,go.common_id,go.extension_code,go.extension_id,go.square_desc,go.square_time,";//###_goods_order
        $select .= "goi.goods_name,";//###_goods_order_item
        $select .= "goc.team_num,goc.team_num_yes,";//###_goods_order_common
        $select .= "g.thumb,g.thumbs,g.price,g.team_price,";// ###_goods
        $select .= "m.username";//###_member
        $sql  = "select {$select} from ###_goods_order as go";
        $sql .= " LEFT JOIN ###_goods_order_item    AS goi  ON go.id        = goi.order_id";
        $sql .= " LEFT JOIN ###_goods_order_common  AS goc  ON go.common_id = goc.id";
        $sql .= " LEFT JOIN ###_goods               AS g    ON goi.good_id  = g.id";
        $sql .= " LEFT JOIN ###_member              AS m    ON go.mid       = m.mid";
        $where = " WHERE go.status_common = 1 AND go.common_id > 0 AND go.square_time>0 AND go.mid=".MID;

        $data['list'] = $this->page->hashQuery($sql.$where.' ORDER BY go.square_time DESC')->result_array();
        foreach($data['list'] as $k=>$v){
            $res = $this->goods->getThumb($v, 1);
            $data['list'][$k]['img_cover'] = $res['img_cover'];
        }
        if (isset($_GET['load'])) {
            $content = '';
            if ($data['list']) {
                foreach ($data['list'] as $v) {
                    $this->smarty->assign('m', $v);
                    $content .= $this->smarty->fetch('goods/lbi/square_list.html');
                }
            }
            echo $content;
            die;
        }
        $this->smarty->assign('data', $data);
        $this->smarty->assign('active', 3);
        $this->smarty->display("goods/square.html");
    }

    //评价列表
    function rate_list($page = 1){
        $this->load->model('page');
        $_GET['page'] = $page;
        $size = (int) $this->site_config['page_size'];
        $this->page->set_vars(array('per' => $size));
        $sql = "select * from ###_goods_rate where mid=".MID;
        $data['list'] = $this->page->hashQuery($sql)->result_array();
        $data['list'] = $this->db->lJoin($data['list'],"member","mid,username","mid","mid");
        $data['list'] = $this->db->lJoin($data['list'],"goods","id,name,price,team_price,thumbs","good_id","id");
        $data['list'] = $this->db->lJoin($data['list'],"goods_order_item","id,order_id,goods_spec","order_id","order_id","item_");
        if($data['list']){
            foreach($data['list'] as $key=>$val){
                $thumbs = isset($val['thumbs']) ? json_decode($val['thumbs'], true) : array();
                if ($thumbs) {
                    if ($thumbs[0]['path']) {
                        $val['img_cover'] = yunurl($thumbs[0]['path']);
                    }
                }
                $data['list'][$key] = $val;
            }
        }
        //echo "<pre>";print_r($data['list']);exit;
        if (isset($_GET['load'])) {
            $content = '';
            if ($data['list']) {
                foreach ($data['list'] as $v) {
                    $this->smarty->assign('m', $v);
                    $content .= $this->smarty->fetch('member/lbi/rate_list.html');
                }
            }
            echo $content;
            die;
        }
        $this->smarty->assign('data', $data);
        $this->smarty->display("member/rate.html");
    }

}