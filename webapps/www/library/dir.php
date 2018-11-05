<?php/** * DIR 文件类 */class Dir_Library {	function fileext($filename) {		return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));	}	function filePutContents($file, $data, $mode = 'wb', $force = true) {		if (!file_exists($file) && $force) {			$this->mk_dir(dirname($file));		}		$contents = (is_array($data)) ? implode('', $data) : $data;		if (($fp = @fopen($file, $mode)) === false) {			return false;		} else {			$bytes = fwrite($fp, $contents);			fclose($fp);			return $bytes;		}	}	function dir_path($path) {		$path = str_replace('\\', '/', $path);		if (substr($path, -1) != '/') {			$path = $path . '/';		}		return $path;	}	function dir_create($path, $mode = 0777) {		if (is_dir($path)) {			return TRUE;		}		$ftp_enable = 0;		$path = $this->dir_path($path);		$temp = explode('/', $path);		$cur_dir = '';		$max = count($temp) - 1;		for ($i = 0; $i < $max; $i++) {			$cur_dir .= $temp[$i] . '/';			if (@is_dir($cur_dir)) {				continue;			}			@mkdir($cur_dir, $mode, true);			@chmod($cur_dir, $mode);		}		return is_dir($path);	}	function mk_dir($dir, $mode = 0777) {		if (is_dir($dir) || @mkdir($dir, $mode)) {			return true;		}		if (!$this->mk_dir(dirname($dir), $mode)) {			return false;		}		return @mkdir($dir, $mode);	}	function dir_copy($fromdir, $todir) {		$fromdir = $this->dir_path($fromdir);		$todir = $this->dir_path($todir);		if (!is_dir($fromdir)) {			return FALSE;		}		if (!is_dir($todir)) {			$this->dir_create($todir);		}		$list = glob($fromdir . '*');		if (!empty($list)) {			foreach ($list as $v) {				$path = $todir . basename($v);				if (is_dir($v)) {					$this->dir_copy($v, $path);				} else {					copy($v, $path);					@chmod($path, 0777);				}			}		}		return TRUE;	}	function dir_list($path, $exts = '', $list = array()) {		$path = $this->dir_path($path);		$files = glob($path . '*');		foreach ($files as $v) {			$fileext = $this->fileext($v);			if (!$exts || preg_match("/\.($exts)/i", $v)) {				$list[] = $v;				if (is_dir($v)) {					$list = $this->dir_list($v, $exts, $list);				}			}		}		return $list;	}	function dir_tree($dir, $parentid = 0, $dirs = array()) {		if ($parentid == 0) {			$id = 0;		}		$list = glob($dir . '*');		foreach ($list as $v) {			if (is_dir($v)) {				$id++;				$dirs[$id] = array('id' => $id, 'parentid' => $parentid, 'name' => basename($v), 'dir' => $v . '/');				$dirs = $this->dir_tree($v . '/', $id, $dirs);			}		}		return $dirs;	}	function dir_delete($dir) {		//$dir = dir_path($dir);		if (!is_dir($dir)) {			return FALSE;		}		$handle = opendir($dir); //��Ŀ¼		while (($file = readdir($handle)) !== false) {			if ($file == '.' || $file == '..') {				continue;			}			$d = $dir . DIRECTORY_SEPARATOR . $file;			is_dir($d) ? $this->dir_delete($d) : @unlink($d);		}		closedir($handle);		return @rmdir($dir);	}}?>