<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript" src="/system_js/xheditor-1.1.13/xheditor-1.1.13-zh-cn.min.js"></script>
    <script type="text/javascript" src="/system_js/ajax_file_upload.js"></script>
    <script type="text/javascript">
        <!--
        var editor;
        $(function () {
            editor = $('#f_ProductContent').xheditor();
            $("#f_CreateDate").datepicker({
                dateFormat: 'yy-mm-dd',
                numberOfMonths: 1,
                showButtonPanel: true
            });

            $("#preview_title_pic1").click(function () {
                var imgTitlePic1 = "{TitlePic1}";
                if (imgTitlePic1 !== '') {
                    var imageOfTitlePic1 = new Image();
                    imageOfTitlePic1.src = imgTitlePic1;
                    $("#dialog_box").dialog({
                        width: imageOfTitlePic1.width + 30,
                        height: imageOfTitlePic1.height + 30
                    });
                    var imgHtml = '<' + 'img src="' + imgTitlePic1 + '" alt="" />';
                    $("#dialog_content").html(imgHtml);

                }
                else {
                    $("#dialog_box").dialog({width: 300, height: 100});
                    $("#dialog_content").html("还没有上传题图1");
                }
            });


            $("#preview_title_pic2").click(function () {
                var imgTitlePic2 = "{TitlePic2}";
                if (imgTitlePic2 !== '') {
                    var imageOfTitlePic2 = new Image();
                    imageOfTitlePic2.src = imgTitlePic2;
                    $("#dialog_box").dialog({
                        width: imageOfTitlePic2.width + 30,
                        height: imageOfTitlePic2.height + 30
                    });
                    var imgHtml = '<' + 'img src="' + imgTitlePic2 + '" alt="" />';
                    $("#dialog_content").html(imgHtml);
                }
                else {
                    $("#dialog_box").dialog({width: 300, height: 100});
                    $("#dialog_content").html("还没有上传题图2");
                }
            });

            $("#preview_title_pic3").click(function () {
                var imgTitlePic3 = "{TitlePic2}";
                if (imgTitlePic3 !== '') {
                    var imageOfTitlePic3 = new Image();
                    imageOfTitlePic3.src = imgTitlePic3;
                    $("#dialog_box").dialog({
                        width: imageOfTitlePic3.width + 30,
                        height: imageOfTitlePic3.height + 30
                    });
                    var imgHtml = '<' + 'img src="' + imgTitlePic3 + '" alt="" />';
                    $("#dialog_content").html(imgHtml);
                }
                else {
                    $("#dialog_box").dialog({width: 300, height: 100});
                    $("#dialog_content").html("还没有上传题图2");
                }
            });

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
    </script>
</head>
<body>
{common_body_deal}
<form id="mainForm" enctype="multipart/form-data"
      action="/default.php?secu=manage&mod=product&m={method}&channel_id={ChannelId}&tab_index={tab_index}"
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
                    <span id="preview_title_pic1" style="cursor:pointer">[预览]</span>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="file_title_pic_2">产品题图2：</label></td>
                <td class="spe_line">
                    <input id="file_title_pic_2" name="file_title_pic_2" type="file" class="input_box"
                           style="width:400px; background: #ffffff; margin-top: 3px;"/>
                    <span id="preview_title_pic2" style="cursor:pointer">[预览]</span>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="file_title_pic_3">产品题图3：</label></td>
                <td class="spe_line">
                    <input id="file_title_pic_3" name="file_title_pic_3" type="file" class="input_box"
                           style="width:400px; background: #ffffff; margin-top: 3px;"/>
                    <span id="preview_title_pic3" style="cursor:pointer">[预览]</span>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="file_title_pic_4">产品题图4：</label></td>
                <td class="spe_line">
                    <input id="file_title_pic_4" name="file_title_pic_4" type="file" class="input_box"
                           style="width:400px; background: #ffffff; margin-top: 3px;"/>
                    <span id="preview_title_pic4" style="cursor:pointer">[预览]</span>
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
    </div>
    <div id="tabs-3">
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">

        </table>
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
