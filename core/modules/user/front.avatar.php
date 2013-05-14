<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->do=='avatar'){
	check_request();
	$this->template->template_dir='core/modules/user/templates/front';
	$this->template->out("user.avatar.php");
}
if($this->do=='avatar-upload'){
	check_request();
	$user_id=$_SESSION['user_id'];
    $data=$_POST['image'];
    $content=base64_decode($data);
    if(file_put_contents("data/user/".$user_id.".jpg",$content)){
        exit("success");
    }
}