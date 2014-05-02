/**
 * DocumentNews
 */
$(function() {
    $(document).tooltip();
    $("#btn_select_all").click(function(event) {
        event.preventDefault();
        var inputSelect = $("[name='input_select']");
        if (inputSelect.prop("checked")) {
            inputSelect.prop("checked", false);//取消全选
        } else {
            inputSelect.prop("checked", true);//全选
        }
    });

    var btnCreate = $("#btn_create");
    btnCreate.css("cursor", "pointer");
    btnCreate.click(function(event) {
        event.preventDefault();
        var pageIndex = parseInt(Request["p"]);
        if (pageIndex <= 0) {
            pageIndex = 1;
        }
        parent.G_TabUrl = '/default.php?secu=manage&mod=document_news&m=create&p=' + pageIndex + '&channel_id=' + parent.G_SelectedChannelId;
        parent.G_TabTitle = parent.G_SelectedChannelName + '-新增文档';
        parent.addTab();
    });

    var btnModify = $(".btn_modify");
    btnModify.css("cursor", "pointer");
    btnModify.click(function(event) {
        var documentNewsId = $(this).attr('idvalue');
        event.preventDefault();
        var pageIndex = parseInt(Request["p"]);
        if (pageIndex <= 0) {
            pageIndex = 1;
        }
        parent.G_TabUrl = '/default.php?secu=manage&mod=document_news&m=modify&document_news_id=' + documentNewsId + '&p=' + pageIndex + '&channel_id=' + parent.G_SelectedChannelId;
        parent.G_TabTitle = parent.G_SelectedChannelName + '-编辑文档';
        parent.addTab();
    });

    //改变状态按钮事件捕获
    $(".btn_change_state").click(function(event) {
        var documentNewsId = $(this).attr('idvalue');
        event.preventDefault();
        //ShowBox('divstate_' + documentNewsId);
    });
    $(".btn_close_box").click(function(event) {
        var documentNewsId = $(this).attr('idvalue');
        event.preventDefault();
        //document.getElementById('divstate_' + docid).style.display = "none";
    });


    //排序变化
    $("#sortgrid").sortable();
    $("#sortgrid").bind("sortstop", function(event, ui) {
        var sortlist = $("#sortgrid").sortable("serialize");
        $.post("/default.php?secu=manage&mod=documentnews&m=async_modifysort&" + sortlist, {
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

    $(".span_state").each(function(){
        $(this).text(FormatState($(this).text()));
    });


    $(".docnewssetstate").click(function() {
        var docid = $(this).attr('idvalue');
        var state = $(this).attr('statevalue');
        DocumentNewsChangeState(docid, state);
    });
});

/**
 * 格式化状态值
 * @return {string}
 */
function FormatState(state){
    switch (state){
        case "0":
            return "新稿";
            break;
        case "1":
            return "已编";
            break;
        case "2":
            return "返工";
            break;
        case "11":
            return "一审";
            break;
        case "12":
            return "二审";
            break;
        case "13":
            return "三审";
            break;
        case "14":
            return "终审";
            break;
        case "20":
            return "<"+"span style='color:#990000'>已否<"+"/span>";
            break;
        default :
            return "未知";
        break;
    }
}

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
    $.post("/default.php?secu=manage&mod=document_news&m=async_modify_state&documentnewsid=" + documentNewsId + "&state=" + state, {
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






