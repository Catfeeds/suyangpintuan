<?php

/**
 * 站内信
 */
class message_model extends Lowxp_Model {
	private $baseTable = '###_message';
	private $extTable  = '###_message_status';

	public $types = array(
		'商户消息',
		'会员消息',
		'分销消息',
		'订单消息',
		'佣金消息',
		'营销消息',
		'其他消息',
	);

	public function getDetail($id, $mid) {
		$sql = 'SELECT b.mid as sender,b.id,b.type,b.title,b.content,b.create_time,a.status,a.mid FROM `###_message_status` a, `###_message` b where a.message_id=b.id AND a.status>0 AND a.mid = ' . $mid . ' AND b.id=' . $id;
		return $this->db->get($sql);
	}

	/**
	 * 逗号分隔符消息标记为已删除
	 * @param  string  $ids 逗号分隔符message_status 表的 id
	 * @param  integer $mid 用户id
	 * @return integer      影响行数
	 */
	public function remove($ids, $mid) {
		return $this->db->update('message_status', 'status=0', 'id in (' . $ids . ') AND mid=' . $mid);
	}

	/**
	 * 逗号分隔符消息标记为已读
	 * @param  string  $ids 逗号分隔符message_status 表的 id
	 * @param  integer $mid 用户id
	 * @return integer      影响行数
	 */
	public function read($ids, $mid) {
		return $this->db->update('message_status', 'status=2', 'id in (' . $ids . ') AND mid=' . $mid);
	}

	/**
	 * 删除所有标记为已删除的消息 用来做定期的垃圾回收
	 * @return void
	 */
	public function trash() {
		$sql = 'SELECT a.message_id FROM ( SELECT * FROM `###_message_status`  GROUP BY message_id,status) a GROUP BY a.message_id HAVING count(a.status) =1 and status = 0;';

		$del = $this->db->select($sql);

		if (!$del) {
			return false;
		}

		$ids = join(',', array_column($del, 'message_id'));

		$this->db->delete('message', 'id in (' . $ids . ')');

		$this->db->delete('message_status', 'message_id in (' . $ids . ')');

	}

	/**
	 * 用户登录时将未读的点对点消息 写入引用
	 * 后期按月执行分表之前 要对所有用户执行本指令 确保可以分表后信息依然可以保持引用关系
	 * @param  integer $mid 用户id
	 * @return integer      影响行数
	 */
	public function getUnRead($mid) {
		$sql = 'INSERT INTO `###_message_status` SELECT "" AS id, a.target AS mid, a.id AS message_id, 1 AS STATUS FROM `###_message` a WHERE a.target = ' . $mid . ' AND NOT EXISTS ( SELECT b.message_id FROM `###_message_status` b WHERE b.mid = ' . $mid . ' AND a.id = b.message_id );';
		return $this->db->query($sql);
	}
}