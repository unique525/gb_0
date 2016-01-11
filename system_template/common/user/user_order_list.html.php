<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <style type="text/css">
        .change_color:visited {
            color: #FF0000;
        text-decoration: none;
        }
        .basic_button{float:left;margin-right:20px}
        #create_window{
            position: absolute;
            top: 100px;
            left: 200px;
            background-color: rgb(245, 245, 245);
            box-shadow: 0px 0px 5px 1px;
            padding: 5px 20px 10px;
            line-height: 40px;
            text-align: left;
        }
        }
    </style>
    <script type="text/javascript" src="/system_js/manage/user/user_order.js"></script>
    <script type="text/javascript">
        $(function () {
            $("#begin_date").datepicker({
                dateFormat: 'yy-mm-dd',
                numberOfMonths: 1,
                showButtonPanel: true
            });
            $("#end_date").datepicker({
                dateFormat: 'yy-mm-dd',
                numberOfMonths: 1,
                showButtonPanel: true
            });


            $("#btn_show_create_window").click(function(){
                $("#new_order_user_name").val("");
                $("#new_order_real_name").val("");
                $("#create_window").show();
                $("#btn_create").removeAttr("disabled");
                $("#btn_create_continue").removeAttr("disabled");
                $("#create_result_data_table").html("");
                $("#create_input_div").show();
                $("#create_result_div").hide();
            });


            $("#btn_create_cancel").click(function(){
                CreateCancel();
            });




            $("#btn_search").click(function(){
                var site_id = parent.G_NowSiteId;
                var user_order_number = $("#user_order_number").val();
                var state = $("#state").val();
                var begin_date = $("#begin_date").val();
                var end_date = $("#end_date").val();
                var search_key = $("#search_key").val();
                window.location.href="/default.php?secu=manage&mod=user_order&m=list_for_search" +"&site_id="+site_id+
                    "&user_order_number="+user_order_number+"&state="+state+"&begin_date="+begin_date+"&end_date="+end_date+"&search_key="+search_key;
            });

            $("#btn_export").click(function(){
                var site_id = parent.G_NowSiteId;
                var begin_date = $("#begin_date").val();
                var end_date = $("#end_date").val();
                if(begin_date == "" && end_date == ""){
                    alert("请选择日期");
                }else{
                    window.location.href="/default.php?secu=manage&mod=user_order&m=export_excel" +"&site_id="+site_id+
                        "&begin_date="+begin_date+"&end_date="+end_date;
                }
            });

            $(".username_span").each(function(){
                var username = $(this).html();
                var idvalue = $(this).attr("idvalue");
                if(username == "" || username == undefined ){
                    $("#user_mobile_span_"+idvalue).css("display","block");
                }

            });
        });
        function OrderPrint(UserOrderId){
            window.open("/default.php?secu=manage&mod=user_order&m=print&user_order_id="+UserOrderId+"&site_id={SiteId}");
        }

        function CreateOfflineOrder(close){

            var site_id = parent.G_NowSiteId;
            var real_name = $("#new_order_real_name").val();
            var user_name = $("#new_order_user_name").val();
            if(real_name==""||real_name==undefined||user_name==""||user_name==undefined){
                alert("信息填写不全");
            }else{
                $.ajax({
                    type:"post",
                    url:"/default.php?secu=manage&mod=user_order&m=async_create_offline_order" +"&site_id="+site_id,
                    data:{real_name:real_name,user_name:user_name},
                    dataType:"jsonp",
                    jsonp: "jsonpcallback",
                    success: function (data) {
                        var result=parseInt(data["result"]);
                        if(result>0){
                            if(result==1){
                                alert("添加成功！");
                                $("#new_order_user_name").val("");
                                $("#new_order_real_name").val("");
                                if(close){
                                    $("#create_window").hide();
                                    $("#btn_create").removeAttr("disabled");
                                    $("#btn_create_continue").removeAttr("disabled");
                                    $("#create_result_data_table").html("");
                                    $("#create_input_div").show();
                                    $("#create_result_div").hide();
                                }
                            }else if(result==10){//已存在用户 加载订单记录
                                var tableContent="";
                                $.each(data["result_data"], function (i, v) {
                                    tableContent+="<tr>";
                                    tableContent+="<td width='40'>"+v["UserOrderNewspaperId"]+"</td>";
                                    tableContent+="<td width='150'>"+v["CreateDate"]+"</td>";
                                    tableContent+="<td width='120'>"+v["SalePrice"]+"</td>";
                                    tableContent+="<td width='100'>"+v["State"]+"</td>";
                                    tableContent+="<td width='150'>"+v["BeginDate"]+"</td>";
                                    tableContent+="<td width='150'>"+v["EndDate"]+"</td>";
                                    tableContent+='<td width="120"><input class="btn2" value="续期" type="button" onclick="Renewal('+v["UserId"]+','+v["UserOrderNewspaperId"]+');"></td>';
                                    tableContent+="</tr>";
                                });
                                $("#create_result_data_table").html(tableContent);
                                $("#create_input_div").hide();
                                $("#create_result_div").show();
                                $("#btn_create").attr("disabled","disabled");
                                $("#btn_create_continue").attr("disabled","disabled");


                            }
                        }else{
                            alert(data["result"]);
                            alert("添加失败！");
                        }
                    },
                    error: function (data, status, e){
                        //alert(data.readyState);
                        alert("添加失败！");
                    }
                });
            }
        }

        function Renewal(user_id,user_order_newspaper_id){
            var site_id = parent.G_NowSiteId;

            $.ajax({
                type:"post",
                url:"/default.php?secu=manage&mod=user_order&m=async_renewal_offline_order_for_newspaper" +"&site_id="+site_id,
                data:{user_id:user_id,user_order_newspaper_id:user_order_newspaper_id},
                dataType:"jsonp",
                jsonp: "jsonpcallback",
                success: function (data) {
                    var result=parseInt(data["result"]);
                    if(result>0){
                        alert("成功！")
                        CreateCancel();
                    }else{
                        alert("失败！"+result);
                        CreateCancel();
                    }
                },
                error: function (data, status, e){
                    //alert(data.readyState);
                    alert("失败！");
                    CreateCancel();
                }
            });
        }

        function CreateCancel(){
            $("#new_order_user_name").val("");
            $("#new_order_real_name").val("");
            $("#create_window").hide();
            $("#btn_create").removeAttr("disabled");
            $("#btn_create_continue").removeAttr("disabled");
            $("#create_result_data_table").html("");
            $("#create_input_div").show();
            $("#create_result_div").hide();
        }
    </script>
