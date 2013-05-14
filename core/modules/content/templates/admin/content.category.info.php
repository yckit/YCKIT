<?php exit?>
<!--{include file="header.php"}-->
<div class="layout-box">
	<div class='layout-title'><!--{if $mode=='insert'}-->添加栏目<!--{else}-->编辑栏目<!--{/if}--></div>
	<div class='layout-body'>

		<form action="?action=content&do=category_<!--{$mode}-->" method="post" enctype="multipart/form-data" onsubmit="return check()">
		<input type="hidden" name="category_uri_old" value="<!--{$category.uri|escape:html}-->" />
		<input type="hidden" name="category_id" value="<!--{$category.id}-->"/>
		<fieldset>
		<legend><b style="color:#B5D53B">栏目信息</b></legend>
		<!--{if $category_option}-->
		上级栏目：<select name="parent_id" class="input"><option value="0">设为根栏目</option><!--{$category_option}--></select>
		<!--{/if}-->
		<p class="blank"></p>
		栏目名称：<input tabindex="1" type="text" name="category_name" id="category_name" style="width:200px" value="<!--{$category.name|escape:html}-->" class="input" x-webkit-speech/>
			<label><input type="checkbox" name="category_is_display" value="1" <!--{if $category.is_display==1}-->checked<!--{/if}--> /> 发布</label>
		<!--{if $mode=='insert'}-->
		<label><input type="checkbox" name="into_menu" value="1" checked /> 加入菜单</label>
		<!--{/if}-->
			<p class="blank"></p>
		文&nbsp;件&nbsp;夹&nbsp;：<input tabindex="2" type="text" name="category_dir" style="width:200px"  value="<!--{$category.dir|escape:html}-->" class="input" x-webkit-speech/>
 
		<p class="blank"></p>

		关&nbsp;键&nbsp;字&nbsp;：</td><td><input tabindex="2" type="text" name="category_keywords"  style="width:400px;" value="<!--{$category.keywords|escape:html}-->" class="input" x-webkit-speech/>

		<p class="blank"></p>
		<table cellspacing="0" cellpadding="0">
		<tr>
		<td>
		描述介绍：</td><td><textarea tabindex="4" name="category_description" style="width:400px;height:60px" class="textarea"><!--{$category.description|escape:html}--></textarea>		</tr>
		<tr></table>

<p class="blank"></p>
		列表显示：<input tabindex="5" type="number" name="category_list_limit" maxlength="3" style="width:60px" value="<!--{$category.list_limit}-->" class="input"/><p class="blank"></p>
缩图宽度：<input tabindex="5" type="number" name="category_image_width" maxlength="3" style="width:60px" value="<!--{$category.image_width}-->" class="input"/>&nbsp;PX<p class="blank"></p>
		缩图高度：<input tabindex="5" type="number" name="category_image_height" maxlength="3"  style="width:60px" value="<!--{$category.image_height}-->" class="input"/>&nbsp;PX
		</fieldset>


		<fieldset><legend title="提示：字段名称一般为英文，如：“price”,显示文本指的是表单前缀，如“价格”,前台调用的时候使用&lt;!--&#123;$article.price.value&#125;--&gt;"><b style="color:#B5D53B">自定义字段</b></legend>

		<p id="diy-fields">
		<!--{if $field}-->
			<!--{foreach from=$field item=field}-->
			<div style="padding:4px 0">
			<input type="hidden"name="field_id[{$field.id}]" size="30" value="{$field.id}"/>
			字段名称：<input type="text"  class="field-name" class="input" name="field_name[{$field.id}]" size="30" value="{$field.name}"/>&nbsp;显示文本：<input type="text" class="input" name="field_text[{$field.id}]" size="30" value="{$field.text}"/>&nbsp;<input type="checkbox" name="field_status[{$field.id}]" value="1" {if $field.status==1}checked{/if}/>&nbsp;启用&nbsp;<input type="checkbox" name="field_delete[]" value="{$field.id}"/>&nbsp;删除
			&nbsp;<a href="javascript:;" onclick="add_value()"title="新增">[+]</a>
			</div>
			<!--{/foreach}-->
		<!--{/if}-->
		</p>
		</fieldset>

		<div style="padding:0 80px">
			<input type="submit" value="提交" class="button" />
		</div>

		</form>
	</div>
</div>

<script type="text/javascript">
$(function(){
	var href=location.href.toString();
		href=href.split("#");
		if(href.length>1){
			tab(parseInt(href[1]),5);
		}
		<!--{if $mode=='insert'}-->
		add_value();add_value();
		<!--{else}-->
		add_value();add_value();
		<!--{/if}-->


});
function check(){
	var name=$('#category_name').val();
	if($.trim(name)==''){
		alert('栏目名称不能为空');
		return false;
	}
}
function add_value(){
	var html='<div style="padding:4px 0">';
		html+='字段名称：<input type="text" class="input" name="field_name_[]" size="30"/>&nbsp;&nbsp;';
		html+='显示文本：<input type="text" class="input" name="field_text_[]" size="30"/>&nbsp;';
		html+='<input type="checkbox" name="field_status_[]" value="1" checked/>&nbsp;启用';
		html+='&nbsp;<a href="javascript:;" onclick="remove_value(this)" title="移除">[-]</a>';
		html+='&nbsp;<a href="javascript:;" onclick="add_value()"title="新增">[+]</a>';
		html+='</div>';
	$('#diy-fields').append(html);
}
function remove_value(obj){
	var parent=obj.parentNode;
		parent.innerHTML='';
		parent.parentNode.removeChild(parent);
	value_count--;
}
</script>