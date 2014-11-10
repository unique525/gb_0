<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/custom_form/custom_form.js"></script>
</head>
<body>
{common_body_deal}
<script type="text/javascript">
    $("document").ready(function(){

        var channelId = Request["channel_id"];

        $("#btn_select_all").click(function(event) {
            event.preventDefault();
            var inputSelect = $("[name='docinput']");
            if (inputSelect.prop("checked")) {
                inputSelect.prop("checked", false);//取消全选
            } else {
                inputSelect.prop("checked", true);//全选
            }
        });

        var btnEdit = $(".btn_edit");
        btnEdit.css("cursor", "pointer");
        btnEdit.click(function(event) {
            var customFormId = $(this).attr('idvalue');
            event.preventDefault();
            var pageIndex = parseInt(Request["p"]);
            if (pageIndex <= 0) {
                pageIndex = 1;
            }
            parent.G_TabUrl = '/default.php?secu=manage&mod=custom_form&m=modify&custom_form_id=' + customFormId + '&p=' + pageIndex + '&channel_id=' + channelId;
            parent.G_TabTitle = '编辑表单';
            parent.addTab();
        });


        var btnOpenCustomFormField = $(".btn_open_custom_form_field");
        btnOpenCustomFormField.css("cursor", "pointer");
        btnOpenCustomFormField.click(function(event) {
            var customFormId = $(this).attr('idvalue');
            var customFormSubject = $(this).attr('title');
            event.preventDefault();
            var pageIndex = parseInt(Request["p"]);
            if (pageIndex <= 0||!pageIndex) {
                pageIndex = 1;
            }
            parent.G_TabUrl = '/default.php?secu=manage&mod=custom_form_field&m=list&custom_form_id=' + customFormId + '&p=' + pageIndex + '&custom_form_subject=' + customFormSubject;
            parent.G_TabTitle = customFormSubject + '-管理字段';
            parent.addTab();
        });


        var btnOpenCustomFormRecord = $(".btn_open_custom_form_record");
        btnOpenCustomFormRecord.css("cursor", "pointer");
        btnOpenCustomFormRecord.click(function(event) {
            var customFormId = $(this).attr('idvalue');
            var customFormSubject = $(this).attr('title');
            event.preventDefault();
            var pageIndex = parseInt(Request["p"]);
            if (pageIndex <= 0||!pageIndex) {
                pageIndex = 1;
            }
            parent.G_TabUrl = '/default.php?secu=manage&mod=custom_form_record&m=list&custom_form_id=' + customFormId + '&p=' + pageIndex + '&custom_form_subject=' + customFormSubject;
            parent.G_TabTitle = customFormSubject + '-查看数据';
            parent.addTab();
        });

        $("#btn_create").click(function(event) {
            event.preventDefault();
            parent.G_TabUrl = '/default.php?secu=manage&mod=custom_form&m=create' + '&channel_id=' +  channelId;
            parent.G_TabTitle = '新建表单';
            parent.addTab();
        });
    });
</script>
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
            <td style="width:40px;text-align:center;"><label for="doc_input">编辑</label></td>
            <td>名称</td>
            <td style="width:80px;text-align:center;">字段</td>
            <td style="width:40px;">发布</td>
            <td style="width:60px;text-align:center;">排序</td>
            <td style="width:40px;text-align:center;">状态</td>
        </tr>
        <icms id="custom_form" type="list">
            <item>
                <![CDATA[
                <tr class="grid_item">
                    <td class="spe_line2" style="text-align:center;"><input id="doc_input" class="doc_input" type="checkbox" name="doc_input" value="{f_CustomFormId}" /></td>
                    <td class="spe_line2" style="text-align:center;"><img class="btn_edit" src="/system_template/default/images/manage/edit.gif" alt="编辑" title="{f_CustomFormId}" idvalue="{f_CustomFormId}"/></td>
                    <td class="spe_line2"><a target="_blank"  title="{f_CustomFormId}" ><span style="color:black;font-weight:inherit;">{f_CustomFormSubject}</span></a> <span class="btn_open_custom_form_record" title="{f_CustomFormSubject}" idvalue="{f_CustomFormId}" style="cursor:pointer;">【查看数据】</span></td>
                    <td class="spe_line2" style="text-align:center;"><span class="btn_open_custom_form_field" title="{f_CustomFormSubject}"  idvalue="{f_CustomFormId}" style="cursor:pointer;">管理</span></td>
                    <td class="spe_line2"><img class="img_publish" style="cursor: pointer" src="/system_template/default/images/manage/publish.gif" title="{f_SiteContentId}" alt="发布" /></td>
                    <td class="spe_line2" style="text-align:center;">{f_sort}</td>
                    <td class="spe_line2" style="text-align:center;">{f_state}</td>
                </tr>
                ]]>
            </item>
        </icms>
    </table>
</div>
{PagerButton}
</body>
</html>
