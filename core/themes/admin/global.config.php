<?php exit?>
<!--{include file="header.php"}-->
<div class="layout-box">
	<div class='layout-title'>系统设置</div>
	<div class='layout-body'>
	<div class="blank"></div>
		<form action="?action=global&do=config_update" method="post" enctype="multipart/form-data">
<div id="tab">
	<a href="#0" id="tab_0" class="hover" onclick="tab(0,4)">基本信息设置</a>
	<a href="#1" id="tab_1" onclick="tab(1,4)">主题控制设置</a>
	<a href="#2" id="tab_2" onclick="tab(2,4)">邮件服务器设置</a>
	<a href="#3" id="tab_3" onclick="tab(3,4)">水印设置</a>
</div>
		<div id="content_0">
		(站点标题)<div class="blank"></div>
		<input x-webkit-speech class="input" type="text" name="title" size="60" value="{$config.title|escape:html}"/><div class="blank"></div>
		(站点站长)<div class="blank"></div>
		<input x-webkit-speech class="input" type="text" name="author" size="60" value="{$config.author|escape:html}"/><div class="blank"></div>
		(站点关键字:适用于SEO)<div class="blank"></div>
		<input x-webkit-speech class="input" type="text" name="keywords" size="60" value="{$config.keywords|escape:html}"/><div class="blank"></div>
		(站点描述:适用于SEO)<div class="blank"></div>
		<textarea class="input" name="description" rows="4" cols="60"><!--{$config.description|escape:html}--></textarea><div class="blank"></div>
		(统计代码:在第三方申请的统计代码，在页面底部显示)<div class="blank"></div>
		<textarea class="input" name="statcode" rows="3" cols="120"><!--{$config.statcode|escape:html}--></textarea><div class="blank"></div>
		(站内公告:)<div class="blank"></div>
		<textarea class="input" name="notice" rows="4" cols="120"><!--{$config.notice|escape:html}--></textarea><div class="blank"></div>



		</div>
		<div id="content_1" style="display:none">
			<script type="text/javascript">
			$(function(){
				$.ajax({
					type:'get',
					url:'?action=global&do=theme_config',
					success:function(result){
						$('#content_1').html(result);
					}
				});
			});
			</script>
		</div>

		<div id="content_2" style="display:none">
		(邮件服务器主机:IP地址或者主机名称)<div class="blank"></div>
		<input x-webkit-speech class="input" type="text" name="smtp_server" size="40" value="{$config.smtp_server|escape:html}"/>
		端口：<input x-webkit-speech class="input" type="text" name="smtp_port" size="5" value="{$config.smtp_port|escape:html}"/><div class="blank"></div>
		(邮件服务器用户:SMTP帐号)<div class="blank"></div>
		<input x-webkit-speech class="input" type="text" name="smtp_user" size="40" value="{$config.smtp_user|escape:html}"/><div class="blank"></div>
		(邮件服务器密码:SMTP密码)<div class="blank"></div>
		<input x-webkit-speech class="input" type="password" name="smtp_password" size="40" value="{$config.smtp_password|escape:html}"/><div class="blank"></div>
		(邮件发送地址:请填写发送邮件地址)<div class="blank"></div>
		<input x-webkit-speech class="input" type="text" name="smtp_mail" size="40" value="{$config.smtp_mail|escape:html}"/><div class="blank"></div>
		</div>
		<div id="content_3" style="display:none">
	<table cellspacing="10">
<tr>
	<td>水印开关:</td>
	<td><input x-webkit-speech type="radio" name="watermark_status" size="15" value="1" {if $config.watermark_status==1}checked{/if}/> 启用
	<input x-webkit-speech type="radio" name="watermark_status" size="15" value="0" {if $config.watermark_status==0}checked{/if}/> 禁用</td>
	</tr>
	<tr>
	<td width="120">水印位置:</td>
	<td>
<select name="watermark_position" class="input">
<option value="0" {if $config.watermark_position==0}selected{/if}/>随机</option>
<option value="1" {if $config.watermark_position==1}selected{/if}/>顶端居左</option>
<option value="2" {if $config.watermark_position==2}selected{/if}/>顶端居中</option>
<option value="3" {if $config.watermark_position==3}selected{/if}/>顶端居右</option>
<option value="4" {if $config.watermark_position==4}selected{/if}/>中部居左</option>
<option value="5" {if $config.watermark_position==5}selected{/if}/>中部居中</option>
<option value="6" {if $config.watermark_position==6}selected{/if}/>中部居右</option>
<option value="7" {if $config.watermark_position==7}selected{/if}/>底端居左</option>
<option value="8" {if $config.watermark_position==8}selected{/if}/>底端居中</option>
<option value="9" {if $config.watermark_position==9}selected{/if}/>底端居右</option>
</select>
</td>
	</tr>
	<tr>
	<td width="120">水印图片:</td>
	<td><input x-webkit-speech class="input" type="file" name="watermark_image" size="15" /> 注意：请尽量使用png透明图片，但也可以支持jpg
	<!--{if $config.watermark_image}-->
	<br /><br />
	<a href="data/<!--{$config.watermark_image}-->" target="_blank"><img src="data/<!--{$config.watermark_image}-->" alt="" align="absmiddle" style="width:100px;height:100px;"/></a>
			<label><input type="checkbox" name="watermark_image_delete" value="<!--{$config.watermark_image}-->" /> 删除</label>
		<input type="hidden" name="watermark_image_old" value="<!--{$config.watermark_image}-->"/>
	<!--{/if}-->
	</td>
	</tr>

	</table>
	</div>
<!--{$plugin}-->
		<input type="submit" value=" 提 交 " class="button"/>
		<input type="button" id="cancel" class="button" value=" 取 消 "/>
		</form>
	</div>
</div>
<script type="text/javascript">
	var href=location.href.toString();
		href=href.split("#");
		if(href.length>1){
			tab(parseInt(href[1]),3);
		}
</script>