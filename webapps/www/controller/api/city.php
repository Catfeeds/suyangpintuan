<?php

/**
 * 同城控制器
 */
class city extends Lowxp {

    function __construct() {
        parent::__construct();
        $this->load->model('linkage');
        $this->load->model('linkagecat');
    }

    /*
     * 同城购首页
     */
    function index(){
        $cid = isset($_REQUEST['cid'])?$_REQUEST['cid']:0;

        if(!defined("MID"))$this->api_result(array('flag' => false, 'msg' => '请先登录！', 'code' => 400001));

        $zone = $this->db->getstr("select zone from ###_member where mid=".MID,"zone");
        if($zone){
            $zone_row = $this->linkage->get($zone);
            if(substr_count($zone_row['arrparentid'],',')>2){
                $zone_array = explode(",",$zone_row['arrparentid']);
                $zoneid = $zone_array[2];
            }else{
                $zoneid = $zone;
            }
            #加载商品分类
            $data['cats'] = $this->linkagecat->loadLinkageCats($zoneid);
            if ($cid) {
                $data['row'] = isset($data['cats'][$cid]) ? $data['cats'][$cid] : '';
                if (empty($data['row'])) {
                    $this->api_result(array('flag' => false, 'msg' => '该分类不存在！', 'code' => 100003));
                }
            }

            //列表头部显示二级分类
            if($data['cats']){
                $cats_array = genTop($data['cats']);
                if($data['row']['parentid']==0 && (!isset($_GET['top']) || $_GET['top']==0)){
                    $data['cat_list'] = $cats_array;
                    $catids = $this->db->getstr("select catid from ###_linkage where id={$zoneid}","catid");
                    $data['cat_isrec'] = $this->db->select("SELECT id,catname,thumb FROM ###_linkage_cat WHERE isrec=1 and  id in ({$catids}) and ismenu=1 and FIND_IN_SET('{$cid}',arrparentid) ORDER BY listorder ASC,id ASC");
                    if($data['cat_isrec']){
                        $data['cat_isrec'] = api_imgurl($data['cat_isrec'],array("thumb"));
                    }else{
                        $data['cat_isrec'] = null;
                    }
                    $data['top'] = 0;
                }else{
                    if(isset($_GET['top']) && $_GET['top']>0){//下级栏目
                        $data['cat_list'] = $this->linkagecat->loadLinkageCatsByCid($zoneid,$cid);
                    }else{//同级栏目
                        $data['cat_list'] = $this->linkagecat->loadLinkageCatsByCid($zoneid,$data['row']['parentid']);
                    }
                    if(isset($_GET['top']) && $_GET['top']>0 ){
                        $data['top'] = (int)$_GET['top'];
                    }else{
                        $data['top'] = (int)$data['row']['parentid'];
                    }

                    $data['cat_list'] = formatCat($data['cat_list']);
                    $data['cat_isrec'] = null;
                }

                /*$cats_array = $this->genTree9($data['cats']);
                if($cid==0 ){
                    $data['cat_list'] = $cats_array;
                }else{
                    if($data['row']['child']==0){//同级栏目
                        $data['cat_list'] = $this->linkagecat->loadLinkageCatsByCid($zoneid,$data['row']['parentid']);
                    }else{//下级栏目
                        $data['cat_list'] = $this->linkagecat->loadLinkageCatsByCid($zoneid,$cid);
                    }
                }
                $data['cat_list'] = array_values($data['cat_list']);*/
            }
            $data['cat_list'] = formatCat($data['cat_list']);
            //return  thumb,ad,nav
            if($cid==0){
                $res = $this->linkage->get_nav_by_zone($zone);
            }else{
                $res = array("thumb"=>null,"ad"=>null,"nav"=>null);
            }

            $data = array_merge($data,$res);
        }else{
            $this->api_result(array('flag' => false, 'msg' => '请先选择城市！', 'code' => 100004));
        }
        unset($data['cats']);
        $data['zone'] = $zone;
        $data['area'] = $this->db->getstr("SELECT name FROM ###_linkage WHERE id = '$zone'");
        $data['cid'] = $cid;
        $this->api_result(array("data"=>$data));
    }

