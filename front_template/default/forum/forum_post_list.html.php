<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <title>{cfg_ForumIeTitle}</title>
    <meta name="keywords" content="{cfg_ForumIeKeywords}"/>
    <meta name="description" content="{cfg_ForumIeDescription}"/>
    <meta name="generator" content="{cfg_MetaGenerator}Sense CMS"/>
    <meta name="author" content="{cfg_MetaAuthor}"/>
    <meta name="copyright" content="{cfg_MetaCopyright}"/>
    <meta name="application-name" content="{cfg_MetaApplicationName}"/>
    <meta name="msapplication-tooltip" content="{cfg_MetaMsApplicationTooltip}"/>
    <link rel="archives" title="archives" href="/archiver/"/>
    <link type="text/css" href="/front_template/common/common.css" rel="stylesheet"/>
    <link type="text/css" href="/front_template/default/skins/gray/common.css" rel="stylesheet"/>
    <link type="text/css" href="/front_template/default/skins/gray/width_19.css" rel="stylesheet"/>
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/common.js"></script>
    <script type="text/javascript" src="/system_js/xheditor-1.1.14/xheditor-1.1.14-zh-cn.min.js"></script>
    <style>
        .replyBox { height: 250px;width: 1100px; background:#F9F9F9;border:#E6E6E6 solid 1px;};
    </style>
    <script type="text/javascript">
        <!--
        var editor;
        var batchAttachWatermark = "0";

        var tableType = window.UPLOAD_TABLE_TYPE_FORUM_POST_CONTENT;
        var tableId = '{ForumId}';

        $(function(){

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

            var forumTopicId = parseInt(Request["forum_topic_id"]);
            var btnConfirm = $("#btnConfirm");

            btnConfirm.click(function(){
                if (forumTopicId == undefined || forumTopicId <=0){
                    $("#dialog_box").dialog({width: 300, height: 100});
                    $("#dialog_content").html("帖子ID不能为空");
                }
                else {
                    var forumPostContent = $("#f_ForumPostContent");
                    if (forumPostContent.val() == '') {
                        $("#dialog_box").dialog({width: 300, height: 100});
                        $("#dialog_content").html("回复内容不能为空");
                    } else {

                        $("#mainForm").attr("action",
                            "/default.php?mod=forum_post&a=reply&forum_id={ForumId}&forum_topic_id={ForumTopicId}");
                        $('#mainForm').submit();
                    }
                }
            });

            $(".img_avatar").each(function(){

                if($(this).attr("src").length<=0){

                    $(this).attr("src","/front_template/default/skins/gray/no_avatar_small.gif");

                }
            });

        });


    </script>
</head>
<body>
{forum_top_nav}
<div id="forum_nav">
    <div id="dialog_box" title="提示" style="display:none;">
        <div id="dialog_content">
        </div>
    </div>
    <div class="content">
        <div class="left"><a href="/default.php?mod=forum">首页</a></div>
        <div class="right"><a href="/default.php?mod=forum_topic&a=create&forum_id={ForumId}">发表主题</a></div>
        <div class="spe"></div>
    </div>
</div>
<div id="forum_post" class="div_info">
    <div class="content">
        <div class="left">
            <icms id="forum_post_list" type="list" where="parent">
                <header>
                    <![CDATA[
                    <table class="forum_post_content_header" cellpadding="0"
                           cellspacing="0" width="100%">
                        <tr>
                            <td class="forum_topic_item td1" width="50px" style="padding-right:10px;">
                                <img class="img_avatar" src="{f_AvatarUploadFilePath}"/>
                            </td>
                            <td class="forum_topic_item" align="left">
                                <table width="100%">
                                    <tr>
                                        <td class="forum_topic_title" colspan="2"
                                            style="font-size: 26px;font-weight: bold;fpm">
                                            {f_ForumPostTitle}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="forum_topic_user_name" style="color: #32A5E7;font-weight: bold;">
                                            {f_UserName}
                                        </td>
                                        <td class="forum_topic_post_time" style="color: #A09999" align="right">
                                            {f_PostTime}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="forum_post_content" colspan="2" height="350px" align="left" style="vertical-align:top;padding-left: 10px;padding-top: 20px">
                                {f_ForumPostContent}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="left" style="padding-left:10px;padding-top:10px;">
                                <span>收藏</span> <span>分享</span> <span>举报</span> <span>主题管理</span>
                            </td>
                        </tr>
                    </table>
                    ]]>
                </header>
                <item>
                    <![CDATA[
                    <table class="forum_post_content_item" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td class="forum_topic_item td1" width="50px" style="padding-right:10px;">
                                <img class="img_avatar" src="{f_AvatarUploadFilePath}"/>
                            </td>
                            <td class="forum_topic_item" align="left" style="font-size: 16px;fpm">
                                {f_ForumPostTitle}
                            </td>
                            <td class="forum_topic_item" width="250px">
                                <div class="forum_topic_user_name" style="color:#9999A0;">{f_UserName}</div>
                                <div class="forum_topic_post_time" style="color:#9999A0;">{f_PostTime}</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="forum_post_content" colspan="3" align="left" style="padding-left:10px;padding-top:10px;vertical-align:top;">
                                {f_ForumPostContent}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" align="left" style="padding-left:10px;padding-top:10px;">
                                <span>回复此楼</span> <span>举报</span> <span>帖子管理</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" align="left" style="padding-left:10px;padding-top:10px;">

                                    {child}

                            </td>
                        </tr>

                    </table>
                    ]]>
                </item>
                <child>
                    <![CDATA[
                    <table cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td style="width:150px;">{f_PostTime} {f_UserName}:</td>
                        <td>{f_ForumPostContent}</td>
                    </tr>
                    </table>
                    ]]>
                </child>
            </icms>
            <form id="mainForm" enctype="multipart/form-data" method="post">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td width="100%" style="padding-left: 12px;padding-top: 15px">
                        <textarea id="f_ForumPostContent" class="replyBox"></textarea>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding-top: 5px;"><input id="btnConfirm" style="height: 40px;width: 90px; background-color: #32A5E7;border: 0;color: #ffffff;font-size: 14px;" type="button" value="发表回复"></td>
                </tr>
            </table>
            </form>
        </div>

        <div class="right">
            aaa
        </div>
        <div class="spe"></div>
    </div>
</div>
</body>
</html>
