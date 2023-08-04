<?php
!defined('DEBUG') AND exit('Forbidden');

$kv=kv_get('cf_theme_modern');
if(!$kv){
	$tablepre = $db->tablepre;
	#--post index--
	db_exec("ALTER TABLE {$tablepre}post ADD INDEX uid_pid(uid, pid);");

	#--default setting--
	$cf_theme_modern = array();
	$cf_theme_modern['announcement'] = 0;
	$cf_theme_modern['right_img'] = 0;
	$cf_theme_modern['thread_aside'] = 0;
	$cf_theme_modern['header_link'] = 0;
	$cf_theme_modern['ShowXiuno'] = 1;
	$cf_theme_modern['ShowProcessed'] = 1;
	
	kv_set('cf_theme_modern', $cf_theme_modern);
}
?>