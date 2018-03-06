-- --------------------------------------------------------
-- 主机:                           127.0.0.1
-- 服务器版本:                        5.7.16 - MySQL Community Server (GPL)
-- 服务器操作系统:                      Win64
-- HeidiSQL 版本:                  9.2.0.4947
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 导出  表 fenxiangpic.pic_admin 结构
CREATE TABLE IF NOT EXISTS `pic_admin` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` char(16) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `email` char(32) NOT NULL COMMENT '用户邮箱',
  `mobile` char(15) NOT NULL DEFAULT '' COMMENT '用户手机',
  `encrypt` varchar(6) NOT NULL,
  `reg_ip` varchar(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `last_login_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` varchar(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '用户状态',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- 正在导出表  fenxiangpic.pic_admin 的数据：2 rows
/*!40000 ALTER TABLE `pic_admin` DISABLE KEYS */;
INSERT INTO `pic_admin` (`id`, `username`, `password`, `email`, `mobile`, `encrypt`, `reg_ip`, `last_login_time`, `last_login_ip`, `status`, `create_time`, `update_time`) VALUES
	(1, 'admin', '6fd0632346f0086441fb985403a928cf', 'admin@admin.com', 'admin', 'p48IW2', '0', 1516927506, '127.0.0.1', 1, 1496365567, 1516927506),
	(2, 'test', '86005ec10aa23ff1657775d3084537d9', '', '', '68ZEFd', '127.0.0.1', 1499220013, '127.0.0.1', 1, 1499219061, 1499220223);
/*!40000 ALTER TABLE `pic_admin` ENABLE KEYS */;


-- 导出  表 fenxiangpic.pic_auth_group 结构
CREATE TABLE IF NOT EXISTS `pic_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` char(255) NOT NULL DEFAULT '',
  `description` text COMMENT '描述',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- 正在导出表  fenxiangpic.pic_auth_group 的数据：2 rows
/*!40000 ALTER TABLE `pic_auth_group` DISABLE KEYS */;
INSERT INTO `pic_auth_group` (`id`, `title`, `status`, `rules`, `description`, `create_time`, `update_time`) VALUES
	(1, '超级管理员', 1, '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,1,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,20,21,22,23,24,25,26,27,28,29,30,31', '', 0, 1466780039),
	(2, '高级管理员', 1, '1,6,11', '高级管理员', 1499220150, 1499220159);
/*!40000 ALTER TABLE `pic_auth_group` ENABLE KEYS */;


-- 导出  表 fenxiangpic.pic_auth_group_access 结构
CREATE TABLE IF NOT EXISTS `pic_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 正在导出表  fenxiangpic.pic_auth_group_access 的数据：2 rows
/*!40000 ALTER TABLE `pic_auth_group_access` DISABLE KEYS */;
INSERT INTO `pic_auth_group_access` (`uid`, `group_id`) VALUES
	(1, 1),
	(2, 2);
/*!40000 ALTER TABLE `pic_auth_group_access` ENABLE KEYS */;


