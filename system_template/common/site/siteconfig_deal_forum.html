<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link id="css_font" type="text/css" href="/system_template/{templatename}/images/font14.css" rel="stylesheet" />
        <link type="text/css" href="/system_template/{templatename}/images/common.css" rel="stylesheet" />
        <link type="text/css" href="/system_template/{templatename}/images/jqueryui/jquery-ui.min.css" rel="stylesheet" />
        <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="/system_js/common.js"></script>
        <script type="text/javascript" src="/system_js/jquery.cookie.js"></script>
        <script type="text/javascript" src="/system_js/jqueryui/jquery-ui.min.js"></script>
        <script type="text/javascript" src="/system_js/ajaxfileupload.js"></script>
        <script type="text/javascript" src="/system_js/xheditor-1.1.13/xheditor-1.1.13-zh-cn.min.js"></script>

        <script type="text/javascript">
            var editor1;
            var editor2;
            $(function() {
                editor1 = $('#cfg_ForumTopinfo_2').xheditor();
                editor2 = $('#cfg_ForumBotinfo_2').xheditor();
                $('#tabs').tabs();
            });

            function sub() {
                $('#mainform').submit();
            }


            function ajaxForumLogoImageUpload() {
                $("#ForumLogoImageloading").ajaxStart(function() {
                    $(this).show();
                }).ajaxComplete(function() {
                    $(this).hide();
                });

                $.ajaxFileUpload({
                    url: '{rootpath}/common/index.php?a=upload&ifn=ForumLogoImageToUpload&sid={siteid}&type=16&returntype=0',
                    secureuri: false,
                    fileElementId: 'ForumLogoImageToUpload',
                    dataType: 'json',
                    success: function(data, status) {
                        if (typeof (data.error) != 'undefined') {
                            if (data.error != '') {
                                $("#resulttable").html(data.error);
                                $("#dialog_resultbox").dialog({
                                });
                            }
                            else {
                                $("#cfg_ForumLogoImage_0").val(data['fileurl']);
                                $("#preview_ForumLogoImage").attr("href", data['fileurl']);
                            }
                        }
                    },
                    error: function(data, status, e) {
                        alert(e);
                    }
                });
                return false;

            }

            /**
             * ajax上传
             */
            function ajaxForumBackGroundUpload() {
                $("#ForumBackgroundloading")
                        .ajaxStart(function() {
                    $(this).show();
                })
                        .ajaxComplete(function() {
                    $(this).hide();
                });

                $.ajaxFileUpload({
                    url: '{rootpath}/common/index.php?a=upload&ifn=ForumBackgroundToUpload&sid={siteid}&type=16&returntype=0',
                    secureuri: false,
                    fileElementId: 'ForumBackgroundToUpload',
                    dataType: 'json',
                    success: function(data, status) {
                        if (typeof (data.error) !== 'undefined') {
                            if (data.error !== '') {
                                $("#resulttable").html(data.error);
                                $("#dialog_resultbox").dialog({
                                });
                            }
                            else {
                                $("#cfg_ForumBackground_0").val(data['fileurl']);
                                $("#preview_ForumBackground").attr("href", data['fileurl']);
                            }
                        }
                    },
                    error: function(data, status, e) {
                        alert(e);
                    }
                })
                return false;

            }


            function ajaxForumTopinfoUpload() {
                $("#ForumTopinfoloading")
                        .ajaxStart(function() {
                    $(this).show();
                })
                        .ajaxComplete(function() {
                    $(this).hide();
                });

                $.ajaxFileUpload({
                    url: '{rootpath}/common/index.php?a=upload&ifn=ForumTopinfoToUpload&sid={siteid}&type=16&returntype=0',
                    secureuri: false,
                    fileElementId: 'ForumTopinfoToUpload',
                    dataType: 'json',
                    success: function(data, status) {
                        if (typeof (data.error) !== 'undefined') {
                            if (data.error !== '') {
                                $("#resulttable").html(data.error);
                                $("#dialog_resultbox").dialog({
                                });
                            }
                            else {
                                var outfile = tofile_html(data['fileurl']);

                                editor1.appendHTML(outfile);
                            }
                        }
                    },
                    error: function(data, status, e) {
                        alert(e);
                    }
                })
                return false;

            }


            function ajaxForumBotinfoUpload() {
                $("#ForumBotinfoloading")
                        .ajaxStart(function() {
                    $(this).show();
                })
                        .ajaxComplete(function() {
                    $(this).hide();
                });

                $.ajaxFileUpload({
                    url: '{rootpath}/common/index.php?a=upload&ifn=ForumBotinfoToUpload&sid={siteid}&type=16&returntype=0',
                    secureuri: false,
                    fileElementId: 'ForumBotinfoToUpload',
                    dataType: 'json',
                    success: function(data, status) {
                        if (typeof (data.error) != 'undefined') {
                            if (data.error != '') {
                                $("#resulttable").html(data.error);
                                $("#dialog_resultbox").dialog({
                                });
                            }
                            else {
                                var outfile = tofile_html(data['fileurl']);

                                editor2.appendHTML(outfile);
                            }
                        }
                    },
                    error: function(data, status, e) {
                        alert(e);
                    }
                })
                return false;

            }
        </script>
    </head>
    <body>
        <div id="dialog_resultbox" title="" style="display:none;">
            <div id="resulttable">

            </div>
        </div>
        <form id="mainform" action="/default.php?secu=manage&mod=siteconfig&a=set&type=1&sid={siteid}" method="post">
            <div id="tabs" style="border: none; width: 99%; margin-left: 0;">
                <ul>
                    <li><a href="#tabs-1">基本设置</a></li>
                    <li><a href="#tabs-2">论坛顶部信息</a></li>
                    <li><a href="#tabs-3">论坛底部信息</a></li>
                    <li><a href="#tabs-4">帖子相关</a></li>
                </ul>
                <div id="tabs-1">
                    <div style="margin: 0 auto;margin-left: 10px;">
                        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td class="speline" height="30" align="right">论坛的IE标题：</td>
                                <td class="speline">
                                    <input name="cfg_ForumIeTitle_0" id="cfg_ForumIeTitle_0" value="{cfg_ForumIeTitle_0}" maxlength="200" type="text" class="inputbox" style=" width: 500px;" />
                                </td>
                            </tr>
                            <tr>
                                <td class="speline" height="30" align="right">论坛的IE keywords：</td>
                                <td class="speline">
                                    <input name="cfg_ForumIeKeywords_0" id="cfg_ForumIeKeywords_0" value="{cfg_ForumIeKeywords_0}" maxlength="200" type="text" class="inputbox" style=" width: 500px;" />
                                </td>
                            </tr>
                            <tr>
                                <td class="speline" height="30" align="right">论坛的IE Description：</td>
                                <td class="speline">
                                    <input name="cfg_ForumIeDescription_0" id="cfg_ForumIeDescription_0" value="{cfg_ForumIeDescription_0}" maxlength="200" type="text" class="inputbox" style=" width: 500px;" />
                                </td>
                            </tr>
                            <tr>
                                <td class="speline" height="30" align="right">论坛LOGO图片：</td>
                                <td class="speline">

                                    <input id="cfg_ForumLogoImage_0" name="cfg_ForumLogoImage_0" value="{cfg_ForumLogoImage_0}" maxlength="200" type="text" class="inputbox" style=" width: 500px;" /> [<a id="preview_ForumLogoImage" href="" target="_blank">预览</a>]<br />
                                    <input id="ForumLogoImageToUpload" name="ForumLogoImageToUpload" type="file" class="inputbox" style="width:200px; background: #ffffff;" /> <img id="ForumLogoImageloading" src="{rootpath}/images/ui-anim_basic_16x16.gif" style="display:none;" />
                                    <input id="btnForumLogoImageUpload" onclick="return ajaxForumLogoImageUpload()" type="button" value="上传" />
                                </td>
                            </tr>
                            <tr>
                                <td class="speline" height="30" align="right">论坛背景图片：</td>
                                <td class="speline">

                                    <input id="cfg_ForumBackground_0" name="cfg_ForumBackground_0" value="{cfg_ForumBackground_0}" maxlength="200" type="text" class="inputbox" style=" width: 500px;" /> [<a id="preview_ForumBackground" href="" target="_blank">预览</a>]<br />
                                    <input id="ForumBackgroundToUpload" name="ForumBackgroundToUpload" type="file" class="inputbox" style="width:200px; background: #ffffff;" /> <img id="ForumBackgroundloading" src="{rootpath}/images/ui-anim_basic_16x16.gif" style="display:none;" />
                                    <input id="btnForumBackgroundUpload" onclick="return ajaxForumBackgroundUpload()" type="button" value="上传" />
                                </td>
                            </tr>
                            <tr>
                                <td class="speline" height="30" align="right">论坛默认的样式：</td>
                                <td class="speline">
                                    <select id="cfg_ForumCssDefault_0" name="cfg_ForumCssDefault_0">
                                        <option {sel_ForumCssDefault_default} value="default">银灰色</option>
                                        <option {sel_ForumCssDefault_black_1} value="black_1">黑色_1</option>
                                        <option {sel_ForumCssDefault_blue_1} value="blue_1">蓝色_1</option>
                                        <option {sel_ForumCssDefault_blue_2} value="blue_2">蓝色_2</option>
                                        <option {sel_ForumCssDefault_blue_3} value="blue_3">蓝色_3</option>
                                        <option {sel_ForumCssDefault_green_1} value="green_1">绿色_1</option>
                                        <option {sel_ForumCssDefault_brown_1} value="brown_1">棕色_1</option>
                                        <option {sel_ForumCssDefault_brown_2} value="brown_2">棕色_2</option>
                                        <option {sel_ForumCssDefault_pink_1} value="pink_1">粉红色_1</option>
                                        <option {sel_ForumCssDefault_purple_1} value="purple_1">紫色_1</option>
                                        <option {sel_ForumCssDefault_red_1} value="red_1">红色(新年)_1</option>
                                        <option {sel_ForumCssDefault_yellow_1} value="yellow_1">黄色_1</option>
                                        <option {sel_ForumCssDefault_wow_1} value="wow_1">魔兽_1</option>
                                        <option {sel_ForumCssDefault_christ_1} value="christ_1">圣诞_1</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="speline" height="30" align="right">论坛默认的样式宽度文件：</td>
                                <td class="speline">
                                    <select id="cfg_ForumCssDefaultWidth_0" name="cfg_ForumCssDefaultWidth_0">
                                        <option {sel_ForumCssDefaultWidth_w980} value="w980">980像素宽</option>
                                        <option {sel_ForumCssDefaultWidth_w80p} value="w80p">80%宽</option>
                                        <option {sel_ForumCssDefaultWidth_w90p} value="w90p">90%宽</option>
                                        <option {sel_ForumCssDefaultWidth_w100p} value="w100p">100%宽</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="speline" height="30" align="right">论坛默认的样式宽度文件：</td>
                                <td class="speline">
                                    <select id="cfg_ForumCssDefaultFontSize_0" name="cfg_ForumCssDefaultFontSize_0">
                                        <option {sel_ForumCssDefaultFontSize_12} value="12">12像素（标准）</option>
                                        <option {sel_ForumCssDefaultFontSize_14} value="14">14像素（较大）</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div id="tabs-2">
                    <div style="margin: 0 auto;margin-left: 10px;">
                        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td class="speline">
                                    <textarea id="cfg_ForumTopinfo_2" name="cfg_ForumTopinfo_2" style="width:90%;height:380px;">{cfg_ForumTopinfo_2}</textarea><br />
                                    <input id="ForumTopinfoToUpload" name="ForumTopinfoToUpload" type="file" class="inputbox" style="width:200px; background: #ffffff;" /> <img id="ForumTopinfoloading" src="{rootpath}/images/ui-anim_basic_16x16.gif" style="display:none;" />
                                    <input id="btnForumTopinfoUpload" onclick="return ajaxForumTopinfoUpload()" type="button" value="上传" />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div id="tabs-3">
                    <div style="margin: 0 auto;margin-left: 10px;">
                        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td class="speline">
                                    <textarea id="cfg_ForumBotinfo_2" name="cfg_ForumBotinfo_2" style="width:90%;height:380px;">{cfg_ForumBotinfo_2}</textarea><br />
                                    <input id="ForumBotinfoToUpload" name="ForumBotinfoToUpload" type="file" class="inputbox" style="width:200px; background: #ffffff;" /> <img id="ForumBotinfoloading" src="{rootpath}/images/ui-anim_basic_16x16.gif" style="display:none;" />
                                    <input id="btnForumBotinfoUpload" onclick="return ajaxForumBotinfoUpload()" type="button" value="上传" />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div id="tabs-4">
                    <div style="margin: 0 auto;margin-left: 10px;">
                        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td class="speline" style="width:20%;" height="30" align="right">主题列表每页记录数：</td>
                                <td class="speline">
                                    <input name="cfg_ForumTopicPageSize_3" id="cfg_ForumTopicPageSize_3" value="{cfg_ForumTopicPageSize_3}" maxlength="3" type="text" class="inputnumber" style=" width: 50px;" />
                                </td>
                            </tr>
                            <tr>
                                <td class="speline" style="width:20%;" height="30" align="right">帖子列表每页记录数：</td>
                                <td class="speline">
                                    <input name="cfg_ForumPostPageSize_3" id="cfg_ForumPostPageSize_3" value="{cfg_ForumPostPageSize_3}" maxlength="3" type="text" class="inputnumber" style=" width: 50px;" />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div id="bot_button">
                <div style="padding-top:10px;padding-left:10px;">
                    <input class="btn" value="确 认" type="button" onclick="sub()" /> <input class="btn" value="取 消" type="button" onclick="javascript:history.go(-1);" />
                </div>
            </div>
        </form>
    </body>
</html>
