/**
 * 公共事件捕获
 * Created by zc on 14-8-27.
 */
$().ready(function() {

    //显示题图
    $(".show_title_pic").click(function(){
        var uploadFileId = parseInt($(this).attr("idvalue"));
        if(uploadFileId != undefined && uploadFileId>0){
            //取得json对象
            $.ajax({
                type: "get",
                url: "default.php?mod=upload_file&a=async_get_one",
                data: {
                    upload_file_id: uploadFileId
                },
                dataType: "json",
                success: function(data) {
                    var uploadFile = eval(data); //将json字符串转换成json对象
                    if(uploadFile.upload_file_path != undefined && uploadFile.upload_file_path.length>0){
                        var image = new Image();
                        image.src = uploadFile.upload_file_path;

                        if(window.ActiveXObject) {
                            image.onreadystatechange = function() {
                                if(image.readyState == "loaded" || image.readyState == "complete") {
                                    image.onreadystatechange = null;
                                    loadDialog(image.src,image.width,image.height);
                                }
                            }
                        } else {
                            image.onload = function() {
                                image.onload  = null;
                                loadDialog(image.src,image.width,image.height);
                            }
                        }


                        function loadDialog(imageSrc,imageWidth,imageHeight){
                            var dialogWidth = imageWidth + 50;
                            var dialogHeight = imageHeight + 50;
                            $("#dialog_box").dialog({
                                width: dialogWidth,
                                height: dialogHeight
                            });

                            var imgHtml = '<' + 'img src="' + imageSrc + '" alt="" />';
                            $("#dialog_content").html(imgHtml);
                        }
                    } else {
                        $("#dialog_box").dialog({width: 300, height: 100});
                        $("#dialog_content").html("还没有上传题图");
                    }
                }
            });
        }else{
            $("#dialog_box").dialog({width: 300, height: 100});
            $("#dialog_content").html("还没有上传题图");
        }

    });

    //显示题图截图
    $(".show_title_pic_cut").click(function(){
        var uploadFileId = parseInt($(this).attr("idvalue"));
        if(uploadFileId != undefined && uploadFileId>0){
            //取得json对象
            $.ajax({
                type: "get",
                url: "default.php?mod=upload_file&a=async_get_one",
                data: {
                    upload_file_id: uploadFileId
                },
                dataType: "json",
                success: function(data) {
                    var uploadFile = eval(data); //将json字符串转换成json对象
                    if(uploadFile.upload_file_cut_path1 != undefined && uploadFile.upload_file_cut_path1.length>0){
                        var image = new Image();
                        image.src = uploadFile.upload_file_cut_path1;

                        if(window.ActiveXObject) {
                            image.onreadystatechange = function() {
                                if(image.readyState == "loaded" || image.readyState == "complete") {
                                    image.onreadystatechange = null;
                                    loadDialog(image.src,image.width,image.height);
                                }
                            }
                        } else {
                            image.onload = function() {
                                image.onload  = null;
                                loadDialog(image.src,image.width,image.height);
                            }
                        }


                        function loadDialog(imageSrc,imageWidth,imageHeight){
                            var dialogWidth = imageWidth + 50;
                            var dialogHeight = imageHeight + 50;
                            $("#dialog_box").dialog({
                                width: dialogWidth,
                                height: dialogHeight
                            });

                            var imgHtml = '<' + 'img src="' + imageSrc + '" alt="" />';
                            $("#dialog_content").html(imgHtml);
                        }
                    } else {
                        $("#dialog_box").dialog({width: 300, height: 100});
                        $("#dialog_content").html("还没有制作截图");
                    }
                }
            });
        }else{
            $("#dialog_box").dialog({width: 300, height: 100});
            $("#dialog_content").html("还没有制作截图");
        }

    });

    $(".btnCloseDialogBox").click(function(){


        if($("#dialog_box") != undefined){

            $("#dialog_box").dialog({
                close: function(event, ui) {}
            });

        }


    });

});