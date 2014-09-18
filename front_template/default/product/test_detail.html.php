<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title></title>
    <link type="text/css" href="/system_template/default/images/jquery_ui/jquery-ui.min.css" rel="stylesheet" />
    <script type="text/javascript" src="/system_js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/system_js/common.js"></script>
    <script type="text/javascript" src="/system_js/jquery_ui/jquery-ui-1.8.2.custom.min.js"></script>
    <script type="text/javascript">
        function addCar(siteId,productId,buyCount,activeProductId)
        {
            var productPriceId=$("#select_price").val();
            alert(productPriceId);
        }
        function addFavorite(siteId,tableId,tableType,userFavoriteTitle,userFavoriteTag)
        {}

    </script>
</head>
<body>
<div>{ProductName}</div>
<icms id="product_param_type_class_{ChannelId}" type="product_param_type_class_list">
    <item>
        <![CDATA[
        <div class="main_line_title" style="font-size:14px">{f_ProductParamTypeClassName}</div>
        <div class="main_line_body">
            <icms_child id="product_param_type_{f_ProductParamTypeClassId}" relation_id="{ProductId}" type="product_param_type_list">
                <item_child>
                    [CDATA]
                    <div class="main_line_content">
                        <div class="main_line_content_left">{f_ParamTypeName}：</div>
                        <div class="main_line_content_right">{f_ParamTypeValue}</div>
                    </div>
                    [/CDATA]
                </item_child>
            </icms_child>
            <div class="spe"></div>
        </div>
        ]]>
    </item>
</icms>
<div>
    产品价格
    <select id="select_price">
        <icms id="{ProductId}" type="product_price_list">
            <item>
                <![CDATA[
                <option value="{f_ProductPriceId}">{f_ProductPriceValue}--{f_ProductPriceIntro}</option>
                ]]>
            </item>
        </icms>
    </select>
</div>
<div><input type="button" value="加入购物车" onclick="addCar('{SiteId}','{ProductId}','1','0')">&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="button" value="加入收藏" onclick="addFavorite('{SiteId}','{ProductId}','1','{ProductName}','产品')"></div>
</body>
</html>
