$(function(){
    $.ajax({
        url:"/default.php?mod=user_car&a=async_get_car_count&site_id=1",
        dataType:"jsonp",
        jsonp:"jsonpcallback",
        success:function(data){
            var result = data["result"];
            $("#user_car_count").html(result);
        }
    });
});

function addCar(siteId,productId,buyCount,productPriceId,activity_product_id){
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

/**
 * 加入收藏
 */
function addFavorite(tableId,favoriteName,tableType,userFavoriteTag,siteId){
        $.ajax({
            url:"/default.php?mod=user_favorite&a=async_add",
            type:"POST",
            data:{table_id:tableId,table_type:tableType,user_favorite_title:favoriteName,user_favorite_tag:userFavoriteTag,site_id:1},
            dataType:"jsonp",
            jsonp:"jsonpcallback",
            success:function(data){
                if(data["result"] == -1){
                    //失败
                    alert("收藏失败");
                    location.replace(location);
                }else if(data["result"] == -2){
                    alert("已被收藏");
                    location.replace(location);
                }else{
                    //成功
                    alert("收藏成功");
                    location.replace(location);
                }
            }
        });
}

function formatPrice(price){
    if(price != undefined){
        if(parseFloat(price)>0){
            return parseFloat(price).toFixed(3);
        }else{
            return "0.000";
        }

    }else{
        return "";
    }

}

/**
 * 时间对象的格式化
 * @param date 时间对象
 * @param format 时间格式字符串
 * @return string format 格式化后的时间对象字符串
 */
function formatDate(date,format) {
    var o = {
        "M+" : date.getMonth() + 1,
        "d+" : date.getDate(),
        "h+" : date.getHours(),
        "m+" : date.getMinutes(),
        "s+" : date.getSeconds(),
        "q+" : Math.floor((date.getMonth() + 3) / 3),
        "S" : date.getMilliseconds()
    };

    if (/(y+)/.test(format))
    {
        format = format.replace(RegExp.$1, (date.getFullYear() + "").substr(4
            - RegExp.$1.length));
    }

    for (var k in o)
    {
        if (new RegExp("(" + k + ")").test(format))
        {
            format = format.replace(RegExp.$1, RegExp.$1.length == 1
                ? o[k]
                : ("00" + o[k]).substr(("" + o[k]).length));
        }
    }
    return format;
}

/**
 * 全文搜索替换
 */
String.prototype.replaceAll = function(s1, s2) {
    return this.replace(new RegExp(s1, "gm"), s2);
}

