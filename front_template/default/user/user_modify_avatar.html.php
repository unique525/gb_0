<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>会员中心</title>
    <link href="/images/common_css.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="/front_template/common/jquery.Jcrop.min.css" type="text/css" />
    <style type="text/css">
        .rightbar input{
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

        var bigWidth = parseInt("{cfg_UserAvatarBigWidth_3}");
        var bigHeight = parseInt("{cfg_UserAvatarBigHeight_3}");
        var smallWidth = parseInt("{cfg_UserAvatarSmallWidth_3}");
        var smallHeight = parseInt("{cfg_UserAvatarSmallHeight_3}");
        var uploadFileId = 0;
        var src = "";
        var tableType = window.UPLOAD_TABLE_TYPE_USER_AVATAR;
        var tableId = parseInt("{UserId}");

        /**
         * 上传原文件回调
         * @param fileElementId
         * @param data
         * @constructor
         */
        window.AjaxFileUploadCallBack = function(fileElementId,data){
            if(data["upload_file_id"] != undefined){
                uploadFileId=parseInt(data["upload_file_id"]);
                //CreateThumb1(uploadFileId,400,0);
                src = data["upload_file_path"].toString();
                if(uploadFileId > 0 && src  != ""){
                    cutImageDisplay(); //显示在截图控件中
                }else{
                    alert("上传失败");
                }
            }
        };

        /**
         *截图回调函数
         * @param data
         * @constructor
         */
        window.CutImgCallBack = function(data){
            if(data["new_image_path"] != undefined){
                $.ajax({
                    url:"/default.php?mod=upload_file&a=async_modify_upload_file_path_for_cut_image&upload_file_id="
                        +uploadFileId+"&upload_file_path="+data["new_image_path"],
                    secureUri:false,
                    dataType:"json",
                    success:function(data){
                        uploadFileId = parseInt(data["upload_file_id"]);
                        if(uploadFileId != undefined && uploadFileId > 0){
                            ModifyUploadFileThumb2(uploadFileId,smallWidth,smallHeight);
                        }
                    },
                    error: function (data, status, e)
                    {
                        alert(e);
                    }
                });
            }else{
                alert("头像截图失败");
            }
        };

        /**
         * 取得一个UploadFile的回调函数
         * @param data
         * @constructor
         */
        window.GetOneUploadFileCallBack = function(fileElementId,uploadFileId, data){
            if(data["upload_file_path"] != ""){
                var avatarSrc = data["upload_file_path"];
                src = avatarSrc;
                $("#avatar").attr("src",avatarSrc);
            }
        };

        /**
         * 修改UploadFileThumbPath2的回调函数
         * @param data
         * @constructor
         */
        window.ModifyUploadFileThumb2CallBack = function(data){
            if(data["upload_file_id"] > 0){
                $.ajax({
                    url:"/default.php?mod=user_info&a=async_modify_avatar_upload_file_id&upload_file_id="+uploadFileId,
                    dataType:"jsonp",
                    jsonp:"jsonpcallback",
                    success:function(data){

                        if(data["result"] > 0){
                            window.location.href = location.href;
                        }else{
                            alert("修改头像失败");
                            $("#sub").attr("disabled","enable");
                            $( "#loadingOfFinish"+loadingImageId ).hide();
                        }
                    }
                });
            }
        };

        $(function(){
            getAvatarUploadFileId();

            $("#btn_upload").click(function () {

                var fileElementId = 'file_upload_to_user_avatar';
                var loadingImageId = "loadingOfUserAvatar";
                var attachWatermark = 0;
                if ($("#cbAttachWatermark").attr("checked") == true) {
                    attachWatermark = 1;
                }
                AjaxFileUpload(
                    fileElementId,
                    tableType,
                    tableId,
                    loadingImageId,
                    $(this),
                    attachWatermark,
                    uploadFileId
                );

            });

            $("#sub").click(function(){
                $(this).attr("disabled","disabled");
                $( "#loadingOfFinish" ).show();
                var CutImgForm = $("#CutImgForm");
                if(uploadFileId > 0){
                    CutImg(CutImgForm,uploadFileId);
                }
            });
        });

        function getAvatarUploadFileId(){
            $.ajax({
                url:"/default.php?mod=user_info&a=async_get_avatar_upload_file_id",
                async:false,
                dataType:"jsonp",
                jsonp:"jsonpcallback",
                success:function(data){
                    var result = data["result"];
                    if(result > 0){
                        uploadFileId = parseInt(result);
                        GetOneUploadFile(uploadFileId);
                    }else{
                        $("#avatar").css("width",bigWidth);
                        $("#avatar").css("height",bigHeight);
                        $("#avatar").attr("alt","您还没有上传头像");

                        //alert("获取头像失败");
                    }
                }
            });
        }


        function cutImageDisplay(){
            $("#upload").css("display","none");
            $("#outer").css("display","block");
            $("#target").attr("src",src);
            var jcrop_api, boundx, boundy;
            $('#target').Jcrop({
                onChange: updatePreview,
                onSelect: updatePreview,
                aspectRatio: 1,
                bgFade:true,
                bgOpacity: .3,
                minSize :[{cfg_UserAvatarMinWidth_3},{cfg_UserAvatarMinHeight_3}]
            },function(){
                // Use the API to get the real image size
                var bounds = this.getBounds();
                boundx = bounds[0];
                boundy = bounds[1];
                // Store the API in the jcrop_api variable
                jcrop_api = this;
            });
            function updatePreview(c){
                if (parseInt(c.w) > 0)
                {
                    var rx = bigWidth/ c.w;
                    var ry = bigHeight/ c.h;
                    $('#preview_large').css({
                        width: Math.round(rx * boundx) + 'px',
                        height: Math.round(ry * boundy) + 'px',
                        marginLeft: '-' + Math.round(rx * c.x) + 'px',
                        marginTop: '-' + Math.round(ry * c.y) + 'px'
                    });
                    var rx_small = smallWidth / c.w;
                    var ry_small = smallHeight / c.h;
                    $('#preview_small').css({
                        width: Math.round(rx_small * boundx) + 'px',
                        height: Math.round(ry_small * boundy) + 'px',
                        marginLeft: '-' + Math.round(rx_small * c.x) + 'px',
                        marginTop: '-' + Math.round(ry_small * c.y) + 'px'
                    });
                    $('#x').val(c.x);
                    $('#y').val(c.y);
                    $('#w').val(c.w);
                    $('#h').val(c.h);
                }
                $('#height').val(bigHeight);
                $('#width').val(bigWidth);
                $('#upload_file_id').val(uploadFileId);
                $("#preview_large").attr("src",src);
                $("#preview_small").attr("src",src);
            }
            $("#pre_avatar").css("display","none");
            $("#outer").css("display","block");
        }
    </script>
</head>

<body>
<pre_temp id="4"></pre_temp>
<div class="clean"></div>
<pre_temp id="12"></pre_temp>
<div class="wrapper">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="193" valign="top" height="750">
    <pre_temp id="6"></pre_temp>
</td>
<td width="1" bgcolor="#D4D4D4"></td>
<td width="1006" valign="top">
    <div class="rightbar">
        <div class="rightbar2"><a href="/">星滋味首页</a> ><a href="/default.php?mod=user&a=homepage">会员中心</a>>修改头像</div>
    </div>
    <div id="upload" style="padding:20px 50px;">
          <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
             <tr>
                <td width="169">
                    <div style="padding:3px; border:1px solid #e9e9e9;">
                        <img id="avatar" width="160px" height="160px" src=""/>
                    </div>
                </td>
                <td width="25" rowspan="9" align="left" ></td>
                <td width="439" rowspan="9" align="left" bgcolor="#f4f4f4">
                    <input id="file_upload_to_user_avatar" name="file_upload_to_user_avatar" type="file" class="input1" style="margin:20px; background:#ffffff; "/>
                    <input id="btn_upload" type="button" value="上传""/>
                    <img id="loadingOfUserAvatar" src="/system_template/common/images/loading1.gif" style="display:none;"/>
                </td>
                <td width="397" align="left" bgcolor="#f4f4f4" ><div style="margin:15px; padding:10px 20px;color: #585858;font-size: 12px; line-height:30px; border-left:1px dashed #CCCCCC"><p ><span  style="color:#CC0000">*</span>  支持jpg、png图片格式</p>
                        <p><span  style="color:#CC0000">*</span>  请上传宽高比不大于2的图片</p>
                        <p><span  style="color:#CC0000">*</span>  请上传宽度大于200像素,高度大于200像素的图片</p></div>
                </td>
             </tr>
          </table>
    </div>
            <div id="outer" style="display:none;padding:20px 50px;">
                <div style="background: #f6f6f6;">
                <div class="jcExample">
                    <div class="article" >
                        <table cellspacing="25">
                            <tr>
                                <td valign="top">
                                    <img src="" id="target" alt="Flowers"/>
                                </td>
                                <td valign="top">
                                    <div style="width:{cfg_UserAvatarBigWidth_3}px;height:{cfg_UserAvatarBigHeight_3}px;overflow:hidden;">
                                        <img src="" id="preview_large" alt="中图预览" class="jcrop-preview" />
                                    </div>
                                </td>
                                <td valign="top">
                                    <div style="width:{cfg_UserAvatarSmallWidth_3}px;height:{cfg_UserAvatarSmallHeight_3}px;overflow:hidden;">
                                        <img src="" id="preview_small" alt="小图预览" class="jcrop-preview" />
                                    </div>
                                </td>
                                <td valign="top">
                                    <div style="padding:5px 10px;color: #585858;font-size:12px; line-height:30px; border-left:1px dashed #CCCCCC">
                                        <p >  你可以随意拖拽及缩放方框来调整</p>
                                        <p >  其他头像的截图区域</p>
                                    </div>
                                </td>
                            </tr>
                        </table>
                <form id="CutImgForm">
                    <input type="hidden" id="x" name="x" />
                    <input type="hidden" id="y" name="y" />
                    <input type="hidden" id="w" name="w" />
                    <input type="hidden" id="h" name="h" />
                    <input type="hidden" value="" id="height" name="height"/>
                    <input type="hidden" value="" id="width" name="width"/>
                    <input type="hidden" value="" id="source" name="source"/>
                    <input type="hidden" value="/default.php?mod=user&a=homepage" id="source" name="re_url"/>
                    <div style="padding-left:25px; padding-bottom: 25px;">
                        <div id="sub" style="background-color:#00a93c;width:90px;height:30px;line-height:30px;font-size:16px;text-align:center;color:#fff;cursor:pointer;">确  定</div>
                    </div>
                    <div style="float:left"><img id="loadingOfFinish" src="/system_template/common/images/loading1.gif" style="display:none;"/></div>
                    <div style="clear:both"></div>
                </form>
            </div>
            </div></div>
            </div>

</td>
</tr>
</table>
</div>
<pre_temp id="8"></pre_temp>
</body>
</html>
