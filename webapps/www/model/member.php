<?php

/**
 * Class user_model
 */
class member_model extends Lowxp_Model
{
    public $baseTable = '###_member';
    public $rankTable = '###_member_rank';

    function __construct()
    {
    }

    //返回帐户明细类型
    function stages($type = '')
    {
        $stages = array(
            ACT_SAVING => array('key' => ACT_SAVING, 'title' => '帐户充值'),
            ACT_DRAWING => array('key' => ACT_DRAWING, 'title' => '帐户提款'),
            ACT_CHANGE => array('key' => ACT_CHANGE, 'title' => '兑换云购币'),
            ACT_ADJUSTING => array('key' => ACT_ADJUSTING, 'title' => '调节帐户'),
            ACT_DB => array('key' => ACT_DB, 'title' => '云购'),
            ACT_ORDER => array('key' => ACT_ORDER, 'title' => '下单'),
            ACT_OTHER => array('key' => ACT_OTHER, 'title' => '其他类型'),
            ACT_RCGCMS => array('key' => ACT_RCGCMS, 'title' => '充值佣金'),
            ACT_IVTREG => array('key' => ACT_IVTREG, 'title' => '邀请奖励'),
            ACT_ACT => array('key' => ACT_ACT, 'title' => '任务领取'),
            ACT_DOP => array('key' => ACT_DOP, 'title' => '扣除冻结款'),
            ACT_LOGIN => array('key' => ACT_LOGIN, 'title' => '每日登陆'),
			ACT_BILL => array('key' => ACT_BILL, 'title' => '商家结算'),
        );
        return $type ? $stages[$type]['title'] : $stages;
    }

    //添加会员等级
    function rank_save()
    {
        $post = $_POST['post'];

        $id = intval($_POST['id']);

        #表单处理
        if (empty($post['rank_name'])) {
            return array('code' => 10001, 'message' => '请输入会员等级名称!');
        }

        #重复处理
        $where = $id ? ' and id!=' . $id : '';

        $res = $this->db->select("select * from " . $this->rankTable . " where rank_name='" . $post['rank_name'] . "'" . $where);
        if ($res) {
            return array('code' => 10003, 'message' => '会员等级已经存在，请更换!');
        }

        #初始值
        $post['min_points'] = intval($post['min_points']);

        $post['max_points'] = intval($post['max_points']);

        #保存
        $where = $id ? array('id' => (int)$id) : '';

        $res = $this->db->save($this->rankTable, $post, '', $where);

        if (empty($res)) {
            return array('code' => 10002, 'message' => '数据操作失败!');
        } //未知错误
        if ($id) {
            admin_log('编辑会员等级：' . $post['rank_name']);
            return array('code' => 0, 'type' => 'update', 'message' => '更新成功');
        } else {
            admin_log('添加会员等级：' . $post['rank_name']);
            return array('code' => 0, 'type' => 'insert', 'message' => '添加成功');
        }

    }

    /**
     * 注册会员
     * @param $input
     * @return array|bool|DB_Result|mysqli_result
     */
    function regist($input)
    {
        $salt = substr(uniqid(rand()), -6);
        $hash_pass = $this->get_salt_hash($input['password'], $salt);
        $pay_pass = $this->get_salt_hash($input['pay_password'], $salt);
        $member_input = array(
            'rank_id' => isset($input['rank_id']) ? intval($input['rank_id']) : 0,
            'nickname' => isset($input['nickname']) ? $input['nickname'] : '',
            'realname' => isset($input['realname']) ? $input['realname'] : '',
            'sex' => isset($input['sex']) ? intval($input['sex']) : 1,
            'photo' => isset($input['photo']) ? $input['photo'] : '',
            'email' => isset($input['email']) ? trim($input['email']) : '',
            'address' => isset($input['address']) ? trim($input['address']) : '',
            'birthday' => isset($input['birthday']) ? trim($input['birthday']) : '',
            'qq' => isset($input['qq']) ? $input['qq'] : '',
            'wx' => isset($input['wx']) ? $input['wx'] : '',
            'password' => $hash_pass,
            'pay_password' => $pay_pass,
            'salt' => $salt,
            'c_time' => RUN_TIME,
            'lastlogin' => RUN_TIME,
            'ip' => getIP(),
            'lastip' => getIP(),
        );
        $member_arr = array(
            'username' => addslashes($input['username']),
            'zone' => isset($input['zone']) ? end($input['zone']) : '',
            'status' => isset($input['status']) ? intval($input['status']) : 1,
            'mobile' => isset($input['mobile']) ? trim($input['mobile']) : '',
        );
        $r = $this->db->insert($this->baseTable, $member_arr);
        if ($r) {
            $this->member_other_save($member_input, $r);
            $oauth = array(
                'mid' => $r,
                'type' => $input['type'],
                'openid' => $input['openid'],
                'create_time' => RUN_TIME,
                'status' => 1,
            );
            $res = $this->db->insert("oauth", $oauth);
            return false !== $res
                ? array(
                    'code' => '0',
                    'message' => '操作成功',
                ) : $res;
        }
        admin_log('添加会员：' . $input['username']);

        return $r
            ? array(
                'mid' => $r,
                'username' => $input['username'],
                'code' => '0',
                'message' => '添加成功',
            ) : $r;
    }

