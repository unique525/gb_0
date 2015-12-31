<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}


    <script>
        $(function(){

            //日期控件
            $(".GetDate").datepicker({
                dateFormat: 'yy-mm-dd',
                numberOfMonths: 1,
                showButtonPanel: true
            });

        });
        function submitForm(continueCreate) {
            var submit=1;
            if ($('#f_LotterySetName').val() == '') {
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
<form id="main_form" action="/default.php?secu=manage&mod=lottery_set&m={method}&lottery_id={LotteryId}&lottery_set_id={LotterySetId}&tab_index={TabIndex}" method="post">

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
                <td class="spe_line" style="height:30px;text-align:right"><label for="f_LotterySetName">奖项名称：</label></td>
                <td class="spe_line"><input type="text" id="f_LotterySetName" name="f_LotterySetName" value="{LotterySetName}"/></td>
            </tr>
            <tr class="grid_item">
                <td class="spe_line" style="height:30px;text-align:right"><label for="f_DayLimit">每日设奖：</label></td>
                <td class="spe_line"><input type="number" id="f_DayLimit" name="f_DayLimit" value="{DayLimit}"/> 个(一般不能为0)</td>
            </tr>
            <tr class="grid_item">
                <td class="spe_line" style="height:30px;text-align:right"><label for="f_TotalLimit">总设奖：</label></td>
                <td class="spe_line"><input type="number" id="f_TotalLimit" name="f_TotalLimit" value="{TotalLimit}"/> 个(一般不能为0)</td>
            </tr>
            <tr class="grid_item">
                <td class="spe_line" style="height:30px;text-align:right"><label for="f_LotterySetGroup">奖项组：</label></td>
                <td class="spe_line"><input type="number" id="f_LotterySetGroup" name="f_LotterySetGroup" value="{LotterySetGroup}"/></td>
            </tr>
            <tr class="grid_item">
                <td class="spe_line" style="height:30px;text-align:right"><label for="f_OneUserLimit">每人获奖限制：</label></td>
                <td class="spe_line"><input type="number" id="f_OneUserLimit" name="f_OneUserLimit" value="{OneUserLimit}"/> 次(-1为不做限制)</td>
            </tr>
            <tr class="grid_item">
                <td class="spe_line" style="height:30px;text-align:right"><label for="f_OneUserDoLimit">每人抽奖限制次数：</label></td>
                <td class="spe_line"><input type="number" id="f_OneUserDoLimit" name="f_OneUserDoLimit" value="{OneUserDoLimit}"/> 次(0为不做限制)</td>
            </tr>
            <tr class="grid_item">
                <td class="spe_line" style="height:30px;text-align:right"><label for="f_BeginTime">开始时间：</label></td>
                <td class="spe_line"><input class="GetDate" type="text" id="f_BeginTime" name="f_BeginTime" value="{BeginTime}"/></td>
            </tr>
            <tr class="grid_item">
                <td class="spe_line" style="height:30px;text-align:right"><label for="f_EndTime">结束时间：</label></td>
                <td class="spe_line"><input class="GetDate" type="text" id="f_EndTime" name="f_EndTime" value="{EndTime}"/></td>
            </tr>
            <tr class="grid_item">
                <td class="spe_line" style="height:30px;text-align:right"><label for="f_Odds">中奖几率：</label></td>
                <td class="spe_line"><input type="number" id="f_Odds" name="f_Odds" value="{Odds}"/> ‱</td>
            </tr>
            <tr class="grid_item">
                <td class="spe_line" style="height:30px;text-align:right"><label for="f_State">状态：</label></td>
                <td class="spe_line" style="height:30px;text-align: left">
                    <select id="f_State" name="f_State">
                        <option value="0">启用</option>
                        <option value="100" selected="selected">停用</option>
                    </select>
                    {s_State}
                </td>
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