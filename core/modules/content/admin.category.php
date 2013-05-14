<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->do=='category'){
	$this->check_access('content_category');
	$this->template->in('category_html',$content->category_html());
	$this->template->out('content.category.list.php');
}
if($this->do=='category_add'||$this->do=='category_edit'){
	$array=array();
	$mode='insert';
	if($this->do=='category_add'){
		$this->check_access('content_category_add');
		$array['list_limit']=8;
		$array['image_width']=250;
		$array['image_height']=250;
		$array['is_display']=1;
		$this->template->in('category_option',$content->category_option());
	}else{
		$this->check_access('content_category_edit');
		$mode='update';
		$category_id=empty($_GET['category_id'])?0:intval($_GET['category_id']);
		$row=$this->db->row("SELECT * FROM ".DB_PREFIX."content_category WHERE category_id='$category_id' LIMIT 0,1");
		$array=array();
		$array['id']=$row['category_id'];
		$array['name']=$row['category_name'];
		$array['dir']=$row['category_dir'];
		$array['keywords']=$row['category_keywords'];
		$array['description']=$row['category_description'];
		$array['list_limit']=$row['category_list_limit'];
		$array['image_width']=$row['category_image_width'];
		$array['image_height']=$row['category_image_height'];
		$array['is_display']=$row['category_is_display'];
		$this->template->in('category_option',$content->category_option(0,$row['parent_id']));
		$field_list=$this->db->result("SELECT * FROM ".DB_PREFIX."content_field WHERE category_id='$category_id'");
		$field=array();
		foreach($field_list as $row){
			$field[$row['field_id']]['id']=$row['field_id'];
			$field[$row['field_id']]['name']=$row['field_name'];
			$field[$row['field_id']]['text']=$row['field_text'];
			$field[$row['field_id']]['status']=$row['field_status'];
		}
		$this->template->in('field',$field);
	}
	$this->template->in('category',$array);
	$this->template->in('mode',$mode);
	$this->template->out('content.category.info.php');
}
 
if($this->do=='category_insert'||$this->do=='category_update'){
	$category_name=empty($_POST['category_name'])?'':addslashes(trim($_POST['category_name']));
	$category_dir=empty($_POST['category_dir'])?'':addslashes(trim($_POST['category_dir']));
	$category_keywords=empty($_POST['category_keywords'])?'':addslashes(trim($_POST['category_keywords']));
	$category_description=empty($_POST['category_description'])?'':addslashes(trim($_POST['category_description']));
 	$category_list_limit=empty($_POST['category_list_limit'])?10:intval($_POST['category_list_limit']);
 	$category_is_display=empty($_POST['category_is_display'])?0:1;
	$category_image_width=empty($_POST['category_image_width'])?100:intval($_POST['category_image_width']);
	$category_image_height=empty($_POST['category_image_height'])?100:intval($_POST['category_image_height']);
	$parent_id=empty($_POST['parent_id'])?0:intval($_POST['parent_id']);
	if($parent_id==0){
		$category_deep=0;
	}else{
		$row=$this->db->row("SELECT category_deep FROM ".DB_PREFIX."content_category WHERE category_id=$parent_id");
		$category_deep=$row['category_deep']+1;
	}
	if($category_name=='')alert('分类名称不能为空');
	if(file_exists($category_dir))alert('分类目录已存在');
	if(empty($category_dir)&&!empty($category_name))$category_dir=pinyin($category_name);
	$array=array();
	$array['category_name']=$category_name;
	$array['category_dir']=$category_dir;
	$array['category_keywords']=$category_keywords;
	$array['category_description']=$category_description;
	$array['category_deep']=$category_deep;
	$array['category_list_limit']=$category_list_limit;
	$array['category_is_display']=$category_is_display;
	$array['category_image_width']=$category_image_width;
	$array['category_image_height']=$category_image_height;
	$array['parent_id']=$parent_id;

	if($this->do=='category_insert'){
		$this->check_access('content_category_add');
		$this->db->insert(DB_PREFIX."content_category",$array);
		$category_id=$this->db->id();
		if(!empty($_POST['into_menu'])){
			$array=array();
			$array['menu_name']=$category_name;
			$array['menu_link']=$content->uri($category_id);
			$array['menu_description']=$category_description;
			$array['menu_target']=0;
			$array['menu_sort']=$this->db->count("SELECT COUNT(*) from ".DB_PREFIX."menu")+1;
			$array['menu_status']=$category_is_display;
			$array['parent_id']=0;
			$this->db->insert(DB_PREFIX."menu",$array);
		}
		# 自定义字段
		$field_name=empty($_POST['field_name_'])?array():$_POST['field_name_'];
		foreach($field_name as $k=>$v){
			if(!empty($_POST['field_name_'][$k])&&!empty($_POST['field_text_'][$k])){
				$array=array();
				$array['field_name']=trim($_POST['field_name_'][$k]);
				$array['field_text']=trim($_POST['field_text_'][$k]);
				$array['field_status']=intval($_POST['field_status_'][$k]);
				$array['category_id']=$category_id;
				$this->db->insert(DB_PREFIX."content_field",$array);
			}
		}
	}else{
		$this->check_access('content_category_edit');
		if($this->config['content_mode']==1){
			if($category_dir_old!=$category_dir){
				$path=str_replace($category_dir_old,'',$content->category_parent_path($category_id));
				@rename($path.'/'.$category_dir_old,$path.'/'.$category_dir);
			}
		}
		$category_id=empty($_POST['category_id'])?0:intval($_POST['category_id']);
		$array=array();
		$array['category_name']=$category_name;
		$array['category_dir']=$category_dir;
		$array['category_keywords']=$category_keywords;
		$array['category_description']=$category_description;
		$array['category_deep']=$category_deep;
		$array['category_list_limit']=$category_list_limit;
		$array['category_is_display']=$category_is_display;
		$array['category_image_width']=$category_image_width;
		$array['category_image_height']=$category_image_height;
		$array['parent_id']=$parent_id;
		$this->db->update(DB_PREFIX."content_category",$array,"category_id=$category_id");
		#自定义字段
		$field_id=empty($_POST['field_id'])?array():$_POST['field_id'];
		$field_delete=empty($_POST['field_delete'])?array():$_POST['field_delete'];
		$field_update=array_diff($field_id,$field_delete);
		foreach($field_delete as $id){
			$this->db->delete(DB_PREFIX."content_field_data","field_id=$id");
			$this->db->delete(DB_PREFIX."content_field","field_id=$id");
		}
		foreach($field_update as $id){
			if(!empty($_POST['field_name'][$id])&&!empty($_POST['field_text'][$id])){
				$array=array();
				$array['field_name']=trim($_POST['field_name'][$id]);
				$array['field_text']=trim($_POST['field_text'][$id]);
				$array['field_status']=intval($_POST['field_status'][$id]);
				$this->db->update(DB_PREFIX."content_field",$array,"field_id=$id");
			}
		}
		#自定义字段添加
		$field_name=empty($_POST['field_name_'])?array():$_POST['field_name_'];
		foreach($field_name as $k=>$v){
			if(!empty($_POST['field_name_'][$k])&&!empty($_POST['field_text_'][$k])){
				$array=array();
				$array['field_name']=trim($_POST['field_name_'][$k]);
				$array['field_text']=trim($_POST['field_text_'][$k]);
				$array['field_status']=intval($_POST['field_status_'][$k]);
				$array['category_id']=$category_id;
				$this->db->insert(DB_PREFIX."content_field",$array);
			}
		}
	}
	if($this->config['content_mode']==1){
		$content->category_path();
		$content->html_index();
		$content->html_category_index($category_id);
		$content->html_category_list($category_id);
	}else{
		clear_cache();
	}
	redirect('?action=content&do=category');
}

