<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>星滋味--{ProductName}</title>
    <link href="/images/common.css" rel="stylesheet" type="text/css" />
    <link href="/images/common_css.css" rel="stylesheet" type="text/css" />
    <link type="text/css" href="/system_template/default/images/jquery_ui/jquery-ui.min.css" rel="stylesheet" />
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/front_js/common.js"></script>
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui-1.8.2.custom.min.js"></script>
    <script src="/front_js/jqzoom/js/jqzoom.js" type="text/javascript"></script>
    <script src="/front_js/roll/msclass.js" type="text/javascript"></script>
    <link rel="stylesheet" href="/front_js/jqzoom/css/jqzoom.css" type="text/css">
    <script type="text/javascript">
        var select_product_price_id = 0;
        $(function(){
            $(".jqzoom").jqzoom({
                offset:5,
                xzoom:350,
                yzoom:350,
                position:"right",
                preload:1,
                lens:1
            });
            //价格选择
            var spanPropon=$('.propon');
            spanPropon.click(function(){
                //产品促销价格
                var productPriceValue=$(this).attr("pricevalue");
                select_product_price_id = $(this).attr("idvalue");
                $("#productPrice").text(productPriceValue);
                //产品价格
                var productSalePriceValue=$("#productSalePrice").text();
                //优惠的差价
                var priceReduceValue=parseFloat(productSalePriceValue)-parseFloat(productPriceValue);
                $("#priceReduce").text(priceReduceValue);
                //价格选择切换效果
                spanPropon.attr("class","propon propondefault");//默认全部不选择
                $(this).attr("class","propon proponselect");//点击选中
            });
            //页面加载后默认选中第一个价格
            spanPropon.eq(0).click();
            //购买数量加减
            $("#dow").click(function () {
                ProductNumChange(-1)
            });
            $("#up").click(function () {
                ProductNumChange(1)
            });
            var inputProductNum=$("#productNum");
            inputProductNum.bind("keyup", function () {
                validateCount.call($(this));
            });
            inputProductNum.bind("blur", function () {
                validateCount.call($(this));
            });
            //产品缩略图滚动效果
            var scrollPic= new Marquee(
                {
                    MSClass	  : ["pic_smiddle","pic_smiddle_content"],
                    Direction : 4,
                    Step	  : 0.3,
                    Width	  : 312,
                    Height	  : 64,
                    Timer	  : 20,
                    DelayTime : 3000,
                    WaitTime  : 100000,
                    ScrollStep: 60,
                    SwitchType: 0,
                    AutoStart : true
                });
            var leftRollBtn=$("#pic_sr");
            var rightRollBtn=$("#pic_sl");
            leftRollBtn.click(function () {
                scrollPic.Run("Left");
            });
            rightRollBtn.click(function () {
                scrollPic.Run("Right");
            });
            //rightRollBtn.css("display","none");//将按钮置为不可点击(样式)
            //滚动至边界做相应处理，切换按钮状态(样式)
            scrollPic.OnBound = function()
            {
                if(scrollPic.Bound == 2)
                {
                    //leftRollBtn.css("display","none");
                }
                else
                {
                    //rightRollBtn.css("display","none");
                }
            };
            //不在边界正常滚动
            scrollPic.UnBound = function()
            {
                rightRollBtn.disabled = $("#pic_sl").disabled = false;
                //rightRollBtn.css("display","");
                //leftRollBtn.css("display","");
            };
            //产品小图鼠标经过显示大图
            $("#pic_smiddle_content li ").mouseover(function(){
                $(this).siblings().each(function(){
                    $("img", this).attr("class", "pic_default");
                });
                var rollImg = $("img", this);
                rollImg.attr("class", "pic_select");
                var originPic=rollImg.attr("originpic");
                var originThumb1pic=rollImg.attr("thumb1pic");
                $("#jqimg").attr("src",originThumb1pic).attr("longdesc",originPic);
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

            //产品详情展示TAB页切换
            $('#detail_desc_tab a').click(function () {
                $(this).siblings().each(function(){
                    $(this).attr("class", "tab");
                });
                $(this).attr("class", "tab cur");
                $(".gdmsgcont").css("display", "none");
                $(".gdmsgcont1").css("display", "none");
                var num = $(this).attr("alt");
                if(num!=3){//不对评论显示控制，评论默认都显示
                    $("#tabdiv"+num).css("display", "block");
                }

            });

            $("#add_car").click(function(){
                var buyCount = $("").val();
                var activity_product_id = 0;
                if(select_product_price_id  > 0){
                    addCar('{SiteId}','{ProductId}',buyCount,select_product_price_id,activity_product_id);
                }
            });
        });
        //产品数量增减
        function ProductNumChange(changeNum) {
            var inputProductNum=$("#productNum");
            var productNum = inputProductNum.val();
            if (Number(productNum)) {
                productNum = parseInt(productNum) + parseInt(changeNum);
                if (productNum == 0) {
                    productNum = 1
                }
                inputProductNum.val(productNum)
            } else {
                if (changeNum > 0) {
                    inputProductNum.val("1")
                } else {
                    inputProductNum.val("1")
                }
            }
        }
        //产品购买数量数字验证
        function validateCount() {
            var intreg = /^\d+$/;
            var sSum = this.val();
            if ("0" == sSum || !intreg.test(sSum)) {
                this.val("1");
            }
        }
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
        <div class="number" id="user_car_count">0</div>
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
        <a href="#">首页</a> &gt; <a href="#">蔬菜</a> &gt; {ProductName}</div>
</div>
<div class="box1200">
<div class="box194 fl">
    <!--小类列表菜单-->
    <div class="listsum" id="categoryListSum">
        <div class="tit">蔬菜水果</div>
        <div class="listsum-1" >
            <dl class="listhover">
                <dt><a href="javascript:;" class="on" hidefocus="true">蔬菜</a></dt>
                <dd><ul>
                        <li><a href="http://localhost/default.php?&mod=product&a=list&channel_id=109">豆角类</a></li>
                        <li><a href="http://localhost/default.php?&mod=product&a=list&channel_id=110" class="" title="叶菜类">叶菜类</a></li>
                    </ul>
                    <div class="clear"></div>
                </dd>
            </dl>
        </div>
        <div class="listsum-1" >
            <dl class="">
                <dt><a class="" href="#" hidefocus="true">水果</a></dt>
                <dd><ul>
                        <li><a href="http://localhost/default.php?&mod=product&a=list&channel_id=111">进口水果</a></li>
                        <li><a href="http://localhost/default.php?&mod=product&a=list&channel_id=112" class="" title="国产水果">国产水果</a></li>
                    </ul>
                    <div class="clear"></div>
                </dd>
            </dl>
        </div>
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
                    <li > <a class="pic" href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank" ><img src="images/1_10.gif" ></a>
                        <p><a href="#" target="_blank" >{f_ProductName}<font class="cleb6100 ml5">果胶和钾含量居水果之首，记忆力之果</font></a>  </p>现价: ￥{f_SalePrice}</li>

                    ]]>
                </item>
            </icms>
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
<div class="box990  fr">
<div class="goodstop"><table width="100%" cellpadding="0" cellspacing="0" >
        <tr>
            <td align="center" valign="top" class="goodstopl" >
                <div id="magnifier" class="magnifier">
                    <div id="BigImage" class="jqzoom" style="border:1px solid #ccc">
                        <img id="jqimg" src="http://image2.benlailife.com/ProductImage/0301030231C.jpg" width="350" longdesc="http://image2.benlailife.com/ProductBigImage/0301030231C.jpg"/>
                    </div>
                </div>
                <div class="pic_small">
                    <span id="pic_sl" class="pic_sl"></span>
                    <div id="pic_smiddle" class="pic_smiddle">
                        <ul id="pic_smiddle_content">
                            <icms id="product_pic_{ProductId}" type="product_pic_list" top="8">
                                <item>
                                    <![CDATA[
                                    <li><img class="pic_default" src="http://image1.benlailife.com/ProductSmallImage/0102020675C.jpg" originpic="http://image2.benlailife.com/ProductBigImage/0102020675C.jpg" thumb1pic="http://image2.benlailife.com/ProductImage/0102020675C.jpg"  width="50"></li>
                                    ]]>
                                </item>
                            </icms>
                        </ul>
                    </div>
                    <span id="pic_sr" class="pic_sr"></span>
                    <div class="clear"> </div>
                </div>
            </td>
            <td width="40">&nbsp;</td>
            <td align="left" valign="top" class="goodstopr"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="left"> <h1 class="ft20">{ProductName}</h1></td>
                    </tr>
                    <tr>
                        <td align="left"><img src="images/2_03.gif" style="padding:15px 0px;" width="89" height="26" /></td>
                    </tr>
                    <tr>
                        <td align="left"><div class="goodstopr"><p class="price_n"><span class="newprice">限时促销价：<span id="productPrice" class="newprice"></span></span></p>
                                <p class="price_n">原　价：<span id="productSalePrice" class="oldprice" style="text-decoration: line-through">{SalePrice}</span></p>
                                <p class="price_n"><span class="chaprice">已优惠：<span id="priceReduce" style="padding-right: 5px; color:#ff3c00"></span></span></p></div>
                    </tr>
                    <tr>
                        <td align="left">
                            <div class="gdproperty_n">
                                <dl>
                                    <dt>品种/规格：</dt>
                                    <dd>
                                        <ul>
                                            <icms id="product_price_{ProductId}" type="product_price_list">
                                                <item>
                                                    <![CDATA[
                                                    <li><span class="propon propondefault" idvalue="{f_ProductPriceId}" pricevalue="{f_ProductPriceValue}">{f_ProductPriceIntro}</span></li>
                                                    ]]>
                                                </item>
                                            </icms>
                                        </ul>
                                    </dd>
                                </dl>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="font-size:14px;">
                            <div class=quantity>
                                <p>购买数量： </P>
                                <p><a hidefocus class=dow id="dow" href="#"><img
                                            src="images/dy_03.gif" width=15
                                            height=15></a> </p>
                                <P class=mt0><INPUT id=productNum class=textgt_n value=1 maxLength=4
                                                    type=text> </p>
                                <P><a hideFocus class=up id="up" href="#"><img
                                            src="images/dy_05.gif" width=15
                                            height=15></a> </p></div>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="padding:20px 0;"><span id="add_car" style="cursor: pointer"><img src="images/2_07.gif" width="155" height="36" /></span>　<a href="＃"><img src="images/2_09.gif" width="155" height="36" /></a></td>
                    </tr>
                    <tr>
                        <td align="left"  style="font-size:14px;" ><img src="images/2_22.gif" width="13" height="14" align="absmiddle" /> <a href="#">降价通知</a> 　<img src="images/2_24.gif" width="18" height="14" align="absmiddle" /> <span style="cursor:pointer" onclick="addFavorite('{ProductId}','{ProductName}','1','商品',{SiteId});">我要收藏</span></td>
                    </tr>
                    <tr>
                        <td align="left" style="padding:20px 0;">分享有礼　<a href="#"><img src="images/2_29.gif" width="16" height="16" /></a> <a href="#"><img src="images/2_31.gif" width="16" height="16" /></a> <a href="#"><img src="images/2_33.gif" width="16" height="16" /></a> <a href="#"><img src="images/2_35.gif" width="16" height="16" /></a> <a href="#"><img src="images/2_37.gif" width="16" height="16" /></a> <a href="#"><img src="images/2_39.gif" width="16" height="16" /></a> <a href="#"><img src="images/2_41.gif" width="16" height="16" /></a></td>
                    </tr>
                    <tr>
                        <td align="left" >&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="left">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
