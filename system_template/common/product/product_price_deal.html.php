<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title></title>
        {common_head}
        <script type="text/javascript">
            $(function(){
                
                if(!Request["product_price_id"]){
                }
            });
            function sub()
            {
                var productPriceValue= $('#f_ProductPriceValue').val();
                var productCount= $('#f_ProductPriceCount').val();
                var productUnit= $('#f_ProductUnit').val();
                if(productPriceValue == ''){
                    $("#dialog_box").dialog({width: 300, height: 100});
                    $("#dialog_content").html("请输入产品价格");
                }
                else if(productCount == ''){
                    $("#dialog_box").dialog({width: 300, height: 100});
                    $("#dialog_content").html("请输入产品库存数量");
                }
                else if(productUnit == ''){
                    $("#dialog_box").dialog({width: 300, height: 100});
                    $("#dialog_content").html("请输入产品单位");
                }
                else {$('#mainForm').submit();}
            }
        </script>
    </head>
    <body>
        {common_body_deal}
        <form id="mainForm" action="/default.php?secu=manage&mod=product_price&m={method}&product_id={ProductId}&product_price_id={ProductPriceId}&p={PageIndex}" method="post">
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
                    <td class="spe_line" height="30" align="right"><label for="f_ProductPriceValue">价格：</label></td>
                     <td class="spe_line" title="{ProductPriceValue}"><input name="f_ProductPriceValue" id="f_ProductPriceValue" value="{ProductPriceValue}" type="text" class="input_price" style=" width: 100px;" /></td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right"><label for="f_ProductCount">库存数量：</label></td>
                    <td class="spe_line" title="{ProductCount}"><input name="f_ProductCount" id="f_ProductCount" value="{ProductCount}" type="text" class="input_number" style=" width: 100px;" /></td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right"><label for="f_ProductUnit">单位：</label></td>
                    <td class="spe_line" title="{ProductUnit}"><input name="f_ProductUnit" id="f_ProductUnit" value="{ProductUnit}" type="text" class="input_box" style=" width: 300px;" /></td>
                </tr>
                <tr>
                    <td class="spe_line" height="30" align="right"><label for="f_ProductPriceIntro">价格说明：</label></td>
                    <td class="spe_line" title="{ProductPriceIntro}"><input name="f_ProductPriceIntro" id="f_ProductPriceIntro" value="{ProductPriceIntro}" type="text" class="input_box" style=" width: 300px;" /></td>
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
