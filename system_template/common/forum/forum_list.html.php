<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        {common_head}
        <script type="text/javascript" src="/system_js/manage/forum/forum.js"></script>
    </head>
    <body>
        <div class="div_list">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td id="td_main_btn">
                        <input id="btn_create" class="btn2" value="新建版块分区" title="新建版块分区，版块分区下需要建立二级版块才能正常使用" type="button"/>
                    </td>
                </tr>
            </table>
            <table class="grid" width="100%" cellpadding="0" cellspacing="0">
                <tr class="grid_title">
                    <td style="width: 30px; text-align: center; cursor: pointer;" id="btn_select_all">全</td>
                    <td style="width: 40px; text-align: center;">编辑</td>
                    <td style="width: 90px; text-align: center;">状态</td>
                    <td style="width: 40px;"></td>
                    <td>名称</td>
                    <td style="width: 50px; text-align: center;">排序</td>
                    <td style="width: 120px;">版主</td>
                </tr>
            </table>
            <ul id="sort_grid">
                {ForumList}
            </ul>
        </div>
    </body>
</html>
