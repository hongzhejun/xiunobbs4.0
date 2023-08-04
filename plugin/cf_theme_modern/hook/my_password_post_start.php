<?php
exit;
if(param('sid')!=session_id()){
message(-1,lang('illegal_request'));
}
if(param('password_old') == 'd41d8cd98f00b204e9800998ecf8427e' || param('password_new') == 'd41d8cd98f00b204e9800998ecf8427e' || param('password_new_repeat') == 'd41d8cd98f00b204e9800998ecf8427e') {
message(-1, lang('password_is_empty'));
}
empty($uid) AND message(-1, lang('please_login'));