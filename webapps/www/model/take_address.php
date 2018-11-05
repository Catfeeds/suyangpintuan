<?php

/**

 * Class take_address_model

 */
class take_address_model extends Lowxp_Model
{
    public $baseTable = '###_take_address';

    /**
     * 保存|更新
     * @return array|bool|DB_Result|mysqli_result
     */
    function save()
    {
        $post = $_POST['post'];
        #表单验证
        $temp_zone = end($post['zone']);
        if(empty($post['mobile']))
            return array('code' => 10001, 'message' => '手机号码不能为空!');
        if(empty($temp_zone))
            return array('code' => 10001, 'message' => '提货地址不能为空!');
        if(empty($post['address']))
            return array('code' => 10001, 'message' => '详细地址不能为空!');
        if(!isset($post['status']) || !in_array($post['status'], array(0,1)))
            return array('code' => 10001, 'message' => '请选择状态!');
        $this->load->Model('linkage');
        $zone = end($post['zone']);
        $input = array(
            'sid' => SID,
            'mobile' => $post['mobile'],
            'zone' => $zone,
            'area' => str_replace('>', '', $this->linkage->pos_linkage($zone, false)),
            'address' => $post['address'],
            'zip' => $post['zip'],
            'status' => $post['status'],
        );
        if($post['id']){
            $result = $this->db->update($this->baseTable, $input, array('id' => $post['id'], 'sid' => SID));
            return $result!==false ? array('code' => 0, 'message' => '修改成功') : $result;
        }else{
            $result = $this->db->insert($this->baseTable, $input);
            return $result!==false ? array('code' => 0, 'message' => '添加成功') : $result;
        }
    }

    /**
     * 查询
     * @param int $id 自提地址id
     * @param int $sid 商家id
     * @return array|null
     */
    function get($id, $sid)
    {
        return $this->db->get("select * from ###_take_address where id = $id and sid = $sid");
    }

    function select($page, $sid)
    {
        $this->load->model('page');
        $_GET['page'] = intval($page);
        $this->page->set_vars(array('per' => (int)$this->common['page_listrows']));
        $sql = "select * from `###_take_address` WHERE sid = $sid";
        $list = $this->page->hashQuery($sql)->result_array();
        foreach($list as $k=>$v){
            $list[$k]['user_count'] = $this->db->getstr("select count(1) as num from ###_take_user where take_id={$v['id']} and status=1");
        }
        return $list;
    }

    /**
     * 根据商家id查询
     * @param int $sid 商家id
     * @return array
     */
    function selectBySid($sid,$where = '')
    {
        return $this->db->select("select * from ###_take_address where status=0 and sid = ".$sid.$where);
    }

    /**
     * 地址id
     * @param $id
     * @return array
     */
    function getById($id)
    {
        return $this->db->get("select * from ###_take_address where id = $id");
    }

    /**
     * 删除
     * @param int $id 自提地址id
     * @param int $sid 商家id
     * @return bool|DB_Result|mysqli_result
     */
    function delete($id, $sid)
    {
        return $this->db->delete($this->baseTable, array("id" => $id, 'sid' => $sid));
    }

    /**
     * 添加核销人员
     */
    function take_user_add()
    {
        $post = $_POST['post'];
        if(empty($post['mid']))return array('code' => 10001, 'message' => '会员不能为空!');
        if(empty($post['take_id']))return array('code' => 10002, 'message' => '请选择自提点!');
        $is_oauth = $this->db->get("select 1 from ###_oauth where mid={$post['mid']} and status=1 and `type`=0");
        if(!$is_oauth)return array('code' => 10003, 'message' => '该会员未绑定微信！');
        $is_resmem = $this->db->get("select 1 from ###_member where mid={$post['mid']} and status=1");
        if(!$is_resmem)return array('code' => 10003, 'message' => '该会员不存在！');
        if($_POST['old_take_id']!=$post['take_id']){
            $is_res = $this->db->get("select 1 from ###_take_user where mid={$post['mid']} and take_id={$post['take_id']}");
            if($is_res)return array('code' => 10004, 'message' => '该核销人员已经在自提点下！');
        }
        if($post['id']){
            $result = $this->db->update("take_user", $post, array('id' => $post['id']));
            return $result ? array('code' => 0, 'message' => '修改成功') : $result;
        }else{
            $result = $this->db->insert("take_user", $post);
            return $result ? array('code' => 0, 'message' => '添加成功') : $result;
        }
    }

    /**
     * 核销人员列表
     */
    function take_user($page,$sid)
    {
        $where = " sid = $sid ";
        $this->load->model('page');
        $_GET['page'] = intval($page);
        $take_id = isset($_GET['take_id'])?intval($_GET['take_id']):0;
        if($take_id>0){
            $where.=" and take_id=".$take_id;
        }
        $this->page->set_vars(array('per' => (int)$this->common['page_listrows']));
        $sql = "select * from `###_take_user` WHERE {$where} ";
        $list = $this->page->hashQuery($sql)->result_array();
        $list = $this->db->lJoin($list,"member","mid,username","mid","mid");
        $list = $this->db->lJoin($list,"member_detail","mid,photo","mid","mid");
        $list = $this->db->lJoin($list,"take_address","id,area,address","take_id","id","take_");
        return $list;
    }

    /**
     * 核销人员详情
     */
    function take_user_one($id=0)
    {
        $sql = "select p1.*,p2.username from `###_take_user` as p1 left join `###_member` as p2 on p1.mid=p2.mid WHERE p1.id = $id";
        $row = $this->db->get($sql);
        return $row;
    }

    /**
     * 删除核销人员
     */
    function take_user_del($id)
    {
        return $this->db->delete("take_user", array("id" => $id));
    }

}