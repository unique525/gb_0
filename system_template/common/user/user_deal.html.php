<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript" src="/system_js/xheditor-1.1.13/xheditor-1.1.13-zh-cn.min.js"></script>
    <script type="text/javascript">
        $(function () {
            var siteid = {siteid};
            var userid = {userid};
            if (!Request["id"]) {
                var today = new Date();
                var month = today.getMonth() + 1;
                var s_date = today.getFullYear() + "-" + month + "-" + today.getDate();
                var s_hour = today.getHours() < 10 ? "0" + today.getHours() : today.getHours();
                var s_minute = today.getMinutes() < 10 ? "0" + today.getMinutes() : today.getMinutes();
                var s_second = today.getSeconds() < 10 ? "0" + today.getSeconds() : today.getSeconds();
                $("#f_enddate").val(s_date);

                var d = new Date();
                d.setMonth(d.getMonth() + 4);
                var news_date = d.getFullYear() + "-" + d.getMonth() + "-" + d.getDate();
                $("#f_enddate").val(news_date);

                //$("#f_createdate").val(s_date + " " + s_hour + ":"+s_minute+":"+s_second );
            }

            var window_h = Request["height"];
            if (!window_h || window_h <= 0) {
                window_h = 600;
            }

            //按用户名进行查找
            $("#searchsub").click(function () {
                var usernamestr = $("#username").val();
                if (usernamestr.length >= 1) {
                    usernamestr = encodeURIComponent(usernamestr);
                    $.ajax({
                        url: "/user/index.php",
                        data: {a: "searchforjson", siteid: siteid, username: usernamestr},
                        dataType: "jsonp",
                        jsonp: "jsonpcallback",
                        success: function (data) {
                            var listcontent = '';
                            $.each(data, function (i, v) {
                                if (parseInt(v["userid"]) > 0) {
                                    listcontent = listcontent + '<span class="spancss" onclick=changeparent("' + v["userid"] + '","' + v["username"] + '")>' + v["username"] + ' </span><br>';
                                }
                            });
                            $("#parentidlist").html(listcontent);
                        }
                    });
                } else {
                    alert("用户名长度需大于或等于2！");
                }
            });
        });

        //显示用户名查找内容
        function editparent() {
            $("#searchparent").css("display", "inline");
        }

        //更改用户名推荐人
        function changeparent(userid, username) {
            //alert(userid+"@@"+username);
            $('#parentname').val(username);
            $('#f_parentid').val(userid);
            $('#parentname').attr("disabled");
            $('#parentname').removeAttr("disabled");

        }
        function submitForm(continueCreate) {
            if ($('#f_ChannelName').val() == '') {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("请输入频道名称");
            } else if ($('#f_ChannelIntro').text().length > 1000) {
                $("#dialog_box").dialog({width: 300, height: 100});
                $("#dialog_content").html("频道简介不能超过1000个字符");
            } else {
                if (continueCreate == 1) {
                    $("#CloseTab").val("0");
                } else {
                    $("#CloseTab").val("1");
                }
                $('#mainForm').submit();
            }
        }

        function sub() {
            if ($('#f_username').val() == '') {
                alert('请输入会员账号！');
                return;
            } else {
                if ($('#f_userpass').val().length < 6) {
                    alert('密码不能少于6位！');
                    return;
                } else {
                    //$('#f_usertype').val($("#f_usergroupid").val());
                    $('#mainform').submit();
                }
            }
        }
    </script>
    <style type="text/css">
        #userlist {
            border: solid 1px #F1F1F1;
            padding: 3px;
            width: 300px;
            LINE-HEIGHT: 30px;
            OVERFLOW: auto;
        }

        #userlist .spancss {
            border-bottom: solid 1px #8F8FBD;
            cursor: pointer;
            font-size: 13px;
            LINE-HEIGHT: 30px;
            text-decoration: none;
            padding-left: 6px;
        }

        .spancss {
            border-bottom: solid 1px #8F8FBD;
            cursor: pointer;
            font-size: 13px;
            LINE-HEIGHT: 30px;
            text-decoration: none;
            padding-left: 6px;
        }

    </style>
</head>
<body>
<div style="margin: 0 auto;">
    <form id="mainForm" enctype="multipart/form-data" action="/default.php?secu=manage&mod=user&m={method}&user_id={userid}&site_id={siteid}"
          method="post">
        <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr style="display: none">
                <td class="spe_line" height="30" align="right">ID：</td>
                <td class="spe_line">
                    <input name="f_userid" id="f_userid" value="{userid}" type="text" style="width: 60px;"/>
                 </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right">帐号：</td>
                <td class="spe_line">
                    <input name="f_username" id="f_username" value="{username}" maxlength="100" type="text" class="inputbox" style=" width: 300px;"/></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right">会员密码：</td>
                <td class="spe_line" title="{userpass}">
                    <input name="f_userpass" id="f_userpass" value="{userpass}" type="text" class="inputbox" style=" width: 300px;"/>(注:不能少于6位)
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right">注册时间：</td>
                <td class="spe_line">
                    <input class="Wdate" id="f_createdate" name="f_createdate" type="text" onClick="WdatePicker()" value="{createdate}" style=" width: 210px;font-size:14px;">
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right">注册IP：</td>
                <td class="spe_line" title="{userpass}">
                    <input name="f_regip" id="f_regip" value="{regip}" type="text"  class="inputbox" style=" width: 300px;"/></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right">推荐用户名：</td>
                <td class="spe_line" title="{parentid}">
                    <input name="parentname" id="parentname" value="{parentname}" type="text" class="inputbox" style=" width: 300px;"/>
                    <input name="f_parentid" id="f_parentid" value="{parentid}" type="hidden"/>&nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="btn" value="修改推荐人" type="button"  onclick="editparent()"/></td>
            </tr>
            <tr>
                <td class="spe_line">

                </td>
                <td class="spe_line">
                    <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0" id="searchparent"
                           style="display:none;">
                        <tr>
                            <td height="30">
                                请输入用户账号进行查找：
                                <input name="username" id="username" value="" type="text" class="inputbox" style=" width: 200px;"/>&nbsp;&nbsp;&nbsp;&nbsp;
                                <input id="searchsub" name="searchsub" class="btn" value="查 找" type="button"/>
                            </td>
                        </tr>
                        <tr>
                            <td height="30">
                                <div id="userlist">
                                    <div id="parentidlist"></div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right">会员状态：</td>
                <td class="spe_line">
                    <select id="f_state" name="f_state">
                        <option value="0"
                        {s_state_0}>开启</option>
                        <option value="10"
                        {s_state_10}>未激活</option>
                        <option value="20"
                        {s_state_20}>被推荐，未激活</option>
                        <option value="30"
                        {s_state_30}>已激活，未审核</option>
                        <option value="100"
                        {s_state_100}>停用</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td colspan="2" height="30" align="center">
                    <input class="btn" value="确 认" type="button" onclick="sub()"/>
                    <input class="btn" value="确认并关闭" type="button" onclick="submitForm(0)"/>
                    <input class="btn" value="取 消" type="button" onclick="closeTab()"/>
                </td>
            </tr>
            <tr>
                <td colspan="2" height="30" align="left">
                    <a href="../user/index.php?a=userinfo&m=edit&userid={userid}&siteid={siteid}">[编辑此会员详细信息]</a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="../user/index.php?a=smsthird&m=sendsms&userid={userid}&siteid={siteid}">[发送手机短信息]</a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="../user/index.php?a=usermail&m=sendmail&userid={userid}&siteid={siteid}">[发送电子邮件]</a>
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>
