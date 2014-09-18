
$().ready(function(){
    //选中时的样式变化
    $('.grid_item').click(function() {
        if ($(this).hasClass('grid_item_selected')) {
            $(this).removeClass('grid_item_selected');
        } else {
            $(this).addClass('grid_item_selected');
        }
    });
});
/**
 * 格式化状态值
 * @param state 状态
 * @return string
 */
function FormatState(state){
    switch (state){
        case "0":
            return "启用";
            break;
        case "30":
            return "发布";
            break;
        case "100":
            return "<"+"span style='color:#990000'>删除<"+"/span>";
            break;
        default :
            return "未知";
            break;
    }
}

/**
 * 修改状态值
 * @param method 业务
 * @param idvalue 业务id
 * @param state 状态
 * @return
 */
function ModifyState(method, idvalue, state) {
    $.ajax({
        url:"/default.php?secu=manage&mod="+method+"&m=modify_state",
        data:{state:state,table_id:idvalue},
        dataType:"jsonp",
        jsonp:"jsonpcallback",
        success:function(data){
            if (parseInt(data["result"]) > 0) {
                $("#state_" + idvalue).html(FormatState(state));
            }
            else alert("修改失败，请联系管理员");
        }
    });
}