<?php

/**
 * Created by PhpStorm.
 * User: hjr
 * Date: 2016/11/8
 * Time: 13:35
 */
class store extends Lowxp {
    function __construct() {
        parent::__construct();
        $this->load->model('business');
        $this->load->model('linkage');
        $this->load->model('goods');
        $isLogin = isset($_SESSION['mid']);
        if($isLogin){
            $this->load->model('member');
            $this->memberinfo = $this->member->member_info($_SESSION['mid']);
            define('MID',$_SESSION['mid']);
            define('USER',$this->memberinfo['username']);
        }
    }

    /**
     * 店铺信息
     * @param $bid
     * @return mixed
     */
    public function stroe_info($bid){
        $data['info'] = $this->business->get($bid);
        #统计商品数量
        $data['goods_total'] = $this->db->getstr("select count(1) as num from ###_goods where is_sale=1 and sid=".$bid,"num");
        #判断是否已关注
        if($this->db->get("select 1 from ###_business_fav where sid={$bid} and mid=".MID)){
            $data['is_fav'] = true;
        }else{
            $data['is_fav'] = false;
        }
        return $data;
    }

    /**
     * 店铺首页
     */
    public function index(){
        $bid = isset($_GET['bid']) ? intval($_GET['bid']) : false;
		if (!$bid) {
            $this->api_result(array('flag' => false, 'msg' => '参数异常，商家id不能为空', 'code' => 100002));
		}
        $data = $this->stroe_info($bid);
        $business = $data['info'];
        unset($data['info']);
        $data['business']['id'] = $business['id'];
        $data['business']['name'] = $business['name'];
        $data['business']['logo'] = (!empty($business['logo'])) ? yunurl($business['logo']) : null;
        $data['business']['banner'] = (!empty($business['banner'])) ? yunurl($business['banner']) : RootUrl.'style/img/dpt.jpg';
        $data['business']['fav_num'] = $business['fav_num'];
        $data['business']['ad'] = json_decode($business['ad'],true);
        if($data['business']['ad']){
            foreach($data['business']['ad'] as $k=>$v){
                $data['business']['ad'][$k] = yunurl($v['path']);
            }
        }
        $coupon = $this->db->select("select * from ###_coupon where status=1 and stock>0 and (end_time>=".RUN_TIME." || end_time=0) and sid=".$bid);
        $data['coupon'] = null;
        if($coupon){
            foreach($coupon as $k=>$v){
                $data['coupon'][$k]['id'] = $v['id'];
                $data['coupon'][$k]['amount'] = $v['amount'];
                $data['coupon'][$k]['need_amount'] = $v['need_amount'];
                $data['coupon'][$k]['end_time'] = $v['end_time'];
				$data['coupon'][$k]['uniqued']     = $v['uniqued'];

				$data['coupon'][$k]['is_has'] = $this->db->getstr("SELECT count(id) as count from `###_coupon_log` where mid=" . MID . " and coupon_id={$v['id']} ", 'count') ?: 0;

            }
        }

        $data['business']['sell_total'] = $this->db->getstr("select sum(sell+sales_num) as num from ###_goods where sid={$bid}","num");
		if ($data['business']['sell_total'] == '') {
			$data['business']['sell_total'] = '0';
		}
        $data['kf_online'] = $business['kf_online'];
        $this->api_result(array('data' => $data));
    }

