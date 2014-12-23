<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>会员中心</title>
    <link href="/images/common_css.css" rel="stylesheet" type="text/css"/>
    <link href="/images/user_layout.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .right {
            cursor: pointer
        }
    </style>
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/front_js/common.js"></script>
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript" src="/front_js/user/user_order.js"></script>

    <script type="text/javascript">

        var siteId = parseInt("{SiteId}");


        $(function () {

            var AllSaleProductPrice = 0;
            $(".UserOrderSubtotal").each(function(){
                var ProductPrice = parseFloat($(this).html());
                AllSaleProductPrice = ProductPrice+AllSaleProductPrice;
            });
            $("#AllSaleProductPrice").html(formatPrice(AllSaleProductPrice));

            var userOrderState = parseInt("{State}");
            if(userOrderState != 30){
                $(".cant_comment").css("display","block");
            }else{
                $(".is_comment").each(function(){
                    var isComment = parseInt($(this).html());
                    var idvalue = $(this).attr("idvalue");
                    if(isComment == 0){
                        $("#non_comment_"+idvalue).css("display","block");
                    }else{
                        $("#commented_"+idvalue).css("display","block");
                    }
                });
            }

            var spanState = $("#span_state");
            var userOrderId = spanState.attr("idvalue");
            var state = spanState.attr("title");
            spanState.html(FormatOrderState(state, userOrderId));

        });
    </script>
</head>

<body>

<pre_temp id="4"></pre_temp>
<div class="clean"></div>
<pre_temp id="12"></pre_temp>
<div class="wrapper">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width="193" valign="top" height="750">
                <pre_temp id="6"></pre_temp>
            </td>
            <td width="1" bgcolor="#D4D4D4"></td>
            <td width="1006" valign="top">
                <div class="rightbar">
                    <div class="rightbar2"><a href="">星滋味首页</a> > <a href="/default.php?mod=user&a=homepage">会员中心</a> > <a href="/default.php?mod=user_order&a=list">我的订单</a>>{UserOrderNumber}</div>
                    <div class="order_detail" style="padding:20px 50px;">
                        <div class="title">基本信息</div>
                        <div style="padding:0 20px;">
                            <table  width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="spe_line infor_title">订单编号：</td>
                                    <td class="spe_line">{UserOrderNumber}</td>
                                    <td class="spe_line infor_title">订单名：</td>
                                    <td class="spe_line">
                                        <input type="text" class="input_box" name="f_UserOrderName" value="{UserOrderName}"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="spe_line infor_title">收货信息：</td>
                                    <td class="spe_line" colspan="3">
                                        <span style="padding-right:5px;">{ReceivePersonName}</span>,<span style="padding:0 5px;">{Address}</span>,<span style="padding:0 5px;">{Mobile}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="spe_line infor_title">发货费用：</td>
                                    <td class="spe_line" >￥<input type="text" class="input_price" id="SendPrice" name="f_SendPrice" value="{SendPrice}"/></td>
                                    <td class="spe_line infor_title">发货信息：</td>
                                    <td class="spe_line">
                                        <a href="/default.php?mod=user_order_send&a=list&user_order_id={UserOrderId}&user_order_number={UserOrderNumber}" target="_blank">点击查看</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="spe_line infor_title">订单总价：</td>
                                    <td class="spe_line">
                                        ￥<span id="display_AllPrice" class="show_price">{AllPrice}</span>
                                    </td>
                                    <td class="spe_line infor_title">订单状态：</td>
                                    <td class="spe_line">
                                        <span id="span_state" idvalue="{UserOrderId}" title="{State}" style="color:#ff3d00;"></span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div style="height:30px"></div>
                        <div class="title">商品信息</div>
                        <table class="grid product_list" width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                            <tr class="grid_title2 list_title">
                                <td colspan="2" class="spe_line product_name" style="text-align: center">商品名</td>
                                <td class="spe_line product_fullprice">商品单价</td>
                                <td class="spe_line product_cutprice">购买价</td>
                                <td class="spe_line product_num">数量</td>
                                <td class="spe_line product_unit">单位</td>
                                <td class="spe_line product_finalprice">小计</td>
                                <td class="spe_line product_operation">操作</td>
                            </tr>
                            <icms id="user_order_product_list">
                                <item>
                                    <![CDATA[
                                    <tr class="grid_item2" id="user_order_product_{f_UserOrderProductId}">
                                        <td colspan="2" class="spe_line product_name">{f_ProductName}</td>
                                        <td class="spe_line product_fullprice">
                                            ￥<span class="show_price">{f_ProductPrice}</span>
                                        </td>
                                        <td class="spe_line product_cutprice">
                                            ￥<span class="show_price">{f_SalePrice}</span>
                                        </td>
                                        <td class="spe_line product_num">
                                            {f_SaleCount}
                                        </td>
                                        <td class="spe_line product_unit">
                                            {f_ProductUnit}
                                        </td>
                                        <td class="spe_line product_finalprice">
                                            ￥<span class="UserOrderSubtotal show_price">{f_Subtotal}</span>
                                        </td>
                                        <td class="spe_line product_operation">
                                            <div idvalue="{f_UserOrderProductId}" style="display:none" class="is_comment">{f_CommentCount}</div>
                                            <div id="non_comment_{f_UserOrderProductId}" class="non_comment" style="display:none"><a href="/default.php?mod=product_comment&a=create&product_id={f_ProductId}&user_order_id={f_UserOrderId}&user_order_product_id={f_UserOrderProductId}">评价商品</a></div>
                                            <div id="commented_{f_UserOrderProductId}" class="commented" style="display:none">已评价</div>
                                            <div id="cant_comment_{f_UserOrderProductId}" class="cant_comment" style="display:none">交易完成才能评论</div>
                                        </td>
                                    </tr>
                                    ]]>
                                </item>
                            </icms>
                            <tr class="grid_item2 bottom_price">
                                <td class="spe_line" colspan="6">总计：</td>
                                <td class="spe_line product_finalprice">
                                    ￥<span class="show_price" id="AllSaleProductPrice"></span>
                                </td>
                                <td class="spe_line product_finalprice">&nbsp;</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>
<pre_temp id="8"></pre_temp>
</body>
</html>
