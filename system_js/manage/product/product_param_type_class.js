/**
 * ProductParamTypeClass
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
        var url='/default.php?secu=manage&mod=product_param_type_class&m=create&site_id='+siteId+'&channel_id='+channelId+'&p=' + pageIndex;
        $("#dialog_frame").attr("src",url);
        $("#dialog_resultbox").dialog({
            hide:true,    //点击关闭时隐藏,如果不加这项,关闭弹窗后再点就会出错.
            autoOpen:true,
            height:260,
            width:800,
            modal:true, //蒙层（弹出会影响页面大小）
            title:'产品参数类别新增',
            overlay: {opacity: 0.5, background: "black" ,overflow:'auto'}
        });
    });

    var btnModify = $(".btn_modify");
    btnModify.css("cursor", "pointer");
    btnModify.click(function(event) {
        event.preventDefault();
        var productParamTypeClassId = $(this).attr('idvalue');
        var pageIndex = Request["p"]==null?1:Request["p"];
        pageIndex =  parseInt(pageIndex);
        if (pageIndex <= 0) {
            pageIndex = 1;
        }
        var url='/default.php?secu=manage&mod=product_param_type_class&m=modify&product_param_type_class_id=' + productParamTypeClassId + '&p=' + pageIndex;
        $("#dialog_frame").attr("src",url);
        $("#dialog_resultbox").dialog({
            hide:true,    //点击关闭是隐藏,如果不加这项,关闭弹窗后再点就会出错.
            autoOpen:true,
            height:260,
            width:800,
            modal:true, //蒙层（弹出会影响页面大小）
            title:'产品参数类别编辑',
            overlay: {opacity: 0.5, background: "black" ,overflow:'auto'}
        });
    });

    //产品参数类型管理
    $(".btn_open_product_param_type_list").click(function(event) {
        event.preventDefault();
        var productParamTypeClassId=$(this).attr('idvalue');
        var productParamTypeClassName=$(this).attr('title');
        parent.G_TabUrl = '/default.php?secu=manage&mod=product_param_type&m=list&product_param_type_class_id=' + productParamTypeClassId;
        parent.G_TabTitle = productParamTypeClassName + '-编辑产品参数';
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
        $(this).html(FormatProductParamTypeClassState($(this).attr("title")));
    });

    $(".div_start").click(function(){
        var idvalue = $(this).attr("idvalue");
        var state = "0";
        ModifyProductParamTypeClassState(idvalue,state);
    });

    $(".div_stop").click(function(){
        var idvalue = $(this).attr("idvalue");
        var state = "100";
        ModifyProductParamTypeClassState(idvalue,state);
    });
});

/**
 * 格式化状态值
 * @param state 状态
 * @return {string}
 */
function FormatProductParamTypeClassState(state){
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

function ModifyProductParamTypeClassState(idvalue, state) {
    $("#span_state_" + idvalue).html("<img src='/system_template/common/images/loading1.gif' />");

    //多行操作
    var id = "";
    var product_param_type_classInput = $('input[name=product_param_type_class_input]');
    product_param_type_classInput.each(function() {
        if (this.checked) {
            id = id + ',' + $(this).val();
        }
    });
    if (id.length > 0) {
        product_param_type_classInput.each(function() {
            if (this.checked) {
                _ModifyProductParamTypeClassState($(this).val(), state);
            }
        });
    }
    else {
        _ModifyProductParamTypeClassState(idvalue, state);
    }
}

function _ModifyProductParamTypeClassState(idvalue, state) {
    $.ajax({
        url:"/default.php?secu=manage&mod=product_param_type_class&m=async_modify_state",
        data:{state:state,product_param_type_class_id:idvalue},
        dataType:"jsonp",
        jsonp:"jsonpcallback",
        success:function(data){
        if (parseInt(data["result"]) > 0) {
            $("#span_state_" + idvalue).html(FormatProductParamTypeClassState(state));
        }
        else alert("修改失败，请联系管理员");
        }
    });
}






