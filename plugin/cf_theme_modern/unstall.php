<?php
!defined('DEBUG') AND exit('Forbidden');

$tablepre = $db->tablepre;
#--post--
db_exec("ALTER TABLE {$tablepre}post DROP INDEX uid_pid;");

kv_delete('cf_theme_modern');

?>