<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script src="/system_js/jquery-1.9.1.min.js"></script>
    <style>
        .class_add{ line-height:20px;}
        #upLoad{border:none; color:#ffffff; background:#52596B; height:28px; line-height:28px; padding:5px 10px;
            font-size:14px; cursor:pointer; width:30px;}
    </style>
    <script>
        $(function(){

            $("#upLoad").click(function(){
                var newTypeName = $('#class_modify').val();
                var forumTopicTypeId = {forumTopicTypeId};
                var forumId = {forumId};
                if(forumTopicTypeId > 0){
                    $.ajax({
                        type: "get",
                        url: "/default.php?secu=manage&mod=forum_topic_type&m=modify_type_name",
                        data: {
                                       forum_id : forumId,
                            forum_topic_type_id : forumTopicTypeId,
                            forum_new_type_name : newTypeName
                        },
                        dataType: "jsonp",
                        jsonp: "jsonpcallback",
                        success: function(data){
                            console.log(data["result"]);
                            if(data["result"] > 0){
                                alert("修改类型成功");
                                parent.location.href = parent.location.href;
                            }
                            else if(data["result"] == -2){
                                alert("修改的类型已存在");
                            }
                            else{
                                alert("修改错误,请联系管理员");
                            }
                        }
                    });
                }
            });
        });

    </script>
</head>
<body>
<div>
    <input class="class_add" id="class_modify" placeholder="修改后的类型" />

    <span id="upLoad">确定</span>
</div>

</body>
</html>

