<?php exit?>
<script>
function check_reset(){
	var user_key=$('#user_key').val();
	if($.trim(user_key)==''){
		alert('新密码不能为空');
		$('#user_key').focus();
		return false;
	}
	$.ajax({
		url:PATH+'front.php?action=user&do=reset-check',
		data:$(".user_form").serialize(),
		type: 'POST',
		dataType:'html',
		success:function(e){
			if(e==''){
				box_close();
				alert('重置完毕');
			}else{
				alert(e);
				$('#user_login').focus();
			}
		}
	})
	return false;
}
</script>

<form method="post" class="user_form" onsubmit="return check_reset()">
<table class="table">
<tr>
<td align="right">请输入重置密码：</td>
<td><input type="password" name="user_key" id="user_key" class="input" size="30"/></td>
</tr>
<tr>
<td>&nbsp;</td>
<td><input type="submit" value=" 提交 " id="user_submit" class="btn"/></td>
<td></td>
</tr>
</table>
<input type="hidden" name="active" value="<!--{$active}-->"/>
</form>
