<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <title>{cfg_ForumIeTitle}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="keywords" content="{cfg_ForumIeKeywords}"/>
    <meta name="description" content="{cfg_ForumIeDescription}"/>
    <meta name="generator" content="{cfg_MetaGenerator}Sense CMS"/>
    <meta name="author" content="{cfg_MetaAuthor}"/>
    <meta name="copyright" content="{cfg_MetaCopyright}"/>
    <meta name="application-name" content="{cfg_MetaApplicationName}"/>
    <meta name="msapplication-tooltip" content="{cfg_MetaMsApplicationTooltip}"/>
    <link rel="archives" title="archives" href="/archiver/"/>
    <link type="text/css" href="/front_template/common/common.css" rel="stylesheet"/>
    <link type="text/css" href="/front_template/default/skins/gray/common_m.css" rel="stylesheet"/>
    <link type="text/css" href="/front_template/default/skins/gray/width_m.css" rel="stylesheet"/>
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/common.js"></script>
    <script type="text/javascript" src="/system_js/xheditor-1.1.14/xheditor-1.1.14-zh-cn.min.js"></script>

    <script type="text/javascript">

        var editor;
        var batchAttachWatermark = "0";

        var tableType = window.UPLOAD_TABLE_TYPE_FORUM_POST_CONTENT;
        var tableId = '{ForumId}';

        $(function () {


            //重置页面TITLE
            var forumPostTitle = $("#add_to_user_favorite").attr("title");

            $(document).attr("title", forumPostTitle + $(document).attr("title"));


            var forumTopicId = parseInt(Request["forum_topic_id"]);


            var btnGoReply = $("#btnGoReply");
            btnGoReply.click(function () {
                if (forumTopicId == undefined || forumTopicId <= 0) {
                    $("#dialog_box").dialog({width: 300, height: 100});
                    $("#dialog_content").html("帖子ID不能为空");
                }
                else {
                    window.location.href =
                        "/default.php?mod=forum_post&a=create&forum_topic_id={ForumTopicId}";

                }
            });

            $(".img_avatar").each(function () {

                if ($(this).attr("src").length <= 0) {

                    $(this).attr("src", "/front_template/default/skins/gray/no_avatar_small.gif");

                }
            });

            //收藏
            var addToUserFavorite = $("#add_to_user_favorite");
            addToUserFavorite.click(function () {
                var forumTopicId = parseInt("{ForumTopicId}");
                if (forumTopicId == undefined || forumTopicId <= 0) {
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
                            table_id: forumTopicId,
                            user_favorite_title: userFavoriteTitle,
                            user_favorite_url: userFavoriteUrl
                        },
                        dataType: "jsonp",
                        jsonp: "jsonpcallback",
                        success: function (result) {

                            var resultCode = parseInt(result["result"]);
                            if (resultCode > 0) {
                                alert("收藏成功");
                            } else if (resultCode == -1) {
                                alert("收藏失败");
                            } else if (resultCode == -2) {
                                alert("您已经收藏过此主题了");
                            } else if (resultCode == -3) {
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
<div id="foot">
    <div class="btn2 item2">
        <a class="btn2_a" href="/default.php?mod=forum_topic&a=create&forum_id={ForumId}">发表主题</a>
    </div>
    <div class="btn3 item2">
        <a class="btn3_a" href="/default.php?mod=forum_post&a=create&forum_topic_id={ForumTopicId}">回复主题</a>
    </div>
    <div class="spe"></div>
</div>
<div id="forum_nav">
    <div id="dialog_box" title="提示" style="display:none;">
        <div id="dialog_content">
        </div>
    </div>
    <div class="content">
        <div>
            <a class="link" href="/default.php?mod=forum">首页</a>
            --
            <a class="link" href="/default.php?mod=forum_topic&forum_id={ForumId}">
                {ForumName}
            </a>
        </div>
    </div>
</div>
<div id="forum_post" class="div_info">
    <div class="content">

        <icms id="forum_post_list" type="list" where="parent">
            <header>
                <![CDATA[
                <table class="forum_post_content_header" cellpadding="0"
                       cellspacing="0" width="100%">
                    <tr>
                        <td colspan="2">
                            <div class="forum_post_title">{f_ForumPostTitle}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="forum_topic_item td1" width="50px" style="padding-right:10px;">
                            <img class="img_avatar" src="{f_AvatarUploadFilePath}"/>
                        </td>
                        <td class="forum_topic_item" align="left">
                            <table width="100%">
                                <tr>
                                    <td>

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
                        <td class="forum_post_content" colspan="2" align="left"
                            style="vertical-align:top;padding-left: 10px;padding-top: 20px">
                            {f_ForumPostContent}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="right" style="padding:10px;">
                            <a href="/default.php?mod=forum_topic&a=modify&forum_topic_id={f_ForumTopicId}">编辑</a>
                            <span id="add_to_user_favorite" style="cursor:pointer;" title="{f_ForumPostTitle}">收藏</span>
                            <span>举报</span>
                            <a class="fancybox fancybox.iframe"
                               href="/default.php?mod=forum_topic&a=operate&forum_topic_id={f_ForumTopicId}">主题管理</a>
                        </td>
                    </tr>
                    <tr>

                        <td colspan="2" style="padding:10px;">
                            <div style="margin:0;">

                                <div class="bdsharebuttonbox"><a href="#" class="bds_more" data-cmd="more"></a><a
                                        href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a><a href="#"
                                                                                                           class="bds_sqq"
                                                                                                           data-cmd="sqq"
                                                                                                           title="分享到QQ好友"></a><a
                                        href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a><a href="#"
                                                                                                           class="bds_tsina"
                                                                                                           data-cmd="tsina"
                                                                                                           title="分享到新浪微博"></a><a
                                        href="#" class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#"
                                                                                                       class="bds_renren"
                                                                                                       data-cmd="renren"
                                                                                                       title="分享到人人网"></a><a
                                        href="#" class="bds_mshare" data-cmd="mshare" title="分享到一键分享"></a><a href="#"
                                                                                                             class="bds_copy"
                                                                                                             data-cmd="copy"
                                                                                                             title="分享到复制网址"></a><a
                                        href="#" class="bds_print" data-cmd="print" title="分享到打印"></a></div>
                                <script>window._bd_share_config = {"common": {"bdSnsKey": {}, "bdText": "", "bdMini": "1", "bdMiniList": false, "bdPic": "", "bdStyle": "2", "bdSize": "32"}, "share": {}, "image": {"viewList": ["weixin", "sqq", "qzone", "tsina", "tqq", "renren", "mshare", "copy", "print"], "viewText": "分享到：", "viewSize": "32"}, "selectShare": {"bdContainerClass": null, "bdSelectMiniList": ["weixin", "sqq", "qzone", "tsina", "tqq", "renren", "mshare", "copy", "print"]}};
                                    with (document)0[(getElementsByTagName('head')[0] || body).appendChild(createElement('script')).src = 'http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion=' + ~(-new Date() / 36e5)];</script>

                            </div>
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
                        <td class="forum_topic_item" align="left">
                            <table width="100%">
                                <tr>
                                    <td>
                                        <div class="forum_post_title">{f_ForumPostTitle}</div>
                                    </td>
                                    <td class="forum_post_post_time" style="padding-right:10px;" align="right">{c_no}楼
                                    </td>
                                </tr>
                                <tr>
                                    <td class="forum_post_user_name" style="">{f_UserName}</td>
                                    <td class="forum_post_post_time" style="padding-right:10px;" align="right">
                                        {f_PostTime}
                                    </td>
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

        <div class="select_device"><a href="">触屏版</a> <a href="">电脑版</a> <a href="">客户端</a></div>
    </div>
</div>
</body>
</html>
