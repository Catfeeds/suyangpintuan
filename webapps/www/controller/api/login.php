<?php

/**
 * Created by PhpStorm.
 * User: hjr
 * Date: 2016/9/12
 * Time: 17:46
 */
class login extends Lowxp{
    /**
     * 登录
     */
    public function act_login(){
        $username = isset($_REQUEST['username']) ? addslashes(trim($_REQUEST['username'])) : '';
        $password = isset($_REQUEST['password']) ? trim($_REQUEST['password']) : '';
        if(empty($username) || empty($password)){
            $this->api_result(array('msg'=>'用户名或密码不能为空'));
        }
        $this->load->model('member');
        $user = $this->db->get("SELECT p1.mid,p1.username,p1.status,p1.zone,p2.password,p2.salt,p2.nickname FROM ###_member as p1 left join ###_member_detail as p2 on p1.mid=p2.mid WHERE p1.username='" . $username . "' || p1.mobile='" . $username . "'");
        if(!$user || $this->member->get_salt_hash($password, $user['salt']) != $user['password']){
            $data['flag'] = false;
            $data['code'] = 100003;
            $data['msg'] = '用户名或密码错误';
            $this->api_result($data);
        }else{
            if ($user['status'] != 1) {
                $this->api_result(array('msg'=>'此帐户已被封号，如有疑问，请咨询网站客服人员！'));
            }
            #登录成功;
            $this->member->setLogin($user);
            if($user['zone']>0){
                $city = $this->db->getstr("select name from ###_linkage where id={$user['zone']}","name");
            }else{
                $city = null;
            }
            $data['UID'] = $user['mid'];
            $data['UNAME'] = $user['username'];
            $data['NNAME'] = $user['nickname'];
            $data['UPSW'] = $user['password'];
            $data['ZONE'] = $user['zone'];
            $data['CITY'] = $city;
            $data['avatar'] = photo($user['mid']);
            unset($user);
            $this->api_result(array('data'=>$data));
        }
    }

