<?php exit?>
<div id="side">
  <?php $this->hook('side_top');?>
<h3 class="side-h3">最新评论</h3>
<div class="widget">
  <ul>
  	
	<!--{foreach from=$comment_list item=comment}-->
	<li class="side-comment">
		<a href="<!--{$comment.uri}-->"><img class="side-avatar" alt="" src="<!--{$comment.avatar}-->" height="36" width="36"  onerror="this.src='{$path}core/images/avatar.jpg'">
			<p><!--{if $comment.site}--><strong><!--{$comment.name|truncate:8}--></strong><!--{else}--><strong><!--{$comment.name}--></strong><!--{/if}--></p>
		<p><!--{$comment.content|truncate:50}--></p></a>
    </li>
	<!--{/foreach}-->

  </ul>
</div>
<div id="luantan">
<!--{if $article_best}-->
<h3 class="side-h3">推荐文章</h3>
<div class="widget">
  <ul class="list">
	<!--{foreach from=$article_best item=article}-->
		<li><a href="<!--{$article.uri}-->"  title="{$article.title|escape:html}" {if $article.is_nofollow eq 1}rel="nofollow"{/if}><!--{$article.title|truncate:20}--></a>
		</li>
	<!--{/foreach}-->
  </ul>
  </div>
 <!--{/if}-->
<h3 class="side-h3">热门文章</h3>
<div class="widget">
  <ul class="list">
	<!--{foreach from=$article_click item=article}-->
		<li><a href="<!--{$article.uri}-->"  title="{$article.title|escape:html}" {if $article.is_nofollow eq 1}rel="nofollow"{/if}><!--{$article.title|truncate:20}--></a>
		</li>
	<!--{/foreach}-->
  </ul>
</div>
</div>
<h3 class="side-h3 side-link">左邻右舍</h3>
<div class="widget">
  <ul class="list">
	<!--{foreach from=$link_list item=link}-->
		<li class="left"><a href="<!--{$link.url}-->" target="_blank" rel="nofollow"><!--{$link.name|escape:html}--></a></li>
	<!--{/foreach}-->
  </ul>
	<br class="clear" />
</div>
 <?php $this->hook('side_bottom');?>
</div>
<!--{if $config.side==1}-->
<script>
$(document).ready(function (){
   var offset = $('.side-link').offset();   
   $(window).scroll(function () { 
    var scrollTop = $(window).scrollTop(); 
    if (offset.top < scrollTop) $('#luantan').addClass('fixed'); 
    else $('#luantan').removeClass('fixed');
     })
;});
</script>
<!--{/if}-->