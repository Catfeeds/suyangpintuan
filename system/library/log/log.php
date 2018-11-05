<?php
/**
 * debug日志处理类
 *
 *  // debug日志配置
 *  $LOG_CONFIG = array(
 *  	// 'log_type' => 'seaslog',    有安装seaslog扩展的主机推荐使用这个
 *  	'log_type' => 'file', // 日志类型: file, seaslog
 *  	// 'log_level' => 'EMERG,ALERT,CRIT,ERR,WARN,NOTIC,INFO,DEBUG,SQL',  // 日志等级: 从左到右依次为8级日志,减少这里的内容时 log::record 不记录对应级别日志
 *  	'log_path' => RootDir . 'log/', // 日志根目录
 *  );
 *
 * 初始化日志配置
 * Log:init($LOG_CONFIG);
 *
 ** 用法示例
 *
 * // 直接写入
 * Log::write('EMERG', 'EMERG'); // 第一个参数 为log信息 第二个参数为等级
 *
 * // 缓存到内存 但不写入
 * Log::record('EMERG', 'EMERG'); // 严重错误: 导致系统崩溃无法使用
 * Log::record('ALERT', 'ALERT'); // 警戒性错误: 必须被立即修改的错误
 * Log::record('CRIT', 'CRIT'); // 临界值错误: 超过临界值的错误，例如一天24小时，而输入的是25小时这样
 * Log::record('ERR', 'ERR'); // 一般错误: 一般性错误
 * Log::record('WARN', 'WARN'); // 警告性错误: 需要发出警告的错误
 * Log::record('NOTIC', 'NOTIC'); // 通知: 程序可以运行但是还不够完美的错误
 * Log::record('INFO', 'INFO'); // 信息: 程序输出信息
 * Log::record('DEBUG', 'DEBUG'); // 调试: 调试信息
 * Log::record('SQL', 'SQL'); // 其他level  被默认识别为debug 注意只在调试模式开启时有效
 * // 把缓存日志一次性写入
 * Log::save();
 *
 */

// php5.2 不支持const  所以无法使用常量
class Log {
	// 日志级别 从上到下，由低到高
	const EMERG  = 'EMERG'; // 严重错误: 导致系统崩溃无法使用
	const ALERT  = 'ALERT'; // 警戒性错误: 必须被立即修改的错误
	const CRIT   = 'CRIT'; // 临界值错误: 超过临界值的错误，例如一天24小时，而输入的是25小时这样
	const ERR    = 'ERR'; // 一般错误: 一般性错误
	const WARN   = 'WARN'; // 警告性错误: 需要发出警告的错误
	const NOTICE = 'NOTIC'; // 通知: 程序可以运行但是还不够完美的错误
	const INFO   = 'INFO'; // 信息: 程序输出信息
	const DEBUG  = 'DEBUG'; // 调试: 调试信息
	const SQL    = 'SQL'; // SQL：SQL语句 注意只在调试模式开启时有效

	// 日志信息
	protected static $log = array();

	// 日志存储
	protected static $storage = null;

	public static $defaultConfig = array(
		'log_type'  => 'file',
		'log_level' => 'EMERG,ALERT,CRIT,ERR,WARN,NOTIC,INFO,DEBUG,SQL',
		'log_path'  => 'log/',
	);

	public static function required($class) {
		require_once dirname(__FILE__) . '/drivers/' . strtolower($class) . '.php';
	}

	// 日志初始化
	public static function init($config = array()) {
		self::$defaultConfig = array_merge(self::$defaultConfig, $config);
		$class               = self::$defaultConfig['log_type'];
		self::required($class);
		$class = 'log_' . $class;

		self::$storage = new $class(self::$defaultConfig);

		IS_DEV && self::registerErrorHandler();

	}

	/**
	 * 记录日志 并且会过滤未经设置的级别
	 * @static
	 * @access public
	 * @param string $message 日志信息
	 * @param string $level  日志级别
	 * @param boolean $record  是否强制记录
	 * @return void
	 */
	public static function record($message, $level = 'DEBUG', $record = false) {
		if ($record || false !== strpos(self::$defaultConfig['log_level'], $level)) {
			self::$log[] = array(
				'log'   => $message,
				'level' => $level,
			);
		}
	}

	/**
	 * 日志保存
	 * @static
	 * @access public
	 * @param integer $type 日志记录方式
	 * @param string $destination  写入目标
	 * @return void
	 */
	public static function save($type = '', $destination = '') {
		if (empty(self::$log)) {
			return;
		}

		if (empty($destination)) {
			$destination = self::$defaultConfig['log_path'];
		}
		if (!self::$storage) {
			$class = $type ? $type : self::$defaultConfig['log_type'];
			self::required($class);
			$class = 'log_' . $class;

			self::$storage = new $class();
		}

		self::$storage->save(self::$log, $destination);

		// 保存后清空日志缓存
		self::$log = array();
	}

	/**
	 * 日志直接写入
	 * @static
	 * @access public
	 * @param string $message 日志信息
	 * @param string $level  日志级别
	 * @param integer $type 日志记录方式
	 * @param string $destination  写入目标
	 * @return void
	 */
	public static function write($message, $level = 'DEBUG', $type = '', $destination = '') {
		if (!self::$storage) {
			$class              = $type ? $type : self::$defaultConfig['log_type'];
			$config['log_path'] = self::$defaultConfig['log_path'];
			self::required($class);
			$class = 'log_' . $class;

			self::$storage = new $class($config);
		}
		if (empty($destination)) {
			$destination = self::$defaultConfig['log_path'] . date('y_m_d') . '.log';
		}
		self::$storage->write($message, $level, $destination);
	}

	/**
	 * 接管报错
	 */
	public static function registerErrorHandler() {
		//set_error_handler(array(__CLASS__, 'error_handler'));
		register_shutdown_function(array(__CLASS__, 'fatalError'));
	}

	public static function error_handler($errno, $errstr, $errfile, $errline) {
		switch ($errno) {
			case E_WARNING:
				$severity = 'E_WARNING';
				break;
			case E_NOTICE:
				$severity = 'E_NOTICE';
				break;
			case E_USER_ERROR:
				$severity = 'E_USER_ERROR';
				break;
			case E_USER_WARNING:
				$severity = 'E_USER_WARNING';
				break;
			case E_USER_NOTICE:
				$severity = 'E_USER_NOTICE';
				break;
			case E_STRICT:
				$severity = 'E_STRICT';
				break;
			case E_RECOVERABLE_ERROR:
				$severity = 'E_RECOVERABLE_ERROR';
				break;
			case E_DEPRECATED:
				$severity = 'E_DEPRECATED';
				break;
			case E_USER_DEPRECATED:
				$severity = 'E_USER_DEPRECATED';
				break;
			case E_ERROR:
				$severity = 'E_ERR';
				break;
			case E_PARSE:
				$severity = 'E_PARSE';
				break;
			case E_CORE_ERROR:
				$severity = 'E_CORE_ERROR';
				break;
			case E_COMPILE_ERROR:
				$severity = 'E_COMPILE_ERROR';
				break;
			case E_USER_ERROR:
				$severity = 'E_USER_ERROR';
				break;
			default:
				$severity = 'E_UNKNOWN_ERROR_' . $errno;
				break;
		}
		$msg = $errstr . ' in ' . $errfile . ' on line ' . $errline;
		self::record($msg, $severity, true);
	}

	public static function fatalError() {
		if ($e = error_get_last()) {
			self::error_handler($e['type'], $e['message'], $e['file'], $e['line']);
		}
		self::save();
	}

}
