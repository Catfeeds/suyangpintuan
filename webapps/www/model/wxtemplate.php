<?php

/**


 * Class template_msg_model


 */

class wxtemplate_model extends Lowxp_Model {

	private $baseTable   = '###_wx_template';
	public $nid = array(
	    "商品购买成功通知"=>"buy_succ",
	    "订单未付款通知"=>"order_nopay",
        "订单取消通知"=>"order_cancel",
	    "订单发货通知"=>"order_ship",
	    "确认收货通知"=>"order_succ",
	    "退款申请通知"=>"refund_apply",
	    "退款结果通知"=>"refund_note",
        "拼团成功通知"=>"team_succ",
	    "拼团失败提醒"=>"team_faild",
	    "砍价成功提醒"=>"bargain_succ",
    );

	/**
	 * 获取模版消息列表 主要用来后台列表展示
	 * @param  integer $type 模版消息类型
	 * @return array         获取模版消息列表
	 */
	public function getList($params=array()) {
	    $where = " where 1 ";
	    if($params['where'])$where.=$params['where'];
		$sql = "select * from {$this->baseTable} {$where} order by id asc";
		return $this->db->select($sql);
	}

	/**
	 * 根据id 获取设置好的用户配置
	 * @param  integer $id 规则id
	 * @return array
	 */
	public function getRow($id) {
		$row = $this->db->get("SELECT * FROM {$this->baseTable} WHERE id=" . $id);
		if (!$row) {
			return array();
		}
		return $row;
	}

    /**
     * 删除
     * @param  integer $id 规则id
     * @return array
     */
    public function del($id) {
        $sql = "DELETE FROM {$this->baseTable} WHERE id=".$id;
        return $this->db->query($sql);
    }

	/**
	 * 获取微信模板
	 * @param  integer $id 规则id
	 * @return array
	 */
	public function getTMlist() {
        $list = $this->getList();
        $nid_str = join(',',array_column($list,"nid"));
        $this->load->model("wxapi");
        $res = $this->wxapi->wechat->getTMlist();

        if($res['template_list']){
            foreach($res['template_list'] as $k=>$v){
                if(strpos($nid_str, $this->nid[$v['title']])!==false)continue;
                if(!isset($this->nid[$v['title']])){
                    $data[$k]['nid'] = substr($v['template_id'],0,8);
                }else{
                    $data[$k]['nid'] = $this->nid[$v['title']];
                }
                $data[$k]['template_id'] = $v['template_id'];
                $data[$k]['title'] = $v['title'];
                $data[$k]['content'] = $v['content'];
                $data[$k]['example'] = $v['example'];
                $data[$k]['c_time'] = RUN_TIME;
            }
            if($data){
                $data = array_values($data);
                $this->db->insertAll("wx_template",$data);
            }
        }
        S('CM_WXMSG_LIST',NULL);
        return $res;
	}



	/**
	 * 处理编辑请求
	 * @param  array  $post 提交的参数
	 * @return json         编辑状态
	 */
	public function save($post) {

		$res = array(
			'error' => '0',
			'msg'   => '编辑成功',
		);

		$data = array(
			'first_data' => $post['first_data'],
			'remark_data' => $post['remark_data'],
			'status' => $post['status']
		);

		$row = $this->db->get("SELECT id FROM ###_wx_template WHERE id = " . $post['id']);
		if (empty($row['id'])) {
			$this->db->insert('wx_template', $data);
		} else {
			$this->db->update('wx_template', $data, 'id=' . $post['id']);
		}
        S('CM_WXMSG_LIST',NULL);
		return $res;
	}



	/**
	 * 模版消息入队函数
	 * @param  integer $ruleId    调用的消息规则id   为0时第三个参数请传入完整的临时规则
	 * @param  string  $targetIds 接受人的mid号  多位则逗号分隔符
	 * @param  array   $params    一维数组参数. 注意顺序有严格要求使用前先查看规则.  规则id 为0时本参数须传入临时规则 格式: array('type' => 整数, 'title' => 字符, 'content' => 字符, 'msg' => 0或1, 'sms' => 0或1, 'wechat' => 0或1, 'mail'=>0或1);
	 * @return boolean
	 */
	public function inQueue($ruleId, $targetIds, $params) {
        $data = array(
            $ruleId,
            $targetIds,
            $params,
        );

        require_once AppDir . 'library/queue/Queue.php';

		$queue = new Queue(array('key' => 'QUEUE_WXMSG'));

		$queue->put($data);
	}

