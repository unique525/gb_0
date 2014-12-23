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
            $("#f_PayDate").datepicker({
                dateFormat: 'yy-mm-dd',
                numberOfMonths: 1,
                showButtonPanel: true
            });
            $("#f_ConfirmDate").datepicker({
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
          action="/default.php?secu=manage&mod=user_order_pay&m={method}&user_order_pay_id={UserOrderPayId}&user_order_id={UserOrderId}"
          method="post">
        <table border="0" width="99%" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td class="spe_line" style="height:40px;" align="right">支付方式：</td>
                <td class="spe_line"><input type="text" class="input_box" id="f_PayWay" value="{PayWay}" name="f_PayWay"/> （银行、第三方支付等名称）</td>
                <td class="spe_line" align="right">支付价格：</td>
                <td class="spe_line"><input type="text" class="input_price" id="f_PayPrice" value="{PayPrice}" name="f_PayPrice"/>元</td>
            </tr>
            <tr>
                <td class="spe_line" style="height:40px;" align="right">支付时间：</td>
                <td class="spe_line"><input type="text" class="input_box" id="f_PayDate" value="{PayDate}" name="f_PayDate"/></td>
                <td class="spe_line" align="right">交易类型：</td>
                <td class="spe_line">
                    <select id="f_PayType" name="f_PayType">
                        <option value="0">收款</option>
                        <option value="10">退款</option>
                    </select>
                    {s_PayType}
                </td>
            </tr>
            <tr>
                <td class="spe_line" style="height:40px;" align="right">确认支付方式：</td>
                <td class="spe_line"><input type="text" class="input_box" id="f_ConfirmWay" value="{ConfirmWay}" name="f_ConfirmWay"/> （银行、第三方支付等名称）</td>
                <td class="spe_line" align="right">确认支付价格：</td>
                <td class="spe_line"><input type="text" class="input_price" id="f_ConfirmPrice" value="{ConfirmPrice}" name="f_ConfirmPrice"/>元</td>
            </tr>
            <tr>
                <td class="spe_line" style="height:40px;" align="right">确认支付时间：</td>
                <td class="spe_line"><input type="text" class="input_box" id="f_ConfirmDate" value="{ConfirmDate}" name="f_ConfirmDate"/></td>
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