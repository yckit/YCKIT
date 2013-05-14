<?php exit?>
<!--{include file="header.php"}-->
<div class="layout-box">
	<div class='layout-title'><!--{if $mode=='insert'}-->添加链接<!--{else}-->编辑链接<!--{/if}--></div>
	<div class='layout-body'>
<div class='blank'></div>
<form action="?action=link&do=link_<!--{$mode}-->" method="post" enctype="multipart/form-data" name="link_info" onsubmit="return check()">
	<input type="hidden" name="link_id" value="<!--{$link.id}-->"/>
		(链接名称)
		<div class="blank"></div>
		<input x-webkit-speech type="text" class="input" name="link_name" id="link_name" size="40" style="width:200px" value="<!--{$link.name|escape:html}-->"/>
		<label><input type="checkbox" name="link_status" value="1" <!--{if $link.status==1}-->checked<!--{/if}-->/>&nbsp;发布</label>
		<div class="blank"></div>

		(连接地址)
		<div class="blank"></div>
		<input x-webkit-speech type="text" class="input" name="link_url" id="link_url" size="40" style="width:400px" value="<!--{$link.url|escape:html}-->"/>

		<div class="blank"></div>
		(连接说明)
		<div class="blank"></div>
		<input x-webkit-speech type="text" class="input" name="link_text" id="link_text" size="40" style="width:400px" value="<!--{$link.text|escape:html}-->"/>
		<div class="blank"></div>
		(链接排序)
		<div class="blank"></div>
		<input x-webkit-speech type="text" class="input" name="link_sort" id="link_sort" size="40" style="width:100px" value="<!--{$link.sort|escape:html}-->"/>
		<div class="blank"></div>
		<input type="submit" class="button" value="提交"/>
	</form>
</div>
<script type="text/javascript">
function check(){
	var name=$('#link_name').val();
	var url=$('#link_url').val();
	if($.trim(name)==''){
		alert('名称不能为空');
		return false;
	}
	if($.trim(url)==''){
		alert('地址不能为空');
		return false;
	}
	return true;
}
</script>