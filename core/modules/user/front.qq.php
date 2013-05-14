<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->do=='qq'){
	check_request();
	$_SESSION['qq_appid']=$this->config['qq_appid']; 
	$_SESSION['qq_appkey']=$this->config['qq_appkey']; 
	$_SESSION['qq_appcallback']=$this->config['qq_appcallback']; 
	$_SESSION['qq_scope']= "get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo";
    $_SESSION['qq_state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
    $url="https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=".$_SESSION['qq_appid']."&redirect_uri=".urlencode($_SESSION['qq_appcallback'])."&state=".$_SESSION['qq_state']."&scope=".$_SESSION['qq_scope'];
    header("Location:$url");
}