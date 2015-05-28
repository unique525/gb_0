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
