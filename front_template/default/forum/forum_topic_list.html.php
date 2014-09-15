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
        <link rel="archives" title="archives" href="/archiver/" />
        <link type="text/css" href="/front_template/common/common.css" rel="stylesheet" />
        <link type="text/css" href="/front_template/default/skins/gray/common.css" rel="stylesheet" />
        <link type="text/css" href="/front_template/default/skins/gray/width_19.css" rel="stylesheet" />
        <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="/system_js/common.js"></script>
    </head>
    <body>
        {forum_top_nav}
        <div id="forum_nav">
            <div class="content">
                <div class="left"><a href="/default.php?mod=forum">首页</a></div>
                <div class="right"><a href="/default.php?mod=forum_topic&a=create&forum_id={ForumId}">发表主题</a></div>
                <div class="spe"></div>
            </div>
        </div>      
        <div id="forum_topic_list">
            <div class="content">
        <table>
        <tr>
            <td class=""><img src="/front_template/default/skins/gray/no_avatar_small.gif" /></td>
            <td class=""></td>
            <td class=""></td>
            <td class=""></td>
        </tr>
    </table>
            </div>
        </div>
    </body>
</html>
