<?php

class Weapp_Library {
	private $appid;
	private $secret;
	private $instance;

	public $errcode = 0;
	public $errmsg  = '';

	const API_BASE = 'https://api.weixin.qq.com';

	public function getError() {
		return $this->errcode . ':' . $this->errmsg;
	}

	public function sendRequestWithToken($url, $body_param = null, $is_post = true) {
		$token = array(
			'access_token' => $this->getAccessToken(),
		);
		return $this->sendHttpRequest($url, $token, $body_param, $is_post);
	}
	/**
	 * @param string $url
	 * @param array $url_param
	 * @param array $body_param
	 * @param bool $is_post
	 * @return mixed
	 * @throws WeAppException
	 */
	public function sendHttpRequest($url, $url_param = null, $body_param = null, $is_post = true) {
		if ($url_param) {
			$url_param = '?' . http_build_query($url_param);
		}
		if ($body_param) {
			$body_param = json_encode($body_param, JSON_UNESCAPED_UNICODE);
		}
		$ch = curl_init($url . $url_param);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if ($is_post) {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $body_param);
		}
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		$data = curl_exec($ch);
		curl_close($ch);
		$array_data = @json_decode($data, true);
		if ($array_data) {
			if (isset($array_data['errcode']) && $array_data['errcode'] != 0) {
				$this->errcode = $array_data['errcode'];
				$this->errmsg  = $array_data['errmsg'];
				return false;
			}
			return $array_data;
		}
		return $data;
	}

	public function getAccessToken() {

		$this->appid  = C('xcx_appid');
		$this->secret = C('xcx_appsecret');

		$cacheName = 'weapp_token_' . $this->appid;

		$token = S($cacheName);

		if (!$token) {
			$url = self::API_BASE . '/cgi-bin/token?grant_type=client_credential&appid=' . $this->appid . '&secret=' . $this->secret;
			$res = http($url);

			if (!$res) {
				$this->errcode = '-1';
				$this->errmsg  = '请求超时';
				return false;
			}

			$res = @json_decode($res, true);
			if (!empty($res['errcode'])) {
				$this->errcode = $res['errcode'];
				$this->errmsg  = $res['errmsg'];
				return false;
			}

			$this->errcode = 0;
			$this->errmsg  = '';

			$token = $res['access_token'];
			S($cacheName, $token, 7000);
		}

		return $token;
	}

	public function getwxacodeunlimit($param) {
		$token = $this->getAccessToken();
		return $this->sendRequestWithToken(self::API_BASE . '/wxa/getwxacodeunlimit', $param);
	}


    public function getwxacode($param) {
        $token = $this->getAccessToken();
        return $this->sendRequestWithToken(self::API_BASE . '/wxa/getwxacode', $param);
    }

}