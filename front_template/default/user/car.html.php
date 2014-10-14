<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link href="/images/common_css.css" rel="stylesheet" type="text/css" />
    <link type="text/css" href="/system_template/default/images/jquery_ui/jquery-ui.min.css" rel="stylesheet" />
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/front_js/common.js"></script>
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript">
        var selected_car = new Array();
        $(function(){
            $(".minus_count").click(function(){
                var user_car_id = $(this).attr("idvalue");
                var buy_count = parseInt($("#buy_count_"+user_car_id).val());
                if(buy_count == 1){
                    alert("购买数量不能小于1");
                }else{
                    buy_count = buy_count-1;
                    $.ajax({
                        url:"/default.php?mod=user_car&a=async_modify_buy_count",
                        data:{buy_count:buy_count,user_car_id:user_car_id},
                        dataType:"jsonp",
                        jsonp:"jsonpcallback",
                        success:function(data){
                            if(data["result"] == -1){
                                //失败
                            }else{
                                //成功
                                $("#buy_count_"+user_car_id).val(buy_count);
                                var product_price = parseFloat($("#product_price_value_"+user_car_id).html());
                                //var send_price = parseFloat($("#send_price_"+user_car_id).html());
                                //var buy_price = buy_count*product_price+send_price;
                                var buy_price = buy_count*product_price;
                                $("#buy_price_"+user_car_id).html(formatPrice(buy_price));

                                if($("#checkbox_car_"+user_car_id).prop("checked")){
                                    var all_price = 0;
                                    for(var i=0;i<selected_car.length;i++){
                                        all_price = all_price + parseFloat($("#buy_price_"+selected_car[i]).html());
                                    }
                                    $("#all_price").html(formatPrice(all_price));
                                }
                            }
                        }
                    });
                }
            });

            $(".add_count").click(function(){
                var user_car_id = $(this).attr("idvalue");
                var buy_count = parseInt($("#buy_count_"+user_car_id).val());
                buy_count = buy_count+1;
                $.ajax({
                    url:"/default.php?mod=user_car&a=async_modify_buy_count",
                    data:{buy_count:buy_count,user_car_id:user_car_id},
                    dataType:"jsonp",
                    jsonp:"jsonpcallback",
                    success:function(data){
                        if(data["result"] == -1){
                            //失败
                        }else{
                            //成功
                            $("#buy_count_"+user_car_id).val(buy_count);
                            var product_price = parseFloat($("#product_price_value_"+user_car_id).html());
//                            var send_price = parseFloat($("#send_price_"+user_car_id).html());
//                            var buy_price = buy_count*product_price+send_price;
                            var buy_price = buy_count*product_price;
                            $("#buy_price_"+user_car_id).html(formatPrice(buy_price));
                            if($("#checkbox_car_"+user_car_id).prop("checked")){
                                var all_price = 0;
                                for(var i=0;i<selected_car.length;i++){
                                    all_price = all_price + parseFloat($("#buy_price_"+selected_car[i]).html());
                                }
                                $("#all_price").html(formatPrice(all_price));
                            }
                        }
                    }
                });
            });

            $(".input_buy_count").blur(function(){
                var user_car_id = $(this).attr("idvalue");
                var buy_count = parseInt($("#buy_count_"+user_car_id).val());
                $.ajax({
                    url:"/default.php?mod=user_car&a=async_modify_buy_count",
                    data:{buy_count:buy_count,user_car_id:user_car_id},
                    dataType:"jsonp",
                    jsonp:"jsonpcallback",
                    success:function(data){
                        if(data["result"] == -1){
                            //失败
                        }else{
                            //成功
                            $("#buy_count_"+user_car_id).val(buy_count);
                            var product_price = parseFloat($("#product_price_value_"+user_car_id).html());
//                            var send_price = parseFloat($("#send_price_"+user_car_id).html());
//                            var buy_price = buy_count*product_price+send_price;
                            var buy_price = buy_count*product_price;
                            $("#buy_price_"+user_car_id).html(formatPrice(buy_price));
                            if($("#checkbox_car_"+user_car_id).prop("checked")){
                                var all_price = 0;
                                for(var i=0;i<selected_car.length;i++){
                                    all_price = all_price + parseFloat($("#buy_price_"+selected_car[i]).html());
                                }
                                $("#all_price").html(formatPrice(all_price));
                            }
                        }
                    }
                });
            });

            $(".delete_product").click(function(){
                var user_car_id = $(this).attr("idvalue");
                $.ajax({
                    url:"/default.php?mod=user_car&a=async_remove_bin",
                    data:{user_car_id:user_car_id},
                    dataType:"jsonp",
                    jsonp:"jsonpcallback",
                    success:function(data){
                        if(data["result"] == -1){
                            //失败
                        }else{
                            //成功
                            location.replace(location);
                        }
                    }
                });
            });



            $(".checkbox_car").click(function(){
                var user_car_id = $(this).attr("idvalue");
                var all_price = 0;

                if($(this).prop("checked")){
                    selected_car.push(user_car_id);
                }else{
                        var index = selected_car.indexOf(user_car_id);
                        selected_car.splice(index,1);
                }
                for(var i=0;i<selected_car.length;i++){
                    all_price = all_price + parseFloat($("#buy_price_"+selected_car[i]).html());
                }
                $("#all_price").html(formatPrice(all_price));
                $("#all_count").html(selected_car.length);
            });

            $(".select_all").click(function(){
                selected_car = new Array();
                if($(this).prop("checked")){
                    $(".checkbox_car").prop("checked",true);//全选
                    $(".select_all").prop("checked",true);
                    $(".checkbox_car").each(function(){
                        selected_car.push($(this).attr("idvalue"));
                    });
                }else{
                    $(".checkbox_car").prop("checked",false);//取消全选
                    $(".select_all").prop("checked",false);
                }
                var all_price = 0;
                for(var i=0;i<selected_car.length;i++){
                    all_price = all_price + parseFloat($("#buy_price_"+selected_car[i]).html());
                }
                $("#all_price").html(formatPrice(all_price));
                $("#all_count").html(selected_car.length);
            });

            $("#batch_delete").click(function(){
                var arr_user_car_id = "";
                for(var i=0;i<selected_car.length;i++){
                    if(i == selected_car.length-1){
                        arr_user_car_id = arr_user_car_id+selected_car[i];
                    }else{
                        arr_user_car_id = arr_user_car_id+selected_car[i]+"_";
                    }
                }
                $.ajax({
                    url:"/default.php?mod=user_car&a=async_batch_remove_bin&site_id=1",
                    type:"POST",
                    data:{arr_user_car_id:arr_user_car_id},
                    dataType:"jsonp",
                    traditional: true,
                    jsonp:"jsonpcallback",
                    success:function(data){
                        var result = data["result"];
                        if(result == 1){
                            location.replace(location);
                        }else{
                            alert("删除失败");
                            location.replace(location);
                        }
                    }
                });
            });

            $("#to_order").click(function(){
                var arr_user_car_id = "";
                if(selected_car.length > 0){
                    for(var i=0;i<selected_car.length;i++){
                        if(i == selected_car.length-1){
                            arr_user_car_id = arr_user_car_id+selected_car[i];
                        }else{
                            arr_user_car_id = arr_user_car_id+selected_car[i]+"_";
                        }
                    }
                    location.replace("/default.php?mod=user_order&a=confirm&arr_user_car_id="+arr_user_car_id+"&site_id=1");
                }else{
                    alert("请选择一个或多个商品");
                }
            });
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
<div class="wrapper">
<div class="logo"><a href=""><img src="/images/mylogo.png" width="320" height="103" /></a></div>
<div class="step">
<div class="step1"><img src="/images/wdgwc.png" width="49" height="49" /></div>
<div class="steptext"><font class="grey">第一步</font><br /><font class="green">我的购物车</font></div>
<div class="angle"></div>
<div class="step1"><img src="/images/txhddd.png" width="49" height="49" /></div>
<div class="steptext"><font class="grey">第二步</font><br /><font class="orange">填写核对订单</font></div>
<div class="angle"></div>
<div class="step1"><img src="/images/tjcg.png" width="49" height="49" /></div>
<div class="steptext"><font class="grey">第三步</font><br /><font class="grey2">提交成功</font></div>
</div>
</div>

</div>
<div class="clean"></div>
<div class="mainbav">
<div class="wrapper">
<div class="goods" id="leftmenu"><ul><li><span>所有商品分类</span></li></ul></div>
<div class="column1"><a href="">首页</a></div>
<div class="column2"><a href="">超市量贩</a></div>
<div class="column2"><a href="">团购</a></div>
<div class="column2"><a href="">最新预售</a></div>
<div class="new"><img src="/images/icon_new.png" width="29" height="30" /></div>
</div>
</div>
<div class="wrapper">
<div class="shoppingcart">
<div class="lefttext">我的购物车</div>
</div>
<div class="clean"></div>
<div class="contgreybg">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="30"><input name="" class="select_all" autocomplete="off" type="checkbox" value="" /></td>
    <td width="58">全选</td>
    <td width="400" align="center">商品</td>
    <td width="155" align="center">单价</td>
    <td width="155" align="center">购买数量</td>
    <td width="119" align="center">小计</td>
    <td width="119" align="center">库存状态</td>
    <td width="78" align="center">操作</td>
  </tr>
</table>
</div>
<div class="clean"></div>
<div class="productlist">
<ul>
<icms id="user_car">
    <item><![CDATA[
        <li>
            <div class="a1"><input class="checkbox_car" id="checkbox_car_{f_UserCarId}" idvalue="{f_UserCarId}" autocomplete="off" name="" type="checkbox" value="" /></div>
            <div class="a2"><img src="{f_UploadFilePath}" width="72" height="72" /></div>
            <div class="a3">{f_ProductName}</div>
            <div class="a4">￥<span id="product_price_value_{f_UserCarId}">{f_ProductPriceValue}</span></div>
            <div class="a5">
                <div class="num">
                    <input class="input_buy_count" id="buy_count_{f_UserCarId}" idvalue="{f_UserCarId}" type="text" value="{f_BuyCount}"/>
                </div>
                <div class="arrow">
                    <div><img class="add_count" id="add_{f_UserCarId}" idvalue="{f_UserCarId}" src="/images/arrow.png" width="13" height="10" /></div>
                    <div><img class="minus_count" id="minus_{f_UserCarId}" idvalue="{f_UserCarId}" src="/images/arrow2.png" width="13" height="10" /></div>
                </div>
            </div>
            <div class="a6">
                ￥<span class="buy_price" id="buy_price_{f_UserCarId}">{f_BuyPrice}</span></div>
            <div class="a7">有货</div>
            <div class="a8">
                <span style="cursor:pointer" onclick="addFavorite('{f_ProductId}','{f_ProductName}','1','商品','{f_SiteId}');">
                    收藏
                </span><br />
                <span class="delete_product" idvalue="{f_UserCarId}" style="cursor: pointer">删除</span>
            </div>
            <div class="clean"></div>
        </li>
        ]]></item>
</icms>
</ul>
</div>
</div>

<div class="wrapper">
<div class="productbottom">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="40"><input class="select_all" name="" autocomplete="off" type="checkbox" value="" /></td>
    <td width="58">全选</td>
    <td width="58"><span id="batch_delete" style="cursor:pointer"><img src="/images/delete.png" width="24" height="24" /></span></td>
    <td align="right" class="f18"></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="right" class="f18">已选择 <span id="all_count">0</span> 件商品，金额 =<font color="#ff3c00" style="font-size:36px;">￥<span id="all_price">0.000</span></font></td>
    </tr>
  <tr>
    <td>
<table width="270" border="0" cellspacing="0" cellpadding="0" style="margin-right:0px;" align="right">
  <tr>
    <td width="15"><img src="/images/arrow3.png" width="7" height="13" /></td>
    <td width="100">继续购物</td>
    <td width="155">
        <span id="to_order" style="cursor: pointer"><img src="/images/btn_js.png" width="155" height="43" /></span>
    </td>
  </tr>
</table>
    </td>
  </tr>
</table>
</div>
<div class="clean"></div>
</div>

<div class="wrapper">
<div class="producttab2">
<div class="title"><a href="">最近收藏</a></div>
<div class="title"><a href="">最近浏览</a></div>
<div class="title"><a href="">爆品抢购</a></div>
<div class="title"><a href="">新品速递</a></div>
<div class="title"><a href="">好评单品</a></div>
<div class="clean"></div>
</div>
<div class="producttab">
<ul>
<li>
<dl>
<dd><img src="/images/pic_prduct.png" width="180" height="160" /></dd>
<dt>妈咪宝贝满299减30送豪礼</dt>
<span style="padding-right:50px;"><font class="pricenew">￥25.90</font></span><span><font class="priceold">￥49</font></span>
</dl>
<div class="btn_jrgwc"><a href=""><img src="/images/btn_jrgwc.png" width="91" height="26" /></a></div>
</li>
<li>
<dl>
<dd><img src="/images/pic_prduct.png" width="180" height="160" /></dd>
<dt>妈咪宝贝满299减30送豪礼</dt>
<span style="padding-right:50px;"><font class="pricenew">￥25.90</font></span><span><font class="priceold">￥49</font></span>
</dl>
<div class="btn_jrgwc"><a href=""><img src="/images/btn_jrgwc.png" width="91" height="26" /></a></div>
</li>
<li>
<dl>
<dd><img src="/images/pic_prduct.png" width="180" height="160" /></dd>
<dt>妈咪宝贝满299减30送豪礼</dt>
<span style="padding-right:50px;"><font class="pricenew">￥25.90</font></span><span><font class="priceold">￥49</font></span>
</dl>
<div class="btn_jrgwc"><a href=""><img src="/images/btn_jrgwc.png" width="91" height="26" /></a></div>
</li>
<li>
<dl>
<dd><img src="/images/pic_prduct.png" width="180" height="160" /></dd>
<dt>妈咪宝贝满299减30送豪礼</dt>
<span style="padding-right:50px;"><font class="pricenew">￥25.90</font></span><span><font class="priceold">￥49</font></span>
</dl>
<div class="btn_jrgwc"><a href=""><img src="/images/btn_jrgwc.png" width="91" height="26" /></a></div>
</li>
<li>
<dl>
<dd><img src="/images/pic_prduct.png" width="180" height="160" /></dd>
<dt>妈咪宝贝满299减30送豪礼</dt>
<span style="padding-right:50px;"><font class="pricenew">￥25.90</font></span><span><font class="priceold">￥49</font></span>
</dl>
<div class="btn_jrgwc"><a href=""><img src="/images/btn_jrgwc.png" width="91" height="26" /></a></div>
</li>
</ul>
<div class="clean"></div>
</div>
</div>

<div class="footerline"></div>
<div class="wrapper">
<div class="footerleft">
<div class="cont">
<div><img src="/images/footergwzn.png" width="79" height="79" /></div>
<b>交易条款</b><br />
<a href="" target="_blank">购物流程</a><br />
<a href="" target="_blank">发票制度</a><br />
<a href="" target="_blank">会员等级</a><br />
<a href="" target="_blank">积分制度</a><br /><br />
</div>
</div>
<div class="footerleft">
<div class="cont">
<div><img src="/images/footerpsfw.png" width="79" height="79" /></div>
<b>配送服务</b><br />
<a href="" target="_blank">配送说明</a><br />
<a href="" target="_blank">配送范围</a><br />
<a href="" target="_blank">配送状态查询</a><br /><br /><br />
</div>
</div>
<div class="footerleft">
<div class="cont">
<div><img src="/images/footerzffs.png" width="79" height="79" /></div>
<b>支付方式</b><br />
<a href="" target="_blank">支付宝支付</a><br />
<a href="" target="_blank">银联在线支付</a><br />
<a href="" target="_blank">货到付款</a><br /><br /><br />
</div>
</div>
<div class="footerleft">
<div class="cont">
<div><img src="/images/footershfw.png" width="79" height="79" /></div>
<b>售后服务</b><br />
<a href="" target="_blank">服务承诺</a><br />
<a href="" target="_blank">退换货政策</a><br />
<a href="" target="_blank">退换货流程</a><br /><br /><br />
</div>
</div>
<div class="footerright" style="padding-left:50px;">
手机客户端下载
<div><img src="/images/weixin.png" width="104" height="104" /></div>
</div>
<div class="footerright" style="padding-right:50px;">
手机客户端下载
<div><img src="/images/weixin.png" width="104" height="104" /></div>
</div>
</div>
</body>
</html>
