<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    {common_head}
        <script type="text/javascript">
            function sub()
            {
                var paramTypeName= $('#f_ParamTypeName').val();
                if(paramTypeName == ''){
                    alert('请填写产品参数名称！');
                }
                else if(paramTypeName.length>50){
                    alert('产品参数名称长度不能超过50个字节！');
                }
                else {$('#mainForm').submit();}
            }
        </script>
    </head>
    <body>
        <form id="mainForm" action="/default.php?secu=manage&mod=product_param_type&m={method}&product_param_type_class_id={ProductParamTypeClassId}&product_param_type_id={ProductParamTypeId}&p={PageIndex}" method="post">
            <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                <tr style="display: none">
                    <td class="spe_line" height="30" align="right"></td>
                    <td class="spe_line">
                        <input type="hidden" id="f_ProductParamTypeClassId" name="f_ProductParamTypeClassId" value="{ProductParamTypeClassId}" />
                        <input type="hidden" id="f_ProductParamTypeId" name="f_ProductParamTypeId" value="{ProductParamTypeId}" />
                        <input type="hidden" id="f_CreateDate" name="f_CreateDate" value="{CreateDate}" />
                    </td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right"><label for="f_ParamTypeName">产品参数名称：</label></td>
                    <td class="spe_line" title="{ParamTypeName}"><input name="f_ParamTypeName" id="f_ParamTypeName" value="{ParamTypeName}" type="text" class="input_box" style=" width: 300px;" /></td>
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
                    <td class="spe_line" height="30" align="right"><label for="f_ParamValueType">参数类型：</label></td>
                    <td class="spe_line">
                        <select id="f_ParamValueType" name="f_ParamValueType">
                            <option value="0" selected>短字符串</option>
                            <option value="1">长字符串</option>
                            <option value="2">文本</option>
                            <option value="3">单精度</option>
                            <option value="4">双精度</option>
                            <option value="5">超链接</option>
                            <option value="6">下拉选择框</option>
                        </select>
                        {s_ParamValueType}
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
