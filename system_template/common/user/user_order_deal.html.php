<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/user/user_order.js"></script>
    <style type="text/css">
        .order{margin-bottom: 5px}
        .order .top{font-size: 16px;color:#000000}
        .order .bottom{font-size: 16px;color:#000000}
        .order .title{font-size: 16px;}
        .product_pic{width:110px;height:110px;float:left;margin-left: 15px;background-size:cover}
        .product_name{text-align:center;line-height: 110px;font-size: 16px;background-color:#ccc;width:780px;height:110px;float:left;margin-left: 5px}
        .product_price{text-align:center;line-height: 110px;font-size: 16px;background-color:#ccc;width:130px;height:110px;float:left;margin-left: 5px}
        .sale_price{text-align:center;line-height: 110px;font-size: 16px;background-color:#ccc;width:130px;height:110px;float:left;margin-left: 5px}
        .sale_count{text-align:center;line-height: 110px;font-size: 16px;background-color:#ccc;width:80px;height:110px;float:left;margin-left: 5px}
        .subtotal{text-align:center;line-height: 110px;font-size: 16px;background-color:#ccc;width:130px;height:110px;float:left;margin-left: 5px}
        .product_count{text-align:center;line-height: 110px;font-size: 16px;background-color:#ccc;width:130px;height:110px;float:left;margin-left: 5px}
        .order_operate{background-color:#ccc;width:160px;height:110px;float:left;margin-left: 5px}
        .one_product{margin-top: 5px}
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
    <form id="mainForm" action="/default.php?secu=manage&mod=user_group&m={method}&user_group_id={UserGroupId}&site_id={SiteId}" method="post">
        <div class="order" style="width:99%;height:auto;">
            <div class="top" style="width:100%;height:30px;background-color: #3165CE;padding-top: 5px">
                <div style="margin-left: 15px;float:left">{CreateDate}</div>
                <div style="margin-left: 15px;float:left">订单号：</div>
                <div style="float:left">{UserOrderNumber}</div>
                <div style="clear:left"></div>
            </div>

            <div class="detail" style="width:100%;margin-top: 5px">
                <div class="title" style="width: 100%;height:22px">
                    <div style="width:110px;float:left;background-color:#ccc;margin-left: 15px;text-align: center">产品图片</div>
                    <div style="width:780px;float:left;background-color:#ccc;margin-left: 5px;text-align: center">产品简介</div>
                    <div style="width:130px;float:left;background-color:#ccc;margin-left: 5px;text-align: center">产品单价</div>
                    <div style="width:130px;float:left;background-color:#ccc;margin-left: 5px;text-align: center">折后价</div>
                    <div style="width:80px;float:left;background-color:#ccc;margin-left: 5px;text-align: center">购买数量</div>
                    <div style="width:130px;float:left;background-color:#ccc;margin-left: 5px;text-align: center">小计</div>
                    <div style="width:130px;float:left;background-color:#ccc;margin-left: 5px;text-align: center">库存</div>
                    <div style="width:160px;float:left;background-color:#ccc;margin-left: 5px;text-align: center">交易操作</div>
                    <div style="clear:left"></div>
                </div>
                <icms id="user_order_product_list">
                    <item><![CDATA[
                        <div class="one_product">
                            <div class="product_pic" style="background-image: url('/upload/Desert.jpg');filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='/upload/Desert.jpg',sizingMethod='scale');">
                                <img width="110" height="110" src="/upload/background.png"/>
                            </div>
                            <div class="product_name">
                                {f_ProductName}
                            </div>
                            <div class="product_price">
                                {f_ProductPrice}/{f_ProductUnit}
                            </div>
                            <div class="sale_price">
                                {f_SalePrice}/{f_ProductUnit}
                            </div>
                            <div class="sale_count">
                                {f_SaleCount}
                            </div>
                            <div class="subtotal">
                                {f_Subtotal}
                            </div>
                            <div class="product_count">
                                {f_ProductCount}{f_ProductUnit}
                            </div>
                            <div class="order_operate">

                            </div>
                            <div style="clear:left"></div>
                        </div>
                        ]]></item>
                </icms>
            </div>
            <div class="bottom" style="width:100%;background-color: #3165CE;margin-top: 5px">
                <div style="margin-left: 15px;">运费：{SendPrice}</div>
                <div style="margin-left: 15px;">总计：{AllPrice}</div>
                <div style="margin-left: 15px;">送货地址：{Address}</div>
                <div style="margin-left: 15px;">收货人：{ReceivePersonName}</div>
            </div>
        </div>
        <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
        <input name="PageIndex" type="hidden" value="{PageIndex}"/>
        <input name="PageSize" type="hidden" value="{PageSize}"/>
        <input name="TabIndex" type="hidden" value="{TabIndex}"/>
    </form>
    <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td height="60" align="center">
                <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/> <input class="btn" value="确认并继续"
                                                                                                type="button"
                                                                                                onclick="submitForm(1)"/>
                <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
            </td>
        </tr>
    </table>
</div>
</body>
</html>