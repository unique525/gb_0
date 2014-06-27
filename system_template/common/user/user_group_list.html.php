<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <style type="text/css">

    </style>
    <script type="text/javascript">

    </script>
</head>
<body>
    <div class="div_list">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td  id="td_main_btn" style="width:80px;">
                    <div class="btn2" id="add">新增分组</div>
                </td>
                <td></td>
            </tr>
        </table>
        <table class="grid" width="100%" cellpadding="0" cellspacing="0">
            <tr  class="grid_title2">
                <td style="width:50px;text-align: center">ID</td>
                <td style="width:50px;text-align: center">编辑</td>
                <td style="width:100px;text-align: center">会员组名称</td>
                <td style="width:200px;text-align: center">所属站点</td>
                <td style="width:80px;text-align: center">状态</td>
                <td  style="width:50px;text-align: center">启用</td>
                <td  style="width:50px;text-align: center">停用</td>
            </tr>
        </table>
        <ul id="type_list">
            <icms id="user_group_list" type="list">
                <item>
                    <![CDATA[
                    <li>
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr class="grid_item2">
                                <td class="spe_line2" style="width:50px;text-align: center">{f_UserGroupId}</td>
                                <td class="spe_line2" style="width:50px;text-align: center">
                                    <a href="default.php?secu=manage&mod=user_group&m=modify&user_group_id={f_UserGroupId}">
                                        <img src="/system_template/{template_name}/images/manage/edit.gif" style="cursor:pointer" class="modify" idvalue="{f_UserGroupId}"/>
                                    </a>
                                </td>
                                <td class="spe_line2" style="width:100px;text-align: center">
                                    <div class="normal_operation_{f_UserGroupId}">
                                        {f_UserGroupName}
                                    </div>
                                </td>
                                <td class="spe_line2" style="width:200px;text-align: center">

                                </td>
                                <td class="spe_line2" style="width:80px;text-align: center">

                                </td>
                                <td class="spe_line2" style="width:50px;text-align: center">
                                    <img src="/system_template/{template_name}/images/manage/start.jpg" style="cursor:pointer"/>
                                </td>
                                <td class="spe_line2" style="width:50px;text-align: center">
                                    <img src="/system_template/{template_name}/images/manage/stop.jpg" style="cursor:pointer"/>
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