<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript" src="/system_js/xheditor-1.1.13/xheditor-1.1.13-zh-cn.min.js"></script>
    <script type="text/javascript" src="/system_js/manage/forum/forum.js"></script>
    <script type="text/javascript">
        <!--
        var editor;
        $(function () {
            editor = $('#f_ForumInfo').xheditor({tools:'full'});

            $("#preview_title_pic1").click(function () {
                var imgTitlePic1 = "{TitlePic1}";
                if (imgTitlePic1 !== '') {
                    var imageOfTitlePic1 = new Image();
                    imageOfTitlePic1.src = imgTitlePic1;
                    $("#dialog_box").dialog({
                        width: imageOfTitlePic1.width + 30,
                        height: imageOfTitlePic1.height + 30
                    });
                    var imgHtml = '<' + 'img src="' + imgTitlePic1 + '" alt="" />';
                    $("#dialog_content").html(imgHtml);

                }
                else {
                    $("#dialog_box").dialog({width: 300, height: 100});
                    $("#dialog_content").html("还没有上传题图1");
                }
            });


            $("#preview_title_pic2").click(function () {
                var imgTitlePic2 = "{TitlePic2}";
                if (imgTitlePic2 !== '') {
                    var imageOfTitlePic2 = new Image();
                    imageOfTitlePic2.src = imgTitlePic2;
                    $("#dialog_box").dialog({
                        width: imageOfTitlePic2.width + 30,
                        height: imageOfTitlePic2.height + 30
                    });
                    var imgHtml = '<' + 'img src="' + imgTitlePic2 + '" alt="" />';
                    $("#dialog_content").html(imgHtml);
                }
                else {
                    $("#dialog_box").dialog({width: 300, height: 100});
                    $("#dialog_content").html("还没有上传题图2");
                }
            });

        });

        function submitForm(continueCreate) {
            if ($('#f_ForumName').val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("请输入版块名称");
            } else if ($('#f_ForumInfo').text().length > 250) {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("版块简介不能超过250个字符");
            } else {
                if(continueCreate == 1){
                    $("#CloseTab").val("0");
                }else{
                    $("#CloseTab").val("1");
                }
                $('#mainForm').submit();
            }
        }
        -->
    </script>
</head>
<body>
{common_body_deal}
<form id="mainForm" enctype="multipart/form-data" action="/default.php?secu=manage&mod=forum&m={method}&site_id={SiteId}&parent_id={ParentId}" method="post">
<div>
<table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td class="spe_line" height="40" align="right">
            <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/> <input class="btn" value="确认并继续新增" type="button" onclick="submitForm(1)"/> <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
        </td>
    </tr>

