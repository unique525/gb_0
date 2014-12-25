
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
 * 状态：申请退货
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
            result = '<span class="span_state" id="State_'+idvalue+'">未付款</span><br /><a href="/default.php?mod=user_order&a=pay&user_order_id='+ idvalue +'" target="_blank">[立即付款]</a>';
            break;
        case USER_ORDER_STATE_PAYMENT_AFTER_RECEIVE:
            result = '<span class="span_state" id="State_'+idvalue+'">货到付款</span>';
            break;
        case USER_ORDER_STATE_PAYMENT:
            result = '<span class="span_state" id="State_'+idvalue+'">已付款</span>';
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
            result = '<span class="span_state" id="State_'+idvalue+'" style="color:red">申请退货</span>';
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