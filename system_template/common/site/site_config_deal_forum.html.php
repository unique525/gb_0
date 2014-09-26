<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript" src="/system_js/xheditor-1.1.14/xheditor-1.1.14-zh-cn.min.js"></script>
    <script type="text/javascript" src="/system_js/color_picker.js"></script>
    <script type="text/javascript" src="/system_js/ajax_file_upload.js"></script>
    <script type="text/javascript" src="/system_js/upload_file.js"></script>

    <script type="text/javascript">
        var editorOfForumTopInfo;
        var editorOfForumBotInfo;
        var tableTypeOfForumTopInfo = window.UPLOAD_TABLE_TYPE_FORUM_TOP_INFO_CONTENT;
        var tableTypeOfForumBotInfo = window.UPLOAD_TABLE_TYPE_FORUM_BOT_INFO_CONTENT;
        var tableId = parseInt("{SiteId}");
        $(function () {
            editorOfForumTopInfo = $('#cfg_ForumTopInfo_2').xheditor();
            editorOfForumBotInfo = $('#cfg_ForumBotInfo_2').xheditor();
            $('#tabs').tabs();



            var btnUploadToForumTopInfo = $("#btnUploadToForumTopInfo");
            btnUploadToForumTopInfo.click(function () {

                var fileElementId = 'file_upload_to_forum_top_info';
                var fUploadFile = null;

                var attachWatermark = 0;
                var loadingImageId = "loadingOfForumTopInfo";
                AjaxFileUpload(fileElementId, tableTypeOfForumTopInfo, tableId, editorOfForumTopInfo, fUploadFile, attachWatermark,loadingImageId);
            });

            var btnUploadToForumBotInfo = $("#btnUploadToForumBotInfo");
            btnUploadToForumBotInfo.click(function () {

                var fileElementId = 'file_upload_to_forum_bot_info';
                var fUploadFile = null;

                var attachWatermark = 0;
                var loadingImageId = "loadingOfForumBotInfo";
                AjaxFileUpload(fileElementId, tableTypeOfForumBotInfo, tableId, editorOfForumBotInfo, fUploadFile, attachWatermark,loadingImageId);
            });

        });

        function submitForm(closeTab) {

                if (closeTab == 1) {
                    $("#CloseTab").val("1");
                } else {
                    $("#CloseTab").val("0");
                }

                $("#mainForm").attr("action", "/default.php?secu=manage&mod=site_config&m=set&site_id={SiteId}&tab_index=" + parent.G_TabIndex + "");
                $('#mainForm').submit();

        }

    </script>
