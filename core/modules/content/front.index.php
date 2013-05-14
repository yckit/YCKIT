<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->action=='content'){
	if(!$this->check_module('content'))exit('The content module is not installed');
	require_once ROOT.'/core/modules/content/class.content.php';$content=new content_class();
	if($handle=opendir("core/modules/content")){
		while(false!==($dir=readdir($handle))){
			if (strpos($dir,'front')!==false&&strpos($dir,'front.index')===false){
				require_once ROOT.'/core/modules/content/'.$dir;
			}
		}
		closedir($handle);
	}
}