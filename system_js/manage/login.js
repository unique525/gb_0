/* 
 * 后台登录js
 */
$().ready(function() {
    $("#adminusername").blur(function() {
        $.ajax({
            type: "get",
            url: "default.php?mod=manage&a=verifytype",
            data: {
                adminusername: $("#adminusername").val()
            },
            dataType: "jsonp",
            jsonp: "jsonpcallback",
            success: function(verifytype) {
                if (verifytype["otp"] == 1) {
                    $("#otptr").css("display", "");
                }
            //$("#divsms").html(verifytype["otp"]);
            }
        });
    });

    $("#checkcode").focus(function() {
        $("#divTips").css("display", "none");
        $("#divTips").html("");
    });
    
    $("#btnsub").click(function() {
        subform();
    });
});


function submitkeyclick(button) {
    if (event.keyCode == 13) {
        event.keyCode = 9;
        event.returnValue = false;
        document.getElementById(button).click();
    }
}
function subform() {
    if ($("#adminusername").val() == '') {
        $("#divTips").css("display", "block");
        $("#divTips").html("请输入帐号！");
        $("#divTips").insertAfter("#divAdminUserName");
        return;
    }
    if ($("#adminuserpass").val() == '') {
        $("#divTips").css("display", "block");
        $("#divTips").html("请输入密码！");
        $("#divTips").insertAfter("#divAdminUserPass");
        return;
    }
    if ($("#checkcode").val() == '') {
        $("#divTips").css("display", "block");
        $("#divTips").html("请输入验证码！");
        $("#divTips").insertAfter("#divVerifyCode");
        return;
    }

    var code = $("#checkcode").val();
    $.post("/default.php?mod=common&a=checkverifycode&sn=managelogin&vct=0&vcv=" + code, {
        opresult: $(this).html()
    }, function(xml) {
        var result = parseInt(xml);
        if (result == -1) {
            $("#divTips").css("display", "block");
            $("#divTips").html("验证码错误！");
            $("#divTips").insertAfter("#divVerifyCode");
        } else if (result == 1) {
            $('#theform').submit();
        } else {

        }
    });
}

