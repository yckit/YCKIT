<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->do=='backup'){
	$this->check_access('global_backup');
	$array=array();
	if($handle=opendir(ROOT."/data/backup/")){
		$no=1;
		while(false!==($dir=readdir($handle))){
			if (strpos($dir,'.sql')!==false){
				$array[$no]['no']=$no;
				$array[$no]['filename']=$dir;
				$array[$no]['lasttime']=date('Y-m-d h:i:s',filemtime(ROOT."/data/backup/".$dir));
				$no++;
			}
		}
		closedir($handle);
	}
	$this->template->in('sqls',$array);
	$this->template->out('global.backup.php');
}
if($this->do=='backup_go'){
	$this->check_access('global_backup');
	$content=$this->db->export();
	$date=date('Ymd');
	$filename=md5($date.mt_rand(0,99999));
	$filename=$date."_".substr($filename,0,10).".sql";
	file_put_contents(ROOT."/data/backup/".$filename,$content);
}
if($this->do=='backup_restore'){# fixed by 2012/11/13
	$this->check_access('global_backup');
	if(isset($_GET['filename'])){
		$filename=trim($_GET['filename']);
		$filename=ROOT."/data/backup/".$filename;
		if(file_exists($filename)){
			$content=file_get_contents($filename);
			$line=explode(";\n",$content);
			foreach($line as $sql){
				if(!empty($sql)){
					$this->db->query($sql);
				}
			}
		}
	}
	clear_cache();
}
if($this->do=='backup_delete'){
	$this->check_access('global_backup');
	if(isset($_GET['filename'])){
		$filename=trim($_GET['filename']);
		@unlink(ROOT."/data/backup/".$filename);
	}
	redirect('?action=global&do=backup');
}