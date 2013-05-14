<?php exit?>
<!--{include file="header.php"}-->
<div class="layout-box">
	<div class='layout-title'>会员角色设置</div>
	<div class='layout-body'>

	<form action="?action=user&do=role_update" method="post" enctype="multipart/form-data">
	<!--{if $role}-->
	<!--{foreach from=$role item=role}-->
	<fieldset>
	<table cellspacing="10">

	<tr>
	<td width="70">角色名称:</td>
	<td><input x-webkit-speech class="input" type="text" name="role_name[<!--{$role.id}-->]" size="25" value="<!--{$role.name|escape:html}-->"  style="width:250px;"/>&nbsp;<input type="checkbox" name="role_status[<!--{$role.id}-->]" value="1" <!--{if $role.status==1}-->checked<!--{/if}--> /> 启用</lable>&nbsp;<input type="checkbox" name="delete_id[]" value="<!--{$role.id}-->" /> 删除</lable></td>
	</tr>
	<tr>
	<td width="70">角色描述:</td>
	<td><textarea class="textarea" name="role_description[<!--{$role.id}-->]" style="width:250px;height:50px;"><!--{$role.description|escape:html}--></textarea></td>
	</tr>
	</table>
	</fieldset>
	<input type="hidden" name="role_id[<!--{$role.id}-->]"  value="<!--{$role.id}-->" />
	<!--{/foreach}-->
	<!--{/if}-->

	<fieldset>
	<legend>添加角色</legend>
	<table cellspacing="10">


	<tr>
	<td width="70">角色名称:</td>
	<td><input x-webkit-speech class="input" type="text" name="role_name_new" size="25" value=""  style="width:250px;"/>&nbsp;<label><input type="checkbox" name="role_status_new" size="15" value="1" checked/> 启用</lable></td>
	</tr>
	<tr>
	<td width="70">角色描述:</td>
	<td><textarea class="textarea" name="role_description_new" style="width:250px;height:50px;"></textarea></td>
	</tr>

	</table>
	</fieldset>
	<input type="submit" value=" 提交 " class="button" style="margin-left:170px;"/>
		</form>
	</div>
</div>