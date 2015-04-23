<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>长沙晚报数字报</title>
    <link rel="stylesheet" href="/image_szb_pc/szb.css">
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <style type="text/css">
        .mask {filter:alpha(opacity=0);-moz-opacity:0;opacity:0;position:absolute;text-align:center;cursor:pointer;background:#eeeeee}
        .mask_select{filter:alpha(opacity=50);-moz-opacity:0.5;opacity:0.5;position:absolute;text-align:center;cursor:pointer;background:#000000}
        .mask a{display:block}
        .mask_select a{display:block}

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
    </style>
    <script>
        function getWeekByDay(dayValue){
            var day = new Date(dayValue); //将日期值格式化
            var today = new Array("日","一","二","三","四","五","六"); //创建星期数组
            return today[day.getDay()];  //返一个星期中的某一天，其中0为星期日
        }
        function showTip(evt,title) {
            var objDiv = $("#div_tip");
            $(objDiv).html(title);
            $(objDiv).css("display","block");
            $(objDiv).css("left", evt.clientX+20);
            $(objDiv).css("top", evt.clientY+30);
        }
        function hideTip() {
            var objDiv = $("#div_tip");
            $(objDiv).css("display", "none");
        }
        $(function () {

            //计算时间
            var currentPaperDate="{CurrentPublishDate}";
            currentPaperDate=currentPaperDate.replace(new RegExp("-","gm"),"/");
            var date = new Date(currentPaperDate);
            var year=date.getFullYear();
            var month=date.getMonth()+1;
            var day=date.getDay();
            var weekday=getWeekByDay(currentPaperDate);
            $("#paper_year").html(year);
            $("#paper_month").html(month);
            $("#paper_day").html(day);
            $("#paper_week_day").html(weekday);

            //计算图片宽度
            var WindowWidth=$(window).width();
            var imgResizeWidth = (WindowWidth-500)/2;
            var imgResizeHeight = imgResizeWidth/2*3;

            var arr1 = {FirstPageArticlePoint};
            var arr2 = {SecondPageArticlePoint};
            var imgTarget1 = $("#img1");
            imgTarget1.css("width",imgResizeWidth);
            imgTarget1.css("height",imgResizeHeight);
            $.each(arr1, function (i, v) {
                var divLeft = v[2] * imgResizeWidth / 100;
                var divTop = v[3] * imgResizeHeight / 100;
                var divWidth = v[4] * imgResizeWidth / 100;
                var divHeight = v[5] * imgResizeHeight / 100;
                var id = "div_" + v[0];
                $("#" + id).mouseenter(function (e) {
                    $(this).attr("class", "mask_select");
                    showTip(e, $(this).attr("idvalue"));
                }).mouseleave(function () {
                        $(this).attr("class", "mask");
                        hideTip();
                    });
                $("#" + id).css("left", divLeft + "px").css("top", divTop + "px").css("width", divWidth + "px").css("height", divHeight + "px").css("line-height", divHeight + "px").children("a").html("&nbsp;");
            });

            var imgTarget2 = $("#img2");
            imgTarget2.css("width",imgResizeWidth);
            imgTarget2.css("height",imgResizeHeight);
            $.each(arr2, function (i, v) {
                var divLeft = v[2] * imgResizeWidth / 100;
                var divTop = v[3] * imgResizeHeight / 100;
                var divWidth = v[4] * imgResizeWidth / 100;
                var divHeight = v[5] * imgResizeHeight / 100;
                var id = "div_" + v[0];
                $("#" + id).mouseenter(function (e) {
                    $(this).attr("class", "mask_select");
                    showTip(e, $(this).attr("idvalue"));
                })
                    .mouseleave(function (e) {
                        $(this).attr("class", "mask");
                        hideTip();
                    });
                $("#" + id).css("left", divLeft + "px").css("top", divTop + "px").css("width", divWidth + "px").css("height", divHeight + "px").css("line-height", divHeight + "px").children("a").html("&nbsp;");
            });

            $("#bmdh").mouseover(function (e) {
                $("#div_bm").css("display", "block");
                $("#div_bm").css("left", e.clientX-10);
                $("#div_bm").css("top", e.clientY-10);
            });
            $("#div_bm").mouseleave(function (){
                $(this).css("display","none");
            });

            $("#bt1").mouseover(function (e) {
                $("#div_bt_nav_1").css("display", "block");
                $("#div_bt_nav_1").css("left", e.clientX);
                $("#div_bt_nav_1").css("top", e.clientY);
            });
            $("#div_bt_nav_1").mouseleave(function (){
                $(this).css("display","none");
            });
            $("#bt2").mouseover(function (e) {
                $("#div_bt_nav_2").css("display", "block");
                $("#div_bt_nav_2").css("left", e.clientX);
                $("#div_bt_nav_2").css("top", e.clientY);
            });
            $("#div_bt_nav_2").mouseleave(function (){
                $(this).css("display","none");
            });

            $("#prevPageBtn").click(function () {
                var id = $(this).attr("idvalue");
                if (id > 0) {
                    return true;
                }
                else {
                    alert("已经是第一页了");
                    return false;
                }

            });
            $("#nextPageBtn").click(function () {
                var id = $(this).attr("idvalue");
                if (id > 0) {
                    return true;
                }
                else {
                    alert("已经是最后一页了");
                    return false;
                }

            });

        });
    </script>
</head>
<body>
<div id="div_tip" style="text-align:center;font-size:20px;padding:10px 20px 10px 20px;position:absolute;display:none;border:1px solid #CEE3E9;background:#F1F7F9;z-index: 100"></div>
<div id="div_bt_nav_1" class="menu1" style="display: none;">
    <ul>
        <icms id="newspaper_page_{NewspaperFirstPageId}" top="100" type="newspaper_article_list">
            <item>
                <![CDATA[
                <li class="first_li"><a href="/default.php?&mod=product&a=list&channel_first_id=28&channel_id=28">{f_NewsPaperArticleTitle}</a></li>
                ]]>
            </item>
        </icms>
    </ul>
</div>
<div id="div_bt_nav_2" class="menu1" style="display: none;">
    <ul>
        <icms id="newspaper_page_{NewspaperSecondPageId}" top="100" type="newspaper_article_list">
            <item>
                <![CDATA[
                <li class="first_li"><a href="/default.php?&mod=product&a=list&channel_first_id=28&channel_id=28">{f_NewsPaperArticleTitle}</a></li>
                ]]>
            </item>
        </icms>
    </ul>
</div>
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
<table style="margin: 0 auto">
    <tr>
        <td>
            <table class="box_left">
                <tr>
                    <td class="t_l"></td>
                    <td class="t_m"></td>
                    <td class="t_r"></td>
                </tr>
                <tr>
                    <td class="m_l"></td>

                    <td class="m_m">
                        <div class="m_m_l">
                            <div id="bt1" style="display: inline">标题导航</div>
                            <div style="position:relative;">
                                <div style="z-index:1"><img id="img1" src="http://www.icswb.com{UploadFilePath_First}" /></div>
                                <icms id="newspaper_page_{NewspaperFirstPageId}" top="100" type="newspaper_article_list">
                                    <item>
                                        <![CDATA[
                                        <div id="div_{f_NewsPaperArticleId}" idvalue="{f_NewspaperArticleTitle}" class="mask"><a href="www.163.com">333333333</a></div>
                                        ]]>
                                    </item>
                                </icms>
                                </div>
                            </div>
                        </div>
                        <div id="turn_left"><a id="prevPageBtn" idvalue="{NewspaperPreviousFirstPageId}" href="/default.php?mod=newspaper&a=gen_one_for_pc&channel_id={ChannelId}&publish_date={PublishDate}&newspaper_first_page_id={NewspaperPreviousFirstPageId}"><img border="0" src="/image_szb_pc/turn-left.png"></a></div>
                    </td>
                    <td class="m_r"></td>
                </tr>
                <tr>
                    <td class="b_l"></td>
                    <td class="b_m"></td>
                    <td class="b_r"></td>
                </tr>
            </table>
            <table class="box_right">
                <tr>
                    <td class="t_m"></td>
                    <td class="t_r"></td>
                </tr>
                <tr>

                    <td class="m_m">
                        <div class="m_m_l">
                            <div id="bt2" style="display: inline">标题导航</div>
                            <div style="position:relative;">
                                <div style="z-index:1">
                                <img id="img2" src="http://www.icswb.com{UploadFilePath_Second}" /></div>
                                <icms id="newspaper_page_{NewspaperSecondPageId}" top="100" type="newspaper_article_list">
                                    <item>
                                        <![CDATA[
                                        <div id="div_{f_NewsPaperArticleId}" idvalue="{f_NewspaperArticleTitle}" class="mask"><a href="www.163.com">333333333</a></div>
                                        ]]>
                                    </item>
                                </icms>
                                </div>
                            </div>
                        </div>
                        <div id="turn_right"><a id="nextPageBtn" idvalue="{NewspaperNextFirstPageId}" href="/default.php?mod=newspaper&a=gen_one_for_pc&channel_id={ChannelId}&publish_date={PublishDate}&newspaper_first_page_id={NewspaperNextFirstPageId}"><img border="0" src="/image_szb_pc/turn-right.png"></a></div>
                    </td>
                    <td class="m_r"></td>
                </tr>
                <tr>
                    <td class="b_m"></td>
                    <td class="b_r"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<div class="box_cen">
    <div class="cen_top"></div>
    <div class="cen_middle"></div>
    <div class="cen_bottom"></div>
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