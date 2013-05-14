<?php exit?><?php
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
require_once ROOT.'/core/yckit.base.php';
class content extends base{
	private $content;
	private $cache_id;
	function __construct(){
		$this->compatible();
		$this->cache_id=isset($_GET['id'])?$_GET['id']:$_GET['cid'];
		$this->template->cache=true;
		if(!$this->template->is_cached(isset($_GET['id'])?'article':'category',$this->cache_id)){
			$this->template->template_dir='core/themes/'.$this->theme;
			$this->template->in('config',$this->config);
			$this->template->in('path',PATH);
			$this->template->in('header',$this->get_header());
			$this->template->in('footer',$this->get_footer());
			$this->set_interface();
			require_once ROOT.'/core/modules/content/class.content.php';
			$this->content=new content_class();
			if(isset($_GET['id'])){
				$this->article();
			}elseif(isset($_GET['cid'])){
				$this->category();
			}
		}
	}
	function category(){
		$category_id=intval($_GET['cid']);
		$category=$this->content->get_category_info($category_id);
		if($this->content->category_have($category_id)){
			$create_array=array_multi2single($this->content->category_id_array($category_id));
			array_push($create_array,$category_id);//add by 2012/11/01
			$in=create_sql_in($create_array,'category_id');
		}else{
			$in="category_id='$category_id'";
		}
		$sql="SELECT * FROM ".DB_PREFIX."content_article WHERE article_is_display=1 AND ".$in." ORDER BY article_id DESC";
		$page_size=$category['list_limit']>0?$category['list_limit']:10;
		$page_current=isset($_GET['page'])&&is_numeric($_GET['page'])?intval($_GET['page']):1;
		$count=$this->db->count($sql);
 
		$array=array();
		if($count>0){
			$result=$this->db->result($sql." LIMIT ".(($page_current-1)*$page_size).",".$page_size);
			foreach($result as $row){
				$array[$row['article_id']]['id']=$row['article_id'];
				$array[$row['article_id']]['title']=$row['article_title'];
				$array[$row['article_id']]['description']=$row['article_description'];
				$array[$row['article_id']]['author']=$row['article_author'];
				$array[$row['article_id']]['image']=$row['article_image'];
				$array[$row['article_id']]['content']=$this->content->get_short_content($row['article_content']);
				$array[$row['article_id']]['uri']=$this->content->uri($row['category_id'],$row['article_id'],$row['article_html']);
				$array[$row['article_id']]['timestamp']=$row['article_time'];
				$array[$row['article_id']]['time']=$row['article_time'];
				$array[$row['article_id']]['comment_count']=$row['article_comment_count'];
				$array[$row['article_id']]['click_count']=$row['article_click_count'];
				$array[$row['article_id']]['category_id']=$row['category_id'];
				$array[$row['article_id']]['is_nofollow']=$row['article_is_nofollow'];
				$array[$row['article_id']]['is_new']=$_SERVER['REQUEST_TIME']-$row['article_time']<3600*24*3?true:false;
				$array[$row['article_id']]['category_name']=$this->db->value(DB_PREFIX."content_category","category_name","category_id=".$row['category_id']);
				$array[$row['article_id']]=array_merge($array[$row['article_id']],$this->content->get_fields($row['category_id'],$row['article_id']));//add 2012/11/7
			}
			$pager=pager(get_self(),'',$page_current,$page_size,$count,'array');
			$this->template->in('category',$category);
			$this->template->in('article',$array);
			$this->template->in('article_click',$this->content->get_articles(array('limit'=>$this->config['click_size'],'category_id'=>$category_id,'orderby'=>'article_click_count')));
			$this->template->in('article_best',$this->content->get_articles(array('limit'=>$this->config['best_size'],'is_best'=>1,'category_id'=>$category_id,'orderby'=>'article_id')));
			$this->template->in("article_category",$this->content->get_category($category_id));
			$this->template->in("here",$this->content->get_here($category_id));
			$this->template->in('pager',$pager);
		}
		if(file_exists(ROOT.'/core/themes/'.$this->theme.'/content.category.list.template.'.$category['dir'].'.php')){
			$this->template->out('content.category.list.template.'.$category['dir'].'.php',$this->cache_id);
		}else{
			$this->template->out('content.list.php',$this->cache_id);
		}
	}
	function article(){
		$article_id=intval($_GET['id']);
		$page_id=isset($_GET['page'])?intval($_GET['page']):1;
		$article=$this->content->get_article_info($article_id);

		$category=$this->content->get_category_info($article['category_id']);
		$prev=$this->db->row("SELECT article_id as id,article_title as title,article_html as html FROM ".DB_PREFIX."content_article WHERE category_id=".$article['category_id']." AND article_id<".$article_id."  ORDER BY article_id DESC LIMIT 0,1");
		$next=$this->db->row("SELECT article_id as id,article_title as title,article_html as html FROM ".DB_PREFIX."content_article WHERE category_id=".$article['category_id']." AND article_id>".$article_id."  ORDER BY article_id ASC LIMIT 0,1");
 
		$this->template->in("prev",$prev);
		$this->template->in("next",$next);
		$this->template->in("article_related_list",$this->content->get_related_article($article_id,$article['keywords']));
		$this->template->in('article_best',$this->content->get_articles(array(
			'limit'=>isset($this->config['click_size'])?$this->config['click_size']:10,
			'is_best'=>1,
			'orderby'=>'article_time'
		)));
		$this->template->in('article_click',$this->content->get_articles(array(
			'limit'=>isset($this->config['best_size'])?$this->config['best_size']:10,
			'orderby'=>'article_click_count'
		)));
		$this->template->in("here",$this->content->get_here($article['category_id']));
 
		$tag='#page#';
		if(strpos($article['content'],$tag)!==false){			
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
	 

			$html="<div class=\"pager\"><ul>";
			if($page_current!=1){
				if($this->config['content_mode']==0){
					$html.="<li><a href='".PATH."content.php?id=$article_id'>&laquo;</a></li>";	
				}else{
					$html.="<li><a href='".PATH."content/$article_id/'>&laquo;</a></li>";
				}
				
			}else{
				$html.="<li class='disabled'><a>&laquo;</a></li>";
			}
			for($i=$page_start;$i<=$page_end;$i++){
				if($i==$page_current){
					$html.="<li class='active'><a>$i</a></li>";
				}else{
					if($this->config['content_mode']==0){
						$html.="<li><a href='".PATH."content.php?id=$article_id&page=$i'>$i</a></li>";	
					}else{
						$html.="<li><a href='".PATH."content/$article_id/page/$i/'>$i</a></li>";
					}
				}
			}
			if($page_current!=$page_count){
				if($this->config['content_mode']==0){
					$html.="<li><a href='".PATH."content.php?id=$article_id&page=$page_count'>&laquo;</a></li>";	
				}else{
					$html.="<li><a href='".PATH."content/$article_id/page/$page_count/'>&laquo;</a></li>";
				}
			}else{
				$html.="<li class='disabled'><a>&raquo;</a></li>";
			}
			$html.="</ul></div>";
			$article['content']=$article['content'].$html;
		}
		$this->template->in("article",$article);
		if(file_exists(ROOT.'/core/themes/'.$this->theme.'/content.category.view.template.'.$category['dir'].'.php')){
			$this->template->out('content.category.view.template.'.$category['dir'].'.php',$this->cache_id);
		}else{
			$this->template->out('content.view.php',$this->cache_id);
		}
	}
}
new content();