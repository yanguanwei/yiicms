-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 06 月 07 日 17:23
-- 服务器版本: 5.5.31
-- PHP 版本: 5.4.15-1~precise+1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `yiicms`
--

-- --------------------------------------------------------

--
-- 表的结构 `y_archive`
--

CREATE TABLE IF NOT EXISTS `y_archive` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `cover` varchar(100) NOT NULL,
  `cid` int(10) unsigned NOT NULL DEFAULT '0',
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `model_id` int(10) unsigned NOT NULL DEFAULT '0',
  `template` varchar(100) NOT NULL,
  `visits` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_highlight` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_top` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `keywords` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `post_time` int(10) unsigned NOT NULL,
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `archive_cid_fpk` (`cid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `y_archive`
--

INSERT INTO `y_archive` (`id`, `title`, `cover`, `cid`, `uid`, `model_id`, `template`, `visits`, `status`, `is_highlight`, `is_top`, `keywords`, `description`, `post_time`, `update_time`) VALUES
(3, 'asdfasdf', '', 2, 1, 3, '0', 0, 0, 0, 0, 'asdf', 'asf', 1370596073, 1370596020);

-- --------------------------------------------------------

--
-- 表的结构 `y_archive_tag`
--

CREATE TABLE IF NOT EXISTS `y_archive_tag` (
  `aid` int(10) unsigned NOT NULL DEFAULT '0',
  `tid` int(10) unsigned NOT NULL DEFAULT '0',
  `type_name` varchar(32) NOT NULL,
  KEY `aid` (`aid`),
  KEY `tid` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `y_archive_tag`
--

INSERT INTO `y_archive_tag` (`aid`, `tid`, `type_name`) VALUES
(3, 8, 'location');

-- --------------------------------------------------------

--
-- 表的结构 `y_channel`
--

CREATE TABLE IF NOT EXISTS `y_channel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `theme_id` int(10) unsigned NOT NULL DEFAULT '0',
  `model_id` int(10) unsigned NOT NULL DEFAULT '0',
  `channel_template` varchar(100) NOT NULL,
  `archive_template` varchar(100) NOT NULL,
  `visible` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort_id` int(10) unsigned NOT NULL DEFAULT '0',
  `keywords` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `post_time` int(10) unsigned NOT NULL DEFAULT '0',
  `tags` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- 转存表中的数据 `y_channel`
--

INSERT INTO `y_channel` (`id`, `title`, `parent_id`, `theme_id`, `model_id`, `channel_template`, `archive_template`, `visible`, `sort_id`, `keywords`, `description`, `post_time`, `tags`) VALUES
(1, '主题活动', 0, 1, 1, '', '', 1, 0, '', '', 1370491136, ''),
(2, '购物街图片', 0, 1, 3, '', '', 1, 0, '', '', 1370593976, 'location'),
(3, '商家', 0, 1, 1, '', '', 1, 0, '', '', 1370491183, ''),
(4, '旅游购物', 0, 1, 1, '', '', 1, 0, '', '', 1370491202, ''),
(5, '促销信息', 0, 1, 5, '', '', 1, 0, '', '', 1370589500, ''),
(6, '精彩瞬间', 0, 1, 1, '', '', 1, 0, '', '', 1370491236, ''),
(7, '支持单位', 0, 1, 2, '', '', 1, 0, '', '', 1370491272, '');

-- --------------------------------------------------------

--
-- 表的结构 `y_channel_alias`
--

CREATE TABLE IF NOT EXISTS `y_channel_alias` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `identifier` char(16) NOT NULL,
  `alias` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `identifier` (`identifier`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `y_channel_alias`
--

INSERT INTO `y_channel_alias` (`id`, `identifier`, `alias`) VALUES
(5, '64f11c7332e4f868', 'promotions');

-- --------------------------------------------------------

--
-- 表的结构 `y_channel_model`
--

CREATE TABLE IF NOT EXISTS `y_channel_model` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `table_name` varchar(20) NOT NULL,
  `alias` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `y_channel_model`
--

INSERT INTO `y_channel_model` (`id`, `title`, `table_name`, `alias`) VALUES
(1, '新闻', 'archive', 'news'),
(2, '友情链接', 'link', 'link'),
(3, '图片', 'archive', 'picture'),
(4, '视频', 'archive', 'video'),
(5, '促销', 'archive', 'promotion');

-- --------------------------------------------------------

--
-- 表的结构 `y_collect_task`
--

CREATE TABLE IF NOT EXISTS `y_collect_task` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `type_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cid` int(10) unsigned NOT NULL DEFAULT '0',
  `configs` text NOT NULL,
  `data` text NOT NULL,
  `is_repeat` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `y_config_type`
--

CREATE TABLE IF NOT EXISTS `y_config_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `is_app` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL,
  `note` varchar(255) NOT NULL,
  `key` varchar(50) NOT NULL,
  `default` text NOT NULL,
  `sort_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `y_config_type`
--

INSERT INTO `y_config_type` (`id`, `type`, `is_app`, `title`, `note`, `key`, `default`, `sort_id`) VALUES
(1, 0, 1, '语言', '', 'language', 'zh_cn', 10),
(2, 0, 1, '网站名称', '', 'name', '', 99),
(3, 0, 0, '网站标题', '显示在网页title标签里', 'title', '', 98),
(4, 3, 0, 'logo', '', 'logo', '', 11),
(5, 0, 0, '招商热线', '', 'hotline', '', 6),
(6, 0, 0, '地址', '', 'address', '', 7),
(7, 0, 0, '网址', '', 'url', 'http://', 9),
(8, 4, 0, '关键字（SEO）', '各关键字以半角逗号“,”分隔', 'keywords', '', 0),
(9, 4, 0, '描述（SEO）', '', 'description', '', 0),
(10, 0, 0, 'ICP备案号', '', 'icp', '', 8);

-- --------------------------------------------------------

--
-- 表的结构 `y_link`
--

CREATE TABLE IF NOT EXISTS `y_link` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `visible` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `post_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `y_link`
--

INSERT INTO `y_link` (`id`, `cid`, `title`, `url`, `logo`, `visible`, `sort_id`, `post_time`) VALUES
(1, 7, '大润发', 'http://', '/uploads/images/logo/index-unit-img1.png', 1, 0, 1370491652),
(2, 7, '三江', 'http://', '/uploads/images/logo/index-unit-img2.png', 1, 0, 1370491678),
(3, 7, '大昌隆', 'http://', '/uploads/images/logo/index-unit-img3.png', 1, 0, 1370493768),
(4, 7, '爱握乐', 'http://', '/uploads/images/logo/index-unit-img4.png', 1, 0, 1370493811),
(5, 7, '宁波银行', 'http://', '/uploads/images/logo/index-unit-img5.png', 1, 0, 1370493835),
(6, 7, '好又多量贩', 'http://', '/uploads/images/logo/index-unit-img6.png', 1, 0, 1370493871),
(7, 7, '中国银行', 'http://', '/uploads/images/logo/index-unit-img7.png', 1, 0, 1370493895);

-- --------------------------------------------------------

--
-- 表的结构 `y_nav`
--

CREATE TABLE IF NOT EXISTS `y_nav` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(50) NOT NULL,
  `type_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `theme_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL,
  `url` varchar(100) NOT NULL,
  `sort_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `enabled` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `y_nav`
--

INSERT INTO `y_nav` (`id`, `identifier`, `type_id`, `parent_id`, `theme_id`, `title`, `url`, `sort_id`, `enabled`) VALUES
(1, 'home', 0, 0, 1, '首页', '/', 0, 1),
(2, 'activities', 0, 0, 1, '主题活动', 'channel/activities', 0, 1),
(3, 'streets', 0, 0, 1, '购物街', 'channel/streets', 0, 1),
(4, 'merchants', 0, 0, 1, '商家展示', 'channel/merchants', 0, 1),
(5, 'shopping', 0, 0, 1, '旅游购物', 'channel/shopping', 0, 1),
(6, 'promotions', 0, 0, 1, '促销信息', 'channel/promotions', 0, 1),
(7, 'moments', 0, 0, 1, '精彩瞬间', 'channel/moments', 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `y_news`
--

CREATE TABLE IF NOT EXISTS `y_news` (
  `id` int(10) unsigned NOT NULL,
  `content` text NOT NULL,
  `source` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `y_promotion`
--

CREATE TABLE IF NOT EXISTS `y_promotion` (
  `id` int(10) unsigned NOT NULL,
  `promotion_type` int(10) unsigned NOT NULL DEFAULT '0',
  `promotion_category` int(10) unsigned NOT NULL DEFAULT '0',
  `location` int(10) unsigned NOT NULL DEFAULT '0',
  `discounts` varchar(32) NOT NULL,
  `start_time` int(10) unsigned NOT NULL DEFAULT '10',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `y_tag`
--

CREATE TABLE IF NOT EXISTS `y_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type_name` (`type_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- 转存表中的数据 `y_tag`
--

INSERT INTO `y_tag` (`id`, `type_name`, `title`) VALUES
(1, 'promotion_category', '食品海鲜'),
(2, 'promotion_category', '服饰家纺'),
(3, 'promotion_category', '家具用品'),
(4, 'promotion_category', '文具礼品'),
(5, 'promotion_category', '生活服务'),
(6, 'promotion_type', '线下促销'),
(7, 'promotion_type', '线上促销'),
(8, 'location', '天一国际购物中心'),
(9, 'location', '江北万达广场');

-- --------------------------------------------------------

--
-- 表的结构 `y_tag_type`
--

CREATE TABLE IF NOT EXISTS `y_tag_type` (
  `name` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `y_tag_type`
--

INSERT INTO `y_tag_type` (`name`, `title`) VALUES
('location', '购物街'),
('promotion_category', '促销分类'),
('promotion_type', '促销类别');

-- --------------------------------------------------------

--
-- 表的结构 `y_theme`
--

CREATE TABLE IF NOT EXISTS `y_theme` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `name` varchar(20) NOT NULL,
  `entry` varchar(50) NOT NULL,
  `configs` text NOT NULL,
  `css` text NOT NULL,
  `js` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `y_theme`
--

INSERT INTO `y_theme` (`id`, `title`, `name`, `entry`, `configs`, `css`, `js`) VALUES
(1, '宁波购物节', 'default', 'index', 'a:10:{s:4:"name";s:15:"宁波购物节";s:5:"title";s:19:"2013宁波购物节";s:4:"logo";s:24:"/uploads/images/logo.jpg";s:8:"language";s:5:"zh_cn";s:3:"url";s:18:"http://www.nbsp.cc";s:3:"icp";s:22:"浙ICP备13001534号-1";s:7:"address";s:44:"宁波国家高新区创苑路750号A座2楼";s:7:"hotline";s:13:"0574-87903707";s:8:"keywords";s:13:"宁波,软件";s:11:"description";s:33:"宁波软件园，宁波智慧园";}', '@charset "utf-8";\r\n/* CSS Document */\r\n\r\nbody, h1, h2, h3, h4, h5, h6, hr, p, blockquote, dl, dt, dd, ul, ol, li, pre, form, fieldset, legend, button, input, textarea, th, td { margin:0; padding:0; }\r\nbody, button, input, select, textarea { font-size:12px; font-family:"Microsoft YaHei","微软雅黑","Microsoft JhengHei","STHeiti,MingLiu";}\r\nh1, h2, h3, h4, h5, h6 { font-size:100%; }\r\naddress, cite, dfn, em, var { font-style:normal; }\r\ncode, kbd, pre, samp { font-family:courier new, courier, monospace; }\r\nsmall { font-size:12px; }\r\ninput::-moz-focus-inner{ border: 0;padding: 0;}\r\nul, ol { list-style:none; }\r\na { text-decoration:none; }\r\na:hover { text-decoration:underline; }\r\nsup { vertical-align:text-top; }\r\nsub { vertical-align:text-bottom; }\r\nlegend { color:#000; }\r\nfieldset, img { border:0; }\r\nbutton, input, select, textarea { font-size:100%; }\r\n/*table { border-collapse:collapse; border-spacing:0; }*/\r\n\r\n.clearfix:after {visibility: hidden;display: block;font-size: 0;content: " ";clear: both;height: 0;}\r\n.clearfix { display: inline-table; }\r\n/* Hides from IE-mac \\*/\r\n* html .clearfix { height: 1%; }\r\n.clearfix { display: block; }\r\n/* End hide from IE-mac */\r\n\r\n\r\na { color:#333333; text-decoration:none;}\r\na:link { color:#333333; text-decoration:none;}\r\na:visited { color:#333333; text-decoration:none;}\r\na:hover { color:#a13a0c; text-decoration:underline;}\r\na:active { color:#333333; text-decoration:none;}\r\n\r\n\r\n.fl {float:left;}\r\n.fr {float:right;}\r\n\r\nbody { color:#333; background:url(../images/bg.png) repeat-x scroll left top #6C3088;}\r\n\r\n@charset "utf-8";\r\n/* CSS Document */\r\n\r\n.header { width:100%; height:275px; background:url(../images/header-bg.png) no-repeat scroll center top;}\r\n.header-wrap { width:1000px; margin:0px auto; height:275px;}\r\n.header-index { background:url(../images/header-index.png) no-repeat scroll center top;}\r\n.header-promotion { background:url(../images/header-promotion.png) no-repeat scroll center top;}\r\n.header-merchant { background:url(../images/header-merchant.png) no-repeat scroll center top;}\r\n.header .search { float:right; padding:10px;}\r\n.header .search .input-txt { width:123px; height:20px; line-height:20px; padding-left:23px; background:url(../images/search-inputTxt.png) no-repeat scroll left top #fff; border:none;}\r\n.header .search .input-btn { width:44px; height:20px; line-height:20px; text-align:center; font-weight:bold; color:#fff; border:none; cursor:pointer; background:url(../images/search-inputBtn.png) repeat-x scroll left top;}\r\n\r\n.navigation { height:53px; background:url(../images/navigation-bg.png) repeat-x scroll left top; width:1018px; margin:-11px auto 0px auto; line-height:42px; overflow:hidden;}\r\n.navigation .nav-left { width:9px; height:53px; display:inline-block; background:url(../images/nav-left.png) no-repeat scroll left top;}\r\n.navigation .nav-right { width:9px; height:53px; display:inline-block; background:url(../images/nav-right.png) no-repeat scroll left top;}\r\n.navigation ul { width:978px; padding-left:20px;}\r\n.navigation ul li { float:left; display:inline-block; padding:0px 22px; height:42px; text-align:center; line-height:42px; font-size:15px; font-weight:bold;}\r\n.navigation ul li.goShopping { width:140px; text-align:left; background:url(../images/nav-goShopping.png) no-repeat scroll left 12px; text-indent:25px; float:right; padding:0px; font-size:16px;}\r\n.navigation ul li a:hover { text-decoration:none;}\r\n\r\n.container { width:1000px; margin:0px auto;}\r\n.index-column1 { width:980px; padding:0px 10px 10px 10px; background:#ffffff; margin-bottom:10px;}\r\n\r\n.index-column1 .index-banner { width:667px; height:293px;}\r\n.index-column1 .index-banner .index-banner-wrap { width:667px; height:293px; margin:0px auto;}\r\n.index-column1 .index-banner .mainbox { overflow: hidden; position: relative; }\r\n.index-column1 .index-banner .flashbox { overflow: hidden; position: relative; }\r\n.index-column1 .index-banner .imagebox { text-align: right; position: relative; z-index: 999;}\r\n.index-column1 .index-banner .bitdiv { display: inline-block; width: 7px; height: 7px; margin: 0 10px 10px 0px; cursor: pointer; float: right; }\r\n.index-column1 .index-banner .defimg { background-image: url(../images/02.png) }\r\n.index-column1 .index-banner .curimg { background-image: url(../images/01.png) }\r\n\r\n.index-column1 .index-trends { width:308px; height:293px; background:url(../images/index-trends-bg.png) no-repeat scroll left top #FDF6EA;}\r\n.index-column1 .index-trends .title { height:37px; line-height:37px;}\r\n.index-column1 .index-trends .title .more { background:url(../images/index-trends-icon.png) no-repeat scroll right center; margin-right:7px; font-weight:normal; padding-right:10px;}\r\n.index-column1 .index-trends .trends-wrap { width:285px; margin:0px auto; padding-top:10px;}\r\n.index-column1 .index-trends .trends-first { height:114px; line-height:20px;}\r\n.index-column1 .index-trends .trends-first .pic { width:114px; height:114px; margin-right:13px;}\r\n.index-column1 .index-trends .trends-first a { color:#FF1DFF;}\r\n.index-column1 .index-trends .trends-first p { text-indent:24px;}\r\n.index-column1 .index-trends .trends-list { padding-top:10px;}\r\n.index-column1 .index-trends .trends-list li { height:22px; line-height:22px; text-indent:15px; background:url(../images/point.png) no-repeat scroll left center;}\r\n\r\n.index-hottest { width:1000px; height:313px; margin-bottom:10px; background:url(../images/index-hottest-bg.png) no-repeat scroll left top;}\r\n.index-hottest .title { height:62px;}\r\n.index-hottest .hottest-tabLi { padding:5px 0px 0px 15px; width:304px;}\r\n.index-hottest .hottest-tabLi li { width:147px; height:34px; margin:0px 5px 5px 0px; float:left; line-height:34px; text-align:center; font-size:14px; font-weight:bold; cursor:pointer;}\r\n.index-hottest .hottest-tabLi li.current { color:#ffffff; background:url(../images/index-hottest-bg2.png) repeat-x scroll left top;}\r\n.index-hottest .hottest-tabLi li.normal { background:url(../images/index-hottest-bg1.png) repeat-x scroll left top;}\r\n.index-hottest .hottest-changeWrap { width:670px; overflow:hidden;}\r\n\r\n.index-column3 { width:990px; padding:6px 0px 10px 10px; background:#ffffff;}\r\n.index-happy { width:758px; margin-right:12px;}\r\n.index-happy .title { height:35px; line-height:35px; font-weight:normal;}\r\n.index-happy .title .more { background:url(../images/more.png) no-repeat scroll right center; padding-right:10px;}\r\n.index-happy .happy-wrap { width:754px; height:249px; padding:9px 0px 0px 0px; border:2px solid #FF9E12; overflow:hidden;}\r\n.index-happy .happy-wrap ul { height:249px; overflow:hidden; padding-left:9px;}\r\n.index-happy .happy-wrap ul li { height:116px; padding:0px 7px 7px 0px; width:241px; text-align:left; float:left;}\r\n.index-happy .happy-wrap ul li img { width:241px; height:116px;}\r\n\r\n.index-section { width:210px;}\r\n.index-section .section-wrap { padding-top:12px;}\r\n.index-section .section-wrap dl dt.t a { color:#ffffff; text-decoration:none;}\r\n.index-section .section-wrap dl dt.t { width:209px; height:25px; line-height:25px; text-indent:12px; font-size:15px; color:#fff; background:url(../images/index-section-t.png) no-repeat scroll left top; margin-bottom:2px;}\r\n.index-section .section-wrap dl dd.d { display:none; padding:8px 0px; text-align:center; width:210px; overflow:hidden;}\r\n\r\n.index-column4 { width:990px; padding:6px 0px 10px 10px; background:#ffffff;}\r\n.index-unit { width:758px; margin-right:12px;}\r\n.index-unit .title { height:35px; line-height:35px; font-weight:normal;}\r\n.index-unit .title .more { background:url(../images/more.png) no-repeat scroll right center; padding-right:10px;}\r\n.index-unit .unit-wrap { width:754px; height:249px; padding:9px 0px 0px 0px; border:2px solid #FF9E12; overflow:hidden;}\r\n.index-unit .unit-wrap span { display:inline-block; height:121px; overflow:hidden; float:left;}\r\n.index-unit .unit-wrap span img { max-height:121px;}\r\n\r\n.index-merchant { width:214px; height:290px; margin-top:7px; background:url(../images/index-merchant-bg.png) no-repeat scroll left top;}\r\n.index-merchant .title { height:29px; line-height:29px; text-indent:12px; font-size:15px; color:#ffffff; font-weight:normal;}\r\n.index-merchant .merchant-wrap { margin-top:15px; height:230px; overflow:hidden; width:214px;}\r\n.index-merchant .merchant-wrap ul li { height:22px; text-align:center; line-height:22px;}\r\n.index-merchant .merchant-wrap ul li a { color:#ffffff;}\r\n.index-merchant .merchant-box { }\r\n\r\n\r\n.footer { width:785px; padding:25px 0px 25px 215px; line-height:22px; background:#ffffff;}\r\n.footer .footer-box { padding-top:10px;}\r\n\r\n\r\n\r\n/*promotion*/\r\n.promotion-sort { width:1000px; margin:0px 0px 10px 0px; background:#fff; padding:10px 0px;}\r\n.promotion-sort .sort-wrap { width:980px; height:103px; margin:0px auto; background:url(../images/promotion-sort-bg.png) no-repeat scroll left top; position:relative;}\r\n.promotion-sort .sort-wrap dl { height:35px; line-height:35px;}\r\n.promotion-sort .sort-wrap dl dt { width:75px; text-indent:7px;}\r\n.promotion-sort .sort-wrap dl dd { margin-left:75px;}\r\n.promotion-sort .sort-wrap dl dd a { padding:0px 8px;}\r\n.promotion-sort .sort-wrap dl dd a:hover { color:#E02680; font-weight:bold; text-decoration:none;}\r\n.promotion-sort .sort-wrap .link { position:absolute; top:30px; right:14px;}\r\n\r\n.promotion-list { width:1000px; padding:10px 0px; background:#ffffff;}\r\n.promotion-list ul { padding-left:10px;}\r\n.promotion-list ul li { width:315px; height:295px; box-shadow:2px 2px 5px #ccc; border:1px solid #dfdfdf; margin:0px 12px 12px 0px; float:left;}\r\n.promotion-list ul li .pic { padding-top:9px; height:203px;}\r\n.promotion-list ul li .pic img { width:297px; height:203px; margin:0px auto; display:block;}\r\n.promotion-list ul li .title { height:30px; padding-top:10px; text-align:center; font-size:13px; font-weight:bold;}\r\n.promotion-list ul li .color-red { color:#E02680;}\r\n.promotion-list ul li .title .name { padding-right:10px; color:#000;}\r\n.promotion-list ul li .other { background:#F7F4E0; height:42px; line-height:42px; text-align:right; padding:0px 10px; font-size:14px;}\r\n.promotion-list ul li .other .discount { width:69px; height:39px; line-height:31px; text-align:center; color:#fff; background:url(../images/promotion-list-discount.png) no-repeat scroll left top; font-weight:bold; font-size:15px;}\r\n\r\n\r\n/*商家展示*/\r\n.merchant-nav { width:1000px; margin:0px 0px 10px 0px; background:#fff; padding:10px 0px;}\r\n.merchant-nav .nav-wrap { width:923px; height:50px; margin:0px auto; background:url(../images/merchant-navBg.png) no-repeat scroll left top; padding:19px 0px 0px 57px;}\r\n.merchant-nav .nav-wrap ul {}\r\n.merchant-nav .nav-wrap ul li { float:left; width:150px; height:29px; line-height:29px; font-size:15px; font-weight:bold;}\r\n.merchant-nav .nav-wrap ul li a { color:#666;}\r\n.merchant-nav .nav-wrap ul li a:hover { color:#da1e79;}\r\n.merchant-nav .nav-wrap ul li span { display:inline-block; height:29px;}\r\n.merchant-nav .nav-wrap ul li .txt { padding-left:5px;}\r\n\r\n.merchant-list { width:1000px; padding:10px 0px; background:#ffffff;}\r\n.merchant-list .tt { border-bottom:2px solid #FF9E11; width:980px; margin:0px auto; margin-bottom:10px;}\r\n.merchant-list .tt span { width:112px; height:35px; line-height:35px; text-align:center; font-size:14px; color:#ffffff; background:url(../images/merchant-list-title.png) no-repeat scroll left top; display:inline-block;}\r\n.merchant-list ul { padding-left:10px;}\r\n.merchant-list ul li { width:315px; height:295px; box-shadow:2px 2px 5px #ccc; border:1px solid #dfdfdf; margin:0px 12px 12px 0px; float:left;}\r\n.merchant-list ul li .pic { padding-top:9px; height:203px;}\r\n.merchant-list ul li .pic img { width:297px; height:203px; margin:0px auto; display:block;}\r\n.merchant-list ul li .title { height:30px; padding-top:10px; text-align:center; font-size:14px; font-weight:bold;}\r\n.merchant-list ul li .color-red { color:#E02680;}\r\n.merchant-list ul li .other { background:#F7F4E0; height:42px; line-height:42px; text-align:center; padding:0px 10px; font-size:16px; font-weight:bold; color:#666;}\r\n\r\n\r\n/* 新闻列表 */\r\n.breadcrumb { background:#fff; height:35px; line-height:23px;}\r\n.breadcrumb a { padding:0px 11px;}\r\n.breadcrumb a.current { color:#DA1E79;}\r\n.news { background:#ffffff;}\r\n.news .news-wrap { width:770px;}\r\n.news .news-list { padding:15px;}\r\n.news .news-list li { height:26px; line-height:26px; margin-bottom:8px; text-indent:30px; font-size:14px;}\r\n.news .news-list li .time { color:#b5b5b5; font-size:12px; padding-right:10px;}\r\n.news .news-list li a { display:block; background:url(../images/point.png) no-repeat scroll 15px center #fff;}\r\n.news .news-list li a:hover { color:#E02680; font-weight:bold; display:block; background:url(../images/point.png) no-repeat scroll 15px center #f1f1f1; text-decoration:none;}\r\n.news .news-list li a:hover .time { font-weight:normal;}\r\n\r\n.news .news-sidebar { width:215px; min-height:400px;_height:400px;_overflow:visible; background:url(../images/news-right-bg.png) no-repeat scroll left top #ffffff;}\r\n.news .news-sidebar .title { height:29px; line-height:29px; text-indent:15px; font-weight:bold; font-size:14px; color:#ffffff;}\r\n.news .news-sidebar .sidebar-wrap { padding-top:16px;}\r\n.news .news-sidebar .sidebar-list { width:194px; margin:0px auto; padding-bottom:7px;}\r\n.news .news-sidebar .sidebar-list dt { width:194px; height:23px; line-height:23px; color:#fff; font-size:14px;}\r\n.news .news-sidebar .sidebar-list dt a { color:#fff; padding-left:12px;}\r\n.news .news-sidebar .sidebar-list1 dt { background:url(../images/news-sidebar1.png) no-repeat scroll left top;}\r\n.news .news-sidebar .sidebar-list2 dt { background:url(../images/news-sidebar2.png) no-repeat scroll left top;}\r\n.news .news-sidebar .sidebar-list3 dt { background:url(../images/news-sidebar3.png) no-repeat scroll left top;}\r\n.news .news-sidebar .sidebar-list4 dt { background:url(../images/news-sidebar4.png) no-repeat scroll left top;}\r\n.news .news-sidebar .sidebar-list dd { text-align:center;}\r\n.news .news-sidebar .sidebar-list dd ul { padding:12px 0px;}\r\n.news .news-sidebar .sidebar-list dd ul li { height:25px; line-height:25px;}\r\n.news .news-sidebar .sidebar-list dd ul li a { color:#fff;}\r\n\r\n.news .news-wrap .news-view-title { width:740px; margin:0px auto; background:#F6F6F6; border:1px solid #e8e8e8; padding:13px 0px;}\r\n.news .news-wrap .news-view-title .view-t1 { font-size:15px; color:#000; text-align:center;}\r\n.news .news-wrap .news-view-title .view-t2 { padding-top:20px; font-size:12px; color:#999; text-align:center; font-weight:normal;}\r\n.news .news-wrap .news-view-details { padding:20px 0px; width:720px; margin:0px auto; line-height:24px; font-size:14px;}', '$(function() {\r\n	$(''.tabs'').each(function() {\r\n		var $block = $(this).parents(''.block'');\r\n		$(''li a'', $(this)).click(function() {\r\n			var href = $(this).attr(''href'');\r\n			if ( href.substr(0, 1) == ''#'' ) {\r\n				$(this).parents(''li'').siblings().removeClass(''actived'')\r\n					.end().addClass(''actived'');\r\n				$(''.tab-content'', $block).hide();\r\n				$(''#tab-'' + href.substr(1), $block).show();\r\n				return false;\r\n			}\r\n		});\r\n		\r\n		if ( $(''li.actived'', $(this)).length == 0 )\r\n			$(''li a'', $(this)).eq(0).click();\r\n	});\r\n	\r\n	//友情链接跳转\r\n	$(''#friendLinks select'').change(function() {\r\n		var $form = $(''#friendLinks_Form'');\r\n		if ( $form.length == 0 ) {\r\n			$form = $(''<form target="_blank"><input type="submit" /></form>'').hide();\r\n			$(''body'').append($form);\r\n		}\r\n		$form.attr(''action'', $(this).val());\r\n		$form.submit();\r\n	});\r\n	\r\n	//搜索框\r\n	var searchText = ''请输入您要搜索的关键字'';\r\n	$(''#searchForm'').submit(function() {\r\n		if ( $(''#searchInput'').val() == searchText )\r\n			return false;\r\n	});\r\n	\r\n	$(''#searchInput'').click(function() {\r\n			if ( $(this).val() == searchText )\r\n				$(this).val('''');\r\n		}).blur(function() {\r\n			if ( !$(this).val() )\r\n				$(this).val(searchText);\r\n		}).blur();\r\n});\r\n\r\nfunction AddFavorite(sURL, sTitle)\r\n{\r\n    try {\r\n        window.external.addFavorite(sURL, sTitle);\r\n    } catch (e) {\r\n        try {\r\n            window.sidebar.addPanel(sTitle, sURL, "");\r\n        } catch (e) {\r\n            alert("加入收藏失败，请使用Ctrl+D进行添加");\r\n        }\r\n    }\r\n}\r\n\r\n//设为首页 <a onclick="SetHome(this,window.location)">设为首页</a>\r\nfunction SetHome(obj,vrl)\r\n{\r\n	try{ \r\n		obj.style.behavior=''url(#default#homepage)'';obj.setHomePage(vrl);\r\n	} catch(e) {\r\n		if(window.netscape) {\r\n			try {\r\n				netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");\r\n			} catch (e) {\r\n				alert("此操作被浏览器拒绝！\\n请在浏览器地址栏输入“about:config”并回车\\n然后将 [signed.applets.codebase_principal_support]的值设置为''true'',双击即可。");\r\n			}\r\n			var prefs = Components.classes[''@mozilla.org/preferences-service;1''].getService(Components.interfaces.nsIPrefBranch);\r\n			prefs.setCharPref(''browser.startup.homepage'',vrl);\r\n		}\r\n	}\r\n}\r\n\r\nfunction setFontSize(selector, size)\r\n{\r\n	$(selector).css(''font-size'', size);\r\n}');

-- --------------------------------------------------------

--
-- 表的结构 `y_theme_template`
--

CREATE TABLE IF NOT EXISTS `y_theme_template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `theme_id` int(10) unsigned NOT NULL DEFAULT '0',
  `path` varchar(100) NOT NULL,
  `post_time` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

--
-- 转存表中的数据 `y_theme_template`
--

INSERT INTO `y_theme_template` (`id`, `theme_id`, `path`, `post_time`) VALUES
(1, 0, '/news/list', 1355815163),
(2, 0, '/news/detail', 1355816241),
(14, 0, '/news/park_detail', 1355816275),
(15, 0, '/picture/list', 1356079918),
(11, 0, '/news/channel_detail', 1355450337),
(13, 0, '/news/firstchannel_detail', 1355467599),
(12, 0, '/news/firstchannel_list', 1355464018),
(17, 0, '/site/index', 1356404483),
(18, 0, '/layouts/main', 1355814411),
(19, 0, '/blocks/newslist', 1355806086),
(20, 0, '/blocks/block', 1355808825),
(21, 0, '/news/search_list', 1355812648),
(22, 0, '/blocks/block_end', 1355809279),
(23, 0, '/news/content_block', 1355815881),
(24, 0, '/news/sidebar', 1355809564),
(25, 0, '/channel/sidebar', 1355809621),
(26, 0, '/video/flv_detail', 1356404168),
(27, 0, '/sidebars/contactus', 1355881973),
(29, 0, '/blocks/flv', 1356401358),
(31, 0, '/link/list', 1356590756),
(32, 0, '/link/picture_list', 1356590769),
(33, 0, '/blocks/linklist', 1356590887);

-- --------------------------------------------------------

--
-- 表的结构 `y_user`
--

CREATE TABLE IF NOT EXISTS `y_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(25) DEFAULT '',
  `password` char(32) NOT NULL,
  `email` varchar(40) NOT NULL,
  `role_id` int(10) unsigned NOT NULL DEFAULT '0',
  `last_login` int(10) unsigned NOT NULL DEFAULT '0',
  `login_token` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `y_user`
--

INSERT INTO `y_user` (`id`, `username`, `password`, `email`, `role_id`, `last_login`, `login_token`) VALUES
(1, 'admin', '2d1d5137f626b2d4b7750b60dd3a43a8', '', 1, 1370588406, '94cd615b3009092aff3a1949588eb341'),
(9, 'manager', 'e10adc3949ba59abbe56e057f20f883e', '', 2, 1355970822, 'c91a4b48b26360a54dcb971a8b046cc8'),
(10, 'nbspadmin', '8652606c8d662eac2fcf1ce9a9d0fa10', '', 2, 1360112352, '568021eb0ca378fc738074472ed9f3f9');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
