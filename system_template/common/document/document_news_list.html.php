<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/document_news/document_news.js"></script>

    <script type="text/javascript">
        $(function() {


            $(".link_view").each(function(){
                var documentNewsId = $(this).attr("idvalue");
                var publishDate = $(this).attr("pub_date");
                if(publishDate.length>0){
                    $(this).attr("href","/h/{ChannelId}/"+publishDate+"/"+documentNewsId+".html");
                }
            });

            //批量发布
            var btnBatchPublish = $("#btn_batch_publish");
            btnBatchPublish.click(function () {

                $("#dialog_frame").attr("src","/default.php?secu=manage&mod=common&m=batch_publish&site_id="
                    +parent.G_NowSiteId+"&publish_type=1&do=0");

                $("#dialog_resultbox").dialog({
                    title: "批量发布",
                    width: 600
                });



            });
            var pageIndex = parseInt(Request["p"]);
            //评论管理
            var btn_batch_manage_comment = $("#btn_batch_manage_comment");
            btn_batch_manage_comment.click(function () {
                window.location.href="/default.php?secu=manage&mod=comment&m=list_for_channel&channel_id={ChannelId}&table_type=7";
            });

            //内容图片管理
            var btn_batch_manage_pic = $(".btn_manage_pic");
            btn_batch_manage_pic.css("cursor","pointer");
            btn_batch_manage_pic.click(function () {
                var documentNewsId=$(this).attr("idvalue");
                window.location.href = '/default.php?secu=manage&mod=document_news_pic&m=list&document_news_id='
                    + documentNewsId + '&tab_index='+ parent.G_TabIndex +'&p=' + pageIndex + '&channel_id={ChannelId}';
            });


            /**
             * 移动
             * **/
            $("#btn_move").click(function (event) {
                event.preventDefault();
                var channelId=$(this).attr("idvalue");
                var docIdString = "";
                var w = 500;
                var h = $(window).height() - 100;

                $('input[name=input_select]').each(function (i) {
                    if (this.checked) {
                        docIdString = docIdString + ',' + $(this).val();
                    }
                });

                docIdString=docIdString.substr(1);
                if (docIdString.length <= 0) {
                    alert("请先选择要操作的文档");
                } else {

                    var url='/default.php?secu=manage&mod=document_news&m=move&channel_id='+channelId+'&doc_id_string='+docIdString;
                    $("#dialog_frame").attr("src",url);
                    $("#dialog_resultbox").dialog({
                        hide:true,    //点击关闭是隐藏,如果不加这项,关闭弹窗后再点就会出错.
                        autoOpen:true,
                        height:w,
                        width:h,
                        modal:true, //蒙层（弹出会影响页面大小）
                        title:'移动',
                        overlay: {opacity: 0.5, background: "black" ,overflow:'auto'}
                    });
                }
            });

            /**
             * 复制
             * **/
            $("#btn_copy").click(function (event) {
                event.preventDefault();
                var channelId=$(this).attr("idvalue");
                var docIdString = "";
                var w = 500;
                var h = $(window).height() - 100;

                $('input[name=input_select]').each(function (i) {
                    if (this.checked) {
                        docIdString = docIdString + ',' + $(this).val();
                    }
                });

                docIdString=docIdString.substr(1);
                if (docIdString.length <= 0) {
                    alert("请先选择要操作的文档");
                } else {

                    var url='/default.php?secu=manage&mod=document_news&m=copy&channel_id='+channelId+'&doc_id_string='+docIdString;
                    $("#dialog_frame").attr("src",url);
                    $("#dialog_resultbox").dialog({
                        hide:true,    //点击关闭是隐藏,如果不加这项,关闭弹窗后再点就会出错.
                        autoOpen:true,
                        height:w,
                        width:h,
                        modal:true, //蒙层（弹出会影响页面大小）
                        title:'复制',
                        overlay: {opacity: 0.5, background: "black" ,overflow:'auto'}
                    });
                }
            });
            var btnChangeState = $(".btn_change_state");
            btnChangeState.css("cursor", "pointer");
            btnChangeState.click(function(event) {
                var documentNewsId = $(this).attr('idvalue');
                event.preventDefault();

                //ShowBox('div_state_box_' + documentNewsId);
                var dialogBox = $("#div_state_box_"+ documentNewsId);
                dialogBox.attr("title","改变文档状态");
                dialogBox.dialog({
                    hide:true,
                    autoOpen:true,
                    height: 150,
                    width:450,
                    modal: true
                });
            });
            $(".btn_close_box").click(function(event) {
                var documentNewsId = $(this).attr('idvalue');
                event.preventDefault();
                document.getElementById('div_state_box_' + documentNewsId).style.display = "none";
            });


        });
    </script>

