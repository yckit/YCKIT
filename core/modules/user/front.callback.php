<?php
if(!defined('ROOT'))exit('Access denied!');
if($this->do=='callback'){
    $user->qq_callback();
    $user->qq_openid();
    $user->qq_info();
    if($_SESSION['user_id']>0){
        $this->db->update(DB_PREFIX."user","open_id='".$_SESSION['qq_openid']."'","user_id='".$_SESSION['user_id']."'");
        $user->qq_avatar($_SESSION['user_id']);
        alert("QQ帐号绑定成功",PATH);
    }else{
        $row=$this->db->row("SELECT * FROM ".DB_PREFIX."user WHERE open_id='".$_SESSION['qq_openid']."'");
        if($row){
            $_SESSION['user_id']=$row['user_id'];
            $_SESSION['user_login']=$row['user_login'];
            $_SESSION['user_nickname']=$row['user_nickname'];
            $_SESSION['role_id']=$row['role_id'];
            $_SESSION['open_id']=$_SESSION['qq_openid'];
            $user->qq_avatar($_SESSION['user_id']);
            redirect(PATH);
        }else{
            redirect(PATH."#join");   
        }
       
    }
}