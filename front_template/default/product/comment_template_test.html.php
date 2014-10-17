<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>星级评分系统</title>
    <style>
        body,div,ul,li,p{margin:0;padding:0;}
        body{color:#666;font:12px/1.5 Arial;}
        ul{list-style-type:none;}
        .star ul,.star span{float:left;display:inline;height:19px;line-height:19px;}
        .star ul{margin:0 10px;}
        .star li{float:left;width:24px;cursor:pointer;text-indent:-9999px;background:url(/images/star.png) no-repeat;}
        .star strong{color:#f60;padding-left:10px;}
        .star li.on{background-position:0 -28px;}
        .star p em{color:#f60;display:block;font-style:normal;}
    </style>
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/common.js"></script>
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript">
        var aMsg = [
            "失望",
            "不满",
            "一般",
            "满意",
            "惊喜"
        ];
        $(function(){
            $(".star").each(function(){
                var idvalue = $(this).attr("idvalue");
                var aLi = $(this).find("li");
                var oSpan = $("#"+idvalue+"_result");
                var iScore = $("#"+idvalue+"_score").val();
                for (var i=1; i<= aLi.length; i++){
                    aLi[i-1].index = i;
                    //鼠标移过显示分数
                    aLi[i-1].onmouseover = function (){
                        for (var j = 0; j < aLi.length; j++) {
                            if(j < this.index){
                                aLi[j].className="on";
                            }else{
                                aLi[j].className="";
                            }
                        }
                        //匹配浮动层文字内容
                        oSpan.html("<em><b>" + this.index + "</b> 分 " + aMsg[this.index - 1]+ "</em>");
                    };
                    //鼠标离开后恢复上次评分
                    aLi[i - 1].onmouseout = function ()
                    {
                        for (var j = 0; j < aLi.length; j++) {
                            if(j < iScore){
                                aLi[j].className="on";
                            }else{
                                aLi[j].className="";
                            }
                        }
                        if(iScore>0){
                            oSpan.html("<strong>" + iScore + " 分</strong> (" + aMsg[iScore-1] + ")");
                        }else{
                            oSpan.html("");
                        }
                    };
                    //点击后进行评分处理
                    aLi[i - 1].onclick = function ()
                    {
                        iScore = this.index;
                        oSpan.html("<strong>" + (this.index) + " 分</strong> (" + aMsg[this.index - 1] + ")");
                        $("#"+idvalue+"_score").val(iScore);
                    }
                }
            });

            $("#submit").click(function(){

            });
        });
    </script>
</head>
<body>
<div style="margin: 0 auto;width:800px">
    <div id="product_star" class="star" idvalue="product">
        <span>评分：</span>
        <ul>
            <li><a href="javascript:;">1</a></li>
            <li><a href="javascript:;">2</a></li>
            <li><a href="javascript:;">3</a></li>
            <li><a href="javascript:;">4</a></li>
            <li><a href="javascript:;">5</a></li>
        </ul>
        <div id="product_result" class="result" idvalue="product"></div>
        <p id="product_display_score" class="display_score" idvalue="product"></p>
    </div>
    <div style="clear:both"></div>
    <div id="send_star" class="star" idvalue="send">
        <span>评分：</span>
        <ul>
            <li><a href="javascript:;">1</a></li>
            <li><a href="javascript:;">2</a></li>
            <li><a href="javascript:;">3</a></li>
            <li><a href="javascript:;">4</a></li>
            <li><a href="javascript:;">5</a></li>
        </ul>
        <div id="send_result" class="result" idvalue="send"></div>
        <p id="send_display_score" class="display_score" idvalue="send"></p>
    </div>
    <div style="clear:both"></div>
    <div id="service_star" class="star" idvalue="service">
        <span>评分：</span>
        <ul>
            <li><a href="javascript:;">1</a></li>
            <li><a href="javascript:;">2</a></li>
            <li><a href="javascript:;">3</a></li>
            <li><a href="javascript:;">4</a></li>
            <li><a href="javascript:;">5</a></li>
        </ul>
        <div id="service_result" class="result" idvalue="service"></div>
        <p id="service_display_score" class="display_score" idvalue="service"></p>
    </div>
    <div style="clear:both"></div>
    <form id="main_form" action="/default.php?mod=product_comment&a=create" method="post">
    差评：<input type="radio" name="appraisal" value="0"/>
    中评：<input type="radio" name="appraisal" value="1"/>
    好评：<input type="radio" name="appraisal" value="2"/>
    <div style="width:100%;">
        <textarea style="width:100%;height:100px" name="content"></textarea>

        <div style="float:right;width:50px">
            <input type="button" value="发表" id="submit" />
        </div>
        <div style="clear:both;"></div>
        <input type="hidden" name="product_score" id="product_score"autocomplete="off" value="0"/>
        <input type="hidden" name="send_score" id="send_score" autocomplete="off" value="0"/>
        <input type="hidden" name="service_score" id="service_score" autocomplete="off" value="0"/>
        <input type="hidden" name="product_id" autocomplete="off" value="0"/>
        <input type="hidden" name="site_id" autocomplete="off" value="0"/>
    </div>
        </form>
</div>
</body>
</html>