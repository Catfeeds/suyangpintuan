<?php

/**

 * ZZCMS 退货、退款管理

 * ============================================================================

 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。

 * 网站地址: http://www.lnest.com；

 * ----------------------------------------------------------------------------

 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和

 * 使用；不允许对程序代码以任何形式任何目的的再发布。

 */

class refund extends Lowxp {

	function __construct() {                   
		parent::__construct();
                
                $this->load->model("refund");
	}

	function lists($type = 1,$page = 1) {
                if($type==1){
                    $this->btnMenu = array(0=>array('url' => '#!refund/lists/1', 'name' => '退款管理'));
                }elseif($type==2){
                    $this->btnMenu = array(0=>array('url' => '#!refund/lists/2', 'name' => '退货管理'),1=>array('url'=>'#!refund/address_list','name'=>'收货地址'),2=>array('url'=>'#!refund/address?com=xshow|添加退货地址','name'=>'添加地址'));
                }                
                $this->smarty->assign('btnMenu', isset($this->btnMenu) ? $this->btnMenu : array());
                            
                $reasonType = $this->refund->reasonTypes;            
                
                ${$_REQUEST['k']} = $_REQUEST['q']; 
                $start_time = !empty($_GET['start_time'])?strtotime($_GET['start_time']):0;
                $end_time = !empty($_GET['end_time'])?strtotime($_GET['end_time']):0;
                
                $where = " type={$type} and sid=".BID;
                if($start_time>0){
                    $where.=" AND c_time>=".$start_time;
                }
                
                if($end_time>0){
                    $where.=" AND c_time<=".$end_time;
                }
                
                if ($_GET['status']>0 || $_GET['status']=='0') {
                    $where .= " AND status=".intval($_GET['status']);
		}
                
                if ($_GET['reason_id']>0 || $_GET['reason_id']=='0') {
                    $where .= " AND reason_id=".intval($_GET['reason_id']);
		}
                if($id>0){
                    $where.=" AND id=".$id;
                }
                if($order_id>0){
                    $where.=" AND order_id=".$order_id;
                }
                
		$sort = !empty($_GET['sort']) ? trim($_GET['sort']) : 'id';

		$order = !empty($_GET['sort']) ? trim($_GET['order']) : 'DESC';
                               
                #分页

		$this->load->model('page');

		$_GET['page'] = intval($page);

		$this->page->set_vars(array('per' => (int) $this->common['page_listrows']));		
		
		#数据集

		$sql = "SELECT * FROM ###_refund WHERE $where ORDER BY $sort $order";

		$list = $this->page->hashQuery($sql)->result_array();
                
                $list = $this->db->lJoin($list,"member","mid,username,mobile","mid","mid");
                
                $list = $this->db->lJoin($list,"goods_order","id,order_sn","order_id","id","order_");
		
                foreach($list as $k=>$v){
                    $list[$k]['reason_name'] = $reasonType[$v['reason_id']];
                }
                
		$this->smarty->assign('reasonType', $reasonType);
                
		$this->smarty->assign('type', $type);
		
                $this->smarty->assign('list', $list);

		$this->smarty->display('business/refund/list.html');

	}

	//更新

	function edit() {

		//提交
		if (isset($_POST['Submit'])) {
                    $res = $this->refund->save();
                    if (isset($res['code']) && $res['code'] == 0) {
                            $this->tip($res['message'], array('inIframe' => true));
                            $this->exeJs("parent.com.xhide();parent.main.refresh()");
                    } else {
                            $this->tip($res['message'], array('inIframe' => true, 'type' => 1));
                    }
                    exit;
		}
		$id = (int) $_GET['id'];
		$row = $this->get_info($id);
                
		$this->smarty->assign('row', $row);
		$this->smarty->display('business/refund/edit.html');

	}
                
        //查看详情
        function detail(){            
                
		$id = (int) $_GET['id'];
                $row = $this->get_info($id);
                
		$this->smarty->assign('row', $row);       
		$this->smarty->display('business/refund/detail.html');
        }
        
        //平台确认
        function status_refund(){
            
                 
		$id = (int) $_GET['id'];
                $row = $this->get_info($id);
                
		$this->smarty->assign('row', $row);
		$this->smarty->display('business/refund/status_refund.html');
        }
        //收货
        function status_shipping(){            
                 
		$id = (int) $_GET['id'];
                $row = $this->get_info($id);
                
		$this->smarty->assign('row', $row);
		$this->smarty->display('business/refund/status_shipping.html');
        }
        
        
        function get_info($id){
            $row = $this->db->get("SELECT r.*,m.username FROM ###_refund as r left join ###_member as m on r.mid=m.mid WHERE r.id=" . $id." and sid=".BID);
                
            $reasonTypes = $this->refund->reasonTypes;
            $row['reason_name'] = $reasonTypes[$row['reason_id']];
            if($row['pic']){
                $row['pic_list'] = explode("|", $row['pic']);
            }
                
            $this->load->model("order");              
            $order = $this->db->get("SELECT * FROM ###_goods_order WHERE id=" . $row['order_id']);
            $orders = array($order);
            $orders = $this->order->unionOrderGoods($orders); 
            $order = $orders[0];
            $actTypes = $this->order->actTypes;
            $order['order_code'] = $actTypes[$order['extension_code']]['title'];
            $row['order'] = $order;
            $row['address_list'] = $this->refund->getAddressList(BID);
            $row['address'] = $this->refund->getAddressOne($row['address_id']);
            
            return $row;
        }
        
