<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript">
        $(document).ready(function(){
            $("#btn_create").click(function(event){
                event.preventDefault();
                parent.G_TabUrl = "/default.php?secu=manage&mod=user&m=create&site_id="+ parent.G_NowSiteId
                    +"&p="+parent.G_NowPageIndex+"&ps="+parent.G_PageSize;
                parent.G_TabTitle = '新增会员';
                parent.addTab();
            });

            $("#btn_search").click(function(event){
                event.preventDefault();


                var searchKey = $("#search_key").val();
                if(searchKey.length<=0){
                    alert("查询字符不能为空");
                    return;
                }
                searchKey = encodeURIComponent(searchKey);

                var searchType = $("#search_type_box").val();

                window.location.href = "/default.php?secu=manage&mod=user_role&m=list&site_id="
                    + parent.G_NowSiteId + "&search_key=" + searchKey
                    + "&search_type=" + searchType;
            });


            $(".modify_user_role").click(function(event){
                event.preventDefault();
                var userId = $(this).attr("idvalue");
                parent.G_TabUrl = "/default.php?secu=manage&mod=user_role&user_id="+userId+"&m=modify&site_id="+ parent.G_NowSiteId
                    +"&p="+parent.G_NowPageIndex+"&ps="+parent.G_PageSize;
                parent.G_TabTitle = '编辑会员身份信息';
                parent.addTab();
            });


            /***会员管理页面js***/
            $(".img_avatar").each(function(){
                var avatar = $(this).attr("src");
                if(avatar == undefined || avatar.length<=0){
                    $(this).attr("src", "/front_template/default/skins/gray/no_avatar.gif");
                }
            });


            var boxWidth = 240;//($(document).width() - 96) / 3;
            $(".li_list_width_img").css("width", boxWidth);
        });
    </script>
</head>
<body>
<div class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn">
                <input id="btn_create" style="display:none;" class="btn2" value="新增会员" title="新增会员" type="button"/>
            </td>
            <td style="text-align: right; margin-right: 8px;">
                <div id="search_box">
                    <label for="search_type_box"></label>
                    <select name="search_type_box" id="search_type_box">
                        <option value="1">会员名</option>
                        <option value="2">注册IP</option>
                        <option value="3">手机号码</option>
                    </select>
                    <label for="search_key"></label><input type="text" id="search_key" name="search_key" class="input_box"/>
                    <input id="btn_search" class="btn2" value="查 询" type="button"/>
                    <span id="search_type" style="display: none"></span>
                </div>
            </td>
        </tr>
    </table>

    <ul id="sort_grid">
        <icms id="user_role_list">
            <item>
                <![CDATA[
                <li class="li_list_width_img">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td width="85">
                                <img class="img_avatar" width="80" height="80" src="" style="display:block;" alt="会员头像"/>
                            </td>
                            <td valign="top" align="left">
                                <div style="height:20px;line-height: 20px">{f_UserName}</div>
                                <div style="height:20px;line-height: 20px">{f_UserMobile}</div>
                                <div style="height:20px;line-height: 20px;overflow:hidden;">{f_UserEMail}</div>
                                <div>{f_UserGroupName}</div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div style="float:left;margin:2px 6px">状态：<span class="span_state" id="state_{f_UserId}" idvalue="{f_State}"></span></div>
                                <div style="float:left;margin:2px 0">
                                    <span class="btn2 modify_user_role" idvalue="{f_UserId}">修改身份</span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </li>
                ]]>
            </item>
        </icms>
    </ul>
    <div class="spe">{pager_button}</div>
</div>
</body>
</html>
