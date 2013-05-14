<div class="pager">
<span>总记录 <!--{$pager.data_count}--> 条,每页显示 <!--{$pager.page_size}--> 条</span>
<span>页次：<!--{$pager.current}-->/<!--{$pager.page_count}--></span>

<!--{if $pager.begin}-->
	<a href="<!--{$pager.begin}-->">首页</a>
<!--{/if}-->
<!--{if $pager.prev}-->
	<a href="<!--{$pager.prev}-->">上一页</a>
<!--{/if}-->
<!--{foreach from=$pager.no key=key item=href}-->
<!--{if $pager.current==$key}-->
	<span class="current"><!--{$key}--></span>
<!--{else}-->
	<a href="<!--{$href}-->"><!--{$key}--></a>
<!--{/if}-->
<!--{/foreach}-->
<!--{if $pager.next}-->
	<a href="<!--{$pager.next}-->">下一页</a>
<!--{/if}-->
<!--{if $pager.end}-->
	<a href="<!--{$pager.end}-->">尾页</a>
<!--{/if}-->
&nbsp;
</div>