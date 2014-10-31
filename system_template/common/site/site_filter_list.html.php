<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}

    <script type="text/javascript" src="/system_js/manage/site/site_filter.js"></script>
    <script type="text/javascript">

        $().ready(function(){
            var siteId = Request["site_id"];
            var siteName = Request["site_name"];
            siteName=decodeURI(siteName);
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
                parent.G_TabUrl = '/default.php?secu=manage&mod=site_filter&m=list' + '&site_id=' + siteId + '&search_key=' + searchKey;
                parent.G_TabTitle = siteName + '-过滤字段搜索';
                parent.addTab();
            });


            $("#btn_create").click(function (event) {
                event.preventDefault();
                parent.G_TabUrl = '/default.php?secu=manage&mod=site_filter&m=create' + '&site_id=' + siteId + '&site_name=' + siteName;
                parent.G_TabTitle = siteName + '-新建过滤字段';
                parent.addTab();
            });


            var btnEdit = $(".btn_edit");
            btnEdit.css("cursor", "pointer");
            btnEdit.click(function (event) {
                var siteFilterId = $(this).attr('idvalue');
                event.preventDefault();
                parent.G_TabUrl = '/default.php?secu=manage&mod=site_filter&m=modify&site_filter_id=' + siteFilterId + '&site_id=' + siteId + '&site_name=' + siteName;
                parent.G_TabTitle = siteName + '-编辑过滤字段';
                parent.addTab();
            });

            $(".span_state").each(function () {
                $(this).html(FormatState($(this).attr("title")));
            });

            $(".span_site_name").each(function () {
                $(this).html(FormatSiteName($(this).attr("title"),siteName));
            });

            $(".span_site_filter_type").each(function () {
                $(this).html(FormatSiteFilterType($(this).attr("title")));
            });

            $(".span_site_filter_area").each(function () {
                $(this).html(FormatSiteFilterArea($(this).attr("title")));
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
                <input id="btn_create" class="btn2" value="新建过滤" title="在本站点新建过滤" type="button"/>
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

    <table class="grid" width="100%" align="center" cellpadding="0" cellspacing="0">
        <tr class="grid_title">
            <td style="width:40px;text-align:center;">ID</td>
            <td style="width:40px;text-align:center;">编辑</td>
            <td style="width:40px;text-align:center;">状态</td>
            <td style="width:40px;text-align:center;">启用</td>
            <td style="width:40px;text-align:center;">停用</td>
            <td style="width:130px;text-align:center;">站点</td>
            <td style="width:233px;text-align:left;">过滤字符</td>
            <td style="width:90px;text-align:center;">间隔字符数</td>
            <td style="width:90px;text-align:center;">过滤范围</td>
            <td style="width:90px;text-align:center;">过滤类型</td>
            <td style="text-align:center;"> </td>
        </tr>
        <icms id="site_filter" type="list"><item><![CDATA[
                <tr class="grid_item">
                    <td class="spe_line2" style="text-align:center;">{f_SiteFilterId}</td>
                    <td class="spe_line2" style="text-align:center;"><img style="cursor: pointer"
                                                                          class="btn_edit"
                                                                          src="/system_template/default/images/manage/edit.gif"
                                                                          alt="编辑" title="{f_SiteFilterId}"
                                                                          idvalue="{f_SiteFilterId}"/></td>

                    <td class="spe_line2" style="text-align:center;"><span class="span_state" title="{f_State}" id="state_{f_SiteFilterId}"></span></td>
                    <td class="spe_line2" style="width:40px;text-align:center;"><span title="{f_SiteFilterId}"><img
                                style=" cursor: pointer" alt="点击启用或审核该信息"
                                src="/system_template/default/images/manage/start.jpg"
                                onclick="ModifyState('site_filter', '{f_SiteFilterId}', '0')"/></span></td>
                    <td class="spe_line2" style="width:40px;text-align:center;"><span title="{f_SiteFilterId}"><img
                                style=" cursor: pointer" alt="停用或删除"
                                src="/system_template/default/images/manage/stop.jpg"
                                onclick="ModifyState('site_filter', '{f_SiteFilterId}', '100')"/></span></td>

                    <td style="text-align:center;" class="spe_line2"><span class="span_site_name" title="{f_SiteId}" id="site_name_{f_SiteFilterId}"></span></td>
                    <td class="spe_line2">{f_SiteFilterWord}</td>
                    <td style="text-align:center;" class="spe_line2">{f_IntervalCount}</td>
                    <td style="text-align:center;" class="spe_line2"><span class="span_site_filter_area" title="{f_SiteFilterArea}" id="filter_area_{f_SiteFilterId}"></span></td>
                    <td style="text-align:center;" class="spe_line2"><span class="span_site_filter_type" title="{f_SiteFilterType}" id="filter_type_{f_SiteFilterId}"></span></td>
                    <td style="text-align:center;" class="spe_line2"> </td>
                </tr>
                ]]></item></icms>
    </table>
    {PagerButton}
</div>
</body>
</html>

