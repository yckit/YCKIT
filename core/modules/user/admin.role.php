<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->do=='role'){
	$this->check_access('user_role');
	$result=$this->db->result("SELECT * FROM ".DB_PREFIX."user_role ORDER BY role_id ASC");
	$array=array();
	foreach($result as $row){
		$array[$row['role_id']]['id']=$row['role_id'];
		$array[$row['role_id']]['name']=$row['role_name'];
		$array[$row['role_id']]['description']=$row['role_description'];
		$array[$row['role_id']]['status']=$row['role_status'];
	}
	$this->template->in("role",$array);
	$this->template->out('user.role.php');
}

if($this->do=='role_update'){
	#添加
	$role_name_new=isset($_POST['role_name_new'])?addslashes(trim($_POST['role_name_new'])):'';
	if(!empty($role_name_new)){
		$role_description_new=isset($_POST['role_description_new'])?addslashes(trim($_POST['role_description_new'])):'';
		$role_status_new=isset($_POST['role_status_new'])?intval($_POST['role_status_new']):0;
		$this->db->insert(DB_PREFIX."user_role",array(
			'role_name'=>$role_name_new,
			'role_description'=>$role_description_new,
			'role_status'=>$role_status_new
		));
	}
	#删除
	$delete_ids=isset($_POST['delete_id'])?$_POST['delete_id']:array();
	if(count($delete_ids)>0){
		foreach($delete_ids as $id){
			$this->db->delete(DB_PREFIX."user_role","role_id=".$id);
		}
	}
	#更新
	$ids=isset($_POST['role_id'])?$_POST['role_id']:array();
	$ids=array_diff($ids,$delete_ids);#计算ID差值

	if(count($ids)>0){
		foreach($ids as $id){
			if(!empty($id)){
				$this->db->update(DB_PREFIX."user_role",array(
					'role_name'=>trim($_POST['role_name'][$id]),
					'role_description'=>trim($_POST['role_description'][$id]),
					'role_status'=>intval($_POST['role_status'][$id])
				),"role_id=".$id);
			}
		}
	}
	redirect('?action=user&do=role');
}