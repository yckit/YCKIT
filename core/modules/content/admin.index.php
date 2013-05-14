<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->action=='content'){
	require_once ROOT.'/core/modules/content/class.content.php';$content=new content_class();
	if($handle=opendir("core/modules/content")){
		while(false!==($dir=readdir($handle))){
			if (strpos($dir,'admin')!==false&&strpos($dir,'admin.index')===false){
				require_once ROOT.'/core/modules/content/'.$dir;
			}
		}
		closedir($handle);
	}
}