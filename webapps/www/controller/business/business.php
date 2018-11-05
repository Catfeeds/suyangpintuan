<?php
/**
 * ZZCMS 商家管理
 * ============================================================================
 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。
 * 网站地址: http://www.lnest.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 */

class business extends Lowxp {
	function __construct() {
		$this->btnMenu = array(
		0 => array('url' => '#!business/bankcard', 'name' => '开户行设置'),
		1 => array('url' => '#!business/bill_log', 'name' => '结算明细'),
		2 => array('url' => '#!business/withdrawals_log', 'name' => '提现管理'),
		3 => array('url' => '#!business/withdrawals', 'name' => '申请提现'),
		);
		parent::__construct();
		#加载
		
	}
        
        /**
	 * 开户行设置
	 */
     function bankcard(){
        	$this->load->model('business');
            if(isset($_POST['Submit'])){
                $post = $_POST['post'];
                $row = $this->business->get_bank_card(BID);
                if($row){
                	$res = $this->db->update("business_bankcard", $post, array("id"=>$post['id']));
                }else{
                	$post['c_time'] = RUN_TIME;
                	$post['sid'] = BID;
                	$res = $this->db->insert("business_bankcard", $post);
                }
                if (false !== $res) {
                        $this->tip("操作成功", array('inIframe' => true));
                        $this->exeJs("parent.com.xhide();parent.main.refresh()");
                } else {
                        $this->error_msg('操作失败'); 
                }
                exit;
            }
            
            
            $row = $this->business->get_bank_card(BID);
                        
            $this->smarty->assign('row', $row);
            $this->smarty->display('business/store/bankcard.html');            
            
        }
        /**
      * 商家提现
      */
     function withdrawals_log($page = '1'){
     	#操作类型
     	$this->load->model('member');
     	$this->smarty->assign('stages', $this->member->stages());
     	$where = "  ";
     	$order = ' ORDER BY ';
     	$where .= " AND sid = ".BID;
     	
     	if (isset($_GET['start_time'])&&$_GET['start_time']) {
     		$where .= " AND logtime >= '" . strtotime($_GET['start_time']) . "'";
     	}
     	if (isset($_GET['end_time'])&&$_GET['end_time']) {
     		$where .= " AND logtime <= '" . strtotime($_GET['end_time']) . "'";
     	}
     	
     	if (isset($_GET['sortorder'])&&$_GET['sortorder']) {
     		if ($_GET['points']) {
     			$order .= trim($_GET['points']) . " " . $_GET['sortorder'];
     		} else {
     			$order .= "id " . $_GET['sortorder'];
     		}
     	} else {
     		$order .= 'id DESC';
     	}
     	#关键词搜索
     	$array = array('k', 'q');
     	foreach ($array as $v) {
	     	if (!isset($_GET[$v])) {
	     		$_GET[$v] = '';
	     	}
     	}
     	if (!empty($_GET['q'])) {
     		$where .= " AND `" . trim($_GET['k']) . "` LIKE '%" . addslashes($_GET['q']) . "%'";
     	}
     	$this->smarty->assign($_GET);
     	$this->load->model('page');
     	$_GET['page'] = intval($page);
     	$this->page->set_vars(array(
     	'per' => (int)$this->common['page_listrows'],
     	'url' => ' href="#!business/withdrawals_log/{num}?"',
     	));
     	$list = $this->page->hashQuery("SELECT * FROM ###_withdraw_commission_sid WHERE id <> 0 $where $order")->result_array();
     	$this->smarty->assign('list', $list);
     	$total = $this->db->get("SELECT SUM(amount) as amount, SUM(fee) as fee FROM ###_withdraw_commission_sid WHERE id <> 0 $where");
     	$this->smarty->assign('total', $total);
     	$this->smarty->assign('btnNo', 2);
     	$this->smarty->display('business/store/withdrawals_log.html');
     }
     