<div  class="baifenb" style="overflow: hidden;"><div class="favorcombcont">
        <div class="zi"> 商品推荐</div>
        <div class=" fctl">
            <ul>
                <icms id="product_rec_level" type="product_list" where="RecLevel" top="4">
                    <item>
                        <![CDATA[
                        <li > <a class="pic" href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank" ><img src="images/3_14.gif" ></a>
                            <p><a href="#" target="_blank" >{f_ProductName}</a>  </p>
                            ￥{f_SalePrice} <span>￥{f_MarketPrice}</span></li>
                        ]]>
                    </item>
                </icms>
            </ul>
        </div>
    </div></div><div class="clear">    </div>
<div class="gdmsg"><ul class="gdmsg_tab">
        <li id="detail_desc_tab" class="gdmsg_tabbox">
            <a  id="id_1" alt="1" class="tab cur" tabindex="0" href="javascript:;">商品介绍</a>
            <a  id="id_2" alt="2" class="tab"  href="javascript:;" tabindex="1">属性</a>
            <a  id="id_3" alt="3" class="tab"  href="javascript:;">评价
                <em>661</em>
            </a></li>
    </ul>
    <div id="tabdiv1" class="gdmsgcont" >
        <div class="dtl758">{ProductContent}</div>
    </div>
    <div id="tabdiv2" class="gdmsgcont1" style="display: none;">
        <icms id="product_param_type_class_{ChannelId}" type="product_param_type_class_list">
            <item>
                <![CDATA[
                <div class="paramone">{f_ProductParamTypeClassName}</div>
                    <icms_child id="product_param_type_{f_ProductParamTypeClassId}" relation_id="{ProductId}" type="product_param_type_list">
                        <item_child>
                            [CDATA]
                            <div class="paramtwo">
                                <div class="paramtwoleft">{f_ParamTypeName}：</div>
                                <div class="paramtworight">{f_ParamTypeValue}</div>
                            </div>
                            [/CDATA]
                        </item_child>
                    </icms_child>
                    <div class="clear"></div>
                ]]>
            </item>
        </icms>
        <div class="clear"></div>
    </div>
