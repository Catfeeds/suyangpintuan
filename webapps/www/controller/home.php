<?php

/**
 * Class home 首页
 */
class home extends Lowxp
{

    function index()
    {

        $this->display_before();

        $tpl = 'index.html';

        $data = array();

        if (STPL == 'mobile') {

            if (C("time_home") > 0 && !$_SESSION['guide_pages']) {
                $tpl = 'home.html';
                $_SESSION['guide_pages'] = 1;
            } else {
                if (!S("CH_INDEX")) {
                    //Feng 2016-05-24 获取首页广告图
                    $ad_list = $this->get_ad("1");
                    $data['ad_list'] = $ad_list;

                    //Feng 2016-05-25 获取热门，新品，热卖产品
                    /*$this->load->model('goods');
                    $data['list_hot'] = $this->goods->getList(10, 1, 0, 0, array("where" => " and is_hot=1 and typeid in (0,1,2,4,8,9,10) "));

                    $data['list_best'] = $this->goods->getList(10, 1, 0, 0, array("where" => " and is_best=1 "));*/

                    //首页头部导航
                    $data['nav_top'] = get_nav(1);

                    //获取商品分类顶级栏目
                    $this->load->model("goodscat");
                    $data['cat_list'] = $this->goodscat->cate();

                    //秒杀倒计时剩余时间
                    $data['kill_etime'] = kill_etime();

                    //拼团快报
                    $data['square'] = $this->goods->getSquare();


                    S("CH_INDEX", $data, 3600);
                }
                $data = S("CH_INDEX");
                //echo "<pre>";print_r($data);exit;

                //秒杀
                $kill_list = getSpread(1);
                $data['kill'] = $kill_list[rand(0,count($kill_list)-1)];
                //全球购
                $spread2 = getSpread(2);
                $data['spread2'] = $spread2[rand(0,count($spread2)-1)];
                //免单开团
                $spread3 = getSpread(3);
                $data['spread3'] = $spread3[rand(0,count($spread3)-1)];
                //众筹尝鲜
                $spread4 = getSpread(4);
                $data['spread4'] = $spread4[rand(0,count($spread4)-1)];
                //底价清仓
                $spread5 = getSpread(5);
                $data['spread5'] = $spread5[rand(0,count($spread5)-1)];
            }


        } elseif(STPL == 'simple') {

            $this->load->model('goods');
            if (!S("CH_INDEX_SIMPLE")) {
                //获取商城产品
                $data['goods_list'] = $this->goods->getList(10, 1, 0, 0);

                //统计商品数量，上新数量，热销数量
                $data['total'] = $this->db->getstr("select count(1) as num from ###_goods where is_sale=1 and stock>0 and sid=0","num");
                $data['total_hot'] = $this->db->getstr("select count(1) as num from ###_goods where is_sale=1 and is_hot=1 and stock>0 and sid=0","num");
                $data['total_new'] = $this->db->getstr("select count(1) as num from ###_goods where is_sale=1 and is_new=1 and stock>0 and sid=0","num");

                S("CH_INDEX_SIMPLE", $data, 600);
            }
            $data = S("CH_INDEX_SIMPLE");
            //优惠券列表
            $data['coupon'] = $this->db->select("select * from ###_coupon where status=1 and stock>0 and (end_time>=".RUN_TIME." || end_time=0) and sid=0");

            if($_GET['type']=='is_new'){
                $data['goods_list'] = $this->goods->getList(10, 1, 0, 0, array("where" => " and is_new=1 and sid=0"));
            }elseif($_GET['type']=='is_hot'){
                $data['goods_list'] = $this->goods->getList(10, 1, 0, 0, array("where" => " and is_hot=1 and sid=0"));
            }

        } else {
            $this->load->model("business");
            $data['b_info'] = $this->business->get($_SESSION['b_id']);
            $data['b_info']['amount'] = $data['b_info']['fee'] + $data['b_info']['deposit'];
        }

        $this->smarty->assign('qr', 0);
        $this->smarty->assign('data', $data);

        $this->smarty->display($tpl);

    }

    /**
     * 上传图片
     */

    function upload()
    {

        $file = trim($_POST['name']);

        $this->load->model('upload');

        $thumb = array();

        if (!empty($_POST['width']) && !empty($_POST['height'])) {

            $thumb = array('thumb' => array('width' => floor($_POST['width']), 'height' => floor($_POST['height'])));

        }

        echo $this->upload->save_image('upFile', $file, $thumb);

    }

    /**
     * 退款退货上传图片
     */

    function upload_refund()
    {

        $file = trim($_POST['name']);

        $this->load->model('upload');

        $thumb = array();

        $pic = $this->upload->save_image('file', 'refund', $thumb);
        $pic = yunurl($pic);
        $name = substr(strrchr($pic, "/"), 1);
        echo json_encode(array("error" => 0, "pic" => $pic, "name" => $name));

    }

    /**
     * 退款退货图片删除
     */

