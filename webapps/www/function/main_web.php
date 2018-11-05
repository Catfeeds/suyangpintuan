<?php #前台函数库，方便模板调用

/** 获取模型字段值，主要用于单选，多选...配置了选项的字段内容
 * @param $value
 * @param $field
 * @param string $catid
 * @param string $module
 * @param type 0获取内容值 1获取配置项数组
 * @return string
 */
function options($value, $field, $catid = '', $module = 'article', $type = 0) {
	global $lowxp;
	$lowxp->load->model('category');
	$lowxp->load->model('content');
	$lowxp->load->library('base');

	if ($catid) {
		$row_category = $lowxp->category->get($catid);
		if (empty($row_category)) {
			return;
		}

		$module = $row_category['module'];
	}

	$lowxp->content->chkModule($module);
	$fieldsinfo = $lowxp->content->getFieldsinfo();
	$options    = $fieldsinfo[$field]['setup']['options'];

	$string = '';
	if (!empty($options)) {
		$array = $lowxp->base->explodeChar($options);
		if ($type == 1) {return $array;}
		$string = $array[$value];
	}

	return $string ? $string : $value;
}

/**
 * SWFUPLOAD 图片上传
 */
function upload_btn($input, $width = '', $height = '', $limit = 1, $file_types = '*.jpg;*.gif;*.png', $file_size = '1 MB') {
	global $lowxp;
	$lowxp->load->library('form');
	$html = $lowxp->form->upload_files($input, $width, $height, $limit, $file_types, $file_size);
	return $html;
}
/**
 * 联动菜单
 */
function linkage($input, $value = '', $str_start = '', $str_end = '') {
	global $lowxp;
	$value = empty($value) ? 1 : $value;
	$lowxp->load->model('linkage');
	$linkage = $lowxp->linkage->select_linkage($value, 1, $input, true, $str_start, $str_end);
	$html    = '<div id="select_linkage">' . $linkage . '</div><script type="text/javascript" src="/style/js/linkage.js"></script>';
	return $html;
}

/** 分享插件
 * @param int $type
 * @return string
 */
function share($comment = '') {
	return '<div class="bdsharebuttonbox"><strong style="float:left">' . (isset($comment['title']) ? $comment['title'] : '') . '</strong><a href="#" class="bds_more" data-cmd="more"></a><a title="分享到QQ空间" href="#" class="bds_qzone" data-cmd="qzone"></a><a title="分享到新浪微博" href="#" class="bds_tsina" data-cmd="tsina"></a><a title="分享到腾讯微博" href="#" class="bds_tqq" data-cmd="tqq"></a><a title="分享到人人网" href="#" class="bds_renren" data-cmd="renren"></a><a title="分享到微信" href="#" class="bds_weixin" data-cmd="weixin"></a></div>
            <script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"' . $comment['text'] . '","bdUrl":"' . $comment['url'] . '","bdMini":"2","bdMiniList":false,"bdPic":"' . $comment['pic'] . '","bdStyle":"' . (isset($comment['bdstyle']) ? $comment['bdstyle'] : '1') . '","bdSize":"' . (isset($comment['bdsize']) ? $comment['bdsize'] : '16') . '"},"share":{}};with(document)0[(getElementsByTagName("head")[0]||body).appendChild(createElement("script")).src="http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion="+~(-new Date()/36e5)];</script>';
}

/** 用户名与昵称显示
 * @param $username
 * @param $nickname
 * @param $type=0手机邮箱只显示一部分，$type=1显示所有长度
 */
function nickname($username, $nickname, $type = 0) {
	if ($nickname) {
		return $type == 0 ? mb_substr($nickname, 0, 8, 'utf-8') : $nickname;
	} else {
		if (is_email($username) && $type = 0) {
			$array    = explode('@', $username);
			$array[0] = mb_substr($array[0], 0, -3, 'utf-8') . '***';
			return implode('@', $array);
		} elseif (is_mobile($username) && $type = 0) {
			return mb_substr($username, 0, -3, 'utf-8') . '***';
		} else {
			return $type == 0 ? mb_substr($username, 0, 8, 'utf-8') : $username;
		}
	}
}

