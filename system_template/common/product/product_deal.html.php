<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}

    <script type="text/javascript" src="/system_js/json.js"></script>
    <script type="text/javascript" src="/system_js/xheditor-1.1.14/xheditor-1.1.14-zh-cn.min.js"></script>
    <script type="text/javascript" src="/system_js/ajax_file_upload.js"></script>
    <script type="text/javascript" src="/system_js/upload_file.js"></script>
    <script type="text/javascript" src="/system_js/manage/product/product_price.js"></script>
    <script type="text/javascript">
        <!--
        var editor;
        var tableType = window.UPLOAD_TABLE_TYPE_PRODUCT_CONTENT;
        var tableId = parseInt('{ChannelId}');

        var productPriceObject = null;
        var productPriceArray = new Array();

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
        };



        $(function () {

            //产品品牌选择
            var btnSelectProductBrand = $("#btn_select_product_brand");
            btnSelectProductBrand.click(function () {
                var url='/default.php?secu=manage&mod=product_brand&&m=list_for_select&site_id={SiteId}';
                $("#dialog_product_brand_select_frame").attr("src",url);
                $("#dialog_product_brand_select_box").dialog({
                    hide:true,    //点击关闭时隐藏,如果不加这项,关闭弹窗后再点就会出错.
                    autoOpen:true,
                    height:500,
                    width:440,
                    modal:true, //蒙层（弹出会影响页面大小）
                    title:'产品品牌选择',
                    overlay: {opacity: 0.5, background: "black" ,overflow:'auto'}
                });
            });

            var editorHeight = $(window).height() - 450;
            editorHeight = parseInt(editorHeight);

            var f_ProductContent = $('#f_ProductContent');

            editor = f_ProductContent.xheditor({
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

                    f_ProductContent.xheditor(false);

                    editor = f_ProductContent.xheditor({
                        tools: 'full',
                        height: editorHeight,
                        upImgUrl: "",
                        upImgExt: "jpg,jpeg,gif,png",
                        localUrlTest: /^https?:\/\/[^\/]*?({manage_domain_rex})\//i,
                        remoteImgSaveUrl: '/default.php?mod=upload_file&a=async_save_remote_image&table_type=' + tableType + '&table_id=' + tableId
                    });

                } else {

                    f_ProductContent.xheditor(false);

                    editor = f_ProductContent.xheditor({
                        tools: 'full',
                        height: editorHeight,
                        upImgUrl: "",
                        upImgExt: "jpg,jpeg,gif,png",
                        localUrlTest: /^https?:\/\/[^\/]*?({manage_domain_rex})\//i,
                        remoteImgSaveUrl: ''
                    });
                }
            });


            var f_AutoRemoveDate = $("#f_AutoRemoveDate");

            f_AutoRemoveDate.datepicker({
                dateFormat: 'yy-mm-dd',
                numberOfMonths: 3,
                showButtonPanel: true
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


            var btnCreateProductPrice = $("#btn_create_product_price");
            btnCreateProductPrice.click(function () {

                var productId = parseInt("{ProductId}");
                if(productId>0){//修改页面的新增价格

                    ProductPriceCreate(productId);

                }else{ //新增页面的增加价格
                    $("#dialog_create_product_price").dialog({
                        title: "新增产品价格",
                        width: 500,
                        height: 300
                    });
                }
            });



            //价格新增确认
            var btnProductPriceConfirm = $("#btn_product_price_confirm");
            btnProductPriceConfirm.click(function () {

                if($("#x_ProductPriceValue").val() == ""){
                    alert("请输入产品价格");
                    return;
                }
                if($("#x_ProductCount").val() == ""){
                    alert("请输入产品数量");
                    return;
                }
                if($("#x_ProductUnit").val() == ""){
                    alert("请输入产品单位");
                    return;
                }
                if($("#x_ProductPriceIntro").val() == ""){
                    alert("请输入产品价格说明");
                    return;
                }

                if($("#btn_product_price_confirm").attr("idvalue") == ""){
                    //新增时确认
                    productPriceObject = new Object();
                    productPriceObject.ProductPriceValue = $("#x_ProductPriceValue").val();
                    productPriceObject.ProductCount = $("#x_ProductCount").val();
                    productPriceObject.ProductUnit = $("#x_ProductUnit").val();
                    productPriceObject.ProductPriceIntro = $("#x_ProductPriceIntro").val();
                    productPriceObject.Sort = $("#x_Sort").val();
                    productPriceObject.State = $("#x_State").val();

                    productPriceArray.push(productPriceObject);

                    $("#x_ProductPriceValue").val("");
                    $("#x_ProductCount").val("");
                    $("#x_ProductPriceIntro").val("");
                    $("#x_Sort").val("0");
                }else{
                    //修改时确认
                    productPriceObject = new Object();
                    productPriceObject.ProductPriceValue = $("#x_ProductPriceValue").val();
                    productPriceObject.ProductCount = $("#x_ProductCount").val();
                    productPriceObject.ProductUnit = $("#x_ProductUnit").val();
                    productPriceObject.ProductPriceIntro = $("#x_ProductPriceIntro").val();
                    productPriceObject.Sort = $("#x_Sort").val();
                    productPriceObject.State = $("#x_State").val();


                    var x = parseInt($("#btn_product_price_confirm").attr("idvalue"));

                    productPriceArray[x] = productPriceObject;
                }




                getProductPriceList();

                $("#dialog_create_product_price").dialog('close');

            });
            getProductPriceList();
        });

        function submitForm(closeTab) {
            if ($('#f_ProductName').val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("请输入产品名称");
            } else if ($('#f_ProductIntro').text().length > 1000) {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("产品简介不能超过1000个字符");
            } else {
                if (closeTab == 1) {
                    $("#CloseTab").val("1");
                } else {
                    $("#CloseTab").val("0");
                }

                //处理价格数组
                var json = JSON.stringify(productPriceArray);
                $("#x_ProductPriceArray").val(json);


                $('#mainForm').submit();
            }
        }
        -->
        function closeProductPriceDialog()
        {
            $('#dialog_resultbox').dialog('close');
        }
        function getProductPriceList() {
            var productId=Request['product_id'];

            if (productId > 0) { //修改页面，从数据库取

                var productPriceHtml=
                    '<tr class="grid_title">'
                        +'<td style="width:40px;text-align:center;">编辑</td>'
                        +'<td>价格说明</td>'
                        +'<td style="width:60px;text-align:center">价格</td>'
                        +'<td style="width:60px;text-align:center">库存数量</td>'
                        +'<td style="width:60px;text-align:center;">单位</td>'
                        +'<td style="width:60px;text-align:center;">状态</td>'
                        +'<td style="width:80px;text-align:center;">启用|停用</td>'
                        +'</tr>';

                $.ajax({
                    url: "/default.php?secu=manage&mod=product_price&m=async_list",
                    data: {secu: "manage", mod: "product_price", m: "async_list", product_id: productId},
                    dataType: "jsonp",
                    async: false,
                    jsonp: "jsonpcallback",
                    success: function (data) {
                        var result = data["result"];
                        $.each(result, function (i, v) {
                            productPriceHtml =productPriceHtml
                               +'<tr>'
                               +'<td class="spe_line2" style="text-align: center;"><img onclick="ProductPriceEdit(this)" class="btn_modify" style="cursor:pointer" src="/system_template/{template_name}/images/manage/edit.gif" alt="编辑" idvalue="'+v["ProductPriceId"]+'" /></td>'
                               +'<td class="spe_line2">'+v["ProductPriceIntro"]+'</td>'
                               +'<td class="spe_line2" style="text-align: center;">'+v["ProductPriceValue"]+'</td>'
                               +'<td class="spe_line2" style="text-align: center;">'+v["ProductCount"]+'</td>'
                               +'<td class="spe_line2" style="text-align: center;">'+v["ProductUnit"]+'</td>'
                               +'<td class="spe_line2" style="text-align: center;"><span class="span_state" id="span_state_'+v["ProductPriceId"]+'">'+FormatProductPriceState(v["State"])+'</span></td>'
                               +'<td class="spe_line2" style="text-align: center;"><img onclick=\'ModifyProductPriceState('+v["ProductPriceId"]+',"0")\' alt="" class="div_start" src="/system_template/{template_name}/images/manage/start.jpg" style="cursor:pointer"/>&nbsp;&nbsp;&nbsp;&nbsp;<img onclick=\'ModifyProductPriceState('+v["ProductPriceId"]+',"100")\' alt="" class="div_stop" src="/system_template/{template_name}/images/manage/stop.jpg" style="cursor:pointer" /></td>'
                               +'</tr>';
                        });
                        $("#product_price_list").html(productPriceHtml);
                    }
                });
            }else{ //新增页面，从productPriceArray对象中取

                var productPriceHtml=
                    '<tr class="grid_title">'
                        +'<td style="width:40px;text-align:center;">编辑</td>'
                        +'<td>价格说明</td>'
                        +'<td style="width:60px;text-align:center">价格</td>'
                        +'<td style="width:60px;text-align:center">库存数量</td>'
                        +'<td style="width:60px;text-align:center;">单位</td>'
                        +'<td style="width:60px;text-align:center;">状态</td>'
                        +'</tr>';

                for(var i=0;i<productPriceArray.length;i++){
                    productPriceHtml = productPriceHtml
                        +'<tr>'
                        +'<td class="spe_line2" style="text-align: center;"><img class="btn_modify_product_price" style="cursor:pointer" src="/system_template/{template_name}/images/manage/edit.gif" alt="编辑" idvalue="'+i+'" /></td>'
                        +'<td class="spe_line2">'+productPriceArray[i].ProductPriceIntro+'</td>'
                        +'<td class="spe_line2" style="text-align: center;">'+productPriceArray[i].ProductPriceValue+'</td>'
                        +'<td class="spe_line2" style="text-align: center;">'+productPriceArray[i].ProductCount+'</td>'
                        +'<td class="spe_line2" style="text-align: center;">'+productPriceArray[i].ProductUnit+'</td>'
                        +'<td class="spe_line2" style="text-align: center;">'+FormatProductPriceState(productPriceArray[i].State)+'</span></td>'
                        +'</tr>';
                }

                $("#product_price_list").html(productPriceHtml);

                //新增产品时，编辑产品价格
                var btnModifyProductPrice = $(".btn_modify_product_price");
                btnModifyProductPrice.click(function () {

                    $("#dialog_create_product_price").dialog({
                        title: "编辑产品价格",
                        width: 500,
                        height: 300
                    });

                    var i = $(this).attr("idvalue");

                    //加载数据
                    if(productPriceArray[i] != undefined && productPriceArray[i] != null){

                        $("#x_ProductPriceValue").val(productPriceArray[i].ProductPriceValue);
                        $("#x_ProductCount").val(productPriceArray[i].ProductCount);
                        $("#x_ProductUnit").val(productPriceArray[i].ProductUnit);
                        $("#x_ProductPriceIntro").val(productPriceArray[i].ProductPriceIntro);
                        $("#x_Sort").val(productPriceArray[i].Sort);
                        $("#x_State").val(productPriceArray[i].State);
                        $("#btn_product_price_confirm").attr("idvalue",i);

                    }

                });

            }
        }
    </script>

    <style type="text/css">
        #main_content {
            width:99%; text-align: center;
        }
        #main_content .main_line_body {
            border-bottom: #d5d5d5 1px dashed; text-align: left
        }
        #main_content .main_line_content {
            float: left; width: 390px; text-align: left
        }
        #main_content .main_line_content_left {
            float: left; width: 80px; line-height: 30px; text-align: right
        }
        #main_content .main_line_content_right {
            float: left; width: 310px; line-height: 30px
        }
        #main_content .main_line_title {
            text-align:left;clear: both; padding-right: 10px; font-weight: bold; padding-bottom: 10px; vertical-align: top; padding-top: 10px; border-bottom: #d5d5d5 1px dashed
        }
    </style>
