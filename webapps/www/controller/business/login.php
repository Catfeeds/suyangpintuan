<?php

/**
 * ZZCMS 管理员登录
 * ============================================================================
 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。
 * 网站地址: http://www.lnest.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 */
class login extends Lowxp
{

    function __construct()
    {
        parent::__construct();

        $_GET && SafeFilter($_GET);
        $_POST && SafeFilter($_POST);
        $_COOKIE && SafeFilter($_COOKIE);
        $_REQUEST && SafeFilter($_REQUEST);

        $this->load->model('user');
        if (isset($_SESSION['b_uid']) && isset($_SERVER['request']['method'])) {
            $method = $_SERVER['request']['method'];
            if ($method != 'destroy') {
                //todo:让用户选择退出登录或跳转到home
                $this->exeJs('alert("当前已登录,该操作需在未登录状态下.");');
                //跳转到一个初始页面
                $this->exeJs('location.href="/business/login/destroy"');
                #$this->exeJs('location.href="/welcome";');
                die;
            }
        }
    }

    /**
     * 首页
     */
    function index()
    {
        $this->smarty->display('business/login/login.html');
    }

    /**
     * 显示错误
     * @param $msg
     */
    private function showError($msg)
    {
        $this->exeJs('alert("' . $msg . '");');
        die;
    }

    /**
     * 登录
     */
    function submit()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $scode = trim($_POST['scode']);

        /*if (isset($this->common['verify_admin']) && $this->common['verify_admin']) {
            if ($scode != -1 && strtolower($scode) != strtolower($_SESSION['scode'])) {
                echo json_encode(array("error" => 2, "msg" => "验证码错误！"));
                exit;
            }
        }*/

        $user = $this->db->get("SELECT id,name,mobile,password,salt FROM ###_business WHERE mobile='" . $username . "'");

