<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}
    <script type="text/javascript">

        <!--


        -->

    </script>
</head>
<body>
<div class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn">
                <input id="btn_create" class="btn2" idvalue="{ChannelId}" value="新建图片轮换" title="在本频道新建图片轮换" type="button"/>
            </td>
            <td style="text-align: right; margin-right: 8px;">
                <div id="search_box">
                    <label for="search_type_box"></label><select name="search_type_box" id="search_type_box" style="display: none">
                    <option value="default">基本搜索</option>
                </select>
                    <label for="search_key"></label><input type="text" id="search_key" name="search_key" class="input_box"/>
                    <input id="btn_search" class="btn2" value="查 询" type="button"/>
                    <span id="search_type" style="display: none"></span>
                    <input id="btn_view_self" class="btn2" value="只看本人" title="只查看当前登录的管理员的文档" type="button"/>
                    <input id="btn_view_all" class="btn2" value="查看全部" title="查看全部的文档" type="button"/>
                </div>
            </td>
        </tr>
    </table>
    <ul id="sort_grid">
        <icms id="pic_slider_list" type="list">
            <item>
                <![CDATA[
                <li id="sort_{f_PicSliderId}" style="float:left;width:24.5%;">

                    <div>
                        <img class="btn_modify" src="/system_template/{template_name}/images/manage/edit.gif" idvalue="{f_PicSliderId}" alt="编辑"/>

                        <span class="span_state" id="span_state_{f_PicSliderId}">{f_State}</span>
                        <img class="btn_change_state" src="/system_template/{template_name}/images/manage/change_state.gif" idvalue="{f_PicSliderId}" title="改变文档状态" alt="改变状态"/>
                        <img class="btn_publish" src="/system_template/{template_name}/images/manage/publish.gif" idvalue="{f_PicSliderId}" title="发布文档" alt="发布"/>
                        <img class="btn_up" src="/system_template/{template_name}/images/manage/arr_up.gif" idvalue="{f_PicSliderId}" title="向上移动" alt="向上"/>
                        <img class="btn_down" src="/system_template/{template_name}/images/manage/arr_down.gif" idvalue="{f_PicSliderId}" title="向下移动" alt="向下"/>
                    </div>
                    <div><img src="{f_PicSliderUploadFileUrl}" alt="" /></div>
                    <div>{f_PicSliderTitle}</div>
                    <div>{f_CreateDate}</div>

                </li>
                ]]>
            </item>
        </icms>
        <li style="clear:left;"></li>
    </ul>
    <div>{pager_button}</div>
</div>
<div id="dialog_box" title="" style="display:none;">
    <div id="dialog_content">

    </div>
</div>
</body>
</html>
