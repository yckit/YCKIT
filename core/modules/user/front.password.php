<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->do=='password'){
	check_request();
	if(!$user->is_login())exit('ERROR:LOGIN');
	$this->template->template_dir='core/modules/user/templates/front';
	$this->template->out("user.password.php");
}
if($this->do=='password-check'){
	check_request();
	$user_key_old=empty($_POST['user_key_old'])?'':trim($_POST['user_key_old']);
	$user_key=empty($_POST['user_key'])?'':trim($_POST['user_key']);
	$sql="SELECT * FROM ".DB_PREFIX."user WHERE user_login='".$_SESSION['user_login']."' and user_key='".md5($user_key_old)."'";
	if(!$this->db->row($sql)){
		exit('当前密码不正确');
	}
	$this->db->update(DB_PREFIX."user","user_key='".md5($user_key)."'","user_id=".$_SESSION['user_id']);
	exit;
}