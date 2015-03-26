
/**
 * 状态：新建
 */
window.USER_ORDER_STATE_NEW = 0;
/**
 * 状态：未付款
 */
window.USER_ORDER_STATE_NON_PAYMENT = 10;
/**
 * 状态：货到付款
 */
window.USER_ORDER_STATE_PAYMENT_AFTER_RECEIVE = 15;
/**
 * 状态：已付款，未发货
 */
window.USER_ORDER_STATE_PAYMENT = 20;
/**
 * 状态：已发货
 */
window.USER_ORDER_STATE_SENT = 25;
/**
 * 状态：交易完成
 */
window.USER_ORDER_STATE_DONE = 30;
/**
 * 状态：交易完成，已评价
 */
window.USER_ORDER_STATE_COMMENT_FINISHED = 31;
/**
 * 状态：交易关闭
 */
window.USER_ORDER_STATE_CLOSE = 40;
/**
 * 状态：申请退款
 */
window.USER_ORDER_STATE_APPLY_REFUND = 50;
/**
 * 状态：退款完成
 */
window.USER_ORDER_STATE_REFUND_FINISHED = 55;
/**
 * 状态：未评价
 */
window.USER_ORDER_STATE_UNCOMMENT = 70;
/**
 * 状态：已删除
 */
window.USER_ORDER_STATE_REMOVED = 100;

/**
 *
 * @param state
 * @param idvalue
 * @returns {string}
 * @constructor
 */
function FormatOrderState(state,idvalue){
    var result = '';

    state = parseInt(state);

    switch(state){
        case USER_ORDER_STATE_NEW:
            result = '<span class="span_state" id="State_'+idvalue+'">未付款</span>';
            break;
        case USER_ORDER_STATE_NON_PAYMENT:
            result = '<span class="span_state" id="State_'+idvalue+'">未付款</span>';
            break;
        case USER_ORDER_STATE_PAYMENT_AFTER_RECEIVE:
            result = '<span class="span_state" id="State_'+idvalue+'">货到付款</span>';
            break;
        case USER_ORDER_STATE_PAYMENT:
            result = '<span class="span_state" id="State_'+idvalue+'">已付款，未发货</span>';
            break;
        case USER_ORDER_STATE_SENT:
            result = '<span class="span_state" id="State_'+idvalue+'">已发货</span>';
            break;
        case USER_ORDER_STATE_DONE:
            result = '<span class="span_state" id="State_'+idvalue+'">交易完成</span>';
            break;
        case USER_ORDER_STATE_COMMENT_FINISHED:
            result = '<span class="span_state" id="State_'+idvalue+'">交易完成，已评价</span>';
            break;
        case USER_ORDER_STATE_CLOSE:
            result = '<span class="span_state" id="State_'+idvalue+'" style="color:red">交易关闭</span>';
            break;
        case USER_ORDER_STATE_APPLY_REFUND:
            result = '<span class="span_state" id="State_'+idvalue+'" style="color:red">申请退款</span>';
            break;
        case USER_ORDER_STATE_REFUND_FINISHED:
            result = '<span class="span_state" id="State_'+idvalue+'" style="color:red">退款完成</span>';
            break;
        case USER_ORDER_STATE_UNCOMMENT:
            result = '<span class="span_state" id="State_'+idvalue+'">未评价</span>';
            break;
        case USER_ORDER_STATE_REMOVED:
            result = '<span class="span_state" id="State_'+idvalue+'">已删除</span>';
            break;
    }
    return result;
}

function FormatOrderPayState(state,idvalue){
    var result;
    switch(state){
        case "0":
            result = '<span class="span_state" id="State_'+idvalue+'">未确认</span>';
            break;
        case "10":
            result = '<span class="span_state" id="State_'+idvalue+'">已确认</span>';
            break;
    }
    return result;
}

function submitForm(continueCreate) {
    if ($('#UserOrderName').val() == '') {
        $("#dialog_box").dialog({width: 300, height: 100});
        $("#dialog_content").html("请输入会员订单名称");
    } else {
        if (continueCreate == 1) {
            $("#CloseTab").val("0");
        } else {
            $("#CloseTab").val("1");
        }
        $('#mainForm').submit();
    }
}

function submitUserProductForm(){
    var SaleCount = parseFloat($("#SaleCount").val());
    if(SaleCount > 0){
        var SalePrice = parseFloat($("#SalePrice").val());
        var Subtotal = SalePrice * SaleCount;
        $("#Subtotal").val(Subtotal);
        $("#userOrderProductForm").submit();
    }else{
        alert("购买数量不能小于0");
    }
}

