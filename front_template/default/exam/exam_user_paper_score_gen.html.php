<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/common.js"></script>
    <script type="text/javascript" src="/system_js/jquery.cookie.js"></script>
    <script type="text/javascript">
        $().ready(function() {
            var examUserPaperId = Request["exam_user_paper_id"];
            $.ajax({
                url: "default.php",
                data: {mod: "exam_user_paper",a: "async_count_score",exam_user_paper_id:examUserPaperId},
                dataType: "jsonp",
                jsonp:"JsonpCallBack",
                success:function(data){
                    if(parseInt(data["result"]) == 100){
                        window.location.href = "/default.php?mod=lottery&a=default&temp=lottery_1&exam_user_paper_id="+examUserPaperId;
                    }else{
                        window.location.href = "/default.php?mod=exam_user_paper&a=error_list&exam_user_paper_id="+examUserPaperId;
                    }
                }
            });
        });
    </script>
</head>
<body>
</body>
</html>


