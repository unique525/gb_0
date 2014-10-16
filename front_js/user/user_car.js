
function addUserCar(siteId,productId,buyCount,productPriceId,activity_product_id){
    $.ajax({
        url:"/default.php?mod=user_car&a=async_create",
        data:{site_id:siteId,product_id:productId,buy_count:buyCount,product_price_id:productPriceId,activity_product_id:activity_product_id},
        dataType:"jsonp",
        jsonp:"jsonpcallback",
        success:function(data){
            var result = parseInt(data["result"]);
            if(result == -1){
                //失败
                alert("加入失败");
                location.replace(location);
            }else if(result == -2){
                var re_url = encodeURIComponent(location);
                window.location.href = "/default.php?mod=user&a=login&re_url="+re_url;
            }else{
                //成功
                alert("加入成功");
                location.replace(location);
            }
        }
    });
}


$(function(){
    $.ajax({
        url:"/default.php?mod=user_car&a=async_get_count&site_id=1",
        dataType:"jsonp",
        jsonp:"jsonpcallback",
        success:function(data){
            var result = data["result"];
            $("#user_car_count").html(result);
        }
    });
});