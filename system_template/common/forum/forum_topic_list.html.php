<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/forum/forum.js"></script>

    <script type="text/javascript">
        $("document").ready(function () {
            var siteId = Request["site_id"];
            var forumId = Request["forum_id"];

            var btnEdit = $(".btn_edit");
            btnEdit.css("cursor", "pointer");
            btnEdit.click(function (event) {
                var forumTopicId =  $(this).attr('idvalue');
                event.preventDefault();
                window.location.href = '/default.php?secu=manage&mod=forum_topic&m=modify&forum_id='+forumId+'&site_id='+siteId+'&forum_topic_id='+forumTopicId;
                //parent.G_TabTitle = '-编辑帖子';
                //parent.addTab();
            });

            //全选
            $("#btn_select_all").click(function(event) {
                event.preventDefault();
                var inputSelect = $("[name='input_select']");
                if (inputSelect.prop("checked")) {
                    inputSelect.prop("checked", false);//取消全选
                } else {
                    inputSelect.prop("checked", true);//全选
                }
            });

            //移动
            $("#btn_move").click(function (event) {
                event.preventDefault();
                var forumId=$(this).attr("idvalue");
                var forumTopicIds = "";

                $('input[name=input_select]').each(function (i) {
                    if (this.checked) {
                        forumTopicIds = forumTopicIds + '|!|' + $(this).val();
                    }
                });

                forumTopicIds=forumTopicIds.substr(3);
                if (forumTopicIds.length <= 0) {
                    alert("请先选择要操作的帖子");
                } else {
                    var w = 500;
                    var h = $(window).height() - 100;
                    var url='/default.php?secu=manage&mod=forum&m=get_move_window&site_id='+siteId+'&forum_id='+forumId+'&forum_ids='+forumTopicIds;
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

        });


    </script>
</head>
<body>
{common_body_deal}
<div id="dialog_resultbox" title="" style="display: none;">
    <div id="result_table" style="font-size: 14px;">
        <iframe id="dialog_frame" src="" style="width: 400px;height: 500px;border:none"></iframe>
    </div>
</div>
<div class="div_list">
    <table cellpadding="0" cellspacing="0" width="100%"">
        <tbody><tr>
            <td id="td_main_btn">
                <input style="cursor: pointer;" id="btn_move" class="btn2" value="移动" idvalue="{f_ForumId}" title="移动帖子到另一个板块" type="button">
            </td>
        </tr>
        </tbody></table>
    <table class="grid" width="100%" align="center" cellpadding="0" cellspacing="0">
        <tr class="grid_title">
            <td style="width: 30px; text-align: center; cursor: pointer;" id="btn_select_all">全</td>
            <td style="width:60px;text-align:center;">ID</td>
            <td style="width:60px;text-align:center;">编辑</td>
            <td style="width:500px;text-align:center;">标题</td>
            <td style="width:200px;text-align:center;">创建时间</td>
            <td style="width:200px;text-align:center;">最后编辑时间</td>
            <td style="width:200px;text-align:center;">发帖人</td>
            <td style="width:200px;text-align:center;">回复列表</td>
        </tr>
        <icms id="forum_topic_list" type="list"><item><![CDATA[
                <tr class="grid_item">
                    <td class="spe_line2" style="width:30px;text-align:center;">
                        <label>
                            <input class="input_select" type="checkbox" name="input_select" value="{f_ForumTopicId}"/>
                        </label>
                    </td>
                    <td class="spe_line2" style="text-align:center;" >{f_ForumTopicId}</td>
                    <td class="spe_line2" style="text-align:center;"><img style="cursor: pointer"
                                                                          class="btn_edit"
                                                                          src="/system_template/default/images/manage/edit.gif"
                                                                          alt="编辑" title="{f_ForumTopicId}"
                                                                          idvalue="{f_ForumTopicId}"/></td>
                    <td class="spe_line2" style="text-align:center;">{f_ForumTopicTitle}</td>
                    <td class="spe_line2" style="text-align:center;">{f_PostTime}</td>
                    <td class="spe_line2" style="text-align:center;">{f_LastPostTime}</td>
                    <td class="spe_line2" style="text-align:center;">{f_UserName}</td>
                    <td class="spe_line2" style="text-align:center;"><span style="cursor: pointer" class="btn_reply_list" idvalue="{f_ForumTopicId}">点击查看</span></td>
                </tr>
                ]]></item></icms>
    </table>
</div>
<div id="pager_btn" style="margin:8px;">
    {PagerButton}
</div>
</body>
</html>
