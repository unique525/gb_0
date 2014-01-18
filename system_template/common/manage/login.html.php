<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="/system_template/{template_name}/images/common.css" rel="stylesheet" type="text/css" />
<link href="/system_template/{template_name}/images/manage/login.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="/system_js/common.js"></script>
<script type="text/javascript" src="/system_js/manage/login.js"></script>
    <style type="text/css">
        body { font-family: "微软雅黑"; background:#2F6368; }
        input { font-family: "微软雅黑";border:none;border:0;}
    </style>
</head>
<body onkeydown="submitKeyClick('btn_sub');" style="">
    <form id="login_form" action="default.php?mod=manage&a=login" method="post">
        <div id="div_login_logo_bg"><div id="div_login_logo">iCMS <span>v2.0</span></div></div>
        <div id="div_login_box">
            <div id="div_manage_user_name" class="text_box">
                帐号：<input id="manage_user_name" name="manage_user_name" class="input_box1" type="text" value="" maxlength="40" />
            </div>
            <div id="div_manage_user_pass" class="text_box">
                密码：<input id="manage_user_pass" name="manage_user_pass" class="input_box1" type="password" value="" maxlength="40" />
            </div>
            <div id="div_manage_user_otp" class="text_box" style="display:none">
                令牌：<input id="manage_user_otp_number" name="manage_user_otp_number" class="input_box1" type="text" value="" maxlength="6" />
            </div>
            <div id="div_sms"></div>
            <div id="div_verify_code" class="text_box">
                验证：<input id="txt_check_code" name="txt_check_code" class="input_box2" type="text" value="" maxlength="5" /> <img src="/default.php?mod=common&a=gen_gif_verify_code&sn=manage_login" title="" alt="" onclick="this.setAttribute('src','/default.php?mod=common&a=gen_gif_verify_code&sn=manage_login&n='+Math.random());" style="cursor: pointer;" />
            </div>
            
            <div class="btn_box">
                <input type="button" id="btn_sub" class="btn_login1" value="登 录" />
            </div>
        
        </div>
        <div id="div_tips"></div>
    <div id="div_result" style="display: none;"></div>
    </form>
</body>
</html>