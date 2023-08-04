<?php
exit;
$s .= "<span class='text-grey ml-1'>(".date('Y-m-d H:i:s', $attach['create_date'])."，".humansize($attach['filesize'])."，".lang('post_attach_downloads')."：".intval($attach['downloads']).")</span>";

?>
