<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{BrowserTitle}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <meta name="keywords" content="{BrowserKeywords}" />
    <meta name="description" content="{BrowserDescription}" />
    <link href="/images/common.css" rel="stylesheet" type="text/css" />
    <link href="/images/common_css.css" rel="stylesheet" type="text/css" />
    <link href="/images/index_layout.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/common.js"></script>
    <script type="text/javascript" src="/front_js/bx_slider/jquery.bxslider.min.js"></script>
    <link href="/front_js/bx_slider/jquery.bxslider.css" rel="stylesheet" />
    <script type="text/javascript">
        //倒计时处理代码
        function countDown(time, day_elem, hour_elem, minute_elem, second_elem) {
            //if(typeof end_time == "string")
            var end_time = new Date(time).getTime(),//月份是实际月份-1
            //current_time = new Date().getTime(),
                sys_second = (end_time - new Date().getTime()) / 1000;
            var timer = setInterval(function () {
                if (sys_second > 0) {
                    sys_second -= 1;
                    var day = Math.floor((sys_second / 3600) / 24);
                    var hour = Math.floor((sys_second / 3600) % 24);
                    var minute = Math.floor((sys_second / 60) % 60);
                    var second = Math.floor(sys_second % 60);
                    day_elem && $(day_elem).text(day);//计算天
                    $(hour_elem).text(hour < 10 ? "0" + hour : hour);//计算小时
                    $(minute_elem).text(minute < 10 ? "0" + minute : minute);//计算分
                    $(second_elem).text(second < 10 ? "0" + second : second);// 计算秒
                } else {
                    clearInterval(timer);
                }
            }, 1000);
        }
        $(function () {
            /* 顶部banner类别菜单初始化 */
            //$('#leftmenu>ul>li>ul').find('li:has(ul:not(:empty))>a').append("<span class='arrow'>></span>"); // 为有子菜单的菜单项添加'>'符号
            $('#leftmenu div[class="menu1"]>ul>li').bind('mouseover',function () // 子菜单的鼠标移入操作
            {
                //有下级菜单的才显示
                if($(this).children('div').find('ul>li').length>0)
                $(this).children('div').css('display', '');
            }).bind('mouseleave', function () // 子菜单的鼠标移出操作
                {
                    $(this).children('div').css('display', 'none');
                });
            //爆品抢购、新品速递、好评单品TAB页切换
            $('#sales>div[alt]').bind('mouseover', function () // 子菜单的鼠标移入操作
            {
                $('#sales>div[alt]').attr("class", "title2 left");
                $(this).attr("class", "title2 left recent");
                $(".good").css("display", "none");
                var num = $(this).attr("alt");
                $("#tabdiv" + num).css("display", "block");
            });
            //显示倒计时

            countDown("2014/12/10 22:59:59",null,"#demo02 .hour","#demo02 .minute","#demo02 .second");

            $('.bx_slider').bxSlider({
                auto: true,
                controls: false
            });

        });
    </script>
</head>

<body>


<div style="">

<span id="top"></span>

<div class="head">
    <pre_temp id="3"></pre_temp>
    <pre_temp id="4"></pre_temp>
    <pre_temp id="5"></pre_temp>
</div>

