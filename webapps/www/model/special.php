<?php

class special_model extends Lowxp_Model
{
    public $baseTable1 = '###_special';
    public $baseTable2 = '###_special_model';

    /**
     * 获取专题模板信息
     * @param $sid int 商家id
     * @return array
     */
    public function selectModel($sid){
        return $this->db->select("select * from {$this->baseTable2} where sid = $sid");
    }

    /**
     * 获取专题模板信息
     * @param $id int 模板id
     * @return array|null
     */
    public function getByIdModel($id){
        return $this->db->get("select * from {$this->baseTable2} where id = $id");
    }

    /**
     * 获取专题模板信息
     * @param $id int 模板id
     * @param $sid int 商家id
     * @return array|null
     */
    public function getByIdSidModel($id, $sid){
        return $this->db->get("select * from {$this->baseTable2} where id = $id and sid = $sid");
    }

    /**
     * 获取专题信息
     * @param $id int 专题id
     * @param $sid int 商家id
     * @return array|null
     */
    public function getByIdSid($id, $sid){
        return $this->db->get("select * from {$this->baseTable1} where id = $id and sid = $sid");
    }

    /**
     * 获取专题信息
     * @param $id
     * @return array|null
     */
    public function getById($id){
        return $this->db->get("select * from {$this->baseTable1} where id = $id");
    }

    /**
     * 通过模板id获取专题信息
     * @param $special_model_id
     * @return array|null
     */
    public function getBySpecialModelId($special_model_id){
        return $this->db->get("select * from {$this->baseTable1} where special_model_id = $special_model_id");
    }

    /**
     * 删除专题
     * @param $id
     * @param string $sid
     * @return bool|DB_Result|mysqli_result
     */
    public function delete($id, $sid = ''){
        $condition['id'] = $id;
        if(!empty($sid)){
            $condition['sid'] = $sid;
        }
        return $this->db->delete($this->baseTable1, $condition);
    }

    /**
     * 删除模板
     * @param $id
     * @param string $sid
     * @return bool|DB_Result|mysqli_result
     */
    public function deleteModel($id, $sid = ''){
        $special = $this->getBySpecialModelId($id);
        if($special){
            return array('code' => 10001, 'message' => '专题中正使用此模板，无法删除');
        }
        $special_model = $this->getByIdSidModel($id, $sid);
        $condition['id'] = $id;
        if(!empty($sid)){
            $condition['sid'] = $sid;
        }
        $file = dirname(__DIR__).'/views/special/'.$special_model['file_name'];
        if(!unlink($file)){
            return false;
        }
        $result = $this->db->delete($this->baseTable2, $condition);
        return $result ? array('code' => 0, 'message' => '删除成功') : $result;
    }

    /**
     * 保存|更新专题
     * @param $sid
     * @return array|bool|DB_Result|mysqli_result
     */
    public function save($sid){
        $post = $_POST['post'];
        #表单验证
        $post['special_model_id'] = intval($post['special_model_id']);
        if(empty($post['special_model_id'])){
            return array('code' => 10001, 'message' => '请选择专题模板');
        }
        $post['title'] = trim($post['title']);
        if(empty($post['title'])){
            return array('code' => 10001, 'message' => '请输入标题');
        }
        $post['goods_id'] = array_filter($post['goods_id']);
        if(count($post['goods_id']) == 0){
            return array('code' => 10001, 'message' => '请至少选择一个商品');
        }
        $this->load->model('goods');
        $goods = $this->goods->selectById($post['goods_id'], 'id,is_sale');
        if(!$goods){
            return array('code' => 10001, 'message' => '你输入的商品id一个也不存在');
        }
        $id_arr = array_column($goods, 'id');
        $diff_id = array_diff($post['goods_id'], $id_arr);
        if(count($diff_id) > 0){
            $diff_id = implode('、',$diff_id);
            return array('code' => 10001, 'message' => "商品id为【{$diff_id}】不存在");
        }
        $error_id = array();
        foreach($goods as $v){
            if($v['is_sale'] == 0){
                $error_id[] = $v['id'];
            }
        }
        if(count($error_id) > 0){
            $error_id = implode('、',$error_id);
            return array('code' => 10001, 'message' => "商品id为【{$error_id}】已下架");
        }

        #保存专题
        $title = $post['title'];
        $goods_id = implode(',', $post['goods_id']);
        $special_model_id = $post['special_model_id'];
        $status = $post['status'];
        $id = $post['id'];
        unset($post['id'],$post['title'],$post['goods_id'],$post['special_model_id'],$post['status']);
        $input = array(
            'title' => $title,
            'goods_id' => $goods_id,
            'special_model_id' => $special_model_id,
            'config_value' => json_encode($post),
            'status' => $status,
            'sid' => $sid,
        );
        if($id){
            $result = $this->db->update($this->baseTable1, $input, array('id' => $id, 'sid' => $sid));
            return $result ? array('code' => 0, 'message' => '修改成功') : $result;
        }else{
            $result = $this->db->insert($this->baseTable1, $input);
            return $result ? array('code' => 0, 'message' => '添加成功') : $result;
        }
    }

    /**
     * 保存|更新专题模板
     * @param $sid
     * @return array|bool|DB_Result|mysqli_result
     */
    public function saveModel($sid){
        $F = $_FILES['file'];
        $post = $_POST['post'];
        if(empty($post['filename']) || !preg_match("/^\w{1,30}$/", $post['filename'])){
            return array('code' => 10001, 'message' => '请输入正确的文件名称');
        }

        #上传文件处理
        if($F['type'] != "text/html"){
            return array('code' => 10001, 'message' => '请添加模板');
        }
        $filename = $F['name'];
        $tmp_name = $F['tmp_name'];
        if (!is_uploaded_file($tmp_name)) {
            return array('code' => 10001, 'message' => '上传失败');
        }
        $special_model = $this->db->select("select * from {$this->baseTable2}");
        foreach($special_model as $v){
            if($v['file_name'] == $filename){
                return array('code' => 10001, 'message' => '文件已存在');
            }
        }
        $path = dirname(__DIR__).'/views/special';
        if (!is_dir($path))
            @mkdir($path, 0777, true);
        $filename = $post['filename'].".html";
        $target = $path."/".$filename;
        move_uploaded_file($tmp_name, $target);

        #对配置的处理
        $config = array();
        foreach($post['key'] as $k=>$v){
            if(!preg_match("/^[A-z]{1,20}$/", $v)){
                return array('code' => 10001, 'message' => '请输入正确的模板的值');
            }
            if(empty($post['label'][$k])){
                return array('code' => 10001, 'message' => '请输入标签');
            }
            if(empty($post['type'][$k])){
                return array('code' => 10001, 'message' => '请选择类型');
            }
            $config[$v]['label'] = $post['label'][$k];
            $config[$v]['type'] = $post['type'][$k];
            $config[$v]['tip'] = $post['tip'][$k];
        }

        $input = array(
            'file_name' => $filename,
            'sid' => $sid,
            'config' => json_encode($config)
        );
        if($post['id']){
            $result = $this->db->update($this->baseTable2, $input, array('id' => $post['id'], 'sid' => $sid));
            return $result ? array('code' => 0, 'message' => '修改成功') : $result;
        }else{
            $result = $this->db->insert($this->baseTable2, $input);
            return $result ? array('code' => 0, 'message' => '添加成功') : $result;
        }
    }
}