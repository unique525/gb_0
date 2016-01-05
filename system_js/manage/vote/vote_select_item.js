/**
 * Vote
 */
$(function() {
    //$(document).tooltip()会使title属性失效;
    var btnCreate = $("#btn_create");
    btnCreate.css("cursor", "pointer");
    btnCreate.click(function(event) {
        event.preventDefault();
        var voteItemId = Request["vote_item_id"];
        var pageIndex = Request["p"]==null?1:Request["p"];
        pageIndex =  parseInt(pageIndex);
        if (pageIndex <= 0) {
            pageIndex = 1;
        }
        var url='/default.php?secu=manage&mod=vote_select_item&m=create&vote_item_id='+voteItemId+'&p=' + pageIndex;
        $("#dialog_frame").attr("src",url);
        $("#dialog_resultbox").dialog({
            hide:true,    //点击关闭是隐藏,如果不加这项,关闭弹窗后再点就会出错.
            autoOpen:true,
            height:400,
            width:800,
            modal:true, //蒙层（弹出会影响页面大小）
            title:'新增题目选项',
            overlay: {opacity: 0.5, background: "black" ,overflow:'auto'}
        });
    });

    var btnModify = $(".btn_modify");
    btnModify.css("cursor", "pointer");
    btnModify.click(function(event) {
        event.preventDefault();
        var voteSelectItemId = $(this).attr('idvalue');
        var pageIndex = Request["p"]==null?1:Request["p"];
        pageIndex =  parseInt(pageIndex);
        if (pageIndex <= 0) {
            pageIndex = 1;
        }
        var url='/default.php?secu=manage&mod=vote_select_item&m=modify&vote_select_item_id=' + voteSelectItemId + '&p=' + pageIndex;
        $("#dialog_frame").attr("src",url);
        $("#dialog_resultbox").dialog({
            hide:true,    //点击关闭是隐藏,如果不加这项,关闭弹窗后再点就会出错.
            autoOpen:true,
            height:400,
            width:800,
            modal:true, //蒙层（弹出会影响页面大小）
            title:'编辑题目选项',
            overlay: {opacity: 0.5, background: "black" ,overflow:'auto'}
        });
    });


    //维持比例加票数
    var btnAddCountRatio = $("#btn_add_count_ratio");
    btnAddCountRatio.css("cursor", "pointer");
    btnAddCountRatio.click(function(event) {
        event.preventDefault();
        var voteItemId = Request["vote_item_id"];
        var pageIndex = Request["p"]==null?1:Request["p"];
        pageIndex =  parseInt(pageIndex);
        if (pageIndex <= 0) {
            pageIndex = 1;
        }
        var url='/default.php?secu=manage&mod=vote_select_item&m=add_count_ratio&vote_item_id=' + voteItemId + '&p=' + pageIndex;
        $("#dialog_frame").attr("src",url);
        $("#dialog_resultbox").dialog({
            hide:true,    //点击关闭是隐藏,如果不加这项,关闭弹窗后再点就会出错.
            autoOpen:true,
            height:400,
            width:800,
            modal:true, //蒙层（弹出会影响页面大小）
            title:'维持比例加票数',
            overlay: {opacity: 0.5, background: "black" ,overflow:'auto'}
        });
    });


    //从节点导入选项
    var btnGetFromDocumentNews = $("#btn_create_from_channel");
    btnGetFromDocumentNews.css("cursor", "pointer");
    btnGetFromDocumentNews.click(function(event) {
        event.preventDefault();
        var voteItemId = Request["vote_item_id"];
        var url='/default.php?secu=manage&mod=vote_select_item&m=create_from_channel&vote_item_id=' + voteItemId;
        $("#dialog_frame").attr("src",url);
        $("#dialog_resultbox").dialog({
            hide:true,    //点击关闭是隐藏,如果不加这项,关闭弹窗后再点就会出错.
            autoOpen:true,
            height:400,
            width:800,
            modal:true, //蒙层（弹出会影响页面大小）
            title:'从节点导入选项',
            overlay: {opacity: 0.5, background: "black" ,overflow:'auto'}
        });
    });

    //从txt导入选项
    var btnGetFromTxt = $("#btn_create_from_txt");
    btnGetFromTxt.css("cursor", "pointer");
    btnGetFromTxt.click(function(event) {
        event.preventDefault();
        var voteItemId = Request["vote_item_id"];
        var url='/default.php?secu=manage&mod=vote_select_item&m=create_from_txt&vote_item_id=' + voteItemId;
        $("#dialog_frame").attr("src",url);
        $("#dialog_resultbox").dialog({
            hide:true,    //点击关闭是隐藏,如果不加这项,关闭弹窗后再点就会出错.
            autoOpen:true,
            height:400,
            width:900,
            modal:true, //蒙层（弹出会影响页面大小）
            title:'从txt导入',
            overlay: {opacity: 0.5, background: "black" ,overflow:'auto'}
        });
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
        $(this).html(FormatVoteSelectItemState($(this).text()));
    });

    $(".div_start").click(function(){
        var idvalue = $(this).attr("idvalue");
        var state = "0";
        ModifyVoteSelectItemState(idvalue,state);
    });

    $(".div_stop").click(function(){
        var idvalue = $(this).attr("idvalue");
        var state = "100";
        ModifyVoteSelectItemState(idvalue,state);
    });
});

/**
 * 格式化状态值
 * @return {string}
 */
function FormatVoteSelectItemState(state){
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

function ModifyVoteSelectItemState(idvalue, state) {
    $("#span_state_" + idvalue).html("<img src='/system_template/common/images/loading1.gif' />");

    //多行操作
    var id = "";
    var voteSelectItemInput = $('input[name=vote_select_item_input]');
    voteSelectItemInput.each(function() {
        if (this.checked) {
            id = id + ',' + $(this).val();
        }
    });
    if (id.length > 0) {
        voteSelectItemInput.each(function() {
            if (this.checked) {
                _ModifyVoteSelectItemState($(this).val(), state);
            }
        });
    }
    else {
        _ModifyVoteSelectItemState(idvalue, state);
    }
}

function _ModifyVoteSelectItemState(idvalue, state) {
    $.ajax({
        url:"/default.php?secu=manage&mod=vote_select_item&m=async_modify_state",
        data:{state:state,vote_select_item_id:idvalue},
        dataType:"jsonp",
        jsonp:"jsonpcallback",
        success:function(data){
            if (parseInt(data["result"]) > 0) {
                $("#span_state_" + idvalue).html(FormatVoteSelectItemState(state));
            }
            else alert("修改失败，请联系管理员");
        }
    });
}







