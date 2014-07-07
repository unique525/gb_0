<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title></title>
        {common_head}
        <script type="text/javascript">
            $(function(){
                
                $("#f_EndDate").datepicker({
                    dateFormat: 'yy-mm-dd',
                    numberOfMonths: 1,
                    showButtonPanel: true
                });
                
                $("#f_BeginDate").datepicker({
                    dateFormat: 'yy-mm-dd',
                    numberOfMonths: 1,
                    showButtonPanel: true
                });
                
                if(!Request["vote_id"]){
                    var today = new Date();
                    var beginDate = formatDate(today,"yyyy-MM-dd");
                    var endDate = formatDate(today,"yyyy-MM-dd");
                    $("#f_EndDate").val(endDate);
                    $("#f_BeginDate").val(beginDate);
                }
            });
            function sub()
            {
                var voteTitle=$('#f_VoteTitle').val();
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
                        <input type="hidden" id="f_AdminUserId" name="f_AdminUserId" value="{AdminUserId}" />
                        <input type="hidden" id="f_SiteId" name="f_SiteId" value="{SiteId}" />
                        <input type="hidden" id="f_ChannelId" name="f_ChannelId" value="{ChannelId}" />
                        <input type="hidden" id="f_CreateDate" name="f_CreateDate" value="{CreateDate}" />

                    </td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right">标题：</td>
                     <td class="spe_line" title="{VoteTitle}"><input name="f_VoteTitle" id="f_VoteTitle" value="{VoteTitle}" type="text" class="input_box" style=" width: 300px;" /></td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right">排序：</td>
                    <td class="spe_line"><input name="f_sort" id="f_sort" value="{sort}" type="text" class="input_number" style=" width: 60px;" />(注:输入数字,数值越大越靠前)</td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right">开始时间：</td>
                    <td class="spe_line">
                        <input type="text" class="input_box" id="f_BeginDate" name="f_BeginDate" value="{BeginDate}" style=" width: 90px;font-size:14px;" maxlength="10" readonly="readonly" />
                    </td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right">到期时间：</td>
                    <td class="spe_line">
                        <input type="text" class="input_box" id="f_EndDate" name="f_EndDate" value="{EndDate}" style=" width: 90px;font-size:14px;" maxlength="10" readonly="readonly" />
                    </td>
                </tr>
                                    <tr>
                    <td class="spe_line" height="30" align="right">显示模式：</td>
                    <td class="spe_line">
                        <select id="f_TempLateName" name="f_TempLateName">
                            <option value="normal_1" {s_TempLateName_normal_1}>普通模式一排一个</option>
                            <option value="normal_2" {s_TempLateName_normal_2}>普通模式一排两个</option>
                            <option value="normal_3" {s_TempLateName_normal_3}>普通模式一排三个</option>
                            <option value="normal_4" {s_TempLateName_normal_4}>普通模式一排四个</option>
                            <option value="style1" {s_TempLateName_style1}>样式一</option>
                            <option value="style2" {s_TempLateName_style2}>样式二</option>
                            <option value="style2" {s_TempLateName_style3}>样式三</option>
                            <option value="style2" {s_TempLateName_bar}>横条</option>
                            <option value="style2" {s_TempLateName_bar_2}>横条2</option>
                            <option value="user_style1" {s_TempLateName_user_style1}>登陆投票样式一</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right">状态：</td>
                    <td class="spe_line">
                        <select id="f_state" name="f_state">
                            <option value="0" {s_State_0}>启用</option>
                            <option value="100" {s_State_100}>停用</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right">是否启用验证码控制：</td>
                    <td class="spe_line">
                        <select id="f_IsCheckCode" name="f_IsCheckCode">
                            <option value="0" {s_IsCheckCode_0}>不启用</option>
                            <option value="1" {s_IsCheckCode_1}>启用</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right">IP限制：</td>
                    <td class="spe_line"><input name="f_IpMaxCount" id="f_IpMaxCount" value="{IpMaxCount}" type="text" class="input_number" style=" width: 60px;" />(注:输入数字,设定每天同一IP最多投票数)</td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right">用户数限制：</td>
                    <td class="spe_line"><input name="f_UserMaxCount" id="f_UserMaxCount" value="{UserMaxCount}" type="text" class="input_number" style=" width: 60px;" />(注:输入数字,设定每天同一用户最多投票数，0表示不限制)</td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right">用户每票积分数：</td>
                    <td class="spe_line"><input name="f_UserScoreNum" id="f_UserScoreNum" value="{UserScoreNum}" type="text" class="input_number" style=" width: 60px;" />(注:输入数字,设定用户每投一票获得积分数，0表示不积分)</td>
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
                        <input class="btn" value="确 认" type="button" onclick="sub()" /> <input class="btn" value="取 消" type="button" onclick="javascript:self.parent.tb_remove();" />
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>
