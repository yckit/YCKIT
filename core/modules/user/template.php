<?php
if(!defined('ROOT'))exit('Access denied!');

$html="
<link rel=\"stylesheet\" type=\"text/css\" href=\"".PATH."core/modules/user/templates/front/styles/user.css\" />
<script src=\"".PATH."core/modules/user/templates/front/scripts/user.js\"></script>
";
$this->add_hook('static',$html);
$this->add_hook('onload',"get_user_info('".PATH."');");