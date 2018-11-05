<?php

/**
 * ZZCMS 前后台父类控制器
 * ============================================================================
 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。
 * 网站地址: http://www.lnest.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 */
class Lowxp extends Lowxp_Controller {

    public $common;
    public $site_config;
    public $mod;

    #需要转https的页面
    public $httpsUrl = array();

    function __construct() {
        parent::__construct();
        if (!headers_sent()) {
            header('Content-Type:text/html; charset=utf-8');
        }

        $this->load->model('session');
        $config = $this->load->config('database');
        $this->load->database($config);
        include AppDir . 'config/constant.php';
        include AppDir . 'function/common.php';
        $this->load->library('base');
        $this->load->library('form');
        $this->load->model('lang');
        $this->load->model('setting');
        $lang = $this->setLanguage(); //设置语言

        $segments = Lowxp_Router::getInstance()->segments;
        if (!isset($segments[0])) {
            return;
        }
        $this->mod = $mod = $segments[0];
        $class = isset($segments[1]) ? $segments[1] : $segments[0];
        $this->class = $class;

        #强制跳转
        static $loaded;
        if (is_null($loaded)) {
            // 获取浏览器标识
            $this->load->library('useragent');
            $loaded = 1;
        }

        //控制器选择加载
        if ($mod == 'manage') {
            $this->setManage();
        }elseif ($mod=='business') {
            $this->setBusiness();
        }elseif($mod=='api'){
            $this->setWebsiteApi();
            $this->api_common();
            define('IS_APP',true);
        }else {
            $this->setWebsite();
            $this->smarty->assign('mod', $mod);
            $durl = isset($segments[1])?'/'.$segments[0].'/'.$segments[1]:'/'.$segments[0];
            $this->smarty->assign('durl',$durl);
            $this->home_common();

            //参数防注入
            $segments_old = $segments;
            SafeFilter($segments);
            $c = array_diff($segments_old, $segments);
            if (!empty($c)) {
                exit($this->msg());
            }
            //APP审核时用
            if(C('app_checking') && isset($_GET['sign'])){
                $mid = decrypt_de($_GET['sign']);
                $this->load->model('member');
                $member = $this->member->member_info($mid);
                $this->member->setLogin($member);
                $_SESSION['cart'] = S("cart_".$mid);
            }
            //前台载入smarty自定义语法.
            $this->smarty->registerPlugin('function', 'zz', array($this, '_callViewFunc'));
            //保存推荐人
            if (isset($_GET['inviter_id']) && intval($_GET['inviter_id'])) {
                zzcookie('inviter_id', intval($_GET['inviter_id']));
            }
            //保存助力ID
            if (isset($_GET['assist_id']) && intval($_GET['assist_id'])) {
                zzcookie('assist_id', intval($_GET['assist_id']));
            }
        }


        // fan 2016-04-01 start 微信授权登录 重构
        if (!in_array($mod, array('manage',"business", 'welcome'))  ) {
            //定义登录会员MID和USER
            if (isset($_SESSION['mid'])) {
                define('MID', $_SESSION['mid']);
                define('USER', $_SESSION['username']);
            }

            // 如果请求的不是manage  和 welcome 模块 则开始进去到前台页面
            // 初始化 微信 和手机版本 判断标识
            $is_wechat = 0;
            $is_mobile = 0;
            //微信访问
            if (IS_WECHAT  && C("wx_appid") && !defined('IS_APP')) {

                // 如果浏览器是微信
                if (empty($_SESSION['mid']) && empty($_SESSION['oauth'])) {
                    $this->load->model('wxapi');
                    // 获取微信信息
					if (C("reg_bind_sms") == 1) {
//只授权不自动注册会员
                        $openid = $this->wxapi->wxoauth("oauth");
					} else {
//授权并自动注册会员
                        $openid = $this->wxapi->wxoauth("reg");
                    }
                    zzcookie('openid', $openid);
                    // 微信登录
                    $this->wxapi->login($openid);
                    if (!defined("MID") && $_SESSION['mid']) {
                        define('MID', $_SESSION['mid']);
                        define('USER', $_SESSION['username']);
                    }
                }

                $is_wechat = 1;
                $this->smarty->assign('wechat', 1);

                //分享朋友圈配置
                $url = getUrl();
                $this->load->model("wxapi");
                $signPackage = $this->wxapi->wechat->getJsSign($url);
                $this->smarty->assign('signPackage', $signPackage);
                if (defined("MID")) {
					if (strpos($url, 'code=') && strpos($url, 'state=')) {
//首次授权登录会带code参数，去掉code
                        $url_query = parse_url($url, PHP_URL_QUERY);
                        parse_str($url_query, $arr_query);
                        unset($arr_query['code']);
                        unset($arr_query['state']);
                        $url_str = count($arr_query) > 0 ? "?" . http_build_query($arr_query) : '';
                        $baseurl = substr($url,0,strpos($url,'?'));
                        $url = $baseurl . $url_str;
                    }
                    if (strpos($url, '?')) {
                        $url = $url . "&inviter_id=" . MID;
                    } else {
                        $url = $url . "?inviter_id=" . MID;
                    }
                }

                $this->smarty->assign('share_url', $url);
            }

        }

        // fan 2016-04-01 end
                

        $this->smarty->assign('l', LANG_NAME);
        $this->smarty->assign('langid', LANG_ID);
        $this->smarty->assign('lang', $lang);
    }

