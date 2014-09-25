<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}


    <script type="text/javascript">
        $("document").ready(function () {
            $(".span_filter").click(function (event) {
                event.preventDefault();
                var siteId=$(this).attr("idvalue");
                var siteName=$(this).attr("title");
                parent.G_TabUrl = '/default.php?secu=manage&mod=site_filter&m=list' + '&site_id=' + siteId + '&site_name=' + siteName;
                parent.G_TabTitle = siteName + '-过滤管理';
                parent.addTab();
            });


            $(".span_ad").click(function (event) {
                event.preventDefault();
                var siteId=$(this).attr("idvalue");
                var siteName=$(this).attr("title");
                parent.G_TabUrl = '/default.php?secu=manage&mod=site_ad&m=list' + '&site_id=' + siteId + '&site_name=' + siteName;
                parent.G_TabTitle = siteName + '-广告管理';
                parent.addTab();
            });



            //格式化站点状态
            $(".span_state").each(function(){
                $(this).html(formatSiteState($(this).text()));
            });

            //开启站点
            $(".img_open_site").click(function(){
                var siteId = parseInt($(this).attr("idvalue"));
                var state = 0; //开启状态
                modifySiteState(siteId, state);
            });
            //停用站点
            $(".img_close_site").click(function(){
                var siteId = parseInt($(this).attr("idvalue"));
                var state = 100; //开启状态
                modifySiteState(siteId, state);
            });
        });



        function modifySiteState(siteId,state){
            if(siteId>0){
                $.ajax({
                    type: "get",
                    url: "default.php?secu=manage&mod=site&m=async_modify_state",
                    data: {
                        site_id: siteId,
                        state:state
                    },
                    dataType: "jsonp",
                    jsonp: "jsonpcallback",
                    success: function(data) {
                        $("#span_state_"+siteId).html(formatSiteState(state));
                    }
                });
            }
        }

        /**
         * 格式化站点状态值
         * @return {string}
         */
        function formatSiteState(state){
            switch (state){
                case "0":
                    return "启用";
                    break;
                case "100":
                    return "<"+"span style='color:#990000'>停用<"+"/span>";
                    break;
                default :
                    return "未知";
                    break;
            }
        }
    </script>
</head>
<body>
<div class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn">
                <input id="btn_create" class="btn2" value="新建站点" title="新建站点" type="button"/>
            </td>
            <td style="text-align: right; margin-right: 8px;">
                <div id="search_box">
                    <label for="search_type_box"></label>
                    <select name="search_type_box" id="search_type_box" style="display: none">
                        <option value="default">基本搜索</option>
                    </select>
                    <label for="search_key"></label><input type="text" id="search_key" name="search_key" class="input_box"/>
                    <input id="btn_search" class="btn2" value="查 询" type="button"/>
                    <span id="search_type" style="display: none"></span>
                </div>
            </td>
        </tr>
    </table>
    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
        <tr class="grid_title">
            <td style="width: 30px; text-align: center; cursor: pointer;" id="btn_select_all">全</td>
            <td style="width: 40px; text-align: center;">编辑</td>
            <td>站点名称</td>
            <td style="width: 70px; text-align: center;">排序号</td>
            <td style="width: 180px;text-align:center;">创建时间</td>
            <td style="width: 150px;text-align:center;">创建人</td>
            <td style="width: 40px; text-align: center;">状态</td>
            <td style="width: 80px;text-align:center;">启用&nbsp;&nbsp;停用</td>
            <td style="width: 400px;text-align:center;">相关管理</td>
        </tr>
    </table>
    <ul id="sort_grid">
        <icms id="site_list" type="list">
            <item>
                <![CDATA[
                <li id="sort_{f_SiteId}">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item">
                            <td class="spe_line2" style="width:30px;text-align:center;"><input class="input_select" type="checkbox" name="input_select" value="{f_SiteId}"/></td>
                            <td class="spe_line2" style="width:40px;text-align:center;"><img class="btn_modify" src="/system_template/{template_name}/images/manage/edit.gif" idvalue="{f_SiteId}" alt="编辑"/></td>
                            <td class="spe_line2"><a target="_blank" href="{f_SiteUrl}">{f_SiteName}</a></td>
                            <td class="spe_line2" style="width:70px;text-align:center;" title="文档的排序数字，越大越靠前">{f_Sort}</td>
                            <td class="spe_line2" style="width:180px;text-align:center;" title="站点创建时间">{f_CreateDate}</td>
                            <td class="spe_line2" style="width:150px;text-align:center;" title="创建人：{f_ManageUserName}">{f_ManageUserName}</td>
                            <td class="spe_line2" style="width:40px;text-align:center;"><span id="span_state_{f_SiteId}" class="span_state" idvalue="{f_SiteId}">{f_State}</span></td>
                            <td class="spe_line2" style="width:80px;text-align:center;"><img class="img_open_site" idvalue="{f_SiteId}" src="/system_template/{template_name}/images/manage/start.jpg" style="cursor:pointer"/>&nbsp;&nbsp;&nbsp;&nbsp;<img class="img_close_site" idvalue="{f_SiteId}" src="/system_template/{template_name}/images/manage/stop.jpg" style="cursor:pointer"/></td>

                            <td class="spe_line2" style="width:380px;text-align:left;padding:0 10px 0 10px">
                                <a href="/default.php?secu=manage&mod=product_brand&&m=list_for_manage_tree&site_id={f_SiteId}">产品品牌管理</a>
                                <span class="span_filter" style="width:50px;margin:0 10px 0 10px;cursor:pointer" idvalue="{f_SiteId}" title="{f_SiteName}">过滤</span>
                                <span class="span_ad" style="width:50px;margin:0 10px 0 10px;cursor:pointer" idvalue="{f_SiteId}" title="{f_SiteName}">广告</span>
                            </td>
                        </tr>
                    </table>
                </li>
                ]]>
            </item>
        </icms>
    </ul>
    <div>{pager_button}</div>
</div>
</body>
</html>
