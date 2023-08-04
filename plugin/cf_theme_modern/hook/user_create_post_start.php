<?php
exit;
$password0 = param('password0');
$password2 = param('password');
if($password0 != $password2){
	message('password', lang('repeat_password_incorrect'));
}
if(!empty($agreebbrule_url)){
	$agreetime = param('agreebbrule');
	if(empty($agreetime)){
		message('agreebbrule', lang('must_agree_to_site_terms_of_service_to_register'));
	}
	if(($time - $agreetime) > 5*60){
		message(1, lang('registration_timed_out_please_reregister'));
	}
}