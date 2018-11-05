<?php

class log_file {

	protected $config = array(
		'log_time_format' => ' c ',
		'log_file_size'   => 2097152,
		'log_path'        => '',
	);

	// 实例化并传入参数
	public function __construct($config = array()) {
		$this->config = array_merge($this->config, $config);
	}

	public function save($logsArr, $destination) {
		$destination .= date('y_m_d') . '.log';
		$tmp = array();
		foreach ($logsArr as $v) {
			$tmp[] = $v['level'] . ": " . $v['log'] . PHP_EOL;
		}
		$this->write(rtrim(join($tmp), PHP_EOL), '', $destination);
	}

	/**
	 * 日志写入接口
	 * @access public
	 * @param string $log 日志信息
	 * @param string $destination  写入目标
	 * @return void
	 */
	public function write($log, $level = '', $destination = '') {
		$now = date($this->config['log_time_format']);
		if (empty($destination)) {
			$destination = $this->config['log_path'] . date('y_m_d') . '.log';
		}
		// 自动创建日志目录
		$log_dir = dirname($destination);
		if (!is_dir($log_dir)) {
			mkdir($log_dir, 0755, true);
		}
		//检测日志文件大小，超过配置大小则备份日志文件重新生成
		if (is_file($destination) && floor($this->config['log_file_size']) <= filesize($destination)) {
			rename($destination, dirname($destination) . '/' . time() . '-' . basename($destination));
		}

		if (isset($_SERVER['HTTP_HOST'])) {
			$current_uri = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		} else {
			$current_uri = "cmd:" . implode(' ', $_SERVER['argv']);
		}

		$log = $level ? $level . ' ' . $log : $log;
		error_log('[' . $now . '] ' . $current_uri . PHP_EOL . $log . PHP_EOL . PHP_EOL, 3, $destination);
	}
}
