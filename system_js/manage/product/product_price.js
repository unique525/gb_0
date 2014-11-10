/**
 * ProductPrice
 */
$(function() {
    //$(document).tooltip()会使title属性失效;
});

function ProductPriceCreate(productId){
    var pageIndex = Request["p"]==null?1:Request["p"];
    pageIndex =  parseInt(pageIndex);
    if (pageIndex <= 0) {
        pageIndex = 1;
    }
    var url='/default.php?secu=manage&mod=product_price&m=create&product_id='+productId+'&p=' + pageIndex;
    $("#dialog_frame").attr("src",url);
    $("#dialog_resultbox").dialog({
        hide:true,    //点击关闭时隐藏,如果不加这项,关闭弹窗后再点就会出错.
        autoOpen:true,
        height:280,
        width:800,
        modal:true, //蒙层（弹出会影响页面大小）
        title:'产品价格新增',
        overlay: {opacity: 0.5, background: "black" ,overflow:'auto'}
    });
}

function ProductPriceEdit(obj){
    var productPriceId = $(obj).attr('idvalue');
    var pageIndex = Request["p"]==null?1:Request["p"];
    pageIndex =  parseInt(pageIndex);
    if (pageIndex <= 0) {
        pageIndex = 1;
    }
    var url='/default.php?secu=manage&mod=product_price&m=modify&product_price_id=' + productPriceId + '&p=' + pageIndex;
    $("#dialog_frame").attr("src",url);
    $("#dialog_resultbox").dialog({
        hide:true,    //点击关闭是隐藏,如果不加这项,关闭弹窗后再点就会出错.
        autoOpen:true,
        height:280,
        width:800,
        modal:true, //蒙层（弹出会影响页面大小）
        title:'产品价格编辑',
        overlay: {opacity: 0.5, background: "black" ,overflow:'auto'}
    });
}

/**
 * 格式化状态值
 * @param state 状态
 * @return {string}
 */
function FormatProductPriceState(state){
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

function ModifyProductPriceState(idvalue, state) {
    $.ajax({
        url:"/default.php?secu=manage&mod=product_price&m=async_modify_state",
        data:{state:state,product_price_id:idvalue},
        dataType:"jsonp",
        jsonp:"jsonpcallback",
        success:function(data){
        if (parseInt(data["result"]) > 0) {
            $("#span_state_" + idvalue).html(FormatProductPriceState(state));
        }
        else alert("修改失败，请联系管理员");
        }
    });
}






