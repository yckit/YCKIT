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
var is_ajax,p;
$(function(){
	$('#menu-0').addClass("current");
	{if $article}
	p=1;
	is_ajax=0;
	var hash=window.location.hash;
	if(hash.indexOf("page")!=-1){
		hash=hash.replace("#page:","");
		if($.trim(hash)!=''){
			p=parseInt(hash);
		}
	}
	load(p);
	{/if}
	<?php $this->hook('onload');?>
});
function load(page){
	$.ajax({
		type:'GET',
		url:PATH+'front.php?action=content&do=more&page='+page+'&is_ajax='+is_ajax,
		success:function(result){
			if(page==1&&is_ajax==0){
				$('#content').append(result);
				is_ajax=1;
			}else{
				$('#content').html(result);
			}
			$("html,body").animate({scrollTop:0},function(){
				if(window.location.hash.indexOf("page")!=-1){
					window.location.href="#page:"+page;
				}
			});
		}
	});
}
</script>
</head>
<body>
<div id="page">
<!--{$header}-->
<div id="content">
<!--{if $article}-->
<!--{foreach from=$article item=article}-->
<article class="post">
<div class="post-header">
<h1 class="post-title">
	<a href="<!--{$article.uri}-->" rel="bookmark" title="<!--{$article.title|escape:html}-->"   <!--{if $article.is_nofollow eq 1}-->rel="nofollow"<!--{/if}-->><!--{$article.title}--></a></h1>
    <div class="post-meta"><!--{if $article.author}-->Post by <!--{$article.author}--><!--{else}-->Post by {$config.author}<!--{/if}--> · <!--{$article.time}--> · <a href="<!--{$article.category_path}-->"><!--{$article.category_name}--></a> · <a href="<!--{$article.uri}-->#comment" rel="bookmark" title="<!--{$article.title|escape:html}-->" <!--{if $article.is_nofollow eq 1}-->rel="nofollow"<!--{/if}-->><!--{$article.comment_count}--> replies</a> ·
	 <!--{$article.click_count}--> views</div>
</div>
<div class="post-content"><!--{$article.content}--></div>
</article>
<!--{/foreach}-->
<!--{else}-->
	<div class="post">
		<div class="post-header">
			<h1 class="post-title">您好，欢迎使用YCKIT！</h1>
		</div>
		<div class="post-content">
		如何使用呢？让我告诉您吧！o(∩_∩)o<br/><br/>
		1.登陆后台[全局管理]>[模块管理]，依次全部安装好。<br/><small>（如果安装失败请检查Linux服务器文件权限是否为0777）</small><br/>
		2.[全局管理]>[全局设置]设置您的网站名称和一些其他资料。<br/>
		3.进入到[内容管理]>[栏目管理]，建立好您的栏目分类。<br/>
		4.开始添加文章。<br/>
		5.其他各种设置。<br/><br/>
		如果您对YCKIT有各种疑问，请到 <a href="http://yckit.com">官网</a>寻求帮助！
		</div>
	</div>
<!--{/if}-->
</div>
<!--{include file="side.php"}-->
  <div class="clear"></div>
 <!--{$footer}-->
</div>
</body>
</html>