/**
 * Vote
 */
$(function() {
    //$(document).tooltip()会使title属性失效;
    var btnCreate = $("#btn_create");
    btnCreate.css("cursor", "pointer");
    btnCreate.click(function(event) {
        event.preventDefault();
        var siteId = Request["site_id"];
        var channelId = Request["channel_id"];
        var pageIndex = Request["p"]==null?1:Request["p"];
        pageIndex =  parseInt(pageIndex);
        if (pageIndex <= 0) {
            pageIndex = 1;
        }
        var url='/default.php?secu=manage&mod=vote&m=create&site_id='+siteId+'&channel_id='+channelId+'&p=' + pageIndex;
        $("#dialog_frame").attr("src",url);
        $("#dialog_resultbox").dialog({
            hide:true,    //点击关闭是隐藏,如果不加这项,关闭弹窗后再点就会出错.
            autoOpen:true,
            height:560,
            width:800,
            modal:true, //蒙层（弹出会影响页面大小）
            title:'投票调查新增',
            overlay: {opacity: 0.5, background: "black" ,overflow:'auto'}
        });
    });

    var btnModify = $(".btn_modify");
    btnModify.css("cursor", "pointer");
    btnModify.click(function(event) {
        event.preventDefault();
        var voteId = $(this).attr('idvalue');
        var pageIndex = Request["p"]==null?1:Request["p"];
        pageIndex =  parseInt(pageIndex);
        if (pageIndex <= 0) {
            pageIndex = 1;
        }
        var url='/default.php?secu=manage&mod=vote&m=modify&vote_id=' + voteId + '&p=' + pageIndex;
        $("#dialog_frame").attr("src",url);
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

    //投票调查题目管理
    $(".btn_open_vote_item_list").click(function(event) {
        event.preventDefault();
        var voteId=$(this).attr('idvalue');
        var voteTitle=$(this).attr('title');
        parent.G_TabUrl = '/default.php?secu=manage&mod=vote_item&m=list&vote_id=' + voteId;
        parent.G_TabTitle = voteTitle + '-编辑题目';
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
        $(this).html(FormatVoteState($(this).attr("title")));
    });

    $(".div_start").click(function(){
        var idvalue = $(this).attr("idvalue");
        var state = "0";
        ModifyVoteState(idvalue,state);
    });

    $(".div_stop").click(function(){
        var idvalue = $(this).attr("idvalue");
        var state = "100";
        ModifyVoteState(idvalue,state);
    });
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

function ModifyVoteState(idvalue, state) {
    $("#span_state_" + idvalue).html("<img src='/system_template/common/images/loading1.gif' />");

    //多行操作
    var id = "";
    var voteInput = $('input[name=vote_input]');
    voteInput.each(function() {
        if (this.checked) {
            id = id + ',' + $(this).val();
        }
    });
    if (id.length > 0) {
        voteInput.each(function() {
            if (this.checked) {
                _ModifyVoteState($(this).val(), state);
            }
        });
    }
    else {
        _ModifyVoteState(idvalue, state);
    }
}

function _ModifyVoteState(idvalue, state) {
    $.ajax({
        url:"/default.php?secu=manage&mod=vote&m=async_modify_state",
        data:{state:state,vote_id:idvalue},
        dataType:"jsonp",
        jsonp:"jsonpcallback",
        success:function(data){
        if (parseInt(data["result"]) > 0) {
            $("#span_state_" + idvalue).html(FormatVoteState(state));
        }
        else alert("修改失败，请联系管理员");
        }
    });
}






