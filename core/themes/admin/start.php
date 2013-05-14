<?php exit?>
<!--{include file="header.php"}-->
<!--{if $check_unsafe }-->
<p class="unsafetip">
<!--{foreach from=$check_unsafe item=tip}-->
<!--{$tip}--><br />
<!--{/foreach}-->
</p>
<!--{/if}-->
<div class="layout-box">
	<div class='layout-title'>服务器概况</div>
	<div class='layout-body'>
		<ul>
		<li>服务器时间：<!--{$server.time}--></li>
		<li>服务器端口：<!--{$server.port}--></li>
		<li>服务器域名：<a href='./' target="_blank" style="color:#B5D53B;text-decoration:underline"><!--{$server.name}--></a></li>
		<li>服务器版本：<!--{$server.os}--></li>
		<li>服务器引擎：<!--{$server.software}--></li>
		<li>数据库版本：MYSQL <!--{$server.db_version}--></li>
		<li>PHP版本：<!--{$server.version}--></li>
		<li>网站根目录：<!--{$server.root}--></li>
		<li>最大上传值：<!--{$server.upload}--></li>
		<li>全局变量：<!--{$server.register_globals}--></li>
		<li>安全模式：<!--{$server.safe_mode}--></li>
		<li>会话超时：<!--{$server.timeout}--> 分</li>
		<li>占用内存：<!--{$server.memory_usage}--> </li>
		<!--{if $server.disable_functions}-->
		<li>禁用函数：<!--{$server.disable_functions}--> </li>
		<!--{/if}-->
		</ul>
	</div>
</div>
<div class="blank"></div>
<div class="layout-box">
	<div class='layout-title'>关于程序</div>
	<div class='layout-body'>
		<ul>
		<li>程序版本：<!--{$config.version}--></li>
		<li>程序作者：野草</li>
		<li>特别感谢：别俊、小林</li>
		<li>官方支持：<a href='http://yckit.com' target="_blank" style="color:#B5D53B;text-decoration:underline">官方网站</a>&nbsp;<a href='http://weibo.com/u/2656906044' target="_blank" style="color:#B5D53B;text-decoration:underline">新浪官方微博</a>&nbsp;<a href='http://yckit.taobao.com' target="_blank" style="color:#B5D53B;text-decoration:underline">淘宝专卖店</a></li>
		<!--<li>捐助开发：<a href="https://me.alipay.com/jinzhe" target="_blank" style="color:#B5D53B;text-decoration:underline">支付宝(Alipay)</a></li>-->
		</ul>
	</div>
</div>
<script type="text/javascript">
$(function(){
	$('.unsafetip').slideDown();
});
</script>