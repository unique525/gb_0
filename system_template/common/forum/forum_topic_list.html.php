<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/forum/forum.js"></script>

    <script type="text/javascript">
        $("document").ready(function () {
            var siteId = Request["site_id"];

            var btnEdit = $(".btn_edit");
            btnEdit.css("cursor", "pointer");
            btnEdit.click(function (event) {
                var siteAdId = $(this).attr('idvalue');
                event.preventDefault();
                parent.G_TabUrl = '/default.php?secu=manage&mod=site_ad&m=modify&site_ad_id=' + siteAdId + '&site_id=' + siteId + '&site_name=' + siteName;
                parent.G_TabTitle = siteName + '-编辑帖子';
                parent.addTab();
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
            <td style="width:40px;text-align:center;">编辑</td>
            <td>标题</td>
            <td style="width:80px;text-align:center;">创建时间</td>
            <td style="width:120px;text-align:center;">最后编辑时间</td>
            <td style="width:120px;text-align:center;">发帖人</td>
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
                </tr>
                ]]></item></icms>
    </table>
</div>
<div id="pager_btn" style="margin:8px;">
    {PagerButton}
</div>
</body>
</html>
