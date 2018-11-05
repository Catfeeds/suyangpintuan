<?php
/**
 * 数据库！
 * Class Lowxp_database
 */
class Database {
	public $_sqls = array();
	public $link;
	public $result;

	private $host;
	private $user;
	private $pass;
	private $dbname;
	private $pre         = '';
	private $charset     = 'UTF8';
	private $version     = '';

	function __construct($db) {
		foreach ($db as $k => $v) {
			$this->{$k} = $v;
		}
        if (empty($this->dbname)) {
			$this->dbname = 'pintuan_' . MERID;
		}
		$this->connect();
	}

	/**
	 * 链接数据库
	 */
	function connect() {
		$this->link    = mysqli_connect($this->host, $this->user, $this->pass, $this->dbname) or die('Access Deny！(DB)');
		$this->version = mysqli_get_server_info($this->link);
		// fan 2016-06-02 start
		// 内置方法设置字符集 提高效率并减少日志数量
		// $this->query("SET NAMES " . $this->charset);
		mysqli_set_charset($this->link, $this->charset);
		// fan 2016-06-02 end
	}

	function version() {
		return $this->version;
	}

	/**
	 * 执行sql
	 * @param $sql
	 * @return bool|DB_Result|mysqli_result
	 */
	function query($sql) {
		if ($sql == '') {
			return '';
		}

		$sqlType = ltrim(strtoupper(substr($sql, 0, 6)));
		//if($sqlType=='SELECT'){
		$sql = $this->setSelectPre($sql);
		//$sql = str_replace('###_',$this->pre,$sql);
		//}

		if (!mysqli_ping($this->link)) {
			mysqli_close($this->link);
			$this->connect();
		};
		$this->_sqls[] = $sql;

		// fan 2016-03-30 start
		// 记录开始时间
		$time = -microtime(true);
		// end 2016-03-30 end

		$this->result = mysqli_query($this->link, $sql); #is_bool(UPDATE,DELETE,INSERT);return Boolean

		// fan 2016-03-30 start
		if (is_bool($this->result) && $this->result === false) {
			// 部署模式记录 错误sql
			//Log::write(mysqli_errno($this->link) . ' - ' . mysqli_error($this->link) . '! SQL: ' . $sql, 'ERR');
            // 错误则退出
            $errno = $this->errno;
            $log   = $errno . ' - ' . $this->error . '! SQL: ' . $sql;
            Log::write($log, 'ERR');

		} elseif (IS_DEV) {
			// dev模式下记录所有sql
			// dev模式下记录结束时间
            $time += microtime(true);
            $log = $time . 'S : ' . $sql;
            Log::record($log, 'SQL');
		}

		// if (is_bool($this->result) && $this->result === false) {
		// 	if (IS_DEV) {
		// 		ShowError('#' . mysqli_errno($this->link) . ' - ' . mysqli_error($this->link) . '<br />SQL：' . $sql);
		// 	} else {
		// 		ShowError('#' . mysqli_errno($this->link) . ' - ' . mysqli_error($this->link));
		// 	}
		// }
		// fan 2016-03-30 end
		if (strtoupper(substr(ltrim($sql), 0, 6)) != 'SELECT') {
			return $this->result ? $this->affected_rows() : false;
		 	} else {
			return new DB_Result($this->result);
		 	}

	}

