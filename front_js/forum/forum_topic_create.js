/**
 * Created by zhangchi on 15-10-27.
 */
$(function () {

    if ($.browser.msie) {
        $('input:checkbox').click(function () {
            this.blur();
            this.focus();
        });
    }


    /************** title **************/
    var forumTopicTitle = $("#f_ForumTopicTitle");

    var forumTopicId = Request["forum_topic_id"];

    if (forumTopicId == undefined || forumTopicId <= 0) {
        if (forumTopicTitle.val() == '{ForumTopicTitle}' || forumTopicTitle.val() == '') {
            forumTopicTitle.val("标题");
            forumTopicTitle.css("color", "#999999");
        }
        forumTopicTitle.focus(function () {
            if (forumTopicTitle.val() == '标题') {
                forumTopicTitle.val("");
            }
        });
    }


    var editorHeight = $(window).height() - 320;
    editorHeight = parseInt(editorHeight);

    var f_ForumPostContent = $('#f_ForumPostContent');
    editor = f_ForumPostContent.xheditor({
        tools: 'full',
        height: editorHeight,
        upImgUrl: "",
        upImgExt: "jpg,jpeg,gif,png",
        localUrlTest: /^https?:\/\/[^\/]*?(localhost)\//i,
        remoteImgSaveUrl: ''

    });


    $('#tabs').tabs();

///////////////////加粗/////////////////////////
    var cbTitleBold = $("#cbTitleBold");
    cbTitleBold.click(function () {
        ChangeToBold();
    });
//
//加载BOLD
    var bold = $("#f_TitleBold").val();
    if (bold == "bold") {
        cbTitleBold.attr('checked', true);
        ChangeToBold();
    }

///////////////////
//加载TITLE COLOR
    var titleColor = $("#f_TitleColor").val();
    if (titleColor.length >= 3) {
        forumTopicTitle.css("color", titleColor);
    }

    var cbSaveRemoteImage = $("#cbSaveRemoteImage");
    cbSaveRemoteImage.change(function () {
        if (cbSaveRemoteImage.prop("checked") == true) {

            f_ForumPostContent.xheditor(false);

            editor = f_ForumPostContent.xheditor({
                tools: 'full',
                height: editorHeight,
                upImgUrl: "",
                upImgExt: "jpg,jpeg,gif,png",
                localUrlTest: /^https?:\/\/[^\/]*?(localhost)\//i,
                remoteImgSaveUrl: '/default.php?mod=upload_file&a=async_save_remote_image&table_type=' + tableType + '&table_id=' + tableId
            });

        } else {

            f_ForumPostContent.xheditor(false);

            editor = f_ForumPostContent.xheditor({
                tools: 'full',
                height: editorHeight,
                upImgUrl: "",
                upImgExt: "jpg,jpeg,gif,png",
                localUrlTest: /^https?:\/\/[^\/]*?(localhost)\//i,
                remoteImgSaveUrl: ''
            });
        }
    });


});

function uploadToContent(tableType, tableId) {
    var fileElementId = 'file_upload_to_content';

    var attachWatermark = 0;
    if ($("#cbAttachWatermark").is(":checked")) {
        attachWatermark = 1;
    }
    var loadingImageId = null;
    var uploadFileId = 0;

    AjaxFileUpload(
        fileElementId,
        tableType,
        tableId,
        loadingImageId,
        $(this),
        attachWatermark,
        uploadFileId
    );
}

function confirm(action, forumId, forumTopicId) {
    if (forumTopicId == undefined || forumTopicId <= 0) {
        var forumTopicTitle = $("#f_ForumTopicTitle");
        if (forumTopicTitle.val() == ''
            || forumTopicTitle.val() == '{ForumTopicTitle}'
            || forumTopicTitle.val() == '标题'
            ) {
            $("#dialog_box").dialog({width: 300, height: 100});
            $("#dialog_content").html("请输入标题");
        } else {

            $("#mainForm").attr("action",
                "/default.php?mod=forum_topic&a=" + action + "&forum_id=" + forumId + "&forum_topic_id=" + forumTopicId);
            $('#mainForm').submit();
        }
    }
    else {

        $("#mainForm").attr("action",
            "/default.php?mod=forum_topic&a=" + action + "&forum_id=" + forumId + "&forum_topic_id=" + forumTopicId);
        $('#mainForm').submit();
    }
}

function ChangeToBold() {

    if ($("#cbTitleBold").prop('checked') == true) {
        $("#f_TitleBold").val("bold");
        $("#f_ForumTopicTitle").css("font-weight", "bold");
    } else {
        $("#f_TitleBold").val("normal");
        $("#f_ForumTopicTitle").css("font-weight", "normal");
    }

}