<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/user/user_order.js"></script>
    <style type="text/css">
        .order{margin-bottom: 5px}
        .order .title{font-size: 16px;color:#000000}
        .product_pic{width:110px;height:110px;float:left;margin-left: 15px;background-size:cover}
        .product_name{text-align:center;line-height: 110px;font-size: 16px;background-color:#ccc;width:780px;height:110px;float:left;margin-left: 5px}
        .product_price{text-align:center;line-height: 110px;font-size: 16px;background-color:#ccc;width:130px;height:110px;float:left;margin-left: 5px}
        .product_count{text-align:center;line-height: 110px;font-size: 16px;background-color:#ccc;width:80px;height:110px;float:left;margin-left: 5px}
        .freight_price{text-align:center;line-height: 110px;font-size: 16px;background-color:#ccc;width:130px;height:110px;float:left;margin-left: 5px}
        .order_price{text-align:center;line-height: 110px;font-size: 16px;background-color:#ccc;width:130px;height:110px;float:left;margin-left: 5px}
        .order_state{text-align:center;line-height: 110px;font-size: 16px;background-color:#ccc;width:130px;height:110px;float:left;margin-left: 5px}
        .order_operate{background-color:#ccc;width:130px;height:110px;float:left;margin-left: 5px}
    </style>
</head>
<body>
    <div class="div_list">
        <div class="search" style="width:99%;height:30px"></div>
        <div class="advance_search" style="width:99%;height:150px;border:1px #CCC solid;display: none">

        </div>
        <table class="grid" width="100%" cellpadding="0" cellspacing="0">
            <tr  class="grid_title2">
                <td style="width:40px;text-align: center">ID</td>
                <td style="width:50px;text-align: center">编辑</td>
                <td style="text-align: center">订单编号</td>
                <td style="width:100px;text-align: center">会员名</td>
                <td style="width:80px;text-align: center">订单总价</td>
                <td style="width:80px;text-align: center">运费</td>
                <td  style="width:80px;text-align: center">状态</td>
            </tr>
        </table>
        <ul id="type_list">
        <icms id="user_order_list">
            <item>
                <![CDATA[
                <li>
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item2">
                            <td class="spe_line2" style="width:40px;text-align: center">{f_UserOrderId}</td>
                            <td class="spe_line2" style="width:50px;text-align: center">
                                <img src="/system_template/{template_name}/images/manage/edit.gif" style="cursor:pointer" class="edit" idvalue="{f_UserOrderId}"/>
                            </td>
                            <td class="spe_line2" style="text-align: center">
                                {f_UserOrderNumber}
                            </td>
                            <td class="spe_line2" style="width:100px;text-align: center">
                                <span>{f_UserName}</span>
                            </td>
                            <td class="spe_line2" style="width:80px;text-align: center">
                                <span>{f_AllPrice}</span>
                            </td>
                            <td class="spe_line2" style="width:80px;text-align: center">
                                <span>{f_SendPrice}</span>
                            </td>
                            <td class="spe_line2" style="width:80px;text-align: center">
                                <span class="span_state" idvalue="{f_UserOrderId}">{f_State}</span>
                            </td>
                        </tr>
                    </table>
                </li>
                ]]>
            </item>
        </icms>
        </ul>
        <!--
        <div class="order" style="width:99%;height:160px;">
            <div class="title" style="width:100%;height:30px;background-color: #3165CE;padding-top: 5px">
                <input type="checkbox" style="margin-left: 5px;float:left"/>
                <div style="margin-left: 15px;float:left">2014-02-11 11:22:33</div>
                <div style="margin-left: 15px;float:left">订单号：</div>
                <div style="float:left">5484131481000054</div>
            </div>
            <div class="detail" style="width:100%;height:120px;padding-top: 5px">
                <div class="product_pic" style="background-image: url('/upload/Desert.jpg');filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='/upload/Desert.jpg',sizingMethod='scale');">
                    <img width="110" height="110" src="/upload/background.png"/>
                </div>
                <div class="product_name">
                    沙漠西瓜
                </div>
                <div class="product_price">
                    产品价格
                </div>
                <div class="product_count">
                    产品数量
                </div>
                <div class="freight_price">
                    运费
                </div>
                <div class="order_price">
                    订单总价
                </div>
                <div class="order_state">
                    交易状态
                </div>
                <div class="order_operate">
                    交易操作
                </div>
                <div style="clear:left"></div>
            </div>
        </div>-->
    </div>
</body>
</html>