    /**
     * 网站前台配置
     */
    private function setWebsite() {
        #终端模板
        $tpl = $this->setting->getTplByDevice();
        
        $this->load->smarty(array(
            'tplDir' => AppDir . 'views/' . LANG_NAME . '/' . $tpl,
            'compileDir' => RUNTIME_PATH . 'views_c/' . LANG_NAME . '/' . $tpl,
            'cacheDir'   => RUNTIME_PATH . 'cache/' . LANG_NAME . '/' . $tpl,
        ));

        #获取站点配置
        $this->site_config = $this->setting->value();
        $this->smarty->assign('site_config', $this->site_config);
        $this->load->model('flow');
        #关闭站点
        if($this->mod != 'welcome'){
            if(C('status_site')){
                echo $this->site_config['status_tip'];die;
            }
        }
    }

    /**
     * 设置后台登录模块.
     */
    private function setManage() {
        $this->load->smarty();

        //获取后台全局配置
        $this->common = $this->setting->value();
        $this->smarty->assign('common', $this->common);

        if ($_SERVER['request']['class'] == 'login') {
            return;
        }

        if (!isset($_SESSION['uid'])) {
            if (isset($_REQUEST['ajax'])) {
                $this->exeJs('location.href="/manage/login";');
            } else {
                header('Location: /manage/login');
            }
            exit;
        }
        define('UID', $_SESSION['uid']); #当前用户ID
        define('USER', $_SESSION['uname']); #当前账号名
        define('GID', $_SESSION['gid']); #当前账号名
        define('SKIN', $_SESSION['skin']);
        define('SID', 0);
        $this->manage_common();
    }

    /**
     * 设置语言
     */
    private function setLanguage() {
        $lang = array(
            0 => array(
                'id' => 1,
                'name' => '中文',
                'mark' => 'cn',
                'listorder' => 1,
                'listorder' => 1,
                'status' => 1,
            ),
        );
        define('LANG_NAME', 'cn');
        define('LANG_ID', 1);
        return $lang;
    }

