<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>我的购物车</title>
<link href="/images/common_css.css" rel="stylesheet" type="text/css"/>
<link type="text/css" href="/system_template/default/images/jquery_ui/jquery-ui.min.css" rel="stylesheet"/>
<script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="/system_js/common.js"></script>
<script type="text/javascript" src="/front_js/user/user_favorite.js"></script>
<script type="text/javascript" src="/system_js/jquery_ui/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
var selected_car = new Array();

function select_all(this_select) {
    selected_car = new Array();
    if (this_select.prop("checked")) {
        $(".checkbox_car").prop("checked", true);//全选
        $(".select_all").prop("checked", true);
        $(".checkbox_car").each(function () {
            selected_car.push($(this).attr("idvalue"));
        });
    } else {
        $(".checkbox_car").prop("checked", false);//取消全选
        $(".select_all").prop("checked", false);
    }
    var all_price = 0;
    for (var i = 0; i < selected_car.length; i++) {
        all_price = all_price + parseFloat($("#buy_price_" + selected_car[i]).html());
    }
    $("#all_price").html(formatPrice(all_price));
    $("#all_count").html(selected_car.length);
}

$(function () {
    select_all($(".select_all"));
    $(".minus_count").click(function () {
        var user_car_id = $(this).attr("idvalue");
        var buy_count = parseInt($("#buy_count_" + user_car_id).val());
        if (buy_count == 1) {
            alert("购买数量不能小于1");
        } else {
            buy_count = buy_count - 1;
            $.ajax({
                url: "/default.php?mod=user_car&a=async_modify_buy_count",
                data: {buy_count: buy_count, user_car_id: user_car_id},
                dataType: "jsonp",
                jsonp: "jsonpcallback",
                success: function (data) {
                    var result = parseInt(data["result"]);

                    if (result > 0) {//成功
                        $("#buy_count_" + user_car_id).val(buy_count);
                        var product_price = parseFloat($("#sale_price_value_" + user_car_id).html());
                        //var send_price = parseFloat($("#send_price_"+user_car_id).html());
                        //var buy_price = buy_count*product_price+send_price;
                        var buy_price = buy_count * product_price;
                        $("#buy_price_" + user_car_id).html(formatPrice(buy_price));

                        if ($("#checkbox_car_" + user_car_id).prop("checked")) {
                            var all_price = 0;
                            for (var i = 0; i < selected_car.length; i++) {
                                all_price = all_price + parseFloat($("#buy_price_" + selected_car[i]).html());
                            }
                            $("#all_price").html(formatPrice(all_price));
                        }
                    } else if(result == -21) {

                        alert("修改失败，当前购买数量大小库存数");

                    } else {

                        alert("修改失败");

                    }
                }
            });
        }
    });

    $(".add_count").click(function () {
        var user_car_id = $(this).attr("idvalue");
        var buy_count = parseInt($("#buy_count_" + user_car_id).val());
        buy_count = buy_count + 1;
        $.ajax({
            url: "/default.php?mod=user_car&a=async_modify_buy_count",
            data: {buy_count: buy_count, user_car_id: user_car_id},
            dataType: "jsonp",
            jsonp: "jsonpcallback",
            success: function (data) {
                var result = parseInt(data["result"]);

                if (result > 0) {
                    //成功
                    $("#buy_count_" + user_car_id).val(buy_count);
                    var product_price = parseFloat($("#sale_price_value_" + user_car_id).html());
//                            var send_price = parseFloat($("#send_price_"+user_car_id).html());
//                            var buy_price = buy_count*product_price+send_price;
                    var buy_price = buy_count * product_price;
                    $("#buy_price_" + user_car_id).html(formatPrice(buy_price));
                    if ($("#checkbox_car_" + user_car_id).prop("checked")) {
                        var all_price = 0;
                        for (var i = 0; i < selected_car.length; i++) {
                            all_price = all_price + parseFloat($("#buy_price_" + selected_car[i]).html());
                        }
                        $("#all_price").html(formatPrice(all_price));
                    }
                } else if(result == -21) {

                    alert("修改失败，当前购买数量大于库存数");

                } else {

                    alert("修改失败");

                }
            }
        });
    });

    $(".input_buy_count").blur(function () {
        var user_car_id = $(this).attr("idvalue");
        var buy_count = parseInt($("#buy_count_" + user_car_id).val());
        $.ajax({
            url: "/default.php?mod=user_car&a=async_modify_buy_count",
            data: {buy_count: buy_count, user_car_id: user_car_id},
            dataType: "jsonp",
            jsonp: "jsonpcallback",
            success: function (data) {
                if (data["result"] == -1) {
                    //失败
                } else {
                    //成功
                    $("#buy_count_" + user_car_id).val(buy_count);
                    var product_price = parseFloat($("#sale_price_value_" + user_car_id).html());
//                            var send_price = parseFloat($("#send_price_"+user_car_id).html());
//                            var buy_price = buy_count*product_price+send_price;
                    var buy_price = buy_count * product_price;
                    $("#buy_price_" + user_car_id).html(formatPrice(buy_price));
                    if ($("#checkbox_car_" + user_car_id).prop("checked")) {
                        var all_price = 0;
                        for (var i = 0; i < selected_car.length; i++) {
                            all_price = all_price + parseFloat($("#buy_price_" + selected_car[i]).html());
                        }
                        $("#all_price").html(formatPrice(all_price));
                    }
                }
            }
        });
    });

    $(".delete_product").click(function () {
        var user_car_id = $(this).attr("idvalue");
        $.ajax({
            url: "/default.php?mod=user_car&a=async_remove_bin",
            data: {user_car_id: user_car_id},
            dataType: "jsonp",
            jsonp: "jsonpcallback",
            success: function (data) {
                if (data["result"] == -1) {
                    //失败
                } else {
                    //成功
                    location.replace(location);
                }
            }
        });
    });


    $(".checkbox_car").click(function () {
        var user_car_id = $(this).attr("idvalue");
        var all_price = 0;

        if ($(this).prop("checked")) {
            selected_car.push(user_car_id);
        } else {
            var index = selected_car.indexOf(user_car_id);
            selected_car.splice(index, 1);
        }
        for (var i = 0; i < selected_car.length; i++) {
            all_price = all_price + parseFloat($("#buy_price_" + selected_car[i]).html());
        }
        $("#all_price").html(formatPrice(all_price));
        $("#all_count").html(selected_car.length);
    });

    $(".select_all").click(function () {
        select_all($(this));
    });

    $("#batch_delete").click(function () {
        var arr_user_car_id = "";
        for (var i = 0; i < selected_car.length; i++) {
            if (i == selected_car.length - 1) {
                arr_user_car_id = arr_user_car_id + selected_car[i];
            } else {
                arr_user_car_id = arr_user_car_id + selected_car[i] + "_";
            }
        }
        $.ajax({
            url: "/default.php?mod=user_car&a=async_batch_remove_bin&site_id=1",
            type: "POST",
            data: {arr_user_car_id: arr_user_car_id},
            dataType: "jsonp",
            traditional: true,
            jsonp: "jsonpcallback",
            success: function (data) {
                var result = data["result"];
                if (result == 1) {
                    location.replace(location);
                } else {
                    alert("删除失败");
                    location.replace(location);
                }
            }
        });
    });

    $("#to_order").click(function () {
        var arr_user_car_id = "";
        if (selected_car.length > 0) {
            for (var i = 0; i < selected_car.length; i++) {
                if (i == selected_car.length - 1) {
                    arr_user_car_id = arr_user_car_id + selected_car[i];
                } else {
                    arr_user_car_id = arr_user_car_id + selected_car[i] + "_";
                }
            }
            location.replace("/default.php?mod=user_order&a=confirm&arr_user_car_id=" + arr_user_car_id + "&site_id=1");
        } else {
            alert("请选择一个或多个商品");
        }
    });

    $(".producttab2 .title").mouseover(function(){
        var idvalue=$(this).attr("idvalue");
        $(".producttab ul").css("display","none");
        $(".producttab #ul_"+idvalue).css("display","block");
    });

});
</script>
</head>

