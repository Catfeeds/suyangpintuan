<?php

/**
 * Class goods_model
 */
class goods_model extends Lowxp_Model {

    private $baseTable = '###_goods';
    private $baseTableAtta = '###_goods_additional';

    public $list_total = 0;

    public function __construct() {
        
    }

    // 获取商品配置
    protected function _Config($name = '') {
        static $_c;
        if (empty($_c)) {
            $_c = $this->setting->value();
        }
        if (!empty($name)) {
            return isset($_c[$name]) ? $_c[$name] : false;
        } else {
            return $_c;
        }
    }

    /** 获取商品列表
     * @param int $size
     * @param int $page
     * @param int $cid 商品分类
     * @param int $type 1时令模式
     * @param array $options
     */
    function getList($size = 10, $page = 1, $cid = 0, $type = 0, $options = array()) {
        $list = array();
        $options = array_merge(array(
            'order' => 'g.listorder asc,g.id asc', //排序
            'where0' => 'and g.is_sale=1 and g.status=0 and g.stock>0', //固定条件
            'where' => '', //可变条件
            'q' => '', //关键词
            'url' => '', //分页链接（参数）
            'target' => '', //分布链接描点
                ), $options);

        //sql初始条件
        $where = " where 1 ";
        if ($options['where0']) {
            $where .= $options['where0'] . " ";
        }
        if ($options['where']) {
            $where .= $options['where'] . " ";
        }

        //筛选商品分类
        if ($cid) {
            $this->load->model('goodscat');
            $where .= " AND g.cid" . $this->goodscat->condArrchild($cid);
        }

        #按关键词搜索
        if (isset($options['q']) && !empty($options['q'])) {
            $where .= " AND (g.name LIKE '%" . htmlspecialchars(trim($options['q'])) . "%' OR ad.keywords LIKE '%" . htmlspecialchars(trim($options['q'])) . "%') ";
        }

        #排序
        $order = " order by " . $options['order'] . " ";

        #分页
        $this->load->model('page');
        $_GET['page'] = intval($page);
        $this->page->set_vars(array('per' => $size));

        #公共SQL
        if (isset($options['q']) && !empty($options['q'])) {
        	$sqlPublic = " from " . $this->baseTable . " as g LEFT JOIN " .$this->baseTableAtta . " as ad  ON g.id=ad.goods_id" . $where . $order;
        }else{
        	$sqlPublic = " from " . $this->baseTable . " as g " . $where . $order;
        }

        #size=0时返回数量
        if (!$size) {
            $sql = "select count(distinct g.id) " . $sqlPublic;
            $count = $this->db->getstr($sql);
            return $count;
        }

        #内容列表结果
        $urlQuery = isset($options['urlQuery']) ? $options['urlQuery'] : "$cid/";
        $target = isset($options['target']) ? $options['target'] : "";
        $sql = "select distinct g.id,g.*,g.sell+g.sales_num as sell_num " . $sqlPublic;
        $res = $this->page->hashQuery($sql, $urlQuery, $target)->result_array();
        $this->list_total = $this->page->pages['total'];

        #格式化内容
        foreach ($res as $val) {
            //$val        = $this->getGoodsCart($val);
            $val = $this->getThumb($val);
            $val['url'] = $options['url'] ? $options['url'] : "/goods/show/" . $val['id'];
            $val['type_name'] = goods_typeid($val['typeid']);

            //标记搜索词
            $val['name_highlight'] = $val['name'];
            if (isset($options['q']) && !empty($options['q'])) {
                $replace_to_array = '<font style="color:#f03;">' . $options['q'] . '</font>';
                $val['name_highlight'] = str_replace($options['q'], $replace_to_array, $val['name_highlight']);
            }

            $list[] = $val;
        }
        return $list;
    }

