<?php exit?>
<!--{include file="header.php"}-->
<div class="layout-box">
	<div class='layout-title'><!--{if $mode=='insert'}-->添加评论<!--{else}-->编辑评论<!--{/if}--></div>
	<div class='layout-body'>
		<form action="?action=content&do=comment_<!--{$mode}-->" method="post" enctype="multipart/form-data" name="comment_info" onsubmit="return check()">
		<input type="hidden" name="comment_id" value="<!--{$comment.id}-->"/>
		<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
		<td width="100">(评论内容)</td>
		<td align="right">
			<b style="color:#ccc">评论对象</b>：<a href="?action=content&do=article_edit&article_id=<!--{$comment.article_id}-->"><!--{$comment.article_title}--></a>
			&nbsp;&nbsp;&nbsp;&nbsp;
		</td>
		</tr>
		</table>
		<div class="blank"></div>
		<textarea class="textarea" name="comment_content" style="width:99%;height:60px"><!--{$comment.content|escape:html}--></textarea>
		<div class="blank"></div>
		<table width="100%" cellspacing="0" cellpadding="0">
		<tr>
		<td width="100">(评论回复)</td>
		<td align="right">
			<b style="color:#ccc">评论者</b>：<!--{$comment.name}-->
		<b style="color:#ccc">时间</b>：<!--{$comment.time}-->
		<b style="color:#ccc">IP</b>：<!--{$comment.ip}-->（<!--{$comment.ip_address}-->）
		<b style="color:#ccc">邮箱</b>：<!--{$comment.email}-->
			<!--{if $comment.site}-->
				<b style="color:#ccc">主页</b>：<a href="<!--{$comment.site}-->" target="_blank"><!--{$comment.site}--></a>
			<!--{/if}-->
			&nbsp;&nbsp;&nbsp;&nbsp;
		</td>
		</tr>
		</table>
		<div class="blank"></div>
		<textarea class="textarea" name="comment_reply" style="width:99%;height:60px"><!--{$comment.reply|escape:html}--></textarea>
		<div class="blank" style="padding:5px;">
			<input type="submit" class="button" value=" 提 交 "/>
			<input type="reset" class="button" value=" 重 置 "/>
			<input type="button" id="cancel" class="button" value=" 取 消 "/>
			<label><input type="checkbox" name="comment_is_display" value="1" <!--{if $comment.is_display==1}-->checked<!--{/if}-->/>&nbsp;发布</label>

			<label><input type="checkbox" name="comment_is_email" value="1" />&nbsp;邮件通知</label>
		</div>
		<input type="hidden" name="comment_email" value="<!--{$comment.email}-->" />
		<input type="hidden" name="article_id" value="<!--{$comment.article_id}-->" />
	</form>
</div>