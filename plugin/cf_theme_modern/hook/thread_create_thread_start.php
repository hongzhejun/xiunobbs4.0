<?php
exit;
if(param('sid')!=session_id()){
message(-1,lang('illegal_request'));
}
