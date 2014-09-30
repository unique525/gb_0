<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>无标题文档</title>
    <link href="/images/common.css" rel="stylesheet" type="text/css" />
    <link href="/images/common_css.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/front_js/common.js"></script>
    <script type="text/javascript ">
        var productTempList='<li id="p_0">'
            +'<div class="mt25">'
            +'<a class="pic" target="_blank" href="#">'
            +'<img class="img150150" width="196" height="196" style="width: 196px; height: 196px"  src="images/3_14.gif">'
            +'</a>'
            +'</div>'
            +'<div class="name">'
            +'<a  target="_blank" href="#">{f_ProductName}<font class="cleb6100 ml5">使用有机肥 人工除草 不使用化学农药</font> </a>'
            +'</div>'
            +'<div class="price" style="color:#eb6100;">'
            +'￥36.00'
            +'<span >已有13608人评价</span><br/><font class="pricenew">原价：￥11.60</font>'
            +'</div>'
            +'<div >'
            +'<input  class="textgt" type="text" maxlength="3" value="1">'
            +'<div class="onup">'
            +'<span class="up" ></span>'
            +'<span class="dow"> </span>'
            +'</div>'
            +'<input style=" margin:0 13px;"  class="butn1 fl mt5" type="button" onclick="c1.AddProductCart(\'#textgtSum_0\' ,37693,\'36.00\',\'0102022101C\',\'1002\')" name="btnbutton" value="">'
            +'<a class="sclist"  onclick="AddWiths(37693)" hidefocus="true" href="#"> 收藏</a>'
            +'</div>'
            +'</li>';
        function getProductList(pageIndex,pageSize,channelId,order,searchKey){
            $.ajax({
                url:"/default.php?mod=product",
                data:{a:"ajax_list",channel_id:channelId,order:order,search_key:searchKey,p:pageIndex,ps:pageSize},
                dataType:"jsonp",
                jsonp:"jsonpcallback",
                success:function(data){
                    var result = data["result"];
                    var productListHtml="<ul>";
                    $.each(result,function(i,v){
                        if(i+1==result.length)//到了最后一个
                        {
                            productListHtml += productTempList+'<div class="line"></div></ul>'
                        }
                        else if((i+1)%4)//不是每行最后一个
                        {
                            productListHtml += productTempList;
                        }
                        else //每行最后一个
                        {
                            productListHtml += productTempList+'</ul><div class="line"></div><ul>'
                        }
                        productListHtml=productListHtml.replaceAll("{f_ProductName}",v["ProductName"]);
                    });
                    $("#product_list").html('<div class="clear"></div>'+productListHtml);
                }
            });
        }
        //根据不同的排序字段顺序显示产品列表
        function showProductListWithOrder(order) {
            if (order != "default") {//默认排序以外其他排序方式
                var nextClassName = $(this).attr("class");
                nextClassName = (nextClassName == "listup" ? "listdown" : "listup");
                $(this).attr("class", nextClassName);
                var direction = (nextClassName == "listup" ? "asc" : "desc");
                getProductList(1, 12, {ChannelId}, order + "_" + direction, "");
            }
            else getProductList(1, 12, {ChannelId}, "" , "");
        }
        $(function(){
            getProductList(1,12,{ChannelId},"","");
        });
    </script>
</head>
<body>
<div class="loginbg">
    <div class="wrapper">
        <div class="loginleft">您好，欢迎来到星滋味    请<a href="">登陆</a>    <a href="">免费注册</a></div>
        <div class="loginright"><a href="">我的星滋味</a>    <a href="">收藏本站</a></div>
    </div>
</div>
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
        <div class="shopping"><span>购物车</span></div>
        <div class="number">0</div>
    </div>
</div>
<div class="clean"></div>
<div class="mainbav">
    <div class="wrapper">
        <div class="goods"><span>所有商品分类</span></div>
        <div class="column1"><a href="">首页</a></div>
        <div class="column2"><a href="">超市量贩</a></div>
        <div class="column2"><a href="">团购</a></div>
        <div class="column2"><a href="">最新预售</a></div>
        <div class="new"><img src="images/icon_new.png" width="29" height="30" /></div>
    </div>
</div>
<div class="box1200">
    <div class="myseatnew">
        <a href="#">首页</a> &gt; <a href="#">蔬菜</a> &gt; 本来精选绿芦笋300g 肉质细嫩，口味香郁</div>
