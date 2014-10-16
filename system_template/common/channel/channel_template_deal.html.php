<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript" src="/system_js/edit_area/edit_area_full.js"></script>
    <script type="text/javascript">
        <!--

        editAreaLoader.init({
            id: "edit_area"	// id of the textarea to transform
            ,start_highlight: true	// if start with highlight
            ,allow_resize: "both"
            ,allow_toggle: false
            ,word_wrap: false
            ,language: "zh"
            ,syntax: "html"
            ,font_size: "11"
            ,font_family: "宋体,Courier New,verdana"

        });

        $(function () {
            var selChannelTemplateType = $("#f_ChannelTemplateType");
            selChannelTemplateType.change(function () {
                var dnt = $(this).val();
                if (dnt == '0') {
                    $("#tr_channel_template_tag").css("display", "none");
                } else {
                    $("#tr_channel_template_tag").css("display", "");
                }
            });
            selChannelTemplateType.change();

        });

        function submitForm(closeTab) {

            var content = editAreaLoader
                .getValue("edit_area")
                .replaceAll("<text_area","<textarea");
            content = content.replaceAll("</text_area>","</textarea>");

            $("#f_ChannelTemplateContent").val(content);


            if ($('#f_ChannelTemplateName').val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("请输入模板名称");
            } else {
                if (closeTab == 1) {
                    $("#CloseTab").val("1");
                } else {
                    $("#CloseTab").val("0");
                }
                $("#mainForm").attr("action", "/default.php?secu=manage" +
                    "&mod=channel_template&m={method}" +
                    "&channel_id={b_ChannelId}" +
                    "&channel_template_id={b_ChannelTemplateId}" +
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
                <td class="spe_line" height="30" align="right"><label for="f_ChannelTemplateName">模板名称：</label></td>
                <td class="spe_line">
                    <input name="f_ChannelTemplateName" id="f_ChannelTemplateName" value="{b_ChannelTemplateName}" maxlength="50" type="text" class="input_box" style="width:200px;"/>
                    <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_ChannelTemplateType">模板类型：</label></td>
                <td class="spe_line">
                    <select id="f_ChannelTemplateType" name="f_ChannelTemplateType">
                        <option value="0">普通模板</option>
                        <option value="1">动态模板</option>
                    </select>
                    {b_s_ChannelTemplateType}
                </td>
            </tr>
            <tr id="tr_channel_template_tag" style="display:none;">
                <td class="spe_line" height="30" align="right"><label for="f_ChannelTemplateTag">模板标签：</label></td>
                <td class="spe_line">
                    <input id="f_ChannelTemplateTag" name="f_ChannelTemplateTag" type="text" value="{b_ChannelTemplateTag}" class="input_box"
                           style="width:200px;" maxlength="50"/>
                </td>
            </tr>


            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_PublishFileName">发布文件名：</label></td>
                <td class="spe_line">
                    <input id="f_PublishFileName" name="f_PublishFileName" type="text" value="{b_PublishFileName}" class="input_box"
                           style="width:200px;" maxlength="50"/>
                </td>
            </tr>

            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_PublishType">发布方式：</label></td>
                <td class="spe_line">
                    <select id="f_PublishType" name="f_PublishType">
                        <option value="0">联动发布，只发布在本频道下</option>
                        <option value="1">联动发布，只发布在触发频道下，有可能是本频道，也有可能是继承频道</option>
                        <option value="2">联动发布，发布在所有继承树关系的频道下</option>
                        <option value="10">非联动发布，只发布在本频道下</option>
                        <option value="20">不发布</option>
                        <option value="30">资讯详细页模板</option>
                        <option value="31">活动详细页模板</option>
                    </select>
                    {b_s_PublishType}
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_ChannelTemplateContent">模板内容：</label></td>
                <td class="spe_line">
                    <textarea id="f_ChannelTemplateContent" name="f_ChannelTemplateContent" style="display:none;">{b_ChannelTemplateContent}</textarea>
                    <textarea id="edit_area" col="20" class="input_box" style="width:97%;height:360px;">{b_ChannelTemplateContent}</textarea>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_AttachmentName">生成附件目录名称：</label></td>
                <td class="spe_line">
                    <input id="f_AttachmentName" name="f_AttachmentName" type="text" value="{b_AttachmentName}" class="input_box"
                           style="width:200px;" maxlength="50"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right">
                    <label for="file_attachment">附件：</label></td>
                <td class="spe_line">
                    <input id="file_attachment" name="file_attachment" type="file" class="input_box"
                           style="width:400px;background:#ffffff;margin-top:3px;"/>
                    <span id="btn_download_attachment" style="cursor:pointer">[下载]</span>
                    <span id="btn_delete_attachment" style="cursor:pointer">[删除]</span>

                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_IsAddVisitCode">是否加入统计代码：</label></td>
                <td class="spe_line">
                    <select id="f_IsAddVisitCode" name="f_IsAddVisitCode">
                        <option value="0">不加入</option>
                        <option value="1">加入</option>
                    </select>
                    {b_s_IsAddVisitCode}
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
