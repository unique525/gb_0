<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>{cfg_ForumIeTitle}</title>
<meta name="keywords" content="{cfg_ForumIeKeywords}" />
<meta name="description" content="{cfg_ForumIeDescription}" />
<meta name="generator" content="{cfg_MetaGenerator}Sense CMS" />
<meta name="author" content="{cfg_MetaAuthor}" />
<meta name="copyright" content="{cfg_MetaCopyright}" />
<meta name="application-name" content="{cfg_MetaApplicationName}" />
<meta name="msapplication-tooltip" content="{cfg_MetaMsApplicationTooltip}" />

<link type="text/css" href="/front_template/default/skins/gray/jquery_ui/jquery-ui.min.css" rel="stylesheet" />
<script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="/system_js/common.js"></script>
<script type="text/javascript" src="/system_js/jquery.cookie.js"></script>
<script type="text/javascript" src="/system_js/jquery_ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="/system_js/manage/tabs.js"></script>

<link rel="archives" title="archives" href="/default.php?mod=forum&a=archiver" />
<link type="text/css" href="/front_template/common/common.css" rel="stylesheet" />
<link type="text/css" href="/front_template/default/skins/gray/common.css" rel="stylesheet" />
<link type="text/css" href="/front_template/default/skins/gray/width_19.css" rel="stylesheet" />


<script type="text/javascript" src="/system_js/xheditor-1.1.14/xheditor-1.1.14-zh-cn.min.js"></script>
<script type="text/javascript" src="/system_js/color_picker.js"></script>
<script type="text/javascript" src="/system_js/ajax_file_upload.js" charset="utf-8"></script>
<script type="text/javascript" src="/system_js/upload_file.js" charset="utf-8"></script>
<script type="text/javascript">

    var editor;
    var batchAttachWatermark = "0";

    var tableType = window.UPLOAD_TABLE_TYPE_FORUM_POST_CONTENT;
    var tableId = parseInt('{ForumId}');


    //上传回调函数
    window.AjaxFileUploadCallBack = function(fileElementId,data){
        if(fileElementId == "file_upload_to_content"){
            if(editor != undefined && editor != null){
                editor.pasteHTML(""+UploadFileFormatHtml(data.upload_file_path));
            }

            var fUploadFile = $("#f_UploadFiles");

            if(fUploadFile != undefined && fUploadFile != null){
                var uploadFiles = fUploadFile.val();
                uploadFiles = uploadFiles + "," + data.upload_file_id;
                fUploadFile.val(uploadFiles);
            }
        }
    };

    $(function(){

        var editorHeight = $(window).height() - 320;
        editorHeight = parseInt(editorHeight);

        var f_ForumPostContent = $('#f_ForumPostContent');

        editor = f_ForumPostContent.xheditor({
            tools:'full',
            height:editorHeight,
            upImgUrl:"",
            upImgExt:"jpg,jpeg,gif,png",
            localUrlTest:/^https?:\/\/[^\/]*?(localhost)\//i,
            remoteImgSaveUrl:''
        });

        var btnConfirm = $("#btnConfirm");
        btnConfirm.click(function(){
            alert("OK");
            if (forumTopicId == undefined || forumTopicId <=0){
                var forumPostContent = $("#f_ForumPostContent");
                if (forumPostContent.val() == ''
                    || forumTopicTitle.val() == '{forumPostContent}'
                    || forumTopicTitle.val() == '内容'
                    ) {
                    $("#dialog_box").dialog({width: 300, height: 100});
                    $("#dialog_content").html("请输入内容");
                } else {

                    $("#mainForm").attr("action",
                        "/default.php?mod=forum_topic&a={action}&forum_id={ForumId}&forum_topic_id={ForumTopicId}");
                    $('#mainForm').submit();
                }
            }
            else {

                $("#mainForm").attr("action",
                    "/default.php?mod=forum_topic&a={action}&forum_id={ForumId}&forum_topic_id={ForumTopicId}");
                $('#mainForm').submit();
            }
        });

    });


</script>
</head>
<body>
<form id="mainForm" enctype="multipart/form-data" method="post">
    <div id="dialog_box" title="提示" style="display:none;">
        <div id="dialog_content">
        </div>
    </div>
    {forum_top_nav}
    <div id="forum_nav">
        <div class="content">
            <div class="left"><a href="/default.php?mod=forum">首页</a> - 修改回帖</div>
            <div class="right"></div>
            <div class="spe"></div>
        </div>
    </div>
    <div id="forum_topic">
        <div class="content">

            <div id="tabs">

                <div id="tabs-1">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="75%"><label for="f_ForumPostContent"></label><text_area class="mceEditor" id="f_ForumPostContent" name="f_ForumPostContent" style=" width: 100%;">{ForumPostContent}</text_area></td>
                            <td style="vertical-align:top;">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-left: 3px; padding: 2px;">
                                    <tr>
                                        <td style="height:35px;">文件上传：</td>
                                        <td align="left">
                                            <input id="file_upload_to_content" name="file_upload_to_content" type="file" class="input_box" size="7" style="width:60%; background: #ffffff;" /> <img id="loading" src="/system_template/common/images/loading1.gif" style="display:none;" /><input id="btnUploadToContent" type="button" value="上传" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="height:35px;"><label for="cbAttachWatermark">附加水印：</label></td>
                                        <td align="left">
                                            <input type="checkbox" id="cbAttachWatermark" name="cbAttachWatermark" /> (只支持jpg或jpeg图片)
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="height:35px;"><label for="cbSaveRemoteImage">远程抓图：</label></td>
                                        <td align="left">
                                            <input type="checkbox" id="cbSaveRemoteImage" name="cbSaveRemoteImage" /> (只支持jpg、jpeg、gif、png图片)
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td height="60" align="center">
                        <input id="btnConfirm" class="btn" value="确 认" type="button" />
                        <input id="btnCancel" class="btn" value="取 消" type="button" />
                    </td>
                </tr>
            </table>
        </div>
    </div>
</form>
</body>
</html>
