
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
 * 格式化站点名称
 * @param siteId 站点id
 * @param siteName 站点名称
 * @return string
 */
function FormatSiteName(siteId,siteName){
    if(siteId>0){
        return siteName;
    }else if(siteId==0){
        return "所有站点";
    }else{
        return "未知"
    }
}


/**
 * 格式化过滤类型
 * @param siteFilterType 过滤类型
 * @return string
 */
function FormatSiteFilterType(siteFilterType){
    switch (siteFilterType){
        case "0":
            return "替换";
            break;
        case "1":
            return "阻止";
            break;
        default :
            return "未知";
            break;
    }
}


/**
 * 格式化过滤范围
 * @param siteFilterArea 过滤范围
 * @return string
 */
function FormatSiteFilterArea(siteFilterArea){ //过滤范围 0:全局 1:资讯 2:活动 3:会员 4:评论
    switch (siteFilterArea){
        case "0":
            return "全局";
            break;
        case "1":
            return "资讯";
            break;
        case "2":
            return "活动";
            break;
        case "3":
            return "会员";
            break;
        case "4":
            return "评论";
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