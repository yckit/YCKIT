<?php exit?>
<!--{include file="header.php"}-->
<div class="layout">
	<div class='layout-title'>插件管理</div>
		<table cellspacing="0" cellpadding="0" width="100%" class="table">
		<thead>
		<tr>
		<th align="center" width="60">版本</td>
		<th align="left" width="200">插件名</td>
		<th align="left">说明</td>
		<th align="center" width="60">时间</td>
		<th align="center" width="60">作者</td>
		<th align="center" width="120">操作</td>
		</tr>
		</thead>
		<!--{foreach from=$plugin_list item=plugin}-->
		<tbody>
		<tr>
		<td align="center"><!--{$plugin.version}--></td>
		<td><b style="color:#B5D53B"><!--{$plugin.name}--></b></td>
		<td><!--{$plugin.description}--></td>
		<td align="center"><!--{$plugin.date}--></td>
		<td align="center"><!--{$plugin.author}--></td>
		<td>

		<!--{if $plugin.install}-->
			<a  class="button" id="uninstall_<!--{$plugin.dir}-->" href="#" onclick="uninstall('<!--{$plugin.dir}-->')" style="background:#D8D26D;color:#444">卸载</a>
		<!--{else}-->
			<a  class="button" id="install_<!--{$plugin.dir}-->" href="#" onclick="install('<!--{$plugin.dir}-->')">安装</a>
		<!--{/if}-->
		<!--{if $plugin.config}-->
			<a  class="button" href="javascript:void(config('<!--{$plugin.name}-->','<!--{$plugin.dir}-->',<!--{$plugin.config_width|default:400}-->,<!--{$plugin.config_height|default:300}-->))">配置</a>
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
			url:'?action=global&do=plugin_install&dir='+dir,
			type:'get',
			success:function(){
				location.reload();
			}
		})
	}
}
function uninstall(dir){
	if(confirm('确定要卸载吗？\n卸载后可能您的数据将会全部清空！')){
		$('#uninstall_'+dir).html('卸载中...');
		$.ajax({
			url:'?action=global&do=plugin_uninstall&dir='+dir,
			type:'get',
			success:function(){
				location.reload();
			}
		})
	}
}
function config(name,dir,width,height){
	$.ajax({
		url:'?action=global&do=plugin_config&dir='+dir,
		success:function(e){
			$.box({
				title:name+'配置',
				width:width,
				height:height,
				close_button:true,
				html:e,
				callback:function(){
 				 
				}
			});
		}
	});
}
</script>