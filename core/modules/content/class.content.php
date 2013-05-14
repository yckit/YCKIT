<?php
if(!defined('ROOT'))exit('Access denied!');
if(!class_exists('content_class')){
	class content_class extends base{
		private $here=array();
		function __construct(){
			$this->init();
		}
		function html_category_index($category_id){
			$category=$this->get_category_info($category_id);
			if(file_exists(ROOT.'/core/themes/'.$this->theme.'/content.category.index.template.'.$category['dir'].'.php')){
				$this->template->template_dir=ROOT.'/core/themes/'.$this->theme;
				$this->template->in('config',$this->config);
				$this->template->in("path",PATH);
				$this->template->in('header',$this->get_header());
				$this->template->in('footer',$this->get_footer());
				$this->template->in("category",$category);
				$this->set_interface();
				$content=$this->template->fetch('content.category.index.template.'.$category['dir'].'.php');
				$path=$this->uri($category_id)."index.html";
				file_put_contents(ROOT.$path,$content);
			}
		}

		function html_category_list($category_id){
			$category=$this->get_category_info($category_id);
			if($this->category_have($category_id)){
				$create_array=array_multi2single($this->category_id_array($category_id));
				array_push($create_array,$category_id);//add by 2012/11/01
				$in=create_sql_in($create_array,'category_id');
			}else{
				$in="category_id='$category_id'";
			}
			$sql="SELECT * FROM ".DB_PREFIX."content_article WHERE article_is_display=1 AND ".$in." ORDER BY article_id DESC";
			$page_size=$category['list_limit']>0?$category['list_limit']:9;
			$count=$this->db->count($sql);
			$page_count=ceil($count/$page_size);
			$page_count=$page_count<1?1:$page_count;
			for($i=1;$i<=$page_count;$i++){
				$result=$this->db->result($sql." LIMIT ".(($i-1)*$page_size).",".$page_size);
				$array=array();
				foreach($result as $row){
					$array[$row['article_id']]['id']=$row['article_id'];
					$array[$row['article_id']]['title']=$row['article_title'];
					$array[$row['article_id']]['description']=$row['article_description'];
					$array[$row['article_id']]['author']=$row['article_author'];
					$array[$row['article_id']]['image']=$row['article_image'];
					$array[$row['article_id']]['content']=$this->get_short_content($row['article_content']);
					$array[$row['article_id']]['uri']=$this->uri($row['category_id'],$row['article_id'],$row['article_html']);
					$array[$row['article_id']]['timestamp']=$row['article_time'];
					$array[$row['article_id']]['time']=$row['article_time'];
					$array[$row['article_id']]['comment_count']=$row['article_comment_count'];
					$array[$row['article_id']]['click_count']=$row['article_click_count'];
					$array[$row['article_id']]['category_id']=$row['category_id'];
					$array[$row['article_id']]['is_nofollow']=$row['article_is_nofollow'];
					$array[$row['article_id']]['is_new']=$_SERVER['REQUEST_TIME']-$row['article_time']<3600*24*3?true:false;
					$array[$row['article_id']]['category_name']=$this->db->value(DB_PREFIX."content_category","category_name","category_id=".$row['category_id']);
					$array[$row['article_id']]=array_merge($array[$row['article_id']],$this->get_fields($row['category_id'],$row['article_id']));//add 2012/11/7
				}
				$pager=pager(get_self(),'',$i,$page_size,$count,'array');
				$this->template->template_dir=ROOT.'/core/themes/'.$this->theme;
				$this->template->in('config',$this->config);
				$this->template->in("path",PATH);
				$this->template->in('header',$this->get_header());
				$this->template->in('footer',$this->get_footer());
				$this->template->in('category',$category);
				$this->template->in('article',$array);
				$this->template->in('article_click',$this->get_articles(array('limit'=>$this->config['click_size'],'category_id'=>$category_id,'orderby'=>'article_click_count')));
				$this->template->in('article_best',$this->get_articles(array('limit'=>$this->config['best_size'],'category_id'=>$category_id,'orderby'=>'article_click_count')));
				$this->template->in("article_category",$this->get_category($category_id));
				$this->template->in("here",$this->get_here($category_id));
				$this->template->in('pager',$pager);
				$this->set_interface();
				if(file_exists(ROOT.'/core/themes/'.$this->theme.'/content.category.list.template.'.$category['dir'].'.php')){
					$content=$this->template->fetch('content.category.list.template.'.$category['dir'].'.php');
				}else{
					$content=$this->template->fetch('content.list.php');
				}
				$name="list".$i.".html";
				$path=$this->uri($category_id,false,false);
				file_put_contents(ROOT.$path.$name,$content);
				if(empty($category['index_template'])&&$i==1&&!file_exists(ROOT.'/core/themes/'.$this->theme.'/content.category.index.template.'.$category['dir'].'.php')){
					$name="index.html";
					file_put_contents(ROOT.$path.$name,$content);
				}
			}
		}
		function html_article($article_id,$page_id=1){
			$article=$this->get_article_info($article_id);
			$category=$this->get_category_info($article['category_id']);
			$prev=$this->db->row("SELECT article_id as id,article_title as title,article_html as html FROM ".DB_PREFIX."content_article WHERE category_id=".$article['category_id']." AND article_id<".$article_id."  ORDER BY article_id DESC LIMIT 0,1");
			$next=$this->db->row("SELECT article_id as id,article_title as title,article_html as html FROM ".DB_PREFIX."content_article WHERE category_id=".$article['category_id']." AND article_id>".$article_id."  ORDER BY article_id ASC LIMIT 0,1");
			$this->template->template_dir=ROOT.'/core/themes/'.$this->theme;
			$this->template->in('config',$this->config);
			$this->template->in("path",PATH);
			$this->template->in('header',$this->get_header());
			$this->template->in('footer',$this->get_footer());
			$this->template->in("prev",$prev);
			$this->template->in("next",$next);
			$this->template->in("article_related_list",$this->get_related_article($article_id,$article['keywords']));
			$this->template->in('article_best',$this->get_articles(array(
				'limit'=>isset($this->config['click_size'])?$this->config['click_size']:10,
				'is_best'=>1,
				'orderby'=>'article_time'
			)));
			$this->template->in('article_click',$this->get_articles(array(
				'limit'=>isset($this->config['best_size'])?$this->config['best_size']:10,
				'orderby'=>'article_click_count'
			)));
			$this->template->in("here",$this->get_here($article['category_id']));
			$this->set_interface();
			$tag='#page#';
			if(strpos($article['content'],$tag)===false){
				$this->template->in("article",$article);
				if(file_exists(ROOT.'/core/themes/'.$this->theme.'/content.category.view.template.'.$category['dir'].'.php')){
					$content=$this->template->fetch('content.category.view.template.'.$category['dir'].'.php');
				}else{
					$content=$this->template->fetch('content.view.php');
				}
				$path=$this->uri($article['category_id'],$article_id,$article['html']);
				file_put_contents(ROOT.$path,$content);
			}else{
				$explode=explode($tag,$article['content']);
				$page_count=count($explode);
				$current=$page_id-1;
				$page_current=$page_id;
				$page_start		=$page_current-2;
				$page_end		=$page_current+2;
				if($page_current<=3){#如果当前页码小于3
					$page_start	=1;
					$page_end	=5;
				}
				if($page_current>$page_count-2){
					$page_start	=$page_count-4;
					$page_end	=$page_count;
				}
				if($page_start<1)$page_start=1;
				if($page_end>$page_count)$page_end=$page_count;
				$article['content']=$explode[$current];
				$current++;
				$path=$this->uri($article['category_id']);

				$html.="<div class=\"pager\"><ul>";
				if($page_current!=1){
					$html.="<li><a href='".$path.$article['html'].".html'>&laquo;</a></li>";
				}else{
					$html.="<li class='disabled'><a>&laquo;</a></li>";
				}
				for($i=$page_start;$i<=$page_end;$i++){
					if($i==$page_current){
						$html.="<li class='active'><a>".$i."</a></li>";
					}else{
						$html.="<li><a href='".$path.$article['html'].($i>1?"-".$i:"").".html'>".$i."</a></li>";
					}
				}
				if($page_current!=$page_count){
					$html.="<li><a href='".$path.$article['html']."-".$page_count.".html'>&raquo;</a></li>";
				}else{
					$html.="<li class='disabled'><a>&raquo;</a></li>";
				}
				$html.="</ul></div>";
				$article['content']=$article['content'].$html;

				$this->template->in("article",$article);

				if(file_exists(ROOT.'/core/themes/'.$this->theme.'/content.category.view.template.'.$category['dir'].'.php')){
					$content=$this->template->fetch('content.category.view.template.'.$category['dir'].'.php');
				}else{
					$content=$this->template->fetch('content.view.php');
				}
				$path=$this->uri($article['category_id']);
				file_put_contents(ROOT.$path.$article['html'].($current>1?"-".$current:"").".html",$content);
				$current++;
				if($current<=$page_count){
					$this->html_article($article_id,$current);
				}
			}
		}
		function get_related_article($article_id,$keywords){
			if(empty($keywords))return array();
			$tags=explode(",",$keywords);
			$sql_join="";
			if(!empty($tags)){
				$sql_condition=array();
				foreach($tags as $tag){
					$sql_condition[]=" article_keywords LIKE '%$tag%' ";
				}
				$sql_join.="AND ".implode(" AND ",$sql_condition);
			}
			$related_article_limit=isset($this->config['content_front_side_related_size'])?$this->config['content_front_side_related_size']:10;
			$sql="SELECT * FROM ".DB_PREFIX."content_article WHERE article_is_display=1 AND article_id!=$article_id $sql_join";
			$sql.=" ORDER BY article_id DESC LIMIT 0,".$related_article_limit;
			$array=array();
			$result=$this->db->result($sql);
			if($result){
				foreach($result as $row){
					$array[$row['article_id']]['id']=$row['article_id'];
					$array[$row['article_id']]['title']=$row['article_title'];
					$array[$row['article_id']]['description']=$row['article_description'];
					$array[$row['article_id']]['image']=$row['article_image'];
					$array[$row['article_id']]['click_count']=number_format($row['article_click_count']);
					$array[$row['article_id']]['comment_count']=number_format($row['article_comment_count']);
					$array[$row['article_id']]['category_name']=$this->db->value(DB_PREFIX."content_category","category_name","category_id=".$row['category_id']);
					$array[$row['article_id']]['category_id']=$row['category_id'];
					$array[$row['article_id']]['is_nofollow']=$row['article_is_nofollow'];
					$array[$row['article_id']]['uri']=$this->uri($row['category_id'],$row['article_id'],$row['article_html']);
					$array[$row['article_id']]['timestamp']=$row['article_time'];
				}
			}
			return $array;
		}
		#清理静态文章
		function html_clear(){
			$folder = @opendir('./');
			if($folder){
				while ($dir=readdir($folder)){
					if ($dir!="."&&$dir!=".."&&$dir!="index.htm"&&is_dir($dir)&&$dir!='core'&&$dir!='data'){
						rm_dir(ROOT.'/'.$dir);
					}
				}
				closedir($folder);
			}
		}
		function check_category(){
			$count=$this->db->count("SELECT  * FROM ".DB_PREFIX."content_category");
			if($count==0){
				redirect('?action=content&do=category_add');
			}
		}
		#获取一层级分类
		function get_category($parent_id=0){
			$array=array();
			$sql="SELECT category_id,category_name FROM ".DB_PREFIX."content_category WHERE parent_id=$parent_id  AND category_is_display=1";
			$sql.=" ORDER BY category_sort ASC";
			$result=$this->db->result($sql);
			if($result){
				foreach($result as $row){
					$array[$row['category_id']]['id']=$row['category_id'];
					$array[$row['category_id']]['name']=$row['category_name'];
					$array[$row['category_id']]['uri']=$this->uri($row['category_id']);
				}
			}
			return $array;
		}
		/*
		 * 获取频道下的栏目
		 *
		 * @param	integer	$parent_id	父ID
		 * @return	array
		 */
		function category_array($parent_id=0){
			if(!isset($array)){
				$array=array();
			}
			$result=$this->db->result("SELECT * FROM ".DB_PREFIX."content_category WHERE parent_id=$parent_id ORDER BY category_sort asc");
			if($result){
				foreach($result as $row){
					$array[$row['category_id']]['id']=$row['category_id'];
					$array[$row['category_id']]['name']=$row['category_name'];
					$array[$row['category_id']]['deep']=$row['category_deep'];
					$array[$row['category_id']]['uri']=$this->category_parent_path($row['category_id']);
					if($this->category_have($row['category_id'])){
						$array[$row['category_id']]['children']=$this->category_array($row['category_id']);
					}
				}
			}
			return $array;
		}
		/*
		 * 获取频道下的栏目（HTML输出）
		 *
		 * @param	integer	$parent_id	父ID
		 * @return	string
		 */
		function category_html($parent_id=0){
			if(!isset($array)){
				$html='';
			}
			$result=$this->db->result("SELECT * FROM ".DB_PREFIX."content_category WHERE parent_id=$parent_id ORDER BY category_sort ASC");
			if($result){
				foreach($result as $row){
					$check_have_child=$this->category_have($row['category_id']);
					$html.='<tr>';
					#if(!$check_have_child){
						$html.='<td style="height:30px;" align="center" ><input type="checkbox" class="checkbox" name="category_id[]" value="'.$row['category_id'].'" /></td>';
					#}else{
					#	$html.='<td style="height:30px;" align="center" >'.$row['category_id'].'</td>';
					#}
					$html.='<td>'.str_repeat('&nbsp;',($row['category_deep']*4)).'';
					if(!$check_have_child&&$row['parent_id']>0){
						$html.='&raquo;';
					}
					$html.='&nbsp;<a href="?action=content&do=article&category_id='.$row['category_id'].'"';

						$html.='style="font-weight:bold;color:#B5D53B"';

					$html.='>'.$row['category_name'].'</a>';
 
					$html.='</td>';
					if(!$check_have_child){
						$html.='<td>'.$this->category_parent_path($row['category_id']).'</td>';
					}else{
						$html.='<td>-</td>';
					}
					$html.='<td>';
					$html.='<a class="button" href="?action=content&do=category_edit&category_id='.$row['category_id'].'">编辑</a>';
					if(!$check_have_child){
						$html.="&nbsp;<a class='button' href='?action=content&do=category_delete&category_id=".$row['category_id']."' onclick=\"return confirm('确定要删除吗？')\">删除</a>";
					}
					$html.='</td>';
					$html.='</tr>';
					if($check_have_child){
						$html.=$this->category_html($row['category_id']);
					}
				}
			}
			return $html;
		}
		/*
		 * 获取栏目（OPTION输出）
		 *
		 * @param	integer	$parent_id	父ID
		 * @param	integer	$select_id	选中ID
		 * @return	string
		 */
		function category_option($parent_id=0,$select_id=0,$safe=false){
			if(!isset($array)){
				$array='';
			}
			$sql="SELECT * FROM ".DB_PREFIX."content_category WHERE parent_id=$parent_id";
			if($safe)$sql.=" AND category_is_display=1";
			$sql.=" ORDER BY category_sort asc";
			$result=$this->db->result($sql);
			if($result){
				foreach($result as $row){
					$array.="<option value=\"".$row['category_id']."\" ".($select_id==$row['category_id']?'selected':'').">".str_repeat('-',($row['category_deep']*4)).$row['category_name']."</option>";
					if($this->category_have($row['category_id'])){
						$array.=$this->category_option($row['category_id'],$select_id,$safe);
					}
				}
			}
			return $array;
		}
		/*
		 * 生成栏目文件夹
		 *
		 * @param	integer	$parent_id	父ID
		 * @param	integer	$select_id	选中ID
		 * @return	string
		 */
		function category_path($parent_id=0,$parent_path=''){
			$parent_path=$parent_path==''?'':$parent_path.'/';
			$result=$this->db->result("SELECT * FROM ".DB_PREFIX."content_category WHERE parent_id=$parent_id ORDER BY category_sort asc");
			if($result){
				foreach($result as $row){
					mk_dir($parent_path.$row['category_dir'],0777,false);
					if($this->category_have($row['category_id'])){
						$this->category_path($row['category_id'],$parent_path.$row['category_dir']);
					}
				}
			}
		}
		/*
		 * 获取频道下的栏目ID
		 *
		 * @param	integer	$parent_id	父ID
		 * @param	integer	$channel_id	频道ID
		 * @return	array
		 */
		function category_id_array($parent_id=0){
			if(!isset($array)){
				$array=array();
			}
			$result=$this->db->result("SELECT category_id FROM ".DB_PREFIX."content_category WHERE parent_id=$parent_id ORDER BY category_sort asc");
			if($result){
				foreach($result as $row){
					$array[]=$row['category_id'];
					if($this->category_have($row['category_id'])){
						$array[]=$this->category_id_array($row['category_id']);
					}
				}
			}
			return $array;
		}
		/*
		 * 检测频道栏目下是否有子栏目
		 *
		 * @param	integer	$category_id	栏目ID
		 * @return	array
		 */
		function category_have($category_id){
			$count=$this->db->count("SELECT * FROM ".DB_PREFIX."content_category WHERE parent_id=$category_id");
			if($count>0){
				return true;
			}else{
				return false;
			}
		}
		/*
		 * 获取当前节点的找出同族父级树
		 *
		 * @param	integer	$category_id	栏目ID
		 * @return	array
		 */
		function category_parent($category_id){
			if(!isset($path)){
				$path='';
			}
			if($category_id>0){
				$row=$this->db->row("SELECT category_dir,parent_id FROM ".DB_PREFIX."content_category WHERE category_id=$category_id LIMIT 0,1");
				$path.=$row['category_dir']."/";
				$path.=$this->category_parent($row['parent_id']);
			}
			return $path;
		}
		/*
		 * 直接显示该栏目的父路径
		 *
		 * @param	integer	$category_id	栏目ID
		 * @return	array
		 */
		function category_parent_path($category_id){
			$s=explode("/",$this->category_parent($category_id));
			$s=array_reverse($s);
			$path=array();
			foreach($s as $v){
				if(!empty($v)){
					$path[]=$v;
				}
			}
			return implode('/',$path);
		}


		function get_category_info($category_id){
			$row=$this->db->row("SELECT * FROM ".DB_PREFIX."content_category WHERE category_id=$category_id LIMIT 0,1");
			$array=array();
			$array['id']=$row['category_id'];
			$array['name']=$row['category_name'];
			$array['deep']=$row['category_name'];
			$array['keywords']=$row['category_keywords'];
			$array['description']=$row['category_description'];
			$array['dir']=$row['category_dir'];
			$array['list_limit']=$row['category_list_limit'];
			$array['image_width']=$row['category_image_width'];
			$array['image_height']=$row['category_image_height'];
			$array['is_display']=$row['category_is_display'];
			return $array;
		}

		function get_article_info($id){
			$tag=array();
			$result=$this->db->result("SELECT * FROM ".DB_PREFIX."tag ORDER BY RAND() LIMIT 5");
			foreach($result as $row){
				$tag[$row['tag_name']]=$row['tag_link'];
			}
			$row=$this->db->row("SELECT * FROM ".DB_PREFIX."content_article WHERE article_id=$id");
			$array=array();
			$array['id']			=$row['article_id'];
			$array['title']			=$row['article_title'];
			$array['content']		=tag_link(str_replace('#more#','',$row['article_content']),$tag);
			if(!empty($row['page_file'])){
				$array['file']			=explode(",",$row['article_file']);
			}else{
				$array['file']=array();
			}
			$array['author']		=htmlspecialchars($row['article_author']);
			$array['keywords']		=htmlspecialchars($row['article_keywords']);
			$array['tags']		=!empty($row['article_keywords'])?explode(",",$row['article_keywords']):array();
			$array['description']	=htmlspecialchars($row['article_description']);
			$array['image']			=$row['article_image'];
			$array['time']			=$row['article_time'];
			$array['html']			=htmlspecialchars($row['article_html']);
			$array['click_count']	=number_format($row['article_click_count']);
			$array['comment_count']	=number_format($row['article_comment_count']);
			$array['is_best']		=$row['article_is_best'];
			$array['is_comment']	=$row['article_is_comment'];
			$array['is_nofollow']	=$row['article_is_nofollow'];
			$array['is_display']	=$row['article_is_display'];
			$array['category_id']=$row['category_id'];
			$array['category_name']=$this->db->value(DB_PREFIX."content_category","category_name","category_id=".$row['category_id']);
			$array['category_path']=$this->uri($row['category_id']);
			$array=array_merge($array,$this->get_fields($row['category_id'],$row['article_id']));
			return $array;
		}
		function get_fields($category_id,$article_id){
			$result=$this->db->result("SELECT a.field_name,a.field_text,b.data_value FROM ".DB_PREFIX."content_field AS a LEFT JOIN ".DB_PREFIX."content_field_data AS b ON a.category_id='$category_id' AND a.field_status=1 AND a.field_id=b.field_id AND b.article_id=$article_id");
			$array=array();
			foreach($result as $row){
				$array[$row['field_name']]=array(
					'text'=>$row['field_text'],
					'value'=>$row['data_value']
				);
			}
			return $array;
		}
		function get_articles($options){
			$category_id=isset($options['category_id'])?$options['category_id']:0;
			$is_best=isset($options['is_best'])?$options['is_best']:0;
			$orderby=isset($options['orderby'])?$options['orderby']:'article_id';
			$sort=isset($options['sort'])?$options['sort']:'desc';
			$limit=isset($options['limit'])?$options['limit']:0;
			$author=isset($options['author'])?$options['author']:'';
			$image=isset($options['image'])?true:false;
			$sql="SELECT * FROM ".DB_PREFIX."content_article WHERE article_is_display=1 ";
			if($category_id>0){
				if($this->category_have($category_id)){
					$sql.=" AND ".create_sql_in(array_multi2single($this->category_id_array($category_id)),'category_id');
				}else{
					$sql.=" AND category_id='$category_id'";
				}
			}
			if($author!=''){
				$sql.=" AND article_author='$author' ";
			}
			if($image){
				$sql.=" AND article_image!='' ";
			}
			if($is_best>0){
				$sql.=" AND article_is_best=1 ";
			}
			$sql.=" ORDER BY $orderby $sort ";
			if($limit>0){
				$sql.="LIMIT 0,$limit";
			}
			$array=array();
			$result=$this->db->result($sql);
			if($result){
				foreach($result as $row){
					$array[$row['article_id']]['id']=$row['article_id'];
					$array[$row['article_id']]['title']=$row['article_title'];
					$array[$row['article_id']]['description']=$row['article_description'];
					$array[$row['article_id']]['content']=$this->get_short_content($row['article_content']);
					$array[$row['article_id']]['image']=$row['article_image'];
					$array[$row['article_id']]['author']=$row['article_author'];
					$array[$row['article_id']]['click_count']=number_format($row['article_click_count']);
					$array[$row['article_id']]['comment_count']=number_format($row['article_comment_count']);
					$array[$row['article_id']]['category_name']=$this->db->value(DB_PREFIX."content_category","category_name","category_id=".$row['category_id']);
					$array[$row['article_id']]['category_path']=$this->uri($row['category_id']);
					$array[$row['article_id']]['category_id']=$row['category_id'];
					$array[$row['article_id']]['is_nofollow']=$row['article_is_nofollow'];
					$array[$row['article_id']]['uri']=$this->uri($row['category_id'],$row['article_id'],$row['article_html']);
					$array[$row['article_id']]['time']=date("Y.m.d",$row['article_time']);
					$array[$row['article_id']]['timestamp']=$row['article_time'];
					$array[$row['article_id']]['is_best']=$row['article_is_best'];
					$array[$row['article_id']]['is_new']=$_SERVER['REQUEST_TIME']-$row['article_time']<3600*24*3?true:false;
					$array[$row['article_id']]=array_merge($array[$row['article_id']],$this->get_fields($row['category_id'],$row['article_id']));
				}
			}
			return $array;
		}
		function get_here($category_id){
			$row=$this->db->row("SELECT * FROM ".DB_PREFIX."content_category WHERE category_id=$category_id LIMIT 0,1");
			$here=array($this->uri($row['category_id']),$row['category_name']);
			if($row['parent_id']>0){
				$tmp=$this->get_here_parent($row['parent_id']);
				$tmp=rtrim($tmp, '|');
				$tmp=explode("|",$tmp);
				$tmp=array_reverse($tmp);
				array_push($tmp,implode(",",$here));
				$new=array();
				foreach($tmp as $v){
					$vv=explode(",",$v);
					$new[]=array($vv[0],$vv[1]);
				}
				$tmp=$new;
				unset($new);
			}else{
				$tmp=array($here);
			}
			return $tmp;
		}
		function get_here_parent($parent_id){//获取上级栏目
			if(!isset($tmp))$tmp='';
			if($parent_id>0){
				$row=$this->db->row("SELECT * FROM ".DB_PREFIX."content_category WHERE category_id=$parent_id LIMIT 0,1");
				$uri=$this->uri($row['category_id']);
				$uri=str_replace(",","",$uri);
				$row['category_name']=str_replace(",","",$row['category_name']);
				$tmp.=$uri.",".$row['category_name']."|";
				if($row['parent_id']>0){
					$tmp.=$this->get_here_parent($row['parent_id']);
				}
			}
			return $tmp;
		}
		function get_short_content($content){
			$tag='#more#';
			$tmp='';
			if(strpos($content,$tag)!==false){
				$explode=explode($tag,$content);
				$tmp=$explode[0];
			}else{
				$tmp=$content;
			}
			$tmp=str_replace('#page#','',$tmp);

			return $tmp;
		}
		function uri($category_id,$article_id=0,$article_html=false){
			if($this->config['content_mode']==0){
				if($article_id>0){
					$uri=PATH."content.php?id=".$article_id;
				}else{
					$uri=PATH."content.php?cid=".$category_id;
				}
			}elseif($this->config['content_mode']==1){
				$uri=PATH.$this->category_parent_path($category_id).'/';
				if($article_html!=false)$uri.=$article_html.'.html';
				
			}elseif($this->config['content_mode']==2){
				if($article_id>0){
					$uri=PATH."content/".$article_id."/";
				}else{
					$uri=PATH."category/".$category_id."/";
				}
			}
			return $uri;
		}
	}
}