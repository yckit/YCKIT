<?php 
if(!defined('ROOT'))exit('Access denied!');
$query=array();
$query[]="CREATE TABLE IF NOT EXISTS `".DB_PREFIX."page` (
  `page_id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `page_title` varchar(255) NOT NULL DEFAULT '',
  `page_keywords` varchar(100) NOT NULL DEFAULT '',
  `page_description` varchar(255) NOT NULL DEFAULT '',
  `page_file` varchar(3000) NOT NULL DEFAULT '',
  `page_content_mode` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `page_content` text NOT NULL,
  `page_sort` int(8) NOT NULL DEFAULT '0',
  `page_html` varchar(100) NOT NULL DEFAULT '',
  `page_is_comment` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `page_is_display` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`page_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

$query[]="CREATE TABLE IF NOT EXISTS `".DB_PREFIX."page_comment` (
  `comment_id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `comment_name` varchar(50) NOT NULL DEFAULT '',
  `comment_email` varchar(255) NOT NULL DEFAULT '',
  `comment_site` varchar(255) NOT NULL DEFAULT '',
  `comment_content` text NOT NULL,
  `comment_reply` text NOT NULL,
  `comment_time` int(10) unsigned NOT NULL DEFAULT '0',
  `comment_ip` varchar(50) NOT NULL DEFAULT '',
  `comment_agent` varchar(255) NOT NULL DEFAULT '',
  `comment_is_display` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `parent_id` int(8) unsigned NOT NULL DEFAULT '0',
  `page_id` int(4) unsigned NOT NULL DEFAULT '0',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8";
if(count($query)>0){
	foreach($query as $sql)$this->db->query($sql);
}
#检查写入配置
$row=$this->db->row("SELECT config_type FROM ".DB_PREFIX."config WHERE config_type='page' LIMIT 0,1");
if(empty($row['config_type'])){
	$this->db->insert(DB_PREFIX."config",array('config_type'=>'page','config_value'=>'YTozOntzOjI4OiJwYWdlX2Zyb250X2NvbW1lbnRfbGlzdF9zaXplIjtzOjI6IjEwIjtzOjI4OiJwYWdlX2FkbWluX2NvbW1lbnRfbGlzdF9zaXplIjtzOjI6IjEwIjtzOjIzOiJwYWdlX2NvbW1lbnRfbW9kZXJhdGlvbiI7aToxO30='));
}
$templates=array(
	'page.config',
	'page.info',
	'page.list',
	'page.comment.info',
	'page.comment.list'
);
foreach($templates as $v){
	$from=ROOT."/core/modules/page/templates/admin/".$v.".php";
	$to=ROOT."/core/themes/admin/".$v.".php";
	@copy($from,$to);
}