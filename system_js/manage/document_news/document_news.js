/**
 * DocumentNews
 */



/**
 * 发布资讯详细页 返回值 资讯id小于0
 */
window.PUBLISH_DOCUMENT_NEWS_RESULT_DOCUMENT_NEWS_ID_ERROR = -130201;
/**
 * 发布资讯详细页 返回值 频道id小于0
 */
window.PUBLISH_DOCUMENT_NEWS_RESULT_CHANNEL_ID_ERROR = -130202;
/**
 * 发布资讯详细页 返回值 状态不正确，必须为终审或已发状态的文档才能发布
 */
window.PUBLISH_DOCUMENT_NEWS_RESULT_STATE_ERROR = -130203;
/**
 * 发布资讯详细页 返回值 没有发布此文档的权限
 */
window.PUBLISH_DOCUMENT_NEWS_RESULT_NO_RIGHT = -130204;
/**
 * 发布资讯详细页 返回值 操作完成，结果存储于结果数组中
 */
window.PUBLISH_DOCUMENT_NEWS_RESULT_FINISHED = 130201;



/**
 * 新稿 0
 */
window.DOCUMENT_NEWS_STATE_NEW = 0;
/**
 * 已编 1
 */
window.DOCUMENT_NEWS_STATE_MODIFY = 1;
/**
 * 返工 2
 */
window.DOCUMENT_NEWS_STATE_REDO = 2;
/**
 * 一审 11
 */
window.DOCUMENT_NEWS_STATE_FIRST_VERIFY = 11;
/**
 * 二审 12
 */
window.DOCUMENT_NEWS_STATE_SECOND_VERIFY = 12;
/**
 * 三审 13
 */
window.DOCUMENT_NEWS_STATE_THIRD_VERIFY = 13;
/**
 * 终审 14
 */
window.DOCUMENT_NEWS_STATE_FINAL_VERIFY = 14;
/**
 * 已否 20
 */
window.DOCUMENT_NEWS_STATE_REFUSE = 20;
/**
 * 已发 30
 */
window.DOCUMENT_NEWS_STATE_PUBLISHED = 30;
/**
 * 已删除 100
 */
window.DOCUMENT_NEWS_STATE_REMOVED = 100;


