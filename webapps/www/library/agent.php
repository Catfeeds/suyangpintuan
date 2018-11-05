<?php
class agent_Library extends Lowxp_Model {
	public $agent_member = array();
	public $agent_total = 0;
	public function __construct() {
		$this->load->model('member');
	}

	/**
	 * 分销商佣金
	 * @param  array  $order      订单信息
	 * @param  array  $member     会员信息
	 */
	public function comms($order) {
		//$order = $this->db->get("SELECT * FROM ###_goods_order WHERE id=" . $order_id);
		$member = $this->member->member_info($order['mid'], 'ivt_id,ivt_level,username,partner_id,partner_rank,zone');
		if ($member['ivt_id'] == 0) {
                    return false;
		}

		$is_rate = C('is_rate'); #合伙人开关
		$is_area = C('is_area'); #区域代理开关
                $goods_comm = C('goods_comm');#商品设置佣金开关

                if($goods_comm){#获取每个商品的佣金或比例
                    $sql = "select g.good_id,g.sell_price,g.buy_num,a.superior_brokerage from ###_goods_order_item as g left join ###_goods_additional as a on g.good_id=a.goods_id where g.order_id={$order['id']}";
                    $goods_list = $this->db->select($sql);
                }
                
		$this->load->model('agent');
		if ($is_area == 1 && $member['zone'] > 0) { #代理商分成
                    $this->agent_area($order, $member);
		}
		if ($is_rate == 1 && $member['partner_id'] > 0) { #合伙人分成
                    $this->agent_member[] = $member['partner_id'];
		}
		$agent_rate = false;
		#推荐人层级
		$agent_list = $this->get_usertree($member['ivt_id']);

		// 模版消息 19 下级已付款,预计佣金到账 {插入昵称},{分销层级},{插入订单号},{插入佣金},{插入店铺}
		// template_msg_action start
		$this->load->model('template_msg');
		$queue = array();

		$siteName = C('site_name');

		foreach ($agent_list as $k => $v) {

			if ($v['agent_rank'] == 0 && $v['partner_rank'] <= 0) { #跳过没有分销等级 区域代理商
                            continue;
			} elseif ($is_rate == 1 && $v['partner_rank'] > 0 ) { //合伙人在推荐人层级才算佣金
                            $agent_rate = true;
                            $member['partner_id']=$member['partner_id']==0?$v['mid']:$member['partner_id'];
                            continue;
			}
			if ($v['agent_rank'] > 0) { #分销商
                                if (in_array($v['mid'], $this->agent_member)) {
                                    continue;
                                }
                                #跳过合伙人，代理商

				#获取分销商等级比例
				$agent_rank = $rank ? $rank["comss_scale_array"] : $this->agent->getComssRank($v['agent_rank']);
                if($goods_comm){#按每个商品单独算佣金
                    
                    foreach($goods_list as $key=>$val){
                        $comm = json_decode($val['superior_brokerage'],true);
                        if($comm[$k]['money']>0){
                            $commission = $comm[$k]['money'];   
                            $desc = $commission."元";
                        }else{
                            $ratio = $comm[$k]['ratio']>0?$comm[$k]['ratio']:$agent_rank[$k];
                            $commission = round($ratio * $val['sell_price'] * $val['buy_num']* 0.01, 2); 
                            $desc = $ratio."%";
                        }                                                                            
                        $this->agent_total += $commission;
                        if ($commission > 0) {
                            $insert_arr = array();
                            $insert_arr['mid'] = $v['mid'];
                            $insert_arr['username'] = $v['username'];
                            $insert_arr['ivt_mid'] = $order['mid'];
                            $insert_arr['ivt_username'] = $member['username'];
                            $insert_arr['order_id'] = $order['id'];
                            $insert_arr['total'] = $val['sell_price']* $val['buy_num'];
                            $insert_arr['commission'] = $commission;
                            $insert_arr['desc'] =  "邀请会员下单(订单号".$order['order_sn']."、商品ID".$val['good_id']."),获得".$desc.L('unit_comm')."收益(".$k."级".L('unit_distribution').")";
                            $insert_arr['level'] = $k;
                            $this->member->save_commission($insert_arr);
                            $queue[] = array(19, $v['mid'], array($v['username'], $k, $order['order_sn'], $commission, $siteName));
                        }
                    }
                    
                    
                }else{#按订单算佣金
                    $commission = round($agent_rank[$k] * $order['goods_amount'] * 0.01, 2);
                    $this->agent_total += $commission;
                    if ($commission > 0) {
                        $insert_arr = array();
                        $insert_arr['mid'] = $v['mid'];
                        $insert_arr['username'] = $v['username'];
                        $insert_arr['ivt_mid'] = $order['mid'];
                        $insert_arr['ivt_username'] = $member['username'];
                        $insert_arr['order_id'] = $order['id'];
                        $insert_arr['total'] = $order['goods_amount'];
                        $insert_arr['commission'] = $commission;
                        $insert_arr['desc'] =  "邀请会员下单(订单号".$order['order_sn']."),获得".$agent_rank[$k]."%".L('unit_comm')."收益(".$k."级".L('unit_distribution').")";
                        $insert_arr['level'] = $k;
                        $this->member->save_commission($insert_arr);
                        $queue[] = array(19, $v['mid'], array($v['username'], $k, $order['order_sn'], $commission, $siteName));
                    }
                }
                                
                               
			}

			
		}

		if(count($queue)>0)$this->template_msg->inQueueMany($queue);
		// template_msg_action end

		if ($is_rate == 1 && $member['partner_id'] > 0 && $agent_rate) { #合伙人分成
                    $row = $this->member->member_info($member['partner_id'], 'mid,username');
                    $row['ivt_mid'] = $order['mid'];
                    $row['ivt_username'] = $member['username'];
                    
                    $this->agent($order, $row);
		}
                
                

	}

