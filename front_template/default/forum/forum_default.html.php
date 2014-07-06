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
        <link rel="archives" title="archives" href="/default.php?mod=archiver" />
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
                <div class="right"></div>
                <div class="spe"></div>
            </div>
        </div>

        <div id="forum_bul">
            <div class="content">
                <div class="left"><a href="/default.php?mod=forum_bul" target="_blank">公告：</a>
                    XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
                </div>
                <div class="right">
                    <div class="stat_box">
                        <span>今日{cfg_ForumNewPostCount}</span>|
                        <span>昨日{cfg_ForumYesterdayPostCount}</span>|
                        <span>帖子{cfg_ForumYesterdayPostCount}</span>|
                        <span>会员{cfg_UserCount}</span>|
                        <span>新会员{cfg_NewRegisterUserName}</span></div>
                </div>
                <div class="spe"></div>
            </div>
        </div>
        {forum_rec_1}
        {forum_list}
    </body>
</html>
