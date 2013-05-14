<?php
if(!defined('ROOT'))exit('Access denied!');
$link_list=array();
$result=$this->db->result("SELECT * FROM ".DB_PREFIX."link WHERE link_status=1 ORDER BY link_sort ASC,link_id DESC");
if($result){
	foreach($result as $row){
		$link_list[$row['link_id']]['id']=$row['link_id'];
		$link_list[$row['link_id']]['name']=$row['link_name'];
		$link_list[$row['link_id']]['url']=$row['link_url'];
		$link_list[$row['link_id']]['text']=$row['link_text'];
	}
}

unset($result);
$this->template->in('link_list',$link_list);
unset($link_list);