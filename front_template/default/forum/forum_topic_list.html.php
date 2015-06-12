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
    <link rel="archives" title="archives" href="/archiver"/>
    <link type="text/css" href="/front_template/common/common.css" rel="stylesheet"/>
    <link type="text/css" href="/front_template/default/skins/gray/common.css" rel="stylesheet"/>
    <link type="text/css" href="/front_template/default/skins/gray/width_19.css" rel="stylesheet"/>
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/common.js"></script>
    <script type="text/javascript">

        $(function(){

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
    <div class="content">
        <div class="left">
            <a class="link" href="/default.php?mod=forum">首页</a>
            --
            <a class="link" href="/default.php?mod=forum_topic&forum_id={ForumId}">
                {ForumName}
            </a>
        </div>
        <div class="right">
            <div class="btn2">
                <a class="btn2_a" href="/default.php?mod=forum_topic&a=create&forum_id={ForumId}">发表主题</a>
            </div>
        </div>
        <div class="spe"></div>
    </div>
</div>
<div id="forum_topic" class="div_list">
    <div class="content">
        <div class="left">

            <table class="topic_list" cellpadding="0" cellspacing="0" width="100%">
                <icms id="forum_topic_list_normal" type="list"  where="parent">
                    <item>
                        <![CDATA[
                        <tr>
                            <td class="forum_topic_item" width="50px" style="padding-right:10px;"><img class="img_avatar" src="{f_AvatarUploadFilePath}"/></td>
                            <td class="forum_topic_item" align="left">
                                <div class="forum_topic_title">
                                    <a href="/default.php?mod=forum_post&a=list&forum_topic_id={f_ForumTopicId}">
                                        {f_ForumTopicTitle}
                                    </a></div>
                                <div class="forum_topic_user_name">{f_UserName}</div>
                            </td>
                            <td class="forum_topic_item" width="300px" align="right">
                                <div class="forum_topic_user_name">阅读：{f_HitCount}   回复：{f_ReplyCount}</div>
                                <div class="forum_topic_post_time">{f_PostTime}</div>
                            </td>
                        </tr>
                        ]]>
                    </item>
                </icms>
            </table>
            <div class="pager_button">
                <div class="pager_button">{pager_button}</div>
            </div>
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
