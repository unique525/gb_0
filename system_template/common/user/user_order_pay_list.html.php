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
            $("#btn_create").click(function(){
                window.location.href = "/default.php?secu=manage&mod=user_order_pay&m=create&user_order_id={UserOrderId}";
            });

            $(".btn_modify").click(function(){
                var userOrderPayId = $(this).attr("idvalue");
                window.location.href = "/default.php?secu=manage&mod=user_order_pay&m=modify&user_order_pay_id="+userOrderPayId+"&user_order_id={UserOrderId}";
            });

            $(".span_pay_type").each(function(){
                var payType = $(this).html();
                $(this).html(FormatPayType(payType));
            });


            function FormatPayType(payType){
                var result = '';
                payType = parseInt(payType);
                switch(payType){
                    case 0:
                        result = '收款';
                        break;
                    case 10:
                        result = '退款';
                        break;
                }
                return result;
            }

        });


        -->
    </script>
</head>
<body>
<div class="div_list">
    <div style="width:100%;margin-bottom:4px;">
        <input id="btn_create" class="btn2" value="新增支付信息" title="新增支付信息" type="button"/>
    </div>

    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
        <tr  class="grid_title2">
            <td class="spe_line" height="30" width="40" align="center">编辑</td>
            <td class="spe_line" height="30" width="80" align="center">支付编号</td>
            <td class="spe_line" height="30" align="center">订单编号</td>
            <td class="spe_line" height="30" width="80" align="center">交易类型</td>
            <td class="spe_line" height="30" width="100" align="center">支付方式</td>
            <td class="spe_line" height="30" width="100" align="center">支付价格</td>
            <td class="spe_line" height="30" width="160" align="center">支付日期</td>
            <td class="spe_line" height="30" width="100" align="center">确认收款方式</td>
            <td class="spe_line" height="30" width="100" align="center">确认收款金额</td>
            <td class="spe_line" height="30" width="160" align="center">确认收款日期</td>
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
                            <td class="spe_line" height="30" width="40" align="center">
                                <img class="btn_modify" style="cursor:pointer;" src="/system_template/{template_name}/images/manage/edit.gif" idvalue="{f_UserOrderPayId}" alt="编辑"/>
                            </td>
                            <td class="spe_line" height="30" width="80" align="center">{f_UserOrderPayId}</td>
                            <td class="spe_line" height="30" align="center">{f_UserOrderNumber}</td>
                            <td class="spe_line" height="30" width="80" align="center"><span class="span_pay_type">{f_PayType}</span></td>
                            <td class="spe_line" height="30" width="100" align="center">{f_PayWay}</td>
                            <td class="spe_line" height="30" width="100" align="center">
                                ￥<span class="show_price">{f_PayPrice}</span>
                            </td>
                            <td class="spe_line" height="30" width="160" align="center">{f_PayDate}</td>
                            <td class="spe_line" height="30" width="100" align="center">
                                <span id="confirm_pay_way_{f_UserOrderPayId}">{f_ConfirmWay}</span>
                            </td>
                            <td class="spe_line" height="30" width="100" align="center">
                                ￥<span class="show_price" id="confirm_pay_price_{f_UserOrderPayId}">{f_ConfirmPrice}</span>
                            </td>
                            <td class="spe_line" height="30" width="160" align="center">
                                <span id="confirm_pay_date_{f_UserOrderPayId}">{f_ConfirmDate}</span>
                            </td>
                            <td class="spe_line" height="30" width="80" align="center">
                                <span id="span_order_pay_state_{f_UserOrderPayId}" class="span_order_pay_state" idvalue="{f_UserOrderPayId}">{f_State}</span>
                            </td>
                            <td class="spe_line" height="30" width="80" align="center">
                               <span id="manage_username_{f_UserOrderPayId}">{f_ManageUserName}</span>
                            </td>
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