	/**
	 * 查询语句表前缀设置
	 * @param $segments
	 * @return string
	 */
	function setTablePre($segments) {
		/*if(!isset($segments[1]))return $segments[0];
			        $pre = '###_';
			        #echo '<pre>';print_r($segments);echo '</pre>';

			        $sql = strtoupper($segments[1]).' ';

			        if(strpos(strtolower($segments[2]),'select')!==false)return $segments[0];

			        if(isset($segments[2])){
			            $item = explode(',',preg_replace('#\s+#',' ',$segments[2]));
			            foreach($item as $val){
			                $subitem = explode(' ',ltrim($val));
			                $table = str_replace('`','',$subitem[0]);
			                $subitem[0] = '`'.(substr($table,0,4)==$pre
			                        ? str_replace($pre,$this->pre,$table)
			                        : $this->pre.$table).'`';

			                $sql.= implode(' ',$subitem).',';
			            }
			        }

			        $sql = substr($sql,0,-1).' ';
		*/

		//Feng 2016-03-24 start
		if (!isset($segments[1])) {
			return $segments[0];
		}

		// fan 2016-04-19 start
		// 解决不是分表 变量未定义的 E_NOTICE 级别错误
		$is_add = false;
		// fan 2016-04-19 end
		$table = trim($segments[1]);
		$pre   = '###_';
		//判断是否分表，修改为分表前缀
               
                if(substr($table, -1)=='`'){
                    $is_add = true;
                    $table = trim($table,'`');
                }
		$table = $this->pre . $table;
		
		return $is_add?$table . "` ":$table . " ";
		//Feng end
		//return $sql;
	}

	/**
	 * 设置查询语句表前缀
	 * @param $sql
	 * @return mixed
	 */
	function setSelectPre($sql) {

		/*$cases = '\sand\s|\son\s|\sleft\s|\sright\s|\sjoin\s|\swhere\s|\sorder\s|\shaving\s|\sgroup\s|\slimit\s|$';
			        $sql = preg_replace_callback('/(from)([\w\W]+?)('.$cases.')/i',array($this,'setTablePre'),$sql);
		*/

		//Feng 2016-03-24 update
		$sql = $sql . " ";
		$sql = preg_replace_callback('|###_(.*)\s|isU', array($this, 'setTablePre'), $sql);
		return $sql;
	}

	/**
	 * 返回查询的多行记录
	 * @param $sql
	 * @return array
	 */
	function select($sql) {
		return $this->query($sql)->result_array();
	}

	/**
	 * 返回查询的单行记录
	 * @param $sql
	 * @return array|null
	 */
	function get($sql) {
		return $this->query($sql)->row_array();
	}

	/** 返回单个字段值
	 * @param $sql
	 * @param string $field
	 * @return mixed
	 */
	function getstr($sql, $field = '') {
		$row = $this->get($sql);
		if (!empty($field)) {
			return $row[$field];
		} else {
			return is_array($row) ? $row[key($row)] : '';
		}
	}

	/**
	 * 插入数据库数据
	 * @param $table
	 * @param $data
	 * @return bool|DB_Result|mysqli_result
	 */
	function insert($table, $data, $echo = false) {
		$table = $this->pre_table($table);
		$keys  = $vals  = array();
		foreach ($data as $key => $val) {
			$keys[] = mysqli_real_escape_string($this->link, $key);
			$vals[] = mysqli_real_escape_string($this->link, $val);
		}
		$names  = '`' . implode('`,`', $keys) . '`';
		$values = "'" . implode("','", $vals) . "'";
		$values = badWord($values);
		if ($echo) {
			echo "INSERT INTO `{$table}`(" . $names . ") VALUES(" . $values . ")";
		}

		$res = $this->query("REPLACE INTO `{$table}`(" . $names . ") VALUES(" . $values . ")");
		return $this->insert_id() ? $this->insert_id() : $res;
	}

	/**
	 * 更新数据库数据
	 * @param string $table
	 * @param string|array $data
	 * @param array $where
	 * @return bool|mysqli_result
	 */
	function update($table, $data, $where) {
		$table = $this->pre_table($table);

		if (is_array($data)) {
			$up = array();
			foreach ($data as $k => $v) {
				$up[] = "`" . mysqli_real_escape_string($this->link, $k) . "`='" . mysqli_real_escape_string($this->link, $v) . "'";
			}
			$setParams = implode(',', $up);
		} else {
			$setParams = $data;
		}
		$setParams = badWord($setParams);

		$condition = '';
		if (is_array($where)) {
			$conds = array();
			foreach ($where as $k => $v) {
				$conds[] = "`" . mysqli_real_escape_string($this->link, $k) . "`='" . mysqli_real_escape_string($this->link, $v) . "'";
			}

			$condition = implode(' AND ', $conds);
		} else {
			$condition = $where;
		}
		return $this->query("UPDATE `$table` SET " . $setParams . " WHERE " . $condition);
	}

