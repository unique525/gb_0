<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>会员中心</title>
    <link href="/images/common_css.css" rel="stylesheet" type="text/css"/>
    <link href="/images/user_layout.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .input_box{
            border: 1px solid #CCC;
        }
        .input_number{
            border: 1px solid #CCC;
        }
        .right {
            cursor: pointer
        }
    </style>
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/front_js/common.js"></script>
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui-1.8.2.custom.min.js"></script>
    

    <script type="text/javascript">


        function submitUserInfoForm(){
            $("#userInfoForm").submit();
        }

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
                    <div class="rightbar2"><a href="/">星滋味首页</a> ><a href="/default.php?mod=user&a=homepage">会员中心</a>>修改会员信息</div>
                </div>
                <div class="change_info">
                    <form id="userInfoForm" enctype="multipart/form-data" action="/default.php?mod=user_info&a=modify&user_id={UserId}"
                          method="post">
                        <table border="0" width="100%" align="center" cellpadding="0" cellspacing="0">
                            <tr>
                                <td class="spe_line label">用户名：</td>
                                <td class="spe_line" width="348">{UserName}</td>
                                <td class="spe_line label">真实姓名：</td>
                                <td class="spe_line" width="348">
                                    <input type="text" class="input_box" value="{RealName}" name="RealName"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spe_line label">昵称：</td>
                                <td class="spe_line">
                                    {NickName}
                                    <input type="hidden" value="{NickName}" name="NickName"/>
                                </td>
                                <td class="spe_line label">性别：</td>
                                <td class="spe_line">
                                    <select id="f_Gender" name="Gender">
                                        <option value="0">男</option>
                                        <option value="1">女</option>
                                    </select>
                                    {s_Gender}
                                </td>
                            </tr>

                            <tr>
                                <td class="spe_line label">会员点卷：</td>
                                <td class="spe_line">
                                    {UserPoint}
                                </td>
                                <td class="spe_line label">会员积分：</td>
                                <td class="spe_line">
                                    {UserScore}
                                </td>
                            </tr>
                            <tr>
                                <td class="spe_line label">会员金钱：</td>
                                <td class="spe_line">
                                    {UserMoney}
                                </td>
                                <td class="spe_line label">会员魅力：</td>
                                <td class="spe_line">
                                    {UserCharm}
                                </td>
                            </tr>
                            <tr>
                                <td class="spe_line label">会员经验：</td>
                                <td class="spe_line">
                                    {UserExp}
                                </td>
                                <td class="spe_line label"></td>
                                <td class="spe_line">
                                </td>
                            </tr>
                            <tr>
                                <td class="spe_line label" valign="top">会员签名：</td>
                                <td class="spe_line" colspan="3">
                                    <textarea style="width:728px; height:100px; margin-bottom:5px;" name="Sign">{Sign}</textarea>
                                </td>
                            </tr>
                            <tr>
                                <td class="spe_line label">Email：</td>
                                <td class="spe_line">
                                    <input type="text" class="input_box" value="{Email}" name="Email"/>
                                </td>
                                <td class="spe_line label">QQ：</td>
                                <td class="spe_line">
                                    <input type="text" class="input_box" id="SalePrice" value="{QQ}" name="QQ"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spe_line label">来自：</td>
                                <td class="spe_line">
                                    <input type="text" class="input_box" value="{ComeFrom}" name="ComeFrom"/>
                                </td>
                                <td class="spe_line label">头衔：</td>
                                <td class="spe_line">
                                    {Honor}
                                </td>
                            </tr>
                            <tr>
                                <td class="spe_line label">生日：</td>
                                <td class="spe_line">
                                    <input type="text" class="input_box" id="f_Birthday" value="{Birthday}" name="Birthday"/>
                                </td>
                                <td class="spe_line label">身份证：</td>
                                <td class="spe_line">
                                    {IdCard}
                                    <input type="hidden" value="{IdCard}" name="IdCard"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spe_line label">邮编：</td>
                                <td class="spe_line">
                                    <input type="text" class="input_number" value="{PostCode}" name="PostCode"/>
                                </td>
                                <td class="spe_line label">地址：</td>
                                <td class="spe_line">
                                    <input type="text" class="input_box" style="width:300px" value="{Address}" name="Address"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spe_line label">电话：</td>
                                <td class="spe_line">
                                    <input type="text" class="input_number" value="{Tel}" name="Tel"/>
                                </td>
                                <td class="spe_line label">手机：</td>
                                <td class="spe_line">
                                    <input type="text" class="input_number" value="{Mobile}" name="Mobile"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spe_line label">国家：</td>
                                <td class="spe_line">
                                    <input type="text" class="input_box" value="{Country}" name="Country"/>
                                </td>
                                <td class="spe_line label">省份：</td>
                                <td class="spe_line">
                                    <input type="text" class="input_box" value="{Province}" name="Province"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spe_line label">职业：</td>
                                <td class="spe_line">
                                    <input type="text" class="input_box" value="{Occupational}" name="Occupational"/>
                                </td>
                                <td class="spe_line label">城市：</td>
                                <td class="spe_line">
                                    <input type="text" class="input_box" value="{City}" name="City"/>
                                </td>
                            </tr>
                            <!--
                            <tr>
                                <td class="spe_line label" >银行名称：</td>
                                <td class="spe_line">
                                    <input type="text" class="input_box" value="{BankName}" name="f_BankName"/>
                                </td>
                                <td class="spe_line label">开户地址：</td>
                                <td class="spe_line">
                                    <input type="text" class="input_box" value="{BankOpenAddress}" name="f_BankOpenAddress"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="spe_line label">银行账户名：</td>
                                <td class="spe_line">
                                    <input type="text" class="input_box" value="{BankUserName}" name="f_BankUserName"/>
                                </td>
                                <td class="spe_line label">银行账号：</td>
                                <td class="spe_line">
                                    <input type="text" class="input_box" value="{BankAccount}" name="f_BankAccount"/>
                                </td>
                            </tr>-->
                        </table>
                        <div class="btns">
                            <input class="btn" style="cursor:pointer" value="确&nbsp;&nbsp;认" type="button" onclick="submitUserInfoForm()"/>
                            <input id="cancel_dialog" class="btn" style="cursor:pointer" value="取&nbsp;&nbsp;消" onclick="window.location.href  = '/default.php?mod=user&a=homepage';" type="button"/>
                        </div>
                    </form>
                </div>
            </td>
        </tr>
    </table>
</div>
<pre_temp id="8"></pre_temp>
</body>
</html>
