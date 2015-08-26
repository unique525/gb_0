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
            //启用
            $(".img_open_album").click(function(){
                var siteId = parseInt($(this).attr("title"));
                var userAlbumId = parseInt($(this).attr("idvalue"));
                var state = 10; //开启状态
                modifySiteState(userAlbumId,state,siteId);
            });
            //停用
            $(".img_close_album").click(function(){
                var siteId = parseInt($(this).attr("title"));
                var userAlbumId = parseInt($(this).attr("idvalue"));
                var state = 100; //停用状态
                modifySiteState(userAlbumId,state,siteId);
            });

            $(".rotate_image").click(function(){
                var UploadFileId = parseInt($(this).attr("idvalue"));
                rotateImage(UploadFileId,90);
            });

            $(".rotate_image2").click(function(){
                var UploadFileId = parseInt($(this).attr("idvalue"));
                rotateImage(UploadFileId,270);
            });

            var boxWidth = 240;//($(document).width() - 96) / 3;
            $(".li_list_width_img").css("width", boxWidth);



            //搜索
            $("#btn_search").click(function(){
                var searchKey=$("#search_key").val();
                var searchType=$("#search_type_box").val();
                window.location.href="/default.php?secu=manage&mod=user_album&m=list&site_id=1&tab_index=2&ps=23&search_key="+searchKey+"&search_type="+searchType;
            });
            //搜索
            $("#btn_student_list").click(function(){
                window.location.href="/default.php?secu=manage&mod=user_album&m=list&user_group_id=2&site_id=1&tab_index=2";
            });
            //搜索
            $("#btn_citizen_list").click(function(){
                window.location.href="/default.php?secu=manage&mod=user_album&m=list&user_group_id=3&site_id=1&tab_index=2";
            });

        });

        function modifySiteState(userAlbumId,state,siteId){
            if(userAlbumId>0){
                $.ajax({
                    type: "get",
                    url: "/default.php?secu=manage&mod=user_album&m=async_modify_state",
                    data: {
                        user_album_id: userAlbumId,
                        state: state,
                        site_id: siteId
                    },
                    dataType: "jsonp",
                    jsonp: "jsonpcallback",
                    success: function(data) {
                        $("#span_state_"+userAlbumId).html(formatSiteState(state));
                    }
                });
            }
        }

        function rotateImage(UploadFileId,angle){
            if(UploadFileId>0){
                $.ajax({
                    type: "get",
                    url: "/default.php?secu=manage&mod=upload_file&m=async_rotate_image&angle="+angle,
                    data: {
                        upload_file_id: UploadFileId
                    },
                    dataType: "jsonp",
                    jsonp: "jsonpcallback",
                    success: function(data) {

                        var d = new Date();
                        var src = $("#img" + UploadFileId).attr("src");
                        $("#img" + UploadFileId).attr("src", src+'?'+d.getTime());

                        //window.location.href = window.location.href;
                        //alert(data['result']);
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
        .li_list_width_img{height: 370px;};
    </style>
</head>
<body>
<div id="dialog_user_info_box" title="提示信息" style="display: none;">
    <div id="user_info_table" style="font-size: 14px;">
        <iframe id="user_info_dialog_frame" src="" frameBorder="0" style="border: 0; " scrolling="auto" width="100%" height="650"></iframe>
    </div>
</div>
<div class="div_list">


    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn">
            </td>
            <td style="text-align: right; margin-right: 8px;">
                <div id="search_box">
                    <label for="search_type_box"></label><select name="search_type_box" id="search_type_box" class="btn2" style="">
                        <option value="0">真实姓名</option>
                        <option value="1">手机号码</option>
                    </select>
                    <label for="search_key"></label><input type="text" id="search_key" name="search_key" class="input_box"/>
                    <input id="btn_search" class="btn2" value="查 询" type="button"/>
                    <span id="search_type" style="display: none"></span>
                    <input id="btn_student_list" class="btn2" value="学生列表" type="button"/>
                    <input id="btn_citizen_list" class="btn2" value="市民列表" type="button"/>
                </div>
            </td>
        </tr>
    </table>
    <ul id="sort_grid">
        <icms id="user_album_list">
            <item>
                <![CDATA[
                <li class="li_list_width_img">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td width="240">
                                <img id="img{f_UploadFileId}" class="img_avatar" title="{f_UserAlbumIntro}" width="244" height="240" src="{f_UploadFilePath}" style="display:block;" alt="会员头像"/>
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                <span class="rotate_image" style="cursor:pointer;" idvalue="{f_UploadFileId}">[旋转(90度)]</span>&nbsp;&nbsp;
                                <span class="rotate_image2" style="cursor:pointer;" idvalue="{f_UploadFileId}">[旋转(270度)]</span>
                                <div  ></div>
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
                                    <img class="img_open_album" title="{f_SiteId}" idvalue="{f_UserAlbumId}" src="/system_template/{template_name}/images/manage/start.jpg" style="cursor:pointer"/>&nbsp;&nbsp;
                                    <img class="img_close_album" title="{f_SiteId}" idvalue="{f_UserAlbumId}" src="/system_template/{template_name}/images/manage/stop.jpg" style="cursor:pointer"/>
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
