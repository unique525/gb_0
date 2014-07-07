<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    {common_head}
        <script type="text/javascript">
            function sub()
            {
                var voteItemTitle= $('#f_VoteItemTitle').val();
                if(voteItemTitle == ''){
                    alert('请填写题目名称！');
                }
                else if(voteItemTitle.length>500){
                    alert('题目名称长度不能超过500个字节！');
                }
                else {$('#mainForm').submit();}
            }
        </script>
    </head>
    <body>
        <form id="mainForm" action="/default.php?secu=manage&mod=vote_item&m={method}&vote_id={VoteId}&vote_item_id={VoteItemId}&p={PageIndex}" method="post">
            <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr style="display: none">
                    <td class="spe_line" height="30" align="right"></td>
                    <td class="spe_line">
                        <input type="hidden" id="f_VoteId" name="f_VoteId" value="{VoteId}" />
                        <input type="hidden" id="f_VoteItemId" name="f_VoteItemId" value="{VoteItemId}" />
                    </td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right"><label for="f_VoteItemTitle">题目名称：</label></td>
                    <td class="spe_line" title="{VoteItemTitle}"><input name="f_VoteItemTitle" id="f_VoteItemTitle" value="{VoteItemTitle}" type="text" class="input_box" style=" width: 300px;" /></td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right"><label for="f_Sort">排序：</label></td>
                    <td class="spe_line"><input name="f_Sort" id="f_Sort" value="{Sort}" type="text" class="input_number" style=" width: 60px;" />(注:输入数字,数值越大越靠前)</td>
                </tr>
                <tr>
                    <td class="spe_line" height="60" align="right"><label for="f_VoteIntro">题目简介：</label></td>
                    <td class="spe_line" title="{voteIntro}"><input name="f_VoteIntro" id="f_VoteIntro" value="{VoteIntro}" type="text" class="input_box" style=" width: 300px;" /></td>
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
                    <td class="spe_line" height="30" align="right"><label for="f_VoteItemType">是否多选：</label></td>
                    <td class="spe_line">
                        <select id="f_VoteItemType" name="f_VoteItemType">
                            <option value="0">单选</option>
                            <option value="1">多选</option>
                        </select>
                        {s_VoteItemType}
                    </td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right"><label for="f_SelectNumMin">最小选择数：</label></td>
                    <td class="spe_line"><input name="f_SelectNumMin" id="f_SelectNumMin" value="{SelectNumMin}" type="text" class="input_number" style=" width: 60px;" />(注:为0表示不限制)</td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right"><label for="f_SelectNumMax">最大选择数：</label></td>
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
