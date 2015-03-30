<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{BrowserTitle}--商品搜索</title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <meta name="keywords" content="{BrowserKeywords}" />
    <meta name="description" content="{BrowserDescription}" />
    <link href="/images/common.css" rel="stylesheet" type="text/css" />
    <link href="/images/common_css.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/common.js"></script>
    <script type="text/javascript" src="/front_js/user/user.js"></script>
    <script type="text/javascript" src="/front_js/user/user_car.js"></script>
    <script type="text/javascript">
        $(function () {
            //根据不同的排序字段顺序显示产品列表
            $('.price-2 a').attr("class", "listup");
            var ps = Request["ps"];
            if(ps==null||ps=="") ps="12";
            var url = "/default.php?&mod=product&a=search&site_id={SiteId}&search_key={SearchKey}&p=1&ps="+ps;
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

<div class="box1200">
    <div class="myseatnew">
        <a href="/">首页</a> &gt; 搜索</div>
</div>
<div class="box1200">
<div class="box194 fl">
    <div class="similar_hot">
        <ul class="title">
            <div class="fl">热销榜</div>
        </ul><div class="clear"> </div>
        <ul>
            <icms id="product_sale_count" type="product_list" where="SaleCount" top="5">
                <item>
                    <![CDATA[
                    <li > <a class="pic" href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank" ><img src="{f_UploadFileThumbPath3}" width="90px" height="90px" ></a>
                        <p><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank" >{f_ProductName}<font class="cleb6100 ml5">{f_ProductIntro}</font></a>  </p>现价: ￥{f_SalePrice}</li>
                    ]]>
                </item>
            </icms>
        </ul>
    </div>
    <div class="blank10"></div>
    <div class="looked" >
        <div class="title">
            <span class="fl">最近浏览</span><a href="javascript:;" id="hrefClear"  class="fr mr5" >清空</a></div>
        <div class="clear">    </div>
        <ul id="explore_list">
            <icms id="user_explore_1" type="user_explore_list" top="3">
                <item>
                    <![CDATA[
                    <li><a class="lookpic" href="{f_Url}" target="_blank" title="">
                            <img src="{f_TitlePic}" width="60" height="60" style="display: inline; "></a>
                        <div class="lookmis">
                            <p class="lookname">
                                <a href="{f_Url}" target="_blank" title="">{f_Title}</a></p><p>￥{f_Price} </p></div>
                    </li>    <div class="clear">    </div>
                    ]]>
                </item>
            </icms>
        </ul>
    </div>
</div>
<div class="box990 fr"><div class="banner01"><a href="#" target="_blank"><img src="images/banner01.gif" width="990" height="105" /></a></div>
<div class="blank20">        </div>

<div class="list_tit"><div class="eb6100">搜索出相关商品<span>{product_page_search_item_count}</span>款
        <a name="product_list_anchor"></a></div>
</div>
<div class="clear"> </div>
<div class="sort">
    <dl class="pxlist" style="padding-left:20px;">
        <dt>排序：</dt>
        <dd>
            <div class="price-2"  style="width: 360px"> <a href="javascript:;" title="default" class="listup on"  style="background-image:none;padding-left:10px">默认</a> <a href="javascript:;" class="listup"  title="time"  >时间</a> <a href="javascript:;"  title="sale"  class="listup"  >销量</a> <a href="javascript:;"   title="price" class="listup" >价格</a> <a href="javascript:;"   title="comment" hidefocus="true" class="listup"  >评论</a>
            </div>
            <div  class="pags1" style="line-height: 28px;"><div class="diggtop p0">      </div></div>
            <span class="clear"></span>
        </dd>
    </dl>
</div>
<div class="clear"> </div>
<div class="listgooods" id="product_list" style=" width:990px;">
    <ul>
    <icms id="product_page_search" type="product_list" top="12" item_splitter_count="4">
        <item>
            <![CDATA[
            <li id="p_0">
                <div class="mt25">
                    <a class="pic" target="_blank"
                       href="/default.php?&mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}">
                        <img class="img150150" width="196" height="196" style="width: 196px; height: 196px"
                             src="{f_UploadFileThumbPath2}">
                    </a>
                </div>
                <div class="name">
                    <a target="_blank"
                       href="/default.php?&mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}">{f_ProductName}<font
                            class="cleb6100 ml5">{f_ProductIntro}</font> </a>
                </div>
                <div class="price" style="color:#eb6100;">
                    ￥{f_SalePrice}
                    <span>已有{f_ProductCommentCount}人评价</span><br/><font class="pricenew">原价：￥{f_MarketPrice}</font>
                </div>
                <a class="sclist" onclick="AddWiths({f_ProductId})" hidefocus="true" href="#"> 收藏</a>
            </li>
            ]]>
        </item>
        <item_splitter>
            <![CDATA[
            </ul>
            <div class="line"></div>
            <ul>
                ]]>
        </item_splitter>
    </icms>
    </ul>
    <div class="line"></div>
</div>
<div style="float: right;">
    {product_page_search_pager_button}
    <div class="clear"></div>
</div>
<div class="blank20">        </div>
</div>
</div>
<div class="clear"></div>
<div class="clear"></div>
<pre_temp id="8"></pre_temp>
</body>
</html>