<?php
if(!defined('ROOT'))exit('Access denied!');

$query=array();
$query[]="DROP TABLE ".DB_PREFIX."link;";
 
if(count($query)>0){
	foreach($query as $sql)$this->db->query($sql);
}
$templates=array(
	'link.info',
	'link.list'
);
foreach($templates as $v){
	@unlink(ROOT."/core/themes/admin/".$v.".php");
}