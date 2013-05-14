<?php exit?>
<!--{include file="header.php"}-->
<script type="text/javascript" src="core/scripts/jquery.crop.js"></script>
<script type="text/javascript" src="core/scripts/jquery.lightbox.js"></script>
<link rel="stylesheet" type="text/css" href="core/styles/jquery.crop.css" />
<link rel="stylesheet" type="text/css" href="core/styles/jquery.lightbox.css" />
<div class="layout-box">
	<div class='layout-title'><!--{if $mode=='insert'}-->添加内容<!--{else}-->编辑内容<!--{/if}--></div>
	<div class='layout-body'>
<div class='blank'></div>
<form action="?action=content&do=article_<!--{$mode}-->" method="post" enctype="multipart/form-data" id="article_info" name="article_info" onsubmit="return check()">
<input name="article_file" id="article_file" type="hidden" value="<!--{$article.file}-->"/>
<input name="draft_id" id="draft_id" type="hidden" value="<!--{$draft_id|default:0}-->"/>
<input type="hidden" name="article_id" value="<!--{$article.id}-->"/>
<input type="hidden" name="id" value="<!--{$article.id}-->"/>
<input type="hidden" name="article_html_old" value="<!--{$article.html}-->"/>
<div id="tab">
	<a href="#0" id="tab_0" class="hover" onmouseover="tab(0,3)">基本信息</a>
	<a href="#1" id="tab_1" onclick="tab(1,3)">可选信息</a>
	<a href="#2" id="tab_2" onclick="tab(2,3)">附件信息</a>
