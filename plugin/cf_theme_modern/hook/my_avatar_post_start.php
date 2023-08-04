<?php
exit;
$method != 'POST' AND message(-1,lang('trigger_xss_protection'));
if(param('sid')!=session_id()){
message(-1,lang('illegal_request'));
}
if ($gid==7 || $uid==0) {
message(-1,lang('insufficient_privilege'));
}