<div class="main">
<div class="first_part">
    <div class="wrapper">
        <div id="leftmenu" style="background:#00a93c;height:429px;">
            <ul>
            <div class="menu1">
                    <ul>
                        <icms id="channel_3" type="channel_list" where="parent">
                            <item>
                                <![CDATA[
                                <li class="first_li"><a href="/default.php?&mod=product&a=list&channel_first_id={f_ChannelId}&channel_id={f_ChannelId}"><img src="{f_icon}" width="37" height="35" />{f_ChannelName}</a>
                                    <div class="menu2" style="display: none;">
                                    <ul>
                                        {child}
                                    </ul>
                                    </div>
                                </li>
                                ]]>
                            </item>
                            <child>
                                <![CDATA[
                                <li><div class="title"><a href="/default.php?&mod=product&a=list&channel_first_id={f_FirstId}&channel_id={f_ChannelId}">{f_ChannelName}</a></div>
                                <div class="dd">{third}</div>
                                </li>
                                ]]>
                            </child>
                            <third>
                                <![CDATA[<a href="/default.php?&mod=product&a=list&channel_first_id={f_FirstId}&channel_id={f_ChannelId}">{f_ChannelName}<span>|</span></a>
                                ]]>
                            </third>

                        </icms>
                    </ul>
            </div>
            </ul>
        </div>
        <div class="middle left">
            <ul class="bx_slider">

            <icms id="channel_134" type="pic_slider_list" top="4" title="24">
                <item>
                    <![CDATA[
                    <li><a href="{f_DirectUrl}" target="_blank"><img src="{f_UploadFilePath}" alt="{f_PicSliderTitle}" width="741" height="429" /></a></li>
                    ]]>
                </item>
            </icms>

            </ul>
        </div>
        <div class="right_bar right">
            <div class="gonggao">
                <div class="title1"><span class="right"><a href="/h/17/list.html" target="_blank">更多</a></span>网站公告 </div>
                <div class="gonggao_list">
                    <ul>
                    <icms id="channel_17" type="document_news_list" top="4" title="24">
                        <item>
                            <![CDATA[
                            <li><a href="{c_DocumentNewsUrl}" style="font-weight:{f_DocumentNewsTitleBold};color:{f_DocumentNewsTitleColor}" target="_blank">{f_DocumentNewsTitle}</a></li>
                            ]]>
                        </item>
                    </icms>
                    </ul>
                </div>
            </div>
            <div class="fuwu">
                <div class="title1"><span class="right"><a href="#">更多</a></span>便民服务</div>
                <div class="fuwu_tags">
                    <div class="fuwu_class gray"><a href="/h/19/20141114/25.html" class="jiazhen" target="_blank">家政服务</a></div>
                    <div class="fuwu_class"><a href="/h/20/20141120/27.html" class="weixiu" target="_blank">维修清洗</a></div>
                    <div class="fuwu_class gray"><a href="/h/21/20141120/29.html" class="fanxin" target="_blank">翻新保养</a></div>
                    <div class="fuwu_class"><a href="/h/22/20141121/38.html" class="jiaju" target="_blank">家居安全</a></div>
                    <div class="fuwu_class"><a href="/h/23/20141121/30.html" class="jiaotong" target="_blank">交通旅游</a></div>
                    <div class="fuwu_class gray"><a href="/h/24/20141121/31.html" class="jiaoyu" target="_blank">教育培训</a></div>
                    <div class="fuwu_class"><a href="/h/25/20141121/10.html" class="jinrong" target="_blank">金融服务</a></div>
                    <div class="fuwu_class gray"><a href="/h/26/20141208/11.html" class="qita" target="_blank">其他服务</a></div>
                </div>
            </div>
        </div>
        <div class="clean"></div>
    </div>
