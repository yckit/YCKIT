<?php 
if(!defined('ROOT'))exit('Access denied!');
$query=array();
$query[]="DROP TABLE ".DB_PREFIX."page;";
$query[]="DROP TABLE ".DB_PREFIX."page_comment;";
if(count($query)>0){
	foreach($query as $sql)$this->db->query($sql);
}
#删除配置项
$this->db->delete(DB_PREFIX."config","config_type='page'");
$templates=array(
	'page.config',
	'page.info',
	'page.list',
	'page.comment.info',
	'page.comment.list'
);
foreach($templates as $v){
	@unlink(ROOT."/core/themes/admin/".$v.".php");
}