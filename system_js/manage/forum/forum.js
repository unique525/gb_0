$(function() {
    $(document).tooltip();

    $("#selectall").click(function(event) {
        event.preventDefault();
        //alert($("[name='docinput']").prop("checked"));
        if ($("[name='docinput']").prop("checked")) {
            $("[name='docinput']").prop("checked", false);//取消全选
        } else {
            $("[name='docinput']").prop("checked", true);//全选
        }
    });

    var btnCreate = $("#btn_create");
    btnCreate.css("cursor", "pointer");
    btnCreate.click(function(event) {
        event.preventDefault();
        parent.G_TabUrl = '/default.php?secu=manage&mod=forum&m=create&site_id=' + parent.G_NowSiteId;
        parent.G_TabTitle = parent.G_NowSiteName + '-新增论坛';
        parent.addTab();
    });

    $(".span_state").each(function(){
        $(this).text(FormatState($(this).text()));
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

    //改变状态按钮事件捕获
    $(".imgchangestate").click(function(event) {
        var docid = $(this).attr('idvalue');
        event.preventDefault();
        ShowBox('divstate_' + docid);
    });
    $(".forumstate").click(function(event) {
        var forumId = $(this).attr('idvalue');
        var state = $(this).attr('statevalue');

        event.preventDefault();
        $.post("/default.php?secu=manage&mod=forum&a=async_modifystate&forumid=" + forumId + "&state=" + state, {
            resultbox: $(this).html()
        }, function(result) {
            //操作完成后触发的命令
            var resultInt = parseInt(result);
            if (resultInt > 0) {
                FormatState($("#spanState_" + forumId), parseInt(state));
            }
        });
    });
    $(".span_closebox").click(function(event) {
        var docid = $(this).attr('idvalue');
        event.preventDefault();
        document.getElementById('divstate_' + docid).style.display = "none";
    });


    //排序变化
    $("#sortgrid").sortable();
    $("#sortgrid").bind("sortstop", function(event, ui) {
        var siteId = parent.G_NowSiteId;
        var sortlist = $("#sortgrid").sortable("serialize");
        $.post("/default.php?secu=manage&mod=forum&siteid=" + siteId + "&a=async_modifysort&" + sortlist, {
            resultbox: $(this).html()
        }, function() {
            //操作完成后触发的命令
        });

    });
    $("#sortgrid").disableSelection();

    //选中时的样式变化
    $('.griditem').click(function() {
        if ($(this).hasClass('docselected')) {
            $(this).removeClass('docselected');
        } else {
            $(this).addClass('docselected');
        }
    });
});

/**
 * 格式化状态值
 * @return {string}
 */
function FormatState(state) {
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