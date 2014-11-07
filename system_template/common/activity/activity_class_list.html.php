<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/activity/activity.js" ></script>
    <style>
        img{vertical-align:middle;}
    </style>
</head>
<body>
{common_body_deal}
<script type="text/javascript">
    $("document").ready(function(){
        var channelId = Request["channel_id"];
        var siteId = Request["site_id"];
        var channelName = Request["channel_name"];
        channelName=decodeURI(channelName);
        var siteName = Request["site_name"];
        siteName=decodeURI(siteName);

        $("#btn_select_all").click(function(event) {
            event.preventDefault();
            var inputSelect = $("[name='doc_input']");
            if (inputSelect.prop("checked")) {
                inputSelect.prop("checked", false);//取消全选
            } else {
                inputSelect.prop("checked", true);//全选
            }
        });


        $("#btn_create").click(function(event) {
            event.preventDefault();
            parent.G_TabUrl = '/default.php?secu=manage&mod=activity_class&m=create' + '&site_id='+ siteId +'&site_name=' +siteName+ '&channel_id=' +  channelId;
            parent.G_TabTitle = channelName + '-新建活动类型';
            parent.addTab();
        });

        var btnEdit = $(".btn_edit");
        btnEdit.css("cursor", "pointer");
        btnEdit.click(function(event) {
            var activityClassId = $(this).attr('idvalue');
            event.preventDefault();
            parent.G_TabUrl = '/default.php?secu=manage&mod=activity_class&m=modify&activity_class_id=' + activityClassId + '&channel_id=' + channelId;
            parent.G_TabTitle = channelName + '-编辑活动类型';
            parent.addTab();
        });


        $(".span_state").each(function(){
            $(this).html(FormatState($(this).attr("title")));
        });
    });


</script>
<div class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn" width="83">
                <input id="btn_create" class="btn2" value="新建类型" title="在本频道新建一个活动类型" type="button"/>
            </td>
            <td id="td_main_btn" align="right" style="display: none">
                <div id="search_box">
                    <label for="search_key"></label><input id="search_key" name="search_key" class="input_box" type="text">
                    <input id="btn_search" class="btn2" value="查 询" type="button">
                    <span id="search_type" style="display: none"></span>
                    <input id="btn_view_all" class="btn2" value="查看全部" title="查看全部的文档" type="button" style="display: none">
                </div>
            </td>
        </tr>
    </table>
    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
        <tr class="grid_title">
            <td style="width:30px;text-align:center; cursor: pointer;" id="btn_select_all"><label for="doc_input">全</label></td>
            <td style="width:40px;text-align:center;">编辑</td>
            <td style="width:40px;text-align:center;">状态</td>
            <td style="width:40px;text-align:center;">启用</td>
            <td style="width:40px;text-align:center;">停用</td>
            <td style="width:auto;text-align:center;">类型</td>
        </tr>
        <icms id="activity_class" type="list" >
            <item>
                <![CDATA[
                <tr class="grid_item">
                    <td class="spe_line2" style="width:30px;text-align:center;"><input id="doc_input" class="doc_input" type="checkbox" name="doc_input" value="{f_ActivityClassId}" /></td>
                    <td class="spe_line2" style="width:40px;text-align:center;"><img style="cursor: pointer" class="btn_edit" src="/system_template/default/images/manage/edit.gif" alt="编辑" title="{f_ActivityClassId}" idvalue="{f_ActivityClassId}"/></td>
                    <td class="spe_line2" style="width:40px;text-align:center;" title="状态"><span class="span_state" title="{f_State}" id="state_{f_ActivityClassId}" ></span></td>
                    <td class="spe_line2" style="width:40px;text-align:center;"><span title="{f_ActivityClassId}"><img style=" cursor: pointer" alt="点击启用或审核该信息" src="/system_template/default/images/manage/start.jpg" onclick="ModifyState('activity_class', '{f_ActivityClassId}', '0')"/></span></td>
                    <td class="spe_line2" style="width:40px;text-align:center;"><span title="{f_ActivityClassId}"><img style=" cursor: pointer" alt="停用或删除" src="/system_template/default/images/manage/stop.jpg" onclick="ModifyState('activity_class', '{f_ActivityClassId}', '100')"/></span></td>
                    <td class="spe_line2" style="width:auto;text-align:center;" title="点击按分类进行汇总"><span class="ActivityClassIdList" title="{f_ActivityClassId}">{f_ActivityClassName}</span></td>
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