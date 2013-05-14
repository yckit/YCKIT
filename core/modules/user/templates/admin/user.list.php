<?php exit?>
<!--{include file="header.php"}-->
<div class="layout">
	<div class='layout-title'>会员管理</div>
	<!--{if $user}-->
	<form action="?action=user&do=delete" method="post" id="user_form">
	<table cellspacing="0" cellpadding="0" width="100%" class="table">
		<thead>
		<tr>
		<th align="center" width="50">&nbsp;</th>

		<th align="left">帐号</td>
		<th  width="5%">登陆IP</td>
		<th  width="5%"><a href="?action=user&do=list&orderby=user_point&sort=desc">积分</a></td>
		<th  width="15%"><a href="?action=user&do=list&orderby=user_login_time&sort=desc">最后登录</a></td>
 		<th  width="15%">注册时间</td><th  width="5%">状态</td>
		</tr>
		</thead>
		<tbody>
		<!--{foreach from=$user item=user}-->
		<tr>
		<td style="height:30px;" align="center" ><input type="checkbox" class="checkbox" name="user_id[]" value="<!--{$user.id}-->" /></td>

		<td>&nbsp;<b class="red"><!--{$user.nickname}--></b>&nbsp;<a href="?action=user&do=edit&user_id=<!--{$user.id}-->"><!--{$user.login}--></a></td>
		<td><!--{$user.login_ip}--></td>
		<td align="center"><b class="green"><!--{$user.point}--></b></td>
		<td align="center" style="color:#666"><!--{$user.login_time}--></td>
		<td align="center" style="color:#666"><!--{$user.join_time}--></td>
		<td align="center" style="color:#666"><!--{if $user.status==1}--><a href="?action=user&do=list&status=1&sort=desc"><b class="green">正常</b></a><!--{else}--><a href="?action=user&do=list&status=0&sort=desc"><b class="red">锁定</b></a><!--{/if}--></td>
		</tr>
		<!--{/foreach}-->
		</tbody>
		<tfoot>
		<tr>
		<td height="30" align="center"><input type="checkbox" onclick="check_all(this)" /></td>
		<td colspan="6">
		<div style="float:right;margin:8px;"><!--{include file="pager.php"}--></div> <a href="javascript:del()" class="button">批量删除</a>
			<input type="text" class="input" id="search_keyword" />
	<a href="javascript:Search()"  class="button">搜索</a>
		</td>
		</tr>
		</tfoot>
	</table>
	</form>
	<!--{else}-->
	<div style="line-height:50px;text-align:center;">暂无数据</div>
	<!--{/if}-->

</div>
<div style="text-align:right">
<a class="button" href="?action=user&do=add">添加会员</a>
</div>
<script type="text/javascript">
function Search(){
var keyword=$("#search_keyword").val();
	if($.trim(keyword)==''){
		alert('关键字不能为空');
		$("#search_keyword").focus();
		return;
	}
	keyword=keyword.replace(/\'/gi,"");
	keyword=keyword.replace(/\"/gi,"");
	keyword=keyword.replace(/\?/gi,"");
	keyword=keyword.replace(/\%/gi,"");
	keyword=keyword.replace(/\./gi,"");
	keyword=keyword.replace(/\*/gi,"");
	window.location.href="?action=user&do=list&keyword="+encodeURI(keyword);
}
function del(){
	var status=false;
	$('.checkbox').each(function(){
		if($(this).attr('checked')){
			status=true;
		}
	});
	if(!status){
		alert('至少选择一项');
		return false;
	}
	$('#user_form').attr('action','?action=user&do=delete');
	$('#user_form').submit();
}
</script>