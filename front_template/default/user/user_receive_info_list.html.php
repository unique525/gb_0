<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>会员中心</title>
    <link href="/images/common_css.css" rel="stylesheet" type="text/css"/>
    <link href="/images/user_layout.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .right {
            cursor: pointer
        }
    </style>
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/front_js/common.js"></script>
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui-1.8.2.custom.min.js"></script>


    <script type="text/javascript">

        var siteId = parseInt("{SiteId}");

        var nowSelectReceiveInfoId = 0;
        $(function () {


//            $(".right").click(function () {
//                var idvalue = $(this).attr("idvalue");
//                var state = $("#" + idvalue + "_child").css("display");
//                if (state == "none") {
//                    $(".right_child").css("display", "none");
//                    $(".right_img").attr("src", "/images/icon_jia.png");
//                    $("#" + idvalue + "_img").attr("src", "/images/icon_jian.png");
//                    $("#" + idvalue + "_child").css("display", "inline");
//                } else {
//                    $("#" + idvalue + "_img").attr("src", "/images/icon_jia.png");
//                    $("#" + idvalue + "_child").css("display", "none");
//                }
//            });

            $("#add_receive_info").click(function () {
                $("#new_receive_info_div").css("display", "block");
            });

            $("#cancel_receive_info").click(function () {
                $("#new_receive_info_div").css("display", "none");
            });

            $(".modify_receive_info").click(function(){
                var receive_info_id = $(this).attr("idvalue");
                nowSelectReceiveInfoId = receive_info_id;
                $("#address").val($("#Address_"+receive_info_id).html());
                $("#postcode").val($("#Postcode_"+receive_info_id).html());
                $("#receive_person_name").val($("#ReceivePersonName_"+receive_info_id).html());
                $("#tel_no").val($("#HomeTel_"+receive_info_id).html());
                $("#mobile").val($("#Mobile_"+receive_info_id).html());

                $("#new_receive_info_div").css("display", "block");
            });

            $("#confirm_receive_info").click(function () {
                var address = $("#address").val();
                var postcode = $("#postcode").val();
                var receive_person_name = $("#receive_person_name").val();
                var tel = $("#tel_no").val();
                var mobile = $("#mobile").val();
                if (address != "" && postcode != "" && receive_person_name != "" && (tel != "" || mobile != "")) {
                    var url = "";
                    if(nowSelectReceiveInfoId == 0){
                        url = "/default.php?mod=user_receive_info&a=async_create";
                    }else{
                        url = "/default.php?mod=user_receive_info&a=async_modify&user_receive_info_id="+nowSelectReceiveInfoId;
                    }
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {address: address, postcode: postcode, receive_person_name: receive_person_name, tel: tel, mobile: mobile},
                        dataType: "jsonp",
                        jsonp: "jsonpcallback",
                        success: function (data) {
                            var result = data["result"];
                            if (result > 0) {
                                location.replace(location);
                            } else if(result == -1) {
                                alert("新增失败");
                            }else if(result == -2){
                                alert("修改失败");
                            }
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
//                            alert(textStatus);
//                            alert(errorThrown);
                        }
                    });
                } else {
                    alert("请填入完整信息");
                }
            });
        });
    </script>
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
                    <div class="rightbar2"><a href="">星滋味首页</a> >会员中心</div>
                </div>
                <div style="padding:20px 50px;">
                    <table class="add_list" width="800" border="0" cellspacing="0" cellpadding="0">
                        <tr class="add_title">
                            <td class="receive_id">收件人</td>
                            <td class="address">收件地址</td>
                            <td class="post_code">邮编</td>
                            <td class="mobile">手机</td>
                            <td class="home_tel">电话</td>
                            <td class="opretion">操作</td>
                        </tr>
                        <icms id="user_receive_info_list">
                            <item><![CDATA[

                                <tr>
                                    <td id="ReceivePersonName_{f_UserReceiveInfoId}" class="receive_id">{f_ReceivePersonName}</td>
                                    <td id="Address_{f_UserReceiveInfoId}" class="address">{f_Address}</td>
                                    <td id="Postcode_{f_UserReceiveInfoId}" class="post_code">{f_Postcode}</td>
                                    <td id="Mobile_{f_UserReceiveInfoId}" class="mobile">{f_Mobile}</td>
                                    <td id="HomeTel_{f_UserReceiveInfoId}" class="home_tel">{f_HomeTel}</td>
                                    <td class="opretion">
                                    <span class="modify_receive_info" idvalue="{f_UserReceiveInfoId}"
                                          style="cursor:pointer">修改本地址</span>
                                    </td>
                                </tr>


                                ]]>
                            </item>
                        </icms>
                        <tr >
                            <td style="border:none; padding-left: 15px;" valign="middle" colspan="5"><img id="add_receive_info" style="cursor: pointer" src="images/btnaddress.png" width="89" height="25"/></td>
                            <td style="border:none;"></td>
                        </tr>
                    </table>
                    <div id="new_receive_info_div" class="add_new" style="display: none">
                        <dl>
                            <dt><span>*</span>详细地址：</dt>
                            <dd>
                                <input id="address" autocomplete="off" type="text" class="cart_n_long" maxlength="40"
                                       value=""/>
                            </dd>
                        </dl>
                        <dl>
                            <dt><span>*</span>邮政编码：</dt>
                            <dd><input id="postcode" autocomplete="off" type="text" class="cart_n_short" value=""/></dd>
                        </dl>
                        <dl>
                            <dt><span>*</span>收 货 人：</dt>
                            <dd><input id="receive_person_name" autocomplete="off" type="text" class="cart_n_short"
                                       value=""/></dd>
                        </dl>
                        <dl>
                            <dt><span>*</span>手&nbsp;&nbsp;&nbsp;&nbsp;机：</dt>
                            <dd><input id="mobile" type="text" class="cart_n_short" autocomplete="off" value=""/>&nbsp;或固定电话&nbsp;
                                <input id="tel_no" type="text" class="cart_n_short" autocomplete="off" value=""/>
                            </dd>
                            <dd class="adr_prompt">两者至少填写一项，用于接收发货通知及送货前确认</dd>
                        </dl>
                        <!--
                        <dl>
                            <dt>地址标注：</dt>
                            <dd><input type="text" value=""  class="cart_n_short"><span style="color:#C1C1C1;padding-left:5px;">例如家里、公司，最多四个字。</span></dd>
                        </dl>
                        <dl>
                            <dt>&nbsp;</dt>
                            <dd><input name="" type="checkbox" value="" /><span style="font-size:12px;padding-left:3px;">设为默认地址</span></dd>
                        </dl>
                        -->
                        <dl>
                            <dt>&nbsp;</dt>
                            <dd>
                                <div id="confirm_receive_info" class="ncart_btn_on"
                                     style="border:1px solid #CCC;cursor:pointer;float:left">保存
                                </div>
                                <div style="float:left;width:10px;border: 1px solid #fff"></div>
                                <div id="cancel_receive_info" class="ncart_btn_on"
                                     style="border:1px solid #CCC;cursor:pointer;float:left">取消
                                </div>
                                <div style="clear:left"></div>
                            </dd>
                        </dl>
                    </div>
                    <div class="clean"></div>
                </div>
            </td>
        </tr>
    </table>
