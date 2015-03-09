<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript">
        <!--

        function submitForm(closeTab) {

                if (closeTab == 1) {
                    $("#CloseTab").val("1");
                } else {
                    $("#CloseTab").val("0");
                }
                $("#mainForm").attr("action", "/default.php?secu=manage" +
                    "&mod=upload_file&m={method}" +
                    "&upload_file_id={UploadFileId}" +
                    "&table_id={TableId}" +
                    "&site_id={SiteId}" +
                    "&tab_index=" + parent.G_TabIndex + "");

                $('#mainForm').submit();

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
                <td class="spe_line" height="30" align="right"><label for="f_UploadFileTitle">标题：</label></td>
                <td class="spe_line">
                    <input name="f_UploadFileTitle" id="f_UploadFileTitle" value="{UploadFileTitle}" type="text"
                           maxlength="200" class="input_box" style="width:300px;"/>
                    <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" style="width:160px;height:40px;text-align: right;">原文件：</td>
                <td colspan="3" class="spe_line" style="text-align: left">
                    <input id="file_UploadFile" name="file_UploadFile" type="file" class="input_box" style="width:400px; background: #ffffff;" />
                    <span id="preview_title_pic" class="show_title_pic" idvalue="{UploadFileId}" style="cursor:pointer;display:none;">[预览]</span>

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