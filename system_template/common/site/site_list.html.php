<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/channel/site_and_channel.js"></script>
</head>
<body>
<div class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn">
                <input id="btn_create" class="btn2" value="新建站点" title="新建站点" type="button"/>
            </td>
            <td style="text-align: right; margin-right: 8px;">
                <div id="search_box">
                    <label for="search_type_box"></label>
                    <select name="search_type_box" id="search_type_box" style="display: none">
                        <option value="default">基本搜索</option>
                    </select>
                    <label for="search_key"></label><input type="text" id="search_key" name="search_key" class="input_box"/>
                    <input id="btn_search" class="btn2" value="查 询" type="button"/>
                    <span id="search_type" style="display: none"></span>
                </div>
            </td>
        </tr>
    </table>
    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
        <tr class="grid_title">
            <td style="width: 30px; text-align: center; cursor: pointer;" id="btn_select_all">全</td>
            <td style="width: 40px; text-align: center;">编辑</td>
            <td>站点名称</td>
            <td style="width: 50px; text-align: center;">排序号</td>
            <td style="width: 180px;">创建时间</td>
            <td style="width: 100px;">创建人</td>
            <td style="width: 40px; text-align: center;">状态</td>
            <td style="width: 80px;text-align:center;">启用|停用</td>
            <td style="width: 40px;">相关管理</td>
        </tr>
    </table>
    <ul id="sort_grid">
        <icms_list id="document_news_list" type="list">
            <item>
                <![CDATA[
                <li id="sort_{f_SiteId}">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item">
                            <td class="spe_line2" style="width:30px;text-align:center;"><input class="input_select" type="checkbox" name="input_select" value="{f_SiteId}"/></td>
                            <td class="spe_line2" style="width:40px;text-align:center;"><img class="btn_modify" src="/system_template/{template_name}/images/manage/edit.gif" idvalue="{f_SiteId}" alt="编辑"/></td>
                            <td class="spe_line2"><a target="_blank" href="{f_SiteUrl}">{f_SiteName}</a></td>
                            <td class="spe_line2" style="width:50px;text-align:center;" title="文档的排序数字，越大越靠前">{f_Sort}</td>
                            <td class="spe_line2" style="width:180px;text-align:center;" title="站点创建时间">{f_CreateDate}</td>
                            <td class="spe_line2" style="width:100px;text-align:center;" title="创建人：{f_ManageUserName}">{f_ManageUserName}</td>
                            <td class="spe_line2" style="width:40px;text-align:center;"><span class="span_state" id="{f_SiteId}">{f_State}</span></td>
                            <td class="spe_line2" style="width:80px;text-align:center;"><img src="/system_template/{template_name}/images/manage/start.jpg" style="cursor:pointer"/>&nbsp;&nbsp;&nbsp;&nbsp;<img src="/system_template/{template_name}/images/manage/stop.jpg" style="cursor:pointer"/></td>
                            <td class="spe_line2" style="width:40px;text-align:center;"></td>
                        </tr>
                    </table>
                </li>
                ]]>
            </item>
        </icms_list>
    </ul>
    <div>{pager_button}</div>
</div>
</body>
</html>