</head>
<body>
<div id="create_window" title="提示信息" style="display:none">
<div id="create_result_div" style="display: none">
    <span style="color:red">用户已存在，若要为其续期，请选择订单。</span>
    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
        <tr  class="grid_title2">
            <td style="width:40px;text-align: center">ID</td>
            <td style="width:150px;text-align: center">下单时间</td>
            <td  style="width:120px;text-align: center">订单价格</td>
            <td style="width:100px;text-align: center">状态</td>
            <td style="width:150px;text-align: center">开始时间</td>
            <td style="width:150px;text-align: center">结束时间</td>
            <td  style="width:120px;text-align: center">续期</td>
        </tr>
    </table>
    <table class="grid" width="100%" cellpadding="0" cellspacing="0" id="create_result_data_table">
    </table>
</div>
<div id="create_input_div">
    新增线下订单<br>
    <label for="new_order_user_name">用户名：</label><input id="new_order_user_name" class="input_box" value="" type="text"><br>
    <label for="new_order_real_name">真实姓名：</label><input id="new_order_real_name" class="input_box" value="" type="text"><br>
</div>
    <input id="btn_create" class="btn2" value="确认并关闭" type="button" onclick="CreateOfflineOrder(true);">
    <input id="btn_create_continue" class="btn2" value="确认并继续" type="button" onclick="CreateOfflineOrder(false);">
    <input id="btn_create_cancel" class="btn2" value="取消" type="button">
