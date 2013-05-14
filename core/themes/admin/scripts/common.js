function check_all(obj){
	$('.checkbox').each(function(){
		$(this).attr('checked',obj.checked);
	});
}
function tab(no,count){
	for (var i=0;i<count ;i++ ){
		$('#tab_'+i).removeClass('hover');
		$('#content_'+i).hide();
	}
	$('#tab_'+no).addClass('hover');
	$('#content_'+no).show();
}
function back(){
	window.history.back();
}
function show_tip(html,time,width){
		var x=($(document).width()-width)/2;
		var y=$(document).height()/2-300;
		var div=$('#tip').attr('id','tip').css({left:x+'px',top:y+'px',width:width+'px'}).show().animate({top:(y+20)+'px'});
			$('body').append(div);
			if(arguments.length==4){
				div.html(div.html()+html);
			}else{
				div.html(html);
			}
			if(time>0){
			   setTimeout(function(){div.animate({top:(y-20)+'px'}).fadeOut('slow');},time*1000);
			}
	}
function change_menu(name){
	$('.submenu').not('.'+name).slideUp();
	$('.'+name).slideDown();
}
/*
 * jQuery 拖动插件
 * 2013.02.07 
 * 野草
 */
(function($){
	$.fn.drag=function(options){
		var options=$.extend({x:true,y:true,area:true},options);
		var that=$(this);
		var is_drag = false;
		var _x,_y;
		that.mousedown(function(event){
                var e=event || window.event;
                is_drag = true;
                _x=e.pageX-that.position().left;
                _y=e.pageY-that.position().top;
                return false
        });
 		$(document).mousemove(function(event){       
			if(!is_drag) return false;
			var e=event || window.event;
			var maxwidth=$(document).width()-that.width();
			var maxheight=$(document).height()-that.height();
			var x=e.pageX-_x;
				x = x < 0 ? 0: x;
				x = x >maxwidth ?maxwidth: x;
 			var y=e.pageY-_y;
				y = y < 0 ? 0: y;
				y = y >maxheight ?maxheight: y;
 
				console.log(x);
            that.css({left:x,top:y})
            return false;  
        }).mouseup(function(){
                is_drag = false;
                return false;
        });
		return this;
	};
})(jQuery);
