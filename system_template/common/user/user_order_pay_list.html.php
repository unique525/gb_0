<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/user/user_order.js"></script>
    <style type="text/css">

    </style>
</head>
<body>
<div class="div_list">
    <div class="search" style="width:99%;height:30px"></div>
    <div class="advance_search" style="width:99%;height:150px;border:1px #CCC solid;display: none">

    </div>
    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
        <tr  class="grid_title2">
            <td class="spe_line" height="30" width="80" align="center">支付编号</td>
            <td class="spe_line" height="30" width="150" align="center">订单编号</td>
            <td class="spe_line" height="30" width="100" align="center">支付方式</td>
            <td class="spe_line" height="30" width="100">支付价格</td>
            <td class="spe_line" height="30" width="160" align="center">支付日期</td>
            <td class="spe_line" height="30" width="100" align="center">确认收款方式</td>
            <td class="spe_line" height="30" width="100" align="center">确认收款金额</td>
            <td class="spe_line" height="30" width="160" align="center">确认收款日期</td>
            <td class="spe_line" height="30" width="80" align="center">操作</td>
            <td class="spe_line" height="30" width="80" align="center">状态</td>
            <td class="spe_line" height="30" width="80" align="center">核对人</td>
        </tr>
    </table>
    <ul id="type_list">
        <icms id="user_order_pay_list">
            <item>
                <![CDATA[
                <li>
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item2">
                            <td class="spe_line" height="30" width="80" align="center">{f_UserOrderPayId}</td>
                            <td class="spe_line" height="30" width="150" align="center">{f_UserOrderNumber}</td>
                            <td class="spe_line" height="30" width="100" align="center">{f_PayWay}</td>
                            <td class="spe_line" height="30" width="100">{f_PayPrice}</td>
                            <td class="spe_line" height="30" width="160" align="center">{f_PayDate}</td>
                            <td id="confirm_pay_way_{f_UserOrderPayId}" class="spe_line" height="30" width="100" align="center">{f_ConfirmWay}</td>
                            <td id="confirm_pay_price_{f_UserOrderPayId}" class="spe_line" height="30" width="100" align="center">{f_ConfirmPrice}</td>
                            <td id="confirm_pay_date_{f_UserOrderPayId}" class="spe_line" height="30" width="160" align="center">{f_ConfirmDate}</td>
                            <td id="span_order_pay_{f_UserOrderPayId}_button" class="spe_line" height="30" width="80" align="center"></td>
                            <td id="span_order_pay_state_{f_UserOrderPayId}" class="spe_line span_order_pay_state" idvalue="{f_UserOrderPayId}"  height="30" width="80" align="center">{f_State}</td>
                            <td id="manage_username_{f_UserOrderPayId}" class="spe_line" height="30" width="80" align="center">{f_ManageUserName}</td>
                        </tr>
                    </table>
                </li>
        ]]>
     </item>
 </icms>
</ul>
</div>
</body>
</html>