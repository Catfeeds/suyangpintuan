<?php/** * Class express_model */class express_model extends Lowxp_Model {	private $baseTable = '###_express';	function __construct() {}    public $shiplist = array(        "huitongkuaidi"=>"百世汇通",        "shunfeng"=>"顺丰速递",        "zhongtong"=>"中通速递",        "yuantong"=>"圆通速递",        "shentong"=>"申通快递",        "tiantian"=>"天天快递",        "yunda"=>"韵达快运",        "ems"=>"EMS",        "debangwuliu"=>"德邦物流",        "anxindakuaixi"=>"安信达",        "baifudongfang"=>"百福东方",        "bangsongwuliu"=>"邦送物流",        "chuanxiwuliu"=>"传喜物流",        "datianwuliu"=>"大田物流",        "dsukuaidi"=>"D速快递",        "disifang"=>"递四方",        "feikangda"=>"飞康达物流",        "feikuaida"=>"飞快达",        "rufengda"=>"凡客如风达",        "fengxingtianxia"=>"风行天下",        "feibaokuaidi"=>"飞豹快递",        "ganzhongnengda"=>"港中能达",        "guotongkuaidi"=>"国通快递",        "guangdongyouzhengwuliu"=>"广东邮政",        "gongsuda"=>"共速达",        "huiqiangkuaidi"=>"汇强快递",        "tiandihuayu"=>"华宇物流",        "hengluwuliu"=>"恒路物流",        "huaxialongwuliu"=>"华夏龙",        "haiwaihuanqiu"=>"海外环球",        "haimengsudi"=>"海盟速递",        "huaqikuaiyun"=>"华企快运",        "haihongwangsong"=>"山东海红",        "jiajiwuliu"=>"佳吉物流",        "jiayiwuliu"=>"佳怡物流",        "jiayunmeiwuliu"=>"加运美",        "jinguangsudikuaijian"=>"京广速递",        "jixianda"=>"急先达",        "jinyuekuaidi"=>"晋越快递",        "jietekuaidi"=>"捷特快递",        "jindawuliu"=>"金大物流",        "jialidatong"=>"嘉里大通",        "kuaijiesudi"=>"快捷速递",        "kangliwuliu"=>"康力物流",        "kuayue"=>"跨越物流",        "lianhaowuliu"=>"联昊通",        "longbanwuliu"=>"龙邦物流",        "lanbiaokuaidi"=>"蓝镖快递",        "longlangkuaidi"=>"隆浪快递",        "quanchenkuaidi"=>"全晨快递",        "quanjitong"=>"全际通",        "quanritongkuaidi"=>"全日通",        "quanyikuaidi"=>"全一快递",        "quanfengkuaidi"=>"全峰快递",        "sevendays"=>"七天连锁",        "santaisudi"=>"三态速递",        "shenghuiwuliu"=>"盛辉物流",        "suer"=>"速尔物流",        "shengfengwuliu"=>"盛丰物流",        "shangda"=>"上大物流",        "tonghetianxia"=>"通和天下",        "youshuwuliu"=>"优速物流",        "wanjiawuliu"=>"万家物流",        "wanxiangwuliu"=>"万象物流",        "weitepai"=>"微特派",        "xinbangwuliu"=>"新邦物流",        "xinfengwuliu"=>"信丰物流",        "neweggozzo"=>"新蛋奥硕物流",        "hkpost"=>"香港邮政",        "yuntongkuaidi"=>"运通快递",        "youzhengguonei"=>"邮政小包",        "yuanchengwuliu"=>"远成物流",        "yafengsudi"=>"亚风速递",        "yibangwuliu"=>"一邦速递",        "zhaijisong"=>"宅急送",        "zhongyouwuliu"=>"中邮物流",    );	//保存数据	function save() {		$post = $_POST['post'];		$post['sid'] = SID;		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;		#表单处理		if (empty($post['name'])) {return array('code' => 10001, 'message' => '请输入物流公司名称!');}		if (empty($post['pinyin'])) {return array('code' => 10001, 'message' => '请输入物流公司标识!');}		#重复处理		$where = $id ? " and id != $id and sid = $post[sid]" : " and sid = $post[sid]";		$res   = $this->db->select("select * from " . $this->baseTable . " where name='" . $post['name'] . "' "  . $where);		if ($res) {return array('code' => 10003, 'message' => '快递公司名称已经存在，请更换!');}		$res = $this->db->select("select * from " . $this->baseTable . " where pinyin='" . $post['pinyin'] . "' " . $where);		if ($res) {return array('code' => 10003, 'message' => '快递标识已经存在，请更换!');}		#保存		$where = $id ? array('id' => $id) : '';		$res   = $this->db->save($this->baseTable, $post, '', $where);		if (empty($res)) {return array('code' => 10002, 'message' => '数据操作失败!');} //未知错误		if ($post['id']) {			admin_log('编辑快递公司：' . $post['name']);			return array('code' => 0, 'type' => 'update', 'message' => '更新成功');		} else {			admin_log('添加快递公司：' . $post['name']);			return array('code' => 0, 'type' => 'insert', 'message' => '添加成功');		}	}	//保存数据	function saveExpressShipping(){		$post = $_POST['p'];		if(empty($post['express_id'])){			return array('code' => 10001, 'message' => '参数异常!');		}		if(empty($post['name'])){			return array('code' => 10001, 'message' => '请输入配送区域名称!');		}		if(empty($post['config']['cs_weight'])){			return array('code' => 10001, 'message' => '请输入初始重量!');		}		if(empty($post['config']['xf_weight'])){			return array('code' => 10001, 'message' => '请输入每KG续费!');		}		if(empty($post['config']['cs_price'])){			return array('code' => 10001, 'message' => '请输入初始费用!');		}		if(empty($post['linkage_id'])){			return array('code' => 10001, 'message' => '请选择所辖地区!');		}		$input['name'] = $post['name'];		$input['express_id'] = $post['express_id'];		$input['linkage_id'] = implode(',', $post['linkage_id']);		$post['config']['cs_weight'] = formatPrice($post['config']['cs_weight']);		$post['config']['cs_price'] = formatPrice($post['config']['cs_price']);		$post['config']['xf_weight'] = formatPrice($post['config']['xf_weight']);		$input['config'] =  serialize($post['config']);		$input['sid'] = SID;		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;		$where = $id ? array('id' => $id) : '';		$res = $this->db->save('###_express_shipping', $input, '', $where);		if (empty($res)) {return array('code' => 10002, 'message' => '数据操作失败!');} //未知错误		$express = $this->db->get("select `name` from $this->baseTable WHERE id = {$input['express_id']}");		if ($id) {			admin_log('编辑' . $express['name'] .'公司区域：' . $post['name']);			return array('code' => 0, 'type' => 'update', 'message' => '更新成功');		} else {			admin_log('添加' . $express['name'] .'公司区域：' . $post['name']);			return array('code' => 0, 'type' => 'insert', 'message' => '添加成功');		}	}	/**	 * 获取快递公司	 * @param $id	 * @param $sid	 * @return array|null	 */	function getExpressByIdSid($id, $sid){		return $this->db->get("select * from $this->baseTable WHERE id = $id AND sid = $sid");	}	/**	 * 获取快递公式区域信息	 * @param $id	 * @param $sid	 * @return array|null	 */	function getExpressShippingById($id, $sid){		return $this->db->get("select * from ###_express_shipping WHERE id = $id and sid = $sid");	}	function selectExpressShipping($where = '', $select = '*'){		return $this->db->select("select $select from ###_express_shipping where $where");	}}