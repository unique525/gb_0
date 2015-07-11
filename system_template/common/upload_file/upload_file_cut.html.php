<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <link rel="stylesheet" href="/front_template/common/jquery.Jcrop.min.css" type="text/css"/>
    <script type="text/javascript" src="/front_js/jquery.Jcrop.min.js"></script>
    <script type="text/javascript" src="/system_js/ajax_file_upload.js"></script>
    <script type="text/javascript" src="/system_js/upload_file.js"></script>

    <script type="text/javascript">

        var bigWidth = 400;
        var bigHeight = 300;
        var uploadFileId = 0;
        var src = "";
        var sourceType = 0; //0从原图截,1从压缩图1截

        /**
         *截图回调函数
         * @param data
         * @constructor
         */
        window.CutImgCallBack = function (data) {
            if (data["new_image_path"] != undefined) {
                $.ajax({
                    url: "/default.php?secu=manage&mod=upload_file&m=async_modify_upload_file_cut_path1&upload_file_id="
                        + uploadFileId + "&upload_file_cut_path=" + data["new_image_path"],
                    secureUri: false,
                    dataType: "json",
                    success: function (data) {
                        uploadFileId = parseInt(data["upload_file_id"]);
                        if (uploadFileId != undefined && uploadFileId > 0) {
                            alert("截图成功");
                            parent.$.fancybox.close();
                        }
                    },
                    error: function (data, status, e) {
                        alert(e);
                    }
                });
            } else {
                alert("截图失败");
            }
        };

        /**
         * 取得一个UploadFile的回调函数
         * @param data
         * @constructor
         */
        window.GetOneUploadFileCallBack = function (data) {
            if(Request["source_type"] != undefined){
                sourceType = parseInt(Request["source_type"]);
            }
            var filePath = "";
            switch (sourceType){
                case 0:
                    filePath = data["upload_file_path"];
                    break;
                case 1:
                    filePath = data["upload_file_compress_path1"];

                    if(filePath == undefined || filePath == ""){

                        alert("压缩图1未设置，请先在站点配置中资讯压缩图的参数")

                    }

                    break;
            }

            if (filePath != undefined && filePath != "") {
                //var uploadFileSrc = filePath;
                uploadFileId = data["upload_file_id"];
                //src = uploadFileSrc;

                $("#upload_file").attr("src", filePath);
                $("#target").attr("src", filePath);
                $("#preview_large").attr("src", filePath);
                $("#source_type").val(sourceType);
            }
        };

        $(function () {

            //从cookie读取常用宽度
            var m_width = parseInt(getcookie("manage_cut_image_m_width"));
            var m_height = parseInt(getcookie("manage_cut_image_m_height"));

            if(m_width>0 && m_height>0){
                $("#m_width").val(m_width);
                $("#m_height").val(m_height);
            }

            $("#confirm_set").click(function(){
                setcookie("manage_cut_image_m_width", $("#m_width").val());
                setcookie("manage_cut_image_m_height", $("#m_height").val());
                window.location.href = window.location.href;
            });


            var minSizeWidth = parseInt($("#m_width").val());
            var minSizeHeight = parseInt($("#m_height").val());
            var minSize = [minSizeWidth,minSizeHeight];

            bigWidth = minSizeWidth;
            bigHeight = minSizeHeight;


            var upload_file_id = parseInt(Request["upload_file_id"]);
            if(upload_file_id>0){
                GetOneUploadFile(upload_file_id);

                var jcrop_api, boundx, boundy;
                var jcropObject = $('#target').Jcrop({
                    onChange: updatePreview,
                    onSelect: updatePreview,
                    aspectRatio: minSizeWidth/minSizeHeight,
                    bgFade: true,
                    bgOpacity: .3,
                    minSize: minSize
                }, function () {
                    // Use the API to get the real image size
                    var bounds = this.getBounds();
                    boundx = bounds[0];
                    boundy = bounds[1];
                    // Store the API in the jcrop_api variable
                    jcrop_api = this;
                });

            }else{
                alert("没有上传图片");
            }

            function updatePreview(c) {
                if (parseInt(c.w) > 0) {
                    var rx = bigWidth / c.w;
                    var ry = bigHeight / c.h;
                    $('#preview_large').css({
                        width: Math.round(rx * boundx) + 'px',
                        height: Math.round(ry * boundy) + 'px',
                        marginLeft: '-' + Math.round(rx * c.x) + 'px',
                        marginTop: '-' + Math.round(ry * c.y) + 'px'
                    });
                    $('#x').val(c.x);
                    $('#y').val(c.y);
                    $('#w').val(c.w);
                    $('#h').val(c.h);
                }
                $('#height').val(c.h);
                $('#width').val(c.w);
                $('#upload_file_id').val(uploadFileId);
            }

            $("#sub").click(function () {
                $(this).attr("disabled", "disabled");
                $("#loadingOfFinish").show();
                var CutImgForm = $("#CutImgForm");
                if (uploadFileId > 0) {
                    var parameter = CutImgForm.serialize();
                    $.ajax({
                        url: "/default.php?secu=manage&mod=upload_file&m=async_cut_image&upload_file_id=" + uploadFileId,
                        data: parameter,
                        secureUri: false,
                        dataType: "json",
                        success: function (data) {
                            window.CutImgCallBack(data);
                        },
                        error: function (data, status, e) {
                            alert(e);
                        }
                    });
                }
            });
        });

        function GetOneUploadFile(uploadFileId) {

            $.ajax({
                url: "/default.php?secu=manage&mod=upload_file&m=async_get_one&upload_file_id=" + uploadFileId,
                async: false,
                secureUri: false,
                dataType: "json",
                success: function (data) {
                    window.GetOneUploadFileCallBack(data);
                },
                error: function (data, status, e) {
                    alert(e);
                }
            });
        }

    </script>
</head>

<body>
<div>

    <div id="outer" style="padding:1px;">
        <div style="background: #f6f6f6;">
            <div class="jcExample">
                <div class="article">
                    <table cellspacing="5">
                        <tr>
                            <td>
                                <img src="" style="" id="target" alt=""/>
                            </td>
                            <td>
                                <div style="width:400px;height:300px;overflow:hidden;display:none;">
                                    <img src="" id="preview_large" alt="中图预览" class="jcrop-preview"/>
                                </div>
                            </td>

                        </tr>
                    </table>
                    <form id="CutImgForm">
                        <input type="hidden" id="x" name="x"/>
                        <input type="hidden" id="y" name="y"/>
                        <input type="hidden" id="w" name="w"/>
                        <input type="hidden" id="h" name="h"/>
                        <input type="hidden" value="" id="height" name="height"/>
                        <input type="hidden" value="" id="width" name="width"/>
                        <input type="hidden" value="" id="source" name="source"/>
                        <input type="hidden" value="" id="source_type" name="source_type"/>

                        <div style="padding-left:5px; padding-bottom: 5px;">

                            <div style="float:left;">

                                <input id="sub" class="btn" value="确认" type="button" />
                                <img id="loadingOfFinish"
                                     src="/system_template/common/images/loading1.gif"
                                     style="display:none;"/>
                            </div>
                            <div style="float:right;">
                                <label for="m_width">最小宽度：</label>
                                <input id="m_width" style="width:60px;" class="input_number" value="400" type="text" />
                                <label for="m_height">最小高度：</label>
                                <input id="m_height" style="width:60px;" class="input_number" value="300" type="text" />
                                <input id="confirm_set" value="确认修改最小宽高" type="button" class="btn" />
                            </div>
                            <div style="clear:both;"></div>


                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>


</div>
</body>
</html>
