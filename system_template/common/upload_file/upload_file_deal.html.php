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
                    <input class="btn" value="确认并返回" type="button" onclick="submitForm(1)"/>
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
                <td class="spe_line" height="30" align="right"><label for="f_UploadFileInfo">简介：</label></td>
                <td class="spe_line">
                    <input name="f_UploadFileInfo" id="f_UploadFileInfo" value="{UploadFileInfo}" type="text"
                           maxlength="1000" class="input_box" style="width:400px;"/>

                </td>
            </tr>

            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_UploadFilePath">原文件网址：</label></td>
                <td class="spe_line">
                    <input name="f_UploadFilePath" readonly="readonly" id="f_UploadFilePath" value="{UploadFilePath}" type="text"
                           maxlength="200" class="input_box" style="width:400px;"/> <a href="{UploadFilePath}" target="_blank">[预览]</a>

                </td>
            </tr>
            <tr>
                <td class="spe_line" style="width:160px;height:40px;text-align: right;">上传原文件：</td>
                <td colspan="3" class="spe_line" style="text-align: left">
                    <input id="file_UploadFile" name="file_UploadFile" type="file" class="input_box" style="width:400px; background: #ffffff;" />
                    <span id="preview_title_pic" class="show_title_pic" idvalue="{UploadFileId}" style="cursor:pointer;display:none;">[预览]</span>

                </td>
            </tr>

            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_UploadFileName">文件名：</label></td>
                <td class="spe_line">
                    <input name="f_UploadFileName" readonly="readonly" id="f_UploadFileName" value="{UploadFileName}" type="text"
                           maxlength="200" class="input_box" style="width:400px;"/>

                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_UploadFileExtentionName">扩展名：</label></td>
                <td class="spe_line">
                    <input name="f_UploadFileExtentionName" readonly="readonly" id="f_UploadFileExtentionName" value="{UploadFileExtentionName}" type="text"
                           maxlength="200" class="input_box" style="width:400px;"/>

                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_UploadFileSize">文件大小：</label></td>
                <td class="spe_line">
                    <input name="f_UploadFileSize" readonly="readonly" id="f_UploadFileSize" value="{UploadFileSize}" type="text"
                           maxlength="200" class="input_box" style="width:400px;"/>

                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_UploadFileOrgName">原始文件：</label></td>
                <td class="spe_line">
                    <input name="f_UploadFileOrgName" readonly="readonly" id="f_UploadFileOrgName" value="{UploadFileOrgName}" type="text"
                           maxlength="200" class="input_box" style="width:400px;"/>

                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_UploadFileMobilePath">移动客户端文件：</label></td>
                <td class="spe_line">
                    <input name="f_UploadFileMobilePath" readonly="readonly" id="f_UploadFileMobilePath" value="{UploadFileMobilePath}" type="text"
                           maxlength="200" class="input_box" style="width:400px;"/> <a href="{UploadFileMobilePath}" target="_blank">[预览]</a>

                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_UploadFilePadPath">平板客户端文件：</label></td>
                <td class="spe_line">
                    <input name="f_UploadFilePadPath" readonly="readonly" id="f_UploadFilePadPath" value="{UploadFilePadPath}" type="text"
                           maxlength="200" class="input_box" style="width:400px;"/> <a href="{UploadFilePadPath}" target="_blank">[预览]</a>

                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_UploadFileThumbPath1">缩略图文件1：</label></td>
                <td class="spe_line">
                    <input name="f_UploadFileThumbPath1" readonly="readonly" id="f_UploadFileThumbPath1" value="{UploadFileThumbPath1}" type="text"
                           maxlength="200" class="input_box" style="width:400px;"/> <a href="{UploadFileThumbPath1}" target="_blank">[预览]</a>

                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_UploadFileThumbPath2">缩略图文件2：</label></td>
                <td class="spe_line">
                    <input name="f_UploadFileThumbPath2" readonly="readonly" id="f_UploadFileThumbPath2" value="{UploadFileThumbPath2}" type="text"
                           maxlength="200" class="input_box" style="width:400px;"/> <a href="{UploadFileThumbPath2}" target="_blank">[预览]</a>

                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_UploadFileThumbPath3">缩略图文件3：</label></td>
                <td class="spe_line">
                    <input name="f_UploadFileThumbPath3" readonly="readonly" id="f_UploadFileThumbPath3" value="{UploadFileThumbPath3}" type="text"
                           maxlength="200" class="input_box" style="width:400px;"/> <a href="{UploadFileThumbPath3}" target="_blank">[预览]</a>

                </td>
            </tr>

            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_UploadFileWatermarkPath1">水印图文件1：</label></td>
                <td class="spe_line">
                    <input name="f_UploadFileWatermarkPath1" readonly="readonly" id="f_UploadFileWatermarkPath1" value="{UploadFileWatermarkPath1}" type="text"
                           maxlength="200" class="input_box" style="width:400px;"/> <a href="{UploadFileWatermarkPath1}" target="_blank">[预览]</a>

                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_UploadFileWatermarkPath2">水印图文件2：</label></td>
                <td class="spe_line">
                    <input name="f_UploadFileWatermarkPath2" readonly="readonly" id="f_UploadFileWatermarkPath2" value="{UploadFileWatermarkPath2}" type="text"
                           maxlength="200" class="input_box" style="width:400px;"/> <a href="{UploadFileWatermarkPath2}" target="_blank">[预览]</a>

                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_UploadFileCompressPath1">压缩图文件1：</label></td>
                <td class="spe_line">
                    <input name="f_UploadFileCompressPath1" readonly="readonly" id="f_UploadFileCompressPath1" value="{UploadFileCompressPath1}" type="text"
                           maxlength="200" class="input_box" style="width:400px;"/> <a href="{UploadFileCompressPath1}" target="_blank">[预览]</a>

                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_UploadFileCompressPath2">压缩图文件2：</label></td>
                <td class="spe_line">
                    <input name="f_UploadFileCompressPath2" readonly="readonly" id="f_UploadFileCompressPath2" value="{UploadFileCompressPath2}" type="text"
                           maxlength="200" class="input_box" style="width:400px;"/> <a href="{UploadFileCompressPath2}" target="_blank">[预览]</a>

                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_TableId">TableId：</label></td>
                <td class="spe_line">
                    <input name="f_TableId" readonly="readonly" id="f_TableId" value="{TableId}" type="text"
                           maxlength="200" class="input_box" style="width:400px;"/>

                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_TableType">TableType：</label></td>
                <td class="spe_line">
                    <input name="f_TableType" readonly="readonly" id="f_TableType" value="{TableType}" type="text"
                           maxlength="200" class="input_box" style="width:400px;"/>

                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_CreateDate">创建时间：</label></td>
                <td class="spe_line">
                    <input name="f_CreateDate" readonly="readonly" id="f_CreateDate" value="{CreateDate}" type="text"
                           maxlength="200" class="input_box" style="width:400px;"/>

                </td>
            </tr>






        </table>

        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td height="60" align="center">
                    <input class="btn" value="确认并返回" type="button" onclick="submitForm(1)"/>
                    <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                </td>
            </tr>
        </table>
    </div>
</form>
</body>
</html>