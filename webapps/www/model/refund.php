<?php
/**
 * Class refund_model
 */
class refund_model extends Lowxp_Model {

	// 奖品类别
	public $reasonTypes = array(
		99 => '不能按时发货',
		98 => '认为是假货',
		97 => '保质期不符',
		96 => '商品破损、有污渍',
		95 => '效果不好不喜欢',
		90=> '其他',
	);
        
        function getList($option = array()){
            
            $page = isset($option['page'])?$option['page']:1;
            
            $where = " 1 ";
            
            if($option['type']>0){
                $where .= " AND type={$option['type']} ";
            }
            
            
            if($option['mid']>0){
                $where .= " AND mid={$option['mid']} ";
            }
            
            #分页

            $this->load->model('page');

            $_GET['page'] = intval($page);

            $this->page->set_vars(array('per' => (int) C('page_listrows')));

            if (isset($option['status']) && !empty($option['status'])) {

                    $is_show = intval($option['status']) == 99 ? 0 : 1;

                    $where .= "AND status=$status ";

            }

            $sort = !empty($_GET['sort']) ? trim($_GET['sort']) : 'id';

            $order = !empty($_GET['sort']) ? trim($_GET['order']) : 'DESC';

            #数据集

            $sql = "SELECT * FROM ###_refund WHERE $where ORDER BY $sort $order";

            $list = $this->page->hashQuery($sql)->result_array();

            $list = $this->db->lJoin($list,"member","mid,username,mobile","mid","mid");

            $list = $this->db->lJoin($list,"goods_order","id,order_sn","order_id","id","order_");

            foreach($list as $k=>$v){
                $list[$k]['reason_name'] = $reasonType[$v['reason_id']];
            }
            
            return $list;
        }
        
