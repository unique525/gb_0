<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    {common_head}

    <script>
        $().ready(function() {
            $(".btnCloseDialogBoxOfModifyManageUserPass").click(function(){


                if($("#dialog_modify_password_box") != undefined){

                    $("#dialog_modify_password_box").dialog({
                        close: function(event, ui) {}
                    });

                }


            });

        });

        function submitForm() {
            if ($("#f_ManageUserPassOfOld").val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100,modal: true});
                $("#dialog_content").html("请输入旧密码");
                return;
            }
            if ($("#f_ManageUserPassOfNew").val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100,modal: true});
                $("#dialog_content").html("请输入新密码");
                return;
            }
            if ($("#f_ManageUserPassOfNewConfirm").val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100,modal: true});
                $("#dialog_content").html("请输入新密码");
                return;
            }
            if ($("#f_ManageUserPassOfNew").val() != $("#f_ManageUserPassOfNewConfirm").val()) {
                $("#dialog_box").dialog({width: 300, height: 100,modal: true});
                $("#dialog_content").html("两次输入的密码不一致");
                return;
            }


            $("#mainForm").submit();
        }
    </script>
</head>
<body>
{common_body_deal}
<form id="mainForm" action="/default.php?secu=manage&mod=manage_user&m=modify_password" method="post">

    <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class="spe_line" height="30" align="right"><label for="f_ManageUserName">当前管理帐号：</label></td>
            <td class="spe_line">
                {ManageUserName}
            </td>
        </tr>
        <tr>
            <td class="spe_line" height="30" align="right"><label for="f_ManageUserPassOfOld">旧密码：</label></td>
            <td class="spe_line">
                <input name="f_ManageUserPassOfOld" id="f_ManageUserPassOfOld" value="" type="password"
                       maxlength="100" class="input_box" style="width:100px;"/>
            </td>
        </tr>
        <tr>
            <td class="spe_line" height="30" align="right"><label for="f_ManageUserPassOfNew">新密码：</label></td>
            <td class="spe_line">
                <input name="f_ManageUserPassOfNew" id="f_ManageUserPassOfNew" value="" type="password"
                       maxlength="100" class="input_box" style="width:100px;"/>
            </td>
        </tr>
        <tr>
            <td class="spe_line" height="30" align="right"><label for="f_ManageUserPassOfNewConfirm">确认新密码：</label></td>
            <td class="spe_line">
                <input name="f_ManageUserPassOfNewConfirm" id="f_ManageUserPassOfNewConfirm" value="" type="password"
                       maxlength="100" class="input_box" style="width:100px;"/>
            </td>
        </tr>

    </table>

    <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td height="60" align="center">
                <input class="btn" value="确 认" type="button" onclick="submitForm()"/>
            </td>
        </tr>
    </table>

</form>
</body>
</html>