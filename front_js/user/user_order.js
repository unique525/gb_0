function FormatOrderState(state,idvalue){
    var result = '';
    switch(state){
        case "0":
            result = '<span class="span_state" id="State_'+idvalue+'">未付款</span>';
            break;
        case "10":
            result = '<span class="span_state" id="State_'+idvalue+'">未付款</span><br /><a href="/default.php?mod=user_order&a=pay&user_order_id='+ idvalue +'" target="_blank">[立即付款]</a>';
            break;
        case "15":
            result = '<span class="span_state" id="State_'+idvalue+'">货到付款</span>';
            break;
        case "20":
            result = '<span class="span_state" id="State_'+idvalue+'">已付款</span>';
            break;
        case "25":
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