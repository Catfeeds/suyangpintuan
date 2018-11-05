<?php

/**
 * Class cart_model
 * 购物车模型
 */
class cart_model extends Lowxp_Model {

	public $baseTable = '###_cart';
	function __construct() {}

	/** 更新或添加到购物车
	 * @param $data
	 */
	function add($data) {
		$where   = array();
		$addtime = strtotime('-1 days'); #购物车过期时间

		$sql  = "SELECT addtime FROM " . $this->baseTable . " WHERE mid='" . $data['mid'] . "' AND cart_type='" . $data['cart_type'] . "'";
		$cart = $this->db->get($sql);
		if (!empty($cart)) {
			$whereTmp = array(
				'mid'       => $data['mid'],
				'cart_type' => $data['cart_type'],
			);
			#一天内该会员该订单类型有保存购物车
			if ($cart['addtime'] > $addtime) {
				$where = $whereTmp;
			} else {
				$this->db->delete('cart', $whereTmp);
			}
		}
		$this->db->save($this->baseTable, $data, '', $where);
	}

	/** 修改购物车
	 * @param $data
	 * @param $where
	 */
	function update($data, $where) {
		$this->db->save($this->baseTable, $data, '', $where);
	}

	/** 查询购物车列表
	 * @param $mid
	 * @param int $type
	 */
	function select($mid, $type = CART_WIN) {
		$sql     = "";
		$addtime = strtotime('-1 days'); #购物车过期时间

		if ($type == CART_WIN) {
			$sql = "SELECT c.*,a.cod,a.act_id FROM " . $this->baseTable . " AS c " .
				"LEFT JOIN ###_auction_log AS a ON a.log_id=c.extension_id " .
				"WHERE c.mid='$mid' AND c.cart_type='$type' AND addtime>'$addtime'";
		} elseif ($type == CART_AUC) {
			$sql = "SELECT c.*,a.act_id FROM " . $this->baseTable . " AS c " .
				"LEFT JOIN ###_goods_activity AS a ON a.act_id=c.extension_id " .
				"WHERE c.mid='$mid' AND c.cart_type='$type' AND addtime>'$addtime'";
		} else {
			$sql = "SELECT * FROM " . $this->baseTable . " WHERE mid='$mid' AND cart_type='$type' AND addtime>'$addtime'";
		}
		return $this->db->select($sql);
	}

	/** 清除购物车
	 * @param $mid
	 * @param int $type 购物车类型
	 */
	function clear($mid, $type = CART_WIN) {
		$this->db->delete($this->baseTable, array('mid' => $mid, 'cart_type' => $type));
	}

	/** 购物车统计
	 * @param $cart
	 */
	function cartTotal($cart) {
		$data = array('pay_points' => 0);
		foreach ($cart as $v) {
			$sql      = "SELECT ext_info FROM ###_goods_activity WHERE act_id='" . $v['act_id'] . "'";
			$ext_info = $this->db->getstr($sql);
			$ext_info = unserialize($ext_info);
			$data['pay_points'] += $ext_info['painum'];
		}
		return $data;
	}

}