</div>
<div class="footerline"></div>
<div class="wrapper">
    <div class="footerleft">
        <div class="cont">
            <div><img src="/images/footergwzn.png" width="79" height="79"/></div>
            <b>交易条款</b><br/>
            <a href="" target="_blank">购物流程</a><br/>
            <a href="" target="_blank">发票制度</a><br/>
            <a href="" target="_blank">会员等级</a><br/>
            <a href="" target="_blank">积分制度</a><br/><br/>
        </div>
    </div>
    <div class="footerleft">
        <div class="cont">
            <div><img src="/images/footerpsfw.png" width="79" height="79"/></div>
            <b>配送服务</b><br/>
            <a href="" target="_blank">配送说明</a><br/>
            <a href="" target="_blank">配送范围</a><br/>
            <a href="" target="_blank">配送状态查询</a><br/><br/><br/>
        </div>
    </div>
    <div class="footerleft">
        <div class="cont">
            <div><img src="/images/footerzffs.png" width="79" height="79"/></div>
            <b>支付方式</b><br/>
            <a href="" target="_blank">支付宝支付</a><br/>
            <a href="" target="_blank">银联在线支付</a><br/>
            <a href="" target="_blank">货到付款</a><br/><br/><br/>
        </div>
    </div>
    <div class="footerleft">
        <div class="cont">
            <div><img src="/images/footershfw.png" width="79" height="79"/></div>
            <b>售后服务</b><br/>
            <a href="" target="_blank">服务承诺</a><br/>
            <a href="" target="_blank">退换货政策</a><br/>
            <a href="" target="_blank">退换货流程</a><br/><br/><br/>
        </div>
    </div>
    <div class="footerright" style="padding-left:50px;">
        手机客户端下载
        <div><img src="/images/weixin.png" width="104" height="104"/></div>
    </div>
    <div class="footerright" style="padding-right:50px;">
        手机客户端下载
        <div><img src="/images/weixin.png" width="104" height="104"/></div>
    </div>
</div>
</body>
</html>
