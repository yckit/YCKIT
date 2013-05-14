<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->action=='user'){
	require_once ROOT.'/core/modules/user/class.user.php';$user=new user_class();
	if($handle=opendir("core/modules/user")){
		while(false!==($dir=readdir($handle))){
			if (strpos($dir,'front')!==false&&strpos($dir,'front.index')===false){
				require_once ROOT.'/core/modules/user/'.$dir;
			}
		}
		closedir($handle);
	}
}