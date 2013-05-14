<?php
if(!defined('ROOT'))exit('Access denied!');

if($this->do=='article'){
	$this->check_access('content_article');
	$content->check_category();
	$sql="SELECT article_id,article_title,article_image,article_html,category_id,article_is_best,article_is_comment,article_comment_count FROM ".DB_PREFIX."content_article WHERE 1=1";
 
	if(!empty($_GET['category_id'])){
		$sql.=" AND category_id='".$_GET['category_id']."'";
	}
	if(!empty($_GET['keyword'])){
		$sql.=" AND article_title like '%".trim($_GET['keyword'])."%'";
	}
	$sql.=" ORDER BY article_id DESC";
	//echo $sql;
	$page_size=$this->config['content_admin_article_list_size']?$this->config['content_admin_article_list_size']:20;
	$page_current=isset($_GET['page'])&&is_numeric($_GET['page'])?intval($_GET['page']):1;
	$count=$this->db->count($sql);
	$res=$this->db->result($sql." limit ".(($page_current-1)*$page_size).",".$page_size);
	$array=array();
	$pager="";
	if($count>0){
		foreach($res as $row){
			$array[$row['article_id']]['id']=$row['article_id'];
			$array[$row['article_id']]['title']=$row['article_title'];
			$array[$row['article_id']]['image']=$row['article_image'];
			$array[$row['article_id']]['html']=$row['article_html'];
			$array[$row['article_id']]['url']=$content->uri($row['category_id'],$row['article_id'],$row['article_html']);
			//$array[$row['article_id']]['time']=date("Y/m/d H:i:s",$row['article_time']);
			$array[$row['article_id']]['comment_count']=$row['article_comment_count'];
			$array[$row['article_id']]['category_id']=$row['category_id'];
			$array[$row['article_id']]['category_name']=$this->db->value(DB_PREFIX."content_category","category_name","category_id=".$row['category_id']);
			$array[$row['article_id']]['is_best']=$row['article_is_best'];
			$array[$row['article_id']]['is_comment']=$row['article_is_comment'];
		}
		$parameter='action=content&do=article&';
		if(!empty($_GET['category_id'])){
			$parameter.="category_id=".$_GET['category_id']."&";
		}
		if(!empty($_GET['keyword'])){
			$parameter.="keyword=".trim($_GET['keyword'])."&";
		}
		$pager=pager(get_self(),$parameter,$page_current,$page_size,$count);
	}
	$this->template->in('article',$array);
	$this->template->in('pager',$pager);
	$this->template->in('category_option',$content->category_option());
	$this->template->out('content.article.list.php');
}
if($this->do=='article_add'||$this->do=='article_edit'){
	$array=array();
	$mode='insert';
	if($this->do=='article_add'){
		$this->check_access('content_article_add');
		$category_id=empty($_GET['category_id'])?0:intval($_GET['category_id']);
		$array['category_id']=$category_id;
		$array['content_mode']=0;
		$array['html']=intval($this->db->value(DB_PREFIX."content_article","max(article_id)","1=1 ORDER BY article_id DESC"))+1;
		$array['is_best']=0;
		$array['is_comment']=1;
		$array['is_display']=1;
		if(isset($_GET['draft_id'])){//读取投稿数据
			$draft_id=intval($_GET['draft_id']);
			$row=$this->db->row("SELECT * FROM ".DB_PREFIX."content_draft WHERE draft_id=$draft_id");
			$array['title']=$row['draft_title'];
			$array['content']=$row['draft_content'];
			$array['author']=$row['draft_author'];
			$this->template->in('draft_id',$draft_id);
			$array['category_id']=$row['category_id'];
		}
		if(!empty($_GET['category_id'])){//栏目选择
			$this->template->in('category_option',$content->category_option(0,$_GET['category_id'],true));
		}else{
			if(!empty($row['category_id'])){
				$this->template->in('category_option',$content->category_option(0,$row['category_id'],true));
			}else{
				if(!empty($_SESSION['category_id'])){
					$this->template->in('category_option',$content->category_option(0,$_SESSION['category_id'],true));
				}else{
					$this->template->in('category_option',$content->category_option(0,0,true));
				}
			}
		}
	}else{
		$this->check_access('content_article_edit');
		$mode='update';
		$article_id=empty($_GET['article_id'])?0:intval($_GET['article_id']);
		$row=$this->db->row("SELECT * FROM ".DB_PREFIX."content_article WHERE article_id=".$article_id);
		$array=array();
		$array['id']=$row['article_id'];
		$array['title']=$row['article_title'];
		$array['content']=$row['article_content'];
		$array['content_mode']=$row['article_content_mode'];
		$array['author']=$row['article_author'];
		$array['keywords']=$row['article_keywords'];
		$array['description']=$row['article_description'];
		$array['image']=$row['article_image'];
		$array['file']=$row['article_file'];
		$array['html']=$row['article_html'];
		$array['is_best']=$row['article_is_best'];
		$array['is_comment']=$row['article_is_comment'];
		$array['is_nofollow']=$row['article_is_nofollow'];
		$array['is_display']=$row['article_is_display'];
		$array['category_id']=$row['category_id'];
		$this->template->in('category',$content->get_category_info($row['category_id']));
		$this->template->in('category_option',$content->category_option(0,$row['category_id'],true));
	}
	$this->template->in('article',$array);
	$this->template->in('mode',$mode);
	$this->template->out('content.article.info.php');
}
if($this->do=='article_insert'||$this->do=='article_update'){
	$article_title=empty($_POST['article_title'])?'':trim(addslashes($_POST['article_title']));
	$article_content=empty($_POST['article_content'])?'':trim(addslashes($_POST['article_content']));
	$article_content_mode=empty($_POST['article_content_mode'])?0:1;
	$article_author=empty($_POST['article_author'])?'':trim(addslashes($_POST['article_author']));
	$article_keywords=empty($_POST['article_keywords'])?'':trim(addslashes($_POST['article_keywords']));
	$article_description=empty($_POST['article_description'])?'':trim(addslashes($_POST['article_description']));
	$article_file=empty($_POST['article_file'])?'':trim(addslashes($_POST['article_file']));
	$article_html=empty($_POST['article_html'])?'':trim(addslashes($_POST['article_html']));
	$article_image=empty($_POST['article_image'])?'':trim(addslashes($_POST['article_image']));
	$article_is_best=empty($_POST['article_is_best'])?0:1;
	$article_is_comment=empty($_POST['article_is_comment'])?0:1;
	$article_is_nofollow=empty($_POST['article_is_nofollow'])?0:1;
	$article_is_display=empty($_POST['article_is_display'])?0:1;
	$category_id=empty($_POST['category_id'])?0:intval($_POST['category_id']);
	if($article_title=='')alert('标题不能为空');
	if($article_content=='')alert('内容不能为空');
	if($content->category_have($category_id))alert('该栏目含有子栏目，请重新选择！');
	#本地方式上传
	$article_image_upload=upload($_FILES['article_image_upload'],'data/content/','jpg,png,gif,bmp',2);
	if(!empty($article_image_upload)){
		$article_image=PATH.'data/content/'.$article_image_upload;
	}
	$array=array();
	$array['article_title']=$article_title;
	$array['article_content']=$article_content;
	$array['article_content_mode']=$article_content_mode;
	$array['article_author']=$article_author;
	$array['article_keywords']=$article_keywords;
	$array['article_description']=$article_description;
	$array['article_file']=$article_file;
	$array['article_html']=$article_html;
	$array['article_image']=$article_image;
	$array['article_is_best']=$article_is_best;
	$array['article_is_comment']=$article_is_comment;
	$array['article_is_nofollow']=$article_is_nofollow;
	$array['article_is_display']=$article_is_display;
	$array['article_time']=$_SERVER['REQUEST_TIME'];
	$array['category_id']=$category_id;

	if($this->do=='article_insert'){
		$this->check_access('content_article_add');
		$this->db->insert(DB_PREFIX."content_article",$array);
		$article_id=$this->db->id();
		if(!empty($_POST['fields'])){#自定义字段录入
			$fields=explode("|",trim($_POST['fields']));
			foreach($fields as $v){
				$data=explode(",",$v);
				$data_value=@$_POST['field_'.$data[1]];
				if(!empty($data_value)){
					$this->db->insert(DB_PREFIX."content_field_data",array(
						'data_value'=>$data_value,
						'article_id'=>$article_id,
						'field_id'=>$data[0]
					));
				}
			}
		}
	}else{
		$this->check_access('content_article_edit');
		$article_id=empty($_POST['article_id'])?0:intval($_POST['article_id']);
		#删除原始图像
		if(!empty($article_image_old)){
			if($article_image!=$article_image_old){
				@unlink(ROOT.'/'.$article_image_old);
			}
		}
		if(!empty($article_image_delete)){
			@unlink(ROOT.'/'.$article_image_delete);
			$article_image='';
		}
		if($this->config['content_mode']==1){#要是生成文件名被修改则删除旧文件
			if($article_html_old!=$article_html){
				$path=$content->category_parent_path($category_id);
				@unlink(ROOT.'/'.$path.'/'.$article_html_old.'.html');
			}
			if($article_is_display==0){
				$path=$content->category_parent_path($category_id);
				@unlink(ROOT.'/'.$path.'/'.$article_html_old.'.html');
			}
		}
		$this->db->update(DB_PREFIX."content_article",$array,"article_id=$article_id");
		
		if(!empty($_POST['fields'])){#自定义字段更新
			$fields=explode("|",trim($_POST['fields']));
			foreach($fields as $v){
				$data=explode(",",$v);
				$data_value=@$_POST['field_'.$data[1]];
				if(!empty($data_value)){
					$this->db->update(DB_PREFIX."content_field_data",array('data_value'=>$data_value),"article_id=$article_id AND field_id=".$data[0]);
				}
			}
		}
	}
	$_SESSION['category_id']=$category_id;
	
	if(!empty($_POST['tag'])&&!empty($article_keywords)){#把关键字加入标签库
		$tag_explode=explode(",",$article_keywords);
		if(count($tag_explode)>0){
			foreach($tag_explode as $v){
				if(!empty($v)){
					$tag_link=$content->uri($category_id,$article_id,$article_html);
					$this->db->insert(DB_PREFIX."tag",array('tag_name'=>$v,'tag_link'=>addslashes($tag_link)));
				}
			}
		}
	}
	if($this->config['content_mode']==1){
		$content->html_index();
		$content->html_article($article_id);
		$content->html_category_index($category_id);
		$content->html_category_list($category_id);
	}else{
		clear_cache();
	}
	redirect('?action=content&do=article');
}

