<?php
/**
 * 优惠券控制器
 */
require_once __DIR__ . '/member.php';
class coupon extends member {
	function __construct() {
		parent::__construct();
		$this->load->model('coupon');
	}

	function index($page = 1) {
		$_GET['page'] = $page;

		$type = empty($_GET['type']) ? '' : $_GET['type'];
        switch ($type) {
            case '1'://未使用
                $where = ' AND (expire_time>=' . RUN_TIME . ' or expire_time=0) AND use_time=0';
                break;
            case '2'://已使用
                $where = ' AND use_time>0';
                break;
            case '3'://过期
                $where = ' AND expire_time>0 AND expire_time<' . RUN_TIME . ' AND use_time=0';
                break;
            case '2|3'://已使用|过期
                $where = ' AND (use_time>0 OR ( expire_time>0 AND expire_time<' . RUN_TIME . ' AND use_time=0))';
                break;
            default://全部
                $where = '';
                break;
        }

		$this->load->model('page');
		// $this->page->set_vars(array('per' => $this->site_config['page_size']));

		$this->page->set_vars(array('per' => 10));

		$sql = 'SELECT * FROM ###_coupon_log WHERE mid = ' . MID . $where . ' ORDER BY create_time DESC';
		$list = $this->page->hashQuery($sql)->result_array();
		$list = $this->db->lJoin($list, 'coupon', 'id,title,need_amount,amount,start_time,end_time,remark', 'coupon_id', 'id', 'b_');
        $list = $this->db->lJoin($list, 'business', 'id,name', 'sid', 'id', 's_');
		foreach ($list as $k => $v) {
			if ($v['share']) {

				$tmp = array(
					 'mid'   => MID,
					'id'    => $v['id'],
				);

				$tmp = '?t=' . url_encrypt($tmp, AuthKey);

				$list[$k]['share_url'] = url('/wxnews/getshare' . $tmp);
			}

		}

		// if (empty($list)) {
		// 	$this->smarty->display('coupon/nolist.html');
		// 	die;
		// }

		$this->smarty->assign('status', $this->coupon->logStatus);
		$this->smarty->assign('type', $type);

		#异步加载
		if (isset($_GET['load'])) {
			if ($list) {
				$content = '';
				foreach ($list as $v) {
					$this->smarty->assign('m', $v);
					$content .= $this->smarty->fetch('coupon/lbi/list.html');
				}
				echo $content;
			}
			die;
		}

		$this->smarty->assign('list', $list);
		$this->smarty->display('coupon/list.html');
	}
	
	//领取优惠券
	function ajax_coupon_get($id){
		$id = intval($id);

        $is_res = $this->db->get("select 1 from ###_coupon_log where mid=".MID." and coupon_id={$id}");
        if($is_res){
            echo json_encode(array("error"=>1,"msg"=>"已经领取过"));exit;
        }
        $stock = $this->db->getstr("select stock from ###_coupon where id={$id}","stock");
        if($stock<=0){
            echo json_encode(array("error"=>1,"msg"=>"优惠券已被领取完"));exit;
        }

		$this->load->model("coupon");
		$res = $this->coupon->sendOne(MID, $id);
		if($res){
			echo json_encode(array("error"=>0,"msg"=>"领取成功"));exit;
		}else{
			echo json_encode(array("error"=>1,"msg"=>"领取失败"));exit;
		}
		
	}

    //优惠券领取界面
    function receive_center($page = 1){
        $_GET['page'] = $page;

        $where = " status=1 and stock>0 and (end_time>=".RUN_TIME." || end_time=0) ";
        $where1 = " AND sid=0 AND is_view=1";
        $where2 = " AND sid>0 ";
        $this->load->model('page');
        // $this->page->set_vars(array('per' => $this->site_config['page_size']));
        $this->page->set_vars(array('per' => 20));
        //平台
        $sql1 = 'SELECT * FROM ###_coupon WHERE  ' . $where.$where1 . ' ORDER BY end_time DESC';
        $list1 = $this->page->hashQuery($sql1)->result_array();
        //查询是否已经领取了优惠券
        foreach($list1 as $k=>$v){
            $have_data = $this->db->get("select * from ###_coupon_log where mid=".MID." and coupon_id=".$v['id']);
            if($have_data['mid']>0){
                $list1[$k]['is_receive'] = 1;
            }else{
                $list1[$k]['is_receive'] = 0;
            }
        }
        //商家
        $sql2 = 'SELECT * FROM ###_coupon WHERE  ' . $where.$where2 . ' ORDER BY end_time DESC';
        $list2 = $this->page->hashQuery($sql2)->result_array();
        //查询是否已经领取了优惠券
        foreach($list2 as $k=>$v){
            $have_data = $this->db->get("select * from ###_coupon_log where mid=".MID." and coupon_id=".$v['id']);
            if($have_data['mid']>0){
                $list2[$k]['is_receive'] = 1;
            }else{
                $list2[$k]['is_receive'] = 0;
            }
        }

        #异步加载
        if (isset($_GET['load'])) {
            if ($list1) {
                $content = '';
                foreach ($list1 as $v) {
                    $this->smarty->assign('m', $v);
                    $content .= $this->smarty->fetch('coupon/lbi/receive_list.html');
                }
                echo $content;
            }
            if ($list2) {
                $content = '';
                foreach ($list2 as $v) {
                    $this->smarty->assign('m', $v);
                    $content .= $this->smarty->fetch('coupon/lbi/receive_list.html');
                }
                echo $content;
            }
            die;
        }
        $this->smarty->assign('list1', $list1);
        $this->smarty->assign('list2', $list2);
        $this->smarty->display('coupon/receive_center.html');
    }
        
}