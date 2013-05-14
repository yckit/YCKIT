<?php exit?><!doctype html>
<html>
<head>
<meta charset="utf-8" />
<meta name="author" content="<!--{$config.author|escape:html}-->" />
<meta name="keywords" content="<!--{$config.keywords|escape:html}-->" />
<meta name="description" content="<!--{$config.description|escape:html}-->" />
<meta name="copyright" content="Powered by YCKIT" />
<title><!--{$config.title|escape:html}--></title>
<!--{include file="static.php"}-->
<script>
$(function(){
	<?php $this->hook('onload');?>
});
</script>
</head>
<body>
<div id="page">
<!--{$header}-->
<div id="content">
<!--{if $index_category}-->
	<div id="content-inner">
	<!--{foreach from=$index_category item=category}-->
		<dl class="index-box floatleft">
			<dt><a href="{$category.uri}" class="more floatright">更多</a><a href="{$category.uri}">{$category.name}</a></dt>
			<!--{foreach from=$category.articles item=article name=article}-->
				<!--{if $template.foreach.article.index==0}-->
				<dd class="big">
				<!--{if $article.image}--><img src="<!--{$article.image}-->" class="image floatleft"/><!--{/if}-->
				<div <!--{if $article.image}-->class="have"<!--{/if}-->>
				<a href="{$article.uri}" title="{$article.title|escape:html}" {if $article.is_nofollow eq 1}rel="nofollow"{/if}><h3><!--{$article.title}--></h3></a>
				<p><!--{if $article.description}--><!--{$article.description|truncate:50}--><!--{else}--><!--{$article.content|strip_tags|truncate:50}--><!--{/if}--></p>
				</div>
				</dd>	
				<!--{else}-->
				<dd><span><!--{$article.timestamp|timestamp:m/d}--></span><a href="{$article.uri}" title="{$article.title|escape:html}" {if $article.is_nofollow eq 1}rel="nofollow"{/if}><!--{$article.title}--></a></dd>
				<!--{/if}-->
			<!--{/foreach}-->
		</dl>
	<!--{/foreach}-->
	</div>
<!--{else}-->
	请到全局设置中设置栏目
<!--{/if}-->

</div>
<!--{include file="side.php"}-->
  <div class="clear"></div>
 <!--{$footer}-->
</div>
</body>
</html>
<script>
var ad=[];
	<!--{foreach from=$config.index_ad key=key item=ad}-->
	{if $ad}
	ad[{$key}]='<div class="index-banner floatleft">{$ad}</div>';
	{/if}
	<!--{/foreach}-->
var length=ad.length;
 	if(length>0){
		for(var i=1;i<=length;++i){
			if(i==length){
				$("#content-inner").append(ad[i-1]);
			}else{
				$("#content").find("dl:eq("+(i*2)+")").before(ad[i-1]);	
			}
			
		}	
 	}

</script>