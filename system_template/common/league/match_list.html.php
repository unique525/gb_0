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
                window.location.href = '/default.php?secu=manage&mod=match&m=create&tab_index='+ parent.G_TabIndex +'&league_id={LeagueId}';
            });
            //编辑
            $(".btn_edit").click(function (event) {
                event.preventDefault();
                var matchId = parseInt($(this).attr("idvalue"));
                window.location.href = '/default.php?secu=manage&mod=match&m=modify&tab_index='+ parent.G_TabIndex +'&match_id='+matchId;
            });

            //导入
            $("#btn_import").click(function (event){
                event.preventDefault();
                window.location.href = '/default.php?secu=manage&mod=match&m=import&tab_index='+ parent.G_TabIndex +'&league_id={LeagueId}';
            });




            //格式化状态
            $(".span_state").each(function(){
                $(this).html(formatState($(this).text()));
            });

            //格式化状态
            $(".span_result").each(function(){
                $(this).html(formatResult($(this).text()));
            });

        });


        function AjaxSubmitOneMatch(id,state){
            $.ajax({
                type: "get",
                url: "/default.php?secu=manage&mod=match&m=async_submit_one_match&match_id="+id+"&state="+state,
                dataType: "jsonp",
                jsonp: "jsonpcallback",
                success: function(data) {
                    if(parseInt(data["result"])>0){
                        $("#span_state_"+id).html(formatState(state));
                        var result=data["result_match"];
                        $("#span_result_"+id).html(formatState(result));
                        var homeGoal=data["home_goal"];
                        var guestGoal=data["home_goal"];
                        var homePenalty=data["home_penalty"];
                        var guestPenalty=data["guest_penalty"];
                        $("#span_goal_"+id).html(homeGoal+'<span class="penalty_'+result+'">('+homePenalty+')</span>-'+guestGoal+'<span class="penalty_'+result+'">('+guestPenalty+')</span>');
                    }else{
                        alert('更新失败！');
                    }
                },
                error:function(){
                    alert('json错误')
                }
            });
        }

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
         * 格式化结果
         * @return {string}
         */
        function formatResult(result){
            result = result.toString();
            switch (result){
                case "0":
                    return "<"+"span >未完<"+"/span>";
                    break;
                case "1":
                    return "<"+"span >主胜<"+"/span>";
                    break;
                case "2":
                    return "<"+"span >客胜<"+"/span>";
                    break;
                case "3":
                    return "<"+"span >打平<"+"/span>";
                    break;
                case "4":
                    return "<"+"span >主胜(点)<"+"/span>";
                    break;
                case "5":
                    return "<"+"span >客胜(点)<"+"/span>";
                    break;
                default :
                    return "未知";
                    break;
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
                    return "<"+"span >未开始<"+"/span>";
                    break;
                case "1":
                    return "<"+"span >进行中<"+"/span>";
                    break;
                case "2":
                    return "<"+"span >已完场<"+"/span>";
                    break;
                case "100":
                    return "<"+"span style='color:#990000'>删除<"+"/span>";
                    break;
                default :
                    return "未知";
                    break;
            }
        }
    </script>
<style>
    .goal_0{display: none}
    .penalty_0{display: none}
    .penalty_1{display: none}
    .penalty_2{display: none}
    .penalty_3{display: none}
</style>
</head>
<body>


<div class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn" width="">
                <input id="btn_create" class="btn2" value="新增比赛" title="" type="button"/>
                <input id="btn_import" class="btn2" value="批量导入" title="" type="button"/>
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
            <!--<td class="spe_line" style="width:80px;text-align:center;">启用&nbsp;&nbsp;停用</td>-->
            <td class="spe_line" style="width:100px;text-align:left;">名称</td>
            <td class="spe_line" style="width:120px;text-align:left;">场地</td>
            <td class="spe_line" style="width:180px;text-align:left;">开球时间</td>
            <td class="spe_line" style="text-align:left;">主队 - 客队</td>
            <td class="spe_line" style="width:80px;text-align:center;">状态</td>
            <td class="spe_line" style="width:70px;text-align:left;">比分</td>
            <td class="spe_line" style="width:40px;text-align:left;">结果</td>
            <td class="spe_line" style="width:80px;text-align:center;">确认完场</td>
        </tr>
        <icms id="match_list" type="list">
            <item><![CDATA[
                <tr class="grid_item" id="{f_MatchId}">
                    <td class="spe_line" style="text-align:center">{f_MatchId}</td>
                    <td class="spe_line2" style="text-align:center;"><img style="cursor: pointer"
                                                                          class="btn_edit"
                                                                          src="/system_template/default/images/manage/edit.gif"
                                                                          alt="编辑" title="{f_MatchId}"
                                                                          idvalue="{f_MatchId}"
                                                                          onclick="Modify('{f_MatchId}')"/></td>
                    <!--<td class="spe_line2" style="width:60px;text-align:center;">
                        <img class="img_open" idvalue="{f_MatchId}" src="/system_template/{template_name}/images/manage/start.jpg" style="cursor:pointer" onclick="modifyState({f_MatchId},0)"/>
                        &nbsp;
                        <img class="img_close" idvalue="{f_MatchId}" src="/system_template/{template_name}/images/manage/stop.jpg" style="cursor:pointer" onclick="modifyState({f_MatchId},100)"/>
                    </td>-->

                    <td class="spe_line">{f_MatchName}</td>
                    <td class="spe_line">{f_StadiumName}</td>
                    <td class="spe_line date_time">{f_BeginDate} {f_BeginTime}</td>
                    <td class="spe_line">{f_HomeTeamName} - {f_GuestTeamName}</td>
                    <td class="spe_line2" style="width:40px;text-align:center;"><span id="span_state_{f_MatchId}" class="span_state" idvalue="{f_MatchId}">{f_State}</span></td>
                    <td class="spe_line" id="span_goal_{f_MatchId}" style="width:50px;"><span class="goal_{f_state}">{f_HomeTeamGoal}<span class="penalty_{f_result}">({f_HomeTeamPenalty})</span> - {f_GuestTeamGoal}<span class="penalty_{f_result}">({f_GuestTeamPenalty})</span><span></span></td>
                    <td class="spe_line span_result" id="span_result_{f_MatchId}" style="width:40px;text-align:left;">{f_Result}</td>

                    <td class="spe_line" style="width:180px;text-align:center;cursor: pointer">
                        <span class="span_filter span_match btn" style="width:50px;margin:0 10px 0 10px;cursor:pointer" onclick="AjaxSubmitOneMatch({f_MatchId},2)">更新完场数据</span>
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