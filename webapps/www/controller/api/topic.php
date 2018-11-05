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
	function lists(){

        $page = !empty($_GET['page']) ? trim($_GET['page']) : '1';

        $sort = !empty($_GET['sort']) ? trim($_GET['sort']) : 'id';

        $order = !empty($_GET['sort']) ? trim($_GET['order']) : 'DESC';

        $where = '  AND start_time<='.RUN_TIME." AND end_time>=".RUN_TIME;

        $option['page'] = $page;
        $option['where'] = $where;
        $option['order'] = " ORDER BY $sort $order ";
        $list = $this->topic->getTopicList($option);
        $data['list_total'] = (int)$this->page->pages['total'];
        if($list){
            foreach($list as $key=>$val){
                $temp = array();
                $temp['id'] = $val['id'];
                $temp['name'] = $val['name'];
                $temp['apply_list'] = $this->topic->getApplyList(array("where"=>" AND status=1 AND act_id={$val['id']} "));
                $temp['apply_list'] = $this->db->lJoin($temp['apply_list'],"goods","id,name,thumbs,price,team_price,team_num","goods_id","id");
                foreach($temp['apply_list'] as $k=>$v){
                    if(empty($v['name'])){
                        unset($v);continue;
                    }
                    $thumbs = json_decode($v['thumbs'],true);
                    $v['img_src'] = yunurl($thumbs[0]['path']);
                    $v['img_cover'] = yunurl($thumbs[0]['path'],300);
                    $temp['apply_list'][$k] = $v;
                }
                $list[$key] = $temp;
            }
        }


        $data['list'] = $list;

        $this->api_result(array('data'=>$data));
	}

    //专题商品列表
    function index(){
        $id = !empty($_GET['id']) ? trim($_GET['id']) : '0';
        $cid = !empty($_GET['cid']) ? trim($_GET['cid']) : '0';
        $page = !empty($_GET['page']) ? trim($_GET['page']) : '1';

        $options = array();
        $options['size'] = 10;
        $options['page'] = $page;
        $options['where'] = " AND t.status=1 AND t.act_id={$id} ";
        $type = isset($_REQUEST['type']) ? intval($_REQUEST['type']) : 0;
        $data = array('row' => array(), 'id' => $id, 'type' => $type);
        #专题信息
        $data['topic'] = $this->topic->getTopicOne($id);
        if(empty($data['topic']) || $data['topic']['start_time']>RUN_TIME || $data['topic']['end_time']<RUN_TIME){
            $this->api_result(array('flag' => false, 'msg' => '没有对应商品', 'code' => 100002));
        }
        if($data['topic']){
            $thumbs = json_decode($data['topic']['thumbs'],true);
            if($thumbs){
                foreach($thumbs as $k=>$v){
                    $tmp[$k] = yunurl($v['path']);
                    $data['topic']['thumbs'] = $tmp;
                }
            }
            if(empty($data['topic']['thumb']))$data['topic']['thumb'] = null;
            if(empty($data['topic']['thumbs']))$data['topic']['thumbs'] = null;
        }


        #加载商品分类
        $data['cats'] = $this->topiccat->loadTopicCats($id);
        if ($cid) {
            $data['row'] = isset($data['cats'][$cid]) ? $data['cats'][$cid] : '';
            if (empty($data['row'])) {
                $this->api_result(array('flag' => false, 'msg' => '没有对应商品分类', 'code' => 100002));
            }
        }
        //print_r($data['row']);exit;
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
            $data['cat_list'] = formatCat(array_values($data['cat_list']));
        }
        unset($data['row']);
        unset($data['cats']);

        if ($cid) {
            $this->load->model('topiccat');
            $options['where'] .= " AND t.cid" . $this->topiccat->condArrchild($cid);
        }

        #排序
        $order = isset($_REQUEST['order']) ? trim($_REQUEST['order']) : 'listorder';
        $sort = isset($_REQUEST['sort']) ? trim($_REQUEST['sort']) : 'DESC';
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
            $v['sell'] = formatNum($v['sell']+$v['sales_num']);
            $data['list'][$k] = $this->goods->getThumb($v);
        }

        $data['list_total'] = (int)$this->page->pages['total'];
        $this->api_result(array('data'=>$data));
    }


    function getTopicGoods(){

        $typeid = !empty($_GET['typeid']) ? trim($_GET['typeid']) : '0';
        $page = !empty($_GET['page']) ? trim($_GET['page']) : '1';
        $this->load->model('page');
        $this->page->set_vars(array('per' => 10));
        $_GET['page'] = intval($page);

        if($typeid==2 && C('index_goods')==0){//首页商品 从热销商品筛选
            $where = "1 and g.is_hot=1 and g.is_sale=1 and g.typeid in (0,1,2,4,8,9,10,11) ";
            $order = " ORDER BY g.listorder asc,g.id desc";
            $sql = "SELECT g.id,g.name,g.thumb,g.thumbs,g.price,g.team_price,g.team_num,g.sell,g.sales_num,g.sell+g.sales_num as sell_num,g.typeid FROM  ###_goods as g WHERE {$where} {$order}";
        }else{
            $id = $this->db->getstr("select id from ###_topic where typeid={$typeid} and start_time<=".RUN_TIME." and end_time>=".RUN_TIME,"id");
            if(!$id)$this->api_result(array('flag' => false, 'msg' => '没有对应商品', 'code' => 100002));
            $where = " t.status=1 AND t.act_id={$id} ";
            $order = " ORDER BY t.listorder asc,sell_num desc";
            $sql = "SELECT t.*,g.id,g.name,g.thumb,g.thumbs,g.price,g.team_price,g.team_num,g.sell,g.sales_num,g.sell+g.sales_num as sell_num,g.typeid FROM ###_topic_goods as t LEFT JOIN ###_goods as g on t.goods_id=g.id WHERE {$where} {$order}";
        }
        $data['list'] = $this->page->hashQuery($sql)->result_array();
        $data['list_total'] = (int)$this->page->pages['total'];
        $this->load->model("goods");
        foreach($data['list'] as $k=>$v){
            $data['list'][$k]['sell'] = formatNum($v['sell']+$v['sales_num']);
            $data['list'][$k]['type_name'] = goods_typeid($v['typeid']);
            $data['list'][$k]['discount_price'] = discount($v['price'], $v['team_price']);
            //$data['list'][$k] = $this->goods->getThumb($v);
            $thumb = json_decode($v['thumb'],true);
            $data['list'][$k]['thumb'] = $data['list'][$k]['img_src'] = yunurl($thumb[0]['path']);
        }
        $data['list'] = count($data['list'])>0?$data['list']:null;


        //echo "<pre>";print_r($data['ad_list']);exit;

        $this->api_result(array('data'=>$data));
    }
    
}
