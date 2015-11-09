<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script src="/system_js/jquery-1.9.1.min.js"></script>
    <style>
        textarea{ height:370px; width:100% }
        #upLoad{ border:none; color:#ffffff; background:#52596B; height:28px; line-height:28px; padding-left:10px; padding-right:10px;
            font-size:14px; cursor:pointer; width:30px; margin:10px auto }
    </style>
    <script>
        $(function(){

            var voteItemId = {voteItemId};

            $("#upLoad").click(function(){
                var txt = initTxt();
                $.ajax({
                    type: "POST",
                    url: "/default.php?secu=manage&mod=vote_select_item&m=check_upload_txt&vote_item_id=" + voteItemId,
                    data: {txt : txt},
                    success: function(data){
                        if(data > 0){
                            alert("上传成功");
                            parent.location.href = parent.location.href;
                        }
                        else if(data == -2){
                            parent.location.href = parent.location.href;
                            alert("数据格式不正确");
                        }
                        else{
                            parent.location.href = parent.location.href;
                            alert("上传错误,请联系管理员");
                        }
                    }
                });

            });
        });

            function initTxt(){
                var txt = $("textarea").val();
                txt = txt.replace(/[\r\n]/g, "!|!"); //将换行替换为!|!
                txt = txt.replace(/\s/g, "@");     //将空格或制表符替换为@
                txt = txt.replace(/，/ig, ",");  //替换中文逗号
                txt = txt.replace(/<[^>]+>/g,""); //删除HTML标记

                return txt;
            }

    </script>
</head>
<body>
<textarea placeholder="在此处粘贴由EXCEL文件导出的TXT文本" wrap="soft"></textarea>

<p id="upLoad">确定</p>
</body>
</html>

