<?php

class log_seaslog {

	// 实例化并传入参数
	public function __construct($config = array()) {
		SeasLog::setBasePath($config['log_path']);
		// 设置 日志一级子目录 这里可以考虑给 fx_ 加上商户mid 号做隔离
		SeasLog::setLogger('SeasLog');
	}

	public function save($logsArr, $destination = '') {
		foreach ($logsArr as $v) {

			switch ($v['level']) {
				case 'EMERG':
					$level = 'SEASLOG_EMERGENCY';
					break;
				case 'ALERT':
					$level = 'SEASLOG_ALERT';
					break;
				case 'CRIT':
					$level = 'SEASLOG_CRITICAL';
					break;
				case 'ERR':
					$level = 'SEASLOG_ERROR';
					break;
				case 'WARN':
					$level = 'SEASLOG_WARNING';
					break;
				case 'NOTIC':
					$level = 'SEASLOG_NOTICE';
					break;
				case 'INFO':
					$level = 'SEASLOG_INFO';
					break;
				case 'DEBUG':
					$level = 'SEASLOG_DEBUG';
					break;
				default:
					$level = 'SEASLOG_DEBUG';
					break;
			}

			$this->write($level, $v['log']);
		}

	}

	/**
	 * 日志写入接口
	 * @access public
	 * @param string $log 日志信息
	 * @param string $destination  写入目标
	 * @return void
	 */
	public function write($log, $level = 'SEASLOG_DEBUG', $destination = '') {
		SeasLog::log($level, $log);
	}
}
