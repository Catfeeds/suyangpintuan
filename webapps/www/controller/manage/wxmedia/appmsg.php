<?php

/**

 * Name 微信菜单设置

 * Class wxmenu

 */

class appmsg extends Lowxp {

	/**

	 * 微信菜单设置

	 */

	function add($wxitem = array()) {

		if (!isset($wxitem['id'])) {

			$wxitem = array(

				'id'              => '',

				'title'           => '',

				'desc'            => '',

				'content'         => '',

				'cover'           => '',

				'cover_in_detail' => '',

				'src_link'        => '',

				'author'          => '',

			);

		}

		$this->smarty->assign('news', $wxitem);

		$this->smarty->display('manage/wxmedia/appmsg/form.html');

	}

	/**

	 * 添加多图文

	 */

	function addMutil($mutil_news = array()) {

		if (!isset($mutil_news['id'])) {

			$mutil_news = array(

				'id'    => '',

				'items' => array(

					array(

						'id'      => '',

						'title'   => '',

						'content' => '',

					),

					array(

						'id'      => '',

						'title'   => '',

						'content' => '',

					),

				),

			);

		}

		$this->smarty->assign('mutil_news_data', json_encode($mutil_news));

		$this->smarty->assign('news_id', $mutil_news['id']);

		$this->smarty->display('manage/wxmedia/appmsg/mutil_form.html');

	}

	/**

	 * 多图文编辑

	 * @param string $news_id

	 */

	function editMutil($news_id = '') {

		is_numeric($news_id) || die('Error!');

		$wxnews = $this->db->select("SELECT * FROM ###_wx_news WHERE id=" . $news_id);

		$this->load->model('wxnews');

		$wxnews = $this->wxnews->getNewsInfo($wxnews);

		$mutil_news = $wxnews[0];

		$this->addMutil($mutil_news);

	}

	function edit($news_id = '') {

		is_numeric($news_id) || $this->fatalError('Error!');

		$wxitem = $this->db->get("SELECT * FROM ###_wx_news_item WHERE news_id=" . $news_id);

		$this->load->model('upload');

		$wxitem = $this->upload->getImgUrls($wxitem, 'cover', 'gallery', array('thumb', 'src'));

		$this->add($wxitem);

	}

	/**

	 * 显示图文模板

	 * @param string $news_id

	 */

	function showAppmsgTpl($news_id = '') {

		is_numeric($news_id) || $this->fatalError('图文ID必须为数字!');

		$wxnews = $this->db->select("SELECT * FROM ###_wx_news WHERE id=" . $news_id);

		#echo '<pre>';print_r($news);echo '</pre>';

		$this->load->model('wxnews');

		$wxnews = $this->wxnews->getNewsInfo($wxnews);

		#$news = $wxnews[0];

		$list = isset($wxnews[0]['items']) ? $wxnews[0]['items'] : array();

		$this->smarty->assign('list', $list);

		$this->smarty->display('manage/wxmedia/appmsg/appmsg_tpl.html');

	}

	/**

	 * 文本

	 * @param string $id

	 */

	function getText($id = '') {

		is_numeric($id) || $this->fatalError('必须为数字!');

		$text = $this->db->get("SELECT * FROM ###_wx_autoreply_text WHERE id=" . $id);

		if (isset($text['id'])) {
			echo $text['content'];
		}

	}

	/**

	 * 创建多图文内容.

	 */

	function createMutil() {

		if (isset($_POST['news_id']) && is_numeric($_POST['news_id'])) {

			$news_id = $_POST['news_id'];

			$this->db->update('wx_news', array('u_time' => RUN_TIME), array('id' => $news_id));

			$this->db->delete('wx_news_item', array('news_id' => $news_id));

			$tipMsg = '更新成功!';

		} else {

			$this->db->insert('wx_news', array(				

				'c_time' => RUN_TIME,

				'u_time' => RUN_TIME,

			));

			$news_id = $this->db->insert_id();

			$tipMsg = '添加成功!';

		}

		if (isset($_POST['texts']) && is_array($_POST['texts'])) {

			$covers = $_POST['covers'];

			$titles = $_POST['titles'];

			$srclinks = $_POST['srclinks'];

			$coverInDetails = $_POST['cover_in_detail'];

			foreach ($_POST['texts'] as $key => $text) {

				$this->db->insert('wx_news_item', array(

					'title'           => $titles[$key],

					'content'         => $text,

					'src_link'        => $srclinks[$key],

					'cover'           => $covers[$key],

					'cover_in_detail' => isset($coverInDetails[$key]) ? $coverInDetails[$key] : '0',

					'c_time'          => RUN_TIME,

					'news_id'         => $news_id,

				));

			}

		}

		$this->tip($tipMsg);

		$this->exeJs('location.href="/manage#!wxmedia";');

	}

	/**

	 * 更新或新增单图文.

	 */

	function create() {

		$news_item = array(

			'title'           => $_POST['title'],

			'author'          => $_POST['author'],

			'desc'            => $_POST['desc'],

			'content'         => $_POST['content'],

			'cover'           => $_POST['cover'],

			'cover_in_detail' => $_POST['cover_in_detail'],

			'src_link'        => $_POST['src_link'],

			'c_time'          => RUN_TIME,

		);

		if (empty($_POST['cover'])) {
			$this->fatalError('请设置一张图片!');
		}

		$wx_news = array(			
			'u_time' => RUN_TIME,

		);

		if (isset($_POST['wxnews_id']) && is_numeric($_POST['wxnews_id'])) {

			$news_id = $_POST['wxnews_id'];

			$this->db->update('wx_news', $wx_news, array('id' => $news_id));

			$this->db->update('wx_news_item', $news_item, array('news_id' => $news_id));

			$tipMsg = '更新成功!';

		} else {

			$wx_news['c_time'] = RUN_TIME;

			$this->db->insert('wx_news', $wx_news);

			$news_item['news_id'] = $this->db->insert_id();

			$this->db->insert('wx_news_item', $news_item);

			$tipMsg = '添加成功!';

		}

		$this->tip($tipMsg);

		$this->exeJs('location.href="/manage#!wxmedia";');

	}

	/**

	 * 设置内容是否显示.

	 * @param string $news_item_id

	 */

	function setCoverFlag($news_item_id = '') {

		isset($_POST['cover_in_detail']) || $this->fatalError('参数缺失!');

		in_array($_POST['cover_in_detail'], array('0', '1')) || $this->fatalError('参数错误!');

		is_numeric($news_item_id) || $this->fatalError('Error!');

		$this->db->update('wx_news_item', array('cover_in_detail' => $_POST['cover_in_detail']), array('id' => $news_item_id));

		$this->tip('设置成功!');

	}

	/**

	 * 删除图文.

	 * @param string $news_id

	 */

	function rmNews($news_id = '') {

		is_numeric($news_id) || $this->fatalError('参数错误!');

		$row = $this->db->get("SELECT * FROM ###_wx_news WHERE id=" . $news_id);

		if (isset($row['id'])) {

			$this->db->delete('wx_news_item', array('news_id' => $news_id));

			$this->db->delete('wx_news', array('id' => $news_id));

		}

		$this->tip('删除成功!');

		$this->exeJs('main.refresh()');

	}

}