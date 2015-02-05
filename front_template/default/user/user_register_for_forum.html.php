<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>会员注册</title>
<link type="text/css" href="/system_template/default/images/jquery_ui/jquery-ui.min.css" rel="stylesheet"/>
<style type="text/css">
    .ok1 {
        background: url(/system_design/ok.gif) no-repeat 160px;
    }

    .error1 {
        background: url(/system_design/error.gif) no-repeat 160px;
    }

    .error_span {
        color: #990000;
        font-weight: bold;
        padding-left: 25px;
        font-size: 12px
    }
</style>
<script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="/system_js/common.js"></script>
<script type="text/javascript" src="/system_js/jquery_ui/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
var canSubmit = true;

function subForm() {
    if ($("#user_name").val() == "") {
        $("#result_table").html("请输入用户名");
        $("#dialog_result_box").dialog("destroy");
        $("#dialog_result_box").dialog({
            buttons: {
                Ok: function () {
                    $(this).dialog("close");
                }
            }
        });
    } else if ($("#user_pass").val() == "") {
        $("#result_table").html("请输入密码");
        $("#dialog_result_box").dialog("destroy");
        $("#dialog_result_box").dialog({
            buttons: {
                Ok: function () {
                    $(this).dialog("close");
                }
            }
        });
    } else if ($("#confirm_pass").val() == "") {
        $("#result_table").html("请确认密码");
        $("#dialog_result_box").dialog("destroy");
        $("#dialog_result_box").dialog({
            buttons: {
                Ok: function () {
                    $(this).dialog("close");
                }
            }
        });
    } else if ($("#check_code").val() == "") {
        $("#result_table").html("请输入验证码");
        $("#dialog_result_box").dialog("destroy");
        $("#dialog_result_box").dialog({
            buttons: {
                Ok: function () {
                    $(this).dialog("close");
                }
            }
        });
    } else if (!$("#accept_item").prop('checked')) {
        $("#result_table").html("您未接受服务条款");
        $("#dialog_result_box").dialog("destroy");
        $("#dialog_result_box").dialog({
            buttons: {
                Ok: function () {
                    $(this).dialog("close");
                }
            }
        });
    } else {
        if (canSubmit) {
            $("#btnsubmit").attr("disabled", true);
            var param = $("#main_form").serialize();
            var url = "/default.php?mod=user&a=async_register";
            $.ajax({
                url: url,
                type: "post",
                data: param,
                dataType: "jsonp",
                jsonp: "jsonpcallback",
                success: function (data) {
                    var result = data["result"];
                    if (result == -1) {
                        $("#result_table").html("这个用户账号已经被注册了！");
                        $("#dialog_result_box").dialog("destroy");
                        $("#dialog_result_box").dialog({
                            buttons: {
                                Ok: function () {
                                    $(this).dialog("close");
                                }
                            }
                        });
                    } else if (result == -6) {
                        $("#result_table").html("格式不正确！");
                        $("#dialog_result_box").dialog("destroy");
                        $("#dialog_result_box").dialog({
                            buttons: {
                                Ok: function () {
                                    $(this).dialog("close");
                                }
                            }
                        });
                    } else if (result == -2) {
                        $("#result_table").html("这个邮箱已经被注册了！");
                        $("#dialog_result_box").dialog("destroy");
                        $("#dialog_result_box").dialog({
                            buttons: {
                                Ok: function () {
                                    $(this).dialog("close");
                                }
                            }
                        });
                    } else if (result == -3) {
                        $("#result_table").html("这个号码已经被注册了！");
                        $("#dialog_result_box").dialog("destroy");
                        $("#dialog_result_box").dialog({
                            buttons: {
                                Ok: function () {
                                    $(this).dialog("close");
                                }
                            }
                        });
                    } else if (result == -4) {
                        $("#result_table").html("参数不正确！");
                        $("#dialog_result_box").dialog("destroy");
                        $("#dialog_result_box").dialog({
                            buttons: {
                                Ok: function () {
                                    $(this).dialog("close");
                                }
                            }
                        });
                    } else if (result == -5) {
                        $("#result_table").html("注册失败！");
                        $("#dialog_result_box").dialog("destroy");
                        $("#dialog_result_box").dialog({
                            buttons: {
                                Ok: function () {
                                    $(this).dialog("close");
                                }
                            }
                        });
                    } else if (result == 1) {
                        window.location.href="/default.php";
                    } else {
                        $("#result_table").html("注册失败，未知错误，请联系管理员！");
                        $("#dialog_result_box").dialog("destroy");
                        $("#dialog_result_box").dialog({
                            buttons: {
                                Ok: function () {
                                    $(this).dialog("close");
                                }
                            }
                        });
                    }
                }
            });
        }
    }
    return false;
}
$(function () {
    var btnSubmit = $("#btn_sub");
    btnSubmit.click(function () {
        subForm();
    });

    $("#user_email").blur(function () {
        $("#span_user_email_error").text("");
        var user_email = $(this).val();
        if (user_email != "") {
            if (!checkEmail(user_email)) {
                $("#td_user_email").attr("class", "error1");
                $("#span_user_email_error").text("邮箱格式不正确！");
                $(this).val("");
            } else {
                var url = "/default.php?mod=user&a=async_check_repeat&site_id=1";
                $.ajax({
                    url: url,
                    data: {user_email: user_email},
                    dataType: "jsonp",
                    jsonp: "jsonpcallback",
                    success: function (data) {
                        var result = data["result"];
                        if (result == -4) {
                            $("#td_user_email").attr("class", "error1");
                            $("#span_user_email_error").text("非法参数");
                            $(this).val("");
                        } else if (result == -2) {
                            $("#td_user_email").attr("class", "error1");
                            $("#span_user_email_error").text("这个邮箱已经被注册了！");
                            $(this).val("");
                        }else if (result == 0) {
                            $("#td_user_email").attr("class", "ok1");
                            $("#span_user_email_error").text("");
                        }
                    }
                });
            }
        }
    });

    $("#user_name").blur(function () {
        $("#span_user_name_error").text("");
        var user_name = $(this).val();
        if (!/^[\u4e00-\u9fa5a-zA-Z0-9]+$/.test(user_name)) {
            $("#td_user_name").attr("class", "error1");
            $("#span_user_name_error").text("用户名格式不正确！");
            $(this).val("");
            canSubmit = false;
        } else {
            var url = "/default.php?mod=user&a=async_check_repeat&site_id=1";
            $.ajax({
                url: url,
                data: {user_name: user_name},
                dataType: "jsonp",
                jsonp: "jsonpcallback",
                success: function (data) {
                    var result = data["result"];
                    if (result == -4) {
                        $("#td_user_name").attr("class", "error1");
                        $("#span_user_name_error").text("非法参数");
                        $(this).val("");
                    } else if (result == -1) {
                        $("#td_user_name").attr("class", "error1");
                        $("#span_user_name_error").text("这个用户账号已经被注册了！");
                        $(this).val("");
                    } else if (result == 0) {
                        $("#td_user_name").attr("class", "ok1");
                        $("#span_user_name_error").text("");
                    }
                }
            });
        }
    });

    $("#user_mobile").blur(function () {
        $("#span_user_mobile_error").text("");
        var user_mobile = $(this).val();

        if (user_mobile != "") {
            var url = "/default.php?mod=user&a=async_check_repeat&site_id=1";
            $.ajax({
                url: url,
                data: {user_mobile: user_mobile},
                dataType: "jsonp",
                jsonp: "jsonpcallback",
                success: function (data) {
                    var result = data["result"];
                    if (result == -4) {
                        $("#td_user_mobile").attr("class", "error1");
                        $("#span_user_mobile_error").text("非法参数");
                        $(this).val("");
                    } else if (result == -3) {
                        $("#td_user_mobile").attr("class", "error1");
                        $("#span_user_mobile_error").text("这个号码已经被注册了！");
                        $(this).val("");
                    } else if (result == 0) {
                        $("#td_user_mobile").attr("class", "ok1");
                        $("#span_user_mobile_error").text("");
                    }
                }
            });
        }
    });

    $("#user_pass").blur(function () {
        var user_pass = $("#user_pass").val();
        if (user_pass.length < 6) {
            $("#td_user_pass").attr("class", "error1");
            $("#span_pass_error").text("密码不能少于6位！");
            canSubmit = false;
        } else {
            $("#td_user_pass").attr("class", "ok1");
            $("#span_pass_error").text("");
        }
    });

    $("#confirm_pass").blur(function () {
        var user_pass = $("#user_pass").val();
        var confirm_pass = $("#confirm_pass").val();
        if (confirm_pass.length > 0) {
            if (confirm_pass != user_pass) {
                $("#td_confirm_pass").attr("class", "error1");
                $("#span_confirm_pass_error").text("两次输入的密码不一样！");
                canSubmit = false;
            } else {
                $("#td_confirm_pass").attr("class", "ok1");
                $("#span_confirm_pass_error").text("");
                canSubmit = true;
            }
        }
    });

    $("#check_code").blur(function () {
        var txtCheckCode = $("#check_code");
        var code = txtCheckCode.val();
        $.post("/default.php?mod=common&a=check_verify_code&sn=user_register&verify_code_type=0&verify_code_value=" + code, {
            opResult: $(this).html()
        }, function (xml) {
            var result = parseInt(xml);
            if (result == -1) {
                $("#td_check_code").attr("class", "error1");
                $("#span_check_code_error").text("验证码错误！");
                canSubmit = false;
            } else {
                $("#td_check_code").attr("class", "ok1");
                $("#span_check_code_error").text("");
                canSubmit = true;
            }
        });
    });
});
</script>
<style>
    .btn {
        background: none repeat scroll 0 0 #52596b;
        border: medium none;
        color: #ffffff;
        cursor: pointer;
        font-family: Microsoft YaHei,Verdana,serif;
        font-size: 14px;
        height: 28px;
        line-height: 28px;
        padding-left: 10px;
        padding-right: 10px;
    }
    .tdfont {
        color: #52596b;
        font-family: Microsoft YaHei,Verdana,serif;
        font-size: 14px;
        word-break: break-all;
        word-wrap: break-word;
    }
