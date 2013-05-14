<?php exit?>
<!--{include file="header.php"}-->
<div class="layout">
	<div class='layout-title'>评论管理</div>
	<!--{if $comment}-->
	<form action="?action=page&do=comment_delete" method="post" id="comment_form">
	<table cellspacing="0" cellpadding="0" width="100%" class="table">
		<thead>
		<tr>
		<th align="center" width="40">&nbsp;</th>
		<th align="center" width="60">头像</th>

		<th align="left" >评论内容</td>
		<th align="left"  width="200">IP</td>
		</tr>
		</thead>
		<tbody>
		<!--{foreach from=$comment item=comment}-->
		<tr>
		<td style="height:60px;" align="center" ><input type="checkbox" class="checkbox" name="comment_id[]" value="<!--{$comment.id}-->" /></td>
		<td style="height:60px;" align="center" ><img src="<!--{$comment.avatar}-->" width="36" height="36" style="border-radius:2px;"/></td>
		<td>
			<b style="color:#88D536"><!--{$comment.name|escape:html}--></b>：<a href="?action=page&do=comment_edit&page_id=<!--{$comment.page_id}-->&comment_id=<!--{$comment.id}-->"><!--{$comment.content|escape:html}--></a>
			<!--{if $comment.is_display==0}-->
			<b style="color:#FE8217">[审核中]</b>
			<!--{/if}-->
			<div class="blank"></div>
			<b style="color:#ccc">时间</b>：<!--{$comment.time}-->
			<b style="color:#ccc">邮箱</b>：<!--{$comment.email|escape:html}-->
			<!--{if $comment.site}-->
				<b style="color:#ccc">主页</b>：<a href="<!--{$comment.site}-->" target="_blank"><!--{$comment.site|escape:html}--></a>
			<!--{/if}-->
		</td>
		<td style="color:#666"><!--{$comment.ip}--><div class="blank"></div><!--{$comment.ip_address}--></td>
		</tr>
			<!--{if $comment.reply}-->
			<tr>
			<td colspan="2">&nbsp;</td><td><b style="color:#BF9A61">回复：<!--{$comment.reply|escape:html}--></b></td><td>&nbsp;</td>
			</tr>
			<!--{/if}-->
<!--{if $comment.children}-->
			<tr>
			<td>&nbsp;</td><td colspan="3">

			<table cellspacing="0" cellpadding="0" width="100%" class="table">
			<!--{foreach from=$comment.children item=child}-->
			<tr>
			<td style="height:60px;width:40px;" align="center" ><input type="checkbox" class="checkbox" name="comment_id[]" value="<!--{$child.id}-->" /></td>
			<td style="height:60px;width:60px;" align="center" ><img src="<!--{$child.avatar}-->" width="36" height="36" style="border-radius:2px;"/></td>
			<td>
				<b style="color:#88D536"><!--{$child.name|escape:html}--></b>：<a href="?action=content&do=comment_edit&content_id=<!--{$child.content_id}-->&comment_id=<!--{$child.id}-->"><!--{$child.content|escape:html}--></a>
				<!--{if $child.is_display==0}-->
				<b style="color:#FE8217">[审核中]</b>
				<!--{/if}-->
				<div class="blank"></div>
				<b style="color:#ccc">时间</b>：<!--{$child.time}-->
				<b style="color:#ccc">邮箱</b>：<!--{$child.email|escape:html}-->
				<!--{if $child.site}-->
					<b style="color:#ccc">主页</b>：<a href="<!--{$child.site}-->" target="_blank"><!--{$child.site|escape:html}--></a>
				<!--{/if}-->
			</td>
			<td style="color:#666" width="200"><!--{$child.ip}--><div class="blank"></div><!--{$child.ip_address}--></td>
			</tr>
				<!--{if $child.reply}-->
				<tr>
				<td colspan="2">&nbsp;</td><td><b style="color:#BF9A61">回复：<!--{$child.reply|escape:html}--></b></td><td>&nbsp;</td>
				</tr>
				<!--{/if}-->

			<!--{/foreach}-->
			</table>


			</td>
			</tr>
				<!--{/if}-->
		<!--{/foreach}-->
		</tbody>
		<tfoot>
		<tr>
		<td height="30" align="center"><input type="checkbox" onclick="check_all(this)" /></td>
		<td colspan="3">
		<div style="float:right;margin:8px;"><!--{include file="pager.php"}--></div>
		<a href="javascript:audit()" class="button">批量审核</a>
		 <a href="javascript:del()" class="button">批量删除</a></td>

		</td>
		</tr>
		</tfoot>
	</table>
	</form>
	<!--{else}-->
	<div style="line-height:50px;text-align:center;">暂无数据</div>
	<!--{/if}-->

</div>
<script type="text/javascript">
function audit(){
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
	$('#comment_form').attr("action","?action=page&do=comment_audit");
	$('#comment_form').submit();
}
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
	$('#comment_form').submit();
}
</script>