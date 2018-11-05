<?php

/**
 * Do not use or reference this directly from your client-side code.
 * Instead, this should be required via the endpoint.php or endpoint-cors.php
 * file(s).
 */

class UploadHandler {

	public $allowedMimes      = array();
	public $allowedExtensions = array();
	public $sizeLimit         = null;
	public $inputName         = 'qqfile';
	public $chunksFolder      = 'chunks'; // 文件块临时目录

	public $chunksCleanupProbability = 0.001; // Once in 1000 requests on avg
	public $chunksExpireIn           = 604800; // One week

	protected $uploadName;

	// protected $error;

	// /**
	//  * 获取错误信息
	//  */
	// public function getError() {
	// 	return $this->error;
	// }

	/**
	 * 获取原始原始文件名
	 * 设置qqfilename 可以改变文件的存储名称
	 */
	public function getName() {

		if (isset($_REQUEST['qqfilename'])) {
			return $_REQUEST['qqfilename'];
		}

		if (isset($_FILES[$this->inputName])) {
			return $_FILES[$this->inputName]['name'];
		}

	}

	/**
	 * 获取5000个初始文件数组 暂未使用
	 * @return array
	 */
	public function getInitialFiles() {
		$initialFiles = array();

		for ($i = 0; $i < 5000; $i++) {
			array_push($initialFiles, array("name" => "name"+$i, uuid => "uuid"+$i, thumbnailUrl => __DIR__ . "/fu.png"));
		}

		return $initialFiles;
	}

	/**
	 * 获取上传后的文件名
	 */
	public function getUploadName() {
		return $this->uploadName;
	}

	/**
	 * 分段上传编译
	 * @param  string $uploadDirectory 上传目录
	 * @param  string $name            文件名
	 * @return array                   处理结果
	 */
	public function combineChunks($uploadDirectory, $name = null) {
		$uuid = $_POST['qquuid'];
		if ($name === null) {
			$name       = $this->getName();
			$targetPath = join('/', array($uploadDirectory, $uuid, $name));
		} else {
			$targetPath = $uploadDirectory . '/' . $name;
		}

		$targetFolder = $this->chunksFolder . '/' . $uuid; // 文件块目录
		$totalParts   = isset($_REQUEST['qqtotalparts']) ? (int) $_REQUEST['qqtotalparts'] : 1;

		$this->uploadName = $name;

		if (!file_exists($targetPath)) {
			if (!$this->createDir(dirname($targetPath))) {
				return array('error' => '创建可写目录失败');
			}
		}

		$target = fopen($targetPath, 'wb');

		for ($i = 0; $i < $totalParts; $i++) {
			$chunk = fopen($targetFolder . '/' . $i, "rb");
			stream_copy_to_stream($chunk, $target);
			fclose($chunk);
		}

		// Success
		fclose($target);
		for ($i = 0; $i < $totalParts; $i++) {
			unlink($targetFolder . '/' . $i);
		}

		rmdir($targetFolder);

		// 文件超过大小限制则删除文件 然后阻止重试
		if (!is_null($this->sizeLimit) && filesize($targetPath) > $this->sizeLimit) {
			unlink($targetPath);
			http_response_code(413);
			return array("success" => false, "uuid" => $uuid, "preventRetry" => true);
		}

		if (!$this->checkMime($targetPath)) {
			unlink($targetPath);
			http_response_code(413);
			$these = implode(', ', $this->allowedMimes);
			return array('error' => '文件类型只能是 ' . $these . '.');
		}

		$targetPath = $this->conventImg($targetPath);
		return array("success" => true, "uuid" => $uuid, 'path' => '/' . $targetPath);
	}

