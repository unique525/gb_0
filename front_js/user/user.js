/**
 * 是否登录
 */
window.isLogin = function(siteId){

    $.ajax({
        url:"/default.php?mod=user&a=async_get_one",
        data:{"site_id":siteId},
        dataType:"jsonp",
        jsonp:"jsonpcallback",
        success:function(data){
            var result = parseInt(data["result"]);
            if(result <= 0){
                window.showLoginBoxCallBack();
            }else{
                var user = eval(data["result"]);
                window.showIsLoginBoxCallBack(user);
            }
        }
    });
}