    /** 商品详细
     * @param $id
     * @param $options
     * @return array|bool|null
     */
    function get($id, $options = array('cart' => true)) {
        //sql初始条件
        $where = " where g.id='$id' and g.is_sale=1 and g.status=0 ";

        $sql = "select g.* from " . $this->baseTable . " as g " . $where;
        $row = $this->db->get($sql);
        if (empty($row)) {
            return false;
        }

        //格式化内容
        #关联购物车数量
        /*if ($options['cart']) {
            $row = $this->getGoodsCart($row);
*/
        #产品规格 Feng 2016-07-20 start
		if (!empty($options['spec'])) {
            $sql = "select price,cost as cost_price,stock,team_price from ###_goods_item where goods_id={$id} and spec='{$options['spec']}'";
            $spec = $this->db->get($sql);
			if ($spec) {
                $row = array_merge($row, $spec);
			}
            $row['goods_spec'] = $this->getSpec($row['sp_val'],$options['spec']);            
        }
        #产品规格 Feng 2016-07-20 end
        #图片相关
        $row = $this->getThumb($row);
        $row = $this->getThumb($row, 2);
        #商品参数
        $row['params'] = !empty($row['params']) ? json_decode($row['params'], true) : array();

        # 附表数据 Feng 2016-04-25 start
        if ($id) {
            $_attr = $this->db->get("SELECT * FROM ###_goods_additional WHERE goods_id=" . $id);
            if (is_array($_attr)) {
                unset($_attr['id'], $_attr['goods_id']);
                foreach ($_attr as $a_k => $a_v) {
                    $row[$a_k] = $a_v;
                }
                unset($_attr);
            }
        }
        # 附表数据 Feng 2016-04-25 end
        
        //AA团 计算每人多少钱
        if($row['typeid']==CART_AA){
            $row['team_price_total'] = $row['team_price'];
            $row['team_price'] = round($row['team_price']/$row['team_num'],2);
        }
        //自选团和阶梯团 获取阶梯价格
        if($row['typeid']==CART_STEP){
            $row['step'] = json_decode($row['step'],true);
            $row['step_array'] = array_combine($row['step']['team_num'], $row['step']['team_price']);
            arsort($row['step']['team_num']);
            arsort($row['step']['team_price']);
            $row['team_num'] = end($row['step']['team_num']);
            $row['team_num_max'] = reset($row['step']['team_num']);
            $row['team_price'] = reset($row['step']['team_price']);
        }elseif($row['typeid']==CART_OPTION){
            $row['step'] = json_decode($row['step'],true);
            $row['step_array'] = array_combine($row['step']['team_num'], $row['step']['team_price']);
            asort($row['step']['team_num']);
            asort($row['step']['team_price']);
            $row['team_num'] = end($row['step']['team_num']);
            $row['team_num_max'] = reset($row['step']['team_num']);
            $row['team_price'] = reset($row['step']['team_price']);
        }
        //商家商品 获取商家名称
        if($row['sid']>0){
            $row['store_name'] = $this->db->getstr("select name from ###_business where id={$row['sid']}","name");
        }
        #echo "<pre>";print_r($row);exit;
        return $row;
    }

    /** 关联商品的购物车数据
     * @param $val
     */
    function getGoodsCart($val) {
        $val['cart_num'] = 0;
        //获取购物车内容  Feng 2016-05-30 start
        $this->load->model("flow");
        $this->cart_goods = $this->flow->cart_goods();
        //获取购物车内容  Feng 2016-05-30 end
        $cart_goods = $this->cart_goods;
        if ($cart_goods) {
            foreach ($cart_goods as $k => $v) {
                if ($v['goods_id'] == $val['id'] && $v['type'] == 1 && $v['obj_id'] == 0) {
                    $val['cart_id'] = $v['id'];
                    if ($_SESSION['mid']) {
                        if ($v['mid'] == $_SESSION['mid']) {
                            $val['cart_num'] = $v['qty'];
                        }
                    } else {
                        $val['cart_num'] = $v['qty'];
                    }
                }
            }
        }
        return $val;
    }

    /** 重新获取
     * @param array $val 一元数组
     * @param int $type 1返回主图 2返回展示图集
     */
    function getThumb($val, $type = 1) {
        $thumb = isset($val['thumb']) ? json_decode($val['thumb'], true) : array();
        $thumbs = isset($val['thumbs']) ? json_decode($val['thumbs'], true) : array();
        if ($type == 2) {
            if ($thumbs) {
                foreach ($thumbs as $k => $v) {
                    //$val['img_thumb'][] = $v['path'];
                    //展示图获取middle图片
                    $val['img_thumb'][] = yunurl(str_replace('_src', '_middle', $v['path']));
                }
            }
            return $val;
        } else {
            if ($thumb[0]['path']) {
                $val['img_src'] = yunurl($thumb[0]['path']);
            }
            if ($thumbs) {
                if ($thumbs[0]['path']) {
                    $val['img_cover'] = yunurl($thumbs[0]['path'],300);
                }
            }
            return $val;
        }
    }