	/**
	 * 批量消息入队
	 * @param  array  $data 消息队列数组   array(array($rid, $tid, $params), array($rid, $tid, $params), ...);
	 * @return void
	 */
    public function inQueueMany($data=array()) {
        require_once AppDir . 'library/queue/Queue.php';

        $queue = new Queue(array('key' => 'QUEUE_WXMSG'));

        if($data)call_user_func_array(array($queue, 'puts'), $data);
    }

	/**
	 * 批量执行队列
	 * @param  integer $count 每次出列的行数
	 * @return void
	 */
	public function dealQueue($count = 100) {
		require_once AppDir . 'library/queue/Queue.php';

		$queue = new Queue(array('key' => 'QUEUE_WXMSG'));

		$msgs = array();

		$rel = array();

		$rows = $queue->gets($count);

		if (!$rows) {
			return false;
		}

		foreach ($rows as $row) {
			if (!$row) {
				continue;
			}
            $mid = $row[0];
            $nid = $row[1];
            $this->sendTemplateMessage($mid,$nid,$row[2]);

		}

	}

    /**
     * 根据mid获取OPENID
     * @param  integer $mid 用户id   mid 为0 表示消息接受人是商户
     * @return string       微信openid
     */
    public function getWehcatByMid($mid = 0) {
        if ($mid) {

            $cacheName = 'CH_MEMBER_WECHAT_'. $mid;

            $contact = S($cacheName);
            if (!$contact) {
                $contact = $this->db->getstr('SELECT openid FROM ###_oauth WHERE type=0 and mid=' . $mid . ' limit 1');

                S($cacheName, $contact, 30);
            }

        } else {
            $contact = false;
        }

        return $contact;
    }

    /**
     * 根据nid获取template_id
     */
    public function getRowByNid($nid='') {
        if($nid=='')return false;
        $cacheName = 'CM_WXMSG_LIST';
        if(S($cacheName)){
            $res = S($cacheName);
        }else{
            $list = $this->getList(array("where"=>" and status=1"));
            foreach($list as $k=>$v){
                $res[$v['nid']] = $v;
            }
            S($cacheName,$res);
        }

        return $res[$nid];
    }

    /**
     * 发送微信消息
     * @param  mixed   $send 参数
     */
	public function sendTemplateMessage($mid,$nid,$send){
        $this->load->model("wxapi");
        $row = $this->getRowByNid($nid);
        $data['touser'] = $this->getWehcatByMid($mid);
        $data['template_id'] = $row['template_id'];
        if(empty($data['touser']) || empty($data['template_id'])){
            return false;
        }
        $data['url'] = $send['url'];
        unset($send['url']);
        $data['topcolor'] = '#FF0000';
        $temp['first']['value'] = $row['first_data'];
        //if(!empty($row['first_data']))$temp['first']['color'] = '#FF0000';
        $temp['first']['color'] = '#FF0000';
        $temp['remark']['color'] = '#FF0000';
        foreach($send as $k=>$v){
            $temp[$k]['value'] = $v;
        }
        $temp['remark']['value'] = $row['remark_data'];
        $data['data'] = $temp;
        #echo "<pre>";print_r($data);exit;
        $this->wxapi->wechat->sendTemplateMessage($data);
    }

    /**
     * 发送微信消息
     * @param  mixed   $send 参数
     */
    public function sendTest($mid,$nid,$send){
        $this->load->model("wxapi");
        $row = $this->getRowByNid($nid);
        $data['touser'] = $this->getWehcatByMid($mid);
        $data['template_id'] = $row['template_id'];
        if(empty($data['touser']) || empty($data['template_id'])){
            return false;
        }
        $data['url'] = $send['url'];
        unset($send['url']);
        $data['topcolor'] = '#FF0000';
        $temp['first']['value'] = $row['first_data'];
        if(!empty($row['first_data']))$temp['first']['color'] = '#FF0000';
        foreach($send as $k=>$v){
            $temp[$k]['value'] = $v;
        }
        $temp['remark']['value'] = $row['remark_data'];
        $data['data'] = $temp;
        echo "<pre>";print_r($data);
        $res = $this->wxapi->wechat->sendTemplateMessage($data);
        echo $this->wxapi->wechat->errCode;
        echo $this->wxapi->wechat->errMsg;
        print_r($res);exit;
    }
}