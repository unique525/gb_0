
function addUserCar(siteId,productId,buyCount,productPriceId,activity_product_id){
    $.ajax({
        url:"/default.php?mod=user_car&a=async_add",
        data:{site_id:siteId,product_id:productId,buyCount:buyCount,product_price_id:productPriceId,activity_product_id:activity_product_id},
        dataType:"jsonp",
        jsonp:"jsonpcallback",
        success:function(data){
            if(data["result"] == -1){
                //失败
                alert("加入失败");
                location.replace(location);
            }else{
                //成功
                alert("加入成功");
                location.replace(location);
            }
        }
    });
}