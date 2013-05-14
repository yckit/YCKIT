<?php exit?>
<!--{include file="header.php"}-->
<div class="layout-box">
	<div class='layout-title'><!--{if $mode=='insert'}-->添加会员<!--{else}-->编辑会员<!--{/if}--></div>
	<div class='layout-body'>
<div class='blank'></div>
<form action="?action=user&do=<!--{$mode}-->" method="post" enctype="multipart/form-data" id="user_info" name="user_info">
<input type="hidden" name="user_id" value="<!--{$user.id}-->"/>
		(帐号:)
		<div class="blank"></div>
		<input  maxlength="100" <!--{if $mode=='update'}-->disabled<!--{/if}--> type="text" class="input" name="user_login" id="user_login" style="width:300px" value="<!--{$user.login|escape:html}-->" x-webkit-speech/>
		<label><input type="checkbox" name="user_status" value="1" <!--{if $user.status==1}-->checked<!--{/if}-->/>&nbsp;启用</label>
		<div class="blank"></div>
		(密码:不能超过20字)<div class="blank"></div>
		<input x-webkit-speech type="password"  minlength="5" maxlength="20" class="input" name="user_key" style="width:300px;" /><div class="blank"></div>

		(昵称:)<div class="blank"></div>
		<input type="text" maxlength="10" class="input" name="user_nickname" style="width:200px;" value="<!--{$user.nickname}-->"/><div class="blank"></div>
		(积分:不能超过20字)<div class="blank"></div>
		<input type="number" class="input" name="user_point" style="width:100px;" value="<!--{$user.point}-->"/><div class="blank"></div>
		(角色:分配角色可区分用户类型)<div class="blank"></div>
		<select name="role_id" class="select">
		<option value="0">请选择角色...</option>
		<!--{foreach from=$role item=role}-->
		<option value="<!--{$role.id}-->" <!--{if $role.id=$user.role_id}-->selected<!--{/if}-->><!--{$role.name|escape:html}--></option>
		<!--{/foreach}-->
		</select>
		<div class="blank">
			<input type="submit" class="button" value=" 提 交 "/>
			<input type="reset" class="button" value=" 重 置 "/>
			<input type="button" id="cancel" class="button" value=" 取 消 "/>
		</div>
	</form>
</div>