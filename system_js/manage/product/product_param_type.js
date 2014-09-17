/**
 * ProductParamType
 */
$(function() {
    //$(document).tooltip()会使title属性失效;
    var btnCreate = $("#btn_create");
    btnCreate.css("cursor", "pointer");
    btnCreate.click(function(event) {
        event.preventDefault();
        var productParamTypeClassId = Request["product_param_type_class_id"];
        var pageIndex = Request["p"]==null?1:Request["p"];
        pageIndex =  parseInt(pageIndex);
        if (pageIndex <= 0) {
            pageIndex = 1;
        }
        var url='/default.php?secu=manage&mod=product_param_type&m=create&product_param_type_class_id='+productParamTypeClassId+'&p=' + pageIndex;
        $("#dialog_frame").attr("src",url);
        $("#dialog_resultbox").dialog({
            hide:true,    //点击关闭是隐藏,如果不加这项,关闭弹窗后再点就会出错.
            autoOpen:true,
            height:250,
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
        var productParamTypeId = $(this).attr('idvalue');
        var pageIndex = Request["p"]==null?1:Request["p"];
        pageIndex =  parseInt(pageIndex);
        if (pageIndex <= 0) {
            pageIndex = 1;
        }
        var url='/default.php?secu=manage&mod=product_param_type&m=modify&product_param_type_id=' + productParamTypeId + '&p=' + pageIndex;
        $("#dialog_frame").attr("src",url);
        $("#dialog_resultbox").dialog({
            hide:true,    //点击关闭时隐藏,如果不加这项,关闭弹窗后再点就会出错.
            autoOpen:true,
            height:250,
            width:800,
            modal:true, //蒙层（弹出会影响页面大小）
            title:'产品编辑',
            overlay: {opacity: 0.5, background: "black" ,overflow:'auto'}
        });
    });

    //产品参数类型管理
    var btnOptionList=$(".btn_open_product_param_type_option_list");
    btnOptionList.each(function(){
        $(this).html(FormatParamType($(this).attr("title")));
    });
    btnOptionList.click(function(event) {
        event.preventDefault();
        var productParamTypeId=$(this).attr('idvalue');
        var ParamTypeName=$(this).attr('idname');
        parent.G_TabUrl = '/default.php?secu=manage&mod=product_param_type_option&m=list&product_param_type_id=' + productParamTypeId;
        parent.G_TabTitle = ParamTypeName + '-编辑参数选项';
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
        $(this).html(FormatVoteItemState($(this).attr("title")));
    });

    $(".span_param_value_type").each(function(){
        $(this).html(FormatParamValueType($(this).attr("title")));
    });

    $(".div_start").click(function(){
        var idvalue = $(this).attr("idvalue");
        var state = "0";
        ModifyVoteItemState(idvalue,state);
    });

    $(".div_stop").click(function(){
        var idvalue = $(this).attr("idvalue");
        var state = "100";
        ModifyVoteItemState(idvalue,state);
    });
});

/**
 * 格式化状态值
 * @return {string}
 */
function FormatVoteItemState(state){
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

/**
 * 格式化产品参数类型名称
 * @return {string}
 */
function FormatParamValueType(paramValueType){
    switch (paramValueType){
        case "0":
            return "短字符串";
            break;
        case "1":
            return "长字符串";
            break;
        case "2":
            return "文本";
            break;
        case "3":
            return "单精度";
            break;
        case "4":
            return "双精度";
            break;
        case "5":
            return "超链接";
            break;
        case "6":
            return "下拉选择框";
            break;
        default :
            return "短字符串";
            break;
    }
}

/**
 * 根据产品参数类型选择是否生成参数类型选项链接
 * @return {string}
 */
function FormatParamType(paramValueType){
    switch (paramValueType){
        case "0":
            return "";
            break;
        case "6":
            return "编辑参数选项";
            break;
        default :
            return "";
            break;
    }
}

function ModifyVoteItemState(idvalue, state) {
    $("#span_state_" + idvalue).html("<img src='/system_template/common/images/loading1.gif' />");

    //多行操作
    var id = "";
    var voteItemInput = $('input[name=product_param_type_input]');
    voteItemInput.each(function() {
        if (this.checked) {
            id = id + ',' + $(this).val();
        }
    });
    if (id.length > 0) {
        voteItemInput.each(function() {
            if (this.checked) {
                _ModifyVoteItemState($(this).val(), state);
            }
        });
    }
    else {
        _ModifyVoteItemState(idvalue, state);
    }
}

function _ModifyVoteItemState(idvalue, state) {
    $.ajax({
        url:"/default.php?secu=manage&mod=product_param_type&m=async_modify_state",
        data:{state:state,product_param_type_id:idvalue},
        dataType:"jsonp",
        jsonp:"jsonpcallback",
        success:function(data){
            if (parseInt(data["result"]) > 0) {
                $("#span_state_" + idvalue).html(FormatVoteItemState(state));
            }
            else alert("修改失败，请联系管理员");
        }
    });
}






