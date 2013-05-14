<?php exit?>
<!--{include file="header.php"}-->
<div class="layout-box">
 	<div class='layout-title'>菜单管理</div>
	<!--{if $menu}-->
	<table cellspacing="0" cellpadding="0" width="100%" class="table">
		<thead>
		<tr>
		<th align="center" width="50">编号</td>
		<th align="left">菜单名称</td>
		<th align="left" width="200">菜单地址</td>
		<th align="left" width="50">打开方式</td>
		<th align="center" width="120">操作</td>
		</tr>
		</thead>
	<!--{foreach from=$menu item=menu}-->
	<tr>
	<td height="28" align="center" width="40"><!--{$menu.id}--></td>
	<td><a href="?action=global&do=menu_edit&menu_id=<!--{$menu.id}-->" class="name"><b class="note"><!--{$menu.name}--></b></a><!--{if $menu.description}-->&nbsp;(<!--{$menu.description}-->)<!--{/if}--></td>
	<td><!--{$menu.link}--></td>
		<td align="center"><!--{if $menu.target==0}-->原页<!--{else}-->弹窗<!--{/if}--></td>
	<td align="center">
		<a href="?action=global&do=menu_edit&menu_id=<!--{$menu.id}-->" class="button">编辑</a>
		<a href="?action=global&do=menu_delete&menu_id=<!--{$menu.id}-->" onclick="return confirm('确认要删除吗？')" class="button">删除</a>
	</td>
	</tr>
		<!--{foreach from=$menu.children item=child}-->
		<tr>
		<td height="28" align="center" width="40">&nbsp;</td>
		<td>&nbsp;&nbsp;&nbsp;&nbsp;<a href="?action=global&do=menu_edit&menu_id=<!--{$child.id}-->" class="name"><b class="note"><!--{$child.name}--></b></a><!--{if $child.description}-->&nbsp;(<!--{$child.description}-->)<!--{/if}--></td>
		<td><!--{$child.link}--></td>
			<td align="center"><!--{if $child.target==0}-->原页<!--{else}-->弹窗<!--{/if}--></td>
		<td align="center">
			<a href="?action=global&do=menu_edit&menu_id=<!--{$child.id}-->" class="button">编辑</a>
			<a href="?action=global&do=menu_delete&menu_id=<!--{$child.id}-->" onclick="return confirm('确认要删除吗？')" class="button">删除</a>
		</td>
		</tr>
		<!--{/foreach}-->
	<!--{/foreach}-->
	</table>
	<!--{else}-->
		<div style="line-height:50px;text-align:center;">暂无数据</div>
	<!--{/if}-->
 
</div>
<div style="text-align:right">
	<a class="button" href="?action=global&do=menu_add">添加菜单</a>
</div>