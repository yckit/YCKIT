<?php
/*
 * YCKIT 基类
 * ============================================================================
 * 版权所有 2012 YCKIT.COM 并保留所有权利。
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 作者：野草
 * 更新：2013年05月10日
 */
if(!defined('ROOT'))exit('Access denied!');
require_once(ROOT.'/core/yckit.version.php');
require_once(ROOT.'/core/yckit.config.php');
require_once(ROOT.'/core/yckit.function.php');
require_once(ROOT.'/core/yckit.db.php');
require_once(ROOT.'/core/yckit.template.php');
require_once(ROOT.'/core/yckit.init.php');
abstract class base{
	public $template;
	public $db;
	public $config;
	public $action;
	public $do;
	public $theme;
	public function compatible(){
		$this->init();
		$this->init_hook();
		session_start();
		header("content-type:text/html;charset=utf-8");
	}
	public function init(){
		$this->db=new db(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
		$this->template=new template();
		$this->template->db=&$this->db;
		$this->config=$this->get_config();
		$this->template->config=&$this->config;
		$this->action=isset($_GET['action'])?trim($_GET['action']):'';
		$this->do=isset($_GET['do'])?trim($_GET['do']):'';
		$this->theme=$this->get_theme();
	}
	function get_config(){
		$array=array();
		$array['version']=$GLOBALS['version'];
		$result=$this->db->result("SELECT config_value FROM ".DB_PREFIX."config WHERE config_type!='modules' AND config_type!='plugins' AND config_type!='theme'");
		if($result){
			foreach($result as $row){
				if(!empty($row['config_value'])){
					$array=array_merge($array,unserialize(base64_decode($row['config_value'])));
				}
			}
		}
		$modules=$this->get_modules();
		if($modules){
			foreach($modules as $dir){
				$config_base=ROOT.'/core/modules/'.$dir.'/config.base.php';
				if(file_exists($config_base)){
					$config_base=include($config_base);
					$array=array_merge($array,$config_base);
				}
			}
		}
		return $array;
	}

	public function set_interface(){
		$modules=$this->get_modules();
		if($modules){
			foreach($modules as $dir){
				$file=ROOT.'/core/modules/'.$dir.'/interface.php';
				if(file_exists($file))require_once $file;
				unset($file);
			}
			$file=ROOT.'/core/themes/'.$this->theme.'/interface.php';
			if(file_exists($file))require_once $file;
		}
		unset($modules);
	}
	public function init_hook(){
		$plugins=$this->get_plugins();
		if($plugins){
			foreach($plugins as $dir){
				$hook=ROOT.'/core/plugins/'.$dir.'/template.php';
				if (is_file($hook))include $hook;
			}
		}
		$modules=$this->get_modules();
		if($modules){
			foreach($modules as $dir){
				$hook=ROOT.'/core/modules/'.$dir.'/template.php';
				if (is_file($hook))include $hook;
			}
		}
		unset($modules);
	}
	public function add_hook($name,$content){
		if(!empty($GLOBALS['ui'][$name])){
			$GLOBALS['ui'][$name].=$content;
		}else{
			$GLOBALS['ui'][$name]=$content;
		}
	}
	public function set_hook($name){
		$plugins=$this->get_plugins();
		if($plugins){
			foreach($plugins as $dir){
				$hook=ROOT.'/core/plugins/'.$dir.'/hook.'.$name.'.php';
				if (is_file($hook))include $hook;
			}
		}
	}

	public function get_header(){
		$this->template->template_dir=ROOT.'/core/themes/'.$this->theme;
		$this->set_interface();
		$modules=$this->get_modules();
		if($modules){
			foreach($modules as $dir){
				if($dir!='global'){
					$file=ROOT.'/core/modules/'.$dir.'/interface.header.php';
					if(file_exists($file)){
						require_once $file;
					}
				}
			}
		}
		unset($modules);
		$array=array();
		$result=$this->db->result("SELECT * FROM ".DB_PREFIX."menu WHERE menu_status=1 AND parent_id=0 ORDER BY menu_sort ASC");
		if($result){
			$URI=substr(strrchr($_SERVER['REQUEST_URI'],'/'),1);
			$n=1;
			foreach($result as $row){
				$array[$row['menu_id']]['id']=$row['menu_id'];
				$array[$row['menu_id']]['name']=$row['menu_name'];
				$array[$row['menu_id']]['description']=$row['menu_description'];
				$array[$row['menu_id']]['target']=$row['menu_target'];
				$array[$row['menu_id']]['link']=$row['menu_link'];
				$array[$row['menu_id']]['sort']=$row['menu_sort'];
				$children=array();
				$children_result=$this->db->result("SELECT * FROM ".DB_PREFIX."menu WHERE menu_status=1 AND parent_id=".$row['menu_id']." ORDER BY menu_id ASC");
				if($children_result){
					foreach($children_result as $child){
						$children[$child['menu_id']]['id']=$child['menu_id'];
						$children[$child['menu_id']]['name']=$child['menu_name'];
						$children[$child['menu_id']]['description']=$child['menu_description'];
						$children[$child['menu_id']]['link']=$child['menu_link'];
						$children[$child['menu_id']]['target']=$child['menu_target'];
					}
				}
				$array[$row['menu_id']]['children']=$children;
				unset($children_result);
				if(!empty($URI)){
					if($URI==$row['menu_link']){
						$array[$row['menu_id']]['active']=true;
					}
				}
				$n++;
			}
		}
		unset($result);
		$this->template->in('menu',$array);
		$this->template->in('config',$this->config);
		$content=$this->template->fetch('header.php');
		return $content;
	}
	public function get_footer(){
		$this->template->template_dir=ROOT.'/core/themes/'.$this->theme;
		$this->set_interface();
		$modules=$this->get_modules();
		if($modules){
			foreach($modules as $dir){
				$file=ROOT.'/core/modules/'.$dir.'/interface.footer.php';
				if(file_exists($file)){
					require_once $file;
				}
			}
		}
		unset($modules);
		$this->template->in('config',$this->config);
		$content=$this->template->fetch('footer.php');
		return $content;
	}
	#获取模块名称
	public function get_theme(){
		$value=array();
		if(false===($value=read_cache('theme'))){
			$value=$this->db->value(DB_PREFIX."config","config_value","config_type='theme'");
			write_cache('theme',$value);
		}
		return $value;
	}
	#获取模块名称
	public function get_modules(){
		$array=array();
		if(!read_cache('modules')){
			$value=$this->db->value(DB_PREFIX."config","config_value","config_type='modules'");
			if(!empty($value)){
				$array=explode("|",$value);
			}else{
				$array=array();
			}
			write_cache('modules',$array);	
		}else{
			$array=read_cache('modules');
		}
		return $array;
	}
	#获取模块名称
	public function get_plugins(){
		$array=array();
		if(!read_cache('plugins')){
			$value=$this->db->value(DB_PREFIX."config","config_value","config_type='plugins'");
			if(!empty($value)){
				$array=explode("|",$value);
			}else{
				$array=array();
			}
			write_cache('plugins',$array);	
		}else{
			$array=read_cache('plugins');
		}
		return $array;
	}
	public function check_module($name){
		return in_array($name,$this->get_modules());
	}

	public function check_plugin($name){
		return in_array($name,$this->get_plugins());
	}
	#生成首页
	public function html_index(){
		$this->template->template_dir=ROOT.'/core/themes/'.$this->theme;
		$this->template->in("path",PATH);
		$this->template->in('header',$this->get_header());
		$this->template->in('footer',$this->get_footer());
		$this->set_interface();
		$this->set_hook('html.index');
		$modules=$this->get_modules();
		if($modules){
			foreach($modules as $dir){
				if($dir!='global'){
					$file=ROOT.'/core/modules/'.$dir.'/interface.index.php';
					if(file_exists($file)){
						require_once $file;
					}
				}
			}
		}
		unset($modules);
		$file=ROOT.'/core/themes/'.$this->theme.'/interface.index.php';
		if(file_exists($file))require_once $file;
		$content=$this->template->fetch('index.php');
		file_put_contents(ROOT.'/index.html',$content);
		unset($content);
	}

}