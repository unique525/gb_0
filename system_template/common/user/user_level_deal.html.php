<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/user/user_level.js"></script>
</head>
<body>
<div class="div_list">
    {common_body_deal}
    <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class="spe_line" height="40" align="right">
                <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/>
                <input class="btn" value="确认并继续" type="button" onclick="submitForm(1)"/>
                <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
            </td>
        </tr>
    </table>
    <form id="mainForm" action="/default.php?secu=manage&mod=user_level&m={method}&user_level_id={UserLevelId}&site_id={SiteId}" method="post" enctype="multipart/form-data">
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="spe_line" style="width:600px"></td>
                <td class="spe_line" height="30" align="right" style="width:100px">名称：</td>
                <td class="spe_line" style="width:80px"><label><input name="f_UserLevelName" id="UserLevelName" value="{UserLevelName}" type="text" class="input_box" style=" width: 100px;" /></label></td>
                <td class="spe_line" align="right" style="width:100px">题图：</td>
                <td class="spe_line" style="width:300px">
                    <input id="TitlePic_upload" name="TitlePic_upload" type="file" class="input_box" style="width:220px; background: #ffffff; margin-top: 3px;" /> <span id="preview_title_pic">[预览]</span>
                </td>
                <td class="spe_line"></td>
            </tr>
            <tr>
                <td class="spe_line" style="width:600px"></td>
                <td class="spe_line" height="30" align="right">论坛发帖数：</td>
                <td class="spe_line" style="width:80px"><label><input name="f_LimitForumPost" id="LimitForumPost" value="{LimitForumPost}" type="text" class="input_number" style=" width: 100px;" /></label></td>
                <td class="spe_line" height="30" align="right">帖子精华数：</td>
                <td class="spe_line" style="width:80px"><label><input name="f_LimitBestForumPost" id="LimitBestForumPost" value="{LimitBestForumPost}" type="text" class="input_number" style=" width: 100px;" /></label></td>
                <td class="spe_line"></td>
            </tr>
            <tr>
                <td class="spe_line" style="width:600px"></td>
                <td class="spe_line" height="30" align="right">积分：</td>
                <td class="spe_line" style="width:80px"><label><input name="f_LimitScore" id="LimitScore" value="{LimitScore}" type="text" class="input_number" style=" width: 100px;"  /></label></td>
                <td class="spe_line" height="30" align="right">金钱：</td>
                <td class="spe_line" style="width:80px"><label><input name="f_LimitMoney" id="LimitMoney" value="{LimitMoney}" type="text" class="input_number" style=" width: 100px;"  /></label></td>
                <td class="spe_line"></td>
            </tr>
            <tr>
                <td class="spe_line" style="width:600px"></td>
                <td class="spe_line" height="30" align="right">魅力：</td>
                <td class="spe_line" style="width:80px"><label><input name="f_LimitCharm" id="LimitCharm" value="{LimitCharm}" type="text" class="input_number" style=" width: 100px;"  /></label></td>
                <td class="spe_line" height="30" align="right">经验：</td>
                <td class="spe_line" style="width:80px"><label><input name="f_LimitExp" id="LimitExp" value="{LimitExp}" type="text" class="input_number" style=" width: 100px;"  /></label></td>
                <td class="spe_line"></td>
            </tr>
            <tr>
                <td class="spe_line" style="width:600px"></td>
                <td class="spe_line" height="30" align="right">点券：</td>
                <td class="spe_line"><label><input name="f_LimitPoint" id="LimitPoint" value="{LimitPoint}" type="text" class="input_number" style=" width: 100px;"  /></label></td>
                <td class="spe_line" height="30" align="right">相册数：</td>
                <td class="spe_line"><label><input name="f_LimitUserAlbum" id="LimitUserAlbum" value="{LimitUserAlbum}" type="text" class="input_number" style=" width: 100px;"  /></label></td>
                <td class="spe_line"></td>
            </tr>
            <tr>
                <td class="spe_line" style="width:600px"></td>
                <td class="spe_line" height="30" align="right">精华相册数：</td>
                <td class="spe_line"><label><input name="f_LimitBestUserAlbum" id="LimitBestUserAlbum" value="{LimitBestUserAlbum}" type="text" class="input_number" style=" width: 100px;"  /></label></td>
                <td class="spe_line" height="30" align="right">相册专家分：</td>
                <td class="spe_line"><label><input name="f_LimitRecUserAlbum" id="LimitRecUserAlbum" value="{LimitRecUserAlbum}" type="text" class="input_number" style=" width: 100px;"  /></label></td>
                <td class="spe_line"></td>
            </tr>
            <tr>
                <td class="spe_line" style="width:600px"></td>
                <td  class="spe_line" height="30" align="right">是否锁定：</td>
                <td  class="spe_line" height="30" align="left">
                    <label>
                    <select id="IsLock" name="f_IsLock" style="">
                        <option value="1">锁定</option>
                        <option value="0">不锁定</option>
                        {s_IsLock}
                    </select>
                    </label>
                </td>
                <td  class="spe_line" height="30" align="right">对应会员组：</td>
                <td  class="spe_line" height="30" align="left" >
                    <label>
                    <select id="f_UpdateToUserGroupId" name="f_UpdateToUserGroupId" style="">
                        <icms id="user_group_list" type="list">
                            <item><![CDATA[
                                <option value="{f_UserGroupId}">{f_UserGroupName}</option>
                                ]]></item>
                        </icms>
                        {s_UpdateToUserGroupId}
                    </select>
                    </label>
                </td>
                <td class="spe_line"></td>
            </tr>
        </table>
        <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
        <input name="PageIndex" type="hidden" value="{PageIndex}"/>
        <input name="PageSize" type="hidden" value="{PageSize}"/>
        <input name="TabIndex" type="hidden" value="{TabIndex}"/>
    </form>
    <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td height="60" align="center">
                <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/>
                <input class="btn" value="确认并继续" type="button" onclick="submitForm(1)"/>
                <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
            </td>
        </tr>
    </table>
</div>
</body>
</html>