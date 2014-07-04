<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/user/user_group.js"></script>
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
        <form id="mainForm" action="/default.php?secu=manage&mod=user_group&m={method}&user_group_id={UserGroupId}&site_id={SiteId}" method="post">
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="spe_line" height="30" align="right">名称：</td>
                <td class="spe_line"><label><input id="UserGroupName" type="text" value="{UserGroupName}" class="input_box" name="f_UserGroupName" style="width:300px"/></label></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right">简介：</td>
                <td class="spe_line"><label><input type="text" value="{UserGroupShortName}" class="input_box" name="f_UserGroupShortName" style="width:300px"/></label></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right">排序：</td>
                <td class="spe_line"><label><input type="text" value="{Sort}" name="f_Sort" class="input_number"  style="width:80px"/>（注：输入数字，数值越大越靠前）</label></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right">是否启用：</td>
                <td class="spe_line">
                    <label>
                        <select>
                            <option value="0">开启</option>
                            <option value="40">停用</option>
                            {s_State}
                        </select>
                    </label>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right">是否锁定：</td>
                <td class="spe_line">
                    <label>
                        <select>
                            <option value="0">不锁定</option>
                            <option value="1">锁定</option>
                            {s_IsLock}
                        </select>
                    </label>
                </td>
            </tr>
        </table>
            <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
            <input name="PageIndex" type="hidden" value="{PageIndex}"/>
            <input name="PageSize" type="hidden" value="{PageSize}"/>
            <input name="TabIndex" type="hidden" value="{TabIndex}"/>
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