<?php exit?>

<dl id="comments">
<dt>发布评论</dt>
		<input type="hidden" id="comment_name" value="{$template.session.user_nickname|default:游客}"/>
		<input type="hidden" id="comment_email"value="{$template.session.user_login}" />
		<input type="hidden" id="comment_site" value=""/>
<dd class="comments-form">
    <div class="avatar"><img onerror="this.src='{$path}core/images/avatar.jpg'"  src="{if $template.session.user_id>0}
    	{$path}data/user/{$template.session.user_id}.jpg
    	{else}
    	{$path}core/images/avatar-comment.png
    	{/if}" align="absmiddle"/></div>
    <div class="main" style="margin-right:0">
    	<div class="textbox">
        <textarea id="comment_content"  {if !$template.session.user_id}disabled{/if}></textarea>
        <input type="hidden" id="parent_id" value="0"/>
        <div class="textbox-bottom">
        	<div class="textbox-info">欢迎您，{$template.session.user_nickname|default:游客}</div>
        	<div class="textbox-submit" id="comment_insert">发送提交</div>
        </div>
        {if !$template.session.user_id}
        <div class="textbox-tip">请<a href="javascript:void(user_box('会员登陆',400,220,'login'))">登录</a>后操作</div>
        {/if}
        </div>

    </div>
    <div class="clear"></div>
</dd>
 <!--{if $comment}-->
<dt>网友评论 <!--{$count}--> 条</dt>
<!--{foreach from=$comment item=comment}-->
<dd class="{cycle values='odd,even'}">
	<a name="comment-<!--{$comment.no}-->" href="#comment-<!--{$comment.no}-->" class="floor">#<!--{$comment.no}--></a>
    <div class="avatar" data-id="1"><img src="<!--{$comment.avatar}-->" align="absmiddle"/></div>
    <div class="main">
    	<p class="main-content"><!--{$comment.content}--></p>
    	<p class="main-meta"><strong><!--{$comment.name}--></strong> <!--{$comment.time}--> <!--{$comment.ip_address}--> 
    		<!--{if $template.session.user_id>0}-->
    			<a href="javascript:void(reply_comment(<!--{$comment.id}-->))">回复</a>
    		<!--{/if}-->
    	</p>
		  <!--{if $comment.reply}-->
		  <p class="main-admin">管理员：<!--{$comment.reply}--></p>
		  <!--{/if}-->
    	 <!--{if $comment.children}-->
	        <dl class="comments-reply">
	        <!--{foreach from=$comment.children item=child}-->
	        <dd>
			    <div class="avatar" data-id="1"><img src="<!--{$child.avatar}-->" align="absmiddle"/></div>
			    <div class="main">
			    	<p class="main-content"><!--{$child.content}--></p>
			    	<p class="main-meta"><strong><!--{$child.name}--></strong> <!--{$comment.time}--> <!--{$comment.ip_address}-->  
    		<!--{if $template.session.user_id>0}-->
    			<a href="javascript:void(reply_comment(<!--{$comment.id}-->))">回复</a>
    		<!--{/if}-->
			    	</p>
  <!--{if $child.reply}-->
  <p class="main-admin">管理员：<!--{$child.reply}--></p>
  <!--{/if}-->
			    </div>
			    <div class="clear"></div>
	        </dd>
	        <!--{/foreach}-->
	        </dl>
        <!--{/if}-->
    </div>
    <div class="clear"></div>
</dd>
<!--{/foreach}-->

<!--{if $pager.page_count>1}-->
			<div class="pager">
			<ul>
			<!--{if $pager.begin}-->
				<li><a href="javascript:get_comment('page',<!--{$page_id}-->,<!--{$pager.begin}-->)">&laquo;</a></li>
			<!--{else}-->
				<li class="disabled"><a>&laquo;</a></li>
			<!--{/if}-->

			<!--{foreach from=$pager.no key=key item=href}-->
			<!--{if $pager.current==$key}-->
				<li class="active"><a><!--{$key}--></a></li>
			<!--{else}-->
				<li><a href="javascript:get_comment('page',<!--{$page_id}-->,<!--{$href}-->)"><!--{$key}--></a></li>
			<!--{/if}-->
			<!--{/foreach}-->

			<!--{if $pager.end}-->
				<li><a href="javascript:get_comment('page',<!--{$page_id}-->,<!--{$pager.end}-->)">&raquo;</a></li>
			<!--{else}-->
				<li class="disabled"><a>&raquo;</a></li>
			<!--{/if}-->
			</ul>
			</div>
	<!--{/if}-->

<!--{/if}-->
</dl>