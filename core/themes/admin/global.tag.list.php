<?php exit?>
<!--{include file="header.php"}-->
<div class="layout-box">
	<div class='layout-title'>添加内链标签</div>
	<div class='layout-body'>
	内链标签：<input type="text" id="tag_name" class="input"/>
	内链标签地址：<input type="text" id="tag_link"  class="input" size="80"/>
	<a href="#" class="button" id="tag_submit" onclick="return insert()">确认添加</a>
	<a href="#" class="button" id="tag_edit_submit" style="display:none">确认修改</a>
	</div>
</div>
<div class="blank"></div>
<div class="layout-box">
	<div class='layout-body'>
	<!--{if $tag}-->
	<form action="?action=global&do=tag_delete" method="post" id="tag_form">
		<table cellspacing="0" cellpadding="0" width="100%" class="table">
			<thead>
			<tr>
			<th align="center" width="40">&nbsp;</td>
			<th align="left" width="150">内链标签</td>
			<th align="left">内链标签地址</td>
			</tr>
			</thead>
			<tbody>
			<!--{foreach from=$tag item=tag}-->
			<tr>
			<td align="center"><input type="checkbox" class="checkbox" name="tag_id[]" value="<!--{$tag.id}-->" /></td>
			<td align="left"><a href="#" id="tag_name_<!--{$tag.id}-->" onclick="edit(<!--{$tag.id}-->)"><!--{$tag.name}--></a></td>
			<td><a href="<!--{$tag.link}-->"  id="tag_link_<!--{$tag.id}-->" target="_blank"><!--{$tag.link}--></td>
			</tr>
			<!--{/foreach}-->
			</tbody>
			<tfoot>
			<td height="30" align="center"><input type="checkbox" onclick="check_all(this)" /></td>
			<td><a href="javascript:del()" class="button">批量删除</a></td>
			<td align="right"><!--{include file="pager.php"}--></td>
			</tr>
			</tfoot>
		</table>
	</form>
	<!--{else}-->
		<div style="text-align:center;line-height:50px;">暂无数据</div>
	<!--{/if}-->
	</div>
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
	$('#tag_form').submit();
}
function insert(){
	var name=$('#tag_name').val();
	var link=$('#tag_link').val();
	if($.trim(name)==''){
		alert('内链标签不能为空');
		return false;
	}
	if($.trim(link)==''){
		alert('URL地址不能为空');
		return false;
	}
	$.ajax({
		type:'GET',
		url:'?action=global&do=tag_insert&tag_name='+encodeURIComponent(name)+'&tag_link='+encodeURIComponent(link),
		success:function(){
			location.reload();
		}
	});
}
function edit(id){
 	var name=$('#tag_name_'+id).html();
	var link=$('#tag_link_'+id).html();
	$('#tag_name').val(name);
	$('#tag_link').val(link);
	$('.layout-title').html('编辑标签');
	$('#tag_submit').hide();
	$('#tag_edit_submit').show().click(function(){
		update(id);
	});
}
function update(id){
	var name=$('#tag_name').val();
	var link=$('#tag_link').val();
	if($.trim(name)==''){
		alert('内链标签不能为空');
		return false;
	}
	if($.trim(link)==''){
		alert('URL地址不能为空');
		return false;
	}
	$.ajax({
		type:'GET',
		url:'?action=global&do=tag_update&tag_id='+id+'&tag_name='+encodeURIComponent(name)+'&tag_link='+encodeURIComponent(link),
		success:function(){
			location.reload();
		}
	});
}
</script>