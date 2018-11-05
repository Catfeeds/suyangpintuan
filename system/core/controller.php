<?php
/**
 *
 * Class Lowxp
 * @property Lowxp_Loader $load
 * @property Smarty $smarty
 * @property Database $db
 * @property page_model $page
 * @property share_model $share
 * @property upload_model $upload
 * @property user_model $user
 * @property debug_model $debug
 * @property wxnews_model $wxnews
 * @property setting_model $setting
 * @property category_model $category
 * @property content_model $content
 * @property taglib_model $taglib
 * @property tree_Library $tree
 * @property menus_model $menus
 * @property wechat_model $wechat
 * @property browser_library $browser
 * @property image_library $image
 * @property UserAgent_Library $useragent
 */
class Lowxp_Controller {

	public $load;
	public $smarty;
	static private $instance;

	function __construct() {
		self::$instance = &$this;
		foreach (isLoaded() as $var) {
			$this->$var = &loadClass($var, 'core');
		}

		$this->load = &loadClass('loader', 'core');
	}

	public static function &get_instance() {
		return self::$instance;
	}

	function query($key = null, $default = null, $xss = 1) {
		$result = isset($_GET[$key]) ? $_GET[$key] : $default;
		if ($xss) {
			$result = $this->xss_clear($result);
		}
		return $result;
	}
	/**
	 * @param null $key
	 * @param null $default
	 * @param int $xss
	 * @return array|null|string
	 */
	function body($key = null, $default = null, $xss = 1) {
		$result = isset($_POST[$key]) ? $_POST[$key] : $default;
		if ($xss) {
			$result = $this->xss_clear($result);
		}
		return $result;
	}

	private function xss_clear($result) {
		if (is_array($result)) {
			#$my_remove_xss = function(&$item){
			#$item = util_General::remove_xss($item);
			#};
			#array_walk_recursive($result, $my_remove_xss);
		} else {
			#$result = util_General::remove_xss($result);
		}
		return $result;
	}

}