    //前台公共
    function home_common() {
        include AppDir . 'function/main_web.php';
        include AppDir . 'includes/languages/common.php';

        //php防注入和XSS攻击通用过滤.
        if($this->class!='chang_parent'){
            $_GET && SafeFilter($_GET);
            $_POST && SafeFilter($_POST);
            $_COOKIE && SafeFilter($_COOKIE);
            $_REQUEST && SafeFilter($_REQUEST);
        }



        #单点登陆、更新在线日志
        //$this->session->init();
        // fan 2016-05-17 start
        // 这步导致前台刷新任何页面均需要获取 购物车列表
        // fan 2016-05-17 end
        #获取购物车
        /* $cart_goods = $this->flow->cart_goods();

          $this->cart_goods = $cart_goods;
*/
        //从cookie取得购物车数量 Feng 2016-05-30
        //$cartNum = cookie('cartNum');
        //$cartNum = isset($_SESSION['cartNum']) ? $_SESSION['cartNum'] : 0;
        //$this->smarty->assign('cartNum', $cartNum);
        
        //底部导航
        if (!S("CH_NAV_B")) {
            //底部导航
            $sql = "select * from ###_nav where type=4 and status=1 order by listorder asc,id asc";
            $nav_list = $this->db->select($sql);
            S("CH_NAV_B", $nav_list);
        }
        
        $this->smarty->assign('nav_b', S("CH_NAV_B"));
    }

    //前台display前的数据处理
    function display_before($row = array()) {
        /* seo内容 */
        $seo = array();

        #分类栏目
        if ((!isset($row['title']) || empty($row['title'])) && isset($row['catname'])) {
            $row['title'] = $row['catname'];
        }

        if (isset($row['title']) && !empty($row['title'])) {
            $seo['title'] = $row['title'];
        } else {
            $seo['title'] = C('seo_title');
        }
        $seo['head'] = $seo['title'];
        if (!empty($seo['title'])) {
            //$seo['title'] = $this->site_config['site_name'] . '_' . $seo['title'];
            $seo['title'] =  $seo['title'];
        }

        if (isset($row['keywords']) && !empty($row['keywords'])) {
            $seo['keywords'] = $row['keywords'];
        } else {
            $seo['keywords'] = C('seo_keywords');
        }

        if (isset($row['description']) && !empty($row['description'])) {
            $seo['description'] = $row['description'];
        } else {
            $seo['description'] = C('seo_description');
        }

        $this->smarty->assign('seo', $seo);
        /* end */
    }

