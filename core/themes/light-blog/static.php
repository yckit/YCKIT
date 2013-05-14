<?php exit?>
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="cache-control" content="no-cache" />
<meta name="viewport" content="width=1024" />
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<?php if($this->check_plugin('rss')):?>
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<!--{$path}-->rss.xml" />
<?php endif;?>
<link rel="shorcut icon" type="image/x-ico" href="<!--{$path}-->core/themes/light-blog/images/favicon.ico" />
<link rel="stylesheet" type="text/css" href="<!--{$path}-->core/themes/light-blog/common.css" media="all"/>
<script src="<!--{$path}-->core/scripts/jquery.js"></script>
<script src="<!--{$path}-->core/scripts/jquery.cookie.js"></script>
<script src="<!--{$path}-->core/scripts/jquery.box.js"></script>
<script src="<!--{$path}-->core/scripts/jquery.editor.js"></script>
<script src="<!--{$path}-->core/themes/light-blog/common.js"></script>
<script>var PATH='<!--{$path}-->';</script>
<?php $this->hook('static');?>