        function save(){
            $post = $_POST['post'];
            if(empty($post['id'])){
                return array('code' => 10001, 'message' => 'ID不能为空');
            }
            
            $row = $this->db->get("select * from ###_refund where id={$post['id']}");
            
            if($row==false){
                return array('code' => 10001, 'message' => '该申请不存在');
            }
            if($row['type']==2 && $post['status']==10 && empty($post['address_id'])){
                return array('code' => 10001, 'message' => '请选择收货地址');
            }
            if(in_array(Edition,array("enter_one","one")) && $post['status']==10 || $post['status_shipping']==10){
                $post['status_refund']=10;
            }
            if($row['complain_status']!=2 && $post['complain_status']==2){
                $post['status_refund']=$post['status']=$post['status_shipping']=10;
            }
            $post['u_time'] = RUN_TIME;
            $res = $this->db->update("refund", $post, array("id"=>$post['id']));
            if($res === false){
                return array('code' => 10001, 'type' => 'update', 'message' => '更新失败');
            }else{
                if($post['status_refund']==10 || $post['complain_status']==2){
                    $this->db->update("goods_order", array("status_order"=>2), array("id"=>$post['order_id']));
                    //添加退款申请
                    $payment_list = $this->db->select("select pay_id,pay_code from ###_payment where enabled=1");
                    foreach($payment_list as $k=>$v){
                        $payment[$v['pay_id']] = strpos($v['pay_code'],"alipay")!==false?"alipay":$v['pay_code'];
                    }
                    $order_info = $this->db->get("select extension_code,pay_id,order_sn from ###_goods_order where id={$post['order_id']}");
                    $type = $order_info['extension_code'];
                    if($type==CART_STEP){
                        $out_res = $this->db->select("select out_trade_no,trade_no,order_amount from ###_pay_log where order_id={$post['order_id']} and is_paid=1");
                        foreach($out_res as $k=>$v){
                            $temp['order_id'] = $post['order_id'];
                            $temp['order_amount'] = $v['order_amount'];
                            $temp['out_trade_no'] = $v['out_trade_no'];
                            $temp['trade_no'] = $v['trade_no'];
                            $temp['payment'] = $payment[$order_info['pay_id']];
                            $this->addRefundLog($temp);
                        }
                    }else{
                        $pay_info = $this->db->get("select out_trade_no,trade_no from ###_pay_log where order_id={$post['order_id']}");
                        $temp = array();
                        $temp['order_id'] = $post['order_id'];
                        $temp['order_amount'] = $row['refund_amount'];
                        $temp['out_trade_no'] = $pay_info['out_trade_no'];
                        $temp['trade_no'] = $pay_info['trade_no'];
                        $temp['payment'] = $payment[$order_info['pay_id']];
                        $this->addRefundLog($temp);
                    }

                    //退款扣除商家余额
                    $row_bill = $this->db->get("select * from ###_business_bills where order_sn='{$order_info['order_sn']}' and stage='bill'");
                    if($row_bill){
                        $bill_log = array();
                        $bill_log['sid'] = $row_bill['sid'];
                        $bill_log['name'] = $row_bill['name'];
                        $bill_log['user_money'] = -$row_bill['user_money'];
                        $bill_log['coupon_id_num'] = $row_bill['coupon_id_num'];
                        $bill_log['coupon_id_sid_num'] = $row_bill['coupon_id_sid_num'];
                        $bill_log['comm_amount'] = $row_bill['comm_amount'];
                        $bill_log['order_sn'] = $row_bill['order_sn'];
                        $bill_log['desc'] = '订单退款扣除商家金额 ' . $row_bill['order_sn'];
                        $this->load->model('business');
                        $this->business->billlog(ACT_UNBILL,$bill_log);
                    }

                    $this->refund_comms($post['order_id']);

                    // 模版消息 17 退款申请被通过 {插入订单号},{插入店铺}
                    // template_msg_action start
                    $this->load->model('template_msg');
                    $msgParams = array($order_info['order_sn'], C("site_name"));
                    $this->template_msg->inQueue(17, $row['mid'], $msgParams);
                    // template_msg_action end

					$order = $this->db->get("select * from ###_goods_order where id={$post['order_id']}");
					
                    $this->load->model('exchange');
                    if($this->exchange->power&&$order['score']>0){
                    	$this->exchange->action(array('mid'=>$order['mid'],'order_sn'=>$order['order_sn'],'score'=>$order['score'],'type'=>"2"));
                    }
                    
                }elseif($post['status']==2 && $post['complain_status']==0){
                    $this->db->update("goods_order", array("is_refund"=>1), array("id"=>$post['order_id']));
                    $order_sn = $this->db->getstr("select order_sn from ###_goods_order where id={$row['order_id']}","order_sn");
                    
                    // 模版消息 18 退款申请被拒绝 {插入订单号},{插入理由},{插入店铺}
                    // template_msg_action start
                    $this->load->model('template_msg');
                    $msgParams = array($order_sn, $post['mark'], C("site_name"));
                    $this->template_msg->inQueue(18, $row['mid'], $msgParams);
                    // template_msg_action end
                    
                }elseif($post['status_refund']==1 && $post['complain_status']==0){
                    
                    // 模版消息 32 退款成功  {插入退款金额}
                    // template_msg_action start
                    $this->load->model('template_msg');
                    $msgParams = array($row['refund_amount']);
                    $this->template_msg->inQueue(32, $row['mid'], $msgParams);
                    // template_msg_action end
                    
                }elseif($post['complain_status']==3){//交易投诉 买家的错

                    $order_sn = $this->db->getstr("select order_sn from ###_goods_order where id={$row['order_id']}","order_sn");

                    // 模版消息 35 交易投诉  {插入订单号}
                    // template_msg_action start
                    $this->load->model('template_msg');
                    $msgParams = array($order_sn);
                    $this->template_msg->inQueue(35, $row['mid'], $msgParams);
                    // template_msg_action end
                }
                
                return array('code' => 0, 'type' => 'update', 'message' => '更新成功');
            }                        
        }
        /**
         * 退货成功扣除佣金
         * @param  array  $mid      会员mid
         */
        function refund_comms($order_id) {
            $this->load->model('member');
            $sql = "select order_sn from ###_goods_order where id=". $order_id;
            $order_sn = $this->db->getstr($sql, 'order_sn');
            $sql = "select * from ###_commission where order_id=" . $order_id;
            $res = $this->db->select($sql);
            if ($res) {
                foreach ($res as $key => $val) {
                    $insert_arr = array();
                    $insert_arr['mid'] = $val['mid'];
                    $insert_arr['username'] = $val['username'];
                    $insert_arr['ivt_mid'] = $val['ivt_mid'];
                    $insert_arr['ivt_username'] = $val['ivt_username'];
                    $insert_arr['order_id'] = $val['order_id'];
                    $insert_arr['total'] = $val['total'];
                    $insert_arr['commission'] = -$val['commission'];
                    $insert_arr['desc'] = "会员退款(订单号".$order_sn."),佣金返还";
                    $insert_arr['level'] = $val['level'];
                    $this->member->save_commission($insert_arr);
                }
            }
        }
        function getAddressList($bid=0){
            
            $res = $this->db->select("select * from ###_business_address where sid={$bid}");
            return $res;
            
        }
        
