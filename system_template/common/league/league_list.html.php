<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript">

        $(function(){
            //新增
            $("#btn_create").click(function (event) {
                event.preventDefault();
                window.location.href = '/default.php?secu=manage&mod=league&m=create&tab_index='+ parent.G_TabIndex +'&site_id={SiteId}';
            });
            //编辑
            $(".btn_edit").click(function (event) {
                event.preventDefault();
                var leagueId = parseInt($(this).attr("idvalue"));
                window.location.href = '/default.php?secu=manage&mod=league&m=modify&tab_index='+ parent.G_TabIndex +'&league_id='+leagueId;
            });


            //管理赛程
            $(".span_match").click(function (event) {
                event.preventDefault();
                var leagueId = parseInt($(this).attr("idvalue"));
                //window.location.href = '/default.php?secu=manage&mod=lottery_set&m=list&tab_index='+ parent.G_TabIndex +'&lottery_id='+lotteryId;

                parent.G_TabUrl = '/default.php?secu=manage&mod=match&m=list&league_id='+leagueId;
                parent.G_TabTitle = '赛程';
                parent.addTab();
            });


            //管理参赛队伍
            $(".span_team_of_league").click(function (event) {
                event.preventDefault();
                var leagueId = parseInt($(this).attr("idvalue"));
                //window.location.href = '/default.php?secu=manage&mod=lottery_set&m=list&tab_index='+ parent.G_TabIndex +'&lottery_id='+lotteryId;

                parent.G_TabUrl = '/default.php?secu=manage&mod=team&m=list_of_league&league_id='+leagueId;
                parent.G_TabTitle = '参赛队伍';
                parent.addTab();
            });



            //格式化状态
            $(".span_state").each(function(){
                $(this).html(formatState($(this).text()));
            });

        });


        function modifyState(Id,state){
            if(Id>0){
                $.ajax({
                    type: "get",
                    url: "/default.php?secu=manage&mod=lottery&m=async_modify_state",
                    data: {
                        lottery_id: Id,
                        state:state
                    },
                    dataType: "jsonp",
                    jsonp: "jsonpcallback",
                    success: function(data) {
                        $("#span_state_"+Id).html(formatState(state));
                    }
                });
            }
        }

        /**
         * 格式化状态值
         * @return {string}
         */
        function formatState(state){
            state = state.toString();
            switch (state){
                case "0":
                    return "<"+"span style='color:#009900'>启用<"+"/span>";
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
            <td id="td_main_btn" width="83">
                <input id="btn_create" class="btn2" value="新增赛事" title="" type="button"/>
            </td>
            <td id="td_main_btn" align="right">
                <div id="search_box" style="display: none">
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
    <table width="100%" class="grid" cellpadding="0" cellspacing="0" id="left_tree">
        <tr class="grid_title">
            <td class="spe_line" style="width:40px;text-align:center">ID</td>
            <td class="spe_line" style="width:40px;text-align:center;">编辑</td>
            <td class="spe_line" style="width:40px;text-align:center;">状态</td>
            <td class="spe_line" style="width:80px;text-align:center;">启用&nbsp;&nbsp;停用</td>
            <td class="spe_line">名称</td>
            <td class="spe_line" style="width:180px;text-align:center;">操作</td>
        </tr>
        <icms id="league_list" type="list">
            <item><![CDATA[
                <tr class="grid_item" id="{f_LeagueId}">
                    <td class="spe_line" style="text-align:center">{f_LeagueId}</td>
                    <td class="spe_line2" style="text-align:center;"><img style="cursor: pointer"
                                                                          class="btn_edit"
                                                                          src="/system_template/default/images/manage/edit.gif"
                                                                          alt="编辑" title="{f_LeagueId}"
                                                                          idvalue="{f_LeagueId}"
                                                                          onclick="Modify('{f_LeagueId}')"/></td>                    <td class="spe_line2" style="width:40px;text-align:center;"><span id="span_state_{f_LeagueId}" class="span_state" idvalue="{f_LeagueId}">{f_State}</span></td>
                    <td class="spe_line2" style="width:60px;text-align:center;">
                        <img class="img_open" idvalue="{f_LeagueId}" src="/system_template/{template_name}/images/manage/start.jpg" style="cursor:pointer" onclick="modifyState({f_LeagueId},0)"/>
                        &nbsp;
                        <img class="img_close" idvalue="{f_LeagueId}" src="/system_template/{template_name}/images/manage/stop.jpg" style="cursor:pointer" onclick="modifyState({f_LeagueId},100)"/>
                    </td>
                    <td class="spe_line">{f_LeagueName}</td>

                    <td class="spe_line" style="width:180px;text-align:center;cursor: pointer">
                        <!--<span class="span_filter span_match" style="width:50px;margin:0 10px 0 10px;cursor:pointer" idvalue="{f_LeagueId}">参赛队伍管理</span>-->
                        <span class="span_filter span_match" style="width:50px;margin:0 10px 0 10px;cursor:pointer" idvalue="{f_LeagueId}">赛程管理</span>

                    </td>

                </tr>
                ]]>
            </item>
        </icms>
    </table>
</div>
<div id="pager_btn" style="margin:8px;">
    {PagerButton}
</div>
</body>
</html>