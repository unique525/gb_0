<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/user/user_group.js"></script>
</head>
<body>
    <div class="div_list">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td  id="td_main_btn" style="width:80px;">
                    <input id="btn_create" class="btn2" value="新建会员组" title="在本站点新建新建会员组" type="button"/>
                </td>
                <td></td>
            </tr>
        </table>
        <table class="grid" width="100%" cellpadding="0" cellspacing="0">
            <tr  class="grid_title2">
                <td style="width:40px;text-align: center">ID</td>
                <td style="width:50px;text-align: center">编辑</td>
                <td style="text-align: left">会员组名称</td>
                <td style="width:60px;text-align: center">状态</td>
                <td  style="width:80px;text-align: center">启用  停用</td>
            </tr>
        </table>
        <ul id="type_list">
            <icms id="user_group_list" type="list">
                <item>
                    <![CDATA[
                    <li>
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr class="grid_item2">
                                <td class="spe_line2" style="width:40px;text-align: center">{f_UserGroupId}</td>
                                <td class="spe_line2" style="width:50px;text-align: center">
                                        <img src="/system_template/{template_name}/images/manage/edit.gif" style="cursor:pointer" class="edit" idvalue="{f_UserGroupId}"/>
                                </td>
                                <td class="spe_line2" style="text-align: left">
                                    <div class="normal_operation_{f_UserGroupId}">
                                        {f_UserGroupName}
                                    </div>
                                </td>
                                <td class="spe_line2" style="width:60px;text-align: center"><span class="span_state">{f_State}</span></td>
                                <td class="spe_line2" style="width:80px;text-align: center">
                                    <img class="div_start" idvalue="{f_UserGroupId}" src="/system_template/{template_name}/images/manage/start.jpg" style="cursor:pointer"/>
                                    &nbsp;&nbsp;
                                    <img class="div_stop" idvalue="{f_UserGroupId}" src="/system_template/{template_name}/images/manage/stop.jpg" style="cursor:pointer"/>
                                </td>
                            </tr>
                        </table>
                    </li>
                    ]]>
                </item>
            </icms>
            {pagerButton}
    </div>
</body>
</html>