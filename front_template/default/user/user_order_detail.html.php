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
    <script type="text/javascript" src="/front_js/user/user_car.js"></script>

    <script type="text/javascript">

        var siteId = parseInt("{SiteId}");


        $(function () {


//            $(".right").click(function () {
//                var idvalue = $(this).attr("idvalue");
//                var state = $("#" + idvalue + "_child").css("display");
//                if (state == "none") {
//                    $(".right_child").css("display", "none");
//                    $(".right_img").attr("src", "/images/icon_jia.png");
//                    $("#" + idvalue + "_img").attr("src", "/images/icon_jian.png");
//                    $("#" + idvalue + "_child").css("display", "inline");
//                } else {
//                    $("#" + idvalue + "_img").attr("src", "/images/icon_jia.png");
//                    $("#" + idvalue + "_child").css("display", "none");
//                }
//            });

            var AllSaleProductPrice = 0;
            $(".UserOrderSubtotal").each(function(){
                var ProductPrice = parseFloat($(this).html());
                AllSaleProductPrice = ProductPrice+AllSaleProductPrice;
            });
            $("#AllSaleProductPrice").html(formatPrice(AllSaleProductPrice));
        });
    </script>
</head>

<body>

<div class="wrapper2">
    <div class="logo"><a href=""><img src="/images/mylogo.png" width="320" height="103"/></a></div>
    <div class="search">
        <div class="search_green"><input name="" type="text" class="text"/></div>
        <div class="searchbtn"><img src="/images/search.png" width="46" height="28"/></div>
        <div class="searchbottom">平谷大桃 哈密瓜 新鲜葡萄 红炉磨坊 太湖鲜鱼</div>
    </div>
    <div class="service">
        <div class="hottel"><span><a href="" target="_blank">热线96333</a></span></div>
        <div class="online"><span><a href="" target="_blank">在线客服</a></span></div>
        <div class="shopping"><a href="/default.php?mod=user_car&a=list"><span>购物车</span></a></div>
        <div class="number" id="user_car_count">0</div>
    </div>
</div>
<div class="clean"></div>
<div class="mainbav">
    <div class="wrapper">
        <div class="goods" id="leftmenu">
            <ul>
                <li><span>会员中心</span></li>
            </ul>
        </div>
        <div class="column1"><a href="">首页</a></div>
        <div class="column2"><a href="">超市量贩</a></div>
        <div class="column2"><a href="">团购</a></div>
        <div class="column2"><a href="">最新预售</a></div>
        <div class="new"><img src="/images/icon_new.png" width="29" height="30"/></div>
    </div>
</div>
<div class="wrapper">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width="193" valign="top" height="750">
                <pre_temp id="6"></pre_temp>
            </td>
            <td width="1" bgcolor="#D4D4D4"></td>
            <td width="1006" valign="top">
                <div class="rightbar">
                    <div class="rightbar2"><a href="">星滋味首页</a> > <a href="">会员中心</a> > <a href="/default.php?mod=user_order&a=list">我的订单</a>>{UserOrderNumber}</div>
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
                                    <td class="spe_line infor_title">送货价格：</td>
                                    <td class="spe_line" >￥<input type="text" class="input_price" id="SendPrice" name="f_SendPrice" value="{SendPrice}"/></td>
                                    <td class="spe_line infor_title">送货时间：</td>
                                    <td class="spe_line">{SendTime}</td>
                                </tr>
                                <tr>
                                    <td class="spe_line infor_title">订单总价：</td>
                                    <td class="spe_line">
                                        ￥<span id="display_AllPrice" class="show_price">{AllPrice}</span>
                                    </td>
                                    <td class="spe_line infor_title">订单状态：</td>
                                    <td class="spe_line">
                                        <span style="color:#ff3d00;"><script type="text/javascript">document.write(FormatOrderState("{State}",{UserOrderId}));</script></span>
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
                            </tr>
                            <icms id="user_order_product_list">
                                <item>
                                    <![CDATA[
                            <tr class="grid_item2" id="user_order_product_{f_UserOrderProductId}">
                                <td class="spe_line product_pic"><img width="80px" height="80px" /></td>
                                <td class="spe_line product_name">{f_ProductName}</td>
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
                            </tr>
                                 ]]>
                                </item>
                            </icms>
                            <tr class="grid_item2 bottom_price">
                                <td class="spe_line" colspan="6">总计：</td>
                                <td class="spe_line product_finalprice">
                                    ￥<span class="show_price" id="AllSaleProductPrice"></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>
<div class="footerline"></div>
<div class="wrapper">
    <div class="footerleft">
        <div class="cont">
            <div><img src="/images/footergwzn.png" width="79" height="79"/></div>
            <b>交易条款</b><br/>
            <a href="" target="_blank">购物流程</a><br/>
            <a href="" target="_blank">发票制度</a><br/>
            <a href="" target="_blank">会员等级</a><br/>
            <a href="" target="_blank">积分制度</a><br/><br/>
        </div>
    </div>
    <div class="footerleft">
        <div class="cont">
            <div><img src="/images/footerpsfw.png" width="79" height="79"/></div>
            <b>配送服务</b><br/>
            <a href="" target="_blank">配送说明</a><br/>
            <a href="" target="_blank">配送范围</a><br/>
            <a href="" target="_blank">配送状态查询</a><br/><br/><br/>
        </div>
    </div>
    <div class="footerleft">
        <div class="cont">
            <div><img src="/images/footerzffs.png" width="79" height="79"/></div>
            <b>支付方式</b><br/>
            <a href="" target="_blank">支付宝支付</a><br/>
            <a href="" target="_blank">银联在线支付</a><br/>
            <a href="" target="_blank">货到付款</a><br/><br/><br/>
        </div>
    </div>
    <div class="footerleft">
        <div class="cont">
            <div><img src="/images/footershfw.png" width="79" height="79"/></div>
            <b>售后服务</b><br/>
            <a href="" target="_blank">服务承诺</a><br/>
            <a href="" target="_blank">退换货政策</a><br/>
            <a href="" target="_blank">退换货流程</a><br/><br/><br/>
        </div>
    </div>
    <div class="footerright" style="padding-left:50px;">
        手机客户端下载
        <div><img src="/images/weixin.png" width="104" height="104"/></div>
    </div>
    <div class="footerright" style="padding-right:50px;">
        手机客户端下载
        <div><img src="/images/weixin.png" width="104" height="104"/></div>
    </div>
</div>
</body>
</html>
