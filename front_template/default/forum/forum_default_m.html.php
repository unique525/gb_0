<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <title>{cfg_ForumIeTitle}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="keywords" content="{cfg_ForumIeKeywords}" />
    <meta name="description" content="{cfg_ForumIeDescription}" />
    <meta name="generator" content="{cfg_MetaGenerator}Sense CMS" />
    <meta name="author" content="{cfg_MetaAuthor}" />
    <meta name="copyright" content="{cfg_MetaCopyright}" />
    <meta name="application-name" content="{cfg_MetaApplicationName}" />
    <meta name="msapplication-tooltip" content="{cfg_MetaMsApplicationTooltip}" />
    <link rel="archives" title="archives" href="/default.php?mod=archiver" />
    <link type="text/css" href="/front_template/common/common.css" rel="stylesheet" />
    <link type="text/css" href="/front_template/default/skins/gray/common_m.css" rel="stylesheet" />
    <link type="text/css" href="/front_template/default/skins/gray/width_m.css" rel="stylesheet" />
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/common.js"></script>
    <script type="text/javascript">

        $(function(){

            /*二级版块*/
            $(".div_forum_two_item").each(function(){

                var childCount = parseInt($(this).attr("tabindex"));
                var childNumber = parseInt($(this).attr("idvalue"));

                //处理情况 一排 2个



                $(this).addClass("forum_two_item2");

                if(childNumber%2 == 1){ //第一个
                    $(this).addClass("forum_two_item2_left");
                }else{
                    $(this).addClass("forum_two_item2_right");
                }






            });



        });

    </script>
</head>
<body>
<div id="forum_nav">
    <div class="content">
        <div class="left"><a href="/default.php?mod=forum">首页</a></div>
        <div class="right"></div>
        <div class="spe"></div>
    </div>
</div>



<div id="forum_top_topic_list">
    <div class="content">
        <icms id="forum_{SiteId}" type="forum_list" where="parent">
            <item>
                <![CDATA[
                <div class="forum_one">
                    <div class="forum_one_item">
                        <div class="forum_one_title"><a style="color:{f_ForumNameFontColor};font-size:{f_ForumNameFontSize};font-weight:{f_ForumNameFontBold};" href="">{f_ForumName}</a></div>
                        <div class="forum_one_moderator"></div>
                        <div class="spe_all"></div>
                    </div>
                    <div class="forum_two">
                        {child}
                        <div class="spe"></div>
                    </div>
                </div>
                ]]>
            </item>
            <child>
                <![CDATA[
                <div class="div_forum_two_item" tabindex="{child_count}" idvalue="{c_child_no}">
                    <table>
                        <tr>
                            <td class="td_info" style="color: red; font: bold">
                                <div class="forum_name"><a style="color:{f_ForumNameFontColor};font-size:{f_ForumNameFontSize};font-weight:{f_ForumNameFontBold};" href="/default.php?mod=forum_topic&forum_id={f_ForumId}">{f_ForumName}</a></div>

                                <div class="forum_stat">新帖 {f_NewCount} 主题 {f_TopicCount} 回复 {f_PostCount}</div>
                            </td>
                        </tr>
                    </table>
                </div>
                ]]>
            </child>
        </icms>
    </div>
</div>
</body>
</html>