    /*
    * 获取同城商品
    */
    function getCityGoods(){

        $cid = !empty($_GET['cid']) ? trim($_GET['cid']) : '0';
        $zone = !empty($_GET['zone']) ? trim($_GET['zone']) : '0';
        $page = !empty($_GET['page']) ? trim($_GET['page']) : '1';


        $id = $this->db->getstr("select id from ###_topic where typeid=4 and start_time<=".RUN_TIME." and end_time>=".RUN_TIME,"id");
        if(!$id  && $cid==0)$this->api_result(array('data' => null));

        $where = " AND status=1 AND act_id={$id} ";
        $goods_where=' 1 ';

        #商品列表
        $this->load->model("topic");
        $sql = "SELECT goods_id FROM ###_topic_goods WHERE 1 {$where} order by listorder desc,id asc";
        $res_goods = $this->db->select($sql);
        $goods_ids = join(",",array_column($res_goods,"goods_id"));
        #判断地区
        if($zone){
            $this->load->model("linkage");
            $res_zone = $this->db->select("select id from ###_linkage where parentid={$zone}");
            if($res_zone){
                $zone_ids = join(',',array_column($res_zone,"id")).",".$zone;
            }else{
                $zone_ids = $zone;
            }
            $goods_where .= " and areaid in ({$zone_ids})";
        }else{
            $goods_where .= " and 1=2 ";
        }
        #刷选商品id
        if($goods_ids && $cid==0){
            $goods_where .= " and id in ({$goods_ids})";
        }else{
            //$goods_where .= " and 1=2 ";
        }
        #同城分类
        if ($cid) {
            $this->load->model('linkagecat');
            $goods_where .= " AND aid" . $this->linkagecat->condArrchild($cid);
        }

        $this->load->model('page');
        $_GET['page'] = $page;
        $this->page->set_vars(array('per' => 10));
        if($goods_ids){
            $sql = "select id,name,thumb,price,team_price,team_num,sell,sales_num,typeid from ###_goods where {$goods_where} ORDER BY FIELD(`id`,{$goods_ids})";
        }else{
            $sql = "select id,name,thumb,price,team_price,team_num,sell,sales_num,typeid from ###_goods where {$goods_where}";
        }
        $data['list'] = $this->page->hashQuery($sql)->result_array();
        foreach($data['list'] as $k=>$v){
            if($v['thumb']){
                $thumb = json_decode($v['thumb'],true);
                $v['thumb'] = yunurl($thumb[0]['path']);
            }
            $v['sell'] = formatNum($v['sell']+$v['sales_num']);
            $v['discount_price'] = discount($v['price'], $v['team_price']);
            unset($v['sales_num']);
            $v['type_name'] = "同城";
            $data['list'][$k] = $v;
        }
        $data['list_total'] = (int)$this->page->pages['total'];
        $this->api_result(array('data' => $data));
    }

    /*
     * 选择城市
     */
    function charea(){
        $zone = $this->db->getstr("select zone from ###_member where mid=".MID,"zone");
        $data['local_city'] = null;
        if($zone){
            $data['local_city'] = $this->db->getstr("select name from ###_linkage where id={$zone}","name");
        }
        $sql = "select id,name from ###_linkage where status=1 and is_hot=1";
        $data['hot_list'] = $this->db->select($sql);

        //开通城市列表
        $sql = "select id,name,pin from ###_linkage where status=1";
        $data['list'] = $this->db->select($sql);
        foreach($data['list'] as $k=>$v){
            $data['pin_list'][$v['pin']][] = $v;
        }
        ksort($data['pin_list']);
        $data['pin'] = array_keys($data['pin_list']);
        unset($data['list']);
        $this->api_result(array("data"=>$data));
    }
    /*
     * 搜索城市
     */
    function ajaxGetSearch(){
        $keyword = trim($_GET['keyword']);
        $content = '';
        if($keyword){
            if(preg_replace("/[a-zA-Z]/","",$keyword)==''){
                $sql = "select * from ###_linkage where status=1 and pin='".strtoupper($keyword)."'";
            }else{
                $sql = "select * from ###_linkage where status=1 and name='{$keyword}'";
            }
            $list = $this->db->select($sql);
            $this->api_result(array("data"=>$list));
        }
        $this->api_result(array("msg"=>'请输入城市信息','code'=>10001));
    }
    /*
     * 设置所在地
     */
    function setLocalCity(){
        $id = isset($_REQUEST['id'])?intval($_REQUEST['id']):0;
        if(!defined("MID")){
            $this->api_result(array('flag'=>false,'msg'=>'请先登录！','code'=>400001));
        }
        if($id==0){
            $this->api_result(array('flag'=>false,'msg'=>'请选择地区！','code'=>100002));
        }

        if($id && defined("MID")){
            $this->db->update("member",array("zone"=>$id),array("mid"=>MID));
            $city = $this->db->getstr("select name from ###_linkage where id={$id}","name");
            $id = (string)$id;
            $this->api_result(array('msg'=>'设置成功','data'=>array('zone'=>$id,'city'=>$city)));
        }else{
            $this->api_result(array('flag'=>false,'msg'=>'设置失败','code'=>100003));
        }
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
    /*
     * 定位城市
     */
    function posCity(){
        $city = trim($_REQUEST['city']);
        if(empty($city)){
            $this->api_result(array('flag'=>false,'msg'=>'城市不能为空','code'=>100001));
        }
        $city = str_replace("市",'',$city);
        $zone = $this->linkage->strToZone($city);
        if($zone){
            $status = $this->db->getstr("select status from ###_linkage where id={$zone}","status");
            if($status==1){
                $this->db->update("member",array("zone"=>$zone),array("mid"=>MID));
                $data['zone'] = $zone;
                $data['city'] = $city;
                $this->api_result(array('data'=>$data));
            }else{
                $this->api_result(array('flag'=>false,'msg'=>'您所在城市还未开通','code'=>100002));
            }

        }else{
            $this->api_result(array('flag'=>false,'msg'=>'您所在城市不存在，请联系管理员添加','code'=>100003));
        }
    }
}
