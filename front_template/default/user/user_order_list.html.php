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


        function FormatOrderState(state,idvalue){
            var result;
            switch(state){
                case "10":
                    result = '<span class="span_state" id="State_'+idvalue+'">未付款</span>';
                    break;
                case "20":
                    result = '<span class="span_state" id="State_'+idvalue+'">已付款</span>';
                    break;
                case "25":
                    result = '<span class="span_state" id="State_'+idvalue+'">已发货</span>';
                    break;
                case "30":
                    result = '<span class="span_state" id="State_'+idvalue+'">交易完成</span>';
                    break;
                case "40":
                    result = '<span class="span_state" id="State_'+idvalue+'" style="color:red">交易关闭</span>';
                    break;
            }
            return result;
        }

        $(function () {

            $(".span_state").each(function(){
                var state = $(this).html();
                var idvalue = $(this).attr("idvalue");
                $(this).html(FormatOrderState(state,idvalue));
            });


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
                case 25:
                    $("#order_state_25").addClass("selected");
                    break;
                case 30:
                    $("#order_state_30").addClass("selected");
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
                </div>
                    <div style="padding:10px 10px;">
                        <div class="order_class">
                            <ul>
                                <li><a id="order_state_-1" href="/default.php?mod=user_order&a=list&state=-1">所有订单</a></li>
                                <li>
                                    <a id="order_state_10" href="/default.php?mod=user_order&a=list&state=10">未付款<span>{UserOrderCountOfNonPayment}</span></a>
                                </li>
                                <li><a id="order_state_20" href="/default.php?mod=user_order&a=list&state=20">已付款<span>{UserOrderCountOfPayment}</span></a></li>
                                <li><a id="order_state_25" href="/default.php?mod=user_order&a=list&state=25">已发货<span>{UserOrderCountOfSent}</span></a></li>
                                <li><a id="order_state_30" href="/default.php?mod=user_order&a=list&state=30">交易完成<span>{UserOrderCountOfDone}</span></a></li>
                                <li><a id="order_state_70" href="/default.php?mod=user_order&a=list&state=70">未评价<span>{UserOrderCountOfUnComment}</span></a></li>
                            </ul>
                            <div class="clean"></div>
                        </div>
                        <div class="order_form">
                            <table class="title" width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="order_number">订单号</td>
                                    <td class="all_price" style="text-align:center;">原价（元）</td>
                                    <td class="send_price" style="text-align:center;">运费（元）</td>
                                    <td class="create_date">下单日期</td>
                                    <td class="state">状态</td>
                                    <td class="option">交易操作</td>
                                </tr>
                            </table>
                            <icms id="user_order_list">
                                <item><![CDATA[
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td class="order_number"><a href="/default.php?mod=user_order&a=detail&user_order_id={f_UserOrderId}" target="_blank">{f_UserOrderNumber}</a></td>
                                            <td class="all_price" style="text-align:center;">{f_AllPrice}</td>
                                            <td class="send_price" style="text-align:center;">{f_SendPrice}</td>
                                            <td class="create_date">{f_CreateDate}</td>
                                            <td class="state"><span class="span_state" idvalue="{f_UserOrderId}">{f_State}</span></td>
                                            <td class="option"><a class="ckxq" href="/default.php?mod=user_order&a=detail&user_order_id={f_UserOrderId}" target="_blank">订单详情</a></td>
                                        </tr>
                                    </table>
                                    ]]></item>
                            </icms>

                        </div>

                        <div class="flips">
                            {pagerButton}

                        </div>
                        <div class="clean"></div>
                    </div>
            </td>
        </tr>
    </table>
</div>
<pre_temp id="8"></pre_temp>
</body>
</html>
