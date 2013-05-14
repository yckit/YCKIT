<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->do=='active'){
	if(empty($_GET['active'])){
		exit('参数错误');
	}else{
		$active=trim($_GET['active']);
		if(strlen($active)!=32){
			exit('参数错误');
		}
		$row=$this->db->row("SELECT user_id FROM ".DB_PREFIX."user_active WHERE active_value='$active'");
		if($row[0]>0){
			$this->db->update(DB_PREFIX."user","user_status=1","user_id=".$row[0]);
		}
	}
}