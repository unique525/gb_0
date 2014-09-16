<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/activity/activity.js"></script>
    <style>
        img {
            vertical-align: middle;
        }
    </style>
</head>
<body>
{common_body_deal}
<script type="text/javascript">
    $("document").ready(function () {

        var channelId = Request["channel_id"];
        var siteId = Request["site_id"];
        var channelName = Request["site_name"];
        var siteName = parent.G_NowSiteName;
        $("#btn_select_all").click(function (event) {
            event.preventDefault();
            var inputSelect = $("[name='doc_input']");
            if (inputSelect.prop("checked")) {
                inputSelect.prop("checked", false);//取消全选
            } else {
                inputSelect.prop("checked", true);//全选
            }
        });


        $("#btn_search").click(function (event) {
            event.preventDefault();
            var searchKey = $("#search_key").val();
            parent.G_TabUrl = '/default.php?secu=manage&mod=activity&m=list' + '&channel_id=' + channelId + '&search_key=' + searchKey;
            parent.G_TabTitle = channelName + '-活动搜索';
            parent.addTab();
        });

        $("#btn_class_list").click(function (event) {
            event.preventDefault();
            parent.G_TabUrl = '/default.php?secu=manage&mod=activity_class&m=list' + '&site_id=' + siteId + '&channel_name=' + channelName + '&site_name=' + siteName + '&channel_id=' + channelId;
            parent.G_TabTitle = channelName + '-活动类型管理';
            parent.addTab();
        });

        $("#btn_create").click(function (event) {
            event.preventDefault();
            parent.G_TabUrl = '/default.php?secu=manage&mod=activity&m=create' + '&channel_id=' + channelId;
            parent.G_TabTitle = channelName + '-新建活动';
            parent.addTab();
        });

        var btnEdit = $(".btn_edit");
        btnEdit.css("cursor", "pointer");
        btnEdit.click(function (event) {
            var activityId = $(this).attr('idvalue');
            var activityType = $(this).attr('type_value');
            event.preventDefault();
            parent.G_TabUrl = '/default.php?secu=manage&mod=activity&m=modify&activity_id=' + activityId + '&activity_type=' + activityType + '&channel_id=' + channelId;
            parent.G_TabTitle = siteName + '-编辑活动';
            parent.addTab();
        });


        $(".span_state").each(function () {
            $(this).html(FormatState($(this).attr("title")));
        });
    });


