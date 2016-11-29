-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-11-30 01:45:30
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cshop`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin_api`
--

CREATE TABLE IF NOT EXISTS `admin_api` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `description` varchar(200) NOT NULL,
  `json` text NOT NULL,
  `time` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `admin_article`
--

CREATE TABLE IF NOT EXISTS `admin_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `time` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `admin_menu`
--

CREATE TABLE IF NOT EXISTS `admin_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '菜单名',
  `url` varchar(100) NOT NULL COMMENT '菜单url',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `admin_menu`
--

INSERT INTO `admin_menu` (`id`, `name`, `url`) VALUES
(1, '文章管理', 'Index/article'),
(2, 'API管理', 'Index/api'),
(3, '分类管理', 'Index/cate'),
(4, '商品管理', 'Index/goods'),
(5, '订单管理', 'Index/order'),
(6, '用户管理', 'Index/index_user'),
(7, '提现管理', 'Index/cash_apply');

-- --------------------------------------------------------

--
-- 表的结构 `admin_menu_permission`
--

CREATE TABLE IF NOT EXISTS `admin_menu_permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `json` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='左侧菜单权限分配' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `admin_menu_permission`
--

INSERT INTO `admin_menu_permission` (`id`, `user_id`, `json`) VALUES
(1, 1, ''),
(2, 2, '{"2":[]}');

-- --------------------------------------------------------

--
-- 表的结构 `admin_user`
--

CREATE TABLE IF NOT EXISTS `admin_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `identity` varchar(20) NOT NULL COMMENT '{超级管理员:root} {普通用户:user}     ',
  `time` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `admin_user`
--

INSERT INTO `admin_user` (`id`, `name`, `password`, `identity`, `time`) VALUES
(1, 'root', 'root', 'root', '1478829283'),
(2, 'sunqi', 'sunqi', 'user', '1479094884');

-- --------------------------------------------------------

--
-- 表的结构 `cash`
--

CREATE TABLE IF NOT EXISTS `cash` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `operate` tinyint(1) NOT NULL COMMENT '0:减 1:加 ',
  `money` varchar(20) NOT NULL,
  `time` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='余额' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `cash`
--

INSERT INTO `cash` (`id`, `user_id`, `operate`, `money`, `time`) VALUES
(1, 1, 1, '10', '1480439803');

-- --------------------------------------------------------

--
-- 表的结构 `cash_apply`
--

CREATE TABLE IF NOT EXISTS `cash_apply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `alipay` varchar(200) NOT NULL COMMENT '支付宝账号',
  `wxpay` varchar(200) NOT NULL COMMENT '微信账号',
  `money` varchar(20) NOT NULL,
  `remark` varchar(300) NOT NULL,
  `add_time` varchar(10) NOT NULL COMMENT '添加时间',
  `end_time` varchar(10) NOT NULL COMMENT '处理时间',
  `status` tinyint(1) NOT NULL COMMENT '处理状态  0：未处理 1：已经处理 2：申请无效',
  `reason` varchar(300) NOT NULL COMMENT '提现申请无效理由',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='申请提现' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `cate`
--

CREATE TABLE IF NOT EXISTS `cate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sort` int(10) unsigned NOT NULL COMMENT '排序 默认值100 越小排序越前',
  `name` varchar(100) NOT NULL,
  `img` varchar(300) NOT NULL,
  `time` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `cate`
--

INSERT INTO `cate` (`id`, `sort`, `name`, `img`, `time`) VALUES
(4, 100, 'test', 'data/uploads/cate/1479831536.jpg', '1479831536'),
(5, 100, '考拉', 'data/uploads/cate/1479913956.jpg', '1479913956');

-- --------------------------------------------------------

--
-- 表的结构 `freeze`
--

CREATE TABLE IF NOT EXISTS `freeze` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `order_id` int(10) unsigned NOT NULL,
  `goods_name` varchar(300) NOT NULL,
  `operate` tinyint(1) NOT NULL COMMENT '0:减 1:加 ',
  `money` varchar(20) NOT NULL,
  `add_time` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='冻结资金' AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `freeze`
--

INSERT INTO `freeze` (`id`, `user_id`, `order_id`, `goods_name`, `operate`, `money`, `add_time`) VALUES
(1, 1, 2, '测试1', 1, '5', '1480435924'),
(2, 2, 1, '测试1', 1, '10.5', '1480436364'),
(3, 2, 1, '测试1', 0, '10.5', '1480439066'),
(4, 2, 1, '测试1', 0, '10.5', '1480439073'),
(5, 1, 2, 'etst', 1, '10', '1480439651');

-- --------------------------------------------------------

--
-- 表的结构 `goods`
--

CREATE TABLE IF NOT EXISTS `goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cate_id` int(10) unsigned NOT NULL,
  `sort` int(10) unsigned NOT NULL,
  `name` varchar(300) NOT NULL,
  `url` text NOT NULL,
  `img` varchar(300) NOT NULL,
  `price` varchar(20) NOT NULL,
  `time` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `goods`
--

INSERT INTO `goods` (`id`, `cate_id`, `sort`, `name`, `url`, `img`, `price`, `time`) VALUES
(1, 5, 100, 'test', 'www.baidu.com', 'data/uploads/goods/1479987215.jpg', '10', '1479905197');

-- --------------------------------------------------------

--
-- 表的结构 `order`
--

CREATE TABLE IF NOT EXISTS `order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `goods_name` varchar(300) NOT NULL COMMENT '商品名',
  `order_num` varchar(100) NOT NULL COMMENT '第三方平台提供-订单编号',
  `income` varchar(20) NOT NULL DEFAULT '0' COMMENT '商家返利',
  `freeze_money` varchar(20) NOT NULL DEFAULT '0' COMMENT '用户返利',
  `add_time` varchar(10) NOT NULL,
  `is_use` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0:失效',
  `end_time` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `order`
--

INSERT INTO `order` (`id`, `user_id`, `goods_name`, `order_num`, `income`, `freeze_money`, `add_time`, `is_use`, `end_time`) VALUES
(1, 2, '测试1', '1235466', '100', '10.5', '1480436364', 0, '1480439078'),
(2, 1, 'etst', '111', '100', '10', '1480439651', 0, '1480439803');

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `freeze_money` varchar(20) NOT NULL DEFAULT '0' COMMENT '冻结资金',
  `cash_money` varchar(20) NOT NULL DEFAULT '0' COMMENT '可提现金额',
  `time` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `name`, `password`, `freeze_money`, `cash_money`, `time`) VALUES
(1, 'root', 'root', '6', '10', '1479922110'),
(2, 'test', 'test', '-41', '31.5', '1479922189');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
