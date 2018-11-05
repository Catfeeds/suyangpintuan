<?php

/**
 * Class posid_model
 */
class posid_model extends Lowxp_Model{

    private $baseTable = '###_posid';

    function __construct(){}

    //保存数据
    function save($post){

        #表单处理
        if(empty($post['name'])){ return array('code'=>10001, 'message'=>'请输入推荐位名称!'); }

        #重复处理
        $where = $post['id'] ? ' and id!='.$post['id'] : '';
        $res = $this->db->select("select * from ".$this->baseTable." where name='".$post['name']."'".$where);
        if($res){ return array('code'=>10003, 'message'=>'推荐位名已经存在，请更换!'); }

        #保存
        $where = $post['id'] ? array('id'=>(int) $post['id']) : '';
        $res = $this->db->save($this->baseTable,$post,'',$where);

        if(empty($res)){ return array('code'=>10002, 'message'=>'数据操作失败!'); } //未知错误
        if($post['id']){
            admin_log('编辑推荐位：'.$post['name']);
            return array('code'=>0, 'type'=>'update', 'message'=>'更新成功');
        }else{
            admin_log('添加推荐位：'.$post['name']);
            return array('code'=>0, 'type'=>'update', 'message'=>'添加成功');
        }

    }

}