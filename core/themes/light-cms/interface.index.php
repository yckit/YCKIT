<?php
if(!isset($this->config['index_category']))exit('系统需要您重新配置[全局设置]');
require_once ROOT.'/core/modules/content/class.content.php';$content=new content_class();
$array=array();

$result=$this->db->result("SELECT * FROM ".DB_PREFIX."content_category WHERE category_id IN (".$this->config['index_category'].")");
if($result){
	foreach ($result as $row) {
		$array[$row['category_id']]['id']=$row['category_id'];
		$array[$row['category_id']]['name']=$row['category_name'];
		$array[$row['category_id']]['uri']=$content->uri($row['category_id']);
		$array2=array();
		$result2=$this->db->result("SELECT * FROM ".DB_PREFIX."content_article WHERE category_id='".$row['category_id']."' AND article_is_display=1 ORDER BY article_id DESC LIMIT 0,".$this->config['index_article_size']);
		if($result2){
			foreach ($result2 as $row2) {
				$array2[$row2['article_id']]['id']=$row2['article_id'];
				$array2[$row2['article_id']]['title']=$row2['article_title'];
				$array2[$row2['article_id']]['description']=$row2['article_description'];
				$array2[$row2['article_id']]['author']=$row2['article_author'];
				$array2[$row2['article_id']]['image']=$row2['article_image'];
				$array2[$row2['article_id']]['content']=$content->get_short_content($row2['article_content']);
				$array2[$row2['article_id']]['uri']=$content->uri($row2['category_id'],$row2['article_id'],$row2['article_html']);
				$array2[$row2['article_id']]['timestamp']=$row2['article_time'];
				$array2[$row2['article_id']]['time']=$row2['article_time'];
				$array2[$row2['article_id']]['comment_count']=$row2['article_comment_count'];
				$array2[$row2['article_id']]['click_count']=$row2['article_click_count'];
				$array2[$row2['article_id']]['category_id']=$row2['category_id'];
				$array2[$row2['article_id']]['is_nofollow']=$row2['article_is_nofollow'];
				$array2[$row2['article_id']]['is_new']=$_SERVER['REQUEST_TIME']-$row2['article_time']<3600*24*3?true:false;
				$array2[$row2['article_id']]['category_name']=$this->db->value(DB_PREFIX."content_category","category_name","category_id=".$row2['category_id']);
				$array2[$row2['article_id']]=array_merge($array2[$row2['article_id']],$content->get_fields($row2['category_id'],$row2['article_id']));//add 2012/11/7
			}
		}
		$array[$row['category_id']]['articles']=$array2;
	}
}

$this->template->in('index_category',$array);