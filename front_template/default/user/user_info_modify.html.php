<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>会员中心</title>
    <link href="/images/common_css.css" rel="stylesheet" type="text/css"/>
    <style type="text/css">
        .right {
            cursor: pointer
        }
    </style>
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/front_js/common.js"></script>
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript" src="/front_js/user/user_car.js"></script>

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

<div class="wrapper2">
    <div class="logo"><a href=""><img src="/images/mylogo.png" width="320" height="103"/></a></div>
    <div class="search">
        <div class="search_green"><input name="" type="text" class="text"/></div>
        <div class="searchbtn"><img src="/images/search.png" width="46" height="28"/></div>
        <div class="searchbottom">平谷大桃 哈密瓜 新鲜葡萄 红炉磨坊 太湖鲜鱼</div>
    </div>
    <div class="service">
        <div class="hottel"><span><a href="" target="_blank">热线96333</a></span></div>
        <div class="online"><span><a href="" target="_blank">在线客服</a></span></div>
        <div class="shopping"><a href="/default.php?mod=user_car&a=list"><span>购物车</span></a></div>
        <div class="number" id="user_car_count">0</div>
    </div>
</div>
<div class="clean"></div>
<div class="mainbav">
    <div class="wrapper">
        <div class="goods" id="leftmenu">
            <ul>
                <li><span>会员中心</span></li>
            </ul>
        </div>
        <div class="column1"><a href="">首页</a></div>
        <div class="column2"><a href="">超市量贩</a></div>
        <div class="column2"><a href="">团购</a></div>
        <div class="column2"><a href="">最新预售</a></div>
        <div class="new"><img src="/images/icon_new.png" width="29" height="30"/></div>
    </div>
