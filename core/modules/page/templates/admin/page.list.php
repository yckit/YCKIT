<?php exit?>
<!--{include file="header.php"}-->
<div class="layout-box">
	<div class='layout-title'>页面管理</div>
	<div class='layout-body'>
	<!--{if $page}-->
	<form action="?action=page&do=page_html" method="post" id="page_form">
	<table cellspacing="0" cellpadding="0" width="100%" class="table">
		<thead>
		<tr>
		<th width="30" align="center" nowrap="nowrap">&nbsp;</th>
		<th align="left">页面标题</td>

		<th align="center" width="200">操作</td>
		</tr>
		</thead>
		<tbody>
		<!--{foreach from=$page item=page}-->
		<tr>
		<td height="30" align="center" ><input type="checkbox" class="checkbox" name="page_id[]" value="<!--{$page.id}-->" /></td>
		<td><a href="?action=page&do=page_edit&page_id=<!--{$page.id}-->" style="font-weight:bold;color:#B5D53B"><!--{$page.title}--></a>&nbsp;(<!--{$page.html}-->.html)</td>

		<td align="center" style="color:#666">
			<a href="<!--{$page.html}-->.html" class="button" target="_blank">预览</a>
			<a href="?action=page&do=page_edit&page_id=<!--{$page.id}-->" class="button">编辑</a>
			<a href="?action=page&do=page_delete&page_id=<!--{$page.id}-->" class="button" onclick="return confirm('确定要删除吗？')">删除</a>
		</td>
		</tr>
		<!--{/foreach}-->
		</tbody>
		<tfoot>
		<tr>
		<td height="30" align="center"><input type="checkbox" onclick="check_all(this)" /></td>
		<td colspan="2">
		<div style="float:right;margin:8px;"><!--{include file="pager.php"}--></div>

		<a href="javascript:html_page()" class="button">批量生成</a> </td>
		</tr>
		</tfoot>
	</table>
	</form>
	<!--{else}-->
	<div style="line-height:50px;text-align:center;">暂无数据</div>
	<!--{/if}-->
	</div>
</div>
<div style="text-align:right">
<a class="button" href="?action=page&do=page_add">添加页面</a>
</div>
<script type="text/javascript">
function html_page(){
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
	if(confirm('确定要操作吗？')){
		$('#page_form').attr('action','?action=page&do=page_html');
		$('#page_form').submit();
	}
}
</script>