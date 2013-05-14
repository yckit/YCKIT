<?php
if(!defined('ROOT'))exit('Access denied!');
require_once ROOT.'/core/modules/content/class.content.php';
$content=new content_class();
if($this->keyword==''){
	$sql="SELECT * FROM ".DB_PREFIX."content_article WHERE article_is_display=1 AND article_keywords like '%".$this->tag."%'  ORDER BY article_id DESC";
}else{
	$sql="SELECT * FROM ".DB_PREFIX."content_article WHERE article_is_display=1 AND article_title like '%".$this->keyword."%'  ORDER BY article_id DESC";
}
$array=array();
$page_size=$this->config['content_front_search_size'];
$page_current=isset($_REQUEST['page'])&&is_numeric($_REQUEST['page'])?intval($_REQUEST['page']):1;
$count=$this->db->count($sql);
if($count>0){
	$page_count=ceil($count/$page_size);
	$result=$this->db->result($sql." LIMIT ".(($page_current-1)*$page_size).",".$page_size);
	foreach($result as $row){
		$array[$row['article_id']]['id']=$row['article_id'];
		$array[$row['article_id']]['title']=str_replace($this->keyword,"<span style='font-weight:bold;color:red'>".$this->keyword."</span>",$row['article_title']);
		$array[$row['article_id']]['description']=$row['article_description'];
		$array[$row['article_id']]['content']=$content->get_short_content($row['article_content']);
		$array[$row['article_id']]['image']=$row['article_image'];
		$array[$row['article_id']]['author']=$row['article_author'];
		$array[$row['article_id']]['click_count']=number_format($row['article_click_count']);
		$array[$row['article_id']]['comment_count']=number_format($row['article_comment_count']);
		$array[$row['article_id']]['category_name']=$this->db->value(DB_PREFIX."content_category","category_name","category_id=".$row['category_id']);
		$array[$row['article_id']]['category_path']=$content->uri($row['category_id']);
		$array[$row['article_id']]['category_id']=$row['category_id'];
		$array[$row['article_id']]['is_nofollow']=$row['article_is_nofollow'];
		$array[$row['article_id']]['uri']=$content->uri($row['category_id'],$row['article_id'],$row['article_html']);
		$array[$row['article_id']]['time']=$row['article_time'];
	}
	$pager=pager(get_self(),'search.php?action=content&keyword='.urlencode($this->keyword),$page_current,$page_size,$count,'array');
}else{
	$pager="";
}
$this->template->in('pager',$pager);
$this->template->in('keyword',($this->keyword==''?$this->tag:$this->keyword));
$this->template->in('article',$array);
$this->template->in('article_click',$content->get_articles(array('limit'=>$this->config['click_size'],'orderby'=>'article_click_count')));
$this->template->in('article_best',$content->get_articles(array('limit'=>$this->config['best_size'],'orderby'=>'article_click_count')));
$this->template->out('content.search.php');