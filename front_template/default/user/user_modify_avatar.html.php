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

        var width = 200;
        var height = 200;
        var uploadFileId = 0;
        var src = "";
        var tableTypeOfForumTopInfo = window.UPLOAD_TABLE_TYPE_USER_AVATAR;
        var tableId = {UserId};

        window.AjaxFileUploadCallBack = function(fileElementId,data){

            if(data["upload_file_id"] != undefined){
                uploadFileId=data["upload_file_id"];
                //CreateThumb1(uploadFileId,400,0);
                cutImage();
            }


        };

        window.CreateThumb1CallBack = function(data){
            src = data["upload_file_thumb_path1"];

        };


        $(function(){
            getAvatar();

            $("#btnupload").click(function () {

                var fileElementId = 'file_upload_to_user_avatar';

                var loadingImageId = "loadingOfUserAvatar";

                AjaxFileUpload(
                    fileElementId,
                    tableTypeOfForumTopInfo,
                    tableId,
                    loadingImageId,
                    $(this),
                    null,
                    null,
                    null,
                    null,
                    null
                );
            });
        });

        function getAvatar(){
            $.ajax({
                url:"/default.php?mod=user_info&a=async_get_avatar",
                async:false,
                dataType:"jsonp",
                jsonp:"jsonpcallback",
                success:function(data){
                    var result = data["result"];
                    if(result == 1){
                        var avatarList = data["avatarList"];
                        var avatarSrc = avatarList["UploadFilePath"];
                        src = avatarSrc;
                        $("#avatar").attr("src",avatarSrc);
                    }else{
                        alert("获取头像失败");
                    }
                }
            });
        }


        function cutImage(){
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
                minSize :[{cfg_UserAvatarMinWidth_3},200]
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
                    var rx = width / c.w;
                    var ry = height / c.h;
                    $('#preview_large').css({
                        width: Math.round(rx * boundx) + 'px',
                        height: Math.round(ry * boundy) + 'px',
                        marginLeft: '-' + Math.round(ry * c.x) + 'px',
                        marginTop: '-' + Math.round(ry * c.y) + 'px'
                    });
                    var rx_small = 50 / c.w;
                    var ry_small = 50 / c.h;
                    $('#preview_small').css({
                        width: Math.round(rx_small * boundx) + 'px',
                        height: Math.round(rx_small * boundy) + 'px',
                        marginLeft: '-' + Math.round(rx_small * c.x) + 'px',
                        marginTop: '-' + Math.round(rx_small * c.y) + 'px'
                    });
                    $('#x').val(c.x);
                    $('#y').val(c.y);
                    $('#w').val(c.w);
                    $('#h').val(c.h);
                }
                $('#height').val(height);
                $('#width').val(width);
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

<div class="wrapper2">
    <div class="logo"><a href=""><img src="/images/mylogo.png" width="320" height="103"/></a></div>
    <div class="search">
        <div class="search_green"><input name="" type="text" class="text"/></div>
        <div class="searchbtn"><img src="/images/search.png" width="46" height="28"/></div>
        <div class="searchbottom">平谷大桃 哈密瓜 新鲜葡萄 红炉磨坊 太湖鲜鱼</div>
    </div>
    <div class="service">
        <div class="hottel"><span><a href="" target="_blank">热线96333</a></span></div>
        <div class="online"><span><a href="" target="_blank">在线客服</a></span></div>
        <div class="shopping"><a href="/default.php?mod=user_car&a=list"><span>购物车</span></a></div>
        <div class="number" id="user_car_count">0</div>
    </div>
</div>
<div class="clean"></div>
<div class="mainbav">
    <div class="wrapper">
        <div class="goods" id="leftmenu">
            <ul>
                <li><span>会员中心</span></li>
            </ul>
        </div>
        <div class="column1"><a href="">首页</a></div>
        <div class="column2"><a href="">超市量贩</a></div>
        <div class="column2"><a href="">团购</a></div>
        <div class="column2"><a href="">最新预售</a></div>
        <div class="new"><img src="/images/icon_new.png" width="29" height="30"/></div>
    </div>
</div>
<div class="wrapper">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="193" valign="top" height="750">
    <pre_temp id="6"></pre_temp>
