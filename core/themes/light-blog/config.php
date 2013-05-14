<?php
$array['index_size']=empty($_POST['index_size'])?10:intval($_POST['index_size']);
$array['comment_size']=empty($_POST['comment_size'])?10:intval($_POST['comment_size']);
$array['best_size']=empty($_POST['best_size'])?10:intval($_POST['best_size']);
$array['click_size']=empty($_POST['click_size'])?10:intval($_POST['click_size']);
$array['side']=empty($_POST['side'])?0:1;
$array['ad']=empty($_POST['ad'])?'':trim($_POST['ad']);