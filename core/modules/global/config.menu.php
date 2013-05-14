<?php
return array(
	'name'=>'全局管理',
	'item'=>array(
		'config'=>array('全局设置','?action=global&do=config'),
		'menu'=>array('菜单设置','?action=global&do=menu'),
		'admin'=>array('角色管理','?action=global&do=admin'),
		'tag'=>array('内链管理','?action=global&do=tag'),
		'theme'=>array('主题管理','?action=global&do=theme'),
		'module'=>array('模块管理','?action=global&do=module'),
		'plugin'=>array('插件管理','?action=global&do=plugin'),
		'backup'=>array('备份管理','?action=global&do=backup'),
	),
	'sort'=>99999
);
