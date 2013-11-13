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
        <link type="text/css" href="/front_template/default/skins/gray/pager1.css" rel="stylesheet" />
        <link type="text/css" href="/front_template/default/skins/gray/common.css" rel="stylesheet" />
        <link type="text/css" href="/front_template/default/skins/gray/width_19.css" rel="stylesheet" />
        <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="/system_js/common.js"></script>
    </head>
    <body>
        {forum_topnav}
        <div id="forum_nav">
            <div class="content">
                <div class="left"><a href="default.php?mod=forum">首页</a></div>
                <div class="right"></div>
                <div class="spe"></div>
            </div>
        </div>
        
        <div id="forum_topic_topbtn">
            <div class="content">
                <div>
                <div class="postnewtopic">
                    <span class="btnpostnewtopic"><a href="">发表新主题</a></span>
                </div>
                <div class="forumtopictypelist"><span>全部分类</span></div>
                <div class="spe"></div>
                </div>
                <div class="pagerbutton">
                    {pagerbutton}
                    </div>
            </div>
        </div>
        
        <div id="forum_topic_list">
            <div class="content">

                <cscms id="forumTopicList" type="list">
                    <item>
                        <![CDATA[
                        <table>
                            <tr>
                                <td class="td_useravatar"><img src="/front_template/default/skins/gray/noavatar_small.gif" /></td>
                                <td class="td_forumtopictitle">
                                    <div class="forumtopictitle">{f_ForumTopicTitle}</div>
                                    <div class="user_time_info">
                                        <span class="username"><a href="">{f_UserName}</a> 于 {f_PostTime} 发表&nbsp;&nbsp;&nbsp;&nbsp;最后回复人 <a href="">{f_LastPostUserName}</a> ({f_LastPostTime})</span>
                                    
                                    </div>
                                </td>
                                <td class="td_forumtopiccount">
                                    <div class="hitcount"><span>点击</span> {f_HitCount}</div>
                                    <div class="replycount"><span>回复</span> {f_ReplyCount}</div>
                                </td>
                                <td class=""></td>
                            </tr>
                        </table>
                        ]]>
                    </item>
                </cscms>
            </div>
        </div>
    </body>
</html>