	/** 自动判断更新/创建
	 * @param $table
	 * @param $data
	 * @param $field
	 * @param string $where
	 */
	function save($table, $post, $fields = '', $where = '') {
		if (empty($fields)) {
			$table_fields = $this->get_fields($table);
			$table_fields = array_keys($table_fields);
			$fields       = $table_fields;
		}

		$data = array();
		foreach ($post as $k => $v) {
			if (in_array($k, $fields)) {
				$data[$k] = $v;
			}
		}

		if (!empty($where)) {return $this->update($table, $data, $where);} else {return $this->insert($table, $data);}
	}

	/**
	 * 删除数据
	 * @param $table
	 * @param $where
	 * @return bool|DB_Result|mysqli_result
	 */
	function delete($table, $where) {
		$table = $this->pre_table($table);
		if (is_array($where) && count($where) == 0) {
			return false;
		}

		$conds     = array();
		$condition = '';
		if (is_array($where)) {
			foreach ($where as $key => $val) {
				if (is_array($val)) {
					$items = array();
					foreach ($val as $v) {
						$items[] = is_numeric($v)
						? $v
						: "'" . mysqli_real_escape_string($this->link, $v) . "'";
					}

					$conds[] = "`$key` IN (" . implode(',', $items) . ")";
				} else {
					$conds[] = "`$key`='" . mysqli_real_escape_string($this->link, $val) . "'";
				}
			}
			$condition = implode(' AND ', $conds);
		} else {
			$condition = $where;
		}

		return $this->query("DELETE FROM `$table` WHERE " . $condition);

	}

	/**
	 * 加入一个表的相关字段数据
	 * --------------------
	 * @param array $data    需要增加字段的数据
	 * @param string $table  往哪个表加数据
	 * @param string $select 查询的字段,将join到$data中
	 * @param string $filter 源数据过滤出的id
	 * @param string $where  新表的条件字段IN($ids)
	 * @param array  $conds  附加字段
	 * @param string $pre    新数据前缀
	 * @return mixed         返回原数据
	 */
	function lJoin($data = array(), $table = '', $select = 'id,uid', $filter = '', $where = '', $pre = '', $conds = array()) {
		if (empty($data)) {
			return $data;
		}

		$ids   = array();
		$in    = $filter;
		$table = '###_' . $table;
		foreach ($data as $v) {
			if (!empty($v[$in])) {
				$ids[$v[$in]] = $v[$in];
			}
		}

#获取where in 的 ids

		$attach = array();
		if (count($ids) > 0) {
			$condition = $where . " IN(" . implode(',', $ids) . ")";
			if (!is_array($conds)) {$condition .= " AND " . $conds;} elseif (count($conds) > 0) {
				foreach ($conds as $k => $v) {
					$condition .= " AND " . addslashes($k) . "='" . addslashes($v) . "'";
				}

			}
			$res = $this->select("SELECT " . $select . " FROM `" . $table . "` WHERE " . $condition);
			foreach ($res as $v) {
				$attach[$v[$where]] = $v;
			}
#数据对接
		}

		$join_field = explode(',', $select);

		foreach ($data as $k => $v) {
			foreach ($join_field as $val) {
				$val                   = trim(trim($val), '`');
				$data[$k][$pre . $val] = isset($attach[$v[$in]][$val]) ? $attach[$v[$in]][$val] : '';
			}
		}
		return $data;
	}
        