</div>
    <div id="dialog_user_order_pay_box" title="提示信息" style="display: none;">
        <div id="user_order_pay__table" style="font-size: 14px;">
            <iframe id="user_order_pay_dialog_frame" src="" frameBorder="0" style="border: 0; " scrolling="auto" width="100%" height="400px"></iframe>
        </div>
    </div>
    <div id="dialog_user_order_send_box" title="提示信息" style="display: none;">
        <div id="user_order_send__table" style="font-size: 14px;">
            <iframe id="user_order_send_dialog_frame" src="" frameBorder="0" style="border: 0; " scrolling="auto" width="100%" height="400px"></iframe>
        </div>
    </div>
    <div class="div_list">
        <div class="basic_button">
            <input id="btn_show_create_window" type="button" class="btn2" value="新建线下订单"/>
        </div>
        <div class="search" style="width:99%;height:30px">
            <label for="search_key">搜索关键字：</label><input type="text" id="search_key" style="width:80px;" value=""/>
            订单号：<input type="text" id="user_order_number" value=""/>
            订单状态：
            <select id="state">
                <option value="">全部</option>
                <option value="10">未付款</option>
                <option value="15">货到付款</option>
                <option value="20">已付款，未发货</option>
                <option value="25">已发货</option>
                <option value="30">交易完成</option>
                <option value="31">交易完成,已评价</option>
                <option value="40">交易关闭</option>
                <option value="50">申请退款</option>
                <option value="55">退款完成</option>
                <option value="70">未评价</option>
            </select>
            起始时间：<input  id="begin_date" value="" type="text" class="input_box" style="width:80px;"/>
            -结束时间：<input id="end_date" value="" type="text" class="input_box" style="width:80px;"/>
            <input id="btn_search" type="button" class="btn2" value="查询"/>
            <input id="btn_export" type="button" class="btn2" value="导出"/>
        </div>
        <div class="advance_search" style="width:99%;height:150px;border:1px #CCC solid;display: none">

        </div>
        <table class="grid" width="100%" cellpadding="0" cellspacing="0">
            <tr  class="grid_title2">
                <td style="width:40px;text-align: center">ID</td>
                <td style="width:50px;text-align: center">编辑</td>
                <td style="text-align: center">订单编号</td>
                <td style="width:100px;text-align: center">会员名</td>
                <td style="width:80px;text-align: center">订单总价</td>
                <td style="width:80px;text-align: center">运费</td>
                <td style="width:150px;text-align: center">付款时间</td>
                <td  style="width:120px;text-align: center">状态</td>
                <td  style="width:250px;text-align: center">相关信息</td>
            </tr>
        </table>
        <ul id="type_list">
        <icms id="user_order_list">
            <item>
                <![CDATA[
                <li>
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item2">
                            <td class="spe_line2" style="width:40px;text-align: center">{f_UserOrderId}</td>
                            <td class="spe_line2" style="width:50px;text-align: center">
                                <img src="/system_template/{template_name}/images/manage/edit.gif" style="cursor:pointer" class="edit" idvalue="{f_UserOrderId}"/>
                            </td>
                            <td class="spe_line2" style="text-align: center">
                                <a target="_blank" class="change_color" href="/default.php?secu=manage&mod=user_order&user_order_id={f_UserOrderId}&m=modify&site_id={f_SiteId}">{f_UserOrderNumber}</a>
                            </td>
                            <td class="spe_line2" style="width:100px;text-align: center">
                                <div idvalue="{f_UserOrderId}" class="username_span">{f_UserName}</div>
                                <div id="user_mobile_span_{f_UserOrderId}" style="display:none">{f_UserMobile}</div>
                            </td>
                            <td class="spe_line2" style="width:80px;text-align: center">
                                ￥<span class="show_price">{f_AllPrice}</span>
                            </td>
                            <td class="spe_line2" style="width:80px;text-align: center">
                                ￥<span class="show_price">{f_SendPrice}</span>
                            </td>
                            <td class="spe_line2" style="width:150px;text-align: center">
                                <span title="创建时间：{f_CreateDate}">{f_PayDate}</span>
                            </td>
                            <td class="spe_line2" style="width:120px;text-align: center">
                                <span class="span_state" idvalue="{f_UserOrderId}">{f_State}</span>
                            </td>
                            <td class="spe_line2" style="width:250px;text-align: center">
                                <input class="btn2 btn_user_order_list_pay_info" idvalue="{f_UserOrderId}" type="button" style="width:80px;" value="支付信息" />
                                <input class="btn2 btn_user_order_list_send_info" idvalue="{f_UserOrderId}" type="button" style="width:80px;" value="发货信息" />
                                <a class="btn2 change_color" href="/default.php?secu=manage&mod=user_order&m=print&user_order_id={f_UserOrderId}&site_id={SiteId}" style="width:80px;" target="_blank">打印 </a>
                            </td>
                        </tr>
                    </table>
                </li>
                ]]>
            </item>
        </icms>
        </ul>

        {pagerButton}
    </div>
</body>
</html>