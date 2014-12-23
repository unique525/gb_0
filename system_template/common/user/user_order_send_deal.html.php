<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/user/user_order.js"></script>
    <style type="text/css">

    </style>
    <script type="text/javascript">
        <!--

        $(function () {
            $("#f_SendTime").datepicker({
                dateFormat: 'yy-mm-dd',
                numberOfMonths: 1,
                showButtonPanel: true
            });
            $("#f_AcceptTime").datepicker({
                dateFormat: 'yy-mm-dd',
                numberOfMonths: 1,
                showButtonPanel: true
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
                <td class="spe_line"><input type="text" class="input_box" id="f_SendPersonName" value="{SendPersonName}" name="f_SendPersonName"/></td>
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
                <td class="spe_line"><input type="text" class="input_box" id="f_AcceptAddress" value="{AcceptAddress}" name="f_AcceptAddress"/></td>
            </tr>
            <tr>
                <td class="spe_line" style="height:40px;" align="right">收货时间：</td>
                <td class="spe_line"><input type="text" class="input_box" id="f_AcceptTime" value="{AcceptTime}" name="f_AcceptTime"/></td>
                <td class="spe_line" align="right">备注：</td>
                <td class="spe_line">
                    <input type="text" class="input_box" id="f_Remark" value="{Remark}" name="f_Remark"/>
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