</div>
<div class="blank30">        </div>
<div class="gdcomment" id="tabdiv6">
    <div class="zi"> 商品评价</div>
    <div class="gdcomment-1">
        <div class="rate"><strong><span id="span_scoreCount">94%</span></strong><br>好评度</div>
        <div class="percent">
            <dl>
                <dt>好评</dt>
                <dd class="d1"><div id="div_VeryGood" style="width: 94%;"></div> </dd>
                <dd class="d2"><span id="Divspan_VeryGood">94%</span></dd></dl>
            <dl><dt>中评</dt><dd class="d1"><div id="div_Good" style="width: 3%;"></div></dd><dd class="d2"><span id="divspan_Good">3%</span></dd></dl>
            <dl><dt>差评</dt><dd class="d1"><div id="div_NoGood" style="width: 3%;"></div></dd><dd class="d2"><span id="divspan_NoGood">3%</span></dd></dl>
        </div>
    </div>
</div>
<div class="blank10">        </div>
<div class="box990  fr">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width="11%" align="center" valign="top" style="padding-top:10px;"><table width="70" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="70" height="70" align="center" valign="middle" background="images/5_25.gif"><a href="#"><img src="images/5_38.gif" width="50" height="50" /></a></td>
                    </tr>
                </table>
                ccddrrrrrrrrr</br>
                <span class="grenn">金牌会员</span></td>
            <td width="89%" align="left" valign="top">
                <table width="882" border="0" cellspacing="0" cellpadding="0">
                    <tr> </tr>
                    <tr>
                        <td background="images/5_22.gif" height="39" style="padding-left:30px;"><img src="images/5_42.gif" width="14" height="14" /><img src="images/5_42.gif" width="14" height="14" /><img src="images/5_42.gif" width="14" height="14" /><img src="images/5_42.gif" width="14" height="14" /><img src="images/5_42.gif" width="14" height="14" /><span style="float:right; padding-right:20px; color:#8b8a8a">2014-09-22 12:30</span></td>
                    </tr>
                    <tr>
                        <td background="images/5_29.gif" style="padding:20px 30px; font-size:14px; line-height:24px;">产品不错，值得推错，值得推错，值得推错，值得推错，值得推荐。 </td>
                    </tr>
                    <tr>
                        <td background="images/5_29.gif" style="padding-left:30px; padding-bottom:20px;"><a href="#"><img src="images/5_32.gif" width="61" height="22" /></a></td>
                    </tr>
                    <tr>
                        <td background="images/5_35.gif" height="1" style="padding-left:30px;"></td>
                    </tr>
                </table></td>
        </tr>
        <tr>
            <td height="10" colspan="2" align="center" valign="top" ></td>
        </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width="11%" align="center" valign="top" style="padding-top:10px;"><table width="70" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="70" height="70" align="center" valign="middle" background="images/5_25.gif"><img src="images/5_38.gif" width="50" height="50" /></td>
                    </tr>
                </table>
                ccddrrrrrrrrr</br>
                <span class="grenn">金牌会员</span></td>
            <td width="89%" align="left" valign="top">
                <table width="882" border="0" cellspacing="0" cellpadding="0">
                    <tr> </tr>
                    <tr>
                        <td background="images/5_22.gif" height="39" style="padding-left:30px;"><img src="images/5_42.gif" width="14" height="14" /><img src="images/5_42.gif" width="14" height="14" /><img src="images/5_42.gif" width="14" height="14" /><img src="images/5_42.gif" width="14" height="14" /><img src="images/5_42.gif" width="14" height="14" /><span style="float:right; padding-right:20px; color:#8b8a8a">2014-09-22 12:30</span></td>
                    </tr>
                    <tr>
                        <td background="images/5_29.gif" style="padding:20px 30px; font-size:14px; line-height:24px;">产品不错，值得推错，值得推错，值得推错，值得推错，值得推荐。 </td>
                    </tr>
                    <tr>
                        <td background="images/5_29.gif" style="padding-left:30px; padding-bottom:20px;"><a href="#"><img src="images/5_32.gif" width="61" height="22" /></a></td>
                    </tr>
                    <tr>
                        <td background="images/5_35.gif" height="1" style="padding-left:30px;"></td>
                    </tr>
                </table></td>
        </tr>
        <tr>
            <td height="10" colspan="2" align="center" valign="top" ></td>
        </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width="11%" align="center" valign="top" style="padding-top:10px;"><table width="70" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="70" height="70" align="center" valign="middle" background="images/5_25.gif"><img src="images/5_38.gif" width="50" height="50" /></td>
                    </tr>
                </table>
                ccddrrrrrrrrr</br>
                <span class="grenn">金牌会员</span></td>
            <td width="89%" align="left" valign="top">
                <table width="882" border="0" cellspacing="0" cellpadding="0">
                    <tr> </tr>
                    <tr>
                        <td background="images/5_22.gif" height="39" style="padding-left:30px;"><img src="images/5_42.gif" width="14" height="14" /><img src="images/5_42.gif" width="14" height="14" /><img src="images/5_42.gif" width="14" height="14" /><img src="images/5_42.gif" width="14" height="14" /><img src="images/5_42.gif" width="14" height="14" /><span style="float:right; padding-right:20px; color:#8b8a8a">2014-09-22 12:30</span></td>
                    </tr>
                    <tr>
                        <td background="images/5_29.gif" style="padding:20px 30px; font-size:14px; line-height:24px;">产品不错，值得推错，值得推错，值得推错，值得推错，值得推荐。 </td>
                    </tr>
                    <tr>
                        <td background="images/5_29.gif" style="padding-left:30px; padding-bottom:20px;"><a href="#"><img src="images/5_32.gif" width="61" height="22" /></a></td>
                    </tr>
                    <tr>
                        <td background="images/5_35.gif" height="1" style="padding-left:30px;"></td>
                    </tr>
                </table></td>
        </tr>
        <tr>
            <td height="10" colspan="2" align="center" valign="top" ></td>
        </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width="11%" align="center" valign="top" style="padding-top:10px;"><table width="70" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="70" height="70" align="center" valign="middle" background="images/5_25.gif"><img src="images/5_38.gif" width="50" height="50" /></td>
                    </tr>
                </table>
                ccddrrrrrrrrr</br>
                <span class="grenn">金牌会员</span></td>
            <td width="89%" align="left" valign="top">
                <table width="882" border="0" cellspacing="0" cellpadding="0">
                    <tr> </tr>
                    <tr>
                        <td background="images/5_22.gif" height="39" style="padding-left:30px;"><img src="images/5_42.gif" width="14" height="14" /><img src="images/5_42.gif" width="14" height="14" /><img src="images/5_42.gif" width="14" height="14" /><img src="images/5_42.gif" width="14" height="14" /><img src="images/5_42.gif" width="14" height="14" /><span style="float:right; padding-right:20px; color:#8b8a8a">2014-09-22 12:30</span></td>
                    </tr>
                    <tr>
                        <td background="images/5_29.gif" style="padding:20px 30px; font-size:14px; line-height:24px;">产品不错，值得推错，值得推错，值得推错，值得推错，值得推荐。 </td>
                    </tr>
                    <tr>
                        <td background="images/5_29.gif" style="padding-left:30px; padding-bottom:20px;"><a href="#"><img src="images/5_32.gif" width="61" height="22" /></a></td>
                    </tr>
                    <tr>
                        <td background="images/5_35.gif" height="1" style="padding-left:30px;"></td>
                    </tr>
                </table></td>
        </tr>
        <tr>
            <td height="10" colspan="2" align="center" valign="top" ></td>
        </tr>
    </table>
</div>
<div class="clear"></div>
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