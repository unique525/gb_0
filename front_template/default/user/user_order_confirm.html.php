<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>订单确认</title>
    <link href="/images/common_css.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/common.js"></script>
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript" src="/system_js/json.js"></script>
    <script type="text/javascript">
        var nowSelectReceiveInfoId = 0;


        var userOrderProductObject = null;
        var userOrderProductArray = new Array();

        $(function () {
            $("#add_receive_info").click(function () {
                $("#new_receive_info_div").css("display", "block");
            });

            $("#cancel_receive_info").click(function () {
                $("#new_receive_info_div").css("display", "none");
            });

            $(".modify_receive_info").click(function(){
                var receive_info_id = $(this).attr("idvalue");
                nowSelectReceiveInfoId = receive_info_id;
                $("#address").val($("#Address_"+receive_info_id).html());
                $("#postcode").val($("#Postcode_"+receive_info_id).html());
                $("#receive_person_name").val($("#ReceivePersonName_"+receive_info_id).html());
                $("#tel_no").val($("#HomeTel_"+receive_info_id).html());
                $("#mobile").val($("#Mobile_"+receive_info_id).html());

                $("#new_receive_info_div").css("display", "block");
            });

            $("#confirm_receive_info").click(function () {
                var address = $("#address").val();
                var postcode = $("#postcode").val();
                var receive_person_name = $("#receive_person_name").val();
                var tel = $("#tel_no").val();
                var mobile = $("#mobile").val();
                if (address != "" && postcode != "" && receive_person_name != "" && (tel != "" || mobile != "")) {
                    var url = "";
                    if(nowSelectReceiveInfoId == 0){
                        url = "/default.php?mod=user_receive_info&a=async_create";
                    }else{
                        url = "/default.php?mod=user_receive_info&a=async_modify&user_receive_info_id="+nowSelectReceiveInfoId;
                    }
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {address: address, postcode: postcode, receive_person_name: receive_person_name, tel: tel, mobile: mobile},
                        dataType: "jsonp",
                        jsonp: "jsonpcallback",
                        success: function (data) {
                            var result = data["result"];
                            if (result > 0) {
                                location.replace(location);
                            } else if(result == -1) {
                                alert("新增失败");
                            }else if(result == -2){
                                alert("修改失败");
                            }
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
//                            alert(textStatus);
//                            alert(errorThrown);
                        }
                    });
                } else {
                    alert("请填入完整信息");
                }
            });

            $("#btn_submit").click(function () {
                var json = JSON.stringify(userOrderProductArray);

                var userReceiveInfoId =  parseInt($("input[name='r_userReceiveInfoId']:checked").val());
                if(userReceiveInfoId<=0 || isNaN(userReceiveInfoId)){

                    alert("请先选择或新增一个收货地址");
                    return;
                }
                if(json == null || json.length<=0){
                    alert("购买的商品出错，请重试");
                    return;
                }

                $("#s_UserReceiveInfoId").val(userReceiveInfoId);
                $("#s_UserOrderProductArray").val(json);

                var form = $("#form_submit");
                //alert(form.serialize());

                form.attr("action", "/default.php?mod=user_order&a=submit");
                form.submit();

            });
        });
    </script>
</head>

<body>
<div class="wrapper">
    <div class="logo"><a href=""><img src="/images/mylogo.png" width="320" height="103"/></a></div>
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

