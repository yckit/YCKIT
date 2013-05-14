<?php exit?>
<!--{include file="header.php"}-->
<div class="layout-box">
	<div class='layout-title'><!--{if $mode=='insert'}-->添加角色<!--{else}-->编辑角色<!--{/if}--></div>
	<div class='layout-body'>
		<form action="?action=global&do=admin_<!--{$mode}-->" method="post" name="admin_info">
<table width="100%">

		<tr>
		<td align="right" width="40" height="40">帐号：</td>
		<td><!--{if $mode=='insert'}--><input x-webkit-speech type="text" name="admin_name" size="40" value="<!--{$admin.name|escape:html}-->" class="input"/>
		<!--{else}-->
		<input type="hidden" name="admin_name" value="<!--{$admin.name|escape:html}-->" autocomplete="off"/>
		<!--{$admin.name|escape:html}-->
		<!--{/if}--></td>
		</tr>
		<tr>
		<td align="right"  height="40">密码：</td>
		<td><input x-webkit-speech type="password" name="admin_password" size="40" value="" class="input" autocomplete="off"/></td>
		</tr>
		<!--{if $template.session.admin_name!=$admin.name&&$template.session.admin_type==1}-->

		<tr>
		<td align="right"  height="30">权限：</td>
		<td>
 <br />
			<!--{foreach from=$access key=key item=access}-->
			<fieldset style="float:left;">
				<!--{foreach from=$access key=key2 item=child}-->
					<!--{if $key2=='default'}-->
						<legend><label><input  onclick="select_checkbox(this)" type="checkbox" name="admin_access_parent[]" value="<!--{$key}-->" id="parent_<!--{$key}-->">&nbsp;<b style="color:#B5D53B"><!--{$child}--></b></label></legend>
					<!--{else}-->
						<div><label><input onclick="select_status('<!--{$key}-->')" type="checkbox" name="admin_access[]" class="checkbox_<!--{$key}-->" value="<!--{$key}-->_<!--{$key2}-->" id="child_<!--{$key}-->_<!--{$key2}-->">&nbsp;<!--{$child}--></label></div>
					<!--{/if}-->
				<!--{/foreach}-->
			</fieldset>
			<!--{/foreach}-->
		</td>
		</tr>
		<tr>
		<td align="right" height="30">类型：</td>
		<td>
		<label><input type="radio" name="admin_type" value="1" {if $admin.type==1}checked{/if} /> 超级角色</label>
		<label><input type="radio" name="admin_type" value="0" {if $admin.type==0}checked{/if} /> 普通角色</label>
		</td>
		</tr>
		<tr>
		<td align="right" height="30">状态：</td>
		<td>
		<label><input type="radio" name="admin_status" value="1" {if $admin.status==1}checked{/if} /> 正常</label>
		<label><input type="radio" name="admin_status" value="0" {if $admin.status==0}checked{/if} /> 锁定</label>
		</td>
		</tr>
		<!--{else}-->
		<input type="hidden" name="admin_type" value="<!--{$admin.type}-->" />
		<input type="hidden" name="admin_status" value="<!--{$admin.status}-->" />
		<!--{/if}-->
		<tr>
		<td align="right" height="30">&nbsp;</td>
		<td>
		<input type="submit" value=" 提 交 " class="button"/>
		</td>
		</tr>
		<input type="hidden" name="admin_id" value="<!--{$admin.id}-->"/>
	
	</table>
	</form>
	</div>
</div>
</body>
</html>
<!--{if $template.session.admin_name!=$admin.name&&$template.session.admin_type==1}-->
<script type="text/javascript">
$(function(){
	var access='<!--{$admin.access}-->';
	var status=false;
	var type=access.split("|");
	var parent=type[0].split(",");
	var children=type[1].split(",");
	for (var i=0;i<parent.length ;i++ ){
		$('#parent_'+parent[i]).attr('checked','checked');
	}
	for (var i=0;i<children.length ;i++ ){
		$('#child_'+children[i]).attr('checked','checked');
	}
});
function select_checkbox(obj){
	if(obj.checked){
		$('.checkbox_'+$(obj).val()).attr('checked','checked');
	}else{
		$('.checkbox_'+$(obj).val()).attr('checked','');
	}
}
function select_status(parent){
	var i=0;
	$('.checkbox_'+parent).each(function(e){
		if($(this).attr('checked')==true){
			i++;
		}
	});
	if(i>0){
		$('#'+parent).attr('checked','checked');
	}else{
		$('#'+parent).attr('checked','');
	}
}

</script>
<!--{/if}-->