<?php exit?>
<!--{include file="header.php"}-->
<div class="layout">
	<div class='layout-title'>模块管理</div>
		<table cellspacing="0" cellpadding="0" width="100%" class="table">
		<thead>
		<tr>
		<th align="center" width="60">版本</td>
		<th align="left">模块介绍</td>
		<th align="center" width="80">状态</td>

		<th align="center" width="80">发布时间</td>
		<th align="center" width="80">作者</td>
		<th align="center" width="100">操作</td>
		</tr>
		</thead>
		<!--{foreach from=$module_list item=module}-->
		<tbody>
		<tr>
		<td align="center"><!--{$module.version}--></td>
		<td><b class="color-white"><!--{$module.name}--></b>&nbsp;(<!--{$module.description}-->)</td>
		<td align="center">
		<!--{if $module.install}-->
			<b class="color-green">启用</b>
		<!--{else}-->
			<b class="color-red">禁用</b>
		<!--{/if}-->
		</td>

		<td align="center"><!--{$module.date}--></td>
		<td align="center"><!--{$module.author}--></td>
		<td align="center">
		<!--{if $module.install}-->
			<a  class="button" id="uninstall_<!--{$module.dir}-->" href="#" onclick="uninstall('<!--{$module.dir}-->')" style="background:#D8D26D;color:#444">卸载</a>
		<!--{else}-->
			<a  class="button" id="install_<!--{$module.dir}-->" href="#" onclick="install('<!--{$module.dir}-->')">安装</a>
		<!--{/if}-->

		</td>
		</tr>
		</tbody>
		<!--{/foreach}-->
		</table>
</div>
<script type="text/javascript">
function install(dir){
	if(confirm('确定要安装吗？')){
		$('#install_'+dir).html('安装中...');
		$.ajax({
			url:'?action=global&do=module_install&dir='+dir,
			type:'get',
			success:function(e){
				location.reload();
			}
		})
	}
}
function uninstall(dir){
	if(confirm('确定要卸载吗？\n卸载后可能您的数据将会全部清空！')){
		$('#uninstall_'+dir).html('卸载中...');
		$.ajax({
			url:'?action=global&do=module_uninstall&dir='+dir,
			type:'get',
			success:function(e){
				location.reload();
			}
		})
	}
}
</script>