</div>
<div class="box1200">
<div class="box194 fl">
    <!--小类列表菜单-->
    <div class="listsum" id="categoryListSum">
        <div class="tit">蔬菜水果</div>
        <div class="listsum-1" >
            <dl class="listhover">
                <dt><a class="on" hidefocus="true">蔬菜</a></dt>
                <dd><ul>
                        <li><a href="#" class="" title="豆角类">豆角类</a></li>
                        <li><a href="#" class="" title="叶菜类">叶菜类</a></li>
                        <li><a href="#" class="" title="根茎类">根茎类</a></li>
                        <li><a href="#" class="" title="茄果类">茄果类</a></li>
                        <li><a href="#" class="" title="瓜类">瓜类</a></li>
                        <li><a href="#" class="" title="香辛类·葱姜蒜">香辛类·葱</a></li>
                        <li><a href="#" class="" title="豆角·豆类">豆角·豆</a></li>
                        <li><a href="#" class="" title="菌菇类">菌菇类</a></li>
                        <li><a href="#" class="" title="芽菜类">芽菜类</a></li>
                        <li><a href="#" class="" title="速冻蔬菜">速冻蔬</a></li>
                    </ul>
                    <div class="clear"></div></dd></dl></div>
        <div class="listsum-1" ><dl class="">
                <dt><a class="" href="#" hidefocus="true">水果</a></dt>
                <dd><ul>
                        <li><a href="#" class="" title="台湾水果">台湾水果</a></li>
                        <li><a href="#" class="" title="国产水果">国产水果</a></li>
                        <li><a href="#" class="#" title="进口水果">进口水果</a></li></ul>
                    <div class="clear"></div></dd></dl></div></div>
    <div class="blank10">        </div>
    <div class="similar_hot">
        <ul class="title">
            <div class="fl">热销榜</div>
        </ul><div class="clear"> </div>
        <ul>
            <li > <a class="pic" href="#" target="_blank" ><img src="images/1_10.gif" ></a>
                <p><a href="#" target="_blank" >智利进口加力苹果1kg（盒装8粒）<font class="cleb6100 ml5">果胶和钾含量居水果之首，记忆力之果</font></a>  </p>现价: ￥42.80</li>
            <li > <a class="pic" href="#" target="_blank" ><img src="images/1_10.gif" ></a>
                <p><a href="#" target="_blank" >智利进口加力苹果1kg（盒装8粒）<font class="cleb6100 ml5">果胶和钾含量居水果之首，记忆力之果</font></a>  </p>现价: ￥42.80</li>
            <li > <a class="pic" href="#" target="_blank" ><img src="images/1_10.gif" ></a>
                <p><a href="#" target="_blank" >智利进口加力苹果1kg（盒装8粒）<font class="cleb6100 ml5">果胶和钾含量居水果之首，记忆力之果</font></a>  </p>现价: ￥42.80</li>
            <li > <a class="pic" href="#" target="_blank" ><img src="images/1_10.gif" ></a>
                <p><a href="#" target="_blank" >智利进口加力苹果1kg（盒装8粒）<font class="cleb6100 ml5">果胶和钾含量居水果之首，记忆力之果</font></a>  </p>现价: ￥42.80</li>
            <li > <a class="pic" href="#" target="_blank" ><img src="images/1_10.gif" ></a>
                <p><a href="#" target="_blank" >智利进口加力苹果1kg（盒装8粒）<font class="cleb6100 ml5">果胶和钾含量居水果之首，记忆力之果</font></a>  </p>现价: ￥42.80</li>
        </ul>
    </div>
    <div class="blank10">        </div>
    <div class="looked" >
        <div class="title">
            <span class="fl">最近浏览</span><a href="#"  class="fr mr5" >清空</a></div>
        <div class="clear">    </div>
        <ul >
            <li><a class="lookpic" href="#" target="_blank" title="">
                    <img src="images/2_14.gif" style="display: inline; "></a>
                <div class="lookmis">
                    <p class="lookname">
                        <a href="#" target="_blank" title="">【进店必败】新西兰佳沛绿果）<font class="cleb6100 ml5">低热量，高营养，酸甜爽口</font></a></p><p>￥18.50 </p></div>
            </li>    <div class="clear">    </div>
            <li><a class="lookpic" href="#" target="_blank" title=""><img src="images/2_14.gif" style="display: inline; " /></a>
                <div class="lookmis">
                    <p class="lookname">
                        <a href="#" target="_blank" title="">【进店必败】新西兰佳沛绿果猕猴桃450g（盒装6粒）<font class="cleb6100 ml5">低热量，高营养，酸甜爽口</font></a></p><p>￥18.50 </p></div>
            </li>    <div class="clear">    </div>
            <li><a class="lookpic" href="#" target="_blank" title=""><img src="images/2_14.gif" style="display: inline; " /></a>
                <div class="lookmis">
                    <p class="lookname">
                        <a href="#" target="_blank" title="">【进店必败】新西兰佳沛绿果猕猴桃450g（盒装6粒）<font class="cleb6100 ml5">低热量，高营养，酸甜爽口</font></a></p><p>￥18.50 </p></div>
            </li>    <div class="clear">    </div>
        </ul>

    </div>
