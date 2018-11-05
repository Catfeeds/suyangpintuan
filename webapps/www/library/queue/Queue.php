<?php

class Queue {

	private $instance = null;

	public $defaultConfig = array(
		//'type'   => 'redis',
		'type'   => 'file',
		'key'    => 'queue',
		'server' => array('ip' => '127.0.0.1', 'port' => '6379'),
	);

	public function __construct(array $config = array()) {
		$config = array_merge($this->defaultConfig, $config);

		$class = strtolower($config['type']);
		require_once __DIR__ . '/driver/' . ucwords($class) . '.php';
		$class = 'Queue_' . $class;

		$this->instance = new $class($config);
		return $this->instance;
	}

	public function __call($method, $params) {
		return call_user_func_array(array($this->instance, $method), $params);
	}

}