<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/user/user_order.js"></script>
    <style type="text/css">

    </style>
</head>
<body>
<div id="dialog_user_order_product_box" title="提示信息" style="display: none;">
    <div id="user_order_product__table" style="font-size: 14px;">
        <iframe id="user_order_product_dialog_frame" src="" frameBorder="0" style="border: 0; " scrolling="auto" width="100%" height="400px"></iframe>
    </div>
</div>
<div id="dialog_user_order_pay_box" title="提示信息" style="display: none;">
    <div id="user_order_pay__table" style="font-size: 14px;">
        <iframe id="user_order_pay_dialog_frame" src="" frameBorder="0" style="border: 0; " scrolling="auto" width="100%" height="400px"></iframe>
    </div>
</div>
<div class="div_list">
    {common_body_deal}
    <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class="spe_line" height="40" align="right">
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
                <td class="spe_line" height="30" style="width:300px;text-align: right">订单编号：</td>
                <td class="spe_line" height="30" style="width:300px">{UserOrderNumber}</td>
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
                                <option value="{f_UserReceiveInfoId}">{f_ReceivePersonName},{f_Address},{f_Mobile}</option>
                                ]]>
                            </item>
                        </icms>
                    </select>
                    {s_UserReceiveInfoId}
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" style="width:100px;text-align: right">送货价格：</td>
                <td class="spe_line" height="30"><input type="text" class="input_price" id="SendPrice" name="f_SendPrice" value="{SendPrice}"/></td>
                <td class="spe_line" height="30" style="width:100px;text-align: right">送货时间：</td>
                <td class="spe_line" height="30">{SendTime}</td>
            </tr>
            <tr>
                <td class="spe_line" height="30" style="width:100px;text-align: right">订单总价：</td>
                <td class="spe_line" height="30" id="display_AllPrice">{AllPrice}元</td>
                <td class="spe_line" height="30" style="width:100px;text-align: right">订单状态：</td>
                <td class="spe_line" height="30">
                    <select name="f_State" id="f_State">
                        <option value="0">未付款</option>
                        <option value="10">已付款</option>
                        <option value="20">已发货</option>
                        <option value="30">交易完成</option>
                        <option value="40">交易关闭</option>
                        {s_state}
                    </select>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" style="width:100px;text-align: right">支付信息：</td>
                <td colspan="3" class="spe_line" height="30" style="text-align: left">
                    <div class="btn2" id="btn_user_order_pay" style="width:80px;text-align: center;float:left" idvalue="{f_UserOrderId}">点击查看</div>
                    <div style="clear:left"></div>
                </td>
            </tr>
        </table>
        <div style="height:30px"></div>
        <table class="grid" width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr class="grid_title2" style="height:30px">
                <td class="spe_line" height="30" style="width:50px;text-align: center">编辑</td>
                <td class="spe_line" height="30" style="text-align: center">商品名</td>
                <td class="spe_line" height="30" style="text-align: center">商品描述</td>
                <td class="spe_line" height="30" style="width:150px">商品单价</td>
                <td class="spe_line" height="30" style="width:150px">购买价</td>
                <td class="spe_line" height="30" style="width:150px">数量</td>
                <td class="spe_line" height="30" style="width:150px">小计</td>
                <td class="spe_line" height="30" style="width:150px;text-align: center"></td>
            </tr>
            <icms id="user_order_product_list">
                <item>
                    <![CDATA[
                    <tr class="grid_item2" style="height:30px">
                        <td class="spe_line" height="30" style="text-align: center">
                            <img src="/system_template/{template_name}/images/manage/edit.gif" style="cursor:pointer" class="btn_order_product_edit" idvalue="{f_UserOrderProductId}" title={f_UserOrderId}/>
                        </td>
                        <td class="spe_line" height="30" style="width:100px;text-align: center">{f_ProductName}</td>
                        <td class="spe_line" height="30" style="width:300px;text-align: center">{f_ProductPriceIntro}</td>
                        <td class="spe_line" height="30" style="width:150px">{f_ProductPrice}元/{f_ProductUnit}</td>
                        <td class="spe_line" height="30" style="width:150px">
                            {f_SalePrice}元/{f_ProductUnit}
                        </td>
                        <td class="spe_line" height="30" style="width:150px">
                            {f_SaleCount}{f_ProductUnit}
                        </td>
                        <td class="spe_line UserOrderSubtotal" height="30" style="width:150px">{f_Subtotal}元</td>
                        <td class="spe_line height="30"">
                            <div class="btn2" style="cursor:pointer;width:50px;text-align: center">删除</div>
                        </td>
                    </tr>
                    ]]>
                </item>
            </icms>
            <tr class="grid_item2">
                <td class="spe_line" height="30" colspan="6" align="right">总计：</td>
                <td class="spe_line" colspan="2" height="30" id="AllSaleProductPrice" align="left">

                </td>
            </tr>
        </table>
        <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
        <input name="PageIndex" type="hidden" value="{PageIndex}"/>
        <input name="PageSize" type="hidden" value="{PageSize}"/>
        <input name="TabIndex" type="hidden" value="{TabIndex}"/>
        <input name="f_AllPrice" id="AllPrice" type="hidden" value=""/>
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