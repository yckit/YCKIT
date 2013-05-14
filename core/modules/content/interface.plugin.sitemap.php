<?php
if(!defined('ROOT'))exit('Access denied!');
require_once ROOT.'/core/modules/content/class.content.php';$content=new content_class();
$array=array();
$result=$this->db->result("SELECT * FROM ".DB_PREFIX."content_article WHERE article_is_display=1 ORDER BY article_time DESC");
if($result){
	foreach($result as $row){
		$array[$row['article_id']]['loc']=get_url().$content->uri($row['category_id'],$row['article_id'],$row['article_html']);
		$array[$row['article_id']]['lastmod']=date('c',$row['article_time']);
	}
}
return $array;