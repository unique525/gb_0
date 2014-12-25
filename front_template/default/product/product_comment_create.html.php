<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>商品评分</title>
    <link href="/images/common_css.css" rel="stylesheet" type="text/css"/>
    <link href="/images/user_layout.css" rel="stylesheet" type="text/css" />
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

            $("#sub").click(function(){
                var parameter = $("#main_form").serialize();
                $.ajax({
                    url:"/default.php?mod=product_comment&a=async_create&product_id={ProductId}&user_order_id={UserOrderId}&user_order_product_id={UserOrderProductId}",
                    type:"post",
                    data:parameter,
                    dataType:"jsonp",
                    jsonp:"jsonpcallback",
                    success:function(data){
                        var result = parseInt(data["result"]);
                        if(result > 0 ){
                            alert("评价成功");
                            window.location.href="/default.php?mod=user_order&a=list";
                        }else if(result == -3){
                            alert("系统错误");
                        }else if(result == -2){
                            alert("您未购买本产品");
                        }else if(result == -5){
                            alert("交易未完成，您不能评论");
                        }else if(result == -4){
                            alert("您还没有登录");
                            window.location.href="/default.php?mod=user&a=login&re_url="+encodeURIComponent(window.location.href);
                        }
                    }
                });
            });
        });
    </script>
</head>
<body>
<pre_temp id="4"></pre_temp>
<div class="clean"></div>
<pre_temp id="12"></pre_temp>
<div class="wrapper">
    <div class="comment_zone">
        <div class="left left_pic"> <img src="{ProductTitlePic}" width="100" height="100" /></div>
        <div class="right right_part">
            <div class="star_zone">
                <div id="product_star" class="star" idvalue="product">
                    <span>产品评分：</span>
                    <ul>
                        <li><a href="javascript:;">1</a></li>
                        <li><a href="javascript:;">2</a></li>
                        <li><a href="javascript:;">3</a></li>
                        <li><a href="javascript:;">4</a></li>
                        <li><a href="javascript:;">5</a></li>
                    </ul>
                    <div id="product_result" class="result" idvalue="product"></div>
                    <p id="product_display_score" class="display_score" idvalue="product"></p>
                    <div class="clean"></div>
                </div>
                <div style="clear:both"></div>
                <div id="send_star" class="star" idvalue="send">
                    <span>物流评分：</span>
                    <ul>
                        <li><a href="javascript:;">1</a></li>
                        <li><a href="javascript:;">2</a></li>
                        <li><a href="javascript:;">3</a></li>
                        <li><a href="javascript:;">4</a></li>
                        <li><a href="javascript:;">5</a></li>
                    </ul>
                    <div id="send_result" class="result" idvalue="send"></div>
                    <p id="send_display_score" class="display_score" idvalue="send"></p>
                    <div class="clean"></div>
                </div>
                <div style="clear:both"></div>
                <div id="service_star" class="star" idvalue="service">
                    <span>服务评分：</span>
                    <ul>
                        <li><a href="javascript:;">1</a></li>
                        <li><a href="javascript:;">2</a></li>
                        <li><a href="javascript:;">3</a></li>
                        <li><a href="javascript:;">4</a></li>
                        <li><a href="javascript:;">5</a></li>
                    </ul>
                    <div id="service_result" class="result" idvalue="service"></div>
                    <p id="service_display_score" class="display_score" idvalue="service"></p>
                    <div class="clean"></div>
                </div>
                <div style="clear:both"></div>
            </div>
        </div>
        <div class="clean"></div>
        <form id="main_form" action="/default.php?mod=product_comment&a=create&product_id={ProductId}&user_order_id={UserOrderId}" method="post">
            <div class="vote">
                <input type="radio" name="appraisal" value="0"/><label>好评</label>
                <input type="radio" name="appraisal" value="1"/><label>中评</label>
                <input type="radio" name="appraisal" value="2"/><label>差评</label>
            </div>
            <div style="width:100%;">
                <textarea style="width:100%;height:100px" name="content"></textarea>

                <div class="comment_submit">
                    <input type="button" style="cursor:pointer" value="发  表" id="sub" />
                </div>
                <div style="clear:both;"></div>
                <input type="hidden" name="product_score" id="product_score" autocomplete="off" value="0"/>
                <input type="hidden" name="send_score" id="send_score" autocomplete="off" value="0"/>
                <input type="hidden" name="service_score" id="service_score" autocomplete="off" value="0"/>
                <input type="hidden" name="site_id" autocomplete="off" value="0"/>
            </div>
        </form>

    </div>
</div>

<div class="footerline"></div>
<div class="wrapper">
    <div class="footerleft">
        <div class="cont">
            <div><img src="images/footergwzn.png" width="79" height="79"/></div>
            <b>交易条款</b><br/>
            <a href="" target="_blank">购物流程</a><br/>
            <a href="" target="_blank">发票制度</a><br/>
            <a href="" target="_blank">会员等级</a><br/>
            <a href="" target="_blank">积分制度</a><br/><br/>
        </div>
    </div>
    <div class="footerleft">
        <div class="cont">
            <div><img src="images/footerpsfw.png" width="79" height="79"/></div>
            <b>配送服务</b><br/>
            <a href="" target="_blank">配送说明</a><br/>
            <a href="" target="_blank">配送范围</a><br/>
            <a href="" target="_blank">配送状态查询</a><br/><br/><br/>
        </div>
    </div>
    <div class="footerleft">
        <div class="cont">
            <div><img src="images/footerzffs.png" width="79" height="79"/></div>
            <b>支付方式</b><br/>
            <a href="" target="_blank">支付宝支付</a><br/>
            <a href="" target="_blank">银联在线支付</a><br/>
            <a href="" target="_blank">货到付款</a><br/><br/><br/>
        </div>
    </div>
    <div class="footerleft">
        <div class="cont">
            <div><img src="images/footershfw.png" width="79" height="79"/></div>
            <b>售后服务</b><br/>
            <a href="" target="_blank">服务承诺</a><br/>
            <a href="" target="_blank">退换货政策</a><br/>
            <a href="" target="_blank">退换货流程</a><br/><br/><br/>
        </div>
    </div>
    <div class="footerright" style="padding-left:50px;">
        手机客户端下载
        <div><img src="images/weixin.png" width="104" height="104"/></div>
    </div>
    <div class="footerright" style="padding-right:50px;">
        手机客户端下载
        <div><img src="images/weixin.png" width="104" height="104"/></div>
    </div>
</div>
</body>
</html>