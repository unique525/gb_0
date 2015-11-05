<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    {common_head}
        <script type="text/javascript">
            $(function () {
                $("#f_PublishDate").datepicker({
                    dateFormat: 'yy-mm-dd',
                    numberOfMonths: 1,
                    showButtonPanel: true
                });

            });
            function sub()
            {
                var voteSelectItemTitle = $('#f_VoteSelectItemTitle');
                if(voteSelectItemTitle.val() == ''){
                    alert('请填写选项名称！');
                }
                else if(voteSelectItemTitle.val().length>500){
                    alert('选项名称过长请不要超过500个字节！');
                }
                else {$('#mainForm').submit();}
            }
        </script>
    </head>
    <body>
    {common_body_deal}
        <form id="mainForm" enctype="multipart/form-data"  action="/default.php?secu=manage&mod=vote_select_item&m={method}&vote_id={VoteId}&vote_item_id={VoteItemId}&vote_select_item_id={VoteSelectItemId}&p={PageIndex}" method="post">

            <div id="tabs" style="margin-left:4px;">
                <ul>
                    <li><a href="#tabs-1">基本参数</a></li>
                    <li><a href="#tabs-2">数字报稿件参数</a></li>
                </ul>
                <div id="tabs-1">
            <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr style="display: none">
                    <td class="spe_line" height="30" align="right"></td>
                    <td class="spe_line">
                        <input type="hidden" id="f_VoteSelectItemId" name="f_VoteSelectItemId" value="{VoteSelectItemId}" />
                        <input type="hidden" id="f_VoteItemId" name="f_VoteItemId" value="{VoteItemId}" />
                    </td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right"><label for="f_VoteSelectItemTitle">选项名称：</label></td>
                     <td class="spe_line"><input name="f_VoteSelectItemTitle" id="f_VoteSelectItemTitle" value="{VoteSelectItemTitle}" type="text" class="input_box" style=" width: 300px;" /></td>
                </tr>
                <tr>
                    <td class="" style="width:60px;height:30px;text-align: right;">题图1：</td>
                    <td class="" style="text-align: left">
                        <input id="file_title_pic_1" name="file_title_pic_1" type="file" class="input_box" style="width:auto;background:#ffffff;margin-top:3px;"/>
                        <span id="preview_title_pic1" class="show_title_pic" idvalue="{TitlePic1UploadFileId}" style="cursor:pointer">[预览]</span>
                    </td>
                </tr>
                <tr>
                    <td class="spe_line" style="width:200px;height:35px;text-align: right;"><label for="f_DirectUrl">直接转向网址:</label></td>
                    <td class="spe_line" style="text-align: left"><input type="text" class="input_box" id="f_DirectUrl" name="f_DirectUrl" value="{DirectUrl}" style=" width: 600px;font-size:14px;" maxlength="200" /> (设置直接转向网址后，文档将直接转向到该网址)</td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right"><label for="f_Sort">排序：</label></td>
                    <td class="spe_line"><input name="f_Sort" id="f_Sort" value="{Sort}" type="text" class="input_number" style=" width: 60px;" />(注:输入数字,数值越大越靠前)</td>
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
                   <td class="spe_line" height="30" align="right">票数：</td>
                   <td class="spe_line">{RecordCount}</td>
                </tr>
                <tr>
                   <td class="spe_line" height="30" align="right"><label for="f_AddCount">加票数：</label></td>
                    <td class="spe_line"><input name="f_AddCount" id="f_AddCount" value="{AddCount}" type="text" class="input_number" style=" width: 60px;" /></td>

                </tr>
            </table>
                    </div>
                <div id="tabs-2">
                    <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="spe_line" style="width:60px;height:30px;text-align: right;"><label for="f_Type">体裁：</label></td>
                            <td class="spe_line" style="text-align: left">
                                <input name="f_Type" id="f_Type" value="{Type}" type="text" class="input_number" style=" width: 160px;" />
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:60px;height:30px;text-align: right;"><label for="f_Author">作者：</label></td>
                            <td class="spe_line" style="text-align: left">
                                <input name="f_Author" id="f_Author" value="{Author}" type="text" class="input_number" style=" width: 160px;" />
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:60px;height:30px;text-align: right;"><label for="f_Editor">编辑：</label></td>
                            <td class="spe_line" style="text-align: left">
                                <input name="f_Editor" id="f_Editor" value="{Editor}" type="text" class="input_number" style=" width: 160px;" />
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:60px;height:30px;text-align: right;"><label for="f_PublishDate">日期：</label></td>
                            <td class="spe_line" style="text-align: left">
                                <input name="f_PublishDate" id="f_PublishDate" value="{PublishDate}" type="text" class="input_number" style=" width: 160px;" />
                            </td>
                        </tr>
                        <tr>
                            <td class="spe_line" style="width:60px;height:30px;text-align: right;"><label for="f_PageNo">版次：</label></td>
                            <td class="spe_line" style="text-align: left">
                                <input name="f_PageNo" id="f_PageNo" value="{PageNo}" type="text" class="input_number" style=" width: 160px;" />
                            </td>
                        </tr>
                    </table>
                </div>

                <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td colspan="2" height="30" align="center">
                        <input class="btn" value="确 认" type="button" onclick="sub()" />
                    </td>
                </tr>
                </table>

        </form>
    </body>
</html>
