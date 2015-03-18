<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript" src="/system_js/manage/product/product.js"></script>
</head>
<body>
<div id="dialog_resultbox" title="" style="display: none;">
    <div id="result_table" style="font-size: 14px;">
        <iframe id="dialog_frame" src=""  style="border: 0; " width="100%" height="460"></iframe>
    </div>
</div>
<div class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn">
                <input id="btn_create" class="btn2" value="新建产品" title="在本频道新建产品" type="button"/>
                <input id="btn_move" class="btn2" value="移动" idvalue="{ChannelId}" title="移动本频道文档至其它频道，请先在下面文档中勾选需要移动的文档" type="button"/>
            </td>
            <td style="text-align: right; margin-right: 8px;">
                <div id="search_box">
                    <label for="search_type_box"></label><select name="search_type_box" id="search_type_box" style="display: none">
                        <option value="default">基本搜索</option>
                        <option value="source">来源搜索</option>
                    </select>
                    <label for="search_key"></label><input type="text" id="search_key" name="search_key" class="input_box"/>
                    <input id="btn_search" class="btn2" value="查 询" type="button"/>
                    <span id="search_type" style="display: none"></span>
                    <input id="btn_view_self" class="btn2" value="只看本人" title="只查看当前登录的管理员录入的产品" type="button"/>
                    <input id="btn_view_all" class="btn2" value="查看全部" title="查看全部的产品" type="button"/>
                </div>
            </td>
        </tr>
    </table>
    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
        <tr class="grid_title">
            <td style="width: 30px; text-align: center; cursor: pointer;" id="btn_select_all">全</td>
            <td style="width: 40px; text-align: center;">编辑</td>
            <td style="width: 40px; text-align: center;">状态</td>
            <td style="width: 40px;"></td>
            <td style="width: 20px;"></td>
            <td style="width: 150px;">产品编号</td>
            <td>产品名称</td>
            <td style="width: 36px;"></td>
            <td id="btn_sort" class="grid_can_sort" title="点击更改排序" style="width: 80px; text-align: center;">
                排序<img id="btn_sort_down" src="/system_template/{template_name}/images/manage/arrow1.gif" alt="down" /><img id="btn_sort_up" style="display:none;" src="/system_template/{template_name}/images/manage/arrow2.gif" alt="up" />
            </td>
            <td style="width: 100px;">显示售价</td>
            <td id="btn_sort_by_sale_count" class="grid_can_sort" title="按销售数量排序" style="width: 100px;">
                销售数量<img id="btn_sort_by_sale_count_down" src="/system_template/{template_name}/images/manage/arrow1.gif" alt="down" /><img id="btn_sort_by_sale_count_up" style="display:none;" src="/system_template/{template_name}/images/manage/arrow2.gif" alt="up" />
            </td>
            <td style="width: 50px; text-align: center;">热门</td>
            <td style="width: 50px; text-align: center;">新品</td>
            <td style="width: 180px;text-align: center;">创建时间</td>
            <td style="width: 100px;">发布人</td>
            <td style="width: 40px;"></td>
        </tr>
    </table>
    <ul id="sort_grid">
        <icms id="product_list" type="list">
            <item>
                <![CDATA[
                <li id="sort_{f_ProductId}">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item">
                            <td class="spe_line2" style="width:30px;text-align:center;">
                                <label>
                                    <input class="input_select" type="checkbox" name="input_select" value="{f_ProductId}"/>
                                </label></td>
                            <td class="spe_line2" style="width:40px;text-align:center;"><img class="btn_modify" src="/system_template/{template_name}/images/manage/edit.gif" idvalue="{f_ProductId}" alt="编辑"/></td>
                            <td class="spe_line2" style="width:40px;text-align:center;"><span class="span_state" id="{f_ProductId}">{f_State}</span></td>
                            <td class="spe_line2" style="width:40px;text-align:center;">
                                <img class="btn_change_state" src="/system_template/{template_name}/images/manage/change_state.gif" idvalue="{f_ProductId}" title="改变产品状态" alt="改变状态"/>
                                <div class="state_box" style="display: none;" id="div_state_box_{f_ProductId}" title="">
                                <div style="float:right;"><img style="vertical-align:top;" class="btn_close_box" idvalue="{f_ProductId}" alt="关闭" title="关闭" src="/system_template/{template_name}/images/manage/close3.gif"/></div>
                                <div style="clear:both;">
                                    <div style="" class="product_set_state" statevalue="0" idvalue="{f_ProductId}">启用</div>
                                    <div style="" class="product_set_state" statevalue="100" idvalue="{f_ProductId}">停用</div>
                                    <div class="spe"></div>
                                </div>
                                </div>
                            </td>
                            <td class="spe_line2" style="width:20px;text-align:center;"><img class="btn_preview" src="/system_template/{template_name}/images/manage/preview.gif" idvalue="{f_ProductId}" alt="预览" title="预览文档"/></td>
                            <td class="spe_line2" style="width:150px;text-align:left;"><a target="_blank" href="/default.php?&mod=product&a=detail&product_id={f_ProductId}"><span style="">{f_ProductNumber}</span></a></td>
                            <td class="spe_line2" style="text-align:left;"><a target="_blank" href="/default.php?&mod=product&a=detail&product_id={f_ProductId}"><span style="">{f_ProductName}</span></a></td>
                            <td class="spe_line2" style="width:36px;text-align:center;"><img style="cursor:pointer;" class="btn_up" src="/system_template/{template_name}/images/manage/arr_up.gif" idvalue="{f_ProductId}" title="向上移动" alt="向上"/><img style="cursor:pointer;" class="btn_down" src="/system_template/{template_name}/images/manage/arr_down.gif" idvalue="{f_ProductId}" title="向下移动" alt="向下"/></td>
                            <td class="spe_line2" style="width:80px;text-align:center;" title="排序数字，越大越靠前">{f_Sort}</td>
                            <td class="spe_line2" style="width:100px;" title="">￥<span class="show_price">{f_SalePrice}</span></td>
                            <td class="spe_line2" style="width:100px;" title="">{f_SaleCount}</td>
                            <td class="spe_line2" style="width:50px;text-align:center;" title="是否热门，用在特定的模板中">{f_IsHot}</td>
                            <td class="spe_line2" style="width:50px;text-align:center;" title="是否最新，用在特定的模板中">{f_IsNew}</td>
                            <td class="spe_line2" style="width:180px;text-align:center;" title="文档创建时间">{f_CreateDate}</td>
                            <td class="spe_line2" style="width:100px;text-align:center;" title="发布人：{f_ManageUserName}">{f_ManageUserName}</td>
                            <td class="spe_line2" style="width:40px;text-align:center;"><img class="btn_manage_pic" src="/system_template/{template_name}/images/manage/pic.gif" idvalue="{f_ProductId}" alt="{f_ProductName}" title="产品图片管理"/> <img class="btn_manage_comment" src="/system_template/{template_name}/images/manage/comment.gif" idvalue="{f_ProductId}" alt="评论管理" title="产品的评论管理"/></td>
                        </tr>
                    </table>
                </li>
                ]]>
            </item>
        </icms>
    </ul>
    <div>{pager_button}</div>
</div>
</body>
</html>
