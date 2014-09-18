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
            $(".delete_favorite").click(function(){
                var user_favorite_id = $(this).attr("idvalue");
                $.ajax({
                    url:"/default.php?mod=user_favorite&a=async_remove_bin",
                    data:{user_favorite_id:user_favorite_id},
                    dataType:"jsonp",
                    jsonp:"jsonpcallback",
                    success:function(data){
                        if(data["result"] == -1){
                            alert("失败");
                        }else{
                            //成功
                            //alert("成功");
                            location.replace(location);
                        }
                    }
                });
            });
        });
    </script>
</head>
<body>
<icms id="user_favorite">
    <item>
        <![CDATA[
        --------------<br/>
        <a href="{f_UserFavoriteUrl}">{f_UserFavoriteTitle}</a>|
        <span class="delete_favorite" idvalue="{f_UserFavoriteId}" style="cursor:pointer">删除</span>
        <br/>
        -------------<br/>
        ]]>
    </item>
</icms>
</body>
</html>
