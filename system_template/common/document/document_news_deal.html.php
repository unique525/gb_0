<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title></title>
{common_head}
<script type="text/javascript" src="/system_js/xheditor-1.1.14/xheditor-1.1.14-zh-cn.min.js"></script>
<script type="text/javascript" src="/system_js/color_picker.js"></script>
<script type="text/javascript" src="/system_js/ajax_file_upload.js"></script>
<script type="text/javascript" src="/system_js/upload_file.js"></script>
<script type="text/javascript">
<!--
var editor;
var batchAttachWatermark = "0";

var tableType = window.UPLOAD_TABLE_TYPE_DOCUMENT_NEWS_CONTENT;
var tableId = parseInt('{ChannelId}');

//上传回调函数
window.AjaxFileUploadCallBack = function(data){

}



$(function () {

    var editorHeight = $(window).height() - 220;
    editorHeight = parseInt(editorHeight);

    var f_DocumentNewsContent = $('#f_DocumentNewsContent');

    editor = f_DocumentNewsContent.xheditor({
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

            f_DocumentNewsContent.xheditor(false);

            editor = f_DocumentNewsContent.xheditor({
                tools: 'full',
                height: editorHeight,
                upImgUrl: "",
                upImgExt: "jpg,jpeg,gif,png",
                localUrlTest: /^https?:\/\/[^\/]*?({manage_domain_rex})\//i,
                remoteImgSaveUrl: '/default.php?mod=upload_file&a=async_save_remote_image&table_type=' + tableType + '&table_id=' + tableId
            });

        } else {

            f_DocumentNewsContent.xheditor(false);

            editor = f_DocumentNewsContent.xheditor({
                tools: 'full',
                height: editorHeight,
                upImgUrl: "",
                upImgExt: "jpg,jpeg,gif,png",
                localUrlTest: /^https?:\/\/[^\/]*?({manage_domain_rex})\//i,
                remoteImgSaveUrl: ''
            });
        }
    });

    $('#tabs').tabs();

    var f_ShowDate = $("#f_ShowDate");

    f_ShowDate.datepicker({
        dateFormat: 'yy-mm-dd',
        numberOfMonths: 3,
        showButtonPanel: true
    });

    if (Request["document_news_id"] == undefined) {
        var today = new Date();
        var month = today.getMonth() + 1;
        var s_date = today.getFullYear() + "-" + month + "-" + today.getDate();
        var s_hour = today.getHours() < 10 ? "0" + today.getHours() : today.getHours();
        var s_minute = today.getMinutes() < 10 ? "0" + today.getMinutes() : today.getMinutes();
        var s_second = today.getSeconds() < 10 ? "0" + today.getSeconds() : today.getSeconds();
        f_ShowDate.val(s_date);
        $("#f_ShowHour").val(s_hour);
        $("#f_ShowMinute").val(s_minute);
        $("#f_ShowSecond").val(s_second);
    }


    ///////////////////加粗/////////////////////////
    var cbTitleBold = $("#cbTitleBold");
    cbTitleBold.click(function () {
        ChangeToBold();
    });
    //
    //加载BOLD
    var bold = $("#f_DocumentNewsTitleBold").val();
    if (bold == "bold") {
        cbTitleBold.attr('checked', true);
        ChangeToBold();
    }

    ///////////////////
    //加载TITLE COLOR
    var titleColor = $("#f_DocumentNewsTitleColor").val();
    if (titleColor.length >= 3) {
        $("#f_DocumentNewsTitle").css("color", titleColor);
    }

    var btnSetSourceName = $(".btnSetSourceName");
    btnSetSourceName.css("cursor", "pointer");
    btnSetSourceName.click(function () {
        $("#f_SourceName").val($(this).text());
    });


    var btnUploadToContent = $("#btnUploadToContent");
    btnUploadToContent.click(function () {

        var fileElementId = 'file_upload_to_content';
        var fUploadFile = $("#f_UploadFiles");

        var attachWatermark = 0;
        if ($("#cbAttachWatermark").attr("checked") == true) {
            attachWatermark = 1;
        }
        var loadingImageId = null;
        var inputTextId = null;
        var previewImageId = null;
        var uploadFileId = 0;
        AjaxFileUpload(
            fileElementId,
            tableType,
            tableId,
            loadingImageId,
            btnUploadToContent,
            editor,
            fUploadFile,
            attachWatermark,
            inputTextId,
            previewImageId,
            uploadFileId
        );
    });

    var btnAddPreContent = $(".btn_add_pre_content");
    btnAddPreContent.click(function () {

        var addContent = $(this).text();
        var editorContent = editor.getSource();
        editorContent = "　　" + addContent + editorContent;
        editor.setSource(editorContent);

    });

    var btnSelectSource = $("#btn_select_source");
    btnSelectSource.css("cursor", "pointer");
    btnSelectSource.click(function () {
        //$("#dialog_abstract_box").dialog({width: 700, height: 400, modal: true});
        $("#dialog_abstract_box").css("display", "block");
        $("#dialog_abstract_content").html("" +
            "<iframe src='/default.php?secu=manage&mod=source&m=select'" +
            " width='780' height='520' frameborder='0'></iframe>");
    });

    var btnCloseAbstractBox = $("#btn_close_abstract_box");
    btnCloseAbstractBox.css("cursor", "pointer");
    btnCloseAbstractBox.click(function () {
        closeAbstractBox();
    });

    ///文本框获取焦点 开始监听
    $('.input_title').focus(function(){
            var id=$(this).attr("id");
        strLengthRefresh=1;
        setTimeout('StrLength("'+id+'")', 100);
        });


    ///文本框失去焦点 停止监听
    $('.input_title').blur(function(){
        strLengthRefresh=0;
        });

});

