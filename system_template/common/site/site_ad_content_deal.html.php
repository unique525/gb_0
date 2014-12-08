<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/xheditor-1.1.14/xheditor-1.1.14-zh-cn.min.js"></script>
    <script type="text/javascript" src="/system_js/color_picker.js"></script>
    <script type="text/javascript" src="/system_js/ajax_file_upload.js"></script>
    <script type="text/javascript" src="/system_js/upload_file.js"></script>

    <script type="text/javascript" src="/system_js/manage/site/site_ad.js"></script>
    <script type="text/javascript">
        <!--
        var editor;
        var batchAttachWatermark = "0";

        var tableType = window.UPLOAD_TABLE_TYPE_SITE_AD_CONTENT;
        var tableId = parseInt('{SiteAdId}');


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

            var f_SiteAdContent = $('#f_SiteAdContent');

            editor = f_SiteAdContent.xheditor({
                tools:'full',
                height:editorHeight,
                upImgUrl:"",
                upImgExt:"jpg,jpeg,gif,png",
                localUrlTest:/^https?:\/\/[^\/]*?({manage_domain_rex})\//i,
                remoteImgSaveUrl:''
            });


            $('#tabs').tabs();

            //日期控件
            $(".GetDate").datepicker({
                dateFormat: 'yy-mm-dd',
                numberOfMonths: 3,
                showButtonPanel: true
            });


            ///////////////////加粗/////////////////////////
            var cbTitleBold = $("#cbTitleBold");
            cbTitleBold.click(function() {
                ChangeToBold();
            });
            //
            //加载BOLD
            var bold = $("#f_SiteAdContentTitleBold").val();
            if(bold == "bold"){
                cbTitleBold.attr('checked',true);
                ChangeToBold();
            }

            ///////////////////
            //加载TITLE COLOR
            var titleColor = $("#f_SiteAdContentTitleColor").val();
            if(titleColor.length>=3){
                $("#f_SiteAdContentTitle").css("color",titleColor);
            }



