<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript">
        function submitForm(continueCreate) {
            var submit=1;
            if ($('#f_FtpWord').val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("请输入host");
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
      action="/default.php?secu=manage&mod=ftp&m={method}&site_id={SiteId}&site_name={SiteName}&ftp_id={FtpId}&tab_index={TabIndex}"
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
                <td class="spe_line">
                    <input name="SiteName" id="SiteName" value="{SiteName}" type="text" readonly="readonly" class="input_box" style=" width: 300px;"/>
                    <input id="f_SiteId" name="f_SiteId" type="hidden" value="{SiteId}"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_FtpHost">服务器host地址：</label></td>
                <td class="spe_line"><input name="f_FtpHost" id="f_FtpHost" value="{FtpHost}" type="text" class="input_box" style=" width: 300px;"/></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_FtpPort">连接端口：</label></td>
                <td class="spe_line"><input name="f_FtpPort" id="f_FtpPort" value="{FtpPort}" type="number" class="input_number" style=" width: 300px;"   />(默认为 21)</td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_PasvMode">连接模式：</label></td>
                <td class="spe_line">
                    <select id="f_PasvMode" name="f_PasvMode">
                        <option value="0" >非被动模式</option>
                        <option value="1" >被动模式</option>
                    </select>
                    {s_PasvMode}
                    (一般情况下非被动模式即可)
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_FtpUser">连接用户名：</label></td>
                <td class="spe_line"><input name="f_FtpUser" id="f_FtpUser" value="{FtpUser}" type="text" class="input_box" style=" width: 300px;" maxlength="20"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_FtpPass">密码：</label></td>
                <td class="spe_line"><input name="f_FtpPass" id="f_FtpPass" value="{FtpPass}" type="password" class="input_box" style=" width: 300px;" maxlength="20"/></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_Timeout">传输超过时间：</label></td>
                <td class="spe_line"><input name="f_Timeout" id="f_Timeout" value="{Timeout}" type="number" class="input_box" style=" width: 300px;" maxlength="20"/>(单位：秒，0 为服务器默认,一般为90)</td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_RemotePath">路径：</label></td>
                <td class="spe_line"><input name="f_RemotePath" id="f_RemotePath" value="{RemotePath}" type="text" class="input_box" style=" width: 300px;" maxlength="20"/>(结尾不要加斜杠“/”)</td>
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
