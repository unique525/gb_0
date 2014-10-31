<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    {common_head}
    <script type="text/javascript">
        function submitForm(continueCreate) {

                if (continueCreate == 1) {
                    $("#CloseTab").val("0");
                } else {
                    $("#CloseTab").val("1");
                }
                $('#main_form').submit();
        }

        $().ready(function(){

            var deleteAttachment = $(".btn_delete_attachment");
            deleteAttachment.click(function () {
                var r=confirm("是否确认删除！");
                if(r==true){
                    var customFormContentId = $(this).attr("id");

                    $.ajax({
                        type: "get",
                        url: "/default.php?secu=manage" +
                            "&mod=custom_form_content" +
                            "&m=async_delete_attachment",
                        data: {
                            custom_form_content_id: customFormContentId
                        },
                        dataType: "jsonp",
                        jsonp: "jsonpcallback",
                        success: function(data) {
                            var result = parseInt(data["result"]);
                            if(result>0){
                                alert("操作完成");
                            }else{
                                alert("没有上传附件或删除失败");
                            }
                        }
                    });
                }
            });

        });

    </script>
</head>
<body>
{common_body_deal}
<form id="main_form" enctype="multipart/form-data" action="/default.php?secu=manage&mod=custom_form_record&m={method}&custom_form_id={CustomFormId}&custom_form_record_id={CustomFormRecordId}&tab_index={TabIndex}" method="post">
    <div style="margin:10px auto;">
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="spe_line" height="40" align="right">
                    <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/>
                    <input class="btn" value="确认并继续新增" style="display:{display}" type="button" onclick="submitForm(1)"/>
                    <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                </td>
            </tr>
        </table>
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            {CustomFormContentTable}
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_sort">排序：</label></td>
                <td class="spe_line"><input name="f_sort" id="f_sort" value="{sort}" type="text" class="input_number" style=" width: 60px;" />(注:输入数字,数值越大越靠前)</td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_state">是否启用：</label></td>
                <td class="spe_line">
                    <input type="hidden" id="f_CustomFormId" name="f_CustomFormId" value="{CustomFormId}" />
                    <input type="hidden" id="f_CreateDate" name="f_CreateDate" value="{CreateDate}" />
                    <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
                    <select id="f_state" name="f_state">
                        <option value="0">未审</option>
                        <option value="1">已审</option>
                        <option value="100">停用</option>
                    </select>
                    {s_state}
                </td>
            </tr>

            <tr>
                <td class="spe_line" height="40" align="right">
                    <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/>
                    <input class="btn" value="确认并继续新增" style="display:{display}" type="button" onclick="submitForm(1)"/>
                    <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                </td>
            </tr>

        </table>

    </div>
</form>
</body>
</html>
