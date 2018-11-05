<?php
/**
 * Class goods 接口
 */
class goods extends Lowxp{

    function __construct() {
        parent::__construct();
        $this->load->model('goods');
        $this->load->model('goodscat');
        $this->load->model('page');
        $isLogin = isset($_SESSION['mid']);
        if($isLogin){
            $this->load->model('member');
            $this->load->Model('linkage');
            $this->memberinfo = $this->member->member_info($_SESSION['mid']);
            define('MID',$_SESSION['mid']);
            define('USER',$this->memberinfo['username']);
        }
    }

    /**
     * 海淘分类
     */
    public function nation(){

        //获取海淘广告图
        $ad_list = $this->get_ad("11");
        $data['ad_list'] = $ad_list[11]['images'];
        if($data['ad_list']) {
            foreach ($data['ad_list'] as $k => $v) {
                $data['ad_list'][$k]['path'] = yunurl($v['path']);
            }
        }

        //首页头部导航
        $nav_top = get_nav(3);
        foreach($nav_top as $k=>$v){
            $img = json_decode($v['img'],true);
            $temp = array();
            $temp['name'] = $v['title'];
            $temp['img'] = yunurl($img[0]['path']);
            $temp['iosurl'] = $img[0]['iosurl'];
            $temp['anurl'] = $img[0]['anurl'];
			$temp['surl']        = $img[0]['surl'];
            $data['nav_top'][$k] = $temp;
        }
        //获取海淘专题
        $ad_list = $this->get_ad("12");
        $data['ad_list_topic'] = $ad_list[12]['images'];
        if($data['ad_list_topic']){
            foreach($data['ad_list_topic'] as $k=>$v){
                $data['ad_list_topic'][$k]['path'] = yunurl($v['path']);
            }
        }

        //获取商品分类顶级栏目
        $this->load->model("nation");
        $data['cat_list'] = genTop($this->nation->cate());

        //获取国家馆
        $data['country_list'] = array();
        $country = $this->db->select("select id,catname,thumb from ###_country where ismenu=1 ");
        if($country){
            foreach($country as $k=>$v){
                if($v['thumb']){
                    $thumb = json_decode($v['thumb'],true);
                    $v['thumb'] = yunurl($thumb[0]['path']);
                }
                $data['country_list'][$k] = $v;
            }
        }

        $this->api_result(array("data"=>$data));
    }

    //海淘商品列表
    function nation_goods(){
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $this->load->model("nation");
        $data = array('row' => array(), 'id' => $id);
        #加载商品分类
        $data['cats'] = $this->nation->loadCats();

        #海淘分类详情
        if ($id) {
            $data['row'] = isset($data['cats'][$id]) ? $data['cats'][$id] : '';
            if (empty($data['row'])) {
                $this->api_result(array('flag'=>false,'msg'=>'该分类不存在','code'=>100002));
            }
        }

        //列表头部显示二级分类
        /*$cats_array = genTop($data['cats']);
        if($data['row']['parentid']==0 && !isset($_GET['all'])){
            $data['cat_list'] = $cats_array;
            $data['cat_isrec'] = $this->db->select("SELECT * FROM ###_nation WHERE isrec=1 and ismenu=1 and FIND_IN_SET('{$id}',arrparentid) ORDER BY listorder DESC,id DESC");
        }else{
            if($data['row']['child']==0){//同级栏目
                $data['cat_list'] = $this->nation->loadCats($data['row']['parentid']);
            }else{//下级栏目
                $data['cat_list'] = $this->nation->loadCats($id);
            }
        }*/
        $cats_array = genTop($data['cats']);
        if($data['row']['parentid']==0 && (!isset($_GET['top']) || $_GET['top']==0)){
            $data['cat_list'] = $cats_array;
            $data['cat_isrec'] = $this->db->select("SELECT id,catname,thumb FROM ###_nation WHERE isrec=1 and ismenu=1 and FIND_IN_SET('{$id}',arrparentid) ORDER BY listorder DESC,id DESC");
            $data['cat_isrec'] = api_imgurl($data['cat_isrec'],array("thumb"));
            $data['top'] = 0;
            if($_GET['country_id']){
                array_unshift($data['cat_list'],array("id"=>$data['top'],"catname"=>"全部"));
                if($id==0)$data['cat_isrec'] = null;
            }
        }else{
            if(isset($_GET['top']) && $_GET['top']>0){//下级栏目
                $data['cat_list'] = $this->nation->loadCats($id);
            }else{//同级栏目
                $data['cat_list'] = $this->nation->loadCats($data['row']['parentid']);
            }
            if(isset($_GET['top']) && $_GET['top']>0 ){
                $data['top'] = (int)$_GET['top'];
            }else{
                $data['top'] = (int)$data['row']['parentid'];
            }

            $data['cat_list'] = formatCat($data['cat_list']);
            $data['cat_isrec'] = null;
        }


        if($data['cat_list'])$data['cat_list'] = array_values($data['cat_list']);
        unset($data['row']);
        unset($data['cats']);
        $this->api_result(array("data"=>$data));
    }

    /**
     * 商品分类（父级）
     */
    public function pCategory(){
        $list = $this->db->select("select id,catname,parentid,arrparentid,arrchildid,thumb,child from ###_goods_cat where parentid = 0");
        if(!$list){
            $data['flag'] = false;
            $data['code'] = 100001;
            $data['msg'] = '获取商品分类失败';
        }else{
            foreach($list as $k=>$v){
                if(!empty($v['thumb'])){
                    $thumb = json_decode($v['thumb']);
                    $list[$k]['thumb'] = yunurl($thumb[0]->path);
                }else{
                    $list[$k]['thumb'] = RootUrl.'/common/nopic.png';
                }
            }
            $data['data'] = $list;
        }
        unset($list);
        $this->api_result($data);
    }

    /**
     * 商品分类（子级）
     */
    public function cCategory(){
        $id = intval($_GET['id']);
        $arrparentid = trim($_GET['arrparentid']);
        $arrchildid = trim(($_GET['arrchildid']));

        if(!$id)
            $this->api_result(array('flag'=>false,'msg'=>'参数异常，缺少父级id','code'=>100002));
        if(!isset($arrparentid) || $arrparentid == '')
            $this->api_result(array('flag'=>false,'msg'=>'参数异常，缺少父级的父级id集合','code'=>100002));
        if(empty($arrchildid))
            $this->api_result(array('flag'=>false,'msg'=>'参数异常，缺少父级的子级id集合','code'=>100002));

        $list = $this->db->select("select id,catname,parentid,arrparentid,arrchildid,thumb,child from ###_goods_cat where ismenu=1 and parentid = $id");
        $data = array();
        if($arrparentid != '0') {
            $key = 0;
            $data['data'][$key] = array('id' => "$id", 'catname' => '全部');
        }else{
            $key = -1;
        }
        foreach($list as $k=>$v){
            $key++;
            if(!empty($v['thumb'])){
                $thumb = json_decode($v['thumb']);
                $list[$k]['thumb'] = yunurl($thumb[0]->path);
            }else{
                $list[$k]['thumb'] = RootUrl.'/common/nopic.png';
            }
            if($v['child'] == 0){
                $list[$k]['id'] = $v['parentid'];
            }
            if($data['data'][0]['catname'] == '全部'){
                $list[$k]['id'] = $v['id'];
            }
            $data['data'][$key] = $list[$k];
        }
        unset($list);
        $this->api_result($data);
    }

