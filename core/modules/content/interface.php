<?php
if(!defined('ROOT'))exit('Access denied!');
require_once ROOT.'/core/modules/content/class.content.php';
$content=new content_class();
$comment_limit=isset($this->config['comment_size'])?$this->config['comment_size']:10;
$array=array();
$sql="SELECT * FROM ".DB_PREFIX."content_comment WHERE comment_is_display=1 ORDER BY comment_id DESC LIMIT 0,".$comment_limit;
$result=$this->db->result($sql);
if($result){
	foreach($result as $row){
		$article_info=$this->db->row("SELECT category_id,article_html,article_id FROM ".DB_PREFIX."content_article WHERE article_id=".$row['article_id']);
		$array[$row['comment_id']]['uri']=$content->uri($article_info['category_id'],$row['article_id'],$article_info['article_html']);
		$array[$row['comment_id']]['id']=$row['comment_id'];
		$array[$row['comment_id']]['name']=$row['comment_name'];
		$array[$row['comment_id']]['site']=$row['comment_site'];
		$array[$row['comment_id']]['email']=$row['comment_email'];
		if(!file_exists("data/user/".$row['user_id'].".jpg")){
			$array[$row['comment_id']]['avatar']=PATH.'core/images/avatar-comment.png';	
		}else{
			$array[$row['comment_id']]['avatar']=PATH.'data/user/'.$row['user_id'].'.jpg';
		}
		$array[$row['comment_id']]['content']=$row['comment_content'];
		$array[$row['comment_id']]['ip_address']=get_ip_address($row['comment_ip']);
		$array[$row['comment_id']]['time']=$row['comment_time'];
	}
}
$this->template->in('comment_list',$array);


$reader_size=isset($this->config['reader_size'])?$this->config['reader_size']:10;
$readers=array();
$result=$this->db->result("SELECT COUNT(comment_name) as count,`comment_email`,`comment_site` ,`comment_name`,`comment_ip` FROM `db_content_comment` GROUP BY `comment_name` ORDER BY count DESC LIMIT $reader_size");
if($result){
	foreach($result as $key=>$row){
		$readers[$key]['name']=$row['comment_name'];
		$readers[$key]['gravatar']='http://www.gravatar.com/avatar/'.md5(strtolower($row['comment_email']));
		$readers[$key]['ip_address']=get_ip_address($row['comment_ip']);
		$readers[$key]['site']=$row['comment_site'];
		$readers[$key]['count']=$row['count'];
	}
}
$this->template->in('readers',$readers);

if(isset($this->config['content_tags'])){
	$tags=explode(",",$this->config['content_tags']);
	if($tags!==false){
		$this->template->in('tags',$tags);
	}
}