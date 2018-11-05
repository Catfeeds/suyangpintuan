<?php

/**
 * take_verify_code
 */
class take_verify_code_model extends Lowxp_Model
{
    public $baseTable = '###_take_verify_code';

    public function getById($id , $select = '*', $mid = ''){
        if(!empty($mid)){
            $sql = "select $select from $this->baseTable where id = $id and mid = $mid";
        }else{
            $sql = "select $select from $this->baseTable where id = $id";
        }
        return $this->db->get($sql);
    }

    public function getByVerifyCode($verifyCode, $select="*"){
        $sql = "select $select from $this->baseTable where verify_code = $verifyCode";
        return $this->db->get($sql);
    }
}