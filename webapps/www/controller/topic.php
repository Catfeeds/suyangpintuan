<?php

/**
 * 专题活动控制器
 */
class topic extends Lowxp {

    function __construct() {
        parent::__construct();
        $this->load->model('topic');
        $this->load->model('topiccat');
    }
	
	//专题列表
	function lists($page=1){

        $sort = !empty($_GET['sort']) ? trim($_GET['sort']) : 'id';

        $order = !empty($_GET['sort']) ? trim($_GET['order']) : 'DESC';

        $where = '  AND start_time<='.RUN_TIME." AND end_time>=".RUN_TIME;

        $option['page'] = $page;
        $option['where'] = $where;
        $option['order'] = " ORDER BY $sort $order ";

        $list = $this->topic->getTopicList($option);
        if($list){
            foreach($list as $key=>$val){
                $val['apply_list'] = $this->topic->getApplyList(array("where"=>" AND status=1 AND act_id={$val['id']} "));
                $val['apply_list'] = $this->db->lJoin($val['apply_list'],"goods","id,name,thumb,thumbs,price,team_price,team_num","goods_id","id");
                foreach($val['apply_list'] as $k=>$v){
                    $val['apply_list'][$k] = $this->goods->getThumb($v);
                }
                $list[$key] = $val;
            }
        }

        $topictype = $this->topic->topicType;
        foreach($list as $k=>$v){
            $list[$k]['type_name'] = $topictype[$v['typeid']];
        }
        $data['list'] = $list;
        #异步加载
        if (isset($_GET['load'])) {
            $content = '';
            if ($data['list']) {
                foreach ($data['list'] as $v) {
                    $this->smarty->assign('m', $v);
                    $content .= $this->smarty->fetch('topic/lbi/topic_list.html');
                }
            }
            echo $content;
            die;
        }
        $this->smarty->assign('data', $data);
        $this->smarty->assign('topictype', $topictype);
        $this->smarty->display('topic/topic_list.html');
	}

