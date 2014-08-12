<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/user/user_level.js"></script>
</head>
<body>
<div class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td  id="td_main_btn" style="width:80px;">
                <input id="btn_create" class="btn2" value="新增会员等级" title="在本站点新建会员组" type="button"/>
            </td>
            <td></td>
        </tr>
    </table>
    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
        <tr  class="grid_title2">
            <td style="width:40px;text-align: center">ID</td>
            <td style="width:50px;text-align: center">编辑</td>
            <td style="text-align: center;width:120px">等级名称</td>
            <td style="text-align: center;width:120px">等级图片</td>
            <td style="text-align: center;width:80px">论坛发帖数</td>
            <td style="text-align: center;width:80px">精华帖数</td>
            <td style="text-align: center;width:80px">积分</td>
            <td style="text-align: center;width:80px">金钱</td>
            <td style="text-align: center;width:80px">魅力</td>
            <td style="text-align: center;width:80px">经验</td>
            <td style="text-align: center;width:80px">点券</td>
            <td style="text-align: center;width:80px">相册数</td>
            <td style="text-align: center;width:80px">精华相册数</td>
            <td style="text-align: center;width:80px">相册专家分</td>
            <td style="text-align: center;width:80px">相册支持票</td>
            <td style="text-align: center;width:80px">对应会员组</td>
            <td style="width:60px;text-align: center">状态</td>
            <td  style="width:80px;text-align: center">启用  停用</td>
        </tr>
    </table>
    <ul id="type_list">
        <icms id="user_level_list" type="list">
            <item>
                <![CDATA[
                <li>
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item2">
                            <td class="spe_line2" style="width:40px;text-align: center">{f_UserLevelId}</td>
                            <td class="spe_line2" style="width:50px;text-align: center">
                                <img src="/system_template/{template_name}/images/manage/edit.gif" style="cursor:pointer" class="edit" idvalue="{f_UserLevelId}"/>
                            </td>
                            <td class="spe_line2" style="text-align: center;width:120px">
                                    {f_UserLevelName}
                            </td>
                            <td class="spe_line2" style="text-align: center;width:120px">
                                <img src="" style="width:120px;height:25px"/>
                            </td>
                            <td class="spe_line2" style="text-align: center;width:80px">{f_LimitForumPost}</td>
                            <td class="spe_line2" style="text-align: center;width:80px">{f_LimitBestForumPost}</td>
                            <td class="spe_line2" style="text-align: center;width:80px">{f_LimitScore}</td>
                            <td class="spe_line2" style="text-align: center;width:80px">{f_LimitMoney}</td>
                            <td class="spe_line2" style="text-align: center;width:80px">{f_LimitCharm}</td>
                            <td class="spe_line2" style="text-align: center;width:80px">{f_LimitExp}</td>
                            <td class="spe_line2" style="text-align: center;width:80px">{f_LimitPoint}</td>
                            <td class="spe_line2" style="text-align: center;width:80px">{f_LimitUserAlbum}</td>
                            <td class="spe_line2" style="text-align: center;width:80px">{f_LimitBestUserAlbum}</td>
                            <td class="spe_line2" style="text-align: center;width:80px">{f_LimitRecUserAlbum}</td>
                            <td class="spe_line2" style="text-align: center;width:80px">{f_LimitUserAlbumCommentCount}</td>
                            <td class="spe_line2" style="text-align: center;width:80px">{f_UserGroupName}</td>
                            <td class="spe_line2" style="width:60px;text-align: center" id="State_{f_UserLevelId}"><span class="span_state" idvalue="{f_UserLevelId}">{f_State}</span></td>
                            <td class="spe_line2" style="width:80px;text-align: center">
                                <img class="div_start" idvalue="{f_UserLevelId}" src="/system_template/{template_name}/images/manage/start.jpg" style="cursor:pointer"/>
                                &nbsp;&nbsp;
                                <img class="div_stop" idvalue="{f_UserLevelId}" src="/system_template/{template_name}/images/manage/stop.jpg" style="cursor:pointer"/>
                            </td>
                        </tr>
                    </table>
                </li>
                ]]>
            </item>
        </icms>
        </ul>
        {pagerButton}
</div>
</body>
</html>