</head>
<body>
{common_body_deal}

<div id="dialog_create_product_price" style="display:none;">

    <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class="spe_line" height="30" align="right"><label for="x_ProductPriceValue">价格：</label></td>
            <td class="spe_line"><input name="x_ProductPriceValue" id="x_ProductPriceValue" value="" type="text" class="input_price" style=" width: 100px;" /></td>
        </tr>
        <tr>
            <td class="spe_line" height="30" align="right"><label for="x_ProductCount">库存数量：</label></td>
            <td class="spe_line"><input name="x_ProductCount" id="x_ProductCount" value="" type="text" class="input_number" style=" width: 100px;" /></td>
        </tr>
        <tr>
            <td class="spe_line" height="30" align="right"><label for="x_ProductUnit">单位：</label></td>
            <td class="spe_line"><input name="x_ProductUnit" id="x_ProductUnit" value="" type="text" class="input_box" style=" width: 300px;" /></td>
        </tr>
        <tr>
            <td class="spe_line" height="30" align="right"><label for="x_ProductPriceIntro">价格说明：</label></td>
            <td class="spe_line"><input name="x_ProductPriceIntro" id="x_ProductPriceIntro" value="" type="text" class="input_box" style=" width: 300px;" /></td>
        </tr>
        <tr>
            <td class="spe_line" height="30" align="right"><label for="x_Sort">排序：</label></td>
            <td class="spe_line"><input name="x_Sort" id="x_Sort" value="0" type="text" class="input_number" style=" width: 60px;" />(注:输入数字,数值越大越靠前)</td>
        </tr>
        <tr>
            <td class="spe_line" height="30" align="right"><label for="x_State">状态：</label></td>
            <td class="spe_line">
                <select id="x_State" name="x_State">
                    <option value="0">启用</option>
                    <option value="100">停用</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" height="30" align="center">
                <input id="btn_product_price_confirm" idvalue="" class="btn" value="确 认" type="button" />
            </td>
        </tr>
    </table>


