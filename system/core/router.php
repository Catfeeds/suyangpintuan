<?php

class Lowxp_Router {

	/**

	 * Array that holds all Route objects

	 * @var array

	 */

	private $routes = array();

	/**

	 * Array to store named routes in, used for reverse routing.

	 * @var array

	 */

	private $namedRoutes = array();

	/**

	 * The base REQUEST_URI. Gets prepended to all route url's.

	 * @var string

	 */

	private $basePath = '';

	/**

	 * Set the base url - gets prepended to all route url's.

	 * @param $basePath

	 */

	public function setBasePath($basePath) {

		$this->basePath = (string) $basePath;

	}

	function __construct() {

		self::$instance = &$this;

		$this->base_dir = ControllerDir;

	}

	static public $instance;

	static function getInstance() {

		if (empty(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;

	}

	/**

	 * Route factory method

	 *

	 * Maps the given URL to the given target.

	 * @param string $routeUrl string

	 * @param mixed $target The target of this route. Can be anything. You'll have to provide your own method to turn *      this into a filename, controller / action pair, etc..

	 * @param array $args Array of optional arguments.

	 */

	public function map($routeUrl, $target = '', array $args = array()) {

		$route = new Route();

		//设置URL,末尾补充斜杠

		$route->setUrl($this->basePath . $routeUrl);

		//设置Target:访问目标[控制器方法/路径]

		$route->setTarget($target);

		//设置请求的方法GET,POST

		if (isset($args['methods'])) {

			$methods = explode(',', $args['methods']);

			$route->setMethods($methods);

		}

		//设置过滤正则,key=>正则

		if (isset($args['filters'])) {

			$route->setFilters($args['filters']);

		}

		//设置name

		if (isset($args['name'])) {

			$route->setName($args['name']);

			//将所有设置的name路由对象,存入Array:$namedRoutes中

			if (!isset($this->namedRoutes[$route->getName()])) {

				$this->namedRoutes[$route->getName()] = $route;

			}

		}

		$this->routes[] = $route;

	}

	/**

	 * Matches the current request against mapped routes

	 */

	public function matchCurrentRequest() {

		$requestMethod = (isset($_POST['_method']) && ($_method = strtoupper($_POST['_method'])) && in_array($_method, array('PUT', 'DELETE'))) ? $_method : isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';

		$requestUrl = !empty($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['REQUEST_URI'];

		// strip GET variables from URL

		if (($pos = strpos($requestUrl, '?')) !== false) {

			$requestUrl = substr($requestUrl, 0, $pos);

		}

		return $this->match($requestUrl, $requestMethod);

	}

	/**

	 * Match given request url and request method and see if a route has been defined for it

	 * If so, return route's target

	 * If called multiple times

	 */

	public function match($requestUrl, $requestMethod = 'GET') {

		#echo '<pre>';print_r($this->routes);echo '</pre>';

		foreach ($this->routes as $route) {

			// compare server request method with route's allowed http methods

			if (!in_array($requestMethod, $route->getMethods())) {
				continue;
			}

			#echo '<b style="color:red">'.$route->getRegex().'</b><br />';

			// check if request url matches route regex. if not, return false.

			// 有匹配到,就停止,没有匹配到则将所有的都匹配一遍,foreach所有的routes

			if (!preg_match("@^" . $route->getRegex() . "*$@i", $requestUrl, $matches)) {
				continue;
			}

			$params = array();

			#echo '<b style="color:blue">@'.$route->getUrl().'</b><br />';

			if (preg_match_all("/:([\w-]+)/", $route->getUrl(), $argument_keys)) {

				// grab array with matches

				$argument_keys = $argument_keys[1];

				// loop trough parameter names, store matching value in $params array

				foreach ($argument_keys as $key => $name) {

					if (isset($matches[$key + 1])) {
						$params[$name] = $matches[$key + 1];
					}

				}

			}

			$route->setParameters($params);

			return $route;

		}

		return false;

	}

	/**

	 * Reverse route a named route

	 *

	 * @param string $route_name The name of the route to reverse route.

	 * @param array $params Optional array of parameters to use in URL

	 * @return string The url to the route

	 */

	public function generate($routeName, array $params = array()) {

		// Check if route exists

		if (!isset($this->namedRoutes[$routeName])) {
			throw new Exception("No route with the name $routeName has been found.");
		}

		$route = $this->namedRoutes[$routeName];

		$url = $route->getUrl();

		// replace route url with given parameters

		if ($params && preg_match_all("/:(\w+)/", $url, $param_keys)) {

			// grab array with matches

			$param_keys = $param_keys[1];

			// loop trough parameter names, store matching value in $params array

			foreach ($param_keys as $i => $key) {

				if (isset($params[$key])) {
					$url = preg_replace("/:(\w+)/", $params[$key], $url, 1);
				}

			}

		}

		return $url;

	}

	/**

	 * 优先查询反向路由配置表.

	 * @return string

	 */

	function getRequestUrl() {

		$route = $this->matchCurrentRequest();

		$requestUrl = '';

		if ($route) {

			//匹配微信模式

			$requestUrl = $route->getTarget();

			$params = $route->getParameters();

			#echo $url4 = $router->generate('users_edit',$params);

			if (!empty($params)) {

				$requestUrl = trim($requestUrl, '/') . '/' . implode('/', $params);

			}

		}

		if ($requestUrl == '') {
			$requestUrl = !empty($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['REQUEST_URI'];
		}

		return $requestUrl;

	}

	public $segments;

	public $base_dir;

	public $fragment = array();

	public $controller;

	static public $class;

	static public $method;

	static public $params = array();

	/**

	 * 查找方法,如果找到了方法,则去执行

	 * @param string $requestUrl

	 * @return bool

	 */

	function location($requestUrl = '') {

		if (empty($requestUrl)) {

			$requestUrl = !empty($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['REQUEST_URI'];

		}

		//增加favicon.ico处理

		#if(strpos($requestUrl,'favicon.ico')!==false)URL_404();

		if (($pos = strpos($requestUrl, '?')) !== false) {

			$requestUrl = substr($requestUrl, 0, $pos);

		}

		$requestUrl = trim($requestUrl, '/');

		if ($requestUrl == '') {
			$requestUrl = DefaultController;
		}

		$this->segments = explode('/', $requestUrl);

		$this->fragment = $this->segments;

		$path = $this->finder();

		if (is_file($path)) {

			$this->setSegments();

			return $path;

		} else {

			URL_404();exit;

		}

	}

	function setSegments() {

		self::$class = end($this->fragment);

		if (count(self::$params) > 0) {

			self::$params = array_reverse(self::$params);

			$method = self::$params[0];

			self::$params = array_slice(self::$params, 1);

		} else {

			$method = DefaultAction;

		}

		self::$method = $method;

		$_SERVER['request'] = array(

			'class'  => self::$class,

			'method' => self::$method,

			'params' => self::$params,

		);

	}

	/**

	 * 查找文件.

	 *

	 *   如:/manage/verify/companies/action

	 *   一层层查找文件,直到找到,原理如下;

	 *   最后一项为数值时(/manage/verify/123),则直接跳过

	 *   1. /manage/verify/companies/action.php 不存在继续往找

	 *   2. /manage/verify/companies.php 不存在继续往找

	 *   3. /manage/verify.php 不存在继续往找

	 * @return bool|string

	 */

	function finder() {

		if (count($this->fragment) == 0) {
			return false;
		}

		if (is_numeric(end($this->fragment))) {

			self::$params[] = array_pop($this->fragment);

			return $this->finder();

		} else {

			$fileName = $this->base_dir . implode('/', $this->fragment) . '.php';

			if (is_file($fileName)) {

				return $fileName;

			} else {

				self::$params[] = array_pop($this->fragment);

				return $this->finder();

			}

		}

	}

}

class Route {

	/**

	 * URL of this Route

	 * @var string

	 */

	private $url;

	/**

	 * Accepted HTTP methods for this route

	 * @var array

	 */

	private $methods = array('GET', 'POST', 'PUT', 'DELETE');

	/**

	 * Target for this route, can be anything.

	 * @var mixed

	 */

	private $target;

	/**

	 * The name of this route, used for reversed routing

	 * @var string

	 */

	private $name;

	/**

	 * Custom parameter filters for this route

	 * @var array

	 */

	private $filters = array();

	/**

	 * Array containing parameters passed through request URL

	 * @var array

	 */

	private $params = array();

	public function getUrl() {

		return $this->url;

	}

	public function setUrl($url) {

		$url = (string) $url;

		// make sure that the URL is suffixed with a forward slash

		if (substr($url, -1) !== '/') {
			$url .= '/';
		}
//最后补斜杠

		$this->url = $url;

	}

	public function getTarget() {

		return $this->target;

	}

	public function setTarget($target) {

		$this->target = $target;

	}

	public function getMethods() {

		return $this->methods;

	}

	public function setMethods(array $methods) {

		$this->methods = $methods;

	}

	public function getName() {

		return $this->name;

	}

	public function setName($name) {

		$this->name = (string) $name;

	}

	public function setFilters(array $filters) {

		$this->filters = $filters;

	}

	public function getRegex() {

		return preg_replace_callback("/:(\w+)/", array(&$this, 'substituteFilter'), $this->url);

	}

	private function substituteFilter($matches) {

		if (isset($matches[1]) && isset($this->filters[$matches[1]])) {

			return $this->filters[$matches[1]];

		}

		return "([\w-]+)";

	}

	public function getParameters() {

		return $this->parameters;

	}

	/**

	 * MatchNewMode

	 */

	function matchWxMode() {

		$matchmode = false;

		foreach ($this->parameters as $k => $v) {

			if (strpos($this->target, ':' . $k) !== false) {

				$matchmode = true;

				$this->target = str_replace(':' . $k, $v, $this->target);

				unset($this->parameters[$k]);

			}

		}

		if ($matchmode) {

			$_SERVER['config']['request'] = $this->parameters;

			$this->parameters = array();

		}

	}

	public function setParameters(array $parameters) {

		$this->parameters = $parameters;

	}

}