<?php
use framework\lib\View;
View::$title = '首页';
View::registerMetaTag(['name' => 'description', 'content' => '后天首页']);
View::registerCssFile("css/index.css");
?>