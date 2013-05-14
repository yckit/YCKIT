<?php exit?>
<!--{include file="header.php"}-->
<div class="layout-box">
	<div class='layout-title'>链接管理</div>
	<div class='layout-body'>
	<!--{if $link}-->
	<form action="?action=link&do=link_delete" method="post" id="link_form">
	<table cellspacing="0" cellpadding="0" width="100%" class="table">
		<thead>
		<tr>
		<th width="80" align="center" nowrap="nowrap">排序</th>
		<th align="left">链接名称</td>

		<th align="center" width="140">操作</td>
		</tr>
		</thead>
		<tbody>
		<!--{foreach from=$link item=link}-->
		<tr>
		<td align="center" ><!--{$link.sort}--></td>
		<td><a href="?action=link&do=link_edit&link_id=<!--{$link.id}-->" style="font-weight:bold;color:#B5D53B"><!--{$link.name}--></a><!--{if $link.text}-->&nbsp;(<!--{$link.text}-->)<!--{/if}--></td>

		<td align="right">
			<a href="?action=link&do=link_edit&link_id=<!--{$link.id}-->" class="button">编辑</a>
			<a href="?action=link&do=link_delete&link_id=<!--{$link.id}-->" class="button" onclick="return confirm('确定要删除吗？')">删除</a>
		</td>
		</tr>
		<!--{/foreach}-->
		</tbody>

	</table>
	</form>
	<!--{else}-->
	<div style="line-height:50px;text-align:center;">暂无数据</div>
	<!--{/if}-->
	</div>
</div>
<div style="text-align:right">
<a class="button" href="?action=link&do=link_add">添加链接</a>
</div>