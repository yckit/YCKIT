function update_article_click(id){
	$.ajax({
		type:'GET',
		url:PATH+'front.php?action=content&do=click&article_id='+id,
		success:function(result){
			$("#click_count").html(result);
		}
	});
}
//评论
function get_comment(type,id,page){
	var type_name=type=='content'?'article_id':'page_id';
	$.ajax({
		type:'GET',
		url:PATH+'front.php?action='+type+'&do=comment&'+type_name+'='+id+'&page='+page,
		success:function(result){
			$("#comment").html(result);
			$("#comment_insert").click(function(){
				var comment_content=$('#comment_content').val();
				var comment_name=$('#comment_name').val();
				var comment_email=$('#comment_email').val();
				var comment_site=$('#comment_site').val();
				var parent_id=$('#parent_id').val();
				if($.trim(comment_content)==''){
					alert('内容不能为空');
					$('#comment_content').focus();
					return false;
				}
				if($.trim(comment_name)==''){
					alert('大名不能为空');
					$('#comment_name').focus();
					return false;
				}
				if($.trim(comment_email)==''){
					alert('邮件不能为空');
					$('#comment_email').focus();
					return false;
				}
				var reg=/([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)/;
				if(!reg.test(comment_email)){
					alert('邮件不合法');
					$('#comment_email').focus();
					return false;
				}
				$.ajax({
					type:"GET",
					url:PATH+"front.php?action="+type+"&do=comment_insert&comment_content="+encodeURI(comment_content)+"&comment_name="+encodeURI(comment_name)+"&comment_email="+encodeURI(comment_email)+"&comment_site="+encodeURI(comment_site)+"&"+type_name+"="+id+"&parent_id="+parent_id+"&r="+Math.random(),
					dataType:"text",
					success:function(e){
						if(e=='EMPTY:NAME'){
							alert('大名不能为空');
							return false;
						}
						if(e=='ERROR:EMAIL'){
							alert('邮件不能为空');
							return false;
						}
						if(e=='EMPTY:CONTENT'){
							alert('内容不能为空');
							return false;
						}
						if(e=='WAIT'){
							alert('恭喜您发表成功，等待管理员审核后方可显示！');
						}
						get_comment(type,id,1);
				}});
			});
		}
	});
}
 
function reply_comment(id){
	$('#parent_id').val(id);
	$('#comment_content').focus();
}

function Search(){
	var keyword=jQuery("#search_keyword").val();
	var action=jQuery("#search_action").val();
	if(jQuery.trim(keyword)==''){
		alert('关键字不能为空');
		jQuery("#search_keyword").focus();
		return;
	}
	keyword=keyword.replace(/\'/gi,"");
	keyword=keyword.replace(/\"/gi,"");
	keyword=keyword.replace(/\?/gi,"");
	keyword=keyword.replace(/\%/gi,"");
	keyword=keyword.replace(/\./gi,"");
	keyword=keyword.replace(/\*/gi,"");
	window.location.href=PATH+"search.php?action="+action+"&keyword="+encodeURI(keyword);
}
String.prototype.strip_tags=function (allow) {
	var allow = allow ? allow.toLowerCase() : '';
	return this.replace(/<[\/\!\?]?([\w_-]*)[^>]*>/igm , function($0, $1) {
		return allow.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
	});
}

$.fn.scrollToTop=function(){
	var me=$(this);
	$(window).scroll(function(){
		if($(window).scrollTop()<10){
			me.fadeOut();
		}else{
			me.fadeIn();
		}
	});
	me.click(function(){
		$("html,body").animate({scrollTop:0});return false;
	});
}
function draft(){
	$.ajax({
		url:PATH+'front.php?action=content&do=draft',
		success:function(e){
			$.box({
					title:'投稿中心',
					width:700,
					height:520,
					close_button:true,
					html:e,
					callback:function(){
						e=$('#draft_content').xheditor({
								shortcuts:{
									'ctrl+enter':function(){
										$('#article_info').submit();
									}
								},
								tools:'simple',
								plugins:{
									Code:{c:'btnCode',t:'插入代码',h:1,e:function(){
										var _this=this;
										var htmlCode='<div><select id="xheCodeType"><option value="html">HTML/XML</option><option value="js">Javascript</option><option value="css">CSS</option><option value="php">PHP</option><option value="java">Java</option><option value="py">Python</option><option value="pl">Perl</option><option value="rb">Ruby</option><option value="cs">C#</option><option value="c">C++/C</option><option value="vb">VB/ASP</option><option value="">其它</option></select></div><div><textarea id="xheCodeValue" wrap="soft" spellcheck="false" style="width:300px;height:100px;" /></div><div style="text-align:right;"><input type="button" id="xheSave" value="确定" /></div>';			var jCode=$(htmlCode),jType=$('#xheCodeType',jCode),jValue=$('#xheCodeValue',jCode),jSave=$('#xheSave',jCode);
										jSave.click(function(){
											_this.loadBookmark();
											_this.pasteHTML('<pre class="prettyprint lang-'+jType.val()+'">'+_this.domEncode(jValue.val())+'</pre>');
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

					}
			});	

		}
	})
}
$(function() {
	$('.post-title a').click(function(e) {
		e.preventDefault();
		var htm = '很给力的加载中...',
		i = 9,
		t = $(this).html(htm).unbind('click'); (function ct() {
			i < 0 ? (i = 9, t.html(htm), ct()) : (t[0].innerHTML += '.', i--, setTimeout(ct, 200))
		})();
		window.location = this.href
	})
});