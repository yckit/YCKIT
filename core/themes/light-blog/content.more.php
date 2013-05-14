<!--{if $template.get.is_ajax==1}-->
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
<!--{/if}-->
<!--{if $pager}-->
	<div class="pager">
	<ul>
	<!--{if $pager.begin}-->
		<li><a href="javascript:load(<!--{$pager.begin}-->)">&laquo;</a></li>
	<!--{else}-->
		<li class="disabled"><a>&laquo;</a></li>
	<!--{/if}-->
	<!--{foreach from=$pager.no key=key item=href}-->
		<!--{if $pager.current==$key}-->
			<li class="active"><a><!--{$key}--></a></li>
		<!--{else}-->
			<li><a href="javascript:load(<!--{$key}-->)"><!--{$key}--></a></li>
		<!--{/if}-->
	<!--{/foreach}-->
	<!--{if $pager.end}-->
		<li><a href="javascript:load(<!--{$pager.end}-->)">&raquo;</a></li>
	<!--{else}-->
		<li class="disabled"><a>&raquo;</a></li>
	<!--{/if}-->
	</ul>
	<div class="clear"></div>
	</div>
<!--{/if}-->
