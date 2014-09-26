/**
 * ProductPic
 */
$(function() {
    //$(document).tooltip()会使title属性失效;
    var btnCreate = $("#btn_create");
    btnCreate.css("cursor", "pointer");
    btnCreate.click(function(event) {
        event.preventDefault();
        var channelId = Request["channel_id"];
        var productId = Request["product_id"];
        var pageIndex = Request["p"]==null?1:Request["p"];
        pageIndex =  parseInt(pageIndex);
        if (pageIndex <= 0) {
            pageIndex = 1;
        }
        var url='/default.php?secu=manage&mod=product_pic&m=create&channel_id='+channelId+'&product_id='+productId+'&p=' + pageIndex;
        $("#dialog_frame").attr("src",url);
        $("#dialog_resultbox").dialog({
            hide:true,    //点击关闭是隐藏,如果不加这项,关闭弹窗后再点就会出错.
            autoOpen:true,
            height:250,
            width:800,
            modal:true, //蒙层（弹出会影响页面大小）
            title:'产品组图新增',
            overlay: {opacity: 0.5, background: "black" ,overflow:'auto'}
        });
    });

    var btnModify = $(".btn_modify");
    btnModify.css("cursor", "pointer");
    btnModify.click(function(event) {
        event.preventDefault();
        var channelId = Request["channel_id"];
        var productPicId = $(this).attr('idvalue');
        var pageIndex = Request["p"]==null?1:Request["p"];
        pageIndex =  parseInt(pageIndex);
        if (pageIndex <= 0) {
            pageIndex = 1;
        }
        var url='/default.php?secu=manage&mod=product_pic&m=modify&channel_id='+channelId+'&product_pic_id=' + productPicId + '&p=' + pageIndex;
        $("#dialog_frame").attr("src",url);
        $("#dialog_resultbox").dialog({
            hide:true,    //点击关闭时隐藏,如果不加这项,关闭弹窗后再点就会出错.
            autoOpen:true,
            height:250,
            width:800,
            modal:true, //蒙层（弹出会影响页面大小）
            title:'产品组图编辑',
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
        $(this).html(FormatProductPicState($(this).attr("title")));
    });

    $(".div_start").click(function(){
        var idvalue = $(this).attr("idvalue");
        var state = "0";
        ModifyProductPicState(idvalue,state);
    });

    $(".div_stop").click(function(){
        var idvalue = $(this).attr("idvalue");
        var state = "100";
        ModifyProductPicState(idvalue,state);
    });
});

/**
 * 格式化状态值
 * @return {string}
 */
function FormatProductPicState(state){
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

function ModifyProductPicState(idvalue, state) {
    $("#span_state_" + idvalue).html("<img src='/system_template/common/images/loading1.gif' />");

    //多行操作
    var id = "";
    var voteItemInput = $('input[name=product_pic_input]');
    voteItemInput.each(function() {
        if (this.checked) {
            id = id + ',' + $(this).val();
        }
    });
    if (id.length > 0) {
        voteItemInput.each(function() {
            if (this.checked) {
                _ModifyProductPicState($(this).val(), state);
            }
        });
    }
    else {
        _ModifyProductPicState(idvalue, state);
    }
}

function _ModifyProductPicState(idvalue, state) {
    $.ajax({
        url:"/default.php?secu=manage&mod=product_pic&m=async_modify_state",
        data:{state:state,product_pic_id:idvalue},
        dataType:"jsonp",
        jsonp:"jsonpcallback",
        success:function(data){
            if (parseInt(data["result"]) > 0) {
                $("#span_state_" + idvalue).html(FormatProductPicState(state));
            }
            else alert("修改失败，请联系管理员");
        }
    });
}






