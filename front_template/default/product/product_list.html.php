<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{BrowserTitle}--{ChannelName}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <meta name="keywords" content="{BrowserKeywords}" />
    <meta name="description" content="{BrowserDescription}" />
    <link href="/images/common.css" rel="stylesheet" type="text/css" />
    <link href="/images/common_css.css" rel="stylesheet" type="text/css" />
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

<div class="box1200">
    <div class="myseatnew">
        <a href="/">首页</a> &gt; <a href="/default.php?&mod=product&a=list&channel_first_id={ChannelFirstId}&channel_id={ChannelId}">{ChannelName}</a></div>
</div>
<div class="box1200">
<div class="box194 fl">
    <!--小类列表菜单-->
    <div class="listsum" id="categoryListSum">
        <div class="tit">{ChannelFirstName}</div>
        <icms id="channel_{ChannelFirstId}" type="channel_list" where="parent">
            <item>
                <![CDATA[
                <div class="listsum-1">
                    <dl title="{f_ChannelName}">
                        <dt><a alt="{f_ChannelId}" href="/default.php?&mod=product&a=list&channel_first_id={ChannelFirstId}&channel_id={f_ChannelId}" hidefocus="true">{f_ChannelName}</a></dt>
                        <dd><ul>
                                {child}
                            </ul>
                            <div class="clear"></div>
                        </dd>
                    </dl>
                </div>
                ]]>
            </item>
            <child>
                <![CDATA[
                <li><a alt="{f_ChannelId}" title="{f_ChannelName}" href="/default.php?&mod=product&a=list&channel_first_id={ChannelFirstId}&channel_id={f_ChannelId}" class="">{f_ChannelName}</a></li>
                ]]>
            </child>
        </icms>
    </div>
    <div class="blank10">        </div>
    <div class="similar_hot">
        <ul class="title">
            <div class="fl">热销榜</div>
        </ul><div class="clear"> </div>
        <ul>
            <icms id="product_sale_count" type="product_list" where="SaleCount" top="5">
                <item>
                    <![CDATA[
                    <li > <a class="pic" href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank" ><img src="{f_UploadFileThumbPath3}" width="90px" height="90px" ></a>
                        <p><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank" >{f_ProductName}</a>  </p>现价: ￥{f_SalePrice}</li>
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
            <icms id="user_explore_1" type="user_explore_list" top="5">
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
<div class="favorcombcont" style="background:url(images/hot_03.gif) no-repeat top left;">
    <div style=" font-size:18px;font:400 20px ;padding-left:25px; line-height:60px; font-family: '微软雅黑';"> 特别推荐</div><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <icms id="product_6" type="product_list" where="RecLevel" top="3">
                <item>
                    <![CDATA[
                    <td>
                        <table width="310" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td width="120" align="left" valign="top"><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><img src="{f_UploadFileThumbPath2}" width="110" height="110" /></a></td>
                                <td align="left" valign="top">
                                    <div class="listgooods"><div class="hot">
                                            <a  target="_blank" href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}">{f_ProductName}</a>
                                        </div>
                                        <div class="jg" style="color:#eb6100;">
                                            <span >原价：￥{f_MarketPrice}</span>￥{f_SalePrice}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    ]]>
                </item>
            </icms>
        </tr>
    </table>
</div>
<div class="clear"> </div>
    <a name="product_list_anchor"></a>
<div class="list_tit"><h1>{ChannelName}</h1> <div class="eb6100">相关商品<span>{product_page_{ChannelId}_item_count}</span>款
    </div>
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
    <icms id="product_page_{ChannelId}" type="product_list" top="12" item_splitter_count="4">
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
                <div class="name" style="margin-top:5px;">
                    <a target="_blank" href="/default.php?&mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}">{f_ProductName}</a>
                </div>
                <div class="price" style="color:#eb6100;">
                    <a style="float:right;" target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=2957670343&site=qq&menu=yes"><img border="0" src="/images1/service.png" alt="联系客服" title="联系客服"/></a>
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
    {product_page_{ChannelId}_pager_button}
    <div class="clear"></div>
</div>
<div class="blank20">        </div>
</div>
</div>
<div class="clear"></div>
<div class="clear"></div>
<pre_temp id="8"></pre_temp>
<script type="text/javascript">var visitConfig = encodeURIComponent("{SiteUrl}") +"||{SiteId}||{ChannelId}||0||0||"+encodeURI("");</script><script type="text/javascript" src="/front_js/visit.js" charset="utf-8"></script>
</body>
</html>