<?php
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
require_once ROOT.'/core/yckit.base.php';
class index extends base{
	private $cache_id;
	function __construct(){
		check_install();
		$this->compatible();
		$this->cache_id='index';
		$this->template->cache=true;
		if(!$this->template->is_cached('index',$this->cache_id)){
			$this->template->template_dir='core/themes/'.$this->theme;
			$this->template->in('config',$this->config);
			$this->template->in('path',PATH);
			$this->template->in('header',$this->get_header());
			$this->template->in('footer',$this->get_footer());
			$this->set_interface();
			$modules=$this->get_modules();
			if($modules){
				foreach($modules as $dir){
					if($dir!='global'){
						$file=ROOT.'/core/modules/'.$dir.'/interface.index.php';
						if(file_exists($file))require_once $file;
					}
				}
			}
			$file=ROOT.'/core/themes/'.$this->theme.'/interface.index.php';
			if(file_exists($file))require_once $file;
		}
		$this->template->out("index.php",$this->cache_id);
	}
}
new index();