    /**
     *
     * 保存数据
     * -- 商品发布数据提交处理方法
     *
     * @access  public
     * @version v0.0.0.1
     * @author  ChenJL <chenjl@lnest.com>
     *
     * @return  array   describe
     */
    public function save() {
        $_PostData = $_POST['post'];
        $_SaveData = array();
        ### 数据重置 ###
        $_goodsAttach = $_PostData['attach'];
        unset($_PostData['attach']);
        if (0 == $_PostData['id']) {
            unset($_PostData['id']);
        }
        $_goods = $_PostData;
        unset($_PostData);
        //cid
        if(isset($_goods['cid']) && !empty($_goods['cid'])){
            $_goods['cid'] = array_filter($_goods['cid']);
            $_goods['cid'] = end($_goods['cid']);
        }
        //aid
        if(isset($_goods['aid']) && !empty($_goods['aid'])){
            $_goods['aid'] = array_filter($_goods['aid']);
            $_goods['aid'] = end($_goods['aid']);
        }
        //bid
        if(isset($_goods['bid']) && !empty($_goods['bid'])){
            $_goods['bid'] = array_filter($_goods['bid']);
            $_goods['bid'] = end($_goods['bid']);
        }
        //nation_id
        if(isset($_goods['nation_id']) && !empty($_goods['nation_id'])){
            $_goods['nation_id'] = array_filter($_goods['nation_id']);
            $_goods['nation_id'] = end($_goods['nation_id']);
        }

		if(!isset($_goods['is_best'])){
        	$_goods['is_best'] = 0;
        }
        
        if(!isset($_goods['is_new'])){
        	$_goods['is_new'] = 0;
        }
        
        if(!isset($_goods['is_hot'])){
        	$_goods['is_hot'] = 0;
        }
		
        ### 必填项处理 ###
        if (empty($_goods['name'])) {
            return array('code' => 10001, 'message' => '请输入商品名称!');
        }
        //秒杀，免费试用，抽奖 有时间限制
        $_goods['start_time'] = !empty($_goods['start_time'])?strtotime($_goods['start_time']):'';
        $_goods['end_time'] = !empty($_goods['end_time'])?strtotime($_goods['end_time']):'';
        if (empty($_goods['id']) && ($_goods['typeid']==CART_KILL || $_goods['typeid']==CART_LUCK || $_goods['typeid']==CART_FREE)){
			if (empty($_goods['start_time']) || empty($_goods['end_time'])) {
				return array('code' => 10001, 'message' => '请选择时间!');
			}
			if (($_goods['typeid'] == CART_LUCK || $_goods['typeid'] == CART_FREE) && empty($_goods['luck_num'])) {
				return array('code' => 10001, 'message' => '请填写中奖人数!');
        }
		}
        if (empty($_goods['cid'])) {
            return array('code' => 10001, 'message' => '请选择商品分类!');
        }
        /*if (empty($_goods['cost_price'])) {
           return array('code' => 10001, 'message' => '请输入成本价!');
		*/
		if($_goods['typeid']==CART_EXCHANGE){
        	$_goods['price'] = $_goods['price_exchange'];
        	$_goods['team_num'] = 2;
        	unset($_goods['price_exchange']);
        }
		
        if (empty($_goods['price'])) {
            return array('code' => 10001, 'message' => '请输入本站价!');
        }        
        if ($this->base->validate($_goods['market_price'], 'number') == false) {
            return array('code' => 10001, 'message' => '价格必须是大于等于0的数字!');
        }
        if ($this->base->validate($_goods['price'], 'number') == false) {
            return array('code' => 10001, 'message' => '促销价必须是大于等于0的数字!');
        }
        if ($this->base->validate($_goods['team_price'], 'number') == false && $_goods['typeid']>0) {
            return array('code' => 10001, 'message' => '团购价必须是大于等于0的数字!');
        }
        if (intval($_goods['team_num'])<=1 && $_goods['typeid']>0) {
            return array('code' => 10001, 'message' => '参团人数必须大于1');
        }
        $_goods['bid'] = empty($_goods['bid']) ? 0 : intval($_goods['bid']);
        $_goods['goods_type'] = isset($_POST['goods_type']) ? $_POST['goods_type'] : 0;

        ### 处理快递方式和自提点 ###
        if(is_array($_goods['express'])){
            $_goods['express'] = join(",",$_goods['express']);
        }else{
            $_goods['express'] = '';
        }
        if(is_array($_goods['take_address'])){
            $_goods['take_address'] = join(",",$_goods['take_address']);
        }else{
            $_goods['take_address'] = '';
        }
        if(is_array($_goodsAttach['goods_tip']) && count($_goodsAttach['goods_tip'])>=3){
            $_goodsAttach['goods_tip'] = join(",",$_goodsAttach['goods_tip']);
        }else{
            return array('code' => 10001, 'message' => '标签不能少于三项');
        }
        ### 重复处理 ###
        $_where = isset($_goods['id']) ? ' and id != ' . $_goods['id'] : '';
        $res = $this->db->select("select * from " . $this->baseTable . " where name='" . $_goods['name'] . "'" . $_where);
        if ($res) {
            return array('code' => 10003, 'message' => '商品名称已经存在，请更换!');
        }

        ### 主表goods数据处理 ###
        # 规格值
        $_goods['sp_val'] = (isset($_POST['sp_val']) && is_array($_POST['sp_val'])) ? json_encode($_POST['sp_val']) : '';
        # 阶梯价格
        if(isset($_POST['step'])){
            $temp_array = array_unique(array_combine($_POST['step']['team_num'],$_POST['step']['team_price']));
            ksort($temp_array);
            unset($_POST['step']['team_num']);
            unset($_POST['step']['team_price']);
            $i=1;
            foreach($temp_array as $k=>$v){
                $_POST['step']['team_num'][$i] = $k;
                $_POST['step']['team_price'][$i] = $v;
                $i++;
            }
            $_goods['step'] = (isset($_POST['step']) && is_array($_POST['step'])) ? json_encode($_POST['step']) : '';
        }
        #print_r($_POST['step']);exit;
        # 添加时间
        $_goods['u_time'] = RUN_TIME;
        if (!isset($_goods['id'])) {
            $_goods['c_time'] = RUN_TIME;
        }
        $_goods['unit'] = isset($_goods['unit']) && !empty($_goods['unit']) ? $_goods['unit'] : $this->_Config('goods_unit');
        # 处理缩略图
        if (isset($_goods['thumb']) && !empty($_goods['thumb'])) {
            $_arrData = array();
            foreach ($_goods['thumb']['path'] as $k => $v) {
                $_arrData[$k]['path'] = $v;
                $_arrData[$k]['title'] = $_goods['thumb']['title'][$k];
            }
            $_goods['thumb'] = json_encode($_arrData);
        } else {
            return array('code' => 10003, 'message' => '请上传缩略图!');
        }
        # 处理图集
        if (isset($_goods['thumbs']) && !empty($_goods['thumbs'])) {
            $_arrData = array();
            foreach ($_goods['thumbs']['path'] as $k => $v) {
                $_arrData[$k]['path'] = $v;
                $_arrData[$k]['title'] = $_goods['thumbs']['title'][$k];
            }
            $_goods['thumbs'] = json_encode($_arrData);
        } else{
            return array('code' => 10003, 'message' => '请至少上传一张展示图集!');
        }
        # 处理分享图片
        if (isset($_goods['share_img']) && !empty($_goods['share_img'])) {
            $_arrData = array();
            foreach ($_goods['share_img']['path'] as $k => $v) {
                $_arrData[$k]['path'] = $v;
                $_arrData[$k]['title'] = $_goods['share_img']['title'][$k];
            }
            $_goods['share_img'] = json_encode($_arrData);
        }
        if (isset($_arrData)) {
            unset($_arrData);
        }
        #处理所在地
        if(isset($_goods['areaid']) && !empty($_goods['areaid'])){
            $_goods['areaid'] = array_filter($_goods['areaid']);
            if(count($_goods['areaid'])>=3){
                $_goods['areaid'] = $_goods['areaid'][1];
            }else{
                $_goods['areaid'] = end($_goods['areaid']);
            }
        }

        # 主表数据保存
        if (isset($_goods['id'])) {
            $res = $this->db->save($this->baseTable, $_goods, '', array('id' => intval($_goods['id'])));
        } else {
            $res = $this->db->save($this->baseTable, $_goods);
        }
        if (!$res) {
            return array('code' => 10002, 'message' => '数据操作失败!');
        }

        if($_goods['typeid']==CART_LUCK || $_goods['typeid']==CART_FREE){
            $goods_id = !isset($_goods['id'])?$res:$_goods['id'];
            $is_lottery = $this->db->get("select 1 from ###_lottery where act_id={$goods_id}");
            if ($is_lottery == false){
                $this->db->insert("lottery", array("act_id" => $goods_id, "typeid" => $_goods['typeid'], "luck_num" => $_goods['luck_num'], "e_time" => $_goods['end_time']));
            }else{
                $this->db->update("lottery", array("typeid" => $_goods['typeid'], "luck_num" => $_goods['luck_num'], "e_time" => $_goods['end_time']),array("act_id" => $goods_id));
            }
        }

        #Feng 2016-07-18 规格管理 start 
        if (isset($_POST['sp_stock']) && is_array($_POST['sp_stock'])) {
            $spCost = $_POST['sp_cost'];
            $spStock = $_POST['sp_stock'];
            $spSerial = $_POST['sp_serial'];
            $spThumb = $_POST['sp_thumb'];
            $spPrice = $_POST['sp_price'];
            $spTeamPrice = $_POST['sp_teamprice'];
            $this->db->delete('goods_item', array('goods_id' => $_goods['id']));

            foreach ($spStock as $spec => $stock) {
                $cost = $spCost[$spec];
                $serial = $spSerial[$spec];
                //$stock = $spStock[$spec];
                $price = $spPrice[$spec];
                $teamprice = $spTeamPrice[$spec];
                $thumb = '';
                $_arrData = array();
                foreach ($spThumb[$spec]['path'] as $k => $v) {
                    $_arrData[$k]['path'] = $v;
                    $_arrData[$k]['title'] = $spThumb[$spec]['title'][$k];
                }
				if (count($_arrData) > 0) {
                    $thumb = json_encode($_arrData);
				}
                    $_goodsId = isset($_goods['id']) ? $_goods['id'] : $res;
                    $this->db->insert('goods_item', array(
                    'goods_id' => $_goodsId,
                    'spec' => $spec,
                    'price' => $price,
                    'cost' => $cost,
                    'team_price' => $teamprice,
                    'stock' => $stock,
                    'serial' => $serial,
                    'thumb' => $thumb,
                ));
            }
        }
        #Feng 2016-07-18 规格管理 end 
        #Feng 2016-05-18 处理属性 start
        if ((isset($_POST['attr_id_list']) && isset($_POST['attr_value_list'])) || (empty($_POST['attr_id_list']) && empty($_POST['attr_value_list']))) {
            $_goodsId = isset($_goods['id']) ? $_goods['id'] : $res;

            // 取得原有的属性值
            $goods_attr_list = array();

            $sql = "SELECT g.*, a.attr_type FROM ###_goods_attr AS g LEFT JOIN ###_attribute AS a ON a.attr_id = g.attr_id WHERE g.goods_id = '{$_goodsId}' ";

            $res_list = $this->db->select($sql);

            foreach ($res_list as $k => $v) {
                $goods_attr_list[$v['attr_id']][$v['attr_value']] = array('sign' => 'delete', 'goods_attr_id' => $v['goods_attr_id']);
            }
            // 循环现有的，根据原有的做相应处理
            if (isset($_POST['attr_id_list'])) {
                foreach ($_POST['attr_id_list'] AS $key => $attr_id) {
                    $attr_value = $_POST['attr_value_list'][$key];
                    $attr_price = $_POST['attr_price_list'][$key];
                    if (!empty($attr_value)) {
                        if (isset($goods_attr_list[$attr_id][$attr_value])) {
                            // 如果原来有，标记为更新
                            $goods_attr_list[$attr_id][$attr_value]['sign'] = 'update';
                            $goods_attr_list[$attr_id][$attr_value]['attr_price'] = $attr_price;
                        } else {
                            // 如果原来没有，标记为新增
                            $goods_attr_list[$attr_id][$attr_value]['sign'] = 'insert';
                            $goods_attr_list[$attr_id][$attr_value]['attr_price'] = $attr_price;
                        }
                    }
                }
            }

            /* 插入、更新、删除数据 */
            foreach ($goods_attr_list as $attr_id => $attr_value_list) {
                foreach ($attr_value_list as $attr_value => $info) {
                    if ($info['sign'] == 'insert') {
                        $sql = "INSERT INTO ###_goods_attr (attr_id, goods_id, attr_value, attr_price)" .
                                "VALUES ('$attr_id', '$_goodsId', '$attr_value', '$info[attr_price]')";
                    } elseif ($info['sign'] == 'update') {
                        $sql = "UPDATE ###_goods_attr SET attr_price = '$info[attr_price]' WHERE goods_attr_id = '$info[goods_attr_id]' LIMIT 1";
                    } else {
                        $sql = "DELETE FROM ###_goods_attr WHERE goods_attr_id = '$info[goods_attr_id]' LIMIT 1";
                    }
                    $this->db->query($sql);
                }
            }
        }
        #Feng 2016-05-18 处理属性 end
        ### 附表goods_additional数据处理 ###
        # 商品编号
        $_goodsAttach['goods_no'] = isset($_goodsAttach['goods_no']) ? $_goodsAttach['goods_no'] : '';
        # 运费
        /* if( !isset($_goodsAttach['freight_payer']) ){
          $_goodsAttach['freight_payer'] = 0;
          if( $_goodsAttach['freight_payer'] == 0 ){
          $_goodsAttach['freight_value'] = 0;
          }
         */
        # 满件包邮
        /* if( !isset($_goodsAttach['full_num_mail']) ){
          $_goodsAttach['freight_payer'] = 0;
         */
        # 库存是否展示
        /* if( !isset($_goodsAttach['is_stock']) ){
          $_goodsAttach['is_stock'] = 1;
         */
        # 销量是否展示
        /* if( !isset($_goodsAttach['is_sell']) ){
          $_goodsAttach['is_sell'] = $this->_Config('goods_isSold');
         */
        # 商品返佣是否开启 1开启，2关闭
        //        if( !isset($_goodsAttach['is_state']) ){
        //            $_goodsAttach['is_state'] = 1;
        //        }
        #配送区域
        if(is_array($_goods['linkage_id'])){
            $_goodsAttach['linkage_id'] = join(",",$_goods['linkage_id']);
        }else{
            $_goodsAttach['linkage_id'] = '';
        }
        # 佣金处理
        $_ConFigSuperiorBroker = $this->_Config('goods_superiorBroker');
        $_ConFigSuperiorBroker = explode("\r", $_ConFigSuperiorBroker);
        foreach ($_goodsAttach['superior_brokerage'] as $goods_attr_k => $goods_attr_v) {
            if (is_array($goods_attr_v)) {
                $_tamp = explode('|', trim($_ConFigSuperiorBroker[$goods_attr_k]));
                $i = 0;
                foreach ($goods_attr_v as $g_a_k => $g_a_v) {
                    //$_goodsAttach['superior_brokerage'][$goods_attr_k][$g_a_k] = !empty($g_a_v) ? intval($g_a_v) : $_tamp[$i];
                    $_goodsAttach['superior_brokerage'][$goods_attr_k][$g_a_k] = intval($g_a_v); #模板已经有默认
                    $i++;
                }
            }
        }
        unset($i);
        $_goodsAttach['superior_brokerage'] = json_encode($_goodsAttach['superior_brokerage']);
        # 附表数据保存
        if ($res && !isset($_goods['id'])) {
// 新增
            $_goodsAttach['goods_id'] = $res;
            $this->db->delete($this->baseTableAtta, array('goods_id' => $res));
            $res = $this->db->save($this->baseTableAtta, $_goodsAttach);
        } elseif (isset($_goods['id'])) {
            // 更新
            $res = $this->db->save($this->baseTableAtta, $_goodsAttach, '', array('goods_id' => $_goods['id']));

        } else {
            return array('code' => 10002, 'message' => '数据操作失败!');
        }


        if ($_goods['id']) {
            admin_log('更新商品：' . $_goods['name']);
            return array('code' => 0, 'type' => 'update', 'message' => '更新成功');
        } else {
            admin_log('添加商品：' . $_goods['name']);
            return array('code' => 0, 'type' => 'insert', 'message' => '添加成功');
        }
    }