    //后台公共
    function manage_common() {
        #后台加载
        include AppDir . 'function/main_manage.php';
        include AppDir . 'function/queue.php';
        include AppDir . 'includes/languages/common.php';
		require_once AppDir . 'function/queue.php';
        $this->load->model('menus');
        
        //访客权限判断
        $data = $this->menus->vor();
        $this->smarty->assign('vor',$_SESSION['vor']);
        if($data['error']){
            $this->tip($data['error'],array('type'=>2,'iniframe'=>isset($data['iniframe'])?$data['iniframe']:false));die;
        }
        
        #权限节点判断
        $res = $this->menus->menus_node();
        if (isset($res['code']) && $res['code'] > 0) {
            $this->tip($res['msg'], array('inIframe' => true, 'type' => 2));
            //$this->exeJs("history.back();");
            die;
        }

        #全局action
        $action = isset($_POST['action']) ? trim($_POST['action']) : '';
        if ($action == 'order') {
            #批量排序
            $post = $_POST['listorders'];
            $table = trim($_POST['table']);
            $field = $_POST['field'];
            $key = isset($_POST['key']) ? $_POST['key'] : 'id';

            if (!empty($post)) {
                $res = $this->db->uporder($post, $table, $field, $key);
                if ($res) {
                    $this->tip('更新排序成功');
                } else {
                    $this->tip('排序失败', array('type' => 1));
                }
            }
            die;
        } elseif ($action == 'status') {
            #更新状态
            $id = (int) $_POST['id'];
            $table = $_POST['table'];
            $field = $_POST['field'] ? $_POST['field'] : 'status';
            $key = $_POST['key'] ? $_POST['key'] : 'id';

            if ($id && $table) {
                $res = $this->db->get("select $field from ###_$table where $key=$id");
                $set[] = $field . '=' . (($res[$field] == 1) ? 0 : 1);

                #多语言状态处理
                if ($table == 'lang') {
                    $lang = $this->lang->select();
                    if ($res[$field] == 1 && count($lang) == 1) {
                        $this->tip('多语言至少开启一个！', array('type' => 1));
                        die;
                    }
                }

                #会员状态处理
                if ($table == 'member' && $field == 'status') {
                    $this->db->delete('member_login_log', array('mid' => $id));
                }

                #晒单状态处理，第一次审核时送夺宝币
                //if($table == 'share') die;
                if ($table == 'share' && $field == 'is_show' && $res[$field] == 0) {
                    $res_share = $this->db->get("select * from ###_$table where $key=$id");
                    if ($res_share['is_points'] == 0) {
                        if ($this->common['share_db']) {
                            $this->load->model('member');
                            $this->member->accountlog(
                                    'admin', array(
                                'mid' => $res_share['mid'],
                                'db_points' => (int) $this->common['share_db'],
                                'rank_points' => (int) $this->common['share_db'] * 10,
                                'desc' => '发布晒单审核通过获得奖励',
                                    )
                            );
                        }
                        $set[] = 'is_points=1';
                    }
                }

                //财务确认
                if ($table == 'goods_order' && $field == 'ismoney' && !in_array(UID, array(2))) {
                    $this->tip('您无权限进行财务确认！', array('type' => 1));
                    die;
                }
                //商品下架同步下架专题商品
                if($table=='goods'){
                    $res_save = $this->db->update($table, implode(',', $set), "$key=$id");
                    if($res_save){
                        $this->db->update("topic_goods",array("status"=>0),array("goods_id"=>$id));
                    }
                    die;
                }

                $res_save = $this->db->update($table, implode(',', $set), "$key=$id");
            }
            die;
        } elseif ($action == 'temp_cache') {
            #清除模板缓存
            $this->smarty->clearAllCache();
            $this->tip('模板缓存清除成功');
            die;
        } elseif ($action == 'backmap') {
            #后台地图
            $menus_map = $this->menus->menus_node(false);
            $this->smarty->assign('menus_map', $menus_map);
            $content = $this->smarty->fetch('manage/public_map.html');
            echo $content;
            die;
        }

        //按钮菜单
        $this->smarty->assign('btnMenu', isset($this->btnMenu) ? $this->btnMenu : array());
        $this->smarty->assign('btnNo', 0);
    }

    /**
     * 操作提示信息框。
     * @param $message
     * @param array $options
     *  type:0正确,1提示,2错误
     *  hideWin:隐藏弹出窗
     *  inIframe:当前的javascript是否在iframe中执行
     *  hideoverlay:是否隐藏当前半透明层
     *  time:当前提示信息在几秒后消失.
     */
    function tip($message, $options = array('type' => 0, 'hideWin' => false, 'inIframe' => false)) {
        //type:0操作成功,1操作失败
        $defaultOpt = array(
            'type' => 0,
            'hideWin' => false,
            'time' => 3,
            'inIframe' => false,
            'hideoverlay' => false,
        );
        $options = array_merge($defaultOpt, $options);
        $optJson = json_encode($options);

        foreach ($options as $k => $v) {
            $options[strtolower($k)] = $v;
        }

        $parent = ($options['iniframe'] ? 'parent.' : '');
        echo '<script type="text/javascript">';
        echo $parent . 'com.xtip("' . $message . '",' . $optJson . ');';
        if ($options['hidewin']) {
            echo $parent . 'com.xhide();';
        }

        echo '</script>';
    }

