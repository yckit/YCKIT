<?php
if(!defined('ROOT'))exit('Access denied!');
#数据库删除表
$query=array();
$query[]="DROP TABLE ".DB_PREFIX."user;";
$query[]="DROP TABLE ".DB_PREFIX."user_role;";
$query[]="DROP TABLE ".DB_PREFIX."user_active;";

#执行命令
if(count($query)>0){
	foreach($query as $sql){
		$this->db->query($sql);
	}
}
#删除配置项
$this->db->delete(DB_PREFIX."config","config_type='user'");

$template_admin=array(
	'user.config',
	'user.info',
	'user.list',
	'user.role'
);
foreach($template_admin as $v){
	@unlink(ROOT."/core/themes/admin/".$v.".php");
}
rm_dir('./data/user');