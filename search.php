<?php
/*
 * YCKIT 前台搜索
 * ============================================================================
 * 版权所有 2012 YCKIT.COM 并保留所有权利。
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 作者：野草
 * 更新：2012年05月02日
 */
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
require_once ROOT.'/core/yckit.base.php';
class search extends base{
	public $keyword='';
	public $action='';
	public $tag='';
	function __construct(){
		$this->compatible();
		if(empty($_GET['e'])){
			$string=array_merge($_GET,$_POST);
			$string=$this->search_encode($string);
			http_301("./search.php?e=$string");
		}else{
			$request=array();
			$request = base64_decode(trim($_GET['e']));
			if ($request !== false){
				$request=unserialize($request);
			}else{
				http_301();
			}
			$_REQUEST=$request;
			if(isset($_REQUEST['keyword'])){
				$_REQUEST['keyword']=$this->replace_chars($_REQUEST['keyword']);
			}
			if(isset($_REQUEST['tag'])){
				$_REQUEST['tag']=$this->replace_chars($_REQUEST['tag']);
			}
			$this->action=isset($_REQUEST['action'])?trim($_REQUEST['action']):'';
			if (empty($_REQUEST['keyword'])){
				if (empty($_REQUEST['tag'])){
					http_301();
				}else{
					$this->tag=trim($_REQUEST['tag']);
				}
			}else{
				$this->keyword=trim($_REQUEST['keyword']);
			}
		}
		$this->template->template_dir='core/themes/'.$this->theme;
		$this->template->in('config',$this->config);
		$this->template->in('path',PATH);
		$this->template->in('keyword',$this->keyword);
		$this->template->in('header',$this->get_header());
		$this->template->in('footer',$this->get_footer());
		$this->set_interface();
		if($this->action!=''){
			require_once ROOT.'/core/modules/'.$this->action.'/interface.search.php';
		}else{
			http_301();
		}
	}
	function search_encode($string){
		$string=str_replace('+','%2b',base64_encode(serialize($string)));//替换+号
		return $string;
	}
	function replace_chars($string){
		$chars=array(
			"'"=>"",
			"\""=>"",
			"%"=>"",
			"and"=>"",
			"select"=>"",
			"@"=>"",
			"^"=>"",
			"&"=>"",
			"+"=>"",
			","=>"",
			"?"=>"",
			"*"=>"",
			"/"=>"",
			"expression"=>"",
			"<"=>"&lt;",
			">"=>"&gt;"
		);
		return str_replace(array_keys($chars),array_values($chars),$string);
	}
}
new search();