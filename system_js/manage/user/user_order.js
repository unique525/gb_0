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
        default:
            result = '<span class="span_state" id="State_'+idvalue+'">启用</span>';
            break;
    }
    return result;
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
});