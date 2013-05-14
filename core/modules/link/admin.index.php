<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->action=='link'){
	if($handle=opendir("core/modules/link")){
		while(false!==($dir=readdir($handle))){
			if ($dir!="."&&$dir!=".."&&strpos($dir,'admin')!==false){
				require_once ROOT.'/core/modules/link/'.$dir;
			}
		}
		closedir($handle);
	}
}