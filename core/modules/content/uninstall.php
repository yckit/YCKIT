<?php 
if(!ROOT)exit('Access denied');
$query=array();
$query[]="DROP TABLE ".DB_PREFIX."content_article;";
$query[]="DROP TABLE ".DB_PREFIX."content_category;";
$query[]="DROP TABLE ".DB_PREFIX."content_comment;";
$query[]="DROP TABLE ".DB_PREFIX."content_draft;";
$query[]="DROP TABLE ".DB_PREFIX."content_field;";
$query[]="DROP TABLE ".DB_PREFIX."content_field_data;";
if(count($query)>0){
	foreach($query as $sql)$this->db->query($sql);
}
$this->db->delete(DB_PREFIX."config","config_type='content'");
$templates=array(
	'content.config',
	'content.article.info',
	'content.article.list',
	'content.comment.info',
	'content.comment.list',
	'content.category.info',
	'content.category.list',
	'content.draft.list'
);
foreach($templates as $v){
	@unlink(ROOT."/core/themes/admin/".$v.".php");
}
@unlink(ROOT."/content.php");
rm_dir(ROOT."/data/content");