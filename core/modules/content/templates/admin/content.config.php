<?php exit?>
<!--{include file="header.php"}-->
<div class="layout-box">
	<div class='layout-title'>内容模块设置</div>
	<div class='layout-body'>
	<div class="blank"></div>
	<form action="?action=content&do=config_update" method="post" enctype="multipart/form-data">

	<fieldset>
	<legend>开关控制</legend>
	<table cellspacing="10">
	<tr>
	<td width="120">动态静态切换:</td>
	<td>
	<label><input type="radio"  onclick="$('#rewrite').hide()" name="content_mode" value="0" {if $config.content_mode==0}checked{/if}/> 动态模式</lable>
	<label><input type="radio"  onclick="$('#rewrite').hide()" name="content_mode" value="1" {if $config.content_mode==1}checked{/if}/> 静态模式</lable>
	<label><input type="radio" onclick="$('#rewrite').show()" name="content_mode" value="2" {if $config.content_mode==2}checked{/if}/> 伪静态模式</lable>

 
	</td>
	</tr>
	<tr id="rewrite" {if $config.content_mode!=2}style="display:none"{/if}>
	<td width="120"></td>
	<td>
 
		<fieldset>
	<legend>Apache的规则：保存为.htaccess后放到根目录</legend>	
		<pre>
RewriteEngine on
Options All -Indexes

RewriteRule ^content/([0-9]+)/$ /content.php?id=$1
RewriteRule ^content/([0-9]+)/page/([0-9]+)/$ /content.php?id=$1&page=$2
RewriteRule ^category/([0-9]+)/$ /content.php?cid=$1
RewriteRule ^category/([0-9]+)/page/([0-9]+)/$ /content.php?cid=$1&page=$2
		</pre>
		</fieldset>

		<fieldset>
	<legend>Nginx的规则：请插入到Nginx配置文件</legend>	
		<pre>
rewrite ^/content/([0-9]+)/$ /content.php?id=$1;
rewrite ^/content/([0-9]+)/page/([0-9]+)/$ /content.php?id=$1&page=$2;
rewrite ^/category/([0-9]+)/$ /content.php?cid=$1;
rewrite ^/category/([0-9]+)/page/([0-9]+)/$ /content.php?cid=$1&page=$2;
		</pre>
		</fieldset>

<fieldset>
	<legend>IISrewrite的规则：请保存为httpd.ini</legend>	
		<pre>
[ISAPI_Rewrite]
CacheClockRate 3600
RepeatLimit 32

RewriteRule ^/content/([0-9]+)/$ /content\.php\?id=$1 [L]
RewriteRule ^/content/([0-9]+)/page/([0-9]+)/$ /content\.php\?id=$1&page=$2 [L]
RewriteRule ^/category/([0-9]+)/$ /content\.php\?cid=$1
RewriteRule ^/category/([0-9]+)/page/([0-9]+)/$ /content\.php\?cid=$1&page=$2 [L]
		</pre>
		</fieldset>

	</td>
	</tr>

	<tr>
	<td width="120">评论审核状态:</td>
	<td>
	<label><input type="checkbox" name="content_comment_moderation" value="1" {if $config.content_comment_moderation==1}checked{/if}/> 启用</lable>
	</td>
	</tr>
	<tr>
	<td width="120">投稿发布状态:</td>
	<td>
	<label><input type="checkbox" name="content_draft_status" value="1" {if $config.content_draft_status==1}checked{/if}/> 启用</lable>
	</td>
	</tr>

	</table>
	</fieldset>

	<fieldset>
	<legend>前台设置</legend>
	<table cellspacing="10">
	<tr>
<td width="120">详细页评论显示:</td>
<td><input x-webkit-speech class="input" type="number" name="content_front_comment_list_size" size="15" value="{$config.content_front_comment_list_size}"/></td>
</tr>
<tr>
<td>搜索结果显示:</td>
<td><input x-webkit-speech class="input" type="number" name="content_front_search_size" size="15" value="{$config.content_front_search_size}"/></td>
</tr>
	</table>
	</fieldset>

	<fieldset>
	<legend>后台设置</legend>
	<table cellspacing="10">
	<tr>
	<td width="120">后台评论列表条数:</td>
	<td><input x-webkit-speech class="input" type="number" name="content_admin_comment_list_size" size="15" value="{$config.content_admin_comment_list_size}"/></td>
	</tr>
	<tr>
	<td width="120">后台文章列表显示:</td>
	<td><input x-webkit-speech class="input" type="number" name="content_admin_article_list_size" size="15" value="{$config.content_admin_article_list_size}"/></td>
	</tr>
	<tr>
	<td>后台投稿列表条数:</td>
	<td><input x-webkit-speech class="input" type="number" name="content_admin_draft_list_size" size="15" value="{$config.content_admin_draft_list_size|escape:html}"/></td>
	</tr>
	</table>
	</fieldset>
	<input type="submit" value=" 更新设置 " class="button" style="margin-left:170px;"/>
		</form>
	</div>
</div>