
<table cellspacing="10">
 
<tr>
<td width="120">首页文章显示:</td>
<td><input x-webkit-speech class="input" type="number" name="index_size" size="15" value="{$config.index_size|default:10}"/></td>
</tr>
<tr>
<td width="120">边栏文章评论:</td>
<td><input x-webkit-speech class="input" type="number" name="comment_size" size="15" value="{$config.comment_size|default:10}"/></td>
</tr>
 <tr>
<td width="120">边栏推荐文章:</td>
<td><input x-webkit-speech class="input" type="number" name="best_size" size="15" value="{$config.best_size|default:10}"/></td>
</tr>
<tr>
<td width="120">边栏热门文章:</td>
<td><input x-webkit-speech class="input" type="number" name="click_size" size="15" value="{$config.click_size|default:10}"/></td>
</tr>
<tr>
<td width="120">边栏固定浮动:</td>
<td><input x-webkit-speech class="input" type="checkbox" name="side" value="1" {if $config.side==1}checked{/if}/> 启用</td>
</tr>
  

<tr>
<td width="120">文章内页<br/>广告代码:</td>
<td><textarea class="input" style="width:500px;height:100px;" name="ad">{$config.ad}</textarea></td>
</tr>
</table>
