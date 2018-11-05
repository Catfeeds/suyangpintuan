<?php
/**
 * ZZCMS goodscat_model
 * ============================================================================
 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。
 * 网站地址: http://www.lnest.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 */

class goodscat_model extends Lowxp_Model {
	private $baseTable = '###_goods_cat';

	function __construct() {}

	/**载入商品分类（缓存）
     */
	function loadCats($id = 0, $is_menu = 1) {
		$array = array();
		$data  = empty($options) ? $this->base->read_static_cache('cats_' . $id . '_' . $is_menu, 'goodscat') : false;

		if ($data === false) {
			$array = $this->select($id);
			if (empty($options)) {
				$this->base->write_static_cache('cats_' . $id . '_' . $is_menu, $array, 'goodscat');
			}

		} else {
			$array = $data;
		}

		return $array;
	}

	//获取栏目
	function select($id = '') {
		$where = ' WHERE id<>0 ';
		if ($id) {
			$where .= " AND parentid='$id' ";
		}

		$array = $this->db->select("SELECT * FROM " . $this->baseTable . $where . " ORDER BY listorder ASC,id ASC");

		$list = array();
		foreach ($array as $v) {
			$list[$v['id']] = $v;
		}
		return $list;
	}

	//获取栏目信息
	function get($id) {
		$where = ' WHERE id<>0 ';
		$sql   = "SELECT * FROM " . $this->baseTable . $where . " AND id=" . $id;
		return $this->db->get($sql);
	}

	//获取顶级栏目
	function getTop($id) {
		$where = ' WHERE id<>0 ';
		$sql   = "SELECT parentid,arrparentid FROM " . $this->baseTable . $where . " AND id=" . $id;
		$res   = $this->db->get($sql);
		$topId = $id;
		if ($res['parentid'] > 0) {
			$arr   = explode(',', $res['arrparentid']);
			$topId = $arr[1];
		}
		$sql = "SELECT id,catname FROM " . $this->baseTable . $where . " AND id=" . $topId;
		return $this->db->get($sql);
	}

	//保存所有父类到数组
	function parentArr($catid) {
		$res = $this->get($catid);

		$arrparentid = $res['arrparentid'];
		$arrparentid = explode(',', $arrparentid . ',' . $catid);
		$arrparentid = array_filter($arrparentid);

		return $arrparentid;
	}

	//保存数据
	function save() {
		$post          = $_POST['post'];
		$chage_all     = isset($_POST['chage_all']) ? $_POST['chage_all'] : '';
		$chage_all_mod = isset($_POST['chage_all_mod']) ? $_POST['chage_all_mod'] : '';
		$id            = isset($post['id']) ? $post['id'] : 0;

		#表单处理
		$post['catname'] = trim($post['catname']);
		if (empty($post['catname'])) {
			return array('code' => 10001, 'message' => '请输入栏目名称!');
		}

		if ($id) {
			#不能转移到自身或子级栏目下
			$res       = $this->db->get("select * from " . $this->baseTable . " where id='$id' ");
			$child_arr = $this->base->explodeChar($res['arrchildid'], ',');
			if (in_array($post['parentid'], $child_arr)) {
				return array('code' => 10001, 'message' => '栏目转移失败，请重新选择上级栏目!');
			}
		}

		#初始值
		if (!$id) {
			$post['hits'] = 0;
		}

		#处理批量栏目名
		$catnames = array();
		$array    = $this->base->explodeChar($post['catname'], "\n");
		foreach ($array as $v) {
			if (trim($v)) {
				$catnames[] = $v;
			}

		}

		#处理图片数据
		if (isset($post['thumb']) && !empty($post['thumb'])) {
			$arrData = array();
			foreach ($post['thumb']['path'] as $k => $v) {
				$arrData[$k]['path']  = $v;
				$arrData[$k]['title'] = $post['thumb']['title'][$k];
			}
			$post['thumb'] = json_encode($arrData);
		}
		if (isset($post['thumb_rec']) && !empty($post['thumb_rec'])) {
			$arrData = array();
			foreach ($post['thumb_rec']['path'] as $k => $v) {
				$arrData[$k]['path']  = $v;
				$arrData[$k]['title'] = $post['thumb_rec']['title'][$k];
			}
			$post['thumb_rec'] = json_encode($arrData);
		}

		#保存
		$where  = $post['id'] ? array('id' => (int) $post['id']) : '';
		$res    = false;
		$res_id = '';

		foreach ($catnames as $v) {
			$post['catname'] = $v;

			$res    = $this->db->save($this->baseTable, $post, '', $where);
			$res_id = $id ? $id : $this->db->insert_id();
		}

		#应用到子类,仅编辑时有效
		if ($id && ($chage_all || $chage_all_mod)) {
			$post_child = array();
			if ($chage_all) {
				$post_child = array_merge($post_child, array(
					'pagesize'      => $post['pagesize'],
					'template_list' => $post['template_list'],
					'template_show' => $post['template_show'],
					'ismenu'        => $post['ismenu'],
				));
			}
			if ($chage_all_mod) {
				$post_child = array_merge($post_child, array(
					'moduleid' => $post['moduleid'],
					'module'   => $post['module'],
				));
			}
			if (!empty($post_child)) {
				$res_category = $this->db->get("select * from " . $this->baseTable . " where id=" . $id);
				$where        = "id != $id AND `arrchildid` IN(" . $res_category['arrchildid'] . ") ";
				$this->db->save($this->baseTable, $post_child, '', $where);
			}
		}

		$this->repair();
		$this->repair();

		if ($res===false) {return array('code' => 10002, 'message' => '数据操作失败!');} //未知错误
		if ($post['id']) {
			admin_log('编辑商品分类：' . implode(',', $catnames));
			return array('code' => 0, 'type' => 'update', 'message' => '更新成功');
		} else {
			admin_log('添加商品分类：' . implode(',', $catnames));
			return array('code' => 0, 'type' => 'insert', 'message' => '添加成功');
		}
	}

