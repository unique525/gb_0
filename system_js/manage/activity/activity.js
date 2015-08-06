



$().ready(function(){
    //选中时的样式变化
    $('.grid_item').click(function() {
        if ($(this).hasClass('grid_item_selected')) {
            $(this).removeClass('grid_item_selected');
        } else {
            $(this).addClass('grid_item_selected');
        }
    });



    //拖动排序变化
    var sortGrid = $("#sort_grid");
    sortGrid.sortable();
    sortGrid.bind("sortstop", function(event, ui) {
        var sortList = $("#sort_grid").sortable("serialize");
        $.post("/default.php?secu=manage&mod=activity&m=async_modify_sort_by_drag&" + sortList, {
            resultbox: $(this).html()
        }, function() {
            //操作完成后触发的命令
        });
    });
    sortGrid.disableSelection();


});


/**
 * 时间按单位分割
 * @param timeName 时间dom id
 * @param timeValue 完整时间string
 * @return
 */
function GetTimes(timeName,timeValue){
    var strDate=timeValue.substr(0,10);
    var strHour=timeValue.substr(11,2);
    var strMin=timeValue.substr(14,2);
    var strSec=timeValue.substr(17,2);
    var Time = $("#f_"+timeName);
    Time.val(strDate);
    $("#f_"+timeName+"ShowHour").val(strHour);
    $("#f_"+timeName+"ShowMinute").val(strMin);
    $("#f_"+timeName+"ShowSecond").val(strSec);


}

/**
 * 合并时间单位
 * @param timeName 时间dom id
 * @return
 */
function SetTimes(timeName){
    var Time = $("#f_"+timeName);
    if(Time.val()){
        Time.val(Time.val().substr(0,10)+' '+$("#f_"+timeName+"ShowHour").val()+':'+$("#f_"+timeName+"ShowMinute").val()+':'+$("#f_"+timeName+"ShowSecond").val());
    }
}


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