/** 生成自定义导航 缓存
 * @param int $type
 * @return array
 */
function nav($type = 0, $limit = '') {
	global $lowxp;
	$array = array();
	$data  = $lowxp->base->read_static_cache('nav-' . $type);

	if ($data === false) {
		$where = " WHERE status=1 ";
		$where .= $type ? "AND type=" . $type : '';
		$limit = $limit ? ' LIMIT ' . $limit : '';
		$array = $lowxp->db->select("SELECT * FROM ###_nav $where ORDER BY listorder,id " . $limit);
		$lowxp->base->write_static_cache('nav-' . $type, $array);
	} else {
		$array = $data;
	}
	return $array;
}

/** 生成自定义栏目 缓存
 * @param int $type
 * @return array
 */
function cate($type = 0) {
	global $lowxp;
	$array = array();
	$data  = $lowxp->base->read_static_cache('cate-' . LANG_ID . '-' . $type);

	if ($data === false) {
		$where = " WHERE lang=" . LANG_ID;
		$list  = $lowxp->db->select("SELECT * FROM ###_category " . $where . " ORDER BY listorder,id");
		$array = array();
		foreach ($list as $v) {
			$array[$v['id']] = $v;
		}
		$lowxp->base->write_static_cache('cate-' . LANG_ID . '-' . $type, $array);
	} else {
		$array = $data;
	}
	return $array;
}

/** 个人首页（微店）
 * @param $mid 会员mid
 * @return URL
 */
function home($mid) {
	$url = RootUrl . 'agent/mshop/' . $mid;
	return $url;
}

/*头像*/
function photo($mid) {
	global $lowxp;
	$nopic = '/common/photo.gif';
	$mid   = intval($mid);
	if (empty($mid)) {
		return $nopic;
	}

	if (!S("H_MEMBER_DETAIL_" . $mid)) {
		$detail = $lowxp->db->get("select photo from ###_member_detail where mid=" . $mid);
		S("H_MEMBER_DETAIL_" . $mid, $detail, 86400);
		$source = $detail['photo'];
	} else {
		$detail = S("H_MEMBER_DETAIL_" . $mid);
		$source = $detail['photo'];
	}
	$size = 80;
	if (empty($source)) {
		return $nopic;
	}

	$img = substr($source, 0, strpos($source, '.jpg'));
	$img = $img . '_' . $size . '.jpg';
	if (C('cloudsave') && strpos($img, '://') === false) {
		$img = C('cloudurl') . $img;
	}
	if (C('cloudsave') && strpos($source, '://') === false ) {
		$source = C('cloudurl') . $source;
	}
	return is_file(RootDir . 'web' . $img) ? $img : $source;
}

/** 分销等级
 *Feng 2016-06-30
 */
function agent_rank($agent_rank=0) {
	global $lowxp;
	$sql = "select name from ###_member_comms_rank where id={$agent_rank}";
	$res = $lowxp->db->get($sql);
	return $res != false ? $res['name'] : $agent_rank;
}




/** 
 *Feng 2016-08-22 折扣
 */
function discount($price,$team_price){
    if(empty($price) || empty($team_price))return '';
    return round($team_price/$price*10,1);
    
}

/** 
 *Feng 2016-08-29 生成缩略图
 */
function thumb($src,$width,$height){
    global $lowxp;
    $lowxp->load->model('upload');
    if (($width || $height)) {
        $thumb = $lowxp->upload->thumb($src, array('width' => $width, 'height' => $height, 'type' => 1, 'dir' => ''));
    }else{
        $thumb = $src;
    }    
    return $thumb;
    
}
/**
 *判断产品的团类型
 */
function goods_typeid($typeid){
    global $lowxp;
    $lowxp->load->model('order');
    return $lowxp->order->actTypes[$typeid]['title'];
}

function member($mid, $field = 'username', $select = '*'){
	global $lowxp;
	$detail = $lowxp->db->get("select {$select} from ###_member where mid = {$mid}");
	return $detail[$field];
}