        #Feng 2015-05-24 批量插入方法 start       
        /**
         * 批量插入记录
         * @access public
         * @param mixed $dataSet 数据集
         * @param array $options 参数表达式
         * @param boolean $replace 是否replace
         * @return false | integer
        */
	function insertAll($table, array $insert_array = array(), array $param = array(), $echo = false) {
                $table = $this->pre_table($table);
		//$first_array = array_slice($insert_array, 1, 1);
		if (!(isset($insert_array[0]) && is_array($insert_array[0]))) {
			return false;
		}
		$values = array();
		foreach ($insert_array as $index => $data) {
			$data = array_merge($data, $param);
			$insert_array[$index] = $data;
			$value = array();
			foreach ($data as $key=>$val) {
				$val = $this->parseValue($val);
				if (is_scalar($val)){
                                    $value[] = $val;
				}
			}
			$values[] = '('.implode(',',$value).')';
		}
		//$first_array = array_slice($insert_array, 1, 1);
		$fields = array_keys($insert_array[0]);
		array_walk($fields, array($this, 'parseKey'));
		$sql = 'REPLACE INTO `' . $table . '` (' . implode(',', $fields) . ') VALUES ' . implode(',', $values);
                if ($echo) {
			echo $sql;
		}
		$res = $this->query($sql);		
		// insert操作会锁定全表 于是知道第一个id 跟count即可知道所有插入的id
		return $res ? array(
			'first' => $this->insert_id(),
			'count' => $this->affected_rows(),
		) : false;
	}
        
	#fan 2015-06-03 批量插入方法 可以返回所有插入的详细内容 start
	/**
	 * 批量插入方法 可以返回所有插入的详细内容 用来确定自动插入的id 或者其他信息
	 * @param  string $table        批量插入的表名
	 * @param  array  $insert_array 需要被插入的数组
	 * @param  array  $param        想合并到每行的记录 比如create_time
	 * @param  string $orderBy      作为查询的排序依据 通常为自增主键 或者插入时间 用来获取最后插入的数据内容
	 * @return mixed      			批量插入的数据 或false
	 */
	function lockInsertAll($table, array $insert_array = array(), array $param = array(), $orderBy = 'id') {
		$table = $this->pre_table($table);
		if (!is_array($insert_array[0])) {
			return false;
		}
		$values = array();
		foreach ($insert_array as $index => $data) {
			$data = array_merge($data, $param);
			$insert_array[$index] = $data;
			$value = array();
			foreach ($data as $key => $val) {
				$val = $this->parseValue($val);
				if (is_scalar($val)) {
					$value[] = $val;
				}
			}
			$values[] = '(' . implode(',', $value) . ')';
		}
		$fields = array_keys($insert_array[0]);
		array_walk($fields, array($this, 'parseKey'));
		$sql = 'REPLACE INTO `' . $table . '` (' . implode(',', $fields) . ') VALUES ' . implode(',', $values);
		$this->query('LOCK TABLE `' . $table . '` WRITE');
		$count = $this->query($sql);
		if ($count) {
			$res = $this->select('SELECT * FROM `' . $table . '`  ORDER BY ' . $orderBy . ' DESC LIMIT ' . $count);
		} else {
			$res = false;
		}
		$this->query('UNLOCK TABLES;');
		return $res;
	}
        /**
	 * 格式化字段
	 *
	 * @param string $key 字段名
	 * @return string
	 */
        function parseKey(&$key){
            $key   =  trim($key);
            if(!preg_match('/[,\'\"\*\(\)`.\s]/',$key)) {
               $key = '`'.$key.'`';
            }
            return $key;
        }
        
        /**
        * 格式化值
        *
        * @param mixed $value
        * @return mixed
        */
        function parseValue($value){
           $value = addslashes(stripslashes($value));//重新加斜线，防止从数据库直接读取出错
           return "'".$value."'";
        }
        #Feng 2015-05-24 批量插入方法 end
        
	/**
	 * 返回查询的数据记录数
	 * @return int
	 */
	function num_rows() {return mysqli_num_rows($this->result);}

	/**
	 * 返回最后一次插入数据的自增id
	 * @return int|string
	 */
	function insert_id() {return mysqli_insert_id($this->link);}

