/* 
 * 后台登录js
 */
document.onkeydown=function(event){
    e = event ? event :(window.event ? window.event : null);
    if(e.keyCode==13){
        subForm();
    }
}

$().ready(function() {
    $("#manage_user_name").blur(function() {
        $.ajax({
            type: "get",
            url: "default.php?mod=manage&a=async_get_verify_type",
            data: {
                manage_user_name: $("#manage_user_name").val()
            },
            dataType: "jsonp",
            jsonp: "jsonpcallback",
            success: function(verifyType) {
                if (verifyType["otp_verify_login"] == "1") {
                    $("#div_manage_user_otp").css("display", "");
                }
            }
        });
    });

    var divTips = $("#div_tips");
    $("#txt_check_code").focus(function() {
        divTips.css("display", "none");
        divTips.html("");
    });

    var btnSubmit = $("#btn_sub");
    btnSubmit.click(function() {
        subForm();
    });

});


function submitKeyClick(event) {
    event = event || window.event;
    var myKeyCode = event.keyCode;
    if (myKeyCode == 13) {
        //keyCode = 9;
        //window.event.returnValue = false;
        document.getElementById("btn_sub").click();
    }
}
function subForm() {
    var divTips = $("#div_tips");
    var txtCheckCode = $("#txt_check_code");
    if ($("#adminusername").val() == '') {
        divTips.css("display", "block");
        divTips.html("请输入帐号！");
        divTips.insertAfter("#div_manage_user_name");
        return;
    }
    if ($("#adminuserpass").val() == '') {
        divTips.css("display", "block");
        divTips.html("请输入密码！");
        divTips.insertAfter("#div_manage_user_pass");
        return;
    }
    if (txtCheckCode.val() == '') {
        divTips.css("display", "block");
        divTips.html("请输入验证码！");
        divTips.insertAfter("#div_verify_code");
        return;
    }

    var code = txtCheckCode.val();
    $.post("/default.php?mod=common&a=check_verify_code&sn=manage_login&verify_code_type=0&verify_code_value=" + code, {
        opResult: $(this).html()
    }, function(xml) {
        var result = parseInt(xml);
        if (result == -1) {
            divTips.css("display", "block");
            divTips.html("验证码错误！");
            divTips.insertAfter("#div_verify_code");
        } else if (result == 1) {
            //ajax submit
            $.ajax({
                type: "post",
                url: "default.php?mod=manage&a=async_do_login",
                data: {
                    manage_user_name: $("#manage_user_name").val(),
                    manage_user_pass: $("#manage_user_pass").val(),
                    manage_user_otp_number : $("#manage_user_otp_number").val()
                },
                dataType: "jsonp",
                jsonp: "jsonpcallback",
                success: function(data) {
                    if(data != undefined){
                        var result = parseInt(data["result"]);
                        switch (result){
                            case -1:
                                divTips.css("display", "block");
                                divTips.html("参数错误，请重试！");
                                divTips.insertAfter("#btn_box");
                                break;
                            case -3:
                                divTips.css("display", "block");
                                divTips.html("登录失败，原因可能是帐号密码错误，或者您的帐号已经停用或过期！");
                                divTips.insertAfter("#btn_box");
                                break;
                            case -6:
                                divTips.css("display", "block");
                                divTips.html("登录失败，口令牌认证失败，请联系管理人员或重新输入口令牌密码！");
                                divTips.insertAfter("#btn_box");
                                break;
                            case -8:
                                divTips.css("display", "block");
                                divTips.html("登录失败，因为此帐号不允许外网使用，请联系管理人员！");
                                divTips.insertAfter("#btn_box");
                                break;
                            case 1:
                                window.location.href = "default.php?secu=manage";
                                break;
                        }
                    }
                }
            });
            //$('#login_form').submit();
        } else {

        }
    });
}