    /**
     * 前台提示(layer插件)
     * icon 0失败1成功,
     * type alert:提示 confirm:确认框
     */
    function msg($message = '', $options = '') {

        if (empty($message) && (empty($options) || !is_array($options))) {
            $url = $options ? $options : '/';
            echo $this->exeJs("location.href='$url';");
            exit;
        } else {

            // fan 2016-05-27 start
            // 性能优化
            $defaultOpt = array(
                'link' => array(),
                'type' => 'alert',
                'iniframe' => true,
                'icon' => 0,
                'url' => '',
            );
            if (empty($options)) {
                $options = array();
            }

            $options = array_merge($defaultOpt, $options);
            extract($options);
            // fan 2016-05-27 end
        }

        if ($url == 'back') {
            $url = 'javascript:history.go(-1)';
        }

        if ($url == 'reload') {
            $url = 'javascript:history.go(0)';
        }

        $parent = ($iniframe ? 'parent.' : '');
        //框架内信息提示
        if ($parent) {
            //URL跳转
            $fun = $url ? ",function(){" . $parent . "location.href='" . $url . "'}" : '';

            if ($url && empty($message)) {
                $js = $parent . "location.href='" . $url . "'";
            } elseif ($type == 'alert') {
                if ($parent) {
                    $icon = "{offset: '150px',icon:$icon}";
                    $js = $parent . 'layer.alert("' . $message . '",' . $icon . $fun . ');';
                } else {
                    $js = $parent . 'alert("' . $message . ');';
                }
            } elseif ($type == 'confirm') {
                if ($parent) {
                    $js = $parent . 'layer.confirm("' . $message . '", {offset: "150px" }' . $fun . ');';
                } else {
                    $js = $parent . 'confirm("' . $message . ');';
                }
            }
            echo $this->exeJs($js);
        } else {
            $this->smarty->assign('link', $link);
            $this->smarty->assign('icon', $icon);
            $this->smarty->assign('url', $url);
            $this->smarty->assign('message', $message);
            $this->smarty->display('msg.html');
        }
    }

    function refresh($time = 3, $isParent = false) {
        $code = 'location.href=location.href;';
        if ($isParent) {
            $code = "parent.location.href=parent.location.href;";
        }

        $this->exeJs('setTimeout(function(){' . $code . '},' . ($time * 1000) . ')');
    }

    /**
     * 面包屑
     */
    function ur_here($str, $params = array()) {
        $sign = ' > ';
        $ur_here = '';
        if (!empty($params)) {
            foreach ($params as $v) {
                $ur_here .= "<font class='st'> $sign </font><a href='$v[url]' style='$v[style]'>$v[name]</a>";
            }
        }
        if ($str) {
            $ur_here .= "<font class='st'> $sign </font><em>$str</em>";
        }
        $this->smarty->assign('ur_here', $ur_here);
    }

    /**
     * 致命错误提示
     */
    function fatalError($message) {
        $this->tip($message, array('type' => 1));
        exit;
    }

    /**
     * 配合前端调用
     * @param $javascript_code
     */
    public function exeJs($javascript_code) {
        echo '<script type="text/javascript">' . $javascript_code . '</script>';
    }

    /**
     * smarty模板调用
     */
    function _callViewFunc($arguments, $smarty) {
        if (!is_array($arguments)) {
            return null;
        }

        if (isset($arguments['mod'])) {
            $mod = '_' . $arguments['mod'];
            $this->load->model('taglib');
            $this->taglib->smarty = $smarty;
            if (!method_exists($this->taglib, $mod)) {
                trigger_error("zzTag: 'mod' parameter is not correct ");
                return null;
            }
            return call_user_func(array($this->taglib, $mod), $arguments);
        }
        return null;
    }

