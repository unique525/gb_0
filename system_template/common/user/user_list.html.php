<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/user/user_manage.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#btn_create").click(function(event){
                event.preventDefault();
                parent.G_TabUrl = "/default.php?secu=manage&mod=user&m=create&site_id="+ parent.G_NowSiteId
                    +"&p="+parent.G_NowPageIndex+"&ps="+parent.G_PageSize;
                parent.G_TabTitle = '新增会员';
                parent.addTab();
            });
            $(".modify_user").click(function(event){
                event.preventDefault();
                var userId = $(this).attr("idvalue");
                parent.G_TabUrl = "/default.php?secu=manage&mod=user&user_id="+userId+"&m=modify&site_id="+ parent.G_NowSiteId
                    +"&p="+parent.G_NowPageIndex+"&ps="+parent.G_PageSize;
                parent.G_TabTitle = '编辑会员信息';
                parent.addTab();
            });

            $(".modify_user_info").click(function(event){
                event.preventDefault();
                var userId = $(this).attr("idvalue");
                var state = $("#state_"+userId).attr("idvalue");
                if(state < 100){
                    var url='/default.php?secu=manage&mod=user_info&m=modify&user_id='+userId+'&site_id='+parent.G_NowSiteId
                        +"&p="+parent.G_NowPageIndex+"&ps="+parent.G_PageSize;;
                    $("#user_info_dialog_frame").attr("src",url);
                    $("#dialog_user_info_box").dialog({
                        hide:true,    //点击关闭是隐藏,如果不加这项,关闭弹窗后再点就会出错.
                        autoOpen:true,
                        height:650,
                        width:1250,
                        modal:true, //蒙层（弹出会影响页面大小）
                        title:'会员详细信息',
                        overlay: {opacity: 0.5, background: "black" ,overflow:'auto'}
                    });
                }else{
                    alert("用户为停用状态，请修改为非停用状态再编辑用户详细信息");
                }
            });
        });
    </script>
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
                <input id="btn_create" class="btn2" value="新增会员" title="新增会员" type="button"/>
                </td>
            <td style="text-align: right; margin-right: 8px;">
                <div id="search_box">
                    <label for="search_type_box"></label><select name="search_type_box" id="search_type_box" style="display: none">
                        <option value="default">会员名</option>
                        <option value="source">注册IP</option>
                    </select>
                    <label for="search_key"></label><input type="text" id="search_key" name="search_key" class="input_box"/>
                    <input id="btn_search" class="btn2" value="查 询" type="button"/>
                    <span id="search_type" style="display: none"></span>
                    </div>
            </td>
        </tr>
    </table>

    <ul id="sort_grid">
        <icms id="user_list">
            <item>
                <![CDATA[
                    <li class="user_item">
                        <table width="100%" cellpadding="0" cellspacing="0" border="1">
                            <tr>
                                <td width="60" height="60">
                                    <img class="img_avatar" width="60" height="60" src="{f_Avatar}" alt="会员头像"/>
                                </td>
                                <td width="5"></td>
                                <td align="left" valign="top">
                                    <div style="width:245px;overflow: hidden">{f_UserName}</div>
                                    状态：<span class="span_state" id="state_{f_UserId}" idvalue="{f_State}"></span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" height="5"></td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <span class="btn2 modify_user" idvalue="{f_UserId}">修改</span>
                                    <span class="btn2 modify_user_info" idvalue="{f_UserId}">修改详细信息</span>
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
