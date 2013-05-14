<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->do=='comment'){
	$this->check_access('page_comment');
	$sql="SELECT * FROM ".DB_PREFIX."page_comment WHERE 1=1";
	if(!empty($_GET['page_id'])){
		$sql.=" AND page_id='".intval($_GET['page_id'])."'";
	}
	$sql.=" AND parent_id=0 ORDER BY comment_id DESC";
	$page_size=$this->config['page_admin_comment_list_size']?$this->config['page_admin_comment_list_size']:20;
	$page_current=isset($_GET['page'])&&is_numeric($_GET['page'])?intval($_GET['page']):1;
	$count=$this->db->count($sql);
	$res=$this->db->result($sql." limit ".(($page_current-1)*$page_size).",".$page_size);
	$array=array();
	$pager=array();
	if($count>0){
		foreach($res as $row){
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
			$array[$row['comment_id']]['time']=date("Y.m.d H:i:s",$row['comment_time']);
			$array[$row['comment_id']]['is_display']=$row['comment_is_display'];
			$array[$row['comment_id']]['page_id']=$row['page_id'];
			$array2=array();
			$result2=$this->db->result("SELECT * FROM ".DB_PREFIX."page_comment WHERE parent_id='".$row['comment_id']."'");
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
					$array2[$row2['comment_id']]['is_display']=$row2['comment_is_display'];
					$array2[$row2['comment_id']]['time']=date("Y/m/d H:i:s",$row2['comment_time']);
					$array2[$row2['comment_id']]['timestamp']=$row2['comment_time'];
				}
			}
			$array[$row['comment_id']]['children']=$array2;
		}
		$parameters="action=page&do=comment&";
		if(!empty($_GET['page_id'])){
			$parameters.="page_id=".intval($_GET['page_id'])."&";
		}
		$pager=pager(get_self(),$parameters,$page_current,$page_size,$count);
	}
	$this->template->in('comment',$array);
	$this->template->in('pager',$pager);
	$this->template->out('page.comment.list.php');
}
if($this->do=='comment_edit'){#编辑评论
	$this->check_access('page_comment');
	$comment_id=isset($_GET['comment_id'])?intval($_GET['comment_id']):'';
	$row=$this->db->row("SELECT * FROM ".DB_PREFIX."page_comment WHERE comment_id='$comment_id'");
	$comment=array();
	$comment['id']=$row['comment_id'];
	$comment['name']=$row['comment_name'];
	$comment['page_title']=$this->db->value(DB_PREFIX."page","page_title","page_id=".$row['page_id']);
	$comment['ip']=$row['comment_ip'];
	$comment['ip_address']=get_ip_address($row['comment_ip']);
	$comment['content']=$row['comment_content'];
	$comment['reply']=$row['comment_reply'];
	$comment['email']=$row['comment_email'];
	$comment['site']=$row['comment_site'];
	$comment['time']=date("Y-m-d h:i:s",$row['comment_time']);
	$comment['is_display']=$row['comment_is_display'];
	$comment['page_id']=$row['page_id'];
	$array=array();
	$sql="SELECT * FROM ".DB_PREFIX."page_comment WHERE parent_id=".$row['comment_id'];
	$res=$this->db->result($sql);
	foreach($res as $row){
		$array[$row['comment_id']]['id']=$row['comment_id'];
		$array[$row['comment_id']]['name']=$row['comment_name'];
		$array[$row['comment_id']]['content']=$row['comment_content'];
		$array[$row['comment_id']]['reply']=$row['comment_reply'];
		$array[$row['comment_id']]['email']=$row['comment_email'];
		$array[$row['comment_id']]['site']=$row['comment_site'];
		$array[$row['comment_id']]['ip']=$row['comment_ip'];
		$array[$row['comment_id']]['ip_address']=get_ip_address($row['comment_ip']);
		$array[$row['comment_id']]['agent']=$row['comment_agent'];
		$array[$row['comment_id']]['time']=date("Y.m.d H:i:s",$row['comment_time']);
		$array[$row['comment_id']]['is_display']=$row['comment_is_display'];
		$array[$row['comment_id']]['page_id']=$row['page_id'];
	}
	$this->template->in('comment',$comment);
	$this->template->in('array',$array);
	$this->template->in('mode','update');
	$this->template->out('page.comment.info.php');
}
if($this->do=='comment_update'){#更新评论
	$this->check_access('page_comment');
	$page_id=empty($_POST['page_id'])?0:intval($_POST['page_id']);
	$comment_id=empty($_POST['comment_id'])?0:intval($_POST['comment_id']);
	$comment_content=empty($_POST['comment_content'])?'':addslashes(trim($_POST['comment_content']));
	$comment_reply=empty($_POST['comment_reply'])?'':addslashes(trim($_POST['comment_reply']));
	$comment_is_display=empty($_POST['comment_is_display'])?0:1;
	$array=array();
	$array['comment_content']=$comment_content;
	$array['comment_reply']=$comment_reply;
	$array['comment_is_display']=$comment_is_display;
	$this->db->update(DB_PREFIX."page_comment",$array,"comment_id=$comment_id");
	if(!empty($_POST['comment_is_email'])){
		$to=trim($_POST['comment_email']);
		$subject="您在[".$this->config['title']."]的评论已经回复！";
		$page=$page->get_page_info($page_id);
		$content="评论页面：<a href='http://".$_SERVER['SERVER_NAME'].PATH.$page['html'].".html'>".$page['title']."</a><br />";
		$content.="您的评论：".$comment_content."<br />";
		$content.="管理回复：".$comment_reply."<br />";
		$subject=iconv('UTF-8','GB2312',$subject);
		$content=iconv('UTF-8','GB2312',$content);
		import('smtp');
		$smtp=new smtp($this->config['smtp_server'],$this->config['smtp_port'],true,$this->config['smtp_user'],$this->config['smtp_password']);
		$smtp->send($to,$this->config['smtp_mail'],$subject,$content,'HTML');
	}
	redirect('?action=page&do=comment');
}
if($this->do=='comment_delete'){#删除内容
	$this->check_access('page_comment');
	$comment_id=empty($_POST['comment_id'])?array():$_POST['comment_id'];
	foreach($comment_id as $value){
		if(!empty($value)){
			$this->db->delete(DB_PREFIX."page_comment","comment_id=".$value);
		}
	}
	clear_cache();
	redirect('?action=page&do=comment');
}
if($this->do=='comment_audit'){
	$this->check_access('page_comment');
	$comment_id=empty($_POST['comment_id'])?array():implode(",",$_POST['comment_id']);
	if(count($comment_id)>0){
		$this->db->query("UPDATE ".DB_PREFIX."page_comment SET comment_is_display=1 WHERE comment_id IN (".$comment_id.")");
	}
	redirect('?action=page&do=comment');
}