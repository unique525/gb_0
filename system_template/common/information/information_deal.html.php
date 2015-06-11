<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/xheditor-1.1.14/xheditor-1.1.14-zh-cn.min.js"></script>
    <script type="text/javascript" src="/system_js/color_picker.js"></script>
    <script type="text/javascript" src="/system_js/ajax_file_upload.js"></script>
    <script type="text/javascript" src="/system_js/upload_file.js"></script>



    <script type="text/javascript">
        <!--
        var editor;
        var batchAttachWatermark = "0";

        var tableType = window.UPLOAD_TABLE_TYPE_INFORMATION_CONTENT;
        var tableId = parseInt('{ChannelId}');

        //上传回调函数
        window.AjaxFileUploadCallBack = function(data){

        }

        $(function(){

            if ($.browser.msie) {
                $('input:checkbox').click(function () {
                    this.blur();
                    this.focus();
                });
            }

            var editorHeight = $(window).height() - 220;
            editorHeight = parseInt(editorHeight);

            var f_ActivityContent = $('#f_InformationContent');

            editor = f_ActivityContent.xheditor({
                tools:'full',
                height:editorHeight,
                upImgUrl:"",
                upImgExt:"jpg,jpeg,gif,png",
                localUrlTest:/^https?:\/\/[^\/]*?({manage_domain_rex})\//i,
                remoteImgSaveUrl:''
            });


            $('#tabs').tabs();







//远程抓图
            var cbSaveRemoteImage = $("#cbSaveRemoteImage");
            cbSaveRemoteImage.change(function(){
                if(cbSaveRemoteImage.prop("checked")==true){

                    f_ActivityContent.xheditor(false);

                    editor = f_ActivityContent.xheditor({
                        tools:'full',
                        height:editorHeight,
                        upImgUrl:"",
                        upImgExt:"jpg,jpeg,gif,png",
                        localUrlTest:/^https?:\/\/[^\/]*?({manage_domain_rex})\//i,
                        remoteImgSaveUrl:'/default.php?mod=upload_file&a=async_save_remote_image&table_type='+tableType+'&table_id='+tableId
                    });

                }else{

                    f_ActivityContent.xheditor(false);

                    editor = $('#f_InformationContent').xheditor({
                        tools:'full',
                        height:editorHeight,
                        upImgUrl:"",
                        upImgExt:"jpg,jpeg,gif,png",
                        localUrlTest:/^https?:\/\/[^\/]*?({manage_domain_rex})\//i,
                        remoteImgSaveUrl:''
                    });
                }
            });

//文件上传
            var btnUploadToContent = $("#btnUploadToContent");
            btnUploadToContent.click(function(){

                var fileElementId = 'file_upload_to_content';

                var attachWatermark = 0;
                if($("#attachwatermark").attr("checked")==true){
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


        //提交
        function submitForm(continueCreate) {
            var submit=1;
            if ($('#f_InformationTitle').val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("请输入标题");
                submit=0;
            }
            if(submit==1) {
                if (continueCreate == 1) {
                    $("#CloseTab").val("0");
                } else {
                    $("#CloseTab").val("1");
                }
                $('#main_form').submit();
            }
        }
        -->
    </script>
</head>
<body>
{common_body_deal}
<form id="main_form" enctype="multipart/form-data"
      action="/default.php?secu=manage&mod=information&m={method}&channel_id={ChannelId}&information_id={InformationId}&tab_index={TabIndex}"
      method="post">
<input type="hidden" id="f_ChannelId" name="f_ChannelId" value="{ChannelId}" />
<input type="hidden" id="f_CreateDate" name="f_CreateDate" value="{CreateDate}" />
<input type="hidden" id="f_UserId" name="f_UserId" value="{UserId}" />
<input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
<input type="hidden" id="f_UploadFiles" name="UploadFiles" value="" />
<table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td class="spe_line" height="40" align="right">
            <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/>
            <input class="btn" value="确认并继续新增" style="display:{display}" type="button" onclick="submitForm(1)"/>
            <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
        </td>
    </tr>
</table>
<div id="tabs" style="width:99%">
<ul>
    <li><a href="#tabs-1">分类信息内容</a></li>
    <li><a href="#tabs-2">管理</a></li>
    <!--<li><a href="#tabs-3">组图上传</a></li>-->
</ul>
<div id="tabs-1">
    <div class="spe_line" style="line-height: 40px;text-align: left;">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td style="width:20px;text-align: left;"><label for="f_InformationTitle">标题：</label></td>
                <td style=" width: 500px;text-align: left">
                    <input type="text" class="input_box" id="f_InformationTitle" name="f_InformationTitle" value="{InformationTitle}" style="width:480px;font-size:14px; background-color: #ffffff;" maxlength="200" />
                </td>
            </tr>
            <tr>
                <td class="spe_line" style="width:20px;height:40px;text-align: left;">题图：</td>
                <td colspan="3" class="spe_line" style="text-align: left">
                    <input id="file_title_pic_1" name="file_title_pic_1" type="file" class="input_box" style="width:400px; background: #ffffff;" />
                    <span id="preview_title_pic" class="show_title_pic" idvalue="{TitlePic1UploadFileId}" style="cursor:pointer">[预览]</span>

                </td>
            </tr>
        </table>
    </div>
    <div style=" margin-top:3px;">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="75%"><label for="f_InformationContent"></label><textarea class="mceEditor" id="f_InformationContent" name="f_InformationContent" style=" width: 100%;">{InformationContent}</textarea></td>
                <td style="vertical-align:top;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border: solid 1px #cccccc; margin-left: 3px; padding: 2px;">
                        <tr>
                            <td class="spe_line" style="width:74px;height:35px;text-align: right;"><label for="f_UserName">发布人：</label></td>
                            <td class="spe_line" style="text-align: left">
                                <input type="text" readonly="true" class="input_box" id="f_UserName" name="f_UserName" value="{UserName}" style="width:95%;font-size:14px;" maxlength="50" />
                            </td>
                        </tr>
                        <tr>
                            <td style="height:35px;">文件上传：</td>
                            <td align="left">
                                <input id="file_upload_to_content" name="file_upload_to_content" type="file" class="input_box" size="7" style="width:60%; background: #ffffff;" /> <img id="loading" src="/system_template/common/images/spinner2.gif" style="display:none;" /><input id="btnUploadToContent" type="button" value="上传" />
                            </td>
                        </tr>
                        <tr>
                            <td style="height:35px;"><label for="cbAttachWatermark">附加水印：</label></td>
                            <td align="left">
                                <input type="checkbox" id="cbAttachWatermark" name="cbAttachWatermark" /> (只支持jpg或jpeg图片)
                            </td>
                        </tr>
                        <tr>
                            <td style="height:35px;"><label for="cbSaveRemoteImage">远程抓图：</label></td>
                            <td align="left">
                                <input type="checkbox" id="cbSaveRemoteImage" name="cbSaveRemoteImage" /> (只支持jpg、jpeg、gif、png图片)
                            </td>
                        </tr>

                        <tr>
                            <td class="spe_line" style="height:35px;"><label for="f_ContactPhone">联系电话：</label></td>
                            <td class="spe_line" style="text-align: left"><input type="text" class="input_number" id="f_ContactPhone" name="f_ContactPhone" value="{ContactPhone}" style=" width: 150px;font-size:14px;" maxlength="200" /></td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="height:35px;"><label for="f_ContactQQ">联系QQ：</label></td>
                            <td class="spe_line" style="text-align: left"><input type="text" class="input_number" id="f_ContactQQ" name="f_ContactQQ" value="{ContactQQ}" style=" width: 150px;font-size:14px;" maxlength="50" /></td>
                        </tr>
                    </table>


                </td>
            </tr>
        </table>
    </div>
</div>
<div id="tabs-2">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">

        <tr>
            <td class="spe_line" style="height:35px;text-align: right;"><label for="f_Sort">排序数字：</label></td>
            <td class="spe_line" style="text-align: left"><input type="text" class="input_number" id="f_Sort" name="f_Sort" value="{Sort}" style=" width: 150px;font-size:14px;" maxlength="10" /></td>
        </tr>
        <tr>
            <td class="spe_line" style="height:35px;text-align: right;"><label for="f_State">状态：</label></td>
            <td class="spe_line" style="text-align: left">
                <select id="f_State" name="f_State">
                    <option value="0" >启用</option>
                    <option value="100" >停用</option>
                </select>
                {s_State}
            </td>
        </tr>
    </table>
</div>
<!--<div id="tabs-3">
    <div id="uploader">
        <p>您的浏览器不支持 Flash, SilverLight, Gears, BrowserPlus 或 HTML5，不能使用组图上传功能</p>
    </div>
    <div class="spe_line" style=" line-height: 30px;"> <label for="f_ShowPicMethod">使用组图控件展示内容中的图片</label>
        <select id="f_ShowPicMethod" name="f_ShowPicMethod">
            <option value="0" >关闭</option>
            <option value="1" >开启</option>
        </select>
        {s_ShowPicMethod}
    </div>
</div>-->

</div>
<div id="bot_button">
    <div style="padding-top:3px;">
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td colspan="2" height="30" align="center">
                    <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/>
                    <input class="btn" value="确认并继续新增" style="display:{display}" type="button" onclick="submitForm(1)"/>
                    <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                </td>
            </tr>
        </table>
    </div>
</div>
</form>

</body>

</html>