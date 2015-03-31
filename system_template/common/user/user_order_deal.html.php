<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/user/user_order.js"></script>
    <style type="text/css">

    </style>
    <script type="text/javascript">
        function OrderPrint(){
            window.open("/default.php?secu=manage&mod=user_order&m=print&user_order_id={UserOrderId}&site_id={SiteId}");
        }
    </script>
</head>
<body>
<div id="dialog_user_order_product_box" title="提示信息" style="display: none;">
    <div id="user_order_product__table" style="font-size: 14px;">
        <iframe id="user_order_product_dialog_frame" src="" frameBorder="0" style="border: 0; " scrolling="auto" width="100%" height="400px"></iframe>
    </div>
</div>
<div id="dialog_user_order_pay_box" title="提示信息" style="display: none;">
    <div id="user_order_pay__table" style="font-size: 14px;">
        <iframe id="user_order_pay_dialog_frame" src="" frameBorder="0" style="border: 0; " scrolling="auto" width="100%" height="650"></iframe>
    </div>
</div>
<div class="div_list">
    {common_body_deal}
    <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class="spe_line" height="40" align="right">
                <input class="btn" value="打印" type="button" onclick="OrderPrint()"/>
                <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/>
                <input class="btn" value="确认并继续" type="button" onclick="submitForm(1)"/>
                <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
            </td>
        </tr>
    </table>
    <form id="mainForm"
          action="/default.php?secu=manage&mod=user_order&m={method}&user_order_id={UserOrderId}&site_id={SiteId}"
          method="post">
        <table  width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="spe_line" height="30" style="width:50px;text-align: right">订单编号：</td>
                <td class="spe_line" height="30" style="width:400px">{UserOrderNumber}</td>
                <td class="spe_line" height="30" style="width:100px;text-align: right">订单名：</td>
                <td class="spe_line" height="30">
                    <input type="text" class="input_box" name="f_UserOrderName" value="{UserOrderName}"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" style="width:100px;text-align: right">收货信息：</td>
                <td class="spe_line" height="30" colspan="3">
                    <select name="f_UserReceiveInfoId" id="f_UserReceiveInfoId">
                        <icms id="user_receive_info_list">
                            <item><![CDATA[
                                <option value="{f_UserReceiveInfoId}">{f_District},{f_ReceivePersonName},{f_Address},{f_Mobile}</option>
                                ]]>
                            </item>
                        </icms>
                    </select>
                    {s_UserReceiveInfoId}
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" style="width:100px;text-align: right">发货费用：</td>
                <td class="spe_line" height="30">￥<input type="text" class="input_price" id="SendPrice" name="f_SendPrice" value="{SendPrice}"/></td>
                <td class="spe_line" height="30" style="width:100px;text-align: right">发货时间：</td>
                <td class="spe_line" height="30">{SendTime}</td>
            </tr>
            <tr>
                <td class="spe_line" height="30" style="width:100px;text-align: right">订单总价：</td>
                <td class="spe_line" height="30">
                    ￥<input type="text" class="input_price" id="display_AllPrice" name="f_AllPrice" value="{AllPrice}"/>
                </td>
                <td class="spe_line" height="30" style="width:100px;text-align: right">订单状态：</td>
                <td class="spe_line" height="30">
                    <select name="f_State" id="f_State">
                        <option value="0">新建</option>
                        <option value="10">未付款</option>
                        <option value="15">货到付款</option>
                        <option value="20">已付款，未发货</option>
                        <option value="25">已发货</option>
                        <option value="30">交易完成</option>
                        <option value="31">交易完成，已评价</option>
                        <option value="40">交易关闭</option>
                        <option value="50">申请退款</option>
                        <option value="55">退款完成</option>
                        <option value="70">未评价</option>
                        <option value="100">已删除</option>
                        {s_State}
                    </select>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" style="width:100px;text-align: right">支付信息：</td>
                <td class="spe_line" height="30" style="text-align: left">
                    <div class="btn2" id="btn_user_order_pay" style="width:80px;text-align: center;float:left" idvalue="{UserOrderId}">点击查看</div>
                    <div style="clear:left"></div>
                </td>
                <td class="spe_line"></td>
                <td class="spe_line">支付宝交易号：{AlipayTradeNo} &nbsp;&nbsp;&nbsp; 支付宝交易状态：{AlipayTradeStatus}</td>
            </tr>
        </table>
        <div style="height:30px"></div>
        <table class="grid" width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr class="grid_title2" style="height:30px">
                <td class="spe_line" height="30" style="width:50px;text-align: center">编辑</td>
                <td class="spe_line" height="30" style="text-align: center">商品名</td>
                <td class="spe_line" height="30" style="text-align: center">商品描述</td>
                <td class="spe_line" height="30" style="width:100px">商品单价</td>
                <td class="spe_line" height="30" style="width:100px">购买价</td>
                <td class="spe_line" height="30" style="width:80px">数量</td>
                <td class="spe_line" height="30" style="width:50px">单位</td>
                <td class="spe_line" height="30" style="width:100px">小计</td>
                <td class="spe_line" height="30" style="width:80px;text-align: center"></td>
            </tr>
            <icms id="user_order_product_list">
                <item>
                    <![CDATA[
                    <tr class="grid_item2" style="height:30px" id="user_order_product_{f_UserOrderProductId}">
                        <td class="spe_line" height="30" style="text-align: center">
                            <img src="/system_template/{template_name}/images/manage/edit.gif" style="cursor:pointer" class="btn_order_product_edit" idvalue="{f_UserOrderProductId}" title={f_UserOrderId}/>
                        </td>
                        <td class="spe_line" height="30" style="text-align: center">
                            <a href="/default.php?mod=product&a=detail&product_id={f_ProductId}" target="_blank">
                            {f_ProductName}
                            </a>
                        </td>
                        <td class="spe_line" height="30" style="text-align: center">{f_ProductPriceIntro}</td>
                        <td class="spe_line " height="30">
                            ￥<span class="show_price">{f_ProductPrice}</span>
                        </td>
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
                            <div class="delete_order_product btn2 " idvalue="{f_UserOrderProductId}" title="{f_UserOrderId}" style="cursor:pointer;width:50px;text-align: center">删除</div>
                        </td>
                    </tr>
                    ]]>
                </item>
            </icms>
            <tr class="grid_item2">
                <td class="spe_line" height="30" colspan="6" align="right">总计：</td>
                <td class="spe_line" colspan="2" height="30" align="left">
                    ￥<span class="show_price" id="AllSaleProductPrice"></span>
                </td>
            </tr>
        </table>
        <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
        <input name="PageIndex" type="hidden" value="{PageIndex}"/>
        <input name="PageSize" type="hidden" value="{PageSize}"/>
        <input name="TabIndex" type="hidden" value="{TabIndex}"/>
    </form>
    <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td height="60" align="center">
                <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/> <input class="btn"
                                                                                                value="确认并继续"
                                                                                                type="button"
                                                                                                onclick="submitForm(1)"/>
                <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
            </td>
        </tr>
    </table>
</div>
</body>
</html>