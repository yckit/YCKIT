
<table cellspacing="10">
 
<tr>
<td width="120">首页文章显示:</td>
<td><input x-webkit-speech class="input" type="number" name="index_size" size="15" value="{$config.index_size|default:10}"/></td>
</tr>
<tr>
<td width="120">边栏文章评论:</td>
<td><input x-webkit-speech class="input" type="number" name="comment_size" size="15" value="{$config.comment_size|default:10}"/></td>
</tr>
 <tr>
<td width="120">边栏推荐文章:</td>
<td><input x-webkit-speech class="input" type="number" name="best_size" size="15" value="{$config.best_size|default:10}"/></td>
</tr>
<tr>
<td width="120">边栏热门文章:</td>
<td><input x-webkit-speech class="input" type="number" name="click_size" size="15" value="{$config.click_size|default:10}"/></td>
</tr>
<tr>
<td width="120">边栏固定浮动:</td>
<td><input x-webkit-speech class="input" type="checkbox" name="side" value="1" {if $config.side==1}checked{/if}/> 启用</td>
</tr>
  

<tr>
<td width="120">文章内页<br/>广告代码:</td>
<td><textarea class="input" style="width:500px;height:100px;" name="ad">{$config.ad}</textarea></td>
</tr>


<tr>
<td width="120">首页调用栏目:</td>
<td>
<?php require_once ROOT.'/core/modules/content/class.content.php';$content=new content_class();?>
<table><tr><td>
<select name="index_category_old" id="index-category-old" class="select" style="height:200px;width:220px" title="双击右移" multiple>
<?php
$category=$content->get_category();
foreach ($category as $key => $value) {
	echo"<option value='".$value["id"]."'>".$value["name"]."</option>";
	$category2=$content->get_category($value["id"]);
	foreach ($category2 as $key => $value2) {
		echo"<option value='".$value2["id"]."'>".$value2["name"]."</option>";
	}
}
?>
</select></td><td style="padding:10px">
<input type="button" value="添加" class="button" id="index-category-add"/><br />
<input type="button" value="删除" class="button" id="index-category-remove"/><br />
<input type="button" value="上移" class="button" id="index-category-up"/><br />
<input type="button" value="下移" class="button" id="index-category-down"/></td><td>
<select name="index_category[]" id="index-category" class="select" style="height:200px;width:220px" multiple></select>
</td></tr></table>

</td>
</tr>
<tr>
<td width="120">首页调用文章:</td>
<td><input x-webkit-speech class="input" type="number" name="index_article_size" size="15" value="{$config.index_article_size|default:10}"/> 条</td>
</tr>
<tr>
<td width="120">首页调用广告:<br />广告栏会根据内容栏目的数目显示，比如只有2个栏目就只会显示一个广告栏</td>
<td>
	<style>
	.block{margin:15px 15px 15px 0;width:250px;height:100px;float:left;background:#666}
	</style>
	<div><div class="block"></div><div class="block"></div><div style=""></div></div>
	<textarea class="textarea" name="index_ad[]" style="width:500px;height:60px" placeholder="第一栏">{$config.index_ad.0|escape:html}</textarea>
	<div><div class="block"></div><div class="block"></div><div style="clear:left;"></div></div>
	<textarea class="textarea" name="index_ad[]" style="width:500px;height:60px" placeholder="第二栏">{$config.index_ad.1|escape:html}</textarea>
	<div><div class="block"></div><div class="block"></div><div style="clear:left;"></div></div>
	<textarea class="textarea" name="index_ad[]" style="width:500px;height:60px" placeholder="第三栏">{$config.index_ad.2|escape:html}</textarea>
	<div><div class="block"></div><div class="block"></div><div style="clear:left;"></div></div>
	<textarea class="textarea" name="index_ad[]" style="width:500px;height:60px" placeholder="第四栏">{$config.index_ad.3|escape:html}</textarea>
	<div><div class="block"></div><div class="block"></div><div style="clear:left;"></div></div>
</td>
</tr>
</table>
<script>
$(function() {
	setInterval(function(){
		$("#index-category").find("option").attr("selected","selected");
	},1000);
	//初始化
	var opt = $("#index-category-old option");
	var data=new String('{$config.index_category}').split(",");
	opt.each(function(){
		if($.inArray($(this).val(),data)!=-1){
			$(this).clone().appendTo("#index-category");
		};
	});	
	$("#index-category-old").bind("blur",function(){
		$("#index-category").find("option").attr("selected","selected");
	})
 
 
	function has_clone(text){
		var opt = $("#index-category option");
		var stage=true;
		opt.each(function(){
			if(text==$(this).text())stage=false;
		});
		return stage;
	}
	$("#index-category-old").dblclick(function() {
		var opt = $("#index-category-old option:selected");
		if (!opt.length) return;
		opt.each(function(){
			if(has_clone($(this).text())){
				$(this).clone().appendTo("#index-category");
			}
		});
		$("#index-category").find("option").attr("selected","selected");
 
	});
	$("#index-category-add").click(function() {
		var opt = $("#index-category-old option:selected");
		if (!opt.length) return;
		opt.each(function(){
			if(has_clone($(this).text())){
				$(this).clone().appendTo("#index-category");
			}
		});
		$("#index-category").find("option").attr("selected","selected");
 
	});
	$("#index-category-remove").click(function() {
		$("#index-category option:selected:first").remove();
 
	});
	$("#index-category-up,#index-category-down").click(function() {
		var opt = $("#index-category option:selected:first");
		if (!opt.length) return;
		if ($(this).attr("id") == "index-category-up"){
			opt.prev().before(opt);
		}else{
			opt.next().after(opt);
		}
	});
});
</script>