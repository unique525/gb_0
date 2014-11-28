<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>星滋味--{ChannelName}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <link href="/images/common.css" rel="stylesheet" type="text/css" />
    <link href="/images/common_css.css" rel="stylesheet" type="text/css" />
    <style>
        .banner{
            width:1200px;
            margin:0 auto;
            background-position:top center;
            height:367px;}

        .main{
            margin:0 auto;
            width:1200px;
        }
        .part_1{
            width:1200px;
            border-bottom:3px #00a93c solid;
            height:48px;
            padding-top:20px;
            background: url(7_03_17.jpg) no-repeat right bottom
        }
        .part_2{
            width:1200px;
            border-bottom:3px #00a93c solid;
            margin-top:15px;
        }
        .part_2 span{
            float:right;
            font-size:14px;
            color:#00a93c;
            padding-top:19px;
        }
        .part_2 span a{
            color:#00a93c;
            text-decoration:none}
        .tejia{
            width:292px;
            height:48px;
            background-color:#00a93c;
        }
        .tejia h2{
            color:#FFF;
            font-size:24px;
            text-align:center;
            padding-top:8px;
            font-weight:normal;}
        .fengnei ul {
            width:731px;
            height:31px;
            padding-top:16px;
            padding-left:20px;
        }
        .fengnei li{
            line-height:30px;
            width:118px;
            float:left;
            border:#EAEAEA solid 1px;
            margin-left:20px;
        }
        .fengnei li a{
            font-size:14px;
            padding-left:30px;
            display:block;

        }
        .fengnei li :hover{
            background:#00a93c;
            color:#FFFFFF;
            text-decoration:none
        }

        .fengnei .fl{
            background:#00a93c;
            color:#FFFFFF;
            font-size:14px;
            padding-left:30px;
            width:98px;
        }
        .fl a:hover{
            background:#00a93c;}
        .foot_title{
            width:170px;
            float:left;
            padding-left:14px;

        }
        .foot_title img{
            float:left;
            padding-left:14px;
            margin-top:2px
        }
        .foot_title h3{
            float:right;
            font-size:18px;
            padding-top:11px;
            padding-right:29px;
            color:#00a93c;
        }
        .foot_title .jiaju{
            float:right;
            font-size:18px;
            padding-top:26px;
            padding-right:29px;
            color:#00a93c;
        }
        .foot_li{
            width:1200px;
            float:left;
            padding-top:9px}
        .foot_li li p{
            line-height:22px;
            font-size:14px;
            padding-left:10px;
            width:266px;
            padding-top:6px;
            padding-bottom:7px
        }
        .foot_li li{
            width:284px;
            float:left;
            padding:8px;
        }
        .foot_li  img{
            padding:8px 3px 4px 8px
        }

        .price_zone{
            border-top:solid #E6E6E6 1px;

        }
        .div-price{
            float:left}
        .fav{
            float:right}
        .price_1{
            float:left;
            height:43px;
            width:150px;
            padding-left:8px;
            padding-bottom:7px
        }
        .price_1 h4{
            line-height:43px;
            font-size:23px;
            color:#ff3c00;
            font-weight:900;
            float:left;
        }

        .price_1 h4 span{
            font-size:14px;
            color:#ff3c00}
        .price_2{
            float:right;
            padding-top:11px;
            padding-right:10px
        }
        .jia{
            width:58px;
            background:url(images/6_17.jpg) no-repeat;
            color:#FFFFFF;
            font-size:12px;
            display: inline-block;
            height:17px;
            line-height:14px;
            _line-height:15px;
            padding: 0 4px 0 10px;
            overflow:hidden;
            /* [disabled]position:relative; */
        }

        .p-del{
            display:block;
            text-decoration:line-through;
            color:#8b8a8a}
        .p-del em{
            400 12px "microsoft yahei";
            color:#8b8a8a
        }
        .price_3{
            height:30px;
            background: url(images/5_17_29.jpg) no-repeat;
            width:90px;
            float:right;
            padding-top:3px;
            margin-top:8px
        }
        .price_3 a,.price_3 a:hover{
            font-size:14px;
            color:#FFF;
            padding-left:14px;
            text-decoration:none
        }
    </style>
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/common.js"></script>
    <script type="text/javascript">
        $(function () {


            //左侧产品类别树形效果
            $("#categoryListSum").delegate('.listsum-1', "click",
                function (e) {
                    if (e.target != "javascript:;" && e.target != 'javascript:void(0);') {
                        var className = $(this).find('dl').attr("class");
                        $("#categoryListSum dt").find('a').removeClass("on");
                        var datalink = $(this).attr("data-link");
                        if (datalink) {
                            $("#categoryListSum dt a").attr("href", datalink);
                        }
                        if (className == "listhover") {
                            $(this).find('dl').removeClass("listhover");
                        } else {
                            $(this).find('dl').find('dt').find('a').addClass("on");
                            $(this).find('dl').addClass('listhover').end().siblings().find("dl").removeClass("listhover")
                        }
                    }
                });
            var channelId = Request["channel_id"];
            $("#categoryListSum div[class='listsum-1'] dl").removeClass("listhover");
            $("#categoryListSum div[class='listsum-1'] dl[alt="+channelId+"]").addClass("listhover").find('a').addClass("on");
            $("#categoryListSum div[class='listsum-1']>dl>dd>ul>li a[alt="+channelId+"]").addClass("onlinked").closest("dl").addClass("listhover").find('dt').find('a').addClass("on");

            //根据不同的排序字段顺序显示产品列表
            $('.price-2 a').attr("class", "listup");
            var ps = Request["ps"];
            if(ps==null||ps=="") ps="12";
            var url = "/default.php?&mod=product&a=list&channel_first_id={ChannelFirstId}&channel_id={ChannelId}&p=1&ps="+ps;
            var order = Request["order"];
            var orderType="";
            var direction="";
            if (order != null&&order != "") {
                orderType = order.split('_')[0];
                direction = order.split('_')[1];
                $('.price-2 a[title="'+orderType+'"]').attr("class", "listup on");
            }
            $('.price-2 a').click(function () {
                if ($(this).attr("title") == orderType) {
                    direction = (direction == "up" ? "down" : "up");
                    url += "&order=" + orderType + "_" + direction;
                }
                else {
                    url += "&order=" + $(this).attr("title") + "_up";
                }
                window.location.href=url+"#product_list_anchor";
            });

            //清空会员浏览记录ajax方法
            $("#hrefClear").click(function(){
                var url = "/default.php?mod=user_explore&a=async_delete";
                $.ajax({
                    url:url,
                    data:{},
                    dataType:"jsonp",
                    jsonp:"jsonpcallback",
                    success:function(data){
                        $("#explore_list").html("");
                    }
                });
            });
        });
    </script>
</head>
<body>
<pre_temp id="3"></pre_temp>
<pre_temp id="4"></pre_temp>
<pre_temp id="9"></pre_temp>

<div class="banner"><img src="images/21_14.jpg" width="1200" height="367" />
</div>
<!--内容-->
<div class="main">
<div class="part_1">
    <div class="tejia">
        <h2>特价量版</h2>
    </div>
</div>
<div class="fengnei">
    <ul>
        <li class="fl">全部分类</li>
        <li><a href="#">食品生鲜</a></li>
        <li><a href="#">家居用品</a></li>
        <li><a href="#">母婴用品</a></li>
    </ul>
</div>
<div class="clean"></div>
<div class="shipin">
<div class="part_2">
    <div class="foot_title">
        <img src="images/18_14.jpg" height="39" width="35"/>
        <h3>食品生鲜</h3>
    </div>
    <span><a href="#">更多>></a></span>
    <div class="clean"></div>
</div>
<div class="foot_li">
    <ul>
    <icms id="product_27" type="product_list" where="DiscountAllChild" top="12">
        <item>
            <![CDATA[
            <li>
            <div style="border:#E1E0DF solid 1px;">
                <a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}"><img src="{f_UploadFileThumbPath1}" height="267" width="267"/></a>
                <p><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}">{f_ProductName}</a></p>
                <div class="price_zone">
                    <div class="price_1">
                        <h4><span>￥</span>{f_SalePrice}</h4>
                        <div class="price_2">
                            <span class="jia">省39.5</span>
                            <span class="p-del"><em>￥</em>{f_MarketPrice}</span>
                        </div>
                    </div>
                    <div class="price_3"><a href="#">立即购买</a></div>
                    <div class="clean"></div>
                </div>
            </div>
            </li>
            ]]>
        </item>
    </icms>
    </ul>
    <div class="clean"></div>
</div>
</div>
<div class="shipin">
<div class="part_2">
    <div class="foot_title">
        <img src="images/19_28.jpg" height="55" width="23"/>
        <h3>家具用品</h3>
    </div>
    <span><a href="#">更多>></a></span>
    <div class="clean"></div>
</div>
<div class="foot_li">
    <ul>
        <li><div style="border:#E1E0DF solid 1px;">
                <a href="#"><img src="images/1.jpg" height="267" width="267"/></a>
                <p><a href="#">蓝月亮3000洗衣液-薰+2000洁净洗衣液-自然+500手洗翻盖*2+1000洗衣液</a></p>
                <div class="price_zone">
                    <div class="price_1">
                        <h4><span>￥</span>118</h4>
                        <div class="price_2">
                            <span class="jia">省39.5</span>
                            <span class="p-del"><em>￥</em>9.5</span>
                        </div>
                    </div>
                    <div class="price_3"><a href="#">立即购买</a></div>
                    <div class="clean"></div>
                </div>
            </div>
        </li>
        <li><div style="border:#E1E0DF solid 1px;">
                <a href="#"><img src="images/1.jpg" height="267" width="267"/></a>
                <p><a href="#">蓝月亮3000洗衣液-薰+2000洁净洗衣液-自然+500手洗翻盖*2+1000洗衣液</a></p>
                <div class="price_zone">
                    <div class="price_1">
                        <h4><span>￥</span>118</h4>
                        <div class="price_2">
                            <span class="jia">省39.5</span>
                            <span class="p-del"><em>￥</em>9.5</span>
                        </div>
                    </div>
                    <div class="price_3"><a href="#">立即购买</a></div>
                    <div class="clean"></div>
                </div>
            </div>
        </li>
        <li><div style="border:#E1E0DF solid 1px;">
                <a href="#"><img src="images/1.jpg" height="267" width="267"/></a>
                <p><a href="#">蓝月亮3000洗衣液-薰+2000洁净洗衣液-自然+500手洗翻盖*2+1000洗衣液</a></p>
                <div class="price_zone">
                    <div class="price_1">
                        <h4><span>￥</span>118</h4>
                        <div class="price_2">
                            <span class="jia">省39.5</span>
                            <span class="p-del"><em>￥</em>9.5</span>
                        </div>
                    </div>
                    <div class="price_3"><a href="#">立即购买</a></div>
                    <div class="clean"></div>
                </div>
            </div>
        </li>
        <li><div style="border:#E1E0DF solid 1px;">
                <a href="#"><img src="images/1.jpg" height="267" width="267"/></a>
                <p><a href="#">蓝月亮3000洗衣液-薰+2000洁净洗衣液-自然+500手洗翻盖*2+1000洗衣液</a></p>
                <div class="price_zone">
                    <div class="price_1">
                        <h4><span>￥</span>118</h4>
                        <div class="price_2">
                            <span class="jia">省39.5</span>
                            <span class="p-del"><em>￥</em>9.5</span>
                        </div>
                    </div>
                    <div class="price_3"><a href="#">立即购买</a></div>
                    <div class="clean"></div>
                </div>
            </div>
        </li>
        <li><div style="border:#E1E0DF solid 1px;">
                <a href="#"><img src="images/1.jpg" height="267" width="267"/></a>
                <p><a href="#">蓝月亮3000洗衣液-薰+2000洁净洗衣液-自然+500手洗翻盖*2+1000洗衣液</a></p>
                <div class="price_zone">
                    <div class="price_1">
                        <h4><span>￥</span>118</h4>
                        <div class="price_2">
                            <span class="jia">省39.5</span>
                            <span class="p-del"><em>￥</em>9.5</span>
                        </div>
                    </div>
                    <div class="price_3"><a href="#">立即购买</a></div>
                    <div class="clean"></div>
                </div>
            </div>
        </li>
        <li><div style="border:#E1E0DF solid 1px;">
                <a href="#"><img src="images/1.jpg" height="267" width="267"/></a>
                <p><a href="#">蓝月亮3000洗衣液-薰+2000洁净洗衣液-自然+500手洗翻盖*2+1000洗衣液</a></p>
                <div class="price_zone">
                    <div class="price_1">
                        <h4><span>￥</span>118</h4>
                        <div class="price_2">
                            <span class="jia">省39.5</span>
                            <span class="p-del"><em>￥</em>9.5</span>
                        </div>
                    </div>
                    <div class="price_3"><a href="#">立即购买</a></div>
                    <div class="clean"></div>
                </div>
            </div>
        </li>
        <li><div style="border:#E1E0DF solid 1px;">
                <a href="#"><img src="images/1.jpg" height="267" width="267"/></a>
                <p><a href="#">蓝月亮3000洗衣液-薰+2000洁净洗衣液-自然+500手洗翻盖*2+1000洗衣液</a></p>
                <div class="price_zone">
                    <div class="price_1">
                        <h4><span>￥</span>118</h4>
                        <div class="price_2">
                            <span class="jia">省39.5</span>
                            <span class="p-del"><em>￥</em>9.5</span>
                        </div>
                    </div>
                    <div class="price_3"><a href="#">立即购买</a></div>
                    <div class="clean"></div>
                </div>
            </div>
        </li>
        <li><div style="border:#E1E0DF solid 1px;">
                <a href="#"><img src="images/1.jpg" height="267" width="267"/></a>
                <p><a href="#">蓝月亮3000洗衣液-薰+2000洁净洗衣液-自然+500手洗翻盖*2+1000洗衣液</a></p>
                <div class="price_zone">
                    <div class="price_1">
                        <h4><span>￥</span>118</h4>
                        <div class="price_2">
                            <span class="jia">省39.5</span>
                            <span class="p-del"><em>￥</em>9.5</span>
                        </div>
                    </div>
                    <div class="price_3"><a href="#">立即购买</a></div>
                    <div class="clean"></div>
                </div>
            </div>
        </li>
        <li><div style="border:#E1E0DF solid 1px;">
                <a href="#"><img src="images/1.jpg" height="267" width="267"/></a>
                <p><a href="#">蓝月亮3000洗衣液-薰+2000洁净洗衣液-自然+500手洗翻盖*2+1000洗衣液</a></p>
                <div class="price_zone">
                    <div class="price_1">
                        <h4><span>￥</span>118</h4>
                        <div class="price_2">
                            <span class="jia">省39.5</span>
                            <span class="p-del"><em>￥</em>9.5</span>
                        </div>
                    </div>
                    <div class="price_3"><a href="#">立即购买</a></div>
                    <div class="clean"></div>
                </div>
            </div>
        </li>
        <li><div style="border:#E1E0DF solid 1px;">
                <a href="#"><img src="images/1.jpg" height="267" width="267"/></a>
                <p><a href="#">蓝月亮3000洗衣液-薰+2000洁净洗衣液-自然+500手洗翻盖*2+1000洗衣液</a></p>
                <div class="price_zone">
                    <div class="price_1">
                        <h4><span>￥</span>118</h4>
                        <div class="price_2">
                            <span class="jia">省39.5</span>
                            <span class="p-del"><em>￥</em>9.5</span>
                        </div>
                    </div>
                    <div class="price_3"><a href="#">立即购买</a></div>
                    <div class="clean"></div>
                </div>
            </div>
        </li>
        <li><div style="border:#E1E0DF solid 1px;">
                <a href="#"><img src="images/1.jpg" height="267" width="267"/></a>
                <p><a href="#">蓝月亮3000洗衣液-薰+2000洁净洗衣液-自然+500手洗翻盖*2+1000洗衣液</a></p>
                <div class="price_zone">
                    <div class="price_1">
                        <h4><span>￥</span>118</h4>
                        <div class="price_2">
                            <span class="jia">省39.5</span>
                            <span class="p-del"><em>￥</em>9.5</span>
                        </div>
                    </div>
                    <div class="price_3"><a href="#">立即购买</a></div>
                    <div class="clean"></div>
                </div>
            </div>
        </li>
        <li><div style="border:#E1E0DF solid 1px;">
                <a href="#"><img src="images/1.jpg" height="267" width="267"/></a>
                <p><a href="#">蓝月亮3000洗衣液-薰+2000洁净洗衣液-自然+500手洗翻盖*2+1000洗衣液</a></p>
                <div class="price_zone">
                    <div class="price_1">
                        <h4><span>￥</span>118</h4>
                        <div class="price_2">
                            <span class="jia">省39.5</span>
                            <span class="p-del"><em>￥</em>9.5</span>
                        </div>
                    </div>
                    <div class="price_3"><a href="#">立即购买</a></div>
                    <div class="clean"></div>
                </div>
            </div>
        </li>
    </ul>
    <div class="clean"></div>
</div>
</div>
<div class="shipin">
<div class="part_2">
    <div class="foot_title">
        <img src="images/20_03.jpg" height="42" width="25"/>
        <h3>母婴用品</h3>
    </div>
    <span><a href="#">更多>></a></span>
    <div class="clean"></div>
</div>
<div class="foot_li">
    <ul>
        <li><div style="border:#E1E0DF solid 1px;">
                <a href="#"><img src="images/1.jpg" height="267" width="267"/></a>
                <p><a href="#">蓝月亮3000洗衣液-薰+2000洁净洗衣液-自然+500手洗翻盖*2+1000洗衣液</a></p>
                <div class="price_zone">
                    <div class="price_1">
                        <h4><span>￥</span>118</h4>
                        <div class="price_2">
                            <span class="jia">省39.5</span>
                            <span class="p-del"><em>￥</em>9.5</span>
                        </div>
                    </div>
                    <div class="price_3"><a href="#">立即购买</a></div>
                    <div class="clean"></div>
                </div>
            </div>
        </li>
        <li><div style="border:#E1E0DF solid 1px;">
                <a href="#"><img src="images/1.jpg" height="267" width="267"/></a>
                <p><a href="#">蓝月亮3000洗衣液-薰+2000洁净洗衣液-自然+500手洗翻盖*2+1000洗衣液</a></p>
                <div class="price_zone">
                    <div class="price_1">
                        <h4><span>￥</span>118</h4>
                        <div class="price_2">
                            <span class="jia">省39.5</span>
                            <span class="p-del"><em>￥</em>9.5</span>
                        </div>
                    </div>
                    <div class="price_3"><a href="#">立即购买</a></div>
                    <div class="clean"></div>
                </div>
            </div>
        </li>
        <li><div style="border:#E1E0DF solid 1px;">
                <a href="#"><img src="images/1.jpg" height="267" width="267"/></a>
                <p><a href="#">蓝月亮3000洗衣液-薰+2000洁净洗衣液-自然+500手洗翻盖*2+1000洗衣液</a></p>
                <div class="price_zone">
                    <div class="price_1">
                        <h4><span>￥</span>118</h4>
                        <div class="price_2">
                            <span class="jia">省39.5</span>
                            <span class="p-del"><em>￥</em>9.5</span>
                        </div>
                    </div>
                    <div class="price_3"><a href="#">立即购买</a></div>
                    <div class="clean"></div>
                </div>
            </div>
        </li>
        <li><div style="border:#E1E0DF solid 1px;">
                <a href="#"><img src="images/1.jpg" height="267" width="267"/></a>
                <p><a href="#">蓝月亮3000洗衣液-薰+2000洁净洗衣液-自然+500手洗翻盖*2+1000洗衣液</a></p>
                <div class="price_zone">
                    <div class="price_1">
                        <h4><span>￥</span>118</h4>
                        <div class="price_2">
                            <span class="jia">省39.5</span>
                            <span class="p-del"><em>￥</em>9.5</span>
                        </div>
                    </div>
                    <div class="price_3"><a href="#">立即购买</a></div>
                    <div class="clean"></div>
                </div>
            </div>
        </li>
        <li><div style="border:#E1E0DF solid 1px;">
                <a href="#"><img src="images/1.jpg" height="267" width="267"/></a>
                <p><a href="#">蓝月亮3000洗衣液-薰+2000洁净洗衣液-自然+500手洗翻盖*2+1000洗衣液</a></p>
                <div class="price_zone">
                    <div class="price_1">
                        <h4><span>￥</span>118</h4>
                        <div class="price_2">
                            <span class="jia">省39.5</span>
                            <span class="p-del"><em>￥</em>9.5</span>
                        </div>
                    </div>
                    <div class="price_3"><a href="#">立即购买</a></div>
                    <div class="clean"></div>
                </div>
            </div>
        </li>
        <li><div style="border:#E1E0DF solid 1px;">
                <a href="#"><img src="images/1.jpg" height="267" width="267"/></a>
                <p><a href="#">蓝月亮3000洗衣液-薰+2000洁净洗衣液-自然+500手洗翻盖*2+1000洗衣液</a></p>
                <div class="price_zone">
                    <div class="price_1">
                        <h4><span>￥</span>118</h4>
                        <div class="price_2">
                            <span class="jia">省39.5</span>
                            <span class="p-del"><em>￥</em>9.5</span>
                        </div>
                    </div>
                    <div class="price_3"><a href="#">立即购买</a></div>
                    <div class="clean"></div>
                </div>
            </div>
        </li>
        <li><div style="border:#E1E0DF solid 1px;">
                <a href="#"><img src="images/1.jpg" height="267" width="267"/></a>
                <p><a href="#">蓝月亮3000洗衣液-薰+2000洁净洗衣液-自然+500手洗翻盖*2+1000洗衣液</a></p>
                <div class="price_zone">
                    <div class="price_1">
                        <h4><span>￥</span>118</h4>
                        <div class="price_2">
                            <span class="jia">省39.5</span>
                            <span class="p-del"><em>￥</em>9.5</span>
                        </div>
                    </div>
                    <div class="price_3"><a href="#">立即购买</a></div>
                    <div class="clean"></div>
                </div>
            </div>
        </li>
        <li><div style="border:#E1E0DF solid 1px;">
                <a href="#"><img src="images/1.jpg" height="267" width="267"/></a>
                <p><a href="#">蓝月亮3000洗衣液-薰+2000洁净洗衣液-自然+500手洗翻盖*2+1000洗衣液</a></p>
                <div class="price_zone">
                    <div class="price_1">
                        <h4><span>￥</span>118</h4>
                        <div class="price_2">
                            <span class="jia">省39.5</span>
                            <span class="p-del"><em>￥</em>9.5</span>
                        </div>
                    </div>
                    <div class="price_3"><a href="#">立即购买</a></div>
                    <div class="clean"></div>
                </div>
            </div>
        </li>
        <li><div style="border:#E1E0DF solid 1px;">
                <a href="#"><img src="images/1.jpg" height="267" width="267"/></a>
                <p><a href="#">蓝月亮3000洗衣液-薰+2000洁净洗衣液-自然+500手洗翻盖*2+1000洗衣液</a></p>
                <div class="price_zone">
                    <div class="price_1">
                        <h4><span>￥</span>118</h4>
                        <div class="price_2">
                            <span class="jia">省39.5</span>
                            <span class="p-del"><em>￥</em>9.5</span>
                        </div>
                    </div>
                    <div class="price_3"><a href="#">立即购买</a></div>
                    <div class="clean"></div>
                </div>
            </div>
        </li>
        <li><div style="border:#E1E0DF solid 1px;">
                <a href="#"><img src="images/1.jpg" height="267" width="267"/></a>
                <p><a href="#">蓝月亮3000洗衣液-薰+2000洁净洗衣液-自然+500手洗翻盖*2+1000洗衣液</a></p>
                <div class="price_zone">
                    <div class="price_1">
                        <h4><span>￥</span>118</h4>
                        <div class="price_2">
                            <span class="jia">省39.5</span>
                            <span class="p-del"><em>￥</em>9.5</span>
                        </div>
                    </div>
                    <div class="price_3"><a href="#">立即购买</a></div>
                    <div class="clean"></div>
                </div>
            </div>
        </li>
        <li><div style="border:#E1E0DF solid 1px;">
                <a href="#"><img src="images/1.jpg" height="267" width="267"/></a>
                <p><a href="#">蓝月亮3000洗衣液-薰+2000洁净洗衣液-自然+500手洗翻盖*2+1000洗衣液</a></p>
                <div class="price_zone">
                    <div class="price_1">
                        <h4><span>￥</span>118</h4>
                        <div class="price_2">
                            <span class="jia">省39.5</span>
                            <span class="p-del"><em>￥</em>9.5</span>
                        </div>
                    </div>
                    <div class="price_3"><a href="#">立即购买</a></div>
                    <div class="clean"></div>
                </div>
            </div>
        </li>
        <li><div style="border:#E1E0DF solid 1px;">
                <a href="#"><img src="images/1.jpg" height="267" width="267"/></a>
                <p><a href="#">蓝月亮3000洗衣液-薰+2000洁净洗衣液-自然+500手洗翻盖*2+1000洗衣液</a></p>
                <div class="price_zone">
                    <div class="price_1">
                        <h4><span>￥</span>118</h4>
                        <div class="price_2">
                            <span class="jia">省39.5</span>
                            <span class="p-del"><em>￥</em>9.5</span>
                        </div>
                    </div>
                    <div class="price_3"><a href="#">立即购买</a></div>
                    <div class="clean"></div>
                </div>
            </div>
        </li>
    </ul>
    <div class="clean"></div>
</div>
</div>
</div>

<div class="clear"></div>
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
</body>
</html>