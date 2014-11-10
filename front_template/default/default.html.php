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
    <script type="text/javascript" src="/front_js/user/user.js"></script>
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
            $('#leftmenu>ul>li>ul').find('li:has(ul:not(:empty))>a').append("<span class='arrow'>></span>"); // 为有子菜单的菜单项添加'>'符号
            $('#leftmenu>ul>li>ul li').bind('mouseover',function () // 子菜单的鼠标移入操作
            {
                $(this).children('ul').css('display', '');
            }).bind('mouseleave', function () // 子菜单的鼠标移出操作
                {
                    $(this).children('ul').css('display', 'none');
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
        });
    </script>
</head>

<body>
<div class="head">
    <pre_temp id="3"></pre_temp>
    <pre_temp id="4"></pre_temp>
    <pre_temp id="5"></pre_temp>
</div>

<div class="main">
<div class="first_part">
    <div class="wrapper">
        <div class="left_bar left" id="leftmenu" style="background:#00a93c;height:429px;">

            <ul>
                <li>
                    <ul style="display: block;">
                        <icms id="channel_3" type="channel_list" where="parent">
                            <item>
                                <![CDATA[
                                <li><img src="{f_icon}" width="37" height="35" /><a href="javascript:;">{f_ChannelName}</a>
                                    <ul style="display: none;">
                                        {child}
                                    </ul>
                                </li>
                                ]]>
                            </item>
                            <child>
                                <![CDATA[
                                <li><span>{f_ChannelName}</span></li>
                                <dd>{third}</dd>
                                ]]>
                            </child>
                            <third>
                                <![CDATA[<a href="/default.php?mod=product&a=list&channel_id={f_ChannelId}">{f_ChannelName}</a>
                                ]]>
                            </third>

                        </icms>
                    </ul>
                </li>
            </ul>

        </div>
        <div class="middle left"><img src="images/ad.jpg" width="741" height="429" /></div>
        <div class="right_bar right">
            <div class="gonggao">
                <div class="title1"><span class="right"><a href="/h/17/list.html" target="_blank">更多</a></span>网站公告 </div>
                <div class="gonggao_list">
                    <ul>
                    <icms id="channel_17" type="document_news_list" top="4" title="24">
                        <item>
                            <![CDATA[
                            <li><a href="{c_DocumentNewsUrl}" target="_blank">{f_DocumentNewsTitle}</a></li>
                            ]]>
                        </item>
                    </icms>
                    </ul>
                </div>
            </div>
            <div class="fuwu">
                <div class="title1"><span class="right"><a href="#">更多</a></span>便民服务</div>
                <div class="fuwu_tags">
                    <div class="fuwu_class gray"><a href="/h/19/list.html" class="jiazhen" target="_blank">家政服务</a></div>
                    <div class="fuwu_class"><a href="/h/20/list.html" class="weixiu" target="_blank">维修清洗</a></div>
                    <div class="fuwu_class gray"><a href="/h/21/list.html" class="fanxin" target="_blank">翻新保养</a></div>
                    <div class="fuwu_class"><a href="/h/22/list.html" class="jiaju" target="_blank">家居安全</a></div>
                    <div class="fuwu_class"><a href="/h/23/list.html" class="jiaotong" target="_blank">交通旅游</a></div>
                    <div class="fuwu_class gray"><a href="/h/24/list.html" class="jiaoyu" target="_blank">教育培训</a></div>
                    <div class="fuwu_class"><a href="/h/25/list.html" class="jinrong" target="_blank">金融服务</a></div>
                    <div class="fuwu_class gray"><a href="/h/26/list.html" class="qita" target="_blank">其他服务</a></div>
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
            <icms id="product_1" type="product_list" where="RecLevel" top="1">
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
                    <icms id="product_2" type="product_list" where="RecLevel" top="3">
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
                </ul>
            </div>
            <div class="good" id="tabdiv2" style="display: none">
                <ul>
                    <icms id="product_3" type="product_list" where="RecLevel" top="3">
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
                </ul>
            </div>
            <div class="good" id="tabdiv3" style="display: none">
                <ul>
                    <icms id="product_4" type="product_list" where="RecLevel" top="3">
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
                </ul>
            </div>
        </div>
    </div>
    <div class="right_bar right">
        <div class="sales">
            <div class="title2 recent"><a href="#" target="_blank">下期预售</a></div>
            <icms id="product_5" type="product_list" where="RecLevel" top="1">
                <item>
                    <![CDATA[
                    <div class="sale_goods" id="sale_goods2">
                        <div class="time">剩余<span class="day"></span>天<span class="hour"></span>时<span class="minute"></span>分<span class="second"></span>秒</div>
                        <div class="pic"><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><img src="{f_UploadFileThumbPath2}" width="160" height="160" /></a></div>
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
            <div class="title3 green_bg">每日生鲜</div>
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
                        <li><a href="/default.php?mod=product&a=list&channel_id={f_ChannelId}">{f_ChannelName}</a></li>
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
                    <icms id="product_27" type="product_list" where="AllChild" top="4">
                        <item>
                            <![CDATA[
                            <li>
                                <div class="left">
                                    <a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><img src="{f_UploadFilePath}" width="160" height="160" /></a>
                                </div>
                                <div class="right">
                                    <div class="name"><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank">{f_ProductName}</a></div>
                                    <div class="price"><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><span class="old_price">￥{f_MarketPrice}</span>￥{f_SalePrice}</a></div>
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
                    <div class="tuijian">
                        <h1><a href="#" target="_blank">买手推荐</a></h1>
                        <p><a href="#">产品产品产品产品</a></p>
                    </div>
                    <a href="#"><img src="images/tuijian_img.jpg" width="241" height="349" /></a></div>
            </div>
        </div>
        <div class="clean"></div>
    </div>
    <div class="box_1">
        <div class="left left_bar">
            <div class="title3 red_bg">时令水果</div>
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
                            <li><a href="/default.php?mod=product&a=list&channel_id={f_ChannelId}">{f_ChannelName}</a></li>
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
                    <icms id="product_28" type="product_list" where="AllChild" top="4">
                        <item>
                            <![CDATA[
                            <li>
                                <div class="left">
                                    <a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><img src="{f_UploadFilePath}" width="160" height="160" /></a>
                                </div>
                                <div class="right">
                                    <div class="name"><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank">{f_ProductName}</a></div>
                                    <div class="price"><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><span class="old_price">￥{f_MarketPrice}</span>￥{f_SalePrice}</a></div>
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
                    <div class="tuijian">
                        <h1><a href="#" target="_blank">买手推荐</a></h1>
                        <p><a href="#">产品产品产品产品</a></p>
                    </div>
                    <a href="#"><img src="images/tuijian_img.jpg" width="241" height="349" /></a></div>
            </div>
        </div>
        <div class="clean"></div>
    </div>
    <div class="side_tag">
        <ul>
            <li><a href="#">每日生鲜</a></li>
            <li><a href="#">时令水果</a></li>
            <li><a href="#">每日生鲜</a></li>
            <li><a href="#">每日生鲜</a></li>
            <li><a href="#">每日生鲜</a></li>
            <li class="go_user"><a href="#">用户中心</a></li>
            <li class="go_top"><a href="#">回到顶部</a></li>
        </ul>
    </div>
</div>
<div class="part_4 wrapper">
    <div class="other_part">
        <ul>
            <li>
                <div class="title4 green_bg">饮食健康</div>
                <div><a href="#" target="_blank"><img src="images/img_2.jpg" width="279" height="153" /></a></div>
                <h1><a href="#" target="_blank">干煸菜花：这样吃更美味</a></h1>
            </li>
            <li>
                <div class="title4 red_bg">饮食健康</div>
                <div><a href="#" target="_blank"><img src="images/img_2.jpg" width="279" height="153" /></a></div>
                <h1><a href="#" target="_blank">干煸菜花：这样吃更美味</a></h1>
            </li>
            <li>
                <div class="title4 blue_bg">饮食健康</div>
                <div><a href="#" target="_blank"><img src="images/img_2.jpg" width="279" height="153" /></a></div>
                <h1><a href="#" target="_blank">干煸菜花：这样吃更美味</a></h1>
            </li>
            <li class="last">
                <div class="title4 purple_bg">饮食健康</div>
                <div><a href="#" target="_blank"><img src="images/img_2.jpg" width="279" height="153" /></a></div>
                <h1><a href="#" target="_blank">干煸菜花：这样吃更美味</a></h1>
            </li>
        </ul>
        <div class="clean"></div>
    </div>
</div>
</div>

<div class="foot">
    <div class="footerline"></div>
    <div class="wrapper">
        <div class="footerleft">
            <div class="cont">
                <div><img src="images/footergwzn.png" width="79" height="79" /></div>
                <b>交易条款</b><br />
                <a href="" target="_blank">购物流程</a><br />
                <a href="" target="_blank">发票制度</a><br />
                <a href="" target="_blank">会员等级</a><br />
                <a href="" target="_blank">积分制度</a><br /><br />
            </div>
        </div>
        <div class="footerleft">
            <div class="cont">
                <div><img src="images/footerpsfw.png" width="79" height="79" /></div>
                <b>配送服务</b><br />
                <a href="" target="_blank">配送说明</a><br />
                <a href="" target="_blank">配送范围</a><br />
                <a href="" target="_blank">配送状态查询</a><br /><br /><br />
            </div>
        </div>
        <div class="footerleft">
            <div class="cont">
                <div><img src="images/footerzffs.png" width="79" height="79" /></div>
                <b>支付方式</b><br />
                <a href="" target="_blank">支付宝支付</a><br />
                <a href="" target="_blank">银联在线支付</a><br />
                <a href="" target="_blank">货到付款</a><br /><br /><br />
            </div>
        </div>
        <div class="footerleft">
            <div class="cont">
                <div><img src="images/footershfw.png" width="79" height="79" /></div>
                <b>售后服务</b><br />
                <a href="" target="_blank">服务承诺</a><br />
                <a href="" target="_blank">退换货政策</a><br />
                <a href="" target="_blank">退换货流程</a><br /><br /><br />
            </div>
        </div>
        <div class="footerright" style="padding-left:50px;">
            手机客户端下载
            <div><img src="images/weixin.png" width="104" height="104" /></div>
        </div>
        <div class="footerright" style="padding-right:50px;">
            手机客户端下载
            <div><img src="images/weixin.png" width="104" height="104" /></div>
        </div>
    </div>
</div>

</body>
</html>
