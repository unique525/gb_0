<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title></title>
        {common_head}
        <script type="text/javascript">
            function sub()
            {
                var productParamTypeClassName= $('#f_ProductParamTypeClassName').val();
                if(productParamTypeClassName == ''){
                    alert('请输入产品参数类别名称！');
                }
                else if(productParamTypeClassName.length>100){
                    alert('产品参数类别名称不能超过100个字节！');
                }
                else {$('#mainForm').submit();}
            }
        </script>
    </head>
    <body>
        <form id="mainForm" action="/default.php?secu=manage&mod=product_param_type_class&m={method}&product_param_type_class_id={ProductParamTypeClassId}&p={PageIndex}" method="post">
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
                    <td class="spe_line" height="30" align="right"><label for="f_ProductParamTypeClassName">名称：</label></td>
                     <td class="spe_line" title="{ProductParamTypeClassName}"><input name="f_ProductParamTypeClassName" id="f_ProductParamTypeClassName" value="{ProductParamTypeClassName}" type="text" class="input_box" style=" width: 300px;" /></td>
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
