<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>会员中心</title>
    <link href="/images/common_css.css" rel="stylesheet" type="text/css"/>
    <link href="/images/user_layout.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .right {
            cursor: pointer
        }
    </style>
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/front_js/common.js"></script>
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui-1.8.2.custom.min.js"></script>

    <script type="text/javascript">

        $(function(){
            $("#sub").click(function(){
                var oldPassword = $("#oldpassword").val();
                var newPassword = $("#newpassword").val();
                var confirmPassword = $("#surepassword").val();
                if(newPassword  == confirmPassword){
                    $.ajax({
                        url:"/default.php?mod=user&a=modify_user_pass",
                        data: {old_password:oldPassword,new_password:newPassword},
                        dataType:"jsonp",
                        jsonp:"jsonpcallback",
                        success:function(data){
                            alert(data["result"]);
                            if(data["result"] > 0){
                                alert("修改成功");
                                window.location.href="/default.php?mod=user&a=modify_pass";
                            }else if(data["result"] > -1){
                                alert("网络错误，请联系管理员");
                            }else{
                                alert("您输入的旧密码错误");
                            }
                        }
                    });
                }else{
                    alert("确定密码不正确，请重新输入");
                }
            });
        });
    </script>
</head>

<body>

<pre_temp id="4"></pre_temp>
<div class="clean"></div>
<pre_temp id="12"></pre_temp>
<div class="wrapper">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width="193" valign="top" height="750">
                <pre_temp id="6"></pre_temp>
            </td>
            <td width="1" bgcolor="#D4D4D4"></td>
            <td width="1006" valign="top">
                <div class="rightbar">
                    <div class="rightbar2"><a href="/">星滋味首页</a> ><a href="/default.php?mod=user&a=homepage">会员中心</a>>修改密码</div>
                </div>
                <div style="padding:20px 50px;">
                    <table border="0" cellpadding="0" cellspacing="0" style="font-size:14px; font-family:'微软雅黑';">
                    <tr>
                        <td width="100" rowspan="3" align="right"></td>
                        <td height="50" align="right">旧密码：</td>
                        <td><input name="oldpassword" type="password" class="input5" id="oldpassword" value=""/></td>
                    </tr>
                    <tr>
                        <td height="50" align="right">新密码：</td>
                        <td><input name="newpassword" type="password" class="input5" id="newpassword" value=""/></td>
                    </tr>
                    <tr>
                        <td height="50" align="right">确认密码：</td>
                        <td><input type="password" class="input5" id="surepassword" value=""/></td>
                    </tr>
                    <tr>
                        <td align="right"></td>
                        <td height="50" align="right"> </td>
                        <td><A class="Btn23H_orangeA vTop"
                               onclick="return doSubmit(this);"  id="sub" href="javascript:void(0);">确　定</A> </td>
                    </tr>
    </table>
                </div>
            </td>
        </tr>
    </table>
</div>
<div class="footerline"></div>
<div class="wrapper">
    <div class="footerleft">
        <div class="cont">
            <div><img src="/images/footergwzn.png" width="79" height="79"/></div>
            <b>交易条款</b><br/>
            <a href="" target="_blank">购物流程</a><br/>
            <a href="" target="_blank">发票制度</a><br/>
            <a href="" target="_blank">会员等级</a><br/>
            <a href="" target="_blank">积分制度</a><br/><br/>
        </div>
    </div>
    <div class="footerleft">
        <div class="cont">
            <div><img src="/images/footerpsfw.png" width="79" height="79"/></div>
            <b>配送服务</b><br/>
            <a href="" target="_blank">配送说明</a><br/>
            <a href="" target="_blank">配送范围</a><br/>
            <a href="" target="_blank">配送状态查询</a><br/><br/><br/>
        </div>
    </div>
    <div class="footerleft">
        <div class="cont">
            <div><img src="/images/footerzffs.png" width="79" height="79"/></div>
            <b>支付方式</b><br/>
            <a href="" target="_blank">支付宝支付</a><br/>
            <a href="" target="_blank">银联在线支付</a><br/>
            <a href="" target="_blank">货到付款</a><br/><br/><br/>
        </div>
    </div>
    <div class="footerleft">
        <div class="cont">
            <div><img src="/images/footershfw.png" width="79" height="79"/></div>
            <b>售后服务</b><br/>
            <a href="" target="_blank">服务承诺</a><br/>
            <a href="" target="_blank">退换货政策</a><br/>
            <a href="" target="_blank">退换货流程</a><br/><br/><br/>
        </div>
    </div>
    <div class="footerright" style="padding-left:50px;">
        手机客户端下载
        <div><img src="/images/weixin.png" width="104" height="104"/></div>
    </div>
    <div class="footerright" style="padding-right:50px;">
        手机客户端下载
        <div><img src="/images/weixin.png" width="104" height="104"/></div>
    </div>
</div>
</body>
</html>
