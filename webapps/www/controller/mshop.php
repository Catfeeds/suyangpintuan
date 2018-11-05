<?php

/**
 * 微店控制器
 * Feng	2016-05-11
 */
class mshop extends Lowxp {
	function index($mid, $page = 1) {
		if ($mid == false) {
			return false;
		}

		$this->smarty->assign('data', $data);
		$this->display_before($data['row']);
		$this->smarty->display("agent/promotion.html");
	}
}