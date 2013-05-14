<?php
/*
 * YCKIT RSS插件
 * ============================================================================
 * 版权所有 2012 YCKIT.COM 并保留所有权利。
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 作者：野草
 * 更新：2012年02月06日
 */
$xml="<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
$xml.="<rss version=\"2.0\">\r\n";
$xml.="\t\t<channel>\r\n";
$xml.="\t\t\t\t<title>".$this->config['title']."</title>\r\n";
$xml.="\t\t\t\t<link>".get_url()."</link>\r\n";
$xml.="\t\t\t\t<description>".$this->config['description']."</description>\r\n";
$xml.="\t\t\t\t<pubDate>".date('c')."</pubDate>\r\n";
$modules=$this->get_modules();
foreach($modules as $dir){
	$sitemap=ROOT.'/core/modules/'.$dir.'/interface.plugin.rss.php';
	if(file_exists($sitemap)&&$data=include($sitemap)){
		#print_r($data);exit;
		foreach($data as $value){
			$xml.="\t\t\t\t<item>\r\n";
			$xml.="\t\t\t\t\t\t<title><![CDATA[".$value['title']."]]></title>\r\n";
			$xml.="\t\t\t\t\t\t<link>".$value['uri']."</link>\r\n";
			$xml.="\t\t\t\t\t\t<category><![CDATA[".$value['category']."]]></category>\r\n";
			$xml.="\t\t\t\t\t\t<description><![CDATA[".$value['description']."]]></description>\r\n";
			$xml.="\t\t\t\t\t\t<pubDate>".$value['date']."</pubDate>\r\n";
			$xml.="\t\t\t\t\t\t<guid>".$value['uri']."</guid>\r\n";
			$xml.="\t\t</item>\r\n";
		}
	}
}
$xml.="\t\t</channel>\r\n";
$xml.="</rss>\r\n";
file_put_contents('rss.xml',$xml);
?>