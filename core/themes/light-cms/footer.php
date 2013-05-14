<?php exit?>
<div id="footer">
	Powered By <a href="http://yckit.com" target="_blank"><u>YCKIT</u></a>  {$config.version}/ Theme Light-CMS
	<?php if($this->check_plugin('sitemap')):?>/<a href="<!--{$path}-->sitemap.xml">SITEMAP</a><?php endif;?>
	<!--{$config.statcode}-->
</div>

<script>
$(function(){
	$("#totop").scrollToTop();
});
</script>
<a id="totop" href="#index"></a>
<script type="text/javascript" src="<!--{$path}-->core/scripts/prettify.js"></script>
<link rel="stylesheet" type="text/css" href="<!--{$path}-->core/styles/prettify.css" />
<script type="text/javascript">prettyPrint();</script>
<!-- Baidu Button BEGIN -->
<script type="text/javascript" id="bdshare_js" data="type=slide&amp;img=8&amp;pos=left&amp;uid=0" ></script>
<script type="text/javascript" id="bdshell_js"></script>
<script type="text/javascript">
var bds_config={"bdTop":0};
document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + Math.ceil(new Date()/3600000);
</script>
<!-- Baidu Button END -->