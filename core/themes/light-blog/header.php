<?php exit?>
<div id="angle"></div>
<div id="header">
        <div id="info"></div>
        <a href="{$path}" title="{$config.title|escape:html}" rel="home" id="logo"><h1 class="site-title">{$config.title|escape:html}</h1></a>
        <h2 class="site-description"><!--{$config.notice|escape:html}--></h2>
        <div id="menu">
                <ul>
                <li><a href="<!--{$path}-->" id="menu-0">首页</a></li>
                <!--{foreach from=$menu item=menu}-->
                <li>
                        <a href="<!--{$menu.link}-->" id="menu-<!--{$menu.id}-->" <!--{if $menu.target==1}-->target="_blank"<!--{/if}-->>         <!--{$menu.name|escape:html}--></a>
                        <!--{if $menu.children}-->
                                <ul>
                                <!--{foreach from=$menu.children item=child}-->
                                <li class="b"><a  class="bb" href="<!--{$child.link}-->" <!--{if $child.target==1}-->target="_blank"<!--{/if}-->><!--{$child.name|escape:html}--></a></li>
                                <!--{/foreach}-->
                                </ul>
                        <!--{/if}-->
                </li>
                <!--{/foreach}-->
                <li class="right">
                <form id="search-form" action="javascript:Search('<!--{$path}-->')">
                        <input type="text" x-webkit-speech id="search_keyword" value="输入关键字..." onFocus="if (this.value == '输入关键字...') {this.value = '';}" onBlur="if (this.value == '') {this.value = '输入关键字...';}"/>
                        <input type="hidden" id="search_action" value="content" class="input" />
                </form>
                </li>
                </ul>      
        </div>
</div>