<?php

!defined('DEBUG') AND exit('Access Denied.');



// 只允许管理员登陆后台
// Only allow administrators to log in the background

// 对于越权访问，可以默认为黑客企图，不用友好提示。
// For unauthorized access, can default to the hacking attempt, without a friendly reminder.
if(DEBUG < 3) {
	// 管理组检查 / check admin group
	if($gid != 1) {
		setcookie('bbs_sid', '', $time - 86400);
		//http_403();
		http_location(url('../user-login'));
	}
	
	// 管理员令牌检查 / check admin token
	admin_token_check();
}

$route = param(0, 'index');

switch ($route) {
	
	case 'index':		include _include(ADMIN_PATH.'route/index.php'); 	break;
	case 'setting': 	include _include(ADMIN_PATH.'route/setting.php'); 	break;
	case 'forum': 		include _include(ADMIN_PATH.'route/forum.php'); 	break;
	case 'friendlink': 	include _include(ADMIN_PATH.'route/friendlink.php'); 	break;
	case 'group': 		include _include(ADMIN_PATH.'route/group.php'); 	break;
	case 'other':		include _include(ADMIN_PATH.'route/other.php'); 	break;
	case 'user':		include _include(ADMIN_PATH.'route/user.php'); 		break;
	case 'thread':		include _include(ADMIN_PATH.'route/thread.php'); 		break;
	case 'plugin':		include _include(ADMIN_PATH.'route/plugin.php'); 	break;
	case 'credits_paylog': include APP_PATH.'plugin/tt_credits/admin_creditslog.php'; break;
case 'credits_addbuy': include APP_PATH.'plugin/tt_credits/admin_addbuy.php'; break;
case 'credits_setuser': include APP_PATH.'plugin/tt_credits/admin_setuser.php'; break;
case 'credits_setcredits': include APP_PATH.'plugin/tt_credits/admin_setcredits.php'; break;
case 'credits_log': include APP_PATH.'plugin/tt_credits/admin_log.php'; break;
case 'credits_rank': include APP_PATH.'plugin/tt_credits/admin_rank.php'; break;
	default: 
		
		include _include(ADMIN_PATH.'route/index.php'); 	break;
		/*
		!is_word($route) AND http_404();
		$routefile = _include(ADMIN_PATH."route/$route.php");
		!is_file($routefile) AND  http_404();
		include $routefile;
		*/
}

?>