var strLengthRefresh=0;  //全局变量 是否监听
///监听函数
function StrLength(id) {
    var str =  $('#'+id).val();
    var realLength = 0, len = str.length, charCode = -1;
    for (var i = 0; i < len; i++) {
        charCode = str.charCodeAt(i);
        if (charCode >= 0 && charCode <= 128) realLength += 1;
        else realLength += 2;
    }
    $('#length_'+id).html("字符长度:" + realLength);
    if(strLengthRefresh==1){
        setTimeout('StrLength("'+id+'")', 100);
    }
}

function ChangeToBold() {

    if ($("#cbTitleBold").attr('checked')) {
        $("#f_DocumentNewsTitleBold").val("bold");
        $("#f_DocumentNewsTitle").css("font-weight", "bold");
    } else {
        $("#f_DocumentNewsTitleBold").val("normal");
        $("#f_DocumentNewsTitle").css("font-weight", "normal");
    }

}

function closeAbstractBox() {
    $("#dialog_abstract_box").css("display", "none");
}

function setSourceName(sourceName) {
    $("#f_SourceName").val(sourceName);
}

function submitForm(closeTab) {
    if ($('#f_DocumentNewsTitle').val() == '') {
        $("#dialog_box").dialog({width: 300, height: 100});
        $("#dialog_content").html("请输入文档标题");
    } else {
        if (closeTab == 1) {
            $("#CloseTab").val("1");
        } else {
            $("#CloseTab").val("0");
        }

        $("#mainForm").attr("action", "/default.php?secu=manage" +
            "&mod=document_news&m={method}" +
            "&channel_id={ChannelId}" +
            "&document_news_id={DocumentNewsId}&tab_index=" + parent.G_TabIndex + "");
        $('#mainForm').submit();
    }
}
-->
</script>
</head>
<body>

{common_body_deal}
<div id="dialog_abstract_box" title="选择来源" style="">
    <div style="text-align:right;"><img id="btn_close_abstract_box" alt="关闭" title="关闭"
                                        src="/system_template/{template_name}/images/manage/close3.gif"/></div>
    <div id="dialog_abstract_content">

    </div>
</div>
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
<div id="tabs" style="margin-left:4px;">
<ul>
    <li><a href="#tabs-1">文档内容</a></li>
    <li><a href="#tabs-2">文档参数</a></li>
    <li><a href="#tabs-3">批量上传</a></li>
    <li><a href="#tabs-4">其他属性</a></li>
