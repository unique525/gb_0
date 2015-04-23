<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>无标题文档</title>
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <style type="text/css">
        body {font-size:12px}
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
            background:#eeeeee;
            filter:alpha(opacity=80);-moz-opacity:0.8;opacity:0.8;
        }
        .menu1 .first_li{width:260px;font-family:"Microsoft YaHei";}
        .menu1 .first_li a{color:#000; line-height:30px;display:block;}
        .menu1 .first_li a:hover{background:#A2DBFF;display:block;line-height:30px;}
    </style>
    <script>
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
            var arr1 = {FirstPageArticlePoint};
            var arr2 = {SecondPageArticlePoint};
            var imgTarget1 = $("#img1");
            var image1Width = imgTarget1.width();
            var image1Height = imgTarget1.height();
            $.each(arr1, function (i, v) {
                var divLeft = v[2] * image1Width / 100;
                var divTop = v[3] * image1Height / 100;
                var divWidth = v[4] * image1Width / 100;
                var divHeight = v[5] * image1Height / 100;
                var id = "div_" + v[0];
                $("#" + id).mouseenter(function (e) {
                    $(this).attr("class", "mask_select");
                    showTip(e, $(this).attr("idvalue"));
                }).mouseout(function () {
                        $(this).attr("class", "mask");
                        hideTip();
                    });
                $("#" + id).css("left", divLeft + "px").css("top", divTop + "px").css("width", divWidth + "px").css("height", divHeight + "px").css("line-height", divHeight + "px").children("a").html("&nbsp;");
            });

            var imgTarget2 = $("#img2");
            var image2Width = imgTarget2.width();
            var image2Height = imgTarget2.height();
            $.each(arr2, function (i, v) {
                var divLeft = v[2] * image2Width / 100;
                var divTop = v[3] * image2Height / 100;
                var divWidth = v[4] * image2Width / 100;
                var divHeight = v[5] * image2Height / 100;
                var id = "div_" + v[0];
                $("#" + id).mouseenter(function (e) {
                    $(this).attr("class", "mask_select");
                    showTip(e, $(this).attr("idvalue"));
                })
                    .mouseout(function (e) {
                        $(this).attr("class", "mask");
                        hideTip();
                    });
                $("#" + id).css("left", divLeft + "px").css("top", divTop + "px").css("width", divWidth + "px").css("height", divHeight + "px").css("line-height", divHeight + "px").children("a").html("&nbsp;");
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
<div>
    <div><a id="prevPageBtn" idvalue="{NewspaperPreviousFirstPageId}" href="/default.php?mod=newspaper&a=gen_one_for_pc&channel_id={ChannelId}&publish_date={PublishDate}&newspaper_first_page_id={NewspaperPreviousFirstPageId}">上一页</a></div>
    <div><a id="nextPageBtn" idvalue="{NewspaperNextFirstPageId}" href="/default.php?mod=newspaper&a=gen_one_for_pc&channel_id={ChannelId}&publish_date={PublishDate}&newspaper_first_page_id={NewspaperNextFirstPageId}">下一页</a></div>
</div>
<div style="">
    <div style="border:1px solid #CC3333;float:left;">
        <div id="bt1" style="display: inline">标题导航</div>
        <div style="position:relative">
        <div style="z-index:1"><img id="img1" src="http://www.icswb.com{UploadFilePath_First}" width="400" /></div>
        <icms id="newspaper_page_{NewspaperFirstPageId}" top="100" type="newspaper_article_list">
            <item>
                <![CDATA[
                <div id="div_{f_NewsPaperArticleId}" idvalue="{f_NewspaperArticleTitle}" class="mask"><a href="www.163.com">333333333</a></div>
                ]]>
            </item>
        </icms>
        </div>
    </div>
    <div style="border:1px solid #CC3333;float:left;">
        <div id="bt2" style="display: inline">标题导航</div>
        <div style="position:relative">
        <div style="z-index:1"><img id="img2" src="http://www.icswb.com{UploadFilePath_Second}" width="400" /></div>
        <icms id="newspaper_page_{NewspaperSecondPageId}" top="100" type="newspaper_article_list">
            <item>
                <![CDATA[
                <div id="div_{f_NewsPaperArticleId}" idvalue="{f_NewspaperArticleTitle}" class="mask"><a href="www.163.com">333333333</a></div>
                ]]>
            </item>
        </icms>
        </div>
    </div>
</body>
</html>