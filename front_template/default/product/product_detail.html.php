<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{BrowserTitle}--{ProductName}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
    <meta name="keywords" content="{BrowserKeywords}" />
    <meta name="description" content="{BrowserDescription}" />
    <link href="/images/common.css" rel="stylesheet" type="text/css" />
    <link href="/images/common_css.css" rel="stylesheet" type="text/css" />
    <link type="text/css" href="/system_template/default/images/jquery_ui/jquery-ui.min.css" rel="stylesheet" />
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/common.js"></script>
    <script type="text/javascript" src="/front_js/user/user.js"></script>
    <script type="text/javascript" src="/front_js/user/user_car.js"></script>
    <script type="text/javascript" src="/front_js/user/user_favorite.js"></script>
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui-1.8.2.custom.min.js"></script>
    <script src="/front_js/jqzoom/js/jqzoom.js" type="text/javascript"></script>
    <script src="/front_js/roll/msclass.js" type="text/javascript"></script>
    <link rel="stylesheet" href="/front_js/jqzoom/css/jqzoom.css" type="text/css" />
    <style type="text/css">
        ul{list-style-type:none;}
        .star ul{float:left;display:inline;height:19px;line-height:19px;}
        .star ul{margin:0 10px;}
        .star li{float:left;width:24px;text-indent:-9999px;background:url(/images/star.png) no-repeat;}
        .star li.on{background-position:0 -28px;}
    </style>
    <script type="text/javascript">
        var selectProductPriceId = 0;
        $(function(){
            $(".jqzoom").jqzoom({
                offset:5,
                xzoom:350,
                yzoom:350,
                position:"right",
                preload:1,
                lens:1
            });
            //页面加载后根据是否已下架标志做隐藏或显示相关元素处理
            var isOnSaleFlag={IsOnSaleFlagValue};
            //alert($("span [class='OnSale']"));
            if(isOnSaleFlag)
            {
              $(".OnSale").css("display","");
              $(".OutSale").css("display","none");
            }
            else
            {
                $(".OnSale").css("display","none");
                $(".OutSale").css("display","");
            }
            //品种规格选择
            var spanPropon=$('.propon');
            spanPropon.click(function(){
                //重置购买数量为1
                $("#productNum").val(1);
                //对应规格产品数量
                var productCount=$(this).attr("productcount");
                $("#productCount").text(productCount);
                //产品促销价格
                var productPriceValue=$(this).attr("pricevalue");
                selectProductPriceId = $(this).attr("idvalue");
                $("#productPrice").text(formatPrice(productPriceValue));
                //产品市场价格
                var productMarketPriceValue=$("#productMarketPrice").text();
                //优惠的差价
                var priceReduceValue = parseFloat(productMarketPriceValue)-parseFloat(productPriceValue);

                priceReduceValue = formatPrice(priceReduceValue);

                $("#priceReduce").text(priceReduceValue);
                //价格选择切换效果
                spanPropon.attr("class","propon propondefault");//默认全部不选择
                $(this).attr("class","propon proponselect");//点击选中
            });
            //页面加载后计算优惠价格
            if(spanPropon.length>0) //如果存在品种规格定的价格
            {
            //页面加载后默认选中第一个品种规格的价格进行优惠价格计算和库存显示
            spanPropon.eq(0).click();
            }
            else //直接用市场价格减去促销价格得出优惠价格
            {
             //产品促销价格
             var productPriceValue=$("#productPrice").text();
             //产品市场价格
             var productMarketPriceValue=$("#productMarketPrice").text();
            //优惠的差价
            var priceReduceValue = parseFloat(productMarketPriceValue)-parseFloat(productPriceValue);
            priceReduceValue = formatPrice(priceReduceValue);
            $("#priceReduce").text(priceReduceValue);
            }
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
                var buyCount = $("#productNum").val();
                var activityProductId = 0;
                if(selectProductPriceId  > 0){
                    addUserCar('{SiteId}','{ProductId}',buyCount,selectProductPriceId,activityProductId,false);
                }
            });

            $("#immediately_buy").click(function(){
                var buyCount = $("#productNum").val();
                var activityProductId = 0;
                if(selectProductPriceId  > 0){
                    addUserCar('{SiteId}','{ProductId}',buyCount,selectProductPriceId,activityProductId,true);
                }
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

            $(".product_comment_ul").each(function(){
                var productScore = $(this).attr("idvalue");
                var aLi = $(this).find("li");
                aLi.each(function(){
                    var star = parseInt($(this).html());
                    if(star <= productScore){
                        $(this).addClass("on");
                    }
                });
            });

            $(".user_avatar").each(function(){
                var user_avatar_path = $(this).attr("src");
                if(user_avatar_path == ""){
                    $(this).attr("src", "{cfg_UserDefaultMaleAvatar_5_upload_file_path}");
                }
            });

            $.ajax({
                url:"/default.php?mod=product_comment&a=async_get_appraisal",
                data:{product_id:{ProductId}},
                dataType:"jsonp",
                jsonp:"jsonpcallback",
                success:function(data){
                    var positive_appraisal = null ?  0:parseInt(data["positive_appraisal"]);
                    var moderate_appraisal = parseInt(data["moderate_appraisal"]);
                    var negative_appraisal = parseInt(data["negative_appraisal"]);

                    var total_appraisal =positive_appraisal + moderate_appraisal + negative_appraisal;

                    $("#total_appraisal").html(total_appraisal.toString());

                    var negative_appraisal_width = 0;
                    var positive_appraisal_width = 0;
                    var moderate_appraisal_width = 0;
                    if(total_appraisal > 0){
                        negative_appraisal_width = (negative_appraisal/total_appraisal).toFixed(2)*100;
                        positive_appraisal_width = (positive_appraisal/total_appraisal).toFixed(2)*100;
                        moderate_appraisal_width = (moderate_appraisal/total_appraisal).toFixed(2)*100;
                    }

                    $("#span_scoreCount").html(positive_appraisal_width+"%");
                    $("#divspan_VeryGood").html(positive_appraisal_width+"%");
                    $("#divspan_Good").html(moderate_appraisal_width+"%");
                    $("#divspan_NoGood").html(negative_appraisal_width+"%");

                    $("#div_VeryGood").css("width",positive_appraisal_width+"%");
                    $("#div_Good").css("width",moderate_appraisal_width+"%");
                    $("#div_NoGood").css("width",negative_appraisal_width+"%");
                }
            });
        });
        //产品数量增减
        function ProductNumChange(changeNum) {
            var productCount = $("#productCount").text();
            var inputProductNum=$("#productNum");
            var productNum = inputProductNum.val();
            if (Number(productNum)) {
                productNum = parseInt(productNum) + parseInt(changeNum);
                if (productNum == 0) {
                    productNum = 1;
                }
                else if(productNum>productCount)
                {
                    alert("购买数量超过了库存数量！");
                    return;
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
            var value = /^\d+$/;
            var sSum = this.val();
            if ("0" == sSum || !value.test(sSum)) {
                this.val("1");
            }
        }

    </script>
</head>
<body>
<pre_temp id="3"></pre_temp>
<pre_temp id="4"></pre_temp>
<pre_temp id="9"></pre_temp>

<div class="box1200">
    <div class="myseatnew">
        <a href="/">首页</a> &gt; {ChannelName} &gt; {ProductName}</div>
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
                    <li > <a class="pic" href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank" ><img src="{f_UploadFileThumbPath3}" width="90" height="90" ></a>
                        <p><a href="#" target="_blank" >{f_ProductName}</a>  </p>现价: ￥<span class="show_price">{f_SalePrice}</span></li>

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
<div class="box990  fr">
<div class="goodstop"><table width="100%" cellpadding="0" cellspacing="0" >
        <tr>
            <td align="center" valign="top" class="goodstopl" >
                <div id="magnifier" class="magnifier">
                    <div id="BigImage" class="jqzoom" style="border:1px solid #ccc">
                        <img id="jqimg" src="{UploadFileThumbPath1}" width="350" longdesc="{UploadFilePath}"/>
                    </div>
                </div>
                <div class="pic_small">
                    <span id="pic_sl" class="pic_sl"></span>
                    <div id="pic_smiddle" class="pic_smiddle">
                        <ul id="pic_smiddle_content">
                            <li><img class="pic_default" src="{UploadFileThumbPath3}" originpic="{UploadFilePath}" thumb1pic="{UploadFileThumbPath1}"  width="50"></li>
                            <icms id="product_pic_{ProductId}" type="product_pic_list" top="100">
                                <item>
                                    <![CDATA[
                                    <li><img class="pic_default" src="{f_UploadFileThumbPath3}" originpic="{f_UploadFilePath}" thumb1pic="{f_UploadFileThumbPath1}"  width="50"></li>
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
                        <td align="left"><div class="goodstopr"><p class="price_n"><span class="newprice">限时促销价：￥<span id="productPrice" class="newprice show_price OnSale">{SalePrice}</span></span></p>
                                <p class="price_n">原　价：￥<span id="productMarketPrice" class="oldprice show_price OnSale" style="text-decoration: line-through">{MarketPrice}</span></p>
                                <p class="price_n"><span class="chaprice">已优惠：￥<span class="show_price OnSale" id="priceReduce" style="padding-right: 5px; color:#ff3c00"></span></span></p></div>
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
                                                    <li><span class="propon propondefault" idvalue="{f_ProductPriceId}" pricevalue="{f_ProductPriceValue}" productcount="{f_ProductCount}">{f_ProductPriceIntro}</span></li>
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
                        <td align="left">
                          <p class="price_n">库存数量：<span id="productCount" class="OnSale"></span></p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="font-size:14px;">
                            <div class=quantity>
                                <p>购买数量：</P>
                                <p><a hidefocus class="dow" id="dow" href="#"><img
                                            src="images/dy_03.gif" width=15
                                            height=15></a></p>
                                <P class="mt0"><input id="productNum" class="textgt_n" value="1" maxLength="4"
                                                    type="text" /></p>
                                <P><a hideFocus class="up" id="up" href="#"><img
                                            src="images/dy_05.gif" width="15"
                                            height="15"></a></p></div>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="padding:20px 0;">
                            <div class="OnSale">
                            <span id="add_car" style="cursor: pointer"><img src="images/2_07.gif" width="155" height="36" /></span>
                            <span id="immediately_buy"><img style="cursor:pointer;" src="images/2_09.gif" width="155" height="36" /></span>
                            </div>
                            <div class="OutSale">
                                <p>此商品已下架，不能购买！</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td align="left"  style="font-size:14px;" ><span style="display: none"><img src="images/2_22.gif" width="13" height="14" align="absmiddle" /> <a href="#">降价通知</a> 　</span><img src="images/2_24.gif" width="18" height="14" align="absmiddle" />
                            <span style="cursor:pointer" onclick="addUserFavorite('{ProductId}','{ProductName}','1','商品');">我要收藏</span>
                             <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=2957670343&site=qq&menu=yes"><img border="0" src="/images1/service2.png" alt="联系客服" title="联系客服"/></a>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="padding:20px 0;"><div class="jiathis_style">
                                <a class="jiathis_button_qzone"></a>
                                <a class="jiathis_button_tsina"></a>
                                <a class="jiathis_button_tqq"></a>
                                <a class="jiathis_button_weixin"></a>
                                <a class="jiathis_button_renren"></a>
                                <a class="jiathis_button_xiaoyou"></a>
                                <a href="http://www.jiathis.com/share" class="jiathis jiathis_txt jtico jtico_jiathis" target="_blank"></a>
                                <a class="jiathis_counter_style"></a>
                            </div>
                            <script type="text/javascript" src="http://v3.jiathis.com/code/jia.js" charset="utf-8"></script></td>
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
        <div class="fctl">
            <ul>
                <icms id="product_1" type="product_list" where="RecLevel" where_value="6" top="4">
                    <item>
                        <![CDATA[
                        <li > <a class="pic" href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank" ><img src="{f_UploadFilePath}" width="150" height="175" ></a>
                            <p><a href="#" target="_blank" >{f_ProductName}</a>  </p>
                            ￥<span class="show_price">{f_SalePrice}</span> <span class="removed">￥<span class="show_price">{f_MarketPrice}</span></span></li>
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
                <em id="total_appraisal">0</em>
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
        <div class="rate"><strong><span id="span_scoreCount"></span></strong><br>好评度</div>
        <div class="percent">
            <dl>
                <dt>好评</dt>
                <dd class="d1">
                    <div id="div_VeryGood" style="height:12px;background-color:red""></div>
                </dd>
                <dd class="d2"><span id="divspan_VeryGood"></span>
                </dd>
            </dl>
            <dl>
                <dt>中评</dt>
                <dd class="d1">
                    <div id="div_Good" style="height:12px;background-color: red"></div>
                </dd>
                <dd class="d2"><span id="divspan_Good"></span></dd>
            </dl>
            <dl>
                <dt>差评</dt>
                <dd class="d1">
                    <div id="div_NoGood" style="height:12px;background-color: red"></div>
                </dd>
                <dd class="d2">
                    <span id="divspan_NoGood"></span>
                </dd>
            </dl>
        </div>
    </div>
</div>
<div class="blank10">        </div>
<div class="box990  fr">
    <a name="comment"></a>
    <icms id="product_comment_list" type="list">
        <item>
            <![CDATA[
    <table width="990px" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width="70px" align="center" valign="top" style="padding-top:10px;">
                <table width="70" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="70" height="70" align="center" valign="middle" background="images/5_25.gif"><a href="#"><img class="user_avatar" src="{f_UploadFileThumbPath2}" width="50" height="50" /></a></td>
                    </tr>
                </table>
                {f_UserName}</br>
            </td>
            <td width="882px" align="left" valign="top">
                <table width="882px" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td background="images/5_22.gif" height="39" style="padding-left:30px;">
                            <span style="float:right; padding-right:20px; color:#8b8a8a">{f_CreateDate}</span>
                            <div class="star">
                                <ul class="product_comment_ul" idvalue="{f_ProductScore}">
                                    <span class="left">产品评分：</span>
                                    <li>1</li>
                                    <li>2</li>
                                    <li>3</li>
                                    <li>4</li>
                                    <li>5</li>
                                </ul>
                                <ul class="product_comment_ul" idvalue="{f_SendScore}">
                                    <span class="left">物流评分：</span>
                                    <li>1</li>
                                    <li>2</li>
                                    <li>3</li>
                                    <li>4</li>
                                    <li>5</li>
                                </ul>
                                <ul class="product_comment_ul" idvalue="{f_ServiceScore}">
                                    <span class="left">服务评分：</span>
                                    <li>1</li>
                                    <li>2</li>
                                    <li>3</li>
                                    <li>4</li>
                                    <li>5</li>
                                </ul>
                                <div class="clean"></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td background="images/5_29.gif" style="word-wrap:break-word;word-break:break-all;padding:20px 30px; font-size:14px; line-height:24px;">
                            {f_Content}
                            <div style="margin-left: 10px;">
                                {child}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td background="images/5_29.gif" style="padding-left:30px; padding-bottom:20px;cursor:pointer"><!--<img src="images/5_32.gif" width="61" height="22" />--></td>
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
            ]]>
        </item>
        <child>
            <![CDATA[
                <div style="color:red">
                    <div>{f_UserName}:&nbsp;&nbsp;&nbsp;&nbsp;{f_Content}</div>
                </div>
            ]]>
        </child>
    </icms>
</div>
<div class="clear"></div>
<div class="pags" style="margin:30px 0px;">
    {product_comment_pager_button}
    <div class="clear"></div>
</div>
</div>
<div class="clear"></div>
<pre_temp id="8"></pre_temp>
<script type="text/javascript">var visitConfig = encodeURIComponent("{SiteUrl}") +"||{SiteId}||{ChannelId}||0||0||"+encodeURI("");</script><script type="text/javascript" src="/front_js/visit.js" charset="utf-8"></script>
</body>
</html>