</ul>
<div id="tabs-1">
    <div class="spe_line" style="line-height: 40px;text-align: left;">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td style=" width: 60px;"><label for="f_DocumentNewsTitle">标题：</label></td>
                <td style=" width: 600px;">
                    <input type="text" class="iColorPicker input_box input_title" id="f_DocumentNewsTitle"
                           name="f_DocumentNewsTitle" value="{DocumentNewsTitle}"
                           style="width:480px;font-size:14px; background-color: #ffffff;" maxlength="200"/>
                    <input type="hidden" id="f_DocumentNewsTitleColor" name="f_DocumentNewsTitleColor"
                           value="{DocumentNewsTitleColor}"/>
                    <input type="hidden" id="f_DocumentNewsTitleBold" name="f_DocumentNewsTitleBold"
                           value="{DocumentNewsTitleBold}"/>
                    <input type="hidden" id="f_SiteId" name="f_SiteId" value="{SiteId}"/>
                    <input type="hidden" id="f_ChannelId" name="f_ChannelId" value="{ChannelId}"/>
                    <input type="hidden" id="f_UploadFiles" name="f_UploadFiles" value="{UploadFiles}"/>
                </td>
                <td>
                    <input type="checkbox" id="cbTitleBold"/> <label for="cbTitleBold">加粗</label>
                    <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
                    <span id="length_f_DocumentNewsTitle"></span>
                </td>
            </tr>
        </table>
    </div>
    <div style=" margin-top:3px;">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="75%"><label for="f_DocumentNewsContent"></label><textarea class="mceEditor"
                                                                                     id="f_DocumentNewsContent"
                                                                                     name="f_DocumentNewsContent"
                                                                                     style=" width: 100%;">{DocumentNewsContent}</textarea>
                </td>
                <td style="vertical-align:top;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0"
                           style=" border: solid 1px #cccccc; margin-left: 3px; padding: 2px;">
                        <tr>
                            <td style="width:74px;height:35px;text-align: right;"><label for="f_SourceName">来源：</label>
                            </td>
                            <td style="text-align: left; line-height:180%;">
                                <input type="text" class="input_box" id="f_SourceName" name="f_SourceName"
                                       value="{SourceName}" style=" width:60%;font-size:14px; margin-top: 4px;"
                                       maxlength="50"/>&nbsp;<span id="btn_select_source">[选择来源]</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:74px;height:35px;text-align: right;">常用来源：</td>
                            <td class="spe_line" style="text-align: left; line-height:180%;">
                                <icms id="source_common_list" type="list">
                                    <item>
                                        <![CDATA[<span class="btnSetSourceName">{f_SourceName}</span><br/>]]>
                                    </item>
                                </icms>
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:74px;height:35px;text-align: right;"><label
                                    for="f_Author">作者：</label></td>
                            <td class="spe_line" style="text-align: left">
                                <input type="text" class="input_box" id="f_Author" name="f_Author" value="{Author}"
                                       style="width:95%;font-size:14px;" maxlength="50"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:74px;height:35px;text-align: right;"><label
                                    for="f_DocumentNewsMainTag">主关键词：</label></td>
                            <td class="spe_line" style="text-align: left">
                                <input type="text" class="input_box" id="f_DocumentNewsMainTag"
                                       name="f_DocumentNewsMainTag" value="{DocumentNewsMainTag}"
                                       style="width:95%;font-size:14px;" maxlength="100"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:74px;height:35px;text-align: right;"><label
                                    for="f_DocumentNewsTag">关键词：</label></td>
                            <td class="spe_line" style="text-align: left">
                                <input type="text" class="input_box" id="f_DocumentNewsTag" name="f_DocumentNewsTag"
                                       value="{DocumentNewsTag}" style="width:95%;font-size:14px;" maxlength="200"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="height:35px;text-align: right;">加前缀：</td>
                            <td class="spe_line" align="left" style="line-height:20px;">
                                <icms id="document_pre_content" type="list">
                                    <item>
                                        <![CDATA[
                                        <span style="cursor: pointer;" class="btn_add_pre_content"
                                              title="{f_DocumentPreContent}">{f_DocumentPreContent}</span><br/>
                                        ]]>
                                    </item>
                                </icms>
                            </td>
                        </tr>
                        <tr>
                            <td style="height:35px;">文件上传：</td>
                            <td align="left">
                                <input id="file_upload_to_content" name="file_upload_to_content" type="file"
                                       class="input_box" size="7" style="width:60%; background: #ffffff;"/> <img
                                    id="loading" src="/system_template/common/images/loading1.gif"
                                    style="display:none;"/><input id="btnUploadToContent" type="button" value="上传"/>
                            </td>
                        </tr>
                        <tr>
                            <td style="height:35px;"><label for="cbAttachWatermark">附加水印：</label></td>
                            <td align="left">
                                <input type="checkbox" id="cbAttachWatermark" name="cbAttachWatermark"/> (只支持jpg或jpeg图片)
                            </td>
                        </tr>
                        <tr>
                            <td style="height:35px;"><label for="cbSaveRemoteImage">远程抓图：</label></td>
                            <td align="left">
                                <input type="checkbox" id="cbSaveRemoteImage" name="cbSaveRemoteImage"/>
                                (只支持jpg、jpeg、gif、png图片)
                            </td>
                        </tr>
                    </table>


                </td>
            </tr>
        </table>
    </div>
