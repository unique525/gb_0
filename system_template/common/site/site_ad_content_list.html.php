<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/site/site_ad.js"></script>

    <script type="text/javascript">
        $("document").ready(function () {
            var siteAdId = Request["site_ad_id"];
            var siteAdName = Request["site_ad_name"];
            siteAdName=decodeURI(siteAdName);
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
                parent.G_TabUrl = '/default.php?secu=manage&mod=site_ad_content&m=list' + '&site_ad_id=' + siteAdId + '&search_key=' + searchKey;
                parent.G_TabTitle = siteAdName + '-广告搜索';
                parent.addTab();
            });


            $("#btn_create").click(function (event) {
                event.preventDefault();
                parent.G_TabUrl = '/default.php?secu=manage&mod=site_ad_content&m=create' + '&site_ad_id=' + siteAdId;
                parent.G_TabTitle = siteAdName + '-新建广告';
                parent.addTab();
            });


            var btnEdit = $(".btn_edit");
            btnEdit.css("cursor", "pointer");
            btnEdit.click(function (event) {
                var siteAdContentId = $(this).attr('idvalue');
                var siteAdContentTitle = $(this).attr('title');
                event.preventDefault();
                parent.G_TabUrl = '/default.php?secu=manage&mod=site_ad_content&m=modify&site_ad_content_id=' + siteAdContentId + '&site_ad_id=' + siteAdId;
                parent.G_TabTitle = siteAdContentTitle + '-编辑广告';
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
                <input id="btn_create" class="btn2" value="新建广告" title="新建一个广告" type="button"/>
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
            <td style="width:220px;">名称</td>
            <td>时间</td>
            <td style="width:80px;text-align:center;">查看广告</td>
            <td style="width:80px;text-align:center;">指向链接</td>
            <td style="width:80px;text-align:center;">虚拟点击</td>
            <td style="width:80px;text-align:center;">更新JS</td>
        </tr>
        <icms id="site_ad_content" type="list"><item><![CDATA[
                <tr class="grid_item">
                    <td class="spe_line2" style="width:40px;text-align:center;">{f_SiteAdContentId}</td>
                    <td class="spe_line2" style="width:40px;text-align:center;"><img style="cursor: pointer"
                                                                                     class="btn_edit"
                                                                                     src="/system_template/default/images/manage/edit.gif"
                                                                                     alt="编辑" title="{f_SiteAdContentTitle}"
                                                                                     idvalue="{f_SiteAdContentId}"/></td>
                    <td class="spe_line2" style="text-align:center;"><span class="span_state" title="{f_State}" id="state_{f_SiteAdContentId}"></span></td>
                    <td class="spe_line2" style="width:40px;text-align:center;"><span title="{f_SiteAdContentId}"><img
                                style=" cursor: pointer" alt="点击启用或审核该信息"
                                src="/system_template/default/images/manage/start.jpg"
                                onclick="ModifyState('site_ad_content', '{f_SiteAdContentId}', '0')"/></span></td>
                    <td class="spe_line2" style="width:40px;text-align:center;"><span title="{f_SiteAdContentId}"><img
                                style=" cursor: pointer" alt="停用或删除"
                                src="/system_template/default/images/manage/stop.jpg"
                                onclick="ModifyState('site_ad_content', '{f_SiteAdContentId}', '100')"/></span></td>
                    <td class="spe_line2" style="width:220px;text-align:left;" title="{f_SiteAdContentId}"><span style="color:{f_SiteAdContentTitleColor};font-weight:{f_SiteAdContentTitleBold};">{f_SiteAdContentTitle}</span></td>
                    <td class="spe_line2" style="text-align:left;">{f_BeginDate}—><script type="text/javascript">CheckEndDate("{f_EndDate}")</script></td>
                    <td class="spe_line2" style="width:80px;text-align:center;"><a href="{rootpath}/{f_titlepic}" title="点击进行查看广告 {f_titlepic}" target="_blank">查看广告</a></td>
                    <td class="spe_line2" style="width:80px;text-align:center;"><a href="{f_adurl}" title="点击查看广告指向链接 {f_adurl}" target="_blank">指向链接</a></td>
                    <td class="spe_line2" style="width:80px;text-align:center;">{f_AddedVirtualClickCount}</td>
                    <td class="spe_line2" style="width:40px;text-align:center;"><a href="#" title="{siteid} 点击更新广告JS {f_adid}" onclick="window.open('index.php?a=ad&m=adcreatejs&adid={f_adid}&siteid={siteid}','', 'width=500, height=380, top=320, left=180, toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no' );">更新JS</a></td>
                </tr>
                ]]></item>
        </icms>
    </table>
</div>
<div id="pager_btn" style="margin:8px;">
    {PagerButton}
</div>
</body>
</html>
