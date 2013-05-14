<?php exit?>
<!--{include file="header.php"}-->
<div class="layout">
	<div class='layout-title'>栏目管理</div>
	<!--{if $category_html}-->
	<form action="?action=content&do=category_clear" method="post" id="category_form">
		<table cellspacing="0" cellpadding="0" width="100%" class="table">
		<thead>
		<tr>
		<th align="center" width="50"></td>
		<th align="left">栏目名称</td>
		<th align="left" width="400">栏目目录</td>
		<th align="center" width="120">操作</td>
		</tr>
		</thead>
		<!--{$category_html}-->
		<tfoot>
		<tr>
		<td height="30" align="center"><input type="checkbox" onclick="check_all(this)" /></td>
		<td colspan="3">
			{if $config.content_mode==2}<a href="javascript:;" onclick="category_html()" class="button">批量生成</a> {/if}
			<a href="javascript:;" onclick="category_clear()" class="button">清理生成后的HTML文件</a>
			
		</td>
		</tr>
		</tfoot>
		</table>
		</form>
	<!--{else}-->
		<div style="text-align:center;line-height:50px">暂无数据</div>
	<!--{/if}-->
</div>
<div style="text-align:right">
	<a class="button" href="?action=content&do=category_add">添加栏目</a>
</div>
<script type="text/javascript">
function category_clear(){
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
		$('#category_form').attr('action','?action=content&do=category_clear');
		$('#category_form').submit();
	}
}
function category_html(){
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
		$('#category_form').attr('action','?action=content&do=category_html');
		$('#category_form').submit();
	}
}
</script>