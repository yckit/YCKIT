<?php
/*
 * YCKIT 后台入口
 * ============================================================================
 * 版权所有 2013 YCKIT.COM 并保留所有权利。
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 作者：野草
 * 更新：2013年05月10日
 */
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
require_once ROOT.'/core/yckit.base.php';
class admin extends base{
	public function __construct(){
		$this->compatible();
		$this->template->template_dir=ROOT.'/core/themes/admin';
		$this->template->in('config',$this->config);
		$this->template->in('ext',EXT);
		if(isset($_SESSION['admin_id'])){
			$this->template->in('leftmenu',$this->get_modules_menu());
		}
		$this->start();
	}
	private function start(){
		if($this->action==''){
			if($this->do=='logout'){#退出
				unset($_SESSION['admin_id'],$_SESSION['admin_name'],$_SESSION['admin_access']);
				redirect(get_self());
			}elseif($this->do=='start'){#欢迎页
				$this->check_login();
				$server=array();
				$server['time']=date("Y-m-d h:i:s",$_SERVER['REQUEST_TIME']);
				$server['port']=$_SERVER['SERVER_PORT'];
				$server['os']=@PHP_OS;
				$server['version']=@PHP_VERSION;
				$server['name']=$_SERVER['SERVER_NAME'];
				$server['software']=$_SERVER['SERVER_SOFTWARE'];
				$server['db_version']=$this->db->version();
				$server['root']=$_SERVER['DOCUMENT_ROOT'];
				$server['upload']=@ini_get('upload_max_filesize');
				$session_timeout=@ini_get('session.gc_maxlifetime');
				$server['timeout']=$session_timeout?$session_timeout/60:'未知';
				$server['register_globals']=get_php_config('register_globals');
				$server['safe_mode']=get_php_config('safe_mode');
				$server['memory_usage']=format_size(memory_get_usage());
				$server['disable_functions']=@ini_get('disable_functions');
				$this->template->in('server',$server);
				$this->template->in('check_unsafe',$this->check_unsafe());
				$this->template->out('start.php');
			}elseif($this->do=='clear_cache'){
				$this->check_login();
				clear_cache();
				if($this->config['content_mode']==1){
					$this->html_index();
				}
			}elseif($this->do=='create_index'){
				$this->check_login();
				$this->html_index();
				exit('<span style="font:normal 12px Arial">Success!</span>');
			}elseif($this->do=='remote_save_image'){
				$this->check_login();
				$path=isset($_GET['path'])?trim($_GET['path']):'data/other/';
				$urls=explode('|',$_POST['urls']);
				$url_count=count($urls);
				for($i=0;$i<$url_count;$i++){
					$localurl=$this->remote_save_image($urls[$i],$path,false);
					if($localurl)$urls[$i]=$localurl;
				}
				echo implode('|',$urls);
				exit;
			}elseif($this->do=='upload'){
				$this->check_login();
				$path=isset($_GET['path'])?trim($_GET['path']):'data/other/';
				$attachment=upload($_FILES['filedata'],$path,EXT);
				$ext=get_ext($_FILES['filedata']['name']);
				if($this->config['watermark_status']==1&&($ext=='jpg'||$ext=='png'||$ext=='gif')){
					make_watermark(ROOT.'/'.$path.$attachment,ROOT.'/data/'.$this->config['watermark_image'],$this->config['watermark_position']);
				}
				echo"{'err':'','msg':'".PATH.$path.$attachment."'}";
				exit;
			}else{
				if(isset($_SESSION['admin_id'])){
					redirect('?do=start');
				}
				if($_POST){
					$admin_name=empty($_POST['admin_name'])?'':trim(strtolower($_POST['admin_name']));
					$admin_password=empty($_POST['admin_password'])?'':trim($_POST['admin_password']);

					$admin_name=filter_string($admin_name,array('%','$','+','"','^','&',"'",'or','select','union'));
					$admin_password=filter_string($admin_password,array('%','$','+','"','^','&',"'",'or','select','union'));
					if(empty($admin_name))alert('管理员名称不能为空');
					if(empty($admin_password))alert('密码不能为空');
					$row=$this->db->row("SELECT * FROM ".DB_PREFIX."admin WHERE admin_name='".$admin_name."' AND admin_password='".md5($admin_password)."' LIMIT 0,1");
					if($row){
						if($row['admin_status']==0){
							alert('管理员已锁定');
						}
						$_SESSION['admin_id']=$row['admin_id'];
						$_SESSION['admin_name']=$row['admin_name'];
						$_SESSION['admin_type']=$row['admin_type'];
						$_SESSION['admin_access']=$row['admin_access'];
						$this->db->update(DB_PREFIX."admin","admin_last_time='".$_SERVER['REQUEST_TIME']."',admin_last_ip='".get_ip()."',admin_login_time=admin_login_time+1","admin_id=".$row['admin_id']);
					}else{
						alert('登陆失败');
					}
					$this->set_hook('admin.login');
					redirect('?do=start');
				}
				$this->template->out('login.php');
			}
		}else{
			$this->check_login();
			if($this->action=='other'){
				$plugins=$this->get_plugins();
				foreach($plugins as $dir){
					if (is_file(ROOT.'/core/plugins/'.$dir.'/menu.php')&&$this->do==$dir){
						include ROOT.'/core/plugins/'.$dir.'/admin.php';
					}
				}
			}else{
				$file=ROOT.'/core/modules/'.$this->action.'/admin.index.php';
				if(file_exists($file))include($file);
			}
		}
	}
	private function remote_save_image($url,$path,$is_remote=true){
		$ext="jpg,jpeg,gif,png";//上传扩展名
		$reExt='('.str_replace(',','|',$ext).')';
		if(substr($url,0,10)=='data:image'){//base64编码的图片，可能出现在firefox粘贴，或者某些网站上，例如google图片
			if(!preg_match('/^data:image\/'.$reExt.'/i',$url,$ext))return false;
			$ext=$ext[1];
			$content=base64_decode(substr($url,strpos($url,'base64,')+7));
		}else{//url图片
			if($is_remote){
				if(!preg_match('/\.'.$reExt.'$/i',$url,$ext))return false;
				$ext=$ext[1];
				$content=http($url);
			}else{
				return false;
			}
		}
		$filename=$path.upload_name($ext);
		#保存到服务器
		file_put_contents(ROOT.'/'.$filename,$content);
		//检查mime是否为图片，需要php.ini中开启gd2扩展
		$fileinfo= @getimagesize(ROOT.'/'.$filename);
		if(!$fileinfo||!preg_match("/image\/".$reExt."/i",$fileinfo['mime'])){
			@unlink(ROOT.'/'.$filename);
			return false;
		}
		return PATH.$filename;
	}
	private function have_access($code){
		$status=false;
		$access=explode('|',trim($_SESSION['admin_access']));
		$access_children=isset($access[1])?explode(',',$access[1]):array();
		foreach($access_children as $v){
			if($v==$code){
				$status=true;
				break;
			}
		}
		return $status;
	}
	private function check_access($code){
		$access=explode('|',trim($_SESSION['admin_access']));
		$access_children=isset($access[1])?explode(',',$access[1]):array();
		if(!in_array($code,$access_children)&&$_SESSION['admin_type']==0){
			exit('Sorry there is no access!');
		}
	}
	#获取后台模块权限
	function get_modules_access(){
		$modules=$this->get_modules();
		$array=array();
		$array['global']=include(ROOT.'/core/modules/global/config.access.php');
		if($modules){
			foreach($modules as $dir){
				$file=ROOT.'/core/modules/'.$dir.'/config.access.php';
				if(file_exists($file))$array[$dir]=@include($file);
			}
		}
		return $array;
	}
	#获取后台模块菜单
	#update 2012/7/16
	function get_modules_menu(){
		$modules=$this->get_modules();
		$modules[]='global';
		$admin_type=isset($_SESSION['admin_type'])?$_SESSION['admin_type']:0;
		$access=explode('|',trim($_SESSION['admin_access']));
		$access_parent=isset($access[0])?explode(',',$access[0]):array();
		$access_children=isset($access[1])?explode(',',$access[1]):array();
		$array=array();
		if($modules){
			$no=1;
			foreach($modules as $dir){
				$file=ROOT.'/core/modules/'.$dir.'/config.menu.php';
				if(file_exists($file)){
					$menu=include $file;
					$item=array();
					foreach($menu['item'] as $k=>$v){
						if($admin_type==0){
							if(in_array($dir."_".$k,$access_children)){
								$item[$k]=$v;
								$item[$k]['code_add']=$k.'_add';
								$item[$k]['code_edit']=$k.'_edit';
							}else{
								unset($menu['item'][$k]);
							}
						}else{
							$item[$k]=$v;
							$item[$k]['code_add']=$k.'_add';
							$item[$k]['code_edit']=$k.'_edit';
						}

					}
					$hash=md5($menu['name']);
					if($admin_type==0){
						if(in_array($dir,$access_parent)){
							$array[$menu['sort'].'_'.$hash]['code']=$dir;
							$array[$menu['sort'].'_'.$hash]['name']=$menu['name'];
							$array[$menu['sort'].'_'.$hash]['children']=$item;
						}
					}else{
						$array[$menu['sort'].'_'.$hash]['code']=$dir;
						$array[$menu['sort'].'_'.$hash]['name']=$menu['name'];
						$array[$menu['sort'].'_'.$hash]['children']=$item;
					}
					$array[$menu['sort'].'_'.$hash]['sort']=$menu['sort'];
					$array[$menu['sort'].'_'.$hash]['no']=$no;
					++$no;
				}
			}
			ksort($array);
		}
		return $array;
	}
  
	private function check_login(){
		if(!isset($_SESSION['admin_id'])){
			alert('请重新登陆',get_self());
		}
	}
	protected function check_unsafe(){
		$array=array();
		if(DEBUG){
			$array[]='提醒：您当前处于测试模式，请手动修改<u>yckit.config.php</u>中的DEBUG改成false！';
		}
		if(file_exists(ROOT.'/admin.php')){
			$array[]='警告：请您立即修改根目录下 <u>admin</u>.php文件名，防止黑客猜中密码闯入！';
		}
		if(file_exists(ROOT.'/install.php')||file_exists(ROOT.'/install.lock')){
			$array[]='警告：请您立即删除根目录下 <u>install.php</u>文件，防止别人初始化您的系统！';
		}
		return $array;
	}
}
new admin();