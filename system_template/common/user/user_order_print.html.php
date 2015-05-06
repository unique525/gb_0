<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link type="text/css" href="/system_template/{template_name}/images/jquery_ui/jquery-ui.min.css" rel="stylesheet" />
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/common.js"></script>
    <script type="text/javascript" src="/system_js/common_event.js"></script>
    <script type="text/javascript" src="/system_js/jquery.cookie.js"></script>
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/system_js/manage/tabs.js"></script>
    <script type="text/javascript" src="/system_js/manage/user/user_order.js"></script>
    <style type="text/css">
        body{font-size: 12px}
    </style>
    <script type="text/javascript">
        function OrderPrint(){
            window.open("/default.php?secu=manage&mod=user_order&user_order_id={UserOrderId}&m=print");
        }

        $(document).ready(function(){

            $("#show_state").html(FormatOrderState({State},"1"));

        });




    </script>
</head>
<body style="font-size: 16px">

<div class="div_list" style="width:800px">
        <div style="text-align: center;"><h3 style="line-height: 110%;">“星滋味”电商平台销售出库单</h3>
            {CreateDate}
        </div>
        <table  width="99%" align="center" border="1" cellspacing="0" cellpadding="0">
            <tr>
                <td class="spe_line" height="30" style="width:50px;text-align: right">网站交易号：</td>
                <td class="spe_line" height="30" colspan="3" style="width:400px">{UserOrderNumber}</td>
            </tr>
            <tr>
                <td class="spe_line" height="30" style="width:100px;text-align: right">客户名称：</td>
                <td class="spe_line" height="30">{ReceivePersonName}</td>
                <td class="spe_line" height="30" style="width:100px;text-align: right">联系电话：</td>
                <td class="spe_line" height="30">{Mobile}</td>
            </tr>
            <tr>
                <td class="spe_line" height="30" style="width:100px;text-align: right">派送地址：</td>
                <td class="spe_line" height="30" colspan="2">
                    {District},{ReceivePersonName},{Address},{Mobile}
                </td>
                <td class="spe_line" height="30">状态：<span id="show_state"></span></td>
            </tr>
            <tr>

            </tr>
        </table>
        <div style="height:30px"></div>
        <table class="grid" width="99%" align="center" border="1" cellspacing="0" cellpadding="0">
            <tr class="grid_title2" style="height:30px">
                <td class="spe_line" height="30" style="text-align: center">序号</td>
                <td class="spe_line" height="30" style="text-align: center">商品名</td>
                <td class="spe_line" height="30" style="text-align: center">商品描述</td>
                <td class="spe_line" height="30" style="width:100px">购买价</td>
                <td class="spe_line" height="30" style="width:80px">数量</td>
                <td class="spe_line" height="30" style="width:50px">单位</td>
                <td class="spe_line" height="30" style="width:100px">小计</td>
                <td class="spe_line" height="30" style="width:100px"></td>
            </tr>
            <icms id="user_order_product_list">
                <item>
                    <![CDATA[
                    <tr class="grid_item2" style="height:30px" id="user_order_product_{f_UserOrderProductId}">
                        <td class="spe_line" height="30" style="text-align: center">
                            {c_no}
                        </td>
                        <td class="spe_line" height="30" style="text-align: center">
                            <a href="/default.php?mod=product&a=detail&product_id={f_ProductId}" target="_blank">
                                {f_ProductName}
                            </a>
                        </td>
                        <td class="spe_line" height="30" style="text-align: center">{f_ProductPriceIntro}</td>
                        <td class="spe_line" height="30">
                            ￥<span class="show_price">{f_SalePrice}</span>
                        </td>
                        <td class="spe_line" height="30">
                            {f_SaleCount}
                        </td>
                        <td class="spe_line" height="30">
                            {f_ProductUnit}
                        </td>
                        <td class="spe_line" height="30">
                            ￥<span class="UserOrderSubtotal">{f_Subtotal}</span>
                        </td>
                        <td class="spe_line" height="30">
                            {f_ProductTag}
                        </td>
                    </tr>
                    ]]>
                </item>
            </icms>
            <tr class="grid_item2">
                <td class="spe_line" width="80" height="30" colspan="1" align="right">合计：</td>
                <td class="spe_line" colspan="7" height="30" align="left">
                    ￥<span class="show_price" id="AllSaleProductPrice"></span>
                </td>
            </tr>
            <tr class="grid_item2">
                <td class="spe_line" width="80" height="30" colspan="1" align="right">配送费用：</td>
                <td class="spe_line" colspan="7" height="30" align="left">
                    ￥<span class="show_price">{SendPrice}</span>
                </td>
            </tr>
            <tr class="grid_item2">
                <td class="spe_line" width="80" height="30" colspan="1" align="right">金额总计：</td>
                <td class="spe_line" colspan="7" height="30" align="left">
                    ￥<span class="show_price">{AllPrice}</span>
                </td>
            </tr>
        </table>
    <div style="margin-top: 15px;padding-left: 20px;">
        <div style="width: 150px;float:left;">制单：</div>
        <div style="width: 150px;float:left;">财务：</div>
        <div style="width: 150px;float:left;">拣配：</div>
        <div style="width: 150px;float:left;">复核：</div>
        <div style="width: 150px;float:left;">客户：</div>
        <div style="clear:left"></div>
    </div>
</div>
</body>
</html>