    #添加一个用户
    function create_user($input)
    {
        if (empty($input['username'])) {
            return array('code' => 10001, 'message' => '请输入用户名!');
        }

        if ($this->check_username($input['username'])) {
            return array('code' => 10001, 'message' => '用户名已存在!');
        }

        if (empty($input['mobile'])) {
            return array('code' => 10001, 'message' => '请输入您的手机号!');
        }

        if ($this->check_username($input['mobile'], 'mobile')) {
            return array('code' => 10001, 'message' => '手机号已存在!');
        }
        //入驻限制会员数量
        if(defined('MEMBER_COUNT') && in_array(Edition,array("enter_one","enter_more")) && MEMBER_COUNT>0){
            $cache_name = 'CM_MEMBER_NUM';
            if(S($cache_name)){
                $member_c = S($cache_name);
            }else{
                $member_c = $this->db->getstr("select count(1) as num from ###_member where is_robots=0","num");
            }
            if($member_c>=MEMBER_COUNT){
                return array('code' => 10001, 'message' => '会员数已达限制!');
            }
            S('CM_MEMBER_NUM',$member_c+1);
        }

        $salt = substr(uniqid(rand()), -6);

        $hash_pass = $this->get_salt_hash($input['password'], $salt);

        $pay_pass = $this->get_salt_hash($input['pay_password'], $salt);

        $member_input = array(
            'rank_id' => isset($input['rank_id']) ? intval($input['rank_id']) : 0,
            'nickname' => isset($input['nickname']) ? $input['nickname'] : '',
            'realname' => isset($input['realname']) ? $input['realname'] : '',
            'sex' => isset($input['sex']) ? intval($input['sex']) : 1,
            'photo' => isset($input['photo']) ? $input['photo'] : '',
            'email' => isset($input['email']) ? trim($input['email']) : '',
            'address' => isset($input['address']) ? trim($input['address']) : '',
            'birthday' => isset($input['birthday']) ? trim($input['birthday']) : '',
            'qq' => isset($input['qq']) ? $input['qq'] : '',
            'wx' => isset($input['wx']) ? $input['wx'] : '',
            'password' => $hash_pass,
            'pay_password' => $pay_pass,
            'salt' => $salt,
            'c_time' => RUN_TIME,
            'lastlogin' => RUN_TIME,
            'ip' => getIP(),
            'lastip' => getIP(),
        );

        $member_arr = array(
            'username' => addslashes($input['username']),
            'zone' => isset($input['zone']) ? end($input['zone']) : '',
            'status' => isset($input['status']) ? intval($input['status']) : 1,
            'mobile' => isset($input['mobile']) ? trim($input['mobile']) : '',
            'is_robots' => isset($input['is_robots']) ? intval($input['is_robots']) : 0,
        );

        if ($input['ivt_id'] > 0 && C('comss')) {

            $this->load->model('score');

            // 模版消息参数 start
            $queue = array(); // 模版消息批量容器
            $memberCount = $this->db->getStr('select count(*) as count from ###_member', 'count');

            $siteName = C('site_name');

            // 模版变量参数 等待插入其他参数
            $templateParam = array($memberCount, $siteName);

            // 模版消息7 注册成会员 {插入ID号},{插入会员个数},{插入店铺}
            $templateId = 7;
            // 模版消息参数 end

            $ivt = $this->db->get('SELECT * FROM ###_member WHERE mid =' . $input['ivt_id']);

            #推荐人统计人数
            if (!empty($ivt['mid'])) {

                // 模版8 推荐成为会员 模版变量 {插入ID号},{插入昵称},{插入会员个数},{插入店铺}
                $templateId = 8;

                // 模版消息参数插入昵称
                array_unshift($templateParam, $ivt['username']);
				if($this->score->power==1){//判断是否开启
                	$rule_3 = $this->score->getRow(3);
                	
	                // 分享送积分规则
	                $scoreRule = $this->score->getConfig(3);
	                // 模版消息 10 有人被下级推荐成会员 {插入昵称},{分销层级},{插入上级昵称},{插入会员个数},{插入店铺}
	
	                if ($ivt['ivt_id']&&$rule_3['status']==1) {
	                    // 推荐人1级上级
	
	                    $queue[] = array(10, $ivt['ivt_id'], array($user['nickname'], 3, getUsername($ivt['ivt_id']), $memberCount, $siteName));
	                    if (!empty($scoreRule['extend'][1])) {
	                        $tmp = array(
	                            'mid' => $ivt['ivt_id'],
	                            'type' => 3, // 注意这里的 ruleId 固定为3表示 分享赚积分
	                            'amount' => round($scoreRule['extend'][1]),
	                            'remark' => $user['nickname'] . ' 被邀请注册.作为推荐人1级上级获得积分',
	                            'c_time' => RUN_TIME,
	                        );
	                        $this->score->scoreLog($tmp);
	                    }
	                }
                }

                // 推荐人本人
                // 模版消息 9 有人被您推荐成会员 {插入昵称},{插入会员个数},{插入店铺}
                $queue[] = array(9, $ivt['mid'], array($user['nickname'], $memberCount, $siteName));
                if($this->score->power==1){//判断是否开启
	                if (!empty($scoreRule['extend'][0])&&$rule_3['status']==1) {
	                    $tmp = array(
	                        'mid' => $ivt['mid'],
	                        'type' => 3, // 注意这里的 ruleId 固定为3表示 分享赚积分
	                        'amount' => round($scoreRule['extend'][0]),
	                        'remark' => $user['nickname'] . ' 被邀请注册.作为直接级上级获得积分',
	                        'c_time' => RUN_TIME,
	                    );
	                    $this->score->scoreLog($tmp);
	                }
                }

                // 分享送积分
                // $this->score->inviteAction($ivt['mid'], $user['nickname'], $templateParam);

                $member_arr['ivt_id'] = $ivt['mid'];
                $member_arr['ivt_level'] = $ivt['ivt_level'] + 1;
                //Feng 添加上三级推荐人id和合伙人id 2016-06-03
                $this->db->update('member', "ivt_count=ivt_count+1", array('mid' => $ivt['mid']));
            }
            // $this->template_msg->inQueue($templateId, $mid, $templateParam);
            $this->template_msg->inQueueMany($queue);
            // template_msg_action end

        }
        $r = $this->db->insert($this->baseTable, $member_arr);
        if ($r) {
			$this->load->model('score');
        	if($this->score->power==1){//判断是否开启
	        	// 注册送积分
	        	$this->score->actionByRuleId(2, $r);
        	}
            $this->member_other_save($member_input, $r);

        	//助力
            $this->load->model('assist');
            $assist_id = cookie("assist_id");
            if($this->assist->power==1 && !empty($assist_id)) {//判断是否开启
                $this->assist->assist_help($assist_id,$r);
            }

        }
        admin_log('添加会员：' . $input['username']);

        return $r
            ? array(
                'mid' => $r,
                'username' => $input['username'],
                'code' => '0',
                'message' => '添加成功',
            ) : $r;
    }

