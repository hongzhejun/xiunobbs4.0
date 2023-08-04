<?php

!defined('DEBUG') AND exit('Access Denied.');

$action = param(1);



$user = user_read($uid);
user_login_check();

$header['mobile_title'] = $user['username'];
$header['mobile_linke'] = url("my");

is_numeric($action) AND $action = '';

$active = $action;



if(empty($action)) {
	
	$header['title'] = lang('my_home');
	
	
	
	include _include(APP_PATH.'view/htm/my.htm');
	
/*	
} elseif($action == 'profile') {
	
	if($ajax) {
		// user_safe_info($user);
		message(0, $user);
	} else {
		include _include(APP_PATH.'view/htm/my_profile.htm');
	}
*/
	
} elseif($action == 'password') {
	
	if($method == 'GET') {
		
		
		
		include _include(APP_PATH.'view/htm/my_password.htm');
		
	} elseif($method == 'POST') {
		
		
		
		$password_old = param('password_old');
		$password_new = param('password_new');
		$password_new_repeat = param('password_new_repeat');
		$password_new_repeat != $password_new AND message(-1, lang('repeat_password_incorrect'));
		md5($password_old.$user['salt']) != $user['password'] AND message('password_old', lang('old_password_incorrect'));
		$password_new = md5($password_new.$user['salt']);
		$r = user_update($uid, array('password'=>$password_new));
		$r === FALSE AND message(-1, lang('password_modify_failed'));
		
		
		message(0, lang('password_modify_successfully'));
		
	}
	

} elseif($action == 'thread') {

	
	
	$page = param(2, 1);
	$pagesize = 20;
	$totalnum = $user['threads'];
	
	
	
	$pagination = pagination(url('my-thread-{page}'), $totalnum, $page, $pagesize);
	$threadlist = mythread_find_by_uid($uid, $page, $pagesize);
	
	
	
	include _include(APP_PATH.'view/htm/my_thread.htm');

	
} elseif($action == 'avatar') {
	
	if($method == 'GET') {
		
		
		
		include _include(APP_PATH.'view/htm/my_avatar.htm');
	
	} else {
		
		
		
		$width = param('width');
		$height = param('height');
		$data = param('data', '', FALSE);
		
		empty($data) AND message(-1, lang('data_is_empty'));
		$data = base64_decode_file_data($data);
		$size = strlen($data);
		$size > 40000 AND message(-1, lang('filesize_too_large', array('maxsize'=>'40K', 'size'=>$size)));
		
		$filename = "$uid.png";
		$dir = substr(sprintf("%09d", $uid), 0, 3).'/';
		$path = $conf['upload_path'].'avatar/'.$dir;
		$url = $conf['upload_url'].'avatar/'.$dir.$filename;
		!is_dir($path) AND (mkdir($path, 0777, TRUE) OR message(-2, lang('directory_create_failed')));
		
		
		file_put_contents($path.$filename, $data) OR message(-1, lang('write_to_file_failed'));
		
		user_update($uid, array('avatar'=>$time));
		
		
		
		message(0, array('url'=>$url));
		
	}
}