    function upload_refund_del()
    {

        $pic = trim($_POST['pic']);

        $this->load->model('upload');

        $dir = $this->upload->getCateDir('refund');

        $res = $this->upload->rmFile(WebDir . $pic);
    }

    /**
     * AJAX联动菜单
     */

    function chang_parent()
    {

        $id = (int)$_POST['id'];

        $hidetop = $_POST['hidetop'];

        $field = $_POST['field'];

        $start = htmlspecialchars_decode($_POST['start']);

        $end = htmlspecialchars_decode($_POST['end']);

        if ($id) {

            $this->load->model('linkage');

            $str = $this->linkage->select_linkage($id, $hidetop, $field, true, $start, $end);

            die($str);

        } else {

            die('');

        }

    }
    /**
     * 编辑器上传图片
     */

    function upload_edit()
    {

        $file = trim($_POST['imgFile']);

        $this->load->model('upload');

        $thumb = array();

        $pic = $this->upload->save_image('imgFile', 'edit', $thumb);
        $pic = yunurl($pic);
        $name = substr(strrchr($pic, "/"), 1);
        echo json_encode(array("error" => 0, "url" => $pic));

    }
    /**
     * AJAX 获取城市
     */

    function get_city()
    {

        $id = (int)$_POST['id'];

        if ($id) {

            $this->load->model('linkage');

            $arr = $this->linkage->select($id);

            $string = '';

            foreach ($arr as $k => $v) {
                $string .= '<option value="' . $v['id'] . '" >' . $v['name'] . '</option>';
            }

            die($string);

        } else {

            die('');

        }

    }

    /**
     * 获取广告
     *
     * Feng 2016-05-24 add
     */

    function get_ad($ids)
    {
        $sql = "select * from ###_ad where status=1 and typeid in ({$ids})";
        $list = $this->db->select($sql);
        $res = array();
        if ($list) {
            foreach ($list as $k => $v) {
                $v['images'] = json_decode($v['images'], true);
                $res[$v['typeid']] = $v;
            }
        }
        return $res;
    }

    /**
     * 关于我们
     *
     * Feng 2016-07-04
     */
    function aboutus()
    {
        $this->smarty->display("aboutus.html");
    }

    function special($id = '')
    {
        if(!$id) {
            exit('参数异常');
        }
        $this->load->smarty(array(
            'tplDir' => AppDir . 'views/'. 'special',
            'compileDir' => RUNTIME_PATH . 'views_c/' .'special',
            'cacheDir'   => RUNTIME_PATH . 'cache/' . 'special',
        ));
        #获取专题信息
        $this->load->model('special');
        $special = $this->special->getById($id);
        $special_model = $this->special->getByIdModel($special['special_model_id']);
        $config_value = json_decode($special['config_value']);
        $data = array();
        foreach($config_value as $k=>$v){
            $data[$k] = $v->path[0];
        }
        #获取商品信息
        $this->load->model('goods');
        $data['list'] = $this->db->select("select id,`name`,thumb,thumbs,price,team_price from ###_goods where id in ({$special['goods_id']})");
        foreach($data['list'] as $k=>$v){
            $res = $this->goods->getThumb($v, 1);
            $data['list'][$k]['img_cover'] = $res['img_cover'];
        }
        $this->smarty->assign('data', $data);
        $this->smarty->display($special_model['file_name']);
    }

    /**
     *关闭关注公众号提示
     */
    function ajax_gznr(){
        $_SESSION['gznr'] = 1;
        die();
    }

    //app下载地址
    function appdown(){
        if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')){
            $url = C('ios_url');
        }else if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')){
            $url = C('android_url');
        }
        $this->redirect($url);
    }

    /**
     * 优惠券编辑界面商品分类zTree列表数据源
     * @return json
     */
    public function goodscat() {

        $sql  = "SELECT id, parentid as pId,catname as name FROM ###_goods_cat ORDER BY listorder ASC,id ASC";
        $list = $this->db->select($sql);

        // 所有分类标签
        array_unshift($list, array('id' => 0, 'pId' => 0, 'name' => '可选品类'));

        $target = empty($_GET['target']) ? array() : explode(',', $_GET['target']);
        if ($target) {
            ksort($target);
            foreach ($list as $k => $v) {
                foreach ($target as $m => $n) {
                    if ($v['id'] == $n) {
                        $list[$k]['checked'] = true;
                        unset($target[$m]);
                        break;
                    }
                }
            }
        }

        $res = array(
            'error' => 0,
            'data'  => array(
                'list' => $list,
            ),
        );

        exit(json_encode($res));
    }


    //定时任务 每3分钟
    function task(){
        $this->load->library("task");
        $this->task->run();
        $this->load->model('template_msg');
        $this->template_msg->dealQueue(100);
        $this->load->model('wxtemplate');
        $this->wxtemplate->dealQueue(100);
    }

    //定时任务 每3分钟
    function task_wx(){
        $this->load->model('wxtemplate');
        $this->wxtemplate->dealQueue(100);
    }
}