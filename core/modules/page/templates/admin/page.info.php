<?php exit?>
<!--{include file="header.php"}-->
<div class="layout-box">
	<div class='layout-title'><!--{if $mode=='insert'}-->添加页面<!--{else}-->编辑页面<!--{/if}--></div>
	<div class='layout-body'>
<div class='blank'></div>
<form action="?action=page&do=page_<!--{$mode}-->" method="post" enctype="multipart/form-data" name="page_info" onsubmit="return check()">
<input type="hidden" name="page_id" value="<!--{$page.id}-->"/>
<input type="hidden" name="page_file" id="page_file"  value="<!--{$page.file}-->"/>
<input type="hidden" name="page_html_old" value="<!--{$page.html}-->"/>
	
		(标题:最多100个字)
		<div class="blank"></div>
		<input x-webkit-speech type="text" class="input" name="page_title" id="page_title" size="40" style="width:400px" value="<!--{$page.title|escape:html}-->"/>

		<label><input type="checkbox" name="page_is_display" value="1" <!--{if $page.is_display==1}-->checked<!--{/if}-->/>&nbsp;发布</label>
		<label><input type="checkbox" name="page_is_comment" value="1" <!--{if $page.is_comment==1}-->checked<!--{/if}-->/>&nbsp;评论</label>
		<!--{if $mode=='insert'}-->
		<label><input type="checkbox" name="into_menu" value="1" checked /> 加入菜单</label>
		<!--{/if}-->
		<div class="blank"></div>

		(关键字:用于SEO优化)
		<div class="blank"></div>
		<input x-webkit-speech type="text" class="input" name="page_keywords" id="page_keywords" size="40" style="width:400px" value="<!--{$page.keywords|escape:html}-->"/>
		<div class="blank"></div>
		(描述:用于SEO优化和简介)
		<div class="blank"></div>
		<textarea class="textarea" name="page_description" style="width:600px;height:60px"><!--{$page.description|escape:html}--></textarea>
		<div class="blank"></div>
		<table width="800" cellspacing="0" cellpadding="0">
		<tr>
		<td>(内容:)</td>
		<td width="300" align="right">
		<label><input type="checkbox" name="page_content_mode" id="page_content_mode" value="1" <!--{if $page.content_mode==1}-->checked<!--{/if}-->/>&nbsp;HTML模式</label>
		</td>
		</tr>
		</table>
		<div class="blank"></div>
		<textarea class="textarea" name="page_content"  id="page_content" style="width:800px;height:400px"><!--{$page.content|escape:html}--></textarea>
		<div class="blank" id="attachment"></div>
		<div class="blank"></div>
		(排序:只允许数字)<div class="blank"></div>
		<input x-webkit-speech type="text" class="input" name="page_sort" size="40" style="width:100px;" value="<!--{$page.sort}-->"/>
		<div class="blank"></div>
		(生成文件:只允许英文字母、数字、_-等字符，无需填写.html)<div class="blank"></div>
		<input x-webkit-speech type="text" class="input" name="page_html" size="40" style="width:200px;" value="<!--{$page.html}-->"/>
		<div class="blank"></div>
 
 
			<input type="submit" class="button" value=" 提 交 "/>
 
 
	</form>
</div>
<script type="text/javascript" src="core/scripts/jquery.editor.js"></script>
<script type="text/javascript">
var e;
$(function(){
	var href=location.href.toString();
		href=href.split("#");
		if(href.length>1){
			tab(parseInt(href[1]),2);
		}
	load_editor(<!--{if $page.content_mode==1}-->false<!--{/if}-->);
	$('#page_content_mode').click(function(){
		if($(this).attr('checked')==true){
			load_editor(false);
		}else{
			if(confirm('确定要操作吗？\n操作后将会失去HTML模式中的部分内容！')){
				load_editor();
			}else{
				$(this).attr('checked',true);
			}
		}
	});

});
function load_editor(){
	if(arguments.length>0){
		$('#page_content').xheditor(false);
		return false;
	}
	e=$('#page_content').xheditor({
		remoteImgSaveUrl:'?do=remote_save_image',
		upLinkUrl:"?do=upload",
		upLinkExt:"<!--{$ext}-->",
		upImgUrl:"?do=upload",
		upImgExt:"jpg,jpeg,gif,png,bmp",
		onUpload:function(msg){
			$("#page_file").val($("#page_file").val()+msg+",");
			get_file();
		},
		shortcuts:{
			'ctrl+enter':function(){
				$('#page_info').submit();
			}
		}
	});
}
function delete_file(filename){
	var file=$('#article_file').val();
		file=file.split(",");
	var file_count=file.length;
	var temp=[];
		for (var i=0;i<file_count;i++ ){
			if(filename!=file[i]){
				temp.push(file[i]);
			}
		}
		$('#article_file').val(temp.join(','));
	$.ajax({
		type:'GET',
		url:'?action=page&do=delete_file&filename='+encodeURIComponent(filename),
		success:function(result){
			get_file();
		}
	});
}
function get_file(){
	var file=$('#page_file').val();
		file=file.split(",");
		file_count=file.length;
	var temp='',temp2='';
		for (var i=0;i<file_count;i++ ){
			var ext=file[i].split(".")[1];
			if(ext=='jpg'||ext=='gif'||ext=='png'){
				temp+="<div style='display:inline-block;+display:inline;+zoom:1;'><a target='_blank' href='"+file[i]+"'><img src='"+file[i]+"' style='width:90px;height:90px;border:1px solid #666;margin-right:2px;padding:1px;cursor:pointer'/></a><br /><input type='button' value='选择' class='button' onclick='set_image(\""+file[i]+"\")'/><input type='button' value='删除' class='button' onclick='delete_file(\""+file[i]+"\")'/></div>";
				temp2+="<div style='display:inline-block;+display:inline;+zoom:1;'><a target='_blank' href='"+file[i]+"'><img src='"+file[i]+"' style='width:90px;height:90px;border:1px solid #666;margin-right:2px;padding:1px;cursor:pointer'/></a><br /><input type='button' value='插入' class='button' onclick='editor_insert_file(\""+file[i]+"\")'/><input type='button' value='删除' class='button' onclick='delete_file(\""+file[i]+"\")'/></div>";
			}
		}
	$('#attachment').html(temp2);
}
function check(){
	var title=$('#page_title').val();
	var content=$('#page_content').val();
	if($.trim(title)==''){
		alert('标题不能为空');
		return false;
	}
	if($.trim(content)==''){
		alert('内容不能为空');
		return false;
	}
	return true;
}
</script>