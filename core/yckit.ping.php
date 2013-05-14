<?php
/*
 * YCKIT 快速搜录类
 * ============================================================================
 * 版权所有 2012-2013 YCKIT.COM 并保留所有权利。
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 作者：野草
 * 更新：2013年02月26日
 */
class ping{
	public $method,$callback;
	/*
	博客名称
	博客首页地址
	新发文章地址
	博客rss地址
	*/
	public function method($site_name,$site_url,$update_url,$update_rss){
		$this->method="<?xml version=\"1.0\" encoding=\"UTF-8\"?><methodCall><methodName>weblogUpdates.extendedPing</methodName><params><param><value>{$site_name}</value></param><param><value>{$site_url}</value></param><param><value>{$update_url}</value></param><param><value>{$update_rss}</value></param></params></methodCall>";
		return $this->method;
	}
	private function push($url,$postvar) {
		$c = curl_init();
		$headers =array("POST ".$url." HTTP/1.0","Content-type: text/xml;charset=\"utf-8\"","Accept: text/xml","Content-length: ".strlen($postvar));
		curl_setopt($c, CURLOPT_URL, $url);
		curl_setopt($c, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($c, CURLOPT_POST, 1);
		curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($c, CURLOPT_POSTFIELDS, $postvar);
		$result=curl_exec ($c);
		curl_close($c);
		return $result;
	}
	public function google(){
		$this->callback = $this->push('http://blogsearch.google.com/ping/RPC2', $this->method);
		return strpos($this->callback, "<boolean>0</boolean>") ? true : false;

	}
	public function baidu(){
		$this->callback = $this->push('http://ping.baidu.com/ping/RPC2', $this->method);
		return strpos($this->callback, "<int>0</int>") ? true : false;
	}
}