	/**
	 * Process the upload.
	 * @param string $uploadDirectory 必须使用相对路径
	 * @param string $name Overwrites the name of the file.
	 */
	public function handleUpload($uploadDirectory, $name = null) {
		if (is_writable($this->chunksFolder) &&
			1 == mt_rand(1, 1 / $this->chunksCleanupProbability)) {

			// Run garbage collection
			$this->cleanupChunks();
		}

		// Check that the max upload size specified in class configuration does not
		// exceed size allowed by server config
		// 文件大小限制检查
		if ($this->toBytes(ini_get('post_max_size')) < $this->sizeLimit ||
			$this->toBytes(ini_get('upload_max_filesize')) < $this->sizeLimit) {
			$neededRequestSize = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
			return array('error' => "Server error. Increase post_max_size and upload_max_filesize to " . $neededRequestSize);
		}

		// $uploadDirectory = realpath($uploadDirectory);
		if ($this->isInaccessible($uploadDirectory)) {
			if (!$this->createDir($uploadDirectory)) {
				return array('error' => '创建可写目录失败');
			}
		}

		$type = $_SERVER['CONTENT_TYPE'];
		if (isset($_SERVER['HTTP_CONTENT_TYPE'])) {
			$type = $_SERVER['HTTP_CONTENT_TYPE'];
		}

		if (!isset($type)) {
			return array('error' => "No files were uploaded.");
		} else if (strpos(strtolower($type), 'multipart/') !== 0) {
			return array('error' => "Server error. Not a multipart request. Please set forceMultipart to default value (true).");
		}

		// Get size and name
		$file = $_FILES[$this->inputName];
		$size = $file['size'];
		if (isset($_REQUEST['qqtotalfilesize'])) {
			$size = $_REQUEST['qqtotalfilesize'];
		}

		$uuid = $_REQUEST['qquuid'];
		if ($name === null) {
			$name   = $this->getName();
			$target = join('/', array($uploadDirectory, $uuid, $name));
		} else {
			$target = $uploadDirectory . '/' . $name;
		}

		// Validate name
		if ($name === null || $name === '') {
			return array('error' => '文件名为空.');
		}

		// Validate file size
		if ($size == 0) {
			return array('error' => '文件为空.');
		}

		if (!is_null($this->sizeLimit) && $size > $this->sizeLimit) {
			return array('error' => '文件过大.', 'preventRetry' => true);
		}

		// Validate file extension
		// $pathinfo = pathinfo($name);
		// $ext      = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';

		// if ($this->allowedExtensions && !in_array(strtolower($ext), array_map("strtolower", $this->allowedExtensions))) {
		// 	$these = implode(', ', $this->allowedExtensions);
		// 	return array('error' => '文件后缀不合法, 后缀只能是 ' . $these . '.');
		// }

		// Save a chunk
		$totalParts = isset($_REQUEST['qqtotalparts']) ? (int) $_REQUEST['qqtotalparts'] : 1;

		if ($totalParts > 1) {
			# chunked upload

			$chunksFolder = $this->chunksFolder;
			$partIndex    = (int) $_REQUEST['qqpartindex'];

			if (!is_writable($chunksFolder) && !is_executable($uploadDirectory)) {
				if (!$this->createDir($chunksFolder)) {
					return array('error' => '创建可写目录失败');
				}
				if (!$this->createDir($uploadDirectory)) {
					return array('error' => '创建可写目录失败');
				}
			}

			$targetFolder = $this->chunksFolder . '/' . $uuid;

			if (!file_exists($targetFolder)) {
				if (!$this->createDir($targetFolder)) {
					return array('error' => '创建可写目录失败');
				}
			}
			$target  = $targetFolder . '/' . $partIndex;
			$success = move_uploaded_file($_FILES[$this->inputName]['tmp_name'], iconv('UTF-8', 'gb2312', $target));

			return array("success" => true, "uuid" => $uuid);

		} else {
			# non-chunked upload

			if (!$this->checkMime($file['tmp_name'])) {
				$these = implode(', ', $this->allowedMimes);
				return array('error' => '文件类型只能是 ' . $these . '.');
			}

			if ($target) {
				$this->uploadName = basename($target);
				if (!$this->createDir(dirname($target))) {
					return array('error' => '创建可写目录失败');
				}

				if (move_uploaded_file($file['tmp_name'], iconv('UTF-8', 'gb2312', $target))) {
					$target = $this->conventImg($target);
					return array('success' => true, "uuid" => $uuid, 'path' => '/' . $target);
				}
			}

			return array('error' => 'Could not save uploaded file.' .
				'The upload was cancelled, or server error encountered');
		}
	}

