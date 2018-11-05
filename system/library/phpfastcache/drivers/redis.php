<?php

/**
 * Class phpfastcache_predis
 * @author Khoa Bui (khoaofgod)  <khoaofgod@gmail.com> http://www.phpfastcache.com
 * Example at our website, any bugs, problems, please visit http://faster.phpfastcache.com
 */
class phpfastcache_redis extends BasePhpFastCache implements phpfastcache_driver {

	/**
	 * @var bool
	 */
	public $checked_redis = false;

	/**
	 * phpfastcache_predis constructor.
	 * @param array $config
	 */
	public function __construct($config = array()) {
		$this->setup($config);
		if (!$this->checkdriver() && !isset($config['skipError'])) {
			$this->fallback = true;
		}
		if (class_exists("Redis")) {
			$this->instant = new Redis();
		}

	}

	/**
	 * @return bool
	 */
	public function checkdriver() {
		// Check memcache
		if (class_exists("Redis")) {
			return true;
		}
		$this->fallback = true;
		return false;
	}

	/**
	 * @return bool
	 */
	public function connectServer() {

		$server = isset($this->config['redis']) ? $this->config['redis'] : array(
			"host"     => "127.0.0.1",
			"port"     => "6379",
			"password" => "",
			"database" => "",
			"timeout"  => "1",
		);

		if ($this->checked_redis === false) {

			$host = $server['host'];

			$port = isset($server['port']) ? (Int) $server['port'] : "";
			if ($port != "") {
				$c['port'] = $port;
			}

			$password = isset($server['password']) ? $server['password'] : "";
			if ($password != "") {
				$c['password'] = $password;
			}

			$database = isset($server['database']) ? $server['database'] : "";
			if ($database != "") {
				$c['database'] = $database;
			}

			$timeout = isset($server['timeout']) ? $server['timeout'] : "";
			if ($timeout != "") {
				$c['timeout'] = $timeout;
			}

			$read_write_timeout = isset($server['read_write_timeout']) ? $server['read_write_timeout'] : "";
			if ($read_write_timeout != "") {
				$c['read_write_timeout'] = $read_write_timeout;
			}

			if (!$this->instant->connect($host, (int) $port, (Int) $timeout)) {
				// 连接失败
				// 最下面返回true  这里设置为false  切换时间为3秒
				// 下面设置成 false  这里设置成true  虽然 切换速度块 但是连续读取的时候 容易自动切换成文件存储 即redis 正常的时候 依然写入文本存储
				// 上面的切换速度虽然慢 不过在redis 正常的时候 基本每次都可以请求redis
				// 如果redis  不是经常垮的话 还是建议上面设置成false 下面设置成true
				$this->checked_redis = false;
				$this->fallback      = true;
				return false;
			} else {
				if ($password && !$this->instant->auth($password)) {
					$this->checked_redis = false;
					$this->fallback      = true;
					return false;
				}
				if ($database != "") {
					$this->instant->select((Int) $database);
				}
				$this->checked_redis = true;
				return true;
			}
		}

		// 原版这里是true 当 redis 挂掉的时候 无法切换成文件
		// 设置成false 切换时间为 1秒
		return true;
	}

	/**
	 * @param $keyword
	 * @param string $value
	 * @param int $time
	 * @param array $option
	 * @return bool
	 */
	public function driver_set($keyword, $value = "", $time = 300, $option = array()) {
		if ($this->connectServer()) {
			$value = $this->encode($value);
			if (isset($option['skipExisting']) && $option['skipExisting'] == true) {
				return $this->instant->set($keyword, $value,
					array('xx', 'ex' => $time));
			} else {
				return $this->instant->set($keyword, $value, $time);
			}
		} else {
			return $this->backup()->set($keyword, $value, $time, $option);
		}
	}

	/**
	 * @param $keyword
	 * @param array $option
	 * @return mixed|null
	 */
	public function driver_get($keyword, $option = array()) {
		if ($this->connectServer()) {
			// return null if no caching
			// return value if in caching'
			$x = $this->instant->get($keyword);
			if ($x == false) {
				return null;
			} else {

				return $this->decode($x);
			}
		} else {
			$this->backup()->get($keyword, $option);
		}

	}

	/**
	 * @param $keyword
	 * @param array $option
	 */
	public function driver_delete($keyword, $option = array()) {

		if ($this->connectServer()) {
			$this->instant->delete($keyword);
		}

	}

	/**
	 * @param array $option
	 * @return array
	 */
	public function driver_stats($option = array()) {
		if ($this->connectServer()) {
			$res = array(
				"info" => "",
				"size" => "",
				"data" => $this->instant->info(),
			);

			return $res;
		}

		return array();

	}

	/**
	 * @param array $option
	 */
	public function driver_clean($option = array()) {
		if ($this->connectServer()) {
			$this->instant->flushDB();
		}

	}

	/**
	 * @param $keyword
	 * @return bool
	 */
	public function driver_isExisting($keyword) {
		if ($this->connectServer()) {
			$x = $this->instant->exists($keyword);
			if ($x == null) {
				return false;
			} else {
				return true;
			}
		} else {
			return $this->backup()->isExisting($keyword);
		}

	}
}
