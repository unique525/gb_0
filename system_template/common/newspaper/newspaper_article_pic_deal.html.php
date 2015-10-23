<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript">
        <!--

        function submitForm(closeTab) {
            if ($('#f_NewspaperArticleTitle').val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("请输入标题");
            } else {
                if (closeTab == 1) {
                    $("#CloseTab").val("1");
                } else {
                    $("#CloseTab").val("0");
                }
                $("#mainForm").attr("action", "/default.php?secu=manage" +
                    "&mod=newspaper_article_pic&m={method}" +
                    "&newspaper_article_id={NewspaperArticleId}" +
                    "&newspaper_article_pic_id={NewspaperArticlePicId}" +
                    "&site_id=" + parent.G_NowSiteId +
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
                <td class="spe_line" height="30" align="right"><label for="f_Remark">标题：</label></td>
                <td class="spe_line">
                    <input name="f_Remark" id="f_Remark" value="{Remark}" type="text"
                           maxlength="300" class="input_box" style="width:300px;"/>
                    <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" style="width:160px;height:40px;text-align: right;">图片：</td>
                <td colspan="3" class="spe_line" style="height:40px;line-height:40px;text-align: left;display:{DisplayCreate}">
                    <input name="newspaper_article_pic" id="newspaper_article_pic" type="file"
                           maxlength="300" class="input_box" style="width:300px;"/>
                </td>
                <td colspan="3" class="spe_line" style="height:40px;line-height:40px;text-align: left;display:{DisplayModify}">
                    <a href="/default.php?secu=manage&mod=upload_file&m=modify&table_id={NewspaperArticlePicId}&site_id={site_id}&upload_file_id={UploadFileId}">文件管理</a>

                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_ShowInSlider">控件显示：</label></td>
                <td class="spe_line">
                    <select id="f_ShowInSlider" name="f_ShowInSlider">
                        <option value="0">停用</option>
                        <option value="1">启用</option>
                    </select>
                    {s_ShowInSlider}
                </td>
            </tr>

            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_CreateDate">创建时间：</label></td>
                <td class="spe_line"><input id="f_CreateDate" name="f_CreateDate" value="{CreateDate}" type="text" class="input_box" style="width:180px;"/></td>
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
                        <option value="0">启用</option>
                        <option value="100">停用</option>
                    </select>
                    {s_State}
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