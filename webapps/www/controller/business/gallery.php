<?php

/**

 * ZZCMS 图集控件

 * ============================================================================

 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。

 * 网站地址: http://www.lnest.com；

 * ----------------------------------------------------------------------------

 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和

 * 使用；不允许对程序代码以任何形式任何目的的再发布。

 */

class gallery extends Lowxp {

	function xupload() {

		echo '<pre>';
		print_r($_FILES);
		echo '</pre>';

		die;

	}

	/**

	 * 点击上传图片，加载上传图片swf插件

	 */

	function show_upload() {

		$rows = $this->db->select("SELECT * FROM ###_gallery_tag WHERE `type`='image' ");

		$this->smarty->assign('gallery_tags', $rows);

		$this->smarty->display('business/gallery/show_upload.html');

	}

	/**

	 * 点击编辑器图片，加载该文件。

	 */

	function loadGalleryBox() {

		$this->getImageList(1);

		$uri = isset($_GET['imageUrl']) ? $_GET['imageUrl'] : '';

		if ($uri != 'http://') {

			$segments = parse_url(isset($_GET['imageUrl']) ? $_GET['imageUrl'] : '');

			if (isset($segments['host']) && strpos(RootUrl, $segments['host']) !== false) {

				$_GET['imageUrl'] = $segments['path'];

			}

		}

		$this->smarty->assign($_GET);

		$this->smarty->display('business/gallery/box.html');

	}

	function getImageList($type = '') {

		$this->load->model('page');

		//读取分类

		$cates = $this->db->select("SELECT * FROM ###_gallery_tag WHERE `type`='image' ORDER BY id");

		$this->smarty->assign('cates', $cates);

		$tag_id = isset($_GET['tag_id']) ? $_GET['tag_id'] : $cates[0]['id'];

		$this->smarty->assign('tag_id', $tag_id);

		//默认读取首个分类:默认分类
		$this->page->set_vars(array("url" => 'href="/business/gallery/getImageList?page={num}&tag_id=' . $tag_id . '&category=gallery&ajax=1" title="第{num}页" '));

		$data = $this->page->query("SELECT * FROM ###_gallery_images WHERE tag_id=" . $tag_id . " ORDER BY id DESC")->result_array();

		$this->load->model('share');

		$data = $this->db->lJoin($data, 'gallery_tag', 'id,name', 'tag_id', 'id', 'tag_');

		$this->load->model('upload');

		$data = $this->upload->getGalleryImgUrls($data);

		$this->smarty->assign('list', $data);

		if ($type == '') {

			$this->smarty->display('business/gallery/imglist.html');

		}

	}

	/**

	 * 上传图片

	 */

	function upload() {

		$this->load->model('upload');

		$tag_id = $_POST['tag_id'];

		//编辑器用图

		$this->db->insert('gallery_images', array(

			'tag_id' => $tag_id, #图片分类ID

		));

		$id = $this->db->insert_id();

		$this->upload->image($id, 'upFile', 'gallery');
		//$this->upload->fxFiles($id, 'gallery', $_FILES['upFile'], 'image');

	}

	/**

	 * 上传图片

	 */

	function upWechatImg() {

		$this->load->model('upload');

		//编辑器用图

		$this->db->insert('gallery_images', array('tag_id' => '2'));

		$id = $this->db->insert_id();

		$this->upload->image($id, 'upFile', 'gallery');

		$img = $this->upload->getGalleryImgUrls(array('id' => $id));

		#不需要裁剪,做一个图片尺寸大小判断.

		if (isset($_POST['flag'])) {

			$this->exeJs('parent.wxnews.stuffWechatImg(' . json_encode($img) . ')');

		} else {

			$this->exeJs('parent.wxreply.stuffWechatImg(' . json_encode($img) . ')');

		}

	}

}