    /**
     * 更新会员
     */
    function update_user($input)
    {
        $update_arr = array();

        if (isset($input['verify_mobile'])) {
            #更新手机状态
            $update_arr = array(
                'verify_mobile' => intval($input['verify_mobile']),
            );
        } else {
            $update_member = array(
                "status" => isset($input['status']) ? intval($input['status']) : '',
                "agent_rank" => isset($input['agent_rank']) ? intval($input['agent_rank']) : 0,
                "mobile" => isset($input['mobile']) ? trim($input['mobile']) : '',
                'ivt_id'     => isset($input['ivt_id']) ? trim($input['ivt_id']) : '',
                'subscribe_time'     => isset($input['subscribe_time']) ? strtotime($input['subscribe_time']) : '',
            );

            $update_arr = array(
                'rank_id' => isset($input['rank_id']) ? intval($input['rank_id']) : 0,
                'nickname' => isset($input['nickname']) ? trim($input['nickname']) : '',
                'sex' => isset($input['sex']) ? intval($input['sex']) : '',
                'email' => isset($input['email']) ? trim($input['email']) : '',
                'wx' => isset($input['wx']) ? trim($input['wx']) : '',
            );
            if (isset($input['mobile']) && !empty($input['mobile'])) {
                if ($this->check_username($input['mobile'], 'mobile', " AND mid!='$input[mid]'")) {
                    return array('code' => 10001, 'message' => '手机号已存在!');
                }

                $update_member['mobile'] = trim($input['mobile']);
            }
        }

        if (!empty($input['password'])) {
            $salt = $this->db->getstr("SELECT salt FROM ###_member_detail WHERE mid = '$input[mid]'");

            $update_arr['password'] = $this->get_salt_hash($input['password'], $salt);
        }

        if (!empty($input['pay_password'])) {
            $salt = $this->db->getstr("SELECT salt FROM ###_member_detail WHERE mid = '$input[mid]'");

            $update_arr['pay_password'] = $this->get_salt_hash($input['pay_password'], $salt);
        }

        admin_log('编辑会员：' . $input['username']);

        $r = $this->db->update($this->baseTable, $update_member, "mid = '$input[mid]'");

        $this->member_other_save($update_arr, $input[mid]);
        return array("code" => 0, "message" => "更新成功");
        /*return $r
            ? array(
                'code'    => '0',
                'message' => '更新成功',
        ) : $r;*/
    }

    /**
     * 检查用户名唯一性
     */
    function check_username($username, $field = 'username', $where = '')
    {
        $member = $this->db->get("SELECT * FROM `" . $this->baseTable . "` WHERE `$field` = '" . addslashes($username) . "' $where");
        return $member;
    }

    /**
     *
     * 将用户设为登录
     * 登录后的信息处理 整站唯一 必须调用这里
     * @param array $uer 登录用户的信息
     */

