<?php
if(!defined('ROOT'))exit('Access denied!');
$this->check_access('global_theme');
if($this->do=='theme'){
	$array=array();
	if($handle=opendir("core/themes/")){
		while(false!==($dir=readdir($handle))){
			if ($dir!="."&&$dir!=".."&&is_dir(ROOT.'/core/themes/'.$dir)&&$dir!="~"&&$dir!="admin"){
				$info=@include(ROOT.'/core/themes/'.$dir.'/info.php');
				if(!empty($info)){
					$info['install']=($this->theme==$dir);
					$info['dir']=$dir;
					$array[]=$info;
				}
			}
		}
		closedir($handle);
	}
	$this->template->in("theme_list",$array);
	$this->template->out('global.theme.php');
}
if($this->do=='theme_default'){
	$dir=empty($_GET['dir'])?'':trim($_GET['dir']);
 	$this->db->update(DB_PREFIX."config","config_value='".$dir."'","config_type='theme'");
	clear_cache();
	redirect('?action=global&do=theme');
}