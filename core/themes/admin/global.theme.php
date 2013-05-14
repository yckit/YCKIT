<?php exit?>
<!--{include file="header.php"}-->
<style>
.theme_list{display:inline-block;+display:inline;+zoom:1;padding:10px;width:200px;height:320px;}
.theme_list img{border:3px solid #444;cursor:pointer;filter:alpha(opacity=80);opacity:0.8}
.theme_list img.current{border:3px solid #FFA631;}
.theme_list p{padding:3px 8px;font-size:12px;}
</style>
<div class="layout-box">
	<div class='layout-title'>主题管理</div>
	<div class='layout-body'>
		<!--{foreach from=$theme_list item=theme}-->
		<div class="theme_list">
			<a href="?action=global&do=theme_default&dir=<!--{$theme.dir}-->"><img <!--{if $theme.install}-->class="current"<!--{/if}--> src="core/themes/<!--{$theme.dir}-->/thumb.png" alt="<!--{$theme.name}-->" /></a>
			<p>模板名称：<b class="red"><!--{$theme.name}--></b></p>
			<p>模板设计：<!--{$theme.author}--></p>
			<p>制作日期：<!--{$theme.date}--></p>
		</div>
		<!--{/foreach}-->
	</div>
</div>