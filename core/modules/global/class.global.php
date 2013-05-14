<?php
if(!defined('ROOT'))exit('Access denied!');
class global_class extends base{
	function __construct(){
		$this->init();
	}
	function get_menu(){
		$array=array();
		$result=$this->db->result("SELECT * FROM ".DB_PREFIX."menu WHERE parent_id=0 ORDER BY menu_sort ASC");
		if($result){
			foreach($result as $row){
				$array[$row['menu_id']]['id']=$row['menu_id'];
				$array[$row['menu_id']]['name']=$row['menu_name'];
				$array[$row['menu_id']]['link']=$row['menu_link'];
			}
		}
		return $array;
	}
}