-- 导出  表 fenxiangpic.pic_auth_rule 结构
CREATE TABLE IF NOT EXISTS `pic_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `pid` mediumint(8) unsigned NOT NULL,
  `path` varchar(100) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `icon` varchar(30) NOT NULL COMMENT '图标',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

-- 正在导出表  fenxiangpic.pic_auth_rule 的数据：30 rows
/*!40000 ALTER TABLE `pic_auth_rule` DISABLE KEYS */;
INSERT INTO `pic_auth_rule` (`id`, `name`, `title`, `type`, `status`, `condition`, `create_time`, `update_time`, `pid`, `path`, `sort`, `is_show`, `icon`) VALUES
	(1, 'admin/main/index', '控制面板', 1, 1, '', 0, 1496813552, 0, '0', 1, 0, 'fa fa-desktop'),
	(2, '', '系统', 1, 1, '', 0, 1496813994, 0, '0', 200, 1, 'fa fa-gears'),
	(3, 'admin/auth_group/index', '角色管理', 1, 1, '', 0, 1496814070, 2, '0-2', 1, 1, 'fa fa-group'),
	(4, 'admin/auth_rule/index', '权限列表', 1, 1, '', 0, 1496814106, 2, '0-2', 2, 1, ''),
	(5, 'admin/auth_rule/add', '添加权限', 1, 1, '', 0, 1466686168, 4, '0-2-4', 1, 0, ''),
	(6, 'admin/user/logout', '退出登录', 1, 1, '', 0, 1490260608, 0, '0', 0, 0, 'fa fa-file-text'),
	(7, 'admin/auth_group/add', '添加角色', 1, 1, '', 0, 0, 3, '0-2-3', 0, 0, ''),
	(8, 'admin/auth_group/edit', '编辑角色', 1, 1, '', 0, 0, 3, '0-2-3', 0, 0, ''),
	(9, 'admin/auth_group/del', '删除角色', 1, 1, '', 0, 0, 3, '0-2-3', 0, 0, ''),
	(10, 'admin/auth_rule/edit', '编辑权限', 1, 1, '', 0, 1466686416, 4, '0-2-4', 2, 0, ''),
	(11, 'admin/user/changePwd', '修改密码', 1, 1, '', 1466688085, 1466688085, 0, '0', 0, 0, ''),
	(12, 'admin/auth_group/resource', '资源管理', 1, 1, '', 1466688887, 1466688887, 3, '0-2-3', 0, 0, ''),
	(13, 'admin/user/index', '用户管理', 1, 1, '', 1466778713, 1490316425, 2, '0-2', 0, 1, 'fa fa-user'),
	(14, 'admin/user/edit', '编辑用户', 1, 1, '', 1466779374, 1466779374, 13, '0-2-13', 0, 0, ''),
	(15, 'admin/user/del', '删除用户', 1, 1, '', 1466779400, 1466779400, 13, '0-2-13', 0, 0, ''),
	(16, 'admin/user/add', '添加用户', 1, 1, '', 1466780028, 1466780028, 13, '0-2-13', 0, 0, ''),
	(17, 'admin/auth_rule/del', '删除权限', 1, 1, '', 1466911172, 1466911172, 4, '0-2-4', 0, 0, ''),
	(18, 'admin/main/welcome', '欢迎界面', 1, 1, '', 0, 1496813535, 0, '0', 2, 0, 'Hui-iconfont-feedback'),
	(20, 'admin/category/index', '分类管理', 1, 1, '', 1516591809, 1516597462, 0, '0', 1, 1, 'fa fa-briefcase'),
	(21, '', '文件管理', 1, 1, '', 1516597170, 1516616294, 0, '0', 2, 1, 'fa fa-camera'),
	(22, 'admin/picture/index', '相片列表', 1, 1, '', 1516616195, 1516616195, 21, '0-21', 1, 1, 'fa fa-database'),
	(23, 'admin/video/index', '视频列表', 1, 1, '', 1516616354, 1516616354, 21, '0-21', 2, 1, 'fa fa-file-video-o'),
	(24, 'admin/category/add', '分类添加', 1, 1, '', 1516877612, 1516877612, 20, '0-20', 0, 0, ''),
	(25, 'admin/category/edit', '分类编辑', 1, 1, '', 1516877654, 1516877654, 20, '0-20', 0, 0, ''),
	(26, 'admin/category/del', '分类删除', 1, 1, '', 1516877688, 1516877688, 20, '0-20', 0, 0, ''),
	(27, 'admin/category/switchstate', '分类控制开关', 1, 1, '', 1516877724, 1516877724, 20, '0-20', 0, 0, ''),
	(28, 'admin/picture/add', '图片添加', 1, 1, '', 1516877766, 1516877766, 22, '0-21-22', 0, 0, ''),
	(29, 'admin/picture/del', '图片删除', 1, 1, '', 1516877804, 1516877804, 22, '0-21-22', 0, 0, ''),
	(30, 'admin/video/add', '视频添加', 1, 1, '', 1516877827, 1516877827, 23, '0-21-23', 0, 0, ''),
	(31, 'admin/video/del', '视频删除', 1, 1, '', 1516877857, 1516877857, 23, '0-21-23', 0, 0, '');
/*!40000 ALTER TABLE `pic_auth_rule` ENABLE KEYS */;


-- 导出  表 fenxiangpic.pic_category 结构
CREATE TABLE IF NOT EXISTS `pic_category` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL COMMENT '分类名称',
  `channel` tinyint(4) NOT NULL COMMENT '频道 1图集 2视频',
  `state` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态 1默认开启 2关闭',
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='相册分类表';

-- 正在导出表  fenxiangpic.pic_category 的数据：~3 rows (大约)
/*!40000 ALTER TABLE `pic_category` DISABLE KEYS */;
INSERT INTO `pic_category` (`id`, `title`, `channel`, `state`, `addtime`) VALUES
	(1, '签到', 1, 1, 1516937377),
	(2, '晚宴', 1, 1, 1516937385),
	(3, '视频', 2, 1, 1516937394);
/*!40000 ALTER TABLE `pic_category` ENABLE KEYS */;


-- 导出  表 fenxiangpic.pic_image 结构
CREATE TABLE IF NOT EXISTS `pic_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL COMMENT '用户id',
  `typeid` smallint(6) NOT NULL COMMENT '所属分类id',
  `imageurl1` varchar(250) NOT NULL COMMENT '原图',
  `imageurl2` varchar(250) NOT NULL COMMENT '缩略图',
  `imageurl3` varchar(250) NOT NULL COMMENT '详情大图',
  `username` varchar(50) NOT NULL COMMENT '上传者',
  `imagesize` int(11) NOT NULL COMMENT '原图大小',
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- 正在导出表  fenxiangpic.pic_image 的数据：~9 rows (大约)
/*!40000 ALTER TABLE `pic_image` DISABLE KEYS */;
INSERT INTO `pic_image` (`id`, `userid`, `typeid`, `imageurl1`, `imageurl2`, `imageurl3`, `username`, `imagesize`, `addtime`) VALUES
	(8, 1, 1, '/uploads/picture/20180126/77bfc600639c140aa7b902260f3af272.jpg', '/uploads/picture/20180126/77bfc600639c140aa7b902260f3af272_small.jpg', '/uploads/picture/20180126/77bfc600639c140aa7b902260f3af272_big.jpg', 'admin', 151691, 1516937427),
	(9, 1, 1, '/uploads/picture/20180126/569725dc8503300ad65aee34a4edd07a.jpg', '/uploads/picture/20180126/569725dc8503300ad65aee34a4edd07a_small.jpg', '/uploads/picture/20180126/569725dc8503300ad65aee34a4edd07a_big.jpg', 'admin', 77254, 1516937427),
	(10, 1, 1, '/uploads/picture/20180126/03d0298baf62c3aa7a9d60a118b8e535.jpg', '/uploads/picture/20180126/03d0298baf62c3aa7a9d60a118b8e535_small.jpg', '/uploads/picture/20180126/03d0298baf62c3aa7a9d60a118b8e535_big.jpg', 'admin', 94477, 1516937427),
	(11, 1, 1, '/uploads/picture/20180126/b2a15461360b31e4670cfd33728bd40c.png', '/uploads/picture/20180126/b2a15461360b31e4670cfd33728bd40c_small.png', '/uploads/picture/20180126/b2a15461360b31e4670cfd33728bd40c_big.png', 'admin', 434166, 1516937427),
	(12, 1, 1, '/uploads/picture/20180126/c0fdc812bf97d7198c57141c2034771d.png', '/uploads/picture/20180126/c0fdc812bf97d7198c57141c2034771d_small.png', '/uploads/picture/20180126/c0fdc812bf97d7198c57141c2034771d_big.png', 'admin', 336052, 1516937427),
	(13, 1, 1, '/uploads/picture/20180126/93f39f0b65dde2b83f454d512107b2e4.png', '/uploads/picture/20180126/93f39f0b65dde2b83f454d512107b2e4_small.png', '/uploads/picture/20180126/93f39f0b65dde2b83f454d512107b2e4_big.png', 'admin', 319128, 1516937428),
	(14, 1, 1, '/uploads/picture/20180126/b2c2414da9257415802622734ce03c07.png', '/uploads/picture/20180126/b2c2414da9257415802622734ce03c07_small.png', '/uploads/picture/20180126/b2c2414da9257415802622734ce03c07_big.png', 'admin', 453472, 1516937428),
	(15, 1, 1, '/uploads/picture/20180126/fd744a9baf50620abf7dab372e6c7de4.jpg', '/uploads/picture/20180126/fd744a9baf50620abf7dab372e6c7de4_small.jpg', '/uploads/picture/20180126/fd744a9baf50620abf7dab372e6c7de4_big.jpg', 'admin', 57270, 1516937428),
	(16, 1, 1, '/uploads/picture/20180126/f197f15bd58e1ad0d7d75c332840533e.jpg', '/uploads/picture/20180126/f197f15bd58e1ad0d7d75c332840533e_small.jpg', '/uploads/picture/20180126/f197f15bd58e1ad0d7d75c332840533e_big.jpg', 'admin', 38556, 1516937428);
