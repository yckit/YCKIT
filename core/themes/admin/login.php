<?php exit?><!doctype html><html><head><meta charset="utf-8" />
<title><!--{$config.title}--></title>
<style type="text/css">
html,body{overflow:hidden;font:normal 12px 'Microsoft Yahei';background:#111;color:#666;padding:0;margin:0;}
body{border-top:5px solid #333}
#login_form{padding:40px;height:220px;}
#login_inner{margin:auto;padding:30px 40px;width:270px;background:#151515;border-radius:2px;}
#other{display:none;position:absolute;left:0;top:0;width:100%;+width:105%;height:30px;line-height:30px;text-align:center;background:#000;}
#other a{color:#FFAA24}
#other a:hover{color:#fff}
#admin_name,#admin_password{width:250px;border:2px solid #ccc;background:#eed;padding:8px;font:normal 12px 'Microsoft Yahei';outline:0;color:#4b751d}
#admin_name:focus,#admin_password:focus{border:2px solid #fc6b13;}
#admin_submit{border-radius:2px;border:none;margin:0;padding:9px 15px;_padding:5px 8px;background:#fc6b13;font:NORMAL 12px arial;color:#fff}
</style>
<script type="text/javascript" src="core/scripts/jquery.js"></script>
<script type="text/javascript">
function check(){
	$('#admin_submit').attr("disabled",true).val("loading...");
	var admin_name=$('#admin_name').val();
	var admin_password=$('#admin_password').val();
	var admin_captcha=$('#admin_captcha').val();

	if($.trim(admin_name)==''){
		alert('[管理员不能为空]');
		$('#admin_name').focus();
		$('#admin_submit').attr("disabled",false).val("LOGIN");
		return false;
	}
	if($.trim(admin_password)==''){
		alert('[管理员密码不能为空]');
		$('#admin_password').focus();
		$('#admin_submit').attr("disabled",false).val("LOGIN");
		return false;
	}
	return true;
}
$(function(){
	var isIE=('v'=='\v');
	if(isIE){
		$('#other').slideDown('slow');
	}
	var height=$(window).height();
	$('#login_form').css({marginTop:height/2-200})
	$('#admin_password').focus();
});

</script>
</head>
<body>


<form action="" method="post" id="login_form" onsubmit="return check()">
<div id="login_inner">
<strong>管理员帐号</strong>：
<div style="padding:3px"></div>
<input type="text" name="admin_name"  id="admin_name" placeholder="请输入管理员帐号" value="admin"  required x-webkit-speech/>
<div style="padding:3px"></div>
<strong>管理员密码</strong>：
<div style="padding:3px"></div>
<input type="password" name="admin_password" id="admin_password" placeholder="请输入密码" autofocus required x-webkit-speech/>
<div style="padding:5px"></div>
<div style="text-align:right">
<input type="submit" value="LOGIN" id="admin_submit"/>
</div>
</div>
</form>
<div id="other">
温馨提示：为了保证最佳效果建议使用 <a href="http://info.msn.com.cn/ie9/" target="_blank">IE9</a>、<a href="http://www.google.com/chrome" target="_blank">谷歌浏览器(Chrome)</a>、<a href="http://www.firefox.com.cn/download/" target="_blank">火狐(FireFox5+)</a>、<a href="http://www.apple.com.cn/safari/download/" target="_blank">Safari</a>、<a href="http://www.opera.com/" target="_blank">Opera</a></div>
</body>
</html>