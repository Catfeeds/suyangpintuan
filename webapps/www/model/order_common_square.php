<?php

/**
 * 拼团订单广场
 */
class order_common_square_model extends Lowxp_Model
{
    public $baseTable = '###_goods_order_common_square';

    /**
     * @param $common_id
     * @return array|null
     */
    function getSquareByCommonId($common_id){
        $sql = "select * from $this->baseTable where common_id = $common_id";
        return $this->db->get($sql);
    }
}