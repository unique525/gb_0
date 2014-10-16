<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript">
        <!--

        $(function () {
            var selChannelTemplateType = $("#f_ChannelTemplateType");
            selChannelTemplateType.change(function () {
                $(this).css("background-color", "#FFFFCC");
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
                    "&channel_id={ChannelId}" +
                    "&channel_template_id={ChannelTemplateId}" +
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
                    <input name="f_ChannelTemplateName" id="f_ChannelTemplateName" value="{ChannelTemplateName}" maxlength="50" type="text" class="input_box" style="width:200px;"/>
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
                    {s_ChannelTemplateType}
                </td>
            </tr>
            <tr id="tr_channel_template_tag" style="display:none;">
                <td class="spe_line" height="30" align="right"><label for="f_ChannelTemplateTag">模板标签：</label></td>
                <td class="spe_line">
                    <input id="f_ChannelTemplateTag" name="f_ChannelTemplateTag" type="text" value="{ChannelTemplateTag}" class="input_box"
                           style="width:200px;" maxlength="50"/>
                </td>
            </tr>


            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_PublishFileName">发布文件名：</label></td>
                <td class="spe_line">
                    <input id="f_PublishFileName" name="f_PublishFileName" type="text" value="{PublishFileName}" class="input_box"
                           style="width:200px;" maxlength="50"/>
                </td>
            </tr>


            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_SubDomain">站点二级域名：</label></td>
                <td class="spe_line">
                    <input id="f_SubDomain" name="f_SubDomain" type="text" value="{SubDomain}" class="input_box"
                           style="width:400px;" maxlength="200"/>
                </td>
            </tr>

            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_PublishType">站点发布方式：</label></td>
                <td class="spe_line">
                    <select id="f_PublishType" name="f_PublishType">
                        <option value="0">本地发布</option>
                        <option value="1">FTP发布</option>
                    </select>
                    {s_PublishType}
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_Sort">排序号：</label></td>
                <td class="spe_line">
                    <input id="f_Sort" name="f_Sort" type="text" value="{Sort}" maxlength="10" class="input_number"/>
                </td>
            </tr>


            <tr>
                <td class="spe_line" height="30" align="right"><label for="file_title_pic">站点图标：</label></td>
                <td class="spe_line">
                    <input id="file_title_pic" name="file_title_pic" type="file" class="input_box"
                           style="width:400px;background:#ffffff;margin-top:3px;"/> <span id="preview_title_pic" class="show_title_pic" idvalue="{TitlePicUploadFileId}" style="cursor:pointer">[预览]</span>

                </td>
            </tr>

            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_BrowserTitle">浏览器标题：</label></td>
                <td class="spe_line">
                    <input id="f_BrowserTitle" name="f_BrowserTitle" type="text" value="{BrowserTitle}" class="input_box"
                           style="width:400px;" maxlength="200"/>
                </td>
            </tr>


            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_BrowserKeywords">浏览器关键字：</label></td>
                <td class="spe_line">
                    <input id="f_BrowserKeywords" name="f_BrowserKeywords" type="text" value="{BrowserKeywords}"
                           class="input_box" style="width:400px;" maxlength="200"/>
                </td>
            </tr>

            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_BrowserDescription">浏览器描述文字：</label></td>
                <td class="spe_line">
                    <input id="f_BrowserDescription" name="f_BrowserDescription" type="text" value="{BrowserDescription}"
                           class="input_box" style=" width: 400px;" maxlength="200"/>
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
