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

    <script type="text/javascript">

        var siteId = parseInt("{SiteId}");


        $(function () {

            var state = parseInt(Request["state"]);
            switch(state){
                case -1:
                    $("#order_state_-1").addClass("selected");
                    break;
                case 10:
                    $("#order_state_10").addClass("selected");
                    break;
                case 20:
                    $("#order_state_20").addClass("selected");
                    break;
                case 70:
                    $("#order_state_70").addClass("selected");
                    break;
            }

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
                    <div class="rightbar2"><a href="/">星滋味首页</a> ><a href="/default.php?mod=user&a=homepage">会员中心</a>>我的订单</div>
                    <div style="padding:20px 50px;">
                        <div class="order_class">
                            <ul>
                                <li><a id="order_state_-1" href="/default.php?mod=user_order&a=list&state=-1">所有订单</a></li>
                                <li>
                                    <a id="order_state_10" href="/default.php?mod=user_order&a=list&state=10">待付款<span>{UserOrderCountOfNonPayment}</span></a>
                                </li>
                                <li><a id="order_state_20" href="/default.php?mod=user_order&a=list&state=20">待发货<span>{UserOrderCountOfPayment}</span></a></li>
                                <li><a id="order_state_70" href="/default.php?mod=user_order&a=list&state=70">待评价<span>{UserOrderCountOfUnComment}</span></a></li>
                            </ul>
                            <div class="clean"></div>
                        </div>
                        <div class="order_form">
                            <table class="title" width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="order_number">订单号</td>
                                    <td class="all_price">原价</td>
                                    <td class="send_price">优惠价</td>
                                    <td class="create_date">下单日期</td>
                                    <td class="state">数量</td>
                                    <td class="option">交易操作</td>
                                </tr>
                            </table>
                            <icms id="user_order_list">
                                <item><![CDATA[
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td class="order_number"><a href="/default.php?mod=user_order&a=detail&user_order_id={f_UserOrderId}" target="_blank">{f_UserOrderNumber}</a></td>
                                            <td class="all_price">{f_AllPrice}</td>
                                            <td class="send_price">{f_SendPrice}</td>
                                            <td class="create_date">{f_CreateDate}</td>
                                            <td class="state">{f_State}</td>
                                            <td class="option"><a class="ckxq" href="/default.php?mod=user_order&a=detail&user_order_id={f_UserOrderId}" target="_blank">订单详情</a></td>
                                        </tr>
                                    </table>
                                    ]]></item>
                            </icms>

                        </div>
                        {pagerButton}
                        <div class="flips">
                            <ul>
                                <li><a href="#">首页</a></li>
                                <li><a href="#">1</a></li>
                                <li><a class="recent" href="#">2</a></li>
                                <li><a href="#">...</a></li>
                                <li><a href="#">5</a></li>
                                <li><a href="#">共5页</a></li>
                                <li><a href="#">5</a></li>
                                <li><a href="#">5</a></li>
                            </ul>
                            <div class="clean"></div>
                        </div>
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
