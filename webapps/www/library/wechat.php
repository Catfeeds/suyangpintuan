<?php

include dirname(__FILE__) . '/wechatSDK.php';
class Wechat_Library extends wechatSDK {
	/**
	 * 重载日志
	 * @param string|array $log
	 */
	protected function log($log) {
		if ($this->debug) {
			Log::write('Wechat_Library: ' . json_encode($log));
		}
		return false;
	}

	/**
	 * 重载设置缓存
	 * @param string $cachename
	 * @param mixed $value
	 * @param int $expired 缓存秒数，如果为0则为长期缓存
	 * @return boolean
	 */
	protected function setCache($cachename, $value, $expired = 0) {
		S($cachename, $value, $expired);
	}

	/**
	 * 重载获取缓存
	 * @param string $cachename
	 * @return mixed
	 */
	protected function getCache($cachename) {
		return S($cachename);
	}

	/**
	 * 重载清除缓存
	 * @param string $cachename
	 * @return boolean
	 */
	protected function removeCache($cachename) {
		S($cachename, null);
		return true;
	}

	public function http_post1($url, $param, $post_file = false) {
		if (!$post_file && is_array($param)) {
			$param = $this->JSON($param);
		}
		if ($post_file) {
			$header[] = "content-type: multipart/form-data; charset=UTF-8";
		} else {
			$header[] = "content-type: application/json; charset=UTF-8";
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$res  = curl_exec($ch);
		$flat = curl_errno($ch);
		$info = curl_getinfo($ch);
		if ($flat) {
			$data = curl_error($ch);
		}
		curl_close($ch);

		if (intval($info["http_code"]) == 200) {
			return $res;
		} else {
			return false;
		}

		// $res = json_decode($res, true);

		// return $res;
	}

	public function JSON($array) {
		arrayRecursive($array, 'urlencode', true);
		$json = json_encode($array);
		return urldecode($json);
	}

}

?>