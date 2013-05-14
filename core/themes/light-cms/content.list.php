<?php exit?><!doctype html>
<html>
<head>
<meta charset="utf-8" />
<meta name="keywords" content="<!--{$category.keywords|escape:html}-->" />
<meta name="description" content="<!--{$category.description|escape:html}-->" />
<meta name="copyright" content="Powered by YCKIT" />
<title><!--{$category.name|escape:html}--> - <!--{$config.title|escape:html}--></title>
<!--{include file="static.php"}-->
<script>
$(function(){
	$('#menu-{$category.id}').addClass("current");
	<?php $this->hook('onload');?>
});
</script>
</head>
<body>
<div id="page">
	<!--{$header}-->
	<div id="content">
		<!--{foreach from=$article item=article name=article}-->
			{if $template.foreach.article.index mod 5 eq 0&&!$template.foreach.article.first}<p><br /></p>{/if}
			<p class="post-list">
			<a href="<!--{$article.uri}-->" rel="bookmark" title="<!--{$article.title|escape:html}-->" {if $article.is_nofollow==1}rel="nofollow"{/if}><!--{$article.title}--></a>
			<span class="time"><!--{$article.time|timestamp:"Y/m/d"}--></span>
			</p>

		<!--{/foreach}--> 
	    
		<!--{if $pager.page_count>1}-->
			<div class="pager">
			<ul>
			<!--{if $pager.begin}-->
				<li><a href="{if $config.content_mode==1}list<!--{$pager.begin}-->.html{elseif $config.content_mode==2}{$path}category/{$category.id}/page/<!--{$pager.begin}-->/{else}{$path}content.php?cid={$category.id}&page=<!--{$pager.begin}-->{/if}">&laquo;</a></li>
			<!--{else}-->
				<li class="disabled"><a>&laquo;</a></li>
			<!--{/if}-->

			<!--{foreach from=$pager.no key=key item=href}-->
			<!--{if $pager.current==$key}-->
				<li class="active"><a><!--{$key}--></a></li>
			<!--{else}-->
				<li><a href="{if $config.content_mode==1}list<!--{$href}-->.html{elseif $config.content_mode==2}{$path}category/{$category.id}/page/<!--{$key}-->/{else}{$path}content.php?cid={$category.id}&page=<!--{$key}-->{/if}"><!--{$key}--></a></li>
			<!--{/if}-->
			<!--{/foreach}-->

			<!--{if $pager.end}-->
				<li><a href="{if $config.content_mode==1}list<!--{$pager.end}-->.html{elseif $config.content_mode==2}{$path}category/{$category.id}/page/<!--{$pager.end}-->/{else}{$path}content.php?cid={$category.id}&page=<!--{$pager.end}-->{/if}">&raquo;</a></li>
			<!--{else}-->
				<li class="disabled"><a>&raquo;</a></li>
			<!--{/if}-->
			</ul>
			</div>
		<!--{/if}-->
	</div>
	<!--{include file="side.php"}-->
	<div class="clear"></div>
	<!--{$footer}-->
</div>
</body>
</html>