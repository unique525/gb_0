function FormatState(state,idvalue){
    var result;
    switch(state){
        case "0":
            result = '<span class="span_state" id="State_'+idvalue+'">未付款</span>';
            break;
        case "10":
            result = '<span class="span_state" id="State_'+idvalue+'">已付款</span>';
            break;
        case "20":
            result = '<span class="span_state" id="State_'+idvalue+'">已发货</span>';
            break;
        case "30":
            result = '<span class="span_state" id="State_'+idvalue+'">交易完成</span>';
            break;
        case "40":
            result = '<span class="span_state" id="State_'+idvalue+'" style="color:red">交易关闭</span>';
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

$(document).ready(function(){
    $(".edit").click(function(){
        var userOrderId = $(this).attr("idvalue");
        window.location.href="/default.php?secu=manage&mod=user_order&user_order_id="+userOrderId+"&m=modify&site_id="+ parent.G_NowSiteId
            +"&p="+parent.G_NowPageIndex+"&ps="+parent.G_PageSize+"&tab_index="+parent.G_TabIndex;
    });

    $(".span_state").each(function(){
        var state = $(this).html();
        var idvalue = $(this).attr("idvalue");
        $(this).html(FormatState(state,idvalue));
    });

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
        var url='/default.php?secu=manage&mod=user_order&m=modify_order_product&user_order_product_id=' + userOrderProductId
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
        var url='/default.php?secu=manage&mod=user_order&m=user_order_pay_list&user_order_id=' + userOrderId + '&site_id='
            +parent.G_NowSiteId+'&p=' + pageIndex;
        $("#user_order_pay_dialog_frame").attr("src",url);
        $("#dialog_user_order_pay_box").dialog({
            hide:true,    //点击关闭是隐藏,如果不加这项,关闭弹窗后再点就会出错.
            autoOpen:true,
            height:450,
            width:800,
            modal:true, //蒙层（弹出会影响页面大小）
            title:'订单中的支付信息',
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
    $("#AllSaleProductPrice").html(AllSaleProductPrice+"元");

    $("#SendPrice").blur(function(){
        var SendPrice = parseFloat($("#SendPrice").val());
        var AllPrice = AllSaleProductPrice+SendPrice;
        $("#display_AllPrice").html(AllPrice+"元");
        $("#AllPrice").val(AllPrice);
    });
});