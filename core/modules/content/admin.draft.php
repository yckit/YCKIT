<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->do=='draft'){
	$this->check_access('content_draft');
	$array=array();
	$sql="SELECT * FROM ".DB_PREFIX."content_draft";
	$page_size=30;
	$page_current=isset($_GET['page'])?intval($_GET['page']):1;
	$count=$this->db->count($sql);
	$res=$this->db->result($sql." order by draft_id desc limit ".(($page_current-1)*$page_size).",".$page_size);
	if($count>0){
			$no=$count-(($page_current-1)*$page_size);
			foreach($res as $row){
				$array[$row['draft_id']]['no']=$no;
				$array[$row['draft_id']]['id']=$row['draft_id'];
				$array[$row['draft_id']]['title']=$row['draft_title'];
				$array[$row['draft_id']]['author']=$row['draft_author'];
				$array[$row['draft_id']]['time']=date('Y-m-d H:i:s',$row['draft_time']);
				$array[$row['draft_id']]['category_id']=$row['category_id'];
				$array[$row['draft_id']]['category_name']=$this->db->value(DB_PREFIX."content_category","category_name","category_id=".$row['category_id']);
				$no--;
			}
			$pager=pager(get_self(),"action=content&do=draft&",$page_current,$page_size,$count);
	}else{
			$pager="";
	}
	$this->template->in('draft',$array);
	$this->template->in('pager',$pager);
	$this->template->out('content.draft.list.php');
}
if($this->do=='draft_delete'){
	$this->check_access('content_draft');
	$draft_id=empty($_POST['draft_id'])?array():$_POST['draft_id'];
	foreach($draft_id as $value){
		if(!empty($value)){
			$this->db->delete(DB_PREFIX."content_draft","draft_id=".$value);
		}
	}
	redirect('?action=content&do=draft');
}