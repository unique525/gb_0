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