<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/common.js"></script>
    <script type="text/javascript" src="/system_js/jquery.cookie.js"></script>
    <script type="text/javascript">


        $(function () {

            var examQuestionClassId = Request["exam_question_class_id"];

            $.ajax({
                url: "default.php",
                data: {mod: "exam_user_paper", a: "async_gen", exam_question_class_id: examQuestionClassId},
                dataType: "jsonp",
                jsonp: "jsonpcallback",
                success: function (data) {

                    var examUserPaperId = parseInt(data);
                    if(examUserPaperId>0){
                        window.location.href = "/default.php?mod=exam_user_answer&a=list&exam_user_paper_id="+examUserPaperId;
                    }else{
                        alert("试题生成失败！");
                    }


                }
            });
        });

    </script>
</head>
<body>
</body>
</html>
