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
                <td class="spe_line" style="width:300px;text-align: right">订单编号：</td>
                <td class="spe_line" style="width:300px">{UserOrderNumber}</td>
                <td class="spe_line" style="width:100px;text-align: right">订单名：</td>
                <td class="spe_line">
                    <input type="text" class="input_box" name="f_UserOrderName" value="{UserOrderName}"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" style="width:100px;text-align: right">收货信息：</td>
                <td class="spe_line" colspan="3">
                    <select name="{f_UserReceiveInfoId}" id="f_UserReceiveInfoId">
                        <icms id="user_receive_info_list">
                            <item><![CDATA[
                                <option value="{f_UserReceiveInfoId}">{f_ReceivePersonName},{f_Address},{f_Mobile}</option>
                                ]]>
                            </item>
                        </icms>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="spe_line" style="width:100px;text-align: right">支付方式：</td>
                <td class="spe_line">{PayWay}</td>
                <td class="spe_line" style="width:100px;text-align: right">确认支付方式:</td>
                <td class="spe_line"><input type="text" class="input_box" name="f_ConfirmWay" value="{ConfirmWay}"/></td>
            </tr>
            <tr>
                <td class="spe_line" style="width:100px;text-align: right">支付金额：</td>
                <td class="spe_line">{PayPrice}</td>
                <td class="spe_line" style="width:100px;text-align: right">确认支付金额：</td>
                <td class="spe_line"><input type="text" class="input_price" name="f_ConfirmPrice" value="{ConfirmPrice}"/></td>
            </tr>
            <tr>
                <td class="spe_line" style="width:100px;text-align: right">支付时间：</td>
                <td class="spe_line">{PayDate}</td>
                <td class="spe_line" style="width:100px;text-align: right">确认支付时间：</td>
                <td class="spe_line"><input type="text" class="input_box" name="f_ConfirmDate" value="{ConfirmDate}"/></td>
            </tr>
            <tr>
                <td class="spe_line" style="width:100px;text-align: right">送货价格：</td>
                <td class="spe_line"><input type="text" class="input_price" name="f_SendPrice" value="{SendPrice}"/></td>
                <td class="spe_line" style="width:100px;text-align: right">送货时间：</td>
                <td class="spe_line">{SendTime}</td>
            </tr>
            <tr>
                <td class="spe_line" style="width:100px;text-align: right">订单总价：</td>
                <td class="spe_line">{AllPrice}</td>
                <td class="spe_line" style="width:100px;text-align: right">订单状态：</td>
                <td class="spe_line">
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
        </table>
        <div style="height:30px"></div>
        <table class="grid" width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr class="grid_title2" style="height:30px">
                <td class="spe_line" style="width:100px;text-align: center">商品名</td>
                <td class="spe_line" style="width:600px;text-align: center">商品描述</td>
                <td class="spe_line" style="width:150px">商品单价</td>
                <td class="spe_line" style="width:150px">折后价</td>
                <td class="spe_line" style="width:150px">购买数</td>
                <td class="spe_line" style="width:150px">小计</td>
                <td class="spe_line" style="text-align: center">操作</td>
            </tr>
            <icms id="user_order_product_list">
                <item>
                    <![CDATA[
                    <tr class="grid_item2" style="height:30px">
                        <td class="spe_line" style="width:100px;text-align: center">{f_ProductName}</td>
                        <td class="spe_line" style="width:300px;text-align: center">{f_ProductIntro}</td>
                        <td class="spe_line" style="width:150px">{f_ProductPrice}/{f_ProductUnit}</td>
                        <td class="spe_line" style="width:150px">
                            <input type="text" class="input_price" size="8" value="{f_SalePrice}"/>/{f_ProductUnit}
                        </td>
                        <td class="spe_line" style="width:150px">{f_SaleCount}{f_ProductUnit}</td>
                        <td class="spe_line" style="width:150px">{f_Subtotal}</td>
                        <td class="spe_line">
                            <div class="btn2" style="cursor:pointer;width:50px;text-align: center">删除</div>
                        </td>
                    </tr>
                    ]]>
                </item>
            </icms>
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