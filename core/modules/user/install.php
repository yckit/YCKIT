<?php 
if(!defined('ROOT'))exit('Access denied!');
#数据库创建表
$query=array();
$query[]="CREATE TABLE IF NOT EXISTS `".DB_PREFIX."user` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(50) NOT NULL DEFAULT '',
  `user_key` varchar(32) NOT NULL DEFAULT '',
  `user_nickname` varchar(10) NOT NULL DEFAULT '',
  `user_point` int(4) unsigned NOT NULL DEFAULT '0',
  `user_join_time` int(10) unsigned NOT NULL DEFAULT '0',
  `user_login_time` int(10) unsigned NOT NULL DEFAULT '0',
  `user_login_ip` varchar(15) NOT NULL DEFAULT '',
  `user_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `role_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `open_id` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_login` (`user_login`),
  UNIQUE KEY `user_nickname` (`user_nickname`),
  KEY `open_id` (`open_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

$query[]="CREATE TABLE IF NOT EXISTS `".DB_PREFIX."user_active` (
  `active_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `active_value` varchar(32) NOT NULL DEFAULT '',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`active_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

$query[]="CREATE TABLE IF NOT EXISTS `".DB_PREFIX."user_role` (
  `role_id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(20) NOT NULL DEFAULT '',
  `role_description` varchar(100) NOT NULL DEFAULT '',
  `role_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

 
#执行命令
if(count($query)>0){
	foreach($query as $sql){
		$this->db->query($sql);
	}
}
#检查写入配置
$row=$this->db->row("SELECT config_type FROM ".DB_PREFIX."config WHERE config_type='user' LIMIT 0,1");
if(empty($row['config_type'])){
	$this->db->insert(DB_PREFIX."config",array('config_type'=>'user','config_value'=>'YTo3OntzOjE2OiJ1c2VyX2Zyb250X2xvZ2luIjtpOjE7czoxNToidXNlcl9mcm9udF9qb2luIjtpOjE7czoyMjoidXNlcl9mcm9udF9qb2luX2FjdGl2ZSI7aTowO3M6MjA6InVzZXJfYWRtaW5fbGlzdF9zaXplIjtpOjIwO3M6ODoicXFfYXBwaWQiO3M6MDoiIjtzOjk6InFxX2FwcGtleSI7czowOiIiO3M6MTQ6InFxX2FwcGNhbGxiYWNrIjtzOjUwOiJodHRwOi8veWNraXQuY29tL2Zyb250LnBocD9hY3Rpb249dXNlciZkbz1jYWxsYmFjayI7fQ=='));
}

$template_admin=array('user.config','user.info','user.list','user.role');
foreach($template_admin as $v){
	@copy(ROOT."/core/modules/user/templates/admin/".$v.".php",ROOT."/core/themes/admin/".$v.".php");
}

mk_dir(ROOT.'/data/user');