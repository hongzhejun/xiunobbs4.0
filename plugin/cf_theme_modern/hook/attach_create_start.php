<?php
exit;
$method != 'POST' AND message(-1,lang('method_error'));
if(param('sid')!=session_id()){
message(-1,lang('illegal_request'));
}