     /**
      * 申请提现
      */
     function withdrawals($id = ''){
     	$this->load->model('business');
     	$data = $limit = array();
     	$data = $this->business->get(BID);
     	$limit['commission_fee'] = C("commission_fee") > 0 ? C("commission_fee") : 0;
     	$limit['com_deposit'] = C("com_deposit") > 0 ? C("com_deposit") : 0;
     	$limit['comm_limit_min'] = C("comm_limit_min") > 0 ? C("comm_limit_min") : 0;
     	$limit['comm_limit_max'] = C("comm_limit_max") > 0 ? C("comm_limit_max") : 0;
     	//查询配置的几种提现方式
     	$bankcard = $this->business->get_bank_card(BID);
     	if(!$bankcard['bankcard']&&!$bankcard['wx']&&!$bankcard['alipay']){
     		$this->tip("请配置提现方式", array('inIframe' => true, 'type' => 1));
     		$this->exeJs("parent.window.location='/business#!business/bankcard';");
     	}
     	$this->smarty->assign('bankcard',$bankcard);
     	
     	if(isset($_POST['Submit'])){
     		$post = $_POST['post'];
     		if(ceil($post['amount'])!=$post['amount']) {
     			$this->tip("申请提现金额必须是整数", array('inIframe' => true, 'type' => 1));
     			exit;
     		}
            if(empty($post['amount']))  {
            	$this->tip("请输入正确的提现金额", array('inIframe' => true, 'type' => 1));
            	exit;
            }
            if($post['amount']>($data['user_money']-$limit['com_deposit'])){
            	$this->tip("提现余额不足！", array('inIframe' => true, 'type' => 1));
            	exit;
            }
            if($post['amount']>$limit['comm_limit_max']){
            	$this->tip("提现余额超过最大值！", array('inIframe' => true, 'type' => 1));
            	exit;
            }
            if($post['amount']<$limit['comm_limit_min']){
            	$this->tip("提现余额太小！", array('inIframe' => true, 'type' => 1));
            	exit;
            }
            if(!$post['type']){
            	$this->tip("请选择提现方式！", array('inIframe' => true, 'type' => 1));
            	exit;
            }
            if($limit['commission_fee']){
            	$post['fee'] = sprintf("%.2f", $post['amount']*$limit['commission_fee']/100);
            }
     		
     		if($id){
     			$res = $this->db->update("withdraw_commission_sid", $post, array("id"=>$id));
     			$this->smarty->assign('id',$id);
     		}else{
     			$post['realname'] = $bankcard['truename'];
     			$post['name'] = $data['name'];
     			if($post['type']==1){
     				$post['account'] = $bankcard['bankcard'];
     				$post['bank'] = $bankcard['bank'];
     			}elseif($post['type']==2){
     				$post['wx'] = $bankcard['wx'];
     			}elseif($post['type']==3){
     				$post['alipay'] = $bankcard['alipay'];
     			}
     			$post['addtime'] = RUN_TIME;
     			$post['sid'] = BID;
     			$res = $this->db->insert("withdraw_commission_sid", $post);
     		}
     		if (false !== $res) {
     			//扣除金额
     			$log_arr = array();
     			$log_arr['mid'] = BID;
     			$log_arr['username'] = $data['name'];
     			$log_arr['user_money'] = -$post['amount']; //余额
     			$log_arr['desc'] = '商家提现 ' ;
     			$this->business->accountlog(ACT_DRAWING, $log_arr);
     			$this->tip("操作成功", array('inIframe' => true));
     			//$this->exeJs("parent.com.xhide();parent.main.refresh()");
     			$this->exeJs("parent.window.location='/business#!business/withdrawals_log';");
     		} else {
     			$this->error_msg('操作失败');
     		}
     		exit;
     	}
     	
     	$row = $this->business->get_bank_card(BID);
     	#echo "<pre>";print_r($data);exit;
     	$this->smarty->assign('row', $row);
     	$this->smarty->assign('data',$data);
     	$this->smarty->assign('limit',$limit);
     	$this->smarty->assign('btnNo', 3);
     	$this->smarty->display('business/store/withdrawals.html');
     }
     
