<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
</head>
<body>
{common_body_deal}
<script type="text/javascript">
    $("document").ready(function(){

        $("#btn_select_all").click(function(event) {
            event.preventDefault();
            var inputSelect = $("[name='doc_input']");
            if (inputSelect.prop("checked")) {
                inputSelect.prop("checked", false);//取消全选
            } else {
                inputSelect.prop("checked", true);//全选
            }
        });

        $("#btn_create").click(function(event) {
            event.preventDefault();
            parent.G_TabUrl = '/default.php?secu=manage&mod=activity&m=create' + '&channel_id=' +  parent.G_SelectedChannelId;
            parent.G_TabTitle = parent.G_SelectedChannelName + '-新建活动';
            parent.addTab();
        });

        var btnEdit = $(".btn_edit");
        btnEdit.css("cursor", "pointer");
        btnEdit.click(function(event) {
            var customFormId = $(this).attr('idvalue');
            event.preventDefault();
            var pageIndex = parseInt(Request["p"]);
            if (pageIndex <= 0) {
                pageIndex = 1;
            }
            parent.G_TabUrl = '/default.php?secu=manage&mod=activity&m=modify&custom_form_id=' + customFormId + '&p=' + pageIndex + '&channel_id=' + parent.G_SelectedChannelId;
            parent.G_TabTitle = parent.G_SelectedChannelName + '-编辑活动';
            parent.addTab();
        });

        var stateTag=$("#state")
        stateTag.html(FormatVoteState(stateTag.attr("title")));
    });

    /**
     * 格式化状态值
     * @param state 状态
     * @return {string}
     */
    function FormatVoteState(state){
        switch (state){
            case "0":
                return "启用";
                break;
            case "100":
                return "<"+"span style='color:#990000'>停用<"+"/span>";
                break;
            default :
                return "未知";
                break;
        }
    }
</script>
<div class="doc_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn">
                <input id="btn_create" class="btn2" value="新建活动" title="在本频道新建一个活动" type="button"/>
            </td>
        </tr>
    </table>
    <table class="doc_grid" cellpadding="0" cellspacing="0">
        <tr class="grid_title">
            <td style="width:30px;text-align:center; cursor: pointer;" id="btn_select_all"><label for="doc_input">全</label></td>
            <td style="width:30px;text-align:center;" >排序</td>
            <td style="width:40px;text-align:center;">编辑</td>
            <td style="width:40px;text-align:center;">状态</td>
            <td>标题</td>
            <td style="width:100px;" title="停留查看开始与结束时间">开始时间</td>
            <td style="width:100px;">发布人</td>
            <td style="width:40px;text-align:center;">类型</td>
            <td style="width:60px;text-align:center;">申请人</td>
            <td style="width:100px;text-align:center;">参加人</td>
            <td style="width:40px;text-align:center;">审核</td>
            <td style="width:40px;text-align:center;">发布</td>
            <td style="width:40px;text-align:center;">删除</td>
            <td style="width:80px;text-align:center;"></td>
        </tr>
        <icms id="activity" type="list" subjectlen="60">
            <item>
                <![CDATA[
                <tr class="grid_item">
                    <td class="spe_line" style="width:30px;text-align:center;"><input id="doc_input" class="doc_input" type="checkbox" name="doc_input" value="{f_ActivityId}" /></td>
                    <td class="spe_line" style="width:30px;text-align:center;">{f_sort}</td>
                    <td class="spe_line" style="width:40px;text-align:center;"><img class="edit_doc" src="/system_template/default/images/manage/edit.gif" alt="编辑" title="{f_ActivityId}" idvalue="{f_ActivityId}" /></td>
                    <td class="spe_line" style="width:40px;text-align:center;" title="点击按状态进行汇总"><span title="{f_State}" id="state" ></span></td>
                    <td class="spe_line" style="text-align:left;overflow: hidden;white-space:nowrap; width:150px; margin-right: 5px;" title="{f_ActivityName}">{f_ActivityName}</td>
                    <td class="spe_line" style="width:80px; overflow: hidden;white-space:nowrap; margin-left: 5px; margin-right: 4px;" title="{f_StartTime} -- {f_EndTime}">{f_StartTime}</td>
                    <td class="spe_line" style="width:100px; overflow: hidden;white-space:nowrap; margin-left: 4px;" title="{f_UserId}__{f_UserName}">{f_UserName}</td>
                    <td class="spe_line" style="width:40px;text-align:center;" title="点击按分类进行汇总"><span class="activityclassid_list" title="{f_activityclassid}">{f_ActivityClass}</span></td>
                    <td class="spe_line" style="width:60px;text-align:center;"><span class="new_activity_user" style=" cursor: pointer" title="{f_ActivityId}">{f_applyusercount} <img style="cursor: pointer" src="{rootpath}/images/add_plus.gif" /></span></td>
                    <td class="spe_line" style="width:100px;text-align:center;"><span class="activity_user_list" style=" cursor: pointer" title="{f_ActivityId}" id="{f_ChannelId}">{f_joinusercount} <img style="cursor: pointer" src="{rootpath}/images/add_manage.gif" /></span></td>
                    <td class="spe_line" style="width:40px;text-align:center;"><img class="img_publish" style="cursor: pointer" src="{rootpath}/images/publish.jpg" title="{f_ActivityId}" alt="发布" /></td>
                    <td class="spe_line" style="width:40px;text-align:center;"><span class="open_activity_id_list" title="{f_ActivityId}"><img style=" cursor: pointer" alt="点击启用或审核该信息" src="/system_template/default/images/manage/start.jpg" /></span></td>
                    <td class="spe_line" style="width:40px;text-align:center;"><span class="stop_activity_id_list" title="{f_ActivityId}"><img style=" cursor: pointer" alt="停用或删除" src="/system_template/default/images/manage/stop.jpg" /></span></td>
                    <td class="spe_line" style="width:40px;text-align:center;"><img class="pic_manage" style="cursor: pointer" src="{rootpath}/images/pic.gif" alt="图片管理" title="{f_ActivityId}" /> <img class="comment_manage" style="cursor: pointer" src="{rootpath}/images/comment.gif" alt="评论管理" title="{f_ActivityId}" /></td>
                </tr>
                ]]>
            </item>
        </icms>
    </table>
</div>
<div id="PagerBtn">
    {PagerButton}
</div>

</body>
</html>