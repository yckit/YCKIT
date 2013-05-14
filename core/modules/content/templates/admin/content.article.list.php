<?php exit?>
<!--{include file="header.php"}-->
<script type="text/javascript" src="core/scripts/jquery.lightbox.js"></script>
<link rel="stylesheet" type="text/css" href="core/styles/jquery.lightbox.css" />
<div class="layout">
	<div class='layout-title'>文章管理</div>

	<!--{if $article}-->
	<form action="?action=content&do=article_delete" method="post" id="article_form">
	<table cellspacing="0" cellpadding="0" width="100%" class="table">
 
		<tbody>
		<!--{foreach from=$article item=article}-->
		<tr>
		<td style="height:30px;" align="center" ><input type="checkbox" class="checkbox" name="article_id[]" value="<!--{$article.id}-->" /></td>
		<td>
		<!--{if $article.category_name&&$article.category_id!=$template.get.category_id}-->
		<a href="?action=content&do=article&category_id=<!--{$article.category_id}-->" style="font-weight:bold;color:#F5D58D">[<!--{$article.category_name|escape:html}-->]</a>&nbsp;
		<!--{/if}-->
		<a href="?action=content&do=article_edit&article_id=<!--{$article.id}-->"><!--{$article.title|escape:html}--></a>

		<!--{if $article.comment_count}-->
		(<a href="?action=content&do=comment&article_id=<!--{$article.id}-->" style="color:#B5D53B">评论<!--{$article.comment_count}-->条</a>)
		<!--{/if}-->

		<!--{if $article.is_best==1}-->
			<b style="color:#B5D53B">[推荐]</b>
		<!--{/if}-->
		<!--{if $article.is_comment==0}-->
			<b style="color:#FE8217">[评论已关闭]</b>
		<!--{/if}-->

		<!--{if $article.image}-->
			<a href="<!--{$article.image}-->" target="_blank" class="lightbox"><img src="<!--{$article.image}-->" alt="" align="absmiddle" style="width:22px;height:22px;border:1px solid #333;padding:1px"/></a>
		<!--{/if}-->
		</td>



		<td align="center" style="color:#666;width:90px;">
		<a href="<!--{$article.url}-->" target="_blank" class="button">预览</a>
		</td>
		</tr>
		<!--{/foreach}-->
		</tbody>
		<tfoot>
		<tr>
		<td height="30" align="center"><input type="checkbox" onclick="check_all(this)" /></td>
		<td colspan="3">
		<div style="float:right;margin:8px;"><!--{include file="pager.php"}--></div>
		<!--{if $config.content_mode==1}-->
		<a href="javascript:;" onclick="html_article()" class="button">批量生成</a> 
		<!--{/if}-->
		<a href="javascript:;" onclick="del()" class="button">批量删除</a>
			<input type="text" class="input" id="search_keyword" />
	<a href="javascript:Search()"  class="button">搜索</a>
		</td>
		</tr>
		</tfoot>
	</table>
	</form>
	<!--{else}-->
	<div style="line-height:50px;text-align:center;">暂无数据</div>
	<!--{/if}-->

</div>
<div style="text-align:right" >
<!--{if $config.content_mode==1}-->
<a class="button" href="javascript:html_all_article();">重新生成</a>
<!--{/if}-->
<a class="button" href="javascript:call_article();">自定义调用</a>
<a class="button" href="?action=content&do=article_add<!--{if $template.get.category_id}-->&category_id=<!--{$template.get.category_id}--><!--{/if}-->">发布</a>
&nbsp;&nbsp;
</div>

