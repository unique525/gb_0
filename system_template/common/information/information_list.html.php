<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}

    <script type="text/javascript" src="/system_js/manage/information/information.js" ></script>
    <style>
        img {
            vertical-align: middle;
        }
    </style>
</head>
<body>
{common_body_deal}
<script type="text/javascript">
    /**
     * 发布资讯详细页 返回值 资讯id小于0
     */
    window.PUBLISH_INFORMATION_RESULT_DOCUMENT_NEWS_ID_ERROR = -130321;
    /**
     * 发布资讯详细页 返回值 频道id小于0
     */
    window.PUBLISH_INFORMATION_RESULT_CHANNEL_ID_ERROR = -130322;
    /**
     * 发布资讯详细页 返回值 状态不正确，必须为终审或已发状态的文档才能发布
     */
    window.PUBLISH_INFORMATION_RESULT_STATE_ERROR = -130323;
    /**
     * 发布资讯详细页 返回值 操作完成，结果存储于结果数组中
     */
    window.PUBLISH_INFORMATION_RESULT_FINISHED = 130321;

    $("document").ready(function () {

        var channelId = Request["channel_id"];
        var siteId = Request["site_id"];
        var channelName = Request["channel_name"];
        var siteName = Request["site_name"];
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
            parent.G_TabUrl = '/default.php?secu=manage&mod=information&m=list' + '&channel_id=' + channelId + '&search_key=' + searchKey;
            parent.G_TabTitle = channelName + '-分类信息搜索结果';
            parent.addTab();
        });

        $("#btn_create").click(function (event) {
            event.preventDefault();
            parent.G_TabUrl = '/default.php?secu=manage&mod=information&m=create' + '&channel_id=' + channelId;
            parent.G_TabTitle = channelName + '-新建分类信息';
            parent.addTab();
        });

        var btnEdit = $(".btn_edit");
        btnEdit.css("cursor", "pointer");
        btnEdit.click(function (event) {
            var informationId = $(this).attr('idvalue');
            var informationTitle = $(this).attr('title');
            event.preventDefault();
            parent.G_TabUrl = '/default.php?secu=manage&mod=information&m=modify&information_id=' + informationId + '&channel_id=' + channelId;
            parent.G_TabTitle = informationTitle + '-编辑分类信息';
            parent.addTab();
        });

        var btnPublish = $(".btn_publish");
        btnPublish.css("cursor", "pointer");
        btnPublish.click(function(event) {
            var informationId = $(this).attr('idvalue');
            event.preventDefault();

            var dialogBox = $("#dialog_box");
            dialogBox.attr("title","发布文档");
            dialogBox.dialog({
                height: 140,
                modal: true
            });

            var dialogContent = $("#dialog_content");
            dialogContent.html("开始发布");

            $.post("/default.php?secu=manage&mod=information&m=async_publish&activity_id=" + informationId + "", {
                resultbox: $(this).html()
            }, function(result) {
                dialogContent.html('<img src="/system_template/common/images/spinner2.gif" /> 正在发布...');
                if (parseInt(result) == window.PUBLISH_INFORMATION_RESULT_DOCUMENT_NEWS_ID_ERROR) {
                    dialogContent.html('活动id小于0');
                }else if (parseInt(result) == window.PUBLISH_INFORMATION_RESULT_CHANNEL_ID_ERROR) {
                    dialogContent.html('频道id小于0');
                }else if (parseInt(result) == window.PUBLISH_INFORMATION_RESULT_STATE_ERROR) {
                    dialogContent.html('状态不正确，必须为[已审]状态的文档才能发布!');
                }else {
                    dialogContent.html("发布完成<br />"+result);
                    var spanState = $("#span_state_" + informationId);
                    spanState.html("<"+"span style='color:#006600'>已发<"+"/span>");
                }
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
                <input id="btn_create" class="btn2" value="新建分类信息" title="在本频道新建一个分类信息" type="button"/>
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
            <td style="width:40px;text-align:center;">发布</td>
            <td>标题</td>
            <td style="width:160px;text-align:center;" title="停留查看开始与结束时间">开始时间</td>
            <td style="width:100px;text-align:center;">发布人</td>
            <td style="width:30px;text-align:center;">排序</td>
            <td style="width:40px;text-align:center;">删除</td>
        </tr>
        <icms id="information" type="list">
            <item>
                <![CDATA[
                <tr class="grid_item">
                    <td class="spe_line2" style="width:30px;text-align:center;"><input id="doc_input" class="doc_input"
                                                                                       type="checkbox" name="doc_input"
                                                                                       value="{f_InformationId}"/></td>
                    <td class="spe_line2" style="width:40px;text-align:center;"><img style="cursor: pointer"
                                                                                     class="btn_edit"
                                                                                     src="/system_template/default/images/manage/edit.gif"
                                                                                     alt="编辑" title="{f_InformationId}"
                                                                                     idvalue="{f_InformationId}"/>
                    </td>
                    <td class="spe_line2" style="width:40px;text-align:center;" ><span
                            class="span_state" title="{f_State}" id="state_{f_InformationId}"></span></td>
                    <td class="spe_line2" style="width:40px;text-align:center;"><span class="open_Information_id_list"
                                                                                      title="{f_InformationId}"><img
                                style=" cursor: pointer" alt="点击启用或审核该信息"
                                src="/system_template/default/images/manage/start.jpg"
                                onclick="ModifyState('information', '{f_InformationId}', '0')"/></span></td>
                    <td class="spe_line2" style="width:40px;text-align:center;"><span class="stop_Information_id_list"
                                                                                      title="{f_InformationId}"><img
                                style=" cursor: pointer" alt="停用或删除"
                                src="/system_template/default/images/manage/stop.jpg"
                                onclick="ModifyState('information', '{f_InformationId}', '100')"/></span></td>
                    <td class="spe_line2" style="width:40px;text-align:center;"><img class="btn_publish"
                                                                                     style="cursor: pointer"
                                                                                     src="/system_template/default/images/manage/publish.gif"
                                                                                     title="{f_InformationId}" idvalue="{f_ActivityId}" alt="发布"/>
                    </td>
                    <td class="spe_line2"
                        style="text-align:left;overflow: hidden;white-space:nowrap; width:auto; margin-right: 5px;"
                        title="{f_InformationTitle}">{f_InformationTitle}
                    </td>
                    <td class="spe_line2"
                        style="width:160px; overflow: hidden;white-space:nowrap; margin-left: 5px; margin-right: 4px;"
                        title="{f_CreateDate}">{f_CreateDate}
                    </td>
                    <td class="spe_line2"
                        style="width:100px; text-align:center;overflow: hidden;white-space:nowrap; margin-left: 4px;"
                        title="{f_UserId}__{f_UserName}">{f_UserName}
                    </td>
                    <td class="spe_line2" style="width:30px;text-align:center;">{f_sort}</td>

                    <td class="spe_line2" style="width:80px;text-align:center;"><img class="pic_manage"
                                                                                     style="cursor: pointer"
                                                                                     src="/system_template/default/images/manage/delete.jpg"
                                                                                     alt="删除" title="{f_InformationId}"/></td>
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