function confirmPay(idvalue){
    var confirm_way = $("#confirm_way_input_"+idvalue).val();
    var confirm_price = $("#confirm_price_input_"+idvalue).val();
    $.ajax({
        url:"/default.php?secu=manage&mod=user_order_pay&m=async_confirm",
        data:{user_order_pay_id:idvalue,confirm_way:confirm_way,confirm_price:confirm_price},
        dataType:"jsonp",
        jsonp:"jsonpcallback",
        success:function(data){
            if(data["result"] > 0){
                $("#confirm_pay_way_"+idvalue).html(data["confirm_way"]);
                $("#confirm_pay_price_"+idvalue).html(formatPrice(data["confirm_price"]));
                $("#confirm_pay_date_"+idvalue).html(data["confirm_date"]);
                $("#manage_username_"+idvalue).html(data["manage_username"]);
                $("#span_order_pay_state_"+idvalue).html("已确认");
                $("#span_order_pay_"+idvalue+"_button").html('' +
                    '<div class="btn2 modify_pay" idvalue="'+idvalue+'" style="width:45px;height:25px" onclick="ModifyPay('+idvalue+')">修改</div>'
                );
            }else{
                alert("修改失败");
            }
        }
    });
}

function ModifyPay(idvalue){
    var pay_way = $("#confirm_pay_way_"+idvalue).html();
    var pay_price = $("#confirm_pay_price_"+idvalue).html();
    $("#confirm_pay_way_"+idvalue).html('<input id="confirm_way_input_'+idvalue+'" type="text" class="input_box" value="'+pay_way+'" style="width:70px"/>');
    $("#confirm_pay_price_"+idvalue).html('<input id="confirm_price_input_'+idvalue+'" type="text" class="input_price" value="'+pay_price+'" style="width:70px"/>');
    $("#span_order_pay_"+idvalue+"_button").html('<div class="btn2 confirm_pay" idvalue="'+idvalue+'" style="width:45px;height:25px" onclick="confirmPay('+idvalue+');">确认</div>');
}

