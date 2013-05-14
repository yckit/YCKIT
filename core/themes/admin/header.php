<?php exit?><!doctype html>
<html>
<head>
<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="core/themes/admin/styles/default.css" />
<script type="text/javascript" src="core/scripts/jquery.js"></script>
<script type="text/javascript" src="core/scripts/jquery.box.js"></script>
<script type="text/javascript" src="core/themes/admin/scripts/common.js"></script>
<script type="text/javascript">
$(function(){
	try{
		$('.<!--{$template.get.action}-->').show();
	}catch(e){
		$('.submenu').first().show();
	}
	$('#clear_cache').click(function(){
		$.ajax({type:'GET',url:'?do=clear_cache',success:function(){show_tip('缓存清空完毕！',2,300);}});
	});
	$('#cancel').click(function(){back();});
	setInterval(function(){
		$.ajax({type:'GET',url:'?do=clear_cache',success:function(){show_tip('完成和服务器握手！',2,300);}});
	},1000*5*60);
});
var PATH='<!--{$path}-->';
</script>
<title><!--{$config.title}--></title>
</head>
<body>
<div id="tip"></div>
<a id="logo" href="?do=start"><!--{$config.title}--></a>
<div id="info">
	<span><!--{$template.session.admin_name}--></span>,欢迎您!
	<a href='?action=global&do=admin_edit&admin_id=<!--{$template.session.admin_id}-->'>修改密码</a>&nbsp;/&nbsp;
	<a href='javascript:;' id="clear_cache">清空缓存</a>&nbsp;/&nbsp;
	<a href='./' target="_blank">网站首页</a>&nbsp;/&nbsp;
	<a href='?do=logout'>退出</a>
</div>
<div id='menu'>
<!--{if $leftmenu}-->
<!--{foreach from=$leftmenu item=leftmenu}-->
<!--{if $leftmenu.children}-->
<div class="menu">
<p onclick="change_menu('<!--{$leftmenu.code}-->')"><!--{$leftmenu.name}--></p>
<div class="submenu <!--{$leftmenu.code}-->">
<!--{foreach from=$leftmenu.children key=key item=child}-->
<a href='<!--{$child.1}-->'
<!--{if $template.get.action==$leftmenu.code&&$template.get.do==$key}-->class="hover"<!--{/if}-->
<!--{if $template.get.action==$leftmenu.code&&$template.get.do==$child.code_add}-->class="hover"<!--{/if}-->
<!--{if $template.get.action==$leftmenu.code&&$template.get.do==$child.code_edit}-->class="hover"<!--{/if}-->
><!--{$child.0}--></a>
<!--{/foreach}-->
</div>
</div>
<div class="blank"></div>
<!--{/if}-->
<!--{/foreach}-->
<!--{/if}-->
</div>