</div>
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
                    <form id="userInfoForm" enctype="multipart/form-data" action="/default.php?mod=user_info&a=modify&user_id={UserId}"
                          method="post">
                    <table border="0" width="99%" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="spe_line"></td>
                            <td class="spe_line" width="120" align="right">用户名：</td>
                            <td class="spe_line" width="320">{UserName}</td>
                            <td class="spe_line" width="120" align="right">真实姓名：</td>
                            <td class="spe_line" width="320">
                                <input type="text" class="input_box" value="{RealName}" name="f_RealName"/>
                            </td>
                            <td class="spe_line"></td>
                        </tr>
                        <tr>
                            <td class="spe_line"></td>
                            <td class="spe_line" align="right">昵称：</td>
                            <td class="spe_line">
                                <input type="text" class="input_box" value="{NickName}" name="f_NickName"/>
                            </td>
                            <td class="spe_line" align="right">性别：</td>
                            <td class="spe_line">
                                <select id="f_Gender" name="f_Gender">
                                    <option value="男">男</option>
                                    <option value="女">女</option>
                                </select>
                                {s_Gender}
                            </td>
                            <td class="spe_line"></td>
                        </tr>
                        <tr>
                            <td class="spe_line"></td>
                            <td class="spe_line" align="right">会员头像：</td>
                            <td class="spe_line">[预览]&nbsp;&nbsp;&nbsp;&nbsp;[修改]</td>
                            <td class="spe_line" align="right">会员积分：</td>
                            <td class="spe_line">
                                <input type="text" class="input_number" value="{UserScore}" name="f_UserScore"/>
                            </td>
                            <td class="spe_line"></td>
                        </tr>
                        <tr>
                            <td class="spe_line"></td>
                            <td class="spe_line" align="right">会员金钱：</td>
                            <td class="spe_line">
                                <input type="text" class="input_number" value="{UserMoney}" name="f_UserMoney"/>
                            </td>
                            <td class="spe_line" align="right">会员魅力：</td>
                            <td class="spe_line">
                                <input type="text" class="input_number" value="{UserCharm}" name="f_UserCharm">
                            </td>
                            <td class="spe_line"></td>
                        </tr>
                        <tr>
                            <td class="spe_line"></td>
                            <td class="spe_line" align="right">会员经验：</td>
                            <td class="spe_line">
                                <input type="text" class="input_number" value="{UserExp}" name="f_UserExp"/>
                            </td>
                            <td class="spe_line" align="right">会员点卷：</td>
                            <td class="spe_line">
                                <input type="text" class="input_number" value="{UserPoint}" name="f_UserPoint"/>
                            </td>
                            <td class="spe_line"></td>
                        </tr>
                        <tr>
                            <td class="spe_line"></td>
                            <td class="spe_line" align="right" valign="top">会员签名：</td>
                            <td class="spe_line" colspan="3">
                                <textarea style="width:590px" name="f_Sign">{Sign}</textarea>
                            </td>
                            <td class="spe_line"></td>
                        </tr>
                        <tr>
                            <td class="spe_line"></td>
                            <td class="spe_line" align="right">Email：</td>
                            <td class="spe_line">
                                <input type="text" class="input_box" style="width: 250px" value="{Email}" name="f_Email"/>
                            </td>
                            <td class="spe_line" align="right">QQ：</td>
                            <td class="spe_line">
                                <input type="text" class="input_box" id="SalePrice" value="{QQ}" name="f_QQ"/>
                            </td>
                            <td class="spe_line"></td>
                        </tr>
                        <tr>
                            <td class="spe_line"></td>
                            <td class="spe_line" align="right">来自：</td>
                            <td class="spe_line">
                                <input type="text" class="input_box" value="{ComeFrom}" name="f_ComeFrom"/>
                            </td>
                            <td class="spe_line" align="right">头衔：</td>
                            <td class="spe_line">
                                <input type="text" class="input_box" value="{Honor}" name="f_Honor"/>
                            </td>
                            <td class="spe_line"></td>
                        </tr>
                        <tr>
                            <td class="spe_line"></td>
                            <td class="spe_line" align="right">生日：</td>
                            <td class="spe_line">
                                <input type="text" id="f_Birthday" value="{Birthday}" name="f_Birthday"/>
                            </td>
                            <td class="spe_line" align="right">身份证：</td>
                            <td class="spe_line">
                                <input type="text" class="input_box" value="{IdCard}" name="f_IdCard"/>
                            </td>
                            <td class="spe_line"></td>
                        </tr>
                        <tr>
                            <td class="spe_line"></td>
                            <td class="spe_line" align="right">邮编：</td>
                            <td class="spe_line">
                                <input type="text" class="input_number" value="{PostCode}" name="f_PostCode"/>
                            </td>
                            <td class="spe_line" align="right">地址：</td>
                            <td class="spe_line">
                                <input type="text" class="input_box" style="width:300px" value="{Address}" name="f_Address"/>
                            </td>
                            <td class="spe_line"></td>
                        </tr>
                        <tr>
                            <td class="spe_line"></td>
                            <td class="spe_line" align="right">电话：</td>
                            <td class="spe_line">
                                <input type="text" class="input_number" value="{Tel}" name="f_Tel"/>
                            </td>
                            <td class="spe_line" align="right">手机：</td>
                            <td class="spe_line">
                                <input type="text" class="input_number" value="{Mobile}" name="f_Mobile"/>
                            </td>
                            <td class="spe_line"></td>
                        </tr>
                        <tr>
                            <td class="spe_line"></td>
                            <td class="spe_line" align="right">国家：</td>
                            <td class="spe_line">
                                <input type="text" class="input_box" value="{Country}" name="f_Country"/>
                            </td>
                            <td class="spe_line" align="right">省份：</td>
                            <td class="spe_line">
                                <input type="text" class="input_box" value="{Province}" name="f_Province"/>
                            </td>
                            <td class="spe_line"></td>
                        </tr>
                        <tr>
                            <td class="spe_line"></td>
                            <td class="spe_line" align="right">职业：</td>
                            <td class="spe_line">
                                <input type="text" class="input_box" value="{Occupational}" name="f_Occupational"/>
                            </td>
                            <td class="spe_line" align="right">城市：</td>
                            <td class="spe_line">
                                <input type="text" class="input_box" value="{City}" name="f_City"/>
                            </td>
                            <td class="spe_line"></td>
                        </tr>
                        <tr>
                            <td class="spe_line"></td>
                            <td class="spe_line" align="right">银行名称：</td>
                            <td class="spe_line">
                                <input type="text" class="input_box" value="{BankName}" name="f_BankName"/>
                            </td>
                            <td class="spe_line" align="right">开户地址：</td>
                            <td class="spe_line">
                                <input type="text" class="input_box" value="{BankOpenAddress}" name="f_BankOpenAddress"/>
                            </td>
                            <td class="spe_line"></td>
                        </tr>
                        <tr>
                            <td class="spe_line"></td>
                            <td class="spe_line" align="right">银行账户名：</td>
                            <td class="spe_line">
                                <input type="text" class="input_box" value="{BankUserName}" name="f_BankUserName"/>
                            </td>
                            <td class="spe_line" align="right">银行账号：</td>
                            <td class="spe_line">
                                <input type="text" class="input_box" value="{BankAccount}" name="f_BankAccount"/>
                            </td>
                            <td class="spe_line"></td>
                        </tr>
                    </table>
                    <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td height="60" align="right">
                                <input class="btn" value="确认" type="button" onclick="submitUserInfoForm()"/>
                            </td>
                            <td width="10"></td>
                            <td height="60" align="left">
                                <input id="cancel_dialog" class="btn" value="取消" type="button"/>
                            </td>
                        </tr>
                    </table>
                    </form>
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