<div class="wrapper3">
    <div class="new_buy">
        <div class="new_con">
            <div class="new_con_tit">收货人<br/>信息</div>
            <div class="new_con_box">
                <div class="new_con_arw"><img src="/images/cart_n_arrowl.gif" width="11" height="21" alt=""/></div>

                <table width="90%" border="0" cellspacing="0" cellpadding="0"
                       style="line-height:50px;margin:20px auto 0px auto;">
                    <tr><td style="color:#FF3C00;" colspan="2"><strong>寄送至：</strong></td></tr>

                    <icms id="user_receive">
                        <item><![CDATA[
                            <tr>
                                <td width="25">
                                    <input type="radio" name="r_userReceiveInfoId" value="{f_UserReceiveInfoId}"/>
                                </td>
                                <td valign="top" style="font-size:14px;word-break:break-all; word-wrap:normal;">
                                    <b>
                                        <span id="Address_{f_UserReceiveInfoId}">{f_Address}</span>,
                                        <span id="ReceivePersonName_{f_UserReceiveInfoId}">{f_ReceivePersonName}</span>,
                                        <span id="Postcode_{f_UserReceiveInfoId}">{f_Postcode}</span>,
                                        <span id="Mobile_{f_UserReceiveInfoId}">{f_Mobile}</span>,
                                        <span id="HomeTel_{f_UserReceiveInfoId}">{f_HomeTel}</span>
                                    </b>
                                </td>
                                <td width="80" style="font-size:12px;">
                                    <span class="modify_receive_info" idvalue="{f_UserReceiveInfoId}"
                                          style="cursor:pointer">修改本地址</span>
                                </td>
                            </tr>
                            ]]>
                        </item>
                    </icms>
                    <tr>
                        <td align="right" height="40"></td>
                        <td valign="top"><img id="add_receive_info" style="cursor: pointer" src="/images/btnaddress.png"
                                              width="89" height="25"/></td>
                        <td></td>
                    </tr>
                </table>

                <div id="new_receive_info_div" class="new_adr_in" style="display: none">
                    <dl>
                        <dt><span>*</span>详细地址：</dt>
                        <dd>
                            <input id="address" autocomplete="off" type="text" class="cart_n_long" maxlength="40"
                                   value=""/>
                        </dd>
                    </dl>
                    <dl>
                        <dt><span>*</span>邮政编码：</dt>
                        <dd><input id="postcode" autocomplete="off" type="text" class="cart_n_short" value=""/></dd>
                    </dl>
                    <dl>
                        <dt><span>*</span>收 货 人：</dt>
                        <dd><input id="receive_person_name" autocomplete="off" type="text" class="cart_n_short"
                                   value=""/></dd>
                    </dl>
                    <dl>
                        <dt><span>*</span>手&nbsp;&nbsp;&nbsp;&nbsp;机：</dt>
                        <dd><input id="mobile" type="text" class="cart_n_short" autocomplete="off" value=""/>&nbsp;或固定电话&nbsp;
                            <input id="tel_no" type="text" class="cart_n_short" autocomplete="off" value=""/>
                        </dd>
                        <dd class="adr_prompt">两者至少填写一项，用于接收发货通知及送货前确认</dd>
                    </dl>
                    <!--
                    <dl>
                        <dt>地址标注：</dt>
                        <dd><input type="text" value=""  class="cart_n_short"><span style="color:#C1C1C1;padding-left:5px;">例如家里、公司，最多四个字。</span></dd>
                    </dl>
                    <dl>
                        <dt>&nbsp;</dt>
                        <dd><input name="" type="checkbox" value="" /><span style="font-size:12px;padding-left:3px;">设为默认地址</span></dd>
                    </dl>
                    -->
                    <dl>
                        <dt>&nbsp;</dt>
                        <dd>
                            <div id="confirm_receive_info" class="ncart_btn_on"
                                 style="border:1px solid #CCC;cursor:pointer;float:left">保存
                            </div>
                            <div style="float:left;width:10px;border: 1px solid #fff"></div>
                            <div id="cancel_receive_info" class="ncart_btn_on"
                                 style="border:1px solid #CCC;cursor:pointer;float:left">取消
                            </div>
                            <div style="clear:left"></div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="new_con">
            <div class="new_con_tit">商品<br/>信息</div>
            <div class="new_con_box">
                <div class="new_con_arw"><img src="/images/cart_n_arrowl.gif" width="11" height="21" alt=""/></div>
                <div class="new_commodity">
                    <div class="fr"><a href="/default.php?mod=user_car&a=list">[返回购物车]</a></div>
                    <table border="0" cellspacing="1" cellpadding="0" class="com_list" bgcolor="#dddddd">
                        <tr class="com_list_tit">
                            <td>商品名称</td>
                            <td>数量</td>
                            <td>原价</td>
                            <td>折后价</td>
                            <td>金额小计</td>
                        </tr>
                        <icms id="product_with_product_price">
                            <item><![CDATA[
                                <script type="text/javascript">


                                    //新增时确认
                                    userOrderProductObject = new Object();
                                    userOrderProductObject.ProductId = "{f_ProductId}";
                                    userOrderProductObject.ProductPriceId = "{f_ProductPriceId}";
                                    userOrderProductObject.ProductPriceValue = "{f_ProductPriceValue}";
                                    userOrderProductObject.SalePrice = "{f_SalePrice}";
                                    userOrderProductObject.SaleCount = "{f_BuyCount}";
                                    userOrderProductObject.Subtotal = "{f_BuyPrice}";

                                    userOrderProductArray.push(userOrderProductObject);


                                </script>
                                <tr>
                                    <td>
                                        <a href="/default.php?mod=product&a=detail&channel_id={f_ChannelId}&product_id={f_ProductId}"
                                           target="_blank">{f_ProductName}</a>
                                    </td>
                                    <td class="send_td01" style="text-align:center">{f_BuyCount}</td>
                                    <td class="send_td01" style="text-align:center">
                                        <span class="show_price">{f_ProductPriceValue}</span>
                                    </td>
                                    <td class="send_td01" style="text-align:center">
                                        <span class="show_price">{f_SalePrice}</span>
                                    </td>
                                    <td class="send_td01" style="text-align:center">
                                        <span class="UserOrderSubtotal show_price">{f_BuyPrice}</span>
                                    </td>
                                </tr>
                                ]]>
                            </item>
                        </icms>
                    </table>
                </div>
                <div class="clean"></div>
            </div>
        </div>

        <div class="new_con">
            <div class="new_con_tit">发票<br/>信息</div>
            <div id="PiaoView" class="new_con_box">
                <div class="new_con_arw"><img src="/images/cart_n_arrowl.gif" width="11" height="21" alt=""/></div>
                <div class="new_cart_inv">
                    <ul>
                        <li>
                            <div class="adr_row01">发票信息：<font color="#FF3C00">请联系申请，我们将尽快为您补寄。</font></div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="new_all_submit">
            <div class="new_all_price">商品金额<strong class="show_price">{TotalProductPrice}</strong>元
                + 运费<strong class="show_price">{SendPrice}</strong>元
                &nbsp;&nbsp;&nbsp;&nbsp;您需为订单支付金额<span class="show_price">{TotalPrice}</span>元
            </div>
            <div class="red">请确认收货地址后进行订单提交！</div>
            <div style="padding-top:15px;">
                <form id="form_submit" action="" method="post">
                    <input id="s_UserReceiveInfoId" name="s_UserReceiveInfoId" value="" type="hidden" />
                    <input id="s_AllPrice" name="s_AllPrice" value="{TotalProductPrice}" type="hidden" />
                    <input id="s_SendPrice" name="s_SendPrice" value="{SendPrice}" type="hidden" />
                    <input id="s_UserOrderProductArray" name="s_UserOrderProductArray" value="" type="hidden" />
                    <input id="s_ArrUserCarId" name="s_ArrUserCarId" value="{ArrUserCarId}" type="hidden" />
                    <input id="btn_submit" style="cursor:pointer;" type="button" class="btn_submit" value="提交"/>
                </form>
            </div>
        </div>

    </div>
</div>
<div class="clean"></div>

<pre_temp id="8"></pre_temp>
</body>
</html>
