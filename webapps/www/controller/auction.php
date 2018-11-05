<?php
/**
 * 促销活动控制器
 * ============================================================================
 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。
 * 网站地址: http://www.lnest.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 */

class auction extends Lowxp {

	function __construct() {
		parent::__construct();
		$method = $_SERVER['request']['method'];

		$this->load->model('auction');

		//判断是否有redis配置
		global $CACHE_CONFIG;
		$this->redis_type = 0;
		if ($CACHE_CONFIG['storage'] == 'redis') {
			$this->redis = new redis();
			$this->redis->connect($CACHE_CONFIG['redis']['host'], $CACHE_CONFIG['redis']['port']);
			$this->redis_type = 1;
		}
	}        

	/** 秒杀列表
	 * @param int $status 状态
	 * @param int $id 商品分类ID
	 * @param int $page 页码
	 */
	function index($page = 1, $status = '') {
		$data = array();

		$size         = 10;
		$data['list'] = $this->auction->getList($size, $page, $id, $status, CART_KILL, array('qishu' => 1, "order" => "act_id desc"));

		#异步加载
		if (isset($_GET['load'])) {
			if ($data['list']) {
				$content = '';
				foreach ($data['list'] as $v) {
					$this->smarty->assign('m', $v);
					$content .= $this->smarty->fetch('auction/lbi/kill_list.html');
				}
				echo $content;die;
			}
		}

		$this->smarty->assign('data', $data);
		$this->display_before(array('title' => '秒杀'));
		$this->smarty->display('auction/kill_list.html');
	}

	/**
	 * 促销详情  缓存促销详情，销量实时获取或者通过redis获取
	 */
	function show($id) {

		$this->load->model("goods");
		$auction  = $this->auction->get($id);
                $goods_id = $auction['goods_id'];
                $row = $this->goods->get($goods_id);
                if (empty($row)) {
                    $this->msg();
                    die;
                }
                
                $type = isset($_REQUEST['type']) ? trim($_REQUEST['type']) : '';
                $common_id = isset($_REQUEST['common_id']) ? intval($_REQUEST['common_id']) : 0;

                //是否收藏
                $this->load->model('fav');
                $row['is_fav'] = $this->fav->is_fav($row['id'], $_SESSION['mid']);
                $data = array('row' => $row, 'id' => $goods_id, 'type' => $type);

                //评价总数
                $where = "WHERE state=1 AND good_id=" . $goods_id;
                $sql = "select COUNT(id) from ###_goods_rate " . $where;
                $data['count'] = $this->db->getstr($sql);

                //Feng 2016-07-19 商品规格 start
                if ($row['sp_val']) {
                    $row['sp_val'] = json_decode($row['sp_val'], true);
                    $sp_ids = join(',', array_keys($row['sp_val']));
                    $sp_arr = $this->db->select("select * from ###_goods_spec where id in ($sp_ids)");
                    $sp_val = array_combine(array_column($sp_arr, 'id'), array_column($sp_arr, "name"));
                    foreach ($row['sp_val'] as $k => $v) {
                        $data['sp_vals'][$sp_val[$k]] = $v;
                    }
                    $data['spec'] = array();
                    $spec = trim($_GET['spec']);
                    if ($spec)
                        $data['spec'] = explode("-", $spec);
                }
                //Feng 2016-07-20 商品规格 end

                //Feng 2016-05-18 商品属性 start
                if ($row['goods_type'] > 0) {
                    $this->load->model('goodstype');
                    $properties = $this->goodstype->get_goods_properties($goods_id);
                    $data['attr'] = $properties['pro'];
                    $data['spe'] = $properties['spe'];
                }
                //Feng 2016-05-18 商品属性 end

                //拼团列表   1元购和免费试用 要新会员才能参团            
                if(check_team(MID,$row['typeid'])){
                    $this->load->model("team");
                    $data['team_list'] = $this->team->getTeamList($goods_id,array("status"=>TEAM_ING,"limit"=>3));
                }          

                $this->smarty->assign('data', $data);
                $this->display_before($data['row']);

                $this->smarty->display('goods/show.html');
	}
	function buy($id) {
		if (!defined("MID")) {
			login();
		}

		$buy_num = isset($_POST['num']) ? intval($_POST['num']) : 1;
		if ($this->redis_type == 1) {
#有开redis的情况
			$aid_key    = "AUCTION_" . $id; //销量
			$aid_list   = "AUCTION_LIST_" . $id; //抢到的用户、数量列表
			$mywatchkey = $this->redis->get($aid_key);
			if (!$mywatchkey) {
				$mywatchkey = $this->auction->getActSell($id);
			}

			//每人限购一次
			if ($redis->hget($aid_list, MID . "_" . $id)) {
				$this->error("您已抢购了", "/auction/show/" . $id);
			}

			$row = $this->auction->get($id);
			if ($this->auction->status($row) != UNDER_WAY) {
				$this->error("操作错误", "/kill/show/" . $id);
			}

			if ($row['max_num'] && $buy_num > $row['max_num']) {
				$this->error("购买数量大于", "/kill/show/" . $id);
			}

			$rob_total = $row['act_stock']; //库存
			if ($mywatchkey < $rob_total) {

				if (($buy_num + $mywatchkey) > $rob_total) {
					$this->error("秒杀失败请重试", "/kill/show/" . $id);
				}

				$this->redis->watch($aid_key);
				$this->redis->multi();

				//插入抢购数据
				$this->redis->hSet($aid_list, MID . "_" . $id, $buy_num);
				$this->redis->set($aid_key, $mywatchkey + $buy_num);
				$rob_result = $this->redis->exec();
				if ($rob_result) {
					//性能问题时可以异步执行
					$this->create_order($id);
					//$this->success('抢购成功！',"/auction/show/".$id);
				} else {
					$this->error('手气不好，再抢购！', "/kill/show/" . $id);
				}
			} else {
				$this->error('秒杀已结束', "/kill/show/" . $id);
			}
		} else {
#没有redis的情况
			$res = $this->auction->is_order_auction($id, $buy_num, CART_KILL);
			if ($res['msg']) {
				$this->error($res['msg'], "/kill/show/" . $id);
			}
#判断条件是否符合
			$this->create_order($id, $buy_num);
		}
	}

