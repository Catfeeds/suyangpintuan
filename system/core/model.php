<?php
/**
 * Class Lowxp_Model
 * @property Lowxp_Loader $load
 * @property database $db
 * @property share_model $share
 * @property debug_model $debug
 * @property Smarty $smarty
 * @property page_model $page
 * @property upload_model $upload
 * @property user_model $user
 * @property wxapi_model $wxapi
 * @property wxnews_model $wxnews
 * @property Image_Library $image
 * @property UserAgent_Library $useragent
 *
 * @method body($key=null, $default=null, $xss=1)
 * @method query($key=null, $default=null, $xss=1)
 */
class Lowxp_Model {

	public $error = '';

	public function getError() {
		return $this->error;
	}

	public function __get($key) {
		$lowxp = &get_instance();
		return $lowxp->$key;
	}

}