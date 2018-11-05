<?php
/**
 * Class bargain_model
 */
class bargain_model extends Lowxp_Model {

        public $pow = 0;
	    public $baseTable = "###_bargain";
	    public $logTable  = "###_bargain_log";
	    public $helpTable = "###_bargain_log_help";

        function __construct() {


            if(file_exists(AppDir.'config/version_bargain.php')){
                include(AppDir.'config/version_bargain.php');
            }
            if(defined("Version_bargain")){
               $this->pow = 1;
            }

            //批量处理
            $queueDealCacheName = 'BARGAIN_ACTION';
            if (!S($queueDealCacheName)) {
                S($queueDealCacheName, 1, 180);
                $this->run();
            }
        }
        //修改过期砍价状态
        function run(){
            $this->db->update($this->logTable,array("status"=>2)," status=0 and e_time<".RUN_TIME);
        }

        /*
        * 获取砍价活动列表
        */
        function getList($option = array()){
            
            $page = isset($option['page'])?$option['page']:1;
            
            $where = " 1 ";
            
            if($option['where']){
                $where .= $option['where'];
            }

            $this->load->model('page');

            $_GET['page'] = intval($page);

            if(isset($option['size'])){
                $size = isset($option['size'])?$option['size']:'10';
            }else{
                $size = isset($this->common['page_listrows'])?(int)$this->common['page_listrows']:10;
            }
            $this->page->set_vars(array('per' => $size));

            if(!isset($option['order'])){
                $option['order'] = " listorder desc,id desc ";
            }
            #数据集

            $sql = "SELECT * FROM {$this->baseTable} WHERE {$where} ORDER BY {$option['order']}";

            $list = $this->page->hashQuery($sql)->result_array();
            $list = $this->db->lJoin($list,"goods","id,name,thumbs,sp_val","goods_id","id","goods_");
            if($list){
                foreach($list as $k=>$v){
                    $thumb = json_decode($v['goods_thumbs'],true);
                    $list[$k]['img_cover'] = yunurl($thumb[0]['path']);
                }
            }
            return $list;
        }


        /*
        * 获取砍价活动详情
        */
        function getOne($id,$field="*"){
            $row = $this->db->get("select {$field} from {$this->baseTable} where id={$id}");
            if($row){
                $this->load->model("goods");
                $row['goods'] = $this->goods->get($row['goods_id']);
            }
            return $row;
        }

        /*
        * 修改砍价
        */
        function save($post = array()){

            if(empty($post['goods_id'])){
                return array('code' => 10001, 'message' => '请选择商品');
            }
            if(empty($post['price'])){
                return array('code' => 10002, 'message' => '原价不能为空');
            }
            if(empty($post['number'])){
                return array('code' => 10002, 'message' => '砍价次数不能为空');
            }
            if(empty($post['term'])){
                return array('code' => 10002, 'message' => '期限不能为空');
            }
            if(empty($post['e_time'])){
                return array('code' => 10002, 'message' => '活动结束时间不能为空');
            }
            $post['e_time'] = strtotime($post['e_time']);

            if($post['id']>0){
                $res = $this->db->update($this->baseTable,$post,array("id"=>$post['id']));
            }else{
                $is_res = $this->db->get("select 1 from {$this->baseTable} where goods_id={$post['goods_id']} ");
                if($is_res){
                    return array('code' => 10003, 'message' => '该商品已报名');
                }
                $post['c_time'] = RUN_TIME;
                $res = $this->db->insert($this->baseTable,$post);
            }
            if($res!==false){
                return array('code' => 0,  'message' => '操作成功');
            }else{
                return array('code' => 10005,  'message' => '操作失败');
            }

        }

        /*
        * 获取砍价记录详情
        */
        function getLogOne($id,$field="*"){
            $row = $this->db->get("select {$field} from {$this->logTable} where id={$id}");
            if($row){
               $row['end_time'] = $row['e_time']-RUN_TIME;
               if($row['end_time']<0)$row['end_time']=0;
            }
            return $row;
        }


        /*
        * 获取砍价记录列表
        */
        function getLogList($option = array()){

            $page = isset($option['page'])?$option['page']:1;

            $where = " 1 ";

            if($option['where']){
                $where .= $option['where'];
            }

            $this->load->model('page');

            $_GET['page'] = intval($page);

            if(isset($option['size'])){
                $size = isset($option['size'])?$option['size']:'10';
            }else{
                $size = isset($this->common['page_listrows'])?(int)$this->common['page_listrows']:10;
            }
            $this->page->set_vars(array('per' => $size));

            if(!isset($option['order'])){
                $option['order'] = " id desc ";
            }
            #数据集

            $sql = "SELECT * FROM {$this->logTable} WHERE {$where} ORDER BY {$option['order']}";

            $list = $this->page->hashQuery($sql)->result_array();
            $list = $this->db->lJoin($list,"goods","id,name,thumbs,sp_val","goods_id","id","goods_");
            $list = $this->db->lJoin($list,"member","mid,username","mid","mid");
            if($list){
                foreach($list as $k=>$v){

                    $list[$k]['end_time'] = $list[$k]['e_time']-RUN_TIME;
                    if($list[$k]['end_time']<0)$list[$k]['end_time']=0;

                    $thumb = json_decode($v['goods_thumbs'],true);
                    $list[$k]['img_cover'] = yunurl($thumb[0]['path']);
                }
            }
            return $list;
        }




