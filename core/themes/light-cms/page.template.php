<?php exit?><!doctype html>
<html>
<head>
<meta charset="utf-8" />
<meta name="keywords" content="<!--{$page.keywords|escape:html}-->" />
<meta name="description" content="<!--{$page.description|escape:html}-->" />
<title><!--{$page.title|escape:html}--> - <!--{$config.title|escape:html}--></title>
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
  <div class="post">
    <div class="post-header"><br /><h1 class="post-title"><!--{$page.title|escape:html}--></h1></div>
    <div class="post-content"><!--{$page.content}--></div>
  </div>
  <!--{if $page.is_comment}-->
    <div id="comment"></div><script>get_comment('page',{$page.id},1);</script>
  <!--{/if}-->
</div>
   <ul class="page-nav floatleft">
    <!--{foreach from=$nav item=nav}-->
    <li><a href="<!--{$path}--><!--{$nav.html|escape:html}-->.html" {if $nav.id==$page.id}class="hover"{/if}><!--{$nav.title|escape:html}--></a></li>
    <!--{/foreach}-->
  </ul>
<div class="clear"></div>
<!--{$footer}-->
</div>
</body>
</html>