</script>
<div class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn" width="83">
                <input id="btn_create" class="btn2" value="新建活动" title="在本频道新建一个活动" type="button"/>
            </td>
            <td id="td_main_btn" width="180">
                <input id="btn_class_list" class="btn2" value="活动类型管理" title="管理本频道活动类型" type="button"/>
            </td>
            <td id="td_main_btn" align="right">
                <div id="search_box">
                    <label for="search_key"></label><input id="search_key" name="search_key" class="input_box"
                                                           type="text">
                    <input id="btn_search" class="btn2" value="查 询" type="button">
                    <span id="search_type" style="display: none"></span>
                    <input id="btn_view_all" class="btn2" value="查看全部" title="查看全部的文档" type="button"
                           style="display: none">
                </div>
            </td>
        </tr>
    </table>
    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
        <tr class="grid_title">
            <td style="width:30px;text-align:center; cursor: pointer;" id="btn_select_all"><label
                    for="doc_input">全</label></td>
            <td style="width:40px;text-align:center;">编辑</td>
            <td style="width:40px;text-align:center;">状态</td>
            <td style="width:40px;text-align:center;">启用</td>
            <td style="width:40px;text-align:center;">停用</td>
            <td>标题</td>
            <td style="width:160px;text-align:center;" title="停留查看开始与结束时间">开始时间</td>
            <td style="width:100px;text-align:center;">发布人</td>
            <td style="width:80px;text-align:center;">类型</td>
            <td style="width:60px;text-align:center;">申请人</td>
            <td style="width:60px;text-align:center;">参加人</td>
            <td style="width:30px;text-align:center;">排序</td>
            <td style="width:40px;text-align:center;">审核</td>
            <td style="width:80px;text-align:center;"></td>
        </tr>
        <icms id="activity" type="list" subjectlen="60">
            <item>
                <![CDATA[
                <tr class="grid_item">
                    <td class="spe_line2" style="width:30px;text-align:center;"><input id="doc_input" class="doc_input"
                                                                                       type="checkbox" name="doc_input"
                                                                                       value="{f_ActivityId}"/></td>
                    <td class="spe_line2" style="width:40px;text-align:center;"><img style="cursor: pointer"
                                                                                     class="btn_edit"
                                                                                     src="/system_template/default/images/manage/edit.gif"
                                                                                     alt="编辑" title="{f_ActivityId}"
                                                                                     idvalue="{f_ActivityId}"
                                                                                     type_value="{f_ActivityType}"/>
                    </td>
                    <td class="spe_line2" style="width:40px;text-align:center;" title="点击按状态进行汇总"><span
                            class="span_state" title="{f_State}" id="state_{f_ActivityId}"></span></td>
                    <td class="spe_line2" style="width:40px;text-align:center;"><span class="open_activity_id_list"
                                                                                      title="{f_ActivityId}"><img
                                style=" cursor: pointer" alt="点击启用或审核该信息"
                                src="/system_template/default/images/manage/start.jpg"
                                onclick="ModifyState('activity', '{f_ActivityId}', '0')"/></span></td>
                    <td class="spe_line2" style="width:40px;text-align:center;"><span class="stop_activity_id_list"
                                                                                      title="{f_ActivityId}"><img
                                style=" cursor: pointer" alt="停用或删除"
                                src="/system_template/default/images/manage/stop.jpg"
                                onclick="ModifyState('activity', '{f_ActivityId}', '100')"/></span></td>
                    <td class="spe_line2"
                        style="text-align:left;overflow: hidden;white-space:nowrap; width:auto; margin-right: 5px;"
                        title="{f_ActivityTitle}">{f_ActivityTitle}
                    </td>
                    <td class="spe_line2"
                        style="width:160px; overflow: hidden;white-space:nowrap; margin-left: 5px; margin-right: 4px;"
                        title="{f_BeginDate} -- {f_EndDate}">{f_BeginDate}
                    </td>
                    <td class="spe_line2"
                        style="width:100px; text-align:center;overflow: hidden;white-space:nowrap; margin-left: 4px;"
                        title="{f_UserId}__{f_UserName}">{f_UserName}
                    </td>
                    <td class="spe_line2" style="width:80px;text-align:center;" title="点击按分类进行汇总"><span
                            class="ActivityClassIdList" title="{f_ActivityClassId}">{f_ActivityClassName}</span></td>
                    <td class="spe_line2" style="width:60px;text-align:center;"><span class="new_activity_user"
                                                                                      style=" cursor: pointer"
                                                                                      title="{f_ActivityId}">{f_ApplyUserCount}</span>
                    </td>
                    <td class="spe_line2" style="width:60px;text-align:center;"><span class="activity_user_list"
                                                                                      style=" cursor: pointer"
                                                                                      title="{f_ActivityId}"
                                                                                      id="{f_ChannelId}">{f_JoinUserCount}</span>
                    </td>
                    <td class="spe_line2" style="width:30px;text-align:center;">{f_sort}</td>
                    <td class="spe_line2" style="width:40px;text-align:center;"><img class="img_publish"
                                                                                     style="cursor: pointer"
                                                                                     src="/system_template/default/images/manage/publish.gif"
                                                                                     title="{f_ActivityId}" alt="发布"/>
                    </td>
                    <td class="spe_line2" style="width:80px;text-align:center;"><img class="pic_manage"
                                                                                     style="cursor: pointer"
                                                                                     src="/system_template/default/images/manage/pic.gif"
                                                                                     alt="图片管理" title="图片管理"/> <img
                            class="comment_manage" style="cursor: pointer"
                            src="/system_template/default/images/manage/comment.gif" alt="评论管理" title="评论管理"/></td>
                </tr>
                ]]>
            </item>
        </icms>
    </table>
</div>
<div id="PagerBtn">
    {PagerButton}
</div>

</body>
</html>