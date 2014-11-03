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
    $(function () {
        /* 顶部banner类别菜单初始化 */
        $('#leftmenu>ul>li>ul').find('li:has(ul:not(:empty))>a').append("<span class='arrow'>></span>"); // 为有子菜单的菜单项添加'>'符号
        $('#leftmenu>ul>li>ul li').bind('mouseover',function() // 子菜单的鼠标移入操作
        {
            $(this).children('ul').css('display','');
        }).bind('mouseleave',function() // 子菜单的鼠标移出操作
        {
                $(this).children('ul').css('display','none');
        });
    });
    </script>
</head>

<body>
<div class="head">
    <pre_temp id="3"></pre_temp>
    <div class="wrapper2">
        <div class="logo"><a href=""><img src="images/mylogo.png" width="320" height="103" /></a></div>
        <div class="search">
            <div class="search_green"><input name="" type="text" class="text"/></div>
            <div class="searchbtn"><img src="images/search.png" width="46" height="28" /></div>
            <div class="searchbottom">平谷大桃  哈密瓜  新鲜葡萄  红炉磨坊  太湖鲜鱼</div>
        </div>
        <div class="service">
            <div class="hottel"><span><a href="" target="_blank">热线96333</a></span></div>
            <div class="online"><span><a href="" target="_blank">在线客服</a></span></div>
            <div class="shopping"><span>购物车</span></div>
            <div class="number">0</div>
        </div>
    </div>
    <div class="clean"></div>
    <div class="mainbav">
        <div class="wrapper">
            <div class="goods"><span>所有商品分类</span></div>
            <div class="column1"><a href="">首页</a></div>
            <div class="column2"><a href="">超市量贩</a></div>
            <div class="column2"><a href="">团购</a></div>
            <div class="column2"><a href="">最新预售</a></div>
            <div class="new"><img src="images/icon_new.png" width="29" height="30" /></div>
        </div>
    </div>
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
                                <li><img src="{f_icon}" width="37" height="35" /><a href="">{f_ChannelName}</a>
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
                                <![CDATA[<a href="/default.php?&mod=product&a=list&channel_id={f_ChannelId}">{f_ChannelName}</a><span>|</span>
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
            <div class="sale_goods">
                <div class="time">剩余<span>02</span>小时<span>23</span>分钟<span>40</span>秒 </div>
                <div class="pic"><a href="#" target="_blank"><img src="images/xianshi_img.jpg" width="162" height="136" /></a></div>
                <div class="name"><a href="#" target="_blank">妈咪宝贝满299减30送豪礼</a></div>
                <div class="price"> <a href="#" target="_blank"><span class="right old_price">￥49</span>￥25.90</a></div>
            </div>
        </div>
    </div>
    <div class="middle left">
        <div class="sales">
            <div class="title2 left "><a href="#" target="_blank">爆品抢购</a></div>
            <div class="title2 left recent"><a href="#" target="_blank">新品速递</a></div>
            <div class="title2 left"><a href="#" target="_blank">好评单品</a></div>
            <div class="clean"></div>
            <div class="good">
                <ul>
                    <li>
                        <div class="new_tag hidden">新品速递</div>
                        <div><a href="#" target="_blank"><img src="images/img_1.jpg" width="194" height="150" /></a></div>
                        <div class="name"><a href="#" target="_blank">富安娜家纺 全棉斜纹 纯棉印花双人四件套</a></div>
                        <div class="price"><a href="#" target="_blank"><span class="right old_price">￥45.5</span>￥25.90</a></div>
                    </li>
                    <li>
                        <div class="new_tag">新品速递</div>
                        <div><a href="#" target="_blank"><img src="images/img_1.jpg" width="194" height="150" /></a></div>
                        <div class="name"><a href="#" target="_blank">富安娜家纺 全棉斜纹 纯棉印花双人四件套</a></div>
                        <div class="price"><a href="#" target="_blank"><span class="right old_price">￥45.5</span>￥25.90</a></div>
                    </li>
                    <li>
                        <div class="new_tag hidden">新品速递</div>
                        <div><a href="#" target="_blank"><img src="images/img_1.jpg" width="194" height="150" /></a></div>
                        <div class="name"><a href="#" target="_blank">富安娜家纺 全棉斜纹 纯棉印花双人四件套</a></div>
                        <div class="price"><a href="#" target="_blank"><span class="right old_price">￥45.5</span>￥25.90</a></div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="right_bar right">
        <div class="sales">
            <div class="title2 recent"><a href="#" target="_blank">下期预售</a></div>
            <div class="sale_goods">
                <div class="time">剩余<span>02</span>小时<span>23</span>分钟<span>40</span>秒 </div>
                <div class="pic"><a href="#" target="_blank"><img src="images/img_1.jpg" width="194" height="150" /></a></div>
                <div class="name"><a href="#" target="_blank">妈咪宝贝满299减30送豪礼</a></div>
                <div class="price"> <a href="#" target="_blank"><span class="right old_price">￥49</span>￥25.90</a></div>
            </div>
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
                        <li>{f_ChannelName}
                            <ul>
                                {child}
                            </ul>
                        </li>
                        ]]>
                    </item>
                    <child>
                        <![CDATA[
                        <li><a href="#" target="_blank">{f_ChannelName}</a></li>
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
                    <li>
                        <div class="left">
                            <a href="#" target="_blank"><img src="images/img_1.jpg" width="194" height="150" /></a>
                        </div>
                        <div class="right">
                            <div class="name"><a href="#" target="_blank">富安娜家纺全棉斜纹纯棉印花双人四件套富安娜家全棉斜纹纯棉印花双人四</a></div>
                            <div class="price"><a href="#" target="_blank">￥25.90<span class="old_price">￥49</span></a></div>
                        </div>
                        <div class="clean"></div>
                    </li>
                    <li>
                        <div class="left">
                            <a href="#" target="_blank"><img src="images/img_1.jpg" width="194" height="150" /></a>
                        </div>
                        <div class="right">
                            <div class="name"><a href="#" target="_blank">富安娜家纺全棉斜纹纯棉印花双人四件套富安娜家全棉斜纹纯棉印花双人四</a></div>
                            <div class="price"><a href="#" target="_blank">￥25.90<span class="old_price">￥49</span></a></div>
                        </div>
                        <div class="clean"></div>
                    </li>
                    <li>
                        <div class="left">
                            <a href="#" target="_blank"><img src="images/img_1.jpg" width="194" height="150" /></a>
                        </div>
                        <div class="right">
                            <div class="name"><a href="#" target="_blank">富安娜家纺全棉斜纹纯棉印花双人四件套富安娜家全棉斜纹纯棉印花双人四</a></div>
                            <div class="price"><a href="#" target="_blank">￥25.90<span class="old_price">￥49</span></a></div>
                        </div>
                        <div class="clean"></div>
                    </li>
                    <li>
                        <div class="left">
                            <a href="#" target="_blank"><img src="images/img_1.jpg" width="194" height="150" /></a>
                        </div>
                        <div class="right">
                            <div class="name"><a href="#" target="_blank">富安娜家纺全棉斜纹纯棉印花双人四件套富安娜家全棉斜纹纯棉印花双人四</a></div>
                            <div class="price"><a href="#" target="_blank">￥25.90<span class="old_price">￥49</span></a></div>
                        </div>
                        <div class="clean"></div>
                    </li>
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
                            <li>{f_ChannelName}
                                <ul>
                                    {child}
                                </ul>
                            </li>
                            ]]>
                        </item>
                        <child>
                            <![CDATA[
                            <li><a href="#" target="_blank">{f_ChannelName}</a></li>
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
                    <li>
                        <div class="left">
                            <a href="#" target="_blank"><img src="images/img_1.jpg" width="194" height="150" /></a>
                        </div>
                        <div class="right">
                            <div class="name"><a href="#" target="_blank">富安娜家纺全棉斜纹纯棉印花双人四件套富安娜家全棉斜纹纯棉印花双人四</a></div>
                            <div class="price"><a href="#" target="_blank">￥25.90<span class="old_price">￥49</span></a></div>
                        </div>
                        <div class="clean"></div>
                    </li>
                    <li>
                        <div class="left">
                            <a href="#" target="_blank"><img src="images/img_1.jpg" width="194" height="150" /></a>
                        </div>
                        <div class="right">
                            <div class="name"><a href="#" target="_blank">富安娜家纺全棉斜纹纯棉印花双人四件套富安娜家全棉斜纹纯棉印花双人四</a></div>
                            <div class="price"><a href="#" target="_blank">￥25.90<span class="old_price">￥49</span></a></div>
                        </div>
                        <div class="clean"></div>
                    </li>
                    <li>
                        <div class="left">
                            <a href="#" target="_blank"><img src="images/img_1.jpg" width="194" height="150" /></a>
                        </div>
                        <div class="right">
                            <div class="name"><a href="#" target="_blank">富安娜家纺全棉斜纹纯棉印花双人四件套富安娜家全棉斜纹纯棉印花双人四</a></div>
                            <div class="price"><a href="#" target="_blank">￥25.90<span class="old_price">￥49</span></a></div>
                        </div>
                        <div class="clean"></div>
                    </li>
                    <li>
                        <div class="left">
                            <a href="#" target="_blank"><img src="images/img_1.jpg" width="194" height="150" /></a>
                        </div>
                        <div class="right">
                            <div class="name"><a href="#" target="_blank">富安娜家纺全棉斜纹纯棉印花双人四件套富安娜家全棉斜纹纯棉印花双人四</a></div>
                            <div class="price"><a href="#" target="_blank">￥25.90<span class="old_price">￥49</span></a></div>
                        </div>
                        <div class="clean"></div>
                    </li>
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