    /**
     * 初始化加载前端smarty模板。
     */
    function initialize_handle() {
        
    }
    /**
     * 所有主流程结束后执行本操作
     */
    function afterload_handle() {
        if (function_exists('fastcgi_finish_request')) {
            // 提高页面响应
            fastcgi_finish_request();
        }
        // 主业务流程全部结束以后, 批量处理延时集中处理 todo 转到MQ执行
        $queueDealCacheName = 'BACKGROUND_ACTION_DEALED';
        if (!S($queueDealCacheName)) {
            S($queueDealCacheName, 1, 180);
            $this->load->model('template_msg');
            $this->template_msg->dealQueue(100);
            $this->load->model('wxtemplate');
            $this->wxtemplate->dealQueue(100);
            //自动确认收货
            $this->load->library("task");
            $this->task->run();
        }

    }
    /**
     * 统一的跳转提示方法

     * 操作错误跳转的快捷方法
     * @access protected
     * @param string  $message 错误信息
     * @param string  $jumpUrl 页面跳转地址
     * @param mixed   $ajax 是否为Ajax方式 当数字时指定跳转时间
     * @param integer $error   错误代码
     * @return void
     */
    protected function error($message = '', $jumpUrl = '', $ajax = false, $error = 1) {
        $this->dispatchJump($message, $jumpUrl, $ajax, $error);
    }

    /**
     * 操作成功跳转的快捷方法
     * @access protected
     * @param string  $message 提示信息
     * @param string  $jumpUrl 页面跳转地址
     * @param mixed   $ajax    是否为Ajax方式 当数字时指定跳转时间
     * @param integer $error   错误代码
     * @return void
     */
    protected function success($message = '', $jumpUrl = '', $ajax = false, $error = 0) {
        $this->dispatchJump($message, $jumpUrl, $ajax, $error);
    }

