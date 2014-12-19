<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    {common_head}


    <script type="text/javascript">
        $("document").ready(function () {

            $("#btn_create").click(function (event) {
                event.preventDefault();
                parent.G_TabUrl = '/default.php?secu=manage&mod=manage_user_group&m=create';
                parent.G_TabTitle =  '新增分组';
                parent.addTab();
            });

            $(".btn_modify").click(function (event) {
                event.preventDefault();
                var manageUserGroupId=$(this).attr("idvalue");
                var manageUserGroupName=$(this).attr("title");
                parent.G_TabUrl = '/default.php?secu=manage&mod=manage_user_group&m=modify' + '&manage_user_group_id=' + manageUserGroupId + '';
                parent.G_TabTitle = manageUserGroupName + '-编辑';
                parent.addTab();
            });

            //格式化状态
            $(".span_state").each(function(){
                $(this).html(formatState($(this).text()));
            });

            //开启
            $(".img_open").click(function(){
                var manageUserGroupId = parseInt($(this).attr("idvalue"));
                var state = 0; //开启状态
                modifyState(manageUserGroupId, state);
            });
            //停用
            $(".img_close").click(function(){
                var manageUserGroupId = parseInt($(this).attr("idvalue"));
                var state = 100; //开启状态
                modifyState(manageUserGroupId, state);
            });
        });



        function modifyState(manageUserGroupId,state){
            if(manageUserGroupId>0){
                $.ajax({
                    type: "get",
                    url: "/default.php?secu=manage&mod=manage_user_group&m=async_modify_state",
                    data: {
                        manage_user_group_id: manageUserGroupId,
                        state:state
                    },
                    dataType: "jsonp",
                    jsonp: "jsonpcallback",
                    success: function(data) {
                        $("#span_state_"+manageUserGroupId).html(formatState(state));
                    }
                });
            }
        }

        /**
         * 格式化状态值
         * @return {string}
         */
        function formatState(state){
            state = state.toString();
            switch (state){
                case "0":
                    return "启用";
                    break;
                case "100":
                    return "<"+"span style='color:#990000'>停用<"+"/span>";
                    break;
                default :
                    return "未知";
                    break;
            }
        }
    </script>
</head>
<body>
<div class="div_list">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_main_btn">
                <input id="btn_create" class="btn2" value="新建分组" title="新建分组" type="button"/>
            </td>
            <td style="text-align: right; margin-right: 8px;">
                <div id="search_box">
                    <label for="search_type_box"></label>
                    <select name="search_type_box" id="search_type_box" style="display: none">
                        <option value="default">基本搜索</option>
                    </select>
                    <label for="search_key"></label><input type="text" id="search_key" name="search_key" class="input_box"/>
                    <input id="btn_search" class="btn2" value="查 询" type="button"/>
                    <span id="search_type" style="display: none"></span>
                </div>
            </td>
        </tr>
    </table>
    <table class="grid" width="100%" cellpadding="0" cellspacing="0">
        <tr class="grid_title">
            <td style="width: 30px; text-align: center; cursor: pointer;" id="btn_select_all">全</td>
            <td style="width: 40px; text-align: center;">编辑</td>
            <td>分组名称</td>
            <td style="width: 70px; text-align: center;">排序号</td>
            <td style="width: 180px;text-align:center;">创建时间</td>
            <td style="width: 40px; text-align: center;">状态</td>
            <td style="width: 80px;text-align:center;">启用&nbsp;&nbsp;停用</td>
            <td style="width: 200px;text-align:center;">相关管理</td>
        </tr>
    </table>
    <ul id="sort_grid">
        <icms id="manage_user_group_list" type="list">
            <item>
                <![CDATA[
                <li id="sort_{f_ManageUserGroupId}">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr class="grid_item">
                            <td class="spe_line2" style="width:30px;text-align:center;"><label>
                                    <input class="input_select" type="checkbox" name="input_select"
                                           value="{f_ManageUserGroupId}"/>
                                </label></td>
                            <td class="spe_line2" style="width:40px;text-align:center;"><img class="btn_modify" title="{f_ManageUserGroupName}" style="cursor:pointer;" src="/system_template/{template_name}/images/manage/edit.gif" idvalue="{f_ManageUserGroupId}" alt="编辑"/></td>
                            <td class="spe_line2">{f_ManageUserGroupName}</td>
                            <td class="spe_line2" style="width:70px;text-align:center;" title="文档的排序数字，越大越靠前">{f_Sort}</td>
                            <td class="spe_line2" style="width:180px;text-align:center;" title="站点创建时间">{f_CreateDate}</td>
                            <td class="spe_line2" style="width:40px;text-align:center;"><span id="span_state_{f_ManageUserGroupId}" class="span_state" idvalue="{f_ManageUserGroupId}">{f_State}</span></td>
                            <td class="spe_line2" style="width:80px;text-align:center;">
                                <img class="img_open" idvalue="{f_ManageUserGroupId}" src="/system_template/{template_name}/images/manage/start.jpg" style="cursor:pointer"/>&nbsp;&nbsp;&nbsp;&nbsp;
                                <img class="img_close" idvalue="{f_ManageUserGroupId}" src="/system_template/{template_name}/images/manage/stop.jpg" style="cursor:pointer"/>
                            </td>
                            <td class="spe_line2" style="width:200px;text-align:center;">
                                <a href="/default.php?secu=manage&mod=manage_user_authority&m=set_by_manage_user_group&manage_user_group_id={f_ManageUserGroupId}&manage_user_group_name={f_ManageUserGroupName}">设置权限</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="/default.php?secu=manage&mod=manage_user&m=list&manage_user_group_id={f_ManageUserGroupId}">所属管理员</a>
                            </td>
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