	function fetch_assoc() {return mysqli_fetch_assoc($this->result);}
	function fetch_array() {return mysqli_fetch_array($this->result);}

	/**
	 * 影响行数
	 * @return int
	 */
	function affected_rows() {return mysqli_affected_rows($this->link);}

	/**
	 * 批量更新排序
	 * @param $order
	 * @param $table
	 */
	function uporder($order, $table, $listorder = 'order', $key = 'id') {
//更新排序
		$table = $this->pre_table($table);
		$s     = '';
		$ids   = array();
		foreach ($order as $k => $v) {
			if (empty($k)) {
				continue;
			}

			$v = intval($v);
			$s .= " WHEN $k THEN $v";
			$ids[] = $k;
		}
		$ids = implode(',', $ids);
		return $this->query("UPDATE `" . $table . "` SET `$listorder`= CASE `$key` $s END WHERE `$key` IN ($ids)");
	}

	/**
	 * table加前缀
	 */
	function pre_table($table) {
		if ($table == '') {
			return '';
		}

		$pre = '###_';

		/*$table = (strpos($table,$pre) !== false)
			            ? str_replace($pre,$this->pre,$table)
		*/

		//Feng 2016-03-24 update start
		$table = trim($table);
		$table = ltrim($table, $pre);
		$table = ltrim($table, $this->pre);
		//去掉###_和zz_
		// if (strpos($table, $pre) !== false) {
		// 	$table = substr($table, 4);
		// } elseif (strpos($table, $this->pre) !== false) {
		// 	$table = substr($table, strpos($table, $this->pre));
		// }
		//判断是否分表，修改为分表前缀
		$table = $this->pre . $table;
		
		//Feng update end
		return $table;
	}

	/**
	 * 获取表字段
	 * @param $table 数据表
	 * @return array
	 */
	public function get_fields($table) {
		$table  = $this->pre_table($table);
		$fields = array();
		$this->query("SHOW COLUMNS FROM $table");
		while ($r = $this->fetch_array()) {
			$fields[$r['Field']] = $r['Type'];
		}
		return $fields;
	}

	/**
	 * 检查不存在的字段
	 * @param $table 表名
	 * @return array
	 */
	public function check_fields($table, $array) {
		$fields   = $this->get_fields($table);
		$nofields = array();
		foreach ($array as $v) {
			if (!array_key_exists($v, $fields)) {
				$nofields[] = $v;
			}
		}
		return $nofields;
	}

	/**
	 * 检查表是否存在
	 * @param $table 表名
	 * @return boolean
	 */
	public function table_exists($table) {
		$table  = $this->pre_table($table);
		$tables = $this->list_tables();
		return in_array($table, $tables) ? 1 : 0;
	}

	/** 遍历所有表
	 * @return array
	 */
	public function list_tables() {
		$tables = array();
		$this->query("SHOW TABLES");
		while ($r = $this->fetch_array()) {
			$tables[] = $r['Tables_in_' . $this->dbname];
		}
		return $tables;
	}
        
     /** 遍历所有表
	 * @return array
	 */
	public function list_tables_datation() {
		$tables = array();
		$this->query("SHOW TABLE STATUS");
		while ($r = $this->fetch_assoc()) {
			$tables[] = $r;
		}
		return $tables;
	}
        
        /** 
	 * @return array
	 */
	public function show_table($table) {	
                $this->result = mysqli_query($this->link, "SHOW CREATE TABLE `{$table}`");
		return $this->fetch_assoc();
	}

}

#数据查询扩展类
class DB_Result {
	function __construct($result) {$this->result = $result;}
	function row_array() {if($this->result)return mysqli_fetch_assoc($this->result);}
	function result_array() {
		$ret = array();while ($a = mysqli_fetch_assoc($this->result)) {
			$ret[] = $a;
		}
		return $ret;}
	function num_rows() {return mysqli_num_rows($this->result);}
}