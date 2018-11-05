/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : lnest_pintuan_v2_one

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2017-11-06 15:52:47
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `zz_account_log`
-- ----------------------------
DROP TABLE IF EXISTS `zz_account_log`;
CREATE TABLE `zz_account_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `mid` int(10) NOT NULL COMMENT '会员ID',
  `username` varchar(60) NOT NULL DEFAULT '' COMMENT '用户名',
  `pay_points` int(10) NOT NULL DEFAULT '0' COMMENT '支付积分',
  `rank_points` int(10) NOT NULL DEFAULT '0' COMMENT '等级积分',
  `db_points` int(10) DEFAULT '0' COMMENT '夺宝币',
  `user_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '可用金额',
  `frozen_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '冻结金额',
  `give_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '充值赠送',
  `stage` varchar(10) NOT NULL COMMENT '操作阶段',
  `desc` varchar(255) NOT NULL,
  `logtime` int(10) NOT NULL COMMENT '操作时间',
  `pay_points_total` int(10) DEFAULT NULL,
  `rank_points_total` int(10) DEFAULT NULL,
  `db_points_total` int(10) DEFAULT NULL,
  `user_money_total` decimal(10,2) DEFAULT NULL,
  `frozen_money_total` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`) USING BTREE,
  KEY `mid` (`mid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='交易记录表';

-- ----------------------------
-- Records of zz_account_log
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_ad`
-- ----------------------------
DROP TABLE IF EXISTS `zz_ad`;
CREATE TABLE `zz_ad` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `userid` int(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(40) NOT NULL DEFAULT '',
  `url` varchar(60) NOT NULL DEFAULT '',
  `listorder` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(11) unsigned NOT NULL DEFAULT '0',
  `lang` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `title_style` varchar(40) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `width` text NOT NULL,
  `height` text NOT NULL,
  `images` mediumtext NOT NULL,
  `typeid` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8 COMMENT='广告表';

-- ----------------------------
-- Records of zz_ad
-- ----------------------------
INSERT INTO `zz_ad` VALUES ('1', '1', '1', 'lnest_admin', '/content/show//1', '0', '1448344483', '1502963846', '1', '首页焦点图', '', '[]', '', '', '[{\"path\":\"\\/style\\/img\\/01.jpg\",\"title\":\"\\/topic\\/index\\/19\",\"iosurl\":\"\",\"anurl\":\"\"}]', '1');
INSERT INTO `zz_ad` VALUES ('56', '1', '1', 'lnest_admin', '/content/show//56', '0', '1475915338', '1502963887', '1', '分享页焦点图 ', '', '[]', '', '', '[{\"path\":\"\\/style\\/img\\/01.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"}]', '2');
INSERT INTO `zz_ad` VALUES ('59', '1', '1', 'lnest_admin', '/content/show//59', '0', '1475915614', '1502963931', '1', '抽奖页面广告', '', '[]', '', '', '[{\"path\":\"\\/style\\/img\\/cjtb.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"}]', '6');
INSERT INTO `zz_ad` VALUES ('60', '1', '1', 'lnest_admin', '/content/show//60', '0', '1475915636', '1502964006', '1', '试用页面广告', '', '[]', '', '', '[{\"path\":\"\\/style\\/img\\/01.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"}]', '5');
INSERT INTO `zz_ad` VALUES ('61', '1', '1', 'lnest_admin', '/content/show//61', '0', '1476011698', '1502963797', '1', 'pc首页焦点图', '', '[]', '', '', '[{\"path\":\"\\/default\\/img\\/pc_01.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"}]', '51');
INSERT INTO `zz_ad` VALUES ('66', '1', '1', 'lnest_admin', '/content/show//66', '0', '1495522130', '1502964021', '1', '新人专享广告', '', '[]', '', '', '[{\"path\":\"\\/style\\/img\\/01.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"}]', '7');
INSERT INTO `zz_ad` VALUES ('67', '1', '1', 'lnest_admin', '/content/show//67', '0', '1495523666', '1502964041', '1', '新人专享优惠券', '', '[]', '', '', '[{\"path\":\"\\/style\\/img\\/01.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"},{\"path\":\"\\/style\\/img\\/01.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"}]', '8');
INSERT INTO `zz_ad` VALUES ('68', '1', '1', 'lnest_admin', '/content/show//68', '0', '1495617779', '1502964049', '1', '海淘banner', '', '[]', '', '', '[{\"path\":\"\\/style\\/img\\/01.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"}]', '11');
INSERT INTO `zz_ad` VALUES ('69', '1', '1', 'lnest_admin', '/content/show//69', '0', '1495694479', '1502964236', '1', '海淘推荐专题', '', '[]', '', '', '[{\"path\":\"\\/style\\/img\\/01.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"},{\"path\":\"\\/style\\/img\\/01.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"},{\"path\":\"\\/style\\/img\\/01.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"},{\"path\":\"\\/style\\/img\\/01.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"}]', '12');
INSERT INTO `zz_ad` VALUES ('70', '1', '1', 'lnest_admin', '/content/show//70', '0', '1495778122', '1502964241', '1', '团长免单', '', '[]', '', '', '[{\"path\":\"\\/style\\/img\\/01.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"}]', '13');
INSERT INTO `zz_ad` VALUES ('71', '1', '1', 'lnest_admin', '/content/show//71', '0', '1495778282', '1501206879', '1', '众筹尝鲜', '', '[]', '', '', '[]', '14');
INSERT INTO `zz_ad` VALUES ('72', '1', '1', 'lnest_admin', '/content/show//72', '0', '1495786367', '1502964269', '1', '品牌清仓', '', '[]', '', '', '[{\"path\":\"\\/style\\/img\\/wap_9.png\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"},{\"path\":\"\\/style\\/img\\/wap_9.png\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"},{\"path\":\"\\/style\\/img\\/wap_9.png\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"}]', '15');
INSERT INTO `zz_ad` VALUES ('73', '1', '1', 'lnest_admin', '/content/show//73', '0', '1495792697', '1502964292', '1', '新品页广告', '', '[]', '', '', '[{\"path\":\"\\/style\\/img\\/01.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"},{\"path\":\"\\/style\\/img\\/01.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"}]', '16');
INSERT INTO `zz_ad` VALUES ('74', '0', '1', 'lnest_admin', '/content/show//74', '0', '1496802903', '1502964077', '1', '首页广告', '', '[]', '', '', '[{\"path\":\"\\/style\\/img\\/01.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"}]', '17');

-- ----------------------------
-- Table structure for `zz_apk_version`
-- ----------------------------
DROP TABLE IF EXISTS `zz_apk_version`;
CREATE TABLE `zz_apk_version` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `userid` int(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(40) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `title_style` varchar(40) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `keywords` varchar(120) NOT NULL DEFAULT '',
  `description` mediumtext NOT NULL,
  `content` text NOT NULL,
  `url` varchar(60) NOT NULL DEFAULT '',
  `template` varchar(40) NOT NULL DEFAULT '',
  `posid` varchar(40) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `listorder` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(11) unsigned NOT NULL DEFAULT '0',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(11) unsigned NOT NULL DEFAULT '0',
  `lang` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `upload` mediumtext NOT NULL,
  `name` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`id`,`status`,`listorder`) USING BTREE,
  KEY `catid` (`id`,`catid`,`status`) USING BTREE,
  KEY `listorder` (`id`,`catid`,`status`,`listorder`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_apk_version
-- ----------------------------
INSERT INTO `zz_apk_version` VALUES ('1', '27', '1', 'lnest_admin', '1.1', '', '', '', '', '<p>\r\n	初始版本\r\n</p>\r\n<p>\r\n	<span style=\"white-space:normal;\">初始版本初始版本</span><span style=\"white-space:normal;\">初始版本初始</span> \r\n</p>\r\n<p>\r\n	初始版本初始版本\r\n</p>\r\n<p>\r\n	初始版本\r\n</p>\r\n<p>\r\n	初始版本初始版本\r\n</p>', '/content/show/27/1', '', '', '1', '0', '0', '1477448055', '1482816471', '1', '[{\"path\":\"\\/upload\\/1\\/files\\/base\\/p\\/l\\/922.apk\",\"title\":\"pin_new\"}]', '2');

-- ----------------------------
-- Table structure for `zz_app`
-- ----------------------------
DROP TABLE IF EXISTS `zz_app`;
CREATE TABLE `zz_app` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(60) DEFAULT NULL,
  `app` varchar(60) DEFAULT NULL COMMENT 'APP名称',
  `secretkey` varchar(60) DEFAULT NULL COMMENT '密钥',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_app
-- ----------------------------
INSERT INTO `zz_app` VALUES ('1', '云购', 'yunbuy', '0fd1ff545d0df8cc9e464370c5b2eddd');

-- ----------------------------
-- Table structure for `zz_article`
-- ----------------------------
DROP TABLE IF EXISTS `zz_article`;
CREATE TABLE `zz_article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `userid` int(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(40) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `title_style` varchar(40) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `keywords` varchar(120) NOT NULL DEFAULT '',
  `description` mediumtext NOT NULL,
  `content` mediumtext NOT NULL,
  `url` varchar(60) NOT NULL DEFAULT '',
  `template` varchar(40) NOT NULL DEFAULT '',
  `posid` varchar(40) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `listorder` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(11) unsigned NOT NULL DEFAULT '0',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(11) unsigned NOT NULL DEFAULT '0',
  `lang` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `publish` text NOT NULL,
  `link` text NOT NULL,
  `logo` mediumtext NOT NULL,
  `ewm` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`id`,`status`,`listorder`) USING BTREE,
  KEY `catid` (`id`,`catid`,`status`) USING BTREE,
  KEY `listorder` (`id`,`catid`,`status`,`listorder`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8 COMMENT='文章表';

-- ----------------------------
-- Records of zz_article
-- ----------------------------
INSERT INTO `zz_article` VALUES ('28', '22', '1', 'lnest_admin', '如何跟团', '', '[]', '', '', '<h3 style=\"font-family:Simsun;white-space:normal;\">\r\n	跟团流程\r\n</h3>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第一：首先关注公众账号或下载拼团的APP应用，登录账号\r\n</p>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第二：通过以下的方法可以跟团\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/n/v/860_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n	<p>\r\n		通过页面弹出来的开团提示\r\n	</p>\r\n</div>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/n/w/861_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n	<p>\r\n		通过首页的就等你了进入跟团列表\r\n	</p>\r\n</div>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/n/y/863_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n	<p>\r\n		通过商品详细页面进入跟团\r\n	</p>\r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	通过微信朋友圈，微信好友传递，微信拼团群、QQ交流群等等一些方式来进行跟团\r\n</p>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第三：进入别人的开团详细页面，点击我要参团进入商品详细，查看商品是不是你需要的：\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/n/x/862_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第四：如果该商品有其它选项的话就会弹出一个选择框如下图，如果商品没有规格选择直接进入第五步：\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/n/p/854_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第五：进入确认订单页面，在这个页面可以进行以下操作：添加和修改收货地址、购买数量、给商家留言、使用优惠劵（有的商品会有团长优惠或免单等、如果是入驻商品家也可以同时使用商家优惠劵）、选择支付方式，默认是微信支付，设置完成后点击支付按钮进行支付环节\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/n/z/864_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第六：支付完成后会进入分享页面，分享页面时默认会提示您让你分享给好友或朋友圈来快速组团如下图：\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/n/r/856_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第七：支付成功后，团要在24小时内邀请到对应的人数才能表示组团成功 下图为组团成功状态：\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/n/s/857_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第八：支付成功后，团要在24小时内没邀请到对应的人数才能表示组团失败，组团失败后系统后系统会自动退款（原路返还） 下图为组团失败状态：\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/n/t/858_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>', '/content/show/22/28', '', '', '1', '19', '15', '1472717950', '1480405820', '1', '', '', '[]', '[]');
INSERT INTO `zz_article` VALUES ('29', '22', '1', 'lnest_admin', '如何开团', '', '[]', '', '', '<h3>\r\n	开团流程\r\n</h3>\r\n<p>\r\n	第一：首先关注公众账号或下载拼团的APP应用，登录账号\r\n</p>\r\n<p>\r\n	第二：进入首页或列表页面选择心仪的一个商品如图所示,点击图片或开团按钮进入商品详细页面\r\n</p>\r\n<div style=\"text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/n/n/852_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p>\r\n	第三：进入商品的详细页面，右下方的拼团按钮，如图所示：\r\n</p>\r\n<div style=\"text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/n/o/853_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p>\r\n	第四：如果该商品有其它选项的话就会弹出一个选择框如下图，如果商品没有规格选择直接进入第五步：\r\n</p>\r\n<div style=\"text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"img/03.png\" /><img src=\"http://pin.lnest.cc/upload/images/gallery/n/p/854_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p>\r\n	第五：进入确认订单页面，在这个页面可以进行以下操作：添加和修改收货地址、购买数量、给商家留言、使用优惠劵（有的商品会有团长优惠或免单等、如果是入驻商品家也可以同时使用商家优惠劵）、选择支付方式，默认是微信支付，设置完成后点击支付按钮进行支付环节\r\n</p>\r\n<div style=\"text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/n/q/855_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p>\r\n	第六：支付完成后会进入分享页面，分享页面时默认会提示您让你分享给好友或朋友圈来快速组团如下图：\r\n</p>\r\n<div style=\"text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/n/r/856_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p>\r\n	第七：支付成功后，团要在24小时内邀请到对应的人数才能表示组团成功  下图为组团成功状态：\r\n</p>\r\n<div style=\"text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/n/s/857_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p>\r\n	第八：支付成功后，团要在24小时内没邀请到对应的人数才能表示组团失败，组团失败后系统后系统会自动退款（原路返还） 下图为组团失败状态：<img src=\"http://pin.lnest.cc/upload/images/gallery/n/t/858_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" />\r\n</p>\r\n<div style=\"text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"img/07.png\" /> \r\n</div>', '/content/show/22/29', '', '', '1', '20', '30', '1472718850', '1480401841', '1', '', '', '[]', '[]');
INSERT INTO `zz_article` VALUES ('30', '25', '1', 'lnest_admin', '御泥坊特卖专场御泥坊特卖专场', '', '[{\"path\":\"\\/upload\\/images\\/gallery\\/3\\/e\\/123_src.jpg\",\"title\":\"img1\"}]', '', '', '御泥坊，从品牌成立至今，坚持自主研发，传承古法传承古法。御泥坊，从品牌成立至今，坚持自主研发，传承古法传承古法御泥坊，从品牌成立至今，坚持自主研发，传承古法传承古法', '/content/show/25/30', '', '', '1', '0', '0', '1476179566', '1479882865', '1', '', '', '[{\"path\":\"\\/upload\\/images\\/gallery\\/3\\/c\\/121_src.png\",\"title\":\"m-logo\"}]', '[{\"path\":\"\\/upload\\/images\\/gallery\\/n\\/g\\/845_src.jpg\",\"title\":\"qr_code\"}]');
INSERT INTO `zz_article` VALUES ('32', '22', '2', 'admin', '什么是拼团', '', '[]', '', '', '<span style=\"font-size:14px;line-height:2;\">是应用于微信公众号的微商城系统，它最大的特点是不但能快速提升销量，而且还能让已经购买的客户数量快速裂变倍增。每个商品都有单独购买价和拼团价，当您通过拼团价购买时，开团支付成功后再邀请朋友参团，参团人数达到组团规定时，订单生效。在规定时间内，若人数不足组团失败，我们会在拼团失败后自动退款。</span><br />\r\n<span style=\"font-size:14px;line-height:2;\">（1）拼购，是基于好友的转发扩散，获取团购优惠。首先选择商品开团：选择拼团商品下单，团即刻开启，但团长完成支付，方能获取转发链接，并在团开启(24)小时内邀请到相应人数的好友支付，此团方能成功</span><br />\r\n<span style=\"font-size:14px;line-height:2;\">（2）团长：开团且该团第一个支付成功的人</span><br />\r\n<span style=\"font-size:14px;line-height:2;\">（3）参团成员：通过分享出去的该团入口进入并完成付款加入该团的团员，参团成员也可以将该团分享出去邀约更多的团员参团</span><br />\r\n<span style=\"font-size:14px;line-height:2;\">（4）团购成功：从团长开团(24)小时内找到相应开团人数的好友参团，则该团成功，卖家发货</span><br />', '/content/show/22/32', '', '', '1', '21', '39', '1480390439', '1480409542', '1', '', '', '[]', '[]');
INSERT INTO `zz_article` VALUES ('33', '22', '2', 'admin', '一元购操作详细说明', '', '[]', '', '', '<h3 style=\"font-family:Simsun;white-space:normal;\">\r\n	一元购\r\n</h3>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	特价商品只要一元钱就可以购买成功\r\n</p>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第一：一元购的入口\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/0/865_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第二：一元购的列表页面\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/1/866_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第三：一元购的商品开团\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/2/867_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第四：一元购的规则说明：\r\n</p>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	1、一元购的每个商品一人只能购买一次（开团的就不能跟团，跟团了的就不能开团）\r\n</p>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	2、一元购的所有的商品都是为邀新团意思是：老用户开团新用户参团（新用户是指在平台没有进行过消费的用户）\r\n</p>', '/content/show/22/33', '', '', '1', '16', '7', '1480404612', '1480420076', '1', '', '', '[]', '[]');
INSERT INTO `zz_article` VALUES ('34', '22', '2', 'admin', '免费试操作详细说明', '', '[]', '', '', '<h3 style=\"font-family:Simsun;white-space:normal;\">\r\n	免费试用\r\n</h3>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	本栏目用于放置免费的试用商品\r\n</p>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第一：免费试用的入口\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/3/868_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第二：免费试用的列表页面\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/4/869_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第三：免费试用的商品开团\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/5/870_src.png\" width=\"424\" height=\"601\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第四：免费试用的规则说明：\r\n</p>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	1、免费试用团没有新老用户限制，只是过得要通过自己去分享，来邀请好友和朋友来参与（每个商品每个用户只能参与一次，购买的数量也只能是一样）\r\n</p>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	2、免费试用团的开团记录不会出现在商品详细页面，首页的就等你了、参考团的人员得要通过自己分享\r\n</p>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	3、如何得到免费试用的商品：免费试用商品是通过抽奖模式来获取的：<br />\r\n商品是有时间规定的一般问题是在一周左右，在这一周之只只能拼团成功的开团者和参考者才有机会参考抽奖<br />\r\n开奖是要试用活动结束后就会开奖然后公布中奖的名单，抽取多少名中奖者可以通过商品详细页面的试用规则里说明\r\n</p>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	如何看果自己是否已经获取试用商品\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/6/871_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	活动结束后点击“查看活动结果”来查看自己是否已经获得\r\n</p>', '/content/show/22/34', '', '', '1', '15', '3', '1480406765', '1480420082', '1', '', '', '[]', '[]');
INSERT INTO `zz_article` VALUES ('35', '22', '2', 'admin', '抽奖团操作详细说明', '', '[]', '', '', '<h3 style=\"font-family:Simsun;white-space:normal;\">\r\n	抽奖团详细说明\r\n</h3>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	本栏目用于放置抽奖的商品\r\n</p>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第一：抽奖的入口\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/7/872_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第二：抽奖的列表页面\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/8/873_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第三：抽奖的的商品开团\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/9/874_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第四：抽奖的规则说明：\r\n</p>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	1、抽奖团是没有新老用户限制的，平台的所有会员都可以参考抽奖，注：每个抽奖商品每个会员只能参考一次\r\n</p>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	2、抽奖团的开团记录和跟团页面是不会出现在平台里的，这时得需要用户分享让好友朋友来参团。\r\n</p>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	3、抽奖团组团成功后是不能开奖的，因为抽奖团是有周期的一般都是在5-7天左右，当用户组团或开团成功成功后，这时只能等待活动结束\r\n</p>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	4、活动结束后随机抽取N名中奖者，获得抽奖的商品,注：抽取多少请看抽奖商品的详细页面上的投资规则有详细说明\r\n</p>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	5、未中奖的用户会全额退，必给予一些奖励详细的奖励可查看抽奖商品页面的抽奖规则说明\r\n</p>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	如何看果自己是否已经获取试用商品\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/a/875_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	活动结束后点击“查看活动结果”来查看自己是否已经获得\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/b/876_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	或是进入用户中心-点击我的抽奖来查看\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/c/877_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>', '/content/show/22/35', '', '', '1', '17', '1', '1480408127', '1480420070', '1', '', '', '[]', '[]');
INSERT INTO `zz_article` VALUES ('36', '22', '2', 'admin', 'AA团操作详细说明', '', '[]', '', '', '<h3 style=\"font-family:Simsun;white-space:normal;\">\r\n	AA团详细说明\r\n</h3>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第一：AA团的入口\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/d/878_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第二：AA团的列表页面\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/e/879_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第三：AA团的商品开团\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/f/880_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第四：AA团规则说明：\r\n</p>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	1、AA团是没有新老用户限制的，平台的所有会员都可以参考抽奖\r\n</p>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	2、AA团的开团记录和跟团页面是不会出现在平台里的，这时得需要用户分享让好友朋友来参团。（因为AA团是好友之间AA付款购买商品的）\r\n</p>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	3、AA团是由第一个支付的人员做为团长开团的，分享给好友、同事等等，只限于好友之间拼团\r\n</p>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	4、拼团成功后商家或平台发货只发给团长，团长收到货后然后由团长统一分配商品给参团人员\r\n</p>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	5、可以通过商品的详细页面来查看AA团的详细说明\r\n</p>', '/content/show/22/36', '', '', '1', '18', '6', '1480411548', '1480420064', '1', '', '', '[]', '[]');
INSERT INTO `zz_article` VALUES ('37', '22', '2', 'admin', '参团的人数决定拼团成功还是团队购买的商品总数量', '', '[]', '', '', '<span style=\"font-family:Simsun;font-size:medium;white-space:normal;\">决定拼团成功的只有“参团人数”，当参团的人数达到限制人数时，开团则拼团成功。 当然也会出现超出人数的情况比如一个团只差一个人，这时用户A和用户B同时下单子，这时就会产生超出人数的团。在限购的范围内，买家可以购买任意数量的商品， 但并不决定拼团是否成功。</span>', '/content/show/22/37', '', '', '1', '0', '2', '1480412620', '0', '1', '', '', '[]', '[]');
INSERT INTO `zz_article` VALUES ('38', '22', '2', 'admin', '拼团失败会怎么样', '', '[]', '', '', '<span style=\"font-family:Simsun;font-size:medium;white-space:normal;\">当到达拼团的限定时间时，如果参团人数不足，则会判定该团拼团失败。会将购物款退还给各位参团人员（包括团长）。</span>', '/content/show/22/38', '', '', '1', '0', '1', '1480412875', '1480412936', '1', '', '', '[]', '[]');
INSERT INTO `zz_article` VALUES ('39', '22', '2', 'admin', '拼团商城内只能选择拼团购买吗', '', '[]', '', '', '<span style=\"font-family:Simsun;font-size:medium;white-space:normal;\">拼团商城中除了选择和朋友一起拼团以外，也可以自己单独购买。单独购买的购物流程就和常规的电商商城相同。</span>', '/content/show/22/39', '', '', '1', '0', '0', '1480413051', '0', '1', '', '', '[]', '[]');
INSERT INTO `zz_article` VALUES ('40', '22', '2', 'admin', '海淘馆操作详细说明', '', '[]', '', '', '<h3 style=\"font-family:Simsun;white-space:normal;\">\r\n	海淘馆\r\n</h3>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第一：海淘馆的入口\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/g/881_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第二：海淘馆的馆类列表页面\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/h/882_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第三：海淘馆的商品列表页面\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/i/883_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第四：海淘馆的商品详细页面\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/j/884_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第五：海淘馆的规则说明：\r\n</p>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	1、海淘馆与普通拼团流程一样\r\n</p>', '/content/show/22/40', '', '', '1', '12', '8', '1480418063', '1480420099', '1', '', '', '[]', '[]');
INSERT INTO `zz_article` VALUES ('41', '22', '2', 'admin', '秒杀操作详细说明', '', '[]', '', '', '<h3 style=\"font-family:Simsun;white-space:normal;\">\r\n	秒杀\r\n</h3>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第一：秒杀的入口\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/k/885_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第二：秒杀的正在进行的列表\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/l/886_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	秒杀的都是受时间和库存的影响的，所有的商品都要抢的，如果时间结束了，开的团还没有拼团成功，表示拼团失败\r\n</p>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	秒杀时间还没有结束，库存已经没有了，开的团还没有拼团成功，表示拼团失败\r\n</p>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第三：秒杀即将开始的列表\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/m/887_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第四：秒杀的商品详细页面(与其他的拼团不同的时详细页面上会有一个结束时间)\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/n/888_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" /> \r\n</div>', '/content/show/22/41', '', '', '1', '13', '3', '1480419570', '1480420093', '1', '', '', '[]', '[]');
INSERT INTO `zz_article` VALUES ('42', '22', '2', 'admin', '超值大牌操作详细说明', '', '[]', '', '', '<h3 style=\"font-family:Simsun;white-space:normal;\">\r\n	超值大牌操作详细说明\r\n</h3>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第一：超值大牌的入口\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/o/889_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" />\r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第二：超值大牌的品牌分类列表(每个品牌的活动都是有周期的，点击一个品牌，进行这个品牌的商品列表页面)\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/p/890_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" />\r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第三：超值大牌的品牌商品列表\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/q/891_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" />\r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	在列表页面可以通过上方的品牌导航，点击可以切换下面的列表，在导航下方都有离活动结束时间，活动结束后所有的商品都是要下架的\r\n</p>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	第四：超值大牌的商品详细页面\r\n</p>\r\n<div style=\"font-family:Simsun;font-size:medium;white-space:normal;text-align:center;padding-bottom:10px;padding-top:10px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/images/gallery/o/r/892_src.png\" width=\"\" height=\"\" alt=\"\" title=\"\" />\r\n</div>\r\n<p style=\"font-family:Simsun;font-size:medium;white-space:normal;\">\r\n	超值大牌的拼团流程与普通拼团一样，只不过这个是活动是受时间与库存影响的，库存没有了或是时间结束了，没有拼团成功的团，就会转化为失败团\r\n</p>', '/content/show/22/42', '', '', '1', '14', '0', '1480420033', '1480420631', '1', '', '', '[]', '[]');
INSERT INTO `zz_article` VALUES ('43', '22', '2', 'admin', '如何查看我的开团情况', '', '[]', '', '', '', '/content/show/22/43', '', '', '1', '0', '0', '1480420990', '0', '1', '', '', '[]', '[]');

-- ----------------------------
-- Table structure for `zz_attribute`
-- ----------------------------
DROP TABLE IF EXISTS `zz_attribute`;
CREATE TABLE `zz_attribute` (
  `attr_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `cat_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '商品类型id',
  `attr_name` varchar(60) NOT NULL DEFAULT '' COMMENT '属性名称',
  `attr_input_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '录入方式',
  `attr_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '属性是否可选',
  `attr_values` text NOT NULL COMMENT '可选值列表',
  `attr_index` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '能否进行检索',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `is_linked` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '相同属性值的商品是否关联',
  `attr_group` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '属性分组',
  PRIMARY KEY (`attr_id`),
  KEY `cat_id` (`cat_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='商品附加属性表';

-- ----------------------------
-- Records of zz_attribute
-- ----------------------------
INSERT INTO `zz_attribute` VALUES ('1', '1', '颜色', '0', '0', '', '0', '0', '0', '0');
INSERT INTO `zz_attribute` VALUES ('2', '1', '尺寸', '0', '0', '', '0', '0', '0', '0');
INSERT INTO `zz_attribute` VALUES ('3', '1', '内存', '0', '0', '', '0', '0', '0', '0');

-- ----------------------------
-- Table structure for `zz_block`
-- ----------------------------
DROP TABLE IF EXISTS `zz_block`;
CREATE TABLE `zz_block` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mark` char(30) NOT NULL DEFAULT '' COMMENT '标识',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `lang` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `content` text COMMENT '内容',
  `listorder` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `pos` (`mark`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COMMENT='文章碎片';

-- ----------------------------
-- Records of zz_block
-- ----------------------------
INSERT INTO `zz_block` VALUES ('1', 'pintuan_note', '【wap产品详情】拼团玩法', '1', '<div class=\"detail\">\r\n	<div class=\"ptjj-tt\">\r\n		<h2>\r\n			拼团玩法介绍\r\n		</h2>\r\n<span class=\"close_note\"><img src=\"/style/img/gb.png\" /></span> \r\n	</div>\r\n	<p>\r\n		<span>1.开团：</span>选择心仪商品，点击“发起X人团”按钮，付款后即为开团成功；\r\n	</p>\r\n	<p>\r\n		<span>2.参团：</span>进入朋友分享的页面，点击“立即参团”按钮，付款后即为参团成功，若多人同时支付，支付成功时间较早的人获得参团资格；\r\n	</p>\r\n	<p>\r\n		<span>3.成团：</span>在开团或参团成功后，点击“邀请小伙伴参团”将页面分享给好友，在有效时间内凑齐人数即为成团，此时商家会开始发货；\r\n	</p>\r\n	<p>\r\n		<span>4.组团失败：</span>在有效时间内未凑齐人数，即为组团失败，此时付款项会原路退回到支付账户；\r\n	</p>\r\n	<p>\r\n		5.组团有效期间内，拼购商品订单不允许取消。\r\n	</p>\r\n</div>', '0');
INSERT INTO `zz_block` VALUES ('3', 'index_compare', '【pc首页】传统电商与社交电商的较量', '1', '<div class=\"title\">\r\n	<h2>\r\n		解决传统商家的4大痛点\r\n	</h2>\r\n	<h3>\r\n		使用港湾有巢拼团系统，借助微信巨额流量，充分利用朋友圈社交分享的力量，瞬间让您拥有病毒式销售引擎\r\n	</h3>\r\n</div>\r\n<ul class=\"sdtd-list clearfix\">\r\n	<li class=\"libg1\">\r\n		<h2>\r\n			便于推广\r\n		</h2>\r\n		<p>\r\n			港湾有巢拼团系统植根于微信，<br />\r\n借助于微信的熟人社交和朋友圈，<br />\r\n让拼团裂变病毒式传播，<br />\r\n通过自主分享带来顾客。\r\n		</p>\r\n	</li>\r\n	<li class=\"libg2\">\r\n		<h2>\r\n			优化群体\r\n		</h2>\r\n		<p>\r\n			利用线上庞大的用户基数和线下<br />\r\n优质店铺服务进行精准营销，<br />\r\n线上赚引流，线下赢口碑，<br />\r\n为您增强客户黏度。\r\n		</p>\r\n	</li>\r\n	<li class=\"libg3\">\r\n		<h2>\r\n			降低成本\r\n		</h2>\r\n		<p>\r\n			新品上市不知道市场反馈如何，<br />\r\n传统调研成本高，效率低，拼<br />\r\n团系统借助朋友圈传播，为企<br />\r\n业减少运营成本资金投入。\r\n		</p>\r\n	</li>\r\n	<li class=\"libg4\">\r\n		<h2>\r\n			快速引导\r\n		</h2>\r\n		<p>\r\n			库存积压多，找不到销售渠道？<br />\r\n利用拼团系统一键分享微信，<br />\r\n微博等社交媒体，迅速引流减少<br />\r\n库存积压。\r\n		</p>\r\n	</li>\r\n</ul>', '0');
INSERT INTO `zz_block` VALUES ('7', 'index_server', '【pc首页】拼团平台服务优势', '1', '<div class=\"title\">\r\n	<h2>\r\n		拼团平台服务优势\r\n	</h2>\r\n	<h3>\r\n		系统不断升级更新，适应市场发展需求\r\n	</h3>\r\n</div>\r\n<div class=\"clearfix ff-list\">\r\n	<img src=\"/default/img/ff1.png\" class=\"ff-img\" /> \r\n	<ul class=\"ff-ul\">\r\n		<li>\r\n			<img src=\"/default/img/ic1.png\" /> \r\n			<h3>\r\n				自由组团\r\n			</h3>\r\n			<p>\r\n				顾客随时自己发起拼团，凑够人数自动组团成功！\r\n			</p>\r\n		</li>\r\n		<li>\r\n			<img src=\"/default/img/ic2.png\" /> \r\n			<h3>\r\n				开团有礼\r\n			</h3>\r\n			<p>\r\n				团长开团有优惠，号召群众来拼团， 拼团成功为你省钱！\r\n			</p>\r\n		</li>\r\n		<li>\r\n			<img src=\"/default/img/ic3.png\" /> \r\n			<h3>\r\n				限时秒杀\r\n			</h3>\r\n			<p>\r\n				在特定的时间内，组团成功，购买超低价的商品\r\n			</p>\r\n		</li>\r\n		<li>\r\n			<img src=\"/default/img/ic4.png\" /> \r\n			<h3>\r\n				抽奖\r\n			</h3>\r\n			<p>\r\n				拼团参与者进行系统抽选，抽中者下单成功，未成功者返还金额\r\n			</p>\r\n		</li>\r\n		<li>\r\n			<img src=\"/default/img/ic5.png\" /> \r\n			<h3>\r\n				拼团广场\r\n			</h3>\r\n			<p>\r\n				不想开团的小伙伴，可以进入广场，拼你想拼的商品\r\n			</p>\r\n		</li>\r\n		<li>\r\n			<img src=\"/default/img/ic6.png\" /> \r\n			<h3>\r\n				邻居团\r\n			</h3>\r\n			<p>\r\n				首单购买当团长，号召群众来拼团， 团长分配商品\r\n			</p>\r\n		</li>\r\n		<li>\r\n			<img src=\"/default/img/ic7.png\" /> \r\n			<h3>\r\n				超值品牌\r\n			</h3>\r\n			<p>\r\n				大品牌特卖汇，首单购买当团长，号召群众来拼团\r\n			</p>\r\n		</li>\r\n		<li>\r\n			<img src=\"/default/img/ic3.png\" /> \r\n			<h3>\r\n				返券返红包\r\n			</h3>\r\n			<p>\r\n				购买成功的会员，返优惠券、红包，刺激再次购买！\r\n			</p>\r\n		</li>\r\n		<li>\r\n			<img src=\"/default/img/ic9.png\" /> \r\n			<h3>\r\n				海淘特卖汇\r\n			</h3>\r\n			<p>\r\n				海淘特卖汇，足不出户拼遍全球顶尖好货\r\n			</p>\r\n		</li>\r\n	</ul>\r\n</div>', '0');
INSERT INTO `zz_block` VALUES ('34', 'app_intro', '【wap】APP介绍页', '1', '<h3>\r\n	【产品简介】\r\n</h3>\r\n<p>\r\n	拼团是在去中心化大环境的团购衍生品。拼团玩法的核心在于利用社交网络(即流量渠道)及熟人间的信任，去达到吸粉和团购的双重目的。\r\n</p>\r\n<p>\r\n	与其他电商自主搜索式购物完全不同，港湾有巢拼团平台充分利用国内活跃用户数量排名第一的社交工具微信，以拼团模式抓住移动社交的红利——在购物行为中添加游戏的味道，用户通过在朋友圈或者微信群等社交传播方式，向朋友、亲人、邻居等发起拼团邀请，让原本单向、单调的“买买买”进化为朋友圈里有互动、有乐趣的“拼拼拼”，在拼团过程中获得分享与沟通的社交乐趣，享受全新的共享式购物体验，从而，自然地将社交流量变现为电商红利。\r\n</p>', '0');
INSERT INTO `zz_block` VALUES ('36', 'team_sort', '【wap】拼团王页面', '1', '<h2>\r\n	第10届港湾有巢团王大赛\r\n</h2>\r\n<p>\r\n	第一名：奖励iphone7\r\n</p>\r\n<p>\r\n	第二名：奖励iphone6\r\n</p>\r\n<p>\r\n	第三名：奖励iphone5\r\n</p>\r\n<p>\r\n	成团数高于50团以上的统一发价1000元的优惠劵\r\n</p>\r\n<p>\r\n	活动时间：2017年1月1日至2017年5月1日\r\n</p>', '0');
INSERT INTO `zz_block` VALUES ('37', 'app_about', '【APP】关于我们', '1', '<p style=\"color:#818181;font-family:微软雅黑;font-size:14px;background-color:#FFFFFF;\">\r\n	<span>港湾有巢是国内新零售移动社交电商平台，隶属于港湾有巢</span>，是国内新起的新零售移动社交电商平台。<span>推出的“新零售+移动社交+聚合营销”的购物理念，通过用户主动分享，产生商品大量曝光，从而基于人与人的社交网络产生交易裂变。满足消费者收获商品的同时，分享有价值的信息，是提高购买性最好的平台。</span> \r\n</p>\r\n<p style=\"color:#818181;font-family:微软雅黑;font-size:14px;background-color:#FFFFFF;\">\r\n	&nbsp; &nbsp;&nbsp;<span style=\"line-height:1.5;\">港湾有巢</span><span style=\"line-height:1.5;\">专注于原生态产品与生活消费领域，结合新零度售o+o模式达到O*O乘数效应，为用户提供国内外品质商品。通过平台社交通路，以全新的拼团模式，打造价比“口碑好货”，解决企业货品流通、触达精准用户、信任与保障等核心交易问题。</span> \r\n</p>\r\n<p class=\"MsoNormal\" style=\"color:#818181;font-family:微软雅黑;font-size:14px;text-indent:31.5pt;background-color:#FFFFFF;\">\r\n	<br />\r\n</p>\r\n<p class=\"MsoNormal\" style=\"color:#818181;font-family:微软雅黑;font-size:14px;background-color:#FFFFFF;\">\r\n	<span style=\"font-weight:bolder;\">港湾有巢</span><span style=\"font-weight:bolder;\">使命：</span><span style=\"font-weight:bolder;\"></span> \r\n</p>\r\n<p class=\"MsoNormal\" style=\"color:#818181;font-family:微软雅黑;font-size:14px;background:#FFFFFF;\">\r\n	<span>为剁手党而生</span><span>；</span><span>比价全网拼尽全球吃遍全世界</span><span>；</span> \r\n</p>\r\n<p class=\"MsoNormal\" style=\"color:#818181;font-family:微软雅黑;font-size:14px;background:#FFFFFF;\">\r\n	<span>爱拼才会赢；</span> \r\n</p>\r\n<p class=\"MsoNormal\" style=\"color:#818181;font-family:微软雅黑;font-size:14px;background:#FFFFFF;\">\r\n	<span><br />\r\n</span> \r\n</p>\r\n<p class=\"MsoNormal\" style=\"color:#818181;font-family:微软雅黑;font-size:14px;background:#FFFFFF;\">\r\n	<span style=\"font-weight:bolder;\">港湾有巢</span><span style=\"font-weight:bolder;\">愿景：</span> \r\n</p>\r\n<p class=\"MsoNormal\" style=\"color:#818181;font-family:微软雅黑;font-size:14px;background:#FFFFFF;\">\r\n	<span>新零售社交电商第一共享平台</span> \r\n</p>\r\n<p class=\"MsoNormal\" style=\"color:#818181;font-family:微软雅黑;font-size:14px;background:#FFFFFF;\">\r\n	<span><br />\r\n</span> \r\n</p>\r\n<p class=\"MsoNormal\" style=\"color:#818181;font-family:微软雅黑;font-size:14px;background:#FFFFFF;\">\r\n	<span style=\"font-weight:bolder;\">港湾有巢</span><span style=\"font-weight:bolder;\">价值观：</span> \r\n</p>\r\n<p class=\"MsoNormal\" style=\"color:#818181;font-family:微软雅黑;font-size:14px;background:#FFFFFF;\">\r\n	<span>客户第一：以客户为中心，时刻为客户着想，从细微之处做起，用心感动客户</span> \r\n</p>\r\n<p class=\"MsoNormal\" style=\"color:#818181;font-family:微软雅黑;font-size:14px;background:#FFFFFF;\">\r\n	<span>团队合作：共享共担，平凡人做平凡事</span> \r\n</p>\r\n<p class=\"MsoNormal\" style=\"color:#818181;font-family:微软雅黑;font-size:14px;background:#FFFFFF;\">\r\n	<span>拥抱变化：迎接变化，勇于创新</span> \r\n</p>\r\n<p class=\"MsoNormal\" style=\"color:#818181;font-family:微软雅黑;font-size:14px;background:#FFFFFF;\">\r\n	<span>诚信：诚实正直，言行坦荡</span> \r\n</p>\r\n<p class=\"MsoNormal\" style=\"color:#818181;font-family:微软雅黑;font-size:14px;background:#FFFFFF;\">\r\n	<span>激情：乐观向上，永不言弃</span> \r\n</p>\r\n<p class=\"MsoNormal\" style=\"color:#818181;font-family:微软雅黑;font-size:14px;background:#FFFFFF;\">\r\n	<span>敬业：专业执着，精益求精</span> \r\n</p>\r\n<p class=\"MsoNormal\" style=\"color:#818181;font-family:微软雅黑;font-size:14px;background:#FFFFFF;\">\r\n	<span>公益：遵循公德、符合公意</span> \r\n</p>\r\n<p>\r\n	<br />\r\n</p>', '0');
INSERT INTO `zz_block` VALUES ('38', 'index_apply', '【pc首页】商家入驻流程', '1', '<div class=\"tit-02\">\r\n	<h2>\r\n		商家入驻\r\n	</h2>\r\n	<p>\r\n		全新的拼团模式，爱拼才会赢，“港湾有巢”助您开启全新的拼团模式\r\n	</p>\r\n</div>\r\n<div class=\"lc-box\">\r\n	<img src=\"/default/img/lc.png\" /> \r\n	<ul class=\"clearfix lc-li\">\r\n		<li>\r\n			入驻申请\r\n		</li>\r\n		<li>\r\n			提交资料\r\n		</li>\r\n		<li>\r\n			等待审核\r\n		</li>\r\n		<li class=\"no\">\r\n			店铺上线\r\n		</li>\r\n	</ul>\r\n</div>', '0');

-- ----------------------------
-- Table structure for `zz_brand`
-- ----------------------------
DROP TABLE IF EXISTS `zz_brand`;
CREATE TABLE `zz_brand` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `catname` varchar(255) NOT NULL COMMENT '分类名称',
  `parentid` int(10) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `arrparentid` text NOT NULL COMMENT '父类ID组',
  `arrchildid` text NOT NULL COMMENT '子类ID组',
  `child` tinyint(1) NOT NULL COMMENT '是否有子级',
  `keywords` varchar(120) NOT NULL COMMENT '页面关键字',
  `description` int(255) NOT NULL COMMENT '页面描述',
  `listorder` smallint(5) NOT NULL DEFAULT '0' COMMENT '排序',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `url` varchar(255) NOT NULL COMMENT 'URL',
  `ismenu` smallint(3) NOT NULL DEFAULT '0' COMMENT '是否导航',
  `sale` decimal(3,1) NOT NULL,
  `start_time` int(11) NOT NULL,
  `end_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='品牌表';

-- ----------------------------
-- Records of zz_brand
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_business`
-- ----------------------------
DROP TABLE IF EXISTS `zz_business`;
CREATE TABLE `zz_business` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(64) NOT NULL COMMENT '商家登陆密码',
  `salt` varchar(10) NOT NULL COMMENT '密码加密码',
  `login_time` int(11) NOT NULL COMMENT '最后登陆时间',
  `name` varchar(50) NOT NULL COMMENT '店铺名称',
  `logo` varchar(100) NOT NULL COMMENT '店铺logo',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '类型 1:个人，2：企业',
  `truename` varchar(50) NOT NULL COMMENT '真实姓名',
  `card` varchar(32) NOT NULL COMMENT '身份证号',
  `card_image` varchar(100) NOT NULL COMMENT ' 身份证正面图',
  `card_image2` varchar(100) NOT NULL COMMENT '身份证背面图',
  `card_image3` varchar(100) NOT NULL COMMENT '手持身份证图',
  `mobile` varchar(12) NOT NULL COMMENT '手机号码',
  `qq` varchar(20) NOT NULL COMMENT 'QQ',
  `email` varchar(50) NOT NULL COMMENT '邮箱',
  `url` varchar(50) NOT NULL COMMENT '淘宝天猫店铺或其他网站',
  `note` varchar(200) NOT NULL COMMENT '备注',
  `percentage` decimal(5,2) NOT NULL COMMENT '平台佣金比例',
  `zone` int(11) NOT NULL COMMENT '所在地',
  `company` varchar(255) NOT NULL COMMENT '公司名称',
  `company_capital` varchar(20) NOT NULL,
  `company_range` varchar(500) NOT NULL,
  `company_url` varchar(30) NOT NULL COMMENT '公司网址',
  `company_agent` varchar(50) NOT NULL COMMENT '旗下／品牌代理',
  `license` varchar(50) NOT NULL COMMENT '营业执照注册号',
  `license_image` varchar(100) NOT NULL COMMENT '营业执照图片',
  `tax_image` varchar(100) NOT NULL COMMENT '税务登记证件扫描件',
  `code_image` varchar(100) NOT NULL COMMENT '组织机构代码证扫描件',
  `brand_auth` varchar(100) NOT NULL COMMENT '品牌授权书',
  `trademark` varchar(100) NOT NULL COMMENT '商标证书',
  `compay_username` varchar(50) NOT NULL COMMENT '法人姓名',
  `company_zone` int(11) NOT NULL COMMENT '联系地址',
  `company_addr` varchar(100) NOT NULL COMMENT '详细地址',
  `company_mobile` varchar(11) NOT NULL COMMENT '法人手机',
  `company_tel` varchar(20) NOT NULL COMMENT '法人固话',
  `mark` varchar(255) NOT NULL COMMENT '备注',
  `status` tinyint(2) NOT NULL COMMENT '状态 0:审核中，1:审核通过，2:审核不通过',
  `c_time` int(11) NOT NULL COMMENT '申请时间',
  `u_time` int(11) NOT NULL COMMENT '修改时间',
  `ad` text NOT NULL COMMENT '轮播广告图',
  `brand` text NOT NULL,
  `banner` varchar(100) NOT NULL COMMENT 'banner图',
  `fav_num` int(11) NOT NULL COMMENT '关注人数',
  `step` tinyint(2) NOT NULL COMMENT '申请步骤',
  `fee` decimal(10,2) NOT NULL COMMENT '认证费',
  `deposit` decimal(10,2) NOT NULL COMMENT '保证金',
  `pay_status` tinyint(2) NOT NULL COMMENT '支付状态',
  `user_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '余额',
  `check_status` tinyint(2) NOT NULL COMMENT '发布商品是否需要审核 0是，1否',
  `cid` int(11) NOT NULL COMMENT '商品分类',
  `typeid` tinyint(2) NOT NULL COMMENT '商家类型：0普通，1同城，2海淘',
  `kf_online` text NOT NULL COMMENT '第三方客服',
  `latitude` varchar(20) NOT NULL COMMENT '纬度',
  `longitude` varchar(20) NOT NULL COMMENT '经度',
  `geohash` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `geohash` (`geohash`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_business
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_business_account`
-- ----------------------------
DROP TABLE IF EXISTS `zz_business_account`;
CREATE TABLE `zz_business_account` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `bid` int(10) NOT NULL COMMENT '商家ID',
  `name` varchar(60) NOT NULL DEFAULT '' COMMENT '商家名称',
  `deposit` int(10) NOT NULL DEFAULT '0' COMMENT '保证金',
  `desc` varchar(255) NOT NULL COMMENT '备注',
  `logtime` int(10) NOT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`),
  KEY `id` (`id`) USING BTREE,
  KEY `mid` (`bid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_business_account
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_business_address`
-- ----------------------------
DROP TABLE IF EXISTS `zz_business_address`;
CREATE TABLE `zz_business_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL COMMENT '商家id 0为平台',
  `address` varchar(255) NOT NULL,
  `name` varchar(50) NOT NULL COMMENT '收货人',
  `mobile` varchar(11) NOT NULL COMMENT '手机号码',
  `c_time` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_business_address
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_business_bankcard`
-- ----------------------------
DROP TABLE IF EXISTS `zz_business_bankcard`;
CREATE TABLE `zz_business_bankcard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL COMMENT '商家id',
  `truename` varchar(50) NOT NULL COMMENT '真实姓名',
  `card` varchar(50) NOT NULL COMMENT '身份证',
  `bankcard` varchar(50) NOT NULL COMMENT '银行卡号',
  `mobile` varchar(11) NOT NULL COMMENT '银行预留手机号',
  `wx` varchar(30) DEFAULT NULL COMMENT '微信号',
  `alipay` varchar(30) DEFAULT NULL COMMENT '支付宝',
  `c_time` varchar(11) NOT NULL,
  `bank` varchar(45) DEFAULT NULL COMMENT '开户行',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_business_bankcard
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_business_bill`
-- ----------------------------
DROP TABLE IF EXISTS `zz_business_bill`;
CREATE TABLE `zz_business_bill` (
  `no` int(11) NOT NULL AUTO_INCREMENT COMMENT '结算单编号(年月店铺ID)',
  `start_date` int(11) NOT NULL COMMENT '开始日期',
  `end_date` int(11) NOT NULL COMMENT '结束日期',
  `order_totals` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单金额',
  `shipping_totals` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '运费',
  `order_return_totals` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '退单金额',
  `commis_totals` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '佣金金额',
  `commis_return_totals` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '退还佣金',
  `store_cost_totals` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '店铺促销活动费用',
  `system_comm` decimal(10,2) NOT NULL COMMENT '系统佣金',
  `result_totals` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '应结金额',
  `create_date` int(11) DEFAULT '0' COMMENT '生成结算单日期',
  `month` mediumint(6) unsigned NOT NULL COMMENT '结算单年月份',
  `state` enum('1','2','3','4') DEFAULT '1' COMMENT '1默认2店家已确认3平台已审核4结算完成',
  `pay_date` int(11) DEFAULT '0' COMMENT '付款日期',
  `pay_content` varchar(200) DEFAULT '' COMMENT '支付备注',
  `sid` int(11) NOT NULL COMMENT '店铺ID',
  `name` varchar(50) DEFAULT NULL COMMENT '店铺名',
  PRIMARY KEY (`no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='结算表';

-- ----------------------------
-- Records of zz_business_bill
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_business_bills`
-- ----------------------------
DROP TABLE IF EXISTS `zz_business_bills`;
CREATE TABLE `zz_business_bills` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(8) unsigned NOT NULL COMMENT '商家ID',
  `order_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单金额',
  `name` varchar(45) NOT NULL COMMENT '商家名称',
  `user_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '结算金额',
  `coupon_id_num` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '平台抵用券金额',
  `coupon_id_sid_num` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商家抵用券金额',
  `comm_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '佣金',
  `stage` varchar(45) NOT NULL,
  `desc` varchar(45) DEFAULT NULL,
  `logtime` int(10) unsigned NOT NULL,
  `order_sn` varchar(45) NOT NULL COMMENT '订单号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_business_bills
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_business_fav`
-- ----------------------------
DROP TABLE IF EXISTS `zz_business_fav`;
CREATE TABLE `zz_business_fav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL COMMENT '商家id',
  `mid` int(11) NOT NULL COMMENT '会员ID',
  `c_time` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_business_fav
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_cart`
-- ----------------------------
DROP TABLE IF EXISTS `zz_cart`;
CREATE TABLE `zz_cart` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mid` int(10) NOT NULL COMMENT '用户ID',
  `goods_id` int(10) NOT NULL COMMENT '云购ID',
  `spec` varchar(100) NOT NULL COMMENT '商品规格',
  `goods_name` varchar(255) NOT NULL COMMENT '产品名称',
  `cost_price` decimal(10,2) NOT NULL COMMENT '成本价',
  `goods_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品价格',
  `qty` smallint(6) NOT NULL COMMENT '购买数量',
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '小计',
  `type` smallint(3) NOT NULL DEFAULT '1' COMMENT '1商品 2秒杀 3团购',
  `obj_id` int(10) NOT NULL DEFAULT '0' COMMENT '其它促销类型ID',
  `is_selected` int(1) NOT NULL COMMENT '购物车是否选中',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='购物车表';

-- ----------------------------
-- Records of zz_cart
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_category`
-- ----------------------------
DROP TABLE IF EXISTS `zz_category`;
CREATE TABLE `zz_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `catname` varchar(30) NOT NULL DEFAULT '',
  `catdir` varchar(30) DEFAULT NULL,
  `parentdir` varchar(50) DEFAULT NULL,
  `parentid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `moduleid` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `module` char(24) DEFAULT NULL,
  `arrparentid` text,
  `arrchildid` text,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(150) DEFAULT NULL,
  `keywords` varchar(100) DEFAULT '',
  `description` varchar(255) DEFAULT NULL,
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `ishtml` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ismenu` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) DEFAULT NULL,
  `child` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `url` varchar(150) DEFAULT NULL,
  `template_list` varchar(20) DEFAULT NULL,
  `template_show` varchar(20) DEFAULT NULL,
  `pagesize` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `readgroup` varchar(100) NOT NULL DEFAULT '0',
  `listtype` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `lang` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `urlruleid` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `issystem` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parentid` (`parentid`) USING BTREE,
  KEY `listorder` (`listorder`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COMMENT='文章分类';

-- ----------------------------
-- Records of zz_category
-- ----------------------------
INSERT INTO `zz_category` VALUES ('22', '常见问题', null, null, '0', '2', 'article', '0', '22', '0', '', '', '', '0', '0', '0', '0', '[{\"path\":\"\\/default\\/img\\/ban3.jpg\",\"title\":\"ban3\"}]', '0', '/content/index/22', 'list', 'show', '10', '0', '0', '1', '0', '0');
INSERT INTO `zz_category` VALUES ('23', '关于我们', null, null, '0', '1', 'page', '0', '23', '0', '', '', '', '0', '0', '0', '0', '[{\"path\":\"\\/default\\/img\\/ban4.jpg\",\"title\":\"ban4\"}]', '0', '/content/index/23', 'index_about', '', '10', '0', '1', '1', '0', '0');
INSERT INTO `zz_category` VALUES ('24', '热点资讯', null, null, '0', '2', 'article', '0', '24', '0', '', '', '', '51', '0', '0', '0', '[{\"path\":\"\\/default\\/img\\/new.jpg\",\"title\":\"\\u70ed\\u70b9\\u8d44\\u8baf\"}]', '0', '/content/index/24', 'list', 'show', '10', '0', '0', '1', '0', '0');
INSERT INTO `zz_category` VALUES ('25', '精彩活动', null, null, '0', '2', 'article', '0', '25', '0', '', '', '', '0', '0', '0', '0', '', '0', '/content/index/25', 'list_business', '', '10', '0', '0', '1', '0', '0');
INSERT INTO `zz_category` VALUES ('26', '快报', null, null, '0', '10', 'kuaibao', '0', '26', '0', '', '', '', '0', '0', '0', '0', '', '0', '/content/index/26', '', '', '10', '0', '0', '1', '0', '0');
INSERT INTO `zz_category` VALUES ('27', 'apk版本', null, null, '0', '11', 'apk_version', '0', '27', '0', '', '', '', '0', '0', '0', '0', '', '0', '/content/index/27', '', '', '10', '0', '0', '1', '0', '0');
INSERT INTO `zz_category` VALUES ('28', '商家入驻', null, null, '0', '1', 'page', '0', '28', '0', '', '', '', '28', '0', '1', '0', '[{\"path\":\"\\/default\\/img\\/ban2.jpg\",\"title\":\"\\u5546\\u5bb6\\u5165\\u9a7b\"}]', '0', '/content/index/28', 'index_business', '', '10', '0', '1', '1', '0', '0');
INSERT INTO `zz_category` VALUES ('29', 'APP下载', null, null, '0', '1', 'page', '0', '29', '0', '', '', '', '52', '0', '1', '0', '[{\"path\":\"\\/default\\/img\\/appxz.jpg\",\"title\":\"1920x779\"}]', '0', '/content/index/29', 'index_app', '', '10', '0', '1', '1', '0', '0');
INSERT INTO `zz_category` VALUES ('30', '平台公告', null, null, '0', '2', 'article', '0', '30', '0', '', '', '', '53', '0', '1', '0', '[{\"path\":\"\\/default\\/img\\/ban3.jpg\",\"title\":\"\\u5546\\u5bb6\\u5e2e\\u52a9\"}]', '0', '/content/index/30', 'list_help', 'show_help', '10', '0', '0', '1', '0', '0');
INSERT INTO `zz_category` VALUES ('31', '入驻标准', null, null, '0', '1', 'page', '0', '31', '0', '', '', '', '0', '0', '0', '0', '[{\"path\":\"\\/default\\/img\\/ban4.jpg\",\"title\":\"ban4\"}]', '0', '/content/index/31', 'index', '', '10', '0', '1', '1', '0', '0');
INSERT INTO `zz_category` VALUES ('32', '加入我们', null, null, '0', '1', 'page', '0', '32', '0', '', '', '', '0', '0', '0', '0', '[{\"path\":\"\\/default\\/img\\/ban4.jpg\",\"title\":\"ban4\"}]', '0', '/content/index/32', 'index', '', '10', '0', '1', '1', '0', '0');
INSERT INTO `zz_category` VALUES ('33', '商家帮助', null, null, '0', '2', 'article', '0', '33', '0', '', '', '', '50', '0', '1', '0', '[{\"path\":\"\\/default\\/img\\/ban3.jpg\",\"title\":\"\\u5546\\u5bb6\\u5e2e\\u52a9\"}]', '0', '/content/index/33', 'list_help', 'show_help', '10', '0', '0', '1', '0', '0');

-- ----------------------------
-- Table structure for `zz_chat_autoreplay`
-- ----------------------------
DROP TABLE IF EXISTS `zz_chat_autoreplay`;
CREATE TABLE `zz_chat_autoreplay` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(11) NOT NULL DEFAULT '0' COMMENT '商户id',
  `keyword` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 模糊匹配 1 精确匹配',
  `content` text NOT NULL COMMENT '回复内容',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_chat_autoreplay
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_chat_config`
-- ----------------------------
DROP TABLE IF EXISTS `zz_chat_config`;
CREATE TABLE `zz_chat_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商户id',
  `guest_avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '游客头像',
  `service_avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '客服头像',
  `service_name` varchar(50) NOT NULL DEFAULT '' COMMENT '客服名称',
  `service_time` varchar(50) NOT NULL DEFAULT '' COMMENT '客服时间',
  `service_tel` varchar(50) NOT NULL DEFAULT '' COMMENT '客服电话',
  `welcome` text NOT NULL COMMENT '欢迎语',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态 0 关闭 1正常',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_chat_config
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_chat_record`
-- ----------------------------
DROP TABLE IF EXISTS `zz_chat_record`;
CREATE TABLE `zz_chat_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bid` int(11) NOT NULL DEFAULT '0' COMMENT '商户id',
  `type` varchar(50) NOT NULL DEFAULT '1' COMMENT '消息类型',
  `group_id` varchar(50) NOT NULL DEFAULT '' COMMENT '分组id',
  `service` int(11) NOT NULL DEFAULT '0' COMMENT '是否为客服',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '消息用户id 正数为 用户 负数为客服',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `avatar` varchar(255) NOT NULL DEFAULT '',
  `msg` text NOT NULL COMMENT '消息内容',
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `time_show` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_chat_record
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_commission`
-- ----------------------------
DROP TABLE IF EXISTS `zz_commission`;
CREATE TABLE `zz_commission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL COMMENT '会员ID',
  `username` varchar(60) NOT NULL DEFAULT '' COMMENT '会员名',
  `ivt_mid` int(11) NOT NULL COMMENT '被邀请者ID',
  `ivt_username` varchar(60) NOT NULL COMMENT '被邀请用户名',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单ID',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单金额',
  `commission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '佣金',
  `desc` varchar(255) NOT NULL COMMENT '描述',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `updatetime` int(11) NOT NULL COMMENT '结算时间',
  `level` int(2) NOT NULL COMMENT '佣金级别 >0分销商佣金，-1：省代理佣金，-2市代理佣金，0：合伙人佣金',
  `status` tinyint(2) NOT NULL COMMENT '是否到账',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='佣金记录表';

-- ----------------------------
-- Records of zz_commission
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_config`
-- ----------------------------
DROP TABLE IF EXISTS `zz_config`;
CREATE TABLE `zz_config` (
  `varname` varchar(20) NOT NULL DEFAULT '' COMMENT '参数名',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '配置名称',
  `group` varchar(20) NOT NULL DEFAULT 'index' COMMENT '分组',
  `value` text NOT NULL COMMENT '参数值',
  `tip` varchar(255) NOT NULL DEFAULT '' COMMENT '提示信息',
  `form_type` varchar(100) NOT NULL DEFAULT 'text' COMMENT '表单类型',
  `user` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为自定义参数',
  `lang` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '多语言',
  `step` text NOT NULL,
  `listorder` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  KEY `varname` (`varname`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='站点配置表';

-- ----------------------------
-- Records of zz_config
-- ----------------------------
INSERT INTO `zz_config` VALUES ('site_name', '网站名称', 'index', '港湾有巢拼团', '请最多输入5个汉字', '', '1', '1', '', '0');
INSERT INTO `zz_config` VALUES ('seo_title', 'SEO标题', 'index', '港湾有巢拼团', '', '', '1', '1', '', '0');
INSERT INTO `zz_config` VALUES ('seo_keywords', 'SEO关键词', 'index', '港湾有巢拼团', '', '', '1', '1', '', '0');
INSERT INTO `zz_config` VALUES ('seo_description', 'SEO简介', 'index', '港湾有巢拼团', '', 'textarea', '1', '1', '', '0');
INSERT INTO `zz_config` VALUES ('cloudurl2', '云存储地址2', 'index', '', '', 'text', '1', '2', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '16');
INSERT INTO `zz_config` VALUES ('mail_type', '邮件发送模式', 'mail', '1', '', 'radio', '1', '0', '{\"options\":\"SMTP \\u51fd\\u6570\\u53d1\\u9001|1\\r\\nMail \\u6a21\\u5757\\u53d1\\u9001|2\"}', '1');
INSERT INTO `zz_config` VALUES ('mail_server', '邮件服务器', 'mail', 'smtp.qq.com', '', 'text', '1', '0', '', '3');
INSERT INTO `zz_config` VALUES ('cloudurl', '云存储地址', 'index', '', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '33');
INSERT INTO `zz_config` VALUES ('bucketname', '云存储空间', 'index', '', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '33');
INSERT INTO `zz_config` VALUES ('mail_port', '服务器端口', 'mail', '25', '', 'text', '1', '0', '', '3');
INSERT INTO `zz_config` VALUES ('mail_send', '发件人地址	', 'mail', '', '<a href=\"javascript:;\" onclick=\"main.openmail(document.getElementById(\'mail_send\').value);\">点击进入邮箱</a>', 'text', '1', '0', '', '4');
INSERT INTO `zz_config` VALUES ('mail_user', '验证用户名', 'mail', '', '', 'text', '1', '0', '', '6');
INSERT INTO `zz_config` VALUES ('mail_password', '验证密码', 'mail', '', '', 'text', '1', '0', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"1\"}', '7');
INSERT INTO `zz_config` VALUES ('page_listrows', '后台列表分页数', 'system', '15', '', '', '1', '0', '{\"size\":\"60\",\"default\":\"\",\"ispassword\":\"0\"}', '2');
INSERT INTO `zz_config` VALUES ('verify_admin', '后台登陆验证码', 'system', '0', '', 'select', '1', '0', '{\"options\":\"\\u5f00\\u542f|1\\r\\n\\u5173\\u95ed|0\"}', '3');
INSERT INTO `zz_config` VALUES ('weibo_appid', '微博应用标识', 'oauth', '', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '7');
INSERT INTO `zz_config` VALUES ('weibo_appkey', '微博应用密钥', 'oauth', '', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '8');
INSERT INTO `zz_config` VALUES ('rank_points_login', '每日登录送经验值', 'points', '5', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"5\",\"ispassword\":\"0\"}', '2');
INSERT INTO `zz_config` VALUES ('pay_points_order', '完成订单获得积分', 'points', '1', '订单每1元能获得的积分数', 'text', '1', '1', '{\"size\":\"\",\"default\":\"1\",\"ispassword\":\"0\"}', '3');
INSERT INTO `zz_config` VALUES ('freight_free', '免运费额度', '', '', '订单达到此额度时，免运费（留空不免）', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '7');
INSERT INTO `zz_config` VALUES ('logCount', '参与人数', '', '390691', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '14');
INSERT INTO `zz_config` VALUES ('qq_appid', 'QQ应用标识', 'oauth', '', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '3');
INSERT INTO `zz_config` VALUES ('qq_appkey', 'QQ应用密钥', 'oauth', '', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '4');
INSERT INTO `zz_config` VALUES ('weibo_login', '微博登录', 'oauth', '0', '', 'select', '1', '1', '{\"options\":\"\\u5f00\\u542f|1\\r\\n\\u5173\\u95ed|0\",\"multiple\":\"0\",\"fieldtype\":\"varchar\",\"numbertype\":\"1\",\"size\":\"\",\"default\":\"0\"}', '5');
INSERT INTO `zz_config` VALUES ('contact_mobile', '商家消息接收手机', 'contact', '', '只用来接收模版消息 买家不可见', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '2');
INSERT INTO `zz_config` VALUES ('page_size', '前台列表分页数', 'system', '10', '', 'text', '1', '2', '{\"size\":\"60\",\"default\":\"\",\"ispassword\":\"0\"}', '1');
INSERT INTO `zz_config` VALUES ('page_size', '前台列表分页数', 'system', '10', '', 'text', '1', '1', '{\"size\":\"60\",\"default\":\"10\",\"ispassword\":\"0\"}', '1');
INSERT INTO `zz_config` VALUES ('mail_auth', 'AUTH LOGIN验证', 'mail', '0', '', 'radio', '1', '0', '{\"options\":\"\\u5426|0\\r\\n\\u662f|1\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"0\"}', '5');
INSERT INTO `zz_config` VALUES ('qq_login', 'QQ登录', 'oauth', '0', '', 'select', '1', '1', '{\"options\":\"\\u5f00\\u542f|1\\r\\n\\u5173\\u95ed|0\",\"multiple\":\"0\",\"fieldtype\":\"varchar\",\"numbertype\":\"1\",\"size\":\"\",\"default\":\"0\"}', '1');
INSERT INTO `zz_config` VALUES ('sms_mod', '发送模式', 'sms', '0', '调试模式将不下发短信，短信日志请查看应用目录www/log/下的日志文件《sms_log_互亿无线短信平台帐号.log》', 'radio', '1', '1', '{\"options\":\"\\u6b63\\u5e38\\u53d1\\u9001|0\\r\\n\\u8c03\\u8bd5\\u6a21\\u5f0f|1\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"0\"}', '3');
INSERT INTO `zz_config` VALUES ('mail_open', '邮件发送总开关', 'mail', '0', '', 'select', '1', '0', '{\"options\":\"\\u5173\\u95ed|0\\r\\n\\u5f00\\u542f|1\",\"multiple\":\"0\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"size\":\"\",\"default\":\"0\"}', '0');
INSERT INTO `zz_config` VALUES ('sms_open', '短信发送总开关', 'sms', '0', '', 'select', '1', '0', '{\"options\":\"\\u5173\\u95ed|0\\r\\n\\u5f00\\u542f|1\",\"multiple\":\"0\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"size\":\"\",\"default\":\"0\"}', '1');
INSERT INTO `zz_config` VALUES ('sms_type', '短信平台', 'sms', '1', '', 'radio', '1', '0', '{\"options\":\"\\u4e0a\\u6d77\\u4e92\\u4ebf|1\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"1\"}', '2');
INSERT INTO `zz_config` VALUES ('sms_username', '平台帐号', 'sms', '', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '3');
INSERT INTO `zz_config` VALUES ('sms_password', '平台密码', 'sms', '', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"1\"}', '4');
INSERT INTO `zz_config` VALUES ('rank_points_info', '完善资料送经验值', 'points', '20', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"50\",\"ispassword\":\"0\"}', '1');
INSERT INTO `zz_config` VALUES ('isPhoto', '赚拍币【上传头像】', 'money', '2', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '1');
INSERT INTO `zz_config` VALUES ('isVoice', '赚拍币【语音认证】', 'money', '1', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '2');
INSERT INTO `zz_config` VALUES ('isIdcard', '赚拍币【实名认证】', 'money', '1', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '3');
INSERT INTO `zz_config` VALUES ('isMail', '赚拍币【邮箱认证】', 'money', '1', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '4');
INSERT INTO `zz_config` VALUES ('isDaren', '赚拍币【夺宝达人】', 'money', '50', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '5');
INSERT INTO `zz_config` VALUES ('isJpDaren', '赚拍币【竞拍达人】', 'money', '50', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '6');
INSERT INTO `zz_config` VALUES ('voice_open', '语音验证码', 'iface', '1', '接口网址：http://www.yuntongxun.com/solution/smsAndLandingCall?kw=BPyzm', 'select', '1', '1', '{\"options\":\"\\u5f00\\u542f|1\\r\\n\\u7981\\u7528|0\",\"multiple\":\"0\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"size\":\"\",\"default\":\"1\"}', '1');
INSERT INTO `zz_config` VALUES ('voice_sid', 'ACCOUNT SID', 'iface', '', '语音验证码SID', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '2');
INSERT INTO `zz_config` VALUES ('voice_token', 'AUTH TOKEN', 'iface', '', '语音验证码TOKEN', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '3');
INSERT INTO `zz_config` VALUES ('comss', '分销开关', 'goods', '1', '', 'radio', '1', '1', '{\"options\":\"\\u5f00\\u542f|1\\r\\n\\u5173\\u95ed|0\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"0\"}', '0');
INSERT INTO `zz_config` VALUES ('keywords', '热门搜索', 'index', '商品、品牌、种类', '用空隔分隔关键词', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '11');
INSERT INTO `zz_config` VALUES ('time_home', '引导页停留时间', 'index', '0', '单位：秒', 'text', '1', '1', '{\"size\":\"50\",\"default\":\"1\",\"ispassword\":\"0\"}', '12');
INSERT INTO `zz_config` VALUES ('withdraw_fee', '提现手续费', '', '0.5%|一个工作日\r\n0.3%|三个工作日\r\n0|一周内', '含%时以百分比计算否则以固定值收取，格式：手续费|天数', 'textarea', '1', '1', '{\"fieldtype\":\"text\",\"width\":\"\",\"height\":\"\",\"default\":\"\"}', '13');
INSERT INTO `zz_config` VALUES ('wx_appid', '微信appid', 'wechat', '', '请输入微信appid', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '14');
INSERT INTO `zz_config` VALUES ('wx_appsecret', '微信appsecret', 'wechat', '', '请输入微信appsecret', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '15');
INSERT INTO `zz_config` VALUES ('wx_token', '微信token', 'wechat', '', '请输入微信token', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '16');
INSERT INTO `zz_config` VALUES ('wx_encodingaeskey', '微信encodingaeskey', 'wechat', '', '请输入微信encodingaeskey', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '17');
INSERT INTO `zz_config` VALUES ('business_tel', '固定电话', 'contact', '400-966-0901', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '3');
INSERT INTO `zz_config` VALUES ('order_num', '订单量', '', '3159', '', 'text', '1', '0', '', '0');
INSERT INTO `zz_config` VALUES ('copyright', '版权信息', 'index', 'Copyright©2014-2015 港湾有巢科技有限公司', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '28');
INSERT INTO `zz_config` VALUES ('icp_code', '备案号', 'index', '闽ICP备xxxxxx号', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '29');
INSERT INTO `zz_config` VALUES ('goods_isComment', '商品评价', '', '1', '关闭后商品详情页将不显示评价', 'radio', '1', '1', '{\"options\":\"\\u5f00\\u542f|1\\r\\n\\u5173\\u95ed|0\",\"fieldtype\":\"varchar\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"1\"}', '1');
INSERT INTO `zz_config` VALUES ('refund_days', '可退货天数', 'goods', '7', '0表示不可退货', 'text', '1', '1', '{\"size\":\"60\",\"default\":\"0\",\"ispassword\":\"0\"}', '2');
INSERT INTO `zz_config` VALUES ('yunuser', '云存储操作员', 'index', '', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '34');
INSERT INTO `zz_config` VALUES ('yunpwd', '云存储密码', 'index', '', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"1\"}', '35');
INSERT INTO `zz_config` VALUES ('is_rate', '合伙人开关', 'agent', '0', '合伙人开启与关闭', 'radio', '1', '1', '{\"options\":\"\\u5f00\\u542f|1\\r\\n\\u5173\\u95ed|0\",\"fieldtype\":\"varchar\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"1\"}', '13');
INSERT INTO `zz_config` VALUES ('agent_province', '地区代理省营业额的百分比', 'agent', '10', '百分比', 'text', '1', '1', '{\"size\":\"60\",\"default\":\"\",\"ispassword\":\"0\"}', '15');
INSERT INTO `zz_config` VALUES ('agent_city', '地区代理市营业额的百分比', 'agent', '5', '百分比', 'text', '1', '1', '{\"size\":\"60\",\"default\":\"\",\"ispassword\":\"0\"}', '16');
INSERT INTO `zz_config` VALUES ('is_upgrade_status', '升级条件', 'agent', '1', '分销等级的升级条件', 'radio', '1', '1', '{\"options\":\"\\u4ea4\\u6613\\u5b8c\\u6210\\u91d1\\u989d|0\\r\\n\\u5df2\\u4ed8\\u6b3e\\u91d1\\u989d|1\",\"fieldtype\":\"varchar\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"0\"}', '21');
INSERT INTO `zz_config` VALUES ('is_area', '区域代理开关', 'agent', '0', '区域代理开启与关闭', 'radio', '1', '1', '{\"options\":\"\\u5f00\\u542f|1\\r\\n\\u5173\\u95ed|0\",\"fieldtype\":\"varchar\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"1\"}', '14');
INSERT INTO `zz_config` VALUES ('confirm_days', '自动确认收货天数', 'goods', '15', '0表示关闭自动确认收货', 'text', '1', '1', '{\"size\":\"60\",\"default\":\"0\",\"ispassword\":\"0\"}', '1');
INSERT INTO `zz_config` VALUES ('cloudsave', '文件云存储', 'index', '0', '', 'select', '1', '1', '{\"options\":\"\\u5f00\\u542f|1\\r\\n\\u5173\\u95ed|0\",\"multiple\":\"0\",\"fieldtype\":\"varchar\",\"numbertype\":\"1\",\"size\":\"\",\"default\":\"0\"}', '32');
INSERT INTO `zz_config` VALUES ('contact_email', '商家消息接收邮箱', 'contact', '', '只用来接收模版消息 买家不可见', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '1');
INSERT INTO `zz_config` VALUES ('contact_hotline', '商家联系方式展示', 'contact', '', '本文字将直接展示给买家.并且不接收模版消息', 'text', '1', '0', '', '0');
INSERT INTO `zz_config` VALUES ('prov_sl', '商家所在地', '', '565', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '17');
INSERT INTO `zz_config` VALUES ('comm_time', '佣金到账时机', 'agent', '1', '佣金是在付款的时候给还是在确认收货的时候', 'radio', '1', '1', '{\"options\":\"\\u786e\\u8ba4\\u6536\\u8d27|0\\r\\n\\u5df2\\u4ed8\\u6b3e|1\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"0\"}', '22');
INSERT INTO `zz_config` VALUES ('goods_comm', '商品单独设置佣金', 'agent', '0', '开启时购买的每个商品对每个分销商都会生成一条佣金记录，关闭时一个订单只会生成一条佣金记录', 'radio', '1', '1', '{\"options\":\"\\u5f00\\u542f|1\\r\\n\\u5173\\u95ed|0\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"0\"}', '23');
INSERT INTO `zz_config` VALUES ('unit', '商品默认单位', 'goods', '件', '未设置单位的商品默认使用本设置作为单位 默认:件', 'text', '1', '1', '{\"size\":\"60\",\"default\":\"0\",\"ispassword\":\"0\"}', '3');
INSERT INTO `zz_config` VALUES ('lottery_rule', '抽奖规则', 'goods', '<p>活动时间【开始时间】-【结束时间】;</p>\r\n<p>1)【结束时间】后，从拼团成功用户中随机抽取【中奖人数】个中奖用户;</p>\r\n<p>2)运气爆棚奖【中奖人数】名：【标题】，【优惠券】;</p>\r\n<p>3)中奖用户会在名单公布后，尽快为您完成发货;</p>\r\n<p>4)未中奖用户抽奖结束后为您发起全额退款，请耐心等待到账</p>\r\n<p>5)中奖名单公布可以首页抽奖里查看呦~</p>', '', 'textarea', '1', '1', '{\"fieldtype\":\"text\",\"width\":\"450\",\"height\":\"150\",\"default\":\"\"}', '4');
INSERT INTO `zz_config` VALUES ('free_rule', '试用规则', 'goods', '<p>活动时间【开始时间】-【结束时间】;</p>\r\n<p>1)申请结束后从拼团成功的订单中随机抽取【中奖人数】名试用者赠送商品;</p>\r\n<p>2)拼团成功后即可获得【优惠券】;</p>\r\n<p>3)中奖用户会在名单公布后，尽快为您完成发货;</p>', '', 'textarea', '1', '1', '{\"fieldtype\":\"text\",\"width\":\"450\",\"height\":\"150\",\"default\":\"\"}', '5');
INSERT INTO `zz_config` VALUES ('aa_rule', 'AA规则', 'goods', '<p>1)由团长指定收货地址发起团购，发起后团长需支付一人价格；</p>\r\n<p>2)团长分享给好友支付所需金额，在有效时间内凑足人数购买，则卖家发货至团长指定收货地址；</p>\r\n<p>3)团长负责收货，并将货品均分给团成员；</p>\r\n<p>4)若有效时间内未凑齐人数购买，则团购失败，已付款项会原路退还到用户的支付帐号；</p>', '', 'textarea', '1', '1', '{\"fieldtype\":\"text\",\"width\":\"450\",\"height\":\"150\",\"default\":\"\"}', '6');
INSERT INTO `zz_config` VALUES ('skin_tpl', '前台风格', 'system', 'mobile', '', 'radio', '1', '1', '{\"options\":\"\\u9ed8\\u8ba4|mobile\\r\\n\\u7cbe\\u7b80|simple\",\"fieldtype\":\"varchar\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"mobile\"}', '4');
INSERT INTO `zz_config` VALUES ('app_checking', '苹果APP审核版本', 'index', '', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '23');
INSERT INTO `zz_config` VALUES ('app_logo', 'APP介绍页logo', '', '[]', '尺寸104*104', 'images', '1', '1', '{\"upload_maxnum\":\"\",\"upload_maxsize\":\"\",\"upload_allowext\":\"*.gif;*.jpg;*.jpeg;*.png\",\"watermark\":\"0\",\"more\":\"0\"}', '24');
INSERT INTO `zz_config` VALUES ('app_text', 'APP介绍页图文介绍', '', '[]', '尺寸250*100', 'images', '1', '1', '{\"upload_maxnum\":\"\",\"upload_maxsize\":\"\",\"upload_allowext\":\"*.gif;*.jpg;*.jpeg;*.png\",\"watermark\":\"0\",\"more\":\"0\"}', '25');
INSERT INTO `zz_config` VALUES ('ios_url', 'IOS下载地址', 'index', '', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '26');
INSERT INTO `zz_config` VALUES ('android_url', '安卓下载地址', 'index', '', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '27');
INSERT INTO `zz_config` VALUES ('comm_limit_max', '佣金提现最高金额', 'goods', '5000', '用户单次提现的最高金额(单位元)', 'text', '1', '1', '{\"size\":\"60\",\"default\":\"\",\"ispassword\":\"0\"}', '0');
INSERT INTO `zz_config` VALUES ('comm_limit_min', '佣金提现最低金额', 'goods', '0', '用户单次提现的最低金额(单位元)', 'text', '1', '1', '{\"size\":\"60\",\"default\":\"\",\"ispassword\":\"0\"}', '0');
INSERT INTO `zz_config` VALUES ('step_rule', '阶梯团规则', 'goods', '<p>1)用户选择好所要拼团的商品，进入开团页面，支付定金开团成功</p>\r\n<p>2)开团成功后，团的有效期为24小时，请急时把拼团分享页面分享给好友或朋友圈</p>\r\n<p>3)例如本团5人一个阶梯（价格为50元），10人一个阶梯（价格为：40元），24小时过后此团只有4个参团表示些拼团失败，系统退定金</p>\r\n<p>4)24小时过后此团的参团人数为6人表示此团参团成功，（请在24小时内补齐尾款），如果这6个人有2个没有支付尾款，商家会以50元的价格出售该商品，别外2个人补齐尾款的话，阶梯团也不会把定金退给客户</p>', '', 'textarea', '1', '1', '{\"fieldtype\":\"text\",\"width\":\"\",\"height\":\"\",\"default\":\"\"}', '7');
INSERT INTO `zz_config` VALUES ('share_rule', '推广团规则', 'goods', '<p>1)用户选择好所要拼团的商品，进入开团页面，支付完成后开团成功</p>\r\n<p>2)开团成功后，团的有效期为24小时，请及时把拼团分享页面分享给好友或朋友圈</p>\r\n<p>3)例如这个商品价值100元，要5个人成团，（如此团每单佣金为：5元），团长开团按100元支付后，分享给小伙伴参团，每参加一个人就递增5元</p>\r\n<p>4)此单组团成功交易完成后（也就是买家确认收到货后）团长可获得 20元</p>', '', 'textarea', '1', '1', '{\"fieldtype\":\"text\",\"width\":\"\",\"height\":\"\",\"default\":\"\"}', '8');
INSERT INTO `zz_config` VALUES ('is_contact', '是否显示底部联系商家', 'index', '0', '', 'radio', '1', '1', '{\"options\":\"\\u662f|1\\r\\n\\u5426|0\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"\"}', '30');
INSERT INTO `zz_config` VALUES ('goods_list', '商品列表显示', 'index', '1', '', 'radio', '1', '1', '{\"options\":\"\\u5355\\u5217|0\\r\\n\\u4e24\\u5217|1\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"0\"}', '31');
INSERT INTO `zz_config` VALUES ('commission_fee', '商家提现费率', 'goods', '0.6', '单位是%', 'text', '1', '1', '{\"size\":\"60\",\"default\":\"\",\"ispassword\":\"0\"}', '0');
INSERT INTO `zz_config` VALUES ('status_site', '站点状态', 'index', '0', '', 'select', '1', '1', '{\"options\":\"\\u5f00\\u542f|0\\r\\n\\u5173\\u95ed|1\",\"multiple\":\"0\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"size\":\"\",\"default\":\"0\"}', '50');
INSERT INTO `zz_config` VALUES ('status_tip', '站点关闭提示', 'index', '网站升级中，马上回来...', '', 'textarea', '1', '1', '{\"fieldtype\":\"text\",\"width\":\"\",\"height\":\"\",\"default\":\"\\u7f51\\u7ad9\\u5347\\u7ea7\\u4e2d\\uff0c\\u9a6c\\u4e0a\\u56de\\u6765...\"}', '51');
INSERT INTO `zz_config` VALUES ('wxpay_gz', '是否需要关注才能支付', 'index', '0', '', 'radio', '1', '1', '{\"options\":\"\\u662f|1\\r\\n\\u5426|0\",\"fieldtype\":\"varchar\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"1\"}', '52');
INSERT INTO `zz_config` VALUES ('amap_map_key', '高德地图key', 'system', '', '高德地图key, 调用地图必须. 客服聊天分享位置需要使用', 'text', '0', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '61');
INSERT INTO `zz_config` VALUES ('watermark_open', '水印功能', 'attach', '0', '', 'select', '1', '1', '{\"options\":\"\\u5f00\\u542f|1\\r\\n\\u7981\\u7528|0\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"0\"}', '1');
INSERT INTO `zz_config` VALUES ('watermark_x', '水平位置', 'attach', 'center', '', 'radio', '1', '1', '{\"options\":\"\\u5c45\\u5de6|left\\r\\n\\u5c45\\u4e2d|center\\r\\n\\u5c45\\u53f3|right\",\"fieldtype\":\"varchar\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"center\"}', '3');
INSERT INTO `zz_config` VALUES ('watermark_y', '垂直位置', 'attach', 'bottom', '', 'radio', '1', '1', '{\"options\":\"\\u5c45\\u4e0a|top\\r\\n\\u5c45\\u4e2d|center\\r\\n\\u5c45\\u4e0b|bottom\",\"fieldtype\":\"varchar\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"center\"}', '4');
INSERT INTO `zz_config` VALUES ('watermark_mod', '水印模块', 'attach', 'manage', '勾选的模块上传图片时会给原图添加水印', 'checkbox', '1', '1', '{\"options\":\"\\u540e\\u53f0\\u63a7\\u4ef6\\u56fe|manage\",\"fieldtype\":\"varchar\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"\"}', '5');
INSERT INTO `zz_config` VALUES ('hide_timeline', '隐藏分享朋友圈', 'wechat', '0', '', 'select', '1', '1', '{\"options\":\"\\u9690\\u85cf|1\\r\\n\\u663e\\u793a|0\",\"multiple\":\"0\",\"fieldtype\":\"varchar\",\"numbertype\":\"1\",\"size\":\"\",\"default\":\"0\"}', '18');
INSERT INTO `zz_config` VALUES ('watermark', '水印图', 'attach', '[]', '', 'images', '1', '1', '{\"upload_maxnum\":\"1\",\"upload_maxsize\":\"\",\"upload_allowext\":\"*.gif;*.jpg;*.jpeg;*.png\",\"watermark\":\"0\",\"more\":\"0\"}', '2');
INSERT INTO `zz_config` VALUES ('is_square', '拼团快报开关', 'index', '1', '', 'radio', '1', '1', '{\"options\":\"\\u5f00|1\\r\\n\\u5173|0\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"0\"}', '53');
INSERT INTO `zz_config` VALUES ('tel', '电话', 'index', '400-966-0901 0592-5552482', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '54');
INSERT INTO `zz_config` VALUES ('email', '邮箱', 'index', 'xm@test.com', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '55');
INSERT INTO `zz_config` VALUES ('address', '地址', 'index', '福建省厦门市软件园二期望海路', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '53');
INSERT INTO `zz_config` VALUES ('zs_url', '招商域名', 'system', '', '招商页面需要跟商城域名分开才可使用，招商域名和商城域名不能完全一样否则会出错', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '62');
INSERT INTO `zz_config` VALUES ('wx_url', '微信站地址', 'system', '', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '63');
INSERT INTO `zz_config` VALUES ('nopay_time', '付款时间(分钟)', 'system', '', '待付款订单多久未付款，自动取消', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '64');
INSERT INTO `zz_config` VALUES ('login_type', '会员登录方式', 'system', '0', '前台会员通过密码登录或手机验证码登录', 'radio', '1', '1', '{\"options\":\"\\u5bc6\\u7801|0\\r\\n\\u9a8c\\u8bc1\\u7801|1\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"0\"}', '65');
INSERT INTO `zz_config` VALUES ('refund_type', '微信退款方式', 'system', '0', '微信退款来源，1从未结算的资金退款，2从余额退款（这个方式会先结算到对公账号并产生费率，需要往商户平台里面充钱才能退）', 'radio', '1', '1', '{\"options\":\"\\u672a\\u7ed3\\u7b97\\u8d44\\u91d1|0\\r\\n\\u4f59\\u989d|1\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"0\"}', '66');
INSERT INTO `zz_config` VALUES ('sales_show', '团购销量显示', 'goods', '0', '发布商品是否显示团购销量字段', 'radio', '1', '1', '{\"options\":\"\\u662f|1\\r\\n\\u5426|0\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"0\"}', '0');
INSERT INTO `zz_config` VALUES ('pin_start', '拼团王开始时间', 'index', '', '', 'datetime', '1', '1', 'null', '56');
INSERT INTO `zz_config` VALUES ('pin_end', '拼团王结束时间', 'index', '', '', 'datetime', '1', '1', 'null', '57');
INSERT INTO `zz_config` VALUES ('web_socket_host', '客服地址', 'system', '', '域名有做加速时使用,例ws://ip:7272', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '61');
INSERT INTO `zz_config` VALUES ('kf_online', '第三方客服', 'index', '', '使用url地址接入', 'textarea', '1', '1', '{\"fieldtype\":\"text\",\"width\":\"\",\"height\":\"\",\"default\":\"\"}', '59');
INSERT INTO `zz_config` VALUES ('index_goods', '首页商品', 'index', '0', '首页下拉商品来源，1:热销商品，2:活动报名', 'radio', '1', '1', '{\"options\":\"\\u70ed\\u9500\\u5546\\u54c1|0\\r\\n\\u6d3b\\u52a8\\u62a5\\u540d|1\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"0\"}', '58');
INSERT INTO `zz_config` VALUES ('index_spread', '首页推广开关', 'index', '1', '首页导航菜单下的5个推广显示与隐藏', 'radio', '1', '1', '{\"options\":\"\\u663e\\u793a|1\\r\\n\\u9690\\u85cf|0\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"1\"}', '58');
INSERT INTO `zz_config` VALUES ('nation_realname', '全球购实名认证', 'goods', '0', '全球购商品是否需要实名认证才能购买', 'radio', '1', '1', '{\"options\":\"\\u5426|0\\r\\n\\u662f|1\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"0\"}', '3');
INSERT INTO `zz_config` VALUES ('icp_beian', '新公网安备', 'index', '', '', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '29');
INSERT INTO `zz_config` VALUES ('auto_join', '自动成团时间（小时）', 'index', '', '0表示关闭自动成团功能，如果要开启请填写正整数，并在会员管理=》快速开团按钮添加会员，该功能仅限普通拼团', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '60');

-- ----------------------------
-- Table structure for `zz_country`
-- ----------------------------
DROP TABLE IF EXISTS `zz_country`;
CREATE TABLE `zz_country` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `catname` varchar(255) NOT NULL COMMENT '分类名称',
  `parentid` int(10) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `arrparentid` text NOT NULL COMMENT '父类ID组',
  `arrchildid` text NOT NULL COMMENT '子类ID组',
  `child` tinyint(1) NOT NULL COMMENT '是否有子级',
  `keywords` varchar(120) NOT NULL COMMENT '页面关键字',
  `description` int(255) NOT NULL COMMENT '页面描述',
  `listorder` smallint(5) NOT NULL DEFAULT '0' COMMENT '排序',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `url` varchar(255) NOT NULL COMMENT 'URL',
  `ismenu` smallint(3) NOT NULL DEFAULT '0' COMMENT '是否导航',
  `isrec` smallint(3) NOT NULL,
  `desc` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COMMENT='国际馆';

-- ----------------------------
-- Records of zz_country
-- ----------------------------
INSERT INTO `zz_country` VALUES ('13', '美国馆', '0', '0', '13', '0', '', '0', '0', '[{\"path\":\"\\/upload\\/60\\/images\\/gallery\\/r\\/4\\/11345_src.png\",\"title\":\"\\u7f8e\\u56fd\\u56fd\\u65d7\"}]', '/cat/index/13', '1', '1', '低到美国人都羡慕');
INSERT INTO `zz_country` VALUES ('14', '德国馆', '0', '0', '14', '0', '', '0', '0', '[{\"path\":\"\\/upload\\/60\\/images\\/gallery\\/r\\/7\\/11348_src.png\",\"title\":\"\\u5fb7\\u56fd\"}]', '/cat/index/14', '1', '1', '把优雅和奢品买回家');
INSERT INTO `zz_country` VALUES ('15', '港台馆', '0', '0', '15', '0', '', '0', '0', '[{\"path\":\"\\/upload\\/60\\/images\\/gallery\\/q\\/a\\/11315_src.png\",\"title\":\"\\u6e2f\\u6fb3\\u53f0\"}]', '/cat/index/15', '1', '1', '香港的尖货台湾的风味小吃');
INSERT INTO `zz_country` VALUES ('20', '法国', '0', '0', '20', '0', '', '0', '0', '[{\"path\":\"\\/upload\\/60\\/images\\/gallery\\/r\\/p\\/998_src.jpg\",\"title\":\"TB2FUsohYJkpuFjy1zcXXa5FFXa_!!2096981539\"}]', '/cat/index/20', '0', '0', '');
INSERT INTO `zz_country` VALUES ('21', '澳州', '0', '0', '21', '0', '', '0', '0', '[{\"path\":\"\\/upload\\/60\\/images\\/gallery\\/r\\/9\\/11350_src.png\",\"title\":\"\\u6fb3\\u5927\\u5229\\u4e9a\"}]', '/cat/index/21', '1', '0', '');
INSERT INTO `zz_country` VALUES ('22', '新西兰', '0', '0', '22', '0', '', '0', '0', '[{\"path\":\"\\/upload\\/60\\/images\\/gallery\\/r\\/a\\/11351_src.png\",\"title\":\"\\u65b0\\u897f\\u5170\"}]', '/cat/index/22', '1', '0', '');
INSERT INTO `zz_country` VALUES ('23', '英国', '0', '0', '23', '0', '', '0', '0', '[{\"path\":\"\\/upload\\/60\\/images\\/gallery\\/r\\/c\\/11353_src.png\",\"title\":\"\\u82f1\\u56fd\\u56fd\\u65d7\"}]', '/cat/index/23', '1', '0', '');
INSERT INTO `zz_country` VALUES ('24', '日本', '0', '0', '24', '0', '', '0', '0', '[{\"path\":\"\\/upload\\/60\\/images\\/gallery\\/r\\/d\\/11354_src.png\",\"title\":\"\\u65e5\\u672c\\u56fd\\u65d7\"}]', '/cat/index/24', '1', '0', '');
INSERT INTO `zz_country` VALUES ('25', '韩国', '0', '0', '25', '0', '', '0', '0', '[{\"path\":\"\\/upload\\/60\\/images\\/gallery\\/r\\/p\\/998_src.jpg\",\"title\":\"TB2FUsohYJkpuFjy1zcXXa5FFXa_!!2096981539\"}]', '/cat/index/25', '0', '0', '');
INSERT INTO `zz_country` VALUES ('26', '新加坡', '0', '0', '26', '0', '', '0', '0', '[{\"path\":\"\\/upload\\/60\\/images\\/gallery\\/r\\/p\\/998_src.jpg\",\"title\":\"TB2FUsohYJkpuFjy1zcXXa5FFXa_!!2096981539\"}]', '/cat/index/26', '0', '0', '');
INSERT INTO `zz_country` VALUES ('27', '荷兰', '0', '0', '27', '0', '', '0', '0', '[{\"path\":\"\\/upload\\/60\\/images\\/gallery\\/r\\/p\\/998_src.jpg\",\"title\":\"TB2FUsohYJkpuFjy1zcXXa5FFXa_!!2096981539\"}]', '/cat/index/27', '1', '0', '');
INSERT INTO `zz_country` VALUES ('28', '其他', '0', '0', '28', '0', '', '0', '0', '[{\"path\":\"\\/upload\\/60\\/images\\/gallery\\/r\\/p\\/998_src.jpg\",\"title\":\"TB2FUsohYJkpuFjy1zcXXa5FFXa_!!2096981539\"}]', '/cat/index/28', '0', '0', '');

-- ----------------------------
-- Table structure for `zz_coupon`
-- ----------------------------
DROP TABLE IF EXISTS `zz_coupon`;
CREATE TABLE `zz_coupon` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '0普通1分享A券2分享B券',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '优惠券标题',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '商户内部备注',
  `amount` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '优惠券金额',
  `need_amount` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单超过此金额才可以使用本优惠券',
  `target` text NOT NULL COMMENT '只能抵扣本类指定的商品',
  `stock` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '剩余数量',
  `sended` int(11) NOT NULL DEFAULT '0' COMMENT '已发放数量',
  `used` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '已使用数量',
  `start_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '开始领取时间',
  `end_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '截止领取时间',
  `uniqued` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '每人是否只能领取一张',
  `exchange` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否可用积分兑换',
  `score` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '兑换所需积分值',
  `paid` tinyint(4) NOT NULL DEFAULT '0' COMMENT '订单完成送积分',
  `paid_goodscat` text NOT NULL COMMENT '交易完成时商品包含本字段指定的品类才送优惠券',
  `paid_amount` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '完成订单送优惠券需要满足的金额',
  `share` tinyint(4) NOT NULL DEFAULT '0' COMMENT '分享型优惠券标记',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  `sid` int(11) NOT NULL COMMENT ' 商家ID',
  `is_view` tinyint(1) NOT NULL DEFAULT '0' COMMENT '默认显示，0为不显示，此为平台抵用券',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='优惠券管理表';

-- ----------------------------
-- Records of zz_coupon
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_coupon_log`
-- ----------------------------
DROP TABLE IF EXISTS `zz_coupon_log`;
CREATE TABLE `zz_coupon_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `send_type` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '获取途径',
  `coupon_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '优惠券id',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '优惠券父id 本优惠券使用后pid对应优惠券share设置为0',
  `order_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '使用时抵扣的对应订单号',
  `share` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '是否可分享',
  `share_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分享时间 用来识别本优惠券是否已经分享过',
  `use_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '使用时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '领取时间',
  `expire_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '过期时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `sid` int(11) NOT NULL COMMENT '商家ID',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='优惠券领取日志表';

-- ----------------------------
-- Records of zz_coupon_log
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_express`
-- ----------------------------
DROP TABLE IF EXISTS `zz_express`;
CREATE TABLE `zz_express` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `pinyin` varchar(100) NOT NULL,
  `listorder` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `free` tinyint(1) NOT NULL DEFAULT '0' COMMENT '免运费',
  `sid` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='运输方式';

-- ----------------------------
-- Records of zz_express
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_express_print_field`
-- ----------------------------
DROP TABLE IF EXISTS `zz_express_print_field`;
CREATE TABLE `zz_express_print_field` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tpl_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '模版id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '字段名',
  `left` varchar(50) NOT NULL DEFAULT '' COMMENT '上边距',
  `top` varchar(50) NOT NULL DEFAULT '' COMMENT '左边距',
  `value` varchar(255) NOT NULL DEFAULT '' COMMENT '字段值 可以用引用或者公司 或者自定义模版',
  `ref` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否为引用字段',
  `default` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否为默认字段',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态:0禁用1启用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_express_print_field
-- ----------------------------
INSERT INTO `zz_express_print_field` VALUES ('10', '1', '收件人', '308px', '198px', 'goods_order.name', '1', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('11', '1', '收件电话', '108px', '276px', 'goods_order.mobile', '1', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('12', '1', '收件地区', '93px', '222px', 'goods_order.area', '1', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('13', '1', '收件地址', '176px', '249px', 'goods_order.address', '1', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('14', '1', '寄件人', '310px', '117px', '', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('15', '1', '寄件单位', '125px', '117px', '', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('16', '1', '寄件地区', '106px', '143px', '', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('17', '1', '寄件地址', '210px', '143px', '', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('18', '1', '寄件电话', '112px', '169px', '', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('19', '1', '收方付', '478px', '370px', '√', '0', '0', '0');
INSERT INTO `zz_express_print_field` VALUES ('20', '2', '收件人', '477px', '95px', 'goods_order.name', '1', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('21', '2', '收件电话', '522px', '232px', 'goods_order.mobile', '1', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('22', '2', '收件地区', '472px', '163px', 'goods_order.area', '1', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('23', '2', '收件地址', '423px', '201px', 'goods_order.address', '1', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('24', '2', '寄件人', '126px', '96px', '', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('25', '2', '寄件电话', '169px', '232px', '', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('26', '2', '寄件地区', '116px', '165px', '', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('27', '2', '寄件地址', '68px', '200px', '', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('28', '2', '寄件单位', '133px', '134px', '', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('29', '2', '收方付', '629px', '284px', '√', '0', '0', '0');
INSERT INTO `zz_express_print_field` VALUES ('30', '3', '收件人', '470px', '99px', 'goods_order.name', '1', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('31', '3', '收件电话', '466px', '205px', 'goods_order.mobile', '1', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('32', '3', '收件地区', '467px', '155px', 'goods_order.area', '1', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('33', '3', '收件地址', '578px', '156px', 'goods_order.address', '1', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('34', '3', '寄件人', '162px', '100px', '刘凯', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('35', '3', '寄件单位', '140px', '140px', '云南普鑫阁茶业', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('36', '3', '寄件地区', '0', '0', '', '0', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('37', '3', '寄件地址', '0', '0', '', '0', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('38', '3', '寄件电话', '143px', '186px', '150966658022', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('39', '4', '收件人', '548px', '48px', 'goods_order.name', '1', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('40', '4', '收件电话', '780px', '49px', 'goods_order.mobile', '1', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('41', '4', '收件地区', '805px', '264px', 'goods_order.area', '1', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('42', '4', '收件地址', '545px', '159px', 'goods_order.address', '1', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('43', '4', '寄件人', '70px', '53px', '聂素霞', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('44', '4', '寄件单位', '115px', '166px', '云南普洱茶批发', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('45', '4', '寄件地区', '0', '0', '', '0', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('46', '4', '寄件地址', '0', '0', '', '0', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('47', '4', '寄件电话', '204px', '61px', '18206706282', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('48', '5', '收件人', '0', '0', 'goods_order.name', '1', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('49', '5', '收件电话', '0', '0', 'goods_order.mobile', '1', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('50', '5', '收件地区', '0', '0', 'goods_order.area', '1', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('51', '5', '收件地址', '0', '0', 'goods_order.address', '1', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('52', '5', '寄件人', '0', '0', '陈小鸭', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('53', '5', '寄件单位', '0', '0', '', '0', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('54', '5', '寄件地区', '0', '0', '', '0', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('55', '5', '寄件地址', '57px', '1px', '河南省焦作市温县南张羌镇', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('56', '5', '寄件电话', '255px', '3px', '15539119446', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('57', '6', '收件人', '0', '0', 'goods_order.name', '1', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('58', '6', '收件电话', '0', '0', 'goods_order.mobile', '1', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('59', '6', '收件地区', '0', '0', 'goods_order.area', '1', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('60', '6', '收件地址', '0', '0', 'goods_order.address', '1', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('61', '6', '寄件人', '0', '0', '卢洛食客', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('62', '6', '寄件单位', '0', '0', '', '0', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('63', '6', '寄件地区', '0', '0', '', '0', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('64', '6', '寄件地址', '0', '0', '河南省三门峡市卢氏县横涧乡电商中心', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('65', '6', '寄件电话', '0', '0', '0398-2158766', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('66', '7', '收件人', '0', '0', 'goods_order.name', '1', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('67', '7', '收件电话', '0', '0', 'goods_order.mobile', '1', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('68', '7', '收件地区', '0', '0', 'goods_order.area', '1', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('69', '7', '收件地址', '0', '0', 'goods_order.address', '1', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('70', '7', '寄件人', '0', '0', '卢洛食客', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('71', '7', '寄件单位', '0', '0', '', '0', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('72', '7', '寄件地区', '0', '0', '', '0', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('73', '7', '寄件地址', '4px', '30px', '河南省三门峡市卢氏县横涧乡电商中心', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('74', '7', '寄件电话', '0px', '58px', '0398-2158766', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('75', '8', '收件人', '2px', '146px', 'goods_order.name', '1', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('76', '8', '收件电话', '81px', '143px', 'goods_order.mobile', '1', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('77', '8', '收件地区', '0', '0', 'goods_order.area', '1', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('78', '8', '收件地址', '0px', '181px', 'goods_order.address', '1', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('79', '8', '寄件人', '0', '0', '梁文君', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('80', '8', '寄件单位', '0', '0', '', '0', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('81', '8', '寄件地区', '0', '0', '', '0', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('82', '8', '寄件地址', '0px', '52px', '后宅街道城北路J133号', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('83', '8', '寄件电话', '87px', '3px', '18658901638', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('84', '9', '收件人', '0', '0', 'goods_order.name', '1', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('85', '9', '收件电话', '0', '0', 'goods_order.mobile', '1', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('86', '9', '收件地区', '0', '0', 'goods_order.area', '1', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('87', '9', '收件地址', '0', '0', 'goods_order.address', '1', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('88', '9', '寄件人', '0', '0', '', '0', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('89', '9', '寄件单位', '62px', '0px', '中山市希欧电器有限公司', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('90', '9', '寄件地区', '0', '0', '中山市', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('91', '9', '寄件地址', '356px', '4px', '黄圃镇启业南路2号', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('92', '9', '寄件电话', '242px', '0px', '18682591928', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('93', '10', '收件人', '0', '0', 'goods_order.name', '1', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('94', '10', '收件电话', '0', '0', 'goods_order.mobile', '1', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('95', '10', '收件地区', '0', '0', 'goods_order.area', '1', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('96', '10', '收件地址', '0', '0', 'goods_order.address', '1', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('97', '10', '寄件人', '0', '0', '', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('98', '10', '寄件单位', '0', '0', '', '0', '1', '1');
INSERT INTO `zz_express_print_field` VALUES ('99', '10', '寄件地区', '0', '0', '', '0', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('100', '10', '寄件地址', '0', '0', '', '0', '1', '0');
INSERT INTO `zz_express_print_field` VALUES ('101', '10', '寄件电话', '0', '0', '', '0', '1', '0');

-- ----------------------------
-- Table structure for `zz_express_print_public`
-- ----------------------------
DROP TABLE IF EXISTS `zz_express_print_public`;
CREATE TABLE `zz_express_print_public` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '模版名',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '模版描述',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '背景图片地址',
  `left` smallint(11) NOT NULL DEFAULT '0' COMMENT '左偏移',
  `top` smallint(11) NOT NULL DEFAULT '0' COMMENT '上偏移',
  `width` smallint(11) NOT NULL DEFAULT '0' COMMENT '背景宽度',
  `height` smallint(11) NOT NULL DEFAULT '0' COMMENT '背景高度',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态:0关闭1开启',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='无sid 存放被引用的公用模版 不能直接私用必须做导入\r\n\r\n';

-- ----------------------------
-- Records of zz_express_print_public
-- ----------------------------
INSERT INTO `zz_express_print_public` VALUES ('1', '顺丰速运2016', '2016年版. 顺丰速运公用模版', '/admin/images/express/1.jpg', '0', '0', '225', '125', '1');
INSERT INTO `zz_express_print_public` VALUES ('2', '申通快递2016', '2016年版. 申通快递公用模版', '/admin/images/express/2.jpg', '0', '0', '225', '125', '1');

-- ----------------------------
-- Table structure for `zz_express_print_public_field`
-- ----------------------------
DROP TABLE IF EXISTS `zz_express_print_public_field`;
CREATE TABLE `zz_express_print_public_field` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tpl_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '模版id 为0表示无条件默认字段 数字表示对应公用模版',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '字段名',
  `left` varchar(50) NOT NULL DEFAULT '' COMMENT '上边距',
  `top` varchar(50) NOT NULL DEFAULT '' COMMENT '左边距',
  `value` varchar(255) NOT NULL DEFAULT '' COMMENT '字段值 可以用引用或者公司 或者自定义模版',
  `ref` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1为引用字段0为固定内容字段.固定内容字段需要在模版定义内容',
  `default` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0非默认字段可以删除 1为默认字段无法删除',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态:0禁用1启用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COMMENT='无sid 打印模版字段表 \r\n存放公用模版字段及默认字段 注意tpl_id 为0的表示无模版默认字段\r\n';

-- ----------------------------
-- Records of zz_express_print_public_field
-- ----------------------------
INSERT INTO `zz_express_print_public_field` VALUES ('1', '0', '收件人', '0', '0', 'goods_order.name', '1', '1', '0');
INSERT INTO `zz_express_print_public_field` VALUES ('2', '0', '收件电话', '0', '0', 'goods_order.mobile', '1', '1', '0');
INSERT INTO `zz_express_print_public_field` VALUES ('3', '0', '收件地区', '0', '0', 'goods_order.area', '1', '1', '0');
INSERT INTO `zz_express_print_public_field` VALUES ('4', '0', '收件地址', '0', '0', 'goods_order.address', '1', '1', '0');
INSERT INTO `zz_express_print_public_field` VALUES ('5', '0', '寄件人', '0', '0', '', '0', '1', '0');
INSERT INTO `zz_express_print_public_field` VALUES ('6', '0', '寄件单位', '0', '0', '', '0', '1', '0');
INSERT INTO `zz_express_print_public_field` VALUES ('7', '0', '寄件地区', '0', '0', '', '0', '1', '0');
INSERT INTO `zz_express_print_public_field` VALUES ('8', '0', '寄件地址', '0', '0', '', '0', '1', '0');
INSERT INTO `zz_express_print_public_field` VALUES ('9', '0', '寄件电话', '0', '0', '', '0', '1', '0');
INSERT INTO `zz_express_print_public_field` VALUES ('10', '1', '收件人', '308px', '198px', 'goods_order.name', '1', '1', '1');
INSERT INTO `zz_express_print_public_field` VALUES ('11', '1', '收件电话', '108px', '276px', 'goods_order.mobile', '1', '1', '1');
INSERT INTO `zz_express_print_public_field` VALUES ('12', '1', '收件地区', '93px', '222px', 'goods_order.area', '1', '1', '1');
INSERT INTO `zz_express_print_public_field` VALUES ('13', '1', '收件地址', '176px', '249px', 'goods_order.address', '1', '1', '1');
INSERT INTO `zz_express_print_public_field` VALUES ('14', '1', '寄件人', '310px', '117px', '', '0', '1', '1');
INSERT INTO `zz_express_print_public_field` VALUES ('15', '1', '寄件单位', '125px', '117px', '', '0', '1', '1');
INSERT INTO `zz_express_print_public_field` VALUES ('16', '1', '寄件地区', '106px', '143px', '', '0', '1', '1');
INSERT INTO `zz_express_print_public_field` VALUES ('17', '1', '寄件地址', '210px', '143px', '', '0', '1', '1');
INSERT INTO `zz_express_print_public_field` VALUES ('18', '1', '寄件电话', '112px', '169px', '', '0', '1', '1');
INSERT INTO `zz_express_print_public_field` VALUES ('19', '1', '收方付', '478px', '370px', '√', '0', '0', '0');
INSERT INTO `zz_express_print_public_field` VALUES ('20', '2', '收件人', '477px', '95px', 'goods_order.name', '1', '1', '1');
INSERT INTO `zz_express_print_public_field` VALUES ('21', '2', '收件电话', '522px', '232px', 'goods_order.mobile', '1', '1', '1');
INSERT INTO `zz_express_print_public_field` VALUES ('22', '2', '收件地区', '472px', '163px', 'goods_order.area', '1', '1', '1');
INSERT INTO `zz_express_print_public_field` VALUES ('23', '2', '收件地址', '423px', '201px', 'goods_order.address', '1', '1', '1');
INSERT INTO `zz_express_print_public_field` VALUES ('24', '2', '寄件人', '126px', '96px', '', '0', '1', '1');
INSERT INTO `zz_express_print_public_field` VALUES ('25', '2', '寄件电话', '169px', '232px', '', '0', '1', '1');
INSERT INTO `zz_express_print_public_field` VALUES ('26', '2', '寄件地区', '116px', '165px', '', '0', '1', '1');
INSERT INTO `zz_express_print_public_field` VALUES ('27', '2', '寄件地址', '68px', '200px', '', '0', '1', '1');
INSERT INTO `zz_express_print_public_field` VALUES ('28', '2', '寄件单位', '133px', '134px', '', '0', '1', '1');
INSERT INTO `zz_express_print_public_field` VALUES ('29', '2', '收方付', '629px', '284px', '√', '0', '0', '0');

-- ----------------------------
-- Table structure for `zz_express_print_tpl`
-- ----------------------------
DROP TABLE IF EXISTS `zz_express_print_tpl`;
CREATE TABLE `zz_express_print_tpl` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商户id',
  `express_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '快递类型id',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '模版描述',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '背景图片地址',
  `left` smallint(11) NOT NULL DEFAULT '0' COMMENT '左偏移',
  `top` smallint(11) NOT NULL DEFAULT '0' COMMENT '上偏移',
  `width` smallint(11) NOT NULL,
  `height` smallint(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态:0关闭1开启',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_express_print_tpl
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_express_shipping`
-- ----------------------------
DROP TABLE IF EXISTS `zz_express_shipping`;
CREATE TABLE `zz_express_shipping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '配送区域名称',
  `express_id` int(11) NOT NULL COMMENT '快递公司id',
  `linkage_id` text NOT NULL COMMENT '地区id',
  `config` text NOT NULL COMMENT '初始重量：cs_weight、初始费用：cs_price、每KG续费：xf_weight',
  `sid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_express_shipping
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_fav`
-- ----------------------------
DROP TABLE IF EXISTS `zz_fav`;
CREATE TABLE `zz_fav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '商品ID',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品收藏';

-- ----------------------------
-- Records of zz_fav
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_files`
-- ----------------------------
DROP TABLE IF EXISTS `zz_files`;
CREATE TABLE `zz_files` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `data_id` int(11) DEFAULT NULL,
  `name` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '文件名称',
  `fileurl` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '文件地址',
  `ext` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '扩展名',
  `cate` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '文件类型',
  `size` int(11) DEFAULT NULL COMMENT '文件大小',
  `c_time` int(11) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文件上传记录';

-- ----------------------------
-- Records of zz_files
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_gallery_images`
-- ----------------------------
DROP TABLE IF EXISTS `zz_gallery_images`;
CREATE TABLE `zz_gallery_images` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) NOT NULL DEFAULT '1' COMMENT '文件分类',
  `sid` int(11) NOT NULL COMMENT '商家id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='图片控件图片关联表';

-- ----------------------------
-- Records of zz_gallery_images
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_gallery_tag`
-- ----------------------------
DROP TABLE IF EXISTS `zz_gallery_tag`;
CREATE TABLE `zz_gallery_tag` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '分类名称',
  `type` enum('image','file') DEFAULT 'image' COMMENT '文件类型',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COMMENT='文件、图片分类表';

-- ----------------------------
-- Records of zz_gallery_tag
-- ----------------------------
INSERT INTO `zz_gallery_tag` VALUES ('1', '默认分类', 'image');
INSERT INTO `zz_gallery_tag` VALUES ('3', '默认分类', 'file');

-- ----------------------------
-- Table structure for `zz_goods`
-- ----------------------------
DROP TABLE IF EXISTS `zz_goods`;
CREATE TABLE `zz_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `typeid` tinyint(2) NOT NULL DEFAULT '1' COMMENT '类型  1:普通拼团，2:1元购，3:秒杀，4:限时特卖，5:免费试用，6:抽奖,7:AA团',
  `name` varchar(90) NOT NULL COMMENT '商品名称',
  `desc` varchar(500) NOT NULL COMMENT '商品简介',
  `content` text NOT NULL COMMENT '商品描述',
  `params` text NOT NULL COMMENT '商品参数',
  `cost_price` decimal(10,2) NOT NULL COMMENT '成本价',
  `market_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '市场价',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '本站价格',
  `cid` int(10) NOT NULL COMMENT '分类ID',
  `bid` int(10) NOT NULL DEFAULT '0' COMMENT '品牌ID',
  `nation_id` int(10) NOT NULL COMMENT '海淘分类',
  `u_time` int(11) NOT NULL COMMENT '更新时间 ',
  `c_time` int(11) NOT NULL COMMENT '创建时间 ',
  `listorder` int(6) NOT NULL DEFAULT '99999' COMMENT '排序',
  `thumb` text NOT NULL COMMENT '缩略图',
  `thumbs` text NOT NULL COMMENT '图集',
  `share_img` text NOT NULL COMMENT '分享图片',
  `stock` int(11) NOT NULL DEFAULT '0' COMMENT '库存',
  `sell` int(11) NOT NULL DEFAULT '0' COMMENT '已售数量',
  `is_sale` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1上架,0下架',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '-1删除,0正常',
  `unit` varchar(20) NOT NULL COMMENT '商品单位',
  `weight` varchar(10) NOT NULL DEFAULT '0' COMMENT '重量',
  `weight_unit` varchar(20) NOT NULL COMMENT '重量单位',
  `goods_type` tinyint(1) NOT NULL COMMENT '商品类型  ',
  `is_best` tinyint(1) NOT NULL COMMENT '精品',
  `is_new` tinyint(1) NOT NULL COMMENT '新品',
  `is_hot` tinyint(1) NOT NULL COMMENT '热销',
  `sp_val` varchar(1000) NOT NULL COMMENT '商品规格',
  `team_price` decimal(10,2) NOT NULL COMMENT '团购价',
  `team_num` int(11) NOT NULL COMMENT '参团人数',
  `sales_num` int(11) NOT NULL COMMENT '团购销量',
  `limit_buy_bumber` int(4) NOT NULL COMMENT '团购限购数量',
  `limit_buy_one` tinyint(2) NOT NULL COMMENT '团购限制次数',
  `discount_type` tinyint(2) NOT NULL COMMENT '团长优惠',
  `discount_amount` decimal(5,2) NOT NULL COMMENT '优惠金额',
  `start_time` int(10) NOT NULL COMMENT '开始时间',
  `end_time` int(10) NOT NULL COMMENT '结束时间',
  `luck_num` int(10) NOT NULL COMMENT '中奖人数',
  `coupon_id` int(4) NOT NULL,
  `sid` int(11) NOT NULL COMMENT '商家id',
  `is_check` tinyint(2) NOT NULL DEFAULT '1' COMMENT '审核：0待审核，1通过,2不通过',
  `admin_note` varchar(255) NOT NULL COMMENT '审核备注',
  `deposit` decimal(6,2) NOT NULL COMMENT '阶梯团定金',
  `step` varchar(200) NOT NULL COMMENT '阶梯 人数和价格',
  `team_day` int(2) NOT NULL COMMENT '成团时间 天',
  `team_hour` int(2) NOT NULL COMMENT '成团时间 小时',
  `team_limit` tinyint(2) NOT NULL COMMENT '团员限制 0不限，1限制',
  `is_free_shipping` tinyint(1) NOT NULL DEFAULT '0',
  `is_virtual` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否充值卡，1是，0否',
  `express` varchar(100) NOT NULL COMMENT '快递方式',
  `take_address` varchar(100) NOT NULL COMMENT '自提点',
  `comm_scale` int(4) NOT NULL COMMENT '佣金比例',
  `aid` int(11) NOT NULL COMMENT '同城分类',
  `areaid` int(11) NOT NULL COMMENT '所在地',
  `country_id` int(11) NOT NULL COMMENT '国际馆',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品数据主表';

-- ----------------------------
-- Records of zz_goods
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_goods_activity`
-- ----------------------------
DROP TABLE IF EXISTS `zz_goods_activity`;
CREATE TABLE `zz_goods_activity` (
  `act_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `act_name` varchar(255) NOT NULL COMMENT '促销名称',
  `act_thumb` varchar(255) NOT NULL COMMENT '促销图片',
  `act_desc` text NOT NULL COMMENT '促销描述',
  `act_type` tinyint(3) unsigned NOT NULL DEFAULT '2' COMMENT '2秒杀 3团购 4套餐 5积分商品',
  `goods_id` int(11) unsigned NOT NULL COMMENT '商品id',
  `start_time` int(10) unsigned NOT NULL COMMENT '开始时间',
  `end_time` int(10) unsigned NOT NULL COMMENT '结束时间',
  `is_finished` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '1已结束',
  `act_points` int(11) NOT NULL DEFAULT '0' COMMENT '所需积分',
  `act_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '促销价',
  `act_sell` int(10) NOT NULL DEFAULT '0' COMMENT '销量',
  `act_stock` int(10) NOT NULL DEFAULT '0' COMMENT '库存',
  `ext_info` text NOT NULL COMMENT '其它参数',
  `qishu` int(10) NOT NULL DEFAULT '1' COMMENT '期数',
  `team_num` int(10) NOT NULL COMMENT '成团人数',
  `limit_buy_bumber` int(10) NOT NULL COMMENT '团购限购数量',
  `limit_buy_one` tinyint(2) NOT NULL COMMENT '团购限制次数',
  PRIMARY KEY (`act_id`),
  KEY `act_name` (`act_name`,`act_type`,`goods_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='促销商品数据表';

-- ----------------------------
-- Records of zz_goods_activity
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_goods_additional`
-- ----------------------------
DROP TABLE IF EXISTS `zz_goods_additional`;
CREATE TABLE `zz_goods_additional` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) unsigned NOT NULL COMMENT '商品关联ID',
  `goods_no` varchar(20) DEFAULT NULL COMMENT '商家编码',
  `basic_sales` mediumint(8) unsigned DEFAULT '0' COMMENT '基础销量',
  `basic_praise` mediumint(8) unsigned DEFAULT '0' COMMENT '基础点赞数',
  `is_stock` set('1','0') DEFAULT '1' COMMENT '库存显示(0隐藏、1显示)',
  `limit_num` tinyint(3) unsigned DEFAULT '0' COMMENT '每人限购',
  `keywords` varchar(120) DEFAULT NULL,
  `description` mediumtext,
  `superior_brokerage` varchar(255) NOT NULL COMMENT '商品佣金设置',
  `goods_tip` varchar(255) DEFAULT NULL COMMENT '商品标签（7天退换，48小时发货等）',
  `share_comss` varchar(255) NOT NULL COMMENT '推广佣金',
  `linkage_id` text NOT NULL COMMENT '配送区域',
  `spec1` int(11) NOT NULL COMMENT '属性1',
  `spec2` int(11) NOT NULL COMMENT '属性2',
  PRIMARY KEY (`id`),
  UNIQUE KEY `goods_id` (`goods_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品数据附表';

-- ----------------------------
-- Records of zz_goods_additional
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_goods_attr`
-- ----------------------------
DROP TABLE IF EXISTS `zz_goods_attr`;
CREATE TABLE `zz_goods_attr` (
  `goods_attr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `attr_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '属性id',
  `attr_value` text NOT NULL COMMENT '属性值',
  `attr_price` varchar(255) NOT NULL DEFAULT '' COMMENT '属性价格',
  PRIMARY KEY (`goods_attr_id`),
  KEY `goods_id` (`goods_id`) USING BTREE,
  KEY `attr_id` (`attr_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品属性表';

-- ----------------------------
-- Records of zz_goods_attr
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_goods_cat`
-- ----------------------------
DROP TABLE IF EXISTS `zz_goods_cat`;
CREATE TABLE `zz_goods_cat` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `catname` varchar(255) NOT NULL COMMENT '分类名称',
  `parentid` int(10) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `arrparentid` text NOT NULL COMMENT '父类ID组',
  `arrchildid` text NOT NULL COMMENT '子类ID组',
  `child` tinyint(1) NOT NULL COMMENT '是否有子级',
  `title` varchar(120) NOT NULL COMMENT '页面标题',
  `keywords` varchar(120) NOT NULL COMMENT '页面关键字',
  `description` varchar(255) NOT NULL COMMENT '页面描述',
  `listorder` smallint(5) NOT NULL DEFAULT '0' COMMENT '排序',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `url` varchar(255) NOT NULL COMMENT 'URL',
  `ismenu` smallint(3) NOT NULL DEFAULT '0' COMMENT '是否导航',
  `isrec` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1推荐',
  `thumb_rec` varchar(255) DEFAULT NULL COMMENT '推荐图',
  PRIMARY KEY (`id`),
  KEY `gcat_parentid` (`parentid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品分类数据表';

-- ----------------------------
-- Records of zz_goods_cat
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_goods_item`
-- ----------------------------
DROP TABLE IF EXISTS `zz_goods_item`;
CREATE TABLE `zz_goods_item` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) DEFAULT NULL COMMENT '商品id',
  `goods_name` varchar(200) DEFAULT NULL COMMENT '商品名称',
  `spec` varchar(100) DEFAULT NULL COMMENT '商品规格id',
  `price` decimal(10,2) DEFAULT NULL COMMENT '商品价格',
  `team_price` decimal(10,2) DEFAULT NULL COMMENT '拼团价',
  `cost` decimal(10,2) DEFAULT NULL COMMENT '成本价',
  `stock` int(11) DEFAULT '0' COMMENT '库存',
  `serial` varchar(20) DEFAULT NULL,
  `thumb` varchar(255) DEFAULT NULL COMMENT '封面图',
  PRIMARY KEY (`id`),
  KEY `item_goodsid` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品子列表（规格）';

-- ----------------------------
-- Records of zz_goods_item
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_goods_order`
-- ----------------------------
DROP TABLE IF EXISTS `zz_goods_order`;
CREATE TABLE `zz_goods_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL COMMENT '会员ID',
  `order_sn` varchar(30) NOT NULL,
  `common_id` int(11) NOT NULL COMMENT '组团id',
  `status_common` tinyint(3) NOT NULL COMMENT '组团状态 1组团中,2组团失败，10：组团成功',
  `status_pay` tinyint(3) NOT NULL DEFAULT '0' COMMENT '付款状态,0未付款 1部分付款 10付款完成 20不需要支付',
  `status_shipping` tinyint(3) NOT NULL DEFAULT '0' COMMENT '发货状态：0未发货 1备货中 2已发货 10已收货',
  `status_order` tinyint(3) NOT NULL DEFAULT '0' COMMENT '订单状态 0默认状态 1已取消 2退款中 3退款成功 4退款失败 5交易关闭  10交易完成',
  `status_lottery` tinyint(3) NOT NULL COMMENT '抽奖状态 0开奖中，2未中奖，10中奖',
  `c_time` int(11) NOT NULL COMMENT '创建订单时间',
  `pay_time` int(10) NOT NULL DEFAULT '0' COMMENT '付款时间',
  `shipping_time` int(10) NOT NULL COMMENT '发货时间',
  `confirm_time` int(10) NOT NULL COMMENT '确认收货时间',
  `fright_id` tinyint(2) NOT NULL DEFAULT '0' COMMENT '配送方式ID',
  `fright_name` varchar(100) NOT NULL COMMENT '配送方式名称',
  `express` varchar(40) NOT NULL DEFAULT '0' COMMENT '快递ID',
  `express_num` varchar(64) NOT NULL DEFAULT '' COMMENT '运单号',
  `order_amount` decimal(10,2) NOT NULL COMMENT '订单总价',
  `goods_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品总价',
  `cost_amount` decimal(10,2) NOT NULL COMMENT '商品成本价，用于统计收益',
  `comm_amount` decimal(10,2) NOT NULL COMMENT '总佣金，用于商家结算',
  `pre_amount` decimal(10,2) NOT NULL COMMENT '阶梯团定金',
  `end_amount` decimal(10,2) NOT NULL COMMENT '阶梯团尾款',
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '运费',
  `free_shipping` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否无条件包邮 一般用在免运费赠送实物商品的时候',
  `pay_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '第三方支付金额',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '未支付金额',
  `money_paid` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '第三方支付金额',
  `surplus` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '使用余额',
  `pay_id` tinyint(5) NOT NULL DEFAULT '0' COMMENT '支付方式ID',
  `pay_name` varchar(200) NOT NULL COMMENT '支付方式名',
  `address_id` int(11) NOT NULL DEFAULT '0' COMMENT '收货地址ID',
  `take_address_id` int(11) NOT NULL DEFAULT '0' COMMENT '自取地址id',
  `zone` int(11) NOT NULL DEFAULT '0' COMMENT '区域id',
  `area` varchar(200) NOT NULL COMMENT '区域',
  `address` varchar(500) NOT NULL COMMENT '收获地址',
  `mobile` varchar(100) NOT NULL,
  `name` varchar(200) NOT NULL COMMENT '收货 人',
  `order_tip` varchar(255) NOT NULL COMMENT '订单备注',
  `ship_remind` tinyint(1) NOT NULL COMMENT '发货提醒',
  `ship_time` int(11) NOT NULL COMMENT '发货提醒时间',
  `coupon_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '优惠券id',
  `coupon_id_sid` int(11) NOT NULL COMMENT '店铺优惠券ID',
  `score_sended` int(11) NOT NULL DEFAULT '0' COMMENT '购物时赠送的积分值 用来在退货时回溯',
  `is_rate` tinyint(1) NOT NULL COMMENT '是否评价 0:为评价，1:已评价',
  `is_refund` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否可以申请退款，1可以，2不可以',
  `extension_code` tinyint(2) NOT NULL DEFAULT '1' COMMENT '订单类型0：商品 1：拼团，2：1元购，3：秒杀，4:限时特卖，5：免费试用，6：抽奖,7:AA团',
  `extension_id` int(11) NOT NULL DEFAULT '0' COMMENT '对应lottery的id 免费试用和抽奖的id',
  `send_couponid` int(11) NOT NULL COMMENT '订单赠送的优惠券',
  `is_comm` tinyint(2) NOT NULL COMMENT '佣金是否发放 0:否，1:是',
  `bonus` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '使用红包',
  `integral` int(10) NOT NULL DEFAULT '0' COMMENT '使用积分',
  `credit` int(11) NOT NULL DEFAULT '0' COMMENT '本次订单可赠积分',
  `send_bonus` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1赠送红包',
  `user_bonus_id` varchar(255) NOT NULL DEFAULT '0' COMMENT '使用的所有红包ID',
  `deposit` decimal(10,0) NOT NULL DEFAULT '0' COMMENT '使用冻结款',
  `best_time` varchar(200) NOT NULL COMMENT '最佳送货时间',
  `is_cod` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1货到付款',
  `is_share` smallint(3) NOT NULL DEFAULT '0' COMMENT '是否晒单',
  `sid` int(11) NOT NULL COMMENT '商家id',
  `square_desc` varchar(255) NOT NULL COMMENT '广场内容',
  `square_time` int(11) NOT NULL COMMENT '广场发布时间',
  `is_robots` tinyint(1) NOT NULL COMMENT '是否机器人下单 1是 0否',
  `order_bill` tinyint(1) NOT NULL COMMENT '0未结算，1已结算',
  `coupon_id_num` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '平台抵用券价值，用于商家结算',
  `coupon_id_sid_num` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商家抵用券价值，用于商家结算',
  `is_balance` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0,财务未结算，1财务已结算',
  PRIMARY KEY (`id`),
  KEY `common_id` (`common_id`) USING BTREE,
  KEY `order_mid` (`mid`) USING BTREE,
  KEY `extren` (`status_common`,`extension_code`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品订单数据表表';

-- ----------------------------
-- Records of zz_goods_order
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_goods_order_common`
-- ----------------------------
DROP TABLE IF EXISTS `zz_goods_order_common`;
CREATE TABLE `zz_goods_order_common` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL COMMENT '开团会员id',
  `goods_id` int(11) NOT NULL COMMENT '商品id',
  `goods_typeid` tinyint(2) NOT NULL COMMENT '商品类型',
  `team_num` int(11) NOT NULL COMMENT '参团人数',
  `team_num_yes` int(11) NOT NULL COMMENT '已参团人数',
  `team_price` decimal(10,2) NOT NULL COMMENT '拼团价格',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态1:组团中,2组团失败,10：组团成功',
  `c_time` int(11) NOT NULL COMMENT '开团时间',
  `u_time` int(11) NOT NULL COMMENT '成团时间',
  `e_time` int(11) NOT NULL COMMENT '截止时间',
  `sid` int(11) NOT NULL COMMENT '商家id',
  `is_robots` tinyint(2) NOT NULL COMMENT '是否机器人开团',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='组团订单表';

-- ----------------------------
-- Records of zz_goods_order_common
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_goods_order_item`
-- ----------------------------
DROP TABLE IF EXISTS `zz_goods_order_item`;
CREATE TABLE `zz_goods_order_item` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(11) DEFAULT '0',
  `order_id` int(11) DEFAULT '0' COMMENT '订单id',
  `good_id` int(11) DEFAULT '0',
  `goods_name` varchar(255) DEFAULT NULL,
  `goods_spec` varchar(100) DEFAULT NULL COMMENT '商品规格',
  `spec` varchar(50) DEFAULT NULL COMMENT '商品规格id组合',
  `buy_num` int(11) DEFAULT NULL COMMENT '购买数量',
  `cost_price` decimal(10,2) DEFAULT NULL COMMENT '成本价',
  `sell_price` decimal(10,2) DEFAULT NULL COMMENT '卖出单价',
  `c_time` int(10) DEFAULT NULL,
  `type` tinyint(1) DEFAULT '1' COMMENT '1商品 2秒杀 3团购 4套餐',
  `obj_id` int(11) DEFAULT '0' COMMENT '扩展ID',
  `goods_info` varchar(200) DEFAULT NULL,
  `extension_id` int(11) DEFAULT '0' COMMENT '其它类型的商品ID 其它类型的商品ID 跟zz_goods_activity 的act_id对应',
  `share_id` int(11) DEFAULT '0' COMMENT '晒单ID',
  `verify_code_id` int(11) NOT NULL DEFAULT '0' COMMENT '自取验证码id',
  `team_num` int(4) NOT NULL COMMENT '拼团人数，用于自选团',
  `express_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单和商品的关联表';

-- ----------------------------
-- Records of zz_goods_order_item
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_goods_order_log`
-- ----------------------------
DROP TABLE IF EXISTS `zz_goods_order_log`;
CREATE TABLE `zz_goods_order_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `state_info` varchar(30) DEFAULT NULL COMMENT '操作日志',
  `c_time` int(11) DEFAULT NULL,
  `userid` int(11) DEFAULT '0' COMMENT '管理员ID',
  `username` varchar(100) DEFAULT NULL COMMENT ' 管理员名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品订单日志表';

-- ----------------------------
-- Records of zz_goods_order_log
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_goods_rate`
-- ----------------------------
DROP TABLE IF EXISTS `zz_goods_rate`;
CREATE TABLE `zz_goods_rate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL COMMENT '评价会员',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `good_id` int(11) NOT NULL COMMENT '商品ID',
  `goods_spec` varchar(100) NOT NULL COMMENT '商品规格',
  `star` tinyint(1) NOT NULL COMMENT '星数',
  `content` varchar(255) NOT NULL COMMENT '评价内容',
  `state` tinyint(1) NOT NULL COMMENT '0待审核，1审核通过，2审核通过',
  `c_time` int(11) NOT NULL COMMENT '评价时间',
  `u_time` int(11) NOT NULL COMMENT '修改时间',
  `listorder` int(4) NOT NULL COMMENT '排序',
  `buy_num` int(5) NOT NULL COMMENT '购买数量',
  `sid` int(11) NOT NULL COMMENT '商家sid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='评论表';

-- ----------------------------
-- Records of zz_goods_rate
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_goods_spec`
-- ----------------------------
DROP TABLE IF EXISTS `zz_goods_spec`;
CREATE TABLE `zz_goods_spec` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL COMMENT '规格名称',
  `type` tinyint(2) DEFAULT '0' COMMENT '类型,0文字,1图片,2颜色',
  `catid` int(11) DEFAULT NULL COMMENT '所属分类',
  `catname` varchar(50) DEFAULT NULL COMMENT '所属分类名称',
  `c_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COMMENT='商品规格';

-- ----------------------------
-- Records of zz_goods_spec
-- ----------------------------
INSERT INTO `zz_goods_spec` VALUES ('19', '口味', '1', '0', '', '1492407653');
INSERT INTO `zz_goods_spec` VALUES ('20', '款式', '1', '0', '', '1492407682');
INSERT INTO `zz_goods_spec` VALUES ('21', '颜色', '1', '0', '', '1498307775');
INSERT INTO `zz_goods_spec` VALUES ('22', '尺码', '1', '0', '', '1498308047');
INSERT INTO `zz_goods_spec` VALUES ('23', '尺寸', '1', '0', '', '1498615331');
INSERT INTO `zz_goods_spec` VALUES ('24', '容量', '1', '0', '', '1498615781');
INSERT INTO `zz_goods_spec` VALUES ('25', '器型', '1', '0', '', '1498787770');
INSERT INTO `zz_goods_spec` VALUES ('26', '花型', '1', '0', '', '1498787819');
INSERT INTO `zz_goods_spec` VALUES ('27', '香型', '1', '0', '', '1498787857');
INSERT INTO `zz_goods_spec` VALUES ('28', '功效', '1', '0', '', '1498787884');
INSERT INTO `zz_goods_spec` VALUES ('29', '型号', '1', '0', '', '1498787911');
INSERT INTO `zz_goods_spec` VALUES ('30', '套餐', '1', '0', '', '1498787937');
INSERT INTO `zz_goods_spec` VALUES ('31', '运营商', '1', '0', '', '1498787959');
INSERT INTO `zz_goods_spec` VALUES ('32', '材质', '1', '0', '', '1498787984');
INSERT INTO `zz_goods_spec` VALUES ('33', '成份', '1', '0', '', '1498788010');
INSERT INTO `zz_goods_spec` VALUES ('34', '版本', '1', '0', '', '1498788035');
INSERT INTO `zz_goods_spec` VALUES ('35', '色号', '1', '0', '', '1498788073');
INSERT INTO `zz_goods_spec` VALUES ('36', '货号', '1', '0', '', '1498788093');
INSERT INTO `zz_goods_spec` VALUES ('37', '类别', '1', '0', '', '1498788114');
INSERT INTO `zz_goods_spec` VALUES ('38', '属性', '1', '0', '', '1498788140');
INSERT INTO `zz_goods_spec` VALUES ('39', '适用年龄', '1', '0', '', '1498788170');
INSERT INTO `zz_goods_spec` VALUES ('40', '重量', '1', '0', '', '1498788186');
INSERT INTO `zz_goods_spec` VALUES ('41', '适用人群', '1', '0', '', '1498788228');
INSERT INTO `zz_goods_spec` VALUES ('42', '组合', '1', '0', '', '1498788253');
INSERT INTO `zz_goods_spec` VALUES ('43', '品类', '1', '0', '', '1498788295');
INSERT INTO `zz_goods_spec` VALUES ('44', '度数', '1', '0', '', '1498788319');
INSERT INTO `zz_goods_spec` VALUES ('45', '罐数', '1', '0', '', '1500341953');
INSERT INTO `zz_goods_spec` VALUES ('46', '盒数', '1', '0', '', '1501387974');

-- ----------------------------
-- Table structure for `zz_goods_spec_item`
-- ----------------------------
DROP TABLE IF EXISTS `zz_goods_spec_item`;
CREATE TABLE `zz_goods_spec_item` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `spec_id` int(11) DEFAULT NULL COMMENT '规格ID',
  `value` varchar(60) DEFAULT NULL COMMENT '属性名称',
  `order` int(11) DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `spec_id` (`spec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=675 DEFAULT CHARSET=utf8 COMMENT='商品规格属性';

-- ----------------------------
-- Records of zz_goods_spec_item
-- ----------------------------
INSERT INTO `zz_goods_spec_item` VALUES ('114', '19', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('115', '19', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('116', '19', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('117', '20', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('118', '20', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('119', '20', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('120', '21', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('121', '21', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('122', '21', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('123', '21', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('124', '21', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('125', '21', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('126', '21', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('127', '21', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('128', '21', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('129', '21', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('130', '21', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('131', '21', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('132', '21', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('133', '21', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('134', '21', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('135', '21', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('136', '21', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('137', '22', '自定义', '1');
INSERT INTO `zz_goods_spec_item` VALUES ('138', '22', '自定义', '2');
INSERT INTO `zz_goods_spec_item` VALUES ('139', '22', '自定义', '3');
INSERT INTO `zz_goods_spec_item` VALUES ('140', '22', '自定义', '4');
INSERT INTO `zz_goods_spec_item` VALUES ('141', '22', '自定义', '5');
INSERT INTO `zz_goods_spec_item` VALUES ('142', '22', '自定义', '6');
INSERT INTO `zz_goods_spec_item` VALUES ('143', '22', '自定义', '7');
INSERT INTO `zz_goods_spec_item` VALUES ('144', '22', '自定义', '8');
INSERT INTO `zz_goods_spec_item` VALUES ('145', '22', '自定义', '9');
INSERT INTO `zz_goods_spec_item` VALUES ('146', '22', '自定义', '10');
INSERT INTO `zz_goods_spec_item` VALUES ('147', '22', '自定义', '11');
INSERT INTO `zz_goods_spec_item` VALUES ('148', '22', '自定义', '12');
INSERT INTO `zz_goods_spec_item` VALUES ('149', '22', '自定义', '13');
INSERT INTO `zz_goods_spec_item` VALUES ('150', '22', '自定义', '14');
INSERT INTO `zz_goods_spec_item` VALUES ('151', '22', '自定义', '15');
INSERT INTO `zz_goods_spec_item` VALUES ('152', '22', '自定义', '16');
INSERT INTO `zz_goods_spec_item` VALUES ('153', '22', '自定义', '17');
INSERT INTO `zz_goods_spec_item` VALUES ('154', '23', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('155', '23', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('156', '23', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('157', '23', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('158', '23', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('159', '23', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('160', '23', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('161', '23', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('162', '23', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('163', '23', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('164', '23', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('165', '23', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('166', '24', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('167', '24', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('168', '24', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('169', '24', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('170', '24', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('171', '24', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('172', '24', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('173', '24', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('174', '24', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('175', '24', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('176', '24', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('177', '24', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('178', '24', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('179', '23', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('180', '23', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('181', '23', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('182', '23', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('183', '24', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('184', '24', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('185', '24', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('186', '24', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('187', '19', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('188', '19', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('189', '19', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('190', '19', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('191', '19', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('192', '19', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('193', '20', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('194', '20', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('195', '20', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('196', '20', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('197', '20', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('198', '20', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('199', '20', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('200', '25', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('201', '25', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('202', '25', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('203', '25', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('204', '25', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('205', '25', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('206', '25', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('207', '25', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('208', '25', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('209', '25', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('210', '26', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('211', '26', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('212', '26', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('213', '26', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('214', '26', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('215', '26', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('216', '26', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('217', '26', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('218', '26', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('219', '26', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('220', '27', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('221', '27', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('222', '27', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('223', '27', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('224', '27', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('225', '27', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('226', '27', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('227', '27', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('228', '27', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('229', '27', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('230', '28', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('231', '28', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('232', '28', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('233', '28', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('234', '28', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('235', '28', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('236', '28', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('237', '28', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('238', '28', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('239', '28', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('240', '29', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('241', '29', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('242', '29', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('243', '29', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('244', '29', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('245', '29', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('246', '29', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('247', '29', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('248', '29', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('249', '29', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('250', '30', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('251', '30', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('252', '30', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('253', '30', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('254', '30', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('255', '30', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('256', '30', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('257', '30', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('258', '30', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('259', '30', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('260', '31', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('261', '31', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('262', '31', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('263', '31', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('264', '31', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('265', '31', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('266', '31', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('267', '31', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('268', '31', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('269', '31', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('270', '32', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('271', '32', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('272', '32', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('273', '32', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('274', '32', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('275', '32', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('276', '32', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('277', '32', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('278', '32', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('279', '32', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('280', '33', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('281', '33', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('282', '33', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('283', '33', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('284', '33', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('285', '33', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('286', '33', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('287', '33', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('288', '33', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('289', '33', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('290', '34', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('291', '34', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('292', '34', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('293', '34', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('294', '34', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('295', '34', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('296', '34', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('297', '34', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('298', '34', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('299', '34', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('300', '35', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('301', '35', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('302', '35', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('303', '35', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('304', '35', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('305', '35', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('306', '35', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('307', '35', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('308', '35', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('309', '36', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('310', '36', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('311', '36', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('312', '36', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('313', '36', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('314', '36', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('315', '36', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('316', '36', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('317', '36', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('318', '36', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('319', '37', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('320', '37', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('321', '37', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('322', '37', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('323', '37', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('324', '37', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('325', '37', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('326', '37', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('327', '37', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('328', '37', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('329', '38', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('330', '38', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('331', '38', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('332', '38', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('333', '38', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('334', '38', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('335', '38', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('336', '38', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('337', '38', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('338', '38', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('339', '39', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('340', '39', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('341', '39', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('342', '39', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('343', '39', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('344', '39', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('345', '39', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('346', '39', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('347', '39', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('348', '39', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('349', '40', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('350', '40', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('351', '40', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('352', '40', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('353', '40', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('354', '40', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('355', '40', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('356', '40', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('357', '40', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('358', '40', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('359', '41', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('360', '41', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('361', '41', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('362', '41', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('363', '41', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('364', '41', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('365', '41', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('366', '41', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('367', '41', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('368', '41', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('369', '42', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('370', '42', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('371', '42', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('372', '42', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('373', '42', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('374', '42', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('375', '42', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('376', '42', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('377', '42', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('378', '42', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('379', '43', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('380', '43', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('381', '43', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('382', '43', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('383', '43', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('384', '43', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('385', '43', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('386', '43', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('387', '43', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('388', '44', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('389', '44', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('390', '44', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('391', '44', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('392', '44', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('393', '44', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('394', '44', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('395', '44', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('396', '44', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('397', '44', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('398', '19', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('399', '19', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('400', '19', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('401', '19', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('402', '19', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('403', '19', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('404', '20', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('405', '20', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('406', '20', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('407', '20', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('408', '20', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('409', '20', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('410', '20', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('411', '20', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('412', '21', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('413', '21', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('414', '21', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('415', '21', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('416', '21', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('417', '21', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('418', '21', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('419', '21', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('420', '20', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('421', '20', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('422', '20', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('423', '20', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('424', '20', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('425', '23', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('426', '23', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('427', '23', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('428', '23', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('429', '23', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('430', '23', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('431', '23', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('432', '23', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('433', '29', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('434', '29', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('435', '29', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('436', '29', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('437', '29', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('438', '29', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('439', '29', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('440', '29', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('441', '29', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('442', '29', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('443', '29', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('444', '35', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('445', '35', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('446', '35', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('447', '35', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('448', '35', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('449', '35', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('450', '35', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('451', '35', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('452', '35', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('453', '35', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('454', '35', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('455', '35', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('456', '35', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('457', '43', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('458', '43', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('459', '43', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('460', '43', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('461', '43', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('462', '43', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('463', '43', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('464', '43', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('465', '43', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('466', '43', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('467', '43', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('468', '43', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('469', '42', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('470', '42', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('471', '42', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('472', '42', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('473', '42', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('474', '42', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('475', '42', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('476', '42', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('477', '42', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('478', '42', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('479', '42', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('480', '42', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('481', '42', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('482', '22', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('483', '22', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('484', '22', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('485', '22', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('486', '22', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('487', '22', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('488', '29', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('489', '29', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('490', '29', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('491', '29', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('492', '27', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('493', '27', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('494', '27', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('495', '27', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('496', '27', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('497', '27', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('498', '27', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('499', '27', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('500', '27', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('501', '27', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('502', '27', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('503', '26', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('504', '26', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('505', '26', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('506', '26', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('507', '26', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('508', '26', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('509', '26', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('510', '26', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('511', '26', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('512', '26', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('513', '26', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('514', '25', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('515', '25', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('516', '25', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('517', '25', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('518', '25', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('519', '25', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('520', '25', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('521', '25', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('522', '25', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('523', '28', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('524', '28', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('525', '28', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('526', '28', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('527', '28', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('528', '28', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('529', '28', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('530', '28', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('531', '28', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('532', '30', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('533', '30', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('534', '30', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('535', '30', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('536', '30', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('537', '30', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('538', '30', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('539', '30', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('540', '30', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('541', '31', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('542', '31', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('543', '31', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('544', '31', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('545', '31', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('546', '31', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('547', '31', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('548', '31', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('549', '31', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('550', '32', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('551', '32', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('552', '32', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('553', '32', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('554', '32', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('555', '32', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('556', '32', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('557', '32', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('558', '32', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('559', '33', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('560', '33', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('561', '33', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('562', '33', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('563', '33', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('564', '33', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('565', '33', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('566', '33', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('567', '33', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('568', '34', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('569', '34', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('570', '34', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('571', '34', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('572', '34', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('573', '34', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('574', '34', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('575', '34', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('576', '34', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('577', '36', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('578', '36', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('579', '36', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('580', '36', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('581', '36', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('582', '36', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('583', '36', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('584', '36', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('585', '36', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('586', '37', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('587', '37', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('588', '37', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('589', '37', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('590', '37', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('591', '37', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('592', '37', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('593', '37', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('594', '37', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('595', '38', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('596', '38', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('597', '38', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('598', '38', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('599', '38', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('600', '38', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('601', '38', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('602', '38', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('603', '38', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('604', '39', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('605', '39', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('606', '39', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('607', '39', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('608', '39', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('609', '39', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('610', '39', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('611', '39', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('612', '39', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('613', '40', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('614', '40', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('615', '40', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('616', '40', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('617', '40', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('618', '40', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('619', '40', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('620', '40', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('621', '40', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('622', '41', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('623', '41', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('624', '41', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('625', '41', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('626', '41', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('627', '41', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('628', '41', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('629', '41', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('630', '41', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('631', '44', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('632', '44', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('633', '44', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('634', '44', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('635', '44', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('636', '44', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('637', '44', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('638', '44', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('639', '44', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('640', '19', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('641', '19', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('642', '19', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('643', '19', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('644', '19', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('645', '24', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('646', '24', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('647', '45', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('648', '45', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('649', '45', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('650', '45', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('651', '45', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('652', '45', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('653', '45', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('654', '45', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('655', '45', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('656', '45', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('657', '46', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('658', '46', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('659', '46', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('660', '46', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('661', '46', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('662', '46', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('663', '46', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('664', '46', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('665', '46', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('666', '46', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('667', '46', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('668', '46', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('669', '46', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('670', '46', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('671', '46', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('672', '46', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('673', '46', '自定义', '0');
INSERT INTO `zz_goods_spec_item` VALUES ('674', '46', '自定义', '0');

-- ----------------------------
-- Table structure for `zz_goods_tag`
-- ----------------------------
DROP TABLE IF EXISTS `zz_goods_tag`;
CREATE TABLE `zz_goods_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='商品标签';

-- ----------------------------
-- Records of zz_goods_tag
-- ----------------------------
INSERT INTO `zz_goods_tag` VALUES ('1', '全场包邮');
INSERT INTO `zz_goods_tag` VALUES ('2', '7天无理由退货');
INSERT INTO `zz_goods_tag` VALUES ('3', '48小时发货');
INSERT INTO `zz_goods_tag` VALUES ('4', '正品保证');
INSERT INTO `zz_goods_tag` VALUES ('5', '售后补贴');
INSERT INTO `zz_goods_tag` VALUES ('6', '海外直釆');
INSERT INTO `zz_goods_tag` VALUES ('7', '国内保税仓发货');
INSERT INTO `zz_goods_tag` VALUES ('8', '海关全程监控');
INSERT INTO `zz_goods_tag` VALUES ('9', '官方自营');
INSERT INTO `zz_goods_tag` VALUES ('10', '人工精选');

-- ----------------------------
-- Table structure for `zz_goods_type`
-- ----------------------------
DROP TABLE IF EXISTS `zz_goods_type`;
CREATE TABLE `zz_goods_type` (
  `cat_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(60) NOT NULL DEFAULT '' COMMENT '类型名称',
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `attr_group` varchar(255) NOT NULL COMMENT '类型分组',
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品属性';

-- ----------------------------
-- Records of zz_goods_type
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_images`
-- ----------------------------
DROP TABLE IF EXISTS `zz_images`;
CREATE TABLE `zz_images` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `data_id` int(10) DEFAULT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '图片名称',
  `imgurl` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '路径',
  `cate` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '图片类型',
  `imgspace` int(10) DEFAULT NULL COMMENT '文件尺寸',
  `size` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '图片长宽',
  `c_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='图片上传记录';

-- ----------------------------
-- Records of zz_images
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_images_thumb`
-- ----------------------------
DROP TABLE IF EXISTS `zz_images_thumb`;
CREATE TABLE `zz_images_thumb` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `image_type` varchar(30) DEFAULT NULL COMMENT '图片类型',
  `rule` varchar(600) DEFAULT NULL,
  `note` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='定制缩略图参数表  全局';

-- ----------------------------
-- Records of zz_images_thumb
-- ----------------------------
INSERT INTO `zz_images_thumb` VALUES ('5', 'news_cover', '{\"thumb\":{\"width\":200,\"height\":200}}', '新闻封面图');
INSERT INTO `zz_images_thumb` VALUES ('6', 'product_cover', '{\"thumb\":{\"width\":200,\"height\":200}}', '产品封面图');
INSERT INTO `zz_images_thumb` VALUES ('7', 'gallery', '{\"thumb\":{\"width\":140,\"height\":140},\"middle\":{\"width\":600,\"height\":0}}', '');
INSERT INTO `zz_images_thumb` VALUES ('9', 'category', null, '栏目封面图');
INSERT INTO `zz_images_thumb` VALUES ('10', 'files', null, '文件');

-- ----------------------------
-- Table structure for `zz_kuaibao`
-- ----------------------------
DROP TABLE IF EXISTS `zz_kuaibao`;
CREATE TABLE `zz_kuaibao` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `userid` int(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(40) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `title_style` varchar(40) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `keywords` varchar(120) NOT NULL DEFAULT '',
  `description` mediumtext NOT NULL,
  `content` mediumtext NOT NULL,
  `url` varchar(60) NOT NULL DEFAULT '',
  `template` varchar(40) NOT NULL DEFAULT '',
  `posid` varchar(40) NOT NULL DEFAULT '',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `listorder` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(11) unsigned NOT NULL DEFAULT '0',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(11) unsigned NOT NULL DEFAULT '0',
  `lang` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `titleprefix` text NOT NULL,
  `links` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`id`,`status`,`listorder`) USING BTREE,
  KEY `catid` (`id`,`catid`,`status`) USING BTREE,
  KEY `listorder` (`id`,`catid`,`status`,`listorder`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_kuaibao
-- ----------------------------
INSERT INTO `zz_kuaibao` VALUES ('3', '26', '1', 'lnest_admin', '拼团新添加玩法介绍', '', '[]', '', '', '<h3 style=\"font-variant-ligatures:normal;orphans:2;widows:2;font-family:Simsun;white-space:normal;\">\r\n	新添加阶梯团玩法介绍\r\n</h3>\r\n<div style=\"font-variant-ligatures:normal;orphans:2;widows:2;font-family:Simsun;font-size:medium;white-space:normal;padding:15px 0px;\">\r\n	<p>\r\n		概述：参团人数越多越便宜<br />\r\n阶梯团采用的是阶梯定价的方式，采取差异化的定价，拼团的人数越多就越便宜。商家可对每层阶梯对应的人数和价格进行设置阶梯。<br />\r\n比如：某个商品10人成团价格是100元，30人成团价格是80元，50人价格是65元等以此类推，<br />\r\n参团方式是以支付宝金的形式进行的，拼团时间一到就按拼团的人数来对应相应的阶梯，然后在支付尾款，不支付的定金是不退的\r\n	</p>\r\n</div>\r\n<h3 style=\"font-variant-ligatures:normal;orphans:2;widows:2;font-family:Simsun;white-space:normal;\">\r\n	新添加推广团玩法介绍\r\n</h3>\r\n<div style=\"font-variant-ligatures:normal;orphans:2;widows:2;font-family:Simsun;font-size:medium;white-space:normal;padding:15px 0px;\">\r\n	<p>\r\n		概述：推广团（也称佣金团）就是给团长一些奖励<br />\r\n比如一个商品是5人团，价格是100元，团长佣金是是10元+，团长花了100元开了些团，然后分享给朋友，这时四个人全都参了团，那么团长就可以得到40元佣金， 佣金会在商品详细页面上出来的，参团的人员是看不到这个佣金的，只有要开团的人和团长才可见的，\r\n	</p>\r\n</div>\r\n<h3 style=\"font-variant-ligatures:normal;orphans:2;widows:2;font-family:Simsun;white-space:normal;\">\r\n	新添加分销团玩法介绍\r\n</h3>\r\n<div style=\"font-variant-ligatures:normal;orphans:2;widows:2;font-family:Simsun;font-size:medium;white-space:normal;padding:15px 0px;\">\r\n	<p>\r\n		概述：分销团就是会员（下家、粉丝）购买了带有分销提成的商品，上家可以得到提成<br />\r\n注：比如我开了一个团，邀请了一个新会员参了团，那么这个新会员就是我的会员，他如果购买带有分销的商品那么我就可以拿到一定的提成\r\n	</p>\r\n</div>\r\n<h3 style=\"font-variant-ligatures:normal;orphans:2;widows:2;font-family:Simsun;white-space:normal;\">\r\n	更多玩法，可以联系我们的商务人员咨询\r\n</h3>', '/content/show/26/4', '', '', '1', '0', '0', '1484544665', '0', '1', '更新', '');
INSERT INTO `zz_kuaibao` VALUES ('4', '26', '1', 'lnest_admin', '拼团APP改版公告', '', '[]', '', '', '<h3 style=\"font-variant-ligatures:normal;orphans:2;white-space:normal;widows:2;font-family:Simsun;\">\r\n	对应用的功能区域进行了添加和修改 如下图所示\r\n</h3>\r\n<div style=\"font-variant-ligatures:normal;orphans:2;white-space:normal;widows:2;font-family:Simsun;font-size:medium;padding:15px 0px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/1/images/gallery/q/d/950_src.png\" width=\"415\" height=\"176\" alt=\"\" title=\"\" />\r\n</div>\r\n<h3 style=\"font-variant-ligatures:normal;orphans:2;white-space:normal;widows:2;font-family:Simsun;\">\r\n	对应用的就等你了修改为拼团广场，所有的拼团就得要自己手动发布 如下图所示\r\n</h3>\r\n<div style=\"font-variant-ligatures:normal;orphans:2;white-space:normal;widows:2;font-family:Simsun;font-size:medium;padding:15px 0px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/1/images/gallery/q/e/951_src.png\" width=\"414\" height=\"739\" alt=\"\" title=\"\" />\r\n</div>\r\n<h3 style=\"font-variant-ligatures:normal;orphans:2;white-space:normal;widows:2;font-family:Simsun;\">\r\n	对应用的商品详细进行了修改，添加了阶梯团的价格显示列表、自己选团、佣金提示、拼团类型等修改 如下图所示\r\n</h3>\r\n<div style=\"font-variant-ligatures:normal;orphans:2;white-space:normal;widows:2;font-family:Simsun;font-size:medium;padding:15px 0px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/1/images/gallery/q/f/952_src.png\" width=\"417\" height=\"738\" alt=\"\" title=\"\" />\r\n</div>\r\n<h3 style=\"font-variant-ligatures:normal;orphans:2;white-space:normal;widows:2;font-family:Simsun;\">\r\n	对应用的会员中心进行了全面的修改、新添加了佣金提现、设置（修改头像、用户名、密码等）、我的会员等功能 如下图所示\r\n</h3>\r\n<div style=\"font-variant-ligatures:normal;orphans:2;white-space:normal;widows:2;font-family:Simsun;font-size:medium;padding:15px 0px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/1/images/gallery/q/g/953_src.png\" width=\"416\" height=\"736\" alt=\"\" title=\"\" />\r\n</div>\r\n<h3 style=\"font-variant-ligatures:normal;orphans:2;white-space:normal;widows:2;font-family:Simsun;\">\r\n	对应用的下单子的页面添加了自提功能，然后对订单页面也添加了自提码验证 如下图所示\r\n</h3>\r\n<div style=\"font-variant-ligatures:normal;orphans:2;white-space:normal;widows:2;font-family:Simsun;font-size:medium;padding:15px 0px;\">\r\n	<img src=\"http://pin.lnest.cc/upload/1/images/gallery/q/h/954_src.png\" width=\"412\" height=\"740\" alt=\"\" title=\"\" />\r\n</div>\r\n<h3 style=\"font-variant-ligatures:normal;orphans:2;white-space:normal;widows:2;font-family:Simsun;\">\r\n	更多修改内容，可咨询我们的商务人员\r\n</h3>', '/content/show/26/3', '', '', '1', '0', '0', '1484544620', '0', '1', '更新', '');

-- ----------------------------
-- Table structure for `zz_linkage`
-- ----------------------------
DROP TABLE IF EXISTS `zz_linkage`;
CREATE TABLE `zz_linkage` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '',
  `arrparentid` text NOT NULL,
  `arrchildid` text NOT NULL,
  `child` tinyint(1) NOT NULL DEFAULT '0',
  `description` varchar(255) NOT NULL,
  `listorder` int(10) NOT NULL DEFAULT '0',
  `lang` tinyint(2) NOT NULL DEFAULT '0',
  `mark` varchar(30) NOT NULL,
  `thumb` text NOT NULL COMMENT 'banner图',
  `nav` text NOT NULL COMMENT '导航',
  `ad` text NOT NULL COMMENT '广告',
  `status` tinyint(2) NOT NULL COMMENT '是否开通，0否，1是',
  `pin` varchar(50) NOT NULL COMMENT '拼音',
  `is_hot` tinyint(2) NOT NULL COMMENT '是否热门城市',
  `catid` text NOT NULL COMMENT '同城分类',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parentid`) USING BTREE,
  KEY `id` (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4620 DEFAULT CHARSET=utf8 COMMENT='地区联动';

-- ----------------------------
-- Records of zz_linkage
-- ----------------------------
INSERT INTO `zz_linkage` VALUES ('1', '0', '地区', '0', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,123,124,125,126,127,128,129,130,131,132,133,134,135,136,137,138,139,140,141,142,143,144,145,146,147,148,149,150,151,152,153,154,155,156,157,158,159,160,161,162,163,164,165,166,167,168,169,170,171,172,173,174,175,176,177,178,179,180,181,182,183,184,185,186,187,188,189,190,191,192,193,194,195,196,197,198,199,200,201,202,203,204,205,206,207,208,209,210,211,212,213,214,215,216,217,218,219,220,221,222,223,224,225,226,227,228,229,230,231,232,233,234,235,236,237,238,239,240,241,242,243,244,245,246,247,248,249,250,251,252,253,254,255,256,257,258,259,260,261,262,263,264,265,266,267,268,269,270,271,272,273,274,275,276,277,278,279,280,281,282,283,284,285,286,287,288,289,290,291,292,293,294,295,296,297,298,299,300,301,302,303,304,305,306,307,308,309,310,311,312,313,314,315,316,317,318,319,320,322,323,324,325,326,327,328,329,330,331,332,333,334,335,336,337,338,339,340,341,342,344,345,346,347,348,349,350,351,352,353,354,355,356,357,358,359,360,361,362,363,364,365,366,367,368,369,370,371,372,373,374,375,376,377,378,379,380,381,382,383,384,385,386,387,388,389,390,391,392,393,398,399,400,401,402,404,405,406,407,408,409,410,411,412,413,414,415,416,417,418,419,420,421,422,423,424,425,426,427,428,429,430,431,432,437,438,439,440,441,442,443,444,445,446,447,448,449,450,451,452,453,454,455,456,457,458,459,460,461,462,463,465,466,467,468,469,470,471,472,473,474,475,476,477,478,479,480,481,482,483,484,485,486,487,488,489,490,491,492,493,494,495,496,497,498,499,500,501,502,503,504,505,506,507,508,509,510,511,512,513,514,515,516,517,518,519,520,521,522,523,524,525,526,527,528,529,530,531,532,533,534,535,536,537,538,539,540,541,542,543,544,545,546,547,548,549,550,551,552,553,554,555,556,557,558,559,560,561,562,563,564,565,566,567,568,569,570,571,572,573,574,575,576,577,578,579,580,581,582,583,584,585,586,587,588,589,590,591,592,593,594,595,596,597,598,599,600,601,602,603,604,605,606,607,608,609,610,611,612,613,614,615,616,617,618,619,620,621,622,623,625,626,627,628,629,630,631,632,633,634,635,636,637,638,639,640,641,642,643,644,645,646,647,648,649,650,651,652,653,654,655,656,657,658,659,660,661,662,663,664,665,666,667,668,669,670,671,672,673,674,675,676,677,678,679,680,681,682,683,684,685,686,687,688,689,690,691,692,693,694,695,696,697,698,699,700,701,702,703,704,705,706,707,708,709,710,711,712,713,714,715,716,717,718,719,720,721,722,723,724,725,726,727,728,729,730,731,732,733,734,735,736,737,738,739,740,741,742,743,744,745,746,747,748,749,750,751,752,753,754,755,756,757,758,759,760,761,762,763,764,765,766,767,768,769,770,771,772,773,774,775,776,777,778,779,780,781,782,783,784,785,786,787,788,789,790,791,792,793,794,795,796,797,798,799,800,801,802,803,804,805,806,807,808,809,810,811,812,813,814,815,816,817,818,819,820,821,822,823,824,825,826,827,828,829,830,831,832,833,834,835,836,837,838,839,840,841,842,843,844,845,846,847,848,849,850,851,852,853,854,855,856,857,858,859,860,861,862,863,864,865,866,867,868,869,870,871,872,873,874,875,876,877,878,879,880,881,882,883,884,885,886,887,888,889,890,891,892,893,894,895,896,897,898,899,900,901,902,903,904,905,906,907,908,909,910,911,912,913,914,915,916,917,918,919,920,921,922,923,924,925,926,927,928,929,930,931,932,933,934,935,936,937,938,939,940,941,942,943,944,945,946,947,948,949,950,951,952,953,954,955,956,957,958,959,960,961,962,963,964,965,966,967,968,969,970,971,972,973,974,975,976,977,978,979,980,981,982,983,984,985,986,987,988,989,990,991,992,993,994,995,996,997,998,999,1000,1001,1002,1003,1004,1005,1006,1007,1008,1009,1010,1011,1012,1013,1014,1015,1016,1017,1018,1019,1020,1021,1022,1023,1024,1025,1026,1027,1028,1029,1030,1031,1032,1033,1034,1035,1036,1037,1038,1039,1040,1041,1042,1043,1044,1045,1046,1047,1048,1050,1051,1052,1053,1054,1055,1056,1057,1058,1059,1060,1061,1062,1063,1064,1065,1066,1067,1068,1069,1070,1071,1072,1073,1074,1075,1076,1077,1078,1079,1080,1081,1082,1083,1084,1085,1086,1087,1088,1089,1090,1091,1092,1093,1094,1095,1096,1097,1098,1099,1100,1101,1102,1103,1104,1105,1106,1107,1108,1109,1110,1111,1112,1113,1114,1115,1116,1117,1118,1119,1120,1121,1122,1123,1124,1125,1126,1127,1128,1129,1130,1131,1132,1133,1134,1135,1136,1137,1138,1139,1140,1141,1142,1143,1144,1145,1146,1147,1148,1149,1150,1151,1152,1153,1154,1155,1156,1157,1158,1159,1160,1161,1162,1163,1164,1165,1166,1167,1168,1169,1170,1171,1172,1173,1174,1175,1176,1177,1178,1179,1180,1181,1182,1183,1184,1185,1186,1187,1188,1189,1190,1191,1192,1193,1194,1195,1196,1197,1198,1199,1201,1202,1203,1204,1205,1206,1207,1208,1209,1210,1211,1212,1213,1214,1215,1216,1217,1218,1219,1220,1221,1222,1223,1224,1225,1226,1227,1228,1229,1230,1231,1232,1233,1234,1235,1236,1237,1238,1239,1240,1241,1242,1243,1244,1245,1246,1247,1248,1249,1250,1251,1252,1253,1254,1255,1256,1257,1258,1259,1260,1261,1262,1263,1264,1265,1266,1267,1268,1269,1270,1271,1272,1273,1274,1275,1276,1277,1278,1279,1280,1281,1282,1283,1284,1285,1286,1287,1288,1289,1290,1291,1292,1293,1294,1295,1296,1297,1298,1299,1300,1301,1302,1303,1304,1305,1306,1307,1308,1309,1310,1311,1312,1313,1314,1315,1316,1317,1318,1319,1320,1321,1322,1323,1324,1325,1326,1327,1328,1329,1330,1331,1332,1333,1334,1335,1336,1337,1338,1339,1340,1341,1342,1343,1344,1345,1346,1347,1348,1349,1350,1351,1352,1353,1354,1355,1356,1357,1358,1359,1360,1361,1362,1363,1364,1365,1366,1367,1368,1369,1370,1371,1372,1373,1374,1375,1376,1377,1378,1379,1380,1381,1382,1383,1384,1385,1386,1387,1388,1389,1390,1391,1392,1393,1394,1395,1396,1397,1398,1399,1400,1401,1402,1403,1404,1405,1406,1407,1408,1409,1410,1411,1412,1413,1414,1415,1416,1417,1418,1419,1420,1421,1422,1423,1424,1425,1426,1427,1428,1429,1430,1431,1432,1433,1434,1435,1436,1437,1438,1439,1440,1441,1442,1443,1444,1445,1446,1447,1448,1449,1450,1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461,1462,1463,1464,1465,1466,1467,1468,1469,1470,1471,1472,1473,1474,1475,1476,1477,1478,1479,1480,1481,1482,1483,1484,1485,1486,1487,1488,1489,1490,1491,1492,1493,1494,1495,1496,1497,1498,1499,1500,1501,1502,1503,1504,1505,1506,1507,1508,1509,1510,1511,1512,1513,1514,1515,1516,1517,1518,1519,1520,1521,1522,1523,1524,1525,1526,1527,1528,1529,1530,1531,1532,1533,1534,1535,1536,1537,1538,1539,1540,1541,1542,1543,1544,1545,1546,1547,1548,1549,1550,1551,1552,1553,1554,1555,1556,1557,1559,1560,1561,1562,1563,1564,1565,1566,1567,1568,1569,1570,1571,1572,1573,1574,1575,1576,1577,1578,1579,1580,1581,1582,1583,1584,1585,1586,1587,1588,1589,1590,1593,1594,1595,1596,1597,1598,1599,1600,1601,1602,1604,1605,1606,1607,1608,1609,1610,1611,1612,1613,1614,1615,1616,1617,1618,1619,1620,1621,1622,1623,1624,1625,1626,1627,1628,1629,1630,1631,1632,1633,1634,1635,1636,1637,1638,1639,1640,1641,1642,1643,1644,1645,1646,1647,1648,1649,1650,1651,1652,1653,1654,1655,1656,1657,1658,1659,1660,1661,1662,1663,1664,1665,1666,1667,1668,1669,1670,1671,1672,1673,1674,1675,1676,1677,1678,1679,1680,1681,1682,1683,1684,1685,1686,1687,1688,1689,1690,1691,1692,1693,1694,1695,1696,1697,1698,1699,1700,1701,1702,1703,1704,1705,1706,1707,1708,1709,1710,1711,1712,1713,1714,1715,1716,1717,1718,1719,1720,1721,1722,1723,1724,1725,1726,1727,1728,1729,1730,1731,1732,1733,1734,1735,1736,1737,1738,1739,1740,1741,1742,1743,1744,1745,1746,1747,1748,1749,1750,1751,1752,1753,1754,1755,1756,1757,1758,1759,1760,1761,1762,1763,1764,1765,1766,1767,1768,1769,1770,1771,1772,1773,1774,1775,1776,1777,1778,1779,1780,1781,1782,1783,1784,1785,1786,1787,1788,1789,1790,1791,1792,1793,1794,1795,1796,1797,1798,1799,1800,1801,1802,1803,1804,1805,1806,1807,1808,1809,1810,1811,1812,1813,1814,1815,1816,1817,1818,1819,1820,1821,1822,1823,1824,1825,1826,1827,1828,1829,1830,1831,1832,1833,1834,1835,1836,1837,1838,1839,1840,1841,1842,1843,1844,1845,1846,1847,1848,1849,1850,1851,1852,1853,1854,1855,1856,1857,1858,1859,1860,1861,1862,1863,1864,1865,1866,1867,1868,1869,1870,1871,1872,1873,1874,1875,1876,1877,1878,1879,1880,1881,1882,1883,1884,1885,1886,1887,1888,1889,1890,1891,1892,1893,1894,1895,1896,1897,1898,1899,1900,1901,1902,1903,1904,1905,1906,1907,1908,1909,1910,1911,1912,1913,1914,1915,1916,1917,1918,1919,1920,1921,1922,1923,1924,1925,1926,1927,1928,1929,1930,1931,1932,1933,1934,1935,1936,1937,1938,1939,1940,1941,1942,1943,1944,1945,1946,1947,1948,1949,1950,1951,1952,1953,1954,1955,1956,1957,1958,1959,1960,1961,1962,1963,1964,1965,1966,1967,1968,1969,1970,1971,1972,1973,1974,1975,1976,1977,1978,1979,1980,1981,1982,1983,1984,1985,1986,1987,1988,1989,1990,1991,1992,1993,1994,1995,1996,1997,1998,1999,2000,2001,2002,2003,2004,2005,2006,2007,2008,2009,2010,2011,2012,2013,2014,2015,2016,2017,2018,2019,2020,2021,2022,2023,2024,2025,2026,2027,2028,2029,2030,2031,2032,2033,2034,2035,2036,2037,2038,2039,2040,2041,2042,2043,2044,2045,2046,2047,2048,2049,2050,2051,2052,2053,2054,2055,2056,2057,2058,2059,2060,2061,2062,2063,2064,2065,2066,2067,2068,2069,2070,2071,2072,2073,2074,2075,2076,2077,2078,2079,2080,2081,2082,2083,2084,2085,2086,2087,2088,2089,2090,2091,2092,2093,2094,2095,2096,2097,2098,2099,2100,2101,2102,2103,2104,2105,2106,2107,2108,2109,2110,2111,2112,2113,2114,2115,2116,2117,2118,2119,2120,2121,2122,2123,2124,2125,2126,2127,2128,2129,2130,2131,2132,2133,2134,2135,2136,2137,2138,2139,2140,2141,2142,2143,2144,2145,2146,2147,2148,2149,2150,2151,2152,2153,2154,2155,2156,2157,2158,2159,2160,2161,2162,2163,2164,2165,2166,2167,2168,2169,2170,2171,2172,2173,2174,2175,2176,2177,2178,2179,2180,2181,2182,2183,2184,2185,2186,2187,2188,2189,2190,2191,2192,2193,2194,2195,2196,2197,2198,2199,2200,2201,2202,2203,2204,2205,2206,2207,2208,2209,2210,2211,2212,2213,2214,2215,2216,2217,2218,2219,2220,2221,2222,2223,2224,2225,2226,2227,2228,2229,2230,2231,2232,2233,2234,2235,2236,2237,2238,2239,2240,2241,2242,2243,2244,2245,2246,2247,2248,2249,2250,2251,2252,2253,2254,2255,2256,2257,2258,2259,2260,2261,2262,2263,2264,2265,2266,2267,2268,2269,2271,2272,2273,2274,2275,2276,2277,2278,2279,2280,2282,2284,2285,2286,2287,2288,2289,2290,2291,2292,2293,2294,2295,2296,2297,2298,2299,2300,2301,2302,2303,2304,2305,2306,2307,2308,2309,2310,2311,2312,2313,2314,2315,2316,2317,2318,2319,2320,2321,2322,2323,2324,2325,2326,2327,2328,2329,2330,2331,2332,2333,2334,2335,2336,2337,2338,2339,2340,2341,2342,2343,2344,2345,2346,2347,2348,2349,2350,2351,2352,2353,2354,2355,2356,2357,2358,2359,2360,2361,2362,2363,2364,2365,2366,2367,2368,2369,2370,2371,2372,2373,2374,2375,2376,2377,2378,2379,2380,2381,2382,2383,2384,2385,2386,2387,2388,2389,2390,2391,2392,2393,2394,2395,2396,2397,2398,2399,2400,2401,2402,2403,2404,2405,2406,2407,2408,2409,2410,2411,2412,2413,2414,2415,2416,2417,2418,2419,2420,2421,2422,2423,2424,2425,2426,2427,2428,2429,2430,2431,2432,2433,2434,2435,2436,2437,2438,2439,2440,2441,2442,2443,2444,2445,2446,2447,2448,2449,2450,2451,2452,2453,2454,2455,2456,2457,2458,2459,2460,2461,2462,2463,2464,2465,2466,2467,2468,2469,2470,2471,2472,2473,2474,2475,2476,2477,2478,2479,2480,2481,2482,2483,2484,2485,2486,2487,2488,2489,2490,2491,2492,2493,2494,2495,2496,2497,2498,2499,2500,2501,2502,2503,2504,2505,2506,2507,2508,2509,2510,2511,2512,2513,2514,2515,2516,2517,2518,2519,2520,2521,2522,2523,2524,2525,2526,2527,2528,2529,2530,2531,2532,2533,2534,2535,2536,2537,2538,2539,2540,2541,2542,2543,2544,2545,2546,2547,2548,2549,2550,2551,2552,2553,2554,2555,2556,2557,2558,2559,2560,2561,2562,2563,2564,2565,2566,2567,2568,2569,2570,2571,2572,2573,2574,2575,2576,2577,2578,2579,2580,2581,2582,2583,2584,2585,2586,2587,2588,2589,2590,2591,2592,2593,2594,2595,2596,2597,2598,2599,2600,2601,2602,2603,2604,2605,2606,2607,2608,2609,2610,2611,2612,2613,2614,2615,2616,2617,2618,2619,2620,2621,2622,2623,2624,2625,2626,2627,2628,2629,2630,2631,2632,2633,2634,2635,2636,2637,2638,2639,2640,2641,2642,2643,2644,2645,2646,2647,2648,2649,2650,2651,2652,2653,2654,2655,2656,2657,2658,2659,2660,2661,2662,2663,2664,2665,2666,2667,2668,2669,2670,2671,2672,2673,2674,2675,2676,2677,2678,2679,2680,2681,2682,2683,2684,2685,2686,2687,2688,2689,2690,2691,2692,2693,2694,2695,2696,2697,2698,2699,2700,2701,2702,2703,2704,2705,2706,2707,2708,2709,2710,2711,2712,2713,2714,2715,2716,2717,2718,2719,2720,2721,2722,2723,2724,2725,2726,2727,2728,2729,2730,2731,2732,2733,2734,2735,2736,2737,2738,2739,2740,2741,2742,2753,2754,2755,2756,2757,2758,2759,2760,2761,2762,2763,2764,2765,2766,2767,2768,2769,2770,2771,2772,2773,2774,2775,2776,2777,2778,2779,2780,2781,2782,2783,2784,2785,2786,2787,2788,2789,2790,2791,2792,2793,2794,2795,2796,2797,2798,2799,2800,2801,2802,2803,2804,2805,2806,2807,2808,2809,2810,2811,2812,2813,2814,2815,2816,2817,2818,2819,2820,2821,2822,2823,2824,2825,2826,2827,2828,2829,2830,2831,2832,2833,2834,2835,2836,2837,2838,2839,2840,2841,2842,2843,2844,2845,2846,2847,2848,2849,2850,2851,2852,2853,2854,2855,2856,2857,2858,2859,2860,2861,2862,2863,2864,2865,2866,2867,2868,2869,2870,2871,2872,2873,2874,2875,2876,2877,2878,2879,2880,2881,2882,2883,2884,2885,2886,2887,2888,2889,2890,2891,2892,2893,2894,2895,2896,2897,2898,2899,2900,2901,2902,2903,2904,2905,2906,2907,2908,2909,2910,2911,2912,2913,2914,2915,2916,2917,2918,2919,2920,2921,2922,2923,2924,2925,2926,2927,2928,2929,2930,2931,2932,2933,2934,2935,2936,2937,2938,2939,2940,2941,2942,2943,2944,2945,2946,2947,2948,2949,2950,2951,2952,2953,2954,2955,2956,2957,2958,2959,2960,2961,2962,2963,2964,2965,2966,2967,2968,2969,2970,2971,2972,2973,2974,2975,2976,2977,2978,2979,2980,2981,2982,2983,2984,2985,2986,2987,2988,2989,2990,2991,2992,2993,2994,2995,2996,2997,2998,2999,3000,3001,3002,3003,3004,3005,3006,3007,3008,3009,3010,3011,3012,3013,3014,3015,3016,3017,3018,3019,3020,3022,3023,3024,3025,3026,3027,3028,3029,3030,3031,3032,3033,3034,3035,3036,3037,3038,3039,3040,3041,3042,3043,3044,3045,3046,3047,3048,3049,3050,3051,3052,3053,3054,3055,3056,3057,3058,3059,3060,3061,3062,3063,3064,3065,3066,3067,3068,3069,3072,3073,3074,3076,3077,3078,3079,3080,3081,3082,3083,3084,3085,3086,3087,3088,3089,3090,3091,3092,3093,3094,3095,3096,3097,3098,3099,3100,3101,3102,3103,3104,3105,3106,3107,3108,3109,3110,3111,3112,3113,3114,3115,3116,3117,3118,3119,3120,3121,3122,3123,3124,3125,3126,3127,3128,3129,3130,3131,3132,3133,3134,3135,3136,3137,3138,3139,3140,3141,3142,3143,3144,3145,3146,3147,3148,3149,3150,3151,3152,3153,3154,3155,3156,3157,3158,3159,3160,3161,3162,3163,3164,3165,3166,3167,3168,3169,3170,3171,3172,3173,3174,3175,3176,3177,3178,3179,3180,3181,3182,3183,3184,3185,3186,3187,3188,3189,3190,3191,3192,3193,3194,3195,3196,3197,3198,3199,3200,3201,3202,3203,3204,3205,3206,3207,3208,3209,3210,3211,3212,3213,3214,3215,3216,3217,3218,3219,3220,3221,3222,3223,3224,3225,3226,3227,3228,3229,3230,3231,3232,3233,3234,3235,3236,3237,3238,3239,3240,3241,3242,3243,3244,3245,3246,3247,3248,3249,3250,3251,3252,3253,3254,3255,3256,3257,3258,3259,3260,3261,3262,3263,3264,3265,3266,3267,3268,3269,3270,3271,3272,3273,3274,3275,3276,3277,3278,3279,3280,3281,3282,3283,3284,3285,3286,3287,3288,3289,3290,3291,3292,3293,3294,3295,3296,3297,3298,3299,3300,3301,3302,3303,3304,3305,3306,3307,3308,3309,3310,3311,3312,3313,3314,3315,3316,3317,3318,3319,3320,3321,3322,3323,3324,3325,3326,3327,3328,3329,3330,3331,3332,3333,3334,3335,3336,3337,3338,3339,3340,3341,3342,3343,3344,3345,3346,3347,3348,3349,3350,3351,3352,3353,3354,3355,3356,3357,3358,3359,3360,3361,3362,3363,3364,3365,3366,3367,3368,3369,3370,3371,3372,3373,3374,3375,3376,3377,3378,3379,3380,3381,3382,3384,3385,3386,3387,3388,3389,3390,3391,3392,3393,3394,3395,3396,3397,3398,3399,3400,3401,3402,3403,3404,3405,3406,3407,3408,3419,3421,3422,3423,3424,3427,3428,3429,3430,3431,3432,3433,3434,3435,3436,3437,3438,3439,3440,3441,3442,3443,3444,3445,3446,3447,3448,3449,3450,3451,3452,3453,3454,3455,3456,3457,3458,3459,3460,3461,3462,3463,3464,3465,3466,3467,3468,3469,3470,3471,3472,3473,3474,3475,3476,3477,3478,3479,3480,3481,3482,3483,3484,3485,3486,3487,3488,3489,3490,3491,3492,3493,3494,3495,3496,3497,3498,3499,3500,3501,3502,3503,3504,3505,3506,3507,3508,3509,3510,3511,3512,3513,3514,3515,3516,3517,3518,3519,3520,3521,3522,3523,3524,3525,3526,3527,3528,3529,3530,3531,3532,3533,3534,3535,3536,3537,3538,3539,3540,3541,3542,3543,3544,3545,3546,3547,3548,3549,3550,3551,3552,3553,3554,3555,3556,3557,3558,3559,3560,3561,3562,3563,3564,3565,3566,3567,3568,3569,3570,3571,3572,3573,3574,3575,3576,3577,3578,3579,3580,3581,3582,3583,3584,3585,3586,3587,3588,3590,3591,3592,3593,3594,3595,3596,3597,3598,3599,3600,3601,3602,3603,3604,3605,3606,3607,3608,3609,3610,3611,3612,3613,3614,3615,3616,3617,3618,3619,3620,3621,3622,3623,3624,3625,3626,3627,3628,3629,3630,3631,3632,3633,3634,3635,3636,3637,3638,3639,3640,3641,3642,3643,3644,3645,3646,3647,3648,3649,3650,3651,3652,3653,3654,3655,3656,3657,3658,3659,3660,3661,3662,3663,3664,3665,3666,3667,3668,3669,3670,3671,3672,3673,3674,3675,3676,3677,3678,3679,3680,3681,3682,3683,3684,3685,3686,3687,3688,3689,3690,3691,3692,3693,3694,3695,3696,3697,3698,3699,3700,3701,3702,3703,3704,3705,3706,3707,3708,3709,3710,3711,3712,3713,3714,3715,3716,3717,3718,3719,3720,3721,3722,3723,3724,3725,3726,3727,3728,3729,3730,3731,3732,3733,3734,3735,3736,3737,3738,3739,3740,3741,3742,3743,3744,3745,3746,3747,3748,3749,3750,3751,3752,3753,3754,3755,3756,3757,3758,3759,3760,3761,3762,3763,3764,3765,3766,3767,3768,3769,3770,3771,3772,3773,3774,3775,3776,3777,3778,3779,3780,3781,3782,3783,3784,3785,3786,3787,3788,3789,3790,3791,3792,3793,3794,3795,3796,3797,3798,3799,3800,3801,3802,3803,3804,3805,3806,3807,3808,3809,3810,3811,3813,3814,3815,3816,3817,3818,3819,3820,3821,3822,3823,3824,3825,3826,3827,3828,3829,3830,3831,3832,3833,3834,3835,3836,3837,3838,3839,3840,3841,3842,3843,3844,3845,3850,3851,3852,3853,3854,3855,3856,3857,3858,3859,3860,3861,3862,3863,3864,3865,3866,3867,3868,3869,3870,3871,3872,3873,3874,3875,3876,3877,3878,3879,3880,3881,3882,3883,3884,3885,3886,3887,3888,3889,3890,3891,3892,3893,3894,3895,3896,3897,3898,3899,3900,3901,3902,3903,3904,3905,3906,3907,3908,3909,3910,3911,3912,3913,3914,3915,3916,3917,3918,3919,3920,3921,3922,3923,3924,3925,3926,3927,3928,3929,3930,3931,3932,3933,3934,3935,3936,3937,3938,3939,3940,3941,3942,3943,3944,3945,3946,3947,3948,3949,3950,3951,3952,3953,3954,3955,3956,3957,3958,3959,3960,3961,3962,3963,3964,3965,3966,3967,3968,3969,3970,3971,3972,3973,3974,3975,3976,3977,3978,3979,3980,3981,3982,3983,3984,3985,3986,3987,3988,3989,3990,3991,3992,3993,3994,3995,3996,3997,3998,3999,4000,4001,4002,4003,4004,4005,4006,4007,4008,4010,4011,4012,4013,4014,4015,4016,4017,4018,4019,4020,4021,4022,4023,4024,4025,4026,4027,4028,4029,4030,4031,4032,4033,4034,4035,4036,4037,4038,4039,4040,4041,4042,4043,4044,4045,4046,4047,4048,4049,4050,4051,4052,4053,4054,4055,4056,4057,4058,4059,4060,4061,4062,4063,4064,4065,4066,4067,4068,4069,4070,4071,4072,4073,4074,4075,4076,4077,4078,4079,4080,4081,4082,4083,4084,4085,4086,4087,4088,4089,4090,4091,4092,4093,4094,4095,4096,4097,4098,4099,4100,4101,4102,4103,4104,4105,4106,4107,4108,4109,4110,4111,4112,4113,4114,4115,4116,4117,4118,4119,4120,4121,4122,4123,4124,4125,4126,4127,4128,4129,4130,4131,4132,4133,4134,4135,4136,4137,4138,4139,4140,4141,4142,4143,4144,4145,4146,4147,4148,4149,4150,4151,4152,4153,4154,4155,4156,4157,4158,4159,4160,4161,4162,4163,4164,4165,4166,4167,4168,4169,4170,4171,4172,4173,4174,4175,4176,4177,4178,4179,4180,4181,4182,4183,4184,4185,4186,4187,4188,4189,4190,4191,4192,4193,4194,4195,4196,4197,4198,4199,4200,4201,4202,4203,4204,4205,4206,4207,4208,4209,4210,4211,4212,4213,4214,4215,4216,4217,4218,4219,4220,4221,4222,4223,4224,4225,4226,4227,4228,4229,4230,4231,4232,4233,4234,4235,4236,4237,4238,4239,4240,4241,4242,4243,4244,4245,4246,4247,4248,4249,4250,4251,4252,4253,4254,4255,4256,4257,4258,4259,4260,4261,4262,4263,4264,4265,4266,4267,4268,4269,4270,4271,4272,4273,4274,4275,4277,4278,4279,4280,4281,4282,4283,4284,4285,4286,4287,4288,4289,4290,4291,4292,4293,4294,4295,4296,4297,4298,4299,4300,4301,4302,4303,4304,4305,4306,4307,4308,4309,4310,4311,4312,4313,4314,4315,4316,4317,4318,4319,4320,4321,4322,4323,4324,4325,4326,4327,4328,4329,4330,4331,4332,4333,4334,4335,4336,4337,4338,4339,4340,4341,4342,4343,4344,4345,4346,4347,4348,4349,4350,4351,4352,4353,4354,4355,4356,4357,4358,4359,4360,4361,4362,4363,4364,4365,4366,4367,4368,4369,4370,4371,4372,4373,4374,4375,4376,4377,4378,4379,4380,4381,4382,4383,4384,4385,4386,4387,4388,4389,4390,4391,4392,4393,4394,4395,4396,4397,4398,4399,4400,4401,4402,4403,4404,4405,4406,4407,4408,4409,4410,4411,4412,4413,4414,4415,4416,4417,4418,4419,4420,4421,4422,4423,4424,4425,4426,4427,4428,4429,4430,4431,4432,4433,4434,4435,4436,4437,4438,4439,4440,4441,4442,4443,4444,4445,4446,4447,4448,4449,4450,4451,4452,4453,4454,4455,4456,4457,4458,4459,4460,4461,4462,4463,4464,4465,4466,4467,4468,4469,4470,4471,4472,4473,4474,4475,4476,4477,4478,4479,4480,4481,4482,4483,4484,4485,4486,4487,4488,4489,4490,4491,4492,4493,4494,4495,4496,4497,4498,4499,4500,4501,4502,4503,4504,4505,4506,4507,4508,4509,4510,4511,4512,4513,4514,4515,4516,4517,4518,4519,4520,4521,4522,4523,4524,4525,4526,4527,4528,4529,4530,4531,4532,4533,4534,4535,4536,4537,4538,4539,4540,4541,4542,4543,4544,4545,4546,4547,4548,4549,4550,4551,4552,4553,4554,4555,4556,4557,4558,4559,4560,4561,4562,4563,4564,4565,4566,4567,4568,4569,4570,4571,4572,4573,4574,4575,4576,4577,4578,4579,4580,4581,4582,4583,4584,4585,4586,4587,4588,4589,4590,4591,4592,4593,4594,4595,4596,4597,4598,4599,4600,4601,4602,4603,4604,4605,4606,4607,4608,4609,4610,4611,4612,4613,4614,4615,4616,4617,4618,4619', '1', '', '0', '1', 'area', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2', '1', '北京', '0,1', '2,500,501,502,503,504,505,506,507,508,509,510,511,512,513,514,515,516,517,3422', '1', '', '0', '1', '', '', '', '', '1', 'B', '0', '0,45,50,51,52,63,64,54,1,47,48,49,55,56,57,2,58,59,60,61,62');
INSERT INTO `zz_linkage` VALUES ('3', '1', '安徽', '0,1', '3,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,398,399,400,401,402,404,405,406,407,408,409,410,411,412,413,414,415,416,417,418,419,420,421,422,423,424,425,426,427,428,429,430,431,432,437,438,439,440,441,442,443,444,445,446,447,448,449,450,451,452,453,454,455,456,457,458,459,460,461,462,463,465,466,467,468,469,470,471,472,473,474,475,476,477,478,479,480,481,482,483,484,485,486,487,488,489,490,491,492,493,494,495,496,497,498,499,3401,3402,3403,3404,3405,3406,3407,3408,3440,3441,3442,3443,3444,3445,3446,3447,3448,3449,3450,3451,3452,3453,3454,3455,3456,3457,3458,3459,3460,3461,3462,3463,3464,3465', '1', '', '0', '1', '', '', '', '', '1', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('4', '1', '福建', '0,1', '4,53,54,55,56,57,58,59,60,61,518,519,520,521,522,523,524,525,526,527,528,529,530,531,532,533,534,535,536,537,538,539,540,541,542,543,544,545,546,547,548,549,550,551,552,553,554,555,556,557,558,559,560,561,562,563,564,565,566,567,568,569,570,571,572,573,574,575,576,577,578,579,580,581,582,583,584,585,586,587,588,589,590,591,592,593,594,595,596,597,598,599,600,601,602,603,3419,3421,3466,3467,3468,3469,3470,3471,3472,3473,3474', '1', '', '0', '1', '', '[{\"path\":\"\\/upload\\/60\\/images\\/gallery\\/t\\/3\\/1048_src.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"}]', '', '[{\"path\":\"\\/upload\\/60\\/images\\/gallery\\/t\\/a\\/1055_src.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"},{\"path\":\"\\/upload\\/60\\/images\\/gallery\\/t\\/0\\/1045_src.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"},{\"path\":\"\\/upload\\/60\\/images\\/gallery\\/t\\/1\\/1046_src.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"},{\"path\":\"\\/upload\\/60\\/images\\/gallery\\/s\\/m\\/1031_src.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"}]', '1', 'F', '0', '0,45,1,47,67,68,69,70,71,48,72,73,74,75,76,77,49,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,55,94,95,96,97,98,56,99,100,101,2,102,106,107,108,109,110,103,111,112,113,114,115,116,117,118,119,120,104,121,122,123,124,125,105,3,33,43,44,290,291,301,302,303,304,305,306,307,308,309,310,311,312,313,314,315,316,317,318,319,292,320,321,322,323,324,325,326,327,293,328,329,330,331,332,333,334,335,336,337,338,339,294,340,341,342,343,344,345,346,347,348,349,350,351,295,296,352,353,354,355,356,357,358,359,360,361,362,363,364,365,366,367,297,368,369,370,371,372,373,374,375,376,377,378,379,380,381,382,383,384,385,386,387,298,299,401,402,403,404,405,406,407,408,409,300,388,389,390,391,392,393,394,395,396,397,398,399,400,410');
INSERT INTO `zz_linkage` VALUES ('5', '1', '甘肃', '0,1', '5,62,63,64,65,66,67,68,69,70,71,72,73,74,75,604,605,606,607,608,609,610,611,612,613,614,615,616,617,618,619,620,621,622,623,625,626,627,628,629,630,631,632,633,634,635,636,637,638,639,640,641,642,643,644,645,646,647,648,649,650,651,652,653,654,655,656,657,658,659,660,661,662,663,664,665,666,667,668,669,670,671,672,673,674,675,676,677,678,679,680,681,682,683,684,685,686,687,688,689,690,691,3475,3476,3477,3478,3479,3480,3481,3482,3483,3484,3485,3486,3487,4021,4022,4023,4024,4025', '1', '', '0', '1', '', '', '', '', '1', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('6', '1', '广东', '0,1', '6,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,692,693,694,695,696,697,698,699,700,701,702,703,704,705,706,707,708,709,710,711,712,713,714,715,716,717,718,719,720,721,722,723,724,725,726,727,728,729,730,731,732,733,734,735,736,737,738,739,740,741,742,743,744,745,746,747,748,749,750,751,752,753,754,755,756,757,758,759,760,761,762,763,764,765,766,767,768,769,770,771,772,773,774,775,776,777,778,779,780,781,782,783,784,785,786,787,788,789,790,791,792,793,794,795,796,797,798,799,800,801,802,803,804,805,806,807,808,809,810,811,812,813,814,815,816,817,818,819,820,821,822,823,824,825,826,827,828,829,830,831,832,833,834,835,836,837,838,839,840,841,842,843,844,845,846,847,848,849,850,851,852,3488,3489,3490,3491,3492,3493,3494,3495,3496,3497,3498,3499,3500,3501,3502,3503,3504,3505,3506,3507,3508,3509,3510,3511,4020,4026,4027,4028,4029,4030,4031,4032,4033,4034,4035,4036,4037,4038,4039,4040,4041,4042,4043,4044,4045,4046', '1', '', '0', '1', '', '', '', '', '1', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('7', '1', '广西壮族自治区', '0,1', '7,97,98,99,100,101,102,103,104,105,106,107,108,109,110,853,854,855,856,857,858,859,860,861,862,863,864,865,866,867,868,869,870,871,872,873,874,875,876,877,878,879,880,881,882,883,884,885,886,887,888,889,890,891,892,893,894,895,896,897,898,899,900,901,902,903,904,905,906,907,908,909,910,911,912,913,914,915,916,917,918,919,920,921,922,923,924,925,926,927,928,929,930,931,932,933,934,935,936,937,938,939,940,941,942,943,944,945,946,947,948,949,950,951,952,953,954,955,956,957,958,959,960,961,3512,3513,3514,3515,3516,3517,3518,3519,3520,3521,3522,3523,3524,3525,3526,3527', '1', '', '0', '1', '', '', '', '', '1', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('8', '1', '贵州', '0,1', '8,111,112,113,114,115,116,117,118,119,962,963,964,965,966,967,968,969,970,971,972,973,974,975,976,977,978,979,980,981,982,983,984,985,986,987,988,989,990,991,992,993,994,995,996,997,998,999,1000,1001,1002,1003,1004,1005,1006,1007,1008,1009,1010,1011,1012,1013,1014,1015,1016,1017,1018,1019,1020,1021,1022,1023,1024,1025,1026,1027,1028,1029,1030,1031,1032,1033,1034,1035,1036,1037,1038,1039,1040,1041,1042,1043,1044,1045,1046,1047,1048,1050,1051,1052,1053,3528,3529,3530,3531,3532,3533,3534,3535,3536,3537,3538', '1', '', '0', '1', '', '', '', '', '1', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('9', '1', '海南', '0,1', '9,120,121,122,123,124,125,126,127,128,129,130,131,132,133,134,135,136,137,1054,1055,1056,1057,1058,1059,1060,1061,1062,1063,1064,1065,1066,1067,1068,1069,1070,1071,1072,1073,1074,1075,1076,1077,3539,3540,3541,3542,3543,4047,4048,4049,4050,4051,4052,4053,4054,4055,4056,4057,4058,4059,4060,4061,4062,4063,4064,4065,4066,4067,4068,4069,4070,4071,4072,4073,4074,4075,4076,4077,4078,4079,4080,4081,4082,4083,4084,4085,4086,4087,4088,4089,4090,4091,4092,4093,4094,4095,4096,4097,4098,4099,4100,4101,4102,4103,4104,4105,4106,4107,4108,4109,4110,4111,4112,4113,4114,4115,4116,4117,4118,4119,4120,4121,4122,4123,4124,4125,4126,4127,4128,4129,4130,4131,4132,4133,4134,4135,4136,4137,4138,4139,4140,4141,4142,4143,4144,4145,4146,4147,4148,4149,4150,4151,4152,4153,4154,4155,4156,4157,4158,4159,4160,4161,4162,4163,4164,4165,4166,4167,4168,4169,4170,4171,4172,4173,4174,4175,4176,4177,4178,4179,4180,4181,4182,4183,4184,4185,4186,4187,4188,4189,4190,4191,4192,4193,4194,4195,4196,4197,4198,4199,4200,4201,4202,4203,4204,4205,4206,4207,4208,4209,4210,4211,4212,4213,4214,4215,4216,4217,4218,4219,4220,4221,4222,4223,4224,4225,4226,4227,4228,4229,4230,4231,4232,4233,4234,4235,4236,4237,4238,4239,4240,4241,4242,4243,4244,4245,4246,4247,4248,4249,4250,4251,4252,4253,4254,4255,4256,4257,4258,4259,4260,4261,4262,4263,4264,4265', '1', '', '0', '1', '', '', '', '', '1', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('10', '1', '河北', '0,1', '10,138,139,140,141,142,143,144,145,146,147,148,1078,1079,1080,1081,1082,1083,1084,1085,1086,1087,1088,1089,1090,1091,1092,1093,1094,1095,1096,1097,1098,1099,1100,1101,1102,1103,1104,1105,1106,1107,1108,1109,1110,1111,1112,1113,1114,1115,1116,1117,1118,1119,1120,1121,1122,1123,1124,1125,1126,1127,1128,1129,1130,1131,1132,1133,1134,1135,1136,1137,1138,1139,1140,1141,1142,1143,1144,1145,1146,1147,1148,1149,1150,1151,1152,1153,1154,1155,1156,1157,1158,1159,1160,1161,1162,1163,1164,1165,1166,1167,1168,1169,1170,1171,1172,1173,1174,1175,1176,1177,1178,1179,1180,1181,1182,1183,1184,1185,1186,1187,1188,1189,1190,1191,1192,1193,1194,1195,1196,1197,1198,1199,1201,1202,1203,1204,1205,1206,1207,1208,1209,1210,1211,1212,1213,1214,1215,1216,1217,1218,1219,1220,1221,1222,1223,1224,1225,1226,1227,1228,1229,1230,1231,1232,1233,1234,1235,1236,1237,1238,1239,1240,1241,1242,1243,1244,1245,1246,1247,1248,1249,1250,3427,3428,3429,3430,3431,3432,3433,3434,3435,3436,3437,3438,3439', '1', '', '0', '1', '', '', '', '', '1', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('11', '1', '河南', '0,1', '11,149,150,151,152,153,154,155,156,157,158,159,160,161,162,163,164,165,166,1251,1252,1253,1254,1255,1256,1257,1258,1259,1260,1261,1262,1263,1264,1265,1266,1267,1268,1269,1270,1271,1272,1273,1274,1275,1276,1277,1278,1279,1280,1281,1282,1283,1284,1285,1286,1287,1288,1289,1290,1291,1292,1293,1294,1295,1296,1297,1298,1299,1300,1301,1302,1303,1304,1305,1306,1307,1308,1309,1310,1311,1312,1313,1314,1315,1316,1317,1318,1319,1320,1321,1322,1323,1324,1325,1326,1327,1328,1329,1330,1331,1332,1333,1334,1335,1336,1337,1338,1339,1340,1341,1342,1343,1344,1345,1346,1347,1348,1349,1350,1351,1352,1353,1354,1355,1356,1357,1358,1359,1360,1361,1362,1363,1364,1365,1366,1367,1368,1369,1370,1371,1372,1373,1374,1375,1376,1377,1378,1379,1380,1381,1382,1383,1384,1385,1386,1387,1388,1389,1390,1391,1392,1393,1394,1395,1396,1397,1398,1399,1400,1401,1402,1403,1404,1405,1406,1407,1408,1409,1410,1411,1412,1413,1414,3544,3545,3546,3547,3548,3549,3550,3551,3552,3553,3554,3555,3556,3557,3558,3559,3560,3561,3562,4266,4267,4268,4269,4270,4271,4272,4273,4274,4275', '1', '', '0', '1', '', '', '', '', '1', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('12', '1', '黑龙江', '0,1', '12,167,168,169,170,171,172,173,174,175,176,177,178,179,1415,1416,1417,1418,1419,1420,1421,1422,1423,1424,1425,1426,1427,1428,1429,1430,1431,1432,1433,1434,1435,1436,1437,1438,1439,1440,1441,1442,1443,1444,1445,1446,1447,1448,1449,1450,1451,1452,1453,1454,1455,1456,1457,1458,1459,1460,1461,1462,1463,1464,1465,1466,1467,1468,1469,1470,1471,1472,1473,1474,1475,1476,1477,1478,1479,1480,1481,1482,1483,1484,1485,1486,1487,1488,1489,1490,1491,1492,1493,1494,1495,1496,1497,1498,1499,1500,1501,1502,1503,1504,1505,1506,1507,1508,1509,1510,1511,1512,1513,1514,1515,1516,1517,1518,1519,1520,1521,1522,1523,1524,1525,1526,1527,1528,1529,1530,1531,1532,1533,1534,1535,1536,1537,1538,1539,1540,1541,1542,1543,3563,3564,3565,3566,3567,3568,3569,3570,3571,3572,3573,3574,3575,3576,3577,3578', '1', '', '0', '1', '', '', '', '', '1', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('13', '1', '湖北', '0,1', '13,180,181,182,183,184,185,186,187,188,189,190,191,192,193,194,195,196,1544,1545,1546,1547,1548,1549,1550,1551,1552,1553,1554,1555,1556,1557,1559,1560,1561,1562,1563,1564,1565,1566,1567,1568,1569,1570,1571,1572,1573,1574,1575,1576,1577,1578,1579,1580,1581,1582,1583,1584,1585,1586,1587,1588,1589,1590,1593,1594,1595,1596,1597,1598,1599,1600,1601,1602,1604,1605,1606,1607,1608,1609,1610,1611,1612,1613,1614,1615,1616,1617,1618,1619,1620,1621,1622,1623,1624,1625,1626,1627,1628,1629,1630,1631,1632,1633,1634,1635,1636,1637,1638,1639,1640,1641,1642,1643,1644,1645,1646,3579,3580,3581,3582,3583,3584,3585,3586,3587,3588,3590,3591,3592,3593,3594,3595,4277,4278,4279,4280,4281,4282,4283,4284,4285,4286,4287,4288,4289,4290,4291,4292,4293,4294,4295,4296,4297,4298,4299,4300,4301,4302,4303,4304,4305,4306,4307,4308,4309,4310,4311,4312,4313,4314,4315,4316,4317,4318,4319,4320,4321,4322,4323,4324,4325,4326,4327,4328,4329,4330,4331,4332,4333,4334,4335,4336,4337,4338,4339,4340,4341,4342,4343,4344,4345,4346,4347,4348,4349,4350,4351,4352,4353,4354,4355,4356,4357,4358,4359,4360,4361,4362', '1', '', '0', '1', '', '', '', '', '1', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('14', '1', '湖南', '0,1', '14,197,198,199,200,201,202,203,204,205,206,207,208,209,210,1647,1648,1649,1650,1651,1652,1653,1654,1655,1656,1657,1658,1659,1660,1661,1662,1663,1664,1665,1666,1667,1668,1669,1670,1671,1672,1673,1674,1675,1676,1677,1678,1679,1680,1681,1682,1683,1684,1685,1686,1687,1688,1689,1690,1691,1692,1693,1694,1695,1696,1697,1698,1699,1700,1701,1702,1703,1704,1705,1706,1707,1708,1709,1710,1711,1712,1713,1714,1715,1716,1717,1718,1719,1720,1721,1722,1723,1724,1725,1726,1727,1728,1729,1730,1731,1732,1733,1734,1735,1736,1737,1738,1739,1740,1741,1742,1743,1744,1745,1746,1747,1748,1749,1750,1751,1752,1753,1754,1755,1756,1757,1758,1759,1760,1761,1762,1763,1764,1765,1766,1767,1768,1769,3596,3597,3598,3599,3600,3601,3602,3603,3604,3605,3606,3607,3608,3609', '1', '', '0', '1', '', '', '', '', '1', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('15', '1', '吉林', '0,1', '15,211,212,213,214,215,216,217,218,219,1770,1771,1772,1773,1774,1775,1776,1777,1778,1779,1780,1781,1782,1783,1784,1785,1786,1787,1788,1789,1790,1791,1792,1793,1794,1795,1796,1797,1798,1799,1800,1801,1802,1803,1804,1805,1806,1807,1808,1809,1810,1811,1812,1813,1814,1815,1816,1817,1818,1819,1820,1821,1822,1823,1824,1825,1826,1827,1828,1829,1830,1831,1832,1833,3610,3611,3612,3613,3614,3615,3616,3617,3618,3619', '1', '', '0', '1', '', '', '', '', '1', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('16', '1', '江苏', '0,1', '16,220,221,222,223,224,225,226,227,228,229,230,231,232,1834,1835,1836,1837,1838,1839,1840,1841,1842,1843,1844,1845,1846,1847,1848,1849,1850,1851,1852,1853,1854,1855,1856,1857,1858,1859,1860,1861,1862,1863,1864,1865,1866,1867,1868,1869,1870,1871,1872,1873,1874,1875,1876,1877,1878,1879,1880,1881,1882,1883,1884,1885,1886,1887,1888,1889,1890,1891,1892,1893,1894,1895,1896,1897,1898,1899,1900,1901,1902,1903,1904,1905,1906,1907,1908,1909,1910,1911,1912,1913,1914,1915,1916,1917,1918,1919,1920,1921,1922,1923,1924,1925,1926,1927,1928,1929,1930,1931,1932,1933,1934,1935,1936,1937,1938,1939,1940,1941,1942,1943,1944,1945,1946,1947,1948,1949,1950,1951,1952,1953,1954,1955,1956,1957,3620,3621,3622,3623,3624,3625,3626,3627,3628,3629,3630,3631,3632,3633,3634,3635,3636,3637,3638', '1', '', '0', '1', '', '', '', '', '1', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('17', '1', '江西', '0,1', '17,233,234,235,236,237,238,239,240,241,242,243,1958,1959,1960,1961,1962,1963,1964,1965,1966,1967,1968,1969,1970,1971,1972,1973,1974,1975,1976,1977,1978,1979,1980,1981,1982,1983,1984,1985,1986,1987,1988,1989,1990,1991,1992,1993,1994,1995,1996,1997,1998,1999,2000,2001,2002,2003,2004,2005,2006,2007,2008,2009,2010,2011,2012,2013,2014,2015,2016,2017,2018,2019,2020,2021,2022,2023,2024,2025,2026,2027,2028,2029,2030,2031,2032,2033,2034,2035,2036,2037,2038,2039,2040,2041,2042,2043,2044,2045,2046,2047,2048,2049,2050,2051,2052,2053,2054,2055,2056,2057,2058,2059,3639,3640,3641,3642,3643,3644,3645,3646,3647,3648,3649,3650', '1', '', '0', '1', '', '', '', '', '1', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('18', '1', '辽宁', '0,1', '18,244,245,246,247,248,249,250,251,252,253,254,255,256,257,2060,2061,2062,2063,2064,2065,2066,2067,2068,2069,2070,2071,2072,2073,2074,2075,2076,2077,2078,2079,2080,2081,2082,2083,2084,2085,2086,2087,2088,2089,2090,2091,2092,2093,2094,2095,2096,2097,2098,2099,2100,2101,2102,2103,2104,2105,2106,2107,2108,2109,2110,2111,2112,2113,2114,2115,2116,2117,2118,2119,2120,2121,2122,2123,2124,2125,2126,2127,2128,2129,2130,2131,2132,2133,2134,2135,2136,2137,2138,2139,2140,2141,2142,2143,2144,2145,2146,2147,2148,2149,2150,2151,2152,2153,2154,2155,2156,2157,2158,2159,2160,2161,3651,3652,3653,3654,3655,3656,3657,3658,3659,3660,3661,3662,3663,3664,3665', '1', '', '0', '1', '', '', '', '', '1', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('19', '1', '内蒙古自治区', '0,1', '19,258,259,260,261,262,263,264,265,266,267,268,269,2162,2163,2164,2165,2166,2167,2168,2169,2170,2171,2172,2173,2174,2175,2176,2177,2178,2179,2180,2181,2182,2183,2184,2185,2186,2187,2188,2189,2190,2191,2192,2193,2194,2195,2196,2197,2198,2199,2200,2201,2202,2203,2204,2205,2206,2207,2208,2209,2210,2211,2212,2213,2214,2215,2216,2217,2218,2219,2220,2221,2222,2223,2224,2225,2226,2227,2228,2229,2230,2231,2232,2233,2234,2235,2236,2237,2238,2239,2240,2241,2242,2243,2244,2245,2246,2247,2248,2249,2250,2251,2252,2253,2254,2255,2256,2257,2258,2259,2260,2261,2262,3666,3667,3668,3669,3670,3671,3672,3673,3674,3675,3676,3677,3678', '1', '', '0', '1', '', '', '', '', '1', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('20', '1', '宁夏回族自治区', '0,1', '20', '1', '', '0', '1', '', '', '', '', '1', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('21', '1', '青海', '0,1', '21,275,276,277,278,279,280,281,282,2289,2290,2291,2292,2293,2294,2295,2296,2297,2298,2299,2300,2301,2302,2303,2304,2305,2306,2307,2308,2309,2310,2311,2312,2313,2314,2315,2316,2317,2318,2319,2320,2321,2322,2323,2324,2325,2326,2327,2328,2329,2330,2331,3685,3686,3687,3688,3689,3690,3691,3692', '1', '', '0', '1', '', '', '', '', '1', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('22', '1', '山东', '0,1', '22,283,284,285,286,287,288,289,290,291,292,293,294,295,296,297,298,299,2332,2333,2334,2335,2336,2337,2338,2339,2340,2341,2342,2343,2344,2345,2346,2347,2348,2349,2350,2351,2352,2353,2354,2355,2356,2357,2358,2359,2360,2361,2362,2363,2364,2365,2366,2367,2368,2369,2370,2371,2372,2373,2374,2375,2376,2377,2378,2379,2380,2381,2382,2383,2384,2385,2386,2387,2388,2389,2390,2391,2392,2393,2394,2395,2396,2397,2398,2399,2400,2401,2402,2403,2404,2405,2406,2407,2408,2409,2410,2411,2412,2413,2414,2415,2416,2417,2418,2419,2420,2421,2422,2423,2424,2425,2426,2427,2428,2429,2430,2431,2432,2433,2434,2435,2436,2437,2438,2439,2440,2441,2442,2443,2444,2445,2446,2447,2448,2449,2450,2451,2452,2453,2454,2455,2456,2457,2458,2459,2460,2461,2462,2463,2464,2465,2466,2467,2468,2469,2470,2471,2472,3693,3694,3695,3696,3697,3698,3699,3700,3701,3702,3703,3704,3705,3706,3707,3708,3709,3710,3711', '1', '', '0', '1', '', '', '', '', '1', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('23', '1', '山西', '0,1', '23,300,301,302,303,304,305,306,307,308,309,310,2473,2474,2475,2476,2477,2478,2479,2480,2481,2482,2483,2484,2485,2486,2487,2488,2489,2490,2491,2492,2493,2494,2495,2496,2497,2498,2499,2500,2501,2502,2503,2504,2505,2506,2507,2508,2509,2510,2511,2512,2513,2514,2515,2516,2517,2518,2519,2520,2521,2522,2523,2524,2525,2526,2527,2528,2529,2530,2531,2532,2533,2534,2535,2536,2537,2538,2539,2540,2541,2542,2543,2544,2545,2546,2547,2548,2549,2550,2551,2552,2553,2554,2555,2556,2557,2558,2559,2560,2561,2562,2563,2564,2565,2566,2567,2568,2569,2570,2571,2572,2573,2574,2575,2576,2577,2578,2579,2580,2581,2582,2583,2584,2585,2586,2587,2588,2589,2590,2591,2592,2593,2594,2595,3712,3713,3714,3715,3716,3717,3718,3719,3720,3721,3722', '1', '', '0', '1', '', '', '', '', '1', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('24', '1', '陕西', '0,1', '24,311,312,313,314,315,316,317,318,319,320,2596,2597,2598,2599,2600,2601,2602,2603,2604,2605,2606,2607,2608,2609,2610,2611,2612,2613,2614,2615,2616,2617,2618,2619,2620,2621,2622,2623,2624,2625,2626,2627,2628,2629,2630,2631,2632,2633,2634,2635,2636,2637,2638,2639,2640,2641,2642,2643,2644,2645,2646,2647,2648,2649,2650,2651,2652,2653,2654,2655,2656,2657,2658,2659,2660,2661,2662,2663,2664,2665,2666,2667,2668,2669,2670,2671,2672,2673,2674,2675,2676,2677,2678,2679,2680,2681,2682,2683,2684,2685,2686,2687,2688,2689,2690,2691,2692,2693,2694,2695,2696,2697,2698,2699,2700,2701,2702,3723,3724,3725,3726,3727,3728,3729,3730,3731,3732', '1', '', '0', '1', '', '', '', '', '1', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('25', '1', '上海', '0,1', '25,2703,2704,2705,2706,2707,2708,2709,2710,2711,2712,2713,2714,2715,2716,2717,2718,2719,2720,2721,3733', '1', '', '0', '1', '', '', '', '', '1', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('26', '1', '四川', '0,1', '26,322,323,324,325,326,327,328,329,330,331,332,333,334,335,336,337,338,339,340,341,342,2722,2723,2724,2725,2726,2727,2728,2729,2730,2731,2732,2733,2734,2735,2736,2737,2738,2739,2740,2741,2742,2753,2754,2755,2756,2757,2758,2759,2760,2761,2762,2763,2764,2765,2766,2767,2768,2769,2770,2771,2772,2773,2774,2775,2776,2777,2778,2779,2780,2781,2782,2783,2784,2785,2786,2787,2788,2789,2790,2791,2792,2793,2794,2795,2796,2797,2798,2799,2800,2801,2802,2803,2804,2805,2806,2807,2808,2809,2810,2811,2812,2813,2814,2815,2816,2817,2818,2819,2820,2821,2822,2823,2824,2825,2826,2827,2828,2829,2830,2831,2832,2833,2834,2835,2836,2837,2838,2839,2840,2841,2842,2843,2844,2845,2846,2847,2848,2849,2850,2851,2852,2853,2854,2855,2856,2857,2858,2859,2860,2861,2862,2863,2864,2865,2866,2867,2868,2869,2870,2871,2872,2873,2874,2875,2876,2877,2878,2879,2880,2881,2882,2883,2884,2885,2886,2887,2888,2889,2890,2891,2892,2893,2894,2895,2896,2897,2898,2899,2900,2901,2902,2903,2904,2905,2906,2907,2908,2909,2910,2911,3734,3735,3736,3737,3738,3739,3740,3741,3742,3743,3744,3745,3746,3747,3748,3749,3750,3751,3752,3753,3754,3755,3756,3757,3758,3759,3760,3761,3762', '1', '', '0', '1', '', '', '', '', '1', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('27', '1', '天津', '0,1', '27,2912,2913,2914,2915,2916,2917,2918,2919,2920,2921,2922,2923,2924,2925,2926,2927,2928,2929,2930,3423,3424', '1', '', '0', '1', '', '', '', '', '1', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('28', '1', '西藏自治区', '0,1', '28', '1', '', '0', '1', '', '', '', '', '1', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('29', '1', '新疆维吾尔族自治区', '0,1', '29,351,352,353,354,355,356,357,358,359,360,361,362,363,364,365,366,3004,3005,3006,3007,3008,3009,3010,3011,3012,3013,3014,3015,3016,3017,3018,3019,3020,3022,3023,3024,3025,3026,3027,3028,3029,3030,3031,3032,3033,3034,3035,3036,3037,3038,3039,3040,3041,3042,3043,3044,3045,3046,3047,3048,3049,3050,3051,3052,3053,3054,3055,3056,3057,3058,3059,3060,3061,3062,3063,3064,3065,3066,3067,3068,3069,3072,3073,3074,3076,3077,3078,3079,3080,3081,3082,3083,3084,3085,3086,3087,3088,3089,3090,3091,3092,3093,3094,3095,3096,3097,3098,3099,3774,3775,3776,3777,3778,3779,3780,3781,3782,3783,3784,3785,3786,3787,3788,3789,3790,3791,3792,3793,3794,3795,3796,3797,3798,3799,3800,3801,3802,3803,3804,3805,3806,3807,3808,3809,3810,3811,4363,4364,4365,4366,4367,4368,4369,4370,4371,4372,4373,4374,4375,4376,4377,4378,4379,4380,4381,4382,4383,4384,4385,4386,4387,4388,4389,4390,4391,4392,4393,4394,4395,4396,4397,4398,4399,4400,4401,4402,4403,4404,4405', '1', '', '0', '1', '', '', '', '', '1', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('30', '1', '云南', '0,1', '30,367,368,369,370,371,372,373,374,375,376,377,378,379,380,381,382,3100,3101,3102,3103,3104,3105,3106,3107,3108,3109,3110,3111,3112,3113,3114,3115,3116,3117,3118,3119,3120,3121,3122,3123,3124,3125,3126,3127,3128,3129,3130,3131,3132,3133,3134,3135,3136,3137,3138,3139,3140,3141,3142,3143,3144,3145,3146,3147,3148,3149,3150,3151,3152,3153,3154,3155,3156,3157,3158,3159,3160,3161,3162,3163,3164,3165,3166,3167,3168,3169,3170,3171,3172,3173,3174,3175,3176,3177,3178,3179,3180,3181,3182,3183,3184,3185,3186,3187,3188,3189,3190,3191,3192,3193,3194,3195,3196,3197,3198,3199,3200,3201,3202,3203,3204,3205,3206,3207,3208,3209,3210,3211,3212,3213,3214,3215,3216,3217,3218,3219,3220,3221,3222,3223,3224,3225,3226,3227,3228,3813,3814,3815,3816,3817,3818,3819,3820,3821,3822,3823,3824,3825,3826,3827,3828,3829', '1', '', '0', '1', '', '', '', '', '1', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('31', '1', '浙江', '0,1', '31,383,384,385,386,387,388,389,390,391,392,393,3229,3230,3231,3232,3233,3234,3235,3236,3237,3238,3239,3240,3241,3242,3243,3244,3245,3246,3247,3248,3249,3250,3251,3252,3253,3254,3255,3256,3257,3258,3259,3260,3261,3262,3263,3264,3265,3266,3267,3268,3269,3270,3271,3272,3273,3274,3275,3276,3277,3278,3279,3280,3281,3282,3283,3284,3285,3286,3287,3288,3289,3290,3291,3292,3293,3294,3295,3296,3297,3298,3299,3300,3301,3302,3303,3304,3305,3306,3307,3308,3309,3310,3311,3312,3313,3314,3315,3316,3317,3318,3319,3320,3321,3322,3323,3324,3830,3831,3832,3833,3834,3835,3836,3837,3838,3839,3840,3841,3842,3843', '1', '', '0', '1', '', '', '', '', '1', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('32', '1', '重庆', '0,1', '32,3325,3326,3327,3328,3329,3330,3331,3332,3333,3334,3335,3336,3337,3338,3339,3340,3341,3342,3343,3344,3345,3346,3347,3348,3349,3350,3351,3352,3353,3354,3355,3356,3357,3358,3359,3360,3361,3362,3363,3364,3844', '1', '', '0', '1', '', '', '', '', '1', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('33', '1', '香港特别行政区', '0,1', '33,3365,3366,3367,3368,3369,3370,3371,3372,3373,3374,3375,3376,3377,3378,3379,3380,3381,3382,3845,4406', '1', '', '0', '1', '', '', '', '', '1', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('34', '1', '澳门特别行政区', '0,1', '34', '1', '', '0', '1', '', '', '', '', '1', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('35', '1', '台湾', '0,1', '35,3384,3385,3386,3387,3388,3389,3390,3391,3392,3393,3394,3395,3396,3397,3398,3399,3400,3852,3853,3854,3855,3856,3857,3858,3859,3860,3861,3862,3863,3864,3865,3866,3867,3868,3869,3870,3871,3872,3873,3874,3875,3876,3877,3878,3879,3880,3881,3882,3883,3884,3885,3886,3887,3888,3889,3890,3891,3892,3893,3894,3895,3896,3897,3898,3899,3900,3901,3902,3903,3904,3905,3906,3907,3908,3909,3910,3911,3912,3913,3914,3915,3916,3917,3918,3919,3920,3921,3922,3923,3924,3925,3926,3927,3928,3929,3930,3931,3932,3933,3934,3935,3936,3937,3938,3939,3940,3941,3942,3943,3944,3945,3946,3947,3948,3949,3950,3951,3952,3953,3954,3955,3956,3957,3958,3959,3960,3961,3962,3963,3964,3965,3966,3967,3968,3969,3970,3971,3972,3973,3974,3975,3976,3977,3978,3979,3980,3981,3982,3983,3984,3985,3986,3987,3988,3989,3990,3991,3992,3993,3994,3995,3996,3997,3998,3999,4000,4001,4002,4003,4004,4005,4006,4007,4008,4010,4011,4012,4013,4014,4015,4016,4017,4018,4019,4407,4408,4409,4410,4411,4412,4413,4414,4415,4416,4417,4418,4419,4420,4421,4422,4423,4424,4425,4426,4427,4428,4429,4430,4431,4432,4433,4434,4435,4436,4437,4438,4439,4440,4441,4442,4443,4444,4445,4446,4447,4448,4449,4450,4451,4452,4453,4454,4455,4456,4457,4458,4459,4460,4461,4462,4463,4464,4465,4466,4467,4468,4469,4470,4471,4472,4473,4474,4475,4476,4477,4478,4479,4480,4481,4482,4483,4484,4485,4486,4487,4488,4489,4490,4491,4492,4493,4494,4495,4496,4497,4498,4499,4500,4501,4502,4503,4504,4505,4506,4507,4508,4509,4510,4511,4512,4513,4514,4515,4516,4517,4518,4519,4520,4521,4522,4523,4524,4525,4526,4527,4528,4529,4530,4531,4532,4533,4534,4535,4536,4537,4538,4539,4540,4541,4542,4543,4544,4545,4546,4547,4548,4549,4550,4551,4552,4553,4554,4555,4556,4557,4558,4559,4560,4561,4562,4563,4564,4565,4566,4567,4568,4569,4570,4571,4572,4573,4574,4575,4576,4577,4578,4579,4580,4581,4582,4583,4584,4585,4586,4587,4588,4589,4590,4591,4592,4593,4594,4595,4596,4597,4598,4599,4600,4601,4602,4603,4604,4605,4606,4607,4608,4609,4610,4611,4612,4613,4614,4615,4616,4617,4618,4619', '1', '', '0', '1', '', '', '', '', '1', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('36', '3', '安庆', '0,1,3', '36,398,399,400,401,402,404,405,406,407,408,3440', '1', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('37', '3', '蚌埠', '0,1,3', '37,409,410,411,412,413,414,415,3441,3442,3443,3444,3445', '1', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('38', '3', '巢湖', '0,1,3', '38,416,417,418,419,420', '1', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('39', '3', '池州', '0,1,3', '39,421,422,423,424,3464', '1', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('40', '3', '滁州', '0,1,3', '40,425,426,427,428,429,430,431,432,3459', '1', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('41', '3', '阜阳', '0,1,3', '41,437,438,439,440,441,442,443,444,3460', '1', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('42', '3', '淮北', '0,1,3', '42,445,446,447,448,3456', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('43', '3', '淮南', '0,1,3', '43,449,450,451,452,453,454,3450,3451', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('44', '3', '黄山', '0,1,3', '44,455,456,457,458,459,460,461,3458', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('45', '3', '六安', '0,1,3', '45,462,463,465,466,467,468,3462', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('46', '3', '马鞍山', '0,1,3', '46,469,470,471,472,3452,3453,3454,3455', '1', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('47', '3', '宿州', '0,1,3', '47,473,474,475,476,477,3461', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('48', '3', '铜陵', '0,1,3', '48,478,479,480,481,3457', '1', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('49', '3', '芜湖', '0,1,3', '49,482,483,484,485,486,487,488,3448,3449', '1', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('50', '3', '宣城', '0,1,3', '50,489,490,491,492,493,494,495,3465', '1', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('51', '3', '亳州', '0,1,3', '51,496,497,498,499,3463', '1', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('53', '4', '福州', '0,1,4', '53,518,519,520,521,522,523,524,525,526,527,528,529,530,3466', '1', '', '0', '1', '', '', '', '', '1', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('54', '4', '龙岩', '0,1,4', '54,531,532,533,534,535,536,537,3473', '1', '', '0', '1', '', '', '', '', '1', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('55', '4', '南平', '0,1,4', '55,538,539,540,541,542,543,544,545,546,547,3472', '1', '', '0', '1', '', '', '', '', '1', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('56', '4', '宁德', '0,1,4', '56,548,549,550,551,552,553,554,555,556,3474', '1', '', '0', '1', '', '', '', '', '1', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('57', '4', '莆田', '0,1,4', '57', '1', '莆田是福建省管辖的一个地级市，古称“兴化”，又称“莆阳”、“莆仙”[1-2]  ，素有“海滨邹鲁”、“文献名邦”之美誉，是福建省历史文化名城。莆田盛产鳗鱼、对虾、梭子蟹、丁昌鱼等海产品，龙眼、荔枝、枇杷、文旦柚“四大名果”驰名中外。莆田被列为第一批国家新型城镇化综合试点地区。', '0', '1', '', '[{\"path\":\"\\/upload\\/60\\/images\\/gallery\\/s\\/f\\/1024_src.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"}]', '', '[{\"path\":\"\\/upload\\/60\\/images\\/gallery\\/s\\/e\\/1023_src.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"},{\"path\":\"\\/upload\\/60\\/images\\/gallery\\/s\\/i\\/1027_src.png\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"},{\"path\":\"\\/upload\\/60\\/images\\/gallery\\/s\\/q\\/1035_src.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"},{\"path\":\"\\/upload\\/60\\/images\\/gallery\\/t\\/0\\/1045_src.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"}]', '1', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('58', '4', '泉州', '0,1,4', '58,562,563,564,565,566,567,568,569,570,571,572,573,574,3470', '1', '', '0', '1', '', '', '', '', '1', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('59', '4', '三明', '0,1,4', '59,575,576,577,578,579,580,581,582,583,584,585,586,3469', '1', '', '0', '1', '', '', '', '', '1', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('60', '4', '厦门', '0,1,4', '60,587,588,589,590,591,592,3467', '1', '', '0', '1', '', '', '', '', '1', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('61', '4', '漳州', '0,1,4', '61,593,594,595,596,597,598,599,600,601,602,603,3419,3421,3471', '1', '', '0', '1', '', '', '', '', '1', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('62', '5', '兰州', '0,1,5', '62,604,605,606,607,608,609,610,611,3475', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('63', '5', '白银', '0,1,5', '63,612,613,614,615,616,3477', '1', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('64', '5', '定西', '0,1,5', '64,617,618,619,620,621,622,623,3484', '1', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('65', '5', '甘南藏族自治州', '0,1,5', '65', '1', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('66', '5', '嘉峪关', '0,1,5', '66,633,4021,4022,4023,4024,4025', '1', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('67', '5', '金昌', '0,1,5', '67,634,635,3476', '1', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('68', '5', '酒泉', '0,1,5', '68,636,637,638,639,640,641,642,3482', '1', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('69', '5', '临夏回族自治州', '0,1,5', '69,643,644,645,646,647,648,649,650,3486', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('70', '5', '陇南', '0,1,5', '70,651,652,653,654,655,656,657,658,659,3485', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('71', '5', '平凉', '0,1,5', '71,660,661,662,663,664,665,666,3481', '1', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('72', '5', '庆阳', '0,1,5', '72,667,668,669,670,671,672,673,674,3483', '1', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('73', '5', '天水', '0,1,5', '73,675,676,677,678,679,680,681,3478', '1', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('74', '5', '武威', '0,1,5', '74,682,683,684,685,3479', '1', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('75', '5', '张掖', '0,1,5', '75,686,687,688,689,690,691,3480', '1', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('76', '6', '广州', '0,1,6', '76,692,693,694,695,696,697,698,699,700,701,702,703,704,3488,3489,3490', '1', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('77', '6', '深圳', '0,1,6', '77,705,706,707,708,709,710,3492', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('78', '6', '潮州', '0,1,6', '78,711,712,713,3509', '1', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('79', '6', '东莞', '0,1,6', '79,714,715,716,717,718,719,720,721,722,723,724,725,726,727,728,729,730,731,732,733,734,735,736,737,738,739,740,741,742,743,744,745,4026,4027,4028', '1', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('80', '6', '佛山', '0,1,6', '80,746,747,748,749,750,3495', '1', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('81', '6', '河源', '0,1,6', '81,751,752,753,754,755,756,3505', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('82', '6', '惠州', '0,1,6', '82,757,758,759,760,761,762,3502', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('83', '6', '江门', '0,1,6', '83,763,764,765,766,767,768,769,3496', '1', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('84', '6', '揭阳', '0,1,6', '84,770,771,772,773,774,3510', '1', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('85', '6', '茂名', '0,1,6', '85,775,776,777,778,779,780,3498', '1', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('86', '6', '梅州', '0,1,6', '86,781,782,783,784,785,786,787,788,3503', '1', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('87', '6', '清远', '0,1,6', '87,789,790,791,792,793,794,795,796,3507', '1', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('88', '6', '汕头', '0,1,6', '88,797,798,799,800,801,802,803,3494', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('89', '6', '汕尾', '0,1,6', '89,804,805,806,807,3504', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('90', '6', '韶关', '0,1,6', '90,808,809,810,811,812,813,814,815,816,817,818,3491', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('91', '6', '阳江', '0,1,6', '91,819,820,821,822,3506', '1', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('92', '6', '云浮', '0,1,6', '92,823,824,825,826,827,3511', '1', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('93', '6', '湛江', '0,1,6', '93,828,829,830,831,832,833,834,835,836,3497', '1', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('94', '6', '肇庆', '0,1,6', '94,837,838,839,840,841,842,843,3499,3500,3501', '1', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('95', '6', '中山', '0,1,6', '95,844,845,846,847,848,849,4020,4029,4030,4031,4032,4033,4034,4035,4036,4037,4038,4039,4040,4041,4042,4043,4044,4045,4046', '1', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('96', '6', '珠海', '0,1,6', '96,850,851,852,3493', '1', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('97', '7', '南宁', '0,1,7', '97,853,854,855,856,857,858,859,860,861,862,863,864,3512', '1', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('98', '7', '桂林', '0,1,7', '98,865,866,867,868,869,870,871,872,873,874,875,876,877,878,879,880,881,3514', '1', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('99', '7', '百色', '0,1,7', '99,882,883,884,885,886,887,888,889,890,891,892,893,3523', '1', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('100', '7', '北海', '0,1,7', '100,894,895,896,897,3517', '1', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('101', '7', '崇左', '0,1,7', '101,898,899,900,901,902,903,904,3527', '1', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('102', '7', '防城港', '0,1,7', '102,905,906,907,908,3518', '1', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('103', '7', '贵港', '0,1,7', '103,909,910,911,912,913,3520', '1', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('104', '7', '河池', '0,1,7', '104,914,915,916,917,918,919,920,921,922,923,924,3525', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('105', '7', '贺州', '0,1,7', '105,925,926,927,928,3524', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('106', '7', '来宾', '0,1,7', '106,929,930,931,932,933,934,3526', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('107', '7', '柳州', '0,1,7', '107,935,936,937,938,939,940,941,942,943,944,3513', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('108', '7', '钦州', '0,1,7', '108,945,946,947,948,3519', '1', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('109', '7', '梧州', '0,1,7', '109,949,950,951,952,953,954,955,3515,3516', '1', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('110', '7', '玉林', '0,1,7', '110,956,957,958,959,960,961,3521,3522', '1', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('111', '8', '贵阳', '0,1,8', '111,962,963,964,965,966,967,968,969,970,971,972,973,3528,3529', '1', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('112', '8', '安顺', '0,1,8', '112,974,975,976,977,978,979', '1', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('113', '8', '毕节', '0,1,8', '113,980,981,982,983,984,985,986,987,3535,3536', '1', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('114', '8', '六盘水', '0,1,8', '114,988,989,990,991,3530', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('115', '8', '黔东南苗族侗族自治州', '0,1,8', '115', '1', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('116', '8', '黔南布依族苗族自治州', '0,1,8', '116,1008,1009,1010,1011,1012,1013,1014,1015,1016,1017,1018,1019,3538', '1', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('117', '8', '黔西南苗族侗族自治州', '0,1,8', '117', '1', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('118', '8', '铜仁', '0,1,8', '118,1028,1029,1030,1031,1032,1033,1034,1035,1036,1037,3532,3533', '1', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('119', '8', '遵义', '0,1,8', '119,1038,1039,1040,1041,1042,1043,1044,1045,1046,1047,1048,1050,1051,1052,1053,3531', '1', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('120', '9', '海口', '0,1,9', '120,1054,1055,1056,1057,3539', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('121', '9', '三亚', '0,1,9', '121,4047,4048,4049,4050,4051,4052,4053,4054,4055,4056,4057,4058', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('122', '9', '白沙黎族自治县', '0,1,9', '122,4188,4189,4190,4191,4192,4193,4194,4195,4196,4197,4198,4199,4200', '1', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('123', '9', '保亭黎族苗族自治县', '0,1,9', '123,4239,4240,4241,4242,4243,4244,4245,4246,4247,4248,4249,4250', '1', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('124', '9', '昌江黎族自治县', '0,1,9', '124,4201,4202,4203,4204,4205,4206,4207,4208,4209,4210', '1', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('125', '9', '澄迈县', '0,1,9', '125,4159,4160,4161,4162,4163,4164,4165,4166,4167,4168,4169,4170,4171,4172,4173,4174,4175', '1', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('126', '9', '定安县', '0,1,9', '126,4136,4137,4138,4139,4140,4141,4142,4143,4144,4145,4146,4147,4148', '1', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('127', '9', '东方', '0,1,9', '127,4124,4125,4126,4127,4128,4129,4130,4131,4132,4133,4134,4135', '1', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('128', '9', '乐东黎族自治县', '0,1,9', '128,4211,4212,4213,4214,4215,4216,4217,4218,4219,4220,4221,4222,4223,4224', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('129', '9', '临高县', '0,1,9', '129,4176,4177,4178,4179,4180,4181,4182,4183,4184,4185,4186,4187', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('130', '9', '陵水黎族自治县', '0,1,9', '130,4225,4226,4227,4228,4229,4230,4231,4232,4233,4234,4235,4236,4237,4238', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('131', '9', '琼海', '0,1,9', '131,4067,4068,4069,4070,4071,4072,4073,4074,4075,4076,4077,4078,4079,4080,4081,4082', '1', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('132', '9', '琼中黎族苗族自治县', '0,1,9', '132,4251,4252,4253,4254,4255,4256,4257,4258,4259,4260,4261,4262,4263,4264,4265', '1', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('133', '9', '屯昌县', '0,1,9', '133,4149,4150,4151,4152,4153,4154,4155,4156,4157,4158', '1', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('134', '9', '万宁', '0,1,9', '134,4108,4109,4110,4111,4112,4113,4114,4115,4116,4117,4118,4119,4120,4121,4122,4123', '1', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('135', '9', '文昌', '0,1,9', '135,4088,4089,4090,4091,4092,4093,4094,4095,4096,4097,4098,4099,4100,4101,4102,4103,4104,4105,4106,4107', '1', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('136', '9', '五指山', '0,1,9', '136,4059,4060,4061,4062,4063,4064,4065,4066', '1', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('137', '9', '儋州', '0,1,9', '137,1058,1059,1060,1061,1062,1063,1064,1065,1066,1067,1068,1069,1070,1071,1072,1073,1074,1075,1076,1077,4083,4084,4085,4086,4087', '1', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('138', '10', '石家庄', '0,1,10', '138', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('139', '10', '保定', '0,1,10', '139', '1', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('140', '10', '沧州', '0,1,10', '140,1127,1128,1129,1130,1131,1132,1133,1134,1135,1136,1137,1138,1139,1140,1141,1142,3437', '1', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('141', '10', '承德', '0,1,10', '141,1143,1144,1145,1146,1147,1148,1149,1150,1151,1152,1153,3436', '1', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('142', '10', '邯郸', '0,1,10', '142', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('143', '10', '衡水', '0,1,10', '143', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('144', '10', '廊坊', '0,1,10', '144,1184,1185,1186,1187,1188,1189,1190,1191,1192,1193,3438', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('145', '10', '秦皇岛', '0,1,10', '145', '1', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('146', '10', '唐山', '0,1,10', '146', '1', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('147', '10', '邢台', '0,1,10', '147', '1', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('148', '10', '张家口', '0,1,10', '148', '1', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('149', '11', '郑州', '0,1,11', '149,1251,1252,1253,1254,1255,1256,1257,1258,1259,1260,1261,1262,1263,1264,1265,1266,1267,3544', '1', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('150', '11', '洛阳', '0,1,11', '150,1268,1269,1270,1271,1272,1273,1274,1275,1276,1277,1278,1279,1280,1281,1282,3547', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('151', '11', '开封', '0,1,11', '151,1283,1284,1285,1286,1287,1288,1289,1290,1291,1292,3545,3546', '1', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('152', '11', '安阳', '0,1,11', '152,1293,1294,1295,1296,1297,1298,1299,1300,1301,3549', '1', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('153', '11', '鹤壁', '0,1,11', '153,1302,1303,1304,1305,1306,3550', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('154', '11', '济源', '0,1,11', '154,1307,4266,4267,4268,4269,4270,4271,4272,4273,4274,4275', '1', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('155', '11', '焦作', '0,1,11', '155,1308,1309,1310,1311,1312,1313,1314,1315,1316,1317,3552', '1', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('156', '11', '南阳', '0,1,11', '156,1318,1319,1320,1321,1322,1323,1324,1325,1326,1327,1328,1329,1330,3558', '1', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('157', '11', '平顶山', '0,1,11', '157,1331,1332,1333,1334,1335,1336,1337,1338,1339,1340,3548', '1', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('158', '11', '三门峡', '0,1,11', '158,1341,1342,1343,1344,1345,1346,3556,3557', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('159', '11', '商丘', '0,1,11', '159,1347,1348,1349,1350,1351,1352,1353,1354,1355,3559', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('160', '11', '新乡', '0,1,11', '160,1356,1357,1358,1359,1360,1361,1362,1363,1364,1365,1366,1367,3551', '1', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('161', '11', '信阳', '0,1,11', '161,1368,1369,1370,1371,1372,1373,1374,1375,1376,1377,3560', '1', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('162', '11', '许昌', '0,1,11', '162,1378,1379,1380,1381,1382,1383,3554', '1', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('163', '11', '周口', '0,1,11', '163,1384,1385,1386,1387,1388,1389,1390,1391,1392,1393,3561', '1', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('164', '11', '驻马店', '0,1,11', '164,1394,1395,1396,1397,1398,1399,1400,1401,1402,1403,3562', '1', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('165', '11', '漯河', '0,1,11', '165,1404,1405,1406,1407,1408,3555', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('166', '11', '濮阳', '0,1,11', '166,1409,1410,1411,1412,1413,1414,3553', '1', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('167', '12', '哈尔滨', '0,1,12', '167,1415,1416,1417,1418,1419,1420,1421,1422,1423,1424,1425,1426,1427,1428,1429,1430,1431,1432,1433,1434,3563', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('168', '12', '大庆', '0,1,12', '168,1435,1436,1437,1438,1439,1440,1441,1442,1443,3569', '1', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('169', '12', '大兴安岭', '0,1,12', '169,1444,1445,1446,3576,3577,3578', '1', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('170', '12', '鹤岗', '0,1,12', '170,1447,1448,1449,1450,1451,1452,1453,1454,3567', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('171', '12', '黑河', '0,1,12', '171,1455,1456,1457,1458,1459,1460,3574', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('172', '12', '鸡西', '0,1,12', '172,1461,1462,1463,1464,1465,1466,1467,1468,3565,3566', '1', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('173', '12', '佳木斯', '0,1,12', '173,1469,1470,1471,1472,1473,1474,1475,1476,1477,1478,3571', '1', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('174', '12', '牡丹江', '0,1,12', '174,1479,1480,1481,1482,1483,1484,1485,1486,1487,1488,3573', '1', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('175', '12', '七台河', '0,1,12', '175,1489,1490,1491,1492,3572', '1', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('176', '12', '齐齐哈尔', '0,1,12', '176,1493,1494,1495,1496,1497,1498,1499,1500,1501,1502,1503,1504,1505,1506,1507,1508,3564', '1', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('177', '12', '双鸭山', '0,1,12', '177,1509,1510,1511,1512,1513,1514,1515,1516,3568', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('178', '12', '绥化', '0,1,12', '178,1517,1518,1519,1520,1521,1522,1523,1524,1525,1526,3575', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('179', '12', '伊春', '0,1,12', '179,1527,1528,1529,1530,1531,1532,1533,1534,1535,1536,1537,1538,1539,1540,1541,1542,1543,3570', '1', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('180', '13', '武汉', '0,1,13', '180,1544,1545,1546,1547,1548,1549,1550,1551,1552,1553,1554,1555,1556,1557,3579', '1', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('181', '13', '仙桃', '0,1,13', '181,4277,4278,4279,4280,4281,4282,4283,4284,4285,4286,4287,4288,4289,4290,4291,4292,4293,4294,4295,4296,4297,4298,4299,4300,4301', '1', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('182', '13', '鄂州', '0,1,13', '182,1559,1560,1561,3586', '1', '', '0', '1', '', '', '', '', '0', 'E', '0', '');
INSERT INTO `zz_linkage` VALUES ('183', '13', '黄冈', '0,1,13', '183,1562,1563,1564,1565,1566,1567,1568,1569,1570,1571,3591', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('184', '13', '黄石', '0,1,13', '184,1572,1573,1574,1575,1576,1577,3580', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('185', '13', '荆门', '0,1,13', '185,1578,1579,1580,1581,1582,3587', '1', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('186', '13', '荆州', '0,1,13', '186,1583,1584,1585,1586,1587,1588,1589,1590,3590', '1', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('187', '13', '潜江', '0,1,13', '187,4302,4303,4304,4305,4306,4307,4308,4309,4310,4311,4312,4313,4314,4315,4316,4317,4318,4319,4320,4321,4322,4323,4324,4325,4326', '1', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('188', '13', '神农架林区', '0,1,13', '188,4355,4356,4357,4358,4359,4360,4361,4362', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('189', '13', '十堰', '0,1,13', '189,1593,1594,1595,1596,1597,1598,1599,1600,3581,3582', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('190', '13', '随州', '0,1,13', '190,1601,1602,3593,3594', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('191', '13', '天门', '0,1,13', '191,4327,4328,4329,4330,4331,4332,4333,4334,4335,4336,4337,4338,4339,4340,4341,4342,4343,4344,4345,4346,4347,4348,4349,4350,4351,4352,4353,4354', '1', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('192', '13', '咸宁', '0,1,13', '192,1604,1605,1606,1607,1608,1609,3592', '1', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('193', '13', '襄阳', '0,1,13', '193,1610,1611,1612,1613,1614,1615,1616,1617,1618,3584,3585', '1', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('194', '13', '孝感', '0,1,13', '194,1619,1620,1621,1622,1623,1624,1625,3588', '1', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('195', '13', '宜昌', '0,1,13', '195,1626,1627,1628,1629,1630,1631,1632,1633,1634,1635,1636,1637,1638,3583', '1', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('196', '13', '恩施土家族苗族自治州', '0,1,13', '196', '1', '', '0', '1', '', '', '', '', '0', 'E', '0', '');
INSERT INTO `zz_linkage` VALUES ('197', '14', '长沙', '0,1,14', '197,1647,1648,1649,1650,1651,1652,1653,1654,1655,1656,3596', '1', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('198', '14', '张家界', '0,1,14', '198,1657,1658,1659,1660,3603', '1', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('199', '14', '常德', '0,1,14', '199,1661,1662,1663,1664,1665,1666,1667,1668,1669,3602', '1', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('200', '14', '郴州', '0,1,14', '200,1670,1671,1672,1673,1674,1675,1676,1677,1678,1679,1680,3605', '1', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('201', '14', '衡阳', '0,1,14', '201,1681,1682,1683,1684,1685,1686,1687,1688,1689,1690,1691,1692,3599', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('202', '14', '怀化', '0,1,14', '202,1693,1694,1695,1696,1697,1698,1699,1700,1701,1702,1703,1704,3607', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('203', '14', '娄底', '0,1,14', '203,1705,1706,1707,1708,1709,3608', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('204', '14', '邵阳', '0,1,14', '204,1710,1711,1712,1713,1714,1715,1716,1717,1718,1719,1720,1721,3600', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('205', '14', '湘潭', '0,1,14', '205,1722,1723,1724,1725,1726,3598', '1', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('206', '14', '湘西土家族苗族自治州', '0,1,14', '206', '1', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('207', '14', '益阳', '0,1,14', '207,1735,1736,1737,1738,1739,1740,3604', '1', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('208', '14', '永州', '0,1,14', '208,1741,1742,1743,1744,1745,1746,1747,1748,1749,1750,1751,3606', '1', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('209', '14', '岳阳', '0,1,14', '209,1752,1753,1754,1755,1756,1757,1758,1759,1760,3601', '1', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('210', '14', '株洲', '0,1,14', '210,1761,1762,1763,1764,1765,1766,1767,1768,1769,3597', '1', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('211', '15', '长春', '0,1,15', '211,1770,1771,1772,1773,1774,1775,1776,1777,1778,1779,1780,1781,1782,1783,3610', '1', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('212', '15', '吉林', '0,1,15', '212,1784,1785,1786,1787,1788,1789,1790,1791,1792,3611', '1', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('213', '15', '白城', '0,1,15', '213,1793,1794,1795,1796,1797,3618', '1', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('214', '15', '白山', '0,1,15', '214,1798,1799,1800,1801,1802,1803,3615,3616', '1', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('215', '15', '辽源', '0,1,15', '215,1804,1805,1806,1807,3613', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('216', '15', '四平', '0,1,15', '216,1808,1809,1810,1811,1812,1813,3612', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('217', '15', '松原', '0,1,15', '217,1814,1815,1816,1817,1818,3617', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('218', '15', '通化', '0,1,15', '218,1819,1820,1821,1822,1823,1824,1825,3614', '1', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('219', '15', '延边朝鲜族自治州', '0,1,15', '219', '1', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('220', '16', '南京', '0,1,16', '220,1834,1835,1836,1837,1838,1839,1840,1841,1842,1843,1844,1845,1846,3620', '1', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('221', '16', '苏州', '0,1,16', '221,1847,1848,1849,1850,1851,1852,1853,1854,1855,1856,1857,1858,1859,1860,1861,1862,1863,1864,1865,1866,1867,1868,1869,3626,3627,3628', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('222', '16', '无锡', '0,1,16', '222,1870,1871,1872,1873,1874,1875,1876,1877,1878,3621,3622,3623', '1', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('223', '16', '常州', '0,1,16', '223,1879,1880,1881,1882,1883,1884,1885,1886,3625', '1', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('224', '16', '淮安', '0,1,16', '224,1887,1888,1889,1890,1891,1892,1893,1894,3631,3632', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('225', '16', '连云港', '0,1,16', '225,1895,1896,1897,1898,1899,1900,1901,3630', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('226', '16', '南通', '0,1,16', '226,1902,1903,1904,1905,1906,1907,1908,1909,1910,3629', '1', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('227', '16', '宿迁', '0,1,16', '227,1911,1912,1913,1914,1915,1916,3638', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('228', '16', '泰州', '0,1,16', '228,1917,1918,1919,1920,1921,1922,3636,3637', '1', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('229', '16', '徐州', '0,1,16', '229,1923,1924,1925,1926,1927,1928,1929,1930,1931,1932,1933,3624', '1', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('230', '16', '盐城', '0,1,16', '230,1934,1935,1936,1937,1938,1939,1940,1941,1942,1943,1944,3633', '1', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('231', '16', '扬州', '0,1,16', '231,1945,1946,1947,1948,1949,1950,1951,3634', '1', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('232', '16', '镇江', '0,1,16', '232,1952,1953,1954,1955,1956,1957,3635', '1', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('233', '17', '南昌', '0,1,17', '233,1958,1959,1960,1961,1962,1963,1964,1965,1966,1967,1968,1969,3639', '1', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('234', '17', '抚州', '0,1,17', '234,1970,1971,1972,1973,1974,1975,1976,1977,1978,1979,1980,3649', '1', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('235', '17', '赣州', '0,1,17', '235,1981,1982,1983,1984,1985,1986,1987,1988,1989,1990,1991,1992,1993,1994,1995,1996,1997,1998,3646', '1', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('236', '17', '吉安', '0,1,17', '236,1999,2000,2001,2002,2003,2004,2005,2006,2007,2008,2009,2010,2011,3647', '1', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('237', '17', '景德镇', '0,1,17', '237,2012,2013,2014,2015,3640', '1', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('238', '17', '九江', '0,1,17', '238,2016,2017,2018,2019,2020,2021,2022,2023,2024,2025,2026,2027,3642,3643', '1', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('239', '17', '萍乡', '0,1,17', '239,2028,2029,2030,2031,2032,3641', '1', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('240', '17', '上饶', '0,1,17', '240,2033,2034,2035,2036,2037,2038,2039,2040,2041,2042,2043,2044,3650', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('241', '17', '新余', '0,1,17', '241,2045,2046,3644', '1', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('242', '17', '宜春', '0,1,17', '242,2047,2048,2049,2050,2051,2052,2053,2054,2055,2056,3648', '1', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('243', '17', '鹰潭', '0,1,17', '243,2057,2058,2059,3645', '1', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('244', '18', '沈阳', '0,1,18', '244,2060,2061,2062,2063,2064,2065,2066,2067,2068,2069,2070,2071,2072,2073,3651,3652', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('245', '18', '大连', '0,1,18', '245,2074,2075,2076,2077,2078,2079,2080,2081,2082,2083,2084,3653', '1', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('246', '18', '鞍山', '0,1,18', '246,2085,2086,2087,2088,2089,2090,2091,3654', '1', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('247', '18', '本溪', '0,1,18', '247,2092,2093,2094,2095,2096,2097,3656', '1', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('248', '18', '朝阳', '0,1,18', '248,2098,2099,2100,2101,2102,2103,2104,3664', '1', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('249', '18', '丹东', '0,1,18', '249,2105,2106,2107,2108,2109,2110,3657', '1', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('250', '18', '抚顺', '0,1,18', '250,2111,2112,2113,2114,2115,2116,2117,3655', '1', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('251', '18', '阜新', '0,1,18', '251,2118,2119,2120,2121,2122,2123,2124,3660', '1', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('252', '18', '葫芦岛', '0,1,18', '252,2125,2126,2127,2128,2129,2130,3665', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('253', '18', '锦州', '0,1,18', '253,2131,2132,2133,2134,2135,2136,2137,3658', '1', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('254', '18', '辽阳', '0,1,18', '254,2138,2139,2140,2141,2142,2143,2144,3661', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('255', '18', '盘锦', '0,1,18', '255,2145,2146,2147,2148,3662', '1', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('256', '18', '铁岭', '0,1,18', '256,2149,2150,2151,2152,2153,2154,2155,3663', '1', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('257', '18', '营口', '0,1,18', '257,2156,2157,2158,2159,2160,2161,3659', '1', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('258', '19', '呼和浩特', '0,1,19', '258,2162,2163,2164,2165,2166,2167,2168,2169,2170,3666', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('259', '19', '阿拉善盟', '0,1,19', '259,2171,2172,2173,3678', '1', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('260', '19', '巴彦淖尔盟', '0,1,19', '260,2174,2175,2176,2177,2178,2179,2180,3674', '1', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('261', '19', '包头', '0,1,19', '261,2181,2182,2183,2184,2185,2186,2187,2188,2189,3667', '1', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('262', '19', '赤峰', '0,1,19', '262,2190,2191,2192,2193,2194,2195,2196,2197,2198,2199,2200,2201,3669', '1', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('263', '19', '鄂尔多斯', '0,1,19', '263,2202,2203,2204,2205,2206,2207,2208,2209,3671', '1', '', '0', '1', '', '', '', '', '0', 'E', '0', '');
INSERT INTO `zz_linkage` VALUES ('264', '19', '呼伦贝尔', '0,1,19', '264,2210,2211,2212,2213,2214,2215,2216,2217,2218,2219,2220,2221,2222,3672,3673', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('265', '19', '通辽', '0,1,19', '265,2223,2224,2225,2226,2227,2228,2229,2230,3670', '1', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('266', '19', '乌海', '0,1,19', '266,2231,2232,2233,3668', '1', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('267', '19', '乌兰察布市', '0,1,19', '267,2234,2235,2236,2237,2238,2239,2240,2241,2242,2243,2244,3675', '1', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('268', '19', '锡林郭勒盟', '0,1,19', '268,2245,2246,2247,2248,2249,2250,2251,2252,2253,2254,2255,2256,3677', '1', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('269', '19', '兴安盟', '0,1,19', '269,2257,2258,2259,2260,2261,2262,3676', '1', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('270', '20', '银川', '0,1,20', '270,2263,2264,2265,2266,2267,2268,3679', '1', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('271', '20', '固原', '0,1,20', '271,2269,2271,2272,2273,2274,3683', '1', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('272', '20', '石嘴山', '0,1,20', '272,2275,2276,2277,2278,2279,3680', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('273', '20', '吴忠', '0,1,20', '273,2280,2282,2284,2285,3681,3682', '1', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('274', '20', '中卫', '0,1,20', '274,2286,2287,2288,3684', '1', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('275', '21', '西宁', '0,1,21', '275,2289,2290,2291,2292,2293,2294,2295,3685', '1', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('276', '21', '果洛藏族自治州', '0,1,21', '276', '1', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('277', '21', '海北藏族自治州', '0,1,21', '277,2302,2303,2304,2305,3687', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('278', '21', '海东', '0,1,21', '278,2306,2307,2308,2309,2310,2311,3686', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('279', '21', '海南藏族自治州', '0,1,21', '279', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('280', '21', '海西藏族蒙古族自治州', '0,1,21', '280', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('281', '21', '黄南藏族自治州', '0,1,21', '281', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('282', '21', '玉树藏族自治州', '0,1,21', '282', '1', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('283', '22', '济南', '0,1,22', '283,2332,2333,2334,2335,2336,2337,2338,2339,2340,2341,3693', '1', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('284', '22', '青岛', '0,1,22', '284,2342,2343,2344,2345,2346,2347,2348,2349,2350,2351,2352,2353,3694', '1', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('285', '22', '滨州', '0,1,22', '285,2354,2355,2356,2357,2358,2359,2360,3710', '1', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('286', '22', '德州', '0,1,22', '286,2361,2362,2363,2364,2365,2366,2367,2368,2369,2370,2371,3707,3708', '1', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('287', '22', '东营', '0,1,22', '287,2372,2373,2374,2375,2376,3697', '1', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('288', '22', '菏泽', '0,1,22', '288,2377,2378,2379,2380,2381,2382,2383,2384,2385,3711', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('289', '22', '济宁', '0,1,22', '289,2386,2387,2388,2389,2390,2391,2392,2393,2394,2395,2396,2397,3700', '1', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('290', '22', '莱芜', '0,1,22', '290,2398,2399,3704', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('291', '22', '聊城', '0,1,22', '291,2400,2401,2402,2403,2404,2405,2406,2407,3709', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('292', '22', '临沂', '0,1,22', '292,2408,2409,2410,2411,2412,2413,2414,2415,2416,2417,2418,2419,3705,3706', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('293', '22', '日照', '0,1,22', '293,2420,2421,2422,2423,3703', '1', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('294', '22', '泰安', '0,1,22', '294,2424,2425,2426,2427,2428,2429,3701', '1', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('295', '22', '威海', '0,1,22', '295,2430,2431,2432,2433,3702', '1', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('296', '22', '潍坊', '0,1,22', '296,2434,2435,2436,2437,2438,2439,2440,2441,2442,2443,2444,2445,3699', '1', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('297', '22', '烟台', '0,1,22', '297,2446,2447,2448,2449,2450,2451,2452,2453,2454,2455,2456,2457,2458,3698', '1', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('298', '22', '枣庄', '0,1,22', '298,2459,2460,2461,2462,2463,2464,3696', '1', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('299', '22', '淄博', '0,1,22', '299,2465,2466,2467,2468,2469,2470,2471,2472,3695', '1', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('300', '23', '太原', '0,1,23', '300,2473,2474,2475,2476,2477,2478,2479,2480,2481,2482,2483,2484,2485,3712', '1', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('301', '23', '长治', '0,1,23', '301,2486,2487,2488,2489,2490,2491,2492,2493,2494,2495,2496,2497,2498,3715', '1', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('302', '23', '大同', '0,1,23', '302,2499,2500,2501,2502,2503,2504,2505,2506,2507,2508,2509,3713', '1', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('303', '23', '晋城', '0,1,23', '303,2510,2511,2512,2513,2514,2515,3716', '1', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('304', '23', '晋中', '0,1,23', '304,2516,2517,2518,2519,2520,2521,2522,2523,2524,2525,2526,3718', '1', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('305', '23', '临汾', '0,1,23', '305,2527,2528,2529,2530,2531,2532,2533,2534,2535,2536,2537,2538,2539,2540,2541,2542,2543,3721', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('306', '23', '吕梁', '0,1,23', '306,2544,2545,2546,2547,2548,2549,2550,2551,2552,2553,2554,2555,2556,2557,3722', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('307', '23', '朔州', '0,1,23', '307,2558,2559,2560,2561,2562,2563,3717', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('308', '23', '忻州', '0,1,23', '308,2564,2565,2566,2567,2568,2569,2570,2571,2572,2573,2574,2575,2576,2577,3720', '1', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('309', '23', '阳泉', '0,1,23', '309,2578,2579,2580,2581,2582,3714', '1', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('310', '23', '运城', '0,1,23', '310,2583,2584,2585,2586,2587,2588,2589,2590,2591,2592,2593,2594,2595,3719', '1', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('311', '24', '西安', '0,1,24', '311,2596,2597,2598,2599,2600,2601,2602,2603,2604,2605,2606,2607,2608,3723', '1', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('312', '24', '安康', '0,1,24', '312,2609,2610,2611,2612,2613,2614,2615,2616,2617,2618,3731', '1', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('313', '24', '宝鸡', '0,1,24', '313,2619,2620,2621,2622,2623,2624,2625,2626,2627,2628,2629,2630,3725', '1', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('314', '24', '汉中', '0,1,24', '314,2631,2632,2633,2634,2635,2636,2637,2638,2639,2640,2641,3729', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('315', '24', '商洛', '0,1,24', '315,2642,2643,2644,2645,2646,2647,2648,3732', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('316', '24', '铜川', '0,1,24', '316,2649,2650,2651,2652,3724', '1', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('317', '24', '渭南', '0,1,24', '317,2653,2654,2655,2656,2657,2658,2659,2660,2661,2662,2663,3727', '1', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('318', '24', '咸阳', '0,1,24', '318,2664,2665,2666,2667,2668,2669,2670,2671,2672,2673,2674,2675,2676,2677,3726', '1', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('319', '24', '延安', '0,1,24', '319,2678,2679,2680,2681,2682,2683,2684,2685,2686,2687,2688,2689,2690,3728', '1', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('320', '24', '榆林', '0,1,24', '320,2691,2692,2693,2694,2695,2696,2697,2698,2699,2700,2701,2702,3730', '1', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('322', '26', '成都', '0,1,26', '322,2722,2723,2724,2725,2726,2727,2728,2729,2730,2731,2732,2733,2734,2735,2736,2737,2738,2739,2740,2741,2742,3734', '1', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('323', '26', '绵阳', '0,1,26', '323,2753,2754,2755,2756,2757,2758,2759,2760,2761,3739', '1', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('324', '26', '阿坝藏族羌族自治州', '0,1,26', '324', '1', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('325', '26', '巴中', '0,1,26', '325,2775,2776,2777,2778,3757,3758', '1', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('326', '26', '达州', '0,1,26', '326,2779,2780,2781,2782,2783,2784,2785,3754,3755', '1', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('327', '26', '德阳', '0,1,26', '327,2786,2787,2788,2789,2790,2791,3738', '1', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('328', '26', '甘孜藏族自治州', '0,1,26', '328', '1', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('329', '26', '广安', '0,1,26', '329,2810,2811,2812,2813,2814,3752,3753', '1', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('330', '26', '广元', '0,1,26', '330,2815,2816,2817,2818,2819,2820,2821,3740,3741', '1', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('331', '26', '乐山', '0,1,26', '331,2822,2823,2824,2825,2826,2827,2828,2829,3744,3745,3746,3747,3748', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('332', '26', '凉山彝族自治州', '0,1,26', '332,2830,2831,2832,2833,2834,2835,2836,2837,2838,2839,2840,2841,2842,2843,2844,2845,2846,3762', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('333', '26', '眉山', '0,1,26', '333,2847,2848,2849,2850,2851,2852,3750', '1', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('334', '26', '南充', '0,1,26', '334,2853,2854,2855,2856,2857,2858,2859,2860,2861,3749', '1', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('335', '26', '内江', '0,1,26', '335,2862,2863,2864,2865,2866,3743', '1', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('336', '26', '攀枝花', '0,1,26', '336,2867,2868,2869,2870,2871,3736', '1', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('337', '26', '遂宁', '0,1,26', '337,2872,2873,2874,2875,2876,3742', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('338', '26', '雅安', '0,1,26', '338,2877,2878,2879,2880,2881,2882,2883,2884,3756', '1', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('339', '26', '宜宾', '0,1,26', '339,2885,2886,2887,2888,2889,2890,2891,2892,2893,2894,3751', '1', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('340', '26', '资阳', '0,1,26', '340,2895,2896,2897,2898,3759', '1', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('341', '26', '自贡', '0,1,26', '341,2899,2900,2901,2902,2903,2904,3735', '1', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('342', '26', '泸州', '0,1,26', '342,2905,2906,2907,2908,2909,2910,2911,3737', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('344', '28', '拉萨', '0,1,28', '344,2931,2932,2933,2934,2935,2936,2937,2938,3763', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('345', '28', '阿里', '0,1,28', '345,2939,2940,2941,2942,2943,2944,2945,3771', '1', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('346', '28', '昌都', '0,1,28', '346,2946,2947,2948,2949,2950,2951,2952,2953,2954,2955,2956,3764,3765', '1', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('347', '28', '林芝', '0,1,28', '347,2957,2958,2959,2960,2961,2962,2963,3772,3773', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('348', '28', '那曲', '0,1,28', '348,2964,2965,2966,2967,2968,2969,2970,2971,2972,2973,3769,3770', '1', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('349', '28', '日喀则', '0,1,28', '349,2974,2975,2976,2977,2978,2979,2980,2981,2982,2983,2984,2985,2986,2987,2988,2989,2990,2991,3767,3768', '1', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('350', '28', '山南', '0,1,28', '350,2992,2993,2994,2995,2996,2997,2998,2999,3000,3001,3002,3003,3766', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('351', '29', '乌鲁木齐', '0,1,29', '351,3004,3005,3006,3007,3008,3009,3010,3011,3774', '1', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('352', '29', '阿克苏', '0,1,29', '352,3012,3013,3014,3015,3016,3017,3018,3019,3020,3785', '1', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('353', '29', '阿拉尔', '0,1,29', '353,4371,4372,4373,4374,4375,4376,4377,4378,4379,4380,4381,4382,4383,4384,4385,4386,4387,4388,4389', '1', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('354', '29', '巴音郭楞蒙古自治州', '0,1,29', '354,3022,3023,3024,3025,3026,3027,3028,3029,3030,3784', '1', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('355', '29', '博尔塔拉蒙古自治州', '0,1,29', '355', '1', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('356', '29', '昌吉回族自治州', '0,1,29', '356,3034,3035,3036,3037,3038,3039,3040,3041,3781', '1', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('357', '29', '哈密', '0,1,29', '357,3042,3043,3044,3780', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('358', '29', '和田', '0,1,29', '358,3045,3046,3047,3048,3049,3050,3051,3052,3788', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('359', '29', '喀什', '0,1,29', '359,3053,3054,3055,3056,3057,3058,3059,3060,3061,3062,3063,3064,3787', '1', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('360', '29', '克拉玛依', '0,1,29', '360,3065,3775,3776,3777', '1', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('361', '29', '克孜勒苏柯尔克孜自治州', '0,1,29', '361', '1', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('362', '29', '石河子', '0,1,29', '362,4363,4364,4365,4366,4367,4368,4369,4370', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('363', '29', '图木舒克', '0,1,29', '363,4390,4391,4392,4393,4394,4395,4396,4397,4398,4399', '1', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('364', '29', '吐鲁番', '0,1,29', '364,3072,3073,3074,3778,3779', '1', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('365', '29', '五家渠', '0,1,29', '365,4400,4401,4402,4403,4404,4405', '1', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('366', '29', '伊犁哈萨克自治州', '0,1,29', '366,3076,3077,3078,3079,3080,3081,3082,3083,3084,3085,3086,3087,3088,3089,3090,3091,3092,3093,3094,3095,3096,3097,3098,3099,3789,3790', '1', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('367', '30', '昆明', '0,1,30', '367,3100,3101,3102,3103,3104,3105,3106,3107,3108,3109,3110,3111,3112,3113,3813', '1', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('368', '30', '怒江傈僳族自治州', '0,1,30', '368,3114,3115,3116,3117,3828', '1', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('369', '30', '普洱', '0,1,30', '369,3118,3119,3120,3121,3122,3123,3124,3125,3126,3127,3819', '1', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('370', '30', '丽江', '0,1,30', '370,3128,3129,3130,3131,3132,3818', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('371', '30', '保山', '0,1,30', '371,3133,3134,3135,3136,3137,3816', '1', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('372', '30', '楚雄彝族自治州', '0,1,30', '372', '1', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('373', '30', '大理白族自治州', '0,1,30', '373,3148,3149,3150,3151,3152,3153,3154,3155,3156,3157,3158,3159,3825', '1', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('374', '30', '德宏傣族景颇族自治州', '0,1,30', '374', '1', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('375', '30', '迪庆藏族自治州', '0,1,30', '375,3165,3166,3167,3829', '1', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('376', '30', '红河哈尼族彝族自治州', '0,1,30', '376,3168,3169,3170,3171,3172,3173,3174,3175,3176,3177,3178,3179,3180,3822', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('377', '30', '临沧', '0,1,30', '377,3181,3182,3183,3184,3185,3186,3187,3188,3820', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('378', '30', '曲靖', '0,1,30', '378,3189,3190,3191,3192,3193,3194,3195,3196,3197,3814', '1', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('379', '30', '文山苗族壮族自治州', '0,1,30', '379', '1', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('380', '30', '西双版纳大爱族自治州', '0,1,30', '380', '1', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('381', '30', '玉溪', '0,1,30', '381,3209,3210,3211,3212,3213,3214,3215,3216,3217,3815', '1', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('382', '30', '昭通', '0,1,30', '382,3218,3219,3220,3221,3222,3223,3224,3225,3226,3227,3228,3817', '1', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('383', '31', '杭州', '0,1,31', '383,3229,3230,3231,3232,3233,3234,3235,3236,3237,3238,3239,3240,3241,3242,3830', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('384', '31', '湖州', '0,1,31', '384,3243,3244,3245,3246,3247,3834', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('385', '31', '嘉兴', '0,1,31', '385,3248,3249,3250,3251,3252,3253,3254,3833', '1', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('386', '31', '金华', '0,1,31', '386,3255,3256,3257,3258,3259,3260,3261,3262,3263,3264,3265,3266,3267,3268,3269,3837,3838', '1', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('387', '31', '丽水', '0,1,31', '387,3270,3271,3272,3273,3274,3275,3276,3277,3278,3843', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('388', '31', '宁波', '0,1,31', '388,3279,3280,3281,3282,3283,3284,3285,3286,3287,3288,3289,3831', '1', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('389', '31', '绍兴', '0,1,31', '389,3290,3291,3292,3293,3294,3295,3835,3836', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('390', '31', '台州', '0,1,31', '390,3296,3297,3298,3299,3300,3301,3302,3303,3304,3842', '1', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('391', '31', '温州', '0,1,31', '391,3305,3306,3307,3308,3309,3310,3311,3312,3313,3314,3315,3832', '1', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('392', '31', '舟山', '0,1,31', '392,3316,3317,3318,3319,3841', '1', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('393', '31', '衢州', '0,1,31', '393,3320,3321,3322,3323,3324,3839,3840', '1', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('398', '36', '迎江区', '0,1,3,36', '398', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('399', '36', '大观区', '0,1,3,36', '399', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('400', '36', '宜秀区', '0,1,3,36', '400', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('401', '36', '桐城市', '0,1,3,36', '401', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('402', '36', '怀宁县', '0,1,3,36', '402', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('404', '36', '潜山县', '0,1,3,36', '404', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('405', '36', '太湖县', '0,1,3,36', '405', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('406', '36', '宿松县', '0,1,3,36', '406', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('407', '36', '望江县', '0,1,3,36', '407', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('408', '36', '岳西县', '0,1,3,36', '408', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('409', '37', '中市区', '0,1,3,37', '409', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('410', '37', '东市区', '0,1,3,37', '410', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('411', '37', '西市区', '0,1,3,37', '411', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('412', '37', '郊区', '0,1,3,37', '412', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('413', '37', '怀远县', '0,1,3,37', '413', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('414', '37', '五河县', '0,1,3,37', '414', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('415', '37', '固镇县', '0,1,3,37', '415', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('416', '38', '居巢区', '0,1,3,38', '416', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('417', '38', '庐江县', '0,1,3,38', '417', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('418', '38', '无为县', '0,1,3,38', '418', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('419', '38', '含山县', '0,1,3,38', '419', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('420', '38', '和县', '0,1,3,38', '420', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('421', '39', '贵池区', '0,1,3,39', '421', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('422', '39', '东至县', '0,1,3,39', '422', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('423', '39', '石台县', '0,1,3,39', '423', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('424', '39', '青阳县', '0,1,3,39', '424', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('425', '40', '琅琊区', '0,1,3,40', '425', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('426', '40', '南谯区', '0,1,3,40', '426', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('427', '40', '天长市', '0,1,3,40', '427', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('428', '40', '明光市', '0,1,3,40', '428', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('429', '40', '来安县', '0,1,3,40', '429', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('430', '40', '全椒县', '0,1,3,40', '430', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('431', '40', '定远县', '0,1,3,40', '431', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('432', '40', '凤阳县', '0,1,3,40', '432', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('437', '41', '颍州区', '0,1,3,41', '437', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('438', '41', '颍东区', '0,1,3,41', '438', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('439', '41', '颍泉区', '0,1,3,41', '439', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('440', '41', '界首市', '0,1,3,41', '440', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('441', '41', '临泉县', '0,1,3,41', '441', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('442', '41', '太和县', '0,1,3,41', '442', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('443', '41', '阜南县', '0,1,3,41', '443', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('444', '41', '颖上县', '0,1,3,41', '444', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('445', '42', '相山区', '0,1,3,42', '445', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('446', '42', '杜集区', '0,1,3,42', '446', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('447', '42', '烈山区', '0,1,3,42', '447', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('448', '42', '濉溪县', '0,1,3,42', '448', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('449', '43', '田家庵区', '0,1,3,43', '449', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('450', '43', '大通区', '0,1,3,43', '450', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('451', '43', '谢家集区', '0,1,3,43', '451', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('452', '43', '八公山区', '0,1,3,43', '452', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('453', '43', '潘集区', '0,1,3,43', '453', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('454', '43', '凤台县', '0,1,3,43', '454', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('455', '44', '屯溪区', '0,1,3,44', '455', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('456', '44', '黄山区', '0,1,3,44', '456', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('457', '44', '徽州区', '0,1,3,44', '457', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('458', '44', '歙县', '0,1,3,44', '458', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('459', '44', '休宁县', '0,1,3,44', '459', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('460', '44', '黟县', '0,1,3,44', '460', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('461', '44', '祁门县', '0,1,3,44', '461', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('462', '45', '金安区', '0,1,3,45', '462', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('463', '45', '裕安区', '0,1,3,45', '463', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('465', '45', '霍邱县', '0,1,3,45', '465', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('466', '45', '舒城县', '0,1,3,45', '466', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('467', '45', '金寨县', '0,1,3,45', '467', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('468', '45', '霍山县', '0,1,3,45', '468', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('469', '46', '雨山区', '0,1,3,46', '469', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('470', '46', '花山区', '0,1,3,46', '470', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('471', '46', '金家庄区', '0,1,3,46', '471', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('472', '46', '当涂县', '0,1,3,46', '472', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('473', '47', '埇桥区', '0,1,3,47', '473', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('474', '47', '砀山县', '0,1,3,47', '474', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('475', '47', '萧县', '0,1,3,47', '475', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('476', '47', '灵璧县', '0,1,3,47', '476', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('477', '47', '泗县', '0,1,3,47', '477', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('478', '48', '铜官山区', '0,1,3,48', '478', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('479', '48', '狮子山区', '0,1,3,48', '479', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('480', '48', '郊区', '0,1,3,48', '480', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('481', '48', '铜陵县', '0,1,3,48', '481', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('482', '49', '镜湖区', '0,1,3,49', '482', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('483', '49', '弋江区', '0,1,3,49', '483', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('484', '49', '鸠江区', '0,1,3,49', '484', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('485', '49', '三山区', '0,1,3,49', '485', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('486', '49', '芜湖县', '0,1,3,49', '486', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('487', '49', '繁昌县', '0,1,3,49', '487', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('488', '49', '南陵县', '0,1,3,49', '488', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('489', '50', '宣州区', '0,1,3,50', '489', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('490', '50', '宁国市', '0,1,3,50', '490', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('491', '50', '郎溪县', '0,1,3,50', '491', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('492', '50', '广德县', '0,1,3,50', '492', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('493', '50', '泾县', '0,1,3,50', '493', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('494', '50', '绩溪县', '0,1,3,50', '494', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('495', '50', '旌德县', '0,1,3,50', '495', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('496', '51', '涡阳县', '0,1,3,51', '496', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('497', '51', '蒙城县', '0,1,3,51', '497', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('498', '51', '利辛县', '0,1,3,51', '498', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('499', '51', '谯城区', '0,1,3,51', '499', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('500', '2', '东城区', '0,1,2', '500', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('501', '2', '西城区', '0,1,2', '501', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('502', '2', '海淀区', '0,1,2', '502', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('503', '2', '朝阳区', '0,1,2', '503', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('504', '2', '崇文区', '0,1,2', '504', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('505', '2', '宣武区', '0,1,2', '505', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('506', '2', '丰台区', '0,1,2', '506', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('507', '2', '石景山区', '0,1,2', '507', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('508', '2', '房山区', '0,1,2', '508', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('509', '2', '门头沟区', '0,1,2', '509', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('510', '2', '通州区', '0,1,2', '510', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('511', '2', '顺义区', '0,1,2', '511', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('512', '2', '昌平区', '0,1,2', '512', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('513', '2', '怀柔区', '0,1,2', '513', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('514', '2', '平谷区', '0,1,2', '514', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('515', '2', '大兴区', '0,1,2', '515', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('516', '2', '密云县', '0,1,2', '516', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('517', '2', '延庆县', '0,1,2', '517', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('518', '53', '鼓楼区', '0,1,4,53', '518', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('519', '53', '台江区', '0,1,4,53', '519', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('520', '53', '仓山区', '0,1,4,53', '520', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('521', '53', '马尾区', '0,1,4,53', '521', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('522', '53', '晋安区', '0,1,4,53', '522', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('523', '53', '福清市', '0,1,4,53', '523', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('524', '53', '长乐市', '0,1,4,53', '524', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('525', '53', '闽侯县', '0,1,4,53', '525', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('526', '53', '连江县', '0,1,4,53', '526', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('527', '53', '罗源县', '0,1,4,53', '527', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('528', '53', '闽清县', '0,1,4,53', '528', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('529', '53', '永泰县', '0,1,4,53', '529', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('530', '53', '平潭县', '0,1,4,53', '530', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('531', '54', '新罗区', '0,1,4,54', '531', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('532', '54', '漳平市', '0,1,4,54', '532', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('533', '54', '长汀县', '0,1,4,54', '533', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('534', '54', '永定区', '0,1,4,54', '534', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('535', '54', '上杭县', '0,1,4,54', '535', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('536', '54', '武平县', '0,1,4,54', '536', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('537', '54', '连城县', '0,1,4,54', '537', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('538', '55', '延平区', '0,1,4,55', '538', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('539', '55', '邵武市', '0,1,4,55', '539', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('540', '55', '武夷山市', '0,1,4,55', '540', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('541', '55', '建瓯市', '0,1,4,55', '541', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('542', '55', '建阳区', '0,1,4,55', '542', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('543', '55', '顺昌县', '0,1,4,55', '543', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('544', '55', '浦城县', '0,1,4,55', '544', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('545', '55', '光泽县', '0,1,4,55', '545', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('546', '55', '松溪县', '0,1,4,55', '546', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('547', '55', '政和县', '0,1,4,55', '547', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('548', '56', '蕉城区', '0,1,4,56', '548', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('549', '56', '福安市', '0,1,4,56', '549', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('550', '56', '福鼎市', '0,1,4,56', '550', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('551', '56', '霞浦县', '0,1,4,56', '551', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('552', '56', '古田县', '0,1,4,56', '552', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('553', '56', '屏南县', '0,1,4,56', '553', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('554', '56', '寿宁县', '0,1,4,56', '554', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('555', '56', '周宁县', '0,1,4,56', '555', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('556', '56', '柘荣县', '0,1,4,56', '556', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('557', '57', '城厢区', '0,1,4,57', '557', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('558', '57', '涵江区', '0,1,4,57', '558', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('559', '57', '荔城区', '0,1,4,57', '559', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('560', '57', '秀屿区', '0,1,4,57', '560', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('561', '57', '仙游县', '0,1,4,57', '561', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('562', '58', '鲤城区', '0,1,4,58', '562', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('563', '58', '丰泽区', '0,1,4,58', '563', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('564', '58', '洛江区', '0,1,4,58', '564', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('565', '58', '清濛开发区', '0,1,4,58', '565', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('566', '58', '泉港区', '0,1,4,58', '566', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('567', '58', '石狮市', '0,1,4,58', '567', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('568', '58', '晋江市', '0,1,4,58', '568', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('569', '58', '南安市', '0,1,4,58', '569', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('570', '58', '惠安县', '0,1,4,58', '570', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('571', '58', '安溪县', '0,1,4,58', '571', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('572', '58', '永春县', '0,1,4,58', '572', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('573', '58', '德化县', '0,1,4,58', '573', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('574', '58', '金门县', '0,1,4,58', '574', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('575', '59', '梅列区', '0,1,4,59', '575', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('576', '59', '三元区', '0,1,4,59', '576', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('577', '59', '永安市', '0,1,4,59', '577', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('578', '59', '明溪县', '0,1,4,59', '578', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('579', '59', '清流县', '0,1,4,59', '579', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('580', '59', '宁化县', '0,1,4,59', '580', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('581', '59', '大田县', '0,1,4,59', '581', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('582', '59', '尤溪县', '0,1,4,59', '582', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('583', '59', '沙县', '0,1,4,59', '583', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('584', '59', '将乐县', '0,1,4,59', '584', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('585', '59', '泰宁县', '0,1,4,59', '585', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('586', '59', '建宁县', '0,1,4,59', '586', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('587', '60', '思明区', '0,1,4,60', '587', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('588', '60', '海沧区', '0,1,4,60', '588', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('589', '60', '湖里区', '0,1,4,60', '589', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('590', '60', '集美区', '0,1,4,60', '590', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('591', '60', '同安区', '0,1,4,60', '591', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('592', '60', '翔安区', '0,1,4,60', '592', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('593', '61', '芗城区', '0,1,4,61', '593', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('594', '61', '龙文区', '0,1,4,61', '594', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('595', '61', '龙海市', '0,1,4,61', '595', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('596', '61', '云霄县', '0,1,4,61', '596', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('597', '61', '漳浦县', '0,1,4,61', '597,3419,3421', '1', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('598', '61', '诏安县', '0,1,4,61', '598', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('599', '61', '长泰县', '0,1,4,61', '599', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('600', '61', '东山县', '0,1,4,61', '600', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('601', '61', '南靖县', '0,1,4,61', '601', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('602', '61', '平和县', '0,1,4,61', '602', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('603', '61', '华安县', '0,1,4,61', '603', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('604', '62', '皋兰县', '0,1,5,62', '604', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('605', '62', '城关区', '0,1,5,62', '605', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('606', '62', '七里河区', '0,1,5,62', '606', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('607', '62', '西固区', '0,1,5,62', '607', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('608', '62', '安宁区', '0,1,5,62', '608', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('609', '62', '红古区', '0,1,5,62', '609', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('610', '62', '永登县', '0,1,5,62', '610', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('611', '62', '榆中县', '0,1,5,62', '611', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('612', '63', '白银区', '0,1,5,63', '612', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('613', '63', '平川区', '0,1,5,63', '613', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('614', '63', '会宁县', '0,1,5,63', '614', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('615', '63', '景泰县', '0,1,5,63', '615', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('616', '63', '靖远县', '0,1,5,63', '616', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('617', '64', '临洮县', '0,1,5,64', '617', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('618', '64', '陇西县', '0,1,5,64', '618', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('619', '64', '通渭县', '0,1,5,64', '619', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('620', '64', '渭源县', '0,1,5,64', '620', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('621', '64', '漳县', '0,1,5,64', '621', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('622', '64', '岷县', '0,1,5,64', '622', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('623', '64', '安定区', '0,1,5,64', '623', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('625', '65', '合作市', '0,1,5,65', '625', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('626', '65', '临潭县', '0,1,5,65', '626', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('627', '65', '卓尼县', '0,1,5,65', '627', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('628', '65', '舟曲县', '0,1,5,65', '628', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('629', '65', '迭部县', '0,1,5,65', '629', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('630', '65', '玛曲县', '0,1,5,65', '630', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('631', '65', '碌曲县', '0,1,5,65', '631', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('632', '65', '夏河县', '0,1,5,65', '632', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('633', '66', '新城镇', '0,1,5,66', '633', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('634', '67', '金川区', '0,1,5,67', '634', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('635', '67', '永昌县', '0,1,5,67', '635', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('636', '68', '肃州区', '0,1,5,68', '636', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('637', '68', '玉门市', '0,1,5,68', '637', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('638', '68', '敦煌市', '0,1,5,68', '638', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('639', '68', '金塔县', '0,1,5,68', '639', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('640', '68', '瓜州县', '0,1,5,68', '640', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('641', '68', '肃北蒙古族自治县', '0,1,5,68', '641', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('642', '68', '阿克塞哈萨克族自治县', '0,1,5,68', '642', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('643', '69', '临夏市', '0,1,5,69', '643', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('644', '69', '临夏县', '0,1,5,69', '644', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('645', '69', '康乐县', '0,1,5,69', '645', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('646', '69', '永靖县', '0,1,5,69', '646', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('647', '69', '广河县', '0,1,5,69', '647', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('648', '69', '和政县', '0,1,5,69', '648', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('649', '69', '东乡族自治县', '0,1,5,69', '649', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('650', '69', '积石山保安族东乡族撒拉族自治县', '0,1,5,69', '650', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('651', '70', '成县', '0,1,5,70', '651', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('652', '70', '徽县', '0,1,5,70', '652', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('653', '70', '康县', '0,1,5,70', '653', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('654', '70', '礼县', '0,1,5,70', '654', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('655', '70', '两当县', '0,1,5,70', '655', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('656', '70', '文县', '0,1,5,70', '656', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('657', '70', '西和县', '0,1,5,70', '657', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('658', '70', '宕昌县', '0,1,5,70', '658', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('659', '70', '武都区', '0,1,5,70', '659', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('660', '71', '崇信县', '0,1,5,71', '660', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('661', '71', '华亭县', '0,1,5,71', '661', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('662', '71', '静宁县', '0,1,5,71', '662', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('663', '71', '灵台县', '0,1,5,71', '663', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('664', '71', '崆峒区', '0,1,5,71', '664', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('665', '71', '庄浪县', '0,1,5,71', '665', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('666', '71', '泾川县', '0,1,5,71', '666', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('667', '72', '合水县', '0,1,5,72', '667', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('668', '72', '华池县', '0,1,5,72', '668', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('669', '72', '环县', '0,1,5,72', '669', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('670', '72', '宁县', '0,1,5,72', '670', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('671', '72', '庆城县', '0,1,5,72', '671', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('672', '72', '西峰区', '0,1,5,72', '672', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('673', '72', '镇原县', '0,1,5,72', '673', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('674', '72', '正宁县', '0,1,5,72', '674', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('675', '73', '甘谷县', '0,1,5,73', '675', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('676', '73', '秦安县', '0,1,5,73', '676', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('677', '73', '清水县', '0,1,5,73', '677', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('678', '73', '秦州区', '0,1,5,73', '678', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('679', '73', '麦积区', '0,1,5,73', '679', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('680', '73', '武山县', '0,1,5,73', '680', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('681', '73', '张家川回族自治县', '0,1,5,73', '681', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('682', '74', '古浪县', '0,1,5,74', '682', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('683', '74', '民勤县', '0,1,5,74', '683', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('684', '74', '天祝藏族自治县', '0,1,5,74', '684', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('685', '74', '凉州区', '0,1,5,74', '685', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('686', '75', '高台县', '0,1,5,75', '686', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('687', '75', '临泽县', '0,1,5,75', '687', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('688', '75', '民乐县', '0,1,5,75', '688', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('689', '75', '山丹县', '0,1,5,75', '689', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('690', '75', '肃南裕固族自治县', '0,1,5,75', '690', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('691', '75', '甘州区', '0,1,5,75', '691', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('692', '76', '从化市', '0,1,6,76', '692', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('693', '76', '天河区', '0,1,6,76', '693', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('694', '76', '东山区', '0,1,6,76', '694', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('695', '76', '白云区', '0,1,6,76', '695', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('696', '76', '海珠区', '0,1,6,76', '696', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('697', '76', '荔湾区', '0,1,6,76', '697', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('698', '76', '越秀区', '0,1,6,76', '698', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('699', '76', '黄埔区', '0,1,6,76', '699', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('700', '76', '番禺区', '0,1,6,76', '700', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('701', '76', '花都区', '0,1,6,76', '701', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('702', '76', '增城区', '0,1,6,76', '702', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('703', '76', '从化区', '0,1,6,76', '703', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('704', '76', '市郊', '0,1,6,76', '704', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('705', '77', '福田区', '0,1,6,77', '705', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('706', '77', '罗湖区', '0,1,6,77', '706', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('707', '77', '南山区', '0,1,6,77', '707', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('708', '77', '宝安区', '0,1,6,77', '708', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('709', '77', '龙岗区', '0,1,6,77', '709', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('710', '77', '盐田区', '0,1,6,77', '710', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('711', '78', '湘桥区', '0,1,6,78', '711', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('712', '78', '潮安县', '0,1,6,78', '712', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('713', '78', '饶平县', '0,1,6,78', '713', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('714', '79', '南城区', '0,1,6,79', '714', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('715', '79', '东城区', '0,1,6,79', '715', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('716', '79', '万江区', '0,1,6,79', '716', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('717', '79', '莞城区', '0,1,6,79', '717', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('718', '79', '石龙镇', '0,1,6,79', '718', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('719', '79', '虎门镇', '0,1,6,79', '719', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('720', '79', '麻涌镇', '0,1,6,79', '720', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('721', '79', '道滘镇', '0,1,6,79', '721', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('722', '79', '石碣镇', '0,1,6,79', '722', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('723', '79', '沙田镇', '0,1,6,79', '723', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('724', '79', '望牛墩镇', '0,1,6,79', '724', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('725', '79', '洪梅镇', '0,1,6,79', '725', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('726', '79', '茶山镇', '0,1,6,79', '726', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('727', '79', '寮步镇', '0,1,6,79', '727', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('728', '79', '大岭山镇', '0,1,6,79', '728', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('729', '79', '大朗镇', '0,1,6,79', '729', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('730', '79', '黄江镇', '0,1,6,79', '730', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('731', '79', '樟木头', '0,1,6,79', '731', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('732', '79', '凤岗镇', '0,1,6,79', '732', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('733', '79', '塘厦镇', '0,1,6,79', '733', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('734', '79', '谢岗镇', '0,1,6,79', '734', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('735', '79', '厚街镇', '0,1,6,79', '735', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('736', '79', '清溪镇', '0,1,6,79', '736', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('737', '79', '常平镇', '0,1,6,79', '737', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('738', '79', '桥头镇', '0,1,6,79', '738', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('739', '79', '横沥镇', '0,1,6,79', '739', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('740', '79', '东坑镇', '0,1,6,79', '740', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('741', '79', '企石镇', '0,1,6,79', '741', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('742', '79', '石排镇', '0,1,6,79', '742', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('743', '79', '长安镇', '0,1,6,79', '743', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('744', '79', '中堂镇', '0,1,6,79', '744', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('745', '79', '高埗镇', '0,1,6,79', '745', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('746', '80', '禅城区', '0,1,6,80', '746', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('747', '80', '南海区', '0,1,6,80', '747', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('748', '80', '顺德区', '0,1,6,80', '748', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('749', '80', '三水区', '0,1,6,80', '749', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('750', '80', '高明区', '0,1,6,80', '750', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('751', '81', '东源县', '0,1,6,81', '751', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('752', '81', '和平县', '0,1,6,81', '752', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('753', '81', '源城区', '0,1,6,81', '753', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('754', '81', '连平县', '0,1,6,81', '754', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('755', '81', '龙川县', '0,1,6,81', '755', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('756', '81', '紫金县', '0,1,6,81', '756', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('757', '82', '惠阳区', '0,1,6,82', '757', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('758', '82', '惠城区', '0,1,6,82', '758', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('759', '82', '大亚湾', '0,1,6,82', '759', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('760', '82', '博罗县', '0,1,6,82', '760', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('761', '82', '惠东县', '0,1,6,82', '761', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('762', '82', '龙门县', '0,1,6,82', '762', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('763', '83', '江海区', '0,1,6,83', '763', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('764', '83', '蓬江区', '0,1,6,83', '764', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('765', '83', '新会区', '0,1,6,83', '765', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('766', '83', '台山市', '0,1,6,83', '766', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('767', '83', '开平市', '0,1,6,83', '767', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('768', '83', '鹤山市', '0,1,6,83', '768', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('769', '83', '恩平市', '0,1,6,83', '769', '0', '', '0', '1', '', '', '', '', '0', 'E', '0', '');
INSERT INTO `zz_linkage` VALUES ('770', '84', '榕城区', '0,1,6,84', '770', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('771', '84', '普宁市', '0,1,6,84', '771', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('772', '84', '揭东县', '0,1,6,84', '772', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('773', '84', '揭西县', '0,1,6,84', '773', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('774', '84', '惠来县', '0,1,6,84', '774', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('775', '85', '茂南区', '0,1,6,85', '775', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('776', '85', '茂港区', '0,1,6,85', '776', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('777', '85', '高州市', '0,1,6,85', '777', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('778', '85', '化州市', '0,1,6,85', '778', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('779', '85', '信宜市', '0,1,6,85', '779', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('780', '85', '电白区', '0,1,6,85', '780', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('781', '86', '梅县区', '0,1,6,86', '781', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('782', '86', '梅江区', '0,1,6,86', '782', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('783', '86', '兴宁市', '0,1,6,86', '783', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('784', '86', '大埔县', '0,1,6,86', '784', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('785', '86', '丰顺县', '0,1,6,86', '785', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('786', '86', '五华县', '0,1,6,86', '786', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('787', '86', '平远县', '0,1,6,86', '787', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('788', '86', '蕉岭县', '0,1,6,86', '788', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('789', '87', '清城区', '0,1,6,87', '789', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('790', '87', '英德市', '0,1,6,87', '790', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('791', '87', '连州市', '0,1,6,87', '791', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('792', '87', '佛冈县', '0,1,6,87', '792', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('793', '87', '阳山县', '0,1,6,87', '793', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('794', '87', '清新区', '0,1,6,87', '794', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('795', '87', '连山壮族瑶族自治县', '0,1,6,87', '795', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('796', '87', '连南', '0,1,6,87', '796', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('797', '88', '南澳县', '0,1,6,88', '797', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('798', '88', '潮阳区', '0,1,6,88', '798', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('799', '88', '澄海区', '0,1,6,88', '799', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('800', '88', '龙湖区', '0,1,6,88', '800', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('801', '88', '金平区', '0,1,6,88', '801', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('802', '88', '濠江区', '0,1,6,88', '802', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('803', '88', '潮南区', '0,1,6,88', '803', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('804', '89', '城区', '0,1,6,89', '804', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('805', '89', '陆丰市', '0,1,6,89', '805', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('806', '89', '海丰县', '0,1,6,89', '806', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('807', '89', '陆河县', '0,1,6,89', '807', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('808', '90', '曲江县', '0,1,6,90', '808', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('809', '90', '浈江区', '0,1,6,90', '809', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('810', '90', '武江区', '0,1,6,90', '810', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('811', '90', '曲江区', '0,1,6,90', '811', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('812', '90', '乐昌市', '0,1,6,90', '812', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('813', '90', '南雄市', '0,1,6,90', '813', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('814', '90', '始兴县', '0,1,6,90', '814', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('815', '90', '仁化县', '0,1,6,90', '815', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('816', '90', '翁源县', '0,1,6,90', '816', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('817', '90', '新丰县', '0,1,6,90', '817', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('818', '90', '乳源瑶族自治县', '0,1,6,90', '818', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('819', '91', '江城区', '0,1,6,91', '819', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('820', '91', '阳春市', '0,1,6,91', '820', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('821', '91', '阳西县', '0,1,6,91', '821', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('822', '91', '阳东县', '0,1,6,91', '822', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('823', '92', '云城区', '0,1,6,92', '823', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('824', '92', '罗定市', '0,1,6,92', '824', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('825', '92', '新兴县', '0,1,6,92', '825', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('826', '92', '郁南县', '0,1,6,92', '826', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('827', '92', '云安县', '0,1,6,92', '827', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('828', '93', '赤坎区', '0,1,6,93', '828', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('829', '93', '霞山区', '0,1,6,93', '829', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('830', '93', '坡头区', '0,1,6,93', '830', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('831', '93', '麻章区', '0,1,6,93', '831', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('832', '93', '廉江市', '0,1,6,93', '832', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('833', '93', '雷州市', '0,1,6,93', '833', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('834', '93', '吴川市', '0,1,6,93', '834', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('835', '93', '遂溪县', '0,1,6,93', '835', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('836', '93', '徐闻县', '0,1,6,93', '836', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('837', '94', '肇庆市', '0,1,6,94', '837', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('838', '94', '高要市', '0,1,6,94', '838', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('839', '94', '四会市', '0,1,6,94', '839', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('840', '94', '广宁县', '0,1,6,94', '840', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('841', '94', '怀集县', '0,1,6,94', '841', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('842', '94', '封开县', '0,1,6,94', '842', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('843', '94', '德庆县', '0,1,6,94', '843', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('844', '95', '石岐街道', '0,1,6,95', '844', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('845', '95', '东区街道', '0,1,6,95', '845', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('846', '95', '西区街道', '0,1,6,95', '846', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('847', '95', '环城街道', '0,1,6,95', '847', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('848', '95', '中山港街道', '0,1,6,95', '848', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('849', '95', '五桂山街道', '0,1,6,95', '849', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('850', '96', '香洲区', '0,1,6,96', '850', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('851', '96', '斗门区', '0,1,6,96', '851', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('852', '96', '金湾区', '0,1,6,96', '852', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('853', '97', '邕宁区', '0,1,7,97', '853', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('854', '97', '青秀区', '0,1,7,97', '854', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('855', '97', '兴宁区', '0,1,7,97', '855', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('856', '97', '良庆区', '0,1,7,97', '856', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('857', '97', '西乡塘区', '0,1,7,97', '857', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('858', '97', '江南区', '0,1,7,97', '858', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('859', '97', '武鸣区', '0,1,7,97', '859', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('860', '97', '隆安县', '0,1,7,97', '860', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('861', '97', '马山县', '0,1,7,97', '861', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('862', '97', '上林县', '0,1,7,97', '862', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('863', '97', '宾阳县', '0,1,7,97', '863', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('864', '97', '横县', '0,1,7,97', '864', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('865', '98', '秀峰区', '0,1,7,98', '865', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('866', '98', '叠彩区', '0,1,7,98', '866', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('867', '98', '象山区', '0,1,7,98', '867', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('868', '98', '七星区', '0,1,7,98', '868', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('869', '98', '雁山区', '0,1,7,98', '869', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('870', '98', '阳朔县', '0,1,7,98', '870', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('871', '98', '临桂区', '0,1,7,98', '871', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('872', '98', '灵川县', '0,1,7,98', '872', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('873', '98', '全州县', '0,1,7,98', '873', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('874', '98', '平乐县', '0,1,7,98', '874', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('875', '98', '兴安县', '0,1,7,98', '875', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('876', '98', '灌阳县', '0,1,7,98', '876', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('877', '98', '荔浦县', '0,1,7,98', '877', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('878', '98', '资源县', '0,1,7,98', '878', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('879', '98', '永福县', '0,1,7,98', '879', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('880', '98', '龙胜各族自治县', '0,1,7,98', '880', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('881', '98', '恭城瑶族自治县', '0,1,7,98', '881', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('882', '99', '右江区', '0,1,7,99', '882', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('883', '99', '凌云县', '0,1,7,99', '883', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('884', '99', '平果县', '0,1,7,99', '884', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('885', '99', '西林县', '0,1,7,99', '885', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('886', '99', '乐业县', '0,1,7,99', '886', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('887', '99', '德保县', '0,1,7,99', '887', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('888', '99', '田林县', '0,1,7,99', '888', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('889', '99', '田阳县', '0,1,7,99', '889', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('890', '99', '靖西县', '0,1,7,99', '890', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('891', '99', '田东县', '0,1,7,99', '891', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('892', '99', '那坡县', '0,1,7,99', '892', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('893', '99', '隆林各族自治县', '0,1,7,99', '893', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('894', '100', '海城区', '0,1,7,100', '894', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('895', '100', '银海区', '0,1,7,100', '895', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('896', '100', '铁山港区', '0,1,7,100', '896', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('897', '100', '合浦县', '0,1,7,100', '897', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('898', '101', '江州区', '0,1,7,101', '898', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('899', '101', '凭祥市', '0,1,7,101', '899', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('900', '101', '宁明县', '0,1,7,101', '900', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('901', '101', '扶绥县', '0,1,7,101', '901', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('902', '101', '龙州县', '0,1,7,101', '902', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('903', '101', '大新县', '0,1,7,101', '903', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('904', '101', '天等县', '0,1,7,101', '904', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('905', '102', '港口区', '0,1,7,102', '905', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('906', '102', '防城区', '0,1,7,102', '906', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('907', '102', '东兴市', '0,1,7,102', '907', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('908', '102', '上思县', '0,1,7,102', '908', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('909', '103', '港北区', '0,1,7,103', '909', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('910', '103', '港南区', '0,1,7,103', '910', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('911', '103', '覃塘区', '0,1,7,103', '911', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('912', '103', '桂平市', '0,1,7,103', '912', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('913', '103', '平南县', '0,1,7,103', '913', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('914', '104', '金城江区', '0,1,7,104', '914', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('915', '104', '宜州市', '0,1,7,104', '915', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('916', '104', '天峨县', '0,1,7,104', '916', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('917', '104', '凤山县', '0,1,7,104', '917', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('918', '104', '南丹县', '0,1,7,104', '918', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('919', '104', '东兰县', '0,1,7,104', '919', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('920', '104', '都安瑶族自治县', '0,1,7,104', '920', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('921', '104', '罗城仫佬族自治县', '0,1,7,104', '921', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('922', '104', '巴马瑶族自治县', '0,1,7,104', '922', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('923', '104', '环江毛南族自治县', '0,1,7,104', '923', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('924', '104', '大化瑶族自治县', '0,1,7,104', '924', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('925', '105', '八步区', '0,1,7,105', '925', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('926', '105', '钟山县', '0,1,7,105', '926', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('927', '105', '昭平县', '0,1,7,105', '927', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('928', '105', '富川瑶族自治县', '0,1,7,105', '928', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('929', '106', '兴宾区', '0,1,7,106', '929', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('930', '106', '合山市', '0,1,7,106', '930', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('931', '106', '象州县', '0,1,7,106', '931', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('932', '106', '武宣县', '0,1,7,106', '932', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('933', '106', '忻城县', '0,1,7,106', '933', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('934', '106', '金秀瑶族自治县', '0,1,7,106', '934', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('935', '107', '城中区', '0,1,7,107', '935', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('936', '107', '鱼峰区', '0,1,7,107', '936', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('937', '107', '柳北区', '0,1,7,107', '937', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('938', '107', '柳南区', '0,1,7,107', '938', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('939', '107', '柳江县', '0,1,7,107', '939', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('940', '107', '柳城县', '0,1,7,107', '940', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('941', '107', '鹿寨县', '0,1,7,107', '941', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('942', '107', '融安县', '0,1,7,107', '942', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('943', '107', '融水苗族自治县', '0,1,7,107', '943', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('944', '107', '三江侗族自治县', '0,1,7,107', '944', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('945', '108', '钦南区', '0,1,7,108', '945', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('946', '108', '钦北区', '0,1,7,108', '946', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('947', '108', '灵山县', '0,1,7,108', '947', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('948', '108', '浦北县', '0,1,7,108', '948', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('949', '109', '万秀区', '0,1,7,109', '949', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('950', '109', '蝶山区', '0,1,7,109', '950', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('951', '109', '长洲区', '0,1,7,109', '951', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('952', '109', '岑溪市', '0,1,7,109', '952', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('953', '109', '苍梧县', '0,1,7,109', '953', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('954', '109', '藤县', '0,1,7,109', '954', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('955', '109', '蒙山县', '0,1,7,109', '955', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('956', '110', '玉州区', '0,1,7,110', '956', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('957', '110', '北流市', '0,1,7,110', '957', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('958', '110', '容县', '0,1,7,110', '958', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('959', '110', '陆川县', '0,1,7,110', '959', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('960', '110', '博白县', '0,1,7,110', '960', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('961', '110', '兴业县', '0,1,7,110', '961', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('962', '111', '南明区', '0,1,8,111', '962', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('963', '111', '云岩区', '0,1,8,111', '963', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('964', '111', '花溪区', '0,1,8,111', '964', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('965', '111', '乌当区', '0,1,8,111', '965', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('966', '111', '白云区', '0,1,8,111', '966', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('967', '111', '小河区', '0,1,8,111', '967', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('968', '111', '金阳新区', '0,1,8,111', '968', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('969', '111', '新天园区', '0,1,8,111', '969', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('970', '111', '清镇市', '0,1,8,111', '970', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('971', '111', '开阳县', '0,1,8,111', '971', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('972', '111', '修文县', '0,1,8,111', '972', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('973', '111', '息烽县', '0,1,8,111', '973', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('974', '112', '西秀区', '0,1,8,112', '974', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('975', '112', '关岭苗族布依族自治县', '0,1,8,112', '975', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('976', '112', '镇宁布依族苗族自治县', '0,1,8,112', '976', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('977', '112', '紫云苗族布依族自治县', '0,1,8,112', '977', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('978', '112', '平坝区', '0,1,8,112', '978', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('979', '112', '普定县', '0,1,8,112', '979', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('980', '113', '毕节市', '0,1,8,113', '980', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('981', '113', '大方县', '0,1,8,113', '981', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('982', '113', '黔西县', '0,1,8,113', '982', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('983', '113', '金沙县', '0,1,8,113', '983', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('984', '113', '织金县', '0,1,8,113', '984', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('985', '113', '纳雍县', '0,1,8,113', '985', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('986', '113', '赫章县', '0,1,8,113', '986', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('987', '113', '威宁彝族回族苗族自治县', '0,1,8,113', '987', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('988', '114', '钟山区', '0,1,8,114', '988', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('989', '114', '六枝特区', '0,1,8,114', '989', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('990', '114', '水城县', '0,1,8,114', '990', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('991', '114', '盘县', '0,1,8,114', '991', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('992', '115', '凯里市', '0,1,8,115', '992', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('993', '115', '黄平县', '0,1,8,115', '993', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('994', '115', '施秉县', '0,1,8,115', '994', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('995', '115', '三穗县', '0,1,8,115', '995', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('996', '115', '镇远县', '0,1,8,115', '996', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('997', '115', '岑巩县', '0,1,8,115', '997', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('998', '115', '天柱县', '0,1,8,115', '998', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('999', '115', '锦屏县', '0,1,8,115', '999', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1000', '115', '剑河县', '0,1,8,115', '1000', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1001', '115', '台江县', '0,1,8,115', '1001', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1002', '115', '黎平县', '0,1,8,115', '1002', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1003', '115', '榕江县', '0,1,8,115', '1003', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('1004', '115', '从江县', '0,1,8,115', '1004', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1005', '115', '雷山县', '0,1,8,115', '1005', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1006', '115', '麻江县', '0,1,8,115', '1006', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('1007', '115', '丹寨县', '0,1,8,115', '1007', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1008', '116', '都匀市', '0,1,8,116', '1008', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1009', '116', '福泉市', '0,1,8,116', '1009', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1010', '116', '荔波县', '0,1,8,116', '1010', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1011', '116', '贵定县', '0,1,8,116', '1011', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1012', '116', '瓮安县', '0,1,8,116', '1012', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1013', '116', '独山县', '0,1,8,116', '1013', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1014', '116', '平塘县', '0,1,8,116', '1014', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('1015', '116', '罗甸县', '0,1,8,116', '1015', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1016', '116', '长顺县', '0,1,8,116', '1016', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1017', '116', '龙里县', '0,1,8,116', '1017', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1018', '116', '惠水县', '0,1,8,116', '1018', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1019', '116', '三都水族自治县', '0,1,8,116', '1019', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1020', '117', '兴义市', '0,1,8,117', '1020', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1021', '117', '兴仁县', '0,1,8,117', '1021', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1022', '117', '普安县', '0,1,8,117', '1022', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('1023', '117', '晴隆县', '0,1,8,117', '1023', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1024', '117', '贞丰县', '0,1,8,117', '1024', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1025', '117', '望谟县', '0,1,8,117', '1025', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1026', '117', '册亨县', '0,1,8,117', '1026', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1027', '117', '安龙县', '0,1,8,117', '1027', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('1028', '118', '铜仁市', '0,1,8,118', '1028', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1029', '118', '江口县', '0,1,8,118', '1029', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1030', '118', '石阡县', '0,1,8,118', '1030', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1031', '118', '思南县', '0,1,8,118', '1031', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1032', '118', '德江县', '0,1,8,118', '1032', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1033', '118', '玉屏侗族自治县', '0,1,8,118', '1033', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1034', '118', '印江土家族苗族自治县', '0,1,8,118', '1034', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1035', '118', '沿河土家族自治县', '0,1,8,118', '1035', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1036', '118', '松桃苗族自治', '0,1,8,118', '1036', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1037', '118', '万山特区', '0,1,8,118', '1037', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1038', '119', '红花岗区', '0,1,8,119', '1038', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1039', '119', '务川县仡佬族苗族自治县', '0,1,8,119', '1039', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1040', '119', '道真仡佬族苗族自治县', '0,1,8,119', '1040', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1041', '119', '汇川区', '0,1,8,119', '1041', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1042', '119', '赤水市', '0,1,8,119', '1042', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1043', '119', '仁怀市', '0,1,8,119', '1043', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('1044', '119', '遵义县', '0,1,8,119', '1044', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1045', '119', '桐梓县', '0,1,8,119', '1045', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1046', '119', '绥阳县', '0,1,8,119', '1046', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1047', '119', '正安县', '0,1,8,119', '1047', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1048', '119', '凤冈县', '0,1,8,119', '1048', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1050', '119', '余庆县', '0,1,8,119', '1050', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1051', '119', '习水县', '0,1,8,119', '1051', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1052', '119', '道真', '0,1,8,119', '1052', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1053', '119', '务川', '0,1,8,119', '1053', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1054', '120', '秀英区', '0,1,9,120', '1054', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1055', '120', '龙华区', '0,1,9,120', '1055', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1056', '120', '琼山区', '0,1,9,120', '1056', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1057', '120', '美兰区', '0,1,9,120', '1057', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('1058', '137', '市区', '0,1,9,137', '1058', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1059', '137', '洋浦开发区', '0,1,9,137', '1059', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1060', '137', '那大镇', '0,1,9,137', '1060', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1061', '137', '王五镇', '0,1,9,137', '1061', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1062', '137', '雅星镇', '0,1,9,137', '1062', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1063', '137', '大成镇', '0,1,9,137', '1063', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1064', '137', '中和镇', '0,1,9,137', '1064', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1065', '137', '峨蔓镇', '0,1,9,137', '1065', '0', '', '0', '1', '', '', '', '', '0', 'E', '0', '');
INSERT INTO `zz_linkage` VALUES ('1066', '137', '南丰镇', '0,1,9,137', '1066', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1067', '137', '白马井镇', '0,1,9,137', '1067', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1068', '137', '兰洋镇', '0,1,9,137', '1068', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1069', '137', '和庆镇', '0,1,9,137', '1069', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1070', '137', '海头镇', '0,1,9,137', '1070', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1071', '137', '排浦镇', '0,1,9,137', '1071', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('1072', '137', '东成镇', '0,1,9,137', '1072', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1073', '137', '光村镇', '0,1,9,137', '1073', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1074', '137', '木棠镇', '0,1,9,137', '1074', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('1075', '137', '新州镇', '0,1,9,137', '1075', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1076', '137', '三都镇', '0,1,9,137', '1076', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1077', '137', '其他', '0,1,9,137', '1077', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1078', '138', '长安区', '0,1,10,138', '1078', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1079', '138', '桥东区', '0,1,10,138', '1079', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1080', '138', '桥西区', '0,1,10,138', '1080', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1081', '138', '新华区', '0,1,10,138', '1081', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1082', '138', '裕华区', '0,1,10,138', '1082', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1083', '138', '井陉矿区', '0,1,10,138', '1083', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1084', '138', '高新区', '0,1,10,138', '1084', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1085', '138', '辛集市', '0,1,10,138', '1085', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1086', '138', '藁城市', '0,1,10,138', '1086', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1087', '138', '晋州市', '0,1,10,138', '1087', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1088', '138', '新乐市', '0,1,10,138', '1088', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1089', '138', '鹿泉市', '0,1,10,138', '1089', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1090', '138', '井陉县', '0,1,10,138', '1090', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1091', '138', '正定县', '0,1,10,138', '1091', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1092', '138', '栾城区', '0,1,10,138', '1092', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1093', '138', '行唐县', '0,1,10,138', '1093', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1094', '138', '灵寿县', '0,1,10,138', '1094', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1095', '138', '高邑县', '0,1,10,138', '1095', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1096', '138', '深泽县', '0,1,10,138', '1096', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1097', '138', '赞皇县', '0,1,10,138', '1097', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1098', '138', '无极县', '0,1,10,138', '1098', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1099', '138', '平山县', '0,1,10,138', '1099', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('1100', '138', '元氏县', '0,1,10,138', '1100', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1101', '138', '赵县', '0,1,10,138', '1101', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1102', '139', '新市区', '0,1,10,139', '1102', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1103', '139', '南市区', '0,1,10,139', '1103', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1104', '139', '北市区', '0,1,10,139', '1104', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1105', '139', '涿州市', '0,1,10,139', '1105', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1106', '139', '定州市', '0,1,10,139', '1106', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1107', '139', '安国市', '0,1,10,139', '1107', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('1108', '139', '高碑店市', '0,1,10,139', '1108', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1109', '139', '满城县', '0,1,10,139', '1109', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('1110', '139', '清苑县', '0,1,10,139', '1110', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1111', '139', '涞水县', '0,1,10,139', '1111', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1112', '139', '阜平县', '0,1,10,139', '1112', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1113', '139', '徐水县', '0,1,10,139', '1113', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1114', '139', '定兴县', '0,1,10,139', '1114', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1115', '139', '唐县', '0,1,10,139', '1115', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1116', '139', '高阳县', '0,1,10,139', '1116', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1117', '139', '容城县', '0,1,10,139', '1117', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('1118', '139', '涞源县', '0,1,10,139', '1118', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1119', '139', '望都县', '0,1,10,139', '1119', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1120', '139', '安新县', '0,1,10,139', '1120', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('1121', '139', '易县', '0,1,10,139', '1121', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1122', '139', '曲阳县', '0,1,10,139', '1122', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1123', '139', '蠡县', '0,1,10,139', '1123', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1124', '139', '顺平县', '0,1,10,139', '1124', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1125', '139', '博野县', '0,1,10,139', '1125', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1126', '139', '雄县', '0,1,10,139', '1126', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1127', '140', '运河区', '0,1,10,140', '1127', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1128', '140', '新华区', '0,1,10,140', '1128', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1129', '140', '泊头市', '0,1,10,140', '1129', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1130', '140', '任丘市', '0,1,10,140', '1130', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('1131', '140', '黄骅市', '0,1,10,140', '1131', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1132', '140', '河间市', '0,1,10,140', '1132', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1133', '140', '沧县', '0,1,10,140', '1133', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1134', '140', '青县', '0,1,10,140', '1134', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1135', '140', '东光县', '0,1,10,140', '1135', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1136', '140', '海兴县', '0,1,10,140', '1136', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1137', '140', '盐山县', '0,1,10,140', '1137', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1138', '140', '肃宁县', '0,1,10,140', '1138', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1139', '140', '南皮县', '0,1,10,140', '1139', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1140', '140', '吴桥县', '0,1,10,140', '1140', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1141', '140', '献县', '0,1,10,140', '1141', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1142', '140', '孟村回族自治县', '0,1,10,140', '1142', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('1143', '141', '双桥区', '0,1,10,141', '1143', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1144', '141', '双滦区', '0,1,10,141', '1144', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1145', '141', '鹰手营子矿区', '0,1,10,141', '1145', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1146', '141', '承德县', '0,1,10,141', '1146', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1147', '141', '兴隆县', '0,1,10,141', '1147', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1148', '141', '平泉县', '0,1,10,141', '1148', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('1149', '141', '滦平县', '0,1,10,141', '1149', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1150', '141', '隆化县', '0,1,10,141', '1150', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1151', '141', '丰宁满族自治县', '0,1,10,141', '1151', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1152', '141', '宽城满族自治县', '0,1,10,141', '1152', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('1153', '141', '围场满族蒙古族自治县', '0,1,10,141', '1153', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1154', '142', '从台区', '0,1,10,142', '1154', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1155', '142', '复兴区', '0,1,10,142', '1155', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1156', '142', '邯山区', '0,1,10,142', '1156', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1157', '142', '峰峰矿区', '0,1,10,142', '1157', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1158', '142', '武安市', '0,1,10,142', '1158', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1159', '142', '邯郸县', '0,1,10,142', '1159', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1160', '142', '临漳县', '0,1,10,142', '1160', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1161', '142', '成安县', '0,1,10,142', '1161', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1162', '142', '大名县', '0,1,10,142', '1162', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1163', '142', '涉县', '0,1,10,142', '1163', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1164', '142', '磁县', '0,1,10,142', '1164', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1165', '142', '肥乡县', '0,1,10,142', '1165', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1166', '142', '永年县', '0,1,10,142', '1166', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1167', '142', '邱县', '0,1,10,142', '1167', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1168', '142', '鸡泽县', '0,1,10,142', '1168', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1169', '142', '广平县', '0,1,10,142', '1169', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1170', '142', '馆陶县', '0,1,10,142', '1170', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1171', '142', '魏县', '0,1,10,142', '1171', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1172', '142', '曲周县', '0,1,10,142', '1172', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1173', '143', '桃城区', '0,1,10,143', '1173', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1174', '143', '冀州市', '0,1,10,143', '1174', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1175', '143', '深州市', '0,1,10,143', '1175', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1176', '143', '枣强县', '0,1,10,143', '1176', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1177', '143', '武邑县', '0,1,10,143', '1177', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1178', '143', '武强县', '0,1,10,143', '1178', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1179', '143', '饶阳县', '0,1,10,143', '1179', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('1180', '143', '安平县', '0,1,10,143', '1180', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('1181', '143', '故城县', '0,1,10,143', '1181', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1182', '143', '景县', '0,1,10,143', '1182', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1183', '143', '阜城县', '0,1,10,143', '1183', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1184', '144', '安次区', '0,1,10,144', '1184', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('1185', '144', '广阳区', '0,1,10,144', '1185', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1186', '144', '霸州市', '0,1,10,144', '1186', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1187', '144', '三河市', '0,1,10,144', '1187', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1188', '144', '固安县', '0,1,10,144', '1188', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1189', '144', '永清县', '0,1,10,144', '1189', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1190', '144', '香河县', '0,1,10,144', '1190', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1191', '144', '大城县', '0,1,10,144', '1191', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1192', '144', '文安县', '0,1,10,144', '1192', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1193', '144', '大厂回族自治县', '0,1,10,144', '1193', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1194', '145', '海港区', '0,1,10,145', '1194', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1195', '145', '山海关区', '0,1,10,145', '1195', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1196', '145', '北戴河区', '0,1,10,145', '1196', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1197', '145', '昌黎县', '0,1,10,145', '1197', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1198', '145', '抚宁县', '0,1,10,145', '1198', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1199', '145', '卢龙县', '0,1,10,145', '1199', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1201', '146', '路北区', '0,1,10,146', '1201', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1202', '146', '路南区', '0,1,10,146', '1202', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1203', '146', '古冶区', '0,1,10,146', '1203', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1204', '146', '开平区', '0,1,10,146', '1204', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('1205', '146', '丰南区', '0,1,10,146', '1205', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1206', '146', '丰润区', '0,1,10,146', '1206', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1207', '146', '遵化市', '0,1,10,146', '1207', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1208', '146', '迁安市', '0,1,10,146', '1208', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1209', '146', '滦县', '0,1,10,146', '1209', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1210', '146', '滦南县', '0,1,10,146', '1210', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1211', '146', '乐亭县', '0,1,10,146', '1211', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1212', '146', '迁西县', '0,1,10,146', '1212', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1213', '146', '玉田县', '0,1,10,146', '1213', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1214', '146', '唐海县', '0,1,10,146', '1214', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1215', '147', '桥东区', '0,1,10,147', '1215', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1216', '147', '桥西区', '0,1,10,147', '1216', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1217', '147', '南宫市', '0,1,10,147', '1217', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1218', '147', '沙河市', '0,1,10,147', '1218', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1219', '147', '邢台县', '0,1,10,147', '1219', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1220', '147', '临城县', '0,1,10,147', '1220', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1221', '147', '内丘县', '0,1,10,147', '1221', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1222', '147', '柏乡县', '0,1,10,147', '1222', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1223', '147', '隆尧县', '0,1,10,147', '1223', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1224', '147', '任县', '0,1,10,147', '1224', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('1225', '147', '南和县', '0,1,10,147', '1225', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1226', '147', '宁晋县', '0,1,10,147', '1226', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1227', '147', '巨鹿县', '0,1,10,147', '1227', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1228', '147', '新河县', '0,1,10,147', '1228', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1229', '147', '广宗县', '0,1,10,147', '1229', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1230', '147', '平乡县', '0,1,10,147', '1230', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('1231', '147', '威县', '0,1,10,147', '1231', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1232', '147', '清河县', '0,1,10,147', '1232', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1233', '147', '临西县', '0,1,10,147', '1233', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1234', '148', '桥西区', '0,1,10,148', '1234', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1235', '148', '桥东区', '0,1,10,148', '1235', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1236', '148', '宣化区', '0,1,10,148', '1236', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1237', '148', '下花园区', '0,1,10,148', '1237', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1238', '148', '宣化县', '0,1,10,148', '1238', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1239', '148', '张北县', '0,1,10,148', '1239', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1240', '148', '康保县', '0,1,10,148', '1240', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('1241', '148', '沽源县', '0,1,10,148', '1241', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1242', '148', '尚义县', '0,1,10,148', '1242', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1243', '148', '蔚县', '0,1,10,148', '1243', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1244', '148', '阳原县', '0,1,10,148', '1244', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1245', '148', '怀安县', '0,1,10,148', '1245', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1246', '148', '万全县', '0,1,10,148', '1246', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1247', '148', '怀来县', '0,1,10,148', '1247', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1248', '148', '涿鹿县', '0,1,10,148', '1248', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1249', '148', '赤城县', '0,1,10,148', '1249', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1250', '148', '崇礼县', '0,1,10,148', '1250', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1251', '149', '金水区', '0,1,11,149', '1251', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1252', '149', '邙山区', '0,1,11,149', '1252', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('1253', '149', '二七区', '0,1,11,149', '1253', '0', '', '0', '1', '', '', '', '', '0', 'E', '0', '');
INSERT INTO `zz_linkage` VALUES ('1254', '149', '管城回族区', '0,1,11,149', '1254', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1255', '149', '中原区', '0,1,11,149', '1255', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1256', '149', '上街区', '0,1,11,149', '1256', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1257', '149', '惠济区', '0,1,11,149', '1257', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1258', '149', '郑东新区', '0,1,11,149', '1258', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1259', '149', '经济技术开发区', '0,1,11,149', '1259', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1260', '149', '高新开发区', '0,1,11,149', '1260', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1261', '149', '出口加工区', '0,1,11,149', '1261', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1262', '149', '巩义市', '0,1,11,149', '1262', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1263', '149', '荥阳市', '0,1,11,149', '1263', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1264', '149', '新密市', '0,1,11,149', '1264', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1265', '149', '新郑市', '0,1,11,149', '1265', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1266', '149', '登封市', '0,1,11,149', '1266', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1267', '149', '中牟县', '0,1,11,149', '1267', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1268', '150', '西工区', '0,1,11,150', '1268', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1269', '150', '老城区', '0,1,11,150', '1269', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1270', '150', '涧西区', '0,1,11,150', '1270', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1271', '150', '瀍河回族区', '0,1,11,150', '1271', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1272', '150', '洛龙区', '0,1,11,150', '1272', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1273', '150', '吉利区', '0,1,11,150', '1273', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1274', '150', '偃师市', '0,1,11,150', '1274', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1275', '150', '孟津县', '0,1,11,150', '1275', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('1276', '150', '新安县', '0,1,11,150', '1276', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1277', '150', '栾川县', '0,1,11,150', '1277', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1278', '150', '嵩县', '0,1,11,150', '1278', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1279', '150', '汝阳县', '0,1,11,150', '1279', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('1280', '150', '宜阳县', '0,1,11,150', '1280', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1281', '150', '洛宁县', '0,1,11,150', '1281', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1282', '150', '伊川县', '0,1,11,150', '1282', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1283', '151', '鼓楼区', '0,1,11,151', '1283', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1284', '151', '龙亭区', '0,1,11,151', '1284', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1285', '151', '顺河回族区', '0,1,11,151', '1285', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1286', '151', '金明区', '0,1,11,151', '1286', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1287', '151', '禹王台区', '0,1,11,151', '1287', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1288', '151', '杞县', '0,1,11,151', '1288', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1289', '151', '通许县', '0,1,11,151', '1289', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1290', '151', '尉氏县', '0,1,11,151', '1290', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1291', '151', '开封县', '0,1,11,151', '1291', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('1292', '151', '兰考县', '0,1,11,151', '1292', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1293', '152', '北关区', '0,1,11,152', '1293', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1294', '152', '文峰区', '0,1,11,152', '1294', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1295', '152', '殷都区', '0,1,11,152', '1295', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1296', '152', '龙安区', '0,1,11,152', '1296', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1297', '152', '林州市', '0,1,11,152', '1297', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1298', '152', '安阳县', '0,1,11,152', '1298', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('1299', '152', '汤阴县', '0,1,11,152', '1299', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1300', '152', '滑县', '0,1,11,152', '1300', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1301', '152', '内黄县', '0,1,11,152', '1301', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1302', '153', '淇滨区', '0,1,11,153', '1302', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1303', '153', '山城区', '0,1,11,153', '1303', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1304', '153', '鹤山区', '0,1,11,153', '1304', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1305', '153', '浚县', '0,1,11,153', '1305', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1306', '153', '淇县', '0,1,11,153', '1306', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1307', '154', '济源市', '0,1,11,154', '1307,4266,4267,4268,4269,4270,4271,4272,4273,4274,4275', '1', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1308', '155', '解放区', '0,1,11,155', '1308', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1309', '155', '中站区', '0,1,11,155', '1309', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1310', '155', '马村区', '0,1,11,155', '1310', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('1311', '155', '山阳区', '0,1,11,155', '1311', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1312', '155', '沁阳市', '0,1,11,155', '1312', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1313', '155', '孟州市', '0,1,11,155', '1313', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('1314', '155', '修武县', '0,1,11,155', '1314', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1315', '155', '博爱县', '0,1,11,155', '1315', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1316', '155', '武陟县', '0,1,11,155', '1316', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1317', '155', '温县', '0,1,11,155', '1317', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1318', '156', '卧龙区', '0,1,11,156', '1318', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1319', '156', '宛城区', '0,1,11,156', '1319', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1320', '156', '邓州市', '0,1,11,156', '1320', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1321', '156', '南召县', '0,1,11,156', '1321', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1322', '156', '方城县', '0,1,11,156', '1322', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1323', '156', '西峡县', '0,1,11,156', '1323', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1324', '156', '镇平县', '0,1,11,156', '1324', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1325', '156', '内乡县', '0,1,11,156', '1325', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1326', '156', '淅川县', '0,1,11,156', '1326', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1327', '156', '社旗县', '0,1,11,156', '1327', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1328', '156', '唐河县', '0,1,11,156', '1328', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1329', '156', '新野县', '0,1,11,156', '1329', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1330', '156', '桐柏县', '0,1,11,156', '1330', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1331', '157', '新华区', '0,1,11,157', '1331', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1332', '157', '卫东区', '0,1,11,157', '1332', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1333', '157', '湛河区', '0,1,11,157', '1333', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1334', '157', '石龙区', '0,1,11,157', '1334', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1335', '157', '舞钢市', '0,1,11,157', '1335', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1336', '157', '汝州市', '0,1,11,157', '1336', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('1337', '157', '宝丰县', '0,1,11,157', '1337', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1338', '157', '叶县', '0,1,11,157', '1338', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1339', '157', '鲁山县', '0,1,11,157', '1339', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1340', '157', '郏县', '0,1,11,157', '1340', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1341', '158', '湖滨区', '0,1,11,158', '1341', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1342', '158', '义马市', '0,1,11,158', '1342', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1343', '158', '灵宝市', '0,1,11,158', '1343', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1344', '158', '渑池县', '0,1,11,158', '1344', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1345', '158', '陕县', '0,1,11,158', '1345', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1346', '158', '卢氏县', '0,1,11,158', '1346', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1347', '159', '梁园区', '0,1,11,159', '1347', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1348', '159', '睢阳区', '0,1,11,159', '1348', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1349', '159', '永城市', '0,1,11,159', '1349', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1350', '159', '民权县', '0,1,11,159', '1350', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('1351', '159', '睢县', '0,1,11,159', '1351', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1352', '159', '宁陵县', '0,1,11,159', '1352', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1353', '159', '虞城县', '0,1,11,159', '1353', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1354', '159', '柘城县', '0,1,11,159', '1354', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1355', '159', '夏邑县', '0,1,11,159', '1355', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1356', '160', '卫滨区', '0,1,11,160', '1356', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1357', '160', '红旗区', '0,1,11,160', '1357', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1358', '160', '凤泉区', '0,1,11,160', '1358', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1359', '160', '牧野区', '0,1,11,160', '1359', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('1360', '160', '卫辉市', '0,1,11,160', '1360', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1361', '160', '辉县市', '0,1,11,160', '1361', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1362', '160', '新乡县', '0,1,11,160', '1362', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1363', '160', '获嘉县', '0,1,11,160', '1363', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1364', '160', '原阳县', '0,1,11,160', '1364', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1365', '160', '延津县', '0,1,11,160', '1365', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1366', '160', '封丘县', '0,1,11,160', '1366', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1367', '160', '长垣县', '0,1,11,160', '1367', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1368', '161', '浉河区', '0,1,11,161', '1368', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1369', '161', '平桥区', '0,1,11,161', '1369', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('1370', '161', '罗山县', '0,1,11,161', '1370', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1371', '161', '光山县', '0,1,11,161', '1371', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1372', '161', '新县', '0,1,11,161', '1372', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1373', '161', '商城县', '0,1,11,161', '1373', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1374', '161', '固始县', '0,1,11,161', '1374', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1375', '161', '潢川县', '0,1,11,161', '1375', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1376', '161', '淮滨县', '0,1,11,161', '1376', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1377', '161', '息县', '0,1,11,161', '1377', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1378', '162', '魏都区', '0,1,11,162', '1378', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1379', '162', '禹州市', '0,1,11,162', '1379', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1380', '162', '长葛市', '0,1,11,162', '1380', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1381', '162', '许昌县', '0,1,11,162', '1381', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1382', '162', '鄢陵县', '0,1,11,162', '1382', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1383', '162', '襄城县', '0,1,11,162', '1383', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1384', '163', '川汇区', '0,1,11,163', '1384', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1385', '163', '项城市', '0,1,11,163', '1385', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1386', '163', '扶沟县', '0,1,11,163', '1386', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1387', '163', '西华县', '0,1,11,163', '1387', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1388', '163', '商水县', '0,1,11,163', '1388', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1389', '163', '沈丘县', '0,1,11,163', '1389', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1390', '163', '郸城县', '0,1,11,163', '1390', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1391', '163', '淮阳县', '0,1,11,163', '1391', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1392', '163', '太康县', '0,1,11,163', '1392', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1393', '163', '鹿邑县', '0,1,11,163', '1393', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1394', '164', '驿城区', '0,1,11,164', '1394', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1395', '164', '西平县', '0,1,11,164', '1395', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1396', '164', '上蔡县', '0,1,11,164', '1396', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1397', '164', '平舆县', '0,1,11,164', '1397', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('1398', '164', '正阳县', '0,1,11,164', '1398', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1399', '164', '确山县', '0,1,11,164', '1399', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1400', '164', '泌阳县', '0,1,11,164', '1400', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('1401', '164', '汝南县', '0,1,11,164', '1401', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('1402', '164', '遂平县', '0,1,11,164', '1402', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1403', '164', '新蔡县', '0,1,11,164', '1403', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1404', '165', '郾城区', '0,1,11,165', '1404', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1405', '165', '源汇区', '0,1,11,165', '1405', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1406', '165', '召陵区', '0,1,11,165', '1406', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1407', '165', '舞阳县', '0,1,11,165', '1407', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1408', '165', '临颍县', '0,1,11,165', '1408', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1409', '166', '华龙区', '0,1,11,166', '1409', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1410', '166', '清丰县', '0,1,11,166', '1410', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1411', '166', '南乐县', '0,1,11,166', '1411', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1412', '166', '范县', '0,1,11,166', '1412', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1413', '166', '台前县', '0,1,11,166', '1413', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1414', '166', '濮阳县', '0,1,11,166', '1414', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('1415', '167', '道里区', '0,1,12,167', '1415', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1416', '167', '南岗区', '0,1,12,167', '1416', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1417', '167', '动力区', '0,1,12,167', '1417', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1418', '167', '平房区', '0,1,12,167', '1418', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('1419', '167', '香坊区', '0,1,12,167', '1419', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1420', '167', '太平区', '0,1,12,167', '1420', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1421', '167', '道外区', '0,1,12,167', '1421', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1422', '167', '阿城区', '0,1,12,167', '1422', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('1423', '167', '呼兰区', '0,1,12,167', '1423', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1424', '167', '松北区', '0,1,12,167', '1424', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1425', '167', '尚志市', '0,1,12,167', '1425', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1426', '167', '双城区', '0,1,12,167', '1426', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1427', '167', '五常市', '0,1,12,167', '1427', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1428', '167', '方正县', '0,1,12,167', '1428', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1429', '167', '宾县', '0,1,12,167', '1429', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1430', '167', '依兰县', '0,1,12,167', '1430', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1431', '167', '巴彦县', '0,1,12,167', '1431', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1432', '167', '通河县', '0,1,12,167', '1432', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1433', '167', '木兰县', '0,1,12,167', '1433', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('1434', '167', '延寿县', '0,1,12,167', '1434', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1435', '168', '萨尔图区', '0,1,12,168', '1435', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1436', '168', '红岗区', '0,1,12,168', '1436', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1437', '168', '龙凤区', '0,1,12,168', '1437', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1438', '168', '让胡路区', '0,1,12,168', '1438', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('1439', '168', '大同区', '0,1,12,168', '1439', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1440', '168', '肇州县', '0,1,12,168', '1440', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1441', '168', '肇源县', '0,1,12,168', '1441', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1442', '168', '林甸县', '0,1,12,168', '1442', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1443', '168', '杜尔伯特蒙古族自治县', '0,1,12,168', '1443', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1444', '169', '呼玛县', '0,1,12,169', '1444', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1445', '169', '漠河县', '0,1,12,169', '1445', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('1446', '169', '塔河县', '0,1,12,169', '1446', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1447', '170', '兴山区', '0,1,12,170', '1447', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1448', '170', '工农区', '0,1,12,170', '1448', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1449', '170', '南山区', '0,1,12,170', '1449', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1450', '170', '兴安区', '0,1,12,170', '1450', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1451', '170', '向阳区', '0,1,12,170', '1451', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1452', '170', '东山区', '0,1,12,170', '1452', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1453', '170', '萝北县', '0,1,12,170', '1453', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1454', '170', '绥滨县', '0,1,12,170', '1454', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1455', '171', '爱辉区', '0,1,12,171', '1455', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('1456', '171', '五大连池市', '0,1,12,171', '1456', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1457', '171', '北安市', '0,1,12,171', '1457', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1458', '171', '嫩江县', '0,1,12,171', '1458', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1459', '171', '逊克县', '0,1,12,171', '1459', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1460', '171', '孙吴县', '0,1,12,171', '1460', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1461', '172', '鸡冠区', '0,1,12,172', '1461', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1462', '172', '恒山区', '0,1,12,172', '1462', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1463', '172', '城子河区', '0,1,12,172', '1463', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1464', '172', '滴道区', '0,1,12,172', '1464', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1465', '172', '梨树区', '0,1,12,172', '1465', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1466', '172', '虎林市', '0,1,12,172', '1466', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1467', '172', '密山市', '0,1,12,172', '1467', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('1468', '172', '鸡东县', '0,1,12,172', '1468', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1469', '173', '前进区', '0,1,12,173', '1469', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1470', '173', '郊区', '0,1,12,173', '1470', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1471', '173', '向阳区', '0,1,12,173', '1471', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1472', '173', '东风区', '0,1,12,173', '1472', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1473', '173', '同江市', '0,1,12,173', '1473', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1474', '173', '富锦市', '0,1,12,173', '1474', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1475', '173', '桦南县', '0,1,12,173', '1475', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1476', '173', '桦川县', '0,1,12,173', '1476', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1477', '173', '汤原县', '0,1,12,173', '1477', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1478', '173', '抚远县', '0,1,12,173', '1478', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1479', '174', '爱民区', '0,1,12,174', '1479', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('1480', '174', '东安区', '0,1,12,174', '1480', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1481', '174', '阳明区', '0,1,12,174', '1481', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1482', '174', '西安区', '0,1,12,174', '1482', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1483', '174', '绥芬河市', '0,1,12,174', '1483', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1484', '174', '海林市', '0,1,12,174', '1484', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1485', '174', '宁安市', '0,1,12,174', '1485', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1486', '174', '穆棱市', '0,1,12,174', '1486', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('1487', '174', '东宁县', '0,1,12,174', '1487', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1488', '174', '林口县', '0,1,12,174', '1488', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1489', '175', '桃山区', '0,1,12,175', '1489', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1490', '175', '新兴区', '0,1,12,175', '1490', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1491', '175', '茄子河区', '0,1,12,175', '1491', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1492', '175', '勃利县', '0,1,12,175', '1492', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1493', '176', '龙沙区', '0,1,12,176', '1493', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1494', '176', '昂昂溪区', '0,1,12,176', '1494', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('1495', '176', '铁峰区', '0,1,12,176', '1495', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1496', '176', '建华区', '0,1,12,176', '1496', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1497', '176', '富拉尔基区', '0,1,12,176', '1497', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1498', '176', '碾子山区', '0,1,12,176', '1498', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1499', '176', '梅里斯达斡尔族区', '0,1,12,176', '1499', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('1500', '176', '讷河市', '0,1,12,176', '1500', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1501', '176', '龙江县', '0,1,12,176', '1501', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1502', '176', '依安县', '0,1,12,176', '1502', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1503', '176', '泰来县', '0,1,12,176', '1503', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1504', '176', '甘南县', '0,1,12,176', '1504', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1505', '176', '富裕县', '0,1,12,176', '1505', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1506', '176', '克山县', '0,1,12,176', '1506', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('1507', '176', '克东县', '0,1,12,176', '1507', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('1508', '176', '拜泉县', '0,1,12,176', '1508', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1509', '177', '尖山区', '0,1,12,177', '1509', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1510', '177', '岭东区', '0,1,12,177', '1510', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1511', '177', '四方台区', '0,1,12,177', '1511', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1512', '177', '宝山区', '0,1,12,177', '1512', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1513', '177', '集贤县', '0,1,12,177', '1513', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1514', '177', '友谊县', '0,1,12,177', '1514', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1515', '177', '宝清县', '0,1,12,177', '1515', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1516', '177', '饶河县', '0,1,12,177', '1516', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('1517', '178', '北林区', '0,1,12,178', '1517', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1518', '178', '安达市', '0,1,12,178', '1518', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('1519', '178', '肇东市', '0,1,12,178', '1519', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1520', '178', '海伦市', '0,1,12,178', '1520', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1521', '178', '望奎县', '0,1,12,178', '1521', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1522', '178', '兰西县', '0,1,12,178', '1522', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1523', '178', '青冈县', '0,1,12,178', '1523', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1524', '178', '庆安县', '0,1,12,178', '1524', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1525', '178', '明水县', '0,1,12,178', '1525', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('1526', '178', '绥棱县', '0,1,12,178', '1526', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1527', '179', '伊春区', '0,1,12,179', '1527', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1528', '179', '带岭区', '0,1,12,179', '1528', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1529', '179', '南岔区', '0,1,12,179', '1529', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1530', '179', '金山屯区', '0,1,12,179', '1530', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1531', '179', '西林区', '0,1,12,179', '1531', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1532', '179', '美溪区', '0,1,12,179', '1532', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('1533', '179', '乌马河区', '0,1,12,179', '1533', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1534', '179', '翠峦区', '0,1,12,179', '1534', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1535', '179', '友好区', '0,1,12,179', '1535', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1536', '179', '上甘岭区', '0,1,12,179', '1536', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1537', '179', '五营区', '0,1,12,179', '1537', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1538', '179', '红星区', '0,1,12,179', '1538', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1539', '179', '新青区', '0,1,12,179', '1539', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1540', '179', '汤旺河区', '0,1,12,179', '1540', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1541', '179', '乌伊岭区', '0,1,12,179', '1541', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1542', '179', '铁力市', '0,1,12,179', '1542', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1543', '179', '嘉荫县', '0,1,12,179', '1543', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1544', '180', '江岸区', '0,1,13,180', '1544', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1545', '180', '武昌区', '0,1,13,180', '1545', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1546', '180', '江汉区', '0,1,13,180', '1546', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1547', '180', '硚口区', '0,1,13,180', '1547', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1548', '180', '汉阳区', '0,1,13,180', '1548', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1549', '180', '青山区', '0,1,13,180', '1549', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1550', '180', '洪山区', '0,1,13,180', '1550', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1551', '180', '东西湖区', '0,1,13,180', '1551', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1552', '180', '汉南区', '0,1,13,180', '1552', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1553', '180', '蔡甸区', '0,1,13,180', '1553', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1554', '180', '江夏区', '0,1,13,180', '1554', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1555', '180', '黄陂区', '0,1,13,180', '1555', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1556', '180', '新洲区', '0,1,13,180', '1556', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1557', '180', '经济开发区', '0,1,13,180', '1557', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1559', '182', '鄂城区', '0,1,13,182', '1559', '0', '', '0', '1', '', '', '', '', '0', 'E', '0', '');
INSERT INTO `zz_linkage` VALUES ('1560', '182', '华容区', '0,1,13,182', '1560', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1561', '182', '梁子湖区', '0,1,13,182', '1561', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1562', '183', '黄州区', '0,1,13,183', '1562', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1563', '183', '麻城市', '0,1,13,183', '1563', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('1564', '183', '武穴市', '0,1,13,183', '1564', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1565', '183', '团风县', '0,1,13,183', '1565', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1566', '183', '红安县', '0,1,13,183', '1566', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1567', '183', '罗田县', '0,1,13,183', '1567', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1568', '183', '英山县', '0,1,13,183', '1568', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1569', '183', '浠水县', '0,1,13,183', '1569', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1570', '183', '蕲春县', '0,1,13,183', '1570', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1571', '183', '黄梅县', '0,1,13,183', '1571', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1572', '184', '黄石港区', '0,1,13,184', '1572', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1573', '184', '西塞山区', '0,1,13,184', '1573', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1574', '184', '下陆区', '0,1,13,184', '1574', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1575', '184', '铁山区', '0,1,13,184', '1575', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1576', '184', '大冶市', '0,1,13,184', '1576', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1577', '184', '阳新县', '0,1,13,184', '1577', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1578', '185', '东宝区', '0,1,13,185', '1578', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1579', '185', '掇刀区', '0,1,13,185', '1579', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1580', '185', '钟祥市', '0,1,13,185', '1580', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1581', '185', '京山县', '0,1,13,185', '1581', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1582', '185', '沙洋县', '0,1,13,185', '1582', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1583', '186', '沙市区', '0,1,13,186', '1583', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1584', '186', '荆州区', '0,1,13,186', '1584', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1585', '186', '石首市', '0,1,13,186', '1585', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1586', '186', '洪湖市', '0,1,13,186', '1586', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1587', '186', '松滋市', '0,1,13,186', '1587', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1588', '186', '公安县', '0,1,13,186', '1588', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1589', '186', '监利县', '0,1,13,186', '1589', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1590', '186', '江陵县', '0,1,13,186', '1590', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1593', '189', '张湾区', '0,1,13,189', '1593', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1594', '189', '茅箭区', '0,1,13,189', '1594', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('1595', '189', '丹江口市', '0,1,13,189', '1595', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1596', '189', '郧县', '0,1,13,189', '1596', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1597', '189', '郧西县', '0,1,13,189', '1597', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1598', '189', '竹山县', '0,1,13,189', '1598', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1599', '189', '竹溪县', '0,1,13,189', '1599', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1600', '189', '房县', '0,1,13,189', '1600', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1601', '190', '曾都区', '0,1,13,190', '1601', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1602', '190', '广水市', '0,1,13,190', '1602', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1604', '192', '咸安区', '0,1,13,192', '1604', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1605', '192', '赤壁市', '0,1,13,192', '1605', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1606', '192', '嘉鱼县', '0,1,13,192', '1606', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1607', '192', '通城县', '0,1,13,192', '1607', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1608', '192', '崇阳县', '0,1,13,192', '1608', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1609', '192', '通山县', '0,1,13,192', '1609', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1610', '193', '襄城区', '0,1,13,193', '1610', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1611', '193', '樊城区', '0,1,13,193', '1611', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1612', '193', '襄阳区', '0,1,13,193', '1612', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1613', '193', '老河口市', '0,1,13,193', '1613', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1614', '193', '枣阳市', '0,1,13,193', '1614', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1615', '193', '宜城市', '0,1,13,193', '1615', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1616', '193', '南漳县', '0,1,13,193', '1616', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1617', '193', '谷城县', '0,1,13,193', '1617', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1618', '193', '保康县', '0,1,13,193', '1618', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1619', '194', '孝南区', '0,1,13,194', '1619', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1620', '194', '应城市', '0,1,13,194', '1620', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1621', '194', '安陆市', '0,1,13,194', '1621', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('1622', '194', '汉川市', '0,1,13,194', '1622', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1623', '194', '孝昌县', '0,1,13,194', '1623', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1624', '194', '大悟县', '0,1,13,194', '1624', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1625', '194', '云梦县', '0,1,13,194', '1625', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1626', '195', '长阳土家族自治县', '0,1,13,195', '1626', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1627', '195', '五峰土家族自治县', '0,1,13,195', '1627', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1628', '195', '西陵区', '0,1,13,195', '1628', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1629', '195', '伍家岗区', '0,1,13,195', '1629', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1630', '195', '点军区', '0,1,13,195', '1630', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1631', '195', '猇亭区', '0,1,13,195', '1631', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1632', '195', '夷陵区', '0,1,13,195', '1632', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1633', '195', '宜都市', '0,1,13,195', '1633', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1634', '195', '当阳市', '0,1,13,195', '1634', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1635', '195', '枝江市', '0,1,13,195', '1635', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1636', '195', '远安县', '0,1,13,195', '1636', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1637', '195', '兴山县', '0,1,13,195', '1637', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1638', '195', '秭归县', '0,1,13,195', '1638', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1639', '196', '恩施市', '0,1,13,196', '1639', '0', '', '0', '1', '', '', '', '', '0', 'E', '0', '');
INSERT INTO `zz_linkage` VALUES ('1640', '196', '利川市', '0,1,13,196', '1640', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1641', '196', '建始县', '0,1,13,196', '1641', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1642', '196', '巴东县', '0,1,13,196', '1642', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1643', '196', '宣恩县', '0,1,13,196', '1643', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1644', '196', '咸丰县', '0,1,13,196', '1644', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1645', '196', '来凤县', '0,1,13,196', '1645', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1646', '196', '鹤峰县', '0,1,13,196', '1646', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1647', '197', '岳麓区', '0,1,14,197', '1647', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1648', '197', '芙蓉区', '0,1,14,197', '1648', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1649', '197', '天心区', '0,1,14,197', '1649', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1650', '197', '开福区', '0,1,14,197', '1650', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('1651', '197', '雨花区', '0,1,14,197', '1651', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1652', '197', '开发区', '0,1,14,197', '1652', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('1653', '197', '浏阳市', '0,1,14,197', '1653', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1654', '197', '长沙县', '0,1,14,197', '1654', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1655', '197', '望城区', '0,1,14,197', '1655', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1656', '197', '宁乡县', '0,1,14,197', '1656', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1657', '198', '永定区', '0,1,14,198', '1657', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1658', '198', '武陵源区', '0,1,14,198', '1658', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1659', '198', '慈利县', '0,1,14,198', '1659', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1660', '198', '桑植县', '0,1,14,198', '1660', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1661', '199', '武陵区', '0,1,14,199', '1661', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1662', '199', '鼎城区', '0,1,14,199', '1662', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1663', '199', '津市市', '0,1,14,199', '1663', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1664', '199', '安乡县', '0,1,14,199', '1664', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('1665', '199', '汉寿县', '0,1,14,199', '1665', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1666', '199', '澧县', '0,1,14,199', '1666', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1667', '199', '临澧县', '0,1,14,199', '1667', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1668', '199', '桃源县', '0,1,14,199', '1668', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1669', '199', '石门县', '0,1,14,199', '1669', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1670', '200', '北湖区', '0,1,14,200', '1670', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1671', '200', '苏仙区', '0,1,14,200', '1671', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1672', '200', '资兴市', '0,1,14,200', '1672', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1673', '200', '桂阳县', '0,1,14,200', '1673', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1674', '200', '宜章县', '0,1,14,200', '1674', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1675', '200', '永兴县', '0,1,14,200', '1675', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1676', '200', '嘉禾县', '0,1,14,200', '1676', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1677', '200', '临武县', '0,1,14,200', '1677', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1678', '200', '汝城县', '0,1,14,200', '1678', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('1679', '200', '桂东县', '0,1,14,200', '1679', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1680', '200', '安仁县', '0,1,14,200', '1680', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('1681', '201', '雁峰区', '0,1,14,201', '1681', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1682', '201', '珠晖区', '0,1,14,201', '1682', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1683', '201', '石鼓区', '0,1,14,201', '1683', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1684', '201', '蒸湘区', '0,1,14,201', '1684', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1685', '201', '南岳区', '0,1,14,201', '1685', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1686', '201', '耒阳市', '0,1,14,201', '1686', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1687', '201', '常宁市', '0,1,14,201', '1687', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1688', '201', '衡阳县', '0,1,14,201', '1688', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1689', '201', '衡南县', '0,1,14,201', '1689', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1690', '201', '衡山县', '0,1,14,201', '1690', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1691', '201', '衡东县', '0,1,14,201', '1691', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1692', '201', '祁东县', '0,1,14,201', '1692', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1693', '202', '鹤城区', '0,1,14,202', '1693', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1694', '202', '靖州苗族侗族自治县', '0,1,14,202', '1694', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1695', '202', '麻阳苗族自治县', '0,1,14,202', '1695', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('1696', '202', '通道侗族自治县', '0,1,14,202', '1696', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1697', '202', '新晃侗族自治县', '0,1,14,202', '1697', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1698', '202', '芷江侗族自治县', '0,1,14,202', '1698', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1699', '202', '沅陵县', '0,1,14,202', '1699', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1700', '202', '辰溪县', '0,1,14,202', '1700', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1701', '202', '溆浦县', '0,1,14,202', '1701', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1702', '202', '中方县', '0,1,14,202', '1702', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1703', '202', '会同县', '0,1,14,202', '1703', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1704', '202', '洪江市', '0,1,14,202', '1704', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1705', '203', '娄星区', '0,1,14,203', '1705', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1706', '203', '冷水江市', '0,1,14,203', '1706', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1707', '203', '涟源市', '0,1,14,203', '1707', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1708', '203', '双峰县', '0,1,14,203', '1708', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1709', '203', '新化县', '0,1,14,203', '1709', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1710', '204', '城步苗族自治县', '0,1,14,204', '1710', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1711', '204', '双清区', '0,1,14,204', '1711', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1712', '204', '大祥区', '0,1,14,204', '1712', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1713', '204', '北塔区', '0,1,14,204', '1713', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1714', '204', '武冈市', '0,1,14,204', '1714', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1715', '204', '邵东县', '0,1,14,204', '1715', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1716', '204', '新邵县', '0,1,14,204', '1716', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1717', '204', '邵阳县', '0,1,14,204', '1717', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1718', '204', '隆回县', '0,1,14,204', '1718', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1719', '204', '洞口县', '0,1,14,204', '1719', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1720', '204', '绥宁县', '0,1,14,204', '1720', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1721', '204', '新宁县', '0,1,14,204', '1721', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1722', '205', '岳塘区', '0,1,14,205', '1722', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1723', '205', '雨湖区', '0,1,14,205', '1723', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1724', '205', '湘乡市', '0,1,14,205', '1724', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1725', '205', '韶山市', '0,1,14,205', '1725', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1726', '205', '湘潭县', '0,1,14,205', '1726', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1727', '206', '吉首市', '0,1,14,206', '1727', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1728', '206', '泸溪县', '0,1,14,206', '1728', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1729', '206', '凤凰县', '0,1,14,206', '1729', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1730', '206', '花垣县', '0,1,14,206', '1730', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1731', '206', '保靖县', '0,1,14,206', '1731', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1732', '206', '古丈县', '0,1,14,206', '1732', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1733', '206', '永顺县', '0,1,14,206', '1733', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1734', '206', '龙山县', '0,1,14,206', '1734', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1735', '207', '赫山区', '0,1,14,207', '1735', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1736', '207', '资阳区', '0,1,14,207', '1736', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1737', '207', '沅江市', '0,1,14,207', '1737', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1738', '207', '南县', '0,1,14,207', '1738', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1739', '207', '桃江县', '0,1,14,207', '1739', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1740', '207', '安化县', '0,1,14,207', '1740', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('1741', '208', '江华瑶族自治县', '0,1,14,208', '1741', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1742', '208', '冷水滩区', '0,1,14,208', '1742', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1743', '208', '零陵区', '0,1,14,208', '1743', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1744', '208', '祁阳县', '0,1,14,208', '1744', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1745', '208', '东安县', '0,1,14,208', '1745', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1746', '208', '双牌县', '0,1,14,208', '1746', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1747', '208', '道县', '0,1,14,208', '1747', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1748', '208', '江永县', '0,1,14,208', '1748', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1749', '208', '宁远县', '0,1,14,208', '1749', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1750', '208', '蓝山县', '0,1,14,208', '1750', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1751', '208', '新田县', '0,1,14,208', '1751', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1752', '209', '岳阳楼区', '0,1,14,209', '1752', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1753', '209', '君山区', '0,1,14,209', '1753', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1754', '209', '云溪区', '0,1,14,209', '1754', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1755', '209', '汨罗市', '0,1,14,209', '1755', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('1756', '209', '临湘市', '0,1,14,209', '1756', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1757', '209', '岳阳县', '0,1,14,209', '1757', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1758', '209', '华容县', '0,1,14,209', '1758', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1759', '209', '湘阴县', '0,1,14,209', '1759', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1760', '209', '平江县', '0,1,14,209', '1760', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('1761', '210', '天元区', '0,1,14,210', '1761', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1762', '210', '荷塘区', '0,1,14,210', '1762', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1763', '210', '芦淞区', '0,1,14,210', '1763', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1764', '210', '石峰区', '0,1,14,210', '1764', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1765', '210', '醴陵市', '0,1,14,210', '1765', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1766', '210', '株洲县', '0,1,14,210', '1766', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1767', '210', '攸县', '0,1,14,210', '1767', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1768', '210', '茶陵县', '0,1,14,210', '1768', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1769', '210', '炎陵县', '0,1,14,210', '1769', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1770', '211', '朝阳区', '0,1,15,211', '1770', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1771', '211', '宽城区', '0,1,15,211', '1771', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('1772', '211', '二道区', '0,1,15,211', '1772', '0', '', '0', '1', '', '', '', '', '0', 'E', '0', '');
INSERT INTO `zz_linkage` VALUES ('1773', '211', '南关区', '0,1,15,211', '1773', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1774', '211', '绿园区', '0,1,15,211', '1774', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1775', '211', '双阳区', '0,1,15,211', '1775', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1776', '211', '净月潭开发区', '0,1,15,211', '1776', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1777', '211', '高新技术开发区', '0,1,15,211', '1777', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1778', '211', '经济技术开发区', '0,1,15,211', '1778', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1779', '211', '汽车产业开发区', '0,1,15,211', '1779', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1780', '211', '德惠市', '0,1,15,211', '1780', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1781', '211', '九台区', '0,1,15,211', '1781', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1782', '211', '榆树市', '0,1,15,211', '1782', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1783', '211', '农安县', '0,1,15,211', '1783', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1784', '212', '船营区', '0,1,15,212', '1784', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1785', '212', '昌邑区', '0,1,15,212', '1785', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1786', '212', '龙潭区', '0,1,15,212', '1786', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1787', '212', '丰满区', '0,1,15,212', '1787', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1788', '212', '蛟河市', '0,1,15,212', '1788', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1789', '212', '桦甸市', '0,1,15,212', '1789', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1790', '212', '舒兰市', '0,1,15,212', '1790', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1791', '212', '磐石市', '0,1,15,212', '1791', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('1792', '212', '永吉县', '0,1,15,212', '1792', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1793', '213', '洮北区', '0,1,15,213', '1793', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1794', '213', '洮南市', '0,1,15,213', '1794', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1795', '213', '大安市', '0,1,15,213', '1795', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1796', '213', '镇赉县', '0,1,15,213', '1796', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1797', '213', '通榆县', '0,1,15,213', '1797', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1798', '214', '江源区', '0,1,15,214', '1798', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1799', '214', '八道江区', '0,1,15,214', '1799', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1800', '214', '长白朝鲜族自治县', '0,1,15,214', '1800', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1801', '214', '临江市', '0,1,15,214', '1801', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1802', '214', '抚松县', '0,1,15,214', '1802', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1803', '214', '靖宇县', '0,1,15,214', '1803', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1804', '215', '龙山区', '0,1,15,215', '1804', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1805', '215', '西安区', '0,1,15,215', '1805', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1806', '215', '东丰县', '0,1,15,215', '1806', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1807', '215', '东辽县', '0,1,15,215', '1807', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1808', '216', '铁西区', '0,1,15,216', '1808', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1809', '216', '铁东区', '0,1,15,216', '1809', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1810', '216', '伊通满族自治县', '0,1,15,216', '1810', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1811', '216', '公主岭市', '0,1,15,216', '1811', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1812', '216', '双辽市', '0,1,15,216', '1812', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1813', '216', '梨树县', '0,1,15,216', '1813', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1814', '217', '前郭尔罗斯蒙古族自治县', '0,1,15,217', '1814', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1815', '217', '宁江区', '0,1,15,217', '1815', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1816', '217', '长岭县', '0,1,15,217', '1816', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1817', '217', '乾安县', '0,1,15,217', '1817', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1818', '217', '扶余县', '0,1,15,217', '1818', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1819', '218', '东昌区', '0,1,15,218', '1819', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1820', '218', '二道江区', '0,1,15,218', '1820', '0', '', '0', '1', '', '', '', '', '0', 'E', '0', '');
INSERT INTO `zz_linkage` VALUES ('1821', '218', '梅河口市', '0,1,15,218', '1821', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('1822', '218', '集安市', '0,1,15,218', '1822', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1823', '218', '通化县', '0,1,15,218', '1823', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1824', '218', '辉南县', '0,1,15,218', '1824', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1825', '218', '柳河县', '0,1,15,218', '1825', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1826', '219', '延吉市', '0,1,15,219', '1826', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1827', '219', '图们市', '0,1,15,219', '1827', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1828', '219', '敦化市', '0,1,15,219', '1828', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1829', '219', '珲春市', '0,1,15,219', '1829', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1830', '219', '龙井市', '0,1,15,219', '1830', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1831', '219', '和龙市', '0,1,15,219', '1831', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1832', '219', '安图县', '0,1,15,219', '1832', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('1833', '219', '汪清县', '0,1,15,219', '1833', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1834', '220', '玄武区', '0,1,16,220', '1834', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1835', '220', '鼓楼区', '0,1,16,220', '1835', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1836', '220', '白下区', '0,1,16,220', '1836', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1837', '220', '建邺区', '0,1,16,220', '1837', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1838', '220', '秦淮区', '0,1,16,220', '1838', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1839', '220', '雨花台区', '0,1,16,220', '1839', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1840', '220', '下关区', '0,1,16,220', '1840', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1841', '220', '栖霞区', '0,1,16,220', '1841', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1842', '220', '浦口区', '0,1,16,220', '1842', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('1843', '220', '江宁区', '0,1,16,220', '1843', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1844', '220', '六合区', '0,1,16,220', '1844', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1845', '220', '溧水区', '0,1,16,220', '1845', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1846', '220', '高淳区', '0,1,16,220', '1846', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1847', '221', '沧浪区', '0,1,16,221', '1847', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1848', '221', '金阊区', '0,1,16,221', '1848', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1849', '221', '平江区', '0,1,16,221', '1849', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('1850', '221', '虎丘区', '0,1,16,221', '1850', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1851', '221', '吴中区', '0,1,16,221', '1851', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1852', '221', '相城区', '0,1,16,221', '1852', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1853', '221', '园区', '0,1,16,221', '1853', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1854', '221', '新区', '0,1,16,221', '1854', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1855', '221', '常熟市', '0,1,16,221', '1855', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1856', '221', '张家港市', '0,1,16,221', '1856', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1857', '221', '玉山镇', '0,1,16,221', '1857', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1858', '221', '巴城镇', '0,1,16,221', '1858', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1859', '221', '周市镇', '0,1,16,221', '1859', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1860', '221', '陆家镇', '0,1,16,221', '1860', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1861', '221', '花桥镇', '0,1,16,221', '1861', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1862', '221', '淀山湖镇', '0,1,16,221', '1862', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1863', '221', '张浦镇', '0,1,16,221', '1863', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1864', '221', '周庄镇', '0,1,16,221', '1864', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1865', '221', '千灯镇', '0,1,16,221', '1865', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1866', '221', '锦溪镇', '0,1,16,221', '1866', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1867', '221', '开发区', '0,1,16,221', '1867', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('1868', '221', '吴江市', '0,1,16,221', '1868', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1869', '221', '太仓市', '0,1,16,221', '1869', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1870', '222', '崇安区', '0,1,16,222', '1870', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1871', '222', '北塘区', '0,1,16,222', '1871', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1872', '222', '南长区', '0,1,16,222', '1872', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1873', '222', '锡山区', '0,1,16,222', '1873', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1874', '222', '惠山区', '0,1,16,222', '1874', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1875', '222', '滨湖区', '0,1,16,222', '1875', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1876', '222', '新区', '0,1,16,222', '1876', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1877', '222', '江阴市', '0,1,16,222', '1877', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1878', '222', '宜兴市', '0,1,16,222', '1878', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1879', '223', '天宁区', '0,1,16,223', '1879', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1880', '223', '钟楼区', '0,1,16,223', '1880', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1881', '223', '戚墅堰区', '0,1,16,223', '1881', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1882', '223', '郊区', '0,1,16,223', '1882', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1883', '223', '新北区', '0,1,16,223', '1883', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1884', '223', '武进区', '0,1,16,223', '1884', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1885', '223', '溧阳市', '0,1,16,223', '1885', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1886', '223', '金坛市', '0,1,16,223', '1886', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1887', '224', '清河区', '0,1,16,224', '1887', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1888', '224', '清浦区', '0,1,16,224', '1888', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1889', '224', '楚州区', '0,1,16,224', '1889', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1890', '224', '淮阴区', '0,1,16,224', '1890', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1891', '224', '涟水县', '0,1,16,224', '1891', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1892', '224', '洪泽县', '0,1,16,224', '1892', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1893', '224', '盱眙县', '0,1,16,224', '1893', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1894', '224', '金湖县', '0,1,16,224', '1894', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1895', '225', '新浦区', '0,1,16,225', '1895', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1896', '225', '连云区', '0,1,16,225', '1896', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1897', '225', '海州区', '0,1,16,225', '1897', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1898', '225', '赣榆区', '0,1,16,225', '1898', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1899', '225', '东海县', '0,1,16,225', '1899', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1900', '225', '灌云县', '0,1,16,225', '1900', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1901', '225', '灌南县', '0,1,16,225', '1901', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1902', '226', '崇川区', '0,1,16,226', '1902', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1903', '226', '港闸区', '0,1,16,226', '1903', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1904', '226', '经济开发区', '0,1,16,226', '1904', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1905', '226', '启东市', '0,1,16,226', '1905', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1906', '226', '如皋市', '0,1,16,226', '1906', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('1907', '226', '通州区', '0,1,16,226', '1907', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1908', '226', '海门市', '0,1,16,226', '1908', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1909', '226', '海安县', '0,1,16,226', '1909', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1910', '226', '如东县', '0,1,16,226', '1910', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('1911', '227', '宿城区', '0,1,16,227', '1911', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1912', '227', '宿豫区', '0,1,16,227', '1912', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1913', '227', '宿豫县', '0,1,16,227', '1913', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1914', '227', '沭阳县', '0,1,16,227', '1914', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1915', '227', '泗阳县', '0,1,16,227', '1915', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1916', '227', '泗洪县', '0,1,16,227', '1916', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1917', '228', '海陵区', '0,1,16,228', '1917', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1918', '228', '高港区', '0,1,16,228', '1918', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1919', '228', '兴化市', '0,1,16,228', '1919', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1920', '228', '靖江市', '0,1,16,228', '1920', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1921', '228', '泰兴市', '0,1,16,228', '1921', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1922', '228', '姜堰区', '0,1,16,228', '1922', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1923', '229', '云龙区', '0,1,16,229', '1923', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1924', '229', '鼓楼区', '0,1,16,229', '1924', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1925', '229', '九里区', '0,1,16,229', '1925', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1926', '229', '贾汪区', '0,1,16,229', '1926', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1927', '229', '泉山区', '0,1,16,229', '1927', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1928', '229', '新沂市', '0,1,16,229', '1928', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1929', '229', '邳州市', '0,1,16,229', '1929', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('1930', '229', '丰县', '0,1,16,229', '1930', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1931', '229', '沛县', '0,1,16,229', '1931', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('1932', '229', '铜山县', '0,1,16,229', '1932', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1933', '229', '睢宁县', '0,1,16,229', '1933', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1934', '230', '城区', '0,1,16,230', '1934', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1935', '230', '亭湖区', '0,1,16,230', '1935', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('1936', '230', '盐都区', '0,1,16,230', '1936', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1937', '230', '盐都县', '0,1,16,230', '1937', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1938', '230', '东台市', '0,1,16,230', '1938', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1939', '230', '大丰市', '0,1,16,230', '1939', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1940', '230', '响水县', '0,1,16,230', '1940', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1941', '230', '滨海县', '0,1,16,230', '1941', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1942', '230', '阜宁县', '0,1,16,230', '1942', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('1943', '230', '射阳县', '0,1,16,230', '1943', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1944', '230', '建湖县', '0,1,16,230', '1944', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1945', '231', '广陵区', '0,1,16,231', '1945', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1946', '231', '维扬区', '0,1,16,231', '1946', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1947', '231', '邗江区', '0,1,16,231', '1947', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1948', '231', '仪征市', '0,1,16,231', '1948', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1949', '231', '高邮市', '0,1,16,231', '1949', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1950', '231', '江都市', '0,1,16,231', '1950', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1951', '231', '宝应县', '0,1,16,231', '1951', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('1952', '232', '京口区', '0,1,16,232', '1952', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1953', '232', '润州区', '0,1,16,232', '1953', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('1954', '232', '丹徒区', '0,1,16,232', '1954', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1955', '232', '丹阳市', '0,1,16,232', '1955', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1956', '232', '扬中市', '0,1,16,232', '1956', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1957', '232', '句容市', '0,1,16,232', '1957', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1958', '233', '东湖区', '0,1,17,233', '1958', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1959', '233', '西湖区', '0,1,17,233', '1959', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1960', '233', '青云谱区', '0,1,17,233', '1960', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1961', '233', '湾里区', '0,1,17,233', '1961', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('1962', '233', '青山湖区', '0,1,17,233', '1962', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1963', '233', '红谷滩新区', '0,1,17,233', '1963', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1964', '233', '昌北区', '0,1,17,233', '1964', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1965', '233', '高新区', '0,1,17,233', '1965', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1966', '233', '南昌县', '0,1,17,233', '1966', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1967', '233', '新建县', '0,1,17,233', '1967', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1968', '233', '安义县', '0,1,17,233', '1968', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('1969', '233', '进贤县', '0,1,17,233', '1969', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1970', '234', '临川区', '0,1,17,234', '1970', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1971', '234', '南城县', '0,1,17,234', '1971', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1972', '234', '黎川县', '0,1,17,234', '1972', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1973', '234', '南丰县', '0,1,17,234', '1973', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1974', '234', '崇仁县', '0,1,17,234', '1974', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1975', '234', '乐安县', '0,1,17,234', '1975', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1976', '234', '宜黄县', '0,1,17,234', '1976', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1977', '234', '金溪县', '0,1,17,234', '1977', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('1978', '234', '资溪县', '0,1,17,234', '1978', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1979', '234', '东乡县', '0,1,17,234', '1979', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1980', '234', '广昌县', '0,1,17,234', '1980', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1981', '235', '章贡区', '0,1,17,235', '1981', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('1982', '235', '于都县', '0,1,17,235', '1982', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('1983', '235', '瑞金市', '0,1,17,235', '1983', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('1984', '235', '南康区', '0,1,17,235', '1984', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1985', '235', '赣县', '0,1,17,235', '1985', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('1986', '235', '信丰县', '0,1,17,235', '1986', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1987', '235', '大余县', '0,1,17,235', '1987', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1988', '235', '上犹县', '0,1,17,235', '1988', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1989', '235', '崇义县', '0,1,17,235', '1989', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('1990', '235', '安远县', '0,1,17,235', '1990', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('1991', '235', '龙南县', '0,1,17,235', '1991', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('1992', '235', '定南县', '0,1,17,235', '1992', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('1993', '235', '全南县', '0,1,17,235', '1993', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('1994', '235', '宁都县', '0,1,17,235', '1994', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('1995', '235', '兴国县', '0,1,17,235', '1995', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1996', '235', '会昌县', '0,1,17,235', '1996', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('1997', '235', '寻乌县', '0,1,17,235', '1997', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('1998', '235', '石城县', '0,1,17,235', '1998', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('1999', '236', '安福县', '0,1,17,236', '1999', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('2000', '236', '吉州区', '0,1,17,236', '2000', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2001', '236', '青原区', '0,1,17,236', '2001', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2002', '236', '井冈山市', '0,1,17,236', '2002', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2003', '236', '吉安县', '0,1,17,236', '2003', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2004', '236', '吉水县', '0,1,17,236', '2004', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2005', '236', '峡江县', '0,1,17,236', '2005', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2006', '236', '新干县', '0,1,17,236', '2006', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2007', '236', '永丰县', '0,1,17,236', '2007', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2008', '236', '泰和县', '0,1,17,236', '2008', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2009', '236', '遂川县', '0,1,17,236', '2009', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2010', '236', '万安县', '0,1,17,236', '2010', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2011', '236', '永新县', '0,1,17,236', '2011', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2012', '237', '珠山区', '0,1,17,237', '2012', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2013', '237', '昌江区', '0,1,17,237', '2013', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2014', '237', '乐平市', '0,1,17,237', '2014', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2015', '237', '浮梁县', '0,1,17,237', '2015', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2016', '238', '浔阳区', '0,1,17,238', '2016', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2017', '238', '庐山区', '0,1,17,238', '2017', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2018', '238', '瑞昌市', '0,1,17,238', '2018', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('2019', '238', '九江县', '0,1,17,238', '2019', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2020', '238', '武宁县', '0,1,17,238', '2020', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2021', '238', '修水县', '0,1,17,238', '2021', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2022', '238', '永修县', '0,1,17,238', '2022', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2023', '238', '德安县', '0,1,17,238', '2023', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2024', '238', '星子县', '0,1,17,238', '2024', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2025', '238', '都昌县', '0,1,17,238', '2025', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2026', '238', '湖口县', '0,1,17,238', '2026', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2027', '238', '彭泽县', '0,1,17,238', '2027', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2028', '239', '安源区', '0,1,17,239', '2028', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('2029', '239', '湘东区', '0,1,17,239', '2029', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2030', '239', '莲花县', '0,1,17,239', '2030', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2031', '239', '芦溪县', '0,1,17,239', '2031', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2032', '239', '上栗县', '0,1,17,239', '2032', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2033', '240', '信州区', '0,1,17,240', '2033', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2034', '240', '德兴市', '0,1,17,240', '2034', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2035', '240', '上饶县', '0,1,17,240', '2035', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2036', '240', '广丰县', '0,1,17,240', '2036', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2037', '240', '玉山县', '0,1,17,240', '2037', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2038', '240', '铅山县', '0,1,17,240', '2038', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2039', '240', '横峰县', '0,1,17,240', '2039', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2040', '240', '弋阳县', '0,1,17,240', '2040', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2041', '240', '余干县', '0,1,17,240', '2041', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2042', '240', '鄱阳县', '0,1,17,240', '2042', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2043', '240', '万年县', '0,1,17,240', '2043', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2044', '240', '婺源县', '0,1,17,240', '2044', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2045', '241', '渝水区', '0,1,17,241', '2045', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2046', '241', '分宜县', '0,1,17,241', '2046', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2047', '242', '袁州区', '0,1,17,242', '2047', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2048', '242', '丰城市', '0,1,17,242', '2048', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2049', '242', '樟树市', '0,1,17,242', '2049', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2050', '242', '高安市', '0,1,17,242', '2050', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2051', '242', '奉新县', '0,1,17,242', '2051', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2052', '242', '万载县', '0,1,17,242', '2052', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2053', '242', '上高县', '0,1,17,242', '2053', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2054', '242', '宜丰县', '0,1,17,242', '2054', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2055', '242', '靖安县', '0,1,17,242', '2055', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2056', '242', '铜鼓县', '0,1,17,242', '2056', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2057', '243', '月湖区', '0,1,17,243', '2057', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2058', '243', '贵溪市', '0,1,17,243', '2058', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2059', '243', '余江县', '0,1,17,243', '2059', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2060', '244', '沈河区', '0,1,18,244', '2060', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2061', '244', '皇姑区', '0,1,18,244', '2061', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2062', '244', '和平区', '0,1,18,244', '2062', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2063', '244', '大东区', '0,1,18,244', '2063', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2064', '244', '铁西区', '0,1,18,244', '2064', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2065', '244', '苏家屯区', '0,1,18,244', '2065', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2066', '244', '东陵区', '0,1,18,244', '2066', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2067', '244', '沈北新区', '0,1,18,244', '2067', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2068', '244', '于洪区', '0,1,18,244', '2068', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2069', '244', '浑南区', '0,1,18,244', '2069', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2070', '244', '新民市', '0,1,18,244', '2070', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2071', '244', '辽中县', '0,1,18,244', '2071', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2072', '244', '康平县', '0,1,18,244', '2072', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('2073', '244', '法库县', '0,1,18,244', '2073', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2074', '245', '西岗区', '0,1,18,245', '2074', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2075', '245', '中山区', '0,1,18,245', '2075', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2076', '245', '沙河口区', '0,1,18,245', '2076', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2077', '245', '甘井子区', '0,1,18,245', '2077', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2078', '245', '旅顺口区', '0,1,18,245', '2078', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2079', '245', '金州区', '0,1,18,245', '2079', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2080', '245', '开发区', '0,1,18,245', '2080', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('2081', '245', '瓦房店市', '0,1,18,245', '2081', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2082', '245', '普兰店市', '0,1,18,245', '2082', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2083', '245', '庄河市', '0,1,18,245', '2083', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2084', '245', '长海县', '0,1,18,245', '2084', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2085', '246', '铁东区', '0,1,18,246', '2085', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2086', '246', '铁西区', '0,1,18,246', '2086', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2087', '246', '立山区', '0,1,18,246', '2087', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2088', '246', '千山区', '0,1,18,246', '2088', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2089', '246', '岫岩满族自治县', '0,1,18,246', '2089', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2090', '246', '海城市', '0,1,18,246', '2090', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2091', '246', '台安县', '0,1,18,246', '2091', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2092', '247', '本溪满族自治县', '0,1,18,247', '2092', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2093', '247', '平山区', '0,1,18,247', '2093', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2094', '247', '明山区', '0,1,18,247', '2094', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2095', '247', '溪湖区', '0,1,18,247', '2095', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2096', '247', '南芬区', '0,1,18,247', '2096', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('2097', '247', '桓仁满族自治县', '0,1,18,247', '2097', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2098', '248', '双塔区', '0,1,18,248', '2098', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2099', '248', '龙城区', '0,1,18,248', '2099', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2100', '248', '喀喇沁左翼蒙古族自治县', '0,1,18,248', '2100', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('2101', '248', '北票市', '0,1,18,248', '2101', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2102', '248', '凌源市', '0,1,18,248', '2102', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2103', '248', '朝阳县', '0,1,18,248', '2103', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2104', '248', '建平县', '0,1,18,248', '2104', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2105', '249', '振兴区', '0,1,18,249', '2105', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2106', '249', '元宝区', '0,1,18,249', '2106', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2107', '249', '振安区', '0,1,18,249', '2107', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2108', '249', '宽甸满族自治县', '0,1,18,249', '2108', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('2109', '249', '东港市', '0,1,18,249', '2109', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2110', '249', '凤城市', '0,1,18,249', '2110', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2111', '250', '顺城区', '0,1,18,250', '2111', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2112', '250', '新抚区', '0,1,18,250', '2112', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2113', '250', '东洲区', '0,1,18,250', '2113', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2114', '250', '望花区', '0,1,18,250', '2114', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2115', '250', '清原满族自治县', '0,1,18,250', '2115', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2116', '250', '新宾满族自治县', '0,1,18,250', '2116', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2117', '250', '抚顺县', '0,1,18,250', '2117', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2118', '251', '阜新蒙古族自治县', '0,1,18,251', '2118', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2119', '251', '海州区', '0,1,18,251', '2119', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2120', '251', '新邱区', '0,1,18,251', '2120', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2121', '251', '太平区', '0,1,18,251', '2121', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2122', '251', '清河门区', '0,1,18,251', '2122', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2123', '251', '细河区', '0,1,18,251', '2123', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2124', '251', '彰武县', '0,1,18,251', '2124', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2125', '252', '龙港区', '0,1,18,252', '2125', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2126', '252', '南票区', '0,1,18,252', '2126', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('2127', '252', '连山区', '0,1,18,252', '2127', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2128', '252', '兴城市', '0,1,18,252', '2128', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2129', '252', '绥中县', '0,1,18,252', '2129', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2130', '252', '建昌县', '0,1,18,252', '2130', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2131', '253', '太和区', '0,1,18,253', '2131', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2132', '253', '古塔区', '0,1,18,253', '2132', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2133', '253', '凌河区', '0,1,18,253', '2133', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2134', '253', '凌海市', '0,1,18,253', '2134', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2135', '253', '北镇市', '0,1,18,253', '2135', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2136', '253', '黑山县', '0,1,18,253', '2136', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2137', '253', '义县', '0,1,18,253', '2137', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2138', '254', '白塔区', '0,1,18,254', '2138', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2139', '254', '文圣区', '0,1,18,254', '2139', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2140', '254', '宏伟区', '0,1,18,254', '2140', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2141', '254', '太子河区', '0,1,18,254', '2141', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2142', '254', '弓长岭区', '0,1,18,254', '2142', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2143', '254', '灯塔市', '0,1,18,254', '2143', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2144', '254', '辽阳县', '0,1,18,254', '2144', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2145', '255', '双台子区', '0,1,18,255', '2145', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2146', '255', '兴隆台区', '0,1,18,255', '2146', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2147', '255', '大洼县', '0,1,18,255', '2147', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2148', '255', '盘山县', '0,1,18,255', '2148', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2149', '256', '银州区', '0,1,18,256', '2149', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2150', '256', '清河区', '0,1,18,256', '2150', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2151', '256', '调兵山市', '0,1,18,256', '2151', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2152', '256', '开原市', '0,1,18,256', '2152', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('2153', '256', '铁岭县', '0,1,18,256', '2153', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2154', '256', '西丰县', '0,1,18,256', '2154', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2155', '256', '昌图县', '0,1,18,256', '2155', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2156', '257', '站前区', '0,1,18,257', '2156', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2157', '257', '西市区', '0,1,18,257', '2157', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2158', '257', '鲅鱼圈区', '0,1,18,257', '2158', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2159', '257', '老边区', '0,1,18,257', '2159', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2160', '257', '盖州市', '0,1,18,257', '2160', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2161', '257', '大石桥市', '0,1,18,257', '2161', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2162', '258', '回民区', '0,1,19,258', '2162', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2163', '258', '玉泉区', '0,1,19,258', '2163', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2164', '258', '新城区', '0,1,19,258', '2164', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2165', '258', '赛罕区', '0,1,19,258', '2165', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2166', '258', '清水河县', '0,1,19,258', '2166', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2167', '258', '土默特左旗', '0,1,19,258', '2167', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2168', '258', '托克托县', '0,1,19,258', '2168', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2169', '258', '和林格尔县', '0,1,19,258', '2169', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2170', '258', '武川县', '0,1,19,258', '2170', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2171', '259', '阿拉善左旗', '0,1,19,259', '2171', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('2172', '259', '阿拉善右旗', '0,1,19,259', '2172', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('2173', '259', '额济纳旗', '0,1,19,259', '2173', '0', '', '0', '1', '', '', '', '', '0', 'E', '0', '');
INSERT INTO `zz_linkage` VALUES ('2174', '260', '临河区', '0,1,19,260', '2174', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2175', '260', '五原县', '0,1,19,260', '2175', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2176', '260', '磴口县', '0,1,19,260', '2176', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2177', '260', '乌拉特前旗', '0,1,19,260', '2177', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2178', '260', '乌拉特中旗', '0,1,19,260', '2178', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2179', '260', '乌拉特后旗', '0,1,19,260', '2179', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2180', '260', '杭锦后旗', '0,1,19,260', '2180', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2181', '261', '昆都仑区', '0,1,19,261', '2181', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('2182', '261', '青山区', '0,1,19,261', '2182', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2183', '261', '东河区', '0,1,19,261', '2183', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2184', '261', '九原区', '0,1,19,261', '2184', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2185', '261', '石拐区', '0,1,19,261', '2185', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2186', '261', '白云矿区', '0,1,19,261', '2186', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2187', '261', '土默特右旗', '0,1,19,261', '2187', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2188', '261', '固阳县', '0,1,19,261', '2188', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2189', '261', '达尔罕茂明安联合旗', '0,1,19,261', '2189', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2190', '262', '红山区', '0,1,19,262', '2190', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2191', '262', '元宝山区', '0,1,19,262', '2191', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2192', '262', '松山区', '0,1,19,262', '2192', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2193', '262', '阿鲁科尔沁旗', '0,1,19,262', '2193', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('2194', '262', '巴林左旗', '0,1,19,262', '2194', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2195', '262', '巴林右旗', '0,1,19,262', '2195', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2196', '262', '林西县', '0,1,19,262', '2196', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2197', '262', '克什克腾旗', '0,1,19,262', '2197', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('2198', '262', '翁牛特旗', '0,1,19,262', '2198', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2199', '262', '喀喇沁旗', '0,1,19,262', '2199', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('2200', '262', '宁城县', '0,1,19,262', '2200', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('2201', '262', '敖汉旗', '0,1,19,262', '2201', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('2202', '263', '东胜区', '0,1,19,263', '2202', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2203', '263', '达拉特旗', '0,1,19,263', '2203', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2204', '263', '准格尔旗', '0,1,19,263', '2204', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2205', '263', '鄂托克前旗', '0,1,19,263', '2205', '0', '', '0', '1', '', '', '', '', '0', 'E', '0', '');
INSERT INTO `zz_linkage` VALUES ('2206', '263', '鄂托克旗', '0,1,19,263', '2206', '0', '', '0', '1', '', '', '', '', '0', 'E', '0', '');
INSERT INTO `zz_linkage` VALUES ('2207', '263', '杭锦旗', '0,1,19,263', '2207', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2208', '263', '乌审旗', '0,1,19,263', '2208', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2209', '263', '伊金霍洛旗', '0,1,19,263', '2209', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2210', '264', '海拉尔区', '0,1,19,264', '2210', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2211', '264', '莫力达瓦达翰尔族自治旗', '0,1,19,264', '2211', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2212', '264', '满洲里市', '0,1,19,264', '2212', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2213', '264', '牙克石市', '0,1,19,264', '2213', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2214', '264', '扎兰屯市', '0,1,19,264', '2214', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2215', '264', '额尔古纳市', '0,1,19,264', '2215', '0', '', '0', '1', '', '', '', '', '0', 'E', '0', '');
INSERT INTO `zz_linkage` VALUES ('2216', '264', '根河市', '0,1,19,264', '2216', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2217', '264', '阿荣旗', '0,1,19,264', '2217', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('2218', '264', '鄂伦春自治旗', '0,1,19,264', '2218', '0', '', '0', '1', '', '', '', '', '0', 'E', '0', '');
INSERT INTO `zz_linkage` VALUES ('2219', '264', '鄂温克族自治旗', '0,1,19,264', '2219', '0', '', '0', '1', '', '', '', '', '0', 'E', '0', '');
INSERT INTO `zz_linkage` VALUES ('2220', '264', '陈巴尔虎旗', '0,1,19,264', '2220', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2221', '264', '新巴尔虎左旗', '0,1,19,264', '2221', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2222', '264', '新巴尔虎右旗', '0,1,19,264', '2222', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2223', '265', '科尔沁区', '0,1,19,265', '2223', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('2224', '265', '霍林郭勒市', '0,1,19,265', '2224', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2225', '265', '科尔沁左翼中旗', '0,1,19,265', '2225', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('2226', '265', '科尔沁左翼后旗', '0,1,19,265', '2226', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('2227', '265', '开鲁县', '0,1,19,265', '2227', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('2228', '265', '库伦旗', '0,1,19,265', '2228', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('2229', '265', '奈曼旗', '0,1,19,265', '2229', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('2230', '265', '扎鲁特旗', '0,1,19,265', '2230', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2231', '266', '海勃湾区', '0,1,19,266', '2231', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2232', '266', '乌达区', '0,1,19,266', '2232', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2233', '266', '海南区', '0,1,19,266', '2233', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2234', '267', '化德县', '0,1,19,267', '2234', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2235', '267', '集宁区', '0,1,19,267', '2235', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2236', '267', '丰镇市', '0,1,19,267', '2236', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2237', '267', '卓资县', '0,1,19,267', '2237', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2238', '267', '商都县', '0,1,19,267', '2238', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2239', '267', '兴和县', '0,1,19,267', '2239', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2240', '267', '凉城县', '0,1,19,267', '2240', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2241', '267', '察哈尔右翼前旗', '0,1,19,267', '2241', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2242', '267', '察哈尔右翼中旗', '0,1,19,267', '2242', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2243', '267', '察哈尔右翼后旗', '0,1,19,267', '2243', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2244', '267', '四子王旗', '0,1,19,267', '2244', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2245', '268', '二连浩特市', '0,1,19,268', '2245', '0', '', '0', '1', '', '', '', '', '0', 'E', '0', '');
INSERT INTO `zz_linkage` VALUES ('2246', '268', '锡林浩特市', '0,1,19,268', '2246', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2247', '268', '阿巴嘎旗', '0,1,19,268', '2247', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('2248', '268', '苏尼特左旗', '0,1,19,268', '2248', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2249', '268', '苏尼特右旗', '0,1,19,268', '2249', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2250', '268', '东乌珠穆沁旗', '0,1,19,268', '2250', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2251', '268', '西乌珠穆沁旗', '0,1,19,268', '2251', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2252', '268', '太仆寺旗', '0,1,19,268', '2252', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2253', '268', '镶黄旗', '0,1,19,268', '2253', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2254', '268', '正镶白旗', '0,1,19,268', '2254', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2255', '268', '正蓝旗', '0,1,19,268', '2255', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2256', '268', '多伦县', '0,1,19,268', '2256', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2257', '269', '乌兰浩特市', '0,1,19,269', '2257', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2258', '269', '阿尔山市', '0,1,19,269', '2258', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('2259', '269', '科尔沁右翼前旗', '0,1,19,269', '2259', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('2260', '269', '科尔沁右翼中旗', '0,1,19,269', '2260', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('2261', '269', '扎赉特旗', '0,1,19,269', '2261', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2262', '269', '突泉县', '0,1,19,269', '2262', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2263', '270', '西夏区', '0,1,20,270', '2263', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2264', '270', '金凤区', '0,1,20,270', '2264', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2265', '270', '兴庆区', '0,1,20,270', '2265', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2266', '270', '灵武市', '0,1,20,270', '2266', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2267', '270', '永宁县', '0,1,20,270', '2267', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2268', '270', '贺兰县', '0,1,20,270', '2268', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2269', '271', '原州区', '0,1,20,271', '2269', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2271', '271', '西吉县', '0,1,20,271', '2271', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2272', '271', '隆德县', '0,1,20,271', '2272', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2273', '271', '泾源县', '0,1,20,271', '2273', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2274', '271', '彭阳县', '0,1,20,271', '2274', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2275', '272', '惠农县', '0,1,20,272', '2275', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2276', '272', '大武口区', '0,1,20,272', '2276', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2277', '272', '惠农区', '0,1,20,272', '2277', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2278', '272', '陶乐县', '0,1,20,272', '2278', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2279', '272', '平罗县', '0,1,20,272', '2279', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2280', '273', '利通区', '0,1,20,273', '2280', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2282', '273', '青铜峡市', '0,1,20,273', '2282', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2284', '273', '盐池县', '0,1,20,273', '2284', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2285', '273', '同心县', '0,1,20,273', '2285', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2286', '274', '沙坡头区', '0,1,20,274', '2286', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2287', '274', '海原县', '0,1,20,274', '2287', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2288', '274', '中宁县', '0,1,20,274', '2288', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2289', '275', '城中区', '0,1,21,275', '2289', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2290', '275', '城东区', '0,1,21,275', '2290', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2291', '275', '城西区', '0,1,21,275', '2291', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2292', '275', '城北区', '0,1,21,275', '2292', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2293', '275', '湟中县', '0,1,21,275', '2293', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2294', '275', '湟源县', '0,1,21,275', '2294', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2295', '275', '大通回族土族自治县', '0,1,21,275', '2295', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2296', '276', '玛沁县', '0,1,21,276', '2296', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2297', '276', '班玛县', '0,1,21,276', '2297', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2298', '276', '甘德县', '0,1,21,276', '2298', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2299', '276', '达日县', '0,1,21,276', '2299', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2300', '276', '久治县', '0,1,21,276', '2300', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2301', '276', '玛多县', '0,1,21,276', '2301', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2302', '277', '海晏县', '0,1,21,277', '2302', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2303', '277', '祁连县', '0,1,21,277', '2303', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2304', '277', '刚察县', '0,1,21,277', '2304', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2305', '277', '门源回族自治县', '0,1,21,277', '2305', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2306', '278', '平安区', '0,1,21,278', '2306', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2307', '278', '乐都区', '0,1,21,278', '2307', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2308', '278', '民和回族土族自治县', '0,1,21,278', '2308', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2309', '278', '互助土族自治县', '0,1,21,278', '2309', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2310', '278', '化隆回族自治县', '0,1,21,278', '2310', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2311', '278', '循化撒拉族自治县', '0,1,21,278', '2311', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2312', '279', '共和县', '0,1,21,279', '2312', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2313', '279', '同德县', '0,1,21,279', '2313', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2314', '279', '贵德县', '0,1,21,279', '2314', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2315', '279', '兴海县', '0,1,21,279', '2315', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2316', '279', '贵南县', '0,1,21,279', '2316', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2317', '280', '德令哈市', '0,1,21,280', '2317', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2318', '280', '格尔木市', '0,1,21,280', '2318', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2319', '280', '乌兰县', '0,1,21,280', '2319', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2320', '280', '都兰县', '0,1,21,280', '2320', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2321', '280', '天峻县', '0,1,21,280', '2321', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2322', '281', '同仁县', '0,1,21,281', '2322', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2323', '281', '尖扎县', '0,1,21,281', '2323', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2324', '281', '泽库县', '0,1,21,281', '2324', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2325', '281', '河南蒙古族自治县', '0,1,21,281', '2325', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2326', '282', '玉树县', '0,1,21,282', '2326', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2327', '282', '杂多县', '0,1,21,282', '2327', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2328', '282', '称多县', '0,1,21,282', '2328', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2329', '282', '治多县', '0,1,21,282', '2329', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2330', '282', '囊谦县', '0,1,21,282', '2330', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('2331', '282', '曲麻莱县', '0,1,21,282', '2331', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2332', '283', '市中区', '0,1,22,283', '2332', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2333', '283', '历下区', '0,1,22,283', '2333', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2334', '283', '天桥区', '0,1,22,283', '2334', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2335', '283', '槐荫区', '0,1,22,283', '2335', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2336', '283', '历城区', '0,1,22,283', '2336', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2337', '283', '长清区', '0,1,22,283', '2337', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2338', '283', '章丘市', '0,1,22,283', '2338', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2339', '283', '平阴县', '0,1,22,283', '2339', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2340', '283', '济阳县', '0,1,22,283', '2340', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2341', '283', '商河县', '0,1,22,283', '2341', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2342', '284', '市南区', '0,1,22,284', '2342', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2343', '284', '市北区', '0,1,22,284', '2343', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2344', '284', '城阳区', '0,1,22,284', '2344', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2345', '284', '四方区', '0,1,22,284', '2345', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2346', '284', '李沧区', '0,1,22,284', '2346', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2347', '284', '黄岛区', '0,1,22,284', '2347', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2348', '284', '崂山区', '0,1,22,284', '2348', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2349', '284', '胶州市', '0,1,22,284', '2349', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2350', '284', '即墨市', '0,1,22,284', '2350', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2351', '284', '平度市', '0,1,22,284', '2351', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2352', '284', '胶南市', '0,1,22,284', '2352', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2353', '284', '莱西市', '0,1,22,284', '2353', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2354', '285', '滨城区', '0,1,22,285', '2354', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2355', '285', '惠民县', '0,1,22,285', '2355', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2356', '285', '阳信县', '0,1,22,285', '2356', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2357', '285', '无棣县', '0,1,22,285', '2357', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2358', '285', '沾化县', '0,1,22,285', '2358', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2359', '285', '博兴县', '0,1,22,285', '2359', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2360', '285', '邹平县', '0,1,22,285', '2360', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2361', '286', '德城区', '0,1,22,286', '2361', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2362', '286', '陵县', '0,1,22,286', '2362', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2363', '286', '乐陵市', '0,1,22,286', '2363', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2364', '286', '禹城市', '0,1,22,286', '2364', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2365', '286', '宁津县', '0,1,22,286', '2365', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('2366', '286', '庆云县', '0,1,22,286', '2366', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2367', '286', '临邑县', '0,1,22,286', '2367', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2368', '286', '齐河县', '0,1,22,286', '2368', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2369', '286', '平原县', '0,1,22,286', '2369', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2370', '286', '夏津县', '0,1,22,286', '2370', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2371', '286', '武城县', '0,1,22,286', '2371', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2372', '287', '东营区', '0,1,22,287', '2372', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2373', '287', '河口区', '0,1,22,287', '2373', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2374', '287', '垦利县', '0,1,22,287', '2374', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('2375', '287', '利津县', '0,1,22,287', '2375', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2376', '287', '广饶县', '0,1,22,287', '2376', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2377', '288', '牡丹区', '0,1,22,288', '2377', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2378', '288', '曹县', '0,1,22,288', '2378', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2379', '288', '单县', '0,1,22,288', '2379', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2380', '288', '成武县', '0,1,22,288', '2380', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2381', '288', '巨野县', '0,1,22,288', '2381', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2382', '288', '郓城县', '0,1,22,288', '2382', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2383', '288', '鄄城县', '0,1,22,288', '2383', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2384', '288', '定陶县', '0,1,22,288', '2384', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2385', '288', '东明县', '0,1,22,288', '2385', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2386', '289', '市中区', '0,1,22,289', '2386', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2387', '289', '任城区', '0,1,22,289', '2387', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('2388', '289', '曲阜市', '0,1,22,289', '2388', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2389', '289', '兖州市', '0,1,22,289', '2389', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2390', '289', '邹城市', '0,1,22,289', '2390', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2391', '289', '微山县', '0,1,22,289', '2391', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2392', '289', '鱼台县', '0,1,22,289', '2392', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2393', '289', '金乡县', '0,1,22,289', '2393', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2394', '289', '嘉祥县', '0,1,22,289', '2394', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2395', '289', '汶上县', '0,1,22,289', '2395', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2396', '289', '泗水县', '0,1,22,289', '2396', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2397', '289', '梁山县', '0,1,22,289', '2397', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2398', '290', '莱城区', '0,1,22,290', '2398', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2399', '290', '钢城区', '0,1,22,290', '2399', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2400', '291', '东昌府区', '0,1,22,291', '2400', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2401', '291', '临清市', '0,1,22,291', '2401', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2402', '291', '阳谷县', '0,1,22,291', '2402', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2403', '291', '莘县', '0,1,22,291', '2403', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2404', '291', '茌平县', '0,1,22,291', '2404', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2405', '291', '东阿县', '0,1,22,291', '2405', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2406', '291', '冠县', '0,1,22,291', '2406', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2407', '291', '高唐县', '0,1,22,291', '2407', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2408', '292', '兰山区', '0,1,22,292', '2408', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2409', '292', '罗庄区', '0,1,22,292', '2409', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2410', '292', '河东区', '0,1,22,292', '2410', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2411', '292', '沂南县', '0,1,22,292', '2411', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2412', '292', '郯城县', '0,1,22,292', '2412', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2413', '292', '沂水县', '0,1,22,292', '2413', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2414', '292', '苍山县', '0,1,22,292', '2414', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2415', '292', '费县', '0,1,22,292', '2415', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2416', '292', '平邑县', '0,1,22,292', '2416', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2417', '292', '莒南县', '0,1,22,292', '2417', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2418', '292', '蒙阴县', '0,1,22,292', '2418', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2419', '292', '临沭县', '0,1,22,292', '2419', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2420', '293', '东港区', '0,1,22,293', '2420', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2421', '293', '岚山区', '0,1,22,293', '2421', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2422', '293', '五莲县', '0,1,22,293', '2422', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2423', '293', '莒县', '0,1,22,293', '2423', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2424', '294', '泰山区', '0,1,22,294', '2424', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2425', '294', '岱岳区', '0,1,22,294', '2425', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2426', '294', '新泰市', '0,1,22,294', '2426', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2427', '294', '肥城市', '0,1,22,294', '2427', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2428', '294', '宁阳县', '0,1,22,294', '2428', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('2429', '294', '东平县', '0,1,22,294', '2429', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2430', '295', '荣成市', '0,1,22,295', '2430', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('2431', '295', '乳山市', '0,1,22,295', '2431', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('2432', '295', '环翠区', '0,1,22,295', '2432', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2433', '295', '文登市', '0,1,22,295', '2433', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2434', '296', '潍城区', '0,1,22,296', '2434', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2435', '296', '寒亭区', '0,1,22,296', '2435', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2436', '296', '坊子区', '0,1,22,296', '2436', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2437', '296', '奎文区', '0,1,22,296', '2437', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('2438', '296', '青州市', '0,1,22,296', '2438', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2439', '296', '诸城市', '0,1,22,296', '2439', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2440', '296', '寿光市', '0,1,22,296', '2440', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2441', '296', '安丘市', '0,1,22,296', '2441', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('2442', '296', '高密市', '0,1,22,296', '2442', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2443', '296', '昌邑市', '0,1,22,296', '2443', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2444', '296', '临朐县', '0,1,22,296', '2444', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2445', '296', '昌乐县', '0,1,22,296', '2445', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2446', '297', '芝罘区', '0,1,22,297', '2446', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2447', '297', '福山区', '0,1,22,297', '2447', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2448', '297', '牟平区', '0,1,22,297', '2448', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2449', '297', '莱山区', '0,1,22,297', '2449', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2450', '297', '开发区', '0,1,22,297', '2450', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('2451', '297', '龙口市', '0,1,22,297', '2451', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2452', '297', '莱阳市', '0,1,22,297', '2452', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2453', '297', '莱州市', '0,1,22,297', '2453', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2454', '297', '蓬莱市', '0,1,22,297', '2454', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2455', '297', '招远市', '0,1,22,297', '2455', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2456', '297', '栖霞市', '0,1,22,297', '2456', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2457', '297', '海阳市', '0,1,22,297', '2457', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2458', '297', '长岛县', '0,1,22,297', '2458', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2459', '298', '市中区', '0,1,22,298', '2459', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2460', '298', '山亭区', '0,1,22,298', '2460', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2461', '298', '峄城区', '0,1,22,298', '2461', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2462', '298', '台儿庄区', '0,1,22,298', '2462', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2463', '298', '薛城区', '0,1,22,298', '2463', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2464', '298', '滕州市', '0,1,22,298', '2464', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2465', '299', '张店区', '0,1,22,299', '2465', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2466', '299', '临淄区', '0,1,22,299', '2466', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2467', '299', '淄川区', '0,1,22,299', '2467', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2468', '299', '博山区', '0,1,22,299', '2468', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2469', '299', '周村区', '0,1,22,299', '2469', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2470', '299', '桓台县', '0,1,22,299', '2470', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2471', '299', '高青县', '0,1,22,299', '2471', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2472', '299', '沂源县', '0,1,22,299', '2472', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2473', '300', '杏花岭区', '0,1,23,300', '2473', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2474', '300', '小店区', '0,1,23,300', '2474', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2475', '300', '迎泽区', '0,1,23,300', '2475', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2476', '300', '尖草坪区', '0,1,23,300', '2476', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2477', '300', '万柏林区', '0,1,23,300', '2477', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2478', '300', '晋源区', '0,1,23,300', '2478', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2479', '300', '高新开发区', '0,1,23,300', '2479', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2480', '300', '民营经济开发区', '0,1,23,300', '2480', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2481', '300', '经济技术开发区', '0,1,23,300', '2481', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2482', '300', '清徐县', '0,1,23,300', '2482', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2483', '300', '阳曲县', '0,1,23,300', '2483', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2484', '300', '娄烦县', '0,1,23,300', '2484', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2485', '300', '古交市', '0,1,23,300', '2485', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2486', '301', '城区', '0,1,23,301', '2486', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2487', '301', '郊区', '0,1,23,301', '2487', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2488', '301', '沁县', '0,1,23,301', '2488', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2489', '301', '潞城市', '0,1,23,301', '2489', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2490', '301', '长治县', '0,1,23,301', '2490', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2491', '301', '襄垣县', '0,1,23,301', '2491', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2492', '301', '屯留县', '0,1,23,301', '2492', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2493', '301', '平顺县', '0,1,23,301', '2493', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2494', '301', '黎城县', '0,1,23,301', '2494', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2495', '301', '壶关县', '0,1,23,301', '2495', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2496', '301', '长子县', '0,1,23,301', '2496', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2497', '301', '武乡县', '0,1,23,301', '2497', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2498', '301', '沁源县', '0,1,23,301', '2498', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2499', '302', '城区', '0,1,23,302', '2499', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2500', '302', '矿区', '0,1,23,302', '2500', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('2501', '302', '南郊区', '0,1,23,302', '2501', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('2502', '302', '新荣区', '0,1,23,302', '2502', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2503', '302', '阳高县', '0,1,23,302', '2503', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2504', '302', '天镇县', '0,1,23,302', '2504', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2505', '302', '广灵县', '0,1,23,302', '2505', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2506', '302', '灵丘县', '0,1,23,302', '2506', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2507', '302', '浑源县', '0,1,23,302', '2507', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2508', '302', '左云县', '0,1,23,302', '2508', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2509', '302', '大同县', '0,1,23,302', '2509', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2510', '303', '城区', '0,1,23,303', '2510', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2511', '303', '高平市', '0,1,23,303', '2511', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2512', '303', '沁水县', '0,1,23,303', '2512', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2513', '303', '阳城县', '0,1,23,303', '2513', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2514', '303', '陵川县', '0,1,23,303', '2514', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2515', '303', '泽州县', '0,1,23,303', '2515', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2516', '304', '榆次区', '0,1,23,304', '2516', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2517', '304', '介休市', '0,1,23,304', '2517', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2518', '304', '榆社县', '0,1,23,304', '2518', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2519', '304', '左权县', '0,1,23,304', '2519', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2520', '304', '和顺县', '0,1,23,304', '2520', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2521', '304', '昔阳县', '0,1,23,304', '2521', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2522', '304', '寿阳县', '0,1,23,304', '2522', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2523', '304', '太谷县', '0,1,23,304', '2523', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2524', '304', '祁县', '0,1,23,304', '2524', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2525', '304', '平遥县', '0,1,23,304', '2525', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2526', '304', '灵石县', '0,1,23,304', '2526', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2527', '305', '尧都区', '0,1,23,305', '2527', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2528', '305', '侯马市', '0,1,23,305', '2528', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2529', '305', '霍州市', '0,1,23,305', '2529', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2530', '305', '曲沃县', '0,1,23,305', '2530', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2531', '305', '翼城县', '0,1,23,305', '2531', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2532', '305', '襄汾县', '0,1,23,305', '2532', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2533', '305', '洪洞县', '0,1,23,305', '2533', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2534', '305', '吉县', '0,1,23,305', '2534', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2535', '305', '安泽县', '0,1,23,305', '2535', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('2536', '305', '浮山县', '0,1,23,305', '2536', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2537', '305', '古县', '0,1,23,305', '2537', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2538', '305', '乡宁县', '0,1,23,305', '2538', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2539', '305', '大宁县', '0,1,23,305', '2539', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2540', '305', '隰县', '0,1,23,305', '2540', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2541', '305', '永和县', '0,1,23,305', '2541', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2542', '305', '蒲县', '0,1,23,305', '2542', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2543', '305', '汾西县', '0,1,23,305', '2543', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2544', '306', '离石市', '0,1,23,306', '2544', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2545', '306', '离石区', '0,1,23,306', '2545', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2546', '306', '孝义市', '0,1,23,306', '2546', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2547', '306', '汾阳市', '0,1,23,306', '2547', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2548', '306', '文水县', '0,1,23,306', '2548', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2549', '306', '交城县', '0,1,23,306', '2549', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2550', '306', '兴县', '0,1,23,306', '2550', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2551', '306', '临县', '0,1,23,306', '2551', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2552', '306', '柳林县', '0,1,23,306', '2552', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2553', '306', '石楼县', '0,1,23,306', '2553', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2554', '306', '岚县', '0,1,23,306', '2554', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2555', '306', '方山县', '0,1,23,306', '2555', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2556', '306', '中阳县', '0,1,23,306', '2556', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2557', '306', '交口县', '0,1,23,306', '2557', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2558', '307', '朔城区', '0,1,23,307', '2558', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2559', '307', '平鲁区', '0,1,23,307', '2559', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2560', '307', '山阴县', '0,1,23,307', '2560', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2561', '307', '应县', '0,1,23,307', '2561', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2562', '307', '右玉县', '0,1,23,307', '2562', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2563', '307', '怀仁县', '0,1,23,307', '2563', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2564', '308', '忻府区', '0,1,23,308', '2564', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2565', '308', '原平市', '0,1,23,308', '2565', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2566', '308', '定襄县', '0,1,23,308', '2566', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2567', '308', '五台县', '0,1,23,308', '2567', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2568', '308', '代县', '0,1,23,308', '2568', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2569', '308', '繁峙县', '0,1,23,308', '2569', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2570', '308', '宁武县', '0,1,23,308', '2570', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('2571', '308', '静乐县', '0,1,23,308', '2571', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2572', '308', '神池县', '0,1,23,308', '2572', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2573', '308', '五寨县', '0,1,23,308', '2573', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2574', '308', '岢岚县', '0,1,23,308', '2574', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('2575', '308', '河曲县', '0,1,23,308', '2575', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2576', '308', '保德县', '0,1,23,308', '2576', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2577', '308', '偏关县', '0,1,23,308', '2577', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2578', '309', '城区', '0,1,23,309', '2578', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2579', '309', '矿区', '0,1,23,309', '2579', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('2580', '309', '郊区', '0,1,23,309', '2580', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2581', '309', '平定县', '0,1,23,309', '2581', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2582', '309', '盂县', '0,1,23,309', '2582', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2583', '310', '盐湖区', '0,1,23,310', '2583', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2584', '310', '永济市', '0,1,23,310', '2584', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2585', '310', '河津市', '0,1,23,310', '2585', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2586', '310', '临猗县', '0,1,23,310', '2586', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2587', '310', '万荣县', '0,1,23,310', '2587', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2588', '310', '闻喜县', '0,1,23,310', '2588', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2589', '310', '稷山县', '0,1,23,310', '2589', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2590', '310', '新绛县', '0,1,23,310', '2590', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2591', '310', '绛县', '0,1,23,310', '2591', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2592', '310', '垣曲县', '0,1,23,310', '2592', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2593', '310', '夏县', '0,1,23,310', '2593', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2594', '310', '平陆县', '0,1,23,310', '2594', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2595', '310', '芮城县', '0,1,23,310', '2595', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('2596', '311', '莲湖区', '0,1,24,311', '2596', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2597', '311', '新城区', '0,1,24,311', '2597', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2598', '311', '碑林区', '0,1,24,311', '2598', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2599', '311', '雁塔区', '0,1,24,311', '2599', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2600', '311', '灞桥区', '0,1,24,311', '2600', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2601', '311', '未央区', '0,1,24,311', '2601', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2602', '311', '阎良区', '0,1,24,311', '2602', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2603', '311', '临潼区', '0,1,24,311', '2603', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2604', '311', '长安区', '0,1,24,311', '2604', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2605', '311', '蓝田县', '0,1,24,311', '2605', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2606', '311', '周至县', '0,1,24,311', '2606', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2607', '311', '户县', '0,1,24,311', '2607', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2608', '311', '高陵区', '0,1,24,311', '2608', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2609', '312', '汉滨区', '0,1,24,312', '2609', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2610', '312', '汉阴县', '0,1,24,312', '2610', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2611', '312', '石泉县', '0,1,24,312', '2611', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2612', '312', '宁陕县', '0,1,24,312', '2612', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('2613', '312', '紫阳县', '0,1,24,312', '2613', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2614', '312', '岚皋县', '0,1,24,312', '2614', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2615', '312', '平利县', '0,1,24,312', '2615', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2616', '312', '镇坪县', '0,1,24,312', '2616', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2617', '312', '旬阳县', '0,1,24,312', '2617', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2618', '312', '白河县', '0,1,24,312', '2618', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2619', '313', '陈仓区', '0,1,24,313', '2619', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2620', '313', '渭滨区', '0,1,24,313', '2620', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2621', '313', '金台区', '0,1,24,313', '2621', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2622', '313', '凤翔县', '0,1,24,313', '2622', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2623', '313', '岐山县', '0,1,24,313', '2623', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2624', '313', '扶风县', '0,1,24,313', '2624', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2625', '313', '眉县', '0,1,24,313', '2625', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2626', '313', '陇县', '0,1,24,313', '2626', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2627', '313', '千阳县', '0,1,24,313', '2627', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2628', '313', '麟游县', '0,1,24,313', '2628', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2629', '313', '凤县', '0,1,24,313', '2629', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2630', '313', '太白县', '0,1,24,313', '2630', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2631', '314', '汉台区', '0,1,24,314', '2631', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2632', '314', '南郑县', '0,1,24,314', '2632', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('2633', '314', '城固县', '0,1,24,314', '2633', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2634', '314', '洋县', '0,1,24,314', '2634', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2635', '314', '西乡县', '0,1,24,314', '2635', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2636', '314', '勉县', '0,1,24,314', '2636', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2637', '314', '宁强县', '0,1,24,314', '2637', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('2638', '314', '略阳县', '0,1,24,314', '2638', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2639', '314', '镇巴县', '0,1,24,314', '2639', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2640', '314', '留坝县', '0,1,24,314', '2640', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2641', '314', '佛坪县', '0,1,24,314', '2641', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2642', '315', '商州区', '0,1,24,315', '2642', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2643', '315', '洛南县', '0,1,24,315', '2643', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2644', '315', '丹凤县', '0,1,24,315', '2644', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2645', '315', '商南县', '0,1,24,315', '2645', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2646', '315', '山阳县', '0,1,24,315', '2646', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2647', '315', '镇安县', '0,1,24,315', '2647', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2648', '315', '柞水县', '0,1,24,315', '2648', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2649', '316', '耀州区', '0,1,24,316', '2649', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2650', '316', '王益区', '0,1,24,316', '2650', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2651', '316', '印台区', '0,1,24,316', '2651', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2652', '316', '宜君县', '0,1,24,316', '2652', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2653', '317', '临渭区', '0,1,24,317', '2653', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2654', '317', '韩城市', '0,1,24,317', '2654', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2655', '317', '华阴市', '0,1,24,317', '2655', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2656', '317', '华县', '0,1,24,317', '2656', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2657', '317', '潼关县', '0,1,24,317', '2657', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2658', '317', '大荔县', '0,1,24,317', '2658', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2659', '317', '合阳县', '0,1,24,317', '2659', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2660', '317', '澄城县', '0,1,24,317', '2660', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2661', '317', '蒲城县', '0,1,24,317', '2661', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2662', '317', '白水县', '0,1,24,317', '2662', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2663', '317', '富平县', '0,1,24,317', '2663', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2664', '318', '秦都区', '0,1,24,318', '2664', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2665', '318', '渭城区', '0,1,24,318', '2665', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2666', '318', '杨陵区', '0,1,24,318', '2666', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2667', '318', '兴平市', '0,1,24,318', '2667', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2668', '318', '三原县', '0,1,24,318', '2668', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2669', '318', '泾阳县', '0,1,24,318', '2669', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2670', '318', '乾县', '0,1,24,318', '2670', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2671', '318', '礼泉县', '0,1,24,318', '2671', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2672', '318', '永寿县', '0,1,24,318', '2672', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2673', '318', '彬县', '0,1,24,318', '2673', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2674', '318', '长武县', '0,1,24,318', '2674', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2675', '318', '旬邑县', '0,1,24,318', '2675', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2676', '318', '淳化县', '0,1,24,318', '2676', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2677', '318', '武功县', '0,1,24,318', '2677', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2678', '319', '吴起县', '0,1,24,319', '2678', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2679', '319', '宝塔区', '0,1,24,319', '2679', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2680', '319', '延长县', '0,1,24,319', '2680', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2681', '319', '延川县', '0,1,24,319', '2681', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2682', '319', '子长县', '0,1,24,319', '2682', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2683', '319', '安塞县', '0,1,24,319', '2683', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('2684', '319', '志丹县', '0,1,24,319', '2684', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2685', '319', '甘泉县', '0,1,24,319', '2685', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2686', '319', '富县', '0,1,24,319', '2686', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2687', '319', '洛川县', '0,1,24,319', '2687', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2688', '319', '宜川县', '0,1,24,319', '2688', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2689', '319', '黄龙县', '0,1,24,319', '2689', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2690', '319', '黄陵县', '0,1,24,319', '2690', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2691', '320', '榆阳区', '0,1,24,320', '2691', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2692', '320', '神木县', '0,1,24,320', '2692', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2693', '320', '府谷县', '0,1,24,320', '2693', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2694', '320', '横山县', '0,1,24,320', '2694', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2695', '320', '靖边县', '0,1,24,320', '2695', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2696', '320', '定边县', '0,1,24,320', '2696', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2697', '320', '绥德县', '0,1,24,320', '2697', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2698', '320', '米脂县', '0,1,24,320', '2698', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2699', '320', '佳县', '0,1,24,320', '2699', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2700', '320', '吴堡县', '0,1,24,320', '2700', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2701', '320', '清涧县', '0,1,24,320', '2701', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2702', '320', '子洲县', '0,1,24,320', '2702', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2703', '25', '长宁区', '0,1,25', '2703', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2704', '25', '闸北区', '0,1,25', '2704', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2705', '25', '闵行区', '0,1,25', '2705', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2706', '25', '徐汇区', '0,1,25', '2706', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2707', '25', '浦东新区', '0,1,25', '2707', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2708', '25', '杨浦区', '0,1,25', '2708', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2709', '25', '普陀区', '0,1,25', '2709', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2710', '25', '静安区', '0,1,25', '2710', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2711', '25', '卢湾区', '0,1,25', '2711', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2712', '25', '虹口区', '0,1,25', '2712', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2713', '25', '黄浦区', '0,1,25', '2713', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2714', '25', '南汇区', '0,1,25', '2714', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('2715', '25', '松江区', '0,1,25', '2715', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2716', '25', '嘉定区', '0,1,25', '2716', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2717', '25', '宝山区', '0,1,25', '2717', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2718', '25', '青浦区', '0,1,25', '2718', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2719', '25', '金山区', '0,1,25', '2719', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2720', '25', '奉贤区', '0,1,25', '2720', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2721', '25', '崇明县', '0,1,25', '2721', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2722', '322', '青羊区', '0,1,26,322', '2722', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2723', '322', '锦江区', '0,1,26,322', '2723', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2724', '322', '金牛区', '0,1,26,322', '2724', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2725', '322', '武侯区', '0,1,26,322', '2725', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2726', '322', '成华区', '0,1,26,322', '2726', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2727', '322', '龙泉驿区', '0,1,26,322', '2727', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2728', '322', '青白江区', '0,1,26,322', '2728', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2729', '322', '新都区', '0,1,26,322', '2729', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2730', '322', '温江区', '0,1,26,322', '2730', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2731', '322', '高新区', '0,1,26,322', '2731', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2732', '322', '高新西区', '0,1,26,322', '2732', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2733', '322', '都江堰市', '0,1,26,322', '2733', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2734', '322', '彭州市', '0,1,26,322', '2734', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2735', '322', '邛崃市', '0,1,26,322', '2735', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2736', '322', '崇州市', '0,1,26,322', '2736', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2737', '322', '金堂县', '0,1,26,322', '2737', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2738', '322', '双流县', '0,1,26,322', '2738', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2739', '322', '郫县', '0,1,26,322', '2739', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2740', '322', '大邑县', '0,1,26,322', '2740', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2741', '322', '蒲江县', '0,1,26,322', '2741', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2742', '322', '新津县', '0,1,26,322', '2742', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2753', '323', '涪城区', '0,1,26,323', '2753', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2754', '323', '游仙区', '0,1,26,323', '2754', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2755', '323', '江油市', '0,1,26,323', '2755', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2756', '323', '盐亭县', '0,1,26,323', '2756', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2757', '323', '三台县', '0,1,26,323', '2757', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2758', '323', '平武县', '0,1,26,323', '2758', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2759', '323', '安县', '0,1,26,323', '2759', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('2760', '323', '梓潼县', '0,1,26,323', '2760', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2761', '323', '北川羌族自治县', '0,1,26,323', '2761', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2762', '324', '马尔康县', '0,1,26,324', '2762', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2763', '324', '汶川县', '0,1,26,324', '2763', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2764', '324', '理县', '0,1,26,324', '2764', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2765', '324', '茂县', '0,1,26,324', '2765', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2766', '324', '松潘县', '0,1,26,324', '2766', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2767', '324', '九寨沟县', '0,1,26,324', '2767', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2768', '324', '金川县', '0,1,26,324', '2768', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2769', '324', '小金县', '0,1,26,324', '2769', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2770', '324', '黑水县', '0,1,26,324', '2770', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2771', '324', '壤塘县', '0,1,26,324', '2771', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('2772', '324', '阿坝县', '0,1,26,324', '2772', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('2773', '324', '若尔盖县', '0,1,26,324', '2773', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('2774', '324', '红原县', '0,1,26,324', '2774', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2775', '325', '巴州区', '0,1,26,325', '2775', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2776', '325', '通江县', '0,1,26,325', '2776', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2777', '325', '南江县', '0,1,26,325', '2777', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('2778', '325', '平昌县', '0,1,26,325', '2778', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2779', '326', '通川区', '0,1,26,326', '2779', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2780', '326', '万源市', '0,1,26,326', '2780', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2781', '326', '达县', '0,1,26,326', '2781', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2782', '326', '宣汉县', '0,1,26,326', '2782', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2783', '326', '开江县', '0,1,26,326', '2783', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('2784', '326', '大竹县', '0,1,26,326', '2784', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2785', '326', '渠县', '0,1,26,326', '2785', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2786', '327', '旌阳区', '0,1,26,327', '2786', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2787', '327', '广汉市', '0,1,26,327', '2787', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2788', '327', '什邡市', '0,1,26,327', '2788', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2789', '327', '绵竹市', '0,1,26,327', '2789', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2790', '327', '罗江县', '0,1,26,327', '2790', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2791', '327', '中江县', '0,1,26,327', '2791', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2792', '328', '康定县', '0,1,26,328', '2792', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('2793', '328', '丹巴县', '0,1,26,328', '2793', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2794', '328', '泸定县', '0,1,26,328', '2794', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2795', '328', '炉霍县', '0,1,26,328', '2795', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2796', '328', '九龙县', '0,1,26,328', '2796', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2797', '328', '甘孜县', '0,1,26,328', '2797', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2798', '328', '雅江县', '0,1,26,328', '2798', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2799', '328', '新龙县', '0,1,26,328', '2799', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2800', '328', '道孚县', '0,1,26,328', '2800', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2801', '328', '白玉县', '0,1,26,328', '2801', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2802', '328', '理塘县', '0,1,26,328', '2802', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2803', '328', '德格县', '0,1,26,328', '2803', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2804', '328', '乡城县', '0,1,26,328', '2804', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2805', '328', '石渠县', '0,1,26,328', '2805', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2806', '328', '稻城县', '0,1,26,328', '2806', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2807', '328', '色达县', '0,1,26,328', '2807', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2808', '328', '巴塘县', '0,1,26,328', '2808', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2809', '328', '得荣县', '0,1,26,328', '2809', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2810', '329', '广安区', '0,1,26,329', '2810', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2811', '329', '华蓥市', '0,1,26,329', '2811', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2812', '329', '岳池县', '0,1,26,329', '2812', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2813', '329', '武胜县', '0,1,26,329', '2813', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2814', '329', '邻水县', '0,1,26,329', '2814', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2815', '330', '利州区', '0,1,26,330', '2815', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2816', '330', '元坝区', '0,1,26,330', '2816', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2817', '330', '朝天区', '0,1,26,330', '2817', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2818', '330', '旺苍县', '0,1,26,330', '2818', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2819', '330', '青川县', '0,1,26,330', '2819', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2820', '330', '剑阁县', '0,1,26,330', '2820', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2821', '330', '苍溪县', '0,1,26,330', '2821', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2822', '331', '峨眉山市', '0,1,26,331', '2822', '0', '', '0', '1', '', '', '', '', '0', 'E', '0', '');
INSERT INTO `zz_linkage` VALUES ('2823', '331', '乐山市', '0,1,26,331', '2823', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2824', '331', '犍为县', '0,1,26,331', '2824', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2825', '331', '井研县', '0,1,26,331', '2825', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2826', '331', '夹江县', '0,1,26,331', '2826', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2827', '331', '沐川县', '0,1,26,331', '2827', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2828', '331', '峨边彝族自治县', '0,1,26,331', '2828', '0', '', '0', '1', '', '', '', '', '0', 'E', '0', '');
INSERT INTO `zz_linkage` VALUES ('2829', '331', '马边彝族自治县', '0,1,26,331', '2829', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2830', '332', '西昌市', '0,1,26,332', '2830', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2831', '332', '盐源县', '0,1,26,332', '2831', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2832', '332', '德昌县', '0,1,26,332', '2832', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2833', '332', '会理县', '0,1,26,332', '2833', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2834', '332', '会东县', '0,1,26,332', '2834', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2835', '332', '宁南县', '0,1,26,332', '2835', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('2836', '332', '普格县', '0,1,26,332', '2836', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2837', '332', '布拖县', '0,1,26,332', '2837', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2838', '332', '金阳县', '0,1,26,332', '2838', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2839', '332', '昭觉县', '0,1,26,332', '2839', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2840', '332', '喜德县', '0,1,26,332', '2840', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2841', '332', '冕宁县', '0,1,26,332', '2841', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2842', '332', '越西县', '0,1,26,332', '2842', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2843', '332', '甘洛县', '0,1,26,332', '2843', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2844', '332', '美姑县', '0,1,26,332', '2844', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2845', '332', '雷波县', '0,1,26,332', '2845', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2846', '332', '木里藏族自治县', '0,1,26,332', '2846', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2847', '333', '东坡区', '0,1,26,333', '2847', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2848', '333', '仁寿县', '0,1,26,333', '2848', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('2849', '333', '彭山区', '0,1,26,333', '2849', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2850', '333', '洪雅县', '0,1,26,333', '2850', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2851', '333', '丹棱县', '0,1,26,333', '2851', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2852', '333', '青神县', '0,1,26,333', '2852', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2853', '334', '阆中市', '0,1,26,334', '2853', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2854', '334', '南部县', '0,1,26,334', '2854', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('2855', '334', '营山县', '0,1,26,334', '2855', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2856', '334', '蓬安县', '0,1,26,334', '2856', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2857', '334', '仪陇县', '0,1,26,334', '2857', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2858', '334', '顺庆区', '0,1,26,334', '2858', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2859', '334', '高坪区', '0,1,26,334', '2859', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2860', '334', '嘉陵区', '0,1,26,334', '2860', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2861', '334', '西充县', '0,1,26,334', '2861', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2862', '335', '市中区', '0,1,26,335', '2862', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2863', '335', '东兴区', '0,1,26,335', '2863', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2864', '335', '威远县', '0,1,26,335', '2864', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2865', '335', '资中县', '0,1,26,335', '2865', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2866', '335', '隆昌县', '0,1,26,335', '2866', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2867', '336', '东  区', '0,1,26,336', '2867', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2868', '336', '西  区', '0,1,26,336', '2868', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2869', '336', '仁和区', '0,1,26,336', '2869', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('2870', '336', '米易县', '0,1,26,336', '2870', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2871', '336', '盐边县', '0,1,26,336', '2871', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2872', '337', '船山区', '0,1,26,337', '2872', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2873', '337', '安居区', '0,1,26,337', '2873', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('2874', '337', '蓬溪县', '0,1,26,337', '2874', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2875', '337', '射洪县', '0,1,26,337', '2875', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2876', '337', '大英县', '0,1,26,337', '2876', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2877', '338', '雨城区', '0,1,26,338', '2877', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2878', '338', '名山县', '0,1,26,338', '2878', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2879', '338', '荥经县', '0,1,26,338', '2879', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2880', '338', '汉源县', '0,1,26,338', '2880', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2881', '338', '石棉县', '0,1,26,338', '2881', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2882', '338', '天全县', '0,1,26,338', '2882', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2883', '338', '芦山县', '0,1,26,338', '2883', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2884', '338', '宝兴县', '0,1,26,338', '2884', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2885', '339', '翠屏区', '0,1,26,339', '2885', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2886', '339', '宜宾县', '0,1,26,339', '2886', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2887', '339', '南溪区', '0,1,26,339', '2887', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('2888', '339', '江安县', '0,1,26,339', '2888', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2889', '339', '长宁县', '0,1,26,339', '2889', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2890', '339', '高县', '0,1,26,339', '2890', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2891', '339', '珙县', '0,1,26,339', '2891', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2892', '339', '筠连县', '0,1,26,339', '2892', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2893', '339', '兴文县', '0,1,26,339', '2893', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2894', '339', '屏山县', '0,1,26,339', '2894', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2895', '340', '雁江区', '0,1,26,340', '2895', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2896', '340', '简阳市', '0,1,26,340', '2896', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2897', '340', '安岳县', '0,1,26,340', '2897', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('2898', '340', '乐至县', '0,1,26,340', '2898', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2899', '341', '大安区', '0,1,26,341', '2899', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2900', '341', '自流井区', '0,1,26,341', '2900', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2901', '341', '贡井区', '0,1,26,341', '2901', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2902', '341', '沿滩区', '0,1,26,341', '2902', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2903', '341', '荣县', '0,1,26,341', '2903', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('2904', '341', '富顺县', '0,1,26,341', '2904', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('2905', '342', '江阳区', '0,1,26,342', '2905', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2906', '342', '纳溪区', '0,1,26,342', '2906', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('2907', '342', '龙马潭区', '0,1,26,342', '2907', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2908', '342', '泸县', '0,1,26,342', '2908', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2909', '342', '合江县', '0,1,26,342', '2909', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2910', '342', '叙永县', '0,1,26,342', '2910', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2911', '342', '古蔺县', '0,1,26,342', '2911', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2912', '27', '和平区', '0,1,27', '2912', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2913', '27', '河西区', '0,1,27', '2913', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2914', '27', '南开区', '0,1,27', '2914', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('2915', '27', '河北区', '0,1,27', '2915', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2916', '27', '河东区', '0,1,27', '2916', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2917', '27', '红桥区', '0,1,27', '2917', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2918', '27', '东丽区', '0,1,27', '2918', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2919', '27', '津南区', '0,1,27', '2919', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2920', '27', '西青区', '0,1,27', '2920', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2921', '27', '北辰区', '0,1,27', '2921', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2922', '27', '塘沽区', '0,1,27', '2922', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('2923', '27', '汉沽区', '0,1,27', '2923', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('2924', '27', '大港区', '0,1,27', '2924', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2925', '27', '武清区', '0,1,27', '2925', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('2926', '27', '宝坻区', '0,1,27', '2926', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2927', '27', '经济开发区', '0,1,27', '2927', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2928', '27', '宁河县', '0,1,27', '2928', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('2929', '27', '静海县', '0,1,27', '2929', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2930', '27', '蓟县', '0,1,27', '2930', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2931', '344', '城关区', '0,1,28,344', '2931', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2932', '344', '林周县', '0,1,28,344', '2932', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2933', '344', '当雄县', '0,1,28,344', '2933', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2934', '344', '尼木县', '0,1,28,344', '2934', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('2935', '344', '曲水县', '0,1,28,344', '2935', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2936', '344', '堆龙德庆县', '0,1,28,344', '2936', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2937', '344', '达孜县', '0,1,28,344', '2937', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2938', '344', '墨竹工卡县', '0,1,28,344', '2938', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2939', '345', '噶尔县', '0,1,28,345', '2939', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2940', '345', '普兰县', '0,1,28,345', '2940', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('2941', '345', '札达县', '0,1,28,345', '2941', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2942', '345', '日土县', '0,1,28,345', '2942', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('2943', '345', '革吉县', '0,1,28,345', '2943', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2944', '345', '改则县', '0,1,28,345', '2944', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2945', '345', '措勤县', '0,1,28,345', '2945', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2946', '346', '昌都县', '0,1,28,346', '2946', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2947', '346', '江达县', '0,1,28,346', '2947', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2948', '346', '贡觉县', '0,1,28,346', '2948', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2949', '346', '类乌齐县', '0,1,28,346', '2949', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2950', '346', '丁青县', '0,1,28,346', '2950', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2951', '346', '察雅县', '0,1,28,346', '2951', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2952', '346', '八宿县', '0,1,28,346', '2952', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2953', '346', '左贡县', '0,1,28,346', '2953', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2954', '346', '芒康县', '0,1,28,346', '2954', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2955', '346', '洛隆县', '0,1,28,346', '2955', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2956', '346', '边坝县', '0,1,28,346', '2956', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2957', '347', '林芝县', '0,1,28,347', '2957', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2958', '347', '工布江达县', '0,1,28,347', '2958', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2959', '347', '米林县', '0,1,28,347', '2959', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2960', '347', '墨脱县', '0,1,28,347', '2960', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('2961', '347', '波密县', '0,1,28,347', '2961', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2962', '347', '察隅县', '0,1,28,347', '2962', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2963', '347', '朗县', '0,1,28,347', '2963', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2964', '348', '那曲县', '0,1,28,348', '2964', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('2965', '348', '嘉黎县', '0,1,28,348', '2965', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2966', '348', '比如县', '0,1,28,348', '2966', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2967', '348', '聂荣县', '0,1,28,348', '2967', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('2968', '348', '安多县', '0,1,28,348', '2968', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('2969', '348', '申扎县', '0,1,28,348', '2969', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2970', '348', '索县', '0,1,28,348', '2970', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2971', '348', '班戈县', '0,1,28,348', '2971', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2972', '348', '巴青县', '0,1,28,348', '2972', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2973', '348', '尼玛县', '0,1,28,348', '2973', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('2974', '349', '日喀则市', '0,1,28,349', '2974', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('2975', '349', '南木林县', '0,1,28,349', '2975', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('2976', '349', '江孜县', '0,1,28,349', '2976', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2977', '349', '定日县', '0,1,28,349', '2977', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2978', '349', '萨迦县', '0,1,28,349', '2978', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2979', '349', '拉孜县', '0,1,28,349', '2979', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('2980', '349', '昂仁县', '0,1,28,349', '2980', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('2981', '349', '谢通门县', '0,1,28,349', '2981', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('2982', '349', '白朗县', '0,1,28,349', '2982', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('2983', '349', '仁布县', '0,1,28,349', '2983', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('2984', '349', '康马县', '0,1,28,349', '2984', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('2985', '349', '定结县', '0,1,28,349', '2985', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('2986', '349', '仲巴县', '0,1,28,349', '2986', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2987', '349', '亚东县', '0,1,28,349', '2987', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('2988', '349', '吉隆县', '0,1,28,349', '2988', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('2989', '349', '聂拉木县', '0,1,28,349', '2989', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('2990', '349', '萨嘎县', '0,1,28,349', '2990', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2991', '349', '岗巴县', '0,1,28,349', '2991', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2992', '350', '乃东县', '0,1,28,350', '2992', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('2993', '350', '扎囊县', '0,1,28,350', '2993', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('2994', '350', '贡嘎县', '0,1,28,350', '2994', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('2995', '350', '桑日县', '0,1,28,350', '2995', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('2996', '350', '琼结县', '0,1,28,350', '2996', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2997', '350', '曲松县', '0,1,28,350', '2997', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('2998', '350', '措美县', '0,1,28,350', '2998', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('2999', '350', '洛扎县', '0,1,28,350', '2999', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3000', '350', '加查县', '0,1,28,350', '3000', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3001', '350', '隆子县', '0,1,28,350', '3001', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3002', '350', '错那县', '0,1,28,350', '3002', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('3003', '350', '浪卡子县', '0,1,28,350', '3003', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3004', '351', '天山区', '0,1,29,351', '3004', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3005', '351', '沙依巴克区', '0,1,29,351', '3005', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3006', '351', '新市区', '0,1,29,351', '3006', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3007', '351', '水磨沟区', '0,1,29,351', '3007', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3008', '351', '头屯河区', '0,1,29,351', '3008', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3009', '351', '达坂城区', '0,1,29,351', '3009', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3010', '351', '米东区', '0,1,29,351', '3010', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('3011', '351', '乌鲁木齐县', '0,1,29,351', '3011', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3012', '352', '阿克苏市', '0,1,29,352', '3012', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('3013', '352', '温宿县', '0,1,29,352', '3013', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3014', '352', '库车县', '0,1,29,352', '3014', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('3015', '352', '沙雅县', '0,1,29,352', '3015', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3016', '352', '新和县', '0,1,29,352', '3016', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3017', '352', '拜城县', '0,1,29,352', '3017', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3018', '352', '乌什县', '0,1,29,352', '3018', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3019', '352', '阿瓦提县', '0,1,29,352', '3019', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('3020', '352', '柯坪县', '0,1,29,352', '3020', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('3022', '354', '库尔勒市', '0,1,29,354', '3022', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('3023', '354', '轮台县', '0,1,29,354', '3023', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3024', '354', '尉犁县', '0,1,29,354', '3024', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3025', '354', '若羌县', '0,1,29,354', '3025', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('3026', '354', '且末县', '0,1,29,354', '3026', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('3027', '354', '焉耆回族自治县', '0,1,29,354', '3027', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3028', '354', '和静县', '0,1,29,354', '3028', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3029', '354', '和硕县', '0,1,29,354', '3029', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3030', '354', '博湖县', '0,1,29,354', '3030', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3031', '355', '博乐市', '0,1,29,355', '3031', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3032', '355', '精河县', '0,1,29,355', '3032', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3033', '355', '温泉县', '0,1,29,355', '3033', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3034', '356', '呼图壁县', '0,1,29,356', '3034', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3035', '356', '米泉市', '0,1,29,356', '3035', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('3036', '356', '昌吉市', '0,1,29,356', '3036', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('3037', '356', '阜康市', '0,1,29,356', '3037', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('3038', '356', '玛纳斯县', '0,1,29,356', '3038', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('3039', '356', '奇台县', '0,1,29,356', '3039', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('3040', '356', '吉木萨尔县', '0,1,29,356', '3040', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3041', '356', '木垒哈萨克自治县', '0,1,29,356', '3041', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('3042', '357', '哈密市', '0,1,29,357', '3042', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3043', '357', '伊吾县', '0,1,29,357', '3043', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3044', '357', '巴里坤哈萨克自治县', '0,1,29,357', '3044', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3045', '358', '和田市', '0,1,29,358', '3045', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3046', '358', '和田县', '0,1,29,358', '3046', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3047', '358', '墨玉县', '0,1,29,358', '3047', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('3048', '358', '皮山县', '0,1,29,358', '3048', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('3049', '358', '洛浦县', '0,1,29,358', '3049', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3050', '358', '策勒县', '0,1,29,358', '3050', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('3051', '358', '于田县', '0,1,29,358', '3051', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3052', '358', '民丰县', '0,1,29,358', '3052', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('3053', '359', '喀什市', '0,1,29,359', '3053', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('3054', '359', '疏附县', '0,1,29,359', '3054', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3055', '359', '疏勒县', '0,1,29,359', '3055', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3056', '359', '英吉沙县', '0,1,29,359', '3056', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3057', '359', '泽普县', '0,1,29,359', '3057', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('3058', '359', '莎车县', '0,1,29,359', '3058', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3059', '359', '叶城县', '0,1,29,359', '3059', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3060', '359', '麦盖提县', '0,1,29,359', '3060', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('3061', '359', '岳普湖县', '0,1,29,359', '3061', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3062', '359', '伽师县', '0,1,29,359', '3062', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('3063', '359', '巴楚县', '0,1,29,359', '3063', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3064', '359', '塔什库尔干塔吉克自治县', '0,1,29,359', '3064', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3065', '360', '克拉玛依市', '0,1,29,360', '3065', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('3066', '361', '阿图什市', '0,1,29,361', '3066', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('3067', '361', '阿克陶县', '0,1,29,361', '3067', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('3068', '361', '阿合奇县', '0,1,29,361', '3068', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('3069', '361', '乌恰县', '0,1,29,361', '3069', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3072', '364', '吐鲁番市', '0,1,29,364', '3072', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3073', '364', '鄯善县', '0,1,29,364', '3073', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3074', '364', '托克逊县', '0,1,29,364', '3074', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3076', '366', '阿勒泰市', '0,1,29,366', '3076', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('3077', '366', '布克赛尔', '0,1,29,366', '3077', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3078', '366', '伊宁市', '0,1,29,366', '3078', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3079', '366', '布尔津县', '0,1,29,366', '3079', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3080', '366', '奎屯市', '0,1,29,366', '3080', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('3081', '366', '乌苏市', '0,1,29,366', '3081', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3082', '366', '额敏县', '0,1,29,366', '3082', '0', '', '0', '1', '', '', '', '', '0', 'E', '0', '');
INSERT INTO `zz_linkage` VALUES ('3083', '366', '富蕴县', '0,1,29,366', '3083', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('3084', '366', '伊宁县', '0,1,29,366', '3084', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3085', '366', '福海县', '0,1,29,366', '3085', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('3086', '366', '霍城县', '0,1,29,366', '3086', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3087', '366', '沙湾县', '0,1,29,366', '3087', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3088', '366', '巩留县', '0,1,29,366', '3088', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('3089', '366', '哈巴河县', '0,1,29,366', '3089', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3090', '366', '托里县', '0,1,29,366', '3090', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3091', '366', '青河县', '0,1,29,366', '3091', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('3092', '366', '新源县', '0,1,29,366', '3092', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3093', '366', '裕民县', '0,1,29,366', '3093', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3094', '366', '和布克赛尔', '0,1,29,366', '3094', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3095', '366', '吉木乃县', '0,1,29,366', '3095', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3096', '366', '昭苏县', '0,1,29,366', '3096', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('3097', '366', '特克斯县', '0,1,29,366', '3097', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3098', '366', '尼勒克县', '0,1,29,366', '3098', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('3099', '366', '察布查尔锡伯自治县', '0,1,29,366', '3099', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('3100', '367', '盘龙区', '0,1,30,367', '3100', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('3101', '367', '五华区', '0,1,30,367', '3101', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3102', '367', '官渡区', '0,1,30,367', '3102', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('3103', '367', '西山区', '0,1,30,367', '3103', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3104', '367', '东川区', '0,1,30,367', '3104', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3105', '367', '安宁市', '0,1,30,367', '3105', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('3106', '367', '呈贡县', '0,1,30,367', '3106', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('3107', '367', '晋宁县', '0,1,30,367', '3107', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3108', '367', '富民县', '0,1,30,367', '3108', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('3109', '367', '宜良县', '0,1,30,367', '3109', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3110', '367', '嵩明县', '0,1,30,367', '3110', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3111', '367', '石林彝族自治县', '0,1,30,367', '3111', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3112', '367', '禄劝彝族苗族自治县', '0,1,30,367', '3112', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3113', '367', '寻甸回族彝族自治县', '0,1,30,367', '3113', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3114', '368', '兰坪白族普米族自治县', '0,1,30,368', '3114', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3115', '368', '泸水县', '0,1,30,368', '3115', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3116', '368', '福贡县', '0,1,30,368', '3116', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('3117', '368', '贡山独龙族怒族自治县', '0,1,30,368', '3117', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('3118', '369', '宁洱哈尼族彝族自治县', '0,1,30,369', '3118', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('3119', '369', '思茅区', '0,1,30,369', '3119', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3120', '369', '墨江哈尼族自治县', '0,1,30,369', '3120', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('3121', '369', '景东彝族自治县', '0,1,30,369', '3121', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3122', '369', '景谷傣族彝族自治县', '0,1,30,369', '3122', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3123', '369', '镇沅彝族哈尼族拉枯族自治县', '0,1,30,369', '3123', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('3124', '369', '江城哈尼族彝族自治县', '0,1,30,369', '3124', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3125', '369', '孟连傣族拉枯族佤族自治县', '0,1,30,369', '3125', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('3126', '369', '澜沧拉枯族自治县', '0,1,30,369', '3126', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3127', '369', '西盟佤族自治县', '0,1,30,369', '3127', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3128', '370', '古城区', '0,1,30,370', '3128', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('3129', '370', '宁蒗彝族自治县', '0,1,30,370', '3129', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('3130', '370', '玉龙纳西族自治县', '0,1,30,370', '3130', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3131', '370', '永胜县', '0,1,30,370', '3131', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3132', '370', '华坪县', '0,1,30,370', '3132', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3133', '371', '隆阳区', '0,1,30,371', '3133', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3134', '371', '施甸县', '0,1,30,371', '3134', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3135', '371', '腾冲县', '0,1,30,371', '3135', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3136', '371', '龙陵县', '0,1,30,371', '3136', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3137', '371', '昌宁县', '0,1,30,371', '3137', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('3138', '372', '楚雄市', '0,1,30,372', '3138', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('3139', '372', '双柏县', '0,1,30,372', '3139', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3140', '372', '牟定县', '0,1,30,372', '3140', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('3141', '372', '南华县', '0,1,30,372', '3141', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('3142', '372', '姚安县', '0,1,30,372', '3142', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3143', '372', '大姚县', '0,1,30,372', '3143', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3144', '372', '永仁县', '0,1,30,372', '3144', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3145', '372', '元谋县', '0,1,30,372', '3145', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3146', '372', '武定县', '0,1,30,372', '3146', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3147', '372', '禄丰县', '0,1,30,372', '3147', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3148', '373', '大理市', '0,1,30,373', '3148', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3149', '373', '祥云县', '0,1,30,373', '3149', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3150', '373', '宾川县', '0,1,30,373', '3150', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3151', '373', '弥渡县', '0,1,30,373', '3151', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('3152', '373', '永平县', '0,1,30,373', '3152', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3153', '373', '云龙县', '0,1,30,373', '3153', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3154', '373', '洱源县', '0,1,30,373', '3154', '0', '', '0', '1', '', '', '', '', '0', 'E', '0', '');
INSERT INTO `zz_linkage` VALUES ('3155', '373', '剑川县', '0,1,30,373', '3155', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3156', '373', '鹤庆县', '0,1,30,373', '3156', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3157', '373', '漾濞彝族回族自治县', '0,1,30,373', '3157', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3158', '373', '南涧彝族自治县', '0,1,30,373', '3158', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('3159', '373', '巍山彝族回族自治县', '0,1,30,373', '3159', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3160', '374', '潞西市', '0,1,30,374', '3160', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3161', '374', '瑞丽市', '0,1,30,374', '3161', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('3162', '374', '梁河县', '0,1,30,374', '3162', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3163', '374', '盈江县', '0,1,30,374', '3163', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3164', '374', '陇川县', '0,1,30,374', '3164', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3165', '375', '香格里拉县', '0,1,30,375', '3165', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3166', '375', '德钦县', '0,1,30,375', '3166', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3167', '375', '维西傈僳族自治县', '0,1,30,375', '3167', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3168', '376', '泸西县', '0,1,30,376', '3168', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3169', '376', '蒙自县', '0,1,30,376', '3169', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('3170', '376', '个旧市', '0,1,30,376', '3170', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('3171', '376', '开远市', '0,1,30,376', '3171', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('3172', '376', '绿春县', '0,1,30,376', '3172', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3173', '376', '建水县', '0,1,30,376', '3173', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3174', '376', '石屏县', '0,1,30,376', '3174', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3175', '376', '弥勒市', '0,1,30,376', '3175', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('3176', '376', '元阳县', '0,1,30,376', '3176', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3177', '376', '红河县', '0,1,30,376', '3177', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3178', '376', '金平苗族瑶族傣族自治县', '0,1,30,376', '3178', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3179', '376', '河口苗族自治县', '0,1,30,376', '3179', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3180', '376', '屏边苗族自治县', '0,1,30,376', '3180', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('3181', '377', '临翔区', '0,1,30,377', '3181', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3182', '377', '凤庆县', '0,1,30,377', '3182', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('3183', '377', '云县', '0,1,30,377', '3183', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3184', '377', '永德县', '0,1,30,377', '3184', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3185', '377', '镇康县', '0,1,30,377', '3185', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('3186', '377', '双江拉枯族佤族布朗族傣族自治县', '0,1,30,377', '3186', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3187', '377', '耿马傣族佤族自治县', '0,1,30,377', '3187', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('3188', '377', '沧源佤族自治县', '0,1,30,377', '3188', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('3189', '378', '麒麟区', '0,1,30,378', '3189', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('3190', '378', '宣威市', '0,1,30,378', '3190', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3191', '378', '马龙县', '0,1,30,378', '3191', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('3192', '378', '陆良县', '0,1,30,378', '3192', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3193', '378', '师宗县', '0,1,30,378', '3193', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3194', '378', '罗平县', '0,1,30,378', '3194', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3195', '378', '富源县', '0,1,30,378', '3195', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('3196', '378', '会泽县', '0,1,30,378', '3196', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3197', '378', '沾益县', '0,1,30,378', '3197', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('3198', '379', '文山市', '0,1,30,379', '3198', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3199', '379', '砚山县', '0,1,30,379', '3199', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3200', '379', '西畴县', '0,1,30,379', '3200', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3201', '379', '麻栗坡县', '0,1,30,379', '3201', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('3202', '379', '马关县', '0,1,30,379', '3202', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('3203', '379', '丘北县', '0,1,30,379', '3203', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('3204', '379', '广南县', '0,1,30,379', '3204', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('3205', '379', '富宁县', '0,1,30,379', '3205', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('3206', '380', '景洪市', '0,1,30,380', '3206', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3207', '380', '勐海县', '0,1,30,380', '3207', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('3208', '380', '勐腊县', '0,1,30,380', '3208', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('3209', '381', '红塔区', '0,1,30,381', '3209', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3210', '381', '江川县', '0,1,30,381', '3210', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3211', '381', '澄江县', '0,1,30,381', '3211', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('3212', '381', '通海县', '0,1,30,381', '3212', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3213', '381', '华宁县', '0,1,30,381', '3213', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3214', '381', '易门县', '0,1,30,381', '3214', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3215', '381', '峨山彝族自治县', '0,1,30,381', '3215', '0', '', '0', '1', '', '', '', '', '0', 'E', '0', '');
INSERT INTO `zz_linkage` VALUES ('3216', '381', '新平彝族傣族自治县', '0,1,30,381', '3216', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3217', '381', '元江哈尼族彝族傣族自治县', '0,1,30,381', '3217', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3218', '382', '昭阳区', '0,1,30,382', '3218', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('3219', '382', '鲁甸县', '0,1,30,382', '3219', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3220', '382', '巧家县', '0,1,30,382', '3220', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('3221', '382', '盐津县', '0,1,30,382', '3221', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3222', '382', '大关县', '0,1,30,382', '3222', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3223', '382', '永善县', '0,1,30,382', '3223', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3224', '382', '绥江县', '0,1,30,382', '3224', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3225', '382', '镇雄县', '0,1,30,382', '3225', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('3226', '382', '彝良县', '0,1,30,382', '3226', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3227', '382', '威信县', '0,1,30,382', '3227', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3228', '382', '水富县', '0,1,30,382', '3228', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3229', '383', '西湖区', '0,1,31,383', '3229', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3230', '383', '上城区', '0,1,31,383', '3230', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3231', '383', '下城区', '0,1,31,383', '3231', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3232', '383', '拱墅区', '0,1,31,383', '3232', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('3233', '383', '滨江区', '0,1,31,383', '3233', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3234', '383', '江干区', '0,1,31,383', '3234', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3235', '383', '萧山区', '0,1,31,383', '3235', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3236', '383', '余杭区', '0,1,31,383', '3236', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3237', '383', '市郊', '0,1,31,383', '3237', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3238', '383', '建德市', '0,1,31,383', '3238', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3239', '383', '富阳市', '0,1,31,383', '3239', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('3240', '383', '临安市', '0,1,31,383', '3240', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3241', '383', '桐庐县', '0,1,31,383', '3241', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3242', '383', '淳安县', '0,1,31,383', '3242', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('3243', '384', '吴兴区', '0,1,31,384', '3243', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3244', '384', '南浔区', '0,1,31,384', '3244', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('3245', '384', '德清县', '0,1,31,384', '3245', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3246', '384', '长兴县', '0,1,31,384', '3246', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('3247', '384', '安吉县', '0,1,31,384', '3247', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('3248', '385', '南湖区', '0,1,31,385', '3248', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('3249', '385', '秀洲区', '0,1,31,385', '3249', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3250', '385', '海宁市', '0,1,31,385', '3250', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3251', '385', '嘉善县', '0,1,31,385', '3251', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3252', '385', '平湖市', '0,1,31,385', '3252', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('3253', '385', '桐乡市', '0,1,31,385', '3253', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3254', '385', '海盐县', '0,1,31,385', '3254', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3255', '386', '婺城区', '0,1,31,386', '3255', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3256', '386', '金东区', '0,1,31,386', '3256', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3257', '386', '兰溪市', '0,1,31,386', '3257', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3258', '386', '市区', '0,1,31,386', '3258', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3259', '386', '佛堂镇', '0,1,31,386', '3259', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('3260', '386', '上溪镇', '0,1,31,386', '3260', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3261', '386', '义亭镇', '0,1,31,386', '3261', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3262', '386', '大陈镇', '0,1,31,386', '3262', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3263', '386', '苏溪镇', '0,1,31,386', '3263', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3264', '386', '赤岸镇', '0,1,31,386', '3264', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('3265', '386', '东阳市', '0,1,31,386', '3265', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3266', '386', '永康市', '0,1,31,386', '3266', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3267', '386', '武义县', '0,1,31,386', '3267', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3268', '386', '浦江县', '0,1,31,386', '3268', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('3269', '386', '磐安县', '0,1,31,386', '3269', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('3270', '387', '莲都区', '0,1,31,387', '3270', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3271', '387', '龙泉市', '0,1,31,387', '3271', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3272', '387', '青田县', '0,1,31,387', '3272', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('3273', '387', '缙云县', '0,1,31,387', '3273', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3274', '387', '遂昌县', '0,1,31,387', '3274', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3275', '387', '松阳县', '0,1,31,387', '3275', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3276', '387', '云和县', '0,1,31,387', '3276', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3277', '387', '庆元县', '0,1,31,387', '3277', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('3278', '387', '景宁畲族自治县', '0,1,31,387', '3278', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3279', '388', '海曙区', '0,1,31,388', '3279', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3280', '388', '江东区', '0,1,31,388', '3280', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3281', '388', '江北区', '0,1,31,388', '3281', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3282', '388', '镇海区', '0,1,31,388', '3282', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('3283', '388', '北仑区', '0,1,31,388', '3283', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3284', '388', '鄞州区', '0,1,31,388', '3284', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3285', '388', '余姚市', '0,1,31,388', '3285', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3286', '388', '慈溪市', '0,1,31,388', '3286', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('3287', '388', '奉化市', '0,1,31,388', '3287', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('3288', '388', '象山县', '0,1,31,388', '3288', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3289', '388', '宁海县', '0,1,31,388', '3289', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('3290', '389', '越城区', '0,1,31,389', '3290', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3291', '389', '上虞市', '0,1,31,389', '3291', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3292', '389', '嵊州市', '0,1,31,389', '3292', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3293', '389', '绍兴县', '0,1,31,389', '3293', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3294', '389', '新昌县', '0,1,31,389', '3294', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3295', '389', '诸暨市', '0,1,31,389', '3295', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('3296', '390', '椒江区', '0,1,31,390', '3296', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3297', '390', '黄岩区', '0,1,31,390', '3297', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3298', '390', '路桥区', '0,1,31,390', '3298', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3299', '390', '温岭市', '0,1,31,390', '3299', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3300', '390', '临海市', '0,1,31,390', '3300', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3301', '390', '玉环县', '0,1,31,390', '3301', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3302', '390', '三门县', '0,1,31,390', '3302', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3303', '390', '天台县', '0,1,31,390', '3303', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3304', '390', '仙居县', '0,1,31,390', '3304', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3305', '391', '鹿城区', '0,1,31,391', '3305', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3306', '391', '龙湾区', '0,1,31,391', '3306', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3307', '391', '瓯海区', '0,1,31,391', '3307', '0', '', '0', '1', '', '', '', '', '0', 'O', '0', '');
INSERT INTO `zz_linkage` VALUES ('3308', '391', '瑞安市', '0,1,31,391', '3308', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('3309', '391', '乐清市', '0,1,31,391', '3309', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3310', '391', '洞头县', '0,1,31,391', '3310', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3311', '391', '永嘉县', '0,1,31,391', '3311', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3312', '391', '平阳县', '0,1,31,391', '3312', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('3313', '391', '苍南县', '0,1,31,391', '3313', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('3314', '391', '文成县', '0,1,31,391', '3314', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3315', '391', '泰顺县', '0,1,31,391', '3315', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3316', '392', '定海区', '0,1,31,392', '3316', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3317', '392', '普陀区', '0,1,31,392', '3317', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('3318', '392', '岱山县', '0,1,31,392', '3318', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3319', '392', '嵊泗县', '0,1,31,392', '3319', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3320', '393', '衢州市', '0,1,31,393', '3320', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('3321', '393', '江山市', '0,1,31,393', '3321', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3322', '393', '常山县', '0,1,31,393', '3322', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('3323', '393', '开化县', '0,1,31,393', '3323', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('3324', '393', '龙游县', '0,1,31,393', '3324', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3325', '32', '合川区', '0,1,32', '3325', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3326', '32', '江津区', '0,1,32', '3326', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3327', '32', '南川区', '0,1,32', '3327', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('3328', '32', '永川区', '0,1,32', '3328', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3329', '32', '南岸区', '0,1,32', '3329', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('3330', '32', '渝北区', '0,1,32', '3330', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3331', '32', '万盛区', '0,1,32', '3331', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3332', '32', '大渡口区', '0,1,32', '3332', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3333', '32', '万州区', '0,1,32', '3333', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3334', '32', '北碚区', '0,1,32', '3334', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3335', '32', '沙坪坝区', '0,1,32', '3335', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3336', '32', '巴南区', '0,1,32', '3336', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3337', '32', '涪陵区', '0,1,32', '3337', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('3338', '32', '江北区', '0,1,32', '3338', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3339', '32', '九龙坡区', '0,1,32', '3339', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3340', '32', '渝中区', '0,1,32', '3340', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3341', '32', '黔江开发区', '0,1,32', '3341', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('3342', '32', '长寿区', '0,1,32', '3342', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('3343', '32', '双桥区', '0,1,32', '3343', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3344', '32', '綦江县', '0,1,32', '3344', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('3345', '32', '潼南县', '0,1,32', '3345', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3346', '32', '铜梁县', '0,1,32', '3346', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3347', '32', '大足区', '0,1,32', '3347', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3348', '32', '荣昌县', '0,1,32', '3348', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('3349', '32', '璧山县', '0,1,32', '3349', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3350', '32', '垫江县', '0,1,32', '3350', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3351', '32', '武隆县', '0,1,32', '3351', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3352', '32', '丰都县', '0,1,32', '3352', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('3353', '32', '城口县', '0,1,32', '3353', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('3354', '32', '梁平县', '0,1,32', '3354', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3355', '32', '开县', '0,1,32', '3355', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('3356', '32', '巫溪县', '0,1,32', '3356', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3357', '32', '巫山县', '0,1,32', '3357', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3358', '32', '奉节县', '0,1,32', '3358', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('3359', '32', '云阳县', '0,1,32', '3359', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3360', '32', '忠县', '0,1,32', '3360', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('3361', '32', '石柱土家族自治县', '0,1,32', '3361', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3362', '32', '彭水苗族土家族自治县', '0,1,32', '3362', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('3363', '32', '酉阳土家族苗族自治县', '0,1,32', '3363', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3364', '32', '秀山土家族苗族自治县', '0,1,32', '3364', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3365', '33', '沙田区', '0,1,33', '3365', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3366', '33', '东区', '0,1,33', '3366', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3367', '33', '观塘区', '0,1,33', '3367', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('3368', '33', '黄大仙区', '0,1,33', '3368', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3369', '33', '九龙城区', '0,1,33', '3369', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3370', '33', '屯门区', '0,1,33', '3370', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3371', '33', '葵青区', '0,1,33', '3371', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('3372', '33', '元朗区', '0,1,33', '3372', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3373', '33', '深水埗区', '0,1,33', '3373', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3374', '33', '西贡区', '0,1,33', '3374', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3375', '33', '大埔区', '0,1,33', '3375', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3376', '33', '湾仔区', '0,1,33', '3376', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3377', '33', '油尖旺区', '0,1,33', '3377', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3378', '33', '北区', '0,1,33', '3378', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3379', '33', '南区', '0,1,33', '3379', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('3380', '33', '荃湾区', '0,1,33', '3380', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('3381', '33', '中西区', '0,1,33', '3381', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('3382', '33', '离岛区', '0,1,33', '3382', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3384', '35', '台北', '0,1,35', '3384,3852,3853,3854,3855,3856,3857,3858,3859,3860,3861,3862,3863,3864', '1', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3385', '35', '高雄', '0,1,35', '3385,3865,3866,3867,3868,3869,3870,3871,3872,3873,3874,3875,3876,3877,3878,3879,3880,3881,3882,3883,3884,3885,3886,3887,3888,3889,3890,3891,3892,3893,3894,3895,3896,3897,3898,3899,3900,3901', '1', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('3386', '35', '基隆', '0,1,35', '3386,3976,3977,3978,3979,3980,3981,3982,3983', '1', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3387', '35', '台中', '0,1,35', '3387,3939,3940,3941,3942,3943,3944,3945,3946,3947,3948,3949,3950,3951,3952,3953,3954,3955,3956,3957,3958,3959,3960,3961,3962,3963,3964,3965,3966,3967,3968,4407', '1', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3388', '35', '台南', '0,1,35', '3388,3902,3903,3904,3905,3906,3907,3908,3909,3910,3911,3912,3913,3914,3915,3916,3917,3918,3919,3920,3921,3922,3923,3924,3925,3926,3927,3928,3929,3930,3931,3932,3933,3934,3935,3936,3937,3938', '1', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3389', '35', '新竹', '0,1,35', '3389,4421,4422,4423,4424', '1', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3390', '35', '嘉义', '0,1,35', '3390,3984,3985,3986', '1', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3391', '35', '宜兰县', '0,1,35', '3391,4425,4426,4427,4428,4429,4430,4431,4432,4433,4434,4435,4436,4437', '1', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3392', '35', '桃园县', '0,1,35', '3392,4452,4453,4454,4455,4456,4457,4458,4459,4460,4461,4462,4463,4464', '1', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3393', '35', '苗栗县', '0,1,35', '3393,4465,4466,4467,4468,4469,4470,4471,4472,4473,4474,4475,4476,4477,4478,4479,4480,4481,4482', '1', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('3394', '35', '彰化县', '0,1,35', '3394,4483,4484,4485,4486,4487,4488,4489,4490,4491,4492,4493,4494,4495,4496,4497,4498,4499,4500,4501,4502,4503,4504,4505,4506,4507,4508', '1', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('3395', '35', '南投县', '0,1,35', '3395,4408,4409,4410,4411,4412,4413,4414,4415,4416,4417,4418,4419,4420', '1', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('3396', '35', '云林县', '0,1,35', '3396,4528,4529,4530,4531,4532,4533,4534,4535,4536,4537,4538,4539,4540,4541,4542,4543,4544,4545,4546,4547', '1', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3397', '35', '屏东县', '0,1,35', '3397,4548,4549,4550,4551,4552,4553,4554,4555,4556,4557,4558,4559,4560,4561,4562,4563,4564,4565,4566,4567,4568,4569,4570,4571,4572,4573,4574,4575,4576,4577,4578,4579', '1', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('3398', '35', '台东县', '0,1,35', '3398,4580,4581,4582,4583,4584,4585,4586,4587,4588,4589,4590,4591,4592,4593,4594,4595', '1', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3399', '35', '花莲县', '0,1,35', '3399,4596,4597,4598,4599,4600,4601,4602,4603,4604,4605,4606,4607,4608,4609', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3400', '35', '澎湖县', '0,1,35', '3400,4610,4611,4612,4613,4614,4615', '1', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('3401', '3', '合肥', '0,1,3', '3401,3402,3403,3404,3405,3406,3407,3408,3446,3447', '1', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3402', '3401', '庐阳区', '0,1,3,3401', '3402', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3403', '3401', '瑶海区', '0,1,3,3401', '3403', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3404', '3401', '蜀山区', '0,1,3,3401', '3404', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3405', '3401', '包河区', '0,1,3,3401', '3405', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3406', '3401', '长丰县', '0,1,3,3401', '3406', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('3407', '3401', '肥东县', '0,1,3,3401', '3407', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('3408', '3401', '肥西县', '0,1,3,3401', '3408', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('3419', '597', '杜浔', '0,1,4,61,597', '3419', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3421', '597', '沙西', '0,1,4,61,597', '3421', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3422', '2', '其他区', '0,1,2', '3422', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3423', '27', '滨海新区', '0,1,27', '3423', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3424', '27', '其他区', '0,1,27', '3424', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3427', '138', '其他区', '0,1,10,138', '3427', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3428', '146', '曹妃甸区', '0,1,10,146', '3428', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('3429', '146', '其他区', '0,1,10,146', '3429', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3430', '145', '青龙满族自治县', '0,1,10,145', '3430', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('3431', '145', '其他区', '0,1,10,145', '3431', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3432', '142', '其他区', '0,1,10,142', '3432', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3433', '147', '其他区', '0,1,10,147', '3433', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3434', '139', '其他区', '0,1,10,139', '3434', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3435', '148', '其他区', '0,1,10,148', '3435', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3436', '141', '其他区', '0,1,10,141', '3436', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3437', '140', '其他区', '0,1,10,140', '3437', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3438', '144', '其他区', '0,1,10,144', '3438', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3439', '143', '其他区', '0,1,10,143', '3439', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3440', '36', '其他区', '0,1,3,36', '3440', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3441', '37', '龙子湖区', '0,1,3,37', '3441', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3442', '37', '蚌山区', '0,1,3,37', '3442', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3443', '37', '禹会区', '0,1,3,37', '3443', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3444', '37', '淮上区', '0,1,3,37', '3444', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3445', '37', '其他区', '0,1,3,37', '3445', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3446', '3401', '巢湖市', '0,1,3,3401', '3446', '0', '', '0', '1', '', '', '', '', '0', 'C', '0', '');
INSERT INTO `zz_linkage` VALUES ('3447', '3401', '庐江县', '0,1,3,3401', '3447', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3448', '49', '无为县', '0,1,3,49', '3448', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3449', '49', '其他区', '0,1,3,49', '3449', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3450', '43', '寿县', '0,1,3,43', '3450', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3451', '43', '其他区', '0,1,3,43', '3451', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3452', '46', '博望区', '0,1,3,46', '3452', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3453', '46', '含山县', '0,1,3,46', '3453', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3454', '46', '和县', '0,1,3,46', '3454', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3455', '46', '其他区', '0,1,3,46', '3455', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3456', '42', '其他区', '0,1,3,42', '3456', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3457', '48', '枞阳县', '0,1,3,48', '3457', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('3458', '44', '其他区', '0,1,3,44', '3458', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3459', '40', '其他区', '0,1,3,40', '3459', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3460', '41', '其他区', '0,1,3,41', '3460', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3461', '47', '其他区', '0,1,3,47', '3461', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3462', '45', '叶集区', '0,1,3,45', '3462', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3463', '51', '其他区', '0,1,3,51', '3463', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3464', '39', '其他区', '0,1,3,39', '3464', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3465', '50', '其他区', '0,1,3,50', '3465', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3466', '53', '其他区', '0,1,4,53', '3466', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3467', '60', '其他区', '0,1,4,60', '3467', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3468', '57', '其他区', '0,1,4,57', '3468', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3469', '59', '其他区', '0,1,4,59', '3469', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3470', '58', '其他区', '0,1,4,58', '3470', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3471', '61', '其他区', '0,1,4,61', '3471', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3472', '55', '其他区', '0,1,4,55', '3472', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3473', '54', '其他区', '0,1,4,54', '3473', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3474', '56', '其他区', '0,1,4,56', '3474', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3475', '62', '其他区', '0,1,5,62', '3475', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3476', '67', '其他区', '0,1,5,67', '3476', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3477', '63', '其他区', '0,1,5,63', '3477', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3478', '73', '其他区', '0,1,5,73', '3478', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3479', '74', '其他区', '0,1,5,74', '3479', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3480', '75', '其他区', '0,1,5,75', '3480', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3481', '71', '其他区', '0,1,5,71', '3481', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3482', '68', '其他区', '0,1,5,68', '3482', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3483', '72', '其他区', '0,1,5,72', '3483', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3484', '64', '其他区', '0,1,5,64', '3484', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3485', '70', '其他区', '0,1,5,70', '3485', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3486', '69', '其他区', '0,1,5,69', '3486', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3487', '65', '其他区', '0,1,5,65', '3487', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3488', '76', '南沙区', '0,1,6,76', '3488', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('3489', '76', '萝岗区', '0,1,6,76', '3489', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3490', '76', '其他区', '0,1,6,76', '3490', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3491', '90', '其他区', '0,1,6,90', '3491', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3492', '77', '其他区', '0,1,6,77', '3492', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3493', '96', '其他区', '0,1,6,96', '3493', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3494', '88', '其他区', '0,1,6,88', '3494', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3495', '80', '其他区', '0,1,6,80', '3495', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3496', '83', '其他区', '0,1,6,83', '3496', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3497', '93', '其他区', '0,1,6,93', '3497', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3498', '85', '其他区', '0,1,6,85', '3498', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3499', '94', '端州区', '0,1,6,94', '3499', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3500', '94', '鼎湖区', '0,1,6,94', '3500', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3501', '94', '其他区', '0,1,6,94', '3501', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3502', '82', '其他区', '0,1,6,82', '3502', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3503', '86', '其他区', '0,1,6,86', '3503', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3504', '89', '其他区', '0,1,6,89', '3504', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3505', '81', '其他区', '0,1,6,81', '3505', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3506', '91', '其他区', '0,1,6,91', '3506', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3507', '87', '其他区', '0,1,6,87', '3507', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3508', '6', '东沙群岛', '0,1,6', '3508', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3509', '78', '其他区', '0,1,6,78', '3509', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3510', '84', '其他区', '0,1,6,84', '3510', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3511', '92', '其他区', '0,1,6,92', '3511', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3512', '97', '其他区', '0,1,7,97', '3512', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3513', '107', '其他区', '0,1,7,107', '3513', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3514', '98', '其他区', '0,1,7,98', '3514', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3515', '109', '龙圩区', '0,1,7,109', '3515', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3516', '109', '其他区', '0,1,7,109', '3516', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3517', '100', '其他区', '0,1,7,100', '3517', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3518', '102', '其他区', '0,1,7,102', '3518', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3519', '108', '其他区', '0,1,7,108', '3519', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3520', '103', '其他区', '0,1,7,103', '3520', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3521', '110', '福绵区', '0,1,7,110', '3521', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('3522', '110', '其他区', '0,1,7,110', '3522', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3523', '99', '其他区', '0,1,7,99', '3523', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3524', '105', '其他区', '0,1,7,105', '3524', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3525', '104', '其他区', '0,1,7,104', '3525', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3526', '106', '其他区', '0,1,7,106', '3526', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3527', '101', '其他区', '0,1,7,101', '3527', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3528', '111', '观山湖区', '0,1,8,111', '3528', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('3529', '111', '其他区', '0,1,8,111', '3529', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3530', '114', '其他区', '0,1,8,114', '3530', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3531', '119', '其他区', '0,1,8,119', '3531', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3532', '118', '碧江区', '0,1,8,118', '3532', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3533', '118', '其他区', '0,1,8,118', '3533', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3534', '117', '其他区', '0,1,8,117', '3534', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3535', '113', '七星关区', '0,1,8,113', '3535', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('3536', '113', '其他区', '0,1,8,113', '3536', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3537', '115', '其他区', '0,1,8,115', '3537', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3538', '116', '其他区', '0,1,8,116', '3538', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3539', '120', '其他区', '0,1,9,120', '3539', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3540', '9', '三沙市', '0,1,9', '3540,3541,3542,3543', '1', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3541', '3540', '西沙群岛', '0,1,9,3540', '3541', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3542', '3540', '南沙群岛', '0,1,9,3540', '3542', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('3543', '3540', '中沙群岛的岛礁及其海域', '0,1,9,3540', '3543', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('3544', '149', '其他区', '0,1,11,149', '3544', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3545', '151', '祥符区', '0,1,11,151', '3545', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3546', '151', '其他区', '0,1,11,151', '3546', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3547', '150', '其他区', '0,1,11,150', '3547', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3548', '157', '其他区', '0,1,11,157', '3548', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3549', '152', '其他区', '0,1,11,152', '3549', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3550', '153', '其他区', '0,1,11,153', '3550', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3551', '160', '其他区', '0,1,11,160', '3551', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3552', '155', '其他区', '0,1,11,155', '3552', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3553', '166', '其他区', '0,1,11,166', '3553', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3554', '162', '其他区', '0,1,11,162', '3554', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3555', '165', '其他区', '0,1,11,165', '3555', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3556', '158', '陕州区', '0,1,11,158', '3556', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3557', '158', '其他区', '0,1,11,158', '3557', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3558', '156', '其他区', '0,1,11,156', '3558', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3559', '159', '其他区', '0,1,11,159', '3559', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3560', '161', '其他区', '0,1,11,161', '3560', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3561', '163', '其他区', '0,1,11,163', '3561', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3562', '164', '其他区', '0,1,11,164', '3562', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3563', '167', '其他区', '0,1,12,167', '3563', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3564', '176', '其他区', '0,1,12,176', '3564', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3565', '172', '麻山区', '0,1,12,172', '3565', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('3566', '172', '其他区', '0,1,12,172', '3566', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3567', '170', '其他区', '0,1,12,170', '3567', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3568', '177', '其他区', '0,1,12,177', '3568', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3569', '168', '其他区', '0,1,12,168', '3569', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3570', '179', '其他区', '0,1,12,179', '3570', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3571', '173', '其他区', '0,1,12,173', '3571', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3572', '175', '其他区', '0,1,12,175', '3572', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3573', '174', '其他区', '0,1,12,174', '3573', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3574', '171', '其他区', '0,1,12,171', '3574', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3575', '178', '其他区', '0,1,12,178', '3575', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3576', '169', '松岭区', '0,1,12,169', '3576', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3577', '169', '加格达奇区', '0,1,12,169', '3577', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3578', '169', '其他区', '0,1,12,169', '3578', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3579', '180', '其他区', '0,1,13,180', '3579', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3580', '184', '其他区', '0,1,13,184', '3580', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3581', '189', '郧阳区', '0,1,13,189', '3581', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3582', '189', '其他区', '0,1,13,189', '3582', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3583', '195', '其他区', '0,1,13,195', '3583', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3584', '193', '襄州区', '0,1,13,193', '3584', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3585', '193', '其他区', '0,1,13,193', '3585', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3586', '182', '其他区', '0,1,13,182', '3586', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3587', '185', '其他区', '0,1,13,185', '3587', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3588', '194', '其他区', '0,1,13,194', '3588', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3590', '186', '其他区', '0,1,13,186', '3590', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3591', '183', '其他区', '0,1,13,183', '3591', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3592', '192', '其他区', '0,1,13,192', '3592', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3593', '190', '随县', '0,1,13,190', '3593', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3594', '190', '其他区', '0,1,13,190', '3594', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3595', '196', '其他区', '0,1,13,196', '3595', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3596', '197', '其他区', '0,1,14,197', '3596', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3597', '210', '其他区', '0,1,14,210', '3597', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3598', '205', '其他区', '0,1,14,205', '3598', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3599', '201', '其他区', '0,1,14,201', '3599', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3600', '204', '其他区', '0,1,14,204', '3600', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3601', '209', '其他区', '0,1,14,209', '3601', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3602', '199', '其他区', '0,1,14,199', '3602', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3603', '198', '其他区', '0,1,14,198', '3603', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3604', '207', '其他区', '0,1,14,207', '3604', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3605', '200', '其他区', '0,1,14,200', '3605', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3606', '208', '其他区', '0,1,14,208', '3606', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3607', '202', '其他区', '0,1,14,202', '3607', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3608', '203', '其他区', '0,1,14,203', '3608', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3609', '206', '其他区', '0,1,14,206', '3609', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3610', '211', '其他区', '0,1,15,211', '3610', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3611', '212', '其他区', '0,1,15,212', '3611', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3612', '216', '其他区', '0,1,15,216', '3612', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3613', '215', '其他区', '0,1,15,215', '3613', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3614', '218', '其他区', '0,1,15,218', '3614', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3615', '214', '浑江区', '0,1,15,214', '3615', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3616', '214', '其他区', '0,1,15,214', '3616', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3617', '217', '其他区', '0,1,15,217', '3617', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3618', '213', '其他区', '0,1,15,213', '3618', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3619', '219', '其他区', '0,1,15,219', '3619', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3620', '220', '其他区', '0,1,16,220', '3620', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3621', '222', '梁溪区', '0,1,16,222', '3621', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3622', '222', '新吴区', '0,1,16,222', '3622', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3623', '222', '其他区', '0,1,16,222', '3623', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3624', '229', '其他区', '0,1,16,229', '3624', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3625', '223', '其他区', '0,1,16,223', '3625', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3626', '221', '姑苏区', '0,1,16,221', '3626', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('3627', '221', '昆山市', '0,1,16,221', '3627', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('3628', '221', '其他区', '0,1,16,221', '3628', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3629', '226', '其他区', '0,1,16,226', '3629', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3630', '225', '其他区', '0,1,16,225', '3630', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3631', '224', '淮安区', '0,1,16,224', '3631', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3632', '224', '其他区', '0,1,16,224', '3632', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3633', '230', '其他区', '0,1,16,230', '3633', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3634', '231', '其他区', '0,1,16,231', '3634', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3635', '232', '其他区', '0,1,16,232', '3635', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3636', '228', '泰兴市', '0,1,16,228', '3636', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3637', '228', '其他区', '0,1,16,228', '3637', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3638', '227', '其他区', '0,1,16,227', '3638', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3639', '233', '其他区', '0,1,17,233', '3639', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3640', '237', '其他区', '0,1,17,237', '3640', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3641', '239', '其他区', '0,1,17,239', '3641', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3642', '238', '共青城市', '0,1,17,238', '3642', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('3643', '238', '其他区', '0,1,17,238', '3643', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3644', '241', '其他区', '0,1,17,241', '3644', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3645', '243', '其他区', '0,1,17,243', '3645', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3646', '235', '其他区', '0,1,17,235', '3646', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3647', '236', '其他区', '0,1,17,236', '3647', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3648', '242', '其他区', '0,1,17,242', '3648', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3649', '234', '其他区', '0,1,17,234', '3649', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3650', '240', '其他区', '0,1,17,240', '3650', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3651', '244', '新城子区', '0,1,18,244', '3651', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3652', '244', '其他区', '0,1,18,244', '3652', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3653', '245', '其他区', '0,1,18,245', '3653', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3654', '246', '其他区', '0,1,18,246', '3654', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3655', '250', '其他区', '0,1,18,250', '3655', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3656', '247', '其他区', '0,1,18,247', '3656', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3657', '249', '其他区', '0,1,18,249', '3657', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3658', '253', '其他区', '0,1,18,253', '3658', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3659', '257', '其他区', '0,1,18,257', '3659', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3660', '251', '其他区', '0,1,18,251', '3660', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3661', '254', '其他区', '0,1,18,254', '3661', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3662', '255', '其他区', '0,1,18,255', '3662', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3663', '256', '其他区', '0,1,18,256', '3663', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3664', '248', '其他区', '0,1,18,248', '3664', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3665', '252', '其他区', '0,1,18,252', '3665', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3666', '258', '其他区', '0,1,19,258', '3666', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3667', '261', '其他区', '0,1,19,261', '3667', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3668', '266', '其他区', '0,1,19,266', '3668', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3669', '262', '其他区', '0,1,19,262', '3669', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3670', '265', '其他区', '0,1,19,265', '3670', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3671', '263', '其他区', '0,1,19,263', '3671', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3672', '264', '扎赉诺尔区', '0,1,19,264', '3672', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('3673', '264', '其他区', '0,1,19,264', '3673', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3674', '260', '其他区', '0,1,19,260', '3674', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3675', '267', '其他区', '0,1,19,267', '3675', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3676', '269', '其他区', '0,1,19,269', '3676', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3677', '268', '其他区', '0,1,19,268', '3677', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3678', '259', '其他区', '0,1,19,259', '3678', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3679', '270', '其他区', '0,1,20,270', '3679', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3680', '272', '其他区', '0,1,20,272', '3680', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3681', '273', '红寺堡区', '0,1,20,273', '3681', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3682', '273', '其他区', '0,1,20,273', '3682', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3683', '271', '其他区', '0,1,20,271', '3683', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3684', '274', '其他区', '0,1,20,274', '3684', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3685', '275', '其他区', '0,1,21,275', '3685', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3686', '278', '其他区', '0,1,21,278', '3686', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3687', '277', '其他区', '0,1,21,277', '3687', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3688', '281', '其他区', '0,1,21,281', '3688', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3689', '279', '其他区', '0,1,21,279', '3689', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3690', '276', '其他区', '0,1,21,276', '3690', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3691', '282', '其他区', '0,1,21,282', '3691', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3692', '280', '其他区', '0,1,21,280', '3692', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3693', '283', '其他区', '0,1,22,283', '3693', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3694', '284', '其他区', '0,1,22,284', '3694', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3695', '299', '其他区', '0,1,22,299', '3695', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3696', '298', '其他区', '0,1,22,298', '3696', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3697', '287', '其他区', '0,1,22,287', '3697', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3698', '297', '其他区', '0,1,22,297', '3698', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3699', '296', '其他区', '0,1,22,296', '3699', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3700', '289', '其他区', '0,1,22,289', '3700', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3701', '294', '其他区', '0,1,22,294', '3701', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3702', '295', '其他区', '0,1,22,295', '3702', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3703', '293', '其他区', '0,1,22,293', '3703', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3704', '290', '其他区', '0,1,22,290', '3704', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3705', '292', '兰陵县', '0,1,22,292', '3705', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3706', '292', '其他区', '0,1,22,292', '3706', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3707', '286', '陵城区', '0,1,22,286', '3707', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3708', '286', '其他区', '0,1,22,286', '3708', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3709', '291', '其他区', '0,1,22,291', '3709', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3710', '285', '其他区', '0,1,22,285', '3710', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3711', '288', '其他区', '0,1,22,288', '3711', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3712', '300', '其他区', '0,1,23,300', '3712', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3713', '302', '其他区', '0,1,23,302', '3713', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3714', '309', '其他区', '0,1,23,309', '3714', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3715', '301', '其他区', '0,1,23,301', '3715', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3716', '303', '其他区', '0,1,23,303', '3716', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3717', '307', '其他区', '0,1,23,307', '3717', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3718', '304', '其他区', '0,1,23,304', '3718', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3719', '310', '其他区', '0,1,23,310', '3719', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3720', '308', '其他区', '0,1,23,308', '3720', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3721', '305', '其他区', '0,1,23,305', '3721', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3722', '306', '其他区', '0,1,23,306', '3722', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3723', '311', '其他区', '0,1,24,311', '3723', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3724', '316', '其他区', '0,1,24,316', '3724', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3725', '313', '其他区', '0,1,24,313', '3725', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3726', '318', '其他区', '0,1,24,318', '3726', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3727', '317', '其他区', '0,1,24,317', '3727', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3728', '319', '其他区', '0,1,24,319', '3728', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3729', '314', '其他区', '0,1,24,314', '3729', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3730', '320', '其他区', '0,1,24,320', '3730', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3731', '312', '其他区', '0,1,24,312', '3731', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3732', '315', '其他区', '0,1,24,315', '3732', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3733', '25', '其他区', '0,1,25', '3733', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3734', '322', '其他区', '0,1,26,322', '3734', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3735', '341', '其他区', '0,1,26,341', '3735', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3736', '336', '其他区', '0,1,26,336', '3736', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3737', '342', '其他区', '0,1,26,342', '3737', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3738', '327', '其他区', '0,1,26,327', '3738', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3739', '323', '其他区', '0,1,26,323', '3739', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3740', '330', '昭化区', '0,1,26,330', '3740', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('3741', '330', '其他区', '0,1,26,330', '3741', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3742', '337', '其他区', '0,1,26,337', '3742', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3743', '335', '其他区', '0,1,26,335', '3743', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3744', '331', '市中区', '0,1,26,331', '3744', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3745', '331', '沙湾区', '0,1,26,331', '3745', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3746', '331', '五通桥区', '0,1,26,331', '3746', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3747', '331', '金口河区', '0,1,26,331', '3747', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3748', '331', '其他区', '0,1,26,331', '3748', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3749', '334', '其他区', '0,1,26,334', '3749', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3750', '333', '其他区', '0,1,26,333', '3750', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3751', '339', '其他区', '0,1,26,339', '3751', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3752', '329', '前锋区', '0,1,26,329', '3752', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('3753', '329', '其他区', '0,1,26,329', '3753', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3754', '326', '达川区', '0,1,26,326', '3754', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3755', '326', '其他区', '0,1,26,326', '3755', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3756', '338', '其他区', '0,1,26,338', '3756', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3757', '325', '恩阳区', '0,1,26,325', '3757', '0', '', '0', '1', '', '', '', '', '0', 'E', '0', '');
INSERT INTO `zz_linkage` VALUES ('3758', '325', '其他区', '0,1,26,325', '3758', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3759', '340', '其他区', '0,1,26,340', '3759', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3760', '324', '其他区', '0,1,26,324', '3760', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3761', '328', '其他区', '0,1,26,328', '3761', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3762', '332', '其他区', '0,1,26,332', '3762', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3763', '344', '其他区', '0,1,28,344', '3763', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3764', '346', '卡诺区', '0,1,28,346', '3764', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('3765', '346', '其他区', '0,1,28,346', '3765', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3766', '350', '其他区', '0,1,28,350', '3766', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3767', '349', '桑珠孜区', '0,1,28,349', '3767', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3768', '349', '其他区', '0,1,28,349', '3768', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3769', '348', '双湖县', '0,1,28,348', '3769', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3770', '348', '其他区', '0,1,28,348', '3770', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3771', '345', '其他区', '0,1,28,345', '3771', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3772', '347', '巴宜区', '0,1,28,347', '3772', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3773', '347', '其他区', '0,1,28,347', '3773', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3774', '351', '其他区', '0,1,29,351', '3774', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3775', '360', '独子山区', '0,1,29,360', '3775', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3776', '360', '白碱滩区', '0,1,29,360', '3776', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3777', '360', '乌尔禾区', '0,1,29,360', '3777', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3778', '364', '高昌区', '0,1,29,364', '3778', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('3779', '364', '其他区', '0,1,29,364', '3779', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3780', '357', '其他区', '0,1,29,357', '3780', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3781', '356', '其他区', '0,1,29,356', '3781', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3782', '355', '阿拉山口市', '0,1,29,355', '3782', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('3783', '355', '其他区', '0,1,29,355', '3783', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3784', '354', '其他区', '0,1,29,354', '3784', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3785', '352', '其他区', '0,1,29,352', '3785', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3786', '361', '其他区', '0,1,29,361', '3786', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3787', '359', '其他区', '0,1,29,359', '3787', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3788', '358', '其他区', '0,1,29,358', '3788', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3789', '366', '霍尔果斯市', '0,1,29,366', '3789', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3790', '366', '其他区', '0,1,29,366', '3790', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3791', '29', '塔城地区', '0,1,29', '3791,3792,3793,3794,3795,3796,3797,3798,3799', '1', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3792', '3791', '塔城市', '0,1,29,3791', '3792', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3793', '3791', '乌苏市', '0,1,29,3791', '3793', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3794', '3791', '额敏县', '0,1,29,3791', '3794', '0', '', '0', '1', '', '', '', '', '0', 'E', '0', '');
INSERT INTO `zz_linkage` VALUES ('3795', '3791', '沙湾县', '0,1,29,3791', '3795', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3796', '3791', '托里县', '0,1,29,3791', '3796', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3797', '3791', '裕民县', '0,1,29,3791', '3797', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3798', '3791', '和布克赛尔蒙古自治县', '0,1,29,3791', '3798', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3799', '3791', '其他区', '0,1,29,3791', '3799', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3800', '29', '阿勒泰', '0,1,29', '3800,3801,3802,3803,3804,3805,3806,3807,3808', '1', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('3801', '3800', '阿勒泰', '0,1,29,3800', '3801', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('3802', '3800', '布尔津县', '0,1,29,3800', '3802', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3803', '3800', '富蕴县', '0,1,29,3800', '3803', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('3804', '3800', '福海县', '0,1,29,3800', '3804', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('3805', '3800', '哈巴河', '0,1,29,3800', '3805', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3806', '3800', '青河县', '0,1,29,3800', '3806', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('3807', '3800', '吉木乃县', '0,1,29,3800', '3807', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3808', '3800', '其他区', '0,1,29,3800', '3808', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3809', '29', '可克达拉', '0,1,29', '3809,3810,3811', '1', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('3810', '3809', '双河市', '0,1,29,3809', '3810', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3811', '3809', '可克达拉', '0,1,29,3809', '3811', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('3813', '367', '其他区', '0,1,30,367', '3813', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3814', '378', '其他区', '0,1,30,378', '3814', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3815', '381', '其他区', '0,1,30,381', '3815', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3816', '371', '其他区', '0,1,30,371', '3816', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3817', '382', '其他区', '0,1,30,382', '3817', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3818', '370', '其他区', '0,1,30,370', '3818', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3819', '369', '其他区', '0,1,30,369', '3819', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3820', '377', '其他区', '0,1,30,377', '3820', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3821', '372', '其他区', '0,1,30,372', '3821', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3822', '376', '其他区', '0,1,30,376', '3822', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3823', '379', '其他区', '0,1,30,379', '3823', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3824', '380', '其他区', '0,1,30,380', '3824', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3825', '373', '其他区', '0,1,30,373', '3825', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3826', '374', '芒市', '0,1,30,374', '3826', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('3827', '374', '其他区', '0,1,30,374', '3827', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3828', '368', '其他区', '0,1,30,368', '3828', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3829', '375', '其他区', '0,1,30,375', '3829', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3830', '383', '其他区', '0,1,31,383', '3830', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3831', '388', '其他区', '0,1,31,388', '3831', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3832', '391', '其他区', '0,1,31,391', '3832', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3833', '385', '其他区', '0,1,31,385', '3833', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3834', '384', '其他区', '0,1,31,384', '3834', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3835', '389', '河桥区', '0,1,31,389', '3835', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3836', '389', '其他区', '0,1,31,389', '3836', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3837', '386', '义乌市', '0,1,31,386', '3837', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3838', '386', '其他区', '0,1,31,386', '3838', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3839', '393', '柯城区', '0,1,31,393', '3839', '0', '', '0', '1', '', '', '', '', '0', 'K', '0', '');
INSERT INTO `zz_linkage` VALUES ('3840', '393', '衢江区', '0,1,31,393', '3840', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('3841', '392', '其他区', '0,1,31,392', '3841', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3842', '390', '其他区', '0,1,31,390', '3842', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3843', '387', '其他区', '0,1,31,387', '3843', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3844', '32', '其他区', '0,1,32', '3844', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3845', '33', '香港岛', '0,1,33', '3845', '1', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3850', '34', '澳门半岛', '0,1,34', '3850', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('3851', '34', '离岛', '0,1,34', '3851', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3852', '3384', '中正区', '0,1,35,3384', '3852', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('3853', '3384', '大同区', '0,1,35,3384', '3853', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3854', '3384', '中山区', '0,1,35,3384', '3854', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('3855', '3384', '松山区', '0,1,35,3384', '3855', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3856', '3384', '大安区', '0,1,35,3384', '3856', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3857', '3384', '万华区', '0,1,35,3384', '3857', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3858', '3384', '信义区', '0,1,35,3384', '3858', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3859', '3384', '士林区', '0,1,35,3384', '3859', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3860', '3384', '北投区', '0,1,35,3384', '3860', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3861', '3384', '内湖区', '0,1,35,3384', '3861', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('3862', '3384', '南港区', '0,1,35,3384', '3862', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('3863', '3384', '文山区', '0,1,35,3384', '3863', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3864', '3384', '其他区', '0,1,35,3384', '3864', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3865', '3385', '新兴区', '0,1,35,3385', '3865', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3866', '3385', '前金区', '0,1,35,3385', '3866', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('3867', '3385', '芩雅区', '0,1,35,3385', '3867', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('3868', '3385', '盐埕区', '0,1,35,3385', '3868', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3869', '3385', '鼓山区', '0,1,35,3385', '3869', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('3870', '3385', '旗津区', '0,1,35,3385', '3870', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('3871', '3385', '前镇区', '0,1,35,3385', '3871', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('3872', '3385', '三民区', '0,1,35,3385', '3872', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3873', '3385', '左营区', '0,1,35,3385', '3873', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('3874', '3385', '楠梓区', '0,1,35,3385', '3874', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('3875', '3385', '小港区', '0,1,35,3385', '3875', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3876', '3385', '任武区', '0,1,35,3385', '3876', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('3877', '3385', '大社区', '0,1,35,3385', '3877', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3878', '3385', '冈山区', '0,1,35,3385', '3878', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('3879', '3385', '路竹区', '0,1,35,3385', '3879', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3880', '3385', '阿莲区', '0,1,35,3385', '3880', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('3881', '3385', '燕巢区', '0,1,35,3385', '3881', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3882', '3385', '桥头区', '0,1,35,3385', '3882', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('3883', '3385', '梓官区', '0,1,35,3385', '3883', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('3884', '3385', '弥陀区', '0,1,35,3385', '3884', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('3885', '3385', '永安区', '0,1,35,3385', '3885', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3886', '3385', '湖内区', '0,1,35,3385', '3886', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3887', '3385', '凤山区', '0,1,35,3385', '3887', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('3888', '3385', '林园区', '0,1,35,3385', '3888', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3889', '3385', '鸟松区', '0,1,35,3385', '3889', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('3890', '3385', '大树区', '0,1,35,3385', '3890', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3891', '3385', '旗山区', '0,1,35,3385', '3891', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('3892', '3385', '美浓区', '0,1,35,3385', '3892', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('3893', '3385', '六龟区', '0,1,35,3385', '3893', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3894', '3385', '内门区', '0,1,35,3385', '3894', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('3895', '3385', '杉林区', '0,1,35,3385', '3895', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3896', '3385', '甲仙区', '0,1,35,3385', '3896', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3897', '3385', '桃源区', '0,1,35,3385', '3897', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3898', '3385', '那玛夏区', '0,1,35,3385', '3898', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('3899', '3385', '茂林区', '0,1,35,3385', '3899', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('3900', '3385', '茄萣区', '0,1,35,3385', '3900', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('3901', '3385', '其他区', '0,1,35,3385', '3901', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3902', '3388', '中西区', '0,1,35,3388', '3902', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('3903', '3388', '东区', '0,1,35,3388', '3903', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3904', '3388', '南区', '0,1,35,3388', '3904', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('3905', '3388', '北区', '0,1,35,3388', '3905', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3906', '3388', '安平区', '0,1,35,3388', '3906', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('3907', '3388', '永康区', '0,1,35,3388', '3907', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3908', '3388', '归仁区', '0,1,35,3388', '3908', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('3909', '3388', '新化区', '0,1,35,3388', '3909', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3910', '3388', '左镇区', '0,1,35,3388', '3910', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('3911', '3388', '玉井区', '0,1,35,3388', '3911', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3912', '3388', '楠西区', '0,1,35,3388', '3912', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('3913', '3388', '南化区', '0,1,35,3388', '3913', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('3914', '3388', '仁德区', '0,1,35,3388', '3914', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('3915', '3388', '关庙区', '0,1,35,3388', '3915', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('3916', '3388', '龙崎区', '0,1,35,3388', '3916', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3917', '3388', '官田区', '0,1,35,3388', '3917', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('3918', '3388', '麻豆区', '0,1,35,3388', '3918', '0', '', '0', '1', '', '', '', '', '0', 'M', '0', '');
INSERT INTO `zz_linkage` VALUES ('3919', '3388', '佳里区', '0,1,35,3388', '3919', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3920', '3388', '西港区', '0,1,35,3388', '3920', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3921', '3388', '七股区', '0,1,35,3388', '3921', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('3922', '3388', '将军区', '0,1,35,3388', '3922', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3923', '3388', '学甲区', '0,1,35,3388', '3923', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3924', '3388', '北门区', '0,1,35,3388', '3924', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3925', '3388', '新营区', '0,1,35,3388', '3925', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3926', '3388', '后壁区', '0,1,35,3388', '3926', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3927', '3388', '白河区', '0,1,35,3388', '3927', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3928', '3388', '东山区', '0,1,35,3388', '3928', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3929', '3388', '六甲区', '0,1,35,3388', '3929', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3930', '3388', '下营区', '0,1,35,3388', '3930', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3931', '3388', '柳营区', '0,1,35,3388', '3931', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3932', '3388', '盐水区', '0,1,35,3388', '3932', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('3933', '3388', '善化区', '0,1,35,3388', '3933', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3934', '3388', '大内区', '0,1,35,3388', '3934', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3935', '3388', '山上区', '0,1,35,3388', '3935', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3936', '3388', '新市区', '0,1,35,3388', '3936', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3937', '3388', '安定区', '0,1,35,3388', '3937', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('3938', '3388', '其他区', '0,1,35,3388', '3938', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3939', '3387', '中区', '0,1,35,3387', '3939', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('3940', '3387', '东区', '0,1,35,3387', '3940', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3941', '3387', '南区', '0,1,35,3387', '3941', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('3942', '3387', '西区', '0,1,35,3387', '3942', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3943', '3387', '北区', '0,1,35,3387', '3943', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3944', '3387', '北屯区', '0,1,35,3387', '3944', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3945', '3387', '南屯区', '0,1,35,3387', '3945', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('3946', '3387', '太平区', '0,1,35,3387', '3946', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3947', '3387', '大里区', '0,1,35,3387', '3947', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3948', '3387', '雾峰区', '0,1,35,3387', '3948', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3949', '3387', '乌日区', '0,1,35,3387', '3949', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3950', '3387', '丰原区', '0,1,35,3387', '3950', '0', '', '0', '1', '', '', '', '', '0', 'F', '0', '');
INSERT INTO `zz_linkage` VALUES ('3951', '3387', '后里区', '0,1,35,3387', '3951', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3952', '3387', '石冈区', '0,1,35,3387', '3952', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3953', '3387', '东势区', '0,1,35,3387', '3953', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3954', '3387', '和平区', '0,1,35,3387', '3954', '0', '', '0', '1', '', '', '', '', '0', 'H', '0', '');
INSERT INTO `zz_linkage` VALUES ('3955', '3387', '新社区', '0,1,35,3387', '3955', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3956', '3387', '潭子区', '0,1,35,3387', '3956', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('3957', '3387', '大雅区', '0,1,35,3387', '3957', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3958', '3387', '神冈区', '0,1,35,3387', '3958', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3959', '3387', '大胜区', '0,1,35,3387', '3959', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3960', '3387', '大肚区', '0,1,35,3387', '3960', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3961', '3387', '沙鹿区', '0,1,35,3387', '3961', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3962', '3387', '龙井区', '0,1,35,3387', '3962', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3963', '3387', '梧栖区', '0,1,35,3387', '3963', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3964', '3387', '清水区', '0,1,35,3387', '3964', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('3965', '3387', '大甲区', '0,1,35,3387', '3965', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3966', '3387', '外浦区', '0,1,35,3387', '3966', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3967', '3387', '大安区', '0,1,35,3387', '3967', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3968', '3387', '其他区', '0,1,35,3387', '3968', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3969', '35', '金门县', '0,1,35', '3969,3970,3971,3972,3973,3974,3975', '1', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3970', '3969', '金沙镇', '0,1,35,3969', '3970', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3971', '3969', '金湖镇', '0,1,35,3969', '3971', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3972', '3969', '金宁乡', '0,1,35,3969', '3972', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3973', '3969', '金城镇', '0,1,35,3969', '3973', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3974', '3969', '烈屿乡', '0,1,35,3969', '3974', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('3975', '3969', '乌坵乡', '0,1,35,3969', '3975', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3976', '3386', '仁爱区', '0,1,35,3386', '3976', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('3977', '3386', '信义区', '0,1,35,3386', '3977', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3978', '3386', '中正区', '0,1,35,3386', '3978', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('3979', '3386', '中山区', '0,1,35,3386', '3979', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('3980', '3386', '安乐区', '0,1,35,3386', '3980', '0', '', '0', '1', '', '', '', '', '0', 'A', '0', '');
INSERT INTO `zz_linkage` VALUES ('3981', '3386', '暖暖区', '0,1,35,3386', '3981', '0', '', '0', '1', '', '', '', '', '0', 'N', '0', '');
INSERT INTO `zz_linkage` VALUES ('3982', '3386', '七堵区', '0,1,35,3386', '3982', '0', '', '0', '1', '', '', '', '', '0', 'Q', '0', '');
INSERT INTO `zz_linkage` VALUES ('3983', '3386', '其他区', '0,1,35,3386', '3983', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3984', '3390', '东区', '0,1,35,3390', '3984', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('3985', '3390', '西区', '0,1,35,3390', '3985', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3986', '3390', '其他区', '0,1,35,3390', '3986', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('3987', '35', '新北市', '0,1,35', '3987,3988,3989,3990,3991,3992,3993,3994,3995,3996,3997,3998,3999,4000,4001,4002,4003,4004,4005,4006,4007,4008,4010,4011,4012,4013,4014,4015,4016,4017,4018', '1', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3988', '3987', '万里区', '0,1,35,3987', '3988', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('3989', '3987', '金山区', '0,1,35,3987', '3989', '0', '', '0', '1', '', '', '', '', '0', 'J', '0', '');
INSERT INTO `zz_linkage` VALUES ('3990', '3987', '板桥区', '0,1,35,3987', '3990', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('3991', '3987', '汐止区', '0,1,35,3987', '3991', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3992', '3987', '深坑区', '0,1,35,3987', '3992', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3993', '3987', '石碇区', '0,1,35,3987', '3993', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3994', '3987', '瑞芳区', '0,1,35,3987', '3994', '0', '', '0', '1', '', '', '', '', '0', 'R', '0', '');
INSERT INTO `zz_linkage` VALUES ('3995', '3987', '平溪区', '0,1,35,3987', '3995', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('3996', '3987', '双溪区', '0,1,35,3987', '3996', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('3997', '3987', '贡寮区', '0,1,35,3987', '3997', '0', '', '0', '1', '', '', '', '', '0', 'G', '0', '');
INSERT INTO `zz_linkage` VALUES ('3998', '3987', '新店区', '0,1,35,3987', '3998', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('3999', '3987', '坪林区', '0,1,35,3987', '3999', '0', '', '0', '1', '', '', '', '', '0', 'P', '0', '');
INSERT INTO `zz_linkage` VALUES ('4000', '3987', '乌来区', '0,1,35,3987', '4000', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('4001', '3987', '永和区', '0,1,35,3987', '4001', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('4002', '3987', '中和区', '0,1,35,3987', '4002', '0', '', '0', '1', '', '', '', '', '0', 'Z', '0', '');
INSERT INTO `zz_linkage` VALUES ('4003', '3987', '土城区', '0,1,35,3987', '4003', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('4004', '3987', '三峡区', '0,1,35,3987', '4004', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('4005', '3987', '树林区', '0,1,35,3987', '4005', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('4006', '3987', '莺歌区', '0,1,35,3987', '4006', '0', '', '0', '1', '', '', '', '', '0', 'Y', '0', '');
INSERT INTO `zz_linkage` VALUES ('4007', '3987', '三重区', '0,1,35,3987', '4007', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('4008', '3987', '新庄区', '0,1,35,3987', '4008', '0', '', '0', '1', '', '', '', '', '0', 'X', '0', '');
INSERT INTO `zz_linkage` VALUES ('4010', '3987', '泰山区', '0,1,35,3987', '4010', '0', '', '0', '1', '', '', '', '', '0', 'T', '0', '');
INSERT INTO `zz_linkage` VALUES ('4011', '3987', '林口区', '0,1,35,3987', '4011', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('4012', '3987', '芦洲区', '0,1,35,3987', '4012', '0', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('4013', '3987', '五股区', '0,1,35,3987', '4013', '0', '', '0', '1', '', '', '', '', '0', 'W', '0', '');
INSERT INTO `zz_linkage` VALUES ('4014', '3987', '八里区', '0,1,35,3987', '4014', '0', '', '0', '1', '', '', '', '', '0', 'B', '0', '');
INSERT INTO `zz_linkage` VALUES ('4015', '3987', '淡水区', '0,1,35,3987', '4015', '0', '', '0', '1', '', '', '', '', '0', 'D', '0', '');
INSERT INTO `zz_linkage` VALUES ('4016', '3987', '三芝区', '0,1,35,3987', '4016', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('4017', '3987', '石门区', '0,1,35,3987', '4017', '0', '', '0', '1', '', '', '', '', '0', 'S', '0', '');
INSERT INTO `zz_linkage` VALUES ('4018', '3987', '其他区', '0,1,35,3987', '4018', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4019', '35', '连江县', '0,1,35', '4019,4616,4617,4618,4619', '1', '', '0', '1', '', '', '', '', '0', 'L', '0', '');
INSERT INTO `zz_linkage` VALUES ('4020', '95', '其他', '0,1,6,95', '4020', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4021', '66', '文珠镇', '0,1,5,66', '4021', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4022', '66', '雄关区', '0,1,5,66', '4022', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4023', '66', '镜铁区', '0,1,5,66', '4023', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4024', '66', '长城区', '0,1,5,66', '4024', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4025', '66', '峪泉镇', '0,1,5,66', '4025', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4026', '79', '松山湖管委会', '0,1,6,79', '4026', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4027', '79', '虎门港管委会', '0,1,6,79', '4027', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4028', '79', '东莞生态园', '0,1,6,79', '4028', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4029', '95', '南区街道', '0,1,6,95', '4029', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4030', '95', '小榄镇', '0,1,6,95', '4030', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4031', '95', '黄圃镇', '0,1,6,95', '4031', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4032', '95', '民众镇', '0,1,6,95', '4032', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4033', '95', '东凤镇', '0,1,6,95', '4033', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4034', '95', '东升镇古镇镇', '0,1,6,95', '4034', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4035', '95', '沙溪镇', '0,1,6,95', '4035', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4036', '95', '坦洲镇', '0,1,6,95', '4036', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4037', '95', '港口镇', '0,1,6,95', '4037', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4038', '95', '三角镇', '0,1,6,95', '4038', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4039', '95', '横栏镇', '0,1,6,95', '4039', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4040', '95', '南头镇', '0,1,6,95', '4040', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4041', '95', '阜沙镇', '0,1,6,95', '4041', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4042', '95', '南朗镇', '0,1,6,95', '4042', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4043', '95', '三乡镇', '0,1,6,95', '4043', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4044', '95', '板芙镇', '0,1,6,95', '4044', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4045', '95', '大涌镇', '0,1,6,95', '4045', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4046', '95', '神湾镇', '0,1,6,95', '4046', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4047', '121', '海棠湾镇', '0,1,9,121', '4047', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4048', '121', '吉阳镇', '0,1,9,121', '4048', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4049', '121', '凤凰镇', '0,1,9,121', '4049', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4050', '121', '崖城镇', '0,1,9,121', '4050', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4051', '121', '天涯镇', '0,1,9,121', '4051', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4052', '121', '育才镇', '0,1,9,121', '4052', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4053', '121', '国营南田农场', '0,1,9,121', '4053', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4054', '121', '国营南新农场', '0,1,9,121', '4054', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4055', '121', '国营立才农场', '0,1,9,121', '4055', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4056', '121', '国营南滨农场', '0,1,9,121', '4056', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4057', '121', '河西区街道', '0,1,9,121', '4057', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4058', '121', '河东区街道', '0,1,9,121', '4058', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4059', '136', '通什镇', '0,1,9,136', '4059', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4060', '136', '南圣镇', '0,1,9,136', '4060', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4061', '136', '毛阳镇', '0,1,9,136', '4061', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4062', '136', '番阳镇', '0,1,9,136', '4062', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4063', '136', '畅好乡', '0,1,9,136', '4063', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4064', '136', '毛道乡', '0,1,9,136', '4064', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4065', '136', '水满乡', '0,1,9,136', '4065', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4066', '136', '国营畅好农场', '0,1,9,136', '4066', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4067', '131', '嘉积镇', '0,1,9,131', '4067', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4068', '131', '万泉镇', '0,1,9,131', '4068', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4069', '131', '石壁镇', '0,1,9,131', '4069', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4070', '131', '中原镇', '0,1,9,131', '4070', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4071', '131', '博鳌镇', '0,1,9,131', '4071', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4072', '131', '阳江镇', '0,1,9,131', '4072', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4073', '131', '龙江镇', '0,1,9,131', '4073', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4074', '131', '潭门镇', '0,1,9,131', '4074', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4075', '131', '塔洋镇', '0,1,9,131', '4075', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4076', '131', '长坡镇', '0,1,9,131', '4076', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4077', '131', '大路镇', '0,1,9,131', '4077', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4078', '131', '会山镇', '0,1,9,131', '4078', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4079', '131', '国营东太农场', '0,1,9,131', '4079', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4080', '131', '国营东红农场', '0,1,9,131', '4080', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4081', '131', '国营东升农场', '0,1,9,131', '4081', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4082', '131', '彬村山华侨农场', '0,1,9,131', '4082', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4083', '137', '国营西培农场', '0,1,9,137', '4083', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4084', '137', '国营西联农场', '0,1,9,137', '4084', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4085', '137', '国营蓝洋农场', '0,1,9,137', '4085', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4086', '137', '国营八一农场', '0,1,9,137', '4086', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4087', '137', '华南热作学院', '0,1,9,137', '4087', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4088', '135', '文城镇', '0,1,9,135', '4088', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4089', '135', '重兴镇', '0,1,9,135', '4089', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4090', '135', '蓬莱镇', '0,1,9,135', '4090', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4091', '135', '会文镇', '0,1,9,135', '4091', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4092', '135', '东路镇', '0,1,9,135', '4092', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4093', '135', '潭牛镇', '0,1,9,135', '4093', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4094', '135', '东阁镇', '0,1,9,135', '4094', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4095', '135', '文教镇', '0,1,9,135', '4095', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4096', '135', '东郊镇', '0,1,9,135', '4096', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4097', '135', '龙楼镇', '0,1,9,135', '4097', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4098', '135', '昌洒镇', '0,1,9,135', '4098', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4099', '135', '翁田镇', '0,1,9,135', '4099', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4100', '135', '抱罗镇', '0,1,9,135', '4100', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4101', '135', '冯坡镇', '0,1,9,135', '4101', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4102', '135', '锦山镇', '0,1,9,135', '4102', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4103', '135', '铺前镇', '0,1,9,135', '4103', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4104', '135', '公坡镇', '0,1,9,135', '4104', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4105', '135', '国营东路农场', '0,1,9,135', '4105', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4106', '135', '国营南阳农场', '0,1,9,135', '4106', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4107', '135', '国营罗豆农场', '0,1,9,135', '4107', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4108', '134', '万城镇', '0,1,9,134', '4108', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4109', '134', '龙滚镇', '0,1,9,134', '4109', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4110', '134', '和乐镇', '0,1,9,134', '4110', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4111', '134', '后安镇', '0,1,9,134', '4111', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4112', '134', '大茂镇', '0,1,9,134', '4112', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4113', '134', '东澳镇', '0,1,9,134', '4113', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4114', '134', '礼纪镇', '0,1,9,134', '4114', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4115', '134', '长丰镇', '0,1,9,134', '4115', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4116', '134', '山根镇', '0,1,9,134', '4116', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4117', '134', '北大镇', '0,1,9,134', '4117', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4118', '134', '南桥镇', '0,1,9,134', '4118', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4119', '134', '三更罗镇', '0,1,9,134', '4119', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4120', '134', '国营东兴农场', '0,1,9,134', '4120', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4121', '134', '国营东和农场', '0,1,9,134', '4121', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4122', '134', '兴隆华侨农场', '0,1,9,134', '4122', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4123', '134', '地方国营六连农场', '0,1,9,134', '4123', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4124', '127', '八所镇', '0,1,9,127', '4124', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4125', '127', '东河镇', '0,1,9,127', '4125', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4126', '127', '大田镇', '0,1,9,127', '4126', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4127', '127', '感城镇', '0,1,9,127', '4127', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4128', '127', '板桥镇', '0,1,9,127', '4128', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4129', '127', '三家镇', '0,1,9,127', '4129', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4130', '127', '四更镇', '0,1,9,127', '4130', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4131', '127', '新龙镇', '0,1,9,127', '4131', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4132', '127', '天安乡', '0,1,9,127', '4132', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4133', '127', '江边乡', '0,1,9,127', '4133', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4134', '127', '国营广坝农场', '0,1,9,127', '4134', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4135', '127', '东方华侨农场', '0,1,9,127', '4135', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4136', '126', '定城镇', '0,1,9,126', '4136', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4137', '126', '新竹镇', '0,1,9,126', '4137', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4138', '126', '龙湖镇', '0,1,9,126', '4138', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4139', '126', '黄竹镇', '0,1,9,126', '4139', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4140', '126', '雷鸣镇', '0,1,9,126', '4140', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4141', '126', '龙门镇', '0,1,9,126', '4141', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4142', '126', '龙河镇', '0,1,9,126', '4142', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4143', '126', '岭口镇', '0,1,9,126', '4143', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4144', '126', '翰林镇', '0,1,9,126', '4144', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4145', '126', '富文镇', '0,1,9,126', '4145', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4146', '126', '国营中瑞农场', '0,1,9,126', '4146', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4147', '126', '国营南海农场', '0,1,9,126', '4147', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4148', '126', '国营金鸡岭农场', '0,1,9,126', '4148', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4149', '133', '屯城镇', '0,1,9,133', '4149', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4150', '133', '新兴镇', '0,1,9,133', '4150', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4151', '133', '枫木镇', '0,1,9,133', '4151', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4152', '133', '乌坡镇', '0,1,9,133', '4152', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4153', '133', '南吕镇', '0,1,9,133', '4153', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4154', '133', '南坤镇', '0,1,9,133', '4154', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4155', '133', '坡心镇', '0,1,9,133', '4155', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4156', '133', '西昌镇', '0,1,9,133', '4156', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4157', '133', '国营中建农场', '0,1,9,133', '4157', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4158', '133', '国营中坤农场', '0,1,9,133', '4158', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4159', '125', '金江镇', '0,1,9,125', '4159', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4160', '125', '老城镇', '0,1,9,125', '4160', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4161', '125', '瑞溪镇', '0,1,9,125', '4161', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4162', '125', '永发镇', '0,1,9,125', '4162', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4163', '125', '加乐镇', '0,1,9,125', '4163', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4164', '125', '文儒镇', '0,1,9,125', '4164', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4165', '125', '中兴镇', '0,1,9,125', '4165', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4166', '125', '仁兴镇', '0,1,9,125', '4166', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4167', '125', '福山镇', '0,1,9,125', '4167', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4168', '125', '桥头镇', '0,1,9,125', '4168', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4169', '125', '大丰镇', '0,1,9,125', '4169', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4170', '125', '国营红光农场', '0,1,9,125', '4170', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4171', '125', '国营红岗农场', '0,1,9,125', '4171', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4172', '125', '国营西达农场', '0,1,9,125', '4172', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4173', '125', '国营昆仑农场', '0,1,9,125', '4173', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4174', '125', '国营和岭农场', '0,1,9,125', '4174', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4175', '125', '国营金安农场', '0,1,9,125', '4175', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4176', '129', '临城镇', '0,1,9,129', '4176', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4177', '129', '波莲镇', '0,1,9,129', '4177', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4178', '129', '东英镇', '0,1,9,129', '4178', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4179', '129', '博厚镇', '0,1,9,129', '4179', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4180', '129', '皇桐镇', '0,1,9,129', '4180', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4181', '129', '多文镇', '0,1,9,129', '4181', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4182', '129', '和舍镇', '0,1,9,129', '4182', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4183', '129', '南宝镇', '0,1,9,129', '4183', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4184', '129', '新盈镇', '0,1,9,129', '4184', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4185', '129', '调楼镇', '0,1,9,129', '4185', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4186', '129', '国营红华农场', '0,1,9,129', '4186', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4187', '129', '国营加来农场', '0,1,9,129', '4187', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4188', '122', '牙叉镇', '0,1,9,122', '4188', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4189', '122', '七坊镇', '0,1,9,122', '4189', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4190', '122', '邦溪镇', '0,1,9,122', '4190', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4191', '122', '打安镇', '0,1,9,122', '4191', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4192', '122', '细水乡', '0,1,9,122', '4192', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4193', '122', '元门乡', '0,1,9,122', '4193', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4194', '122', '阜龙乡', '0,1,9,122', '4194', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4195', '122', '青松乡', '0,1,9,122', '4195', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4196', '122', '金波乡', '0,1,9,122', '4196', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4197', '122', '荣邦乡', '0,1,9,122', '4197', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4198', '122', '国营白沙农场', '0,1,9,122', '4198', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4199', '122', '国营龙江农场', '0,1,9,122', '4199', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4200', '122', '国营邦溪农场', '0,1,9,122', '4200', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4201', '124', '石碌镇', '0,1,9,124', '4201', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4202', '124', '叉河镇', '0,1,9,124', '4202', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4203', '124', '十月田镇', '0,1,9,124', '4203', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4204', '124', '乌烈镇', '0,1,9,124', '4204', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4205', '124', '昌化镇', '0,1,9,124', '4205', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4206', '124', '海尾镇', '0,1,9,124', '4206', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4207', '124', '七叉镇', '0,1,9,124', '4207', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4208', '124', '王下乡', '0,1,9,124', '4208', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4209', '124', '国营红林农场', '0,1,9,124', '4209', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4210', '124', '海南矿业联合有限公司', '0,1,9,124', '4210', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4211', '128', '抱由镇', '0,1,9,128', '4211', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4212', '128', '万冲镇', '0,1,9,128', '4212', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4213', '128', '大安镇', '0,1,9,128', '4213', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4214', '128', '志仲镇', '0,1,9,128', '4214', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4215', '128', '千家镇', '0,1,9,128', '4215', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4216', '128', '九所镇', '0,1,9,128', '4216', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4217', '128', '佛罗镇', '0,1,9,128', '4217', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4218', '128', '尖峰镇', '0,1,9,128', '4218', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4219', '128', '莺歌海镇', '0,1,9,128', '4219', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4220', '128', '国营山荣农场', '0,1,9,128', '4220', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4221', '128', '国营乐光农场', '0,1,9,128', '4221', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4222', '128', '国营尖峰岭林业公司', '0,1,9,128', '4222', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4223', '128', '国营保国农场', '0,1,9,128', '4223', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4224', '128', '国营莺歌海盐场', '0,1,9,128', '4224', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4225', '130', '椰林镇', '0,1,9,130', '4225', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4226', '130', '光坡镇', '0,1,9,130', '4226', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4227', '130', '三才镇', '0,1,9,130', '4227', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4228', '130', '英州镇', '0,1,9,130', '4228', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4229', '130', '隆广镇', '0,1,9,130', '4229', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4230', '130', '文罗镇', '0,1,9,130', '4230', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4231', '130', '本号镇', '0,1,9,130', '4231', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4232', '130', '新村镇', '0,1,9,130', '4232', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4233', '130', '黎安镇', '0,1,9,130', '4233', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4234', '130', '提蒙乡', '0,1,9,130', '4234', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4235', '130', '群英乡', '0,1,9,130', '4235', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4236', '130', '国营岭门农场', '0,1,9,130', '4236', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4237', '130', '国营南平农场', '0,1,9,130', '4237', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4238', '130', '国营吊罗山林业公司', '0,1,9,130', '4238', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4239', '123', '保城镇', '0,1,9,123', '4239', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4240', '123', '什玲镇', '0,1,9,123', '4240', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4241', '123', '加茂镇', '0,1,9,123', '4241', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4242', '123', '响水镇', '0,1,9,123', '4242', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4243', '123', '新政镇', '0,1,9,123', '4243', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4244', '123', '三道镇', '0,1,9,123', '4244', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4245', '123', '六弓乡', '0,1,9,123', '4245', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4246', '123', '南林乡', '0,1,9,123', '4246', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4247', '123', '毛感乡', '0,1,9,123', '4247', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4248', '123', '国营新星农场', '0,1,9,123', '4248', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4249', '123', '海南保亭热带作物研究所', '0,1,9,123', '4249', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4250', '123', '国营三道农场', '0,1,9,123', '4250', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4251', '132', '营根镇', '0,1,9,132', '4251', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4252', '132', '湾岭镇', '0,1,9,132', '4252', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4253', '132', '黎母山镇', '0,1,9,132', '4253', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4254', '132', '和平镇', '0,1,9,132', '4254', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4255', '132', '长征镇', '0,1,9,132', '4255', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4256', '132', '红毛镇', '0,1,9,132', '4256', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4257', '132', '中平镇', '0,1,9,132', '4257', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4258', '132', '吊罗山乡', '0,1,9,132', '4258', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4259', '132', '上安乡', '0,1,9,132', '4259', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4260', '132', '什运乡', '0,1,9,132', '4260', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4261', '132', '国营阳江农场', '0,1,9,132', '4261', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4262', '132', '国营乌石农场', '0,1,9,132', '4262', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4263', '132', '国营加钗农场', '0,1,9,132', '4263', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4264', '132', '国营长征农场', '0,1,9,132', '4264', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4265', '132', '国营黎母山林业公司', '0,1,9,132', '4265', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4266', '1307', '五龙口镇', '0,1,11,154,1307', '4266', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4267', '1307', '轵城镇', '0,1,11,154,1307', '4267', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4268', '1307', '承留镇', '0,1,11,154,1307', '4268', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4269', '1307', '邵原镇', '0,1,11,154,1307', '4269', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4270', '1307', '坡头镇', '0,1,11,154,1307', '4270', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4271', '1307', '梨林镇', '0,1,11,154,1307', '4271', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4272', '1307', '大峪镇', '0,1,11,154,1307', '4272', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4273', '1307', '思礼镇', '0,1,11,154,1307', '4273', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4274', '1307', '王屋镇', '0,1,11,154,1307', '4274', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4275', '1307', '下冶镇', '0,1,11,154,1307', '4275', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4277', '181', '沙嘴街道', '0,1,13,181', '4277', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4278', '181', '干河街道', '0,1,13,181', '4278', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4279', '181', '龙华山办事处', '0,1,13,181', '4279', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4280', '181', '郑场镇', '0,1,13,181', '4280', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4281', '181', '毛嘴镇', '0,1,13,181', '4281', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4282', '181', '豆河镇', '0,1,13,181', '4282', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4283', '181', '三伏潭镇', '0,1,13,181', '4283', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4284', '181', '胡场镇', '0,1,13,181', '4284', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4285', '181', '长倘口镇', '0,1,13,181', '4285', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4286', '181', '西流河镇', '0,1,13,181', '4286', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4287', '181', '沙湖镇', '0,1,13,181', '4287', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4288', '181', '杨林尾镇', '0,1,13,181', '4288', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4289', '181', '彭场镇', '0,1,13,181', '4289', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4290', '181', '张沟镇', '0,1,13,181', '4290', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4291', '181', '郭河镇', '0,1,13,181', '4291', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4292', '181', '沔城回族镇', '0,1,13,181', '4292', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4293', '181', '通海口镇', '0,1,13,181', '4293', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4294', '181', '陈场镇', '0,1,13,181', '4294', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4295', '181', '工业园区', '0,1,13,181', '4295', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4296', '181', '九合垸原种场', '0,1,13,181', '4296', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4297', '181', '沙湖原种场', '0,1,13,181', '4297', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4298', '181', '五湖渔场', '0,1,13,181', '4298', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4299', '181', '赵西垸林场', '0,1,13,181', '4299', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4300', '181', '畜禽良种场', '0,1,13,181', '4300', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4301', '181', '排湖风景区', '0,1,13,181', '4301', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4302', '187', '园林办事处', '0,1,13,187', '4302', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4303', '187', '杨市办事处', '0,1,13,187', '4303', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4304', '187', '周矶办事处', '0,1,13,187', '4304', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4305', '187', '广华办事处', '0,1,13,187', '4305', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4306', '187', '泰丰办事处', '0,1,13,187', '4306', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4307', '187', '高场办事处', '0,1,13,187', '4307', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4308', '187', '竹根滩镇', '0,1,13,187', '4308', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4309', '187', '渔洋镇', '0,1,13,187', '4309', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4310', '187', '王场镇', '0,1,13,187', '4310', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4311', '187', '高石碑镇', '0,1,13,187', '4311', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4312', '187', '熊口镇', '0,1,13,187', '4312', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4313', '187', '老新镇', '0,1,13,187', '4313', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4314', '187', '浩口镇', '0,1,13,187', '4314', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4315', '187', '积玉口镇', '0,1,13,187', '4315', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4316', '187', '张金镇', '0,1,13,187', '4316', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4317', '187', '龙湾镇', '0,1,13,187', '4317', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4318', '187', '江汉石油管理局', '0,1,13,187', '4318', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4319', '187', '潜江经济开发区', '0,1,13,187', '4319', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4320', '187', '周矶管理区', '0,1,13,187', '4320', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4321', '187', '后湖管理区', '0,1,13,187', '4321', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4322', '187', '熊口管理区', '0,1,13,187', '4322', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4323', '187', '总口管理区', '0,1,13,187', '4323', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4324', '187', '白鹭湖管理区', '0,1,13,187', '4324', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4325', '187', '运粮湖管理区', '0,1,13,187', '4325', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4326', '187', '浩口原种场', '0,1,13,187', '4326', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4327', '191', '竟陵街道', '0,1,13,191', '4327', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4328', '191', '侨乡街道开发区', '0,1,13,191', '4328', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4329', '191', '杨林街道', '0,1,13,191', '4329', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4330', '191', '多宝镇', '0,1,13,191', '4330', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4331', '191', '拖市镇', '0,1,13,191', '4331', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4332', '191', '张港镇', '0,1,13,191', '4332', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4333', '191', '蒋场镇', '0,1,13,191', '4333', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4334', '191', '汪场镇', '0,1,13,191', '4334', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4335', '191', '渔薪镇', '0,1,13,191', '4335', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4336', '191', '黄潭镇', '0,1,13,191', '4336', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4337', '191', '岳口镇', '0,1,13,191', '4337', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4338', '191', '横林镇', '0,1,13,191', '4338', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4339', '191', '彭市镇', '0,1,13,191', '4339', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4340', '191', '麻洋镇', '0,1,13,191', '4340', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4341', '191', '多祥镇', '0,1,13,191', '4341', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4342', '191', '干驿镇', '0,1,13,191', '4342', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4343', '191', '马湾镇', '0,1,13,191', '4343', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4344', '191', '卢市镇', '0,1,13,191', '4344', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4345', '191', '小板镇', '0,1,13,191', '4345', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4346', '191', '九真镇', '0,1,13,191', '4346', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4347', '191', '皂市镇', '0,1,13,191', '4347', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4348', '191', '胡市镇', '0,1,13,191', '4348', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4349', '191', '石河镇', '0,1,13,191', '4349', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4350', '191', '佛子山镇', '0,1,13,191', '4350', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4351', '191', '净潭乡', '0,1,13,191', '4351', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4352', '191', '蒋湖农场', '0,1,13,191', '4352', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4353', '191', '白茅湖农场', '0,1,13,191', '4353', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4354', '191', '沉湖管委会', '0,1,13,191', '4354', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4355', '188', '松柏镇', '0,1,13,188', '4355', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4356', '188', '阳日镇', '0,1,13,188', '4356', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4357', '188', '木鱼镇', '0,1,13,188', '4357', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4358', '188', '红坪镇', '0,1,13,188', '4358', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4359', '188', '新华镇', '0,1,13,188', '4359', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4360', '188', '九湖镇', '0,1,13,188', '4360', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4361', '188', '宋洛乡', '0,1,13,188', '4361', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4362', '188', '下谷坪土家族乡', '0,1,13,188', '4362', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4363', '362', '新城街道', '0,1,29,362', '4363', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4364', '362', '向阳街道', '0,1,29,362', '4364', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4365', '362', '红山街道', '0,1,29,362', '4365', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4366', '362', '老街街道', '0,1,29,362', '4366', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4367', '362', '东城街道', '0,1,29,362', '4367', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4368', '362', '北泉镇', '0,1,29,362', '4368', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4369', '362', '石河子乡', '0,1,29,362', '4369', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4370', '362', '兵团一五二团', '0,1,29,362', '4370', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4371', '353', '金银川路街道', '0,1,29,353', '4371', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4372', '353', '幸福路街道', '0,1,29,353', '4372', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4373', '353', '青松路街道', '0,1,29,353', '4373', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4374', '353', '南口街道', '0,1,29,353', '4374', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4375', '353', '托喀依乡', '0,1,29,353', '4375', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4376', '353', '工业园区', '0,1,29,353', '4376', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4377', '353', '兵团七团', '0,1,29,353', '4377', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4378', '353', '兵团八团', '0,1,29,353', '4378', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4379', '353', '兵团十团', '0,1,29,353', '4379', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4380', '353', '兵团十一团', '0,1,29,353', '4380', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4381', '353', '兵团十二团', '0,1,29,353', '4381', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4382', '353', '兵团十三团', '0,1,29,353', '4382', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4383', '353', '兵团十四团', '0,1,29,353', '4383', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4384', '353', '兵团十六团', '0,1,29,353', '4384', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4385', '353', '兵团第一师水利水电工程处', '0,1,29,353', '4385', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4386', '353', '兵团第一师塔里木灌区水利管理处', '0,1,29,353', '4386', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4387', '353', '阿拉尔农场', '0,1,29,353', '4387', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4388', '353', '兵团第一师幸福农场', '0,1,29,353', '4388', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4389', '353', '中心监狱', '0,1,29,353', '4389', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4390', '363', '齐干却勒街道', '0,1,29,363', '4390', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4391', '363', '前海街道', '0,1,29,363', '4391', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4392', '363', '永安坝街道', '0,1,29,363', '4392', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4393', '363', '兵团四十九团', '0,1,29,363', '4393', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4394', '363', '兵团四十四团', '0,1,29,363', '4394', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4395', '363', '兵团五十团', '0,1,29,363', '4395', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4396', '363', '兵团五十一团', '0,1,29,363', '4396', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4397', '363', '兵团五十三团', '0,1,29,363', '4397', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4398', '363', '兵团图木舒克市喀拉拜勒镇', '0,1,29,363', '4398', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4399', '363', '兵团图木舒克市永安坝', '0,1,29,363', '4399', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4400', '365', '军垦路街道', '0,1,29,365', '4400', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4401', '365', '青湖路街道', '0,1,29,365', '4401', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4402', '365', '人民路街道', '0,1,29,365', '4402', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4403', '365', '兵团一零一团', '0,1,29,365', '4403', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4404', '365', '兵团一零二团', '0,1,29,365', '4404', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4405', '365', '兵团一零三团', '0,1,29,365', '4405', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4406', '33', '新界', '0,1,33', '4406', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4407', '3387', '西屯区', '0,1,35,3387', '4407', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4408', '3395', '南投市', '0,1,35,3395', '4408', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4409', '3395', '中寮乡', '0,1,35,3395', '4409', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4410', '3395', '草屯镇', '0,1,35,3395', '4410', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4411', '3395', '国姓乡', '0,1,35,3395', '4411', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4412', '3395', '埔里镇', '0,1,35,3395', '4412', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4413', '3395', '仁爱乡', '0,1,35,3395', '4413', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4414', '3395', '名间乡', '0,1,35,3395', '4414', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4415', '3395', '集集镇', '0,1,35,3395', '4415', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4416', '3395', '水里乡', '0,1,35,3395', '4416', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4417', '3395', '鱼池乡', '0,1,35,3395', '4417', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4418', '3395', '信义乡', '0,1,35,3395', '4418', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4419', '3395', '竹山镇', '0,1,35,3395', '4419', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4420', '3395', '鹿谷乡', '0,1,35,3395', '4420', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4421', '3389', '东区', '0,1,35,3389', '4421', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4422', '3389', '北区', '0,1,35,3389', '4422', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4423', '3389', '香山区', '0,1,35,3389', '4423', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4424', '3389', '其他区', '0,1,35,3389', '4424', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4425', '3391', '宜兰市', '0,1,35,3391', '4425', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4426', '3391', '头城镇', '0,1,35,3391', '4426', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4427', '3391', '礁溪乡', '0,1,35,3391', '4427', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4428', '3391', '壮围乡', '0,1,35,3391', '4428', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4429', '3391', '员山乡', '0,1,35,3391', '4429', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4430', '3391', '罗东镇', '0,1,35,3391', '4430', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4431', '3391', '三星乡', '0,1,35,3391', '4431', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4432', '3391', '大同乡', '0,1,35,3391', '4432', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4433', '3391', '五结乡', '0,1,35,3391', '4433', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4434', '3391', '冬山乡', '0,1,35,3391', '4434', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4435', '3391', '苏澳镇', '0,1,35,3391', '4435', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4436', '3391', '南澳乡', '0,1,35,3391', '4436', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4437', '3391', '钓鱼台', '0,1,35,3391', '4437', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4438', '35', '新竹县', '0,1,35', '4438,4439,4440,4441,4442,4443,4444,4445,4446,4447,4448,4449,4450,4451', '1', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4439', '4438', '竹北市', '0,1,35,4438', '4439', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4440', '4438', '湖口乡', '0,1,35,4438', '4440', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4441', '4438', '新丰乡', '0,1,35,4438', '4441', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4442', '4438', '新埔镇', '0,1,35,4438', '4442', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4443', '4438', '关西镇', '0,1,35,4438', '4443', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4444', '4438', '宝山乡', '0,1,35,4438', '4444', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4445', '4438', '竹东镇', '0,1,35,4438', '4445', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4446', '4438', '五峰乡', '0,1,35,4438', '4446', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4447', '4438', '横山乡', '0,1,35,4438', '4447', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4448', '4438', '尖石乡', '0,1,35,4438', '4448', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4449', '4438', '北埔乡', '0,1,35,4438', '4449', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4450', '4438', '峨眉乡', '0,1,35,4438', '4450', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4451', '4438', '芎林乡', '0,1,35,4438', '4451', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4452', '3392', '中坜市', '0,1,35,3392', '4452', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4453', '3392', '平镇市', '0,1,35,3392', '4453', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4454', '3392', '龙潭乡', '0,1,35,3392', '4454', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4455', '3392', '杨梅市', '0,1,35,3392', '4455', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4456', '3392', '新屋乡', '0,1,35,3392', '4456', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4457', '3392', '观音乡', '0,1,35,3392', '4457', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4458', '3392', '桃园市', '0,1,35,3392', '4458', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4459', '3392', '龟山乡', '0,1,35,3392', '4459', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4460', '3392', '八德市', '0,1,35,3392', '4460', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4461', '3392', '大溪镇', '0,1,35,3392', '4461', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4462', '3392', '复兴乡', '0,1,35,3392', '4462', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4463', '3392', '大园乡', '0,1,35,3392', '4463', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4464', '3392', '芦竹乡', '0,1,35,3392', '4464', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4465', '3393', '竹南镇', '0,1,35,3393', '4465', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4466', '3393', '头份镇', '0,1,35,3393', '4466', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4467', '3393', '三湾乡', '0,1,35,3393', '4467', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4468', '3393', '南庄乡', '0,1,35,3393', '4468', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4469', '3393', '狮潭乡', '0,1,35,3393', '4469', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4470', '3393', '后龙镇', '0,1,35,3393', '4470', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4471', '3393', '通宵真', '0,1,35,3393', '4471', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4472', '3393', '苑里镇', '0,1,35,3393', '4472', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4473', '3393', '苗栗市', '0,1,35,3393', '4473', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4474', '3393', '造桥乡', '0,1,35,3393', '4474', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4475', '3393', '头屋乡', '0,1,35,3393', '4475', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4476', '3393', '公馆乡', '0,1,35,3393', '4476', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4477', '3393', '大湖乡', '0,1,35,3393', '4477', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4478', '3393', '泰安乡', '0,1,35,3393', '4478', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4479', '3393', '铜锣乡', '0,1,35,3393', '4479', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4480', '3393', '三义乡', '0,1,35,3393', '4480', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4481', '3393', '西湖乡', '0,1,35,3393', '4481', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4482', '3393', '卓兰镇', '0,1,35,3393', '4482', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4483', '3394', '彰化市', '0,1,35,3394', '4483', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4484', '3394', '芬园乡', '0,1,35,3394', '4484', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4485', '3394', '花坛乡', '0,1,35,3394', '4485', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4486', '3394', '秀水乡', '0,1,35,3394', '4486', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4487', '3394', '鹿港镇', '0,1,35,3394', '4487', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4488', '3394', '福兴乡', '0,1,35,3394', '4488', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4489', '3394', '线西乡', '0,1,35,3394', '4489', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4490', '3394', '和美镇', '0,1,35,3394', '4490', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4491', '3394', '伸港乡', '0,1,35,3394', '4491', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4492', '3394', '员林镇', '0,1,35,3394', '4492', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4493', '3394', '社头乡', '0,1,35,3394', '4493', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4494', '3394', '永靖乡', '0,1,35,3394', '4494', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4495', '3394', '埔心乡', '0,1,35,3394', '4495', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4496', '3394', '溪湖镇', '0,1,35,3394', '4496', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4497', '3394', '大村乡', '0,1,35,3394', '4497', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4498', '3394', '埔盐乡', '0,1,35,3394', '4498', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4499', '3394', '田中镇', '0,1,35,3394', '4499', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4500', '3394', '北斗镇', '0,1,35,3394', '4500', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4501', '3394', '田尾乡', '0,1,35,3394', '4501', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4502', '3394', '埤头乡', '0,1,35,3394', '4502', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4503', '3394', '溪洲乡', '0,1,35,3394', '4503', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4504', '3394', '竹塘乡', '0,1,35,3394', '4504', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4505', '3394', '二林镇', '0,1,35,3394', '4505', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4506', '3394', '大城乡', '0,1,35,3394', '4506', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4507', '3394', '芳苑乡', '0,1,35,3394', '4507', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4508', '3394', '二水乡', '0,1,35,3394', '4508', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4509', '35', '嘉义县', '0,1,35', '4509,4510,4511,4512,4513,4514,4515,4516,4517,4518,4519,4520,4521,4522,4523,4524,4525,4526,4527', '1', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4510', '4509', '番路乡', '0,1,35,4509', '4510', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4511', '4509', '梅山乡', '0,1,35,4509', '4511', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4512', '4509', '竹崎乡', '0,1,35,4509', '4512', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4513', '4509', '阿里山乡', '0,1,35,4509', '4513', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4514', '4509', '中埔乡', '0,1,35,4509', '4514', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4515', '4509', '大埔乡', '0,1,35,4509', '4515', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4516', '4509', '水上乡', '0,1,35,4509', '4516', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4517', '4509', '鹿草乡', '0,1,35,4509', '4517', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4518', '4509', '太保市', '0,1,35,4509', '4518', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4519', '4509', '朴子乡', '0,1,35,4509', '4519', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4520', '4509', '东石乡', '0,1,35,4509', '4520', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4521', '4509', '六脚乡', '0,1,35,4509', '4521', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4522', '4509', '新港乡', '0,1,35,4509', '4522', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4523', '4509', '民雄乡', '0,1,35,4509', '4523', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4524', '4509', '大林镇', '0,1,35,4509', '4524', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4525', '4509', '溪口乡', '0,1,35,4509', '4525', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4526', '4509', '义竹乡', '0,1,35,4509', '4526', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4527', '4509', '布袋镇', '0,1,35,4509', '4527', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4528', '3396', '斗南镇', '0,1,35,3396', '4528', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4529', '3396', '大埤乡', '0,1,35,3396', '4529', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4530', '3396', '虎尾镇', '0,1,35,3396', '4530', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4531', '3396', '土库镇', '0,1,35,3396', '4531', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4532', '3396', '褒忠乡', '0,1,35,3396', '4532', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4533', '3396', '东势乡', '0,1,35,3396', '4533', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4534', '3396', '台西乡', '0,1,35,3396', '4534', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4535', '3396', '仑背乡', '0,1,35,3396', '4535', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4536', '3396', '麦寮乡', '0,1,35,3396', '4536', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4537', '3396', '斗六市', '0,1,35,3396', '4537', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4538', '3396', '林内乡', '0,1,35,3396', '4538', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4539', '3396', '古坑乡', '0,1,35,3396', '4539', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4540', '3396', '莿桐乡', '0,1,35,3396', '4540', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4541', '3396', '西螺镇', '0,1,35,3396', '4541', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4542', '3396', '二仑乡', '0,1,35,3396', '4542', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4543', '3396', '北港镇', '0,1,35,3396', '4543', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4544', '3396', '水林乡', '0,1,35,3396', '4544', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4545', '3396', '口湖乡', '0,1,35,3396', '4545', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4546', '3396', '四湖乡', '0,1,35,3396', '4546', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4547', '3396', '元长乡', '0,1,35,3396', '4547', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4548', '3397', '屏东市', '0,1,35,3397', '4548', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4549', '3397', '三地门乡', '0,1,35,3397', '4549', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4550', '3397', '雾台乡', '0,1,35,3397', '4550', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4551', '3397', '玛家乡', '0,1,35,3397', '4551', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4552', '3397', '九如乡', '0,1,35,3397', '4552', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4553', '3397', '里港乡', '0,1,35,3397', '4553', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4554', '3397', '高树乡', '0,1,35,3397', '4554', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4555', '3397', '盐埔乡', '0,1,35,3397', '4555', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4556', '3397', '长治乡', '0,1,35,3397', '4556', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4557', '3397', '麟洛乡', '0,1,35,3397', '4557', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4558', '3397', '竹田乡', '0,1,35,3397', '4558', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4559', '3397', '内埔乡', '0,1,35,3397', '4559', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4560', '3397', '万丹乡', '0,1,35,3397', '4560', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4561', '3397', '潮州镇', '0,1,35,3397', '4561', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4562', '3397', '泰武乡', '0,1,35,3397', '4562', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4563', '3397', '来义乡', '0,1,35,3397', '4563', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4564', '3397', '万峦乡', '0,1,35,3397', '4564', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4565', '3397', '崁顶乡', '0,1,35,3397', '4565', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4566', '3397', '新埤乡', '0,1,35,3397', '4566', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4567', '3397', '南州乡', '0,1,35,3397', '4567', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4568', '3397', '林边乡', '0,1,35,3397', '4568', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4569', '3397', '东港镇', '0,1,35,3397', '4569', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4570', '3397', '琉球乡', '0,1,35,3397', '4570', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4571', '3397', '佳冬乡', '0,1,35,3397', '4571', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4572', '3397', '枋寮乡', '0,1,35,3397', '4572', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4573', '3397', '枋山乡', '0,1,35,3397', '4573', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4574', '3397', '春日乡', '0,1,35,3397', '4574', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4575', '3397', '狮子乡', '0,1,35,3397', '4575', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4576', '3397', '车城乡', '0,1,35,3397', '4576', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4577', '3397', '牡丹乡', '0,1,35,3397', '4577', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4578', '3397', '恒春乡', '0,1,35,3397', '4578', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4579', '3397', '满州乡', '0,1,35,3397', '4579', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4580', '3398', '台东市', '0,1,35,3398', '4580', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4581', '3398', '绿岛乡', '0,1,35,3398', '4581', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4582', '3398', '兰屿乡', '0,1,35,3398', '4582', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4583', '3398', '延平乡', '0,1,35,3398', '4583', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4584', '3398', '卑南乡', '0,1,35,3398', '4584', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4585', '3398', '鹿野乡', '0,1,35,3398', '4585', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4586', '3398', '关山乡', '0,1,35,3398', '4586', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4587', '3398', '海瑞乡', '0,1,35,3398', '4587', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4588', '3398', '池上乡', '0,1,35,3398', '4588', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4589', '3398', '东河乡', '0,1,35,3398', '4589', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4590', '3398', '成功乡', '0,1,35,3398', '4590', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4591', '3398', '长滨乡', '0,1,35,3398', '4591', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4592', '3398', '金峰乡', '0,1,35,3398', '4592', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4593', '3398', '大武乡', '0,1,35,3398', '4593', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4594', '3398', '达人乡', '0,1,35,3398', '4594', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4595', '3398', '太麻里乡', '0,1,35,3398', '4595', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4596', '3399', '花莲市', '0,1,35,3399', '4596', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4597', '3399', '新城乡', '0,1,35,3399', '4597', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4598', '3399', '太鲁镇', '0,1,35,3399', '4598', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4599', '3399', '秀林乡', '0,1,35,3399', '4599', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4600', '3399', '吉安乡', '0,1,35,3399', '4600', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4601', '3399', '寿丰乡', '0,1,35,3399', '4601', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4602', '3399', '凤林镇', '0,1,35,3399', '4602', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4603', '3399', '光复乡', '0,1,35,3399', '4603', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4604', '3399', '丰滨乡', '0,1,35,3399', '4604', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4605', '3399', '瑞穗乡', '0,1,35,3399', '4605', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4606', '3399', '万荣乡', '0,1,35,3399', '4606', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4607', '3399', '玉里镇', '0,1,35,3399', '4607', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4608', '3399', '卓溪乡', '0,1,35,3399', '4608', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4609', '3399', '富里乡', '0,1,35,3399', '4609', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4610', '3400', '马公市', '0,1,35,3400', '4610', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4611', '3400', '西屿乡', '0,1,35,3400', '4611', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4612', '3400', '望安乡', '0,1,35,3400', '4612', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4613', '3400', '七美乡', '0,1,35,3400', '4613', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4614', '3400', '白沙乡', '0,1,35,3400', '4614', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4615', '3400', '湖西乡', '0,1,35,3400', '4615', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4616', '4019', '南竿乡', '0,1,35,4019', '4616', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4617', '4019', '北竿乡', '0,1,35,4019', '4617', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4618', '4019', '莒光乡', '0,1,35,4019', '4618', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');
INSERT INTO `zz_linkage` VALUES ('4619', '4019', '东引乡', '0,1,35,4019', '4619', '0', '', '0', '1', '', '', '', '', '0', '', '0', '');

-- ----------------------------
-- Table structure for `zz_linkage_cat`
-- ----------------------------
DROP TABLE IF EXISTS `zz_linkage_cat`;
CREATE TABLE `zz_linkage_cat` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `catname` varchar(255) NOT NULL COMMENT '分类名称',
  `parentid` int(10) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `arrparentid` text NOT NULL COMMENT '父类ID组',
  `arrchildid` text NOT NULL COMMENT '子类ID组',
  `child` tinyint(1) NOT NULL COMMENT '是否有子级',
  `title` varchar(120) NOT NULL COMMENT '页面标题',
  `keywords` varchar(120) NOT NULL COMMENT '页面关键字',
  `description` varchar(255) NOT NULL COMMENT '页面描述',
  `listorder` smallint(5) NOT NULL DEFAULT '0' COMMENT '排序',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `url` varchar(255) NOT NULL COMMENT 'URL',
  `ismenu` smallint(3) NOT NULL DEFAULT '0' COMMENT '是否导航',
  `isrec` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1推荐',
  `thumb_rec` varchar(255) DEFAULT NULL COMMENT '推荐图',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='同城分类';

-- ----------------------------
-- Records of zz_linkage_cat
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_linkage_nav`
-- ----------------------------
DROP TABLE IF EXISTS `zz_linkage_nav`;
CREATE TABLE `zz_linkage_nav` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `listorder` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `zone` int(11) NOT NULL COMMENT '地区',
  `title` varchar(255) NOT NULL DEFAULT '',
  `linkurl` text NOT NULL,
  `img` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='前台底部菜单';

-- ----------------------------
-- Records of zz_linkage_nav
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_links`
-- ----------------------------
DROP TABLE IF EXISTS `zz_links`;
CREATE TABLE `zz_links` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `userid` int(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(40) NOT NULL DEFAULT '',
  `url` varchar(60) NOT NULL DEFAULT '',
  `listorder` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(11) unsigned NOT NULL DEFAULT '0',
  `lang` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `linktype` varchar(255) NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL DEFAULT '',
  `title_style` varchar(40) NOT NULL DEFAULT '',
  `thumb` varchar(100) NOT NULL DEFAULT '',
  `siteurl` text NOT NULL,
  `intro` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='友情链接';

-- ----------------------------
-- Records of zz_links
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_lottery`
-- ----------------------------
DROP TABLE IF EXISTS `zz_lottery`;
CREATE TABLE `zz_lottery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `act_id` int(11) NOT NULL COMMENT '活动id 或者商品id',
  `typeid` tinyint(2) NOT NULL COMMENT '活动类型 5免费试用，6抽奖',
  `luck_num` int(11) NOT NULL COMMENT '中奖人数',
  `status` tinyint(2) NOT NULL COMMENT '状态：0开奖中，1已开奖',
  `e_time` int(10) DEFAULT NULL COMMENT '开奖时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='开奖列表';

-- ----------------------------
-- Records of zz_lottery
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_lottery_log`
-- ----------------------------
DROP TABLE IF EXISTS `zz_lottery_log`;
CREATE TABLE `zz_lottery_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lottery_id` int(11) NOT NULL COMMENT '活动id 或者商品id',
  `act_id` int(11) NOT NULL COMMENT '活动id',
  `mid` int(11) NOT NULL COMMENT '中奖会员id',
  `order_sn` varchar(30) NOT NULL COMMENT '订单号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_lottery_log
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_mail`
-- ----------------------------
DROP TABLE IF EXISTS `zz_mail`;
CREATE TABLE `zz_mail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(20) NOT NULL COMMENT '帐号',
  `content` text NOT NULL,
  `send_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='邮箱';

-- ----------------------------
-- Records of zz_mail
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_member`
-- ----------------------------
DROP TABLE IF EXISTS `zz_member`;
CREATE TABLE `zz_member` (
  `mid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL COMMENT '用户名',
  `ivt_id` int(11) NOT NULL DEFAULT '0' COMMENT '邀请人',
  `ivt_id_2` int(11) NOT NULL COMMENT '2级推荐人',
  `ivt_id_3` int(11) NOT NULL COMMENT '3级推荐人',
  `zone` int(11) NOT NULL COMMENT '地区id',
  `mobile` varchar(20) NOT NULL COMMENT '手机号码',
  `verify_mobile` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否验证',
  `commission_total` decimal(10,2) NOT NULL COMMENT '佣金总额',
  `commission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '佣金余额',
  `deduct_commission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '扣除佣金',
  `user_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '可用余额',
  `frozen_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '冻结金额',
  `back_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '解冻金额',
  `status` smallint(3) NOT NULL DEFAULT '1' COMMENT '1正常,0关闭',
  `subscribe_time` int(10) NOT NULL COMMENT '关注时间',
  `ivt_count` int(8) NOT NULL DEFAULT '0',
  `ivt_level` tinyint(2) NOT NULL DEFAULT '0' COMMENT '分销级别',
  `agent_rank` tinyint(2) NOT NULL COMMENT '分销等级',
  `partner_id` int(11) NOT NULL COMMENT '合伙人mid',
  `partner_rank` smallint(3) NOT NULL COMMENT '-2市代理，-1省代理，>0合伙人等级',
  `is_agent` tinyint(2) NOT NULL COMMENT '分销中心开店 0否，1是',
  `agent_time` int(11) NOT NULL COMMENT '开店时间',
  `is_new` tinyint(2) NOT NULL COMMENT '是否为新用户，0是，1否（有拼团过）',
  `is_robots` tinyint(2) NOT NULL COMMENT '系统会员',
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员表';

-- ----------------------------
-- Records of zz_member
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_member_account`
-- ----------------------------
DROP TABLE IF EXISTS `zz_member_account`;
CREATE TABLE `zz_member_account` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `mid` int(10) NOT NULL COMMENT '会员ID',
  `username` varchar(255) NOT NULL COMMENT '会员',
  `admin_id` int(10) NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `admin_user` varchar(255) NOT NULL DEFAULT '' COMMENT '管理员名称',
  `amount` decimal(10,2) NOT NULL COMMENT '充值金额',
  `fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '手续费',
  `add_time` int(10) NOT NULL COMMENT '操作时间',
  `pay_id` int(10) NOT NULL DEFAULT '0' COMMENT '支付ID',
  `pay_name` varchar(60) NOT NULL DEFAULT '' COMMENT '支付名称',
  `pay_code` varchar(60) NOT NULL DEFAULT '' COMMENT '支付代码',
  `pay_time` int(10) NOT NULL DEFAULT '0' COMMENT '支付时间',
  `user_note` text COMMENT '用户备注',
  `admin_note` text COMMENT '管理员备注',
  `type` tinyint(1) NOT NULL COMMENT '类型:1充值2提现',
  `status` tinyint(1) NOT NULL COMMENT '支付状态:1待付款2已完成3已取消',
  `gotime` varchar(30) DEFAULT NULL,
  `from` tinyint(1) NOT NULL COMMENT '提现类型,1：佣金提现，2余额提现',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='充值提现';

-- ----------------------------
-- Records of zz_member_account
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_member_address`
-- ----------------------------
DROP TABLE IF EXISTS `zz_member_address`;
CREATE TABLE `zz_member_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL,
  `username` varchar(12) NOT NULL,
  `name` varchar(12) NOT NULL COMMENT '收件人',
  `mobile` varchar(20) NOT NULL COMMENT '手机号',
  `zone` int(11) NOT NULL COMMENT '区域ID',
  `area` varchar(60) NOT NULL COMMENT '区域',
  `address` varchar(60) NOT NULL COMMENT '详细地址',
  `zip` varchar(10) NOT NULL COMMENT '邮编',
  `is_default` tinyint(3) NOT NULL DEFAULT '0' COMMENT '默认地址',
  PRIMARY KEY (`id`),
  KEY `addr_mid` (`mid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='收货地址表';

-- ----------------------------
-- Records of zz_member_address
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_member_agent`
-- ----------------------------
DROP TABLE IF EXISTS `zz_member_agent`;
CREATE TABLE `zz_member_agent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL COMMENT '用户ID',
  `mobile` varchar(11) NOT NULL COMMENT '手机号码',
  `type` tinyint(2) NOT NULL COMMENT '1:合伙人，2:省代理，3:市代理',
  `areaid` int(11) NOT NULL COMMENT ' 地区ID',
  `status` tinyint(2) NOT NULL COMMENT '状态 0:审核中，1:通过，2:不通过',
  `agent_scale` float(5,2) DEFAULT NULL COMMENT '代理利润百分比',
  `c_time` int(11) NOT NULL COMMENT '申请日期',
  `u_time` int(11) NOT NULL COMMENT '修改时间',
  `addip` varchar(20) NOT NULL,
  `remark` varchar(255) DEFAULT NULL COMMENT '备注',
  `rank` tinyint(1) NOT NULL COMMENT '合伙人等级',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='合伙人和代理商 申请记录表';

-- ----------------------------
-- Records of zz_member_agent
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_member_agent_rank`
-- ----------------------------
DROP TABLE IF EXISTS `zz_member_agent_rank`;
CREATE TABLE `zz_member_agent_rank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '等级名称',
  `scale` varchar(11) DEFAULT NULL COMMENT '级别佣金比例',
  `listorder` int(2) DEFAULT NULL COMMENT '权重',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='合伙人等级';

-- ----------------------------
-- Records of zz_member_agent_rank
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_member_bankcard`
-- ----------------------------
DROP TABLE IF EXISTS `zz_member_bankcard`;
CREATE TABLE `zz_member_bankcard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `mid` int(11) NOT NULL,
  `username` varchar(60) NOT NULL,
  `bankname` varchar(60) NOT NULL,
  `bankcard` varchar(255) NOT NULL,
  `bankaddress` varchar(255) NOT NULL,
  `is_default` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='提现银行';

-- ----------------------------
-- Records of zz_member_bankcard
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_member_comms_rank`
-- ----------------------------
DROP TABLE IF EXISTS `zz_member_comms_rank`;
CREATE TABLE `zz_member_comms_rank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT '等级名称',
  `comss_scale` varchar(255) DEFAULT NULL COMMENT '级别佣金比例 序列化',
  `conditions` varchar(255) DEFAULT NULL COMMENT '自动升级条件',
  `con_join` varchar(11) DEFAULT NULL COMMENT '条件结合 与:and 或:or',
  `listorder` int(2) DEFAULT NULL COMMENT '权重',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='佣金等级 有sid';

-- ----------------------------
-- Records of zz_member_comms_rank
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_member_detail`
-- ----------------------------
DROP TABLE IF EXISTS `zz_member_detail`;
CREATE TABLE `zz_member_detail` (
  `mid` int(11) unsigned NOT NULL,
  `password` varchar(64) DEFAULT NULL,
  `pay_password` varchar(64) DEFAULT NULL COMMENT '支付密码',
  `salt` char(6) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL COMMENT '头像',
  `nickname` varchar(30) DEFAULT NULL COMMENT '昵称',
  `realname` varchar(20) DEFAULT NULL COMMENT '真实姓名',
  `idcard` varchar(60) DEFAULT NULL COMMENT '身份证号',
  `rank_id` smallint(3) NOT NULL DEFAULT '0' COMMENT '等级id',
  `email` varchar(100) DEFAULT NULL COMMENT '邮箱',
  `verify_email` tinyint(2) DEFAULT '0',
  `address` varchar(100) DEFAULT NULL COMMENT '地址',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `sex` tinyint(2) DEFAULT '1' COMMENT '1男,2女',
  `ip` char(15) DEFAULT NULL COMMENT '注册IP',
  `lastip` char(15) NOT NULL COMMENT '上次登陆IP',
  `login_time` smallint(6) DEFAULT '0' COMMENT '登录次数',
  `login` int(11) DEFAULT NULL COMMENT '当前登陆时间',
  `lastlogin` int(11) unsigned DEFAULT NULL,
  `c_time` int(10) DEFAULT NULL,
  `intro` varchar(50) DEFAULT NULL,
  `qq` varchar(30) DEFAULT NULL,
  `wx` varchar(50) DEFAULT NULL COMMENT '微信号',
  `mshop_img` varchar(255) DEFAULT NULL COMMENT '微店banner',
  `mshop_name` varchar(255) DEFAULT NULL COMMENT '微店名称',
  `mshop_notice` varchar(255) DEFAULT NULL COMMENT '微店公告',
  `mshop_share` varchar(255) DEFAULT NULL COMMENT '微店分享语',
  `good_share` varchar(255) DEFAULT NULL COMMENT '商品分享语',
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户详情表';

-- ----------------------------
-- Records of zz_member_detail
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_member_login_log`
-- ----------------------------
DROP TABLE IF EXISTS `zz_member_login_log`;
CREATE TABLE `zz_member_login_log` (
  `sesskey` char(32) NOT NULL,
  `mid` int(11) NOT NULL DEFAULT '0',
  `adminid` int(11) NOT NULL DEFAULT '0',
  `ip` char(15) NOT NULL,
  `c_time` int(10) NOT NULL,
  PRIMARY KEY (`sesskey`),
  KEY `sesskey` (`sesskey`) USING BTREE,
  KEY `mid` (`mid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员登陆记录';

-- ----------------------------
-- Records of zz_member_login_log
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_member_opinion`
-- ----------------------------
DROP TABLE IF EXISTS `zz_member_opinion`;
CREATE TABLE `zz_member_opinion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL COMMENT '会员id',
  `username` varchar(60) DEFAULT NULL COMMENT '会员',
  `mobile` varchar(12) NOT NULL COMMENT '手机号码',
  `email` varchar(20) NOT NULL COMMENT '邮箱',
  `content` varchar(255) DEFAULT NULL COMMENT '内容',
  `status` tinyint(2) NOT NULL COMMENT '状态 0:未读,1:已读',
  `c_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='意见反馈表';

-- ----------------------------
-- Records of zz_member_opinion
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_member_rank`
-- ----------------------------
DROP TABLE IF EXISTS `zz_member_rank`;
CREATE TABLE `zz_member_rank` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `rank_name` varchar(30) NOT NULL DEFAULT '' COMMENT '等级名称',
  `min_points` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '经验值下限',
  `max_points` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '经验值上限',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '图标',
  `special` tinyint(1) DEFAULT '0' COMMENT '特殊会员组',
  `allow_time` int(8) NOT NULL DEFAULT '0' COMMENT '分享次数',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='会员等级';

-- ----------------------------
-- Records of zz_member_rank
-- ----------------------------
INSERT INTO `zz_member_rank` VALUES ('9', '农民', '0', '5000', '', '0', '10');
INSERT INTO `zz_member_rank` VALUES ('10', '商人', '5000', '10000', '', '0', '0');
INSERT INTO `zz_member_rank` VALUES ('11', '掌柜', '10000', '20000', '', '0', '0');
INSERT INTO `zz_member_rank` VALUES ('12', '大地主', '20000', '50000', '', '0', '0');
INSERT INTO `zz_member_rank` VALUES ('13', '大财主', '20000', '25000', '', '0', '0');

-- ----------------------------
-- Table structure for `zz_member_redpack`
-- ----------------------------
DROP TABLE IF EXISTS `zz_member_redpack`;
CREATE TABLE `zz_member_redpack` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `mid` int(10) NOT NULL COMMENT '会员ID',
  `username` varchar(255) NOT NULL COMMENT '会员',
  `sendname` varchar(50) NOT NULL COMMENT '发送人',
  `mch_billno` varchar(30) NOT NULL COMMENT '商户订单号',
  `amount` decimal(10,2) NOT NULL COMMENT '金额',
  `actname` varchar(50) NOT NULL COMMENT '活动名称',
  `wishing` text NOT NULL COMMENT '红包祝福语',
  `remark` varchar(255) NOT NULL COMMENT '备注',
  `status` tinyint(1) NOT NULL COMMENT '发送状态:1待付款2已完成3已取消',
  `add_time` int(10) NOT NULL COMMENT '发放时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='红包记录表';

-- ----------------------------
-- Records of zz_member_redpack
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_menus`
-- ----------------------------
DROP TABLE IF EXISTS `zz_menus`;
CREATE TABLE `zz_menus` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `parentid` smallint(5) DEFAULT NULL,
  `mod` varchar(30) DEFAULT NULL,
  `action` varchar(30) DEFAULT NULL,
  `param` varchar(100) DEFAULT NULL,
  `icon` varchar(10) DEFAULT NULL,
  `listorder` int(11) DEFAULT '0',
  `type` tinyint(2) DEFAULT '1' COMMENT '1:用于显示,2用于处理子菜单关系',
  `status` tinyint(1) DEFAULT '1',
  `issystem` tinyint(1) DEFAULT '0',
  `module` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=254 DEFAULT CHARSET=utf8 COMMENT='后台菜单';

-- ----------------------------
-- Records of zz_menus
-- ----------------------------
INSERT INTO `zz_menus` VALUES ('1', '系统', '0', 'setting', 'index', '', '', '0', '1', '1', '1', null);
INSERT INTO `zz_menus` VALUES ('2', '站点配置', '1', 'setting', 'index', '', '&#xe634;', '0', '1', '1', null, null);
INSERT INTO `zz_menus` VALUES ('3', '文章', '0', 'website', 'index', '', '&#xe631;', '3', '1', '1', '1', null);
INSERT INTO `zz_menus` VALUES ('15', '添加/编辑菜单', '18', 'menus', 'edit', '', null, '1', '2', '1', null, null);
INSERT INTO `zz_menus` VALUES ('17', '扩展', '0', 'extend', 'extend', '', null, '6', '1', '1', '1', null);
INSERT INTO `zz_menus` VALUES ('18', '后台菜单', '1', 'menus', 'index', '', '&#xe631;', '1', '1', '1', null, null);
INSERT INTO `zz_menus` VALUES ('22', '修改密码', '2', 'setting', 'chpass', '', '', '0', '2', '1', null, null);
INSERT INTO `zz_menus` VALUES ('27', '模型管理', '3', 'module', 'index', '', '&#xe62d;', '2', '1', '1', null, null);
INSERT INTO `zz_menus` VALUES ('28', '添加/编辑模型', '27', 'module', 'edit', '', '', '1', '2', '1', null, null);
INSERT INTO `zz_menus` VALUES ('29', '字段管理', '27', 'field', 'index', '', '', '2', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('30', '添加/编辑字段', '27', 'field', 'edit', '', '', '3', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('31', '栏目管理', '3', 'category', 'index', '', '&#xe631;', '0', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('32', '添加/编辑栏目', '31', 'category', 'edit', '', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('47', '推荐位', '89', 'posid', 'index', '', '&#xe632;', '3', '1', '0', '0', null);
INSERT INTO `zz_menus` VALUES ('48', '添加/编辑推荐位', '47', 'posid', 'edit', '', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('65', '文章模型', '3', 'content', 'index', 'article', '&#xe601;', '0', '1', '1', '0', 'article');
INSERT INTO `zz_menus` VALUES ('66', '添加/编辑内容', '65', 'content', 'edit', 'article', null, '0', '2', '1', '0', 'article');
INSERT INTO `zz_menus` VALUES ('71', '修改内容', '31', 'content', 'edit', 'page', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('72', '友情链接', '89', 'content', 'index', 'links', '&#xe646;', '0', '1', '1', '0', 'links');
INSERT INTO `zz_menus` VALUES ('73', '添加/编辑内容', '72', 'content', 'edit', 'links', null, '0', '2', '1', '0', 'links');
INSERT INTO `zz_menus` VALUES ('74', '广告模型', '89', 'content', 'index', 'ad', '&#xe601;', '2', '1', '1', '0', 'ad');
INSERT INTO `zz_menus` VALUES ('75', '添加/编辑内容', '74', 'content', 'edit', 'ad', null, '0', '2', '1', '0', 'ad');
INSERT INTO `zz_menus` VALUES ('76', '分配权限', '2', 'setting', 'node', '1', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('77', '文章碎片', '89', 'block', 'index', '', '&#xe619;', '1', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('78', '添加/编辑碎片', '77', 'block', 'edit', '', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('79', '自定义导航', '89', 'content', 'index', 'nav', '&#xe601;', '1', '1', '1', '0', 'nav');
INSERT INTO `zz_menus` VALUES ('80', '添加/编辑内容', '79', 'content', 'edit', 'nav', null, '0', '2', '1', '0', 'nav');
INSERT INTO `zz_menus` VALUES ('81', '媒体库', '17', 'upload', 'media', '', '&#xe602;', '0', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('87', '地区', '89', 'linkage', 'index/?id=1', '', '&#xe631;', '4', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('88', '添加/编辑联动菜单', '87', 'linkage', 'edit', '', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('89', '模块', '0', 'content', 'index', '', '', '5', '1', '1', '1', null);
INSERT INTO `zz_menus` VALUES ('90', '微信', '0', 'wechat', '', '', '&#xe615;', '7', '1', '1', '1', null);
INSERT INTO `zz_menus` VALUES ('91', '菜单设置', '90', 'wxmenu', 'index', '', '&#xe605;', '0', '1', '1', '1', null);
INSERT INTO `zz_menus` VALUES ('92', '素材管理', '90', 'wxmedia', '', '', '&#xe605;', '0', '1', '1', '1', null);
INSERT INTO `zz_menus` VALUES ('93', '自动回复', '90', 'wxreply', '', '', '&#xe605;', '0', '1', '1', '1', null);
INSERT INTO `zz_menus` VALUES ('95', '会员', '0', 'member', '', '', '', '2', '1', '1', '1', null);
INSERT INTO `zz_menus` VALUES ('96', '会员管理', '95', 'member', 'index', '', '&#xe621;', '0', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('97', '商城', '0', 'mall', 'index', '', '', '1', '1', '1', '1', null);
INSERT INTO `zz_menus` VALUES ('98', '商品管理', '97', 'goods', 'index', '', '&#xe631;', '1', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('99', '账户明细', '96', 'member', 'account_log', '', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('106', '订单管理', '209', 'order', 'index', '', '&#xe647;', '9', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('107', '订单详情', '106', 'order', 'detail', '', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('123', '支付方式', '1', 'payment', 'index', '', '&#xe614;', '2', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('125', '管理员列表', '1', 'admin', 'index', '', '&#xe614;', '4', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('126', '管理员角色', '1', 'admin', 'role', '', '&#xe614;', '5', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('127', '日志管理', '1', 'logs', 'index', '', '&#xe614;', '6', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('128', '添加/编辑', '125', 'admin', 'edit', '', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('129', '添加/编辑', '126', 'admin', 'edit_role', '', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('130', '短信验证码日志', '127', 'log', 'sms', '', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('132', '邮箱短信模板', '1', 'templates', 'index', '', '&#xe614;', '3', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('133', '控制面板', '1', 'timing', 'index', '', '&#xe641;', '-1', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('136', '商品分类', '97', 'goodscat', 'index', '', '&#xe605;', '2', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('138', '评价管理', '97', 'rate', 'index', '', '&#xe605;', '8', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('145', '会员等级', '95', 'member_rank', 'index', '', '&#xe640;', '3', '1', '0', '0', null);
INSERT INTO `zz_menus` VALUES ('147', '佣金明细', '95', 'member', 'commission', '', '&#xe605;', '7', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('149', '佣金提现', '95', 'member', 'withdraw_commission', '', '&#xe605;', '8', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('153', '商品品牌', '97', 'brand', 'index', '', '&#xe605;', '3', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('156', '发送队列 ', '132', 'templates', 'send', '', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('157', '删除内容', '65', 'content', 'del', 'article', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('158', '快递公司', '1', 'express', 'index', '', '&#xe614;', '2', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('159', '视频模型', '3', 'content', 'index', 'video', '&#xe601;', '0', '1', '0', '0', 'video');
INSERT INTO `zz_menus` VALUES ('160', '添加/编辑内容', '159', 'content', 'edit', 'video', null, '0', '2', '1', '0', 'video');
INSERT INTO `zz_menus` VALUES ('164', '多媒体分类', '17', 'gallery_tag', 'index', '', '&#xe631;', '0', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('165', '添加/编辑', '164', 'gallery_tag', 'edit', '', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('174', '实名认证', '95', 'member', 'verify_idcard', '', '&#xe63f;', '0', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('181', '模板消息', '1', 'template_msg', 'index', '', '&#xe601;', '2', '1', '1', '0', '');
INSERT INTO `zz_menus` VALUES ('182', '意见反馈', '95', 'member', 'opinion', '', '&#xe605;', '5', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('183', '营销', '0', 'marketing', 'index', '', '&#xe601;', '3', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('184', '积分设置', '183', 'score', 'index', '', '&#xe601;', '0', '1', '0', '0', null);
INSERT INTO `zz_menus` VALUES ('186', '优惠券管理', '183', 'coupon', 'index', '', '&#xe601;', '0', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('187', '添加/编辑内容', '186', 'coupon', 'edit', '', '&#xe601;', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('188', '发放记录', '186', 'coupon', 'logs', '', '&#xe601;', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('189', '同城分类', '97', 'linkagecat', 'index', '', '&#xe605;', '2', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('190', '大转盘管理', '183', 'wheel', 'index', '', '&#xe601;', '0', '1', '0', '0', null);
INSERT INTO `zz_menus` VALUES ('191', '活动列表', '190', 'wheel', 'index', '', '&#xe601;', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('192', '中奖记录', '190', 'wheel', 'logs', '', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('193', '添加/编辑内容', '190', 'wheel', 'edit', '', '&#xe601;', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('195', '规格管理', '97', 'goods/spec', 'index', '', '&#xe605;', '2', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('196', '会员公告', '95', 'member', 'message', '', '&#xe645;', '0', '1', '0', '0', null);
INSERT INTO `zz_menus` VALUES ('201', '海淘分类', '97', 'nation', 'index', '', '&#xe605;', '3', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('202', '拼团订单', '209', 'team', 'index', '', '&#xe647;', '10', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('203', '拼团详情', '202', 'team', 'detail', '', '&#xe647;', '10', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('204', '快递单打印模版', '158', 'express', 'printEdit', '', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('205', '退款列表', '209', 'refund', 'lists/1', '', '&#xe647;', '10', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('206', '退货列表', '209', 'refund', 'lists/2', '', '&#xe647;', '10', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('207', '收货地址', '206', 'refund', 'address_list', '', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('208', '退款记录', '209', 'refund', 'log', '', '&#xe647;', '10', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('209', '订单', '0', 'order', 'index', '', '', '1', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('212', '快报模型', '3', 'content', 'index', 'kuaibao', '&#xe601;', '0', '1', '1', '0', 'kuaibao');
INSERT INTO `zz_menus` VALUES ('213', '添加/编辑内容', '212', 'content', 'edit', 'kuaibao', null, '0', '2', '1', '0', 'kuaibao');
INSERT INTO `zz_menus` VALUES ('214', '添加会员', '96', 'member', 'edit', '', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('215', '删除', '98', 'goods', 'del', '', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('218', 'apk版本模型', '3', 'content', 'index', 'apk_version', '&#xe601;', '0', '1', '1', '0', 'apk_version');
INSERT INTO `zz_menus` VALUES ('219', '添加/编辑内容', '218', 'content', 'edit', 'apk_version', null, '0', '2', '1', '0', 'apk_version');
INSERT INTO `zz_menus` VALUES ('220', '数据库', '1', 'databack', 'index', 'export', '&#xe601;', '6', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('221', '数据库还原', '220', 'databack', 'index', 'import', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('222', '自提地址', '97', 'take_address', 'index', '', '&#xe605;', '8', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('223', '添加/编辑', '222', 'take_address', 'edit', '', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('224', '删除', '222', 'take_address', 'del', '', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('225', '专题活动', '183', 'topic', 'index/2', '', '&#xe601;', '10', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('229', '核销人员', '222', 'take_address', 'take_user', '', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('230', '设置区域', '158', 'express', 'expressShippingList', '', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('231', '添加配送区域', '158', 'express', 'editExpressShipping', '', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('232', '统计', '0', 'statistic', '', '', '', '10', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('233', '概述及设置', '232', 'statistic', 'index', '', '', '0', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('234', '进账记录', '232', 'statistic', 'pay_log', '', '', '0', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('235', '出账记录', '232', 'statistic', 'refund_log', '', '', '0', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('236', '客服', '0', 'chat', 'index', '', '&#xe601;', '11', '1', '1', '1', null);
INSERT INTO `zz_menus` VALUES ('237', '接待窗口', '236', 'chat', 'index', '', '&#xe601;', '0', '1', '1', '1', null);
INSERT INTO `zz_menus` VALUES ('238', '手机接待', '236', 'chat', 'mobile', '', '&#xe601;', '0', '1', '1', '1', null);
INSERT INTO `zz_menus` VALUES ('239', '客服设置', '236', 'chat', 'setting', '', '&#xe601;', '0', '1', '1', '1', null);
INSERT INTO `zz_menus` VALUES ('240', '聊天记录', '236', 'chat', 'records', '', '&#xe601;', '0', '1', '1', '1', null);
INSERT INTO `zz_menus` VALUES ('241', '自动回复', '236', 'chat', 'autoreplay', '', '&#xe601;', '0', '1', '1', '1', null);
INSERT INTO `zz_menus` VALUES ('242', '自动回复编辑', '241', 'chat', 'autoreplay_edit', '', '&#xe601;', '0', '2', '1', '1', null);
INSERT INTO `zz_menus` VALUES ('243', '微信消息模板', '1', 'wxtemplate', '', '', '&#xe601;', '2', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('244', '专题分类', '183', 'topiccat', 'index', '', '&#xe601;', '9', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('246', '商品标签', '97', 'goods_tag', 'index', '', '&#xe601;', '9', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('247', '活动类型', '183', 'topic_type', 'index', '', '&#xe601;', '7', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('248', '竞价活动', '183', 'topic', 'index/1', '', '&#xe601;', '8', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('249', '联动导航', '87', 'linkage/nav', '', '', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('250', '首页推广', '89', 'spread', 'index', '', '&#xe601;', '0', '1', '1', '0', 'spread');
INSERT INTO `zz_menus` VALUES ('251', '添加/编辑内容', '250', 'spread', 'edit', '', null, '0', '2', '1', '0', 'spread');
INSERT INTO `zz_menus` VALUES ('252', '国家馆', '97', 'country', 'index', '', '&#xe605;', '3', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('253', '编辑', '98', 'goods', 'edit', '', '', '0', '2', '1', '0', null);

-- ----------------------------
-- Table structure for `zz_message`
-- ----------------------------
DROP TABLE IF EXISTS `zz_message`;
CREATE TABLE `zz_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发送人id',
  `target` int(11) NOT NULL DEFAULT '0' COMMENT '-1 多人 数字 指定目标id单人  多人时预先生成引用',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '消息类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '站内信标题',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '站内信内容',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0已删除 1正常',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`) USING BTREE,
  KEY `target` (`target`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='站内信';

-- ----------------------------
-- Records of zz_message
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_message_status`
-- ----------------------------
DROP TABLE IF EXISTS `zz_message_status`;
CREATE TABLE `zz_message_status` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '接受人id',
  `message_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '引用的站内信id',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0已删除 1未读 2已读',
  PRIMARY KEY (`id`),
  KEY `mid` (`mid`) USING BTREE,
  KEY `message_id` (`message_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='站内信引用规则表';

-- ----------------------------
-- Records of zz_message_status
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_module`
-- ----------------------------
DROP TABLE IF EXISTS `zz_module`;
CREATE TABLE `zz_module` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `description` varchar(200) NOT NULL DEFAULT '',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `issystem` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `issearch` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `listsearch` varchar(255) NOT NULL,
  `listshow` varchar(255) NOT NULL,
  `listfields` varchar(255) NOT NULL DEFAULT '',
  `htorder` varchar(255) DEFAULT NULL,
  `setup` mediumtext NOT NULL,
  `listorder` smallint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='模型分类表';

-- ----------------------------
-- Records of zz_module
-- ----------------------------
INSERT INTO `zz_module` VALUES ('1', '单页模型', 'page', '单页模型', '3', '1', '1', '', '', '*', null, '', '1', '1');
INSERT INTO `zz_module` VALUES ('2', '文章模型', 'article', '文章模型', '3', '1', '1', '', 'title', '*', '', '', '0', '1');
INSERT INTO `zz_module` VALUES ('5', '友情链接', 'links', '扩展模型', '17', '0', '1', 'title', 'title,siteurl', '*', 'listorder,id', '', '0', '1');
INSERT INTO `zz_module` VALUES ('6', '广告模型', 'ad', '广告位和广告模型', '89', '1', '0', '', 'title,typeid,images,width,height', '*', 'listorder,id', '', '2', '1');
INSERT INTO `zz_module` VALUES ('7', '自定义导航', 'nav', '自定义导航模型', '1', '1', '1', 'title,catid,posid,type,status', 'title,linkurl,type', '*', 'listorder,id', '', '3', '1');
INSERT INTO `zz_module` VALUES ('8', '商品推荐位', 'rec', '', '97', '1', '1', '', 'title,recid', '*', '', '', '0', '1');
INSERT INTO `zz_module` VALUES ('9', '视频模型', 'video', '视频模型', '3', '0', '1', '', '', '*', '', '', '0', '1');
INSERT INTO `zz_module` VALUES ('10', '快报模型', 'kuaibao', '快报模型', '3', '0', '1', '', '', '*', '', '', '0', '1');
INSERT INTO `zz_module` VALUES ('11', 'apk版本模型', 'apk_version', 'apk版本模型', '3', '0', '1', '', '', '*', '', '', '0', '1');
INSERT INTO `zz_module` VALUES ('13', '首页推广', 'spread', '首页推广模型', '89', '0', '1', 'typeid', 'typeid', '*', '', '', '0', '1');

-- ----------------------------
-- Table structure for `zz_module_field`
-- ----------------------------
DROP TABLE IF EXISTS `zz_module_field`;
CREATE TABLE `zz_module_field` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `moduleid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `field` varchar(20) NOT NULL DEFAULT '',
  `name` varchar(30) NOT NULL DEFAULT '',
  `tips` varchar(150) NOT NULL DEFAULT '',
  `required` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `minlength` int(10) unsigned NOT NULL DEFAULT '0',
  `maxlength` int(10) unsigned NOT NULL DEFAULT '0',
  `pattern` varchar(255) NOT NULL DEFAULT '',
  `errormsg` varchar(255) NOT NULL DEFAULT '',
  `class` varchar(20) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `setup` mediumtext NOT NULL,
  `ispost` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `unpostgroup` varchar(60) NOT NULL DEFAULT '',
  `listorder` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `issystem` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8 COMMENT='文章模块字段表';

-- ----------------------------
-- Records of zz_module_field
-- ----------------------------
INSERT INTO `zz_module_field` VALUES ('1', '1', 'createtime', '发布时间', '', '1', '0', '0', '', '', '', 'datetime', '', '0', '0', '96', '1', '1');
INSERT INTO `zz_module_field` VALUES ('2', '1', 'status', '状态', '', '0', '0', '0', '', '', '', 'radio', '{\"options\":\"\\u5df2\\u5ba1\\u6838|1\\r\\n\\u672a\\u5ba1\\u6838|0\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"labelwidth\":\"75\",\"default\":\"1\"}', '0', '0', '100', '1', '1');
INSERT INTO `zz_module_field` VALUES ('3', '1', 'title', '标题', '', '1', '0', '0', '', '', '', 'title', '{\"thumb\":\"0\",\"style\":\"0\",\"size\":\"\"}', '0', '', '0', '1', '1');
INSERT INTO `zz_module_field` VALUES ('4', '1', 'content', '内容', '', '0', '0', '0', '', '', '', 'editor', '{\"edittype\":\"kindeditor\",\"toolbar\":\"basic\",\"default\":\"\",\"height\":\"\"}', '0', '', '0', '1', '1');
INSERT INTO `zz_module_field` VALUES ('7', '1', 'hits', '点击次数', '', '0', '0', '0', '', '', '', 'number', '{\"size\":\"60\",\"numbertype\":\"1\",\"decimaldigits\":\"0\",\"default\":\"0\"}', '0', '', '95', '1', '1');
INSERT INTO `zz_module_field` VALUES ('8', '2', 'catid', '栏目', '', '1', '1', '6', '', '必须选择一个栏目', '', 'catid', '', '1', '', '0', '1', '1');
INSERT INTO `zz_module_field` VALUES ('9', '2', 'title', '标题', '', '1', '1', '80', '', '标题必填3-80个字', '', 'title', '{\"thumb\":\"1\",\"btntext\":\"\",\"style\":\"1\",\"size\":\"\"}', '1', '0', '0', '1', '1');
INSERT INTO `zz_module_field` VALUES ('10', '2', 'keywords', 'SEO关键词', '', '0', '0', '80', '', '', '', 'text', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\",\"fieldtype\":\"varchar\"}', '1', '0', '97', '1', '1');
INSERT INTO `zz_module_field` VALUES ('11', '2', 'description', 'SEO描述', '', '0', '0', '0', '', '', '', 'textarea', '{\"fieldtype\":\"mediumtext\",\"width\":\"\",\"height\":\"\",\"default\":\"\"}', '1', '0', '98', '1', '1');
INSERT INTO `zz_module_field` VALUES ('12', '2', 'content', '内容', '', '0', '0', '0', '', '', '', 'editor', '{\"edittype\":\"kindeditor\",\"toolbar\":\"full\",\"default\":\"\",\"height\":\"\"}', '1', '0', '0', '1', '1');
INSERT INTO `zz_module_field` VALUES ('13', '2', 'hits', '点击次数', '', '0', '0', '8', '', '', '', 'number', '{\"size\":\"60\",\"numbertype\":\"1\",\"decimaldigits\":\"0\",\"default\":\"0\"}', '1', '0', '95', '1', '1');
INSERT INTO `zz_module_field` VALUES ('14', '2', 'posid', '推荐位', '', '0', '0', '0', '', '', '', 'posid', '', '1', '0', '94', '1', '1');
INSERT INTO `zz_module_field` VALUES ('15', '2', 'createtime', '发布时间', '', '0', '0', '0', '', '', '', 'datetime', '', '1', '0', '96', '1', '1');
INSERT INTO `zz_module_field` VALUES ('16', '2', 'template', '模板', '', '0', '0', '0', '', '', '', 'template', '', '0', '0', '99', '1', '1');
INSERT INTO `zz_module_field` VALUES ('17', '2', 'status', '状态', '', '0', '0', '0', '', '', '', 'radio', '{\"options\":\"\\u5f00\\u542f|1\\r\\n\\u5173\\u95ed|0\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"labelwidth\":\"75\",\"default\":\"1\"}', '0', '0', '100', '1', '1');
INSERT INTO `zz_module_field` VALUES ('31', '5', 'createtime', '发布时间', '', '1', '0', '0', '', '', '', 'datetime', '', '0', '0', '96', '1', '1');
INSERT INTO `zz_module_field` VALUES ('32', '5', 'status', '状态', '', '0', '0', '0', '', '', '', 'radio', '{\"options\":\"\\u5df2\\u5ba1\\u6838|1\\r\\n\\u672a\\u5ba1\\u6838|0\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"labelwidth\":\"75\",\"default\":\"1\"}', '0', '0', '100', '1', '1');
INSERT INTO `zz_module_field` VALUES ('33', '5', 'linktype', '链接类型', '', '1', '0', '0', '', '', '', 'radio', '{\"options\":\"\\u4f01\\u4e1a\\u7c7b|1\\r\\n\\u95e8\\u6237\\u7c7b|2\\r\\n\\u7535\\u5546\\u7c7b|3\",\"fieldtype\":\"varchar\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"1\"}', '0', '', '0', '1', '0');
INSERT INTO `zz_module_field` VALUES ('34', '5', 'title', '链接名称', '', '1', '0', '0', '', '', '', 'title', '{\"thumb\":\"1\",\"style\":\"0\",\"size\":\"\"}', '0', '', '0', '1', '0');
INSERT INTO `zz_module_field` VALUES ('35', '5', 'siteurl', '链接地址', '', '0', '0', '0', 'url', '', '', 'text', '{\"size\":\"\",\"default\":\"http:\\/\\/\",\"ispassword\":\"0\"}', '0', '', '0', '1', '0');
INSERT INTO `zz_module_field` VALUES ('37', '5', 'intro', '简介', '', '0', '0', '0', '', '', '', 'textarea', '{\"fieldtype\":\"text\",\"width\":\"\",\"height\":\"\",\"default\":\"\"}', '0', '', '0', '1', '0');
INSERT INTO `zz_module_field` VALUES ('40', '6', 'createtime', '发布时间', '', '1', '0', '0', '', '', '', 'datetime', '', '0', '0', '96', '1', '1');
INSERT INTO `zz_module_field` VALUES ('41', '6', 'status', '状态', '', '0', '0', '0', '', '', '', 'radio', '{\"options\":\"\\u5df2\\u5ba1\\u6838|1\\r\\n\\u672a\\u5ba1\\u6838|0\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"labelwidth\":\"75\",\"default\":\"1\"}', '0', '0', '100', '1', '1');
INSERT INTO `zz_module_field` VALUES ('42', '6', 'title', '广告位名称', '', '1', '0', '0', '', '', '', 'title', '{\"thumb\":\"1\",\"btntext\":\"\\u5e7f\\u544a\\u4f4d\\u622a\\u56fe\",\"style\":\"0\",\"size\":\"\"}', '0', '', '0', '1', '0');
INSERT INTO `zz_module_field` VALUES ('43', '6', 'width', '广告位宽度', '广告位宽度请输入数字类型', '0', '0', '0', 'digits', '', '', 'text', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '0', '', '0', '1', '0');
INSERT INTO `zz_module_field` VALUES ('44', '6', 'height', '广告位高度', '广告位高度请输入数字类型', '0', '0', '0', 'digits', '', '', 'text', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '0', '', '0', '1', '0');
INSERT INTO `zz_module_field` VALUES ('45', '6', 'images', '广告图片', '', '0', '0', '0', '', '', '', 'images', '{\"upload_maxnum\":\"10\",\"upload_maxsize\":\"\",\"upload_allowext\":\"*.gif;*.jpg;*.jpeg;*.png\",\"watermark\":\"0\",\"more\":\"0\"}', '0', '', '1', '1', '0');
INSERT INTO `zz_module_field` VALUES ('46', '7', 'createtime', '发布时间', '', '1', '0', '0', '', '', '', 'datetime', '', '0', '0', '100', '1', '1');
INSERT INTO `zz_module_field` VALUES ('47', '7', 'status', '状态', '', '0', '0', '0', '', '', '', 'radio', '{\"options\":\"\\u663e\\u793a|1\\r\\n\\u9690\\u85cf|0\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"1\"}', '0', '0', '96', '1', '1');
INSERT INTO `zz_module_field` VALUES ('48', '7', 'type', '显示位置', '', '1', '0', '0', '', '', '', 'radio', '{\"options\":\"\\u5934\\u90e8\\u5bfc\\u822a|1\\r\\n\\u5e95\\u90e8\\u5bfc\\u822a\\uff08\\u9700\\u4f20\\u4e24\\u5f20\\uff09|4\\r\\n\\u5206\\u4eab\\u9875\\u5bfc\\u822a|2\\r\\n\\u6d77\\u6dd8\\u5bfc\\u822a|3\",\"fieldtype\":\"varchar\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"1\"}', '0', '', '0', '1', '0');
INSERT INTO `zz_module_field` VALUES ('49', '7', 'title', '导航名称', '', '1', '0', '0', '', '', '', 'title', '{\"thumb\":\"0\",\"btntext\":\"\",\"style\":\"0\",\"size\":\"300\"}', '0', '', '0', '1', '0');
INSERT INTO `zz_module_field` VALUES ('50', '7', 'linkurl', '链接地址', '', '0', '0', '0', '', '', '', 'text', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '0', '', '0', '1', '0');
INSERT INTO `zz_module_field` VALUES ('53', '2', 'publish', '来源', '', '0', '0', '0', '', '', '', 'text', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '0', '', '0', '1', '0');
INSERT INTO `zz_module_field` VALUES ('60', '2', 'link', '外部链接', '', '0', '0', '0', '', '', '', 'text', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '0', '', '0', '1', '0');
INSERT INTO `zz_module_field` VALUES ('64', '8', 'createtime', '发布时间', '', '1', '0', '0', '', '', '', 'datetime', '', '0', '0', '96', '1', '1');
INSERT INTO `zz_module_field` VALUES ('65', '8', 'status', '状态', '', '0', '0', '0', '', '', '', 'radio', '{\"options\":\"\\u5df2\\u5ba1\\u6838|1\\r\\n\\u672a\\u5ba1\\u6838|0\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"labelwidth\":\"75\",\"default\":\"1\"}', '0', '0', '100', '1', '1');
INSERT INTO `zz_module_field` VALUES ('66', '8', 'title', '标题', '', '0', '0', '0', '', '', '', 'text', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '0', '', '0', '1', '0');
INSERT INTO `zz_module_field` VALUES ('67', '8', 'thumb', '图片', '', '0', '0', '0', '', '', '', 'images', '{\"upload_maxnum\":\"\",\"upload_maxsize\":\"\",\"upload_allowext\":\"*.gif;*.jpg;*.jpeg;*.png\",\"watermark\":\"0\",\"more\":\"0\"}', '0', '', '0', '1', '0');
INSERT INTO `zz_module_field` VALUES ('68', '8', 'type', '推荐位', '', '1', '0', '0', '', '', '', 'select', '{\"options\":\"\\u9996\\u9875\\u7126\\u70b9\\u56fe\\u53f3\\u4fa7\\u593a\\u5b9d\\u63a8\\u8350\\u4f4d|1\\r\\n\\u9996\\u9875\\u7126\\u70b9\\u56fe\\u4e0b\\u65b9\\u7ade\\u62cd\\u63a8\\u8350\\u4f4d|2\",\"multiple\":\"0\",\"fieldtype\":\"varchar\",\"numbertype\":\"1\",\"size\":\"\",\"default\":\"\"}', '0', '', '0', '1', '0');
INSERT INTO `zz_module_field` VALUES ('69', '8', 'recid', '推荐内容ID', '', '1', '0', '0', 'number', '', '', 'text', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '0', '', '0', '1', '0');
INSERT INTO `zz_module_field` VALUES ('70', '9', 'createtime', '发布时间', '', '1', '0', '0', '', '', '', 'datetime', '', '0', '0', '96', '1', '1');
INSERT INTO `zz_module_field` VALUES ('71', '9', 'status', '状态', '', '0', '0', '0', '', '', '', 'radio', '{\"options\":\"\\u5df2\\u5ba1\\u6838|1\\r\\n\\u672a\\u5ba1\\u6838|0\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"labelwidth\":\"75\",\"default\":\"1\"}', '0', '0', '100', '1', '1');
INSERT INTO `zz_module_field` VALUES ('72', '9', 'title', '视频标题', '', '1', '0', '0', '', '', '', 'title', '{\"thumb\":\"1\",\"btntext\":\"\",\"style\":\"0\",\"size\":\"\"}', '0', '', '1', '1', '0');
INSERT INTO `zz_module_field` VALUES ('73', '9', 'file', '本地视频', '', '0', '0', '0', '', '', '', 'files', '{\"upload_maxnum\":\"1\",\"upload_maxsize\":\"100\",\"upload_allowext\":\"*.mp4;*.flv;\",\"more\":\"0\"}', '0', '', '2', '1', '0');
INSERT INTO `zz_module_field` VALUES ('74', '9', 'posid', '推荐位', '', '0', '0', '0', '', '', '', 'posid', '', '0', '', '10', '1', '0');
INSERT INTO `zz_module_field` VALUES ('75', '9', 'catid', '栏目', '', '1', '0', '0', '', '', '', 'catid', '', '0', '', '0', '1', '0');
INSERT INTO `zz_module_field` VALUES ('76', '9', 'videourl', '远程视频', '', '0', '0', '0', '', '', '', 'text', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '0', '', '3', '1', '0');
INSERT INTO `zz_module_field` VALUES ('78', '6', 'typeid', '广告位置', '', '1', '0', '0', '', '', '', 'select', '{\"options\":\"\\u3010\\u79fb\\u52a8\\u7aef-\\u9996\\u9875\\u7126\\u70b9\\u3011\\u7126\\u70b9\\u56fe|1\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u9996\\u9875\\u5e7f\\u544a\\u3011\\u5e7f\\u544a\\u56fe|17\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u5206\\u4eab\\u9875\\u3011\\u7126\\u70b9\\u56fe|2\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u5e97\\u94fa\\u9875\\u3011\\u7126\\u70b9\\u56fe|3\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u79d2\\u6740\\u9875\\u3011\\u5934\\u90e8\\u56fe\\u7247|4\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u8bd5\\u7528\\u9875\\u3011\\u5934\\u90e8\\u56fe\\u7247|5\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u62bd\\u5956\\u9875\\u3011\\u5934\\u90e8\\u56fe\\u7247|6\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u65b0\\u4eba\\u4e13\\u4eab\\u9875\\u3011\\u5934\\u90e8\\u56fe\\u7247|7\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u65b0\\u4eba\\u4e13\\u4eab\\u9875\\u3011\\u4f18\\u60e0\\u5238\\u56fe\\u7247|8\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u6d77\\u6dd8\\u3011\\u7126\\u70b9\\u56fe|11\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u6d77\\u6dd8\\u3011\\u63a8\\u8350\\u4e13\\u9898|12\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u56e2\\u957f\\u514d\\u5355\\u9875\\u3011\\u56e2\\u957f\\u514d\\u5355|13\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u4f17\\u7b79\\u5c1d\\u9c9c\\u9875\\u3011\\u4f17\\u7b79\\u5c1d\\u9c9c|14\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u54c1\\u724c\\u6e05\\u4ed3\\u9875\\u3011\\u54c1\\u724c\\u6e05\\u4ed3|15\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u65b0\\u54c1\\u9875\\u3011\\u65b0\\u54c1\\u5e7f\\u544a|16\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u6ce8\\u518c\\u767b\\u9646\\u3011\\u6ce8\\u518c\\u767b\\u9646|20\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u5f15\\u5bfc\\u9875\\u56fe\\u7247\\u3011|10\\r\\n\\u3010pc\\u7aef-\\u9996\\u9875\\u3011\\u7126\\u70b9\\u56fe|51\\r\\n\\u3010pc\\u7aef-\\u884c\\u4e1a\\u6848\\u4f8b\\u3011banner|52\\r\\n\\u3010pc\\u7aef-\\u62fc\\u56e2\\u8d44\\u8baf\\u3011banner|53\\r\\n\\u3010pc\\u7aef-\\u8054\\u7cfb\\u6211\\u4eec\\u3011banner|54\",\"multiple\":\"0\",\"fieldtype\":\"varchar\",\"numbertype\":\"1\",\"size\":\"\",\"default\":\"\"}', '0', '', '0', '1', '0');
INSERT INTO `zz_module_field` VALUES ('80', '7', 'img', '图标', '', '0', '0', '0', '', '', '', 'images', '{\"upload_maxnum\":\"1\",\"upload_maxsize\":\"2\",\"upload_allowext\":\"*.gif;*.jpg;*.jpeg;*.png\",\"watermark\":\"0\",\"more\":\"0\"}', '0', '', '0', '1', '0');
INSERT INTO `zz_module_field` VALUES ('81', '2', 'logo', '商家logo', '', '0', '0', '0', '', '', '', 'images', '{\"upload_maxnum\":\"\",\"upload_maxsize\":\"\",\"upload_allowext\":\"*.gif;*.jpg;*.jpeg;*.png\",\"watermark\":\"0\",\"more\":\"0\"}', '0', '', '0', '1', '0');
INSERT INTO `zz_module_field` VALUES ('82', '2', 'ewm', '商家二维码', '', '0', '0', '0', '', '', '', 'images', '{\"upload_maxnum\":\"\",\"upload_maxsize\":\"\",\"upload_allowext\":\"*.gif;*.jpg;*.jpeg;*.png\",\"watermark\":\"0\",\"more\":\"0\"}', '0', '', '0', '1', '0');
INSERT INTO `zz_module_field` VALUES ('83', '10', 'catid', '栏目', '', '1', '1', '6', '', '必须选择一个栏目', '', 'catid', '', '1', '', '0', '1', '1');
INSERT INTO `zz_module_field` VALUES ('84', '10', 'title', '标题', '', '0', '1', '80', '', '标题必填3-80个字', '', 'title', '{\"thumb\":\"1\",\"btntext\":\"\",\"style\":\"1\",\"size\":\"\"}', '1', '0', '2', '1', '1');
INSERT INTO `zz_module_field` VALUES ('85', '10', 'keywords', 'SEO关键词', '', '0', '0', '80', '', '', '', 'text', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\",\"fieldtype\":\"varchar\"}', '1', '0', '97', '0', '1');
INSERT INTO `zz_module_field` VALUES ('86', '10', 'description', 'SEO描述', '', '0', '0', '0', '', '', '', 'textarea', '{\"fieldtype\":\"mediumtext\",\"width\":\"\",\"height\":\"\",\"default\":\"\"}', '1', '0', '98', '0', '1');
INSERT INTO `zz_module_field` VALUES ('87', '10', 'content', '内容', '', '0', '0', '0', '', '', '', 'editor', '{\"toolbar\":\"basic\",\"default\":\"\",\"height\":\"\",\"showpage\":\"1\",\"enablekeylink\":\"0\",\"replacenum\":\"\",\"enablesaveimage\":\"0\",\"flashupload\":\"1\",\"alowuploadexts\":\"\"}', '1', '0', '4', '1', '1');
INSERT INTO `zz_module_field` VALUES ('88', '10', 'hits', '点击次数', '', '0', '0', '8', '', '', '', 'number', '{\"size\":\"60\",\"numbertype\":\"1\",\"decimaldigits\":\"0\",\"default\":\"0\"}', '1', '0', '95', '0', '1');
INSERT INTO `zz_module_field` VALUES ('89', '10', 'posid', '推荐位', '', '0', '0', '0', '', '', '', 'posid', '', '1', '0', '94', '0', '1');
INSERT INTO `zz_module_field` VALUES ('90', '10', 'createtime', '发布时间', '', '0', '0', '0', '', '', '', 'datetime', '', '1', '0', '96', '1', '1');
INSERT INTO `zz_module_field` VALUES ('91', '10', 'template', '模板', '', '0', '0', '0', '', '', '', 'template', '', '0', '0', '99', '0', '1');
INSERT INTO `zz_module_field` VALUES ('92', '10', 'status', '状态', '', '0', '0', '0', '', '', '', 'radio', '{\"options\":\"\\u5df2\\u5ba1\\u6838|1\\r\\n\\u672a\\u5ba1\\u6838|0\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"labelwidth\":\"75\",\"default\":\"1\"}', '0', '0', '100', '1', '1');
INSERT INTO `zz_module_field` VALUES ('93', '10', 'titleprefix', '标题前缀', '', '1', '0', '0', '', '', '', 'text', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '0', '', '1', '1', '0');
INSERT INTO `zz_module_field` VALUES ('94', '10', 'links', '链接', '', '0', '0', '0', '', '', '', 'text', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '0', '', '3', '1', '0');
INSERT INTO `zz_module_field` VALUES ('101', '11', 'catid', '栏目', '', '1', '1', '6', '', '必须选择一个栏目', '', 'catid', '', '1', '', '0', '1', '1');
INSERT INTO `zz_module_field` VALUES ('102', '11', 'title', '版本号', '', '1', '1', '80', '', '标题必填3-80个字', '', 'title', '{\"thumb\":\"0\",\"btntext\":\"\",\"style\":\"1\",\"size\":\"\"}', '1', '0', '0', '1', '1');
INSERT INTO `zz_module_field` VALUES ('103', '11', 'keywords', 'SEO关键词', '', '0', '0', '80', '', '', '', 'text', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\",\"fieldtype\":\"varchar\"}', '1', '0', '97', '0', '1');
INSERT INTO `zz_module_field` VALUES ('104', '11', 'description', 'SEO描述', '', '0', '0', '0', '', '', '', 'textarea', '{\"fieldtype\":\"mediumtext\",\"width\":\"\",\"height\":\"\",\"default\":\"\"}', '1', '0', '98', '0', '1');
INSERT INTO `zz_module_field` VALUES ('105', '11', 'content', '版本内容', '', '1', '0', '0', '', '', '', 'editor', '{\"edittype\":\"kindeditor\",\"toolbar\":\"basic\",\"default\":\"\",\"height\":\"\"}', '1', '0', '2', '1', '1');
INSERT INTO `zz_module_field` VALUES ('106', '11', 'hits', '点击次数', '', '0', '0', '8', '', '', '', 'number', '{\"size\":\"60\",\"numbertype\":\"1\",\"decimaldigits\":\"0\",\"default\":\"0\"}', '1', '0', '95', '0', '1');
INSERT INTO `zz_module_field` VALUES ('107', '11', 'posid', '推荐位', '', '0', '0', '0', '', '', '', 'posid', '', '1', '0', '94', '0', '1');
INSERT INTO `zz_module_field` VALUES ('108', '11', 'createtime', '发布时间', '', '0', '0', '0', '', '', '', 'datetime', '', '1', '0', '96', '1', '1');
INSERT INTO `zz_module_field` VALUES ('109', '11', 'template', '模板', '', '0', '0', '0', '', '', '', 'template', '', '0', '0', '99', '0', '1');
INSERT INTO `zz_module_field` VALUES ('110', '11', 'status', '状态', '', '0', '0', '0', '', '', '', 'radio', '{\"options\":\"\\u5df2\\u5ba1\\u6838|1\\r\\n\\u672a\\u5ba1\\u6838|0\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"labelwidth\":\"75\",\"default\":\"1\"}', '0', '0', '100', '1', '1');
INSERT INTO `zz_module_field` VALUES ('111', '11', 'upload', 'apk上传', '', '1', '0', '0', '', '', '', 'files', '{\"upload_maxnum\":\"1\",\"upload_maxsize\":\"100\",\"upload_allowext\":\"*.apk\",\"more\":\"0\"}', '0', '', '3', '1', '0');
INSERT INTO `zz_module_field` VALUES ('112', '11', 'name', '版本名', '', '1', '0', '0', '', '', '', 'text', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '0', '', '1', '1', '0');
INSERT INTO `zz_module_field` VALUES ('113', '13', 'createtime', '发布时间', '', '1', '0', '0', '', '', '', 'datetime', '', '0', '0', '96', '1', '1');
INSERT INTO `zz_module_field` VALUES ('114', '13', 'status', '状态', '', '0', '0', '0', '', '', '', 'radio', '{\"options\":\"\\u5df2\\u5ba1\\u6838|1\\r\\n\\u672a\\u5ba1\\u6838|0\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"labelwidth\":\"75\",\"default\":\"1\"}', '0', '0', '100', '1', '1');
INSERT INTO `zz_module_field` VALUES ('115', '13', 'typeid', '位置', '', '1', '0', '0', '', '', '', 'radio', '{\"options\":\"\\u79d2\\u6740|1\\r\\n\\u5168\\u7403\\u8d2d|2\\r\\n\\u514d\\u5355\\u5f00\\u56e2|3\\r\\n\\u4f17\\u7b79\\u5c1d\\u9c9c|4\\r\\n\\u5e95\\u4ef7\\u6e05\\u4ed3|5\",\"fieldtype\":\"tinyint\",\"numbertype\":\"1\",\"labelwidth\":\"\",\"default\":\"1\"}', '0', '', '0', '1', '0');
INSERT INTO `zz_module_field` VALUES ('116', '13', 'gid', '商品ID', '', '0', '0', '0', '', '', '', 'text', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '0', '', '0', '1', '0');

-- ----------------------------
-- Table structure for `zz_msg`
-- ----------------------------
DROP TABLE IF EXISTS `zz_msg`;
CREATE TABLE `zz_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) DEFAULT '0',
  `username` varchar(100) DEFAULT NULL,
  `mid_type` tinyint(1) DEFAULT '0' COMMENT '发布人类型0:会员留言1:管理员留言',
  `tomid` int(11) DEFAULT '0',
  `tousername` varchar(100) DEFAULT NULL,
  `tomid_type` tinyint(1) DEFAULT '0' COMMENT '接收人类型0:会员留言1:管理员留言',
  `title` varchar(60) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `add_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='老消息表';

-- ----------------------------
-- Records of zz_msg
-- ----------------------------
INSERT INTO `zz_msg` VALUES ('10', '1', 'lnest_admin', '1', '413', '', '0', '关于2016年五一活动的公告', '五一期间，所有商品均满200减20元，可以累计。', '1461738975');
INSERT INTO `zz_msg` VALUES ('11', '1', 'lnest_admin', '1', '413', '', '0', '全站买100送100活动正式启动', '全站买100送100活动正式启动', '1461828622');
INSERT INTO `zz_msg` VALUES ('13', '1', 'lnest_admin', '1', '0', '所有会员', '0', 'test', 'test111', '1470367100');

-- ----------------------------
-- Table structure for `zz_mshop`
-- ----------------------------
DROP TABLE IF EXISTS `zz_mshop`;
CREATE TABLE `zz_mshop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL,
  `good_id` varchar(255) NOT NULL COMMENT '取消推广商品id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微店';

-- ----------------------------
-- Records of zz_mshop
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_m_user`
-- ----------------------------
DROP TABLE IF EXISTS `zz_m_user`;
CREATE TABLE `zz_m_user` (
  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(64) NOT NULL,
  `lastlogin` int(11) NOT NULL,
  `salt` char(6) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT '1',
  `ip` varchar(15) NOT NULL,
  `u_time` int(11) NOT NULL,
  `c_time` int(11) NOT NULL,
  `desc` text NOT NULL,
  `visitor` tinyint(2) NOT NULL COMMENT '1 访客',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='管理员';

-- ----------------------------
-- Records of zz_m_user
-- ----------------------------
INSERT INTO `zz_m_user` VALUES ('24', 'admin', '0b1ebbc6a34c0d3e20b42d6159f69a85', '1501729145', '96856d', '-1', '', '1501729145', '1501729145', '', '0');

-- ----------------------------
-- Table structure for `zz_m_user_group`
-- ----------------------------
DROP TABLE IF EXISTS `zz_m_user_group`;
CREATE TABLE `zz_m_user_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `desc` text NOT NULL,
  `menu_list` varchar(600) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '权限菜单',
  `listorder` smallint(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='权限分组';

-- ----------------------------
-- Records of zz_m_user_group
-- ----------------------------
INSERT INTO `zz_m_user_group` VALUES ('1', '系统管理员', '整站管理，拥有最高权限', '1,133,134,135,2,22,76,18,15,123,158,204,230,231,181,243,132,156,125,128,126,129,127,130,220,221,97,98,215,253,136,189,195,153,201,252,161,162,138,222,223,224,229,246,209,106,107,202,203,205,206,207,208,95,96,99,214,174,196,145,182,147,149,170,116,124,171,172,173,194,175,176,177,185,178,179,180,3,31,32,71,65,66,157,159,160,212,213,218,219,27,28,29,30,183,184,186,187,188,190,191,192,193,247,248,244,225,89,72,73,250,251,77,78,79,80,74,75,47,48,87,88,249,17,81,164,165,90,91,92,93,232,233,234,235,236,237,238,239,240,241,242', '1');

-- ----------------------------
-- Table structure for `zz_m_user_log`
-- ----------------------------
DROP TABLE IF EXISTS `zz_m_user_log`;
CREATE TABLE `zz_m_user_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作时间',
  `user_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `log_info` varchar(255) NOT NULL DEFAULT '' COMMENT '操作事项',
  `ip_address` varchar(15) NOT NULL DEFAULT '' COMMENT 'IP地址',
  PRIMARY KEY (`log_id`),
  KEY `log_time` (`log_time`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8 COMMENT='管理员操作记录';

-- ----------------------------
-- Records of zz_m_user_log
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_nation`
-- ----------------------------
DROP TABLE IF EXISTS `zz_nation`;
CREATE TABLE `zz_nation` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `catname` varchar(255) NOT NULL COMMENT '分类名称',
  `parentid` int(10) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `arrparentid` text NOT NULL COMMENT '父类ID组',
  `arrchildid` text NOT NULL COMMENT '子类ID组',
  `child` tinyint(1) NOT NULL COMMENT '是否有子级',
  `keywords` varchar(120) NOT NULL COMMENT '页面关键字',
  `description` int(255) NOT NULL COMMENT '页面描述',
  `listorder` smallint(5) NOT NULL DEFAULT '0' COMMENT '排序',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `url` varchar(255) NOT NULL COMMENT 'URL',
  `ismenu` smallint(3) NOT NULL DEFAULT '0' COMMENT '是否导航',
  `isrec` smallint(3) NOT NULL,
  `desc` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='海淘分类';

-- ----------------------------
-- Records of zz_nation
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_nav`
-- ----------------------------
DROP TABLE IF EXISTS `zz_nav`;
CREATE TABLE `zz_nav` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `userid` int(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(40) NOT NULL DEFAULT '',
  `url` varchar(60) NOT NULL DEFAULT '',
  `listorder` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(11) unsigned NOT NULL DEFAULT '0',
  `lang` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `type` varchar(255) NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL DEFAULT '',
  `title_style` varchar(40) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `linkurl` text NOT NULL,
  `img` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8 COMMENT='前台底部菜单';

-- ----------------------------
-- Records of zz_nav
-- ----------------------------
INSERT INTO `zz_nav` VALUES ('83', '1', '1', 'lnest_admin', '/content/show//83', '0', '1471835648', '1501464155', '1', '1', '免费试用', '', '', '/goods/lottery/5 ', '[{\"path\":\"\\/style\\/img\\/h-ico2.png\",\"title\":\"\",\"iosurl\":\"Free\",\"anurl\":\"\"}]');
INSERT INTO `zz_nav` VALUES ('84', '1', '1', 'lnest_admin', '/content/show//84', '0', '1471835738', '1501464163', '1', '1', '品牌折扣', '', '', '/goods/brand', '[{\"path\":\"\\/style\\/img\\/h-ico7.png\",\"title\":\"h-ico7\",\"iosurl\":\"Brand\",\"anurl\":\"\"}]');
INSERT INTO `zz_nav` VALUES ('85', '1', '1', 'lnest_admin', '/content/show//85', '0', '1471835755', '1501464174', '1', '1', '高佣推广', '', '', '/goods/comms', '[{\"path\":\"\\/style\\/img\\/h-ico5.png\",\"title\":\"h-ico5\",\"iosurl\":\"Commission\",\"anurl\":\"\"}]');
INSERT INTO `zz_nav` VALUES ('86', '1', '1', 'lnest_admin', '/content/show//86', '0', '1471835763', '1501464183', '1', '1', '9.9元包邮', '', '', 'topic/index/3', '[{\"path\":\"\\/style\\/img\\/h-ico6.png\",\"title\":\"h-ico6\",\"iosurl\":\"Pinkage\",\"anurl\":\"\"}]');
INSERT INTO `zz_nav` VALUES ('87', '1', '1', 'lnest_admin', '/content/show//87', '0', '1471835775', '1501464193', '1', '1', '每日抽奖', '', '', '/goods/lottery/6 ', '[{\"path\":\"\\/style\\/img\\/h-ico9.png\",\"title\":\"h-ico9\",\"iosurl\":\"Lotto\",\"anurl\":\"\"}]');
INSERT INTO `zz_nav` VALUES ('88', '1', '1', 'lnest_admin', '/content/show//88', '0', '1471835790', '1501464201', '1', '1', '热销榜', '', '', '/goods/search/?order=sales&sort=DESC', '[{\"path\":\"\\/style\\/img\\/h-ico1.png\",\"title\":\"h-ico1\",\"iosurl\":\"Hot\",\"anurl\":\"\"}]');
INSERT INTO `zz_nav` VALUES ('89', '1', '1', 'lnest_admin', '/content/show//89', '0', '1471835813', '1502962029', '1', '1', '邻居团', '', '', '/goods/index/?typeid=7', '[{\"path\":\"\\/style\\/img\\/h-ico11.png\",\"title\":\"\",\"iosurl\":\"Neighbor\",\"anurl\":\"\"}]');
INSERT INTO `zz_nav` VALUES ('90', '1', '1', 'lnest_admin', '/content/show//90', '0', '1471835826', '1484903885', '1', '1', '推广团', '', '', '/goods/index/?typeid=8', '[{\"path\":\"\\/style\\/img\\/h-ico10.png\",\"title\":\"h-ico10\"}]');
INSERT INTO `zz_nav` VALUES ('91', '0', '1', 'lnest_admin', '/content/show//91', '0', '1484809622', '1496987319', '1', '1', '店铺街', '', '', '/store/lists', '[{\"path\":\"\\/style\\/img\\/h-ico4.png\",\"title\":\"h-ico4\",\"iosurl\":\"\",\"anurl\":\"\"}]');
INSERT INTO `zz_nav` VALUES ('92', '1', '1', 'lnest_admin', '/content/show//92', '0', '1484809661', '1501464231', '1', '1', '新品热销', '', '', '/goods/news', '[{\"path\":\"\\/style\\/img\\/h-ico3.png\",\"title\":\"h-ico3\",\"iosurl\":\"New\",\"anurl\":\"\"}]');
INSERT INTO `zz_nav` VALUES ('93', '1', '1', 'lnest_admin', '/content/show//93', '0', '1489977664', '1489978828', '1', '2', '首页', '', '', '/', '[{\"path\":\"\\/style\\/img\\/h-ico1.png\",\"title\":\"\"}]');
INSERT INTO `zz_nav` VALUES ('94', '1', '1', 'lnest_admin', '/content/show//94', '0', '1495693530', '1495693576', '1', '3', '限时秒杀', '', '', '', '[{\"path\":\"\\/style\\/img\\/h-ico2.png\",\"title\":\"\"}]');
INSERT INTO `zz_nav` VALUES ('95', '1', '1', 'lnest_admin', '/content/show//95', '0', '1495693593', '1495693653', '1', '3', '品牌折扣', '', '', '', '[{\"path\":\"\\/style\\/img\\/h-ico3.png\",\"title\":\"\"}]');
INSERT INTO `zz_nav` VALUES ('96', '1', '1', 'lnest_admin', '/content/show//96', '0', '1495693620', '0', '1', '3', '爱逛街', '', '', '', '[{\"path\":\"\\/style\\/img\\/h-ico4.png\",\"title\":\"\"}]');
INSERT INTO `zz_nav` VALUES ('97', '1', '1', 'lnest_admin', '/content/show//97', '0', '1495693665', '1495693688', '1', '3', '母婴童装', '', '', '', '[{\"path\":\"\\/style\\/img\\/h-ico6.png\",\"title\":\"519_src\"}]');
INSERT INTO `zz_nav` VALUES ('98', '1', '1', 'lnest_admin', '/content/show//98', '0', '1495693695', '0', '1', '3', '美食汇', '', '', '', '[{\"path\":\"\\/style\\/img\\/h-ico2.png\",\"title\":\"519_src\"}]');
INSERT INTO `zz_nav` VALUES ('99', '1', '2', 'seanadmin', '/content/show//99', '0', '1500686074', '1502962017', '1', '1', '同城新零售', '', '', '/city/index', '[{\"path\":\"\\/style\\/img\\/h-ico12.png\",\"title\":\"\",\"iosurl\":\"City\",\"anurl\":\"\"}]');
INSERT INTO `zz_nav` VALUES ('100', '1', '24', 'admin', '/content/show//100', '0', '1505814527', '0', '1', '4', '首页', '', '', '/', '[{\"path\":\"\\/style\\/img\\/f-ico1.png\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"},{\"path\":\"\\/style\\/img\\/f-ico1-1.png\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"}]');
INSERT INTO `zz_nav` VALUES ('101', '1', '24', 'admin', '/content/show//101', '0', '1505814646', '1505814729', '1', '4', '新品', '', '', '/goods/news', '[{\"path\":\"\\/style\\/img\\/f-ico2.png\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"},{\"path\":\"\\/style\\/img\\/f-ico2-2.png\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"}]');
INSERT INTO `zz_nav` VALUES ('102', '1', '24', 'admin', '/content/show//102', '0', '1505814735', '1505815054', '1', '4', '全球购', '', '', '/goods/nation', '[{\"path\":\"\\/style\\/img\\/f-ico3.png\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"},{\"path\":\"\\/style\\/img\\/f-ico3-2.png\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"}]');
INSERT INTO `zz_nav` VALUES ('103', '1', '24', 'admin', '/content/show//103', '0', '1505814786', '1505815049', '1', '4', '搜索', '', '', '/goods/cat', '[{\"path\":\"\\/style\\/img\\/f-ico4.png\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"},{\"path\":\"\\/style\\/img\\/f-ico4-2.png\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"}]');
INSERT INTO `zz_nav` VALUES ('104', '1', '24', 'admin', '/content/show//104', '0', '1505814952', '1505818112', '1', '4', '我的', '', '', '/member', '[{\"path\":\"\\/style\\/img\\/f-ico5.png\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"},{\"path\":\"\\/style\\/img\\/f-ico5-2.png\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"}]');

-- ----------------------------
-- Table structure for `zz_oauth`
-- ----------------------------
DROP TABLE IF EXISTS `zz_oauth`;
CREATE TABLE `zz_oauth` (
  `mid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'oauth类型 0：微信、1：qq、2：微博、3微信:unionid',
  `openid` char(32) NOT NULL DEFAULT '',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0 无效 1有效',
  PRIMARY KEY (`mid`,`type`),
  UNIQUE KEY `openid` (`openid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员授权表';

-- ----------------------------
-- Records of zz_oauth
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_page`
-- ----------------------------
DROP TABLE IF EXISTS `zz_page`;
CREATE TABLE `zz_page` (
  `id` int(11) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `userid` int(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(40) NOT NULL DEFAULT '',
  `url` varchar(60) NOT NULL DEFAULT '',
  `listorder` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(11) unsigned NOT NULL DEFAULT '0',
  `lang` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `title_style` varchar(40) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='单页模型';

-- ----------------------------
-- Records of zz_page
-- ----------------------------
INSERT INTO `zz_page` VALUES ('23', '1', '1', 'lnest_admin', '/content/show//23', '0', '1476165309', '1502963490', '1', '联系我们', '', '', '', '442');
INSERT INTO `zz_page` VALUES ('28', '1', '2', 'admin', '/content/show//28', '28', '1498459581', '1502963519', '1', '商家入驻', '', '', '<h2 class=\"title-01\">\r\n	为什么选择我们<span>？</span> \r\n</h2>\r\n<ul class=\"clearfix plyp-box\">\r\n	<li>\r\n		<img src=\"/default/img/p1.jpg\" /> \r\n		<p>\r\n			免费入驻\r\n		</p>\r\n		<div class=\"pl-abs\">\r\n			<h2>\r\n				免费入驻\r\n			</h2>\r\n			<p>\r\n				入驻港湾有巢,不需要收取任何费用,只需要缴纳对应品类的保证金。保证金将汇入您的店铺账户，开店结束时予以退还。\r\n			</p>\r\n		</div>\r\n	</li>\r\n	<li>\r\n		<img src=\"/default/img/p2.jpg\" /> \r\n		<p>\r\n			功能丰富\r\n		</p>\r\n		<div class=\"pl-abs\">\r\n			<h2>\r\n				<span>功能丰富</span> \r\n			</h2>\r\n			<p>\r\n				港湾有巢，除了一般的拼团功能，还有抽奖团、秒杀团、阶梯团、品牌团、试用团、新专团、海淘团、领居团、推广团、自选团、分销团等新鲜玩法\r\n			</p>\r\n		</div>\r\n	</li>\r\n	<li>\r\n		<img src=\"/default/img/p3.jpg\" /> \r\n		<p>\r\n			流程简单\r\n		</p>\r\n		<div class=\"pl-abs\">\r\n			<h2>\r\n				流程简单\r\n			</h2>\r\n			<p>\r\n				流程简单 不收取任何佣金与服务费，入驻简单快捷；拥有成熟的平台运营体系，基于微信社交生态圈的拼团模式，定期进行高转化率的优惠活动，拥有众多品牌官方授权及高品质货品保证。\r\n			</p>\r\n		</div>\r\n	</li>\r\n	<li>\r\n		<img src=\"/default/img/p4.jpg\" /> \r\n		<p>\r\n			形式新颖\r\n		</p>\r\n		<div class=\"pl-abs\">\r\n			<h2>\r\n				形式新颖\r\n			</h2>\r\n			<p>\r\n				与其他电商搜索式购物完全不同港湾有巢充分利用社交工具微信，以拼团模式抓住社交的红利；在购物行为中融入游戏的趣味，在拼团过程中获得分享与沟通的社交乐趣，享受全新的共享式购物体验。\r\n			</p>\r\n			<div>\r\n				<br />\r\n			</div>\r\n		</div>\r\n	</li>\r\n</ul>', '2260');
INSERT INTO `zz_page` VALUES ('29', '1', '2', 'admin', '', '40', '1498457985', '1498457985', '1', 'APP下载', '', '', '', '1009');
INSERT INTO `zz_page` VALUES ('31', '1', '2', 'admin', '', '0', '1498459908', '1498459908', '1', '入驻标准', '', '', '', '177');
INSERT INTO `zz_page` VALUES ('32', '1', '2', 'admin', '/content/show//32', '0', '1498460012', '1498460021', '1', '加入我们', '', '', '', '80');

-- ----------------------------
-- Table structure for `zz_payment`
-- ----------------------------
DROP TABLE IF EXISTS `zz_payment`;
CREATE TABLE `zz_payment` (
  `pay_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `pay_code` varchar(20) NOT NULL DEFAULT '',
  `pay_name` varchar(120) NOT NULL DEFAULT '',
  `pay_fee` varchar(10) NOT NULL DEFAULT '0',
  `pay_desc` text NOT NULL,
  `pay_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pay_config` text NOT NULL,
  `enabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_cod` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_online` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`pay_id`),
  UNIQUE KEY `pay_code` (`pay_code`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8 COMMENT='支付配置';

-- ----------------------------
-- Records of zz_payment
-- ----------------------------
INSERT INTO `zz_payment` VALUES ('42', 'wxpay', '微信端支付', '0', '微信支付', '1', 'a:6:{i:0;a:3:{s:4:\"name\";s:12:\"wxpay_app_id\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:18:\"wx769f14514bb64a5a\";}i:1;a:3:{s:4:\"name\";s:16:\"wxpay_app_secret\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:32:\"23fb245f292d42093afd5581c3a8a0b2\";}i:2;a:3:{s:4:\"name\";s:11:\"wxpay_mchid\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:10:\"1485529512\";}i:3;a:3:{s:4:\"name\";s:9:\"wxpay_key\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:32:\"ebciLYhr0ATevXGi6Fw6h9MLLEYcz1s1\";}i:4;a:3:{s:4:\"name\";s:8:\"cert_pem\";s:4:\"type\";s:4:\"file\";s:5:\"value\";s:80:\"/home/wwwroot/pingengduo.com/webapps/www/data/60/cacert/wxpay/apiclient_cert.pem\";}i:5;a:3:{s:4:\"name\";s:7:\"key_pem\";s:4:\"type\";s:4:\"file\";s:5:\"value\";s:79:\"/home/wwwroot/pingengduo.com/webapps/www/data/60/cacert/wxpay/apiclient_key.pem\";}}', '0', '0', '1', '[{\"path\":\"\\/upload\\/60\\/images\\/gallery\\/3\\/7\\/14372_src.png\",\"title\":\"16_src\"}]');
INSERT INTO `zz_payment` VALUES ('60', 'alipay', '支付宝', '0', '国内先进的网上支付平台。三种支付接口：担保交易，即时到账，双接口。在线即可开通，零预付，免年费，单笔阶梯费率，无流量限制。', '0', 'a:4:{i:0;a:3:{s:4:\"name\";s:14:\"alipay_account\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:17:\"3556179684@qq.com\";}i:1;a:3:{s:4:\"name\";s:10:\"alipay_key\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:32:\"2lvd0ialbexnwhwchf5dfocaol4rv7fa\";}i:2;a:3:{s:4:\"name\";s:14:\"alipay_partner\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:16:\"2088621236338833\";}i:3;a:3:{s:4:\"name\";s:17:\"alipay_pay_method\";s:4:\"type\";s:6:\"select\";s:5:\"value\";s:1:\"2\";}}', '0', '0', '1', '');
INSERT INTO `zz_payment` VALUES ('61', 'wxpayapp', '微信APP支付', '0', 'APP支付接口 <a target=\"_blank\" href=\"https://open.weixin.qq.com/cgi-bin/frame?t=home/app_tmpl&lang=zh_CN\">立即申请</a>', '0', 'a:6:{i:0;a:3:{s:4:\"name\";s:12:\"wxpay_app_id\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}i:1;a:3:{s:4:\"name\";s:16:\"wxpay_app_secret\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}i:2;a:3:{s:4:\"name\";s:11:\"wxpay_mchid\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}i:3;a:3:{s:4:\"name\";s:9:\"wxpay_key\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:0:\"\";}i:4;a:3:{s:4:\"name\";s:8:\"cert_pem\";s:4:\"type\";s:4:\"file\";s:5:\"value\";s:66:\"F:/lnest_tcg/webapps/www/data/1/cacert/wxpayapp/apiclient_cert.pem\";}i:5;a:3:{s:4:\"name\";s:7:\"key_pem\";s:4:\"type\";s:4:\"file\";s:5:\"value\";s:65:\"F:/lnest_tcg/webapps/www/data/1/cacert/wxpayapp/apiclient_key.pem\";}}', '0', '1', '1', '');
INSERT INTO `zz_payment` VALUES ('62', 'alipayapp', '支付宝APP', '0', '支付宝APP', '0', 'a:4:{i:0;a:3:{s:4:\"name\";s:14:\"alipay_account\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:17:\"3556179684@qq.com\";}i:1;a:3:{s:4:\"name\";s:11:\"private_key\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:1612:\"MIIEogIBAAKCAQEA0gJ8CEnAh+MoNPTEMIc77mFnnLFbz69XPJ19NsVUgbl3qsMT fkXPjbPQbMoN/G3dHkviwL0l0EkMVoRcBA9dzAa4ZawBOvzejb7f2axODAMZxdGJ THaQly2UEXP5ej6raItllzZcevXlyyvv1O/tR/xx/vOBjuBpAWsFiwfX2hF6Xqxl U9jmNOPqmPciuvm7wOF69i7eiw0auawt7Ao2dNx0n5Fu6NfI4/sF1CU0vw3dmy86 hjMguBgu8EAhkdc+c9RkiulqjngV+irODlgjPb934R3dy1SP8SXR/2q7KRs0ntQo uQerClTYKJbInrMafLl4S+GFanGoo43uMZvK5wIDAQABAoIBAEGH6PAlI2DDry4n GnkSGc9esZghd8asrgoKEqJenOoF16N/T7vdSNCsxWLNAeQ82/yyLUV7QCtoP+NX VecmbFxX1H5VV+TyNvgAEZW4wHnbFdW2dqNXc/LYlBWGHNjaaV4r8T1oBx+PT29A fIvzehU2XtPxZAITYHDC1m31upMexELt+MpeLu3jk6dRqHgzNWSGGEUcMchfz1zu d49Sx8Y2d4PJzYDCL/buY/hrnOJicD40JzVoTawk7bsR13SmzTY9TgptAmhqro1n 2HJJ1+DkhUj2FMWr/5CLdyhIFIXww7j+ZV7L+YlU02BTiy38JyPhHe9Z8awv0Ie1 D5zdKGECgYEA8Ojb0hK6oD0GA+Wt2IGZhFlf68fR6sSMzMyEU4sYVkWWTHE2zkc9 JLuyUI3AGXSOe/IYnTYzf+CH38aVy2nNqNKxnWTHGQFKSwXw2hm6Ibri6wrtllCa dF92k0vz/u47NPggEaYmGZPOwGEfU0krEU74a5brZ40HfbkNbIX8Ug8CgYEA3yog PtOSE6TDhKuoodqw8JQz9fEK3vARMeasXbLcTi7yD+rWsWyBUfKtEaAIDdJBQfS+ hI6Fb5BzuAqZp9HiDaRowH4Zi5f7KBB2d+wTedQGgmciQxN3UcRFUYgihuBmwA+h dVJoSVDjnvziJVKir5JyyY165fs9ZKGs57IfcakCgYACP+ptl8cuX+OCfc6VywAI AF2o8gC/1H4MG4zQyue7RoMUy6nbW0by5N3RasF0e2YpOiWIskzg0NPz/wK/F4nY Hb+S3LIPRbfP44pQPxVB42DjGQMG+FG47HtK/NaWdtsOr5J9F7QbYQn5Oe709CX2 z2n6dzpTiTmcrvG1JbspeQKBgEDLJlmXbA4wBGR8wpVbvYvcO8nollNsOFHYu6ro x3Ybw0RD1tnkuxdj8kPvxubcaP4RZU9vYeamdjK1QS+sjImRdqiM9DXHhrB3Ny5S aMUyfkFWWGhJOpeO+OFXOU8X0D1rTGGX75d52NzMZ4yWlY2MY6+JWGl1rLmPB3iu CtshAoGAfH4TnUdjjR871i3jge7gLAjBXuWxXPyZtFzK74Vuxg7A5ufiQZb1JDrq rnMfmtmvhdjjMVSfTmrvznNcOmMxZ7LVBpUeAgU6mzzvrZD3t223J3tDxAmQgYAC lHDFv7EhYjhNbZAK/6ZRBmnE53Qq3NtPhVpAHs6d/cJvF6LfTOQ=\";}i:2;a:3:{s:4:\"name\";s:10:\"public_key\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:392:\"MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjAZGiWH1SXRbwlSz/A1JAToppWOci2Rw3nvPzx0d9Nw+cZKv5pqLawmHitnPVdr9YqYCMBAb7QqvoJMRdTbByLjhWO+Y8w2ZOWF8S9RtUDoSVCdr99dM2VvaQJaZXG1gSy7u5F2MUyqTmvS3ajLVCLm4vLE+C7aYB/27wSw6eiipxrGQqFWeeUWj9H4KTB7HbTe77HNxY4IXbWXdJG9nt/z0tHp0pGEZxjDzXAfjDWg0yRNmReY9FCL+jgDgDT6IeQSxuEYu7BuwwxJYrkpRvSQBM3HrELHcShpXNLadG29WLYK5SfWeTP6xpFlbrzq158y2GhyFDB28MuUh13Q8DQIDAQAB\";}i:3;a:3:{s:4:\"name\";s:14:\"alipay_partner\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:16:\"2017071407751624\";}}', '0', '1', '1', '[{\"path\":\"\\/upload\\/images\\/gallery\\/f\\/1\\/542_src.png\",\"title\":\"103_src\"}]');
INSERT INTO `zz_payment` VALUES ('63', 'alipay_wap', '手机支付宝', '0', '手机支付宝在线支付', '0', 'a:6:{i:0;a:3:{s:4:\"name\";s:13:\"alipay_remark\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:39:\"港湾有巢\";}i:1;a:3:{s:4:\"name\";s:14:\"alipay_account\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:17:\"3556179684@qq.com\";}i:2;a:3:{s:4:\"name\";s:10:\"alipay_key\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:32:\"2lvd0ialbexnwhwchf5dfocaol4rv7fa\";}i:3;a:3:{s:4:\"name\";s:14:\"alipay_partner\";s:4:\"type\";s:4:\"text\";s:5:\"value\";s:16:\"2088621236338833\";}i:4;a:3:{s:4:\"name\";s:17:\"alipay_pay_method\";s:4:\"type\";s:6:\"select\";s:5:\"value\";s:1:\"0\";}i:5;a:3:{s:4:\"name\";s:13:\"alipay_cacert\";s:4:\"type\";s:4:\"file\";s:5:\"value\";s:0:\"\";}}', '0', '0', '1', '[{\"path\":\"\\/upload\\/60\\/images\\/gallery\\/3\\/8\\/14373_src.png\",\"title\":\"17_src\"}]');

-- ----------------------------
-- Table structure for `zz_pay_log`
-- ----------------------------
DROP TABLE IF EXISTS `zz_pay_log`;
CREATE TABLE `zz_pay_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID',
  `order_amount` decimal(10,2) unsigned NOT NULL COMMENT '订单金额',
  `order_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '支付类型:0订单1预存款2夺宝',
  `is_paid` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否支付',
  `out_trade_no` varchar(32) NOT NULL COMMENT '支付单号',
  `trade_no` varchar(64) NOT NULL COMMENT '支付宝交易号 用于退款',
  PRIMARY KEY (`log_id`),
  KEY `pay_orderid` (`order_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='支付记录';

-- ----------------------------
-- Records of zz_pay_log
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_posid`
-- ----------------------------
DROP TABLE IF EXISTS `zz_posid`;
CREATE TABLE `zz_posid` (
  `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '',
  `listorder` tinyint(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_posid
-- ----------------------------
INSERT INTO `zz_posid` VALUES ('1', '首页推荐', '0');
INSERT INTO `zz_posid` VALUES ('3', '内页推荐', '0');

-- ----------------------------
-- Table structure for `zz_refund`
-- ----------------------------
DROP TABLE IF EXISTS `zz_refund`;
CREATE TABLE `zz_refund` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL,
  `status` tinyint(2) NOT NULL COMMENT '状态 0:申请中，1确认待发货，2拒绝,10通过',
  `status_refund` tinyint(2) NOT NULL COMMENT '平台确认 0未确认，10已确认',
  `status_shipping` tinyint(2) NOT NULL COMMENT '确认收货 0未收货 1已发货 10已收货',
  `type` tinyint(2) NOT NULL COMMENT '类型：1退款，2退货',
  `order_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID,唯一索引',
  `refund_amount` decimal(10,2) unsigned NOT NULL COMMENT '退款金额',
  `reason_id` tinyint(2) NOT NULL COMMENT '退款，退货原因',
  `pic` varchar(500) NOT NULL COMMENT '退款凭证图片',
  `note` varchar(255) NOT NULL COMMENT '退款/货 理由',
  `c_time` int(11) NOT NULL COMMENT '申请时间',
  `u_time` int(11) NOT NULL COMMENT '平台确认时间',
  `express` varchar(60) NOT NULL COMMENT '快递公司',
  `express_num` varchar(60) NOT NULL COMMENT '运单号',
  `address_id` int(8) NOT NULL COMMENT '收货id',
  `mark` varchar(255) NOT NULL COMMENT '商家备注',
  `admin_mark` varchar(255) NOT NULL COMMENT '平台备注',
  `sid` int(11) NOT NULL COMMENT '商家id',
  `complain_status` tinyint(2) NOT NULL COMMENT '交易投诉状态 1申请中，2商家的错 退款，3买家的错',
  `complain_content` varchar(255) NOT NULL COMMENT '交易投诉内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_refund
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_refund_log`
-- ----------------------------
DROP TABLE IF EXISTS `zz_refund_log`;
CREATE TABLE `zz_refund_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `payment` varchar(20) NOT NULL COMMENT '退款类型：微信wxpay，支付宝alipay',
  `order_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '订单ID,唯一索引',
  `order_amount` decimal(10,2) unsigned NOT NULL COMMENT '订单金额',
  `out_trade_no` varchar(32) NOT NULL COMMENT '支付订单号',
  `trade_no` varchar(64) NOT NULL COMMENT '支付宝交易号',
  `out_refund_no` varchar(32) NOT NULL COMMENT '退款订单号',
  `is_refund` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否退款 0 申请中，1是，2否,3处理中',
  `remark` varchar(255) NOT NULL,
  `c_time` int(11) NOT NULL,
  `u_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`,`out_trade_no`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_refund_log
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_score_config`
-- ----------------------------
DROP TABLE IF EXISTS `zz_score_config`;
CREATE TABLE `zz_score_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rule_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '积分规则模版id',
  `config` text NOT NULL COMMENT '规则配置',
  `extend` varchar(255) NOT NULL DEFAULT '' COMMENT '附加规则',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '积分规则开关',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='积分规则配置表';

-- ----------------------------
-- Records of zz_score_config
-- ----------------------------
INSERT INTO `zz_score_config` VALUES ('1', '1', '{\"amount\":\"2\",\"extend\":[]}', '', '0');
INSERT INTO `zz_score_config` VALUES ('2', '4', '{\"percent\":\"4\",\"extend\":\"{\\\"200\\\":200}\"}', '', '1');
INSERT INTO `zz_score_config` VALUES ('3', '3', '{\"extend\":\"[\\\"10\\\",\\\"8\\\"]\"}', '', '0');
INSERT INTO `zz_score_config` VALUES ('5', '2', '{\"amount\":\"10\"}', '', '0');
INSERT INTO `zz_score_config` VALUES ('6', '5', '{\"percent\":\"100\"}', '', '0');

-- ----------------------------
-- Table structure for `zz_score_log`
-- ----------------------------
DROP TABLE IF EXISTS `zz_score_log`;
CREATE TABLE `zz_score_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `type` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '积分类型',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '积分数量',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
  `c_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `index_score_mid` (`mid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1511 DEFAULT CHARSET=utf8 COMMENT='积分表';


-- ----------------------------
-- Table structure for `zz_score_rule`
-- ----------------------------
DROP TABLE IF EXISTS `zz_score_rule`;
CREATE TABLE `zz_score_rule` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '规则类型',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '规则标题',
  `description` text NOT NULL COMMENT '规则描述',
  `config` text NOT NULL COMMENT '积分配置',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '积分规则开关',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='积分规则模版表';

-- ----------------------------
-- Records of zz_score_rule
-- ----------------------------
INSERT INTO `zz_score_rule` VALUES ('1', '1', '签到积分', '设置每日签到奖励开关及对应分值,并可以扩展规则 例如连续签到N天送N积分,达到M天再送M积分', '{\"amount\":10,\"extend\":[]}', '1');
INSERT INTO `zz_score_rule` VALUES ('2', '2', '注册积分', '当用户首次注册为会员的时候赠送积分', '{\"amount\":10}', '1');
INSERT INTO `zz_score_rule` VALUES ('3', '3', '分享积分', '当有人通过分享链接或分销名片成为分享者下级时，将给分享者及其上级发放积分', '{\"extend\":\"[10,10,10,10,10,10,10]\"}', '1');
INSERT INTO `zz_score_rule` VALUES ('4', '4', '充值积分', '用户冲值时,根据充值金额(单位元)按照设置的比例赠送积分,并可以设置满多少额外送多少积分', '{\"percent\":0,\"extend\":\"{\\\"200\\\":200}\"}', '0');
INSERT INTO `zz_score_rule` VALUES ('5', '5', '购物积分', '用户购物时,根据购物金额(单位元)按照设置的比例赠送积分', '{\"percent\":100}', '1');

-- ----------------------------
-- Table structure for `zz_score_total`
-- ----------------------------
DROP TABLE IF EXISTS `zz_score_total`;
CREATE TABLE `zz_score_total` (
  `mid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `total_left` int(11) NOT NULL DEFAULT '0' COMMENT '当前剩余总积分',
  `total_amount` int(11) NOT NULL DEFAULT '0' COMMENT '历史获得积分的总数',
  `total_cost` int(11) NOT NULL DEFAULT '0' COMMENT '历史消耗积分总数',
  `last_sign` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上一次签到的时间戳',
  `continue_sign` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '连续签到天数',
  `score_0` int(11) NOT NULL DEFAULT '0' COMMENT '其他类型积分统计. 不传type默认统计到这里',
  `score_1` int(11) NOT NULL DEFAULT '0' COMMENT '签到积分总值',
  `score_2` int(11) NOT NULL DEFAULT '0' COMMENT '注册积分总值',
  `score_3` int(11) NOT NULL DEFAULT '0' COMMENT '分享积分总值',
  `score_4` int(11) NOT NULL DEFAULT '0' COMMENT '充值积分总值',
  `score_5` int(11) NOT NULL DEFAULT '0' COMMENT '购物积分总值',
  `u_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上次编辑时间',
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='积分统计表';

-- ----------------------------
-- Table structure for `zz_send`
-- ----------------------------
DROP TABLE IF EXISTS `zz_send`;
CREATE TABLE `zz_send` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '会员ID',
  `content` text NOT NULL,
  `type` varchar(20) NOT NULL COMMENT '队列类型：短信 邮件',
  `template_code` varchar(30) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1发送完成',
  `add_time` int(11) NOT NULL,
  `send_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='有可能是找回密码使用的';

-- ----------------------------
-- Records of zz_send
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_share`
-- ----------------------------
DROP TABLE IF EXISTS `zz_share`;
CREATE TABLE `zz_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `thumbs` text NOT NULL COMMENT '多图',
  `order_id` int(11) NOT NULL COMMENT '订单ID',
  `extension_code` smallint(3) DEFAULT NULL,
  `obj_name` varchar(60) DEFAULT NULL COMMENT '晒单对象名称',
  `obj_id` int(11) DEFAULT NULL COMMENT '晒单对象ID',
  `qishu` smallint(6) NOT NULL COMMENT '期数',
  `comment` int(6) NOT NULL DEFAULT '0' COMMENT '评论',
  `ding` int(6) NOT NULL DEFAULT '0' COMMENT '顶',
  `luck_code` char(8) DEFAULT NULL COMMENT '中奖号',
  `mid` int(11) NOT NULL COMMENT '用户ID',
  `username` varchar(60) NOT NULL COMMENT '用户名',
  `db_points` int(6) NOT NULL DEFAULT '0' COMMENT '奖励夺宝币',
  `is_rec` smallint(3) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `addtime` int(10) NOT NULL,
  `is_show` tinyint(1) DEFAULT '1',
  `listorder` mediumint(8) DEFAULT '0',
  `goods_id` int(11) NOT NULL DEFAULT '0',
  `is_points` tinyint(1) DEFAULT '0' COMMENT '是否审核过',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='晒单';

-- ----------------------------
-- Records of zz_share
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_sms`
-- ----------------------------
DROP TABLE IF EXISTS `zz_sms`;
CREATE TABLE `zz_sms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(20) DEFAULT NULL,
  `content` text,
  `send_time` int(11) DEFAULT NULL,
  `tpl` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='短信记录表';

-- ----------------------------
-- Records of zz_sms
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_special`
-- ----------------------------
DROP TABLE IF EXISTS `zz_special`;
CREATE TABLE `zz_special` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL COMMENT '标题',
  `goods_id` text NOT NULL COMMENT '商品id字符串',
  `special_model_id` int(11) NOT NULL COMMENT '模板id',
  `config_value` text NOT NULL COMMENT '配置',
  `status` tinyint(1) NOT NULL COMMENT '状态（0：正常，1：禁用）',
  `sid` int(11) NOT NULL DEFAULT '0' COMMENT '商家id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_special
-- ----------------------------
INSERT INTO `zz_special` VALUES ('1', '测试', '492,491', '1', '{\"ban\":{\"path\":[\"\\/upload\\/1\\/images\\/gallery\\/p\\/r\\/928_src.png\"],\"title\":[\"zt01top\"]},\"tit\":{\"path\":[\"\\/upload\\/1\\/images\\/gallery\\/p\\/q\\/927_src.png\"],\"title\":[\"zt01-tit\"]}}', '0', '0');

-- ----------------------------
-- Table structure for `zz_special_model`
-- ----------------------------
DROP TABLE IF EXISTS `zz_special_model`;
CREATE TABLE `zz_special_model` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `file_name` varchar(35) NOT NULL COMMENT '文件名称',
  `sid` int(11) NOT NULL DEFAULT '0' COMMENT '商家id',
  `config` text NOT NULL COMMENT '配置',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_special_model
-- ----------------------------
INSERT INTO `zz_special_model` VALUES ('1', 'special.html', '0', '{\"ban\":{\"label\":\"\\u9876\\u90e8banner\",\"type\":\"image\",\"tip\":\"\\u89c4\\u683c\\uff1a750*429 \\u50cf\\u7d20\"},\"tit\":{\"label\":\"\\u9876\\u90e8tit\",\"type\":\"image\",\"tip\":\"\\u89c4\\u683c\\uff1a750*125 \\u50cf\\u7d20\"}}');

-- ----------------------------
-- Table structure for `zz_spread`
-- ----------------------------
DROP TABLE IF EXISTS `zz_spread`;
CREATE TABLE `zz_spread` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `url` varchar(200) NOT NULL DEFAULT '',
  `iosurl` varchar(200) NOT NULL COMMENT '苹果链接',
  `anurl` varchar(200) NOT NULL COMMENT '安卓链接',
  `listorder` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(11) unsigned NOT NULL DEFAULT '0',
  `typeid` tinyint(3) unsigned NOT NULL COMMENT '显示位置',
  `gid` text NOT NULL,
  `name` varchar(300) NOT NULL COMMENT '标题',
  `desc` varchar(300) NOT NULL COMMENT '副标题',
  `thumb` varchar(400) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_spread
-- ----------------------------
INSERT INTO `zz_spread` VALUES ('1', '1', '/goods/kill', '', '', '5', '1496902093', '1504838482', '1', '', '限量秒杀', '每天10点，限量秒杀', '[{\"path\":\"\\/style\\/img\\/spread0.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"}]');
INSERT INTO `zz_spread` VALUES ('2', '1', '/goods/nation', '', '', '4', '1496902189', '1503479757', '2', '', '全球购', '进口爆款底价购', '[{\"path\":\"\\/style\\/img\\/spread1.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"}]');
INSERT INTO `zz_spread` VALUES ('3', '1', '/goods/free_discount', '', '', '3', '1496902193', '1503479754', '3', '', '免单开团', '免单开团', '[{\"path\":\"\\/style\\/img\\/spread2.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"}]');
INSERT INTO `zz_spread` VALUES ('4', '1', '/goods/zcgoods', '', '', '2', '1496902196', '1503479750', '4', '', '新品预售', '新品预售', '[{\"path\":\"\\/style\\/img\\/spread3.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"}]');
INSERT INTO `zz_spread` VALUES ('5', '1', '/goods/brand_kill', '', '', '1', '1496902200', '1503479746', '5', '', '底价清仓', '清仓大甩卖', '[{\"path\":\"\\/style\\/img\\/spread4.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"}]');

-- ----------------------------
-- Table structure for `zz_take_address`
-- ----------------------------
DROP TABLE IF EXISTS `zz_take_address`;
CREATE TABLE `zz_take_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL,
  `mobile` varchar(20) NOT NULL COMMENT '手机号',
  `zone` int(11) NOT NULL COMMENT '区域ID',
  `area` varchar(60) NOT NULL COMMENT '区域',
  `address` varchar(60) NOT NULL COMMENT '详细地址',
  `zip` varchar(10) NOT NULL COMMENT '邮编',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0正常，1禁用',
  PRIMARY KEY (`id`),
  KEY `take_sid` (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='自提地址表';

-- ----------------------------
-- Records of zz_take_address
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_take_user`
-- ----------------------------
DROP TABLE IF EXISTS `zz_take_user`;
CREATE TABLE `zz_take_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL COMMENT '核销人员MID',
  `take_id` int(11) NOT NULL COMMENT '自提点',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 1:正常，0:禁用',
  `sid` int(11) NOT NULL COMMENT '所属商家',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_take_user
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_take_verify_code`
-- ----------------------------
DROP TABLE IF EXISTS `zz_take_verify_code`;
CREATE TABLE `zz_take_verify_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL COMMENT '会员id',
  `order_id` int(11) NOT NULL COMMENT '会员id',
  `verify_code` varchar(13) NOT NULL DEFAULT '' COMMENT '提取验证码',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态：0未验证，1已验证',
  PRIMARY KEY (`id`),
  UNIQUE KEY `verify_code` (`verify_code`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_take_verify_code
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_templates`
-- ----------------------------
DROP TABLE IF EXISTS `zz_templates`;
CREATE TABLE `zz_templates` (
  `template_id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `template_code` varchar(30) NOT NULL DEFAULT '',
  `template_subject` varchar(200) NOT NULL DEFAULT '',
  `template_content` text NOT NULL,
  `last_modify` int(10) unsigned NOT NULL DEFAULT '0',
  `last_send` int(10) unsigned NOT NULL DEFAULT '0',
  `send_number` int(10) DEFAULT '0' COMMENT '发送次数',
  `type` varchar(10) NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `is_system` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1系统模板，不可删除',
  PRIMARY KEY (`template_id`),
  UNIQUE KEY `template_code` (`template_code`) USING BTREE,
  KEY `type` (`type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_templates
-- ----------------------------
INSERT INTO `zz_templates` VALUES ('1', 'send_password', '密码找回', '{$user_name}您好！<br><br>\r\n\r\n您已经进行了密码重置的操作，请点击以下链接(或者复制到您的浏览器):<br>\r\n<a href=\\\"{$reset_email}\\\" target=\\\"_blank\\\">{$reset_email}</a><br><br>\r\n\r\n以确认您的新密码重置操作！<br><br>\r\n\r\n{$shop_name}<br>\r\n{$send_date}', '1415184208', '1425909372', '57', 'mail', '1', '1');
INSERT INTO `zz_templates` VALUES ('8', 'register_validate', '邮件验证', '{$user_name}您好！<br><br>\r\n\r\n这封邮件是 {$shop_name} 发送的。你收到这封邮件是为了验证你注册邮件地址是否有效。如果您已经通过验证了，请忽略这封邮件。<br>\r\n请点击以下链接(或者复制到您的浏览器)来验证你的邮件地址:<br>\r\n<a href=\\\"{$validate_email}\\\" target=\\\"_blank\\\">{$validate_email}</a><br><br>\r\n\r\n{$site_config.site_name}<br>\r\n{$send_date}', '1415189385', '1426501471', '3686', 'mail', '1', '1');
INSERT INTO `zz_templates` VALUES ('24', 'sms_business_getpwd', '商家找回密码', '您的验证码是：{$verify_code}。请不要把验证码泄露给其他人。', '1481854145', '1501650895', '10', 'sms', '1', '1');
INSERT INTO `zz_templates` VALUES ('25', 'sms_business_reg', '商家入驻', '您的验证码是：{$verify_code}。请不要把验证码泄露给其他人。', '1501676778', '1502104673', '414', 'sms', '1', '1');
INSERT INTO `zz_templates` VALUES ('26', 'sms_bankcard', '绑定银行卡验证', '您的验证码是：{$verify_code}。请不要把验证码泄露给其他人。', '1468303715', '1468303742', '27', 'sms', '1', '1');
INSERT INTO `zz_templates` VALUES ('27', 'sms_chpaypass', '修改交易密码验证', '您正在修改交易密码,验证码是：{$verify_code}。若非您本人操作请尽量联系客服。', '1483067993', '1426384247', '64', 'sms', '1', '1');
INSERT INTO `zz_templates` VALUES ('28', 'sms_chpass', '找回密码', '您的验证码是：{$verify_code}。请不要把验证码泄露给其他人。', '1483068390', '1502103701', '13', 'sms', '1', '1');
INSERT INTO `zz_templates` VALUES ('29', 'sms_register', '注册验证码', '您的验证码是：{$verify_code}。请不要把验证码泄露给其他人。', '1501676767', '1502166537', '13430', 'sms', '1', '1');
INSERT INTO `zz_templates` VALUES ('30', 'sms_code', '短信验证码', '您的验证码是：{$verify_code}。请不要把验证码泄露给其他人。', '1501676762', '1502790705', '17', 'sms', '1', '1');
INSERT INTO `zz_templates` VALUES ('31', 'sms_business_succ', '入驻成功', '恭喜您入驻成功，请登陆网站查看，', '1501676752', '1501635945', '228', 'sms', '1', '0');
INSERT INTO `zz_templates` VALUES ('32', 'sms_business_fail', '入驻失败', '您申请入驻资料有误，请登陆网站查看', '1499412804', '1502163328', '141', 'sms', '1', '0');

-- ----------------------------
-- Table structure for `zz_template_msg_config`
-- ----------------------------
DROP TABLE IF EXISTS `zz_template_msg_config`;
CREATE TABLE `zz_template_msg_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rule_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '规则id 等于默认模版的id',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '模版内容',
  `msg` tinyint(4) NOT NULL DEFAULT '0' COMMENT '站内信开关',
  `wechat` tinyint(4) NOT NULL DEFAULT '0' COMMENT '微信开关',
  `mail` tinyint(4) NOT NULL DEFAULT '0' COMMENT '邮件开关',
  `sms` tinyint(4) NOT NULL DEFAULT '0' COMMENT '短信开关',
  `u_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上次编辑时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COMMENT='模板消息配置';

-- ----------------------------
-- Records of zz_template_msg_config
-- ----------------------------
INSERT INTO `zz_template_msg_config` VALUES ('1', '1', '您的会员已下单，单号{插入订单号}，会员昵称{插入昵称}', '0', '0', '1', '0', '1467273936');
INSERT INTO `zz_template_msg_config` VALUES ('2', '2', '您有订单已付款，单号{插入订单号}，会员昵称{插入昵称}', '0', '0', '1', '0', '1467273942');
INSERT INTO `zz_template_msg_config` VALUES ('3', '3', '您有订单已确认收货，单号{插入订单号}，会员昵称{插入昵称}', '1', '0', '1', '0', '1467273917');
INSERT INTO `zz_template_msg_config` VALUES ('4', '4', '您的店铺有一笔订单退款,订单号:{插入订单号},退款人:{插入昵称}', '1', '0', '1', '0', '1467273917');
INSERT INTO `zz_template_msg_config` VALUES ('5', '5', '您的会员已申请成为合伙人或代理，会员昵称{插入昵称}', '1', '0', '1', '1', '1467273917');
INSERT INTO `zz_template_msg_config` VALUES ('6', '6', '您有分销商{插入昵称}申请提现', '1', '0', '1', '1', '1467273917');
INSERT INTO `zz_template_msg_config` VALUES ('7', '24', '您有买家提醒发货,订单号{插入订单号}', '1', '0', '1', '1', '1467273917');
INSERT INTO `zz_template_msg_config` VALUES ('8', '19', '您的第{分销层级}级下级{插入昵称}在{插入店铺}已付款,预计佣金{插入佣金}元，订单号{插入订单号}', '1', '1', '1', '1', '1467273949');
INSERT INTO `zz_template_msg_config` VALUES ('9', '20', '您有一张 {插入店铺}面值为{插入优惠券面值}的优惠券,未使用.不要错过哦', '1', '1', '0', '0', '1467273963');
INSERT INTO `zz_template_msg_config` VALUES ('10', '21', '恭喜您{插入昵称}在店铺{插入店铺}参与的活动{插入活动名称},获得:{插入商品标题}', '1', '1', '0', '0', '1490860004');
INSERT INTO `zz_template_msg_config` VALUES ('11', '22', '您申请的提现已付款，申请金额{插入金额}，申请账号{插入账号}，请查收', '1', '1', '1', '1', '1467273992');
INSERT INTO `zz_template_msg_config` VALUES ('12', '23', '您在{插入店铺}申请的提现被驳回，申请金额{插入金额}，申请账号{插入账号}', '1', '1', '1', '1', '1467273992');
INSERT INTO `zz_template_msg_config` VALUES ('13', '14', '您在{插入店铺}有未付款订单，订单号{插入订单号}请及时付款', '1', '1', '1', '1', '1467274000');
INSERT INTO `zz_template_msg_config` VALUES ('14', '15', '亲爱的会员，您购买的{插入商品标题}已经发货啦~', '1', '1', '0', '0', '1467274000');
INSERT INTO `zz_template_msg_config` VALUES ('15', '16', '您的第{分销层级}级下级{插入昵称}在{插入店铺}有一笔订单申请退款,订单号:{插入订单号}', '1', '1', '1', '1', '1467274000');
INSERT INTO `zz_template_msg_config` VALUES ('16', '17', '您在店铺{插入店铺}的退款申请被通过，订单号{插入订单号}', '1', '1', '0', '0', '1467274000');
INSERT INTO `zz_template_msg_config` VALUES ('17', '18', '您在店铺{插入店铺}的退款申请被拒绝，订单号{插入订单号}，理由:{插入理由}', '1', '1', '0', '0', '1467274000');
INSERT INTO `zz_template_msg_config` VALUES ('18', '11', '{插入昵称}恭喜，您已成为合伙人或代理，现在可以进入会员中心，分销产品啦! ^-^{插入链接}', '1', '1', '1', '1', '1467274003');
INSERT INTO `zz_template_msg_config` VALUES ('19', '12', '抱歉，您在{插入店铺}的申请成为分销商被拒绝，理由:{插入理由}！', '1', '1', '1', '1', '1467274003');
INSERT INTO `zz_template_msg_config` VALUES ('20', '13', '{插入昵称}恭喜，您已成为分销商，现在可以进入会员中心，分销产品啦! ^-^{插入链接}', '1', '1', '1', '1', '1467274003');
INSERT INTO `zz_template_msg_config` VALUES ('21', '7', '恭喜您，成为{插入店铺}的第{插入会员个数}个会员', '0', '0', '0', '0', '1467274007');
INSERT INTO `zz_template_msg_config` VALUES ('22', '8', '恭喜您，由{插入昵称}推荐成为{插入店铺}的第{插入会员个数}个会员', '0', '0', '0', '0', '1467274007');
INSERT INTO `zz_template_msg_config` VALUES ('23', '9', '恭喜您,推荐的{插入昵称}成为{插入店铺}的第{插入会员个数}位会员', '0', '0', '0', '0', '1467274007');
INSERT INTO `zz_template_msg_config` VALUES ('24', '10', '恭喜您,您的第{分销层级}级下级{插入上级昵称}推荐{插入昵称}成为{插入店铺}的第{插入会员个数}位会员！', '1', '1', '1', '1', '1467274007');
INSERT INTO `zz_template_msg_config` VALUES ('25', '25', '{插入昵称}恭喜，您已成为合伙人，现在可以进入会员中心，分销产品啦! ^-^{插入链接}', '1', '0', '0', '0', '1467697624');
INSERT INTO `zz_template_msg_config` VALUES ('26', '26', '抱歉，您在{插入店铺}的申请成为分销商被拒绝，理由:{插入理由}！', '1', '0', '0', '0', '1467697626');
INSERT INTO `zz_template_msg_config` VALUES ('28', '30', '恭喜您拼团成功！我们将尽快为您发货，订单号{插入订单号},商品:{插入商品标题}', '1', '1', '0', '0', '1475990710');
INSERT INTO `zz_template_msg_config` VALUES ('29', '31', '您参加的拼团因人数不足而组团失败，我们将在1-3个工作日内为你安排退款事宜，退款金额:{插入退款金额}', '1', '1', '0', '0', '1475990716');
INSERT INTO `zz_template_msg_config` VALUES ('30', '32', '您的订单退款申请已经提交给微信处理，退款最迟5个工作日内会退到您的支付账号，退款金额:{插入退款金额}', '1', '1', '0', '0', '1475994191');
INSERT INTO `zz_template_msg_config` VALUES ('31', '33', '您有商品拼团成功，请及时发货', '0', '0', '1', '0', '1483586120');
INSERT INTO `zz_template_msg_config` VALUES ('32', '34', '恭喜您拼团成功！请尽快补齐尾款，订单号{插入订单号},商品:{插入商品标题}', '1', '1', '0', '0', '1484722096');
INSERT INTO `zz_template_msg_config` VALUES ('33', '35', '您的交易投诉平台已处理，单号{插入订单号}', '1', '0', '0', '0', '1499923578');

-- ----------------------------
-- Table structure for `zz_template_msg_rule`
-- ----------------------------
DROP TABLE IF EXISTS `zz_template_msg_rule`;
CREATE TABLE `zz_template_msg_rule` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '规则分组',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '模版标题',
  `description` text NOT NULL COMMENT '模版描述',
  `content` varchar(255) NOT NULL COMMENT '默认模版内容',
  `params` varchar(255) NOT NULL DEFAULT '' COMMENT '变量字符串',
  `msg` tinyint(4) NOT NULL DEFAULT '0' COMMENT '站内信开关',
  `wechat` tinyint(4) NOT NULL DEFAULT '0' COMMENT '微信开关',
  `mail` tinyint(4) NOT NULL DEFAULT '0' COMMENT '邮件开关',
  `sms` tinyint(4) NOT NULL DEFAULT '0' COMMENT '短信开关',
  `listorder` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态: -1隐藏 0关闭 1正常',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COMMENT='默认模版消息规则表';

-- ----------------------------
-- Records of zz_template_msg_rule
-- ----------------------------
INSERT INTO `zz_template_msg_rule` VALUES ('1', '0', '会员已下单', '', '您的会员已下单，单号{插入订单号}，会员昵称{插入昵称}', '{插入昵称},{插入订单号}', '1', '0', '0', '0', '0', '1');
INSERT INTO `zz_template_msg_rule` VALUES ('2', '0', '订单已付款', '', '您有订单已付款，单号{插入订单号}，会员昵称{插入昵称}', '{插入昵称},{插入订单号}', '1', '0', '0', '0', '0', '1');
INSERT INTO `zz_template_msg_rule` VALUES ('3', '0', '订单确认收货', '', '您有订单已确认收货，单号{插入订单号}，会员昵称{插入昵称}', '{插入昵称},{插入订单号}', '1', '0', '0', '0', '0', '1');
INSERT INTO `zz_template_msg_rule` VALUES ('4', '0', '会员申请退款', '', '您的店铺有一笔订单退款,订单号:{插入订单号},退款人:{插入昵称}', '{插入昵称},{插入订单号}', '1', '0', '0', '0', '0', '1');
INSERT INTO `zz_template_msg_rule` VALUES ('7', '1', '注册成为会员', '', '恭喜您，成为{插入店铺}的第{插入会员个数}个会员', '{插入ID号},{插入会员个数},{插入店铺}', '1', '0', '0', '0', '0', '1');
INSERT INTO `zz_template_msg_rule` VALUES ('8', '1', '被推荐成会员', '', '恭喜您，由{插入昵称}推荐成为{插入店铺}的第{插入会员个数}个会员', '{插入ID号},{插入昵称},{插入会员个数},{插入店铺}', '1', '0', '0', '0', '0', '1');
INSERT INTO `zz_template_msg_rule` VALUES ('9', '1', '有人被您推荐成会员', '', '恭喜您,推荐的{插入昵称}成为{插入店铺}的第{插入会员个数}位会员', '{插入昵称},{插入会员个数},{插入店铺}', '1', '0', '0', '0', '0', '1');
INSERT INTO `zz_template_msg_rule` VALUES ('10', '1', '有人被下级推荐成会员', '', '恭喜您,您的第{分销层级}级下级{插入上级昵称}推荐{插入昵称}成为{插入店铺}的第{插入会员个数}位会员！', '{插入昵称},{分销层级},{插入上级昵称},{插入会员个数},{插入店铺}', '1', '0', '0', '0', '0', '0');
INSERT INTO `zz_template_msg_rule` VALUES ('15', '3', '卖家已发货', '', '亲爱的会员，您购买的{插入商品标题}已经发货啦~', '{插入订单号},{插入商品标题},{插入快递公司},{插入快递单号}', '1', '0', '0', '0', '0', '1');
INSERT INTO `zz_template_msg_rule` VALUES ('17', '3', '退款申请被通过', '', '您在店铺{插入店铺}的退款申请被通过，订单号{插入订单号}', '{插入订单号},{插入店铺}', '1', '0', '0', '0', '0', '1');
INSERT INTO `zz_template_msg_rule` VALUES ('18', '3', '退款申请被拒绝', '', '您在店铺{插入店铺}的退款申请被拒绝，订单号{插入订单号}，理由:{插入理由}', '{插入订单号},{插入理由},{插入店铺}', '1', '0', '0', '0', '0', '1');
INSERT INTO `zz_template_msg_rule` VALUES ('20', '5', '优惠券未使用', '', '您有一张 {插入店铺}面值为{插入优惠券面值}的优惠券,未使用.不要错过哦', '{插入优惠券面值},{插入店铺}', '1', '0', '0', '0', '0', '1');
INSERT INTO `zz_template_msg_rule` VALUES ('21', '5', '活动中奖', '', '恭喜您{插入昵称}在店铺{插入店铺}参与的活动{插入活动名称}中奖,奖品:{插入商品标题}', '{插入昵称},{插入商品标题},{插入活动名称},{插入店铺}', '1', '0', '0', '0', '0', '1');
INSERT INTO `zz_template_msg_rule` VALUES ('30', '1', '拼团成功', '', '恭喜您拼团成功！我们将尽快为您发货，订单号{插入订单号},商品:{插入商品标题}', '{插入订单号},{插入商品标题}', '1', '0', '0', '0', '0', '1');
INSERT INTO `zz_template_msg_rule` VALUES ('31', '1', '拼团失败', '', '您参加的拼团因人数不足而组团失败，我们将在1-3个工作日内为你安排退款事宜，退款金额:{插入退款金额}', '{插入退款金额}', '1', '0', '0', '0', '0', '1');
INSERT INTO `zz_template_msg_rule` VALUES ('32', '3', '退款成功', '', '您的订单退款申请已经提交给微信处理，退款最迟5个工作日内会退到您的支付账号，退款金额:{插入退款金额}', '{插入退款金额}', '1', '0', '0', '0', '0', '1');
INSERT INTO `zz_template_msg_rule` VALUES ('33', '0', '拼团成功', '', '您有商品拼团成功，请及时发货', '', '1', '0', '0', '0', '0', '1');
INSERT INTO `zz_template_msg_rule` VALUES ('34', '1', '阶梯团付尾款', '', '恭喜您拼团成功！请尽快补齐尾款，订单号{插入订单号},商品:{插入商品标题}', '{插入订单号},{插入商品标题}', '1', '0', '0', '0', '0', '1');
INSERT INTO `zz_template_msg_rule` VALUES ('35', '1', '交易投诉', '', '您的交易投诉平台已处理，单号{插入订单号}', '{插入订单号}', '0', '0', '0', '0', '0', '1');

-- ----------------------------
-- Table structure for `zz_topic`
-- ----------------------------
DROP TABLE IF EXISTS `zz_topic`;
CREATE TABLE `zz_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(2) NOT NULL COMMENT '类型 1竞价活动，2专题活动',
  `typeid` int(4) NOT NULL COMMENT '专题类型',
  `name` varchar(255) NOT NULL COMMENT '专题名称',
  `apply_stime` int(11) NOT NULL COMMENT '报名开始时间',
  `apply_etime` int(11) NOT NULL COMMENT '报名结束时间',
  `start_time` int(11) NOT NULL COMMENT '活动开始时间',
  `end_time` int(11) NOT NULL COMMENT '活动结束时间',
  `status` tinyint(2) NOT NULL COMMENT '状态',
  `thumb` text NOT NULL COMMENT '专题图片',
  `thumbs` text NOT NULL COMMENT 'banner图',
  `catid` text NOT NULL COMMENT '专题分类',
  `listorder` int(6) NOT NULL DEFAULT '99999' COMMENT '排序',
  `posid` varchar(255) NOT NULL COMMENT '显示位置',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_topic
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_topic_cat`
-- ----------------------------
DROP TABLE IF EXISTS `zz_topic_cat`;
CREATE TABLE `zz_topic_cat` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `catname` varchar(255) NOT NULL COMMENT '分类名称',
  `parentid` int(10) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `arrparentid` text NOT NULL COMMENT '父类ID组',
  `arrchildid` text NOT NULL COMMENT '子类ID组',
  `child` tinyint(1) NOT NULL COMMENT '是否有子级',
  `title` varchar(120) NOT NULL COMMENT '页面标题',
  `keywords` varchar(120) NOT NULL COMMENT '页面关键字',
  `description` varchar(255) NOT NULL COMMENT '页面描述',
  `listorder` smallint(5) NOT NULL DEFAULT '0' COMMENT '排序',
  `thumb` varchar(255) NOT NULL COMMENT '缩略图',
  `url` varchar(255) NOT NULL COMMENT 'URL',
  `ismenu` smallint(3) NOT NULL DEFAULT '0' COMMENT '是否导航',
  `isrec` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1推荐',
  `thumb_rec` varchar(255) DEFAULT NULL COMMENT '推荐图',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='专题分类';

-- ----------------------------
-- Records of zz_topic_cat
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_topic_goods`
-- ----------------------------
DROP TABLE IF EXISTS `zz_topic_goods`;
CREATE TABLE `zz_topic_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `act_id` int(11) NOT NULL COMMENT '专题id',
  `goods_id` int(11) NOT NULL COMMENT '商品id',
  `cid` int(11) NOT NULL COMMENT '产品分类id',
  `c_time` int(11) NOT NULL COMMENT '提交时间',
  `u_time` int(11) NOT NULL COMMENT '修改时间',
  `status` tinyint(2) NOT NULL COMMENT '状态：0审核中，1通过，2不通过',
  `remark` varchar(255) NOT NULL COMMENT '备注',
  `listorder` smallint(5) NOT NULL COMMENT '排序',
  `sid` int(11) NOT NULL COMMENT '商家id',
  PRIMARY KEY (`id`),
  KEY `act_id` (`act_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='活动报名列表';

-- ----------------------------
-- Records of zz_topic_goods
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_topic_type`
-- ----------------------------
DROP TABLE IF EXISTS `zz_topic_type`;
CREATE TABLE `zz_topic_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typeid` tinyint(2) NOT NULL COMMENT '分类:1竞价活动，2专题活动',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `desc` text NOT NULL COMMENT '描述',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态 1开启，0关闭',
  `thumb` text NOT NULL COMMENT '封面图',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='活动类型';

-- ----------------------------
-- Records of zz_topic_type
-- ----------------------------
INSERT INTO `zz_topic_type` VALUES ('1', '1', '推文商品竞价', '直达消费者的最佳推广渠道，转化率和成交量超可观！', '1', '[{\"path\":\"\\/upload\\/1\\/images\\/gallery\\/q\\/w\\/969_src.jpg\",\"title\":\"\\u9996\\u98751\"}]');
INSERT INTO `zz_topic_type` VALUES ('2', '1', '首页商品竞价', '爆款商品的集中展示平台，海量订单岂可袖手旁观，就等你来！', '1', '');
INSERT INTO `zz_topic_type` VALUES ('3', '1', '海淘专区首页竞价', '海淘商品的风向标，巨大流量，款款热销。心动不如行动，快快报名!', '1', '');
INSERT INTO `zz_topic_type` VALUES ('4', '1', '同城到家专区首页竞价', '', '1', '');
INSERT INTO `zz_topic_type` VALUES ('5', '1', '限时秒杀', '', '1', '');
INSERT INTO `zz_topic_type` VALUES ('6', '2', '免费试用', '', '1', '');
INSERT INTO `zz_topic_type` VALUES ('7', '2', '品牌折扣', '', '1', '');
INSERT INTO `zz_topic_type` VALUES ('8', '2', '1元团', '', '1', '');
INSERT INTO `zz_topic_type` VALUES ('9', '2', '每日抽奖', '', '1', '');
INSERT INTO `zz_topic_type` VALUES ('10', '2', '热销榜', '', '1', '');
INSERT INTO `zz_topic_type` VALUES ('11', '2', '新人专享', '', '1', '');
INSERT INTO `zz_topic_type` VALUES ('12', '2', '新品热拼', '', '1', '');
INSERT INTO `zz_topic_type` VALUES ('13', '2', '品牌清仓', '', '1', '');
INSERT INTO `zz_topic_type` VALUES ('14', '2', '邻居团', '', '1', '');
INSERT INTO `zz_topic_type` VALUES ('15', '2', '推广团', '', '1', '');

-- ----------------------------
-- Table structure for `zz_usecode_log`
-- ----------------------------
DROP TABLE IF EXISTS `zz_usecode_log`;
CREATE TABLE `zz_usecode_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单ID',
  `order_sn` varchar(60) CHARACTER SET gbk NOT NULL COMMENT '订单号',
  `mid` int(11) NOT NULL DEFAULT '0' COMMENT '使用者ID',
  `username` varchar(60) CHARACTER SET gbk NOT NULL,
  `use_time` int(10) NOT NULL COMMENT '使用时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分享码记录';

-- ----------------------------
-- Records of zz_usecode_log
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_verify_code`
-- ----------------------------
DROP TABLE IF EXISTS `zz_verify_code`;
CREATE TABLE `zz_verify_code` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` varchar(12) NOT NULL,
  `getip` varchar(15) NOT NULL,
  `verifycode` varchar(10) NOT NULL,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `reguid` mediumint(8) unsigned DEFAULT '0',
  `regdateline` int(10) unsigned DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='手机验证码';

-- ----------------------------
-- Records of zz_verify_code
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_verify_idcard`
-- ----------------------------
DROP TABLE IF EXISTS `zz_verify_idcard`;
CREATE TABLE `zz_verify_idcard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL,
  `username` varchar(60) NOT NULL,
  `realname` varchar(12) NOT NULL,
  `card` varchar(60) NOT NULL COMMENT '身份证号',
  `card_image` varchar(225) NOT NULL,
  `card_image2` varchar(225) NOT NULL COMMENT '身份证背面照',
  `status` smallint(3) NOT NULL DEFAULT '1' COMMENT '状态:1待审核2已通过3未通过',
  `remark` text NOT NULL COMMENT '备注',
  `add_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='实名认证表';

-- ----------------------------
-- Records of zz_verify_idcard
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_video`
-- ----------------------------
DROP TABLE IF EXISTS `zz_video`;
CREATE TABLE `zz_video` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `userid` int(8) unsigned NOT NULL DEFAULT '0',
  `username` varchar(40) NOT NULL DEFAULT '',
  `url` varchar(60) NOT NULL DEFAULT '',
  `listorder` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(11) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(11) unsigned NOT NULL DEFAULT '0',
  `lang` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `title_style` varchar(40) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `file` mediumtext NOT NULL,
  `posid` varchar(40) NOT NULL DEFAULT '',
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `videourl` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_video
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_wheel`
-- ----------------------------
DROP TABLE IF EXISTS `zz_wheel`;
CREATE TABLE `zz_wheel` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '活动标题',
  `description` text NOT NULL COMMENT '抽奖活动描述',
  `miss_tip` varchar(255) NOT NULL DEFAULT '' COMMENT '未中奖说明',
  `miss_score` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '未中奖安慰积分',
  `score_cost` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '每次抽奖所需积分',
  `start_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '活动开始时间',
  `end_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '活动结束时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上次编辑时间',
  `winning_times` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '中奖次数',
  `hit_times` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '参与次数',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态 0关闭 1开启',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='大转盘主配置表';

-- ----------------------------
-- Records of zz_wheel
-- ----------------------------
INSERT INTO `zz_wheel` VALUES ('1', '测试1', '1.本次活动单次抽奖消耗100积分，积分直接从用户积分里面扣除；<br />\r\n2.活动时间：2014年7月25日-9月4日活动说明：活动期间，参加太平洋保险在线商城2周年乐趴总动员活动；<br />\r\n3.活动期间，用户通过周年庆活动专属页面在网上投保太平洋私家车商业险，即可获得好礼4选2；<br />\r\n4.本次活动最终解释权归***有限公司所有。', '对不起,没有中奖.请再接再厉哦 ^_^', '1', '10', '1464241200', '1464327600', '1464249945', '1464591817', '0', '0', '0');
INSERT INTO `zz_wheel` VALUES ('2', '测试2', '1.本次活动单次抽奖消耗100积分，积分直接从用户积分里面扣除；<br />\r\n2.活动时间：2014年7月25日-9月4日活动说明：活动期间，参加太平洋保险在线商城2周年乐趴总动员活动；<br />\r\n3.活动期间，用户通过周年庆活动专属页面在网上投保太平洋私家车商业险，即可获得好礼4选2；<br />\r\n4.本次活动最终解释权归***有限公司所有。', '很遗憾,没有中奖.请再接再厉哦 ^_^', '1', '10', '0', '0', '1464256729', '1464775435', '0', '0', '0');
INSERT INTO `zz_wheel` VALUES ('3', '测试1', '<p>\r\n	1. 本次活动单次抽奖消耗 XX 积分，积分直接从用户积分里面扣除\r\n</p>\r\n<p>\r\n	2. 本活动有效期 XX 起,至 XX\r\n</p>\r\n<p>\r\n	3. 实物奖品 15天内不填写收货地址并且不主动联系商家. 视为主动放弃奖品\r\n</p>\r\n<p>\r\n	4. 本次活动最终解释权归 XX 所有\r\n</p>', '对不起,没有中奖.请再接再厉哦 ^_^', '5', '10', '0', '0', '1464601984', '1464848717', '0', '0', '1');

-- ----------------------------
-- Table structure for `zz_wheel_log`
-- ----------------------------
DROP TABLE IF EXISTS `zz_wheel_log`;
CREATE TABLE `zz_wheel_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `wheel_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '活动id',
  `level` tinyint(4) NOT NULL DEFAULT '0' COMMENT '中奖等级 0 不中 1 一等奖 2二等奖 类推',
  `type` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '奖品类别 0未中奖1实物2优惠券3积分',
  `score_cost` int(11) NOT NULL DEFAULT '0' COMMENT '抽奖消耗的积分',
  `score_send` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '中奖赠送的积分',
  `coupon_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '中奖优惠券id',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '奖品描述',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `good_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品id',
  `need_express` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否需要运输',
  `address_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收货地址id',
  `express_name` varchar(255) NOT NULL DEFAULT '' COMMENT '快递名称',
  `express_sn` varchar(255) NOT NULL DEFAULT '' COMMENT '快递单号',
  `message_from` varchar(255) NOT NULL DEFAULT '' COMMENT '用户给商户的留言',
  `message_to` varchar(255) NOT NULL DEFAULT '' COMMENT '商户给用户留言',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上次修改时间',
  `expire_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '过期时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0作废 1已发奖 2待发奖 3待领奖 ',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='大转盘中奖记录';

-- ----------------------------
-- Records of zz_wheel_log
-- ----------------------------
INSERT INTO `zz_wheel_log` VALUES ('1', '2', '3', '3', '0', '10', '100', '0', '大转盘三等奖: 100积分', '1475130218', '0', '0', '0', '', '', '', '', '1475130218', '1476426218', '1');
INSERT INTO `zz_wheel_log` VALUES ('2', '2', '3', '3', '0', '10', '100', '0', '大转盘三等奖: 100积分', '1475131240', '0', '0', '0', '', '', '', '', '1475131240', '1476427240', '1');
INSERT INTO `zz_wheel_log` VALUES ('3', '2', '3', '3', '0', '10', '100', '0', '大转盘三等奖: 100积分', '1475131610', '0', '0', '0', '', '', '', '', '1475131610', '1476427610', '1');
INSERT INTO `zz_wheel_log` VALUES ('4', '2', '3', '3', '0', '10', '100', '0', '大转盘三等奖: 100积分', '1475131880', '0', '0', '0', '', '', '', '', '1475131880', '1476427880', '1');
INSERT INTO `zz_wheel_log` VALUES ('5', '2', '3', '3', '0', '10', '100', '0', '大转盘三等奖: 100积分', '1475132124', '0', '0', '0', '', '', '', '', '1475132124', '1476428124', '1');

-- ----------------------------
-- Table structure for `zz_wheel_prize`
-- ----------------------------
DROP TABLE IF EXISTS `zz_wheel_prize`;
CREATE TABLE `zz_wheel_prize` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `level` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '从低到高几等奖',
  `wheel_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '大转盘活动id',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '奖品名称',
  `type` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '奖品类型 0积分 1实物 2优惠券',
  `coupon_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '优惠券id',
  `good_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '奖品id',
  `need_express` tinyint(4) NOT NULL,
  `score_send` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '奖励积分分值',
  `stock` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '奖品数量 数量为0的商品中奖率永远为0 ',
  `rate` decimal(11,8) unsigned NOT NULL DEFAULT '0.00000000' COMMENT '中奖率  单位百分之一 最高小数点后8位小数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='大转盘的奖品明细表';

-- ----------------------------
-- Records of zz_wheel_prize
-- ----------------------------
INSERT INTO `zz_wheel_prize` VALUES ('1', '1', '3', '生态土鸡一只', '1', '0', '87', '1', '0', '1', '10.00000000');
INSERT INTO `zz_wheel_prize` VALUES ('2', '2', '3', '100元优惠券一张', '2', '3', '0', '0', '0', '0', '20.00000000');
INSERT INTO `zz_wheel_prize` VALUES ('3', '3', '3', '100积分', '0', '0', '0', '0', '100', '63', '20.00000000');

-- ----------------------------
-- Table structure for `zz_withdraw_commission`
-- ----------------------------
DROP TABLE IF EXISTS `zz_withdraw_commission`;
CREATE TABLE `zz_withdraw_commission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL,
  `username` varchar(60) NOT NULL,
  `type` tinyint(2) NOT NULL COMMENT '提现方式 0:微信(线上接口) 1:微信(线下转账) 2:支付宝(线下转账)',
  `realname` varchar(60) NOT NULL COMMENT '真实姓名',
  `account` varchar(60) NOT NULL COMMENT '收款账号',
  `commission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '佣金总额',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '提现总额',
  `fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '手续费',
  `addtime` int(10) NOT NULL COMMENT '添加时间',
  `status` smallint(3) NOT NULL DEFAULT '0' COMMENT '状态:0待处理1处理中2已处理',
  `openid` varchar(40) NOT NULL COMMENT '微信openid,用于请求微信转账接口',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='提现记录';

-- ----------------------------
-- Records of zz_withdraw_commission
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_withdraw_commission_sid`
-- ----------------------------
DROP TABLE IF EXISTS `zz_withdraw_commission_sid`;
CREATE TABLE `zz_withdraw_commission_sid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1，银行卡，2，微信，3，支付宝',
  `realname` varchar(60) NOT NULL COMMENT '真实姓名',
  `account` varchar(60) NOT NULL COMMENT '收款账号',
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '提现总额',
  `fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '手续费',
  `addtime` int(10) NOT NULL COMMENT '添加时间',
  `status` smallint(3) NOT NULL DEFAULT '0' COMMENT '状态:0待处理1处理中2已处理',
  `bank` varchar(45) DEFAULT NULL COMMENT '开户行',
  `wx` varchar(45) DEFAULT NULL COMMENT '微信账号',
  `alipay` varchar(45) DEFAULT NULL COMMENT '支付宝账号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='提现记录';

-- ----------------------------
-- Records of zz_withdraw_commission_sid
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_wx_autoreply_keyword`
-- ----------------------------
DROP TABLE IF EXISTS `zz_wx_autoreply_keyword`;
CREATE TABLE `zz_wx_autoreply_keyword` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rule_id` int(11) unsigned DEFAULT NULL COMMENT '规则id',
  `keyword` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `match` tinyint(2) DEFAULT '0' COMMENT '1完全匹配,0模糊匹配',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=utf8 COMMENT='微信自动回复关键字';

-- ----------------------------
-- Records of zz_wx_autoreply_keyword
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_wx_autoreply_rule`
-- ----------------------------
DROP TABLE IF EXISTS `zz_wx_autoreply_rule`;
CREATE TABLE `zz_wx_autoreply_rule` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `rule_type` enum('subscribe','autoreply','normal') CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '自动回复的场景',
  `keywords` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '关键字',
  `reply_list` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `c_time` int(10) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='自动回复规则总表';

-- ----------------------------
-- Records of zz_wx_autoreply_rule
-- ----------------------------
INSERT INTO `zz_wx_autoreply_rule` VALUES ('1', 'subscribe', 'subscribe', '', '{\"msg_type\":\"text\",\"msg_id\":49}', '1502077333');
INSERT INTO `zz_wx_autoreply_rule` VALUES ('12', 'autoreply', 'autoreply', '', '{\"msg_type\":\"news\",\"msg_id\":\"3\"}', '1481694574');

-- ----------------------------
-- Table structure for `zz_wx_autoreply_text`
-- ----------------------------
DROP TABLE IF EXISTS `zz_wx_autoreply_text`;
CREATE TABLE `zz_wx_autoreply_text` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rule_id` int(11) DEFAULT NULL,
  `content` varchar(1200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8 COMMENT='自动回复文本内容';

-- ----------------------------
-- Records of zz_wx_autoreply_text
-- ----------------------------
INSERT INTO `zz_wx_autoreply_text` VALUES ('49', '1', '');

-- ----------------------------
-- Table structure for `zz_wx_logs`
-- ----------------------------
DROP TABLE IF EXISTS `zz_wx_logs`;
CREATE TABLE `zz_wx_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `file` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `c_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='调试用的表 作废';

-- ----------------------------
-- Records of zz_wx_logs
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_wx_menu_data`
-- ----------------------------
DROP TABLE IF EXISTS `zz_wx_menu_data`;
CREATE TABLE `zz_wx_menu_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '递增主键',
  `json` text COMMENT '未发布菜单信息',
  `release` text COMMENT '已发布的菜单信息',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='微信自信定义菜单';

-- ----------------------------
-- Records of zz_wx_menu_data
-- ----------------------------
INSERT INTO `zz_wx_menu_data` VALUES ('1', '[]', '\"\"');

-- ----------------------------
-- Table structure for `zz_wx_news`
-- ----------------------------
DROP TABLE IF EXISTS `zz_wx_news`;
CREATE TABLE `zz_wx_news` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `c_time` int(11) DEFAULT NULL,
  `u_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='微信图文消息列表';

-- ----------------------------
-- Records of zz_wx_news
-- ----------------------------
INSERT INTO `zz_wx_news` VALUES ('4', '1459903685', '1459903685');

-- ----------------------------
-- Table structure for `zz_wx_news_item`
-- ----------------------------
DROP TABLE IF EXISTS `zz_wx_news_item`;
CREATE TABLE `zz_wx_news_item` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `news_id` int(11) DEFAULT NULL,
  `title` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `desc` varchar(280) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `author` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '作者',
  `cover` int(11) DEFAULT NULL COMMENT '封面图片ID',
  `cover_in_detail` tinyint(2) DEFAULT '0' COMMENT '封面是否显示在正文中',
  `src_link` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '',
  `view_num` int(11) DEFAULT '0' COMMENT '浏览次数',
  `c_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='微信图文消息内容';

-- ----------------------------
-- Records of zz_wx_news_item
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_wx_reply`
-- ----------------------------
DROP TABLE IF EXISTS `zz_wx_reply`;
CREATE TABLE `zz_wx_reply` (
  `re_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '索引ID',
  `re_type` smallint(3) DEFAULT '1' COMMENT '回复类型1被关回复2消息自动回复3关键字回复',
  `data_type` smallint(3) DEFAULT '1' COMMENT '内容类型1文字2图文',
  `content` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '回复内容',
  `keyword` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '匹配关键字',
  `rule_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '规则名称',
  PRIMARY KEY (`re_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='待删除';

-- ----------------------------
-- Records of zz_wx_reply
-- ----------------------------

-- ----------------------------
-- Table structure for `zz_wx_template`
-- ----------------------------
DROP TABLE IF EXISTS `zz_wx_template`;
CREATE TABLE `zz_wx_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nid` varchar(32) NOT NULL COMMENT '模板标识',
  `template_id` varchar(128) NOT NULL COMMENT '规则id',
  `title` varchar(255) NOT NULL COMMENT ' 标题',
  `content` text NOT NULL COMMENT '发送内容',
  `example` text NOT NULL COMMENT '事例',
  `first_data` varchar(255) NOT NULL,
  `remark_data` varchar(255) NOT NULL,
  `status` tinyint(2) NOT NULL COMMENT '状态 1开启，0关闭',
  `c_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nid` (`nid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zz_wx_template
-- ----------------------------

-- ----------------------------
-- 砍价功能
-- ----------------------------
CREATE TABLE `zz_bargain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `price` decimal(10,2) NOT NULL COMMENT '原价',
  `last_price` decimal(10,2) NOT NULL COMMENT '底价',
  `number` int(4) NOT NULL COMMENT '砍价人数',
  `stock` int(11) NOT NULL COMMENT '库存',
  `sell` int(11) NOT NULL COMMENT '销量',
  `term` int(4) NOT NULL COMMENT '有效时间天数',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态1正常，0禁用',
  `listorder` int(11) NOT NULL COMMENT '排序',
  `c_time` int(11) NOT NULL COMMENT '创建时间',
  `sid` int(11) NOT NULL COMMENT '商家ID',
  `is_check` tinyint(2) NOT NULL COMMENT '是否审核',
  `is_self` tinyint(2) NOT NULL COMMENT '自己砍一刀 0否 1是',
  `note` text NOT NULL COMMENT '活动说明',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='砍价活动';

CREATE TABLE `zz_bargain_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bargain_id` int(11) NOT NULL COMMENT '砍价id',
  `goods_id` int(11) NOT NULL COMMENT '商品id',
  `spec` varchar(255) NOT NULL COMMENT '商品规格id组合',
  `mid` int(11) NOT NULL COMMENT '会员id',
  `price` decimal(10,2) NOT NULL COMMENT '原价',
  `last_price` decimal(10,2) NOT NULL COMMENT '底价',
  `bargain_price` decimal(10,2) NOT NULL COMMENT '已砍价总额',
  `number` int(11) NOT NULL COMMENT '总人数',
  `number_yes` int(11) NOT NULL COMMENT '已砍人数',
  `e_time` int(11) NOT NULL COMMENT '结束时间',
  `c_time` int(11) NOT NULL COMMENT '创建时间',
  `status` tinyint(2) NOT NULL COMMENT '砍价状态 0砍价中，1砍价成功,2砍价失败',
  `order_id` int(11) NOT NULL COMMENT '订单id',
  `sid` int(11) NOT NULL COMMENT '商家id',
  PRIMARY KEY (`id`),
  KEY `goods_id_mid` (`goods_id`,`mid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='砍价列表';

CREATE TABLE `zz_bargain_log_help` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL COMMENT '会员id',
  `log_id` int(11) NOT NULL,
  `money` decimal(10,2) NOT NULL COMMENT '砍掉金额',
  `type` tinyint(2) NOT NULL COMMENT '类型：0帮忙砍价，1自己砍价',
  `c_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `id_mid` (`mid`,`log_id`) USING BTREE,
  KEY `logid` (`log_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT=' 帮忙砍价列表';

INSERT INTO `zz_config` VALUES ('bargain_times', '会员每天帮忙砍价次数', 'index', '3', '为空则不限制次数', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '60');
INSERT INTO `zz_config` VALUES ('bargain_sid_num', '每个商家发布砍价数量', 'index', '2', '0则表示商家不能发布砍价', 'text', '1', '1', '{\"size\":\"\",\"default\":\"\",\"ispassword\":\"0\"}', '61');

INSERT INTO `zz_block` VALUES ('50', 'bargain_rule', '【wap】砍价规则', '1', '<p>\r\n	1.邀请好友一起砍价，砍到0元即可免费领取商品；\r\n</p>\r\n<p>\r\n	2.对于同一个砍价，您只能帮助砍价一次；\r\n</p>\r\n<p>\r\n	3.每次砍价金额随机，参与好友越多越容易成功；\r\n</p>\r\n<p>\r\n	4.您每天最多可以帮助3个好友砍价；\r\n</p>\r\n<p>\r\n	5.每次砍价需在24小时内完成，逾期失效；\r\n</p>\r\n<p>\r\n	6.拼了又拼可在法律法规允许范围内对活动规则解释；\r\n</p>', '0');

REPLACE INTO `zz_module_field` VALUES ('78', '6', 'typeid', '广告位置', '', '1', '0', '0', '', '', '', 'select', '{\"options\":\"\\u3010\\u79fb\\u52a8\\u7aef-\\u9996\\u9875\\u7126\\u70b9\\u3011\\u7126\\u70b9\\u56fe|1\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u9996\\u9875\\u5e7f\\u544a\\u3011\\u5e7f\\u544a\\u56fe|17\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u5206\\u4eab\\u9875\\u3011\\u7126\\u70b9\\u56fe|2\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u8bd5\\u7528\\u9875\\u3011\\u5934\\u90e8\\u56fe\\u7247|5\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u62bd\\u5956\\u9875\\u3011\\u5934\\u90e8\\u56fe\\u7247|6\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u65b0\\u4eba\\u4e13\\u4eab\\u9875\\u3011\\u5934\\u90e8\\u56fe\\u7247|7\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u65b0\\u4eba\\u4e13\\u4eab\\u9875\\u3011\\u4f18\\u60e0\\u5238\\u56fe\\u7247|8\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u6d77\\u6dd8\\u3011\\u7126\\u70b9\\u56fe|11\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u6d77\\u6dd8\\u3011\\u63a8\\u8350\\u4e13\\u9898|12\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u56e2\\u957f\\u514d\\u5355\\u9875\\u3011\\u56e2\\u957f\\u514d\\u5355|13\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u4f17\\u7b79\\u5c1d\\u9c9c\\u9875\\u3011\\u4f17\\u7b79\\u5c1d\\u9c9c|14\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u54c1\\u724c\\u6e05\\u4ed3\\u9875\\u3011\\u54c1\\u724c\\u6e05\\u4ed3|15\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u65b0\\u54c1\\u9875\\u3011\\u65b0\\u54c1\\u5e7f\\u544a|16\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u6ce8\\u518c\\u767b\\u9646\\u3011\\u6ce8\\u518c\\u767b\\u9646|20\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u780d\\u4ef7\\u5217\\u8868\\u3011\\u780d\\u4ef7\\u5217\\u8868|21\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u5f15\\u5bfc\\u9875\\u56fe\\u7247\\u3011|10\\r\\n\\u3010pc\\u7aef-\\u9996\\u9875\\u3011\\u7126\\u70b9\\u56fe|51\\r\\n\\u3010pc\\u7aef-\\u884c\\u4e1a\\u6848\\u4f8b\\u3011banner|52\\r\\n\\u3010pc\\u7aef-\\u62fc\\u56e2\\u8d44\\u8baf\\u3011banner|53\\r\\n\\u3010pc\\u7aef-\\u8054\\u7cfb\\u6211\\u4eec\\u3011banner|54\",\"multiple\":\"0\",\"fieldtype\":\"varchar\",\"numbertype\":\"1\",\"size\":\"\",\"default\":\"\"}', '0', '', '0', '1', '0');
INSERT INTO `zz_ad` (`status`, `userid`, `username`, `url`, `listorder`, `createtime`, `updatetime`, `lang`, `title`, `title_style`, `thumb`, `width`, `height`, `images`, `typeid`) VALUES ( 1, 24, 'admin', '/content/show//76', 0, 1514510558, 0, 1, '砍价列表', '', '[]', '', '', '[{\"path\":\"\\/style\\/img\\/bargain-1.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\"}]', '21');


INSERT INTO `zz_menus` VALUES ('254', '砍价管理', '183', 'bargain', 'index', '', '&#xe601;', '0', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('255', '编辑砍价', '254', 'bargain', 'edit', '', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('256', '砍价记录列表', '254', 'bargain', 'log_list', '', '', '0', '2', '1', '0', null);

-- ----------------------------
-- 积分功能
-- ----------------------------

INSERT INTO `zz_menus` VALUES ('257', '积分记录', '95', 'score', 'log', '', '', '0', '1', '1', '0', NULL);

DELETE FROM `zz_score_log`;

DELETE FROM `zz_score_total`;

INSERT INTO `zz_score_rule` VALUES ('6', '6', '积分兑换', '用户可以使用积分去积分兑换区兑换商品', '{\"percent\":10}', '1');

ALTER TABLE `zz_goods_order` ADD COLUMN `score` DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT '积分使用' AFTER `surplus`;

ALTER TABLE `zz_score_total` ADD COLUMN `score_6` INT(11) NOT NULL DEFAULT 0 COMMENT '积分兑换' AFTER `u_time`;

DROP TABLE IF EXISTS `zz_goods_order_score`;
CREATE TABLE `zz_goods_order_score` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` int(11) unsigned NOT NULL COMMENT '订单ID',
  `amount` decimal(10,2) NOT NULL COMMENT '第三方支付金额',
  `score` int(10) unsigned NOT NULL COMMENT '需要发送的积分',
  `percent` decimal(10,2) NOT NULL COMMENT '当前购物积分利率',
  `addtime` int(10) unsigned NOT NULL COMMENT '记录时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0,未发送，1已发送，2，退款',
  `mid` int(10) unsigned NOT NULL COMMENT '会员id',
  `updatetime` int(10) unsigned NOT NULL COMMENT '更新时间',
  `order_sn` varchar(30) NOT NULL COMMENT '订单号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='记录订单需要发送的积分';


-- ----------------------------
-- 助力插件
-- ----------------------------
DROP TABLE IF EXISTS `zz_assist`;
CREATE TABLE `zz_assist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL COMMENT '商品ID',
  `price` decimal(10,2) NOT NULL COMMENT '原价',
  `number` int(4) NOT NULL COMMENT '砍价人数',
  `stock` int(11) NOT NULL COMMENT '库存',
  `sell` int(11) NOT NULL COMMENT '销量',
  `term` int(4) NOT NULL COMMENT '有效时间天数',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态1正常，0禁用',
  `listorder` int(11) NOT NULL COMMENT '排序',
  `c_time` int(11) NOT NULL COMMENT '创建时间',
  `sid` int(11) NOT NULL COMMENT '商家ID',
  `is_check` tinyint(2) NOT NULL COMMENT '是否审核',
  `note` text NOT NULL COMMENT '活动说明',
  `keywords` varchar(120) NOT NULL,
  `description` text NOT NULL,
  `is_app` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否需要app登录',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='助力活动';

DROP TABLE IF EXISTS `zz_assist_log`;
CREATE TABLE `zz_assist_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assist_id` int(11) NOT NULL COMMENT '助力id',
  `goods_id` int(11) NOT NULL COMMENT '商品id',
  `spec` varchar(255) NOT NULL COMMENT '商品规格id组合',
  `mid` int(11) NOT NULL COMMENT '会员id',
  `price` decimal(10,2) NOT NULL COMMENT '原价',
  `number` int(11) NOT NULL COMMENT '总人数',
  `number_yes` int(11) NOT NULL COMMENT '已助力人数',
  `e_time` int(11) NOT NULL COMMENT '结束时间',
  `c_time` int(11) NOT NULL COMMENT '创建时间',
  `status` tinyint(2) NOT NULL COMMENT '砍价状态 0砍价中，1砍价成功,2砍价失败',
  `order_id` int(11) NOT NULL COMMENT '订单id',
  `sid` int(11) NOT NULL COMMENT '商家id',
  `address_id` int(11) NOT NULL COMMENT '收货地址ID',
  `zone` int(11) NOT NULL COMMENT '区域id',
  `area` varchar(200) NOT NULL COMMENT '区域',
  `address` varchar(500) NOT NULL COMMENT '收货地址',
  `mobile` varchar(11) NOT NULL,
  `name` varchar(200) NOT NULL COMMENT '姓名',
  `is_app` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否需要app登录',
  PRIMARY KEY (`id`),
  KEY `goods_id_mid` (`goods_id`,`mid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='砍价列表';

DROP TABLE IF EXISTS `zz_assist_log_help`;
CREATE TABLE `zz_assist_log_help` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL COMMENT '会员id',
  `log_id` int(11) NOT NULL,
  `c_time` int(11) NOT NULL COMMENT '创建时间',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态 1成功',
  PRIMARY KEY (`id`),
  KEY `id_mid` (`mid`,`log_id`) USING BTREE,
  KEY `logid` (`log_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT=' 帮忙砍价列表';


REPLACE INTO `zz_module_field` VALUES ('78', '6', 'typeid', '广告位置', '', '1', '0', '0', '', '', '', 'select', '{\"options\":\"\\u3010\\u79fb\\u52a8\\u7aef-\\u9996\\u9875\\u7126\\u70b9\\u3011\\u7126\\u70b9\\u56fe|1\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u9996\\u9875\\u5e7f\\u544a\\u3011\\u5e7f\\u544a\\u56fe|17\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u5206\\u4eab\\u9875\\u3011\\u7126\\u70b9\\u56fe|2\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u5e97\\u94fa\\u9875\\u3011\\u7126\\u70b9\\u56fe|3\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u79d2\\u6740\\u9875\\u3011\\u5934\\u90e8\\u56fe\\u7247|4\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u8bd5\\u7528\\u9875\\u3011\\u5934\\u90e8\\u56fe\\u7247|5\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u62bd\\u5956\\u9875\\u3011\\u5934\\u90e8\\u56fe\\u7247|6\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u65b0\\u4eba\\u4e13\\u4eab\\u9875\\u3011\\u5934\\u90e8\\u56fe\\u7247|7\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u65b0\\u4eba\\u4e13\\u4eab\\u9875\\u3011\\u4f18\\u60e0\\u5238\\u56fe\\u7247|8\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u6d77\\u6dd8\\u3011\\u7126\\u70b9\\u56fe|11\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u6d77\\u6dd8\\u3011\\u63a8\\u8350\\u4e13\\u9898|12\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u56e2\\u957f\\u514d\\u5355\\u9875\\u3011\\u56e2\\u957f\\u514d\\u5355|13\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u4f17\\u7b79\\u5c1d\\u9c9c\\u9875\\u3011\\u4f17\\u7b79\\u5c1d\\u9c9c|14\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u54c1\\u724c\\u6e05\\u4ed3\\u9875\\u3011\\u54c1\\u724c\\u6e05\\u4ed3|15\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u65b0\\u54c1\\u9875\\u3011\\u65b0\\u54c1\\u5e7f\\u544a|16\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u6ce8\\u518c\\u767b\\u9646\\u3011\\u6ce8\\u518c\\u767b\\u9646|20\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u780d\\u4ef7\\u5217\\u8868\\u3011\\u780d\\u4ef7\\u5217\\u8868|21\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u52a9\\u529b\\u5217\\u8868\\u3011\\u52a9\\u529b\\u5217\\u8868|22\\r\\n\\u3010\\u79fb\\u52a8\\u7aef-\\u5f15\\u5bfc\\u9875\\u56fe\\u7247\\u3011|10\\r\\n\\u3010pc\\u7aef-\\u9996\\u9875\\u3011\\u7126\\u70b9\\u56fe|51\\r\\n\\u3010pc\\u7aef-\\u884c\\u4e1a\\u6848\\u4f8b\\u3011banner|52\\r\\n\\u3010pc\\u7aef-\\u62fc\\u56e2\\u8d44\\u8baf\\u3011banner|53\\r\\n\\u3010pc\\u7aef-\\u8054\\u7cfb\\u6211\\u4eec\\u3011banner|54\",\"multiple\":\"0\",\"fieldtype\":\"varchar\",\"numbertype\":\"1\",\"size\":\"\",\"default\":\"\"}', '0', '', '0', '1', '0');
INSERT INTO `zz_ad` (`status`, `userid`, `username`, `url`, `listorder`, `createtime`, `updatetime`, `lang`, `title`, `title_style`, `thumb`, `width`, `height`, `images`, `typeid`) VALUES ('1', '24', 'admin', '/content/show//77', '0', '1516415010', '0', '1', '助力列表', '', '[]', '', '', '[{\"path\":\"\\/style\\/img\\/help-img.jpg\",\"title\":\"\",\"iosurl\":\"\",\"anurl\":\"\",\"surl\":null}]', '22');

INSERT INTO `zz_block` VALUES ('51', 'assist_rule', '【wap】助力团规则', '1', '<p>\r\n	1.邀请好友助力，达到助力人数，即可享受免单权利；\r\n</p>\r\n<p>\r\n	2.每个新用户仅可助力一次。同一设备、微信号视为同一用户；\r\n</p>\r\n<p>\r\n	3.若发现用户存在刷单、虚假用户助力等违规行为，平台有权判定助力失败；\r\n</p>\r\n<p>\r\n	4.邀请到足够好友帮您助力成功之后，可前往我的订单查看发货与物流详情；\r\n</p>\r\n<p>\r\n	5.对物流、商品有疑问或者需要修改收货地址请联系商家客服处理；\r\n</p>\r\n<p>\r\n	6.拼了又拼可在法律法规允许范围内对本次活动规则解释并做适当修改。\r\n</p>', '0');


INSERT INTO `zz_menus` VALUES ('258', '助力管理', '183', 'assist', 'index', '', '&#xe601;', '0', '1', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('259', '编辑助力', '258', 'assist', 'edit', '', '', '0', '2', '1', '0', null);
INSERT INTO `zz_menus` VALUES ('260', '助力记录列表', '258', 'assist', 'log_list', '', '', '0', '2', '1', '0', null);


ALTER TABLE `zz_bargain` ADD COLUMN `e_time`  int(11) NOT NULL COMMENT '结束时间' AFTER `c_time`;
ALTER TABLE `zz_bargain` ADD COLUMN `keywords`  varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `note`;
ALTER TABLE `zz_bargain` ADD COLUMN `description`  text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `keywords`;

-- ----------------------------
-- 商家权限和多媒体无限级分类
-- ----------------------------
DROP TABLE IF EXISTS `zz_business_user`;
CREATE TABLE `zz_business_user` (
  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(64) NOT NULL,
  `lastlogin` int(11) NOT NULL,
  `salt` char(6) NOT NULL,
  `group_id` int(11) NOT NULL DEFAULT '1',
  `ip` varchar(15) NOT NULL,
  `u_time` int(11) NOT NULL,
  `c_time` int(11) NOT NULL,
  `desc` text NOT NULL,
  `visitor` tinyint(2) NOT NULL COMMENT '1 访客',
  `sid` int(11) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='管理员';


DROP TABLE IF EXISTS `zz_business_user_group`;
CREATE TABLE `zz_business_user_group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `desc` text NOT NULL,
  `menu_list` varchar(600) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '权限菜单',
  `listorder` smallint(3) NOT NULL,
  `sid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='权限分组';

DROP TABLE IF EXISTS `zz_business_user_log`;
CREATE TABLE `zz_business_user_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `log_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作时间',
  `user_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `log_info` varchar(255) NOT NULL DEFAULT '' COMMENT '操作事项',
  `ip_address` varchar(15) NOT NULL DEFAULT '' COMMENT 'IP地址',
  `sid` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `log_time` (`log_time`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='管理员操作记录';

ALTER TABLE `zz_business` ADD COLUMN `address`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '店铺所在地' AFTER `geohash`;
ALTER TABLE `zz_gallery_tag` ADD COLUMN `sid`  int(11) NOT NULL AFTER `type`;
ALTER TABLE `zz_gallery_tag` ADD COLUMN `parentid`  int(11) NOT NULL AFTER `sid`;
CREATE INDEX `good_id` ON `zz_goods_order_item`(`good_id`) USING BTREE ;