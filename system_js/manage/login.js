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
    $.post("/default.php?mod=common&a=check_verify_code&sn=manage_login&vct=0&vcv=" + code, {
        opResult: $(this).html()
    }, function(xml) {
        var result = parseInt(xml);
        if (result == -1) {
            divTips.css("display", "block");
            divTips.html("验证码错误！");
            divTips.insertAfter("#div_verify_code");
        } else if (result == 1) {
            $('#login_form').submit();
        } else {

        }
    });
}

