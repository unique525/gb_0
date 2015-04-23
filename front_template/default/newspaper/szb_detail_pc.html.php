<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{NewspaperArticleTitle} - {BrowserTitle}</title>
    <meta name="keywords" content="{BrowserKeywords}" />
    <meta name="description" content="{BrowserDescription}" />
    <link rel="stylesheet" href="/image_szb_pc/szb.css">
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/front_js/roll/msclass.js"></script>
    <style type="text/css">
        a {
            text-decoration: none;
        }
        /* 标题导航栏 */
        .menu1{position: absolute;z-index: 100}
        .menu1 a{text-decoration:none;font-size:14px;}
        .menu1 ul,li{
            z-index: 100;
            list-style: none outside none;
            margin:0;
            padding:0;
        }
        .menu1 ul{
            width:260px;
            background:#ffffff;
        }
        .menu1 .first_li{width:260px;font-family:"Microsoft YaHei";}
        .menu1 .first_li a{color:#000; line-height:30px;display:block;}
        .menu1 .first_li a:hover{background:#A2DBFF;display:block;line-height:30px;}
        /*左侧导航栏*/
        .am-panel{
            line-height: 160%;
            margin-bottom:10px;
        }
        .am-panel-default {
            border: 1px solid #DDD;
        }
        .am-panel-hd {
            cursor: pointer;
            border-bottom: 0px none;
            color: #444;
            background-color: #F5F5F5;
            border-color: #DDD;
            padding: 5px;
        }
        .am-panel-collapse{
            display: none;
            border-top: 1px solid #DDD;
        }
        .am-panel-title {
            margin: 0px;
            font-size: 100%;
            color: inherit;
        }
        .am-panel-bd {
            border-top: 1px solid #DDD;
            padding: 5px;
        }
        .am-panel-bd a{
            color:#727272;
        }

       /*轮换图样式*/
        .slider-nav {width: 100%;text-align: center; line-height: 28px; border-top: #d8d8d8 1px solid; height: 28px;}
        .slider-nav-bar{margin: 0 auto;display:inline-block;padding-top: 6px; zoom: 1; height: 22px;}
        .slider-nav-bar ul{display:inline-block;*display:inline;float:left}
        .slider-nav-bar li {list-style:none;margin-top: 5px; background: url(/image_szb_pc/qqsplit.png) no-repeat -165px -112px; float: left; margin-left: 12px; width: 6px; cursor: pointer; height: 6px; _display: inline}
        .slider-nav-bar li.active {background: url(/image_szb_pc/qqsplit.png) no-repeat -165px -86px}
        .slider-content {}
        #slider-pre {background: url(/image_szb_pc/qqsplit.png) no-repeat -151px -60px; float: left; width: 20px; cursor: pointer; margin-right: 8px; height: 15px; _display: inline}
        #slider-next {background: url(/image_szb_pc/qqsplit.png) no-repeat -151px -131px; float: left; width: 20px; cursor: pointer; height: 15px;margin-left: 16px;}
        #slider-marquee {overflow: hidden; zoom: 1;left:-10px;position:relative;margin:0 auto;}
        #slider-marquee li{margin: 0 1px;}
        #slider-marquee-content img {border: #ccc 1px solid;overflow: hidden}
        #slider-marquee-content .link {margin:0 auto;padding-top:20px;width:500px;display: block; text-align: center;font-size:18px;line-height:26px;}
        #slider-marquee-content a:link {color: #0b3b8c; text-decoration: none}
        #slider-marquee-content a:visited {color: #0b3b8c; text-decoration: none}
        #slider-marquee-content a:hover {color: #0b3b8c; text-decoration: underline}
        #slider-marquee-content a:active {color: #0b3b8c; text-decoration: underline}

    </style>
    <script>
        function getWeekByDay(dayValue){
            var day = new Date(dayValue); //将日期值格式化
            var today = new Array("日","一","二","三","四","五","六"); //创建星期数组
            return today[day.getDay()];  //返一个星期中的某一天，其中0为星期日
        }
        $(function () {

            //计算时间
            var currentPaperDate = "{PublishDate}";
            currentPaperDate = currentPaperDate.replace(new RegExp("-", "gm"), "/");
            var date = new Date(currentPaperDate);
            var year = date.getFullYear();
            var month = date.getMonth() + 1;
            var day = date.getDay();
            var weekday = getWeekByDay(currentPaperDate);
            $("#paper_year").html(year);
            $("#paper_month").html(month);
            $("#paper_day").html(day);
            $("#paper_week_day").html(weekday);


            $("#bmdh").mouseover(function (e) {
                $("#div_bm").css("display", "block");
                $("#div_bm").css("left", e.clientX - 10);
                $("#div_bm").css("top", e.clientY - 10);
            });
            $("#div_bm").mouseleave(function () {
                $(this).css("display", "none");
            });

            /*展开与折叠左边导航菜单*/
            $("div .am-panel-hd").click(function () {
                $(this).siblings("div").toggle();
            });

            /*滚动图片*/
            var picCount = $("#slider-marquee-content >li").length;
            if (picCount > 0) {
                //添加圆点图示
                for (var i = 0; i < picCount; i++) {
                    $("#slider-tab").append("<li></li>");
                }
                var marqueeControl = new Marquee(["slider-marquee", "slider-marquee-content", "slider-tab", "onclick"], 4, 0.5, 604, 600, 20, 5000, 100000, 0);
                $("#slider-marquee").css("height","");
                $("#slider-marquee-content").css("height","");
                $("#slider-pre").click(function () {
                    marqueeControl.Run(3);
                });
                $("#slider-next").click(function () {
                    marqueeControl.Run(2);
                });
            }
            else {
                $("#slider-box").css("display", "none");
            }

        });

    </script>
</head>
<body>
<!--头部-->
<div class="head_box">
    <div id="div_bm" class="menu1" style="display: none;">
        <ul>
            <icms id="newspaper_page" top="100">
                <item>
                    <![CDATA[
                    <li class="first_li"><a href="/default.php?mod=newspaper&a=gen_one_for_pc&channel_id={ChannelId}&publish_date={PublishDate}&newspaper_first_page_id={f_NewspaperPageId}">{f_NewsPaperPageName}</a></li>
                    ]]>
                </item>
            </icms>
        </ul>
    </div>
    <div class="head_s">
        <div class="head_top">
            <div class="headIcon">
                <img src="/image_szb_pc/changsha-icon_02.png">
            </div>
            <div class="headTxt">
                <p class="name_s">多媒体数字报</p>

                <p class="call_s">社长：龙跃刚 总编辑：徐辉 晚报热线：96333</p>

                <div id="register_s">注册</div>
                <div class="space_s"></div>
                <div id="login_s">登陆</div>
            </div>
        </div>
        <div class="headIndex">
            <div class="space_s">&nbsp;</div>
            <a href="#">长沙晚报网</a>
            <div>
                <img src="/image_szb_pc/line.png">
            </div>
            <a href="#">数字报</a>
            <div>
                <img src="/image_szb_pc/line.png">
            </div>
            <a href="#">版面概览</a>
            <div>
                <img src="/image_szb_pc/line.png">
            </div>
            <a id="bmdh" href="javascript:void(0)">版面导航</a>
            <div>
                <img src="/image_szb_pc/line.png">
            </div>

            <div class="headDate">
                <span id="paper_year"></span>年<span id="paper_month"></span>月<span id="paper_day"></span>日 星期<span id="paper_week_day"></span> 出版
            </div>
            <div class="search">
                <form action="#" method="get">
                    <input class="input_s" type="text" name="search_txt">
                    <input class="sub_s" type="image" src="/image_szb_pc/search2_03.png">
                </form>
            </div>
        </div>
    </div>
</div>
<!--内容-->
<div style="margin: auto;width: 1200px">
    <div style="float: left;width: 296px;margin: 10px;">
        <div class="am-panel-group" id="accordion">
            <icms id="newspaper_page_and_article" type="list" >
                <item>
                    <![CDATA[
                    <div class="am-panel am-panel-default">
                        <div class="am-panel-hd">
                            <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#do-not-say-{c_no}'}">
                                {f_NewspaperPageName}（{f_NewspaperPageNo}）
                            </h4>
                        </div>
                        <div class="am-panel-collapse">
                            {child}
                        </div>
                    </div>
                    ]]>
                </item>
                <child>
                    <![CDATA[
                    <div class="am-panel-bd">
                        <a href="/default.php?mod=newspaper_article&a=detail&newspaper_article_id={f_NewspaperArticleId}">
                            {f_NewspaperArticleTitle}
                        </a>
                    </div>
                    ]]>
                </child>
            </icms>
        </div>
    </div>
    <div style="float: left;border: #d8d8d8 1px solid; margin-top: 10px;  width: 860px;">
        <div style="text-align:center;margin:5px;">
            <h4>{NewspaperArticleCiteTitle}</h4>
            <h2>{NewspaperArticleTitle}</h2>
            <h4>{NewspaperArticleSubTitle}</h4>
        </div>
        <div id="slider-box">
            <div class="slider-content">
                <div id="slider-marquee">
                    <ul id="slider-marquee-content">
                        <icms id="newspaper_article_{NewspaperArticleId}" type="newspaper_article_pic_list" top="100">
                            <item>
                                <![CDATA[
                                <li>
                                    <a href="javascript:void(0)"><img width=600 alt="{f_Remark}" src="http://www.icswb.com/{f_UploadFilePath}"></a>
                                    <span class="link">{f_Remark}</span>
                                </li>
                                ]]>
                            </item>
                        </icms>
                        <icms id="newspaper_article_slider_{NewspaperArticleId}" type="newspaper_article_pic_list_slider" top="100">
                            <item>
                                <![CDATA[
                                <li>
                                    <a href="javascript:void(0)"><img width=600 alt="{f_Remark}" src="http://www.icswb.com/{f_UploadFilePath}"></a>
                                    <span class="link">{f_Remark}</span>
                                </li>
                                ]]>
                            </item>
                        </icms>
                    </ul>
                </div>
            </div>
            <div class="slider-nav">
                <div class="slider-nav-bar"><span id="slider-pre"></span><ul id="slider-tab"></ul><span id="slider-next"></span></div>
            </div>
        </div>

        <div>
            <p style="margin:10px;line-height:150%;font-size:120%;" id="content">{NewspaperArticleContent}</p>
        </div>
        <div class="box_cen">
            <div class="cen_top"></div>
            <div class="cen_middle"></div>
            <div class="cen_bottom"></div>
        </div>
    </div>
</div>
<!--内容-->
<div style="clear: both"></div>
<!--底部-->
<div>
    <div class="bottom_left">
        <div class="inner"></div>
    </div>
    <div class="bottom_main">
        <div class="inner">
            <div class="bottom_s">
                <div class="bottomIndex">
                    <p>标题导航</p>

                    <div>
                        <ul>
                            <li><a href="#">大道之间失去比 u 的区别</a></li>
                            <li><a href="#">这是你肚饿我把我变成</a></li>
                            <li><a href="#">哈哈三个代表哪家</a></li>
                            <li><a href="#">那就是从 in 为</a></li>
                            <li><a href="#">路上看到才能发言说</a></li>
                        </ul>
                    </div>
                </div>
                <div id="calendar"></div>
                <div class="code">
                    <img src="/image_szb_pc/code1.png">
                    <img src="/image_szb_pc/code2.png">
                </div>
                <div class="exp">
                    <img src="/image_szb_pc/icon2015_18.png">
                </div>
            </div>
        </div>
    </div>
    <div class="bottom_right">
        <div class="inner"></div>
    </div>
</div>
</body>
</html>