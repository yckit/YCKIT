<?php exit?>
<div id="footer">
	Powered By <a href="http://yckit.com" target="_blank"><u>YCKIT</u></a> {$config.version} / Theme Light-BLOG
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