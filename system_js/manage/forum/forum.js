$(function() {
    //$(document).tooltip();

    $("#btn_select_all").click(function(event) {
        event.preventDefault();
        var docInput = $("[name='doc_input']");
        if (docInput.prop("checked")) {
            docInput.prop("checked", false);//取消全选
        } else {
            docInput.prop("checked", true);//全选
        }
    });

    var btnCreate = $("#btn_create");
    btnCreate.css("cursor", "pointer");
    btnCreate.click(function(event) {
        event.preventDefault();
        parent.G_TabUrl = '/default.php?secu=manage&mod=forum&m=create&rank=0&site_id=' + parent.G_NowSiteId;
        parent.G_TabTitle = parent.G_NowSiteName + '-新增论坛版块分区';
        parent.addTab();
    });






    $(".edit_doc").css("cursor", "pointer");
    $(".edit_doc").click(function(event) {
        var docid = $(this).attr('idvalue');
        event.preventDefault();
        var pageIndex = parseInt(Request["p"]);
        if (pageIndex <= 0) {
            pageIndex = 1;
        }
        parent.G_TabUrl = '/default.php?secu=manage&mod=documentnews&a=modify&documentnewsid=' + docid + '&p=' + pageIndex + '&cid=' + parent.G_SelectedDocumentChannelId;
        parent.G_TabTitle = parent.G_SelectedDocumentChannelName + '-编辑文档';
        parent.addTab();
    });



    //格式化站点状态
    $(".span_state").each(function(){
        $(this).text(FormatForumState($(this).text()));
    });
    //改变状态按钮事件捕获
    $(".img_modify_state").click(function(event) {
        var forumId = $(this).attr('idvalue');
        event.preventDefault();
        ShowBox('div_state_' + forumId);
    });
    $(".forum_state").click(function(event) {
        var forumId = $(this).attr('idvalue');
        var state = $(this).attr('title');

        event.preventDefault();
        $.post("/default.php?secu=manage&mod=forum&a=async_modify_state&forum_id=" + forumId + "&state=" + state, {
            resultbox: $(this).html()
        }, function(result) {
            //操作完成后触发的命令
            var resultInt = parseInt(result);
            if (resultInt > 0) {
                $("#span_state_" + forumId).text(FormatForumState(parseInt(state)));
            }
        });
    });
    $(".span_close_box").click(function(event) {
        var forumId = $(this).attr('idvalue');
        event.preventDefault();
        document.getElementById('div_state_' + forumId).style.display = "none";
    });


    //排序变化
    $("#sortgrid").sortable();
    $("#sortgrid").bind("sortstop", function(event, ui) {
        var siteId = parent.G_NowSiteId;
        var sortlist = $("#sortgrid").sortable("serialize");
        $.post("/default.php?secu=manage&mod=forum&siteid=" + siteId + "&a=async_modify_sort&" + sortlist, {
            resultbox: $(this).html()
        }, function() {
            //操作完成后触发的命令
        });

    });
    $("#sortgrid").disableSelection();

    //选中时的样式变化
    $('.grid_item').click(function() {
        if ($(this).hasClass('grid_item_selected')) {
            $(this).removeClass('grid_item_selected');
        } else {
            $(this).addClass('grid_item_selected');
        }
    });
});

/**
 * 格式化状态值
 * @return {string}
 */
function FormatForumState(state) {
    switch (state) {
        case 0:
            return "正常";
            break;
        case 1:
            return "禁止访问";
            break;
        case 2:
            return "暂时关闭";
            break;
        case 3:
            return "按用户加密";
            break;
        case 4:
            return "按身份加密";
            break;
        case 5:
            return "按发帖加密";
            break;
        case 6:
            return "按积分加密";
            break;
        case 7:
            return "按金钱加密";
            break;
        case 8:
            return "按魅力加密";
            break;
        case 9:
            return "按经验加密";
            break;
        case 10:
            return "禁止发帖";
            break;
        case 100:
            return "已删除";
            break;
        default:
            return "未知状态";
            break;
    }
}