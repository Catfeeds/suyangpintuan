<?php

class Queue_redis {

	protected $redis_server;

	protected $server;

	protected $port;

	/**
	 * @var 消息队列标志
	 */
	protected $key;

	/**
	 * 构造队列，创建redis链接
	 * @param $server_config
	 * @param $key
	 * @param bool $p_connect
	 */

	public function __construct(array $config = array()) {

		$this->server = empty($config['server']['ip']) ? '127.0.0.1' : $config['server']['ip'];
		$this->port   = empty($config['server']['port']) ? '6379' : $config['server']['port'];
		$this->key    = empty($config['key']) ? 'redis_message_queue' : $config['key'];

		$this->check_environment();

		$p_connect = empty($config['p_connect']) ? false : true;
		if ($p_connect) {
			$this->pconnect();
		} else {
			$this->connect();
		}
	}

	/**
	 * 析构函数，关闭redis链接，使用长连接时，最好主动调用关闭
	 */
	public function __destruct() {
		$this->close();
	}

	/**
	 * 短连接
	 */
	private function connect() {
		$this->redis_server = new \Redis();
		$this->redis_server->connect($this->server, $this->port);
	}

	/**
	 * 长连接
	 */
	public function pconnect() {
		$this->redis_server = new \Redis();
		$this->redis_server->pconnect($this->server, $this->port);
	}

	/**
	 * 关闭链接
	 */
	public function close() {
		$this->redis_server->close();
	}

	/**
	 * 向队列插入一条数据
	 * @param $data
	 * @return mixed
	 */
	public function put($data) {
		$data = (is_object($data) || is_array($data)) ? json_encode($data) : $data;
		return $this->redis_server->rPush($this->key, $data);
	}

	/**
	 * 向队列中插入一串数据 需要解决数组的问题
	 * @param $data
	 * @return mixed
	 */
	public function puts() {
		$params = func_get_args();
		array_walk($params, function (&$v, $k) {
			$v = (is_object($v) || is_array($v)) ? json_encode($v) : $v;
		});
		$params = array_merge(array($this->key), $params);
		return call_user_func_array(array($this->redis_server, 'lPush'), $params);
	}

	/**
	 * 向队列中插入一串数据
	 * @param $data
	 * @return mixed
	 */
	public function puts2() {
		$params = func_get_args();
		return call_user_func_array(array($this, 'put'), $params);
	}

	/**
	 * 从队列顶部获取一条记录
	 * @return mixed
	 */
	public function get() {
		$res = $this->redis_server->lPop($this->key);
		return json_decode($res, true);
	}

	/**
	 * 从队列顶部获取一条记录
	 * @return mixed
	 */
	public function gets($count = 1) {
		$res = array();

		for ($i = 0; $i < $count; $i++) {
			$res[] = json_decode($this->redis_server->lPop($this->key), true);
		}

		return $res;
	}

	/**
	 * 选择数据库，可以用于区分不同队列
	 * @param $database
	 */
	public function select($database) {
		$this->redis_server->select($database);
	}

	/**
	 * 获得队列状态，即目前队列中的消息数量
	 * @return mixed
	 */
	public function size() {
		return $this->redis_server->lSize($this->key);
	}

	/**
	 * 获取某一位置的值，不会删除该位置的值
	 * @param $pos
	 * @return mixed
	 */
	public function view($pos) {
		return $this->redis_server->lGet($this->key, $pos);
	}

	/**
	 * 检查Redis扩展
	 * @throws Exception
	 */
	protected function check_environment() {
		if (!\extension_loaded('redis')) {
			throw new \Exception('Redis extension not loaded');
		}
	}
}