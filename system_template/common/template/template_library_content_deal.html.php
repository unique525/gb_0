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
            id: "tabs-1_edit_area"	// id of the textarea to transform
            ,start_highlight: true	// if start with highlight
            ,allow_resize: "both"
            ,allow_toggle: false
            ,word_wrap: false
            ,language: "zh"
            ,syntax: "html"
            ,font_size: "12"
            ,font_family: "宋体,Courier New,verdana"

        });



        $(function () {
            $("#tabs").tabs({
                    activate: function( event, ui ) {

                        //设置当前的来源tab id
                        var oldId = ui.oldPanel.attr("id");
                        var newId = ui.newPanel.attr("id");

                        editAreaLoader.init({
                            id: newId+"_edit_area"	// id of the textarea to transform
                            ,start_highlight: true	// if start with highlight
                            ,allow_resize: "both"
                            ,allow_toggle: false
                            ,word_wrap: false
                            ,language: "zh"
                            ,syntax: "html"
                            ,font_size: "12"
                            ,font_family: "宋体,Courier New,verdana"

                        });
                    }
                }
            );

            var selChannelTemplateType = $("#f_TemplateType");
            selChannelTemplateType.change(function () {
                var dnt = $(this).val();
                if (dnt == '0') {
                    $("#tr_template_tag").css("display", "none");
                } else {
                    $("#tr_template_tag").css("display", "");
                }
            });
            selChannelTemplateType.change();

            $("#span_show_1").click(function () {
                editAreaLoader.init({
                    id: "edit_area2"	// id of the textarea to transform
                    ,start_highlight: true	// if start with highlight
                    ,allow_resize: "both"
                    ,allow_toggle: false
                    ,word_wrap: false
                    ,language: "zh"
                    ,syntax: "html"
                    ,font_size: "12"
                    ,font_family: "宋体,Courier New,verdana"

                });
                $("#div_2").css("display","block");
            });

            var deleteAttachment = $("#btn_delete_attachment");
            deleteAttachment.click(function () {

                $.ajax({
                    type: "get",
                    url: "/default.php?secu=manage" +
                        "&mod=template_library_content" +
                        "&m=async_delete_attachment",
                    data: {
                        template_library_content_id: "{TemplateLibraryContentId}"
                    },
                    dataType: "jsonp",
                    jsonp: "jsonpcallback",
                    success: function(data) {
                        var result = parseInt(data["result"]);
                        if(result>0){
                            alert("操作完成");
                        }else{
                            alert("没有上传附件或删除失败");
                        }
                    }
                });

            });

        });

        function submitForm(closeTab) {

            var content = editAreaLoader
                .getValue("tabs-1_edit_area")
                .replaceAll("<text_area","<textarea");
            content = content.replaceAll("</text_area>","</textarea>");

            $("#f_TemplateContent").val(content);

            var content2 = editAreaLoader
                .getValue("tabs-2_edit_area")
                .replaceAll("<text_area","<textarea");
            content2 = content2.replaceAll("</text_area>","</textarea>");
            $("#f_TemplateContentForMobile").val(content2);

            var content3 = editAreaLoader
                .getValue("tabs-3_edit_area")
                .replaceAll("<text_area","<textarea");
            content3 = content3.replaceAll("</text_area>","</textarea>");
            $("#f_TemplateContentForPad").val(content3);

            var content4 = editAreaLoader
                .getValue("tabs-4_edit_area")
                .replaceAll("<text_area","<textarea");
            content4 = content4.replaceAll("</text_area>","</textarea>");
            $("#f_TemplateContentForTV").val(content4);

            if ($('#f_TemplateName').val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("请输入模板名称");
            } else {
                if (closeTab == 1) {
                    $("#CloseTab").val("1");
                } else {
                    $("#CloseTab").val("0");
                }
                $("#mainForm").attr("action", "/default.php?secu=manage" +
                    "&mod=template_library_content&m={method}" +
                    "&template_library_content_id={TemplateLibraryContentId}" +
                    "&template_library_id={TemplateLibraryId}" +
                    "&template_library_channel_id={TemplateLibraryChannelId}" +
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
                    <input class="btn" style="display: {display}" value="确认并继续" type="button" onclick="submitForm(0)"/>
                    <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                </td>
            </tr>
        </table>
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_TemplateName">模板名称：</label></td>
                <td class="spe_line">
                    <input name="f_TemplateName" id="f_TemplateName" value="{TemplateName}" maxlength="50" type="text" class="input_box" style="width:200px;"/>
                    <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_TemplateType">模板类型：</label></td>
                <td class="spe_line">
                    <select id="f_TemplateType" name="f_TemplateType">
                        <option value="0">普通模板</option>
                        <option value="1">动态模板</option>
                    </select>
                    {s_TemplateType}
                </td>
            </tr>
            <tr id="tr_template_tag" style="display:none;">
                <td class="spe_line" height="30" align="right"><label for="f_TemplateTag">模板标签：</label></td>
                <td class="spe_line">
                    <input id="f_TemplateTag" name="f_TemplateTag" type="text" value="{TemplateTag}" class="input_box"
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
                        <option value="32">自定义页面详细页模板</option>
                        <option value="33">分类信息详细页模板</option>
                        <option value="34">产品详细页模板</option>
                    </select>
                    {s_PublishType}
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_TemplateContent">模板内容：</label></td>
                <td class="spe_line">

                    <div id="tabs" style="margin-left:4px;">
                        <ul>
                            <li><a href="#tabs-1">网页模板</a></li>
                            <li><label for="f_TemplateContentForMobile"><a href="#tabs-2">移动客户端（手机）模板</a></label></li>
                            <li><label for="f_TemplateContentForPad"><a href="#tabs-3">平板电脑模板</a></label></li>
                            <li><label for="f_TemplateContentForTV"><a href="#tabs-4">大屏幕（电视）模板</a></label></li>
                        </ul>
                        <div id="tabs-1" style="padding-top:3px;">
                            <textarea id="f_TemplateContent" name="f_TemplateContent" style="display:none;">{TemplateContent}</textarea>
                            <label for="tabs-1_edit_area"></label><textarea id="tabs-1_edit_area" col="20" class="input_box" style="width:97%;height:360px;">{TemplateContent}</textarea>
                        </div>
                        <div id="tabs-2" style="padding-top:3px;">
                            <textarea id="f_TemplateContentForMobile" name="f_TemplateContentForMobile" style="display:none;">{TemplateContentForMobile}</textarea>
                            <label for="tabs-2_edit_area"></label><textarea id="tabs-2_edit_area" col="20" class="input_box" style="width:97%;height:360px;">{TemplateContentForMobile}</textarea>
                        </div>
                        <div id="tabs-3" style="padding-top:3px;">
                            <textarea id="f_TemplateContentForPad" name="f_TemplateContentForPad" style="display:none;">{TemplateContentForPad}</textarea>
                            <label for="tabs-3_edit_area"></label><textarea id="tabs-3_edit_area" col="20" class="input_box" style="width:97%;height:360px;">{TemplateContentForPad}</textarea>

                        </div>
                        <div id="tabs-4" style="padding-top:3px;">
                            <textarea id="f_TemplateContentForTV" name="f_TemplateContentForTV" style="display:none;">{TemplateContentForTV}</textarea>
                            <label for="tabs-4_edit_area"></label><textarea id="tabs-4_edit_area" col="20" class="input_box" style="width:97%;height:360px;">{TemplateContentForTV}</textarea>
                        </div>



                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_AttachmentName">生成附件目录名称：</label></td>
                <td class="spe_line">
                    <input id="f_AttachmentName" name="f_AttachmentName" type="text" value="{AttachmentName}" class="input_box"
                           style="width:200px;" maxlength="50"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right">
                    <label for="file_attachment">附件：</label></td>
                <td class="spe_line">
                    <input id="file_attachment" name="file_attachment" type="file" class="input_box"
                           style="width:400px;background:#ffffff;margin-top:3px;"/>
                    <span id="btn_download_attachment" style="cursor:pointer">
                        <a href="/default.php?secu=manage&mod=template_library_content&m=get_attachment&template_library_content_id={TemplateLibraryContentId}" target="_blank">
                            [下载]
                        </a>
                    </span>
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
                    {s_IsAddVisitCode}
                </td>
            </tr>
        </table>

        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td height="60" align="center">
                    <input class="btn" value="确认并关闭" type="button" onclick="submitForm(1)"/>
                    <input class="btn" style="display: {display}" value="确认并继续" type="button" onclick="submitForm(0)"/>
                    <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                </td>
            </tr>
        </table>
    </div>
</form>
</body>
</html>
