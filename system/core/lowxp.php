<?php

if (!defined('App')) {
	die('Access Deny!');
}

// 检测PHP环境
if (version_compare(PHP_VERSION, '5.3.0', '<')) {
	die('require PHP > 5.3.0 !');
}

define('CMS_START_TIME', microtime(true));
define('CMS_START_MEM', memory_get_usage());

define('SYSTEM_TYPE_NAME', "pintuan");

define('IS_AJAX', ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || !empty($_POST['ajax']) || !empty($_GET['ajax'])) ? true : false);

define('IS_WECHAT', isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') ? true : false);

define('IS_CLI', 'cli' === PHP_SAPI ? true : false);

define('REQUEST_SCHEME', is_ssl() ? 'https://' : 'http://'); // 设置整站协议为http  如果需要https  或者做判断 请修改这里的值

$_HOST = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';

$tmpHost = parse_url(REQUEST_SCHEME . $_HOST);

empty($tmpHost['host']) && die('url parse failed');

define('Domain', $tmpHost['host']);

//$_HOST = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : ''; #域名不带端口号

define('IS_LOCAL', strpos(Domain, '.local') !== false);

define('RootDir', str_replace('system/core/lowxp.php', '', str_replace('\\', '/', __FILE__)));

define('WebDir', RootDir . 'web/');

define('SysDir', RootDir . 'system/');

define('AppDir', RootDir . 'webapps/' . App . '/');

define('SysCfg', RootDir . 'system/config/'); #系统公共配置路径

define('RUNTIME', AppDir . 'data/');

include AppDir . 'config/config.php';
// 引入缓存  配置在 AppDir config/config.php 内部
include SysDir . 'library/phpfastcache/phpfastcache.php';
// 缓存初始化 缓存配置 可以在 app配置中修改 所以加载配置以后初始化缓存
$CACHE_CONFIG = isset($CACHE_CONFIG) ? $CACHE_CONFIG : array();
phpFastCache::setup($CACHE_CONFIG);

// 引入日志
include SysDir . '/library/log/log.php';

// 引入加密类库
include SysDir . '/library/crypt.php';

$LOG_CONFIG = isset($LOG_CONFIG) ? $LOG_CONFIG : array();
Log::init($LOG_CONFIG);

include AppDir . 'config/version.php'; #版本号
include RootDir . 'system/core/common.php';

//路由
$Router = &loadClass('router', 'core');

//包含路由配置
$_RouteFile = AppDir . 'config/route.php';

if (is_file($_RouteFile)) {
	include $_RouteFile;
}

$requestUri = $Router->getRequestUrl();

$controller_file = $Router->location($requestUri);

$class = Lowxp_Router::$class;

$method = Lowxp_Router::$method;

$params = Lowxp_Router::$params;

//加载器
$Loader = &loadClass('loader', 'core');

#$Loader->config('autoload');
//错误处理
#$Error = &load_class('error','core');

/**
 * 执行路由返回的类方法
 */

//包含控制器基础类

require RootDir . 'system/core/controller.php';

if (is_file(AppDir . 'controller/controller.php')) {

	require AppDir . 'controller/controller.php';

}

//包含模型基础类

require RootDir . 'system/core/model.php';

//file_not_exist or exit!

if (!is_file($controller_file)) {
	URL_404();
}

include $controller_file;

$className = $class . ClassExt;

if (!class_exists($className)) {
	ShowError('Controller ' . $class . " Not Exists!");
}

$lowxp = new $className;

/**

 * 返回控制器实例

 * @return Lowxp_Controller

 */

function &get_instance() {

	return Lowxp_Controller::get_instance();

}

//执行调用

if (method_exists($lowxp, $method)) {
	if (is_callable(array($lowxp, $method))) {

		if (method_exists($lowxp, initializeFunc)) {
			$lowxp->{initializeFunc}();
		}

		//调用前执行

		if (method_exists($lowxp, PreloadFunc)) {
			$lowxp->{PreloadFunc}();
		}

		//调用当前访问方法

		call_user_func_array(array($lowxp, $method), $params);

		//调用后执行
        $AfterloadFunc = defined('AfterloadFunc') && AfterloadFunc ? AfterloadFunc : 'afterload_handle';
		if (method_exists($lowxp, $AfterloadFunc)) {
			$lowxp->{$AfterloadFunc}();
		}

	} else {

		URL_404();

	}

} else {

	URL_404();

}

// 判断https
function is_ssl() {
	if (isset($_SERVER['HTTPS']) && ('1' == $_SERVER['HTTPS'] || 'on' == strtolower($_SERVER['HTTPS']))) {
		return true;
	} elseif (isset($_SERVER['REQUEST_SCHEME']) && 'https' == $_SERVER['REQUEST_SCHEME']) {
		return true;
	} elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
		return true;
	} elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && 'https' == $_SERVER['HTTP_X_FORWARDED_PROTO']) {
		return true;
	}
	return false;
}

/**读缓存文件SID*/

function read_sid($cache_name, $dir = 'sid') {

	static $resultAuth = array();

	if (!empty($resultAuth[$cache_name])) {

		return $resultAuth[$cache_name];

	}

	$cache_file_path = AppDir.'data/' . ($dir ? $dir . '/' : '') . $cache_name . '.php';

	if (file_exists($cache_file_path)) {

		include $cache_file_path;

		$result[$cache_name] = $data;

		return $result[$cache_name];

	} else {

		return false;

	}

}

/**读缓存文件*/

function read_static_cache($cache_name, $dir = 'static') {

	static $resultAuth = array();

	if (!empty($resultAuth[$cache_name])) {

		return $resultAuth[$cache_name];

	}

	$cache_file_path = RUNTIME_PATH  . ($dir ? $dir . '/' : '') . $cache_name . '.php';

	if (file_exists($cache_file_path)) {

		include $cache_file_path;

		$result[$cache_name] = $data;

		return $result[$cache_name];

	} else {

		return false;

	}

}

/**写缓存文件*/

function write_static_cache($cache_name, $caches, $dir = 'static') {

	$cache_dir = RUNTIME_PATH . ($dir ? $dir . '/' : '');

	if (!is_dir($cache_dir)) {
		mkdir($cache_dir, 0777, true);
	}

	$cache_file_path = $cache_dir . $cache_name . '.php';

	$content = "<?php ";

	$content .= "\$data = " . var_export($caches, true) . ";";

	$content .= " ?>";

	file_put_contents($cache_file_path, $content, LOCK_EX);

}