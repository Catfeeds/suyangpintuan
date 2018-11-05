<?php
/**
 * Class exchange_model
 */
class exchange_model extends Lowxp_Model {
	public $power = 0;//总开关

    function __construct() {
        if (file_exists(AppDir . 'config/version_score.php')) {
            include AppDir . 'config/version_score.php';
        }
        if (defined("Version_score")) {
            $this->power = 1;
        }
    }

	/*
	 * 这里退还积分，与使用积分
	 */
	function action($param){
		//扣除积分
		$this->load->model('score');
		
		$res = array(
				'error' => 1,
		);
		if($param['type']==1){
			$remark = "您的订单（sn:".$param['order_sn']."）取消，退回积分：".$param['score'];
			$score = $param['score'];
		}elseif($param['type']==2){
			$remark = "您的订单（sn:".$param['order_sn']."）退款/退货，退回积分：".$param['score'];
			$score = $param['score'];
		}else{
			$score_info = $this->score->getTotal(MID);
			if ($score_info['total_left'] < $param['score']) {
				$res['msg'] = '对不起,您的积分不够';
				exit(json_encode($res));
			}
			$score = -$param['score'];
			$remark = "积分兑换使用积分：".$param['score']."，订单（sn:".$param['order_sn']."）";
		}
		//定义type=6
		if (!$this->score->scoreLog(array('mid' => $param['mid'], 'amount' =>$score, 'remark' => $remark,'type'=>6))) {
			// 积分扣除错误
		
			$res['msg'] = '系统繁忙,请稍后重试';
			exit(json_encode($res));
		}
	}
	/**
	 * 添加第三方支付需要发送的积分记录
	 */
	function score_record($order){
		//积分计算
		$this->load->model('score');
		$rule = $this->score->getRow(5);
		$insert_arr = array();
		$insert_arr['mid'] = $order['mid'];
		$insert_arr['orderid'] = $order['id'];
		$insert_arr['order_sn'] = $order['order_sn'];
		$insert_arr['amount'] = $order['money_paid'];
		$insert_arr['score'] = round($order['money_paid']*$rule['config']['percent']/100);//积分取整、四舍五入
		$insert_arr['percent'] = $rule['config']['percent'];
		$insert_arr['addtime'] = RUN_TIME;
		$insert_arr['status'] = 0;//预留字段，后期如果要把退单时时计算进来，需要根据退单步骤时时统计
		//插入之前需要判断该订单是否已经有数据，避免重复操作
		$is_data = $this->db->get("select * from ###_goods_order_score where orderid={$order['id']}");
		if(empty($is_data)&&$insert_arr['score']>0&&$rule['status']==1){
			$this->db->insert('goods_order_score',$insert_arr);
		}
		 
	}
}