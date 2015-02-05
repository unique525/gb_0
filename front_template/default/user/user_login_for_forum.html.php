<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{cfg_ForumIeTitle} 会员登陆</title>
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/common.js"></script>
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript">
        var reUrl = Request["re_url"];
        $(function(){
            $("#btn_login").click(function(){
                var url = "/default.php?mod=user&a=async_login";
                var userAccount = $("#user_account").val();
                var userPass = $("#user_pass").val();
                if(userAccount != "" && userPass != ""){
                    $.ajax({
                        url:url,
                        data:{user_account:userAccount,user_pass:userPass,site_id:1},
                        dataType:"jsonp",
                        jsonp:"jsonpcallback",
                        success:function(data){
                            var result = data["result"];
                            if(result == -1){
                                alert("账号或密码错误");
                            }else if(result == -2){
                                alert("非法参数");
                            }else if(result > 0){
                                if(reUrl == undefined || reUrl.length == 0){
                                    window.location.href="/default.php?mod=user&a=homepage&site_id=1";
                                }else{
                                    location.href=decodeURIComponent(reUrl);
                                }
                            }else{
                                alert(result);
                            }
                        }
                    });
                }else{
                    alert("请填写用户名和密码");
                }
            });
        });
    </script>
    {common_head}
</head>

<body>
<div class="wrapper" style="text-align:center;padding-top:20px;">
    <div class="clean"></div>
    <table width="200px" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td>
                <div class="login">
                    <div class="login_cont">
                        <table width="250px" border="0" cellspacing="0" cellpadding="0">
                            <tr><td>邮箱/验证手机/用户名</td></tr>
                            <tr>
                                <td height="30"><input id="user_account" name="" type="text" class="input1"/></td>
                            </tr>
                            <tr><td style="padding-left: 50px" align="left">登录密码</td></tr>
                            <tr>
                                <td height="30"><input id="user_pass" name="" type="password" class="input1"/></td>
                            </tr>
                            <tr>
                                <td height="30"><input id="btn_login" name="" type="button" class="btn2" style="cursor:pointer" value="提交"/></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