	/**
	 * 合伙人佣金
	 * @param  array  $order      订单信息
	 * @param  array  $member     会员信息
	 */
	public function agent($order, $member) {

		$sql = "select p1.*,p2.scale from ###_member_agent as p1 left join ###_member_agent_rank as p2 on p1.rank = p2.id where p1.type=1 and p1.status=1 and p1.mid='{$member['mid']}'";
		$row = $this->db->get($sql);
                $scale = 0;
		if ($row['agent_scale'] > 0) {
			$commission = round($order['goods_amount'] * ($row['agent_scale'] * 0.01), 2);
                        $scale = $row['agent_scale'];
		} elseif ($row['scale'] > 0) {
			$commission = round($order['goods_amount'] * ($row['scale'] * 0.01), 2);
                        $scale = $row['scale'];
		}
		$commission = $commission - $this->agent_total;
		if ($commission > 0) {
			$this->agent_member[] = $member['mid'];
			$insert_arr = array();
			$insert_arr['mid'] = $member['mid'];
			$insert_arr['username'] = $member['username'];
			$insert_arr['ivt_mid'] = $member['ivt_mid'];
			$insert_arr['ivt_username'] = $member['ivt_username'];
			$insert_arr['order_id'] = $order['id'];
			$insert_arr['total'] = $order['goods_amount'];
			$insert_arr['commission'] = $commission;
			$insert_arr['desc'] =  "邀请会员下单(订单号".$order['order_sn']."),获得".$scale."%".L('unit_comm')."收益(合伙人)";
			$this->member->save_commission($insert_arr);
		}

	}

