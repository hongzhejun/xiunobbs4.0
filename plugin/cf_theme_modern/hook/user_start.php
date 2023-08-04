<?php
exit;
if($action == 'post') {
	if($method == 'GET') {
	// hook user_post_get_start.php

	$_uid = param(2, 0);
	$_user = user_read($_uid);
	
	empty($_user) AND message(-1, lang('user_not_exists'));
	$header['title'] = $_user['username'].lang('de').lang('user_post').' - '.$conf['sitename'];
	$header['mobile_title'] = $_user['username'].lang('de').lang('user_post').' - '.$conf['sitename'];
	
	$page = param(3, 1);
	$pagesize = 20;
	$totalnum = $_user['posts'];
	$pagination = pagination(url("user-post-$_uid-{page}"), $totalnum, $page, $pagesize);
	$postlist = post_find_by_uid($_uid, $page, $pagesize);
	
	post_list_access_filter($postlist, $gid);
	
	// hook user_post_get_end.php
	
	include _include(APP_PATH.'plugin/cf_theme_modern/htm/user_post.htm');
	}
}