    /**
     * @param int $id
     * @param int $type 1返回checkbox数组；2返回key; 3根据ids返回数组
     * @return array
     */
    function getFourYear($id = 0, $type = 0) {
        $list = array();
        $array = array(
            1 => array('id' => 1, 'title' => '春'),
            2 => array('id' => 2, 'title' => '夏'),
            3 => array('id' => 3, 'title' => '秋'),
            4 => array('id' => 4, 'title' => '冬'),
        );
        if ($type == 1) {
            foreach ($array as $k => $v) {
                $list[$k] = $v['title'];
            }
        } elseif ($type == 2) {
            $list = array_keys($array);
        } elseif ($type == 3) {
            if ($id) {
                $idArr = explode(',', $id);
                foreach ($idArr as $v) {
                    if ($array[$v]) {
                        $list[$v] = $array[$v];
                    }
                }
            } else {
                $list = $array;
            }
        } else {
            $list = $array;
        }
        return $list;
    }

    /**
     * 商品规格格式化.按spec_item_id排序(从小到大)
     * @param $specstr
     * @return string
     */
    function spec_format($specstr) {
        $arr = explode('-', $specstr);
        if (count($arr)) {
            foreach ($arr as $k => $val) {
                $arr[$k] = intval($val);
            }

            sort($arr);
            $spec = implode('-', $arr);
        } else {
            $spec = $specstr;
        }
        return $spec;
    }

