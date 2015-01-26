<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    {common_head}

    <script>
        $().ready(function() {
            var btnSubmit = $("#btn_sub");

            btnSubmit.click(function() {
                subForm();
            });
            function subForm() {
                var divTips = $("#div_tips");
                if ($("#manage_user_old_pass").val() == '') {
                    divTips.css("display", "block");
                    divTips.html("请输入旧密码！");
                    divTips.insertAfter("#div_manage_user_pass");
                    return;
                }
                if ($("#manage_user_new_pass").val() == '') {
                    divTips.css("display", "block");
                    divTips.html("请输入新密码！");
                    divTips.insertAfter("#div_manage_user_new_pass");
                    return;
                }
                if ($("#manage_user_newpass_confirm").val() == '') {
                    divTips.css("display", "block");
                    divTips.html("请输入确认新密码！");
                    divTips.insertAfter("#div_manage_user_new_pass_confirm");
                    return;
                }
                //var userpassmodify = $("#user_pass_modify");
                $("#user_pass_modify").submit();
            }
        });
    </script>
</head>
<body>
<form id="user_pass_modify" enctype="multipart/form-data" action="/default.php?secu=manage&mod=manage_user&m=modify_password" method="post">
    <div id="div_manages_user_pass_modify_"></div>
    <div id="div_modify_box">
        <div id="div_manage_user_pass" class="text_box">
            <label for="manage_user_old_pass">旧密码：</label><input id="manage_user_old_pass" name="manage_user_old_pass"  type="password" value="" maxlength="40" />
        </div>
        <div id="div_manage_user_new_pass" class="text_box">
            <label for="manage_user_new_pass">新密码：</label><input id="manage_user_new_pass" name="manage_user_new_pass"  type="password" value="" maxlength="40" />
        </div>
        <div id="div_manage_user_new_pass_confirm" class="text_box">
            <label for="manage_user_new_pass_confirm">确认新密码：</label><input id="manage_user_new_pass_confirm" name="manage_user_new_pass_confirm" type="password" value="" maxlength="40" />
        </div>
        <div id="btn_box" class="btn_box">
            <input type="button" id="btn_sub" class="btn" value="确 定" />
        </div>

    </div>
</form>
</body>
</html>