    function setLogin($user, $admin = 0)
    {
        #更新在线日志
        $this->session->login($user['mid']);

        $_SESSION['mid'] = $user['mid'];

        $_SESSION['username'] = $user['username'];

        if (isset($user['subscribe_time'])) {
            // 记录关注状态
            $_SESSION['subscribe_time'] = $user['subscribe_time'];
        }

        if (!$admin) {
            #每天登录加一次经验值
            // if (!$this->db->getstr("SELECT COUNT(mid) FROM ###_account_log WHERE mid=" . $_SESSION['mid'] . " AND stage='" . ACT_LOGIN . "' AND logtime>" . strtotime(date('Y-m-d')))) {
            // 	$this->accountlog(ACT_LOGIN, array('rank_points' => (int) $this->site_config['rank_points_login'], 'desc' => '每日登录获得经验值'));
            // }

            //登录后合并购物车
            //$this->load->model('flow');
            //$this->flow->cart_merge();

            //登录日志
            $this->add_login_log($user);

            // 登录时将所有的未读消息写入到引用表 这样不活跃的用户就没有消息引用 减少数据压力
            $this->load->model('message');

            $this->message->getUnRead($user['mid']);

        }
    }

    /**
     * 增加用户登录记录
     */
    function add_login_log($user)
    {
        $update = "login_time=login_time+1,lastip=ip,`ip`='" . getIP() . "',`lastlogin`=login,`login`='" . RUN_TIME . "'";

        $this->db->update("###_member_detail", $update, "mid = '$user[mid]'");
    }

    /**
     * 获取加密的密码
     *
     * @param $password
     * @param $salt
     * @param string $gsalt
     * @return string
     */
    public function get_salt_hash($password, $salt, $gsalt = 'scYltK')
    {
        $passwordmd5 = preg_match('/^\w{32}$/', $password) ? $password : md5($password);
        return md5($passwordmd5 . $salt . $gsalt);
    }

    /**
     * 修改密码
     * @param $oldpass
     * @param $newpass
     * @param $uid
     * @return int
     */
    function alter_pass($oldpass, $newpass, $uid, $field = 'password')
    {
        $user = $this->db->get("SELECT * FROM `###_member_detail` WHERE `mid` = '" . intval($uid) . "'");

        if (!isset($user['mid'])) {
            return -1; #不存在该用户
        } elseif ($user[$field] != $this->get_salt_hash($oldpass, $user['salt'])) {
            return -2; #密码错误
        } else {
            $setPass = $this->get_salt_hash($newpass, $user['salt']);

            $this->db->update('member_detail', array(
                $field => $setPass,
            ), array('mid' => intval($uid)));
            return $user['mid'];
        }
    }

    #检查登录
    public function check_login($username, $password)
    {
        $user = $this->db->query("SELECT * FROM `" . $this->baseTable . "` WHERE `username` = '" . addslashes($username) . "'")->row_array();
        if (empty($user['email'])) {
            return -1; #不存在该邮箱
        } elseif ($user['password'] != $this->get_salt_hash($password, $user['salt'])) {
            return -2; #密码错误
        }
        return $user;
    }

    /**
     * 保存认证身份证
     */
    function verify_idcard_save($input)
    {
        if ($input['id']) {
            $id = $input['id'];
            unset($input['id']);

            admin_log('编辑验证身份证：' . $input['username']);

            $r = $this->db->update('verify_idcard', $input, array('id' => $id));
        } else {
            admin_log('添加验证身份证：' . $input['username']);

            $r = $this->db->insert('verify_idcard', $input);
        }
        return $r;
    }

    /**
     * 会员收货地址
     */
    function member_address($mid, $type = 0)
    {
        $address = array();

        $list = $this->db->select("SELECT * FROM `###_member_address` WHERE `mid` = '" . $mid . "' ORDER BY is_default DESC");
        if ($type == 1) {
            foreach ($list as $v) {
                $address[$v['id']] = $v;
            }
        } else {
            $address = $list;
        }
        return $address;
    }

    /**
     * @param array $where
     * @return array|null
     */
    function get_address($where)
    {
        $row = $this->db->get("SELECT * FROM `###_member_address` WHERE " . $where);
        return $row;
    }

    /**
     * 会员收货地址编辑
     */
    function member_address_save($input)
    {
        if ($input['id']) {
            $id = $input['id'];
            unset($input['id']);

            $r = $this->db->update('member_address', $input, array('id' => $id));
        } else {
            $r = $this->db->insert('member_address', $input);
        }
        return $r;
    }

    /**
     * 会员银行账号
     */
    function bankcard($mid)
    {
        $bankcard = $this->db->select("SELECT * FROM `###_member_bankcard` WHERE `mid` = '" . $mid . "' ORDER BY is_default DESC");
        return $bankcard;
    }

    /**
     * 会员银行账号编辑
     */
    function bankcard_save($input)
    {
        if ($input['id']) {
            $id = $input['id'];
            unset($input['id']);

            $r = $this->db->update('member_bankcard', $input, array('id' => $id));
        } else {
            $r = $this->db->insert('member_bankcard', $input);
        }
        return $r;
    }

