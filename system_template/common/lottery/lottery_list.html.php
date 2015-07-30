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
                window.location.href = '/default.php?secu=manage&mod=lottery&m=create&tab_index='+ parent.G_TabIndex +'&channel_id={ChannelId}';
            });
            //编辑
            $(".btn_edit").click(function (event) {
                event.preventDefault();
                var lotteryId = parseInt($(this).attr("idvalue"));
                window.location.href = '/default.php?secu=manage&mod=lottery&m=modify&tab_index='+ parent.G_TabIndex +'&lottery_id='+lotteryId;
            });
            //管理奖项设置
            $(".span_lottery_set").click(function (event) {
                event.preventDefault();
                var lotteryId = parseInt($(this).attr("idvalue"));
                //window.location.href = '/default.php?secu=manage&mod=lottery_set&m=list&tab_index='+ parent.G_TabIndex +'&lottery_id='+lotteryId;

                parent.G_TabUrl = '/default.php?secu=manage&mod=lottery_set&m=list&lottery_id='+lotteryId;
                parent.G_TabTitle = '奖项设置';
                parent.addTab();

            });

            //格式化状态
            $(".span_state").each(function(){
                $(this).html(formatState($(this).text()));
            });

        });


        function modifyState(lotteryId,state){
            if(lotteryId>0){
                $.ajax({
                    type: "get",
                    url: "/default.php?secu=manage&mod=lottery&m=async_modify_state",
                    data: {
                        lottery_id: lotteryId,
                        state:state
                    },
                    dataType: "jsonp",
                    jsonp: "jsonpcallback",
                    success: function(data) {
                        $("#span_state_"+lotteryId).html(formatState(state));
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
                <input id="btn_create" class="btn2" value="新增抽奖" title="" type="button"/>
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
            <td class="spe_line">抽奖名称</td>
            <td class="spe_line" style="width:180px;text-align:center;">管理奖项</td>
        </tr>
        <icms id="lottery_list" type="list">
            <item><![CDATA[
                <tr class="grid_item" id="{f_LotteryId}">
                    <td class="spe_line" style="text-align:center">{f_LotteryId}</td>
                    <td class="spe_line2" style="text-align:center;"><img style="cursor: pointer"
                                                                          class="btn_edit"
                                                                          src="/system_template/default/images/manage/edit.gif"
                                                                          alt="编辑" title="{f_LotteryId}"
                                                                          idvalue="{f_LotteryId}"
                                                                          onclick="Modify('{f_LotteryId}')"/></td>                    <td class="spe_line2" style="width:40px;text-align:center;"><span id="span_state_{f_LotteryId}" class="span_state" idvalue="{f_LotteryId}">{f_State}</span></td>
                    <td class="spe_line2" style="width:60px;text-align:center;">
                        <img class="img_open" idvalue="{f_LotteryId}" src="/system_template/{template_name}/images/manage/start.jpg" style="cursor:pointer" onclick="modifyState({f_LotteryId},0)"/>
                        &nbsp;
                        <img class="img_close" idvalue="{f_LotteryId}" src="/system_template/{template_name}/images/manage/stop.jpg" style="cursor:pointer" onclick="modifyState({f_LotteryId},100)"/>
                    </td>
                    <td class="spe_line">{f_LotteryName}</td>

                    <td class="spe_line span_lottery_set" idvalue="{f_LotteryId}" style="width:180px;text-align:center;cursor: pointer">管理奖项</td>

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