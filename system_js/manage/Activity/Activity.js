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


/**
 * 时间按单位分割
 * @param timeName 时间dom id
 * @param timeValue 完整时间string
 * @return
 */
function GetTimes(timeName,timeValue){
    var strDate=timeValue.substr(0,10);
    var strHour=timeValue.substr(11,2);
    var strMin=timeValue.substr(14,2);
    var strSec=timeValue.substr(17,2);
    var Time = $("#f_"+timeName);
    Time.val(strDate);
    $("#f_"+timeName+"ShowHour").val(strHour);
    $("#f_"+timeName+"ShowMinute").val(strMin);
    $("#f_"+timeName+"ShowSecond").val(strSec);


}

/**
 * 合并时间单位
 * @param timeName 时间dom id
 * @return
 */
function SetTimes(timeName){
    var Time = $("#f_"+timeName);
    if(Time.val()){
        Time.val(Time.val().substr(0,10)+' '+$("#f_"+timeName+"ShowHour").val()+':'+$("#f_"+timeName+"ShowMinute").val()+':'+$("#f_"+timeName+"ShowSecond").val());
    }
}


/**
 * 格式化状态值
 * @param state 状态
 * @return string
 */
function FormatVoteState(state){
    switch (state){
        case "0":
            return "启用";
            break;
        case "100":
            return "<"+"span style='color:#990000'>停用<"+"/span>";
            break;
        default :
            return "未知";
            break;
    }
}

/**
 * 修改状态值
 * @param state 状态
 * @return
 */
function ModifyState(idvalue, state) {
    $.ajax({
        url:"/default.php?secu=manage&mod=activity&m=modify_state",
        data:{state:state,activity_id:idvalue},
        dataType:"jsonp",
        jsonp:"jsonpcallback",
        success:function(data){
            if (parseInt(data["result"]) > 0) {
                $("#state_" + idvalue).html(FormatVoteState(state));
            }
            else alert("修改失败，请联系管理员");
        }
    });
}