    /**
     * 发送验证码（先判断是否存在，不存在注册会员，后登陆）
     */
    public function send_verify_code(){
        $act = isset($_POST['act']) ? trim($_POST['act']) : 'sms_code';
        $mobile = isset($_POST['mobile']) ? trim($_POST['mobile']) : null;
        if(!$mobile){
            $this->api_result(array('flag' => false, 'msg' => '请输入手机号码！', 'code' => 100002));
        }
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
                    $this->api_result(array('flag' => false, 'msg' => '手机号码已经存在！', 'code' => 100002));
                }
                break;
            case 'sms_chpass':
                //找回密码
                $status = 3;
                //提交的手机号是否存在
                $sql = "SELECT COUNT(mid) FROM `###_member` WHERE mobile = '$mobile'";
                if ($this->db->getstr($sql) == 0) {
                    $this->api_result(array('flag' => false, 'msg' => '手机号不存在！', 'code' => 100002));
                }
                break;
            default:
            # code...
            break;
        }

        //获取验证码请求是否获取过
        /*$iTime=60;
        $sql = "SELECT COUNT(id) FROM ###_verify_code WHERE status='$status' AND mobile='$mobile' AND getip='".getIP()."' AND dateline>'".(time()-$iTime)."'";
        if ($this->db->getstr($sql) > 0)
        {
            $this->api_result(array('flag' => false, 'msg'=> '每IP每手机号每'.$iTime.'秒只能获取一次验证码。', 'code' => 100001));
        }*/
        if(S("MOBILE_".$mobile)){
            $this->api_result(array('flag' => false, 'msg'=> '每手机号每60秒只能获取一次验证码。', 'code' => 100001));
        }
        S("MOBILE_".$mobile,$mobile,60);

        #发送验证码
        $this->load->library('sms');
        $verifycode = $this->sms->getVerifyCode();
        $this->smarty->assign('verify_code', $verifycode);
        $ret = $this->sms->sendSmsTpl($mobile, $template_code = 'sms_register');
        if ($ret === true) {
            $data = array(
                'mobile'     => $mobile,
                'getip'      => getIP(),
                'verifycode' => $verifycode,
                'dateline'   => time(),
                'status'     => $status,
            );
            $this->db->save('###_verify_code', $data);
            $this->api_result(array('data'=>array('verifycode'=>$verifycode),'msg'=>'短信验证码已发送,请注意查收'));
        }else{
            $msg = $ret ? $ret : '手机短信验证码发送失败!';
            $this->api_result(array('flag' => false, 'msg'=>$msg, 'code' => 100001));
        }
    }

    /**
     * 验证码验证登录
     */
    public function login_verify_code(){
        $verifycode = isset($_POST['verifycode']) ? trim($_POST['verifycode']) : null;
        $mobile = isset($_POST['mobile']) ? trim($_POST['mobile']) : null;
        if(!$verifycode){
            $this->api_result(array('flag' => false, 'msg' => '请输入验证码！', 'code' => 100002));
        }
        if(!$mobile){
            $this->api_result(array('flag' => false, 'msg' => '请输入手机号！', 'code' => 100002));
        }
        $sql  = "SELECT id FROM ###_verify_code WHERE mobile='$mobile' AND verifycode='$verifycode'  AND dateline>(" . time() . "-3600)"; //验证码60分钟内有效
        $temp = $this->db->get($sql);
        if (!$temp) {
            $msg = "手机号码和验证码不匹配或者验证码已过期（1小时内有效）";
            $this->api_result(array('flag' => false, 'msg' => $msg, 'code' => 100001));
        }
        if ($this->site_config['sms_open'] == 1 && statusTpl('sms_register')) {
            $result = $this->db->update('verify_code', array(
                'reguid'      => $_SESSION['mid'],
                'regdateline' => time(),
                'status'      => 2,
            ), "`mobile`='$mobile' AND `verifycode`='$verifycode' AND `getip`='" . getIP() . "' AND `status`='1' AND `dateline`>" . (time() - 3600));
            if(!$result){
                $this->api_result(array('flag' => false, 'msg' => '验证失败，请重新验证', 'code' => 100001));
            }
            $this->load->model('member');
            $user = $this->db->get("SELECT p1.mid,p1.username,p1.status,p2.password,p2.salt FROM ###_member as p1 left join ###_member_detail as p2 on p1.mid=p2.mid WHERE  p1.mobile='" . $mobile . "'");
            if($user){
                if ($user['status'] != 1) {
                    $this->api_result(array('msg'=>'此帐户已被封号，如有疑问，请咨询网站客服人员！'));
                }
                #登录成功;
                $this->member->setLogin($user);
                $data['UID'] = $user['mid'];
                $data['UNAME'] = $user['username'];
                $data['UPSW'] = $user['password'];
                $data['avatar'] = RootUrl.photo($user['mid']);
                unset($user);
                $this->api_result(array('data'=>$data));
            }else{
                $this->api_result(array('flag' => false, 'msg' => '会员不存在', 'code' => 100001));
            }

        }else{
            $msg = '请在后台打开短信配置或者检查短信模板';
            $this->api_result(array('flag' => false, 'msg' => $msg, 'code' => 100001));
        }
    }

    /**
     * 第三方登录
     */
    public function oauth_login(){
        $type = !empty($_REQUEST['type']) ? trim($_REQUEST['type']) : false;
        if(!empty($_POST['type']) && !$type) $type = trim($_POST['type']);
        $openid = trim($_POST['openid']);
        $unionid = trim($_POST['unionid']);
        if(!$type) $this->api_result(array('flag' => false, 'msg' => '第三方登录类型不能为空，值（qq、wx、wb）对应第三方（QQ、微信、微博）', 'code' => 100002));
        if(empty($openid))
            $this->api_result(array('msg'=>'授权失败,openid不存在'));
        switch($type){
            case 'wx':
                $type = 0;
                break;
            case 'qq':
                $type = 1;
                break;
            case 'wb':
                $type = 2;
                break;
            default:
                $this->api_result(array('flag' => false, 'msg' => '第三方登录类型只限qq、wx、wb，对应QQ、微信、微博', 'code' => 100002));
        }
        $sql = "SELECT A.*,B.username,C.password,C.nickname FROM ###_oauth AS A LEFT JOIN ###_member AS B ON A.mid = B.mid LEFT JOIN ###_member_detail AS C ON C.mid = B.mid WHERE A.status=1 AND (A.openid = '$openid' OR A.openid = '$unionid') limit 1";
        $user = $this->db->get($sql);
        $this->load->model('member');
        #注册
        if(empty($user)){
            $input['username']       = isset($_POST['nickname']) ? trim($_POST['nickname']) : '';
            $input['nickname']       = isset($_POST['nickname']) ? trim($_POST['nickname']) : '';
            $input['avatar']          = isset($_POST['avatar']) ? trim($_POST['avatar']) : '';
            $input['type'] = $type;
            $input['open_id'] = $openid;
            $input['unionid']        = $unionid;
            $input['subscribe_time'] = 0;
            $input['sex']            = 1;
            $input['birthday']       = '0000-00-00';
            $res = $this->member->oauth_user($input);
            if($res['code'] != 0){
                $msg = "注册失败," . $res['message'];
                $this->api_result(array('flag' => false, 'msg' => $msg, 'code' => 100001));
            }
            $user = $this->db->get($sql);
        }
        $this->member->setLogin($user);
        $data['UID'] = $user['mid'];
        $data['UNAME'] = $user['username'];
        $data['UPSW'] = $user['password'];
        $data['avatar'] = $_POST['avatar'];
        $data['nickname'] = $user['nickname'];
        $data['sessionid'] = session_id();
        //更新小程序MID
        if(!empty($_SERVER['HTTP_TOKEN'])) {
            $token = $_SERVER['HTTP_TOKEN'];
            $result = S($token);
            $result['mid'] = $user['mid'];
            $result['username'] = $user['username'];
            //有效期1天
            $expires = RUN_TIME + 3600 * 24;
            $result['expires'] = $expires;
            S($token, $result, $expires);
        }

        //助力
        $this->load->model("assist");
        if($this->assist->power==1){
            $this->assist->assist_help_app($user['mid']);
        }
        unset($user);
        $this->api_result(array('data'=>$data));
    }

    /**
     * 注册
     */
    function register(){
        $verifycode = isset($_POST['verifycode']) ? trim($_POST['verifycode']) : null;
        $post['mobile'] = isset($_POST['mobile']) ? trim($_POST['mobile']) : null;
        $post['password'] = isset($_POST['password']) ? trim($_POST['password']) : null;
        $post['username'] = $post['mobile'];

        if(!$post['mobile']){
            $this->api_result(array('flag' => false, 'msg' => '请输入手机号！', 'code' => 100001));
        }else{
            $is_res = $this->db->get("select 1 from ###_member where mobile={$post['mobile']}");
            if($is_res)$this->api_result(array('flag' => false, 'msg' => '该手机号已经存在！', 'code' => 100002));
        }

        if(!$post['password']){
            $this->api_result(array('flag' => false, 'msg' => '请输入密码！', 'code' => 100003));
        }

        if (C('sms_open') == 1 && statusTpl('sms_register')) {

            if(!$verifycode){
                $this->api_result(array('flag' => false, 'msg' => '请输入验证码！', 'code' => 100004));
            }
            /* 验证手机号验证码和IP */
            $sql  = "SELECT id FROM ###_verify_code WHERE mobile='{$post['mobile']}' AND verifycode='$verifycode' AND dateline>(" . time() . "-3600)"; //验证码60分钟内有效
            $temp = $this->db->get($sql);
            if (!$temp) {
                $this->api_result(array('flag' => false, 'msg' => "手机号码和验证码不匹配或者验证码已过期（1小时内有效）", 'code' => 100005));
            }

        }
        $this->load->model('member');
        $res = $this->member->create_user($post);
        #print_r($res);exit;
        if($res){
            $data['UID'] = $res['mid'];
            $data['UNAME'] = $res['username'];
            $data['UPSW'] = $res['password'];
            $data['avatar'] = RootUrl.photo($res['mid']);
            $this->api_result(array('data'=>$data));
        }else{
            $this->api_result(array('flag' => false, 'msg' => '注册失败', 'code' => 100009));
        }

    }

    function forget(){
        $mobile = trim($_POST['mobile']);
        $password = trim($_POST['password']);
        if(empty($mobile)){
            $this->api_result(array('flag' => false, 'msg' => '手机号码不能为空！', 'code' => 100002));
        }
        if(empty($_POST['sms_code'])){
            $this->api_result(array('flag' => false, 'msg' => '验证码不能为空！', 'code' => 100002));
        }
        if(empty($password)){
            $this->api_result(array('flag' => false, 'msg' => '密码不能为空！', 'code' => 100002));
        }
        //注册短信验证码
        $verifycode = '';
        if ($this->site_config['sms_open'] == 1 && statusTpl('sms_chpass')) {
            $this->load->library('sms');
            $verifycode = empty($_POST['sms_code']) ? '' : trim($_POST['sms_code']);

            /* 验证手机号验证码和IP */
            $sql  = "SELECT id FROM ###_verify_code WHERE mobile='$mobile' AND verifycode='$verifycode' AND status=3 AND dateline>(" . time() . "-3600)"; //验证码60分钟内有效
            $temp = $this->db->get($sql);
            if (!$temp) {
                $this->api_result(array('flag' => false, 'msg' => '手机号码和验证码不匹配或者验证码已过期（1小时内有效）', 'code' => 100002));
            }
        }
        $member = $this->db->get("SELECT D.mid,D.salt FROM ###_member as M left join ###_member_detail as D on M.mid=D.mid WHERE M.mobile = '$mobile' ");
        if (empty($member)) {
            $this->api_result(array('flag' => false, 'msg' => '该用户不存在!', 'code' => 100002));
        }
        $this->load->model('member');
        $pwd = $this->member->get_salt_hash($password,$member['salt']);
        $this->db->update("member_detail",array("password"=>$pwd),array("mid"=>$member['mid']));
        $this->api_result(array('msg' => '修改密码成功'));
    }

    //退出登录
    function doexit() {
        $this->load->model('member');
        $this->member->logout();
        $this->api_result(array('msg' => '退出成功'));
    }

    //小程序登录
    function weapp(){
        $code = !empty($_REQUEST['code']) ? trim($_REQUEST['code']) : '';
        $appid = C('xcx_appid');
        $appsecret = C('xcx_appsecret');
        if(empty($appid) || empty($appsecret)) $this->api_result(array('msg' => '请配置小程序AppId','code'=>'10001'));
        //CODE授权
        if(!empty($code)){
            $url = "https://api.weixin.qq.com/sns/jscode2session?appid=".$appid."&secret=".$appsecret."&js_code=".$code."&grant_type=authorization_code";
            $result = http($url,'get');
            $result = json_decode($result,true);
            //处理SESSIONID
            if(!empty($result['openid'])){
                //是否已授权用户
                $sql = "SELECT A.*,B.username,C.password,C.nickname,C.photo FROM ###_oauth AS A LEFT JOIN ###_member AS B ON A.mid = B.mid LEFT JOIN ###_member_detail AS C ON C.mid = B.mid WHERE A.status=1 AND (A.openid = '$result[openid]' OR A.openid = '$result[unionid]') limit 1";
                $user = $this->db->get($sql);
                $data = array();
                if(!empty($user)){
                    $result['mid'] = $data['UID'] = $user['mid'];
                    $result['username'] = $data['UNAME'] = $user['username'];
                    $data['nickname'] = $user['nickname'];
                    $data['avatar'] = $user['photo'];
                    $data['sessionid'] = session_id();
                }
                $key = encrypt_en($result['openid'].$result['session_key']);
                //有效期1天
                $expires = RUN_TIME + 3600 * 24;
                $result['expires'] = $expires;
                S($key, $result, $expires);
                $data['token'] = $key;
                $this->api_result($data);
            }else{
                $this->api_result(array('msg' => 'CODE授权失败','code'=>'10002'));
            }
        }
        //TOKEN验证并授权用户
        $token = !empty($_REQUEST['token']) ? trim($_REQUEST['token']) : '';
        if(!empty($token) && $_SERVER['REQUEST_METHOD']=='POST'){
            $verifyToken = S($token);
            if(empty($verifyToken)) $this->api_result(array('msg' => 'TOKEN失效','code'=>'40001'));
            $put = $_POST;
            if(empty($put['encryptedData']) || empty($put['iv']))  $this->api_result(array('msg' => '缺少验证参数','code'=>'40002',$_POST));
            require_once AppDir . '/library/wxBizDataCrypt.php';
            $crypt = new WXBizDataCrypt($appid, $verifyToken['session_key']);
            $errCode = $crypt->decryptData($put['encryptedData'], $put['iv'], $data );
            $err = array(
                '41001'=>'encodingAesKey 非法',
                '41003'=>'aes 解密失败',
                '41004'=>'解密后得到的buffer非法',
                '41005'=>'base64加密失败',
                '41016'=>'base64解密失败'
            );
            if($errCode == 0){
                $data = json_decode($data,true);
                if($data['watermark']['appid']!=$appid) $this->api_result(array('msg' => 'APPID不匹配','code'=>'40003'));
                if($data['openId']!=$verifyToken['openid']) $this->api_result(array('msg' => 'OPENID不匹配','code'=>'40004'));
                //授权账号
                $_POST['openid'] = $data['openId'];
                $_POST['unionid'] = $data['unionId'];
                $_POST['avatar'] = $data['avatarUrl'];
                $_POST['nickname'] = $data['nickName'];
                $_POST['type'] = 'wx';
                $this->oauth_login();
            }else{
                $this->api_result(array('msg' => $err[$errCode],'code'=>$errCode));
            }
        }else{
            $this->api_result(array('msg' => 'TOKEN失效','code'=>'10002'));
        }
    }
}