<?php exit?>
<!--{if $config.user_front_join==0}-->
<div style="line-height:200px;text-align:center">注册会员已关闭</div>
<!--{else}-->
<script>
function check_join(){
	var user_login=$('#user_login').val();
	var user_key=$('#user_key').val();
	var user_nickname=$('#user_nickname').val();
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
	if($.trim(user_nickname)==''){
		alert('昵称不能为空');
		$('#user_nickname').focus();
		return false;
	}
	$.ajax({
		url:PATH+'front.php?action=user&do=join-check',
		data:$(".user_form").serialize(),
		type: 'POST',
		dataType:'html',
		success:function(e){
			if(e=='LOGIN'){
				window.location.href=window.location.toString().replace("#join","");
			}else if(e=='ACTIVE'){
				alert('感谢您的注册，请查收您的邮箱进行验证。');
				window.location.href=window.location.toString().replace("#join","");
			}else{
				alert(e);
				$('#user_login').focus();
			}
		}
	})
	return false;
}
</script>
<form method="post" class="user_form" onsubmit="return check_join()">
<table cellspacing="0" cellpadding="0">
<tr>
<td align="right">邮箱：</td>
<td><input type="text" name="user_login" id="user_login" class="input" size="30"  tabindex="1"/></td>
<td><a href="javascript:user_box('会员登陆',400,220,'login');">登录会员</a></td>
</tr>
<tr>
<td align="right">密码：</td>
<td><input type="password" name="user_key" id="user_key" class="input" size="30" maxlength="20"  tabindex="2"/></td>
<td><a href="javascript:javascript:user_box('找回密码',400,150,'forget');;">找回密码</a></td>
</tr>
<tr>
<td align="right">确认：</td>
<td><input type="password" name="user_key_confirm" id="user_key_confirm" class="input" size="30" maxlength="20"  tabindex="3"/></td>
</tr>
<tr>
<td align="right">昵称：</td>
<td><input type="text" name="user_nickname" id="user_nickname" value="{$template.session.qq_nickname}" class="input" size="30" maxlength="10"  tabindex="4"/></td>
<td></td>
</tr>
<tr>
<td>&nbsp;</td>
<td><input type="submit" value=" 注 册 " id="user_submit" class="btn"/>
<!--{if $config.qq}-->
<a href="{$path}front.php?action=user&do=qq"><img src="{$path}core/modules/user/templates/front/images/qq_login.gif" align="absmiddle"/></a>
<!--{/if}-->
</td>
<td></td>
</tr>
</table>
</form>
<!--{/if}-->