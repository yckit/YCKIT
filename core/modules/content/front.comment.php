<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->do=='comment'){
	check_request();
	$article_id=empty($_GET['article_id'])?0:intval($_GET['article_id']);
	$sql="SELECT * FROM ".DB_PREFIX."content_comment WHERE comment_is_display=1 AND parent_id=0";
	if($article_id>0){
		$sql.=" AND article_id=$article_id";
	}
	$sql.=" AND parent_id=0 ORDER BY comment_id DESC";

	$page_size=$this->config['content_front_comment_list_size']?$this->config['content_front_comment_list_size']:10;
	$page_current=isset($_GET['page'])&&is_numeric($_GET['page'])?intval($_GET['page']):1;
	$count=$this->db->count($sql);
	$result=$this->db->result($sql." LIMIT ".(($page_current-1)*$page_size).",".$page_size);
	$array=array();
	$pager=array();
	if($count>0){
		$no=$count-(($page_current-1)*$page_size);
		foreach($result as $row){
			$array[$row['comment_id']]['no']=$no;
			$array[$row['comment_id']]['id']=$row['comment_id'];
			$array[$row['comment_id']]['name']=$row['comment_name'];
			$array[$row['comment_id']]['content']=$row['comment_content'];
			$array[$row['comment_id']]['reply']=$row['comment_reply'];
			$array[$row['comment_id']]['email']=$row['comment_email'];
			if(!file_exists("data/user/".$row['user_id'].".jpg")){
				$array[$row['comment_id']]['avatar']=PATH.'core/images/avatar-comment.png';	
			}else{
				$array[$row['comment_id']]['avatar']=PATH.'data/user/'.$row['user_id'].'.jpg';
			}
			$array[$row['comment_id']]['site']=$row['comment_site'];
			$array[$row['comment_id']]['ip']=$row['comment_ip'];
			$array[$row['comment_id']]['ip_address']=get_ip_address($row['comment_ip']);
			$array[$row['comment_id']]['os']=get_os($row['comment_agent']);
			$array[$row['comment_id']]['bs']=get_bs($row['comment_agent']);
			
			$array[$row['comment_id']]['time']=date("Y/m/d H:i:s",$row['comment_time']);
			$array[$row['comment_id']]['timestamp']=$row['comment_time'];
			$array2=array();
			$result2=$this->db->result("SELECT * FROM ".DB_PREFIX."content_comment WHERE comment_is_display=1 AND parent_id='".$row['comment_id']."'");
			if($result2){
				foreach($result2 as $row2){
					$array2[$row2['comment_id']]['id']=$row2['comment_id'];
					$array2[$row2['comment_id']]['name']=$row2['comment_name'];
					$array2[$row2['comment_id']]['content']=$row2['comment_content'];
					$array2[$row2['comment_id']]['reply']=$row2['comment_reply'];
					$array2[$row2['comment_id']]['email']=$row2['comment_email'];
					if(!file_exists("data/user/".$row2['user_id'].".jpg")){
						$array2[$row2['comment_id']]['avatar']=PATH.'core/images/avatar-comment.png';	
					}else{
						$array2[$row2['comment_id']]['avatar']=PATH.'data/user/'.$row2['user_id'].'.jpg';
					}
					$array2[$row2['comment_id']]['site']=$row2['comment_site'];
					$array2[$row2['comment_id']]['ip']=$row2['comment_ip'];
					$array2[$row2['comment_id']]['ip_address']=get_ip_address($row2['comment_ip']);
					$array2[$row2['comment_id']]['os']=get_os($row2['comment_agent']);
					$array2[$row2['comment_id']]['bs']=get_bs($row2['comment_agent']);
					$array2[$row2['comment_id']]['time']=date("Y/m/d H:i:s",$row2['comment_time']);
					$array2[$row2['comment_id']]['timestamp']=$row2['comment_time'];
				}
			}
			$array[$row['comment_id']]['children']=$array2;
			$no--;
		}
		$parameters="action=content&do=comment&";
		if($article_id>0){
			$parameters.="article_id=".$article_id."&";
		}
		$pager=pager(PATH.'front.php',$parameters,$page_current,$page_size,$count,'array');
	}
	$this->template->in('article_id',$article_id);
	$this->template->in('count',$count);
	$this->template->in('comment',$array);
	$this->template->in('pager',$pager);
	$this->template->out('content.comment.php');
}
if($this->do=='comment_insert'){
	check_request();
	$comment_name=empty($_GET['comment_name'])?'':addslashes(trim($_GET['comment_name']));
	$comment_email=empty($_GET['comment_email'])?'':addslashes(trim($_GET['comment_email']));
	$comment_site=empty($_GET['comment_site'])?'':addslashes(trim($_GET['comment_site']));
	$comment_content=empty($_GET['comment_content'])?'':addslashes(trim($_GET['comment_content']));

	$article_id=empty($_GET['article_id'])?0:intval($_GET['article_id']);
	$parent_id=empty($_GET['parent_id'])?0:intval($_GET['parent_id']);
	$this->set_hook('front.content.comment');
	$array=array();
	$array['comment_name']=strip_tags($comment_name);
	$array['comment_email']=strip_tags($comment_email);
	$array['comment_site']=strip_tags($comment_site);
	$array['comment_content']=$comment_content;
	$array['comment_reply']='';
	$array['comment_ip']=get_ip();
	$array['comment_time']=$_SERVER['REQUEST_TIME'];
	$array['comment_agent']=addslashes($_SERVER['HTTP_USER_AGENT']);
	$array['comment_is_display']=$this->config['content_comment_moderation']==1?0:1;
	$array['parent_id']=$parent_id;
	$array['article_id']=$article_id;
	$array['user_id']=intval($_SESSION['user_id']);
	$this->db->insert(DB_PREFIX."content_comment",$array);
	if($article_id>0){
		$this->db->update(DB_PREFIX."content_article","article_comment_count=article_comment_count+1","article_id=$article_id");
	}
	if($this->config['content_comment_moderation']==1){
		exit('WAIT');
	}
}
?>