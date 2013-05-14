<?php exit?>
<!--{include file="header.php"}-->
<div class="layout-box">
	<div class='layout-title'><!--{if $mode=='insert'}-->添加菜单<!--{else}-->编辑菜单<!--{/if}--></div>
	<div class='layout-body'>
		<form action="?action=global&do=menu_<!--{$mode}-->" method="post" name="menu_info">
<table width="100%">
		<tr>
		<td align="right" width="80" height="40">上级菜单：</td>
		<td><select name="parent_id" class="select">
		<option value="0">选择父菜单...</option>
		<!--{foreach from=$parent_menu item=parent_menu}-->
		<option value="<!--{$parent_menu.id}-->" <!--{if $parent_menu.id==$menu.parent_id}-->selected<!--{/if}-->><!--{$parent_menu.name}--></option>
		<!--{/foreach}-->
		</select>
		</td>
		</tr>

		<tr>
		<td align="right" width="80" height="40">菜单名称：</td>
		<td><input x-webkit-speech type="text" name="menu_name" size="30" value="<!--{$menu.name|escape:html}-->" class="input"/></td>
		</tr>
		<tr>
		<td align="right"  height="40">菜单地址：</td>
		<td><input x-webkit-speech type="text" name="menu_link" style="width:400px;" value="<!--{$menu.link|escape:html}-->" class="input" autocomplete="off"/> (如果是站内链接请使用绝对路径以“/”开头)</td>
		</tr>
		<tr>
		<td align="right"  height="40">菜单描述：</td>
		<td><textarea name="menu_description" class="textarea" style="width:400px;height:60px"><!--{$menu.description|escape:html}--></textarea></td>
		</tr>

		<tr>
		<td align="right"  height="40">菜单排序：</td>
		<td><input x-webkit-speech type="number" name="menu_sort" style="width:60px;" value="<!--{$menu.sort|escape:html}-->" class="input" autocomplete="off"/></td>
		</tr>


		<tr>
		<td align="right" height="40">打开方式：</td>
		<td>
		<label><input type="radio" name="menu_target" value="0" {if $menu.target==0}checked{/if} /> 原页</label>
		<label><input type="radio" name="menu_target" value="1" {if $menu.target==1}checked{/if} /> 弹出</label>

		</td>
		</tr>
		<tr>
		<td align="right" height="40">菜单状态：</td>
		<td>
		<label><input type="radio" name="menu_status" value="1" {if $menu.status==1}checked{/if} /> 正常</label>
		<label><input type="radio" name="menu_status" value="0" {if $menu.status==0}checked{/if} /> 锁定</label>
		</td>
		</tr>

		<tr>
		<td align="right" height="40">&nbsp;</td>
		<td>
		<input type="submit" value=" 提 交 " class="button"/>
		</td>
		</tr>
		<input type="hidden" name="menu_id" value="<!--{$menu.id}-->"/>
	</form>
	</table>
	</div>
</div>
</body>
</html>