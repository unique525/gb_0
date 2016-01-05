<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script src="/system_js/jquery-1.9.1.min.js"></script>
    <style>
        textarea{ height:280px; width:100% }
        #upLoad{ border:none; color:#ffffff; background:#52596B; height:28px; line-height:28px; padding-left:10px; padding-right:10px;
            font-size:14px; cursor:pointer; width:30px; margin:0px auto;margin-top:10px; }
    </style>
    <script>
        $(function(){

            var voteItemId = {voteItemId};

            $("#upLoad").click(function(){
                var txt = initTextToJson();

                if(txt != -1){

                    $.ajax({
                        type: "POST",
                        url: "/default.php?secu=manage&mod=vote_select_item&m=check_upload_txt&vote_item_id=" + voteItemId,
                        data: {txt: txt},
                        dataType:"jsonp",
                        jsonp:"jsonpcallback",
                        success: function(data){

                            if(data["result"] == 1){
                                alert("上传成功");
                                parent.location.reload();
                            }
                            else{
                                alert("错误:"+data["message"]);
                            }
                        }
                    });
                }

            });
        });

        function initTextToJson(){
            var rawTxt = $("textarea").val();
            rawTxt = rawTxt.replace();
            rawTxt = rawTxt.replace(/\，/g, ",");
            rawTxt = rawTxt.replace(/\、/g, ",");
            var result = [];

            var arrayRow = rawTxt.split("\r\n");                    //分割行
            arrayRow = rawTxt.split("\n");                    //分割行

            for(var i = 0; i < arrayRow.length; i++){

                if((arrayRow[i].replace(/(^\s*)|(\s*$)/g, "")).length > 0){                //丢弃 去掉首位空白字符之后的长度为0(整行没有文字)的行

                    var arrayContent = arrayRow[i].split("\t");     //分割每一行的每个列

                    if(arrayContent[1].length > 0){                 //在此处设置不能为空的项
                        var temp = [];

                        for(var j = 0; j < arrayContent.length; j++){

                            temp.push(arrayContent[j]);
                        }

                        result.push(temp);
                    }
                    else{
                        showErrorMessage(i, arrayContent[1]);
                        return -1;
                    }

                }
            }

            return JSON.stringify(result);
        }

        function showErrorMessage(i, title){
            var message;
            if(title == ''){
                message = "第" + i + "行附近,标题为空";
            }
            else{
                message = "第" + i + "行,标题为" + title + "  格式错误";
            }
            alert(message);
        }

    </script>
</head>
<body>
<textarea placeholder="在此处粘贴由EXCEL文件导出的TXT文本" wrap="soft"></textarea>

<p id="upLoad">确定</p>
</body>
</html>

