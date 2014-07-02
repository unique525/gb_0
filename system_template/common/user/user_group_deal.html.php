<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <style type="text/css">

    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#sub").click(function(){
                if($("#UserGroupName").val() == ""){
                    alert("请填写会员组名称");
                }else{
                    $("#mainForm").submit();
                }
            });
        });
    </script>
</head>
<body>
    <div class="div_list" style="border:1px #CCC solid">
        {common_body_deal}
        <form id="mainForm" action="/default.php?secu=manage&mod=user_group&m={method}&user_group_id={UserGroupId}&site_id={SiteId}" method="post">
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="spe_line" height="30" align="right">上级部门：</td>
                <td class="spe_line">
                    <label>
                        <select>
                            <option value="0" {s_parent_id_0}>暂无</option>
                        </select>
                    </label>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right">名称：</td>
                <td class="spe_line"><label><input id="UserGroupName" type="text" value="{UserGroupName}" name="f_UserGroupName"/></label></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right">简介：</td>
                <td class="spe_line"><label><input type="text" value="{UserGroupShortName}" name="f_UserGroupShortName"/></label></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right">排序：</td>
                <td class="spe_line"><label><input type="text" value="{Sort}" name="f_Sort"/>（注：输入数字，数值越大越靠前）</label></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right">级别：</td>
                <td class="spe_line"><label><input type="text" value="{Rank}" name="f_Rank"/></label></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right">是否启用：</td>
                <td class="spe_line">
                    <label>
                        <select>
                            <option value="0" {s_state_0}>开启</option>
                            <option value="100" {s_state_0}>停用</option>
                        </select>
                    </label>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right">是否锁定：</td>
                <td class="spe_line">
                    <label>
                        <select>
                            <option value="">不锁定</option>
                            <option value="">锁定</option>
                        </select>
                    </label>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right">
                    <input id="sub" type="button" class="btn2" value="确&nbsp;&nbsp;&nbsp;&nbsp;定"/>&nbsp;
                </td>
                <td class="spe_line">
                    <input id="cancel" type="button" class="btn2" value="取&nbsp;&nbsp;&nbsp;&nbsp;消"/>
                </td>
            </tr>
        </table>
            <input type="hidden" value="{ReturnUrl}" name="return_url"/>
        </form>
    </div>
</body>
</html>