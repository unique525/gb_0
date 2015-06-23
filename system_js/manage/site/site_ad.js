
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
 * 格式化显示类型
 * @param showType 显示类型
 * @return string
 */
function FormatShowType(showType){
    switch (showType){
        case "0":
            return "图片";
            break;
        case "1":
            return "文字";
            break;
        case "2":
            return "轮换";
            break;
        case "3":
            return "随机";
            break;
        case "4":
            return "落幕";
            break;
        default :
            return "未知";
            break;
    }
}


/**
 * 格式化点击统计状态
 * @param state 状态
 * @return string
 */
function FormatOpenCount(state){
    switch (state){
        case "1":
            return "<"+"span style='color:green'>开启<"+"/span>";
            break;
        case "0":
            return "关闭";
            break;
        default :
            return "未知";
            break;
    }
}

/**
 * 格式化虚拟点击状态
 * @param state 状态
 * @return string
 */
function FormatVirtualClick(state){
    switch (state){
        case "1":
            return "<"+"span style='color:green'>开启<"+"/span>";
            break;
        case "0":
            return "关闭";
            break;
        default :
            return "未知";
            break;
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
    var after = 0;

    var today = new Date();

    var arr=endDate.split(" ");
    arr=arr[0].split("-");
    var site_year = arr[0];
    var site_month = arr[1]-1;
    var site_day = arr[2];

    var end=new Date(site_year,site_month,site_day)
    if(end>today)
        after=1;

    if(after == 1){
        document.write(endDate);
    }else{
        document.write('<font color=red>'+endDate+'</font>');

    }
}






