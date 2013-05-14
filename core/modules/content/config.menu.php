<?php
return array(
	'name'=>'内容管理',
	'item'=>array(
		'article'=>array('文章管理','?action=content&do=article'),
		'comment'=>array('评论管理','?action=content&do=comment'),
		'category'=>array('栏目管理','?action=content&do=category'),
		'draft'=>array('投稿管理','?action=content&do=draft'),
		'config'=>array('内容设置','?action=content&do=config'),
	),
	'sort'=>1
);
