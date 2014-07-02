<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/custom_form/custom_form_deal.js"></script>
    <script type="text/javascript">
        function submitForm(continueCreate)
        {
            if($('#f_CustomFormFieldName').val() == ''){
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("请输入表单名称");
            }
            else
            {
                if (continueCreate == 1) {
                    $("#CloseTab").val("0");
                } else {
                    $("#CloseTab").val("1");
                }
                $('#main_form').submit();
            }
        }

    </script>
</head>
<body>
{common_body_deal}
<form id="main_form" action="/default.php?secu=manage&mod=custom_form_field&m={method}&custom_form_field_id={CustomFormFieldId}&custom_form_id={CustomFormId}" method="post">
    <div style="margin:10px auto;margin-left: 10px;">
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="spe_line" height="40" align="right">
                    <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/>
                    <input class="btn" style="display:{display}" value="确认并继续新增" type="button" onclick="submitForm(1)"/>
                    <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                </td>
            </tr>
        </table>
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="spe_line" height="30" align="right">表单字段名称：</td>
                <td class="spe_line">
                    <input name="f_CustomFormFieldName" id="f_CustomFormFieldName" value="{CustomFormFieldName}" type="text" class="input_box" style=" width: 300px;" />
                    <input type="hidden" id="f_CustomFormId" name="f_CustomFormId" value="{CustomFormId}" />
                    <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right">表单字段类型：</td>
                <td class="spe_line">
                    <select id="f_CustomFormFieldType" name="f_CustomFormFieldType">
                        <option value="1" {s_CustomFormFieldType_1}>文字</option>
                        <option value="0" {s_CustomFormFieldType_0}>整数</option>
                        <option value="2" {s_CustomFormFieldType_2}>内容较大的文本</option>
                        <option value="3" {s_CustomFormFieldType_3}>数字（含小数）</option>
                        <option value="4" {s_CustomFormFieldType_4}>日期时间</option>
                        <option value="5" {s_CustomFormFieldType_5}>二进制文件</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right">排序：</td>
                <td class="spe_line"><input name="f_sort" id="f_sort" value="{sort}" type="text" class="input_number" style=" width: 60px;" />(注:输入数字,数值越大越靠前)</td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right">是否启用：</td>
                <td class="spe_line">
                    <select id="f_state" name="f_state">
                        <option value="0" {s_state_0}>启用</option>
                        <option value="100" {s_state_100}>停用</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right">是否显示在列表中：</td>
                <td class="spe_line">
                    <select id="f_ShowInList" name="f_ShowInList">
                        <option value="1" {s_ShowInList_1}>显示</option>
                        <option value="0" {s_ShowInList_0}>不显示</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td colspan="2" height="30" align="center">
                    <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/>
                    <input class="btn" style="display:{display}" value="确认并继续新增" type="button" onclick="submitForm(1)"/>
                    <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                </td>
            </tr>

        </table>

    </div>
</form>
</body>
</html>