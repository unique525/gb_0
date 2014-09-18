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
 * 发布资讯详细页 返回值 操作完成，结果存储于结果数组中
 */
window.PUBLISH_DOCUMENT_NEWS_RESULT_FINISHED = 130201;



/**
 * 新稿
 */
window.DOCUMENT_NEWS_STATE_NEW = 0;
/**
 * 已编
 */
window.DOCUMENT_NEWS_STATE_MODIFY = 1;
/**
 * 返工
 */
window.DOCUMENT_NEWS_STATE_REDO = 2;
/**
 * 一审
 */
window.DOCUMENT_NEWS_STATE_FIRST_VERIFY = 11;
/**
 * 二审
 */
window.DOCUMENT_NEWS_STATE_SECOND_VERIFY = 12;
/**
 * 三审
 */
window.DOCUMENT_NEWS_STATE_THIRD_VERIFY = 13;
/**
 * 终审
 */
window.DOCUMENT_NEWS_STATE_FINAL_VERIFY = 14;
/**
 * 已否
 */
window.DOCUMENT_NEWS_STATE_REFUSE = 20;
/**
 * 已发
 */
window.DOCUMENT_NEWS_STATE_PUBLISHED = 30;


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
        if (pageIndex <= 0) {
            pageIndex = 1;
        }
        parent.G_TabUrl = '/default.php?secu=manage&mod=document_news&m=create&p=' + pageIndex + '&channel_id=' + channelId;
        parent.G_TabTitle = parent.G_SelectedChannelName + '-新增文档';
        parent.addTab();
    });

    var btnModify = $(".btn_modify");
    btnModify.css("cursor", "pointer");
    btnModify.click(function(event) {
        var documentNewsId = $(this).attr('idvalue');
        event.preventDefault();
        var pageIndex = parseInt(Request["p"]);
        if (pageIndex <= 0) {
            pageIndex = 1;
        }
        parent.G_TabUrl = '/default.php?secu=manage&mod=document_news&m=modify&document_news_id=' + documentNewsId + '&p=' + pageIndex + '&channel_id=' + parent.G_SelectedChannelId;
        parent.G_TabTitle = parent.G_SelectedChannelName + '-编辑文档';
        parent.addTab();
    });

    //改变状态按钮事件捕获
    var btnChangeState = $(".btn_change_state");
    btnChangeState.css("cursor", "pointer");
    btnChangeState.click(function(event) {
        var documentNewsId = $(this).attr('idvalue');
        event.preventDefault();
        ShowBox('div_state_box_' + documentNewsId);
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

        $.post("/default.php?secu=manage&mod=document_news&m=publish&document_news_id=" + documentNewsId + "", {
            resultbox: $(this).html()
        }, function(result) {
            dialogContent.html('<img src="/system_template/common/images/spinner2.gif" /> 正在发布...');
            if (parseInt(result) == window.PUBLISH_DOCUMENT_NEWS_RESULT_FINISHED) {
                dialogContent.html('发布完成');
            }else if (parseInt(result) == window.PUBLISH_DOCUMENT_NEWS_RESULT_DOCUMENT_NEWS_ID_ERROR) {
                dialogContent.html('资讯id小于0');
            }else if (parseInt(result) == window.PUBLISH_DOCUMENT_NEWS_RESULT_CHANNEL_ID_ERROR) {
                dialogContent.html('频道id小于0');
            }else {
                dialogContent.html('未知结果');
            }
        });
    });


    //排序变化
    $("#sortgrid").sortable();
    $("#sortgrid").bind("sortstop", function(event, ui) {
        var sortlist = $("#sortgrid").sortable("serialize");
        $.post("/default.php?secu=manage&mod=documentnews&m=async_modifysort&" + sortlist, {
            resultbox: $(this).html()
        }, function() {
            //操作完成后触发的命令
        });
    });
    $("#sortgrid").disableSelection();

    //选中时的样式变化
    $('.grid_item').click(function() {
        if ($(this).hasClass('grid_item_selected')) {
            $(this).removeClass('grid_item_selected');
        } else {
            $(this).addClass('grid_item_selected');
        }
    });

    $(".span_state").each(function(){
        $(this).text(formatDocumentNewsState($(this).text()));
    });

    var documentNewsSetState = $(".document_news_set_state");
    documentNewsSetState.css("cursor", "pointer");
    documentNewsSetState.click(function() {
        var documentNewsId = $(this).attr('idvalue');
        var state = $(this).attr('statevalue');
        documentNewsChangeState(documentNewsId, state);
    });
});

/**
 * 格式化状态值
 * @return {string}
 */
function formatDocumentNewsState(state){
    switch (state){
        case "0":
            return "新稿";
            break;
        case "1":
            return "已编";
            break;
        case "2":
            return "返工";
            break;
        case "11":
            return "一审";
            break;
        case "12":
            return "二审";
            break;
        case "13":
            return "三审";
            break;
        case "14":
            return "终审";
            break;
        case "20":
            return "<"+"span style='color:#990000'>已否<"+"/span>";
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
            var spanState = $("#span_state_" + documentNewsId);
            switch (state)
            {
                case 0:
                    spanState.text("新稿");
                    break;
                case 1:
                    spanState.text("已编");
                    break;
                case 2:
                    spanState.text("返工");
                    break;
                case 11:
                    spanState.text("一审");
                    break;
                case 12:
                    spanState.text("二审");
                    break;
                case 13:
                    spanState.text("三审");
                    break;
                case 14:
                    spanState.text("终审");
                    break;
                case 20:
                    spanState.html("<span style='color:#990000'>已否</span>");
                    break;
                default:
                    spanState.text("错误");
                    break;
            }
        } else if (parseInt(xml) == -2) {
            alert("设置失败，您尚未开通操作此功能的权限，如需开通此权限，请联系管理人员！");
        }
        else {
            alert("设置失败");
        }
    });
    document.getElementById('div_state_box_' + documentNewsId).style.display = "none";
}







