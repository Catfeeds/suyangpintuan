<?php

/**
 * 同城控制器
 */
class city extends Lowxp {

    function __construct() {
        parent::__construct();
        $this->load->model('linkage');
        $this->load->model('linkagecat');
        $this->display_before();
    }

    /*
     * 同城购首页
     */
    function index($cid = 0){

        if(!defined("MID")) exit($this->msg('',array("url"=>"/member/login")));

        $zone = $this->db->getstr("select zone from ###_member where mid=".MID,"zone");
        if($zone){
            $zone_row = $this->linkage->get($zone);
            $data['local_city'] = $zone_row['name'];
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
                    exit($this->msg());
                }
            }

            //列表头部显示二级分类
            if($data['cats']){
                $cats_array = $this->genTree9($data['cats']);
                if($data['row']['parentid']==0 && !isset($_GET['top'])){
                    $data['cat_list'] = $cats_array;
                    $catids = $this->db->getstr("select catid from ###_linkage where id={$zoneid}","catid");
                    $data['cat_isrec'] = $this->db->select("SELECT * FROM ###_linkage_cat WHERE isrec=1 and  id in ({$catids}) and ismenu=1 and FIND_IN_SET('{$cid}',arrparentid) ORDER BY listorder ASC,id ASC");
                }else{
                    if(isset($_GET['top'])){//下级栏目
                        $data['cat_list'] = $this->linkagecat->loadLinkageCatsByCid($zoneid,$cid);
                    }else{//同级栏目
                        $data['cat_list'] = $this->linkagecat->loadLinkageCatsByCid($zoneid,$data['row']['parentid']);
                    }
                    $data['top'] = isset($_GET['top'])?$_GET['top']:$data['row']['parentid'];
                }
                /*if($cid==0 ){
                    $data['cat_list'] = $cats_array;
                }else{
                    if($data['row']['child']==0){//同级栏目
                        $data['cat_list'] = $this->linkagecat->loadLinkageCatsByCid($zoneid,$data['row']['parentid']);
                    }else{//下级栏目
                        $data['cat_list'] = $this->linkagecat->loadLinkageCatsByCid($zoneid,$cid);
                    }
                }*/
            }

            //return  thumb,ad,nav
            $res = $this->linkage->get_nav_by_zone($zone);
            $data = array_merge($data,$res);
        }else{
            exit($this->msg('',array("url"=>"/city/charea")));
        }

        //echo "<pre>";print_r($data);exit;
        $this->smarty->assign('data', $data);
        $this->smarty->assign('zone', $zone);
        $this->smarty->assign('cid', $cid);
        $this->smarty->display('city/index.html');
    }

    /*
    * 获取同城商品
    */
    function getCityGoods($cid=0,$zone=0,$page=1){

        $id = $this->db->getstr("select id from ###_topic where typeid=4 and start_time<=".RUN_TIME." and end_time>=".RUN_TIME,"id");
        if(!$id && $cid==0)return false;

        $where = " AND status=1 ";
        if($id>0)$where.=" AND act_id={$id}";
        $goods_where=' 1 ';

        #商品列表
        $this->load->model("topic");
        $sql = "SELECT goods_id FROM ###_topic_goods WHERE 1 {$where} order by listorder desc,id asc";
        $res_goods = $this->db->select($sql);
        if(!$res_goods)return false;
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

        $sql = "select id,name,thumb,thumbs,price,team_price,team_num,sell,sales_num from ###_goods where {$goods_where} ORDER BY FIELD(`id`,{$goods_ids})";
        $data['list'] = $this->page->hashQuery($sql)->result_array();
        //echo $sql;exit;
        $this->load->model("goods");
        foreach($data['list'] as $k=>$v){
            $data['list'][$k] = $this->goods->getThumb($v);
        }
        //echo "<pre>";print_r($data['ad_list']);exit;

        //专题显示位置
        $this->smarty->assign('posid',2);

        #异步加载
        if (isset($_GET['load'])) {
            $content = '';
            if ($data['list']) {
                foreach ($data['list'] as $key=>$v) {
                    $this->smarty->assign('m', $v);
                    $this->smarty->assign('k', $key+($page-1)*10);
                    $content .= $this->smarty->fetch('goods/lbi/goods_list.html');
                }
            }
            echo $content;
            die;
        }
        $this->smarty->assign('data', $data);
        $this->smarty->display('goods/list.html');

    }

    /*
     * 选择城市
     */
    function charea(){
        $zone = $this->db->getstr("select zone from ###_member where mid=".MID,"zone");
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
        //echo "<pre>";print_r($data);exit;
        $this->smarty->assign('data', $data);
        $this->smarty->display('city/charea.html');

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
            if($list){
                foreach($list as $m){
                    $this->smarty->assign("m",$m);
                    $content .= $this->smarty->fetch("city/lbi/search.html");
                }
            }
        }
        echo $content;exit;
    }
    /*
     * 设置所在地
     */
    function setLocalCity(){
        $id = intval($_GET['id']);
        if($id && defined("MID")){
            $this->db->update("member",array("zone"=>$id),array("mid"=>MID));
            echo 1;exit;
        }else{
            echo 2;exit;
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

}
