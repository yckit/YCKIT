<?php exit?>
<!--{include file="header.php"}-->
<div class="layout">
	<div class='layout-title'>投稿管理</div>
	<!--{if $draft}-->
	<form action="?action=content&do=draft_delete" method="post" id="darft_form">
	<table cellspacing="0" cellpadding="0" width="100%" class="table">
		<thead>
		<tr>
		<th width="30" align="center" nowrap="nowrap">&nbsp;</th>
		<th align="left">标题</td>
		<th align="center">作者</td>
		<th align="center" width="150">时间</td>
		</tr>
		</thead>
		<tbody>
		<!--{foreach from=$draft item=draft}-->
		<tr>
		<td height="30" align="center" ><input type="checkbox" class="checkbox" name="draft_id[]" value="<!--{$draft.id}-->" /></td>
		<td>

		<!--{if $draft.category_name}-->
		<a href="?action=content&do=article&category_id=<!--{$draft.category_id}-->" style="font-weight:bold;color:#F5D58D">[<!--{$draft.category_name|escape:html}-->]</a>&nbsp;
		<!--{/if}-->
			<a href="?action=content&do=article_add&draft_id=<!--{$draft.id}-->"><!--{$draft.title|escape:html}--></a>

		</td>
		<td align="center"><!--{$draft.author|escape:html}--></td>
		<td align="center" style="color:#666"><!--{$draft.time}--></td>
		</tr>
		<!--{/foreach}-->
		</tbody>
		<tfoot>
		<tr>
		<td height="30" align="center"><input type="checkbox" onclick="check_all(this)" /></td>
		<td><a href="javascript:del()" class="button">批量删除</a></td>
		<td align="right" colspan="2"><!--{include file="pager.php"}--></td>
		</tr>
		</tfoot>
	</table>
	</form>
	<!--{else}-->
	<div style="line-height:50px;text-align:center;">暂无数据</div>
	<!--{/if}-->
</div>
<script type="text/javascript">
function del(){
	var status=false;
	$('.checkbox').each(function(){
		if($(this).attr('checked')){
			status=true;
		}
	});
	if(!status){
		alert('至少选择一项');
		return false;
	}
	$('#darft_form').submit();
}
</script>