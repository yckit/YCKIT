<?php
/*
 * YCKIT 初始化
 * ============================================================================
 * 版权所有 2012 YCKIT.COM 并保留所有权利。
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 作者：野草
 * 更新：2012.05.01
 */
#PHP版本要大于5
if(version_compare(PHP_VERSION,'5.1.0','<')){
	exit('PHP_VERSION>5.1.0');
}
#程序运行模式
if(DEBUG){
	error_reporting(E_ALL);
}else{
	error_reporting(0);
}
#检测是否开启GZIP
if(GZIP){
	@ob_start("ob_gzhandler");
}else{
	@ob_start();
}
#设置时间区域
if(version_compare(PHP_VERSION,'5.1.0','>')){
	date_default_timezone_set(TIMEZONE);
}
#魔法引用如果开启就先移除斜杠
@set_magic_quotes_runtime(0);
if(@get_magic_quotes_gpc()){
	$_GET=remove_slashes($_GET);
	$_POST=remove_slashes($_POST);
	$_COOKIE=remove_slashes($_COOKIE);
}
#在部分IIS上会没有REQUEST_URI变量
$PHP_SELF=htmlentities(isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']);
$query_string = isset($_SERVER['argv'][0])?$_SERVER['argv'][0]:$_SERVER['QUERY_STRING'];
if (!isset($_SERVER['REQUEST_URI'])){
	$_SERVER['REQUEST_URI'] = PHP_SELF.'?'.$query_string;
}else{
	if (strpos($_SERVER['REQUEST_URI'], '?') === false && $query_string){
		$_SERVER['REQUEST_URI'] .= '?'.$query_string;
	}
}
/**
 * Multibyte encoding fallback functions
 *
 * The PHP multibyte encoding extension is not enabled by default. In cases where it is not enabled,
 * these functions provide a fallback.
 *
 * Borrowed from MediaWiki, under the GPLv2. Thanks!
 */
if ( !function_exists( 'mb_strlen' ) ) {
	/**
	 * Fallback implementation of mb_strlen, hardcoded to UTF-8.
	 * @param string $str
	 * @param string $enc optional encoding; ignored
	 * @return int
	 */
	function mb_strlen( $str, $enc = '' ) {
		$counts = count_chars( $str );
		$total = 0;
		// Count ASCII bytes
		for( $i = 0; $i < 0x80; $i++ ) {
			$total += $counts[$i];
		}
		// Count multibyte sequence heads
		for( $i = 0xc0; $i < 0xff; $i++ ) {
			$total += $counts[$i];
		}
		return $total;
	}
}
$ui=array();