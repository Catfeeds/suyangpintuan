<?php

/**

 * ZZCMS 上传控件

 * ============================================================================

 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。

 * 网站地址: http://www.lnest.com；

 * ----------------------------------------------------------------------------

 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和

 * 使用；不允许对程序代码以任何形式任何目的的再发布。

 */
class upload extends Lowxp {

    /**
     * 上传图片框
     */
    function showBox() {
        $this->getImageList(1);
        $uri = isset($_GET['imageUrl']) ? $_GET['imageUrl'] : '';
        if ($uri != 'http://') {
            $segments = parse_url(isset($_GET['imageUrl']) ? $_GET['imageUrl'] : '');
            if (isset($segments['host']) && strpos(RootUrl, $segments['host']) !== false) {
                $_GET['imageUrl'] = $segments['path'];
            }
        }
        $this->smarty->assign($_GET);
        $this->smarty->display('business/upload/image/box.html');
    }

    /**
     * 点击上传图片，加载上传图片swf插件
     */
    function uploadBox() {
        /*$rows = $this->db->select("SELECT * FROM ###_gallery_tag WHERE `type`='image' and (sid=".BID." or sid=0 and id=1) " );
        $this->smarty->assign('gallery_tags', $rows);*/

        $this->load->model("gallery_tag");
        $select_categorys = $this->gallery_tag->category_option('','','','','and (sid='.BID.' or sid=0 and id=1)');
        $this->smarty->assign('select_categorys', $select_categorys);

        $this->smarty->display('business/upload/image/upbox.html');
    }

    /**
     * 读取图库列表
     * @param string $type
     */
    function getImageList($type = '') {
        $this->load->model('page');
        $this->load->model('gallery_tag');
        $this->load->library('tree');
        //读取分类
        /*$cates = $this->db->select("SELECT * FROM ###_gallery_tag WHERE `type`='image' and (sid=".BID." or sid=0 and id=1) ORDER BY id");
        $this->smarty->assign('cates', $cates);
        $tag_id = isset($_GET['tag_id']) ? $_GET['tag_id'] : $cates[0]['id'];
        $this->smarty->assign('tag_id', $tag_id);*/
        $cates = $this->gallery_tag->select('','and (sid='.BID.' or sid=0 and id=1)');
        $tag_id = isset($_GET['tag_id']) ? $_GET['tag_id'] : current($cates)['id'];
        $this->smarty->assign('tag_id', $tag_id);
        $array = array();
        foreach($cates as $v){
            $v['hon'] = '';
            if($tag_id==$v['id'])$v['hon'] = 'on';
            $array[] = $v;
        }
        $this->tree->set_params($array);
        $str = "<li><a href='javascript:;' rel='\$id' class='e2-upload-chTag-\$id \$hon'>\$spacer\$name</a></li>";
        $cates = $this->tree->get_tree(0, $str);
        $this->smarty->assign('cates', $cates);

        $this->page->set_vars(array("url" => 'href="/business/upload/getImageList?page={num}&tag_id=' . $tag_id . '&category=gallery&ajax=1" title="第{num}页" '));
        //默认读取首个分类:默认分类
        $data = $this->page->query("SELECT * FROM ###_gallery_images WHERE tag_id=" . $tag_id . " AND sid=".BID." ORDER BY id DESC")->result_array();
        $this->load->model('share');
        $data = $this->db->lJoin($data, 'gallery_tag', 'id,name', 'tag_id', 'id', 'tag_');
        $this->load->model('upload');
        $data = $this->upload->getGalleryImgUrls($data);
        $this->smarty->assign('list', $data);
        if ($type == '') {
            $this->smarty->display('business/upload/image/list.html');
        }
    }

    /**
     * 上传图片
     */
    function upImage() {
        $this->load->model('upload');
        $tag_id = $_POST['tag_id'];
        $sid = $_POST['sid'];
        $watermark = $_POST['watermark'];//var_dump($_POST);exit;
        //编辑器用图
        $this->db->insert('gallery_images', array(
            'tag_id' => $tag_id, #图片分类ID
            'sid' => $sid, #商家SID
        ));
        $id = $this->db->insert_id();
        //$this->upload->image($id, 'upFile', 'gallery');
        $this->upload->fxFiles($id, 'gallery', $_FILES['upFile'], 'image',$watermark);
    }

