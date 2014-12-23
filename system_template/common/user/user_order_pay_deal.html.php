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
    {common_body_deal}
    <form id="userOrderPayForm"
          action="/default.php?secu=manage&mod=user_order_pay&m={method}&user_order_pay_id={UserOrderPayId}&user_order_id={UserOrderId}&site_id={SiteId}"
          method="post">
        <table border="0" width="99%" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td class="spe_line" align="right">商品名：</td>
                <td class="spe_line">{ProductName}</td>
                <td class="spe_line" align="right">商品参数：</td>
                <td class="spe_line">{ProductPriceIntro}</td>
            </tr>
            <tr>
                <td class="spe_line" align="right">商品价格：</td>
                <td class="spe_line">{ProductPrice}元</td>
                <td class="spe_line" align="right">购买价：</td>
                <td class="spe_line">
                    <input type="text" class="input_price" id="SalePrice" value="{SalePrice}" name="f_SalePrice"/>元
                </td>
            </tr>
            <tr>
                <td class="spe_line" align="right">数量：</td>
                <td class="spe_line">
                    <input type="text" class="input_number" id="SaleCount" value="{SaleCount}" name="f_SaleCount"/>
                </td>
                <td class="spe_line" align="right">是否自动发货：</td>
                <td class="spe_line">
                    <select id="f_AutoSendMessage" name="f_AutoSendMessage">
                        <option value="否">否</option>
                        <option value="是">是</option>
                    </select>
                    {s_AutoSendMessage}
                </td>
            </tr>
            <tr>
                <td class="spe_line" align="right">小计：</td>
                <td class="spe_line" colspan="3" id="display_subtotal">
                    {Subtotal}元
                </td>
            </tr>
        </table>
        <input type="hidden" id="Subtotal" value="" name="f_Subtotal"/>
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td height="60" align="center">
                    <input class="btn" value="确认" type="button" onclick="submitUserProductForm()"/>
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>