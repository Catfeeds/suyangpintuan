<?php

/**


 * ZZCMS 支付方式


 * ============================================================================


 * * 版权所有 2014-2016 厦门紫竹数码科技有限公司，并保留所有权利。


 * 网站地址: http://www.lnest.com；


 * ----------------------------------------------------------------------------


 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和


 * 使用；不允许对程序代码以任何形式任何目的的再发布。


 *


 */

class payment extends Lowxp {

	function __construct() {

		#按钮

		$this->btnMenu = array(

			0 => array('url' => '#!payment/index', 'name' => '支付方式'),

		);

		parent::__construct();

		#加载

		$this->load->model('payment');

	}

	function index($page = 1) {

		#已安装的支付方式

		$pay_list = array();

		$sql = "SELECT * FROM " . $this->payment->baseTable . " WHERE enabled = '1' ORDER BY pay_order";

		$res = $this->db->select($sql);

		foreach ($res as $v) {

			$pay_list[$v['pay_code']] = $v;

		}

		#从文件夹读取所有支付方式

		$modules = read_modules(AppDir . 'includes/modules/payment');

		global $_LANG;

		for ($i = 0; $i < count($modules); $i++) {

			$code = $modules[$i]['code'];

			$modules[$i]['pay_code'] = $modules[$i]['code'];

			/* 如果数据库中有，取数据库中的名称和描述 */

			if (isset($pay_list[$code])) {

				$modules[$i]['pay_id'] = $pay_list[$code]['pay_id'];

				$modules[$i]['name'] = $pay_list[$code]['pay_name'];

				$modules[$i]['pay_fee'] = $pay_list[$code]['pay_fee'];

				$modules[$i]['is_cod'] = $pay_list[$code]['is_cod'];

				$modules[$i]['desc'] = $pay_list[$code]['pay_desc'];

				$modules[$i]['pay_order'] = $pay_list[$code]['pay_order'];

				$modules[$i]['install'] = '1';

			} else {

				$modules[$i]['name'] = $_LANG[$modules[$i]['code']];

				if (!isset($modules[$i]['pay_fee'])) {

					$modules[$i]['pay_fee'] = 0;

				}

				$modules[$i]['desc'] = $_LANG[$modules[$i]['desc']];

				$modules[$i]['install'] = '0';

			}

		}

		$this->smarty->assign('list', $modules);

		$this->smarty->display('manage/payment/list.html');

	}

	//安装 编辑

	function install($cod) {

		if (!$cod) {
			die;
		}

		#编辑时获取详情

		$sql = "SELECT * FROM " . $this->payment->baseTable . " WHERE pay_code = '$cod' AND enabled = '1' ";

		$pay = $this->db->get($sql);

		/* 取相应插件信息 */

		$set_modules = true;

		include_once AppDir . 'includes/modules/payment/' . $cod . '.php';

		global $_LANG;

		$data = $modules[0];

		if (empty($pay)) {

			/* 对支付费用判断。如果data['pay_fee']为false无支付费用，为空则说明以配送有关，其它可以修改 */

			if (isset($data['pay_fee'])) {
				$data['pay_fee'] = trim($data['pay_fee']);
			} else {
				$data['pay_fee'] = 0;
			}

			$pay['pay_code'] = $data['code'];

			$pay['pay_name'] = $_LANG[$data['code']];

			$pay['pay_desc'] = $_LANG[$data['desc']];

			$pay['is_cod'] = $data['is_cod'];

			$pay['pay_fee'] = $data['pay_fee'];

			$pay['is_online'] = $data['is_online'];

		}

		/* 编辑时取得配置信息 */

		if (is_string($pay['pay_config'])) {

			$store = unserialize($pay['pay_config']);

			/* 取出已经设置属性的code */

			$code_list = array();

			foreach ($store as $key => $value) {

				$code_list[$value['name']] = $value['value'];

			}

			$pay['pay_config'] = array();

			/* 循环插件中所有属性 */

			foreach ($data['config'] as $key => $value) {

				$pay['pay_config'][$key]['desc'] = (isset($_LANG[$value['name'] . '_desc'])) ? $_LANG[$value['name'] . '_desc'] : '';

				$pay['pay_config'][$key]['label'] = $_LANG[$value['name']];

				$pay['pay_config'][$key]['name'] = $value['name'];

				$pay['pay_config'][$key]['type'] = $value['type'];

				if (isset($code_list[$value['name']])) {

					$pay['pay_config'][$key]['value'] = $code_list[$value['name']];

				} else {

					$pay['pay_config'][$key]['value'] = $value['value'];

				}

				if ($pay['pay_config'][$key]['type'] == 'select' ||

					$pay['pay_config'][$key]['type'] == 'radiobox') {

					$pay['pay_config'][$key]['range'] = $_LANG[$pay['pay_config'][$key]['name'] . '_range'];

				}

			}
                        

		}

		//安装时取得配置信息

		else {

			$pay['pay_config'] = array();

			foreach ($data['config'] AS $key => $value) {

				$config_desc = (isset($_LANG[$value['name'] . '_desc'])) ? $_LANG[$value['name'] . '_desc'] : '';

				$pay['pay_config'][$key] = $value +

				array('label' => $_LANG[$value['name']], 'value' => $value['value'], 'desc' => $config_desc);

				if ($pay['pay_config'][$key]['type'] == 'select' ||

					$pay['pay_config'][$key]['type'] == 'radiobox') {

					$pay['pay_config'][$key]['range'] = $_LANG[$pay['pay_config'][$key]['name'] . '_range'];

				}

			}

		}

		//生成图片控件

		$pay['html_thumb'] = $this->form->files('thumb', $pay['thumb']);

		//编辑

		$this->smarty->assign('row', $pay);

		$this->smarty->display('manage/payment/edit.html');

	}

	#安装编辑

	function submit() {

		if (isset($_POST['Submit'])) {

			$res = $this->payment->save();

			if (isset($res['code']) && $res['code'] == 0) {

				$this->tip($res['message'], array('inIframe' => true));

				$this->exeJs("parent.com.xhide();parent.main.refresh()");

			} else {

				$this->tip($res['message'], array('inIframe' => true, 'type' => 1));

			}

			exit;

		}

	}

	//卸载

	function uninstall() {

		$code = trim($_REQUEST['id']);

		if (!$code) {
			die;
		}

		/* 把 enabled 设为 0 */

		admin_log('卸载支付方式：' . $this->db->getstr("SELECT pay_name FROM " . $this->payment->baseTable . " WHERE pay_code='" . $code . "'"));

		$this->db->update($this->payment->baseTable, array('enabled' => 0), array('pay_code' => $code));

		$this->tip('卸载成功', array('type' => 1));

	}

}