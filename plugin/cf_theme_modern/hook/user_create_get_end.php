<?php
exit;
$register_img_url=empty($cf_theme_modern['register_img_url'])?'plugin/cf_theme_modern/img/register_tips.png':$cf_theme_modern['register_img_url'];
$user_create_end_referer=http_referer();
if(isset($cf_theme_modern['user_create_end_referer']) && $cf_theme_modern['user_create_end_referer']!=""){
	$user_create_end_referer=$cf_theme_modern['user_create_end_referer'];
}
$password_complexity=empty($cf_theme_modern['pwd_complexity'])?'0':$cf_theme_modern['pwd_complexity'];