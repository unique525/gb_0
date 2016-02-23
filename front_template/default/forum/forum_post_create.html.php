<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <title>{cfg_ForumIeTitle}</title>
    {forum_common_head}
    <meta name="keywords" content="{cfg_ForumIeKeywords}" />
    <meta name="description" content="{cfg_ForumIeDescription}" />
    <meta name="generator" content="{cfg_MetaGenerator}Sense CMS" />
    <meta name="author" content="{cfg_MetaAuthor}" />
    <meta name="copyright" content="{cfg_MetaCopyright}" />
    <meta name="application-name" content="{cfg_MetaApplicationName}" />
    <meta name="msapplication-tooltip" content="{cfg_MetaMsApplicationTooltip}" />
    <link rel="archives" title="archives" href="/default.php?mod=forum&a=archiver" />
    <link type="text/css" href="/front_template/common/common.css" rel="stylesheet" />
    <link type="text/css" href="/front_template/default/skins/gray/common.css" rel="stylesheet" />
    <link type="text/css" href="/front_template/default/skins/gray/width_19.css" rel="stylesheet" />
    <script type="text/javascript" src="/system_js/xheditor-1.1.14/xheditor-1.1.14-zh-cn.min.js"></script>
    <script type="text/javascript" src="/system_js/color_picker.js"></script>
    <script type="text/javascript" src="/system_js/ajax_file_upload.js"></script>
    <script type="text/javascript" src="/system_js/upload_file.js"></script>
    <script type="text/javascript" src="/system_js/base64.js"></script>

    <script type="text/javascript">
        <!--
        var editor;
        var batchAttachWatermark = "0";

        var tableType = window.UPLOAD_TABLE_TYPE_FORUM_POST_CONTENT;
        var tableId = parseInt('{ForumTopicId}');


        //上传回调函数
        window.AjaxFileUploadCallBack = function(fileElementId,data){
            var uploadFilePath = data.upload_file_path;

            if(editor != undefined && editor != null && uploadFilePath.length>0){

                editor.pasteHTML(""+UploadFileFormatHtml(uploadFilePath));

            }
        }



        $(function(){

            if ($.browser.msie) {
                $('input:checkbox').click(function () {
                    this.blur();
                    this.focus();
                });
            }


            /************** title **************/
            var forumPostTitle = $("#f_ForumPostTitle");

            var forumPostId = Request["forum_post_id"];

            if (forumPostId == undefined || forumPostId <=0){ //新增
                if(forumPostTitle.val() == '{ForumPostTitle}' || forumPostTitle.val() == ''){
                    forumPostTitle.val("标题（可以为空）");
                    forumPostTitle.css("color","#999999");
                }
                forumPostTitle.focus(function(){
                    if(forumPostTitle.val() == '标题（可以为空）'){
                        forumPostTitle.val("");
                    }
                });
            }



            var editorHeight = $(window).height() - 320;
            editorHeight = parseInt(editorHeight);

            var f_ForumPostContent = $('#f_ForumPostContent');

            editor = f_ForumPostContent.xheditor({
                tools:'full',
                height:editorHeight,
                upImgUrl:"",
                upImgExt:"jpg,jpeg,gif,png",
                localUrlTest:/^https?:\/\/[^\/]*?({manage_domain_rex})\//i,
                remoteImgSaveUrl:''
            });

            editor.settings.focus = function(){
                saveForumPostContent();
            };
            editor.settings.blur = function(){
                saveForumPostContent();
            };

            function saveForumPostContent(){
                var forumPostContent = editor.getSource();
                if(forumPostContent.length>0){
                    forumPostContent = UrlEncode(forumPostContent);

                    setcookie("forum_post_content", forumPostContent);
                }

            }

            var btnRestore = $("#btnRestore");
            btnRestore.click(function(){

                if(confirm("从之前录入的内容恢复吗？恢复内容会覆盖当前录入的内容，请谨慎使用！")){

                    //save in cookie
                    var forumPostContent = getcookie("forum_post_content");

                    forumPostContent = UrlDecode(forumPostContent);

                    f_ForumPostContent.val(forumPostContent);

                }


            });

            var cbSaveRemoteImage = $("#cbSaveRemoteImage");
            cbSaveRemoteImage.change(function(){
                if(cbSaveRemoteImage.prop("checked")==true){

                    f_ForumPostContent.xheditor(false);

                    editor = f_ForumPostContent.xheditor({
                        tools:'full',
                        height:editorHeight,
                        upImgUrl:"",
                        upImgExt:"jpg,jpeg,gif,png",
                        localUrlTest:/^https?:\/\/[^\/]*?({manage_domain_rex})\//i,
                        remoteImgSaveUrl:'/default.php?mod=upload_file&a=async_save_remote_image&table_type='+tableType+'&table_id='+tableId
                    });

                }else{

                    f_ForumPostContent.xheditor(false);

                    editor = f_ForumPostContent.xheditor({
                        tools:'full',
                        height:editorHeight,
                        upImgUrl:"",
                        upImgExt:"jpg,jpeg,gif,png",
                        localUrlTest:/^https?:\/\/[^\/]*?({manage_domain_rex})\//i,
                        remoteImgSaveUrl:''
                    });
                }
            });

            var btnUploadToContent = $("#btnUploadToContent");
            btnUploadToContent.click(function(){

                var fileElementId = 'file_upload_to_content';

                var attachWatermark = 0;
                if($("#attachwatermark").attr("checked")==true){
                    attachWatermark = 1;
                }
                var uploadFileId = 0;
                var loadingImageId = null;

                AjaxFileUpload(
                    fileElementId,
                    tableType,
                    tableId,
                    loadingImageId,
                    $(this),
                    attachWatermark,
                    uploadFileId
                );
            });

            var btnConfirm = $("#btnConfirm");
            btnConfirm.click(function(){
                if (forumPostId == undefined || forumPostId <=0){

                    if(forumPostTitle.val() == '标题（可以为空）'){
                        forumPostTitle.val("");
                    }


                    var forumPostContent = $("#f_ForumPostContent");
                    if (forumPostContent.val() == '') {
                        $("#dialog_box").dialog({width: 300, height: 100});
                        $("#dialog_content").html("请输入回复内容");
                    } else {

                        $("#mainForm").attr("action",
                            "/default.php?mod=forum_post&a={action}&forum_topic_id={ForumTopicId}");
                        $('#mainForm').submit();
                    }
                }
                else {

                    $("#mainForm").attr("action",
                        "/default.php?mod=forum_post&a={action}&forum_topic_id={ForumTopicId}");
                    $('#mainForm').submit();
                }
            });


        });

        -->
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
            <div class="left"><a href="/default.php?mod=forum">首页</a> - 发布回复</div>
            <div class="right"></div>
            <div class="spe"></div>
        </div>
    </div>
    <div id="forum_post">
        <div class="content" style="padding:10px;">

                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="75%">
                                <div style="padding: 10px 10px 10px 0;">
                                <label for="f_ForumPostTitle"></label>
                                <input type="text" class="input_box_post_title" id="f_ForumPostTitle" name="f_ForumPostTitle" value="{ForumPostTitle}" style="width:100%;" maxlength="300" />
                                <input type="hidden" id="f_ForumTopicId" name="f_ForumTopicId" value="{ForumTopicId}" />
                                <input type="hidden" id="f_UploadFiles" name="f_UploadFiles" value="{UploadFiles}" />
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><label for="f_ForumPostContent"></label><textarea onpropertychange="forumPostContentChange()" oninput="forumPostContentChange()" class="mceEditor" id="f_ForumPostContent" name="f_ForumPostContent" style=" width: 100%;">{ForumPostContent}</textarea></td>
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

            <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td height="60" align="center">
                        <input id="btnConfirm" class="btn" value="确 认" type="button" />
                        <input id="btnCancel" class="btn" value="取 消" type="button" />
                        <input id="btnRestore" class="btn" value="恢复内容" type="button" />

                    </td>
                </tr>
            </table>
        </div>
    </div>
</form>
</body>
</html>
