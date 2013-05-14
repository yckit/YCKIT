(function($){
//通用窗口
	$.box=function(options){
		var options=$.extend({title:'',width:300, height:300,html:'',close_button:true,callback:function(){}},options);
		box_close();
		//窗口外框
		var mask=$('<div />');
			mask.attr('id','box-mask');
			mask.addClass('box-mask');
			mask.css({
				top:0,
				left:0,
				width:$(document).width()+'px',
				height:$(document).height()+'px'
			});
			$(document.body).append(mask);
		//窗口外框
		var layout=$('<div />');
			layout.attr('id','box-layout');
			layout.addClass('box-layout');
			layout.css({
				top:(($(window).height()-options.height)/2+$(document).scrollTop())+'px',
				left:($(document).width()-options.width)/2+'px',
				width:options.width+10+'px',
				height:options.height+10+'px'
			});
			$(document.body).append(layout);
		//窗口内框
		var content=$('<div />');
			content.attr('id','box-content');
			content.addClass('box-content');
			content.width(options.width);
			content.height(options.height);
			content.appendTo(layout);
		//标题
		var caption=$('<div />');
 			caption.addClass('box-caption');
			caption.appendTo(content);
			caption.html(options.title);
 		if(options.close_button){
			//关闭按钮
			var close=$('<a href="javascript:void(0)" id="box-close"/></a>');
				close.addClass('box-close');
				close.appendTo(content);
				close.bind('click',function(){
					$('#box-layout').fadeOut('fast',function(){
							$('#box-mask').remove();
							$(this).empty().remove();
						});
				});
		}
		//HTML
		var html=$('<div />');
 			html.addClass('box-html');
			html.appendTo(content);
			html.html(options.html);
			content.append(html);
			layout.fadeIn('fast',function(){
				//layout.animate({top:'150px'},500);
			});
			options.callback();

	};
	return this;
})(jQuery);
var box_close=function(){
	$('#box-layout').fadeOut('slow',function(){
		$('#box-mask').remove();
		$(this).empty().remove();
	});
};