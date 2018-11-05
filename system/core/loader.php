<?php

/**

 * 路由加载器！

 * Class Lowxp_Loader

 */

class Lowxp_Loader {

	static public $instance = null;

	static public $issetUTF8 = false;

	private $configs = array();

	function __construct() {

	}

	/**

	 * 设置include/require路径

	 * @param $pathes

	 */

	function setIncludePath($pathes) {

		set_include_path(

			get_include_path()

			. PATH_SEPARATOR . RootDir

			. PATH_SEPARATOR . join(PATH_SEPARATOR, $pathes)

		);

	}

	/**

	 * @return Lowxp_Loader|null

	 */

	static function getInstance() {

		if (is_null(self::$instance)) {

			self::$instance = new self();

		}

		return self::$instance;

	}

	/**

	 * 设置网页内容编码为utf-8

	 */

	static function setHeaderUTF8() {

		if (!self::$issetUTF8) {

			header('Content-Type:text/html; charset=utf-8');

			self::$issetUTF8 = true;

		}

	}

	/**

	 * 加载smarty

	 */

	function smarty($params = array(), $return = false) {

		require_once RootDir . 'system/smarty/SmartyBC.class.php';

		$template_dir = isset($params['tplDir']) ? $params['tplDir'] : AppDir . 'views/'; //模板目录

		$compile_dir = isset($params['compileDir']) ? $params['compileDir'] : RUNTIME_PATH . 'views_c/'; //编译目录

		$cache_dir = isset($params['cacheDir']) ? $params['cacheDir'] : RUNTIME_PATH . 'cache/'; //缓存目录

		$plugin_dir = isset($params['pluginDir']) ? $params['pluginDir'] : ''; //缓存目录

		//初始化

		$smarty = new SmartyBC();

		$smarty->setTemplateDir($template_dir);

		$smarty->setCompileDir($compile_dir);

		$smarty->setCacheDir($cache_dir);

		if ($plugin_dir) {$smarty->addPluginsDir($plugin_dir);}

		if ($return === false) {

			$lowxp = &get_instance();

			$lowxp->smarty = $smarty;

			return true;

		}

		return $smarty;

	}

	/**

	 * 加载数据库

	 * @param $params

	 * @param bool $return  是否返回实例,默认为单例

	 * @return Database

	 */

	function database($params, $return = false) {

		//查看是否已加载该文件

		static $loaded_db_file;

		if (is_null($loaded_db_file)) {

			require SysDir . 'core/database.php';

			$loaded_db_file = true;

		}

		if ($return === false) {

			$lowxp = &get_instance();

			$lowxp->db = new Database($params);

			return true;

		} else {

			return new Database($params);

		}

	}

	public $models = array();

	public $libs = array();

	/**

	 * 加载model

	 * @param $model

	 * @param string|array|mixed $params

	 * @param string $alias

	 */

	function model($model, $params = '', $alias = '') {

		if (is_array($model)) {

			array_map(array($this, 'model'), $model);

			return;

		}

		$alias = $alias == '' ? $model : $alias;

		$lowxp = &get_instance();

		if (in_array($alias, $this->models)) {
			return;
		}

		foreach (array(AppDir, SysDir) as $baseDir) {

			$path = $baseDir . 'model/' . $model . '.php';

			if (is_file($path)) {

				include $path;

				break;

			}

		}

		$class = $model . '_model';

		if (!class_exists($class)) {

			showError($model . '不存在');die;

		}

		$lowxp->$alias = empty($params) ? new $class() : new $class($params);

		$this->models[] = $alias;

	}

	/**

	 * 加载类库

	 * @param $library

	 * @param string|array|mixed $params

	 * @param string $alias

	 */

	function library($library, $params = '', $alias = '') {

		if (is_array($library)) {

			array_map(array($this, 'library'), $library);

			return;

		}

		//别名

		$alias = $alias == '' ? $library : $alias;

		$lowxp = &get_instance();

		if (in_array($alias, $this->libs)) {
			return;
		}

		foreach (array(AppDir, SysDir) as $baseDir) {

			$path = $baseDir . 'library/' . $library . '.php';

			if (is_file($path)) {

				include $path;
				break;

			}

		}

		$class = $library . '_Library';

		if (!class_exists($class)) {

			showError($library . '不存在');die;

		}

		$lowxp->$alias = empty($params) ? new $class() : new $class($params);

		$this->libs[] = $alias;

	}

	/**

	 * 加载配置

	 * @param $item

	 * @param null $key

	 * @return mixed

	 */

	function config($item, $key = null) {

		if (!isset($this->configs[$item])) {

			$file = '';

			foreach (array(CfgDir, SysCfg) as $path) {

				$file = $path . $item . '.php';

				if (is_file($file)) {
					break;
				}

			}

			if ($file == '') {

				showError('配置' . $item . '不存在');die;

			}

			require $file;

			$this->configs[$item] = isset($config) ? $config : array(); #添加至配置列表

		}

		return $key === null ?

		$this->configs[$item] :

		(isset($this->configs[$item][$key]) ? $this->configs[$item][$key] : null);

	}

	/**

	 * 自动加载

	 * @param $classname

	 */

	function autoload($classname) {

		if (in_array($classname, array('util_Database', 'util_Criteria', 'util_PDO'))) {

			if ($GLOBALS['DBEngine'] == 'pdo') {

				//如果使用PDO模式,则加载相关文件

				$classname = str_replace('_', '_pdoEngine_', $classname);

			}

		}

		$filename = str_replace('_', '/', $classname) . '.php';

		if ($this->searchIncludePath($filename)) {

			require_once $filename;

		} else {

			$this->_autoload($classname);

		}

	}

	/**

	 * 遍历加载

	 * @param $filename

	 * @return bool|string

	 */

	private function searchIncludePath($filename) {

		if (is_file($filename)) {
			return $filename;
		}

		foreach (explode(PATH_SEPARATOR, ini_get("include_path")) as $path) {

			if (strlen($path) > 0 && $path{strlen($path) - 1} != DIRECTORY_SEPARATOR) {

				$path .= DIRECTORY_SEPARATOR;

			}

			$f = realpath($path . $filename);

			if ($f && is_file($f)) {

				return $f;

			}

		}

		return false;

	}

	/**

	 * @param $classname

	 */

	private function _autoload($classname) {

		$filename = str_replace('_', '/', strtolower($classname)) . '.php';

		if ($this->searchIncludePath($filename)) {

			require_once $filename;

		}

	}

}