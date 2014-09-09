<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript" src="/system_js/xheditor-1.1.14/xheditor-1.1.14-zh-cn.min.js"></script>
    <script type="text/javascript" src="/system_js/ajax_file_upload.js"></script>
    <script type="text/javascript">
        <!--
        var editor;
        $(function () {
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
            var productPriceHtml=
                '<tr class="grid_title">'
                    +'<td style="width:40px;text-align:center;">编辑</td>'
                    +'<td>价格说明</td>'
                    +'<td style="width:60px;text-align:center">价格</td>'
                    +'<td style="width:60px;text-align:center">数量</td>'
                    +'<td style="width:60px;text-align:center;">单位</td>'
                    +'<td style="width:60px;text-align:center;">状态</td>'
                    +'<td style="width:80px;text-align:center;">启用|停用</td>'
                    +'</tr>';
            if (productId > 0) {
                $.ajax({
                    url: "/default.php?secu=manage&mod=product_price&m=async_list&p=NaN",
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
            }
        }
    </script>
    <script type="text/javascript" src="/system_js/manage/product/product_price.js"></script>
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
<div id="dialog_resultbox" title="提示信息" style="display: none;">
    <div id="result_table" style="font-size: 14px;">
        <iframe id="dialog_frame" src=""  style="border: 0; " width="100%" height="220px"></iframe>
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
                <td class="spe_line">
                    <input type="hidden" id="f_ChannelId" name="f_ChannelId" value="{ChannelId}" />
                    <input type="hidden" id="f_SiteId" name="f_SiteId" value="{SiteId}" />
                    <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
                    <input name="f_ProductName" id="f_ProductName" value="{ProductName}" type="text" class="input_box" style="width:300px;"/>
                </td>
            </tr>

            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_ProductNumber">产品编号：</label></td>
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
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_SaleState">上架情况：</label></td>
                <td class="spe_line">
                    <select id="f_SaleState" name="f_SaleState">
                        <option value="0">正常上架</option>
                        <option value="50">召回处理</option>
                        <option value="100">已经下架</option>
                    </select>
                    {s_SaleState}
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="file_title_pic_1">产品题图1：</label></td>
                <td class="spe_line">
                    <input id="file_title_pic_1" name="file_title_pic_1" type="file" class="input_box"
                           style="width:400px;background:#ffffff;margin-top:3px;"/>
                    <span id="preview_title_pic1" class="show_title_pic" idvalue="{TitlePic1UploadFileId}" style="cursor:pointer">[预览]</span>
                </td>
            </tr>
            <tr>
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
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="file_title_pic_4">产品题图4：</label></td>
                <td class="spe_line">
                    <input id="file_title_pic_4" name="file_title_pic_4" type="file" class="input_box"
                           style="width:400px; background: #ffffff; margin-top: 3px;"/>
                    <span id="preview_title_pic4" class="show_title_pic" idvalue="{TitlePic4UploadFileId}" style="cursor:pointer">[预览]</span>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_ProductIntro">产品介绍：</label></td>
                <td class="spe_line">
                    <textarea cols="30" rows="30" id="f_ProductIntro" name="f_ProductIntro" style="width:70%;height:100px;">{ProductIntro}</textarea>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_ProductContent">产品内容：</label></td>
                <td class="spe_line">
                    <textarea cols="30" rows="30" id="f_ProductContent" name="f_ProductContent" style="width:70%;height:250px;">{ProductContent}</textarea>
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
                <input style="margin-left: 40px" type="button" value="增加价格" onclick="ProductPriceCreate()"; />
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
