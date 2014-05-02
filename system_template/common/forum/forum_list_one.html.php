<li id="forumsort_{f_forumid}" class="forumrankone">
    <table class="docgrid" cellpadding="0" cellspacing="0">
        <tr class="griditem">
            <td class="speline2" style="width:30px;text-align:center;"><input class="docinput" type="checkbox" name="docinput" value="{f_forumid}" /></td>
            <td class="speline2" style="width:40px;text-align:center;"><img class="edit_doc systemimage" src="/system_template/{templatename}/images/manage/edit.gif" idvalue="{f_forumid}" alt="编辑" /></td>
            <td class="speline2" style="width:90px;text-align:center;"><span id="spanState_{f_forumid}" class="formatstate">{f_state}</span></td>
            <td class="speline2" style="width:40px;text-align:left;">
                <img class="imgchangestate systemimage" style="cursor: pointer" src="/system_template/{templatename}/images/manage/changestate.gif" idvalue="{f_forumid}" title="改变版块状态" alt="改变状态" />
                <div class="forumstatebox" id="divstate_{f_forumid}" idvalue="{f_forumid}" title="">
                    <div style="float:right;"><img class="span_closebox systemimage" idvalue="{f_forumid}" title="关闭" src="/system_template/{templatename}/images/manage/close3.gif" /></div>
                    <div style="clear:both;">
                        <div class="forumstate" statevalue="0" idvalue="{f_forumid}">正常</div>
                        <div class="forumstate" statevalue="1" idvalue="{f_forumid}">禁止访问</div>
                        <div class="forumstate" statevalue="2" idvalue="{f_forumid}">暂时关闭</div>
                        <div class="forumstate" style="display:none;" statevalue="3" idvalue="{f_forumid}">按用户加密</div>
                        <div class="forumstate" style="display:none;" statevalue="4" idvalue="{f_forumid}">按身份加密</div>
                        <div class="forumstate" style="display:none;" statevalue="5" idvalue="{f_forumid}">按发帖加密</div>
                        <div class="forumstate" style="display:none;" statevalue="6" idvalue="{f_forumid}">按积分加密</div>
                        <div class="forumstate" style="display:none;" statevalue="7" idvalue="{f_forumid}">按金钱加密</div>
                        <div class="forumstate" style="display:none;" statevalue="8" idvalue="{f_forumid}">按魅力加密</div>
                        <div class="forumstate" style="display:none;" statevalue="9" idvalue="{f_forumid}">按经验加密</div>
                        <div class="forumstate" statevalue="10" idvalue="{f_forumid}">禁止发帖</div>
                        <div class="forumstate" statevalue="100" idvalue="{f_forumid}">删除</div>
                        <div class="spe"></div>
                    </div>
                </div>
            </td>
            <td class="speline2"><a target="_blank" href="#"><span style="color:{f_ForumNameFontColor};font-weight:{f_ForumNameFontBold};font-size:{f_ForumNameFontSize}">{f_ForumName}</span></a></td>
            <td class="speline2" style="width:36px;"><img class="imgup systemimage" style="cursor: pointer" src="/system_template/{templatename}/images/manage/arr_up.gif" idvalue="{f_forumid}" title="向上移动" alt="向上" /><img class="imgdown systemimage" style="cursor: pointer" src="/system_template/{templatename}/images/manage/arr_down.gif" idvalue="{f_forumid}" title="向下移动" alt="向下" /></td>
            <td class="speline2" style="width:50px;text-align:center;" title="文档的排序数字，越大越靠前">{f_sort}</td>
            <td class="speline2" style="width:120px;text-align:left;" title="">{f_moderator}</td>
        </tr>
    </table>
</li> 