/**
 * 是否登录
 */
window.isLogin = function(){
    alert(user.UserName);
    $.ajax({
        url:"/default.php?mod=user&a=async_get_one",
        data:{},
        dataType:"jsonp",
        jsonp:"jsonpcallback",
        success:function(data){
            var result = parseInt(data["result"]);
            if(result <= 0){
                //window.showLoginBox();
            }else{
                var user = eval(data["result"]);
                alert(user.UserName);
                //window.showIsLoginBox();
            }
        }
    });
}

