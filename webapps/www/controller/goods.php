<?php

/**
 * 商品控制器
 */
class goods extends Lowxp {

    function __construct() {
        parent::__construct();
        $this->load->model('goods');
        $this->load->model('goodscat');
    }

    //商品列表
    function index($id = 0, $page = 1) {
        $options = array();
        $size = 10;
        $tpl = 'goods/list.html';
        $type = isset($_REQUEST['type']) ? intval($_REQUEST['type']) : 0;
        $data = array('row' => array(), 'id' => $id, 'type' => $type, 'url' => '');

        #加载商品分类
        $data['cats'] = $this->goodscat->loadCats();
        
        
        #商品分类详情
        if ($id>0) {
            $data['row'] = isset($data['cats'][$id]) ? $data['cats'][$id] : '';
            if (empty($data['row'])) {
                exit($this->msg());
            }
        }
        #echo "<pre>";print_r($data['row']);exit;
        //列表头部显示二级分类
        $cats_array = $this->genTree9($data['cats']);
        if($data['row']['parentid']==0 && !isset($_GET['top'])){
            $data['cat_list'] = $cats_array;
            $data['cat_isrec'] = $this->db->select("SELECT * FROM ###_goods_cat WHERE isrec=1 and ismenu=1 and FIND_IN_SET('{$id}',arrparentid) ORDER BY listorder ASC,id ASC");
        }else{
           if(isset($_GET['top'])){//下级栏目
                $data['cat_list'] = $this->goodscat->loadCats($id);
           }else{//同级栏目
                $data['cat_list'] = $this->goodscat->loadCats($data['row']['parentid']);
           }
           $data['top'] = isset($_GET['top'])?$_GET['top']:$data['row']['parentid'];
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
        if (isset($_GET['nation_id']) && $_GET['nation_id']>0) {
            $this->load->model('nation');
            $options['where'] .= " and g.nation_id" . $this->nation->condArrchild($_GET['nation_id']);
        }
        if (isset($_GET['country_id']) && $_GET['country_id']>0) {
            $options['where'] .= " and g.country_id = ".  intval($_GET['country_id']);
        }
        if (isset($_GET['typeid']) && $_GET['typeid']>0) {
            $options['where'] .= " and g.typeid = ".  intval($_GET['typeid']);
			$this->load->model('score');
            $rule_6 = $this->score->getRow(6);
            if($_GET['typeid']==CART_EXCHANGE&&$rule_6['status']==0){
            	$this->error("无权操作权限！","/");
				die;
            }
        }elseif($_GET['index']==1){
            //$options['where'] .= " and g.typeid in (0,1,2,4,8,9,10,11)";
        }
        
        #品牌筛选
        $bid = intval($_REQUEST['bid']);
        if ($bid > 0) {
            $data['url'] .= '&bid=' . $_REQUEST['bid'];
            $this->load->model('brand');
            $options['where'] .= " and g.bid" . $this->brand->condArrchild($bid);
        }
        #搜索
        if (isset($_REQUEST['q'])) {
            $data['url'] .= '&q=' . $_REQUEST['q'];
            $options['q'] = trim($_REQUEST['q']);
        }

        #排序
        $order = isset($_REQUEST['order']) ? trim($_REQUEST['order']) : 'listorder';
        $sort = isset($_REQUEST['sort']) ? trim($_REQUEST['sort']) : 'ASC';
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
        //echo $orderby;exit;
        #商品列表
        $data['list'] = $this->goods->getList($size, $page, $id, $type, $options);
		
        #异步加载
        if (isset($_GET['load'])) {
            $content = '';
            if ($data['list']) {                
                foreach ($data['list'] as $key=>$v) {
                    $this->smarty->assign('typeid', isset($_GET['typeid'])?$_GET['typeid']:'-1');
                    $this->smarty->assign('m', $v);
                    $this->smarty->assign('k', $key+($page-1)*$size);
                    $content .= $this->smarty->fetch('goods/lbi/goods_list.html');
                }
            }
            echo $content;
            die;
        }
        #echo "<pre>";print_r($data);exit;
        if ($type == 1) {
            $data['nav'] = 3;
        } else {
            $data['nav'] = 2;
        }
        $this->smarty->assign('data', $data);
        $this->smarty->assign('typeid', isset($_GET['typeid'])?$_GET['typeid']:'-1');
        $this->display_before($data['row']);      
        $this->smarty->display($tpl);
    }

    //商品详细
    function show($id, $page = 1) {
        
        $row = $this->goods->get($id);
        if (empty($row)) {
            $this->msg();
            die;
        }        
        
        $type = isset($_REQUEST['type']) ? trim($_REQUEST['type']) : '';
        $common_id = isset($_REQUEST['common_id']) ? intval($_REQUEST['common_id']) : 0;

        //是否收藏
        $this->load->model('fav');
        $row['is_fav'] = $this->fav->is_fav($row['id'], $_SESSION['mid']);
        $data = array('row' => $row, 'id' => $id, 'type' => $type,'common_id'=>$common_id);

        //评价总数
        $where = "WHERE state=1 AND good_id=" . $id;
        $sql = "select COUNT(id) from ###_goods_rate " . $where;
        $data['count'] = $this->db->getstr($sql);

        //Feng 2016-07-19 商品规格 start
        if ($row['sp_val']) {
            $row['sp_val'] = json_decode($row['sp_val'], true);
            $sp_ids = join(',', array_keys($row['sp_val']));
            $sp_arr = $this->db->select("select * from ###_goods_spec where id in ($sp_ids)");
            $sp_val = array_combine(array_column($sp_arr, 'id'), array_column($sp_arr, "name"));
            foreach ($row['sp_val'] as $k => $v) {
                $data['sp_vals'][$sp_val[$k]] = $v;
            }
            $data['spec'] = array();
            $spec = trim($_GET['spec']);
            if ($spec)
                $data['spec'] = explode("-", $spec);
        }
        //Feng 2016-07-20 商品规格 end
        
        //Feng 2016-05-18 商品属性 start
        /*if ($row['goods_type'] > 0) {
            $this->load->model('goodstype');
            $properties = $this->goodstype->get_goods_properties($id);
            $data['attr'] = $properties['pro'];
            $data['spe'] = $properties['spe'];
        }*/
        //Feng 2016-05-18 商品属性 end      
          
        //拼团列表   1元购和免费试用 要新会员才能参团            
        if(check_team(MID,$row['typeid']) && $row['stock']>0 && $common_id==0 && $row['typeid']!=CART_LUCK && $row['typeid']!=AA){
            $this->load->model("team");
            $data['team_list'] = $this->team->getTeamList($id,array("status"=>TEAM_ING));
        }elseif($common_id>0){
            $team_price = $this->db->getstr("select team_price from ###_goods_order_common where id=".$common_id,"team_price");
            if($team_price>0)$data['row']['team_price'] = $team_price;
        }
        
        //抽奖、免费试用、AA团规则
        if(in_array($row['typeid'],array(CART_AA,CART_FREE,CART_LUCK,CART_SHARE,CART_STEP))){
            $coupon = '';
            if($row['coupon_id']>0){
                $coupon = $this->db->getstr("select title from ###_coupon where id=".$row['coupon_id'],"title");
            }
            if($row['typeid']==CART_LUCK)$data['row']['rule'] = C('lottery_rule'); 
            if($row['typeid']==CART_FREE)$data['row']['rule'] = C('free_rule');
            if($row['typeid']==CART_AA)$data['row']['rule'] = C('aa_rule');
            if($row['typeid']==CART_SHARE)$data['row']['rule'] = C('share_rule');
            if($row['typeid']==CART_STEP)$data['row']['rule'] = C('step_rule');
            $patterns = array("/【开始时间】/","/【结束时间】/","/【中奖人数】/","/【标题】/","/【优惠券】/");
            $replacements = array(date("m月d日",$row['start_time']),date("m月d日",$row['end_time']),$row['luck_num'],$row['name'],$coupon);            
            $data['row']['rule'] = preg_replace ( $patterns ,  $replacements ,  $data['row']['rule'] );
        }
        
        //商品标签 包邮 7天退换 48小时发货
        if($row['goods_tip']){
            $this->load->model("goods_tag");
            $tags = $this->goods_tag->getList(array("where"=>" AND id in({$row['goods_tip']})"));
            if($tags)$data['row']['goods_tip_list'] = array_column($tags,"name");
        }
        //商品类型名称
        $this->load->model("order");
        $data['row']['typename'] = $this->order->actTypes[$row['typeid']]['title'];

        //获取商家信息
        if($data['row']['sid']>0){
            $this->load->model("business");
            $store['info'] = $this->business->get($data['row']['sid'],"name,logo,fav_num,kf_online");
            //统计商品数量
            $store['goods_total'] = $this->db->getstr("select count(1) as num from ###_goods where is_sale=1 and sid=".$data['row']['sid'],"num");
            //统计销量
            $store['sell_total'] = $this->db->getstr("select sum(sell+sales_num) as num from ###_goods where is_sale=1 and sid=".$data['row']['sid'],"num");
            //判断是否已关注
            $store['is_fav'] = $this->db->get("select 1 from ###_business_fav where sid={$data['row']['sid']} and mid=".MID);
            $data['store'] = $store;

            $data['tj_list'] = array();
            $data['tj_list'] = $this->db->select("select * from ###_goods where id!={$data['row']['id']} and sid={$data['row']['sid']} and is_sale=1 and status=0 and stock>0 and is_new=1 order by listorder desc,id desc limit 10");
            foreach($data['tj_list'] as $key=>$val){
                $data['tj_list'][$key] = $this->goods->getThumb($val);
            }
            $data['kf_online'] = $store['info']['kf_online'];
        }else{
            $data['kf_online'] = C('kf_online');
        }

        //判断阶梯团商品是否存在开团中的拼团
        if($row['typeid']==CART_STEP){
            $res_step = $this->db->get("select id,team_price from ###_goods_order_common where goods_id={$row['id']} and status=".TEAM_ING." and e_time>=".RUN_TIME);
            $data['team'] = $res_step;
            if($data['team']){
                arsort($data['row']['step']['team_num']);
                $data['team']['team_max'] = reset($data['row']['step']['team_num']);
                $team_scale = round($data['team']['team_num_yes']/$data['team']['team_max']*100);
                $data['team']['team_scale'] = $team_scale>100?100:$team_scale;
                $data['common_id'] = $data['team']['id'];
                $data['row']['team_price'] = $data['team']['team_price'];
                if(defined("MID")) {
                    $is_res = $this->db->getstr("select common_id from ###_goods_order where common_id={$data['common_id']} and (status_common=1 or status_common=10) and mid=" . MID, "common_id");
                    if ($is_res) $data['join_common_id'] = $is_res;
                }
            }
        }

        //判断商品是否限购以及是否购买过
        if($data['row']['limit_buy_one']==1 && defined("MID")){
            $is_res = $this->db->getstr("select common_id from ###_goods_order where extension_id={$data['row']['id']} and  (status_common=1 or status_common=10) and mid=".MID,"common_id");
            if($is_res)$data['join_common_id'] = $is_res;
        }
        //秒杀试用时间未开始隐藏销量
        if($row['start_time']>RUN_TIME && in_array($row['typeid'],array(CART_FREE,CART_LUCK,CART_KILL))){
            $data['row']['sales_num'] = $data['row']['sell'] = 0;
        }

        //判断是否在为开始的秒杀中
        $data['is_wkill'] = $this->goods->is_wkill($data['row']['id']);

        $this->smarty->assign('data', $data);
        $this->display_before($data['row']);

        if($row['typeid']==CART_GOODS||$row['typeid']==CART_EXCHANGE){//单独购买
			if($row['typeid']==CART_EXCHANGE){
        		$this->load->model('score');
        		$rule_6 = $this->score->getRow(6);
        		if($rule_6['status']!=1){
        			$this->error("无权操作权限！","/");
        			die;
        		}
        	}
            $this->smarty->display('goods/show_buy.html');
        }else{
            $this->smarty->display('goods/show.html');
        }

    }

    //ajax 获取不同属性的价格和库存
    function ajax_goodspec() {
        $id = intval($_POST['goods_id']);
        $spec = trim($_POST['spec'], '-');
        $sql = "select price,team_price,spec,thumb from ###_goods_item where goods_id={$id} and spec='{$spec}'";
        $row = $this->db->get($sql);
        if ($row){
            $row['code'] = "success";            
        }else{
            $row['code'] = 'failed';
        }            
        if ($row['thumb']) {
            $arr = json_decode($row['thumb'], true);
            $arr[0]['path'] = yunurl($arr[0]['path']);
            $row = array_merge($row, $arr[0]);
        }
        
        echo json_encode($row);
        exit;
    }

    //ajax获取商品属性
    function ajax_attr($id) {
        $sql = "select a.*,b.attr_name from ###_goods_attr as a left join ###_attribute as b on a.attr_id = b.attr_id where a.goods_id={$id} and b.attr_type=0 ";
        $data['list'] = $this->db->select($sql);
        if ($data['list']) {
            $content = '';
            foreach ($data['list'] as $v) {
                $this->smarty->assign('m', $v);
                $content .= $this->smarty->fetch('goods/lbi/attr_list.html');
            }
            echo $content;
            die;
        }
    }

    //ajax获取商品评价
    function ajax_rate($id, $page = 1) {

        $where = "WHERE state=1 AND good_id=" . $id;
        //$sql = "select COUNT(id) from ###_goods_rate " . $where;
        //$data['count'] = $this->db->getstr($sql);
        $size = 10;
        $this->load->model('page');
        $_GET['page'] = $page;
        $this->page->set_vars(array('per' => $size));
        $star = intval($_GET['star']);
        if ($star > 0) {
            $where .= " and star={$star}";
        }
        $sql = "SELECT * FROM ###_goods_rate " . $where . " ORDER BY listorder DESC,id DESC";
        $data['list'] = $this->page->hashQuery($sql)->result_array();
        if($data['list']==false)die;
        $data['list'] = $this->db->lJoin($data['list'], 'member_detail', 'mid,nickname', 'mid', 'mid');
        if ($data['list']) {
            $content = '';
            foreach ($data['list'] as $v) {
                $this->smarty->assign('m', $v);
                $content .= $this->smarty->fetch('goods/lbi/comment_list.html');
            }
            echo $content;
            die;
        }
    }

    //ajax获取商品评价
    function ajax_rate_count($id) {
        $data = array("star_1" => 0, "star_2" => 0, "star_3" => 0, "star_4" => 0, "star_5" => 0);
        $sql = "select COUNT(id) as num,star from ###_goods_rate WHERE state=1 AND good_id={$id}  group by star";
        $res = $this->db->select($sql);
        foreach ($res as $k => $v) {
            $data["star_" . $v['star']] = $v['num'];
        }
        echo json_encode($data);
        exit;
    }

    function search() {
        $data['search'] = explode(' ', C('keywords'));
        
        $this->display_before();
        $this->smarty->assign('data', $data);
        $this->smarty->assign('active', 2);
        $this->smarty->display('goods/search.html');
    }

    //收藏商品
    function addFav() {
        $data = array('error' => 2, 'msg' => '未知错误', 'type' => '');

        //未登录
        if (!$_SESSION['mid']) {
            $data['error'] = 1;
            exit(json_encode($data));
        }

        //获取必要参数
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        if ($id == 0) {
            $data['msg'] = '参数错误！';
            exit(json_encode($data));
        }

        $this->load->model('fav');
        $res = $this->fav->save($id, $_SESSION['mid']);

        $data['error'] = $res['code'];
        $data['msg'] = $res['message'];
        $data['type'] = $res['type'];
        exit(json_encode($data));
    }

    //分类列表
    function cat($id = '', $page = 1) {
        $_GET['page'] = $page;

        $list = $this->goodscat->select();

        $list = $this->goodscat->treeWithThumb($list);
        foreach($list as $k=>$v){
            if($v['ismenu']==0)unset($list[$k]);
            if(count($v['sub'])>0){
                foreach($v['sub'] as $_k=>$_v){
                    if($_v['ismenu']==0)unset($list[$k]['sub'][$_k]);
                }
            }
        }
        $list = array_values($list);
        /* $goods_list = $this->goods->getList(12, $page, $id);
          #异步加载
          if (isset($_GET['load'])) {
          if ($goods_list) {
          $content = '';
          foreach ($goods_list as $v) {
          $this->smarty->assign('m', $v);
          $content .= $this->smarty->fetch('goods/lbi/cat_goods_list.html');
          }
          echo $content;die;
          }
         */
        #print_r($list);exit;
        $this->smarty->assign('id', $id);
        $this->smarty->assign('list', $list);
        //$this->smarty->assign('goods_list', $goods_list);
        $this->smarty->display('goods/cat.html');
    }

    function genTree9($list) {
        $tree = array();
        foreach ($list as $k => $v) {
            if (isset($list[$v['parentid']])) {
                $list[$v['parentid']]['sub'][$k] = &$list[$k];
            } else {
                $tree[$v['id']] = &$list[$k];
            }
        }
        return $tree;
    }

    //品牌列表
    function brand($bid=0,$page=1) {
        $data['bid'] = $bid;
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

        $this->load->model("brand");
        foreach($data['list'] as $k=>$v){
            $data['list'][$k]['end_day'] = $v['end_time']>=RUN_TIME?(ceil(($v['end_time']-RUN_TIME)/(24*3600))):0;
            $options = array();
            $options['where'] .= " and g.bid" . $this->brand->condArrchild($v['id']);
            $options['order'] = " g.listorder asc,sell_num desc";
            $data['list'][$k]['goods_list'] = $this->goods->getList(10,0,0,0,$options);
        }

        #异步加载
        if (isset($_GET['load'])) {
            $content = '';
            if ($data['list']) {
                foreach ($data['list'] as $v) {
                    $this->smarty->assign('v', $v);
                    $content .= $this->smarty->fetch('goods/lbi/brand_list.html');
                }
            }
            echo $content;
            die;
        }

        #加载商品分类
        $data['cats'] = $this->brand->loadCats();
        $data['cat_list'] = $this->genTree9($data['cats']);

        $this->smarty->assign('data', $data);
        $this->smarty->display('goods/brand.html');
    }
    
    //品牌列表
    function brand_goods($top=0,$id=0) {
        if($id==0)$id=$top;
        $this->load->model("brand");
        $data['cats'] = $this->brand->loadCats();
        $data['top'] = $data['cats'][$top];
        $data['row'] = $data['cats'][$id];
        $data['row']['end_time'] = $data['row']['end_time']>RUN_TIME?($data['row']['end_time']-RUN_TIME):0;
        //print_r($data['top']);exit;
        if($data['top']['child']==1){
            $sql = "select * from ###_brand where ismenu=1 and parentid={$top} and (end_time=0 or end_time>0 and end_time>=".RUN_TIME." ) order by listorder desc,id desc";
            $data['brand_list'] = $this->db->select($sql);
        }
        //echo "<pre>";print_r($data);exit;
        $this->smarty->assign("data",$data);
        $this->smarty->display('goods/brand_goods.html');
    }
    
     //海淘列表
    function nation($page=1) {

        $this->load->model("goods");
        //获取海淘广告图
        $ad_list = $this->get_ad("11");
        $data['ad_list'] = $ad_list;

        //首页头部导航
        $data['nav_top'] = get_nav(3);

        //获取商品分类顶级栏目
        $this->load->model("nation");
        $data['cat_list'] = $this->nation->cate();

        //获取国家馆
        $data['country_list'] = array();
        $country = $this->db->select("select id,catname,thumb from ###_country where ismenu=1 ");
        if($country){
            foreach($country as $k=>$v){
                /*if($v['thumb']){
                    $thumb = json_decode($v['thumb'],true);
                    $v['thumb'] = $thumb[0]['thumb'];
                }*/
                $data['country_list'][$k] = $v;
            }
        }

        $this->smarty->assign('data', $data);        
        $this->smarty->display('goods/nation.html');
        
    }
    
    //海淘商品列表
    function nation_goods($id=0){

        $this->load->model("nation");
        $data = array('row' => array(), 'id' => $id);
        $data['country_id'] = isset($_GET['country_id'])?intval($_GET['country_id']):0;
        #加载商品分类
        $data['cats'] = $this->nation->loadCats();


        #海淘分类详情
        if ($id>0) {
            $data['row'] = isset($data['cats'][$id]) ? $data['cats'][$id] : '';
            if (empty($data['row'])) {
                exit($this->msg());
            }
        }

        //列表头部显示二级分类
        /*$cats_array = $this->genTree9($data['cats']);
        if($data['row']['parentid']==0 && !isset($_GET['all'])){
            $data['cat_list'] = $cats_array;
            $data['cat_isrec'] = $this->db->select("SELECT * FROM ###_nation WHERE isrec=1 and ismenu=1 and FIND_IN_SET('{$id}',arrparentid) ORDER BY listorder DESC,id DESC");
        }else{
            if($data['row']['arrchildid']==$id){//同级栏目
                $data['cat_list'] = $this->nation->loadCats($data['row']['parentid']);
            }else{//下级栏目
                $data['cat_list'] = $this->nation->loadCats($id);
            }
        }*/
        $cats_array = $this->genTree9($data['cats']);
        if($data['row']['parentid']==0 && !isset($_GET['top'])){
            $data['cat_list'] = $cats_array;
            $data['cat_isrec'] = $this->db->select("SELECT * FROM ###_nation WHERE isrec=1 and ismenu=1 and FIND_IN_SET('{$id}',arrparentid) ORDER BY listorder DESC,id DESC");
        }else{
            if(isset($_GET['top'])){//下级栏目
                $data['cat_list'] = $this->nation->loadCats($id);
            }else{//同级栏目
                $data['cat_list'] = $this->nation->loadCats($data['row']['parentid']);
            }
            $data['top'] = isset($_GET['top'])?$_GET['top']:$data['row']['parentid'];
        }


        //echo "<pre>";print_r($data['cat_list']);exit;
        /*$sql = "select * from ###_nation where ismenu=1 order by listorder desc,id desc";
        $data['nation_list'] = $this->db->select($sql);
        $data['id'] = $id;
        foreach($data['nation_list'] as $k=>$v){
            if($v['id']==$id){
                $data['row'] = $v;
                break;
            }
        }*/
        $this->smarty->assign('data', $data);        
        $this->smarty->display('goods/nation_goods.html');
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
    //ajax获取大家都在团 商品
    function ajax_rand() {
        $options['where'] = " and g.typeid !=7 ";
        $options['order'] = " sell_num desc,g.listorder desc,g.id DESC";
        $data['list'] = $this->goods->getList(8, 1, 0, 0, $options);        
        if ($data['list']) {
            $content = '';
            foreach ($data['list'] as $v) {
                $this->smarty->assign('m', $v);
                $content .= $this->smarty->fetch('goods/lbi/rand_list.html');
            }
            echo $content;
            die;
        }
    }
    
    //团购详情
    function team($common_id) {
        
        $this->load->model("team");
        $data = $this->team->get($common_id,'',array("limit"=>" limit 15"));
        $data['is_login'] = defined("MID")?true:false;
        
        //剩余人数
        $data['row']['team_num_no'] = $data['row']['team_num']-$data['row']['team_num_yes'];
        
        //var_dump($data['is_login']);
        if($data['is_login']){
            //团长
            $data['tz'] = 0;
            if($data['row']['mid']==MID)$data['tz'] = 1;

            //判断购买次数限制
            $data['limit_one'] = false;
            if($data['goods']['limit_buy_one']==1){
                $is_res = $this->db->get("select 1 from ###_goods_order where extension_id={$data['row']['goods_id']} and status_common>0 and mid=".MID);            
                $data['limit_one'] = $is_res==false?false:true;
            }
            
            //判断是否参团        
            $is_join = $this->db->get("select 1 from ###_goods_order where common_id={$common_id} and status_common>0 and mid=".MID);            
            $data['is_join'] = $is_join==false?false:true;

            //拼团列表   1元购和免费试用 要新会员才能参团            
            $data['check_team'] = check_team(MID,$data['goods']['typeid']);
        }        
        //echo "<pre>";print_r($data);exit;
        if($data['goods']['typeid']==CART_STEP){
            arsort($data['goods']['step']['team_num']);
            $data['goods']['team_max'] = reset($data['goods']['step']['team_num']);
            $team_scale = round($data['row']['team_num_yes']/$data['goods']['team_max']*100);
            $data['goods']['team_scale'] = $team_scale>100?100:$team_scale;
        }
        if ($data['goods']['sp_val']) {
            $data['goods']['sp_val'] = json_decode($data['goods']['sp_val'], true);
            $sp_ids = join(',', array_keys($data['goods']['sp_val']));
            $sp_arr = $this->db->select("select * from ###_goods_spec where id in ($sp_ids)");
            $sp_val = array_combine(array_column($sp_arr, 'id'), array_column($sp_arr, "name"));
            foreach ($data['goods']['sp_val'] as $k => $v) {
                $data['goods']['sp_vals'][$sp_val[$k]] = $v;
            }
            $data['goods']['spec'] = array();
            $spec = trim($_GET['spec']);
            if ($spec)$data['goods']['spec'] = explode("-", $spec);
        }
        //抽奖、免费试用、AA团规则
        if(in_array($data['goods']['typeid'],array(CART_AA,CART_FREE,CART_LUCK,CART_STEP))){
            $coupon = '';
            if($data['goods']['coupon_id']>0){
                $coupon = $this->db->getstr("select title from ###_coupon where id=".$data['goods']['coupon_id'],"title");
            }
            if($data['goods']['typeid']==CART_LUCK)$data['goods']['rule'] = C('lottery_rule');
            if($data['goods']['typeid']==CART_FREE)$data['goods']['rule'] = C('free_rule');
            if($data['goods']['typeid']==CART_AA)$data['goods']['rule'] = C('aa_rule');
            if($data['goods']['typeid']==CART_STEP)$data['goods']['rule'] = C('step_rule');
            $patterns = array("/【开始时间】/","/【结束时间】/","/【中奖人数】/","/【标题】/","/【优惠券】/");
            $replacements = array(date("m月d日",$data['goods']['start_time']),date("m月d日",$data['goods']['end_time']),$data['goods']['luck_num'],$data['goods']['name'],$coupon);
            $data['goods']['rule'] = preg_replace ( $patterns ,  $replacements ,  $data['goods']['rule'] );
        }
        $data['team_list'] = $this->team->getTeamList($data['goods']['id'],array("status"=>TEAM_ING,"limit"=>3));
        if(!S("CH_TEAM_NAV")){
            $nav_list = get_nav(2);
            if($nav_list)S("CH_TEAM_NAV",$nav_list);
        }
        $data['nav'] = S("CH_TEAM_NAV");

        $qrurl = creat_tmp_code(url("/goods/team/{$data['row']['id']}?inviter_id=". MID),3);
        $this->smarty->assign('qrurl', $qrurl);

        $this->smarty->assign('data', $data);
        $this->display_before($data['goods']);
        $this->smarty->display('goods/team.html');
    }
    
    //中奖名单
    function luck($id,$page=1) {
        
        //分页
        $this->load->model('page');
        $_GET['page'] = $page;
        $size         = (int) $this->site_config['page_size'];
        $this->page->set_vars(array('per' => $size));
                
        $sql  = "SELECT a.*,m.mobile FROM ###_lottery_log as a left join ###_member as m on a.mid=m.mid WHERE a.act_id='" . $id . "' ORDER BY a.id DESC";
        $data['list'] = $this->page->hashQuery($sql)->result_array();
        #异步加载
        if (isset($_GET['load'])) {
            $content = '';
            if ($data['list']) {                
                foreach ($data['list'] as $v) {
                    $v['mobile'] = cut_format($v['mobile'], 3, 4);
                    $v['order_sn'] = cut_format($v['order_sn'], 3, 4);
                    $this->smarty->assign('m', $v);
                    $content .= $this->smarty->fetch('goods/lbi/luck_list.html');
                }
            }
            echo $content;
            die;
        }
        
        foreach($data['list'] as $k=>$v){
            $data['list'][$k]['mobile'] = cut_format($v['mobile'], 3, 4);
            $data['list'][$k]['order_sn'] = cut_format($v['order_sn'], 3, 4);
        }
        
        $this->load->model("goods");
        $data['goods'] = $this->goods->get($id);

        //echo "<pre>";print_r($data['goods']);exit;
        $this->smarty->assign('data', $data);
        $this->smarty->display('goods/luck.html');
    }
    
    //抽奖和免费试用列表
    function lottery($typeid){
        $this->smarty->assign('typeid', $typeid);
        $data['title'] = $typeid==5?"免费试用":"抽奖";
        $this->display_before($data);
        $this->smarty->display('goods/lottery.html');
    }
    
    //秒杀列表
    function kill_bak($time=0,$page=1){
        $now = strtotime(date("Y-m-d H:00",RUN_TIME));
        $mday = strtotime(date("Y-m-d"))+24*3600;
       
        //获取当天秒杀的时间段
        $sql = "select distinct start_time from ###_goods where start_time>={$now} and start_time<={$mday} order by start_time asc";
        $res_list = $this->db->select($sql);
        $kill_time = array();
        foreach($res_list as $k=>$v){
            $temp = array();
            $temp['time'] = $v['start_time'];
            $temp['date'] = date("H:i",$v['start_time']);
            $kill_time[] = $temp;
        }
        $data['kill_time'] = $kill_time;
        
        if($data['kill_time']){
            if($time==0){//没传时间则取时间段第一个          
                $time = $data['now_time'] = $kill_time[0]['time'];   
            }else{
                $data['now_time'] = $time;
            }
            //获取下一次秒杀的时间
            if($time<RUN_TIME){//抢购中
                if(count($data['kill_time'])>1){
                    $data['going_time'] = $kill_time[1]['time']-RUN_TIME;
                }
            }else{//即将开始
                $data['well_time'] = $time-RUN_TIME;
            }

            $options = array();
            $size = 10;

            if($data['now_time']==$kill_time[0]['time']){//抢购中  如果抢购时间前还有商品没有被抢完可以放到 抢购中            
                $options['where'] .= "and g.typeid = 3 and (g.start_time<{$data['now_time']} and g.stock>g.sell or g.start_time={$data['now_time']}) and g.end_time>=".RUN_TIME;
            }else{//即将开始
                $options['where'] .= "and g.typeid = 3 and g.start_time={$data['now_time']} and g.end_time>=".RUN_TIME;
            }

             #排序
            $order = isset($_REQUEST['order']) ? trim($_REQUEST['order']) : 'stock';
            $sort = isset($_REQUEST['sort']) ? trim($_REQUEST['sort']) : 'DESC';
            $orderby = '';        
            $data['list'] = $this->goods->getList($size, $page, $id, $type, $options);

            foreach($data['list'] as $k=>$v){
                $data['list'][$k]['scale'] = round($v['sell']/($v['stock']+$v['sell']),2)*100;
            }
        }  
        
        #异步加载
        if (isset($_GET['load'])) {
            $content = '';
            if ($data['list']) {                
                foreach ($data['list'] as $v) {
                    $this->smarty->assign('m', $v);
                    $content .= $this->smarty->fetch('goods/lbi/kill_list.html');
                }
            }
            echo $content;
            die;
        }
        $this->smarty->assign('data', $data);
        $this->smarty->display("goods/kill.html");
    }

    //秒杀
    function kill($time='now',$page=1){

		if($time=='now'){
			$id = $this->db->getstr("select id from ###_topic where typeid=5 and start_time<=".RUN_TIME." and end_time>=".RUN_TIME,"id");
		}else{
			$id = $this->db->getstr("select id from ###_topic where typeid=5 and start_time>".RUN_TIME,"id");
		}
        if(!$id>0)$id=0;
        //if(!$id)$this->msg();
        $where = " t.status=1 AND t.act_id={$id} ";
        $order = isset($_REQUEST['order']) ? trim($_REQUEST['order']) : '';
        $sort = isset($_REQUEST['sort']) ? trim($_REQUEST['sort']) : '';
        if($order=='goods_id'){
            $order = " ORDER BY (case when t.goods_id={$sort} then 1 ELSE 4 END),t.listorder asc,sell_num desc";
        }else{
            $order = " ORDER BY t.listorder asc,sell_num desc";
        }

        $this->load->model('page');
        $_GET['page'] = intval($page);
        $this->page->set_vars(array('per' => 10));
        $sql = "SELECT t.*,g.id,g.name,g.desc,g.thumb,g.thumbs,g.price,g.team_price,g.team_num,g.sell,g.sales_num,g.sell+g.sales_num as sell_num,g.stock,g.start_time,g.typeid FROM ###_topic_goods as t LEFT JOIN ###_goods as g on t.goods_id=g.id WHERE {$where} {$order}";
        $data['list'] = $this->page->hashQuery($sql)->result_array();
        $this->load->model("goods");
        foreach($data['list'] as $k=>$v){
            $data['list'][$k] = $this->goods->getThumb($v);
            if($v['start_time']>RUN_TIME && in_array($v['typeid'],array(CART_FREE,CART_LUCK,CART_KILL))){
                $data['list'][$k]['scale'] = $data['list'][$k]['sell'] = $data['list'][$k]['sales_num'] = 0;
            }else{
                if($v['stock']+$v['sell']+$v['sales_num']>0)$data['list'][$k]['scale'] = round(($v['sell']+$v['sales_num'])/($v['stock']+$v['sell']+$v['sales_num']),2)*100;
            }

        }
        
        #异步加载
        if (isset($_GET['load'])) {
            $content = '';
            if ($data['list']) {
                foreach ($data['list'] as $key=>$v) {
                    $this->smarty->assign('type', $time);
                    $this->smarty->assign('m', $v);
                    $this->smarty->assign('k', $key+($page-1)*10);
                    $content .= $this->smarty->fetch('goods/lbi/kill_list.html');
                }
            }
            echo $content;
            die;
        }
        $data['kill_etime'] = kill_etime();
        $this->smarty->assign('type', $time);
        $this->smarty->assign('data', $data);
        $this->smarty->display("goods/kill.html");
    }
    
    
    //拼团列表
    function common($page = 1){
        $this->load->model("team");
        $option['page'] = $page;
        $option['status'] = 1;  
        $option['where'] = " and goods_typeid<6 ";
        $data['list'] = $this->team->getList($option);
        foreach($data['list'] as $k=>$v){
            $v['end_time'] = $v['e_time']-RUN_TIME;
            $data['list'][$k] = $v;
        }
         #异步加载
        if (isset($_GET['load'])) {
            $content = '';
            if ($data['list']) {                
                foreach ($data['list'] as $v) {                  
                    $this->smarty->assign('m', $v);
                    $content .= $this->smarty->fetch('goods/lbi/common_list.html');
                }
            }
            echo $content;
            die;
        }
        $this->smarty->assign('data', $data);
        $this->smarty->display("goods/common.html");
    }

    //广场
    function square($page = 1){
        $this->load->model('page');
        $_GET['page'] = $page;
        $size = (int) $this->site_config['page_size'];
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
            $data['list'][$k]['img_cover'] = $res['img_cover'];
        }
        if (isset($_GET['load'])) {
            $content = '';
            if ($data['list']) {
                foreach ($data['list'] as $v) {
                    $this->smarty->assign('m', $v);
                    $content .= $this->smarty->fetch('goods/lbi/square_list.html');
                }
            }
            echo $content;
            die;
        }
        $this->smarty->assign('data', $data);
        $this->smarty->assign('active', 3);
        $this->smarty->display("goods/square.html");
    }

    //分销爆款
    function comms($page=1){
        $size = 10;
        $options['where'] = " AND g.comm_scale>0";

        $order = '';
        if($_GET['order']=='sales'){
            $order = " sell_num desc,g.listorder desc ";
        }else{
            $order = " g.comm_scale desc,g.listorder desc";
            $this->smarty->assign('comms', 1);//显示佣金
        }
        $options['order'] = $order==''?"g.comm_scale desc,g.listorder desc":$order;
        $data['list'] = $this->goods->getList($size, $page, 0, 0, $options);

        #异步加载
        if (isset($_GET['load'])) {
            $content = '';
            if ($data['list']) {
                foreach ($data['list'] as $key=>$v) {
                    $this->smarty->assign('typeid', isset($_GET['typeid'])?$_GET['typeid']:'-1');
                    $this->smarty->assign('m', $v);
                    $this->smarty->assign('k', $key+($page-1)*$size);
                    $content .= $this->smarty->fetch('goods/lbi/goods_list.html');
                }
            }
            echo $content;
            die;
        }
        $this->smarty->assign('typeid', isset($_GET['typeid'])?$_GET['typeid']:'-1');
        $this->smarty->assign('data', $data);
        $this->smarty->display('goods/comms.html');
    }

    //团长免单
    function free_discount($page = 1){
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
        $data['list'] = $this->goods->getList($size, $page, 0, $type, $options);

        #异步加载
        if (isset($_GET['load'])) {
            $content = '';
            if ($data['list']) {
                foreach ($data['list'] as $key=>$v) {
                    $this->smarty->assign('m', $v);
                    $content .= $this->smarty->fetch('goods/lbi/goods_list.html');
                }
            }
            echo $content;
            die;
        }
        //echo "<pre>";print_r($data);exit;
        $this->smarty->assign('data', $data);
        $this->display_before($data['row']);
        $this->smarty->display('goods/free_discount.html');
    }

    //众筹尝鲜
    function zcgoods($page = 1){
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
        $data['list'] = $this->goods->getList($size, $page, 0, $type, $options);

        #异步加载
        if (isset($_GET['load'])) {
            $content = '';
            if ($data['list']) {
                foreach ($data['list'] as $key=>$v) {
                    $this->smarty->assign('m', $v);
                    $content .= $this->smarty->fetch('goods/lbi/goods_list.html');
                }
            }
            echo $content;
            die;
        }
        //echo "<pre>";print_r($data);exit;
        $this->smarty->assign('data', $data);
        $this->display_before($data['row']);
        $this->smarty->display('goods/zcgoods.html');
    }


    //品牌清仓
    function brand_kill_bak($id=0,$page = 1) {

        $data = array('row' => array(), 'id' => $id);
        $this->load->model("brand");
        $data['cats'] = $this->brand->loadCats();
        if ($id) {
            $data['row'] = isset($data['cats'][$id]) ? $data['cats'][$id] : '';
            if (empty($data['row'])) {
                exit($this->msg());
            }
        }
        //$data['brand_list'] = $this->genTree9($data['cats']);
        if($id>0){
            if($data['row']['child']==1){//下级栏目
                $data['brand_list'] = $this->brand->loadCats($data['row']['id']);
            }else{//同级栏目
                $data['brand_list'] = $this->brand->loadCats($data['row']['parentid']);
                $data['brand_list'] = $this->genTree9($data['brand_list']);
            }
        }else{//顶级级栏目
            $data['brand_list'] = $this->brand->loadCats(0);
            $data['brand_list'] = $this->genTree9($data['brand_list']);
        }
        //echo "<pre>";print_r($this->brand->loadCats(0));exit;
        $options = array();
        $size = 10;
        $options['where'] .= "and g.typeid = 3  and g.end_time>=".RUN_TIME." and g.start_time<=".RUN_TIME;
        $options['where0'] = 'and g.is_sale=1 and g.status=0 '; //固定条件;
        #品牌筛选
        if ($id > 0) {
            $this->load->model('brand');
            $options['where'] .= " and g.bid" . $this->brand->condArrchild($id);
        }
        #排序
        $order = isset($_REQUEST['order']) ? trim($_REQUEST['order']) : '';
        $sort = isset($_REQUEST['sort']) ? trim($_REQUEST['sort']) : '';
        if($order=='goods_id'){
            $options['order'] = " (case when id={$sort} then 1 ELSE 4 END),g.listorder desc,g.id desc";
        }else{
            $options['order'] = " g.listorder desc,g.id desc";
        }

        $data['list'] = $this->goods->getList($size, $page, 0, $type, $options);

        foreach($data['list'] as $k=>$v){
            $data['list'][$k]['scale'] = round($v['sell']/($v['stock']+$v['sell']),2)*100;
        }
        #异步加载
        if (isset($_GET['load'])) {
            $content = '';
            if ($data['list']) {
                foreach ($data['list'] as $key=>$v) {
                    $this->smarty->assign('m', $v);
                    $this->smarty->assign('k', $key+($page-1)*$size);
                    $content .= $this->smarty->fetch('goods/lbi/kill_list.html');
                }
            }
            echo $content;
            die;
        }
        $this->smarty->assign('type', $time);
        $this->smarty->assign('data', $data);
        $this->smarty->display("goods/brand_kill.html");
    }
    //品牌清仓
    function brand_kill($cid=0,$page = 1) {
        $id = $this->db->getstr("select id from ###_topic where typeid=13 and start_time<=".RUN_TIME." and end_time>=".RUN_TIME,"id");
        if(!$id)exit($this->msg());
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
        $data['list'] = $this->page->hashQuery($sql)->result_array();

        $this->load->model("goods");
        foreach($data['list'] as $k=>$v){
            $data['list'][$k] = $this->goods->getThumb($v);
        }

        $this->smarty->assign('posid', 5);
        #异步加载
        if (isset($_GET['load'])) {
            $content = '';
            if ($data['list']) {
                foreach ($data['list'] as $key=>$v) {
                    $this->smarty->assign('m', $v);
                    $this->smarty->assign('k', $key+($page-1)*$options['size']);
                    $content .= $this->smarty->fetch('goods/lbi/brand_kill.html');
                }
            }
            echo $content;
            die;
        }

        #加载商品分类
        $data['cats'] = $this->topiccat->loadTopicCats($id);
        if ($cid) {
            $data['row'] = isset($data['cats'][$cid]) ? $data['cats'][$cid] : '';
            if (empty($data['row'])) {
                exit($this->msg());
            }
        }
        //列表头部显示二级分类
        if($data['cats']){
            $cats_array = $this->genTree9($data['cats']);
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
        $data['etime'] = kill_etime();
        $this->smarty->assign('data', $data);
        $this->smarty->display("goods/brand_kill.html");
    }

    //新品热拼
    function news($page = 1){

        //获取海淘专题id
        $id = $this->db->getstr("select id from ###_topic where typeid=12 and start_time<=".RUN_TIME." and end_time>=".RUN_TIME,"id");
        if(!$id)$this->msg();
        $where = " t.status=1 AND t.act_id={$id} ";
        $order = " ORDER BY t.listorder asc,sell_num desc";
        $this->load->model('page');
        $this->page->set_vars(array('per' => 10));
        $_GET['page'] = intval($page);
        $sql = "SELECT t.*,g.id,g.name,g.thumb,g.thumbs,g.price,g.team_price,g.team_num,g.sell,g.sales_num,g.sell+g.sales_num as sell_num FROM ###_topic_goods as t LEFT JOIN ###_goods as g on t.goods_id=g.id WHERE {$where} {$order}";
        $data['list'] = $this->page->hashQuery($sql)->result_array();
        $this->load->model("goods");
        foreach($data['list'] as $k=>$v){
            $sql = "select m.photo from ###_member_detail as m left join ###_goods_order_common as c on m.mid=c.mid where c.goods_id={$v['id']} and c.status=1 limit 2";
            $res = $this->db->select($sql);
            $v['join_member'] = array();
            if($res){
               foreach($res as $_k=>$_v){
                   $v['join_member'][] = empty($_v['photo'])?'/common/photo.gif':yunurl($_v['photo']);
               }
            }
            $data['list'][$k] = $this->goods->getThumb($v);
        }
        //echo "<pre>";print_r($data['list']);exit;
        #异步加载
        if (isset($_GET['load'])) {
            $content = '';
            if ($data['list']) {
                foreach ($data['list'] as $key=>$v) {
                    $this->smarty->assign('m', $v);
                    $content .= $this->smarty->fetch('goods/lbi/new_list.html');
                }
            }
            echo $content;
            die;
        }
        //随机获取头像
        $data['user1'] = rand_user(2);
        $data['user2'] = rand_user(2);
        $this->display_before($data);
        $this->smarty->assign('data', $data);
        $this->smarty->display('goods/news.html');
    }

    //话费 流量
    function virtual($page = 1){

        $options = array();
        $size = 10;
        $type = isset($_REQUEST['type']) ? intval($_REQUEST['type']) : 0;
        $data = array();
        $options['where'] = "";

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
        $data['list'] = $this->goods->getList($size, $page, 0, $type, $options);

        #异步加载
        if (isset($_GET['load'])) {
            $content = '';
            if ($data['list']) {
                foreach ($data['list'] as $key=>$v) {
                    $this->smarty->assign('m', $v);
                    $content .= $this->smarty->fetch('goods/lbi/virtual_list.html');
                }
            }
            echo $content;
            die;
        }
        //echo "<pre>";print_r($data);exit;
        $this->smarty->assign('data', $data);
        $this->display_before($data['row']);
        $this->smarty->display('goods/virtual.html');
    }
    function test(){
        $this->smarty->display('goods/test.html');
    }
    //输出远程图片 用于生成图片
    function get_pic(){
        $url = $_GET['picurl'];
        header ( 'Content-Type: image/jpeg' );
        $data = file_get_contents($url);
        echo $data;
    }

    function ajax_team_list($id){
        $this->load->model("team");
        $data['team_list'] = $this->team->getTeamList($id,array("status"=>TEAM_ING));
        $this->smarty->assign('data', $data);
        $content = $this->smarty->fetch('goods/ajax_team_list.html');
        echo $content;exit;
    }

}