if($this->do=='article_delete'){#删除内容
	$this->check_access('content_article');
	$article_id=empty($_POST['article_id'])?array():$_POST['article_id'];
	foreach($article_id as $value){
		if(!empty($value)){
			//判断内容是否有缩图，有就删除
			$row=$this->db->row("SELECT article_image,article_file,category_id,article_html FROM ".DB_PREFIX."content_article WHERE article_id='".$value."'");
			if(!empty($row['article_image'])){
				@unlink(ROOT."/".$row['article_image']);
			}
			//提取该内容附属的附件文件名并删除
			$file=explode(",",$row['article_file']);
			foreach($file as $v){
				@unlink(ROOT."/".$v);
			}
			#删除静态文件？还是不删除呢？
			@unlink(ROOT.'/'.$content->category_parent_path($row['category_id'])."/".$row['article_html'].".html");
			$this->db->delete(DB_PREFIX."content_comment","article_id=".$value."");
			$this->db->delete(DB_PREFIX."content_article","article_id=".$value."");
		}
	}
	if($this->config['content_mode']==1){
		$this->html_index();
	}else{
		clear_cache();
	}
	redirect('?action=content&do=article');
}
if($this->do=='get_fields'){
	$this->check_access('content_article');
	$category_id=empty($_GET['category_id'])?0:intval($_GET['category_id']);
	$article_id=empty($_GET['article_id'])?0:intval($_GET['article_id']);
	$result=$this->db->result("SELECT * FROM ".DB_PREFIX."content_field WHERE category_id='$category_id' AND field_status=1");
	$hidden=array();
	foreach($result as $row){
		$data=$this->db->row("SELECT * FROM ".DB_PREFIX."content_field_data WHERE field_id='".$row['field_id']."' AND article_id=$article_id");
		echo $row['field_text']."：";
		echo"<div class=\"blank\"></div>";
		echo"<input tabindex=\"2\" type=\"text\" class=\"input\" name=\"field_".$row['field_name']."\" style=\"width:400px\" value=\"".($data['data_value']!=''?trim($data['data_value']):'')."\" />";
		echo"<div class=\"blank\"></div>";
		$hidden[]=$row['field_id'].",".$row['field_name'];
	}
	echo"<input type='hidden' name='fields' value='".implode("|",$hidden)."'/>";
}
if($this->do=='get_description'){
	$this->check_access('content_article');
	$content=isset($_POST['content'])?trim($_POST['content']):'';
	echo truncate(strip_tags($content),100);
	exit;
}
if($this->do=='get_html'){
	$this->check_access('content_article');
	$content=isset($_POST['content'])?trim($_POST['content']):'';
	$content=str_replace(",","-",$content);
	echo pinyin($content);
	exit;
}
if($this->do=='check_html'){
	$this->check_access('content_article');
	$id=isset($_GET['id'])?intval($_GET['id']):0;
	$content=isset($_GET['content'])?trim($_GET['content']):'';
	$sql="SELECT * FROM ".DB_PREFIX."content_article WHERE article_html='$content'";
	if($id>0){
		$sql.=" AND article_id!=$id ";
	}
	$sql.=" LIMIT 0,1";
	$row=$this->db->row($sql);
	if($row){
		echo'YES';
	}
	exit;
}
if($this->do=='clear'){
	$this->check_access('content_article');
	$content->html_clear();
	redirect('?action=content&do=article');
	exit;
}
if($this->do=='html'){
	$this->check_access('content_article');
	$article_id=empty($_POST['article_id'])?array():$_POST['article_id'];
	foreach($article_id as $value){
		if(!empty($value)){
			$content->html_article($value);
		}
	}
	$content->html_index();
	$this->set_hook('batch.html.article');
	redirect('?action=content&do=article');
}
if($this->do=='delete_file'){
	$this->check_access('content_article');
	$filename=empty($_GET['filename'])?'':trim($_GET['filename']);
	if(file_exists(ROOT.$filename)){
		@unlink(ROOT.$filename);
	}
	exit;
}
if($this->do=='make_thumb'){
	$this->check_access('content_article');
	$image=isset($_GET['image'])?trim($_GET['image']):'';
	$category_id=isset($_GET['category_id'])?trim($_GET['category_id']):'';
	if($category_id==''||$image==''){
		exit('ERROR');
	}
	$ext=strtolower(get_ext($image));
	$name=date('YmdHis');
	for($i=0; $i < 3; $i++)$name.= chr(mt_rand(97, 122));
	$name='thumb-'.strtoupper(md5($name)).".".$ext;
	$category_info=$content->get_category_info($category_id);
	if($category_info['image_width']==0||$category_info['image_height']==0){
		exit('ERROR');
	}
	if(PATH=='/'){
		make_thumb(ROOT.$image,$category_info['image_width'],$category_info['image_height'],ROOT.'/data/content/'.$name);
	}else{
		make_thumb(str_replace(PATH,"",$image),$category_info['image_width'],$category_info['image_height'],ROOT.'/data/content/'.$name);
	}
	exit(PATH.'data/content/'.$name);
}
if($this->do=='crop_image'){
	$this->check_access('content_article');
	$image=isset($_GET['image'])?trim($_GET['image']):'';
	$ext=strtolower(get_ext($image));
	$name=date('YmdHis');
	for ($i=0; $i < 3; $i++){
		$name.= chr(mt_rand(97, 122));
	}
	$name=strtoupper(md5($name)).".".$ext;
	$name='/data/content/'.$name;
	$image_name=ROOT.$name;
	if(strpos($image,'http://')!==false){
		$image_data=http($image);#抓取远程图片到本地
		if(file_put_contents($image_name,$image_data)){
			$image=str_replace(ROOT,'',$image_name);
		}else{
			exit('ERROR:FETCH');
		}
	}
	$x=isset($_GET['x'])?intval($_GET['x']):0;
	$y=isset($_GET['y'])?intval($_GET['y']):0;
	$w=isset($_GET['w'])?intval($_GET['w']):0;
	$h=isset($_GET['h'])?intval($_GET['h']):0;
	$width=$w;$height=$h;
	if($ext=='jpg'){
		$im=imagecreatefromjpeg(ROOT.$image);
	}elseif($ext=='png'){
		$im=imagecreatefrompng(ROOT.$image);
	}elseif($ext=='gif'){
		$im=imagecreatefromgif(ROOT.$image);
	}
	$canvas=imagecreatetruecolor($width,$height);
	imagecopyresampled($canvas,$im,0,0,$x,$y,$width,$height,$w,$h);
	header('content-type:image/jpeg');
	if($ext=='jpg'){
		imagejpeg($canvas,$image_name,100);
	}elseif($ext=='png'){
		imagepng($canvas,$image_name);
	}elseif($ext=='gif'){
		imagegif($canvas,$image_name);
	}
	imagedestroy($canvas);
	imagedestroy($im);
	exit($name);
}

