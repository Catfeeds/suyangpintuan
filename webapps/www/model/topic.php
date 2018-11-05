<?php
/**
 * Class topic_model
 */
class topic_model extends Lowxp_Model {

	    public $topicType = array(
	        1=>"首页商品",
	        2=>"新人特享",
	        3=>"上新",
	        4=>"9块9特卖",
	        5=>"爱逛街",
	        6=>"海淘",
        );
        public $posidArray = array(
            1=>"首页",
            2=>"同城",
            3=>"海淘",
            4=>"秒杀",
            5=>"清仓",
        );

        /*
        * 获取专题活动列表
        */
        function getTopicList($option = array()){
            
            $page = isset($option['page'])?$option['page']:1;
            
            $where = " 1 ";
            
            if($option['where']){
                $where .= $option['where'];
            }

            $this->load->model('page');

            $_GET['page'] = intval($page);

            if($this->common['page_listrows']){
                $size = (int)$this->common['page_listrows'];
            }else{
                $size = isset($option['size'])?$option['size']:'10';
            }
            $this->page->set_vars(array('per' => $size));

            #数据集

            $sql = "SELECT * FROM ###_topic WHERE {$where} {$option['order']}";

            $list = $this->page->hashQuery($sql,$option['type'] . '/'.$option['typeid'].'/')->result_array();
            
            return $list;
        }


        /*
        * 获取专题活动详情
        */
        function getTopicOne($id){
            $row = $this->db->get("select * from ###_topic where id={$id}");
            return $row;
        }

        /*
        * 修改专题信息
        */
        function save(){
            $post = $_POST['post'];

            if(empty($post['name'])){
                return array('code' => 10001, 'message' => '专题名称不能为空');
            }
            if(empty($post['typeid'])){
                return array('code' => 10002, 'message' => '请选择类型');
            }
            if(empty($post['start_time'])){
                return array('code' => 10003, 'message' => '活动开始时间不能为空');
            }
            if(empty($post['end_time'])){
                return array('code' => 10004, 'message' => '活动结束时间不能为空');
            }

            # 处理缩略图
            if (isset($post['thumb']) && !empty($post['thumb'])) {
                $_arrData = array();
                foreach ($post['thumb']['path'] as $k => $v) {
                    $_arrData[$k]['path'] = $v;
                    $_arrData[$k]['title'] = $post['thumb']['title'][$k];
                }
                $post['thumb'] = json_encode($_arrData);
            }

            # 处理图集
            if (isset($post['thumbs']) && !empty($post['thumbs'])) {
                $_arrData = array();
                foreach ($post['thumbs']['path'] as $k => $v) {
                    $_arrData[$k]['path'] = $v;
                    $_arrData[$k]['title'] = $post['thumbs']['title'][$k];
                }
                $post['thumbs'] = json_encode($_arrData);
            }

            //$post['u_time'] = RUN_TIME;
            $post['apply_stime'] = strtotime($post['apply_stime']);
            $post['apply_etime'] = strtotime($post['apply_etime']);
            $post['start_time'] = strtotime($post['start_time']);
            $post['end_time'] = strtotime($post['end_time']);
            //显示位置
            if(isset($post['posid'])){
                $post['posid'] = join(",",$post['posid']);
            }else{
                $post['posid'] = '';
            }

            if($post['id']>0){
                $res = $this->db->update("topic",$post,array("id"=>$post['id']));
            }else{
                $res = $this->db->insert("topic",$post);
            }
            if($res!==false){
                return array('code' => 0,  'message' => '操作成功');
            }else{
                return array('code' => 10005,  'message' => '操作失败');
            }


        }

        /*
        * 报名专题活动
        */
        function apply_save($post = array()){

            if(empty($post['act_id'])){
                return array('code' => 10001, 'message' => '专题ID不能为空');
            }
            if(empty($post['goods_id'])){
                return array('code' => 10002, 'message' => '商品ID不能为空');
            }

            if($post['id']>0){
                $res = $this->db->update("topic_goods",$post,array("id"=>$post['id']));
            }else{
                $is_res = $this->db->get("select 1 from ###_topic_goods where act_id={$post['act_id']} and goods_id={$post['goods_id']}");
                if($is_res){
                    return array('code' => 10003, 'message' => '该商品已报名');
                }
                $topic = $this->getTopicOne($post['act_id']);
                if(!empty($topic['apply_stime']) && $topic['apply_stime']>RUN_TIME){
                    return array('code' => 10003, 'message' => '报名时间未到');
                }
                if(!empty($topic['apply_etime']) && $topic['apply_etime']<RUN_TIME){
                    return array('code' => 10003, 'message' => '报名时间已过');
                }
                if(!isset($post['sid'])){
                    $post['sid'] = $this->db->getstr("select sid from ###_goods where id={$post['goods_id']}",'sid');
                }
                $post['c_time'] = RUN_TIME;
                $res = $this->db->insert("topic_goods",$post);
            }
            if($res!==false){
                return array('code' => 0,  'message' => '操作成功');
            }else{
                return array('code' => 10005,  'message' => '操作失败');
            }

        }

        /*
        * 获取专题活动列表
        */
        function getApplyList($option = array()){

            $page = isset($option['page'])?$option['page']:1;

            $where = " 1 ";

            if($option['where']){
                $where .= $option['where'];
            }

            $this->load->model('page');

            $_GET['page'] = intval($page);

            if($this->common['page_listrows']){
                $size = (int)$this->common['page_listrows'];
            }else{
                $size = isset($option['size'])?$option['size']:'10';
            }
            $this->page->set_vars(array('per' => $size));

            if(empty($option['order'])){
                $option['order'] = " order by listorder asc,id asc";
            }
            #数据集

            $sql = "SELECT * FROM ###_topic_goods WHERE {$where} {$option['order']}";
            if($option['act_id']){
                $list = $this->page->hashQuery($sql,$option['act_id'].'/')->result_array();
            }else{
                $list = $this->page->hashQuery($sql)->result_array();
            }


            return $list;
        }

        /*
        * 获取报名详情
        */
        function getApplyOne($id){
            $row = $this->db->get("select * from ###_topic_goods where id={$id}");
            return $row;
        }

        /*
        * 获取所有专题列表并缓存
        */
        function getTopicApply($posid=0){
            $talist = "CM_TOPIC_APPLY_LIST_".$posid;
            if(!S($talist) && S($talist)!=1){
                $this->load->model("goods");
                $where = '  type=2 AND start_time<='.RUN_TIME." AND end_time>=".RUN_TIME;
                if($posid>0){
                    $where .= " AND  FIND_IN_SET('{$posid}',posid) ";
                }
                $list = $this->db->select("SELECT id,name,catid FROM ###_topic WHERE {$where} ORDER BY listorder asc limit 20");
                foreach($list as $key=>$val){
                    $val['is_catid'] = !empty($val['catid'])?1:0;
                    unset($val['catid']);
                    $val['apply_list'] = $this->getApplyList(array("where"=>" AND status=1 AND act_id={$val['id']} "));
                    $val['apply_list'] = $this->db->lJoin($val['apply_list'],"goods","id,name,thumb,thumbs,price,team_price,team_num","goods_id","id");
                    foreach($val['apply_list'] as $k=>$v){
                        $val['apply_list'][$k] = $this->goods->getThumb($v);
                    }
                    $list[$key] = $val;
                }
                $list = count($list)>0?$list:1;
                S($talist,$list);
            }else{
                $list = S($talist);
            }
            return $list;
        }


}