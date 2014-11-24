<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript">
        <!--

        $(function () {

            var strColumns = $("#f_ManageMenuOfColumnIdValue").val();
            var arrColumns = strColumns.split(",");
            var cbManageMenuOfColumnIdValue=$('input[type="checkbox"][name="cb_manage_menu_of_column_id_value"]');

            $.each(cbManageMenuOfColumnIdValue,function(i,n){

                for(var x=0;x<arrColumns.length;x++){

                    if(arrColumns[x] == n.value){

                        n.checked = true;

                    }
                }
            });

        });

        function submitForm(closeTab) {
            if ($('#f_ManageUserGroupName').val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("请输入管理分组名称");
            } else {
                if (closeTab == 1) {
                    $("#CloseTab").val("1");
                } else {
                    $("#CloseTab").val("0");
                }

                var cbManageMenuOfColumnIdValue=$('input[type="checkbox"][name="cb_manage_menu_of_column_id_value"]');
                var fManageMenuOfColumnIdValue = '';
                $.each(cbManageMenuOfColumnIdValue,function(i,n){
                    if (n.checked){
                        fManageMenuOfColumnIdValue += "," + n.value;
                    }
                });
                if(fManageMenuOfColumnIdValue.substring(0,1) == ','){
                    fManageMenuOfColumnIdValue = fManageMenuOfColumnIdValue.substring(1,fManageMenuOfColumnIdValue.length);
                }

                $("#f_ManageMenuOfColumnIdValue").val(fManageMenuOfColumnIdValue);



                $("#mainForm").attr("action", "/default.php?secu=manage" +
                    "&mod=manage_user_group&m={method}" +
                    "&manage_user_group_id={ManageUserGroupId}" +
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
                <td class="spe_line" height="30" align="right"><label for="f_ManageUserGroupName">管理分组名称：</label></td>
                <td class="spe_line">
                    <input name="f_ManageUserGroupName" id="f_ManageUserGroupName" value="{ManageUserGroupName}" type="text"
                           maxlength="100" class="input_box" style="width:300px;"/>
                    <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="cb_manage_menu_of_column_id_value">可用的左则导航：</label></td>
                <td class="spe_line">
                    <input id="f_ManageMenuOfColumnIdValue" name="f_ManageMenuOfColumnIdValue" type="hidden" value="{ManageMenuOfColumnIdValue}"/>
                    <icms id="manage_menu_of_column" type="list">
                            <item>
                                <![CDATA[
                                <input id="cb_manage_menu_of_column_id_value" name="cb_manage_menu_of_column_id_value" type="checkbox"
                                       value="{f_ManageMenuOfColumnId}" />&nbsp;&nbsp;{f_ManageMenuOfColumnName}&nbsp;&nbsp;&nbsp;&nbsp;
                                ]]>
                            </item>
                    </icms>

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
