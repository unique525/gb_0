<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui-timepicker-addon.js"></script>
    <style>
        /* css for timepicker */
        .ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
        .ui-timepicker-div dl { text-align: left; }
        .ui-timepicker-div dl dt { float: left; clear:left; padding: 0 0 0 5px; }
        .ui-timepicker-div dl dd { margin: 0 10px 10px 45%; }
        .ui-timepicker-div td { font-size: 90%; }
        .ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }

        .ui-timepicker-rtl{ direction: rtl; }
        .ui-timepicker-rtl dl { text-align: right; padding: 0 5px 0 0; }
        .ui-timepicker-rtl dl dt{ float: right; clear: right; }
        .ui-timepicker-rtl dl dd { margin: 0 45% 10px 10px; }
    </style>

    <script>
        $(function(){

            $(function(){

                $(".GetDate").datetimepicker({
                    showSecond: true,
                    dateFormat: 'yy-mm-dd',
                    numberOfMonths: 1,
                    timeFormat: 'HH:mm:ss',
                    stepHour: 1,
                    stepMinute: 1,
                    stepSecond: 1
                });


            });

        });
        function submitForm(continueCreate) {
            var submit=1;
            if ($('#f_LotteryName').val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("请输入名称");
                submit=0;
            }
            if(submit==1) {
                if (continueCreate == 1) {
                    $("#CloseTab").val("0");
                } else {
                    $("#CloseTab").val("1");
                }
                $('#main_form').submit();
            }
        }
    </script>
</head>
<body>
{common_body_deal}
<form id="main_form" action="/default.php?secu=manage&mod=lottery&m={method}&channel_id={ChannelId}&lottery_id={LotteryId}&tab_index={TabIndex}" method="post">

    <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
    <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class="spe_line" height="40" align="right">
                <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/>
                <input class="btn" value="确认并继续新增" style="display:{display}" type="button" onclick="submitForm(1)"/>
                <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
            </td>
        </tr>
    </table>
    <div style="margin: 0 auto 10px;">
        <table width="99%" class="doc_grid" cellpadding="0" cellspacing="0">
            <tr class="grid_item">
                <td class="spe_line" style="height:30px;text-align:right"><label for="f_LotteryName">名称：</label></td>
                <td class="spe_line"><input type="text" id="f_LotteryName" name="f_LotteryName" value="{LotteryName}"/></td>
            </tr>
            <tr class="grid_item">
                <td class="spe_line" style="height:30px;text-align:right"><label for="f_LimitContent">抽奖限制条件：</label></td>
                <td class="spe_line"><input type="number" id="f_LimitContent" name="f_LimitContent" value="{LimitContent}"/>（例如答题100分才能抽奖，这里就填100）</td>
            </tr>
            <tr class="grid_item">
                <td class="spe_line" style="height:30px;text-align:right"><label for="f_LimitUserGroup">抽奖限制参加的会员组：</label></td>
                <td class="spe_line"><input type="number" id="f_LimitUserGroup" name="f_LimitUserGroup" value="{LimitUserGroup}"/>（填入会员组ID,以,分隔）</td>
            </tr>
            <tr class="grid_item">
                <td class="spe_line" style="height:30px;text-align:right"><label for="f_OddsType">抽奖方式：</label></td>
                <td class="spe_line">
                    <select id="f_OddsType" name="f_OddsType">
                        <option value="0" selected="selected">按几率</option>
                    </select>
                    {s_OddsType}
                </td>
            </tr>
            <tr class="grid_item">
                <td class="spe_line" style="height:30px;text-align:right"><label for="f_State">状态：</label></td>
                <td class="spe_line" style="height:30px;text-align: left">
                    <select id="f_State" name="f_State">
                        <option value="0" selected="selected">启用</option>
                        <option value="100">停用</option>
                    </select>
                    {s_State}
                </td>
            </tr>

            <tr class="grid_item">
                <td class="spe_line" style="height:30px;text-align:right"><label for="f_BeginDate">开始时间：</label></td>
                <td class="spe_line"><input class="GetDate" type="text" id="f_BeginDate" name="f_BeginDate" value="{BeginDate}"/></td>
            </tr>
            <tr class="grid_item">
                <td class="spe_line" style="height:30px;text-align:right"><label for="f_EndDate">结束时间：</label></td>
                <td class="spe_line"><input class="GetDate" type="text" id="f_EndDate" name="f_EndDate" value="{EndDate}"/></td>
            </tr>
            <tr class="grid_item">
                <td class="spe_line" style="height:30px;text-align:right"><label for="f_OneUserDoLimit">单个用户参与限制：</label></td>
                <td class="spe_line"><input type="number" id="f_OneUserDoLimit" name="f_OneUserDoLimit" value="{OneUserDoLimit}"/>（次）</td>
            </tr>

            <tr class="grid_item">
                <td class="spe_line" style="height:30px;text-align:right"><label for="f_TableId">对应表ID：</label></td>
                <td class="spe_line"><input type="number" id="f_TableId" name="f_TableId" value="{TableId}"/>（可为空）</td>
            </tr>
            <tr class="grid_item">
                <td class="spe_line" style="height:30px;text-align:right"><label for="f_TableType">对应表类型：</label></td>
                <td class="spe_line"><input type="number" id="f_TableType" name="f_TableType" value="{TableType}"/>（可为空）</td>
            </tr>

        </table>
        <div id="bot_button">
            <div style="padding-top:3px;">
                <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td colspan="2" height="30" align="center">
                            <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/>
                            <input class="btn" value="确认并继续新增" style="display:{display}" type="button" onclick="submitForm(1)"/>
                            <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</form>
</body>
</html>