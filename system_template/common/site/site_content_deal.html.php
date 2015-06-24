<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript" src="/system_js/xheditor-1.1.14/xheditor-1.1.14-zh-cn.min.js"></script>
    <script type="text/javascript" src="/system_js/ajax_file_upload.js"></script>
    <script type="text/javascript" src="/system_js/upload_file.js"></script>
    <script type="text/javascript">
        <!--
        var editor;
        var tableType = window.UPLOAD_TABLE_TYPE_SITE_CONTENT;
        var tableId = parseInt('{ChannelId}');

        //上传回调函数
        window.AjaxFileUploadCallBack = function(fileElementId,data){

            if(data.upload_file_watermark_path1 != null
                && data.upload_file_watermark_path1 != undefined
                && data.upload_file_watermark_path1.length>0
                && $("#cbAttachWatermark").is(":checked")
                ){
                //添加水印图到编辑控件中
                if(editor != undefined && editor != null){
                    editor.pasteHTML(""+UploadFileFormatHtml(data.upload_file_watermark_path1));
                }
            }else{
                //添加原图到编辑控件中
                if(editor != undefined && editor != null){
                    editor.pasteHTML(""+UploadFileFormatHtml(data.upload_file_path));
                }

            }

            var fUploadFile = $("#f_UploadFiles");

            if(fUploadFile != undefined && fUploadFile != null){
                var uploadFiles = fUploadFile.val();
                uploadFiles = uploadFiles + "," + data.upload_file_id;
                fUploadFile.val(uploadFiles);
            }
        }



        $(function () {

            var editorHeight = $(window).height() - 275;
            editorHeight = parseInt(editorHeight);

            var fSiteContentValue = $('#f_SiteContentValue');

            editor = fSiteContentValue.xheditor({
                tools: 'full',
                height: editorHeight,
                upImgUrl: "",
                upImgExt: "jpg,jpeg,gif,png",
                localUrlTest: /^https?:\/\/[^\/]*?({manage_domain_rex})\//i,
                remoteImgSaveUrl: ''
            });
            /******************    远程抓图    ********************/
            var cbSaveRemoteImage = $("#cbSaveRemoteImage");
            cbSaveRemoteImage.change(function () {
                if (cbSaveRemoteImage.prop("checked") == true) {

                    fSiteContentValue.xheditor(false);

                    editor = fSiteContentValue.xheditor({
                        tools: 'full',
                        height: editorHeight,
                        upImgUrl: "",
                        upImgExt: "jpg,jpeg,gif,png",
                        localUrlTest: /^https?:\/\/[^\/]*?({manage_domain_rex})\//i,
                        remoteImgSaveUrl: '/default.php?' +
                            'mod=upload_file' +
                            '&a=async_save_remote_image' +
                            '&table_type=' + tableType + '' +
                            '&table_id=' + tableId
                    });

                } else {

                    fSiteContentValue.xheditor(false);

                    editor = fSiteContentValue.xheditor({
                        tools: 'full',
                        height: editorHeight,
                        upImgUrl: "",
                        upImgExt: "jpg,jpeg,gif,png",
                        localUrlTest: /^https?:\/\/[^\/]*?({manage_domain_rex})\//i,
                        remoteImgSaveUrl: ''
                    });
                }
            });


            var btnUploadToContent = $("#btnUploadToContent");
            btnUploadToContent.click(function () {

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

            });

        });

        function submitForm(closeTab) {
            if ($('#f_SiteContentTitle').val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("请输入标题");
            } else {
                if (closeTab == 1) {
                    $("#CloseTab").val("1");
                } else {
                    $("#CloseTab").val("0");
                }
                $('#mainForm').submit();
            }
        }
        -->
    </script>
</head>
<body>
{common_body_deal}
<form id="mainForm" enctype="multipart/form-data"
      action="/default.php?secu=manage&mod=site_content&m={method}&channel_id={ChannelId}&site_content_id={SiteContentId}&tab_index={tab_index}"
      method="post">
<div>
<table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td class="spe_line" height="40" align="right">
            <input class="btn" value="确认并关闭" type="button" onclick="submitForm(1)"/>
            <input class="btn" value="确认并编辑" type="button" onclick="submitForm(2)"/>
            <input class="btn" value="确认并继续" type="button" onclick="submitForm(0)"/>
            <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
        </td>
    </tr>
</table>
<table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">

    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_SiteContentTitle">标题：</label></td>
        <td class="spe_line">
            <input name="f_SiteContentTitle" id="f_SiteContentTitle" value="{SiteContentTitle}" type="text" class="input_box" style="width:500px;"/>
            <input type="hidden" id="f_SiteId" name="f_SiteId" value="{SiteId}"/>
            <input type="hidden" id="f_ChannelId" name="f_ChannelId" value="{ChannelId}"/>
            <input type="hidden" id="f_UploadFiles" name="f_UploadFiles" value="{UploadFiles}"/>
        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_Sort">排序号：</label></td>
        <td class="spe_line">
            <input id="f_Sort" name="f_Sort" type="text" value="{Sort}" maxlength="10" class="input_number" style="width:100px;"/>
        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="f_SiteContentValue">内容：</label></td>
        <td class="spe_line">
            <textarea cols="30" rows="30" id="f_SiteContentValue" name="f_SiteContentValue" style="width:70%;height:250px;">{SiteContentValue}</textarea>
        </td>
    </tr>

    <tr>
        <td class="spe_line" height="30" align="right">文件上传：</td>
        <td class="spe_line">
            <input id="file_upload_to_content" name="file_upload_to_content" type="file"
                   class="input_box" size="7" style="width:60%; background: #ffffff;"/> <img
                id="loading" src="/system_template/common/images/loading1.gif"
                style="display:none;"/><input id="btnUploadToContent" type="button" value="上传"/>
        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="cbAttachWatermark">附加水印：</label></td>
        <td class="spe_line">
            <input type="checkbox" id="cbAttachWatermark" name="cbAttachWatermark"/> (只支持jpg或jpeg图片)
        </td>
    </tr>
    <tr>
        <td class="spe_line" height="30" align="right"><label for="cbSaveRemoteImage">远程抓图：</label></td>
        <td class="spe_line">
            <input type="checkbox" id="cbSaveRemoteImage" name="cbSaveRemoteImage"/>
            (只支持jpg、jpeg、gif、png图片)
        </td>
    </tr>
</table>

<table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td height="60" align="center">
            <input class="btn" value="确认并关闭" type="button" onclick="submitForm(1)"/>
            <input class="btn" value="确认并编辑" type="button" onclick="submitForm(2)"/>
            <input class="btn" value="确认并继续" type="button" onclick="submitForm(0)"/>
            <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
        </td>
    </tr>
</table>
</div>
</form>
</body>
</html>