</head>
<body>

<div id="dialog_resultbox" title="" style="display: none;">
    <div id="result_table" style="font-size: 14px;">
        <iframe id="dialog_frame" src=""  style="border: 0; " width="100%" height="460"></iframe>
    </div>
</div>

<div class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn">
                <input id="btn_create" class="btn2" idvalue="{ChannelId}" value="新建文档" title="在本频道新建资讯类的文档" type="button"/>
                <input id="btn_move" class="btn2" value="移动" idvalue="{ChannelId}" title="移动本频道文档至其它频道，请先在下面文档中勾选需要移动的文档" type="button"/>
                <input id="btn_copy" class="btn2" value="复制" idvalue="{ChannelId}" title="复制本频道文档至其它频道，请先在下面文档中勾选需要复制的文档" type="button"/>
                <input id="btn_batch_publish" class="btn2" value="批量发布" title="批量发布" type="button"/>
                <input id="btn_batch_manage_comment" class="btn2" value="评论管理" title="评论管理" type="button"/>
            </td>
            <td style="text-align: right; margin-right: 8px;">
                <div id="search_box">
                    <label for="search_type_box"></label><select name="search_type_box" id="search_type_box" style="display: none">
                        <option value="default">基本搜索</option>
                        <option value="source">来源搜索</option>
                    </select>
                    <label for="search_key"></label><input type="text" id="search_key" name="search_key" class="input_box"/>
                    <input id="btn_search" class="btn2" idvalue="{ChannelId}" value="查 询" type="button"/>
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
            <td style="padding-left:10px;">标题</td>
            <td style="width: 36px;"></td>
            <td id="btn_sort" class="grid_can_sort" title="点击更改排序" style="width: 60px; text-align: center;">
                排序<img id="btn_sort_down" src="/system_template/{template_name}/images/manage/arrow1.gif" alt="down" /><img id="btn_sort_up" style="display:none;" src="/system_template/{template_name}/images/manage/arrow2.gif" alt="up" />
            </td>
            <td style="width: 50px; text-align: center;">推荐</td>
            <td style="width: 50px; text-align: center;">首页</td>
            <td id="btn_sort_by_hit" class="grid_can_sort" title="按点击排序" style="width: 50px; text-align: center;">
                点击<img id="btn_sort_by_hit_down" src="/system_template/{template_name}/images/manage/arrow1.gif" alt="down" /><img id="btn_sort_by_hit_up" style="display:none;" src="/system_template/{template_name}/images/manage/arrow2.gif" alt="up" />
            </td>
            <td style="width: 180px; text-align: center;">创建时间</td>
            <td style="width: 100px; text-align: center;">发稿人</td>
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
                            <td class="spe_line2" style="width:30px;text-align:center;">
                                <label>
                                    <input class="input_select" type="checkbox" name="input_select" value="{f_DocumentNewsId}"/>
                                </label></td>
                            <td class="spe_line2" style="width:40px;text-align:center;"><img class="btn_modify" src="/system_template/{template_name}/images/manage/edit.gif" idvalue="{f_DocumentNewsId}" alt="编辑"/></td>
                            <td class="spe_line2" style="width:40px;text-align:center;"><span class="span_state" id="span_state_{f_DocumentNewsId}">{f_State}</span></td>
                            <td class="spe_line2" style="width:40px;text-align:center;">
                                <img class="btn_change_state" src="/system_template/{template_name}/images/manage/change_state.gif" idvalue="{f_DocumentNewsId}" title="改变文档状态" alt="改变状态"/>
                                <div class="state_box" style="display: none;" id="div_state_box_{f_DocumentNewsId}" title="">

                                    <div style="clear:both;">
                                        <div style="{CanCreate}" class="document_news_set_state" statevalue="0" idvalue="{f_DocumentNewsId}">新稿</div>
                                        <div style="{CanModify}" class="document_news_set_state" statevalue="1" idvalue="{f_DocumentNewsId}">已编</div>
                                        <div style="{CanRework}" class="document_news_set_state" statevalue="2" idvalue="{f_DocumentNewsId}">返工</div>
                                        <div style="{CanAudit1}" class="document_news_set_state" statevalue="11" idvalue="{f_DocumentNewsId}">一审</div>
                                        <div style="{CanAudit2}" class="document_news_set_state" statevalue="12" idvalue="{f_DocumentNewsId}">二审</div>
                                        <div style="{CanAudit3}" class="document_news_set_state" statevalue="13" idvalue="{f_DocumentNewsId}">三审</div>
                                        <div style="{CanAudit4}" class="document_news_set_state" statevalue="14" idvalue="{f_DocumentNewsId}">终审</div>
                                        <div style="{CanRefused}" class="document_news_set_state" statevalue="20" idvalue="{f_DocumentNewsId}">已否</div>
                                        <div class="spe"></div>

                                    </div>
                                    <div style="padding-top: 10px; margin-left: 0px;">
                                        备注：<input style="width: 300px;" type="text" id="manage_remark_{f_DocumentNewsId}" value="" />
                                        <span class="btn_modify_manage_remark" idvalue="{f_DocumentNewsId}">&nbsp;&nbsp;确认</span>
                                    </div>
                                    <div style="padding-top: 10px;">
                                        <label id="remark_{f_DocumentNewsId}" style="color: #009cda">{f_ManageRemark}</label>
                                    </div>
                                </div>
                            </td>
                            <td class="spe_line2" style="width:20px;text-align:center;"><img class="btn_preview" src="/system_template/{template_name}/images/manage/preview.gif" idvalue="{f_DocumentNewsId}" alt="预览" title="预览文档"/></td>
                            <td class="spe_line2" style="width:20px;text-align:center;"><img class="btn_publish" src="/system_template/{template_name}/images/manage/publish.gif" idvalue="{f_DocumentNewsId}" title="发布文档" alt="发布"/></td>
                            <td class="spe_line2" style="padding-left:10px;"><a target="_blank" class="link_view" idvalue="{f_DocumentNewsId}" pub_date="{f_year}{f_month}{f_day}"><span style="color:{f_DocumentNewsTitleColor};font-weight:{f_DocumentNewsTitleBold};">{f_DocumentNewsTitle}</span></a></td>
                            <td class="spe_line2" style="width:36px;text-align:center;"><img class="btn_up" style="cursor:pointer;" src="/system_template/{template_name}/images/manage/arr_up.gif" idvalue="{f_DocumentNewsId}" title="向上移动" alt="向上"/><img style="cursor:pointer;" class="btn_down" src="/system_template/{template_name}/images/manage/arr_down.gif" idvalue="{f_DocumentNewsId}" title="向下移动" alt="向下"/></td>
                            <td class="spe_line2" style="width:60px;text-align:center;" title="文档的排序数字，越大越靠前">{f_Sort}</td>
                            <td class="spe_line2" style="width:50px;text-align:center;" title="文档的推荐级别，用在特定的模板中">{f_RecLevel}</td>
                            <td class="spe_line2" style="width:50px;text-align:center;" title="文档的是否上首页，用在特定的模板中">{f_ShowIndex}</td>
                            <td class="spe_line2" style="width:50px;text-align:center;">{f_Hit}</td>
                            <td class="spe_line2" style="width:180px;text-align:center;" title="文档创建时间">{f_CreateDate}</td>
                            <td class="spe_line2" style="width:100px;text-align:center;" title="发稿人：{f_ManageUserName}">{f_ManageUserName}</td>
                            <td class="spe_line2" style="width:40px;text-align:center;">
                                <img class="btn_manage_pic" src="/system_template/{template_name}/images/manage/pic.gif" idvalue="{f_DocumentNewsId}" alt="图片管理" title="文档中上传的图片管理"/>
                                <a href="/default.php?secu=manage&mod=comment&m=list&table_id={f_DocumentNewsId}&site_id={SiteId}&table_type=7"><img class="btn_manage_comment" src="/system_template/{template_name}/images/manage/comment.gif" idvalue="{f_DocumentNewsId}" alt="评论管理" title="文档的评论管理"/></a>
                            </td>
                        </tr>
                    </table>
                </li>
                ]]>
            </item>
        </icms>
    </ul>
    <div>{pager_button}</div>
</div>
<div id="dialog_box" title="" style="display:none;">
    <div id="dialog_content">

    </div>
</div>
</body>
</html>
