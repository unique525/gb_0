<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title></title>
    <link type="text/css" href="/system_template/default/images/jquery_ui/jquery-ui.min.css" rel="stylesheet" />
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/common.js"></script>
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript">
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
                                var send_price = parseFloat($("#send_price_"+user_car_id).html());
                                var buy_price = buy_count*product_price+send_price;
                                $("#buy_price_"+user_car_id).html(formatPrice(buy_price));
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
                            var send_price = parseFloat($("#send_price_"+user_car_id).html());
                            var buy_price = buy_count*product_price+send_price;
                            $("#buy_price_"+user_car_id).html(formatPrice(buy_price));
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
                            var send_price = parseFloat($("#send_price_"+user_car_id).html());
                            var buy_price = buy_count*product_price+send_price;
                            $("#buy_price_"+user_car_id).html(formatPrice(buy_price));
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
        });
    </script>
</head>
<body>
<icms id="user_car">
    <item>
        <![CDATA[
             --------------<br/>
            <img src="{f_UploadFilePath}"/>|
            {f_ProductName}|
            {f_ProductPriceIntro}|
            <span id="product_price_value_{f_UserCarId}">{f_ProductPriceValue}|</span>
            <span class="minus_count" id="minus_{f_UserCarId}" idvalue="{f_UserCarId}" style="cursor:pointer">-</span>
            <input class="input_buy_count" id="buy_count_{f_UserCarId}" idvalue="{f_UserCarId}" type="text" size="2" value="{f_BuyCount}"/>
            <span class="add_count" id="add_{f_UserCarId}" idvalue="{f_UserCarId}" style="cursor:pointer">+</span>|
            <span id="send_price_{f_UserCarId}">{f_SendPrice}</span>|
            <span id="buy_price_{f_UserCarId}">{f_BuyPrice}</span>|
            <span class="delete_product" idvalue="{f_UserCarId}" style="cursor:pointer">删除</span>
            <br/>
            -------------<br/>
        ]]>
    </item>
</icms>
</body>
</html>
