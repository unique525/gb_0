<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    {common_head}
        <script type="text/javascript">
            $(function(){
                var img = $("#img_title_pic");
                var previewImage = new Image();
                previewImage.src = img.attr("src");
                var tp = '{TitlePic}';
                $("#preview_titlepic").click(function() {
                    if(tp != ''){

                        $("#dialog_title_pic").dialog({
                            width : previewImage.width+30,
                            height : previewImage.height+50
                        });

                    }
                    else{
                        alert('还没有上传题图');
                    }
                });                
            });
            function sub()
            {
                if($('#f_VoteSelectItemTitle').val() == ''){
                    alert('请填写选项名称！');
                }
                else if($('#f_VoteSelectItemTitle').val().length>500){
                    alert('选项名称过长请不要超过500个字节！');
                }
                else {$('#mainForm').submit();}
            }
        </script>
    </head>
    <body>
        <form id="mainForm" enctype="multipart/form-data"  action="default.php?secu=manage&mod=vote_select_item&m={method}&vote_id={VoteId}&vote_item_id={VoteItemId}&vote_select_item_id={VoteSelectItemId}&p={PageIndex}" method="post">
            <div style="margin: 0 auto;margin-left: 10px;">                
                <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                    <tr style="display: none">
                        <td class="spe_line" height="30" align="right"></td>
                        <td class="spe_line">
                            <input type="hidden" id="f_VoteSelectItemId" name="f_VoteSelectItemId" value="{VoteSelectItemId}" />
                            <input type="hidden" id="f_VoteItemId" name="f_VoteItemId" value="{VoteItemId}" />
                        </td>
                    </tr>
                    <tr>
                        <td class="spe_line" height="30" align="right">选项名称：</td>
                         <td class="spe_line"><input name="f_VoteSelectItemTitle" id="f_VoteSelectItemTitle" value="{VoteSelectItemTitle}" type="text" class="input_box" style=" width: 300px;" /></td>
                    </tr>
                    <tr>
                        <td class="spe_line" style="width:200px;height:35px;text-align: right;">题图：</td>
                        <td class="spe_line" style="text-align: left">
                            <input id="file_title_pic" name="file_title_pic" type="file" class="input_box" style="width:300px; background: #ffffff; margin-top: 3px;" /> <span id="preview_title_pic" style="cursor:pointer">[预览]</span>
                            <div id="dialog_title_pic" title="题图预览（{TitlePic}）" style="display:none;">
                                <div id="pubtable">
                                    <table>
                                        <tr>
                                            <td><img id="img_title_pic" src="{TitlePic}" alt="title_pic" /></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="spe_line" style="width:200px;height:35px;text-align: right;">直接转向网址：</td>
                        <td class="spe_line" style="text-align: left"><input type="text" class="input_box" id="f_DirectUrl" name="f_DirectUrl" value="{DirectUrl}" style=" width: 600px;font-size:14px;" maxlength="200" /> (设置直接转向网址后，文档将直接转向到该网址)</td>
                    </tr>
                    <tr>
                        <td class="spe_line" height="30" align="right">排序：</td>
                        <td class="spe_line"><input name="f_sort" id="f_sort" value="{sort}" type="text" class="inputnumber" style=" width: 60px;" />(注:输入数字,数值越大越靠前)</td>
                    </tr>
                    
                    <tr>
                        <td class="spe_line" height="30" align="right">状态：</td>
                        <td class="spe_line">
                            <select id="f_State" name="f_State">
                                <option value="0" {s_State_0}>启用</option>
                                <option value="100" {s_State_100}>停用</option>
                            </select>
                        </td>
                    </tr>    
                    <tr>
                       <td class="spe_line" height="30" align="right">票数：</td>
                       <td class="spe_line">{RecordCount}</td>
                    </tr>
                    <tr>
                       <td class="spe_line" height="30" align="right">加票数：</td>
                        <td class="spe_line"><input name="f_AddCount" id="f_AddCount" value="{AddCount}" type="text" class="inputnumber" style=" width: 60px;" /></td>
                    
                    </tr>
                    <tr>
                        <td colspan="2" height="30" align="center">
                            <input class="btn" value="确 认" type="button" onclick="sub()" />
                        </td>
                    </tr>
                </table>
            </div>
        </form>
    </body>
</html>
