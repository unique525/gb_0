<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript" src="/system_js/xheditor-1.1.14/xheditor-1.1.14-zh-cn.min.js"></script>

    <script type="text/javascript">
        <!--
        var editor;
        $(function () {
            editor = $('#f_NewspaperArticleContent').xheditor();
        });



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
                    "&mod=newspaper_article&m={method}" +
                    "&newspaper_article_id={NewspaperArticleId}" +
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
                <td class="spe_line" height="30" align="right"><label for="f_NewspaperArticleTitle">标题：</label></td>
                <td class="spe_line">
                    <input name="f_NewspaperArticleTitle" id="f_NewspaperArticleTitle" value="{NewspaperArticleTitle}" type="text"
                           maxlength="300" class="input_box" style="width:300px;"/>
                    <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_NewspaperArticleCiteTitle">引题：</label></td>
                <td class="spe_line">
                    <input name="f_NewspaperArticleCiteTitle" id="f_NewspaperArticleCiteTitle" value="{NewspaperArticleCiteTitle}" type="text"
                           maxlength="300" class="input_box" style="width:300px;"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_NewspaperArticleSubTitle">副题：</label></td>
                <td class="spe_line">
                    <input name="f_NewspaperArticleSubTitle" id="f_NewspaperArticleSubTitle" value="{NewspaperArticleSubTitle}" type="text"
                           maxlength="300" class="input_box" style="width:300px;"/>
                </td>
            </tr>


            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_NewspaperArticleContent">内容：</label></td>
                <td class="spe_line">
                    <textarea cols="30" rows="30" id="f_NewspaperArticleContent" name="f_NewspaperArticleContent" style="width:70%;height:250px;">{NewspaperArticleContent}</textarea>
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
                <td class="spe_line" height="30" align="right"><label for="f_NewspaperArticleType">类型：</label></td>
                <td class="spe_line">
                    <select id="f_NewspaperArticleType" name="f_NewspaperArticleType">
                        <option value="1">普通</option>
                        <option value="2">广告</option>
                    </select>
                    {s_NewspaperArticleType}
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