</td>
<td width="1" bgcolor="#D4D4D4"></td>
<td width="1006" valign="top">
<div class="rightbar">
<div class="rightbar2"><a href="">星滋味首页</a> >会员中心</div>
    <div id="upload" style="padding:25px;">
        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td width="169"><div style="padding:3px; border:1px solid #e9e9e9;"><img id="avatar" width="160px" height="160px" src=""/></div></td>
                <td width="25" rowspan="9" align="left" ></td>
                <td width="439" rowspan="9" align="left" bgcolor="#f4f4f4">
                    <input id="file_upload_to_user_avatar" name="file_upload_to_user_avatar" type="file" class="input1" style="margin:20px; background:#ffffff; "/>
                    <input id="btnupload" type="button" value="上传""/>
                    <img id="loadingOfUserAvatar" src="/system_template/common/images/loading1.gif" style="display:none;"/>
                </td>
                <td width="397" align="left" bgcolor="#f4f4f4" ><div style="margin:15px; padding:10px 20px;color: #585858;font-size: 12px; line-height:30px; border-left:1px dashed #CCCCCC"><p ><span  style="color:#CC0000">*</span>  支持jpg、png图片格式</p>
                        <p><span  style="color:#CC0000">*</span>  请上传宽高比不大于2的图片</p>
                        <p><span  style="color:#CC0000">*</span>  请上传宽度大于200像素,高度大于200像素的图片</p></div>                           </td>
            </tr>
        </table>
    </div>
    <div id="outer" style="display:none">
        <div class="jcExample">
            <div class="article" >
                <table cellspacing="25">
                    <tr>
                        <td valign="top">
                            <img src="" id="target" alt="Flowers"/>
                        </td>
                        <td valign="top">
                            <div style="width:200px;height:200px;overflow:hidden;">
                                <img src="" id="preview_large" alt="中图预览" class="jcrop-preview" />
                            </div>
                        </td>
                        <td valign="top">
                            <div style="width:50px;height:50px;overflow:hidden;">
                                <img src="" id="preview_small" alt="小图预览" class="jcrop-preview" />
                            </div>
                        </td>
                        <td valign="top">
                            <div style="padding:5px 10px;color: #585858;font-size:12px; line-height:30px; border-left:1px dashed #CCCCCC">
                                <p >  你可以随意拖拽及缩放方框来调整</p>
                                <p >  其他头像的截图区域</p></div>
                        </td>
                    </tr>
                </table>
                <form action="/default.php?mod=user_info&a=generate_avatar" method="post">
                    <input type="hidden" id="x" name="x" />
                    <input type="hidden" id="y" name="y" />
                    <input type="hidden" id="w" name="w" />
                    <input type="hidden" id="h" name="h" />
                    <input type="hidden" value="" id="height" name="height"/>
                    <input type="hidden" value="" id="width" name="width"/>
                    <input type="hidden" value="" id="source" name="source"/>
                    <input type="hidden" value="/default.php?mod=user&a=homepage" id="source" name="re_url"/>
                    <input type="submit" class="Btn23H_orangeA vTop" style="margin:0px 0px 25px 25px;border:none;" value="确定" />
                </form>
            </div>
        </div>
    </div>
</div>
</td>
</tr>
</table>
</div>
<div class="footerline"></div>
<div class="wrapper">
    <div class="footerleft">
        <div class="cont">
            <div><img src="/images/footergwzn.png" width="79" height="79"/></div>
            <b>交易条款</b><br/>
            <a href="" target="_blank">购物流程</a><br/>
            <a href="" target="_blank">发票制度</a><br/>
            <a href="" target="_blank">会员等级</a><br/>
            <a href="" target="_blank">积分制度</a><br/><br/>
        </div>
    </div>
    <div class="footerleft">
        <div class="cont">
            <div><img src="/images/footerpsfw.png" width="79" height="79"/></div>
            <b>配送服务</b><br/>
            <a href="" target="_blank">配送说明</a><br/>
            <a href="" target="_blank">配送范围</a><br/>
            <a href="" target="_blank">配送状态查询</a><br/><br/><br/>
        </div>
    </div>
    <div class="footerleft">
        <div class="cont">
            <div><img src="/images/footerzffs.png" width="79" height="79"/></div>
            <b>支付方式</b><br/>
            <a href="" target="_blank">支付宝支付</a><br/>
            <a href="" target="_blank">银联在线支付</a><br/>
            <a href="" target="_blank">货到付款</a><br/><br/><br/>
        </div>
    </div>
    <div class="footerleft">
        <div class="cont">
            <div><img src="/images/footershfw.png" width="79" height="79"/></div>
            <b>售后服务</b><br/>
            <a href="" target="_blank">服务承诺</a><br/>
            <a href="" target="_blank">退换货政策</a><br/>
            <a href="" target="_blank">退换货流程</a><br/><br/><br/>
        </div>
    </div>
    <div class="footerright" style="padding-left:50px;">
        手机客户端下载
        <div><img src="/images/weixin.png" width="104" height="104"/></div>
    </div>
    <div class="footerright" style="padding-right:50px;">
        手机客户端下载
        <div><img src="/images/weixin.png" width="104" height="104"/></div>
    </div>
</div>
</body>
</html>
