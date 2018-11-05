<?php
/**
 * 店铺主页
 * ============================================================================
 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。
 * 网站地址: http://www.lnest.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 */
class store extends Lowxp{
    function __construct(){
        parent::__construct();

        //商家开关
        $this->load->model('business');

        if(!$this->business->business_power){
            //$this->exeJs('location.href="/"');die;
        }

    }
    //获取店铺信息
    function stroe_info($bid){
        $bid = intval($bid);
        if($bid==0){
            $this->exeJs('location.href="/"');die;
        }
        $data['info'] = $this->business->get($bid);
        $data['sid'] = $bid;
        //统计商品数量        
        $data['goods_total'] = $this->db->getstr("select count(1) as num from ###_goods where is_sale=1 and sid=".$bid,"num");

        //判断是否已关注
        $data['is_fav'] = $this->db->get("select 1 from ###_business_fav where sid={$bid} and mid=".MID);

        return $data;
    }

    //店铺主页
    function index($bid=0){
        $bid = intval($bid);
        $data = $this->stroe_info($bid);
        $data['coupon'] = $this->db->select("select * from ###_coupon where status=1 and stock>0 and (end_time>=".RUN_TIME." || end_time=0) and sid=".$bid);
        foreach($data['coupon'] as $k=>$v){
            $data['coupon'][$k]['is_has'] = $this->db->getstr("select 1 from ###_coupon_log where mid=".MID." and coupon_id={$v['id']} ");
        }
        $data['sell_total'] = $this->db->getstr("select sum(sell+sales_num) as num from ###_goods where sid={$bid}","num");
        $before['title'] = $data['info']['name'];
        $before['description'] = clearHtml($data['info']['note']);

        //获取砍价活动
        $option['where'] = " and status=1 and sid=".$bid;
        $this->load->model("bargain");
        $data['bargain_list'] = $this->bargain->getList($option);

        $this->display_before($before);
        $this->smarty->assign('data',$data);
        $this->smarty->display('store/index.html');

    }

    //产品上新
    function goods_news($bid=0,$page=1){

        $data = $this->stroe_info($bid);

        $month = strtotime(date("Y-m",time()));
        $res_list = $this->db->select("select * from ###_goods where is_sale=1 and c_time>".$month." and sid=".$bid);
        $data_array = array();
        foreach($res_list as $k=>$v){
            $res_list[$k]['datelog'] = '';
            $temp = strtotime(date("Ymd",$v['c_time']));
            if(!in_array($temp, $data_array)){
                array_push($data_array,$temp);
                $res_list[$k]['datelog'] = $temp;
            }
        }
        $data['list'] = $res_list;
        $before['title'] = $data['info']['name'];
        $before['description'] = clearHtml($data['info']['note']);
        $this->display_before($before);
        $this->smarty->assign('data',$data);
        $this->smarty->display('store/new.html');
    }

    //获取店铺商品
    function goods($bid=0,$page = 1){
        $options = array();
        $size = 10;
        $data = array('row' => array(), 'url' => '');
        if($bid>0){
            $options['where'] .= " and sid=".$bid;
        }else{
            $options['where'] .= " and sid<0 ";
        }

        #热门，热卖，新品筛选
        if (isset($_GET['is_best']) && $_GET['is_best']>0) {
            $options['where'] .= " and g.is_best = 1";
        }
        if (isset($_GET['is_hot']) && $_GET['is_hot']>0) {
            $options['where'] .= " and g.is_hot = 1";
        }
        if (isset($_GET['is_new']) && $_GET['is_new']>0) {
            $options['where'] .= " and g.is_new = 1";
        }

        #排序
        $order = isset($_REQUEST['order']) ? trim($_REQUEST['order']) : 'sales';
        $sort = isset($_REQUEST['sort']) ? trim($_REQUEST['sort']) : 'DESC';
        $orderby = '';
        if ($order == 'sales') {
            //销量
            $orderby = "g.sell {$sort}";
        }
        if ($orderby) {
            $options['order'] = $orderby;
        }
        #商品列表
        $data['list'] = $this->goods->getList($size, $page, '', '', $options);

        #异步加载
        if (isset($_GET['load'])) {
            $content = '';
            if ($data['list']) {
                foreach ($data['list'] as $v) {
                    $this->smarty->assign('m', $v);
                    $content .= $this->smarty->fetch('store/lbi/goods_list.html');
                }
            }
            echo $content;
            die;
        }
        $data_temp = $this->stroe_info($bid);
        $data_temp['list'] = $data['list'];
        $this->smarty->assign('data',$data_temp);
        $before['title'] = $data_temp['info']['name'];
        $before['description'] = clearHtml($data_temp['info']['note']);
        $this->display_before($before);
        $this->smarty->display('store/goods.html');

    }

