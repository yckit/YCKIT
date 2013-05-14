<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->do=='more'){
	check_request();
	$category_id=empty($_GET['category_id'])?0:intval($_GET['category_id']);
	$sql="SELECT * FROM ".DB_PREFIX."content_article WHERE article_is_display=1";
	if($category_id>0){
		$sql.=" AND category_id=$category_id";
	}
	$sql.="  ORDER BY article_id DESC";
	$page_size=isset($this->config['index_size'])?$this->config['index_size']:10;
	$page_current=isset($_GET['page'])&&is_numeric($_GET['page'])?intval($_GET['page']):2;
	$count=$this->db->count($sql);
	if($count>0){
		$page_count=@ceil($count/$page_size);
		$result=$this->db->result($sql." LIMIT ".(($page_current-1)*$page_size).",".$page_size);
		$array=array();
		if($count>0){
			foreach($result as $row){
				$array[$row['article_id']]['id']=$row['article_id'];
				$array[$row['article_id']]['title']=$row['article_title'];
				$array[$row['article_id']]['description']=$row['article_description'];
				$array[$row['article_id']]['content']=$content->get_short_content($row['article_content']);
				$array[$row['article_id']]['author']=$row['article_author'];
				$array[$row['article_id']]['image']=$row['article_image'];
				$array[$row['article_id']]['uri']=$content->uri($row['category_id'],$row['article_id'],$row['article_html']);
				$array[$row['article_id']]['time']=date("Y.m.d",$row['article_time']);
				$array[$row['article_id']]['timestamp']=$row['article_time'];
				$array[$row['article_id']]['comment_count']=$row['article_comment_count'];
				$array[$row['article_id']]['click_count']=$row['article_click_count'];
				$array[$row['article_id']]['category_id']=$row['category_id'];
				$array[$row['article_id']]['category_name']=$this->db->value(DB_PREFIX."content_category","category_name","category_id=".$row['category_id']);
				$array[$row['article_id']]['category_path']=$content->uri($row['category_id']);
				$array[$row['article_id']]['is_new']=$_SERVER['REQUEST_TIME']-$row['article_time']<3600*24*3?true:false;
				$array[$row['article_id']]['is_nofollow']=$row['article_is_nofollow'];
			}
			$pager=pager(get_self(),'',$page_current,$page_size,$count,'array');
		}
		$this->template->in('pager',$pager);
		$this->template->in('article',$array);
		$this->template->out('content.more.php');
	}else{
		exit('OVER');
	}
}
?>