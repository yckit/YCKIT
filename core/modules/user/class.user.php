<?php
if(!defined('ROOT'))exit('Access denied!');
class user_class extends base{
	function __construct(){
		$this->init();
	}
	function is_login(){
		return isset($_SESSION['user_id'])?true:false;
	}
	function get_role(){
		$array=array();
		$result=$this->db->result("SELECT * FROM ".DB_PREFIX."user_role WHERE role_status=1");
		if($result){
			foreach($result as $row){
				$array[$row['role_id']]['id']=$row['role_id'];
				$array[$row['role_id']]['name']=$row['role_name'];
				$array[$row['role_id']]['description']=$row['role_description'];
			}
		}
		return $array;
	}

	function check_disabled_ip(){
		$status=false;
		$my_ip=get_ip();
		$disabled_ip=$this->config['user_disabled_ip'];
		if(!empty($disabled_ip)){
			$disabled_ip_explode=explode("\n",$disabled_ip);
			foreach($disabled_ip_explode as $ip){
				if(trim($ip)==$my_ip){
					$status=true;
					break;
				}
			}
		}
		return $status;
	}
	function qq_callback(){
	    if($_REQUEST['state'] == $_SESSION['qq_state']){
	        $token_url="https://graph.qq.com/oauth2.0/token?grant_type=authorization_code&client_id=".$_SESSION["qq_appid"]."&redirect_uri=".urlencode($_SESSION["qq_appcallback"])."&client_secret=".$_SESSION["qq_appkey"]."&code=".$_REQUEST["code"];
	        $response = file_get_contents($token_url);
	        if (strpos($response, "callback") !== false){
	            $lpos = strpos($response, "(");
	            $rpos = strrpos($response, ")");
	            $response  = substr($response, $lpos + 1, $rpos - $lpos -1);
	            $msg = json_decode($response);
	            if (isset($msg->error)){
	                echo "<h3>error:</h3>" . $msg->error;
	                echo "<h3>msg  :</h3>" . $msg->error_description;
	                exit;
	            }
	        }
	        $params = array();
	        parse_str($response, $params);
	        $_SESSION["qq_token"] = $params["access_token"];

	    }else{
	        echo("The state does not match. You may be a victim of CSRF.");
	    }
	}
	function qq_openid(){
	    $graph_url = "https://graph.qq.com/oauth2.0/me?access_token=".$_SESSION['qq_token'];
	    $str  = file_get_contents($graph_url);
	    if (strpos($str, "callback") !== false){
	        $lpos = strpos($str, "(");
	        $rpos = strrpos($str, ")");
	        $str  = substr($str, $lpos + 1, $rpos - $lpos -1);
	    }
	    $user = json_decode($str);
	    if (isset($user->error)){
	        echo "<h3>error:</h3>" . $user->error;
	        echo "<h3>msg  :</h3>" . $user->error_description;
	        exit;
	    }
	    $_SESSION["qq_openid"] = $user->openid;
	}
	function qq_info(){
	    $get_user_info = "https://graph.qq.com/user/get_user_info?"
	        . "access_token=" . $_SESSION['qq_token']
	        . "&oauth_consumer_key=" . $_SESSION["qq_appid"]
	        . "&openid=" . $_SESSION["qq_openid"]
	        . "&format=json";
	    $info = file_get_contents($get_user_info);
	    $arr = json_decode($info, true);
	    $_SESSION["qq_nickname"] = $arr['nickname'];
	    $_SESSION["qq_avatar"] = $arr['figureurl_qq_2'];
	    $_SESSION["qq_sex"] = $arr['gender'];
	    $_SESSION["qq_vip"] = $arr['vip'];
	    return $arr;
	}
	function qq_avatar($id){
		@file_put_contents("data/user/".$id.".jpg",@file_get_contents($_SESSION['qq_avatar']));
	}
}