    /**
     *
     * 属性表(goods_spec、goods_spec_item)增删改
     *
     * $this->goods->specFormatOperate('add',array('type'=>'spec','name'=>'属性名称')); // 添加属性
     * $this->goods->specFormatOperate('add',array('type'=>'item','value'=>'属性值名称','spec_id'=>'属性ID','order'=>0)); // 添加属性值
     *
     * $this->goods->specFormatOperate('edit',array('type'=>'spec','name'=>'属性值名称','spec_id'=>'属性ID')); // 规格属性名称修改
     *
     * $this->goods->specFormatOperate('del',array('type'=>'spec','id'=>'属性ID')); // 删除属性、值
     * $this->goods->specFormatOperate('del',array('type'=>'item','id'=>'属性值ID')); // 删除属性值
     */
    function specFormatOperate($type, $data) {
        switch ($type) {
            case 'add':
                if ('item' == $data['type'] && !empty($data['value'])) {
                    $_id = $this->db->insert('goods_spec_item', array('value' => $data['value'], 'spec_id' => $data['spec_id'], 'order' => 0));
                    return $_id;
                } else {
                    $_id = $this->db->insert('goods_spec', array('name' => (empty($data['name']) ? '规格名称' : $data['name']), 'c_time' => time()));
                    return $_id;
                }
                break;
            case 'edit':
                if ('spec' == $data['type'] && !empty($data['spec_id'])) {
                    $data['name'] = empty($data['name']) ? '规格名称' : $data['name'];
                    $this->db->update('goods_spec', array('name' => $data['name']), array('id' => $data['spec_id']));
                }
                break;
            case 'del':
                if ('spec' == $data['type'] && !empty($data['id'])) {
                    $this->db->delete('goods_spec', array('id' => $data['id']));
                    $this->db->delete('goods_spec_item', array('spec_id' => $data['id']));
                } else {
                    $this->db->delete('goods_spec_item', array('id' => $data['id']));
                }
                break;
        }
    }

