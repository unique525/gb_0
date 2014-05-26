<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/custom_form/custom_form_deal.js"></script>
</head>

<body>
{common_body_deal}
<form id="main_form" action="/default.php?secu=manage&mod=custom_form&m={method}&site_id={SiteId}&channel_id={ChannelId}&custom_form_id={CustomFormId}" method="post">
    <div style="margin:10px auto;margin-left: 10px;">
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="spe_line" height="40" align="right">
                    <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/>
                    <input class="btn" value="确认并继续新增" type="button" onclick="submitForm(1)"/>
                    <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                </td>
            </tr>
        </table>
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="spe_line" height="30" align="right">表单名称：</td>
                <td class="spe_line">
                    <input type="text" name="f_CustomFormSubject" id="f_CustomFormSubject" value="{CustomFormSubject}" class="input_box" style=" width: 300px;" />
                    <input type="hidden" id="f_CustomFormId" name="f_CustomFormId" value="{CustomFormId}" />
                    <input type="hidden" id="f_ChannelId" name="f_ChannelId" value="{ChannelId}" />
                    <input type="hidden" id="f_ManageUserId" name="f_ManageUserId" value="{ManageUserId}" />
                    <input type="hidden" id="f_CreateDate" name="f_CreateDate" value="{CreateDate}" />
                    <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right">排序：</td>
                <td class="spe_line"><input name="f_Sort" id="f_Sort" value="{Sort}" type="text" class="input_number" style=" width: 60px;" />(注:输入数字,数值越大越靠前)</td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right">是否启用：</td>
                <td class="spe_line">
                    <select id="f_state" name="f_state" >
                        <option value="0" {s_state_0}>启用</option>
                        <option value="100" {s_state_100}>停用</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td colspan="2" height="30" align="center">
                    <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/>
                    <input class="btn" value="确认并继续新增" type="button" onclick="submitForm(1)"/>
                    <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                </td>
            </tr>

        </table>

    </div>
</form>
</body>
</html>
