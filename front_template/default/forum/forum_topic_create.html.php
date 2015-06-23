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

        if ($.browser.msie) {
            $('input:checkbox').click(function () {
                this.blur();
                this.focus();
            });
        }


        /************** title **************/
        var forumTopicTitle = $("#f_ForumTopicTitle");

        var forumTopicId = Request["forum_topic_id"];

        if (forumTopicId == undefined || forumTopicId <=0){
            if(forumTopicTitle.val() == '{ForumTopicTitle}' || forumTopicTitle.val() == ''){
                forumTopicTitle.val("标题");
                forumTopicTitle.css("color","#999999");
            }
            forumTopicTitle.focus(function(){
                if(forumTopicTitle.val() == '标题'){
                    forumTopicTitle.val("");
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
            localUrlTest:/^https?:\/\/[^\/]*?(localhost)\//i,
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
                    localUrlTest:/^https?:\/\/[^\/]*?(localhost)\//i,
                    remoteImgSaveUrl:'/default.php?mod=upload_file&a=async_save_remote_image&table_type='+tableType+'&table_id='+tableId
                });

            }else{

                f_ForumPostContent.xheditor(false);

                editor = f_ForumPostContent.xheditor({
                    tools:'full',
                    height:editorHeight,
                    upImgUrl:"",
                    upImgExt:"jpg,jpeg,gif,png",
                    localUrlTest:/^https?:\/\/[^\/]*?(localhost)\//i,
                    remoteImgSaveUrl:''
                });
            }
        });

        var btnUploadToContent = $("#btnUploadToContent");
        btnUploadToContent.click(function(){

            var fileElementId = 'file_upload_to_content';

            var attachWatermark = 0;
            if($("#cbAttachWatermark").is(":checked")){
                attachWatermark = 1;
            }
            var loadingImageId = null;
            var uploadFileId = 0;

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
            if (forumTopicId == undefined || forumTopicId <=0){
                var forumTopicTitle = $("#f_ForumTopicTitle");
                if (forumTopicTitle.val() == ''
                    || forumTopicTitle.val() == '{ForumTopicTitle}'
                    || forumTopicTitle.val() == '标题'
                    ) {
                    $("#dialog_box").dialog({width: 300, height: 100});
                    $("#dialog_content").html("请输入标题");
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

    function ChangeToBold(){

        if($("#cbTitleBold").prop('checked') == true){
            $("#f_TitleBold").val("bold");
            $("#f_ForumTopicTitle").css("font-weight","bold");
        }else{
            $("#f_TitleBold").val("normal");
            $("#f_ForumTopicTitle").css("font-weight","normal");
        }

    }


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
                    <li class="ui-tabs-nav-custom"><a href="#tabs-2">特殊选项</a></li>
                </ul>
                <div id="tabs-1">
                    <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="width: 80px;">
                                <div class="forum_topic_type_select">
                                    <select name="f_ForumTopicTypeId">
                                        <option value="0">主题类型</option>
                                        <icms id="forum_topic_type_list" type="list">
                                            <item>
                                                <![CDATA[
                                                <option value="{f_ForumTopicTypeId}">{f_ForumTopicTypeName}</option>
                                                ]]>
                                            </item>
                                        </icms>
                                    </select>
                                </div>
                            </td>
                            <td style="padding:4px 0;">

                                <label for="f_ForumTopicTitle"></label>
                                <input type="text" class="iColorPicker input_box_topic_title" id="f_ForumTopicTitle" name="f_ForumTopicTitle" style="width:70%;" maxlength="300" />
                                <input type="hidden" id="f_TitleColor" name="f_TitleColor"  />
                                <input type="hidden" id="f_TitleBold" name="f_TitleBold"  />
                                <input type="hidden" id="f_ForumId" name="f_ForumId" value="{ForumId}" />
                                <input type="hidden" id="f_UploadFiles" name="f_UploadFiles" value="" />
                                <input type="checkbox" id="cbTitleBold" /> <label for="cbTitleBold">加粗</label>
                            </td>
                        </tr>

                    </table>

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
                <div id="tabs-2">
                    <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="width: 120px; height: 40px; text-align: right;">
                                <label for="f_ShowSign">显示签名：</label>
                            </td>
                            <td style="padding:4px 0;">
                                <input type="checkbox" id="f_ShowSign" name="f_ShowSign" checked="checked" />
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 40px; text-align: right;">
                                <label for="f_ForumTopicAccess">特殊功能：</label>
                            </td>
                            <td style="padding:4px 0;">
                                <div class="forum_topic_access_select">
                                    <select id="f_ForumTopicAccess" name="f_ForumTopicAccess">
                                        <option value="0">无</option>
                                        <option value="1">禁止回复帖</option>
                                        <option value="10">问题帖 - 悬赏{cfg_ForumMoneyName}</option>
                                        <option value="11">问题帖 - 悬赏{cfg_ForumScoreName}</option>
                                        <option value="12">问题帖 - 悬赏{cfg_ForumPointName}</option>
                                        <option value="13">问题帖 - 悬赏{cfg_ForumCharmName}</option>
                                        <option value="14">问题帖 - 悬赏{cfg_ForumExpName}</option>
                                        <option value="20">出售帖 - {cfg_ForumMoneyName}购买</option>
                                        <option value="21">出售帖 - {cfg_ForumScoreName}购买</option>
                                        <option value="22">出售帖 - {cfg_ForumPointName}购买</option>
                                        <option value="23">出售帖 - {cfg_ForumCharmName}购买</option>
                                        <option value="24">出售帖 - {cfg_ForumExpName}购买</option>
                                        <option value="30">阅读限制帖 - 达到X{cfg_ForumMoneyName}可见</option>
                                        <option value="31">阅读限制帖 - 达到X{cfg_ForumScoreName}可见</option>
                                        <option value="32">阅读限制帖 - 达到X{cfg_ForumPointName}可见</option>
                                        <option value="33">阅读限制帖 - 达到X{cfg_ForumCharmName}可见</option>
                                        <option value="34">阅读限制帖 - 达到X{cfg_ForumExpName}可见</option>
                                        <option value="35">阅读限制帖 - 某人可见</option>
                                        <option value="36">阅读限制帖 - 好友可见</option>
                                        <option value="37">阅读限制帖 - 某身份可见</option>
                                        <option value="38">阅读限制帖 - 达到X{cfg_ForumPostCountName}可见</option>
                                        <option value="39">阅读限制帖 - 达到X注册时间可见(分钟)</option>
                                        <option value="40">阅读限制帖 - 达到X在线时间可见(分钟)</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 40px; text-align: right;">
                                <label for="f_AccessLimitNumber">输入数值：</label>
                            </td>
                            <td style="padding:4px 0;">
                                <input class="input_number" id="f_AccessLimitNumber" name="f_AccessLimitNumber" type="text" value="{AccessLimitNumber}" maxlength="10" />
                            </td>
                        </tr>

                        <tr>
                            <td style="height: 40px; text-align: right;">
                                <label for="a_AccessLimitUserName">输入会员名：</label>
                            </td>
                            <td style="padding:4px 0;">
                                <input class="input_box_normal" id="a_AccessLimitUserName" name="a_AccessLimitUserName" style="width:500px;" type="text" maxlength="2000" /> (多个会员请用 ; 分隔会员名)
                                <input type="hidden" name="f_AccessLimitUserId" value="{AccessLimitUserId}" />
                            </td>
                        </tr>

                        <tr>
                            <td style="height: 40px; text-align: right;">
                                <label for="f_AccessLimitUserGroupId">选择身份：</label>
                            </td>
                            <td style="padding:4px 0;">
                                <div>
                                    <icms id="user_group_list" type="list">
                                        <item>
                                            <![CDATA[
                                            <div style="float:left;width:20%;"><input name="AccessLimitUserGroupId" type="checkbox" value="{f_UserGroupId}" /> {f_UserGroupName}</div>
                                            ]]>
                                        </item>
                                    </icms>
                                    <div class="spe"></div>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td style="height: 40px; text-align: right;">
                                <label for="f_ShowBoughtUser">显示购买者：</label>
                            </td>
                            <td style="padding:4px 0;">
                                <input type="checkbox" id="f_ShowBoughtUser" name="f_ShowBoughtUser" checked="checked" />
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 40px; text-align: right;">
                                <label for="f_IsOneSale">一次性购买：</label>
                            </td>
                            <td style="padding:4px 0;">
                                <input type="checkbox" id="f_IsOneSale" name="f_IsOneSale" checked="checked" />
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
