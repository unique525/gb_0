<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/site/site.js"></script>

    <script type="text/javascript">
        $("document").ready(function () {
            var siteId = Request["site_id"];
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
                parent.G_TabUrl = '/default.php?secu=manage&mod=site_ad&m=list' + '&site_id=' + siteId + '&search_key=' + searchKey;
                parent.G_TabTitle = siteName + '-广告搜索';
                parent.addTab();
            });

            $(".span_content").click(function (event) {
                event.preventDefault();
                var adId=this.attr("idvalue");
                var adName=this.attr("title");
                parent.G_TabUrl = '/default.php?secu=manage&mod=site_ad_content&m=list' + '&site_id=' + siteId + '&site_ad_id=' + adId;
                parent.G_TabTitle = adName + '-广告内容';
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
</head>
<body>
{common_body_deal}

<div class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn" width="83">
                <input id="btn_create" class="btn2" value="新建广告" title="在本站点新建一个广告" type="button"/>
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
    <table class="doc_grid" width="99%" align="center" cellpadding="0" cellspacing="0">
        <tr class="grid_title">
            <td style="width:60px;text-align:center;">ID</td>
            <td style="width:40px;text-align:center;">编辑</td>
            <td style="width:40px;text-align:center;">停用</td>
            <td style="width:40px;text-align:center;">启用</td>
            <td>站点名称</td>
            <td>广告位</td>
            <td style="width:40px;text-align:center;">状态</td>
            <td style="width:120px;text-align:center;">规格</td>
            <td style="width:80px;text-align:center;">查看广告</td>
            <td style="width:80px;text-align:center;">更新JS</td>
            <td style="width:40px;text-align:center;">预览</td>
        </tr>
        <icms id="site_ad" type="list"><item><![CDATA[
                <tr class="grid_item">
                    <td class="spe_line2" style="text-align:center;" >{f_SiteAdId}</td>
                    <td class="spe_line2" style="text-align:center;"><img style="cursor: pointer"
                                                                          class="btn_edit"
                                                                          src="/system_template/default/images/manage/edit.gif"
                                                                          alt="编辑" title="{f_SiteAdId}"
                                                                          idvalue="{f_SiteAdId}"/></td>
                    <td class="spe_line2" style="text-align:center;"><span class="span_state" title="{f_State}" id="state_{f_SiteAdId}"></span></td>
                    <td class="spe_line2" style="width:40px;text-align:center;"><span title="{f_SiteAdId}"><img
                                style=" cursor: pointer" alt="点击启用或审核该信息"
                                src="/system_template/default/images/manage/start.jpg"
                                onclick="ModifyState('site_ad', '{f_SiteAdId}', '0')"/></span></td>
                    <td class="spe_line2" style="width:40px;text-align:center;"><span title="{f_SiteAdId}"><img
                                style=" cursor: pointer" alt="停用或删除"
                                src="/system_template/default/images/manage/stop.jpg"
                                onclick="ModifyState('site_ad', '{f_SiteAdId}', '100')"/></span></td>
                    <td class="spe_line2" >{f_SiteName}</td>
                    <td class="spe_line2" title="{f_SiteAdId}">{f_SiteAdName}</td>
                    <td class="spe_line2" style="text-align:center;">{f_AdWidth}×{f_AdHeight}</td>
                    <td class="spe_line2" style="text-align:center;"><span class="span_content"  idvalue="{f_SiteAdId}" title="{f_SiteAdName}">查看广告</span></td>
                    <td class="spe_line2" style="text-align:center;"><a href="#" title="{f_siteid} 点击更新广告JS {f_adid}" onclick="window.open('index.php?a=ad&m=adcreatejs&adid={f_adid}&siteid={f_siteid}','', 'width=500, height=380, top=320, left=180, toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no' );">更新JS</a></td>
                    <td class="spe_line2" style="text-align:center;"><a href="{rootpath}/document/index.php?a=ad&m=list&adid={f_adid}&height=&width=&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=true" title="点击进行预览 {f_adid}">预览</a></td>
                </tr>
                ]]></item></icms>
    </table>
</div>
<div id="pager_btn" style="margin:8px;">
    {PagerButton}
</div>
</body>
</html>