	/**
	 * Returns a path to use with this upload. Check that the name does not exist,
	 * and appends a suffix otherwise.
	 * @param string $uploadDirectory Target directory
	 * @param string $filename The name of the file to use.
	 */
	protected function getUniqueTargetPath($uploadDirectory, $filename) {
		// Allow only one process at the time to get a unique file name, otherwise
		// if multiple people would upload a file with the same name at the same time
		// only the latest would be saved.

		if (function_exists('sem_acquire')) {
			$lock = sem_get(ftok(__FILE__, 'u'));
			sem_acquire($lock);
		}

		$pathinfo = pathinfo($filename);
		$base     = $pathinfo['filename'];
		$ext      = isset($pathinfo['extension']) ? $pathinfo['extension'] : '';
		$ext      = $ext == '' ? $ext : '.' . $ext;

		$unique = $base;
		$suffix = 0;

		// Get unique file name for the file, by appending random suffix.

		while (file_exists($uploadDirectory . '/' . $unique . $ext)) {
			$suffix += rand(1, 999);
			$unique = $base . '-' . $suffix;
		}

		$result = $uploadDirectory . '/' . $unique . $ext;

		// Create an empty target file
		if (!touch($result)) {
			// Failed
			$result = false;
		}

		if (function_exists('sem_acquire')) {
			sem_release($lock);
		}

		return $result;
	}

	/**
	 * Deletes all file parts in the chunks folder for files uploaded
	 * more than chunksExpireIn seconds ago
	 */
	protected function cleanupChunks() {
		foreach (scandir($this->chunksFolder) as $item) {
			if ($item == "." || $item == "..") {
				continue;
			}

			$path = $this->chunksFolder . '/' . $item;

			if (!is_dir($path)) {
				continue;
			}

			if (time() - filemtime($path) > $this->chunksExpireIn) {
				$this->removeDir($path);
			}
		}
	}

	/**
	 * Removes a directory and all files contained inside
	 * @param string $dir
	 */
	protected function removeDir($dir) {
		foreach (scandir($dir) as $item) {
			if ($item == "." || $item == "..") {
				continue;
			}

			if (is_dir($item)) {
				$this->removeDir($item);
			} else {
				unlink(join('/', array($dir, $item)));
			}

		}
		rmdir($dir);
	}

	/**
	 * create a directory, if it's exist set writable
	 * @param  string $dir
	 */
	protected function createDir($dir) {
		if (!$dir) {
			return false;
		}
		if (is_dir($dir)) {
			chmod($dir, 0777);
		} else {
			mkdir($dir, 0777, true);
		}
		return !$this->isInaccessible($dir);
	}

	/**
	 * Converts a given size with units to bytes.
	 * @param string $str
	 */
	protected function toBytes($str) {
		$val  = trim($str);
		$last = strtolower($str[strlen($str) - 1]);
		switch ($last) {
			case 'g':$val *= 1024;
			case 'm':$val *= 1024;
			case 'k':$val *= 1024;
		}
		return $val;
	}

	/**
	 * Determines whether a directory can be accessed.
	 *
	 * is_executable() is not reliable on Windows prior PHP 5.0.0
	 *  (http://www.php.net/manual/en/function.is-executable.php)
	 * The following tests if the current OS is Windows and if so, merely
	 * checks if the folder is writable;
	 * otherwise, it checks additionally for executable status (like before).
	 * 注意windows下需要为文件存储的目录分配 everyone 的写入权限
	 * @param string $directory The target directory to test access
	 */
	protected function isInaccessible($directory) {
		$isWin              = $this->isWindows();
		$folderInaccessible = ($isWin) ? !is_writable($directory) : (!is_writable($directory) && !is_executable($directory));
		return $folderInaccessible;
	}

	/**
	 * Determines is the OS is Windows or not
	 *
	 * @return boolean
	 */

	protected function isWindows() {
		$isWin = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN');
		return $isWin;
	}

	/**
	 * 如果后缀是图片 返回mime 类型 否则返回文件后缀名
	 * @param  string $file 本地文件路径
	 * @return string       如果后缀是图片 返回mime 类型 否则返回文件后缀名
	 */
	protected function getFileType($file) {
		$ext = strtolower(pathinfo($file), PATHINFO_EXTENSION);
		switch ($ext) {
			case 'jpeg':
			case 'jpg':
				return 'image/jpeg';
			case 'png':
				return 'image/png';
			case 'gif':
				return 'image/gif';
			default:
				return $ext;
		}
	}

