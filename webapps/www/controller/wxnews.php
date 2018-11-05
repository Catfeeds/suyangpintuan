<?php
/**
 * Class wxnews
 */

class wxnews extends Lowxp {

	/**
	 * 图文详情
	 * @param $id
	 */
	public function detail($id) {
		is_numeric($id) || die;
		$item = $this->db->get("SELECT * FROM ###_wx_news_item WHERE id = " . $id);

		if (isset($item['id'])) {
			#浏览次数累加
			$this->db->update('wx_news_item', 'view_num=view_num+1', array('id' => $item['id']));
		}

		$this->load->model('upload');
		$item = $this->upload->getImgUrls($item, 'cover', 'gallery', array('thumb', 'src'));
		$this->smarty->assign('item', $item);

		$this->smarty->display('wechat/news_detail.html');
	}

	/**
	 * 扫码领取优惠券展示页
	 * @return void
	 */
	public function scanCoupon() {

		$tmp = $this->parseToken();

		$sql    = 'SELECT * FROM `###_coupon` WHERE id=' . $tmp['id'];
		$coupon = $this->db->get($sql);

		if (!$coupon) {
			$this->error('对不起.活动不存在或者已过期.', '/');
		}

		$url = url('/wechat/scanCouponHandle?t=' . $_GET['t']);

		$qrcode = creat_tmp_code($url);
		$this->smarty->assign('qrcode', $qrcode);
		$this->smarty->assign('coupon', $coupon);
		$this->smarty->display('coupon/scan.html');
	}

	/**
	 * 扫码领取优惠券
	 * @return void
	 */
	public function scanCouponHandle() {
		$tmp = $this->parseToken();

		$sql    = 'SELECT * FROM `###_coupon` WHERE id=' . $tmp['id'];
		$coupon = $this->db->get($sql);

		if (!$coupon) {
			$this->error('对不起.活动不存在或者已过期.', '/');
		}

		$url = '/wxnews/scanCoupon?t=' . $_GET['t'];

		if (0 >= $coupon['stock']) {
			$this->error('对不起.优惠券已被领完下次请早哦', $url);
		}

		// 判断是否已经领取过
		$sql = 'SELECT id FROM `###_coupon_log` WHERE coupon_id = ' . $tmp['id'] . ' AND mid=' . MID;
		$tmp = $this->db->get($sql);
		if ($tmp) {
			$this->error('对不起.您已经领取过优惠券了哦', $url);
		}

		$this->load->model('coupon');
		if ($this->coupon->sendOne(MID, $tmp['id'], 2)) {
			$this->success('恭喜你, 获得优惠券一张', '/coupon/index');
		} else {
			$this->error('对不起, 扫码抢优惠券的人太多.请稍后重试', $url);
		};
	}

	/**
	 * 领取分享型优惠券展示页
	 *
	 *
	 *
	 * @param  string $token 加密以后的参数集合
	 * @return void
	 */
	public function getShare() {
		if (IS_AJAX) {
			$this->getShareHandle();
			die;
		}

		$tmp = $this->parseToken();

		if (!isset($tmp['mid'])) {
			URL_404();
			die;
		}

		$this->load->model('coupon');
		$coupon = $this->coupon->getFullCouponLog($tmp['id']);

		if (!$coupon) {
			$this->error('活动不存在或者已过期');
		}

		if ($coupon['mid'] == $tmp['mid']) {
			$this->error('你不可以领取自己的分享券');
		}

		if (isset($_SESSION['mid'])) {
			$this->load->model('member');
			$member = $this->member->member_info($_SESSION['mid']);

			if ($member) {
				$this->smarty->assign('member', $member);
			}
		}

		$this->smarty->assign('sendUsername', getUsername($tmp['mid']));
		$this->smarty->assign('coupon', $coupon);
		$this->smarty->display('coupon/receive.html');
	}

	/**
	 * 领取分享券处理方法 暂时先假设一定会有$_SESSION['mid']
	 * @return json
	 */
	public function getShareHandle() {
		// 尝试绑定用户手机号
		$this->load->model('member');
		$res = $this->member->bindMobile();

		if (isset($res['error'])) {
			$this->error($res['msg']);
		}

		$tmp = $this->parseToken();

		if (isset($_SESSION['mid'])) {
			$this->load->model('coupon');
			$res = $this->coupon->share($_SESSION['mid'], $tmp['id']);

			if (isset($res['msg'])) {
				if ($res['error']) {
					$this->error($res['msg']);
				} else {
					$this->success($res['msg'], '/coupon/index');
				}
			}
		} else {
			IS_DEV && log::write('无法获取登录会员信息');
			$this->error('对不起,请先登录并绑定手机号');
		}

	}

	/**
	 * 扫码和分享领券解析token
	 * @param  string $token 加密以后的参数集合
	 * @return array
	 */
	public function parseToken() {

		if (isset($_GET['t'])) {
			$token = $_GET['t'];
		} else {
			URL_404();die;
		}

		$tmp = url_decrypt($token, AuthKey);
		if (empty($tmp['id']) ) {
			URL_404();
			die;
		}
		return $tmp;
	}

}