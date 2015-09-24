<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/forum/forum.js"></script>

    <script type="text/javascript">
        $("document").ready(function () {
            var siteId = Request["site_id"];
            var forumId = Request["forum_id"];

            var btnEdit = $(".btn_edit");
            btnEdit.css("cursor", "pointer");
            btnEdit.click(function (event) {
                var forumTopicId =  $(this).attr('idvalue');
                event.preventDefault();
                window.location.href = '/default.php?secu=manage&mod=forum_topic&m=modify&forum_id='+forumId+'&site_id='+siteId+'&forum_topic_id='+forumTopicId;
                //parent.G_TabTitle = '-编辑帖子';
                //parent.addTab();
            });

        });


    </script>
</head>
<body>
{common_body_deal}

<div class="div_list">
    <table class="grid" width="100%" align="center" cellpadding="0" cellspacing="0">
        <tr class="grid_title">
            <td style="width:60px;text-align:center;">ID</td>
            <td style="width:60px;text-align:center;">编辑</td>
            <td style="width:500px;text-align:center;">标题</td>
            <td style="width:200px;text-align:center;">创建时间</td>
            <td style="width:200px;text-align:center;">最后编辑时间</td>
            <td style="width:200px;text-align:center;">发帖人</td>
            <td style="width:200px;text-align:center;">回复列表</td>
        </tr>
        <icms id="forum_topic_list" type="list"><item><![CDATA[
                <tr class="grid_item">
                    <td class="spe_line2" style="text-align:center;" >{f_ForumTopicId}</td>
                    <td class="spe_line2" style="text-align:center;"><img style="cursor: pointer"
                                                                          class="btn_edit"
                                                                          src="/system_template/default/images/manage/edit.gif"
                                                                          alt="编辑" title="{f_ForumTopicId}"
                                                                          idvalue="{f_ForumTopicId}"/></td>
                    <td class="spe_line2" style="text-align:center;">{f_ForumTopicTitle}</td>
                    <td class="spe_line2" style="text-align:center;">{f_PostTime}</td>
                    <td class="spe_line2" style="text-align:center;">{f_LastPostTime}</td>
                    <td class="spe_line2" style="text-align:center;">{f_UserName}</td>
                    <td class="spe_line2" style="text-align:center;"><span style="cursor: pointer" class="btn_reply_list" idvalue="{f_ForumTopicId}">点击查看</span></td>
                </tr>
                ]]></item></icms>
    </table>
</div>
<div id="pager_btn" style="margin:8px;">
    {PagerButton}
</div>
</body>
</html>
