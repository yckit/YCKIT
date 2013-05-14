<?php exit?>
<!--{include file="header.php"}-->
<div class="layout-box">
	<div class='layout-title'>会员模块设置</div>
	<div class='layout-body'>
	<div class="blank"></div>
	<form action="?action=user&do=config_update" method="post" enctype="multipart/form-data">

	<fieldset>
	<legend>开关控制</legend>
	<table cellspacing="10">
 
	<tr>
	<td width="120">会员登录状态:</td>
	<td>
	<label><input type="checkbox" name="user_front_login" size="15" value="1" {if $config.user_front_login==1}checked{/if}/> 启用</lable>
	</td>
	</tr>
	<tr>
	<td width="120">会员注册状态:</td>
	<td>
	<label><input type="checkbox" name="user_front_join" size="15" value="1" {if $config.user_front_join==1}checked{/if}/> 启用</lable>
	</td>
	</tr>

	<tr>
	<td width="120">会员注册激活:</td>
	<td>
	<label><input type="checkbox" name="user_front_join_active" size="15" value="1" {if $config.user_front_join_active==1}checked{/if}/> 启用</lable>
	</td>
	</tr>

	</table>
	</fieldset>
	<fieldset>
	<legend>后台设置</legend>
	<table cellspacing="10">
	<tr>
	<td width="120">后台会员列表条数:</td>
	<td><input x-webkit-speech class="input" type="number" name="user_admin_list_size" size="15" value="{$config.user_admin_list_size}"/></td>
	</tr>
 
	</table>
	</fieldset>
	<fieldset>
	<legend>QQ登陆设置</legend>
	<table cellspacing="10">
	<tr>
	<td width="120">开关:</td>
	<td>
	<label><input type="checkbox" name="qq" size="15" value="1" {if $config.qq==1}checked{/if}/> 启用</lable>
	</td>
	</tr>
	<tr>
	<td width="120">APP ID:</td>
	<td><input class="input" type="text" name="qq_appid" size="60" value="{$config.qq_appid}"/></td>
	</tr>
 	<tr>
	<td width="120">APP KEY:</td>
	<td><input class="input" type="text" name="qq_appkey" size="60" value="{$config.qq_appkey}"/></td>
	</tr>
  	<tr>
	<td width="120">APP 回调网址:</td>
	<td><input class="input" type="text" name="qq_appcallback" size="60" value="{$config.qq_appcallback}"/></td>
	</tr>
 
	</table>
	</fieldset>
	<input type="submit" value=" 更新设置 " class="button" style="margin-left:170px;"/>
		</form>
	</div>
</div>