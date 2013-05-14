<?php
/*
 * YCKIT 前台接口
 * ============================================================================
 * 版权所有 2012 YCKIT.COM 并保留所有权利。
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 作者：野草
 * 更新：2012年03月28日
 */
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
require_once ROOT.'/core/yckit.base.php';
class front extends base{
	public function __construct(){
		$this->compatible();
		$this->template->template_dir='core/themes/'.$this->theme;
		$this->template->in('config',$this->config);
		$this->template->in('path',PATH);
		$this->set_interface();
 
		if($this->action=='theme'){
			@require_once ROOT.'/core/themes/'.$this->theme.'/front.php';
		}elseif($this->action!=''){
			require_once ROOT.'/core/modules/'.$this->action.'/front.index.php';
		}else{
			http_301();
		}
	}
}
new front();