<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript">
        <!--

        $(function () {


        });

        function submitForm(closeTab) {
            if ($('#f_SiteName').val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("请输入站点名称");
            } else {
                if (closeTab == 1) {
                    $("#CloseTab").val("1");
                } else {
                    $("#CloseTab").val("0");
                }
                $("#mainForm").attr("action", "/default.php?secu=manage" +
                    "&mod=site&m={method}" +
                    "&site_id={SiteId}" +
                    "&tab_index=" + parent.G_TabIndex + "");

                $('#mainForm').submit();
            }
        }
        -->
    </script>
</head>
<body>
{common_body_deal}
<form id="mainForm" enctype="multipart/form-data"
      action=""
      method="post">
<div>
<table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td class="spe_line" height="40" align="right">
            <input class="btn" value="确认并关闭" type="button" onclick="submitForm(1)"/>
            <input class="btn" value="确认并继续" type="button" onclick="submitForm(0)"/>
            <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
        </td>
    </tr>
</table>
<table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_SiteName">站点名称：</label></td>
        <td class="spe_line">
            <input name="f_SiteName" id="f_SiteName" value="{SiteName}" type="text"
                   maxlength="100" class="input_box" style="width:300px;"/>
            <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_SiteType">站点类型：</label></td>
        <td class="spe_line">
            <select id="f_SiteType" name="f_SiteType">
                <option value="0">普通</option>
            </select>
            {s_SiteType}
        </td>
    </tr>


    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_SiteUrl">站点网址：</label></td>
        <td class="spe_line">
            <input id="f_SiteUrl" name="f_SiteUrl" type="text" value="{SiteUrl}" class="input_box"
                   style="width:400px;" maxlength="200"/>
        </td>
    </tr>


    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_SubDomain">站点二级域名：</label></td>
        <td class="spe_line">
            <input id="f_SubDomain" name="f_SubDomain" type="text" value="{SubDomain}" class="input_box"
                   style="width:400px;" maxlength="200"/>
        </td>
    </tr>


    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_BindDomain">绑定一级域名：</label></td>
        <td class="spe_line">
            <input id="f_BindDomain" name="f_BindDomain" type="text" value="{BindDomain}" class="input_box"
                   style="width:400px;" maxlength="500"/> 多个一级域名用,分隔
        </td>
    </tr>

    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_PublishType">站点发布方式：</label></td>
        <td class="spe_line">
            <select id="f_PublishType" name="f_PublishType">
                <option value="0">本地发布</option>
                <option value="1">FTP发布</option>
            </select>
            {s_PublishType}
        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_Sort">排序号：</label></td>
        <td class="spe_line">
            <input id="f_Sort" name="f_Sort" type="text" value="{Sort}" maxlength="10" class="input_number"/>
        </td>
    </tr>


    <tr>
        <td class="spe_line" height="30" align="right"><label for="file_title_pic">站点图标：</label></td>
        <td class="spe_line">
            <input id="file_title_pic" name="file_title_pic" type="file" class="input_box"
                   style="width:400px;background:#ffffff;margin-top:3px;"/> <span id="preview_title_pic" class="show_title_pic" idvalue="{TitlePicUploadFileId}" style="cursor:pointer">[预览]</span>

        </td>
    </tr>

    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_BrowserTitle">浏览器标题：</label></td>
        <td class="spe_line">
            <input id="f_BrowserTitle" name="f_BrowserTitle" type="text" value="{BrowserTitle}" class="input_box"
                   style="width:400px;" maxlength="200"/>
        </td>
    </tr>


    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_BrowserKeywords">浏览器关键字：</label></td>
        <td class="spe_line">
            <input id="f_BrowserKeywords" name="f_BrowserKeywords" type="text" value="{BrowserKeywords}"
                   class="input_box" style="width:400px;" maxlength="200"/>
        </td>
    </tr>

    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_BrowserDescription">浏览器描述文字：</label></td>
        <td class="spe_line">
            <input id="f_BrowserDescription" name="f_BrowserDescription" type="text" value="{BrowserDescription}"
                   class="input_box" style=" width: 400px;" maxlength="200"/>
        </td>
    </tr>
</table>

<table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td height="60" align="center">
            <input class="btn" value="确认并关闭" type="button" onclick="submitForm(1)"/>
            <input class="btn" value="确认并继续" type="button" onclick="submitForm(0)"/>
            <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
        </td>
    </tr>
</table>
</div>
</form>
</body>
</html>
