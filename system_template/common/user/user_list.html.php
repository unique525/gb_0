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
            $(".modify_user_info").click(function(event){
                event.preventDefault();
                var userId = $(this).attr("idvalue");
                parent.G_TabUrl = "/default.php?secu=manage&mod=user&user_id="+userId+"&m=modify&site_id="+ parent.G_NowSiteId
                    +"&p="+parent.G_NowPageIndex+"&ps="+parent.G_PageSize;
                parent.G_TabTitle = '编辑会员信息';
                parent.addTab();
            });
        });
    </script>
</head>
<body>
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
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td width="60">
                                    <img class="img_avatar" width="60" height="60" src="{f_Avatar}" alt="会员头像"/>
                                </td>
                                <td width="5"></td>
                                <td align="left" valign="top">
                                    <div>{f_UserName}</div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" height="5"></td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <span class="btn2 modify_user_info" idvalue="{f_UserId}">修改会员信息</span>
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