        function save(){
            if (isset($_POST['Submit'])) {
                $res = $this->refund->save();
                if (isset($res['code']) && $res['code'] == 0) {
                        $this->tip($res['message'], array('inIframe' => true));
                        $this->exeJs("parent.com.xhide();parent.main.refresh()");
                } else {
                        $this->tip($res['message'], array('inIframe' => true, 'type' => 1));
                }
                exit;
            }
        }
        
        //收货地址
        function address_list(){
            $this->btnMenu = array(1=>array('url' => '#!refund/lists/2', 'name' => '退货管理'),0=>array('url'=>'#!refund/address_list','name'=>'收货地址'),2=>array('url'=>'#!refund/address?com=xshow|添加退货地址','name'=>'添加地址'));
            $this->smarty->assign('btnMenu', isset($this->btnMenu) ? $this->btnMenu : array());
            
            $list = $this->refund->getAddressList(BID);
            
            $this->smarty->assign("list",$list);
            $this->smarty->display('business/refund/address_list.html');
        }
        
        //添加收货地址
        function address(){
            if(isset($_POST['Submit'])){
                $_POST['post']['sid'] = BID;
                $res = $this->refund->address();
                if (isset($res['code']) && $res['code'] == 0) {
                    $this->tip($res['message'], array('inIframe' => true));
                    $this->exeJs("parent.com.xhide();parent.main.refresh()");
                } else {
                    $this->tip($res['message'], array('inIframe' => true, 'type' => 1));
                }
                exit;
            }
            $id = intval($_REQUEST['id']);
            $row = array();
            if($id){
                $row = $this->refund->getAddressOne($id);                
            }
            $this->smarty->assign("row",$row);
            $this->smarty->display('business/refund/address.html');
        }
        
        //退款记录 需要请求第三方接口的列表
        function log(){
            
            $this->btnMenu = array(0=>array('url' => '#!refund/log', 'name' => '退款记录'));
            $this->smarty->assign('btnMenu', isset($this->btnMenu) ? $this->btnMenu : array());
            
            ${$_REQUEST['k']} = $_REQUEST['q']; 
            $start_time = !empty($_GET['start_time'])?strtotime($_GET['start_time']):0;
            $end_time = !empty($_GET['end_time'])?strtotime($_GET['end_time']):0;

            $where = " 1 ";
            if($start_time>0){
                $where.=" AND c_time>=".$start_time;
            }

            if($end_time>0){
                $where.=" AND c_time<=".$end_time;
            }

            if ($_GET['is_refund']>0 || $_GET['is_refund']=='0') {
                $where .= " AND is_refund=".intval($_GET['is_refund']);
            }

            if($id>0){
                $where.=" AND id=".$id;
            }
            if($order_id>0){
                $where.=" AND order_id=".$order_id;
            }

            $sort = !empty($_GET['sort']) ? trim($_GET['sort']) : 'id';

            $order = !empty($_GET['sort']) ? trim($_GET['order']) : 'DESC';

            #分页

            $this->load->model('page');

            $_GET['page'] = intval($page);

            $this->page->set_vars(array('per' => (int) $this->common['page_listrows']));		

            #数据集

            $sql = "SELECT * FROM ###_refund_log WHERE $where ORDER BY $sort $order";
            
            $list = $this->page->hashQuery($sql)->result_array();
                            
            $list = $this->db->lJoin($list,"goods_order","id,order_sn","order_id","id","order_");          

            $this->smarty->assign('list', $list);

            $this->smarty->display('business/refund/log.html');
            
        }
        
        //退款记录详情
        function log_detail(){
            $id = intval($_GET['id']);
            $row = $this->db->get("select * from ###_refund_log where id={$id}");
            
            $this->load->model("order");              
            $order = $this->db->get("SELECT * FROM ###_goods_order WHERE id=" . $row['order_id']);
            $orders = array($order);
            $orders = $this->order->unionOrderGoods($orders); 
            $order = $orders[0];
            $actTypes = $this->order->actTypes;
            $order['order_code'] = $actTypes[$order['extension_code']]['title'];
            $row['order'] = $order;
            
            $this->smarty->assign("row",$row);
            $this->smarty->display("business/refund/log_detail.html");
        }
        
        //退款记录编辑
        function log_edit(){
            
            if (isset($_POST['Submit'])) {            
                $post = $_POST['post'];              
                $res = $this->refund->updateRefundLog($post['id'],$post);
                if (isset($res['code']) && $res['code'] == 0) {
                        $this->tip($res['message'], array('inIframe' => true));
                        $this->exeJs("parent.com.xhide();parent.main.refresh()");
                } else {
                        $this->tip($res['message'], array('inIframe' => true, 'type' => 1));
                }
                exit;
            }
            $id = intval($_REQUEST['id']);
            $row = $this->db->get("select * from ###_refund_log where id={$id}");
            $this->load->model("order");              
            $order = $this->db->get("SELECT * FROM ###_goods_order WHERE id=" . $row['order_id']);
            $orders = array($order);
            $orders = $this->order->unionOrderGoods($orders); 
            $order = $orders[0];
            $actTypes = $this->order->actTypes;
            $order['order_code'] = $actTypes[$order['extension_code']]['title'];
            $row['order'] = $order;
            
            $this->smarty->assign("row",$row);
            $this->smarty->display("business/refund/log_edit.html");
        }

}