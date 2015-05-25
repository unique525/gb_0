<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>会员中心</title>
<link href="/images/common_css.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="/front_template/common/jquery.Jcrop.min.css" type="text/css"/>
<style type="text/css">
    .rightbar input {
        border: 1px solid #CCC;
    }

    .right {
        cursor: pointer
    }
</style>
<script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="/front_js/common.js"></script>
<script type="text/javascript" src="/front_js/jquery.Jcrop.min.js"></script>
<script type="text/javascript" src="/system_js/jquery_ui/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src="/front_js/user/user_car.js"></script>
<script type="text/javascript" src="/system_js/ajax_file_upload.js"></script>
<script type="text/javascript" src="/system_js/upload_file.js"></script>

<script type="text/javascript">

    var bigWidth =400;
    var bigHeight = 300;
    var uploadFileId = 0;
    var src = "";
    var tableType = window.UPLOAD_TABLE_TYPE_USER_AVATAR;
    var tableId = parseInt("{UserId}");

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
        if (data["upload_file_path"] != "") {
            var uploadFileSrc = data["upload_file_path"];
            uploadFileId = data["upload_file_id"];
            src = uploadFileSrc;
            $("#upload_file").attr("src", uploadFileSrc);
            $("#target").attr("src", src);
            $("#preview_large").attr("src", src);
        }
    };

    $(function () {
        var upload_file_id = Request["upload_file_id"];
        GetOneUploadFile(upload_file_id);

        var jcrop_api, boundx, boundy;
        $('#target').Jcrop({
            onChange: updatePreview,
            onSelect: updatePreview,
            //aspectRatio: 1,
            bgFade: true,
            bgOpacity: .3,
            minSize: [
                400,
                300
            ]
        }, function () {
            // Use the API to get the real image size
            var bounds = this.getBounds();
            boundx = bounds[0];
            boundy = bounds[1];
            // Store the API in the jcrop_api variable
            jcrop_api = this;
        });

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
            $('#height').val(bigHeight);
            $('#width').val(bigWidth);
            $('#upload_file_id').val(uploadFileId);
        }

        $("#sub").click(function () {
            $(this).attr("disabled", "disabled");
            $("#loadingOfFinish").show();
            var CutImgForm = $("#CutImgForm");
            if (uploadFileId > 0) {
                    var parameter = CutImgForm.serialize();
                    $.ajax({
                        url:"/default.php?secu=manage&mod=upload_file&m=async_cut_image&upload_file_id="+uploadFileId,
                        data:parameter,
                        secureUri:false,
                        dataType:"json",
                        success:function(data){
                            window.CutImgCallBack(data);
                        },
                        error: function (data, status, e)
                        {
                            alert(e);
                        }
                    });
            }
        });
    });

    function GetOneUploadFile(uploadFileId){

        $.ajax({
            url:"/default.php?secu=manage&mod=upload_file&m=async_get_one&upload_file_id="+uploadFileId,
            async:false,
            secureUri:false,
            dataType:"json",
            success:function(data){
                window.GetOneUploadFileCallBack(data);
            },
            error: function (data, status, e)
            {
                alert(e);
            }
        });
    }

</script>
</head>

<body>
<div class="wrapper" style="width: 90%">

                <div id="outer" style="padding:20px 50px;">
                    <div style="background: #f6f6f6;">
                        <div class="jcExample">
                            <div class="article">
                                <table cellspacing="25">
                                    <tr>
                                        <td valign="top">
                                            <img src="" id="target" alt="Flowers"/>
                                        </td>
                                        <td valign="top">
                                            <div style="width:400px;height:300px;overflow:hidden;">
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

                                    <div style="padding-left:25px; padding-bottom: 25px;">
                                        <div id="sub"
                                             style="background-color:#00a93c;width:90px;height:30px;line-height:30px;font-size:16px;text-align:center;color:#fff;cursor:pointer;">
                                            确 定
                                        </div>
                                    </div>
                                    <div style="float:left"><img id="loadingOfFinish"
                                                                 src="/system_template/common/images/loading1.gif"
                                                                 style="display:none;"/></div>
                                    <div style="clear:both"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </td>
        </tr>
    </table>
</div>
</body>
</html>
