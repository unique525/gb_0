<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/user/user_manage.js"></script>
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
                            <td width="60"><img class="img_avatar" width="60" height="60" src="/upload/Desert.jpg" alt="会员头像"/></td>
                            <td>
                                <div>{f_UserName}</div>
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
