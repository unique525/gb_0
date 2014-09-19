<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title></title>
        {common_head}
        <script type="text/javascript">
            function sub()
            {
                var ProductPicTag= $('#f_ProductPicTag').val();
                if(ProductPicTag == ''){
                    alert('请输入产品图片类别！');
                }
                else if(ProductPicTag.length>50){
                    alert('产品图片类别字符长度不能超过50个字节！');
                }
                else {$('#mainForm').submit();}
            }
        </script>
    </head>
    <body>
    {common_body_deal}
        <form id="mainForm" enctype="multipart/form-data" action="/default.php?secu=manage&mod=product_pic&m={method}&product_id={ProductId}&product_pic_id={ProductPicId}&p={PageIndex}" method="post">
            <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr style="display: none">
                    <td class="spe_line" height="30" align="right"></td>
                    <td class="spe_line">
                        <input type="hidden" id="f_ManageUserId" name="f_ManageUserId" value="{ManageUserId}" />
                        <input type="hidden" id="f_ProductId" name="f_ProductId" value="{ProductId}" />
                        <input type="hidden" id="f_CreateDate" name="f_CreateDate" value="{CreateDate}" />

                    </td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right"><label for="f_ProductPicTag">类别：</label></td>
                     <td class="spe_line" title="{ProductPicTag}"><input name="f_ProductPicTag" id="f_ProductPicTag" value="{ProductPicTag}" type="text" class="input_box" style=" width: 300px;" /></td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right"><label for="file_pic">图片</label></td>
                    <td class="spe_line">
                        <input id="file_pic" name="file_pic" type="file" class="input_box"
                               style="width:400px;background:#ffffff;margin-top:3px;"/>
                        <span id="preview_title_pic" class="show_title_pic" idvalue="{UploadFileId}" style="cursor:pointer">[预览]</span>
                    </td>
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
                    <td colspan="2" height="30" align="center">
                        <input class="btn" value="确 认" type="button" onclick="sub()" />
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>