    //===============上传文件=========Start//
    /**
     * 上传图片框
     */
    function showFileBox() {
        $this->getFileList(1);
        $uri = isset($_GET['imageUrl']) ? $_GET['imageUrl'] : '';
        if ($uri != 'http://') {
            $segments = parse_url(isset($_GET['imageUrl']) ? $_GET['imageUrl'] : '');
            if (isset($segments['host']) && strpos(RootUrl, $segments['host']) !== false) {
                $_GET['imageUrl'] = $segments['path'];
            }
        }
        $this->smarty->assign($_GET);
        $this->smarty->display('business/upload/file/box.html');
    }

    /**
     * 读取图库列表
     * @param string $type
     */
    function getFileList($type = '') {
        $this->load->model('page');
        //读取分类
        $cates = $this->db->select("SELECT * FROM ###_gallery_tag WHERE `type`='file' ORDER BY id");
        $this->smarty->assign('cates', $cates);
        $tag_id = isset($_GET['tag_id']) ? $_GET['tag_id'] : $cates[0]['id'];
        $this->smarty->assign('tag_id', $tag_id);
        //默认读取首个分类:默认分类
        $data = $this->page->query("SELECT * FROM ###_gallery_images WHERE tag_id=" . $tag_id ." AND sid=".BID)->result_array();
        $data = $this->db->lJoin($data, 'gallery_tag', 'id,name', 'tag_id', 'id', 'tag_');
        $this->load->model('upload');
        $data = $this->upload->getFileUrls($data);
        $this->smarty->assign('list', $data);
        if ($type == '') {
            $this->smarty->display('business/upload/file/list.html');
        }
    }

    /**
     * 显示上传文件框.
     */
    function upfileBox() {
        $rows = $this->db->select("SELECT * FROM ###_gallery_tag WHERE `type`='file' ");
        $this->smarty->assign('gallery_tags', $rows);
        $this->smarty->display('business/upload/file/upbox.html');
    }

    /**
     * 上传文件
     */
    function upFile() {
        $this->load->model('upload');
        $tag_id = $_POST['tag_id'];
        $sid = $_POST['sid'];
        //编辑器用图
        $this->db->insert('gallery_images', array(
            'tag_id' => $tag_id, #图片分类ID
            'sid' => $sid, #商家SID
        ));
        $id = $this->db->insert_id();
        $this->upload->upfile($id, 'upFile');
    }

    //=================媒体库管理======
    function media($page = 1, $type = 'image') {
        $this->load->model('page');
        $this->page->set_vars(array(
            'per' => 20,
            'url' => 'href="#!upload/media/{num}/' . $type . '"',
        ));
        $_POST['page'] = $page;
        if ($type == 'image') {
            $list = $this->page->query("SELECT * FROM ###_images  ORDER BY id DESC")->result_array();
            $list = $this->setImagesUrl($list);
        } else {
            $list = $this->page->query("SELECT * FROM ###_files  ORDER BY id DESC")->result_array();
            $list = $this->setFilesUrl($list);
        }
        $this->smarty->assign('type', $type);
        $this->smarty->assign('title', '媒体管理');
        $this->smarty->assign('list', $list);
        if (isset($_POST['list'])) {
            $this->smarty->display('business/upload/media/image_list.html');
        } else {
            $this->smarty->display('business/upload/media/image.html');
        }
    }

    /**
     * 云图片生成
     */
    public function cloudimg() {
        $this->load->model('setting');
        $site_config = $this->setting->value("'cloudsave'");
        if ($site_config['cloudsave']) {
            $list = $this->db->select("SELECT * FROM ###_images  ORDER BY id DESC");
            if ($list) {
                $this->load->model('upload');
                foreach ($list as $val) {
                    $this->upload->yunsave($val['cate'] . '/' . $val['imgurl']);
                }
            }
            $this->tip('生成成功!');
        } else {
            $this->tip('暂未开启云存储!');
        }
    }

