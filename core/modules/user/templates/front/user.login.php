<?php exit?>
<!--{if $config.user_front_login==0}-->
<div style="line-height:150px;text-align:center">登录会员已关闭</div>
<!--{else}-->
<script>
function check_login(){
	var user_login=$('#user_login').val();
	var user_key=$('#user_key').val();
	if($.trim(user_login)==''){
		alert('帐号不能为空');
		$('#user_login').focus();
		return false;
	}
	if($.trim(user_key)==''){
		alert('密码不能为空');
		$('#user_key').focus();
		return false;
	}
	$.ajax({
		url:PATH+'front.php?action=user&do=login-check',
		data:$(".user_form").serialize(),
		type: 'POST',
		dataType:'html',
		success:function(e){
			if(e==''){
				window.location.reload();
			}else{
				alert(e);
				$('#user_login').focus();
			}
		}
	})
	return false;
}
</script>
<form method="post" class="user_form" onsubmit="return check_login()">
<table cellspacing="0" cellpadding="0">
<tr>
<td align="right" nowrap>帐号：</td>
<td><input type="text" name="user_login" id="user_login" class="input" size="30" tabindex="1"/></td>
<td nowrap><a href="javascript:user_box('会员注册',400,300,'join');">注册会员</a></td>
</tr>
<tr>
<td align="right">&nbsp;</td>
<td colspan="2" class="tip">可以使用E-mail或昵称来进行登陆</td>
</tr>
<tr>
<td align="right" nowrap>密码：</td>
<td><input type="password" name="user_key" id="user_key" class="input"  size="30" tabindex="2"/></td>
<td nowrap><a href="javascript:user_box('找回密码',400,150,'forget');">找回密码</a></td>
</tr>

<tr>
<td>&nbsp;</td>
<td><input type="submit" value=" 登 录 " id="user_submit" class="btn" tabindex="3"/>
<!--{if $config.qq}-->
<a href="{$path}front.php?action=user&do=qq"><img src="{$path}core/modules/user/templates/front/images/qq_login.gif" align="absmiddle"/></a>
<!--{/if}-->
</td>
<td></td>
</tr>
</table>
</form>
<!--{/if}-->