if($this->do=='create_go'){
	@set_time_limit(0);
	$filename=md5(date('Ym')).'.txt';
	$no=isset($_GET['no'])?intval($_GET['no']):0;
	$array=array();
	if($no==0){
		$content->html_index();
		$content->category_path();
		$result=$this->db->result("SELECT * FROM ".DB_PREFIX."content_category WHERE category_is_display=1");
		foreach($result as $row){
			$array[]=array($row['category_id'],$row['category_name']);
		}
		unset($result);
		@file_put_contents(ROOT.'/'.$filename,base64_encode(serialize($array)));
	}else{
		$array=unserialize(base64_decode(file_get_contents(ROOT.'/'.$filename)));
	}
	echo'<html><head><meta charset="utf-8"></head><body bgColor="transparent" style="font-size:12px;font-family:Microsoft Yahei;color:#000">';
	if($no<count($array)){
 
		$sql="SELECT * FROM ".DB_PREFIX."content_article WHERE category_id=".$array[$no][0];
		$page_size=50;
		$page_current=isset($_GET['page'])?intval($_GET['page']):1;
		$count=$this->db->count($sql);

		//if($count>0){
			echo'正在准备生成栏目：'.$array[$no][1].'<br />';
			$page_count=ceil($count/$page_size);
			$result=$this->db->result($sql." limit ".(($page_current-1)*$page_size).",".$page_size);
			if($page_current==1){
				$content->html_category_index($array[$no][0]);
				$content->html_category_list($array[$no][0]);
			}

			foreach($result as $row){
				echo'正在生成文章：'.$row['article_title'].'<br />';
				$content->html_article($row['article_id']);
			}
		//}
		if($page_current<$page_count){//当前翻页小于总页数
			++$page_current;
			echo'<script>location.href="?action=content&do=create_go&no='.$no.'&page='.$page_current.'"</script>';
		}else{
			++$no;
			echo'<script>location.href="?action=content&do=create_go&no='.$no.'"</script>';
		}
	}else{
		echo'全部生成完毕！';
		@unlink(ROOT.'/'.$filename);
	}
	echo'</body></html>';
}