elseif($action == 'credits') {
    if($method == 'GET')
        include _include(APP_PATH.'plugin/tt_credits/view/htm/my_credits.htm');
}elseif($action == 'purchased') {
    if($method == 'GET'){
        $pagesize = 20;
        $page = param(2, 1);
        $cond = array('uid'=>$uid);
        $threadlist = credits_thread_purchased_find_by_uid($uid, $page, $pagesize);
        $pagination = pagination(url("my-purchased-{page}"), credits_purchased_count($cond), $page, $pagesize);
        include _include(APP_PATH.'plugin/tt_credits/view/htm/my_purchased.htm');
    }
}elseif($action == 'trade') {
    if($method == 'GET')
        include _include(APP_PATH.'plugin/tt_credits/view/htm/my_trade.htm');
    elseif($method=='POST'){
        $op = param('op');
        if($op=='n'){
            $set=setting_get('tt_credits');$e_rmb = param('e_rmb'); $my_rmbs=$user['rmbs'];$my_golds=$user['golds'];$min=$set['min'];$e_rmb_raw=$e_rmb;
            $e_rmb *= $set['exchange_n'];
            if($e_rmb<$min) {message(-1, '最低兑换金额：¥'.($min/100.0).'，您兑换的金额不足。');die();}
            if($e_rmb<=0 ) {message(-1, lang('ERROR'));die();}
            preg_replace('/[^0-9-]+/','',$e_rmb);
            if($my_rmbs<$e_rmb) {message(-1, lang('credit_no_enough'));die();}
            if(empty($uid)||empty($e_rmb)){message(-1, "ERROR");die();}
            $recent_query = db_find_one('user_pay',array('uid'=>$uid,'type'=>'6'),array('time'=>-1));
            $now_time = time();
            if($now_time-$recent_query['time']<=600) {message(-1, "每10分钟只能兑换一次，您兑换过于频繁！");die();}
            $my_golds+= $e_rmb_raw;
            $my_rmbs -= $e_rmb;
            db_insert('user_pay',array('uid'=>$uid,'status'=>1,'num'=>$e_rmb,'type'=>'6','credit_type'=>'3','code'=>'','time'=>time()));
            user_update($user['uid'],array('rmbs'=>$my_rmbs,'golds'=>$my_golds));
            user_update_group($user['uid']);
            message(0, lang('update_successfully'));
        }elseif($op=='c'){
            $set=setting_get('tt_credits');$e_golds=param('e_golds_c'); $my_golds = $user['golds'];$my_rmbs=$user['rmbs'];$min=$set['min'];$e_golds_raw=$e_golds;
            $e_golds*= $set['exchange_c'];
            if(empty($uid)||empty($e_golds)){message(-1, "ERROR");die();}
            if($e_golds<$min*$set['exchange_c']) {message(-1, '最低兑换金币：'.($min*$set['exchange_c']).'，您兑换的金额不足。');die();}
            if($e_golds<=0 ){message(-1, lang('ERROR'));die();}
            preg_replace('/[^0-9-]+/','',$e_golds);
            if($my_golds<$e_golds){message(-1, lang('credit_no_enough'));die();}
            $recent_query = db_find_one('user_pay',array('uid'=>$uid,'type'=>'6'),array('time'=>-1));
            $now_time = time();
            if($now_time-$recent_query['time']<=600) {message(-1, "每10分钟只能兑换一次，您兑换过于频繁！");die();}
            $my_golds-=$e_golds;
            $my_rmbs+=$e_golds_raw;
            db_insert('user_pay',array('uid'=>$uid,'status'=>1,'num'=>$e_golds,'type'=>'6','credit_type'=>'2','code'=>'','time'=>time()));
            user_update($user['uid'],array('rmbs'=>$my_rmbs,'golds'=>$my_golds));
            user_update_group($user['uid']);
            message(0, lang('update_successfully'));
        }elseif($op=='t'){
            $to_username=param('trans_username'); $to_num=param('trans_num'); $credits_type=param('trans_credits');
            if($to_num<=0) {message(-1, "请输入正数!");die();}
            if(empty($to_username)) {message(-1, "用户名不能为空!");die();}
            if(empty($credits_type)) {message(-1, "积分段为空!");die();}
            if(db_count('user',array('username'=>$to_username))<=0){message(-1, "用户不存在!");die();}
            if($user['username']==$to_username) {message(-1, "不能自己给自己转账！");die();}
            $credits_name =get_credits_name_by_type($credits_type);
            $to_user = db_find_one('user',array('username'=>$to_username));
            if($user[$credits_name]<$to_num) {message(-1, "您的余额不足,请充值!");die();}
            db_update('user',array('username'=>$to_username),array($credits_name.'+'=>$to_num));
            db_update('user',array('uid'=>$uid),array($credits_name.'-'=>$to_num));
            db_insert('user_pay',array('uid'=>$to_user['uid'],'status'=>1,'num'=>$to_num,'type'=>13,'credit_type'=>$credits_type,'time'=>time(),'code'=>''));
            db_insert('user_pay',array('uid'=>$uid,'status'=>1,'num'=>$to_num,'type'=>12,'credit_type'=>$credits_type,'time'=>time(),'code'=>''));
            message(0,'转账成功!');
        }
    }
}
elseif($action == 'record') {
    if($method == 'GET')
        include _include(APP_PATH.'plugin/tt_credits/view/htm/my_record.htm');
}

?>