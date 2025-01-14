<?php


// 只能在当前 request 生命周期缓存，要跨进程，可以再加一层缓存： memcached/xcache/apc/
$g_static_users = array(); // 变量缓存



// ------------> 最原生的 CURD，无关联其他数据。

function user__create($arr) {
	
	$r = db_insert('user', $arr);
	
	return $r;
}

function user__update($uid, $update) {
	
	$r = db_update('user', array('uid'=>$uid), $update);
	
	return $r;
}

function user__read($uid) {
	
	$user = db_find_one('user', array('uid'=>$uid));
	
	return $user;
}

function user__delete($uid) {
	
	$r = db_delete('user', array('uid'=>$uid));
	
	return $r;
}

// ------------> 关联 CURD，主要是强相关的数据，比如缓存。弱相关的大量数据需要另外处理。

function user_create($arr) {
	
	global $conf;
	$r = user__create($arr);
	
	// 全站统计
	runtime_set('users+', 1);
	runtime_set('todayusers+', 1);
	
	
	return $r;
}

function user_update($uid, $arr) {
	
	global $conf, $g_static_users;
	$r = user__update($uid, $arr);
	!in_array($conf['cache']['type'], array('mysql', 'pdo_mysql')) AND cache_delete("user-$uid");
	isset($g_static_users[$uid]) AND $g_static_users[$uid] = array_merge($g_static_users[$uid], $arr);
	
	
	return $r;
}

function user_read($uid) {
	global $g_static_users;
	if(empty($uid)) return array();
	$uid = intval($uid);
	
	$user = user__read($uid);
	user_format($user);
	$g_static_users[$uid] = $user;
	
	return $user;
}


// 从缓存中读取，避免重复从数据库取数据，主要用来前端显示，可能有延迟。重要业务逻辑不要调用此函数，数据可能不准确，因为并没有清理缓存，针对 request 生命周期有效。
function user_read_cache($uid) {
	global $conf, $g_static_users;
	if(isset($g_static_users[$uid])) return $g_static_users[$uid];
	
	
	
	// 游客
	if($uid == 0) return user_guest();
	
	if(!in_array($conf['cache']['type'], array('mysql', 'pdo_mysql'))) {
		$r = cache_get("user-$uid");
		if($r === NULL) {
			$r = user_read($uid);
			cache_set("user-$uid", $r);
		}
	} else {
		$r = user_read($uid);
	}
	
	$g_static_users[$uid] = $r ? $r : user_guest();
	
	
	return $g_static_users[$uid];
}

function user_delete($uid) {
	global $conf, $g_static_users;
	
	
	$user = user_read($uid);
	if(empty($user)) return NULL;
	
	// 清理主题帖
	$threadlist = mythread_find_by_uid($uid, 1, 1000);
	foreach($threadlist as $thread) {
		thread_delete($thread['tid']);
	}
	
	// 清理回帖
	post_delete_by_uid($uid);
	
	// 清理附件
	attach_delete_by_uid($uid);
	
	// 删除头像
	$user['avatar_path'] AND xn_unlink($user['avatar_path']);
	
	$r = user__delete($uid);
	
	!in_array($conf['cache']['type'], array('mysql', 'pdo_mysql')) AND cache_delete("user-$uid");
	if(isset($g_static_users[$uid])) unset($g_static_users[$uid]);
	
	// 全站统计
	runtime_set('users-', 1);
	
	
	return $r;
}

function user_find($cond = array(), $orderby = array(), $page = 1, $pagesize = 20) {
	global $g_static_users;
	
	$userlist = db_find('user', $cond, $orderby, $page, $pagesize);
	if($userlist) foreach ($userlist as &$user) {
		$g_static_users[$user['uid']] = $user;
		user_format($user);
	}
	
	return $userlist;
}

// ------------> 其他方法

function user_read_by_email($email) {
	global $g_static_users;
	
	$user = db_find_one('user', array('email'=>$email));
	user_format($user);
	$g_static_users[$user['uid']] = $user;
	
	return $user;
}

function user_read_by_username($username) {
	global $g_static_users;
	
	$user = db_find_one('user', array('username'=>$username));
	user_format($user);
	$g_static_users[$user['uid']] = $user;
	
	return $user;
}

function user_count($cond = array()) {
	
	$n = db_count('user', $cond);
	
	return $n;
}

function user_maxid($cond = array()) {
	
	$n = db_maxid('user', 'uid');
	
	return $n;
}

function user_format(&$user) {
	global $conf, $grouplist;
	if(empty($user)) return;

	
	
	$user['create_ip_fmt']   = long2ip(intval($user['create_ip']));
	$user['create_date_fmt'] = empty($user['create_date']) ? '0000-00-00' : date('Y-m-d', $user['create_date']);
	$user['login_ip_fmt']    = long2ip(intval($user['login_ip']));
	$user['login_date_fmt'] = empty($user['login_date']) ? '0000-00-00' : date('Y-m-d', $user['login_date']);
	
	$user['groupname'] = group_name($user['gid']);
	
	$dir = substr(sprintf("%09d", $user['uid']), 0, 3);
	
	$user['avatar_url'] = $user['avatar'] ? $conf['upload_url']."avatar/$dir/$user[uid].png?".$user['avatar'] : 'view/img/avatar.png';
	$user['avatar_path'] = $user['avatar'] ? $conf['upload_path']."avatar/$dir/$user[uid].png?".$user['avatar'] : '';

	$user['online_status'] = 1;
	
}