	#栏目批量处理
	function repair() {
		@set_time_limit(500);
		$this->categorys = $categorys = array();
		$categorys       = $this->db->select("select * from " . $this->baseTable . " where parentid=0 ");
		$this->set_categorys($categorys);

		if (is_array($this->categorys)) {
			foreach ($this->categorys as $id => $cat) {
				$this->categorys[$id]['arrparentid'] = $arrparentid = $this->get_arrparentid($id);
				$this->categorys[$id]['arrchildid']  = $arrchildid  = $this->get_arrchildid($id);

				$url = '';
				if ($cat['type'] == 1) {
					$url = $cat['url'];
				} else {
					$url = '/goods/index/' . $cat['id'];
				}

				$child = is_numeric($arrchildid) ? 0 : 1;
				$this->db->update($this->baseTable, array('arrparentid' => $arrparentid, 'arrchildid' => $arrchildid, 'child' => $child, 'url' => $url), array('id' => $id));
			}
		}
	}

	#递归输出所有栏目
	function set_categorys($categorys = array()) {
		if (is_array($categorys) && !empty($categorys)) {
			foreach ($categorys as $id => $c) {
				$this->categorys[$c['id']] = $c;
				$r                         = $this->db->select("select * from " . $this->baseTable . " where parentid=" . $c['id']);
				$this->set_categorys($r);
			}
		}
		return true;
	}

	#重新获取所有父级
	function get_arrparentid($id, $arrparentid = '') {
		if (!is_array($this->categorys) || !isset($this->categorys[$id])) {
			return false;
		}

		$parentid    = $this->categorys[$id]['parentid'];
		$arrparentid = $arrparentid ? $parentid . ',' . $arrparentid : $parentid;
		if ($parentid) {
			$arrparentid = $this->get_arrparentid($parentid, $arrparentid);
		} else {
			$this->categorys[$id]['arrparentid'] = $arrparentid;
		}
		return $arrparentid;
	}

	#重新获取所有子级
	function get_arrchildid($id) {
		$arrchildid = $id;
		if (is_array($this->categorys)) {
			foreach ($this->categorys as $catid => $cat) {
				if ($cat['parentid'] && $id != $catid) {
					$arrparentids = $this->base->explodeChar($cat['arrparentid'], ',');
					if (in_array($id, $arrparentids)) {
						$arrchildid .= ',' . $catid;
					}

				}
			}
		}
		return $arrchildid;
	}

	/**
	 * 获取所属栏目名
	 */
	function get_catnamestr($cid, $str = '->') {
		$row     = $this->db->get("SELECT id,catname,arrparentid FROM " . $this->baseTable . " where id=" . $cid);
		$catname = '';
		if ($row['arrparentid']) {
		    $cats = $this->loadCats();
            $ex_array = explode(",",$row['arrparentid']);
            foreach($ex_array as $v){
                if($v==0)continue;
                $catname .= $cats[$v]['catname'] . $str ;
            }
		}
		return $catname . $row['catname'];
	}

