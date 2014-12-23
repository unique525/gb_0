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
            var isModify = true;
            $(".modify_accept_time").datetimepicker({
                showSecond: true,
                dateFormat: 'yy-mm-dd',
                numberOfMonths: 1,
                timeFormat: 'hh:mm:ss',
                stepHour: 1,
                stepMinute: 1,
                stepSecond: 1
            });
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
                        '<td class="spe_line" height="30" width="165" align="center">' +
                        '<input type="text" class="input_box" id="accept_time" value="" style="width:160px">' +
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
                        dateFormat: 'yy-mm-dd',
                        numberOfMonths: 1,
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
                        var sendCompany = $("#send_company").val();
                        var userOrderId = parseInt("{UserOrderId}");
                        $.ajax({
                            type:"post",
                            url:"/default.php?secu=manage&mod=user_order_send&m=async_create&site_id="+parent.parent.G_NowSiteId+"&user_order_id="+userOrderId,
                            data:{
                                acceptPersonName:acceptPersonName,
                                acceptAddress:acceptAddress,
                                acceptTel:acceptTel,
                                acceptTime:acceptTime,
                                sendCompany:sendCompany
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

            $(".modify").click(function(){
                if(isModify){
                    isModify = false;
                    var idvalue = $(this).attr("idvalue");
                    $("#accept_person_name_"+idvalue).css("display","none");
                    $("#accept_address_"+idvalue).css("display","none");
                    $("#accept_tel_"+idvalue).css("display","none");
                    $("#accept_time_"+idvalue).css("display","none");
                    $("#send_company_"+idvalue).css("display","none");
                    $("#modify_accept_person_name_"+idvalue).css("display","inline");
                    $("#modify_accept_address_"+idvalue).css("display","inline");
                    $("#modify_accept_tel_"+idvalue).css("display","inline");
                    $("#modify_accept_time_"+idvalue).css("display","inline");
                    $("#modify_send_company_"+idvalue).css("display","inline");

                    $("#operator_div_"+idvalue).css("display","none");
                    $("#modify_div_"+idvalue).css("display","block");

                }else{
                    alert("请逐条修改");
                }
            });

            $(".confirm_modify").click(function(){
                var idvalue = $(this).attr("idvalue");
                var accept_person_name = $("#modify_accept_person_name_"+idvalue).val();
                var accept_address = $("#modify_accept_address_"+idvalue).val();
                var accept_tel = $("#modify_accept_tel_"+idvalue).val();
                var accept_time = $("#modify_accept_time_"+idvalue).val();
                var send_company = $("#modify_send_company_"+idvalue).val();
                $.ajax({
                    type:"post",
                    url:"/default.php?secu=manage&mod=user_order_send&m=async_modify&user_order_send_id="+idvalue,
                    data:{accept_person_name:accept_person_name,accept_address:accept_address,accept_tel:accept_tel,accept_time:accept_time,send_company:send_company},
                    dataType:"jsonp",
                    jsonp:"jsonpcallback",
                    success:function(data){
                        var result = data["result"];
                        if(result > 0){
                            window.location.href=window.location.href;
                        }else{
                            alert("修改失败");
                        }
                    }
                });
            });

            $(".cancel_modify").click(function(){
                isModify = true;
                var idvalue = $(this).attr("idvalue");
                $("#accept_person_name_"+idvalue).css("display","inline");
                $("#accept_address_"+idvalue).css("display","inline");
                $("#accept_tel_"+idvalue).css("display","inline");
                $("#accept_time_"+idvalue).css("display","inline");
                $("#send_company_"+idvalue).css("display","inline");
                $("#modify_accept_person_name_"+idvalue).css("display","none");
                $("#modify_accept_address_"+idvalue).css("display","none");
                $("#modify_accept_tel_"+idvalue).css("display","none");
                $("#modify_accept_time_"+idvalue).css("display","none");
                $("#modify_send_company_"+idvalue).css("display","none");

                $("#operator_div_"+idvalue).css("display","block");
                $("#modify_div_"+idvalue).css("display","none");
            });

            $(".delete").click(function(){
                var idvalue = $(this).attr("idvalue");
                $.ajax({
                    url:"/default.php?secu=manage&mod=user_order_send&m=async_remove&user_order_send_id="+idvalue,
                    dataType:"jsonp",
                    jsonp:"jsonpcallback",
                    success:function(data){
                        var result = data["result"];
                        if(result > 0){
                            window.location.href=window.location.href;
                        }else{
                            alert("修改失败");
                        }
                    }
                });
            });
        });
    </script>
</head>
<body>
<div class="div_list">
    <div style="width:99%;">
        <div class="btn" id="create" style="float:left;width:50px;text-align: center">新增</div>
        <div style="clear:left"></div>
    </div>
    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
        <tr  class="grid_title2">
            <td class="spe_line" height="30" width="80" align="center">接收人姓名</td>
            <td class="spe_line" height="30" width="600" align="center">接收地址</td>
            <td class="spe_line" height="30" width="100" align="center">接收人电话</td>
            <td class="spe_line" height="30" width="165" align="center">接收时间</td>
            <td class="spe_line" height="30" width="80" align="center">送货公司名称</td>
            <td class="spe_line" height="30" align="center"></td>
        </tr>
    </table>
    <ul id="type_list">
        <icms id="user_order_send_list">
            <item>
                <![CDATA[
                <li>
                <table class="grid" width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item2" id="user_order_send_{f_UserOrderSendId}">
                            <td class="spe_line" height="30" width="80" align="center">
                                <div id="accept_person_name_{f_UserOrderSendId}">{f_AcceptPersonName}</div>
                                <input type="text" value="{f_AcceptPersonName}" class="input_box" id="modify_accept_person_name_{f_UserOrderSendId}" style="display:none;width:70px"/>
                            </td>
                            <td class="spe_line" height="30" width="600" align="center">
                                <div id="accept_address_{f_UserOrderSendId}">{f_AcceptAddress}</div>
                                <input type="text" value="{f_AcceptAddress}" class="input_box" id="modify_accept_address_{f_UserOrderSendId}" style="display:none;width:590px"/>
                            </td>
                            <td class="spe_line" height="30" width="100" align="center">
                                <div id="accept_tel_{f_UserOrderSendId}">{f_AcceptTel}</div>
                                <input type="text" value="{f_AcceptTel}" id="modify_accept_tel_{f_UserOrderSendId}" class="input_box" style="display:none;width:90px"/>
                            </td>
                            <td class="spe_line" height="30" width="165" align="center">
                                <div id="accept_time_{f_UserOrderSendId}">{f_AcceptTime}</div>
                                <input type="text" id="modify_accept_time_{f_UserOrderSendId}" class="input_box modify_accept_time" value="{f_AcceptTime}" style="display:none;width:155px"/>
                            </td>
                            <td class="spe_line" height="30" width="80" align="center">
                                <div id="send_company_{f_UserOrderSendId}">{f_SendCompany}</div>
                                <input type="text" id="modify_send_company_{f_UserOrderSendId}" value="{f_SendCompany}" class="input_box" style="display:none;width:70px"/>
                            </td>
                            <td class="spe_line" height="30" align="center" id="operator_{f_UserOrderSendId}">
                                <div id="operator_div_{f_UserOrderSendId}">
                                    <div idvalue="{f_UserOrderSendId}" class="modify btn" style="float:left">修改</div>
                                    <div style="width:5px;height:20px;float:left"></div>
                                    <div idvalue="{f_UserOrderSendId}" class="delete btn" style="float:left">删除</div>
                                </div>
                                <div id="modify_div_{f_UserOrderSendId}" style="display:none">
                                    <div class="confirm_modify btn" idvalue="{f_UserOrderSendId}" style="float:left">确定</div>
                                    <div style="width:5px;height:20px;float:left"></div>
                                    <div class="cancel_modify btn" idvalue="{f_UserOrderSendId}" style="float:left">取消</div>
                                </div>
                                <div style="clear:left"></div>
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