    //店铺详情
    function info($bid=0){
        $data = $this->stroe_info($bid);
        if($data['info']['zone']>0){
            $this->load->model("linkage");
            $data['info']['zone'] = $this->linkage->pos_linkage($data['info']['zone'], false);
            $data['info']['zone'] = str_replace('>', ',', $data['info']['zone']);
        }
        $data['sell_total'] = $this->db->getstr("select sum(sell+sales_num) as num from ###_goods where sid={$bid}","num");
        $this->smarty->assign('data',$data);
        $before['title'] = $data['info']['name'];
        $before['description'] = clearHtml($data['info']['note']);
        $this->display_before($before);
        $this->smarty->display('store/info.html');
    }

    //店铺二维码
    function qrcode($bid=0){
        $data = $this->stroe_info($bid);
        $url = url('/store/index/'.$bid.'?inviter_id='.$_SESSION['mid']);
        $data['qrcode'] = creat_code($url, 'sid_' . $bid . '.png');
        $this->smarty->assign('data',$data);
        $this->smarty->display('store/qrcode.html');
    }

    //领取优惠券
    function ajax_coupon_get($id){
        if(!defined('MID')){
            echo json_encode(array("error"=>1,"status"=>1,"msg"=>"请先登录"));exit;
        }
        $id = intval($id);
        $this->load->model("coupon");
        $coupon = $this->db->get('SELECT uniqued FROM `###_coupon` WHERE id=' . $id);
        if (!$coupon) {
            echo json_encode(array("error"=>1,"msg"=>"对不起.活动不存在或者已过期."));exit;
        }
        #判断是否每人是否只能领取一张
        if($coupon['uniqued'] == 1){
            $is_res = $this->db->get("select 1 from ###_coupon_log where mid=".MID." and coupon_id={$id}");
            if($is_res){
                echo json_encode(array("error"=>1,"msg"=>"已经领取过"));exit;
            }
        }
        $res = $this->coupon->sendOne(MID, $id);
        if($res){
            echo json_encode(array("error"=>0,"msg"=>"领取成功"));exit;
        }else{
            echo json_encode(array("error"=>1,"msg"=>"领取失败"));exit;
        }
    }

    //扫码领取
    function scanCouponHandle($id){
        if(!defined('MID')){
            $this->error('请先登录', '/member/login');
        }
        $coupon = $this->db->get('SELECT id,stock,uniqued FROM `###_coupon` WHERE id=' . $id);
        if (!$coupon) {
            $this->error('对不起.活动不存在或者已过期.', '/');
        }
        if (0 >= $coupon['stock']) {
            $this->error('对不起.优惠券已被领完下次请早哦', '/');
        }
        #判断是否每人是否只能领取一张
        if($coupon['uniqued'] == 1){
            $sql = 'SELECT id FROM `###_coupon_log` WHERE coupon_id = ' . $coupon['id'] . ' AND mid=' . MID;
            $is_res = $this->db->get($sql);
            if ($is_res) {
                $this->error('对不起.您已经领取过优惠券了哦', '/coupon/index');
            }
        }
        $this->load->model('coupon');
        if ($this->coupon->sendOne(MID, $coupon['id'], 2)) {
            $this->success('恭喜你, 获得优惠券一张', '/coupon/index');
        } else {
            $this->error('对不起, 扫码抢优惠券的人太多.请稍后重试', '/');
        };
    }

    //关注店铺
    function ajax_guanz($bid=0){
        if(!defined('MID')){
            echo json_encode(array("error"=>1,"status"=>1,"msg"=>"请先登录"));exit;
        }
        $bid = intval($bid);
        $is_res = $this->db->get("select 1 from ###_business_fav where sid={$bid} and mid=".MID);
        if($is_res){
            $res = $this->db->delete("business_fav", array("mid"=>MID,"sid"=>$bid));
            if($res){
                $this->db->update("business", "fav_num=fav_num-1", array("id"=>$bid));
                echo json_encode(array("error"=>0,"status"=>2,"msg"=>"取消关注"));exit;
            }
        }else{
            $data['mid'] = MID;
            $data['sid'] = $bid;
            $data['c_time'] = RUN_TIME;
            $res = $this->db->insert("business_fav", $data);
            if($res){
                $this->db->update("business", "fav_num=fav_num+1", array("id"=>$bid));
                echo json_encode(array("error"=>0,"status"=>1,"msg"=>"关注"));exit;
            }
        }
        echo json_encode(array("error"=>1,"status"=>1,"msg"=>"关注"));exit;

    }