	/**
	 * 促销购买
	 */
	function create_order($id, $buy_num = 1) {
		if (!defined("MID")) {
			login();
		}

		if ($this->redis_type == 1) {
			$num     = $this->redis->hget("AUCTION_LIST_" . $id, MID . "_" . $id);
			$buy_num = $num > 0 ? $num : $buy_num;
		}

		$row = $this->auction->get($id);

		$ext_info = unserialize($row['ext_info']);
		$row      = array_merge($row, $ext_info);

		$this->load->model("flow");
		$order                   = array('mid' => MID, 'c_time' => RUN_TIME);
		$order['order_sn']       = $order_sn       = $this->flow->order_sn();
		$order['order_amount']   = $order['goods_amount']   = $order['amount']   = $buy_num * $row['act_price'];
		$order['const_amount']   = $buy_num * $row['goods_info']['cost_price'];
		$order['extension_id']   = $id;
		$order['extension_code'] = CART_KILL;
		$order_id                = $order['order_id']                = $this->db->save('goods_order', $order);
		if ($order_id > 0) {
			$data_goods = array(
				'mid'          => MID,
				'order_id'     => $order_id,
				'cost_price'   => $row['goods_info']['cost_price'],
				'good_id'      => $row['goods_info']['id'],
				'goods_name'   => $row['goods_info']['name'],
				'buy_num'      => $buy_num,
				'sell_price'   => $row['act_price'],
				'c_time'       => RUN_TIME,
				'extension_id' => $id,
			);
			$r   = $this->db->save('goods_order_item', $data_goods);
			$sql = "update ###_goods_activity set act_sell=act_sell+{$buy_num} where act_id={$id} and act_stock>=act_sell+{$buy_num}";
			$res = $this->db->query($sql);
			if (!$res > 0) {
				$this->db->delete("goods_order", array("id" => $order_id));
				$this->db->delete("goods_order_item", array("id" => $r));
				$this->error('秒杀失败请重试', "/kill/show/" . $id);
			} else {
				header("Location:/flow/checkout/" . $order_sn);
			}

		} else {
			$this->error('秒杀失败请重试', "/kill/show/" . $id);
		}

	}

}