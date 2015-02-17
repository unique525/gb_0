<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>会员中心</title>
    <link href="/images/common_css.css" rel="stylesheet" type="text/css"/>
    <link href="/images/user_layout.css" rel="stylesheet" type="text/css" />


</head>

<body>

<pre_temp id="4"></pre_temp>
<div class="clean"></div>
<pre_temp id="12"></pre_temp>
<div class="wrapper">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width="193" valign="top" height="750">
                <pre_temp id="6"></pre_temp>
            </td>
            <td width="1" bgcolor="#D4D4D4"></td>
            <td width="1006" valign="top">
                <div class="rightbar">
                    <div class="rightbar2"><a href="/">星滋味首页</a> ><a href="/default.php?mod=user&a=homepage">会员中心</a> ><a href="/default.php?mod=user_order&a=detail&user_order_id={UserOrderId}">{UserOrderNumber}</a>>订单发货信息</div>
                </div>
                <div class="order_detail" style="padding:10px 10px;">
                    <div class="title">发货信息</div>
                    <table class="grid product_list" width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                        <tr class="grid_title2 list_title">
                            <td class="spe_line" style="text-align: center">送货人</td>
                            <td class="spe_line">送货时间</td>
                            <td class="spe_line">送货公司</td>
                            <td class="spe_line">签收人</td>
                            <td class="spe_line">签收地址</td>
                            <td class="spe_line">签收人电话</td>
                        </tr>
                        <icms id="user_order_send_list" type="list">
                            <item>
                                <![CDATA[
                                <tr class="grid_item2" id="user_order_product_{f_UserOrderProductId}">
                                    <td class="spe_line" style="text-align: center">{f_SendPersonName}(电话：{f_SendPersonMobile})</td>
                                    <td class="spe_line">{f_SendTime}</td>
                                    <td class="spe_line">{f_SendCompany} </td>
                                    <td class="spe_line">{f_AcceptPersonName}</td>
                                    <td class="spe_line">{f_AcceptAddress}</td>
                                    <td class="spe_line">{f_AcceptTel}</td>
                                </tr>
                                ]]>
                            </item>
                        </icms>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