     /**
      * 取消提现申请
      */
     function withdrawals_del($id){
     	if(!$id){
     		$this->tip("参数错误", array('inIframe' => true, 'type' => 1));
     		$this->exeJs("parent.window.location='/business#!business/withdrawals_log';");
     	}
     	$this->load->model('business');
     	//检查该申请是否是本人，是否是未审核
     	$withdraw = $this->business->get_withdrawals_log($id);
     	
     	if(empty($withdraw)){//没有该申请
     		$this->tip("不存在该申请！", array('inIframe' => true, 'type' => 1));
     		exit;
     	}
     	if($withdraw['sid']!=BID){//不属于该商家提现申请
     		$this->tip("操作错误！", array('inIframe' => true, 'type' => 1));
     		exit;
     	}
     	if($withdraw['status']!=0){
     		$this->tip("该申请不在未审核状态！", array('inIframe' => true, 'type' => 1));
     		exit;
     	}
     	
     	$res = $this->db->delete("withdraw_commission_sid", array('id'=>$id));
     	
     	if (false !== $res) {
     		//返回申请提现金额
     		$log_arr = array();
     		$log_arr['mid'] = BID;
     		$log_arr['username'] = $withdraw['name'];
     		$log_arr['user_money'] = $withdraw['amount']; //余额
     		$log_arr['desc'] = '商家提现返回 ' ;
     		$this->business->accountlog(ACT_DRAWING, $log_arr);
     		$this->tip("操作成功", array('inIframe' => true));
     		$this->exeJs("parent.window.location='/business#!business/withdrawals_log';");
     	} else {
     		$this->error_msg('操作失败');
     	}
     	exit;
     }
        /**
	 * 店铺设置
	 */
        function setting(){
            if(isset($_POST['Submit'])){
                $post = $_POST['post'];
                $post['zone'] = end(array_filter($post['zone']));                        
                $this->load->model("upload");
                $image_url = $this->load->config('picture','image_url');
                if($_FILES['logo']['error']==0){
                    $post['logo'] = $this->upload->upload_image('logo', $image_url.'logo/');
                }elseif(empty($post['id'])){
                    $this->error_msg('请上传店铺LOGO'); 
                }
                if($_FILES['banner']['error']==0){
                    $post['banner'] = $this->upload->upload_image('banner', $image_url.'other/');
                }elseif(empty($post['id'])){
                    $this->error_msg('请上传店铺banner'); 
                }
                if (isset($post['ad']) && !empty($post['ad'])) {
                    $_arrData = array();
                    foreach ($post['ad']['path'] as $k => $v) {
                        $_arrData[$k]['path'] = $v;
                        $_arrData[$k]['title'] = $post['ad']['title'][$k];
                        $_arrData[$k]['iosurl'] = $post['ad']['iosurl'][$k];
                        $_arrData[$k]['anurl'] = $post['ad']['anurl'][$k];
                    }
                    if(count($_arrData)>5){
                        $this->error_msg('广告图不能大于5张'); 
                    }
                    $post['ad'] = json_encode($_arrData);
                } else {
                    $this->error_msg('请至少上传一张广告图'); 
                }
                if (isset($post['brand']) && !empty($post['brand'])) {
                    $_arrData = array();
                    foreach ($post['brand']['path'] as $k => $v) {
                        $_arrData[$k]['path'] = $v;
                        $_arrData[$k]['title'] = $post['brand']['title'][$k];
                    }
                    if(count($_arrData)>10){
                        $this->error_msg('主营品牌图不能大于10张'); 
                    }
                    $post['brand'] = json_encode($_arrData);
                } 
                $res = $this->db->update("business", $post, array("id"=>BID));
                if (false !== $res) {
                        $this->tip("操作成功", array('inIframe' => true));
                        $this->exeJs("parent.com.xhide();parent.main.refresh()");
                } else {
                        $this->error_msg('操作失败'); 
                }
                exit;
            }
            
            $info = $this->db->get("select * from ###_business where id=".BID);

            //获取分类
            $this->load->model("goodscat");
            $select_categorys = $this->goodscat->category_top_option(isset($info['cid']) ? $info['cid'] : 0);
            $this->smarty->assign('select_categorys', $select_categorys);
            
            $info['ad'] = isset($info['ad']) ? $info['ad'] : '';
            $info['html_ad'] = $this->form->files('ad', $info['ad'], '上传图集', array(
                'maxnum' => 5,
            ),array("appurl"=>1));
            $info['brand'] = isset($info['brand']) ? $info['brand'] : '';
            $info['html_brand'] = $this->form->files('brand', $info['brand'], '上传图集', array(
                'maxnum' => 10,
            ),array("appurl"=>1));
            $this->smarty->assign('info', $info);
            $this->smarty->display('business/store/setting.html');           
            
        }
        /**
	 * 修改密码
	 */
        function updatepwd(){
            if(isset($_POST['Submit'])){
                $post = $_POST['post'];
                if(empty($post['oldpass']))$this->error_msg('原密码不能为空'); 
                if(empty($post['pass1']))$this->error_msg('新密码不能为空'); 
                if(empty($post['pass2']))$this->error_msg('确认密码不能为空'); 
                if($post['pass2']!=$post['pass1'])$this->error_msg('两次密码不一样'); 
                $user = $this->db->get("SELECT password,salt FROM ###_business WHERE id='" . BID."'");
                if ($this->get_salt_hash($post['oldpass'], $user['salt']) != $user['password']) {
                    $this->error_msg('原密码错误'); 
                }                
                if(!empty($post['pass1'])){                    
                    $post['password'] = $this->get_salt_hash($post['pass1'], $user['salt']);
                }                
                $res = $this->db->update("business", array('password'=>$post['password']), array("id"=>BID));
                if (false !== $res) {
                    $this->tip("操作成功", array('inIframe' => true));
                    $this->exeJs("parent.com.xhide();parent.main.refresh()");
                } else {
                    $this->error_msg('操作失败'); 
                }
                exit;
            }            
            $this->smarty->display('business/store/updatepwd.html');
        }
        
        
        function error_msg($msg){
            $this->tip($msg, array('inIframe' => true));exit;
        }
        