</style>
</head>

<body>
<div id="dialog_result_box" title="提示信息" style="display: none;">
    <div id="result_table" style="font-size: 14px;">
    </div>
</div>
</div>
<div class="clean"></div>
<form id="main_form">
    <div class="wrapper">
        <table width="500px" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="550" valign="top">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tdfont">
                        <tr>
                            <td width="150px;" class="registerleft"><span>*</span>用户名：</td>
                            <td id="td_user_name" style="padding-top:3px;padding-bottom: 3px;">
                                <input id="user_name" maxlength="40" name="UserName" type="text" class="input_box registeright"/>
                                <span id="span_user_name_error" class="error_span"></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="registerleft"><span>*</span>请设置密码：</td>
                            <td style="padding-top:3px;padding-bottom: 3px;">
                                <input name="UserPass" type="password" id="user_pass" class="input_box registeright"/>
                                <span id="span_pass_error" class="error_span"></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="registerleft"><span>*</span>请确认密码：</td>
                            <td id="td_confirm_pass" style="padding-top:3px;padding-bottom: 3px;">
                                <input type="password" id="confirm_pass" class="input_box registeright" value=""/>
                                <span id="span_confirm_pass_error" class="error_span"></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="registerleft">验证手机：</td>
                            <td id="td_user_mobile" style="padding-top:3px;padding-bottom: 3px;">
                                <input type="text" id="user_mobile" maxlength="40" class="input_box registeright" name="UserMobile" value=""/>
                                <span id="span_user_mobile_error" class="error_span"></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="registerleft">验证邮箱：</td>
                            <td id="td_user_email" style="padding-top:3px;padding-bottom: 3px;">
                                <input type="text" id="user_email" maxlength="40" class="input_box registeright" name="UserEmail" value=""/>
                                <span id="span_user_email_error" class="error_span"></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="registerleft"><span>*</span>验证码：</td>
                            <td id="td_check_code" style="padding-top:3px;padding-bottom: 3px;">
                                <input type="text" id="check_code" class="input_number registeright2" value=""/>
                                <img src="/default.php?mod=common&a=gen_gif_verify_code&sn=user_register" title="" alt=""
                                     onclick="this.setAttribute('src','/default.php?mod=common&a=gen_gif_verify_code&sn=user_register&n='+Math.random());"
                                     style="cursor: pointer;"/>
                                <span class="error_span" id="span_check_code_error"></span>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="padding:5px 0px;height:30px;"><span id="btn_sub" class="btn">立即注册</span></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</form>
</body>
</html>
