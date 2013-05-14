jQuery.fn.LoadImage=function(width,height,loadpic){
	return this.each(function(){
		var t=$(this);
		var src=$(this).attr("src")
		var img=new Image();
			img.src=src;
		var autoScaling=function(){
			if(img.width>=width){
				if(img.width>0 && img.height>0){ 
					//if(img.width/img.height>=width/height){ 
						if(img.width>width){ 
							t.width(width); 
							t.height((img.height*width)/img.width); 
						}else{ 
							t.width(img.width); 
							t.height(img.height); 
						} 
						/*
					}else{ 
						if(img.height>height){ 
							t.height(height); 
							t.width((img.width*height)/img.height); 
						}else{ 
							t.width(img.width); 
							t.height(img.height); 
						} 
					}
					*/
				}
			}
		}
		//处理ff下会自动读取缓存图片
		if(img.complete){
			autoScaling();
		    return;
		}
		$(this).attr("src","");
		var loading=$("<img alt=\"加载中...\"  src=\""+loadpic+"\" style=\"display:block\"/>");
		t.hide();
		t.after(loading);
		$(img).load(function(){
 
			
				autoScaling();
				loading.remove();
				t.attr("src",this.src);
				t.show();
 
		});
	});
}