    /**
     * 获取商品规格 Feng 2016-07-21
     * @param $sp_val 商品列表sp_val自动
     * @param $spec   选中的规格id：1-3
     * @return string 返回规格名和值：容量:100ml 颜色:黑
     */
    function getSpec($sp_val = '', $spec = '', $join = ' ') {
		if ($sp_val == '' || $spec == '') {
            return '';
		}
        $sp_val = json_decode($sp_val, true);
        $res = $this->db->select("select id,name from ###_goods_spec ");
        $sp_list = array_combine(array_column($res, "id"), array_column($res, "name"));
        $sp_arr = array();
        foreach ($sp_val as $key => $val) {
            foreach ($val as $k => $v) {
                $sp_arr[$k] = $sp_list[$key] . ":" . $v;
            }
        }
        $spec_str = '';
        $spec_arr = explode("-", $spec);
        foreach ($spec_arr as $key => $val) {
            $spec_str.=$join . $sp_arr[$val];
        }
        return ltrim($spec_str, $join);
    }

    /**
     * 检查商品是否满足条件
     */
    function check_goods($cart,$post){

        $row = $this->get($cart['goods_id']);
        $cart['goods_cid'] = $row['cid'];
        $cart['coupon_id'] = $row['coupon_id'];
        $cart['sid'] = $row['sid'];
        $cart['team_num'] = $cart['team_num']>0?$cart['team_num']:$row['team_num'];#自选团 传组团人数过来
        if($post['num']!=$cart['qty']){
            $cart['qty'] = $_SESSION['cart']['qty'] = $post['num'];
            $cart['subtotal'] = formatPrice($cart['goods_price'] * $cart['qty']);
        }
        if($cart['qty']<=0){
            $this->error = "商品数量不能为0";
            return false;
        }

        if($row['stock']<$cart['qty']){
            $this->error = "库存不够";
            return false;
        }
        //检查配送区域
        if(!empty($row['linkage_id'])){
            if($cart['common_id']==0 || ($cart['common_id']>0 && $cart['type']!=CART_AA)){
                $address_id = isset($_POST['address_id']) ? intval($_POST['address_id']) : 0;
                if(!empty($address_id)){
                    $area = $this->db->getstr("SELECT l.arrparentid FROM ###_member_address as m,###_linkage as l WHERE m.id = '$address_id' AND m.mid = ".MID." AND m.zone=l.id");
                    $area_arr = explode(",",$area);
                    $goods_linkage =  explode(",",$row['linkage_id']);
                    $same_area = array_intersect($goods_linkage,$area_arr);
                    if(empty($same_area)){
                        $this->error = "该商品不支持此区域配送";
                        return false;
                    }
                }
            }
        }
		if ($cart['type'] == CART_GOODS) {
			return array_merge($row, $cart);
		}

        if($row['limit_buy_bumber']>0 && $row['limit_buy_bumber']<$cart['qty']){
            $this->error = "每人限购".$row['limit_buy_bumber']."件";
            return false;
        }

        if($row['limit_buy_one']==1){
            $is_has = $this->db->get("select 1 from ###_goods_order where extension_id={$cart['goods_id']} and status_pay>0 and mid=".MID);
            if($is_has){
                $this->error = "该商品每人限购一次";
                return false;
            }
        }

		if ($cart['type'] == CART_KILL) {
//秒杀判断是否有库存
            if($row['stock']<=0){
                $this->error = "该商品已抢光";
                return false;
            }
        }
		if (in_array($cart['type'], array(CART_KILL, CART_LUCK, CART_FREE))) {
//秒杀，免费试用，抽奖到期不能再参团
            if($row['end_time']<=RUN_TIME){
                $this->error = "该活动已结束";
                return false;
            }
        }

        if($cart['common_id']>0){
            if($cart['type']!=CART_STEP ){
                $common_order = $this->db->get("select * from ###_goods_order_common where team_num>team_num_yes and id=".$cart['common_id']);
                if($common_order==false){
                    $this->error = "该团已满";
                    return false;
                }
            }elseif($cart['type']==CART_STEP && $row['team_limit']){
                $common_order = $this->db->get("select team_num_yes from ###_goods_order_common where id=".$cart['common_id']);
                if($common_order['team_num_yes']>=$row['team_num_max']){
                    $this->error = "该团已满";
                    return false;
                }
            }
		} elseif ($cart['type'] != CART_GOODS) {
//直购除外 团长优惠
			if ($row['discount_type'] == 1) {
				$cart['subtotal'] = 0;
			}
//团长免单
			if ($row['discount_type'] == 2 && $row['discount_amount'] > 0) {
//团长优惠
                $cart['subtotal']=$cart['subtotal']-$row['discount_amount'];
                $cart['subtotal'] = $cart['subtotal']<0?0:$cart['subtotal'];
            }
        }
        return array_merge($row,$cart);
    }

