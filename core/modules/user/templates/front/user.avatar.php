<?php exit?>
<div id="avatar-bg">本地上传<div id="avatar"></div></div>
<script src="{$path}core/scripts/swfobject.js"></script>
<script>
swfobject.embedSWF(
	"{$path}core/modules/user/templates/front/images/avatar.swf?uploadURL="+encodeURI("{$path}front.php?action=user%26do=avatar-upload"),
	"avatar","150","150","10.2","", 
	{},
	{ quality: "high", scale: "noscale", wmode: "transparent", allowscriptaccess: "always"},
	{ id: "videos", name: "videos"}
 ); 
 var frame={};
	 frame.uploadSuccess=function(){
	 	box_close();
	 	get_user_info("{$path}");
	 }
</script>