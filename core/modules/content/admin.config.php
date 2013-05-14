<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->do=='config'){
	$this->check_access('content_config');
	$this->template->out('content.config.php');
}
if($this->do=='config_update'){
	$this->check_access('content_config');
	$array=array();
	$array['content_front_comment_list_size']=empty($_POST['content_front_comment_list_size'])?'':addslashes(trim($_POST['content_front_comment_list_size']));
	$array['content_front_search_size']=empty($_POST['content_front_search_size'])?9:intval($_POST['content_front_search_size']);
	$array['content_admin_article_list_size']=empty($_POST['content_admin_article_list_size'])?'':addslashes(trim($_POST['content_admin_article_list_size']));
	$array['content_admin_comment_list_size']=empty($_POST['content_admin_comment_list_size'])?'':addslashes(trim($_POST['content_admin_comment_list_size']));
	$array['content_admin_draft_list_size']=empty($_POST['content_admin_draft_list_size'])?'':addslashes(trim($_POST['content_admin_draft_list_size']));
	$array['content_draft_status']=intval(@$_POST['content_draft_status']);
	$array['content_comment_moderation']=intval(@$_POST['content_comment_moderation']);
	$array['content_mode']=intval(@$_POST['content_mode']);
	$config_value=base64_encode(serialize($array));
	$this->db->update(DB_PREFIX."config",array('config_value'=>$config_value),"config_type='content'");
	clear_cache();
	redirect('?action=content&do=config');
}