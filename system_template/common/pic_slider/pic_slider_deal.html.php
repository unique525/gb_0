<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript" src="/system_js/upload_file.js"></script>
    <script type="text/javascript">
        <!--
        $(function () {

        });

        function submitForm(closeTab) {
            if ($('#f_PicSliderTitle').val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("请输入标题");
            }
            else {
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
      action="/default.php?secu=manage&mod=pic_slider&m={method}&pic_slider_id={PicSliderId}&channel_id={ChannelId}&tab_index={tab_index}"
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
        <td class="spe_line" height="30" align="right"><label for="f_PicSliderTitle">标题：</label></td>
        <td class="spe_line">
            <input name="f_PicSliderTitle" id="f_PicSliderTitle" value="{PicSliderTitle}" type="text" class="input_box" style="width:300px;"/>
            <input name="f_SiteId" type="hidden" value="{SiteId}"/>
            <input name="f_ChannelId" type="hidden" value="{ChannelId}"/>
            <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_DirectUrl">转向网址：</label></td>
        <td class="spe_line">
            <input id="f_DirectUrl" name="f_DirectUrl" type="text" value="{DirectUrl}" maxlength="200" class="input_box" style="width:500px;"/>
        </td>
    </tr>

    <tr>
        <td class="spe_line" height="30" align="right"><label for="file_upload_file">图片：</label></td>
        <td class="spe_line">
            <input id="file_upload_file" name="file_upload_file" type="file" class="input_box"
                   style="width:400px;background:#ffffff;margin-top:3px;"/> <span id="preview_upload_file_id" class="show_title_pic" idvalue="{UploadFileId}" style="cursor:pointer">[预览]</span>

        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_Sort">排序号：</label></td>
        <td class="spe_line">
            <input id="f_Sort" name="f_Sort" type="text" value="{Sort}" maxlength="10" class="input_number"/>
        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_State">状态：</label></td>
        <td class="spe_line">
            <select id="f_State" name="f_State">
                <option value="0">新稿</option>
                <option value="30">已审</option>
                <option value="100">已删</option>
            </select>
            {s_State}
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
