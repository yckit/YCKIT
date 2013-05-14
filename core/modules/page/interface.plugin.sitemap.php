<?php
if(!defined('ROOT'))exit('Access denied!');
$array=array();
$result=$this->db->result("SELECT * FROM ".DB_PREFIX."page WHERE page_is_display=1 ORDER BY page_id DESC");
if($result){
	foreach($result as $row){
		$array[$row['page_id']]['loc']=get_url().$row['page_html'].'.html';
		$array[$row['page_id']]['lastmod']=date('c');
	}
}
return $array;