    //专题商品列表
    function index($id = 0,$cid = 0, $page = 1) {
        $options = array();
        $options['size'] = 10;
        $options['page'] = $page;
        $options['where'] = " AND t.status=1 AND t.act_id={$id} ";
        $type = isset($_REQUEST['type']) ? intval($_REQUEST['type']) : 0;
        $data = array('row' => array(), 'id' => $id, 'type' => $type, 'url' => '');
        #专题信息
        $data['topic'] = $this->topic->getTopicOne($id);
        if(empty($data['topic']) || $data['topic']['start_time']>RUN_TIME || $data['topic']['end_time']<RUN_TIME){
            exit($this->msg());
        }

        #加载商品分类
        $data['cats'] = $this->topiccat->loadTopicCats($id);
        if ($cid) {
            $data['row'] = isset($data['cats'][$cid]) ? $data['cats'][$cid] : '';
            if (empty($data['row'])) {
                exit($this->msg());
            }
        }
        //print_r($data['row']);exit;
        //列表头部显示二级分类
        if($data['cats']){
            $cats_array = $this->genTree9($data['cats']);
            /*if($cid==0 ){
                $data['cat_list'] = $cats_array;

            }else{
                if($data['row']['child']==0){//同级栏目
                    $data['cat_list'] = $this->topiccat->loadTopicCatsByCid($id,$data['row']['parentid']);
                }else{//下级栏目
                    $data['cat_list'] = $this->topiccat->loadTopicCatsByCid($id,$cid);
                }

            }*/
            if($data['row']['parentid']==0 && !isset($_GET['top'])){
                $data['cat_list'] = $cats_array;
            }else{
                if(isset($_GET['top'])){//下级栏目
                    $data['cat_list'] = $this->topiccat->loadTopicCatsByCid($id,$cid);
                }else{//同级栏目
                    $data['cat_list'] = $this->topiccat->loadTopicCatsByCid($id,$data['row']['parentid']);
                }
                $data['top'] = isset($_GET['top'])?$_GET['top']:$data['row']['parentid'];
            }
            $data['cat_isrec'] = $this->topiccat->loadTopicCatsByCid($id,$cid);
        }

        if ($cid) {
            $this->load->model('topiccat');
            $options['where'] .= " AND t.cid" . $this->topiccat->condArrchild($cid);
        }

        #排序
        $order = isset($_REQUEST['order']) ? trim($_REQUEST['order']) : 'listorder';
        $sort = isset($_REQUEST['sort']) ? trim($_REQUEST['sort']) : 'DESC';
        $orderby = '';
        if($order=='goods_id'){
            $options['order'] = " order by (case when t.goods_id={$sort} then 1 ELSE 4 END), t.listorder desc,sell_num desc";
        }else{
            $options['order'] = "order by t.listorder asc,sell_num desc";
        }

        #商品列表
        $this->load->model('page');
        $_GET['page'] = intval($page);
        $this->page->set_vars(array('per' => $options['size']));
        $sql = "SELECT t.*,g.id,g.name,g.thumb,g.thumbs,g.price,g.team_price,g.team_num,g.sell,g.sales_num,g.sell+g.sales_num as sell_num FROM ###_topic_goods as t LEFT JOIN ###_goods as g on t.goods_id=g.id WHERE 1 {$options['where']} {$options['order']}";
        $data['list'] = $this->page->hashQuery($sql)->result_array();
        $this->load->model("goods");
        foreach($data['list'] as $k=>$v){
            $data['list'][$k] = $this->goods->getThumb($v);
        }

        #异步加载
        if (isset($_GET['load'])) {
            $content = '';
            if ($data['list']) {                
                foreach ($data['list'] as $v) {
                    $this->smarty->assign('m', $v);
                    $content .= $this->smarty->fetch('topic/lbi/list.html');
                }
            }
            echo $content;
            die;
        }
        ##echo "<pre>";print_r($data['list']);exit;
        $this->smarty->assign('data', $data);
        $data['row']['title'] = $data['topic']['name'];
        $this->display_before($data['row']);      
        $this->smarty->display('topic/list.html');
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

    function getTopicGoods($typeid=0,$page=1){

        $id = $this->db->getstr("select id from ###_topic where typeid={$typeid} and start_time<=".RUN_TIME." and end_time>=".RUN_TIME,"id");
        if(!$id)return false;
        $options = array();
        $options['size'] = 10;
        #商品列表
        $where = " t.status=1 AND t.act_id={$id} ";
        $order = " ORDER BY t.listorder asc,sell_num desc";
        $this->load->model('page');
        $this->page->set_vars(array('per' => $options['size']));
        $_GET['page'] = intval($page);
        $sql = "SELECT t.*,g.id,g.name,g.thumb,g.thumbs,g.price,g.team_price,g.team_num,g.sell,g.sales_num,g.sell+g.sales_num as sell_num FROM ###_topic_goods as t LEFT JOIN ###_goods as g on t.goods_id=g.id WHERE {$where} {$order}";
        $data['list'] = $this->page->hashQuery($sql)->result_array();

        $this->load->model("goods");
        foreach($data['list'] as $k=>$v){
            if(empty($v['id'])){
               unset($data['list'][$k]);continue;
            }
            $data['list'][$k] = $this->goods->getThumb($v);
        }
        $data['list'] = array_values($data['list']);
        //echo "<pre>";print_r($data['list']);exit;

        switch ( $typeid ) {
            case  2 :
                $posid=1;break;
            case  3 :
                $posid=3;break;
            case  4 :
                $posid=2;break;
            case  5 :
                $posid=4;break;
        }
        $this->smarty->assign('posid',$posid);
        #异步加载
        if (isset($_GET['load'])) {
            $content = '';
            if ($data['list']) {
                foreach ($data['list'] as $key=>$v) {
                    $this->smarty->assign('m', $v);
                    $this->smarty->assign('k', $key+($page-1)*$options['size']);
                    $content .= $this->smarty->fetch('goods/lbi/goods_list.html');
                }
            }
            echo $content;
            die;
        }
        $this->smarty->assign('data', $data);
        $this->smarty->display('goods/list.html');

    }
    
}
