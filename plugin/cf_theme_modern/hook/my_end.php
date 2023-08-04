<?php
exit;
if($action == 'post') {
	if($method == 'GET') {
    // hook my_post_get_start.php
	$header['title'] = lang('my').lang('post').' - '.$conf['sitename'];
    $header['mobile_title'] = lang('my').lang('post').' - '.$conf['sitename'];
    $page = param(2, 1);
    $pagesize = 10;
    $totalnum = $user['posts'];
    $pagination = pagination(url("my-post-{page}"), $totalnum, $page, $pagesize);
    $postlist = post_find_by_uid($uid, $page, $pagesize);
    post_list_access_filter($postlist, $gid);
    // hook my_post_get_end.php
    include _include(APP_PATH.'plugin/cf_theme_modern/htm/my_post.htm');
	}
}
