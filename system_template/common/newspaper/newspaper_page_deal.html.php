<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript">
        <!--

        $(function () {


        });

        function submitForm(closeTab) {
            if ($('#f_NewspaperPageName').val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("请输入名称");
            } else {
                if (closeTab == 1) {
                    $("#CloseTab").val("1");
                } else {
                    $("#CloseTab").val("0");
                }
                $("#mainForm").attr("action", "/default.php?secu=manage" +
                    "&mod=newspaper_page&m={method}" +
                    "&newspaper_page_id={NewspaperPageId}" +
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
                <td class="spe_line" height="30" align="right"><label for="f_NewspaperPageName">名称：</label></td>
                <td class="spe_line">
                    <input name="f_NewspaperPageName" id="f_NewspaperPageName" value="{NewspaperPageName}" type="text"
                           maxlength="100" class="input_box" style="width:300px;"/>
                    <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_NewspaperPageNo">序号：</label></td>
                <td class="spe_line">
                    <input name="f_NewspaperPageNo" id="f_NewspaperPageNo" value="{NewspaperPageNo}" type="text"
                           maxlength="100" class="input_box" style="width:300px;"/>
                </td>
            </tr>


            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_Editor">编辑：</label></td>
                <td class="spe_line">
                    <input name="f_Editor" id="f_Editor" value="{Editor}" type="text"
                           maxlength="100" class="input_box" style="width:150px;"/>
                </td>
            </tr>


            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_IssueDepartment">签发部门：</label></td>
                <td class="spe_line">
                    <input name="f_IssueDepartment" id="f_IssueDepartment" value="{IssueDepartment}" type="text"
                           maxlength="100" class="input_box" style="width:150px;"/>
                </td>
            </tr>


            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_Issuer">签发人：</label></td>
                <td class="spe_line">
                    <input name="f_Issuer" id="f_Issuer" value="{Issuer}" type="text"
                           maxlength="100" class="input_box" style="width:150px;"/>
                </td>
            </tr>

            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_CreateDate">创建时间：</label></td>
                <td class="spe_line"><input id="f_CreateDate" name="f_CreateDate" value="{CreateDate}" type="text" class="input_box" style="width:180px;"/></td>
            </tr>



            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_ArticleCount">文章数量：</label></td>
                <td class="spe_line">
                    <input id="f_ArticleCount" name="f_ArticleCount" type="text" value="{ArticleCount}" maxlength="10" class="input_number"/>
                </td>
            </tr>


            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_PicCount">图片数量：</label></td>
                <td class="spe_line">
                    <input id="f_PicCount" name="f_PicCount" type="text" value="{PicCount}" maxlength="10" class="input_number"/>
                </td>
            </tr>


            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_PageWidth">版面宽度：</label></td>
                <td class="spe_line">
                    <input id="f_PageWidth" name="f_PageWidth" type="text" value="{PageWidth}" maxlength="10" class="input_number"/>
                </td>
            </tr>


            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_PageHeight">版面高度：</label></td>
                <td class="spe_line">
                    <input id="f_PageHeight" name="f_PageHeight" type="text" value="{PageHeight}" maxlength="10" class="input_number"/>
                </td>
            </tr>

            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_Sort">排序号：</label></td>
                <td class="spe_line">
                    <input id="f_Sort" name="f_Sort" type="text" value="{Sort}" maxlength="10" class="input_number"/> (越小越靠前)
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