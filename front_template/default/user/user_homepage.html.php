<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>会员中心</title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <link href="/images/common_css.css" rel="stylesheet" type="text/css"/>
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

            $(".producttab2 .title").mouseover(function(){
                var idvalue=$(this).attr("idvalue");
                $(".tabCon .content").css("display","none");
                $(".tabCon #div_"+idvalue).css("display","block");
            });

            var userAvatar = $("#user_avatar").attr("src");
            if(userAvatar == ""){
                $("#user_avatar").attr("src","{cfg_UserDefaultMaleAvatar_5_upload_file_path}");
            }


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
                    <div class="rightbar2"><a href="/">星滋味首页</a> >会员中心</div>
                </div>
                <div class="rightmember">
                    <div style="padding:20px; background:#f6f6f6;">
                    <div class="leftperson"><img src="{UploadFilePath}" id="user_avatar" width="120" height="120"/>
                        <h3><a href="/default.php?mod=user_info&a=modify_avatar">修改头像</a></h3>
                    </div>
                    <div class="rightinfo">
                        <b>您好！ {UserAccount}</b><br/>
                        <div class="change_info"><a href="/default.php?mod=user_info&a=modify">编辑个人资料</a></div>
                        <div style="display:none"><span><strong>会员级别：</strong>{UserGroupName}</span><span><strong>我的积分：</strong>{UserScore} 分</span></div>
                        <ul>
                            <!--<li>待处理订单（1）</li>-->
                            <li><a href="/default.php?mod=user_order&a=list&state=20" target="_blank">待发货订单（<span>{UserOrderOfPayment}</span>）</a>
                            </li>
                            <li><a href="/default.php?mod=user_order&a=list&state=0" target="_blank">待支付订单（<span>{UserOrderOfNewCount}</span>)</a>
                            </li>
                        </ul>
                    </div>
                    <div class="clean"></div>
                </div>
                </div>
                <div class="rightmember">
                    <div id="tab">
                        <div class="tabList">
                            <ul class="producttab2">
                                <li class="title" idvalue="zjsc"><a>最近收藏</a></li>
                                <li class="title" idvalue="zjll"><a>最近浏览</a></li>
                                <li class="title" idvalue="bpqg"><a>爆品抢购</a></li>
                                <li class="title" idvalue="xpsd"><a>新品速递</a></li>
                                <li class="title" idvalue="hpdp"><a>好评单品</a></li>
                            </ul>
                        </div>
                        <div class="tabCon">
                            <div  id="div_zjsc" class="content">
                                <icms id="recent_user_favorite_list" type="list">
                                    <item><![CDATA[
                                        <li>
                                            <div><a href="{f_UserFavoriteUrl}" target="_blank"><img src="{f_UploadFilePath}" width="194" height="194" /></a></div>
                                            <div class="name"><a href="{f_UserFavoriteUrl}" target="_blank">{f_UserFavoriteTitle}</a></div>
                                        </li>
                                        ]]>
                                    </item>
                                </icms>
                            </div>
                            <div id="div_zjll" class="content" style="display:none">
                                <icms id="user_explore_1" type="user_explore_list" top="4">
                                    <item>
                                        <![CDATA[
                                        <li>
                                            <div><a href="{f_Url}" target="_blank"><img src="{f_TitlePic}" width="194" height="194" /></a></div>
                                            <div class="name"><a href="{f_Url}" target="_blank">{f_Title}</a></div>
                                            <div class="price"><a href="{f_Url}" target="_blank">￥{f_Price}</a></div>
                                        </li>
                                         ]]>
                                    </item>
                                </icms>
                            </div>
                            <div id="div_bpqg" class="content" style="display:none">
                                <icms id="product_1" type="product_list" where="RecLevel" where_value="2" top="4">
                                    <item>
                                        <![CDATA[
                                        <li>
                                            <div><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><img src="{f_UploadFileThumbPath2}" width="194" height="194" /></a></div>
                                            <div class="name"><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank">{f_ProductName}</a></div>
                                            <div class="price"><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><span class="right old_price">￥{f_MarketPrice}</span>￥{f_SalePrice}</a></div>
                                        </li>
                                        ]]>
                                    </item>
                                </icms>
                            </div>
                            <div id="div_xpsd" class="content" style="display:none">
                                <icms id="product_1" type="product_list" where="RecLevel" where_value="3" top="4">
                                    <item>
                                        <![CDATA[
                                        <li>
                                            <div><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><img src="{f_UploadFileThumbPath2}" width="194" height="194" /></a></div>
                                            <div class="name"><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank">{f_ProductName}</a></div>
                                            <div class="price"><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><span class="right old_price">￥{f_MarketPrice}</span>￥{f_SalePrice}</a></div>
                                        </li>
                                        ]]>
                                    </item>
                                </icms>
                            </div>
                            <div id="div_hpdp" class="content" style="display:none">
                                <icms id="product_1" type="product_list" where="RecLevel" where_value="4" top="4">
                                    <item>
                                        <![CDATA[
                                        <li>
                                            <div><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><img src="{f_UploadFileThumbPath2}" width="194" height="194" /></a></div>
                                            <div class="name"><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank">{f_ProductName}</a></div>
                                            <div class="price"><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><span class="right old_price">￥{f_MarketPrice}</span>￥{f_SalePrice}</a></div>
                                        </li>
                                        ]]>
                                    </item>
                                </icms>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>
<pre_temp id="8"></pre_temp>
</body>
</html>
