<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <title>{cfg_ForumIeTitle}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="keywords" content="{cfg_ForumIeKeywords}"/>
    <meta name="description" content="{cfg_ForumIeDescription}"/>
    <meta name="generator" content="{cfg_MetaGenerator}Sense CMS"/>
    <meta name="author" content="{cfg_MetaAuthor}"/>
    <meta name="copyright" content="{cfg_MetaCopyright}"/>
    <meta name="application-name" content="{cfg_MetaApplicationName}"/>
    <meta name="msapplication-tooltip" content="{cfg_MetaMsApplicationTooltip}"/>
    <link rel="archives" title="archives" href="/archiver"/>
    <link type="text/css" href="/front_template/common/common.css" rel="stylesheet"/>
    <link type="text/css" href="/front_template/default/skins/gray/common_m.css" rel="stylesheet"/>
    <link type="text/css" href="/front_template/default/skins/gray/width_m.css" rel="stylesheet"/>
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/common.js"></script>
    <script type="text/javascript">

        $(function(){
            $(".span_nickname").each(function(){
                if($(this).html().length<=0){

                    $(this).html("晚报网友");

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
<div id="foot">
    <div class="btn2">
        <a class="btn2_a" href="/default.php?mod=forum_topic&a=create&forum_id={ForumId}">发表主题</a>
    </div>
</div>
<div id="forum_nav">
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
<div id="forum_topic" class="div_list">
    <div class="content">

        <icms id="forum_topic_list_normal" type="list"  where="parent">
            <item>
                <![CDATA[
                <table class="topic_list" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td colspan="2">
                            <div class="forum_topic_title">
                                <a href="/default.php?mod=forum_post&a=list&forum_topic_id={f_ForumTopicId}">
                                    {f_ForumTopicTitle}
                                </a></div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="forum_topic_title">
                                <a href="/default.php?mod=forum_post&a=list&forum_topic_id={f_ForumTopicId}">
                                    {f_ForumTopicTitle}
                                </a></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="forum_topic_item" align="left">
                            <div class="forum_topic_user_name"><span  class="span_nickname">{f_NickName}</span> ({f_PostTime})</div>
                        </td>
                        <td class="forum_topic_item" align="right">
                            <div class="forum_topic_user_name">阅{f_HitCount}/回{f_ReplyCount}</div>
                        </td>
                    </tr>
                </table>
                ]]>
            </item>
        </icms>

        <div class="pager_button">
            {pager_button}
        </div>

        <div class="select_device"><a href="">触屏版</a> <a href="">电脑版</a> <a href="">客户端</a></div>



    </div>

</div>
</body>
</html>