<body>
<div class="wrapper">
    <div class="logo"><a href="/"><img src="/images/mylogo.png" width="320" height="103"/></a></div>
    <div class="step">
        <div class="step1"><img src="/images/wdgwc.png" width="49" height="49"/></div>
        <div class="steptext"><font class="grey">第一步</font><br/><font class="green">我的购物车</font></div>
        <div class="angle"></div>
        <div class="step1"><img src="/images/txhddd.png" width="49" height="49"/></div>
        <div class="steptext"><font class="grey">第二步</font><br/><font class="orange">填写核对订单</font></div>
        <div class="angle"></div>
        <div class="step1"><img src="/images/tjcg.png" width="49" height="49"/></div>
        <div class="steptext"><font class="grey">第三步</font><br/><font class="grey2">提交成功</font></div>
    </div>
</div>

</div>
<div class="clean"></div>
<pre_temp id="12"></pre_temp>
<div class="wrapper">
    <div class="shoppingcart">
        <div class="lefttext">我的购物车</div>
    </div>
    <div class="clean"></div>
    <div class="contgreybg">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="30"><input name="" class="select_all" autocomplete="off" type="checkbox" checked="checked" value=""/></td>
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
            <icms id="user_car_list">
                <item><![CDATA[
                    <li>
                        <div class="a1"><input class="checkbox_car" id="checkbox_car_{f_UserCarId}"
                                               idvalue="{f_UserCarId}" autocomplete="off" name="" type="checkbox"
                                               value=""/></div>
                        <div class="a2"><a href="/default.php?mod=product&a=detail&product_id={f_ProductId}" target="_blank"><img src="{f_UploadFilePath}" width="72" height="72"/></a></div>
                        <div class="a3"><a href="/default.php?mod=product&a=detail&product_id={f_ProductId}" target="_blank">{f_ProductName}</a></div>
                        <div class="a4">
                            ￥<span id="sale_price_value_{f_UserCarId}" class="show_price">{f_SalePrice}</span><br/>
                            <span style="font-size: 14px;color:#CCC">原价：￥</span><span
                                id="product_price_value_{f_UserCarId}" class="show_price"
                                style="TEXT-DECORATION: line-through;font-size: 14px;color:#CCC">{f_MarketPrice}</span>
                        </div>
                        <div class="a5">
                            <div class="num">
                                <input class="input_buy_count" id="buy_count_{f_UserCarId}" idvalue="{f_UserCarId}"
                                       type="text" value="{f_BuyCount}"/>
                            </div>
                            <div class="arrow">
                                <div><img alt="增加" style="cursor:pointer;" class="add_count" id="add_{f_UserCarId}" idvalue="{f_UserCarId}"
                                          src="/images/arrow.png" width="13" height="10"/></div>
                                <div><img alt="减少" style="cursor:pointer;" class="minus_count" id="minus_{f_UserCarId}" idvalue="{f_UserCarId}"
                                          src="/images/arrow2.png" width="13" height="10"/></div>
                            </div>
                        </div>
                        <div class="a6">
                            ￥<span class="buy_price show_price" id="buy_price_{f_UserCarId}">{f_BuyPrice}</span></div>
                        <div class="a7">有货</div>
                        <div class="a8">
                <span style="cursor:pointer"
                      onclick="addUserFavorite('{f_ProductId}','{f_ProductName}','1','商品','{f_SiteId}');">
                    收藏
                </span><br/>
                            <span class="delete_product" idvalue="{f_UserCarId}" style="cursor: pointer">删除</span>
                        </div>
                        <div class="clean"></div>
                    </li>
                    ]]>
                </item>
            </icms>
        </ul>
    </div>
