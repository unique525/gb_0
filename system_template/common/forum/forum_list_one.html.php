<li id="forum_sort_{f_ForumId}" class="forum_rank_one">
    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
        <tr class="grid_item">
            <td class="spe_line2" style="width:30px;text-align:center;"><input class="doc_input" type="checkbox" name="doc_input" value="{f_ForumId}" /></td>
            <td class="spe_line2" style="width:40px;text-align:center;"><img class="edit_doc system_image" src="/system_template/{template_name}/images/manage/edit.gif" idvalue="{f_ForumId}" alt="编辑" /></td>
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
            <td class="spe_line2">
                <a target="_blank" href="#"><span style="color:{f_ForumNameFontColor};font-weight:{f_ForumNameFontBold};font-size:{f_ForumNameFontSize}">{f_ForumName}</span></a>
                <a href="/default.php?secu=manage&mod=forum&m=create&parent_id={f_ForumId}&forum_rank=1&site_id={SiteId}">[新增子版块]</a></td>
            <td class="spe_line2" style="width:36px;">
                <img class="img_up system_image" style="cursor: pointer" src="/system_template/{template_name}/images/manage/arr_up.gif" idvalue="{f_ForumId}" title="向上移动" alt="向上" /><img class="img_down system_image" style="cursor: pointer" src="/system_template/{template_name}/images/manage/arr_down.gif" idvalue="{f_ForumId}" title="向下移动" alt="向下" />
            </td>
            <td class="spe_line2" style="width:50px;text-align:center;" title="文档的排序数字，越大越靠前">{f_Sort}</td>
            <td class="spe_line2" style="width:120px;text-align:left;" title="">{f_Moderator}</td>
        </tr>
    </table>
</li> 