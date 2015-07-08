<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript">
        $(document).ready(function(){

            //格式化站点状态
            $(".span_state").each(function(){
                $(this).html(formatSiteState($(this).text()));
            });
            //开启站点
            $(".img_open_album").click(function(){
                var siteId = parseInt($(this).attr("idvalue"));
                var state = 0; //开启状态
                modifySiteState(siteId, state);
            });
            //停用站点
            $(".img_close_album").click(function(){
                var siteId = parseInt($(this).attr("idvalue"));
                var state = 100; //开启状态
                modifySiteState(siteId, state);
            });

            var boxWidth = 240;//($(document).width() - 96) / 3;
            $(".li_list_width_img").css("width", boxWidth);
        });

        function modifySiteState(siteId,state){
            if(siteId>0){
                $.ajax({
                    type: "get",
                    url: "/default.php?secu=manage&mod=site&m=async_modify_state",
                    data: {
                        site_id: siteId,
                        state:state
                    },
                    dataType: "jsonp",
                    jsonp: "jsonpcallback",
                    success: function(data) {
                        $("#span_state_"+siteId).html(formatSiteState(state));
                    }
                });
            }
        }

        /**
         * 格式化状态值
         * @return {string}
         */
        function formatSiteState(state){
            state = state.toString();
            switch (state){
                case "0":
                    return "未审核";
                    break;
                case "10":
                    return "已审核";
                    break;
                case "100":
                    return "<"+"span style='color:#990000'>停用<"+"/span>";
                    break;
                default :
                    return "未知";
                    break;
            }
        }
    </script>
    <style>
        .li_list_width_img{height: 350px;};
    </style>
</head>
<body>
<div id="dialog_user_info_box" title="提示信息" style="display: none;">
    <div id="user_info_table" style="font-size: 14px;">
        <iframe id="user_info_dialog_frame" src="" frameBorder="0" style="border: 0; " scrolling="auto" width="100%" height="650"></iframe>
    </div>
</div>
<div class="div_list">

    <ul id="sort_grid">
        <icms id="user_album_list">
            <item>
                <![CDATA[
                <li class="li_list_width_img">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td width="240">
                                <img class="img_avatar" title="{f_UserAlbumIntro}" width="244" height="240" src="{f_UploadFilePath}" style="display:block;" alt="会员头像"/>
                            </td>
                        </tr>
                        <tr>
                            <td align="left">
                                <div style="height:20px;line-height: 20px">姓名：{f_RealName}</div>
                            </td>
                        </tr>
                        <tr>
                            <td align="left">
                                <div style="height:20px;line-height: 20px">学校：{f_SchoolName}</div>
                            </td>
                        </tr>
                        <tr>
                            <td align="left">
                                <div style="height:20px;line-height: 20px">班级：{f_ClassName}</div>
                            </td>
                        </tr>
                        <tr>
                            <td align="left">
                                <div style="height:20px;line-height: 20px">手机：{f_UserMobile}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div>
                                    状态：<span id="span_state_{f_UserAlbumId}" class="span_state" idvalue="{f_UserAlbumId}">{f_State}</span>
                                    <img class="img_open_album" idvalue="{f_UserAlbumId}" src="/system_template/{template_name}/images/manage/start.jpg" style="cursor:pointer"/>&nbsp;&nbsp;
                                    <img class="img_close_album" idvalue="{f_UserAlbumId}" src="/system_template/{template_name}/images/manage/stop.jpg" style="cursor:pointer"/>
                                </div>
                            </td>
                        </tr>
                    </table>
                </li>
                ]]>
            </item>
        </icms>
        <li class="spe"></li>
    </ul>

    <div style="margin-top: 10px;">{pager_button}</div>
</div>
</body>
</html>