/*!40000 ALTER TABLE `pic_image` ENABLE KEYS */;


-- 导出  表 fenxiangpic.pic_video 结构
CREATE TABLE IF NOT EXISTS `pic_video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL COMMENT '上传者id',
  `username` varchar(50) NOT NULL COMMENT '上传者',
  `typeid` smallint(6) NOT NULL COMMENT '分类id',
  `imageurl` varchar(250) DEFAULT NULL COMMENT '缩略图',
  `videourl` varchar(250) NOT NULL COMMENT '视频地址',
  `width` int(11) NOT NULL COMMENT '视频宽度',
  `height` int(11) NOT NULL COMMENT '视频高度',
  `size` bigint(20) NOT NULL COMMENT '视频大小',
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='短视频表';

-- 正在导出表  fenxiangpic.pic_video 的数据：~1 rows (大约)
/*!40000 ALTER TABLE `pic_video` DISABLE KEYS */;
INSERT INTO `pic_video` (`id`, `userid`, `username`, `typeid`, `imageurl`, `videourl`, `width`, `height`, `size`, `addtime`) VALUES
	(10, 1, 'admin', 3, '/uploads/video/allimg/1516941917.jpg', '/uploads/video/20180126/7c830d24d185c2b9018c166ce1c83c2a.mp4', 960, 544, 1619010, 1516941917);
/*!40000 ALTER TABLE `pic_video` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
