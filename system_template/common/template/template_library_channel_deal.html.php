<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript" src="/system_js/xheditor-1.1.14/xheditor-1.1.14-zh-cn.min.js"></script>
    <script type="text/javascript" src="/system_js/ajax_file_upload.js"></script>
    <script type="text/javascript" src="/system_js/upload_file.js"></script>
    <script type="text/javascript">
        <!--
        var editor;
        $(function () {
            editor = $('#f_ChannelIntro').xheditor();
            $("#f_CreateDate").datepicker({
                dateFormat: 'yy-mm-dd',
                numberOfMonths: 1,
                showButtonPanel: true
            });


            var selChannelType = $("#f_ChannelType");
            selChannelType.change(function () {
                var dnt = $(this).val();
                if (dnt == '50') {
                    $(".type_0").css("display", "none");
                    $(".type_1").css("display", "");
                } else {
                    $(".type_0").css("display", "");
                    $(".type_1").css("display", "none");
                }

            });
            selChannelType.change();



            //去掉modify时 parent选择框内id=自身id的项（父频道不能是自己）
            $(".parent_id[value='{TemplateLibraryChannelId}']").hide();
        });

        function submitForm(closeTab) {
            if ($('#f_ChannelName').val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("请输入频道名称");
            } else if ($('#f_ChannelIntro').text().length > 1000) {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("频道简介不能超过1000个字符");
            } else {
                if (closeTab == 1) {
                    $("#CloseTab").val("1");
                } else {
                    $("#CloseTab").val("0");
                }
                $('#mainForm').submit();
            }
        }
        -->
    </script>
</head>
<body>
{common_body_deal}
<form id="mainForm" enctype="multipart/form-data"
      action="/default.php?secu=manage&mod=template_library_channel&m={method}&template_library_id={TemplateLibraryId}&template_library_channel_id={TemplateLibraryChannelId}&tab_index={tab_index}"
      method="post">
<div>
<table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td class="spe_line" height="40" align="right">
            <input class="btn" value="确认并关闭" type="button" onclick="submitForm(1)"/>
            <input class="btn" style="display: {display}" value="确认并继续" type="button" onclick="submitForm(0)"/>
            <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
        </td>
    </tr>
</table>
<table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr style="display: {display}">
        <td class="spe_line" width="20%" height="30" align="right"><label for="f_ParentId">上级频道：</label></td>
        <td class="spe_line">
            <select id="f_ParentId" name="f_ParentId">
                <option value="0">默认</option>
                <icms id="template_library_channel_list" type="list">
                    <item><![CDATA[
                        <option class="parent_id" value="{f_TemplateLibraryChannelId}" idvalue="{f_Rank}">{f_ChannelName}</option>
                        ]]>
                    </item>
                </icms>
            </select>
            {s_ParentId}
        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_ChannelName">频道名称：</label></td>
        <td class="spe_line">
            <input name="f_ChannelName" id="f_ChannelName" value="{ChannelName}" type="text" class="input_box" style="width:300px;"/>
            <input name="f_SiteId" type="hidden" value="{SiteId}"/>
            <input name="f_TemplateLibraryId" type="hidden" value="{TemplateLibraryId}"/>
            <input name="f_Rank" type="hidden" value="{Rank}"/>
            <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_CreateDate">创建时间：</label></td>
        <td class="spe_line"><input id="f_CreateDate" name="f_CreateDate" value="{CreateDate}" type="text" class="input_box" style="width:80px;"/></td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_ChannelType">频道类型：</label></td>
        <td class="spe_line">
            <select id="f_ChannelType" name="f_ChannelType">
                <option value="1">新闻资讯</option>
                <option value="2">咨询答复</option>
                <option value="3">图片轮换</option>
                <option value="4">产品</option>
                <option value="5">频道结合产品</option>
                <option value="6">活动</option>
                <option value="7">在线调查</option>
                <option value="8">自定义页面</option>
                <option value="9">友情链接</option>
                <option value="10">活动表单</option>
                <option value="11">文字直播</option>
                <option value="12">投票</option>
                <option value="13">在线测试</option>
                <option value="14">分类信息</option>
                <option value="15">电子报</option>
                <option value="0">站点首页</option>
                <option value="50">外部接口</option>
            </select>
            {s_ChannelType}
        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_Sort">排序号：</label></td>
        <td class="spe_line">
            <input id="f_Sort" name="f_Sort" type="text" value="{Sort}" maxlength="10" class="input_number"/>
        </td>
    </tr>
