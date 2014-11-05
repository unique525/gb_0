<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>星滋味--{ChannelName}</title>
    <link href="/images/common.css" rel="stylesheet" type="text/css" />
    <link href="/images/common_css.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/common.js"></script>
    <script type="text/javascript">
        $(function () {
            /* 顶部banner类别菜单初始化 */
            $('#leftmenu>ul>li>ul').find('li:has(ul:not(:empty))>a').append("<span class='arrow'>></span>"); // 为有子菜单的菜单项添加'>'符号
            $("#leftmenu>ul>li").bind('mouseover',function() // 顶级菜单项的鼠标移入操作
            {
                $(this).children('ul').css('display','');
            }).bind('mouseleave',function() // 顶级菜单项的鼠标移出操作
                {
                    $(this).children('ul').css('display','none');
                });
            $('#leftmenu>ul>li>ul li').bind('mouseover',function() // 子菜单的鼠标移入操作
            {
                $(this).children('ul').css('display','');
            }).bind('mouseleave',function() // 子菜单的鼠标移出操作
                {
                    $(this).children('ul').css('display','none');
                });

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
            //var channelid = Request["channel_id"];
            //$("#categoryListSum").find('dl').removeClass("listhover");
            //$("#categoryListSum div[class='listsum-1'] dl").removeClass("listhover");
            //$("#categoryListSum div[class='listsum-1'] dl[title='+channelid+']").addClass("listhover");

            //根据不同的排序字段顺序显示产品列表
            $('.price-2 a').attr("class", "listup");
            var ps = Request["ps"];
            if(ps==null||ps=="") ps="12";
            var url = "/default.php?&mod=product&a=list&channel_id={ChannelId}&p=1&ps="+ps;
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
        <div class="shopping"><span><a href="/default.php?mod=user_car&a=list" target="_blank"></a></span></div>
        <div class="number">0</div>
    </div>
</div>
<div class="clean"></div>
<div class="mainbav">
    <div class="wrapper">
        <div id="leftmenu">
            <ul>
                <li><span>所有商品分类</span>
                    <ul style="display: none;">
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
        <div class="column1"><a href="">首页</a></div>
        <div class="column2"><a href="">超市量贩</a></div>
        <div class="column2"><a href="">团购</a></div>
        <div class="column2"><a href="">最新预售</a></div>
        <div class="new"><img src="images/icon_new.png" width="29" height="30" /></div>
    </div>
</div>
<div class="box1200">
    <div class="myseatnew">
        <a href="#">首页</a> &gt; <a href="/default.php?&mod=product&a=list&channel_id={ChannelId}">{ChannelName}</a></div>
</div>
<div class="box1200">
<div class="box194 fl">
    <!--小类列表菜单-->
    <div class="listsum" id="categoryListSum">
        <div class="tit">{ChannelName}</div>
        <icms id="channel_{ChannelId}" type="channel_list" where="parent">
            <item>
                <![CDATA[
                <div class="listsum-1">
                    <dl title="{f_ChannelId}">
                        <dt><a href="javascript:;"  hidefocus="true">{f_ChannelName}</a></dt>
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
                <li><a href="/default.php?&mod=product&a=list&channel_id={f_ChannelId}" class="" title="{f_ChannelName}">{f_ChannelName}</a></li>
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
                    <li > <a class="pic" href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank" ><img src="{f_UploadFilePath}" width="90px" height="90px" ></a>
                        <p><a href="#" target="_blank" >{f_ProductName}<font class="cleb6100 ml5">果胶和钾含量居水果之首，记忆力之果</font></a>  </p>现价: ￥{f_SalePrice}</li>

                    ]]>
                </item>
            </icms>
        </ul>
    </div>
    <div class="blank10">        </div>
    <div class="looked" >
        <div class="title">
            <span class="fl">最近浏览</span><a href="javascript:;" id="hrefClear"  class="fr mr5" >清空</a></div>
        <div class="clear">    </div>
        <ul id="explore_list">
            <icms id="user_explore_1" type="user_explore_list" top="3">
                <item>
                    <![CDATA[
                    <li><a class="lookpic" href="#" target="_blank" title="">
                            <img src="{f_TitlePic}" width="60" height="60" style="display: inline; "></a>
                        <div class="lookmis">
                            <p class="lookname">
                                <a href="#" target="_blank" title="">【进店必败】{f_Title}）<font class="cleb6100 ml5">低热量，高营养，酸甜爽口</font></a></p><p>￥{f_Price} </p></div>
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
                                <td width="120" align="left" valign="top"><a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><img src="{f_UploadFilePath}" width="110" height="110" /></a></td>
                                <td align="left" valign="top">
                                    <div class="listgooods"><div class="hot">
                                            <a  target="_blank" href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}">{f_ProductName}<font class="cleb6100 ml5">使用有机肥 人工除草 不使用化学农药</font> </a>
                                        </div>
                                        <div class="jg" style="color:#eb6100;">
                                            ￥{f_SalePrice}
                                            <span >原价：￥{f_MarketPrice}</span>
                                        </div>
                                        <div class="btn_jrgwc">
                                            <a target="_blank" href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}"><img src="images/2_11.png" width="76" height="26" /></a>
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
<div class="list_tit"><h1>{ChannelName}</h1> <div class="eb6100">相关商品<span>238</span>款
    </div>
</div>
<div class="clear"> </div>
<div class="sort">
    <dl class="pxlist" style="padding-left:20px;">
        <dt>排序：</dt>
        <dd>
            <div class="price-2"  style="width: 360px">
                <a href="javascript:;" title="default" class="listup on"  style="background-image:none;padding-left:10px"  >默认</a> <a href="javascript:;" class="listup"  title="time"  >时间</a> <a href="javascript:;"  title="sale"  class="listup"  >销量</a> <a href="javascript:;"   title="price" class="listup" >价格</a> <a href="javascript:;"   title="comment" hidefocus="true" class="listup"  >评论</a>
            </div>
            <div  class="pags1" style="line-height: 28px;"><div class="diggtop p0">    <a href="#">&lt;</a>    2/9    <a class="disabledr02" href="#" >下一页&nbsp;&gt; </a>    </div></div>
            <span class="clear"></span>
        </dd>
    </dl>
</div>
<div class="clear"> </div>
<div class="listgooods" id="product_list" style=" width:990px;">
    <ul>
    <icms id="product_list" type="list" item_splitter_count="4">
        <item>
            <![CDATA[
            <li id="p_0">
                <div class="mt25">
                    <a class="pic" target="_blank"
                       href="/default.php?&mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}">
                        <img class="img150150" width="196" height="196" style="width: 196px; height: 196px"
                             src="{f_UploadFilePath}">
                    </a>
                </div>
                <div class="name">
                    <a target="_blank"
                       href="/default.php?&mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}">{f_ProductName}<font
                            class="cleb6100 ml5">使用有机肥 人工除草 不使用化学农药</font> </a>
                </div>
                <div class="price" style="color:#eb6100;">
                    ￥{f_SalePrice}
                    <span>已有13608人评价</span><br/><font class="pricenew">原价：￥{f_MarketPrice}</font>
                </div>
                <div class="btn_jrgwc">
                    <a target="_blank" href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}"><img src="images/2_11.png" width="76" height="26" /></a>
                </div>
                <a class="sclist" onclick="AddWiths(37693)" hidefocus="true" href="#"> 收藏</a>
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
    <div class="line"></div>
    </ul>
</div>
<div style="float: right;">
    {pager_button}
    <div class="clear"></div>
</div>

</div>
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