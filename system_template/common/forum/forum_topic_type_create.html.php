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
                var txt = $("#class_add").val();
                $.ajax({
                    type: "POST",
                    url: '/default.php?secu=manage&mod=forum_topic_type&m=create&site_id={siteId}&forum_id={forumId}',
                    data: {newType : txt},
                    success: function(data){
                        if(data > 0){
                            alert("新增类型成功");
                            parent.location.href = parent.location.href;
                        }
                        else if(data == -2){
                            alert("已存在这个类型");
                            parent.location.href = parent.location.href;
                        }
                        else{
                            alert("上传错误,请联系管理员");
                            parent.location.href = parent.location.href;

                        }
                    }
                });

            });
        });

    </script>
</head>
<body>
<div>
    <input class="class_add" id="class_add" placeholder="新增的类别" />

    <span id="upLoad">确定</span>
</div>

</body>
</html>

