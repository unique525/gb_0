<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/user/user_order.js"></script>
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui-timepicker-addon.js"></script>
    <script type="text/javascript">
        $(function(){
            var isCreate = true;
            $("#create").click(function(){
                if(isCreate){
                    isCreate = false;
                    var create_table = '<li>'+
                        '<table width="100%" cellpadding="0" cellspacing="0" id="create_table">'+
                        '<tr class="grid_item2">'+
                        '<td class="spe_line" height="30" width="80" align="center" style="">' +
                        '<input type="text" class="input_box" id="accept_person_name" style="width:65px">' +
                        '</td>'+
                        '<td class="spe_line" height="30" width="600" align="center">' +
                            '<input type="text" class="input_box" id="accept_address" style="width:575px">' +
                        '</td>'+
                        '<td class="spe_line" height="30" width="100" align="center">' +
                        '<input type="text" class="input_box" id="accept_tel"  style="width:85px">' +
                        '</td>'+
                        '<td class="spe_line" height="30" width="100" align="center">' +
                        '<input type="text" class="input_box" id="accept_time"  style="width:85px">' +
                        '</td>'+
                        '<td class="spe_line" height="30" width="80" align="center">' +
                        '<input type="text" class="input_box" id="send_company"  style="width:65px">' +
                        '</td>'+
                        '<td class="spe_line" height="30" align="center">'+
                        '<input type="button" class="btn" id="sub" value="确定">&nbsp;&nbsp;&nbsp;&nbsp;'+
                        '<input type="button" class="btn" id="cancel" value="取消">'+
                        '</td>'+
                        '</tr>'+
                        '</table>'+
                        '</li>';
                    var type_list_html = $("#type_list").html();
                    if(type_list_html.indexOf("还没有任何发货记录.") >= 0){
                        $("#type_list").html(create_table);
                    }else{
                        $("#type_list").append(create_table);
                    }

                    $("#accept_time").datetimepicker({
                        showSecond: true,
                        timeFormat: 'hh:mm:ss',
                        stepHour: 1,
                        stepMinute: 1,
                        stepSecond: 1
                    });

                    $("#sub").click(function(){
                        var acceptPersonName = $("#accept_person_name").val();
                        var acceptAddress = $("#accept_address").val();
                        var acceptTel = $("#accept_tel").val();
                        var acceptTime = $("#accept_time").val();
                        var acceptCompany = $("#send_company").val();
                        $.ajax({
                            type:"post",
                            url:"/default.php?mod=user_order_send&m=create",
                            data:{
                                acceptPersonName:acceptPersonName,
                                acceptAddress:acceptAddress,
                                acceptTel:acceptTel,
                                acceptTime:acceptTime,
                                acceptCompany:acceptCompany,
                                siteId:parent.parent.G_NowSiteId,
                                userOrderId:{UserOrderId}
                            },
                            dataType:"jsonp",
                            jsonp:"jsonpcallback",
                            success:function(data){
                                var result = data["result"];
                                if(result > 0 ){
                                    window.location.href=window.location.href;
                                }else{
                                    alert("新增失败");
                                }
                            }
                        });
                    });

                    $("#cancel").click(function(){
                        isCreate = true;
                        $("#create_table").remove();
                    });
                }else{
                    alert("请逐条增加");
                }
            });
        });
    </script>
</head>
<body>
<div class="div_list">
    <div style="width:99%;">
        <div class="btn" id="create" style="float:left;width:50px;text-align: center">新增</div>
        <div style="float:left;width:10px;height:5px;"></div>
        <div class="btn" id="modify" style="float:left;width:50px;text-align: center">修改</div>
        <div style="clear:left"></div>
    </div>
    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
        <tr  class="grid_title2">
            <td class="spe_line" height="30" width="80" align="center">接收人姓名</td>
            <td class="spe_line" height="30" width="600" align="center">接收地址</td>
            <td class="spe_line" height="30" width="100" align="center">接收人电话</td>
            <td class="spe_line" height="30" width="100" align="center">接收时间</td>
            <td class="spe_line" height="30" width="80" align="center">公司名</td>
            <td class="spe_line" height="30" align="center"></td>
        </tr>
    </table>
    <ul id="type_list">
        <icms id="user_order_send_list">
            <item>
                <![CDATA[
                        <tr class="grid_item2" id="user_order_send_{f_UserOrderSendId}">
                            <td class="spe_line" height="30" width="80" align="center">{f_AcceptPersonName}</td>
                            <td class="spe_line" height="30" width="600" align="center">{f_AcceptAddress}</td>
                            <td class="spe_line" height="30" width="100" align="center">{f_AcceptTel}</td>
                            <td class="spe_line" height="30" width="100" align="center">{f_AcceptTime}</td>
                            <td class="spe_line" height="30" width="80" align="center">{f_SendCompany}</td>
                            <td class="spe_line" height="30" align="center">
                                <div idvalue="{f_UserOrderSendId}" class="delete">删除</div>
                            </td>
                        </tr>
                    </table>
                </li>
        ]]>
     </item>
 </icms>
</ul>
</div>
</body>
</html>