<?php exit?>
<script>
function check_change(path){
	var user_key_old=$('#user_key_old').val();
	var user_key=$('#user_key').val();
	if($.trim(user_key_old)==''){
		alert('当前密码不能为空');
		$('#user_key_old').focus();
		return false;
	}
	if($.trim(user_key)==''){
		alert('新密码不能为空');
		$('#user_key').focus();
		return false;
	}
	$.ajax({
		url:path+'front.php?action=user&do=password-check',
		data:$(".user_form").serialize(),
		type: 'POST',
		dataType:'html',
		success:function(e){
			if(e==''){
				box_close();
				get_user_info(path);
			}else{
				alert(e);
				$('#user_key_old').focus();
			}
		}
	})
	return false;
}
</script>
<form method="post"  class="user_form" onsubmit="return check_change('<!--{$path}-->')">
<table cellspacing="15">
<tr>
<td align="right">请输入当前密码：</td>
<td><input type="password" name="user_key_old" id="user_key_old" class="input" size="30"/></td>
</tr>

<tr>
<td align="right">请输入新的密码：</td>
<td><input type="password" name="user_key" id="user_key" class="input" size="30"/></td>
</tr>

<tr>
<td>&nbsp;</td>
<td><input type="submit" value=" 提交 " id="user_submit" class="btn"/></td>
<td></td>
</tr>
</table>
</form>