    /**
     * 添加佣金记录
     * @param $order_common
     * @param $goods
     */
    function comms_record($order){

        $order_common = $this->db->get("select * from ###_goods_order_common where id={$order['common_id']}");
        if($order_common['type']==CART_FREE || $order_common['type']==CART_LUCK){
            return false;
        }

        if($order['extension_code']==CART_SHARE || C('comss')){
            $member = $this->member_info($order['mid']);
            $goods = $this->db->get("select goods_id,share_comss from ###_goods_additional where goods_id=".$order['extension_id']);
            $comm_scale = $this->db->getstr("select comm_scale from ###_goods where id=".$order['extension_id'],"comm_scale");
        }

        if(C('comss') && isset($comm_scale) && $comm_scale>0 && $member['ivt_id']>0){//分销佣金
            $ivt = $this->member_info($member['ivt_id']);
            $ratio = $comm_scale;
            $commission = round($ratio * $order['order_amount']* 0.01, 2);
            $desc = $ratio."%";

            if ($commission > 0) {
                $insert_arr = array();
                $insert_arr['mid'] = $ivt['mid'];
                $insert_arr['username'] = $ivt['nickname'];
                $insert_arr['ivt_mid'] = $order['mid'];
                $insert_arr['ivt_username'] = $member['nickname'];
                $insert_arr['order_id'] = $order['id'];
                $insert_arr['total'] = $order['order_amount'];
                $insert_arr['commission'] = $commission;
                $insert_arr['desc'] =  "邀请会员[".$member['nickname']."]下单(订单号".$order['order_sn']."、商品ID".$order['extension_id']."),获得".$desc.L('unit_comm')."(".L('unit_distribution').L('unit_comm').")";
                $insert_arr['level'] = 1;
                $this->save_commission($insert_arr);
            }
        }
        if($order_common['goods_typeid']==CART_SHARE && isset($goods) && $goods['share_comss']>0){//推广团添加佣金
            if($order['mid']==$order_common['mid']){
                return false;
            }
            $temp = $this->member_other($order_common['mid']);
            $insert_arr = array();
            $insert_arr['mid'] = $order_common['mid'];
            $insert_arr['username'] = $temp['nickname'];
            $insert_arr['ivt_mid'] = $order['mid'];
            $insert_arr['ivt_username'] = $member['nickname'];
            $insert_arr['order_id'] = $order['id'];
            $insert_arr['total'] = $order['order_amount'];
            $insert_arr['commission'] = $goods['share_comss'];
            $insert_arr['desc'] =  "会员[".$member['nickname']."]参团(订单号".$order['order_sn']."、商品ID".$goods['goods_id']."),获得".$goods['share_comss']."元".L('unit_comm')."收益(推广团)";
            $this->save_commission($insert_arr);
        }

    }

    /**
     * 佣金
     */
    function save_commission($input)
    {
        $input['addtime'] = RUN_TIME;

        $this->db->insert('commission', $input);
        //更新会员佣金
        //$this->db->update('member', "commission = commission + $input[commission],commission_total = commission_total + $input[commission]", array('mid' => $input['mid']));
        //更新订单佣金
        $this->db->update('goods_order', "comm_amount = comm_amount + $input[commission],is_comm=1", array('id' => $input['order_id']));
    }

    function commission_fee($amount)
    {

        $result = array();

        $result['fee'] = $amount * $this->site_config['present_rate'];

        $result['amount'] = $amount - $result['fee'];
        return $result;

    }

    /**
     * 佣金提现手续费
     */
    function commission_fee2($amount)
    {
        $result = array();
        switch ($amount) {
            case $amount <= 200:
                $result['fee'] = $amount * 0.08;

                break;
            case 200 < $amount && $amount <= 500:
                $result['fee'] = $amount * 0.05;

                break;
            case 500 < $amount && $amount <= 800:
                $result['fee'] = $amount * 0.05;

                $result['sales_tax'] = $amount * 0.056;

                break;
            case 800 < $amount && $amount <= 1000:
                $result['fee'] = $amount * 0.05;

                $result['sales_tax'] = $amount * 0.056;

                $result['income_tax'] = $amount * 0.03;

                break;
            case 1000 < $amount && $amount <= 4000:
                $result['fee'] = $amount * 0.05;

                $result['sales_tax'] = $amount * 0.056;

                $result['income_tax'] = ($amount - 800) * 0.2;

                break;
        }
        $result['amount'] = $amount - $result['fee'] - $result['sales_tax'] - $result['income_tax'];
        return $result;
    }

    /**
     * 提现/充值记录
     */
    function member_account_save($input)
    {
        if ($input['id']) {
            $id = $input['id'];
            unset($input['id']);

            admin_log('编辑充值/提现记录：' . $input['username']);

            $r = $this->db->update('member_account', $input, array('id' => $id));
        } else {
            $input['add_time'] = RUN_TIME;

            admin_log('添加充值/提现记录：' . $input['username']);

            $r = $this->db->insert('member_account', $input);
        }
        return false !== $r
            ? array(
                'code' => '0',
                'message' => '操作成功',
            ) : $r;
    }

