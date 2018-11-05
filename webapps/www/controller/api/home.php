<?php

/**
 * Class home 首页
 */
class home extends Lowxp{

    /**
     * 首页banner
     */
    public function banner(){
        $this->load->model('upload');
        $ad = $this->db->get("select * from ###_ad where status=1 and typeid = 1");
        if(!$ad){
            $data['flag'] = false;
            $data['code'] = 100001;
            $data['msg'] = '获取广告失败,数据不存在';
        }else {
            $ad['image'] = json_decode($ad['images'], true);
            if (!empty($ad['image'])) {
                foreach ($ad['image'] as $key=>$val) {
                    $ad['image'][$key]['path'] = yunurl($val['path']);
                    $ad['image'][$key]['link'] = $val['title'];
                    $ad['image'][$key]['thumb'] = yunurl($this->upload->thumb($val['path'], array('width' => '640', 'height' => '200')));
                    unset($ad['image'][$key]['title']);
                }
                $data['data'] = $ad['image'];
            }
        }
        unset($ad);
        $this->api_result($data);
    }

    /**
     * 地区
     */
    public function area(){
        $area = $this->db->select("SELECT id,`name`,`parentid` FROM ###_linkage WHERE parentid >= 1");
        $item = array();
        foreach($area as $v){
            $item[$v['id']] = $v;
        }
        $data = $this->tree($item);
        echo json_encode($data);die;
    }
    public function tree($items) {
        $tree = array(); //格式化好的树
        foreach ($items as $item) {
            if (isset($items[$item['parentid']])) {
                $items[$item['parentid']]['sub'][] = &$items[$item['id']];
            } else {
                $tree[] = &$items[$item['id']];
            }
        }
        return $tree;
    }

