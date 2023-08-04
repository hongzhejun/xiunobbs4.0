<?php 
!defined('DEBUG') AND exit('Access Denied.');

if($method=='POST'){
    if(param('csrf')!=session_id()){message(0,lang('illegal_request'));}
	$post = array();
    $post['announcement']=param('announcement','0');
    $post['index_anouncement']=param('index_anouncement','', FALSE);
    
    $post['background_url']=trim(param('background_url','',false));
    $post['logo_url']=trim(param('logo_url','',false));
    $post['register_img_url']=trim(param('register_img_url','',false));
    $post['right_img']=param('right_img','0');
    $post['right_img_title']=trim(param('right_img_title','',false));
    $post['right_img_src']=trim(param('right_img_src','',false));
    $post['right_img_url']=trim(param('right_img_url','',false));
    
    $post['pwd_complexity']=param('pwd_complexity','0');
    $post['agreebbrule_url']=trim(param('agreebbrule_url','',false));
    $post['user_create_end_referer']=trim(param('user_create_end_referer','',false));
    $post['header_link']=param('header_link','0');
    $post['header_link_url']=trim(param('header_link_url','',false));
    
    $post['outer_style']=trim(preg_replace('/\s(?=\s)/','\\1',param('outer_style','',false)));
    $post['footer_script']=trim(param('footer_script','',false));
    $post['header_html']=trim(param('header_html','',false));
    $post['footer_html']=trim(param('footer_html','',false));
    $post['footer_top']=trim(param('footer_top','',false));
    $post['ShowXiuno']=param('ShowXiuno','0');
    $post['powered_by']=trim(param('powered_by','',false));
    $post['ShowProcessed']=param('ShowProcessed','0');
    $post['footer_down']=trim(param('footer_down','',false));
    
    kv_set('cf_theme_modern',$post);
	message(0, lang('save_successfully'));
}
elseif($method=='GET'){
    $json=json_decode(file_get_contents(APP_PATH.'plugin/cf_theme_modern/conf.json'),true);
    include _include(ADMIN_PATH.'view/htm/header.inc.htm');
    echo '<div class="row"><div class="col-lg-10 offset-lg-1"><div class="card"><div class="card-body">';
    $cf_theme_modern=kv_get('cf_theme_modern');

    echo '<div class="btn-group">
<a class="btn btn-light active" id="cf-index" href="javascript:;" onclick="cf(\'index\');">版本</a>
<a class="btn btn-light" id="cf-announcement" href="javascript:;" onclick="cf(\'announcement\');">公告</a>
<a class="btn btn-light" id="cf-picture" href="javascript:;" onclick="cf(\'picture\');">图片</a>
<a class="btn btn-light" id="cf-features" href="javascript:;" onclick="cf(\'features\');">功能</a>
<a class="btn btn-light" id="cf-code" href="javascript:;" onclick="cf(\'code\');">自定义代码</a>
</div><hr />
<form action="'.url("plugin-setting-cf_theme_modern").'" method="post" id="form">
<span id="cf_index">
<div><b>当前版本</b>：'.$json['version'].'</div>
<div><b>检查更新</b>：<a href="https://cloudatabases.com/thread-63.htm" target="_blank">https://cloudatabases.com/thread-63.htm</a></div>
</span>

<span id="cf_announcement" style="display:none;">
<div><b>是否启用</b></div>
<div>'.form_radio_yes_no('announcement',empty($cf_theme_modern['announcement'])?0:1).'</div>
<hr />
<div><b>'.lang('index_anouncement').'</b></div>
<div>'.form_textarea('index_anouncement',empty($cf_theme_modern['index_anouncement'])?'':$cf_theme_modern['index_anouncement'], '100%', 300).'</div>
<span class="text-grey small">&lt;li style="list-style-type: disc;"&gt;<br>
&nbsp;&nbsp;&lt;a href="./thread-1.htm" target="_blank"&gt;《论坛用户守则》&lt;/a&gt;<br>
&lt;/li&gt;</span>
</span>

<span id="cf_picture" style="display:none;">
网站背景图片地址（为空时默认灰白色）
<div>'.form_text('background_url',empty($cf_theme_modern['background_url'])?'':$cf_theme_modern['background_url']).'</div>
<span class="text-grey small">/plugin/cf_theme_modern/images/bg.png</span>
<hr />
Logo图片地址（为空时读取logo_pc_url路径）
<div>'.form_text('logo_url',empty($cf_theme_modern['logo_url'])?'':$cf_theme_modern['logo_url']).'</div>
<span class="text-grey small">/plugin/cf_theme_modern/images/logo_bbs.png</span>
<hr />
登录注册页面自定义提示卡片（PC端）
<div>'.form_text('register_img_url',empty($cf_theme_modern['register_img_url'])?'':$cf_theme_modern['register_img_url']).'</div>
<span class="text-grey small">/plugin/cf_theme_modern/img/register_tips.png</span>
<hr />
右侧栏展示自定义图片信息（仅PC端可显示）
<div>'.form_radio_yes_no('right_img',empty($cf_theme_modern['right_img'])?0:1).'</div>
图片信息标题
<div>'.form_text('right_img_title',empty($cf_theme_modern['right_img_title'])?'':$cf_theme_modern['right_img_title']).'</div>
<span class="text-grey small">赞助商</span>
<br>
右侧图片文件地址
<div>'.form_text('right_img_src',empty($cf_theme_modern['right_img_src'])?'':$cf_theme_modern['right_img_src']).'</div>
<span class="text-grey small">/plugin/cf_theme_modern/images/5.jpg</span>
<br>
右侧图片点击后跳转超链接地址
<div>'.form_text('right_img_url',empty($cf_theme_modern['right_img_url'])?'':$cf_theme_modern['right_img_url']).'</div>
<span class="text-grey small">https://cloudatabases.com</span>
</span>

<span id="cf_features" style="display:none;">
注册密码复杂度（大写字母、小写字母、数字、特殊字符、至少8个字符）
<div>'.form_text('pwd_complexity',empty($cf_theme_modern['pwd_complexity'])?'':$cf_theme_modern['pwd_complexity']).'</div>
<span class="text-grey small">请填写需要满足的条件数量，填写范围 0 ~ 5 ，例如：3</span>
<hr />
网站服务条款地址（为空时关闭该功能）
<div>'.form_text('agreebbrule_url',empty($cf_theme_modern['agreebbrule_url'])?'':$cf_theme_modern['agreebbrule_url']).'</div>
<span class="text-grey small">https://cloudatabases.com/thread-1.htm</span>
<hr />
用户注册完毕后跳转页面地址（为空时默认跳转首页）
<div>'.form_text('user_create_end_referer',empty($cf_theme_modern['user_create_end_referer'])?'':$cf_theme_modern['user_create_end_referer']).'</div>
<span class="text-grey small">https://cloudatabases.com</span>
<hr />
导航栏自定义链接
<div>'.form_radio_yes_no('header_link',empty($cf_theme_modern['header_link'])?0:1).'</div>
导航栏自定义链接地址
<div>'.form_textarea('header_link_url',empty($cf_theme_modern['header_link_url'])?'':$cf_theme_modern['header_link_url'], '100%', 100).'</div>
<span class="text-grey small">&lt;li class="item-link1"&gt;&lt;a class="Button Button--link" rel="noopener noreferrer" target="_blank" title="关于" external href="/about.htm">关于&lt;/a&gt;&lt;/li&gt;</span>
</span>

<span id="cf_code" style="display:none;">
<style>
.site-css .form-control{padding: 20px 10px;border-radius: 0;border:1px solid #03A9F4}
.site-css .form-control:focus { box-shadow:0px 0px 3px 0px #4CBEF1 inset;background: #FAFDFF;}
.site-css p{color: #FFFFFF;background: #03A9F4;margin: 0;padding: 5px 10px 5px;}
.site-js{ background: #000 ;}
.site-js .form-control{padding: 5px 10px;box-shadow:none;background: transparent;border-color: transparent;border-radius:0;color:#5ce638;font-size: 12px;font-family:courier new, sans-serif;}
.site-js .form-control:focus {box-shadow:none;border-color: transparent;color: #5ce638;}
.site-js p{ color: #FF0B7A; background:#353535;margin: 0;padding: 5px 10px 5px;}
.bx-e{ padding: 0;border-radius: 0.3rem; overflow: hidden;}
b.z-js{background: #f0ad4e;color:#fff;border:none;}
b.z-jq{background: #6AB3DE;color:#fff;border:none;}
</style>
<div><b>外部样式</b>（论坛全局CSS）</div>
<div class="col-sm-10 site-css bx-e">
<p class="small">&lt;style type="text/css"&gt;</b></p>
'.form_textarea('outer_style',empty($cf_theme_modern['outer_style'])?'':$cf_theme_modern['outer_style'], '100%', 200).'
<p class="small">&lt;/style&gt;</p>
</div>
<div class="col-sm-10">
<span class="text-danger">请填写规范的CSS层叠样式代码<b class="text-info">（此处填写的代码在所有页面生效）</b></span>
<br>代码示例：
<span class="text-grey small">
body{background:#FFFAFA}   /*设置网站背景色：Snow*/
</span>
</div>
<hr />
<div><b class="z-js">JavaScript</b>&nbsp;<b class="z-jq">jQuery</b>（论坛全局JS）</div>
<div class="col-sm-10 site-js bx-e">
<p class="small">&lt;script type="text/javascript"&gt;</b></p>
'.form_textarea('footer_script',empty($cf_theme_modern['footer_script'])?'':$cf_theme_modern['footer_script'], '100%', 200).'
<p class="small">&lt;/script&gt;</p>
</div>
<div class="col-sm-10">
<span class="text-danger">请填写规范的JavaScript 或 jQuery代码<b class="text-info">（此处填写的代码在所有页面生效）</b></span>
<br>代码示例：<br>
<span class="text-grey small">
window.onfocus = function () {<br>
 document.title = \'恢复正常了...\';<br>
};<br>
window.onblur = function () {<br>
 document.title = \'快回来~页面崩溃了\';<br>
};
</span>
</div>
<hr />
<div><b>站点顶部自定义HTML</b>（可用于放外联文件，或自定义JS/CSS代码）</div>
'.form_textarea('header_html',empty($cf_theme_modern['header_html'])?'':$cf_theme_modern['header_html'], '100%', 100).'
<hr />
<div><b>站点底部自定义HTML</b>（可用于放外联文件，或自定义JS/CSS代码）</div>
'.form_textarea('footer_html',empty($cf_theme_modern['footer_html'])?'':$cf_theme_modern['footer_html'], '100%', 100).'
<hr />
<div><b>页脚上方自定义HTML</b></div>
'.form_textarea('footer_top',empty($cf_theme_modern['footer_top'])?'':$cf_theme_modern['footer_top'], '100%', 100).'
代码示例：
<span class="text-grey small">
<br>Copyright @ '.date("Y").' '.$_SERVER['SERVER_NAME'].'
</span>
<p><hr>
<div><b>页脚显示“Powered by Xiuno”</b></div>
<div>'.form_radio_yes_no('ShowXiuno',empty($cf_theme_modern['ShowXiuno'])?0:1).'</div>
将“Xiuno”替换成自定义文字（为空时关闭该功能）
<div>'.form_text('powered_by',empty($cf_theme_modern['powered_by'])?'':$cf_theme_modern['powered_by']).'</div>
<span class="text-grey small">cloudatabases.com</span>
<hr>
<div><b>页脚显示性能数据</b>（Processed: X.XXX, SQL: XX）</div>
<div>'.form_radio_yes_no('ShowProcessed',empty($cf_theme_modern['ShowProcessed'])?0:1).'</div>
<p><hr>
<div><b>页脚下方自定义HTML</b></div>
'.form_textarea('footer_down',empty($cf_theme_modern['footer_down'])?'':$cf_theme_modern['footer_down'], '100%', 100).'
代码示例：
<span class="text-grey small">
<br>
&lt;h5 class="beian" style="font-size: 0.83em;"&gt;<br>
&lt;a style="color: #868e96;" href="http://beian.miit.gov.cn/" target="_blank"&gt;京ICP备XXXXXXXXX号-XX&lt;/a&gt;<br>
&lt;a&gt; / &lt;/a&gt;<br>
&lt;a target="_blank" href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=XXXXXXXXXXXXXX" style="text-decoration:none;"&gt;<br>
&lt;img src="./plugin/cf_theme_modern/img/110.png" style="padding-left: 2px; padding-right: 3px; vertical-align: bottom;"&gt;&lt;/a&gt;<br>
京公网安备 XXXXXXXXXXXXXX号
&lt;/h5&gt;
</span>

</span>
<hr />
<input name="csrf" value="'.session_id().'" style="display:none;" />
<div class="form-group row">
	<label class="col-sm-2 form-control-label"></label>
	<div class="col-sm-12">
		<button type="submit" class="btn btn-primary btn-block" id="submit" data-loading-text="'.lang('submiting').'...">'.lang('confirm').'</button>
		<a role="button" class="btn btn-secondary btn-block mt-1" href="javascript:history.back();">'.lang('back').'</a>
	</div>
</div>
</form>

<script>
function cf(page){
document.querySelector(\'#cf-index\').classList.add(page==\'index\'?\'active\':\'cf\');
document.querySelector(\'#cf-index\').classList.remove(page==\'index\'?\'cf\':\'active\');
document.querySelector(\'#cf-announcement\').classList.add(page==\'announcement\'?\'active\':\'cf\');
document.querySelector(\'#cf-announcement\').classList.remove(page==\'announcement\'?\'cf\':\'active\');
document.querySelector(\'#cf-picture\').classList.add(page==\'picture\'?\'active\':\'cf\');
document.querySelector(\'#cf-picture\').classList.remove(page==\'picture\'?\'cf\':\'active\');
document.querySelector(\'#cf-features\').classList.add(page==\'features\'?\'active\':\'cf\');
document.querySelector(\'#cf-features\').classList.remove(page==\'features\'?\'cf\':\'active\');
document.querySelector(\'#cf-code\').classList.add(page==\'code\'?\'active\':\'cf\');
document.querySelector(\'#cf-code\').classList.remove(page==\'code\'?\'cf\':\'active\');
document.querySelector(\'#cf_index\').style.display=(page==\'index\')?\'\':\'none\';
document.querySelector(\'#cf_announcement\').style.display=(page==\'announcement\')?\'\':\'none\';
document.querySelector(\'#cf_picture\').style.display=(page==\'picture\')?\'\':\'none\';
document.querySelector(\'#cf_features\').style.display=(page==\'features\')?\'\':\'none\';
document.querySelector(\'#cf_code\').style.display=(page==\'code\')?\'\':\'none\';
};
function cf_filter(text){
var map={\'&\':\'&amp;\',\'<\':\'&lt;\',\'>\':\'&gt;\',\'"\':\'&quot;\',"\'":\'&#039;\'};
return text.replace(/[&<>"\']/g,function(m){return map[m];});
};
</script>

';
echo '</div><p class="ml-4 text-small text-muted">本插件由<a target=\'_blank\' href=\'https://cloudatabases.com/\'>云库论坛</a>原创。</p></div></div></div>';
include _include(ADMIN_PATH.'view/htm/footer.inc.htm');
echo '<script>
var jform = $("#form");
var jsubmit = $("#submit");
var referer = \''.url("plugin-setting-cf_theme_modern").'\';
jform.on(\'submit\', function(){
	jform.reset();
	jsubmit.button(\'loading\');
	var postdata = jform.serialize();
	$.xpost(jform.attr(\'action\'), postdata, function(code, message) {
		if(code == 0) {console.log("dd");
			$.alert(message);
			jsubmit.text(message).delay(2000).button(\'reset\').location(referer);
			return;
		} else {
			$.alert(message);
			jsubmit.button(\'reset\');
		}
	});
	return false;
});
</script>';
}
?>