</table>
<div class="type_0">
    <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class="spe_line" width="20%" height="30" align="right"><label for="f_PublishType">发布方式：</label></td>
            <td class="spe_line">
                <select id="f_PublishType" name="f_PublishType">
                    <option value="1">自动发布新稿</option>
                    <option value="0">仅发布终审文档</option>
                </select>
                {s_PublishType}
            </td>
        </tr>
        <tr>
            <td class="spe_line" height="30" align="right"><label for="f_PublishPath">发布文件夹：</label></td>
            <td class="spe_line">
                <input id="f_PublishPath" name="f_PublishPath" type="text" value="{PublishPath}" class="input_box"/>
                (可以为空)
            </td>
        </tr>
        <tr>
            <td class="spe_line" height="30" align="right"><label for="f_HasFtp">是否有单独FTP设置：</label></td>
            <td class="spe_line">
                <select id="f_HasFtp" name="f_HasFtp">
                    <option value="0">无</option>
                    <option value="1">有</option>
                </select>
                {s_HasFtp}
            </td>
        </tr>
        <tr>
            <td class="spe_line" height="30" align="right"><label for="f_ShowChildList">是否显示子频道数据：</label></td>
            <td class="spe_line">
                <select id="f_ShowChildList" name="f_ShowChildList">
                    <option value="0">否</option>
                    <option value="1">是</option>
                </select> (在显示列表数据时)
                {s_ShowChildList}
            </td>
        </tr>
        <tr>
            <td class="spe_line" height="30" align="right"><label for="f_OpenComment">评论：</label></td>
            <td class="spe_line">
                <select id="f_OpenComment" name="f_OpenComment">
                    <option value="0">不允许</option>
                    <option value="10">允许但需要审核（先审后发）</option>
                    <option value="20">允许但需要审核（先发后审）</option>
                    <option value="30">自由评论</option>
                </select>
                {s_OpenComment}
            </td>
        </tr>
        <!--
        <tr>
            <td class="spe_line" height="30" align="right"><label for="f_Invisible">是否在频道导航树上隐藏：</label></td>
            <td class="spe_line">
                <select id="f_Invisible" name="f_Invisible">
                    <option value="0">不隐藏</option>
                    <option value="1">隐藏</option>
                </select>
                {s_Invisible}
            </td>
        </tr>-->


        <tr>
            <td class="spe_line" height="30" align="right"><label for="f_ChannelBrowserTitle">浏览器标题：</label></td>
            <td class="spe_line">
                <input id="f_ChannelBrowserTitle" name="f_ChannelBrowserTitle" type="text" value="{ChannelBrowserTitle}" class="input_box"
                       style="width:400px;" maxlength="200"/>
            </td>
        </tr>


        <tr>
            <td class="spe_line" height="30" align="right"><label for="f_ChannelBrowserKeywords">浏览器关键字：</label></td>
            <td class="spe_line">
                <input id="f_ChannelBrowserKeywords" name="f_ChannelBrowserKeywords" type="text" value="{ChannelBrowserKeywords}"
                       class="input_box" style="width:400px;" maxlength="200"/>
            </td>
        </tr>

        <tr>
            <td class="spe_line" height="30" align="right"><label for="f_ChannelBrowserDescription">浏览器描述文字：</label></td>
            <td class="spe_line">
                <input id="f_ChannelBrowserDescription" name="f_ChannelBrowserDescription" type="text" value="{ChannelBrowserDescription}"
                       class="input_box" style=" width: 400px;" maxlength="200"/>
            </td>
        </tr>
        <tr>
            <td class="spe_line" height="30" align="right"><label for="f_IsCircle">是否加入循环调用：</label></td>
            <td class="spe_line">
                <select id="f_IsCircle" name="f_IsCircle">
                    <option value="0">否</option>
                    <option value="1" selected="selected">是</option>
                </select> (在调用频道数据时)
                {s_IsCircle}
            </td>
        </tr>
        <tr>
            <td class="spe_line" height="30" align="right"><label for="f_IsShowIndex">是否显示在聚合页中：</label></td>
            <td class="spe_line">
                <select id="f_IsShowIndex" name="f_IsShowIndex">
                    <option value="0">否</option>
                    <option value="1">是</option>
                </select> (在使用频道聚合页中时)
                {s_IsShowIndex}
            </td>
        </tr>
        <!--
        <tr>
            <td class="spe_line" height="30" align="right"><label for="file_title_pic_1">频道图片1：</label></td>
            <td class="spe_line">
                <input id="file_title_pic_1" name="file_title_pic_1" type="file" class="input_box"
                       style="width:400px;background:#ffffff;margin-top:3px;"/> <span id="preview_title_pic1" class="show_title_pic" idvalue="{TitlePic1UploadFileId}" style="cursor:pointer">[预览]</span>

            </td>
        </tr>
        <tr>
            <td class="spe_line" height="30" align="right">频道图片2：</td>
            <td class="spe_line">
                <input id="file_title_pic_2" name="file_title_pic_2" type="file" class="input_box" style="width:400px; background: #ffffff; margin-top: 3px;"/>
                <span id="preview_title_pic2" class="show_title_pic" idvalue="{TitlePic2UploadFileId}" style="cursor:pointer">[预览]</span>
            </td>
        </tr>
        <tr>
            <td class="spe_line" height="30" align="right">频道图片3：</td>
            <td class="spe_line">
                <input id="file_title_pic_3" name="file_title_pic_3" type="file" class="input_box"
                       style="width:400px; background: #ffffff; margin-top: 3px;"/> <span id="preview_title_pic3" class="show_title_pic" idvalue="{TitlePic3UploadFileId}" style="cursor:pointer">[预览]</span>

            </td>
        </tr>
        <tr>
            <td class="spe_line" height="30" align="right"><label for="f_ChannelIntro">频道介绍：</label></td>
            <td class="spe_line">
                <textarea cols="30" rows="30" id="f_ChannelIntro" name="f_ChannelIntro" style="width:70%;height:250px;">{ChannelIntro}</textarea>
            </td>
        </tr>-->
    </table>
</div>
<div class="type_1" style="display: none;">
    <!--<table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">

        <tr>
            <td class="spe_line" width="20%" height="30" align="right"><label for="f_PublishApiUrl">发布API接口地址：</label>
            </td>
            <td class="spe_line">
                <input id="f_PublishApiUrl" name="f_PublishApiUrl" type="text" value="{PublishApiUrl}" class="input_box"
                       style="width: 500px;" maxlength="200"/>
            </td>
        </tr>
        <tr>
            <td class="spe_line" height="30" align="right"><label for="f_PublishApiType">发布API接口类型：</label></td>
            <td class="spe_line">
                <select id="f_PublishApiType" name="f_PublishApiType">
                    <option value="0">JSON</option>
                    <option value="1">XML</option>
                </select> (在使用频道聚合页中时)
                {s_PublishApiType}
            </td>
        </tr>
    </table>-->
</div>
<table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td height="60" align="center">
            <input class="btn" value="确认并关闭" type="button" onclick="submitForm(1)"/> <input class="btn" style="display: {display}" value="确认并继续"
                                                                                            type="button"
                                                                                            onclick="submitForm(0)"/>
            <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
        </td>
    </tr>
</table>
</div>
</form>
</body>
</html>
