<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->do=='reset'){
	if(!empty($_GET['active'])){
		$active=trim($_GET['active']);
		if(strlen($active)!=32){
			exit('参数错误');
		}
	}
	$this->template->template_dir='core/modules/user/templates/front';
	$this->template->in('active',$active);
	$this->template->out("user.reset.php");
}
if($this->do=='reset-check'){
	check_request();
	$active=empty($_POST['active'])?'':trim($_POST['active']);
	$user_key=empty($_POST['user_key'])?'':md5(trim($_POST['user_key']));
	if(empty($active)){
		exit('无法识别您的身份');
	}
	if(empty($user_key)){
		exit('重置密码不能为空');
	}
	$row=$this->db->row("SELECT user_id FROM ".DB_PREFIX."user_active WHERE active_value='$active'");
	if($row){
		$this->db->update(DB_PREFIX."user","user_key='$user_key'","user_id=".$row['user_id']);
	}else{
		exit('重置失败');
	}
}