<?php exit?><!doctype html>
<html>
<head>
<meta charset="utf-8" />
<meta name="author" content="<!--{$article.author|escape:html}-->" />
<meta name="keywords" content="<!--{$article.keywords|escape:html}-->" />
<meta name="description" content="<!--{$article.description|escape:html}-->" />
<meta name="copyright" content="Powered by YCKIT" />
<title><!--{$article.title|escape:html}--> - <!--{$config.title|escape:html}--></title>
<!--{include file="static.php"}-->
<script type="text/javascript" src="<!--{$path}-->core/scripts/jquery.lightbox.js"></script>
<link rel="stylesheet" type="text/css" href="<!--{$path}-->core/styles/jquery.lightbox.css" />
<script>
$(function(){
  $('#menu-{$category.id}').addClass("current");
  $('.post-content img').wrap(function(){return "<a href='"+$(this).attr('src')+"' rel='lightbox'></a>";});
  update_article_click({$article.id});
  <?php $this->hook('onload');?>
  $('a[rel=lightbox]').lightBox({
    imageLoading:PATH+'core/images/lightbox-ico-loading.gif',
    imageBtnPrev:PATH+'core/images/lightbox-btn-prev.gif',
    imageBtnNext:PATH+'core/images/lightbox-btn-next.gif',
    imageBtnClose:PATH+'core/images/lightbox-btn-close.gif',
    imageBlank:PATH+'core/images/lightbox-blank.gif'
  });
  <?php $this->hook('onload');?>
});
</script>
</head>
<body>
<div id="page">
<!--{$header}-->
    <div id="content">
      <div class="post">
        <div class="post-header">
            <h1 class="post-title"><!--{$article.title}--></h1>
            <div class="post-meta">
              <!--{if $article.author}-->
              Post by <!--{$article.author}-->
              <!--{else}-->
              Post by {$config.author}
              <!--{/if}--> · 
              <!--{$article.time|timestamp:"Y.m.d"}--> · 
              <a href="<!--{$article.category_path}-->"><!--{$article.category_name}--></a>
              <!--{if $article.tags}-->
                <!--{foreach from=$article.tags item=tag}--> 
                · <a href="<!--{$path}-->search.php?action=content&tag=<!--{$tag|escape:html}-->"><!--{$tag}--></a>
                <!--{/foreach}-->
              <!--{/if}-->
               · <a href="<!--{$article.uri}-->#comment" rel="bookmark" title="<!--{$article.title|escape:html}-->" <!--{if $article.is_nofollow eq 1}-->rel="nofollow"<!--{/if}-->><!--{$article.comment_count}--> replies</a> ·
              <!--{$article.click_count}--> views
            </div>
          </div>
   
          <div class="post-content"><!--{$article.content}--></div>
 
          {if $config.ad}<div class="post-ad">{$config.ad}</div>{/if}
          <div class="post-nav">
            上一篇：
          <!--{if $prev}-->
          <a href="{if $config.content_mode==1}<!--{$prev.html}-->.html{elseif $config.content_mode==2}{$path}content/<!--{$prev.id}-->/{else}{$path}content.php?id=<!--{$prev.id}-->{/if}"><!--{$prev.title}--></a><!--{else}-->没有了<!--{/if}-->
          <br />
          下一篇：
          <!--{if $next}--><a href="{if $config.content_mode==1}<!--{$next.html}-->.html{elseif $config.content_mode==2}{$path}content/<!--{$next.id}-->/{else}{$path}content.php?id=<!--{$next.id}-->{/if}"><!--{$next.title}--></a><!--{else}-->没有了<!--{/if}-->
      </div>
      <!--{if $article.is_comment}-->
            <div id="comment"></div><script>get_comment('content',{$article.id},1);</script>
      <!--{/if}-->
  </div>
</div>
<!--{include file="side.php"}-->
<div class="clear"></div>
<!--{$footer}-->
</div>
</body>
</html>