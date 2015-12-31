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

    <link rel="stylesheet" href="/system_js/fancy_box/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
    <script type="text/javascript" src="/system_js/fancy_box/source/jquery.fancybox.pack.js?v=2.1.5"></script>


    <style>
        .replyBox { height: 250px;width: 1100px; background:#F9F9F9;border:#E6E6E6 solid 1px;};
    </style>
    <script type="text/javascript">

        var editor;
        var batchAttachWatermark = "0";

        var tableType = window.UPLOAD_TABLE_TYPE_FORUM_POST_CONTENT;
        var tableId = '{ForumId}';

        $(function(){

            $('.fancybox').fancybox();

            //重置页面TITLE
            var forumPostTitle = $("#add_to_user_favorite").attr("title");

            $(document).attr("title",forumPostTitle + $(document).attr("title"));


            var f_ForumPostContent = $('#f_ForumPostContent');

            editor = f_ForumPostContent.xheditor({
                tools:'full',
                height:200,
                upImgUrl:"",
                upImgExt:"jpg,jpeg,gif,png",
                localUrlTest:/^https?://[^/]*?(localhost)//i,
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
                        "/default.php?mod=forum_post&a=reply&forum_topic_id={ForumTopicId}");
                    $('#mainForm').submit();
                }
            }
        });

        var btnGoReply = $("#btnGoReply");
        btnGoReply.click(function(){
            if (forumTopicId == undefined || forumTopicId <=0){
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("帖子ID不能为空");
            }
            else {
                window.location.href =
                    "/default.php?mod=forum_post&a=create&forum_topic_id={ForumTopicId}";

            }
        });

        $(".img_avatar").each(function(){

            if($(this).attr("src").length<=0){

                $(this).attr("src","/front_template/default/skins/gray/no_avatar_small.gif");

            }
        });

        //收藏
        var addToUserFavorite = $("#add_to_user_favorite");
        addToUserFavorite.click(function(){
            var forumTopicId = parseInt("{ForumTopicId}");
            if (forumTopicId == undefined || forumTopicId <=0){
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("帖子ID不能为空");
            }
            else {
                var userFavoriteTableType = 4;//论坛主题 4


                var userFavoriteTitle = UrlEncode($(this).attr("title"));
                var userFavoriteUrl = UrlEncode(window.location.href);

                $.ajax({
                    type: "get",
                    url: "/default.php?mod=user_favorite&a=async_add",
                    data: {
                        table_type: userFavoriteTableType,
                        table_id:forumTopicId,
                        user_favorite_title:userFavoriteTitle,
                        user_favorite_url:userFavoriteUrl
                    },
                    dataType: "jsonp",
                    jsonp: "jsonpcallback",
                    success: function(result) {

                        var resultCode = parseInt(result["result"]);
                        if(resultCode > 0){
                            alert("收藏成功");
                        }else if(resultCode == -1){
                            alert("收藏失败");
                        }else if(resultCode == -2){
                            alert("您已经收藏过此主题了");
                        }else if(resultCode == -3){
                            alert("没有登录，请登录后收藏");
                        }

                    }
                });

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
        <div class="left">
            <a class="link" href="/default.php?mod=forum">首页</a>
            --
            <a class="link" href="/default.php?mod=forum_topic&forum_id={ForumId}">
                {ForumName}
            </a>
        </div>
        <div class="right">
            <div class="btn2" style="float:left;">
                <a class="btn2_a" href="/default.php?mod=forum_topic&a=create&forum_id={ForumId}">发表主题</a>
            </div>
            <div class="btn3" style="float:left;margin-left:10px;">
                <a class="btn3_a" href="/default.php?mod=forum_post&a=create&forum_topic_id={ForumTopicId}">回复主题</a>
            </div>
            <div class="spe"></div>
        </div>
        <div class="spe_all"></div>
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
                                        <td>
                                            <div class="forum_post_title">{f_ForumPostTitle}</div>

                                        </td>
                                        <td class="forum_post_post_time" style="padding-right:10px;" align="right">楼主</td>
                                    </tr>
                                    <tr>
                                        <td class="forum_post_user_name" style="">{f_UserName}</td>
                                        <td class="forum_post_post_time" style="" align="right">{f_PostTime}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="forum_post_content" colspan="2" align="left" style="vertical-align:top;padding-left: 10px;padding-top: 20px">
                                {f_ForumPostContent}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="right" style="padding:10px;">
                                <a href="/default.php?mod=forum_topic&a=modify&forum_topic_id={f_ForumTopicId}&forum_id={f_ForumId}">编辑</a>
                                <span id="add_to_user_favorite" style="cursor:pointer;" title="{f_ForumPostTitle}">收藏</span>
                                <span>举报</span>
                                <a class="fancybox fancybox.iframe" href="/default.php?mod=forum_topic&a=operate&forum_topic_id={f_ForumTopicId}">主题管理</a>
                            </td>
                        </tr>
                        <tr>

                            <td colspan="2" style="padding:10px;"><div style="margin:0;">

                                    <div class="bdsharebuttonbox"><a href="#" class="bds_more" data-cmd="more"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a><a href="#" class="bds_sqq" data-cmd="sqq" title="分享到QQ好友"></a><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#" class="bds_renren" data-cmd="renren" title="分享到人人网"></a><a href="#" class="bds_mshare" data-cmd="mshare" title="分享到一键分享"></a><a href="#" class="bds_copy" data-cmd="copy" title="分享到复制网址"></a><a href="#" class="bds_print" data-cmd="print" title="分享到打印"></a></div>
                                    <script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"1","bdMiniList":false,"bdPic":"","bdStyle":"2","bdSize":"32"},"share":{},"image":{"viewList":["weixin","sqq","qzone","tsina","tqq","renren","mshare","copy","print"],"viewText":"分享到：","viewSize":"32"},"selectShare":{"bdContainerClass":null,"bdSelectMiniList":["weixin","sqq","qzone","tsina","tqq","renren","mshare","copy","print"]}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>

                                </div></td>

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
                            <td class="forum_topic_item" align="left">
                                <table width="100%">
                                    <tr>
                                        <td>
                                            <div class="forum_post_title">{f_ForumPostTitle}</div>
                                        </td>
                                        <td class="forum_post_post_time"style="padding-right:10px;" align="right">{c_no}楼</td>
                                    </tr>
                                    <tr>
                                        <td class="forum_post_user_name" style="">{f_UserName}</td>
                                        <td class="forum_post_post_time" style="padding-right:10px;" align="right">{f_PostTime}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="forum_post_content" colspan="2" align="left"
                                style="vertical-align:top;padding:10px;">
                                {f_ForumPostContent}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="right" style="padding:10px;">
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
            <div class="pager_button">
                {pager_button}
            </div>
            <form id="mainForm" enctype="multipart/form-data" method="post">
                <table cellpadding="0" cellspacing="0" width="100%" style="display:{UserIsLogin};">
                    <tr>
                        <td colspan="2">
                            <text_area id="f_ForumPostContent" name="f_ForumPostContent" class="replyBox"></text_area>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="padding-top: 5px;">
                            <input id="btnConfirm" class="btn2" type="button" value="快速回复提交">
                        </td>
                        <td align="right" style="padding-top: 5px;">
                            <input id="btnGoReply" class="btn3" type="button" value="转到高级回复">
                        </td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" width="100%" style="display:{UserUnLogin};">
                    <tr>
                        <td align="center" style="padding:25px;">
                            快速回复：您还没有登录，请先<a href="{UserLoginUrl}">[登录]</a>或<a href="{UserRegisterUrl}">[注册]</a>
                        </td>
                    </tr>
                </table>
            </form>
        </div>

        <div class="right">
            <div id="forum_rec_1_v">
                <div class="content_v">
                    <div class="new_topic">
                        <div class="title"><a href="" target="_blank">最新主题</a></div>
                        <ul>
                            <icms id="site_{SiteId}" type="forum_topic_list" where="new" top="8">
                                <item>
                                    <![CDATA[
                                    <li><span>{c_no}</span><a href="/default.php?mod=forum_post&a=list&forum_topic_id={f_ForumTopicId}" target="_blank">{f_ForumTopicTitle}</a></li>
                                    ]]>
                                </item>
                            </icms>
                        </ul>
                    </div>
                    <div class="hot_topic">
                        <div class="title"><a href="" target="_blank">热门主题</a></div>
                        <ul>
                            <icms id="site_{SiteId}" type="forum_topic_list" where="hot" top="8">
                                <item>
                                    <![CDATA[
                                    <li><span>{c_no}</span><a href="/default.php?mod=forum_post&a=list&forum_topic_id={f_ForumTopicId}" target="_blank">{f_ForumTopicTitle}</a></li>
                                    ]]>
                                </item>
                            </icms>
                        </ul>

                    </div>
                    <div class="best_topic">
                        <div class="title"><a href="" target="_blank">精华主题</a></div>
                        <ul>
                            <icms id="site_{SiteId}" type="forum_topic_list" where="best" top="8">
                                <item>
                                    <![CDATA[
                                    <li><span>{c_no}</span><a href="/default.php?mod=forum_post&a=list&forum_topic_id={f_ForumTopicId}" target="_blank">{f_ForumTopicTitle}</a></li>
                                    ]]>
                                </item>
                            </icms>
                        </ul>

                    </div>
                    <div class="spe"></div>
                </div>
            </div>
        </div>
        <div class="spe"></div>
    </div>
</div>
</body>
</html>