</div>
<div id="tabs-2">
    <table width="99%" border="0" cellspacing="0" cellpadding="0">

        <tr>
            <td class="spe_line" style="width:200px;height:35px;text-align: right;">题图1：</td>
            <td class="spe_line" style="text-align: left">
                <input id="file_title_pic_1" name="file_title_pic_1" type="file" class="input_box"
                       style="width:400px;background:#ffffff;margin-top:3px;"/> <span id="preview_title_pic1"
                                                                                      class="show_title_pic"
                                                                                      idvalue="{TitlePic1UploadFileId}"
                                                                                      style="cursor:pointer">[预览]</span>
            </td>
        </tr>
        <tr>
            <td class="spe_line" style="width:200px;height:35px;text-align: right;">题图2：</td>
            <td class="spe_line" style="text-align: left">
                <input id="file_title_pic_2" name="file_title_pic_2" type="file" class="input_box"
                       style="width:400px; background: #ffffff; margin-top: 3px;"/> <span id="preview_title_pic2"
                                                                                          class="show_title_pic"
                                                                                          idvalue="{TitlePic2UploadFileId}"
                                                                                          style="cursor:pointer">[预览]</span>
            </td>
        </tr>
        <tr>
            <td class="spe_line" style="width:200px;height:35px;text-align: right;">题图3：</td>
            <td class="spe_line" style="text-align: left">
                <input id="file_title_pic_3" name="file_title_pic_3" type="file" class="input_box"
                       style="width:400px; background: #ffffff; margin-top: 3px;"/> <span id="preview_title_pic3"
                                                                                          class="show_title_pic"
                                                                                          idvalue="{TitlePic3UploadFileId}"
                                                                                          style="cursor:pointer">[预览]</span>
            </td>
        </tr>
        <tr>
            <td class="spe_line" style="width:200px;height:35px;text-align: right;"><label
                    for="f_DocumentNewsShortTitle">短标题：</label></td>
            <td class="spe_line" style="text-align: left"><input type="text" class="input_box input_title"
                                                                 id="f_DocumentNewsShortTitle"
                                                                 name="f_DocumentNewsShortTitle"
                                                                 value="{DocumentNewsShortTitle}"
                                                                 style=" width: 600px;font-size:14px;" maxlength="100"/>
            <span id="length_f_DocumentNewsShortTitle"></span>
            </td>
        </tr>
        <tr>
            <td class="spe_line" style="width:200px;height:35px;text-align: right;"><label for="f_DocumentNewsSubTitle">副标题：</label>
            </td>
            <td class="spe_line" style="text-align: left"><input type="text" class="input_box input_title"
                                                                 id="f_DocumentNewsSubTitle"
                                                                 name="f_DocumentNewsSubTitle"
                                                                 value="{DocumentNewsSubTitle}"
                                                                 style=" width: 600px;font-size:14px;" maxlength="100"/>
                <span id="length_f_DocumentNewsSubTitle"></span>
            </td>
        </tr>

        <tr>
            <td class="spe_line" style="width:200px;height:35px;text-align: right;"><label
                    for="f_DocumentNewsCiteTitle">引题：</label></td>
            <td class="spe_line" style="text-align: left"><input type="text" class="input_box input_title"
                                                                 id="f_DocumentNewsCiteTitle"
                                                                 name="f_DocumentNewsCiteTitle"
                                                                 value="{DocumentNewsCiteTitle}"
                                                                 style=" width: 600px;font-size:14px;" maxlength="100"/>
                <span id="length_f_DocumentNewsCiteTitle"></span>
            </td>
        </tr>

        <tr>
            <td class="spe_line" style="width:200px;height:65px;text-align: right;"><label
                    for="f_DocumentNewsIntro">摘要：</label><br/><br/>
                <input type="button" class="btn4" value="编 写"
                       onclick='showModalDialog("/system_js/manage/document_news/edit_abstract.html", window, "dialogWidth:850px;dialogHeight:400px;help:no;scroll:no;status:no");'/>&nbsp;
            </td>
            <td class="spe_line" style="text-align: left"><textarea class="input_box input_title" id="f_DocumentNewsIntro"
                                                                    name="f_DocumentNewsIntro"
                                                                    style="width: 600px; height: 80px;font-size:14px;">{DocumentNewsIntro}</textarea>
                <span id="length_f_DocumentNewsIntro"></span>
            </td>
        </tr>
        <tr>
            <td class="spe_line" style="width:200px;height:35px;text-align: right;"><label
                    for="f_DirectUrl">直接转向网址：</label></td>
            <td class="spe_line" style="text-align: left"><input type="text" class="input_box" id="f_DirectUrl"
                                                                 name="f_DirectUrl" value="{DirectUrl}"
                                                                 style=" width: 600px;font-size:14px;" maxlength="200"/>
                (设置直接转向网址后，文档将直接转向到该网址)
            </td>
        </tr>
    </table>