	/**
	 * 获取文件的mime 类型 必须要 fileinfo php扩展支持
	 * 否则会根据文件后缀返回大致的 不可靠的 mime 类型
	 * @param  string $file 文件本地路径
	 * @return string
	 */
	protected function getMimeType($file) {
		if (function_exists('finfo_open')) {
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			return strtolower(finfo_file($finfo, $file));
		}

		if (function_exists('getimagesize')) {
			$tmp = getimagesize($file);
			if (is_array($tmp) && $tmp) {
				return strtolower($tmp['mime']);
			}
		}

		return strtolower($this->getFileType($file));
	}

	/**
	 * 检查上传的文件MIME类型是否合法
	 * @param string $localPath 文件路径
	 */
	protected function checkMime($localPath) {
		$mime = $this->getMimeType($localPath);
		return in_array($mime, $this->allowedMimes);
	}

	/**
	 * 如果上传的文件是图片格式 则保存为jpg文件
	 * 解决文件上传的安全问题 并规范后缀
	 * @param  string $path 文件路径
	 * @return string
	 */
	protected function conventImg($path) {
		$img = $this->gdImg($path);
		return $img ? $this->imgToJpg($img, $path) : $path;
	}

	/**
	 * 图像转gd
	 * @param  string $path 原始文件路径
	 * @return class        返回gd库对象
	 */
	protected function gdImg($path) {
		$mime = $this->getMimeType($path);
		switch ($mime) {
			case 'image/gif':
				return imagecreatefromgif($path);
				break;
			case 'image/png':
				return imagecreatefrompng($path);
				break;
			case 'image/jpeg':
				// return imagecreatefromjpeg($path);
				break;
			default:
				break;
		}
		return false;
	}

	/**
	 * gd输出jpg
	 * @param  class  $img  gd库
	 * @param  string $path 原始文件地址
	 * @return string       目标文件地址
	 */
	protected function imgToJpg($img, $path) {
		$newPath = $path;
		$len     = strrpos($path, '.');
		if ($len !== false) {
			$newPath = substr($path, 0, $len);
		}
		$newPath .= '.jpg';
		if (imagejpeg($img, $newPath)) {
			@unlink($path);
		}
		return $newPath;
	}

	/**
	 * 后期根据mime 设置文件扩展名
	 * @param string $path 返回文件新本地路径
	 */
	protected function setExtension($path) {
		$mime = $this->getMimeType($path);
		if (isset($this->mimeExtension[$mime])) {
			$newPath = $path . '.' . $this->mimeExtension[$mime];
			return rename($path, $newPath) ? $newPath : $path;
		} else {
			return $path;
		}
	}

