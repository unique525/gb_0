<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript">
        $(function(){

        });
        function submitForm(closeTab)
        {
            $('#main_form').submit();
        }

    </script>
</head>
<body>
<div class="div_list">
{common_body_deal}
<table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td class="spe_line" height="40" align="right">
            <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/>
            <input class="btn" value="确认并继续" type="button" onclick="submitForm(1)"/>
            <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
        </td>
    </tr>
</table>
<form id="main_form" action="/default.php?secu=manage&mod=user_role&m=modify&site_id={SiteId}&user_id={UserId}" method="post">
<div id="tabs">

<div id="tabs-1">
    <div>
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="spe_line" height="30" align="right"><label for="select_UserGroupId">选择身份：</label></td>
                <td class="spe_line">

                    <select id="select_UserGroupId" name="select_UserGroupId">

                        <icms id="user_group_list">
                            <item>
                                <![CDATA[
                                <option value="{f_UserGroupId}">{f_UserGroupName}</option>
                                ]]>
                            </item>
                        </icms>
                    </select>

                    <input id="UserId" name="UserId" value="{UserId}" type="hidden" />

                    <script type="text/javascript">
                        $("#select_UserGroupId").find("option[value={OldUserGroupId}]").attr("selected",true);
                    </script>

                </td>
            </tr>
        </table>
    </div>
</div>
</div>
</form>
<table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td height="60" align="center">
            <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/> <input class="btn" value="确认并继续"
                                                                                            type="button"
                                                                                            onclick="submitForm(1)"/>
            <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
        </td>
    </tr>
</table>
</div>
</body>
</html>