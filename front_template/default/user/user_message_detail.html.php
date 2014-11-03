<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>会员中心</title>
    <link href="/images/common_css.css" rel="stylesheet" type="text/css"/>
    <style type="text/css">
        .right {
            cursor: pointer
        }
    </style>
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/front_js/common.js"></script>
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript" src="/front_js/user/user_car.js"></script>

    <script type="text/javascript">

        var siteId = parseInt("{SiteId}");


        $(function () {


            $(".right").click(function () {
                var idvalue = $(this).attr("idvalue");
                var state = $("#" + idvalue + "_child").css("display");
                if (state == "none") {
                    $(".right_child").css("display", "none");
                    $(".right_img").attr("src", "/images/icon_jia.png");
                    $("#" + idvalue + "_img").attr("src", "/images/icon_jian.png");
                    $("#" + idvalue + "_child").css("display", "inline");
                } else {
                    $("#" + idvalue + "_img").attr("src", "/images/icon_jia.png");
                    $("#" + idvalue + "_child").css("display", "none");
                }
            });
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
                <div class="leftsidebar">
                    <div class="leftsidebar1">
                        <div class="icon"><img class="right_img" id="account_manage_img" src="/images/icon_jia.png"
                                               width="11" height="11"/></div>
                        <div class="right" alt="close" idvalue="account_manage">账户管理</div>
                    </div>
                    <div class="right_child" id="account_manage_child" style="display: none">
                        <div class="leftsidebar2"><a href="">交易条款</a></div>
                        <div class="leftsidebar2"><a href="">购物流程</a></div>
                        <div class="leftsidebar2"><a href="">发票制度</a></div>
                    </div>
                    <div class="leftsidebar1">
                        <div class="icon"><img class="right_img" id="shopping_guide_img" src="/images/icon_jia.png"
                                               width="11" height="11"/></div>
                        <div class="right" idvalue="shopping_guide">购物指南</div>
                    </div>
                    <div class="right_child" id="shopping_guide_child" style="display: none">
                        <div class="leftsidebar2"><a href="">交易条款</a></div>
                        <div class="leftsidebar2"><a href="">购物流程</a></div>
                        <div class="leftsidebar2"><a href="">发票制度</a></div>
                        <div class="leftsidebar2"><a href="">会员等级</a></div>
                        <div class="leftsidebar2"><a href="">积分制度</a></div>
                    </div>
                    <div class="leftsidebar1">
                        <div class="icon"><img class="right_img" id="send_service_img" src="/images/icon_jia.png"
                                               width="11" height="11"/></div>
                        <div class="right" idvalue="send_service">配送服务</div>
                    </div>
                    <div class="leftsidebar1">
                        <div class="icon"><img class="right_img" id="pay_way_img" src="/images/icon_jia.png" width="11"
                                               height="11"/></div>
                        <div class="right" idvalue="pay_way">支付方式</div>
                    </div>
                    <div class="leftsidebar1">
                        <div class="icon"><img class="right_img" id="after_service_img" src="/images/icon_jia.png"
                                               width="11" height="11"/></div>
                        <div class="right" idvalue="after_service">售后服务</div>
                    </div>
                </div>
            </td>
            <td width="1" bgcolor="#D4D4D4"></td>
            <td width="1006" valign="top">
                <div class="rightbar">
                    <div class="rightbar2"><a href="">星滋味首页</a> >会员中心</div>
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