    /**
     * 商品列表
     */
    public function lists(){
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;//商品分类id
        $typeid = isset($_GET['typeid']) ? intval($_GET['typeid']) : 0;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;//页码
        $size = isset($_GET['size']) ? intval($_GET['size']) : 10;//每页显示数量
        $type = isset($_GET['type']) ? intval($_GET['type']) : 0;
        $is_best = isset($_GET['best']) ? intval($_GET['best']) : 0;//热门
        $is_hot = isset($_GET['hot']) ? intval($_GET['hot']) : 0;//热卖
        $is_new = isset($_GET['new']) ? intval($_GET['new']) : 0;//新品
        $nid = $_GET['nid'] ? $_GET['nid'] : 0;//海淘分类id
        $country_id = $_GET['country_id'] ? $_GET['country_id'] : 0;//国家馆id
        $bid = isset($_GET['bid']) ? intval($_GET['bid']) : 0;
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';//搜索
		$indexcat = isset($_GET['indexcat']) ? intval($_GET['indexcat']) : 0;//首页分类

        $data = array();
        #加载商品分类
        $goodscat = $this->goodscat->loadCats();
        #检查商品分类是否存在
        if ($id) {
            $exist = isset($goodscat[$id]) ? $goodscat[$id] : '';
            if(!$exist){
                $data['flag'] = false;
                $data['code'] = 100001;
                $data['msg'] = '获取商品分类失败,该分类不存在';
                unset($goodscat);
                $this->api_result($data);
            }
        }

        $options = array();

        #类型  1:普通拼团，2:1元购，3:秒杀，4:限时特卖，5:免费试用，6:抽奖, 7:AA团, 8:推广团, 9:自选团, 10:阶梯团
        if($typeid){
            $options['where'] = " and g.typeid = $typeid ";
            if($typeid == 5){
                $status = isset($_GET['status']) ? 'over' : '';
                if($status == 'over'){
                    $options['where'] .= " and g.end_time < ".RUN_TIME;
                }elseif($status == 'going'){
                    $options['where'] .= " and g.end_time > ".RUN_TIME;
                }
            }
        }elseif($_GET['index']==1){
            $options['where'] .= " and g.typeid in (0,1,2,4,8,9,10)";
        }

        #热门，热卖，新品筛选
        if ($is_best)
            $options['where'] .= " and g.is_best = 1";
        if ($is_hot)
            $options['where'] .= " and g.is_hot = 1";
        if ($is_new)
            $options['where'] .= " and g.is_new = 1";

        #海淘
        if ($nid) {
            $this->load->model('nation');
            $options['where'] .= " and g.nation_id" . $this->nation->condArrchild($nid);
        }
        #国家馆
        if ($country_id) {
            $options['where'] .= " and g.country_id = ".  $country_id;
        }
        #品牌筛选
        if ($bid > 0) {
            $this->load->model('brand');
            $options['where'] .= " and g.bid" . $this->brand->condArrchild($bid);
        }

        #搜索
        if ($q)
            $options['q'] = $q;
		
		if (isset($_GET['indexcat']) && $_GET['indexcat']>0) {
        	$options['where'] .= " and g.index_cid = ".$_GET['indexcat'];
        }

        #排序
        $order = isset($_GET['order']) ? trim($_GET['order']) : 'listorder';
        $sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'ASC';
        $orderby = '';
        if ($order == 'price') {
            //价格
            $orderby = "g.team_price {$sort}";
        } elseif ($order == 'new') {
            //时间
            $orderby = "g.c_time {$sort}";
        } elseif ($order == 'sales') {
            //销量
            $orderby = "sell_num {$sort}";
        } elseif ($order == 'praise') {
            //好评 暂无字段
            $orderby = "g.listorder {$sort}";
        }elseif($order == 'lottery'){
            $orderby = " (case when  g.start_time<".RUN_TIME." and g.end_time>".RUN_TIME."  then 3 when  g.start_time>".RUN_TIME." then 2 when g.end_time<".RUN_TIME." then 1 end ) desc,g.listorder asc,g.id desc ";
        }elseif($order=='goods_id'){
            $orderby = " (case when id={$sort} then 1 ELSE 4 END),g.listorder asc,g.id asc";
        }else{
            $orderby = "g.listorder {$sort},g.id DESC";
        }
        if ($orderby) {
            $options['order'] = $orderby;
        }
        $data['list_type'] = C('goods_list')?0:C('goods_list');
        if($typeid==2)$data['list_type']=1;
        $data['list'] = null;
        #商品列表
        $goods = $this->goods->getList($size, $page, $id, $type, $options);
        if($goods){
            $data['list_total'] = (int)$this->page->pages['total'];
            foreach ($goods as $k => $v) {
                $data['list'][$k]['id'] = $v['id'];
                $data['list'][$k]['typeid'] = $v['typeid'];
                $data['list'][$k]['type_name'] = goods_typeid($v['typeid']);
                $data['list'][$k]['name'] = $v['name'];
                $data['list'][$k]['price'] = $v['price'];
                $data['list'][$k]['team_price'] = (string)$v['team_price'];
                $data['list'][$k]['discount_price'] = discount($v['price'], $v['team_price']);
                if($is_best || $typeid == 1){
                    $thumb = json_decode($v['thumbs']);
                }else{
                    $thumb = json_decode($v['thumb']);
                }
                $thumbs = json_decode($v['thumbs']);
                //$data['list'][$k]['thumb'] = RootUrl . $thumb[0]->path;
                $data['list'][$k]['thumb'] = yunurl($thumb[0]->path);
                $data['list'][$k]['img_src'] = yunurl($thumbs[0]->path);
                $data['list'][$k]['img_cover'] = yunurl($thumbs[0]->path,300);
                $data['list'][$k]['team_num'] = $v['team_num'];
                $data['list'][$k]['team_people_num'] = formatNum($v['sell']+$v['sales_num']);
                $data['list'][$k]['sell'] = formatNum($v['sell']+$v['sales_num']);
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
     * 商品详情页
     */
    public function show(){
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;//商品id
        $mid = MID;//会员id

        if(!$id){
            $data['flag'] = false;
            $data['code'] = 100002;
            $data['msg'] = '参数异常，商品id为空';
            $this->api_result($data);
        }
        $goods = $this->goods->get($id);
        if(!$goods){
            $data['flag'] = false;
            $data['code'] = 100001;
            $data['msg'] = '获取商品失败,商品不存在';
        }else{
            #是否收藏
            $this->load->model('fav');
            $goods['is_fav'] = $this->fav->is_fav($goods['id'], $mid);

            #参团人数
            $goods['team_people_num'] = $goods['sell']+$goods['sales_num'];

            #评价总数
            $where = "WHERE state=1 AND good_id=" . $goods['id'];
            $sql = "select COUNT(id) from ###_goods_rate " . $where;
            $goods['count'] = $this->db->getstr($sql);

            #拼团列表   1元购和免费试用 要新会员才能参团
            if(check_team($mid,$goods['typeid'])){
                $this->load->model("team");
                $data['data']['team_list'] = $this->team->getTeamList($id,array("status"=>TEAM_ING,"limit"=>3));
                foreach($data['data']['team_list'] as $k=>$v){
                    $photo = photo($v['mid']);
                    if(strpos($photo,"http")===false){
                        $data['data']['team_list'][$k]['avatar'] = rtrim(RootUrl,"/").$photo;
                    }else{
                        $data['data']['team_list'][$k]['avatar'] = $photo;
                    }
                }
            }else{
                $data['data']['team_list'] = null;
            }
            if($common_id>0){
                $team_price = $this->db->getstr("select team_price from ###_goods_order_common where id=".$common_id,"team_price");
                if($team_price>0)$goods['team_price'] = $team_price;
            }
            #图片处理
            $thumb = json_decode($goods['thumb'],true);
            $share_img = json_decode($goods['share_img'],true);
            $thumbs = json_decode($goods['thumbs'], true);
            if(is_array($thumbs)){
                foreach($thumbs as $k=>$v){
                    if(!empty($v['path'])){
                        $thumbs[$k] = yunurl($v['path']);
                    }
                }
            }

            $goods['content'] = rep_content($goods['content']);
            $data['data']['id'] = (int)$goods['id'];
            $data['data']['typeid'] = (int)$goods['typeid'];
            $data['data']['typename'] = goods_typeid($goods['typeid']);
            $data['data']['name'] = $goods['name'];//商品名称
            $data['data']['content'] = $goods['content'];//商品详情
            $data['data']['thumb'] = yunurl($thumb[0]['path'],'300');//商品封面图
            $data['data']['share_img'] = !empty($share_img[0]['path'])?yunurl($share_img[0]['path']):yunurl($thumb[0]['path']);//商品分享图
            $data['data']['thumbs'] = $thumbs;//商品轮播图集
            $data['data']['price'] = $goods['price'];//现价
            $data['data']['team_price_total'] = formatPrice($goods['team_price_total']);
            $data['data']['team_price'] = formatPrice($goods['team_price']);//团购价
            $data['data']['deposit'] = formatPrice($goods['deposit']);//团购价
            $data['data']['team_save'] = formatPrice($goods['price']-$goods['team_price']);//拼团节省
            $data['data']['limit_buy_bumber'] = $goods['limit_buy_bumber'];//限购数量
            $data['data']['is_fav'] = $goods['is_fav'];//是否收藏
            $data['data']['team_num'] = $goods['team_num'];//参团人数
            $data['data']['is_wkill'] = $this->goods->is_wkill($goods['id']);
            $data['data']['stock'] = $goods['stock'];

            $data['data']['count'] = $goods['count'];//评价总数
            $data['data']['start_time'] = $goods['start_time'];
            $data['data']['end_time'] = $goods['end_time'];
            $data['data']['run_time'] = RUN_TIME;
            if($goods['start_time']>RUN_TIME  && in_array($goods['typeid'],array(CART_FREE,CART_LUCK,CART_KILL))){
                $data['data']['team_people_num'] = 0;//已参团总人数
            }else{
                $data['data']['team_people_num'] = formatNum($goods['sell']+$goods['sales_num']);//已参团总人数
            }
            $data['data']['share_comss'] = formatPrice($goods['share_comss']);
            $data['data']['desc'] = $goods['desc'];
            $data['data']['share_content'] = !empty($goods['description'])?$goods['description']:C('seo_description');//分享描述
            //商品标签 包邮 7天退换 48小时发货
            $data['data']['goods_tip'] = null;
            if($goods['goods_tip']){
                //商品标签 包邮 7天退换 48小时发货
                $this->load->model("goods_tag");
                $tags = $this->goods_tag->getList(array("where"=>" AND id in({$goods['goods_tip']})"));
                if($tags)$data['data']['goods_tip'] = array_column($tags,"name");
            }
            $rule = null;
            if(in_array($goods['typeid'],array(CART_AA,CART_FREE,CART_LUCK,CART_SHARE,CART_STEP))){
                $coupon = '';
                if($goods['coupon_id']>0){
                    $coupon = $this->db->getstr("select title from ###_coupon where id=".$goods['coupon_id'],"title");
                }
                if($goods['typeid']==CART_LUCK) $rule = C('lottery_rule');
                if($goods['typeid']==CART_FREE) $rule = C('free_rule');
                if($goods['typeid']==CART_AA) $rule = C('aa_rule');
                if($goods['typeid']==CART_SHARE) $rule = C('share_rule');
                if($goods['typeid']==CART_STEP) $rule = C('step_rule');
                $patterns = array("/【开始时间】/","/【结束时间】/","/【中奖人数】/","/【标题】/","/【优惠券】/");
                $replacements = array(date("m月d日",$goods['start_time']),date("m月d日",$goods['end_time']),$goods['luck_num'],$goods['name'],$coupon);
                $data['data']['rule'] = strip_tags(preg_replace ($patterns ,  $replacements ,  $rule));
            }else{
                $data['data']['rule'] = $rule;
            }
            //判断活动状态
            $data['data']['status_name'] = null;
            if($goods['end_time']<RUN_TIME  &&  in_array($goods['typeid'],array(CART_FREE,CART_LUCK,CART_KILL))){
                $data['data']['status_name'] = "活动结束";
            }

            #商品规格
            $data['data']['goods_item'] = null;
            if ($goods['sp_val']) {
                $sp_val = $sp_arr= json_decode($goods['sp_val'], true);
                $sp_ids = join(',', array_keys($sp_arr));
                $sp_arr = $this->db->select("select id,name from ###_goods_spec where id in ($sp_ids)");
                $goods['spec'] = array();
                $spec = trim($_GET['spec']);
                if ($spec)$goods['spec'] = explode("-", $spec);
                if(!empty($sp_arr)){
                    foreach ($sp_arr as $key=>$val){
                        $sp_arr[$key]['spec_arr'] = $sp_val[$val['id']];
                    }
                }
                $data['data']['sp_arr'] = $sp_arr;
                $data['data']['sp_val'] = $goods['sp_val'];
                $data['data']['spec'] = $goods['spec'];//记录选择的商品规格
                //多规格商品
                $data['data']['goods_item'] = $this->db->select("select * from ###_goods_item where goods_id={$id}");
                foreach($data['data']['goods_item'] as $k=>$v){
                    $v['spec_array'] = $this->getSpec($goods['sp_val'],$v['spec']);
                    if(!empty($v['thumb'])){
                        $thumb = json_decode($v['thumb']);
                        $v['thumb']  = yunurl($thumb[0]->path);
                    }
                    if($goods['typeid']==CART_AA) $v['team_price'] = round($v['team_price']/$goods['team_num'],2);
                    //$v['thumb'] = !empty($v['thumb'])?$v['thumb']:null;
                    //$v['team_price'] = $data['data']['team_price'];
                    $data['data']['goods_item'][$k] = $v;
                }
            }
            $data['data']['step_array'] = null;
            $step_array = is_array($goods['step_array'])?array_map(formatPrice,$goods['step_array']):null;
            if($step_array){
                foreach($step_array as $k=>$v){
                    $data['data']['step_array'][] = array("step_key"=>$k,"step_value"=>$v);
                }
            }


            #商家信息
            $data['data']['store'] = $data['data']['tj_list'] = null;
            if($goods['sid'] > 0){
                $this->load->model("business");
                $store = $this->business->get($goods['sid'],"id,name,logo,fav_num,kf_online");
                $store['logo'] =  empty($store['logo'])?null:yunurl($store['logo']);
                $data['data']['store'] = $store;
                #统计商品数量
                $data['data']['store']['goods_total'] = $this->db->getstr("select count(1) as num from ###_goods where is_sale=1 and sid=".$goods['sid'],"num");
                #统计销量
                $data['data']['store']['sell_total'] = $this->db->getstr("select sum(sell)+sum(sales_num) as num from ###_goods where is_sale=1 and sid=".$goods['sid'],"num");
                #判断是否已关注
                if($this->db->get("select 1 from ###_business_fav where sid={$goods['sid']} and mid=".MID)){
                    $data['data']['store']['is_fav'] = true;
                }else{
                    $data['data']['store']['is_fav'] = false;
                }

                #商家推荐
                $tj_list = $this->db->select("select * from ###_goods where id!={$data['data']['id']} and sid={$goods['sid']} and is_sale=1 and status=0 and stock>0 and is_new=1 order by listorder desc,id desc limit 10");
                foreach($tj_list as $key=>$val){
                    $val = $this->goods->getThumb($val);
                    $tmp['id'] = $val['id'];
                    $tmp['name'] = $val['name'];
                    $tmp['typeid'] = $val['typeid'];
                    $tmp['type_name'] = goods_typeid($val['typeid']);
                    $tmp['price'] = $val['price'];
                    $tmp['team_price'] = $val['team_price'];
                    $tmp['img_cover'] =  $val['img_cover'];
                    $data['data']['tj_list'][] = $tmp;
                }
                $data['data']['kf_online'] = $store['kf_online'];
            }else{
                $data['data']['kf_online'] = C('kf_online');
            }
            $data['data']['kf_online'] = empty($data['data']['kf_online'])?null:$data['data']['kf_online'];
            $online_link = url('/chat?good_id=').$goods['id'];
            if(!empty($goods['sid'])) $online_link.='&bid='.$goods['sid'];
            $data['data']['online_link'] = empty($data['data']['kf_online']) ? $online_link : $data['data']['kf_online'];
            $data['data']['online_link'] = urlencode($data['data']['online_link']);

            $data['join_common_id'] = $data['common_id'] = 0;
            $data['team'] = null;
            //判断阶梯团商品是否存在开团中的拼团
            if($goods['typeid']==CART_STEP){
                $res_step = $this->db->get("select * from ###_goods_order_common where goods_id={$goods['id']} and status=".TEAM_ING." and e_time>=".RUN_TIME);
                $data['team'] = $res_step;
                if($data['team']){
                    arsort($goods['step']['team_num']);
                    $data['team']['team_max'] = reset($goods['step']['team_num']);
                    $team_scale = round($data['team']['team_num_yes']/$data['team']['team_max']*100);
                    $data['team']['team_scale'] = $team_scale>100?100:$team_scale;
                    $data['common_id'] = (int)$data['team']['id'];
                    $data['data']['team_price'] = $data['team']['team_price'];
                    if(defined("MID")) {
                        $is_res = $this->db->getstr("select common_id from ###_goods_order where common_id={$data['common_id']} and (status_common=1 or status_common=10) and mid=" . MID, "common_id");
                        if ($is_res) $data['join_common_id'] = (int)$is_res;
                    }
                }

            }
            //判断商品是否限购以及是否购买过
            if($data['row']['limit_buy_one']==1 && defined("MID")){
                $is_res = $this->db->getstr("select common_id from ###_goods_order where extension_id={$data['row']['id']} and  (status_common=1 or status_common=10) and mid=".MID,"common_id");
                if($is_res)$data['join_common_id'] = (int)$is_res;
            }
            $share_url = RootUrl;
            if(C('wx_url')){
                $share_url = REQUEST_SCHEME.C('wx_url').'/';
            }
            $data['data']['url'] = $share_url."goods/show/$id";
        }
        unset($goods);
        $this->api_result($data);
    }

    /**
     * 格式化商品规格
     * @param $sp_val
     * @param string $spec
     * @return array|null
     */
    function getSpec($sp_val,$spec = '') {
        if ($spec == ''|| $sp_val == '')return null;
        $sp_val = json_decode($sp_val, true);
        if(!S("CH_SPEC")){
            $res = $this->db->select("select * from ###_goods_spec");
            S("CH_SPEC",$res);
        }else{
            $res = S("CH_SPEC");
        }
        $sp_list = array_combine(array_column($res, "id"), array_column($res, "name"));
        $spec_array = array();
        foreach($sp_val as $key=>$val){
            foreach($val as $k=>$v){
                $spec_array[$k]['spec_key'] = $sp_list[$key];
                $spec_array[$k]['spec_value'] = $v;
            }
        }
        $spec_arr = explode("-", $spec);
        $result = array();
        foreach($spec_arr as $v){
            $result[] = $spec_array[$v];
        }
        return $result;
    }

    /**
     * 免费试用、抽奖活动结束详情（中奖）
     */
    public function luck(){
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;//页码
        $size = isset($_GET['size']) ? intval($_GET['size']) : 10;//每页显示数量

        if(!$id)
            $this->api_result(array('flag'=>false,'msg'=>'参数异常，缺少id','code'=>100002));

        $_GET['page'] = $page;
        $this->page->set_vars(array('per' => $size));
        $sql  = "SELECT a.*,m.mobile,m.username FROM ###_lottery_log as a left join ###_member as m on a.mid=m.mid WHERE a.act_id='" . $id . "' ORDER BY a.id DESC";
        $data['list'] = $this->page->hashQuery($sql)->result_array();
        $data['list_total'] = (int)$this->page->pages['total'];
        foreach($data['list'] as $k=>$v){
            $data['list'][$k]['mobile'] = cut_format($v['mobile'], 3, 4);
            $data['list'][$k]['order_sn'] = cut_format($v['order_sn'], 3, 4);
            $data['list'][$k]['avatar'] = yunurl(photo($v['mid']));
        }
        $goods = $this->goods->get($id);
        $data['goods']['id'] = (int)$goods['id'];
        $data['goods']['img_cover'] = yunurl($goods['img_cover']);
        $data['goods']['name'] = $goods['name'];
        $data['goods']['team_price'] = formatPrice($goods['team_price']);
        $data['goods']['price'] = formatPrice($goods['price']);

        $this->api_result(array('data'=>$data));
    }

    /**
     * 客户评价
     */
    public function rate(){
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;//页码
        $size = isset($_REQUEST['size']) ? intval($_REQUEST['size']) : 10;//每页显示数量
        $star = isset($_REQUEST['star']) ? intval($_GET['star']) : 0;

        if(!$id){
            $data['flag'] = false;
            $data['code'] = 100002;
            $data['msg'] = '参数异常，商品id不能为空';
            $this->api_result($data);
        }

        $where = "WHERE state=1 AND good_id=" . $id;

        $_GET['page'] = $page;
        $this->page->set_vars(array('per' => $size));
        if ($star > 0) {
            $where .= " and star={$star}";
        }
        $sql = "SELECT * FROM ###_goods_rate " . $where . " ORDER BY listorder DESC,id DESC";
        $list = $this->page->hashQuery($sql)->result_array();
        $list = $this->db->lJoin($list, 'member_detail', 'mid,nickname', 'mid', 'mid');
        $data = array();
        if($list) {
            $data['list_total'] = (int)$this->page->pages['total'];
            foreach ($list as $k => $v) {
                $data['list'][$k]['mid'] = $v['mid'];
                $data['list'][$k]['avatar'] = yunurl(photo($v['mid']));
                $data['list'][$k]['nickname'] = $v['nickname'];
                $data['list'][$k]['c_time'] = date('Y-m-d H:m:s', $v['c_time']);
                $data['list'][$k]['star'] = $v['star'];
                $data['list'][$k]['content'] = $v['content'];
            }
        }
        unset($list);
        $this->api_result(array('data'=>$data));
    }

    //ajax获取大家都在团 商品
    function ajax_rand() {
        $size = !empty($_REQUEST['size']) ? intval($_REQUEST['size']) : 8;
        $options['where'] = " and g.typeid =1 ";
        $data = $this->goods->getList($size, 1, 0, 0, $options);
        $this->api_result(array('data'=>$data));
    }

    /**
     * 团购详情
     */
    public function team(){
        $common_id = isset($_GET['common_id']) ? intval($_GET['common_id']) : 0;
        $mid = MID;//会员id
        if(!$common_id){
            $data['flag'] = false;
            $data['code'] = 100002;
            $data['msg'] = '参数异常，common_id不能为空';
            $this->api_result($data);
        }
        $this->load->model("team");
        $arr = $this->team->get($common_id);
        $tmp_price = !empty($arr['row']['team_price'])?$arr['row']['team_price']:$arr['goods']['team_price'];
        #分享链接
        $data['share_links'] = RootUrl.'goods/team/'.$common_id;

        $data['id'] = $common_id;//团购id
        $data['tz_mid'] = $arr['row']['mid'];//团长id
        $data['goods_id'] = (int)$arr['goods']['id'];//商品id
        $data['goods_typeid'] = $arr['goods']['typeid'];//商品类型
        $data['goods_typename'] = goods_typeid($arr['goods']['typeid']);//商品类型
        $data['goods_img_cover'] = $arr['goods']['img_cover'];
        $data['goods_name'] = $arr['goods']['name'];//商品名称
        $data['share_content'] = !empty($arr['goods']['description'])?$arr['goods']['description']:C('seo_description');//分享描述
        $data['goods_team_price'] = formatPrice($tmp_price);//团购价
        $data['goods_price'] = $arr['goods']['price'];//原价
        $data['stock'] = $arr['goods']['stock'];//库存
        $data['team_save'] = $arr['goods']['price'] - $arr['goods']['team_price'];//团购节省
        $data['team_num_no'] = $arr['row']['team_num']-$arr['row']['team_num_yes'];//团购剩余人数
        $data['team_num_no'] = $data['team_num_no']<0?0:$data['team_num_no'];
        $data['team_num_yes'] = $arr['row']['team_num_yes'];
        if($data['team_num_no'] == 1)
            $data['team_num_no_msg'] = "参团支付成功后，立马组团成功，为您节省了$data[team_save]元";//团购提示信息
        else
            $data['team_num_no_msg'] = "参团支付成功后，如果人数不足，系统会自动退款";
        $data['team_status'] = $arr['row']['status'];//团购状态

        #团剩余时间
        if($data['team_status'] == TEAM_FIELD || $data['team_status'] == TEAM_SUCC || $arr['row']['end_time'] == 0)
            $data['end_time'] = 0;//拼团已结束
        else
            $data['end_time'] = $arr['row']['end_time'];

        #会员信息
        foreach($arr['list'] as $k=>$v){
            $arr['list'][$k]['c_time'] = date('Y-m-d H:m:s',$v['c_time']);

            $photo = photo($v['mid']);
            if(strpos($photo,"http")===false){
                $arr['list'][$k]['avatar'] = rtrim(RootUrl,"/").$photo;
            }else{
                $arr['list'][$k]['avatar'] = $photo;
            }
        }
        $data['member'] = $arr['list'];//参团会员信息
        //判断购买次数限制
        $data['limit_one'] = false;
        if($data['goods']['limit_buy_one']==1){
            $is_res = $this->db->get("select 1 from ###_goods_order where extension_id={$data['goods_id']} and status_common>0 and mid=".MID);
            $data['limit_one'] = $is_res==false?false:true;
        }
        //判断是否参团
        $is_join = $this->db->get("select 1 from ###_goods_order where common_id={$common_id} and status_common>0 and mid=".MID);
        $data['is_join'] = $is_join==false?false:true;

        if($arr['row']['goods_typeid'] == CART_AA){
            $data['AA_team_price'] = "AA团每人".$arr['goods']['team_price']."元";
            $data['AA_top_msg'] = '此团为AA团，只限与团长是好友、同事、同学、或是离的非常近的小伙伴们进行购买，因为货只发给团长，由团长分配给团成员,可通过右边的拼团玩法了解详细';
        }else{
            $data['AA_team_price'] = '';
            $data['AA_top_msg'] = '';
        }
        #阶梯团

        $data['team_max'] = $data['team_scale'] = $data['step_array'] = null;
        if($arr['row']['goods_typeid']==CART_STEP){
            arsort($arr['goods']['step']['team_num']);
            $data['team_max'] = reset($arr['goods']['step']['team_num']);
            $team_scale = round($arr['row']['team_num_yes']/$data['team_max']*100);
            $data['team_scale'] = $team_scale>100?100:$team_scale;
            $step_array = is_array($arr['goods']['step_array'])?array_map(formatPrice,$arr['goods']['step_array']):null;
            if($step_array){
                foreach($step_array as $k=>$v){
                    $data['step_array'][] = array("step_key"=>$k,"step_value"=>$v);
                }
            }
        }

        $data['team_list'] = $this->team->getTeamList($data['goods_id'],array("status"=>TEAM_ING,"limit"=>3));
        foreach($data['team_list'] as $k=>$v){
            $photo = photo($v['mid']);
            if(strpos($photo,"http")===false){
                $data['team_list'][$k]['avatar'] = rtrim(RootUrl,"/").$photo;
            }else{
                $data['team_list'][$k]['avatar'] = $photo;
            }
        }

        #商品规格
        $data['goods_item'] = $data['sp_arr'] = null;
        if ($arr['goods']['sp_val']) {
            $sp_val = $sp_arr= json_decode($arr['goods']['sp_val'], true);
            $sp_ids = join(',', array_keys($sp_arr));
            $sp_arr = $this->db->select("select id,name from ###_goods_spec where id in ($sp_ids)");
            $goods['spec'] = array();
            $spec = trim($_GET['spec']);
            if ($spec)$goods['spec'] = explode("-", $spec);
            if(!empty($sp_arr)){
                foreach ($sp_arr as $key=>$val){
                    $sp_arr[$key]['spec_arr'] = $sp_val[$val['id']];
                }
            }
            $data['sp_arr'] = $sp_arr;
            //多规格商品
            $goods_item = $this->db->select("select * from ###_goods_item where goods_id={$arr['goods']['id']}");
            foreach($goods_item as $k=>$v){
                $v['spec_array'] = $this->getSpec($arr['goods']['sp_val'],$v['spec']);
                if(!empty($v['thumb'])){
                    $thumb = json_decode($v['thumb']);
                    $v['thumb']  = yunurl($thumb[0]->path);
                }
                if($data['goods_typeid']==CART_AA) $v['team_price'] = round($v['team_price']/($data['team_num_no']+$data['team_num_yes']),2);
                $v['team_price'] = empty($v['team_price'])?$data['data']['team_price']:$v['team_price'];
                $data['goods_item'][$k] = $v;
            }
        }

        #顶部提示信息
        $data['top_msg'] = '';
        if($data['team_num_no'] == 0 || $data['team_status'] == TEAM_SUCC){
            $data['top_msg'] = '<div class="bg-danger"><h3>(T_T) 此团已满</h3><p>掐指一算，您就是下一位团长(☆＿☆)</p></div>';
        }elseif($data['team_status'] == TEAM_FIELD){
            $data['top_msg'] = '<div class="bg-danger"><h3>(T_T) 拼团购买失败</h3></div>';
        }elseif($data['is_join']){
            $data['top_msg'] = '<div class="bg-success"><h3>( *^_^* )此团正在拼团中</h3><p>想要快速成团邀请小伙伴一起参与吧！</p></div>';
        }elseif($data['limit_one']){
            $data['top_msg'] = '<div class="bg-danger"><h3>(T_T) 您已达限购次数，不能参团</h3></div>';
        }elseif(check_team($mid,$arr['goods']['typeid'])){//拼团列表   1元购和免费试用 要新会员才能参团
            $data['top_msg'] = '<div class="bg-success">终于等到你了，快来参团  (*^_^*)</div>';
        }
        $data['check_team'] = check_team($mid,$arr['goods']['typeid']);
        unset($arr);
        $this->api_result(array('data'=>$data));
    }

    /**
     * 限时秒杀
     */
    public function kill(){
        $time = !empty($_GET['time']) ? trim($_GET['time']) : 'now';
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;//页码
        $size = isset($_GET['size']) ? intval($_GET['size']) : 10;//每页显示数量

        if($time=='now'){
            $id = $this->db->getstr("select id from ###_topic where typeid=5 and start_time<=".RUN_TIME." and end_time>=".RUN_TIME,"id");
        }else{
            $id = $this->db->getstr("select id from ###_topic where typeid=5 and start_time>".RUN_TIME,"id");
        }
        if(!$id>0)$id=0;
        $where = " t.status=1 AND t.act_id={$id} ";
        $order = isset($_REQUEST['order']) ? trim($_REQUEST['order']) : '';
        $sort = isset($_REQUEST['sort']) ? trim($_REQUEST['sort']) : '';
        if($order=='goods_id'){
            $order = " ORDER BY (case when t.goods_id={$sort} then 1 ELSE 4 END),t.listorder asc,sell_num desc";
        }else{
            $order = " ORDER BY t.listorder asc,sell_num desc";
        }

        $this->load->model('page');
        $this->page->set_vars(array('per' => 10));
        $_GET['page'] = intval($page);
        $sql = "SELECT t.*,g.id,g.name,g.desc,g.thumb,g.thumbs,g.price,g.team_price,g.team_num,g.sell,g.sales_num,g.sell+g.sales_num as sell_num,g.stock,g.start_time,g.end_time,g.typeid FROM ###_topic_goods as t LEFT JOIN ###_goods as g on t.goods_id=g.id WHERE {$where} {$order}";
        $list = $this->page->hashQuery($sql)->result_array();
        $this->load->model("goods");
        if($list){
            $data['list_total'] = (int)$this->page->pages['total'];
            foreach($list as $k=>$v){
                $data['list'][$k]['id'] = $v['id'];//商品id
                $data['list'][$k]['name'] = $v['name'];//商品名称
                $data['list'][$k]['desc'] = $v['desc'];//商品名称
                $thumb = json_decode($v['thumb']);
                $data['list'][$k]['thumb'] = yunurl($thumb[0]->path,300);
                $data['list'][$k]['discount'] = discount($v['price'],$v['team_price']);
                $data['list'][$k]['team_num'] = (int)$v['team_num'];//x人团
                $data['list'][$k]['run_time'] = RUN_TIME;
                $data['list'][$k]['start_time'] = $v['start_time'];
                $data['list'][$k]['end_time'] = $v['end_time'];
                $data['list'][$k]['team_price'] = formatPrice($v['team_price']);//团购价
                $data['list'][$k]['price'] = $v['price'];//原价
                $data['list'][$k]['sell'] = formatNum($v['sell']+$v['sales_num']);//销售量
                $data['list'][$k]['stock'] = (int)$v['stock'];//库存
                if($v['start_time']>RUN_TIME  && in_array($v['typeid'],array(CART_FREE,CART_LUCK,CART_KILL))){
                    $data['list'][$k]['scale'] =  0;
                    $data['list'][$k]['sell'] = '0';
                }else{
                    $data['list'][$k]['scale'] = round(($v['sell']+$v['sales_num'])/($v['stock']+$v['sell']+$v['sales_num']),2)*100;
                }
            }
        }
        $data['etime'] = kill_etime();
        $this->api_result(array('data'=>$data));
    }

    /**
     * 超值大牌
     */
    public function brand(){
        $bid = isset($_GET['bid']) ? intval($_GET['bid']) : 0;//品牌分类id
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;//页码
        $this->load->model('page');
        $_GET['page'] = $page;
        $this->page->set_vars(array('per' => 10));

        if($bid>0){
            $parent_str = $bid;
        }else{
            $sql = "select id from ###_brand where ismenu=1 and parentid=0 order by listorder desc,id desc";
            $res = $this->db->select($sql);
            $parent_str = $res?join(',',array_column($res,"id")):"1=2";
        }

        $sql = "select id,catname,thumb,sale,end_time from ###_brand where ismenu=1 and parentid in ($parent_str) and (end_time=0 or end_time>0 and end_time>=".RUN_TIME." ) order by listorder desc,id desc";
        $data['list'] =  $this->page->hashQuery($sql)->result_array();
        $data['list_total'] = (int)$this->page->pages['total'];
        $this->load->model("brand");
        if($data['list']){
            foreach($data['list'] as $k=>$v){
                $thumb = json_decode($v['thumb'],true);
                $data['list'][$k]['thumb'] = yunurl($thumb[0]['path']);

                $data['list'][$k]['end_day'] = $v['end_time']>=RUN_TIME?(ceil(($v['end_time']-RUN_TIME)/(24*3600))):0;
                $options = $goods = array();
                $options['where'] .= " and g.bid" . $this->brand->condArrchild($v['id']);
                $options['order'] = " g.listorder asc,sell_num desc";
                $goods = $this->goods->getList(10,0,0,0,$options);
                $data['list'][$k]['goods_list'] = null;
                foreach($goods as $_k=>$_v){
                    $temp = array();
                    $temp['id'] = $_v['id'];
                    $temp['name'] = $_v['name'];
                    $temp['price'] = $_v['price'];
                    $temp['team_price'] = $_v['team_price'];
                    $temp['team_num'] = $_v['team_num'];
                    $temp['img_src'] = $_v['img_src'];
                    $temp['img_cover'] = $_v['img_cover'];
                    $data['list'][$k]['goods_list'][] = $temp;
                }
            }
        }

        #加载商品分类

        $cats = $this->brand->loadCats();
        $data['cat_list'] = genTop($cats);


        $this->api_result(array('data'=>$data));
    }

    /**
     * 超值大牌商品列表
     */
    function brand_goods() {
        $top = isset($_GET['top']) ? intval($_GET['top']) : 0;
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if($id==0)$id=$top;
        if($id==0){
            $this->api_result(array('flag' => false, 'msg' => '品牌ID不能为空', 'code' => 100002));
        }
        $this->load->model("brand");
        $data['cats'] = $this->brand->loadCats();
        $data['top'] = $data['cats'][$top];
        $data['row'] = $data['cats'][$id];
        if (empty($data['row'])) {
            $this->api_result(array('flag' => false, 'msg' => '该品牌不存在', 'code' => 100003));
        }
        $data['row']['end_time'] = $data['row']['end_time']>RUN_TIME?($data['row']['end_time']-RUN_TIME):0;
        if($data['row']['parentid']>0){
            $temp = $this->brand->getTop($id);
            $data['row']['thumb'] = $temp['thumb'];
        }
        if($data['top']['thumb']){
            $thumb = json_decode($data['top']['thumb'],true);
            $data['row']['thumb'] = yunurl($thumb[0]['path']);
        }
        if($data['top']['child']==1){
            $sql = "select * from ###_brand where ismenu=1 and parentid={$top} and (end_time=0 or end_time>0 and end_time>=".RUN_TIME." ) order by listorder desc,id desc";
            $data['brand_list'] = $this->db->select($sql);
        }
        //$data['brand_list'] = $this->db->select($sql);
        $temp = array();
        $temp['id'] = $data['row']['id'];
        $temp['top'] = $top;
        $temp['catname'] = $data['row']['catname'];
        $temp['thumb'] = $data['row']['thumb'];
        unset($data['cats']);
        unset($data['row']);
        unset($data['top']);
        $data['row'] = $temp;
        $this->api_result(array('data'=>$data));
    }

    /**
     * 就等你来（参与团购）
     */
    public function common(){
        $this->load->model("team");
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;//页码
        $option['page'] = $page;
        $option['status'] = 1;
        $option['where'] = " and goods_typeid<5 ";
        $list = $this->team->getList($option);
        $data = array();
        if($list) {
            $data['list_total'] = (int)$this->page->pages['total'];
            foreach ($list as $k => $v) {
                $data['list'][$k]['id'] = $v['id'];
                $data['list'][$k]['goods_id'] = $v['goods_id'];
                $data['list'][$k]['goods_name'] = $v['goods_name'];
                $data['list'][$k]['goods_price'] = $v['goods_price'];
                $data['list'][$k]['goods_team_price'] = formatPrice($v['goods_team_price']);
                $data['list'][$k]['discount_price'] = discount($v['goods_price'], $v['goods_team_price']);
                $data['list'][$k]['goods_sell'] = (int)$v['goods_sell'];
                $data['list'][$k]['goods_team_num'] = (int)$v['goods_team_num'];
                $data['list'][$k]['team_num_yes'] = (int)$v['team_num_yes'];
                $thumb = json_decode($v['goods_thumb']);
                $data['list'][$k]['goods_thumb'] = yunurl($thumb[0]->path);
                $data['list'][$k]['end_time'] = $v['e_time'] - RUN_TIME;
            }
        }
        unset($list);
        $this->api_result(array('data'=>$data));
    }

    /**
     * 组团订单通知
     */
    public function order_notice(){
        $cache_name = "CH_ORDER_NOTICE";
        $temp = array();
        if(!S($cache_name)){
            $end_time = RUN_TIME-24*3600;
            $res = $this->db->select("select common_id,mid,c_time from ###_goods_order where status_common=".TEAM_ING." and extension_code !=5  and extension_code !=6 and c_time>".$end_time." limit 100");
            $res = $this->db->lJoin($res, "member", "mid,mobile,username", "mid", "mid");
            $res = $this->db->lJoin($res, "member_detail", "mid,photo,nickname", "mid", "mid");
            foreach ($res as $k => $v) {
                $temp[$k]['id'] = (int)$v['common_id'];
                $temp[$k]['avatar'] = empty($v['photo']) ? RootUrl .'/common/photo.gif' : yunurl($v['photo']);
                $name = !empty($v['nickname'])?$v['nickname']:$v['username'];
                $temp[$k]['message'] = formatTime($v['c_time'])."," . cut_str($name,24) . "成功参团，等你来拼！";
            }
            S($cache_name,$temp,180);
        }else{
            $temp = S($cache_name);
        }
        $max = round(count($temp));
        $key = rand(0,$max);
        $this->api_result(array('data'=>$temp[$key]));
    }

    /**
     * 系统当前版本
     */
    public function version(){
        $version = $this->db->get("select title,`name`,content,upload from ###_apk_version where status = 1 order by createtime DESC,id DESC");
        if(!$version){
            $this->api_result(array('flag' => false, 'msg' => '未添加任何版本记录', 'code' => 100001));
        }
        $data['version'] = $version['title'];
        $data['name'] = $version['name'];

        $data['content'] =  strip_tags($version['content']);
        $upload = json_decode($version['upload']);
        $data['down_url'] = RootUrl.$upload[0]->path;
        $this->api_result(array('data'=>$data));
    }

    /**
     * 图片
     * @param $typeid
     */
    public function ad($typeid){
        $ad = getAd($typeid);
        if(!empty($ad)){
            $ad = json_decode($ad);
			$ad = yunurl($ad[0]->path);
        }else{
            $ad = null;
        }
        $this->api_result(array('data'=>$ad));
    }

    function square(){

        $_GET['page'] = isset($_GET['page']) ? intval($_GET['page']) : 1;//页码
        $size = isset($_GET['size']) ? intval($_GET['size']) : 10;//每页显示数量

        $this->load->model('page');
        $this->page->set_vars(array('per' => $size));
        $select  = "go.mid,go.common_id,go.extension_code,go.extension_id,go.square_desc,go.square_time,";//###_goods_order
        $select .= "goi.goods_name,";//###_goods_order_item
        $select .= "goc.team_num,goc.team_num_yes,";//###_goods_order_common
        $select .= "g.thumb,g.thumbs,g.price,g.team_price,";// ###_goods
        $select .= "m.username";//###_member
        $sql  = "select {$select} from ###_goods_order as go";
        $sql .= " LEFT JOIN ###_goods_order_item    AS goi  ON go.id        = goi.order_id";
        $sql .= " LEFT JOIN ###_goods_order_common  AS goc  ON go.common_id = goc.id";
        $sql .= " LEFT JOIN ###_goods               AS g    ON goi.good_id  = g.id";
        $sql .= " LEFT JOIN ###_member              AS m    ON go.mid       = m.mid";
        $where = " WHERE go.status_common = 1 AND go.common_id > 0 AND go.square_time>0 AND goc.status=1 ";
        if($_GET['goods_name']) {
            $where .= " AND goi.goods_name LIKE '%{$_GET['goods_name']}%'";
        }
        $data['list'] = $this->page->hashQuery($sql.$where.'ORDER BY go.square_time DESC')->result_array();
        foreach($data['list'] as $k=>$v){
            $res = $this->goods->getThumb($v, 1);
            $thumb = json_decode($v['thumb'], true);
            $thumbs = json_decode($v['thumbs'], true);
            unset($data['list'][$k]['thumb'], $data['list'][$k]['thumbs']);
            foreach($thumb as $m){
                $data['list'][$k]['thumb'][] = yunurl($m['path']);
            }
            foreach($thumbs as $m){
                $data['list'][$k]['thumbs'][] = yunurl($m['path']);
            }
            $data['list'][$k]['img_cover'] = $res['img_cover'];
            $photo = ltrim(photo_path($v['mid']),'/');
            $data['list'][$k]['photo'] = strpos($photo,"://")!==false?$photo:RootUrl.$photo;
            $data['list'][$k]['goods_typename'] = goods_typeid($v['extension_code']);
            $data['list'][$k]['square_time'] = date('Y-m-d H:m:s', $v['square_time']);
        }
        $data['list_total'] = (int)$this->page->pages['total'];
        $this->api_result(array('data'=>$data));
    }

    function getcat(){
        $data['cats'] = $this->goodscat->loadCats();
        /*foreach($data['cats'] as $k=>$v){
            if($v['ismenu']==0)unset($data['cats'][$k]);
        }*/
        $cats_array = $this->genTree9($data['cats']);

        /*$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $cats_array = genTop($data['cats']);
        if($data['row']['parentid']==0 && (!isset($_GET['top']) || $_GET['top']==0)){
            $data['cat_list'] = $cats_array;
            $data['cat_isrec'] = $this->db->select("SELECT id,catname,thumb FROM ###_nation WHERE isrec=1 and ismenu=1 and FIND_IN_SET('{$id}',arrparentid) ORDER BY listorder DESC,id DESC");
            $data['cat_isrec'] = api_imgurl($data['cat_isrec'],array("thumb"));
        }else{
            if(isset($_GET['top']) && $_GET['top']>0){//下级栏目
                $data['cat_list'] = $this->nation->loadCats($id);
            }else{//同级栏目
                $data['cat_list'] = $this->nation->loadCats($data['row']['parentid']);
            }
            if(isset($_GET['top']) && $_GET['top']>0 ){
                $data['top'] = $_GET['top'];
            }else{
                $data['top'] = $data['row']['parentid'];
            }
            array_unshift($data['cat_list'],array("id"=>$data['top'],"catname"=>"全部"));
            $data['cat_list'] = formatCat($data['cat_list']);
            $data['cat_isrec'] = null;
        }*/
        $this->api_result(array('data'=>$cats_array));
    }

    //获取商品分类接口
    function getGoodsCat(){
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        $data['cats'] = $this->goodscat->loadCats();

        if ($id) {
            $data['row'] = isset($data['cats'][$id]) ? $data['cats'][$id] : '';
            if (empty($data['row'])) {
                exit($this->msg());
            }
        }
        $cats_array = $this->genTree9($data['cats']);
        $data['top'] = null;
        if(empty($id)){
            $data['cat_list'] = genTop(array_values($cats_array));
            $data['cat_isrec'] = null;
        }elseif( $data['row']['parentid']==0 && !isset($_GET['top'])){
            $data['cat_list'] = $cats_array;
            $data['cat_isrec'] = $this->db->select("SELECT id,catname,thumb FROM ###_goods_cat WHERE isrec=1 and ismenu=1 and FIND_IN_SET('{$id}',arrparentid) ORDER BY listorder ASC,id ASC LIMIT 9");
            $data['cat_isrec'] = api_imgurl($data['cat_isrec'],array("thumb"));
        }else{
            if(isset($_GET['top'])){//下级栏目
                $data['cat_list'] = $this->goodscat->loadCats($id);
            }else{//同级栏目
                $data['cat_list'] = $this->goodscat->loadCats($data['row']['parentid']);
            }
            $data['top'] = isset($_GET['top'])?$_GET['top']:$data['row']['parentid'];
        }
        if($data['top']){
            array_unshift($data['cat_list'],array("id"=>$data['top'],"catname"=>"全部"));
        }
        $data['top_parent'] = $this->goodscat->getTop($id);
        $data['cat_list'] = count($data['cat_list'])>0?formatCat($data['cat_list']):null;
        unset($data['cats']);
        unset($data['row']);
        $this->api_result(array('data'=>$data));
    }

    function genTree9($list) {
        $tree = array();
        foreach ($list as $k => $v) {
            if($v['ismenu']==0)continue;
            if($v['child']==0)$list[$k]['sub'] = null;
            unset($list[$k]['title']);
            unset($list[$k]['keywords']);
            unset($list[$k]['description']);
            unset($list[$k]['arrparentid']);
            unset($list[$k]['listorder']);
            unset($list[$k]['url']);
            if($v['thumb']){
                $thumb = json_decode($v['thumb'],true);
                $list[$k]['thumb'] = yunurl($thumb[0]['path']);
            }
            if($v['thumb_rec']){
                $thumb = json_decode($v['thumb_rec'],true);
                $list[$k]['thumb_rec'] = yunurl($thumb[0]['path']);
            }
            if (isset($list[$v['parentid']])) {
                $list[$v['parentid']]['sub'][] = &$list[$k];
            }else {
                $tree[] = &$list[$k];
            }
        }
        return $tree;
    }


    //分销爆款
    function comms(){

        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;//页码
        $size = 10;
        $options['where'] = " AND g.comm_scale>0";

        $order = '';
        if($_GET['order']=='sales'){
            $order = " g.sell desc,g.listorder desc ";
        }elseif($_GET['order']=='comms_scale'){
            $order = " g.comm_scale desc,g.listorder desc";
        }
        $options['order'] = $order==''?"g.comm_scale desc,g.listorder desc":$order;
        $list = $this->goods->getList($size, $page, 0, 0, $options);
        $data['list'] = null;
        if($list){
            foreach($list as $k=>$v){
                $temp = array();
                $temp['id'] = $v['id'];
                $temp['typeid'] = $v['typeid'];
                $temp['name'] = $v['name'];
                $temp['desc'] = $v['desc'];
                $temp['price'] = $v['price'];
                $temp['team_price'] = $v['team_price'];
                $temp['team_num'] = $v['team_num'];
                $temp['img_src'] = $v['img_src'];
                $temp['img_cover'] = $v['img_cover'];
                $temp['sell'] = formatNum($v['sell']+$v['sales_num']);
                $temp['discount_price'] = discount($v['price'], $v['team_price']);
				$temp['comm_scale'] = $v['comm_scale'];
                $data['list'][$k] = $temp;
            }
        }

        $data['list_total'] = (int)$this->page->pages['total'];

        $this->api_result(array('data'=>$data));
    }


    //团长免单
    function free_discount(){
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;//页码
        $options = array();
        $size = 10;
        $type = isset($_REQUEST['type']) ? intval($_REQUEST['type']) : 0;
        $data = array();
        $options['where'] = " and g.discount_type=1 ";

        #排序
        $order = isset($_REQUEST['order']) ? trim($_REQUEST['order']) : 'count';
        $sort = isset($_REQUEST['sort']) ? trim($_REQUEST['sort']) : 'DESC';
        $orderby = '';
        if($order=='goods_id'){
            $orderby = " (case when id={$sort} then 1 ELSE 4 END),g.listorder desc,g.id desc";
        }
        if ($orderby) {
            $options['order'] = $orderby;
        }

        #商品列表
        $list = $this->goods->getList($size, $page, 0, $type, $options);
        $data['list'] = null;
        if($list){
            foreach($list as $k=>$v){
                $temp = array();
                $temp['id'] = $v['id'];
                $temp['typeid'] = $v['typeid'];
                $temp['name'] = $v['name'];
                $temp['desc'] = $v['desc'];
                $temp['price'] = $v['price'];
                $temp['team_price'] = $v['team_price'];
                $temp['team_num'] = $v['team_num'];
                $temp['img_src'] = $v['img_src'];
                $temp['img_cover'] = $v['img_cover'];
                $temp['sell'] = formatNum($v['sell']+$v['sales_num']);
                $data['list'][$k] = $temp;
            }
        }
        $data['list_total'] = (int)$this->page->pages['total'];
        $this->api_result(array('data'=>$data));
    }

    //众筹尝鲜
    function zcgoods(){
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;//页码
        $options = array();
        $size = 10;
        $type = isset($_REQUEST['type']) ? intval($_REQUEST['type']) : 0;
        $data = array();
        $options['where'] = " and g.typeid=11 ";
        #排序
        $order = isset($_REQUEST['order']) ? trim($_REQUEST['order']) : 'count';
        $sort = isset($_REQUEST['sort']) ? trim($_REQUEST['sort']) : 'DESC';
        $orderby = '';
        if($order=='goods_id'){
            $orderby = " (case when id={$sort} then 1 ELSE 4 END),g.listorder desc,g.id desc";
        }
        if ($orderby) {
            $options['order'] = $orderby;
        }

        #商品列表
        $list = $this->goods->getList($size, $page, 0, $type, $options);

        $data['list'] = null;
        if($list){
            foreach($list as $k=>$v){
                $temp = array();
                $temp['id'] = $v['id'];
                $temp['typeid'] = $v['typeid'];
                $temp['name'] = $v['name'];
                $temp['desc'] = $v['desc'];
                $temp['price'] = $v['price'];
                $temp['team_price'] = $v['team_price'];
                $temp['team_num'] = $v['team_num'];
                $temp['img_src'] = $v['img_src'];
                $temp['img_cover'] = $v['img_cover'];
                $temp['sell'] = formatNum($v['sell']+$v['sales_num']);
                $data['list'][$k] = $temp;
            }
        }
        $data['list_total'] = (int)$this->page->pages['total'];
        $this->api_result(array('data'=>$data));
    }


    //品牌清仓
    function brand_kill() {
        $cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;//页码
        $data = array('row' => array(), 'cid' => $cid);
        $id = $this->db->getstr("select id from ###_topic where typeid=13 and start_time<=".RUN_TIME." and end_time>=".RUN_TIME,"id");
        if(!$id)$this->api_result(array('flag' => false, 'msg' => '没有低价清仓', 'code' => 100002));
        $this->load->model("topiccat");
        $options = array();
        $options['size'] = 10;
        $options['page'] = $page;
        $options['where'] = " AND t.status=1 AND t.act_id={$id} ";
        if ($cid) {
            $this->load->model('topiccat');
            $options['where'] .= " AND t.cid" . $this->topiccat->condArrchild($cid);
        }
        #排序
        $order = $_GET['order'];
        $sort = $_GET['sort'];
        if($order=='goods_id'){
            $options['order'] = " (case when g.id={$sort} then 1 ELSE 4 END),t.listorder asc,sell_num desc";
        }else{
            $options['order'] = " t.listorder asc,sell_num desc";
        }
        #商品列表
        $this->load->model('page');
        $this->page->set_vars(array('per' => 10));
        $_GET['page'] = intval($page);
        $sql = "SELECT t.*,g.id,g.name,g.thumb,g.thumbs,g.price,g.team_price,g.team_num,g.sell,g.sales_num,g.stock,g.typeid,g.sell+g.sales_num as sell_num FROM ###_topic_goods as t LEFT JOIN ###_goods as g on t.goods_id=g.id WHERE 1 {$options['where']} order by {$options['order']}";
        $list = $this->page->hashQuery($sql)->result_array();
        $this->load->model("goods");
        foreach($list as $k=>$v){
            $list[$k] = $this->goods->getThumb($v);
        }
        $data['etime'] = kill_etime();
        $data['list'] = null;
        if($list){
            foreach($list as $k=>$v){
                $temp = array();
                $temp['id'] = $v['id'];
                $temp['typeid'] = $v['typeid'];
                $temp['name'] = $v['name'];
                $temp['desc'] = $v['desc'];
                $temp['price'] = $v['price'];
                $temp['team_price'] = $v['team_price'];
                $temp['team_num'] = $v['team_num'];
                $temp['thumb'] = $v['img_src'];
                $temp['img_src'] = $v['img_cover'];
                $temp['sell'] = formatNum($v['sell']+$v['sales_num']);
                //$temp['scale'] = round($v['sell']/($v['stock']+$v['sell']),2)*100;
                $data['list'][$k] = $temp;
            }
        }
        $data['list_total'] = (int)$this->page->pages['total'];

        #加载商品分类
        $data['cats'] = $this->topiccat->loadTopicCats($id);
        if ($cid) {
            $data['row'] = isset($data['cats'][$cid]) ? $data['cats'][$cid] : '';
            if (empty($data['row'])) {
                exit($this->msg());
            }
        }
        //列表头部显示二级分类
        $data['cat_list'] = null;
        if($data['cats']){
            $cats_array = genTop($data['cats']);
            if($cid==0 ){
                $data['cat_list'] = $cats_array;
            }else{
                if($data['row']['child']==0){//同级栏目
                    $data['cat_list'] = $this->topiccat->loadTopicCatsByCid($id,$data['row']['parentid']);
                }else{//下级栏目
                    $data['cat_list'] = $this->topiccat->loadTopicCatsByCid($id,$cid);
                }
            }
        }
        if($data['cat_list']){
            array_unshift($data['cat_list'],array("id"=>0,"catname"=>"全部"));
        }else{
            $data['cat_list']=array(0=>array("id"=>0,"catname"=>"全部"));
        }

        unset($data['row']);
        unset($data['cats']);
        $this->api_result(array('data'=>$data));
    }

    //新品热拼
    function news(){
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;//页码
        //获取海淘专题id
        $id = $this->db->getstr("select id from ###_topic where typeid=12 and start_time<=".RUN_TIME." and end_time>=".RUN_TIME,"id");

        $options = array();
        $options['size'] = 10;
        $options['page'] = $page;
        $options['where'] = " AND status=1 AND act_id={$id} ";
        #商品列表
        if($id){
            $where = " t.status=1 AND t.act_id={$id} ";
            $order = " ORDER BY t.listorder asc,sell_num desc";
            $this->load->model('page');
            $this->page->set_vars(array('per' => 10));
            $_GET['page'] = intval($page);
            $sql = "SELECT t.*,g.id,g.name,g.thumb,g.thumbs,g.price,g.team_price,g.team_num,g.sell,g.sales_num,g.sell+g.sales_num as sell_num FROM ###_topic_goods as t LEFT JOIN ###_goods as g on t.goods_id=g.id WHERE {$where} {$order}";
            $data['list'] = $this->page->hashQuery($sql)->result_array();
            $this->load->model("goods");
            foreach($data['list'] as $k=>$v){
                $thumb = json_decode($v['thumb'],true);
                $data['list'][$k]['thumb'] = yunurl($thumb[0]['path'],300);
                $data['list'][$k]['discount_price'] = discount($v['price'], $v['team_price']);
            }
        }else{
            $data['list'] = null;
        }

        $data['list_total'] = (int)$this->page->pages['total'];
        $data['user1'] = rand_user(2);
        $data['user2'] = rand_user(2);
        $this->api_result(array('data'=>$data));
    }

    /**
     * 获取广告
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

	//ajax 获取不同属性的价格和库存
	function ajax_goodspec() {
		$id   = intval($_GET['goods_id']);
		$spec = trim($_GET['spec'], '-');
		$sql  = "select price,team_price,spec,thumb from ###_goods_item where goods_id={$id} and spec='{$spec}'";
		$row  = $this->db->get($sql);
		if ($row) {
			$row['code'] = "success";
		} else {
			$row['code'] = 'failed';
		}
		if ($row['thumb']) {
			$arr            = json_decode($row['thumb'], true);
			$arr[0]['path'] = yunurl($arr[0]['path']);
			$row            = array_merge($row, $arr[0]);
		}
		$this->api_result(array('data' => $row));
	}
}