        /**
	 * 获取加密的密码
	 *
	 * @param $password
	 * @param $salt
	 * @param string $gsalt
	 * @return string
	 */
	public function get_salt_hash($password, $salt, $gsalt = 'scYltK') {

		$passwordmd5 = preg_match('/^\w{32}$/', $password) ? $password : md5($password);

		return md5($passwordmd5 . $salt . $gsalt);

	}
        
        function bill(){
            $no = trim($_REQUEST['no']);
            $name = trim($_REQUEST['name']);
            $state = intval($_REQUEST['state']);
            if($no){
                $option['where'] = " and no={$no} ";
            }
            if($name){
                $option['where'] = " and name like '%{$name}%' ";
            }
            if($state>0){
                $option['where'] = " and state={$state} ";
            }
            $this->load->model("business");
            $list = $this->business->get_bill(BID,$option);
            $this->smarty->assign("list",$list);
            $this->smarty->display('business/store/bill.html'); 
        }  
		/**
      * 商家结算明细
      */
     function bill_log($page = '1'){
		#操作类型
		$this->load->model('member');
        $this->smarty->assign('stages', $this->member->stages());
        $where = "  AND stage in('bill','unbill') ";
        $order = ' ORDER BY ';
        $where .= " AND sid = ".BID;
        if (isset($_GET['stage'])&&$_GET['stage']) {
            $where .= " AND stage = '$_GET[stage]'";
        }
        if (isset($_GET['start_time'])&&$_GET['start_time']) {
            $where .= " AND logtime >= '" . strtotime($_GET['start_time']) . "'";
        }
        if (isset($_GET['end_time'])&&$_GET['end_time']) {
            $where .= " AND logtime <= '" . strtotime($_GET['end_time']) . "'";
        }
        if (isset($_GET['points'])&&$_GET['points']) {
            $where .= " AND " . trim($_GET['points']) . "<>0";
        }
        if (isset($_GET['sortorder'])&&$_GET['sortorder']) {
            if ($_GET['points']) {
                $order .= trim($_GET['points']) . " " . $_GET['sortorder'];
            } else {
                $order .= "id " . $_GET['sortorder'];
            }
        } else {
            $order .= 'id DESC';
        }
        #关键词搜索
        $array = array('k', 'q');
        foreach ($array as $v) {
            if (!isset($_GET[$v])) {
                $_GET[$v] = '';
            }
        }
        if (!empty($_GET['q'])) {
            $where .= " AND `" . trim($_GET['k']) . "` LIKE '%" . addslashes($_GET['q']) . "%'";
        }
        $this->smarty->assign($_GET);
        $this->load->model('page');
        $_GET['page'] = intval($page);
        $this->page->set_vars(array(
            'per' => (int)$this->common['page_listrows'],
            'url' => ' href="#!business/bill_log/{num}?"',
        ));
        $list = $this->page->hashQuery("SELECT * FROM ###_business_bills WHERE id <> 0 $where $order")->result_array();
        $this->smarty->assign('list', $list);
        $total = $this->db->get("SELECT SUM(user_money) as user_money FROM ###_business_bills WHERE id <> 0 $where");
        $this->smarty->assign('total', $total);
     	$this->smarty->display('business/store/bill_log.html');
     }
        /**
	 * 商家账单确认	
	 */
        function comfirm_bill(){
            $this->load->model("business");
            if(isset($_POST['Submit'])){               
               $res = $this->business->update_bill(); 
               if (false !== $res) {
                    $this->tip("操作成功", array('inIframe' => true));
                    $this->exeJs("parent.com.xhide();parent.main.refresh()");
                } else {
                        $this->error_msg('操作失败'); 
                }
            }
            
            $no = trim($_REQUEST['no']);            
            
            $row = $this->business->get_bill_one($no);            
            $this->smarty->assign("row",$row);
            $this->smarty->display('business/store/bill_pay.html');
            
        }
         /**
	 * 商家账单详情	
	 */
        function detail_bill(){
            $this->load->model("business"); 
            $no = trim($_REQUEST['no']); 
            $row = $this->business->get_bill_one($no);        
            $this->smarty->assign("row",$row);
            $this->smarty->display('business/store/bill_detail.html');
            
        }

    /**
     * 商家定位
     */
    function geolbs($id=''){
        $this->smarty->assign("id",encrypt_en($id));
        $this->smarty->display('manage/business/geolbs.html');
    }

    /**
     * 商家保证金记录
     */
    function account($page = 1)
    {
        $where = " where 1 and bid=".BID;
        $order = " order by id desc ";

        $start_time = isset($_GET['start_time']) ? strtotime($_GET['start_time']) : '';
        $end_time = isset($_GET['end_time']) ? strtotime($_GET['end_time']) : '';

        if ($start_time) {
            $where .= " and logtime>='{$start_time}'";
        }
        if ($end_time) {
            $where .= " and logtime<='{$end_time}'";
        }

        #分页
        $this->load->model('page');
        $_GET['page'] = intval($page);
        $this->page->set_vars(array('per' => (int)$this->common['page_listrows']));
        $sql = "select * from `###_business_account` $where $order";
        $data['list'] = $this->page->hashQuery($sql)->result_array();

        $data['deposit'] = $this->db->getstr("select deposit from ###_business where id=".BID,'deposit');

        $this->smarty->assign('data', $data);

        $this->smarty->display('business/store/account.html');

    }
        
}