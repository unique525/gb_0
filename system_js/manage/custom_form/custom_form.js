/**
 * Created by 525 on 14-5-12.
 */


$("document").ready(function(){


    //选中时的样式变化
    $('.grid_item').click(function() {
        if ($(this).hasClass('grid_item_selected')) {
            $(this).removeClass('grid_item_selected');
        } else {
            $(this).addClass('grid_item_selected');
        }
    });


    $("#btn_select_all").click(function(event) {
        event.preventDefault();
        var inputSelect = $("[name='docinput']");
        if (inputSelect.prop("checked")) {
            inputSelect.prop("checked", false);//取消全选
        } else {
            inputSelect.prop("checked", true);//全选
        }
    });

    var btnEdit = $(".btn_edit");
    btnEdit.css("cursor", "pointer");
    btnEdit.click(function(event) {
        var customFormId = $(this).attr('idvalue');
        event.preventDefault();
        var pageIndex = parseInt(Request["p"]);
        if (pageIndex <= 0||!pageIndex) {
            pageIndex = 1;
        }
        parent.G_TabUrl = '/default.php?secu=manage&mod=custom_form&m=modify&custom_form_id=' + customFormId + '&p=' + pageIndex + '&channel_id=' + parent.G_SelectedChannelId;
        parent.G_TabTitle = parent.G_SelectedChannelName + '-编辑表单';
        parent.addTab();
    });


    var btnOpenCustomFormField = $(".btn_open_custom_form_field");
    btnOpenCustomFormField.css("cursor", "pointer");
    btnOpenCustomFormField.click(function(event) {
        var customFormId = $(this).attr('idvalue');
        event.preventDefault();
        var pageIndex = parseInt(Request["p"]);
        if (pageIndex <= 0||!pageIndex) {
            pageIndex = 1;
        }
        parent.G_TabUrl = '/default.php?secu=manage&mod=custom_form_field&m=modify&custom_form_id=' + customFormId + '&p=' + pageIndex + '&channel_id=' + parent.G_SelectedChannelId;
        parent.G_TabTitle = parent.G_SelectedChannelName + '-管理字段';
        parent.addTab();
    });


    var btnOpenCustomFormRecord = $(".btn_open_custom_form_record");
    btnOpenCustomFormRecord.css("cursor", "pointer");
    btnOpenCustomFormRecord.click(function(event) {
        var customFormId = $(this).attr('idvalue');
        event.preventDefault();
        var pageIndex = parseInt(Request["p"]);
        if (pageIndex <= 0||!pageIndex) {
            pageIndex = 1;
        }
        parent.G_TabUrl = '/default.php?secu=manage&mod=custom_form_record&m=list&custom_form_id=' + customFormId + '&p=' + pageIndex + '&channel_id=' + parent.G_SelectedChannelId;
        parent.G_TabTitle = parent.G_SelectedChannelName + '-查看数据';
        parent.addTab();
    });

    $("#btn_create").click(function(event) {
        event.preventDefault();
        parent.G_TabUrl = '/default.php?secu=manage&mod=custom_form&m=create' + '&channel_id=' +  parent.G_SelectedChannelId;
        parent.G_TabTitle = parent.G_SelectedChannelName + '-新建表单';
        parent.addTab();
    });
});