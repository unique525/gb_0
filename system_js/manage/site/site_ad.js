
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
        case "100":
            return "<"+"span style='color:#990000'>停用<"+"/span>";
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

/**
 * 检查日期
 * @param endDate 结束时间
 * @return
 */
function CheckEndDate(endDate){
    var isDate = 0;

    var today = new Date();
    var now_day = today.getDate();
    var now_month = today.getMonth()+1;
    var now_year = today.getFullYear();

    var arr=endDate.split(" ");
    arr=arr[0].split("-");
    var site_year = arr[0];
    var site_month = arr[1];
    var site_day = arr[2];

    if(site_year < now_year){
        isDate = 0;     //年过期
    } else{
        if(site_month < now_month){
            isDate = 0;     //月过期
        }else{
            if(site_day < now_day){
                isDate = 0;     //日期过期
            }else{
                isDate = 1;
            }
        }
    }

    if(isDate == 1){
        document.write(endDate);
    }else{
        document.write('<font color=red>'+endDate+'</font>');

    }
}