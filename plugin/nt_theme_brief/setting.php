<?php
!defined('DEBUG') AND exit('Access Denied.');
if ($method == 'GET') {
    $kv = kv_get('nt_theme_brief');
    $input['transparence'] = form_text('transparence',$kv['transparence']);
    $input['agreebbrule'] = form_text('agreebbrule',$kv['agreebbrule']);
    include _include(APP_PATH.'plugin/nt_theme_brief/setting.htm');
} else {
    $kv = array();
    $kv['transparence'] = param('transparence');
    $kv['agreebbrule'] = param('agreebbrule');
    kv_set('nt_theme_brief', $kv);
    message(0,lang('save_successfully'));
}
?>