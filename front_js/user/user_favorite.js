
function addUserFavorite(tableId,favoriteName,tableType,userFavoriteTag,siteId){
    $.ajax({
        url:"/default.php?mod=user_favorite&a=async_add",
        type:"POST",
        data:{table_id:tableId,table_type:tableType,user_favorite_title:favoriteName,user_favorite_tag:userFavoriteTag,site_id:siteId},
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