<?php/** * Class taglib_model * 自定义smarty 数据标签 * * 标签默认属性说明： * mod 标签函数名 * var 标签返回的变量名 默认assign tagData（避免与程序assign的变量冲突，建议命名方式：tag开头，如tagData） * debug=1(测试) */class taglib_model extends Lowxp_Model {	#定义标签属性	protected $tags = array(		'slide'    => array('attr' => 'id'),		'category' => array('attr' => 'catid,type,field,child,ismenu,limit'),		'content'  => array('attr' => 'module,catid,id,type,field,child,posid,where,order,limit'),		'block'    => array('attr' => 'mark'),		'catpos'   => array('attr' => 'catid,space,end'),		'fileurl'  => array('attr' => 'source,width,height,type,field,num,nopic'),		'pn'       => array('attr' => 'module,catid,id'),		'photo'    => array('attr' => 'source,width,height,type,field,num,nopic'),	);	#模型内容上下篇	function _pn($arguments) {		$attr = $this->parseAttr($arguments);		@extract($attr);		if (empty($module) && empty($catid)) {			return;		}		$this->load->model('category');		if (!empty($catid)) {			$row_category = $this->category->get($catid);			if ($row_category['module']) {				$module = $row_category['module'];			} else {				return;			}		}		$this->load->model('content');		$this->content->chkModule($module);		$moduleinfo = $this->content->getModuleinfo();		$fieldsinfo = $this->content->getFieldsinfo();		$tableLib = $module;		$whereLib = ' WHERE lang=' . LANG_ID;		$orderLib = ' ORDER BY listorder DESC,id DESC';		if ($fieldsinfo['status']) {			$whereLib .= " AND status=1";		}		foreach ($attr as $_key => $_val) {			switch ($_key) {				case 'catid':					$$_key = !empty($_val) ? (int) $_val : '';					if (!empty($$_key)) {						$whereLib .= " AND catid" . $this->category->condArrchild($$_key);					}					break;				case 'id':					$$_key = (int) $_val;					break;			}		}		$list = $this->db->select("SELECT id,title,url FROM ###_" . $tableLib . $whereLib . $orderLib);		$data = array();		foreach ($list as $k => $v) {			if ($v['id'] == $id) {				$data['next'] = $list[$k - 1];				$data['prev'] = $list[$k + 1];				break;			}		}		$this->smarty->assign($var, $data ? $data : array());	}	#输出图片文件标签	function _fileurl($arguments) {		// fan 2016-04-19 start		// 解决 $dir 不存在的notic错误		$dir = '';		// fan 2016-04-19 end		$attr = $this->parseAttr($arguments);		@extract($attr);		$nopic = $nopic ? $nopic : '/common/nopic.png';        $port = $_SERVER['SERVER_PORT']!='80'?':'.$_SERVER['SERVER_PORT']:'';        $url   = rtrim(RootUrl,'/').$port;		if (C('cloudsave')) {			$url = !empty($dir) ? C('cloudurl2') : C('cloudurl');		}		$array = json_decode($source, true);		#原图为字符串时转换数组		if (!is_array($array) && !empty($source)) {			$array = array();			if (strpos($source, RootUrl) !== false) {				$source = str_replace(RootUrl, '/', $source);			}			$array[] = array('path' => $source, 'title' => $source);		}		$field  = (empty($field)) ? 'path' : $field;		$num    = (empty($num)) ? count($array) : (int) $num;		$width  = (empty($width)) ? 0 : (int) $width;		$height = (empty($height)) ? 0 : (int) $height;		$type   = (empty($type)) ? 2 : (int) $type;		if (empty($array) && $num > 1) {			return;		}		$this->load->model('upload');		$data = array();                                        		if (is_array($array)) {			foreach ($array as $k => $v) {				#自动生成缩略图				$thumb = '';				if (($width || $height)) {					$thumb = $this->upload->thumb($v['path'], array(						'width'  => $width,						'height' => $height,						'type'   => $type,						'dir'    => $dir,					)					);				}				if (strpos($v['path'], 'http://') === false && strpos($v['path'], 'https://') === false) {					$v['path'] = $url . $v['path'];				}				$v['thumb'] = $thumb ? $thumb : $v['path'];				if (strpos($v['thumb'], 'http://') === false && strpos($v['thumb'], 'https://') === false) {					$v['thumb'] = $url . $v['thumb'];				}				$data[] = $v;				if ($k + 1 == $num) {					break;				}			}		}		if ($num == 1 && $field && $type<=2) {			if (($width || $height)) {				$field = 'thumb';			}			if (empty($array)) {				return $nopic;			}			return url($data[0][$field]);		} else {			$this->smarty->assign($var, $data ? $data : array());		}	}	#用户头像显示	function _photo($arguments) {		$attr = $this->parseAttr($arguments);		@extract($attr);		$rooturl   = substr(RootUrl, 0, strlen(RootUrl) - 1);		$size      = !empty($size) ? $size : '160';		$nopic_arr = array('160' => '/common/photo.jpg', '80' => '/common/photo_mid.jpeg', '30' => '/common/default.gif');		$nopic     = !empty($nopic) ? $nopic : $nopic_arr[$size];		if (empty($source)) {			return $rooturl . $nopic;		}		if (strpos($source, 'http://') !== false) {			return $source;		}		$img = substr($source, 0, strpos($source, '.jpg'));		$img = $img . '_' . $size . '.jpg';		$url = !empty($this->site_config['cloudurl']) ? $this->site_config['cloudurl'] : $rooturl;		return file_exists(RootDir . 'web' . $img) ? $url . $img : $url . $source;	}	#分类面包屑	function _catpos($arguments) {		$attr = $this->parseAttr($arguments);		@extract($attr);		if (!is_numeric($catid)) {			return;		}		$this->load->model('category');		$list_category = $this->category->select();		if (!isset($list_category[$catid])) {			return '';		}		$arrparentid = $list_category[$catid]['arrparentid'];		$arrparentid = explode(',', $arrparentid . ',' . $catid);		$arrparentid = array_filter($arrparentid);		$parsestr = '';		foreach ($arrparentid as $k => $v) {			$str = '<a href="' . url($list_category[$v]['url']) . '">' . $list_category[$v]['catname'] . '</a>';			if (count($arrparentid) == $k && !empty($end)) {$space = $end;}			$parsestr .= (!empty($space) && strpos($space, '###') !== false) ? str_replace('###', $str, $space) : $str;		}		unset($list_category);		echo $parsestr;	}	#文章碎片	function _block($arguments) {		$attr = $this->parseAttr($arguments);		@extract($attr);		$tableLib = '###_block';		$whereLib = " WHERE mark='" . $mark."'" ;        if(!S("CH_BLOCK_".$mark)){            $data = $this->db->getstr("SELECT content FROM " . $tableLib . $whereLib);            S("CH_BLOCK_".$mark,$data,3600);        }else{            $data = S("CH_BLOCK_".$mark);        }        $result = $data ? stripcslashes($data) : '';        if ($return) {            return $result;        } else {            echo $result;        }	}	#模型内容	function _content($arguments) {		$attr = $this->parseAttr($arguments);		@extract($attr);		$tableLib = '';		$whereLib = ' WHERE lang=' . LANG_ID;		$orderLib = ' ORDER BY ';		$limitLib = '';		foreach ($attr as $_key => $_val) {			switch ($_key) {				case 'module':					$$_key = !empty($_val) ? $_val : 'article';					break;				case 'type':					$$_key = !empty($_val) ? $_val : 'list';					break;				case 'child':					$$_key = ($_val !== '') ? $_val : 1;					break;				case 'posid':					if ($$_key) {						$whereLib .= " AND FIND_IN_SET('" . $_val . "',posid) ";					}					break;				case 'where':					$$_key = !empty($_val) ? ' AND ' . $_val : '';					$whereLib .= $$_key;					break;				case 'order':					$$_key = !empty($_val) ? $_val : 'listorder DESC,id DESC';					$orderLib .= $$_key;					break;				case 'limit':					$$_key = !empty($_val) ? (int) $_val : '';					if ($$_key) {						$limitLib .= ' LIMIT ' . $$_key;					}					break;			}		}		$row = array();		if ($catid) {			$this->load->model('category');			$res = $this->category->get($catid);			if (empty($res)) {				return;			}			$module = $res['module'];			$row = $this->db->get("SELECT arrchildid,child FROM ###_category WHERE id=" . $catid);		}		if ($row) {			$whereLib .= ($child && $row['child']) ? " AND catid IN(" . $row['arrchildid'] . ")" : " AND catid=" . $catid;		}		#获取模型及字段信息		$tableLib = '###_' . $module;		$this->load->model('content');		$this->content->chkModule($module);		$fieldsinfo = $this->content->getFieldsinfo();		$moduleinfo = $this->content->getModuleinfo();		if (isset($fieldsinfo['status'])) {			$whereLib .= " AND status=1";		}		if ($moduleinfo['listfields']) {			$field = $field ? $field : $moduleinfo['listfields'];		}		$data = array();		if ($id) {			$whereLib .= " AND id=" . $id;			$data = $this->db->get("SELECT $field FROM " . $tableLib . $whereLib);		} elseif ($type == 'row') {			$data = $this->db->get("SELECT $field FROM " . $tableLib . $whereLib . $orderLib);		} else {			$data = $this->db->select("SELECT $field FROM " . $tableLib . $whereLib . $orderLib . $limitLib);		}		$this->smarty->assign($var, $data ? $data : array());	}	function _cateTree($arguments) {		$attr = $this->parseAttr($arguments);		#echo '<pre>';print_r($attr);echo '</pre>';		$rows = $this->db->select("SELECT id,catname,parentid,url FROM category WHERE lang=" . LANG_ID . " ORDER BY listorder,id");		$items = array();		foreach ($rows as $val) {			$items[$val['id']] = $val;		}		foreach ($items as $item) {			$items[$item['parentid']]['son'][$item['id']] = &$items[$item['id']];		}		$data = isset($items[0]['son']) ? $items[0]['son'] : array();		#echo '<pre>';print_r($data);echo '</pre>';		$cateid = isset($attr['cateid']) ? $attr['cateid'] : 0;		#echo '<pre>';print_r($data);echo '</pre>';		if ($cateid != 0) {			#echo '<pre>';print_r($data[$cateid]);echo '</pre>';			$this->smarty->assign($attr['var'], $data[$cateid]);		} else {			$this->smarty->assign($attr['var'], $data);		}	}	#栏目	function _category($arguments) {		$attr = $this->parseAttr($arguments);		@extract($attr);		$tableLib = '###_category';		$whereLib = ' WHERE lang=' . LANG_ID;		$orderLib = ' ORDER BY listorder DESC,id ASC';		$limitLib = '';		foreach ($attr as $_key => $_val) {			switch ($_key) {				case 'field':					$$_key = !empty($_val) ? $_val : '*';					break;				case 'limit':					$$_key = !empty($_val) ? (int) $_val : '';					if ($$_key) {						$limitLib .= ' LIMIT ' . $$_key;					}					break;				case 'ismenu':					if ($$_key !== '' && ($$_key == 1 || $$_key == 0)) {						$whereLib .= ' AND ismenu=' . $_val;					}					break;				case 'type':					#parent父级 brother同级 child子级 self自己 parentrow父级单独行					$$_key = !empty($_val) ? $_val : 'brother';					break;				case 'catid':					$$_key = !empty($_val) ? $_val : 0;					break;			}		}		$row = array();		if ($catid) {			$row = $this->db->get("SELECT parentid,arrparentid,arrchildid,child FROM $tableLib WHERE id=" . $catid);		}		$data = array();		switch ($type) {			case 'field':				if ($row) {					$whereLib .= ' AND id=' . $catid;					$field = ($field && $field != '*') ? $field : 'catname';					$str   = $this->db->getstr("SELECT $field FROM $tableLib " . $whereLib);					echo $str;				}				break;			case 'self':				if ($row) {					if ($child) {						$whereLib .= " AND id IN(" . $row['arrchildid'] . ")";						$data = $this->db->select("SELECT $field FROM $tableLib " . $whereLib . $orderLib . $limitLib);					} else {						$whereLib .= ' AND id=' . $catid;						$data = $this->db->get("SELECT $field FROM $tableLib " . $whereLib);					}				}				break;			case 'top':				if ($row) {					$arrparent = explode(',', $row['arrparentid']);					$arrparent = array_filter($arrparent);					$topid     = $arrparent[1] ? $arrparent[1] : $catid;					if (empty($topid)) {						break;					}					$row_top = $this->db->get("SELECT arrchildid FROM $tableLib WHERE id=" . $topid);					if ($child) {						$whereLib .= " AND id IN(" . $row_top['arrchildid'] . ")";						$data = $this->db->select("SELECT $field FROM $tableLib " . $whereLib . $orderLib . $limitLib);					} else {						$whereLib .= ' AND id=' . $topid;						$data = $this->db->get("SELECT $field FROM $tableLib " . $whereLib);					}				}				break;			case 'parent':				if ($row) {					if ($row['parentid'] == 0) {$row['parentid'] = $catid;}					$row_parent = $this->db->get("SELECT arrchildid FROM $tableLib WHERE id=" . $row['parentid']);					if ($child) {						$whereLib .= " AND id IN(" . $row_parent['arrchildid'] . ")";						$data = $this->db->select("SELECT $field FROM $tableLib " . $whereLib . $orderLib . $limitLib);					} else {						$whereLib .= ' AND id=' . $row['parentid'];						$data = $this->db->get("SELECT $field FROM $tableLib " . $whereLib);					}				}				break;			case 'child':				if ($row) {					if ($child) {						$whereLib .= " AND id IN(" . $row['arrchildid'] . ") AND id!=" . $catid;						$data = $this->db->select("SELECT $field FROM $tableLib " . $whereLib . $orderLib . $limitLib);					} else {						$whereLib .= ' AND parentid=' . $catid;						$data = $this->db->select("SELECT $field FROM $tableLib " . $whereLib . $orderLib . $limitLib);					}				}				break;			default:				if ($row) {					if ($row['parentid'] == 0) {$row['parentid'] = $catid;}					$row_parent = $this->db->get("SELECT arrchildid FROM $tableLib WHERE id=" . $row['parentid']);					if ($child) {						$whereLib .= " AND id IN(" . $row_parent['arrchildid'] . ") AND id!=" . $row['parentid'];					} else {						$whereLib .= " AND parentid=" . $row['parentid'];					}				} else {					if (!$child) {						$whereLib .= " AND parentid=0";					}				}				$data = $this->db->select("SELECT $field FROM $tableLib " . $whereLib . $orderLib . $limitLib);				break;		}		$this->smarty->assign($var, $data ? $data : array());	}	#轮播图	function _slider($arguments) {		$attr = $this->parseAttr($arguments);		isset($arguments['id']) || die('Error');		$id = $arguments['id'];		is_numeric($id) || die('arguments type is error!');		$slider = $this->db->get("SELECT * FROM site_slider WHERE id=" . $id);		$rows   = $this->db->select("SELECT * FROM site_slider_item WHERE slider_id=" . $id);		$this->load->model('upload');		$rows = $this->upload->getImgUrls($rows, 'id', $slider['imgcate_id'], array('thumb', 'src'));		if (!empty($arguments['debug'])) {			echo '<pre>';			print_r($rows);			echo '</pre>';		}		$this->smarty->assign($attr['var'], $rows);	}	#TagLib标签属性分析 返回标签属性数组	function parseAttr($attr) {		$mod = isset($attr['mod']) ? trim($attr['mod']) : '';		$tags = 'var,debug' . (trim($this->tags[$mod]['attr']) ? (',' . $this->tags[$mod]['attr']) : '');		$tags = explode(',', $tags);		foreach ($tags as $v) {			if ($v == 'var') {				$attr[$v] = isset($attr[$v]) ? $attr[$v] : 'tagData';			}			$attr[$v] = isset($attr[$v]) ? $attr[$v] : '';		}		return $attr;	}}