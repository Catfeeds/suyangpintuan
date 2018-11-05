<?php

/**
 * Name 虚拟数据
 */
class robots extends Lowxp {

	function __construct() {
        parent::__construct();
    }

    /**
     * 批量添加虚拟数据
     */
    function add(){
        if(isset($_POST['Submit'])){
            $this->load->model('member');
            $robots = $_POST['robots'];
            $robots = explode("\r",$robots);
            $robots = array_map(trim,$robots);
            if(is_array($robots)){
                foreach($robots as $k=>$v){
                    $post_array = explode("|",$v);
                    $post_array = array_map(trim,$post_array);
                    if(count($post_array)!=3)continue;
                    $post['is_robots'] = 1;
                    $post['username'] = $post_array[0];
                    $post['nickname'] = $post_array[1];
                    $post['mobile'] = $post_array[2];
                    $this->member->create_user($post);
                }
            }
            $this->tip("操作成功！", array('inIframe' => true));
            $this->exeJs("parent.com.xhide();parent.main.refresh()");
        }
        $this->smarty->display('manage/robots/add.html');
    }

    /**
     * 快速开团
     */
    function team(){

        #商品信息
        $id = intval($_REQUEST['id']);
        $this->load->model("goods");
        $row = $this->goods->get($id);

        #获取自选团和阶梯团 人数和价格
        if(in_array($row['typeid'],array(CART_STEP,CART_OPTION))){
            $temp = each($row['step_array']);
            $row['team_price'] = $temp['value'];
            $row['team_num'] = $temp['key'];
        }
        #echo "<pre>";print_r($row);exit;
        if (empty($row)) {
            $this->tip("您的选择的商品有误！", array('inIframe' => true));exit;
        }

        if(isset($_POST['Submit'])){
            $num = $_POST['num'];
            $member_arr = $this->get_robots($num);
            foreach($member_arr as $k=>$v){
                $this->create_order($v,$row);
            }
            $this->tip("操作成功！", array('inIframe' => true));
            $this->exeJs("parent.com.xhide();parent.main.refresh()");
        }

        $this->load->model('order');
        $row['typeid_name'] = $this->order->actTypes[$row['typeid']]['title'];

        $this->smarty->assign("row",$row);
        $this->smarty->display('manage/robots/team.html');
    }
    /**
     * 虚拟数据下单
     */
    function create_order($mid,$goods, $buy_num = 1) {
        if($mid==false)return false;

        $this->load->model('order');
        $this->load->model('flow');

        $order = array(
            'mid' => $mid,
            'c_time' => RUN_TIME,
        );

        //购物车
        $order['extension_code'] = $goods['typeid'];
        $order['extension_id'] = $goods['id'];
        $order['is_robots'] = 1;

        // 订单总价
        $order['order_amount'] = $goods['team_price']*$buy_num;
        // 商品总价
        $order['goods_amount'] = $order['order_amount'];

        // 未支付金额 扣除
        $order['amount'] = 0;

        //阶梯团定金
        if($goods['typeid']==CART_STEP){
            $order['pre_amount'] = $goods['deposit']*$buy_num;
            $order['amount'] = $total['flowTotal'] = $order['pre_amount'];
        }

        if($cart['type']==CART_STEP){
            $order['status_pay'] = 1;
        }else{
            $order['status_pay'] = 10;
        }

        //订单号
        $order['order_sn'] = $order_sn = $this->flow->order_sn();

        //商家id
        $order['sid'] = $goods['sid'];
        $order_id = $order['id'] = $order['order_id'] = $this->db->save('goods_order', $order);
        if (!$order_id) {
           return false;
        }

        $data_goods = array(
            'mid' => $mid,
            'order_id' => $order_id,
            'cost_price' => $goods['cost_price'],
            'good_id' => $goods['id'],
            'goods_name' => $goods['name'],
            'buy_num' => $buy_num,
            'sell_price' => $goods['team_price'],
            'c_time' => time(),
            'type' => $goods['typeid'],
            'team_num' => $goods['team_num'],
        );
        $this->db->save('goods_order_item', $data_goods);

        //添加拼团
        $data = array();
        $data['mid'] = $mid;
        $data['goods_id'] = $goods['id'];
        $data['goods_typeid'] = $goods['typeid'];
        $data['team_num'] = $goods['option_team_num']>0?$goods['option_team_num']:$goods['team_num'];
        $data['team_num_yes'] = 1;
        $data['team_price'] = $goods['team_price'];
        $data['c_time'] = RUN_TIME-rand(100,999);
        $team_day = !empty($goods['team_day'])?$goods['team_day']:0;#成团时间 单位天
        $team_hour = !empty($goods['team_hour'])?$goods['team_hour']:0;#成团时间 单位小时
        $end_time = $team_day*TEAM_TIME+$team_hour;
        $data['e_time'] = RUN_TIME + $end_time * 3600-rand(100,999);
        $data['sid'] = $goods['sid'];
        $data['is_robots'] = 1;
        $common_id = $this->db->insert("goods_order_common", $data);
        $order['common_id'] = $common_id;

        //免费试用和抽奖添加开奖信息
        if ($goods['typeid'] == CART_FREE || $goods['typeid'] == CART_LUCK) {
            $lottery_status = 1;
            $is_lottery = $this->db->get("select 1 from ###_lottery where act_id={$goods['id']}");
            if ($is_lottery == false) $this->db->insert("lottery", array("act_id" => $goods['id'], "typeid" => $goods['typeid'], "luck_num" => $goods['luck_num'], "e_time" => $goods['end_time']));
        }

        //更新订单信息
        $this->db->update("goods_order", array("common_id" => $common_id, "status_common" => TEAM_ING, 'status_lottery' => $lottery_status), array("id" => $order['id']));
        return $common_id;

    }
    /**
     * 随机获取虚拟数据
     */
    function get_robots($num = 1){
        $num = $num==0?1:abs(intval($num));
        $sql = "SELECT mid FROM zz_member WHERE  is_robots=1 and status=1";
        $res = $this->db->select($sql);
        $res = array_column($res,'mid');
        if(count($res)<=$num){
            $array = $res;
        }else{
            $res = array_flip($res);
            $array = array_rand($res,$num);
        }
        if($num==1)$array = array($array);
        return $array;
    }

}