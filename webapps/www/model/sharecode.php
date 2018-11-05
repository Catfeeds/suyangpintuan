<?php

/**
 * Class sharecode_model
 */
class sharecode_model extends Lowxp_Model{
    private $baseTable = '###_sharecode';
    function __construct(){}

    /**随机分享码
     * @param $mid
     * @param int $value
     * @return string
     */
    function code($mid,$value=0){
        $salt = $this->db->getstr("SELECT salt FROM ###_member WHERE mid = '$mid'");
        $code = md5($mid.$salt.$value);
        return substr($code,0,5);
    }

    /**
     * 创建分享码
     * @param $mid
     * @param $value
     * @return string
     */
    function creat_code($mid,$value){
        $member = $this->db->get("SELECT m.username,m.rank_id,r.allow_time FROM ###_member AS m LEFT JOIN ###_member_rank AS r ON m.rank_id = r.id WHERE m.mid = '$mid'");
        $inser_arr = array();
        $inser_arr['mid'] = $mid;
        $inser_arr['username'] = $member['username'];
        $inser_arr['code'] = $this->code($mid,$value);
        $inser_arr['value'] = $value;
        $inser_arr['used_time'] = 0;
        $inser_arr['allow_time'] = !empty($member['allow_time_time']) ? $member['allow_time_time'] : $this->site_config['allow_time'];
        $inser_arr['add_time'] = RUN_TIME;
        $this->db->insert($this->baseTable,$inser_arr);
        return $inser_arr['code'];
    }
}