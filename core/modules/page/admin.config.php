<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->do=='config'){
	$this->check_access('page_config');
	$this->template->out('page.config.php');
}
if($this->do=='config_update'){
	$this->check_access('page_config');
	$array=array();
	$array['page_front_comment_list_size']=empty($_POST['page_front_comment_list_size'])?'':addslashes(trim($_POST['page_front_comment_list_size']));
	$array['page_admin_comment_list_size']=empty($_POST['page_admin_comment_list_size'])?'':addslashes(trim($_POST['page_admin_comment_list_size']));
	$array['page_comment_moderation']=intval($_POST['page_comment_moderation']);
	$config_value=base64_encode(serialize($array));
	$this->db->update(DB_PREFIX."config",array('config_value'=>$config_value),"config_type='page'");
	clear_cache();
	redirect('?action=page&do=config');
}