$(function() {
    //$(document).tooltip();
    $("#btn_select_all").click(function(event) {
        event.preventDefault();
        var inputSelect = $("[name='input_select']");
        if (inputSelect.prop("checked")) {
            inputSelect.prop("checked", false);//取消全选
        } else {
            inputSelect.prop("checked", true);//全选
        }
    });

    var btnCreate = $("#btn_create");
    btnCreate.css("cursor", "pointer");
    btnCreate.click(function(event) {
        event.preventDefault();
        var pageIndex = parseInt(Request["p"]);
        var channelId = parseInt($(this).attr('idvalue'));
        if (pageIndex == undefined || isNaN(pageIndex) || pageIndex <= 0) {
            pageIndex = 1;
        }
        //parent.G_TabUrl = '/default.php?secu=manage' +
        //    '&mod=document_news&m=create&p=' + pageIndex + '&channel_id=' + channelId;
        //parent.G_TabTitle = parent.G_SelectedChannelName + '-新增文档';
        //parent.addTab();
        window.location.href = '/default.php?secu=manage' +
            '&mod=document_news&m=create&tab_index='+ parent.G_TabIndex +'&p=' + pageIndex + '&channel_id=' + channelId;
    });

    var btnModify = $(".btn_modify");
    btnModify.css("cursor", "pointer");
    btnModify.click(function(event) {
        var documentNewsId = $(this).attr('idvalue');
        event.preventDefault();
        var pageIndex = parseInt(Request["p"]);
        if (pageIndex == undefined || isNaN(pageIndex) || pageIndex <= 0) {
            pageIndex = 1;
        }
        //parent.G_TabUrl = '/default.php?secu=manage&mod=document_news&m=modify&document_news_id='
        //    + documentNewsId + '&p=' + pageIndex + '&channel_id='
        //    + parent.G_SelectedChannelId;
        //parent.G_TabTitle = parent.G_SelectedChannelName + '-编辑文档';
        //parent.addTab();
        window.location.href = '/default.php?secu=manage&mod=document_news&m=modify&document_news_id='
            + documentNewsId + '&tab_index='+ parent.G_TabIndex +'&p=' + pageIndex + '&channel_id='
            + parent.G_SelectedChannelId;
    });

    var btnSearch = $("#btn_search");
    btnSearch.css("cursor", "pointer");
    btnSearch.click(function(event) {
        event.preventDefault();
        var pageIndex = parseInt(Request["p"]);
        var channelId = parseInt($(this).attr('idvalue'));
        if (pageIndex == undefined || isNaN(pageIndex) || pageIndex <= 0) {
            pageIndex = 1;
        }
        var searchKey = encodeURIComponent($("#search_key").val());
        if(searchKey.length<=0){
            alert("请输入查询关键字");
        }else{
            window.location.href = '/default.php?secu=manage' +
                '&mod=document_news&m=list&search_key='+searchKey+'&tab_index='+ parent.G_TabIndex +'&p=' + pageIndex + '&channel_id=' + channelId;
        }


    });



    $(".btn_close_box").click(function(event) {
        var documentNewsId = $(this).attr('idvalue');
        event.preventDefault();
        document.getElementById('div_state_box_' + documentNewsId).style.display = "none";
    });


    var btnPublish = $(".btn_publish");
    btnPublish.css("cursor", "pointer");
    btnPublish.click(function(event) {
        var documentNewsId = $(this).attr('idvalue');
        event.preventDefault();

        var dialogBox = $("#dialog_box");
        dialogBox.attr("title","发布文档");
        dialogBox.dialog({
            height: 140,
            modal: true
        });

        var dialogContent = $("#dialog_content");
        dialogContent.html("开始发布");

        $.post("/default.php?secu=manage&mod=document_news&m=async_publish&document_news_id=" + documentNewsId + "", {
            resultbox: $(this).html()
        }, function(result) {
            dialogContent.html('<img src="/system_template/common/images/spinner2.gif" /> 正在发布...');
            if (parseInt(result) == window.PUBLISH_DOCUMENT_NEWS_RESULT_DOCUMENT_NEWS_ID_ERROR) {
                dialogContent.html('资讯id小于0');
            }else if (parseInt(result) == window.PUBLISH_DOCUMENT_NEWS_RESULT_CHANNEL_ID_ERROR) {
                dialogContent.html('频道id小于0');
            }else if (parseInt(result) == window.PUBLISH_DOCUMENT_NEWS_RESULT_STATE_ERROR) {
                dialogContent.html('状态不正确，必须为[终审]或[已发]状态的文档才能发布!');
            }else if (parseInt(result) == window.PUBLISH_DOCUMENT_NEWS_RESULT_NO_RIGHT) {
                dialogContent.html('没有发布此文档的权限!');
            }else{
                dialogContent.html("发布完成<br />"+result);
                var spanState = $("#span_state_" + documentNewsId);
                spanState.html("<"+"span style='color:#006600'>已发<"+"/span>");
                //window.location.href = window.location.href;
            }
        });
    });


    //改变向上移动（排序）
    $(".btn_up").click(function(event) {
        var documentNewsId = $(this).attr('idvalue');
        event.preventDefault();
        $.post("/default.php?secu=manage&mod=document_news&m=async_modify_sort&document_news_id="+documentNewsId + "&sort=1", {
            resultbox: $(this).html()
        }, function(xml) {
            window.location.href = window.location.href;
        });
    });

    //改变向下移动（排序）
    $(".btn_down").click(function(event) {
        var documentNewsId = $(this).attr('idvalue');
        event.preventDefault();
        $.post("/default.php?secu=manage&mod=document_news&m=async_modify_sort&document_news_id=" + documentNewsId + "&sort=-1", {
            resultbox: $(this).html()
        }, function(xml) {
            window.location.href = window.location.href;
        });
    });

    //拖动排序变化
    var sortGrid = $("#sort_grid");
    sortGrid.sortable();
    sortGrid.bind("sortstop", function(event, ui) {
        var sortList = $("#sort_grid").sortable("serialize");
        $.post("/default.php?secu=manage&mod=document_news&m=async_modify_sort_by_drag&" + sortList, {
            resultbox: $(this).html()
        }, function() {
            //操作完成后触发的命令
        });
    });
    sortGrid.disableSelection();

    /****************** 顶部排序按钮 ***************************/

    var gridCanSort = $(".grid_can_sort");
    gridCanSort.css("cursor", "pointer");
    gridCanSort.mouseover(function(){
       $(this).attr("class","grid_can_sort_selected");
    });
    gridCanSort.mouseleave(function(){
        $(this).attr("class","grid_can_sort");
    });

    var btnSortDown = $("#btn_sort_down");
    var btnSortUp = $("#btn_sort_up");
    if(Request["sort"] == "up"){
        btnSortDown.css("display","none");
        btnSortUp.css("display","");
    }else{
        btnSortDown.css("display","");
        btnSortUp.css("display","none");
    }



    $("#btn_sort").click(function(){
        var url = window.location.href;
        url = url.replaceAll("&sort=up","");
        url = url.replaceAll("&sort=down","");
        url = url.replaceAll("&hit=up","");
        url = url.replaceAll("&hit=down","");

        var btnSortDown = $("#btn_sort_down");
        var btnSortUp = $("#btn_sort_up");
        if(btnSortDown.css("display") != "none"){ //当前是降序,改成升序
            btnSortDown.css("display","none");
            btnSortUp.css("display","");
            window.location.href = url + "&sort=up";
        }else{
            btnSortDown.css("display","");
            btnSortUp.css("display","none");
            window.location.href = url + "&sort=down";
        }
    });


    var btnSortByHitDown = $("#btn_sort_by_hit_down");
    var btnSortByHitUp = $("#btn_sort_by_hit_up");
    if(Request["hit"] == "up"){
        btnSortByHitDown.css("display","none");
        btnSortByHitUp.css("display","");
    }else{
        btnSortByHitDown.css("display","");
        btnSortByHitUp.css("display","none");
    }

    $("#btn_sort_by_hit").click(function(){

        var url = window.location.href;
        url = url.replaceAll("&sort=up","");
        url = url.replaceAll("&sort=down","");
        url = url.replaceAll("&hit=up","");
        url = url.replaceAll("&hit=down","");

        var btnSortByHitDown = $("#btn_sort_by_hit_down");
        var btnSortByHitUp = $("#btn_sort_by_hit_up");
        if(btnSortByHitDown.css("display") != "none"){ //当前是降序,改成升序
            btnSortByHitDown.css("display","none");
            btnSortByHitUp.css("display","");
            window.location.href = url + "&hit=up";
        }else{
            btnSortByHitDown.css("display","");
            btnSortByHitUp.css("display","none");
            window.location.href = url + "&hit=down";
        }
    });
    /*********************************************/

    //选中时的样式变化
    $('.grid_item').click(function() {
        if ($(this).hasClass('grid_item_selected')) {
            $(this).removeClass('grid_item_selected');
        } else {
            $(this).addClass('grid_item_selected');
        }
    });

    $(".span_state").each(function(){
        $(this).html(formatDocumentNewsState($(this).text()));
    });

    var documentNewsSetState = $(".document_news_set_state");
    documentNewsSetState.css("cursor", "pointer");
    documentNewsSetState.click(function() {
        var documentNewsId = $(this).attr('idvalue');
        var state = $(this).attr('statevalue');
        new documentNewsChangeState(documentNewsId, state);
    });

    $(".btn_modify_manage_remark").click(function(event){
        var documentNewsId = $(this).attr('idvalue');
        var manageRemark = $("#manage_remark_"+ documentNewsId);

        if(manageRemark.val() == ""){
            alert("请填写备注信息");
            //$("#dialog_box").dialog({width: 300, height: 100});
            //$("#dialog_content").html("请正确填写信息");
        }else{
            $.post("/default.php?secu=manage&mod=document_news&m=async_modify_manage_remark&document_news_id="+documentNewsId + "&manage_remark="+manageRemark.val()+"", {
                resultbox: $(this).html()
            }, function(xml) {
                if (parseInt(xml) > 0) {
                    alert("修改成功");
                    $("#remark_"+ documentNewsId).html(manageRemark.val());
                    $("#manage_remark_"+ documentNewsId).html("");
                }
                else{
                    alert("修改失败");
                }
            });
        }
    });
});

