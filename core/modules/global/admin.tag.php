<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->do=='tag'){
	$this->check_access('global_tag');
	$sql="SELECT * FROM ".DB_PREFIX."tag WHERE 1=1";
	if(!empty($_GET['keyword'])){
		$sql.=" AND tag_name like '%".trim($_GET['keyword'])."%'";
	}
	$sql.=" ORDER BY tag_id DESC";
	$page_size=15;
	$page_current=isset($_GET['page'])&&is_numeric($_GET['page'])?intval($_GET['page']):1;
	$count=$this->db->count($sql);
	$res=$this->db->result($sql." limit ".(($page_current-1)*$page_size).",".$page_size);
	$array=array();
	if($count>0){
		foreach($res as $row){
			$array[$row['tag_id']]['id']=$row['tag_id'];
			$array[$row['tag_id']]['name']=$row['tag_name'];
			$array[$row['tag_id']]['link']=$row['tag_link'];
		}
		$parameter='action=content&do=tag&';
		if(!empty($_GET['keyword'])){
			$parameter.="keyword='".trim($_GET['keyword'])."'";
		}
		$pager=pager(get_self(),$parameter,$page_current,$page_size,$count);
	}else{
		$pager="";
	}
	$this->template->in('tag',$array);
	$this->template->in('pager',$pager);
	$this->template->out('global.tag.list.php');
}
if($this->do=='tag_insert'){
	$this->check_access('global_tag');
	$tag_name=empty($_GET['tag_name'])?'':trim($_GET['tag_name']);
	$tag_link=empty($_GET['tag_link'])?'':trim($_GET['tag_link']);
	$array=array();
	$array['tag_name']=$tag_name;
	$array['tag_link']=$tag_link;
	$this->db->insert(DB_PREFIX."tag",$array);
}
if($this->do=='tag_update'){
	$this->check_access('global_tag');
	$tag_id=empty($_GET['tag_id'])?'':trim($_GET['tag_id']);
	$tag_name=empty($_GET['tag_name'])?'':trim($_GET['tag_name']);
	$tag_link=empty($_GET['tag_link'])?'':trim($_GET['tag_link']);
	$array=array();
	$array['tag_name']=$tag_name;
	$array['tag_link']=$tag_link;
	$this->db->update(DB_PREFIX."tag",$array,'tag_id='.$tag_id);
}
if($this->do=='tag_delete'){
	$this->check_access('global_tag');
	$tag_id=empty($_POST['tag_id'])?array():$_POST['tag_id'];
	foreach($tag_id as $v){
		if(!empty($v)){
			$this->db->delete(DB_PREFIX."tag","tag_id=".$v."");
		}
	}
	redirect('?action=global&do=tag');
}