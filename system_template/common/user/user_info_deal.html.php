<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript">
        $(function () {
            $("#f_Birthday").datepicker({
                dateFormat: 'yy-mm-dd',
                numberOfMonths: 1,
                showButtonPanel: true
            });

            $("#cancel_dialog").click(function(){
                //$("#dialog_user_info_box", window.parent.document).dialog('close') ;
                parent.$("#dialog_user_info_box").dialog('close');
            });
        });

        function submitUserInfoForm(){
            $("#userInfoForm").submit();
        }
    </script>

</head>
<body>
<div id="dialog_user_info_box" title="提示信息" style="display: none;">
    <div id="user_info_table" style="font-size: 14px;">
        <iframe id="user_info_dialog_frame" src="" frameBorder="0" style="border: 0; " scrolling="auto" width="100%" height="650"></iframe>
    </div>
</div>
<div class="div_list">
    {common_body_deal}
    <form id="userInfoForm" enctype="multipart/form-data" action="/default.php?secu=manage&mod=user_info&m={method}&user_id={UserId}&site_id={SiteId}"
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
                <td class="spe_line" align="right">找回密码问题：</td>
                <td class="spe_line">
                    <input type="text" class="input_number" value="{Question}" name="f_Question"/>
                </td>
                <td class="spe_line" align="right">找回密码答案：</td>
                <td class="spe_line">
                    <input type="text" class="input_number" value="{Answer}" name="f_Answer"/>
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
            <tr>
                <td class="spe_line"></td>
                <td class="spe_line" align="right">微信OPENID：</td>
                <td class="spe_line">
                    {WxOpenId}
                </td>
                <td class="spe_line" align="right"></td>
                <td class="spe_line">
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
</body>
</html>
