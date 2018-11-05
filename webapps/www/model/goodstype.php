<?php
/**
 * ZZCMS goodstype_model
 * ============================================================================
 * * 版权所有 2016 厦门紫竹数码科技有限公司，并保留所有权利。
 * 网站地址: http://www.lnest.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 */

class goodstype_model extends Lowxp_Model {
	private $baseTable = '###_goods_type';

	function __construct() {}

	//保存数据
	function save() {
		$post        = $_POST['post'];
		$cat_id      = isset($post['cat_id']) ? $post['cat_id'] : 0;

		#表单处理
		if (empty($post['cat_name'])) {
			return array('code' => 10001, 'message' => '请输入商品类型名称!');
		}

		if ($cat_id) {
			$res = $this->db->update("goods_type", $post, array("cat_id" => $cat_id));
			if ($res) {
				return array('code' => 0, 'type' => 'update', 'message' => '更新成功');
			} else {
				return array('code' => 10002, 'message' => '数据操作失败!');
			}
		} else {
			$res = $this->db->insert("goods_type", $post);
			if ($res) {
				return array('code' => 0, 'type' => 'insert', 'message' => '添加成功');
			} else {
				return array('code' => 10002, 'message' => '数据操作失败!');
			}
		}
	}
	//获取商品类型列表
	function get_cat_list() {
		$cat_list = $this->db->select("select * from ###_goods_type where enabled=1 ");
		return $cat_list;
	}
	//获取商品类型信息
	function get($cat_id) {
		return $this->db->get("select * from ###_goods_type where cat_id={$cat_id}");
	}

	//删除商品类型
	function del($cat_id) {
		if (!$cat_id) {
			return array('code' => 10002, 'message' => 'ID不能为空');
		}

		$res = $this->db->delete("goods_type", array("cat_id" => $cat_id));
		$this->db->delete("attribute", array("cat_id" => $cat_id));
		if ($res) {
			return array('code' => 0, 'type' => 'delete', 'message' => '删除成功');
		} else {
			return array('code' => 10002, 'message' => '数据操作失败!');
		}
	}

	//删除属性
	function attr_del($attr_id) {
		if (!$attr_id) {
			return array('code' => 10002, 'message' => 'ID不能为空');
		}

		$sql = "delete from ###_attribute where attr_id in ({$attr_id})";
		$res = $this->db->query($sql);
		if ($res) {
			$sql = "delete from ###_goods_attr where attr_id in ({$attr_id})";
			$this->db->query($sql);
			return array('code' => 0, 'type' => 'delete', 'message' => '删除成功');
		} else {
			return array('code' => 10002, 'message' => '数据操作失败!');
		}
	}

	//获取属性信息
	function get_attr($attr_id) {
		return $this->db->get("select * from ###_attribute where attr_id={$attr_id}");
	}

	//保存属性
	function attr_save() {
		$post    = $_POST['post'];
		$attr_id = isset($post['attr_id']) ? $post['attr_id'] : 0;

		#表单处理
		if (empty($post['attr_name'])) {
			return array('code' => 10001, 'message' => '请输入属性名称!');
		}

		if ($attr_id) {
			$res = $this->db->update("attribute", $post, array("attr_id" => $attr_id));
			if (false !== $res) {
				return array('code' => 0, 'type' => 'update', 'message' => '更新成功');
			} else {
				return array('code' => 10002, 'message' => '数据操作失败!');
			}
		} else {
			$res = $this->db->insert("attribute", $post);
			if ($res) {
				return array('code' => 0, 'type' => 'insert', 'message' => '添加成功');
			} else {
				return array('code' => 10002, 'message' => '数据操作失败!');
			}
		}
	}

	//更新属性排序
	function attr_sort() {
		$sort_order = $_POST['sort_order'];
		foreach ($sort_order as $k => $v) {
			$this->db->update("attribute", array("sort_order" => $v), array("attr_id" => $k));
		}
	}

	/**
	 * 取得通用属性和某分类的属性，以及某商品的属性值
	 * @param   int     $cat_id     分类编号
	 * @param   int     $goods_id   商品编号
	 * @return  array   规格与属性列表
	 */
	function get_attr_list($cat_id, $goods_id = 0) {
		if (empty($cat_id)) {
			return array();
		}

		// 查询属性值及商品的属性值
		$sql = "SELECT a.attr_id, a.attr_name, a.attr_input_type, a.attr_type, a.attr_values, v.attr_value, v.attr_price " .
		"FROM ###_attribute AS a " .
		"LEFT JOIN ###_goods_attr AS v " .
		"ON v.attr_id = a.attr_id AND v.goods_id = '$goods_id' " .
		"WHERE a.cat_id = " . intval($cat_id) . " OR a.cat_id = 0 " .
			"ORDER BY a.sort_order, a.attr_type, a.attr_id, v.attr_price, v.goods_attr_id";

		$row = $this->db->select($sql);

		return $row;
	}
        /**
        * 获得商品的属性和规格
        *
        * @access  public
        * @param   integer $goods_id
        * @return  array
        */
       function get_goods_properties($goods_id)
       {
           /* 对属性进行重新排序和分组 */
           $sql = "SELECT gt.attr_group ".
                   "FROM ###_goods_type AS gt, ###_goods AS g ".
                   "WHERE g.id='$goods_id' AND gt.cat_id=g.goods_type";
           $grp = $this->db->get($sql);
           if (!empty($grp['attr_group']))
           {
                $groups = explode("\n", strtr($grp, "\r", ''));
           }

           /* 获得商品的规格 */
           $sql = "SELECT a.attr_id, a.attr_name, a.attr_group, a.is_linked, a.attr_type, ".
                       "g.goods_attr_id, g.attr_value, g.attr_price " .
                   'FROM ###_goods_attr AS g ' .
                   'LEFT JOIN ###_attribute AS a ON a.attr_id = g.attr_id ' .
                   "WHERE g.goods_id = '$goods_id' " .
                   'ORDER BY a.sort_order, g.attr_price, g.goods_attr_id';
           $res = $this->db->select($sql);

           $arr['pro'] = array();     // 属性
           $arr['spe'] = array();     // 规格
           $arr['lnk'] = array();     // 关联的属性

           foreach ($res AS $row)
           {
               $row['attr_value'] = str_replace("\n", '<br />', $row['attr_value']);

               if ($row['attr_type'] == 0)
               {
                   $group = (isset($groups[$row['attr_group']])) ? $groups[$row['attr_group']] :"attribute";

                   $arr['pro'][$group][$row['attr_id']]['name']  = $row['attr_name'];
                   $arr['pro'][$group][$row['attr_id']]['value'] = $row['attr_value'];
               }
               else
               {
                   $arr['spe'][$row['attr_id']]['attr_type'] = $row['attr_type'];
                   $arr['spe'][$row['attr_id']]['name']     = $row['attr_name'];
                   $arr['spe'][$row['attr_id']]['values'][] = array(
                                                               'label'        => $row['attr_value'],
                                                               'price'        => $row['attr_price'],
                                                               'format_price' => price_format(abs($row['attr_price']), false),
                                                               'id'           => $row['goods_attr_id']);
               }

               if ($row['is_linked'] == 1)
               {
                   /* 如果该属性需要关联，先保存下来 */
                   $arr['lnk'][$row['attr_id']]['name']  = $row['attr_name'];
                   $arr['lnk'][$row['attr_id']]['value'] = $row['attr_value'];
               }
           }           
           return $arr;
       }
        

}