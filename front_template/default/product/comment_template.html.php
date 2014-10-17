<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>星级评分系统</title>
    <style>
        body,div,ul,li,p{margin:0;padding:0;}
        body{color:#666;font:12px/1.5 Arial;}
        ul{list-style-type:none;}
        .star ul,#star span{float:left;display:inline;height:19px;line-height:19px;}
        .star ul{margin:0 10px;}
        .star li{float:left;width:24px;cursor:pointer;text-indent:-9999px;background:url(/images/star.png) no-repeat;}
        .star strong{color:#f60;padding-left:10px;}
        .star li.on{background-position:0 -28px;}
        .star p{position:absolute;top:20px;width:159px;height:60px;display:none;background:url(/images/icon.gif) no-repeat;padding:7px 10px 0;}
        .star p em{color:#f60;display:block;font-style:normal;}
    </style>
    <script type="text/javascript">
        window.onload = function ()
        {
            var oProductStar = document.getElementById("product_star");
            var oSendStar = document.getElementById("send_star");
            var oServiceStar = document.getElementById("service_star");
            var aLi = oStar.getElementsByTagName("li");
            var oUl = oStar.getElementsByTagName("ul")[0];
            var oSpan = document.getElementById("result");
            var oP = document.getElementById("display_score");
            var i = iScore = iStar = 0;
            var aMsg = [
                "很不满意|差得太离谱，与卖家描述的严重不符，非常不满",
                "不满意|部分有破损，与卖家描述的不符，不满意",
                "一般|质量一般，没有卖家描述的那么好",
                "满意|质量不错，与卖家描述的基本一致，还是挺满意的",
                "非常满意|质量非常好，与卖家描述的完全一致，非常满意"
            ]

            for (i = 1; i <= aLi.length; i++)
            {
                aLi[i - 1].index = i;
                //鼠标移过显示分数
                aLi[i - 1].onmouseover = function ()
                {
                    fnPoint(this.index);
                    //浮动层显示
                    oP.style.display = "block";
                    //计算浮动层位置
                    oP.style.left = oUl.offsetLeft + this.index * this.offsetWidth - 64 + "px";
                    //匹配浮动层文字内容
                    oP.innerHTML = "<em><b>" + this.index + "</b> 分 " + aMsg[this.index - 1].match(/(.+)\|/)[1] + "</em>" + aMsg[this.index - 1].match(/\|(.+)/)[1]
                };
                //鼠标离开后恢复上次评分
                aLi[i - 1].onmouseout = function ()
                {
                    fnPoint();
                    //关闭浮动层
                    oP.style.display = "none"
                };
                //点击后进行评分处理
                aLi[i - 1].onclick = function ()
                {
                    iStar = this.index;
                    oP.style.display = "none";
                    oSpan.innerHTML = "<strong>" + (this.index) + " 分</strong> (" + aMsg[this.index - 1].match(/\|(.+)/)[1] + ")"
                    $("#score").val(iScore);
                }
            }
            //评分处理
            function fnPoint(iArg)
            {
                //分数赋值
                iScore = iArg || iStar;
                for (i = 0; i < aLi.length; i++) aLi[i].className = i < iScore ? "on" : "";
            }
        };
    </script>
</head>
<body>
<div style="margin: 0 auto;width:800px">
<div id="product_star" class="star">
    <span>评分：</span>
    <ul>
        <li><a href="javascript:;">1</a></li>
        <li><a href="javascript:;">2</a></li>
        <li><a href="javascript:;">3</a></li>
        <li><a href="javascript:;">4</a></li>
        <li><a href="javascript:;">5</a></li>
    </ul>
    <div id="product_result" class="result"></div>
    <p id="product_display_score" class="display_score"></p>
</div>
    <div id="send_star" class="star">
        <span>评分：</span>
        <ul>
            <li><a href="javascript:;">1</a></li>
            <li><a href="javascript:;">2</a></li>
            <li><a href="javascript:;">3</a></li>
            <li><a href="javascript:;">4</a></li>
            <li><a href="javascript:;">5</a></li>
        </ul>
        <div id="send_result" class="result"></div>
        <p id="send_display_score" class="display_score"></p>
    </div>
    <div id="service_star" class="star">
        <span>评分：</span>
        <ul>
            <li><a href="javascript:;">1</a></li>
            <li><a href="javascript:;">2</a></li>
            <li><a href="javascript:;">3</a></li>
            <li><a href="javascript:;">4</a></li>
            <li><a href="javascript:;">5</a></li>
        </ul>
        <div id="service_result" class="result"></div>
        <p id="service_display_score" class="display_score"></p>
    </div>
<div style="clear:both"></div>
<div style="width:100%;">
    <textarea style="width:100%;height:100px" name="Content"></textarea>

    <div style="float:right;width:50px">
        <input type="button" value="发表" id="submit" />
    </div>
    <div style="clear:both;"></div>
    <input type="hidden" id="score" value=""/>
</div>
</div>
</body>
</html>