</div>
<div id="content_0">
		(栏目:指定所属栏目)
		<div class="blank"></div>
		<select name="category_id" id="category_id" class="select">
		<!--{$category_option}-->
		</select>
		<div class="blank"></div>
		(标题:最多100个字)
		<div class="blank"></div>
		<input tabindex="1" type="text" class="input" name="article_title" id="article_title" size="40" style="width:580px" value="<!--{$article.title}-->" x-webkit-speech/>
			<label><input type="checkbox" name="article_is_display" value="1" <!--{if $article.is_display==1}-->checked<!--{/if}-->/>&nbsp;发布</label>
			<label><input type="checkbox" name="article_is_comment" value="1" <!--{if $article.is_comment==1}-->checked<!--{/if}-->/>&nbsp;评论</label>
			<label><input type="checkbox" name="article_is_best" value="1" <!--{if $article.is_best==1}-->checked<!--{/if}-->/>&nbsp;推荐</label>
			<label title="选中后搜索引擎不会抓取该文章"><input type="checkbox" name="article_is_nofollow" value="1" <!--{if $article.is_nofollow==1}-->checked<!--{/if}-->/>&nbsp;禁止收录</label>
		<div class="blank"></div>
		<div id="fields"></div>
		<table width="800" cellspacing="0" cellpadding="0">
		<tr>
		<td>(内容:分页请用<a href="javascript:e.pasteHTML('#page#')">#page#</a>,简要请用<a href="javascript:e.pasteHTML('#more#')">#more#</a>)</td>
		<td width="300" align="right">
		<label><input type="checkbox" name="article_content_mode" id="article_content_mode" value="1" <!--{if $article.content_mode==1}-->checked<!--{/if}-->/>&nbsp;HTML模式</label>
		</td>
		</tr>
		</table>
		<div class="blank"></div>
		<textarea tabindex="2" class="textarea" name="article_content"  id="article_content" style="margin-right:5px;width:800px;height:400px"><!--{$article.content|escape:html}--></textarea>
		<div class="blank" id="attachment"></div>
		</td>
		</tr>
		</table>
		</div>
		<div id="content_1" style="display:none">
		(作者:不能超过20字)<div class="blank"></div>
		<input x-webkit-speech type="text" class="input" name="article_author" size="40" style="width:200px;" value="<!--{$article.author}-->"/><div class="blank"></div>

		<table width="600" cellspacing="0" cellpadding="0">
		<tr>
		<td>(图像:可输入http://开头的外部URL图片地址)</td>
		<td width="250" align="right">
 		<label><input type="checkbox" onclick="$('#article_image_upload').toggle()"/> 本地上传</label>
		<input type="button" value="裁剪图像" class="button" onclick="crop_image()" /></td>
		</tr>
		</table>
		<div class="blank"></div>
		<input type="text" class="input" name="article_image" id="article_image" style="width:600px;" value="<!--{$article.image}-->" />

		<!--{if $article.image}-->
		<a href="<!--{$article.image}-->" target="_blank"><img src="<!--{$article.image}-->" alt="" align="absmiddle" style="width:25px;height:25px;border:1px solid #666;padding:1px"/></a>
		<label><input type="checkbox" name="article_image_delete" value="<!--{$article.image}-->" /> 删除</label>
		<input type="hidden" name="article_image_old" value="<!--{$article.image}-->"/>
		<div class="blank"></div>
		<!--{/if}-->
		<div id="article_image_upload" style="display:none">
		<div class="blank"></div>
		<input type="file" class="input" name="article_image_upload"/>
		</div>
		<div class="blank" id="cropbox"></div>
		<div class="blank" id="file"></div>
		<table width="600" cellspacing="0" cellpadding="0">
		<tr>
		<td>(关键字:SEO优化)</td>
		<td width="100" align="right"><label><input type="checkbox" value="1" name="tag"/>&nbsp;加入内链库</label></td>
		</tr>
		</table>

		<div class="blank"></div>
		<input x-webkit-speech type="text" class="input" name="article_keywords" id="article_keywords" size="40" style="width:600px;" value="<!--{$article.keywords}-->"/><div class="blank"></div>
		<table width="600" cellspacing="0" cellpadding="0">
		<tr>
		<td>(描述:100字左右)</td>
		<td width="100" align="right"><input type="button" value="内容中提取描述" class="button" onclick="get_description()" /></td>
		</tr>
		</table>
		<textarea class="textarea" name="article_description" id="article_description" style="width:600px;height:60px"><!--{$article.description}--></textarea><div class="blank"></div>
		<div class="blank"></div>
		(生成文件:只允许英文字母、数字、_-等字符，不包括后缀名如.html)<div class="blank"></div>
		<input x-webkit-speech type="text" class="input" name="article_html" id="article_html" size="40" style="width:200px;" value="<!--{$article.html}-->" onblur="check_html(<!--{$article.id}-->)"/>
		<input type="button" value="关键字中提取拼音" class="button" onclick="get_html()" />
		<div class="blank"></div>



		</div>
<div id="content_2" style="display:none;min-height:500px"><div id="attachment_list"></div></div>
		<div class="blank">
			<input type="submit" class="button" value=" 提 交 "/>
		</div>
	</form>
</div>
<script type="text/javascript" src="core/scripts/jquery.editor.js"></script>
<script type="text/javascript">
var e;
$(function(){
	var href=location.href.toString();
		href=href.split("#");
		if(href.length>1){
			tab(parseInt(href[1]),3);
		}
	get_file();
	load_editor(<!--{if $article.content_mode==1}-->false<!--{/if}-->);
	$('#file a').lightBox();
	$('#attachment a').lightBox();
	$('#article_content_mode').click(function(){
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
	var category_id=$('#category_id').val();
	var article_id=<!--{if $mode=='insert'}-->0<!--{else}--><!--{$article.id}--><!--{/if}-->;
	if(parseInt(category_id)>0){
		load_fields(category_id,article_id);
	}
	$('#category_id').change(function(){
		load_fields($(this).val(),article_id);
	});
});
function load_fields(category_id,article_id){
	$.ajax({
		type:'GET',
		url:'?action=content&do=get_fields&category_id='+category_id+'&article_id='+article_id,
		success:function(result){
			$('#fields').html(result);
		}
	});
}
function load_editor(){
	if(arguments.length>0){
		$('#article_content').xheditor(false);
		return false;
	}
	e=$('#article_content').xheditor({
		remoteImgSaveUrl:'?do=remote_save_image&path=data/content/',
		upLinkUrl:"?do=upload&path=data/content/",
		upLinkExt:"<!--{$ext}-->",
		upImgUrl:"?do=upload&path=data/content/",
		upImgExt:"jpg,jpeg,gif,png,bmp",
		onUpload:function(msg){
			if(msg!=''||msg!='data/content/'){
				$("#article_file").val($("#article_file").val()+msg+",");
				get_file();get_attachment();
			}else{
				alert('上传无法完成，请核对您的文件格式');
			}
		},
		shortcuts:{
			'ctrl+enter':function(){
				$('#article_info').submit();
			}
		},
		plugins:{
			Code:{c:'btnCode',t:'插入代码',h:1,e:function(){
				var _this=this;
				var htmlCode='<div><select id="xheCodeType"><option value="html">HTML/XML</option><option value="js">Javascript</option><option value="css">CSS</option><option value="php">PHP</option><option value="java">Java</option><option value="py">Python</option><option value="pl">Perl</option><option value="rb">Ruby</option><option value="cs">C#</option><option value="c">C++/C</option><option value="vb">VB/ASP</option><option value="">其它</option></select></div><div><textarea id="xheCodeValue" wrap="soft" spellcheck="false" style="width:300px;height:100px;" /></div><div style="text-align:right;"><input type="button" id="xheSave" value="确定" /></div>';			var jCode=$(htmlCode),jType=$('#xheCodeType',jCode),jValue=$('#xheCodeValue',jCode),jSave=$('#xheSave',jCode);
				jSave.click(function(){
					_this.loadBookmark();
					_this.pasteHTML('<pre class="prettyprint lang-'+jType.val()+'">'+_this.domEncode(jValue.val().replace('&nbsp;','&amp;nbsp;'))+'</pre>');
					_this.hidePanel();
					return false;
				});
				_this.saveBookmark();
				_this.showDialog(jCode);
			}},
			map:{c:'btnMap',t:'插入Google地图',e:function(){
				var _this=this;
				_this.saveBookmark();
				_this.showIframeModal('Google 地图','core/images/editor/googlemap.html',function(v){
					_this.loadBookmark();
					_this.pasteHTML('<img src="'+v+'" />');
				},608,404);
			}}
		},
		loadCSS:'<style>pre{margin-left:2em;border-left:3px solid #CCC;padding:0 1em;}</style>'
	});
	get_attachment();
}
function editor_insert(html){
	e.pasteHTML(html);
}
function editor_insert_file(filename){
	var ext=filename.split(".")[1];
	if(ext=='gif'||ext=='jpg'||ext=='png'||ext=='bmp'){
		editor_insert('<img src="'+filename+'" alt=""/>');
	}else{
		editor_insert('<a href="'+filename+'">'+filename+'</a>');
	}
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
		url:'?action=content&do=delete_file&filename='+encodeURIComponent(filename),
		success:function(result){
			get_file();get_attachment();
		}
	});
}
function get_file(){
	var file=$('#article_file').val();
		file=file.split(",");
		file_count=file.length;
	var temp='',temp2='';
		for (var i=0;i<file_count;i++ ){
			var ext=file[i].split(".")[1];
			if(ext=='jpg'||ext=='gif'||ext=='png'){
				temp+="<div style='display:inline-block;+display:inline;+zoom:1;'><a target='_blank' href='"+file[i]+"'><img src='"+file[i]+"' style='width:90px;height:90px;border:1px solid #666;margin-right:2px;padding:1px;cursor:pointer'/></a><br /><input type='button' value='选择' class='button' onclick='set_image(\""+file[i]+"\")'/>&nbsp;<input type='button' value='删除' class='button' onclick='delete_file(\""+file[i]+"\")'/></div>";
				temp2+="<div style='display:inline-block;+display:inline;+zoom:1;'><a target='_blank' href='"+file[i]+"'><img src='"+file[i]+"' style='width:90px;height:90px;border:1px solid #666;margin-right:2px;padding:1px;cursor:pointer'/></a><br /><input type='button' value='插入' class='button' onclick='editor_insert_file(\""+file[i]+"\")'/>&nbsp;<input type='button' value='删除' class='button' onclick='delete_file(\""+file[i]+"\")'/></div>";
			}
		}
	$('#file').html(temp);
	$('#attachment').html(temp2);
}
function get_attachment(){
	var file=$('#article_file').val();
	if(file!=''){
			file=file.split(",");
			file_count=file.length;
		var temp='<table cellspacing="0" cellpadding="0" class="table" style="width:500px">';
			temp+='<thead>';
			temp+='<tr>';
			temp+='<th width="40">&nbsp;</td>';
			temp+='<th>文件名</td>';
			temp+='<th>操作</td>';
			temp+='</tr>';
			temp+='</thead>';
			temp+='<tbody>';
			for (var i=0;i<file_count;i++ ){
				if(file[i]!=''){
					var ext=file[i].split(".")[1];
					if(ext=='jpg'||ext=='gif'||ext=='png'){
						temp+='<tr>';
						temp+="<td align='center' style='height:40px'><a target='_blank' href='"+file[i]+"'><img src='"+file[i]+"' style='width:22px;height:22px;border:1px solid #666;padding:1px;'/></a></td>";
						temp+='<td>&nbsp;&nbsp;<a target="_blank" href='+file[i]+'>'+file[i]+'</a></td>';
						temp+='<td align="center">';
						temp+='<a href="javascript:delete_file(\''+file[i]+'\')" class="button">删除</a>';
						temp+='</td>';
						temp+='</tr>';
					}else{
						temp+='<tr>';
						temp+="<td>&nbsp;</td>";
						temp+='<td>&nbsp;&nbsp;<a target="_blank" href='+file[i]+'>'+file[i]+'</a></td>';
						temp+='<td align="center">';
						temp+='<a href="javascript:delete_file(\''+file[i]+'\')" class="button">删除</a>';
						temp+='</td>';
						temp+='</tr>';
					}
				}
			}
			temp+='</tbody>';
			temp+='</table>'
		$('#attachment_list').html(temp);
	}else{
		$('#attachment_list').empty();
	}
}
function crop_image(){
	var image=$("#article_image").val();
	if($.trim(image)==''){
		alert('没有裁剪对象');
		$("#article_image").focus();
		return false;
	}
	$('#cropbox').html('<table cellspacing="0" cellpadding="0"><tr><td valign="top"><div  style="border:5px solid #fff;"><img  src="'+image+'" id="croper" /></div></td><td valign="top">&nbsp;</td><td valign="top"><table cellspacing="5"><tr><td>X坐标:</td><td><input type="text" id="x"  class="input" style="width:50px" readonly/></td></tr><tr><td>Y坐标:</td><td><input type="text" id="y"  class="input"style="width:50px"  readonly/></td></tr><tr><td>宽度:</td><td><input type="text" id="w"  class="input" style="width:50px"  readonly/></td></tr><tr><td>高度:</td><td><input type="text" id="h" class="input" style="width:50px" readonly /></td></tr></table><br /><br /><div style="text-align:center"><input class="button" type="button" value="裁剪" id="crop_submit"/>&nbsp;<input class="button" type="button" value="取消" id="crop_cancel"/></div>');
	$('#croper').crop({
		//aspectRatio: 1,
		allowResize:true,
		setSelect:[0,0,<!--{$category.image_width|default:250}-->,<!--{$category.image_height|default:250}-->],
		onSelect:function(coords){
			$('#x').val(coords.x);
			$('#y').val(coords.y);
			$('#w').val(coords.w);
			$('#h').val(coords.h);
		},
		onChange:function(coords){
			$('#x').val(coords.x);
			$('#y').val(coords.y);
			$('#w').val(coords.w);
			$('#h').val(coords.h);
		}
	});
	$('#crop_submit').click(function(){
		var x=$('#x').val();
		var y=$('#y').val();
		var w=$('#w').val();
		var h=$('#h').val();
		$.ajax({
			type:'GET',
			url:'?action=content&do=crop_image&image='+image+'&x='+x+'&y='+y+'&w='+w+'&h='+h,
			success:function(result){
				$("#article_image").val(result);
				$('#cropbox').empty();
			}
		})
	});
	$('#crop_cancel').click(function(){
		$('#cropbox').empty();
	});
}
function set_image(image){
	var category_id=$('#category_id').val();
	if(parseInt(category_id)==0){
		alert('请选择栏目');
	}

	$.ajax({
		type:'GET',
		url:'?action=content&do=make_thumb&image='+image+'&category_id='+category_id,
		success:function(result){
			if(result=='ERROR'){
				alert('请检查栏目的缩图尺寸是否大于0或检查上传目录是否可写。');
				return;
			}
			$("#article_image").val(result);
			if($('#cropbox').html()!=''){
				crop_image();
			}
		}
	});

}
function add_value(){
	var html='<div style="padding:4px 0">';
		html+='字段：<input type="text" class="input" name="diy_name[]" size="15"/>&nbsp;';
		html+='值：<input type="text" class="input" name="diy_value[]" size="60"/>&nbsp;<a href="javascript:;" onclick="remove_value(this)">[-]</a>';
		html+='</div>';
	document.getElementById('article_value').innerHTML+=html;
}
function remove_value(obj){
	var parent=obj.parentNode;
		parent.innerHTML='';
		parent.parentNode.removeChild(parent);
	value_count--;
}
function check(){
	var title=$('#article_title').val();
	var content=$('#article_content').val();
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
function get_keywords(){
	var title=$('#article_title').val();
	if($.trim(title)==''){
		alert('标题不能为空');
		return false;
	}
	$.ajax({
		type:'GET',
		url:'?action=content&do=get_keywords&title='+encodeURIComponent(title),
		success:function(result){
			if($.trim(result)==''){
				alert('系统匹配不到关键字');
			}else{
				$('#article_keywords').val(result);
			}
		}
	});
}
function get_description(){
	var content=$('#article_content').val();
	if($.trim(content)==''){
		alert('内容不能为空');
		return false;
	}
	$.ajax({
		type:'POST',
		url:'?action=content&do=get_description',
		data:'content='+encodeURIComponent(content),
		success:function(result){
			$('#article_description').val(result);
		}
	});
}
function get_html(){
	var content=$('#article_keywords').val();
	if($.trim(content)==''){
		alert('关键字不能为空');
		return false;
	}
	$.ajax({
		type:'POST',
		url:'?action=content&do=get_html',
		data:'content='+encodeURIComponent(content),
		success:function(result){
			$('#article_html').val(result);
		}
	});
}
function check_html(id){
	var content=$('#article_html').val();
	if($.trim(content)!=''){
		$.ajax({
			type:'GET',
			url:'?action=content&do=check_html&id='+id+'&content='+encodeURIComponent(content),
			success:function(result){
				if(result=='YES'){
					alert('该文件名已存在');
					$('#article_html').val('');
				}
			}
		});
	}
}
</script>