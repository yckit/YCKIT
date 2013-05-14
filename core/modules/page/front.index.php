<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->action=='page'){
	if($handle=opendir("core/modules/page")){
		while(false!==($dir=readdir($handle))){
			if ($dir!="."&&$dir!=".."&&$dir!="index.htm"&&$dir!="index.php"&&strpos($dir,'class')===false&&strpos($dir,'front')!==false){
				require_once ROOT.'/core/modules/page/'.$dir;
			}
		}
		closedir($handle);
	}
}