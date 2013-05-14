<?php exit?>
<!--{include file="header.php"}-->
<div class="layout-box">
	<div class='layout-title'>单页模块设置</div>
	<div class='layout-body'>
	<div class="blank"></div>
	<form action="?action=page&do=config_update" method="post" enctype="multipart/form-data">

	<table cellspacing="10">

	<tr>
	<td>评论审核状态:</td>
	<td>	<label><input type="checkbox" name="page_comment_moderation" size="15" value="1" {if $config.page_comment_moderation==1}checked{/if}/> 启用
</lable></td>
	</tr>

	<tr>
	<td>前台评论列表条数:</td>
	<td><input x-webkit-speech class="input" type="text" name="page_front_comment_list_size" size="15" value="{$config.page_front_comment_list_size}"/></td>
	</tr>


	<tr>
	<td>后台评论列表条数:</td>
	<td><input x-webkit-speech class="input" type="text" name="page_admin_comment_list_size" size="15" value="{$config.page_admin_comment_list_size}"/></td>
	</tr>





	<tr>
	<td>&nbsp;</td>
	<td><input type="submit" value=" 更新设置 " class="button"/></td>
	</tr>
	</table>



		</form>
	</div>
</div>