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
        <script type="text/javascript">

            $(function(){

                /*二级版块*/
                $(".div_forum_two_item").each(function(){

                    var childCount = parseInt($(this).attr("tabindex"));
                    var childNumber = parseInt($(this).attr("idvalue"));

                    //处理情况 一排 2,3,4,5个

                    if(childCount%5 == 0){ //5的倍数

                        $(this).addClass("forum_two_item5");

                        if(childNumber%5 == 0){ //最后一个
                            $(this).addClass("forum_two_item5_right");
                        }else{
                            $(this).addClass("forum_two_item5_left");
                        }

                    }else if(childCount%4 == 0){ //4的倍数

                        $(this).addClass("forum_two_item4");

                        if(childNumber%4 == 0){ //最后一个
                            $(this).addClass("forum_two_item4_right");
                        }else{
                            $(this).addClass("forum_two_item4_left");
                        }

                    }else if(childCount%3 == 0){ //3的倍数

                        $(this).addClass("forum_two_item3");

                        if(childNumber%3 == 0){ //最后一个
                            $(this).addClass("forum_two_item3_right");
                        }else{
                            $(this).addClass("forum_two_item3_left");
                        }

                    }else if(childCount%2 == 0){ //2的倍数

                        $(this).addClass("forum_two_item2");

                        if(childNumber%2 == 1){ //第一个
                            $(this).addClass("forum_two_item2_left");
                        }else{
                            $(this).addClass("forum_two_item2_right");
                        }

                    }




                });



            });

        </script>
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
        <div id="forum_rec_1">
            <div class="content">
                <div class="new_pic_topic"><a href=""><img src="" width="400" height="230" alt="" /></a></div>
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
        <div id="forum_list">
            <div class="content">
                <icms id="forum_{SiteId}" type="forum_list" where="parent">
                    <item>
                        <![CDATA[
                        <div class="forum_one">
                            <div class="forum_one_item">
                                <div class="forum_one_title"><a style="color:{f_ForumNameFontColor};font-size:{f_ForumNameFontSize};font-weight:{f_ForumNameFontBold};" href="">{f_ForumName}</a></div>
                                <div class="forum_one_moderator">版主：XXX</div>
                                <div class="spe_all"></div>
                            </div>
                            <div class="forum_two">
                                {child}
                                <div class="spe"></div>
                            </div>
                        </div>
                        <script type="text/javascript">
                            $(function(){
                                var showLastPostInfo = parseInt("{f_ShowLastPostInfo}");
                                if(showLastPostInfo==0){
                                    $(".show_topic_list_"+{f_ForumId}).css("display","none");
                                }
                            });
                        </script>
                        ]]>
                    </item>
                    <child>
                        <![CDATA[
                        <div class="div_forum_two_item" tabindex="{child_count}" idvalue="{c_child_no}">
                            <table>
                                <tr>
                                    <td class="td_pic"><a href="/default.php?mod=forum_topic&forum_id={f_ForumId}"><img src="{f_ForumPic1UploadFilePath}" /></a></td>
                                    <td class="td_info">
                                        <div class="forum_name"><a style="color:{f_ForumNameFontColor};font-size:{f_ForumNameFontSize};font-weight:{f_ForumNameFontBold};" href="/default.php?mod=forum_topic&forum_id={f_ForumId}">{f_ForumName}</a></div>
                                        <div class="forum_intro" title="{f_ForumInfo}">{f_ForumInfo}</div>
                                        <div class="forum_stat">新帖 {f_NewCount} 主题 {f_TopicCount} 回复 {f_PostCount}</div>
                                    </td>
                                </tr>
                            </table>
                            <div class="topic_list show_topic_list_{f_ParentId}">
                                <ul class="ul_topic_list_{child_count}">
                                    {child_info_string}
                                </ul>
                            </div>
                        </div>
                        ]]>
                    </child>
                    <child_info_string>
                        <![CDATA[
                        <li class="li_topic_list"><a style="overflow:hidden;" href="/default.php?mod=forum_post&a=list&forum_topic_id={f_id}">{f_title}</a></li>
                        ]]>
                    </child_info_string>
                </icms>
            </div>
        </div>
    </body>
</html>