	/**
	 * 代理佣金
	 * @param  array  $order      订单信息
	 * @param  array  $member     会员信息
	 * @param  array  $type       代理类型 -1省 -2市
	 */
	public function agent_area($order, $member, $type = 0) {

		$this->load->model("linkage");
		$zone_arr = $this->linkage->get($member['zone']);
		$zone_str = $zone_arr['arrparentid'] . ',' . $member['zone'];
		if ($type == 0) {
			$sql = "select * from ###_member_agent where type>1 and status=1 and areaid in ({$zone_str})";
		} elseif ($type == '-1') { #省代
		$sql = "select * from ###_member_agent where type=2 and status=1 and areaid in ({$zone_str})";
		} elseif ($type == '-2') { #市代
		$sql = "select * from ###_member_agent where type=3 and status=1 and areaid in ({$zone_str})";
		}
		$list = $this->db->select($sql);
		foreach ($list as $k => $v) {
			if ($v['mid'] == $order['mid']) {
				continue;
			}#跳过自己

			if ($v['agent_scale'] == 0) {
				$v['agent_scale'] = $v['type'] == 2 ? C("agent_province") : C("agent_city");
			}

			$commission = round($order['goods_amount'] * $v['agent_scale'] * 0.01, 2);
			if ($commission > 0) {
				$this->agent_member[] = $v['mid'];
				$row = $this->member->member_info($v['mid'], 'mid,username');
				$insert_arr = array();
				$insert_arr['mid'] = $v['mid'];
				$insert_arr['username'] = $row['username'];
				$insert_arr['ivt_mid'] = $order['mid'];
				$insert_arr['ivt_username'] = $member['username'];
				$insert_arr['order_id'] = $order['id'];
				$insert_arr['total'] = $order['goods_amount'];
				$insert_arr['commission'] = $commission;
				$desc = $v['type'] == 2 ? "省".L('unit_agent')."抽成" : "市".L('unit_agent')."抽成";
                $insert_arr['desc'] =  "会员下单(订单号".$order['order_sn']."),获得".$v['agent_scale']."%".L('unit_comm')."收益(".$desc.")";
				$insert_arr['level'] = $v['type'] == 2 ? "-1":"-2" ;
				$this->member->save_commission($insert_arr);
			}
		}
	}

	/**
	 * 判断用户的地区代理
	 * @param  array  $mid        代理会员
	 * @param  array  $zone       地区
	 */
	function is_area_agent($mid, $zone) {

		if ($zone == false) {
			return false;
		}

		$res = $this->db->get("select * from ###_member_agent where type>1 and mid='{$mid}'");
		if ($res['areaid'] == $zone) {
			return true;
		}

		$this->load->model("linkage");
		$zone_res = $this->linkage->get($zone);
		$zone_arr = explode(',', $zone_res['arrparentid']);
		if (in_array($res['areaid'], $zone_arr)) {
			return true;
		}

		return false;
	}

	/**
	 * 获取分销商的分销比例
	 * @param  array  $mid      会员mid
	 */
	function get_agent_scale($mid) {
		$this->load->model('agent');
		$member = $this->member->member_info($mid, 'agent_rank');
		$auto_run = C('auto_run'); #分销商等级判断自动执行开关
		if ($auto_run == 0) { #分销商等级判断自动执行开关,没有开启则实时判断用户分销等级
		$rank = $this->agent->getComss($mid);
			$agent_rank = $rank['id'];
		} else {
			$agent_rank = $member['agent_rank'];
		}
		if ($agent_rank == 0) {
			return 0;
		}

		$agent_scale = $this->agent->getComssRank($agent_rank);
		return $agent_scale[1];
	}

	/**
	 * 退货成功扣除佣金
	 * @param  array  $mid      会员mid
	 */
	function refund_comms($order_id) {
        $sql = "select order_sn from ###_goods_order where id=". $order_id;
        $order_sn = $this->db->getstr($sql, 'order_sn');
		$sql = "select * from ###_commission where order_id=" . $order_id;
		$res = $this->db->select($sql);
		if ($res) {
			foreach ($res as $key => $val) {
				$insert_arr = array();
				$insert_arr['mid'] = $val['mid'];
				$insert_arr['username'] = $val['username'];
				$insert_arr['ivt_mid'] = $val['ivt_mid'];
				$insert_arr['ivt_username'] = $val['ivt_username'];
				$insert_arr['order_id'] = $val['order_id'];
				$insert_arr['total'] = $val['total'];
				$insert_arr['commission'] = -$val['commission'];
				$insert_arr['desc'] = "邀请会员退货(订单号".$order_sn."),佣金返还";
				$insert_arr['level'] = $val['level'];
				$this->member->save_commission($insert_arr);
			}
		}
	}

	#获取分销层级信息 向上
	function get_usertree($mid, $level = 1, &$res = array()) {
		if ($level > COMSS_LEVEL) {return $res;}
		$member = $this->member->member_info($mid, 'mid,username,ivt_id,agent_rank,partner_rank');

		//获取用户分销等级
		$this->load->model('agent');
		$rank = $this->agent->getComss($member['mid']);                
		$member['agent_rank'] = $rank['id'];
                
		if ($member['ivt_id']) {
			$res[$level] = $member;
			$level++;
			$this->get_usertree($member['ivt_id'], $level, $res);
		} else {
			$res[$level] = $member;
		}
		return $res;
	}

}

?>