    /**
     * 商家商品列表
     */
    public function goods(){
        $bid = isset($_GET['bid']) ? intval($_GET['bid']) : false;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;//页码
        $size = isset($_GET['size']) ? intval($_GET['size']) : (int) $this->site_config['page_size'];//每页显示数量
		if (!$bid) {
            $this->api_result(array('flag' => false, 'msg' => '参数异常，商家id不能为空', 'code' => 100002));
		}
        $options = array();
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
            $orderby = "g.sell {$sort}";
        }
        if ($orderby) {
            $options['order'] = $orderby;
        }
        #商品列表
        $goods = $this->goods->getList($size, $page, '', '', $options);
        if($goods){
            $data['list_total'] = (int)$this->page->pages['total'];
            foreach ($goods as $k => $v) {
                $data['list'][$k]['id'] = $v['id'];
                $data['list'][$k]['typeid'] = $v['typeid'];
                $data['list'][$k]['name'] = $v['name'];
                $data['list'][$k]['price'] = $v['price'];
                $data['list'][$k]['team_price'] = (string)$v['team_price'];
                $data['list'][$k]['discount_price'] = discount($v['price'], $v['team_price']);
                $data['list'][$k]['thumb'] =  $v['img_cover'];
                $data['list'][$k]['team_num'] = $v['team_num'];
                $data['list'][$k]['team_people_num'] = formatNum($v['sell']+$v['sales_num']);
                $data['list'][$k]['luck_num'] = (int)$v['luck_num'];
                $data['list'][$k]['start_time'] = $v['start_time'];
                $data['list'][$k]['end_time'] = $v['end_time'];
                $data['list'][$k]['run_time'] = RUN_TIME;
            }
        }
        unset($goods);
        $this->api_result(array('data'=>$data));
    }

    /**
     * 店铺详情
     */
    public function info(){
        $bid = isset($_GET['bid']) ? intval($_GET['bid']) : false;
		if (!$bid) {
            $this->api_result(array('flag' => false, 'msg' => '参数异常，商家id不能为空', 'code' => 100002));
		}
        $data = $this->stroe_info($bid);
        $business = $data['info'];
        $data['business']['id'] = $business['id'];
        $data['business']['name'] = $business['name'];
        $data['business']['logo'] = yunurl($business['logo']);
        $data['business']['banner'] = yunurl($business['banner']);
        $data['business']['fav_num'] = $business['fav_num'];
        $data['business']['note'] = $business['note'];
		$data['business']['type']    = $business['type'];
        if($business['zone']>0){
            $this->load->model("linkage");
            $zone = $this->linkage->pos_linkage($business['zone'], false);
            $data['business']['zone'] = str_replace('>', ',', $zone);
        }
        $data['business']['c_time'] = $business['c_time'];
		$data['business']['c_time_s'] = date('Y-m-d', $business['c_time']);
        $data['business']['brand'] = json_decode($business['brand'],true);
        if($data['business']['brand']){
            foreach($data['business']['brand'] as $k=>$v){
                $data['business']['brand'][$k] = yunurl($v['path']);
            }
        }
        $url = url('/store/index/'.$bid.'?inviter_id='.$_SESSION['mid']);
        $data['business']['qrcode'] = yunurl(creat_code($url, 'sid_' . $bid . '.png'));
        $data['business']['deposit'] = $business['deposit'];
        $data['business']['sell_total'] = $this->db->getstr("select sum(sell+sales_num) as num from ###_goods where sid={$bid}","num");
        unset($data['info']);
        $this->api_result(array('data'=>$data));
    }

    /**
     * 店铺上新商品列表
     */
    public function goods_news(){
        $bid = isset($_GET['bid']) ? intval($_GET['bid']) : false;
		if (!$bid) {
			$this->api_result(array('flag' => false, 'msg' => '参数异常，商家id不能为空', 'code' => 100002));
		}
        $month = strtotime(date("Y-m",time()));
        #商品列表
        $goods = $this->db->select("select * from ###_goods where is_sale=1 and c_time>".$month." and sid=".$bid);
        if($goods){
            $data['list_total'] = count($goods);
            $data_array = array();
            foreach($goods as $k=>$v){
                $data['list'][$k]['datelog'] = '';
                $temp = strtotime(date("Ymd",$v['c_time']));
                if(!in_array($temp, $data_array)){
                    array_push($data_array,$temp);
                    $data['list'][$k]['datelog'] = "$temp";
                }
                $data['list'][$k]['id'] = $v['id'];
                $data['list'][$k]['typeid'] = $v['typeid'];
                $data['list'][$k]['name'] = $v['name'];
                $data['list'][$k]['price'] = $v['price'];
                $data['list'][$k]['team_price'] = (string)$v['team_price'];
                $data['list'][$k]['discount_price'] = discount($v['price'], $v['team_price']);
                $thumb = json_decode($v['thumbs']);
                $data['list'][$k]['thumb'] = yunurl($thumb[0]->path);
                $data['list'][$k]['team_num'] = $v['team_num'];
                $data['list'][$k]['team_people_num'] = $v['sell'];
                $data['list'][$k]['luck_num'] = (int)$v['luck_num'];
                $data['list'][$k]['start_time'] = $v['start_time'];
                $data['list'][$k]['end_time'] = $v['end_time'];
                $data['list'][$k]['run_time'] = RUN_TIME;
            }
        }
        $this->api_result(array('data'=>$data));
    }

    /**
     * 领取优惠卷
     */
    public function coupon_get(){
		if (!defined('MID')) {
            $this->api_result(array('flag' => false, 'msg' => '请先登录', 'code' => 400001));
		}
        $id = isset($_GET['id']) ? intval($_GET['id']) : false;
		if (!$id) {
            $this->api_result(array('flag' => false, 'msg' => '参数异常，优惠卷id不能为空', 'code' => 100002));
		}
		$coupon = $this->db->get('SELECT * FROM `###_coupon` WHERE id=' . $id);
        if (!$coupon) {
            $this->api_result(array('flag' => false, 'msg' => '对不起.活动不存在或者已过期', 'code' => 100001));
        }
		if (empty($coupon['stock'])) {
			$this->api_result(array('flag' => false, 'msg' => '对不起.已领完, 无剩余数量', 'code' => 100001));
		}
        #判断是否每人是否只能领取一张
        if($coupon['uniqued'] == 1){
            $is_res = $this->db->get("select 1 from ###_coupon_log where mid=".MID." and coupon_id={$id}");
			if ($is_res) {
                $this->api_result(array('flag' => false, 'msg' => '已经领取过', 'code' => 100001));
			}
        }
        $this->load->model("coupon");
        $res = $this->coupon->sendOne(MID, $id);
        if($res){
            $this->api_result(array('msg' => '领取成功'));
        }else{
            $this->api_result(array('flag' => false, 'msg' => '领取失败', 'code' => 100001));
        }
    }

    /**
     * 关注店铺
     */
    public function guanzhu(){
        if(!defined('MID')){
            $this->api_result(array('flag' => false, 'msg' => '请先登录', 'code' => 400001));
        }
        $bid = isset($_GET['bid']) ? intval($_GET['bid']) : false;
		if (!$bid) {
			$this->api_result(array('flag' => false, 'msg' => '参数异常，商家id不能为空', 'code' => 100002));
		}
        $is_res = $this->db->get("select 1 from ###_business_fav where sid={$bid} and mid=".MID);
        if($is_res){
            $this->db->delete("business_fav", array("mid"=>MID,"sid"=>$bid));
            $this->db->update("business", "fav_num=fav_num-1", array("id"=>$bid));
            $this->api_result(array('msg' => '取消成功'));
        }else{
            $data['mid'] = MID;
            $data['sid'] = $bid;
            $data['c_time'] = RUN_TIME;
            $this->db->insert("business_fav", $data);
            $this->db->update("business", "fav_num=fav_num+1", array("id"=>$bid));
            $this->api_result(array('msg' => '关注成功'));
        }
    }

    /**
     * 店铺街
     */
    function lists(){

        $cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;//页码

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
            $v['logo'] = yunurl($v['logo']);
            $v['goods_num'] = $tmp_res['total'];
            $v['goods_sale'] = $tmp_res['num'];
            $v['area'] = $this->linkage->pos_linkage($v['zone'],false);
            $v['area'] = str_replace(">",",",$v['area']);
            $data['list'][$k] = $v;
        }
        $data['list_total'] = (int)$this->page->pages['total'];
        $this->load->model("goodscat");
        $res = $this->goodscat->loadCats();
        $cat_array = array();
        foreach($res as $k=>$v){
			if ($v['parentid'] == 0) {
				$cat_array[] = $v;
			}
		}
        $data['cat_list'] = genTop($cat_array);
        $data['cid'] = $cid;

        $this->api_result(array('data'=>$data));
    }
	
	//附近商家
    function nearby(){

        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
        $latitude = isset($_GET['latitude']) ? $_GET['latitude'] : 0;
        $longitude = isset($_GET['longitude']) ? $_GET['longitude'] : 0;

        $where = "1 ";
        if($cid>0){
            $where.=" and cid=".$cid;
        }

        $this->load->library("geohash");
        $this->load->model("linkage");
        //判断是否有缓存会员地理位置
        if($latitude>0 && $longitude>0){

            $n_geohash = $this->geohash->encode($latitude,$longitude);
            
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
                    $val['logo'] = yunurl($val['logo']);
                    $distance = getDistance($latitude, $longitude, $val['latitude'], $val['longitude']);
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
        }else{
            $data['list'] = null;
        }

        $this->load->model("goodscat");
        $res = $this->goodscat->loadCats();
        $cat_array = array();
        foreach($res as $k=>$v){
            if($v['parentid']==0)$cat_array[] = $v;
        }
        $data['cat_list'] = genTop($cat_array);
        $data['cid'] = $cid;

        $this->api_result(array('data'=>$data));
    }

}