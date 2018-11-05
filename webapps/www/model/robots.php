<?php

/**
 * Name 机器人
 */
class robots_model extends Lowxp_Model{

    /**
     * 随机获取机器人
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

    /**
     * 快速参团
     * id 团ID
     * num 参团人数
     */
    function join_team($id=0){
        if($id==0){
            return false;
        }
        $this->load->model("order");
        //跳过AA团和阶梯团
        $row = $this->db->get("select * from ###_goods_order_common where id={$id} and status=1 and goods_typeid != ".CART_AA." and goods_typeid!=".CART_STEP );
        //获取系统会员
        $num = $row['team_num']-$row['team_num_yes'];
        $list = $this->get_robots($num);
        if(count(array_filter($list))<$num)return false;

        //免费试用和抽奖添加开奖信息
        if ($row['goods_typeid'] == CART_FREE || $row['goods_typeid'] == CART_LUCK) $lottery_status = 1;
        //阶梯团修改价格
        if($row['extension_code'] == CART_STEP){
            $new_price = $this->order->common_price($row);
        }

        /*if ($row['goods_typeid'] == CART_STEP) {
            $this->load->model("goods");
            $goods = $this->goods->get($row['goods_id']);
            if ($goods['team_limit'] && $goods['team_num_max'] <= $row['team_num_yes'] + 1) {
                $this->db->update("goods_order_common", "team_num=team_num_yes+1,team_num_yes=team_num_yes+1,status=10,u_time=" . RUN_TIME, array("id" => $row['id']));
                $this->db->update("goods_order", array("status_common" => TEAM_SUCC, 'status_lottery' => $lottery_status), "common_id={$row['id']} and status_pay>0");
                //阶梯团修改最后价格
                $order_common['team_price'] = $new_price;
                $this->order->common_step($row);
                //组团成功发送消息
                $this->common_succ_step($row['id'], $row);
            } else {
                $this->db->update("goods_order_common", "team_num=team_num_yes+1,team_num_yes=team_num_yes+1", array("id" => $row['id']));
                $this->db->update("goods_order", array("status_common" => TEAM_ING), "common_id={$row['id']} and status_pay>0");
            }
        } else {*/
            $this->db->update("goods_order_common", "team_num_yes=team_num,status=10,u_time=" . RUN_TIME, array("id" => $row['id']));
            $this->db->update("goods_order", array("status_common" => TEAM_SUCC, 'status_lottery' => $lottery_status), "common_id={$row['id']} and status_pay>0");

            foreach($list as $k=>$v){
                $order[$k]['mid'] = $v;
                $order[$k]['c_time'] = RUN_TIME;
                $order[$k]['extension_code'] = $row['goods_typeid'];
                $order[$k]['extension_id'] = $row['goods_id'];
                $order[$k]['is_robots'] = 1;
                $order[$k]['order_amount'] = $row['team_price'];
                $order[$k]['goods_amount'] = $row['team_price'];
                $order[$k]['amount'] = 0;
                $order[$k]['status_pay'] = $row['goods_typeid']==CART_STEP?1:10;
                $order[$k]['status_common'] = 10;
                $order[$k]['order_sn'] = $this->order_sn();
                $order[$k]['sid'] = $row['sid'];
                $order[$k]['common_id'] = $row['id'];
            }
            $this->db->insertAll('goods_order', $order);

            //组团成功发送消息
            $this->order->common_succ($row['id'], $row);
        //}
    }

    //生成订单号
    function order_sn() {
        $order_sn = date('YmdHis').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(md5(microtime(true)),true), 7, 13), 1))), 0, 8);
        return $order_sn;
    }


}