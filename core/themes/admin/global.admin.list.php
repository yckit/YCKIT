<?php exit?>
<!--{include file="header.php"}-->
<div class="layout-box">
	<div class='layout-title'>角色管理</div>
	<table cellspacing="0" cellpadding="0" width="100%" class="table">
		<thead>
		<tr>
		<th align="center">编号</td>
		<th align="left">帐号</td>
		<th>类型</td>
		<th>登陆次数</td>
		<th>最后登陆时间</td>
		<th>最后登陆IP</td>
		<th align="center" width="150">操作</td>
		</tr>
		</thead>
	<!--{foreach from=$admin item=admin}-->
	<tr>
	<td height="28" align="center" width="40"><!--{$admin.id}--></td>
	<td>
	<!--{if $template.session.admin_name!=$admin.name&&$template.session.admin_type==1}-->
	<a href="?action=global&do=admin_edit&admin_id=<!--{$admin.id}-->" class="name"><!--{$admin.name}--></a>
	<!--{else}-->
		<!--{$admin.name}-->
	<!--{/if}-->
	</td>
	<td align="center"><!--{if $admin.type==1}--><span style="font-weight:bold;color:#9FD538">超级角色</span><!--{else}-->普通角色<!--{/if}--></td>
	<td align="center"><!--{$admin.login_time}--></td>
	<td align="center"><!--{$admin.last_time}--></td>
	<td align="center"><!--{$admin.last_ip}--></td>
	<td align="center">
	<!--{if $template.session.admin_name!=$admin.name&&$template.session.admin_type==1}-->
		<a href="?action=global&do=admin_delete&admin_id=<!--{$admin.id}-->" onclick="return confirm('确认要删除吗？')">删除</a>
	<!--{else}-->
	-
	<!--{/if}-->
	</td>
	</tr>
	<!--{/foreach}-->
	</table>
</div>
<div style="text-align:right">
	<a class="button" href="?action=global&do=admin_add">添加角色</a>
</div>