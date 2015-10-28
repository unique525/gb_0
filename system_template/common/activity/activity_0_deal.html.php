<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/xheditor-1.1.14/xheditor-1.1.14-zh-cn.min.js"></script>
    <script type="text/javascript" src="/system_js/color_picker.js"></script>
    <script type="text/javascript" src="/system_js/ajax_file_upload.js"></script>
    <script type="text/javascript" src="/system_js/upload_file.js"></script>

    <script type="text/javascript" src="/system_js/manage/activity/city.js" ></script>
    <script type="text/javascript" src="/system_js/manage/activity/activity.js" ></script>
    <script type="text/javascript">


        $().ready(function() {
            GetTimes("BeginDate","{BeginDate}");
            GetTimes("EndDate","{EndDate}");
            GetTimes("ApplyEndDate","{ApplyEndDate}");

        });


    </script>
    <script type="text/javascript">
        <!--
        var editor;
        var batchAttachWatermark = "0";

        var tableType = window.UPLOAD_TABLE_TYPE_ACTIVITY_PIC;
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
        };



        $(function(){

            if ($.browser.msie) {
                $('input:checkbox').click(function () {
                    this.blur();
                    this.focus();
                });
            }

            var editorHeight = $(window).height() - 220;
            editorHeight = parseInt(editorHeight);

            var f_ActivityContent = $('#f_ActivityContent');

            editor = f_ActivityContent.xheditor({
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

                    editor = $('#f_ActivityContent').xheditor({
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
            if ($('#f_ActivityTitle').val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("请输入活动名称");
                submit=0;
            }
            if(submit==1) {
                //处理时间
                SetTimes("BeginDate");
                SetTimes("EndDate");
                SetTimes("ApplyBeginDate");
                SetTimes("ApplyEndDate");
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
<div class="div_deal">
<form id="main_form" enctype="multipart/form-data"
      action="/default.php?secu=manage&mod=activity&m={method}&channel_id={ChannelId}&activity_id={ActivityId}&activity_type={ActivityType}&tab_index={TabIndex}"
      method="post">
<input type="hidden" id="f_ChannelId" name="f_ChannelId" value="{ChannelId}" />
<input type="hidden" id="f_ActivityType" name="f_ActivityType" value="{ActivityType}" />
<input type="hidden" id="f_ManageUserId" name="f_ManageUserId" value="{ManageUserId}" />
<input type="hidden" id="f_ManageUserName" name="f_ManageUserName" value="{ManageUserName}" />
<input type="hidden" id="f_CreateDate" name="f_CreateDate" value="{CreateDate}" />
<input type="hidden" id="f_UserId" name="f_UserId" value="{UserId}" />
<input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
<input type="hidden" id="f_UploadFiles" name="UploadFiles" value="" />
<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td class="spe_line" height="40" align="right">
            <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/>
            <input class="btn" value="确认并继续新增" style="display:{display}" type="button" onclick="submitForm(1)"/>
            <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
        </td>
    </tr>
</table>
<table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
<tr>
<td>
<div id="tabs" style="margin-left:4px;">
<ul>
    <li><a href="#tabs-1">活动内容</a></li>
    <li><a href="#tabs-2">属性</a></li>
    <!--<li><a href="#tabs-3">组图上传</a></li>-->
</ul>
<div id="tabs-1">
    <div class="spe_line" style="line-height: 40px;text-align: left;">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td style=" width: 60px;"><label for="f_ActivityTitle">标题：</label></td>
                <td style=" width: 600px;">
                    <input type="text" class="input_box" id="f_ActivityTitle" name="f_ActivityTitle" value="{ActivityTitle}" style="width:480px;font-size:14px; background-color: #ffffff;" maxlength="200" />
                </td>
            </tr>

        </table>
    </div>
    <div style=" margin-top:3px;">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="75%"><label for="f_ActivityContent"></label><textarea class="mceEditor" id="f_ActivityContent" name="f_ActivityContent" style=" width: 100%;">{ActivityContent}</textarea></td>
                <td style="vertical-align:top;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style=" border: solid 1px #cccccc; margin-left: 3px; padding: 2px;">
                        <tr>
                            <td class="spe_line" style="width:74px;height:35px;text-align: right;"><label for="f_UserName">发布人：</label></td>
                            <td class="spe_line" style="text-align: left">
                                <input type="text" class="input_box" id="f_UserName" name="f_UserName" value="{UserName}" style="width:95%;font-size:14px;" maxlength="50" />
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
                    </table>


                </td>
            </tr>
        </table>
    </div>
</div>
<div id="tabs-2">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
        <tr>
            <td class="spe_line" height="30" align="right"><label for="f_DirectUrl">直接转向网址：</label></td>
            <td colspan="3" class="spe_line"><input type="text" class="input_box" id="f_DirectUrl"
                                        name="f_DirectUrl" value="{DirectUrl}"
                                        style=" width: 70%;font-size:14px;" maxlength="200"/>
            </td>
        </tr>
        <tr>
            <td class="spe_line" style="width:160px;height:40px;text-align: right;">题图1：</td>
            <td colspan="3" class="spe_line" style="text-align: left">
                <input id="file_title_pic_1" name="file_title_pic_1" type="file" class="input_box" style="width:400px; background: #ffffff;" />
                <span id="preview_title_pic" class="show_title_pic" idvalue="{TitlePic1UploadFileId}" style="cursor:pointer">[预览]</span>

            </td>
        </tr>
        <tr>
            <td class="spe_line" style="width:160px;height:40px;text-align: right;">题图2：</td>
            <td colspan="3" class="spe_line" style="text-align: left">
                <input id="file_title_pic_2" name="file_title_pic_2" type="file" class="input_box" style="width:400px; background: #ffffff;" />
                <span id="preview_title_pic" class="show_title_pic" idvalue="{TitlePic2UploadFileId}" style="cursor:pointer">[预览]</span>

            </td>
        </tr>
        <tr>
            <td class="spe_line" style="width:160px;height:40px;text-align: right;">题图3：</td>
            <td colspan="3" class="spe_line" style="text-align: left">
                <input id="file_title_pic_3" name="file_title_pic_3" type="file" class="input_box" style="width:400px; background: #ffffff;" />
                <span id="preview_title_pic" class="show_title_pic" idvalue="{TitlePic3UploadFileId}" style="cursor:pointer">[预览]</span>

            </td>
        </tr>
        <tr>
            <td class="spe_line" style="width:160px;height:65px;text-align: right;"><label for="f_ActivityIntro">活动简介：</label></td>
            <td colspan="3" class="spe_line" style="text-align: left">
                <textarea id="f_ActivityIntro" name="f_ActivityIntro" style=" width: 600px; height: 200px;font-size:14px;">{ActivityIntro}</textarea>
            </td>
        </tr>
        <tr>
            <td class="spe_line" style="width:160px;height:35px;text-align: right;"><label for="f_ActivityClassId">活动分类：</label></td>
            <td colspan="3" class="spe_line" style="text-align: left">
                <select id="f_ActivityClassId" name="f_ActivityClassId">
                    <icms id="activity_class_list" type="list">
                        <item><![CDATA[
                            <option value="{f_ActivityClassId}">{f_ActivityClassName}</option>
                            ]]></item>
                    </icms>
                </select>
                {s_ActivityClassId}
            </td>
        </tr>
        <tr>
            <td class="spe_line" style="width:160px;height:65px;text-align: right;"><label for="f_ActivityRequest">活动要求：</label></td>
            <td colspan="3" class="spe_line" style="text-align: left">
                <textarea id="f_ActivityRequest" name="f_ActivityRequest" style=" width: 400px; height: 80px;font-size:14px;">{ActivityRequest}</textarea>
            </td>
        </tr>
        <tr>
            <td class="spe_line" style="width:160px;height:65px;text-align: right;"><label for="f_ActivityRemark">活动注意事项：</label></td>
            <td colspan="3" class="spe_line" style="text-align: left">
                <textarea id="f_ActivityRemark" name="f_ActivityRemark" style=" width: 400px; height: 80px;font-size:14px;">{ActivityRemark}</textarea>
            </td>
        </tr>
        <tr>
            <td class="spe_line" style="width:160px;height:35px;text-align: right;">活动地区：</td>
            <td colspan="3" class="spe_line" style="text-align: left"><div id="CityBox">
                    <!--<input type="hidden" id="f_province" name="f_province" value="{Province}" />
                    <input type="hidden" id="f_city" name="f_city" value="{City}" />-->
                    <script type="text/javascript">
                        showprovince('f_province', 'f_city', '{Province}', 'CityBox');
                        showcity('f_city', '{City}', 'f_province', 'CityBox');
                    </script>
                </div></td>
        </tr>
        <tr>
            <td class="spe_line" style="width:160px;height:35px;text-align: right;"><label for="f_ActivityAddress">活动地点：</label></td>
            <td colspan="3" class="spe_line" style="width:600px;text-align: left"><input type="text" class="input_box" id="f_ActivityAddress" name="f_ActivityAddress" value="{ActivityAddress}" style=" width: 600px;font-size:14px;" maxlength="100" /></td>
        </tr>
        <tr>
            <td class="spe_line" style="width:160px;height:65px;text-align: right;"><label for="f_AssemblyAddress">集合地点：</label></td>
            <td colspan="3" class="spe_line" style="text-align: left"><input type="text" class="input_box" id="f_AssemblyAddress" name="f_AssemblyAddress" value="{AssemblyAddress}" style=" width: 600px;font-size:14px;" maxlength="100" /></td>
        </tr>
        <tr>
            <td class="spe_line" style="height:35px;text-align: right;width:160px;"><label for="f_BeginDate">活动开始时间：</label></td>
            <td class="spe_line" colspan="3" style="text-align: left;width:550px;">
                <input type="text" class="GetDate" id="f_BeginDate" name="f_BeginDate" value="{BeginDate}"  style=" width: 85px;font-size:14px;" maxlength="20"  />
                <input type="text" class="input_number" style=" width:20px;font-size:14px;" id="f_BeginDateShowHour"  maxlength="2" value=""/><label for="f_BeginDateShowHour">时</label>
                <input type="text" class="input_number" style=" width:20px;font-size:14px;" id="f_BeginDateShowMinute"   maxlength="2" value=""  /><label for="f_BeginDateShowMinute">分</label>
                <input type="text" class="input_number" style=" width:20px;font-size:14px;" id="f_BeginDateShowSecond"   maxlength="2" value=""  /><label for="f_BeginDateShowSecond">秒</label>

                <label for="f_EndDate">至</label>
                <input type="text" class="GetDate" id="f_EndDate" name="f_EndDate" value="{EndDate}"  style=" width: 85px;font-size:14px;" maxlength="20"  />
                <input type="text" class="input_number" style=" width:20px;font-size:14px;" id="f_EndDateShowHour"  maxlength="2"    value="" />  <label for="f_EndDateShowHour">时</label>
                <input type="text" class="input_number" style=" width:20px;font-size:14px;" id="f_EndDateShowMinute"   maxlength="2" value="" /><label for="f_EndDateShowMinute">分</label>
                <input type="text" class="input_number" style=" width:20px;font-size:14px;" id="f_EndDateShowSecond"   maxlength="2" value="" /><label for="f_EndDateShowSecond">秒</label>

            </td>
        </tr>
        <tr>
            <td class="spe_line" style="width:160px;height:35px;text-align: right;"><label for="f_ApplyEndDate">活动报名截止时间：</label></td>
            <td colspan="3" class="spe_line" style="text-align: left">
                <input type="text" class="GetDate" id="f_ApplyEndDate" name="f_ApplyEndDate" value="{ApplyEndDate}"  style=" width: 85px;font-size:14px;" maxlength="20"  />
                <input type="text" class="input_number" style=" width:20px;font-size:14px;" id="f_ApplyEndDateShowHour"  maxlength="2"    value="" />  <label for="f_ApplyEndDateShowHour">时</label>
                <input type="text" class="input_number" style=" width:20px;font-size:14px;" id="f_ApplyEndDateShowMinute"   maxlength="2" value="" /><label for="f_ApplyEndDateShowMinute">分</label>
                <input type="text" class="input_number" style=" width:20px;font-size:14px;" id="f_ApplyEndDateShowSecond"   maxlength="2" value="" /><label for="f_ApplyEndDateShowSecond">秒</label>
            </td>
        </tr>
        <tr>
            <td class="spe_line" style="height:35px;text-align: right;width:160px;"><label for="f_UserCountLimit">人数限制：</label></td>
            <td class="spe_line" style="text-align: left;width:250px;"><input type="text" class="input_number" id="f_UserCountLimit" name="f_UserCountLimit" value="{UserCountLimit}" style=" width: 150px;font-size:14px;" maxlength="10" /></td>
            <td class="spe_line" style="height:35px;text-align: right;width:100px;"><label for="f_ActivityFee">活动费用：</label></td>
            <td class="spe_line" style="text-align: left"><input type="text" class="input_box" id="f_ActivityFee" name="f_ActivityFee" value="{ActivityFee}" style=" width: 150px;font-size:14px;" maxlength="10" /></td>
        </tr>
        <tr>
            <td class="spe_line" style="height:35px;text-align: right;"><label for="f_Contact">联系电话：</label></td>
            <td class="spe_line" style="text-align: left"><input type="text" class="input_number" id="f_Contact" name="f_Contact" value="{Contact}" style=" width: 150px;font-size:14px;" maxlength="200" /></td>
            <td class="spe_line" style="height:35px;text-align: right;"><label for="f_QQ">联系QQ：</label></td>
            <td class="spe_line" style="text-align: left"><input type="text" class="input_number" id="f_QQ" name="f_QQ" value="{QQ}" style=" width: 150px;font-size:14px;" maxlength="50" /></td>
        </tr>
        <tr>
            <td class="spe_line" style="height:35px;text-align: right;"><label for="f_Sort">排序数字：</label></td>
            <td class="spe_line" style="text-align: left"><input type="text" class="input_number" id="f_Sort" name="f_Sort" value="{Sort}" style=" width: 150px;font-size:14px;" maxlength="10" /></td>
            <td class="spe_line" style="height:35px;text-align: right;"><label for="f_Hit">点击数：</label></td>
            <td class="spe_line" style="text-align: left"><input type="text" class="input_number" id="f_Hit" name="f_Hit" value="{Hit}" style=" width: 150px;font-size:14px;" maxlength="10" /></td>
        </tr>
        <tr>
            <td class="spe_line" style="height:35px;text-align: right;"><label for="f_RecLevel">推荐等级：</label></td>
            <td class="spe_line" style="text-align: left"><input type="text" class="input_number" id="f_RecLevel" name="f_RecLevel" value="{RecLevel}" style=" width: 150px;font-size:14px;" maxlength="10" /></td>

            <td class="spe_line" style="width:160px;height:35px;text-align: right;"><label for="f_OpenComment">新闻评论：</label></td>
            <td class="spe_line" style="text-align: left">
                <select id="f_OpenComment" name="f_OpenComment">
                    <option value="40" >根据频道设置而定</option>
                    <option value="20" >允许但需要审核（先发后审）</option>
                    <option value="10" >允许但需要审核（先审后发）</option>
                    <option value="30" >自由评论</option>
                    <option value="0" >不允许</option>
                </select>
                {s_OpenComment}
            </td>
        </tr>
    </table>

    <div class="spe_line" style="line-height:35px">
        <div style="height:35px;float:left;margin-left:333px"><label for="f_IsGroupPic">比赛类活动时，评图是否以组图方式评选：</label></div>
        <div style="">
            <select id="f_IsGroupPic" name="f_IsGroupPic">
                <option value="0" >否</option>
                <option value="1" >是</option>
            </select>
            {s_IsGroupPic}
        </div>
    </div>
</div>

</div>
</td>
</tr>
</table>
<table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td colspan="2" height="30" align="center">
            <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/>
            <input class="btn" value="确认并继续新增" style="display:{display}" type="button" onclick="submitForm(1)"/>
            <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
        </td>
    </tr>
</table>
</form>
</div>
</body>

</html>