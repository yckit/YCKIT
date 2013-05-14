<?php
if(!defined('ROOT'))exit('Access denied!');
class page_class extends base{
	function __construct(){
		$this->init();
		$this->template->template_dir='core/themes/'.read_cache('default_theme');
		$this->template->in('config',$this->config);
		$this->template->in("path",PATH);
		$this->set_interface();
	}
	#生成静态页面
	function html_page($page_id){
		$page=$this->get_page_info($page_id);
		$this->template->in('header',$this->get_header());
		$this->template->in('footer',$this->get_footer());
		$this->template->in("nav",$this->get_nav());
		$this->template->in("page",$page);
		$this->template->in("page_hash",md5(PATH.$page['html'].".html"));
		if(file_exists(ROOT.'/core/themes/'.$this->theme.'/page.template.'.$page['html'].'.php')){
			$content=$this->template->fetch('page.template.'.$page['html'].'.php');			
		}else{
			$content=$this->template->fetch('page.template.php');
		}
		file_put_contents(ROOT.'/'.$page['html'].'.html',$content);
	}
	#获取页面信息
	function get_page_info($id){
		$row=$this->db->row("SELECT * FROM ".DB_PREFIX."page WHERE page_id=$id LIMIT 0,1");
		$array=array();
		$array['id']=$row['page_id'];
		$array['title']=$row['page_title'];
		$array['content']=$row['page_content'];
		if(!empty($row['page_file'])){
			$array['file']=explode(",",$row['page_file']);
		}else{
			$array['file']=array();
		}
		$array['keywords']=htmlspecialchars($row['page_keywords']);
		$array['description']=htmlspecialchars($row['page_description']);
		$array['html']=$row['page_html'];
		$array['is_comment']=$row['page_is_comment'];
		$array['is_display']=$row['page_is_display'];
		return $array;
	}
	function get_nav(){
		$result=$this->db->result("SELECT * FROM ".DB_PREFIX."page WHERE page_is_display=1 ORDER BY page_sort ASC");
		$array=array();
		if($result){
			foreach($result as $row){
				$array[$row['page_id']]['id']=$row['page_id'];
				$array[$row['page_id']]['title']=$row['page_title'];
				$array[$row['page_id']]['html']=$row['page_html'];
			}
		}
		return $array;
	}
}