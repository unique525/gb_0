<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/user/user_order.js"></script>
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
    <script type="text/javascript">
        <!--

        $(function () {
            $("#f_SendTime").datetimepicker({
                showSecond: true,
                dateFormat: 'yy-mm-dd',
                numberOfMonths: 1,
                timeFormat: 'HH:mm:ss',
                stepHour: 1,
                stepMinute: 1,
                stepSecond: 1
            });
            $("#f_AcceptTime").datetimepicker({
                showSecond: true,
                dateFormat: 'yy-mm-dd',
                numberOfMonths: 1,
                timeFormat: 'HH:mm:ss',
                stepHour: 1,
                stepMinute: 1,
                stepSecond: 1
            });

            $.ajax({
                url:"",
                data:{},
                dataType:"jsonp",
                jsonp:"jsonpcallback",
                success:function(data){

                }
            });
        });

        function submitForm() {
            $('#mainForm').submit();
        }
        function cancel(){
            window.history.back();
        }
        -->
    </script>
</head>
<body>
<div class="div_list">
    {common_body_deal}
    <form id="mainForm"
          action="/default.php?secu=manage&mod=user_order_send&m={method}&user_order_send_id={UserOrderSendId}&user_order_id={UserOrderId}"
          method="post">
        <table border="0" width="99%" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td class="spe_line" style="height:40px;" align="right">发货公司：</td>
                <td class="spe_line"><input type="text" class="input_box" id="f_SendCompany" value="{SendCompany}" name="f_SendCompany"/></td>
                <td class="spe_line" align="right">送货人姓名：</td>
                <td class="spe_line"><input type="text" class="input_box" id="f_SendPersonName" value="{SendPersonName}" name="f_SendPersonName"/>
                （电话：<input type="text" class="input_box" id="f_SendPersonMobile" value="{SendPersonMobile}" name="f_SendPersonMobile"/>）
                </td>
            </tr>
            <tr>
                <td class="spe_line" style="height:40px;" align="right">发货时间：</td>
                <td class="spe_line"><input type="text" class="input_box" id="f_SendTime" value="{SendTime}" name="f_SendTime"/></td>
                <td class="spe_line" align="right">状态：</td>
                <td class="spe_line">
                    <select id="f_State" name="f_State">
                        <option value="0">启用</option>
                        <option value="100">停用</option>
                    </select>
                    {s_State}
                </td>
            </tr>
            <tr>
                <td class="spe_line" style="height:40px;" align="right">签收人：</td>
                <td class="spe_line"><input type="text" class="input_box" id="f_AcceptPersonName" value="{AcceptPersonName}" name="f_AcceptPersonName"/></td>
                <td class="spe_line" align="right">收货地址：</td>
                <td class="spe_line"><input type="text" class="input_box" id="f_AcceptAddress" style="width:300px;" value="{AcceptAddress}" name="f_AcceptAddress"/></td>
            </tr>
            <tr>
                <td class="spe_line" style="height:40px;" align="right">收货时间：</td>
                <td class="spe_line"><input type="text" class="input_box" id="f_AcceptTime" value="{AcceptTime}" name="f_AcceptTime"/></td>
                <td class="spe_line" align="right">签收人电话：</td>
                <td class="spe_line">
                    <input type="text" class="input_box" id="f_AcceptTel" value="{AcceptTel}" name="f_AcceptTel"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" style="height:40px;" align="right">备注：</td>
                <td class="spe_line"><input type="text" class="input_box" id="f_Remark" value="{Remark}" name="f_Remark"/></td>
                <td class="spe_line" align="right"></td>
                <td class="spe_line">

                </td>
            </tr>
        </table>
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td height="60" align="center">
                    <input class="btn" value="确认" type="button" onclick="submitForm()"/>
                    <input class="btn" value="取消" type="button" onclick="cancel()"/>
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>