    /**
     *  标准 ajax 格式 array('error' => 0, 'msg'=> '成功', 'url'=> '', 'data' => array('所有附加信息都在这里'));
     * Ajax方式返回数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type AJAX返回数据格式
     * @param int $json_option 传递给json_encode的option参数
     * @return void
     */
    protected function ajaxReturn($data, $type = '', $json_option = 0) {
        switch (strtoupper($type)) {
            case 'XML':
                // 返回xml格式数据
                header('Content-Type:text/xml; charset=utf-8');
                exit(xml_encode($data));
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                $handler = isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
                exit($handler . '(' . json_encode($data, $json_option) . ');');
            case 'EVAL':
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($data);
            default:
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode($data, $json_option));
        }
    }

    /**
     * Action跳转(URL重定向） 支持指定模块和延时跳转
     * @access protected
     * @param string $url 跳转的URL表达式
     * @param array $params 其它URL参数
     * @param integer $delay 延时跳转的时间 单位为秒
     * @param string $msg 跳转提示信息
     * @return void
     */
    protected function redirect($url, $params = array(), $delay = 0, $msg = '') {
        // $url = getUrl($url, $params);
        redirect($url, $delay, $msg);
    }

    /**
     * 默认跳转操作 支持错误导向和正确跳转
     * 调用模板显示 默认为public目录下面的success页面
     * 提示页面为可配置 支持模板标签
     * @param string  $message 提示信息
     * @param string  $jumpUrl 页面跳转地址
     * @param mixed   $ajax    是否为Ajax方式 当数字时指定跳转时间
     * @param Boolean $error   0成功 1以上 错误代码
     * @access private
     * @return void
     */
    private function dispatchJump($message, $jumpUrl = '', $ajax = false, $error = 0) {

        $data = is_array($ajax) ? $ajax : array();
        $data['msg'] = $message;
        $data['error'] = $error;
        $data['url'] = $jumpUrl;

        if (true === $ajax || IS_AJAX) {
            // AJAX提交
            $this->ajaxReturn($data);
        }

        if ($error) {
            $tpl = 'common/error.html';
            $data['metaTitle'] = '失败！';
            $data['waitSecond'] = 3;
            if (empty($jumpUrl)) {
                //$data['url'] = 'javascript:history.back(-1);';
                $data['url'] = $_SERVER["HTTP_REFERER"];
            }
        } else {
            $tpl = 'common/success.html';
            $data['metaTitle'] = '成功！';
            $data['waitSecond'] = 1;
            if (empty($jumpUrl)) {
                $data['url'] = $_SERVER["HTTP_REFERER"];
            }
        }

        $data['waitSecond'] = is_int($ajax) ? $ajax : $data['waitSecond'];

        //如果设置了关闭窗口，则提示完毕后自动关闭窗口
        if (!empty($_GET['closeWin'])) {
            $jumpUrl = 'javascript:window.close();';
        }

        //保证输出不受静态缓存影响
        $this->smarty->caching = false;
        $this->smarty->assign('data', $data);
        $this->smarty->display($tpl);
        exit;
    }
        
    /**
     * API返回
     */
    function api_result($result){
        $result['flag'] = !empty($result['code']) ? false : true;
        $result['code'] = !empty($result['code']) ? $result['code'] : 0;
        $result['msg'] = !empty($result['msg']) ? $result['msg'] : '操作成功';
        $result['data'] = !empty($result['data']) ? $result['data'] : null;
        $result['time'] = RUN_TIME;
        header('Content-Type:application/json; charset=utf-8');
        die(json_encode($result));
    }
    
    /**
     * 网站前台配置(API)
     */
    private function setWebsiteApi(){
        $this->load->smarty();

        #获取站点配置
        $this->site_config = $this->setting->value();
        $this->smarty->assign('site_config',$this->site_config);

        #关闭站点
        if($this->mod != 'welcome'){
            if($this->site_config['status_site']){
                echo $this->site_config['status_tip'];die;
            }
        }
    }
    //API公共
    function api_common(){
		include AppDir . 'function/main_web.php';
		include AppDir . 'includes/languages/common.php';

        //php防注入和XSS攻击通用过滤.
        $_GET     && SafeFilter($_GET);
        $_POST    && SafeFilter($_POST);
        $_COOKIE  && SafeFilter($_COOKIE);
        $_REQUEST && SafeFilter($_REQUEST);


        #验证KEY
		$key = isset($_SERVER['HTTP_APPKEY']) ? trim($_SERVER['HTTP_APPKEY']) : '';
        //$app = $this->db->get("SELECT * FROM ###_app WHERE secretkey = '$key'");
        define('ISAPI',1);
		if (isset($_SERVER['HTTP_UID']) && intval($_SERVER['HTTP_UID'])) {
            $_SESSION['mid'] = intval($_SERVER['HTTP_UID']);
            $_SESSION['username'] = trim($_SERVER['HTTP_UNAME']);
            $upsw = trim($_SERVER['HTTP_UPSW']);
            if(!empty($upsw)){
                $this->load->model('member');
                $username = $this->db->get("SELECT username FROM ###_member as A JOIN ###_member_detail AS B ON A.mid=B.mid WHERE A.mid='$_SESSION[mid]' AND A.`status`=1 AND B.password = '$upsw'");
            }else{
                $sql = "SELECT username FROM ###_member WHERE mid = '$_SESSION[mid]' AND status = 1";
                $username= $this->db->getstr($sql);
            }
            if(empty($username)){
                unset($_SESSION['mid']);
                unset($_SESSION['username']);
            }
            unset($username);
        }
        #验证小程序TOKEN
        if(!empty($_SERVER['HTTP_TOKEN'])){
            $token = $_SERVER['HTTP_TOKEN'];
            $verifyToken = S($token);
			if (empty($verifyToken)) {
				$this->api_result(array('code' => '40001', 'msg' => '授权失效'));
			}
			if (empty($verifyToken['mid']) && $_SERVER['request']['class'] != 'login') {
				$this->api_result(array('code' => '40001', 'msg' => 'MID缺失'));
			}
            $_SESSION['mid'] = $verifyToken['mid'];
            $_SESSION['username'] = $verifyToken['username'];
            //更新有效期
            if($verifyToken['expires']<RUN_TIME-1800){
                $expires = RUN_TIME + 3600 * 24;
                $verifyToken['expires'] = $expires;
                S($token, $verifyToken, $expires);
            }
        }
        //if(empty($app)) $this->api_result(array('code'=>'100001','msg'=>'非法授权'));
    }
    
    /**
     * 设置商家登录模块.
     */
    private function setBusiness() {
        $this->load->smarty();
        
        //获取后台全局配置
        $this->common = $this->setting->value();
        $this->smarty->assign('common', $this->common);

        if ($_SERVER['request']['class'] == 'login') {
            return;
        }

        if (!isset($_SESSION['b_id'])) {
            
            if (isset($_REQUEST['ajax'])) {
                $this->exeJs('location.href="/business/login";');
            } else {
                header('Location: /business/login');
            }
            exit;
        }elseif($_SESSION['b_status']!=1){
            $_SESSION['b_status'] = $this->db->getstr("select status from ###_business where id={$_SESSION['b_id']}");
            if($_SESSION['b_status']!=1){
                header('Location: /content/applythree');
            }
        }
		define('BID', isset($_SESSION['b_id']) ? $_SESSION['b_id'] : ''); #当前用户ID
		define('USER', isset($_SESSION['b_user']) ? $_SESSION['b_user'] : ''); #当前账号名
		define('SKIN', isset($_SESSION['skin']) ? $_SESSION['skin'] : '');
		define('SID', isset($_SESSION['b_id']) ? $_SESSION['b_id'] : '');
        define('BGID', isset($_SESSION['b_group_id']) ? $_SESSION['b_group_id'] : '0'); #当前账号名
        $this->business_common();
    }
    
    //商家中心公共
    function business_common() {
        #后台加载
        include AppDir . 'function/main_manage.php';
        include AppDir . 'function/queue.php';
        include AppDir . 'includes/languages/common.php';

        $this->load->model("menus_sid");
        $res = $this->menus_sid->menus_node();
        if (isset($res['code']) && $res['code'] > 0) {
            $this->tip($res['msg'], array('inIframe' => true, 'type' => 2));
            die;
        }

        #全局action
        $action = isset($_POST['action']) ? trim($_POST['action']) : '';
        if ($action == 'order') {
            #批量排序
            $post = $_POST['listorders'];
            $table = trim($_POST['table']);
            $field = $_POST['field'];
            $key = isset($_POST['key']) ? $_POST['key'] : 'id';

            if (!empty($post)) {
                $res = $this->db->uporder($post, $table, $field, $key);
                if ($res) {
                    $this->tip('更新排序成功');
                } else {
                    $this->tip('排序失败', array('type' => 1));
                }
            }
            die;
        } elseif ($action == 'status') {
            #更新状态
            $id = (int) $_POST['id'];
            $table = $_POST['table'];
            $field = $_POST['field'] ? $_POST['field'] : 'status';
            $key = $_POST['key'] ? $_POST['key'] : 'id';

            if ($id && $table) {
                $res = $this->db->get("select $field from ###_$table where $key=$id");
                $set[] = $field . '=' . (($res[$field] == 1) ? 0 : 1);

                #多语言状态处理
                if ($table == 'lang') {
                    $lang = $this->lang->select();
                    if ($res[$field] == 1 && count($lang) == 1) {
                        $this->tip('多语言至少开启一个！', array('type' => 1));
                        die;
                    }
                }

                #会员状态处理
                if ($table == 'member' && $field == 'status') {
                    $this->db->delete('member_login_log', array('mid' => $id));
                }

                $res_save = $this->db->update($table, implode(',', $set), "$key=$id");
            }
            die;
        }
        
        //按钮菜单
        $this->smarty->assign('btnMenu', isset($this->btnMenu) ? $this->btnMenu : array());
        $this->smarty->assign('btnNo', 0);
    }

}