function user_guest() {
	global $conf;
	static $guest = NULL;
	
	
	if($guest) return $guest; // 返回引用，节省内存。
	$guest = array (
		'uid' => 0,
		'gid' => 0,
		'groupname' => lang('guest_group'),
		'username' => lang('guest'),
		'avatar_url' => 'view/img/avatar.png',
		'create_ip_fmt' => '',
		'create_date_fmt' => '',
		'login_date_fmt' => '',
		'email' => '',
		
		'threads' => 0,
		'posts' => 0,
	);
	
	
	return $guest; // 防止内存拷贝
}

// 根据积分来调整用户组
function user_update_group($uid) {
	global $conf, $grouplist;
	$user = user_read_cache($uid);
	if($user['gid'] < 100) return FALSE;
	
	
	
	// 遍历 credits 范围，调整用户组
	foreach($grouplist as $group) {
		if($group['gid'] < 100) continue;
		$n = $user['posts'] + $user['threads']; // 根据发帖数
		$n=$user['credits'];
		if($n > $group['creditsfrom'] && $n < $group['creditsto']) {
			if($user['gid'] != $group['gid']) {
				user_update($uid, array('gid'=>$group['gid']));
				return TRUE;
			}
		}
	}
	
	
	return FALSE;
}

// uids: 1,2,3,4 -> array()
function user_find_by_uids($uids) {
	
	$uids = trim($uids);
	if(empty($uids)) return array();
	$arr = explode(',', $uids);
	$r = array();
	foreach($arr as $_uid) {
		$user = user_read_cache($_uid);
		if(empty($user)) continue;
		$r[$user['uid']] = $user;
	}
	
	return $r;
}

// 获取用户安全信息
function user_safe_info($user) {
	
	unset($user['password']);
	unset($user['email']);
	unset($user['salt']);
	unset($user['password_sms']);
	unset($user['idnumber']);
	unset($user['realname']);
	unset($user['qq']);
	unset($user['mobile']);
	unset($user['create_ip']);
	unset($user['create_ip_fmt']);
	unset($user['create_date']);
	unset($user['create_date_fmt']);
	unset($user['login_ip']);
	unset($user['login_date']);
	unset($user['login_ip_fmt']);
	unset($user['login_date_fmt']);
	unset($user['logins']);
	
	return $user;
}


// 用户
function user_token_get() {
	global $time;
	$_uid = user_token_get_do();
	

	if(!$_uid) {
		//setcookie('bbs_token', '', $time - 86400, '');
	}
	
	
	
	return $_uid;
}

// 用户
function user_token_get_do() {
	global $time, $ip, $conf;
	$token = param('bbs_token');
	
	
	
	if(empty($token)) return FALSE;
	$tokenkey = md5(xn_key());
	$s = xn_decrypt($token, $tokenkey);
	if(empty($s)) return FALSE;
	$arr = explode("\t", $s);
	if(count($arr) != 4) return FALSE;
	list($_ip, $_time, $_uid, $_pwd) = $arr;
	//if($ip != $_ip) return FALSE;
	//if($time - $_time > 86400) return FALSE;
	// 检查密码是否被修改。
	if($time - $_time > 1800) {
		$user = user_read($_uid);
		if(empty($user)) return 0;
		if(md5($user['password']) != $_pwd) {
			return 0;
		}
	}
	
	
	
	
	
	return $_uid;	
}

// 设置 token，防止 sid 过期后被删除
function user_token_set($uid) {
	global $time, $conf;
	if(empty($uid)) return;
	$token = user_token_gen($uid);
	setcookie('bbs_token', $token, $time + 8640000, $conf['cookie_path']);
	
	
}

function user_token_clear() {
	global $time, $conf;
	setcookie('bbs_token', '', $time - 8640000, $conf['cookie_path']);
	
	
}

function user_token_gen($uid) {
	global $ip, $time, $conf;
	
	
	
	$user = user_read($uid);
	$pwd = md5($user['password']);
	$tokenkey = md5(xn_key());
	$token = xn_encrypt("$ip	$time	$uid	$pwd", $tokenkey);
	
	
	
	return $token;
}


// 前台登录验证
function user_login_check() {
	global $user;
	
	
	
	empty($user) AND http_location(url('user-login'));
	
	
}



// 获取用户来路
function user_http_referer() {
	
	$referer = param('referer'); // 优先从参数获取 | GET is priority
	empty($referer) AND $referer = array_value($_SERVER, 'HTTP_REFERER', '');
	
	$referer = str_replace(array('\"', '"', '<', '>', ' ', '*', "\t", "\r", "\n"), '', $referer); // 干掉特殊字符 strip special chars
	
	if(
		!preg_match('#^(http|https)://[\w\-=/\.]+/[\w\-=.%\#?]*$#is', $referer) 
		|| strpos($referer, 'user-login.htm') !== FALSE 
		|| strpos($referer, 'user-logout.htm') !== FALSE 
		|| strpos($referer, 'user-create.htm') !== FALSE 
		|| strpos($referer, 'user-setpw.htm') !== FALSE 
		|| strpos($referer, 'user-resetpw_complete.htm') !== FALSE
	) {
		$referer = './';
	}
	
	return $referer;
}

function user_auth_check($token) {
	
	global $time;
	$auth = param(2);
	$s = decrypt($auth);
	empty($s) AND message(-1, lang('decrypt_failed'));
	$arr = explode('-', $s);
	count($arr) != 3 AND message(-1, lang('encrypt_failed'));
	list($_ip, $_time, $_uid) = $arr;
	$_user = user_read($_uid);
	empty($_user) AND message(-1, lang('user_not_exists'));
	$time - $_time > 3600 AND message(-1, lang('link_has_expired'));
	
	return $_user;
}




?>