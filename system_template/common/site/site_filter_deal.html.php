<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/site/site_ad.js"></script>
    <script type="text/javascript">
        function submitForm(continueCreate) {
            var submit=1;
            if ($('#f_SiteFilterWord').val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("请输入过滤字符");
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
      action="/default.php?secu=manage&mod=site_filter&m={method}&site_id={SiteId}&site_name={SiteName}&site_filter_id={SiteFilterId}&tab_index={TabIndex}"
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
                <td class="spe_line" height="30" align="right"><label for="f_SiteId">所在站点：</label></td>
                <td class="spe_line">
                    <select id="f_SiteId" name="f_SiteId" >
                        <option value="0">所有站点</option>
                        <option value="{SiteId}">{SiteName}</option>
                    </select>
                    {s_SiteId}
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_SiteFilterWord">过滤字符：</label></td>
                <td class="spe_line"><input name="f_SiteFilterWord" id="f_SiteFilterWord" value="{SiteFilterWord}" type="text" class="input_box" style=" width: 300px;" maxlength="20" {ReadOnly}/></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_IntervalCount">间隔字符数：</label></td>
                <td class="spe_line"><input name="f_IntervalCount" id="f_IntervalCount" value="{IntervalCount}" type="number" class="input_number" style=" width: 300px;"   /></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_State">状态：</label></td>
                <td class="spe_line">
                    <select id="f_State" name="f_State">
                        <option value="0" >正常</option>
                        <option value="100" >停用</option>
                    </select>
                    {s_State}
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_SiteFilterArea">过滤范围：</label></td>
                <td class="spe_line">
                    <select id="f_SiteFilterArea" name="f_SiteFilterArea">
                        <option value="0">全局</option>
                        <option value="1">资讯</option>
                        <option value="2">活动</option>
                        <option value="3">会员</option>
                        <option value="4">评论</option>
                    </select>
                    {s_SiteFilterArea}
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_SiteFilterType">过滤类型：</label></td>
                <td class="spe_line">
                    <select id="f_SiteFilterType" name="f_SiteFilterType">
                        <option value="0">替换</option>
                        <option value="1">阻止</option>
                    </select>
                    {s_SiteFilterType}
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
                            <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</form>
</body>
</html>