        //生成砍价
        function bargain_log_save($post = array()){

            if(empty($post['bargain_id'])){
                return array('code' => 10001, 'message' => '砍价ID不能为空');
            }
            $row = $this->getOne($post['bargain_id']);
            if(empty($row) || $row['status']==0){
                return array('code' => 10001, 'message' => '砍价不存在');
            }
            if($row['e_time']<RUN_TIME){
                return array('code' => 10001, 'message' => '该砍价活动已结束');
            }

            $res_has = $this->db->getstr("select id from {$this->logTable} where bargain_id={$post['bargain_id']} and e_time>".RUN_TIME." and status=0 and mid=".MID);
            if($res_has){
                return array('code' => 10002, 'id'=>$res_has, 'message' => '当前有未完成的活动');
            }

            //扣除活动库存
            $is_stock = $this->db->update($this->baseTable,'stock=stock-1,sell=sell+1','stock>=1 and id='.$post['bargain_id']);
            if($is_stock===false){
                return array('code' => 10001,  'message' => '活动库存不足');
            }

            $data['bargain_id'] = $post['bargain_id'];//砍价id
            $data['goods_id'] = $row['goods_id'];//商品id
            $data['spec'] = $post['spec'];//商品属性
            $data['mid'] = MID;
            $data['price'] = $row['price'];//砍价原价
            $data['last_price'] = $row['last_price'];//砍价底价
            $data['number'] = $row['number'];//砍价总需人次
            $data['sid'] = $row['sid'];
            $data['e_time'] = RUN_TIME+$row['term']*86400;//截止时间
            $data['c_time'] = RUN_TIME;
            $log_id = $this->db->insert($this->logTable,$data);
            if($log_id>0){
                if($row['is_self']==1){
                    $this->bargain_help($log_id,$type=1);
                }
                return array('code' => 0,  'message' => '砍价成功','id'=>$log_id);
            }else{
                return array('code' => 10001,  'message' => '砍价失败');
            }

        }

        //帮忙砍价
        function bargain_help($log_id=0,$type=0){

            if(empty($log_id)){
                return array('code' => 10001, 'message' => 'ID不能为空');
            }

            //每个砍价只能帮一次
            $res_has = $this->db->getstr("select 1 from {$this->helpTable} where mid=".MID." and log_id={$log_id}");
            if($res_has){
                return array('code' => 10001, 'message' => '您已帮忙砍价过');
            }

            //每个会员一天可以帮忙砍价次数
            if(C('bargain_times')>0){
                $today = strtotime(date("Y-m-d",RUN_TIME));
                $times = $this->db->getstr("select count(*) as num from {$this->helpTable} where mid=".MID." and type=0 and c_time>{$today}");
                if($times>=C('bargain_times')){
                    return array('code' => 10001, 'message' => '您已今天砍价次数用完');
                }
            }


            $row = $this->getLogOne($log_id);
            $total = $row['price']-$row['last_price']-$row['bargain_price'];
            $num = $row['number']-$row['number_yes'];
            $status = $num==1?1:0;

            if($total<=0){
                return array('code' => 10001, 'message' => '该商品已被砍到底价啦');
            }
            if($row['e_time']<RUN_TIME){
                return array('code' => 10001, 'message' => '该砍价已过期');
            }
            $e_time = $this->db->getstr("select e_time from {$this->baseTable} where id={$row['bargain_id']} and status=1","e_time");
            if($e_time<RUN_TIME){
                return array('code' => 10001, 'message' => '该砍价活动已结束');
            }

            $money = $this->bargain_money($total,$num);
            $res = $this->db->update($this->logTable,"number_yes = number_yes+1,bargain_price=bargain_price+{$money},status={$status}"," id={$log_id} and number_yes<number");
            if($res!==false){
                $data['mid'] = MID;
                $data['log_id'] = $log_id;
                $data['type'] = $type;
                $data['money'] = $money;
                $data['c_time'] = RUN_TIME;
                $this->db->insert($this->helpTable,$data);

                //砍价成功后发送通知
                if($status==1){
                    $goods_name = $this->db->getstr("select name from ###_goods where id={$row['goods_id']}","name");
                    // 微信模版消息 refund_note 退款结果通知
                    // wxtemplate_action start
                    $msgParams = array(
                        "url" => note_url("/bargain/show/".$log_id),
                        "keyword1"=>$goods_name,
                        "keyword2"=>$row['last_price']."元",
                    );
                    $wxmsg[] = array(0=>$row['mid'],1=>"bargain_succ",2=>$msgParams);
                    $this->load->model('wxtemplate');
                    $this->wxtemplate->inQueueMany($wxmsg);
                    // wxtemplate_action end

                }

                return array('code' => 0, 'message' => '砍价成功','money'=>$money,'over_money'=>$total-$money);
            }else{
                return array('code' => 10001, 'message' => '砍价失败');
            }

        }


        //砍价金额
        function bargain_money($total,$num){
            if($num>1){
                $min=0.01;
                $safe_total = ($total  - ($num-1) * $min)/($num-1);//随机安全上限
                $total = round(mt_rand($min * 100, $safe_total * 100) / 100,2);
                $total = sprintf("%.2f", $total);   //砍掉的金额
            }
            return $total;
        }


        /*
        * 帮忙砍价记录列表
        */
        function getHelpLogList($log_id=0){
            $where = " log_id={$log_id} ";
            $option['order'] = " id desc ";
            $list = $this->db->select("SELECT * FROM {$this->helpTable} WHERE {$where} ORDER BY {$option['order']}");
            $list = $this->db->lJoin($list,"member","mid,username","mid","mid");
            return $list;
        }

}