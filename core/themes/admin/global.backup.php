<?php exit?>
<!--{include file="header.php"}-->
<div class="layout-box">
	<div class='layout-title'>备份管理</div>
	
	<table cellspacing="0" cellpadding="0" width="100%" class="table">
		<!--{if $sqls}-->
		<thead>
		<tr>
		<th>&nbsp;</td>
		<th align="left">文件名</td>
		<th width="120" align="left">生成时间</td>
		<th width="150">操作</td>
		</tr>
		</thead>
		<tbody>
		<!--{foreach from=$sqls item=sql}-->
		<tr>
		<td align="center"><!--{$sql.no}--></td><td><a href="<!--{$path}-->data/backup/<!--{$sql.filename}-->" title="右键另存下载"><!--{$sql.filename}--></a></td><td><!--{$sql.lasttime}--></td>
		<td align="center"><a href="javascript:backup_restore('<!--{$sql.filename}-->')" class="button">恢复</a>&nbsp;<a href="javascript:backup_delete('<!--{$sql.filename}-->')" class="button">删除</a></td>
		</tr>
		<!--{/foreach}-->
		<!--{else}-->
		<tr>
		<td align="center" colspan="4">暂无备份</td>
		</tr>
 		<!--{/if}-->
		</tbody>
		</table>

</div>
<div style="text-align:right"><a href="javascript:backup()" class="button">创建备份</a></div>
<script type="text/javascript">
function backup_restore(filename){
	if(confirm('确定要恢复吗？')){
		$.ajax({
			type:'GET',
			url:'?action=global&do=backup_restore&filename='+filename,
			success:function(e){
				show_tip('备份数据恢复完毕！',2,300);
			}
		});
	}
}
function backup_delete(filename){
	if(confirm('确定要删除吗？')){
		location.href='?action=global&do=backup_delete&filename='+filename;
	}
}
function backup(){
	$.ajax({
		type:'GET',
		url:'?action=global&do=backup_go',
		success:function(e){
			location.reload();
		}
	});
}
</script>