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
                    <div class="leftperson"><img src="images/pic_person.png" width="120" height="120"/>
                        <h3><a href="/default.php?mod=user_info&a=modify_avatar">修改头像</a></h3>
                    </div>
                    <div class="rightinfo">
                        <b>您好！ {UserAccount}</b><br/>
                        <div class="change_info"><a href="/default.php?mod=user_info&a=modify">编辑个人资料</a></div>
                        <span><strong>会员级别：</strong>{UserGroupName}</span><span><strong>我的积分：</strong>{UserScore} 分</span>
                        <ul>
                            <!--<li>待处理订单（1）</li>-->
                            <li><a href="/default.php?mod=user_order&a=list&state=0" target="_blank">待评价订单（<span>{UserOrderOfUncommentCount}</span>）</a>
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
                            <ul>
                                <li class="cur">最近收藏</li>
                                <li>最近浏览</li>
                                <li>爆品抢购</li>
                                <li>新品速递</li>
                                <li>好评单品</li>
                            </ul>
                        </div>
                        <div class="tabCon">
                            <div class="cur">
                                <icms id="recent_user_favorite_list" type="list">
                                    <item><![CDATA[
                                        <dl>
                                            <dd><a href="{f_UserFavoriteUrl}" target="_blank"><img
                                                        src="{f_UploadFilePath}" width="180" height="160"/></a></dd>
                                            <dt><a href="{f_UserFavoriteUrl}" target="_blank">{f_UserFavoriteTitle}</a>
                                            </dt>
                                        </dl>
                                        ]]>
                                    </item>
                                </icms>
                            </div>
                            <div style="display:none;">
                                <dl>
                                    <dd><img src="/images/pic_prduct.png" width="180" height="160"/></dd>
                                    <dt>妈咪宝贝满299减30送豪礼2</dt>
                                    <h3>￥25.90</h3>
                                </dl>
                                <dl>
                                    <dd><img src="/images/pic_prduct.png" width="180" height="160"/></dd>
                                    <dt>妈咪宝贝满299减30送豪礼2</dt>
                                    <h3>￥25.90</h3>
                                </dl>
                                <dl>
                                    <dd><img src="/images/pic_prduct.png" width="180" height="160"/></dd>
                                    <dt>妈咪宝贝满299减30送豪礼2</dt>
                                    <h3>￥25.90</h3>
                                </dl>
                                <dl>
                                    <dd><img src="/images/pic_prduct.png" width="180" height="160"/></dd>
                                    <dt>妈咪宝贝满299减30送豪礼2</dt>
                                    <h3>￥25.90</h3>
                                </dl>
                            </div>
                            <div style="display:none;">
                                <dl>
                                    <dd><img src="/images/pic_prduct.png" width="180" height="160"/></dd>
                                    <dt>妈咪宝贝满299减30送豪礼3</dt>
                                    <h3>￥25.90</h3>
                                </dl>
                                <dl>
                                    <dd><img src="/images/pic_prduct.png" width="180" height="160"/></dd>
                                    <dt>妈咪宝贝满299减30送豪礼3</dt>
                                    <h3>￥25.90</h3>
                                </dl>
                                <dl>
                                    <dd><img src="/images/pic_prduct.png" width="180" height="160"/></dd>
                                    <dt>妈咪宝贝满299减30送豪礼3</dt>
                                    <h3>￥25.90</h3>
                                </dl>
                                <dl>
                                    <dd><img src="/images/pic_prduct.png" width="180" height="160"/></dd>
                                    <dt>妈咪宝贝满299减30送豪礼3</dt>
                                    <h3>￥25.90</h3>
                                </dl>
                            </div>
                            <div style="display:none;">
                                <dl>
                                    <dd><img src="/images/pic_prduct.png" width="180" height="160"/></dd>
                                    <dt>妈咪宝贝满299减30送豪礼4</dt>
                                    <h3>￥25.90</h3>
                                </dl>
                                <dl>
                                    <dd><img src="/images/pic_prduct.png" width="180" height="160"/></dd>
                                    <dt>妈咪宝贝满299减30送豪礼4</dt>
                                    <h3>￥25.90</h3>
                                </dl>
                                <dl>
                                    <dd><img src="/images/pic_prduct.png" width="180" height="160"/></dd>
                                    <dt>妈咪宝贝满299减30送豪礼4</dt>
                                    <h3>￥25.90</h3>
                                </dl>
                                <dl>
                                    <dd><img src="/images/pic_prduct.png" width="180" height="160"/></dd>
                                    <dt>妈咪宝贝满299减30送豪礼4</dt>
                                    <h3>￥25.90</h3>
                                </dl>
                            </div>
                            <div style="display:none;">
                                <dl>
                                    <dd><img src="/images/pic_prduct.png" width="180" height="160"/></dd>
                                    <dt>妈咪宝贝满299减30送豪礼5</dt>
                                    <h3>￥25.90</h3>
                                </dl>
                                <dl>
                                    <dd><img src="/images/pic_prduct.png" width="180" height="160"/></dd>
                                    <dt>妈咪宝贝满299减30送豪礼5</dt>
                                    <h3>￥25.90</h3>
                                </dl>
                                <dl>
                                    <dd><img src="/images/pic_prduct.png" width="180" height="160"/></dd>
                                    <dt>妈咪宝贝满299减30送豪礼5</dt>
                                    <h3>￥25.90</h3>
                                </dl>
                                <dl>
                                    <dd><img src="/images/pic_prduct.png" width="180" height="160"/></dd>
                                    <dt>妈咪宝贝满299减30送豪礼5</dt>
                                    <h3>￥25.90</h3>
                                </dl>
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