    /**
     * 会员信息
     */
    function member_info($id, $filed = '*')
    {
        $member = $this->db->get("SELECT $filed FROM " . $this->baseTable . " WHERE mid = '$id'");

        $member['defaultPic'] = '/upload/' . (!empty($member['sex']) && ($member['sex'] == 1) ? 'man' : 'woman') . '.png';

        $info = $this->member_other($id);

        if ($info && is_array($info)) {
            $member = array_merge($member, $info);
        }

        return $member;
    }

    /**
     * 会员扩展信息
     */
    function member_other($id, $filed = '*')
    {
        if (S("H_MEMBER_DETAIL_" . $id)) {
            $info = S("H_MEMBER_DETAIL_" . $id);
        } else {
            $info = $this->db->get("SELECT $filed FROM ###_member_detail WHERE mid = '$id'");

            S("H_MEMBER_DETAIL_" . $id, $info, 86400);
        }
        return $info;
    }

    /**
     * 会员扩展信息编辑
     */
    function member_other_save($data, $mid)
    {
        $r = $this->db->get("select 1 from ###_member_detail where mid=" . $mid);
        if ($r) {
            $res = $this->db->update("member_detail", $data, array('mid' => $mid));
        } else {
            $data['mid'] = $mid;

            $res = $this->db->insert("member_detail", $data);
        }
        S("H_MEMBER_DETAIL_" . $mid, NULL);
        return false !== $res
            ? array(
                'code' => '0',
                'message' => '操作成功',
            ) : $res;

    }

    /**
     * 会员退出
     */
    function logout()
    {
        unset($_SESSION['mid']);
        unset($_SESSION['username']);
        unset($_SESSION['member']);

        zzcookie('username', '');

        zzcookie('password', '');
        //清空购物车数量 Feng 2016-05-30
        unset($_SESSION['cartNum']);
    }

    /**
     * 账户日志
     * 冻结余额时可用相应进行调整
     */
    function accountlog($stage = 'admin', $input)
    {
        global $lowxp;

        $log_arr = array();

        $log_arr['mid'] = empty($input['mid']) ? $_SESSION['mid'] : $input['mid'];

        $member = $this->db->get("SELECT * FROM ###_member WHERE mid = '$log_arr[mid]'");

        $log_arr['username'] = $log_arr['mid'] == $_SESSION['mid'] ? $_SESSION['username'] : $this->db->getstr("SELECT username FROM ###_member WHERE mid = '$log_arr[mid]'");

        $log_arr['stage'] = $stage;

        $log_arr['desc'] = $input['desc'];

        $log_arr['logtime'] = RUN_TIME;
        //$log_arr['pay_points']   = $logs['pay_points']   = isset($input['pay_points']) ? $input['pay_points'] : 0;
        //$log_arr['rank_points']  = $logs['rank_points']  = isset($input['rank_points']) ? $input['rank_points'] : 0;
        //$log_arr['db_points']    = $logs['db_points']    = isset($input['db_points']) ? $input['db_points'] : 0;
        $log_arr['frozen_money'] = $logs['frozen_money'] = isset($input['frozen_money']) ? $input['frozen_money'] : 0;

        $log_arr['user_money'] = $logs['user_money'] = isset($input['user_money']) ? $input['user_money'] : 0;
        //$log_arr['give_money']   = isset($input['give_money']) ? $input['give_money'] : 0;
        //$log_arr['pay_money']=$logs['pay_money']=isset($input['pay_money'])?$input['pay_money']:0;

        $set_arr = array();
        foreach ($logs as $k => $v) {
            $set_arr[] = "$k = $k+('$v')";
        }

        //记录日志
        $this->db->insert('###_account_log', $log_arr);

        //更新会员账户
        /*$sql = "SELECT a.username,a.rank_points,a.rank_id,b.special FROM " . $this->baseTable . " AS a " .
            "LEFT JOIN ###_member_rank AS b ON a.rank_id=b.id " .
            "WHERE mid=" . $log_arr['mid'];
        $member = $this->db->get($sql);

        #更新会员等级
        if (empty($member['special']) && $log_arr['rank_points'] > 0) {
            $rank_points = $member['rank_points'] + $log_arr['rank_points'];
            $sql         = "SELECT id FROM ###_member_rank WHERE min_points<='" . $rank_points . "' AND max_points>'" . $rank_points . "'";
            $set_arr[]   = "rank_id = " . (int) $this->db->getstr($sql);
        }
                */
        $r = $this->db->update($this->baseTable, implode(',', $set_arr), "mid = '$log_arr[mid]'");

        return false !== $r
            ? array(
                'code' => '0',
                'message' => '更新成功',
            ) : $r;
    }

    //访客列表
    function visitList($size, $page, $mid, $options = array())
    {
        #分页
        $this->load->model('page');

        $_GET['page'] = intval($page);

        $this->page->set_vars(array('per' => $size));

        $sql = "SELECT * FROM ###_member_visit WHERE mid1=" . $mid . " ORDER BY lasttime DESC";

        $urlQuery = isset($options['url']) ? $options['url'] : "";

        $res = $this->page->hashQuery($sql, $urlQuery)->result_array();

        $res = $this->db->lJoin($res, 'member', 'mid,photo,username,nickname,intro,rank_points', 'mid2', 'mid');

        return $res;
    }

