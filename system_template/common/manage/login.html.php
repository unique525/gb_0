<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="/system_template/{templatename}/images/common.css" rel="stylesheet" type="text/css" />
<link href="/system_template/{templatename}/images/manage/login.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{rootpath}/system_js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="{rootpath}/system_js/common.js"></script>
<script type="text/javascript" src="{rootpath}/system_js/manage/login.js"></script>
    <style type="text/css">
        body { font-family: "微软雅黑"; background:#2F6368; }
        input { font-family: "微软雅黑";border:none;border:0;}
    </style>
</head>
<body onkeydown="submitkeyclick('btnsub');" style="">
    <form id="theform" action="default.php?mod=manage&a=login" method="post">
        <div id="loginlogobg"><div id="loginlogo">iCMS <span>v2.0</span></div></div>
        <div id="loginbox">            
            <div id="divAdminUserName" class="textbox">
                帐号：<input id="adminusername" name="adminusername" class="inputbox1" type="text" value="" maxlength="40" />
            </div>
            <div id="divAdminUserPass" class="textbox">
                密码：<input id="adminuserpass" name="adminuserpass" class="inputbox1" type="password" value="" maxlength="40" />
            </div>
            <div id="divAdminUserOtpPass" class="textbox" id="otptr" style="display:none">
                令牌：<input id="adminuserotppass" name="adminuserotppass" class="inputbox1" type="text" value="" maxlength="6" />
            </div>
            <div id="divsms"></div>
            <div id="divVerifyCode" class="textbox">
                验证：<input id="checkcode" name="checkcode" class="inputbox2" type="text" value="" maxlength="4" /> <img src="/default.php?mod=common&a=genverifycode&sn=managelogin" title="" alt="" onclick="this.setAttribute('src','/default.php?mod=common&a=genverifycode&sn=managelogin&n='+Math.random());" style="cursor: pointer;" />
            </div>
            
            <div class="btnbox">
                <input type="button" id="btnsub" class="btnlogin1" value="登 录" />
            </div>
        
        </div>
        <div id="divTips"></div>
    <div id="opresult" style="display: none;"></div>  
        
        
    </form>
</body>
</html>