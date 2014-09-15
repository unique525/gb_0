<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <title>{cfg_ForumIeTitle}</title>
        {forum_common_head}
        <script type="text/javascript" src="/system_js/xheditor-1.1.14/xheditor-1.1.14-zh-cn.min.js"></script>
        <script type="text/javascript" src="/system_js/color_picker.js"></script>
        <script type="text/javascript" src="/system_js/ajax_file_upload.js"></script>
        <script type="text/javascript" src="/system_js/upload_file.js"></script>
        <script type="text/javascript">
        <!--
        var editor;
        var batchAttachWatermark = "0";

        var tableType = window.UPLOAD_TABLE_TYPE_FORUM_POST_CONTENT;
        var tableId = '{ForumId}';

        $(function(){

            if ($.browser.msie) {
                $('input:checkbox').click(function () {
                    this.blur();
                    this.focus();
                });
            };


            /************** title **************/
            var forumTopicTitle = $("#f_ForumTopicTitle");
            if(forumTopicTitle.val() == '{ForumTopicTitle}' || forumTopicTitle.val() == ''){
                forumTopicTitle.val("标题");
                forumTopicTitle.css("color","#999999");
            }
            forumTopicTitle.focus(function(){
                if(forumTopicTitle.val() == '标题'){
                    forumTopicTitle.val("");
                }
            });

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


            $('#tabs').tabs();

            ///////////////////加粗/////////////////////////
            var cbTitleBold = $("#cbTitleBold");
            cbTitleBold.click(function() {
                ChangeToBold();
            });
            //
            //加载BOLD
            var bold = $("#f_TitleBold").val();
            if(bold == "bold"){
                cbTitleBold.attr('checked',true);
                ChangeToBold();
            }

            ///////////////////
            //加载TITLE COLOR
            var titleColor = $("#f_TitleColor").val();
            if(titleColor.length>=3){
                forumTopicTitle.css("color",titleColor);
            }

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
                var fUploadFile = $("#f_UploadFiles");

                var attachWatermark = 0;
                if($("#attachwatermark").attr("checked")==true){
                    attachWatermark = 1;
                }

                AjaxFileUpload(fileElementId,tableType,tableId,editor,fUploadFile,attachWatermark);
            });

            var btnConfirm = $("#btnConfirm");
            btnConfirm.click(function(){
                var forumTopicTitle = $("#f_ForumTopicTitle");
                if (forumTopicTitle.val() == ''
                    || forumTopicTitle.val() == '{ForumTopicTitle}'
                    || forumTopicTitle.val() == '标题'
                    ) {
                    $("#dialog_box").dialog({width: 300, height: 100});
                    $("#dialog_content").html("请输入标题");
                } else {

                    $("#mainForm").attr("action",
                        "/default.php?mod=forum&m={action}&forum_id={ForumId}&forum_topic_id={ForumTopicId}");
                    $('#mainForm').submit();
                }
            });

        });

        function ChangeToBold(){

            if($("#cbTitleBold").prop('checked') == true){
                $("#f_TitleBold").val("bold");
                $("#f_ForumTopicTitle").css("font-weight","bold");
            }else{
                $("#f_TitleBold").val("normal");
                $("#f_ForumTopicTitle").css("font-weight","normal");
            }

        }

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
                <div class="left"><a href="/default.php?mod=forum">首页</a> - 发布主题</div>
                <div class="right"></div>
                <div class="spe"></div>
            </div>
        </div>
        <div id="forum_topic">
            <div class="content">

                <div id="tabs">
                    <ul style="padding:0">
                        <li class="ui-tabs-nav-custom"><a href="#tabs-1">主题内容</a></li>
                        <li class="ui-tabs-nav-custom"><a href="#tabs-2">高级参数</a></li>
                    </ul>
                    <div id="tabs-1">
                        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="width: 80px;">
                                    <div class="topic_type_select">
                                    <select style="border:none;width:80px;" name="">
                                        <option value="">[原创]</option>
                                    </select>
                                    </div>
                                </td>
                                <td style="padding:4px 0;">

                                    <label for="f_ForumTopicTitle"></label>
                                    <input type="text" class="iColorPicker input_box_topic_title" id="f_ForumTopicTitle" name="f_ForumTopicTitle" value="{ForumTopicTitle}" style="width:70%;font-size:16px;padding:4px;background-color: #ffffff;" maxlength="300" />
                                    <input type="hidden" id="f_TitleColor" name="f_TitleColor" value="{TitleColor}"  />
                                    <input type="hidden" id="f_TitleBold" name="f_TitleBold" value="{TitleBold}"  />
                                    <input type="hidden" id="f_ForumId" name="f_ForumId" value="{ForumId}" />
                                    <input type="hidden" id="f_UploadFiles" name="f_UploadFiles" value="{UploadFiles}" />
                                    <input type="checkbox" id="cbTitleBold" /> <label for="cbTitleBold">加粗</label>
                                </td>
                            </tr>

                        </table>

                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="75%"><label for="f_ForumPostContent"></label><textarea class="mceEditor" id="f_ForumPostContent" name="f_ForumPostContent" style=" width: 100%;">{ForumPostContent}</textarea></td>
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
                    <div id="tabs-2">

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
