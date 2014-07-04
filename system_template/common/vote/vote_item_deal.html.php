<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    {common_head}
        <script type="text/javascript">
            function sub()
            {
                if($('#f_VoteItemTitle').val() == ''){
                    alert('请填写题目名称！');
                }
                else if($('#f_VoteItemTitle').val().length>500){
                    alert('题目名称长度不能超过500个字节！');
                }
                else {$('#mainForm').submit();}
            }
        </script>
    </head>
    <body>
        <form id="mainForm" action="default.php?secu=manage&mod=vote_item&m={method}&vote_id={VoteId}&vote_item_id={VoteItemId}&p={PageIndex}" method="post">
            <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr style="display: none">
                    <td class="spe_line" height="30" align="right"></td>
                    <td class="spe_line">
                        <input type="hidden" id="f_VoteId" name="f_VoteId" value="{VoteId}" />
                        <input type="hidden" id="f_VoteItemId" name="f_VoteItemId" value="{VoteItemId}" />
                    </td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right">题目名称：</td>
                    <td class="spe_line" title="{VoteItemTitle}"><input name="f_VoteItemTitle" id="f_VoteItemTitle" value="{VoteItemTitle}" type="text" class="input_box" style=" width: 300px;" /></td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right">排序：</td>
                    <td class="spe_line"><input name="f_sort" id="f_sort" value="{sort}" type="text" class="input_number" style=" width: 60px;" />(注:输入数字,数值越大越靠前)</td>
                </tr>
                <tr>
                    <td class="spe_line" height="60" align="right">题目简介：</td>
                    <td class="spe_line" title="{voteIntro}"><input name="f_voteIntro" id="f_voteIntro" value="{voteIntro}" type="text" class="input_box" style=" width: 300px;" /></td>
                </tr>

                <tr>
                    <td class="spe_line" height="30" align="right">状态：</td>
                    <td class="spe_line">
                        <select id="f_state" name="f_state">
                            <option value="0" {s_state_0}>启用</option>
                            <option value="100" {s_state_100}>停用</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td class="spe_line" height="30" align="right">是否多选：</td>
                    <td class="spe_line">
                        <select id="f_VoteItemType" name="f_VoteItemType">
                            <option value="0" {s_VoteItemType_0}>单选</option>
                            <option value="1" {s_VoteItemType_1}>多选</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right">最小选择数：</td>
                    <td class="spe_line"><input name="f_SelectNumMin" id="f_SelectNumMin" value="{SelectNumMin}" type="text" class="input_number" style=" width: 60px;" />(注:为0表示不限制)</td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right">最大选择数：</td>
                    <td class="spe_line"><input name="f_SelectNumMax" id="f_SelectNumMax" value="{SelectNumMax}" type="text" class="input_number" style=" width: 60px;" />(注:为0表示不限制)</td>
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
