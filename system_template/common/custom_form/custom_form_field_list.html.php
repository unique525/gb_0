<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript">
        $("document").ready(function(){
            $("#btn_create_custom_form_field").click(function(event) {
                event.preventDefault();
                parent.G_TabUrl = '/default.php?secu=manage&mod=custom_form_field&m=create&custom_form_id={CustomFormId}';
                parent.G_TabTitle = parent.G_SelectedChannelName + '-新增字段';
                parent.addTab();
            });

            var btnEdit = $(".btn_edit_custom_form_field");
            btnEdit.css("cursor", "pointer");
            btnEdit.click(function(event) {
                var customFormFieldId = $(this).attr('idvalue');
                event.preventDefault();
                parent.G_TabUrl = '/default.php?secu=manage&mod=custom_form_field&m=modify&custom_form_id={CustomFormId}&custom_form_field_id=' + customFormFieldId + '&channel_id=' + parent.G_SelectedChannelId;
                parent.G_TabTitle = parent.G_SelectedChannelName + '-编辑字段';
                parent.addTab();
            });
        });


    </script>
</head>
<body>
{common_body_deal}
<div class="div_list">
    <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td align="left" style="padding: 3px 0;">
                <input id="btn_create_custom_form_field" class="btn2" value="新建字段" title="在本表单新建一个字段" type="button"/>
            </td>
        </tr>
    </table>
    <table width="99%" class="doc_grid" cellpadding="0" cellspacing="0">
        <tr class="grid_title">
            <td style="width:40px;text-align:center;">ID</td>
            <td style="width:40px;text-align:center;">编辑</td>
            <td style="width:40px;text-align:center;">状态</td>
            <td>表单字段名称</td>
            <td style="width:120px;">表单字段类型</td>
            <td style="width:60px;text-align:center;">排序</td>
        </tr>
        <icms_list id="custom_form_field" type="list">
            <item>
                <![CDATA[
                <tr class="grid_item">
                    <td class="spe_line" style="text-align:center;">{f_CustomFormFieldId}</td>
                    <td class="spe_line" style="text-align:center;"><img class="btn_edit_custom_form_field" src="/system_template/default/images/manage/edit.gif" alt="编辑" idvalue="{f_CustomFormFieldId}" title="{f_CustomFormId}" /></td>
                    <td class="spe_line" style="text-align:center;">{f_State}</td>
                    <td class="spe_line">{f_CustomFormFieldName}</td>
                    <td class="spe_line" style="text-align:center;">{f_CustomFormFieldType}</td>
                    <td class="spe_line" style="text-align:center;">{f_Sort}</td>
                </tr>
                ]]>
            </item>
        </icms_list>
    </table>
{NoField}
</div>
</body>
</html>