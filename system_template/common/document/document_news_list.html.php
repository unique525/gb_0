<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/document_news/document_news.js"></script>
</head>
<body>
<div class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn">
                <input id="btn_create" class="btn2" value="新建文档" title="在本频道新建资讯类的文档" type="button"/>
                <input id="btn_move" class="btn2" value="移动" title="移动本频道文档至其它频道，请先在下面文档中勾选需要移动的文档" type="button"/>
                <input id="btn_copy" class="btn2" value="复制" title="复制本频道文档至其它频道，请先在下面文档中勾选需要复制的文档" type="button"/>
            </td>
            <td style="text-align: right; margin-right: 8px;">
                <div id="search_box">
                    <label for="search_type_box"></label><select name="search_type_box" id="search_type_box" style="display: none">
                        <option value="default">基本搜索</option>
                        <option value="source">来源搜索</option>
                    </select>
                    <label for="search_key"></label><input type="text" id="search_key" name="search_key" class="input_box"/>
                    <input id="btn_search" class="btn2" value="查 询" type="button"/>
                    <span id="search_type" style="display: none"></span>
                    <input id="btn_view_self" class="btn2" value="只看本人" title="只查看当前登录的管理员的文档" type="button"/>
                    <input id="btn_view_all" class="btn2" value="查看全部" title="查看全部的文档" type="button"/>
                </div>
            </td>
        </tr>
    </table>
    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
        <tr class="grid_title">
            <td style="width: 30px; text-align: center; cursor: pointer;" id="btn_select_all">全</td>
            <td style="width: 40px; text-align: center;">编辑</td>
            <td style="width: 40px; text-align: center;">状态</td>
            <td style="width: 40px;"></td>
            <td style="width: 20px;"></td>
            <td style="width: 20px;"></td>
            <td>标题</td>
            <td style="width: 36px;"></td>
            <td style="width: 50px; text-align: center;">排序</td>
            <td style="width: 50px; text-align: center;">推荐</td>
            <td style="width: 50px; text-align: center;">点击</td>
            <td style="width: 180px;">创建时间</td>
            <td style="width: 100px;">发稿人</td>
            <td style="width: 40px;"></td>
        </tr>
    </table>
    <ul id="sort_grid">
        <icms id="document_news_list" type="list">
            <item>
                <![CDATA[
                <li id="sort_{f_DocumentNewsId}">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item">
                            <td class="spe_line2" style="width:30px;text-align:center;"><input class="input_select" type="checkbox" name="input_select" value="{f_DocumentNewsId}"/></td>
                            <td class="spe_line2" style="width:40px;text-align:center;"><img class="btn_modify" src="/system_template/{template_name}/images/manage/edit.gif" idvalue="{f_DocumentNewsId}" alt="编辑"/></td>
                            <td class="spe_line2" style="width:40px;text-align:center;"><span class="span_state" id="{f_DocumentNewsId}">{f_State}</span></td>
                            <td class="spe_line2" style="width:40px;text-align:center;"><img class="btn_change_state" src="/system_template/{template_name}/images/manage/change_state.gif" idvalue="{f_DocumentNewsId}" title="改变文档状态" alt="改变状态"/></td>
                            <td class="spe_line2" style="width:20px;text-align:center;"><img class="btn_preview" src="/system_template/{template_name}/images/manage/preview.gif" idvalue="{f_DocumentNewsId}" alt="预览" title="预览文档"/></td>
                            <td class="spe_line2" style="width:20px;text-align:center;"><img class="btn_publish" src="/system_template/{template_name}/images/manage/publish.gif" idvalue="{f_DocumentNewsId}" title="发布文档" alt="发布"/></td>
                            <td class="spe_line2"><a target="_blank" href="{view_url}"><span style="color:{f_DocumentNewsTitleColor};font-weight:{f_DocumentNewsTitleBold};">{f_DocumentNewsTitle}</span></a></td>
                            <td class="spe_line2" style="width:36px;text-align:center;"><img class="btn_up" src="/system_template/{template_name}/images/manage/arr_up.gif" idvalue="{f_DocumentNewsId}" title="向上移动" alt="向上"/><img class="btn_down" src="/system_template/{template_name}/images/manage/arr_down.gif" idvalue="{f_DocumentNewsId}" title="向下移动" alt="向下"/></td>
                            <td class="spe_line2" style="width:50px;text-align:center;" title="文档的排序数字，越大越靠前">{f_Sort}</td>
                            <td class="spe_line2" style="width:50px;text-align:center;" title="文档的推荐级别，用在特定的模板中">{f_RecLevel}</td>
                            <td class="spe_line2" style="width:50px;text-align:center;">{f_Hit}</td>
                            <td class="spe_line2" style="width:180px;text-align:center;" title="文档创建时间">{f_CreateDate}</td>
                            <td class="spe_line2" style="width:100px;text-align:center;" title="发稿人：{f_ManageUserName}">{f_ManageUserName}</td>
                            <td class="spe_line2" style="width:40px;text-align:center;"><img class="btn_manage_pic" src="/system_template/{template_name}/images/manage/pic.gif" idvalue="{f_DocumentNewsId}" alt="图片管理" title="文档中上传的图片管理"/> <img class="btn_manage_comment" src="/system_template/{template_name}/images/manage/comment.gif" idvalue="{f_DocumentNewsId}" alt="评论管理" title="文档的评论管理"/></td>
                        </tr>
                    </table>
                </li>
                ]]>
            </item>
        </icms>
    </ul>
    <div>{pager_button}</div>
</div>
<div id="dialog_publish" title="发布文档" style="display:none;">
    <div id="div_publish">
        <table>
            <tr>
                <td><img src="/system_template/common/images/spinner2.gif" alt="loading..."/></td>
                <td>正在发布，请稍候！</td>
            </tr>
        </table>
    </div>
</div>
<div id="dialog_delete" title="撤掉文档" style="display:none;">
    <div id="div_delete">
        <table>
            <tr>
                <td><img src="/system_template/common/images/spinner2.gif" alt="deleting..."/></td>
                <td>正在撤掉文档，请稍候！</td>
            </tr>
        </table>
    </div>
</div>
<div class="state_box" style="display: none;" id="div_state_box" title="">
    <div style="float:right;"><img class="btn_close_box" alt="关闭" title="关闭" src="/system_template/{template_name}/images/manage/close3.gif"/></div>
    <div style="clear:both;">
        <div style="{CanCreate}" class="docnewssetstate" statevalue="0" idvalue="{f_documentnewsid}">新稿</div>
        <div style="{CanModify}" class="docnewssetstate" statevalue="1" idvalue="{f_documentnewsid}">已编</div>
        <div style="{CanRework}" class="docnewssetstate" statevalue="2" idvalue="{f_documentnewsid}">返工</div>
        <div style="{CanAudit1}" class="docnewssetstate" statevalue="11" idvalue="{f_documentnewsid}">一审</div>
        <div style="{CanAudit2}" class="docnewssetstate" statevalue="12" idvalue="{f_documentnewsid}">二审</div>
        <div style="{CanAudit3}" class="docnewssetstate" statevalue="13" idvalue="{f_documentnewsid}">三审</div>
        <div style="{CanAudit4}" class="docnewssetstate" statevalue="14" idvalue="{f_documentnewsid}">终审</div>
        <div style="{CanRefused}" class="docnewssetstate" statevalue="20" idvalue="{f_documentnewsid}">已否</div>
        <div class="spe"></div>
    </div>
</div>
</body>
</html>
