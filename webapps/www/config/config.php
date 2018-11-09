<?php
//设置时区
date_default_timezone_set('Asia/Shanghai');

//程序开始运行的时间
define('RUN_TIME', isset($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time());

//配置域名
// fan 2016-04-01 start
//按需 设置开发者模式
define('IS_DEV', true);

if (IS_LOCAL) {
	//本地
	//define('Domain', $_HOST); // 域名不同的话 后台无法登录
	define('RootUrl',REQUEST_SCHEME.Domain.'/' );
} else {
	//服务器
	//define('Domain', $_HOST);
	define('RootUrl',REQUEST_SCHEME.Domain.'/' );
}
if (IS_DEV) {
	// fan 2016-04-01 start
	// 错误提示转移到config内部
	ini_set('display_errors', 'On');
	error_reporting(E_ALL ^ E_NOTICE);
	// fan 2016-04-01 end
} else {
	error_reporting(0);
}
// fan 2016-04-01 end

//配置目录
define('CfgDir', AppDir . 'config/');
//控制器目录
define('ControllerDir', AppDir . 'controller/');

//配置常量
define('DefaultController', 'home');
define('DefaultAction', 'index');
define('ClassExt', '');
define('PreloadFunc', '');
define('initializeFunc', 'initialize_handle');
define('AfterloadFunc', '');

// 日志配置
$LOG_CONFIG = array(
	// 'log_type' => 'seaslog',
	'log_type' => 'file', // 日志类型: file, seaslog
	// 'log_level' => 'EMERG,ALERT,CRIT,ERR,WARN,NOTIC,INFO,DEBUG,SQL',  // 日志等级: 从左到右依次为8级日志,减少这里的内容时 log::record 不记录对应级别日志
	'log_path' => RUNTIME . 'log/', // 日志根目录
);

// fan 2016-03-23 start
// 配置缓存
// 去掉下放的注释开始本机redis
$CACHE_CONFIG = array(
	//'storage'       => 'redis',
	'storage'       => 'files',
	'fallback'      => 'files', // 开启redis  必须开启这个
	'default_chmod' => 0777, // 0777 , 0666, 0644
	'securityKey'   => 'auto',
	'htaccess'      => false,
	'path'          => RUNTIME. 'cache', // 如果配置此项 请务必选择可写目录
	'redis'         => array(
            'host'     => '127.0.0.1',
            'port'     => '6379',
            'password' => '',
            'database' => '',
            'timeout'  => '',
	),
	'extensions'    => array(),
);
// fan 2016-03-23 end

define('AuthKey', 'sbN0aFLkxYIl2s6W1NPV'); #授权码