    //店铺街
    function lists($cid=0,$page=1){
        $where = "1 and status=1 ";
        if($cid>0){
            $where.=" and cid=".$cid;
        }

        $order = " order by id desc";
        #分页
        $this->load->model('page');
        $_GET['page'] = intval($page);
        $this->page->set_vars(array('per' => 10));
        $sql = "select id,logo,name,zone from `###_business` where $where $order";
        $data['list'] = $this->page->hashQuery($sql)->result_array();
        $this->load->model("linkage");
        foreach($data['list'] as $k=>$v){
            $tmp_res = $this->db->get("select sum(sell+sales_num) as num,count(1) as total from ###_goods where sid={$v['id']}");
            $v['goods_num'] = $tmp_res['total'];
            $v['goods_sale'] = $tmp_res['num'];
            $v['area'] = $this->linkage->pos_linkage($v['zone'],false);
            $v['area'] = str_replace(">",",",$v['area']);
            $data['list'][$k] = $v;
        }

        #异步加载
        if (isset($_GET['load'])) {
            $content = '';
            if ($data['list']) {
                foreach ($data['list'] as $v) {
                    $this->smarty->assign('m', $v);
                    $content .= $this->smarty->fetch('store/lbi/lists.html');
                }
            }
            echo $content;
            die;
        }

        $this->load->model("goodscat");
        $res = $this->goodscat->loadCats();
        $cat_array = array();
        foreach($res as $k=>$v){
            if($v['parentid']==0)$cat_array[] = $v;
        }
        $data['cat_list'] = $cat_array;
        //echo "<pre>";print_r($data);exit;
        $data['cid'] = $cid;
        $this->smarty->assign('data', $data);
        $this->smarty->display('store/lists.html');
    }

    //后台商家定位
    function ltion(){
        if(isset($_POST['Submit'])){
            $id = intval(decrypt_de($_POST['id']));
            $lnglat = $_POST['lnglat'];
            $lnglat_array = explode(',',$lnglat);
            $updata['longitude'] = $lnglat_array[0];
            $updata['latitude'] = $lnglat_array[1];
            $this->load->library("geohash");
            $updata['geohash'] = $this->geohash->encode($updata['latitude'],$updata['longitude']);
            $res = $this->db->update("business",$updata,array("id"=>$id));
            if($res !== false){
                echo json_encode(array("code"=>0,"msg"=>"定位成功"));exit;
            }else{
                echo json_encode(array("code"=>1,"msg"=>"定位失败"));exit;
            }
        }
        $id = intval(decrypt_de($_REQUEST['id']));
        if($id>0){
            $row = $this->db->get("select latitude,longitude from ###_business where id={$id}");
            $this->smarty->assign("row",$row);
            $this->smarty->assign("id",$_GET['id']);
            $this->smarty->display("common/getlbs.html");
        }else{
            die("ID不存在");
        }
    }

    //缓存会员地址位置
    function save_lbs(){
        if($_POST['latitude']){
            $_SESSION['latitude'] = $_POST['latitude'];
            $_SESSION['longitude'] = $_POST['longitude'];
        }
    }

    //附近商家
    function nearby($cid=0,$page = 1){

        $where = "1 ";
        if($cid>0){
            $where.=" and cid=".$cid;
        }

        $this->load->library("geohash");
        $this->load->model("linkage");
        //判断是否有缓存会员地理位置
        if(isset($_SESSION['latitude'])){
            $row['latitude'] = $_SESSION['latitude'];
            $row['longitude'] = $_SESSION['longitude'];
            $n_geohash = $this->geohash->encode($row['latitude'],$row['longitude']);

            //附近，参数n代表Geohash，精确的位数，也就是大概距离；n=6时候，大概为附近1千米
            $n = 5;
            $like_geohash = substr($n_geohash, 0, $n);
            $sql = 'select id,name,logo,zone,latitude,longitude from ###_business where geohash like "'.$like_geohash.'%" and '.$where;

            $data['list'] = $this->db->select($sql);
            //算出实际距离
            if($data['list']){
                foreach($data['list'] as $key =>$val) {
                    $tmp_res = $this->db->get("select sum(sell+sales_num) as num,count(1) as total from ###_goods where sid={$val['id']}");
                    $val['goods_num'] = $tmp_res['total'];
                    $val['goods_sale'] = $tmp_res['num'];
                    $distance = getDistance($row['latitude'], $row['longitude'], $val['latitude'], $val['longitude']);
                    $val['distance'] = $distance."km";
                    $data['list'][$key] = $val;
                    //排序列
                    $sortdistance[$key] = $distance;
                }
                //距离排序
                array_multisort($sortdistance,SORT_ASC,$data['list']);

                $size = 10;
                $start = $size*($page-1);
                $data['list'] = array_slice($data['list'],$start,$size);
            }
        }

        //echo "<pre>";print_r($data);exit;
        #异步加载
        if (isset($_GET['load'])) {
            $content = '';
            if ($data['list']) {
                foreach ($data['list'] as $v) {
                    $this->smarty->assign('m', $v);
                    $content .= $this->smarty->fetch('store/lbi/nearby.html');
                }
            }
            echo $content;
            die;
        }

        $this->load->model("goodscat");
        $res = $this->goodscat->loadCats();
        $cat_array = array();
        foreach($res as $k=>$v){
            if($v['parentid']==0)$cat_array[] = $v;
        }
        $data['cat_list'] = $cat_array;
        $data['cid'] = $cid;

        $this->smarty->assign('data', $data);
        $this->smarty->display('store/nearby.html');
    }

}