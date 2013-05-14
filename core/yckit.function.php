<?php
/*
 * YCKIT 公用函数库
 * ============================================================================
 * 版权所有 2012 YCKIT.COM 并保留所有权利。
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 作者：野草
 * 更新：2013年05月10日
 */
function check_install(){
	if(file_exists("install.php")){
		header("location:install.php");
	}
}
function clear_cache($filename=''){
	$dirs=array();
	$dirs[]=ROOT.'/core/compiles/';
	if(empty($filename)){
		foreach ($dirs AS $dir){
			$folder = @opendir($dir);
			if ($folder === false){
				continue;
			}
			while ($file = readdir($folder)){
				if ($file == '.'||$file=='..'){
					continue;
				}
				if (is_file($dir.$file)){
					 @unlink($dir . $file);
				}
			}
			closedir($folder);
		}
	}else{
		foreach ($dirs AS $dir){
			$folder = @opendir($dir);
			if ($folder === false){
				continue;
			}
			if (is_file($dir.$filename)){
				 @unlink($dir . $filename);
			}
			closedir($folder);
		}
	}
}
function import($name){
	require_once(ROOT.'/core/yckit.'.$name.'.php');
}
/*
 * 生成缩略图
 *
 * @param	string	$image	临时文件对象
 * @param	integer	$toW	生成宽度
 * @param	integer	$toH	生成高度
 * @param	string	$image_thumb	临时文件对象
 * @return	void
 */