<script type="text/javascript">
$(function(){
	$('.lightbox').lightBox();
});
function Search(){
var keyword=$("#search_keyword").val();
	if($.trim(keyword)==''){
		alert('关键字不能为空');
		$("#search_keyword").focus();
		return;
	}
	keyword=keyword.replace(/\'/gi,"");
	keyword=keyword.replace(/\"/gi,"");
	keyword=keyword.replace(/\?/gi,"");
	keyword=keyword.replace(/\%/gi,"");
	keyword=keyword.replace(/\./gi,"");
	keyword=keyword.replace(/\*/gi,"");
	window.location.href="?action=content&do=article<!--{if $template.get.category_id}-->&category_id=<!--{$template.get.category_id}--><!--{/if}-->&keyword="+encodeURI(keyword);
}
function del(){
	var status=false;
	$('.checkbox').each(function(){
		if($(this).attr('checked')){
			status=true;
		}
	});
	if(!status){
		alert('至少选择一项');
		return false;
	}
	$('#article_form').attr('action','?action=content&do=article_delete');
	$('#article_form').submit();
}
function html_article(){
	var status=false;
	$('.checkbox').each(function(){
		if($(this).attr('checked')){
			status=true;
		}
	});
	if(!status){
		alert('至少选择一项');
		return false;
	}
	if(confirm('确定要操作吗？')){
		$('#article_form').attr('action','?action=content&do=html');
		$('#article_form').submit();
	}
}
function html_all_article(){
	$.box({
			title:'生成文章',
			width:720,
			height:470,
			close_button:true,
			html:'<iframe allowTransparency="true" id="createframe" src="about:blank" style="width:100%;height:400px;" frameborder="0"></iframe><div style="text-align:center"></div>',
			callback:function(){
				$('#createframe').attr('src','?action=content&do=create_go');
			 
			}
		});
}
function call_article(){
	$.box({
			title:'自定义调用',
			width:720,
			height:510,
			close_button:true,
			html:'<fieldset style="border:1px solid #ccc"><legend>参数设置</legend><table><tr><td width="250">选择栏目：<select id="category" class="select" style="width:150px;"><option value="">不选择栏目</option><!--{$category_option}--></select><p class="blank"></p>标题长度：<input type="text" id="length"  style="width:137px;" value="20" class="select" title="一个汉字2位，英文一位"/><p class="blank"></p>显示条数：<input type="text" id="limit"  style="width:137px;" value="10" class="select"/><p class="blank"></p>排序字段：<select id="orderby" class="select" style="width:150px;"><option value="id">编号</option><option value="time">时间</option><option value="click_count">人气</option><option value="comment_count">评论</option></select></td><td width="250"><p class="blank"></p>排序方式：<select id="sort" class="select" style="width:150px;"><option value="asc">正序</option><option value="desc">倒序</option></select><p class="blank"></p>日期格式：<select id="format" class="select" style="width:150px;"><option value="Y-m-d">年-月-日</option><option value="Y/m/d">年/月/日</option><option value="Y.m.d">年.月.日</option><option value="m-d">月-日</option><option value="m/d">月/日</option><option value="m.d">月.日</option></select><p class="blank"></p>发布时间：<select id="time" class="select" style="width:150px;"><option value="">不限制</option><option value="1">1天内</option><option value="3">3天内</option><option value="7">1周内</option><option value="30">1月内</option><option value="365">1年内</option></select><p class="blank"></p>缩图选项：<select id="image" class="select" style="width:150px;"><option value="">不限制</option><option value="1">只显示含有图像</option><option value="0">只显示不含有图像</option></select></td><td width="250"><p class="blank"></p>作者选项：<select id="author" class="select" style="width:150px;"><option value="">不限制</option><option value="1">只显示含有作者</option></select><p class="blank"></p>内容选项：<select id="content" class="select" style="width:150px;"><option value="">不限制</option><option value="1">只显示HTML模式</option></select><p class="blank"></p>评论选项：<select id="comment" class="select" style="width:150px;"><option value="">不限制</option><option value="1">只显示评论有1次以上</option></select><p class="blank"></p>是否推荐：<select id="best" class="select" style="width:150px;"><option value="">不限制</option><option value="1">是</option></select></td></tr></table></fieldset><fieldset style="border:1px solid #ccc"><legend>调用代码</legend><textarea class="textarea" id="call_article_text" style="width:650px;height:200px;"></textarea></fieldset><div style="text-align:center"><input type="button" value="生成代码" class="button"  id="call_article_result"/></div>',
			callback:function(){
				$('#call_article_result').click(function(){
					var category=$('#category').val();
					var limit=$('#limit').val();
					var length=$('#length').val();
					var orderby=$('#orderby').val();
					var sort=$('#sort').val();
					var format=$('#format').val();
					var image=$('#image').val();
					var time=$('#time').val();
					var author=$('#author').val();
					var content=$('#content').val();
					var comment=$('#comment').val();
					var best=$('#best').val();
					var php="&lt;?php require_once ROOT.'/core/modules/content/class.content.php';$content=new content_class();?&gt;\n";
						if($.trim(time)!=""){
							if(time=='1'){
								php+="&lt;?php $time=time()-86400;?&gt;\n";
							}
							if(time=='3'){
								php+="&lt;?php $time=time()-86400*3;?&gt;\n";
							}
							if(time=='7'){
								php+="&lt;?php $time=time()-86400*7;?&gt;\n";
							}
							if(time=='30'){
								php+="&lt;?php $time=time()-86400*30;?&gt;\n";
							}
							if(time=='365'){
								php+="&lt;?php $time=time()-86400*365;?&gt;\n";
							}
						}
						php+="&lt;?php $sql=\"SELECT * FROM \".DB_PREFIX.\"content_article WHERE article_is_display=1";
						if($.trim(category)!=""){
							php+=" AND category_id IN("+category+")";
						}
						if($.trim(time)!=""){
							php+=" AND article_time>=$time";
						}
						if($.trim(image)!=""){
							php+=" AND article_image!=''";
						}
						if($.trim(author)!=""){
							php+=" AND article_author!=''";
						}
						if($.trim(content)!=""){
							php+=" AND article_content_mode=1";
						}
						if($.trim(comment)!=""){
							php+=" AND article_comment_count>0";
						}
						if($.trim(best)!=""){
							php+=" AND article_is_best=1";
						}
						php+=" ORDER BY article_"+orderby+" "+sort+" LIMIT "+limit+"\";?&gt;\n";

						php+="&lt;?php $result=$this-&gt;db-&gt;result($sql);if($result):?&gt;\n";
						php+="&lt;ul&gt;\n";
						php+="&lt;?php foreach($result as $row):?&gt;\n";
						php+="&lt;!--开始循环--&gt;\n";
						php+="&lt;li&gt;&lt;a href=\"&lt;?php echo($content-&gt;uri($row['category_id'],$row['article_html']));?&gt;\"&gt;&lt;?php echo(truncate($row['article_title'],"+length+"));?&gt;&lt;/a&gt;\n";
						php+="&lt;br /&gt;&lt;?php echo(date('"+format+"',$row['article_time']));?&gt;&lt;/li&gt;\n";
						php+="&lt;!--结束循环--&gt;\n";
						php+="&lt;?php endforeach;?&gt;\n";
						php+="&lt;/ul&gt;\n";
						php+="&lt;?php endif;unset($result)?&gt;";
						php=php.replace(/&lt;/g,"<").replace(/&gt;/g,">");

						$('#call_article_text').val(php);

				});
			}
		});
}
</script>