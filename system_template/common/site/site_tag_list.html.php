<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}


    <script type="text/javascript">
        $("document").ready(function () {

            $("#btn_create").click(function (event) {
                event.preventDefault();
                //parent.G_TabUrl = '/default.php?secu=manage&mod=site&m=create';
                //parent.G_TabTitle =  '新增站点';
                //parent.addTab();
                window.location.href = '/default.php?secu=manage&mod=site&m=create&tab_index='+ parent.G_TabIndex +'';
            });


            $(".btn_modify").click(function (event) {
                event.preventDefault();
                var siteId=$(this).attr("idvalue");
                var siteName=$(this).attr("title");
                //parent.G_TabUrl = '/default.php?secu=manage&mod=site&m=modify' + '&site_id=' + siteId + '';
                //parent.G_TabTitle = siteName + '-编辑';
                //parent.addTab();
                window.location.href = '/default.php?secu=manage&mod=site&m=modify&tab_index='+ parent.G_TabIndex +'&site_id=' + siteId + '';
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
                    url: "/default.php?secu=manage&mod=site&m=async_modify_state",
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
         * 格式化状态值
         * @return {string}
         */
        function formatSiteState(state){
            state = state.toString();
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
                <input id="btn_create" class="btn2" value="新增关键词" title="新增关键词" type="button"/>
            </td>
            <td style="text-align: right; margin-right: 8px;">
                <div id="search_box">
                    <label for="search_key"></label><input type="text" id="search_key" name="search_key" class="input_box"/>
                    <input id="btn_search" class="btn2" value="查 询" type="button"/>
                </div>
            </td>
        </tr>
    </table>
    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
        <tr class="grid_title">
            <td style="width: 40px; text-align: center;">ID</td>
            <td style="width: 40px; text-align: center;">编辑</td>
            <td style="width: 120px; text-align: center;">关键词</td>
            <td style="width: 180px;text-align:center;">创建时间</td>
            <td style="width: 40px; text-align: center;">状态</td>
            <td style="width: 80px;text-align:center;">启用&nbsp;&nbsp;停用</td>
        </tr>
    </table>
    <ul id="sort_grid">
        <icms id="site_tag_list" type="list">
            <item>
                <![CDATA[
                <li id="sort_{f_SiteId}">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item">
                            <td class="spe_line2" style="width:40px;text-align:center;">{f_SiteTagId}</td>
                            <td class="spe_line2" style="width:40px;text-align:center;">
                                <img class="btn_modify" title="{f_SiteTagId}" style="cursor:pointer;" src="/system_template/{template_name}/images/manage/edit.gif" idvalue="{f_SiteTagId}" alt="编辑"/></td>
                            <td class="spe_line2" style="width:120px;text-align:center;">{f_SiteTagName}</td>
                            <td class="spe_line2" style="width:180px;text-align:center;" title="站点创建时间">{f_CreateDate}</td>
                            <td class="spe_line2" style="width:40px;text-align:center;"><span id="span_state_{f_SiteId}" class="span_state" idvalue="{f_SiteId}">{f_State}</span></td>
                            <td class="spe_line2" style="width:80px;text-align:center;">
                                <img class="img_open_site" idvalue="{f_SiteTagId}" src="/system_template/{template_name}/images/manage/start.jpg" style="cursor:pointer"/>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <img class="img_close_site" idvalue="{f_SiteTagId}" src="/system_template/{template_name}/images/manage/stop.jpg" style="cursor:pointer"/>
                            </td>

                        </tr>
                    </table>
                </li>
                ]]>
            </item>
        </icms>
    </ul>
    <div>{PagerButton}</div>
</div>
</body>
</html>
