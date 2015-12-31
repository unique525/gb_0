<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript">
        $(function(){
            $('#tabs').tabs();
        });
        function submitForm(closeTab)
        {
            //把所有未选中的checkbox设置为选中以post提交有数据，同时赋值为0传递给后台表示未选中
            var t1 = document.getElementsByTagName("input");
            for(var i=0;i<t1.length;i++)
            {
                if(t1[i].type == "checkbox"){
                    if(!(t1[i].checked)){
                        t1[i].checked = true;
                        t1[i].value = "0";
                    }
                }
            }
            $('#main_form').submit();
        }

    </script>
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
<form id="main_form" action="/default.php?secu=manage&mod=user_popedom&m=modify&site_id={SiteId}&user_group_id={UserGroupId}&user_id={UserId}" method="post">
<div id="tabs">
<ul>
    <li><a href="#tabs-1">会员面板</a></li>
    <li><a href="#tabs-2">论坛相关</a></li>
</ul>
<div id="tabs-1">
    <div>
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_UserSignMaxContentCount">会员签名的最大字符数：</label></td>
                <td class="spe_line"><input name="u_UserSignMaxContentCount" id="u_UserSignMaxContentCount" value="{UserSignMaxContentCount}" maxlength="6" type="text" class="input_number" style=" width: 100px;" /> （0表示不限制）</td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_UserAlbumMaxUploadPerOnce">会员相册单次最大上传文件数：</label></td>
                <td class="spe_line"><input name="u_UserAlbumMaxUploadPerOnce" id="u_UserAlbumMaxUploadPerOnce" value="{UserAlbumMaxUploadPerOnce}" maxlength="2" type="text" class="input_number" style=" width: 100px;" /> （0表示不限制）</td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_UserAllowHidden">允许隐身登录：</label></td>
                <td class="spe_line"><input name="u_UserAllowHidden" id="u_UserAllowHidden" {c_UserAllowHidden} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_UserCanPostActivity">允许发布活动：</label></td>
                <td class="spe_line"><input name="u_UserCanPostActivity" id="u_UserCanPostActivity" {c_UserCanPostActivity} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_UserSetRecCountLimit">给会员相册打推荐分的最大值：</label></td>
                <td class="spe_line"><input name="u_UserSetRecCountLimit" id="u_UserSetRecCountLimit" value="{UserSetRecCountLimit}" maxlength="2" type="text" class="input_number" style=" width: 100px;" /> （0表示不能打分）</td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_UserSetRecCountDayMax">给会员相册打推荐分的每日上限值：</label></td>
                <td class="spe_line"><input name="u_UserSetRecCountDayMax" id="u_UserSetRecCountDayMax" value="{UserSetRecCountDayMax}" maxlength="2" type="text" class="input_number" style=" width: 100px;" /> （0表示不限制）</td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_UserCanExploreMustPayNewspaper">可以浏览付费报纸：</label></td>
                <td class="spe_line"><input name="u_UserCanExploreMustPayNewspaper" id="u_UserCanExploreMustPayNewspaper" {c_UserCanExploreMustPayNewspaper} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_UserCanScoreArticle">可以进行月度评稿打分：</label></td>
                <td class="spe_line"><input name="u_UserCanScoreArticle" id="u_UserCanScoreArticle" {c_UserCanScoreArticle} type="checkbox" /></td>
            </tr>
        </table>
    </div>
