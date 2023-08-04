<?php
exit;

function post_find_by_uid($uid, $page = 1, $pagesize = 50) {
	global $conf;
	
	// hook model_post_find_by_uid_start.php
	
	$arrlist = db_find('post', array('uid'=>$uid,'isfirst'=>0), array('pid'=>-1), $page, $pagesize, '', array('pid'));
	$pids = arrlist_values($arrlist, 'pid');
	$postlist = post_find_by_pids($pids);
	$postlist = arrlist_multisort($postlist, 'pid', FALSE);
	
	foreach($postlist as $k=>&$post) {
		//user_post_message_format($post['message_fmt']);
		//$post['filelist'] = array();
		$post['floor'] = 0; // default
		$thread = thread_read_cache($post['tid']);
		$post['subject'] = $thread['subject'];
	}
	
	// hook model_post_find_by_uid_end.php
	return $postlist;
}
