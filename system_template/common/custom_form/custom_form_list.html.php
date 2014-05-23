<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
</head>
<body>
<script type="text/javascript" src="/system_js/manage/custom_form/custom_form.js"></script>
<div  class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn">
                <input id="btn_create" class="btn2" value="新建表单" title="在本频道新建一个表单" type="button"/>
            </td>
        </tr>
    </table>
    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
        <tr class="grid_title">
            <td style="width:30px;text-align:center; cursor: pointer;" id="btn_select_all">全</td>
            <td style="width:40px;text-align:center;">编辑</td>
            <td>名称</td>
            <td style="width:80px;text-align:center;">字段</td>
            <td style="width:40px;">发布</td>
            <td style="width:60px;text-align:center;">排序</td>
            <td style="width:40px;text-align:center;">状态</td>
        </tr>
        <icms_list id="custom_form" type="list">
            <item>
                <![CDATA[
                <tr class="grid_item">
                    <td class="spe_line" style="text-align:center;"><input class="doc_input" type="checkbox" name="doc_input" value="{f_CustomFormId}" /></td>
                    <td class="spe_line" style="text-align:center;"><img class="btn_edit" src="/system_template/default/images/manage/edit.gif" alt="编辑" title="{f_CustomFormId}" idvalue="{f_CustomFormId}"/></td>
                    <td class="spe_line"><a target="_blank"  title="{f_CustomFormId}" ><span style="color:black;font-weight:inherit;">{f_CustomFormSubject}</span></a> <span class="btn_open_custom_form_record" title="{f_CustomFormId}" idvalue="{f_CustomFormId}" style="cursor:pointer;">【查看数据】</span></td>
                    <td class="spe_line" style="text-align:center;"><span class="btn_open_custom_form_field" title="{f_CustomFormId}"  idvalue="{f_CustomFormId}" style="cursor:pointer;">管理</span></td>
                    <td class="spe_line"><img class="img_publish" style="cursor: pointer" src="/system_template/default/images/manage/publish.gif" title="{f_SiteContentId}" alt="发布" /></td>
                    <td class="spe_line" style="text-align:center;">{f_sort}</td>
                    <td class="spe_line" style="text-align:center;">{f_state}</td>
                </tr>
                ]]>
            </item>
        </icms_list>
    </table>
</div>
{PagerButton}
</body>
</html>