        function getAddressOne($id){
            
            $res = $this->db->get("select * from ###_business_address where id={$id}");
            return $res;
            
        }
        
        function address(){
            
            $post=$_POST['post'];            
            $res = $this->db->save("business_address", $post);
            if($res === false){
                return array('code' => 10001, 'type' => 'update', 'message' => '更新失败');
            }else{               
                return array('code' => 0, 'type' => 'update', 'message' => '更新成功');
            }   
        }
        
        function status_name($info){
            $name = '';
            if($info['type']==1){
                if($info['status']==0)$name = "退款申请中";   
                if($info['status']==2)$name = "商家拒绝";
                if($info['status']==10){
                    $name = "商家确认";
                    if($info['status_refund']==0){
                        $name.=",等待平台确认";
                    }elseif($info['status_refund']==10){
                        $name="平台已确认";
                    }
                } 
            }elseif($info['type']==2){
                if($info['status']==0)$name = "退货申请中";  
                if($info['status']==2)$name = "商家拒绝";
                if($info['status']==10){                    
                    if($info['status_shipping']==0){
                        $name.="商家确认,待退货";
                    }elseif($info['status_shipping']==1){                        
                        $name.="商家确认,返仓中";
                    }elseif($info['status_shipping']==10){                        
                        if($info['status_refund']==0 ){
                            $name.="返仓中,等待平台确认";
                        }elseif($info['status_refund']==10){
                            $name.="平台已确认";
                        }
                    }
                } 
            }          
            return $name;
        }
        
        /*
        * 添加退款记录
        * $data = array("order_id","order_amount","out_trade_no");
        */
        function addRefundLog($data=array()){
            $data['c_time'] = RUN_TIME;
            $res = $this->db->insert("refund_log", $data);
            if($res!==false){
                return array('code' => 0, 'message' => '成功');
            }else{
                return array('code' => 10001, 'message' => '失败');
            }
        }
        
        /*
        * 修改退款记录 
        */
        function updateRefundLog($id,$data=array()){
            if(empty($id) && empty($data['order_id']))return array('code' => 10001, 'message' => 'ID和订单ID不能为空');
            $data['u_time'] = RUN_TIME;
            $res = $this->db->update("refund_log", $data,array('id'=>$id));
            if($res!==false){
                if($data['is_refund']==1)$this->db->update("goods_order", array("status_order"=>3), array('id'=>$data['order_id']));
                return array('code' => 0, 'message' => '成功');
            }else{                
                return array('code' => 10001, 'message' => '失败');
            }
        }
        
}