	/**
	 * mime 和扩展名对照表 根据需要扩展
	 * @var array
	 */
	public $mimeExtension = array(
		'application/envoy'                       => 'evy',
		'application/fractals'                    => 'fif',
		'application/futuresplash'                => 'spl',
		'application/hta'                         => 'hta',
		'application/internet-property-stream'    => 'acx',
		'application/mac-binhex40'                => 'hqx',
		'application/msword'                      => 'doc',
		// 'application/msword'                      => 'dot',
		// 'application/octet-stream'                => '*',
		// 'application/octet-stream'                => 'bin',
		// 'application/octet-stream'                => 'class',
		// 'application/octet-stream'                => 'dms',
		// 'application/octet-stream'                => 'exe',
		// 'application/octet-stream'                => 'lha',
		// 'application/octet-stream'                => 'lzh',
		'application/oda'                         => 'oda',
		'application/olescript'                   => 'axs',
		'application/pdf'                         => 'pdf',
		'application/pics-rules'                  => 'prf',
		'application/pkcs10'                      => 'p10',
		'application/pkix-crl'                    => 'crl',
		'application/postscript'                  => 'ai',
		// 'application/postscript'                  => 'eps',
		// 'application/postscript'                  => 'ps',
		'application/rtf'                         => 'rtf',
		'application/set-payment-initiation'      => 'setpay',
		'application/set-registration-initiation' => 'setreg',
		// 'application/vnd.ms-excel'                => 'xla',
		// 'application/vnd.ms-excel'                => 'xlc',
		// 'application/vnd.ms-excel'                => 'xlm',
		'application/vnd.ms-excel'                => 'xls',
		// 'application/vnd.ms-excel'                => 'xlt',
		// 'application/vnd.ms-excel'                => 'xlw',
		'application/vnd.ms-outlook'              => 'msg',
		'application/vnd.ms-pkicertstore'         => 'sst',
		'application/vnd.ms-pkiseccat'            => 'cat',
		'application/vnd.ms-pkistl'               => 'stl',
		// 'application/vnd.ms-powerpoint'           => 'pot',
		// 'application/vnd.ms-powerpoint'           => 'pps',
		'application/vnd.ms-powerpoint'           => 'ppt',
		'application/vnd.ms-project'              => 'mpp',
		// 'application/vnd.ms-works'                => 'wcm',
		// 'application/vnd.ms-works'                => 'wdb',
		// 'application/vnd.ms-works'                => 'wks',
		'application/vnd.ms-works'                => 'wps',
		'application/winhlp'                      => 'hlp',
		'application/x-bcpio'                     => 'bcpio',
		'application/x-cdf'                       => 'cdf',
		'application/x-compress'                  => 'z',
		'application/x-compressed'                => 'tgz',
		'application/x-cpio'                      => 'cpio',
		'application/x-csh'                       => 'csh',
		// 'application/x-director'                  => 'dcr',
		'application/x-director'                  => 'dir',
		// 'application/x-director'                  => 'dxr',
		'application/x-dvi'                       => 'dvi',
		'application/x-gtar'                      => 'gtar',
		'application/x-gzip'                      => 'gz',
		'application/x-hdf'                       => 'hdf',
		'application/x-internet-signup'           => 'ins',
		// 'application/x-internet-signup'           => 'isp',
		'application/x-iphone'                    => 'iii',
		'application/x-javascript'                => 'js',
		'application/x-latex'                     => 'latex',
		'application/x-msaccess'                  => 'mdb',
		'application/x-mscardfile'                => 'crd',
		'application/x-msclip'                    => 'clp',
		'application/x-msdownload'                => 'dll',
		// 'application/x-msmediaview'               => 'm13',
		// 'application/x-msmediaview'               => 'm14',
		'application/x-msmediaview'               => 'mvb',
		'application/x-msmetafile'                => 'wmf',
		'application/x-msmoney'                   => 'mny',
		'application/x-mspublisher'               => 'pub',
		'application/x-msschedule'                => 'scd',
		'application/x-msterminal'                => 'trm',
		'application/x-mswrite'                   => 'wri',
		'application/x-netcdf'                    => 'cdf',
		// 'application/x-netcdf'                    => 'nc',
		'application/x-perfmon'                   => 'pma',
		// 'application/x-perfmon'                   => 'pmc',
		// 'application/x-perfmon'                   => 'pml',
		// 'application/x-perfmon'                   => 'pmr',
		// 'application/x-perfmon'                   => 'pmw',
		'application/x-pkcs12'                    => 'p12',
		// 'application/x-pkcs12'                    => 'pfx',
		'application/x-pkcs7-certificates'        => 'p7b',
		// 'application/x-pkcs7-certificates'        => 'spc',
		'application/x-pkcs7-certreqresp'         => 'p7r',
		'application/x-pkcs7-mime'                => 'p7c',
		// 'application/x-pkcs7-mime'                => 'p7m',
		'application/x-pkcs7-signature'           => 'p7s',
		'application/x-sh'                        => 'sh',
		'application/x-shar'                      => 'shar',
		'application/x-shockwave-flash'           => 'swf',
		'application/x-stuffit'                   => 'sit',
		'application/x-sv4cpio'                   => 'sv4cpio',
		'application/x-sv4crc'                    => 'sv4crc',
		'application/x-tar'                       => 'tar',
		'application/x-tcl'                       => 'tcl',
		'application/x-tex'                       => 'tex',
		'application/x-texinfo'                   => 'texi',
		// 'application/x-texinfo'                   => 'texinfo',
		'application/x-troff'                     => 'roff',
		// 'application/x-troff'                     => 't',
		// 'application/x-troff'                     => 'tr',
		'application/x-troff-man'                 => 'man',
		'application/x-troff-me'                  => 'me',
		'application/x-troff-ms'                  => 'ms',
		'application/x-ustar'                     => 'ustar',
		'application/x-wais-source'               => 'src',
		'application/x-x509-ca-cert'              => 'cer',
		// 'application/x-x509-ca-cert'              => 'crt',
		// 'application/x-x509-ca-cert'              => 'der',
		'application/ynd.ms-pkipko'               => 'pko',
		'application/zip'                         => 'zip',
		'audio/basic'                             => 'au',
		// 'audio/basic'                             => 'snd',
		'audio/mid'                               => 'mid',
		// 'audio/mid'                               => 'rmi',
		'audio/mpeg'                              => 'mp3',
		'audio/x-aiff'                            => 'aif',
		// 'audio/x-aiff'                            => 'aifc',
		// 'audio/x-aiff'                            => 'aiff',
		'audio/x-mpegurl'                         => 'm3u',
		'audio/x-pn-realaudio'                    => 'ra',
		// 'audio/x-pn-realaudio'                    => 'ram',
		'audio/x-wav'                             => 'wav',
		'image/bmp'                               => 'bmp',
		'image/cis-cod'                           => 'cod',
		'image/gif'                               => 'gif',
		// 'image/gif'                               => 'gif',
		'image/ief'                               => 'ief',
		// 'image/jpeg'                              => 'jpe',
		// 'image/jpeg'                              => 'jpeg',
		'image/jpeg'                              => 'jpg',
		'image/pipeg'                             => 'jfif',
		'image/png'                               => 'png',
		'image/svg+xml'                           => 'svg',
		'image/tiff'                              => 'tif',
		// 'image/tiff'                              => 'tiff',
		'image/x-cmu-raster'                      => 'ras',
		'image/x-cmx'                             => 'cmx',
		'image/x-icon'                            => 'ico',
		'image/x-portable-anymap'                 => 'pnm',
		'image/x-portable-bitmap'                 => 'pbm',
		'image/x-portable-graymap'                => 'pgm',
		'image/x-portable-pixmap'                 => 'ppm',
		'image/x-rgb'                             => 'rgb',
		'image/x-xbitmap'                         => 'xbm',
		'image/x-xpixmap'                         => 'xpm',
		'image/x-xwindowdump'                     => 'xwd',
		'message/rfc822'                          => 'mht',
		// 'message/rfc822'                          => 'mhtml',
		// 'message/rfc822'                          => 'nws',
		'text/css'                                => 'css',
		'text/h323'                               => '323',
		// 'text/html'                               => 'htm',
		'text/html'                               => 'html',
		// 'text/html'                               => 'stm',
		'text/iuls'                               => 'uls',
		// 'text/plain'                              => 'bas',
		// 'text/plain'                              => 'c',
		// 'text/plain'                              => 'h',
		'text/plain'                              => 'txt',
		'text/richtext'                           => 'rtx',
		'text/scriptlet'                          => 'sct',
		'text/tab-separated-values'               => 'tsv',
		'text/webviewhtml'                        => 'htt',
		'text/x-component'                        => 'htc',
		'text/x-setext'                           => 'etx',
		'text/x-vcard'                            => 'vcf',
		// 'video/mpeg'                              => 'mp2',
		// 'video/mpeg'                              => 'mpa',
		// 'video/mpeg'                              => 'mpe',
		// 'video/mpeg'                              => 'mpeg',
		'video/mpeg'                              => 'mpg',
		// 'video/mpeg'                              => 'mpv2',
		'video/quicktime'                         => 'mov',
		// 'video/quicktime'                         => 'qt',
		// 'video/x-la-asf'                          => 'lsf',
		'video/x-la-asf'                          => 'lsx',
		'video/x-ms-asf'                          => 'asf',
		// 'video/x-ms-asf'                          => 'asr',
		// 'video/x-ms-asf'                          => 'asx',
		'video/x-msvideo'                         => 'avi',
		'video/x-sgi-movie'                       => 'movie',
		// 'x-world/x-vrml'                          => 'flr',
		'x-world/x-vrml'                          => 'vrml',
		// 'x-world/x-vrml'                          => 'wrl',
		// 'x-world/x-vrml'                          => 'wrz',
		// 'x-world/x-vrml'                          => 'xaf',
		// 'x-world/x-vrml'                          => 'xof',
	);

}