/**
 * 格式化状态值
 * @return {string}
 */
function formatDocumentNewsState(state){
    state = parseInt(state);
    switch (state){
        case window.DOCUMENT_NEWS_STATE_NEW:
            return "新稿";
            break;
        case window.DOCUMENT_NEWS_STATE_MODIFY:
            return "已编";
            break;
        case window.DOCUMENT_NEWS_STATE_REDO:
            return "返工";
            break;
        case window.DOCUMENT_NEWS_STATE_FIRST_VERIFY:
            return "一审";
            break;
        case window.DOCUMENT_NEWS_STATE_SECOND_VERIFY:
            return "二审";
            break;
        case window.DOCUMENT_NEWS_STATE_THIRD_VERIFY:
            return "三审";
            break;
        case window.DOCUMENT_NEWS_STATE_FINAL_VERIFY:
            return "终审";
            break;
        case window.DOCUMENT_NEWS_STATE_REFUSE:
            return "<"+"span style='color:#990000'>已否<"+"/span>";
            break;
        case window.DOCUMENT_NEWS_STATE_PUBLISHED:
            return "<"+"span style='color:#006600'>已发<"+"/span>";
            break;
        default :
            return "未知";
            break;
    }
}

/**
 *
 * @param {int} documentNewsId 资讯id
 * @param {int} state 状态
 * @constructor
 */
