<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    {common_head}
    <script type="text/javascript" src="/system_js/manage/forum/forum.js"></script>
</head>
<body>
<div class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn">
                <input id="btn_create" class="btn2" value="新建版块分区" title="新建版块分区，版块分区下需要建立二级版块才能正常使用" type="button"/>
                <input id="btn_reset_last_info" class="btn2" value="重置版块最后回复数据"  type="button"/>
            </td>
        </tr>
    </table>
    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
        <tr class="grid_title">
            <td style="width: 30px; text-align: center; cursor: pointer;" id="btn_select_all">全</td>
            <td style="width: 40px; text-align: center;">编辑</td>
            <td style="width: 90px; text-align: center;">状态</td>
            <td style="width: 30px;"></td>
            <td>名称</td>
            <td style="width: 160px;">类型</td>
            <td style="width: 70px; text-align: center;">帖子管理</td>
            <td style="width: 45px; text-align: center;"></td>
            <td style="width: 50px; text-align: center;">排序</td>
            <td style="width: 120px;">版主</td>
        </tr>
    </table>
    <ul id="sort_grid">

        <icms id="forum_list" type="list">
            <item>
                <![CDATA[

                <li id="forum_sort_{f_ForumId}" class="forum_rank_one">
                    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item">
                            <!----------选则框--------------->
                            <td class="spe_line2" style="width:30px;text-align:center;">
                                <input class="doc_input"
                                       type="checkbox"
                                       name="doc_input"
                                       value="{f_ForumId}"/>
                            </td>

                            <!----------编辑按钮--------------->
                            <td class="spe_line2" style="width:40px;text-align:center;">
                                <a href="/default.php?secu=manage&mod=forum&m=modify&forum_id={f_ForumId}&site_id={f_SiteId}&forum_rank={f_ForumRank}"><img
                                        class="edit_doc system_image"
                                        src="/system_template/{template_name}/images/manage/edit.gif"
                                        idvalue="{f_ForumId}"
                                        alt="编辑"/>
                                </a>
                            </td>

                            <!----------状态--------------->
                            <td class="spe_line2" style="width:90px;text-align:center;">
                                <span id="span_state_{f_ForumId}" class="span_state">{f_State}</span>
                            </td>

                            <!----------改变状态名称--------------->
                            <td class="spe_line2" style="width:40px;text-align:left;">
                                <img class="img_modify_state system_image"
                                     style="cursor: pointer"
                                     src="/system_template/{template_name}/images/manage/change_state.gif"
                                     idvalue="{f_ForumId}"
                                     title="改变版块状态"
                                     alt="改变状态"/>

                                <div class="forum_state_box"
                                     id="div_state_{f_ForumId}"
                                     idvalue="{f_ForumId}"
                                     title="">
                                    <div style="float:right;">
                                        <img class="span_close_box system_image"
                                             idvalue="{f_ForumId}"
                                             title="关闭"
                                             src="/system_template/{template_name}/images/manage/close3.gif"/>
                                    </div>
                                    <div style="clear:both;">
                                        <div class="forum_state" title="0" idvalue="{f_ForumId}">正常</div>
                                        <div class="forum_state" title="1" idvalue="{f_ForumId}">禁止访问</div>
                                        <div class="forum_state" title="100" idvalue="{f_ForumId}">关闭</div>
                                        <div class="forum_state" style="display:none;" title="3" idvalue="{f_ForumId}">按用户加密</div>
                                        <div class="forum_state" style="display:none;" title="4" idvalue="{f_ForumId}">按身份加密</div>
                                        <div class="forum_state" style="display:none;" title="5" idvalue="{f_ForumId}">
                                            按发帖加密
                                        </div>
                                        <div class="forum_state" style="display:none;" title="6" idvalue="{f_ForumId}">
                                            按积分加密
                                        </div>
                                        <div class="forum_state" style="display:none;" title="7" idvalue="{f_ForumId}">
                                            按金钱加密
                                        </div>
                                        <div class="forum_state" style="display:none;" title="8" idvalue="{f_ForumId}">
                                            按魅力加密
                                        </div>
                                        <div class="forum_state" style="display:none;" title="9" idvalue="{f_ForumId}">
                                            按经验加密
                                        </div>
                                        <div class="forum_state" title="10" idvalue="{f_ForumId}">禁止发帖</div>
                                        <div class="forum_state" title="100" idvalue="{f_ForumId}">删除</div>
                                        <div class="spe"></div>
                                    </div>
                                </div>
                            </td>

                            <!----------名称--------------->
                            <td class="spe_line2">
                                <a target="_blank" href="#">
                                    <span style="color:{f_ForumNameFontColor};font-weight:{f_ForumNameFontBold};font-size:{f_ForumNameFontSize}">{f_ForumName}</span>
                                </a>
                                <a href="/default.php?secu=manage&mod=forum&m=create&parent_id={f_ForumId}&forum_rank=1&site_id={SiteId}">[新增子版块]</a>

                            </td>

                            <!----------类型--------------->
                            <td style="width: 160px;" class="spe_line2">
                                <span style="cursor:pointer"
                                      class="btn_topic_type"
                                      idvalue="{f_ForumId}">帖子类型管理</span>
                            </td>

                            <!----------管理--------------->
                            <td class="spe_line2" style="width:90px;text-align:center;">
                                <span class="btn_topic_list" style="cursor: pointer" idvalue="{f_ForumId}">
                                <a href="/default.php?secu=manage&mod=forum_topic&m=list&forum_id={f_ForumId}&site_id={SiteId}">管理主题</a>
                            </span></td>

                            <!----------移动--------------->
                            <td class="spe_line2" style="width:36px;"><img class="img_up system_image" style="cursor: pointer"
                                     src="/system_template/{template_name}/images/manage/arr_up.gif"
                                     idvalue="{f_ForumId}"
                                     title="向上移动"
                                     alt="向上"/><img class="img_down system_image"
                                     style="cursor: pointer"
                                     src="/system_template/{template_name}/images/manage/arr_down.gif"
                                     idvalue="{f_ForumId}"
                                     title="向下移动"
                                     alt="向下"/></td>

                            <!----------排序--------------->
                            <td class="spe_line2" style="width:50px;text-align:center;" title="文档的排序数字，越大越靠前">{f_Sort}</td>

                            <!----------版主--------------->
                            <td class="spe_line2" style="width:120px;text-align:left;" title="">{f_Moderator}</td>
                        </tr>
                    </table>
                </li>
                {child}
                ]]>
            </item>
            <child>
                <![CDATA[
                <li id="forum_sort_{f_ForumId}" style="margin-left:20px;">
                    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item">
                            <td class="spe_line2" style="width:30px;text-align:center;"><input class="doc_input" type="checkbox" name="docinput" value="{f_forumid}" /></td>
                            <td class="spe_line2" style="width:40px;text-align:center;"><a href="/default.php?secu=manage&mod=forum&m=modify&forum_id={f_ForumId}&site_id={f_SiteId}"><img class="edit_doc system_image" src="/system_template/{template_name}/images/manage/edit.gif" idvalue="{f_ForumId}" alt="编辑" /></a></td>
                            <td class="spe_line2" style="width:90px;text-align:center;"><span id="span_state_{f_ForumId}" class="span_state">{f_State}</span></td>
                            <td class="spe_line2" style="width:40px;text-align:left;">
                                <img class="img_modify_state system_image" style="cursor: pointer" src="/system_template/{template_name}/images/manage/change_state.gif" idvalue="{f_ForumId}" title="改变版块状态" alt="改变状态" />
                                <div class="forum_state_box" id="div_state_{f_ForumId}" idvalue="{f_ForumId}" title="">
                                    <div style="float:right;"><img class="span_close_box system_image" idvalue="{f_ForumId}" title="关闭" src="/system_template/{template_name}/images/manage/close3.gif" /></div>
                                    <div style="clear:both;">
                                        <div class="forum_state" title="0" idvalue="{f_ForumId}">正常</div>
                                        <div class="forum_state" title="1" idvalue="{f_ForumId}">禁止访问</div>
                                        <div class="forum_state" title="2" idvalue="{f_ForumId}">暂时关闭</div>
                                        <div class="forum_state" style="display:none;" title="3" idvalue="{f_ForumId}">按用户加密</div>
                                        <div class="forum_state" style="display:none;" title="4" idvalue="{f_ForumId}">按身份加密</div>
                                        <div class="forum_state" style="display:none;" title="5" idvalue="{f_ForumId}">按发帖加密</div>
                                        <div class="forum_state" style="display:none;" title="6" idvalue="{f_ForumId}">按积分加密</div>
                                        <div class="forum_state" style="display:none;" title="7" idvalue="{f_ForumId}">按金钱加密</div>
                                        <div class="forum_state" style="display:none;" title="8" idvalue="{f_ForumId}">按魅力加密</div>
                                        <div class="forum_state" style="display:none;" title="9" idvalue="{f_ForumId}">按经验加密</div>
                                        <div class="forum_state" title="10" idvalue="{f_ForumId}">禁止发帖</div>
                                        <div class="forum_state" title="100" idvalue="{f_ForumId}">删除</div>
                                        <div class="spe"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="spe_line2"><a target="_blank" href="/default.php?mod=forum_topic&a=list&forum_id={f_ForumId}"><span style="color:{f_ForumNameFontColor};font-weight:{f_ForumNameFontBold};font-size:{f_ForumNameFontSize}">{f_ForumName}</span></a></td>
                            <!----------类型--------------->
                            <td style="width: 160px;" class="spe_line2">
                                <span style="cursor:pointer"
                                      class="btn_topic_type"
                                      idvalue="{f_ForumId}">帖子类型管理</span>
                            </td>
                            <td class="spe_line2" style="width:90px;text-align:center;"><span class="btn_topic_list" style="cursor: pointer" idvalue="{f_ForumId}">
                                <a href="/default.php?secu=manage&mod=forum_topic&m=list&forum_id={f_ForumId}&site_id={SiteId}">管理主题</a>
                            </span></td>
                            <td class="spe_line2" style="width:36px;"><img class="img_up system_image" style="cursor: pointer" src="/system_template/{template_name}/images/manage/arr_up.gif" idvalue="{f_ForumId}" title="向上移动" alt="向上" /><img class="img_down system_image" style="cursor: pointer" src="/system_template/{template_name}/images/manage/arr_down.gif" idvalue="{f_ForumId}" title="向下移动" alt="向下" /></td>
                            <td class="spe_line2" style="width:50px;text-align:center;" title="文档的排序数字，越大越靠前">{f_Sort}</td>
                            <td class="spe_line2" style="width:120px;text-align:left;" title="">{f_Moderator}</td>
                        </tr>
                    </table>
                </li>
                {third}
                ]]>
            </child>
            <third>
                <![CDATA[
                <li id="forum_sort_{f_ForumId}" style="margin-left:40px;">
                    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item">
                            <td class="spe_line2" style="width:30px;text-align:center;"><input class="doc_input" type="checkbox" name="docinput" value="{f_forumid}" /></td>
                            <td class="spe_line2" style="width:40px;text-align:center;"><a href="/default.php?secu=manage&mod=forum&m=modify&forum_id={f_ForumId}&site_id={f_SiteId}"><img class="edit_doc system_image" src="/system_template/{template_name}/images/manage/edit.gif" idvalue="{f_ForumId}" alt="编辑" /></a></td>
                            <td class="spe_line2" style="width:90px;text-align:center;"><span id="span_state_{f_ForumId}" class="span_state">{f_State}</span></td>
                            <td class="spe_line2" style="width:40px;text-align:left;">
                                <img class="img_modify_state system_image" style="cursor: pointer" src="/system_template/{template_name}/images/manage/change_state.gif" idvalue="{f_ForumId}" title="改变版块状态" alt="改变状态" />
                                <div class="forum_state_box" id="div_state_{f_ForumId}" idvalue="{f_ForumId}" title="">
                                    <div style="float:right;"><img class="span_close_box system_image" idvalue="{f_ForumId}" title="关闭" src="/system_template/{template_name}/images/manage/close3.gif" /></div>
                                    <div style="clear:both;">
                                        <div class="forum_state" title="0" idvalue="{f_ForumId}">正常</div>
                                        <div class="forum_state" title="1" idvalue="{f_ForumId}">禁止访问</div>
                                        <div class="forum_state" title="2" idvalue="{f_ForumId}">暂时关闭</div>
                                        <div class="forum_state" style="display:none;" title="3" idvalue="{f_ForumId}">按用户加密</div>
                                        <div class="forum_state" style="display:none;" title="4" idvalue="{f_ForumId}">按身份加密</div>
                                        <div class="forum_state" style="display:none;" title="5" idvalue="{f_ForumId}">按发帖加密</div>
                                        <div class="forum_state" style="display:none;" title="6" idvalue="{f_ForumId}">按积分加密</div>
                                        <div class="forum_state" style="display:none;" title="7" idvalue="{f_ForumId}">按金钱加密</div>
                                        <div class="forum_state" style="display:none;" title="8" idvalue="{f_ForumId}">按魅力加密</div>
                                        <div class="forum_state" style="display:none;" title="9" idvalue="{f_ForumId}">按经验加密</div>
                                        <div class="forum_state" title="10" idvalue="{f_ForumId}">禁止发帖</div>
                                        <div class="forum_state" title="100" idvalue="{f_ForumId}">删除</div>
                                        <div class="spe"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="spe_line2"><a target="_blank" href="/default.php?mod=forum&a=list&forum_id={f_ForumId}"><span style="color:{f_ForumNameFontColor};font-weight:{f_ForumNameFontBold};font-size:{f_ForumNameFontSize}">{f_ForumName}</span></a></td>

                            <td class="spe_line2" style="width:90px;text-align:center;"><span class="btn_topic_list" style="cursor: pointer" idvalue="{f_ForumId}">
                                <a href="/default.php?secu=manage&mod=forum_topic&m=list&forum_id={f_ForumId}&site_id={SiteId}">查看</a>
                            </span></td>
                            <td class="spe_line2" style="width:36px;"><img class="img_up system_image" style="cursor: pointer" src="/system_template/{template_name}/images/manage/arr_up.gif" idvalue="{f_ForumId}" title="向上移动" alt="向上" /><img class="img_down system_image" style="cursor: pointer" src="/system_template/{template_name}/images/manage/arr_down.gif" idvalue="{f_ForumId}" title="向下移动" alt="向下" /></td>
                            <td class="spe_line2" style="width:50px;text-align:center;" title="文档的排序数字，越大越靠前">{f_Sort}</td>
                            <td class="spe_line2" style="width:120px;text-align:left;" title="">{f_Moderator}</td>
                        </tr>
                    </table>
                </li>

                ]]>
            </third>
        </icms>
    </ul>
</div>
</body>
</html>