</div>



<div id="dialog_resultbox" title="提示信息" style="display: none;">
    <div id="result_table" style="font-size: 14px;">
        <iframe id="dialog_frame" src=""  style="border: 0; " width="100%" height="220px"></iframe>
    </div>
</div>
<div id="dialog_product_brand_select_box" title="提示信息" style="display: none;">
    <div id="result_table" style="font-size: 14px;">
        <iframe id="dialog_product_brand_select_frame" src=""  style="border: 0; " width="100%" height="440px"></iframe>
    </div>
</div>
<form id="mainForm" enctype="multipart/form-data"
      action="/default.php?secu=manage&mod=product&m={method}&channel_id={ChannelId}&product_id={ProductId}&tab_index={tab_index}"
      method="post">
<div>
<table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td class="spe_line" height="40" align="right">
            <input class="btn" value="确认并关闭" type="button" onclick="submitForm(1)"/>
            <input class="btn" value="确认并继续" type="button" onclick="submitForm(0)"/>
            <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
        </td>
    </tr>
</table>


<div id="tabs" style="margin-left:4px;">
    <ul>
        <li><a href="#tabs-1">基本属性</a></li>
        <li><a href="#tabs-2">价格相关</a></li>
        <li><a href="#tabs-3">产品参数</a></li>
        <li><a href="#tabs-4">发货相关</a></li>
        <li><a href="#tabs-5">其他属性</a></li>
    </ul>
    <div id="tabs-1">
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td style=" width: 120px;" class="spe_line" height="30" align="right"><label for="f_ProductName">产品名称：</label></td>
                <td style=" width: 500px;" class="spe_line">
                    <input type="hidden" id="f_ChannelId" name="f_ChannelId" value="{ChannelId}" />
                    <input type="hidden" id="f_SiteId" name="f_SiteId" value="{SiteId}" />
                    <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
                    <input name="f_ProductName" id="f_ProductName" value="{ProductName}" type="text" class="input_box" style="width:300px;"/>
                    <input type="hidden" id="f_UploadFiles" name="f_UploadFiles" value="{UploadFiles}"/>
                    <input type="hidden" id="x_ProductPriceArray" name="x_ProductPriceArray" />
                </td>
                <td style=" width: 120px;" class="spe_line" height="30" align="right"><label for="f_ProductNumber">产品编号：</label></td>
                <td class="spe_line">
                    <input id="f_ProductNumber" name="f_ProductNumber" type="text" value="{ProductNumber}" maxlength="100" style="width:300px;" class="input_box"/>
                    （可以为空）</td>
            </tr>

            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_State">状态：</label></td>
                <td class="spe_line">
                    <select id="f_State" name="f_State">
                        <option value="0">正常</option>
                        <option value="100">停用</option>
                    </select>
                    {s_State}
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="f_SaleCount">销售数量：</label><input name="f_SaleCount" id="f_SaleCount" value="{SaleCount}" type="text" class="input_box" style="width:100px;"/>
                </td>
                <td class="spe_line" height="30" align="right"><label for="f_SaleState">上架情况：</label></td>
                <td class="spe_line">
                    <select id="f_SaleState" name="f_SaleState">
                        <option value="0">正常上架</option>
                        <option value="50">召回处理</option>
                        <option value="100">已经下架</option>
                    </select>
                    {s_SaleState}
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="f_OpenAutoRemove">开启自动下架：</label>
                    <select id="f_OpenAutoRemove" name="f_OpenAutoRemove">
                        <option value="0">关闭</option>
                        <option value="1">开启</option>
                    </select>
                    {s_OpenAutoRemove}
                    <label for="f_AutoRemoveDate">自动下架日期：</label>
                    <input type="text" class="input_box" id="f_AutoRemoveDate" name="f_AutoRemoveDate" value="{AutoRemoveDate}"
                           style=" width: 90px;font-size:14px;" maxlength="10" readonly="readonly"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="file_title_pic_1">产品题图1：</label></td>
                <td class="spe_line">
                    <input id="file_title_pic_1" name="file_title_pic_1" type="file" class="input_box"
                           style="width:400px;background:#ffffff;margin-top:3px;"/>
                    <span id="preview_title_pic1" class="show_title_pic" idvalue="{TitlePic1UploadFileId}" style="cursor:pointer">[预览]</span>
                </td>
                <td class="spe_line" height="30" align="right"><label for="file_title_pic_2">产品题图2：</label></td>
                <td class="spe_line">
                    <input id="file_title_pic_2" name="file_title_pic_2" type="file" class="input_box"
                           style="width:400px; background: #ffffff; margin-top: 3px;"/>
                    <span id="preview_title_pic2" class="show_title_pic" idvalue="{TitlePic2UploadFileId}" style="cursor:pointer">[预览]</span>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="file_title_pic_3">产品题图3：</label></td>
                <td class="spe_line">
                    <input id="file_title_pic_3" name="file_title_pic_3" type="file" class="input_box"
                           style="width:400px; background: #ffffff; margin-top: 3px;"/>
                    <span id="preview_title_pic3" class="show_title_pic" idvalue="{TitlePic3UploadFileId}" style="cursor:pointer">[预览]</span>
                </td>
                <td class="spe_line" height="30" align="right"><label for="file_title_pic_4">产品题图4：</label></td>
                <td class="spe_line">
                    <input id="file_title_pic_4" name="file_title_pic_4" type="file" class="input_box"
                           style="width:400px; background: #ffffff; margin-top: 3px;"/>
                    <span id="preview_title_pic4" class="show_title_pic" idvalue="{TitlePic4UploadFileId}" style="cursor:pointer">[预览]</span>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_ProductIntro">产品介绍：</label></td>
                <td colspan="3" class="spe_line">
                    <textarea cols="30" rows="30" id="f_ProductIntro" name="f_ProductIntro" style="width:70%;height:100px;">{ProductIntro}</textarea>
                </td>

            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_ProductContent">产品内容：</label></td>
                <td class="spe_line" colspan="3">
                    <textarea cols="30" rows="30" id="f_ProductContent" name="f_ProductContent" style="width:70%;height:250px;">{ProductContent}</textarea>
                </td>

            </tr>
            <tr>
                <td height="30" align="right"></td>
                <td colspan="3">
                    <label for="cbAttachWatermark">附加水印：</label>
                    <input type="checkbox" id="cbAttachWatermark" name="cbAttachWatermark"/> (只支持jpg或jpeg图片)
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="cbSaveRemoteImage">远程抓图：</label>
                    <input type="checkbox" id="cbSaveRemoteImage" name="cbSaveRemoteImage"/> (只支持jpg、jpeg、gif、png图片)
                </td>

            </tr>
            <tr>
                <td class="spe_line" height="30" align="right">文件上传：</td>
                <td class="spe_line" colspan="3">
                    <input id="file_upload_to_content" name="file_upload_to_content" type="file"
                           class="input_box" size="7" style="width:30%; background: #ffffff;"/> <img
                        id="loading" src="/system_template/common/images/loading1.gif"
                        style="display:none;"/><input id="btnUploadToContent" type="button" value="上传"/>

                </td>
            </tr>
        </table>
    </div>
    <div id="tabs-2">
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td style=" width: 120px;" class="spe_line" height="30" align="right"><label for="f_SalePrice">显示售价：</label></td>
            <td class="spe_line">
                <input id="f_SalePrice" name="f_SalePrice" type="text" value="{SalePrice}" maxlength="10" style="width:80px;" class="input_price"/>
            </td>
        </tr>
        <tr>
            <td class="spe_line" height="30" align="right"><label for="f_MarketPrice">市面售价：</label></td>
            <td class="spe_line">
                <input id="f_MarketPrice" name="f_MarketPrice" type="text" value="{MarketPrice}" maxlength="10" style="width:80px;" class="input_price"/>
            </td>
        </tr>
        </table>
        <table width="60%" cellpadding="0" cellspacing="0" style="border:1px solid #cccccc; margin-top: 10px" align="left">
            <tr>
                <td align="left"><span style="font-size:14px; font-weight: bold; margin-left: 12px">产品价格列表</span>
                <input style="margin-left: 40px" type="button" value="增加价格" id="btn_create_product_price" />
                    <input type="hidden" id="hidden_product_price_object" />
                </td>
            </tr>
            <tr>
                <td>
                    <table id="product_price_list" width="100%" cellpadding="0" cellspacing="0" align="left">
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <div id="tabs-3">
        <div id="main_content">
            <icms id="{ChannelId}" type="product_param_type_class_list">
                <item>
                    <![CDATA[
                    <div class="main_line_title" style="font-size:14px">{f_ProductParamTypeClassName}</div>
                    <div class="main_line_body">
                        <icms_child id="{f_ProductParamTypeClassId}" type="product_param_type_list">
                            <item_child>
                                [CDATA]
                                <div class="main_line_content">
                                    <div class="main_line_content_left">{f_ParamTypeName}：</div>
                                    <div class="main_line_content_right"><icms_control id="{f_ProductParamTypeId}" product_id="{ProductId}" type="{f_ParamValueType}" input_class="input_box" ></icms_control></div>
                                </div>
                                [/CDATA]
                            </item_child>
                        </icms_child>
                        <div class="spe"></div>
                    </div>
                    ]]>
                </item>
            </icms>
        </div>
    </div>
    <div id="tabs-4">
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td style=" width: 120px;" class="spe_line" height="30" align="right"><label for="f_SendPrice">发货费用：</label></td>
                <td class="spe_line">
                    <input id="f_SendPrice" name="f_SendPrice" type="text" value="{SendPrice}" maxlength="10" style="width:80px;" class="input_price"/>
                </td>
            </tr>

            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_SendPriceAdd">发货续重费用：</label></td>
                <td class="spe_line">
                    <input id="f_SendPriceAdd" name="f_SendPriceAdd" type="text" value="{SendPriceAdd}" maxlength="10" style="width:80px;" class="input_price"/>
                </td>
            </tr>
        </table>
    </div>
    <div id="tabs-5">
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td style=" width: 120px;" class="spe_line" height="30" align="right"><label for="f_ProductShortName">产品品牌：</label></td>
                <td class="spe_line">
                    <label id="s_ProductBrandName" style="width:200px;font-size:14px;">{ProductBrandName}</label>
                    <input type="hidden" id="f_ProductBrandId" name="f_ProductBrandId" value="{ProductBrandId}" />
                    <input type="button"  id="btn_select_product_brand" name="btn_select_product_brand" value="选择品牌" />
                </td>
            </tr>
            <tr>
                <td style=" width: 120px;" class="spe_line" height="30" align="right"><label for="f_ProductShortName">产品简称：</label></td>
                <td class="spe_line">
                    <input id="f_ProductShortName" name="f_ProductShortName" type="text" value="{ProductShortName}" maxlength="100" style="width:300px;" class="input_box"/>
                    （可以为空）</td>
            </tr>

            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_ProductTag">关键字（标签）：</label></td>
                <td class="spe_line">
                    <input id="f_ProductTag" name="f_ProductTag" type="text" value="{ProductTag}" maxlength="200" style="width:300px;" class="input_box"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_Sort">排序号：</label></td>
                <td class="spe_line">
                    <input id="f_Sort" name="f_Sort" type="text" value="{Sort}" maxlength="10" style="width:80px;" class="input_number"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_RecLevel">推荐级别：</label></td>
                <td class="spe_line">
                    <select id="f_RecLevel" name="f_RecLevel">
                        <option value="0">未推荐</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                    {s_RecLevel}
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_IsHot">是否热门产品：</label></td>
                <td class="spe_line">
                    <select id="f_IsHot" name="f_IsHot">
                        <option value="0">无</option>
                        <option value="1">有</option>
                    </select>
                    {s_IsHot}
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_IsNew">是否最新产品：</label></td>
                <td class="spe_line">
                    <select id="f_IsNew" name="f_IsNew">
                        <option value="0">否</option>
                        <option value="1">是</option>
                    </select>
                    {s_IsNew}
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_IsDiscount">是否量贩产品：</label></td>
                <td class="spe_line">
                    <select id="f_IsDiscount" name="f_IsDiscount">
                        <option value="0">否</option>
                        <option value="1">是</option>
                    </select>
                    {s_IsDiscount}
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_GetScore">赠送积分：</label></td>
                <td class="spe_line">
                    <input id="f_GetScore" name="f_GetScore" type="text" value="{GetScore}" maxlength="10" style="width:80px;" class="input_number"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_DirectUrl">直接转向网址：</label></td>
                <td class="spe_line">
                    <input id="f_DirectUrl" name="f_DirectUrl" type="text" value="{DirectUrl}" maxlength="200" style="width:600px;" class="input_box"/>
                </td>
            </tr>
        </table>
    </div>
</div>


<table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td height="60" align="center">
            <input class="btn" value="确认并关闭" type="button" onclick="submitForm(1)"/> <input class="btn" value="确认并继续"
                                                                                            type="button"
                                                                                            onclick="submitForm(0)"/>
            <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
        </td>
    </tr>
</table>
</div>
</form>
</body>
</html>
