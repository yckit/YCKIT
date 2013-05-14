<?php exit?><!doctype html>
<html>
<head>
<meta charset="utf-8" />
<meta name="author" content="<!--{$config.author|escape:html}-->" />
<meta name="keywords" content="<!--{$config.keywords|escape:html}-->" />
<meta name="description" content="<!--{$config.description|escape:html}-->" />
<meta name="copyright" content="Powered by YCKIT" />
<title><!--{$keyword}--> - <!--{$config.title|escape:html}--></title>
<!--{include file="static.php"}-->
<script>
$(function(){
	<?php $this->hook('onload');?>
});
</script>
</head>
<body>
<section id="page">
<!--{$header}-->
    <div id="content">
    <!--{if $article}-->
    <!--{foreach from=$article item=article}-->
      <div class="post">
        <div class="post-header">
           <h1 class="post-title"><a href="<!--{$article.uri}-->" rel="bookmark" title="<!--{$article.title|escape:html}-->"   <!--{if $article.is_nofollow eq 1}-->rel="nofollow"<!--{/if}-->><!--{$article.title}--></a></h1>
            <div class="post-meta"><!--{if $article.author}-->Post by <!--{$article.author}--><!--{else}-->Post by {$config.author}<!--{/if}--> · <!--{$article.time|timestamp:"Y.m.d"}--> · <a href="<!--{$article.category_path}-->"><!--{$article.category_name}--></a> · <a href="<!--{$article.uri}-->#comment" rel="bookmark" title="<!--{$article.title|escape:html}-->" <!--{if $article.is_nofollow eq 1}-->rel="nofollow"<!--{/if}-->><!--{$article.comment_count}--> replies</a> ·
         <!--{$article.click_count}--> views</div>
           </div>
        <div class="post-content"><!--{$article.content}--></div>
       
         </div>
    <!--{/foreach}--> 
    <!--{if $pager}-->
<div class="pager">
<ul>
<!--{if $pager.begin}-->
<li>
<a href="<!--{$path}-->search.php?action=content&keyword=<!--{$keyword}-->&page=<!--{$pager.begin}-->">&laquo;</a>
</li>
<!--{else}-->
<li class="disabled"><a>&laquo;</a></li>
<!--{/if}-->
<!--{foreach from=$pager.no key=key item=href}-->
<!--{if $pager.current==$key}-->
<li class="active"><a><!--{$key}--></a></li>
<!--{else}-->
<li><a href="<!--{$path}-->search.php?action=content&keyword=<!--{$keyword}-->&page=<!--{$href}-->"><!--{$key}--></a></li>
<!--{/if}-->
<!--{/foreach}-->
<!--{if $pager.end}-->
<li>
<a href="<!--{$path}-->search.php?action=content&keyword=<!--{$keyword}-->&page=<!--{$pager.end}-->">&raquo;</a>
</li>
<!--{else}-->
<li class="disabled"><a>&raquo;</a></li>
<!--{/if}-->
</ul>
</div>
<!--{/if}-->
    <!--{else}-->
	<div style="line-height:400px;text-align:center">暂无关于 “ <!--{$keyword}--> ” 的文章</div>
<!--{/if}--> 

   </div>

  </div>
  <!--{include file="side.php"}-->
  <div class="clear"></div>
  <!--{$footer}-->
</section>
</body>
</html>