</div>

<div class="wrapper">
    <div class="productbottom">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="40"><input class="select_all" name="" autocomplete="off" type="checkbox" value=""/></td>
                <td width="58">全选</td>
                <td width="58"><span id="batch_delete" style="cursor:pointer"><img src="/images/delete.png" width="24"
                                                                                   height="24"/></span></td>
                <td align="right" class="f18"></td>
            </tr>
        </table>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td align="right" class="f18">已选择 <span id="all_count">0</span> 件商品，金额 =<font color="#ff3c00" style="font-size:36px;">
                        ￥<span id="all_price">0.00</span></font></td>
            </tr>
            <tr>
                <td>
                    <table width="270" border="0" cellspacing="0" cellpadding="0" style="margin-right:0px;"
                           align="right">
                        <tr>
                            <td width="15"><img src="/images/arrow3.png" width="7" height="13"/></td>
                            <td width="100"><a href="/">继续购物</a></td>
                            <td width="155">
                                <span id="to_order" style="cursor: pointer"><img src="/images/btn_js.png" width="155"
                                                                                 height="43"/></span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <div class="clean"></div>
</div>

<div class="wrapper" style="display:none">
    <div class="producttab2">
        <div class="title" idvalue="zjsc"><a>最近收藏</a></div>
        <div class="title" idvalue="zjll"><a>最近浏览</a></div>
        <div class="title" idvalue="bpqg"><a>爆品抢购</a></div>
        <div class="title" idvalue="xpsd"><a>新品速递</a></div>
        <div class="title" idvalue="hpdp"><a>好评单品</a></div>
        <div class="clean"></div>
    </div>
    <div class="producttab">
        <ul id="ul_zjsc">
            <icms id="recent_user_favorite_list">
                <item>
                    <![CDATA[
                    <li>
                        <dl>
                            <dd><a href="{f_UserFavoriteUrl}" target="_blank"><img src="{f_UploadFilePath}" width="180" height="160"/></a></dd>
                            <dt><a href="{f_UserFavoriteUrl}" target="_blank">{f_UserFavoriteTitle}</a></dt>
                        </dl>
                    </li>
                    ]]>
                </item>
            </icms>
        </ul>
        <ul id="ul_zjll" style="display: none">
            <icms id="user_explore_1" type="user_explore_list" top="5">
                <item>
                    <![CDATA[
                    <li>
                        <dl>
                            <dd>
                                <a class="lookpic" href="{f_Url}" target="_blank" title="">
                                    <img src="{f_TitlePic}" width="180" height="160" style="display: inline; "/>
                                </a>
                            </dd>
                            <dt>
                                <a href="{f_Url}" target="_blank">{f_Title}</a>
                            </dt>
                            <h3><a href="{f_Url}" target="_blank">￥{f_Price}</a></h3>
                        </dl>
                    </li>
                    ]]>
                </item>
            </icms>
        </ul>
        <ul id="ul_bpqg" style="display: none">
            <icms id="product_1" type="product_list" where="RecLevel" where_value="2" top="5">
                <item>
                    <![CDATA[
                    <li>
                        <dl>
                            <dd>
                                <a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><img src="{f_UploadFileThumbPath2}" width="194" height="194" /></a>
                            </dd>
                            <dt>
                                <a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank">{f_ProductName}</a>
                            </dt>
                            <h3>
                                <a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><span class="right old_price">￥{f_MarketPrice}</span>￥{f_SalePrice}</a>
                            </h3>
                        </dl>
                    </li>
                    ]]>
                </item>
            </icms>
        </ul>
        <ul id="ul_xpsd" style="display: none">
            <icms id="product_1" type="product_list" where="RecLevel" where_value="3" top="5">
                <item>
                    <![CDATA[
                    <li>
                        <dl>
                            <dd>
                                <a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><img src="{f_UploadFileThumbPath2}" width="194" height="194" /></a>
                            </dd>
                            <dt>
                                <a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank">{f_ProductName}</a>
                            </dt>
                            <h3>
                                <a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><span class="right old_price">￥{f_MarketPrice}</span>￥{f_SalePrice}</a>
                            </h3>
                        </dl>
                    </li>
                    ]]>
                </item>
            </icms>
        </ul>
        <ul id="ul_hpdp" style="display: none">
            <icms id="product_1" type="product_list" where="RecLevel" where_value="4" top="5">
                <item>
                    <![CDATA[
                    <li>
                        <dl>
                            <dd>
                                <a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><img src="{f_UploadFileThumbPath2}" width="194" height="194" /></a>
                            </dd>
                            <dt>
                                <a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank">{f_ProductName}</a>
                            </dt>
                            <h3>
                                <a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}" target="_blank"><span class="right old_price">￥{f_MarketPrice}</span>￥{f_SalePrice}</a>
                            </h3>
                        </dl>
                    </li>
                    ]]>
                </item>
            </icms>
        </ul>
        <div class="clean"></div>
    </div>
</div>

<pre_temp id="8"></pre_temp>
</body>
</html>
