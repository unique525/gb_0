<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}


    <script>
        function submitForm(continueCreate) {
            var submit=1;
            if ($('#TemplateLibraryName').val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("请输入名称");
                submit=0;
            }
            if(submit==1) {
                if (continueCreate == 1) {
                    $("#CloseTab").val("0");
                } else {
                    $("#CloseTab").val("1");
                }
                $('#main_form').submit();
            }
        }
    </script>
    </head>
    <body>
    {common_body_deal}
        <form id="main_form" action="/default.php?secu=manage&mod=template_library&m={method}&template_library_id={TemplateLibraryId}&site_id={SiteId}&tab_index={TabIndex}" method="post">

            <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
            <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="spe_line" height="40" align="right">
                        <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/>
                        <input class="btn" value="确认并继续新增" style="display:{display}" type="button" onclick="submitForm(1)"/>
                        <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                    </td>
                </tr>
            </table>
            <div style="margin: 0 auto 10px;">
            <table width="99%" class="doc_grid" cellpadding="0" cellspacing="0">
                <tr class="grid_item">
                    <td class="spe_line" style="text-align:center"><label for="TemplateLibraryName">模板库名称</label></td>
                    <td class="spe_line"><input type="text" id="TemplateLibraryName" name="f_TemplateLibraryName" value="{TemplateLibraryName}"/></td>
                </tr>
            </table>
                <div id="bot_button">
                    <div style="padding-top:3px;">
                        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td colspan="2" height="30" align="center">
                                    <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/>
                                    <input class="btn" value="确认并继续新增" style="display:{display}" type="button" onclick="submitForm(1)"/>
                                    <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                </div>
        </form>
    </body>
</html>