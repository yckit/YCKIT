<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->do=='click'){
	check_request();
	$article_id=empty($_GET['article_id'])?exit('Parameters are missing...'):intval($_GET['article_id']);
	$this->db->update(DB_PREFIX."content_article","article_click_count=article_click_count+1","article_id=$article_id");
	$click_count=$this->db->value(DB_PREFIX."content_article","article_click_count","article_id=$article_id LIMIT 0,1");
	exit(number_format($click_count));
}
?>