</div>
<div id="tabs-2">
    <div>
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">

            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumAllowPostTopic">允许发表主题：</label></td>
                <td class="spe_line"><input name="u_ForumAllowPostTopic" id="u_ForumAllowPostTopic" {c_ForumAllowPostTopic} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumAllowPostReply">允许发表回复：</label></td>
                <td class="spe_line"><input name="u_ForumAllowPostReply" id="u_ForumAllowPostReply" {c_ForumAllowPostReply} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumPostTopicMaxContentCount">发表主题允许的最大字符数：</label></td>
                <td class="spe_line">
                    <input name="u_ForumPostTopicMaxContentCount" id="u_ForumPostTopicMaxContentCount" value="{ForumPostTopicMaxContentCount}" maxlength="10" type="text" class="input_number" style=" width: 100px;" /> （0表示不限制）
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumPostReplyMaxContentCount">发表回复允许的最大字符数：</label></td>
                <td class="spe_line">
                    <input name="u_ForumPostReplyMaxContentCount" id="u_ForumPostReplyMaxContentCount" value="{ForumPostReplyMaxContentCount}" maxlength="10" type="text" class="input_number" style=" width: 100px;" /> （0表示不限制）
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumPostTopicMinContentCount">发表主题允许的最少字符数：</label></td>
                <td class="spe_line">
                    <input name="u_ForumPostTopicMinContentCount" id="u_ForumPostTopicMinContentCount" value="{ForumPostTopicMinContentCount}" maxlength="10" type="text" class="input_number" style=" width: 100px;" /> （0表示不限制）
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumPostReplyMinContentCount">发表回复允许的最少字符数：</label></td>
                <td class="spe_line">
                    <input name="u_ForumPostReplyMinContentCount" id="u_ForumPostReplyMinContentCount" value="{ForumPostReplyMinContentCount}" maxlength="10" type="text" class="input_number" style=" width: 100px;" /> （0表示不限制）
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumAllowPostAdvancedTopicAccess">允许发布高级主题(如回复可见,积分可见等形式的主题)：</label></td>
                <td class="spe_line"><input name="u_ForumAllowPostAdvancedTopicAccess" id="u_ForumAllowPostAdvancedTopicAccess" {c_ForumAllowPostAdvancedTopicAccess} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumAllowPostAdvancedTopicTitle">允许发布高级主题标题(如多种颜色,加粗的主题)：</label></td>
                <td class="spe_line"><input name="u_ForumAllowPostAdvancedTopicTitle" id="u_ForumAllowPostAdvancedTopicTitle" {c_ForumAllowPostAdvancedTopicTitle} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumAllowPostMediaTopic">允许发布多媒体<strong>主题</strong>(如视频、Flash、框架页面等)：</label></td>
                <td class="spe_line"><input name="u_ForumAllowPostMediaTopic" id="u_ForumAllowPostMediaTopic" {c_ForumAllowPostMediaTopic} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumAllowPostMediaReply">允许发布多媒体<strong>回复</strong>(如视频、Flash、框架页面等)：</label></td>
                <td class="spe_line"><input name="u_ForumAllowPostMediaReply" id="u_ForumAllowPostMediaReply" {c_ForumAllowPostMediaReply} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumEditSelfPost">编辑自己的帖子：</label></td>
                <td class="spe_line"><input name="u_ForumEditSelfPost" id="u_ForumEditSelfPost" {c_ForumEditSelfPost} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumEditOtherPost">编辑其他人的帖子：</label></td>
                <td class="spe_line"><input name="u_ForumEditOtherPost" id="u_ForumEditOtherPost" {c_ForumEditOtherPost} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumDeleteSelfPost">删除自己的帖子：</label></td>
                <td class="spe_line"><input name="u_ForumDeleteSelfPost" id="u_ForumDeleteSelfPost" {c_ForumDeleteSelfPost} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumDeleteOtherPost">删除其他人的帖子：</label></td>
                <td class="spe_line"><input name="u_ForumDeleteOtherPost" id="u_ForumDeleteOtherPost" {c_ForumDeleteOtherPost} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumForbidOtherEditMyTopic">禁止其他人编辑自己的主题(一般只有管理员有此权限)：</label></td>
                <td class="spe_line"><input name="u_ForumForbidOtherEditMyTopic" id="u_ForumForbidOtherEditMyTopic" {c_ForumForbidOtherEditMyTopic} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumForbidOtherEditMyReply">禁止其他人编辑自己的回复(一般只有管理员有此权限)：</label></td>
                <td class="spe_line"><input name="u_ForumForbidOtherEditMyReply" id="u_ForumForbidOtherEditMyReply" {c_ForumForbidOtherEditMyReply} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumForbidOtherDeleteMyTopic">禁止其他人删除自己的主题(一般只有管理员有此权限)：</label></td>
                <td class="spe_line"><input name="u_ForumForbidOtherDeleteMyTopic" id="u_ForumForbidOtherDeleteMyTopic" {c_ForumForbidOtherDeleteMyTopic} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumForbidOtherDeleteMyReply">禁止其他人删除自己的回复(一般只有管理员有此权限)：</label></td>
                <td class="spe_line"><input name="u_ForumForbidOtherDeleteMyReply" id="u_ForumForbidOtherDeleteMyReply" {c_ForumForbidOtherDeleteMyReply} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumSetSelfTopicLock">将自己的主题设为锁定状态：</label></td>
                <td class="spe_line"><input name="u_ForumSetSelfTopicLock" id="u_ForumSetSelfTopicLock" {c_ForumSetSelfTopicLock} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumSetSelfTopicBanReply">将自己的主题设为禁止回复状态：</label></td>
                <td class="spe_line"><input name="u_ForumSetSelfTopicBanReply" id="u_ForumSetSelfTopicBanReply" {c_ForumSetSelfTopicBanReply} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumSetOtherTopicLock">将其他人的主题设为锁定状态：</label></td>
                <td class="spe_line"><input name="u_ForumSetOtherTopicLock" id="u_ForumSetOtherTopicLock" {c_ForumSetOtherTopicLock} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumSetOtherTopicBanReply">将其他人的主题设为禁止回复状态：</label></td>
                <td class="spe_line"><input name="u_ForumSetOtherTopicBanReply" id="u_ForumSetOtherTopicBanReply" {c_ForumSetOtherTopicBanReply} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumPostAddMoney">在帖子中给帖主增加{ForumMoneyName}：</label></td>
                <td class="spe_line"><input name="u_ForumPostAddMoney" id="u_ForumPostAddMoney" {c_ForumPostAddMoney} type="checkbox" />（是否允许给自己增加<label
                        for="u_ForumPostAddMoneyForSelf"></label><input name="u_ForumPostAddMoneyForSelf" id="u_ForumPostAddMoneyForSelf" {c_ForumPostAddMoneyForSelf} type="checkbox" />，每日限额：<label
                        for="u_ForumPostAddMoneyLimit"></label><input name="u_ForumPostAddMoneyLimit" id="u_ForumPostAddMoneyLimit" value="{ForumPostAddMoneyLimit}" maxlength="10" type="text" class="input_number" style=" width: 100px;" /> （0表示不限制））</td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumPostAddCharm">在帖子中给帖主增加{ForumCharmName}：</label></td>
                <td class="spe_line"><input name="u_ForumPostAddCharm" id="u_ForumPostAddCharm" {c_ForumPostAddCharm} type="checkbox" />（是否允许给自己增加<label
                        for="u_ForumPostAddCharmForSelf"></label><input name="u_ForumPostAddCharmForSelf" id="u_ForumPostAddCharmForSelf" {c_ForumPostAddCharmForSelf} type="checkbox" />，每日限额：<label
                        for="u_ForumPostAddCharmLimit"></label><input name="u_ForumPostAddCharmLimit" id="u_ForumPostAddCharmLimit" value="{ForumPostAddCharmLimit}" maxlength="10" type="text" class="input_number" style=" width: 100px;" /> （0表示不限制））</td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumPostAddScore">在帖子中给帖主增加{ForumScoreName}：</label></td>
                <td class="spe_line"><input name="u_ForumPostAddScore" id="u_ForumPostAddScore" {c_ForumPostAddScore} type="checkbox" />（是否允许给自己增加<label
                        for="u_ForumPostAddScoreForSelf"></label><input name="u_ForumPostAddScoreForSelf" id="u_ForumPostAddScoreForSelf" {c_ForumPostAddScoreForSelf} type="checkbox" />，每日限额：<label
                        for="u_ForumPostAddScoreLimit"></label><input name="u_ForumPostAddScoreLimit" id="u_ForumPostAddScoreLimit" value="{ForumPostAddScoreLimit}" maxlength="10" type="text" class="input_number" style=" width: 100px;" /> （0表示不限制））</td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumPostAddExp">在帖子中给帖主增加{ForumExpName}：</label></td>
                <td class="spe_line"><input name="u_ForumPostAddExp" id="u_ForumPostAddExp" {c_ForumPostAddExp} type="checkbox" />（是否允许给自己增加<label
                        for="u_ForumPostAddExpForSelf"></label><input name="u_ForumPostAddExpForSelf" id="u_ForumPostAddExpForSelf" {c_ForumPostAddExpForSelf} type="checkbox" />，每日限额：<label
                        for="u_ForumPostAddExpLimit"></label><input name="u_ForumPostAddExpLimit" id="u_ForumPostAddExpLimit" value="{ForumPostAddExpLimit}" maxlength="10" type="text" class="input_number" style=" width: 100px;" /> （0表示不限制））</td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumPostSetBoardTop">将主题设为版块置顶：</label></td>
                <td class="spe_line"><input name="u_ForumPostSetBoardTop" id="u_ForumPostSetBoardTop" {c_ForumPostSetBoardTop} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumPostSetRegionTop">将主题设为分区版块置顶：</label></td>
                <td class="spe_line"><input name="u_ForumPostSetRegionTop" id="u_ForumPostSetRegionTop" {c_ForumPostSetRegionTop} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumPostSetAllTop">将主题设为全部版块置顶：</label></td>
                <td class="spe_line"><input name="u_ForumPostSetAllTop" id="u_ForumPostSetAllTop" {c_ForumPostSetAllTop} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumMoveTopic">移动主题(可以移动自己或他人的主题，一般只有版主以上身份有此权限)：</label></td>
                <td class="spe_line"><input name="u_ForumMoveTopic" id="u_ForumMoveTopic" {c_ForumMoveTopic} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumCopyTopic">复制主题(可以复制自己或他人的主题，一般只有版主以上身份有此权限)：</label></td>
                <td class="spe_line"><input name="u_ForumCopyTopic" id="u_ForumCopyTopic" {c_ForumCopyTopic} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumSetBestTopic">将主题设为精华主题：</label></td>
                <td class="spe_line"><input name="u_ForumSetBestTopic" id="u_ForumSetBestTopic" {c_ForumSetBestTopic} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumSetRecTopic">将主题设为推荐主题：</label></td>
                <td class="spe_line"><input name="u_ForumSetRecTopic" id="u_ForumSetRecTopic" {c_ForumSetRecTopic} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumPostAllowUpload">允许在帖子中上传文件：</label></td>
                <td class="spe_line"><input name="u_ForumPostAllowUpload" id="u_ForumPostAllowUpload" {c_ForumPostAllowUpload} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumPostAllowUploadType">帖子中允许上传的文件类型：</label></td>
                <td class="spe_line"><input name="u_ForumPostAllowUploadType" id="u_ForumPostAllowUploadType" value="{ForumPostAllowUploadType}" maxlength="50" type="text" class="inputbox" style=" width: 200px;" />（例如：jpg,gif,bmp,png 用英文逗号分隔(,) * 表示不限制）</td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumPostMaxUploadSize">帖子中允许上传的文件大小（KB）：</label></td>
                <td class="spe_line"><input name="u_ForumPostMaxUploadSize" id="u_ForumPostMaxUploadSize" value="{ForumPostMaxUploadSize}" maxlength="10" type="text" class="input_number" style=" width: 100px;" /> KB （0表示不限制）</td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumPostMaxUploadPerDay">每日最大可上传的文件数：</label></td>
                <td class="spe_line"><input name="u_ForumPostMaxUploadPerDay" id="u_ForumPostMaxUploadPerDay" value="{ForumPostMaxUploadPerDay}" maxlength="10" type="text" class="input_number" style=" width: 100px;" /> （0表示不限制）</td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumPostMaxUploadPerPost">每个帖子最大可上传的文件数：</label></td>
                <td class="spe_line"><input name="u_ForumPostMaxUploadPerPost" id="u_ForumPostMaxUploadPerPost" value="{ForumPostMaxUploadPerPost}" maxlength="10" type="text" class="input_number" style=" width: 100px;" /> （0表示不限制）</td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumAllowViewAttachment">允许查看帖子中上传的文件：</label></td>
                <td class="spe_line"><input name="u_ForumAllowViewAttachment" id="u_ForumAllowViewAttachment" {c_ForumAllowViewAttachment} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumShowEditInfo">编辑帖子时是否显示编辑信息：</label></td>
                <td class="spe_line"><input name="u_ForumShowEditInfo" id="u_ForumShowEditInfo" {c_ForumShowEditInfo} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumAllowSearch">允许使用论坛搜索功能：</label></td>
                <td class="spe_line"><input name="u_ForumAllowSearch" id="u_ForumAllowSearch" {c_ForumAllowSearch} type="checkbox" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="u_ForumIgnoreLimit">忽视帖子中的所有限制设定（一般只有管理员有此权限）：</label></td>
                <td class="spe_line"><input name="u_ForumIgnoreLimit" id="u_ForumIgnoreLimit" {c_ForumIgnoreLimit} type="checkbox" /> （开启此选项后，锁定帖、禁止回复帖、限制条件阅读帖等主题的限制对此会员将无效）</td>
            </tr>
        </table>
    </div>
</div>
</div>
</form>
<table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td height="60" align="center">
            <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/> <input class="btn" value="确认并继续"
                                                                                            type="button"
                                                                                            onclick="submitForm(1)"/>
            <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
        </td>
    </tr>
</table>
</div>
</body>
</html>