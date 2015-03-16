<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title></title>
{common_head}
<script type="text/javascript" src="/system_js/manage/document_news/document_news_pic.js"></script>
<script type="text/javascript">
<!--
$(function () {


});

function submitForm(closeTab) {
    SetDocumentNewsPic();
        $("#mainForm").attr("action", "/default.php?secu=manage" +
            "&mod=document_news_pic&m={method}" +
            "&channel_id={ChannelId}" +
            "&document_news_id={DocumentNewsId}&tab_index=" + parent.G_TabIndex + "&p={PageIndex}");
        $('#mainForm').submit();
}


-->
</script>
<style>

    /***** 图片管理css  *****/
    .li_pic_img_item{
        width:276px;
        float: left;
        margin: 10px;
        box-shadow: 0 0 5px #666;
        padding-right: 4px;
        background: none repeat scroll 0 0 #EFEFEF;
        position: relative;
    }
    .notice {width:100%;height:100%;position: absolute;background-color: rgba(3, 3, 3, 0.7);z-index: 5;display: none}
    .pic_img_container {width:280px;height:190px;background-color: rgb(253, 253, 253)}
    .pic_img_container img{max-width:280px;max-height:190px;display: block}
    .pic_img_title{padding:3px 5px;width:266px;height:22px;position:relative}
    .pic_img_title input{width:265px;height:15px;position: absolute;margin-top:3px;z-index: 10}
    .pic_img_state{padding:3px 5px;position: relative}
    .pic_img_state span{float:left;}
    .pic_img_state img{vertical-align:middle;cursor: pointer}
    .btn_update_title{float:right;cursor: pointer;display: none;padding:0 5px;right:0;top: 3px;background: #eee;z-index: 10;position: absolute}
</style>
</head>
<body>

{common_body_deal}
<div class="div_deal">
<form id="mainForm" enctype="multipart/form-data" method="post">
<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td class="spe_line" height="40" align="right">
            <input class="btn" value="确认并关闭" type="button" onclick="submitForm(1)"/>
            <!--<input class="btn" value="确认并继续" type="button" onclick="submitForm(0)"/>-->
            <input class="btn" value="返 回" type="button" onclick="closeTab()"/>
        </td>
    </tr>
</table>
<table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
<tr>
<td>

<div id="tabs-5">
    <div class="div_list" id="">
        <ul id="old_pic_list">

            <icms id="document_news_pic" type="list">
                <item>
                    <![CDATA[
                    <li class="li_pic_img_item" id="UploadFileId_{f_UploadFileId}" >
                        <div class="notice" id="notice_{f_UploadFileId}"></div>

                        <table class="pic_img_container" cellspacing="0"><tr><td align="center" valign="center"><img class="pic_slider_img" onclick="showOriImg('{f_UploadFilePath}')" idvalue="{f_UploadFileId}" src="{f_UploadFilePath}" style="cursor:pointer;" title="点击查看原始图片"/></td></tr></table>

                        <div class="pic_img_title" style="padding:3px 5px;">
                            <input class="input_box" idvalue="{f_UploadFileId}" title="{f_UploadFileTitle}" id="pic_title_{f_UploadFileId}" value="{f_UploadFileTitle}" onclick="SetEventForDocumentNewsPicTitle({f_UploadFileId})">
                        </div>
                        <div class="pic_img_state" style="padding:3px 5px;">
                            <div class="keep_pic" idvalue="{f_DocumentNewsPicId}" title="{f_ShowInPicSlider}" id="FormValues_{f_UploadFileId}"></div>
                            <script type="text/javascript">ModifyShowInPicSlider({f_UploadFileId}, {f_ShowInPicSlider}, "keep_pic")</script>
                            <img class="btn_modify btn_show_pic" idvalue="{f_DocumentNewsPicId}" title="在控件中显示" src="/system_template/{template_name}/images/manage/start.jpg"
                                 alt="{f_UploadFileId}" onclick="ModifyShowInPicSlider('{f_UploadFileId}', '1', 'modify_pic')"/>
                            <img class="btn_modify btn_hide_pic" idvalue="{f_DocumentNewsPicId}" title="在控件中隐藏" src="/system_template/{template_name}/images/manage/stop.jpg"
                                 alt="{f_UploadFileId}" onclick="ModifyShowInPicSlider('{f_UploadFileId}', '0', 'modify_pic')"/>
                            <a href="/default.php?secu=manage&mod=upload_file&m=modify&table_id={ChannelId}&site_id={SiteId}&upload_file_id={f_UploadFileId}">
                                <img class="btn_modify" src="/system_template/{template_name}/images/manage/edit.gif"
                                     title="编辑" />
                            </a>
                            <img class="btn_modify btn_delete_pic" idvalue="{f_UploadFileId}" id="{f_DocumentNewsPicId}" src="/system_template/{template_name}/images/manage/delete.jpg"
                                 alt="删除" onclick="DeleteDocumentNewsPic('{f_UploadFileId}')"/>
                            <div class="btn_update_title" idvalue="{f_UploadFileId}" title="{f_ShowInPicSlider}" id="update_pic_title_{f_UploadFileId}" onclick="AjaxUpdateDocumentNewsPicTitle({f_UploadFileId})" >点击修改</div>
                        </div>


                    </li>

                    ]]>
                </item>
            </icms>
            <li style="clear:left;"></li>
        </ul>
    </div>
    <input id="delete_pic_list" name="delete_pic_list" value="" type="hidden"/>
    <input id="modify_pic_list" name="modify_pic_list" value="" type="hidden"/>
    <input id="create_pic_list" name="create_pic_list" value="" type="hidden"/>
</div>
</td>
</tr>
</table>

<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td height="60" align="center">
            <input class="btn" value="确认并关闭" type="button" onclick="submitForm(1)"/>
            <!--<input class="btn" value="确认并继续" type="button" onclick="submitForm(0)"/>-->
            <input class="btn" value="返 回" type="button" onclick="closeTab()"/>
        </td>
    </tr>
</table>

</form>
</div>

</body>
</html>
