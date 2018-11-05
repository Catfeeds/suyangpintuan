<?php

/**
 * Crypt 加密实现类
 * @category   ORG
 * @package  ORG
 * @subpackage  Crypt
 * @author    liu21st <liu21st@gmail.com>
 */
class Crypt {

	/**
	 * 加密字符串
	 * @param string $str 字符串
	 * @param string $key 加密key
	 * @param integer $expire 有效期（秒）
	 * @return string
	 */
	public static function encrypt($str, $key, $expire = 0) {
		$expire = sprintf('%010d', $expire ? $expire + time() : 0);
		$r      = md5($key);
		$c      = 0;
		$v      = "";
		$str    = $expire . $str;
		$len    = strlen($str);
		$l      = strlen($r);
		for ($i = 0; $i < $len; $i++) {
			if ($c == $l) {
				$c = 0;
			}

			$v .= substr($r, $c, 1) .
				(substr($str, $i, 1) ^ substr($r, $c, 1));
			$c++;
		}
		return self::ed($v, $key);
	}

	/**
	 * 解密字符串
	 * @param string $str 字符串
	 * @param string $key 加密key
	 * @return string
	 */
	public static function decrypt($str, $key) {
		$str = self::ed($str, $key);
		$v   = "";
		$len = strlen($str);
		for ($i = 0; $i < $len; $i++) {
			$md5 = substr($str, $i, 1);
			$i++;
			$v .= (substr($str, $i, 1) ^ $md5);
		}
		$data   = $v;
		$expire = substr($data, 0, 10);
		if ($expire > 0 && $expire < time()) {
			return '';
		}
		$data = substr($data, 10);
		return $data;
	}

	private static function ed($str, $key) {
		$r   = md5($key);
		$c   = 0;
		$v   = '';
		$len = strlen($str);
		$l   = strlen($r);
		for ($i = 0; $i < $len; $i++) {
			if ($c == $l) {
				$c = 0;
			}

			$v .= substr($str, $i, 1) ^ substr($r, $c, 1);
			$c++;
		}
		return $v;
	}
}
/*
用法：
AESCRYPT::encrypt($str,$key);
AESCRYPT::decrypt($str,$key);
 */
class AESCRYPT {
	public static function encrypt($input, $key) {
		$size  = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
		$input = AESCRYPT::pkcs5_pad($input, $size);
		$td    = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
		$iv    = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		mcrypt_generic_init($td, $key, $iv);
		$data = mcrypt_generic($td, $input);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		$data = base64_encode($data);
		return $data;
	}
	private static function pkcs5_pad($text, $blocksize) {
		$pad = $blocksize - (strlen($text) % $blocksize);
		return $text . str_repeat(chr($pad), $pad);
	}
	public static function decrypt($sStr, $sKey) {
		$decrypted = mcrypt_decrypt(
			MCRYPT_RIJNDAEL_128,
			$sKey,
			base64_decode($sStr),
			MCRYPT_MODE_ECB
		);
		$dec_s     = strlen($decrypted);
		$padding   = ord($decrypted[$dec_s - 1]);
		$decrypted = substr($decrypted, 0, -$padding);
		return $decrypted;
	}
}