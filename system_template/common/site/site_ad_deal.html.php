<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/site/site_ad.js"></script>
    <script type="text/javascript">
        function submitForm(continueCreate) {
            var submit=1;
            if ($('#f_SiteAdName').val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("请输入广告位名称");
                submit=0;
            }
            if(submit==1) {
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
      action="/default.php?secu=manage&mod=site_ad&m={method}&site_id={SiteId}&site_name={SiteName}&site_ad_id={SiteAdId}&tab_index={TabIndex}"
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
    <div style="margin: 0 auto 10px;">
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="spe_line" height="30" align="right"><label for="SiteName">站点名称：</label></td>
                <td class="spe_line"><input name="SiteName" id="SiteName" value="{SiteName}" type="text" class="input_box" style=" width: 300px;" disabled />
                    <input name="f_SiteId" id="f_SiteId" value="{SiteId}" type="hidden" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_SiteAdName">广告位名称：</label></td>
                <td class="spe_line"><input name="f_SiteAdName" id="f_SiteAdName" value="{SiteAdName}" type="text" class="input_box" style=" width: 300px;" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_SiteAdWidth">广告位宽：</label></td>
                <td class="spe_line"><input name="f_SiteAdWidth" id="f_SiteAdWidth" value="{SiteAdWidth}" type="text" class="input_box" style=" width: 300px;" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_SiteAdHeight">广告位高：</label></td>
                <td class="spe_line"><input name="f_SiteAdHeight" id="f_SiteAdHeight" value="{SiteAdHeight}" type="text" class="input_box" style=" width: 300px;" /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_SiteAdType">广告位类型：</label></td>
                <td class="spe_line">
                    <select id="f_SiteAdType" name="f_SiteAdType">
                        <option value="0">JS</option>
                        <option value="1">HTML</option>
                    </select>
                    {s_SiteAdType}
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_ShowType">显示类型：</label></td>
                <td class="spe_line">
                    <select id="f_ShowType" name="f_ShowType">
                        <option value="0">图片</option>
                        <option value="1">文字</option>
                        <option value="2">轮换</option>
                        <option value="3">随机</option>
                        <option value="4">落幕</option>
                    </select>
                    {s_ShowType}
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_ShowNumber">是否显示轮换数字：</label></td>
                <td class="spe_line">
                    <select id="f_ShowNumber" name="f_ShowNumber">
                        <option value="0">否</option>
                        <option value="1">是</option>
                    </select>
                    {s_ShowNumber}
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_ShowOnce">是否只显示一次：</label></td>
                <td class="spe_line">
                    <select id="f_ShowOnce" name="f_ShowOnce">
                        <option value="0">否</option>
                        <option value="1">是</option>
                    </select>
                    {s_ShowOnce}
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_State">是否启用：</label></td>
                <td class="spe_line">
                    <select id="f_State" name="f_State">
                        <option value="0" >启用</option>
                        <option value="100" >停用</option>
                    </select>
                    {s_State}
                </td>
            </tr>

        </table>
        <div id="bot_button">
            <div style="padding-top:3px;">
                <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td colspan="2" height="30" align="center">
                            <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/>
                            <input class="btn" value="确认并继续新增" style="display:{display}" type="button" onclick="submitForm(1)"/>
                            <input class="btn" value="取 消" type="button" onclick="window.location.href='/default.php?secu=manage&mod=site_ad&m=list&site_id={SiteId}&site_name={SiteName}';"/>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</form>
</body>
</html>