</div>
<div class="second_part wrapper">
    <div class="left_bar left">
        <div class="xianshi">
            <div class="title2"><a href="#" target="_blank">限时抢购</a></div>
            <icms id="product_1" type="product_list" where="RecLevel" where_value="1" top="1">
                <item>
                    <![CDATA[
                    <div class="sale_goods" id="sale_goods">
                        <div class="time">剩余<span class="day"></span>天<span class="hour"></span>时<span class="minute"></span>分<span class="second"></span>秒</div>
                        <div class="pic"><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><img src="{f_UploadFileThumbPath2}" width="162" height="162" /></a></div>
                        <div class="name"><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank">{f_ProductName}</a></div>
                        <div class="price"> <a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><span class="right old_price">￥{f_MarketPrice}</span>￥{f_SalePrice}</a></div>
                    </div>
                    <script type="text/javascript">
                    $(function () {
                        countDown("{f_AutoRemoveDate}","#sale_goods .day","#sale_goods .hour","#sale_goods .minute","#sale_goods .second");
                    });
                    </script>
                    ]]>
                </item>
            </icms>
        </div>
    </div>
    <div class="middle left">
        <div class="sales" id="sales">
            <div class="title2 left recent" alt="1"><a href="javascript:;">爆品抢购</a></div>
            <div class="title2 left" alt="2"><a href="javascript:;">新品速递</a></div>
            <div class="title2 left" alt="3"><a href="javascript:;">好评单品</a></div>
            <div class="clean"></div>
            <div class="good" id="tabdiv1">
                <ul>
                    <icms id="product_1" type="product_list" where="RecLevel" where_value="2" top="3">
                        <item>
                            <![CDATA[
                            <li>
                                <div><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><img src="{f_UploadFileThumbPath2}" width="194" height="194" /></a></div>
                                <div class="name"><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank">{f_ProductName}</a></div>
                                <div class="price"><span class="right"><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=2957670343&site=qq&menu=yes"><img border="0" src="/images1/service.png" alt="联系客服" title="联系客服"/></a></span><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><span class="right old_price">￥{f_MarketPrice}</span>￥{f_SalePrice}</a></div>
                            </li>
                            ]]>
                        </item>
                    </icms>
                </ul>
            </div>
            <div class="good" id="tabdiv2" style="display: none">
                <ul>
                    <icms id="product_1" type="product_list" where="RecLevel" where_value="3" top="3">
                        <item>
                            <![CDATA[
                            <li>
                                <div><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><img src="{f_UploadFileThumbPath2}" width="194" height="194" /></a></div>
                                <div class="name"><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank">{f_ProductName}</a></div>
                                <div class="price"><span class="right"><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=2957670343&site=qq&menu=yes"><img border="0" src="/images1/service.png" alt="联系客服" title="联系客服"/></a></span><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><span class="right old_price">￥{f_MarketPrice}</span>￥{f_SalePrice}</a></div>
                            </li>
                            ]]>
                        </item>
                    </icms>
                </ul>
            </div>
            <div class="good" id="tabdiv3" style="display: none">
                <ul>
                    <icms id="product_1" type="product_list" where="RecLevel" where_value="4" top="3">
                        <item>
                            <![CDATA[
                            <li>
                                <div><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><img src="{f_UploadFileThumbPath2}" width="194" height="194" /></a></div>
                                <div class="name"><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank">{f_ProductName}</a></div>
                                <div class="price"><span class="right"><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=2957670343&site=qq&menu=yes"><img border="0" src="/images1/service.png" alt="联系客服" title="联系客服"/></a></span><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><span class="right old_price">￥{f_MarketPrice}</span>￥{f_SalePrice}</a></div>
                            </li>
                            ]]>
                        </item>
                    </icms>
                </ul>
            </div>
        </div>
    </div>
    <div class="right_bar right">
        <div class="sales">
            <div class="title2 recent"><a href="#" target="_blank">下期预售</a></div>
            <icms id="product_1" type="product_list" where="RecLevel" where_value="5" top="1">
                <item>
                    <![CDATA[
                    <div class="sale_goods" id="sale_goods2">
                        <div class="time">剩余<span class="day"></span>天<span class="hour"></span>时<span class="minute"></span>分<span class="second"></span>秒</div>
                        <div class="pic"><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><img src="{f_UploadFileThumbPath2}" width="150" height="160" /></a></div>
                        <div class="name"><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank">{f_ProductName}</a></div>
                        <div class="price"> <a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><span class="right old_price">￥{f_MarketPrice}</span>￥{f_SalePrice}</a></div>
                    </div>
                    <script type="text/javascript">
                        $(function () {
                            countDown("{f_AutoRemoveDate}","#sale_goods2 .day","#sale_goods2 .hour","#sale_goods2 .minute","#sale_goods2 .second");
                        });
                    </script>
                    ]]>
                </item>
            </icms>
        </div>
    </div>
    <div class="clean"></div>
