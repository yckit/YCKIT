<?php
/*
 * YCKIT sitemap插件
 * ============================================================================
 * 版权所有 2012 YCKIT.COM 并保留所有权利。
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 作者：野草
 * 更新：2012年02月06日
 */
$xml="<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\r\n";
$xml.="<url>\r\n";
$xml.="\t<loc>".get_url()."</loc>\r\n";
$xml.="\t<changefreq>daily</changefreq>\r\n";
$xml.="\t<priority>0.8</priority>\r\n";
$xml.="</url>\r\n";
$modules=$this->get_modules();
foreach($modules as $dir){
	$sitemap=ROOT.'/core/modules/'.$dir.'/interface.plugin.sitemap.php';
	if(file_exists($sitemap)&&$data=include($sitemap)){
		#print_r($data);exit;
		foreach($data as $value){
			$xml.="<url>\r\n";
			$xml.="\t<loc>".$value['loc']."</loc>\r\n";
			$xml.="\t<lastmod>".$value['lastmod']."</lastmod>\r\n";
			$xml.="\t<changefreq>daily</changefreq>\r\n";
			$xml.="\t<priority>0.8</priority>\r\n";
			$xml.="</url>\r\n";
		}
	}
}
$xml.="</urlset>\r\n";
file_put_contents('sitemap.xml',$xml);