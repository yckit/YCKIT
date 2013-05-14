<?php
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
require_once ROOT.'/core/yckit.config.php';
require_once ROOT.'/core/yckit.function.php';
require_once ROOT.'/core/yckit.version.php';
if($_POST){
	import('db');
	$form_db_host		=trim($_POST['DB_HOST']);
	$form_db_user		=trim($_POST['DB_USER']);
	$form_db_password	=trim($_POST['DB_PASSWORD']);
	$form_db_name		=trim($_POST['DB_NAME']);
	$form_db_prefix		=trim($_POST['DB_PREFIX']);
	$form_path		=trim($_POST['PATH']);
	if(empty($form_path)){
		$form_path='/';
	}
	$db=new db($form_db_host,$form_db_user,$form_db_password,$form_db_name,true);
	#数据库创建表
	$query=array();
	$query[]="CREATE TABLE IF NOT EXISTS `".$form_db_prefix."admin` (
	  `admin_id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
	  `admin_name` varchar(50) NOT NULL,
	  `admin_password` varchar(100) NOT NULL,
	  `admin_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
	  `admin_access` varchar(255) NOT NULL DEFAULT '',
	  `admin_login_time` int(4) unsigned NOT NULL DEFAULT '0',
	  `admin_last_time` int(10) unsigned NOT NULL DEFAULT '0',
	  `admin_last_ip` varchar(50) NOT NULL DEFAULT '',
	  `admin_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
	  PRIMARY KEY (`admin_id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;";

	$query[]="INSERT INTO `".$form_db_prefix."admin` (`admin_id`, `admin_name`, `admin_password`, `admin_type`, `admin_access`, `admin_login_time`, `admin_last_time`, `admin_last_ip`, `admin_status`) VALUES(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, '', 1, 1322447870, '127.0.0.1', 1);";

	$query[]="CREATE TABLE IF NOT EXISTS `".$form_db_prefix."config` (
	  `config_type` varchar(10) NOT NULL DEFAULT '',
	  `config_value` text NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;";


	$query[]="INSERT INTO `".$form_db_prefix."config` (`config_type`, `config_value`) VALUES('modules', ''),('plugins', ''),('theme', 'light-blog'),('config','YToyMzp7czo1OiJ0aXRsZSI7czo1OiJZQ0tJVCI7czo2OiJhdXRob3IiO3M6Njoi6YeO6I2JIjtzOjg6ImtleXdvcmRzIjtzOjU6IllDS0lUIjtzOjExOiJkZXNjcmlwdGlvbiI7czo1MToi5LiA5Liq5Z+65LqOUEhQK01ZU1FM5p625p6E5LiL55qE5pWP5o235byA5Y+R5qGG5p62IjtzOjg6InN0YXRjb2RlIjtzOjA6IiI7czo2OiJub3RpY2UiO3M6NTE6IuS4gOS4quWfuuS6jlBIUCtNWVNRTOaetuaehOS4i+eahOaVj+aNt+W8gOWPkeahhuaetiI7czoxMToic210cF9zZXJ2ZXIiO3M6MTI6InNtdHAuMTYzLmNvbSI7czo5OiJzbXRwX3VzZXIiO3M6MjA6Im0xMzU4NTg2NDM0NkAxNjMuY29tIjtzOjEzOiJzbXRwX3Bhc3N3b3JkIjtzOjA6IiI7czo5OiJzbXRwX3BvcnQiO3M6MjoiMjUiO3M6OToic210cF9tYWlsIjtzOjIwOiJtMTM1ODU4NjQzNDZAMTYzLmNvbSI7czoxNjoid2F0ZXJtYXJrX3N0YXR1cyI7aTowO3M6MTg6IndhdGVybWFya19wb3NpdGlvbiI7aTowO3M6MTU6IndhdGVybWFya19pbWFnZSI7TjtzOjEwOiJpbmRleF9zaXplIjtpOjEwO3M6OToiYmVzdF9zaXplIjtpOjU7czoxMDoiY2xpY2tfc2l6ZSI7aTo1O3M6NDoic2lkZSI7aToxO3M6NDoic2luYSI7czowOiIiO3M6MjoicXEiO3M6MDoiIjtzOjQ6InNvaHUiO3M6MDoiIjtzOjY6Indhbmd5aSI7czowOiIiO3M6MjoiYWQiO3M6MDoiIjt9');";


	$query[]="CREATE TABLE IF NOT EXISTS `".$form_db_prefix."tag` (
	  `tag_id` int(8) NOT NULL AUTO_INCREMENT,
	  `tag_name` varchar(50) NOT NULL DEFAULT '',
	  `tag_link` varchar(255) NOT NULL DEFAULT '',
	  PRIMARY KEY (`tag_id`),
	  UNIQUE KEY `tag_name` (`tag_name`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";


	$query[]="CREATE TABLE IF NOT EXISTS `".$form_db_prefix."menu` (
	  `menu_id` smallint(3) unsigned NOT NULL auto_increment,
	  `menu_name` varchar(50) NOT NULL default '',
	  `menu_link` varchar(255) NOT NULL default '',
	  `menu_description` varchar(100) NOT NULL default '',
	  `menu_target` tinyint(1) unsigned NOT NULL default '0',
	  `menu_sort` smallint(3) NOT NULL default '0',
	  `menu_status` tinyint(1) unsigned NOT NULL default '0',
	  `parent_id` smallint(3) unsigned NOT NULL default '0',
	  PRIMARY KEY  (`menu_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

	
	#执行命令
	if(count($query)>0){
		foreach($query as $sql){
			$db->query($sql);
		}
	}
	$CONFIG="<?php\n";
	$CONFIG.="define('DEBUG',false);\n\n";
	$CONFIG.="define('GZIP',true);\n\n";
	$CONFIG.="define('TIMEZONE','PRC');\n\n";
	$CONFIG.="define('PATH','".$form_path."');\n\n";
	$CONFIG.="define('EXT','jpg,jpeg,gif,png,bmp,torrent,zip,rar,7z,doc,docx,xls,xlsx,ppt,pptx,csv,mp3,wma,swf,flv,txt');\n\n";
	$CONFIG.="define('DB_HOST','$form_db_host');\n\n";
	$CONFIG.="define('DB_USER','$form_db_user');\n\n";
	$CONFIG.="define('DB_PASSWORD','$form_db_password');\n\n";
	$CONFIG.="define('DB_NAME','$form_db_name');\n\n";
	$CONFIG.="define('DB_PREFIX','$form_db_prefix');";
	file_put_contents(ROOT.'/core/yckit.config.php',$CONFIG) or die("请检查文件 core/yckit.config.php 的权限是否为0777!");
	if(get_ip()!='127.0.0.1'){
		echo "<iframe src='http://yckit.com/api/?domain=".$_SERVER['SERVER_NAME']."&version=".$version."' style='display:none'></iframe>";
	}
	@unlink("install.php");
	redirect('./admin.php');
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>YCKIT</title>
<style type="text/css">
html,body{overflow:hidden;font:normal 12px 'Microsoft Yahei';background:#111;color:#666;padding:0;margin:0;}
body{border-top:5px solid #333}
#intro{background:#222;overflow:hidden;height:0;padding:15px 0 0 15px}
.blank{padding:3px;}
.input{width:300px;border:2px solid #ccc;background:#eed;padding:8px;font:normal 12px 'Microsoft Yahei';outline:0;color:#4b751d}
.input:focus{border:2px solid #fc6b13;}
.submit{border-radius:2px;text-align:center;border:none;margin-left:100px;padding:9px 15px;width:100px;_padding:5px 8px;background:#333;cursor:pointer;font:bold 12px 'Microsoft Yahei';color:#fff}
</style>
<script src="core/scripts/jquery.js"></script>
<script>
$(function(){
	$('#intro').animate({height:'65px'},900);
});
</script>
 </head>
 <body>
 <div id="intro">
		 <span style="font-size:2em;font-weight:bold;color:#eee;"><?php if(PHP_OS=='WINNT'){echo('Windows');}else{echo(PHP_OS);}?></span><br />
		 PHP <?=@PHP_VERSION?>/<?=@$_SERVER['SERVER_SOFTWARE']?>
 </div>
 
 <form method="post" style="padding:15px;">
 (数据库主机)
 <div class="blank"></div>
 <input type="text" name="DB_HOST" size="30" class="input" value="localhost" />
 <div class="blank"></div>
 (数据库用户)
 <div class="blank"></div>
 <input type="text" name="DB_USER" size="30"  class="input" value="root" />
 <div class="blank"></div>
 (数据库密码)
 <div class="blank"></div>
 <input type="text" name="DB_PASSWORD" size="30"  class="input"  value="123456"/>
 <div class="blank"></div>
 (数据库名称：必须是以字母开头的字符串)
 <div class="blank"></div>
 <input type="text" name="DB_NAME" size="30"  class="input" value="yckit" />
 <div class="blank"></div>
 (数据表前缀：必须是以字母开头的字符串)
 <div class="blank"></div>
 <input type="text" name="DB_PREFIX" size="30"  class="input" value="db_"/>
 <div class="blank"></div> <div class="blank"></div>
 <div onclick="if(confirm('确定要安装吗?')){document.forms[0].submit()}" class="submit">开始安装</div>
  <input type="hidden" name="PATH" size="30"  class="input" value="<?php echo str_replace('install.php','',$_SERVER['SCRIPT_NAME'])?>"/>
 </form>
 </body>
 </html>