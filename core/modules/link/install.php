<?php
if(!defined('ROOT'))exit('Access denied!');
$query=array();
$query[]="CREATE TABLE IF NOT EXISTS `".DB_PREFIX."link` (
	  `link_id` tinyint(3) unsigned NOT NULL auto_increment,
	  `link_name` varchar(50) NOT NULL,
	  `link_text` varchar(255) NOT NULL,
	  `link_url` varchar(255) NOT NULL,
	  `link_sort` int(4) unsigned NOT NULL default '0',
	  `link_status` tinyint(1) unsigned NOT NULL default '0',
	  PRIMARY KEY  (`link_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
$query[]="INSERT INTO `db_link` (`link_id`, `link_name`, `link_text`, `link_url`, `link_sort`, `link_status`) VALUES(1, 'YCKIT', '', 'http://yckit.com', 1, 1),(2, '域名抢注', '', 'http://yumingqiangzhu.com', 1, 1);";

if(count($query)>0){
	foreach($query as $sql)$this->db->query($sql);
}
$templates=array('link.info','link.list');
foreach($templates as $v){
	$from=ROOT."/core/modules/link/templates/admin/".$v.".php";
	$to=ROOT."/core/themes/admin/".$v.".php";
	@copy($from,$to);
}