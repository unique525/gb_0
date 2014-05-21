<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}    <script type="text/javascript">
        $(function(){
            if(!Request["custom_form_id"]){
                var today = new Date();
                var month = today.getMonth()+1;
                var s_date = today.getFullYear()+"-"+month+"-"+today.getDate();
                var s_hour = today.getHours()<10?"0"+today.getHours():today.getHours();
                var s_minute = today.getMinutes()<10?"0"+today.getMinutes():today.getMinutes();
                var s_second = today.getSeconds()<10?"0"+today.getSeconds():today.getSeconds();

                $("#f_createdate").val(s_date + " " + s_hour + ":"+s_minute+":"+s_second );
            }
        });
        function sub()
        {
            if($('#f_custom_Form_Subject').val() == ''){
                alert('请输入表单名称');
                return;
            }
            else
            {
                $('#mainform').submit();
            }
        }

    </script>
</head>

<body>
<form id="mainform" action="default.php?secu=manage&mod=custom_form&m={method}&site_id={site_id}&channel_id={channel_id}&id={custom_form_id}&tab={tab}" method="post">
    <div style="margin:10px auto;margin-left: 10px;">
        <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="speline" height="30" align="right">表单名称：</td>
                <td class="speline">
                    <input name="f_customFormSubject" id="f_custom_Form_Subject" value="{customFormSubject}" type="text" class="inputbox" style=" width: 300px;" />
                    <input type="hidden" id="f_custom_form_id" name="f_customFormId" value="{custom_form_id}" />
                    <input type="hidden" id="f_channel_id" name="f_channelId" value="{channel_id}" />
                    <input type="hidden" id="f_manage_user_id" name="f_manageUserId" value="{manage_user_id}" />
                    <input type="hidden" id="f_createdate" name="f_createdate" value="{createdate}" />
                </td>
            </tr>
            <tr>
                <td class="speline" height="30" align="right">排序：</td>
                <td class="speline"><input name="f_sort" id="f_sort" value="{sort}" type="text" class="input_number" style=" width: 60px;" />(注:输入数字,数值越大越靠前)</td>
            </tr>
            <tr>
                <td class="speline" height="30" align="right">是否启用：</td>
                <td class="speline">
                    <select id="f_state" name="f_state" >
                        <option value="0" {s_state_0}>启用</option>
                        <option value="100" {s_state_100}>停用</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td colspan="2" height="30" align="center">
                    <input class="btn" value="确认" type="button" onclick="sub()" />
                </td>
            </tr>

        </table>

    </div>
</form>
</body>
</html>
