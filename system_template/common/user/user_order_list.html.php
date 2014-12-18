<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
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

            $("#btn_search").click(function(){
                var site_id = 1;
                var user_order_number = $("#user_order_number").val();
                var state = $("#state").val();
                var begin_date = $("#begin_date").val();
                var end_date = $("#end_date").val();
                window.location.href="/default.php?secu=manage&mod=user_order&m=list_for_search" +"&site_id="+site_id+
                    "&user_order_number="+user_order_number+"&state="+state+"&begin_date="+begin_date+"&end_date="+end_date;
            });


        })

    </script>
</head>
<body>
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
        <div class="search" style="width:99%;height:30px">
            订单号：<input type="text" id="user_order_number" value=""/>
            订单状态：
            <select id="state">
                <option value="">全部</option>
                <option value="0">未付款</option>
                <option value="20">已付款，未发货</option>
                <option value="25">已发货</option>
                <option value="30">交易完成</option>
                <option value="40">交易关闭</option>
                <option value="70">未评价</option>
            </select>
            起始时间：<input  id="begin_date" value="" type="text" class="input_box" style="width:80px;"/>
            -结束时间：<input id="end_date" value="" type="text" class="input_box" style="width:80px;"/>
            <input id="btn_search" type="button" class="btn2" value="查询"/>
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
                <td style="width:150px;text-align: center">创建日期</td>
                <td  style="width:120px;text-align: center">状态</td>
                <td  style="width:150px;text-align: center">相关信息</td>
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
                                {f_UserOrderNumber}
                            </td>
                            <td class="spe_line2" style="width:100px;text-align: center">
                                <span>{f_UserName}</span>
                            </td>
                            <td class="spe_line2" style="width:80px;text-align: center">
                                ￥<span class="show_price">{f_AllPrice}</span>
                            </td>
                            <td class="spe_line2" style="width:80px;text-align: center">
                                ￥<span class="show_price">{f_SendPrice}</span>
                            </td>
                            <td class="spe_line2" style="width:150px;text-align: center">
                                <span>{f_CreateDate}</span>
                            </td>
                            <td class="spe_line2" style="width:120px;text-align: center">
                                <span class="span_state" idvalue="{f_UserOrderId}">{f_State}</span>
                            </td>
                            <td class="spe_line2" style="width:150px;text-align: center">
                                <div class="btn2 btn_user_order_list_pay_info" idvalue="{f_UserOrderId}" style="width:80px;">支付信息</div>
                                <div class="btn2 btn_user_order_list_send_info" idvalue="{f_UserOrderId}" style="width:80px;">物流信息</div>
                            </td>
                        </tr>
                    </table>
                </li>
                ]]>
            </item>
        </icms>
        </ul>
        <!--
        <div class="order" style="width:99%;height:160px;">
            <div class="title" style="width:100%;height:30px;background-color: #3165CE;padding-top: 5px">
                <input type="checkbox" style="margin-left: 5px;float:left"/>
                <div style="margin-left: 15px;float:left">2014-02-11 11:22:33</div>
                <div style="margin-left: 15px;float:left">订单号：</div>
                <div style="float:left">5484131481000054</div>
            </div>
            <div class="detail" style="width:100%;height:120px;padding-top: 5px">
                <div class="product_pic" style="background-image: url('/upload/Desert.jpg');filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='/upload/Desert.jpg',sizingMethod='scale');">
                    <img width="110" height="110" src="/upload/background.png"/>
                </div>
                <div class="product_name">
                    沙漠西瓜
                </div>
                <div class="product_price">
                    产品价格
                </div>
                <div class="product_count">
                    产品数量
                </div>
                <div class="freight_price">
                    运费
                </div>
                <div class="order_price">
                    订单总价
                </div>
                <div class="order_state">
                    交易状态
                </div>
                <div class="order_operate">
                    交易操作
                </div>
                <div style="clear:left"></div>
            </div>
        </div>-->
        {pagerButton}
    </div>
</body>
</html>