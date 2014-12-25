<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript" src="/system_js/xheditor-1.1.14/xheditor-1.1.14-zh-cn.min.js"></script>
    <script type="text/javascript" src="/system_js/color_picker.js"></script>
    <script type="text/javascript" src="/system_js/ajax_file_upload.js"></script>
    <script type="text/javascript" src="/system_js/upload_file.js"></script>

    <script type="text/javascript">
        var tableTypeOfSiteConfig = window.UPLOAD_TABLE_TYPE_SITE_CONFIG_PIC;
        var tableId = parseInt("{SiteId}");

        window.AjaxFileUploadCallBack = function(fileElementId,data){
            var uploadFileId =  data.upload_file_id;
            var uploadFilePath = data.upload_file_path;

            if(fileElementId == "file_newspaper_article_pic_watermark"){
                $( "#cfg_NewspaperArticlePicWatermarkUploadFileId_5" ).val(uploadFileId);
                $( "#preview_NewspaperArticlePicWatermarkUploadFileId").attr("src",uploadFilePath);
            }
        }

        $(function () {
            $('#tabs').tabs();

            //报纸文章附件上传的图片中的水印图
            var btnNewspaperArticlePicWatermarkUploadFileId = $("#btnNewspaperArticlePicWatermarkUploadFileId");
            btnNewspaperArticlePicWatermarkUploadFileId.click(function () {

                var fileElementId = 'file_newspaper_article_pic_watermark';
                var fUploadFile = null;
                var editor = null;
                var attachWatermark = 0;
                var loadingImageId = "loadingOfNewspaperArticlePicWatermarkUploadFileId";
                var inputTextId = null;
                var previewImageId = null;
                var uploadFileId = 0;


                AjaxFileUpload(
                    fileElementId,
                    tableTypeOfSiteConfig,
                    tableId,
                    loadingImageId,
                    $(this),
                    editor,
                    fUploadFile,
                    attachWatermark,
                    inputTextId,
                    previewImageId,
                    uploadFileId
                );
            });
        });

        function submitForm(closeTab) {

            if (closeTab == 1) {
                $("#CloseTab").val("1");
            } else {
                $("#CloseTab").val("0");
            }

            $("#mainForm").attr("action", "/default.php?secu=manage&mod=site_config&m=set&type=0&site_id={SiteId}&tab_index=" + parent.G_TabIndex + "");
            $('#mainForm').submit();

        }

    </script>
</head>
<body>
<div class="div_deal">
    <form id="mainForm" enctype="multipart/form-data" method="post">
        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="spe_line" height="40" align="right">
                    <input class="btn" value="确认并关闭" type="button" onclick="submitForm(1)"/>
                    <input class="btn" value="确认并继续" type="button" onclick="submitForm(0)"/>
                    <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                </td>
            </tr>
        </table>
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <div id="tabs">
                        <ul>
                            <li><a href="#tabs-1">基本设置</a></li>
                            <li><a href="#tabs-2">tabs-2</a></li>
                            <li><a href="#tabs-3">tabs-3</a></li>
                            <li><a href="#tabs-4">tabs-4</a></li>
                        </ul>
                        <div id="tabs-1">
                            <div style="">
                                <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">

                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_NewspaperArticlePicWatermarkUploadFileId_5">报纸文章附件上传的图片中的水印图：</label></td>
                                        <td class="spe_line">
                                            <img id="preview_NewspaperArticlePicWatermarkUploadFileId" src="{cfg_NewspaperArticlePicWatermarkUploadFileId_5_upload_file_path}" /><br/>
                                            <input id="file_newspaper_article_pic_watermark" name="file_newspaper_article_pic_watermark" type="file" class="input_box" style="width:200px; background: #ffffff;"/>
                                            <input id="cfg_NewspaperArticlePicWatermarkUploadFileId_5" name="cfg_NewspaperArticlePicWatermarkUploadFileId_5" type="hidden" value="{cfg_NewspaperArticlePicWatermarkUploadFileId_5}"/>
                                            <img id="loadingOfNewspaperArticlePicWatermarkUploadFileId" src="/system_template/common/images/loading1.gif" style="display:none;"/>
                                            <input id="btnNewspaperArticlePicWatermarkUploadFileId" type="button" value="上传"/>
                                        </td>
                                    </tr>

                                </table>
                            </div>
                        </div>

                        <div id="tabs-2">
                            <div>

                            </div>
                        </div>

                        <div id="tabs-3">
                            <div>

                            </div>
                        </div>

                        <div id="tabs-4">
                            <div>

                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td height="60" align="center">
                    <input class="btn" value="确认并关闭" type="button" onclick="submitForm(1)"/>
                    <input class="btn" value="确认并继续" type="button" onclick="submitForm(0)"/>
                    <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>
