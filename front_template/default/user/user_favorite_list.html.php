<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>会员中心-我的收藏</title>
    <link href="/images/common_css.css" rel="stylesheet" type="text/css"/>
    <link href="/images/user_layout.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .right {
            cursor: pointer
        }
    </style>
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/front_js/common.js"></script>
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript" src="/front_js/user/user_car.js"></script>

    <script type="text/javascript">

        var siteId = parseInt("{SiteId}");


        $(function () {


//            $(".right").click(function () {
//                var idvalue = $(this).attr("idvalue");
//                var state = $("#" + idvalue + "_child").css("display");
//                if (state == "none") {
//                    $(".right_child").css("display", "none");
//                    $(".right_img").attr("src", "/images/icon_jia.png");
//                    $("#" + idvalue + "_img").attr("src", "/images/icon_jian.png");
//                    $("#" + idvalue + "_child").css("display", "inline");
//                } else {
//                    $("#" + idvalue + "_img").attr("src", "/images/icon_jia.png");
//                    $("#" + idvalue + "_child").css("display", "none");
//                }
//            });

            $(".delete_user_favorite").click(function(){
                var userFavoriteId = $(this).attr("idvalue");
                    $.ajax({
                        url:"/default.php?mod=user_favorite&a=async_remove_bin&user_favorite_id="+userFavoriteId,
                        dataType:"jsonp",
                        jsonp:"jsonpcallback",
                        success:function(data){
                            var result = data["result"];
                            if(result > 0){
                                window.location.href=window.location.href;
                            }else{
                                alert("删除失败");
                            }
                        }
                    });
            });
        });
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
                    <div class="rightbar2"><a href="/">星滋味首页</a> ><a href="/default.php?mod=user&a=homepage">会员中心</a>>我的收藏</div>
                </div>
                <div style="padding:10px 10px;">
                    <ul class="faver_list">
                        <icms id="user_favorite_list">
                            <item><![CDATA[
                                <li class="order_number"> <a href="{f_UserFavoriteUrl}" target="_blank">
                                        <div><img src="{f_UploadFilePath}" width="200" height="200"/></div></a>
                                    <a class="faver_title" href="{f_UserFavoriteUrl}" target="_blank">{f_UserFavoriteTitle}</a>
                                    <div class="delete_user_favorite" style="cursor:pointer" idvalue="{f_UserFavoriteId}">删除</div>
                                </li>
                                ]]></item>
                        </icms>
                        <div class="clean"></div>

                    </ul>
                </div>
                <div class="flips">
                    {pagerButton}
                </div>
                <div class="clean"></div>
            </td>
        </tr>
</table>
</div>
<pre_temp id="8"></pre_temp>
</body>
</html>
