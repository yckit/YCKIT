<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->do==''||$this->do=='config'){
	$this->check_access('global_admin');
	$this->template->out('global.config.php');
}
if($this->do=='config_update'){
	$this->check_access('global_config');
	$array=array();
	$array['title']=empty($_POST['title'])?'':trim($_POST['title']);
	$array['author']=empty($_POST['author'])?'':addslashes(trim($_POST['author']));
	$array['keywords']=empty($_POST['keywords'])?'':addslashes(trim($_POST['keywords']));
	$array['description']=empty($_POST['description'])?'':addslashes(trim($_POST['description']));
	$array['statcode']=empty($_POST['statcode'])?'':trim($_POST['statcode']);
	$array['notice']=empty($_POST['notice'])?'':trim($_POST['notice']);
	$array['smtp_server']=empty($_POST['smtp_server'])?'':addslashes(trim($_POST['smtp_server']));
	$array['smtp_user']=empty($_POST['smtp_user'])?'':addslashes(trim($_POST['smtp_user']));
	$array['smtp_password']=empty($_POST['smtp_server'])?'':addslashes(trim($_POST['smtp_password']));
	$array['smtp_port']=empty($_POST['smtp_server'])?'':addslashes(trim($_POST['smtp_port']));
	$array['smtp_mail']=empty($_POST['smtp_mail'])?'':addslashes(trim($_POST['smtp_mail']));

	$watermark_image=upload($_FILES['watermark_image'],'data/','png,jpg');
	$array['watermark_status']=intval($_POST['watermark_status']);
	$array['watermark_position']=intval($_POST['watermark_position']);
	if(!empty($watermark_image)){
		$array['watermark_image']=$watermark_image;
	}else{
		$array['watermark_image']=$_POST['watermark_image_old'];
	}
	if(!empty($_POST['watermark_image_delete'])){
		@unlink(ROOT.PATH.'data/'.$_POST['watermark_image_old']);
		$array['watermark_image']='';
	}

	$theme_config_include=ROOT.'/core/themes/'.$this->theme.'/config.php';
	if(file_exists($theme_config_include)){
		@include $theme_config_include;
	}
	$config_value=base64_encode(serialize($array));
	$this->db->update(DB_PREFIX."config",array('config_value'=>$config_value),"config_type='config'");
	clear_cache();
	redirect('?action=global&do=config');
}
if($this->do=='theme_config'){
	$this->template->template_dir=ROOT.'/core/themes/'.$this->theme;
	$this->template->out('config.template.php');
}