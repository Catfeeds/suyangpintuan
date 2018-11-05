<?php

class Queue_file {
	private $data = array(); // 队列数组 内容
	private $file; // 资源容器
	public $name; // 文本队列文件名
	public $fullName;

	public $put_save = true;

	public function __construct(array $config = array()) {

		$this->name = empty($config['key']) ? 'file_queue' : $config['key'];

		$this->fullName = RUNTIME_PATH . 'queue/' . $this->name . '.qu';
		if (!file_exists($this->fullName)) {
			// 自动创建日志目录
			$log_dir = dirname($this->fullName);
			if (!is_dir($log_dir)) {
				mkdir($log_dir, 0755, true);
			}
			try {
				touch($this->fullName);
			} catch (Exception $e) {
				IS_DEV && Log::write($this->fullName . '创建文件队列失败');
			}
		}

		try {
			$this->file = fopen($this->fullName, 'r+');
		} catch (Exception $e) {
			IS_DEV && Log::write($this->fullName . ' 文件打开失败');
			return false;
		}

	}

	/**
	 * 加载队列
	 */
	public function load() {
	    clearstatcache();
		$filesize = filesize($this->fullName);
		if ($filesize) {
			rewind($this->file);
			$content    = trim(fread($this->file, $filesize));
			$this->data = json_decode($content, true);
		}

		$this->data = $this->data ?: array();
	}

	/**
	 * 保存队列
	 * @return boolean 写入成功与否
	 */
	public function save() {
		ftruncate($this->file, 0);
		rewind($this->file);
		return fwrite($this->file, json_encode($this->data));
	}

	/**
	 * 入队
	 * @return boolean 写入成功与否
	 */
	public function put($param) {
		if (flock($this->file, LOCK_EX)) {
			$this->load();
			$this->data[] = $param;
			$this->save();
			flock($this->file, LOCK_UN);
		}
	}

	/**
	 * 向队列中插入一串信息
	 * @param $message
	 * @return mixed
	 */
	public function puts() {
		if (flock($this->file, LOCK_EX)) {
			$this->load();
			$params = func_get_args();

			foreach ($params as $v) {
				$this->data[] = $v;
			}
			$this->save();
			flock($this->file, LOCK_UN);
		}
	}

	/**
	 * 出队
	 * @return mixed 获取到的数据
	 */
	public function get() {
		if (flock($this->file, LOCK_EX)) {
			$this->load();
			$res = array_shift($this->data);
			$this->save();
			flock($this->file, LOCK_UN);
			return $res;
		}
	}

	/**
	 * 出对
	 * @return mixed 获取到的数据
	 */
	public function gets($count = 1) {
		if ($count && flock($this->file, LOCK_EX)) {
			$this->load();

			$res = array();
			for ($i = 0; $i < $count; $i++) {
				$res[] = array_shift($this->data);
			}

			$this->save();
			flock($this->file, LOCK_UN);
			return $res;
		}
	}

	/**
	 * 获得队列状态，即目前队列中的消息数量
	 * @return integer
	 */
	public function size() {
		return count($this->data);
	}

	/**
	 * 析构 写入日志
	 */
	public function __destruct() {
		// flock($this->file, LOCK_UN);
		fclose($this->file);
	}
}