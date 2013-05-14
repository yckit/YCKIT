<?php 
if(!defined('ROOT'))exit('Access denied!');
$query=array();
$query[]="CREATE TABLE IF NOT EXISTS `".DB_PREFIX."content_article` (
  `article_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `article_title` varchar(255) NOT NULL,
  `article_content_mode` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `article_content` text NOT NULL,
  `article_author` varchar(50) NOT NULL DEFAULT '',
  `article_keywords` varchar(255) NOT NULL DEFAULT '',
  `article_description` varchar(255) NOT NULL DEFAULT '',
  `article_image` varchar(255) NOT NULL,
  `article_file` varchar(1000) NOT NULL DEFAULT '',
  `article_html` varchar(255) NOT NULL DEFAULT '',
  `article_click_count` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `article_comment_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `article_time` int(10) unsigned NOT NULL DEFAULT '0',
  `article_is_best` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `article_is_comment` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `article_is_nofollow` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `article_is_display` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `category_id` smallint(3) unsigned NOT NULL DEFAULT '0',
  `user_id` int(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`article_id`),
  KEY `article_title` (`article_title`,`article_author`,`category_id`,`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

$query[]="CREATE TABLE IF NOT EXISTS `".DB_PREFIX."content_category` (
  `category_id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) NOT NULL,
  `category_dir` varchar(50) NOT NULL DEFAULT '',
  `category_keywords` varchar(50) NOT NULL DEFAULT '',
  `category_description` varchar(255) NOT NULL,
  `category_list_limit` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `category_deep` int(4) unsigned NOT NULL DEFAULT '0',
  `category_sort` tinyint(3) NOT NULL DEFAULT '0',
  `category_image_width` int(4) unsigned NOT NULL DEFAULT '0',
  `category_image_height` int(4) unsigned NOT NULL DEFAULT '0',
  `category_is_display` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `parent_id` smallint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

$query[]="CREATE TABLE IF NOT EXISTS `".DB_PREFIX."content_comment` (
  `comment_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
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
  `article_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `article_id` (`article_id`,`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";


$query[]="CREATE TABLE IF NOT EXISTS `".DB_PREFIX."content_draft` (
  `draft_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `draft_title` varchar(255) NOT NULL DEFAULT '',
  `draft_content` text NOT NULL,
  `draft_author` varchar(50) NOT NULL DEFAULT '',
  `draft_time` int(10) unsigned NOT NULL DEFAULT '0',
  `category_id` smallint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`draft_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
 
$query[]="CREATE TABLE IF NOT EXISTS `".DB_PREFIX."content_field` (
  `field_id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `field_name` varchar(20) NOT NULL DEFAULT '',
  `field_text` varchar(20) NOT NULL DEFAULT '',
  `field_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `category_id` smallint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`field_id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

$query[]="CREATE TABLE IF NOT EXISTS `".DB_PREFIX."content_field_data` (
  `data_id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `data_value` text NOT NULL,
  `article_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `field_id` int(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`data_id`),
  KEY `article_id` (`article_id`,`field_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
 
if(count($query)>0){
  foreach($query as $sql){
    $this->db->query($sql);
  }
}
$row=$this->db->row("SELECT config_type FROM ".DB_PREFIX."config WHERE config_type='content' LIMIT 0,1");
if(empty($row['config_type'])){
	$this->db->insert(DB_PREFIX."config",array('config_type'=>'content','config_value'=>'YTo4OntzOjMxOiJjb250ZW50X2Zyb250X2NvbW1lbnRfbGlzdF9zaXplIjtzOjI6IjEwIjtzOjI1OiJjb250ZW50X2Zyb250X3NlYXJjaF9zaXplIjtpOjEwO3M6MzE6ImNvbnRlbnRfYWRtaW5fYXJ0aWNsZV9saXN0X3NpemUiO3M6MjoiMTAiO3M6MzE6ImNvbnRlbnRfYWRtaW5fY29tbWVudF9saXN0X3NpemUiO3M6MjoiMTAiO3M6Mjk6ImNvbnRlbnRfYWRtaW5fZHJhZnRfbGlzdF9zaXplIjtzOjI6IjEwIjtzOjIwOiJjb250ZW50X2RyYWZ0X3N0YXR1cyI7aTowO3M6MjY6ImNvbnRlbnRfY29tbWVudF9tb2RlcmF0aW9uIjtpOjA7czoxMjoiY29udGVudF9tb2RlIjtpOjA7fQ=='));
}

$templates=array(
	'content.config',
	'content.article.info',
	'content.article.list',
	'content.comment.info',
	'content.comment.list',
	'content.category.info',
	'content.category.list',
	'content.draft.list'
);
foreach($templates as $v){
	$from=ROOT."/core/modules/content/templates/admin/".$v.".php";
	$to=ROOT."/core/themes/admin/".$v.".php";
	@copy($from,$to);
}
$source=file_get_contents(ROOT."/core/modules/content/templates/content.php");
$source=str_replace('<?php exit?>','',$source);
@file_put_contents(ROOT."/content.php", $source);
mk_dir(ROOT."/data/content");