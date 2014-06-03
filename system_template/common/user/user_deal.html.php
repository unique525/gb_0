<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/user/user_manage.js"></script>
    <script type="text/javascript">
        $(function () {
            $("#f_CreateDate").datepicker({
                dateFormat: 'yy-mm-dd',
                numberOfMonths: 1,
                showButtonPanel: true
            });

        });



    </script>
    <style type="text/css">
        #UserList {
            border: solid 1px #F1F1F1;
            padding: 3px;
            width: 300px;
            LINE-HEIGHT: 30px;
            OVERFLOW: auto;
        }

        #UserList .span_css {
            border-bottom: solid 1px #8F8FBD;
            cursor: pointer;
            font-size: 13px;
            LINE-HEIGHT: 30px;
            text-decoration: none;
            padding-left: 6px;
        }

        .span_css {
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
    <form id="mainForm" enctype="multipart/form-data" action="/default.php?secu=manage&mod=user&m={method}&user_id={UserId}&site_id={SiteId}"
          method="post">
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="spe_line" height="40" align="right">
                    <input id="btn_ConfirmClose"  class="btn" value="确认并关闭" type="button" idvalue="0"/>
                    <input id="btn_ConfirmGoOn" class="btn" value="确认并继续" type="button" idvalue="1"/>
                    <input id="btn_Remove" class="btn" value="取 消" type="button"/>
                </td>
            </tr>
        </table>

        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr style="display: none">
                <td class="spe_line" height="30" align="right"><label for="f_UserId">会员Id：</label></td>
                <td class="spe_line">
                    <input name="f_UserId" id="f_UserId" value="{UserId}" type="text" style="width: 60px;"/>
                    <input id="OldUserName" name="OldUserName" type="hidden" value="{UserName}"/>
                    <input id="CloseTab" name="CloseTab" type="hidden" value="0"/>

                 </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_UserName">会员帐号：</label></td>
                <td class="spe_line">
                    <input name="f_UserName" id="f_UserName" value="{UserName}" maxlength="100" type="text" class="input_box" style=" width: 300px;"/></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_UserPass">会员密码：</label></td>
                <td class="spe_line" title="{UserPass}">
                    <input name="f_UserPass" id="f_UserPass" value="{UserPass}" type="text" class="input_box" style=" width: 300px;"/>(注:不能少于6位)
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_CreateDate">会员注册时间：</label></td>
                <td class="spe_line">
                    <input id="f_CreateDate" name="f_CreateDate" value="{CreateDate}" type="text" class="input_box" style="width:80px;"/>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_RegIp">会员注册IP：</label></td>
                <td class="spe_line">
                    <input name="f_RegIp" id="f_RegIp" value="{RegIp}" type="text"  class="input_box" style=" width: 300px;"/></td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_ParentName">会员推荐用户名：</label></td>
                <td class="spe_line" title="{ParentId}">
                    <input name="ParentName" id="ParentName" value="{ParentName}" type="text" class="input_box" style=" width: 300px;"/>
                    <input name="f_ParentId" id="f_ParentId" value="{ParentId}" type="hidden"/>&nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="btn" value="修改推荐人" type="button"  onclick="editParent()"/></td>
            </tr>
            <tr>
                <td class="spe_line">

                </td>
                <td class="spe_line">
                    <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0" id="searchParent"
                           style="display:none;">
                        <tr>
                            <td height="30">
                                <label for="f_SearchParent">请输入用户账号进行查找：</label>
                                <input name="UserName" id="UserName" value="" type="text" class="input_box" style=" width: 200px;"/>&nbsp;&nbsp;&nbsp;&nbsp;
                                <input id="SearchSub" name="SearchSub" class="btn" value="查 找" type="button"/>
                            </td>
                        </tr>
                        <tr>
                            <td height="30">
                                <div id="UserList">
                                    <div id="ParentIdList"></div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="spe_line" height="30" align="right"><label for="f_State">会员状态：</label></td>
                <td class="spe_line">
                    <select id="f_State" name="f_State">
                        <option value="0">开启</option>
                        <option value="10">未激活</option>
                        <option value="20">被推荐，未激活</option>
                        <option value="30">已激活，未审核</option>
                        <option value="100">停用</option>
                        {s_State}
                    </select>
                </td>
            </tr>

            <tr>
                <td colspan="2" height="30" align="center">
                    <input id="btn_ConfirmCloseTwo" class="btn" value="确认并关闭" type="button" idvalue="0"/>
                    <input id="btn_RemoveTwo" class="btn" value="取 消" type="button"/>
                </td>
            </tr>
            <tr>
                <td colspan="2" height="30" align="left">
                    <a href="/default.php?secu=manage&mod=userinfo&m=edit&user_id={UserId}&site_id={SiteId}">[编辑此会员详细信息]</a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="/default.php?secu=manage&mod=smsthird&m=sendsms&user_id={UserId}&site_id={SiteId}">[发送手机短信息]</a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="/default.php?secu=manage&mod=usermail&m=sendmail&user_id={UserId}&site_id={SiteId}">[发送电子邮件]</a>
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>
