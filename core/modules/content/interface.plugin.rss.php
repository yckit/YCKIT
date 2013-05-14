<?php
if(!defined('ROOT'))exit('Access denied!');
require_once ROOT.'/core/modules/content/class.content.php';$content=new content_class();

$array=array();
$result=$this->db->result("SELECT * FROM ".DB_PREFIX."content_article WHERE article_is_display=1 ORDER BY article_time DESC LIMIT 0,20");
if($result){
	foreach($result as $row){
		$array[$row['article_id']]['title']=$row['article_title'];
		$array[$row['article_id']]['uri']=get_url().$content->uri($row['category_id'],$row['article_id'],$row['article_html']);
		$array[$row['article_id']]['date']=date('c',$row['article_time']);
		$array[$row['article_id']]['category']=$this->db->value(DB_PREFIX."content_category","category_name","category_id=".$row['category_id']);
		$array[$row['article_id']]['description']=$row['article_content'];
	}
}
return $array;