</div>
<div class="box990 fr"><div class="banner01"><a href="#" target="_blank"><img src="images/banner01.gif" width="990" height="105" /></a></div>
<div class="blank20">        </div>
<div class="favorcombcont" style="background:url(images/hot_03.gif) no-repeat top left;">
    <div style=" font-size:18px;font:400 20px ;padding-left:25px; line-height:60px; font-family: '微软雅黑';"> 特别推荐</div><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td><table width="310" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="120" align="left" valign="top"><a href="#" target="_blank"><img src="images/hot_02.gif" width="110" height="110" /></a></td>
                        <td align="left" valign="top"><div class="listgooods"><div class="hot">
                                    <a  target="_blank" href="#">（兑换专用）云南蒙自石榴（6粒装） 0g<font class="cleb6100 ml5">使用有机肥 人工除草 不使用化学农药</font> </a>
                                </div>
                                <div class="jg" style="color:#eb6100;">
                                    ￥36.00
                                    <span >原价：￥11.60</span>
                                </div>
                                <div >
                                    <input  class="textgt" type="text" maxlength="3" value="1">
                                    <div class="onup">
                                        <span class="up" ></span>
                                        <span class="dow"> </span>
                                    </div>
                                    <input style=" margin:0 13px;"  class="butn1 fl mt5" type="button" onclick="c1.AddProductCart('#textgtSum_0' ,37693,'36.00','0102022101C','1002')" name="btnbutton" value="">
                                </div></div></td>
                    </tr>
                </table></td>
            <td><table width="310" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="120" align="left" valign="top"><a href="#" target="_blank"><img src="images/hot_02.gif" width="110" height="110" /></a></td>
                        <td align="left" valign="top"><div class="listgooods"><div class="hot">
                                    <a  target="_blank" href="#">（兑换专用）云南蒙自石榴（6粒装） 0g<font class="cleb6100 ml5">使用有机肥 人工除草 不使用化学农药</font> </a>
                                </div>
                                <div class="jg" style="color:#eb6100;">
                                    ￥36.00
                                    <span >原价：￥11.60</span>
                                </div>
                                <div >
                                    <input  class="textgt" type="text" maxlength="3" value="1">
                                    <div class="onup">
                                        <span class="up" ></span>
                                        <span class="dow"> </span>
                                    </div>
                                    <input style=" margin:0 13px;"  class="butn1 fl mt5" type="button" onclick="c1.AddProductCart('#textgtSum_0' ,37693,'36.00','0102022101C','1002')" name="btnbutton" value="">
                                </div></div></td>
                    </tr>
                </table></td>
            <td><table width="310" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="120" align="left" valign="top"><a href="#" target="_blank"><img src="images/hot_02.gif" width="110" height="110" /></a></td>
                        <td align="left" valign="top"><div class="listgooods"><div class="hot">
                                    <a  target="_blank" href="#">（兑换专用）云南蒙自石榴（6粒装） 0g<font class="cleb6100 ml5">使用有机肥 人工除草 不使用化学农药</font> </a>
                                </div>
                                <div class="jg" style="color:#eb6100;">
                                    ￥36.00
                                    <span >原价：￥11.60</span>
                                </div>
                                <div >
                                    <input  class="textgt" type="text" maxlength="3" value="1">
                                    <div class="onup">
                                        <span class="up" ></span>
                                        <span class="dow"> </span>
                                    </div>
                                    <input style=" margin:0 13px;"  class="butn1 fl mt5" type="button" onclick="c1.AddProductCart('#textgtSum_0' ,37693,'36.00','0102022101C','1002')" name="btnbutton" value="">
                                </div></div></td>
                    </tr>
                </table></td>
        </tr>
    </table>
</div>
<div class="clear"> </div>
<div class="list_tit"><h1>{ChannelName}</h1> <div class="eb6100">相关商品<span>238</span>款
    </div>
</div>
<div class="clear"> </div>
<div class="sort">
    <dl class="pxlist" style="padding-left:20px;">
        <dt>排序：</dt>
        <dd>
            <div class="price-2"  style="width: 360px">
                <a href="javascript:;" onclick="showProductListWithOrder('default')" class="listup on"  style="background-image:none;padding-left:10px"  >默认</a> <a href="javascript:;" class="listup" onclick="showProductListWithOrder('time')"  >时间</a> <a href="javascript:;" onclick="showProductListWithOrder('sale')"  class="listup"  >销量</a> <a href="javascript:;"  onclick="showProductListWithOrder('price')" class="listup" >价格</a> <a href="javascript:;"  onclick="showProductListWithOrder('comment')" hidefocus="true" class="listup"  >评论</a>
            </div>
            <div  class="pags1" style="line-height: 28px;"><div class="diggtop p0">    <a href="#">&lt;</a>    2/9    <a class="disabledr02" href="#" >下一页&nbsp;&gt; </a>    </div></div>
            <span class="clear"></span>
        </dd>
    </dl>
</div>
<div class="clear"> </div>
<div class="listgooods" id="product_list" style=" width:990px;">
</div>
<div class="pags" style="margin:30px 0px;">
    <div class="pdr15 fr" style="width: 165px;">
        <input  class="butn16 fr ml10" type="button" value="">
        <div class="fr"> 页 </div>
        <div class="fr">
            <input  class="pagtext" type="text"  value="1">
        </div>
        <div class="fr"> 转至 </div>
    </div>
    <div class="fr eb6100"></div>
    <div class="fr pdr15">共50页</div>
    <div class="digg">
        <a class="disabledl02" href="#">上一页</a>
        <span  class="current">1</span>
        <a  href="#">2</a>
        <a  href="#">3</a>
        <a class="dian">...</a>
        <a  href="#">50</a>
        <a class="disabledr" href="#">下一页</a>
    </div>
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