//分类删除
if($this->do=='category_delete'){
	$category_id=empty($_GET['category_id'])?0:intval($_GET['category_id']);
	#删除该目录
	rm_dir(ROOT.'/'.$content->category_parent_path($category_id));
	#读取内容并删除相关联的杂项
	$sql="SELECT article_id,article_image,article_file FROM ".DB_PREFIX."content_article WHERE category_id=$category_id";
	$result=$this->db->result($sql);
	foreach($result as $row){
		if(!empty($row['article_image']))@unlink(ROOT.'/'.$row['article_image']);
		//提取该内容附属的附件文件名并删除
		$file=explode(",",$row['article_file']);
		foreach($file as $v){
			@unlink(ROOT."/".$v);
		}
		$this->db->delete(DB_PREFIX."content_comment","article_id=".$row['article_id']);//删除内容评论
	}
	$this->db->delete(DB_PREFIX."content_article","category_id=$category_id");//删除内容
	$this->db->delete(DB_PREFIX."content_category","category_id=$category_id");//删除分类
	redirect('?action=content&do=category');
}
//分类清理
if($this->do=='category_clear'){
	$category_id=empty($_POST['category_id'])?array():$_POST['category_id'];
	foreach($category_id as $value){
		if(!empty($value)){
			$dir=$content->category_parent_path($value);
			$folder=@opendir($dir);
			if($folder){
				while ($file=readdir($folder)){
					if($file!="."&&$file!=".."&&$file!="index.htm")@unlink($dir."/".$file);
				}
				closedir($folder);
			}
		}
	}
	redirect('?action=content&do=category');
}
if($this->do=='category_html'){
	$content->category_path();
	$category_id=empty($_POST['category_id'])?array():$_POST['category_id'];
	foreach($category_id as $id){
		if(!empty($id)){
			$content->html_category_index($id);
			$content->html_category_list($id);
			$result=$this->db->result("SELECT * FROM ".DB_PREFIX."content_article WHERE category_id=$id");
			if($result){
				foreach($result as $row){
					$content->html_article($row['article_id']);
				}
			}
			unset($result);

		}
	}
	unset($category_id);
	if($this->config['content_mode']==1){
		$this->html_index();
	}
	redirect('?action=content&do=category');
}