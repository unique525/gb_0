/**
 * Vote
 */
$(function() {
    //$(document).tooltip()会使title属性失效;
    var btnCreate = $("#btn_create");
    btnCreate.css("cursor", "pointer");
    btnCreate.click(function(event) {
        event.preventDefault();
        var voteId = Request["vote_id"];
        var pageIndex = Request["p"]==null?1:Request["p"];
        pageIndex =  parseInt(pageIndex);
        if (pageIndex <= 0) {
            pageIndex = 1;
        }
        var url='/default.php?secu=manage&mod=vote_item&m=create&vote_id='+voteId+'&p=' + pageIndex;
        $("#dialogiframe").attr("src",url);
        $("#dialog_resultbox").dialog({
            hide:true,    //点击关闭是隐藏,如果不加这项,关闭弹窗后再点就会出错.
            autoOpen:true,
            height:560,
            width:800,
            modal:true, //蒙层（弹出会影响页面大小）
            title:'题目新增',
            overlay: {opacity: 0.5, background: "black" ,overflow:'auto'}
        });
    });

    var btnModify = $(".btn_modify");
    btnModify.css("cursor", "pointer");
    btnModify.click(function(event) {
        event.preventDefault();
        var voteItemId = $(this).attr('idvalue');
        var pageIndex = Request["p"]==null?1:Request["p"];
        pageIndex =  parseInt(pageIndex);
        if (pageIndex <= 0) {
            pageIndex = 1;
        }
        var url='/default.php?secu=manage&mod=vote_item&m=modify&vote_item_id=' + voteItemId + '&p=' + pageIndex;
        $("#dialogiframe").attr("src",url);
        $("#dialog_resultbox").dialog({
            hide:true,    //点击关闭是隐藏,如果不加这项,关闭弹窗后再点就会出错.
            autoOpen:true,
            height:560,
            width:800,
            modal:true, //蒙层（弹出会影响页面大小）
            title:'投票调查编辑',
            overlay: {opacity: 0.5, background: "black" ,overflow:'auto'}
        });
    });

    //投票调查题目选项管理
    $(".btn_open_vote_select_item_list").click(function(event) {
        event.preventDefault();
        var voteItemId=$(this).attr('idvalue');
        var voteItemTitle=$(this).attr('title');
        parent.G_TabUrl = '/default.php?secu=manage&mod=vote_select_item&m=list&vote_item_id=' + voteItemId;
        parent.G_TabTitle = voteItemTitle + '-编辑题目';
        parent.addTab();
    });

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
        VoteChangeState(docid, state);
    });
});

/**
 * 格式化状态值
 * @return {string}
 */
function FormatState(state){
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

function VoteChangeState(voteId, state) {
    if (state === 20) {
        $("#dialog_docnewsdelete").dialog({});
    }
    $("#spanstate_" + voteId).html("<img src='../system_images/manage/loading1.gif' />");

    //多文档操作
    var docid = "";
    $('input[name=vote_input]').each(function(i) {
        if (this.checked) {
            docid = docid + ',' + $(this).val();
        }
    });
    if (docid.length > 0) {
        $('input[name=vote_input]').each(function(i) {
            if (this.checked) {
                _SetVoteState($(this).val(), state);
            }
        });
    }
    else {
        _SetVoteState(voteId, state);
    }
}

function _SetVoteState(voteId, state) {
    $.post("/default.php?secu=manage&mod=vote&m=remove_to_bin&vote_id=" + voteId + "&state=" + state, {
        resultbox: $(this).html()
    }, function(xml) {
        if (parseInt(xml) > 0) {
            switch (state)
            {
                case 0:
                    $("#spanstate_" + voteId).text("启用");
                    break;
                case 100:
                    $('#dialog_docnewsdelete').dialog('close');
                    $("#spanstate_" + voteId).html("<span style='color:#990000'>停用</span>");
                    break;
                default:
                    $("#spanstate_" + voteId).text("错误");
                    break;
            }
        } else if (parseInt(xml) == -2) {
            alert("设置失败，您尚未开通操作此功能的权限，如需开通此权限，请联系管理人员！");
        }
        else {
            alert("设置失败");
        }
    });
    document.getElementById('divstate_' + voteId).style.display = "none";
}