    //好友列表
    function friList($size, $page, $mid, $options = array())
    {
        #分页
        $this->load->model('page');

        $_GET['page'] = intval($page);

        $this->page->set_vars(array('per' => $size));

        $sql = "SELECT * FROM ###_member_fri WHERE mid1=" . $mid . " ORDER BY c_time DESC";

        $urlQuery = isset($options['url']) ? $options['url'] : "";

        $res = $this->page->hashQuery($sql, $urlQuery)->result_array();

        $res = $this->db->lJoin($res, 'member', 'mid,photo,username,nickname,intro,rank_points', 'mid2', 'mid');

        return $res;
    }

    //判断是否为好友关系
    function isFri($mid1, $mid2)
    {
        if (!$mid1 || !$mid2) {
            return false;
        }

        $sql = "SELECT COUNT(mid1) FROM ###_member_fri WHERE mid1=" . $mid1 . " AND mid2=" . $mid2;
        return $this->db->getstr($sql);
    }

    //添加/解除好友
    function addFri($mid1, $mid2)
    {
        if (!$mid1 || !$mid2) {
            return false;
        }

        $isfri = $this->isFri($mid1, $mid2);
        if ($isfri > 0) {
            #解除好友
            $this->db->delete('member_fri', "mid1=" . $mid1 . " AND mid2=" . $mid2);
            return 2;
        } else {
            #添加好友
            $this->db->save('member_fri', array(
                'mid1' => $mid1,
                'mid2' => $mid2,
                'c_time' => time(),
            ));
            return 1;
        }
    }

    //获取推荐人数
    function itvCount($mid, $options = array())
    {
        $where = isset($options['where']) ? $options['where'] : '';

        $ivt_count = $this->db->getstr("SELECT count(mid) FROM ###_member WHERE ivt_id='" . $mid . "' $where");
        return $ivt_count;
    }