        if (isset($user['id'])) {
            $this->load->model('user');
            if ($this->user->get_salt_hash($password, $user['salt']) != $user['password']) {
                echo json_encode(array("error" => 2, "msg" => "账号或密码错误！"));
                exit;
            }
            //执行登录操作
            $this->setLogin($user);
            $this->db->update("business", array("login_time" => RUN_TIME), array("mobile" => $user['mobile']));
            #获取第一个链接进行跳转.
            echo json_encode(array("error" => 0, "msg" => "登录成功！"));
            exit;
        } else {

            $user = $this->db->get("SELECT uid,username,password,salt,sid,group_id FROM ###_business_user WHERE username='" . $username . "'");
            if(isset($user['uid'])){
                $this->load->model('user');
                if ($this->user->get_salt_hash($password, $user['salt']) != $user['password']) {
                    echo json_encode(array("error" => 2, "msg" => "账号或密码错误！"));
                    exit;
                }
                $user['id'] = $user['sid'];
                $user['mobile'] = $user['name'] = $user['username'];
                $this->setLogin($user);
                $this->db->update("business_user", array("lastlogin" => RUN_TIME), array("uid" => $user['uid']));
                echo json_encode(array("error" => 0, "msg" => "登录成功！"));
                exit;
            }

            echo json_encode(array("error" => 2, "msg" => "账号或密码错误！"));
            exit;
        }
    }

    //注册
    function reg()
    {
        if (isset($_POST['Submit'])) {
            $res = $this->db->getstr("select count(1) as num from ###_business where status=1", "num");
            if ($res > STORE_NUM) exit($this->msg('店铺数量已达到最大限制'));
            $post = $_POST['post'];
            if (empty($post['mobile'])) exit($this->msg('手机不能为空'));
            if (empty($post['password'])) exit($this->msg('密码不能为空'));
            $is_res = $this->db->get("select 1 from ###_business where mobile={$post['mobile']}");
            if ($is_res) exit($this->msg('手机号码已存在'));

            $post['salt'] = substr(uniqid(rand()), -6);
            $post['password'] = get_salt_hash($post['password'], $post['salt']);

            //短信验证码
            $verifycode = '';
            if (C('sms_open') == 1 && statusTpl('sms_business_reg')) {
                $this->load->library('sms');
                $verifycode = empty($_POST['sms_code']) ? '' : trim($_POST['sms_code']);

                /* 验证手机号验证码和IP */
                $sql = "SELECT id FROM ###_verify_code WHERE mobile='{$post['mobile']}' AND verifycode='$verifycode' AND  status=6 AND dateline>(" . time() . "-3600)"; //验证码60分钟内有效
                $temp = $this->db->get($sql);
                if (!$temp) {
                    exit($this->msg("手机号码和验证码不匹配或者验证码已过期（1小时内有效）"));
                }
            } else {
                $scode = isset($_POST['scode']) ? trim($_POST['scode']) : '';
                if (!$scode) {
                    exit($this->msg("请输入验证码计算答案"));
                }
                if ($scode != $_SESSION['scode']) {
                    exit($this->msg("对不起,答案错误"));
                }
            }
            $post['c_time'] = RUN_TIME;
            $res = $this->db->insert('business', $post);
            if ($res) {
                $this->load->model('business');
                $user = $this->business->get($res);
                $this->setLogin($user);
                exit($this->msg('注册成功', array("url" => "/content/apply")));
            } else {
                echo json_encode(array("error" => 2, "msg" => "账号或密码错误！"));
                exit;
            }

        }
    }

    /**
     * 退出登录
     */
    function destroy()
    {
        #$_SESSION = array();
        unset($_SESSION['b_name']);
        unset($_SESSION['b_user']);
        unset($_SESSION['b_id']);
        unset($_SESSION['b_group_id']);
        unset($_SESSION['b_uid']);
        header('Location:/business/login');
    }

    /**
     * 登录初始化session
     * @param $user
     */
    private function setLogin($user)
    {
        $_SESSION['b_name'] = $user['name'];
        $_SESSION['b_user'] = $user['mobile'];
        $_SESSION['b_id'] = $user['id'];
        $_SESSION['b_status'] = $user['status'];
        $_SESSION['b_group_id'] = $user['group_id'];
        $_SESSION['b_uid'] = $user['uid'];
        //默认皮肤.
        $_SESSION['b_skin'] = '1';

    }

    /**
     * 找回密码
     * @param $user
     */
    function getpwd()
    {
        if (isset($_POST['Submit'])) {

            if (empty($_POST['mobile'])) exit($this->msg("手机号码不能为空"));
            if (empty($_POST['sms_code'])) exit($this->msg("手机验证码不能为空"));

            //短信验证码
            $verifycode = '';
            if (C('sms_open') == 1 && statusTpl('sms_business_getpwd')) {
                $this->load->library('sms');
                $verifycode = empty($_POST['sms_code']) ? '' : trim($_POST['sms_code']);

                /* 验证手机号验证码和IP */
                $sql = "SELECT id FROM ###_verify_code WHERE mobile='{$_POST['mobile']}' AND verifycode='$verifycode' AND status=7  AND dateline>(" . time() . "-3600)"; //验证码60分钟内有效
                $temp = $this->db->get($sql);
                if (!$temp) {
                    exit($this->msg("手机号码和验证码不匹配或者验证码已过期（1小时内有效）"));
                }
            }
            $token = createToken();
            exit($this->msg('', array("url" => "/business/login/updatepwd/" . $_POST['mobile'] . "/" . $token)));
        }
        $this->smarty->display('business/login/getpwd.html');
    }

    function updatepwd($mobile = '', $token = '')
    {
        $_REQUEST['token'] = $token;
        if (!checkToken()) exit($this->msg('', array("url" => "/business/login/getpwd")));;
        $this->smarty->assign("mobile", $mobile);
        $this->smarty->display('business/login/update_pwd.html');
    }

    function save()
    {
        if (isset($_POST['Submit'])) {
            $post = $_POST['post'];

            if (empty($post['pass1'])) exit($this->msg("新密码不能为空"));
            if (empty($post['pass2'])) exit($this->msg("确认密码不能为空"));
            if ($post['pass2'] != $post['pass1']) exit($this->msg("两次密码不一样"));

            $user = $this->db->get("SELECT  salt FROM ###_business WHERE mobile='" . $post['mobile'] . "'");
            $pwd = $this->user->get_salt_hash($post['pass1'], $user['salt']);

            $res = $this->db->update("business", array('password' => $pwd), array("mobile" => $post['mobile']));
            if (false !== $res) {
                exit($this->msg('修改密码成功', array("url" => "/business/login")));
            } else {
                exit($this->msg('操作失败'));
            }
        }
    }

}