</div>
<div class="part_3 wrapper">
    <div class="box_1">
        <div class="left left_bar">
            <div class="title3 green_bg">食品生鲜<span id="channel_27"></span></div>
            <div class="nav">
                <ul>
                <icms id="channel_27" type="channel_list" where="parent">
                    <item>
                        <![CDATA[
                        <li><span class="sec_title">{f_ChannelName}</span>
                            <ul>
                                {child}
                            </ul>
                        </li>
                        ]]>
                    </item>
                    <child>
                        <![CDATA[
                        <li><a href="/default.php?mod=product&a=list&channel_first_id=27&channel_id={f_ChannelId}">{f_ChannelName}</a></li>
                        ]]>
                    </child>
                </icms>
                </ul>

                <div class="clean"></div>
            </div>
        </div>
        <div class="left middle green_top">
            <div class="sales">
                <ul>
                    <icms id="product_27" type="product_list" where="BelongChannel" top="4">
                        <item>
                            <![CDATA[
                            <li>
                                <div class="left">
                                    <a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><img src="{f_UploadFilePath}" width="150" height="150" /></a>
                                </div>
                                <div class="right">
                                    <div class="name"><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank">{f_ProductName}</a></div>
                                    <div class="price"><a style="float:right;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=2957670343&site=qq&menu=yes"><img border="0" src="/images1/service.png" alt="联系客服" title="联系客服"/></a><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><span class="old_price">￥{f_MarketPrice}</span>￥{f_SalePrice}</a></div>
                                </div>
                                <div class="clean"></div>
                            </li>
                            ]]>
                        </item>
                    </icms>
                </ul>
            </div>
        </div>
        <div class="right_bar right">
            <div class="sales">
                <div class="right_ad">
                    <icms id="channel_135" type="document_news_list" top="0,1" title="36">
                        <item>
                            <![CDATA[
                            <div class="tuijian">
                                <h1><a href="{c_DocumentNewsUrl}" target="_blank">{f_DocumentNewsTitle}</a></h1>
                                <p><a href="{c_DocumentNewsUrl}">{f_DocumentNewsIntro}</a></p>
                            </div>
                            <a href="{c_DocumentNewsUrl}"><img src="{f_TitlePic1Path}" width="241" height="349" /></a>
                            ]]>
                        </item>
                    </icms>
                </div>

            </div>
        </div>
        <div class="clean"></div>
    </div>
    <div class="box_1">
        <div class="left left_bar">
            <div class="title3 red_bg">时令水果<span id="channel_28"></span></div>
            <div class="nav">
                <ul>
                    <icms id="channel_28" type="channel_list" where="parent">
                        <item>
                            <![CDATA[
                            <li><span class="sec_title">{f_ChannelName}</span>
                                <ul>
                                    {child}
                                </ul>
                            </li>
                            ]]>
                        </item>
                        <child>
                            <![CDATA[
                            <li><a href="/default.php?mod=product&a=list&channel_first_id=28&channel_id={f_ChannelId}">{f_ChannelName}</a></li>
                            ]]>
                        </child>
                    </icms>
                </ul>
                <div class="clean"></div>
            </div>
        </div>
        <div class="left middle red_top">
            <div class="sales">
                <ul>
                    <icms id="product_28" type="product_list" where="BelongChannel" top="4">
                        <item>
                            <![CDATA[
                            <li>
                                <div class="left">
                                    <a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><img src="{f_UploadFilePath}" width="150" height="150" /></a>
                                </div>
                                <div class="right">
                                    <div class="name"><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank">{f_ProductName}</a></div>
                                    <div class="price"><a style="float:right;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=2957670343&site=qq&menu=yes"><img border="0" src="/images1/service.png" alt="联系客服" title="联系客服"/></a><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><span class="old_price">￥{f_MarketPrice}</span>￥{f_SalePrice}</a></div>
                                </div>
                                <div class="clean"></div>
                            </li>
                            ]]>
                        </item>
                    </icms>
                </ul>
            </div>
        </div>
        <div class="right_bar right">
            <div class="sales">
                <div class="right_ad">
                    <icms id="channel_135" type="document_news_list" top="1,1" title="36">
                        <item>
                            <![CDATA[
                            <div class="tuijian">
                                <h1><a href="{c_DocumentNewsUrl}" target="_blank">{f_DocumentNewsTitle}</a></h1>
                                <p><a href="{c_DocumentNewsUrl}">{f_DocumentNewsIntro}</a></p>
                            </div>
                            <a href="{c_DocumentNewsUrl}"><img src="{f_TitlePic1Path}" width="241" height="349" /></a>
                            ]]>
                        </item>
                    </icms>
                </div>
            </div>
        </div>
        <div class="clean"></div>
    </div>
    <div class="box_1">
        <div class="left left_bar">
            <div class="title3 blue_bg">家居用品<span id="channel_33"></span></div>
            <div class="nav">
                <ul>
                    <icms id="channel_33" type="channel_list" where="parent">
                        <item>
                            <![CDATA[
                            <li><span class="sec_title">{f_ChannelName}</span>
                                <ul>
                                    {child}
                                </ul>
                            </li>
                            ]]>
                        </item>
                        <child>
                            <![CDATA[
                            <li><a href="/default.php?mod=product&a=list&channel_first_id=33&channel_id={f_ChannelId}">{f_ChannelName}</a></li>
                            ]]>
                        </child>
                    </icms>
                </ul>
                <div class="clean"></div>
            </div>
        </div>
        <div class="left middle blue_top">
            <div class="sales">

                <ul>
                    <icms id="product_33" type="product_list" where="BelongChannel" top="4">
                        <item>
                            <![CDATA[
                            <li>
                                <div class="left">
                                    <a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><img src="{f_UploadFilePath}" width="150" height="150" /></a>
                                </div>
                                <div class="right">
                                    <div class="name"><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank">{f_ProductName}</a></div>
                                    <div class="price"><a style="float:right;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=2957670343&site=qq&menu=yes"><img border="0" src="/images1/service.png" alt="联系客服" title="联系客服"/></a><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><span class="old_price">￥{f_MarketPrice}</span>￥{f_SalePrice}</a></div>
                                </div>
                                <div class="clean"></div>
                            </li>
                            ]]>
                        </item>
                    </icms>
                </ul>
            </div>
        </div>
        <div class="right_bar right">
            <div class="sales">
                <div class="right_ad">
                    <icms id="channel_135" type="document_news_list" top="2,1" title="36">
                        <item>
                            <![CDATA[
                            <div class="tuijian">
                                <h1><a href="{c_DocumentNewsUrl}" target="_blank">{f_DocumentNewsTitle}</a></h1>
                                <p><a href="{c_DocumentNewsUrl}">{f_DocumentNewsIntro}</a></p>
                            </div>
                            <a href="{c_DocumentNewsUrl}"><img src="{f_TitlePic1Path}" width="241" height="349" /></a>
                            ]]>
                        </item>
                    </icms>
                </div>
            </div>
        </div>
        <div class="clean"></div>
    </div>
    <div class="box_1">
        <div class="left left_bar">
            <div class="title3 purple_bg">办公家电<span id="channel_35"></span></div>
            <div class="nav">
                <ul>
                    <icms id="channel_35" type="channel_list" where="parent">
                        <item>
                            <![CDATA[
                            <li><span class="sec_title">{f_ChannelName}</span>
                                <ul>
                                    {child}
                                </ul>
                            </li>
                            ]]>
                        </item>
                        <child>
                            <![CDATA[
                            <li><a href="/default.php?mod=product&a=list&channel_first_id=35&channel_id={f_ChannelId}">{f_ChannelName}</a></li>
                            ]]>
                        </child>
                    </icms>
                </ul>
                <div class="clean"></div>
            </div>
        </div>
        <div class="left middle purple_top">
            <div class="sales">
                <ul>
                    <icms id="product_35" type="product_list" where="BelongChannel" top="4">
                        <item>
                            <![CDATA[
                            <li>
                                <div class="left">
                                    <a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><img src="{f_UploadFilePath}" width="150" height="150" /></a>
                                </div>
                                <div class="right">
                                    <div class="name"><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank">{f_ProductName}</a></div>
                                    <div class="price"><a style="float:right;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=2957670343&site=qq&menu=yes"><img border="0" src="/images1/service.png" alt="联系客服" title="联系客服"/></a><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><span class="old_price">￥{f_MarketPrice}</span>￥{f_SalePrice}</a></div>
                                </div>
                                <div class="clean"></div>
                            </li>
                            ]]>
                        </item>
                    </icms>
                </ul>
            </div>
        </div>
        <div class="right_bar right">
            <div class="sales">
                <div class="right_ad">
                    <icms id="channel_135" type="document_news_list" top="3,1" title="36">
                        <item>
                            <![CDATA[
                            <div class="tuijian">
                                <h1><a href="{c_DocumentNewsUrl}" target="_blank">{f_DocumentNewsTitle}</a></h1>
                                <p><a href="{c_DocumentNewsUrl}">{f_DocumentNewsIntro}</a></p>
                            </div>
                            <a href="{c_DocumentNewsUrl}"><img src="{f_TitlePic1Path}" width="241" height="349" /></a>
                            ]]>
                        </item>
                    </icms>
                </div>
            </div>
        </div>
        <div class="clean"></div>
    </div>
    <div class="side_tag">
        <ul>
            <li><a href="#channel_27">食品生鲜</a></li>
            <li><a href="#channel_28">时令水果</a></li>
            <li><a href="#channel_33">家居用品</a></li>
            <li><a href="#channel_35">办公家电</a></li>
            <li class="go_user"><a href="/default.php?mod=user&a=homepage">用户中心</a></li>
            <li class="go_top"><a href="#top">回到顶部</a></li>
        </ul>
    </div>
