<?php exit?>
<script>
function check_forget(){
	var user_login=$('#user_login').val();
	if($.trim(user_login)==''){
		alert('邮箱不能为空');
		$('#user_login').focus();
		return false;
	}
	box_close();
	$.ajax({
		url:PATH+'front.php?action=user&do=forget-check',
		data:$(".user_form").serialize(),
		type: 'POST',
		dataType:'html',
		success:function(e){
			if(e==''){
				alert('请查收您的邮箱进行完成重置密码');
			}else{
				alert(e);
				$('#user_login').focus();
			}
		}
	})
	return false;
}
</script>
<form action="" method="post" class="user_form" onsubmit="return check_forget()">
<table cellspacing="5">
<tr>
<td align="right">请输入邮箱：</td>
<td><input type="text" name="user_login" id="user_login" class="input" size="30"/></td>
</tr>
<tr>
<td>&nbsp;</td>
<td><input type="submit" value=" 提交 " id="user_submit" class="btn"/></td>
<td></td>
</tr>
</table>
</form>
