function FormatState(state){
    var result;
    switch(state){
        case "0":
            result = '<span>启用</span>';
            break;
        case "40":
            result = '<span style="color:red">停用</span>';
            break;
        default:
            result = '<span>启用</span>';
            break;
    }
    return result;
}



function ChangeState(idvalue,state){
    $.ajax({
        url:"/default.php?secu=manage&mod=user_group&m=modify_state",
        data:{state:state,user_group_id:idvalue},
        dataType:"jsonp",
        jsonp:"JsonpCallBack",
        success:function(data){
            if(data["result"] == 0){
                alert("修改失败，请联系管理员");
            }else{
                var state_div = $("#State_"+idvalue);
                state_div.html(FormatState(state));
            }
        }
    });
}

function submitForm(continueCreate) {
    if ($('#UserGroupName').val() == '') {
        $("#dialog_box").dialog({width: 300, height: 100});
        $("#dialog_content").html("请输入会员组名称");
    } else {
        if (continueCreate == 1) {
            $("#CloseTab").val("0");
        } else {
            $("#CloseTab").val("1");
        }
        $('#mainForm').submit();
    }
}

$(document).ready(function(){
    var btnCreate = $("#btn_create");
    btnCreate.css("cursor", "pointer");
    btnCreate.click(function(event) {
        event.preventDefault();
        var pageIndex = parseInt(Request["p"]);
        if (pageIndex <= 0) {
            pageIndex = 1;
        }
        window.location.href="/default.php?secu=manage&mod=user_group&m=create&site_id="+ parent.G_NowSiteId
            +"&p="+parent.G_NowPageIndex+"&ps="+parent.G_PageSize+"&tab_index="+parent.G_TabIndex;
    });

    $(".edit").click(function(){
        var userGroupId = $(this).attr("idvalue");
        window.location.href="/default.php?secu=manage&mod=user_group&user_group_id="+userGroupId+"&m=modify&site_id="+ parent.G_NowSiteId
            +"&p="+parent.G_NowPageIndex+"&ps="+parent.G_PageSize+"&tab_index="+parent.G_TabIndex;
    });

    $(".span_state").each(function(){
        var state = $(this).html();
        $(this).html(FormatState(state));
    });

    $(".div_start").click(function(){
        var idvalue = $(this).attr("idvalue");
        var state = 0;
        ChangeState(idvalue,state);
    });

    $(".div_stop").click(function(){
        var idvalue = $(this).attr("idvalue");
        var state = 40;
        ChangeState(idvalue,state);
    });
});