function documentNewsChangeState(documentNewsId, state) {
    $("#span_state" + documentNewsId).html("<img src='/system_template/common/images/loading1.gif' />");

    $.post("/default.php?secu=manage&mod=document_news&m=async_modify_state&document_news_id=" + documentNewsId + "&state=" + state, {
        resultbox: $(this).html()
    }, function(xml) {
        if (parseInt(xml) > 0) {
            state = parseInt(state);

            var spanState = $("#span_state_" + documentNewsId);
            switch (state)
            {
                case window.DOCUMENT_NEWS_STATE_NEW:
                    spanState.html("新稿");
                    break;
                case window.DOCUMENT_NEWS_STATE_MODIFY:
                    spanState.html("已编");
                    break;
                case window.DOCUMENT_NEWS_STATE_REDO:
                    spanState.html("返工");
                    break;
                case window.DOCUMENT_NEWS_STATE_FIRST_VERIFY:
                    spanState.html("一审");
                    break;
                case window.DOCUMENT_NEWS_STATE_SECOND_VERIFY:
                    spanState.html("二审");
                    break;
                case window.DOCUMENT_NEWS_STATE_THIRD_VERIFY:
                    spanState.html("三审");
                    break;
                case window.DOCUMENT_NEWS_STATE_FINAL_VERIFY:
                    spanState.html("终审");
                    break;
                case window.DOCUMENT_NEWS_STATE_REFUSE:
                    spanState.html("<span style='color:#990000'>已否</span>");
                    break;
                default:
                    spanState.html("未知");
                    break;
            }
        } else if (parseInt(xml) == -2) {
            alert("设置失败，您尚未开通操作此功能的权限，如需开通此权限，请联系管理人员！");
        }
        else {
            alert("设置失败");
        }
    });
    //document.getElementById('div_state_box_' + documentNewsId).style.display = "none";
}







