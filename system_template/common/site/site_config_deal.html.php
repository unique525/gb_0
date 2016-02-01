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
            if(fileElementId == "file_newspaper_article_pic_watermark"){
                var uploadFileId =  data.upload_file_id;
                var uploadFilePath = data.upload_file_path;
                $( "#cfg_NewspaperArticlePicWatermarkUploadFileId" ).val(uploadFileId);
                $( "#preview_NewspaperArticlePicWatermarkUploadFileId").attr("src",uploadFilePath);
            }
            else if(fileElementId == "file_document_news_content_pic_watermark"){
                var uploadFileId =  data.upload_file_id;
                var uploadFilePath = data.upload_file_path;
                $( "#cfg_DocumentNewsContentPicWatermarkUploadFileId" ).val(uploadFileId);
                $( "#preview_DocumentNewsContentPicWatermarkUploadFileId").attr("src",uploadFilePath);
            }
        };

        window.GetOneUploadFileCallBack = function(fileElementId,uploadFileId, data){
            if(data["upload_file_path"] != ""){

                $("#"+fileElementId).attr("src",data["upload_file_path"]);

            }
        };

        $(function () {
            $('#tabs').tabs();

            //加载已经上传的图片的预览图
            //upload_file.js
            var newspaperArticlePicWatermarkUploadFileId = parseInt("{cfg_NewspaperArticlePicWatermarkUploadFileId}");
            if(newspaperArticlePicWatermarkUploadFileId>0){
                GetOneUploadFile('preview_NewspaperArticlePicWatermarkUploadFileId',newspaperArticlePicWatermarkUploadFileId);
            }

            var documentNewsContentPicWatermarkUploadFileId = parseInt("{cfg_DocumentNewsContentPicWatermarkUploadFileId}");
            if(documentNewsContentPicWatermarkUploadFileId>0){
                GetOneUploadFile('preview_DocumentNewsContentPicWatermarkUploadFileId',documentNewsContentPicWatermarkUploadFileId);
            }


            //报纸文章附件上传的图片中的水印图
            var btnNewspaperArticlePicWatermarkUploadFileId = $("#btnNewspaperArticlePicWatermarkUploadFileId");
            btnNewspaperArticlePicWatermarkUploadFileId.click(function () {

                var fileElementId = 'file_newspaper_article_pic_watermark';
                var attachWatermark = 0;
                var loadingImageId = "loadingOfNewspaperArticlePicWatermarkUploadFileId";
                var uploadFileId = 0;


                AjaxFileUpload(
                    fileElementId,
                    tableTypeOfSiteConfig,
                    tableId,
                    loadingImageId,
                    $(this),
                    attachWatermark,
                    uploadFileId
                );
            });


            //资讯内容上传的图片中的水印图
            var btnDocumentNewsContentPicWatermarkUploadFileId = $("#btnDocumentNewsContentPicWatermarkUploadFileId");
            btnDocumentNewsContentPicWatermarkUploadFileId.click(function () {

                var fileElementId = 'file_document_news_content_pic_watermark';
                var attachWatermark = 0;
                var loadingImageId = "loadingOfDocumentNewsContentPicWatermarkUploadFileId";
                var uploadFileId = 0;


                AjaxFileUpload(
                    fileElementId,
                    tableTypeOfSiteConfig,
                    tableId,
                    loadingImageId,
                    $(this),
                    attachWatermark,
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
                            <li><a href="#tabs-2">产品设置</a></li>
                            <li><a href="#tabs-3">资讯设置</a></li>
                            <li><a href="#tabs-4">电子报设置</a></li>
                            <li><a href="#tabs-5">第三方接口设置</a></li>
                            <li><a href="#tabs-6">邮件参数设置</a></li>
                        </ul>
                        <div id="tabs-1">
                            <div style="">
                                <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">

                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                        </td>
                                        <td class="spe_line">
                                        </td>
                                    </tr>
                                </table>
                            </div>

                        </div>

                        <div id="tabs-2">
                            <div>
                                <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_ProductTitlePic1MobileWidth">手机客户端产品题图1的同比缩小宽度值：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_ProductTitlePic1MobileWidth" type="text" class="input_number" name="cfg_ProductTitlePic1MobileWidth" value="{cfg_ProductTitlePic1MobileWidth}"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_ProductTitlePic1PadWidth">平板客户端产品题图1的同比缩小宽度值：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_ProductTitlePic1PadWidth" type="text" class="input_number" name="cfg_ProductTitlePic1PadWidth" value="{cfg_ProductTitlePic1PadWidth}"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_ProductPicMobileWidth">手机客户端产品图片的同比缩小宽度值：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_ProductPicMobileWidth" type="text" class="input_number" name="cfg_ProductPicMobileWidth" value="{cfg_ProductPicMobileWidth}"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_ProductPicPadWidth">平板客户端产品图片的同比缩小宽度值：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_ProductPicPadWidth" type="text" class="input_number" name="cfg_ProductPicPadWidth" value="{cfg_ProductPicPadWidth}"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_ProductPicThumb1Width">产品图片的缩略图1宽度值：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_ProductPicThumb1Width" type="text" class="input_number" name="cfg_ProductPicThumb1Width" value="{cfg_ProductPicThumb1Width}"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_ProductPicThumb2Width">产品图片的缩略图2宽度值：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_ProductPicThumb2Width" type="text" class="input_number" name="cfg_ProductPicThumb2Width" value="{cfg_ProductPicThumb2Width}"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_ProductPicThumb3Width">产品图片的缩略图3宽度值：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_ProductPicThumb3Width" type="text" class="input_number" name="cfg_ProductPicThumb3Width" value="{cfg_ProductPicThumb3Width}"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_ProductPicWatermark1Width">产品图片的水印图1宽度值：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_ProductPicWatermark1Width" type="text" class="input_number" name="cfg_ProductPicWatermark1Width" value="{cfg_ProductPicWatermark1Width}"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_ProductPicWatermark2Width">产品图片的水印图2宽度值：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_ProductPicWatermark2Width" type="text" class="input_number" name="cfg_ProductPicWatermark2Width" value="{cfg_ProductPicWatermark2Width}"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_ProductPicCompress1Width">产品图片的压缩图1宽度值：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_ProductPicCompress1Width" type="text" class="input_number" name="cfg_ProductPicCompress1Width" value="{cfg_ProductPicCompress1Width}"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_ProductPicCompress2Width">产品图片的压缩图2宽度值：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_ProductPicCompress2Width" type="text" class="input_number" name="cfg_ProductPicCompress2Width" value="{cfg_ProductPicCompress2Width}"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_UserOrderFirstSubPrice">会员订单第一次下单优惠的金额：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_UserOrderFirstSubPrice" type="text" class="input_number" name="cfg_UserOrderFirstSubPrice" value="{cfg_UserOrderFirstSubPrice}"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_ProductSendPriceMode">发货费用模式：</label>
                                        </td>
                                        <td class="spe_line">
                                            <select id="cfg_ProductSendPriceMode" name="cfg_ProductSendPriceMode">
                                                <option value="0">全场免费</option>
                                                <option value="1">达到某金额免费</option>
                                                <option value="2">所有运费累加，并计算续重费，然后客服手动修改运费</option>
                                                <option value="3">取最高的运费，并计算最高项的续重费</option>
                                            </select>
                                            {sel_ProductSendPriceMode}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_ProductSendPriceFreeLimit">发货费用模式（1）,达到某金额免费：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_ProductSendPriceFreeLimit" type="text" class="input_price" name="cfg_ProductSendPriceFreeLimit" value="{cfg_ProductSendPriceFreeLimit}"/>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div id="tabs-3">
                            <div>
                                <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_DocumentNewsContentPicWatermarkUploadFileId">资讯内容上传的图片中的水印图：</label></td>
                                        <td class="spe_line">
                                            <img id="preview_DocumentNewsContentPicWatermarkUploadFileId" src="{cfg_DocumentNewsContentPicWatermarkUploadFileId_upload_file_path}" /><br/>
                                            <input id="file_document_news_content_pic_watermark" name="file_document_news_content_pic_watermark" type="file" class="input_box" style="width:200px; background: #ffffff;"/>
                                            <input id="cfg_DocumentNewsContentPicWatermarkUploadFileId" name="cfg_DocumentNewsContentPicWatermarkUploadFileId" type="hidden" value="{cfg_DocumentNewsContentPicWatermarkUploadFileId}"/>
                                            <img id="loadingOfDocumentNewsContentPicWatermarkUploadFileId" src="/system_template/common/images/loading1.gif" style="display:none;"/>
                                            <input id="btnDocumentNewsContentPicWatermarkUploadFileId" type="button" value="上传"/>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_DocumentNewsTitlePic1MobileWidth">适配手机客户端，资讯题图1的同比缩小宽度值：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_DocumentNewsTitlePic1MobileWidth" type="text" class="input_number" name="cfg_DocumentNewsTitlePic1MobileWidth" value="{cfg_DocumentNewsTitlePic1MobileWidth}"/> （0为不处理）
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_DocumentNewsTitlePic1PadWidth">适配平板客户端，资讯题图1的同比缩小宽度值：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_DocumentNewsTitlePic1PadWidth" type="text" class="input_number" name="cfg_DocumentNewsTitlePic1PadWidth" value="{cfg_DocumentNewsTitlePic1PadWidth}"/> （0为不处理）
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_DocumentNewsTitlePic2MobileWidth">适配手机客户端，资讯题图2的同比缩小宽度值：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_DocumentNewsTitlePic2MobileWidth" type="text" class="input_number" name="cfg_DocumentNewsTitlePic2MobileWidth" value="{cfg_DocumentNewsTitlePic2MobileWidth}"/> （0为不处理）
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_DocumentNewsTitlePic2PadWidth">适配平板客户端，资讯题图2的同比缩小宽度值：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_DocumentNewsTitlePic2PadWidth" type="text" class="input_number" name="cfg_DocumentNewsTitlePic2PadWidth" value="{cfg_DocumentNewsTitlePic2PadWidth}"/> （0为不处理）
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_DocumentNewsTitlePic3MobileWidth">适配手机客户端，资讯题图3的同比缩小宽度值：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_DocumentNewsTitlePic3MobileWidth" type="text" class="input_number" name="cfg_DocumentNewsTitlePic3MobileWidth" value="{cfg_DocumentNewsTitlePic3MobileWidth}"/> （0为不处理）
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_DocumentNewsTitlePic3PadWidth">适配平板客户端，资讯题图3的同比缩小宽度值：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_DocumentNewsTitlePic3PadWidth" type="text" class="input_number" name="cfg_DocumentNewsTitlePic3PadWidth" value="{cfg_DocumentNewsTitlePic3PadWidth}"/> （0为不处理）
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_DocumentNewsTitlePic1CompressWidth">资讯题图1压缩图宽度值：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_DocumentNewsTitlePic1CompressWidth" type="text" class="input_number" name="cfg_DocumentNewsTitlePic1CompressWidth" value="{cfg_DocumentNewsTitlePic1CompressWidth}"/> （0为不处理）
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_DocumentNewsTitlePic1CompressHeight">资讯题图1压缩图高度值：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_DocumentNewsTitlePic1CompressHeight" type="text" class="input_number" name="cfg_DocumentNewsTitlePic1CompressHeight" value="{cfg_DocumentNewsTitlePic1CompressHeight}"/> （0为不处理）
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_DocumentNewsTitlePic2CompressWidth">资讯题图2压缩图宽度值：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_DocumentNewsTitlePic2CompressWidth" type="text" class="input_number" name="cfg_DocumentNewsTitlePic2CompressWidth" value="{cfg_DocumentNewsTitlePic2CompressWidth}"/> （0为不处理）
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_DocumentNewsTitlePic2CompressHeight">资讯题图2压缩图高度值：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_DocumentNewsTitlePic2CompressHeight" type="text" class="input_number" name="cfg_DocumentNewsTitlePic2CompressHeight" value="{cfg_DocumentNewsTitlePic2CompressHeight}"/> （0为不处理）
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div id="tabs-4">
                            <div>
                                <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_NewspaperArticlePicWatermarkUploadFileId">报纸文章附件上传的图片中的水印图：</label></td>
                                        <td class="spe_line">
                                            <img id="preview_NewspaperArticlePicWatermarkUploadFileId" src="{cfg_NewspaperArticlePicWatermarkUploadFileId_upload_file_path}" /><br/>
                                            <input id="file_newspaper_article_pic_watermark" name="file_newspaper_article_pic_watermark" type="file" class="input_box" style="width:200px; background: #ffffff;"/>
                                            <input id="cfg_NewspaperArticlePicWatermarkUploadFileId" name="cfg_NewspaperArticlePicWatermarkUploadFileId" type="hidden"
                                                   value="{cfg_NewspaperArticlePicWatermarkUploadFileId}"/>
                                            <img id="loadingOfNewspaperArticlePicWatermarkUploadFileId" src="/system_template/common/images/loading1.gif" style="display:none;"/>
                                            <input id="btnNewspaperArticlePicWatermarkUploadFileId" type="button" value="上传"/>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div id="tabs-5">
                            <div>
                                <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">


                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_WeiXinAppId">微信 AppId ：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_WeiXinAppId" type="text" class="input_box" style="width:300px;" name="cfg_WeiXinAppId" value="{cfg_WeiXinAppId}"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_WeiXinAppSecret">微信 AppSecret：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_WeiXinAppSecret" type="text" class="input_box" style="width:300px;" name="cfg_WeiXinAppSecret" value="{cfg_WeiXinAppSecret}"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_WeiXinAccessToken">微信 AccessToken：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_WeiXinAccessToken" type="text" class="input_box" style="width:300px;" name="cfg_WeiXinAccessToken" value="{cfg_WeiXinAccessToken}"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_WeiXinAccessTokenGetTime">微信 AccessTokenGetTime：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_WeiXinAccessTokenGetTime" type="text" class="input_box" style="width:300px;" name="cfg_WeiXinAccessTokenGetTime" value="{cfg_WeiXinAccessTokenGetTime}"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_WeiXinRefreshToken">微信 RefreshToken：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_WeiXinRefreshToken" type="text" class="input_box" style="width:300px;" name="cfg_WeiXinRefreshToken" value="{cfg_WeiXinRefreshToken}"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_WeiXinRefreshTokenGetTime">微信 RefreshTokenGetTime：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_WeiXinRefreshTokenGetTime" type="text" class="input_box" style="width:300px;" name="cfg_WeiXinRefreshTokenGetTime" value="{cfg_WeiXinRefreshTokenGetTime}"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_PayAlipaySellerEmail">支付宝 合作者帐号：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_PayAlipaySellerEmail" type="text" class="input_box" style="width:300px;" name="cfg_PayAlipaySellerEmail" value="{cfg_PayAlipaySellerEmail}"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_PayAlipayPartnerID">支付宝 合作者ID(PartnerID)：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_PayAlipayPartnerID" type="text" class="input_box" style="width:300px;" name="cfg_PayAlipayPartnerID" value="{cfg_PayAlipayPartnerID}"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_PayAlipayKey">支付宝 合作者Key：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_PayAlipayKey" type="text" class="input_box" style="width:300px;" name="cfg_PayAlipayKey" value="{cfg_PayAlipayKey}"/>
                                        </td>
                                    </tr>


                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_MobSmsCheckUrl">MOB的短信验证请求网址：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_MobSmsCheckUrl" type="text" class="input_box" style="width:300px;" name="cfg_MobSmsCheckUrl" value="{cfg_MobSmsCheckUrl}"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_MobAppKey">MOB的APP KEY：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_MobAppKey" type="text" class="input_box" style="width:300px;" name="cfg_MobAppKey" value="{cfg_MobAppKey}"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="40" align="right">
                                            <label for="cfg_MobAppSecret">MOB的APP SECRET：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input id="cfg_MobAppSecret" type="text" class="input_box" style="width:300px;" name="cfg_MobAppSecret" value="{cfg_MobAppSecret}"/>
                                        </td>
                                    </tr>



                                </table>
                            </div>
                        </div>
                        <div id="tabs-6">
                            <div>
                                <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="spe_line" height="30" align="right">
                                            <label for="cfg_MailSmtpHost">SMTP服务器地址：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input name="cfg_MailSmtpHost" id="cfg_MailSmtpHost" value="{cfg_MailSmtpHost}" maxlength="200" type="text" class="input_box" style=" width: 500px;" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="30" align="right">
                                            <label for="cfg_MailSmtpUserName">SMTP服务器用户名：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input name="cfg_MailSmtpUserName" id="cfg_MailSmtpUserName" value="{cfg_MailSmtpUserName}" maxlength="200" type="text" class="input_box" style=" width: 500px;" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="30" align="right">
                                            <label for="cfg_MailSmtpPassword">SMTP服务器密码：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input name="cfg_MailSmtpPassword" id="cfg_MailSmtpPassword" value="{cfg_MailSmtpPassword}" maxlength="200" type="text" class="input_box" style=" width: 500px;" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="30" align="right">
                                            <label for="cfg_MailSmtpPort">SMTP服务器的端口号：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input name="cfg_MailSmtpPort" id="cfg_MailSmtpPort" value="{cfg_MailSmtpPort}" maxlength="8" type="text" class="input_number" style=" width: 100px;" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="30" align="right">
                                            <label for="cfg_MailFrom">发件人地址：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input name="cfg_MailFrom" id="cfg_MailFrom" value="{cfg_MailFrom}" maxlength="200" type="text" class="input_box" style=" width: 500px;" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spe_line" height="30" align="right">
                                            <label for="cfg_MailReplyTo">邮件回复地址：</label>
                                        </td>
                                        <td class="spe_line">
                                            <input name="cfg_MailReplyTo" id="cfg_MailReplyTo" value="{cfg_MailReplyTo}" maxlength="200" type="text" class="input_box" style=" width: 500px;" />
                                        </td>
                                    </tr>
                                </table>
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
