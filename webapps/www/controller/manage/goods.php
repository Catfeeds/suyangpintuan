<?php

/**
 * Name 产品管理
 * Class product_adm
 */
class goods extends Lowxp {

    function __construct() {
        #按钮
        $this->btnMenu = array(
            0 => array('url' => '#!goods/index', 'name' => '商品管理'),
            1 => array('url' => '#!goods/edit', 'name' => '添加商品'),
            //2=>array('url'=>'#!goods/edit?type=gift&com=xshow|添加礼盒','name'=>'添加礼盒'),
        );

        parent::__construct();
        $this->load->model('goodscat');
        $this->load->model('brand');
        $this->load->model('goods');
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

    /**
     * IndexAction
     */
    function index($page = 1) {
        #检索
        $conds = $this->getCondtions(0);
        $condtions = $conds['where'];
        $orderby = $conds['order'];
        $excel = (isset($_GET['excel']) && $_GET['excel'] == 1) ? 1 : 0;

        #分页
        $this->load->model('page');
        $_GET['page'] = intval($page);
        $this->page->set_vars(array('per' => (int) $this->common['page_listrows']));

        $sql = "SELECT *,sell+sales_num as sell_num FROM ###_goods WHERE " . $condtions . " ORDER BY " . $orderby;

        if ($excel == 1) {
            $data['list'] = $this->db->select($sql);
            return $data['list'];
        }else{
            $data['list'] = $this->page->hashQuery($sql)->result_array();
        }


        //获取下拉分类
        $select_categorys = $this->goodscat->category_option(isset($_GET['cid']) ? $_GET['cid'] : 0);
        $this->smarty->assign('select_categorys', $select_categorys);
        #获取下拉品牌
        $select_brands = $this->brand->category_option(isset($_GET['bid']) ? $_GET['bid'] : 0);
        $this->smarty->assign('select_brands', $select_brands);
        
        $this->load->model("order");
        //判断积分兑换是否开启
        $this->load->model('exchange');
        if($this->exchange->power){
        	$actTypes = $this->order->actTypes+$this->order->actTypes_exchange;
        }else{
        	$actTypes = $this->order->actTypes;
        }

        $this->smarty->assign('select_type', $actTypes);
        if ($data['list']) {
            foreach ($data['list'] as $k => $v) {
                $v = $this->goods->getThumb($v);
                $v['img_src'] = str_replace('_src', '_thumb', $v['img_src']);
                //$v['array_fs'] = $this->goods->getFourYear($v['fs'],3);
                //商品分类
                $v['cat_name_str'] = $this->goodscat->get_catnamestr($v['cid']);
                $v['type_name'] = $actTypes[$v['typeid']]['title'];
                $data['list'][$k] = $v;                
            }
        }

        $this->smarty->assign('data', $data);
        $this->smarty->display('manage/goods/list.html');
    }

    function edit() {
        //提交
        if (isset($_POST['Submit'])) {
            $res = $this->goods->save();
            //添加商品刷新缓存
            $this->base->clear_caches();
            if (isset($res['code']) && $res['code'] == 0) {
                $this->tip($res['message'], array('inIframe' => true));
                if($_POST['post']['sid']>0){
                    $this->exeJs("parent.window.location='/manage#!goods/business_goods';");
                }else{
                    $this->exeJs("parent.window.location='/manage#!goods/index';");
                }
            } else {
                $this->tip($res['message'], array('inIframe' => true, 'type' => 1));
            }
            exit;
        }

        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $type = isset($_GET['type']) ? trim($_GET['type']) : '';
        $row = array();

        //编辑
        if ($id) {
            $row = $this->db->get("SELECT * FROM ###_goods WHERE id=" . $id);
            $this->smarty->assign('id', $id);
            if(!empty($row['step']))$row['step'] = json_decode($row['step'], true);

        }

        //获取下拉分类
        /*$select_categorys = $this->goodscat->category_option(isset($row['cid']) ? $row['cid'] : 0);
        $this->smarty->assign('select_categorys', $select_categorys);*/
        //获取下拉同城分类
        $row['areaid'] = !empty($row['areaid'])?$row['areaid']:0;
        $select_areacat = $this->area_option($row['areaid'],$row);
        $this->smarty->assign('select_areacat', $select_areacat);

        #获取下拉品牌
        /*$select_brands = $this->brand->category_option(isset($row['bid']) ? $row['bid'] : 0);
        $this->smarty->assign('select_brands', $select_brands); */
        #获取下拉海淘
        /*$this->load->model("nation");
        $select_nation = $this->nation->category_option(isset($row['nation_id']) ? $row['nation_id'] : 0);
        $this->smarty->assign('select_nation', $select_nation);*/
        #获取下拉国家馆
        $this->load->model("country");
        $country_nation = $this->country->category_option(isset($row['country_id']) ? $row['country_id'] : 0);
        $this->smarty->assign('select_country', $country_nation);

        #获取下拉优惠券
        $this->load->model("coupon");
        $coupon_list = $this->coupon->getAvailableCouponList(" and sid=0 ");
        $this->smarty->assign('coupon_list', $coupon_list);
        #获取商品规格
        $spec_select = $this->db->select("select id,name from ###_goods_spec");
        $this->smarty->assign('spec_select', $spec_select);

        $sid = isset($row['sid']) ? $row['sid'] : 0;
        #快递方式
        $row['express_list'] = $this->db->select("select * from ###_express where status = 1 AND sid = $sid order by listorder asc,id asc");
        #自提点
        $this->load->model("take_address");
        $row['take_address_list'] = $this->take_address->selectBySid($sid);
        #配送区域
        $row['linkage_list'] = $this->db->select("select id,name from ###_linkage where parentid = 1");
        #商品标签
        $this->load->model("goods_tag");
        $row['goods_tag'] = $this->goods_tag->getList();

        #echo "<pre>";print_r($row);exit;
        //Feng 2016-05-18 start  商品属性
        /*$this->load->model("goodstype");
        $cat_list = $this->goodstype->get_cat_list();
        $this->smarty->assign("cat_list", $cat_list);
        $this->smarty->assign('goods_attr_html', $this->build_attr_html($row['goods_type'], $row['id']));*/
        //Feng 2016-05-18 end
        //Feng 2016-04-18 start 判断分销佣金是否开启
        $comss = C('comss'); #分销佣金总开关
        if ($comss && $id == 0) {
            #获取佣金设置数据
            $_ConFigSuperiorBroker = $this->_Config('goods_superiorBroker');
            $_ConFigSuperiorBroker = explode("\r", $_ConFigSuperiorBroker);
            $newSuperiorBroker = array();
            foreach ($_ConFigSuperiorBroker as $SuperiorBroker) {
                $newSuperiorBroker[] = explode("|", trim($SuperiorBroker));
            }
            unset($_ConFigSuperiorBroker);
            $this->smarty->assign('SuperiorBroker', $newSuperiorBroker);
        }
        #分销商佣金级数
        $comss_level = COMSS_LEVEL;
        $this->smarty->assign('comss_level', $comss_level);
        //Feng 2016-04-18 end
        //初始化内容
        $row['content'] = isset($row['content']) ? $row['content'] : '';
        $row['html_content'] = $this->form->editor('content', $row['content'], 'name="post[content]" style="width:100%;height:240px;"', array('toolbar' => 'full'));

        //生成图片控件
        $row['thumb'] = isset($row['thumb']) ? $row['thumb'] : '';
        $row['thumbs'] = isset($row['thumbs']) ? $row['thumbs'] : '';
        $row['html_thumb'] = $this->form->files('thumb', $row['thumb']);
        $row['html_thumbs'] = $this->form->files('thumbs', $row['thumbs'], '上传图集', array(
            'maxnum' => 10,
        ));
        $row['share_img'] = isset($row['share_img']) ? $row['share_img'] : '';
        $row['html_share'] = $this->form->files('share_img', $row['share_img'],'上传图集',array(
            'watermark'=>1,
        ));
        if (!$id) {
            $this->smarty->assign('btnNo', 1);
        }

        # 附表数据
        if ($id) {
            $_attr = $this->db->get("SELECT * FROM ###_goods_additional WHERE goods_id=" . $id);
            if (is_array($_attr)) {
                unset($_attr['id'], $_attr['goods_id']);
                foreach ($_attr as $a_k => $a_v) {
                    if ('superior_brokerage' == $a_k ) {
                        $row[$a_k] = json_decode($a_v, true);
                    }else {
                        $row[$a_k] = $a_v;
                    }
                }
                unset($_attr);
            }
            #商品规格  start
            $sp_ids = array();
            if (!empty($row['sp_val'])) {
                $row['sp_val'] = json_decode($row['sp_val'], true);
                foreach ($row['sp_val'] as $sp_id => $val) {
                    $sp_ids[] = $sp_id;
                }
            }
            $row['sp_ids'] = implode(',', $sp_ids);
            if (!empty($row['sp_ids'])) {
                $speclist = $this->db->select("SELECT * FROM ###_goods_spec WHERE id IN(" . $row['sp_ids'] . ")");
                $rows = $this->db->select("SELECT * FROM ###_goods_spec_item WHERE spec_id IN(" . $row['sp_ids'] . ")");
                $specItems = array();
                foreach ($rows as $v)
                    $specItems[$v['spec_id']][] = $v;
                foreach ($speclist as $k => $val) {
                    $speclist[$k]['values'] = isset($specItems[$val['id']]) ? $specItems[$val['id']] : array();
                }

                $this->smarty->assign('spec_list', $speclist);
            } else {
                $this->smarty->assign('spec_list', array());
            }
            #商品规格  end
        }
        if($row['sid']>0){
            $this->load->model('business');
            $row['business'] = $this->business->get($row['sid'],'name,mobile');
        }        
        //$row['superior_brokerage']['is_state'] = empty($row['superior_brokerage']['is_state']) ? $this->_Config('goods_isBrokerage') : $row['superior_brokerage']['is_state'];
        #商品规格 start
        $items = $this->db->select("SELECT * FROM ###_goods_item WHERE goods_id=" . $id);
        $goodsItem = array();
        foreach ($items as $val) {
            $goodsItem['price-' . $val['spec']] = $val['price'];
            $goodsItem['teamprice-' . $val['spec']] = $val['team_price'];
            $goodsItem['cost-' . $val['spec']] = $val['cost'];
            $goodsItem['stock-' . $val['spec']] = $val['stock'];
            $goodsItem['serial-' . $val['spec']] = $val['serial'];
            if ($val['thumb']) {
                $thumb = json_decode($val['thumb'], true);
                $goodsItem['thumb-path-' . $val['spec']] = !empty($thumb[0]['path']) ? $thumb[0]['path'] : '';
                $goodsItem['thumb-title-' . $val['spec']] = !empty($thumb[0]['title']) ? $thumb[0]['title'] : '';
            }
        }
        if ($items && $row['sp_val']){
            $this->smarty->assign('goodsItem', json_encode($goodsItem));
        }

        //商品类型
        $this->load->model("order");
        //判断积分兑换是否开启
        $this->load->model('exchange');
        if($this->exchange->power){
        	$actTypes = array_merge($this->order->actTypes,$this->order->actTypes_exchange);
        }else{
        	$actTypes = $this->order->actTypes;
        }
		$this->smarty->assign('actTypes', $actTypes);

        #商品规格 end
        $this->smarty->assign('row', $row);
        $row['cid'] = isset($row['cid']) ? $row['cid'] : 0;
        $this->smarty->display('manage/goods/edit.html');
    }

    /*
     * 获取同城分类下拉
     */
    function area_option($zone = 0,$row=array()){
        /*$this->load->model("linkagecat");
        $row['aid'] = !empty($row['aid'])?$row['aid']:0;
        $this->load->model('linkage');
        $zone_row = $this->linkage->get($zone);
        if($zone_row['parentid']>1){
            $zone_array = explode(",",$zone_row['arrparentid']);
            $zoneid = $zone_array[2];
            $zone_row = $this->linkage->get($zoneid);
        }
        $zone_row['catid'] = !empty($zone_row['catid'])?$zone_row['catid']:0;

        //$select_areacat = $this->linkage->select_cat($row['aid'],'aid','linkage_cat');

        $select_areacat = $this->linkagecat->get_option($row['aid'],$zone_row['catid']);*/
        $this->load->model("linkage");
        $select_areacat = $this->linkage->select_cat($row['aid'],"aid","linkage_cat",$zone);

        if($_POST['type']=='json'){
            echo  $select_areacat;exit;
        }else{
            return $select_areacat;
        }
    }

    /**
     * 删除产品
     * @param $id
     */
    function del() {
        $id = (int) $_POST['id'];
        if (!$id) {
            die;
        }

        $row = $this->db->get("SELECT * FROM ###_goods WHERE id=" . $id);
        if (isset($row['id'])) {
            admin_log('删除商品：' . $row['name']);
            //$this->db->update('product',array('status'=>'-1'),array('id'=>$id));
            $this->db->delete('goods', array('id' => $id));
            $this->db->delete('goods_additional', array('goods_id' => $id));//删除商品附表
            $this->db->delete('topic_goods', array('goods_id' => $id));//删除专题报名商品
            $this->tip('删除成功', array('type' => 1));
        }
    }

    /** 检索条件
     * @return array
     */
    private function getCondtions($sid = 0) {
        $where = ' 1 ';
        $order = ' ORDER BY ';
        
        #关键词搜索
        $array = array('k', 'q', 'cid');
        foreach ($array as $v) {
            if (!isset($_REQUEST[$v])) {
                $_REQUEST[$v] = '';
            }
        }
        
        
        if(!empty($_REQUEST['sid']) && $_REQUEST['sid']>=0){
            $where.=' AND sid='.intval($_REQUEST['sid']);
        }else{
            if($sid == 0){
                $where.=' AND sid=0 ';
            }elseif($sid>0){
                $where.=' AND sid>0 ';
            }
        }
                
        if (!empty($_REQUEST['q'])) {
            $where .= " AND " . trim($_REQUEST['k']) . " LIKE '%" . addslashes($_REQUEST['q']) . "%'";
        }
        //平台审核状态
        if ($_REQUEST['is_check']=='0' || $_REQUEST['is_check']>0) {
            $where .= " AND is_check=".$_REQUEST['is_check'];
        }
        //上架
        if ($_REQUEST['is_sale']>0) {
            $is_sale = $_REQUEST['is_sale']==1?1:0;
            $where .= " AND is_sale=".$is_sale;
        }
        //同城
        if ($_REQUEST['is_areaid']>0) {
            $is_areaid = $_REQUEST['is_areaid']==1?1:0;
            if($is_areaid==1){
                $where .= " AND areaid>0 ";
            }else{
                $where .= " AND areaid=0 ";
            }
        }

        //商品分类
        if (!empty($_REQUEST['cid'])) {
            $where .= " AND cid " . $this->goodscat->condArrchild(intval($_REQUEST['cid']));
        }
         //商品分类
        if (!empty($_REQUEST['bid'])) {
            $where .= " AND bid " . $this->brand->condArrchild(intval($_REQUEST['bid']));
        }
        //商品分类
        if ($_REQUEST['typeid']>0 || $_REQUEST['typeid']=='0') {
            $where .= " AND typeid=".intval($_REQUEST['typeid']);
        }
        //商家
        if ($_REQUEST['bname']) {
            $bid_array = $this->db->select("select id from ###_business where name LIKE '%" . addslashes($_REQUEST['bname']) . "%'");
            if($bid_array){
                $bid_array = array_column($bid_array,"id");
                $where .= " AND sid in (".join(',',$bid_array).") ";
            }else{
                $where .= " AND 1=2 ";
            }
        }

        #快速排序
        $order = "listorder ASC,id DESC";
        //$order = "id DESC";
        if (isset($_REQUEST['sortby'])  && !empty($_REQUEST['sortby'])) {
            $order = $_REQUEST['sortby'] . ' ' . (isset($_REQUEST['sortorder']) ? $_REQUEST['sortorder'] : 'DESC');
        }

        return array('where' => $where, 'order' => $order);
    }

    /**
     * ajax检索商品
     */
    function search_goods() {
        if ($_POST['ajax']) {
            $x = array('html' => '', 'name' => '', 'price' => 0);
            $array = $this->getCondtions();
            $where = $array['where'];
            $order = $array['order'];

            $sql = "SELECT id,name,price FROM ###_goods WHERE " . $where . ' ORDER BY ' . $order;
            $rows = $this->db->select($sql);

            foreach ($rows as $k => $v) {
                if ($k == 0) {
                    $x['name'] = $v['name'];
                    $x['price'] = $v['price'];
                }
                $x['html'] .= "<option value='$v[id]' price='$v[price]'>$v[name]</option>";
            }
            exit(json_encode($x));
        } else {
            exit('error');
        }
    }

    //获取规格相关Html
    function changSpec() {
        $goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : 0;

        $goods = $goods_id ? $this->db->get("SELECT * FROM ###_goods WHERE id=" . $goods_id) : array();
        $setup = !empty($goods['params']) ? json_decode($goods['params'], true) : array();
        if (empty($setup)) {
            $setup = array(
                array('name' => '', 'value' => ''),
            );
        }

        $this->smarty->assign('setup', $setup);
        $this->smarty->assign('goods_id', $goods_id);
        $this->smarty->display('manage/goods/spec.html');
    }

    /**
     *
     * 导出订单
     *
     * @access  public
     * @version v0.0.0.1
     * @author  Chenjl <chenjl@lnest.com>
     *
     * @return bool  上传成功(true)/失败(false)
     *
     * @example $this->goods->exportExcel()；
     *
     */
    function exportExcel($type=0) {
        $this->load->model('share');
        $_GET['excel'] = 1;
        if($type==0){
            $data = $this->index();
        }else{
            $data = $this->business_goods();
        }
        $list = array();

        #获取品牌
        static $brand;
        /* if (empty($brand) || !is_array($brand)) {
          $brandRows = $this->db->select("SELECT `id`,`catname` FROM `###_brand`");
          foreach ($brandRows AS $b_k => $b_v) {
          $brand[$b_k] = $b_v['catname'];
          }
          } */
        foreach ($data as $k => $v) {
            //$v['is_sale'] = $v['is_sale'] ? '上架' : '下架';
            //$v['cid']     = $v['cat_name_str'];
            //$v['bid']     = isset($brand[$v['bid']]) ? $brand[$v['bid']] : '无';
            $v['unit'] = empty($v['unit']) ? '个' : $v['unit'];
            $v['weight'] = empty($v['weight']) ? 0 : $v['weight'];
            $v['desc'] = clearBr($v['desc']);
            $v['content'] = clearBr($v['content']);
            $list[] = $v;
        }
        $fields = array(
            'id' => 'ID',
            'name' => '商品名称',
            'desc' => '商品简介',
            'content' => '商品描述',
            'params' => '商品参数',
            'market_price' => '市场价',
            'cost_price' => '成本价',
            'price' => '本站价格',
            'cid' => '分类',
            'bid' => '品牌',
            'stock' => '库存',
            'sell' => '已售数量',
            'is_sale' => '状态',
            'unit' => '商品单位',
            'weight' => '重量',
            'weight_unit' => '重量单位',
            'sell' => '已售数量',
            'thumb' => '封面图',
            'thumbs' => '产品图集',
        );

        $this->share->SetExcelHeader('商品列表-' . date('Y-m-d H:i:s'), '商品列表');
        $this->share->SetExcelBody($fields, $list);
    }

    public function getAjaxNorms() {
        $_type = $_REQUEST['type'];
        unset($_POST['type']);
        switch ($_type) {
            case 'add_spec': // 规格属性，数据库创建
                echo $this->goods->specFormatOperate('add', array('type' => 'spec'));
                break;
            case 'add_item': // 规格属性值添加
                echo $this->goods->specFormatOperate('add', array('type' => 'item', 'value' => $_POST['value'], 'spec_id' => $_POST['spec_id'], 'order' => 0));
                break;
            case 'spec_edit': // 规格属性名称修改
                if (empty($_POST['spec_id'])) {
                    return;
                }
                $this->goods->specFormatOperate('edit', array('type' => 'spec', 'name' => $_POST['name'], 'spec_id' => $_POST['spec_id']));
                break;
            case 'spec_del': // 删除规格属性、属性值
                if (empty($_POST['spec_id'])) {
                    return;
                }
                $this->goods->specFormatOperate('del', array('type' => 'spec', 'id' => $_POST['spec_id']));
                break;
            case 'item_del': // 规格属性值删除
                if (empty($_POST['id'])) {
                    return;
                }
                $this->goods->specFormatOperate('del', array('type' => 'item', 'id' => $_POST['id']));
                break;
        }
    }

    /**
     * 选择分类获取规格参数
     * @param $id
     */
    function selCid($cid, $id = 0) {
        if ($id > 0) {
            $row = $this->db->get("SELECT * FROM ###_goods WHERE id = " . $id);
            $row['sp_val'] = json_decode($row['sp_val'], true);
            $this->smarty->assign('product', $row);
        }

        $cid_arr = $this->goodscat->get($cid);
        $cid_str = $cid_arr['arrchildid'];
        $spec = $this->db->select("select * from ###_goods_spec where catid in($cid_str)");
        if ($spec) {
            $sp_ids = '';
            foreach ($spec as $v) {
                $sp_ids .= ',' . $v['id'];
            }
            $sp_ids = substr($sp_ids, 1);
        }
        if (!empty($sp_ids)) {
            $speclist = $this->db->select("SELECT * FROM ###_goods_spec WHERE id IN(" . $sp_ids . ")");
            $rows = $this->db->select("SELECT * FROM ###_goods_spec_item WHERE spec_id IN(" . $sp_ids . ")");
            $specItems = array();
            foreach ($rows as $v) {
                $specItems[$v['spec_id']][] = $v;
            }

            foreach ($speclist as $k => $val) {
                $speclist[$k]['values'] = isset($specItems[$val['id']]) ? $specItems[$val['id']] : array();
            }

            $this->smarty->assign('spec_list', $speclist);
            $res = $this->smarty->fetch('manage/goods/edit_spec.html');
        } else {
            $this->smarty->assign('spec_list', array());
            $res = array();
        }
        echo json_encode(array('data' => $res));
        exit;
    }

    /**
     * 选择分类获取规格参数
     * @param $id
     */
    function specId($spec_id, $id = 0) {
        if ($id > 0) {
            $row = $this->db->get("SELECT * FROM ###_goods WHERE id = " . $id);
            $row['sp_val'] = json_decode($row['sp_val'], true);
            $this->smarty->assign('product', $row);
        }

        /*$spec = $this->db->select("select * from ###_goods_spec where id in ({$spec_id})");
        if ($spec) {
            $sp_ids = '';
            foreach ($spec as $v) {
                $sp_ids .= ',' . $v['id'];
            }
            $sp_ids = substr($sp_ids, 1);
        }*/
        if(strpos($spec_id,'_')!=flase){
            $spec_id = str_replace("_",",",$spec_id);
        }
        $sp_ids = $spec_id;
        if (!empty($sp_ids)) {
            $speclist = $this->db->select("SELECT * FROM ###_goods_spec WHERE id IN(" . $sp_ids . ")");
            $rows = $this->db->select("SELECT * FROM ###_goods_spec_item WHERE spec_id IN(" . $sp_ids . ")");
            $specItems = array();
            foreach ($rows as $v) {
                $specItems[$v['spec_id']][] = $v;
            }

            foreach ($speclist as $k => $val) {
                $speclist[$k]['values'] = isset($specItems[$val['id']]) ? $specItems[$val['id']] : array();
            }

            $this->smarty->assign('spec_list', $speclist);
            $res = $this->smarty->fetch('manage/goods/edit_spec.html');
        } else {
            $this->smarty->assign('spec_list', array());
            $res = array();
        }
        echo json_encode(array('data' => $res));
        exit;
    }

    /**
     * 切换商品类型
     * */
    function get_attr_html() {
        $goods_id = empty($_POST['goods_id']) ? 0 : intval($_POST['goods_id']);
        $goods_type = empty($_POST['goods_type']) ? 0 : intval($_POST['goods_type']);

        $content = $this->build_attr_html($goods_type, $goods_id);
        echo $content;
        exit;
    }

    /**
     * 根据属性数组创建属性的表单
     *
     * @access  public
     * @param   int     $cat_id     分类编号
     * @param   int     $goods_id   商品编号
     * @return  string
     */
    function build_attr_html($cat_id, $goods_id = 0) {
        $this->load->model("goodstype");
        $attr = $this->goodstype->get_attr_list($cat_id, $goods_id);
        $html = '<table width="100%" id="attrTable">';
        $spec = 0;

        foreach ($attr AS $key => $val) {
            $html .= "<tr><td class='label'>";
            if ($val['attr_type'] == 1 || $val['attr_type'] == 2) {
                $html .= ($spec != $val['attr_id']) ?
                        "<a href='javascript:;' onclick='addSpec(this)'>[+]</a>" :
                        "<a href='javascript:;' onclick='removeSpec(this)'>[-]</a>";
                $spec = $val['attr_id'];
            }

            $html .= "$val[attr_name]</td><td><input type='hidden' name='attr_id_list[]' value='$val[attr_id]' />";

            if ($val['attr_input_type'] == 0) {
                $html .= '<input name="attr_value_list[]" type="text" value="' . htmlspecialchars($val['attr_value']) . '" size="40" /> ';
            } elseif ($val['attr_input_type'] == 2) {
                $html .= '<textarea name="attr_value_list[]" rows="3" cols="40">' . htmlspecialchars($val['attr_value']) . '</textarea>';
            } else {
                $html .= '<select name="attr_value_list[]">';
                $html .= '<option value="">请选择</option>';

                $attr_values = explode("\n", $val['attr_values']);

                foreach ($attr_values AS $opt) {
                    $opt = trim(htmlspecialchars($opt));

                    $html .= ($val['attr_value'] != $opt) ?
                            '<option value="' . $opt . '">' . $opt . '</option>' :
                            '<option value="' . $opt . '" selected="selected">' . $opt . '</option>';
                }
                $html .= '</select> ';
            }

            $html .= ($val['attr_type'] == 1 || $val['attr_type'] == 2) ?
                    '属性价格  <input type="text" name="attr_price_list[]" value="' . $val['attr_price'] . '" size="5" maxlength="10" />' :
                    ' <input type="hidden" name="attr_price_list[]" value="0" />';

            $html .= '</td></tr>';
        }

        $html .= '</table>';

        return $html;
    }

    /**
     * 获取商品的选择列表
     * @param  integer $page  第几页
     * @return string         返回可以被ajax get获取的标准页面
     */
    public function selection($page = 1) {
        #检索
        $conds = $this->getCondtions(-1);
        $condtions = $conds['where'];
        $orderby = $conds['order'];

        // 过滤掉无用的商品
        $condtions .= ' and is_sale=1 and status=0  and stock >0 ';

        #分页
        $this->load->model('page');
        $_GET['page'] = intval($page);

        $this->page->set_vars(array(
            'per' =>(int) $this->common['page_listrows'],
            'url' => 'href="javascript:;" title="第{num}页" data-page="{num}"',
        ));

        $sql = "SELECT * FROM ###_goods WHERE " . $condtions . " ORDER BY " . $orderby;

        $data['list'] = $this->page->query($sql)->result_array();

        //获取下拉分类
        $select_categorys = $this->goodscat->category_option(isset($_GET['cid']) ? $_GET['cid'] : 0);
        $this->smarty->assign('select_categorys', $select_categorys);

        //拼团类型
        $this->load->model("order");
        $actTypes = $this->order->actTypes;
        $this->smarty->assign('select_type', $actTypes);

        if ($data['list']) {
            foreach ($data['list'] as $k => $v) {
                $v = $this->goods->getThumb($v);
                // $v['array_fs'] = $this->goods->getFourYear($v['fs'], 3);
                //商品分类
                $v['cat_name_str'] = $this->goodscat->get_catnamestr($v['cid']);
                $v['type_name'] = $actTypes[$v['typeid']]['title'];
                $data['list'][$k] = $v;
            }
        }
        $only = isset($_GET['only']) ? true : false;
        $this->smarty->assign('select_id', $_GET['select_id']);
        $this->smarty->assign('only', $only);
        $this->smarty->assign('data', $data);
        $this->smarty->display('manage/goods/selection.html');
    }


    /**
     * 获取商品的选择列表
     * @param  integer $page  第几页
     * @return string         返回可以被ajax get获取的标准页面
     */
    public function selection_wheel($page = 1) {
        #检索
        $conds = $this->getCondtions();
        $condtions = $conds['where'];
        $orderby = $conds['order'];

        // 过滤掉无用的商品
        $condtions .= ' and is_sale=1 and status=0  and stock >0';

        #分页
        $this->load->model('page');
        $_GET['page'] = intval($page);

        $this->page->set_vars(array(
            'per' =>(int) $this->common['page_listrows'],
            'url' => 'href="javascript:;" title="第{num}页" data-page="{num}"',
        ));

        $sql = "SELECT * FROM ###_goods WHERE " . $condtions . " ORDER BY " . $orderby;

        $data['list'] = $this->page->query($sql)->result_array();

        //获取下拉分类
        $select_categorys = $this->goodscat->category_option(isset($_GET['cid']) ? $_GET['cid'] : 0);
        $this->smarty->assign('select_categorys', $select_categorys);

        //拼团类型
        $this->load->model("order");
        $actTypes = $this->order->actTypes;
        $this->smarty->assign('select_type', $actTypes);

        if ($data['list']) {
            foreach ($data['list'] as $k => $v) {
                $v = $this->goods->getThumb($v);
                // $v['array_fs'] = $this->goods->getFourYear($v['fs'], 3);
                //商品分类
                $v['cat_name_str'] = $this->goodscat->get_catnamestr($v['cid']);
                $v['type_name'] = $actTypes[$v['typeid']]['title'];
                $data['list'][$k] = $v;
            }
        }
        $only = isset($_GET['only']) ? true : false;
        $this->smarty->assign('select_id', $_GET['select_id']);
        $this->smarty->assign('only', $only);
        $this->smarty->assign('data', $data);
        $this->smarty->display('manage/goods/selection_wheel.html');
    }

    /**
     * 批量修改价格页面
     * @param  integer $page  第几页
     * @return string         返回可以被ajax get获取的标准页面
     */
    function batch_price($page = 1) {
        #检索
        $conds = $this->getCondtions();
        $condtions = $conds['where'];
        $orderby = 'is_sale desc,id desc';

        //获取下拉分类
        $select_categorys = $this->goodscat->category_option(isset($_GET['cid']) ? $_GET['cid'] : 0);
        $this->smarty->assign('select_categorys', $select_categorys);
        #获取下拉品牌
        $select_brands = $this->brand->category_option(isset($_GET['bid']) ? $_GET['bid'] : 0);
        $this->smarty->assign('select_brands', $select_brands);

        if ($_GET['cid']) {//选择分类之后才显示商品
            #分页
            $this->load->model('page');
            $_GET['page'] = intval($page);
            $this->page->set_vars(array('per' => (int) $this->common['page_listrows']));

            $sql = "SELECT * FROM ###_goods WHERE " . $condtions . " ORDER BY " . $orderby;
            $data['list'] = $this->page->hashQuery($sql)->result_array();

            if ($data['list']) {

                $sql = "select * from ###_goods_spec where catid='{$_GET['cid']}'";
                $spec = $this->db->select($sql);
                foreach ($spec as $k => $v) {
                    $spec[$k]['item'] = $this->db->select("select * from ###_goods_spec_item where spec_id='{$v['id']}'");
                }
                $this->smarty->assign("spec", $spec);

                foreach ($data['list'] as $k => $v) {
                    $v = $this->goods->getThumb($v);
                    $v['cat_name_str'] = $this->goodscat->get_catnamestr($v['cid']);

                    $items = $this->db->select("SELECT * FROM ###_goods_item WHERE goods_id=" . $v['id']);
                    foreach ($items as $key => $val) {
                        $spec = $this->getGoodSpec($v['sp_val'], $val['spec']);
                        $val['goods_spec'] = $spec['spec_str'];
                        $val['goods_spec_arr'] = $spec['spec_arr'];
                        $v['item'][$key] = $val;
                    }
                    $data['list'][$k] = $v;
                }
            }
        }

        $this->smarty->assign('data', $data);
        $this->smarty->display('manage/goods/batch_price.html');
    }

    /**
     * 批量修改价格提交	
     */
    function batch_price_save() {
        #检索
        $conds = $this->getCondtions();
        $condtions = $conds['where'];
        if ($_POST['cid']) {//选择分类之后才显示商品                                       
            $sql = "SELECT * FROM ###_goods WHERE " . $condtions;
            $data['list'] = $this->db->select($sql);
            if ($data['list']) {
                foreach ($data['list'] as $k => $v) {
                    $items = $this->db->select("SELECT * FROM ###_goods_item WHERE goods_id=" . $v['id']);
                    $min_price = array();
                    $res = false;
                    foreach ($items as $key => $val) {##修改商品规格价格##
                        $spec = $this->getGoodSpec($v['sp_val'], $val['spec']);
                        $spec = $spec['spec_arr'];
                        if ($_POST['typeid'] == 1) {##根据原价格计算 加减乘除##
                            $options = floatval($_POST['options']);
                            if ($options > 0) {
                                $sql = "price = price" . $_POST['act'] . $options;
                                $res = $this->db->update("goods_item", $sql, array('id' => $val['id']));
                            }
                        } elseif ($_POST['typeid'] == 2) {##根据商品规格属性计算##
                            $fanshi = $_POST['fanshi'];
                            $min_price[] = $new_price = floatval(eval("return $fanshi;"));
                            if ($new_price > 0)
                                $res = $this->db->update("goods_item", array('price' => $new_price), array('id' => $val['id']));
                        }
                    }
                    if ($_POST['typeid'] == 1) {##修改商品价格并下架##
                        $sql = "is_sale=0,price = price" . $_POST['act'] . floatval($_POST['options']);
                        $this->db->update("goods", $sql, array('id' => $v['id']));
                    } elseif ($_POST['typeid'] == 2 && $res !== false) {
                        $price = min($min_price);
                        $this->db->update("goods", array('is_sale' => 0, 'price' => $price), array('id' => $v['id']));
                    }
                }
            }
        }
        $this->tip("操作成功", array('inIframe' => true));
        $this->exeJs("parent.window.location='/manage#!goods/batch_price?com=xshow|批量修改价格&cid={$_POST['cid']}';");
    }

    /**
     * 批量上架或下架
     */
    function batch_sale() {
        #检索
        $conds = $this->getCondtions();
        $condtions = $conds['where'];
        if ($_POST['cid']) {
            $cid = intval($_POST['cid']);
            $is_sale = intval($_POST['is_sale']);
            $this->db->update("###_goods", array("is_sale" => $is_sale), array("cid" => $cid));
        }
        $_GET['cid'] = $_POST['cid'];
        $this->batch_price();
    }

    /**
     * 获取子商品的规格名称
     */
    function getGoodSpec($sp_val, $spec) {
        $sp_arr = json_decode($sp_val, true);
        $arr = $parent = array();
        foreach ($sp_arr as $k => $v) {
            foreach ($v as $_k => $_v) {
                $arr[$_k] = $_v;
                $parent[$_k] = $k;
            }
        }
        $spec_str = '';
        $spec_arr = explode("-", $spec);
        foreach ($spec_arr as $key => $val) {
            $spec_str.=" " . $arr[$val];
            $spec_array[$parent[$val]] = $this->number($arr[$val]);
        }

        return array("spec_str" => $spec_str, "spec_arr" => $spec_array);
    }

    /**
     * 获取规格中的数字
     */
    function number($str) {
        $str = trim($str);
        if (empty($str)) {
            return '';
        }
        $reg = '/(\d+(\.\d+)?)/is'; //匹配数字的正则表达式
        preg_match_all($reg, $str, $result);
        if (is_array($result) && !empty($result) && !empty($result[1]) && !empty($result[1][0])) {
            return $result[1][0];
        }
        return '';
    }

    /**
     * 导入商品
     */
    function excelGoods() {
        if (isset($_POST['leadExcel']) && !empty($_POST['leadExcel'])) {
            if (empty($_POST['type'])) {
                $this->tip("请选择类型", array('inIframe' => true, 'type' => 1));
                exit;
            }
            $filename = $_FILES['inputExcel']['name'];
            $tmp_name = $_FILES['inputExcel']['tmp_name'];
            if ($_POST['type'] == 'local') {
                $data0 = mb_convert_encoding(file_get_contents($_FILES['inputExcel']['tmp_name']), "utf-8", "gbk");
                $data = explode("\n", trim($data0));
                foreach ($data as $key => $val) {
                    if ($key < 1)
                        continue;
                    $line_list = explode("\t", $val);
                    $arr[] = array(
                        'name' => $line_list[1],
                        'desc' => $line_list[2],
                        'content' => $line_list[3],
                        'params' => $line_list[4],
                        'market_price' => $line_list[5],
                        'cost_price' => $line_list[6],
                        'price' => $line_list[7],
                        'cid' => $line_list[8],
                        'bid' => $line_list[9],
                        'stock' => $line_list[10],
                        'sell' => $line_list[11],
                        'is_sale' => $line_list[12],
                        'unit' => $line_list[13],
                        'weight' => $line_list[14],
                        'weight_unit' => $line_list[15],
                        //'sell' => $line_list[16],
                        'thumb' => $line_list[16],
                        'thumbs' => $line_list[17],
                    );
                }
                $res = $this->db->insertAll("goods", $arr);
            }elseif ($_POST['type'] == 'taobao') {
                $data0 = mb_convert_encoding(file_get_contents($_FILES['inputExcel']['tmp_name']), "utf-8", "UTF-16LE");
                $data = explode("\n", trim($data0));
                $list = array();
                foreach ($data as $key => $val) {
                    if ($key < 3)
                        continue;
                    $line_list = explode("\t", $val);
                    $arr[] = array(
                        'name' => trim($line_list[0], '"'),
                        'content' => trim(str_replace('""', '\"', $line_list[20]), '"'),
                        'price' => $line_list[7],
                        'stock' => $line_list[9],
                        'desc' => trim($line_list[57], '"'),
                        'c_time' => strtotime(trim($line_list[19], "\"")),
                        'cid' => 0,
                        'weight' => 0,
                    );
                }
                $res = $this->db->insertAll("goods", $arr);
            }

            if ($res > 0) {
                $this->tip("导入成功", array('inIframe' => true, 'type' => 1));
                $this->exeJs("parent.com.xhide();parent.main.refresh()");
            } else {
                $this->tip("导入失败", array('inIframe' => true, 'type' => 1));
            }
        }
        $template = "manage/goods/goods_excel.html";
        $this->smarty->display($template);
    }
    
    /**
     * 商家商品管理
     */
    function business_goods($page = 1) {
        #检索
        $conds = $this->getCondtions(1);
        $condtions = $conds['where'];
        $orderby = $conds['order'];
        $excel = (isset($_GET['excel']) && $_GET['excel'] == 1) ? 1 : 0;

        #分页
        $this->load->model('page');
        $_GET['page'] = intval($page);
        $this->page->set_vars(array('per' => (int) $this->common['page_listrows']));

        $sql = "SELECT *,sell+sales_num as sell_num FROM ###_goods WHERE " . $condtions . " ORDER BY " . $orderby;
        if ($excel == 1) {
            $data['list'] = $this->db->select($sql);
            return $data['list'];
        }else{
            $data['list'] = $this->page->hashQuery($sql)->result_array();
        }
        //获取商家信息
        $data['list'] = $this->db->lJoin($data['list'],'business','id,name,logo','sid','id','b_');
        
        //获取下拉分类
        $select_categorys = $this->goodscat->category_option(isset($_GET['cid']) ? $_GET['cid'] : 0);
        $this->smarty->assign('select_categorys', $select_categorys);
        #获取下拉品牌
        $select_brands = $this->brand->category_option(isset($_GET['bid']) ? $_GET['bid'] : 0);
        $this->smarty->assign('select_brands', $select_brands);
        
        $this->load->model("order");
        $actTypes = $this->order->actTypes;
        //unset($actTypes[0]);
        $this->smarty->assign('select_type', $actTypes);
        if ($data['list']) {
            foreach ($data['list'] as $k => $v) {
                $v = $this->goods->getThumb($v);
                $v['img_src'] = str_replace('_src', '_thumb', $v['img_src']);
                //$v['array_fs'] = $this->goods->getFourYear($v['fs'],3);
                //商品分类
                $v['cat_name_str'] = $this->goodscat->get_catnamestr($v['cid']);
                $v['type_name'] = $actTypes[$v['typeid']]['title'];
                $data['list'][$k] = $v;                
            }
        }

        //商家列表
        $res_business = $this->db->select("select id,name from ###_business where status=1");
        if(count($res_business)>0){
            $business = array_combine(array_column($res_business, 'id'), array_column($res_business, "name"));
        }else{
            $business = array();
        }
        $this->smarty->assign('business_list', $business);
        $this->smarty->assign('data', $data);
        $this->smarty->display('manage/goods/business_goods_list.html');
    }

    /**
     * 商品二维码
     * @return void
     */
    public function qrcode() {
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $row = $this->db->get("select id,name from ###_goods where id={$id}");
        $url = url('/goods/show/'.$id);
        $qrcode = creat_tmp_code($url);
        $this->smarty->assign('qrcode', $qrcode);
        $this->smarty->assign('row', $row);
        $this->smarty->display('manage/goods/qrcode.html');
    }

}