    /**
     * 常见问题
     */
    public function problem(){
        $this->load->model('page');
        $_GET['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
        $sql = "SELECT id,title,content,catid FROM ###_article WHERE catid = 22 AND status = 1 ORDER BY listorder DESC,id DESC";
        $size = (int) $this->site_config['page_size'];
        $this->page->set_vars(array('per' => $size));
        $list = $this->page->hashQuery($sql)->result_array();
        if(!$list){
            $this->api_result(array('data' => $list));
        }else {
            $data['list_total'] = (int)$this->page->pages['total'];
            foreach ($list as $k => $v) {
                $data['list'][$k]['id'] = $v['id'];
                $data['list'][$k]['title'] = $v['title'];
                $data['list'][$k]['content'] = rep_content($v['content']);
                $data['list'][$k]['url'] = urlencode(url('/content/show/'.$v['catid'].'/'.$v['id']));
            }
        }
        unset($list);
        $this->api_result(array('data'=>$data));
    }

    /**
     * 联系我们
     */
    public function contactUs(){
        $data['logo'] = yunurl(C("share_logo.png","images"));
        $data['name'] = C("site_name");
        $data['version'] = '1.0';
        $data['tel'] = C('business_tel');
        $data['site_name'] = C('site_name');
        $data['copyright'] = C('copyright');
        $data['icp_code'] = C('icp_code');
        $data['content'] = $this->db->getstr("select content from ###_block where mark='app_about'","content");
        $data['content'] = strip_tags(html_entity_decode(rep_content($data['content'])));
        $this->api_result(array('data'=>$data));
    }

    /**
     * 快报
     */
    public function kuaibao(){
        $this->load->model('page');
        $_GET['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
        $sql = "SELECT id,titleprefix,title,links,content FROM ###_kuaibao WHERE catid = 26 AND status = 1 ORDER BY listorder DESC,id DESC";
        $size = (int) $this->site_config['page_size'];
        $this->page->set_vars(array('per' => $size));
        $list = $this->page->hashQuery($sql)->result_array();
        if(!$list){
            $this->api_result(array('data' => $list));
        }else {
            $data['list_total'] = (int)$this->page->pages['total'];
            foreach ($list as $k => $v) {
                $data['list'][$k]['id'] = $v['id'];
                $data['list'][$k]['prefix'] = $v['titleprefix'];
                $data['list'][$k]['title'] = $v['title'];
                $data['list'][$k]['links'] = $v['links'];
                //$data['list'][$k]['content'] = str_replace('http://pintuan.local/', RootUrl, $v['content']);
                $data['list'][$k]['content'] = rep_content($v['content']);
            }
        }
        unset($list);
        $this->api_result(array('data'=>$data));
    }

    /**
     * 版本号
     */
    public function get_version(){
        $data['app_checking'] = C('app_checking');
        $this->api_result(array('data'=>$data));
    }

    /**
     * 获取自定义导航
     */
    public function getNav(){
        $typeid = intval($_REQUEST['typeid']);
        $nav = get_nav($typeid);
        $data['nav'] = null;
        /*if($typeid==1){
            if(defined("MID") && is_new(MID)){
                $temp['img'] = yunurl('/style/img/h-ico10.png');
                $temp['iosurl'] = "NewComer";
                $temp['anurl'] = RootUrl."goods/lists?page=1&typeid=2";
                $temp['title'] = '新人专享';
                $data['nav'][] = $temp;
            }else{
                $temp['img'] = yunurl('/style/img/h-ico10.png');
                $temp['iosurl'] = "Promote";
                $temp['anurl'] = RootUrl."goods/lists?page=1&typeid=8";
                $temp['title'] = '推广团';
                $data['nav'][] = $temp;
            }
		*/
        foreach($nav as $k=>$v){
            $temp = array();
            $img = json_decode($v['img'],true);
            $temp['img'] = yunurl($img[0]['path']);
            $temp['img_hover'] = yunurl($img[1]['path']);
            $temp['iosurl'] = !empty($img[0]['iosurl'])?$img[0]['iosurl']:null;
            $temp['anurl'] = !empty($img[0]['anurl'])?$img[0]['anurl']:null;
            $temp['surl'] = !empty($img[0]['surl'])?$img[0]['surl']:null;
            $temp['title'] = $v['title'];
            $data['nav'][] = $temp;
        }
        $this->api_result(array('data'=>$data));
    }

    /**
     * 获取广告图
     */
    public function getAd(){
        $typeid = intval($_REQUEST['typeid']);
        $ad = getAd($typeid);
        $data['ad'] = null;
        if($ad){
            $img = json_decode($ad,true);
            foreach($img as $k=>$v){
                $temp['path'] = yunurl($v['path']);
                $temp['iosurl'] = !empty($v['iosurl'])?$v['iosurl']:null;
                $temp['anurl'] = !empty($v['anurl'])?$v['anurl']:null;
				$temp['surl']   = !empty($v['surl']) ? $v['surl'] : null;
                $data['ad'][] = $temp;
            }
        }
        $this->api_result(array('data'=>$data));
    }

    /**
     * 获取专题报名列表（用于插入产品列表）
     */
    public function getTA(){
        $data['list'] = null;
        $posid=isset($_GET['posid'])?$_GET['posid']:0;
        $list = getTA($posid);
        //var_dump($data['list']);exit;
        if(is_array($list) && count($list)){
            foreach($list as $k=>$v){
                $v['url'] = RootUrl."topic/index/".$v['id'];
                if($v['apply_list']){
                    foreach($v['apply_list'] as $_k=>$_v){
                       unset($_v['id']);
                       unset($_v['act_id']);
                       unset($_v['cid']);
                       unset($_v['c_time']);
                       unset($_v['u_time']);
                       unset($_v['status']);
                       unset($_v['remark']);
                       unset($_v['listorder']);
                       unset($_v['thumb']);
                       unset($_v['thumbs']);
                       $v['apply_list'][$_k] = $_v;
                    }
                }
                $data['list'][$k] = $v;
            }
        }
        $this->api_result(array('data'=>$data));
    }
    //获取首页推广
    function getSpread(){
        //拼团快报
        $data['square'] = null;
        if(C('is_square')){
            $this->load->model("goods");
            $data['square'] = $this->goods->getSquare();
			if (empty($data['square'])) {
				$data['square'] = null;
			}
        }
        if(C('index_spread')>0){
            //秒杀
            $kill_list = getSpread(1);
            $data['kill'] = $kill_list[rand(0,count($kill_list)-1)];
            $data['kill']['etime'] = kill_etime();

            //全球购
            $spread2 = getSpread(2);
            $data['spread2'] = $spread2[rand(0,count($spread2)-1)];
            $data['spread2']['url'] = RootUrl."goods/nation?order=goods_id&sort={$data['spread2']['id']}";
            //免单开团
            $spread3 = getSpread(3);
            $data['spread3'] = $spread3[rand(0,count($spread3)-1)];
            $data['spread3']['url'] = RootUrl."goods/free_discount?order=goods_id&sort={$data['spread3']['id']}";
            //众筹尝鲜
            $spread4 = getSpread(4);
            $data['spread4'] = $spread4[rand(0,count($spread4)-1)];
            $data['spread4']['url'] = RootUrl."goods/zcgoods?order=goods_id&sort={$data['spread4']['id']}";
            //底价清仓
            $spread5 = getSpread(5);
            $data['spread5'] = $spread5[rand(0,count($spread5)-1)];
            $data['spread5']['url'] = RootUrl."goods/brand_kill?order=goods_id&sort={$data['spread5']['id']}";
        }else{
            $data['kill'] = $data['spread2'] = $data['spread3'] = $data['spread4'] = $data['spread5'] = null;
        }


        $this->api_result(array('data'=>$data));
    }
    /**
     * 获取站点配置
     */
    public function getCon(){
        //$data['site_name'] = C('site_name');//站点名称
        $data['seo_title'] = C('seo_title');//站点标题
        $data['share_url'] = REQUEST_SCHEME.C('wx_url').'/';//分享域名
        $data['comm_limit_max'] = C('comm_limit_max');//佣金提现最高金额
        $data['comm_limit_min'] = C('comm_limit_min');//佣金提现最低金额
        $this->api_result(array('data'=>$data));
    }

    /**
     * 文章碎片
     */
    function block() {
        $mark = trim($_GET['mark']);
        if (empty($mark)) {
            $this->api_result(array('msg' => '缺少标记', 'code' => 100001));
        }
        $this->load->model('taglib');
        $data = $this->taglib->_block(array('mark' => $mark, 'return' => true));
        $this->api_result(array('data' => $data));
    }

    /**
     * 经纬度获取位置信息
     */
    function getLocation(){
        $location = trim($_REQUEST['location']);
        $key = "TWABZ-ZLIRP-GKJDF-V5GIA-XDYUZ-VGFRG";
		if (empty($location)) {
			$this->api_result(array('msg' => '缺少经纬度信息', 'code' => 10001));
		}
        $result = http("http://apis.map.qq.com/ws/geocoder/v1/?location=$location&key=$key");
        $result = json_decode($result,true);
        if($result['status']==0){
            $this->api_result(array('data'=>$result['result']['address_component']));
        }else{
            $this->api_result(array('msg'=>$result['message'],'code'=>$result['status']));
        }
    }
}