/**
 * 公共事件捕获
 * Created by zc on 14-8-27.
 */
$().ready(function() {

    //格式化价格
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

                        var dialogWidth = image.width + 50;
                        var dialogHeight = image.height + 50;
                        $("#dialog_box").dialog({
                            width: dialogWidth,
                            height: dialogHeight
                        });
                        var imgHtml = '<' + 'img src="' + uploadFile.upload_file_path + '" alt="" />';
                        $("#dialog_content").html(imgHtml);
                    } else {
                        $("#dialog_box").dialog({width: 300, height: 100});
                        $("#dialog_content").html("还没有上传题图");
                    }
                }
            });
        }

    });


});