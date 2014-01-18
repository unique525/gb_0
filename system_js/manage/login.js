/* 
 * 后台登录js
 */
$().ready(function() {
    $("#manage_user_name").blur(function() {
        $.ajax({
            type: "get",
            url: "default.php?mod=manage&a=get_verify_type",
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

    $("#txt_check_code").focus(function() {
        $("#div_tips").css("display", "none");
        $("#div_tips").html("");
    });
    
    $("#btn_sub").click(function() {
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
    if ($("#adminusername").val() == '') {
        $("#div_tips").css("display", "block");
        $("#div_tips").html("请输入帐号！");
        $("#div_tips").insertAfter("#div_manage_user_name");
        return;
    }
    if ($("#adminuserpass").val() == '') {
        $("#div_tips").css("display", "block");
        $("#div_tips").html("请输入密码！");
        $("#div_tips").insertAfter("#div_manage_user_pass");
        return;
    }
    if ($("#txt_check_code").val() == '') {
        $("#div_tips").css("display", "block");
        $("#div_tips").html("请输入验证码！");
        $("#div_tips").insertAfter("#div_verify_code");
        return;
    }

    var code = $("#txt_check_code").val();
    $.post("/default.php?mod=common&a=check_verify_code&sn=manage_login&vct=0&vcv=" + code, {
        opresult: $(this).html()
    }, function(xml) {
        var result = parseInt(xml);
        if (result == -1) {
            $("#div_tips").css("display", "block");
            $("#div_tips").html("验证码错误！");
            $("#div_tips").insertAfter("#div_verify_code");
        } else if (result == 1) {
            $('#login_form').submit();
        } else {

        }
    });
}

