<?php
/*
 * YCKIT 数据库类
 * ============================================================================
 * 版权所有 2012 YCKIT.COM 并保留所有权利。
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 作者：野草
 * 更新：2012.05.01
 */
class db{
	# 变量
	private $db_link=null;
	private $db_name='';
	# 初始
	function __construct($db_host, $db_user, $db_password,$db_name,$db_create=false){
		$this->db_link=@mysql_connect($db_host, $db_user, $db_password,true) or exit("Can't connect MySQL server($db_host)!");
		if($db_create){
			$this->query("CREATE DATABASE IF NOT EXISTS $db_name DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
		}
		@mysql_select_db($db_name,$this->db_link) or exit("Can't select MySQL database($db_name)!");
		@mysql_query("set names 'utf8'",$this->db_link);
		$this->db_name=$db_name;
	}
	# 注销
	function __destruct(){
		if($this->db_link){
			mysql_close($this->db_link);
		}
	}
	# 执行SQL语句
	function query($sql){
		return mysql_query($sql,$this->db_link);
	}
	# 插入数据
	# $table,表名,字符串
	# $values,值,数组
	# $debug,是否输入SQL,布尔值
	function insert($table,$values,$debug=false){
		$ks='';
		$vs='';
		foreach($values as $key => $value){
			$ks.=$ks?",`$key`":"`$key`";
			$vs.=$vs?",'$value'":"'$value'";
		}
		$sql="insert into `$table` ($ks) values ($vs)";
		if($debug)return $sql;
		return $this->query($sql);
	}
	# 更新数据
	# $table,表名,字符串
	# $values,值,数组
	# $condition,条件,字符串
	# $debug,是否输入SQL,布尔值
	function update($table,$values,$condition='',$debug=false){
		$v='';
		if(is_string($values)){
			$v.=$values;
		}else{
			foreach($values as $key => $value){
				$v.=$v?",`$key`='$value'":"`$key`='$value'";
			}
		}
		$sql="update `$table` set $v  where $condition";
		if($debug)return $sql;
		return $this->query($sql);
	}
	# 删除数据
	# $table,表名,字符串
	# $condition,条件,字符串
	# $debug,是否输入SQL,布尔值
	function delete($table,$condition='',$debug=false){
		if(empty($condition)||$condition==''){
			$sql="delete from $table";
		}else{
			$sql="delete from $table where $condition";
		}
		if($debug)return $sql;
		return $this->query($sql);
	}
	# 统计数据
	# $sql,SQL语句,字符串
	# 返回：整数
	function count($sql){
		return mysql_num_rows($this->query($sql));
    }
	# 查询数据
	# $sql,SQL语句,字符串
	# 返回：数组
	function result($sql){
		$temp=false;
        $result=$this->query($sql);
        if($result){
            $array = array();
            while ($row = mysql_fetch_assoc($result)){
                $array[] = $row;
            }
            $temp=$array;
			mysql_free_result($result);
        }
		return $temp;
    }
	# 查询单行数据
	# $sql,SQL语句,字符串
	# 返回：数组
	function row($sql){
		$temp;
		$result=$this->query($sql);
        if ($result){
			$temp=mysql_fetch_array($result);
			mysql_free_result($result);
		}else{
			$temp=false;
		}
		return $temp;
	}
	/*
     * 获取指定字段值
     *
     * @access  public
     * @param	string	$table	表
	 * @param	string	$field	字段
	 * @param	string	$field_value 值
     * @return  array&boolean
     */
	function value($table,$field,$where=''){
		if(empty($table)||empty($field))return false;
		$result=$this->row("SELECT ".$field." FROM ".$table." WHERE ".$where."");
		return $result[0];
	}
	function repeat($table,$field,$value){
		$row=$this->row("SELECT $field FROM $table WHERE $field='$value' LIMIT 1");
		return $row?true:false;
	}
	function table(){
		$array=array();
		$result=mysql_list_tables($this->db_name);
		while ($row = mysql_fetch_row($result))$array[]=$row[0];
		return $array;
	}
	function export(){
		$table=$this->table();
		$sql='';
		foreach ($table as $v){
			$sql.="DROP TABLE IF EXISTS `$v`;\n";
			$rs=mysql_fetch_row(mysql_query("show create table $v"));
			$sql.=$rs[1].";\n\n";
		}
		foreach ($table as $v){
			$res=$this->query("select * from $v");
			$fild=mysql_num_fields($res);
			while ($rs=mysql_fetch_array($res)){
				$comma="";
				$sql.="insert into $v values(";
				for($i=0;$i<$fild;$i++){
					$sql.=$comma."'".mysql_escape_string($rs[$i])."'";
					$comma = ",";
				}
				$sql.=");\n";
			}
			$sql.="\n";
		}
		return $sql;
	}
	# 获取新插入ID
	# 返回：整数
    function id(){
        return mysql_insert_id($this->db_link);
    }
	# 获取版本
	function version(){
		return mysql_get_server_info($this->db_link);
	}
}
?>