//远程抓图
            var cbSaveRemoteImage = $("#cbSaveRemoteImage");
            cbSaveRemoteImage.change(function(){
                if(cbSaveRemoteImage.prop("checked")==true){

                    f_SiteAdContent.xheditor(false);

                    editor = f_SiteAdContent.xheditor({
                        tools:'full',
                        height:editorHeight,
                        upImgUrl:"",
                        upImgExt:"jpg,jpeg,gif,png",
                        localUrlTest:/^https?:\/\/[^\/]*?({manage_domain_rex})\//i,
                        remoteImgSaveUrl:'/default.php?mod=upload_file&a=async_save_remote_image&table_type='+tableType+'&table_id='+tableId
                    });

                }else{

                    f_SiteAdContent.xheditor(false);

                    editor = $('#f_SiteAdContent').xheditor({
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
                var fUploadFile = $("#f_UploadFiles");

                var attachWatermark = 0;
                if($("#attachwatermark").attr("checked")==true){
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




        });


        function ChangeToBold(){

            if($("#cbTitleBold").attr('checked')){
                $("#f_DocumentNewsTitleBold").val("bold");
                $("#f_DocumentNewsTitle").css("font-weight","bold");
            }else{
                $("#f_DocumentNewsTitleBold").val("normal");
                $("#f_DocumentNewsTitle").css("font-weight","normal");
            }

        }

        //取第一张为题图
        function SetTitlePic(){
            var obj = $('<div></div>');
            obj.html(editor.getSource());
            var images=obj.find("img");
            var src=images.eq(0).attr("src");

        }

        //提交
        function submitForm(continueCreate) {
            SetTitlePic();
            var submit=1;
            if ($('#f_SiteAdContentTitle').val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("请输入广告标题");
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
      action="/default.php?secu=manage&mod=site_ad_content&m={method}&site_ad_content_id={SiteAdContentId}&site_ad_id={SiteAdId}&tab_index={TabIndex}"
      method="post">
<input type="hidden" id="f_ManageUserId" name="f_ManageUserId" value="{ManageUserId}" />
<input type="hidden" id="f_SiteAdId" name="f_SiteAdId" value="{SiteAdId}" />
<input type="hidden" id="f_CreateDate" name="f_CreateDate" value="{CreateDate}" />
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
<div id="tabs-1">
    <div class="spe_line" style="line-height: 40px;text-align: left;">
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td style=" width: 60px;"><label for="f_SiteAdContentTitle">&nbsp;标题：</label></td>
                <td style="">
                    <input type="text" class="iColorPicker input_box" id="f_SiteAdContentTitle" name="f_SiteAdContentTitle" value="{SiteAdContentTitle}" style="width:480px;font-size:14px; background-color: #ffffff;" maxlength="200" />
                    <input type="hidden" id="f_SiteAdContentTitleColor" name="f_SiteAdContentTitleColor" value="{SiteAdContentTitleColor}"  />
                    <input type="hidden" id="f_SiteAdContentTitleBold" name="f_SiteAdContentTitleBold" value="{SiteAdContentTitleBold}"  />
                </td>
            </tr>

        </table>
    </div>
    <div style=" margin-top:3px;">
        <table width="99%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="75%">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border: solid 1px #cccccc; margin-left: 3px; padding: 2px;">
                        <tr>
                            <td class="spe_line" colspan="2">
                                <label for="f_SiteAdContent"></label><textarea class="mceEditor" id="f_SiteAdContent" name="f_SiteAdContent" style=" width: 100%;">{SiteAdContent}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="height:35px;">
                                <div style="float: left">文件上传：</div>
                                <div style="width:600px;float: left">
                                    <input id="file_upload_to_content" name="file_upload_to_content" type="file" class="input_box" size="7" style="width:60%; background: #ffffff;" /> <img id="loading" src="/system_template/common/images/spinner2.gif" style="display:none;" /><input id="btnUploadToContent" type="button" value="上传" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="height:35px;">
                                        <div style="float: left"><label for="cbAttachWatermark">附加水印：</label></div>
                                        <div style="float: left">
                                            <input type="checkbox" id="cbAttachWatermark" name="cbAttachWatermark" /> (只支持jpg或jpeg图片)
                                        </div>
                                        <div style="margin-left:10px;float: left"><label for="cbSaveRemoteImage">远程抓图：</label></div>
                                        <div style="float: left">
                                            <input type="checkbox" id="cbSaveRemoteImage" name="cbSaveRemoteImage" /> (只支持jpg、jpeg、gif、png图片)
                                        </div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="vertical-align:top;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border: solid 1px #cccccc; margin-left: 3px; padding: 2px;">
                        <tr>
                            <td class="spe_line" style="width:74px;height:35px;text-align: right;"><label for="f_SiteAdUrl">链接地址：</label></td>
                            <td class="spe_line" style="text-align: left">
                                <input type="text" class="input_box" id="f_SiteAdUrl" name="f_SiteAdUrl" value="{SiteAdUrl}"  style=" width: 200px;font-size:14px;" maxlength="500"  />
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:74px;height:35px;text-align: right;"><label for="f_SiteAdType">广告类型：</label></td>
                            <td class="spe_line" style="text-align: left">

                                <select id="f_SiteAdType" name="f_SiteAdType">
                                    <option value="0" selected="selected">GIF</option>
                                    <option value="1">SWF默认模式</option>
                                    <option value="2">SWF_透明</option>
                                    <option value="3">SWF_降级</option>
                                </select>
                                {s_SiteAdType}
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:74px;height:35px;text-align: right;"><label for="f_BeginDate">开始时间：</label></td>
                            <td class="spe_line" style="text-align: left">
                                <input type="text" class="GetDate" id="f_BeginDate" name="f_BeginDate" value="{BeginDate}"  style=" width: 85px;font-size:14px;" maxlength="20"  />
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:74px;height:35px;text-align: right;"><label for="f_EndDate">结束时间：</label></td>
                            <td class="spe_line" style="text-align: left">
                                <input type="text" class="GetDate" id="f_EndDate" name="f_EndDate" value="{EndDate}"  style=" width: 85px;font-size:14px;" maxlength="20"  />
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:74px;height:35px;text-align: right;"><label for="f_OpenCount">统计：</label></td>
                            <td class="spe_line" style="text-align: left">
                                    <select id="f_OpenCount" name="f_OpenCount">
                                        <option value="1">启用</option>
                                        <option value="0" selected="selected">停用</option>
                                    </select>
                                    (是否对点击数进行统计)
                                    {s_OpenCount}
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:74px;height:35px;text-align: right;"><label for="f_OpenVirtualClick">虚拟点击：</label></td>
                            <td class="spe_line" style="text-align: left">
                                <select id="f_OpenVirtualClick" name="f_OpenVirtualClick">
                                    <option value="0" >停用</option>
                                    <option value="1">启用</option>
                                </select>
                                (是否启用虚拟点击)
                                {s_OpenVirtualClick}
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:74px;height:35px;text-align: right;"><label for="f_VirtualClickLimit">点击次数：</label></td>
                            <td class="spe_line" style="text-align: left">
                                <input type="text" class="input_box" id="f_VirtualClickLimit" name="f_VirtualClickLimit" value="{VirtualClickLimit}"  style=" width: 60px;font-size:14px;" maxlength="20"  />
                                (单位为：n次/每小时)
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:74px;height:35px;text-align: right;"><label for="f_State">状态：</label></td>
                            <td class="spe_line" style="text-align: left">
                                <select id="f_State" name="f_State">
                                    <option value="0" selected="selected">启用</option>
                                    <option value="100">停用</option>
                                </select>
                                {s_State}
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:74px;height:35px;text-align: right;"><label for="f_ResidenceTime">停留时间：</label></td>
                            <td class="spe_line" style="text-align: left">
                                <input type="text" class="input_box" id="f_ResidenceTime" name="f_ResidenceTime" value="{ResidenceTime}"  style=" width: 60px;font-size:14px;" maxlength="20"  />
                                (单位为：秒)
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:74px;height:35px;text-align: right;"><label for="f_Sort">排序：</label></td>
                            <td class="spe_line" style="text-align: left">
                                <input type="text" class="input_box" id="f_Sort" name="f_Sort" value="{Sort}"  style=" width: 60px;font-size:14px;" maxlength="20"  />
                                (数值越大越靠前)
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:74px;height:35px;text-align: right;"><label for="f_AddedVirtualClickCount">外挂虚拟点击数：</label></td>
                            <td class="spe_line" style="text-align: left">
                                <input type="text" class="input_box" id="f_AddedVirtualClickCount" name="f_AddedVirtualClickCount" value="{AddedVirtualClickCount}"  style=" width: 60px;font-size:14px;" maxlength="20"  />
                            </td>
                        </tr>
                    </table>


                </td>
            </tr>
        </table>
    </div>
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