</div>
<div class="part_4 wrapper">
    <div class="other_part">
        <ul>
            <li>
                <div class="title4 green_bg"><a href="/h/136/list.html" target="_blank">饮食健康</a></div>
                <icms id="channel_136" type="document_news_list" top="1" title="36">
                    <item>
                        <![CDATA[
                        <div><a href="{c_DocumentNewsUrl}" target="_blank"><img src="{f_TitlePic1Path}" width="279" height="153" alt="{f_DocumentNewsTitle}" /></a></div>
                        <h1><a href="{c_DocumentNewsUrl}" style="font-weight:{f_DocumentNewsTitleBold};color:{f_DocumentNewsTitleColor}" target="_blank">{f_DocumentNewsTitle}</a></h1>
                        ]]>
                    </item>
                </icms>
            </li>
            <li>
                <div class="title4 red_bg"><a href="/h/137/list.html" target="_blank">家庭保健</a></div>
                <icms id="channel_137" type="document_news_list" top="1" title="36">
                    <item>
                        <![CDATA[
                        <div><a href="{c_DocumentNewsUrl}" target="_blank"><img src="{f_TitlePic1Path}" width="279" height="153" alt="{f_DocumentNewsTitle}" /></a></div>
                        <h1><a href="{c_DocumentNewsUrl}" style="font-weight:{f_DocumentNewsTitleBold};color:{f_DocumentNewsTitleColor}" target="_blank">{f_DocumentNewsTitle}</a></h1>
                        ]]>
                    </item>
                </icms>
            </li>
            <li>
                <div class="title4 blue_bg"><a href="/h/138/list.html" target="_blank">时尚生活</a></div>
                <icms id="channel_138" type="document_news_list" top="1" title="36">
                    <item>
                        <![CDATA[
                        <div><a href="{c_DocumentNewsUrl}" target="_blank"><img src="{f_TitlePic1Path}" width="279" height="153" alt="{f_DocumentNewsTitle}" /></a></div>
                        <h1><a href="{c_DocumentNewsUrl}" style="font-weight:{f_DocumentNewsTitleBold};color:{f_DocumentNewsTitleColor}" target="_blank">{f_DocumentNewsTitle}</a></h1>
                        ]]>
                    </item>
                </icms>
            </li>
            <li class="last">
                <div class="title4 purple_bg"><a href="/h/139/list.html" target="_blank">旅游休闲</a></div>
                <icms id="channel_139" type="document_news_list" top="1" title="36">
                    <item>
                        <![CDATA[
                        <div><a href="{c_DocumentNewsUrl}" target="_blank"><img src="{f_TitlePic1Path}" width="279" height="153" alt="{f_DocumentNewsTitle}" /></a></div>
                        <h1><a href="{c_DocumentNewsUrl}" style="font-weight:{f_DocumentNewsTitleBold};color:{f_DocumentNewsTitleColor}" target="_blank">{f_DocumentNewsTitle}</a></h1>
                        ]]>
                    </item>
                </icms>
            </li>
        </ul>
        <div class="clean"></div>
    </div>
</div>
</div>

<pre_temp id="8"></pre_temp>
<script type="text/javascript">var visitConfig = encodeURIComponent("http://www.96333xzw.com") +"||{SiteId}||{ChannelId}||0||0||"+encodeURI("");</script><script type="text/javascript" src="/front_js/visit.js" charset="utf-8"></script>
</div>
</body>
</html>