function make_thumb($image,$toW,$toH,$image_thumb="",$mode=false){
	if($image_thumb==""){#目标图像为空则替换原始图像
		$image_thumb=$image;
	}
	//获取原始图片大小
	$info=GetImageSize($image);
	if($info[2]==1) {
		if(function_exists("imagecreatefromgif")){
			$im=ImageCreateFromGIF($image);
		}
	}elseif($info[2]==2){
		if(function_exists("imagecreatefromjpeg")){
			$im=ImageCreateFromJpeg($image);
		}
	}else{
		$im=ImageCreateFromPNG($image);
	}
	$srcW=ImageSX($im);//获取原始图片宽度
	$srcH=ImageSY($im);//获取原始图片高度

	$toWH=$toW/$toH;//获取缩图比例
	$srcWH=$srcW/$srcH;//获取原始图比例

	if(!$mode){
		#按比例缩放图像算法
		if($toWH<=$srcWH){
			$ftoH=$toH;
			$ftoW=$ftoH*($srcW/$srcH);
		}else{
			$ftoW=$toW;
			$ftoH=$ftoW*($srcH/$srcW);
		}
		//创建画布并且复制原始图像到画布
		if (function_exists('imagecreatetruecolor')&&(function_exists('imagecopyresampled'))){
			$canvas=ImageCreateTrueColor($ftoW,$ftoH);
			#ImageCopyResized(dest,src,dx,dy,sx,sy,dw,dh,sw,sh);
			ImageCopyResampled($canvas,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);#缩放粘帖
		}else{
			$canvas=ImageCreate($ftoW,$ftoH);
			ImageCopyResized($canvas,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
		}
	}else{//裁剪
		if($srcW>$srcH){
			$ftoH=$toH;
			$ftoW=$ftoH*($srcW/$srcH);
		}else{
			$ftoW=$toW;
			$ftoH=$ftoW*($srcH/$srcW);
		}
		if (function_exists('imagecreatetruecolor')&&(function_exists('imagecopyresampled'))){
			$canvas=ImageCreateTrueColor($toW,$toH);
			ImageCopyResampled($canvas,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);#缩放粘帖
		}else{
			$canvas=ImageCreate($toW,$toH);
			ImageCopyResized($canvas,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
		}
	}
	//输入图像
	if(strtolower(get_ext($image))=='jpg'){
		imagejpeg($canvas,$image_thumb,100);
	}elseif(strtolower(get_ext($image))=='png'){
		imagepng($canvas,$image_thumb);
	}elseif(strtolower(get_ext($image))=='gif'){
		imagegif($canvas,$image_thumb);
	}
	//回收资源
	ImageDestroy($canvas);
	ImageDestroy($im);
}
/*
 * 生成水印
 *
 * @param	string	$groundImage	要水印图像
 * @param	string	$waterImage		水印图片
 * @param	integer	$waterPos		水印位置
 * @param	integer	$xOffset		X偏移
 * @param	integer	$yOffset		Y偏移
 * @return	void
 */
function make_watermark($groundImage,$waterImage="",$waterPos=0,$xOffset=0,$yOffset=0) {
     if(!empty($waterImage) && file_exists($waterImage)) {
         $water_info = getimagesize($waterImage);
         $water_w     = $water_info[0];//取得水印图片的宽
         $water_h     = $water_info[1];//取得水印图片的高
         switch($water_info[2])   {    //取得水印图片的格式
             case 1:$water_im = imagecreatefromgif($waterImage);break;
             case 2:$water_im = imagecreatefromjpeg($waterImage);break;
             case 3:$water_im = imagecreatefrompng($waterImage);break;
         }
     }
     //读取背景图片
     if(!empty($groundImage) && file_exists($groundImage)) {
         $ground_info = getimagesize($groundImage);
         $ground_w     = $ground_info[0];//取得背景图片的宽
         $ground_h     = $ground_info[1];//取得背景图片的高
         switch($ground_info[2]) {//取得背景图片的格式
             case 1:$ground_im = imagecreatefromgif($groundImage);break;
             case 2:$ground_im = imagecreatefromjpeg($groundImage);break;
             case 3:$ground_im = imagecreatefrompng($groundImage);break;
         }
     }
     $w = $water_w;
     $h = $water_h;
	 //水印位置
     switch($waterPos) {
         case 0:$posX = rand(0,($ground_w - $w));$posY = rand(0,($ground_h - $h));break;//随机
         case 1:$posX=0;$posY = 0;break;//1为顶端居左
         case 2:$posX = ($ground_w - $w) / 2;$posY = 0;break;//2为顶端居中
         case 3:$posX = $ground_w - $w;$posY = 0;break;//3为顶端居右
         case 4:$posX = 0;$posY = ($ground_h - $h) / 2;break;//4为中部居左
         case 5:$posX = ($ground_w - $w) / 2;$posY = ($ground_h - $h) / 2;break;//5为中部居中
         case 6:$posX = $ground_w - $w;$posY = ($ground_h - $h) / 2;break;//6为中部居右
         case 7:$posX = 0;$posY = $ground_h - $h;break;//7为底端居左
         case 8:$posX = ($ground_w - $w) / 2;$posY = $ground_h - $h;break;//8为底端居中
         case 9:$posX = $ground_w - $w;$posY = $ground_h - $h;break;//9为底端居右
         default:$posX = rand(0,($ground_w - $w));$posY = rand(0,($ground_h - $h));break;//随机
     }
     //设定图像的混色模式
     imagealphablending($ground_im, true);
     imagecopy($ground_im, $water_im, $posX + $xOffset, $posY + $yOffset, 0, 0, $water_w,$water_h);//拷贝水印到目标文件
     @unlink($groundImage);
     switch($ground_info[2]){
         case 1:imagegif($ground_im,$groundImage);break;
         case 2:imagejpeg($ground_im,$groundImage,100);break;
         case 3:imagepng($ground_im,$groundImage);break;
     }
     //释放内存
     if(isset($water_info)) unset($water_info);
     if(isset($water_im)) imagedestroy($water_im);
     unset($ground_info);
     imagedestroy($ground_im);
}
/*
 * 创建文件夹
 *
 * @param	string	$dir	路径
 * @param	integer	$mode	文件夹权限
 * @return	boolean
 */
function mk_dir($dir,$mode=0777,$index=true) {
    if(!is_dir($dir)) {
        mk_dir(dirname($dir));
        mkdir($dir);
		if($index)@file_put_contents($dir.'/index.htm','');
    }
}
/*
 * 删除文件夹
 *
 * @param	string	$dir	路径
 * @return	boolean
 */
function rm_dir($dir){
	$dh=opendir($dir);
	while($file=readdir($dh)){
		if($file!="."&&$file!=".."){
			$fullpath=$dir."/".$file;
			if(!is_dir($fullpath)){
				unlink($fullpath);
			}else{
				rm_dir($fullpath);
			}
		}
	}
	closedir($dh);
	if(rmdir($dir)){
		return true;
	}else{
		return false;
	}
}
/*
 * 上传文件
 *
 * @param	object	$upload	上传对象，可是单个或者数组
 * @param	boolean	$target	上传目标
 * @param	string	$ext	允许上传的文件后缀用逗号分隔
 * @param	integer	$size	上传大小（单位M）
 * @return	string
 */
function upload($upload,$target='./',$exts='jpg,jpeg,gif,png,bmp,torrent,zip,rar,7z,doc,docx,xls,xlsx,ppt,pptx,csv,mp3,wma,swf,flv,txt',$size=20,$rename=''){
	mk_dir($target);
	if(is_array($upload['name'])){
		$return=array();
		foreach ($upload["name"] as $k=>$v){
			if (!empty($upload['name'][$k])){
				$ext=get_ext($upload['name'][$k]);
				if (strpos($exts,$ext)!==false&&upload_check($upload['tmp_name'][$k],$ext)==$ext&&$upload['size'][$k]<$size*1024*1024){
					$name=empty($rename)?upload_name($ext):upload_rename($rename,$ext);
					if (upload_move($upload['tmp_name'][$k],$target.$name)){
						$return[]=$name;
					}
				}
			}
		}
		return $return;
	}else{
		$return='';
		if (!empty($upload['name'])){
			$ext=get_ext($upload['name']);
			if(strpos($exts,$ext)!==false&&upload_check($upload['tmp_name'],$ext)==$ext&&$upload['size']<$size*1024*1024){
				$name=empty($rename)?upload_name($ext):upload_rename($rename,$ext);
				if (upload_move($upload['tmp_name'],$target.$name)){
					$return=$name;
				}
			}
		}
	}
	return $return;
}
function upload_name($ext){
	$name=date('YmdHis');
	for ($i=0; $i < 3; $i++){
		$name.= chr(mt_rand(97, 122));
	}
	$name=strtoupper(md5($name)).".".$ext;
	return (string)$name;
}
function upload_rename($rename,$ext){
	$name=$rename.".".$ext;
	return (string)$name;
}
/*
 * 移动上传文件
 *
 * @param	string	$from	文件来源
 * @param	string	$target 移动目标地
 * @return	boolean
 */
function upload_move($from, $target= ''){
	if (function_exists("move_uploaded_file")){
		if (move_uploaded_file($from, $target)){
			@chmod($target,0755);
			return true;
		}
	}elseif (copy($from, $target)){
		@chmod($target,0755);
		return true;
	}
	return false;
}
/*
 * 检查上传文件
 *
 * @param	string	$name	临时文件
 * @param	string	$ext 上传文件后缀
 * @return	boolean
 */
function upload_check($name,$ext){
	$str=$format='';
	$file=@fopen($name, 'rb');
	if ($file){
		$str=@fread($file, 0x400);
		@fclose($file);
		if (strlen($str) >= 2 ){
			if (substr($str, 0, 4)=='MThd' && $ext != 'txt'){
				$format='mid';
			}elseif (substr($str, 0, 4)=='RIFF' && $ext=='wav'){
				$format='wav';
			}elseif (substr($str ,0, 3)=="\xFF\xD8\xFF"){
				$format='jpg';
			}elseif (substr($str ,0, 4)=='GIF8' && $ext != 'txt'){
				$format='gif';
			}elseif (substr($str ,0, 8)=="\x89\x50\x4E\x47\x0D\x0A\x1A\x0A"){
				$format='png';
			}elseif (substr($str ,0, 2)=='BM' && $ext != 'txt'){
				$format='bmp';
			}elseif ((substr($str ,0, 3)=='CWS' || substr($str ,0, 3)=='FWS') && $ext != 'txt'){
				$format='swf';
			}elseif (substr($str ,0, 4)=="\xD0\xCF\x11\xE0"){   // D0CF11E==DOCFILE==Microsoft Office Document
				if (substr($str,0x200,4)=="\xEC\xA5\xC1\x00" || $ext=='doc'){
					$format='doc';
				}elseif (substr($str,0x200,2)=="\x09\x08" || $ext=='xls'){
					$format='xls';
				}elseif (substr($str,0x200,4)=="\xFD\xFF\xFF\xFF" || $ext=='ppt'){
					$format='ppt';
				}
			}elseif (substr($str ,0, 2)=="7z"){
				$format='7z';
			}elseif (substr($str ,0, 4)=="PK\x03\x04"){
				$format='zip';
			}elseif (substr($str ,0, 4)=='Rar!' && $ext != 'txt'){
				$format='rar';
			}elseif (substr($str ,0, 4)=="\x25PDF"){
				$format='pdf';
			}elseif (substr($str ,0, 3)=="\x30\x82\x0A"){
				$format='cert';
			}elseif (substr($str ,0, 4)=='ITSF' && $ext != 'txt'){
				$format='chm';
			}elseif (substr($str ,0, 4)=="\x2ERMF"){
				$format='rm';
			}elseif ($ext=='sql'){
				$format='sql';
			}elseif ($ext=='txt'){
				$format='txt';
			}elseif ($ext=='htm'){
				$format='htm';
			}elseif ($ext=='html'){
				$format='html';
			}elseif (substr($str ,0, 3)=='FLV'){
				$format='flv';
			}
		}
	}
	return $format;
}
/*
 * 截取字符串
 *
 * @param	string	$string		要截取的字符串
 * @param	integer	$length		要截取的字数
 * @param	boolean	$append		是否打印省略号移
 * @return	string
 */
function truncate($string,$length,$append = true){
    $string = trim($string);
    $strlength = strlen($string);
    if ($length == 0 || $length >= $strlength){
        return $string;
    }elseif ($length < 0){
        $length = $strlength + $length;
        if ($length < 0)
        {
            $length = $strlength;
        }
    }
    if (function_exists('mb_substr')){
        $newstr = mb_substr($string, 0, $length,"UTF-8");
    }elseif (function_exists('iconv_substr')){
        $newstr = iconv_substr($string, 0, $length,"UTF-8");
    }else{
		for($i=0;$i<$length;$i++){
				$tempstring=substr($string,0,1);
				if(ord($tempstring)>127){
					$i++;
					if($i<$length){
						$newstring[]=substr($string,0,3);
						$string=substr($string,3);
					}
				}else{
					$newstring[]=substr($string,0,1);
					$string=substr($string,1);
				}
			}
		$newstr =join($newstring);
    }
    if ($append && $string != $newstr){
        $newstr .= '...';
    }
    return $newstr;
}
/*
 * 检测是否合法提交（在自己的服务器提交）
 *
 * @return	string,void
 */
function check_request(){
	if(empty($_SERVER['HTTP_REFERER'])||(preg_replace("/https?:\/\/([^\:\/]+).*/i","\\1",$_SERVER['HTTP_REFERER'])!=preg_replace("/([^\:]+).*/", "\\1",$_SERVER['HTTP_HOST']))){
		@header("HTTP/1.0 404 Not Found");
		exit();
	}
}
/*
 * 过滤特定字符
 *
 * @param	string $content 内容池
 * @param	array $filter 要过滤的字符阵列
 * @return	string
 */
function filter_string($content,$filter=array()){
	$temp=$content;
	if(is_array($filter)&&count($filter)>0){
		foreach($filter as $value){
			$temp=str_replace($value,'',$temp);
		}
	}
	return $temp;
}
function http_301($url='./'){
	header('HTTP/1.1 301 Moved Permanently');
	Header( "Location:$url");
	exit();
}
function http_404(){
	header("HTTP/1.1 404 Not Found");
	exit();
}
/*
 * 获取文件后缀名
 *
 * @param	string $filename 文件名
 * @return	string
 */
function get_ext($filename){
	if(!empty($filename)){
		return end(explode(".",strtolower($filename)));
	}
}
/*
 * 获取PHP_SELF
 *
 * @return	string
 */
function get_self(){
	return isset($_SERVER['PHP_SELF'])?$_SERVER['PHP_SELF']:$_SERVER['SCRIPT_NAME'];
}
/*
 * 获取虚拟绝对路径（不包含文件名）
 *
 * @return	string
 */
function get_url(){
	$php_self=get_self();
	$self=explode('/',$php_self);
	$self_count=count($self);
	$url='http://'.$_SERVER['SERVER_NAME'];
	if($self_count>1){
		$url.=str_replace('/'.$self[$self_count-1],'',$php_self);
	}
	if(substr($url,-1)!='/'){
		$url.='/';
	}
	return $url;
}
/*
 * 获取IP
 *
 * @return	string
 */
function get_ip(){
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	}elseif (isset($_SERVER['HTTP_CLIENT_IP'])){
		$ip=$_SERVER['HTTP_CLIENT_IP'];
	}else{
		$ip=$_SERVER['REMOTE_ADDR'];
	}
	if(check_ip($ip)){
		return $ip;
	}else{
		return '0.0.0.0';
	}
}
/*
 * 检查IP合法性
 *
 * @return	string
 */
function check_ip($ip){
	$oct = explode('.', $ip);
	if (count($oct) != 4) {
		return false;
	}
	for ($i = 0; $i < 4; $i++) {
		if (!is_numeric($oct[$i])) {
			return false;
		}
		if ($oct[$i] < 0 || $oct[$i] > 255){
			return false;
		}
	}
	return true;
}
/*
 * 获取IP物理位置
 *
 * @param string $ip IP地址
 * @return	string
 */
function get_ip_address($ip){
    static $fp = NULL, $offset = array(), $index = NULL;
    $ip    = @gethostbyname($ip);
    $ipdot = explode('.', $ip);
    $ip    = pack('N', ip2long($ip));
    $ipdot[0] = (int)$ipdot[0];
    $ipdot[1] = (int)$ipdot[1];
    if ($ipdot[0] == 10 || $ipdot[0] == 127 || ($ipdot[0] == 192 && $ipdot[1] == 168) || ($ipdot[0] == 172 && ($ipdot[1] >= 16 && $ipdot[1] <= 31))){
        return 'LAN';
    }
    if ($fp === NULL){
        $fp = fopen(ROOT.'/data/ip.dat', 'rb');
        if ($fp === false){
            return 'Invalid IP data file';
        }
        $offset = unpack('Nlen', fread($fp, 4));
        if ($offset['len'] < 4){
            return 'Invalid IP data file';
        }
        $index  = fread($fp, $offset['len'] - 4);
    }
    $length = $offset['len'] - 1028;
    $start  = unpack('Vlen', $index[$ipdot[0] * 4] . $index[$ipdot[0] * 4 + 1] . $index[$ipdot[0] * 4 + 2] . $index[$ipdot[0] * 4 + 3]);
    for ($start = $start['len'] * 8 + 1024; $start < $length; $start += 8){
        if ($index{$start} . $index{$start + 1} . $index{$start + 2} . $index{$start + 3} >= $ip){
            $index_offset = unpack('Vlen', $index{$start + 4} . $index{$start + 5} . $index{$start + 6} . "\x0");
            $index_length = unpack('Clen', $index{$start + 7});
            break;
        }
    }
    fseek($fp, $offset['len'] + $index_offset['len'] - 1024);
    $area = fread($fp, $index_length['len']);
    fclose($fp);
    $fp = NULL;
    return $area;
}
/*
 * 获取操作系统
 *
 * @param string $AGENT HTTP_USER_AGENT
 * @return	string
 */
function get_os($AGENT=''){
	if(empty($AGENT)){
		$AGENT=$_SERVER["HTTP_USER_AGENT"];
	}
	if(strpos($AGENT,"Windows NT 5.0"))$os="Windows 2000";
	elseif(strpos($AGENT,"Windows NT 5.1"))$os="Windows XP";
	elseif(strpos($AGENT,"Windows NT 5.2"))$os="Windows 2003";
	elseif(strpos($AGENT,"Windows NT 6.0"))$os="Windows Vista";
	elseif(strpos($AGENT,"Windows NT 6.1"))$os="Windows 7";
	elseif(strpos($AGENT,"Windows NT"))$os="Windows NT";
	elseif(strpos($AGENT,"Windows CE"))$os="Windows CE";
	elseif(strpos($AGENT,"ME"))$os="Windows ME";
	elseif(strpos($AGENT,"Windows 9"))$os="Windows 98";
	elseif(strpos($AGENT,"unix"))$os="Unix";
	elseif(strpos($AGENT,"linux"))$os="Linux";
	elseif(strpos($AGENT,"SunOS"))$os="SunOS";
	elseif(strpos($AGENT,"OpenBSD"))$os="OpenBSD";
	elseif(strpos($AGENT,"FreeBSD"))$os="FreeBSD";
	elseif(strpos($AGENT,"AIX"))$os="AIX";
	elseif(strpos($AGENT,"Mac"))$os="Mac";
	else $os="Other";
	return $os;
}
/*
 * 获取浏览器
 *
 * @param string $AGENT HTTP_USER_AGENT
 * @return	string
 */
function get_bs($AGENT=''){
	if(empty($AGENT)){
		$AGENT=$_SERVER["HTTP_USER_AGENT"];
	}
	if(strpos($AGENT,"Opera"))$browser="Opera";
	elseif(strpos($AGENT,"Firefox"))$browser="Firefox";
	elseif(strpos($AGENT,"Chrome"))$browser="Chrome";
	elseif(strpos($AGENT,"MSIE 6"))$browser="IE6";
	elseif(strpos($AGENT,"MSIE 7"))$browser="IE7";
	elseif(strpos($AGENT,"MSIE 8"))$browser="IE8";
	else $browser="Other";
	return $browser;
}
/*
 * 检测E-MAIL 合法性
 *
 * @param string $user_email E-mail
 * @return	boolean
 */
function is_email($user_email){
    $chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
    if (strpos($user_email, '@') !== false && strpos($user_email, '.') !== false){
        if (preg_match($chars, $user_email)){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}
/*
 * 创建SQL IN语法
 *
 * @param array $item_list		数组
 * @param string $field_name	字段
 * @return	string
 */
function create_sql_in($item_list, $field_name = ''){
    if (empty($item_list)){
        return $field_name . " IN ('') ";
    }else{
        if (!is_array($item_list)){
            $item_list = explode(',', $item_list);
        }
        $item_list = array_unique($item_list);
        $item_list_tmp = '';
        foreach ($item_list AS $item){
            if ($item !== ''){
                $item_list_tmp .= $item_list_tmp ? ",'$item'" : "'$item'";
            }
        }
        if (empty($item_list_tmp)){
            return $field_name . " IN ('') ";
        }else{
            return $field_name . ' IN (' . $item_list_tmp . ') ';
        }
    }
}
/*
 * 获取远程内容
 *
 * @param string $url	地址
 * @return	string
 */
function http($url){
	set_time_limit(0);
	$result=file_get_contents($url);
	if($result===false){
		$curl=curl_init();
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_HEADER,false);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		$result=curl_exec($curl);
		curl_close($curl);
	}
	if(empty($result)){
		$result=false;
	}
	return $result;
}
/*
 * 无提示转跳
 * @param string $url 地址
 * @return	void
 */
function redirect($url){
	echo"<script type='text/javascript'>location.href='$url'</script>";
	exit;
}
/*
 * 提示转跳
 * @param string $url 地址
 * @return	void
 */
function alert($text,$url=''){
	echo"<script type='text/javascript'>";
	echo"alert('$text');";
	if($url!=''){
		echo"location.href='$url';";
	}else{
		echo"history.back();";
	}
	echo"</script>";
	exit;
}
/*
 * 通用分页
 *
 * @param string $page_name 页面文件
 * @param string $page_parameters 页面参数
 * @param string $page_current 页面当前页面
 * @param string $page_size 页面显示各数
 * @param string $count 总数据
 * @param string $mode	组合模式(normal=传统模式,uri=地址模式,array=数组模式)
 * @return	string
 */
function pager($page_name,$page_parameters='',$page_current,$page_size,$count,$mode='normal'){
	parse_str($page_parameters);
	$page_count		=ceil($count/$page_size);
	$page_start		=$page_current-4;#开始
	$page_end		=$page_current+4;#结束
	if($page_current<=6){#如果当前页码小于3
		$page_start	=1;
		$page_end	=9;
	}
	if($page_current>$page_count-4){
		$page_start	=$page_count-8;
		$page_end	=$page_count;
	}
	if($page_start<1)$page_start=1;
	if($page_end>$page_count)$page_end=$page_count;
	$array=array();
	$array['current']=$page_current;
	$array['page_size']=number_format($page_size);
	$array['data_count']=number_format($count);
	$array['page_count']=number_format($page_count);
	if($page_current!=1){
		if($mode=='array'){
			$array['begin']=1;
		}else{
			$array['begin']=$page_name."?".$page_parameters."page=1";
		}
	}else{
		$array['begin']='';
	}
	$page_prev=$page_current-1;
	if($page_prev>=1){
		if($mode=='array'){
			$array['prev']=$page_prev;
		}else{
			$array['prev']=$page_name."?".$page_parameters."page=".$page_prev;
		}
	}else{
		$array['prev']='';
	}
	for($i=$page_start;$i<=$page_end;$i++){
		if($mode=='array'){
			$array['no'][$i]=$i;
		}else{
			$array['no'][$i]=$page_name."?".$page_parameters."page=".$i;
		}
	}
	$page_next=$page_current+1;
	if($page_next<=$page_count){
		if($mode=='array'){
			$array['next']=$page_next;
		}else{
			$array['next']=$page_name."?".$page_parameters."page=".$page_next;
		}
	}else{
		$array['next']='';
	}
	if($page_current!=$page_count){
		if($mode=='array'){
			$array['end']=$page_count;
		}else{
			$array['end']=$page_name."?".$page_parameters."page=".$page_count;
		}
	}else{
		$array['end']='';
	}
	return $array;
}
/*
 * 写结果缓存文件
 * @params  string  $cache_name
 * @params  string  $value
 */
function write_cache($name,$value){
    $path=ROOT.'/core/compiles/'.md5($name).'.php';
	if($value!=''){
		file_put_contents($path,"<?php return ".var_export($value,true).";",LOCK_EX);
	}
}
/*
 * 读结果缓存文件
 * @params  string  $cache_name
 * @return  array   $data
 */
function read_cache($name){
    $path=ROOT.'/core/compiles/'.md5($name).'.php';
    if(file_exists($path)){
        return include($path);
    }else{
        return false;
    }
}
/*
 * 读取文件或者文件夹的权限代码
 * @params  string  $file_path
 * @return  intger
 */
function file_level($file_path){
    if (!file_exists($file_path))return false;
	$mark = 0;
	if(strtoupper(substr(PHP_OS, 0, 3))=='WIN'){
        $test_file = $file_path.'/test.txt';
        if (is_dir($file_path)){
            /* 检查目录是否可读 */
            $dir = @opendir($file_path);
            if($dir === false)return $mark; //如果目录打开失败，直接返回目录不可修改、不可写、不可读
            if (@readdir($dir) !== false)$mark ^= 1;
            @closedir($dir);             /* 检查目录是否可写 */
            $fp = @fopen($test_file, 'wb');
            if ($fp === false)return $mark; //如果目录中的文件创建失败，返回不可写。
            if (@fwrite($fp, 'directory access testing.') !== false)$mark ^= 2; //目录可写可读011，目录可写不可读 010
            @fclose($fp);
			@unlink($test_file);
			/* 检查目录是否可修改 */
            $fp = @fopen($test_file, 'ab+');
            if ($fp === false)return $mark;
            if (@fwrite($fp, "modify test.\r\n") !== false)$mark ^= 4;
            @fclose($fp);
			/* 检查目录下是否有执行rename()函数的权限 */
            if (@rename($test_file, $test_file) !== false)$mark ^= 8;
            @unlink($test_file);
        }elseif (is_file($file_path)){
            /* 以读方式打开 */
            $fp = @fopen($file_path, 'rb');
            if ($fp) $mark ^= 1; //可读 001
            @fclose($fp);             /* 试着修改文件 */
            $fp = @fopen($file_path, 'ab+');
            if ($fp && @fwrite($fp, '') !== false) $mark ^= 6;
            @fclose($fp);             /* 检查目录下是否有执行rename()函数的权限 */
            if (@rename($file_path,$file_path.".txt") !== false){
				$mark ^= 8;
				@rename($file_path.".txt",$file_path);
			}
        }
    }else{
        if (@is_readable($file_path)){
            $mark ^= 1;
        }
		if (@is_writable($file_path)){
            $mark ^= 14;
        }
    }
	return $mark;
}
/*
 * 格式化容量
 * @params  string  $cache_name
 * @return  array   $data
 */
function format_size($filesize){
    if($filesize >= 1073741824){
		$filesize=round($filesize / 1073741824 * 100) / 100 . ' GB';
	}elseif ($filesize >= 1048576){
		$filesize=round($filesize / 1048576 * 100) / 100 . ' MB';
    }elseif($filesize >= 1024){
		$filesize=round($filesize / 1024 * 100) / 100 . ' KB';
    }else{
		$filesize=$filesize.' Bytes';
	}
    return $filesize;
}
function pinyin($content,$charset='utf-8') {
	$data='';
	$k="a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo";
	$v="-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274|-10270|-10262|-10260|-10256|-10254";
	$kk=explode('|',$k);
	$vv =explode('|',$v);
	$data=array_combine($kk,$vv);
	arsort($data);
	reset($data);
	if($charset!='gb2312')$content=pinyin_utf_gb($content);
	$result='';
	for($i=0;$i<strlen($content); $i++){
			$p=ord(substr($content, $i, 1));
			if($p>160) {
				$q=ord(substr($content,++$i,1)); $p=$p*256+$q-65536;
			}
			$result.=pinyin_translate($p, $data);
	}
	return $result;
	#return preg_replace("/[^a-z0-9]*/",'',$result);
}

function pinyin_translate($number,$data) {
	if($number>0&&$number<160){
		return chr($number);
	}elseif($number<-20319||$number>-10247){
		return '';
	}else{
		foreach($data as $k=>$v){
			if($v<=$number) break;
		}
		return $k;
	}
}

function pinyin_utf_gb($content){
	$string='';
	if($content<0x80){
		$string.=$content;
	}elseif($content < 0x800){
		$string.=chr(0xC0|$content>>6);
		$string.=chr(0x80|$content&0x3F);
	}elseif($content < 0x10000){
		$string.=chr(0xE0|$content>>12);
		$string.=chr(0x80|$content>>6&0x3F);
		$string.=chr(0x80|$content&0x3F);
	}elseif($content< 0x200000){
		$string.=chr(0xF0|$content>>18);
		$string.=chr(0x80|$content>>12&0x3F);
		$string.=chr(0x80|$content>>6&0x3F);
		$string.=chr(0x80|$content&0x3F);
	}
	return iconv('UTF-8', 'GB2312',$string);
}

function get_php_config($name){
	switch($result=@get_cfg_var($name)){
		case 0:
			return '×';
			break;
		case 1:
			return '√';
			break;
		default:
			return $result;
			break;
	}
}
#判断是否为机器人、爬虫
function check_robot($useragent = ''){
	static $kw_spiders = 'Bot|Crawl|Spider|slurp|sohu-search|lycos|robozilla';
	static $kw_browsers = 'MSIE|Netscape|Opera|Konqueror|Mozilla';
	$useragent = empty($useragent) ? $_SERVER['HTTP_USER_AGENT'] : $useragent;
	if(!strexists($useragent, 'http://') && preg_match("/($kw_browsers)/i", $useragent)) {
		return false;
	} elseif(preg_match("/($kw_spiders)/i", $useragent)) {
		return true;
	} else {
		return false;
	}
}
#判断是否为移动设备
function check_mobile(){
	static $keyword ='iPhone|Android|phone|WAP|NetFront|JAVA|Opera\sMini|UCWEB|Windows\sCE|Symbian|Series|webOS|SonyEricsson|Sony|BlackBerry|IEMobile|dopod|Nokia|samsung|PalmSource|Xda|PIEPlus|MEIZU|MIDP|CLDC';
	if(preg_match("/$mobilebrowser_list/i",$_SERVER['HTTP_USER_AGENT'],$keyword)){
		return true;
	}else{
		if(preg_match('/(mozilla|chrome|safari|opera|m3gate|winwap|openwave|myop)/i', $_SERVER['HTTP_USER_AGENT'])){
			return false;
		}else{
			return false;
		}
	}
}
#检查XSS攻击
function check_xss() {
	$temp=strtoupper(urldecode(urldecode($_SERVER['REQUEST_URI'])));
	if(strpos($temp, '<') !== false || strpos($temp, '"') !== false || strpos($temp,'CONTENT-TRANSFER-ENCODING')!==false) {
		exit('request_tainting');
	}
	return true;
}
#生成标签内链
function tag_link($content,$tags=array()){
	$html_tags=array('html','head','body','div','span','p','h1','h2','h3','h4','h5','h6','strong','em','abbr','acronym','address','bdo','blockquote','cite','q','code','ins','del','dfn','kbd','pre','samp','var','br','a','base','img','area','map','object','param','ul','ol','li','dl','dt','dd','table','tr','td','th','tbody','thead','tfoot','col','colgroup','caption','form','input','textarea','select','option','optgroup','button','label','fieldset','legend','b','i','tt','sub',' sup',' big','small','hr');
	if(count($tags)>0){
		$i=1;
		$tmp=array();
		foreach($tags as $tag=>$url){
			if(!in_array($tag,$html_tags)){
				$tmp[$i]="<a href=\"$url\" class=\"tag\">$tag</a>";
				$content=str_replace_limit($tag,"__tmp__".$i,$content,1);
				$i+=1;
			}
		}
		foreach($tmp as $k=>$v){
			$content=str_replace_limit("__tmp__".$k,$v,$content,1);
		}
	}
	return $content;
}

function str_replace_limit($search, $replace, $subject, $limit=-1){
    if(is_array($search)){
        foreach ($search as $k=>$v){
            $search[$k] = '`' . preg_quote($search[$k],'`') . '`';
        }
    }else{
        $search = '`' . preg_quote($search,'`') . '`';
    }
    return preg_replace($search, $replace, $subject, $limit);
}
 

function remove_slashes($s){
	if(is_array($s)){
		foreach ($s as $k=>$v)$s[$k]=remove_slashes($v);
	}else{
		$s=stripslashes($s);
	}
	return $s;
}
function format_time($time){
	$dur=$_SERVER['REQUEST_TIME']-$time;
	if($dur < 60){
		return $dur.'秒前';
	}else{
		if($dur < 3600){
			return floor($dur/60).'分钟前';
		}else{
			if($dur < 86400){
				return floor($dur/3600).'小时前';
			}else{
				if($dur < 259200){
					return floor($dur/86400).'天前';
				}else{
					return date('Y-m-d H:i',$time);
				}
			}
		}
	}
}

function array_multi2single($array){
	$result_array=array();
	foreach($array as $value){
		if(is_array($value)){
			array_multi2single($value);
		}else{
			$result_array[]=$value;
		}
	}
	return $result_array;
}