<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->action=='page'){
	require_once ROOT.'/core/modules/page/class.page.php';
	$page=new page_class();
	if($handle=opendir("core/modules/page")){
		while(false!==($dir=readdir($handle))){
			if (strpos($dir,'admin')!==false&&strpos($dir,'admin.index')===false){
				require_once ROOT.'/core/modules/page/'.$dir;
			}
		}
		closedir($handle);
	}
}