</table>
<table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td class="spe_line" width="20%" height="30" align="right">上级版块：</td>
        <td class="spe_line">{ParentName}
            <input name="f_ParentId" type="hidden" value="{ParentId}"/>
            <input name="f_SiteId" type="hidden" value="{SiteId}"/>
            <input name="f_Rank" type="hidden" value="{Rank}"/>
            <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_ForumName">版块名称：</label></td>
        <td class="spe_line"><input name="f_ForumName" id="f_ForumName" value="{ForumName}" type="text" class="input_box" style="width:300px;"/></td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_ForumType">版块类型：</label></td>
        <td class="spe_line">
            <select id="f_ForumType" name="f_ForumType">
                <option value="0">只允许会员发帖和回复</option>
                <option value="5">只允许会员发帖、但不能回复</option>
                <option value="10">允许会员、游客发帖和回复</option>
                <option value="11">允许会员、游客发帖，但不能回复</option>
                <option value="12">允许会员、游客回复，但不能发帖</option>
            </select>
            {s_ForumType}
        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_ForumAccess">会员访问限制：</label></td>
        <td class="spe_line">
            <select id="f_ForumAccess" name="f_ForumAccess">
                <option value="0">允许会员正常访问</option>
                <option value="10">禁止会员访问(管理员除外)</option>
                <option value="20">允许特定会员访问</option>
                <option value="21">允许特定身份的会员访问</option>
                <option value="22">允许发帖数大于设定数量的会员访问</option>
                <option value="23">允许积分数大于设定数量的会员访问</option>
                <option value="24">允许金钱数大于设定数量的会员访问</option>
                <option value="25">允许魅力数大于设定数量的会员访问</option>
                <option value="26">允许经验数大于设定数量的会员访问</option>
            </select>
            {s_ForumAccess}
        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_ForumGuestAccess">游客访问限制：</label></td>
        <td class="spe_line">
            <select id="f_ForumGuestAccess" name="f_ForumGuestAccess">
                <option value="0">允许游客访问</option>
                <option value="10">禁止游客访问</option>
            </select>
            {s_ForumGuestAccess}
        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_ForumMode">显示模式：</label></td>
        <td class="spe_line">
            <select id="f_ForumMode" name="f_ForumMode">
                <option value="0">一排四个版块，附带5条最新主题</option>
                <option value="5">一排三个版块，附带5条最新主题</option>
                <option value="10">一排四个版块，不附带最新主题</option>
                <option value="15">一排三个版块，不附带最新主题</option>
                <option value="20">一排一个版块，传统横排显示</option>
            </select>
            {s_ForumMode}
        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_ForumAuditType">发帖审核设置：</label></td>
        <td class="spe_line">
            <select id="f_ForumAuditType" name="f_ForumAuditType">
                <option value="0">无需审核</option>
                <option value="10">先发后审（先发帖，后审核）</option>
                <option value="11">先审后发（先审核，后显示）</option>
            </select>
            {s_ForumAuditType}
        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_Sort">排序号：</label></td>
        <td class="spe_line">
            <input id="f_Sort" name="f_Sort" type="text" value="{Sort}" maxlength="10" class="input_number"/>
        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="file_forum_pic_1">版块图片1：</label></td>
        <td class="spe_line">
            <input id="file_forum_pic_1" name="file_forum_pic_1" type="file" class="input_box" style="width:400px;background:#ffffff;margin-top:3px;"/> <span id="preview_forum_pic1" style="cursor:pointer">[预览]</span>
        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="file_forum_pic_2">版块图片2：</label></td>
        <td class="spe_line">
            <input id="file_forum_pic_2" name="file_forum_pic_2" type="file" class="input_box" style="width:400px;background:#ffffff;margin-top:3px;"/> <span id="preview_forum_pic2" style="cursor:pointer">[预览]</span>
        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_ForumInfo">版块介绍：</label></td>
        <td class="spe_line">
            <textarea cols="30" rows="30" id="f_ForumInfo" name="f_ForumInfo" style="width:80%;height:150px;">{ForumInfo}</textarea>
        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_ForumRule">版块介绍：</label></td>
        <td class="spe_line">
            <textarea cols="30" rows="30" id="f_ForumRule" name="f_ForumRule" style="width:80%;height:150px;">{ForumRule}</textarea>
        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_ForumAdContent">版块广告代码：</label></td>
        <td class="spe_line">
            <textarea cols="30" rows="30" id="f_ForumAdContent" name="f_ForumAdContent" style="width:80%;height:150px;">{ForumAdContent}</textarea>
        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_AutoOpTopic">发帖特殊模式：</label></td>
        <td class="spe_line">
            <select id="f_AutoOpTopic" name="f_AutoOpTopic">
                <option value="0">正常发帖</option>
                <option value="10">发帖后自动设为禁止回复</option>
            </select>
            {s_AutoOpTopic}
        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_AutoAddTopicTitlePre">自动添加主题前缀：</label></td>
        <td class="spe_line">
            <select id="f_AutoAddTopicTitlePre" name="f_AutoAddTopicTitlePre">
                <option value="0">不自动添加</option>
                <option value="10">自动添加【发帖日期】</option>
            </select>
            {s_AutoAddTopicTitlePre}
        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_CloseUpload">关闭上传功能：</label></td>
        <td class="spe_line">
            <select id="f_CloseUpload" name="f_CloseUpload">
                <option value="0">不关闭</option>
                <option value="10">关闭</option>
            </select>
            {s_CloseUpload}
        </td>
    </tr>
</table>
<table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td height="60" align="center">
            <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/> <input class="btn" value="确认并继续新增" type="button" onclick="submitForm(1)"/> <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
        </td>
    </tr>
</table>

</div>
</form>
</body>
</html>
