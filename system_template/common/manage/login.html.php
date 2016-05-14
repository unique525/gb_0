<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{system_name}</title>
<link href="/system_template/{template_name}/images/common.css" rel="stylesheet" type="text/css" />
<link href="/system_template/{template_name}/images/manage/login.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="/system_js/common.js"></script>
<script type="text/javascript" src="/system_js/manage/login.js"></script>
</head>
<body onkeydown="submitKeyClick('btn_sub');" style="">
    <form id="login_form" action="/default.php?mod=manage&a=login" method="post">
        <div id="div_login_logo_bg"><div id="div_login_logo"> <span></span></div></div>
        <div id="div_login_box">
            <div id="div_manage_user_name" class="text_box">
                <label for="manage_user_name">帐号：</label><input id="manage_user_name" name="manage_user_name" class="input_box1" type="text" value="" maxlength="40" />
            </div>
            <div id="div_manage_user_pass" class="text_box">
                <label for="manage_user_pass">密码：</label><input id="manage_user_pass" name="manage_user_pass" class="input_box1" type="password" value="" maxlength="40" />
            </div>
            <div id="div_manage_user_otp" class="text_box" style="display:none">
                <label for="manage_user_otp_number">令牌：</label><input id="manage_user_otp_number" name="manage_user_otp_number" class="input_box1" type="text" value="" maxlength="6" />
            </div>
            <div id="div_sms"></div>
            <div id="div_verify_code" class="text_box">
                <label for="txt_check_code">验证：</label><input id="txt_check_code" name="txt_check_code" class="input_box2" type="text" value="" maxlength="5" /> <img src="/default.php?mod=common&a=gen_gif_verify_code&sn=manage_login" title="" alt="" onclick="this.setAttribute('src','/default.php?mod=common&a=gen_gif_verify_code&sn=manage_login&n='+Math.random());" style="cursor: pointer;" />
            </div>
            
            <div id="btn_box" class="btn_box">
                <input type="button" id="btn_sub" class="btn_login1" value="登 录" />
            </div>
        
        </div>
        <div id="div_tips"></div>
    <div id="div_result" style="display: none;"></div>
    </form>
</body>
</html>