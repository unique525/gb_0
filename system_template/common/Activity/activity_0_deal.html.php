<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
{common_head}
    <script type="text/javascript" src="/system_js/manage/Activity/city.js" ></script>
    <script type="text/javascript" src="/system_js/manage/Activity/datepicker/Wdatepicker.js" ></script>
    <script type="text/javascript" src="/system_js/manage/Activity/aActivity.js" ></script>
    <script type="text/javascript">
        function submitForm(continueCreate) {
            var submit=1;
            if ($('#f_ActivityName').val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("请输入活动名称");
                submit=0;
            }
            /*var province=$('#province').val();
            if(province == ''){
                alert("请选择省份");
                submit = 0;
            }
            $('#f_province').val(province);
            if($('#city').val() == ''){
                alert("请选择城市");
                submit = 0;
            }
            if($('#f_ActivityClass').val() == ''){
                alert("活动分类为空");
                submit = 0;
            }

            var ActivityPlace=$('#f_ActivityPlace');
            if(ActivityPlace.val() == ''){
                alert('请输入活动地点！');
                ActivityPlace.focus();
                submit = 0;
                return;
            }
            var MeetingPlace=$('#f_MeetingPlace');
            if(MeetingPlace.val() == ''){
                alert('请输入集合地点！');
                MeetingPlace.focus();
                submit = 0;
                return;
            }
            /*var startTime = $("f_StartTime").val();
            var endTime = $("f_EndTime").val();
            var signUpStartTime = $("f_SignUpStartTime").val();
            var signUpDeadLine = $("f_SignUpDeadLine").val();

            if(startTime >  endTime ){
                alert('开始时间(集合时间)不能大于活动结束时间!);
                submit = 0;
                return;
            }

            if(signUpDeadLine >  endTime ){
                alert('报名截止时间不能大于活动结束时间！);
                submit = 0;
                return;
            }

            if( signUpStartTime >=  signUpDeadLine ){
                alert('报名开始时间不能大于报名截止时间！);
                submit = 0;
                return;
            }*/
            if(submit==1) {
                //处理时间
                SetTimes("StartTime");
                SetTimes("EndTime");
                SetTimes("SignUpStartTime");
                SetTimes("SignUpDeadLine");
                if (continueCreate == 1) {
                    $("#CloseTab").val("0");
                } else {
                    $("#CloseTab").val("1");
                }
                $('#main_form').submit();
            }
        }

        function SetTimes(timeName){
            var Time = $("#f_"+timeName);
            Time.val(Time.val().substr(0,10)+' '+$("#f_"+timeName+"ShowHour").val()+':'+$("#f_"+timeName+"ShowMinute").val()+':'+$("#f_"+timeName+"ShowSecond").val())
            alert(Time.val());
        }
    </script>

    </head>
    <body>
    {common_body_deal}
        <form id="main_form" enctype="multipart/form-data"
              action="/default.php?secu=manage&mod=activity&m={method}&channel_id={ChannelId}&activity_id={ActivityId}&activity_type={ActivityType}&tab_index={TabIndex}"
              method="post">
            <input type="hidden" id="f_ChannelId" name="f_ChannelId" value="{ChannelId}" />
            <input type="hidden" id="f_ActivityType" name="f_ActivityType" value="{ActivityType}" />
            <input type="hidden" id="f_ManageUserId" name="f_ManageUserId" value="{ManageUserId}" />
            <input type="hidden" id="f_ManageUserName" name="f_ManageUserName" value="{ManageUserName}" />
            <input type="hidden" id="f_CreateDate" name="f_CreateDate" value="{CreateDate}" />
            <input type="hidden" id="f_UserId" name="f_UserId" value="{UserId}" />
            <input type="hidden" id="f_UserName" name="f_UserName" value="{UserName}" />
            <input type="hidden" id="f_TitlePicUploadFileId" name="f_TitlePicUploadFileId" value="{TitlePicUploadFileId}" />
            <input type="hidden" id="f_UploadFile" name="f_UploadFile" value="{UploadFile}" />
            <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
            <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="spe_line" height="40" align="right">
                        <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/>
                        <input class="btn" value="确认并继续新增" style="display:{display}" type="button" onclick="submitForm(1)"/>
                        <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                    </td>
                </tr>
            </table>
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">活动内容</a></li>
                    <li><a href="#tabs-2">属性</a></li>
                    <li><a href="#tabs-3">组图上传</a></li>
                </ul>
                <div id="tabs-1">
                    <div class="spe_line" style="line-height: 40px;text-align: left;">
                        <table width="750px" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="width:100px;"><label for="f_ActivityName">活动标题：</label></td>
                                <td>
                                    <input type="text" class="input_box" id="f_ActivityName" name="f_ActivityName" value="{ActivityName}" style=" width: 350px;font-size:14px;" maxlength="200" />
                                    <input type="hidden" id="s_UploadFile" name="s_UploadFile" value="{UploadFile}" />
                                </td>
                                <td style="width:300px;" colspan="2"><div id="CustomWidget" style="display:none;">
                                        <div id="ColorSelector2"><div style="background-color: #000000"></div></div>
                                        <div id="ColorPickerHolder2">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>介绍图片网址：</td>
                                <td>
                                    <input id="file_title_pic" name="file_title_pic" type="file" class="input_box" style="width:60%; background: #ffffff;" />
                                    <span id="preview_title_pic" class="show_title_pic" idvalue="{TitlePicUploadFileId}" style="cursor:pointer">[预览]</span>

                                </td>
                            </tr>
                        </table>
                    </div>
                    <div  class="spe_line" style="line-height: 40px;text-align: left;">
                        <table width="750px" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td><label for="f_ActivityContent">活动内容：</label></td>
                            </tr>
                            <tr>
                                <td width="98%"><textarea id="f_ActivityContent" name="f_ActivityContent" style=" width: 100%;">{ActivityContent}</textarea></td>
                            </tr>
                        </table>

                    </div>
                </div>
                <div id="tabs-2">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
                        <tr>
                            <td class="spe_line" style="width:160px;height:65px;text-align: right;"><label for="f_ActivityIntro">活动简介：</label></td>
                            <td colspan="3" class="spe_line" style="text-align: left">
                                <textarea id="f_ActivityIntro" name="f_ActivityIntro" style=" width: 600px; height: 200px;font-size:14px;">{ActivityIntro}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:160px;height:35px;text-align: right;"><label for="f_ActivityClass">活动分类：</label></td>
                            <td colspan="3" class="spe_line" style="text-align: left">
                                <input type="text" id="f_ActivityClass" name="f_ActivityClass" value="{ActivityClass}" />
                                <!--<select id="C" name="f_ActivityClassId">
                                    <icms id="ActivityClassList" type="list"><item><![CDATA[
                                            <option  value="{f_activityclassid}" {s_activityclassid_{f_activityclassid}}>{f_ActivityClassName}</option>
                                            ]]></item></icms>
                                </select>-->
                            </td>
                        </tr>
                        <tr>
                        <td class="spe_line" style="width:160px;height:65px;text-align: right;"><label for="f_ActivityNotice">活动注意事项：</label></td>
                        <td colspan="3" class="spe_line" style="text-align: left">
                            <textarea id="f_ActivityNotice" name="f_ActivityNotice" style=" width: 400px; height: 80px;font-size:14px;">{ActivityNotice}</textarea>
                        </td>
                    </tr>
                        <tr>
                            <td class="spe_line" style="width:160px;height:35px;text-align: right;">活动地区：</td>
                            <td colspan="3" class="spe_line" style="text-align: left"><div id="CityBox">
                                    <input type="hidden" id="f_province" name="f_province" value="{province}" />
                                    <input type="hidden" id="f_city" name="f_city" value="{city}" />
                                    <script type="text/javascript">
                                        showprovince('province', 'city', '{province}', 'CityBox');
                                        showcity('city', '{city}', 'province', 'CityBox');
                                    </script>
                                </div></td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:160px;height:35px;text-align: right;"><label for="f_ActivityPlace">活动地点：</label></td>
                            <td colspan="3" class="spe_line" style="width:600px;text-align: left"><input type="text" class="input_box" id="f_ActivityPlace" name="f_ActivityPlace" value="{ActivityPlace}" style=" width: 600px;font-size:14px;" maxlength="100" /></td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:160px;height:65px;text-align: right;"><label for="f_MeetingPlace">集合地点：</label></td>
                            <td colspan="3" class="spe_line" style="text-align: left"><input type="text" class="input_box" id="f_MeetingPlace" name="f_MeetingPlace" value="{MeetingPlace}" style=" width: 600px;font-size:14px;" maxlength="100" /></td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="height:35px;text-align: right;width:160px;"><label for="f_StartTime">活动开始时间：</label></td>
                            <td class="spe_line" colspan="3" style="text-align: left;width:550px;">
                                <input type="text" class="WDate" id="f_StartTime" name="f_StartTime" value="{StartTime}" onClick="WdatePicker()" style=" width: 85px;font-size:14px;" maxlength="20"  />
                                <input type="text" class="input_number" style=" width:20px;font-size:14px;" id="f_StartTimeShowHour"  maxlength="2" value=""/><label for="f_StartTimeShowHour">时</label>
                                <input type="text" class="input_number" style=" width:20px;font-size:14px;" id="f_StartTimeShowMinute"   maxlength="2" value=""  /><label for="f_StartTimeShowMinute">分</label>
                                <input type="text" class="input_number" style=" width:20px;font-size:14px;" id="f_StartTimeShowSecond"   maxlength="2" value=""  /><label for="f_StartTimeShowSecond">秒</label>

                                <label for="f_EndTime">至</label>
                                <input type="text" class="WDate" id="f_EndTime" name="f_EndTime" value="{EndTime}" onClick="WdatePicker()" style=" width: 85px;font-size:14px;" maxlength="20"  />
                                  <input type="text" class="input_number" style=" width:20px;font-size:14px;" id="f_EndTimeShowHour"  maxlength="2"    value="" />  <label for="f_EndTimeShowHour">时</label>
                                <input type="text" class="input_number" style=" width:20px;font-size:14px;" id="f_EndTimeShowMinute"   maxlength="2" value="" /><label for="f_EndTimeShowMinute">分</label>
                                <input type="text" class="input_number" style=" width:20px;font-size:14px;" id="f_EndTimeShowSecond"   maxlength="2" value="" /><label for="f_EndTimeShowSecond">秒</label>

                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:160px;height:35px;text-align: right;"><label for="f_SignUpStartTime">活动报名开始时间：</label></td>
                            <td colspan="3" class="spe_line" style="text-align: left;width:400px;">
                                <input type="text" class="WDate" id="f_SignUpStartTime" name="f_SignUpStartTime" value="{SignUpStartTime}" onClick="WdatePicker()" style=" width: 85px;font-size:14px;" maxlength="20"  />
                                <input type="text" class="input_number" style=" width:20px;font-size:14px;" id="f_SignUpStartTimeShowHour"  maxlength="2"    value="" />  <label for="f_SignUpStartTimeShowHour">时</label>
                                <input type="text" class="input_number" style=" width:20px;font-size:14px;" id="f_SignUpStartTimeShowMinute"   maxlength="2" value="" /><label for="f_SignUpStartTimeShowMinute">分</label>
                                <input type="text" class="input_number" style=" width:20px;font-size:14px;" id="f_SignUpStartTimeShowSecond"   maxlength="2" value="" /><label for="f_SignUpStartTimeShowSecond">秒</label>

                                </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:160px;height:35px;text-align: right;"><label for="f_SignUpDeadLine">活动报名截止时间：</label></td>
                            <td colspan="3" class="spe_line" style="text-align: left">
                                <input type="text" class="WDate" id="f_SignUpDeadLine" name="f_SignUpDeadLine" value="{SignUpDeadLine}" onClick="WdatePicker()" style=" width: 85px;font-size:14px;" maxlength="20"  />
                                <input type="text" class="input_number" style=" width:20px;font-size:14px;" id="f_SignUpDeadLineShowHour"  maxlength="2"    value="" />  <label for="f_SignUpDeadLineShowHour">时</label>
                                <input type="text" class="input_number" style=" width:20px;font-size:14px;" id="f_SignUpDeadLineShowMinute"   maxlength="2" value="" /><label for="f_SignUpDeadLineShowMinute">分</label>
                                <input type="text" class="input_number" style=" width:20px;font-size:14px;" id="f_SignUpDeadLineShowSecond"   maxlength="2" value="" /><label for="f_SignUpDeadLineShowSecond">秒</label>
                                -->
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="height:35px;text-align: right;width:160px;"><label for="f_UserLimit">人数限制：</label></td>
                            <td class="spe_line" style="text-align: left;width:250px;"><input type="text" class="input_number" id="f_UserLimit" name="f_UserLimit" value="{UserLimit}" style=" width: 150px;font-size:14px;" maxlength="10" /></td>
                            <td class="spe_line" style="height:35px;text-align: right;width:100px;"><label for="f_ActivityCost">均价：</label></td>
                            <td class="spe_line" style="text-align: left"><input type="text" class="input_box" id="f_ActivityCost" name="f_ActivityCost" value="{ActivityCost}" style=" width: 150px;font-size:14px;" maxlength="10" /></td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="height:35px;text-align: right;"><label for="f_PhoneOfLeader">联系电话：</label></td>
                            <td class="spe_line" style="text-align: left"><input type="text" class="input_number" id="f_PhoneOfLeader" name="f_PhoneOfLeader" value="{PhoneOfLeader}" style=" width: 150px;font-size:14px;" maxlength="200" /></td>
                            <td class="spe_line" style="height:35px;text-align: right;"><label for="f_QQOfLeader">联系QQ：</label></td>
                            <td class="spe_line" style="text-align: left"><input type="text" class="input_number" id="f_QQOfLeader" name="f_QQOfLeader" value="{QQOfLeader}" style=" width: 150px;font-size:14px;" maxlength="50" /></td>
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

                </div>
                <div id="tabs-3">
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