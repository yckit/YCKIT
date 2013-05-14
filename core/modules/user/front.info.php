<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->do=='info'){
	check_request();
	$this->template->template_dir='core/modules/user/templates/front';
	$this->template->out('user.info.php');
}