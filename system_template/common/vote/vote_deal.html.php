<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title></title>
        {common_head}
        <script type="text/javascript">
            $(function(){
                var inputBeginDate=$("#f_BeginDate");
                var inputEndDate=$("#f_EndDate");
                inputBeginDate.datepicker({
                    dateFormat: 'yy-mm-dd',
                    numberOfMonths: 1,
                    showButtonPanel: true
                });
                inputEndDate.datepicker({
                    dateFormat: 'yy-mm-dd',
                    numberOfMonths: 1,
                    showButtonPanel: true
                });
                
                if(!Request["vote_id"]){
                    var today = new Date();
                    var beginDate = formatDate(today,"yyyy-MM-dd");
                    var endDate = formatDate(today,"yyyy-MM-dd");
                    inputBeginDate.val(beginDate);
                    inputEndDate.val(endDate);
                }
            });
            function sub()
            {
                var voteTitle= $('#f_VoteTitle').val();
                if(voteTitle == ''){
                    alert('请定义标题！');
                }
                else if(voteTitle.length>100){
                    alert('定义标题过长请不要超过100个字节！');
                }
                else {$('#mainForm').submit();}
            }
        </script>
    </head>
    <body>
        <form id="mainForm" action="/default.php?secu=manage&mod=vote&m={method}&vote_id={VoteId}&p={PageIndex}" method="post">
            <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr style="display: none">
                    <td class="spe_line" height="30" align="right"></td>
                    <td class="spe_line">
                        <input type="hidden" id="f_ManageUserId" name="f_ManageUserId" value="{ManageUserId}" />
                        <input type="hidden" id="f_SiteId" name="f_SiteId" value="{SiteId}" />
                        <input type="hidden" id="f_ChannelId" name="f_ChannelId" value="{ChannelId}" />
                        <input type="hidden" id="f_CreateDate" name="f_CreateDate" value="{CreateDate}" />

                    </td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right"><label for="f_VoteTitle">标题：</label></td>
                     <td class="spe_line" title="{VoteTitle}"><input name="f_VoteTitle" id="f_VoteTitle" value="{VoteTitle}" type="text" class="input_box" style=" width: 300px;" /></td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right"><label for="f_Sort">排序：</label></td>
                    <td class="spe_line"><input name="f_Sort" id="f_Sort" value="{Sort}" type="text" class="input_number" style=" width: 60px;" />(注:输入数字,数值越大越靠前)</td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right"><label for="f_BeginDate">开始时间：</label></td>
                    <td class="spe_line">
                        <input type="text" class="input_box" id="f_BeginDate" name="f_BeginDate" value="{BeginDate}" style=" width: 90px;font-size:14px;" maxlength="10" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right"><label for="f_EndDate">到期时间：</label></td>
                    <td class="spe_line">
                        <input type="text" class="input_box" id="f_EndDate" name="f_EndDate" value="{EndDate}" style=" width: 90px;font-size:14px;" maxlength="10" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right"><label for="f_TemplateName">显示模式：</label></td>
                    <td class="spe_line">
                        <select id="f_TemplateName" name="f_TemplateName">
                            <option value="normal_1">普通模式一排一个</option>
                            <option value="normal_2">普通模式一排两个</option>
                            <option value="normal_3">普通模式一排三个</option>
                            <option value="normal_4">普通模式一排四个</option>
                            <option value="style1">样式一</option>
                            <option value="style2">样式二</option>
                            <option value="style3">样式三</option>
                            <option value="user_style1">登陆投票样式一</option>
                        </select>
                        {s_TemplateName}
                    </td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right"><label for="f_State">状态：</label></td>
                    <td class="spe_line">
                        <select id="f_State" name="f_State">
                            <option value="0">启用</option>
                            <option value="100">停用</option>
                        </select>
                        {s_State}
                    </td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right"><label for="f_IsCheckCode">是否启用验证码控制：</label></td>
                    <td class="spe_line">
                        <select id="f_IsCheckCode" name="f_IsCheckCode">
                            <option value="0">不启用</option>
                            <option value="1">启用</option>
                        </select>
                        {s_IsCheckCode}
                    </td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right"><label for="f_IpMaxCount">IP限制：</label></td>
                    <td class="spe_line"><input name="f_IpMaxCount" id="f_IpMaxCount" value="{IpMaxCount}" type="text" class="input_number" style=" width: 60px;" />(注:输入数字,设定每天同一IP最多投票数)</td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right"><label for="f_UserMaxCount">用户数限制：</label></td>
                    <td class="spe_line"><input name="f_UserMaxCount" id="f_UserMaxCount" value="{UserMaxCount}" type="text" class="input_number" style=" width: 60px;" />(注:输入数字,设定每天同一用户最多投票数，0表示不限制)</td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right"><label for="f_UserScoreNum">用户每票积分数：</label></td>
                    <td class="spe_line"><input name="f_UserScoreNum" id="f_UserScoreNum" value="{UserScoreNum}" type="text" class="input_number" style=" width: 60px;" />(注:输入数字,设定用户每投一票获得积分数，0表示不积分)</td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right"><label for="f_LimitUserGroupId">用户组限制：</label></td>
                    <td class="spe_line"><input name="f_LimitUserGroupId" id="f_LimitUserGroupId" value="{LimitUserGroupId}" type="text" class="input_number" style=" width: 60px;" />(注:只有该组用户才能参与)</td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right">票数：</td>
                    <td class="spe_line">{RecordCount}</td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right">加票数：</td>
                    <td class="spe_line">{AddCount}</td>
                </tr>
                <tr>
                    <td colspan="2" height="30" align="center">
                        <input class="btn" value="确 认" type="button" onclick="sub()" />
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>
