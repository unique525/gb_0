<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript">

        $(function(){
            //格式化状态
            $(".span_state").each(function(){
                $(this).html(formatState($(this).text()));
            });

        });


        function modifyState(lotterySetId,state){
            if(lotterySetId>0){
                $.ajax({
                    type: "get",
                    url: "/default.php?secu=manage&mod=lottery_award_user&m=async_modify_state",
                    data: {
                        lottery_id: lotterySetId,
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
    </script>

</head>
<body>


<div class="div_list">
    <!--<table width="100%" cellpadding="0" cellspacing="0">
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
    </table>-->
    <table width="100%" class="grid" cellpadding="0" cellspacing="0" id="left_tree">
        <tr class="grid_title">
            <td class="spe_line" style="width:40px;text-align:center"><!--ID--></td>
            <td class="spe_line" style="width:180px;text-align:center;">用户ID</td>
            <td class="spe_line" style="width:180px;text-align:center;">名字</td>
            <td class="spe_line" style="width:180px;text-align:center;">邮箱</td>
            <td class="spe_line" style="width:180px;text-align:center;">手机号码</td>
            <td class="spe_line" style="width:180px;text-align:center;">获奖时间</td>
            <td class="spe_line"></td>
            <!--<td class="spe_line" style="width:40px;text-align:center;">状态</td>
            <td class="spe_line" style="width:80px;text-align:center;">启用&nbsp;&nbsp;停用</td>-->
        </tr>
        <icms id="lottery_award_user_list" type="list">
            <item><![CDATA[
                <tr class="grid_item" id="{f_LotteryAwardUserId}" style="height:29px">
                    <td class="spe_line" style="text-align:center"></td>
                    <td class="spe_line" style="width:180px;text-align:center;">{f_UserId}</td>
                    <td class="spe_line" style="text-align:center">{f_UserName}</td>
                    <td class="spe_line" style="text-align:center">{f_UserEmail}</td>
                    <td class="spe_line" style="text-align:center">{f_UserMobile}</td>
                    <td class="spe_line" style="text-align:center">{f_CreateDate}</td>
                    <td class="spe_line"></td>
                    <!--<td class="spe_line2" style="width:40px;text-align:center;"><span id="span_state_{f_LotteryAwardUserId}" class="span_state" idvalue="{f_LotteryAwardUserId}">{f_State}</span></td>
                    <td class="spe_line2" style="width:80px;text-align:center;">
                        <img class="img_open" idvalue="{f_LotteryAwardUserId}" src="/system_template/{template_name}/images/manage/start.jpg" style="cursor:pointer"/>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <img class="img_close" idvalue="{f_LotteryAwardUserId}" src="/system_template/{template_name}/images/manage/stop.jpg" style="cursor:pointer"/>
                       </td>-->
                </tr>
                ]]>
            </item>
        </icms>
    </table>
    <table
</div>
</body>
</html>