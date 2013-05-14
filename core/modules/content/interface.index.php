<?php
if(!defined('ROOT'))exit('Access denied!');
require_once ROOT.'/core/modules/content/class.content.php';$content=new content_class();

$this->template->in('article',$content->get_articles(array(
	'limit'=>isset($this->config['index_size'])?$this->config['index_size']:10
)));
$this->template->in('article_best',$content->get_articles(array(
	'limit'=>isset($this->config['best_size'])?$this->config['best_size']:10,
	'is_best'=>1,
	'orderby'=>'article_time'
)));
$this->template->in('article_click',$content->get_articles(array(
	'limit'=>isset($this->config['click_size'])?$this->config['click_size']:10,
	'orderby'=>'article_click_count'
)));