function get_user_info(){
	$.ajax({
		type:'get',
		url:PATH+'front.php?action=user&do=info',
		success:function(e){
			$('#info').html(e);
		}
	});
}
function logout(){
	box_close();
	$.ajax({
		url:PATH+'front.php?action=user&do=logout',
		success:function(){
			window.location.reload();
		}
	})
}
function user_box(title,width,height,action){
	$.ajax({
		url:PATH+'front.php?action=user&do='+action,
		success:function(e){
			$.box({
				title:title,
				width:width,
				height:height,
				close_button:true,
				html:e,
				callback:function(){

				}
			});	

		}
	})
}
$(function(){
	var hash=window.location.hash;
	if(hash.indexOf("active")!=-1){
		hash=hash.replace("#active:","");
		if($.trim(hash)!=''){
			$.ajax({
				url:PATH+'front.php?action=user&do=active&active='+hash,
				success:function(e){
					if(e==''){
						alert("您的帐号已激活！");
						location.href=PATH;
					}else{
						alert(e);
					}

				}
			});
		}
	}
	if(hash.indexOf("reset")!=-1){
		hash=hash.replace("#reset:","");
		if($.trim(hash)!=''){
 			user_box('重设密码',400,150,'reset&active='+hash);
		}
	}
	if(hash.indexOf("join")!=-1){
		hash=hash.replace("#","");
		if($.trim(hash)!=''){
 			user_box('注册会员',400,300,'join');
		}
	}
});