$(document).ready(function(){
    $(".edit").click(function(){
        var userOrderId = $(this).attr("idvalue");
        parent.G_TabUrl = "/default.php?secu=manage&mod=user_order&user_order_id="+userOrderId+"&m=modify&site_id="+ parent.G_NowSiteId
            +"&p="+parent.G_NowPageIndex+"&ps="+parent.G_PageSize;
        parent.G_TabTitle = '编辑订单';
        parent.addTab();
    });

    $(".span_state").each(function(){
        var state = $(this).html();
        var idvalue = $(this).attr("idvalue");
        $(this).html(FormatOrderState(state,idvalue));
    });
/**
    $(".span_order_pay_state").each(function(){
        var state = $(this).html();
        var idvalue = $(this).attr("idvalue");
        $(this).html(FormatOrderPayState(state,idvalue));
        if(state == 0){
            $("#confirm_pay_way_"+idvalue).html('<input id="confirm_way_input_'+idvalue+'" type="text" class="input_box" value="" style="width:70px"/>');
            $("#confirm_pay_price_"+idvalue).html('<input id="confirm_price_input_'+idvalue+'" type="text" class="input_price" value="" style="width:70px"/>');
            $("#confirm_pay_date_"+idvalue).html('');
            $("#span_order_pay_"+idvalue+"_button").html('<div class="btn2 confirm_pay" idvalue="'+idvalue+'" style="width:45px;height:25px" onclick="confirmPay('+idvalue+');">确认</div>');
        }else{
            $("#span_order_pay_"+idvalue+"_button").html('<div class="btn2 modify_pay" idvalue="'+idvalue+'" style="width:45px;height:25px" onclick="ModifyPay('+idvalue+');">修改</div>');
        }

    });
*/
    var btnOrderProductEdit = $(".btn_order_product_edit");
    btnOrderProductEdit.css("cursor", "pointer");
    btnOrderProductEdit.click(function(event) {
        event.preventDefault();
        var userOrderProductId = $(this).attr("idvalue");
        var userOrderId = $(this).attr("title");
        var pageIndex = Request["p"]==null?1:Request["p"];
        pageIndex =  parseInt(pageIndex);
        if (pageIndex <= 0) {
            pageIndex = 1;
        }
        var url='/default.php?secu=manage&mod=user_order_product&m=modify&user_order_product_id=' + userOrderProductId
            + '&user_order_id='+userOrderId+'&site_id='+parent.G_NowSiteId+'&p=' + pageIndex;
        $("#user_order_product_dialog_frame").attr("src",url);
        $("#dialog_user_order_product_box").dialog({
            hide:true,    //点击关闭是隐藏,如果不加这项,关闭弹窗后再点就会出错.
            autoOpen:true,
            height:450,
            width:800,
            modal:true, //蒙层（弹出会影响页面大小）
            title:'订单中的商品信息编辑',
            overlay: {opacity: 0.5, background: "black" ,overflow:'auto'}
        });
    });

    $("#btn_user_order_pay").click(function(){
        var userOrderId = $(this).attr("idvalue");
        var pageIndex = Request["p"]==null?1:Request["p"];
        var url='/default.php?secu=manage&mod=user_order_pay&m=list&user_order_id=' + userOrderId + '&site_id='
            +parent.G_NowSiteId+'&p=' + pageIndex;
        $("#user_order_pay_dialog_frame").attr("src",url);
        $("#dialog_user_order_pay_box").dialog({
            hide:true,    //点击关闭是隐藏,如果不加这项,关闭弹窗后再点就会出错.
            autoOpen:true,
            height:650,
            width:1250,
            modal:true, //蒙层（弹出会影响页面大小）
            title:'订单中的支付信息',
            overlay: {opacity: 0.5, background: "black" ,overflow:'auto'}
        });
    });

    //在订单列表页打开支付信息
    $(".btn_user_order_list_pay_info").click(function(){
        var userOrderId = $(this).attr("idvalue");
        var pageIndex = Request["p"]==null?1:Request["p"];
        var url='/default.php?secu=manage&mod=user_order_pay&m=list&user_order_id=' + userOrderId + '&site_id='
            +parent.G_NowSiteId+'&p=' + pageIndex;
        $("#user_order_pay_dialog_frame").attr("src",url);
        $("#dialog_user_order_pay_box").dialog({
            hide:true,    //点击关闭是隐藏,如果不加这项,关闭弹窗后再点就会出错.
            autoOpen:true,
            height:450,
            width:850,
            modal:true, //蒙层（弹出会影响页面大小）
            title:'订单中的支付信息',
            overlay: {opacity: 0.5, background: "black" ,overflow:'auto'}
        });
    });

    //在订单列表页打开发货信息
    $(".btn_user_order_list_send_info").click(function(){
        var userOrderId = $(this).attr("idvalue");
        var pageIndex = Request["p"]==null?1:Request["p"];
        var url='/default.php?secu=manage&mod=user_order_send&m=list&user_order_id=' + userOrderId + '&site_id='
            +parent.G_NowSiteId+'&p=' + pageIndex;
        $("#user_order_send_dialog_frame").attr("src",url);
        $("#dialog_user_order_send_box").dialog({
            hide:true,    //点击关闭是隐藏,如果不加这项,关闭弹窗后再点就会出错.
            autoOpen:true,
            height:450,
            width:850,
            modal:true, //蒙层（弹出会影响页面大小）
            title:'订单中的发货信息',
            overlay: {opacity: 0.5, background: "black" ,overflow:'auto'}
        });
    });

    $("#SalePrice").blur(function(){
        var SaleCount = parseFloat($("#SaleCount").val());
        var SalePrice = parseFloat($("#SalePrice").val());
        var Subtotal = SalePrice * SaleCount;
        $("#display_subtotal").html(Subtotal);
    });

    $("#SaleCount").blur(function(){
        var SaleCount = parseFloat($("#SaleCount").val());
        var SalePrice = parseFloat($("#SalePrice").val());
        var Subtotal = SalePrice * SaleCount;
        $("#display_subtotal").html(Subtotal);
    });

    //计算订单中的产品总价
    var AllSaleProductPrice = 0;
    $(".UserOrderSubtotal").each(function(){
        var ProductPrice = parseFloat($(this).html());
        AllSaleProductPrice = ProductPrice+AllSaleProductPrice;
    });
    $("#AllSaleProductPrice").html(formatPrice(AllSaleProductPrice));

//
//    $("#SendPrice").blur(function(){
//        var SendPrice = parseFloat($("#SendPrice").val());
//        var AllPrice = AllSaleProductPrice+SendPrice;
//        $("#display_AllPrice").html(formatPrice(AllPrice));
//        $("#AllPrice").val(AllPrice);
//    });

    $(".delete_order_product").click(function(){
        var user_order_product_id = $(this).attr("idvalue");
        var user_order_id = $(this).attr("title");

        $.ajax({
            url:"/default.php?secu=manage&mod=user_order_product&m=async_modify_state",
            data:{user_order_id:user_order_id,user_order_product_id:user_order_product_id,state:100},
            dataType:"jsonp",
            jsonp:"jsonpcallback",
            success:function(data){
                if(data["result"] > 0){
                    $("#user_order_product_"+user_order_product_id).remove();
                    $("#display_AllPrice").val(formatPrice(data["all_price"]));
                    $("#AllPrice").val(data["all_price"]);
                    var AllSaleProductPrice = 0;
                    $(".UserOrderSubtotal").each(function(){
                        var ProductPrice = parseFloat($(this).html());
                        AllSaleProductPrice = ProductPrice+AllSaleProductPrice;
                    });
                    $("#AllSaleProductPrice").html(formatPrice(AllSaleProductPrice));
                }else{
                    alert("修改失败,请联系管理员");
                }
            }
        });
    });
});