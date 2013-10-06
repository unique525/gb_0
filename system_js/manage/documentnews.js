/**
 * DOC NEWS LIST
 */

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

    $(".edit_doc").css("cursor", "pointer");
    $(".edit_doc").click(function(event) {
        var docid = $(this).attr('idvalue');
        event.preventDefault();
        var pageIndex = parseInt(Request["p"]);
        if (pageIndex <= 0) {
            pageIndex = 1;
        }
        parent.G_TabUrl = '/default.php?secu=manage&mod=documentnews&m=modify&documentnewsid=' + docid + '&p=' + pageIndex + '&cid=' + parent.G_SelectedDocumentChannelId;
        parent.G_TabTitle = parent.G_SelectedDocumentChannelName + '-编辑文档';
        parent.addTab();
    });

    //改变状态按钮事件捕获
    $(".imgchangestate").click(function(event) {
        var docid = $(this).attr('idvalue');
        event.preventDefault();
        ShowBox('divstate_' + docid);
    });
    $(".span_closebox").click(function(event) {
        var docid = $(this).attr('idvalue');
        event.preventDefault();
        document.getElementById('divstate_' + docid).style.display = "none";
    });


    //排序变化
    $("#sortgrid").sortable();
    $("#sortgrid").bind("sortstop", function(event, ui) {
        var sortlist = $("#sortgrid").sortable("serialize");
        $.post("/default.php?secu=manage&mod=documentnews&m=async_updatesort&" + sortlist, {
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


    $(".docnewssetstate").click(function() {
        var docid = $(this).attr('idvalue');
        var state = $(this).attr('statevalue');
        DocumentNewsChangeState(docid, state);
    });
});

function DocumentNewsChangeState(documentNewsId, state) {
    if (state === 20) {
        $("#dialog_docnewsdelete").dialog({});
    }
    $("#spanstate_" + documentNewsId).html("<img src='../system_images/manage/loading1.gif' />");

    //多文档操作
    var docid = "";
    $('input[name=docinput]').each(function(i) {
        if (this.checked) {
            docid = docid + ',' + $(this).val();
        }
    });
    if (docid.length > 0) {
        $('input[name=docinput]').each(function(i) {
            if (this.checked) {
                _SetDocumentNewsState($(this).val(), state);
            }
        });
    }
    else {
        _SetDocumentNewsState(documentNewsId, state);
    }
}

function _SetDocumentNewsState(documentNewsId, state) {
    $.post("/default.php?secu=manage&mod=documentnews&m=async_changestate&documentnewsid=" + documentNewsId + "&state=" + state, {
        resultbox: $(this).html()
    }, function(xml) {
        if (parseInt(xml) > 0) {
            switch (state)
            {
                case 0:
                    $("#spanstate_" + documentNewsId).text("新稿");
                    break;
                case 1:
                    $("#spanstate_" + documentNewsId).text("已编");
                    break;
                case 2:
                    $("#spanstate_" + documentNewsId).text("返工");
                    break;
                case 11:
                    $("#spanstate_" + documentNewsId).text("一审");
                    break;
                case 12:
                    $("#spanstate_" + documentNewsId).text("二审");
                    break;
                case 13:
                    $("#spanstate_" + documentNewsId).text("三审");
                    break;
                case 14:
                    $("#spanstate_" + documentNewsId).text("终审");
                    break;
                case 20:
                    $('#dialog_docnewsdelete').dialog('close');
                    $("#spanstate_" + documentNewsId).html("<span style='color:#990000'>已否</span>");
                    break;
                default:
                    $("#spanstate_" + documentNewsId).text("错误");
                    break;
            }
        } else if (parseInt(xml) == -2) {
            alert("设置失败，您尚未开通操作此功能的权限，如需开通此权限，请联系管理人员！");
        }
        else {
            alert("设置失败");
        }
    });
    if (state !== 20) {
        document.getElementById('divstate_' + documentNewsId).style.display = "none";
    }
}






