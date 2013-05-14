<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->do=='forget'){
	$this->template->template_dir='core/modules/user/templates/front';
	$this->template->out("user.forget.php");
}
if($this->do=='forget-check'){
	check_request();
	$user_login=empty($_POST['user_login'])?'':trim(addslashes($_POST['user_login']));
	if(empty($user_login)){
		exit('E-MAIL地址不能为空');
	}
	$row=$this->db->row("SELECT user_id FROM ".DB_PREFIX."user WHERE user_login='$user_login'");
	if($row){
		$active_value=md5($user_login.$_SERVER['REQUEST_TIME']);
		$active=array();
		$active['active_value']=$active_value;
		$row2=$this->db->row("SELECT user_id FROM ".DB_PREFIX."user_active WHERE user_id='".$row['user_id']."'");
		if($row2){
			$this->db->update(DB_PREFIX."user_active",$active,'user_id='.$row['user_id']);
		}else{
			$active['user_id']=$row['user_id'];
			$this->db->insert(DB_PREFIX."user_active",$active);
		}
		$subject=iconv('UTF-8','GB2312',$this->config['title']."提醒您：请您重置密码！");
		$content=iconv('UTF-8','GB2312','<a href="http://'.$_SERVER['SERVER_NAME'].PATH.'#reset:'.$active_value.'">重置密码</a>');
		import('smtp');
		$smtp=new smtp($this->config['smtp_server'],$this->config['smtp_port'],true,$this->config['smtp_user'],$this->config['smtp_password']);
		if($smtp->send($user_login,$this->config['smtp_mail'],$subject,$content,'HTML')){

		}
	}else{
		exit('系统无法识别您的E-mail');
	}
}