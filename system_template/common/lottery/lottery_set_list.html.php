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
                window.location.href = '/default.php?secu=manage&mod=lottery_set&m=create&tab_index='+ parent.G_TabIndex +'&lottery_id={LotteryId}';
            });
            //编辑
            $(".btn_edit").click(function (event) {
                event.preventDefault();
                var lotterySetId = parseInt($(this).attr("idvalue"));
                window.location.href='/default.php?secu=manage&mod=lottery_set&m=modify&tab_index='+ parent.G_TabIndex +'&lottery_set_id='+lotterySetId;

                //parent.G_TabUrl = '/default.php?secu=manage&mod=lottery_set&m=list&lottery_id='+lotteryId;
                //parent.G_TabTitle = '奖项设置';
                //parent.addTab();

            });


            //管理获奖用户
            $(".span_lottery_award_user").click(function (event) {
                event.preventDefault();
                var lotterySetId = parseInt($(this).attr("idvalue"));
                window.open('/default.php?secu=manage&mod=lottery_award_user&m=list&tab_index='+ parent.G_TabIndex +'&lottery_set_id='+lotterySetId);

                //parent.G_TabUrl = '/default.php?secu=manage&mod=lottery_set&m=list&lottery_id='+lotteryId;
                //parent.G_TabTitle = '奖项设置';
                //parent.addTab();

            });

            //修改状态
            $(".img_open").click(function(){
                modifyState($(this).attr("idvalue"),0)
            });
            $(".img_close").click(function(){
                modifyState($(this).attr("idvalue"),100)
            });

            //格式化状态
            $(".span_state").each(function(){
                $(this).html(formatState($(this).text()));
            });

            $(".one_user_limit[idvalue='-1']").html("不限制");

        });


        function modifyState(lotterySetId,state){
            if(lotterySetId>0){
                $.ajax({
                    type: "get",
                    url: "/default.php?secu=manage&mod=lottery_set&m=async_modify_state",
                    data: {
                        lottery_set_id: lotterySetId,
                        state:state
                    },
                    dataType: "jsonp",
                    jsonp: "jsonpcallback",
                    success: function(data) {
                        $("#span_state_"+lotterySetId).html(formatState(state));
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


        /**
         * 检查日期
         * @param endDate 结束时间
         * @return
         */
        function CheckEndDate(endDate){
            var after = 0;

            var today = new Date();

            var arr=endDate.split(" ");
            arr=arr[0].split("-");
            var site_year = arr[0];
            var site_month = arr[1]-1;
            var site_day = arr[2];

            var end=new Date(site_year,site_month,site_day)
            if(end>today){
                after=1;
            }

            if(after == 1){
                document.write(endDate);
            }else{
                document.write('<font color=red>'+endDate+'</font>');

            }
        }
    </script>

</head>
<body>


<div class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn" width="83">
                <input id="btn_create" class="btn2" value="新增奖项" title="" type="button"/>
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
            <td class="spe_line">奖项名称</td>
            <td class="spe_line" style="width:160px;text-align:center;">开始时间</td>
            <td class="spe_line" style="width:160px;text-align:center;">截止时间</td>
            <td class="spe_line" style="width:80px;text-align:center;">中奖几率</td>
            <td class="spe_line" style="width:80px;text-align:center;">每日设奖</td>
            <td class="spe_line" style="width:80px;text-align:center;">总设奖</td>
            <td class="spe_line" style="width:80px;text-align:center;">已获奖</td>
            <td class="spe_line" style="width:80px;text-align:center;">奖项组</td>
            <td class="spe_line" style="width:100px;text-align:center;">单人获奖限制</td>
            <td class="spe_line" style="width:180px;text-align:center;">操作</td>
        </tr>
        <icms id="lottery_set_list" type="list">
            <item><![CDATA[
                <tr class="grid_item" id="{f_LotterySetId}">
                    <td class="spe_line" style="text-align:center">{f_LotterySetId}</td>
                    <td class="spe_line2" style="text-align:center;"><img style="cursor: pointer"
                                                                          class="btn_edit"
                                                                          src="/system_template/default/images/manage/edit.gif"
                                                                          alt="编辑" title="{f_LotterySetId}"
                                                                          idvalue="{f_LotterySetId}"/></td>
                    <td class="spe_line2" style="width:40px;text-align:center;"><span id="span_state_{f_LotterySetId}" class="span_state" idvalue="{f_LotterySetId}">{f_State}</span></td>
                    <td class="spe_line2" style="width:80px;text-align:center;">
                        <img class="img_open" idvalue="{f_LotterySetId}" src="/system_template/{template_name}/images/manage/start.jpg" style="cursor:pointer"/>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <img class="img_close" idvalue="{f_LotterySetId}" src="/system_template/{template_name}/images/manage/stop.jpg" style="cursor:pointer"/>
                    </td>
                    <td class="spe_line">{f_LotterySetName}</td>
                    <td class="spe_line" style="width:160px;text-align:center;">{f_BeginTime}</td>
                    <td class="spe_line" style="width:160px;text-align:center;"><script type="text/javascript">CheckEndDate("{f_EndTime}")</script></td>
                    <td class="spe_line" style="width:80px;text-align:center;">{f_Odds}‱</td>
                    <td class="spe_line" style="width:80px;text-align:center;">{f_DayLimit}个</td>
                    <td class="spe_line" style="width:80px;text-align:center;">{f_TotalLimit}个</td>
                    <td class="spe_line" style="width:80px;text-align:center;">{f_AwardCount}次</td>
                    <td class="spe_line" style="width:80px;text-align:center;">{f_LotterySetGroup}</td>
                    <td class="spe_line one_user_limit" idvalue="{f_OneUserLimit}" style="width:100px;text-align:center;">{f_OneUserLimit}次</td>
                    <td class="spe_line span_lottery_award_user" idvalue="{f_LotterySetId}" style="width:180px;text-align:center;cursor: pointer">查看获奖用户</td>
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