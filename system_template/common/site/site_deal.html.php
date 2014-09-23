<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript" src="/system_js/xheditor-1.1.13/xheditor-1.1.13-zh-cn.min.js"></script>
    <script type="text/javascript">
        <!--
        var editor;
        $(function () {
            editor = $('#f_ChannelIntro').xheditor();


            $("#preview_title_pic1").click(function () {
                var imgTitlePic1 = "{TitlePic1}";
                if (imgTitlePic1 !== '') {
                    var imageOfTitlePic1 = new Image();
                    imageOfTitlePic1.src = imgTitlePic1;
                    $("#dialog_box").dialog({
                        width: imageOfTitlePic1.width + 30,
                        height: imageOfTitlePic1.height + 30
                    });
                    var imgHtml = '<' + 'img src="' + imgTitlePic1 + '" alt="" />';
                    $("#dialog_content").html(imgHtml);

                }
                else {
                    $("#dialog_box").dialog({width: 300, height: 100});
                    $("#dialog_content").html("还没有上传题图1");
                }
            });


            $("#preview_title_pic2").click(function () {
                var imgTitlePic2 = "{TitlePic2}";
                if (imgTitlePic2 !== '') {
                    var imageOfTitlePic2 = new Image();
                    imageOfTitlePic2.src = imgTitlePic2;
                    $("#dialog_box").dialog({
                        width: imageOfTitlePic2.width + 30,
                        height: imageOfTitlePic2.height + 30
                    });
                    var imgHtml = '<' + 'img src="' + imgTitlePic2 + '" alt="" />';
                    $("#dialog_content").html(imgHtml);
                }
                else {
                    $("#dialog_box").dialog({width: 300, height: 100});
                    $("#dialog_content").html("还没有上传题图2");
                }
            });

            $("#preview_title_pic3").click(function () {
                var imgTitlePic3 = "{TitlePic2}";
                if (imgTitlePic3 !== '') {
                    var imageOfTitlePic3 = new Image();
                    imageOfTitlePic3.src = imgTitlePic3;
                    $("#dialog_box").dialog({
                        width: imageOfTitlePic3.width + 30,
                        height: imageOfTitlePic3.height + 30
                    });
                    var imgHtml = '<' + 'img src="' + imgTitlePic3 + '" alt="" />';
                    $("#dialog_content").html(imgHtml);
                }
                else {
                    $("#dialog_box").dialog({width: 300, height: 100});
                    $("#dialog_content").html("还没有上传题图2");
                }
            });

            var selChannelType = $("#f_ChannelType");
            selChannelType.change(function () {
                $(this).css("background-color", "#FFFFCC");
                var dnt = $(this).val();
                if (dnt == '50') {
                    $(".type_0").css("display", "none");
                    $(".type_1").css("display", "");
                } else {
                    $(".type_0").css("display", "");
                    $(".type_1").css("display", "none");
                }

            });
            selChannelType.change();
        });

        function submitForm(closeTab) {
            if ($('#f_SiteName').val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("请输入频道名称");
            } else if ($('#f_ChannelIntro').text().length > 1000) {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("频道简介不能超过1000个字符");
            } else {
                if (closeTab == 1) {
                    $("#CloseTab").val("1");
                } else {
                    $("#CloseTab").val("0");
                }
                $('#mainForm').submit();
            }
        }
        -->
    </script>
</head>
<body>
{common_body_deal}
<form id="mainForm" enctype="multipart/form-data"
      action="/default.php?secu=manage&mod=site&m={method}&site_id={SiteId}"
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
        <td class="spe_line" height="30" align="right"><label for="f_SiteName">站点名称：</label></td>
        <td class="spe_line">
            <input name="f_SiteName" id="f_SiteName" value="{SiteName}" type="text" class="input_box" style="width:300px;"/>
        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_SiteType">站点类型：</label></td>
        <td class="spe_line">
            <select id="f_SiteType" name="f_SiteType">
                <option value="0">普通</option>
            </select>
            {s_SiteType}
        </td>
    </tr>


    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_SiteUrl">站点网址：</label></td>
        <td class="spe_line">
            <input id="f_SiteUrl" name="f_SiteUrl" type="text" value="{SiteUrl}" class="input_box"
                   style="width:400px;" maxlength="200"/>
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
            {f_PublishType}
        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_Sort">排序号：</label></td>
        <td class="spe_line">
            <input id="f_Sort" name="f_Sort" type="text" value="{Sort}" maxlength="10" class="input_number"/>
        </td>
    </tr>


    <tr>
        <td class="spe_line" height="30" align="right"><label for="file_title_pic_1">站点图标：</label></td>
        <td class="spe_line">
            <input id="file_title_pic_1" name="file_title_pic_1" type="file" class="input_box"
                   style="width:400px;background:#ffffff;margin-top:3px;"/> <span id="preview_title_pic1"
                                                                                  style="cursor:pointer">[预览]</span>

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
            <input class="btn" value="确认并关闭" type="button" onclick="submitForm(1)"/> <input class="btn" value="确认并继续"
                                                                                            type="button"
                                                                                            onclick="submitForm(0)"/>
            <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
        </td>
    </tr>
</table>
</div>
</form>
</body>
</html>