</div>
<div id="tabs-3">
    <div id="uploader">
        <p>您的浏览器不支持 Flash, SilverLight, Gears, BrowserPlus 或 HTML5，不能使用组图上传功能</p>
    </div>
    <div class="spe_line" style=" line-height: 30px;"><label for="f_ShowPicMethod">使用组图控件展示内容中的图片</label>
        <select id="f_ShowPicMethod" name="f_ShowPicMethod">
            <option value="0">关闭</option>
            <option value="1">开启</option>
        </select>
        {s_ShowPicMethod}
        &nbsp;&nbsp;&nbsp;&nbsp;
        <label for="batchAttachWatermark">附加水印：</label><input type="checkbox" id="batchAttachWatermark"
                                                              name="batchAttachWatermark"/> (只支持jpg或jpeg图片)
    </div>
</div>
<div id="tabs-4">

    <table width="99%" border="0" cellspacing="0" cellpadding="0">

        <tr>
            <td class="spe_line" style="width:200px;height:35px;text-align: right;"><label for="f_Sort">排序数字：</label>
            </td>
            <td class="spe_line" style="text-align: left"><input type="text" class="input_number" id="f_Sort"
                                                                 name="f_Sort" value="{Sort}"
                                                                 style=" width: 60px;font-size:14px;" maxlength="10"/>
                (输入数字，越大越靠前)
            </td>
        </tr>
        <tr>
            <td class="spe_line" style="width:200px;height:35px;text-align: right;">是否热门：</td>
            <td class="spe_line" style="text-align: left">
                <label>
                    <input type="radio" name="f_IsHot" value="0"/>
                    否
                </label>
                <label>
                    <input type="radio" name="f_IsHot" value="1"/>
                    是
                </label>
                {r_IsHot}
            </td>
        </tr>
        <tr>
            <td class="spe_line" style="width:200px;height:35px;text-align: right;">推荐级别：</td>
            <td class="spe_line" style="text-align: left">
                <label>
                    <input type="radio" name="f_RecLevel" value="0"/>
                    0
                </label>
                <label>
                    <input type="radio" name="f_RecLevel" value="1"/>
                    1
                </label>
                <label>
                    <input type="radio" name="f_RecLevel" value="2"/>
                    2
                </label>
                <label>
                    <input type="radio" name="f_RecLevel" value="3"/>
                    3
                </label>
                <label>
                    <input type="radio" name="f_RecLevel" value="4"/>
                    4
                </label>
                <label>
                    <input type="radio" name="f_RecLevel" value="5"/>
                    5
                </label>
                <label>
                    <input type="radio" name="f_RecLevel" value="6"/>
                    6
                </label>
                <label>
                    <input type="radio" name="f_RecLevel" value="7"/>
                    7
                </label>
                <label>
                    <input type="radio" name="f_RecLevel" value="8"/>
                    8
                </label>
                <label>
                    <input type="radio" name="f_RecLevel" value="9"/>
                    9
                </label>
                <label>
                    <input type="radio" name="f_RecLevel" value="10"/>
                    10
                </label>
                {r_RecLevel}
            </td>
        </tr>
        <tr>
            <td class="spe_line" style="width:200px;height:35px;text-align: right;">显示时间：</td>
            <td class="spe_line" style="text-align: left">
                <input type="text" class="input_box" id="f_ShowDate" name="f_ShowDate" value="{ShowDate}"
                       style=" width: 90px;font-size:14px;" maxlength="10" readonly="readonly"/><label
                    for="f_ShowDate"> </label>
                <input type="text" class="input_number" style=" width:20px;font-size:14px;" id="f_ShowHour"
                       name="f_ShowHour" value="{ShowHour}" maxlength="2"/><label for="f_ShowHour">:</label>
                <input type="text" class="input_number" style=" width:20px;font-size:14px;" id="f_ShowMinute"
                       name="f_ShowMinute" value="{ShowMinute}" maxlength="2"/><label for="f_ShowMinute">:</label>
                <input type="text" class="input_number" style=" width:20px;font-size:14px;" id="f_ShowSecond"
                       name="f_ShowSecond" value="{ShowSecond}" maxlength="2"/><label for="f_ShowSecond">
                    (在文档中显示出来的时间，可任意设置)</label>
            </td>
        </tr>
        <tr>
            <td class="spe_line" style="width:200px;height:35px;text-align: right;"><label for="f_DocumentNewsType">新闻类型：</label>
            </td>
            <td class="spe_line" style="text-align: left">
                <select id="f_DocumentNewsType" name="f_DocumentNewsType">
                    <option value="0">常规新闻</option>
                </select>{s_DocumentNewsType}
            </td>
        </tr>
        <tr>
            <td class="spe_line" style="width:200px;height:35px;text-align: right;"><label for="f_State">当前状态：</label>
            </td>
            <td class="spe_line" style="text-align: left">
                <select id="f_State" name="f_state">
                    <option value="0">新稿</option>
                    <option value="1">已编</option>
                    <option value="2">返工</option>
                    <option value="11">一审</option>
                    <option value="12">二审</option>
                    <option value="13">三审</option>
                    <option value="14">终审</option>
                    <option value="127"></option>
                </select>
                {s_state}
            </td>
        </tr>
        <tr>
            <td class="spe_line" style="width:200px;height:35px;text-align: right;"><label
                    for="f_OpenComment">新闻评论：</label></td>
            <td class="spe_line" style="text-align: left">
                <select id="f_OpenComment" name="f_OpenComment">
                    <option value="40">根据频道设置而定</option>
                    <option value="20">允许但需要审核（先发后审）</option>
                    <option value="10">允许但需要审核（先审后发）</option>
                    <option value="30">自由评论</option>
                    <option value="0">不允许</option>
                </select>
                {s_OpenComment}
            </td>
        </tr>
        <tr>
            <td class="spe_line" style="width:200px;height:35px;text-align: right;"><label
                    for="f_ClosePosition">心情表态：</label></td>
            <td class="spe_line" style="text-align: left">
                <select id="f_ClosePosition" name="f_ClosePosition">
                    <option value="0">开启</option>
                    <option value="1">关闭</option>
                </select>
                {s_ClosePosition}
            </td>
        </tr>
        <!-- 加点击 -->
        <tr>
            <td class="spe_line" style="width:200px;height:35px;text-align: right;"><label
                    for="f_Hit">初始点击数：</label></td>
            <td class="spe_line" style="text-align: left">
                <input type="text" class="input_box" id="f_Hit" name="f_Hit"
                       value="{Hit}" style="width:95%;font-size:14px;" maxlength="200" />
                <script type="text/javascript">
                    $(function () {
                        var hit=$("#f_Hit").attr("value");
                        var siteId={SiteId};
                        if((hit==""||hit=="0")&&siteId==2){
                            var addHit=GetRandomNum(500,700);
                            $("#f_Hit").attr("value",addHit);
                        }
                    });
                    function GetRandomNum(Min,Max)
                    {
                        var Range = Max - Min;
                        var Rand = Math.random();
                        return(Min + Math.round(Rand * Range));
                    }
                </script>
            </td>
        </tr>
        <!-- 加点击 end -->
    </table>
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
<div id="dialog_abstract_box" title="编写摘要" style="display:none;">
    <div id="dialog_abstract_content">

    </div>
</div>

</body>
</html>
