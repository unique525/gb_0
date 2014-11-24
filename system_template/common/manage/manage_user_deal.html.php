<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript">
        <!--

        $(function () {
            $("#f_EndDate").datepicker({
                dateFormat: 'yy-mm-dd',
                numberOfMonths: 1,
                showButtonPanel: true
            });

        });

        function submitForm(closeTab) {
            if ($('#f_ManageUserName').val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("请输入管理员帐号");
            } else {
                if (closeTab == 1) {
                    $("#CloseTab").val("1");
                } else {
                    $("#CloseTab").val("0");
                }
                $("#mainForm").attr("action", "/default.php?secu=manage" +
                    "&mod=manage_user&m={method}" +
                    "&manage_user_id={ManageUserId}" +
                    "&tab_index=" + parent.G_TabIndex + "");

                $('#mainForm').submit();
            }
        }
        -->
    </script>
</head>
<body>
{common_body_deal}
<form id="mainForm" enctype="multipart/form-data"
      action=""
      method="post">
    <div>
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="spe_line" height="40" align="right">
                    <input class="btn" value="确认并关闭" type="button" onclick="submitForm(1)"/>
                    <input class="btn" value="确认并继续" type="button" onclick="submitForm(0)"/>
                    <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                </td>
            </tr>
        </table>
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_ManageUserName">管理帐号：</label></td>
                <td class="spe_line">
                    <input name="f_ManageUserName" id="f_ManageUserName" value="{ManageUserName}" type="text"
                           maxlength="100" class="input_box" style="width:300px;"/>
                    <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_ManageUserPass">管理密码：</label></td>
                <td class="spe_line">
                    <input name="f_ManageUserPass" id="f_ManageUserPass" value="{ManageUserPass}" type="password" title="{ManageUserPass}"
                           maxlength="100" class="input_box" style="width:300px;"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_ManageUserGroupId">所属管理分组：</label></td>
                <td class="spe_line">
                    <select id="f_ManageUserGroupId" name="f_ManageUserGroupId">
                        <icms id="manage_user_group_list" type="list">
                            <item>
                                <![CDATA[
                                <option value="{f_ManageUserGroupId}">{f_ManageUserGroupName}</option>
                                ]]>
                            </item>
                        </icms>
                    </select>
                    {s_ManageUserGroupId}
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_EndDate">到期时间：</label></td>
                <td class="spe_line"><input id="f_EndDate" name="f_EndDate" value="{EndDate}" type="text" class="input_box" style="width:75px;"/></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_UserId">绑定的会员id：</label></td>
                <td class="spe_line">
                    <input id="f_UserId" name="f_UserId" type="text" value="{UserId}" class="input_number"
                           style="width:100px;" maxlength="20"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_UserName">绑定的会员名：</label></td>
                <td class="spe_line">
                    <input id="f_UserName" name="f_UserName" type="text" value="{UserName}" class="input_box"
                           style="width:200px;" maxlength="50"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_Mobile">联系方式：</label></td>
                <td class="spe_line">
                    <input id="f_Mobile" name="f_Mobile" type="text" value="{Mobile}" class="input_box"
                           style="width:400px;" maxlength="50"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_OpenPublicLogin">公网登录：</label></td>
                <td class="spe_line">
                    <select id="f_OpenPublicLogin" name="f_OpenPublicLogin">
                        <option value="0">关闭</option>
                        <option value="1">开启</option>
                    </select>
                    {s_OpenPublicLogin}
                </td>
            </tr>

            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_SmsVerifyLogin">公网登录短信认证：</label></td>
                <td class="spe_line">
                    <select id="f_SmsVerifyLogin" name="f_SmsVerifyLogin">
                        <option value="0">关闭</option>
                        <option value="1">开启</option>
                    </select>
                    {s_SmsVerifyLogin}
                </td>
            </tr>


            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_OtpVerifyLogin">公网登录口令牌认证：</label></td>
                <td class="spe_line">
                    <select id="f_OtpVerifyLogin" name="f_OtpVerifyLogin">
                        <option value="0">关闭</option>
                        <option value="1">开启</option>
                    </select>
                    {s_OtpVerifyLogin}
                </td>
            </tr>


            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_OtpBarCode">令牌号（背面条形码）：</label></td>
                <td class="spe_line">
                    <input id="f_OtpBarCode" name="f_OtpBarCode" type="text" value="{OtpBarCode}" class="input_box"
                           style="width:400px;" maxlength="50"/>
                </td>
            </tr>


            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_OtpAuthorityKey">密钥：</label></td>
                <td class="spe_line">
                    <input id="f_OtpAuthorityKey" name="f_OtpAuthorityKey" type="text" value="{OtpAuthorityKey}" class="input_box"
                           style="width:400px;" maxlength="50"/>
                </td>
            </tr>


            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_OtpCurrentSuccess">成功值：</label></td>
                <td class="spe_line">
                    <input id="f_OtpCurrentSuccess" name="f_OtpCurrentSuccess" type="text" value="{OtpCurrentSuccess}" class="input_number"
                           style="width:100px;" maxlength="20"/>
                </td>
            </tr>


            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_OtpCurrentDrift">漂移值：</label></td>
                <td class="spe_line">
                    <input id="f_OtpCurrentDrift" name="f_OtpCurrentDrift" type="text" value="{OtpCurrentDrift}" class="input_number"
                           style="width:100px;" maxlength="20"/>
                </td>
            </tr>



            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_Sort">排序号：</label></td>
                <td class="spe_line">
                    <input id="f_Sort" name="f_Sort" type="text" value="{Sort}" maxlength="10" class="input_number"/>
                </td>
            </tr>

        </table>

        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td height="60" align="center">
                    <input class="btn" value="确认并关闭" type="button" onclick="submitForm(1)"/>
                    <input class="btn" value="确认并继续" type="button" onclick="submitForm(0)"/>
                    <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                </td>
            </tr>
        </table>
    </div>
</form>
</body>
</html>