	/** 获取栏目下拉
	 * @param string $value 当前选中
	 * @param string $moduleid 只显示此模型下的栏目
	 * @param bool $showparent 是否显示父级（用于检索时true,添加内容时false)
	 * @return mixed
	 */
	function category_option($value = '', $moduleid = '', $showparent = false, $parent_id = 0) {
		$this->load->library('tree');
		$category = $this->select($parent_id);
		$array    = array();
		foreach ($category as $r) {
			$r['selected'] = $r['id'] == $value ? 'selected' : '';
			$r['disabled'] = '';

			if ($moduleid) {
				$arr  = $this->base->explodeChar($r['arrchildid'], ",");
				$show = 0;
				foreach ((array) $arr as $rr) {
					$show = 1;
				}
				if (empty($show)) {
					continue;
				}

				$r['disabled'] = ($r['child'] && $showparent == false) ? ' disabled' : '';
			}

			$array[] = $r;
		}

		$str = "<option value='\$id' \$disabled \$selected>\$spacer\$catname</option>";
		$this->tree->set_params($array);
		$select_categorys = $this->tree->get_tree(0, $str, $value);
		return $select_categorys;
	}


    /** 获取顶级栏目下拉
     * @param string $value 当前选中
     * @param string $moduleid 只显示此模型下的栏目
     * @param bool $showparent 是否显示父级（用于检索时true,添加内容时false)
     * @return mixed
     */
    function category_top_option($value = '', $moduleid = '', $showparent = false, $parent_id = 0) {
        $this->load->library('tree');
        //$category = $this->select($parent_id);
        $category = $this->db->select("SELECT * FROM " . $this->baseTable  . " WHERE parentid=0 ORDER BY listorder ASC,id ASC");
        foreach ($category as $v) {
            $category[$v['id']] = $v;
        }

        $array    = array();
        foreach ($category as $r) {
            $r['selected'] = $r['id'] == $value ? 'selected' : '';
            $r['disabled'] = '';

            if ($moduleid) {
                $arr  = $this->base->explodeChar($r['arrchildid'], ",");
                $show = 0;
                foreach ((array) $arr as $rr) {
                    $show = 1;
                }
                if (empty($show)) {
                    continue;
                }

                $r['disabled'] = ($r['child'] && $showparent == false) ? ' disabled' : '';
            }

            $array[] = $r;
        }

        $str = "<option value='\$id' \$disabled \$selected>\$spacer\$catname</option>";
        $this->tree->set_params($array);
        $select_categorys = $this->tree->get_tree(0, $str, $value);
        return $select_categorys;
    }

	/**
	 * 栏目子级筛选
	 */
	function condArrchild($catid) {
		$goodsCat = $this->loadCats();
		if (!empty($goodsCat[$catid]['arrchildid'])) {
			return " IN(" . $goodsCat[$catid]['arrchildid'] . ") ";
		} else {
			return ' = 0';
		}

	}

    /**
	 * 缓存中获取栏目
	 */
	function cate($catid=0) {
		$goodsCat = $this->loadCats();
                $res = array();
                if($catid==0){
                    foreach($goodsCat as $k=>$v){
                        if($v['parentid']>0)continue;
                        $res[] = $v;
                    }
                }else{
                    foreach($goodsCat as $k=>$v){
                        if($v['parentid']==$catid)$res[] = $v;
                    }
                }
                return $res;
	}

    /**
     * 带有缩略图的分类
     * @param $list
     * @return array
     */
    function treeWithThumb($list) {
        $tree = array();
        foreach ($list as $k => $v) {
            if (isset($list[$v['parentid']])) {
                $list[$v['parentid']]['sub'][$k] = &$list[$k];
                if(empty($list[$v['parentid']]['sub'][$k]['thumb'])){
                    $list[$v['parentid']]['sub'][$k]['thumb'] = RootUrl.'common/nopic.png';
                }else{
                    $obj = json_decode($list[$v['parentid']]['sub'][$k]['thumb']);
                    $list[$v['parentid']]['sub'][$k]['thumb'] = yunurl($obj[0]->path);
                }
            } else {
                $tree[$v['id']] = &$list[$k];
                if(empty($tree[$v['id']]['thumb'])){
                    $tree[$v['id']]['thumb'] = RootUrl.'common/nopic.png';
                }else{
                    $obj = json_decode($tree[$v['id']]['thumb']);
                    $tree[$v['id']]['thumb'] = yunurl($obj[0]->path);
                }
            }
        }
        return $tree;
    }
}