    /**
     * 通过id查询商品
     * @param $id int|array 商品id
     * @param string $select 查询字段
     * @return array
     */
    public function selectById($id, $select = "*"){
        if(is_array($id)){
            $id = implode(',', $id);
            $where = "id in ({$id})";
        }else{
            $where = "id = $id";
        }
        $sql = "select {$select} from $this->baseTable where {$where}";
        return $this->db->select($sql);
    }

    /**
     * 获取拼团快报
     * @return array
     */
    public function getSquare(){
        $sql = "select mid,common_id,extension_code,extension_id,square_desc,square_time from ###_goods_order where status_common = 1 AND common_id > 0 AND square_time>0 limit 20";
        $list = $this->db->select($sql);
        $list = $this->db->lJoin($list,"goods","id,name","extension_id","id","goods_");
        return $list;
    }
    /**
     * 判断一个商品是否是秒杀
     * @return boolean
     */
    public function is_wkill($id = 0){
        if($id==0){
            return false;
        }
        //判断是否是专题报名产品
        $is_res = $this->db->getstr("select 1 from ###_topic_goods where status=1 and goods_id={$id}");
        if(!$is_res){
            return false;
        }
        $now_ids = $well_ids = $is_now = $is_well = "";
        $now = $this->db->select("select id from ###_topic where typeid=5 and start_time<=".RUN_TIME." and end_time>=".RUN_TIME,"id");
        if($now){
            $now_ids = join(',',array_column($now,"id"));
            $is_now = $this->db->getstr("select 1 from ###_topic_goods where status=1 and act_id in ($now_ids) and goods_id={$id}");
        }

        $well = $this->db->select("select id from ###_topic where typeid=5 and start_time>".RUN_TIME,"id");
        if($well){
            $well_ids = join(',',array_column($well,"id"));
            $is_well = $this->db->getstr("select 1 from ###_topic_goods where status=1 and act_id in ($well_ids) and goods_id={$id}");
        }
        if($is_well && !$is_now){
            return true;
        }
        return false;

    }



}
