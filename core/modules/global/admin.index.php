<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->action=='global'){
	require_once ROOT.'/core/modules/global/class.global.php';
	$global=new global_class();
	if($global_handle=opendir("core/modules/global")){
		while(false!==($dir=readdir($global_handle))){
			if (strpos($dir,'admin')!==false&&strpos($dir,'admin.index')===false){
				require_once ROOT.'/core/modules/global/'.$dir;
			}
		}
		closedir($global_handle);
	}
}