</head>
<body>
<div class="div_deal">
    <form id="mainForm" enctype="multipart/form-data" method="post">
        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
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
                <td>
                    <div id="tabs">
                        <ul>
                            <li><a href="#tabs-1">基本设置</a></li>
                            <li><a href="#tabs-2">论坛顶部信息</a></li>
                            <li><a href="#tabs-3">论坛底部信息</a></li>
                            <li><a href="#tabs-4">帖子相关</a></li>
                        </ul>
                        <div id="tabs-1">
                            <div style="">
                                <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_ForumIeTitle_0">论坛浏览器标题：</label></td>
                                        <td class="spe_line">
                                            <input name="cfg_ForumIeTitle_0" id="cfg_ForumIeTitle_0" value="{cfg_ForumIeTitle_0}" maxlength="200" type="text" class="input_box" style=" width: 500px;"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_ForumIeKeywords_0">论坛浏览器关键字 Keyword：</label></td>
                                        <td class="spe_line">
                                            <input name="cfg_ForumIeKeywords_0" id="cfg_ForumIeKeywords_0" value="{cfg_ForumIeKeywords_0}" maxlength="200" type="text" class="input_box" style=" width: 500px;"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_ForumIeDescription_0">论坛浏览器描述文字 Description：</label></td>
                                        <td class="spe_line">
                                            <input name="cfg_ForumIeDescription_0" id="cfg_ForumIeDescription_0" value="{cfg_ForumIeDescription_0}" maxlength="200" type="text" class="input_box" style=" width: 500px;"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_ForumLogoImage_0">论坛LOGO图片：</label></td>
                                        <td class="spe_line">

                                            <input id="cfg_ForumLogoImage_0" name="cfg_ForumLogoImage_0" value="{cfg_ForumLogoImage_0}" maxlength="200" type="text" class="input_box" style=" width: 500px;"/> [<a id="preview_ForumLogoImage" href="" target="_blank">预览</a>]<br/>
                                            <input id="ForumLogoImageToUpload" name="ForumLogoImageToUpload" type="file" class="input_box" style="width:200px; background: #ffffff;"/> <img id="ForumLogoImageloading" src="{rootpath}/images/ui-anim_basic_16x16.gif" style="display:none;"/>
                                            <input id="btnForumLogoImageUpload" onclick="return ajaxForumLogoImageUpload()" type="button" value="上传"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_ForumBackground_0">论坛背景图片：</label></td>
                                        <td class="spe_line">

                                            <input id="cfg_ForumBackground_0" name="cfg_ForumBackground_0" value="{cfg_ForumBackground_0}" maxlength="200" type="text" class="input_box" style=" width: 500px;"/> [<a id="preview_ForumBackground" href="" target="_blank">预览</a>]<br/>
                                            <input id="ForumBackgroundToUpload" name="ForumBackgroundToUpload" type="file" class="input_box" style="width:200px; background: #ffffff;"/> <img id="ForumBackgroundloading" src="{rootpath}/images/ui-anim_basic_16x16.gif" style="display:none;"/>
                                            <input id="btnForumBackgroundUpload" onclick="return ajaxForumBackgroundUpload()" type="button" value="上传"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_ForumCssDefault_0">论坛默认的样式：</label></td>
                                        <td class="spe_line">
                                            <select id="cfg_ForumCssDefault_0" name="cfg_ForumCssDefault_0">
                                                <option value="default">银灰色</option>
                                                <option value="black_1">黑色_1</option>
                                                <option value="blue_1">蓝色_1</option>
                                                <option value="blue_2">蓝色_2</option>
                                                <option value="blue_3">蓝色_3</option>
                                                <option value="green_1">绿色_1</option>
                                                <option value="brown_1">棕色_1</option>
                                                <option value="brown_2">棕色_2</option>
                                                <option value="pink_1">粉红色_1</option>
                                                <option value="purple_1">紫色_1</option>
                                                <option value="red_1">红色(新年)_1</option>
                                                <option value="yellow_1">黄色_1</option>
                                                <option value="wow_1">魔兽_1</option>
                                                <option value="christ_1">圣诞_1</option>
                                            </select>
                                            {sel_ForumCssDefault}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_ForumCssDefaultWidth_0">论坛默认的样式宽度文件：</label></td>
                                        <td class="spe_line">
                                            <select id="cfg_ForumCssDefaultWidth_0" name="cfg_ForumCssDefaultWidth_0">
                                                <option value="w980">980像素宽</option>
                                                <option value="w80p">80%宽</option>
                                                <option value="w90p">90%宽</option>
                                                <option value="w100p">100%宽</option>
                                            </select>
                                            {sel_ForumCssDefaultWidth}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_ForumCssDefaultFontSize_0">论坛默认的样式字体文件：</label></td>
                                        <td class="spe_line">
                                            <select id="cfg_ForumCssDefaultFontSize_0" name="cfg_ForumCssDefaultFontSize_0">
                                                <option value="12">12像素（标准）</option>
                                                <option value="14">14像素（较大）</option>
                                            </select>
                                            {sel_ForumCssDefaultFontSize}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div id="tabs-2">
                            <div>
                                <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="spe_line">
                                            <label for="cfg_ForumTopInfo_2"></label><textarea id="cfg_ForumTopInfo_2" name="cfg_ForumTopInfo_2" style="width:90%;height:380px;">{cfg_ForumTopInfo_2}</textarea><br/>
                                            <input id="file_upload_to_forum_top_info" name="file_upload_to_forum_top_info" type="file"
                                                   class="input_box" size="7" style="width:60%; background: #ffffff;"/> <img
                                                id="loadingOfForumTopInfo" src="/system_template/common/images/loading1.gif"
                                                style="display:none;"/><input id="btnUploadToForumTopInfo" type="button" value="上传"/>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div id="tabs-3">
                            <div>
                                <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="spe_line">
                                            <label for="cfg_ForumBotInfo_2"></label><textarea id="cfg_ForumBotInfo_2" name="cfg_ForumBotInfo_2" style="width:90%;height:380px;">{cfg_ForumBotInfo_2}</textarea><br/>
                                            <input id="file_upload_to_forum_bot_info" name="file_upload_to_forum_bot_info" type="file"
                                                   class="input_box" size="7" style="width:60%; background: #ffffff;"/> <img
                                                id="loadingOfForumBotInfo" src="/system_template/common/images/loading1.gif"
                                                style="display:none;"/><input id="btnUploadToForumBotInfo" type="button" value="上传"/>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div id="tabs-4">
                            <div>
                                <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="spe_line" style="width:20%;" height="40" align="right">
                                            <label for="cfg_ForumTopicPageSize_3">主题列表每页记录数：</label></td>
                                        <td class="spe_line">
                                            <input name="cfg_ForumTopicPageSize_3" id="cfg_ForumTopicPageSize_3" value="{cfg_ForumTopicPageSize_3}" maxlength="3" type="text" class="input_number" style=" width: 50px;"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" style="width:20%;" height="40" align="right">
                                            <label for="cfg_ForumPostPageSize_3">帖子列表每页记录数：</label></td>
                                        <td class="spe_line">
                                            <input name="cfg_ForumPostPageSize_3" id="cfg_ForumPostPageSize_3" value="{cfg_ForumPostPageSize_3}" maxlength="3" type="text" class="input_number" style=" width: 50px;"/>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td height="60" align="center">
                    <input class="btn" value="确认并关闭" type="button" onclick="submitForm(1)"/>
                    <input class="btn" value="确认并继续" type="button" onclick="submitForm(0)"/>
                    <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>