    //第三注册用户
    function oauth_user($user)
    {

        if (empty($user['open_id'])) {
            return false;
        }

        $sql = '';

        $member = array();

        $sql = "SELECT * FROM ###_oauth WHERE openid='" . $user['unionid'] . "' OR openid='" . $user['open_id'] . "' limit 1";

        // 有sql 标识新注册用户
        if ($sql) {
            $member = $this->db->get($sql);
        }

        if (empty($member['mid'])) {
            //入驻限制会员数量
            if(defined('MEMBER_COUNT') && in_array(Edition,array("enter_one","enter_more")) && MEMBER_COUNT>0){
                $cache_name = 'CM_MEMBER_NUM';
                if(S($cache_name)){
                    $member_c = S($cache_name);
                }else{
                    $member_c = $this->db->getstr("select count(1) as num from ###_member where is_robots=0","num");
                }
                if($member_c>=MEMBER_COUNT){
                    return false;
                }
                S('CM_MEMBER_NUM',$member_c+1);
            }
            // 新用户注册
            $user['nickname'] = trim($user['nickname']);

            $user['open_id'] = isset($user['open_id']) ? $user['open_id'] : '';

            $user['unionid'] = isset($user['unionid']) ? $user['unionid'] : '';

            //获取所在地id
            $location = isset($user['location']) ? $user['location'] : '';
            $zone = 0;
            if($location){
                $this->load->model("linkage");
                $zone = $this->linkage->strToZone($location);
            }

            $data = array(
                'username' => $user['nickname'], //  微信扫码用户名即为微信用户名  有重复可能 用户名 后期不作为登录依据
                'mobile' => isset($user['mobile']) ? $user['mobile'] : '',
                'subscribe_time' => isset($user['subscribe_time']) ? $user['subscribe_time'] : 0,
                'ivt_id' => 0,
                'ivt_level' => 0,
                'zone' => $zone,
            );

            // 注册送积分
            $this->load->model('score');

            $this->score->actionByRuleId(2, $mid);

            // 模版消息参数 start
            $queue = array(); // 模版消息批量容器
            $memberCount = $this->db->getStr('select count(*) as count from ###_member', 'count');

            $siteName = C('site_name');

            // 模版变量参数 等待插入其他参数
            $templateParam = array($memberCount, $siteName);

            // 模版消息7 注册成会员 {插入ID号},{插入会员个数},{插入店铺}
            $templateId = 7;
            // 模版消息参数 end

            // 要求参数字段名称 注意要尽量生僻不要跟其他get参数重复
            $ivtFieldName = 'inviter_id';
            $ivtId = intval($_GET[$ivtFieldName]) > 0 ? intval($_GET[$ivtFieldName]) : $_SESSION[$ivtFieldName];
            if ($ivtId > 0 && C('comss')) {
                //$ivtId = intval($_GET[$ivtFieldName]);

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

                    /*if ($ivt['ivt_id']) {
                        // 推荐人1级上级

                        $queue[] = array(10, $ivt['ivt_id'], array($user['nickname'], 3, getUsername($ivt['ivt_id']), $memberCount, $siteName));
                        if (!empty($scoreRule['extend'][1])) {
                            $tmp = array(
                                'mid' => $ivt['ivt_id'],
                                'type' => 3, // 注意这里的 ruleId 固定为3表示 分享赚积分
                                'amount' => round($scoreRule['extend'][1]),
                                'remark' => $user['nickname'] . ' 被邀请注册.作为推荐人1级上级获得积分',
                                'c_time' => RUN_TIME,
                            );
                            $this->score->scoreLog($tmp);
                        }
                    }*/

                    // 推荐人本人
                    // 模版消息 9 有人被您推荐成会员 {插入昵称},{插入会员个数},{插入店铺}
                    $queue[] = array(9, $ivt['mid'], array($user['nickname'], $memberCount, $siteName));
                    if (!empty($scoreRule['extend'][0])) {
                        $tmp = array(
                            'mid' => $ivt['mid'],
                            'type' => 3, // 注意这里的 ruleId 固定为3表示 分享赚积分
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
                    $this->db->update('member', "ivt_count=ivt_count+1", array('mid' => $ivt['mid']));
                }

            }

            $mid = $this->db->insert('member', $data);
            if ($mid > 0) {
                $data_oauth['mid'] = $mid;

                $data_oauth['type'] = !empty($user['type'])?$user['type']:0;

                $data_oauth['openid'] = $user['open_id'];

                $data_oauth['create_time'] = RUN_TIME;

                $this->db->insert('oauth', $data_oauth);

                if($user['unionid']){
                    $data_oauth['mid'] = $mid;

                    $data_oauth['type'] = 3;//微信unionid

                    $data_oauth['openid'] = $user['unionid'];

                    $data_oauth['create_time'] = RUN_TIME;

                    $this->db->insert('oauth', $data_oauth);
                }

                $data_detail['mid'] = $mid;

                $data_detail['nickname'] = $user['nickname'];

                $data_detail['photo'] = $user['avatar'];

                $data_detail['c_time'] = RUN_TIME;

                $data_detail['lastlogin'] = RUN_TIME;

                $this->db->insert('member_detail', $data_detail);

                //助力
                $this->load->model('assist');
                $assist_id = cookie("assist_id");
                if($this->assist->power==1 && !empty($assist_id)) {//判断是否开启
                    $this->assist->assist_help($assist_id,$mid);
                }
            }

            // 模版消息 start
            // 主动注册会员 或者被推荐成会员

            $receiver = array(
                'wehcat' => $user['openid'],
                'msg' => $mid,
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
            //$this->template_msg->inQueueMany($queue);
            // template_msg_action end

            // 模版消息 end
            return $mid;
        }else{

            if($user['unionid']){
                $is_res = $this->db->get("select 1 from ###_oauth where mid={$member['mid']} and openid='{$user['unionid']}'");
                if(!$is_res){
                    $data_oauth['mid'] = $member['mid'];

                    $data_oauth['type'] = 3;//微信unionid

                    $data_oauth['openid'] = $user['unionid'];

                    $data_oauth['create_time'] = RUN_TIME;

                    $this->db->insert('oauth', $data_oauth);
                }
            }
            return $member['mid'];
        }
    }

    public function bindMobile()
    {
        if (isset($_POST)) {

            $res = array(
                'error' => 1,
                'msg' => '',
            );

            $mobile = isset($_POST['mobile']) ? trim($_POST['mobile']) : '';
            #手机号不能为空
            if (empty($mobile)) {
                $res['msg'] = '手机号不能为空';
                return $res;
            }
            #手机号唯一
            $r = $this->db->get("SELECT username FROM `###_member` WHERE mobile='$mobile'");
            if ($r) {
                $res['msg'] = '手机号已被注册, 请更换';
                return $res;
            }
            //注册短信验证码
            $verifycode = '';
            if ($this->site_config['sms_open'] == 1 && statusTpl('sms_register')) {
                $this->load->library('sms');
                $verifycode = empty($_POST['sms_code']) ? '' : trim($_POST['sms_code']);
                /* 验证手机号验证码和IP */
                $sql = "SELECT id FROM `###_verify_code` WHERE mobile='$mobile' AND verifycode='$verifycode' AND dateline>(" . RUN_TIME . "-3600)"; //ÑéÖ¤Âë60·ÖÖÓÄÚÓÐÐ§
                $temp = $this->db->get($sql);
                if (!$temp) {
                    $res['msg'] = '手机号码和验证码不匹配或者验证码已过期（1小时内有效）';
                    return $res;
                }
            }

            if (isset($_SESSION['mid'])) {
                $this->db->update('member', 'mobile=' . $mobile, 'where mid = ' . $_SESSION['mid']);
                $res['error'] = 0;
                $res['msg'] = '绑定成功';
                return $res;
            } else {
                // todo 非微信环境下未登录用户注册成会员并做登录操作
                // todo 必须保证 $_SESSION['mid'] 存在
            }
        }

    }
}