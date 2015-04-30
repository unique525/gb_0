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
                ]]>
            </item>
            <child>
                <![CDATA[
                <div class="div_forum_two_item" tabindex="{child_count}" idvalue="{c_child_no}">
                    <table>
                        <tr>
                            <td class="td_pic"><img src="{f_ForumPic1UploadFilePath}" /></td>
                            <td class="td_info">
                                <div class="forum_name"><a style="color:{f_ForumNameFontColor};font-size:{f_ForumNameFontSize};font-weight:{f_ForumNameFontBold};" href="">{f_ForumName}</a></div>
                                <div class="forum_intro" title="{f_ForumInfo}">{f_ForumInfo}</div>
                                <div class="forum_stat">新帖 {f_NewCount} 主题 {f_TopicCount} 回复 {f_PostCount}</div>
                            </td>
                        </tr>
                    </table>
                    <div class="topic_list">
                        <ul class="ul_topic_list_{child_count}">
                            <li class="li_topic_list"><a style="overflow:hidden;" href="">【亚历山大鹦鹉】不小心把妹子的小压力弄飞了咋办啊，8个月大不小心把妹子的小压力弄飞了咋办啊，8个月大</a></li>
                            <li class="li_topic_list"><a href="">【讨论】“铜”对鹦鹉有害吗？？比如鹦鹉啃咬铜脚环</a></li>
                            <li class="li_topic_list"><a href="">【亚历山大鹦鹉】不小心把妹子的小压力弄飞了咋办啊，8个月大不小心把妹子的小压力弄飞了咋办啊，8个月大</a></li>
                            <li class="li_topic_list"><a href="">【亚历山大鹦鹉】不小心把妹子的小压力弄飞了咋办啊，8个月大不小心把妹子的小压力弄飞了咋办啊，8个月大</a></li>
                            <li class="li_topic_list"><a href="">【亚历山大鹦鹉】不小心把妹子的小压力弄飞了咋办啊，8个月大不小心把妹子的小压力弄飞了咋办啊，8个月大</a></li>
                            <li class="li_topic_list"><a href="">【亚历山大鹦鹉】不小心把妹子的小压力弄飞了咋办啊，8个月大不小心把妹子的小压力弄飞了咋办啊，8个月大</a></li>
                            <li class="li_topic_list"><a href="">【亚历山大鹦鹉】不小心把妹子的小压力弄飞了咋办啊，8个月大不小心把妹子的小压力弄飞了咋办啊，8个月大</a></li>
                            <li class="li_topic_list"><a href="">【亚历山大鹦鹉】不小心把妹子的小压力弄飞了咋办啊，8个月大不小心把妹子的小压力弄飞了咋办啊，8个月大</a></li>
                        </ul>
                    </div>
                </div>
                ]]>
            </child>
        </icms>
    </div>    
</div>