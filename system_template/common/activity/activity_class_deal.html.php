<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript">
        $().ready(function () {
            var siteName = Request["site_name"];
            siteName=decodeURI(siteName);
            $('#SiteName').val(siteName);
        });
        function submitForm(continueCreate) {
            var submit = 1;
            if ($('#f_ActivityClassName').val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("请输入活动类型名称");
                submit = 0;
            }
            if (submit == 1) {
                if (continueCreate == 1) {
                    $("#CloseTab").val("0");
                } else {
                    $("#CloseTab").val("1");
                }
                $('#main_form').submit();
            }
        }
    </script>
</head>
<body>
{common_body_deal}
<form id="main_form" enctype="multipart/form-data"
      action="/default.php?secu=manage&mod=activity_class&m={method}&channel_id={ChannelId}&site_id={SiteId}&activity_class_id={ActivityClassId}&tab_index={TabIndex}"
      method="post">
    <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
    <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class="spe_line" height="40" align="right">
                <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/>
                <input class="btn" value="确认并继续新增" style="display:{display}" type="button" onclick="submitForm(1)"/>
                <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
            </td>
        </tr>
    </table>
    <div style="margin: 0 auto;">
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="spe_line" height="30" align="right"><label for="SiteName" title="{SiteId}">所属站点名称：</label>
                </td>
                <td class="spe_line"><input name="SiteName" id="SiteName" value="" type="text" class="input_box"
                                            style=" width: 300px;" disabled/>
                    <label for="f_SiteId">
                        <input name="f_SiteId" id="f_SiteId" value="{SiteId}" type="text" class="input_box"
                               style=" width: 300px;display:none"/></label>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="ChannelName"
                                                                      title="{ChannelId}">所属频道名称：</label></td>
                <td class="spe_line"><input name="ChannelName" id="ChannelName" value="{ChannelName}" type="text"
                                            class="input_box" style=" width: 300px;" disabled/>
                    <label for="f_ChannelId">
                        <input name="f_ChannelId" id="f_ChannelId" value="{ChannelId}" type="text" class="input_box"
                               style=" width: 300px;display:none"/></label>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_ActivityClassName"
                                                                      title="{ActivityClassId}">活动分类名称：</label></td>
                <td class="spe_line"><input name="f_ActivityClassName" id="f_ActivityClassName"
                                            value="{ActivityClassName}" type="text" class="input_box"
                                            style=" width: 300px;"/>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_ActivityType">活动分类类型：</label></td>
                <td class="spe_line">
                    <select id="f_ActivityType" name="f_ActivityType">
                        <option value="0">线下活动</option>
                    </select>
                    {s_ActivityType}
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_State">是否启用：</label></td>
                <td class="spe_line">
                    <select id="f_State" name="f_State">
                        <option value="0">开启</option>
                        <option value="100">停用</option>
                    </select>
                    {s_State}
                </td>
            </tr>

        </table>
    </div>
    <div id="bot_button">
        <div style="padding-top:3px;">
            <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td colspan="2" height="30" align="center">
                        <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/>
                        <input class="btn" value="确认并继续新增" style="display:{display}" type="button"
                               onclick="submitForm(1)"/>
                        <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</form>
</body>
</html>