    /**
     * 补全图片路径.
     * @param $list
     * @return mixed
     */
    private function setImagesUrl($list) {
        static $upUrl;
        if (is_null($upUrl)) {
            $upUrl = $this->load->config('picture', 'image_url');
        }
#保存目录
        #$imgurl = $image['imgurl'];
        foreach ($list as $key => $val) {
            $imgPath                    = $upUrl . $val['cate'] . '/';
            $list[$key]['imgurl_src']   = $imgPath . $val['imgurl'];
            $list[$key]['imgurl_thumb'] = $imgPath . str_replace('_src', '_thumb', $val['imgurl']);
        }
        return $list;
    }

    private function setFilesUrl($list) {
        static $upUrl;
        if (is_null($upUrl)) {
            $upUrl = $this->load->config('picture', 'file_url');
        }
#保存目录
        #$imgurl = $image['imgurl'];
        foreach ($list as $key => $val) {
            $imgPath                = $upUrl . $val['cate'] . '/';
            $list[$key]['file_url'] = $imgPath . $val['fileurl'];
        }
        return $list;
    }

    /**
     * 删除媒体图片
     * @param $id
     */
    function delMediaImage($id) {
        is_numeric($id) || $this->fatalError('参数错误!');
        //读取image信息.
        $row      = $this->db->get("SELECT * FROM ###_images WHERE id=" . $id);
        $category = $row['cate'];
        //列出该目录下的所有文件.
        //删除gallery中的记录
        if ($category == 'gallery') {
            $data_id = $row['data_id'];
            $this->db->delete('gallery_images', array('id' => $data_id));
        }
        $this->db->delete('images', array('id' => $id));
        $this->rmImagesByCate($category, $row['data_id']);
        $this->tip('删除成功!');
    }

    /**
     * 删除媒体文件
     * @param $id
     */
    function delMediaFile($id) {
        is_numeric($id) || $this->fatalError('参数错误!');
        //读取image信息.
        $row      = $this->db->get("SELECT * FROM ###_files WHERE id=" . $id);
        $category = $row['cate'];
        //列出该目录下的所有文件.
        $this->rmFilesByCate($category, $row['data_id']);
        $this->db->delete('files', array('id' => $id));
        $this->tip('删除成功!');
    }

    /**
     * 删除某个分类下的图片
     * @param $category
     * @param $data_id
     */
    private function rmImagesByCate($category, $data_id) {
        $this->load->model('upload');
        $IdCat   = $this->upload->IdCat($data_id);
        $FullDir = $this->upload->getFullDir($IdCat, $category);
        if (!is_dir($FullDir)) {
            $this->fatalError('删除失败!');
        }

        $files = $this->matchFiles($FullDir, $data_id . '_');
        foreach ($files as $fileName) {
            $filePath = $FullDir . $fileName;
            if (is_file($filePath)) {
                unlink($filePath);
            }
        }
    }

    /**
     * 删除某个分类下的文件
     * @param $category
     * @param $data_id
     */
    private function rmFilesByCate($category, $data_id) {
        $this->load->model('upload');
        $IdCat   = $this->upload->IdCat($data_id);
        $FullDir = $this->upload->getFullDir($IdCat, $category, 'file_dir');
        if (!is_dir($FullDir)) {
            $this->fatalError('删除失败!');
        }

        $files = $this->matchFiles($FullDir, $data_id . '.');
        foreach ($files as $fileName) {
            $filePath = $FullDir . $fileName;
            if (is_file($filePath)) {
                unlink($filePath);
            }
        }
    }

    /**
     * 匹配相同文件前缀(ID_/ID.)的文件
     * @param $dir
     * @param $filePrefix
     * @return array|bool
     */
    private function matchFiles($dir, $filePrefix) {
        if (!is_dir($dir)) {
            return false;
        }

        //打开目录
        $handle = opendir($dir);
        $files  = array();
        //阅读目录
        while (false != ($file = readdir($handle))) {
            if ($file == '.' && $file == '..') {
                continue;
            }

            if (is_file("$dir/$file")) {
                if (strpos($file, $filePrefix) === 0) {
                    $files[] = $file;
                }
            }
        }
        return $files;
    }

}
